<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // Import the correct Request class
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $regions = json_decode(file_get_contents(public_path('json/region.json')));
        $provinces = json_decode(file_get_contents(public_path('json/province.json')));
        $cities = json_decode(file_get_contents(public_path('json/city.json')));
        $barangays = json_decode(file_get_contents(public_path('json/barangay.json')));

        return view('auth.register', compact('regions', 'provinces', 'cities', 'barangays'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'agree_terms_and_conditions' => ['required'],
            'name' => ['required', 'min:3'],
            'surname' => ['required', 'min:3'],
            'contact_number' => ['required', 'numeric', 'min:10'],
            'region' => ['required'],
            'province' => ['required'],
            'city' => ['required'],
            'barangay' => ['required'],
            'street' => ['required'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'active' => 1,
            'is_admin' => 0,
        ]);

        $regions = json_decode(file_get_contents(public_path('json/region.json')));
        $provinces = json_decode(file_get_contents(public_path('json/province.json')));
        $cities = json_decode(file_get_contents(public_path('json/city.json')));
        $barangays = json_decode(file_get_contents(public_path('json/barangay.json')));

        $region = $this->getNameByCode($regions, $data['region'], 'region_code', 'region_name') ?? '';
        $province = $this->getNameByCode($provinces, $data['province'], 'province_code', 'province_name') ?? '';
        $city = $this->getNameByCode($cities, $data['city'], 'city_code', 'city_name') ?? '';
        $barangay = $this->getNameByCode($barangays, $data['barangay'], 'brgy_code', 'brgy_name') ?? '';

        $user->profile()->create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'contact_number' => $data['contact_number'] ?? '',
            'region' => $region,
            'province' => $province,
            'city' => $city,
            'barangay' => $barangay,
            'street' => $data['street'] ?? '',
        ]);

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
    }

    private function getNameByCode($array, $code, $codeKey, $nameKey)
    {
        $item = array_filter($array, fn($item) => $item->$codeKey == $code);
        return $item ? reset($item)->$nameKey : null;
    }
}
