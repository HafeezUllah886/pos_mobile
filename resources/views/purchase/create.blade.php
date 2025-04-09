@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6"><h3> Create Purchase </h3></div>

                                <div class="col-6 d-flex flex-row-reverse">
                                    <button onclick="window.close()" class="btn btn-danger">Close</button>
                                    <button type="button" class="btn btn-primary" style="margin-right:10px;" data-bs-toggle="modal" data-bs-target="#new">Add Product</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('purchase.store') }}" method="post">
                        @csrf
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
                                        <th width="30%">Item</th>
                                        <th width="10%" class="text-center">Warehouse</th>
                                        <th class="text-center">P-Price</th>
                                        <th class="text-center">S-Price</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Amount</th>
                                        <th></th>
                                    </thead>
                                    <tbody id="products_list"></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-end">Total</th>
                                            <th class="text-end" id="totalAmount">0.00</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="comp">Purchase Inv No.</label>
                                    <input type="text" name="inv" id="inv" class="form-control">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="discount">Discount</label>
                                    <input type="number" name="discount" oninput="updateTotal()" id="discount" step="any" value="0" class="form-control no_zero">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="dc">Delivery Charges</label>
                                    <input type="number" name="dc" id="dc" oninput="updateTotal()" min="0" step="any" value="0" class="form-control no_zero">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="net">Net Amount</label>
                                    <input type="number" name="net" id="net" step="any" readonly value="0" class="form-control">
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="vendor">Vendor</label>
                                    <select name="vendorID" id="vendorID" class="selectize1">
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group vendorName mt-2">
                                    <label for="vendorName">Name</label>
                                    <input type="text" name="vendorName" id="vendorName" class="form-control">
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
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="status">Payment Status</label>
                                    <select name="status" id="status1" class="form-control">
                                        <option disabled value="advanced">Paid in Advance</option>
                                        <option value="paid">Paid</option>
                                        <option disabled value="pending">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Create Purchase</button>
                            </div>
                </div>
            </form>
            </div>
            <div id="new" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Create New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                    </div>
                    <form id="productForm">
                        <div class="modal-body">
                            <div class="form-group mt-2">
                                <label for="name">Name</label>
                                <input type="text" name="name" required id="name" class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label for="catID">Category</label>
                               <select name="catID" id="catID" class="form-control">
                                @foreach ($cats as $cat)
                                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                                @endforeach
                               </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="pprice">Purchase Price</label>
                                <input type="number" step="any" required name="pprice"
                                    value="0" min="0" id="pprice"
                                    class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label for="price">Sale Price</label>
                                <input type="number" step="any" required name="price" value="0" min="0" id="price" class="form-control">
                            </div>
                           {{--  <div class="form-group mt-2">
                                <label for="discount">Discount</label>
                                <input type="number" step="any" name="discount" required value="0" min="0" id="discount" class="form-control">
                            </div> --}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

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
        var warehouses = @json($warehouses);
        var existingProducts = [];

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
                        html += '<td class="no-padding"><select name="warehouse[]" class="form-control text-center no-padding" id="warehouse_' + id + '">';
                            warehouses.forEach(function(warehouse) {
                                html += '<option value="' + warehouse.id + '" >' + warehouse.name + '</option>';
                            });
                        html += '</select></td>';
                        html += '<td class="no-padding"><input type="number" name="pprice[]" oninput="updateChanges(' + id + ')" step="any" value="'+product.pprice+'" min="1" class="form-control text-center no-padding" id="pprice_' + id + '"></td>';
                        html += '<td class="no-padding"><input type="number" name="price[]" step="any" value="'+product.price+'" min="0" class="form-control text-center no-padding" id="price_' + id + '"></td>';
                        html += '<td class="no-padding"><input type="number" name="qty[]" oninput="updateChanges(' + id + ')" min="0" step="any" value="0" class="form-control text-center no-padding" id="qty_' + id + '"></td>';
                        html += '<td class="no-padding"><input type="number" name="amount[]" min="0.1" readonly required step="any" value="1" class="form-control text-center no-padding" id="amount_' + id + '"></td>';
                        html += '<td class="no-padding"> <span class="btn btn-sm btn-danger" onclick="deleteRow('+id+')">X</span> </td>';
                        html += '<input type="hidden" name="id[]" value="' + id + '">';
                        html += '</tr>';
                        $("#products_list").prepend(html);
                        existingProducts.push(id);
                        updateChanges(id);
                    }
                }
            });
        }

        function updateChanges(id) {
            var qty = parseFloat($('#qty_' + id).val());
            var pprice = parseFloat($('#pprice_' + id).val());

            var amount = qty * pprice;
            $("#amount_"+id).val(amount.toFixed(2));
            updateTotal();
        }

        function updateTotal() {
            var total = 0;
            $("input[id^='amount_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                total += parseFloat(inputValue);
            });

            $("#totalAmount").html(total.toFixed(2));


            var discount = parseFloat($("#discount").val());
            var dc = parseFloat($("#dc").val());

            var net = (total + dc) - discount;

            $("#net").val(net.toFixed(2));
        }

        function deleteRow(id) {
            existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
            $('#row_'+id).remove();
            updateTotal();
        }

        $(document).ready(function() {
        $('#productForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            $.ajax({
                url: '{{url("/productAjax")}}', // Your GET URL
                method: 'GET',
                data: $(this).serialize(), // Serialize the form data
                success: function(response) {
                    $("#new").modal('hide');
                    if(response.response == "Exists")
                    {
                        Toastify({
                        text: "Product Already Exists",
                        className: "info",
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        style: {
                            background: "linear-gradient(to right, #FF5733, #E70000)",
                        }
                        }).showToast();
                    }
                    else
                    {
                        getSingleProduct(response.response);
                    }

                },
                error: function(xhr, status, error) {
                    console.log(error);
                    // Handle errors
                }
            });
        });
    });

    function checkAccount()
    {
        var id = $("#vendorID").find(":selected").val();
        if(id == 3)
        {
            $(".vendorName").removeClass("d-none");
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
            $(".vendorName").addClass("d-none");
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

    </script>
     @foreach ($products as $product)
     @if($product->isDefault == "Yes")
     <script>getSingleProduct({{$product->id}});</script>
     @endif
     @endforeach
@endsection
