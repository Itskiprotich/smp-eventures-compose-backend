@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-handshake-o"></i> Loan Types</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Loan Types</a></li>
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
      <div class="tile-title-w-btn">
        <h3 class="title"></h3>

        <div class="btn-group"><button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Add Loan Type </button> </div>

        <!-- Modal div -->
        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Register Loan Type</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

              </div>
              <form method="POST" action="/loantypes/register" enctype="multipart/form-data">
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

                  <div class="row">
                    <div class="form-group col-md-6">
                      <label class="control-label">Name</label>
                      <input name="loan_name" class="form-control" required="required" type="text" placeholder="Enter Name">
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Duration</label>
                      <select name="duration" required="required" class="form-control" id="exampleSelect1">
                        <option value="7">1 Week Loan</option>
                        <option value="14">2 Weeks Loan</option>
                        <option value="21">3 Weeks Loan</option>
                        <option value="28">1 Month Loan</option>
                        <option value="56">2 Months Loan</option>
                        <option value="84">3 Months Loan</option>
                        <option value="168">6 Months Loan</option>

                      </select>
                    </div>

                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label class="control-label">Interest Rate</label>
                      <input name="interest_rate" class="form-control" required="required" type="number" placeholder="Enter Interest">
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Admin Fee</label>
                      <input name="admin_fee" class="form-control" required="required" type="number" placeholder="Enter Admin Fee">
                    </div>

                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label class="control-label">Minimum Limit</label>
                      <input name="min_limit" class="form-control" required="required" type="number" placeholder="Enter Minimum ">
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Maximum Limit</label>
                      <input name="max_limit" class="form-control" required="required" type="number" placeholder="Enter Maximum ">
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
      </div>
    </div>
    <div class="tile-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered" id="datatable">
          <thead>
            <tr>
              <th>Name</th>
              <th>Code</th>
              <th>Duration</th>
              <th>Minimum</th>
              <th>Maximum</th>
              <th>Interest</th>
              <th>Admin Fee</th>
              <th>Status</th>
              <th>Action</th>
            </tr>

          </thead>
          <tbody>
            @foreach($loantypes as $loantype)
            <tr>
              <td>{{$loantype['loan_name']}} </td>
              <td>{{$loantype['loan_code']}}</td>
              <td>{{$loantype['duration']}}</td>
              <td>{{$loantype['min_limit']}}</td>
              <td>{{$loantype['max_limit']}}</td>
              <td>{{$loantype['interest_rate']}}</td>
              <td>{{$loantype['admin_fee']}}</td>
              <td> @if($loantype['active'] == 1)

                <span class="badge badge-success">Active</span>

                @else
                <span class="badge badge-warning">Inactive</span>
                @endif
              </td>
              <td>

                <a class="btn btn-success" href="/loantypes/view/{{$loantype['loan_code']}}"><i class="fa fa-lg fa-eye"></i></a><a class="btn btn-primary" href="/loantypes/edit/{{$loantype['loan_code']}}"><i class="fa fa-lg fa-edit"></i></a>

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