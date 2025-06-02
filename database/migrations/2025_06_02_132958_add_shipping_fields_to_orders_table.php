<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingFieldsToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_provider')->nullable()->after('tracking_number');
            $table->timestamp('shipped_at')->nullable()->after('shipping_provider');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->timestamp('estimated_delivery')->nullable()->after('delivered_at');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_provider',
                'shipped_at',
                'delivered_at',
                'estimated_delivery',
            ]);
        });
    }
}
