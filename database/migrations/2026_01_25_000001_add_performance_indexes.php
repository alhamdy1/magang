<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add indexes for performance optimization on frequently queried columns.
     */
    public function up(): void
    {
        // Add indexes to permits table
        Schema::table('permits', function (Blueprint $table) {
            // Index for status filtering (very common query)
            $table->index('status', 'idx_permits_status');
            
            // Index for tracking number lookup
            $table->index('tracking_number', 'idx_permits_tracking');
            
            // Index for user's permits
            $table->index('user_id', 'idx_permits_user');
            
            // Index for operator claimed permits
            $table->index('claimed_by', 'idx_permits_claimed');
            
            // Composite index for guest lookup (email + nik)
            $table->index(['guest_email', 'guest_nik'], 'idx_permits_guest_lookup');
            
            // Index for date-based queries
            $table->index('created_at', 'idx_permits_created');
            
            // Index for nik lookup
            $table->index('nik_pemohon', 'idx_permits_nik');
        });

        // Add indexes to approval_histories table
        Schema::table('approval_histories', function (Blueprint $table) {
            // Index for permit history lookup
            $table->index('permit_id', 'idx_history_permit');
            
            // Index for user actions
            $table->index('user_id', 'idx_history_user');
            
            // Composite index for filtering
            $table->index(['permit_id', 'created_at'], 'idx_history_permit_date');
        });

        // Add indexes to documents table
        Schema::table('documents', function (Blueprint $table) {
            // Index for permit documents
            $table->index('permit_id', 'idx_documents_permit');
        });

        // Add indexes to users table
        Schema::table('users', function (Blueprint $table) {
            // Index for role filtering
            $table->index('role', 'idx_users_role');
            
            // Index for NIK lookup
            $table->index('nik', 'idx_users_nik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->dropIndex('idx_permits_status');
            $table->dropIndex('idx_permits_tracking');
            $table->dropIndex('idx_permits_user');
            $table->dropIndex('idx_permits_claimed');
            $table->dropIndex('idx_permits_guest_lookup');
            $table->dropIndex('idx_permits_created');
            $table->dropIndex('idx_permits_nik');
        });

        Schema::table('approval_histories', function (Blueprint $table) {
            $table->dropIndex('idx_history_permit');
            $table->dropIndex('idx_history_user');
            $table->dropIndex('idx_history_permit_date');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex('idx_documents_permit');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_nik');
        });
    }
};
