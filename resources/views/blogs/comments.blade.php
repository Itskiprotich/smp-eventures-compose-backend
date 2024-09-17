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
                                <th>Blog</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0; ?>
                            @foreach($comments as $cat)
                            <?php $count++ ?>
                            <tr>
                                <td>{{ $count }}</td>
                                <td> {!! nl2br($cat->comment) !!} </td>
                                <td><a href="/learning/blogs/view/{{$cat->blog_id}}"> {{$cat->blog_name }}</a></td>
                                <td>{{ $cat->username }}</td>
                                <td> @if($cat['status'] == "pending")
                                    <span class="badge badge-warning">Pending</span>
                                    @else
                                    <span class="badge badge-success">Published</span>
                                    @endif
                                </td>
                                <td>{{ $cat->created_at }}</td>
                                <td>
                                    <div class="btn-group">
                                        <form method="POST" action="/learning/blogs/comments/approve/{{$cat['id']}}">
                                            @csrf
                                            <input name="_method" type="hidden" value="POST">
                                            <button type="submit" class="btn-success btn-flat show_confirm" data-toggle="tooltip" title='Approve'> <i class="fa fa-check"> </i>Approve</button>
                                        </form>
                                        <form method="POST" action="/learning/blogs/comments/reject/{{$cat['id']}}">
                                            @csrf
                                            <input name="_method" type="hidden" value="POST">
                                            <button type="submit" class="btn-danger btn-flat show_reject" data-toggle="tooltip" title='Reject'> <i class="fa fa-trash-o"> </i>Reject</button>
                                        </form>

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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to approve this comment?')) {
            e.preventDefault();
        }
    });

    $('.show_reject').click(function(e) {
        if (!confirm('Are you sure you want to reject this comment?')) {
            e.preventDefault();
        }
    });
</script>
@endsection