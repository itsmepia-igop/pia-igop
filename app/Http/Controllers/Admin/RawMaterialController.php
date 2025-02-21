<?php

namespace App\Http\Controllers\Admin;

use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\RawMaterialBatch;
use App\Http\Controllers\Controller;
use App\Http\Requests\RawMaterialRequest;
use App\Http\Requests\RawMaterialBatchRequest;

class RawMaterialController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'];

            $materials = RawMaterial::query();
            $datatables = datatables()::of($materials);

            $datatables->editColumn('updated_at', function ($item) {
                return $item->updated_at->format('M d, Y');
            })->addColumn('edit_route', function ($item) {
                return route('admin.material.update', $item->id);
            })->addColumn('actions', function ($item) {
                $btn = '';
                $edit_route = route('admin.material.edit', $item->id);
                $btn .= "<a href='$edit_route' class='text-muted me-3 btn-edit'><i class='fa-regular fa-pen-to-square me-2'></i>Edit</a>";

                $delete_route = route('admin.material.delete', $item->id);
                $btn .= "<a href='#' class='text-muted btn-remove' data-target='#material-$item->id'><i class='fa-solid fa-trash me-2'></i>Remove</a>
                            <form action='$delete_route' method='post' class='d-none' id='material-$item->id'>". csrf_field() .' '. method_field('delete') ."</form>";

                return $btn;
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where("id", $keyword)
                        ->orWhere('name', 'like', "%$keyword%")
                        // ->orWhere('batch_number', 'like', "%$keyword%")
                        ->orWhere('quantity', 'like', "%$keyword%");
                });
            }
            
            return $datatables->rawColumns(['actions'])->make(true);
        }

        return view('admin.raw-material.index');
    }

    public function store(RawMaterialRequest $request)
    {
        RawMaterial::create([
            'name' => $request->input('name'),
            'quantity' => 0,
            'batch_number' => '',
            // 'quantity' => $request->input('quantity'),
        ]);

        return redirect()->back()->with('message', 'Raw material successfully added.');
    }

    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'];

            $materials = RawMaterialBatch::where('raw_material_id', $id);
            $datatables = datatables()::of($materials);

            $datatables->editColumn('updated_at', function ($item) {
                return $item->updated_at->format('M d, Y');
            })->addColumn('edit_route', function ($item) {
                return route('admin.material.update', $item->id);
            })->addColumn('actions', function ($item) {
                $btn = '';
                $edit_route = route('admin.material.restock.update', ['id' => $item->raw_material_id, 'batch_id' => $item->id]);
                $btn .= "<a href='#' class='text-muted me-3 btn-edit' data-route='$edit_route' data-label-type='Edit'><i class='fa-regular fa-pen-to-square me-2'></i>Edit</a>";

                $delete_route = route('admin.material.restock.delete', ['id' => $item->raw_material_id, 'batch_id' => $item->id]);
                $btn .= "<a href='#' class='text-muted btn-remove' data-target='#material-$item->id'><i class='fa-solid fa-trash me-2'></i>Remove</a>
                            <form action='$delete_route' method='post' class='d-none' id='material-$item->id'>". csrf_field() .' '. method_field('delete') ."</form>";

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

        $raw_material = RawMaterial::find($id);
        if (empty($raw_material)) {
            return redirect()->back()->withErrors(['message' => 'Raw material does not exists.']);
        }

        return view('admin.raw-material.edit', compact('raw_material'));
    }

    public function update(RawMaterialRequest $request, $id)
    {
        $material = RawMaterial::find($id);
        if (empty($material)) {
            return redirect()->back()->withErrors(['message' => 'Raw material does not exists.']);
        }
               
        $material->name = $request->input('name');
        // $material->batch_number = $request->input('batch_number');
        // $material->quantity = $request->input('quantity');
        $material->save();

        return redirect()->back()->with('message', 'Raw material successfully updated.');
    }

    public function delete($id)
    {
        $material = RawMaterial::find($id);
        if (empty($material)) {
            return redirect()->back()->withErrors(['message' => 'Raw material does not exists.']);
        }

        RawMaterialBatch::where('raw_material_id', $material->id)->delete();
        $material->delete();

        return redirect()->back()->with('message', 'Raw material successfully deleted.');
    }

    public function restock(RawMaterialBatchRequest $request, $id)
    {
        $material = RawMaterial::find($id);
        if (empty($material)) {
            return redirect()->back()->withErrors(['message' => 'Raw material does not exists.']);
        }

        RawMaterialBatch::create([
            'raw_material_id' => $material->id,
            'batch_number' => $request->input('batch_number'),
            'quantity' => $request->input('quantity'),
            'total_quantity' => $request->input('quantity')
        ]);

        $material->quantity += $request->input('quantity');
        $material->save();

        return redirect()->back()->with('message', 'Raw material restocked successfully.');
    }

    public function restockUpdate(RawMaterialBatchRequest $request, $id, $batch_id)
    {
        $material = RawMaterial::find($id);
        $batch = RawMaterialBatch::find($batch_id);
        if (empty($material) || empty($batch) || $batch->raw_material_id !== $material->id) {
            return redirect()->back()->withErrors(['message' => 'Raw material batch does not exists.']);
        }

        $old_quantity = $batch->quantity;

        $batch->batch_number = $request->input('batch_number');
        $batch->quantity = $request->input('quantity');
        $batch->save();

        $material->quantity -= ($old_quantity - $request->input('quantity'));
        $material->save();

        return redirect()->back()->with('message', 'Raw material batch updated successfully.');
    }

    public function restockDelete($id, $batch_id)
    {
        $material = RawMaterial::find($id);
        $batch = RawMaterialBatch::find($batch_id);
        if (empty($material) || empty($batch) || $batch->raw_material_id !== $material->id) {
            return redirect()->back()->withErrors(['message' => 'Raw material batch does not exists.']);
        }

        $material->quantity -= $batch->quantity;
        $material->save();

        $batch->delete();

        return redirect()->back()->with('message', 'Raw material batch deleted successfully.');
    }
}
