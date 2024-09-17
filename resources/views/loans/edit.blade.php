@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-money"></i> Loans</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Edit Loan</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->

    <div class="col-md-8">
        <div class="tile">
            <h5 class="tile-title">Loan Details</h5>
            <div class="tile-body">

                <!-- <form method="POST" action="/loans/update"> -->
                <form>
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label"> Name</label>
                            <input name="name" value="{{$data['customer_name']}}" class="form-control" required="required" type="text" placeholder="Enter Name">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Phone</label>
                            <input name="phone" value="{{$data['phone']}}" class="form-control" required="required" type="text" placeholder="Enter Phone">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Reference</label>
                            <input name="reference" value="{{$data['loan_ref']}}" class="form-control" required="required" type="text" placeholder="Enter Reference">
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

                    </div>

                </form>
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
            <h5 class="tile-title">Action </h5>
            <div class="tile-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <form method="POST" action="/loans/reject/{{ $data['loan_ref'] }}">
                            @csrf
                            <input name="_method" type="hidden" value="POST">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-danger reject_confirm" data-toggle="tooltip" title='Reject '> <i class="fa fa-trash-o">Reject </i></button>
                            </div>
                        </form>
                    </div>
                    <div class="form-group col-md-4">

                        <div class="btn-group"><button class="btn btn-primary" data-toggle="modal" data-target="#guarantor"><i class="fa fa-lg fa-edit"></i> Guarantor </button> </div>


                    </div>
                    <div class="form-group col-md-4">
                        <form method="POST" action="/loans/update/confirm/{{ $data['loan_ref'] }}">
                            @csrf
                            <input name="_method" type="hidden" value="POST">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-success approve_confirm" data-toggle="tooltip" title='Approve '> <i class="fa fa-check-circle">Approve </i></button>
                            </div>
                        </form>

                    </div>


                </div>
                <div class="row">
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

    <!-- Guarantor -->
    <div id="guarantor" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Guarantor</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="/loans/assign/{{ $data['loan_ref'] }}" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">

                        <div class="form-group ">

                            <label class="control-label">Select Member</label>
                            <select name="guarantor" required="required" class="form-control" style="width: 80%;" id="demoSelect">

                                @foreach($all as $customer)
                                <option value="{{$customer['phone']}}"> {{$customer['phone']}} -{{$customer['firstname']}} {{$customer['lastname']}}</option>
                                @endforeach
                            </select>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Assign</button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>


                    </div>

                </form>

            </div>
        </div>
    </div>
    <!-- End Guarantor -->
</div>


<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h5 class="tile-title">Loan Schedule</h5>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>

                                <th>Phone</th>
                                <th>Reference</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Paid Amount</th>

                            </tr>

                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                            <tr>

                                <td>{{$schedule['phone']}}</td>
                                <td>{{$schedule['loan_ref']}}</td>
                                <td>{{$schedule['amount']}}</td>
                                <td>{{$schedule['due_date']}}</td>
                                <td>{{$schedule['paid_amount']}}</td>


                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>


</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    $('.reject_confirm').click(function(e) {
        if (!confirm('Are you sure you want to reject this loan?')) {
            e.preventDefault();
        }
    });
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    $('.approve_confirm').click(function(e) {
        if (!confirm('Are you sure you want to approve this loan?')) {
            e.preventDefault();
        }
    });
</script>

@endsection