<?php

namespace App\Http\Controllers;

use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        $user = Auth::user();
        $permits = Permit::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $stats = [
            'total' => Permit::where('user_id', $user->id)->count(),
            'pending' => Permit::where('user_id', $user->id)
                ->whereNotIn('status', ['completed', 'operator_rejected', 'kasi_rejected', 'kabid_rejected'])
                ->count(),
            'approved' => Permit::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'rejected' => Permit::where('user_id', $user->id)
                ->whereIn('status', ['operator_rejected', 'kasi_rejected', 'kabid_rejected'])
                ->count(),
        ];

        return view('user.dashboard', compact('permits', 'stats'));
    }

    public function operatorDashboard()
    {
        $user = Auth::user();
        
        // Available permits (submitted and not claimed by others)
        $availablePermits = Permit::where('status', 'submitted')
            ->whereNull('claimed_by')
            ->orderBy('created_at', 'asc')
            ->take(10)
            ->get();

        // Permits claimed by this operator
        $myPermits = Permit::where('claimed_by', $user->id)
            ->whereIn('status', ['operator_review'])
            ->orderBy('claimed_at', 'desc')
            ->get();

        $stats = [
            'available' => Permit::where('status', 'submitted')->whereNull('claimed_by')->count(),
            'my_review' => Permit::where('claimed_by', $user->id)->where('status', 'operator_review')->count(),
            'approved_today' => Permit::where('status', 'operator_approved')
                ->whereDate('updated_at', today())
                ->count(),
        ];

        return view('operator.dashboard', compact('availablePermits', 'myPermits', 'stats'));
    }

    public function kasiDashboard()
    {
        // Permits approved by operator, waiting for Kasi review
        $pendingPermits = Permit::where('status', 'operator_approved')
            ->orderBy('updated_at', 'asc')
            ->paginate(10);

        // Permits currently being reviewed by Kasi
        $reviewingPermits = Permit::where('status', 'kasi_review')
            ->orderBy('updated_at', 'desc')
            ->get();

        $stats = [
            'pending' => Permit::where('status', 'operator_approved')->count(),
            'reviewing' => Permit::where('status', 'kasi_review')->count(),
            'approved_today' => Permit::where('status', 'kasi_approved')
                ->whereDate('updated_at', today())
                ->count(),
        ];

        return view('kasi.dashboard', compact('pendingPermits', 'reviewingPermits', 'stats'));
    }

    public function kabidDashboard()
    {
        // Permits approved by Kasi, waiting for Kabid review
        $pendingPermits = Permit::where('status', 'kasi_approved')
            ->orderBy('updated_at', 'asc')
            ->paginate(10);

        // Permits currently being reviewed by Kabid
        $reviewingPermits = Permit::where('status', 'kabid_review')
            ->orderBy('updated_at', 'desc')
            ->get();

        $stats = [
            'pending' => Permit::where('status', 'kasi_approved')->count(),
            'reviewing' => Permit::where('status', 'kabid_review')->count(),
            'completed_today' => Permit::where('status', 'completed')
                ->whereDate('updated_at', today())
                ->count(),
        ];

        return view('kabid.dashboard', compact('pendingPermits', 'reviewingPermits', 'stats'));
    }

    public function adminDashboard()
    {
        $stats = [
            'total_permits' => Permit::count(),
            'pending_permits' => Permit::whereNotIn('status', ['completed', 'operator_rejected', 'kasi_rejected', 'kabid_rejected'])->count(),
            'completed_permits' => Permit::where('status', 'completed')->count(),
            'total_users' => \App\Models\User::count(),
        ];

        $recentPermits = Permit::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPermits'));
    }
}
