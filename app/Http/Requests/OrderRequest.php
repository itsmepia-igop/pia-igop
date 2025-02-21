<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class OrderRequest extends FormRequest
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
        $id = $this->route('id');
        $product = Product::find($id);

        $rules = [
            'region' => ['required'],
            'province' => ['required'],
            'city' => ['required'],
            'barangay' => ['required'],
            'street' => ['required'],
            'quantity' => ['required', 'integer'],
            'thickness' => ['nullable', 'in:'. join(',', json_decode($product->thickness ?? '[]'))],
            'size' => ['nullable', 'in:'. join(',', json_decode($product->sizes ?? '[]'))],
            'note' => ['nullable'],
            'design' => ['file', 'nullable'],
            'size' => ['required']
        ];

        if ($product->is_customize) {
            $rules['thickness'][0] = 'required';
        }

        return $rules;
    }
}
