<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <title>Minimart Manager</title>

    <!-- Bootstrap CSS CDN -->
    <link href="{{ asset('libs/bootstrap/bootstrap.min.css') }}" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/css/sell.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/ach.ultilities.css') }}">
</head>
<body class="d-flex flex-column">

   <header>

        <div class=" d-flex">

            <ul id="header-nav" class="ms-auto">
                <li class="nav-item"><a href="" class="nav-link">
                    <i class="ri-user-line"></i> Bán hàng
                </a></li>
                <li class="nav-item"><a href="" class="nav-link">
                    <i class="ri-bill-line"></i> Hóa đơn
                </a></li>
            </ul>

            <div id="user" class="ms-auto">
                <div class="d-flex align-items-center">
                    <a href="">
                        <img id="user-thumb" src="{{ asset('images/user.jpg') }}">
                    </a>
                    <div class="d-flex flex-column ms-2">
                        <a href="" class="fs-14"> Cashier </a>
                        <a href="" class="text-primary fs-14"> Logout </a>
                    </div>
                </div>
            </div>
        </div>

   </header>

   <main class="flex-1">

        @yield('content')
   </main>
    
    <!-- Jquery -->
    <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap Script CND -->
    <script src="{{ asset('libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
   
    <script type="text/javascript" src="{{ asset('/js/main.js') }}"></script>

    @yield('pageScript')
        
</body>
</html>