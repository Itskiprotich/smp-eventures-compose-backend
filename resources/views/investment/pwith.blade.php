@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i> Withdrawals</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Investment Withdrawals</a></li>
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

            @if (session()->has('error'))
            <div class="alert alert-dismissible alert-warning">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('error') }}</p>
            </div>
            @endif
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Request Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawal as $with)
                            <tr>
                                <td>{{$with['firstname']}} {{$with['lastname']}}</td>
                                <td>{{$with['phone']}}</td>
                                <td>{{$with['amount']}}</td>
                                <td> @if($with['status'] == 1)
                                    <span class="badge badge-success">Active</span>
                                    @else
                                    <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{$with['created_at']}}</td>
                                <td>
                                    <div class="btn-group">
                                        <form method="POST" action="/investment/reject/{{$with->reference}}">
                                            @csrf
                                            <input name="_method" type="hidden" value="POST">
                                            <button type="submit" class="btn-danger btn-flat show_confirm" data-toggle="tooltip" title='Reject'> <i class="fa fa-trash-o"> </i></button>
                                        </form>
                                        <form method="POST" action="/investment/approve/{{$with->reference}}">
                                            @csrf
                                            <input name="_method" type="hidden" value="POST">
                                            <button type="submit" class="btn-success btn-flat show_confirm_approve" data-toggle="tooltip" title='Approve'> <i class="fa fa-check"> </i></button>
                                        </form>
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
        if (!confirm('Are you sure you want to approved?')) {
            e.preventDefault();
        }
    });
</script>

@endsection