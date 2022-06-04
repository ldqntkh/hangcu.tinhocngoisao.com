<?php 
// local login
if( !function_exists('customer_login_account') ) {
    function customer_login_account(  ) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $msg = '';
        
        if( empty( $username ) ) {
            $msg = __('Thông tin tài khoản không chính xác', 'hangcu');
        } else if( empty( $password ) ) {
            $msg = __('Thông tin mật khẩu không chính xác', 'hangcu');
        }

        if( $msg != '' ) {
            wp_send_json_error([
                "errMsg" => $msg
            ]);
            die;
        }
        // login account
        $login = wp_signon([
            "user_login" => $username,
            "user_password" => $password,
            "remember" => true
        ]);
        if (is_wp_error($login)) {
            wp_send_json_error([
                "success" => false,
                "errMsg"   => "Tài khoản hoặc mật khẩu không chính xác"
            ]);
            die;
        } else {
            // check phone number
            if (get_field( 'customer_mobile_phone', 'user_'.$login->ID ) ) { 
                wp_send_json_success([
                    "success" => true,
                    "user" => $login
                ]);
            } else {
                wp_send_json_success([
                    "success" => true,
                    "user" => false
                ]);
            }
            die;
        }
    }
}

// social login
if( !function_exists('customer_login_account_byid') ) {
    function customer_login_account_byid() {
        $user_id = $_POST['user_id'];
        if( empty( $user_id ) ) {
            $msg = __('Thông tin tài khoản không chính xác', 'hangcu');
        }
        if( $msg != '' ) {
            wp_send_json_error([
                "errMsg" => $msg
            ]);
            die;
        }
        
        // login account
        $user_login = get_user_by( 'id', $user_id );
        if( !$user_login ) {
            wp_send_json_error([
                "errMsg" => __('Thông tin tài khoản không chính xác', 'hangcu')
            ]);
            die;
        }
        wp_set_current_user($user_id, $user_login->user_login);
        wp_set_auth_cookie($user_id);
        do_action( 'wp_login', $user_login->user_login, $user_login );
        // check phone number
        
        if ( get_field( 'customer_mobile_phone', 'user_'.$user_id ) ) { 
            global $current_user;
            wp_send_json_success([
                "success" => true,
                "user" => $current_user
            ]);
        } else {
            wp_send_json_success([
                "success" => true,
                "user" => false
            ]);
        }
        die;
    }
}

// social register
if( !function_exists('customer_register_social_account') ) {
    function customer_register_social_account() {
        $user_id = $_POST['user_id'];
        $user_name = $_POST['user_name'];
        $user_email = $_POST['user_email'];
        $providerID = $_POST['providerID'];
        $socialID = $_POST['user_id'];
        if( empty( $user_id ) || empty( $user_name ) || empty( $providerID ) ) {
            $msg = __('Thông tin không đầy đủ', 'hangcu');
        }
        if( $msg != '' ) {
            wp_send_json_error([
                "errMsg" => $msg
            ]);
            die;
        }
        
        if( empty($user_email) || $user_email == 'undefined' ) {
            $user_email = $user_id . '_' . $providerID . '@social.hangcu';
        }

        $password = uniqid($user_id);
        $create_user_id = wp_insert_user([
            "user_login" => $user_email,
            "user_nicename" => $user_email,
            "display_name" => $user_name,
            "first_name" => $user_name,
            "last_name" => "",
            "user_email" => $user_email,
            "user_pass" => $password
        ]);
        
        if (is_wp_error($create_user_id)) {
            // check current user email
            $user = get_user_by('email', $user_email);
            if( !$user ) {
                $user = get_user_by('login',$user_email);
            }
            if( $user ) {
                $social_id = false;
                if ( class_exists('NextendSocialLogin') && NextendSocialLogin::isProviderEnabled($providerID)) {
                    $provider = NextendSocialLogin::$enabledProviders[$providerID];
                    $social_id = $provider->linkUserToProviderIdentifier($create_user_id , $socialID, true);
                }
                wp_set_current_user($user->id, $user->user_login);
                wp_set_auth_cookie($user->id);
                do_action( 'wp_login', $user->user_login, $user );

                // check phone number
                
                if ( get_field( 'customer_mobile_phone', 'user_'.$user->id ) ) { 
                    global $current_user;
                    wp_send_json_success([
                        "success" => true,
                        "user" => $current_user
                    ]);
                } else {
                    wp_send_json_success([
                        "success" => true,
                        "user" => false
                    ]);
                }
            } else {
                wp_send_json_error([
                    "success" => false,
                    "errMsg"   => "Thông tin liên quan về tài khoản này đã được đăng ký"
                ]);
            }
            die;
        } else {
            // update_field( 'customer_mobile_phone', $data_user['phonenumber'], 'user_'.$user_id );
            // link user
            $social_id = false;
            if ( class_exists('NextendSocialLogin') && NextendSocialLogin::isProviderEnabled($providerID)) {
                $provider = NextendSocialLogin::$enabledProviders[$providerID];
                $social_id = $provider->linkUserToProviderIdentifier($create_user_id , $socialID, true);
            }
            // wp_logout();
            $login = wp_signon([
                "user_login" => $user_email,
                "user_password" => $password,
                "remember" => true
            ]);
            // check phone number
            $user = wp_get_current_user();
            if ( get_field( 'customer_mobile_phone', 'user_'.$user->ID ) ) { 
                wp_send_json_success([
                    "success" => true,
                    "user" => $user
                ]);
            } else {
                wp_send_json_success([
                    "success" => true,
                    "user" => false
                ]);
            }
            die;
        }

    }
}

// local register
if( !function_exists('customer_register_account') ) {
    function customer_register_account(  ) {
        $data_user = $_POST['data_user'];
        $msg = '';
        if( empty( $data_user['fullname'] ) ) {
            $msg = __('Tên hiển thị không được để trống', 'hangcu');
        } else if( empty( $data_user['phonenumber'] ) ) {
            $msg = __('Số điện thoại không được để trống', 'hangcu');
        } else if( empty( $data_user['verify_phone'] ) ) {
            $msg = __('Vui lòng nhập mã OTP', 'hangcu');
        } else if( empty( $data_user['email'] ) ) {
            $msg = __('Email không được để trống', 'hangcu');
        } else if( empty( $data_user['password'] ) ) {
            $msg = __('Mật khẩu không được để trống', 'hangcu');
        }

        if( $msg != '' ) {
            wp_send_json_error([
                "errMsg" => $msg
            ]);
            die;
        }

        if (validPhoneNumberWithOtpCode(trim($data_user['phonenumber']), trim($data_user['verify_phone']))) {
            $params = array(
                'meta_key'     => 'customer_mobile_phone',
                'meta_value'   => trim($data_user['phonenumber'])
            );
    
            $user = get_users($params);
            if ($user && !empty($user)) {
                wp_send_json_error([
                    "errMsg" => 'Số điện thoại đã tồn tại'
                ]);
                die;
            }
            $user = get_user_by( 'email', trim($data_user['email']) );
            if ($user && !empty($user)) {
                wp_send_json_error([
                    "errMsg" => 'Email đã tồn tại'
                ]);
                die;
            }

            $user_id = wp_insert_user([
                "user_login" => trim($data_user['phonenumber']),
                "user_nicename" => trim($data_user['fullname']),
                "display_name" => trim($data_user['fullname']),
                "user_email" => trim($data_user['email']),
                "user_pass" => trim($data_user['password'])
            ]);
            
            if (is_wp_error($user_id)) {
                wp_send_json_error([
                    "success" => false,
                    "errMsg"   => "Có lỗi khi tạo tài khoản. Vui lòng thử lại"
                ]);
                die;
            } else {
                update_field( 'customer_mobile_phone', $data_user['phonenumber'], 'user_'.$user_id );
                $login = wp_signon([
                    "user_login" => $data_user['phonenumber'],
                    "user_password" => $data_user['password'],
                    "remember" => true
                ]);
                
                wp_send_json_success([
                    "success" => true,
                    "user" => $login
                ]);
                die;
                die;
            }
        } else {
            $errors = __('Mã xác thực không hợp lệ', 'hangcu');
            wp_send_json_error([
                "errMsg" => $errors
            ]);
            die;
        }

        // login account
        // $login = wp_signon([
        //     "user_login" => $data_user['username'],
        //     "user_password" => $data_user['password'],
        //     "remember" => true
        // ]);
        // if (is_wp_error($login)) {
        //     wp_send_json_error([
        //         "success" => false,
        //         "errMsg"   => "Tài khoản hoặc mật khẩu không chính xác"
        //     ]);
        //     die;
        // } else {
        //     wp_send_json_success([]);
        //     die;
        // }
    }
}

// forgot pass
if( !function_exists( 'customer_forgot_password' ) ) {
    function customer_forgot_password() {
        $login = $_POST['username'];
        if ( empty( $login ) ) {
            wp_send_json_error([
                "success" => false,
                "errMsg"   => "Vui lòng cung cấp tên đăng nhập"
            ]);
            die;
		} else {
			// Check on username first, as customers can use emails as usernames.
			$user_data = get_user_by( 'login', $login );
		}

		// If no user found, check if it login is email and lookup user based on email.
		if ( ! $user_data && is_email( $login ) && apply_filters( 'woocommerce_get_username_from_email', true ) ) {
			$user_data = get_user_by( 'email', $login );
		}

		$errors = new WP_Error();

		do_action( 'lostpassword_post', $errors, $user_data );

		if ( $errors->get_error_code() ) {
            wp_send_json_error([
                "success" => false,
                "errMsg"   => $errors->get_error_message()
            ]);
            die;
		}

		if ( ! $user_data ) {
            wp_send_json_error([
                "success" => false,
                "errMsg"   => "Tên đăng nhập hoặc email không tồn tại"
            ]);
            die;
		}

		if ( is_multisite() && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
			wp_send_json_error([
                "success" => false,
                "errMsg"   => "Tên đăng nhập hoặc email không tồn tại"
            ]);
            die;
		}

		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;

		do_action( 'retrieve_password', $user_login );

		$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

		if ( ! $allow ) {
            wp_send_json_error([
                "success" => false,
                "errMsg"   => "Không thể cập nhật mật khẩu cho tài khoản"
            ]);
            die;

		} elseif ( is_wp_error( $allow ) ) {
            wp_send_json_error([
                "success" => false,
                "errMsg"   => $allow->get_error_message()
            ]);
            die;
		}

		// Get password reset key (function introduced in WordPress 4.4).
		$key = get_password_reset_key( $user_data );

		// Send email notification.
		WC()->mailer(); // Load email classes.
		do_action( 'woocommerce_reset_password_notification', $user_login, $key );

		wp_send_json_error([
            "success" => true,
            "errMsg"   => "Một email chứa nội dung yêu cầu đặt lại mật khẩu đã được gửi tới email mà bạn đã đăng ký. Vui lòng thực hiện theo hướng dẫn!"
        ]);
        die;
    }
}

// check user logged
if( !function_exists('customer_check_user_logged') ) {
    function customer_check_user_logged() {
        $current_user = wp_get_current_user();
        if( !$current_user ) {
            wp_send_json_success([
                "success" => true,
                "user" => false
            ]);
        } else {
            // global $current_user;
            $phone_num = $_SESSION['customer_mobile_phone'];
            if( isset( $phone_num ) ) {
                wp_send_json_success([
                    "success" => true,
                    "phone"=> true,
                    "user" => $current_user
                ]);
                die;
            }
            // check phone number
            $usert_id = $current_user->get_id() ? $current_user->get_id() : $current_user->ID;
            $phone_num = get_field( 'customer_mobile_phone', 'user_'. $usert_id );
            if ( $phone_num ) { 
                $_SESSION['customer_mobile_phone'] = $phone_num;
                wp_send_json_success([
                    "success" => true,
                    "user" => $current_user
                ]);
            } else {
                wp_logout();
                wp_send_json_success([
                    "success" => true,
                    "user" => false
                ]);
            }
        }
        die;
    }
}