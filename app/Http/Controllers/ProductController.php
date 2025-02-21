<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Feedback;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use App\Models\ProductRawMaterial;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DefaultNotification;
use Illuminate\Support\Facades\Notification;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'];

            $products = Product::query();
            $datatables = datatables()::of($products);

            $datatables->editColumn('price', function ($item) {
                return '₱' . number_format($item->price, 2);
            })->addColumn('actions', function ($item) {
                return "<a href='#' class='text-muted me-3 btn-view text-nowrap'><i class='fa-regular fa-eye me-2'></i>View</a>";
            })->addColumn('materials', function ($item) {
                return ProductRawMaterial::with(['material'])->where('product_id', $item->id)->get()->toArray();
            })->addColumn('checkout_route', function($item) {
                return route('user.product.checkout', $item->id);
            })->addColumn('description_table', function ($item) {
                return $this->truncate($item->description, 100);
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where("id", $keyword)
                        ->orWhere('name', 'like', "%$keyword%")
                        ->orWhere('description', 'like', "%$keyword%")
                        ->orWhere('quantity', 'like', "%$keyword%")
                        ->orWhere('price', 'like', "%$keyword%");
                });
            }
            
            return $datatables->rawColumns(['actions'])->make(true);
        }
        
        return view('products.index');
    }

    private function truncate($text, $chars = 25) {
        if (strlen($text) <= $chars) {
            return $text;
        }
        $text = $text." ";
        $text = substr($text,0,$chars);
        $text = substr($text,0,strrpos($text,' '));
        $text = $text."...";
        return $text;
    }

    public function show($id)
    {
        $product = Product::with(['raw_materials'])->find($id);
        if(empty($product)) {
            return redirect()->back()->withErrors(['message' => 'Product does not exists.']);
        }
        if ($product->is_customize) {
            $quantity = null;
            foreach ($product->raw_materials as $raw_material) {
                $available = $raw_material->material->quantity;
                $needed = $raw_material->count;

                $possible = floor($available / $needed);

                if ($quantity == null || $possible < $quantity) {
                    $quantity = $possible;
                }
            }

            $product->quantity = $quantity ?? 0;
        }
        
        $feedbacks = Feedback::where('product_id', $product->id)->inRandomOrder()->paginate(5);
        $products = Product::leftJoin('product_raw_materials as prm', 'prm.product_id', 'products.id')
                           ->leftJoin('raw_materials as rm', 'rm.id', 'prm.raw_material_id')
                           ->where('products.id', '!=', $product->id)
                           ->where('is_customize', $product->is_customize)
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
        return view('products.show', compact('product', 'products', 'feedbacks'));
    }

    public function checkout($id)
    {
        $product = Product::with(['raw_materials'])->find($id);
        // if(empty($product) || $product->quantity < 300) {
        //     abort(404);
        // }
        if ($product->is_customize) {
            $quantity = null;
            foreach ($product->raw_materials as $raw_material) {
                $available = $raw_material->material->quantity;
                $needed = $raw_material->count;

                $possible = floor($available / $needed);

                if ($quantity == null || $possible < $quantity) {
                    $quantity = $possible;
                }
            }

            $product->quantity = $quantity ?? 0;
        }
        if($product->quantity == 0) {
            return redirect()->back()->withErrors(['message' => 'Product does not exists.']);
        }

        $regions = json_decode(file_get_contents(public_path('json/region.json')));
        $provinces = json_decode(file_get_contents(public_path('json/province.json')));
        $cities = json_decode(file_get_contents(public_path('json/city.json')));
        $barangays = json_decode(file_get_contents(public_path('json/barangay.json')));

        return view('products.checkout', compact('product', 'regions', 'provinces', 'cities', 'barangays'));
    }

    public function checkoutStore(OrderRequest $request, $id)
    {
        $product = Product::with(['raw_materials'])->find($id);
        if(empty($product)) {
            return redirect()->back()->withErrors(['message' => 'Product does not exists.']);
        }

        if ($request->hasFile('design')) {
            $design = $request->file('design');
            $path = Storage::disk('public')->put('/attachments/design', $design);
            
            $file = File::create([
                'file_name' => $design->getClientOriginalName(),
                'file_mime' => $design->getClientMimeType(),
                'path' => $path,
                'user_id' => auth()->user()->id
            ]);
        }
        
        $regions = json_decode(file_get_contents(public_path('json/region.json')));
        $provinces = json_decode(file_get_contents(public_path('json/province.json')));
        $cities = json_decode(file_get_contents(public_path('json/city.json')));
        $barangays = json_decode(file_get_contents(public_path('json/barangay.json')));

        if (!empty($request->input('region'))) {
            $region = array_filter($regions, function ($region) use ($request) {
                    return $region->region_code == $request->input('region');    
                });
            $region = reset($region)->region_name;
        }
        if (!empty($request->input('province'))) {
            $province = array_filter($provinces, function ($province) use ($request) {
                    return $province->province_code == $request->input('province');    
                });
            $province = reset($province)->province_name;
        }
        if (!empty($request->input('city'))) {
            $city = array_filter($cities, function ($city) use ($request) {
                    return $city->city_code == $request->input('city');    
                });
            $city = reset($city)->city_name;
        }
        if (!empty($request->input('barangay'))) {
            $barangay = array_filter($barangays, function ($barangay) use ($request) {
                    return $barangay->brgy_code == $request->input('barangay');    
                });
            $barangay = reset($barangay)->brgy_name;
        }

        $total = $request->input('quantity') * $product->price;
        $status = OrderStatus::where('name', 'Pending')->first();

        $order = Order::create([
            'order_status_id' => $status->id,
            'region' => $region,
            'province' => $province,
            'city' => $city,
            'barangay' => $barangay,
            'street' => $request->input('street'),
            'quantity' => $request->input('quantity'),
            'total' => $total,
            'product_id' => $product->id,
            'thickness' => $request->input('thickness') ?? '',
            'size' => $request->input('size') ?? '',
            'note' => $request->input('note'),
            'file_id' => $file->id ?? null,
            'user_id' => auth()->user()->id
        ]);

        $users = User::where('is_admin', 1)->orWhere('is_staff', 1)->get();
        $message = "A customer has placed an order.";
        $link = route('admin.order.show', $order->id);

        Notification::send(
            $users, 
            new DefaultNotification($message, $link)
        );

        return redirect()->route('user.order.show', $order->id)->with('message', 'Order has been successfully placed.');
    }
}
