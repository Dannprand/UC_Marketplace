<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('merchants', function (Blueprint $table) {
        // Hapus jika sudah ada (optional)
        if (Schema::hasColumn('merchants', 'merchant_password')) {
            $table->dropColumn('merchant_password');
        }
        
        // Tambahkan kolom baru
        $table->string('merchant_password')->after('merchant_pfp');
    });
}

public function down()
{
    Schema::table('merchants', function (Blueprint $table) {
        $table->dropColumn('merchant_password');
    });
}
};
