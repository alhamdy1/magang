<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Permit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardStatsComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        $cacheKey = 'dashboard_stats_' . $user->id . '_' . $user->role;
        
        $stats = Cache::remember($cacheKey, 300, function () use ($user) {
            return $this->getStatsForRole($user);
        });

        $view->with('dashboardStats', $stats);
    }

    /**
     * Get stats based on user role
     */
    protected function getStatsForRole($user): array
    {
        return match ($user->role) {
            'admin' => $this->getAdminStats(),
            'operator' => $this->getOperatorStats($user),
            'kasi' => $this->getKasiStats(),
            'kabid' => $this->getKabidStats(),
            'user' => $this->getUserStats($user),
            default => [],
        };
    }

    protected function getAdminStats(): array
    {
        return [
            'total_permits' => Permit::count(),
            'pending_permits' => Permit::where('status', 'pending_admin')->count(),
            'completed_today' => Permit::whereDate('updated_at', today())
                ->where('status', 'approved')
                ->count(),
        ];
    }

    protected function getOperatorStats($user): array
    {
        return [
            'available_permits' => Permit::where('status', 'pending_operator')
                ->whereNull('claimed_by')
                ->count(),
            'my_reviews' => Permit::where('claimed_by', $user->id)
                ->whereIn('status', ['pending_operator', 'pending_kasi'])
                ->count(),
            'completed_today' => Permit::where('claimed_by', $user->id)
                ->whereDate('updated_at', today())
                ->count(),
        ];
    }

    protected function getKasiStats(): array
    {
        return [
            'pending_review' => Permit::where('status', 'pending_kasi')->count(),
            'approved_today' => Permit::where('status', 'approved_kasi')
                ->whereDate('updated_at', today())
                ->count(),
        ];
    }

    protected function getKabidStats(): array
    {
        return [
            'pending_review' => Permit::where('status', 'pending_kabid')->count(),
            'approved_today' => Permit::where('status', 'approved')
                ->whereDate('updated_at', today())
                ->count(),
        ];
    }

    protected function getUserStats($user): array
    {
        return [
            'total_permits' => Permit::where('user_id', $user->id)->count(),
            'pending_permits' => Permit::where('user_id', $user->id)
                ->whereNotIn('status', ['approved', 'rejected'])
                ->count(),
            'approved_permits' => Permit::where('user_id', $user->id)
                ->where('status', 'approved')
                ->count(),
        ];
    }
}
