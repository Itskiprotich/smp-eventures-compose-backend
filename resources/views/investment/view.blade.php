@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-money"></i> Investment</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">View Investment</a></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="tile">
            @if (session()->has('wsuccess'))
            <div class="alert alert-dismissible alert-success">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('wsuccess') }}</p>
            </div>
            @endif
            @if (session()->has('werror'))
            <div class="alert alert-dismissible alert-danger">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('werror') }}</p>
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

                <form method="POST" action="">
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
                            <label class="control-label">Email</label>
                            <input name="email" value="{{ $data['email_address'] }}" class="form-control" required="required" type="email" placeholder="Enter Email Address">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Phone</label>
                            <input name="phone" value="{{ $data['phone'] }}" class="form-control" required="required" type="number" placeholder="Enter Phone Number">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Float</label>
                            <input name="float_balance" value="{{ $data['float_balance'] }}" class="form-control" required="required" type="number" placeholder="">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Interest</label>
                            <input name="interest_balance" value="{{ $data['interest_balance'] }}" class="form-control" required="required" type="number" placeholder="">
                        </div>

                    </div>


                </form>
                <div class="form-group col-md-4">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                        <i class="fa fa-lg fa-minus-square"></i> Withdraw
                    </button>
                </div>

                <!-- Modal div -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Cash Withdrawal</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/investment/withdrawal/{{ $data['phone'] }}" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    <div class="row">

                                        <div class="form-group col-md-12">
                                            <label class="control-label">Source</label>
                                            <select name="source" required="required" class="form-control" id="exampleSelect1">

                                                <option value="true">Float Balance</option>
                                                <option value="false">Interest Balance</option>


                                            </select>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="control-label">Amount</label>
                                            <input name="amount" class="form-control" required="required" type="number" placeholder="Enter Amount">
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
        </div>
    </div>
</div>
<div class="col-md-4">
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
        <h5 class="tile-title"> Cash Deposit</h5>
        <div class="tile-body">

            <form method="POST" action="/investment/deposit/{{ $data['phone'] }}">
                @csrf
                <div class="form-group">
                    <label class="control-label">Amount</label>
                    <input name="amount" class="form-control" required="required" type="number" placeholder="Enter Amount">
                </div>
                <div class="form-group ">
                    <label class="control-label">Reference</label>
                    <input name="reference" class="form-control" required="required" type="text" placeholder="Enter Reference">
                </div>

                <div class="form-group ">
                    <label class="control-label">Narration</label>
                    <textarea name="narration" required="required" class="form-control"> </textarea>

                </div>


                <div class="row">

                    <div class="form-group col-md-5 ">
                        <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Approve</button>
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
            <h5 class="tile-title">Investments</h5>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>

                                <th>Phone</th>
                                <th>Reference</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Total Amount</th>
                                <th>Date</th>

                            </tr>

                        </thead>
                        <tbody>
                            @foreach($savings as $saving)
                            <tr>

                                <td>{{$saving['phone']}}</td>
                                <td>{{$saving['reference']}}</td>
                                <td>{{$saving['description']}}</td>
                                <td>{{$saving['amount']}}</td>
                                <td>{{$saving['total']}}</td>
                                <td>{{$saving['created_at']}}</td>
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