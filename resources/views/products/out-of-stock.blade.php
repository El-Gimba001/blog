@extends('layouts.app')

@section('title', 'Out of Stock Products')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0">Out of Stock Products</h1>
            <p class="text-muted">Products with zero or negative quantity</p>
        </div>
        <div class="col-auto">
            <div class="btn-group">
                <a href="{{ route('products.low-stock') }}" class="btn btn-outline-warning">
                    <i data-lucide="alert-triangle" class="icon-16 me-2"></i>Low Stock
                </a>
                <a href="{{ route('products.critical-stock') }}" class="btn btn-outline-danger">
                    <i data-lucide="alert-circle" class="icon-16 me-2"></i>Critical
                </a>
                <a href="{{ route('products.out-of-stock') }}" class="btn btn-outline-dark active">
                    <i data-lucide="x-circle" class="icon-16 me-2"></i>Out of Stock
                </a>
            </div>
        </div>
    </div>

    @if($outOfStockProducts->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i data-lucide="check-circle" class="icon-48 text-success mb-3"></i>
                <h4>No out of stock products!</h4>
                <p class="text-muted">All products are in stock.</p>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Current Stock</th>
                                <th>Reorder Point</th>
                                <th>Days Out</th>
                                <th>Store</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($outOfStockProducts as $product)
                            <tr class="table-dark-subtle">
                                <td>
                                    <strong>{{ $product->name }}</strong><br>
                                    <small class="text-muted">{{ $product->reference }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-dark">
                                        {{ $product->quantity }} {{ $product->unit }}
                                    </span>
                                </td>
                                <td>{{ $product->reorder_point }}</td>
                                <td>
                                    @if($product->out_of_stock_since)
                                        <span class="text-muted small">
                                            {{ $product->out_of_stock_since->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="text-muted small">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->emporium)
                                        {{ $product->emporium->name }}
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('products.edit', $product) }}" 
                                           class="btn btn-outline-primary" title="Edit">
                                            <i data-lucide="edit" class="icon-16"></i>
                                        </a>
                                        <button class="btn btn-outline-dark send-alert-btn" 
                                                data-product-id="{{ $product->id }}"
                                                title="Send Alert">
                                            <i data-lucide="bell" class="icon-16"></i>
                                        </button>
                                        <a href="{{ route('products.restock') }}?product_id={{ $product->id }}" 
                                           class="btn btn-outline-success"
                                           title="Restock">
                                            <i data-lucide="package" class="icon-16"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $outOfStockProducts->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
lucide.createIcons();

$(document).ready(function() {
    $('.send-alert-btn').on('click', function() {
        const productId = $(this).data('product-id');
        const button = $(this);
        
        button.html('<i data-lucide="loader-2" class="icon-16 animate-spin"></i>');
        lucide.createIcons();
        
        $.ajax({
            url: `/products/${productId}/send-alert`,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                button.html('<i data-lucide="bell" class="icon-16"></i>');
                lucide.createIcons();
                showToast('success', response.message);
            },
            error: function(xhr) {
                button.html('<i data-lucide="bell" class="icon-16"></i>');
                lucide.createIcons();
                showToast('error', xhr.responseJSON?.message || 'Error sending alert');
            }
        });
    });
});
</script>
@endpush