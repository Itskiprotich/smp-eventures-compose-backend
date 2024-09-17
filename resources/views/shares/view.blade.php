@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-money"></i> Shares</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">View Shares</a></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h5 class="tile-title"> Details</h5>
            <div class="tile-body">

                <form method="POST" action="/customer/register">
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
                            <label class="control-label">Membership</label>
                            <input name="membership" value="{{ $data['membership_no'] }}" class="form-control" required="required" type="email" placeholder="Enter Membership">
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
            <h5 class="tile-title">Shares History</h5>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>

                                <th>Phone</th>
                                <th>Reference</th>
                                <th>Amount</th>
                                <th>Total Amount</th>
                                <th>Date</th>

                            </tr>

                        </thead>
                        <tbody>
                            @foreach($shares as $saving)
                            <tr>

                                <td>{{$saving['phone']}}</td>
                                <td>{{$saving['reference']}}</td>
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