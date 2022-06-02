<?php 
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    
    class GEARVNSaleAccessoriesCampaign {

        /**
         * @var int
         */
        private $ID;
        
        /**
         * @var string
         */
        private $name;

        /**
         * @var int
         */
        private $product_id;

        /**
         * @var datetime
         */
        private $start_date;

         /**
         * @var datetime
         */
        private $enable;

        /**
         * @var datetime
         */
        private $end_date;

        /**
         * @var string
         */
        private $user_create;

        /**
         * @var datetime
         */
        private $create_at;

        /**
         * @var datetime
         */
        private $update_at;

        private $table_name = 'se_campaign';

        function __construct( $init_data = null ) {
            if ( $init_data != null ) {
                $init_data_json = json_decode( $init_data, true );

                if ( json_last_error() == JSON_ERROR_NONE ) {
                    $this->init_data( $init_data_json );
                } 
            }
        }

        private function init_data( $init_data_json ) {
            if ( $init_data_json != null ) {
                $this->ID               = empty( $init_data_json['ID'] ) ? null : $init_data_json['ID'];
                $this->name             = empty( $init_data_json['name'] ) ? null : $init_data_json['name'];
                $this->product_id       = empty( $init_data_json['product_id'] ) ? null : $init_data_json['product_id'];
                $this->start_date       = empty( $init_data_json['start_date'] ) ? null : $init_data_json['start_date'];
                $this->end_date         = empty( $init_data_json['end_date'] ) ? null : $init_data_json['end_date'];
                $this->enable           = empty( $init_data_json['enable'] ) ? false : $init_data_json['enable'];
                $this->user_create      = empty( $init_data_json['user_create'] ) ? null : $init_data_json['user_create'];
                $this->create_at        = empty( $init_data_json['create_at'] ) ? null : $init_data_json['create_at'];
                // $this->update_at        = empty( $init_data_json['update_at'] ) ? null : $init_data_json['update_at'];
            }
        }

        private function getFormatData( $obj ) {
            return array(
                "ID"            => $obj->ID,
                "name"          => $obj->name,
                "product_id"    => $obj->product_id,
                "start_date"    => $obj->start_date,
                "end_date"      => $obj->end_date,
                "enable"        => $obj->enable,
                "user_create"   => $obj->user_create,
                "create_at"     => $obj->create_at
            );
        }

        public function checkCurrentCampaign() {
            $current_data = $this->getFormatData( $this );
            $result = $this->getCampaignByField( 'product_id', $this->product_id );

            if ( !$result || count( $result ) <= 0 ) return false;
            else $old_data = $this->getFormatData( $result[0] );

            if ( $current_data['name'] != $old_data['name'] || $current_data['start_date']  != $old_data['start_date'] 
                || $current_data['end_date'] != $old_data['end_date']
                || $current_data['enable'] != $old_data['enable'] || $current_data['user_create'] != $old_data['user_create']) 
                return false;

            return true;
        }

        private function addNewCampaign() {
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name;
            //$wpdb->delete( $table_name , array( 'product_id' => $this->product_id) );

            $result_old = $this->getCampaignByField( 'product_id', $this->product_id );

            if ( empty( $result_old ) || count( $result_old ) < 1 ) {
                $result = $wpdb->insert( $table_name , array( 'name' =>$this->name,
                                                            'start_date' => $this->start_date,
                                                            'end_date' => $this->end_date,
                                                            'enable' => $this->enable,
                                                            'product_id' => $this->product_id,
                                                            'user_create' => $this->user_create ));
                if( $result ) {
                    return $wpdb->insert_id;
                } else return $result;
            } else {
                $result = $wpdb->update( $table_name , array( 'name' =>$this->name,
                                                            'start_date' => $this->start_date,
                                                            'end_date' => $this->end_date,
                                                            'enable' => $this->enable,
                                                            'user_create' => $this->user_create ),
                                                        array('product_id' => $this->product_id ));
                if( $result ) {
                    return $result_old[0]->ID;
                } else return $result;
            }
        }

        private function addDataToHistory( $action = 'create' ) {
            $result = $this->getCampaignByField( 'product_id', $this->product_id );
            $old_data = null;
            if ( $result && count( $result ) > 0 ) {
                $result = $result[0];
                $old_data = $this->getFormatData( $result );
                $this->ID = $result->ID;
                $action = 'update';
            }

            $current_data = $this->getFormatData( $this );
            $history = new GEARVNSaleAccessoriesHistory();
            $history->addNewHistory( array( 'user_create' => $this->user_create,
                                        'table_name' => $this->table_name,
                                        'new_data' => json_encode( $current_data ),
                                        'old_data' => json_encode( $old_data ),
                                        'action' => $action ) );
        }

        public function getCampaignByField( $field_name, $value ) {
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name;
            $result = $wpdb->get_results( "select * from $table_name WHERE $field_name = '$value'" );

            return $result;
        }

        public function saveCampaignData() {
            // check with current product id has any campaing
            // if YES => write old and new data to history
            // $exists_campaign = $this->getCampaignByField( 'product_id', $this->product_id );

            // if ( $exists_campaign && count( $exists_campaign ) > 0 ) {
            //     // insert to history
            // }
            try {
                $this->addDataToHistory( 'create' );
                $insert_data = $this->addNewCampaign();
    
                return $insert_data;
            } catch ( Exception $e ) {
                $msg = $e->getMessage();
            }
        }

        public function getInfoCampaignByProductId( $product_id ) {
            $product = wc_get_product( $product_id );

            if ( $product ) {
                $campaign = $this->getCampaignByField( 'product_id', $product_id );
                if( empty( $campaign ) || count( $campaign ) == 0 ) return null;

                $campaign = $campaign[0];
                if ( !$campaign->enable || $campaign->enable == 0 ) return null;

                $date_now = date("Y-m-d");

                if ( $campaign->start_date <= $date_now && $campaign->end_date >= $date_now ) {
                    // get group
                    $group_class = new GEARVNGroupSaleAccessory();
                    $groups = $group_class->getGroupsByCampaign( $campaign->ID );
                    $result = [];
                    if( empty( $groups ) || count( $groups ) <= 0 ) return false;

                    $product_class = new GEARVNProductSaleAccessory();

                    foreach( $groups as $group ) {
                        $products = $group_class->getProductIdsByGroup( $group->ID );

                        if ( empty( $products ) || count( $products ) <= 0 ) continue;

                        $pids = [];
                        foreach( $products as $pr ) {
                            array_push( $pids, $pr->product_id );
                        }

                        $item = [
                            "se_name"   => $group->name,
                            "se_type"   => $group->discount_type,
                            "se_down"   => $group->discount_value,
                            "products"  => $pids
                        ];

                        array_push( $result, $item );
                    }

                    if ( count( $result ) <= 0 ) return null;
                    return $result;
                }
                return null;
            } 
            return null;
        }

        /**
         * $date : ngày tạo order, không bao gồm thời gian "yy-m-d"
         * $order_create_date: ngày giờ tạo order "yy-m-d H:m:s"
         */
        public function getInfoCampaignByProductIdAndDate( $product_id, $date, $order_create_date ) {
            /**
             * function này sẽ dựa vào history để lấy được thông tin khuyến mãi của ngày tạo đơn hàng
             * 
             */
            // lấy campaign theo ngày tạo đơn
            $campaign = $this->getCampaignByField( 'product_id', $product_id );
            if( empty( $campaign ) || count( $campaign ) == 0 ) return null;

            $campaign = $campaign[0];
            $history = new GEARVNSaleAccessoriesHistory( );
            if ( $campaign->start_date <= $date && $campaign->end_date >= $date && ( $campaign->enable || $campaign->enable == 1 ) ) {
                // to do here ?????
            } else {
                /**
                 * trường hợp campaign ngày này không còn hiệu lực
                 */
                // lấy campaign từ history
                // check với trường hợp đơn hàng tạo mới
                $conditon = "new_data LIKE '%\"product_id\":\"" . $product_id . "\"%' AND table_name = '" . $this->table_name . "' AND create_at  BETWEEN '" .$order_create_date. "' AND '" .$date. " 23:59:59' ORDER BY create_at DESC";
                $records = $history->getHistoryByCondition( $conditon );
                if( empty( $records ) || count( $records ) == 0 ) return null;
                $record = $records[0];
                if ( $record->action == 'update' ) {
                    $this->init_data( json_decode( $record->old_data, true ) );
                } else {
                    $this->init_data( json_decode( $record->new_data, true ) );
                }
                $campaign = $this;
                if ( !$campaign->enable || $campaign->enable == 0 ) return null;
                
            }

            // thời gian kiểm tra group sẽ là lúc tạo order
            $group_class = new GEARVNGroupSaleAccessory();

            $groups = $group_class->getGroupsByCampaign( $campaign->ID, null );

            if ( empty( $groups ) || count( $groups ) == 0 ) return null;

            // lọc lấy những group nào đang được enable
            $filter_groups = [];
            foreach( $groups as $group ) {
                // $json = json_encode( $group );
                if ( !empty( $filter_groups[ $group->ID ] ) ) continue;
                if ( $group->enable == 1 ) {
                    $filter_groups[ $group->ID ] = $group;
                } else {
                    // get từ history
                    $group_condition = " table_name = 'se_group' AND new_data LIKE '%\"ID\":\"$group->ID\",\"campaign_id\":\"$campaign->ID\"%' AND create_at  >= '$order_create_date' ORDER BY create_at DESC ";
                    $records = $history->getHistoryByCondition( $group_condition );
                    foreach( $records as $record ) {
                        $json_data = $record->new_data;
                        if ( $record->action == 'update' || $record->action == 'delete' ) {
                            $json_data = $record->old_data;
                        }
                        $json_data = json_decode( $json_data, true );
                        if ( !$json_data['enable'] || $json_data['enable'] == 0 ) continue;
                        $json_data = (object)$json_data;
                        $filter_groups[ $json_data->ID ] = $json_data;
                    }
                }
            }

            $result = [];
            $product_class = new GEARVNProductSaleAccessory();

            foreach( $filter_groups as $key => $group ) {
                if ( $group == null ) continue;
                $products = $group_class->getProductIdsByGroup( $key );

                if ( empty( $products ) || count( $products ) <= 0 ) continue;

                $pids = [];
                foreach( $products as $pr ) {
                    array_push( $pids, $pr->product_id );
                }

                $item = [
                    "se_name"   => $group->name,
                    "se_type"   => $group->discount_type,
                    "se_down"   => $group->discount_value,
                    "products"  => $pids
                ];

                array_push( $result, $item );
            }

            if ( count( $result ) <= 0 ) return null;
            return $result;
        }

        public function removeDrafCampaignByProductId( $product_id ) {
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name;
            $result = $wpdb->delete( $table_name , array( 'product_id' => $product_id ) );
            return $result;
        }
    }