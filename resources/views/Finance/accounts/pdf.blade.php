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
                                <div class="col-4 text-center"><h2>ACCOUNT STATEMENT</h2></div>
                            </div>
                            <div class="card-body">
                                <table class="w-100">
                                    <tr>
                                        <td>
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Account Title</p>
                                        <h5 class="fs-14 mb-0">{{ $account->title }}</h5>
                                        <h5 class="fs-14 mb-0">{{ $account->type }}</h5>
                                        </td>
                                        <td>
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Dates</p>
                                        <h5 class="fs-14 mb-0"><small class="text-muted" id="invoice-time">From </small><span id="invoice-date">{{ date("d M Y", strtotime($from)) }}</span> </h5>
                                        <h5 class="fs-14 mb-0"><small class="text-muted" id="invoice-time">To &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</small><span id="invoice-date">{{ date("d M Y", strtotime($to)) }}</span> </h5>
                                        </td>
                                        <td>
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Balance</p>
                                            <h5 class="fs-14 mb-0"><small class="text-muted" id="invoice-time">Opening </small><span id="invoice-date">Rs. {{ number_format($pre_balance) }}</span> </h5>
                                            <h5 class="fs-14 mb-0"><small class="text-muted" id="invoice-time">Closing &nbsp;&nbsp;</small><span id="invoice-date">Rs. {{ number_format($cur_balance) }}</span> </h5>
                                        </td>
                                        <td>
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Printed On</p>
                                            <h5 class="fs-14 mb-0"><span id="total-amount">{{ date("d M Y") }}</span></h5>
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
                                                <th scope="col" style="width: 50px;">Ref#</th>
                                                <th scope="col">Date</th>
                                                <th scope="col" class="text-start">Notes</th>
                                                <th scope="col" class="text-end text-success">Credit</th>
                                                <th scope="col" class="text-end text-danger">Debit</th>
                                                <th scope="col" class="text-end">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products-list">
                                            @php
                                                $balance = $pre_balance;
                                            @endphp
                                        @foreach ($transactions as $key => $trans)
                                        @php
                                            $balance += $trans->cr;
                                            $balance -= $trans->db;
                                        @endphp
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $trans->refID }}</td>
                                                <td>{{ date('d M Y', strtotime($trans->date)) }}</td>
                                                <td class="text-start">{!! $trans->notes !!}</td>
                                                <td class="text-end text-success">{{ number_format($trans->cr) }}</td>
                                                <td class="text-end text-danger">{{ number_format($trans->db) }}</td>
                                                <td class="text-end">{{ number_format($balance) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table><!--end table-->
                                </div>

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



