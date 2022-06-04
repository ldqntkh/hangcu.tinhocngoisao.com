<?php

add_action( 'wp_head', function() {
    $utm = $_GET['utm'];
    $aff_sid = $_GET['aff_sid'];
    $key = 'tracking_order_at';
    if( !empty( $utm ) && !empty( $aff_sid ) ) {
        if( WC() ) {
            $data = WC()->session->get($key);
            if( isset( $data ) ) {
                $hasValue = false;
                for( $i = 0; $i < count( $data ); $i++ ) {
                    if( $data[$i]['utm'] == $utm || $data[$i]['aff_sid'] == $aff_sid ) {
                        $hasValue = true;
                        break;
                    }
                }
                if( !$hasValue ) {
                    array_push( $data, [ "utm" => $utm, "aff_sid" => $aff_sid ] );
                    WC()->session->set($key, $data);
                }
            } else {
                WC()->session->set($key, [ "utm" => $utm, "aff_sid" => $aff_sid ]);
            }
        }
    }
}, 10 );