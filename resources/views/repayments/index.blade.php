@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-users"></i> Payments</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Payments</a></li>
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

      @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
      <div class="tile-title-w-btn">
        <h3 class="title">All Payments</h3>

        <div class="btn-group"><button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Add Missing Payment </button> </div>

        <!-- Modal div -->
        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add Missing Payment</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

              </div>
              <form method="POST" action="/payments/record" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                  <div class="row">
                    <div class="form-group col-md-6">
                      <label class="control-label">Phone</label>
                      <select name="phone" required="required" style="width: 100%" class="form-control" id="demoSelect">

                        @foreach($customers as $cust)
                        <option value="{{$cust['phone']}}">
                          {{$cust['phone']}}-{{$cust['firstname']}} {{$cust['lastname']}}
                        </option>
                        @endforeach

                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Type</label>
                      <select name="type" required="required" class="form-control" id="exampleSelect1">
                        <option value="1">Loan Repayment</option>
                        <!-- <option value="2">Savings</option> -->
                        <option value="3">Welfare</option>
                        <option value="4">Shares</option>

                      </select>
                    </div>

                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label class="control-label">Amount</label>
                      <input name="amount" class="form-control" required="required" type="number" placeholder="Enter Amount">
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Reference</label>
                      <input name="code" class="form-control" required="required" type="text" placeholder="Enter Reference Code">
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
    </div>
    <div class="tile-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered" id="datatable">
          <thead>
            <tr>
              <th>Phone</th>
              <th>Reference</th>
              <th>Amount</th>
              <th>Description</th>
              <th>Channel</th>
              <th>Status</th>
              <th>Action By </th>
              <th>Transaction Date</th>
            </tr>

          </thead>
          <tbody>
            @foreach($response as $res)
            <tr>
              <td>{{$res['msisdn_id']}}</td>
              <td>{{$res['txncd']}}</td>
              <td>{{$res['mc']}}</td>
              <td>{{$res['msisdn_idnum']}}</td>
              <td>{{$res['channel']}}</td>
              <td> @if($res['status'] == 0)

                <span class="badge badge-success">Success</span>

                @else
                <span class="badge badge-warning">Failed</span>
                @endif
              </td>
              <td>{{$res['action_by']}}</td>
              <td>{{$res['created_at']}}</td>

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