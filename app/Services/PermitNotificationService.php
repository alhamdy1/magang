<?php

namespace App\Services;

use App\Mail\PermitSubmitted;
use App\Mail\PermitStatusUpdated;
use App\Models\Permit;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PermitNotificationService
{
    /**
     * Send notification when permit is submitted.
     */
    public function sendSubmittedNotification(Permit $permit): bool
    {
        $email = $permit->applicant_email;
        
        if (!$email) {
            Log::warning("Cannot send permit submitted notification - no email for permit {$permit->id}");
            return false;
        }

        try {
            Mail::to($email)->send(new PermitSubmitted($permit));
            Log::info("Permit submitted notification sent to {$email} for permit {$permit->tracking_number}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send permit submitted notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification when permit status is updated.
     */
    public function sendStatusUpdateNotification(Permit $permit, string $oldStatus, string $newStatus, ?string $notes = null): bool
    {
        $email = $permit->applicant_email;
        
        if (!$email) {
            Log::warning("Cannot send status update notification - no email for permit {$permit->id}");
            return false;
        }

        // Don't send notification if status didn't change
        if ($oldStatus === $newStatus) {
            return false;
        }

        try {
            Mail::to($email)->send(new PermitStatusUpdated($permit, $oldStatus, $newStatus, $notes));
            Log::info("Status update notification sent to {$email} for permit {$permit->tracking_number}: {$oldStatus} -> {$newStatus}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send status update notification: " . $e->getMessage());
            return false;
        }
    }
}
