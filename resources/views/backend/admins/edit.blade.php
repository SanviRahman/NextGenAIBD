@extends('backend.layouts.master')
@section('content')
    <!-- Container fluid -->
    <section class="p-4 container-fluid">
        <div class="row">
            <!-- Page header -->
            <div class="col-lg-12 col-md-12 col-12">
                <div class="pb-3 mb-3 border-bottom d-md-flex align-items-center justify-content-between">
                    <div class="mb-3 mb-md-0">
                        <h1 class="mb-1 h2 fw-bold">Update New Admin</h1>
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('backend_Home_Index') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('backend_Admins_Index') }}">Admin</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Update New
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('backend_Admins_Index') }}" class="btn btn-outline-secondary">
                            Back to All Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('backend_Admins_Update', $admin->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-xl-9 col-lg-8 col-md-12 col-12">
                    <!-- Card -->
                    <div class="mb-4 border-0 card">
                        <!-- Card header -->
                        <div class="card-header">
                            <h4 class="mb-0">Update Admin Deteils</h4>
                        </div>
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
                            @if (Session::get('error'))
                                <div class="alert alert-danger">
                                    {{ session::get('error') }}
                                </div>
                            @endif
                            @if (Session::get('success'))
                                <div class="alert alert-success">
                                    {{ session::get('success') }}
                                </div>
                            @endif
                        </div>
                        <!-- Card body -->
                    </div>

                    <div class="mb-4 card">
                        <!-- card body -->
                        <div class="card-body">
                            <!-- row -->
                            <div class="row gx-3">
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="name">
                                        Admin Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') ?? $admin->name }}"
                                        class="form-control" placeholder="" id="name">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="username">
                                        Admin Username <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="username" value="{{ old('username') ?? $admin->username }}"
                                        class="form-control" placeholder="" id="username">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="type">
                                        Admin Type <span class="text-danger">*</span>
                                    </label>
                                    <select name="type" class="form-select" id="type">
                                        <option value="admin">Admin</option>
                                        <option value="editor">Editor</option>
                                        <option value="superadmin">Superadmin</option>
                                        <option value="visitor">Visitor</option>
                                    </select>
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="email">
                                        Admin Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="email" value="{{ old('email') ?? $admin->email }}"
                                        class="form-control" placeholder="" id="email">
                                </div>
                                <div class="mb-3 col-lg-6">
                                    <label for="countrySelect" class="form-label">country</label>
                                    <select name="country" class="form-select" id="countrySelect">
                                        <option disabled value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->country_name }}"
                                                @if ($admin->country == $country->country_name) selected @endif
                                                data-phonecode="{{ $country->phonecode }}">
                                                {{ $country->country_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                    <script>
                                        $(document).ready(function() {
                                            $('#countrySelect').change(function() {
                                                var selectedPhoneCode = $('option:selected', this).data('phonecode');
                                                $('#phoneCodeDisplay').text(selectedPhoneCode);
                                                $('input[name="phonecode"]').val(selectedPhoneCode);
                                            });
                                        });
                                    </script>
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-lg-6">
                                    <label for="mobile" class="form-label">Admin Mobile Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="phoneCodeDisplay">{{ $admin->phonecode }}</span>
                                        <input name="phonecode" type="hidden" value="{{ $admin->phonecode }}">
                                        <input type="number" id="mobile" class="form-control" name="mobile"
                                            placeholder="" value="{{ $admin->mobile }}" required="">
                                    </div>
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="password">
                                        Admin password <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="password"
                                        value="{{ old('password') ?? $admin->password_confirmation }}"
                                        class="form-control" placeholder="" id="password">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="avatar">
                                        Admin Avatar <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="avatar" class="form-control" placeholder=""
                                        id="avatar">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-12 col-12">
                    <!-- Card -->
                    <div class="mt-4 mb-4 card mt-lg-0">
                        <!-- Card Header -->
                        <div class="card-header d-lg-flex">
                            <h4 class="mb-0">Others Info</h4>
                        </div>
                        <div class="card-header d-lg-flex"></div>
                    </div>
                    <div class="mb-4 card">
                        <!-- Card Header -->
                        <div class="card-header">
                            <!-- Input -->
                            <img src="{{ asset('/media/admin') . '/' . $admin->avatar }}" alt="1"
                                class="rounded img-fluid">
                        </div>
                    </div>
                    <!-- Card  -->
                    <div class="mb-4 card">
                        <!-- Card Header -->
                        <div class="card-header">
                            <!-- Input -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label" for="status_class">
                                    Admin Status <span class="text-danger">*</span>
                                </label>
                                <select name="status" class="form-select" id="status_class">
                                    <option value="1">Active</option>
                                    <option value="2">Pending</option>
                                    <option value="0">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Card  -->
                    <div class="mb-4 card">
                        <!-- Card header -->
                        <div class="card-header d-lg-flex">
                            <h4 class="mb-0">Now History</h4>
                        </div>
                        <!-- List group -->
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        {{ \Carbon\Carbon::now()->format('M d, h:i A') }}
                                    </h5>
                                </div>
                                <div>
                                    <span class="badge bg-success badge-pill">Published</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="">
                        <!-- List group -->
                        <div class="d-flex justify-content-end">
                            <!-- buttons -->
                            <a href="#" class="btn btn-outline-primary me-2">Cancel</a>
                            <button class="btn btn-outline-danger me-2" type="reset">Reset</button>
                            <button class="btn btn-primary" type="submit">Publish Data</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </section>

@endsection
