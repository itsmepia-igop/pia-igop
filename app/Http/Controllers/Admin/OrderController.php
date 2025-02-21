<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\DefaultNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\ProductBatch;
use App\Models\RawMaterialBatch;

class OrderController extends Controller
{
    public function index(Request $request, $status = null)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'];
            $sales_type = $request->sales_type;

            $products = Order::leftJoin('products as p', 'p.id', 'orders.product_id')
                             ->leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                             ->leftJoin('users as u', 'u.id', 'orders.user_id')
                             ->leftJoin('profiles as pf', 'pf.user_id', 'u.id')
                             ->where(function ($query) use ($status) {
                                if (!empty($status)) {
                                    $query->where('os.name', str_replace('-', ' ', $status));
                                }
                             })
                             ->where(function ($query) use ($sales_type) {
                                if (!empty($sales_type)) {
                                    $query->where('p.is_customize', $sales_type !== 'generic');
                                }    
                             })
                             ->select([
                                'orders.*',
                                'p.name as product_name',
                                'os.name as status',
                                DB::raw("CONCAT(pf.name, ' ', pf.surname) as full_name")
                             ]);

            $datatables = datatables()::of($products);

            $datatables->addColumn('actions', function ($item) {
                $view_route = route('admin.order.show', $item->id);
                return "<a href='$view_route' class='text-muted me-3 btn-view text-nowrap'><i class='fa-regular fa-eye me-2'></i>View</a>";
            })->editColumn('total', function ($item) {
                return '₱ ' . number_format($item->total, 2);
            })->editColumn('updated_at', function ($item) {
                return $item->updated_at->format('M d, Y');
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where(function ($sql) use ($keyword) {
                        $sql->where(DB::raw("CONCAT(YEAR(orders.updated_at), '-', LPAD(orders.id, 6, '0'))"), 'LIKE', "%$keyword%")
                            ->orWhere('p.name', 'LIKE', "%$keyword%")
                            ->orWhere('orders.quantity', 'LIKE', "%$keyword%")
                            ->orWhere('os.name', 'LIKE', "%$keyword%")
                            ->orWhere(DB::raw("CONCAT('₱ ', FORMAT(orders.total, 2))"), 'LIKE', "%$keyword%")
                            ->orWhere(DB::raw('CONCAT(pf.name, " ", pf.surname)'), 'LIKE', "%$keyword%")
                            ->orWhere(DB::raw('DATE_FORMAT(orders.updated_at, "%b %d, %Y")'), 'LIKE', "%$keyword%");
                    });
                });
            }
            
            return $datatables->rawColumns(['actions'])->make(true);
        }
        
        return view('admin.orders.index', compact('status'));
    }

    public function show($id)
    {
        $order = Order::with(['product' => function ($query) {
                $query->withTrashed();    
            }, 'status', 'user'])->find($id);
        if (empty($order)) {
            return redirect()->back()->withErrors(['message' => 'Order does not exists.']);
        }

        return view('admin.orders.show', compact('order'));
    }

    public function changeStatus(Request $request, $status_key, $id)
    {
        $status = OrderStatus::where('name', str_replace('-', ' ', $status_key))->first();
        $order = Order::find($id);
        if (empty($order) || empty($status)) {
            return redirect()->back()->withErrors(['message' => 'Order does not exists.']);
        }
        
        if ($status_key == 'to-pay' && $order->re_request_count >= 3) {
            return redirect()->back()->withErrors(['message' => 'Order does not exists.']);
        }

        if ($status->name == 'On Process') {
            DB::transaction(function () use ($order) {
                $product = $order->product;
                if (!$product->is_customize) {
                    $total_quantity = $order->quantity;
                    $product->quantity -= $total_quantity;
                    $product->last_deducted = now();
                    $product->save();

                    do {
                        $batch = ProductBatch::where('quantity', '>', 0)->where('product_id', $product->id)->oldest()->first();
                        if (empty($batch)) {
                            return redirect()->back()->withErrors(['message' => 'Order does not exists.']);
                        }

                        $difference = min($batch->quantity, $total_quantity);
                        $batch->quantity -= $difference;
                        $total_quantity -= $difference;

                        if ($batch->quantity < 0) {
                            $batch->quantity = 0;
                        }

                        $batch->save();
                    } while ($total_quantity > 0);
                } else {
                    foreach ($product->raw_materials as $material) {
                        $total_quantity = ($material->count * $order->quantity);
                        $raw_material = $material->material;
                        $raw_material->quantity -= $total_quantity;
                        $raw_material->last_deducted = now();
                        $raw_material->save();

                        do {
                            $batch = RawMaterialBatch::where('quantity', '>', 0)->where('raw_material_id', $raw_material->id)->oldest()->first();
                            if (empty($batch)) {
                                return redirect()->back()->withErrors(['message' => 'Order does not exists.']);
                            }
        
                            $difference = min($batch->quantity, $total_quantity);
                            $batch->quantity -= $difference;
                            $total_quantity -= $difference;
        
                            if ($batch->quantity < 0) {
                                $batch->quantity = 0;
                            }
        
                            $batch->save();
                        } while ($total_quantity > 0);
                    }
                }

                if ($product->quantity < config('app.threshold')) {
                    // DB Notification
                    $users = User::where('is_admin', 1)->orWhere('is_staff', 1)->get();
                    $message = "The $product->name is low on stock.";
                    $link = route('admin.product.edit', $product->id);
        
                    Notification::send(
                        $users, 
                        new DefaultNotification($message, $link)
                    );
                }
            });
        }

        if (!empty($request->input('rejection_message'))) {
            $reason = $request->input('rejection_message');
            if ($reason == 'Other') {
                $reason .= ': ' . $request->specific_reason;
            }

            $order->rejection_message = $reason;
        }
        
        $order->re_request_reason = null;
        if (!empty($request->input('re_request_reason'))) {
            $reason = $request->input('re_request_reason');
            if ($reason == 'Other') {
                $reason .= ': ' . $request->specific_reason;
            }

            $order->re_request_reason = $reason;
        }

        // DB Notification
        $message = "The admin has change your order's status.";
        switch ($status_key) {
            case 'to-pay':
                $message = "A payment is required to your order.";
                if ($order->status->name == "To Review Payment") {
                    $message = "Your payment is being resubmitted.";
                    $order->re_request_count += 1;
                }
                break;
            case 'rejected':
                $message = "Your order has been rejected.";
                break;
            case 'on-process':
                $message = "Your order is now processing.";
                break;
            case 'to-deliver':
                $message = "Your order has been shipped.";

                $regions = json_decode(file_get_contents(public_path('json/region.json')));
                $region = array_filter($regions, function ($region) use ($order) {
                    return $region->region_name == $order->region;    
                });
                $region = reset($region)->region_name;

                $days = $order->product->is_customize ? 45 + ($region->estimated_delivery_days ?? 1) : ($region->estimated_delivery_days ?? 3);
                $order->estimate_delivery = now()->addDays($days);
                break;
        }
        $link = route('user.order.show', $order->id);

        Notification::send(
            $order->user, 
            new DefaultNotification($message, $link)
        );

        $order->order_status_id = $status->id;
        $order->save();

        return redirect()->back()->with('message', 'Order status change to '. $status->name);
    }
}
