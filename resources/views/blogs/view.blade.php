@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i> Blog</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">View Blog</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h3 class="title">{{ $blog->title }}</h3>
                <div class="btn-group">

                </div>
            </div>
            <div class="tile-body">
                <div class="row">

                    <div class="col-md-6">
                        <div class="bs-component">
                            <div class="card">
                                <h4 class="card-header"> {!! nl2br($blog->description) !!} </h4>
                                <div class="card-body">
                                </div><img style="height: 200px; width: 100%; display: block;" src="/uploads/{{$blog->image}}" alt="Card image">
                                <div class="card-body">
                                    <p class="card-text">

                                        {!! nl2br($blog->content) !!}
                                    </p>
                                </div>
                                <div class="card-footer text-muted">Date Posted {{$blog->created_at}}</div>
                            </div>
                            <hr>
                            <div class="card">
                                <h4 class="card-header">Comments</h4>
                                <div class="card-body">
                                    @foreach($comments as $data)
                                    <a href="#">
                                        <p class="card-title">
                                            {!! nl2br($data->comment) !!}
                                        </p>

                                    </a>
                                    @endforeach
                                </div>

                                <div class="card-body">
                                    <form method="POST" action="/learning/blogs/comments/add/{{$blog->id}}">
                                        @csrf
                                        <div class="tile-body">
                                            <label class="control-label"> Enter Your Comment</label>
                                            <textarea name="comment" required="required" class="form-control ckeditor" rows="4" placeholder="Enter Comment"></textarea>
                                        </div>
                                        <div class="tile-footer">
                                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Submit </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-muted"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bs-component">
                            <div class="card">
                                <h4 class="card-header">Categories</h4>
                                <div class="card-body">
                                    @foreach($categories as $blogCategory)
                                    <a href="/learning/blogs/per-category/{{$blogCategory->id}}">
                                        <h5 class="card-title">{{ $blogCategory->title }}</h5>

                                    </a>
                                    @endforeach
                                </div>

                                <div class="card-body">
                                    <p class="card-text"> </p>
                                </div>
                                <div class="card-footer text-muted"></div>
                            </div>
                            <hr>

                            <div class="card">
                                <h4 class="card-header">Recent Blogs</h4>
                                <div class="card-body">
                                    @foreach($blogs as $blog)
                                    <a href="/learning/blogs/view/{{$blog->id}}">
                                        <h5 class="card-title">{{ $blog->title }}</h5>
                                        <hr>
                                    </a>
                                    @endforeach
                                </div>
                                <div class="card-footer text-muted">

                                    <a href="/learning/blogs" class="btn btn-sm btn-primary btn-block mt-30">View All Posts</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>

@endsection