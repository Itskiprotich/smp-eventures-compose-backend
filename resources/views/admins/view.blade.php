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
                                    <div class="btn-group">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-lock"></i> </button>

                                        <!-- Start of Modal -->
                                        <div id="myModal" class="modal fade" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header"> Password Change
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                    </div>
                                                    <form method="POST" action="/admin/password-change/{{$data['id']}}" enctype="multipart/form-data">
                                                        @csrf

                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label class="control-label">Password</label>
                                                                <input name="password" class="form-control" required="required" type="password" placeholder="Enter Password">
                                                            </div>
                                                            <div class="form-group ">
                                                                <label class="control-label">Confirm Password</label>
                                                                <input name="confirm" class="form-control" required="required" type="password" placeholder="Confirm Password">
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
                <h5>Update Admin Details</h5>
            </div>
            <div class="tile-body">
                @if (session()->has('success'))
                <!-- {!! display_success('Data uploaded Successfully') !!} -->
                <div class="alert alert-dismissible alert-success">
                    <button class="close" type="button" data-dismiss="alert">×</button>
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                <form method="POST" action="/admin/update/{{$data['id']}}" enctype="multipart/form-data">
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
                                <input name="firstname" value="{{$data['firstname']}}" class="form-control" required="required" type="text" placeholder="Enter First Name">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Last Name</label>
                                <input name="lastname" value="{{$data['lastname']}}" class="form-control" required="required" type="text" placeholder="Enter Last Name">
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Phone</label>
                                <input name="phone" value="{{$data['phone']}}" class="form-control" required="required" type="text" placeholder="Enter Phone">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Email</label>
                                <input name="email" value="{{$data['email']}}" class="form-control" required="required" type="text" placeholder="Enter Email">
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
                            <div class="row">
                                <div class="form-group col-md-12" style="margin-left: 20px;">
                                    <div class="utility">
                                        <div class="animated-checkbox">
                                            <label for="remember">
                                                <input type="checkbox" value="1" {{ $data['alerts_on'] ? 'checked' : '' }} name="alerts_on" id="remember">
                                                <span class="label-text">Can Receive Alerts</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group col-md-4 align-self-end">
                                <button class="btn btn-success" type="submit">Update</button>
                            </div>

                        </div>
                </form>
            </div>
        </div>

    </div>
</div>
</div>

@endsection