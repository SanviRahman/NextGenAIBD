@extends('backend.layouts.master')
@section('content')
    <!-- Container fluid -->
    <section class="p-4 container-fluid">
        <div class="row">
            <!-- Page header -->
            <div class="col-lg-12 col-md-12 col-12">
                <div class="pb-3 mb-3 border-bottom d-md-flex align-items-center justify-content-between">
                    <div class="mb-3 mb-md-0">
                        <h1 class="mb-1 h2 fw-bold">Update Course Apply</h1>
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('backend_Home_Index') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('backend_ApplyCourse_Index') }}">Course
                                        Apply</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Update Post
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('backend_ApplyCourse_Index') }}" class="btn btn-outline-secondary">
                            Back to All Course Apply
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('backend_ApplyCourse_Update', $courseApply->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-xl-9 col-lg-8 col-md-12 col-12">
                    <!-- Card -->
                    <div class="mb-4 border-0 card">
                        <!-- Card header -->
                        <div class="card-header">
                            <h4 class="mb-0">Create Course Apply</h4>
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
                        <!-- Card body -->

                        <!-- card body -->
                        <div class="card-body">

                            <!-- row -->
                            <div class="row gx-3">
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="image">
                                        Image <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="image" class="form-control" id="image">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="name">
                                        Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') ?? $courseApply->name }}"
                                        class="form-control" placeholder="Enter name" id="name">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="address">
                                        Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="address"
                                        value="{{ old('address') ?? $courseApply->address }}" class="form-control"
                                        placeholder="" id="address">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="number">
                                        Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="number" value="{{ old('number') ?? $courseApply->number }}"
                                        class="form-control" placeholder="" id="number">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="email">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email') ?? $courseApply->email }}"
                                        class="form-control" placeholder="" id="email">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="password">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="password"
                                        value="{{ old('password') ?? $courseApply->password }}" class="form-control"
                                        placeholder="" id="password">
                                </div>
                                <!-- Educational Institute -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="educational_institute">
                                        Educational Institute <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="educational_institute"
                                        value="{{ old('educational_institute') ?? $courseApply->educational_institute }}"
                                        class="form-control" id="educational_institute">
                                </div>

                                <!-- Course -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="course">
                                        Course <span class="text-danger">*</span>
                                    </label>

                                    <select name="course" class="form-select text-dark" id="course">
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->title }}"
                                                @if ($course->title == $courseApply->course) selected @endif>
                                                {{ $course->title }} - ({{ $course->price }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <br>
                                <!-- Course Fee -->
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="course_fee">
                                        Course Fee <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="course_fee"
                                        value="{{ old('course_fee') ?? $courseApply->course_fee }}" class="form-control"
                                        id="course_fee">
                                </div>

                                <!-- Course Fee Paid -->
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="course_fee_paid">
                                        Course Fee Paid <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="course_fee_paid"
                                        value="{{ old('course_fee_paid') ?? $courseApply->course_fee_paid }}"
                                        class="form-control" id="course_fee_paid">
                                </div>

                                <!-- Course Fee Unpaid -->
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="course_fee_unpaid">
                                        Course Fee Unpaid <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="course_fee_unpaid"
                                        value="{{ old('course_fee_unpaid') ?? $courseApply->course_fee_unpaid }}"
                                        class="form-control" id="course_fee_unpaid">
                                </div>

                                <!-- Description -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="description">
                                        Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="description" class="form-control" rows="4" placeholder="Enter Description" id="description">{{ old('description') ?? $courseApply->description }}</textarea>
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

                        <!-- Card Header -->
                        <div class="card-body">
                            <!-- input -->
                            <img src="{{ asset('/media/courseApply') . '/' . $courseApply->image }}" alt="1"
                                class="rounded img-fluid">
                        </div>

                        <!-- Card Header -->
                        <div class="card-footer">
                            <!-- Input -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label" for="status_class">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select name="status" class="form-select" id="status_class">
                                    <option value="1">Active</option>
                                    <option value="2">Pending</option>
                                    <option value="3">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <!-- List group -->
                        <div class="d-flex justify-content-end">
                            <!-- buttons -->
                            <a href="#" class="btn btn-outline-primary me-2">Cancel</a>
                            <button class="btn btn-outline-danger me-2" type="reset">Reset</button>
                            <button class="btn btn-primary" type="submit">Publish</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </section>

@endsection
