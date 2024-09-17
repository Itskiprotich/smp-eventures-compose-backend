@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-area-chart"></i>Cash flow Accumulated</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Accumulated</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div>
                    <form class="row" action="/reports/filter/accumulated" method="post">
                        @csrf
                        <div class="form-group col-md-4">
                            <label class="control-label">Start Date</label>
                            <input class="form-control" autocomplete="off" name="startdate" required="required" id="startDate" type="text" placeholder="Select Date">
                        </div> 
                        <div class="form-group col-md-4">
                            <label class="control-label">End Date</label>
                            <input class="form-control" autocomplete="off" name="enddate" required="required" id="endDate" type="text" placeholder="Select Date">
                        </div>

                        <div class="form-group col-md-4 align-self-end">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search!!</button>
                        </div>
                    </form>

                </div>
                <div class="clearix"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                <tr class="table-info">
                                        <th>Receipts</th>
                                        <th>Balance</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td>#</td>
                                    </tr>
                                   
                                    <tr>
                                        <td>Loan Principal Repayments</td>
                                        <td>{{ $data['loan_principal_repayment']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Loan Interest Repayments</td>
                                        <td>{{ $data['loan_interest_repayment']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Loan Penalty Repayments</td>
                                        <td>{{ $data['loan_penalty_repayment']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Loan Fees Repayments (Non-Deductable)</td>
                                        <td>{{ $data['loan_fees_processing']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Deductable Loan Fees</td>
                                        <td>{{ $data['loan_fees_deductable']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Investor Account Deposits</td>
                                        <td>{{ $data['loan_investor_deposits']}}</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>Total Receipts (A)</td> 
                                        <td>{{ $data['total_receipts_a']}}</td>
                                    </tr>

                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                <tr class="table-info">
                                        <th>Payments</th>
                                        <th>Balance</th>
                                    </tr>

                                </thead>
                                <tbody>

                                    <tr>
                                        <td>Expenses</td>
                                        <td>{{ $data['expenses']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Payroll</td>
                                        <td>{{ $data['payroll']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Investor Account Withdrawals</td>
                                        <td>{{ $data['investor_withdrawals']}}</td>
                                    </tr>
                                   
                                    <tr class="table-danger">
                                        <td>Total Payments (B) </td>
                                        <td>{{ $data['total_payment_b']}}</td>
                                    </tr>
                                    <tr >
                                        <td> </td>
                                        <td></td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>Total Cash Balance (A) - (B)</td>
                                        <td>{{ $data['total_payment_a_b']}}</td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection