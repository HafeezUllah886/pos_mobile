<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{route('dashboard')}}" class="logo logo-dark">
            <span class="logo-sm">
                <h3 class="text-white">DC</h3>
            </span>
            <span class="logo-lg">
                <h3 class="text-white mt-3">DELUXE CABLE</h3>
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{route('dashboard')}}" class="logo logo-light">
            <span class="logo-sm">
                <h3 class="text-white">DC</h3>
            </span>
            <span class="logo-lg">
                <h3 class="text-white mt-3">DELUXE CABLE</h3>
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                    alt="Header Avatar">
                <span class="text-start">
                    <span class="d-block fw-medium sidebar-user-name-text">{{ auth()->user()->name }}</span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text"><i
                            class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span
                            class="align-middle">Online</span></span>
                </span>
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <h6 class="dropdown-header">Welcome {{ auth()->user()->name }}!</h6>
            <a class="dropdown-item" href="{{ route('profile') }}"><i
                    class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Profile</span></a>
            <a class="dropdown-item" href="auth-logout-basic.html"><i
                    class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle"
                    data-key="t-logout">Logout</span></a>
        </div>
    </div>
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('dashboard') }}">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li> <!-- end Dashboard Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sales" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-shopping-cart-fill"></i><span data-key="t-apps">Sale</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sales">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a onclick="newWindow('{{ route('sale.create') }}')" class="nav-link"
                                    data-key="t-chat">Create Sale</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('sale.index') }}" class="nav-link" data-key="t-chat"> Sales History</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#quot" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-shopping-cart-fill"></i><span data-key="t-apps">Quotation</span>
                    </a>
                    <div class="collapse menu-dropdown" id="quot">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a onclick="newWindow('{{ route('quotation.create') }}')" class="nav-link"
                                    data-key="t-chat">Create Quotation</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('quotation.index') }}" class="nav-link" data-key="t-chat"> Quotation History</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#purchase" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-shopping-cart-line"></i><span data-key="t-apps">Purchase</span>
                    </a>
                    <div class="collapse menu-dropdown" id="purchase">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a onclick="newWindow('{{ route('purchase.create') }}')" class="nav-link"
                                    data-key="t-chat">Create Purchase</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('purchase.index') }}" class="nav-link" data-key="t-chat"> Purchase
                                    History </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#stock" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-stack-line"></i><span data-key="t-apps">Stocks</span>
                    </a>
                    <div class="collapse menu-dropdown" id="stock">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('product_stock.index', ['zero' => 'not_allowed']) }}" class="nav-link" data-key="t-chat">Stock</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('product_stock.index', ['zero' => 'allowed']) }}" class="nav-link" data-key="t-chat">Stock with Zero</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('product_stock.index', ['zero' => 'above_zero']) }}" class="nav-link" data-key="t-chat">Stock Above Zero</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('product_stock.index', ['zero' => 'below_zero']) }}" class="nav-link" data-key="t-chat">Stock Below Zero</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('stockTransfer.index') }}" class="nav-link" data-key="t-chat">Stock Transfer</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#products" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-apps-2-line"></i> <span data-key="t-apps">Products</span>
                    </a>
                    <div class="collapse menu-dropdown" id="products">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('product.index') }}" class="nav-link" data-key="t-chat">Products
                                    List </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('categories.index') }}" class="nav-link" data-key="t-chat">Products
                                    Categories </a>
                            </li>
                           {{--  <li class="nav-item">
                                <a href="{{ route('units.index') }}" class="nav-link" data-key="t-chat"> Units </a>
                            </li> --}}
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarFinance" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarFinance">
                        <i class="ri-file-list-3-line"></i> <span data-key="t-forms">Finance</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarFinance">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('account.create') }}" class="nav-link"
                                    data-key="t-basic-elements">Create Account</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('accountsList', 'Business') }}" class="nav-link"
                                    data-key="t-form-select">
                                    Business Accounts </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('accountsList', 'Customer') }}" class="nav-link"
                                    data-key="t-checkboxs-radios">Customer Accounts</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('accountsList', 'Vendor') }}" class="nav-link"
                                    data-key="t-pickers">
                                    Vendor Accounts </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('receivings.index') }}" class="nav-link"
                                    data-key="t-input-masks">Payment Receiving</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('deposit_withdraw.index') }}" class="nav-link"
                                    data-key="t-input-masks">Deposit / Withdraw</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('transfers.index') }}" class="nav-link"
                                    data-key="t-advanced">Transfer</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('expenses.index') }}" class="nav-link" data-key="t-range-slider">
                                    Expenses</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarReports" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarReports">
                        <i class="ri-file-list-3-line"></i> <span data-key="t-forms">Reports</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarReports">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('reportProfit') }}" class="nav-link"
                                    data-key="t-basic-elements">Profit / Loss</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reportCashbook') }}" class="nav-link"
                                    data-key="t-basic-elements">Daily Cash Book</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reportLedger') }}" class="nav-link"
                                    data-key="t-basic-elements">Ledger Report</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('todos.index')}}">
                        <i class="ri-apps-2-line"></i> <span data-key="t-apps">Todos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#warehouses" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-apps-2-line"></i> <span data-key="t-apps">Warehouses</span>
                    </a>
                    <div class="collapse menu-dropdown" id="warehouses">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('warehouses.index') }}" class="nav-link" data-key="t-chat">Warehouses
                                    List </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
