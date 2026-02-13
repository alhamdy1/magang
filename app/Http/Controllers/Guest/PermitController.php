<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuestPermitRequest;
use App\Models\Permit;
use App\Models\Document;
use App\Services\SecureFileUploadService;
use App\Services\PermitNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermitController extends Controller
{
    protected SecureFileUploadService $fileUploadService;
    protected PermitNotificationService $notificationService;

    public function __construct(
        SecureFileUploadService $fileUploadService,
        PermitNotificationService $notificationService
    ) {
        $this->fileUploadService = $fileUploadService;
        $this->notificationService = $notificationService;
    }

    /**
     * Show the form for creating a new permit (guest).
     */
    public function create()
    {
        return view('guest.permits.create');
    }

    /**
     * Store a newly created permit (guest submission).
     */
    public function store(StoreGuestPermitRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        
        try {
            // Generate tracking number
            $trackingNumber = Permit::generateTrackingNumber();
            
            // Create permit
            $permit = Permit::create([
                'user_id' => null, // Guest submission
                'tracking_number' => $trackingNumber,
                'is_guest' => true,
                'guest_email' => strtolower($validated['guest_email']),
                'guest_phone' => $validated['guest_phone'],
                'guest_nik' => $validated['guest_nik'],
                'nama_pemohon' => $validated['nama_pemohon'],
                'nik_pemohon' => $validated['nik_pemohon'],
                'alamat' => $validated['alamat'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'klasifikasi' => $validated['klasifikasi'],
                'ukuran_jumlah' => $validated['ukuran_jumlah'],
                'narasi' => $validated['narasi'],
                'lokasi_alamat' => $validated['lokasi_alamat'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'status' => 'submitted',
            ]);

            // Document types mapping (must match enum values in database)
            $documentTypes = [
                'doc_ktp' => 'ktp',
                'doc_npwp' => 'npwp',
                'doc_akte' => 'akte_pendirian',
                'doc_retribusi' => 'retribusi_pajak',
                'doc_data_isian' => 'data_pemohon',
                'doc_pernyataan' => 'surat_pernyataan',
                'doc_foto_reklame' => 'foto_kondisi',
                'doc_konstruksi' => 'gambar_konstruksi',
                'doc_permohonan' => 'surat_permohonan',
                'doc_kuasa' => 'surat_kuasa',
            ];

            // Store documents securely
            foreach ($documentTypes as $field => $type) {
                if ($request->hasFile($field)) {
                    $result = $this->fileUploadService->upload(
                        $request->file($field),
                        'documents/' . $permit->id
                    );
                    
                    if ($result) {
                        Document::create([
                            'permit_id' => $permit->id,
                            'document_type' => $type,
                            'file_path' => $result['path'],
                            'original_name' => $result['original_name'],
                            'mime_type' => $result['mime_type'],
                            'file_size' => $result['size'],
                        ]);
                    }
                }
            }

            DB::commit();

            // Log successful submission
            Log::channel('permits')->info('Guest permit submitted', [
                'permit_id' => $permit->id,
                'tracking_number' => $trackingNumber,
                'guest_email' => $validated['guest_email'],
            ]);

            // Send email notification (outside transaction)
            $this->notificationService->sendSubmittedNotification($permit);

            // Redirect to success page with tracking info
            return redirect()->route('guest.permits.success', ['tracking' => $trackingNumber])
                ->with('success', 'Permohonan berhasil diajukan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guest permit submission failed: ' . $e->getMessage(), [
                'email' => $validated['guest_email'] ?? 'unknown',
            ]);
            return back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Show success page after submission.
     */
    public function success(Request $request)
    {
        $trackingNumber = $request->query('tracking');
        
        if (!$trackingNumber) {
            return redirect()->route('home');
        }

        $permit = Permit::findByTracking($trackingNumber);

        return view('guest.permits.success', [
            'trackingNumber' => $trackingNumber,
            'permit' => $permit,
        ]);
    }
}
