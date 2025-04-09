@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6"><h3> Create Sale </h3></div>
                                <div class="col-6 d-flex flex-row-reverse">
                                    <button onclick="window.close()" class="btn btn-danger">Close</button>
                                    <button type="button" class="btn btn-primary" style="margin-right:10px;" data-bs-toggle="modal" data-bs-target="#new">Add Product</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">

                    <form action="{{ route('sale.store') }}" method="post">
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
                                        <th width="20%">Product</th>
                                        <th class="text-center">Warehouse</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Amount</th>
                                        <th></th>
                                    </thead>
                                    <tbody id="products_list"></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">Total</th>
                                            <th class="text-end" id="totalAmount">0.00</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-3"></div>
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
                                    <label for="customer">Customer</label>
                                    <select name="customerID" id="customerID" class="selectize1">
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group customerName mt-2">
                                    <label for="customerName">Name</label>
                                    <input type="text" name="customerName" id="customerName" class="form-control">
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
                                        <option disabled value="partial">Partial Payment</option>
                                    </select>
                                </div>
                                <div class="form-group d-none paid mt-2">
                                    <label for="paid">Paid Amount</label>
                                    <input type="number" name="paid" id="paid" value="0" class="form-control">
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Create Sale</button>
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

@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .no-padding {
            padding: 5px 5px !important;
        }
        .ui-autocomplete {
    font-family: 'Noto Sans', Arial, sans-serif;
}
    </style>



    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
                url: "{{ url('sales/getproduct/') }}/" + id,
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
                        html += '<td class="no-padding"><input type="number" name="qty[]" oninput="updateChanges(' + id +')" min="0" step="any" value="0" class="form-control text-center" id="qty_' + id + '"></div></td>';
                        html += '<td class="no-padding"><input type="number" name="price[]" oninput="updateChanges(' + id + ')" step="any" value="'+product.price+'" min="1" class="form-control text-center" id="price_' + id + '"></td>';
                        html += '<td class="no-padding"><input type="number" name="amount[]" readonly step="any" value="0.00" min="0" class="form-control text-center" id="amount_' + id + '"></td>';
                        html += '<td> <span class="btn btn-sm btn-danger" onclick="deleteRow('+id+')">X</span> </td>';
                        html += '<input type="hidden" name="id[]" value="' + id + '">';
                        html += '<input type="hidden" id="stock_'+id+'" value="' + product.stock + '">';
                        html += '</tr>';
                        $("#products_list").prepend(html);
                        updateChanges(id);
                        existingProducts.push(id);
                    }
                }
            });
        }

        function updateChanges(id) {
            var qty = $('#qty_' + id).val();
            var price = $('#price_' + id).val();

            var amount = price * qty;

            $("#amount_"+id).val(amount);

            updateTotal();
        }

        function updateTotal() {
            var totalAmount = 0;
            $("input[id^='amount_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                totalAmount += parseFloat(inputValue);
            });
            $("#totalAmount").html(totalAmount.toFixed(2));

            var discount = parseFloat($("#discount").val());
            var dc = parseFloat($("#dc").val());

            var net = (totalAmount + dc) - discount;

            $("#net").val(net);

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
        var id = $("#customerID").find(":selected").val();
        if(id == 2)
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

    $("#customerID").on("change", function(){
        checkAccount();
    });

    $(document).ready(function() {
    $('#search').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: '/search_products',  // Backend API endpoint
                type: 'GET',
                dataType: 'json',
                data: {
                    q: request.term  // The search query sent to the server
                },
                success: function(data) {
                    // Map response to format [{ label: "Product Name", value: "Product ID" }]
                    response($.map(data.products, function(item) {
                        return {
                            label: item.text,  // This will display Urdu or English product name
                            value: item.value  // Unique product ID (can also return the name if you want)
                        };
                    }));
                }
            });
        },
        minLength: 2,  // Start searching after typing 2 characters
        select: function(event, ui) {
            const productId = ui.item.value;

        // Call another function and pass the product ID
        getSingleProduct(productId);

        // Clear the search input after selection
        $('#search').val('');

        return false;
        }
    });
});

    </script>
    @foreach ($products as $product)
    @if($product->isDefault == "Yes")
    <script>getSingleProduct({{$product->id}});</script>
    @endif
    @endforeach
@endsection
