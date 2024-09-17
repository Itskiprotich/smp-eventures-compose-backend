@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-money"></i> Savings</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">View Savings</a></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="tile">
            <h6 class="tile-title"></h6>


            @if (session()->has('error'))
            <div class="alert alert-dismissible alert-danger">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('error') }}</p>
            </div>
            @endif
            <div class="tile-body">

                <form method="POST" action="/repayments/update/savings">
                    @csrf
                    <div class="form-group ">
                        <label class="control-label">Phone number</label>
                        <input name="phone" value="{{$data['phone']}}" class="form-control" required="required" type="text" placeholder="Enter Category Name">

                    </div>
                    <div class="form-group ">
                        <label class="control-label">Amount</label>
                        <input name="amount" class="form-control" required="required" type="text" placeholder="Enter Amount">

                    </div>
                    <div class="form-group ">
                        <label class="control-label">Transaction Code</label>
                        <input name="trans_code" class="form-control" required="required" type="text" placeholder="Enter Transaction Code">

                    </div>
                    <div class="form-group ">
                        <!-- <div class="form-group col-md-4 align-self-end"> -->
                        <button class="btn btn-primary" type="submit">Approve Payment</button>
                        <!-- </div> -->
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
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
            <h5 class="tile-title">Customer Savings</h5>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>

                                <th>Phone</th>
                                <th>Reference</th>
                                <th>Product</th>
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
                                <td>{{$saving['product_name']}}</td>
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