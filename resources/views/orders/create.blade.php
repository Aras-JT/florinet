@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card border-0 shadow-sm">
        <div class="row g-0">
            <!-- Product Image -->
            <div class="col-lg-6 d-flex align-items-center" style="background-color: #f9f9f9;">
                <img src="{{ $product->image_url }}" alt="{{ $product->title }}" class="img-fluid rounded-start w-100" style="object-fit: cover; height: 100%;">
            </div>

            <!-- Product Details and Order Form -->
            <div class="col-lg-6">
                <div class="card-body p-5">
                    <h1 class="card-title display-5 mb-4">{{ $product->title }}</h1>
                    <p class="card-text lead mb-4">{{ $product->description }}</p>
                    <h3 class="text-success mb-3">€{{ number_format($product->price, 2) }}</h3>
                    <p class="text-muted mb-5">Stock: {{ $product->stock }}</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('orders.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <!-- Customer Information -->
                        <div class="mb-4">
                            <h4 class="mb-3">Customer Information</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customer_name" class="form-label">Name*</label>
                                    <input type="text" name="customer_name" class="form-control" required value="{{ old('customer_name') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_email" class="form-label">Email*</label>
                                    <input type="email" name="customer_email" class="form-control" required value="{{ old('customer_email') }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="customer_phone_number" class="form-label">Phone Number*</label>
                                    <input type="text" name="customer_phone_number" class="form-control" required value="{{ old('customer_phone_number') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="mb-4">
                            <h4 class="mb-3">Shipping Address</h4>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="customer_street_name" class="form-label">Street Name*</label>
                                    <input type="text" name="customer_street_name" class="form-control" required value="{{ old('customer_street_name') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="customer_house_number" class="form-label">House Number*</label>
                                    <input type="text" name="customer_house_number" class="form-control" required value="{{ old('customer_house_number') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_postal_code" class="form-label">Postal Code*</label>
                                    <input type="text" name="customer_postal_code" class="form-control" required value="{{ old('customer_postal_code') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_city" class="form-label">City*</label>
                                    <input type="text" name="customer_city" class="form-control" required value="{{ old('customer_city') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="mb-4">
                            <h4 class="mb-3">Order Details</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label">Quantity*</label>
                                    <input type="number" name="amount" class="form-control" min="1" value="{{ old('amount', 1) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="note" class="form-label">Note (Optional)</label>
                                    <textarea name="note" class="form-control" rows="2">{{ old('note') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    body {
        background-color: #f0f2f5;
    }
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    .card-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
    }
    .card-text {
        font-family: 'Open Sans', sans-serif;
        font-size: 1.1rem;
    }
    .form-label {
        font-weight: 600;
    }
    .btn-primary {
        background-color: #ff7f50;
        border-color: #ff7f50;
    }
    .btn-primary:hover {
        background-color: #ff6a39;
        border-color: #ff6a39;
    }
    .alert-danger {
        font-size: 0.9rem;
    }
</style>

@endsection
