@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i> Comments</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">All Comments</a></li>
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

                </div>
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Comment</th>
                                <th>Created</th>
                                <th>User</th>
                                <th>Course</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0; ?>
                            @foreach($comments as $cm)
                            <?php $count++ ?>
                            <tr>
                                <td>{{ $count }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                                        <i class="fa fa-lg fa-eye"></i>
                                        Show </button>

                                </td>
                                <td>{{ $cm->created_at}}</td>
                                <td>{{ $cm->student_name }}</td>
                                <td>{{ $cm->course_title}}</td>
                                <td>{{ $cm->type }}</td>
                                <td>{{ $cm->status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-success btn-sm" href="#">
                                            <i class="fa fa-lg fa-eye"></i></a>
                                            <a class="btn btn-info btn-sm" href="#">
                                            <i class="fa fa-lg fa-reply"></i></a>
                                        <a class="btn btn-primary btn-sm" href="#">
                                            <i class="fa fa-lg fa-edit"></i></a>
                                        <a class="btn btn-danger btn-sm" href="#">
                                            <i class="fa fa-lg fa-trash"></i></a>
                                    </div>
                                </td>
                                <!-- Start of Modal -->
                                <div id="myModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4>Message</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            </div>
                                            <form method="POST" action="#" enctype="multipart/form-data">
                                                @csrf

                                                <div class="modal-body">
                                                    <p>{{$cm->comment}}</p>
                                                </div>

                                                <div class="modal-footer">
                                                    <div class="form-group col-md-4 align-self-end">
                                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End of Modal -->
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