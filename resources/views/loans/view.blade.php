@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-money"></i> Loans</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">View Loan</a></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-8">
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

            <div class="tile-title-w-btn">
                <h6 class="title">Loan Details- @if($data['paused'] == 1)

                    <span class="badge badge-warning">Penalty Paused</span>

                    @else
                    <span class="badge badge-success">Penalty Active</span>
                    @endif
                </h6>

                <div class="btn-group">
                    <button class="btn btn-success" data-toggle="modal" data-target="#statementModal"><i class="fa fa-lg fa-file-pdf-o"></i> Generate Statement </button>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#editLoan"><i class="fa fa-lg fa-edit"></i> Edit Loan </button>
                    <button class="btn btn-info" data-toggle="modal" data-target="#topupLoan"><i class="fa fa-lg fa-upload"></i> Topup Loan </button>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Waive Penalty/Loan </button>
                </div>
                <!-- Statement Modal -->
                <div id="statementModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Loan Statement</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/loans/generate/{{$data['loan_ref']}}" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    <div class="row">
                                       <p>Loan statement for {{$data['customer_name']}} will be generated</p>

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
                <!-- End of statement Modal -->

                <!-- Topup Loan -->

                <!-- End of Topup -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Waive Penalty/Loan</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/loans/waive/{{$data['loan_ref']}}" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="control-label">Amount</label>
                                            <input name="amount" class="form-control" required="required" type="number" placeholder="Enter Amount">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="control-label">Reason *</label>
                                            <textarea name="reason" required class="form-control" id="exampleTextarea" rows="3"></textarea>

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
                <!-- Modal div -->
                <div id="topupLoan" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Topup Loan</h6>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/loans/topup/{{$data['loan_ref']}}" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="control-label">Amount</label>
                                            <input name="amount" class="form-control" required="required" type="number" placeholder="Enter Amount">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="control-label">Duration</label>
                                            <select name="duration" required="required" class="form-control" id="exampleSelect1">

                                                <option value="0">None</option>
                                                <option value="7">1 Week</option>
                                                <option value="14">2 Weeks</option>
                                                <option value="21">3 Weeks</option>
                                                <option value="28">1 Month</option>
                                                <option value="56">2 Months</option>
                                                <option value="84">3 Months</option>
                                                <option value="168">6 Months</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="control-label">Reason</label>
                                            <textarea name="reason" required class="form-control" id="exampleTextarea" rows="3"></textarea>
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
            </div>
            <div id="editLoan" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Edit Loan</h6>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                        </div>
                        <form method="POST" action="/loans/correct" enctype="multipart/form-data">
                            @csrf

                            <div class="modal-body">

                                <div class="form-group">
                                    <label class="control-label">Reference</label>
                                    <input name="loan_ref" value="{{$data['loan_ref']}}" enabled class="form-control" required="required" type="text" placeholder="Enter Reference">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Loan Type</label>
                                    <select name="loan_code" required="required" class="form-control" id="exampleSelect1">

                                        @foreach($loantypes as $prod)
                                        <option value="{{$prod['loan_code']}}">{{$prod['loan_name']}} </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Principal</label>
                                    <input name="amount" class="form-control" required="required" type="number" placeholder="Enter Amount">
                                </div>
                                <div class="form-group ">
                                    <label class="control-label">Reason *</label>
                                    <textarea name="reason" required class="form-control" id="exampleTextarea" rows="3"></textarea>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit">Submit</button>
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Start of Topup -->

                <div id="editLoan" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Edit Loan</h6>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/loans/correct" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    <div class="form-group">
                                        <label class="control-label">Reference</label>
                                        <input name="loan_ref" value="{{$data['loan_ref']}}" enabled class="form-control" required="required" type="text" placeholder="Enter Reference">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Loan Type</label>
                                        <select name="loan_code" required="required" class="form-control" id="exampleSelect1">

                                            @foreach($loantypes as $prod)
                                            <option value="{{$prod['loan_code']}}">{{$prod['loan_name']}} </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Principal</label>
                                        <input name="amount" class="form-control" required="required" type="number" placeholder="Enter Amount">
                                    </div>
                                    <div class="form-group ">
                                        <label class="control-label">Reason *</label>
                                        <textarea name="reason" required class="form-control" id="exampleTextarea" rows="3"></textarea>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End of Topup -->
                </div>
            </div>
            <div class="tile-body">

                <form method="POST" action="/loans/update">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Reference</label>
                            <input name="reference" value="{{$data['loan_ref']}}" class="form-control" required="required" type="text" placeholder="Enter Reference">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label"> Name</label>
                            <input name="name" value="{{$data['customer_name']}}" class="form-control" required="required" type="text" placeholder="Enter Name">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Phone</label>
                            <input name="phone" value="{{$data['phone']}}" class="form-control" required="required" type="text" placeholder="Enter Phone">
                        </div>

                    </div>

                    <div class="row">

                        <div class="form-group col-md-4">
                            <label class="control-label">Principal</label>
                            <input name="principal" value="{{$data['principle']}}" class="form-control" required="required" type="text" placeholder="Enter Principal">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Interest</label>
                            <input name="national" value="{{$data['interest']}}" class="form-control" required="required" type="number" placeholder="Enter Interest">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Admin Fee</label>
                            <input name="national" value="{{$data['admin_fee']}}" class="form-control" required="required" type="number" placeholder="Enter Admin Fee">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Loan Amount</label>
                            <input name="loan_amount" value="{{$data['loan_amount']}}" class="form-control" required="required" type="text" placeholder="Enter Loan Amount">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Loan Balance</label>
                            <input name="loan_balance" value="{{$data['loan_balance']}}" class="form-control" required="required" type="text" placeholder="Enter Loan Balance">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Duration</label>
                            <input name="loan_duration" value="{{$data['repayment_period']}}" class="form-control" required="required" type="text" placeholder="Enter Duration">
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Repayment Date</label>
                            <input name="loan_balance" value="{{$data['repayment_date']}}" class="form-control" required="required" type="text" placeholder="Enter Repayment Date">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Penalty Amount</label>
                            <input name="loan_duration" value="{{$data['penalty_amount']}}" class="form-control" required="required" type="text" placeholder="Enter Duration">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Next Penalty Date</label>
                            <input name="loan_balance" value="{{$data['penalty_date']}}" class="form-control" required="required" type="text" placeholder="Enter Repayment Date">
                        </div>

                    </div>
                </form>
            </div>

        </div>

    </div>
    <div class="col-md-4">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h6 class="tile-title">Guarantors </h6>
                <div class="btn-group">
                    <a href="/downloads/loan/{{$data['loan_ref']}}" target="_blank" class="btn btn-primary"><i class="fa fa-lg fa-file-pdf-o"></i>Download</a>

                </div>

            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatble">
                        <thead>
                            <tr>

                                <th>Phone</th>
                                <th>Name</th>
                                <th>Added</th>

                            </tr>

                        </thead>
                        <tbody>
                            @foreach($guarantors as $gr)
                            <tr>

                                <td>{{$gr['phonenumber']}}</td>
                                <td>{{$gr['firstname']}} {{$gr['lastname']}}</td>
                                <td>{{$gr['created_at']}}</td>


                            </tr>
                            @endforeach


                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="tile">
            <h6 class="tile-title">Loan Schedule</h6>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>

                                <th>Phone</th>
                                <th>Reference</th>
                                <th>Amount</th>
                                <th>Due Date</th>

                            </tr>

                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                            <tr>

                                <td>{{$schedule['phone']}}</td>
                                <td>{{$schedule['loan_ref']}}</td>
                                <td>{{$schedule['amount']}}</td>
                                <td>{{$schedule['due_date']}}</td>


                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="tile">
            <h6 class="tile-title">Loan Repayments</h6>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="repayments">
                        <thead>
                            <tr>

                                <th>Date</th>
                                <th>Reference</th>
                                <th>Amount</th>
                                <th>Date Paid</th>
                                <th>Balance</th>
                                <th>Initiator</th>

                            </tr>

                        </thead>
                        <tbody>
                            @foreach($repayments as $repayment)
                            <tr>

                                <td>{{$repayment['date_paid']}}</td>
                                <td>{{$repayment['reference']}}</td>
                                <td>{{$repayment['paid_amount']}}</td>
                                <td>{{$repayment['date_paid']}}</td>
                                <td>{{$repayment['balance']}}</td>
                                <td>{{$repayment['initiator']}}</td>
                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="tile">

            <div class="tile-title-w-btn">
                <h6>Notes</h6>

                <div class="btn-group">
                    @if($data['paused'] == 1)
                    <button class="btn btn-success" data-toggle="modal" data-target="#activateModal">
                        <i class="fa fa-lg fa-power-off"></i> Activate Penalty
                    </button>
                    @else
                    <button class="btn btn-warning" data-toggle="modal" data-target="#pauseModal">
                        <i class="fa fa-lg fa-plus"></i> Pause Penalty
                    </button>
                    @endif
                    <button class="btn btn-primary" data-toggle="modal" data-target="#noteModal">
                        <i class="fa fa-lg fa-plus"></i> Add Note
                    </button>
                </div>
                <div id="noteModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Add Note</h6>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/loans/note/{{$data['loan_ref']}}" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">
                                    <div class="form-group col-md-12">
                                        <label class="control-label">Description *</label>
                                        <textarea name="description" required class="form-control" id="exampleTextarea" rows="3"></textarea>
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
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reference</th>
                                <th>Description</th>
                                <th>Created</th>

                            </tr>

                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach($notes as $note)
                            <?php $i++; ?>
                            <tr>

                                <td>{{$i}}</td>
                                <td>{{$note['loan_ref']}}</td>
                                <td>{{$note['description']}}</td>
                                <td>{{$note['created_at']}}</td>


                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <div id="pauseModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Pause Penalty</h6>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form method="POST" action="/loans/pause/{{$data['loan_ref']}}" enctype="multipart/form-data">
                            @csrf

                            <div class="modal-body">
                                <div class="form-group col-md-12">
                                    <label class="control-label">Reason *</label>
                                    <textarea name="description" required class="form-control" id="exampleTextarea" rows="3"></textarea>
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

            <div id="activateModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Activate Penalty</h6>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                        </div>
                        <form method="POST" action="/loans/activate/{{$data['loan_ref']}}" enctype="multipart/form-data">
                            @csrf

                            <div class="modal-body">
                                <div class="form-group col-md-12">
                                    <label class="control-label">Reason *</label>
                                    <textarea name="description" required class="form-control" id="exampleTextarea" rows="3"></textarea>
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
    @if(count($top_logs) > 0)
    <div class="col-md-6">
        <div class="tile">

            <div class="tile-title-w-btn">
                <h6>Top Up History</h6>
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reference</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Created</th>

                            </tr>

                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach($top_logs as $log)
                            <?php $i++; ?>
                            <tr>

                                <td>{{$i}}</td>
                                <td> <a href="#">{{$log['phone']}}</a></td>
                                <td>{{$log['title']}}</td>
                                <td>{{$log['body']}}</td>
                                <td>{{$log['created_at']}}</td>


                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@if(count($waive_logs) > 0)
<div class="row">
<div class="col-md-6">
        <div class="tile">

            <div class="tile-title-w-btn">
                <h6>Waive History</h6>
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reference</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Created</th>

                            </tr>

                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach($waive_logs as $log)
                            <?php $i++; ?>
                            <tr>

                                <td>{{$i}}</td>
                                <td> <a href="#">{{$log['phone']}}</a></td>
                                <td>{{$log['title']}}</td>
                                <td>{{$log['body']}}</td>
                                <td>{{$log['created_at']}}</td>


                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection