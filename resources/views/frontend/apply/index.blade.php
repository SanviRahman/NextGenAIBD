@extends('frontend.layouts.master')
@section('title', 'Apply | Sunnah digital Agency')
@section('content')
    <section class="banner-section breadcumd-banner">
        <div class="container">
            <div class="breadcumd-wrapper p-0 m-0">
                <h1 class="left-content">
                    Apply
                </h1>
                <ul class="right-content">
                    <li>
                        <a href="{{ route('home_Index') }}">
                            Home
                        </a>
                    </li>
                    <li>
                        Apply
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="about-section bg-white pt-120 pb-120">
        <div class="shape">
            <img src="{{ asset('frontend') }}/assets/images/vector/s-vector.png" alt="shape">
        </div>
        <div class="shape2">
            <img src="{{ asset('frontend') }}/assets/images/vector/s-vector.png" alt="shape">
        </div>
        <div class="container">
            <form action="{{ route('frontend_ApplyDB_Index') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-xl-9 col-lg-8 col-md-12 col-12 mx-auto">
                        <!-- Card -->
                        <div class="mb-4 border-0 card">
                            <!-- Card header -->
                            <div class="card-header text-dark">
                                <h4 class="mb-0 text-dark">Apply for admission</h4>
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
                                    <div class="alert alert-success">
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
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label" for="name">
                                            Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control" placeholder="" id="name">
                                    </div>
                                    <!-- input -->
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="address">
                                            Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="address" value="{{ old('address') }}"
                                            class="form-control" placeholder="" id="address">
                                    </div>
                                    <!-- input -->
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="number">
                                            Number <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="number" value="{{ old('number') }}"
                                            class="form-control" placeholder="" id="number">
                                    </div>
                                    <!-- input -->
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="email">
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control" placeholder="" id="email">
                                    </div>

                                    <!-- input -->
                                    {{-- <div class="mb-3 col-md-6">
                                        <label class="form-label" for="password">
                                            Password <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="password" value="{{ old('password') }}"
                                            class="form-control" placeholder="" id="password">
                                    </div> --}}

                                    <!-- Educational Institute -->
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="educational_institute">
                                            Educational Institute <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="educational_institute"
                                            value="{{ old('educational_institute') }}" class="form-control"
                                            id="educational_institute">
                                    </div>

                                    <div class="mb-3 col-md-12">
                                        <label class="form-label" for="course">
                                            Course <span class="text-danger">*</span>
                                        </label>
                                        <select name="course" class="form-select text-dark" id="course">
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->title }}" data-price="{{ $course->price }}">
                                                    {{ $course->title }} - ({{ $course->price }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Course Fee -->
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="course_fee">
                                            Course Fee <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="course_fee_visible" value="{{ old('course_fee') }}"
                                            class="form-control" id="course_fee" readonly>
                                        <input type="hidden" name="course_fee" id="hidden_course_fee">
                                    </div>

                                    <!-- Course Fee Paid -->
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="course_fee_paid">
                                            Course Fee Paid <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="course_fee_paid"
                                            value="{{ old('course_fee_paid') }}" class="form-control"
                                            id="course_fee_paid">
                                    </div>

                                    <!-- Course Fee Unpaid -->
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="course_fee_unpaid">
                                            Course Fee Unpaid <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="course_fee_unpaid_visible"
                                            value="{{ old('course_fee_unpaid') }}" class="form-control"
                                            id="course_fee_unpaid" readonly>
                                        <input type="hidden" name="course_fee_unpaid" id="hidden_course_fee_unpaid">
                                    </div>


                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const courseSelect = document.getElementById('course');
                                            const courseFeeInput = document.getElementById('course_fee');
                                            const courseFeePaidInput = document.getElementById('course_fee_paid');
                                            const courseFeeUnpaidInput = document.getElementById('course_fee_unpaid');
                                            const hiddenCourseFeeInput = document.getElementById('hidden_course_fee');
                                            const hiddenCourseFeeUnpaidInput = document.getElementById('hidden_course_fee_unpaid');

                                            let selectedCourseFee = 0;

                                            // Function to update the hidden fields
                                            function updateHiddenFields() {
                                                hiddenCourseFeeInput.value = courseFeeInput.value;
                                                hiddenCourseFeeUnpaidInput.value = courseFeeUnpaidInput.value;
                                            }

                                            // Function to update course fee and unpaid fee
                                            function updateFees() {
                                                const paidAmount = parseFloat(courseFeePaidInput.value) || 0;
                                                const unpaidAmount = selectedCourseFee - paidAmount;

                                                // Update unpaid amount and ensure it doesn't go below 0
                                                courseFeeUnpaidInput.value = unpaidAmount >= 0 ? unpaidAmount.toFixed(2) : 0;

                                                // Also update hidden fields before submission
                                                updateHiddenFields();
                                            }

                                            // Update the course fee when a course is selected
                                            courseSelect.addEventListener('change', function() {
                                                // Get the selected option's price from data attribute
                                                selectedCourseFee = parseFloat(courseSelect.options[courseSelect.selectedIndex]
                                                    .getAttribute('data-price')) || 0;

                                                // Set the course fee input
                                                courseFeeInput.value = selectedCourseFee.toFixed(2);

                                                // Reset the paid and unpaid fields
                                                courseFeePaidInput.value = '';
                                                courseFeeUnpaidInput.value = selectedCourseFee.toFixed(2);

                                                // Update hidden inputs
                                                updateHiddenFields();
                                            });

                                            // Update the unpaid fee when paid amount changes
                                            courseFeePaidInput.addEventListener('input', function() {
                                                const paidAmount = parseFloat(courseFeePaidInput.value) || 0;

                                                // Prevent entering more than the total course fee
                                                if (paidAmount > selectedCourseFee) {
                                                    courseFeePaidInput.value = selectedCourseFee;
                                                }

                                                updateFees();
                                            });

                                            // Initialize with the first selected course fee
                                            const initialSelectedCourse = courseSelect.options[courseSelect.selectedIndex];
                                            if (initialSelectedCourse) {
                                                selectedCourseFee = parseFloat(initialSelectedCourse.getAttribute('data-price')) || 0;
                                                courseFeeInput.value = selectedCourseFee.toFixed(2);
                                                courseFeeUnpaidInput.value = selectedCourseFee.toFixed(2);
                                                updateHiddenFields();
                                            }
                                        });
                                    </script>


                                    <!-- Description -->
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label" for="description">
                                            Description <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="description" class="form-control" rows="4" placeholder="Enter Description" id="description">{{ old('description') }}</textarea>
                                    </div>


                                    <div class="d-flex justify-content-end">
                                        <!-- buttons -->
                                        <a href="#" class="btn btn-outline-primary me-2">Cancel</a>
                                        <button class="btn btn-outline-danger me-2" type="reset">Reset</button>
                                        <button class="btn btn-primary" type="submit">Publish Data</button>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </form>
        </div>
    </section>
@endsection
