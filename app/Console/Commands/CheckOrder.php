<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Console\Command;
use App\Notifications\DefaultNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class CheckOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check order status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // To Pay status reaches 2 days
        $date = Carbon::now()->subDays(2);

        $status = OrderStatus::where('name', 'To Pay')->first();
        $orders = Order::where('order_status_id', $status->id)->whereDate('updated_at', $date)->get();

        $cancel_status = OrderStatus::where('name', 'Cancelled')->first();
        foreach ($orders as $order) {
            $order->cancel_reason = "System: Order canceled due to incomplete payment within the required timeframe.";
            $order->order_status_id = $cancel_status->id;
            $order->save();
            
            // user - DB Notification
            $link = route('user.order.show', $order->id);
            $message = "Your order has been canceled due to unpaid status.";

            Notification::send(
                $order->user, 
                new DefaultNotification($message, $link)
            );
            
            // Admin - DB Notification
            $users = User::where('is_admin', 1)->orWhere('is_staff', 1)->get();
            $message = "Order has been canceled due to unpaid status.";
            $link = route('admin.order.show', $order->id);

            Notification::send(
                $users, 
                new DefaultNotification($message, $link)
            );
        }

        // Pending status reaches 1 week
        $date = Carbon::now()->subWeek();

        $status = OrderStatus::where('name', 'Pending')->first();
        $orders = Order::where('order_status_id', $status->id)->whereDate('updated_at', $date)->get();

        $cancel_status = OrderStatus::where('name', 'Rejected')->first();
        foreach ($orders as $order) {
            $order->rejection_message = "System: Order automatically rejected after remaining pending for a week.";
            $order->order_status_id = $cancel_status->id;
            $order->save();
            
            // DB Notification
            $link = route('user.order.show', $order->id);
            $message = "Your order has been rejected.";

            Notification::send(
                $order->user, 
                new DefaultNotification($message, $link)
            );
        }

        // More than estimated oders
        $date = Carbon::parse('2025-01-14');

        $status = OrderStatus::where('name', 'To Deliver')->first();
        $orders = Order::where('order_status_id', $status->id)->where(DB::raw('DATE_FORMAT(estimate_delivery, "%Y-%m-%d")'), $date->format('Y-m-d'))->get();

        foreach ($orders as $order) {
            $order->estimate_delivery = now()->addDay();
            $order->save();
            
            // DB Notification
            $link = route('user.order.show', $order->id);
            $message = "Due to delivery issues, there might be some delay in your order.";

            Notification::send(
                $order->user,
                new DefaultNotification($message, $link)
            );
        }
    }
}
