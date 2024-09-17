@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-money"></i> View Pool</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">View Pool</a></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-4">
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
                <form method="POST" action="/b4m/contribute">
                    @csrf
                    <div class="form-group">
                        <label class="control-label">Reference</label>
                        <input name="reference" value="{{$data['reference']}}" class="form-control" required="required" type="text" placeholder="Enter Amount">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Amount</label>
                        <input name="amount" class="form-control" required="required" type="number" placeholder="Enter Amount">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Member Name</label>

                        <select name="phone" required="required" class="form-control" id="exampleSelect1">

                            @foreach($buyforme as $customer)
                            <option value="{{$customer['phone']}}">{{$customer['firstname']}} {{$customer['lastname']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Transaction Code</label>
                        <input name="trans_code" class="form-control" required="required" type="text" placeholder="Enter Code">
                    </div>
                    <div class="form-group ">

                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Contribute</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="tile">
            <div class="tile-body">

                <form method="POST" action="/loans/update">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label class="control-label"> First Name</label>
                            <input name="firstname" value="{{$data['firstname']}}" class="form-control" required="required" type="text" placeholder="Enter Name">
                        </div>
                        <div class="form-group col-md-5">
                            <label class="control-label">Last Name</label>
                            <input name="lastname" value="{{$data['lastname']}}" class="form-control" required="required" type="text" placeholder="Enter Phone">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label class="control-label">Phone</label>
                            <input name="phone" value="{{$data['phone']}}" class="form-control" required="required" type="text" placeholder="Enter Reference">
                        </div>
                        <div class="form-group col-md-5">
                            <label class="control-label">Reference</label>
                            <input name="reference" value="{{$data['reference']}}" class="form-control" required="required" type="text" placeholder="Enter Principal">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label class="control-label">Amount</label>
                            <input name="amount" value="{{$data['amount']}}" class="form-control" required="required" type="number" placeholder="Enter Interest">
                        </div>
                        <div class="form-group col-md-5">
                            <label class="control-label">Balance</label>
                            <input name="balance" value="{{$data['balance']}}" class="form-control" required="required" type="number" placeholder="Enter Admin Fee">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label class="control-label">Description</label>
                            <textarea name="description" class="form-control" id="exampleTextarea" rows="3">{{$data['description']}}</textarea>
                        </div>
                        <div class="form-group col-md-5">
                            <label class="control-label">Status</label>
                            <select name="status" required="required" class="form-control" id="exampleSelect1">
                                @if($data['is_closed'] == 1)
                                <option value="true">Active</option>
                                @else
                                <option value="true">Pending</option>
                                @endif
                            </select>
                        </div>


                    </div>


                </form>
            </div>
        </div>
    </div>


</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h5 class="tile-title">Member Commitments</h5>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>

                                <th>Phone</th>
                                <th>Reference</th>
                                <th>Transaction</th>
                                <th>Commit Amount</th>
                                <th>Commit Date</th>

                            </tr>

                        </thead>
                        <tbody>
                            @foreach($commitment as $commit)
                            <tr>

                                <td>{{$commit['phone']}}</td>
                                <td>{{$commit['reference']}}</td>
                                <td>{{$commit['trans_code']}}</td>
                                <td>{{$commit['amount']}}</td>
                                <td>{{$commit['created_at']}}</td>


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