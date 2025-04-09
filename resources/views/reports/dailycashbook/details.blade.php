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
                                        <h3>Daily Cash Book</h3>
                                    </div>
                                </div>
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        @php
                            $total_cr = $cr_trans->sum('cr');
                            $total_db = $db_trans->sum('db');
                            $closingBalance = ($pre_balance + $total_cr) - $total_db;
                        @endphp
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between">
                                    <div class="">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                        <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($date)) }}</h5>
                                    </div>
                                    <!--end col-->

                                    <div class="">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Opening Balance</p>
                                        <h5 class="fs-14 mb-0">{{ number_format($pre_balance, 2) }}</h5>
                                    </div>
                                    <!--end col-->

                                    <div class="">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold text-success">Total Credits</p>
                                        <h5 class="fs-14 mb-0 text-success">{{ number_format($total_cr, 2) }}</h5>
                                    </div>
                                    <!--end col-->

                                    <div class="">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold text-danger">Total Debits</p>
                                        <h5 class="fs-14 mb-0 text-danger">{{ number_format($total_db, 2) }}</h5>
                                    </div>
                                    <!--end col-->

                                    <div class="">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Closing Balance</p>
                                        <h5 class="fs-14 mb-0">{{ number_format($closingBalance, 2) }}</h5>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-header">
                                <h4 class="m-0">Credits / Inflow</h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col"  class="m-0 p-1" style="width: 50px;">#</th>
                                                <th scope="col"  class="m-0 p-1">Ref #</th>
                                                <th scope="col" class="text-start m-0 p-1">Account</th>
                                                <th scope="col" class="text-start m-0 p-1">Notes</th>
                                                <th scope="col" class="text-end m-0 p-1">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                        @foreach ($cr_trans as $key => $cr_tran)
                                            <tr class="border-1">
                                                <td class="m-0 p-0">{{ $key+1 }}</td>
                                                <td class="m-0 p-0">{{ $cr_tran->refID }}</td>
                                                <td class="text-start m-0 p-0">{{ $cr_tran->account->title }}</td>
                                                <td class="text-start m-0 p-0">{!! $cr_tran->notes !!}</td>
                                                <td class="text-end m-0 p-0">{{ number_format($cr_tran->cr,2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end">Total Credits</th>
                                                <th class="text-end">{{number_format($cr_trans->sum('cr'), 2)}}</th>
                                            </tr>
                                        </tfoot>
                                    </table><!--end table-->
                                </div>

                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-header">
                                <h4 class="m-0">Debits / Outflow</h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col"  class="m-0 p-1" style="width: 50px;">#</th>
                                                <th scope="col"  class="m-0 p-1">Ref #</th>
                                                <th scope="col" class="text-start m-0 p-1">Account</th>
                                                <th scope="col" class="text-start m-0 p-1">Notes</th>
                                                <th scope="col" class="text-end m-0 p-1">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                        @foreach ($db_trans as $key => $db_tran)
                                            <tr class="border-1">
                                                <td class="m-0 p-0">{{ $key+1 }}</td>
                                                <td class="m-0 p-0">{{ $db_tran->refID }}</td>
                                                <td class="text-start m-0 p-0">{{ $db_tran->account->title }}</td>
                                                <td class="text-start m-0 p-0">{!! $db_tran->notes !!}</td>
                                                <td class="text-end m-0 p-0">{{ number_format($db_tran->db,2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end">Total Debits</th>
                                                <th class="text-end">{{number_format($db_trans->sum('db'), 2)}}</th>
                                            </tr>
                                        </tfoot>
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



