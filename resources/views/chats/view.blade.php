@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i> Chat Room</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Chat Room</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-8">
        <div class="tile">

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <h3 class="tile-title">Chat</h3>
            <div class="messanger">
                <div class="messages">
                    @foreach($chats as $chat)

                    @if($chat['is_admin']== 1)
                    <div class="message me"><img width="30px;" height="30px" src="{{ asset('images/smp.jpg') }}">

                        @if(substr( $chat['message'], 0, 5 ) === "https")
                        <img width="150px;" height="150px" src="{{$chat['message']}}">
                        @else
                        <p class="info">{{$chat['message']}}</p>
                        @endif
                    </div>
                    @else
                    <div class="message "><img width="30px;" height="30px" src="{{ asset('images/smp.jpg') }}">

                        @if(substr( $chat['message'], 0, 5 ) === "https")
                        <img width="150px;" height="150px" src="{{$chat['message']}}">
                        @else
                        <p class="info">{{$chat['message']}}</p>
                        @endif
                    </div>
                    @endif
                    @endforeach

                </div>
                <form method="POST" action="/chats/add">
                    @csrf
                    <div class="sender">

                        <input type="text" value="{{$chat['phone']}}" hidden name="phone" required="required">
                        <input type="text" name="message" required="required" placeholder="Send Message">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-lg fa-fw fa-paper-plane"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="tile">

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <h3 class="tile-title">Attachment</h3>
            <div class="tile-body">
                <form method="POST" action="/chats/upload/{{$chat['phone']}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group ">
                        <label class="control-label">First Name</label>
                        <input required name="attachment" class="form-control" required="required" accept=".jpg,.jpeg,.png,.pdf" type="file" placeholder="Upload">
                    </div>
                    <div class="form-group col-md-4 align-self-end">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection