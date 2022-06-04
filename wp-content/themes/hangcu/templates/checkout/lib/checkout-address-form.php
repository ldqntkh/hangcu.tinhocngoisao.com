<?php 
    function save_shipping_addresses() {
        if ( isset( $_POST['shipping_account_address_action'] ) && 
                    ( $_POST['shipping_account_address_action'] == 'save' 
                    || $_POST['shipping_account_address_action'] == 'delete' 
                    || $_POST['shipping_account_address_action'] == 'update' ) ) {
            if ($_POST['shipping_account_address_action'] == 'save') {
                $key = $_POST['shipping_address_key_selected'];
                $currentUser = wp_get_current_user();
                $otherAddr = [];

                if ($currentUser->ID !== 0) {
                    $otherAddr = get_user_meta( $currentUser->ID, 'hangcu_multiple_shipping_addresses', true );
                }

                if ($key && $otherAddr[$key]) {
                    WC()->session->set('address_key_selected', $key);
                    WC()->session->set('checkoutstep', 3);
                }
            }

            unset( $_POST['shipping_account_address_action'] );

            $page_url = get_permalink( wc_get_page_id( 'checkout' ) );
            wp_redirect( $page_url );
            exit;
        }
    }

    function hangcu_delete_address() {
        $currentUser = wp_get_current_user();
        $otherAddr = [];

        if ($currentUser->ID !== 0) {
            $otherAddr = get_user_meta( $currentUser->ID, 'hangcu_multiple_shipping_addresses', true );

            unset($otherAddr[$_GET['delete-address']]);

            update_user_meta( $currentUser->ID, 'hangcu_multiple_shipping_addresses', $otherAddr );
        }
        
        $page_url = add_query_arg( array(
            'step' => 'shipping'
        ), wc_get_checkout_url() );
        wp_redirect( $page_url );
        exit;
    }
?>