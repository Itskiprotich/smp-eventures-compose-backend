@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-money"></i> Repayments</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Make Repayments</a></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="tile">
            <h6 class="tile-title">Enter Details</h6>


            @if (session()->has('error'))
            <div class="alert alert-dismissible alert-danger">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('error') }}</p>
            </div>
            @endif
            <div class="tile-body">

                <form method="POST" action="/repayments/update">
                    @csrf
                    <div class="form-group ">
                        <label class="control-label">Phone number</label>
                        <input name="phone" value="{{$data['phone']}}" class="form-control" required="required" type="text" placeholder="Enter Category Name">

                    </div>
                    <div class="form-group ">
                        <label class="control-label">Amount</label>
                        <input name="amount" class="form-control" required="required" type="text" placeholder="Enter Amount">

                    </div>
                    <div class="form-group ">
                        <label class="control-label">Transaction Code</label>
                        <input name="trans_code" class="form-control" required="required" type="text" placeholder="Enter Transaction Code">

                    </div>
                    <div class="form-group ">
                        <!-- <div class="form-group col-md-4 align-self-end"> -->
                        <button class="btn btn-primary" type="submit">Approve Payment</button>
                        <!-- </div> -->
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="tile">
            <h5 class="tile-title">View Loan Details</h5>
            <div class="tile-body">

                <form method="POST" action="/loans/update">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label class="control-label"> Name</label>
                            <input name="name" value="{{$data['customer_name']}}" class="form-control" required="required" type="text" placeholder="Enter Name">
                        </div>
                        <div class="form-group col-md-5">
                            <label class="control-label">Phone</label>
                            <input name="phone" value="{{$data['phone']}}" class="form-control" required="required" type="text" placeholder="Enter Phone">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label class="control-label">Reference</label>
                            <input name="reference" value="{{$data['loan_ref']}}" class="form-control" required="required" type="text" placeholder="Enter Reference">
                        </div>
                        <div class="form-group col-md-5">
                            <label class="control-label">Principal</label>
                            <input name="principal" value="{{$data['principle']}}" class="form-control" required="required" type="text" placeholder="Enter Principal">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label class="control-label">Interest</label>
                            <input name="national" value="{{$data['interest']}}" class="form-control" required="required" type="number" placeholder="Enter Interest">
                        </div>
                        <div class="form-group col-md-5">
                            <label class="control-label">Admin Fee</label>
                            <input name="national" value="{{$data['admin_fee']}}" class="form-control" required="required" type="number" placeholder="Enter Admin Fee">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label class="control-label">Loan Amount</label>
                            <input name="loan_amount" value="{{$data['admin_fee']}}" class="form-control" required="required" type="text" placeholder="Enter Loan Amount">
                        </div>
                        <div class="form-group col-md-5">
                            <label class="control-label">Loan Balance</label>
                            <input name="loan_balance" value="{{$data['loan_balance']}}" class="form-control" required="required" type="text" placeholder="Enter Loan Balance">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label class="control-label">Duration</label>
                            <input name="loan_duration" value="{{$data['repayment_period']}}" class="form-control" required="required" type="text" placeholder="Enter Duration">
                        </div>
                        <div class="form-group col-md-5">
                            <label class="control-label">Repayment Date</label>
                            <input name="loan_balance" value="{{$data['repayment_date']}}" class="form-control" required="required" type="text" placeholder="Enter Repayment Date">
                        </div>

                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h5 class="tile-title">Loan Schedule</h5>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>

                                <th>Phone</th>
                                <th>Reference</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Paid Amount</th>

                            </tr>

                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                            <tr>

                                <td>{{$schedule['phone']}}</td>
                                <td>{{$schedule['loan_ref']}}</td>
                                <td>{{$schedule['amount']}}</td>
                                <td>{{$schedule['due_date']}}</td>
                                <td>{{$schedule['paid_amount']}}</td>


                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h5 class="tile-title">Loan Repayments</h5>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="repayments">
                        <thead>
                            <tr>

                                <th>Phone</th>
                                <th>Reference</th>
                                <th>Amount</th>
                                <th>Date Paid</th>
                                <th>Balance</th>
                                <th>Initiator</th>

                            </tr>

                        </thead>
                        <tbody>
                            @foreach($repayments as $repayment)
                            <tr>

                                <td>{{$repayment['phone']}}</td>
                                <td>{{$repayment['loan_ref']}}</td>
                                <td>{{$repayment['paid_amount']}}</td>
                                <td>{{$repayment['date_paid']}}</td>
                                <td>{{$repayment['balance']}}</td>
                                <td>{{$repayment['initiator']}}</td>
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