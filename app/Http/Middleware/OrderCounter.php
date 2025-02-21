<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class OrderCounter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $orders_count = [];

        $expired_at = now()->addMinutes(30);
        if (auth()->user() && auth()->user()->is_admin) {
            $orders_count['pending'] = Cache::remember('pending', $expired_at, function () {
                            return Order::leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                                        ->where('os.name', 'Pending')
                                        ->select(['orders.*'])
                                        ->count();
                        });
            $orders_count['to_review'] = Cache::remember('to_review', $expired_at, function () {
                            return Order::leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                                        ->where('os.name', 'To Review Payment')
                                        ->select(['orders.*'])
                                        ->count();
                        });
            $orders_count['on_process'] = Cache::remember('on_process', $expired_at, function () {
                            return Order::leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                                        ->where('os.name', 'On Process')
                                        ->select(['orders.*'])
                                        ->count();
                        });
            $orders_count['to_deliver'] = Cache::remember('to_deliver', $expired_at, function () {
                            return Order::leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                                        ->where('os.name', 'To Deliver')
                                        ->select(['orders.*'])
                                        ->count();
                        });
        } elseif (auth()->user()) {
            $orders_count['to_pay'] = Cache::remember('to_pay', $expired_at, function () {
                            return Order::leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                                        ->where('os.name', 'To Pay')
                                        ->where('orders.user_id', auth()->user()->id)
                                        ->select(['orders.*'])
                                        ->count();
                        });
            $orders_count['on_process'] = Cache::remember('on_process', $expired_at, function () {
                            return Order::leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                                        ->where('os.name', 'On Process')
                                        ->where('orders.user_id', auth()->user()->id)
                                        ->select(['orders.*'])
                                        ->count();
                        });
            $orders_count['to_deliver'] = Cache::remember('to_deliver', $expired_at, function () {
                            return Order::leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                                        ->where('os.name', 'To Deliver')
                                        ->where('orders.user_id', auth()->user()->id)
                                        ->select(['orders.*'])
                                        ->count();
                        });
            $orders_count['rejected'] = Cache::remember('rejected', $expired_at, function () {
                            return Order::leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                                        ->where('os.name', 'Rejected')
                                        ->where('orders.user_id', auth()->user()->id)
                                        ->select(['orders.*'])
                                        ->count();
                        });
            $orders_count['completed'] = Cache::remember('completed', $expired_at, function () {
                            return Order::leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                                        ->where('os.name', 'Completed')
                                        ->where('orders.user_id', auth()->user()->id)
                                        ->select(['orders.*'])
                                        ->count();
                        });
        }
        
        view()->share('orders_count', $orders_count);

        return $next($request);
    }
}
