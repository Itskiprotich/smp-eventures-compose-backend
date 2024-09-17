@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-area-chart"></i>Cash Flow Monthly</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Cash Flow Monthly</a></li>
  </ul>
</div>
<div class="row">
  <!-- To be worked on -->
  <div class="col-md-12">
    <div class="tile">
      <div class="tile-body">
        <div>
          <form class="row">
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
              <button class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search!!</button>
            </div>
          </form>

        </div>
        <div class="clearix"></div>

        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr class="table-info">
                <th></th>
                <th>{{ $data['month_one']}}</th>
                <th>{{ $data['month_two']}}</th>
                <th>{{ $data['month_three']}}</th>
                <th>{{ $data['month_four']}}</th>
                <th>{{ $data['month_five']}}</th>
                <th>{{ $data['month_six']}}</th>
              </tr>

            </thead>
            <tbody>
              <tr>
                <td>Opening Balance (O)</td>
                <td>{{ $data['opening_one']}}</td>
                <td>{{ $data['opening_two']}}</td>
                <td>{{ $data['opening_three']}}</td>
                <td>{{ $data['opening_four']}}</td>
                <td>{{ $data['opening_five']}}</td>
                <td>{{ $data['opening_six']}}</td>
              </tr>
              <tr>
                <td>Receipts</td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
              </tr>
              <tr>
                <td>Loan Principal Repayments</td>
                <td>{{ $data['loan_principal_repayments_one']}}</td>
                <td>{{ $data['loan_principal_repayments_two']}}</td>
                <td>{{ $data['loan_principal_repayments_three']}}</td>
                <td>{{ $data['loan_principal_repayments_four']}}</td>
                <td>{{ $data['loan_principal_repayments_five']}}</td>
                <td>{{ $data['loan_principal_repayments_six']}}</td>
              </tr>
              <tr>
                <td>Loan Interest Repayments</td>
                <td>{{ $data['loan_interest_repayments_one']}}</td>
                <td>{{ $data['loan_interest_repayments_two']}}</td>
                <td>{{ $data['loan_interest_repayments_three']}}</td>
                <td>{{ $data['loan_interest_repayments_four']}}</td>
                <td>{{ $data['loan_interest_repayments_five']}}</td>
                <td>{{ $data['loan_interest_repayments_six']}}</td>
              </tr>
              <tr>
                <td>Loan Penalty Repayments</td>
                <td>{{ $data['loan_penalty_repayments_one']}}</td>
                <td>{{ $data['loan_penalty_repayments_two']}}</td>
                <td>{{ $data['loan_penalty_repayments_three']}}</td>
                <td>{{ $data['loan_penalty_repayments_four']}}</td>
                <td>{{ $data['loan_penalty_repayments_five']}}</td>
                <td>{{ $data['loan_penalty_repayments_six']}}</td>
              </tr>
              <tr>
                <td>Loan Fees Repayments (Non-Deductable)</td>
                <td>{{ $data['loan_fees_non_deduct_one']}}</td>
                <td>{{ $data['loan_fees_non_deduct_three']}}</td>
                <td>{{ $data['loan_fees_non_deduct_three']}}</td>
                <td>{{ $data['loan_fees_non_deduct_four']}}</td>
                <td>{{ $data['loan_fees_non_deduct_five']}}</td>
                <td>{{ $data['loan_fees_non_deduct_six']}}</td>
              </tr>
              <tr>
                <td>Deductable Loan Fees</td>
                <td>{{ $data['loan_fees_deduct_one']}}</td>
                <td>{{ $data['loan_fees_deduct_two']}}</td>
                <td>{{ $data['loan_fees_deduct_three']}}</td>
                <td>{{ $data['loan_fees_deduct_four']}}</td>
                <td>{{ $data['loan_fees_deduct_five']}}</td>
                <td>{{ $data['loan_fees_deduct_six']}}</td>
              </tr>
              <tr>
                <td>Investor Account Deposits</td>
                <td>{{ $data['investor_deposits_one']}}</td>
                <td>{{ $data['investor_deposits_two']}}</td>
                <td>{{ $data['investor_deposits_three']}}</td>
                <td>{{ $data['investor_deposits_four']}}</td>
                <td>{{ $data['investor_deposits_five']}}</td>
                <td>{{ $data['investor_deposits_six']}}</td>
              </tr>
              <tr class="table-success">
                <td>Total Receipts (A)</td>
                <td>{{ $data['total_receipts_a_one']}}</td>
                <td>{{ $data['total_receipts_a_two']}}</td>
                <td>{{ $data['total_receipts_a_three']}}</td>
                <td>{{ $data['total_receipts_a_four']}}</td>
                <td>{{ $data['total_receipts_a_five']}}</td>
                <td>{{ $data['total_receipts_a_six']}}</td>
              </tr>
              <tr>
                <td> </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td>Payments</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>

              <tr>
                <td>Expenses</td>
                <td>{{ $data['expenses_one']}}</td>
                <td>{{ $data['expenses_two']}}</td>
                <td>{{ $data['expenses_three']}}</td>
                <td>{{ $data['expenses_four']}}</td>
                <td>{{ $data['expenses_five']}}</td>
                <td>{{ $data['expenses_six']}}</td>
              </tr>
              <tr>
                <td>Loans Released (Principal)</td>
                <td>{{ $data['prin_one']}}</td>
                <td>{{ $data['prin_two']}}</td>
                <td>{{ $data['prin_three']}}</td>
                <td>{{ $data['prin_four']}}</td>
                <td>{{ $data['prin_five']}}</td>
                <td>{{ $data['prin_six']}}</td>
              </tr>
              <tr>

                <td>Payroll</td>
                <td>{{ $data['payroll_one']}}</td>
                <td>{{ $data['payroll_two']}}</td>
                <td>{{ $data['payroll_three']}}</td>
                <td>{{ $data['payroll_four']}}</td>
                <td>{{ $data['payroll_five']}}</td>
                <td>{{ $data['payroll_six']}}</td>
              </tr>
              <tr>
                <td>Investor Account Withdrawals</td>
                <td>{{ $data['withdrawals_one']}}</td>
                <td>{{ $data['withdrawals_two']}}</td>
                <td>{{ $data['withdrawals_three']}}</td>
                <td>{{ $data['withdrawals_four']}}</td>
                <td>{{ $data['withdrawals_five']}}</td>
                <td>{{ $data['withdrawals_six']}}</td>
              </tr>

              <tr class="table-danger">
                <td>Total Payments (B) </td>
                <td>{{ $data['total_payments_b_one']}}</td>
                <td>{{ $data['total_payments_b_two']}}</td>
                <td>{{ $data['total_payments_b_three']}}</td>
                <td>{{ $data['total_payments_b_four']}}</td>
                <td>{{ $data['total_payments_b_five']}}</td>
                <td>{{ $data['total_payments_b_six']}}</td>
              </tr>
              <tr>
                <td> </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr class="table-warning">
                <td>Cash Balance (O) + (A) - (B)</td>
                <td>{{ $data['cash_balance_one']}}</td>
                <td>{{ $data['cash_balance_two']}}</td>
                <td>{{ $data['cash_balance_three']}}</td>
                <td>{{ $data['cash_balance_four']}}</td>
                <td>{{ $data['cash_balance_five']}}</td>
                <td>{{ $data['cash_balance_six']}}</td>
              </tr>

            </tbody>

          </table>
        </div>


      </div>
    </div>
  </div>
</div>
</div>

@endsection