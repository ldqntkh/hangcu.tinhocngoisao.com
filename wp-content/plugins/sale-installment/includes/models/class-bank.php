<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Bank {

    private $table_name = 'bank';

    private $sub_table_name = 'sub_bank';

    private $ID;

    private $bank_name;

    private $bank_type;

    private $bank_img;

    private $display_index;

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
            $this->ID               = empty( $init_data_json['ID'] ) ? null : $init_data_json['ID'];
            $this->bank_name        = empty( $init_data_json['bank_name'] ) ? "" : $init_data_json['bank_name'];
            $this->bank_type        = empty( $init_data_json['bank_type'] ) ? 0 : $init_data_json['bank_type'];
            $this->bank_img         = empty( $init_data_json['bank_img'] ) ? "" : $init_data_json['bank_img'];
            $this->display_index    = empty( $init_data_json['display_index'] ) ? 0 : $init_data_json['display_index'];
        }
    }

    public function getFormatData( $objClass ) {
        $image = wp_get_attachment_image_src( $objClass->bank_img, 'full' );
        if ( !$image ) {
            $image = esc_js( wc_placeholder_img_src() );
        } else {
            $image = $image[0];
        }
        return [
            "ID"    => $objClass->ID,
            "bank_name" => $objClass->bank_name,
            "bank_type" => $objClass->bank_type,
            "bank_img"  => $image,
            "display_index" => $display_index
        ];
    }

    public function getFormatDataSubBank( $objClass ) {
        return [
            "sub_bank_name"    => $objClass->sub_bank_name,
            "bank_id" => $objClass->bank_id,
            "display_index" => $display_index
        ];
    }

    public function getBankById( $id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table_name;

        $sql = "select * from $table_name WHERE ID = $id";

        $result = $wpdb->get_results( $sql );
            
        return $result;
    }

    public function getListBanks( ) {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table_name;
        $sql = "select * from $table_name ";

        $sql .= " ORDER BY display_index ";

        $result = $wpdb->get_results( $sql );
            
        return $result;
    }

    public function addNew() {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table_name;

        $result = $wpdb->insert( $table_name , array( 'bank_name' =>$this->bank_name,
                                                        'bank_type' => $this->bank_type,
                                                        'bank_img' => $this->bank_img,
                                                        'display_index' => $this->display_index ));
        $sql = $wpdb->last_query;
        
        if( $result ) {
            return $wpdb->insert_id;
        } else return $result;
    }

    public function getListBanksHtml( ) {
        $banks = $this->getListBanks( );
        ob_start();
        foreach( $banks as $bank ) : 
            $image = wp_get_attachment_image_src( $bank->bank_img, 'full' );
            if ( !$image ) {
                $image = esc_js( wc_placeholder_img_src() );
            } else {
                $image = $image[0];
            }
        ?>
            <tr>
                <td scope="col" class="manage-column column-thumb">
                    <img src="<?php echo $image ?>" width="70px" height="37px"/>
                </td>
                <td scope="col" class="manage-column column-thumb colum-action">
                    <p><strong><?php echo $bank->bank_name ?></strong></p>
                    <p class="action">
                        <a href="<?php echo admin_url( 'admin.php?page=star_banks&type=edit&bank_id='.$bank->ID) ?>" class="edit-brand" action='edit' data-id="<?php echo $bank->ID ?>"><?php _e('Sửa', BANK_PLUGIN_NAME) ?></a>
                        &nbsp;|&nbsp;
                        <a href="#" class="remove-bank" action='delete' data-title='<?php echo $bank->bank_name ?>' data-id="<?php echo $bank->ID ?>"><?php _e('Xóa', BANK_PLUGIN_NAME) ?></a>
                    </p>
                </td>
                <?php 
                    if ( $bank->bank_type == 1 ) { ?>
                        <td scope="col" class="manage-column column-thumb" style="color: green"><?php _e( 'Tổ chức tài chính', BANK_PLUGIN_NAME ) ?></td>
                    <?php } else { ?>
                        <td scope="col" class="manage-column column-thumb" style="color: red"><?php _e( 'Tín dụng', BANK_PLUGIN_NAME ) ?></td>
                    <?php }
                ?>
                <td>
                    <?php 
                        if ( $bank->bank_type == 1 ) { ?>
                            <a href="<?php echo admin_url( 'admin.php?page=star_banks&type=insert-installment&bank_id='.$bank->ID) ?>" data-id="<?php echo $bank->ID ?>"><?php _e('Thiết lập trả góp', BANK_PLUGIN_NAME) ?></a>
                        <?php } else { ?>
                            <a href="<?php echo admin_url( 'admin.php?page=star_banks&type=insert-sub&bank_id='.$bank->ID) ?>" data-id="<?php echo $bank->ID ?>"><?php _e('Thêm sub bank', BANK_PLUGIN_NAME) ?></a>
                            &nbsp;|&nbsp;
                            <a href="<?php echo admin_url( 'admin.php?page=star_banks&type=insert-installment&bank_id='.$bank->ID) ?>" data-id="<?php echo $bank->ID ?>"><?php _e('Thiết lập trả góp', BANK_PLUGIN_NAME) ?></a>
                        <?php }
                    ?>
                </td>
            </tr>
        <?php endforeach; 

        $content = ob_get_contents();
        ob_clean();
        ob_end_flush();
        return $content;
    }

    public function removeBank( $bank_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table_name;
        $result = $wpdb->delete( $table_name , array( 'id' => $bank_id) );
        return $result;
    }

    public function updateBank() {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table_name;

        $result = $wpdb->update( $table_name , array( 'bank_name' =>$this->bank_name,
                                                        'bank_type' => $this->bank_type,
                                                        'bank_img' => $this->bank_img,
                                                        'display_index' => $this->display_index ), 
                                                array('ID' => $this->ID ));
        $sql = $wpdb->last_query;
        $error = $wpdb->last_error;
        return $result;
    }

    public function insertSubBanks( $bank_id, $sub_banks ) {
        try {
            global $wpdb;
            $sub_table_name = $wpdb->prefix . $this->sub_table_name;

            foreach( $sub_banks as $key => $sub ) {
                if ( empty( $sub ) ) {
                    $wpdb->delete( $sub_table_name , array( 'sub_bank_name' => $sub, 'bank_id' => $bank_id ) );
                } else {
                    $result = $wpdb->insert( $sub_table_name , array( 'sub_bank_name' => $sub,
                                                            'bank_id' => $bank_id ));
                    $sql = $wpdb->last_query;
                }
            }
            return true;
        } catch ( Exception $e ) {
            return false;
        }
    }

    public function getSubBanks( $bank_id ) {
        global $wpdb;
        $sub_table_name = $wpdb->prefix . $this->sub_table_name;

        $sql = "select * from $sub_table_name WHERE bank_id = $bank_id";

        $result = $wpdb->get_results( $sql );
            
        return $result;
    }

    public function getBankData() {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table_name;
        $sql = "select * from $table_name ";

        $sql .= " ORDER BY bank_type DESC ";

        $banks = $wpdb->get_results( $sql );

        $taichinh = [];
        $tindung = [];
        $installment = new Installment();

        foreach( $banks as $bank ) {
            $installments = [];
            $installments = $installment->getListInstallment( $bank->ID );
            
            $installments_data = [];

            foreach( $installments as $ins ) {
                $rs = $installment->getFormatData( $ins );
                $installments_data[] = $rs;
            }
            
            
            if ( $bank->bank_type == 1 ) {
                $item = array(
                    "bank"  => $this->getFormatData( $bank ),
                    "installments"   => $installments_data
                );
                $taichinh[] = $item;
                
            } else {
                $sub_banks = $this->getSubBanks( $bank->ID );
                $sub_banks_data = [];

                foreach( $sub_banks as $sub ) {
                    $rs = $this->getFormatDataSubBank( $sub );
                    $sub_banks_data[] = $rs;
                }

                $item = [
                    "bank"  => $this->getFormatData( $bank ),
                    "sub_bank"  => $sub_banks_data,
                    "installments"   => $installments_data
                ];
                $tindung[] = $item;
            }
        }
        
        return [
            'taichinh'  => $taichinh,
            'tindung'  => $tindung
        ];
    }
}