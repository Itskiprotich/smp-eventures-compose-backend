@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i> Instructor</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">View Instructor</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-8">
        <div class="tile">
            @if (session()->has('ksuccess'))
            <div class="alert alert-dismissible alert-success">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('ksuccess') }}</p>
            </div>
            @endif
            @if (session()->has('kerror'))
            <div class="alert alert-dismissible alert-danger">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('kerror') }}</p>
            </div>
            @endif
            <div class="tile-title-w-btn">
                <h3 class="title">Personal Details</h3>
            </div>
            <form method="POST" action="/learning/instructors/update/{{$instructor['id']}}" enctype="multipart/form-data">
                @csrf
                <div class="tile-body">
                    <!-- add data here -->
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Firstname</label>
                            <input name="firstname" value="{{$instructor['firstname']}}" class="form-control" required="required" type="text" placeholder="Enter Firstname">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Lastname</label>
                            <input name="lastname" value="{{$instructor['lastname']}}" class="form-control" required="required" type="text" placeholder="Enter Lastname">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Email</label>
                            <input name="email" value="{{$instructor['email']}}" class="form-control" required="required" type="email" placeholder="Enter Email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Offline </label>
                            <select name="offline" required="required" class="form-control selector">
                                <option value="{{$instructor['offline']}}">{{$instructor['offline']==0?"Online":"Offline"}} </option>
                                <option value="0">Online </option>
                                <option value="1">Offline</option>

                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Offline Message</label>
                            <textarea name="offline_message" class="form-control" id="exampleTextarea" rows="3">{{$instructor['offline_message']}}</textarea>

                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Address</label>
                            <input name="address" value="{{$instructor['address']}}" class="form-control" required="required" type="text" placeholder="Enter Address">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Role</label>

                            <select name="role_name" required="required" class="form-control selector">
                                <option value="{{$instructor['role_name']}}">{{$instructor['role_name']}} </option>
                                <option value="Teacher">Teacher </option>
                                <option value="Consultant">Consultant</option>
                                <option value="Organization">Organization</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Verified</label>
                            <select name="verified" required="required" class="form-control selector">
                                <option value="{{$instructor['verified']}}">{{$instructor['verified']==0?"Not Verified":"Verified"}} </option>
                                <option value="1">Verified </option>
                                <option value="0">De-Verified</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Meeting Status</label>
                            <select name="meeting_status" required="required" class="form-control selector">
                                <option value="{{$instructor['meeting_status']}}">{{$instructor['meeting_status']==0?"Unavailable":"Available"}} </option>
                                <option value="1">Available </option>
                                <option value="0">Unavailable</option>

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label">Bio</label>
                            <textarea name="bio" class="form-control ckeditor">{{$instructor['bio']}}</textarea>

                        </div>
                    </div>
                </div>
                <div class="tile-footer">
                    <div class="form-group col-md-4 align-self-end">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Submit</button>
                    </div>
                </div>

            </form>
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
            @if ($errors->any())
            <div class="alert alert-dismissible alert-danger">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="tile-title-w-btn">
                <h5>Update Profile</h5>
            </div>
            <form method="POST" action="/learning/instructors/avatar/{{$instructor['id']}}" enctype="multipart/form-data">
                @csrf
                <div class="tile-body">
                    <div class="profile">
                        <div class="info"><img class="user-img" style="width: 200px; height: 250px" src="/uploads/{{$instructor['avatar']}}">

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Select Image</label>
                        <input name="image" class="form-control" required="required" type="file" placeholder="Select Photo">
                    </div>

                </div>
                <div class="tile-footer">
                    <div class="form-group col-md-4 align-self-end">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Submit</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
</div>

@endsection