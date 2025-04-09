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
                            <div class="card-header border-bottom-dashed p-4">
                                <div class="row">
                                    <table class="w-100">
                                        <tbody>
                                            <tr>
                                                <td> <h2 class="p-0 m-0">DELUXE CABLES QUETTA</h2></td>
                                                <td>
                                                    <h3>Payment Receiving Receipt</h3>
                                                    <p> <span class="text-muted text-uppercase fw-semibold mt-0 m-0 p-0">Receipt Ref # </span><span class="fs-14 m-0 p-0">{{$receiving->refID}}</span></p>
                                                    <p> <span class="text-muted text-uppercase fw-semibold mt-0 m-0 p-0">Date : </span><span class="fs-14 m-0 p-0">{{date("d M Y" ,strtotime($receiving->date))}}</span></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body p-3">
                               <table style="width:100%;">
                                <tr>
                                    <td style="width:30%;" class="p-3 pb-1"><strong>Received with thanks from</strong></td>
                                    <td class="border-2 border-top-0 border-start-0 border-end-0 text-center p-4 pb-1">{{$receiving->fromAccount->title}}</td>
                                </tr>
                                <tr>
                                    <td style="width:30%;" class="p-3 pb-1"><strong>Receiving Amount</strong></td>
                                    <td class="border-2 border-top-0 border-start-0 border-end-0 text-center p-4 pb-1">{{number_format($receiving->amount,2)}}</td>
                                </tr>
                                <tr>
                                    <td style="width:30%;" class="p-3 pb-1"><strong>Amount in Words</strong></td>
                                    <td class="border-2 border-top-0 border-start-0 border-end-0 text-center p-4 pb-1">Rupees {{numberToWords($receiving->amount,2)}} Only</td>
                                </tr>
                                <tr>
                                    <td style="width:30%;" class="p-3 pb-1"><strong>Received In</strong></td>
                                    <td class="border-2 border-top-0 border-start-0 border-end-0 text-center p-4 pb-1">{{$receiving->inAccount->title}}</td>
                                </tr>
                               </table>

                               <table style="width:100%;">
                                <tr>
                                    <td style="width:80%;" class="p-3 pb-1 text-end" colspan="3"><strong>Previous Balance: </strong></td>
                                    <td class="border-2 border-top-0 border-start-0 border-end-0 text-center p-4 pb-1" >{{number_format(spotBalanceBefore($receiving->fromID, $receiving->refID),2)}}</td>
                                </tr>
                                <tr>
                                    <td class="p-3 pb-1"><strong>Deposited By: _________________</strong></td>
                                    <td class="p-3 pb-1"><strong>Received By: _________________</strong></td>
                                    <td class="p-3 pb-1 text-end"><strong>Current Balance: </strong></td>
                                    <td class="border-2 border-top-0 border-start-0 border-end-0 text-center p-4 pb-1" >{{number_format(spotBalance($receiving->fromID, $receiving->refID),2)}}</td>
                                </tr>
                               </table>


                            </div>
                            <div class="card-footer">

                                <p><strong>Notes: </strong> {{$receiving->notes}}</p>


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

    <script src="{{ public_path('assets/js/app.js') }}"></script>
    <script src="{{ public_path('assets/libs/toastify/toastify.min.js') }}"></script>

    <script src="{{ public_path('assets/js/jquery-3.6.0.min.js') }}"></script>

</body>

</html>



