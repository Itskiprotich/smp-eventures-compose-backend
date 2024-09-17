@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-area-chart"></i> Trial Balance</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Trial Balance</a></li>
  </ul>
</div>
<div class="row">
  <!-- To be worked on -->
  <div class="col-md-12">
    <div class="tile">
      <div class="tile-body">
        <div>
          <form class="row">
            <div class="form-group col-md-4">
              <label class="control-label">Start Date</label>
              <input class="form-control"  required="required"  id="startDate" type="text" placeholder="Select Date">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label">End Date</label>
              <input class="form-control"  required="required"  id="endDate" type="text" placeholder="Select Date">
            </div>
            
            <div class="form-group col-md-4 align-self-end">
              <button class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Submit</button>
            </div>
          </form>
          <!--  -->
        </div>
        <div class="clearix"></div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="datatable">
            <thead>
              <tr>
                <th>Account Type</th>
                <th>Account Code</th>
                <th>Dr</th>
                <th>Cr</th> 
                
              </tr>
            </thead>
            <tbody>
              @foreach($customers as $customer)
              <tr>
                
                <td>{{$customer['phone']}}</td> 
                <td>{{$customer['type']}}</td>
                <td>{{$customer['status']}}</td>  
                <td>{{$customer['created_at']}}</td>
              

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