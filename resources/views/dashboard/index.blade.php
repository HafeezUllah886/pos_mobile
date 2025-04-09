@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card crm-widget">
            <div class="card-body p-0">
                <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                    <div class="col">
                        @php
                            $customersBalance = customerBalance();
                            $vendorsBalance = vendorBalance();
                            $myBalance = myBalance();
                        @endphp
                        <div class="py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">Purchases</h5>
                            <div class="d-flex align-items-center">
                                {{-- <div class="flex-shrink-0">
                                    <i class="ri-space-ship-line display-6 text-muted cfs-22"></i>
                                </div> --}}
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0 cfs-22"><span class="counter-value">{{number_format(totalPurchases(), 0)}}</span></h2>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col">
                        <div class="mt-3 mt-md-0 py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">Sales</h5>
                            <div class="d-flex align-items-center">
                               {{--  <div class="flex-shrink-0">
                                    <i class="ri-exchange-dollar-line display-6 text-muted cfs-22"></i>
                                </div> --}}
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0 cfs-22"><span class="counter-value">{{number_format(totalSales(), 0)}}</span></h2>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col">
                        <div class="mt-3 mt-md-0 py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">Customers Balance</h5>
                            <div class="d-flex align-items-center">
                                {{-- <div class="flex-shrink-0">
                                    <i class="ri-pulse-line display-6 text-muted cfs-22"></i>
                                </div> --}}
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0 cfs-22 {{$customersBalance < 0 ? "text-danger" : "text-success"}}"><span class="counter-value" >{{number_format($customersBalance, 0)}}</span></h2>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col">
                        <div class="mt-3 mt-lg-0 py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">Vendors Balance</h5>
                            <div class="d-flex align-items-center">
                                {{-- <div class="flex-shrink-0">
                                    <i class="ri-trophy-line display-6 text-muted cfs-22"></i>
                                </div> --}}
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0 cfs-22 {{$vendorsBalance < 0 ? "text-danger" : "text-success"}}"><span class="counter-value">{{number_format($vendorsBalance, 0)}}</span></h2>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col">
                        <div class="mt-3 mt-lg-0 py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">My Balance</h5>
                            <div class="d-flex align-items-center">
                                {{-- <div class="flex-shrink-0">
                                    <i class="ri-service-line display-6 text-muted cfs-22"></i>
                                </div> --}}
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0 cfs-22 {{$myBalance < 0 ? "text-danger" : "text-success"}}"><span class="counter-value" >{{number_format($myBalance, 0)}}</span></h2>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col">
                        <div class="mt-3 mt-lg-0 py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">Stock Value</h5>
                            <div class="d-flex align-items-center">
                                {{-- <div class="flex-shrink-0">
                                    <i class="ri-service-line display-6 text-muted cfs-22"></i>
                                </div> --}}
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0 cfs-22"><span class="counter-value" >{{number_format(stockValue(), 0)}}</span></h2>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div><!-- end row -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Revenue (Monthly)</h4>
            </div><!-- end card header -->

            <div class="card-header p-0 border-0 bg-light-subtle">
                <div class="row g-0 text-center">
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1"><span class="counter-value" data-target="{{$last_sale}}">0</span></h5>
                            <p class="text-muted mb-0">Sales</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1"><span class="counter-value" data-target="{{$last_expense}}">0</span></h5>
                            <p class="text-muted mb-0">Expenses</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1"><span class="counter-value" data-target="{{$last_profit}}">0</span></h5>
                            <p class="text-muted mb-0">Profit</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0 border-end-0">
                            <h5 class="mb-1 text-success"><span class="counter-value" data-target="{{$last_profit - $last_expense}}">0</span></h5>
                            <p class="text-muted mb-0">Net Profit</p>
                        </div>
                    </div>
                    <!--end col-->
                </div>
            </div><!-- end card header -->

            <div class="card-body p-0 pb-2">
                <div class="w-100">
                    <div id="customer_impression_charts" data-colors='["--vz-primary", "--vz-success", "--vz-danger"]' data-colors-minimal='["--vz-light", "--vz-primary", "--vz-info"]' data-colors-saas='["--vz-success", "--vz-info", "--vz-danger"]' data-colors-modern='["--vz-warning", "--vz-primary", "--vz-success"]' data-colors-interactive='["--vz-info", "--vz-primary", "--vz-danger"]' data-colors-creative='["--vz-warning", "--vz-primary", "--vz-danger"]' data-colors-corporate='["--vz-light", "--vz-primary", "--vz-secondary"]' data-colors-galaxy='["--vz-secondary", "--vz-primary", "--vz-primary-rgb, 0.50"]' data-colors-classic='["--vz-light", "--vz-primary", "--vz-secondary"]' data-colors-vintage='["--vz-success", "--vz-primary", "--vz-secondary"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->


    <!-- end col -->
</div>
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Best Selling Products</h4>
                <div class="flex-shrink-0">
                </div>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                        <tbody>
                            @foreach ($topProductsArray as $product)
                            <tr>
                                <td>
                                    <div>
                                        <h5 class="fs-14 my-1">{{$product['name']}}</h5>
                                        <span class="text-muted">Product</span>
                                    </div>
                                </td>
                                <td>
                                    <h5 class="fs-14 my-1 fw-normal">{{number_format($product['price'])}}</h5>
                                    <span class="text-muted">Avg Price</span>
                                </td>
                                <td>
                                    <h5 class="fs-14 my-1 fw-normal">{{number_format($product['sold'])}}</h5>
                                    <span class="text-muted">Sold</span>
                                </td>
                                <td>
                                    <h5 class="fs-14 my-1 fw-normal">{{number_format($product['stock'])}}</h5>
                                    <span class="text-muted">Stock</span>
                                </td>
                                <td>
                                    <h5 class="fs-14 my-1 fw-normal">{{number_format($product['amount'])}}</h5>
                                    <span class="text-muted">Amount</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Top Customers</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                        <tbody>
                           @foreach ($topCustomersArray as $customer)
                           <tr>
                            <td>
                                <div class="">
                                        <h5 class="fs-14 my-1 fw-medium">{{$customer['name']}}</h5>
                                        <span class="text-muted">Title</span>
                                </div>
                            </td>
                            <td>
                                <h5 class="fs-14 my-1 fw-normal">{{number_format($customer['purchases'])}}</h5>
                                <span class="text-muted">Total Purchase</span>
                            </td>
                            <td>
                                <h5 class="fs-14 my-1 fw-normal">{{number_format($customer['balance'])}}</h5>
                                <span class="text-muted">Current Balance</span>
                            </td>
                        </tr><!-- end -->
                           @endforeach
                        </tbody>
                    </table><!-- end table -->
                </div>

            </div> <!-- .card-body-->
        </div> <!-- .card-->
    </div> <!-- .col-->
</div> <!-- end row-->
@endsection
@section('page-css')

@endsection
@section('page-js')
       <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
       <script src="{{asset('assets/js/pages/dashboard-ecommerce.init.js')}}"></script>
       <script>


        function updateCustomerImpressionChart(ordersData, earningsData, refundsData, months) {
            var t = getChartColorsArray("customer_impression_charts");
            if (t) {
                var e = {
                    series: [
                        { name: "Sales", type: "area", data: ordersData },   // Updated Orders data
                        { name: "Profit", type: "bar", data: earningsData }, // Updated Earnings data
                        { name: "Expense", type: "line", data: refundsData } // Updated Refunds data
                    ],
                    chart: { height: 370, type: "line", toolbar: { show: !1 } },
                    stroke: { curve: "straight", dashArray: [0, 0, 8], width: [2, 0, 2.2] },
                    fill: { opacity: [0.1, 0.9, 1] },
                    markers: { size: [0, 0, 0], strokeWidth: 2, hover: { size: 4 } },
                    xaxis: { categories: months, axisTicks: { show: !1 }, axisBorder: { show: !1 } },
                    grid: { show: !0, xaxis: { lines: { show: !0 } }, yaxis: { lines: { show: !1 } }, padding: { top: 0, right: -2, bottom: 15, left: 10 } },
                    legend: { show: !0, horizontalAlign: "center", offsetX: 0, offsetY: -5, markers: { width: 9, height: 9, radius: 6 }, itemMargin: { horizontal: 10, vertical: 0 } },
                    plotOptions: { bar: { columnWidth: "30%", barHeight: "70%" } },
                    colors: t,
                    tooltip: {
                        shared: !0,
                        y: [
                            {
                                formatter: function (e) {
                                    return void 0 !== e ? e.toFixed(0) : e;
                                },
                            },
                            {
                                formatter: function (e) {
                                    return void 0 !== e ? e.toFixed(2) : e;
                                },
                            },
                            {
                                formatter: function (e) {
                                    return void 0 !== e ? e.toFixed(0) : e;
                                },
                            },
                        ],
                    },
                };
                if (customerImpressionChart) {
                    customerImpressionChart.destroy();
                }
                customerImpressionChart = new ApexCharts(document.querySelector("#customer_impression_charts"), e);
                customerImpressionChart.render();
            }
        }

        var sales = @json($sales);
        var months = @json($monthNames);
        var expenses = @json($expenses);
        var profits = @json($profits);
        updateCustomerImpressionChart(
            sales,
            profits,
            expenses,
            months
        );

       </script>
@endsection

