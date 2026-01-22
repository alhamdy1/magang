<?php

namespace App\Http\Controllers;

use App\Models\ApprovalHistory;
use App\Models\Document;
use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PermitController extends Controller
{
    /**
     * Display user's permits list.
     */
    public function index()
    {
        $permits = Permit::where('user_id', Auth::id())
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
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:20',
            'klasifikasi' => 'required|in:permanen,non_permanen',
            'ukuran_jumlah' => 'required|string|max:255',
            'narasi' => 'required|string',
            'lokasi_alamat' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            // Document validations
            'ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'npwp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'akte_pendirian' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'retribusi_pajak' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'data_pemohon' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'surat_pernyataan' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'foto_kondisi' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'gambar_konstruksi' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'surat_permohonan' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'surat_kuasa' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Create permit
        $permit = Permit::create([
            'user_id' => Auth::id(),
            'nama_pemohon' => $request->nama_pemohon,
            'alamat' => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
            'klasifikasi' => $request->klasifikasi,
            'ukuran_jumlah' => $request->ukuran_jumlah,
            'narasi' => $request->narasi,
            'lokasi_alamat' => $request->lokasi_alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'submitted',
        ]);

        // Upload documents
        $documentTypes = ['ktp', 'npwp', 'akte_pendirian', 'retribusi_pajak', 'data_pemohon', 
                         'surat_pernyataan', 'foto_kondisi', 'gambar_konstruksi', 'surat_permohonan', 'surat_kuasa'];

        foreach ($documentTypes as $type) {
            if ($request->hasFile($type)) {
                $file = $request->file($type);
                $path = $file->store('permits/' . $permit->id, 'public');
                
                Document::create([
                    'permit_id' => $permit->id,
                    'document_type' => $type,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
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

        return redirect()->route('user.permits.show', $permit)
            ->with('success', 'Permohonan izin berhasil diajukan.');
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
