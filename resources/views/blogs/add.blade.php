@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-file"></i> Blog</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">New Post</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-12">
        <div class="tile">
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

            <h3 class="tile-title">New Blog Post</h3>
            <div class="tile-body">

                <form method="POST" action="/learning/blogs/register"  enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Title</label>
                            <input name="title" class="form-control" required="required" type="text" placeholder="Enter Title">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Category</label>
                            <select name="category_id" required="required" class="form-control selector" id="exampleSelect1">
                                @foreach($categories as $prod)
                                <option value="{{$prod['id']}}">{{$prod['title']}} </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="control-label">Cover Image</label>
                            <input name="image" class="form-control" required="required" type="file" placeholder="Select Image">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label">Description</label>

                            <textarea name="description" required="required" class="form-control ckeditor" rows="4" placeholder="Enter Description"></textarea>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label">Content</label>

                            <textarea name="content" required="required" class="form-control ckeditor" rows="4" placeholder="Enter Content"></textarea>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Enable Comments</label>

                            <select name="enable_comment" required="required" class="form-control" id="demoSelect">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>

                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Publish</label>

                            <select name="publish" required="required" class="form-control" id="demoSelect2">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4 align-self-end">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Submit Post </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection