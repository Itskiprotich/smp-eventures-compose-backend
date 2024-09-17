@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-user"></i> Loan Type</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Add Loan Type </a></li>
  </ul>
</div>
<div class="row">
  <!-- To be worked on -->
  <div class="col-md-12">
    <div class="tile">
      <div class="tile-body">

        <form method="POST" >
          @csrf
          <div class="row">
            <div class="form-group col-md-4">
              <label class="control-label">Name</label>
              <input name="loan_name" value="{{ $loantype['loan_name'] }}"  class="form-control" required="required" type="text" placeholder="Enter Name">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label">Duration</label>
              <select name="duration" required="required" class="form-control" id="exampleSelect1">
                <option value="{{ $loantype['duration'] }}">{{ $loantype['duration'] }}</option>
               
              </select>
            </div>

          </div>
          <div class="row">
            <div class="form-group col-md-4">
              <label class="control-label">Interest Rate</label>
              <input name="interest_rate"  value="{{ $loantype['interest_rate'] }}" class="form-control" required="required" type="number" placeholder="Enter Interest">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label">Admin Fee</label>
              <input name="admin_fee" value="{{ $loantype['admin_fee'] }}"  class="form-control" required="required" type="number" placeholder="Enter Admin Fee">
            </div>

          </div>
          <div class="row">
            <div class="form-group col-md-4">
              <label class="control-label">Maximum Limit</label>
              <input name="max_limit"  value="{{ $loantype['max_limit'] }}"  class="form-control" required="required" type="number" placeholder="Enter Maximum ">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label">Minimum Limit</label>
              <input name="min_limit" value="{{ $loantype['min_limit'] }}"  class="form-control" required="required" type="number" placeholder="Enter Minimum ">
            </div>

          </div>
        
          <!--<div class="row">
            <div class="form-group col-md-4 align-self-end">
              <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Submit</button>
            </div>
          </div>-->
        </form>
      </div>
    </div>
  </div>

</div>

@endsection