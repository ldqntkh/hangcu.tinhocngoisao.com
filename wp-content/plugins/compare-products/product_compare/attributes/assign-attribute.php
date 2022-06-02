<?php
function setting_assign_attributes() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    $groupProductAttribute = new GroupProductAttribute;
    $groupProductAttribute->renderMain();
}

class GroupProductAttribute {
    public function __construct() {

    }

    static function getListAttributeOfGroup($group_id) {
        global $wpdb;
        $attribute_search = isset($_REQUEST['attribute_name']) ? $_REQUEST['attribute_name'] : '';
        $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_ATTRIBUTES;
        $table_join = $wpdb->prefix . TB_COMPARE_ATTRIBUTE_TYPE;
        $table_mapping_attribute = $wpdb->prefix . TB_COMPARE_GROUP_PRODUCT_ATTRIBUTES;

        $attributes = $wpdb->get_results( "select * from $table_name INNER JOIN $table_join ON $table_name.attribute_type = $table_join.value where attribute_name LIKE '%${attribute_search}%' 
                                        AND $table_name.attribute_id NOT IN (select attribute_id from $table_mapping_attribute where group_id = '$group_id' )
                                        ORDER BY id" );

        return $attributes;
    }

    protected function getListAttribute($group_id) {
        global $wpdb;
        $attribute_search = isset($_REQUEST['attribute_name']) ? $_REQUEST['attribute_name'] : '';
        $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_ATTRIBUTES;
        $table_join = $wpdb->prefix . TB_COMPARE_ATTRIBUTE_TYPE;
        $table_mapping_attribute = $wpdb->prefix . TB_COMPARE_GROUP_PRODUCT_ATTRIBUTES;

        $attributes = $wpdb->get_results( "select * from $table_name INNER JOIN $table_join ON $table_name.attribute_type = $table_join.value where attribute_name LIKE '%${attribute_search}%' 
                                        AND $table_name.attribute_id NOT IN (select attribute_id from $table_mapping_attribute where group_id = '$group_id' )
                                        ORDER BY id" );
        foreach ($attributes as $attribute) { ?>
            <tr>
                <td><?php echo $attribute->attribute_id ?></td>
                <td><?php echo $attribute->attribute_name ?></td>
                <td><?php echo $attribute->attribute_desc ?></td>
                <td><?php echo $attribute->name ?></td>
                <td>
                    <a class="select-attribute" href="#" data-select='<?php echo json_encode($attribute) ?>'><?php echo __('Select', TEXT_COMPARE_PRODUCT) ?></a>
                </td>
            </tr>
        <?php }
    }

    protected function checkGroupExist($group_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . TB_COMPARE_GROUP_ATTRIBUTES;
        $count_query = "select count(*) from $table_name where group_id = '${group_id}'";
        $num = $wpdb->get_var($count_query);
        return $num;
    }

    protected function getCountListAttributes() {
        global $wpdb;
        $attribute_search = isset($_REQUEST['attribute_name']) ? $_REQUEST['attribute_name'] : '';
        $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_ATTRIBUTES;
        $count_query = "select count(*) from $table_name where attribute_name LIKE '%${attribute_search}%'";
        $num = $wpdb->get_var($count_query);
        return $num;
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
        $group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : '';
        $attribute_search = isset($_REQUEST['attribute_name']) ? $_REQUEST['attribute_name'] : '';
        if ($group_id == '' || $this->checkGroupExist($group_id) <= 0 ) {
            wp_redirect( admin_url( 'admin.php?page=group_products' ) );
        } ?>
    
        <div class="product-compares">
            <div class="lst-attributes">
                <div class="search-attribute">
                    <h3><?php echo __('Attributes', TEXT_COMPARE_PRODUCT) ?></h3>
                    <!-- <div class="search">
                        <form method="get">
                            <input type="hidden" name="page" class="post_page" value="assign_attributes" />
                            <input type="text" name="attribute_name" id="attribute_name" value="<?php echo $attribute_search; ?>" placeholder="<?php echo __("Attribute name", TEXT_COMPARE_PRODUCT) ?>" />
                            <input type="submit" value="Search" />
                        </form>
                    </div> -->
                </div>
                <div class="lst-result">
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
                            <?php $this->getListAttribute($group_id) ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php }
}
