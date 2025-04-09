@extends('layout.popups')
@section('content')
        <div class="row justify-content-center">
            <div class="col-xxl-9">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                                <a href="{{url('account/statement/pdf/')}}/{{$account->id}}/{{$from}}/{{$to}}" class="btn btn-info ml-4"><i class="ri-file-line mr-4"></i> Generate PDF</a>
                                <a href="https://web.whatsapp.com/" target="_blank" class="btn btn-success ml-4"><i class="ri-whatsapp-line mr-4"></i> Whatsapp</a>
                                <a href="javascript:window.print()" class="btn btn-success ml-4"><i class="ri-printer-line mr-4"></i> Print</a>
                            </div>
                            <div class="card-header border-bottom-dashed p-4">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h1>MILLAT MACHINERY & SOLAR AGENCY</h1>
                                    </div>
                                </div>
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Account Title</p>
                                        <h5 class="fs-14 mb-0">{{ $account->title }}</h5>
                                        <h5 class="fs-14 mb-0">{{ $account->type }}</h5>
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
                                        <h5 class="fs-14 mb-0"><small class="text-muted" id="invoice-time">Opening </small><span id="invoice-date">Rs. {{ number_format($pre_balance) }}</span> </h5>
                                        <h5 class="fs-14 mb-0"><small class="text-muted" id="invoice-time">Closing &nbsp;&nbsp;</small><span id="invoice-date">Rs. {{ number_format($cur_balance) }}</span> </h5>

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

