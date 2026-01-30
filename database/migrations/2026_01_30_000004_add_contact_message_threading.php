<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add contact message threading support
        Schema::table('contact_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_messages', 'thread_id')) {
                $table->unsignedBigInteger('thread_id')->nullable()->after('id');
                $table->string('category')->default('general')->after('thread_id');
                $table->boolean('is_pinned')->default(false)->after('category');
                $table->timestamp('follow_up_at')->nullable()->after('is_pinned');
                $table->string('assigned_to')->nullable()->after('follow_up_at');
                $table->index(['thread_id', 'created_at']);
                $table->index('category');
                $table->index('assigned_to');
            }
        });

        // Create contact message threads table
        Schema::create('contact_message_threads', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('name');
            $table->string('category')->default('general');
            $table->enum('status', ['new', 'in_progress', 'handled', 'archived'])->default('new');
            $table->unsignedBigInteger('last_message_id')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->string('assigned_to')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index('assigned_to');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_message_threads');
        
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['thread_id', 'category', 'is_pinned', 'follow_up_at', 'assigned_to']);
        });
    }
};
