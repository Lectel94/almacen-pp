<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Invoice {{ $invoice->number }}</title>


    <style>
        @page {
            margin: 80px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .logo {
            width: 150px;
        }

        .company-info {
            text-align: right;
            font-size: 10px;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }

        .section {
            width: 45%;
            font-size: 10px;
        }

        .section h3 {
            margin-bottom: 5px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        table,
        th,
        td {}

        th {
            background-color: #f0f0f0;
            font-weight: normal;
        }

        th,
        td {
            padding: 2px 4px;
            font-size: 10px;
            text-align: justify;
        }

        .totals {
            margin-top: 10px;
            width: 100%;
            border: none;
        }

        .totals td {
            border: none;
            padding: 4px 8px;
            font-weight: bold;
        }

        p {
            margin-bottom: 0px !important;
        }
    </style>


</head>

<body>
    <!-- Encabezado principal con logo y título -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 60%;">
                <img src="{{ public_path('/img/logo2.jpg') }}" width="90" />
            </td>
            <td style="width: 40%;text-align: justify;color:rgb(82, 177, 232);">
                <h1 style="color: #0490bd;text-align: justify;">Invoice</h1>
            </td>
        </tr>
    </table>

    <!-- Datos de empresa y del invoice -->
    <table class="info" cellpadding="0" cellspacing="0">
        <tr>
            <td class="client-info" style="width: 60%;">
                <p>Latin Flavors Distribution LLC</p>
                <p>15600 Masha St</p>
                <p>Pflugerville, TX 78728</p>
                <p>Office Phone: 8328979411</p>
                <p>cubangroceries@gmail.com</p>
            </td>
            <td class="client-info client-info-right" style="width: 40%;">
                <p><strong>Invoice Number:</strong> {{ $invoice->order->number }}</p>
                <p><strong>Invoice Date:</strong> {{ $invoice->issued_date }}</p>
                <p><strong>Payment Terms:</strong> Due On Receipt</p>
                <p><strong>Invoice Due Date:</strong> {{ $invoice->issued_date }}</p>
                <p><strong>Invoice Amount:</strong> {{ $invoice->total_amount }}</p>
                <p><strong>Created By:</strong> Jose Alejandro Madrigal Monzon</p>
            </td>
        </tr>
    </table>

    <!-- Información del cliente y envío -->
    <table class="info" cellpadding="10" cellspacing="0" style="margin-top:5px;">
        <tr>
            <td class="client-info" style="width: 60%;">
                <h3>Bill To:</h3>
                {{ $invoice->order->company_name ? $invoice->order->company_name : $invoice->order->first_name .' '.
                $invoice->order->last_name }}
            </td>
            <td class="client-info client-info-right" style="width: 40%;">
                <h3>Ship To:</h3>
                {{ $invoice->order->company_name ? $invoice->order->company_name : $invoice->order->first_name .' '.
                $invoice->order->last_name }}
            </td>
        </tr>
    </table>


    <!-- Tabla de detalles -->
    <table border="1">
        <thead>
            <tr>
                <th>Product </th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Taxable</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->order->orderDetails as $detail)
            <tr>
                <td>{{ $detail->product->sku ?? 'N/A' }}</td>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ number_format($detail->unit_price, 2) }}</td>
                <td></td>
                <td>{{ number_format($detail->quantity * $detail->unit_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total -->
    <div style="float: right;">
        <table class="totals" style="margin-top:10px;">
            <tr>
                <td>Subtotal:</td>
                <td>{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            <!-- Puedes agregar impuestos si los tienes -->
            <tr>
                <td>Total:</td>
                <td>{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>



</body>

</html>