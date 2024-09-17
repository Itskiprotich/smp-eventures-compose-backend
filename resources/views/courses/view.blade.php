@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-money"></i> Course</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">View Course</a></li>
    </ul>
</div>
<div class="row">
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
                <h6 class="title">Course Details
                </h6>

                <div class="btn-group"> <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Apply Discount </button>
                    <form method="POST" action="/learning/courses/featured/apply/{{$data['id']}}">
                        @csrf
                        <input name="_method" type="hidden" value="POST">
                        <button type="submit" class="btn btn-success show_confirm"> <i class="fa fa-check"> </i>Mark Featured</button>
                    </form>
                </div>
                <!-- Topup Loan -->

                <!-- End of Topup -->
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
                                </div>
                            </form>
                        </div>


                    </div>
                </div>

            </div>

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
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h6 class="tile-title">Sections</h6>

                <!-- Test  -->
                <div class="btn-group">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#sectionModal"><i class="fa fa-lg fa-plus"></i> Add Section </button>

                </div>
                <!-- Topup Loan -->

                <!-- End of Topup -->
                <div id="sectionModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Section</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="POST" action="/learning/courses/chapters/add/{{$data['id']}}" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">
                                    <div class="form-group ">
                                        <label class="control-label">Title</label>
                                        <input name="title" class="form-control" required="required" type="text" placeholder="Enter Title">
                                    </div>
                                    <div class="form-group ">
                                        <label class="control-label">The student should pass all parts</label>
                                        <select name="pass" required="required" style="width: 100%;" class="form-control">
                                            <option value="false">No </option>
                                            <option value="true">Yes </option>
                                        </select>

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                </div>

                            </form>
                        </div>



                    </div>
                </div>
                <div id="sectionModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Section</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="POST" action="/learning/courses/chapters/add/{{$data['id']}}" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">
                                    <div class="form-group ">
                                        <label class="control-label">Title</label>
                                        <input name="title" class="form-control" required="required" type="text" placeholder="Enter Title">
                                    </div>
                                    <div class="form-group ">
                                        <label class="control-label">The student should pass all parts</label>
                                        <select name="pass" required="required" style="width: 100%;" class="form-control">
                                            <option value="false">No </option>
                                            <option value="true">Yes </option>
                                        </select>

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                </div>

                            </form>
                        </div>



                    </div>
                </div>
                <!-- End Test -->
            </div>
            <div class="tile-body">
                <!-- Start -->
                <div id="accordion">
                    @foreach($chapters as $chapter)
                    <div class="card">
                        <div class="card-header" id="{{$chapter['title']}}">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse{{$chapter['id']}}" aria-expanded="false" aria-controls="{{$chapter['title']}}">
                                    {{$chapter['title']}}
                                </button>
                            </h5>
                        </div>
                        <div id="collapse{{$chapter['id']}}" class="collapse" aria-labelledby="{{$chapter['title']}}" data-parent="#accordion">
                            <div class="card-body">
                                <div class="col-md-12">
                                    <div class="tile">
                                        <div class="tile-title-w-btn">
                                            <h3 class="title">Sessions</h3>

                                            <div class="btn-group">

                                                <a class="nav-link dropdown-toggle btn btn-primary" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-lg fa-plus"></i></a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="/learning/courses/chapters/lesson/add/{{$data['id']}}/{{$chapter['id']}}">New Text Lesson</a>
                                                    <a class="dropdown-item" href="#">New Quiz</a>
                                                    <a class="dropdown-item" href="#">New Assignment</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#"></a>
                                                </div>

                                                <button class="btn btn-primary" data-toggle="modal" data-target="#editModal{{$chapter['id']}}"><i class="fa fa-lg fa-edit"></i> </button>
                                                <a class="btn btn-primary confirm_delete" href="/learning/courses/chapters/delete/{{$data['id']}}/{{$chapter['id']}}"><i class="fa fa-lg fa-trash"></i></a>
                                            </div>
                                            <!-- Modal to Edit the Session Title -->
                                            <div id="editModal{{$chapter['id']}}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Section</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <form method="POST" action="/learning/courses/chapters/edit/{{$data['id']}}/{{$chapter['id']}}" enctype="multipart/form-data">
                                                            @csrf

                                                            <div class="modal-body">
                                                                <div class="form-group ">
                                                                    <label class="control-label">Title</label>
                                                                    <input name="title" value="{{$chapter['title']}}" class="form-control" required="required" type="text" placeholder="Enter Title">
                                                                </div>
                                                                <div class="form-group ">
                                                                    <label class="control-label">The student should pass all parts</label>
                                                                    <select name="pass" required="required" style="width: 100%;" class="form-control">
                                                                        <option value="false">No </option>
                                                                        <option value="true">Yes </option>
                                                                    </select>

                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="submit">Submit</button>
                                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End of Modal -->
                                        </div>
                                        <div class="tile-body">
                                            @foreach($chapter['lessons'] as $less)
                                            <div id="accordionalt">
                                                <div class="card">
                                                    <div class="card-header" id="{{$less['title']}}">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse{{$chapter['id']}}{{$less['id']}}" aria-expanded="false" aria-controls="{{$less['title']}}">
                                                                {{$less['title']}}
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="collapse{{$chapter['id']}}{{$less['id']}}" class="collapse" aria-labelledby="{{$less['title']}}" data-parent="#accordionalt">
                                                        <div class="card-body">
                                                            <div class="col-md-12">
                                                                <div class="tile">
                                                                    <div class="tile-title-w-btn">

                                                                    </div>
                                                                    <div class="tile-body">
                                                                        <p> {!! nl2br($less->description) !!}</p>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach

                </div>

                <!-- End -->
            </div>
            <div class="tile-footer">

            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h6 class="tile-title">FAQs</h6>

                <!-- Test  -->
                <div class="btn-group">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#faqModal"><i class="fa fa-lg fa-plus"></i> New FAQ </button>

                </div>
                <!-- Topup Loan -->

                <!-- End of Topup -->
                <div id="faqModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add FAQ</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="POST" action="/learning/courses/faq/add/{{$data['id']}}" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">
                                    <div class="form-group ">
                                        <label class="control-label">Title</label>
                                        <input name="title" class="form-control" required="required" type="text" placeholder="Enter Title">
                                    </div>
                                    <div class="form-group ">
                                        <label class="control-label">Answer</label>
                                        <textarea name="answer" required="required" class="form-control " rows="4" placeholder="Enter Description"></textarea>

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                </div>

                            </form>
                        </div>


                    </div>
                </div>

                <!-- End Test -->
            </div>
            <div class="tile-body">
                <!-- Start -->
                <div id="accordion">
                    @foreach($faqs as $chapter)
                    <div class="card">
                        <div class="card-header" id="{{$chapter['title']}}">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse{{$chapter['id']}}" aria-expanded="false" aria-controls="{{$chapter['title']}}">
                                    {{$chapter['title']}}
                                </button>
                            </h5>
                        </div>
                        <div id="collapse{{$chapter['id']}}" class="collapse" aria-labelledby="{{$chapter['title']}}" data-parent="#accordion">
                            <div class="card-body">
                                <div class="col-md-12">
                                    <div class="tile">
                                        <div class="tile-title-w-btn">
                                            <h3 class="title"> </h3>
                                            <div class="btn-group">
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#editFaQModal{{$chapter['id']}}"><i class="fa fa-lg fa-edit"></i> </button>
                                                <a class="btn btn-danger confirm_delete" href="/learning/courses/faq/delete/{{$data['id']}}/{{$chapter['id']}}"><i class="fa fa-lg fa-trash"></i></a>
                                            </div>
                                            <!-- Modal to Edit the Session Title -->
                                            <div id="editFaQModal{{$chapter['id']}}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit FAQ</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <form method="POST" action="/learning/courses/faq/edit/{{$data['id']}}/{{$chapter['id']}}" enctype="multipart/form-data">
                                                            @csrf

                                                            <div class="modal-body">
                                                                <div class="form-group ">
                                                                    <label class="control-label">Title</label>
                                                                    <input name="title" value="{{$chapter['title']}}" class="form-control" required="required" type="text" placeholder="Enter Title">
                                                                </div>
                                                                <div class="form-group ">
                                                                    <label class="control-label">Answer</label>
                                                                    <textarea name="answer" required="required" class="form-control " rows="4" placeholder="Enter Description">
                                                                    {{$chapter['answer']}}
                                                                    </textarea>


                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="submit">Submit</button>
                                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End of Modal -->
                                        </div>
                                        <div class="tile-body">
                                            <p> {{$chapter['answer']}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach

                </div>

                <!-- End -->
            </div>
            <div class="tile-footer">

            </div>
        </div>
    </div>

</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    $('.collapse').collapse();
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want mark this course as featured?')) {
            e.preventDefault();
        }
    });
    $('.confirm_delete').click(function(e) {
        if (!confirm('Are you sure you want delete this Session?')) {
            e.preventDefault();
        }
    });
</script>

@endsection