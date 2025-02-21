<?php

namespace App\Http\Controllers\Admin;

use stdClass;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Exports\SalesReport;
use Illuminate\Http\Request;
use App\Models\ProductRawMaterial;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Cache;
use OpenSpout\Common\Entity\Style\Style;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Exports\InventoryReport;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'];
            // $start = $request->start;
            // $end = $request->end;
            [$start, $end] = explode(' - ', $request->date ?? ' - ');
            $sales_type = $request->sales_type;

            $orders = Order::leftJoin('products as p', 'p.id', 'orders.product_id')
                             ->leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                             ->leftJoin('users as u', 'u.id', 'orders.user_id')
                             ->leftJoin('profiles as pf', 'pf.user_id', 'u.id')
                             ->where('os.name', 'Completed')
                             ->where(function ($query) use ($start, $end) {
                                if (!empty($start)) {
                                    $query->whereDate('orders.created_at', '>=', Carbon::createFromFormat('m/d/Y', $start)->format('Y-m-d'));
                                }
                                if (!empty($end)) {
                                    $query->whereDate('orders.created_at', '<=', Carbon::createFromFormat('m/d/Y', $end)->format('Y-m-d'));
                                }
                             })
                             ->where(function ($query) use ($sales_type) {
                                if (!empty($sales_type)) {
                                    $query->where('p.is_customize', $sales_type !== 'generic');
                                }
                             })
                             ->select([
                                'orders.id',
                                'p.name',
                                'orders.thickness',
                                'orders.size',
                                'orders.quantity',
                                'orders.created_at',
                                DB::raw("CONCAT('₱ ', FORMAT(orders.total / orders.quantity, 2)) as price"),
                                DB::raw("CONCAT('₱ ', FORMAT(orders.total, 2)) as total"),
                                DB::raw('DATE_FORMAT(orders.updated_at, "%b %d, %Y") as date_complete')
                             ]);

            $datatables = datatables()::of($orders);

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where(function ($sql) use ($keyword) {
                        $sql->where('p.name', 'LIKE', "%$keyword%")
                            ->orWhere('orders.thickness', 'LIKE', "%$keyword%")
                            ->orWhere('orders.size', 'LIKE', "%$keyword%")
                            ->orWhere('orders.quantity', 'LIKE', "%$keyword%")
                            ->orWhere(DB::raw("CONCAT('₱ ', FORMAT(orders.total / orders.quantity, 2))"), 'LIKE', "%$keyword%")
                            ->orWhere(DB::raw("CONCAT('₱ ', FORMAT(orders.total, 2))"), 'LIKE', "%$keyword%")
                            ->orWhere(DB::raw('DATE_FORMAT(orders.updated_at, "%b %d, %Y")'), 'LIKE', "%$keyword%");;
                    });
                });
            }
            
            return $datatables->make(true);
        }
        
        return view('admin.reports.sales');
    }

    public function salesExport(Request $request)
    {   
        // $keyword = $request->search['value'];
        [$start, $end] = explode(' - ', $request->date ?? ' - ');
        $sales_type = $request->sales_type;
        $keyword = $request->keyword;

        $orders = Order::leftJoin('products as p', 'p.id', 'orders.product_id')
                         ->leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                         ->leftJoin('users as u', 'u.id', 'orders.user_id')
                         ->leftJoin('profiles as pf', 'pf.user_id', 'u.id')
                         ->where('os.name', 'Completed')
                         ->where(function ($query) use ($start, $end) {
                            if (!empty($start)) {
                                $query->whereDate('orders.created_at', '>=', Carbon::createFromFormat('m/d/Y', $start)->format('Y-m-d'));
                            }
                            if (!empty($end)) {
                                $query->whereDate('orders.created_at', '<=', Carbon::createFromFormat('m/d/Y', $end)->format('Y-m-d'));
                            }
                         })
                         ->where(function ($query) use ($sales_type) {
                            if (!empty($sales_type)) {
                                $query->where('p.is_customize', $sales_type !== 'generic');
                            }
                         })
                         ->select([
                            'orders.id',
                            'p.name',
                            'orders.thickness',
                            'orders.size',
                            'orders.quantity',
                            'orders.created_at',
                            DB::raw("CONCAT('₱ ', FORMAT(orders.total / orders.quantity, 2)) as price"),
                            DB::raw("CONCAT('₱ ', FORMAT(orders.total, 2)) as total"),
                            DB::raw('DATE_FORMAT(orders.updated_at, "%b %d, %Y") as date_complete')
                         ]);

        if (!empty($keyword)) {
            $orders->where(function ($sql) use ($keyword) {
                $sql->where('p.name', 'LIKE', "%$keyword%")
                    ->orWhere('orders.thickness', 'LIKE', "%$keyword%")
                    ->orWhere('orders.size', 'LIKE', "%$keyword%")
                    ->orWhere('orders.quantity', 'LIKE', "%$keyword%")
                    ->orWhere(DB::raw("CONCAT('₱ ', FORMAT(orders.total / orders.quantity, 2))"), 'LIKE', "%$keyword%")
                    ->orWhere(DB::raw("CONCAT('₱ ', FORMAT(orders.total, 2))"), 'LIKE', "%$keyword%")
                    ->orWhere(DB::raw('DATE_FORMAT(orders.updated_at, "%b %d, %Y")'), 'LIKE', "%$keyword%");;
            });
        }

        $orders = $orders->get();

        $export_data = new SalesReport(
            $orders,
            $request->date,
        );

        return Excel::download($export_data, 'sales.xlsx');
    }

    public function productInventory(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'];

            $invetories = Product::withTrashed()
                                ->leftJoin('product_batches as pb', 'pb.product_id', 'products.id')
                                // ->leftJoinSub(
                                //     DB::table('product_batches as sub_pb')
                                //         ->select([
                                //             'sub_pb.product_id',
                                //             DB::raw('
                                //                 TIMESTAMPDIFF(
                                //                     DAY, 
                                //                     LAG(sub_pb.created_at) OVER (PARTITION BY sub_pb.product_id ORDER BY sub_pb.created_at), 
                                //                     sub_pb.created_at
                                //                 ) AS restock_gap
                                //             ')
                                //         ]),
                                //     'gaps',
                                //     'gaps.product_id',
                                //     'products.id'
                                // )
                                ->select([
                                    'products.id',
                                    'products.name',
                                    'products.description',
                                    'products.quantity',
                                    DB::raw("CONCAT('₱ ', FORMAT(products.price, 2)) AS price"),
                                    DB::raw("CONCAT('₱ ', FORMAT(products.quantity * products.price, 2)) AS value"),
                                    // DB::raw("COALESCE(FLOOR(AVG(gaps.restock_gap)), 0) AS avg_restock"),
                                    // DB::raw("COALESCE(FLOOR(AVG(pb.total_quantity)), 0) AS avg_quantity"),
                                    DB::raw("CASE WHEN products.deleted_at IS NOT NULL THEN 'Yes' ELSE 'No' END AS is_deleted"),
                                    DB::raw("COALESCE(DATE_FORMAT(products.last_deducted, '%b %d, %Y'), 'N/A') as last_deducted"),
                                    DB::raw("COALESCE(DATE_FORMAT((SELECT MAX(created_at) FROM product_batches WHERE product_batches.product_id = products.id), '%b %d, %Y'), 'N/A') as last_restock")
                                ])
                                ->groupBy('products.id', 'products.name', 'products.description', 'products.quantity', 'products.price', 'products.deleted_at', 'products.last_deducted')
                                ->where('is_customize', 0);
            $datatables = datatables()::of($invetories);

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->having(function ($sql) use ($keyword) {
                        $sql->having('name', 'like', "%$keyword%")
                            ->orhaving('description', 'like', "%$keyword%")
                            ->orhaving('quantity', 'like', "%$keyword%")
                            ->orhaving('price', 'like', "%$keyword%")
                            ->orhaving('value', 'like', "%$keyword%")
                            // ->orhaving('avg_restock', 'like', "%$keyword%")
                            // ->orhaving('avg_quantity', 'like', "%$keyword%")
                            ->orhaving('last_deducted', 'like', "%$keyword%")
                            ->orhaving('last_restock', 'like', "%$keyword%")
                            ->orhaving('is_deleted', 'like', "%$keyword%");
                    });
                });
            }
            
            return $datatables->rawColumns(['actions'])->make(true);
        }
        
        return view('admin.reports.inventory');
    }

    public function materialInventory(Request $request)
    {
        $keyword = $request->search['value'];

        $invetories = RawMaterial::leftJoin('raw_material_batches as rmb', 'rmb.raw_material_id', 'raw_materials.id')
                            // ->leftJoinSub(
                            //     DB::table('raw_material_batches as sub_rmb')
                            //         ->select([
                            //             'sub_rmb.raw_material_id',
                            //             DB::raw('
                            //                 TIMESTAMPDIFF(
                            //                     DAY, 
                            //                     LAG(sub_rmb.created_at) OVER (PARTITION BY sub_rmb.raw_material_id ORDER BY sub_rmb.created_at), 
                            //                     sub_rmb.created_at
                            //                 ) AS restock_gap
                            //             ')
                            //         ]),
                            //     'gaps',
                            //     'gaps.raw_material_id',
                            //     'raw_materials.id'
                            // )
                            ->select([
                                'raw_materials.id',
                                'raw_materials.name',
                                'raw_materials.quantity',
                                // DB::raw("COALESCE(FLOOR(AVG(gaps.restock_gap)), 0) AS avg_restock"),
                                // DB::raw("COALESCE(FLOOR(AVG(rmb.total_quantity)), 0) AS avg_quantity"),
                                DB::raw("COALESCE(DATE_FORMAT(raw_materials.last_deducted, '%b %d, %Y'), 'N/A') as last_deducted"),
                                DB::raw("COALESCE(DATE_FORMAT((SELECT MAX(created_at) FROM raw_material_batches WHERE raw_material_batches.raw_material_id = raw_materials.id), '%b %d, %Y'), 'N/A') as last_restock")
                            ])
                            ->groupBy('raw_materials.id', 'raw_materials.name', 'raw_materials.quantity');
        $datatables = datatables()::of($invetories);

        if (!empty($keyword)) {
            $datatables->filter(function ($query) use ($keyword) {
                $query->having(function ($sql) use ($keyword) {
                    $sql->having('name', 'like', "%$keyword%")
                        ->orhaving('quantity', 'like', "%$keyword%")
                        // ->orhaving('avg_restock', 'like', "%$keyword%")
                        // ->orhaving('avg_quantity', 'like', "%$keyword%")
                        ->orhaving('last_deducted', 'like', "%$keyword%")
                        ->orhaving('last_restock', 'like', "%$keyword%");
                });
            });
        }
        
        return $datatables->rawColumns(['actions'])->make(true);
    }

    public function inventoryExport(Request $request)
    {
        $products = Product::withTrashed()
                            ->leftJoin('product_batches as pb', 'pb.product_id', 'products.id')
                            ->select([
                                'products.id',
                                'products.name',
                                'products.description',
                                'products.quantity',
                                DB::raw("CONCAT('₱ ', FORMAT(products.price, 2)) AS price"),
                                DB::raw("CONCAT('₱ ', FORMAT(products.quantity * products.price, 2)) AS value"),
                                DB::raw("CASE WHEN products.deleted_at IS NOT NULL THEN 'Yes' ELSE 'No' END AS is_deleted"),
                                DB::raw("COALESCE(DATE_FORMAT(products.last_deducted, '%b %d, %Y'), 'N/A') as last_deducted"),
                                DB::raw("COALESCE(DATE_FORMAT((SELECT MAX(created_at) FROM product_batches WHERE product_batches.product_id = products.id), '%b %d, %Y'), 'N/A') as last_restock")
                            ])
                            ->groupBy('products.id', 'products.name', 'products.description', 'products.quantity', 'products.price', 'products.deleted_at', 'products.last_deducted')
                            ->where('is_customize', 0);

        $keyword = $request->product_keyword;
        if (!empty($keyword)) {
            $products->having(function ($sql) use ($keyword) {
                $sql->having('name', 'like', "%$keyword%")
                    ->orhaving('description', 'like', "%$keyword%")
                    ->orhaving('quantity', 'like', "%$keyword%")
                    ->orhaving('price', 'like', "%$keyword%")
                    ->orhaving('value', 'like', "%$keyword%")
                    ->orhaving('last_deducted', 'like', "%$keyword%")
                    ->orhaving('last_restock', 'like', "%$keyword%")
                    ->orhaving('is_deleted', 'like', "%$keyword%");
            });
        }
        $products = $products->get();


        $materials = RawMaterial::leftJoin('raw_material_batches as rmb', 'rmb.raw_material_id', 'raw_materials.id')
                            ->select([
                                'raw_materials.id',
                                'raw_materials.name',
                                'raw_materials.quantity',
                                DB::raw("COALESCE(DATE_FORMAT(raw_materials.last_deducted, '%b %d, %Y'), 'N/A') as last_deducted"),
                                DB::raw("COALESCE(DATE_FORMAT((SELECT MAX(created_at) FROM raw_material_batches WHERE raw_material_batches.raw_material_id = raw_materials.id), '%b %d, %Y'), 'N/A') as last_restock")
                            ])
                            ->groupBy('raw_materials.id', 'raw_materials.name', 'raw_materials.quantity', 'raw_materials.last_deducted');

        $keyword = $request->material_keyword;
        if (!empty($keyword)) {
            $materials->having(function ($sql) use ($keyword) {
                $sql->having('name', 'like', "%$keyword%")
                    ->orhaving('quantity', 'like', "%$keyword%")
                    ->orhaving('last_deducted', 'like', "%$keyword%")
                    ->orhaving('last_restock', 'like', "%$keyword%");
            });
        }

        $materials = $materials->get();

        
        $export_data = new InventoryReport(
            $products,
            $materials,
        );

        return Excel::download($export_data, 'invoices.xlsx');
    }
}
