<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
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

        return view('admin.settings.index', compact('option'));
    }

    public function savePayments(Request $request)
    {
        $validate = $request->validate([
            'data.*.bank' => 'required',
            'data.*.number' => 'required',
            'data.*.qr' => 'required',
            'data.*.previous_qr' => 'nullable'
        ]);

        foreach ($validate['data'] as $key => $data) {
            if (!empty($data['qr'])) {
                $path = Storage::disk('public')->put('/attachments/settings', $data['qr']);
                $validate['data'][$key]['qr'] = $path;
            } elseif (!empty($data['previous_qr'])) {
                $validate['data'][$key]['qr'] = $data['previous_qr'];
            }
        }

        $qr_payment = Setting::where('slug', 'payments')->first();
        if (!empty($qr_payment)) {
            $qr_payment->content = json_encode($validate['data']);
            $qr_payment->save();
        } else {
            Setting::create([
                'slug' => 'payments',
                'content' => json_encode($validate['data'])
            ]);
        }

        return redirect()->back()->with('message', 'QR Code for payment successully uplaoded.');
    }
}
