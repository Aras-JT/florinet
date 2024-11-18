@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            <!-- this script is only for demo purpose for the initial products fetch -->
            <script>
                (function() {
                    const totalDuration = 15000; // 15 seconds
                    const refreshInterval = 3000; // 3 seconds
                    const startTimeKey = 'productsFetchedStartTime';

                    let startTime = localStorage.getItem(startTimeKey);

                    if (!startTime) {
                        startTime = Date.now();
                        localStorage.setItem(startTimeKey, startTime);
                    }

                    const elapsedTime = Date.now() - startTime;

                    if (elapsedTime < totalDuration) {
                        setTimeout(function() {
                            location.reload();
                        }, refreshInterval);
                    } else {
                        localStorage.removeItem(startTimeKey);
                    }
                })();
            </script>
        @endif

        @if ($noProducts)
            <div class="text-center my-5">
                <h3>No products found</h3>
                <a href="{{ route('products.fetch') }}" class="btn btn-primary mt-3">Fetch Products (only for demo purpose)</a>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-4 g-4" id="product-list">
                @foreach ($products as $product)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->title }}" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title text-truncate">{{ $product->title }}</h5>
                                <p class="card-text text-muted small">{{ $product->description }}</p>
                                <div class="mt-3">
                                    <p class="fw-bold mb-1">€ {{ number_format($product->price, 2) }}</p>
                                    <p class="text-muted small">In stock: {{ $product->stock }}</p>
                                </div>
                                <a href="{{ route('orders.create', ['product_id' => $product->id]) }}" class="btn btn-outline-primary w-100">Order Now</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4" id="loading" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        @endif
    </div>

    <script>
        let page = 1;
        const loadMoreProducts = () => {
            page++;
            fetch(`?page=${page}`)
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const htmlDocument = parser.parseFromString(data, 'text/html');
                    const newProducts = htmlDocument.querySelector('#product-list').innerHTML;
                    document.querySelector('#product-list').insertAdjacentHTML('beforeend', newProducts);
                    if (!htmlDocument.querySelector('#product-list').innerHTML.trim()) {
                        window.removeEventListener('scroll', handleScroll);
                        document.querySelector('#loading').style.display = 'none';
                    }
                });
        };

        const handleScroll = () => {
            const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
            if (scrollTop + clientHeight >= scrollHeight - 5) {
                document.querySelector('#loading').style.display = 'block';
                loadMoreProducts();
            }
        };

        window.addEventListener('scroll', handleScroll);
    </script>
@endsection