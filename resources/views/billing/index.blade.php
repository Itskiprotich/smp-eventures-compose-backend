@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-user"></i> Billing</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Billing</a></li>
    </ul>
</div>

<div class="tile mb-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h2 class="mb-3 line-head" id="navs">Billing</h2>

            </div>
        </div>
    </div>
    <div class="row" style="margin-bottom: 2rem;">
        <div class="col-lg-12">
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
            <div class="bs-component">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#paid">Unpaid</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#unpaid">Paid</a></li>


                </ul>
                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade active show" id="paid">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="repayments">
                                    <thead>
                                        <tr>

                                            <th>Month</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Float </th>
                                            <th>Ratio </th>
                                            <th>Amount </th>
                                            <th>Status </th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($unpaid as $bill)
                                        <tr>
                                            <td>{{$bill['month']}} {{$bill['year']}}</td>
                                            <td>{{$bill['name']}}</td>
                                            <td>{{$bill['phone']}}</td>
                                            <td>{{$bill['float_balance']}}</td>
                                            <td>{{$bill['ratio']}}</td>
                                            <td>{{$bill['earnings']}}</td>
                                            <td> @if($bill['status'] == 1)

                                                <span class="badge badge-success">Paid</span>

                                                @else
                                                <span class="badge badge-warning">Unpaid</span>
                                                @endif
                                            </td>
                                            <td>{{$bill['created_at']}}</td>
                                            <td>
                                                <form method="POST" action="/billing/pay/{{$bill['id']}}">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="POST">
                                                    <button type="submit" class="btn-success btn-flat show_confirm" data-toggle="tooltip" title='Pay'> <i class="fa fa-check"> </i></button>
                                                </form>
                                            </td>

                                        </tr>
                                        @endforeach

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="unpaid">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="datatable">
                                    <thead>
                                        <tr>

                                            <th>Month</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Float </th>
                                            <th>Ratio </th>
                                            <th>Amount </th>
                                            <th>Status </th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($paid as $bill)
                                        <tr>
                                            <td>{{$bill['month']}} {{$bill['year']}}</td>
                                            <td>{{$bill['name']}}</td>
                                            <td>{{$bill['phone']}}</td>
                                            <td>{{$bill['float_balance']}}</td>
                                            <td>{{$bill['ratio']}}</td>
                                            <td>{{$bill['earnings']}}</td>
                                            <td> @if($bill['status'] == 1)

                                                <span class="badge badge-success">Paid</span>

                                                @else
                                                <span class="badge badge-warning">Unpaid</span>
                                                @endif
                                            </td>
                                            <td>{{$bill['created_at']}}</td>

                                        </tr>
                                        @endforeach

                                    </tbody>

                                </table>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>

    </div>

</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to pay this bill?')) {
            e.preventDefault();
        }
    });
</script>
@endsection