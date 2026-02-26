<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('agency_name');
            $table->string('dsgn_code')->nullable();
            $table->string('dba')->nullable();
            $table->string('cert_no');
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('address_line_3')->nullable();
            $table->string('city')->nullable();
            $table->string('state_province')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('accountable_manager')->nullable();
            $table->string('accountable_manager_phone')->nullable();
            $table->string('accountable_manager_email')->nullable();
            $table->string('liaison')->nullable();
            $table->string('liaison_phone')->nullable();
            $table->string('liaison_email')->nullable();
            $table->string('rating_accessory')->nullable();
            $table->string('rating_airframe')->nullable();
            $table->string('rating_instrument')->nullable();
            $table->string('rating_limited')->nullable();
            $table->string('rating_powerplant')->nullable();
            $table->string('rating_propeller')->nullable();
            $table->string('rating_radio')->nullable();
            $table->string('bilateral_agreements')->nullable();
            $table->string('image_path')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->date('updated_at_source')->nullable();
            $table->foreignId('group_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stations');
    }
};
