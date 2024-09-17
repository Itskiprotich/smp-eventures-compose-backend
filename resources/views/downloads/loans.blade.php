<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Loan Statement: {{$data['customer_name']}}</title>

    <style>
        .header {
            background-color: #C5B18F;
            color: #fff;
            padding: 20px;
            display: flex;
            align-items: center;
        }

        .header h1 {
            margin: 0;
        }

        .header p {
            margin: 0;
            font-size: 14px;
        }

        .company-details {
            display: flex;
            align-items: center;
            margin-left: auto;
        }

        .company-details img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .three-column-table {
            width: 100%;
            border-collapse: collapse;
        }

        .three-column-table th,
        .three-column-table td {
            border: 1px solid black;
            padding: 8px;
        }

        .three-column-table th {
            background-color: #f2f2f2;
        }

        .three-column-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>

</head>

<body onload="init()">
    <div class="header">
        <div class="company-details"> 
            <div>
                <h2>SMP Eventures</h2>
                <p>Ruiri, Membley, Kenya</p>
            </div>
        </div>
    </div>

    <h2 style="text-align: center; ">Personal Loan: {{$data['customer_name']}}</h2>

    <table class="three-column-table">

        <tr>
            <td><b>Reference</b></td>
            <td>{{$data['loan_ref']}}</td>
            <td><b>Name</b></td>
            <td>{{$data['customer_name']}}</td>
        </tr>
        <tr>
            <td><b>Phone</b></td>
            <td>{{$data['phone']}}</td>
            <td><b>Principal</b></td>
            <td>{{$data['principle']}}</td>
        </tr>
        <tr>
            <td><b>Interest</b></td>
            <td>{{$data['interest']}}</td>
            <td><b>Admin Fee</b></td>
            <td>{{$data['admin_fee']}}</td>
        </tr>
        <tr>
            <td><b>Duration</b></td>
            <td>{{$data['repayment_period']}}</td>
            <td><b>Repayment Date</b></td>
            <td>{{$data['repayment_date']}}</td>
        </tr>
        <tr>
            <td><b>Penalty Amount</b></td>
            <td>{{$data['penalty_amount']}}</td>
            <td><b>Next Penalty Date</b></td>
            <td>{{$data['penalty_date']}}</td>
        </tr>
        <tr>
            <td><b>Loan Amount</b></td>
            <td>{{$data['loan_amount']}}</td>
            <td><b>Loan Balance</b></td>
            <td>{{$data['loan_balance']}}</td>
        </tr>
    </table>
    <br>

    <?php
    if ($schedules) { ?>
        <h4 style="text-align: center; ">Loan Schedule</h4>
        <table class="three-column-table">
            <tr>
                <th>Date</th>
                <th>Reference</th>
                <th>Amount</th>
                <th>Due Date</th>
            </tr>
            <tbody>
                @foreach($schedules as $schedule)
                <tr>

                    <td>{{$schedule['phone']}}</td>
                    <td>{{$schedule['loan_ref']}}</td>
                    <td>{{$schedule['amount']}}</td>
                    <td>{{$schedule['due_date']}}</td>


                </tr>
                @endforeach

            </tbody>

        </table>

    <?php } ?>
    <?php
    if ($repayments) { ?>
        <h4 style="text-align: center; ">Repayments</h4>
        <table class="three-column-table">
            <tr>
                <th>Date</th>
                <th>Reference</th>
                <th>Amount</th>
                <th>Date Paid</th>
                <th>Balance</th>
                <th>Initiator</th>
            </tr>
            <tbody>
                @foreach($repayments as $repayment)
                <tr>

                    <td>{{$repayment['date_paid']}}</td>
                    <td>{{$repayment['reference']}}</td>
                    <td>{{$repayment['paid_amount']}}</td>
                    <td>{{$repayment['date_paid']}}</td>
                    <td>{{$repayment['balance']}}</td>
                    <td>{{$repayment['initiator']}}</td>
                </tr>
                @endforeach

            </tbody>

        </table>

    <?php } ?>
    <?php
    if ($reminders) { ?>
        <h4 style="text-align: center; ">Reminders</h4>
        <table class="three-column-table">
            <tr>
                <th>#</th>
                <th>Message</th>
            </tr>
            <tbody>
                <?php $count = 0; ?>
                @foreach($reminders as $rem)
                <?php $count++ ?>
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$rem['message']}}</td>
                </tr>
                @endforeach

            </tbody>

        </table>

    <?php } ?>

</body>

</html>