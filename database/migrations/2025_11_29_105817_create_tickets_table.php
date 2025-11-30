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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // VIP, Regular, Early Bird, etc.
            $table->text('description')->nullable();
            $table->text('benefits')->nullable(); // JSON or text for benefits list
            $table->decimal('price', 12, 2);
            $table->integer('quota'); // Total available tickets
            $table->integer('sold')->default(0); // Number of tickets sold
            $table->string('image')->nullable();
            $table->datetime('sale_start')->nullable();
            $table->datetime('sale_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
