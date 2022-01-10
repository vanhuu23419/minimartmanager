@foreach ($products as $product)
    <div class="product_item" productId="{{ $product->id }}" unitPrice="{{ $product->price }}" onclick="SELL.selectProduct(this)">
        <img src="{{ asset('storage/'.$product->thumb_path) }}" class="thumbnail">
        <span class="product_name mt-6">{{ $product->name }}</span>
    </div>
@endforeach