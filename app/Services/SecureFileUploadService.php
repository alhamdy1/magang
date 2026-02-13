<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SecureFileUploadService
{
    /**
     * Allowed MIME types and their extensions.
     */
    protected array $allowedTypes = [
        'application/pdf' => 'pdf',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    ];

    /**
     * Maximum file size in bytes (5MB default).
     */
    protected int $maxFileSize = 5242880;

    /**
     * Upload file securely with validation.
     */
    public function upload(UploadedFile $file, string $directory, ?int $maxSize = null): ?array
    {
        try {
            // Validate file
            if (!$this->validateFile($file, $maxSize)) {
                return null;
            }

            // Generate secure filename
            $filename = $this->generateSecureFilename($file);
            
            // Store file
            $path = $file->storeAs($directory, $filename, 'public');
            
            if (!$path) {
                Log::error('Failed to store file', ['original' => $file->getClientOriginalName()]);
                return null;
            }

            // Verify stored file
            if (!Storage::disk('public')->exists($path)) {
                Log::error('File not found after upload', ['path' => $path]);
                return null;
            }

            return [
                'path' => $path,
                'filename' => $filename,
                'original_name' => $this->sanitizeFilename($file->getClientOriginalName()),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ];

        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Validate uploaded file.
     */
    protected function validateFile(UploadedFile $file, ?int $maxSize = null): bool
    {
        $maxSize = $maxSize ?? $this->maxFileSize;

        // Check if file is valid
        if (!$file->isValid()) {
            Log::warning('Invalid file upload', ['error' => $file->getError()]);
            return false;
        }

        // Check file size
        if ($file->getSize() > $maxSize) {
            Log::warning('File too large', [
                'size' => $file->getSize(),
                'max' => $maxSize,
            ]);
            return false;
        }

        // Validate MIME type by checking actual file content
        $mimeType = $file->getMimeType();
        if (!array_key_exists($mimeType, $this->allowedTypes)) {
            Log::warning('Invalid MIME type', [
                'mime' => $mimeType,
                'original' => $file->getClientOriginalName(),
            ]);
            return false;
        }

        // Additional check for image files - verify they're actually images
        if (str_starts_with($mimeType, 'image/')) {
            if (!$this->isValidImage($file)) {
                Log::warning('Invalid image file', ['file' => $file->getClientOriginalName()]);
                return false;
            }
        }

        // Check for PDF validity
        if ($mimeType === 'application/pdf') {
            if (!$this->isValidPdf($file)) {
                Log::warning('Invalid PDF file', ['file' => $file->getClientOriginalName()]);
                return false;
            }
        }

        return true;
    }

    /**
     * Verify image file is actually an image.
     */
    protected function isValidImage(UploadedFile $file): bool
    {
        try {
            $imageInfo = @getimagesize($file->getPathname());
            return $imageInfo !== false && $imageInfo[0] > 0 && $imageInfo[1] > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verify PDF file is actually a PDF.
     */
    protected function isValidPdf(UploadedFile $file): bool
    {
        try {
            $handle = fopen($file->getPathname(), 'rb');
            if (!$handle) {
                return false;
            }
            
            $header = fread($handle, 5);
            fclose($handle);
            
            // PDF files start with %PDF-
            return str_starts_with($header, '%PDF-');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Generate secure random filename.
     */
    protected function generateSecureFilename(UploadedFile $file): string
    {
        $extension = $this->allowedTypes[$file->getMimeType()] ?? 'bin';
        $timestamp = now()->format('Ymd_His');
        $random = Str::random(16);
        
        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Sanitize original filename for storage.
     */
    protected function sanitizeFilename(string $filename): string
    {
        // Remove path traversal attempts
        $filename = basename($filename);
        
        // Remove special characters
        $filename = preg_replace('/[^a-zA-Z0-9\.\-\_\s]/', '', $filename);
        
        // Limit length
        if (strlen($filename) > 200) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $name = pathinfo($filename, PATHINFO_FILENAME);
            $filename = substr($name, 0, 195) . '.' . $ext;
        }
        
        return $filename;
    }

    /**
     * Delete file securely.
     */
    public function delete(string $path): bool
    {
        try {
            // Prevent path traversal
            if (str_contains($path, '..')) {
                Log::warning('Path traversal attempt in file deletion', ['path' => $path]);
                return false;
            }

            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->delete($path);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('File deletion error: ' . $e->getMessage(), ['path' => $path]);
            return false;
        }
    }

    /**
     * Set maximum file size.
     */
    public function setMaxFileSize(int $bytes): self
    {
        $this->maxFileSize = $bytes;
        return $this;
    }
}
