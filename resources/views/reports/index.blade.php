@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-cc"></i> Accounts</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Accounts</a></li>
  </ul>
</div>
<div class="row">
  <!-- To be worked on -->
  <div class="col-md-12">
    <div class="tile">
      <div class="tile-title-w-btn">
        <h3 class="title"></h3>

        <div class="btn-group">
          <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Add Account </button>
        </div>

        <!-- Modal div -->
        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>

              </div>
              <form method="POST" action="/reports/accounts/add">
                @csrf

                <div class="modal-body">

                  @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                  @endif
                  <div class="form-group ">
                    <label class="control-label">Account Number</label>
                    <input name="number" class="form-control" required="required" type="text" placeholder="Enter Account Number">

                  </div>

                  <div class="form-group ">
                    <label class="control-label">Account Name</label>
                    <input name="name" class="form-control" required="required" type="text" placeholder="Enter Account Name">

                  </div>
                  <div class="form-group ">
                    <label class="control-label">Category</label>
                    <select name="category" required="required" class="form-control" id="exampleSelect1">

                      @foreach($categories as $cart)
                      <option value="{{$cart['id']}}">{{$cart['name']}}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="modal-footer">
                    <div class="form-group col-md-4 align-self-end">
                      <button class="btn btn-success" type="submit">Add Account</button>
                    </div>

                  </div>
              </form>
            </div>
          </div>
        </div>


      </div>

      <!-- End modal -->
    </div>
    <div class="tile-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered" id="datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>Item Name</th>
              <th>Account Number</th>
              <th>Account Type</th>
              <th>Status</th>
              <th>Added</th>
            </tr>
          </thead>
          <tbody>

            @foreach($charts as $chart)
            <tr>
              <td>{{ $chart['id']}}</td>
              <td><a href="/reports/edit/{{$chart['id']}}">{{$chart['chart_name']}}</a></td>
              <td>{{$chart['account_no']}}</td>
              <td>{{$chart['chart_name']}}</td>
              <td> @if($chart['status'] == 1)

                <span class="badge badge-success">Active</span>

                @else
                <span class="badge badge-warning">Inactive</span>
                @endif
              </td>

              <td>{{$chart['created_at']}}</td>


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