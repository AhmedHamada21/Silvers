@extends('layouts.master')
@section('css')
<style>
    .chat-button button{
        font-size: 20px;
        width: 40px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        display: block;
        background: #84ba3f;
        color: #ffffff;
        margin-top: 2px;
        border-radius: 50%;
    }
</style>
@section('title')
{{$data['ticket']->title}}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="mb-0"> {{$data['ticket']->title}}</h4>
        </div>
        <div class="col-sm-6">
            <ol class="float-left pt-0 pr-0 breadcrumb float-sm-right ">
                <li class="breadcrumb-item"><a href="{{route('callCenter.dashboard')}}" class="default-color">Dasboard</a></li>
                <li class="breadcrumb-item active">{{$data['ticket']->title}}</li>
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
                <h1>Ticket Details</h1>
                
                <p><strong>Title:</strong> {{ $data['ticket']->title }}</p>
                <p><strong>Order Code:</strong> {{ $data['ticket']->order_code }}</p>
                <p><strong>Ticket Code:</strong> {{ $data['ticket']->ticket_code }}</p>
                <p><strong>Subject:</strong>
                    <textarea class="form-control col-8 input-message" rows="2">{{ $data['ticket']->subject }}</textarea>
                     </p>
                <p><strong>Priority:</strong> {{ $data['ticket']->priority }}</p>
                <p><strong>Status:</strong> {{ $data['ticket']->status }}</p>
                
                <div class="col-12 d-flex">
                    <div class="card-body">
                        <!-- Start Chat Widget -->
                        <div class="chats-topbar mb-30 position-relative">
                            <div class="d-block d-md-flex justify-content-between">
                                <div class="d-block">
                                    <h6 class="mb-0">{{ ucfirst($data['ticket']->title) . ' / ' . ucfirst($data['ticket']->callcenter->name) . ' Chat'
                                        }}</h6>
                                </div>
                            </div>
                        </div>
                        <!-- End Chat Widget -->
                        <div class="scrollbar max-h-600" style="height: 300px; overflow-y: scroll; overflow-x: scroll;">
                            <div class="chats">
                                @foreach($data['replies'] as $reply)
                                    <div class="chat-wrapper clearfix">
                                        <div class="chat-avatar">
                                            @if (!empty($reply->admin_id) || !empty($reply->manager_id) || !empty($reply->callcenter_id))
                                                <img class="img-fluid avatar-small"
                                                    src="{{ (empty($reply->admin_id) || empty($reply->manager_id))  ? asset('dashboard/default/default_admin.jpg') : asset('dashboard/default/default_callcenter.jpg') }}"
                                                    alt="">
                                            @endif
                                            
                                        </div>
                                        <div class="chat-body p-3"
                                            style="{{ (empty($reply->admin_id) && empty($reply->manager_id)) ? 'margin-left:250px;width: 50%; background-color: #dedb1b; text-align: center; display:block; color: #444;' : ($reply->admin_id || $reply->manager_id ? 'background-color: #8aff9a; text-align: left;' : 'text-align: left;') }}">
                                            <p>
                                                <span class="text-gray">
                                                    {{ $reply?->manager?->email}}
                                                </span>
                                                <br>
                                                {{ $reply->messages }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="time d-block mt-5px mb-20 ml-20 text-gray">{{ $reply->created_at->format('g:iA')}}</span>
                                @endforeach
                            </div>
                        </div>
                        @if (auth('admin')->check() || get_user_data()->type == 'manager')
                            <div class="chats mt-30">
                                <div class="chat-wrapper mb-0 clearfix">
                                    <form action="{{ route('CallCenterTickets.addReply', ['id' => $data['ticket']->id]) }}" method="post">
                                        @csrf
                                        <div class="chat-input">
                                            <div class="chat-input-icon">
                                                <a class="text-muted" href="#"> <i class="fa fa-smile-o"></i> </a>
                                            </div>
                                            <textarea class="form-control input-message scrollbar" placeholder="Type here...*" rows="2"
                                                name="message"></textarea>
                                        </div>
                                        <div class="chat-button">
                                            <button type="submit"><i class="ti-clip"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                        
                        <!-- End Chat ScrollBar -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- row closed -->
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
