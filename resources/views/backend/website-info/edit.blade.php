@extends('backend.layouts.master')
@section('content')
    <!-- Container fluid -->
    <section class="p-4 container-fluid">
        <div class="row">
            <!-- Page header -->
            <div class="col-lg-12 col-md-12 col-12">
                <div class="pb-3 mb-3 border-bottom d-md-flex align-items-center justify-content-between">
                    <div class="mb-3 mb-md-0">
                        <h1 class="mb-1 h2 fw-bold">Update Website InFo</h1>
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('backend_Home_Index') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('backend_Website_Info_Index') }}">Website
                                        InFo</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Update Post
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('backend_Website_Info_Index') }}" class="btn btn-outline-secondary">
                            Back to All Website InFo
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('backend_Website_Info_Update', $websiteinfo->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-xl-9 col-lg-8 col-md-12 col-12">
                    <!-- Card -->
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

                    <div class="mb-4 card">
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
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="address">
                                        Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="address"
                                        value="{{ old('address') ?? $websiteinfo->address }}" class="form-control"
                                        id="address">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="email">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="email" value="{{ old('email') ?? $websiteinfo->email }}"
                                        class="form-control" id="email">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="number">
                                        Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="number" value="{{ old('number') ?? $websiteinfo->number }}"
                                        class="form-control" id="number">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="wattsup_number">
                                        Wattsup Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="wattsup_number"
                                        value="{{ old('wattsup_number') ?? $websiteinfo->wattsup_number }}"
                                        class="form-control" id="wattsup_number">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="facebook">
                                        Facebook
                                    </label>
                                    <input type="url" name="facebook"
                                        value="{{ old('facebook') ?? $websiteinfo->facebook }}" class="form-control"
                                        id="facebook">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="instagram">
                                        Instagram
                                    </label>
                                    <input type="url" name="instagram"
                                        value="{{ old('instagram') ?? $websiteinfo->instagram }}" class="form-control"
                                        id="instagram">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="twitter">
                                        Twitter
                                    </label>
                                    <input type="url" name="twitter"
                                        value="{{ old('twitter') ?? $websiteinfo->twitter }}" class="form-control"
                                        id="twitter">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="linkdin">
                                        Linkdin
                                    </label>
                                    <input type="url" name="linkdin"
                                        value="{{ old('linkdin') ?? $websiteinfo->linkdin }}" class="form-control"
                                        id="linkdin">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="radit">
                                        Radit
                                    </label>
                                    <input type="url" name="radit"
                                        value="{{ old('radit') ?? $websiteinfo->radit }}" class="form-control"
                                        id="radit">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="tiktok">
                                        Tiktok
                                    </label>
                                    <input type="url" name="tiktok"
                                        value="{{ old('tiktok') ?? $websiteinfo->tiktok }}" class="form-control"
                                        id="tiktok">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="pintarest">
                                        Pintarest
                                    </label>
                                    <input type="url" name="pintarest"
                                        value="{{ old('pintarest') ?? $websiteinfo->pintarest }}" class="form-control"
                                        id="pintarest">
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
                                    {{-- <script src="{{ asset('backend') }}/assets/js/no-api-key5.10.7-133_tinymce.min.js"></script>
                                    <script>
                                        tinymce.init({
                                            selector: 'textarea',
                                            plugins: 'table lists link',
                                            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | table | link',
                                        });

                                        function submitForm() {
                                            var editorContent = tinymce.get('myTextarea').getContent();
                                            // Send 'editorContent' to your server using AJAX or form submission
                                            alert('Content to be submitted:\n\n' + editorContent);
                                        }
                                    </script> --}}
                                    <label for="sort_descroption" class="form-label"> Description <small
                                            class="text-muted">(Optional)</small></label>
                                    <textarea name="sort_descroption" value="" class="form-control" id="sort_descroption" rows="3"
                                        placeholder="Write a Description">
                                        {{ old('sort_descroption') ?? $websiteinfo->sort_descroption }}
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
                    <!-- Card  -->
                    <div class="mb-4 card">
                        <!-- Card Header -->
                        <div class="card-body">
                            <!-- input -->
                            <img src="{{ asset('/media/banner') . '/' . $websiteinfo->image }}" alt="1"
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
                            <button class="btn btn-primary" type="submit">Publish Data</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
