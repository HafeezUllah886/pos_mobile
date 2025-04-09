@extends('layout.popups')
@section('content')
        <div class="row justify-content-center">
            <div class="col-xxl-9">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                                <a href="{{url('sales/pdf/')}}/{{$sale->id}}" class="btn btn-info ml-4"><i class="ri-file-line mr-4"></i> Generate PDF</a>
                                <a href="https://web.whatsapp.com/" target="_blank" class="btn btn-success ml-4"><i class="ri-whatsapp-line mr-4"></i> Whatsapp</a>
                                <a href="javascript:window.print()" class="btn btn-primary ml-4"><i class="ri-printer-line mr-4"></i> Print</a>
                            </div>
                            <div class="card-header border-bottom-dashed p-4">
                                @include('layout.header')
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        <div class="col-lg-12 ">
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4 text-center"><h2>SALES INVOICE</h2></div>
                            </div>
                            <div class="card-body p-4 pb-0 pt-0">
                                <div class="row g-3">
                                    <div class="col-3">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Inv #</p>
                                        <h5 class="fs-14 mb-0">{{$sale->id}}</h5>
                                    </div>
                                    <div class="col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Customer</p>
                                        <h5 class="fs-14 mb-0">{{$sale->customer->title}}</h5>
                                        <h6 class="fs-14 mb-0">{{$sale->customerID != 2 ? $sale->customer->address : $sale->customerName}}</h6>
                                    </div>
                                    <div class="col-3">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                        <h5 class="fs-14 mb-0">{{date("d M Y" ,strtotime($sale->date))}}</h5>
                                    </div>
                                    <!--end col-->
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body p-4 pb-0 pt-0">
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
                                        <tbody id="products-list" style="font-weight: bolder">
                                           @foreach ($sale->details as $key => $product)
                                               <tr class="border-1 border-dark">
                                                <td class="m-1 p-1 border-1 border-dark">{{$key+1}}</td>
                                                <td class="text-start m-1 p-1 border-1 border-dark">{{$product->product->name}}</td>
                                                <td class="text-end m-1 p-1 border-1 border-dark">{{number_format($product->qty)}}</td>
                                                <td class="text-end m-1 p-1 border-1 border-dark">{{number_format($product->price, 2)}}</td>
                                                <td class="text-end m-1 p-1 border-1 border-dark">{{number_format($product->amount, 2)}}</td>
                                               </tr>
                                           @endforeach
                                        </tbody>
                                        <tfoot style="font-weight: bolder">
                                            @php
                                                $due = $sale->total - $sale->payments->sum('amount');
                                                $paid = $sale->payments->sum('amount');
                                            @endphp
                                            <tr class="border-1 border-dark">
                                                <th colspan="4" class="text-end">Total</th>
                                                <th class="text-end">{{number_format($sale->details->sum('amount'),2)}}</th>
                                            </tr>
                                            <tr class="m-0 p-0">
                                                <th colspan="4" class="text-end p-0 m-0">Discount (-)</th>
                                                <th class="text-end p-0 m-0 ">{{number_format($sale->discount,2)}}</th>
                                            </tr>
                                            <tr class="m-0 p-0">
                                                <th colspan="4" class="text-end p-0 m-0">Delivery Charges (+)</th>
                                                <th class="text-end p-0 m-0 ">{{number_format($sale->dc,2)}}</th>
                                            </tr>
                                            <tr class="m-0 p-0">
                                                <th colspan="4" class="text-end p-0 m-0">Net Bill</th>
                                                <th class="text-end p-0 m-0 border-2 border-start-0 border-end-0 border-dark">{{number_format($sale->total,2)}}</th>
                                            </tr>
                                            <tr class="m-0 p-0">
                                                <th colspan="4" class="text-end p-0 m-0">Paid</th>
                                                <th class="text-end p-0 m-0">{{number_format($paid,2)}}</th>
                                            </tr>
                                            <tr class="m-0 p-0">
                                                <th colspan="4" class="text-end p-0 m-0">Due</th>
                                                <th class="text-end p-0 m-0">{{number_format($due,2)}}</th>
                                            </tr>
                                            @if ($sale->customerID != 2)
                                            <tr class="m-0 p-0">
                                                <th colspan="4" class="text-end p-0 m-0">Previous Balance</th>
                                                <th class="text-end p-0 m-0">{{number_format(spotBalanceBefore($sale->customerID, $sale->refID),2)}}</th>
                                            </tr>
                                            <tr class="m-0 p-0">
                                                <th colspan="4" class="text-end p-0 m-0">Net Account Balance</th>
                                                <th class="text-end p-0 m-0">{{number_format(spotBalance($sale->customerID, $sale->refID),2)}}</th>
                                            </tr>
                                            @endif

                                        </tfoot>
                                    </table><!--end table-->
                                </div>
                            </div>
                            <div class="card-footer">
                                @if ($sale->notes != "")
                                <p><strong>Notes: </strong>{{$sale->notes}}</p>
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

@endsection

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/libs/datatable/datatable.bootstrap5.min.css') }}" />
<!--datatable responsive css-->
<link rel="stylesheet" href="{{ asset('assets/libs/datatable/responsive.bootstrap.min.css') }}" />

<link rel="stylesheet" href="{{ asset('assets/libs/datatable/buttons.dataTables.min.css') }}">
<link href='https://fonts.googleapis.com/css?family=Noto Nastaliq Urdu' rel='stylesheet'>
<style>
    .urdu {
        font-family: 'Noto Nastaliq Urdu';font-size: 12px;
    }
    </style>
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

