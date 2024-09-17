@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-book"></i> Course</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">New Course</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-12">
        <div class="tile">
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

            <h3 class="tile-title">Basic Information</h3>
            <div class="tile-body">

                <form method="POST" action="/learning/courses/create" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Course Type</label>
                            <select name="type_id" required="required" class="form-control selector">
                                @foreach($types as $prod)
                                <option value="{{$prod['id']}}">{{$prod['title']}} </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Course Category</label>
                            <select name="category_id" required="required" class="form-control selector">
                                @foreach($categories as $prod)
                                <option value="{{$prod['id']}}">{{$prod['title']}} </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Instructor</label>
                            <select name="teacher_id" required="required" class="form-control selector">
                                @foreach($instructors as $prod)
                                <option value="{{$prod['id']}}">{{$prod['username']}} - {{$prod['phone']}}</option>
                                @endforeach

                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Title</label>
                            <input name="title" class="form-control" required="required" type="text" placeholder="Enter Title">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Image Cover</label>
                            <input name="image" class="form-control" required="required" type="file" placeholder="Select Image">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Video URL</label>
                            <input name="video" class="form-control" required="required" type="url" placeholder="Enter URL">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label">Description</label>
                            <textarea name="description" required="required" class="form-control ckeditor" rows="4" placeholder="Enter Description"></textarea>
                        </div>


                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="control-label">Capacity</label>
                            <input name="capacity" class="form-control" required="required" type="number" placeholder="Enter Capacity">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">Price</label>
                            <input name="price" class="form-control" required="required" type="number" placeholder="Enter Price">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">Start Date</label>
                            <input name="start_date" class="form-control" required="required" type="date" placeholder="Enter Date">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">End Date</label>
                            <input name="end_date" class="form-control" required="required" type="date" placeholder="Enter Date">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4 align-self-end">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Register</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection