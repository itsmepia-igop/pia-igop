<?php

namespace App\Http\Controllers\Admin;

use App\Models\File;
use App\Models\User;
use App\Models\Product;
use App\Models\Feedback;
use App\Models\RawMaterial;
use App\Models\ProductBatch;
use Illuminate\Http\Request;
use App\Models\ProductRawMaterial;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductBatchRequest;
use App\Notifications\DefaultNotification;
use Illuminate\Support\Facades\Notification;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'] ?? '';
            $sales_type = $request->sales_type;

            $products = Product::where(function ($query) use ($sales_type) {
                    if (!empty($sales_type)) {
                        $query->where('is_customize', $sales_type !== 'generic');
                    }    
                });
            $datatables = datatables()::of($products);

            $datatables->editColumn('price', function ($item) {
                return '₱' . number_format($item->price, 2);
            })->addColumn('edit_route', function ($item) {
                return route('admin.product.update', $item->id);
            })->addColumn('actions', function ($item) {
                $btn = '';

                $edit_id = "btn-edit-" . $item->id;
                $edit_route = route('admin.product.edit', $item->id);
                $btn .= "<a href='$edit_route' class='text-muted me-3 btn-edit' id='$edit_id'><i class='fa-regular fa-pen-to-square me-2'></i>Edit</a>";

                $delete_route = route('admin.product.delete', $item->id);
                $btn .= "<a href='#' class='text-muted btn-remove me-3 ' data-target='#product-$item->id'><i class='fa-solid fa-trash me-2'></i>Remove</a>
                            <form action='$delete_route' method='post' class='d-none' id='product-$item->id'>". csrf_field() .' '. method_field('delete') ."</form>";
                $feedbacks_route = route('admin.product.feedbacks', $item->id);
                $btn .= "<a href='$feedbacks_route' class='text-muted'><i class='fa-regular fa-comments me-2'></i>Feedback</a>";

                return $btn;
            })->addColumn('materials', function ($item) {
                return ProductRawMaterial::where('product_id', $item->id)->get()->toArray();
            })->addColumn('design', function ($item) {
                return !empty($item->file_id) ? "<img src='". Storage::url($item->file->path) ."' width='100px' />" : '';
            })->addColumn('customize', function ($item) {
                return $item->is_customize ? "Yes" : "No";
            })->editColumn('quantity', function ($item) {
                // if (!$item->is_customize) return $item->quantity;

                // $quantity = null;
                // foreach ($item->raw_materials as $raw_material) {
                //     $available = $raw_material->material->quantity;
                //     $needed = $raw_material->count;

                //     $possible = floor($available / $needed);

                //     if ($quantity == null || $possible < $quantity) {
                //         $quantity = $possible;
                //     }
                // }

                return $item->quantity ?? 0;
            })->editColumn('updated_at', function ($item) {
                return $item->updated_at->format('M d, Y');
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where(function ($sql) use ($keyword) {
                        $sql->where("id", $keyword)
                            ->orWhere('name', 'like', "%$keyword%")
                            ->orWhere('quantity', 'like', "%$keyword%")
                            ->orWhere(DB::raw("CONCAT('₱ ', FORMAT(price, 2))"), 'like', "%$keyword%")
                            ->orWhere(DB::raw("DATE_FORMAT(updated_at, '%b %d, %Y')"), 'like', "%$keyword%");
                    });
                });
            }
            
            return $datatables->rawColumns(['actions', 'design'])->make(true);
        }

        $materials = RawMaterial::all();
        
        return view('admin.product.index', compact('materials'));
    }

    public function archived(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'] ?? '';

            $products = Product::onlyTrashed();
            $datatables = datatables()::of($products);

            $datatables->editColumn('price', function ($item) {
                return '₱' . number_format($item->price, 2);
            })->addColumn('edit_route', function ($item) {
                return route('admin.product.update', $item->id);
            })->addColumn('actions', function ($item) {
                $btn = '';

                $restore_route = route('admin.product.restore', $item->id);
                $btn .= "<a href='#' class='text-muted btn-restore me-3 ' data-target='#product-restore-$item->id'><i class='fa-solid fa-trash-arrow-up me-2'></i>Restore</a>
                            <form action='$restore_route' method='post' class='d-none' id='product-restore-$item->id'>". csrf_field() .' '. method_field('patch') ."</form>";
                
                $feedbacks_route = route('admin.product.feedbacks', $item->id);
                $btn .= "<a href='$feedbacks_route' class='text-muted'><i class='fa-regular fa-comments me-2'></i>Feedback</a>";

                return $btn;
            })->addColumn('materials', function ($item) {
                return ProductRawMaterial::where('product_id', $item->id)->get()->toArray();
            })->addColumn('design', function ($item) {
                return !empty($item->file_id) ? "<img src='". Storage::url($item->file->path) ."' width='100px' />" : '';
            })->addColumn('customize', function ($item) {
                return $item->is_customize ? "Yes" : "No";
            })->editColumn('quantity', function ($item) {
                // if (!$item->is_customize) return $item->quantity;

                // $quantity = null;
                // foreach ($item->raw_materials as $raw_material) {
                //     $available = $raw_material->material->quantity;
                //     $needed = $raw_material->count;

                //     $possible = floor($available / $needed);

                //     if ($quantity == null || $possible < $quantity) {
                //         $quantity = $possible;
                //     }
                // }

                return $item->quantity ?? 0;
            })->editColumn('updated_at', function ($item) {
                return $item->updated_at->format('M d, Y');
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where(function ($sql) use ($keyword) {
                        $sql->where("id", $keyword)
                            ->orWhere('name', 'like', "%$keyword%")
                            ->orWhere('quantity', 'like', "%$keyword%")
                            ->orWhere(DB::raw("CONCAT('₱ ', FORMAT(price, 2))"), 'like', "%$keyword%")
                            ->orWhere(DB::raw("DATE_FORMAT(updated_at, '%b %d, %Y')"), 'like', "%$keyword%");
                    });
                });
            }
            
            return $datatables->rawColumns(['actions', 'design'])->make(true);
        }

        return view('admin.product.archive');
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create([
            'name' => $request->input('name'),
            // 'quantity' => !empty($request->input('is_customize')) ? 0 : $request->input('quantity'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'is_customize' => $request->input('is_customize') ?? false,
            'sizes' => json_encode($request->input('size')),
        ]);

        if ($request->hasFile('design')) {
            $design = $request->file('design');
            $path = Storage::disk('public')->put('/attachments/design', $design);
            
            $file = File::create([
                'file_name' => $design->getClientOriginalName(),
                'file_mime' => $design->getClientMimeType(),
                'path' => $path,
                'user_id' => auth()->user()->id
            ]);

            $product->file_id = $file->id;
            $product->save();
        }

        if ($request->input('materials_id') && $product->is_customize) {
            foreach ($request->input('materials_id') as $key => $material_id) {
                $product->raw_materials()->create([
                    'raw_material_id' => $material_id,
                    'count' => $request->input('materials_count')[$key]
                ]);
            }

            $product->thickness = json_encode($request->input('thickness'));
            $product->save();
        }

        return redirect()->back()->with('message', 'Product successfully added.');
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::find($id);
        if (empty($product)) {
            return redirect()->back()->withErrors(['message' => 'Product does not exists.']);
        }

        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->is_customize = $request->input('is_customize') ?? false;
        $product->sizes = json_encode($request->input('size'));
        $product->save();

        if ($request->hasFile('design')) {
            $design = $request->file('design');
            $path = Storage::disk('public')->put('/attachments/design', $design);
            
            $file = File::create([
                'file_name' => $design->getClientOriginalName(),
                'file_mime' => $design->getClientMimeType(),
                'path' => $path,
                'user_id' => auth()->user()->id
            ]);

            $product->file_id = $file->id;
            $product->save();
        }

        $product->raw_materials()->delete();

        if ($request->input('materials_id') && $product->is_customize) {
            foreach ($request->input('materials_id') as $key => $material_id) {
                $product->raw_materials()->create([
                    'raw_material_id' => $material_id,
                    'count' => $request->input('materials_count')[$key]
                ]);
            }

            $product->thickness = json_encode($request->input('thickness'));
            $product->quantity = 0;
            $product->save();
            $product->batches()->delete();
        }

        return redirect()->back()->with('message', 'Product successfully updated.');
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if (empty($product)) {
            return redirect()->back()->withErrors(['message' => 'Product does not exists.']);
        }

        $product->delete();

        return redirect()->back()->with('message', 'Product successfully deleted.');
    }

    public function feedbacks(Request $request, $id)
    {
        $product = Product::withTrashed()->find($id);
        if (empty($product)) {
            return redirect()->back()->withErrors(['message' => 'Product does not exists.']);
        }

        if ($request->ajax()) {
            $keyword = $request->search['value'];

            $feedbacks = Feedback::leftJoin('users as u', 'u.id', 'feedback.user_id')
                                ->leftJoin('profiles as p', 'p.user_id', 'u.id')
                                ->select([
                                    'feedback.*',
                                    DB::raw('CONCAT(p.name, " ", p.surname) as full_name')
                                ]);
            $datatables = datatables()::of($feedbacks);

            $datatables->addColumn('actions', function ($item) {
                $btn = '';
                
                if (auth()->user()->is_admin) {
                    $delete_route = route('admin.product.feedback.delete', ['id' => $item->product->id, 'feedback_id' => $item->id]);
                    $btn .= "<a href='#' class='text-muted btn-remove' data-target='#feedback-$item->id'><i class='fa-solid fa-trash me-2'></i>Remove</a>
                                <form action='$delete_route' method='post' class='d-none' id='feedback-$item->id'>". csrf_field() .' '. method_field('delete') ."</form>";
                }

                return $btn;
            })->addColumn('image', function ($item) {
                return !empty($item->file_id) ? "<img src='". Storage::url($item->file->path) ."' width='100px' />" : '';
            })->editColumn('created_at', function ($item) {
                return $item->created_at->format('M d, Y');
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where('feedback.rate', 'like', "%$keyword%")
                        ->orWhere('feedback.message', 'like', "%$keyword%")
                        ->orWhere(DB::raw("CONCAT(p.name, ' ', p.surname)"), 'like', "%$keyword%")
                        ->orWhere(DB::raw("DATE_FORMAT(feedback.created_at, '%b %d, %Y')"), 'like', "%$keyword%");
                });
            }
            
            return $datatables->rawColumns(['actions', 'image'])->make(true);
        }

        return view('admin.product.feedbacks', compact('product'));
    }

    public function deleteFeedback($id, $feedback_id)
    {
        $product = Product::find($id);
        if (empty($product)) {
            return redirect()->back()->withErrors(['message' => 'Product does not exists.']);
        }

        $feedback = $product->feedbacks->where('id', $feedback_id)->first();
        if (empty($feedback)) {
            return redirect()->back()->withErrors(['message' => 'Product feedback does not exists.']);
        }

        $feedback->delete();

        return redirect()->back()->with('message', 'Feedback successfully deleted.');
    }

    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'];

            $products = ProductBatch::where('product_id', $id);
            $datatables = datatables()::of($products);

            $datatables->editColumn('updated_at', function ($item) {
                return $item->updated_at->format('M d, Y');
            })->addColumn('edit_route', function ($item) {
                return route('admin.product.update', $item->id);
            })->addColumn('actions', function ($item) {
                $btn = '';
                $edit_route = route('admin.product.restock.update', ['id' => $item->product_id, 'batch_id' => $item->id]);
                $btn .= "<a href='#' class='text-muted me-3 btn-edit' data-route='$edit_route' data-label-type='Edit'><i class='fa-regular fa-pen-to-square me-2'></i>Edit</a>";

                $delete_route = route('admin.product.restock.delete', ['id' => $item->product_id, 'batch_id' => $item->id]);
                $btn .= "<a href='#' class='text-muted btn-remove' data-target='#product-$item->id'><i class='fa-solid fa-trash me-2'></i>Remove</a>
                            <form action='$delete_route' method='post' class='d-none' id='product-$item->id'>". csrf_field() .' '. method_field('delete') ."</form>";

                return $btn;
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where("id", $keyword)
                        // ->orWhere('name', 'like', "%$keyword%")
                        ->orWhere('batch_number', 'like', "%$keyword%")
                        ->orWhere('quantity', 'like', "%$keyword%");
                });
            }
            
            return $datatables->rawColumns(['actions'])->make(true);
        }

        $product = Product::find($id);
        if (empty($product)) {
            return redirect()->back()->withErrors(['message' => 'Product does not exists.']);
        }
        $materials = RawMaterial::all();

        return view('admin.product.edit', compact('product', 'materials'));
    }

    public function restock(ProductBatchRequest $request, $id)
    {
        $product = Product::find($id);
        if (empty($product)) {
            return redirect()->back()->withErrors(['message' => 'Product does not exists.']);
        }

        ProductBatch::create([
            'product_id' => $product->id,
            'batch_number' => $request->input('batch_number'),
            'quantity' => $request->input('quantity'),
            'total_quantity' => $request->input('quantity')
        ]);

        $product->quantity += $request->input('quantity');
        $product->save();
        
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

        return redirect()->back()->with('message', 'Product restocked successfully.');
    }

    public function restockUpdate(ProductBatchRequest $request, $id, $batch_id)
    {
        $product = Product::find($id);
        $batch = ProductBatch::find($batch_id);
        if (empty($product) || empty($batch) || $batch->product_id !== $product->id) {
            return redirect()->back()->withErrors(['message' => 'Product batch does not exists.']);
        }

        $old_quantity = $batch->quantity;

        $batch->batch_number = $request->input('batch_number');
        $batch->quantity = $request->input('quantity');
        $batch->save();

        $product->quantity -= ($old_quantity - $request->input('quantity'));
        $product->save();

        return redirect()->back()->with('message', 'Product batch updated successfully.');
    }

    public function restockDelete($id, $batch_id)
    {
        $product = Product::find($id);
        $batch = ProductBatch::find($batch_id);
        if (empty($product) || empty($batch) || $batch->product_id !== $product->id) {
            return redirect()->back()->withErrors(['message' => 'Product batch does not exists.']);
        }

        $product->quantity -= $batch->quantity;
        $product->save();

        $batch->delete();

        return redirect()->back()->with('message', 'Product batch deleted successfully.');
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->find($id);
        if (empty($product)) {
            return redirect()->back()->withErrors(['message' => 'Product does not exists.']);
        }

        $product->restore();

        return redirect()->back()->with('message', 'Product restored successfully.');
    }
}
