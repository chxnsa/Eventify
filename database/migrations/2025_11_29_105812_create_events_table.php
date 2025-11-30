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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Creator (admin/organizer)
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->date('date_start');
            $table->date('date_end')->nullable(); // For multi-day events
            $table->time('time_start');
            $table->time('time_end');
            $table->string('location'); // City
            $table->string('venue'); // Venue name
            $table->string('address')->nullable(); // Full address
            $table->string('image')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
