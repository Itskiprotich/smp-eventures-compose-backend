@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-user"></i> Customer</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">View Customer</a></li>
    </ul>
</div>

<div class="tile mb-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h2 class="mb-3 line-head" id="navs">Customer Details</h2>

            </div>
        </div>
    </div>
    <div class="row" style="margin-bottom: 2rem;">
        <div class="col-lg-12">

            <div class="bs-component">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#profile">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#loans">Loans</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#savings">Savings</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#groups">Groups</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#otherloans">Guaranteed Loans</a></li>


                </ul>
                <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade " id="otherloans">
                        <div class="row">
                            <!-- To be worked on -->
                            <div class="col-md-12">
                                <div class="tile">
                                    <h5 class="tile-title">Guaranteed Loans</h5>
                                    <div class="tile-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered datatable">
                                                <thead>
                                                    <tr>
                                                    <th>Name</th>
                                                            <th>Phone</th>
                                                            <th>Reference</th>
                                                            <th>Loan Amount</th>
                                                            <th>Loan Balance</th>
                                                            <th>Disbursement Date</th>
                                                            <th>Principal</th>
                                                            <th>Interest</th>
                                                            <th>Admin Fee</th>
                                                            <th>Penalty</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody>
                                                        @foreach($data['guaranteed'] as $loan)
                                                        <tr>
                                                            <td> <a href="/loans/view/{{$loan['loan_ref']}}">{{$loan['customer_name']}}
                                                                </a>
                                                            </td>
                                                            <td>{{$loan['phone']}}</td>
                                                            <td>{{$loan['loan_ref']}}</td>
                                                            <td>{{$loan['loan_amount']}}</td>
                                                            <td>{{$loan['loan_balance']}}</td>
                                                            <td>{{$loan['disbursment_date']}}</td>
                                                            <td>{{$loan['principle']}}</td>
                                                            <td>{{$loan['interest']}}</td>
                                                            <td>{{$loan['admin_fee']}}</td>
                                                            <td>{{$loan['penalty_amount']}}</td>

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
                    <div class="tab-pane fade " id="groups">
                        <div class="row">
                            <!-- To be worked on -->
                            <div class="col-md-12">
                                <div class="tile">
                                    <h5 class="tile-title">Assigned Groups</h5>
                                    <div class="tile-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered datatable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Description</th>
                                                        <th>Assigned</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $count = 0; ?>
                                                    @foreach($data['assigned_groups'] as $one)
                                                    <?php $count++; ?>
                                                    <tr>
                                                        <td>{{$count}}</td>
                                                        <td>{{$one['title']}}</td>
                                                        <td>{{$one['description']}}</td>
                                                        <td>{{$one['created_at']}}</td>
                                                        <td>
                                                            <form method="POST" action="/savings/groups/remove/{{$data['customer']['id']}}/{{$one['id']}}">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="POST">
                                                                <button type="submit" class="btn-danger btn-flat show_confirm_group" data-toggle="tooltip" title='Unassign'> <i class="fa fa-trash"> </i></button>
                                                            </form>
                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        </div>

                                    </div>
                                    <div class="tile-footer">
                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#assignNewModal">
                                            <i class="fa fa-lg fa-plus"></i> Assign New Group</button>
                                        <!-- Start of Modal -->
                                        <div id="assignNewModal" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">Assign Group
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <form method="POST" action="/customer/assign/group/{{$data['customer']['id']}}" enctype="multipart/form-data">
                                                        @csrf

                                                        <div class="modal-body">

                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label class="control-label">Group Name</label>

                                                                    <select name="group_id" style="width: 100%;" required="required" class="form-control selector" id="exampleSelect1">
                                                                        @foreach($data['groups'] as $pr)
                                                                        <option value="{{$pr['id']}}">{{$pr['title']}} </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" type="submit">Submit</button>
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End of Modal -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade active show" id="profile">

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

                                    <div class="alert alert-dismissible alert-danger">
                                        <button class="close" type="button" data-dismiss="alert">×</button>
                                        <p>{{ session('error') }}</p>
                                    </div>
                                    @endif
                                    <div class="tile-body">

                                        <form method="POST" action="/customer/update/correct/{{ $data['customer']['id'] }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label">First Name</label>
                                                    <input name="firstname" value="{{ $data['customer']['firstname'] }}" class="form-control" required="required" type="text" placeholder="Enter First Name">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label">Last Name</label>
                                                    <input name="lastname" value="{{ $data['customer']['lastname'] }}" class="form-control" required="required" type="text" placeholder="Enter Last Name">
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label">Phone</label>
                                                    <input name="phone" value="{{ $data['customer']['phone'] }}" class="form-control" required="required" type="phone" placeholder="Enter Phone">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label">Email</label>
                                                    <input name="email" value="{{ $data['customer']['email'] }}" class="form-control" required="required" type="email" placeholder="Enter Email">
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label">ID Number</label>
                                                    <input name="national" value="{{ $data['customer']['national_id'] }}" class="form-control" required="required" type="number" placeholder="Enter ID Number">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label">Gender</label>
                                                    <select name="gender" required="required" class="form-control" id="exampleSelect1">
                                                        <option value="{{ $data['customer']['gender'] }}">{{ $data['customer']['gender'] }}</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label">Membership</label>
                                                    <input name="member" value="{{ $data['customer']['membership_no'] }}" class="form-control" required="required" type="text" placeholder="Enter Password">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label">Device Name</label>
                                                    <input name="devicename" value="{{ $data['customer']['devicename'] }}" class="form-control" required="required" type="text" placeholder="Enter Device">

                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <a href="/customer/approved"><button class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Back</button></a>
                                                </div>
                                                <div class="form-group col-md-3">

                                                    <a href="/customer/alerts-on/{{ $data['customer']['phone']}}">
                                                        <button class="btn btn-success" type="button">
                                                            @if($data['customer']['alerts_enabled'] == 1)
                                                            <i class="fa fa-fw  fa-bell-slash"></i>Alerts Off
                                                            @else
                                                            <i class="fa fa-fw  fa-bell"></i>Alerts On
                                                            @endif
                                                        </button></a>


                                                </div>

                                                <div class="form-group col-md-3">
                                                    <a href="/customer/resetpin/{{ $data['customer']['phone']}}"><button class="btn btn-warning" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Reset PIN</button></a>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update </button>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <a href="/customer/online/{{ $data['customer']['id']}}"><button class="btn btn-primary show_confirm_online" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Online Access</button></a>
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

                                        <form method="POST" action="/customer/update/{{ $data['customer']['id'] }}">
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
                                                <input name="loanlimit" value="{{ $data['customer']['loanlimit'] }}" class="form-control" required="required" type="number" placeholder="Enter Loan Limit">

                                            </div>

                                            <div class="form-group ">
                                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Approve Client</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="loans">
                        <div class="row">
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Request New Loan</button>
                                <div id="myModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header"> New Loan Application
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            </div>
                                            <form method="POST" action="/loans/user/{{$data['customer']['id']}}" enctype="multipart/form-data">
                                                @csrf

                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label">Phone</label>

                                                            <select name="phone" required="required" class="form-control" id="exampleSelect1">

                                                                <option value="{{$data['customer']['phone']}}">{{$data['customer']['phone']}} </option>

                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label">Loan Type</label>
                                                            <select name="loan_code" required="required" class="form-control" id="exampleSelect1">

                                                                @foreach($data['loantypes'] as $prod)
                                                                <option value="{{$prod['loan_code']}}">{{$prod['loan_name']}} </option>
                                                                @endforeach

                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label">Amount</label>
                                                            <input name="principle" class="form-control" required="required" type="number" placeholder="Enter Amount">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label">Disbursement Date</label>
                                                            <input class="form-control" autocomplete="off" name="startdate" required="required" id="startDate" type="text" placeholder="Select Date">
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-primary" type="submit">Submit</button>
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- End of Modal -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="tile p-0">
                                    <ul class="nav flex-column nav-tabs user-tabs">
                                        <li class="nav-item"><a class="nav-link active" href="#pending" data-toggle="tab">Pending</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#approved" data-toggle="tab">Approved</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="#paid" data-toggle="tab">Paid</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="#rejected" data-toggle="tab">Rejected</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#overdue" data-toggle="tab">Overdue</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="tab-content">
                                    <!-- Pending -->
                                    <div class="tab-pane active" id="pending">
                                        <div class="timeline-post">

                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered" id="datatable">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Phone</th>
                                                            <th>Reference</th>
                                                            <th>Loan Amount</th>
                                                            <th>Loan Balance</th>
                                                            <th>Disbursement Date</th>
                                                            <th>Principal</th>
                                                            <th>Interest</th>
                                                            <th>Admin Fee</th>
                                                            <th>Penalty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($data['pending'] as $loan)
                                                        <tr>
                                                            <td> <a href="/loans/edit/{{$loan['loan_ref']}}">{{$loan['customer_name']}}
                                                                </a>
                                                            </td>
                                                            <td>{{$loan['phone']}}</td>
                                                            <td>{{$loan['loan_ref']}}</td>
                                                            <td>{{$loan['loan_amount']}}</td>
                                                            <td>{{$loan['loan_balance']}}</td>
                                                            <td>{{$loan['disbursment_date']}}</td>
                                                            <td>{{$loan['principle']}}</td>
                                                            <td>{{$loan['interest']}}</td>
                                                            <td>{{$loan['admin_fee']}}</td>
                                                            <td>{{$loan['penalty_amount']}}</td>

                                                        </tr>
                                                        @endforeach
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Approved -->
                                    <div class="tab-pane fade" id="approved">
                                        <div class="timeline-post">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered" id="datatable1">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Phone</th>
                                                            <th>Reference</th>
                                                            <th>Loan Amount</th>
                                                            <th>Loan Balance</th>
                                                            <th>Disbursement Date</th>
                                                            <th>Principal</th>
                                                            <th>Interest</th>
                                                            <th>Admin Fee</th>
                                                            <th>Penalty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($data['approved'] as $loan)
                                                        <tr>
                                                            <td> <a href="/loans/view/{{$loan['loan_ref']}}">{{$loan['customer_name']}}
                                                                </a>
                                                            </td>
                                                            <td>{{$loan['phone']}}</td>
                                                            <td>{{$loan['loan_ref']}}</td>
                                                            <td>{{$loan['loan_amount']}}</td>
                                                            <td>{{$loan['loan_balance']}}</td>
                                                            <td>{{$loan['disbursment_date']}}</td>
                                                            <td>{{$loan['principle']}}</td>
                                                            <td>{{$loan['interest']}}</td>
                                                            <td>{{$loan['admin_fee']}}</td>
                                                            <td>{{$loan['penalty_amount']}}</td>

                                                        </tr>
                                                        @endforeach
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Paid -->
                                    <div class="tab-pane fade" id="paid">
                                        <div class="timeline-post">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered" id="datatable2">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Phone</th>
                                                            <th>Reference</th>
                                                            <th>Loan Amount</th>
                                                            <th>Loan Balance</th>
                                                            <th>Disbursement Date</th>
                                                            <th>Principal</th>
                                                            <th>Interest</th>
                                                            <th>Admin Fee</th>
                                                            <th>Penalty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($data['paid'] as $loan)
                                                        <tr>
                                                            <td> <a href="/loans/view/{{$loan['loan_ref']}}">{{$loan['customer_name']}}
                                                                </a>
                                                            </td>
                                                            <td>{{$loan['phone']}}</td>
                                                            <td>{{$loan['loan_ref']}}</td>
                                                            <td>{{$loan['loan_amount']}}</td>
                                                            <td>{{$loan['loan_balance']}}</td>
                                                            <td>{{$loan['disbursment_date']}}</td>
                                                            <td>{{$loan['principle']}}</td>
                                                            <td>{{$loan['interest']}}</td>
                                                            <td>{{$loan['admin_fee']}}</td>
                                                            <td>{{$loan['penalty_amount']}}</td>

                                                        </tr>
                                                        @endforeach
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Rejected -->
                                    <div class="tab-pane fade" id="rejected">
                                        <div class="timeline-post">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered" id="datatable3">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Phone</th>
                                                            <th>Reference</th>
                                                            <th>Loan Amount</th>
                                                            <th>Loan Balance</th>
                                                            <th>Disbursement Date</th>
                                                            <th>Principal</th>
                                                            <th>Interest</th>
                                                            <th>Admin Fee</th>
                                                            <th>Penalty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($data['rejected'] as $loan)
                                                        <tr>
                                                            <td> <a href="/loans/view/{{$loan['loan_ref']}}">{{$loan['customer_name']}}
                                                                </a>
                                                            </td>
                                                            <td>{{$loan['phone']}}</td>
                                                            <td>{{$loan['loan_ref']}}</td>
                                                            <td>{{$loan['loan_amount']}}</td>
                                                            <td>{{$loan['loan_balance']}}</td>
                                                            <td>{{$loan['disbursment_date']}}</td>
                                                            <td>{{$loan['principle']}}</td>
                                                            <td>{{$loan['interest']}}</td>
                                                            <td>{{$loan['admin_fee']}}</td>
                                                            <td>{{$loan['penalty_amount']}}</td>

                                                        </tr>
                                                        @endforeach
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Overdue -->
                                    <div class="tab-pane fade" id="overdue">
                                        <div class="timeline-post">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered" id="datatable4">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Phone</th>
                                                            <th>Reference</th>
                                                            <th>Loan Amount</th>
                                                            <th>Loan Balance</th>
                                                            <th>Disbursement Date</th>
                                                            <th>Principal</th>
                                                            <th>Interest</th>
                                                            <th>Admin Fee</th>
                                                            <th>Penalty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($data['overdue'] as $loan)
                                                        <tr>
                                                            <td> <a href="/loans/view/{{$loan['loan_ref']}}">{{$loan['customer_name']}}
                                                                </a>
                                                            </td>
                                                            <td>{{$loan['phone']}}</td>
                                                            <td>{{$loan['loan_ref']}}</td>
                                                            <td>{{$loan['loan_amount']}}</td>
                                                            <td>{{$loan['loan_balance']}}</td>
                                                            <td>{{$loan['disbursment_date']}}</td>
                                                            <td>{{$loan['principle']}}</td>
                                                            <td>{{$loan['interest']}}</td>
                                                            <td>{{$loan['admin_fee']}}</td>
                                                            <td>{{$loan['penalty_amount']}}</td>

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
                    <div class="tab-pane fade" id="savings">
                        <div class="row">
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-4">
                                <div class="widget-small info coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
                                    <div class="info">
                                        <h5>Total Savings</h5>
                                        <h4>{{$data['save']['amount']}}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4"> <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addSavings"><i class="fa fa-lg fa-plus"></i> Add Savings</button>
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#withdrawCash"><i class="fa fa-lg fa-plus"></i> Initiate Withdrawal</button>
                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#statementModal"><i class="fa fa-lg fa-file-pdf-o"></i>Generate Statement</button>


                                <!-- Statement Modal -->
                                <!-- Statement Modal -->
                                <div id="statementModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Saving Statement</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            </div>
                                            <form method="POST" action="/savings/statement/{{$data['customer']['phone']}}/{{$data['customer']['id']}}" enctype="multipart/form-data">
                                                @csrf

                                                <div class="modal-body">

                                                    <div class="row">
                                                        <p>Savings statement for {{$data['customer']['firstname']}} {{$data['customer']['lastname']}} will be generated</p>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-primary" type="submit">Generate</button>
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- End Modal -->


                                <!-- Start of Deposits Modal -->
                                <div id="addSavings" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header"> New Saving
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            </div>
                                            <form method="POST" action="/savings/user/{{$data['customer']['id']}}" enctype="multipart/form-data">
                                                @csrf

                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label">Phone</label>
                                                            <input name="phone" value="{{$data['customer']['phone']}}" class="form-control" required="required" type="text" placeholder="Enter Phone">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label">Saving Product</label>
                                                            <select name="product" required="required" class="form-control" id="exampleSelect1">

                                                                @foreach($data['products'] as $prod)
                                                                <option value="{{$prod['product_code']}}">{{$prod['product_name']}}
                                                                </option>
                                                                @endforeach

                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label">Amount</label>
                                                            <input name="amount" class="form-control" required="required" type="number" placeholder="Enter Amount">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label">Reference</label>
                                                            <input name="code" class="form-control" required="required" type="text" placeholder="Enter Reference Code">
                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button class="btn btn-primary" type="submit">Submit</button>
                                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                                    </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Deposits Modal -->
                            <!-- Start of Withdrawals Modal -->
                            <div id="withdrawCash" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header"> Initiate Withdrawal
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                        </div>
                                        <form method="POST" action="/savings/initiate/user/{{$data['customer']['id']}}" enctype="multipart/form-data">
                                            @csrf

                                            <div class="modal-body">

                                                <div class="form-group col-md-12">
                                                    <label class="control-label">Phone</label>
                                                    <select name="phone" required="required" style="width: 82%" class="form-control" id="demoSelect">

                                                        <option value="{{$data['customer']['phone']}}">
                                                            {{$data['customer']['phone']}}-{{$data['customer']['firstname']}} {{$data['customer']['lastname']}}
                                                        </option>

                                                    </select>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label class="control-label">Product</label>
                                                    <select name="product" required="required" style="width: 95%" class="form-control col-md-11" id="demoSelect2">

                                                        @foreach($data['current_products'] as $prod)
                                                        <option value="{{$prod['product_code']}}">{{$prod['product_name']}}
                                                        </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div class="form-group col-md-11">
                                                    <label class="control-label">Amount</label>
                                                    <input name="amount" class="form-control" required="required" type="number" placeholder="Enter Amount">
                                                </div>



                                                <div class="modal-footer">
                                                    <button class="btn btn-primary" type="submit">Submit</button>
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- End of Withdrawals Modal -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="tile p-0">
                            <ul class="nav flex-column nav-tabs user-tabs">
                                <li class="nav-item"><a class="nav-link active" href="#deposits" data-toggle="tab">Savings Deposits</a></li>
                                <li class="nav-item"><a class="nav-link" href="#psummary" data-toggle="tab">Product Summary</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#pwith" data-toggle="tab">Pending Withdrawals</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#awith" data-toggle="tab">Approved Withdrawals</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="tab-content">
                            <!-- Pending -->
                            <div class="tab-pane active" id="deposits">
                                <div class="timeline-post">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered" id="datatable5">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Reference</th>
                                                    <th>Product</th>
                                                    <th>Amount</th>
                                                    <th>Total Amount</th>
                                                    <th>Date</th>

                                                </tr>

                                            </thead>
                                            <tbody>
                                                <?php $count = 0; ?>
                                                @foreach($data['savings'] as $saving)
                                                <?php $count++; ?>
                                                <tr>
                                                    <td>{{$count}}</td>
                                                    <td>{{$saving['reference']}}</td>
                                                    <td>{{$saving['product_name']}}</td>
                                                    <td>{{$saving['amount']}}</td>
                                                    <td>{{$saving['total']}}</td>
                                                    <td>{{$saving['timestamp']}}</td>
                                                </tr>
                                                @endforeach

                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="psummary">
                                <div class="timeline-post">
                                    <div class="table-responsive">

                                        <table class="table table-hover table-bordered" id="now">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Reference</th>
                                                    <th>Name</th>
                                                    <th>Amount</th>

                                                </tr>

                                            </thead>
                                            <tbody>
                                                <?php $count = 0; ?>
                                                @foreach($data['current_products'] as $sm)
                                                <?php $count++; ?>
                                                <tr>
                                                    <td>{{$count}}</td>
                                                    <td>{{$sm['product_code']}}</td>
                                                    <td>{{$sm['product_name']}}</td>
                                                    <td>{{$sm['revenue']}}</td>
                                                </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="pwith">
                                <div class="timeline-post">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered" id="with">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Request Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $count = 0; ?>
                                                @foreach($data['pwith'] as $with)
                                                <?php $count++; ?>
                                                <tr>
                                                    <td>{{$count}}</td>
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
                                                            <form method="POST" action="/savings/approve/user/withdrawal/{{$with['reference']}}/{{$data['customer']['id']}}">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="POST">
                                                                <button type="submit" class="btn-success btn-flat show_confirm_approve" data-toggle="tooltip" title='Aprrove'> <i class="fa fa-check">
                                                                    </i></button>
                                                            </form>



                                                            <form method="POST" action="/savings/reject/user/withdrawal/{{$with['reference']}}/{{$data['customer']['id']}}">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="POST">
                                                                <button type="submit" class="btn-danger btn-flat show_confirm" data-toggle="tooltip" title='Reject'> <i class="fa fa-trash">
                                                                    </i></button>
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
                            <div class="tab-pane" id="awith">
                                <div class="timeline-post">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered" id="now">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Request Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $count = 0; ?>
                                                @foreach($data['awith'] as $with)
                                                <?php $count++; ?>
                                                <tr>
                                                    <td>{{$count}}</td>
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

                                                        <div class="btn-group"><a class="btn btn-success" href="#"><i class="fa fa-lg fa-eye"></i></a></div>
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
        $('.show_confirm_group').click(function(e) {
            if (!confirm('Are you sure you want to remove group?')) {
                e.preventDefault();
            }
        });
    </script>
    @endsection