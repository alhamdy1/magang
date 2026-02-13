<?php

namespace App\Http\Controllers\Kabid;

use App\Http\Controllers\Controller;
use App\Models\ApprovalHistory;
use App\Models\Document;
use App\Models\Permit;
use App\Services\PermitNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermitController extends Controller
{
    protected PermitNotificationService $notificationService;

    public function __construct(PermitNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display list of permits pending Kabid review.
     */
    public function index()
    {
        $permits = Permit::where('status', 'kasi_approved')
            ->orderBy('updated_at', 'asc')
            ->paginate(15);

        return view('kabid.permits.index', compact('permits'));
    }

    /**
     * Show permit details for review.
     */
    public function show(Permit $permit)
    {
        // Only show permits that are approved by Kasi or currently being reviewed by Kabid
        if (!in_array($permit->status, ['kasi_approved', 'kabid_review'])) {
            return back()->with('error', 'Permohonan ini tidak dapat direview pada tahap ini.');
        }

        // If not already in kabid_review, mark it
        if ($permit->status === 'kasi_approved') {
            $permit->update(['status' => 'kabid_review']);

            ApprovalHistory::create([
                'permit_id' => $permit->id,
                'user_id' => Auth::id(),
                'action' => 'claimed',
                'level' => 'kabid',
                'old_status' => 'kasi_approved',
                'new_status' => 'kabid_review',
                'notes' => 'Permohonan direview oleh Kabid Penyelenggaraan',
            ]);
        }

        $permit->load(['user', 'documents', 'approvalHistories.user']);
        $documentTypes = Document::getDocumentTypes();

        return view('kabid.permits.show', compact('permit', 'documentTypes'));
    }

    /**
     * Approve the permit (final approval).
     */
    public function approve(Request $request, Permit $permit)
    {
        if ($permit->status !== 'kabid_review') {
            return back()->with('error', 'Permohonan ini tidak dapat disetujui pada tahap ini.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $permit->status;

        // Generate permit number when fully approved
        $permitNumber = Permit::generatePermitNumber();

        $permit->update([
            'status' => 'completed',
            'permit_number' => $permitNumber,
            'kabid_notes' => $request->notes,
        ]);

        ApprovalHistory::create([
            'permit_id' => $permit->id,
            'user_id' => Auth::id(),
            'action' => 'approved',
            'level' => 'kabid',
            'old_status' => 'kabid_review',
            'new_status' => 'completed',
            'notes' => $request->notes ?? 'Disetujui oleh Kabid Penyelenggaraan. Nomor Izin: ' . $permitNumber,
        ]);

        // Send notification email for approved permit
        $this->notificationService->sendStatusUpdateNotification(
            $permit, $oldStatus, 'approved', 'Permohonan izin reklame Anda telah disetujui. Nomor Izin: ' . $permitNumber
        );

        return redirect()->route('kabid.dashboard')
            ->with('success', 'Permohonan berhasil disetujui. Nomor Izin: ' . $permitNumber);
    }

    /**
     * Reject the permit.
     */
    public function reject(Request $request, Permit $permit)
    {
        if ($permit->status !== 'kabid_review') {
            return back()->with('error', 'Permohonan ini tidak dapat ditolak pada tahap ini.');
        }

        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $oldStatus = $permit->status;

        $permit->update([
            'status' => 'kabid_rejected',
            'kabid_notes' => $request->notes,
        ]);

        ApprovalHistory::create([
            'permit_id' => $permit->id,
            'user_id' => Auth::id(),
            'action' => 'rejected',
            'level' => 'kabid',
            'old_status' => 'kabid_review',
            'new_status' => 'kabid_rejected',
            'notes' => $request->notes,
        ]);

        // Send notification email
        $this->notificationService->sendStatusUpdateNotification(
            $permit, $oldStatus, 'rejected', $request->notes
        );

        return redirect()->route('kabid.dashboard')
            ->with('success', 'Permohonan berhasil ditolak.');
    }
}
