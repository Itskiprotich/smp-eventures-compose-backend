@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1> <i class="fa fa-code"></i> Branches</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Registered Branches</a></li>
    </ul>
</div>
<div class="row">

    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h5 class="tile-title">All Branches </h5>
                <div class="btn-group">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Add Branch </button>
                </div>
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Branch</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/branches/add">
                                @csrf

                                <div class="modal-body">

                                    <div class="form-group ">
                                        <label class="control-label">Branch Name</label>

                                        <input name="name" required class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleTextarea">Description</label>
                                        <textarea name="description" required class="form-control" id="exampleTextarea" rows="3"></textarea>
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
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">

                        <thead>
                            <tr>
                                <th style="width: 1%;">#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Recruiting</th>
                                <th style="width: 10%;">Action</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php $count = 0; ?>
                            @foreach($branches as $branch)
                            <?php $count++; ?>
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$branch->name}}</td>
                                <td>{{$branch->description}}</td>
                                <td> @if($branch['active'] == 1)
                                    <span class="badge badge-success">Active</span>
                                    @else
                                    <span class="badge badge-warning">In Active</span>
                                    @endif
                                </td>
                                <td> @if($branch['recruit'] == 1)
                                    <span class="badge badge-success">Accepting</span>
                                    @else
                                    <span class="badge badge-warning">On Hold</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit{{$branch['id']}}" data-toggle="tooltip" data-placement="top" title="Edit">
                                            <i class="fa fa-lg fa-edit"></i>
                                        </button>

                                        @if($branch['active'] == 1)
                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete{{$branch['id']}}"
                                        data-toggle="tooltip" data-placement="top" title="Delete">
                                            <i class="fa fa-lg fa-trash-o"></i></button>
                                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#recruit{{$branch['id']}}"
                                        data-toggle="tooltip" data-placement="top" title="Recruit">
                                            <i class="fa fa-lg fa-check"></i></button>
                                        @else
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#restore{{$branch['id']}}"
                                        data-toggle="tooltip" data-placement="top" title="Restore">
                                            <i class="fa fa-lg fa-refresh"></i></button>
                                        @endif



                                    </div>

                                    <div id="edit{{$branch['id']}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Branch</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                </div>
                                                <form method="POST" action="/branches/edit/{{$branch['id']}}">
                                                    @csrf

                                                    <div class="modal-body">

                                                        <div class="form-group ">
                                                            <label class="control-label">Branch Name</label>

                                                            <input name="name" value="{{$branch->name}}" required class="form-control" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleTextarea">Description</label>
                                                            <textarea name="description" required class="form-control" id="exampleTextarea" rows="3">{{$branch->description}}
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
                                    <!-- De-activate -->
                                    <div id="delete{{$branch['id']}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Deactivate Branch</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                </div>
                                                <form method="POST" action="/branches/delete/{{$branch['id']}}">
                                                    @csrf

                                                    <div class="modal-body danger">

                                                        <div class="form-group ">
                                                            <label class="control-label">Are you sure you want to deactivate the branch?</label>

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
                                    <!-- End of De-activate -->

                                    <!-- Start Restore -->
                                    <div id="restore{{$branch['id']}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Restore Branch</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                </div>
                                                <form method="POST" action="/branches/restore/{{$branch['id']}}">
                                                    @csrf

                                                    <div class="modal-body danger">

                                                        <div class="form-group ">
                                                            <label class="control-label">Are you sure you want to restore the branch?</label>

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
                                    <!-- End Restore -->
                                    <!-- Start Recruit -->
                                    <div id="recruit{{$branch['id']}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Mark Branch as Recruiting</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                </div>
                                                <form method="POST" action="/branches/recruit/{{$branch['id']}}">
                                                    @csrf

                                                    <div class="modal-body danger">

                                                        <div class="form-group ">
                                                            <label class="control-label">Are you sure you want to activate the branch to recruit?</label>

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

                                    <!-- End Recruit -->
                                </td>

                                </td>
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