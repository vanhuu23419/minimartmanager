<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="./images/logo.png" type="image/png">
    <title>Minimart Manager</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- W2ui CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/w2ui@1.5.3/w2ui-1.5.min.css">
    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/ach.ultilities.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/w2ui-custom.css') }}">
</head>
<body>

   <header>

        <div class="container d-flex">

            <ul id="header-nav">
                    <li class="nav-item dropdown active">
                        <a href="{{ route('product.index') }}" class="nav-link" >
                            <i class="ri-store-line"></i> Kho hàng
                        </a>

                        <ul class="dropdown-menu">
                            <li class="nav-item"><a href="{{ route('product.index') }}" class="nav-link">Sản phẩm</a></li>
                            <li class="nav-item"><a href="" class="nav-link">Danh mục</a></li>
                            <li class="nav-item"><a href="" class="nav-link">Nhập hàng</a></li>
                        </ul>
                    </li>

                    <li class="nav-item"><a href="" class="nav-link">
                        <i class="ri-file-chart-line"></i> Báo cáo
                    </a></li>
                    <li class="nav-item"><a href="" class="nav-link">
                        <i class="ri-bill-line"></i> Hóa đơn
                    </a></li>
                    <li class="nav-item"><a href="" class="nav-link">
                        <i class="ri-user-line"></i> Tài khoản
                    </a></li>
            </ul>

            <div id="user" class="ms-auto">
                <div class="d-flex align-items-center">
                    <a href="">
                        <img id="user-thumb" src="{{ asset('images/user.jpg') }}">
                    </a>
                    <div class="d-flex flex-column ms-2">
                        <a href="" class="fs-14"> Admin </a>
                        <a href="" class="text-primary fs-14"> Logout </a>
                    </div>
                </div>
            </div>
        </div>

   </header>

   <main class="my-40">
       <div class="container">

            @yield('content')
        </div>
   </main>
    
    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap Script CND -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!-- W2ui JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/w2ui@1.5.3/w2ui-1.5.min.js"></script>

    <script type="text/javascript" src="{{ asset('/js/main.js') }}"></script>

    @yield('pageScript')
        
</body>
</html>