@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-area-chart"></i>Income Statement Report</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Income Statement Report</a></li>
  </ul>
</div>
<div class="row">
  <!-- To be worked on -->
  <div class="col-md-12">
    <div class="tile">
      <div class="tile-body">
        <div>
          <form class="row" action="/reports/filter/income" method="post">
          @csrf
            <div class="form-group col-md-4">
              <label class="control-label">Start Date</label>
              <input class="form-control" autocomplete="off" name="startdate" required="required"  id="startDate" type="text" placeholder="Select Date">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label">End Date</label>
              <input class="form-control" autocomplete="off" name="enddate"  required="required"  id="endDate" type="text" placeholder="Select Date">
            </div>
             
            <div class="form-group col-md-4 align-self-end">
              <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Submit</button>
            </div>
          </form>
          
        </div>
        <div class="clearix"></div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="datatable">
            <thead>
              <tr>
                <th>Account Type</th>
                <th>Account Code</th>
                <th>Account Name</th>
                <th>Amount </th>
                <!-- <th>Amount Cr</th>  -->
              </tr>
              
            </thead>
            <tbody>
              @foreach($charts as $chart)
              <tr>
                <td>{{$chart['account_type']}}</td>
                <td>{{$chart['account_code']}}</td>
                <td>{{$chart['account_name']}}</td>
                <td>{{$chart['amount_dr']}}</td> 
                <!-- <td>{{$chart['amount_cr']}}</td>  -->
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