<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table): void {
            $table->unsignedInteger('total_bounced')->default(0)->after('total_clicked');
            $table->unsignedInteger('total_spam_complaints')->default(0)->after('total_bounced');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table): void {
            $table->dropColumn(['total_bounced', 'total_spam_complaints']);
        });
    }
};
