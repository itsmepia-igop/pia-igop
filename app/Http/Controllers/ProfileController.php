<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $profile = Auth::user()->profile;

        $view = 'profile.edit';
        if (Auth::user()->is_admin || Auth::user()->is_staff) {
            $view = 'admin.profile.edit';
        }

        $regions = json_decode(file_get_contents(public_path('json/region.json')));
        $provinces = json_decode(file_get_contents(public_path('json/province.json')));
        $cities = json_decode(file_get_contents(public_path('json/city.json')));
        $barangays = json_decode(file_get_contents(public_path('json/barangay.json')));

        return view($view, compact('profile', 'regions', 'provinces', 'cities', 'barangays'));
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
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

        if ($profile = auth()->user()->profile) {
            $profile->name = $request->input('name');
            $profile->surname = $request->input('surname');
            $profile->contact_number = $request->input('contact_number') ?? '';
            $profile->region = $region ?? '';
            $profile->province = $province ?? '';
            $profile->city = $city ?? '';
            $profile->barangay = $barangay ?? '';
            $profile->street = $request->input('street') ?? '';
            $profile->save();
        } else {
            auth()->user()->profile()->create([
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
                'contact_number' => $request->input('contact_number') ?? '',
                'region' => $region ?? '',
                'province' => $province ?? '',
                'city' => $city ?? '',
                'barangay' => $barangay ?? '',
                'street' => $request->input('street') ?? '',
            ]);
        }

        return redirect()->back()->with('message', 'Profile successfully updated.');
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return redirect()->back()->withPasswordStatus(__('Password successfully updated.'));
    }

    public function notifications()
    {
        return view(Auth::user()->is_admin || Auth::user()->is_staff ? 'admin.profile.notifications' : 'profile.notifications');
    }

    public function readNotifications(Request $request)
    {
        $user = Auth::user();
        if(isset($request->notificationIds) && count($request->notificationIds) > 0){
            $notificationIds = $request->notificationIds;
            foreach($notificationIds as $notification_id) {
                $notification = $user->notifications()->where('id', $notification_id)->first();
                if(!empty($notification)) {
                    $notification->markAsRead();
                }
            }
        }

        return redirect()->back()->with('message', 'Successfully mark as read selected notifications.');
    }
}
