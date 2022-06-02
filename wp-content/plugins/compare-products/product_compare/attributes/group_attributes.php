<?php
function setting_group_product() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    
    $groupAttribute = new GroupAttribute;
    $groupAttribute->renderMain();
}

class GroupAttribute {
    public function __construct() {
    }

    public function setupApi() {
        add_action( 'wp_ajax_addgroupattribute', array( $this, 'addGroupAttribute'));
        add_action( 'wp_ajax_updategroupattribute', array( $this, 'updateGroupAttribute'));
        add_action( 'wp_ajax_removegroupattribute', array( $this, 'removeGroupAttribute'));
    }

    public function addGroupAttribute() {
        $group_attribute = isset($_POST['group_attribute']) ? $_POST['group_attribute'] : null;
        if ($group_attribute == null) die;
        try {
            global $wpdb;
            $table_name = $wpdb->prefix . TB_COMPARE_GROUP_ATTRIBUTES;
            $result = $wpdb->insert( $table_name , array( 'group_id' => $group_attribute['group_id'].'-'. $group_attribute['product_type'],
                                                            'group_name' => $group_attribute['group_name'],
                                                            'group_desc' => $group_attribute['group_desc'],
                                                            'product_type' => $group_attribute['product_type'],
                                                            'display_index' => $group_attribute['display_index'] ));
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

    public function updateGroupAttribute() {
        $group_attribute = isset($_POST['group_attribute']) ? $_POST['group_attribute'] : null;
        if ($group_attribute == null) die;
        try {
            global $wpdb;
            $table_name = $wpdb->prefix . TB_COMPARE_GROUP_ATTRIBUTES;
            $result = $wpdb->update( $table_name , array( 'group_name' => $group_attribute['group_name'],
                                                            'group_desc' => $group_attribute['group_desc'],
                                                            'product_type' => $group_attribute['product_type'],
                                                            'display_index' => $group_attribute['display_index'] ),
                                                    array('group_id' => $group_attribute['group_id']));
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

    public function removeGroupAttribute() {
        $group_data = isset($_POST['data']) ? $_POST['data'] : -1;
        if ($group_data == -1) die;
        try {
            global $wpdb;
            $table_name = $wpdb->prefix . TB_COMPARE_GROUP_ATTRIBUTES;

            $table_group_product_attr = $wpdb->prefix . TB_COMPARE_GROUP_PRODUCT_ATTRIBUTES;
            $wpdb->delete( $table_group_product_attr , array( 'group_id' => $group_data['group_id']) );

            $result = $wpdb->delete( $table_name , array( 'group_id' => $group_data['group_id'], "product_type" => $group_data['product_type']) );
            // remove on table mapping
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

    protected function getCountListGroupAttributes() {
        global $wpdb;
        $group_search = isset($_REQUEST['group_name']) ? $_REQUEST['group_name'] : '';
        $table_name = $wpdb->prefix . TB_COMPARE_GROUP_ATTRIBUTES;
        $count_query = "select count(*) from $table_name where group_name LIKE '%${group_search}%'";
        $num = $wpdb->get_var($count_query);
        return $num;
    }

    protected function getListProductTypes() {
        global $wpdb;
        $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_TYPE;
        $producttypes = $wpdb->get_results( "select * from $table_name ORDER BY is_default DESC" );
        foreach ($producttypes as $producttype) { ?>
            <option value="<?php echo $producttype->id ?>"><?php echo $producttype->product_type_name ?></option>
        <?php }
    }

    protected function getListGroupAttribute() {
        global $wpdb;
        $group_search = isset($_REQUEST['group_name']) ? $_REQUEST['group_name'] : '';
        $table_name = $wpdb->prefix . TB_COMPARE_GROUP_ATTRIBUTES;
        $table_product_type = $wpdb->prefix . TB_COMPARE_PRODUCT_TYPE;
        $table_group_product_attributes = $wpdb->prefix . TB_COMPARE_GROUP_PRODUCT_ATTRIBUTES;

        $paged = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 1;
        $itemPerPage = 20;
        $offset = ( $paged * $itemPerPage ) - $itemPerPage;
        $groups = $wpdb->get_results( "select *, (select count(*) from $table_group_product_attributes where $table_name.group_id = $table_group_product_attributes.group_id) as Attributes 
                            from $table_name INNER JOIN $table_product_type ON $table_name.product_type = $table_product_type.id 
                            where group_name LIKE '%${group_search}%' ORDER BY id LIMIT ${offset}, ${itemPerPage}" );
        foreach ($groups as $group) { ?>
            <tr>
                <td><?php echo $group->group_id ?></td>
                <td><?php echo $group->group_name ?></td>
                <td><?php echo $group->group_desc ?></td>
                <td><?php echo $group->product_type_name ?></td>
                <td><?php echo $group->display_index ?></td>
                <td>
                    <span><?php echo $group->Attributes ?></span>
                    <a href="<?php echo admin_url('admin.php?page=group_product_attributes&group_id='.$group->group_id) ?>" class="add"><?php echo __('Edit', TEXT_COMPARE_PRODUCT) ?></a>
                </td>
                <td>
                    <a class="edit-group-attribute" href="#" data-edit='<?php echo json_encode($group) ?>'><?php echo __('Edit', TEXT_COMPARE_PRODUCT) ?></a>
                    <a class="remove-group-attribute" data-remove='<?php echo json_encode($group) ?>' href="#"><?php echo __('Delete', TEXT_COMPARE_PRODUCT) ?></a>
                </td>
            </tr>
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
                'total'     => ceil( $this->getCountListGroupAttributes() / 10 ),
                'current'   => $paged,
            )
        );
        if ( $page_links ) {
            echo "<div class='tablenav-pages'>$page_links</div>";
        }
    }

    public function renderMain() {
        $group_search = isset($_REQUEST['group_name']) ? $_REQUEST['group_name'] : '';
    ?>
        <div class="product-compares">
            <div class="modal" id="modal-product-type">
                <div class="container">
                    <div class="title">
                        <h3 class="add-new-group-attribute"><?php echo __("Add new group attribute", TEXT_COMPARE_PRODUCT);?></h3>
                        <h3 class="edit-group-attribute"><?php echo __("Edit group attribute", TEXT_COMPARE_PRODUCT);?></h3>
                        <h3 class="delete-group-attribute"><?php echo __("Delete group attribute", TEXT_COMPARE_PRODUCT);?></h3>
                        <button class="button" id="close-modal-group-attribute" type="button"><?php echo __('Close', TEXT_COMPARE_PRODUCT) ?></button>
                    </div>
                    <div class="content">
                        <div class="form-group">
                            <label for="group_id"><?php echo __("Group id", TEXT_COMPARE_PRODUCT) ?></label>
                            <input type="text" name="group_id" id="group_id" placeholder="<?php echo __("Group id", TEXT_COMPARE_PRODUCT) ?>" value="" />
                        </div>
                        <div class="form-group">
                            <label for="group_name"><?php echo __("Group name", TEXT_COMPARE_PRODUCT) ?></label>
                            <input type="text" name="group_name" id="group_name" placeholder="<?php echo __("Group name", TEXT_COMPARE_PRODUCT) ?>" value="" />
                        </div>
                        <div class="form-group">
                            <label for="group_desc"><?php echo __("Group description", TEXT_COMPARE_PRODUCT) ?></label>
                            <textarea name="group_desc" id="group_desc" rows="4" cols="50"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="display_index"><?php echo __("Display Index", TEXT_COMPARE_PRODUCT) ?></label>
                            <input type="number" min="0" name="display_index" id="display_index" placeholder="<?php echo __("Display Index", TEXT_COMPARE_PRODUCT) ?>" value="" />
                        </div>
                        <div class="form-group">
                            <label for="product_type"><?php echo __("Product type", TEXT_COMPARE_PRODUCT) ?></label>
                            <select name="product_type" id="product_type">
                                <?php $this->getListProductTypes() ?>
                            </select>
                        </div>
                        <div class="form-msg">
                            <label for="type_name"><?php echo __("Do you want to delete this group attribute?", TEXT_COMPARE_PRODUCT) ?></label>
                        </div>
                        <div class="button-group">
                            <button class="button" type="button" id="save-group-attribute"><?php echo __("Save", TEXT_COMPARE_PRODUCT) ?></button>
                            <button class="button" type="button" id="delete-group-attribute"><?php echo __("Yes", TEXT_COMPARE_PRODUCT) ?></button>
                            <button class="button" type="button" id="cancel-group-attribute"><?php echo __("Cancel", TEXT_COMPARE_PRODUCT) ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lst-group-attributes">
                <div class="search-attribute">
                    <h3><?php echo __('Group Attributes', TEXT_COMPARE_PRODUCT) ?></h3>
                    </i><?php echo __('These attributes products will be used to configure product comparisons in product management', TEXT_COMPARE_PRODUCT) ?></i>
                    <p>
                        <a class="button" id='add-new-group-attribute' href="#"><?php echo __('Add new', TEXT_COMPARE_PRODUCT) ?></a>
                    </p>
                    <div class="search">
                        <form method="get">
                            <input type="hidden" name="page" class="post_page" value="group_products" />
                            <input type="text" name="group_name" id="group_name" value="<?php echo $group_search; ?>" placeholder="<?php echo __("Group attribute name", TEXT_COMPARE_PRODUCT) ?>" />
                            <button class="button" type="submit">Search</button>
                        </form>
                    </div>
                </div>
                <div class="lst-result">
                    <div class="group-links">
                        <?php 
                            echo '<a class="button" href="'. admin_url().'admin.php?page=compare_products">Product types</a>';
                            echo '<a class="button" href="'. admin_url().'admin.php?page=attributes">Attributes</a>';
                        ?>
                    </div>
                    <?php $this->renderPaging() ?>
                    <table cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th><?php echo __('ID', TEXT_COMPARE_PRODUCT) ?></th>
                                <th><?php echo __('Group name', TEXT_COMPARE_PRODUCT) ?></th>
                                <th><?php echo __('Group description', TEXT_COMPARE_PRODUCT) ?></th>
                                <th><?php echo __('Product type', TEXT_COMPARE_PRODUCT) ?></th>
                                <th><?php echo __('Display order', TEXT_COMPARE_PRODUCT) ?></th>
                                <th><?php echo __('Attributes', TEXT_COMPARE_PRODUCT) ?></th>
                                <th><?php echo __('Actions', TEXT_COMPARE_PRODUCT) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $this->getListGroupAttribute() ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php }
}