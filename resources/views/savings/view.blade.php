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
            <h5 class="tile-title"> Details</h5>
            <div class="tile-body">

                <form method="POST" action="#">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">First Name</label>
                            <input name="firstname" disabled value="{{ $data['firstname'] }}" class="form-control" required="required" type="text" placeholder="Enter First Name">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Last Name</label>
                            <input name="lastname" disabled value="{{ $data['lastname'] }}" class="form-control" required="required" type="text" placeholder="Enter Last Name">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Phone</label>
                            <input name="phone" disabled value="{{ $data['phone'] }}" class="form-control" required="required" type="phone" placeholder="Enter Phone">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Membership</label>
                            <input name="membership" disabled value="{{ $data['membership_no'] }}" class="form-control" required="required" type="email" placeholder="Enter Membership">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Total Savings</label>
                            <input name="phone" disabled value="{{ $data['total_savings'] }}" class="form-control" required="required" type="text" placeholder="">
                        </div>
                    </div>


                </form>
            </div>
            <div class="tile-footer">
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#statementModal"><i class="fa fa-lg fa-file-pdf-o"></i>Generate Statement</button>
                        <!-- Statement Modal -->
                        <div id="statementModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Saving Statement</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                    </div>
                                    <form method="POST" action="/savings/statement/{{$data['phone']}}/{{$data['id']}}" enctype="multipart/form-data">
                                        @csrf

                                        <div class="modal-body">

                                            <div class="row">
                                                <p>Savings statement for {{$data['firstname']}} {{$data['lastname']}} will be generated</p>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" type="submit" >Generate</button>
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- End Modal -->
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="tile">
            <h5 class="tile-title">Summary</h5>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="mysave">
                        <thead>
                            <tr>


                                <th>Reference</th>
                                <th>Name</th>
                                <th>Amount</th>

                            </tr>

                        </thead>
                        <tbody>
                            @foreach($savingsproducts as $sm)
                            <tr>

                                <td>{{$sm['product_code']}}</td>
                                <td>{{$sm['product_name']}}</td>
                                <td>{{$sm['revenue']}}</td>
                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
            <div class="tile-footer">
                <div class="row">
                    <div class="col-md-6">

                    </div>
                    <div class="col-md-6">

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
            <h5 class="tile-title"> Earnings</h5>
            <div class="tile-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label">Current Balance</label>
                        <input name="current" disabled value="{{ $data['current'] }}" class="form-control" required="required" type="text" placeholder="0">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">Available Balance</label>
                        <input name="available" disabled value="{{ $data['available'] }}" class="form-control" required="required" type="text" placeholder="0">
                    </div>

                </div>

            </div>
            <div class="tile-footer">
                <div class="row">
                    <div class="form-group col-md-8 align-self-end">
                        <div class="btn-group">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-refresh"></i> Process Earnings </button>

                            <!-- Start of Modal  -->
                            <div id="myModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Process Earnings</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                        </div>
                                        <form method="POST" action="/savings/payment/{{$data['phone']}}" enctype="multipart/form-data">
                                            @csrf

                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="control-label">Amount</label>
                                                        <input name="amount" class="form-control" required="required" type="number" placeholder="Enter Amount">
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
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h5 class="tile-title">Customer Savings</h5>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered datatable">
                        <thead>
                            <tr>
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

                                <td>{{$saving['reference']}}</td>
                                <td>{{$saving['product_name']}}</td>
                                <td>{{$saving['amount']}}</td>
                                <td>{{$saving['total']}}</td>
                                <td>{{$saving['saved']}}</td>
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
    <div class="col-md-8">
        <div class="tile">
            <h5 class="tile-title">Withdrawals</h5>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered datatable">
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
                            <?php $c = 0; ?>
                            @foreach($withdrawals as $saving)
                            <?php $c++; ?>
                            <tr>
                                <td>{{$c}}</td>
                                <td>{{$saving['reference']}}</td>
                                <td>{{$saving['product_name']}}</td>
                                <td>{{$saving['amount']}}</td>
                                <td>{{$saving['total']}}</td>
                                <td>{{$saving['tolewa']}}</td>
                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="tile">
            <h5 class="tile-title">Interest</h5>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reference</th> 
                                <th>Amount</th> 
                                <th>Date</th>

                            </tr>

                        </thead>
                        <tbody>
                            <?php $c = 0; ?>
                            @foreach($interes as $saving)
                            <?php $c++; ?>
                            <tr>
                                <td>{{$c}}</td>
                                <td>{{$saving['reference']}}</td> 
                                <td>{{$saving['available'] * -1}}</td> 
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