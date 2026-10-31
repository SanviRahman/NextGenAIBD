@extends('backend.layouts.master')
@section('content')
    <!-- Container fluid -->
    <section class="p-4 container-fluid">
        <div class="p-0 border-bottom card">
            <div class="p-3 card-body">
                <h3 class="m-0">Theme Options</h3>
            </div>
        </div>
        <div class="card">
            <div class="row">
                <div class="col-lg-2 col-12">
                    <!-- Menu them -->
                    @include('backend.theme-option.components.theme-menu')
                    <!-- Menu them -->
                </div>
                <div class="col-lg-9 col-12">
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
                    @if (Session::get('error'))
                        <div class="alert alert-danger">
                            {{ session::get('error') }}
                        </div>
                    @endif
                    <div class="py-5 px- rounded-3">
                        <form action="{{ route('backend_Settings_Logo_Update') }}" method="POST"
                            enctype="multipart/form-data" class="m-2">
                            @csrf
                            <div class="row gx-3">
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="Favicon">
                                        Favicon <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="favicon" class="form-control" id="Favicon">
                                    <img src="{{ asset('/media/logo') . '/' . $logo->favicon }}" class="p-1 mt-2 border"
                                        style="width: 60px" alt="">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="Front_Logo">
                                        Front Logo <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="frontlogo" class="form-control" id="Front_Logo">
                                    <img src="{{ asset('/media/logo') . '/' . $logo->frontlogo }}" class="p-1 mt-2 border"
                                        style="width: 90px" alt="">
                                </div>
                                <!-- input -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="Back_Logo">
                                        Back Logo <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="backlogo" class="form-control" id="Back_Logo">
                                    <img src="{{ asset('/media/logo') . '/' . $logo->backlogo }}" class="p-1 mt-2 border"
                                        style="width: 90px" alt="">
                                </div>

                                <div class="col">
                                    <button class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
