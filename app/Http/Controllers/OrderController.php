<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Feedback;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Notifications\DefaultNotification;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $orders = Order::leftJoin('products as p', 'p.id', 'orders.product_id')
                       ->leftJoin('files as f', 'f.id', 'p.file_id')
                       ->leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                       ->where('orders.user_id', auth()->user()->id)
                       ->select([
                            'orders.*',
                            'p.name as product_name',
                            'p.description as product_description',
                            'f.path as file_path',
                            'os.name as status'
                       ])->get();
        $statuses = OrderStatus::all();
        return view('orders.index', compact('statuses', 'orders'));
    }

    public function show($id)
    {
        $order = Order::with(['product' => function ($query) {
                $query->withTrashed()
                    ->with(['feedbacks']);
            }, 'status'])->find($id);
        if (empty($order) || $order->user_id !== auth()->user()->id) {
            return redirect()->back()->withErrors(['message' => 'Order does not exists.']);
        }

        $products = Product::leftJoin('product_raw_materials as prm', 'prm.product_id', 'products.id')
                           ->leftJoin('raw_materials as rm', 'rm.id', 'prm.raw_material_id')
                           ->where('products.id', '!=', $order->product_id)
                           ->where(function ($res) {
                                $res->where(function ($query) {
                                    $query->where('products.is_customize', false)
                                        ->where('products.quantity', '>', 0);
                                })->orWhere(function ($query) {
                                        $query->where('products.is_customize', true)
                                            ->whereRaw("NOT EXISTS (
                                                SELECT 1
                                                FROM product_raw_materials prm_sub
                                                JOIN raw_materials rm_sub ON rm_sub.id = prm_sub.raw_material_id
                                                WHERE prm_sub.product_id = products.id
                                                AND rm_sub.quantity < prm_sub.count
                                            )");
                                });
                           })
                           ->select(['products.*'])
                           ->distinct()
                           ->inRandomOrder()
                           ->limit(6)
                           ->get();
        $payments = Setting::where('slug', 'payments')->first();
        $options = [];
        if (!empty($payments)) {
            $options = json_decode($payments->content);
        }

        $option = null;
        foreach ($options as $option) {
            if (in_array(strtolower($option->bank), ['gcash', 'g-cash'])) {
                $option = $option;
                break;
            }
        }

        return view('orders.show', compact('order', 'products', 'option'));
    }

    public function uploadPayment(Request $request, $id)
    {
        $order = Order::find($id);
        if (empty($order) || $order->user_id !== auth()->user()->id) {
            return redirect()->back()->withErrors(['message' => 'Order does not exists.']);
        }

        $validate = Validator::make($request->all(), [
            'payment' => !empty($order->payment) ? 'nullable' : 'required',
            'payment_reference' => 'required',
            'payment_type' => 'required'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withError('Something went wrong when uploading payment.')->withErrors($validate);
        }

        if ($request->hasFile('payment')) {
            $payment = $request->file('payment');
            $path = Storage::disk('public')->put('/attachments/payment', $payment);
            
            $file = File::create([
                'file_name' => $payment->getClientOriginalName(),
                'file_mime' => $payment->getClientMimeType(),
                'path' => $path,
                'user_id' => auth()->user()->id
            ]);
            
            $order->payment = $file->id;
        }

        $status = OrderStatus::where('name', 'To Review Payment')->first();

        $order->order_status_id = $status->id;
        $order->payment_reference = $request->input('payment_reference');
        $order->payment_type = $request->input('payment_type');
        $order->save();

        // DB Notification
        $users = User::where('is_admin', 1)->orWhere('is_staff', 1)->get();
        $message = "A customer has uploaded a payment.";
        $link = route('admin.order.show', $order->id);

        Notification::send(
            $users, 
            new DefaultNotification($message, $link)
        );

        return redirect()->back()->with('message', 'Payment successfully uploaded.');
    }

    public function cancel(Request $request, $id)
    {
        $order = Order::find($id);
        if (empty($order) || $order->user_id !== auth()->user()->id) {
            return redirect()->back()->withErrors(['message' => 'Order does not exists.']);
        }

        $status = OrderStatus::where('name', 'Cancelled')->first();

        $reason = $request->cancel_reason;
        if ($reason == 'Other') {
            $reason .= ': ' . $request->specific_reason;
        }

        $order->order_status_id = $status->id;
        $order->cancel_reason = $reason;
        $order->re_request_reason = null;
        $order->save();

        // DB Notification
        $users = User::where('is_admin', 1)->orWhere('is_staff', 1)->get();
        $message = "A customer has canceled the order.";
        $link = route('admin.order.show', $order->id);

        Notification::send(
            $users, 
            new DefaultNotification($message, $link)
        );

        return redirect()->back()->with('message', 'Order cancelled successfully.');
    }

    public function receive($id)
    {
        $order = Order::find($id);
        if (empty($order) || $order->user_id !== auth()->user()->id) {
            return redirect()->back()->withErrors(['message' => 'Order does not exists.']);
        }

        $status = OrderStatus::where('name', 'Completed')->first();

        $order->estimate_delivery = null;
        $order->order_status_id = $status->id;
        $order->save();
        
        // DB Notification
        $users = User::where('is_admin', 1)->orWhere('is_staff', 1)->get();
        $message = "A customer has received the order.";
        $link = route('admin.order.show', $order->id);

        Notification::send(
            $users, 
            new DefaultNotification($message, $link)
        );

        return redirect()->back()->with('message', 'Order received successfully.');
    }

    public function feedback(Request $request, $id)
    {
        $validate = $request->validate([
            'img' => 'nullable',
            'rate' => 'required',
            'message' => 'nullable'
        ]);

        $order = Order::find($id);
        if (empty($order) || $order->user_id !== auth()->user()->id) {
            return redirect()->back()->withErrors(['message' => 'Order does not exists.']);
        }

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $path = Storage::disk('public')->put('/attachments/feedback', $img);
            
            $file = File::create([
                'file_name' => $img->getClientOriginalName(),
                'file_mime' => $img->getClientMimeType(),
                'path' => $path,
                'user_id' => auth()->user()->id
            ]);
        }

        Feedback::create([
            'user_id' => auth()->user()->id,
            'product_id' => $order->product_id,
            'file_id' => $file->id ?? null,
            'rate' => $validate['rate'],
            'message' => $validate['message'] ?? null
        ]);

        
        // DB Notification
        $users = User::where('is_admin', 1)->orWhere('is_staff', 1)->get();
        $message = "A customer has added a feedback.";
        $link = route('admin.product.feedbacks', $order->product->id);

        Notification::send(
            $users, 
            new DefaultNotification($message, $link)
        );
        
        return redirect()->back()->with('message', 'Feedback successfully added.');
    }
}
