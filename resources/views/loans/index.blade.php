@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-users"></i> Loans</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Pending Loans </a></li>
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
        <h3 class="title"></h3>

        <div class="btn-group">
          <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Add New </button>
        </div>

        <!-- Modal div -->
        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add New Loan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

              </div>
              <form method="POST" action="/loans/manual" enctype="multipart/form-data">
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
                      <label class="control-label">Loan Type</label>
                      <select name="loan_code" required="required" class="form-control" id="exampleSelect1">

                        @foreach($loantypes as $prod)
                        <option value="{{$prod['loan_code']}}">{{$prod['loan_name']}} </option>
                        @endforeach

                      </select>
                    </div>

                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label class="control-label">Amount</label>
                      <input name="principle" class="form-control" required="required" type="number" placeholder="Enter Amount">
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Disbursement Date</label>
                      <input class="form-control" autocomplete="off" name="startdate" required="required" id="startDate" type="text" placeholder="Select Date">
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
              <th>#</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Reference</th>
              <th>Loan Amount</th>
              <th>Loan Balance</th>
              <th>Disbursement Date</th>
              <th>Principal</th>
              <th>Interest</th>
              <th>Admin Fee</th>
              <th>Penalty</th>

            </tr>

          </thead>
          <tbody>
            <?php $count = 0; ?>
            @foreach($loans as $loan)
            <?php $count++; ?>
            <tr>
              <td>{{$count}}</td>
              <td> <a href="/loans/edit/{{$loan['loan_ref']}}">{{$loan['customer_name']}} </a></td>
              <td>{{$loan['phone']}}</td>
              <td>{{$loan['loan_ref']}}</td>
              <td>{{$loan['loan_amount']}}</td>
              <td>{{$loan['loan_balance']}}</td>
              <td>{{$loan['disbursment_date']}}</td>
              <td>{{$loan['principle']}}</td>
              <td>{{$loan['interest']}}</td>
              <td>{{$loan['admin_fee']}}</td>
              <td>{{$loan['penalty_amount']}}</td>

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