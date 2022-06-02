<?php
function setting_attributes() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    $attributes = new CP_Attribute;
    $attributes->renderMain();
}

class CP_Attribute {
    public function __construct() {
    }

    public function setupApi() {
        add_action( 'wp_ajax_addattribute', array( $this, 'addAttribute'));
        add_action( 'wp_ajax_removeattribute', array( $this, 'removeAttribute'));
        add_action( 'wp_ajax_updateattribute', array( $this, 'updateAttribute'));
    }

    public function addAttribute() {
        $attribute = isset($_POST['attribute']) ? $_POST['attribute'] : null;
        if ($attribute == null) die;
        try {
            global $wpdb;
            $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_ATTRIBUTES;
            $result = $wpdb->insert( $table_name , array( 'attribute_id' => $attribute['attribute_id'],
                                                            'attribute_name' => $attribute['attribute_name'],
                                                            'attribute_type' => $attribute['attribute_type'],
                                                            'attribute_desc' => $attribute['attribute_desc'] ));
            wp_send_json_success(array(
                "success"=>  $result,
                "errMsg"=> ""
            ));
        } catch (Exception $e) {
            wp_send_json_error(array(
                "success"=>  false,
                "errMsg"=> $e->getMessage()
            ));
        }
        die();
    }

    public function removeAttribute() {
        $attribute_id = isset($_POST['attribute_id']) ? $_POST['attribute_id'] : -1;
        if ($attribute_id == -1) die;
        try {
            global $wpdb;
            $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_ATTRIBUTES;
            $table_group_product_attr = $wpdb->prefix . TB_COMPARE_GROUP_PRODUCT_ATTRIBUTES;
            $wpdb->delete( $table_group_product_attr , array( 'attribute_id' => $attribute_id) );
            $result = $wpdb->delete( $table_name , array( 'attribute_id' => $attribute_id) );
            wp_send_json_success(array(
                "success"=>  $result,
                "errMsg"=> ""
            ));
        } catch (Exception $e) {
            wp_send_json_error(array(
                "success"=>  false,
                "errMsg"=> $e->getMessage()
            ));
        }
        die();
    }

    public function updateAttribute() {
        $attribute = isset($_POST['attribute']) ? $_POST['attribute'] : null;
        if ($attribute == null) die;
        
        try {
            global $wpdb;
            $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_ATTRIBUTES;
            $result = $wpdb->update( $table_name , array('attribute_name' => $attribute['attribute_name'],
                                                        'attribute_type' => $attribute['attribute_type'],
                                                        'attribute_desc' => $attribute['attribute_desc'] ), 
                                                    array('attribute_id' =>$attribute['attribute_id'] ));
            wp_send_json_success(array(
                "success"=> $result,
                "errMsg"=> ""
            ));
        } catch (Exception $e) {
            wp_send_json_error(array(
                "success"=>  false,
                "errMsg"=> $e->getMessage()
            ));
        }
        die();
    }

    protected function getCountListAttributes() {
        global $wpdb;
        $attribute_search = isset($_REQUEST['attribute_name']) ? $_REQUEST['attribute_name'] : '';
        $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_ATTRIBUTES;
        $count_query = "select count(*) from $table_name where attribute_name LIKE '%${attribute_search}%'";
        $num = $wpdb->get_var($count_query);
        return $num;
    }

    protected function getListAttribute() {
        global $wpdb;
        $attribute_search = isset($_REQUEST['attribute_name']) ? $_REQUEST['attribute_name'] : '';
        $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_ATTRIBUTES;
        $table_join = $wpdb->prefix . TB_COMPARE_ATTRIBUTE_TYPE;

        $paged = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 1;
        $itemPerPage = 20;
        $offset = ( $paged * $itemPerPage ) - $itemPerPage;
        $attributes = $wpdb->get_results( "select * from $table_name INNER JOIN $table_join ON $table_name.attribute_type = $table_join.value where attribute_name LIKE '%${attribute_search}%' ORDER BY id LIMIT ${offset}, ${itemPerPage}" );
        foreach ($attributes as $attribute) { ?>
            <tr>
                <td><?php echo $attribute->attribute_id ?></td>
                <td><?php echo $attribute->attribute_name ?></td>
                <td><?php echo $attribute->attribute_desc ?></td>
                <td><?php echo $attribute->name ?></td>
                <td>
                    <a class="edit-attribute" href="#" data-edit='<?php echo json_encode($attribute) ?>'><?php echo __('Edit', TEXT_COMPARE_PRODUCT) ?></a>
                    <a class="remove-attribute" data-remove='<?php echo json_encode($attribute) ?>' href="#"><?php echo __('Delete', TEXT_COMPARE_PRODUCT) ?></a>
                </td>
            </tr>
        <?php }
    }

    protected function getListAttributeTypes() {
        global $wpdb;
        $table_name = $wpdb->prefix . TB_COMPARE_ATTRIBUTE_TYPE;
        $attributetypes = $wpdb->get_results( "select * from $table_name" );
        echo "<option value=''>" . __('Select a attribute type',TEXT_COMPARE_PRODUCT ) . "</option>";
        foreach ($attributetypes as $attributetype) { ?>
            <option value="<?php echo $attributetype->value ?>"><?php echo $attributetype->name ?></option>
        <?php }
    }

    protected function renderPaging() {
        $paged = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 1;
        $page_links = paginate_links(
            array(
                'base'      => add_query_arg( 'paged', '%#%' ),
                'format'    => '',
                'prev_text' => __( '&laquo;' ),
                'next_text' => __( '&raquo;' ),
                'total'     => ceil( $this->getCountListAttributes() / 10 ),
                'current'   => $paged,
            )
        );
        if ( $page_links ) {
            echo "<div class='tablenav-pages'>$page_links</div>";
        }
    }

    public function renderMain() {
        $attribute_search = isset($_REQUEST['attribute_name']) ? $_REQUEST['attribute_name'] : '';
    ?>
        <div class="product-compares">
            <div class="modal" id="modal-product-type">
                <div class="container">
                    <div class="title">
                        <h3 class="add-new-attribute"><?php echo __("Add new attribute", TEXT_COMPARE_PRODUCT);?></h3>
                        <h3 class="edit-attribute"><?php echo __("Edit attribute", TEXT_COMPARE_PRODUCT);?></h3>
                        <h3 class="delete-attribute"><?php echo __("Delete attribute", TEXT_COMPARE_PRODUCT);?></h3>
                        <button class="button" id="close-modal-attribute" type="button"><?php echo __('Close', TEXT_COMPARE_PRODUCT) ?></button>
                    </div>
                    <div class="content">
                        <div class="form-group">
                            <label for="attribute_id"><?php echo __("Attribute id", TEXT_COMPARE_PRODUCT) ?></label>
                            <input type="text" name="attribute_id" id="attribute_id" placeholder="<?php echo __("Attribute id", TEXT_COMPARE_PRODUCT) ?>" value="" />
                        </div>
                        <div class="form-group">
                            <label for="attribute_name"><?php echo __("Attribute name", TEXT_COMPARE_PRODUCT) ?></label>
                            <input type="text" name="attribute_name" id="attribute_name" placeholder="<?php echo __("Attribute name", TEXT_COMPARE_PRODUCT) ?>" value="" />
                        </div>
                        <div class="form-group">
                            <label for="attribute_desc"><?php echo __("Attribute description", TEXT_COMPARE_PRODUCT) ?></label>
                            <textarea name="attribute_desc" id="attribute_desc" rows="4" cols="50"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="attribute_type"><?php echo __("Attribute type", TEXT_COMPARE_PRODUCT) ?></label>
                            <select name="attribute_type" id="attribute_type">
                                <?php $this->getListAttributeTypes() ?>
                            </select>
                        </div>
                        <div class="form-msg">
                            <label for="type_name"><?php echo __("Do you want to delete this attribute?", TEXT_COMPARE_PRODUCT) ?></label>
                        </div>
                        <div class="button-group">
                            <button class="button" type="button" id="save-attribute"><?php echo __("Save", TEXT_COMPARE_PRODUCT) ?></button>
                            <button class="button" type="button" id="delete-attribute"><?php echo __("Yes", TEXT_COMPARE_PRODUCT) ?></button>
                            <button class="button" type="button" id="cancel-attribute"><?php echo __("Cancel", TEXT_COMPARE_PRODUCT) ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lst-attributes">
                <div class="search-attribute">
                    <h3><?php echo __('Attributes', TEXT_COMPARE_PRODUCT) ?></h3>
                    </i><?php echo __('These attributes products will be used to configure product comparisons in product management', TEXT_COMPARE_PRODUCT) ?></i>
                    <p>
                        <a id='add-new-attribute' class="button" href="#"><?php echo __('Add new', TEXT_COMPARE_PRODUCT) ?></a>
                    </p>
                    <div class="search">
                        <form method="get">
                            <input type="hidden" name="page" class="post_page" value="attributes" />
                            <input type="text" name="attribute_name" id="attribute_name" value="<?php echo $attribute_search; ?>" placeholder="<?php echo __("Attribute name", TEXT_COMPARE_PRODUCT) ?>" />
                            <button class="button" type="submit">Search</button>
                        </form>
                    </div>
                </div>
                <div class="lst-result">
                    <div class="group-links">
                        <?php 
                            echo '<a class="button" href="'. admin_url().'admin.php?page=compare_products">Product types</a>';
                            echo '<a class="button" href="'. admin_url().'admin.php?page=group_products">Group Product Compare</a>';
                        ?>
                    </div>
                    <?php $this->renderPaging() ?>
                    <table cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th><?php echo __('ID', TEXT_COMPARE_PRODUCT) ?></th>
                                <th><?php echo __('Attribute name', TEXT_COMPARE_PRODUCT) ?></th>
                                <th><?php echo __('Attribute description', TEXT_COMPARE_PRODUCT) ?></th>
                                <th><?php echo __('Attribute type', TEXT_COMPARE_PRODUCT) ?></th>
                                <th><?php echo __('Actions', TEXT_COMPARE_PRODUCT) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $this->getListAttribute() ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php }
}