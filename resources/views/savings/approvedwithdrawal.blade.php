@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-users"></i> Withdrawals</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Savings Withdrawals</a></li>
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
      <div class="tile-body">
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="datatable">
            <thead>
              <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Amount</th>
                <th>Product</th>
                <th>Status</th>
                <th>Request Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($withdrawal as $with)
              <tr>
                <td>{{$with['firstname']}} {{$with['lastname']}}</td>
                <td>{{$with['phone']}}</td>
                <td>{{$with['amount']}}</td>
                <td>{{$with['product_name']}}</td>
                <td> @if($with['status'] == 1)
                  <span class="badge badge-success">Active</span>
                  @else
                  <span class="badge badge-warning">Pending</span>
                  @endif
                </td>
                <td>{{$with['created_at']}}</td>
                <td>
                  <!-- <div class="btn-group"><a class="btn btn-success" href="/savings/withdrawal/{{$with['reference']}}"><i class="fa fa-lg fa-eye"></i></a></div> -->
                  <div class="btn-group"><a class="btn btn-success" href="#"><i class="fa fa-lg fa-eye"></i></a></div>
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