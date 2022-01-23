<?php 
    $routeName = Route::current()->getName();
    $route = route($routeName);
?>

@extends('layouts.layout')

@section('content')


<div class="container mt-40 mb-50">

    <div id="report">

        <section id="today-report">
            <div class="section-title mb-20">
                <h3 class="fs-22 uppercase lh-20 mb-10"> Hôm nay: </h3>
                <span class="d-block bg-blue" style="height:5px; width: 50px;"></span>
            </div>
            
            <div class="section-content d-flex col-gap-6 mb-50">

                <div class="flex-1 bg-white p-16 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="ri-money-dollar-circle-line fs-48 opacity-50 text-primary"></i>
                        <div class="ms-auto d-flex flex-column">
                            <span class="d-block fs-28 lh-40 ms-auto" style="font-weight:600;"> {{ number_format($dayReport['total_revenue']) }} đ</span>
                            <span class="fs-14 opacity-70 ms-auto uppercase" style="font-weight:600;">DOANH
                                THU</span>
                        </div>
                    </div>
                </div>
                <div class="flex-1 bg-white p-16 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="ri-wallet-line fs-48 opacity-50 txt-green"></i>
                        <div class="ms-auto d-flex flex-column">
                            <span class="d-block fs-28 lh-40 ms-auto" style="font-weight:600;"> {{ number_format($dayReport['total_profit']) }} đ</span>
                            <span class="fs-14 opacity-70 ms-auto uppercase" style="font-weight:600;">LỢI
                                NHUẬN</span>
                        </div>
                    </div>
                </div>
                <div class="flex-1 bg-white p-16 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="ri-file-list-3-line fs-48 opacity-50"></i>
                        <div class="ms-auto d-flex flex-column">
                            <span class="d-block fs-28 lh-40 ms-auto" style="font-weight:600;"> {{ $dayReport['num_receipts'] }}</span>
                            <span class="fs-14 opacity-70 ms-auto uppercase" style="font-weight:600;">HÓA ĐƠN</span>
                        </div>
                    </div>
                </div>
                <div class="flex-1 bg-white p-16 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="ri-shopping-bag-line fs-48 opacity-50"></i>
                        <div class="ms-auto d-flex flex-column">
                            <span class="d-block fs-28 lh-40 ms-auto" style="font-weight:600;">{{ $dayReport['num_products'] }}</span>
                            <span class="fs-14 opacity-70 ms-auto uppercase" style="font-weight:600;">SẢN
                                PHẨM</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="charts">
            <div class="section-title d-flex align-items-center mb-20">
                <div>
                    <h3 class="fs-22 uppercase lh-20 mb-10"> Tổng quan: </h3>
                    <span class="d-block bg-red" style="height:5px; width: 50px;"></span>
                </div>

                <?php 
                    $options = [
                        'weekReport' => 'Tuần này',
                        'monthReport' => 'Tháng này',
                        'yearReport' => 'Năm này',
                        'custom' => 'Ngày tùy chọn'
                    ];
                ?>
                <div id="chart-datepicker" class="dropdown dropstart ms-auto">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $options[$action] }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <?php
                            $options = collect($options)->filter(function($v,$k) {
                                return $k!= 'custom' && $k != request()->route('time');
                            });    
                        ?>
                        @foreach ($options as $k=>$v)
                        <li>
                            <a class="dropdown-item" href="/report/{{ $k }}">{{ $v }}</a>
                        </li>
                        @endforeach

                        <li class="mt-10 p-6 border-top dropdown dropstart">
                            <button onclick="showDatePicker()" class="btn btn-sm btn-success w-100 dropdown-toggle"
                            > Chọn ngày</button>

                            

                        </li>
                    </ul>
                </div>
            </div>

            <div class="section-content">

                <div class="d-flex col-gap-10">

                    <div class="flex-1 bg-white rounded-3">
                        <div class="p-1">

                            <div style="height: 300px">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>


                        <div class="px-16">Từ: {{ $fromDate }} - Đến:  {{ $toDate }}</div>

                        <div class="p-16 d-flex align-items-center">
                            <div class="d-flex flex-column pe-20 border-end">
                                <span class="opacity-70 fs-14" style="font-weight:600">Doanh thu</span>
                                <span id="revenueSummary" class="fs-16" style="font-weight:600">0 đ</span>
                            </div>


                            <div class="d-flex flex-column ms-20 border-end pe-20">
                                <span class="opacity-70 fs-14" style="font-weight:600">Lợi nhuận</span>
                                <span id="profitSummary" class="fs-16" style="font-weight:600">0 đ</span>
                            </div>

                            <div class="d-flex flex-column ms-20 border-end pe-20">
                                <span class="opacity-70 fs-14" style="font-weight:600">Hóa đơn</span>
                                <span id="receiptSummary" class="fs-16" style="font-weight:600">0</span>
                            </div>

                            <div id="prevTermSummary" class="d-none flex-column ps-20">
                                <span class="value fs-24 d-inline-flex align-items-center">
                                </span>
                                <span class="fs-12">So với kì trước</span>
                            </div>

                        </div>

                    </div>

                    <div class="flex-1 bg-white rounded-3">

                        <div class="d-flex">

                            <div class="flex-1 p-10">
                                <div id="grid" style="width: 100%; height: 380px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


@endsection

@section('pageScript')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
       
        const config = {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Doanh thu',
                        data: [],
                        borderColor: '#2076f7',
                        backgroundColor: '#e1e9f5',
                        borderWidth: 2,
                        lineTension: 0.5,
                        fill: true,
                    }
                ]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    yAxes: {
                        ticks: {
                            display: false
                        },
                    },
                    xAxes: {
                        ticks: {
                            display: false
                        },
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        };
        $.post('/report/chartReport', { 
            '_token': '{{ csrf_token() }}',
            'from' : '{{ $fromDate }}',
            'to' : '{{ $toDate }}',
            'period': '{{ $period }}'
        }, (result) => {
            
            /*
            Rendering Chart
            */
            config.data.labels = result.chartLabels;
            config.data.datasets[0].data = result.chartData.map(e => Math.round(e));
            var ctx = document.getElementById("myChart").getContext("2d");
            const myChart = new Chart(ctx, config);

            /*
            Chart Summary
            */
            $('#revenueSummary').text(toCurrency(result.reportData.total_revenue) + ' đ');
            $('#profitSummary').text(toCurrency(result.reportData.total_profit) + ' đ');
            $('#receiptSummary').text(result.reportData.num_receipts);

            if(result.prevTermRev > 0) {
                $('#prevTermSummary').removeClass('d-none');
                $('#prevTermSummary').addClass('d-flex');
                
                if (result.reportData.total_revenue > result.prevTermRev) {
                    var avg = Math.round(((result.reportData.total_revenue / result.prevTermRev) - 1)*100);
                    $('#prevTermSummary').addClass('txt-green');
                    $('#prevTermSummary .value').html(
                        '<i class="ri-arrow-up-fill"></i>' + avg + '%');

                }
                else {
                    var avg = Math.round(((result.prevTermRev/result.reportData.total_revenue) - 1)*100);
                    $('#prevTermSummary').addClass('text-danger');
                    $('#prevTermSummary .value').html(
                        '<i class="ri-arrow-down-fill"></i>' + avg + '%');
                }
            }
        });

    </script>
    <script>
        $(function () {
           

            $('#grid').w2grid({
                name: 'grid',
                recid: 'product_id',
                header: 'Sản phẩm bán chạy',
                records: <?php echo json_encode($bestSellingProducts->toArray()); ?>,
                show: {
                    header: true,
                    lineNumbers: true
                },
                columns: [
                    { field: 'product_id', text: 'ID', size: '50px' },
                    { field: 'product_name', text: 'Sản phẩm' },
                    { field: 'quantity', text: 'Đã bán' },
                ],
            });
        });
    </script>

    <script>

        function pickDate() 
        {
            var body = {
                'from': $('input[type=us-date1]').val(),
                'to': $('input[type=us-date2]').val(),
                '_token': '{{ csrf_token() }}'
            };
            $('#datepicker .validation').addClass('d-none');
            if (!body.from) {
                $('#datepicker .validation[for="us-date1"]').removeClass('d-none');
            }
            else if(!body.to) {
                $('#datepicker .validation[for="us-date2"]').removeClass('d-none');
            }
            else {
                window.location.href=`/report/custom?from=${body.from}&to=${body.to}&_token=${body._token}`;
            }
        }

        function showDatePicker() {

            w2popup.open({
                title: 'Tùy chọn ngày',
                body: `
                    <div id="datepicker" class="p-10">
                    <div class="mb-2 d-flex flex-column">
                        <label>Từ ngày:</label>
                        <div> <input type="us-date1"></div>
                        <span for="us-date1" class="d-none validation form-text text-danger"> Hãy chọn ngày bắt đầu. </span>
                    </div>
                    <div class="mb-2 d-flex flex-column">
                        <label>Đến ngày:</label>
                        <div> <input type="us-date2"></div>
                        <span for="us-date2" class="d-none validation form-text text-danger"> Hãy chọn ngày kết thúc. </span>
                    </div>
                    </div>
                `,
                buttons: `
                    <button class="w2ui-btn btn-primary" onclick="pickDate()">Nhập</button>
                    <button class="w2ui-btn" onclick="w2popup.close();">Đóng</button> 
                `,
                onOpen: function(e) {
                    e.onComplete = function() {
                        $('input[type=us-date1]').w2field('date', { 
                            format: 'd-m-yyyy', end: $('input[type=us-date2]') 
                        });
                        $('input[type=us-date2]').w2field('date', { 
                            format: 'd-m-yyyy', start: $('input[type=us-date1]') 
                        });
                    }
                },
                width: 350,
                height: 250,
            });
        }
    </script>

@endsection