@extends('layout.app')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>View Ledger Report</h3>
                </div>
                <div class="card-body">
                    <div class="form-group mt-2">
                        <label for="from">From</label>
                        <input type="date" name="from" id="from" value="{{firstDayOfMonth()}}" class="form-control">
                    </div>
                    <div class="form-group mt-2">
                        <label for="to">To</label>
                                <input type="date" name="to" id="to" value="{{lastDayOfMonth()}}" class="form-control">
                    </div>
                    <div class="form-group mt-2">
                        <label for="type">Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="All">All Accounts</option>
                                    <option value="Business">Business Accounts</option>
                                    <option value="Customer">Customer Accounts</option>
                                    <option value="Vendor">Vendor Accounts</option>
                                </select>
                    </div>

                    <div class="form-group mt-2">
                        <button class="btn btn-success w-100" id="viewBtn">View Report</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('page-js')

    <script>

        $("#viewBtn").on("click", function (){
            var from = $("#from").val();
            var to = $("#to").val();
            var type = $("#type").find(":selected").val();
            var url = "{{ route('reportLedgerData', ['from' => ':from', 'to' => ':to', 'type' => ':type']) }}"
        .replace(':from', from)
        .replace(':to', to)
        .replace(':type', type);
            window.open(url, "_blank", "width=1000,height=800");
        });
    </script>
@endsection
