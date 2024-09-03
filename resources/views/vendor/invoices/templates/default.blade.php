<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .container {
            width: 95%;
            margin: 0 auto;
        }

        .header,
        .footer {
            display: flex;
            justify-content: end;
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 150px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 28px;
            margin: 0;
            color: #2d2d2d;
        }

        .header h2 {
            font-size: 22px;
            margin: 5px 0 20px 0;
            color: #6B7280;
        }

        .invoice-info {
            width: 100%;
            text-align: left;
            display: flex;
            justify-content: space-between;
        }

        .box {
            margin: 30px 0;
        }

        .box h4 {
            color: #353535;
            font-size: 14px;
        }

        .box p {
            color: #6b7280;
        }

        .box span {
            color: #2d2f35;
        }

        .total-box {
            margin: 20px 0;
            font-size: 20px;
        }

        .total-box p span {
            font-size: 40px;
            color: #0f0f12;
        }

        .total-box p {
            color: #6b7280;
        }

        .details {
            width: 250px;
            margin: 30px 0;
            text-align: start;
        }

        .invoice-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            direction: ltr;
        }

        .invoice-details tr {
            border-bottom: 1px solid #aeaeae;
        }

        .invoice-details th,
        .invoice-details td {
            padding: 8px;
            text-align: start;
            font-size: 14px;
            color: #3c4048;
        }

        .invoice-details th span,
        .invoice-details td span {
            color: #7a7878;
        }

        .total-section {
            margin-top: 20px;
            text-align: right;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
        }

        .total-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .total-section th,
        .total-section td {
            padding: 8px;
            text-align: right;
        }

        .total-section th {
            font-weight: bold;
        }

        .total-section td:last-child {
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }

        .qr-code {
            display: flex;
            flex-direction: column;
            align-items: end;
            text-align: end;
            font-size: 12px;
            margin-top: 20px;
        }

        .qr-code img {
            width: 100px;
            height: 100px;
        }

    </style>
</head>

<body>
<div class="container">

    <div class="header">
        <div>
            <h1>Tax Invoice</h1>
            <h2 style="font-weight: lighter;">فــاتــورة ضــريبــية</h2>
        </div>
    </div>

    <!-- Invoice Information -->
    <div class="invoice-info">
        <div style="margin-top: -40px; display: flex; flex-direction: column;">
            <img src="{{asset("images/cougarsInvoice.png")}}" style="width: 75%;" alt="Cougars Health Club Logo">
            <div class="details">
                <div class="box">
                    <h4>Cougars Health Club</h4>
                    <p style="color: #4c525e;">Abdul Maqsud Khojah,, Al Rawdah<br> Jeddah 23435<br> Kingdom of Saudi
                        Arabia</p>
                    <p style="color: #4c525e; direction: ltr;text-align: end;">+966(12)263-0263</p>
                    <p style="color: #4c525e;">VAT number</p>
                    <p>رقم التسجيل الضريبي</p>
                    <span>300246671500003</span>
                </div>
            </div>
        </div>
        <div>
            <div class="box">
                <h4>Invoice number</h4>
                <p>رقم الفاتورة</p>
                <span>INV-{{ $invoice['invoice']->id }}</span>
            </div>
            <div class="box">
                <h4>Date</h4>
                <p>ألتاريخ</p>
                <span>{{ date('Y-m-d') }}</span>
            </div>
            <div class="box">
                <h4>Issue Date</h4>
                <p>تاريخ الإصدار</p>
                <span>{{ date('Y-m-d', strtotime($invoice['invoice']->created_at)) }}</span>
            </div>
        </div>
        <div>
            <div class="box">
                <h4>Bill to</h4>
                <p>العميل</p>
                <span>{{ $invoice['buyer']->name }}</span>
            </div>
            <div class="box">
                <h4>Kingdom of Saudi Arabia</h4>
                <span>VAT number</span>
                <p>رقم التسجيل الضريبي</p>
            </div>
        </div>
    </div>

    <div class="total-box">
        <h5>Total Due</h5>
        <p>الرصيد المستحق</p>
        <p style="color: #2f2f2f;"><span>{{ $invoice['invoice']->net_amount - $invoice['invoice']->payments->sum('amount') }}</span> ر.س.SAR</p>
    </div>

    <!-- Invoice Details Table -->
    <table class="invoice-details">
        <thead>
        <tr>
            <th>Item / Description <br><span>الوصف  المنتج</span></th>
            <th>Quantity <br><span>الكمية</span></th>
            <th>Price <br><span>السعر</span></th>
            <th>Taxable amount <br><span>المبلغ الخاضع للضريبة</span></th>
            <th>VAT <br><span>القيمة المضافة</span></th>
            <th>Amount <br><span>المجموع</span></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($invoice['items'] as $item)
{{--            @dd($item)--}}
            <tr>
                <td>{{$item['title']}}</td>
                <td>1</td>
                <td>{{$item['pricePerUnit']}}</td>
                <td>--</td>
                <td>{{$item['pricePerUnit'] * 0.15}} <br><span style="font-size:12px">15%</span></td>
                <td>{{$item['pricePerUnit'] +($item['pricePerUnit']*0.15)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Total Section -->
    <div class="total-section">
        <div class="right">
            <table dir="ltr">
                <tr>
                    <td>Subtotal <br> <span>المجموع الفرعي</span></td>
                    <td>SAR .ر.س</td>
                    <td>{{ $invoice['invoice']->net_amount}}</td>
                </tr>
                <tr>
                    <td>Total VAT <br> <span>إجمالي ضريبة القيمة المضافة</span></td>
                    <td>SAR .ر.س</td>
                    <td>{{ $invoice['invoice']->net_amount * 0.15 }}</td>
                </tr>
                <tr>
                    <td>Total <br> <span>الإجمالي</span></td>
                    <td>SAR .ر.س</td>
                    <td>{{ $invoice['invoice']->net_amount + ($invoice['invoice']->net_amount * 0.15 ) }}</td>
                </tr>

                <tr>
                    <td>Paid amount <br> <span>المبلغ المدفوع</span></td>
                    <td>SAR .ر.س</td>
                    <td>{{ $invoice['invoice']->payments->sum('amount') }}</td>
                </tr>
            </table>
        </div>
        <div class="qr-code">
            <img src="{{asset('images/QRcode.png')}}" alt="QR Code">
            <p>This QR code is encoded as per ZATCA e-invoicing requirements<br>
                رمز الاستجابة السريعة مشفر حسب متطلبات هيئة الزكاة والضريبة والجمارك للفوترة الإلكترونية</p>
        </div>
    </div>
</div>
</body>

</html>
