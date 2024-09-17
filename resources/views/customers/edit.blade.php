@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-user"></i> Customer</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Edit Customer</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-8">
        <div class="tile">
            <h3 class="tile-title"></h3>
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

                <form method="POST" action="/customer/update/correct/{{ $data['id'] }}">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">First Name</label>
                            <input name="firstname" value="{{ $data['firstname'] }}" class="form-control" required="required" type="text" placeholder="Enter First Name">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Last Name</label>
                            <input name="lastname" value="{{ $data['lastname'] }}" class="form-control" required="required" type="text" placeholder="Enter Last Name">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Phone</label>
                            <input name="phone" value="{{ $data['phone'] }}" class="form-control" required="required" type="phone" placeholder="Enter Phone">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Email</label>
                            <input name="email" value="{{ $data['email'] }}" class="form-control" required="required" type="email" placeholder="Enter Email">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">ID Number</label>
                            <input name="national" value="{{ $data['national_id'] }}" class="form-control" required="required" type="number" placeholder="Enter ID Number">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Gender</label>
                            <select name="gender" required="required" class="form-control" id="exampleSelect1">
                                <option value="{{ $data['gender'] }}">{{ $data['gender'] }}</option>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Membership</label>
                            <input name="member" value="{{ $data['membership_no'] }}" class="form-control" required="required" type="text" placeholder="Enter Password">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Device Name</label>
                            <input name="devicename" value="{{ $data['devicename'] }}" class="form-control" required="required" type="text" placeholder="Enter Device">

                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <a href="/customer/pending"><button class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Back</button></a>
                        </div>
                        <div class="form-group col-md-4">
                            <a href="/customer/resetpin/{{ $data['phone']}}"><button class="btn btn-warning" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Reset PIN</button></a>
                        </div>
                        <div class="form-group col-md-4">
                            <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Details</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="tile">
            <h3 class="tile-title"></h3>
            @if (session()->has('success'))

            <div class="alert alert-dismissible alert-success">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('success') }}</p>
            </div>
            @endif
            <div class="tile-body">

                <form method="POST" action="/customer/update/{{ $data['id'] }}">
                    @csrf
                    <div class="form-group">
                        <label class="control-label">Status</label>

                        <select name="status" required="required" class="form-control" id="exampleSelect1">
                            <option value="Approved">Approved</option>
                            <option value="Pending">Pending</option>

                        </select>
                    </div>
                    <div class="form-group ">
                        <label class="control-label">Blacklist Status</label>
                        <select name="blacklist" required="required" class="form-control" id="exampleSelect1">
                            <option value="false">Active</option>
                            <option value="true">Blacklist</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Disbursement Mode</label>

                        <select name="automatic" required="required" class="form-control" id="exampleSelect1">
                            <option value="false">Manual</option>
                            <option value="true">Auto</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="control-label">Loan Limit</label>
                        <input name="loanlimit" value="{{ $data['loanlimit'] }}" class="form-control" required="required" type="number" placeholder="Enter Loan Limit">

                    </div>

                    <div class="form-group ">
                        <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Approve Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection