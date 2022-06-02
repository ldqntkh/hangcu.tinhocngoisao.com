<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Installment {

    private $table_name = 'monthly_installment';

    private $ID;

    private $month;

    private $bank_id;

    private $min_price;

    private $prepaid_percentage;

    private $fee;

    private $docs_require;

    public function __construct( $init_data = null ) {
        if ( $init_data != null ) {
            $init_data_json = json_decode( $init_data, true );

            if ( json_last_error() == JSON_ERROR_NONE ) {
                $this->init_data( $init_data_json );
            } 
        }
    }

    private function init_data( $init_data_json ) {
        if ( $init_data_json != null ) {
            $this->month                    = empty( $init_data_json['month'] ) ? 3 : $init_data_json['month'];
            $this->bank_id                  = empty( $init_data_json['bank_id'] ) ? null : $init_data_json['bank_id'];
            $this->min_price                = empty( $init_data_json['min_price'] ) ? null : $init_data_json['min_price'];
            $this->prepaid_percentage       = empty( $init_data_json['prepaid_percentage'] ) ? 0 : $init_data_json['prepaid_percentage'];
            $this->fee                      = empty( $init_data_json['fee'] ) ? 0 : $init_data_json['fee'];
            $this->docs_require             = empty( $init_data_json['docs_require'] ) ? null : $init_data_json['docs_require'];
        }
    }

    public function getFormatData( $objClass ) {
        return [
            "month" => $objClass->month,
            "bank_id" => $objClass->bank_id,
            "min_price" => $objClass->min_price,
            "prepaid_percentage" => $objClass->prepaid_percentage,
            "fee" => $objClass->fee,
            "docs_require" => $objClass->docs_require,
        ];
    }


    public function addNew() {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table_name;

        $result = $wpdb->insert( $table_name , array( 'month' =>$this->month,
                                                        'bank_id' => $this->bank_id,
                                                        'min_price' => $this->min_price,
                                                        'prepaid_percentage' => $this->prepaid_percentage,
                                                        'fee' => $this->fee,
                                                        'docs_require' => $this->docs_require ));
        $sql = $wpdb->last_query;
        
        return $result;
    }

    public function getListInstallment( $bank_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table_name;
        $sql = "select * from $table_name WHERE bank_id = $bank_id";
        $sql .= " ORDER BY month ";
        $result = $wpdb->get_results( $sql );
            
        return $result;
    }

    public function getListInstallmentHtml( $bank_id ) {
        $installments = $this->getListInstallment( $bank_id );
        ob_start();
        foreach( $installments as $installment ) : ?>
            <tr>
                <td scope="col" class="manage-column column-thumb">
                    <?php echo $installment->month ?>
                </td>
                <td scope="col" class="manage-column column-thumb">
                    <?php echo $installment->min_price ?>
                </td>
                <td scope="col" class="manage-column column-thumb">
                    <?php echo $installment->prepaid_percentage ?>
                </td>
                <td scope="col" class="manage-column column-thumb">
                    <?php echo $installment->fee ?>
                </td>
                <td scope="col" class="manage-column column-thumb">
                    <?php echo $installment->docs_require ?>
                </td>
                <td scope="col" class="manage-column column-thumb">
                    <a href="#" class="delete-installment" data-id="<?php echo $installment->month ?>">
                    Xóa<span class="spinner is-active hide"></span></a>
                </td>
            </tr>
        <?php endforeach; 

        $content = ob_get_contents();
        ob_clean();
        ob_end_flush();
        return $content;
    }

    public function removeInstallment( $bank_id, $month ) {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table_name;
        $result = $wpdb->delete( $table_name , array( 'bank_id' => $bank_id, 'month' => $month ) );
        return $result;
    }
}