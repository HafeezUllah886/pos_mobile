@extends('layout.popups')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header row">
                    <div class="col-6"><h3> Sale Payments </h3></div>
                    <div class="col-6 d-flex flex-row-reverse"><button onclick="window.close()" class="btn btn-danger">Close</button></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('sale_payment.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="salesID" value="{{ $sale->id }}">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="number" name="amount" required step="any" min="1"
                                            max="{{ $due }}" value="{{ $due }}" id="amount"
                                            class="form-control">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="accountID">Account</label>
                                        <select name="accountID" id="accountID" required class="selectize">
                                            <option value="">Select Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}"> {{ $account->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="date">Date</label>
                                        <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                                            id="date" class="form-control">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="notes">Notes</label>
                                        <textarea name="notes" class="form-control" id="notes" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary w-100">Save</button>
                                </div>
                            </form>

                        </div>
                        <div class="col-md-6">

                            <table class="table">
                                <thead>
                                    <th>Date</th>
                                    <th>Account</th>
                                    <th>Notes</th>
                                    <th class="text-end">Amount</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach ($sale->payments as $payment)
                                        <tr>
                                            <td>{{ date('d M Y', strtotime($payment->date)) }}</td>
                                            <td>{{ $payment->account->title }}</td>
                                            <td>{{ $payment->notes }}</td>
                                            <td class="text-end">{{ number_format($payment->amount) }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('salePayment.show', $payment->id) }}"
                                                    class="btn btn-info btn-sm">Print</a>
                                                <a href="{{ route('salePayment.delete', [$sale->id, $payment->refID]) }}"
                                                    class="btn btn-danger btn-sm">X</a>

                                                </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th class="text-end">{{ number_format($sale->payments->sum('amount')) }}</th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    </div>
    <!-- Default Modals -->
@endsection
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".selectize").selectize();
    </script>
@endsection
