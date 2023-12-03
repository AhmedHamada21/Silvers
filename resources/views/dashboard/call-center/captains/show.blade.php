@extends('layouts.master')
@section('css')
@section('title')
{{ $data['captain']->name . ' ' . $data['title'] }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="mb-0">{{ $data['captain']->name . ' ' . $data['title'] }}</h4>
        </div>
        <div class="col-sm-6">
            <ol class="float-left pt-0 pr-0 breadcrumb float-sm-right ">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="default-color">Dasboard</a></li>
                <li class="breadcrumb-item active">{{ $data['captain']->name . ' ' . $data['title'] }}</li>
            </ol>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')
@include('layouts.common.partials.messages')
<!-- row -->
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h2>{{ $data['captain']->name }} Information</h2>
                        <p>Name: {{ $data['captain']->name }}</p>
                        <p>Email: {{ $data['captain']->email }}</p>
                        <p>Phone: {{ $data['captain']->phone }}</p>
                    </div>

                    <div class="col-md-6">
                        <h2>Profile Information</h2>
                        <p>Address: {{ $data['captain']->profile->address }}</p>
                        <p>Bio: {{ $data['captain']->profile->bio }}</p>
                        <p>Rate: {{ $data['captain']->profile->rate }}</p>
                        <p>Number of Trips: {{ $data['captain']->profile->number_trips }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- row closed -->

<!-- Start Content and Tabs -->
<div class="row">
    <!-- Start Tabs -->
    <div class="col-xl-12 col-lg-12 mb-30">
        <div class="card mb-30">
            <div class="card-body">
                <div class="comment-block">
                    <div class="mb-0 form-group">
                        <div class="tab nav-bt">
                            <!-- Start Nav Tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active show" id="profile-03-tab" data-toggle="tab"
                                        href="#profile-03" role="tab" aria-controls="profile-03"
                                        aria-selected="true">Profile</a>
                                </li>
                            </ul>
                            <!-- End Nav Tabs -->

                            <!-- Start Tab Content -->
                            <div class="tab-content">
                                @include('dashboard.call-center.captains.tabs.profile.profile_content_tab')
                            </div>
                            <!-- End Tab Content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Tabs -->
</div>
<!-- End Content and Tabs -->
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection