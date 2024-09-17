@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-user"></i> Float Balance</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#"> Float Balance</a></li>
    </ul>
</div>
<div class="row">
    <!-- To be worked on -->
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h3 class="title"></h3>
                @if (session()->has('success'))
                <div class="alert alert-dismissible alert-success">
                    <button class="close" type="button" data-dismiss="alert">×</button>
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                @if (session()->has('error'))
                <div class="alert alert-dismissible alert-warning">
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
                <div class="btn-group"><button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Deposit Float </button> </div>

                <!-- Modal div -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>Deposit Float</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/admin/float/add" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    <div class="form-group">
                                        <label class="control-label">Amount</label>
                                        <input name="amount" class="form-control" required="required" type="number" placeholder="Enter Amount">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Narration</label>
                                        <textarea name="narration" class="form-control" id="exampleTextarea" rows="3"></textarea>
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
            <div class="row" style="margin-bottom: 2rem;">
                <div class="col-lg-12">

                    <div class="bs-component">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#pending">Pending Transaction</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#approved">Approved Transactions</a></li>


                        </ul>
                        <div class="tab-content" id="myTabContent">

                            <div class="tab-pane fade active show" id="pending">
                                <hr>
                                <br>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered" id="datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Reference</th>
                                                    <th>Amount</th>
                                                    <th>Balance</th>
                                                    <th>Description</th>
                                                    <th>Action By</th>
                                                    <th>Approved By</th>
                                                    <th>Status</th>
                                                    <th>Transaction Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $count=0;?>
                                                @foreach($pending as $dt)
                                                <?php $count++;?>
                                                <tr>
                                                    <td>{{$count}}</td>
                                                    <td>{{$dt['reference']}}</td>
                                                    <td>{{$dt['amount']}}</td>
                                                    <td>{{$dt['balance']}}</td>
                                                    <td>{{$dt['narration']}}</td>
                                                    <td>{{$dt['action_by']}}</td>
                                                    <td>{{$dt['approved_by']}}</td>
                                                    <td> @if($dt['status'] == 1)
                                                        <span class="badge badge-success">Active</span>
                                                        @else
                                                        <span class="badge badge-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>{{$dt['created_at']}}</td>
                                                    <td>
                                                        <form method="POST" action="/admin/float/approve/{{$dt['reference']}}">
                                                            @csrf
                                                            <input name="_method" type="hidden" value="POST">
                                                            <button type="submit" class="btn-success btn-flat show_confirm" data-toggle="tooltip" title='Approve'> <i class="fa fa-check"> </i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="approved">
                                <hr>
                                <br>
                                <div class="row">

                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Reference</th>
                                                    <th>Amount</th>
                                                    <th>Balance</th>
                                                    <th>Description</th>
                                                    <th>Action By</th>
                                                    <th>Approved By</th>
                                                    <th>Status</th>
                                                    <th>Transaction Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $c=0;?>
                                                @foreach($approved as $dt)
                                                <?php $c++;?>
                                                <tr>
                                                    <td>{{$c}}</td>
                                                    <td>{{$dt['reference']}}</td>
                                                    <td>{{$dt['amount']}}</td>
                                                    <td>{{$dt['balance']}}</td>
                                                    <td>{{$dt['narration']}}</td>
                                                    <td>{{$dt['action_by']}}</td>
                                                    <td>{{$dt['approved_by']}}</td>
                                                    <td> @if($dt['status'] == 1)
                                                        <span class="badge badge-success">Active</span>
                                                        @else
                                                        <span class="badge badge-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>{{$dt['created_at']}}</td>
                                                    <td>
                                                        <form method="POST" action="/admin/float/approve/{{$dt['reference']}}">
                                                            @csrf
                                                            <input name="_method" type="hidden" value="POST">
                                                            <button type="submit" class="btn-success btn-flat show_confirm" data-toggle="tooltip" title='Approve'> <i class="fa fa-check"> </i></button>
                                                        </form>
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
                </div>
            </div>

        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to approve this?')) {
            e.preventDefault();
        }
    });
</script>
@endsection