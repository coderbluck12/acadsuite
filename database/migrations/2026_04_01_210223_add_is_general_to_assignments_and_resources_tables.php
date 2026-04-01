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
        Schema::table('assignments', function (Blueprint $table) {
            $table->boolean('is_general')->default(false)->after('is_published');
        });
        Schema::table('resources', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete()->after('tenant_id');
            $table->boolean('is_general')->default(false)->after('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('is_general');
        });
        Schema::table('resources', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn(['course_id', 'is_general']);
        });
    }
};
