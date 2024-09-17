@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i> Instructors</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">All Instructors</a></li>
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
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instructors as $admin)
                            <tr>
                                <td><a href="/learning/instructors/view/{{$admin['id']}}">{{$admin['firstname']}} {{$admin['lastname']}} </a></td>
                                <td>{{$admin['phone']}}</td>
                                <td>{{$admin['email']}}</td>
                                <td>{{$admin['usertype']}}</td>
                                <td>{{$admin['created_at']}}</td>

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
                <h5>Add New Instructor</h5>
            </div>
            <form method="POST" action="/learning/instructors/new" enctype="multipart/form-data">
                @csrf
                <div class="tile-body">

                    <div class="form-group">
                        <label class="control-label">Admin Account</label>
                        <select name="email" required="required" class="form-control" id="demoSelect">
                        @foreach($admins as $prod)
                      <option value="{{$prod['email']}}">{{$prod['username']}} - {{$prod['email']}} </option>
                      @endforeach

                        </select>
                    </div>
                </div>
                <div class="tile-footer">
                    <div class="form-group col-md-4 align-self-end">
                        <button class="btn btn-success" type="submit">Submit</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
</div>

@endsection