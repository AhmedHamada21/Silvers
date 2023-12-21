@extends('layouts.master')
@section('css')
@section('title')
{{$data['callCenter']->name . ' ' .$data['title']}}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="mb-0">{{$data['callCenter']->name . ' ' .$data['title']}}</h4>
        </div>
        <div class="col-sm-6">
            <ol class="float-left pt-0 pr-0 breadcrumb float-sm-right ">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="default-color">Dasboard</a></li>
                <li class="breadcrumb-item active">{{$data['callCenter']->name . ' ' .$data['title']}}</li>
            </ol>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')
@include('layouts.common.partials.messages')
<!-- start profile content -->
<div class="profile-page">
    <!-- start profile-page-container -->
    <!-- Start User Info -->
    <div class="row">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <div class="user-bg" style="background: url({{asset('assets/images/user-bg.jpg') }});">
                        <div class="user-info">
                            <div class="row">
                                <div class="col-lg-4 align-self-center">
                                    <div class="user-dp"><img
                                            src="{{ $data['callCenter']->profile?->avatar ? asset('dashboard/images/driver_document/' . $data['callCenter']->email . $data['callCenter']->phone . '_' . $data['callCenter']->profile->uuid  . '/' . $data['callCenter']->profile->avatar) : asset('dashboard/default/default_admin.jpg') }}"
                                            alt="{{$data['callCenter']?->name}}"></div>
                                    <div class="user-detail">
                                        <h4 class="nametext-light">
                                            <p class="mb-0">
                                                <span style="font-size: 12px;"
                                                    class="fa fa-circle text-{{ $data['callCenter']?->status == 'active' ? 'success' : 'danger' }}"></span>
                                                <i
                                                    class="fa {{ $data['callCenter']?->gender == 'male' ? 'fa-male text-primary' : 'fa-female text-purple' }}"></i>
                                                {{$data['callCenter']?->name}}
                                            </p>
                                            <p class="mb-0">{{$data['callCenter']?->email}}</p>
                                            <p class="mb-0">{{'Phone' . $data['callCenter']?->phone}}</p>
                                        </h4>
                                    </div>
                                </div>

                                <div class="col-lg-4 text-left align-self-center">
                                    <form id="toggleForm{{ $data['callCenter']->id }}" method="POST"
                                        action="{{ route('callCenters.update-status',$data['callCenter']?->id) }}">
                                        @csrf
                                        <label>Call-Center Work Status</label>
                                        <select class="form-control p-2" name="status" style="outline-style:none;"
                                            onchange="this.form.submit();">
                                            <option selected>Choose Call-Center Work Status</option>
                                            <option value="active" {{$data['callCenter']?->status == 'active' ?
                                                'selected' : ''}}>Active</option>
                                            <option value="inactive" {{$data['callCenter']?->status == 'inactive' ?
                                                'selected' : ''}}>In Active</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End User Info -->

</div>
<!-- end profile content -->
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection