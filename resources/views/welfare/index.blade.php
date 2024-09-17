@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-user"></i> Members</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">All Members</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-12">
        <div class="tile">

            <div class="tile-title-w-btn">
                <h3 class="title"></h3>
                @if (session()->has('success'))
                <div class="alert alert-dismissible alert-success">
                    <button class="close" type="button" data-dismiss="alert">×</button>
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                <div class="btn-group"><button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Make Contribution </button> </div>

                <!-- Modal div -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6>Make Deposit</h6>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/welfare/add" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    <div class="form-group ">
                                        <label class="control-label">Member Name</label>

                                        <select name="phone" required="required" class="form-control" id="exampleSelect1">

                                            @foreach($welfare as $customer)
                                            <option value="{{$customer['phone']}}">{{$customer['firstname']}} {{$customer['lastname']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group ">
                                        <label class="control-label">Amount</label>
                                        <input name="amount" class="form-control" required="required" type="text" placeholder="Enter Amount">
                                    </div>
                                    <div class="form-group ">
                                        <label class="control-label">Reference</label>
                                        <input name="payment_ref" class="form-control" required="required" type="text" placeholder="Enter Reference">
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
        <div class="tile-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Amount</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($welfare as $saving)
                        <tr>
                            <td><a href="/welfare/view/{{$saving['phone']}}">{{$saving['firstname']}} {{$saving['lastname']}}</a></td>
                            <td>{{$saving['phone']}}</td>
                            <td>{{$saving['welfare']}}</td>
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