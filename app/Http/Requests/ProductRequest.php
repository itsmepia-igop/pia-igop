<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => ['required', 'unique:products,name'],
            // 'quantity' => ['required_unless:is_customize, 1'],
            'price' => ['required', 'numeric'],
            'description' => ['required'],
            'is_customize' => ['nullable'],
            'materials_id.*' => ['required_if:is_customize, 1'],
            'materials_count.*' => ['required_if:is_customize, 1'],
            'design' => ['required', 'max:1000', 'image'],
            'size.*' => ['required'],
            'thickness.*' => ['required_if:is_customize, 1']
        ];

        $id = $this->route('id');
        if (!empty($id)) {
            // $rules['quantity'] = ['nullable'];
            $rules['name'] = ['required', 'unique:products,name,'. $id];
            $rules['design'] = ['nullable'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'materials_id.*.required_if' => 'The material type is required',
            'materials_count.*.required_if' => 'The material per product is required',
        ];
    }
}
