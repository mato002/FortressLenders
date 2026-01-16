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
        Schema::table('candidates', function (Blueprint $table) {
            $table->text('bio_data')->nullable()->after('email'); // JSON or text field for bio data
            $table->boolean('bio_data_completed')->default(false)->after('bio_data');
            $table->timestamp('bio_data_completed_at')->nullable()->after('bio_data_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn(['bio_data', 'bio_data_completed', 'bio_data_completed_at']);
        });
    }
};
