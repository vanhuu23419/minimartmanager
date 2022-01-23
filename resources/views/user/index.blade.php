<?php 
    $routeName = Route::current()->getName();
    $route = route($routeName);
?>

@extends('layouts.layout')
@section('content')

    <div class="container mt-40">

    @include('shared.grid_view.grid_view', [
        'pageTitle' => $pageTitle,
        'currentPage' => $currentPage,
        'pageSize' => $pageSize,
        'totalItems' => $totalItems,
        'paginationOffset' => $paginationOffset,
        'records' => $users,
        'router' => $router
    ])

    </div>


@endsection

@section('pageScript')

    <script>

        $(function () {
            $('#grid').w2grid({
                name: 'grid',
                recid: 'id',
                multiSelect: true,
                records: <?php echo json_encode($users); ?>,
                show: { 
                    footer:true, selectColumn: true,
                    toolbar: true, toolbarDelete: true, toolbarEdit: true 
                },
                onReload: function() { window.location.href = '{{ $route }}'; },
                onEdit: function (event) {
                    var selected = this.getSelection()[0];
                    window.location.href = `/user/edit/modify/${selected}`;
                },
                msgDelete: 'Bạn có muốn xóa {{ $tableName }} đã chọn?',
                onDelete: function (event) {
                    var that = this;
                    var selected = that.getSelection();
                    event.onComplete = async (e) => {
                        var action = '{{ route('user.destroy') }}';
                        await fetch(action + '?ids='+selected.join(','));
                    }
                },
                columns: [
                    { field: 'id', text: 'ID', size: '50px' },
                    { field: 'name', text: 'Tên danh mục' },
                    { field: 'email', text: 'Email' },
                    { field: 'role_name', text: 'Vai trò' },
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
                    }
                }
            });
        });

    </script>
    
@endsection
