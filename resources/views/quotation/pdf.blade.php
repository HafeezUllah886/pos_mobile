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

</head>

<body>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-header border-bottom-dashed">
                                <img src="{{public_path('assets/images/Header.jpg')}}" class="w-100">
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        <div class="col-lg-12 ">
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4 text-center"><h2>QUOTATION</h2></div>
                            </div>
                            <div class="card-body">
                                <table class="w-100">
                                    <tr>
                                        <td>
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Quot #</p>
                                            <h5 class="fs-14 mb-0">{{$quot->id}}</h5>
                                        </td>
                                        <td>
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Customer Name</p>
                                        <h5 class="fs-14 mb-0">{{$quot->customerName}}</h5>
                                        <h6 class="fs-14 mb-0">{{$quot->customerAddress}}</h6>
                                        </td>
                                        <td>
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                        <h5 class="fs-14 mb-0">{{date("d M Y" ,strtotime($quot->date))}}</h5>
                                        </td>
                                        <td>
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Valid Till</p>
                                        <h5 class="fs-14 mb-0">{{date("d M Y" ,strtotime($quot->validTill))}}</h5>
                                        </td>
                                    </tr>
                                </table>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col" style="width: 50px;">#</th>
                                                <th scope="col" class="text-start">Product</th>
                                                <th scope="col" class="text-end">Qty</th>
                                                <th scope="col" class="text-end">Price</th>
                                                <th scope="col" class="text-end">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products-list">
                                           @foreach ($quot->details as $key => $product)
                                               <tr class="border-1 border-dark">
                                                <td class="m-1 p-1 border-1 border-dark">{{$key+1}}</td>
                                                <td class="text-start m-1 p-1 border-1 border-dark">{{$product->product->name}}</td>
                                                <td class="text-end m-1 p-1 border-1 border-dark">{{number_format($product->qty)}}</td>
                                                <td class="text-end m-1 p-1 border-1 border-dark">{{number_format($product->price, 2)}}</td>
                                                <td class="text-end m-1 p-1 border-1 border-dark">{{number_format($product->amount, 2)}}</td>
                                               </tr>
                                           @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="border-1 border-dark">
                                                <th colspan="4" class="text-end">Total</th>
                                                <th class="text-end">{{number_format($quot->details->sum('amount'),2)}}</th>
                                            </tr>
                                            <tr class="m-0 p-0">
                                                <th colspan="4" class="text-end p-0 m-0">Discount (-)</th>
                                                <th class="text-end p-0 m-0 ">{{number_format($quot->discount,2)}}</th>
                                            </tr>
                                            <tr class="m-0 p-0">
                                                <th colspan="4" class="text-end p-0 m-0">Delivery Charges (+)</th>
                                                <th class="text-end p-0 m-0 ">{{number_format($quot->dc,2)}}</th>
                                            </tr>
                                            <tr class="m-0 p-0">
                                                <th colspan="4" class="text-end p-0 m-0">Net Amount</th>
                                                <th class="text-end p-0 m-0 border-2 border-start-0 border-end-0 border-dark">{{number_format($quot->total,2)}}</th>
                                            </tr>

                                        </tfoot>
                                    </table><!--end table-->
                                </div>
                            </div>
                            <div class="card-footer">
                                @if ($quot->notes != "")
                                <p><strong>Notes: </strong>{{$quot->notes}}</p>
                                @endif
                               <p class="text-center urdu"><strong></strong></p>

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



