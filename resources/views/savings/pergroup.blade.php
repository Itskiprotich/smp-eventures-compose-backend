@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i> Savings</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Customer Savings</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
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
            <div class="tile-title-w-btn">
                <h3 class="title"></h3>

                <div class="btn-group"><button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i
                            class="fa fa-lg fa-plus"></i> Add Savings Deposit </button> </div>

                <!-- Modal div -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Missing Payment</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/savings/manual" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Phone</label>
                                            <input name="phone" class="form-control" required="required" type="text"
                                                placeholder="Enter Phone">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Saving Product</label>
                                            <select name="product" required="required" class="form-control"
                                                id="exampleSelect1">

                                                @foreach($products as $prod)
                                                <option value="{{$prod['product_code']}}">{{$prod['product_name']}}
                                                </option>
                                                @endforeach

                                            </select>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Amount</label>
                                            <input name="amount" class="form-control" required="required" type="number"
                                                placeholder="Enter Amount">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Reference</label>
                                            <input name="code" class="form-control" required="required" type="text"
                                                placeholder="Enter Reference Code">
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                        <button class="btn btn-secondary" type="button"
                                            data-dismiss="modal">Close</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tile-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th style="width: 3%;">#</th>
                            <th>Name</th>
                            <th>Phone</th>  
                            <th>Group</th>
                            <th>Product</th>
                            <th>Amount</th>  
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count=0;?>
                        @foreach($savings as $saving)
                        <?php $count++;?>
                        <tr>
                            <td>{{$count}}</td>
                            <td><a href="/savings/view/{{$saving->phone}}">{{$saving->name}}</a></td>
                            <td>{{$saving->phone}}</td>
                            <td>{{$saving->group_name}}</td>   
                            <td>{{$saving->product_name}}</td>  
                            <td>{{$saving->amount}}</td>       
                            <td>{{$saving->created}}</td>  

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