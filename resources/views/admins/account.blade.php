@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-user"></i> Account</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Profile</a></li>
    </ul>
</div>
<div class="row user">
      
    <div class="col-md-3">
        <div class="tile p-0">
            <ul class="nav flex-column nav-tabs user-tabs">
                <li class="nav-item"><a class="nav-link active" href="#user-timeline" data-toggle="tab">Logs</a></li>
                <li class="nav-item"><a class="nav-link" href="#user-settings" data-toggle="tab">Settings</a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-9">
        <div class="tab-content">
            <div class="tab-pane active" id="user-timeline">
                <div class="timeline-post">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Phone</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                <tr>
                                    <td>{{$log['id']}} </td>
                                    <td>{{$log['phone']}}</td>
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
            <div class="tab-pane fade" id="user-settings">
                <div class="tile user-settings">
                    <h4 class="line-head">Settings</h4>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
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
                    <form method="POST" action="/admin/changer">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label>First Name</label>
                                <input name="firstname" value="{{ $data['firstname'] }}" class="form-control" required="required" type="text" placeholder="Enter First Name">
                            </div>
                            <div class="col-md-4">
                                <label>Last Name</label>
                                <input name="lastname" value="{{ $data['lastname'] }}" class="form-control" required="required" type="text" placeholder="Enter Last Name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-4">
                                <label>Email</label>
                                <input name="email" value="{{ $data['email'] }}" class="form-control" required="required" type="email" placeholder="Enter Email Addresss">
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-8 mb-4">
                                <label>Mobile No</label>

                                <input name="email" value="{{ $data['phone'] }}" class="form-control" required="required" type="number" placeholder="Enter Email Addresss">
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-8 mb-4">
                                <label>Password</label>
                                <input name="password" class="form-control" required="required" type="password" placeholder="Enter Password">
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-8 mb-4">
                                <label>Confirm Password</label>
                                <input name="confirm" class="form-control" required="required" type="password" placeholder="Confirm Password">
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-12">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection