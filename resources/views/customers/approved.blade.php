@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-users"></i> Customers</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">Approved Customers</a></li>
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
              <?php $count = 0; ?>
              @foreach($customers as $customer)
              <?php $count++; ?>
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
                    <a class="btn btn-success btn-sm" href="/customer/view/{{$customer['id']}}"><i class="fa fa-lg fa-eye"></i></a>
                    <a class="btn btn-primary btn-sm" href="/customer/edit/{{$customer['id']}}"><i class="fa fa-lg fa-edit"></i></a>
                  </div>
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