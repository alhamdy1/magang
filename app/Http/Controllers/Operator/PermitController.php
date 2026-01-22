<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\ApprovalHistory;
use App\Models\Document;
use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermitController extends Controller
{
    /**
     * Display list of available permits for operators.
     */
    public function index()
    {
        // Available permits (submitted and not claimed)
        $availablePermits = Permit::where('status', 'submitted')
            ->whereNull('claimed_by')
            ->orderBy('created_at', 'asc')
            ->paginate(15);

        return view('operator.permits.index', compact('availablePermits'));
    }

    /**
     * Display permits claimed by the current operator.
     */
    public function myPermits()
    {
        $permits = Permit::where('claimed_by', Auth::id())
            ->whereIn('status', ['operator_review'])
            ->orderBy('claimed_at', 'desc')
            ->paginate(15);

        return view('operator.permits.my-permits', compact('permits'));
    }

    /**
     * Claim a permit for review.
     * This prevents other operators from reviewing the same permit.
     */
    public function claim(Permit $permit)
    {
        // Use database transaction and locking to prevent race conditions
        DB::beginTransaction();
        try {
            // Lock the permit row for update
            $permit = Permit::where('id', $permit->id)
                ->where('status', 'submitted')
                ->whereNull('claimed_by')
                ->lockForUpdate()
                ->first();

            if (!$permit) {
                DB::rollBack();
                return back()->with('error', 'Permohonan ini sudah diambil oleh operator lain atau tidak tersedia.');
            }

            $permit->update([
                'claimed_by' => Auth::id(),
                'claimed_at' => now(),
                'status' => 'operator_review',
            ]);

            // Log the claim action
            ApprovalHistory::create([
                'permit_id' => $permit->id,
                'user_id' => Auth::id(),
                'action' => 'claimed',
                'level' => 'operator',
                'old_status' => 'submitted',
                'new_status' => 'operator_review',
                'notes' => 'Permohonan diambil untuk direview oleh operator',
            ]);

            DB::commit();
            return redirect()->route('operator.permits.show', $permit)
                ->with('success', 'Permohonan berhasil diambil untuk direview.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Release a claimed permit.
     */
    public function release(Permit $permit)
    {
        // Ensure only the operator who claimed it can release it
        if ($permit->claimed_by !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk melepas permohonan ini.');
        }

        $permit->update([
            'claimed_by' => null,
            'claimed_at' => null,
            'status' => 'submitted',
        ]);

        ApprovalHistory::create([
            'permit_id' => $permit->id,
            'user_id' => Auth::id(),
            'action' => 'released',
            'level' => 'operator',
            'old_status' => 'operator_review',
            'new_status' => 'submitted',
            'notes' => 'Permohonan dilepaskan oleh operator',
        ]);

        return redirect()->route('operator.permits.index')
            ->with('success', 'Permohonan berhasil dilepaskan.');
    }

    /**
     * Show permit details for review.
     */
    public function show(Permit $permit)
    {
        // Ensure only the operator who claimed it can view it for review
        if ($permit->claimed_by !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mereview permohonan ini.');
        }

        $permit->load(['user', 'documents', 'approvalHistories.user']);
        $documentTypes = Document::getDocumentTypes();

        return view('operator.permits.show', compact('permit', 'documentTypes'));
    }

    /**
     * Approve the permit.
     */
    public function approve(Request $request, Permit $permit)
    {
        // Ensure only the operator who claimed it can approve
        if ($permit->claimed_by !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui permohonan ini.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $permit->update([
            'status' => 'operator_approved',
            'operator_notes' => $request->notes,
        ]);

        ApprovalHistory::create([
            'permit_id' => $permit->id,
            'user_id' => Auth::id(),
            'action' => 'approved',
            'level' => 'operator',
            'old_status' => 'operator_review',
            'new_status' => 'operator_approved',
            'notes' => $request->notes ?? 'Disetujui oleh operator',
        ]);

        return redirect()->route('operator.dashboard')
            ->with('success', 'Permohonan berhasil disetujui dan diteruskan ke Kasi Perizinan.');
    }

    /**
     * Reject the permit.
     */
    public function reject(Request $request, Permit $permit)
    {
        // Ensure only the operator who claimed it can reject
        if ($permit->claimed_by !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menolak permohonan ini.');
        }

        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $permit->update([
            'status' => 'operator_rejected',
            'operator_notes' => $request->notes,
        ]);

        ApprovalHistory::create([
            'permit_id' => $permit->id,
            'user_id' => Auth::id(),
            'action' => 'rejected',
            'level' => 'operator',
            'old_status' => 'operator_review',
            'new_status' => 'operator_rejected',
            'notes' => $request->notes,
        ]);

        return redirect()->route('operator.dashboard')
            ->with('success', 'Permohonan berhasil ditolak.');
    }
}
