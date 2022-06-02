<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sale_Accessories_Helper {

    public static function init_api() {
        add_action( 'wp_ajax_se_search_product', array('Sale_Accessories_Helper', 'search_product') );
        add_action( 'wp_ajax_se_get_product_data', array('Sale_Accessories_Helper', 'get_product_data') );
        add_action( 'wp_ajax_se_insert_campaign', array('Sale_Accessories_Helper', 'insert_campaign') );
        add_action( 'wp_ajax_se_insert_campaign_group', array('Sale_Accessories_Helper', 'insert_campaign_group') );
        add_action( 'wp_ajax_se_update_campaign_group', array('Sale_Accessories_Helper', 'update_campaign_group') );
        add_action( 'wp_ajax_se_remove_campaign_group', array('Sale_Accessories_Helper', 'remove_campaign_group') );
        add_action( 'wp_ajax_se_update_product_data', array('Sale_Accessories_Helper', 'update_product_data') );
        // add_action( 'wp_ajax_nopriv_se_search_product', array('Sale_Accessories_Helper', 'search_product') );
    }
    
    public static function search_product() {
        
        if ( isset( $_REQUEST['fn'] ) && 'get_ajax_search' == $_REQUEST['fn'] && isset( $_GET['term'] ) ) {
           
            $term       = isset( $_GET['term'] ) ? (string) wc_clean( wp_unslash( $_GET['term'] ) ) : '';
            $data_store = WC_Data_Store::load( 'product' );
            $ids        = $data_store->search_products( $term, 'public', false );
            
            $products        = array();
            
            foreach ( $ids as $id ) {
                $product_object = wc_get_product( $id );

                if ( !$product_object ) continue;
                
                $product = array(
                    "ID"        => $product_object->get_id(),
                    "name"      => $product_object->get_name(),
                    "image"     => get_the_post_thumbnail_url( $product_object->get_id() ),
                    "price"     => wc_price($product_object->get_price())
                );
                
                array_push( $products, $product );
            }

            wp_send_json_success( array(
                'error' => '',
                'products'  => $products
            ) );
        } else {
            wp_send_json_error( array(
                'error' => __('Params not found!', THNS_SALE_ACCESSORIES_PLUGIN)
            ) );
        }
        die;
    }

    public static function get_product_data() {
        if ( isset( $_REQUEST['fn'] ) && 'post_ajax_search' == $_REQUEST['fn'] && isset( $_POST['ids'] ) ) {
           
            $ids       = isset( $_POST['ids'] ) ? $_POST['ids'] : [];
            if ( !$ids || count($ids) <= 0 ) {
                wp_send_json_error( array(
                    'error' => __('Params not found!', THNS_SALE_ACCESSORIES_PLUGIN)
                ) );
                die;
            }
            
            $products        = array();
            
            foreach ( $ids as $id ) {
                $product_object = wc_get_product( $id );

                if ( !$product_object ) continue;
                
                $product = array(
                    "ID"        => $product_object->get_id(),
                    "name"      => $product_object->get_name(),
                    "image"     => get_the_post_thumbnail_url( $product_object->get_id() ),
                    "price"     => wc_price($product_object->get_price())
                );
                
                array_push( $products, $product );
            }

            wp_send_json_success( array(
                'error' => '',
                'products'  => $products
            ) );
        } else {
            wp_send_json_error( array(
                'error' => __('Params not found!', THNS_SALE_ACCESSORIES_PLUGIN)
            ) );
        }
        die;

    }

    public static function insert_campaign() {
        $user = wp_get_current_user();

        if ( $user && current_user_can( 'edit_products' ) ) {

            $campaign_name =        $_POST['campaign_name'];
            $product_id =           $_POST['current_product_id'];
            $campaign_start_date =  $_POST['campaign_start_date'];
            $campaign_end_date =    $_POST['campaign_end_date'];
            $enable =               $_POST['campaign_enable'];

            if ( empty( $campaign_name ) || empty( $product_id ) || empty( $campaign_start_date ) || empty( $campaign_end_date ) ) {
                wp_send_json_error( array(
                    'error' => __('Params not valid!', THNS_SALE_ACCESSORIES_PLUGIN)
                ) );
                die;
            } else {
                $campaign_data = array(
                    "name" => $campaign_name,
                    "product_id"    => $product_id,
                    "start_date"    => $campaign_start_date,
                    "end_date"      => $campaign_end_date,
                    "enable"        => $enable,
                    "user_create"   => $user->user_login
                );

                $campaign = new GEARVNSaleAccessoriesCampaign( json_encode( $campaign_data ) );
                if ( $campaign->checkCurrentCampaign() ) {
                    wp_send_json_error( array(
                        'error' => __('Same data!', THNS_SALE_ACCESSORIES_PLUGIN),
                        'display'   => false
                    ) );
                    die;
                }
                $result = $campaign->saveCampaignData();
                
                if( $result ) {
                    wp_send_json_success( array(
                        'campaign_id' => $result
                    ) );
                } else {
                    wp_send_json_error( array(
                        'error' => __('Service error!', THNS_SALE_ACCESSORIES_PLUGIN)
                    ) );
                }
                die;
            }

        } else {
            wp_send_json_error( array(
                'error' => __('Permission denied!', THNS_SALE_ACCESSORIES_PLUGIN)
            ) );
            die;
        }
    }

    public static function insert_campaign_group() {
        $user = wp_get_current_user();

        if ( $user && current_user_can( 'edit_products' ) ) {
            $campaign_id =          $_POST['campaign_id'];
            $name =                 $_POST['name'];
            $discount_type =        $_POST['discount_type'];
            $discount_value =       $_POST['discount_value'];
            $display_index =        $_POST['display_index'];

            if ( empty( $campaign_id ) || empty( $name ) || empty( $discount_type ) || empty( $discount_value ) ) {
                wp_send_json_error( array(
                    'error' => __('Params not valid!', THNS_SALE_ACCESSORIES_PLUGIN)
                ) );
                die;
            } else {
                $group_data = array(
                    "campaign_id"       => $campaign_id,
                    "name"              => $name,
                    "discount_type"     => $discount_type,
                    "discount_value"    => $discount_value,
                    "display_index"     => $display_index,
                    "user_create"       => $user->user_login
                );

                $group = new GEARVNGroupSaleAccessory( json_encode( $group_data ) );
                if ( $group->checkCurrentGroup() ) {
                    wp_send_json_error( array(
                        'error' => __('Same data!', THNS_SALE_ACCESSORIES_PLUGIN),
                        'display'   => false
                    ) );
                    die;
                }
                $result = $group->saveGroupData();
                
                if( $result ) {
                    wp_send_json_success( array(
                        'group_id' => $result
                    ) );
                } else {
                    wp_send_json_error( array(
                        'error' => __('Service error!', THNS_SALE_ACCESSORIES_PLUGIN)
                    ) );
                }
                die;
            }

        } else {
            wp_send_json_error( array(
                'error' => __('Permission denied!', THNS_SALE_ACCESSORIES_PLUGIN)
            ) );
            die;
        }
    }

    public static function update_campaign_group() {
        $user = wp_get_current_user();

        if ( $user && current_user_can( 'edit_products' ) ) {
            $group_data =          $_POST['group'];
            $group_data['user_create'] = $user->user_login;

            $group = new GEARVNGroupSaleAccessory( json_encode( $group_data ) );
            if ( $group->checkCurrentGroup() ) {
                wp_send_json_error( array(
                    'error' => __('Same data!', THNS_SALE_ACCESSORIES_PLUGIN),
                    'display'   => false
                ) );
                die;
            }
            $result = $group->updateGroupData();
                
            if( $result ) {
                wp_send_json_success( array(
                    'group_id' => $result
                ) );
            } else {
                wp_send_json_error( array(
                    'error' => __('Service error!', THNS_SALE_ACCESSORIES_PLUGIN)
                ) );
            }
            die;

        } else {
            wp_send_json_error( array(
                'error' => __('Permission denied!', THNS_SALE_ACCESSORIES_PLUGIN)
            ) );
            die;
        }
    }

    public static function remove_campaign_group() {
        $user = wp_get_current_user();

        if ( $user && current_user_can( 'edit_products' ) ) {
            $group_data =          $_POST['group'];
            $group = new GEARVNGroupSaleAccessory( json_encode( $group_data ) );
            $result = $group->deleteGroupData();
            if( $result ) {
                wp_send_json_success( array(
                    'group_id' => $result
                ) );
            } else {
                wp_send_json_error( array(
                    'error' => __('Service error!', THNS_SALE_ACCESSORIES_PLUGIN)
                ) );
            }
            die;
        } else {
            wp_send_json_error( array(
                'error' => __('Permission denied!', THNS_SALE_ACCESSORIES_PLUGIN)
            ) );
            die;
        }
    }

    public static function update_product_data() {
        $user = wp_get_current_user();

        if ( $user && current_user_can( 'edit_products' ) ) {
            $ids =              $_POST['ids'];
            $group_id =         $_POST['group_id'];
            if ( count( $ids ) <= 0 || $group_id == '' ) {
                wp_send_json_error( array(
                    'error' => __('Params not found!', THNS_SALE_ACCESSORIES_PLUGIN)
                ) );
                die;
            }

            $group = new GEARVNGroupSaleAccessory( );
            $currentIds = $group->getProductIdsByGroup( $group_id );
            if ( $currentIds == $ids ) {
                wp_send_json_error( array(
                    'error' => __('Same data!', THNS_SALE_ACCESSORIES_PLUGIN),
                    'display'   => false
                ) );
                die;
            } else {
                // $user->user_login
                $product_class = new GEARVNProductSaleAccessory();

                $result = $product_class->insertProducts( $ids, $group_id, $user->user_login );

                wp_send_json_success( array(
                    'total' => $result
                ) );
            }

        } else {
            wp_send_json_error( array(
                'error' => __('Permission denied!', THNS_SALE_ACCESSORIES_PLUGIN)
            ) );
            die;
        }
    }
}

Sale_Accessories_Helper::init_api();