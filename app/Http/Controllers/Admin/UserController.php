<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'];

            $users = User::leftJoin('profiles as p', 'p.user_id', 'users.id')
                         ->where('users.is_admin', false)
                         ->select([
                            'users.*',
                            DB::raw("'Customer' as role_name"),
                            DB::raw("CONCAT(p.name, ' ', p.surname) as full_name"),
                            'p.contact_number',
                            DB::raw("CONCAT(p.street, ', ', p.barangay, ', ', p.province, ', ', p.region) as address"),
                         ]);
            $datatables = datatables()::of($users);

            $datatables->editColumn('created_at', function ($item) {
                return $item->created_at->format('M d, Y');
            })->addColumn('actions', function ($item) {
                return "<a href='#' class='text-muted me-3 btn-view'><i class='fa-regular fa-eye'></i> View</a>";
            })->addColumn('disable_route', function ($item) {
                return route('admin.user.disable', $item->id);
            })->addColumn('enable_route', function ($item) {
                return route('admin.user.enable', $item->id);
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where(DB::raw("CONCAT(p.name, ' ', p.surname)"), 'like', "%$keyword%")
                        ->orWhere('users.email', 'like', "%$keyword%")
                        ->orWhere(DB::raw("'Customer'"), 'like', "%$keyword%");
                });
            }
            
            return $datatables->rawColumns(['actions'])->make(true);
        }

        $admin = false;
        return view('admin.users.index', compact('admin'));
    }

    public function admin(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'];

            $users = User::leftJoin('profiles as p', 'p.user_id', 'users.id')
                         ->where('users.is_admin', true)
                         ->select([
                            'users.*',
                            DB::raw("CASE WHEN users.is_staff THEN 'Staff' ELSE 'Administrator' END as role_name"),
                            DB::raw("CONCAT(p.name, ' ', p.surname) as full_name"),
                            'p.contact_number',
                            DB::raw("CONCAT(p.street, ', ', p.barangay, ', ', p.province, ', ', p.region) as address"),
                         ]);
            $datatables = datatables()::of($users);

            $datatables->editColumn('created_at', function ($item) {
                return $item->created_at->format('M d, Y');
            })->addColumn('actions', function ($item) {
                return "<a href='#' class='text-muted me-3 btn-view'><i class='fa-regular fa-eye'></i> View</a>";
            })->addColumn('disable_route', function ($item) {
                return route('admin.user.disable', $item->id);
            })->addColumn('enable_route', function ($item) {
                return route('admin.user.enable', $item->id);
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where(DB::raw("CONCAT(p.name, ' ', p.surname)"), 'like', "%$keyword%")
                        ->orWhere('users.email', 'like', "%$keyword%")
                        ->orWhere(DB::raw("CASE WHEN users.is_staff THEN 'Staff' ELSE 'Administrator' END"), 'like', "%$keyword%");
                });
            }
            
            return $datatables->rawColumns(['actions'])->make(true);
        }

        $admin = true;
        return view('admin.users.index', compact('admin'));
    }

    public function createAdmin(UserRequest $request) 
    {
        $user = User::create([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'active' => 1,
                'is_admin' => 1,
                'is_staff' => $request->input('user_type') == 'staff' ? 1 : 0
            ]);

        event(new Registered($user));

        return redirect()->back()->with('message', 'Admin user successfully added.');
    }

    public function disable($id)
    {
        $user = User::find($id);
        $user->active = false;
        $user->save();

        return redirect()->back()->with('message', 'User successfully disabled.');
    }

    public function enable($id)
    {
        $user = User::find($id);
        $user->active = true;
        $user->save();

        return redirect()->back()->with('message', 'User successfully enabled.');
    }
}
