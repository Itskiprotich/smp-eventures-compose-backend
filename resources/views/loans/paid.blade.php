@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-users"></i> Loans</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Paid Loans </a></li>
  </ul>
</div>
<div class="row">
  <!-- To be worked on -->
  <div class="col-md-12">
    <div class="tile">
      <div class="tile-body">
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="datatable">
            <thead>
              <tr>
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
              @foreach($loans as $loan)
              <tr>
                <td> <a href="/loans/view/{{$loan['loan_ref']}}">{{$loan['customer_name']}} </a></td>
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