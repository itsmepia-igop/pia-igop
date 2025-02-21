<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => ['required', 'min:3'],
            'surname' => ['required', 'min:3'],
        ];

        if (!auth()->user()->is_admin) {
            $rules['contact_number'] = ['required', 'numeric', 'min:10'];
            $rules['region'] = ['required'];
            $rules['province'] = ['required'];
            $rules['city'] = ['required'];
            $rules['barangay'] = ['required'];
            $rules['street'] = ['required'];
        }

        return $rules;
    }
}
