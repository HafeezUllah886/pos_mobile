@extends('layout.popups')
@section('content')
        <div class="row justify-content-center">
            <div class="col-xxl-9">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                                <a href="javascript:window.print()" class="btn btn-success ml-4"><i class="ri-printer-line mr-4"></i> Print</a>
                            </div>
                            <div class="card-header border-bottom-dashed p-4">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h1>DELUXE CABLES QUETTA</h1>
                                    </div>
                                    <div class="flex-shrink-0 mt-sm-0 mt-3">
                                        <h3>Product Stock</h3>
                                    </div>
                                </div>
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Product</p>
                                        <h5 class="fs-14 mb-0">{{ $product->name }}</h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Dates</p>
                                        <h5 class="fs-14 mb-0"><small class="text-muted" id="invoice-time">From </small><span id="invoice-date">{{ date("d M Y", strtotime($from)) }}</span> </h5>
                                        <h5 class="fs-14 mb-0"><small class="text-muted" id="invoice-time">To &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</small><span id="invoice-date">{{ date("d M Y", strtotime($to)) }}</span> </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Balance</p>
                                        <h5 class="fs-14 mb-0"><small class="text-muted" id="invoice-time">Current &nbsp;</small><span id="invoice-date">{{ number_format($cur_balance) }}</span> </h5>
                                        <h5 class="fs-14 mb-0"><small class="text-muted" id="invoice-time">Previous </small><span id="invoice-date">{{ number_format($pre_balance) }}</span> </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Printed On</p>
                                        <h5 class="fs-14 mb-0"><span id="total-amount">{{ date("d M Y") }}</span></h5>
                                        {{-- <h5 class="fs-14 mb-0"><span id="total-amount">{{ \Carbon\Carbon::now()->format('h:i A') }}</span></h5> --}}
                                    </div>
                                    <!--end col-->
                                </div>
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
                                                <th scope="col" class="text-start">Warehouse</th>
                                                <th scope="col" class="text-start">Notes</th>
                                                <th scope="col" class="text-end">Credit</th>
                                                <th scope="col" class="text-end">Debit</th>
                                                <th scope="col" class="text-end">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products-list">
                                            @php
                                                $balance = $pre_balance;
                                            @endphp
                                        @foreach ($stocks as $key => $stock)
                                        @php
                                            $balance += $stock->cr;
                                            $balance -= $stock->db;
                                        @endphp
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $stock->refID }}</td>
                                                <td>{{ date('d M Y', strtotime($stock->date)) }}</td>
                                                <td class="text-start">{{ $stock->warehouse->name }}</td>
                                                <td class="text-start">{{ $stock->notes }}</td>
                                                <td class="text-end">{{ number_format($stock->cr) }}</td>
                                                <td class="text-end">{{ number_format($stock->db) }}</td>
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

@endsection

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/libs/datatable/datatable.bootstrap5.min.css') }}" />
<!--datatable responsive css-->
<link rel="stylesheet" href="{{ asset('assets/libs/datatable/responsive.bootstrap.min.css') }}" />

<link rel="stylesheet" href="{{ asset('assets/libs/datatable/buttons.dataTables.min.css') }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.print.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/vfs_fonts.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/pdfmake.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/jszip.min.js')}}"></script>

    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
@endsection

