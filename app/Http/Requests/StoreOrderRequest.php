<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class StoreOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_name'           => 'required|string',
            'customer_email'          => 'required|email',
            'customer_street_name'    => 'required|string',
            'customer_house_number'   => 'required|string',
            'customer_postal_code'    => 'required|string',
            'customer_city'           => 'required|string',
            'customer_phone_number'   => 'required|string',
            'note'                    => 'nullable|string',
            'product_id'              => 'required|integer|exists:products,id',
            'amount'                  => 'required|integer|min:1|max:' . Product::find($this->input('product_id'))->stock,
        ];
    }

    public function messages()
    {
        return [
            'amount.max' => 'The order amount exceeds the available stock (:max).',
            'amount.min' => 'The order amount must be at least :min.',
            'product_id.exists' => 'The selected product does not exist.',
            'product_id.integer' => 'The selected product is invalid.',
            'product_id.required' => 'The product field is required.',
            'customer_name.required' => 'The customer name field is required.',
            'customer_email.required' => 'The customer email field is required.',
            'customer_email.email' => 'The customer email must be a valid email address.',
            'customer_street_name.required' => 'The street name field is required.',
            'customer_house_number.required' => 'The house number field is required.',
            'customer_postal_code.required' => 'The postal code field is required.',
            'customer_city.required' => 'The city field is required.',
            'customer_phone_number.required' => 'The phone number field is required.',
        ];
    }
}