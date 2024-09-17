@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-film"></i> Course Categories</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">All Course Categories</a></li>
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
                                <th>#</th>
                                <th>Name</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0; ?>
                            @foreach($categories as $type)
                            <?php $count++; ?>
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$type['title']}}</td>
                                <td>{{$type['created_at']}}</td>

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
                <h5>Add New Category</h5>
            </div>
            <form method="POST" action="/learning/courses/new_category" enctype="multipart/form-data">
                @csrf
                <div class="tile-body">

                    <div class="form-group">
                        <label class="control-label">Category Name</label>
                        <input name="title" class="form-control" required="required" type="text" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Icon</label>
                        <input name="image" class="form-control" required="required" type="file" placeholder="Select Image">
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