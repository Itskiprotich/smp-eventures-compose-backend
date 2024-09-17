<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="description" content="SMP EVentures">
    <!-- Twitter meta-->
    <meta property="twitter:card" content="summary_large_image">
    <!-- Open Graph Meta-->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SMP EVentures">
    <meta property="og:title" content="SMP EVentures">

    <title>SMP EVentures</title>
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

        <header class="app-header"><a class="app-header__logo" href="/home">SMP EVentures</a>
            <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
            <!-- Navbar Right Menu-->
            <ul class="app-nav">

                <!--Notification Menu-->
                <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show Messages">Due Next Week <i class="fa fa-calendar fa-lg"></i><span id="conv-notf-count">{{ App\Models\Loans::dueToday() }}</span></a>
                    <ul class="app-notification dropdown-menu dropdown-menu-right">
                        <li class="app-notification__title"> {{ App\Models\Loans::dueToday() }} Loan(s) Due Next Week.
                        </li>
                        <div class="app-notification__content">
                            <?php
                            foreach (App\Models\Loans::loans_due_today() as $loan) {
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
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                        <div class="info">
                            <h5>Course Type Daily Sales</h5>
                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <div class="info-box">
                                        <div class="info-box-content">6</div>
                                        <div class="info-box-content">Live Classes</div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="info-box">
                                        <div class="info-box-content">6</div>
                                        <div class="info-box-content">Course</div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="info-box">
                                        <div class="info-box-content">6</div>
                                        <div class="info-box-content">Meeting</div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="widget-small primary coloured-icon"><i class="icon fa fa-dollar fa-3x"></i>
                                    <div class="info">
                                        <div class="info-box-content">6</div>
                                        <div class="info-box-content">Today Sales</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                        <div class="info">
                            <h5>Platform Income</h5>
                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <div class="info-box">
                                        <div class="info-box-content">6</div>
                                        <div class="info-box-content">Today</div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="info-box">
                                        <div class="info-box-content">6</div>
                                        <div class="info-box-content">Month</div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="info-box">
                                        <div class="info-box-content">6</div>
                                        <div class="info-box-content">Year</div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="widget-small primary coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                                    <div class="info">
                                        <div class="info-box-content">6</div>
                                        <div class="info-box-content">Total Income</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                        <div class="info">
                            <h5>Sales Count</h5>
                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <div class="info-box">
                                        <div class="info-box-content">6</div>
                                        <div class="info-box-content">Today</div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="info-box">
                                        <div class="info-box-content">6</div>
                                        <div class="info-box-content">Month</div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="info-box">
                                        <div class="info-box-content">6</div>
                                        <div class="info-box-content">Yeah</div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="widget-small primary coloured-icon"><i class="icon fa fa-shopping-cart fa-3x"></i>
                                    <div class="info">
                                        <div class="info-box-content">6</div>
                                        <div class="info-box-content">Total Sales</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-shopping-cart fa-3x"></i>
                        <div class="info">
                            <h5>New Sale</h5>
                            <p>{{$data['new_sale']}}</p>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-comments fa-3x"></i>
                        <div class="info">
                            <h5>New Comment</h5>
                            <p><b>{{$data['new_comment']}}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-shopping-basket fa-3x"></i>
                        <div class="info">
                            <h5>New Support Ticket</h5>
                            <p><b>{{$data['new_tickets']}}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-book fa-3x"></i>
                        <div class="info">
                            <h5>Course Pending Review</h5>
                            <p><b>{{$data['review_courses']}}</b></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="tile">
                        <h5 class="tile-title">Sales Statisctics </h5>
                        <div class="embed-responsive embed-responsive-16by9">
                            <canvas class="embed-responsive-item" id="registrationsChart"></canvas>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h5 class="tile-title">Recent Comments</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="mysave">
                                <thead>
                                    <tr>


                                        <th>Reference</th>
                                        <th>Name</th>
                                        <th>Amount</th>

                                    </tr>

                                </thead>
                                <tbody>
                                   

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-book fa-3x"></i>
                        <div class="info">
                            <h5>Total Courses</h5>
                            <p>{{$data['total_courses']}}</p>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small info coloured-icon"><i class="icon fa fa-comments fa-3x"></i>
                        <div class="info">
                            <h5>In Review Courses</h5>
                            <p><b>{{$data['review_courses']}}</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-calendar fa-3x"></i>
                        <div class="info">
                            <h5>Total Duration</h5>
                            <p><b>{{$data['total_duration']}}</b> hrs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                            <h5>Total Sales</h5>
                            <p><b>{{$data['total_sales']}}</b></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="tile">
                        <h5 class="tile-title">Top Selling Courses </h5>
                        <div class="embed-responsive embed-responsive-16by9">
                            <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h5 class="tile-title">Top Selling Instructors</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h5 class="tile-title">Most Active Students</h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="tile">
                        <h5 class="tile-title">User Registrations </h5>
                        <div class="embed-responsive embed-responsive-16by9">
                            <canvas class="embed-responsive-item" id="registrationsChart"></canvas>
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
        var registrations = {
            labels: <?= $data['dates'] ?>,
            datasets: [{
                    label: "Registrations",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgb(0,128,0)",
                    pointColor: "rgb(0,128,0)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: <?= $data['registrations'] ?>,
                },

            ]
        };


        var ctxl = $("#registrationsChart").get(0).getContext("2d");
        var lineChart = new Chart(ctxl).Line(registrations);
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