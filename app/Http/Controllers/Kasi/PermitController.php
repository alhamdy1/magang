<?php

namespace App\Http\Controllers\Kasi;

use App\Http\Controllers\Controller;
use App\Models\ApprovalHistory;
use App\Models\Document;
use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermitController extends Controller
{
    /**
     * Display list of permits pending Kasi review.
     */
    public function index()
    {
        $permits = Permit::where('status', 'operator_approved')
            ->orderBy('updated_at', 'asc')
            ->paginate(15);

        return view('kasi.permits.index', compact('permits'));
    }

    /**
     * Show permit details for review.
     */
    public function show(Permit $permit)
    {
        // Only show permits that are approved by operator or currently being reviewed by Kasi
        if (!in_array($permit->status, ['operator_approved', 'kasi_review'])) {
            return back()->with('error', 'Permohonan ini tidak dapat direview pada tahap ini.');
        }

        // If not already in kasi_review, mark it
        if ($permit->status === 'operator_approved') {
            $permit->update(['status' => 'kasi_review']);

            ApprovalHistory::create([
                'permit_id' => $permit->id,
                'user_id' => Auth::id(),
                'action' => 'claimed',
                'level' => 'kasi',
                'old_status' => 'operator_approved',
                'new_status' => 'kasi_review',
                'notes' => 'Permohonan direview oleh Kasi Perijinan',
            ]);
        }

        $permit->load(['user', 'documents', 'approvalHistories.user']);
        $documentTypes = Document::getDocumentTypes();

        return view('kasi.permits.show', compact('permit', 'documentTypes'));
    }

    /**
     * Approve the permit.
     */
    public function approve(Request $request, Permit $permit)
    {
        if ($permit->status !== 'kasi_review') {
            return back()->with('error', 'Permohonan ini tidak dapat disetujui pada tahap ini.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $permit->update([
            'status' => 'kasi_approved',
            'kasi_notes' => $request->notes,
        ]);

        ApprovalHistory::create([
            'permit_id' => $permit->id,
            'user_id' => Auth::id(),
            'action' => 'approved',
            'level' => 'kasi',
            'old_status' => 'kasi_review',
            'new_status' => 'kasi_approved',
            'notes' => $request->notes ?? 'Disetujui oleh Kasi Perijinan',
        ]);

        return redirect()->route('kasi.dashboard')
            ->with('success', 'Permohonan berhasil disetujui dan diteruskan ke Kabid Penyelenggaraan.');
    }

    /**
     * Reject the permit.
     */
    public function reject(Request $request, Permit $permit)
    {
        if ($permit->status !== 'kasi_review') {
            return back()->with('error', 'Permohonan ini tidak dapat ditolak pada tahap ini.');
        }

        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $permit->update([
            'status' => 'kasi_rejected',
            'kasi_notes' => $request->notes,
        ]);

        ApprovalHistory::create([
            'permit_id' => $permit->id,
            'user_id' => Auth::id(),
            'action' => 'rejected',
            'level' => 'kasi',
            'old_status' => 'kasi_review',
            'new_status' => 'kasi_rejected',
            'notes' => $request->notes,
        ]);

        return redirect()->route('kasi.dashboard')
            ->with('success', 'Permohonan berhasil ditolak.');
    }
}
