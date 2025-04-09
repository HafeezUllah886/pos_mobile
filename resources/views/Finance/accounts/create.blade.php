@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Create Account</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('account.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="title">Account Title</label>
                                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mt-2">
                                    <label for="type">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="Business">Business</option>
                                        <option value="Customer">Customer</option>
                                        <option value="Vendor">Vendor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-2" id="catBox">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="Cash">Cash</option>
                                        <option value="Bank">Bank</option>
                                        <option value="Cheque">Cheque</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6 mt-2 customer" >
                                <div class="form-group">
                                    <label for="contact">Contact #</label>
                                    <input type="text" name="contact" id="contact" value="{{ old('contact') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-6 mt-2 customer" >
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="initial">Initial Amount</label>
                                    <div class="input-group">
                                        <input type="number" step="any" id="initial" name="initial" value="0"
                                            min="0" class="form-control">
                                        <div class="input-group-append">
                                            <select class="form-control" name="initialType" id="inputGroupSelect04">
                                                <option value="0">Credit</option>
                                                <option value="1">Debit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-secondary w-100">Create</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Default Modals -->


@endsection


@section('page-js')
    <script>
        $(".customer").hide();
        $("#type").on("change",  function (){
            var type = $("#type").find(":selected").val();

            if(type === "Business")
            {
                $("#catBox").show();
            }
            else
            {
                $("#catBox").hide();
            }

            if(type === "Customer")
            {
                $(".customer").show();
            }
            else
            {
                $(".customer").hide();
            }
        });
    </script>
@endsection
