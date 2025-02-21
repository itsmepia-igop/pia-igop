<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Setting;

class PageController extends Controller
{
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    // public function index(string $page)
    // {
    //     if (view()->exists("pages.{$page}")) {
    //         return view("pages.{$page}");
    //     }

    //     return abort(404);
    // }

    public function dashboard()
    {
        $products = Product::leftJoin('product_raw_materials as prm', 'prm.product_id', 'products.id')
                           ->leftJoin('raw_materials as rm', 'rm.id', 'prm.raw_material_id')
                           ->where(function ($query) {
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
                           })
                           ->select(['products.*'])
                           ->distinct()
                           ->inRandomOrder()
                           ->get();
        return view('pages.dashboard', compact('products'));

        $products = [
            [
                'id' => 1,
                'name' => 'Test',
            ]
        ];

        $product_raw_materials = [
            [
                'id' => 1,
                'product_id' => 1,
                'raw_materials_id' => 1,
                'count' => 25
            ],
            [
                'id' => 2,
                'product_id' => 1,
                'raw_materials_id' => 2,
                'count' => 50
            ],
        ];

        $raw_materials = [
            [
                'id' => 1,
                'name' => 'Material 1',
                'quantity' => 30
            ],
            [
                'id' => 2,
                'name' => 'Material 1',
                'quantity' => 30
            ]
        ];
    }

    public function icons()
    {
        return view('pages.icons');
    }

    public function notifications()
    {
        return view('pages.notifications');
    }

    public function termsCondition()
    {
        return view('pages.terms-condition');
    }

    public function paymentOptions()
    {
        $payments = Setting::where('slug', 'payments')->first();
        $options = [];
        if (!empty($payments)) {
            $options = json_decode($payments->content);
        }

        return view('pages.payment-options', compact('options'));
    }
}
