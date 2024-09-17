@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-user"></i> Admin</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Edit Admin</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">Admin Details</h3>
            <div class="tile-body">

                <form method="POST" action="/admin/update">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">First Name</label>
                            <input name="firstname" value="{{ $data['firstname'] }}" class="form-control" required="required" type="text" placeholder="Enter First Name">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Last Name</label>
                            <input name="lastname" value="{{ $data['lastname'] }}" class="form-control" required="required" type="text" placeholder="Enter Last Name">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Phone</label>
                            <input name="phone" value="{{ $data['phone'] }}" class="form-control" required="required" type="phone" placeholder="Enter Phone">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Email</label>
                            <input name="email" value="{{ $data['email'] }}" class="form-control" required="required" type="email" placeholder="Enter Email">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">ID Number</label>
                            <input name="national" value="{{ $data['national_id'] }}" class="form-control" required="required" type="number" placeholder="Enter ID Number">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Gender</label>
                            <select name="gender" required="required" class="form-control" id="exampleSelect1">
                                <option value="{{ $data['gender'] }}">{{ $data['gender'] }}</option>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Membership</label>
                            <input name="member" value="{{ $data['membership_no'] }}" class="form-control" required="required" type="text" placeholder="Enter Password">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Status</label>

                            <select name="status" required="required" class="form-control" id="exampleSelect1">
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Disbursement Mode</label>
                            
                            <select name="automatic" required="required" class="form-control" id="exampleSelect1">
                                <option value="false">Manual</option>
                                <option value="true">Auto</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Blacklist Status</label>
                            <select name="blacklist" required="required" class="form-control" id="exampleSelect1">
                                <option value="false">Active</option>
                                <option value="true">Blacklist</option>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                    <div class="form-group col-md-4">
                            <label class="control-label">Device Name</label>
                            <input name="devicename" value="{{ $data['devicename'] }}" class="form-control" required="required" type="text" placeholder="Enter Device">
                            
                        </div>  
                    <div class="form-group col-md-4">
                            <label class="control-label">Loan Limit</label>
                            <input name="loanlimit" value="{{ $data['loanlimit'] }}" class="form-control" required="required" type="number" placeholder="Enter Loan Limit">
                            
                        </div>
                    

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4 align-self-end">
                            <button class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Back</button>
                        </div>
                        <div class="form-group col-md-4 align-self-end">
                            <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection