@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-area-chart"></i> Balance Sheet Report</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Balance Sheet Report</a></li>
  </ul>
</div>
<div class="row">
  <!-- To be worked on -->
  <div class="col-md-12">
    <div class="tile">
      <div class="tile-body">
        <div>
          @if(session()->has('message'))
          <div class="alert alert-success">
            {{ session()->get('message') }}
          </div>
          @endif
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          <form class="row" method="post" action="/reports/filter/balance">
            @csrf

            <div class="form-group col-md-4">
              <label class="control-label">Start Date</label>
              <input class="form-control" name="startdate" required="required" id="startDate" type="text" placeholder="Select Date">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label">End Date</label>
              <input class="form-control" name="enddate" required="required" id="endDate" type="text" placeholder="Select Date">
            </div>

            <div class="form-group col-md-4 align-self-end">
              <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
            </div>
          </form>
          <!--  -->
        </div>
        <div class="clearix"></div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="datatable">
            <thead>
              <tr>
                <th>Account Type</th>
                <th>Account Code</th>
                <th>Account Name</th>
                <th>Amount Dr</th>
                <th>Amount Cr</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($customers as $customer)
              <tr>
                <td>{{$customer['firstname']}} {{$customer['lastname']}}</td>
                <td>{{$customer['phone']}}</td>
                <td>{{$customer['membership_no']}}</td>
                <td>{{$customer['type']}}</td>
                <td>{{$customer['status']}}</td>
                <td>
                  <div class="btn-group"><a class="btn btn-primary" href="/customer/edit/{{$customer['id']}}"><i class="fa fa-lg fa-edit"></i></a><a class="btn btn-danger" href="#"><i class="fa fa-lg fa-trash"></i></a></div>
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