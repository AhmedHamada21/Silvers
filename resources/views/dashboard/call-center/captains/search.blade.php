@extends('layouts.master')
@section('css')

@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">

            </div>
            <div class="col-sm-6">
                <ol class="float-left pt-0 pr-0 breadcrumb float-sm-right ">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="default-color">Dasboard</a></li>
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


                    <br>
                    <br>



                </div>


            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection
@section('js')
@endsection
