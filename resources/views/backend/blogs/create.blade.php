@extends('backend.layouts.master')
@section('content')
    <!-- Container fluid -->
    <section class="p-4 container-fluid">
        <div class="row">
            <!-- Page header -->
            <div class="col-lg-12 col-md-12 col-12">
                <div class="pb-3 mb-3 border-bottom d-md-flex align-items-center justify-content-between">
                    <div class="mb-3 mb-md-0">
                        <h1 class="mb-1 h2 fw-bold">Add New Blog</h1>
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('backend_Home_Index') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('backend_Blogs_Index') }}">Blog</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Add New Post
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('backend_Blogs_Index') }}" class="btn btn-outline-secondary">
                            Back to All Blog
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('backend_Blogs_Store') }}" method="POST" enctype="multipart/form-data">
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
                        </div>
                        <!-- Card body -->
                    </div>

                    <div class="mb-4 card">
                        <!-- card body -->
                        <div class="card-body">
                            <h4 class="mb-4">Blog Overview</h4>

                            <!-- row -->
                            <div class="row gx-3">
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="title">
                                        Blog Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title" value="{{ old('title') }}" class="form-control"
                                        placeholder="Enter Blog Title" id="title">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="image">
                                        Blog Image <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="image" class="form-control" id="image">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="sort_description">
                                        Blog Sort Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="sort_description" value="" class="form-control" id="sort_description" rows="3"
                                        placeholder="Write a sort_description">
                                        {{ old('sort_description') }}
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
                                    <label for="description" class="form-label">Blog Description <small
                                            class="text-muted">(Optional)</small></label>
                                    <textarea name="description" value="" class="form-control textarea" id="description" rows="3"
                                        placeholder="Write a Description">
                                        {{ old('description') }}
                                    </textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <!-- buttons -->
                        <a href="#" class="btn btn-outline-primary me-2">Cancel</a>
                        <button class="btn btn-primary" type="submit">Publish Data</button>
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
                            <!-- input -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label" for="categories">
                                    Blog Categories <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="categories" value="{{ old('categories') }}" class="form-control"
                                    placeholder="Enter Categories" id="categories">
                            </div>
                            <!-- Input -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label" for="status_class">
                                    Blog Status <span class="text-danger">*</span>
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
                            <button class="btn btn-primary" type="submit">Publish Data</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </section>

@endsection
