@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-user"></i> Discounts</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">All Discounts</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-8">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h3 class="title"></h3>
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Coupon</th>
                                <th>Times</th>
                                <th>Expiry</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0; ?>
                            @foreach($discounts as $disc)
                            <?php $count++; ?>
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$disc->title}}</td>
                                <td> @if($disc['is_percentage'] == 0)

                                    <span class="badge badge-success">Fixed Amount</span>

                                    @else
                                    <span class="badge badge-success">Percentage</span>
                                    @endif
                                </td>
                                <td>{{$disc->amount}}</td>
                                <td>{{$disc->coupon}}</td>
                                <td>{{$disc->usable_times}}</td>
                                <td>{{$disc->expiry_time}}</td>

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
            @if (session()->has('success'))
            <div class="alert alert-dismissible alert-success">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('success') }}</p>
            </div>
            @endif
            @if (session()->has('error'))
            <div class="alert alert-dismissible alert-success">
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
                <h5>Add Discount</h5>
            </div>
            <form method="POST" action="/learning/courses/discounts/add" enctype="multipart/form-data">
                @csrf
                <div class="tile-body">


                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Title</label>
                            <input name="title" class="form-control" required="required" type="text" placeholder="Enter Title">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Discount type</label>
                            <select name="is_percentage" required="required" class="form-control" id="exampleSelect1">
                                <option value="1">Percentage</option>
                                <option value="0">Fixed Amount</option>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Discount Amount/Rate</label>
                            <input name="amount" class="form-control" required="required" type="number" placeholder="">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Coupon</label>
                            <input name="coupon" class="form-control" required="required" type="text" placeholder="">
                        </div>

                    </div>
                    <div class="row">

                        <div class="form-group col-md-6">
                            <label class="control-label">Usable Times</label>
                            <input name="usable_times" class="form-control" required="required" type="text" placeholder="">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="control-label">Expiry Date</label>
                            <input name="expiry_time" class="form-control" required="required" type="date" placeholder="">

                        </div>

                    </div>
                    <div class="tile-footer">
                        <div class="form-group col-md-4 align-self-end">
                            <button class="btn btn-success" type="submit">Register</button>
                        </div>

                    </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>

@endsection