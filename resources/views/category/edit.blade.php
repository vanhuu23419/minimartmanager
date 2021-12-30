<?php 
    $routeName = Route::current()->getName();
?>

@extends('layouts.layout')
@section('content')

<div id="page-title" class="d-flex align-items-center">
    <h1 class="m-0"> {{ $pageTitle }}</h1>
</div>

<form class="d-flex flex-column mt-40" style="width:400px">

    <div class="mb-10">
        <label class="d-block fw-bold mb-6 fs">Tên danh mục (<span class="text-danger">*</span>):</label>
        <input id="name" name="name" class="w-100" value="{{ $category->name }}">
        <span class="d-block validation form-text text-danger d-none" for="name"></span>
    </div>

    <div class="mb-10">
        <label class="d-block fw-bold fs-14 mb-6">Mô tả danh mục:</label>
        <textarea name="description" id="description" rows="3" class="w-100"></textarea>
        <span class="d-block validation form-text text-danger d-none" for="description"></span>
    </div>

    <div class="mt-10">
        <button type="button" class="btn btn-success bt-green me-6" onclick="save()">{{ $flag == 'modify' ? 'Cập nhật' : 'Tạo mới' }}</button>
        <a href="{{ route('category.index') }}" class="btn btn-warning"> Hủy bỏ</a>
    </div>
</form>

@endsection

@section('pageScript')

    <script>
        
        function showValidations( errors ) {
            var labels = {
                'name': 'Tên danh mục',
            };

            $('.validation').addClass('d-none');
            for (const key in errors) {
                var labelKey = key.replace('_', ' ')
                var label = labels[labelKey];
                var err = errors[key][0].replace(labelKey, label);
                $(`.validation[for="${key}"]`).removeClass('d-none').text(err);
            }
        }

        async function save() {

            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('name', $('#name').val());
            formData.append('description', $('#description').val());

            var action = '/category/store/{{ $flag }}' + '{{ ($flag == 'modify') ? "/{$category->id}" : '' }}';
            var data = await fetch(action, {
                method: 'POST',
                body: formData
            });
            var result = await data.json();
            if (result.status == 'failed') {
                w2popup.open({
                    title: 'Thông báo',
                    body: '<div class="w2ui-centered text-danger">{{ $pageTitle }} thất bại!!</div>',
                    width: 250,
                    height: 150
                });
                showValidations(result.errors);
            }
            else {
                w2popup.open({
                    title: 'Thông báo',
                    body: '<div class="w2ui-centered text-success">{{ $pageTitle }} thành công!!</div>',
                    width: 250,
                    height: 150
                });

                if ('{{ $flag }}' == 'create') {
                    setTimeout(() => {
                        window.location.href = '/category/edit/modify/' + result.categoryId
                    }, 2000);
                }
            }
        }

        $(function() {

            $('#name').w2field('text');
            $('#description').w2field('textarea');
        });
    </script>
    
@endsection