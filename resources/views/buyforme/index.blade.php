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
                <div class="btn-group"><button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> New Member </button> </div>

                <!-- Modal div -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/b4m/add" enctype="multipart/form-data">
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

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="control-label">Member Name</label>

                                            <select name="phone" required="required" class="form-control" id="exampleSelect1">

                                                @foreach($customers as $customer)
                                                <option value="{{$customer['phone']}}">{{$customer['firstname']}} {{$customer['lastname']}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" type="submit">Register</button>
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
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buyforme as $admin)
                        <tr>
                            <td>{{$admin['firstname']}} {{$admin['lastname']}} </td>
                            <td>{{$admin['phone']}}</td>
                            <td>{{$admin['amount']}}</td>
                            <td>{{$admin['balance']}}</td>
                            <td> @if($admin['status'] == 1)

                                <span class="badge badge-success">Active</span>

                                @else
                                <span class="badge badge-warning">Inactive</span>
                                @endif
                            </td>
                            <td>{{$admin['created_at']}}</td>

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