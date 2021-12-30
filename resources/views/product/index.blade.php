<?php 
    $routeName = Route::current()->getName();
    $route = route($routeName);
?>

@extends('layouts.layout')
@section('content')

    @include('shared.grid_view.grid_view', [
        'pageTitle' => 'Danh sách sản phẩm',
        'currentPage' => $currentPage,
        'pageSize' => $pageSize,
        'totalItems' => $totalItems,
        'paginationOffset' => $paginationOffset,
        'records' => $products,
        'router' => $router
    ])

@endsection

@section('pageScript')

    <script>

        $(function () {
            $('#grid').w2grid({
                name: 'grid',
                recid: 'id',
                multiSelect: true,
                records: <?php echo json_encode($products); ?>,
                show: { 
                    footer:true, selectColumn: true,
                    toolbar: true, toolbarDelete: true, toolbarEdit: true 
                },
                toolbar: { 
                    items: [
                        { type: 'break' },
                        { 
                            type: 'html',  id: 'categorySelect', 
                            html: `
                                <span class="ms-2">Hiển thị cho:</span>
                                <input id="categorySelect" type="list" class="me-2" placeholder="{{ $categoryName }}" >
                            `
                        },
                        { 
                            type: 'html',  id: 'orderby', 
                            html: `
                                <span class="ms-2">Sắp xếp theo:</span>
                                <input id="orderby" type="list" class="me-2" placeholder="{{ $orderbyOptions[$orderby] }}" >
                            ` 
                        },
                    ],
                },
                onReload: function() { window.location.href = '{{ $route }}'; },
                onAdd: function (event) {},
                onEdit: function (event) {
                    var selected = this.getSelection()[0];
                    window.location.href = `/product/edit/modify/${selected}`;
                },
                msgDelete: 'Bạn có muốn xóa {{ $tableName }} đã chọn?',
                onDelete: function (event) {
                    var that = this;
                    var selected = that.getSelection();
                    event.onComplete = async (e) => {
                        var action = '{{ route('product.destroy') }}';
                        await fetch(action + '?ids='+selected.join(','));
                    }
                },
                columns: [
                    //{ field: 'id', text: 'ID', size: '50px' },
                    { field: 'name', text: 'Sản phẩm', render: function(rec) {
                        return `

                        <div class="grid-col__product-name d-flex align-items-center"> 
                            <img src="${rec['thumb_path']}" class="product-thumb" width="40" height="40"/>
                            <div class="ms-10 fw-bold">
                                <span class="d-block">#${ rec['id'] }</span>
                                <span class="d-block mt-6">${ rec['name'] }</span>
                            <div>    
                        </div>
                        `;
                    }},
                    { field: 'category_names', text: 'Danh mục' },
                    { field: 'quantity', text: 'Số lượng', render: (rec) => `${ rec['quantity'] } (${rec['unit_name']})`, size: '120px' },
                    { field: 'cost', text: 'Chi phí', render: (rec) => `${rec['cost']} đ`, size: '120px' },
                    { field: 'price', text: 'Giá bán', render: (rec) => `${rec['price']} đ`, size: '120px' },
                ],
                onRender: function(e) {
                    e.onComplete = async () => {

                        /* 
                        Render Grid Toolbar 
                        */
                        // remove w2ui search
                        //
                        w2ui['grid'].toolbar.remove('w2ui-search');
                        w2ui['grid'].toolbar.remove('w2ui-break0');
                        // Change to Vietnamese text for button
                        setTimeout(() => {
                            $('#tb_grid_toolbar_item_w2ui-edit .w2ui-tb-text').text('Chỉnh sửa');
                            $('#tb_grid_toolbar_item_w2ui-delete .w2ui-tb-text').text('Xóa');
                        }, 1000);

                        // category select list
                        //
                        var categorySelect = $('input[type=list]#categorySelect').w2field('list', {
                            url: `<?php echo route('w2ui.category.get'); ?>`,
                            compare: function(item, search) {
                                return true;
                            },
                        });

                        $('input[type=list]#categorySelect').change(function(e) {
                            var val =  $(e.target).val();
                            var items = $(e.target).w2field().options.items;
                            var selected = items.find(i => i.text == val).id;
                            window.location = `{{ $route }}?categoryId=${selected}`;    // Blade
                        })

                        // order by 
                        //
                        var categorySelect = $('input[type=list]#orderby').w2field('list', {
                            items: <?php echo json_encode( $orderbyOptions); ?>
                        });

                        $('input[type=list]#orderby').change(function(e) {
                            var val =  $(e.target).val();
                            var selected = $(e.target).w2field().options.items
                                                .find(i => i.text == val).id;
                            
                            var search = window.location.search.split('&');
                            var queryIndex = window.location.search.indexOf('orderby=');
                            if (queryIndex == -1) {
                                search.push('orderby=' + selected);
                            }
                            else {
                                search = search.map(i => {
                                    return i.includes('orderby=')
                                            ? 'orderby=' + selected
                                            : i;
                                });
                            }
                            
                            window.location.search = search.join('&');
                        })
                    }
                }
            });
        });

    </script>
    
@endsection
