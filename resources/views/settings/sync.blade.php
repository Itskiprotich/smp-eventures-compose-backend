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

            <div class="tile-title-w-btn">
                <h3 class="title"></h3>
                @if (session()->has('success'))

                <div class="alert alert-dismissible alert-success">
                    <button class="close" type="button" data-dismiss="alert">×</button>
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                @if (session()->has('error'))

                <div class="alert alert-dismissible alert-danger">
                    <button class="close" type="button" data-dismiss="alert">×</button>
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                <div class="btn-group">
                <a class="btn btn-info show_confirm_online" href="/settings/sync"><i class="fa fa-lg fa-refresh"></i>Sync All</a>
                   
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
                            <?php $count = 0; ?>
                            @foreach($customers as $customer)
                            <?php $count++; ?>
                            <tr>
                                <td>{{$count}}</td>
                                <td> <a href="/customer/view/{{$customer['id']}}"> {{$customer['firstname']}} {{$customer['lastname']}}</td>
                                <td>{{$customer['phone']}}</td>
                                <td>{{$customer['membership_no']}}</td>
                                <td>{{$customer['type']}}</td>
                                <td>{{$customer['status']}}</td>
                                <td>{{$customer['loanlimit']}}</td>
                                <td>{{$customer['created_at']}}</td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-info btn-sm show_confirm_online" href="/customer/online/{{ $customer['id']}}"><i class="fa fa-lg fa-refresh"></i></a>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to reject this?')) {
            e.preventDefault();
        }
    });

    $('.show_confirm_approve').click(function(e) {
        if (!confirm('Are you sure you want to approve this record?')) {
            e.preventDefault();
        }
    });

    $('.show_confirm_online').click(function(e) {
        if (!confirm('Are you sure you want to grant online access?')) {
            e.preventDefault();
        }
    });
</script>
@endsection