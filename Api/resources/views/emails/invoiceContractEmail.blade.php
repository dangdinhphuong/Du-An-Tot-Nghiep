<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .invoice-body {
            padding: 0 20% 0 20%;
            font-family: arial, sans-serif;
        }

        .invoice-left {
            float: left;
            font-weight: 600;
            font-size: 1.875rem;
            line-height: 2.25rem;
        }

        .invoice-right {
            float: right;
        }

        .invoice-right-top {
            margin: 0 0 6px 0;
            font-weight: 600;
        }

        .invoice-right-bot {
            margin-top: 6px;
            font-weight: 600;
        }

        .invoice-header-bot {
            clear: both;
            margin-top: 0;
        }

        .invoice-infor-left {
            float: left;
        }

        .invoice-infor-right {
            float: left;
            margin-left: 40%;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            width: 60px;
            font-size: 0.9rem;
        }

        td {
            text-align: center;
        }

        .invoice-confirm-left {
            float: left;
        }

        .invoice-confirm-right {
            float: left;
            margin-left: 10%;
            margin-right: 10%;
        }

        .invoice-confirm-right-2 {
            float: left;
        }

    </style>
</head>

<body>
    <div class="invoice-body">
        <!-- header -->
        <div class="invoice-header-top">
            <div class="invoice-left">
                Ký túc xá
            </div>
            <div class="invoice-right">
                <p class="invoice-right-top">Địa chỉ: Hà Nội</p>
                <p class="invoice-right-bot">Sđt: </p>
            </div>

        </div>
        <div class="invoice-header-bot ">
            <h2 style="float:left;margin:5px 50px 10px 0;">PHIẾU THU</h2>
            <h2 style="float:left;margin:5px 50px 10px 0;"></h2>
            <p style="clear:both;"><i>{{ $data['month-year'] }}</i></p>
        </div>
        <!-- infor -->
        <div>
            <div class="invoice-infor-left">
                <p>Người thuê: {{ $data['invoice_details']['student_info']->last_name }}
                    {{ $data['invoice_details']['student_info']->first_name }}<b></b></p>
                <p>Điện thoại: {{ $data['invoice_details']['student_info']->phone }}</p>
                <p>Loại thu phí: Phí phòng và các phí kèm theo</p>
                <p>Từ ngày: {{ $data['day-month-year'] }}</p>
            </div>
            <div class="invoice-infor-right">
                <p>Số phiếu: </p>
                <p>Ngày tạo: {{ $data['day-month-year'] }} </p>
                <p>Ngày thu: {{ $data['day-month-year'] }} </p>
                <p>Đến ngày: </p>
            </div>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th style="width:120px">NỘI DUNG</th>
                            <th>ĐVT</th>
                            <th>CSC</th>
                            <th>ĐƠN GIÁ</th>
                            <th style="width:80px">THÀNH TIỀN</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>0</td>
                            <td>{{ $data['invoice_details']['room_fee_info']['name'] }}</td>
                            <td>{{ $data['invoice_details']['room_fee_info']['unit'] }}</td>
                            <td></td>
                            <td>{{ $data['invoice_details']['room_fee_info']['unit_price'] }}</td>
                            <td>{{ $data['invoice_details']['room_fee_info']['total_money'] }}</td>
                        </tr>
                        @isset($data['invoice_details']['project_service_info'])
                            @foreach ($data['invoice_details']['project_service_info'] as $projectService)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $projectService->name }}</td>
                                    <td>{{ $projectService->unit }}</td>
                                    <td>{{ $projectService->index }}</td>
                                    <td>{{ $projectService->unit_price }}</td>
                                    <td>{{ $projectService->total_money }}</td>
                                </tr>
                            @endforeach
                        @endisset

                        <tr>
                            <th colspan="5" style="text-align: right;">TỔNG TIỀN</th>
                            <th style="text-align: center;">{{ $data['invoices']['total_money'] }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- xác nhận -->
            <div class="invoice-confirm">
                <div class="invoice-confirm-left">
                    <p><b>Tiền bằng chữ: {{ $data['invoices']['total_money_text'] }}</b></p>
                    <p><b>Ghi chú</b></p>
                </div>
                <div class="invoice-confirm-right">
                    <p><b>Khách đóng tiền</b></p>
                    <p><i>(Ký và ghi họ tên)</i></p>
                    <p style="margin-top: 10px;text-align: center;"><b></b></p>
                </div>
                <div class="invoice-confirm-right-2">
                    <p><b>Người lập phiếu</b></p>
                    <p><i>(Ký và ghi họ tên)</i></p>
                    <p style="margin-top: 10px;text-align: center;"><b></b></p>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>
