@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-users"></i> Customers</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Pending Customers</a></li>
  </ul>
</div>
<div class="row">
  <!-- To be worked on -->
  <div class="col-md-12">
    <div class="tile">
      <div class="tile-title-w-btn">
        <h3 class="title"></h3>
        @if (session()->has('success'))
        
         <div class="alert alert-dismissible alert-success">
          <button class="close" type="button" data-dismiss="alert">×</button>
          <p>{{ session('success') }}</p>
        </div>  
        @endif
        <div class="btn-group">
          <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Bulk Upload </button> </div>

        <!-- Modal div -->
        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>

              </div>
              <form method="POST" action="/uploads/upload-customers" enctype="multipart/form-data">
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
                    <label class="control-label">Select File</label>

                    <input name="uploaded_file" class="form-control" type="file">

                  </div>

                  <div class="modal-footer">
                    <div class="form-group col-md-4 align-self-end">
                      <button class="btn btn-success" type="submit">Upload</button>
                    </div>

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
              <th>Membership</th>
              <th>Type</th>
              <th>Status</th>
              <th>Loan Limit</th>
              <th>Joined</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $count=0;?>
            @foreach($customers as $customer)
            <?php $count++;?>
            <tr>
              <td>{{$count}}</td>
              <td>{{$customer['firstname']}} {{$customer['lastname']}}</td>
              <td>{{$customer['phone']}}</td>
              <td>{{$customer['membership_no']}}</td>
              <td>{{$customer['type']}}</td>
              <td>{{$customer['status']}}</td>
              <td>{{$customer['loanlimit']}}</td>
              <td>{{$customer['created_at']}}</td>
              <td>
                <div class="btn-group">
                  <!-- <a class="btn btn-success" href="/customer/view/{{$customer['id']}}"><i class="fa fa-lg fa-eye"></i></a> -->
                  <a class="btn btn-primary btn-sm" href="/customer/edit/{{$customer['id']}}"><i class="fa fa-lg fa-edit"></i></a> </div>
              </td>

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