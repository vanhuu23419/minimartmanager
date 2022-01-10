<div productId="{{ $product->id }}" class="item d-flex align-items-center">
    <span class="product-name fs-14 me-20">
        <b class="quantity">{{ $quantity }} x </b> 
        {{ $product->name }} 
    </span>
    <button onclick="SELL.removeFromReceipt({{ $product->id }})" 
            class="remove text-danger m-0 p-0 ms-auto"> 
        <i class="ri-close-circle-line fs-24"></i>
    </button>
</div>