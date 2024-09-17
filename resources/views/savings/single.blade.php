@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-money"></i> Withdrawal</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">View Withdrawal</a></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
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
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <h5 class="tile-title"> Details</h5>
            <div class="tile-body">

                <form method="POST" action="/savings/withdrawal/process/{{ $data['reference'] }}">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Saving Product</label>
                            <input name="reference" value="{{ $data['product_name'] }}" class="form-control" required="required" type="text" placeholder="Enter First Name">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Amount</label>
                            <input name="amount" value="{{ $data['amount'] }}" class="form-control" required="required" type="text" placeholder="Enter Last Name">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Phone</label>
                            <input name="phone" value="{{ $data['phone'] }}" class="form-control" required="required" type="phone" placeholder="Enter Phone">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Transactio Date</label>
                            <input name="date" value="{{ $data['created_at'] }}" class="form-control" required="required" type="text" placeholder="Enter Transaction Date">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4 align-self-end">
                            <a href="/savings/pwithdrawals" class="btn btn-xs btn-danger"><i class="fa fa-fw fa-lg fa-check-circle"></i>Cancel</a>
                        </div>
                        <div class="form-group col-md-4 align-self-end">
                            <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Approve</button>
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>

@endsection