<?php 

    $req = app('request');

    $routeName = Route::current()->getName();
    $route = route($routeName);

    $show = ($currentPage - 1) * $pageSize + count($records);
    $totalPages = ceil($totalItems / $pageSize);
    $currentOffset = ceil($currentPage/ $paginationOffset) - 1;
    $start = $currentOffset * $paginationOffset + 1;
    $end = $currentOffset * $paginationOffset + $paginationOffset;

?>

<div class="d-flex align-items-center">
    <div id="page-title" class="d-flex align-items-center">
        <h1 class="m-0"> {{ $pageTitle }}</h1>
        <a href="/{{ $router }}/edit/create"class="px-14 py-6 bg-green text-white rounded-pill ms-20 text-decoration-none"> 
            <span class="d-inline-flex align-items-center fs-14">
                <i class="ri-add-box-line me-8 d-inline-flex align-items-center fs-16"></i>
                Tạo mới 
            </span>
        </a>
    </div>

    <div id="page-search" class="ms-auto">
        <form action="{{ $route }}">
            @csrf
            <input type="text" name="search" placeholder="Nhập tên sản phẩm..." value="{{ app('request')->get('search') }}">
            <button type="submit"><i class="ri-search-line"></i></button>
        </form>
    </div>
</div>

<div id="grid" class="mt-30" style="width: 100%; height: 500px;"></div>

<nav id="grid-pagination" class="mt-10 d-flex">
    <ul class="pagination fs-12">
        @if ($start - 1 > 0)
        <li class="page-item">
            <a class="page-link text-black" 
                href="{{ route( $routeName, collect($req->query())->replace(['paged' => $start-1])->toArray()) }}">
                Trước
            </a>
        </li>
        @endif
        
        @for ($i = $start; $i <= $end && $i <= $totalPages; ++$i)
        <li class="page-item">
            <a href="{{ route( $routeName, collect($req->query())->replace(['paged' => $i])->toArray()) }}" 
                @class(['page-link', 'text-black' => $i != $currentPage])> 
                {{ $i }}
            </a>
        </li>
        @endfor

        @if ($end + 1 <= $totalPages)
        <li class="page-item">
            <a class="page-link text-black" 
                href="{{ route( $routeName, collect($req->query())->replace(['paged' => $end+1])->toArray()) }}">
                Sau
            </a>
        </li>
        @endif
    </ul>

    <div class="ms-auto grid__summary text-secondary"> Hiểm thị: {{ $show }} / {{ $totalItems }} </div>
</nav>  