@extends('backend.layouts.master')
@section('content')
    <!-- Container fluid -->
    <section class="p-4 container-fluid">
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Page header -->
                <div class="pb-3 mb-3 border-bottom d-md-flex align-items-center justify-content-between">
                    <div class="mb-2 mb-lg-0">
                        <h1 class="mb-0 h2 fw-bold">Success Story </h1>
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('backend_Home_Index') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Success Story
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <!-- button -->
                    <div>
                        <a href="#" class="me-5 text-body"><i class="fe fe-upload me-2"></i>Export</a>
                        <a href="#" class="me-5 text-body"><i class="fe fe-download me-2"></i>Import</a>
                        <a href="{{ route('backend_Success_Story_Edit') }}" class="btn btn-primary me-2">UPDATE</a>
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
                    <!-- Tab -->
                    <div class="card-body">
                        <b>Image</b><br>
                        <img src="{{ asset('/media/successStory') . '/' . $successStory->image }}" alt="successStory Image"
                            class="rounded img-4by3-md">
                        <hr>
                        <b>Title</b><br>
                        {!! $successStory->title !!}
                        <hr>
                        <b>Sort Title</b><br>
                        {!! $successStory->sort_title !!}
                        <hr>
                        <b>Description</b><br>
                        {!! $successStory->description !!}
                        <hr>
                        <b>Video url</b><br>
                        {!! $successStory->video_url !!}
                        <hr>
                        <b>CREATE DATA</b><br>
                        {{ $successStory->created_at }}
                        <hr>
                        <b>UPDATE DATE</b><br>
                        {{ $successStory->updated_at }}
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Container fluid -->
@endsection
