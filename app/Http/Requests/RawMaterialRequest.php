<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RawMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'unique:raw_materials,name'],
            // 'batch_number' => ['required'],
            // 'quantity' => ['required', 'integer'],
        ];

        $id = $this->route('id');
        if (!empty($id)) {
            $rules['name'] = ['required', 'unique:raw_materials,name,'.$id];
        }

        return $rules;
    }
}
