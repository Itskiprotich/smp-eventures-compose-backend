@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-shopping-basket"></i> Repayments</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Loans Repayments</a></li>
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
                                <th style="width:3%">#</th>
                                <th>Month</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php $cc = 0; ?>
                            @foreach($data as $dt)
                            <?php $cc++; ?>
                            <tr>
                                <td>{{$cc}}</td>
                                <td> <a href="#">{{$dt['month']}} </a></td>
                                <td>{{$dt['amount']}}</td>
                                <td></td>

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