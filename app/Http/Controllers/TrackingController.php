<?php

namespace App\Http\Controllers;

use App\Models\Permit;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Show the tracking form.
     */
    public function index()
    {
        return view('tracking.index');
    }

    /**
     * Search and show tracking result.
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'tracking_number' => 'nullable|string|max:20',
            'email' => 'required|email',
            'nik' => 'required|string|size:16|regex:/^[0-9]+$/',
        ], [
            'nik.size' => 'NIK harus 16 digit',
            'nik.regex' => 'NIK hanya boleh berisi angka',
        ]);

        // Build query
        $query = Permit::query();
        
        // If tracking number provided, use it for exact match
        if (!empty($validated['tracking_number'])) {
            $query->where('tracking_number', $validated['tracking_number']);
        }
        
        // Filter by email (guest_email or user email)
        $query->where(function ($q) use ($validated) {
            $q->where('guest_email', $validated['email'])
              ->orWhereHas('user', function ($userQuery) use ($validated) {
                  $userQuery->where('email', $validated['email']);
              });
        });
        
        // Filter by NIK
        $query->where(function ($q) use ($validated) {
            $q->where('nik_pemohon', $validated['nik'])
              ->orWhere('guest_nik', $validated['nik'])
              ->orWhereHas('user', function ($userQuery) use ($validated) {
                  $userQuery->where('nik', $validated['nik']);
              });
        });
        
        // Get permits (if tracking number provided, get single, else get all matching)
        if (!empty($validated['tracking_number'])) {
            $permit = $query->first();
            
            if (!$permit) {
                return back()->withInput()->with('error', 'Permohonan tidak ditemukan. Pastikan nomor tracking, email, dan NIK benar.');
            }
            
            return redirect()->route('tracking.show', ['trackingNumber' => $permit->tracking_number])
                ->with('verified_email', $validated['email'])
                ->with('verified_nik', $validated['nik']);
        } else {
            // Get all permits for this email + NIK combination
            $permits = $query->orderBy('created_at', 'desc')->get();
            
            if ($permits->isEmpty()) {
                return back()->withInput()->with('error', 'Tidak ditemukan permohonan dengan email dan NIK tersebut.');
            }
            
            // If only one permit found, redirect to detail
            if ($permits->count() === 1) {
                return redirect()->route('tracking.show', ['trackingNumber' => $permits->first()->tracking_number])
                    ->with('verified_email', $validated['email'])
                    ->with('verified_nik', $validated['nik']);
            }
            
            // Multiple permits found, show list
            return view('tracking.list', [
                'permits' => $permits,
                'email' => $validated['email'],
                'nik' => $validated['nik'],
            ]);
        }
    }

    /**
     * Show tracking detail for a specific permit.
     */
    public function show(Request $request, $trackingNumber)
    {
        $permit = Permit::where('tracking_number', $trackingNumber)
            ->with(['documents', 'approvalHistories.user'])
            ->first();
        
        if (!$permit) {
            return redirect()->route('tracking.index')
                ->with('error', 'Permohonan tidak ditemukan.');
        }
        
        // For security, we need email/NIK verification to view details
        // Check if coming from search (verified) or direct access
        $verifiedEmail = session('verified_email');
        $verifiedNik = session('verified_nik');
        
        // If not verified, require verification
        if (!$verifiedEmail || !$verifiedNik) {
            return view('tracking.verify', [
                'trackingNumber' => $trackingNumber,
            ]);
        }
        
        // Verify the email and NIK match this permit
        $emailMatch = ($permit->guest_email === $verifiedEmail) || 
                      ($permit->user && $permit->user->email === $verifiedEmail);
        
        $nikMatch = ($permit->nik_pemohon === $verifiedNik) || 
                    ($permit->guest_nik === $verifiedNik) ||
                    ($permit->user && $permit->user->nik === $verifiedNik);
        
        if (!$emailMatch || !$nikMatch) {
            return redirect()->route('tracking.index')
                ->with('error', 'Akses ditolak. Email atau NIK tidak sesuai dengan permohonan ini.');
        }

        return view('tracking.show', [
            'permit' => $permit,
        ]);
    }

    /**
     * Verify access to a specific permit (POST from verify form).
     */
    public function verify(Request $request, $trackingNumber)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'nik' => 'required|string|size:16|regex:/^[0-9]+$/',
        ]);

        $permit = Permit::where('tracking_number', $trackingNumber)->first();
        
        if (!$permit) {
            return back()->with('error', 'Permohonan tidak ditemukan.');
        }
        
        // Verify the email and NIK match
        $emailMatch = ($permit->guest_email === $validated['email']) || 
                      ($permit->user && $permit->user->email === $validated['email']);
        
        $nikMatch = ($permit->nik_pemohon === $validated['nik']) || 
                    ($permit->guest_nik === $validated['nik']) ||
                    ($permit->user && $permit->user->nik === $validated['nik']);
        
        if (!$emailMatch || !$nikMatch) {
            return back()->withInput()->with('error', 'Email atau NIK tidak sesuai dengan permohonan ini.');
        }
        
        return redirect()->route('tracking.show', ['trackingNumber' => $trackingNumber])
            ->with('verified_email', $validated['email'])
            ->with('verified_nik', $validated['nik']);
    }
}
