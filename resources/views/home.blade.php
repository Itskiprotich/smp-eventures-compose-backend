<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="description" content="Jetpack Compose">
    <!-- Twitter meta-->
    <meta property="twitter:card" content="summary_large_image">
    <!-- Open Graph Meta-->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Jetpack Compose">
    <meta property="og:title" content="Jetpack Compose">

    <title>Jetpack Compose</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="icon" href="{{ url('images/smp.jpg') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--  -->

</head>

<body class="app sidebar-mini">
    <div id="app">

        <header class="app-header"><a class="app-header__logo" href="/home">Jetpack Compose</a>
            <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
            <!-- Navbar Right Menu-->
            <ul class="app-nav">

                <!--Notification Menu-->
                <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show Messages">Due Next Week <i class="fa fa-calendar fa-lg"></i><span id="conv-notf-count"> {{ App\Models\Loans::loans_next_week_count() }}</span></a>
                    <ul class="app-notification dropdown-menu dropdown-menu-right">
                        <li class="app-notification__title"> {{ App\Models\Loans::loans_next_week_count() }} Loan(s) Due Next Week.
                        </li>
                        <div class="app-notification__content">
                            <?php
                            foreach (App\Models\Loans::loans_next_week() as $loan) {
                                echo '<li><a class="app-notification__item" href="' . url('/loans/view/' . $loan->loan_ref) . '"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fa fa-money fa-stack-1x fa-inverse"></i></span></span>
                            <div>
                                <p class="app-notification__message">' . $loan->customer_name . '</p>
                                <p class="app-notification__meta">' . $loan->loan_code . '</p>
                            </div></a></li>';
                            }


                            ?>

                        </div>
                        <li class="app-notification__footer"><a href="/loans/weekly">View All.</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show Messages"><i class="fa fa-commenting-o fa-lg"></i><span id="conv-notf-count">{{ App\Models\ChatRoom::countMessages() }}</span></a>
                    <ul class="app-notification dropdown-menu dropdown-menu-right">
                        <li class="app-notification__title">You have {{ App\Models\ChatRoom::countMessages() }} new messages.
                        </li>
                        <div class="app-notification__content">

                            <div class="app-notification__content">

                            </div>
                        </div>
                        <li class="app-notification__footer"><a href="/chats">Open Chats.</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show notifications"><i class="fa fa-bell-o fa-lg"></i><span id="conv-notf-count">{{ App\Models\Loans::countLoans() }}</span></a>
                    <ul class="app-notification dropdown-menu dropdown-menu-right">
                        <li class="app-notification__title">You have {{ App\Models\Loans::countLoans() }} new loans.
                        </li>
                        <div class="app-notification__content">

                            <div class="app-notification__content">

                            </div>
                        </div>
                        <li class="app-notification__footer"><a href="/loans">See all notifications.</a></li>
                    </ul>
                </li>
                <!-- User Menu-->
                <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
                    <ul class="dropdown-menu settings-menu dropdown-menu-right">

                        <li><a class="dropdown-item" href="/account"><i class="fa fa-user fa-lg"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="/switch"><i class="fa fa-refresh fa-lg"></i> Switch Branch</a></li>
                        <li><a class="dropdown-item" href="{{route('logout')}} " onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><i class="fa fa-sign-out fa-lg"></i>
                                Logout</a>
                            <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </header>
        <!-- Sidebar menu-->
        <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
        <aside class="app-sidebar">

            @include('layouts/common')
        </aside>
        <main class="app-content">
            <div class="app-title">
                <div>
                    <h1><i class="fa fa-dashboard"></i> Dashboard: {{$data['branch_name']}}</h1>

                </div>
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                        <div class="info">
                            <h5>Borrowers</h5>
                            <p><b>{{ $data['all_customers'] }}</b> Total <b>{{ $data['approved_customers'] }}</b>Active
                            </p>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
                        <div class="info">
                            <h5>Principal Released</h5>
                            <p><b>{{ $data['disbursed_principal'] }}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-shopping-basket fa-3x"></i>
                        <div class="info">
                            <h5>Total Collections</h5>
                            <p><b>{{ $data['total_repayments'] }}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h5>Outstanding Loans</h5>
                            <p><b>{{ $data['unpaid_loans'] }}</b></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h5>Total Outstanding Principal</h5>
                            <p><b>{{ $data['outstanding_unpaid_principal'] }}</b></p>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h5>Total Loans</h5>
                            <p><b>{{ $data['total_loans'] }}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h5>Open Loans</h5>
                            <p><b>{{ $data['open_loans'] }}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h5>Fully Paid Loans</h5>
                            <p><b>{{ $data['fully_paid'] }}</b></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h5>Paybill</h5>
                            <p><b>{{ $data['paybill_balance'] }}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h5>System Balance</h5>
                            <p><b>{{ $data['system_balance'] }}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h5>Partialy Paid Loans</h5>
                            <p><b>{{ $data['all_partially'] }}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h5>Overdue Loans</h5>
                            <p><b>{{ $data['all_overdue'] }}</b></p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="tile">
                        <h5 class="tile-title">Loan Collections vs Loans Released </h5>
                        <div class="embed-responsive embed-responsive-16by9">
                            <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
                        </div>
                        <p style="color:yellow">Disbursements</p>
                        <p style="color:green">Repayments</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h5 class="tile-title">Open Loans Status - To Date</h5>

                        <div class="embed-responsive embed-responsive-16by9">
                            <canvas class="embed-responsive-item" id="pieChartDemo"></canvas>
                        </div>

                    </div>
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h5>Payouts vs Interest</h5>
                            <p><b><span class="badge badge-primary">Paid KES</span> {{ $data['paid_investor'] }}- <span class="badge badge-success">In Progress KES</span> {{$data['unpaid_investor']}}</b><br><span class="badge badge-success">Total Interest KES</span> {{ $data['paid_interest'] }}</p>

                        </div>
                    </div>


                </div>
                <div class="col-md-2">
                    <div class="tile">
                        <h5 class="tile-title">Loan Summary</h5>
                        <div class="bs-component">

                            <ul class="list-group text white">
                                <a href="/loans/weekly">
                                    <li class="list-group-item text-white bg-success"><span class="tag tag-default tag-pill float-xs-right"> {{ $data['tomorrow']}}</span> Loans Due Next Week</li>
                                </a>
                                <a href="/loans/overdue">
                                    <li class="list-group-item  text-white bg-warning"><span class="tag tag-default tag-pill float-xs-right"> {{ $data['past']}}</span> Loans Past Maturity</li>
                                </a>
                            </ul>
                        </div>
                    </div>
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h5>Savings Interest</h5>
                            <p> {{ $data['available'] }}</p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                        <div class="info">
                            <h5>Interest</h5>
                            <p><b>{{ $data['disbursed_interest'] }}</b></p>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
                        <div class="info">
                            <h5>Admin Fee</h5>
                            <p><b>{{ $data['disbursed_admin_fee'] }}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-shopping-basket fa-3x"></i>
                        <div class="info">
                            <h5>Total Savings</h5>
                            <p><b>{{ $data['total_savings'] }}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small danger coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h5>Penalties</h5>
                            <p><b>{{ $data['penalties'] }}</b></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start of Collections -->
            <div class="row">

                <div class="col-md-2 col-lg-3">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-shopping-basket fa-1x"></i>
                        <div class="info">
                            <h5>Repayments Today</h5>
                            <p><a href="/loans/repayments"><b>{{ $data['collection_today'] }}</b></a></p>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-shopping-basket fa-1x"></i>
                        <div class="info">
                            <h5>Repayments this Month</h5>
                            <p><a href="/loans/repayments"><b>{{ $data['collection_month'] }}</b></a></p>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small danger coloured-icon"><i class="icon fa fa-shopping-basket fa-1x"></i>
                        <div class="info">
                            <h5>Repayments this Year</h5>
                            <p><a href="/loans/repayments"><b>{{ $data['collection_year'] }}</b></a></p>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-shopping-basket fa-1x"></i>
                        <div class="info">
                            <h5>Repayments All Time</h5>
                            <p><a href="/loans/repayments"><b>{{ $data['collection_all'] }}</b></a></p>

                        </div>
                    </div>
                </div>
            </div>

            <!-- End of Collections -->
            <!-- Disbursements -->
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-shopping-basket fa-3x"></i>
                        <div class="info">
                            <h5>Disbursement Today</h5>
                            <p><b>{{ $data['disburse_today'] }}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                        <div class="info">
                            <h5>Disbursements This Month</h5>
                            <p><b>{{ $data['disburse_month'] }}</b></p>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
                        <div class="info">
                            <h5>Disbursements This Year</h5>
                            <p><b>{{ $data['disburse_year'] }}</b></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
                        <div class="info">
                            <h5>Interest This Month</h5>
                            <p><b>{{ $data['interest_month'] }}</b></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="tile">
                        <h5 class="tile-title">Gender Distribution </h5>
                        <div class="embed-responsive embed-responsive-16by9">
                            <canvas class="embed-responsive-item" id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="tile">
                        <h5 class="tile-title">Savings vs Withdrawals</h5>
                        <div class="embed-responsive embed-responsive-16by9">
                            <canvas class="embed-responsive-item" id="repayChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="tile">
                        <h5 class="tile-title">Savings Products Summary</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered imeja" id="mysave">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Name</th>
                                        <th>Amount</th>

                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach($data['savingsproducts'] as $sm)
                                    <tr>

                                        <td><a href="/savings/per-product/{{$sm['product_code']}}"> {{$sm['product_code']}}</a></td>
                                        <td>{{$sm['product_name']}}</td>
                                        <td>{{$sm['revenue']}}</td>
                                    </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="tile">
                        <h5 class="tile-title">Billing Summary</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered imeja" id="mysave">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Month</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count = 0; ?>
                                    @foreach($data['billings'] as $sm)
                                    <?php $count++; ?>
                                    <tr>
                                        <td>{{$count}}</td>
                                        <td>{{$sm['month']}} {{$sm['year']}}</td>
                                        <td>{{$sm['total_earnings']}}</td>
                                    </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Interest -->
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-shopping-basket fa-3x"></i>
                        <div class="info">
                            <h5>Current Interest Balance</h5>
                            <p><b>{{ $data['current'] }}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                        <div class="info">
                            <h5>Available Interest Balance</h5>
                            <p><b>{{ $data['available'] }}</b></p>

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Interest -->
            <div class="row">
                <div class="col-md-6">
                    <div class="tile">
                        <h5 class="tile-title">Daily Interest vs Admin Fee</h5>
                        <div class="embed-responsive embed-responsive-16by9">
                            <canvas class="embed-responsive-item" id="interestChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="tile">
                        <h5 class="tile-title">Interest Withdrawal</h5>
                        <div class="embed-responsive embed-responsive-16by9">
                            <canvas class="embed-responsive-item" id="interestWithdrawalChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-shopping-basket fa-3x"></i>
                        <div class="info">
                            <h5>Withdrawals Today</h5>
                            <p><b>{{ $data['w_today'] }}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                        <div class="info">
                            <h5>Withdrawals This Month</h5>
                            <p><b>{{ $data['w_month'] }}</b></p>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
                        <div class="info">
                            <h5>Withdrawals This Year</h5>
                            <p><b>{{ $data['w_year'] }}</b></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
                        <div class="info">
                            <h5>Withdrawals all All</h5>
                            <p><b>{{ $data['w_all'] }}</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>


    <script src="{{ asset('assets/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{ asset('assets/js/popper.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/js/main.js')}}"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="{{ asset('assets/js/plugins/pace.min.js')}}"></script>

    <!-- Page specific javascripts-->
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('assets/js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/dropzone.js')}}"></script>
    <script type="text/javascript">
        $('#datatable').DataTable({
            dom: 'lBfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    </script>
    <script type="text/javascript">
        $('#repayments').DataTable({
            dom: 'lBfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        $('.imeja').DataTable({
            dom: 'lBfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    </script>

    <script type="text/javascript">
        // Login Page Flipbox control
        $('.login-content [data-toggle="flip"]').click(function() {
            $('.login-box').toggleClass('flipped');
            return false;
        });
    </script>

    <script src="{{ asset('assets/js/plugins/pace.min.js') }}"></script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="{{ asset('assets/js/plugins/chart.js') }}"></script>
    <script type="text/javascript">
        $('#sl').on('click', function() {
            $('#tl').loadingBtn();
            $('#tb').loadingBtn({
                text: "Signing In"
            });
        });

        $('#el').on('click', function() {
            $('#tl').loadingBtnComplete();
            $('#tb').loadingBtnComplete({
                html: "Sign In"
            });
        });

        $('#startDate').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true

        });
        $('#endDate').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });

        $('#demoSelect').select2();
    </script>
    <script type="text/javascript">
        var data = {
            labels: <?= $data['dates'] ?>,
            datasets: [{
                    label: "Disbursements",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgb(255,255,0)",
                    pointColor: "rgb(255,255,0)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: <?= $data['disburse'] ?>,
                },
                {
                    label: "Repayments",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgb(0,128,0)",
                    pointColor: "rgb(0,128,0)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: <?= $data['repay'] ?>,
                }
            ],
            options: {
                legend: {
                    display: true,
                    position: 'top'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        };
        // var interest = {
        //     labels: <?= $data['dates'] ?>,
        //     datasets: [{
        //             label: "Interest",
        //             fillColor: "rgba(151,187,205,0.2)",
        //             strokeColor: "rgb(0,128,0)",
        //             pointColor: "rgb(0,128,0)",
        //             pointStrokeColor: "#fff",
        //             pointHighlightFill: "#fff",
        //             pointHighlightStroke: "rgba(151,187,205,1)",
        //             data: <?= $data['interest'] ?>,
        //         },
        //         {
        //             label: "Admin Fee",
        //             fillColor: "rgba(151,187,205,1)",
        //             strokeColor: "rgb(0,128,0)",
        //             pointColor: "rgb(0,128,0)",
        //             pointStrokeColor: "#fff",
        //             pointHighlightFill: "#fff",
        //             pointHighlightStroke: "rgba(151,187,205,1)",
        //             data: <?= $data['admin'] ?>,
        //         },
        //     ],
        //     options: {
        //         legend: {
        //             display: true,
        //             position: 'top'
        //         },
        //         scales: {
        //             yAxes: [{
        //                 ticks: {
        //                     beginAtZero: true
        //                 }
        //             }]
        //         }
        //     }
        // };
        var interest = {
            labels: <?= $data['dates'] ?>,
            datasets: [{
                    label: "Interest",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgb(0,128,0)",
                    pointColor: "rgb(0,128,0)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: <?= $data['interest'] ?>,
                },
                {
                    label: "Admin Fee",
                    fillColor: "rgba(151,187,205,1)",
                    strokeColor: "rgb(0,128,0)",
                    pointColor: "rgb(0,128,0)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: <?= $data['admin'] ?>,
                }
            ],
            options: {
                legend: {
                    display: true,
                    position: 'top'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + tooltipItem.yLabel;
                        }
                    }
                }
            }
        };


        var interestWithdraw = {
            labels: <?= $data['dates'] ?>,
            datasets: [{
                    label: "Interest Withdrawal",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgb(0,128,0)",
                    pointColor: "rgb(0,128,0)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: <?= $data['interestWithdraw'] ?>,
                }

            ],
            options: {
                legend: {
                    display: true,
                    position: 'top'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + tooltipItem.yLabel;
                        }
                    }
                }
            }
        };



        var saving_data = {
            labels: <?= $data['dates'] ?>,
            datasets: [{
                    label: "Deposits",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: <?= $data['depos'] ?>,
                },
                {
                    label: "Withdrawals",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: <?= $data['withs'] ?>,
                }
            ],
            options: {
                legend: {
                    display: true,
                    position: 'top'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        };

        var gender = [{
                value: <?= $data['male'] ?>,
                color: "#FF5A5E",
                highlight: "#FF5A5E",
                label: "Male"
            },
            {
                value: <?= $data['female'] ?>,
                color: "#46BFBD",
                highlight: "#46BFBD",
                label: "Female"

            }
        ]
        var pdata = [{
                value: <?= $data['all_paid'] ?>,
                color: "#00FF00",
                highlight: "#00FF00",
                label: "Paid Loans"
            },
            {
                value: <?= $data['all_disbursed'] ?>,
                color: "#46BFBD",
                highlight: "#46BFBD",
                label: "Disbursed Loans"
            },
            {
                value: <?= $data['all_overdue'] ?>,
                color: "#FF0000",
                highlight: "#FF0000",
                label: "Overdue Loans"
            },
            {
                value: <?= $data['all_partially'] ?>,
                color: "#FFFF00",
                highlight: "#FFFF00",
                label: "Partialy Paid"
            },
            {
                value: <?= $data['all_unpaid'] ?>,
                color: "#F7464A",
                highlight: "#FF5A5E",
                label: "UnPaid"
            }
        ]




        var ctxl = $("#interestWithdrawalChart").get(0).getContext("2d");
        var lineChart = new Chart(ctxl).Line(interestWithdraw);

        var ctxl = $("#interestChart").get(0).getContext("2d");
        var lineChart = new Chart(ctxl).Line(interest);

        var ctxl = $("#lineChartDemo").get(0).getContext("2d");
        var lineChart = new Chart(ctxl).Line(data);

        var ctxp = $("#pieChartDemo").get(0).getContext("2d");
        var pieChart = new Chart(ctxp).Pie(pdata);


        var ctxd = $("#genderChart").get(0).getContext("2d");
        var doughnutChart = new Chart(ctxd).Doughnut(gender);


        var ctxb = $("#repayChart").get(0).getContext("2d");
        var doughnutChart = new Chart(ctxb).Bar(saving_data);
    </script>
    <!-- Google analytics script-->
    <script type="text/javascript">
        if (document.location.hostname == 'pratikborsadiya.in') {
            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
            ga('create', 'UA-72504830-1', 'auto');
            ga('send', 'pageview');
        }
    </script>
</body>


</html>