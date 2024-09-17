<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Saving Statement: {{$data['name']}}</title>

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

    <h2 style="text-align: center; ">Personal Savings: {{$data['name']}}</h2>

    <table class="three-column-table">
        <tr>
            <td><b>First Name</b></td>
            <td>{{$data['firstname']}}</td>
            <td><b>Last Name</b></td>
            <td>{{$data['lastname']}}</td>
        </tr>
        <tr>
            <td><b>Phone</b></td>
            <td>{{$data['phone']}}</td>
            <td><b>Total Savings</b></td>
            <td>{{$data['total_savings']}}</td>
        </tr>
    </table>
    <br>
    <hr>
    <br>
    <table class="three-column-table">
        <tr>
            <th colspan="4">
                <h4 style="text-align: center; ">Product Summary</h4>
            </th>
        </tr>
        <tr>
            <th>#</th>
            <th>Reference</th>
            <th>Name</th>
            <th>Amount</th>
        </tr>
        <tbody>
            <?php $c = 0; ?>
            @foreach($savingsproducts as $sm)
            <?php $c++; ?>
            <tr>
                <td>{{$c}}</td>
                <td>{{$sm['product_code']}}</td>
                <td>{{$sm['product_name']}}</td>
                <td>{{$sm['revenue']}}</td>
            </tr>
            @endforeach

        </tbody>

    </table>
    <br>
    <hr>
    <br>
    <table class="three-column-table">
        <thead>
            <tr>
                <th colspan="6">
                    <h4 style="text-align: center; ">Deposits</h4>
                </th>
            </tr>
            <tr>
                <th>#</th>
                <th>Reference</th>
                <th>Product</th>
                <th>Amount</th>
                <th>Total Amount</th>
                <th>Date</th>

            </tr>

        </thead>
        <tbody>
            <?php $c = 0; ?>
            @foreach($savings as $saving)
            <?php $c++; ?>
            <tr>
                <td>{{$c}}</td>
                <td>{{$saving['reference']}}</td>
                <td>{{$saving['product_name']}}</td>
                <td>{{$saving['amount']}}</td>
                <td>{{$saving['total']}}</td>
                <td>{{$saving['saved']}}</td>
            </tr>
            @endforeach

        </tbody>

    </table>
    <br>
    <hr>
    <br>

    <table class="three-column-table">
        <thead>
            <tr>
                <th colspan="6">
                    <h4 style="text-align: center; ">Withdrawals</h4>
                </th>
            </tr>
            <tr>
                <th>#</th>
                <th>Reference</th>
                <th>Product</th>
                <th>Amount</th>
                <th>Total Amount</th>
                <th>Date</th>

            </tr>

        </thead>
        <tbody>
            <?php $c = 0; ?>
            @foreach($withdrawals as $saving)
            <?php $c++; ?>
            <tr>
                <td>{{$c}}</td>
                <td>{{$saving['reference']}}</td>
                <td>{{$saving['product_name']}}</td>
                <td>{{$saving['amount']}}</td>
                <td>{{$saving['total']}}</td>
                <td>{{$saving['tolewa']}}</td>
            </tr>
            @endforeach

        </tbody>

    </table>



</body>

</html>