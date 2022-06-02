<?php 
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    class GEARVNSaleAccessoriesHistory {
        /**
         * @var int
         */
        private $ID;

        /**
         * @var string
         */
        private $user_create;

        /**
         * @var string
         */
        private $table_name;

        /**
         * @var string
         */
        private $new_data;

        /**
         * @var string
         */
        private $old_data;

        /**
         * @var string
         */
        private $action;

        /**
         * @var datetime
         */
        private $create_at;

        private $table_name_hs = 'se_history';

        function __construct() {

        }

        public function addNewHistory( $data ) {
            global $wpdb;
            $table_name = $wpdb->prefix . $this->table_name_hs;
            $result = $wpdb->insert(  $table_name , array(   'user_create' => $data['user_create'],
                                                            'table_name' => $data['table_name'],
                                                            'new_data' => $data['new_data'],
                                                            'old_data' => !empty( $data['old_data'] ) ? $data['old_data'] : "",
                                                            'action' => $data['action'] )) ;
            $sql = $wpdb->last_query;
            return $result;
        }

        public function getHistoryByCondition( $condition ) {
            global $wpdb;
            $table_name_history = $wpdb->prefix . $this->table_name_hs;
            $result = $wpdb->get_results( "select * from $table_name_history where " . $condition );
            $sql = $wpdb->last_query;
            return $result;
        }
    }