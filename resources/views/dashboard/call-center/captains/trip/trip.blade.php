@extends('layouts.master')
@section('css')
@section('title')
{{ $data->first()->captain->name . ' Trips' }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="mb-0">{{ $data->first()->captain->name . ' Trips' }}</h4>
        </div>
        <div class="col-sm-6">
            <ol class="float-left pt-0 pr-0 breadcrumb float-sm-right ">
                <li class="breadcrumb-item"><a href="{{route('callCenter.dashboard')}}"
                        class="default-color">Dasboard</a></li>
                <li class="breadcrumb-item active">{{ $data->first()->captain->name . ' Trips' }}</li>
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
                <!-- Start Tabs -->
                <div class="comment-block">
                    <div class="mb-0 form-group">
                        <div class="tab nav-bt">
                            <!-- Start Nav Tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                @foreach($types as $key => $type)
                                <li class="nav-item">
                                    <a class="nav-link {{ $key == 0 ? 'active show' : '' }}" id="{{ strtolower(str_replace(' ', '_', $type)) }}-tab"
                                        data-toggle="tab" href="#{{ strtolower(str_replace(' ', '_', $type)) }}" role="tab"
                                        aria-controls="{{ strtolower(str_replace(' ', '_', $type)) }}" aria-selected="true">{{ $type }}</a>
                                </li>
                                @endforeach
                            </ul>
                            <!-- End Nav Tabs -->
                
                            <!-- Start Tab Content -->
                            <div class="tab-content">
                                @foreach($types as $key => $type)
                                <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="{{ strtolower(str_replace(' ', '_', $type)) }}"
                                    role="tabpanel" aria-labelledby="{{ strtolower(str_replace(' ', '_', $type)) }}-tab">
                                    <h4>{{ $type }} Trips</h4>
                            
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Order Code</th>
                                                <th>Created At</th>
                                                <!-- Add more columns as needed -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data as $item)
                                            @if($type == 'Orders' && $item instanceof \App\Models\Order)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->order_code }}</td>
                                                <td>{{ $item->created_at }}</td>
                                                <!-- Add more cells for additional columns -->
                                            </tr>
                                            @elseif($type == 'Order Hours' && $item instanceof \App\Models\OrderHour)
                                            <!-- Display content for Order Hours -->
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->order_code }}</td>
                                                <td>{{ $item->created_at }}</td>
                                                <!-- Add more cells for additional columns -->
                                            </tr>
                                            @elseif($type == 'Order Days' && $item instanceof \App\Models\OrderDay)
                                            <!-- Display content for Order Days -->
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->order_code }}</td>
                                                <td>{{ $item->created_at }}</td>
                                                <!-- Add more cells for additional columns -->
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                            
                                </div>
                                @endforeach
                            </div>
                            <!-- End Tab Content -->
                        </div>
                    </div>
                </div>
                <!-- End Tabs -->
            </div>

        </div>
    </div>
</div>
<!-- row closed -->
@endsection
@section('js')

@endsection