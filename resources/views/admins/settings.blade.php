@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-user"></i> Variables</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">All Variables</a></li>
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
            <div class="alert alert-danger">
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
                    <button class="btn btn-danger" data-toggle="modal" data-target="#phoneModal">
                        <i class="fa fa-lg fa-cogs"></i> Update Phone </button>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                        <i class="fa fa-lg fa-plus"></i> Add/Edit Variable </button>
                </div>

                <!-- Start of Phone -->
                <div id="phoneModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>Phone Number Update</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/settings/phone/update" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">
                                    <div class="form-group col-md-12">
                                        <label class="control-label">Current Phone</label>
                                        <input name="current_phone" class="form-control" required="required" type="number" placeholder="Enter Phone Number">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="control-label">Update Phone</label>
                                        <input name="update_phone" class="form-control" required="required" type="number" placeholder="Enter Phone Number">
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

                <!-- End of Phone Modal -->
                <!-- Modal div -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>Add/Edit Variable</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/settings/add" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">


                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Admin Email</label>
                                            <input name="email" value="{{$setting['admin_email']}}" class="form-control" required="required" type="email" placeholder="Enter Email">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Registration Code</label>
                                            <input name="code"  value="{{$setting['code']}}" class="form-control" required="required" type="text" placeholder="Enter Code">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Maturity Days</label>
                                            <input name="days"  value="{{$setting['days']}}" class="form-control" required="required" type="number" placeholder="Enter Days">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Savings Rate (eg 5)</label>
                                            <input name="saving_rate"  value="{{$setting['saving_rate'] * 100 }}" class="form-control" required="required" type="number" placeholder="Enter Rate">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">System Rate (eg 5)</label>
                                            <input name="system_rate"  value="{{$setting['system_rate']  * 100 }}" class="form-control" required="required" type="number" placeholder="Enter Rate">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Developer Rate (eg 5)</label>
                                            <input name="developer_rate"  value="{{$setting['developer_rate']  * 100 }}" class="form-control" required="required" type="number" placeholder="Enter Rate">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Investor Rate (eg 5)</label>
                                            <input name="investor_rate"  value="{{$setting['investor_rate']  * 100 }}" class="form-control" required="required" type="number" placeholder="Enter Rate">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Status</label>

                                            <select name="status" required="required" class="form-control" id="exampleSelect1">
                                                <option value="true">Active</option>
                                                <option value="false">Inactive</option>

                                            </select>
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
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>Admin Email</th>
                                <th>Reg Code</th>
                                <th>Maturity Days</th>
                                <th>Saving Rate</th>
                                <th>System Rate</th>
                                <th>Developer Rate</th>
                                <th>Investor Rate</th>
                                <th>Status</th>
                                <th>Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($settings as $sett)
                            <tr>
                                <td>{{$sett['admin_email']}}</td>
                                <td>{{$sett['code']}}</td>
                                <td>{{$sett['days']}}</td>
                                <td>{{$sett['saving_rate']}}</td>
                                <td>{{$sett['system_rate']}}</td>
                                <td>{{$sett['developer_rate']}}</td>
                                <td>{{$sett['investor_rate']}}</td>
                                <td> @if($sett['status'] == 1)

                                    <span class="badge badge-success">Active</span>

                                    @else
                                    <span class="badge badge-warning">Inactive</span>
                                    @endif
                                </td>
                                <td>{{$sett['created_at']}}</td>

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

            <div class="tile-title-w-btn">
                <h3 class="title"></h3>

                <div class="btn-group">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#socialModal">
                        <i class="fa fa-lg fa-plus"></i>Add/Update Socials </button>
                </div>

                <!-- Modal div -->
                <div id="socialModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>Add/Edit Social</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/settings/socials" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">


                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Facebook</label>
                                            <input name="facebook" class="form-control" required="required" type="url" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Twitter</label>
                                            <input name="twitter" class="form-control" required="required" type="url" placeholder="">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Instagram</label>
                                            <input name="instagram" class="form-control" required="required" type="url" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Youtube</label>
                                            <input name="youtube" class="form-control" required="required" type="url" placeholder="">
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
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Url</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Facebook</td>
                                <td><a href="{{$social['facebook']}}" target="_blank"> {{$social['facebook']}}</a></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Twitter</td>
                                <td><a href="{{$social['twitter']}}" target="_blank"> {{$social['twitter']}}</a></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Instagram</td>
                                <td><a href="{{$social['instagram']}}" target="_blank"> {{$social['instagram']}}</a></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Youtube</td>
                                <td><a href="{{$social['youtube']}}" target="_blank"> {{$social['youtube']}}</a></td>
                            </tr>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection