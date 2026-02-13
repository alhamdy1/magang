<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermitRequest;
use App\Models\ApprovalHistory;
use App\Models\Document;
use App\Models\Permit;
use App\Services\PermitNotificationService;
use App\Services\SecureFileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermitController extends Controller
{
    protected PermitNotificationService $notificationService;
    protected SecureFileUploadService $fileUploadService;

    public function __construct(
        PermitNotificationService $notificationService,
        SecureFileUploadService $fileUploadService
    ) {
        $this->notificationService = $notificationService;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display user's permits list.
     */
    public function index()
    {
        $permits = Permit::forUser(Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.permits.index', compact('permits'));
    }

    /**
     * Show form to create new permit.
     */
    public function create()
    {
        $documentTypes = Document::getDocumentTypes();
        return view('user.permits.create', compact('documentTypes'));
    }

    /**
     * Store a new permit application.
     */
    public function store(StorePermitRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            // Create permit with tracking number
            $permit = Permit::create([
                'user_id' => Auth::id(),
                'tracking_number' => Permit::generateTrackingNumber(),
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

            // Upload documents securely
            $documentTypes = ['ktp', 'npwp', 'akte_pendirian', 'retribusi_pajak', 'data_pemohon', 
                             'surat_pernyataan', 'foto_kondisi', 'gambar_konstruksi', 'surat_permohonan', 'surat_kuasa'];

            foreach ($documentTypes as $type) {
                if ($request->hasFile($type)) {
                    $maxSize = in_array($type, ['foto_kondisi', 'gambar_konstruksi']) ? 10485760 : 5242880;
                    $result = $this->fileUploadService->setMaxFileSize($maxSize)->upload(
                        $request->file($type),
                        'permits/' . $permit->id
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

            // Create approval history
            ApprovalHistory::create([
                'permit_id' => $permit->id,
                'user_id' => Auth::id(),
                'action' => 'submitted',
                'level' => 'user',
                'old_status' => null,
                'new_status' => 'submitted',
                'notes' => 'Permohonan diajukan',
            ]);

            DB::commit();

            // Log successful submission
            Log::channel('permits')->info('New permit submitted', [
                'permit_id' => $permit->id,
                'tracking_number' => $permit->tracking_number,
                'user_id' => Auth::id(),
            ]);

            // Send email notification (outside transaction)
            $this->notificationService->sendSubmittedNotification($permit);

            return redirect()->route('user.permits.show', $permit)
                ->with('success', 'Permohonan izin berhasil diajukan. Nomor tracking: ' . $permit->tracking_number);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Permit submission failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat mengajukan permohonan. Silakan coba lagi.');
        }
    }

    /**
     * Display specific permit details.
     */
    public function show(Permit $permit)
    {
        // Ensure user can only view their own permits
        if ($permit->user_id !== Auth::id()) {
            abort(403);
        }

        $permit->load(['documents', 'approvalHistories.user']);
        $documentTypes = Document::getDocumentTypes();

        return view('user.permits.show', compact('permit', 'documentTypes'));
    }

    /**
     * Track permit status.
     */
    public function track(Permit $permit)
    {
        // Ensure user can only track their own permits
        if ($permit->user_id !== Auth::id()) {
            abort(403);
        }

        $histories = $permit->approvalHistories()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('user.permits.track', compact('permit', 'histories'));
    }
}
