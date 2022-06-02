<?php 
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * đối với class này trong lớp history sẽ chỉ có 2 định dạng create - delete
     * trong new_data sẽ đính kèm item cuối cùng dạng 'group_id:<id>' vào để check được xem data đó thuộc nhóm nào
     */
    
    class GEARVNProductSaleAccessory {
        /**
         * @var int
         */
        private $group_id;
        
        /**
         * @var int
         */
        private $product_id;

        /**
         * @var string
         */
        private $user_create;

         /**
         * @var int
         */
        private $display_index;

        /**
         * @var datetime
         */
        private $create_at;

        /**
         * @var datetime
         */
        private $update_at;

        private $table_name = 'se_products';

        function __construct( $init_data = null ) {
            if ( $init_data != null ) {
                $init_data_json = json_decode( $init_data, true );

                if ( json_last_error() == JSON_ERROR_NONE ) {
                    $this->init_data( $init_data_json );
                } 
            }
        }

        private function init_data( $init_data_json ) {
            if ( $init_data_json == null ) {
                $this->ID               = empty( $init_data_json['ID'] ) ? null : $init_data_json['ID'];
                $this->group_id         = empty( $init_data_json['group_id'] ) ? null : $init_data_json['group_id'];
                $this->product_id       = empty( $init_data_json['product_id'] ) ? null : $init_data_json['product_id'];
                $this->user_create      = empty( $init_data_json['user_create'] ) ? null : $init_data_json['user_create'];
                $this->create_at        = empty( $init_data_json['create_at'] ) ? null : $init_data_json['create_at'];
                // $this->update_at        = empty( $init_data_json['update_at'] ) ? null : $init_data_json['update_at'];
            }
        }

        private function addDataToHistory( $action = 'create') {
            $old_data = null;
            $current_data = $this->getFormatData( $this );
            $history = new GEARVNSaleAccessoriesHistory();

            if( $action == 'create' ) {
                array_push( $current_data, 'group_id:'.$group_id );
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
                    array_push( $current_data, 'group_id:'.$group_id );
                    array_push( $old_data, 'group_id:'.$group_id );
                    $old_data && $history->addNewHistory( array( 'user_create' => $this->user_create,
                                                            'table_name' => $this->table_name,
                                                            'new_data' => json_encode( $current_data ),
                                                            'old_data' => json_encode( $old_data ),
                                                            'action' => $action ) );
                }

            } else {
                array_push( $current_data, 'group_id:'.$group_id );
                $history->addNewHistory( array( 'user_create' => $this->user_create,
                                                            'table_name' => $this->table_name,
                                                            'new_data' => json_encode( $current_data ),
                                                            'old_data' => null,
                                                            'action' => $action ) );
            }
        }

        public function removeAllProductByGroup( $group_id, $user_create ) {
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name;

            $group = new GEARVNGroupSaleAccessory();
            $history = new GEARVNSaleAccessoriesHistory();

            $ids = $group->getProductIdsByGroup( $group_id );
            $pids = [];
            foreach( $ids as $pr ) {
                array_push( $pids, $pr->product_id );
            }
            if ( count( $pids ) > 0 ) {
                array_push( $pids, 'group_id:'.$group_id );

                $history->addNewHistory( array( 'user_create' => $user_create,
                                                                'table_name' => $this->table_name,
                                                                'new_data' => json_encode( $pids ),
                                                                'old_data' => null,
                                                                'action' => 'delete' ) );
                $result = $wpdb->delete( $table_name , array( 'group_id' => $group_id ) );   
            }
        }

        private function insertProduct( $id, $group_id, $user_create, $index ) {
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name;
            $result = $wpdb->insert( $table_name , array( 'group_id' => $group_id,
                                                            'product_id' => $id,
                                                            'user_create' => $user_create,
                                                            'display_index' => $index ));
            return $result;
        }

        public function insertProducts( $ids, $group_id, $user_create ) {

            if ( count( $ids ) <= 0 ) return 0;
            $this->removeAllProductByGroup( $group_id, $user_create );
            $count = 0;
            foreach( $ids as $id ) {
                $product_object = wc_get_product( $id );

                if ( !$product_object ) continue;

                $rs = $this->insertProduct( $id, $group_id, $user_create, $count );

                if ( $rs ) $count ++;
            }
            $history = new GEARVNSaleAccessoriesHistory();
            array_push( $ids, 'group_id:'.$group_id );
            $history->addNewHistory( array( 'user_create' => $user_create,
                                                            'table_name' => $this->table_name,
                                                            'new_data' => json_encode( $ids ),
                                                            'old_data' => null,
                                                            'action' => 'create' ) );
            return $count;

        }


    }