<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn('calendly_link');
            $table->json('availability')->nullable()->after('fee');
        });

        Schema::table('consultation_bookings', function (Blueprint $table) {
            $table->date('booking_date')->nullable()->after('user_id');
            $table->string('booking_time')->nullable()->after('booking_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->string('calendly_link')->nullable();
            $table->dropColumn('availability');
        });

        Schema::table('consultation_bookings', function (Blueprint $table) {
            $table->dropColumn(['booking_date', 'booking_time']);
        });
    }
};
