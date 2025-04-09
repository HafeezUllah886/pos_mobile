<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title>Business Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Business Management System" name="description" />
    <meta content="Hafeez Ullah" name="author" />

    <!-- Layout config Js -->
    <script src="{{ public_path('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ public_path('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->

    <!-- App Css-->
    <link href="{{ public_path('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ public_path('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ public_path('assets/libs/toastify/toastify.min.css') }}" rel="stylesheet" type="text/css" />

<link href='https://fonts.googleapis.com/css?family=Noto Nastaliq Urdu' rel='stylesheet'>
<style>
    @page {
    size: A4;
    margin: 1cm;
}
body {
    zoom: 0.75; /* Scale down to fit the content */
}
</style>

</head>

<body>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-header border-bottom-dashed">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h2>DELUXE CABLES QUETTA</h2>
                                    </div>
                                </div>
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        <div class="col-lg-12 ">
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4 text-center"><h3>PURCHASE VOUCHAR</h3></div>
                            </div>
                            <div class="card-body">
                                <table class="w-100">
                                    <tr>
                                        <td>
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Vourchar #</p>
                                            <h5 class="fs-14 mb-0">{{$purchase->id}}</h5>
                                        </td>
                                        <td>
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                        <h5 class="fs-14 mb-0">{{date("d M Y" ,strtotime($purchase->date))}}</h5>
                                        </td>
                                        <td>
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Vendor</p>
                                            <h5 class="fs-14 mb-0">{{$purchase->vendor->title}}</h5>
                                        </td>
                                        <td>
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Inv #</p>
                                            <h5 class="fs-14 mb-0"><span id="total-amount">{{ $purchase->inv ?? "-" }}</span></h5>
                                        </td>
                                    </tr>
                                </table>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body " style="padding-right: 50px">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="w-100">
                                            <table class="table text-center align-middle mb-0 w-100">
                                                <thead>
                                                    <tr class="table-active">
                                                        <th scope="col" style="width: 50px;">#</th>
                                                        <th scope="col" class="text-start">Product</th>
                                                        <th scope="col" class="text-start">Warehouse</th>
                                                        <th scope="col" class="text-end">P-Price</th>
                                                        <th scope="col" class="text-end">S-Price</th>
                                                        <th scope="col" class="text-end">Qty</th>
                                                        <th scope="col" class="text-end">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="products-list">
                                                   @foreach ($purchase->details as $key => $product)
                                                       <tr>
                                                        <td class="p-1 m-1">{{$key+1}}</td>
                                                        <td class="text-start p-1 m-1">{{$product->product->name}}</td>
                                                        <td class="text-start p-1 m-1">{{$product->warehouse->name}}</td>
                                                        <td class="text-end p-1 m-1">{{number_format($product->pprice,2)}}</td>
                                                        <td class="text-end p-1 m-1">{{number_format($product->price,2)}}</td>
                                                        <td class="text-end p-1 m-1">{{number_format($product->qty)}}</td>
                                                        <td class="text-end p-1 m-1">{{number_format($product->amount,2)}}</td>

                                                       </tr>
                                                   @endforeach
                                                </tbody>
                                            </table><!--end table-->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8"></div>
                                    <div class="col-4">
                                        @php
                                            $amount = $purchase->details->sum('amount');
                                            $discount = $purchase->discount;
                                            $dc = $purchase->dc;
                                            $net = ($amount + $dc) - $discount;
                                        @endphp
                                        <table class="table">
                                            <tr>
                                                <th class="text-end p-1 m-1">Total</th>
                                                <th class="text-end p-1 m-1">{{number_format($amount, 2)}}</th>
                                            </tr>
                                            <tr>
                                                <th class="text-end p-1 m-1">Discount</th>
                                                <th class="text-end p-1 m-1">{{number_format($discount, 2)}}</th>
                                            </tr>
                                            <tr>
                                                <th class="text-end p-1 m-1">Delivery Charges</th>
                                                <th class="text-end p-1 m-1">{{number_format($dc, 2)}}</th>
                                            </tr>
                                            <tr>
                                                <th class="text-end p-1 m-1">Net Bill </th>
                                                <th class="text-end p-1 m-1">{{number_format($net, 2)}}</th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>


                            </div>
                            <div class="card-footer">
                                <p><strong>Notes: </strong>{{$purchase->notes}}</p>
                            </div>
                            <!--end card-body-->
                        </div><!--end col-->


                    </div><!--end row-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    <!-- JAVASCRIPT -->
    <script src="{{ public_path('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ public_path('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ public_path('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ public_path('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ public_path('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    {{--     <script src="{{ asset('assets/js/plugins.js') }}"></script> --}}

    <!-- App js -->
    <script src="{{ public_path('assets/js/app.js') }}"></script>
    <script src="{{ public_path('assets/libs/toastify/toastify.min.js') }}"></script>

    <script src="{{ public_path('assets/js/jquery-3.6.0.min.js') }}"></script>

</body>

</html>



