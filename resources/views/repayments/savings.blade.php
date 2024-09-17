@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-users"></i> Savings</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Customer Savings</a></li>
  </ul>
</div>
<div class="row">
  <!-- To be worked on -->
  <div class="col-md-12">
    <div class="tile">

      @if (session()->has('success'))
      <div class="alert alert-dismissible alert-success">
        <button class="close" type="button" data-dismiss="alert">×</button>
        <p>{{ session('success') }}</p>
      </div>
      @endif
      
      <div class="tile-title-w-btn">
        <h3 class="title"></h3>

        <div class="btn-group"> </div>

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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
              <th>Name</th>
              <th>Phone</th>
              <th>Amount</th>
              <th>Joined</th>
            </tr>
          </thead>
          <tbody>
            @foreach($savings as $saving)
            <tr>
              <td><a href="/repayments/savings/view/{{$saving['phone']}}">{{$saving['firstname']}} {{$saving['lastname']}}</a></td>
              <td>{{$saving['phone']}}</td>
              <td>{{$saving['amount']}}</td>
              <td>{{$saving['created_at']}}</td>

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