@extends('layout.app')
@section('content')
<div class="col-12">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card mt-3">
        <div class="card-header">
            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab" aria-selected="true" >
                        <i class="fas fa-home"></i> User Details
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link " data-bs-toggle="tab" href="#changePassword" role="tab" aria-selected="false" tabindex="-1">
                        <i class="far fa-user"></i> Change Password
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body p-4">
            <div class="tab-content">
                <div class="tab-pane active show" id="personalDetails" role="tabpanel">
                    <form method="post" action="{{ route('updateProfile') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="firstnameInput" class="form-label">User Name</label>
                                    <input type="text" class="form-control" id="firstnameInput" name="name" placeholder="Enter User Name" value="{{auth()->user()->name}}">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="emailInput" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="emailInput" name="email" placeholder="Enter your email" value="{{auth()->user()->email}}">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="designationInput" class="form-label">Role</label>
                                    <input type="text" class="form-control" disabled id="designationInput" placeholder="Role" value="{{auth()->user()->role == 1 ? "Admin" : "Operator"}}">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary">Updates</button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
                <!--end tab-pane-->
                <div class="tab-pane" id="changePassword" role="tabpanel">
                    <form method="post" action="{{route('changePassword')}}">
                        @csrf
                        <div class="row g-2">
                            <div class="col-12">
                                <div>
                                    <label for="oldpasswordInput" class="form-label">Old Password*</label>
                                    <input type="password" class="form-control" name="old_password" id="oldpasswordInput" placeholder="Enter current password">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-12">
                                <div>
                                    <label for="newpasswordInput" class="form-label">New Password*</label>
                                    <input type="password" class="form-control" name="new_password" id="newpasswordInput" placeholder="Enter new password">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-12">
                                <div>
                                    <label for="confirmpasswordInput" class="form-label">Confirm Password*</label>
                                    <input type="password" class="form-control" name="new_password_confirmation" id="confirmpasswordInput" placeholder="Confirm password">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success">Change Password</button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection


