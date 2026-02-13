<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

class DateHelper
{
    /**
     * Format date to Indonesian format
     */
    public static function formatIndonesian($date, string $format = 'full'): string
    {
        if (!$date) {
            return '-';
        }
        
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $days = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        
        switch ($format) {
            case 'short':
                // 25 Jan 2025
                return $carbon->format('d') . ' ' . substr($months[$carbon->month], 0, 3) . ' ' . $carbon->format('Y');
            
            case 'medium':
                // 25 Januari 2025
                return $carbon->format('d') . ' ' . $months[$carbon->month] . ' ' . $carbon->format('Y');
            
            case 'full':
                // Senin, 25 Januari 2025
                return $days[$carbon->format('l')] . ', ' . $carbon->format('d') . ' ' . $months[$carbon->month] . ' ' . $carbon->format('Y');
            
            case 'datetime':
                // 25 Januari 2025, 14:30
                return $carbon->format('d') . ' ' . $months[$carbon->month] . ' ' . $carbon->format('Y') . ', ' . $carbon->format('H:i');
            
            case 'relative':
                return static::relativeTime($carbon);
            
            default:
                return $carbon->format($format);
        }
    }
    
    /**
     * Get relative time in Indonesian
     */
    public static function relativeTime(Carbon $date): string
    {
        $now = Carbon::now();
        $diff = $date->diff($now);
        
        if ($diff->y > 0) {
            return $diff->y . ' tahun yang lalu';
        }
        if ($diff->m > 0) {
            return $diff->m . ' bulan yang lalu';
        }
        if ($diff->d > 0) {
            if ($diff->d === 1) {
                return 'Kemarin';
            }
            if ($diff->d < 7) {
                return $diff->d . ' hari yang lalu';
            }
            return floor($diff->d / 7) . ' minggu yang lalu';
        }
        if ($diff->h > 0) {
            return $diff->h . ' jam yang lalu';
        }
        if ($diff->i > 0) {
            return $diff->i . ' menit yang lalu';
        }
        return 'Baru saja';
    }
}
