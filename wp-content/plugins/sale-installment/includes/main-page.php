<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// HOOK creaet admin menu
add_action( 'admin_menu', 'star_bank_menu', 20 );
function star_bank_menu() {
    add_options_page( 'Trả góp', 'Trả góp', 'manage_options', 'star_banks', 'setting_star_banks' );
}

function setting_star_banks() {
    if ( isset( $_GET['type'] ) ) {
        $bank_id = $_GET['bank_id'];

        if ( empty( $bank_id ) ) {
            wp_redirect( admin_url( 'admin.php?page=star_banks' ) );
        } else {
            $objbank = new Bank();

            $bank = $objbank->getBankById( $bank_id );

            if ( $bank == null ) wp_redirect( admin_url( 'admin.php?page=star_banks' ) );
            else {
                if ( $_GET['type'] == 'edit' ) {
                    include_once BANK_PLUGIN_DIR . '/includes/views/admin/edit-bank.php';
                } elseif ( $_GET['type'] == 'insert-sub' ) {
                    if ( $bank[0]->bank_type === 1 ) {
                        wp_redirect( admin_url( 'admin.php?page=star_banks' ) );
                    } else {
                        include_once BANK_PLUGIN_DIR . '/includes/views/admin/insert-sub-bank.php';
                    }
                } elseif ( $_GET['type'] == 'insert-installment' ) {
                    include_once BANK_PLUGIN_DIR . '/includes/views/admin/installment/insert-installment.php';
                }
            }
        }
    }
    else include_once BANK_PLUGIN_DIR . '/includes/views/admin/manage-banks.php';
}
