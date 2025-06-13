<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;

class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired';
    protected $description = 'Cancel orders that have expired without payment';

    public function handle()
    {
        $expiredOrders = Order::with('items')
            ->where('status', 'payment_pending')
            ->where('expired_at', '<', Carbon::now())
            ->get();

        foreach ($expiredOrders as $order) {
            $order->status = 'cancelled';
            $order->save();

            // Kembalikan stok produk
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock += $item->quantity;
                    $product->save();
                }
            }
            
            $this->info("Cancelled order #{$order->order_number}");
        }

        $this->info('Cancelled '.$expiredOrders->count().' expired orders.');
    }
}