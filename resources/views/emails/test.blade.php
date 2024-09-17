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
    <!-- Export Buttons -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">

</head>

<body class="app sidebar-mini">
    <main class="app-content">
        <div class="app-title">

            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">Invoice</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <section class="invoice">
                        <div class="row mb-4">
                            <div class="col-6">
                                <h2 class="page-header"><i class="fa fa-globe"></i> Imeja Developers Ltd</h2>
                            </div>
                            <div class="col-6">
                                <h5 class="text-right">Date: 01/01/2016</h5>
                            </div>
                        </div>
                        <div class="row invoice-info">
                            <div class="col-4">From
                                <address><strong>Imeja Developers Ltd.</strong><br>Roysambu, Thika Road,<br>Nairobi, Kenya<br>Email: info@imejadevelopers.co.ke</address>
                            </div>
                            <div class="col-4">To
                                <address><strong>Misiati Peter</strong><br>SMP Eventures<br>Nairobi, Kenya<br>Phone: +254706<br>Email: john.doe@example.com</address>
                            </div>
                            <div class="col-4"><b>Invoice #007612</b><br><br><b>Order ID:</b> 4F3S8J<br><b>Payment Due:</b> 2/22/2014<br><b>Account:</b> 968-34567</div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Qty</th>
                                            <th>Description</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Service Fee</td>
                                            <td>455-981-221</td>
                                            <td>KSh 6170</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </section>
                </div>
            </div>
        </div>
    </main>
    <!-- Essential javascripts for application to work-->
    <script src="{{ asset('assets/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{ asset('assets/js/popper.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/js/main.js')}}"></script>
    <script src="{{ asset('assets/js/plugins/pace.min.js')}}"></script>

</body>

</html>