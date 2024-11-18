<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;

class RetrieveProductsJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function handle()
    {
        $apiKey  = config('florinet.api_key');
        $baseUrl = config('florinet.base_url');
        $url     = $baseUrl . '/products';

        $currentPage = 1;
        $lastPage    = null;

        do {
            try {
                // Make the HTTP request
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                ])->get($url, [
                    'page' => $currentPage,
                ]);

                if ($response->successful()) {
                    $responseData = $response->json();

                    $currentPage = $responseData['meta']['current_page'];
                    $lastPage    = $responseData['meta']['last_page'];

                    $products = $responseData['data'];

                    // Use transaction per page
                    DB::transaction(function () use ($products) {
                        foreach ($products as $productData) {
                            Product::updateOrCreate(
                                ['id' => $productData['id']],
                                [
                                    'title'         => $productData['title'],
                                    'description'   => $productData['description'],
                                    'price'         => $productData['price'],
                                    'selling_price' => $productData['selling_price'],
                                    'currency'      => $productData['currency'],
                                    'image_url'     => $productData['image_url'],
                                    'article_code'  => $productData['article_code'],
                                    'stock'         => $productData['stock'],
                                    'properties'    => $productData['properties'],
                                    'published_at'  => $productData['published_at'],
                                ]
                            );
                        }
                    });

                    $currentPage++;

                } elseif ($response->status() == 429) {
                    // Rate limit exceeded, wait and retry the same request
                    $retryAfter = $response->header('Retry-After', 10); // default to 10 seconds if header not present
                    Log::warning('Rate limit exceeded. Waiting for ' . $retryAfter . ' seconds before retrying.');
                    sleep($retryAfter);
                    // Do not increment currentPage, retry the same page

                } else {
                    Log::error('Failed to retrieve products.', [
                        'status_code' => $response->status(),
                        'response_body' => $response->body(),
                        'page' => $currentPage,
                    ]);
                    break;
                }
            } catch (RequestException $e) {
                Log::error('HTTP Request Exception occurred.', [
                    'message' => $e->getMessage(),
                    'page' => $currentPage,
                ]);
                break;
            } catch (\Exception $e) {
                Log::error('An unexpected error occurred.', [
                    'message' => $e->getMessage(),
                    'page' => $currentPage,
                ]);
                break;
            }
        } while ($lastPage === null || $currentPage <= $lastPage);
    }
}
