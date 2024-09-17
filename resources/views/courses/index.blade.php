@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i> Courses</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">All Courses</a></li>
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
            @if (session()->has('error'))

            <div class="alert alert-dismissible alert-danger">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('error') }}</p>
            </div>
            @endif
            <div class="tile-title-w-btn">
                <h3 class="title"></h3>
                <div class="btn-group">
                    <a href="/learning/courses/new">
                        <button class="btn btn-primary"><i class="fa fa-lg fa-plus"></i> New Course </button>
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
                                <th>Instructor</th>
                                <th>Price</th>
                                <th>Sales</th>
                                <th>Income</th>
                                <th>Students</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0; ?>
                            @foreach($courses as $blog)
                            <?php $count++ ?>
                            <tr>
                                <td>{{ $count }}</td>
                                <td><a href="#"> {{ $blog->title }}</a></td>
                                <td>{{ $blog->username}}</td>
                                <td>{{ $blog->price }}</td>
                                <td>{{ 0 }}</td>
                                <td>{{ 0 }}</td>
                                <td>{{ 0 }}</td>
                                <td>{{ 0 }}</td>
                                <td>{{ $blog->updated_at }}</td>
                                <td>{{ $blog->status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-primary btn-sm" href="/learning/courses/view/{{$blog->id}}">
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