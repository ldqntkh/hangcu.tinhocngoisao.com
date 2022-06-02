<?php

if( !function_exists('hook_search_in_array') ) {
    function hook_search_in_array($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }elseif(isset($array[$key]) && is_serialized($array[$key]) && in_array($value,maybe_unserialize($array[$key]))){
                $results[] = $array;
            }
            foreach ($array as $subarray) {
                $results = array_merge($results, hook_search_in_array($subarray, $key, $value));
            }
        }

        return $results;
    }
}

if( !function_exists('formatAddressHookPayload') ) {
    function formatAddressHookPayload( $state_code, $city_code, $address_2 ) {
        include THEME_PATH . '/inc/webhooks/tinh_thanhpho.php';
        include THEME_PATH . '/inc/webhooks/quan_huyen.php';
        include THEME_PATH . '/inc/webhooks/tinh_thanhpho_old.php';
        include THEME_PATH . '/inc/webhooks/quan_huyen_old.php';
        include THEME_PATH . '/inc/webhooks/xa_phuong_thitran.php';
        
        $tinh_thanh = $tinh_thanhpho[$state_code] || -1;
        if( $tinh_thanh == -1 ) {
            $tinh_thanh = $tinh_thanhpho_old[$state_code] || $state_code;
            $quan = hook_search_in_array($quan_huyen_old,'matp',$state_code);
            usort($quan, 'devvn_natorder' );
            $ten_quan_huyen = '';
            if($quan) {
                foreach( $quan as $q ) {
                    if( $q['maqh'] == $city_code ) {
                        $ten_quan_huyen = $q['name'];
                        break;
                    }
                }
            }
        } else {
            $quan = hook_search_in_array($quan_huyen,'matp',$state_code);
            usort($quan, 'devvn_natorder' );
            $ten_quan_huyen = '';
            if($quan) {
                foreach( $quan as $q ) {
                    if( $q['maqh'] == $city_code ) {
                        $ten_quan_huyen = $q['name'];
                        break;
                    }
                }
            }
        }
        
        if( $ten_quan_huyen == '' ) {
            $ten_quan_huyen = $city_code;
            $tinh_thanh = $state_code;
        }
        
        $xa = hook_search_in_array($xa_phuong_thitran,'maqh',$city_code);
        usort($xa, 'devvn_natorder' );
        $ten_xa = '';
        if($xa) {
            foreach( $xa as $x ) {
                if( $x['xaid'] == $address_2 ) {
                    $ten_xa = $x['name'];
                    break;
                }
            }
        }
        if( $ten_xa == '' ) $ten_xa = $address_2;

        return $ten_xa . ', ' . $ten_quan_huyen . ', ' . $tinh_thanh;
    }
}


add_filter('woocommerce_webhook_payload', function($payload, $resource, $resource_id, $this_id) {

    if( $resource == 'order' ) {
        // get product intergrated code
        $payload['billing']['full_address'] = $payload['billing']['address_1'] . ', ' 
                . formatAddressHookPayload( $payload['billing']['state'], $payload['billing']['city'], $payload['billing']['address_2'] );
    }

    return $payload; 
}, 10, 4);

