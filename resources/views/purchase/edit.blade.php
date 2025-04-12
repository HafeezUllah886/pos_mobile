@extends('layout.popups')
@section('content')
<script>
    var existingProducts = [];

    @foreach ($purchase->details as $product)
        @php
            $productID = $product->productID;
        @endphp
        existingProducts.push({{$productID}});
    @endforeach
</script>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6"><h3> Edit Purchase </h3></div>
                                <div class="col-6 d-flex flex-row-reverse"><button onclick="window.close()" class="btn btn-danger">Close</button></div>
                            </div>

                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('purchase.update', $purchase->id) }}" method="post">
                        @csrf
                        @method("PUT")
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="product">Product</label>
                                    <select name="product" class="selectize" id="product">
                                        <option value=""></option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">

                                <table class="table table-striped table-hover">
                                    <thead>
                                        <th >Product</th>
                                        <th class="text-center">IMEI Number</th>
                                        <th width="20%" class="text-center">Price</th>
                                        <th></th>
                                    </thead>
                                    <tbody id="products_list">
                                        @foreach ($purchase->details as $product)
                                        @php
                                            $id = $product->product->id;
                                        @endphp
                                        <tr id="row_{{$id}}">
                                            <td class="no-padding">{{$product->product->name}}</td>
                                            <td class="no-padding"><input type="text" name="imei[]" {{$product->status == "Available" ? "" : "disabled"}} value="{{$product->imei}}" class="form-control text-center no-padding"></td>
                                            <td class="no-padding"><input type="number" name="price[]" {{$product->status == "Available" ? "" : "disabled"}} oninput="updateTotal()" step="any" value="{{$product->price}}" min="1" class="form-control text-center no-padding price"></td>
                                            <td class="no-padding"> @if($product->status == "Available") <span class="btn btn-sm btn-danger" onclick="deleteRow({{$id}})">X</span> @endif </td>
                                            <input type="hidden" name="id[]" value="{{$id}}">
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-end">Total</th>
                                            <th class="text-end" id="totalAmount">{{$purchase->details->sum('price')}}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="comp">Purchase Inv No.</label>
                                    <input type="text" name="inv" id="inv" value="{{$purchase->inv}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" id="date" value="{{ $purchase->date }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="vendor">Vendor</label>
                                    <select name="vendorID" id="vendorID" class="selectize1">
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" @selected($vendor->id == $purchase->vendorID)>{{ $vendor->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group vendorName mt-2">
                                    <label for="vendorName">Name</label>
                                    <input type="text" name="vendorName" value="{{$purchase->vendorName}}" id="vendorName" class="form-control">
                                </div>
                            </div>

                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="account">Account</label>
                                    <select name="accountID" id="account" class="selectize1">
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="status">Payment Status</label>
                                    <select name="status" id="status1" class="form-control">
                                        <option value="advanced">Paid in Advance</option>
                                        <option value="paid">Paid</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="5">{{$purchase->notes}}</textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Update Purchase</button>
                            </div>
                </div>
            </form>
            </div>

        </div>
        <!--end card-->
    </div>
    <!--end col-->
    </div>
    <!--end row-->
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
    <style>
        .no-padding {
            padding: 5px 5px !important;
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".selectize1").selectize();
        $(".selectize").selectize({
            onChange: function(value) {
                if (!value.length) return;
                if (value != null) {
                    getSingleProduct(value);
                    this.clear();
                    this.focus();
                }

            },
        });
       
        function getSingleProduct(id) {
            $.ajax({
                url: "{{ url('purchases/getproduct/') }}/" + id,
                method: "GET",
                success: function(product) {
                    let found = $.grep(existingProducts, function(element) {
                        return element === product.id;
                    });
                    if (found.length > 0) {

                    } else {
                        var id = product.id;

                        var html = '<tr id="row_' + id + '">';
                        html += '<td class="no-padding">' + product.name + '</td>';
                        html += '<td class="no-padding"><input type="text" name="imei[]" class="form-control text-center no-padding"></td>';
                        html += '<td class="no-padding"><input type="number" name="price[]" oninput="updateTotal()" step="any" value="'+product.pprice+'" min="1" class="form-control text-center no-padding price"></td>';
                        html += '<td class="no-padding"><span class="btn btn-sm btn-danger" onclick="deleteRow('+id+')">X</span> </td>';
                        html += '<input type="hidden" name="id[]" value="' + id + '">';
                        html += '</tr>';
                        $("#products_list").prepend(html);
                        existingProducts.push(id);
                        updateTotal(id);
                    }
                }
            });
        }

        function updateTotal() {
            var total = 0;
            $(".price").each(function() {
    var inputValue = $(this).val();
    total += parseFloat(inputValue) || 0;
});

            $("#totalAmount").html(total.toFixed(2));
        }

        function deleteRow(id) {
            existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
            $('#row_'+id).remove();
            updateTotal();
        }

        function checkAccount()
    {
        var id = $("#vendorID").find(":selected").val();
        if(id == 3)
        {
            $(".customerName").removeClass("d-none");
            $('#status1 option').each(function() {
            var optionValue = $(this).val();
            if (optionValue === 'advanced' || optionValue === 'pending' || optionValue === 'partial') {
                $(this).prop('disabled', true);
            }
            if (optionValue === 'paid') {
                $(this).prop('selected', true);
            }
            });
        }
        else
        {
            $(".customerName").addClass("d-none");
            $('#status1 option').each(function() {
            var optionValue = $(this).val();
            if (optionValue === 'advanced' || optionValue === 'pending' || optionValue === 'partial') {
                $(this).prop('disabled', false);
            }
            });
        }
    }

    $("#vendorID").on("change", function(){
        checkAccount();
    });
    checkAccount();


    </script>
@endsection
