@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-user"></i> Pool</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Closed Pool</a></li>
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
                                <th>Reference</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pools as $pool)
                            <tr>
                                <td>
                                    <a href="/b4m/view/{{$pool['reference']}}">{{$pool['reference']}} </a>
                                </td>
                                <td>{{$pool['firstname']}} {{$pool['lastname']}} </td>
                                <td>{{$pool['phone']}}</td>
                                <td>{{$pool['description']}}</td>
                                <td>{{$pool['amount']}}</td>
                                <td>{{$pool['balance']}}</td>
                                <td> @if($pool['is_closed'] == 1)

                                    <span class="badge badge-success">Active</span>

                                    @else
                                    <span class="badge badge-primary">Pending</span>
                                    @endif
                                </td>
                                <td>{{$pool['created_at']}}</td>

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