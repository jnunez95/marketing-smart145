<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_events', function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->index();
            $table->string('record_type', 50)->index();
            $table->string('recipient')->index();
            $table->string('tag')->nullable()->index();
            $table->foreignId('campaign_log_id')->nullable()->constrained('campaign_logs')->nullOnDelete();
            $table->timestamp('event_at')->index();
            $table->json('metadata')->nullable();
            $table->string('geo_country')->nullable();
            $table->string('geo_country_code', 10)->nullable();
            $table->string('geo_region')->nullable();
            $table->string('geo_city')->nullable();
            $table->string('geo_ip', 45)->nullable();
            $table->string('bounce_type', 50)->nullable();
            $table->unsignedSmallInteger('bounce_type_code')->nullable();
            $table->text('original_link')->nullable();
            $table->string('platform', 50)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('os_name')->nullable();
            $table->string('client_name')->nullable();
            $table->json('raw_payload');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_events');
    }
};
