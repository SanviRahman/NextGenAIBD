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
                    <div class="py-2 rounded-3">
                        <a href="{{route('admin_Settings_Sitemap_Generate')}}" class="btn btn-outline-primary">
                            Sitemap Generated
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
