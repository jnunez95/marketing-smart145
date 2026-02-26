<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE campaign_logs MODIFY COLUMN status ENUM('pending', 'sent', 'failed', 'opened', 'clicked', 'bounced', 'spam_complaint') NOT NULL DEFAULT 'pending'");
        }
        // SQLite: enum se almacena como texto, los nuevos valores ya son válidos.
        // PostgreSQL: usar CHECK o dejar tipo varchar si se usó.
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE campaign_logs MODIFY COLUMN status ENUM('pending', 'sent', 'failed', 'opened', 'clicked') NOT NULL DEFAULT 'pending'");
        }
    }
};
