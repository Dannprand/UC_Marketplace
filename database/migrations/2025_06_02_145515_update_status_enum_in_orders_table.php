<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom menjadi string sementara untuk menghindari error enum
        DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(50)");

        // Ubah kembali ke ENUM dengan nilai yang diperbarui
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'pending_payment') DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Kembalikan ke ENUM semula
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");
    }
};

