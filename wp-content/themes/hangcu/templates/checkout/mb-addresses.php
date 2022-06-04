<?php
    get_header('checkout');
    $user     = wp_get_current_user();

    $otherAddr = null;
    if ($user->ID !== 0) {
        $otherAddr = get_user_meta( $user->ID, 'hangcu_multiple_shipping_addresses', true );
    }

    if( isset( $_GET['type'] ) ) {
        if( $_GET['type'] == 'addnew' ) {
            include_once( 'address/mb-addnew.php' );
        } elseif( $_GET['type'] == 'edit' && isset( $_GET['key'] ) ) {
            $edit_address = null;
            foreach ( $otherAddr as $idx => $address ) {
                if( $idx == $_GET['key'] ) {
                    $edit_address = $address;
                    break;
                }
            }
            if( !$edit_address ) {
                include_once( 'address/mb-list.php' );
            } else {
                include_once( 'address/mb-update.php' );
            }
            
        } else {
            include_once( 'address/mb-list.php' );
        }
    } else {
        include_once( 'address/mb-list.php' );
    }
?>