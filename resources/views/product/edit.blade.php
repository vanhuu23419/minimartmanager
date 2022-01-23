<?php 
    $routeName = Route::current()->getName();
?>

@extends('layouts.layout')
@section('content')

<div class="container mt-40">

    <div id="page-title" class="d-flex align-items-center">
        <h1 class="m-0"> {{ $pageTitle }}</h1>
    </div>

    <form class="d-flex mt-40">

        <div class="d-flex flex-column me-5 mt-2">
            <img id="thumbnail" src="<?php echo asset('storage/'.$product->thumb_path); ?>" width="150" height="150">
            <input id="thumbnail_input" type="file" name="thumbnail" accept="image/x-png,image/gif,image/jpeg" hidden>
            <button class="btn btn-sm btn-outline-primary mt-2" type="button" onclick="$('#thumbnail_input').click()"> 
                Chọn hình
            </button>
        </div>

        <div class="d-flex flex-column"  style="width:400px">
            <div class="mb-10">
                <label class="d-block fw-bold mb-6 fs">Tên sản phẩm (<span class="text-danger">*</span>):</label>
                <input id="name" name="name" class="w-100" value="{{ $product->name }}">
                <span class="d-block validation form-text text-danger d-none" for="name"></span>
            </div>
        
            <div class="d-flex mb-10">
                <div class="me-3">
                    <label class="d-block fw-bold fs-14 mb-6">Số lượng:</label>
                    <input id="quantity" name="quantity" class="w-100" value="{{ $product->quantity }}">
                    <span class="d-block validation form-text text-danger d-none" for="quantity"></span>
                </div>
                
                <div class="">
                    <label class="d-block fw-bold fs-14 mb-6">Đơn vị:</label>
                    <input id="unit_val" type="text" name="unit_id" hidden>
                    <input id="unit_list" class="w-100 bg-white">
                    <span class="d-block validation form-text text-danger d-none" for="unit_id"></span>
                </div>
            </div>
            
            <div class="mb-10">
                <label class="d-block fw-bold fs-14 mb-6">Danh mục (<span class="text-danger">*</span>):</label>
                <input id="categories_val" type="text" name="category_ids" hidden>
                <input id="categories_list" class="w-100" placeholder="Gõ để tìm kiếm">
                <span class="d-block validation form-text text-danger d-none" for="category_ids"></span>
            </div>
        
            <div class="d-flex mb-10">
                <div class="me-3">
                    <label class="d-block fw-bold fs-14 mb-6">Chi phí:</label>
                    <input id="cost" name="cost" class="w-100" value="{{ $product->cost }}">
                    <span class="d-block validation form-text text-danger d-none" for="cost"></span>
                </div>
                
                <div>
                    <label class="d-block fw-bold fs-14 mb-6">Giá bán:</label>
                    <input id="price" name="price" class="w-100" value="{{ $product->price }}">
                    <span class="d-block validation form-text text-danger d-none" for="price"></span>
                </div>
            </div>
        
            <div class="mb-10">
                <label class="d-block fw-bold fs-14 mb-6">Mô tả sản phẩm:</label>
                <textarea name="description" id="description" rows="3" class="w-100">{{ $product->description }}</textarea>
                <span class="d-block validation form-text text-danger d-none" for="description"></span>
            </div>
        
            <div class="mt-10">
                <button type="button" class="btn btn-success bt-green me-6" onclick="save()">{{ $flag == 'modify' ? 'Cập nhật' : 'Tạo mới' }}</button>
                <a href="/product/index" class="btn btn-warning"> Hủy bỏ</a>
            </div>
        </div>
    </form>

</div>


@endsection

@section('pageScript')

    <script>

        
        function showValidations( errors ) {
            var labels = {
                'name': 'Tên sản phẩm',
                'quantity': 'Số lượng',
                'cost': 'Chi phí',
                'price': 'Giá bán',
                'unit id': 'Đơn vị',
                'category ids': 'Danh mục',
                'description': 'Mô tả',
            };

            $('.validation').addClass('d-none');
            for (const key in errors) {
                var labelKey = key.replace('_', ' ')
                var label = labels[labelKey];
                var err = errors[key][0].replace(labelKey, label);
                $(`.validation[for="${key}"]`).removeClass('d-none').text(err);
            }
        }

        function updateProductUnitInput() {
            var val =  $('#unit_list').val();
            var items = $('#unit_list').w2field().options.items;
            var selected = items.find(i => i.text == val).id;
            $('#unit_val').val(selected);
        }

        function updateProductCategoriesInput() {
            var selected = $('#categories_list').w2field().options.selected;
            $('#categories_val').val(selected.map(e => e.id).join(','));
        }

        async function save() {

            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('name', $('#name').val());
            formData.append('quantity', $('#quantity').val().replace(',', '')); // removed w2ui ',' on input value
            formData.append('cost', $('#price').val().replace(',', '')); // removed w2ui ',' on input value
            formData.append('price', $('#cost').val().replace(',', '')); // removed w2ui ',' on input value
            formData.append('unit_id', $('#unit_val').val());
            formData.append('category_ids', $('#categories_val').val());
            formData.append('description', $('#description').val());
            formData.append('thumbnail', $('#thumbnail_input')[0].files[0]??null);

            var action = '/product/store/{{ $flag }}' + '{{ ($flag == 'modify') ? "/{$product->id}" : '' }}';
            var data = await fetch(action, {
                method: 'POST',
                body: formData
            });
            var result = await data.json();
            if (result.status == 'failed') {
                w2popup.open({
                    title: 'Thông báo',
                    body: '<div class="w2ui-centered text-danger">{{ $flag == 'modify' ? 'Cập nhật' : 'Tạo mới' }} Sản phẩm thất bại!!</div>',
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
                        window.location.href = '/product/edit/modify/' + result.productId
                    }, 2000);
                }
            }
        }


        $(function() {

            $('#name').w2field('text');
            $('#quantity').w2field('int');
            $('#price').w2field('float');
            $('#cost').w2field('float');
            $('#description').w2field('textarea');

            // thumbnail
            $('#thumbnail_input').change(function(e) {
                var input = e.target;
                    if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#thumbnail').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });

            // product unit list
            var productUnits = <?php echo json_encode($w2uiProductUnits); ?>;
            var productUnitSelected = productUnits.find(e => e.id == '{{ $product->unit_id }}');
            $('#unit_list').w2field('list', { items: productUnits, max: 1, selected: productUnitSelected });
            $('#unit_list').change(() => updateProductUnitInput());
            updateProductUnitInput();

            // category multiselect
            var productCategories = <?php echo json_encode($w2uiCategoriesList); ?>;
            var categorySelect = $('#categories_list').w2field('enum', {
                url: `<?php echo route('w2ui.category.get'); ?>`,
                selected: productCategories,
                compare: function(item, search) {
                    return true;
                },
            });
            $('#categories_list').change(() => updateProductCategoriesInput());
            updateProductCategoriesInput();

        });
    </script>
    
@endsection