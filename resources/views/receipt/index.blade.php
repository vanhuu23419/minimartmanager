<?php 
    $routeName = Route::current()->getName();
    $route = route($routeName);
?>

@extends('layouts.layout')
@section('content')

    @include('shared.grid_view.grid_view', [
        'pageTitle' => $pageTitle,
        'currentPage' => $currentPage,
        'pageSize' => $pageSize,
        'totalItems' => $totalItems,
        'paginationOffset' => $paginationOffset,
        'records' => $receipts,
        'router' => $router,
        'search' => false,
        'create' => false,
    ])

@endsection

@section('pageScript')

    <script>

        $(function () {
            $('#grid').w2grid({
                name: 'grid',
                recid: 'id',
                multiSelect: true,
                records: <?php echo json_encode($receipts); ?>,
                show: { 
                    footer:true, selectColumn: true,
                    toolbar: true, toolbarDelete: true, toolbarEdit: true 
                },
                onReload: function() { window.location.href = '{{ $route }}'; },
                onEdit: function (event) {
                    var selected = this.getSelection()[0];
                    window.open(  `/sell/printReceipt/${selected}?print=no`  ) ;
                },
                msgDelete: 'Bạn có muốn xóa {{ $tableName }} đã chọn?',
                onDelete: function (event) {
                    var that = this;
                    var selected = that.getSelection();
                    event.onComplete = async (e) => {
                        await fetch('/receipt/destroy' + '?ids='+selected.join(',')+'&_token={{ csrf_token() }}', {method:"post"});
                    }
                },
                columns: [
                    { field: 'id', text: 'Mã hóa đơn', size: '100px' },
                    { field: 'time', text: 'Ngày tạo' },
                    { field: 'num_products', text: 'Số lượng' },
                    { field: 'total', text: 'Tổng' },
                    { field: 'received', text: 'Đã nhận' },
                    { field: 'change', text: 'Tiền thối' },
                ],
                toolbar: {
                    items: [
                        { type: 'break' },
                        { 
                            type: 'html',  id: 'fromDate', 
                            html: `
                            <div class="">
                                <span class="fs-12 me-6">Từ ngày:</span><input name="fromDate" type="us-date" value="{{ request()->get('fromDate') }}">
                            </div>
                            ` 
                        },
                    ]
                },
                onRender: function(e) {
                    e.onComplete = async () => {

                        /* 
                        Render Grid Toolbar 
                        */
                        // remove w2ui search
                        //
                        w2ui['grid'].toolbar.remove('w2ui-search');
                        w2ui['grid'].toolbar.remove('w2ui-break0');
                        $('input[name="fromDate"]').w2field('date');
                        $('input[name="fromDate"]').change((e) => {
                            window.location = "/receipt?fromDate=" + $(e.target).val();
                        });
                    }
                }
            });
        });

    </script>
    
@endsection
