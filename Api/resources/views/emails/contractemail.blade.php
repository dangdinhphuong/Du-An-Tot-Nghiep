<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .contract-main {
            font-family: Times New Roman, Times, serif;
            margin: 0 auto !important;
        }

        .text-center {
            text-align: center !important;
        }

        .p {
            text-align: center !important;

        }

        .contract-text-bold {
            font-weight: 700 !important;
        }

        .flex {
            display: flex !important;
            justify-content: space-around !important;
        }

        .footer {
            display: flex !important;
            justify-content: space-around !important;
        }

        .ben-cho-thue {
            text-align: center !important;
            margin: 10px;
        }

        .ky {
            text-align: center !important
        }

        .cong-hoa {
            text-align: center !important;
        }

    </style>
</head>

<body>
    <div class="container">
        <main class="contract-main">
            <div class="cong-hoa">
                <p class="cong-hoa">CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
                <p class="cong-hoa">Độc lập - Tự do - Hạnh phúc</p>
            </div>

            <section>
                <div class="cong-hoa">
                    <p class="cong-hoa contract-text-bold">HỢP ĐỒNG
                        THUÊ</p>
                    <p class="cong-hoa">(Số: )</p>
                </div>

                <div>
                    <p>
                        Hôm nay, ngày..........tháng..........năm..........
                        Tại..................................................Chúng tôi gồm
                        có:
                    </p>
                    <p class="contract-text-bold">BÊN CHO THUÊ (BÊN A):</p>
                    <div class="contract-text-bold">a) Trường hợp là cá nhân:</div>
                    <div>
                        <p>Ông/Bà: </p>
                        <p>Năm sinh:……………………………………………..………………………………………………</p>
                        <p>Hộ khẩu:……………………………………………..………………………………………………</p>
                        <p>Địa chỉ:……………………………………………..………………………………………………</p>
                        <p>Điện thoại:……………………………………………..………………………………………………</p>
                        <p>
                            Là chủ sở hữu nhà ở: ……………………………………………..……………………………………………………………
                        </p>
                    </div>
                    <p class="contract-text-bold">BÊN THUÊ (BÊN B):</p>
                    <div>
                        <p>Ông/Bà: {{ $data['student'][0]['last_name'] . ' ' . $data['student'][0]['first_name'] }}
                        </p>
                        <p>Năm sinh: {{ $data['student'][0]['birth'] }}</p>
                        <p>
                            CMND số: {{ $data['student_info'][0]['CCCD'] }} Ngày cấp:
                            {{ $data['student_info'][0]['date_range'] }}
                            Nơi cấp: {{ $data['student_info'][0]['issued_by'] }}
                        </p>
                        <p>Hộ khẩu: ……………………………………………..……………………………………………………………</p>
                        <p>Địa chỉ: {{ $data['student'][0]['address'] }}</p>
                        <p>Điện thoại: {{ $data['student'][0]['phone'] }}</p>
                        <p>Mã số thuế: ……………………………………………..……………………………………………………………</p>
                        <p>Tài khoản số: ……………………………………………..……………………………………………………………</p>
                        <p>Mở tại ngân hàng: ……………………………………………..……………………………………………………………</p>
                    </div>

                    <i class="contract-text-bold">
                        Hai bên cùng thỏa thuận ký hợp đồng với những nội dung sau:
                    </i>
                    <p class="contract-text-bold">ĐIỀU 1: ĐỐI TƯỢNG CỦA HỢP ĐỒNG</p>
                    <div>
                        <p>Đối tượng của hợp đồng này là căn phòng: {{ $data['room']['name'] }}</p>
                        <p>1.1. Thông tin phòng:</p>
                        <p>1.1.1) Thuộc dự án: {{ $data['room']['floor']['building']['project']['name'] }} </p>
                        <p>1.1.3) Nhà: {{ $data['room']['floor']['building']['name'] }}</p>
                        <p>1.1.4) Địa chỉ: {{ $data['room']['floor']['building']['project']['address'] }}</p>
                        <p>1.1.6) Loại phòng: {{ $data['room']['room_type']['name'] }}</p>
                        <p>
                            1.1.7) Trang thiết bị chủ yếu gắn liền với phòng ở (nếu có) :
                            ……………………………………………………….
                        </p>
                    </div>

                    <p class="contract-text-bold">
                        ĐIỀU 2: GIÁ CHO THUÊ NHÀ Ở VÀ PHƯƠNG THỨC THANH TOÁN
                    </p>
                    <div>
                        <p>
                            2.1. Giá cho thuê phòng là: {{ $data['room']['room_type']['price'] }} đồng/
                            tháng (Bằng chữ: {{ $data['room']['room_type']['price_text'] }}
                            .)
                        </p>
                        <p>
                            Giá cho thuê này đã bao gồm các chi phí về quản lý, bảo trì và vận
                            hành nhà ở.
                        </p>
                        <p>
                            2.2. Các chi phí sử dụng điện, nước và các dịch vụ khác do bên B
                            thanh toán cho bên A gồm.
                        </p>
                        <table class="invoice-detail" style="border-collapse: collapse; margin-left: 42px">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #ccc">STT</th>
                                    <th style="width: 55%; border: 1px solid #ccc">Loại phí</th>
                                    <th style="width: 115px; border: 1px solid #ccc">Đơn giá</th>
                                    <th style="width: 100px; border: 1px solid #ccc">Đơn vị</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tbody>
                                @foreach ($data['project_service'] as $project_service)
                                    <tr>
                                        <td style="border: 1px solid #ccc; text-align: center">{{ $loop->index }}

                                        </td>
                                        <td style="border: 1px solid #ccc; text-align: left">
                                            {{ $project_service[0]['name'] }}
                                        </td>
                                        <td style="border: 1px solid #ccc; text-align: center">
                                            {{ $project_service[0]['unit_price'] }}
                                        </td>
                                        <td style="border: 1px solid #ccc; text-align: right">
                                            {{ $project_service[0]['unit'] }}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <p>
                            2.3. Phương thức thanh toán: bằng ............., trả vào ngày hàng
                            tháng.
                        </p>
                        <p>2.4. Bên B thanh toán trước {{ $data['room']['room_type']['price_deposit'] }} đồng</p>
                    </div>

                    <p style="font-weight:bold;">
                        ĐIỀU 3: THỜI HẠN THUÊ VÀ THỜI ĐIỂM GIAO NHẬN NHÀ Ở
                    </p>
                    <div>
                        <p>
                            3.1. Thời hạn thuê ngôi phòng nêu trên kể từ ngày {{ $data['start_day'] }} đến
                            {{ $data['end_day'] }}
                        </p>
                        <p>3.2. Thời điểm giao nhận phòng ở là ngày {{ $data['start_day'] }}</p>
                    </div>

                    <p style="font-weight:bold;">ĐIỀU 4: NGHĨA VỤ VÀ QUYỀN CỦA BÊN A</p>
                    <div>
                        <p style="font-weight:bold;">4.1. Nghĩa vụ của bên A:</p>
                        <p>
                            a) Giao nhà ở và trang thiết bị gắn liền với nhà ở (nếu có) cho
                            bên B theo đúng hợp đồng;
                        </p>

                        <p>b) Phổ biến cho bên B quy định về quản lý sử dụng nhà ở;</p>
                        <p>c) Bảo đảm cho bên B sử dụng ổn định nhà trong thời hạn thuê;</p>
                        <p>
                            d) Bảo dưỡng, sửa chữa nhà theo định kỳ hoặc theo thỏa thuận; nếu
                            bên A không bảo dưỡng, sửa chữa nhà mà gây thiệt hại cho bên B,
                            thì phải bồi thường;
                        </p>
                        <p>e) Tạo điều kiện cho bên B sử dụng thuận tiện diện tích thuê;</p>
                        <p>f) Nộp các khoản thuế về nhà và đất (nếu có);</p>
                        <p>
                            g) Hướng dẫn, đôn đốc bên B thực hiện đúng các quy định về đăng ký
                            tạm trú.
                        </p>

                        <p style="font-weight:bold;">4.2. Quyền của bên A:</p>
                        <p>
                            a) Yêu cầu bên B trả đủ tiền thuê nhà đúng kỳ hạn như đã thỏa
                            thuận;
                        </p>

                        <p>
                            b) Trường hợp chưa hết hạn hợp đồng mà bên A cải tạo nhà ở và được
                            bên B đồng ý thì bên A được quyền điều chỉnh giá cho thuê nhà ở.
                            Giá cho thuê nhà ở mới do các bên thoả thuận; trong trường hợp
                            không thoả thuận được thì bên A có quyền đơn phương chấm dứt hợp
                            đồng thuê nhà ở và phải bồi thường cho bên B theo quy định của
                            pháp luật;
                        </p>
                        <p>
                            c) Yêu cầu bên B có trách nhiệm trong việc sửa chữa phần hư hỏng,
                            bồi thường thiệt hại do lỗi của bên B gây ra;
                        </p>
                        <p>
                            d) Cải tạo, nâng cấp nhà cho thuê khi được bên B đồng ý, nhưng
                            không được gây phiền hà cho bên B sử dụng chỗ ở;
                        </p>
                        <p>
                            e) Được lấy lại nhà cho thuê khi hết hạn hợp đồng thuê, nếu hợp
                            đồng không quy định thời hạn thuê thì bên cho thuê muốn lấy lại
                            nhà phải báo cho bên thuê biết trước sáu tháng;
                        </p>
                        <p>
                            f) Đơn phương chấm dứt thực hiện hợp đồng thuê nhà nhưng phải báo
                            cho bên B biết trước ít nhất 30 ngày nếu không có thỏa thuận khác
                            và yêu cầu bồi thường thiệt hại nếu bên B có một trong các hành vi
                            sau đây:
                        </p>
                        <p>
                            - Bên cho thuê nhà ở thuộc sở hữu nhà nước, nhà ở xã hội cho thuê
                            không đúng thẩm quyền, không đúng đối tượng, không đúng điều kiện
                            theo quy định của Luật nhà ở;
                        </p>
                        <p>
                            - Không trả tiền thuê nhà liên tiếp trong ba tháng trở lên mà
                            không có lý do chính đáng;
                        </p>
                        <p>
                            - Sử dụng nhà không đúng mục đích thuê như đã thỏa thuận trong hợp
                            đồng;
                        </p>
                        <p>
                            - Bên B tự ý đục phá, cơi nới, cải tạo, phá dỡ nhà ở đang thuê;
                        </p>
                        <p>
                            - Bên B chuyển đổi, cho mượn, cho thuê lại nhà ở đang thuê mà
                            không có sự đồng ý của bên A;
                        </p>
                        <p>
                            - Thuộc trường hợp quy định tại khoản 2 Điều 129 của Luật nhà ở.
                        </p>
                        <p>
                            - Bên B làm mất trật tự, vệ sinh môi trường, ảnh hưởng nghiêm
                            trọng đến sinh hoạt của những người xung quanh đã được bên A hoặc
                            tổ trưởng tổ dân phố, trưởng thôn, làng, ấp, bản, buôn, phum, sóc
                            lập biên bản đến lần thứ ba mà vẫn không khắc phục;
                        </p>
                    </div>

                    <p style="font-weight:bold;">ĐIỀU 5: NGHĨA VỤ VÀ QUYỀN CỦA BÊN B</p>

                    <p style="font-weight:bold;">5.2. Quyền của bên B:</p>

                    <div class="font-light text-sm space-y-3">
                        <p>
                            a) Nhận nhà ở và trang thiết bị gắn liền (nếu có) theo đúng thoả
                            thuận;
                        </p>
                        <p>
                            b) Được đổi nhà đang thuê với bên thuê khác, nếu được bên A đồng ý
                            bằng văn bản;
                        </p>
                        <p>
                            c) Được cho thuê lại nhà đang thuê, nếu được bên cho thuê đồng ý
                            bằng văn bản;
                        </p>
                        <p>
                            d) Yêu cầu bên A sửa chữa nhà đang cho thuê trong trường hợp nhà
                            bị hư hỏng nặng;
                        </p>
                        <p>
                            e) Được ưu tiên ký hợp đồng thuê tiếp, nếu đã hết hạn thuê mà nhà
                            vẫn dùng để cho thuê;
                        </p>
                        <p>
                            f) Được ưu tiên mua nhà đang thuê, khi bên A thông báo về việc bán
                            ngôi nhà;
                        </p>
                        <p>
                            g) Chấp hành các quy định về giữ gìn vệ sinh môi trường và an ninh
                            trật tự trong khu vực cư trú;
                        </p>
                        <p>- Không sửa chữa nhà ở khi có hư hỏng nặng;</p>
                        <p>
                            - Tăng giá thuê nhà ở bất hợp lý hoặc tăng giá thuê mà không thông
                            báo cho bên thuê nhà ở biết trước theo thỏa thuận;
                        </p>
                        <p>- Quyền sử dụng nhà ở bị hạn chế do lợi ích của người thứ ba.</p>
                    </div>

                    <p style="font-weight:bold;">5.1. Nghĩa vụ của bên B:</p>

                    <div>
                        <p>
                            a) Sử dụng nhà đúng mục đích đã thỏa thuận, giữ gìn nhà ở và có
                            trách nhiệm trong việc sửa chữa những hư hỏng do mình gây ra;
                        </p>
                        <p>b) Trả đủ tiền thuê nhà đúng kỳ hạn đã thỏa thuận;</p>
                        <p>
                            c) Trả tiền điện, nước, điện thoại, vệ sinh và các chi phí phát
                            sinh khác trong thời gian thuê nhà;
                        </p>
                        <p>d) Trả nhà cho bên A theo đúng thỏa thuận.</p>
                        <p>e) Chấp hành đầy đủ những quy định về quản lý sử dụng nhà ở;</p>
                        <p>
                            f) Không được chuyển nhượng hợp đồng thuê nhà hoặc cho người khác
                            thuê lại trừ trường hợp được bên A đồng ý bằng văn bản;
                        </p>
                        <p>
                            g) Chấp hành các quy định về giữ gìn vệ sinh môi trường và an ninh
                            trật tự trong khu vực cư trú;
                        </p>
                        <p>
                            h) Giao lại nhà cho bên A trong các trường hợp chấm dứt hợp đồng
                            quy định tại mục h khoản 5.2 Điều 5;
                        </p>
                    </div>

                    <p style="font-weight:bold;">ĐIỀU 6: QUYỀN TIẾP TỤC THUÊ NHÀ Ở</p>

                    <div>
                        <p>
                            6.1. Trường hợp chủ sở hữu nhà ở chết mà thời hạn thuê nhà ở vẫn
                            còn thì bên B được tiếp tục thuê đến hết hạn hợp đồng. Người thừa
                            kế có trách nhiệm tiếp tục thực hiện hợp đồng thuê nhà ở đã ký kết
                            trước đó, trừ trường hợp các bên có thỏa thuận khác. Trường hợp
                            chủ sở hữu không có người thừa kế hợp pháp theo quy định của pháp
                            luật thì nhà ở đó thuộc quyền sở hữu của Nhà nước và người đang
                            thuê nhà ở được tiếp tục thuê theo quy định về quản lý, sử dụng
                            nhà ở thuộc sở hữu nhà nước.
                        </p>
                        <p>
                            6.2. Trường hợp chủ sở hữu nhà ở chuyển quyền sở hữu nhà ở đang
                            cho thuê cho người khác mà thời hạn thuê nhà ở vẫn còn thì bên B
                            được tiếp tục thuê đến hết hạn hợp đồng; chủ sở hữu nhà ở mới có
                            trách nhiệm tiếp tục thực hiện hợp đồng thuê nhà ở đã ký kết trước
                            đó, trừ trường hợp các bên có thỏa thuận khác.
                        </p>
                        <p>
                            6.3. Khi bên B chết mà thời hạn thuê nhà ở vẫn còn thì người đang
                            cùng sinh sống với bên B được tiếp tục thuê đến hết hạn hợp đồng
                            thuê nhà ở, trừ trường hợp thuê nhà ở công vụ hoặc các bên có thỏa
                            thuận khác hoặc pháp luật có quy định khác.
                        </p>
                    </div>

                    <p style="font-weight:bold;">ĐIỀU 7: TRÁCH NHIỆM DO VI PHẠM HỢP ĐỒNG</p>

                    <div>
                        <p>
                            Trong quá trình thực hiện hợp đồng mà phát sinh tranh chấp, các
                            bên cùng nhau thương lượng giải quyết; trong trường hợp không tự
                            giải quyết được, cần phải thực hiện bằng cách hòa giải; nếu hòa
                            giải không thành thì đưa ra Tòa án có thẩm quyền theo quy định của
                            pháp luật.
                        </p>
                    </div>

                    <p style="font-weight:bold;">ĐIỀU 8: CÁC THỎA THUẬN KHÁC</p>

                    <div>
                        <p>
                            8.1. Việc sửa đổi, bổ sung hoặc hủy bỏ hợp đồng này phải lập thành
                            văn bản và phải được công chứng hoặc chứng thực mới có giá trị để
                            thực hiện.
                        </p>
                        <p>
                            8.2. Trường hợp thuê nhà ở thuộc sở hữu nhà nước thì việc chấm dứt
                            hợp đồng thuê nhà được thực hiện khi có một trong các trường hợp
                            quy định tại khoản 1 Điều 84 của Luật nhà ở.
                        </p>
                        <p>
                            Trường hợp thuê nhà ở không thuộc sở hữu nhà nước thì việc chấm
                            dứt hợp đồng thuê nhà ở được thực hiện khi có một trong các trường
                            hợp sau đây:
                        </p>
                        <p>
                            a) Hợp đồng thuê nhà ở hết hạn; trường hợp trong hợp đồng không
                            xác định thời hạn thì hợp đồng chấm dứt sau 90 ngày, kể từ ngày
                            bên A thông báo cho bên B biết việc chấm dứt hợp đồng;
                        </p>
                        <p>b) Nhà ở cho thuê không còn;</p>
                        <p>
                            c) Nhà ở cho thuê bị hư hỏng nặng, có nguy cơ sập đổ hoặc thuộc
                            khu vực đã có quyết định thu hồi đất, giải tỏa nhà ở hoặc có quyết
                            định phá dỡ của cơ quan nhà nước có thẩm quyền; nhà ở cho thuê
                            thuộc diện bị Nhà nước trưng mua, trưng dụng để sử dụng vào các
                            mục đích khác.
                        </p>
                        <p>
                            Bên A phải thông báo bằng văn bản cho bên B biết trước 30 ngày về
                            việc chấm dứt hợp đồng thuê nhà ở quy định tại điểm này, trừ
                            trường hợp các bên có thỏa thuận khác;
                        </p>
                        <p>d) Hai bên thoả thuận chấm dứt hợp đồng trước thời hạn.</p>
                        <p>
                            e) Bên B chết hoặc có tuyên bố mất tích của Tòa án mà khi chết,
                            mất tích không có ai đang cùng chung sống;
                        </p>
                        <p>
                            f) Chấm dứt khi một trong các bên đơn phương chấm dứt thực hiện
                            hợp đồng thuê nhà ở.
                        </p>
                    </div>

                    <p style="font-weight:bold;">ĐIỀU 9: CAM KẾT CỦA CÁC BÊN</p>

                    <div>
                        <p>
                            Bên A và bên B chịu trách nhiệm trước pháp luật về những lời cùng
                            cam kết sau đây:
                        </p>
                        <p>
                            9.1. Đã khai đúng sự thật và tự chịu trách nhiệm về tính chính xác
                            của những thông tin về nhân thân đã ghi trong hợp đồng này.
                        </p>
                        <p>
                            9.2. Thực hiện đúng và đầy đủ tất cả những thỏa thuận đã ghi trong
                            hợp đồng này; nếu bên nào vi phạm mà gây thiệt hại, thì phải bồi
                            thường cho bên kia hoặc cho người thứ ba (nếu có).
                        </p>
                        <p>
                            Trong quá trình thực hiện nếu phát hiện thấy những vấn đề cần thoả
                            thuận thì hai bên có thể lập thêm phụ lục hợp đồng. Nội dung Hợp
                            đồng phụ có giá trị pháp lý như hợp đồng chính.
                        </p>
                        <p>
                            9.3. Hợp đồng này có giá trị kể từ ngày hai bên ký kết (trường hợp
                            là cá nhân cho thuê nhà ở từ 06 tháng trở lên thì Hợp đồng có hiệu
                            lực kể từ ngày Hợp đồng được công chứng hoặc chứng thực)
                        </p>
                    </div>

                    <p style="font-weight:bold;">ĐIỀU 10: ĐIỀU KHOẢN CUỐI CÙNG</p>

                    <div>
                        <p>
                            10.1. Hai bên đã hiểu rõ quyền, nghĩa vụ và lợi ích hợp pháp của
                            mình, ý nghĩa và hậu quả pháp lý của việc công chứng (chứng thực)
                            này, sau khi đã được nghe lời giải thích của người có thẩm quyền
                            công chứng hoặc chứng thực dưới đây.
                        </p>
                        <p>
                            10.2. Hai bên đã tự đọc lại hợp đồng này, đã hiểu và đồng ý tất cả
                            các điều khoản ghi trong hợp đồng này.
                        </p>
                        <p>
                            Hợp đồng được lập thành ………. (………..) bản, mỗi bên giữ một bản và
                            có giá trị như nhau.
                        </p>
                    </div>
                    <div class="footer">
                        <div class="ben-cho-thue">
                            BÊN CHO THUÊ
                            <div>
                                <i>(Ký, ghi rõ họ tên)</i>
                            </div>
                        </div>
                        <div class="ben-cho-thue">
                            BÊN THUÊ
                            <div>
                                <i>(Ký, ghi rõ họ tên)</i>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
