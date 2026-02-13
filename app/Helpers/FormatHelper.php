<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Format phone number to Indonesian format
     */
    public static function phone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert 08xxx to +628xxx
        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        }
        
        // Format as +62 xxx-xxxx-xxxx
        if (strlen($phone) >= 10) {
            return '+' . substr($phone, 0, 2) . ' ' . 
                   substr($phone, 2, 3) . '-' . 
                   substr($phone, 5, 4) . '-' . 
                   substr($phone, 9);
        }
        
        return $phone;
    }
    
    /**
     * Format NIK with spaces for readability
     */
    public static function nik(string $nik): string
    {
        $nik = preg_replace('/[^0-9]/', '', $nik);
        
        if (strlen($nik) === 16) {
            return substr($nik, 0, 4) . ' ' . 
                   substr($nik, 4, 4) . ' ' . 
                   substr($nik, 8, 4) . ' ' . 
                   substr($nik, 12, 4);
        }
        
        return $nik;
    }
    
    /**
     * Format currency to Indonesian Rupiah
     */
    public static function rupiah($amount, bool $withSymbol = true): string
    {
        if ($amount === null) {
            return '-';
        }
        
        $formatted = number_format((float) $amount, 0, ',', '.');
        
        return $withSymbol ? 'Rp ' . $formatted : $formatted;
    }
    
    /**
     * Format file size to human readable
     */
    public static function fileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Truncate text with ellipsis
     */
    public static function truncate(string $text, int $length = 100, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        
        return mb_substr($text, 0, $length) . $suffix;
    }
    
    /**
     * Format tracking number for display
     */
    public static function trackingNumber(string $tracking): string
    {
        // PRK-20250125-XXXX -> PRK-2025-0125-XXXX
        if (preg_match('/^([A-Z]+)-(\d{4})(\d{4})-([A-Z0-9]+)$/', $tracking, $matches)) {
            return $matches[1] . '-' . $matches[2] . '-' . $matches[3] . '-' . $matches[4];
        }
        
        return $tracking;
    }
    
    /**
     * Mask email for privacy
     */
    public static function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }
        
        $name = $parts[0];
        $domain = $parts[1];
        
        $maskedName = substr($name, 0, 2) . str_repeat('*', max(strlen($name) - 4, 2)) . substr($name, -2);
        
        return $maskedName . '@' . $domain;
    }
    
    /**
     * Mask phone number for privacy
     */
    public static function maskPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) >= 10) {
            return substr($phone, 0, 4) . str_repeat('*', strlen($phone) - 8) . substr($phone, -4);
        }
        
        return $phone;
    }
}
