@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-money"></i> Product </h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">View Product</a></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">


            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <h5 class="tile-title"> Details</h5>
            <div class="tile-body">

                <form method="POST" action="/savings/product/update/{{ $data['product_code'] }}">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Product Code</label>
                            <input name="product_code" disabled value="{{ $data['product_code'] }}" class="form-control" required="required" type="text" placeholder="Enter First Name">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Product Name</label>
                            <input name="product_name" value="{{ $data['product_name'] }}" class="form-control" required="required" type="text" placeholder="Enter Last Name">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
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
                        <div class="form-group col-md-4">
                            <label class="control-label">Status</label>
                            <select name="status" required="required" class="form-control" id="exampleSelect1">
                                <option value="true">Active</option>
                                <option value="false">Inactive</option>

                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Minimum</label>
                            <input name="min_limit" value="{{ $data['min_limit'] }}" class="form-control" required="required" type="number" placeholder="Enter Minimum">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Maximum</label>
                            <input name="max_limit" value="{{ $data['max_limit'] }}" class="form-control" required="required" type="number" placeholder="Enter Maximum">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-5 ">
                            <a href="/savings/products" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-lg fa-check-circle"></i>Back</a>

                        </div>
                        <div class="form-group col-md-5 ">
                            <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection