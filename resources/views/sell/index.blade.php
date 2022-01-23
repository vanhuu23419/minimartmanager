@extends('layouts.layout')

@section('pageStyles')
    <link rel="stylesheet" href="{{ asset('/css/sell.css') }}">
@endsection

@section('content')

<div id="container" class="d-flex h-100">
    <div id="categories" class="d-flex flex-column py-40">
        <?php $countCat = 0; ?>
        @foreach ($categories as $cat)
            <a categoryId="{{ $cat->id }}" @class(['category_item', 'active' => $countCat++ == 0])
                onclick="SELL.changeCategory({{ $cat->id }})">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <div id="products" class="flex-1 d-flex flex-column">
        <div class="products flex-1">

            <div class="d-flex align-items-center p-20">

                <h3 class="uppercase fs-24">{{ $categories->first()->name }}</h3>

                <div id="product_search" class="ms-auto">
                    <input type="text" placeholder="Nhập tên sản phẩm">
                    <button class="search-button">
                        <i class="ri-search-line"></i>
                    </button>
                </div>

                
            </div>

            <div class="products__container position-relative d-flex flex-wrap px-20 row-gap-16 col-gap-16">

                @include('sell.products', ['products' => $products])

            </div>

        </div>
        
        <div class="product_selection d-none">
            <div class="d-flex">
                <div class="selected-product flex-1 p-16 d-flex align-items-center">
                    <img src="" alt="" class="thumbnail">
                    <span class="product-name fs-18 fw-bolder ms-10">           
                        Product name 
                    </span>
                </div>
    
                <div class="add-to-receipt d-flex p-16">
                    <div class="input d-flex flex-column">
                        <span class="product-price fw-500 mb-1"> Đơn giá: 0đ </span>
                        <input type="number" value="1" name="quantity">
                    </div>
                    <button onclick="SELL.addToReceipt()" class="add-to-receipt-btn bg-green flex-1 ms-16 text-white rounded-3 fw-500"> 
                        <span class="text"> Thêm </span>
                        <span class="loading d-none lds-dual-ring-light" style="transform:scale(1.5);"> </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="receipt" class="d-flex flex-column">
        <div class="receipt_items p-16">
        </div>
        
        <div class="receipt_summary d-flex align-items-center px-16">
            <span class="fs-16"><b>Tổng: </b></span>
            <span class="fs-16 ms-6 total">0 đ</span>
        </div>
        
        <div class="receipt_controls p-16">

            <div class="d-flex">
                <div class="flex-1">
                    <span class="fw-500 mb-1">Khách đưa:</span>
                    <input id="received" type="number" class="w-100" value="">
                </div>
                <div class="flex-1 ms-10">
                    <span class="fw-500 mb-1">Thối khách:</span>
                    <input id="change" type="text" class="w-100" value="" disabled>
                </div>
            </div>

            <div id="amounts" class="d-flex my-16 bg-secondary" style="width:263px">
                <div class="d-flex flex-1 flex-column" style="margin-right: 1px;">
                    <button onclick="SELL.addToReceived(1000)" class="bg-white default-btn py-8 px-16 flex-1" style="margin-bottom: 1px;">1000</button>
                    <button onclick="SELL.addToReceived(2000)"  class="default-btn bg-white py-8 px-16 flex-1" style="margin-bottom: 1px;">2000</button>
                    <button onclick="SELL.addToReceived(5000)"  class="default-btn bg-white py-8 px-16 flex-1">5000</button>
                </div>
                <div class="d-flex flex-1 flex-column" style="margin-right: 1px;">
                    <button onclick="SELL.addToReceived(10000)"  class="default-btn bg-white py-8 px-16 flex-1" style="margin-bottom: 1px;">10.000</button>
                    <button onclick="SELL.addToReceived(20000)"  class="default-btn bg-white py-8 px-16 flex-1" style="margin-bottom: 1px;">20.000</button>
                    <button onclick="SELL.addToReceived(50000)"  class="default-btn bg-white py-8 px-16 flex-1">50.000</button>
                </div>
                <div class="d-flex flex-1 flex-column">
                    <button onclick="SELL.addToReceived(100000)"  class="default-btn bg-white py-8 px-16 flex-1" style="margin-bottom: 1px;">100.000</button>
                    <button onclick="SELL.addToReceived(200000)"  class="default-btn bg-white py-8 px-16 flex-1" style="margin-bottom: 1px;">200.000</button>
                    <button onclick="SELL.addToReceived(500000)"  class="default-btn bg-white py-8 px-16 flex-1">500.000</button>
                </div>
            </div>

            <div class="mt-auto d-flex" style="height: 50px;">
                <button onclick="SELL.printReceipt()" class="flex-1 m-0 bg-blue text-white me-1"> 
                    <i class="ri-printer-line fs-18 me-2"></i>
                    Xuất hóa đơn 
                </button>
                <button onclick="SELL.cancelOrder()" class="flex-1 m-0 bg-red text-white"> 
                    <i class="ri-save-line fs-18 me-2"></i>
                    Hủy bỏ 
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('pageScript')
    
    <script>
        $(function() {
            // Main content height
            var mainContentHeight = $('body').outerHeight() - $('header').outerHeight();
            var mainContentMarginTop = 3;
            $('main').css("height", mainContentHeight - mainContentMarginTop);

            // Receipt Items Section Height
            var receiptItemHeight = $('body').outerHeight() - ($('#receipt .receipt_summary').outerHeight() + $('#receipt .receipt_controls').outerHeight());
            $('#receipt .receipt_items').css("height", receiptItemHeight);
        });
    </script>

    <script>
        
        const SELL = {
            receiptItems: [],
            hasProducts: true,
            search: '',
            categoryId: {{ $categories->first()->id }},   // Selected Category
            numProducts: {{ $products->count() }},        // Number of Products Showed for Category ( Infinity Load )
            productsScrollAnchor: 0,
            setProductsScrollAnchor: function() {
                var productsEl =  document.querySelector('#products .products');
                // The scroll value which indicates when to trigger "Products Infinity Load" event
                this.productsScrollAnchor = productsEl.scrollHeight - $(productsEl).outerHeight();
            },
            showLoading: function() {
                var loading = `
                    <div id="loading" class="d-flex align-items-center justify-content-center w-100 my-3"> 
                        <span class="lds-dual-ring" style="transform:scale(1.5);"> </span>
                    </div>
                `;
                $('#products .products .products__container').append(loading);
            },
            hideLoading: function() {
                $('#products #loading').remove();
            },
            loadProducts: async function() {
                var that = this;
                var body = {
                    _token: '{{ csrf_token() }}',
                    categoryId: this.categoryId,
                    numProducts: this.numProducts,
                    search: this.search
                };
                //
                that.showLoading()
                $.post('/sell/products', body, function(html) {
                    // Update View
                    that.hideLoading();
                    $('#products .products__container').append(html);
                    // Update data
                    that.numProducts = $('#products .products__container .product_item').length;
                    that.setProductsScrollAnchor();
                    if (!html) {
                        that.hasProducts = false;
                    }
                });
            },
            changeCategory: async function( catId ) {
                var that = this;
                that.categoryId = catId;
                that.search = null;
                that.numProducts = 0;
                //
                $('#product_search input').val('');
                that.search = '';
                $('#products .products__container').html('');
                //
                that.loadProducts();
                //
                $('#categories .category_item.active').removeClass('active');
                $(`#categories .category_item[categoryId="${catId}"]`).addClass('active');
            },
            onSearch: function() {
                var that = this;
                if (!that.search) {
                    return;
                }
                //
                that.numProducts = 0;
                //
                $('#products .products__container').html('');
                that.loadProducts();
            },
            selectProduct: function( el ) {
                var productId = $(el).attr('productId'),
                    productName = $(el).children('.product_name').text(),
                    unitPrice = +$(el).attr('unitPrice'),
                    thumbNail = $(el).children('.thumbnail').attr('src');
                //
                $('.products .product_item.active').removeClass('active');
                $(el).addClass('active');
                $('.product_selection').removeClass('d-none');
                $('.product_selection input[name="quantity"]').val(1);
                //
                $('.product_selection .thumbnail').attr('src',thumbNail);
                $('.product_selection .product-name').text(productName);
                $('.product_selection .product-price').text('Đơn giá: ' + toCurrency(unitPrice) + ' đ');
                //
                this.selectedProductId = productId;    

            },    
            addToReceipt: function() {
                var that = this,
                    productId = that.selectedProductId,
                    quantity = $('.product_selection input[name="quantity"]').val(),
                    unitPrice = $(`.product_item[productId="${productId}"]`).attr('unitPrice');

                // Add loading animation
                $('.add-to-receipt .text').addClass('d-none');
                $('.add-to-receipt .loading').removeClass('d-none');

                // Server-side Rendering the Receipt Item
                var action = '{{ route('sell.addToReceipt') }}',
                    data = { productId, quantity, _token: '{{ csrf_token() }}' };
                $.post(action, data, function(html) {
                    // Add Receipt
                    var existed = that.receiptItems.find(p => p.id == productId);
                    if (existed) {
                        existed.quantity += +quantity;
                        // update view
                        $(`.receipt_items .item[productId="${productId}"] .quantity`)
                            .text(existed.quantity + ' x ');
                    }
                    else {
                        that.receiptItems.push({ 
                            id: productId, quantity: +quantity, unitPrice: +unitPrice 
                        });
                        // update view
                        $('.receipt_items').append(html);
                    }
                    // Update Recepit Summary
                    var total = that.receiptItems
                        .reduce((total,item) => total + item.unitPrice * item.quantity, 0);
                    $('.receipt_summary .total').text(toCurrency(total) + ' đ');
                    that.updateChange();

                    // Remove loading animation
                    $('.add-to-receipt .loading').addClass('d-none');
                    $('.add-to-receipt .text').removeClass('d-none');

                    // Update Scroll Anchor
                    that.setProductsScrollAnchor();
                });
            },
            removeFromReceipt: function(id) {
                var that = this;
                // Remove from receipt
                that.receiptItems = that.receiptItems.filter(p => p.id != id);
                // Update Recepit Summary
                var total = that.receiptItems
                .reduce((total,item) => total + item.unitPrice * item.quantity, 0);
                $('.receipt_summary .total').text(toCurrency(total) + ' đ');
                that.updateChange();
                // Update view
                $(`.receipt_items .item[productId="${id}"]`).remove();
            },
            updateChange: function() {  
                var total = this.receiptItems.reduce((total,item) => total + item.unitPrice * item.quantity, 0),
                    received = $('#received').val();
                if (received - total > 0) {
                    $('#change').val(toCurrency(received - total) +' đ');
                }
                else {
                    $('#change').val(0 +' đ');
                }
            },
            addToReceived: function(amount) {
                var received = $('#received').val();
                $('#received').val( +received + amount );
                this.updateChange();
            },
            printReceipt: function() {
                if(this.receiptItems.length == 0) {
                    alert('Đơn hàng trống!!');
                    return;
                }

                /* Save Receipt */
                var data = {
                    'items': JSON.stringify(this.receiptItems),
                    'received': $('#received').val(),
                    '_token': '{{ csrf_token() }}'
                };

                $.post('{{ route('sell.saveReceipt') }}', data, (id) => {

                    /* Clear Receipt Items */
                    this.clearReceipt();
                    /*Print Receipt*/
                    window.open( '/receipt/printReceipt/' + id );
                });
            },
            cancelOrder: function() {

                if(this.receiptItems.length == 0) {
                    alert('Đơn hàng trống!!');
                    return;
                }
                var proceed = confirm('Đơn hàng này sẽ bị hủy?');
                if (proceed) 
                {
                    this.clearReceipt();
                }
            },
            clearReceipt: function() {
                /* Clear Receipt Items */
                this.receiptItems = [];

                /* Update View */
                $('.receipt_items').html('');
                $('.receipt_summary .total').text('0 đ');
                $('#received').val('0');
                $('#change').html('0 đ');
                $('.product_selection').addClass('d-none');
                this.setProductsScrollAnchor();
            },
            init: function() {
                var that = this;
                /*
                Products Infinity Load Events
                */
                that.setProductsScrollAnchor();
                //
                $('#products .products').scroll((e) => {
                    if (that.hasProducts)
                    if ($(e.target).scrollTop() == that.productsScrollAnchor) {
                        that.loadProducts();
                    }
                })
                //
                $('#product_search input').keydown(function(e){
                    if(e.keyCode == 13){
                        var val = $(e.target).val();
                        that.search = val;
                        that.onSearch();
                    }  
                });
                $('#product_search input').change((e) => that.search = $(e.target).val())
                $('#product_search .search-button').click(() => that.onSearch());
                
                /*
                Receipt COntrols
                */
                $('#received').keyup(() => SELL.updateChange()); 
                $('#received').change(() => SELL.updateChange()); 
            },
        }

        $(function(){

            SELL.init();
        });

    </script>

@endsection