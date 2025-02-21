<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;
use App\Models\RawMaterial;

class HomeController extends BaseController
{
    public function index()
    {
        $products = Product::where('is_customize', 0)
                           ->where('quantity', '<', config('app.threshold'))
                           ->distinct()
                           ->limit(5)
                           ->get();

        $materials = RawMaterial::where('quantity', '<', config('app.threshold'))
                            ->distinct()
                            ->limit(5)
                            ->get();
        
        $orders = Order::where('order_status_id', 1)->limit(5)->get();
        
        $cards = [
            'orders' => Order::whereDate('created_at', now())->count(),
            'sales' => Order::whereDate('created_at', now())->sum('total')
        ];

        // Current Week
        $thisWeekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $thisWeekEnd = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $thisWeekSales = [];
        for ($date = $thisWeekStart->copy(); $date->lte($thisWeekEnd); $date->addDay()) {
            $total = Order::whereDate('created_at', $date->toDateString())
                            ->sum('total');
            $thisWeekSales[$date->format('l')] = $total;
        }

        // Last Week
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY);
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek(Carbon::SUNDAY);

        $lastWeekSales = [];
        for ($date = $lastWeekStart->copy(); $date->lte($lastWeekEnd); $date->addDay()) {
            $total = Order::whereDate('created_at', $date->toDateString())
                            ->sum('total');
            $lastWeekSales[$date->format('l')] = $total;
        }

        // Current Month
        $thisMonthStart = Carbon::now()->startOfMonth();
        $thisMonthEnd = Carbon::now()->endOfMonth();

        $thisMonthSales = [];
        for ($date = $thisMonthStart->copy(); $date->lte($thisMonthEnd); $date->addDay()) {
            $total = Order::whereDate('created_at', $date->toDateString())
                            ->sum('total');
            $thisMonthSales[(int) $date->format('d')] = $total;
        }

        // Last Month
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $lastMonthSales = [];
        for ($date = $thisMonthStart->copy(); $date->lte($thisMonthEnd); $date->addDay()) {
            $lastMonthDate = $lastMonthStart->copy()->addDays($date->day - 1);
            
            if ($lastMonthDate->lte($lastMonthEnd)) {
                $total = Order::whereDate('created_at', $lastMonthDate->toDateString())
                                ->sum('total');
                $lastMonthSales[(int) $date->format('d')] = $total;
            } else {
                $lastMonthSales[(int) $date->format('d')] = 0; // Fill with 0 if last month has fewer days
            }
        }

        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];

        // Current Year
        $year = Carbon::now()->year;
        $thisYearSales = Order::select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as total_sales'))
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total_sales', 'month');
        
        $lastYear = $year - 1;
        $lastYearSales = Order::select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as total_sales'))
            ->whereYear('created_at', $lastYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total_sales', 'month');

        $sales = [
            'this_week' => $thisWeekSales,
            'last_week' => $lastWeekSales,
            'this_month' => $thisMonthSales,
            'last_month' => $lastMonthSales,
            'this_year' => [],
            'last_year' => []
        ];

        foreach ($months as $num => $name) {
            $sales['this_year'][$name] = $thisYearSales->get($num, 0);
            $sales['last_year'][$name] = $lastYearSales->get($num, 0);
        }

        return view('admin.dashboard', compact('products', 'materials', 'orders', 'cards', 'sales'));
    }
}
