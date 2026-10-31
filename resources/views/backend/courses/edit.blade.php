@extends('backend.layouts.master')
@section('content')
    <!-- Container fluid -->
    <section class="p-4 container-fluid">
        <div class="row">
            <!-- Page header -->
            <div class="col-lg-12 col-md-12 col-12">
                <div class="pb-3 mb-3 border-bottom d-md-flex align-items-cjustify-content-between">
                    <div class="mb-3 mb-md-0">
                        <h1 class="mb-1 h2 fw-bold">Update Course</h1>
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('backend_Home_Index') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('backend_Courses_Index') }}">COURSE</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Update Post
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('backend_Courses_Index') }}" class="btn btn-outline-secondary">
                            Back to All Course
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('backend_Courses_Update', $course->id) }}" method="POST" enctype="multipart/form-data">
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
                            <h4 class="mb-4">Course Overview</h4>

                            <!-- row -->
                            <div class="row gx-3">
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="title">
                                        Course Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title" value="{{ old('title') ?? $course->title }}"
                                        class="form-control" placeholder="" id="title">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="video_url">
                                        Course Video URL <span class="text-danger">*</span>
                                    </label>
                                    <input type="url" name="video_url"
                                        value="{{ old('video_url') ?? $course->video_url }}" class="form-control"
                                        placeholder="" id="video_url">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="sort_description">
                                        Course Sort Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="sort_description" value="" class="form-control" id="sort_description" rows="3"
                                        placeholder="Write a sort_description">
                                        {{ old('sort_description') ?? $course->sort_description}}
                                    </textarea>
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="image">
                                        Course Image <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="image" class="form-control" id="image">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-2">
                                    <label class="form-label" for="price">
                                        Course Price <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="price" value="{{ old('price') ?? $course->price }}"
                                        class="form-control" placeholder="price" id="price">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-2">
                                    <label class="form-label" for="discount">
                                        Course Discount <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="discount" value="{{ old('discount') ?? $course->discount }}"
                                        class="form-control" placeholder="" id="discount">
                                </div>
                                <!-- Input -->
                                <div class="mb-3 col-md-2">
                                    <label class="form-label" for="total_student">
                                        Total student <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="total_student"
                                        value="{{ old('total_student') ?? $course->total_student }}" class="form-control"
                                        placeholder="" id="total_student">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="duration_class">
                                        Course Duration Class <span class="text-danger">*</span>
                                    </label>
                                    <select name="duration_class" class="form-select" id="duration_class">
                                        <option @if ($course->duration_class == '1 Month') selected @endif value="1 Month">
                                            1 Month
                                        </option>
                                        <option @if ($course->duration_class == '2 Month') selected @endif value="2 Month">
                                            2 Month
                                        </option>
                                        <option @if ($course->duration_class == '3 Month') selected @endif value="3 Month">
                                            3 Month
                                        </option>
                                        <option @if ($course->duration_class == '4 Month') selected @endif value="4 Month">
                                            4 Month
                                        </option>
                                        <option @if ($course->duration_class == '5 Month') selected @endif value="5 Month">
                                            5 Month
                                        </option>
                                        <option @if ($course->duration_class == '6 Month') selected @endif value="6 Month">
                                            6 Month
                                        </option>
                                        <option @if ($course->duration_class == '7 Month') selected @endif value="7 Month">
                                            7 Month
                                        </option>
                                        <option @if ($course->duration_class == '8 Month') selected @endif value="8 Month">
                                            8 Month
                                        </option>
                                        <option @if ($course->duration_class == '9 Month') selected @endif value="9 Month">
                                            9 Month
                                        </option>
                                        <option @if ($course->duration_class == '10 Month') selected @endif value="10 Month">
                                            10 Month
                                        </option>
                                        <option @if ($course->duration_class == '11 Month') selected @endif value="11 Month">
                                            11 Month
                                        </option>
                                        <option @if ($course->duration_class == '12 Month') selected @endif value="12 Month">
                                            12 Month
                                        </option>
                                    </select>
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="weekly_class">
                                        Course Weekly Class <span class="text-danger">*</span>
                                    </label>
                                    <select name="weekly_class" class="form-select" id="weekly_class">
                                        <option @if ($course->weekly_class == '1') selected @endif value="1">
                                            1
                                        </option>
                                        <option @if ($course->weekly_class == '2') selected @endif value="2">
                                            2
                                        </option>
                                        <option @if ($course->weekly_class == '3') selected @endif value="3">
                                            3
                                        </option>
                                        <option @if ($course->weekly_class == '4') selected @endif value="4">
                                            4
                                        </option>
                                        <option @if ($course->weekly_class == '5') selected @endif value="5">
                                            5
                                        </option>
                                        <option @if ($course->weekly_class == '6') selected @endif value="6">
                                            6
                                        </option>
                                        <option @if ($course->weekly_class == '7') selected @endif value="7">
                                            7
                                        </option>
                                    </select>
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="skill_requirements">
                                        Course skill requirements <span class="text-danger">*</span>
                                    </label>
                                    <select name="skill_requirements" class="form-select" id="skill_requirements">
                                        <option @if ($course->skill_requirements == 'Beginner') selected @endif value="Beginner">
                                            Beginner
                                        </option>
                                        <option @if ($course->skill_requirements == 'Intermediate') selected @endif value="Intermediate">
                                            Intermediate
                                        </option>
                                        <option @if ($course->skill_requirements == 'Advanced') selected @endif value="Advanced">
                                            Advanced
                                        </option>
                                        <option @if ($course->skill_requirements == 'Expert') selected @endif value="Expert">
                                            Expert
                                        </option>
                                    </select>
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="language">
                                        Course Language <span class="text-danger">*</span>
                                    </label>
                                    <select name="language" class="form-select" id="language">
                                        <option @if ($course->language == 'Bangla') selected @endif value="Bangla">
                                            Bangla
                                        </option>
                                        <option @if ($course->language == 'English') selected @endif value="English">
                                            English
                                        </option>
                                        <option @if ($course->language == 'Spanish') selected @endif value="Spanish">
                                            Spanish
                                        </option>
                                        <option @if ($course->language == 'Hindi') selected @endif value="Hindi">
                                            Hindi
                                        </option>
                                        <option @if ($course->language == 'Arabic') selected @endif value="Arabic">
                                            Arabic
                                        </option>
                                    </select>
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="assignment">
                                        Course Assignment <span class="text-danger">*</span>
                                    </label>
                                    <select name="assignment" class="form-select" id="assignment">
                                        <option @if ($course->assignment == 'Yes') selected @endif value="Yes">
                                            Yes
                                        </option>
                                        <option @if ($course->assignment == 'No') selected @endif value="No">
                                            No
                                        </option>
                                    </select>
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
                                    <script src="{{ asset('backend') }}/assets/js/no-api-key5.10.7-133_tinymce.min.js"></script>
                                    <script>
                                        tinymce.init({
                                            selector: '.textarea',
                                            plugins: 'table lists link',
                                            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncalignright | bullist numlist outdent indent | table | link',
                                        });

                                        function submitForm() {
                                            var editorContent = tinymce.get('myTextarea').getContent();
                                            // Send 'editorContent' to your server using AJAX or form submission
                                            alert('Content to be submitted:\n\n' + editorContent);
                                        }
                                    </script>
                                    <label for="description" class="form-label">Course Description <small
                                            class="text-muted">(Optional)</small></label>
                                    <textarea name="description" value="" class="form-control textarea" id="description" rows="3"
                                        placeholder="Write a Description">
                                        {{ old('description') ?? $course->description }}
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
                            <img src="{{ asset('/media/course') . '/' . $course->image }}" alt="1"
                                class="rounded img-fluid">
                        </div>
                    </div>
                    <!-- Card  -->
                    <div class="mb-4 card">
                        <!-- Card Header -->
                        <div class="card-header">
                            <!-- input -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label" for="categories">
                                    Course Categories <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="categories"
                                    value="{{ old('categories') ?? $course->categories }}" class="form-control"
                                    id="categories">
                            </div>
                        </div>
                    </div>
                    <!-- Card  -->
                    <div class="mb-4 card">
                        <!-- Card Header -->
                        <div class="card-header">
                            <!-- Input -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label" for="status_class">
                                    Course Status <span class="text-danger">*</span>
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
                    <div class="card">
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

                    <div class="d-flex justify-content-end pt-5">
                        <!-- buttons -->
                        <a href="#" class="btn btn-outline-primary me-2">Cancel</a>
                        <button class="btn btn-primary" type="submit">Publish Data</button>
                    </div>

                </div>

            </div>
        </form>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
