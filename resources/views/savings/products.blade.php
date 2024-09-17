@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-handshake-o"></i> Saving Products</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Saving Products</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-8">
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

                <div class="btn-group">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                        <i class="fa fa-lg fa-plus"></i> Add Product </button>
                    <button class="btn btn-success" data-toggle="modal" data-target="#assignModal">
                        <i class="fa fa-lg fa-link"></i> Assign Group </button>
                </div>

                <!-- Modal div -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Register Saving Product</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/savings/product/add" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Name</label>
                                            <input name="loan_name" class="form-control" required="required" type="text" placeholder="Enter Name">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Duration</label>
                                            <select name="duration" required="required" class="form-control" id="exampleSelect1">
                                                <option value="7">1 Week Plan</option>
                                                <option value="14">2 Weeks Plan</option>
                                                <option value="21">3 Weeks Plan</option>
                                                <option value="28">1 Month Plan</option>
                                                <option value="56">2 Months Plan</option>
                                                <option value="84">3 Months Plan</option>
                                                <option value="168">6 Months Plan</option>
                                                <option value="336">12 Months Plan</option>

                                            </select>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Minimum Limit</label>
                                            <input name="min_limit" class="form-control" required="required" type="number" placeholder="Enter Minimum ">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Maximum Limit</label>
                                            <input name="max_limit" class="form-control" required="required" type="number" placeholder="Enter Maximum ">
                                        </div>


                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit">Register</button>
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <!-- End of Modal -->


                <!-- Modal div -->
                <div id="assignModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Assign Group</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/savings/groups/assign" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">
                                    <div class="form-group col-md-12">
                                        <label class="control-label">Saving Product</label>
                                        <select name="product_code" style="width: 100%;" required="required" class="form-control selector">
                                            @foreach($products as $prd)
                                            <option value="{{$prd['product_code']}}">{{$prd['product_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="control-label">Group</label>
                                        <select name="group_id" style="width: 100%;" required="required" class="form-control selector">
                                            @foreach($groups as $prd)
                                            <option value="{{$prd['id']}}">{{$prd['title']}}</option>
                                            @endforeach
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
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Duration</th>
                                <th>Minimum</th>
                                <th>Maximum</th>
                                <th>Group</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach($products as $prd)
                            <tr>

                                <td><a href="/savings/product/view/{{$prd['product_code']}}">{{$prd['product_code']}}</a></td>
                                <td>{{$prd['product_name']}}</td>
                                <td>{{$prd['duration']}}</td>
                                <td>{{$prd['min_limit']}}</td>
                                <td>{{$prd['max_limit']}}</td>
                                <td>{{$prd['parent_group']}}</td>
                                <td>@if($prd['active'] == 1)
                                    <span class="badge badge-success">Active</span>
                                    @else
                                    <span class="badge badge-warning">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editProductModal{{$prd['product_code']}}">
                                            <i class="fa fa-lg fa-edit"></i></button>
                                    </div>


                                    <!-- Modal div -->
                                    <div id="editProductModal{{$prd['product_code']}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Product - {{$prd['product_code']}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                </div>
                                                <!-- Edit Form Start Here -->
                                                <form method="POST" action="/savings/product/update/{{$prd['product_code']}}" enctype="multipart/form-data">
                                                    @csrf

                                                    <div class="modal-body">

                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label class="control-label">Name</label>
                                                                <input name="product_name" value="{{$prd['product_name']}}" class="form-control" required="required" type="text" placeholder="Enter Name">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="control-label">Duration</label>
                                                                <select name="duration" required="required" class="form-control" id="exampleSelect1">
                                                                    <option value="{{$prd['duration']}}">Current Duration</option>
                                                                    <option value="7">1 Week Plan</option>
                                                                    <option value="14">2 Weeks Plan</option>
                                                                    <option value="21">3 Weeks Plan</option>
                                                                    <option value="28">1 Month Plan</option>
                                                                    <option value="56">2 Months Plan</option>
                                                                    <option value="84">3 Months Plan</option>
                                                                    <option value="168">6 Months Plan</option>
                                                                    <option value="336">12 Months Plan</option>

                                                                </select>
                                                            </div>

                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label class="control-label">Minimum Limit</label>
                                                                <input name="min_limit" value="{{$prd['min_limit']}}" class="form-control" required="required" type="number" placeholder="Enter Minimum ">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="control-label">Maximum Limit</label>
                                                                <input name="max_limit" value="{{$prd['max_limit']}}" class="form-control" required="required" type="number" placeholder="Enter Maximum ">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label class="control-label">Status</label>
                                                                <select name="status" required="required" class="form-control" id="exampleSelect1">
                                                                    <option value="true">Active</option>
                                                                    <option value="false">Inactive</option>

                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="control-label">Product</label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-primary" type="submit">Update</button>
                                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                                <!-- Edit form start here -->

                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Modal -->
                                </td>


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

            @if (session()->has('gsuccess'))
            <div class="alert alert-dismissible alert-success">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('gsuccess') }}</p>
            </div>
            @endif
            @if (session()->has('gerror'))
            <div class="alert alert-dismissible alert-danger">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('gerror') }}</p>
            </div>
            @endif
            <div class="tile-title-w-btn">
                <h3 class="title"></h3>

                <div class="btn-group">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#groupModal">
                        <i class="fa fa-lg fa-plus"></i> Add Group </button>
                </div>

                <!-- Modal div -->
                <div id="groupModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add new Group</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/savings/groups/add" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="control-label">Name</label>
                                            <input name="title" class="form-control" required="required" type="text" placeholder="Enter Name">
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="control-label">Description *</label>
                                            <textarea name="description" required class="form-control" id="exampleTextarea" rows="3"></textarea>
                                        </div>
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
                <div class="table-responsive">
                    <table class="table table-hover table-bordered datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Created</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php $count = 0; ?>
                            @foreach($groups as $prd)
                            <?php $count++ ?>
                            <tr>
                                <td>{{$count}}</td>
                                <td><a href="/savings/groups/view/{{$prd['id']}}">{{$prd['title']}}</a></td>
                                <td>{{$prd['description']}}</td>
                                <td>{{$prd['created_at']}}</td>
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