@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-cogs"></i> Settings</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Settings</a></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="tile">
            <h5 class="tile-title">Sms Codes </h5>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($options ?? '' as $op)
                            <tr>
                                <td>{{$op['id']}}</td>
                                <td>{{$op['type']}}</td>
                                <td>{{$op['message']}}</td>


                            </tr>
                            @endforeach

                        </tbody>

                    </table>


                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h5 class="tile-title">All Messages </h5>
                <div class="btn-group">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Add Message </button>
                </div>
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Message</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/messages/record">
                                @csrf

                                <div class="modal-body">

                                    <div class="form-group ">
                                        <label class="control-label">Message Type</label>
                                        <select name="type" required="required" class="form-control" id="exampleSelect1">

                                            @foreach($types as $tp)
                                            <option value="{{$tp['id']}}">{{$tp['type']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleTextarea">Message</label>
                                        <textarea name="message" required class="form-control" id="exampleTextarea" rows="3"></textarea>
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
        </div>
        <div class="tile-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="datatable">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Message</th>
                        </tr>

                    </thead>
                    <tbody>
                        @foreach($messages as $res)
                        <tr>
                            <td>{{$res['id']}}</td>
                            <td>{{$res['mode']}}</td>
                            <td>{{$res['message']}}</td>

                        </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

</div>
</div>

@endsection