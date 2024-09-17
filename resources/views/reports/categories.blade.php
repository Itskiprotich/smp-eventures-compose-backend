@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-cc"></i> Categories</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Categories</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h3 class="title"></h3>

                <div class="btn-group">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Add Category </button>
                </div>

                <!-- Modal div -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/category/add">
                                @csrf

                                <div class="modal-body">

                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    <div class="form-group ">
                                        <label class="control-label">Category Name</label>
                                        <input name="name" class="form-control" required="required" type="text" placeholder="Enter Category Name">

                                    </div>


                                    <div class="modal-footer">
                                        <div class="form-group col-md-4 align-self-end">
                                            <button class="btn btn-success" type="submit">Add</button>
                                        </div>

                                    </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <!-- End modal -->

        <div class="tile-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Added</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($categories as $category)
                        <tr>
                            <td>{{$category['id']}}</td>
                            <td>{{$category['name']}}</td>

                            <td> @if($category['status'] == 1)
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-warning">Inactive</span>
                                @endif
                            </td>
                            <td>{{$category['created_at']}}</td>
                            <td>
                                <a class="btn btn-primary" href="/reports/edit/{{$category['id']}}"><i class="fa fa-lg fa-edit"></i></a>
                            </td>
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