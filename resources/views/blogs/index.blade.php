@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i> Blogs</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">All Blogs</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-12">
        <div class="tile">
            @if (session()->has('success'))

            <div class="alert alert-dismissible alert-success">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('success') }}</p>
            </div>
            @endif
            <div class="tile-title-w-btn">
                <h3 class="title"></h3>
                <div class="btn-group">
                    <a href="/learning/blogs/new">
                        <button class="btn btn-primary"><i class="fa fa-lg fa-plus"></i> New Post </button>
                    </a>
                </div>
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Author</th>
                                <th>Comments</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0; ?>
                            @foreach($blogs as $blog)
                            <?php $count++ ?>
                            <tr>
                                <td>{{ $count }}</td>
                                <td><a href="/learning/blogs/view/{{$blog->id}}"> {{ $blog->title }}</a></td>
                                <td>{{ $blog->category_name}}</td>
                                <td>{{ $blog->username }}</td>
                                <td>{{ 0 }}</td>
                                <td>{{ $blog->created_at }}</td>
                                <td>{{ $blog->status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-primary btn-sm" href="/learning/blogs/edit/{{$blog->id}}">
                                            <i class="fa fa-lg fa-edit"></i></a>
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
</div>

@endsection