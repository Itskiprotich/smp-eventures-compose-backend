@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-area-chart"></i> Consolidated Report</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Consolidated Report</a></li>
  </ul>
</div>
<div class="row">
  <!-- To be worked on -->
  <div class="col-md-12">
    <div class="tile">
      <div class="tile-body">
        <div>
        <form class="row" action="/reports/filter/consolidated" method="post">
          @csrf
            <div class="form-group col-md-3">
              <label class="control-label">Start Date</label>
              <input class="form-control" autocomplete="off" name="start_date"  required="required"  id="startDate" type="text" placeholder="Select Date">
            </div>
            <div class="form-group col-md-3">
              <label class="control-label">End Date</label>
              <input class="form-control" autocomplete="off" name="end_date"  required="required"  id="endDate" type="text" placeholder="Select Date">
            </div>
            <div class="form-group col-md-3">
              <label class="control-label">Type</label>
            
              <select name="loan_status" required="required" class="form-control" id="exampleSelect1">
                <option value="Pending">Pending</option>
                <option value="Disbursed">Disbursed</option>
                <option value="Paid">Paid</option>
                <option value="Unpaid">Unpaid</option>
                <option value="Overdue">Overdue</option>
                <option value="Goodloans">Goodloans</option>

              </select>
            </div>
            <div class="form-group col-md-3 align-self-end">
              <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Submit</button>
            </div>
          </form>
          <!--  -->
        </div>
        <div class="clearix"></div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="datatable">
            <thead>
              <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Loan Amount</th>
                <th>Loan Balance</th>
                <th>Disbursment Date</th>
                <th>Repayment Date</th>
                <th>Principal</th>
                <th>Loan Disbursed</th>
                <th>Interest</th>
                <th>Admin Fee</th> 
              </tr>
            </thead>
            <tbody>
              @foreach($loans as $loan)
              <tr>
                <td>{{$loan['customer_name']}} </td>
                <td>{{$loan['phone']}}</td>
                <td>{{$loan['loan_amount']}}</td>
                <td>{{$loan['loan_balance']}}</td>
                <td>{{$loan['disbursment_date']}}</td>
                <td>{{$loan['repayment_date']}}</td>
                <td>{{$loan['principle']}}</td>
                <td>{{$loan['loan_disbursed']}}</td>
                <td>{{$loan['interest']}}</td>
                <td>{{$loan['admin_fee']}}</td>
               

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