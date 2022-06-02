<?php
function setting_group_product_attributes() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    
    $mappingProductAttribute = new MappingProductAttribute;
    $mappingProductAttribute->renderMain();
}

class MappingProductAttribute {
    public function __construct() {
    }

    public function setupApi() {
        add_action( 'wp_ajax_assignattribute', array( $this, 'assignAttribute'));
        add_action( 'wp_ajax_updategroupproductattribute', array( $this, 'updateAttribute'));
        add_action( 'wp_ajax_removegroupproductattribute', array( $this, 'removeAttribute'));
    }

    public function updateAttribute() {
        $attribute = isset($_POST['attribute']) ? $_POST['attribute'] : null;
        if ($attribute == null) die;
        try {
            global $wpdb;
            $table_name = $wpdb->prefix . TB_COMPARE_GROUP_PRODUCT_ATTRIBUTES;
            $result = $wpdb->update( $table_name , array('display_index' => $attribute['display_index']),
                                                    array( 'attribute_id' => $attribute['attribute_id'],
                                                            'group_id' => $attribute['group_id'] ));
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
        $attribute = isset($_POST['attribute']) ? $_POST['attribute'] : null;
        if ($attribute == null) die;
        try {
            global $wpdb;
            $table_name = $wpdb->prefix . TB_COMPARE_GROUP_PRODUCT_ATTRIBUTES;
            $result = $wpdb->delete( $table_name, array( 'attribute_id' => $attribute['attribute_id'],
                                                            'group_id' => $attribute['group_id'] ));
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

    public function assignAttribute() {
        $attribute = isset($_POST['attribute']) ? $_POST['attribute'] : null;
        if ($attribute == null) die;
        try {
            global $wpdb;
            $table_name = $wpdb->prefix . TB_COMPARE_GROUP_PRODUCT_ATTRIBUTES;
            $result = $wpdb->insert( $table_name , array( 'attribute_id' => $attribute['attribute_id'],
                                                            'group_id' => $attribute['group_id'],
                                                            'display_index' => $attribute['display_index']));
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

    protected function checkGroupExist($group_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . TB_COMPARE_GROUP_ATTRIBUTES;
        $count_query = "select count(*) from $table_name where group_id = '${group_id}'";
        $num = $wpdb->get_var($count_query);
        return $num;
    }

    protected function getListGroupProductAttribute($group_id) {
        global $wpdb;
        $attribute_search = isset($_REQUEST['attribute_name']) ? $_REQUEST['attribute_name'] : '';
        $table_name = $wpdb->prefix . TB_COMPARE_GROUP_PRODUCT_ATTRIBUTES;
        $table_join = $wpdb->prefix . TB_COMPARE_PRODUCT_ATTRIBUTES;
        $table_join_type = $wpdb->prefix . TB_COMPARE_ATTRIBUTE_TYPE;

        $attributes = $wpdb->get_results( "select * from $table_name INNER JOIN $table_join ON $table_name.attribute_id = $table_join.attribute_id 
                                            INNER JOIN $table_join_type  ON $table_join.attribute_type = $table_join_type.value 
                                            where $table_name.group_id = '$group_id' AND $table_join.attribute_name LIKE '%${attribute_search}%' ORDER BY $table_name.display_index ");
        foreach ($attributes as $attribute) { ?>
            <tr>
                <td><?php echo $attribute->attribute_id ?></td>
                <td><?php echo $attribute->attribute_name ?></td>
                <td><?php echo $attribute->attribute_desc ?></td>
                <td><?php echo $attribute->name ?></td>
                <td><?php echo $attribute->display_index ?></td>
                <td>
                    <a class="edit-group-product-attribute" href="#" data-edit='<?php echo json_encode($attribute) ?>'><?php echo __('Edit', TEXT_COMPARE_PRODUCT) ?></a>
                    <a class="remove-group-product-attribute" data-remove='<?php echo json_encode($attribute) ?>' href="#"><?php echo __('Delete', TEXT_COMPARE_PRODUCT) ?></a>
                </td>
            </tr>
        <?php }
    }
    
    public function renderMain() {
        $group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : '';
        $attribute_name = isset($_REQUEST['attribute_name']) ? $_REQUEST['attribute_name'] : '';
        if ($group_id == '' || $this->checkGroupExist($group_id) <=0 ) {
            wp_redirect( admin_url( 'admin.php?page=group_products' ) );
        } ?>
        <div class="lst-group-product-attributes">
        <div class="modal" id="modal-product-type">
            <div class="container">
                    <div class="title">
                        <h3 class="edit-attribute"><?php echo __("Edit group product attribute", TEXT_COMPARE_PRODUCT);?></h3>
                        <h3 class="delete-attribute"><?php echo __("Delete group product attribute", TEXT_COMPARE_PRODUCT);?></h3>
                        <button id="close-modal-group-product-attribute" type="button"><?php echo __('Close', TEXT_COMPARE_PRODUCT) ?></button>
                    </div>
                    <div class="content">
                        <div class="form-group">
                            <label for="attribute_id"><?php echo __("Attribute id", TEXT_COMPARE_PRODUCT) ?></label>
                            <input type="text" readonly name="attribute_id" id="attribute_id" placeholder="<?php echo __("Attribute id", TEXT_COMPARE_PRODUCT) ?>" value="" />
                        </div>
                        <div class="form-group">
                            <label for="display_index"><?php echo __("Display Index", TEXT_COMPARE_PRODUCT) ?></label>
                            <input type="number" min="0" name="display_index" id="display_index" placeholder="<?php echo __("Display Index", TEXT_COMPARE_PRODUCT) ?>" value="" />
                        </div>
                        <div class="form-msg">
                            <label for="type_name"><?php echo __("Do you want to delete this product attribute?", TEXT_COMPARE_PRODUCT) ?></label>
                        </div>
                        <div class="button-group">
                            <input type="button" id="save-group-product-attribute" value="<?php echo __("Save", TEXT_COMPARE_PRODUCT) ?>"/>
                            <input type="button" id="delete-group-product-attribute" value="<?php echo __("Yes", TEXT_COMPARE_PRODUCT) ?>"/>
                            <input type="button" id="cancel-group-product-attribute" value="<?php echo __("Cancel", TEXT_COMPARE_PRODUCT) ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="search-attribute">
                <h3><?php echo __('Assign Attributes: ' . $group_id, TEXT_COMPARE_PRODUCT) ?></h3>
                </i><?php echo __('These attributes products will be used to configure product comparisons in product management', TEXT_COMPARE_PRODUCT) ?></i>
                <div class="add-new-attr">
                    <div class="input-group">
                        <input type="hidden" name="attribute_id_selected" id="attribute_id_selected" placeholder="<?php echo __("Attribute ID", TEXT_COMPARE_PRODUCT) ?>"/>
                        <input type="text" list="list_attribute" id="list_attribute_id_select" placeholder="Chọn attribute"/>
                        <datalist id="list_attribute">
                        <?php
                            $attrbutes = GroupProductAttribute::getListAttributeOfGroup($_GET['group_id']);
                            $json_attrbutes = [];

                            foreach ($attrbutes as $attribute) {
                                echo '<option value="'.$attribute->attribute_id.'">';

                                $tmp = [];
                                $tmp['attribute_id'] = $attribute->attribute_id;
                                $tmp['attribute_name'] = $attribute->attribute_name;
                                $tmp['attribute_desc'] = $attribute->attribute_desc;
                                $tmp['type'] = $attribute->name;

                                $json_attrbutes[] = $tmp;
                            }
                        ?>
                        </datalist>
                        <script>
                            var json_attrbutes = <?php echo json_encode($json_attrbutes); ?>;
                        </script>
                        <!-- <a class="button" id='add-new-group-product-attribute' href="#">...</a> -->
                    </div>
                    <div class="input-group">
                        <input type="number" min="0" name="display_index_table" id="display_index_table" value="" placeholder="<?php echo __("Display order", TEXT_COMPARE_PRODUCT) ?>"/>
                    </div>
                    <button class="button" type="button" value="add" id="assign-attribute">
                        <?php echo __("Add", TEXT_COMPARE_PRODUCT) ?>
                    </button>
                </div>
                <div class="search">
                    <form method="get">
                        <input type="hidden" name="page" class="post_page" value="group_product_attributes" />
                        <input type="hidden" name="group_id" class="post_page" value="<?php echo $group_id; ?>" />
                        <input type="text" name="attribute_name" id="attribute_name" value="<?php echo $attribute_name; ?>" placeholder="<?php echo __("Attribute name", TEXT_COMPARE_PRODUCT) ?>" />
                        <button class="button" type="submit">Search</button>
                    </form>
                </div>
            </div>
            <div class="lst-result">
                <div class="group-links">
                    <?php 
                        echo '<a class="button" href="'. admin_url().'admin.php?page=compare_products">Product types</a>';
                        echo '<a class="button" href="'. admin_url().'admin.php?page=attributes">Attributes</a>';
                        echo '<a class="button" href="'. admin_url().'admin.php?page=group_products">Group Product Compare</a>';
                    ?>
                </div>
                <table cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th><?php echo __('ID', TEXT_COMPARE_PRODUCT) ?></th>
                            <th><?php echo __('Attribute name', TEXT_COMPARE_PRODUCT) ?></th>
                            <th><?php echo __('Attribute description', TEXT_COMPARE_PRODUCT) ?></th>
                            <th><?php echo __('Attribute type', TEXT_COMPARE_PRODUCT) ?></th>
                            <th><?php echo __('Display order', TEXT_COMPARE_PRODUCT) ?></th>
                            <th><?php echo __('Actions', TEXT_COMPARE_PRODUCT) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $this->getListGroupProductAttribute($group_id); ?>
                    </tbody>
                </table>
            </div>
            <script>
                var adminAttributeUrl = '<?php echo admin_url( 'admin.php?page=assign_attributes&group_id='.$group_id ) ?>';
                var groupId = '<?php echo $group_id; ?>';
            </script>
        </div>
    <?php }
}