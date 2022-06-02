<?php 
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    
    class GEARVNGroupSaleAccessory {

        /**
         * @var int
         */
        private $ID;

        /**
         * @var int
         */
        private $campaign_id;
        
        /**
         * @var string
         */
        private $name;

        /**
         * @var string
         */
        private $discount_type;

        /**
         * @var int
         */
        private $discount_value;

        /**
         * @var int
         */
        private $display_index;

        /**
         * @var string
         */
        private $user_create;

        /**
         * @var int
         */
        private $enable = 1;

        /**
         * @var datetime
         */
        private $create_at;

        /**
         * @var datetime
         */
        private $update_at;

        private $table_name = 'se_group';

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
                $this->campaign_id      = empty( $init_data_json['campaign_id'] ) ? null : $init_data_json['campaign_id'];
                $this->name             = empty( $init_data_json['name'] ) ? null : $init_data_json['name'];
                $this->discount_type    = empty( $init_data_json['discount_type'] ) ? null : $init_data_json['discount_type'];
                $this->discount_value   = empty( $init_data_json['discount_value'] ) ? null : $init_data_json['discount_value'];
                $this->display_index    = empty( $init_data_json['display_index'] ) ? null : $init_data_json['display_index'];
                $this->user_create      = empty( $init_data_json['user_create'] ) ? null : $init_data_json['user_create'];
                $this->create_at        = empty( $init_data_json['create_at'] ) ? null : $init_data_json['create_at'];
                // $this->update_at        = empty( $init_data_json['update_at'] ) ? null : $init_data_json['update_at'];
            }
        }

        public function getFormatData( $obj ) {
            return array(
                "ID"                => $obj->ID,
                "campaign_id"       => $obj->campaign_id,
                "name"              => $obj->name,
                "discount_type"     => $obj->discount_type,
                "discount_value"    => $obj->discount_value,
                "display_index"     => $obj->display_index,
                "user_create"       => $obj->user_create,
                "enable"            => $obj->enable,
                "create_at"         => $obj->create_at,
            );
        }

        private function getRecordByThis() {
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name;
            $result = $wpdb->get_results( "select * from $table_name WHERE `campaign_id` = '$this->campaign_id' 
                                            AND `name` = '$this->name' 
                                            AND `discount_type` = '$this->discount_type' 
                                            AND `discount_value` = '$this->discount_value' 
                                            AND `display_index` = '$this->display_index' 
                                            AND `user_create` = '$this->user_create' " );
            
            return $result;
        }

        private function addDataToHistory( $action = 'create') {
            $old_data = null;
            $current_data = $this->getFormatData( $this );
            $history = new GEARVNSaleAccessoriesHistory();

            if( $action == 'create' ) {
                $history->addNewHistory( array( 'user_create' => $this->user_create,
                                                'table_name' => $this->table_name,
                                                'new_data' => json_encode( $current_data ),
                                                'old_data' => json_encode( $old_data ),
                                                'action' => $action ) );
            } else if( $action == 'update' ) {
                $record = $this->getGroupByField( 'ID', $this->ID );

                if ( $record && count( $record ) > 0 ) {
                    $record = $record[0];
                    $old_data = $this->getFormatData( $record );

                    $old_data && $history->addNewHistory( array( 'user_create' => $this->user_create,
                                                            'table_name' => $this->table_name,
                                                            'new_data' => json_encode( $current_data ),
                                                            'old_data' => json_encode( $old_data ),
                                                            'action' => $action ) );
                }

            } else {
                $old_data = $current_data;
                $current_data['enable'] = 0;
                $history->addNewHistory( array( 'user_create' => $this->user_create,
                                                            'table_name' => $this->table_name,
                                                            'new_data' => json_encode( $current_data ),
                                                            'old_data' => json_encode( $old_data ),
                                                            'action' => $action ) );
            }
        }

        private function addNewGroup( ) {
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name;

            $result = $wpdb->insert( $table_name , array( 'campaign_id' =>$this->campaign_id,
                                                            'name' => $this->name,
                                                            'discount_type' => $this->discount_type,
                                                            'discount_value' => $this->discount_value,
                                                            'display_index' => $this->display_index,
                                                            'user_create' => $this->user_create ));
            if( $result ) {
                return $wpdb->insert_id;
            } else return $result;
        }

        private function updateGroup() {
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name;

            $result = $wpdb->update( $table_name , array( 'campaign_id' =>$this->campaign_id,
                                                            'name' => $this->name,
                                                            'discount_type' => $this->discount_type,
                                                            'discount_value' => $this->discount_value,
                                                            'display_index' => $this->display_index,
                                                            'user_create' => $this->user_create ),
                                                    array('ID' => $this->ID ));
            
            return $result;
        }

        public function checkCurrentGroup() {
            $result = $this->getRecordByThis();

            if ( $result && count( $result ) > 0 )  return true;
            return false;
        }

        public function getGroupByField( $field_name, $value, $enable = 1 ) {
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name;
            $result = $wpdb->get_results( "select * from $table_name WHERE `$field_name` = '$value' AND `enable` = " . $enable );
            
            return $result;
        }

        public function saveGroupData() {
            $insert_data = $this->addNewGroup();
            if ( $insert_data != 0 || $insert_data > 0 ) {
                $this->ID = $insert_data;
                $this->addDataToHistory( 'create' );
            }
            return $insert_data;
        }

        public function updateGroupData() {
            $this->addDataToHistory( 'update' );
            $update_data = $this->updateGroup();
    
            return $update_data;
        }

        /**
         * để giải quyết bài toán hiện tại, group sẽ không được delete mà thay vào đó sẽ chỉ ẩn group đó đi
         */
        public function deleteGroupData() {
            // remove all products
            // $product_class = new GEARVNProductSaleAccessory();
            // $product_class->removeAllProductByGroup( $this->ID, $this->user_create );
            $this->addDataToHistory( 'delete' );
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name;
            // $result = $wpdb->delete( $table_name , array( 'ID' => $this->ID ) );
            $result = $wpdb->update( $table_name , array( 'enable' => 0 ),
                                                    array('ID' => $this->ID ));
            return $result;
        }

        public function getGroupsByCampaign( $campaign_id, $enable = 1 ) {
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name;
            if ( $enable == null ) {
                $result = $wpdb->get_results( "select * from $table_name WHERE `campaign_id` = '$campaign_id' order by display_index ASC" );
            } else {
                $result = $wpdb->get_results( "select * from $table_name WHERE `campaign_id` = '$campaign_id' AND `enable` = $enable order by display_index ASC" );
            }
            $sql = $wpdb->last_query;
            return $result;
        }

        public function getProductIdsByGroup( $group_id ) {
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name;
            $table_product_name = $wpdb->prefix . 'se_products';
            $result = $wpdb->get_results( "select product_id from $table_name  INNER JOIN $table_product_name ON $table_name.ID = $table_product_name.group_id  
                                                WHERE $table_name.ID = '$group_id' order by $table_product_name.display_index ASC " );
            
            return $result;
        }

        public function getGroupsByCondition( $condition ) {
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name;
            $result = $wpdb->get_results( "select * from $table_name " . $condition );
            $sql = $wpdb->last_query;
            return $result;
        }
    }