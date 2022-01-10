<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Receipt {{ $receipt->id }} </title>
    <link href='{{ asset('libs/bootstrap/bootstrap.min.css') }}' rel='stylesheet'>
    <style> 
        .body-main {
            background: #ffffff;
            border-bottom: 15px solid #1E1F23;
            border-top: 15px solid #1E1F23;
            margin-top: 30px;
            margin-bottom: 30px;
            padding: 40px 30px !important;
            position: relative;
            box-shadow: 0 1px 21px #808080;
            font-size: 10px
        }

        .main thead {
            background: #1E1F23;
            color: #fff
        }

        .img {
            height: 100px
        }

        h1 {
            text-align: center
        }
    </style>
</head>
<body class='snippet-body'>
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 body-main">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4"> <img class="img" alt="Invoce Template" src="{{ asset('images/shopping_cart_PNG59.png') }}" /> </div>
                            <div class="col-md-8 text-right">
                                <h4 style="color: #F81D2D;"><strong>INVOICE{{ $receipt->id }}</strong></h4>
                            </div>
                        </div> <br />
                        <div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            <h5>Description</h5>
                                        </th>
                                        <th>
                                            <h5>Amount</h5>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($receiptItems as $item)
                                        <tr>
                                            <td class="col-md-9">{{ $item->quantity }} x {{ $item->product_name }}</td>
                                            <td class="col-md-3"><i class="fas fa-rupee-sign" area-hidden="true"></i> {{ $item->product_price }} đ </td>
                                        </tr>
                                    @endforeach
                                    
                                    <tr>
                                        <td class="text-right">
                                            <p> <strong>Total Amount: </strong> </p>
                                            <p> <strong>Received Amount: </strong> </p>
                                            <p> <strong>Change: </strong> </p>
                                        </td>
                                        <td>
                                            <p> <strong><i class="fas fa-rupee-sign" area-hidden="true"></i> {{ $receipt->total }} đ</strong> </p>
                                            <p> <strong><i class="fas fa-rupee-sign" area-hidden="true"></i> {{ $receipt->received }} đ </strong> </p>
                                            <p> <strong><i class="fas fa-rupee-sign" area-hidden="true"></i> {{ $receipt->change }} đ</strong> </p>
                                        </td>
                                    </tr>
                                    <tr style="color: #F81D2D;">
                                        <td class="text-right">
                                            <h4><strong>Total:</strong></h4>
                                        </td>
                                        <td class="text-left">
                                            <h4><strong><i class="fas fa-rupee-sign" area-hidden="true"></i> {{ $receipt->total }} đ </strong></h4>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <div class="col-md-12">
                                <p><b>Date :</b> {{ $receipt->created_at }} </p> <br />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!request()->get('print') || request()->get('print') == 'yes')
        <script type='text/Javascript'>
            print();
        </script>
    @endif
</body>
</html>