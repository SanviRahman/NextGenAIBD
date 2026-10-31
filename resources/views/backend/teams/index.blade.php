@extends('backend.layouts.master')
@section('content')
    <!-- Container fluid -->
    <section class="p-4 container-fluid">
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Page header -->
                <div class="pb-3 mb-3 border-bottom d-md-flex align-items-center justify-content-between">
                    <div class="mb-2 mb-lg-0">
                        <h1 class="mb-0 h2 fw-bold">Teams </h1>
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('backend_Home_Index') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Teams
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <!-- button -->
                    <div>
                        <a href="#" class="me-5 text-body"><i class="fe fe-upload me-2"></i>Export</a>
                        <a href="#" class="me-5 text-body"><i class="fe fe-download me-2"></i>Import</a>
                        <a href="{{ route('backend_Teams_Create') }}" class="btn btn-primary me-2">Add Product</a>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Card -->
                <div class="card rounded-3">

                    <div class="card-header">
                        @if ($errors->any())
                            <div class="alert alert-danger" style="background: #DA1D25; color:#ffffff">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (Session::get('success'))
                            <div class="alert alert-success">
                                {{ session::get('success') }}
                            </div>
                        @endif
                        @if (Session::get('error'))
                            <div class="alert alert-danger">
                                {{ session::get('error') }}
                            </div>
                        @endif
                    </div>
                    <!-- Card Header -->
                    <div class="p-4 row">
                        <div class="col-md-7">
                            <a class="me-1 active1 btn btn-light" href="{{ route('backend_Teams_Index') }}">
                                All <span class="badge bg-primary">{{ $team }}</span>
                            </a>
                            <a class="me-1 btn btn-light" id="active-tab" href="{{ route('backend_Teams_Active') }}">
                                Active <span class="badge bg-success">{{ $active_teams }}</span>
                            </a>
                            <a class="me-1 btn btn-light" id="pending-tab" href="{{ route('backend_Teams_Pending') }}">
                                Pending <span class="badge bg-info">{{ $pending_teams }}</span>
                            </a>
                            <a class="me-1 btn btn-light" id="draft-tab" href="{{ route('backend_Teams_Drafts') }}">
                                Draft <span class="badge bg-danger">{{ $draft_teams }}</span>
                            </a>
                        </div>
                        <div class="col-md-5">
                            <!-- Form -->
                            <form action="{{ route('backend_Teams_Search') }}" method="GET"
                                class="d-flex align-items-right col-12 col-md-8 col-lg-12">
                                <span class="pt-2 position-absolute ps-3 search-icon">
                                    <i class="fe fe-search"></i>
                                </span>
                                <input type="search" name="search" class="form-control ps-6" placeholder="Filter Team">
                                <button class="input-group-text btn btn-primary" type="submit">Search</button>
                                <a href="{{ route('backend_Teams_Index') }}"
                                    class="input-group-text btn btn-dark">Reload!</a>
                            </form>
                        </div>
                    </div>
                    <!-- Tab -->
                    <div class="border-0 table-responsive">
                        <!-- Table -->
                        <table class="table mb-0 text-nowrap table-hover table-centered table-with-checkbox">
                            <!-- Table Head -->
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="checkAll">
                                            <label class="form-check-label" for="checkAll"></label>
                                        </div>
                                    </th>
                                    <th>ID</th>
                                    <th>Teams</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table body -->
                                @foreach ($teams as $item)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="productOne">
                                                <label class="form-check-label" for="productOne"></label>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $item->id }}
                                        </td>
                                        <td>
                                            <a href="#" class="text-inherit">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <img src="{{ asset('/media/team') . '/' . $item->image }}"
                                                            alt="1" class="rounded img-4by3-md">
                                                    </div>
                                                    <div class="ms-3">
                                                        <h5 class="mb-0 text-primary-hover">
                                                            {{ $item->name }}
                                                        </h5>

                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            @if ($item->status == 1)
                                                <span class="badge bg-success badge-dot me-1"></span> Active
                                            @elseif ($item->status == 2)
                                                <span class="badge bg-info badge-dot me-1"></span> Pending
                                            @elseif($item->status == 3)
                                                <span class="badge bg-warning badge-dot me-1"></span> Draft
                                            @else
                                                null
                                            @endif
                                            <span class="dropdown dropstart">
                                                <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                    role="button" id="productDropdown1" data-bs-toggle="dropdown"
                                                    data-bs-offset="-20,20" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                </a>
                                                <span class="dropdown-menu" aria-labelledby="productDropdown1"
                                                    style="">
                                                    <span class="dropdown-header">Status</span>
                                                    <a class="dropdown-item"
                                                        href="{{ route('backend_Teams_Status', ['team' => $item->id, 'status' => 1]) }}">
                                                        <i class="fe fe-edit dropdown-item-icon"></i> Active
                                                    </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('backend_Teams_Status', ['team' => $item->id, 'status' => 2]) }}">
                                                        <i class="fe fe-edit dropdown-item-icon"></i> Pending
                                                    </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('backend_Teams_Status', ['team' => $item->id, 'status' => 3]) }}">
                                                        <i class="fe fe-edit dropdown-item-icon"></i> Draft
                                                    </a>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="dropdown dropstart">
                                                <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                    role="button" id="productDropdown1" data-bs-toggle="dropdown"
                                                    data-bs-offset="-20,20" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                </a>
                                                <span class="dropdown-menu" aria-labelledby="productDropdown1"
                                                    style="">
                                                    <span class="dropdown-header">Settings</span>
                                                    <a class="dropdown-item"
                                                        href="{{ route('backend_Teams_Edit', $item->id) }}">
                                                        <i class="fe fe-edit dropdown-item-icon"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item" href="#"><i
                                                            class="fe fe-mail dropdown-item-icon"></i>Invite</a>

                                                    <a class="dropdown-item" target="blank"
                                                        href="{{ route('backend_Teams_Destory', $item->id) }}"
                                                        onclick="return confirm('Are You Sure ?')"
                                                        onclick="event.preventDefault();
                                                                    document.getElementById('delete-form'{{ $item->id }}).submit();">
                                                        <i class="fe fe-trash dropdown-item-icon"></i>
                                                        Delete
                                                    </a>

                                                    <form id="delete-form{{ $item->id }}"
                                                        action="{{ route('backend_Teams_Destory', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                </span>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Card Footer -->
                    <div class="card-footer d-md-flex justify-content-between align-items-center">
                        <div>
                            {{-- <span>Showing products 1 to 9 of 12</span> --}}
                        </div>
                        <nav>
                            <ul class="mt-2 mb-0 pagination">
                                <li class="page-item">
                                    {{ $teams->links('vendor.pagination.bootstrap-4') }}
                                </li>
                                {{-- {{ $teams->links() }} --}}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
