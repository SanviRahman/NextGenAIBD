@extends('backend.layouts.master')
@section('content')
    <!-- Container fluid -->
    <section class="p-4 container-fluid">
        <div class="row">
            <!-- Page header -->
            <div class="col-lg-12 col-md-12 col-12">
                <div class="pb-3 mb-3 border-bottom d-md-flex align-items-center justify-content-between">
                    <div class="mb-3 mb-md-0">
                        <h1 class="mb-1 h2 fw-bold">Update Membership</h1>
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('backend_Home_Index') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('backend_Membership_Index') }}">Membership</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Update Post
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('backend_Membership_Index') }}" class="btn btn-outline-secondary">
                            Back to All Membership
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('backend_Membership_Update', $membership->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-xl-9 col-lg-8 col-md-12 col-12">
                    <!-- Card -->
                    <div class="mb-4 border-0 card">
                        <!-- Card header -->
                        <div class="card-header">
                            <h4 class="mb-0">Create Post</h4>
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
                    </div>

                    <div class="mb-4 card">
                        <!-- card body -->
                        <div class="card-body">
                            <h4 class="mb-4">Membership Overview</h4>

                            <!-- row -->
                            <div class="row gx-3">
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="image">
                                        Membership Image <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="image" class="form-control"
                                        placeholder="Enter Membership Image" id="image">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="title">
                                        Membership Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title" value="{{ old('title') ?? $membership->title }}"
                                        class="form-control" placeholder="Enter Membership Title" id="title">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="number">
                                        Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="number" value="{{ old('number') ?? $membership->number }}"
                                        class="form-control" placeholder="" id="number">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="website_url">
                                        Website URL <span class="text-danger">*</span>
                                    </label>
                                    <input type="url" name="website_url"
                                        value="{{ old('website_url') ?? $membership->website_url }}" class="form-control"
                                        placeholder="" id="website_url">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="page_url">
                                        Page URL <span class="text-danger">*</span>
                                    </label>
                                    <input type="url" name="page_url"
                                        value="{{ old('page_url') ?? $membership->page_url }}" class="form-control"
                                        placeholder="" id="page_url">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="email">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email') ?? $membership->email }}"
                                        class="form-control" placeholder="" id="email">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="password">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="password"
                                        value="{{ old('password') ?? $membership->password }}" class="form-control"
                                        placeholder="" id="password">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="total_costing">
                                        Total Costing <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="total_costing"
                                        value="{{ old('total_costing') ?? $membership->total_costing }}"
                                        class="form-control" placeholder="" id="total_costing">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="total_service">
                                        Total Service <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="total_service"
                                        value="{{ old('total_service') ?? $membership->total_service }}"
                                        class="form-control" placeholder="" id="total_service">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="video_url">
                                        Membership video url <span class="text-danger">*</span>
                                    </label>
                                    <input type="url" name="video_url"
                                        value="{{ old('video_url') ?? $membership->video_url }}" class="form-control"
                                        placeholder="" id="video_url">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="address">
                                        Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="address"
                                        value="{{ old('address') ?? $membership->address }}" class="form-control"
                                        placeholder="" id="address">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="image">
                                        Membership Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="sort_description" id="" cols="" rows="4" class="form-control">
                                        {{ old('sort_description') ?? $membership->sort_description }}
                                    </textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- card -->
                    <div class="mb-4 card">
                        <!-- card body -->
                        <div class="card-body">
                            <!-- row -->
                            <div class="row gx-3">
                                <div class="col-md-12">
                                    {{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script> --}}
                                    <script src="{{ asset('backend') }}/assets/js/no-api-key5.10.7-133_tinymce.min.js"></script>
                                    <script>
                                        tinymce.init({
                                            selector: '.textarea',
                                            plugins: 'table lists link',
                                            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | table | link',
                                        });

                                        function submitForm() {
                                            var editorContent = tinymce.get('myTextarea').getContent();
                                            // Send 'editorContent' to your server using AJAX or form submission
                                            alert('Content to be submitted:\n\n' + editorContent);
                                        }
                                    </script>
                                    <label for="description" class="form-label">Membership Description <small
                                            class="text-muted">(Optional)</small></label>
                                    <textarea name="description" value="" class="form-control textarea" id="description" rows="20"
                                        placeholder="Write a Description">
                                        {{ old('description') ?? $membership->description }}
                                    </textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- <div class="d-flex justify-content-end">
                        <!-- buttons -->
                        <a href="#" class="btn btn-outline-primary me-2">Cancel</a>
                        <button class="btn btn-primary" type="submit">Publish Data</button>
                    </div> --}}
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
                    <!-- Card  -->
                    <div class="mb-4 card">
                        <!-- Card Header -->
                        <div class="card-body">
                            <!-- input -->
                            <img src="{{ asset('/media/membership') . '/' . $membership->image }}" alt="1"
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
                                    Membership Status <span class="text-danger">*</span>
                                </label>
                                <select name="status" class="form-select" id="status_class">
                                    <option value="1">Active</option>
                                    <option value="2">Pending</option>
                                    <option value="3">Draft</option>
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
                            <button class="btn btn-primary" type="submit">Publish</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </section>

@endsection
