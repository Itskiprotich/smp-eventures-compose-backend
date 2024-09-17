@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-bar-chart"></i> Investment</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Investment</a></li>
  </ul>
</div>
<div class="row">
  <!-- To be worked on -->
  <div class="col-md-12">
    <div class="tile">
      <div class="tile-title-w-btn">

        <h3 class="title"></h3>
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
        <div class="btn-group">
          <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Add Investor </button>
          <button class="btn btn-info" data-toggle="modal" data-target="#transferModal">
            <i class="fa fa-lg fa-refresh"></i> Transfer Shares </button>
        </div>

        <!-- Modal div -->
        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header"> New Investor
                <button type="button" class="close" data-dismiss="modal">&times;</button>

              </div>
              <form method="POST" action="/investment/register" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                  <div class="form-group">
                    <label class="control-label">Phone Number</label>
                    <select name="phone" required="required" class="form-control" style="width: 82%" id="demoSelect">

                      @foreach($customers as $prod)
                      <option value="{{$prod['phone']}}">{{$prod['firstname']}} {{$prod['lastname']}} - {{$prod['phone']}} </option>
                      @endforeach

                    </select>
                  </div>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-primary" type="submit">Register</button>
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>

                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Shares Transfer -->
        <div id="transferModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h6>Shares Transfer</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

              </div>
              <form method="POST" action="/investment/transfer" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                  <div class="form-group ">
                    <label class="control-label">Original Account</label>

                    <select name="original" required="required" class="form-control selector" style="width: 100%">

                      @foreach($investment as $prod)
                      <option value="{{$prod['phone']}}">{{$prod['firstname']}} {{$prod['lastname']}} - {{$prod['phone']}} </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group ">
                    <label class="control-label">Destination Account</label>

                    <select name="destination" required="required" class="form-control selector" style="width: 100%">
                      @foreach($investment as $prod)
                      <option value="{{$prod['phone']}}">{{$prod['firstname']}} {{$prod['lastname']}} - {{$prod['phone']}} </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group ">
                    <label class="control-label">Amount</label>
                    <input name="amount" class="form-control col-md-12" required="required" type="text" placeholder="Enter Amount">
                  </div>
                  <div class="form-group ">
                    <label class="control-label">Note</label>
                    <textarea name="note" required class="form-control" id="exampleTextarea" rows="3"></textarea>
                  </div>
                </div>

                <div class="modal-footer">
                  <button class="btn btn-primary" type="submit">Submit</button>
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>

                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="tile-body">
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="datatable">
            <thead>
              <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Float</th>
                <th>Interest</th>
                <th>Status</th>
                <th>Joined</th>
              </tr>
            </thead>
            <tbody>
              @foreach($investment as $invest)
              <tr>
                <td><a href="/investment/view/{{$invest['phone']}}">{{$invest['firstname']}} {{$invest['lastname']}}</a></td>
                <td>{{$invest['phone']}}</td>
                <td>{{$invest['float_balance']}}</td>
                <td>{{$invest['interest_balance']}}</td>
                <td> @if($invest['status'] == 1)

                  <span class="badge badge-success">Active</span>

                  @else
                  <span class="badge badge-warning">Inactive</span>
                  @endif
                </td>
                <td>{{$invest['created_at']}}</td>


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