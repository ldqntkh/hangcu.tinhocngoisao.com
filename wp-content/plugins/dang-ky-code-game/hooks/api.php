<?php
add_action( 'wp_ajax_dangkycodegame', 'dangkycodegame');
add_action( 'wp_ajax_nopriv_dangkycodegame', 'dangkycodegame');


function dangkycodegame ($request) {
    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $phone_number = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $company_name = isset($_POST['company']) ? trim($_POST['company']) : '';
    // $has_thebaohanh = isset($_POST['has_thebaohanh']) ? trim($_POST['has_thebaohanh']) : 0;
    $nganh_hang = isset($_POST['product_type']) ? trim($_POST['product_type']) : '';

    if( $fullname == '' || $phone_number == '' || $email == '' || $description == '' || $address == '' || $nganh_hang == '' ) {
        wp_send_json_error([
            "success" => false,
            "data"  => null,
            "message"   => __("Tham số không phù hợp", "ycbh")
        ]);
    }

    // insert vào db
    // check đã có request hay chưa
    global $table_prefix, $wpdb, $wnm_db_version;
    $table_dang_ky_code_game = $table_prefix . 'dang_ky_code_game';
    $check = $wpdb->get_results(" SELECT * FROM $table_dang_ky_code_game WHERE
                                    (`phone_number` = '$phone_number' OR `email` = '$email') 
                                    AND DATE(NOW()) = DATE(`created_at`) 
                                    AND `status` = '0' ");
    if( $check && count( $check ) > 3 ) {
        wp_send_json_error([
            "success" => false,
            "data"  => null,
            "message"   => __("Bạn đã gửi nhiều hơn 1 yêu cầu trong hôm nay. Vui lòng đợi kết quả từ chúng tôi.", "ycbh")
        ]);
    }

    // insert file
    require_once( ABSPATH . 'wp-admin/includes/admin.php' );
    require_once(ABSPATH.'wp-admin/includes/file.php');
    // upload file
    function wpse_183245_upload_dir( $dirs ) {
        $dirs['subdir'] = '/dkcodegame';
        $dirs['path'] = $dirs['basedir'] . '/dkcodegame';
        $dirs['url'] = $dirs['baseurl'] . '/dkcodegame';
    
        return $dirs;
    }

    $file = $_FILES['file'];
    $rsFiles = [];
    add_filter( 'upload_dir', 'wpse_183245_upload_dir' );
    $file_array = [
        "error" =>   0,
        "name"  =>   $file['name'],
        "size"  =>   $file['size'],
        "tmp_name"  =>   $file['tmp_name'],
        "type"  =>   $file['type'],
    ];
    
    $file_return = wp_handle_upload( $file_array , array('test_form' => false ) );
    $rsFiles[] = $file_return;

    remove_filter( 'upload_dir', 'wpse_183245_upload_dir' );
    
    // insert vào db
    $insert = $wpdb->insert( $table_dang_ky_code_game, [
        "phone_number" => $phone_number,
        "email" => $email,
        "fullname"  => $fullname,
        "address"  => $address,
        "company_name"  => $company_name,
        // "has_thebaohanh"  => $has_thebaohanh,
        // "nganh_hang" => $nganh_hang,
        "description"   => $description,
        "file_data" => json_encode($rsFiles),
        "status" => 0
    ] );

    if( $insert ) {
        // thông báo đến admin
        try {
            $message = '<p><strong>' . __("Có khách hàng mới yêu cầu bảo hành", "ycbh") . '</strong></p>
            <p>Họ tên khách hàng: <strong>' . $fullname . '</strong></p>
            <p>Chi tiết tại: <strong><a href="' . admin_url('admin.php?page=danh-sach-bao-gia-san-pham&type=edit&cpp_id='. $wpdb->insert_id) . '">Đây</a></strong></p>';
            $dkcodegame_emails = get_option('dkcodegame_emails') ? get_option('dkcodegame_emails') : "";
            $headers = array('Content-Type: text/html; charset=UTF-8');
            if( $dkcodegame_emails ) {
                
                $_emails = explode(',', $dkcodegame_emails);
                for( $i = 0; $i < count($_emails ); $i++) {
                    $isMailSent = wp_mail( $_emails[$i], __('Thông tin yêu cầu bảo hành', 'ycbh'), $message, $headers );
                }
            }
        } catch (Exception $e) {
            wp_send_json_success([
                "success"   => true,
                "data"  => $e->get_message(),
                "message"   => __("Yêu cầu của bạn đã được tiếp nhận. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất", "ycbh")
            ]);
        }
        
        wp_send_json_success([
            "success"   => true,
            "data"  => null,
            "message"   => __("Yêu cầu của bạn đã được tiếp nhận. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất", "ycbh")
        ]);
    } else {
        wp_send_json_error([
            "success" => false,
            "data"  => $wpdb->last_error,
            "message"   => __("Đã có lỗi xảy ra. Vui lòng thử lại.", "ycbh")
        ]);
    }

}