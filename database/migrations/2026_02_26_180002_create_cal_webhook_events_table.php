<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cal_webhook_events', function (Blueprint $table): void {
            $table->id();
            $table->string('trigger_event');            // e.g. BOOKING_CREATED
            $table->string('booking_uid')->nullable();  // payload.uid if present
            $table->json('payload');                    // full raw payload
            $table->string('status')->default('received'); // received | processed | failed
            $table->timestamp('event_time');            // createdAt from Cal.com
            $table->timestamps();

            $table->index('trigger_event');
            $table->index('status');
            $table->index('booking_uid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cal_webhook_events');
    }
};
