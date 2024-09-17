@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-user"></i> Admins</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">All Admins</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
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


            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Alerts</th>
                                <th>Joined</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                            <tr>
                                <td><a href="/admin/view/{{$admin['id']}}">{{$admin['firstname']}} {{$admin['lastname']}} </a></td>
                                <td>{{$admin['phone']}}</td>
                                <td>{{$admin['email']}}</td>
                                <td>{{$admin['usertype']}}</td>
                                <td> @if($admin['alerts_on'] == 1)
                                    <span class="badge badge-success">Active</span>
                                    @else
                                    <span class="badge badge-warning">Inactive</span>
                                    @endif
                                </td>
                                <td>{{$admin['created_at']}}</td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-success" href="/admin/view/{{$admin['id']}}">
                                            <i class="fa fa-lg fa-eye"></i></a>

                                    </div>
                                   
                                </td>
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
            <div class="tile-title-w-btn">
                <h5>Add New Admin</h5>
            </div>
            <div class="tile-body">
                @if (session()->has('success'))
                <div class="alert alert-dismissible alert-success">
                    <button class="close" type="button" data-dismiss="alert">×</button>
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                <form method="POST" action="/admin/add" enctype="multipart/form-data">
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
                            <div class="form-group col-md-6">
                                <label class="control-label">First Name</label>
                                <input name="firstname" class="form-control" required="required" type="text" placeholder="Enter First Name">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Last Name</label>
                                <input name="lastname" class="form-control" required="required" type="text" placeholder="Enter Last Name">
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Phone</label>
                                <input name="phone" class="form-control" required="required" type="text" placeholder="Enter Phone">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Email</label>
                                <input name="email" class="form-control" required="required" type="text" placeholder="Enter Email">
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">User Type</label>

                                <select name="usertype" required="required" class="form-control" id="exampleSelect1">
                                    <option value="Admin">Admin</option>
                                    <option value="Superadmin">Superadmin</option>

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Status</label>

                                <select name="status" required="required" class="form-control" id="exampleSelect1">
                                    <option value="true">Active</option>
                                    <option value="false">Inactive</option>

                                </select>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <div class="form-group col-md-4 align-self-end">
                                <button class="btn btn-success" type="submit">Register</button>
                            </div>

                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

@endsection