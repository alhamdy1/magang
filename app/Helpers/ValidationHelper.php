<?php

namespace App\Helpers;

class ValidationHelper
{
    /**
     * Validate Indonesian NIK (Nomor Induk Kependudukan)
     */
    public static function isValidNIK(string $nik): bool
    {
        // Remove any non-numeric characters
        $nik = preg_replace('/[^0-9]/', '', $nik);
        
        // NIK must be exactly 16 digits
        if (strlen($nik) !== 16) {
            return false;
        }
        
        // First 2 digits: Province code (01-99)
        $provinceCode = (int) substr($nik, 0, 2);
        if ($provinceCode < 1 || $provinceCode > 99) {
            return false;
        }
        
        // Digits 3-4: Regency/City code (01-99)
        $regencyCode = (int) substr($nik, 2, 2);
        if ($regencyCode < 1 || $regencyCode > 99) {
            return false;
        }
        
        // Digits 5-6: District code (01-99)
        $districtCode = (int) substr($nik, 4, 2);
        if ($districtCode < 1 || $districtCode > 99) {
            return false;
        }
        
        // Digits 7-8: Birth date (01-31 for male, 41-71 for female)
        $birthDate = (int) substr($nik, 6, 2);
        if (!(($birthDate >= 1 && $birthDate <= 31) || ($birthDate >= 41 && $birthDate <= 71))) {
            return false;
        }
        
        // Digits 9-10: Birth month (01-12)
        $birthMonth = (int) substr($nik, 8, 2);
        if ($birthMonth < 1 || $birthMonth > 12) {
            return false;
        }
        
        // Digits 11-12: Birth year (00-99)
        // This is valid for any 2-digit year
        
        // Digits 13-16: Sequence number (0001-9999)
        $sequence = (int) substr($nik, 12, 4);
        if ($sequence < 1) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Extract info from NIK
     */
    public static function parseNIK(string $nik): ?array
    {
        if (!static::isValidNIK($nik)) {
            return null;
        }
        
        $nik = preg_replace('/[^0-9]/', '', $nik);
        
        $birthDate = (int) substr($nik, 6, 2);
        $isFemale = $birthDate > 40;
        $actualBirthDate = $isFemale ? $birthDate - 40 : $birthDate;
        
        $birthMonth = (int) substr($nik, 8, 2);
        $birthYear = (int) substr($nik, 10, 2);
        
        // Determine century (simple heuristic)
        $currentYear = (int) date('y');
        $birthYearFull = $birthYear <= $currentYear ? 2000 + $birthYear : 1900 + $birthYear;
        
        return [
            'province_code' => substr($nik, 0, 2),
            'regency_code' => substr($nik, 2, 2),
            'district_code' => substr($nik, 4, 2),
            'gender' => $isFemale ? 'female' : 'male',
            'birth_date' => sprintf('%04d-%02d-%02d', $birthYearFull, $birthMonth, $actualBirthDate),
            'sequence' => substr($nik, 12, 4),
        ];
    }
    
    /**
     * Validate Indonesian phone number
     */
    public static function isValidPhoneNumber(string $phone): bool
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Must start with 08 or 628
        if (str_starts_with($phone, '08')) {
            return strlen($phone) >= 10 && strlen($phone) <= 14;
        }
        
        if (str_starts_with($phone, '628')) {
            return strlen($phone) >= 11 && strlen($phone) <= 15;
        }
        
        return false;
    }
    
    /**
     * Check if email is from trusted domain (for admin verification)
     */
    public static function isTrustedEmailDomain(string $email): bool
    {
        $trustedDomains = [
            'go.id',
            'gov.id',
            'ac.id',
            'sch.id',
        ];
        
        $domain = strtolower(substr(strrchr($email, '@'), 1));
        
        foreach ($trustedDomains as $trusted) {
            if ($domain === $trusted || str_ends_with($domain, '.' . $trusted)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Sanitize filename
     */
    public static function sanitizeFilename(string $filename): string
    {
        // Remove path components
        $filename = basename($filename);
        
        // Replace unsafe characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Remove multiple underscores
        $filename = preg_replace('/_+/', '_', $filename);
        
        // Limit length
        if (strlen($filename) > 200) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $name = pathinfo($filename, PATHINFO_FILENAME);
            $filename = substr($name, 0, 200 - strlen($ext) - 1) . '.' . $ext;
        }
        
        return $filename;
    }
}
