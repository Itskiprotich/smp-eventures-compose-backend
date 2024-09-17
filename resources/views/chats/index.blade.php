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
  <div class="col-md-12">
    <div class="tile">
      @if (session()->has('success'))

      <div class="alert alert-dismissible alert-success">
        <button class="close" type="button" data-dismiss="alert">×</button>
        <p>{{ session('success') }}</p>
      </div>
      @endif
      @if (session()->has('error'))

      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button>
        <p>{{ session('error') }}</p>
      </div>
      @endif
      <div class="tile-title-w-btn">
        <h3 class="title"></h3>

        <div class="btn-group"><button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Compose </button> </div>
        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h5>New Message</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

              </div>
              <form method="POST" action="/chats/compose" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                  @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                  @endif
                  <div class="form-group ">
                    <label class="control-label">Select Phone</label>
                    <select name="phone" required="required" class="form-control" id="exampleSelect1">

                      @foreach($customers as $prod)
                      <option value="{{$prod['phone']}}">{{$prod['firstname']}} {{$prod['lastname']}} - {{$prod['phone']}} </option>
                      @endforeach

                    </select>

                  </div>
                  <div class="form-group ">
                    <label class="control-label">Message</label>
                    <input name="message" class="form-control" required="required" type="text" placeholder="Type here...">

                  </div>


                  <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Send</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>


                  </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="tile-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered" id="datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>Phone</th>
              <th>Message</th>
              <th>Status</th>
              <th>Time</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($chats as $chat)
            <tr>
              <td>{{$chat['id']}}</td>
              <td><a href="/chats/view/{{$chat['phone']}}">{{$chat['phone']}}</a></td>
              <td>{{$chat['message']}}</td>
              <td> @if($chat['is_admin'] == 1)

                <span class="badge badge-warning">Admin Message</span>

                @else
                <span class="badge badge-success">User Message</span>
                @endif
              </td>
              <td>{{$chat['created_at']}}</td>
              <td>
                <a href="/chats/view/{{$chat['phone']}}" class="badge badge-success"><i class="fa fa-lg fa-eye"> View</i></a>
              </td>

            </tr>
            @endforeach

          </tbody>

        </table>
      </div>
    </div>
  </div>
</div>
</div>

@endsection