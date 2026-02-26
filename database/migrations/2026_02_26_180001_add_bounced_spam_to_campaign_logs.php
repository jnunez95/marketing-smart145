<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE campaign_logs MODIFY COLUMN status ENUM('pending', 'sent', 'failed', 'opened', 'clicked', 'bounced', 'spam_complaint') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
         DB::statement("ALTER TABLE campaign_logs MODIFY COLUMN status ENUM('pending', 'sent', 'failed', 'opened', 'clicked') NOT NULL DEFAULT 'pending'");
    }
};
