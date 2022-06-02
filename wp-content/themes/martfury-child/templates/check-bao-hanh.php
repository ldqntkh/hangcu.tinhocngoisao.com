<?php
/*
    Template Name: Kiểm tra bảo hành
*/

get_header();
$msg = "";
$response = null;

function formatDateHTSoft( $strDate ) {
    $date = str_replace( '/Date(', '', $strDate );
    $date = intval( str_replace( '+0700)/', '', $date ) ) / 1000;
    $date = date("d/m/Y h:i:s A", strtotime('+7 hours', date($date)));
    return $date;
}

if ( !empty( $_POST['soBN'] ) || !empty( $_POST['soDT'] ) ) {
    
    // $api_url = "http://baohanhapi.tinhocngoisao.com:8080/TTBaoHanh.ashx?textSearch=";
    $api_url = "http://ngoisaolon.htsoft.vn:9044/ActionService.svc/LoadListWarrantyHistoryByCallerID";

    $flag = false;
    if ( !empty( $_POST['soDT'] ) ) {
        if ( !empty(preg_match('/(09|03|07|08|05)+([0-9]{8}$)/', trim($_POST['soDT']) )) ) {
            $flag = true;
        } else {
            $msg = "Số điện thoại không hợp lệ";
        }
    } 
    if ( $flag ) {
    
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_PORT => "9044",
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n\t\"callerid\":\"" . $_POST['soDT'] . "\"\n}",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "clienttag: FFA7BB0E-82F9-4168-B46D-1AD3B526E00D",
                "content-type: application/json"
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            $response = json_decode( $response, true );
            if ( $response['responseCode'] == '00' &&  isset( $response['dataDetail'] ))
                $response = $response['dataDetail'];
            else $response = [];

            if ( count( $response ) == 0 ) {
                $msg = "Rất tiếc chúng tôi không tìm thấy thông tin bạn đã cung cấp!";
            }
        } catch ( Exception $e) {
            // var_dump( $e );die;
        }

    } else {
         $msg = "Rất tiếc bạn chưa cung cấp thông tin cho chúng tôi!";
    }
}

?>

<div class="check-warranty">
    <div class="content-noti">
        <h3>Lưu ý:</h3>
        <ol>
            <li>Bạn có thể tra cứu bảo hành trong vòng 6 tháng gần nhất. </li>
            <li>Khi tình trạng bảo hành là “đã trả lại khách”, cột “ngày trả” ghi nhận thời gian tinhocngoisao.com gởi hàng qua nhà chuyển phát đến tận nơi cho quý khách , thông thường trong vòng 3-6 ngày sẽ đến ( trừ trường hợp bất khả kháng thiên tai, dịch bệnh…) </li>
            <li>Thông thường , thời gian xử lý 1 trường hợp bảo hành từ 10-24 ngày . Tuy nhiên, một vài trường hợp hãng hoàn tiền , hoặc hỗ trợ bảo hành , có thể lâu hơn dự kiến , kéo dài từ 30-45 ngày. </li>
            <li>Quý khách vui lòng ghi chi tiết địa chỉ để khi bảo hành xong, tinhocngoisao.com nhanh chóng gởi trả hàng bảo hành xong về tận địa chỉ quý khách .</li>
        </ol>
    </div>
    <div class="form-search">
        <?php 
            if ( strlen( $msg ) > 0 ) {
                echo '<p class="error">'.$msg.'</p>';
            }
        ?>
        
        <form method="post" style="padding: 10px">
            <div class="input-form">
                <label for="soDT">Số điện thoại:</label>
                <input type="text" name="soDT" id="soDT" value="<?php if ( !empty( $_POST['soDT'] ) ) echo $_POST['soDT'] ; ?>" />
            </div>
            <div class="submit-form" style="margin-bottom: 10px">
                <button type="submit">Kiểm tra bảo hành</button>
            </div>
        </form>
    </div>
    
    <?php 
        if ( $response != null && count( $response ) > 0 ) : 
            $html_mobile = '';
        ?>
            <div class="warranty-result-desktop">
                <table style="width:100%">
                    <colgroup>
                        <col span="1" style="width: 10%;">
                        <col span="1" style="width: 25%;">
                        <col span="1" style="width: 20%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 10%;">
                        <col span="1" style="width: 10%;">
                        <col span="1" style="width: 10%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Số biên nhận</th>
                            <th>Tên sản phẩm</th>
                            <th>Mã sản phẩm</th>
                            <th>Lỗi</th>
                            <th>Ngày nhận</th>
                            <th>Tình trạng</th>
                            <th>Ngày hẹn trả dự kiến</th>
                            <!-- <th>Ngày trả</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $response as $item ) : 
                        
                            // $ngayNhan = formatDateHTSoft( $item['CreatedDate'] );
                            // $ngayHenTra = formatDateHTSoft( $item['RETURNEDDATEFROMNPP'] );
                            // var_dump( $ngayHenTra );
                            // lấy month/year
                            $dates = explode( '/', $item['CreatedDate'] );
                            if( intval($dates[2]) < 2021 ) {
                                continue;
                            } else {
                                if( $dates[1] < 6 ) {
                                    continue;
                                }
                            }

                            $tinhtrang = 'Đang kiểm tra';

                            if ( $item['Returned'] === true ) {
                                $tinhtrang = 'Đã trả khách';
                            } else if ( $item['REPAIRED'] === true || $item['RETURNEDFROMNPP'] === true ) {
                                $tinhtrang = 'Đã sửa xong';
                            }

                            // $ngayTra = '';
                            // if ( strpos( $item['TIMERETURN'], "01/01/0001" ) === false ) {
                            //     $ngayTra = $item['TIMERETURN'];
                            //     $ngayTra = str_replace( 'CH', 'PM', $ngayTra );
                            //     $ngayTra = str_replace( 'SA', 'AM', $ngayTra );
                            // }
                            $html_mobile .= '<div class="item">
                                                <p><strong>Số biên nhận: </strong><span>'.$item['DocCode'].'</span></p>
                                                <p><strong>Tên sản phẩm: </strong><span>'.$item['ItemName'].'</span></p>
                                                <p><strong>Mã sản phẩm: </strong><span>'.$item['ItemCode'].'</span></p>
                                                <p><strong>Lỗi: </strong><span>'.$item['ErrorNote'].'</span></p>
                                                <p><strong>Ngày nhận: </strong><span>'.$item['CreatedDate'].'</span></p>
                                                <p><strong>Tình trạng: </strong><span>'.$tinhtrang.'</span></p>
                                                <p><strong>Ngày hẹn trả dự kiến: </strong>'.$item['ReturnDate'].'</span></p>
                                            </div>';
                        ?>
                            <tr>
                                <td><?php echo $item['DocCode'] ?></td>
                                <td><?php echo $item['ItemName'] ?></td>
                                <td><?php echo $item['ItemCode'] ?></td>
                                <td><?php echo $item['ErrorNote'] ?></td>
                                <td><?php echo $item['CreatedDate'] ?></td>
                                <td><?php echo $tinhtrang ?></td>
                                <td><?php echo $item['ReturnDate'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="warranty-result-mobile">
                <?php echo $html_mobile; ?>
            </div>
        <?php endif;
    ?>
</div>

<?php



get_footer();