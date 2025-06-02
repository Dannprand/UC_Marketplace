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
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('merchant_name');
            $table->text('merchant_description')->nullable();
            $table->string('merchant_pfp')->nullable();
            
            // --- Tambahkan dua baris ini di sini ---
            $table->string('account_number')->nullable(); // Kolom untuk nomor rekening
            $table->string('bank_name')->nullable();      // Kolom untuk nama bank
            // --- Akhir penambahan ---

            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('total_sales')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};