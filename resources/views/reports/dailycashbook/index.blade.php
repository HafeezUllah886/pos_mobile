@extends('layout.app')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>View Daily Cash Book Report</h3>
                </div>
                <div class="card-body">
                    <div class="form-group mt-2">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control">
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
            var date = $("#date").val();
            var url = "{{ route('reportCashbookData', ['date' => ':date']) }}"
        .replace(':date', date);
            window.open(url, "_blank", "width=1000,height=800");
        });
    </script>
@endsection
