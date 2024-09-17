@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-book"></i> Course</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">View Course</a></li>
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

                <form method="POST" action="/learning/courses/update/{{$data['id']}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Course Type</label>
                            <select name="type_id" required="required" class="form-control selector">

                                <option value="{{$data['types_id']}}">{{$data['types_title']}}</option>

                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Course Category</label>
                            <select name="category_id" required="required" class="form-control selector">

                                <option value="{{$data['category_id']}}">{{$data['category_title']}}</option>

                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Instructor</label>
                            <select name="teacher_id" required="required" class="form-control selector">

                                <option value="{{$data['user_id']}}">{{$data['username']}}</option>


                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Title</label>
                            <input name="title" value="{{$data['title']}}" class="form-control" required="required" type="text" placeholder="Enter Title">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Image Cover</label>
                            <input name="image" class="form-control" type="file" placeholder="Select Image">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Video URL</label>
                            <input name="video" value="{{$data['video_demo']}}" class="form-control" required="required" type="url" placeholder="Enter URL">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label">Description</label>
                            <textarea name="description" required="required" class="form-control ckeditor" rows="4" placeholder="Enter Description">{{$data['description']}}</textarea>
                        </div>


                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="control-label">Capacity</label>
                            <input name="capacity" value="{{$data['capacity']}}" class="form-control" required="required" type="number" placeholder="Enter Capacity">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">Price</label>
                            <input name="price" value="{{$data['price']}}" class="form-control" required="required" type="number" placeholder="Enter Price">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">Start Date</label>
                            <input name="start_date" value="{{$data['start_date']}}" class="form-control" required="required" type="date" placeholder="Enter Date">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">End Date</label>
                            <input name="end_date" value="{{$data['end_date']}}" class="form-control" required="required" type="date" placeholder="Enter Date">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4 align-self-end">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="tile">
            <h3 class="tile-title">Additional Information</h3>
            <div class="tile-body">
                <!-- Start of Button -->
                <div class="btn-group">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Add Discount </button>
                    <form method="POST" action="/learning/courses/featured/apply/{{$data['id']}}">
                        @csrf
                        <input name="_method" type="hidden" value="POST">
                        <button type="submit" class="btn btn-success show_confirm"> <i class="fa fa-check"> </i>Mark Featured</button>
                    </form>
                </div>

                <!-- Modal div -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Apply Discount</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/learning/courses/discounts/apply/{{$data['id']}}" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    <div class="form-group col-md-12">
                                        <label class="control-label">Discount</label>
                                        <select name="discount" required="required" style="width: 80%;" class="form-control selector">

                                            @foreach($discounts as $prod)
                                            <option value="{{$prod['id']}}">{{$prod['title']}} </option>
                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- End of Button -->

            </div>
        </div>
    </div>


</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want mark this course as featured?')) {
            e.preventDefault();
        }
    });
</script>
@endsection