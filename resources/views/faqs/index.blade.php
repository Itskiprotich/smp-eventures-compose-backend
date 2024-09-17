@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-handshake-o"></i> Frequency Asked Questions</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">FAQs</a></li>
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
                <h3 class="title"></h3>

                <div class="btn-group"><button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Add FAQ </button> </div>

                <!-- Modal div -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add FAQ</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/faqs/add" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    <div class="form-group col-md-12">
                                        <label class="control-label">Question</label>
                                        <input name="question" class="form-control" required="required" type="text" placeholder="Enter Question">
                                    </div>


                                    <div class="form-group col-md-12">
                                        <label class="control-label">Answer</label>
                                        <textarea name="answer" class="form-control" id="exampleTextarea" rows="3"></textarea>
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
            </div>

            <div class="tile-body">
                <!-- Start -->
                <div id="accordion">
                    @foreach($faqs as $faq)
                    <div class="card">
                        <div class="card-header" id="{{$faq['question']}}">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse{{$faq['id']}}" aria-expanded="false" aria-controls="{{$faq['question']}}">
                                    {{$faq['question']}}
                                </button>
                            </h5>
                        </div>
                        <div id="collapse{{$faq['id']}}" class="collapse" aria-labelledby="{{$faq['question']}}" data-parent="#accordion">
                            <div class="card-body">
                            <p> {{$faq['answer']}}</p>
                                
                            </div>
                        </div>
                    </div>

                    @endforeach

                </div>

                <!-- End -->
            </div>
        </div>
    </div>
</div>

@endsection