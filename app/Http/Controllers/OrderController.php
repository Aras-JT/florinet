<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $product_id = $request->input('product_id');
        $product = Product::findOrFail($product_id);

        return view('orders.create', compact('product'));
    }

    public function store(StoreOrderRequest $request)
    {
        $orderData = [
            'customer_name'           => $request['customer_name'],
            'customer_email'          => $request['customer_email'],
            'customer_street_name'    => $request['customer_street_name'],
            'customer_house_number'   => $request['customer_house_number'],
            'customer_postal_code'    => $request['customer_postal_code'],
            'customer_city'           => $request['customer_city'],
            'customer_phone_number'   => $request['customer_phone_number'],
            'note'                    => $request['note'] ?? '',
            'order_rows' => [
                [
                    'product_id' => $request['product_id'],
                    'amount'     => $request['amount'],
                ],
            ],
        ];

        $apiKey  = config('florinet.api_key');
        $baseUrl = config('florinet.base_url');
        $url     = $baseUrl . '/orders';
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ])->post($url, $orderData);

            if ($response->successful()) {
                return redirect()->route('products.index')->with('success', 'Order placed successfully!');
            } else {
                $errorMessage = $response->json('message') ?? 'Failed to place order.';
                return back()->withErrors(['error' => $errorMessage]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}
