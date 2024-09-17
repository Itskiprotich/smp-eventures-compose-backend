@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-book"></i> Lesson</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">New Lesson</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-8">
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

                <form method="POST" action="/learning/courses/chapters/lesson/{{$course['id']}}/{{$chapter['id']}}/create" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Title</label>
                            <input name="title" class="form-control" required="required" type="text" placeholder="Enter Title">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label">Description</label>
                            <textarea name="description" required="required" class="form-control ckeditor" rows="4" placeholder="Enter Description"></textarea>
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