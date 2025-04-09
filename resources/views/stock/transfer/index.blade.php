@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Stock Transfer</h3>
                    <a onclick="newWindow('{{ route('stockTransfer.create') }}')" type="button" class="btn btn-primary ">Create New</a>
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

                    <table class="table" id="buttons-datatables">
                        <thead>
                            <th>#</th>
                            <th>From (Warehouse)</th>
                            <th>To (Warehouse)</th>
                            <th>Date</th>
                            <th>Notes</th>
                            <th>Transfered By</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($transfers as $key => $transfer)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transfer->from->name }}</td>
                                    <td>{{ $transfer->to->name }}</td>
                                    <td>{{ date('d M Y', strtotime($transfer->date)) }}</td>
                                    <td>{{ $transfer->notes }}</td>
                                    <td>{{ $transfer->user->name }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button class="dropdown-item" onclick="newWindow('{{route('stockTransfer.show', $transfer->id)}}')"
                                                        onclick=""><i
                                                            class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                        View
                                                    </button>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="{{route('stockTransfer.delete', $transfer->id)}}">
                                                        <i class="ri-delete-bin-2-fill align-bottom me-2 text-danger"></i>
                                                        Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Default Modals -->
@endsection

