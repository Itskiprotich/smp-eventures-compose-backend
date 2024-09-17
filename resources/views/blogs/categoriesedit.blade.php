@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i> Categories</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">All Categories</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-8">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h3 class="title"></h3>
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
                <div class="btn-group">

                </div>

            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0; ?>
                            @foreach($categories as $cat)
                            <?php $count++ ?>
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $cat->title }}</td>
                                <td>{{ $cat->created_at }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-primary btn-sm" href="/learning/blogs/categories/edit/{{$cat->id}}"><i class="fa fa-lg fa-edit"></i>Edit</a>
                                        <a class="btn btn-danger btn-sm" href="#"><i class="fa fa-lg fa-trash-o"></i>Delete</a>
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
                <h3 class="title">Edit Category</h3>
                <div class="btn-group">

                </div>

            </div>
            <form method="POST" action="/learning/blogs/category/update/{{$category->id}}">
                @csrf
                <div class="tile-body">
                    <label class="control-label">Category</label>
                    <input name="title"  value="{{$category->title}}"  class="form-control" required="required" type="text" placeholder="Enter Title">
                </div>
                <div class="tile-footer">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Submit </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection