@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i> Withdrawals</h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Savings Withdrawals</a></li>
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

                <div class="btn-group"><button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-lg fa-plus"></i> Initiate </button> </div>

                <!-- Modal div -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Initiate Withdrawal</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <form method="POST" action="/savings/initiate/manual" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    <div class="form-group col-md-12">
                                        <label class="control-label">Phone</label>
                                        <select name="phone" required="required" style="width: 100%" class="form-control selector phoneSelect">

                                            @foreach($customers as $cust)
                                            <option value="{{$cust['phone']}}">
                                                {{$cust['phone']}}-{{$cust['firstname']}} {{$cust['lastname']}}
                                            </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="control-label">Product</label>
                                        <select name="product" required="required" style="width: 100%" class="form-control selector productSelect">

                                            @foreach($products as $prod)
                                            <option value="{{$prod['product_code']}}">{{$prod['product_name']}}
                                            </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">

                                        <p class="availableBalance"> Available Balance:</p>
                                        <br>
                                        <label class="control-label">Amount</label>
                                        <input name="amount" class="form-control" required="required" type="number" placeholder="Enter Amount">
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
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Amount</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Request Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawal as $with)
                        <tr>
                            <td>{{$with['firstname']}} {{$with['lastname']}}</td>
                            <td>{{$with['phone']}}</td>
                            <td>{{$with['amount']}}</td>
                            <td>{{$with['product_name']}}</td>
                            <td> @if($with['status'] == 1)
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{$with['created_at']}}</td>

                            <td>
                                <div class="btn-group">
                                    <form method="POST" action="/savings/approve/withdrawal/{{$with['reference']}}">
                                        @csrf
                                        <input name="_method" type="hidden" value="POST">
                                        <button type="submit" class="btn-success btn-flat show_confirm_approve" data-toggle="tooltip" title='Aprrove'> <i class="fa fa-check">
                                            </i></button>
                                    </form>
                                    <form method="POST" action="/savings/reject/withdrawal/{{$with['reference']}}">
                                        @csrf
                                        <input name="_method" type="hidden" value="POST">
                                        <button type="submit" class="btn-danger btn-flat show_confirm" data-toggle="tooltip" title='Reject'> <i class="fa fa-trash">
                                            </i></button>
                                    </form>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">

$(function() { // This ensures the code runs when the DOM is ready
        console.log('Script loaded');

        // Log select elements
        console.log('Phone Select:', $('#phoneSelect'));
        console.log('Product Select:', $('#productSelect'));
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to reject this?')) {
            e.preventDefault();
        }
    });

    $('.show_confirm_approve').click(function(e) {
        if (!confirm('Are you sure you want to approve this record?')) {
            e.preventDefault();
        }
    });
    $('.phoneSelect').change(function(e) {
        fetchAvailableBalance();
        console.log('Phone select changed to:', $(this).val());
    });
    $('.productSelect').change(function(e) {
        fetchAvailableBalance();
        console.log('Product select changed to:', $(this).val());

    });

    function fetchAvailableBalance(){
        const phone = $('.phoneSelect').val();
        const product = $('.productSelect').val();

       if (phone && product) {
            // Make API call
            $.ajax({
                url: '/api/getAvailableBalance',
                method: 'GET',
                data: { phone: phone, product: product },
                success: function(data) {
                    $('.availableBalance').text(`Available Balance: ${data.balance}`);
                },
                error: function(error) {
                    console.error('Error fetching available balance:', error);
                    $('.availableBalance').text('Error fetching available balance');
                }
            });
        }
    }
});
</script>

 
@endsection