<?php
function setting_product_types() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    $productTypes = new ProductTypes;
    $productTypes->renderMain();
}

class ProductTypes {
    public function __construct() {
    }

    public function setupApi() {
        add_action( 'wp_ajax_addproducttype', array( $this, 'addProductType'));
        add_action( 'wp_ajax_removeproducttype', array( $this, 'removeProductType'));
        add_action( 'wp_ajax_updateproducttype', array( $this, 'updateProductType'));
    }

    public function addProductType() {
        $productTypeName = isset($_POST['type_name']) ? $_POST['type_name'] : '';
        if ($productTypeName == '') die;
        try {
            global $wpdb;
            $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_TYPE;
            $result = $wpdb->insert( $table_name , array( 'product_type_name' => $productTypeName) );
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

    public function removeProductType() {
        $productTypeId = isset($_POST['type_id']) ? $_POST['type_id'] : -1;
        if ($productTypeId == -1) die;
        try {
            global $wpdb;
            $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_TYPE;
            $table_group_attribute = $wpdb->prefix . TB_COMPARE_GROUP_ATTRIBUTES;
            $result_update_group_attribute = $wpdb->update($table_group_attribute, array('product_type'=> 1), array('product_type' => $productTypeId));
            // update all group attribute to default
            $result = $wpdb->delete( $table_name , array( 'id' => $productTypeId) );
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

    public function updateProductType() {
        $productType = isset($_POST['product_type']) ? $_POST['product_type'] : null;
        if ($productType == null) die;
        
        try {
            global $wpdb;
            $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_TYPE;
            $result = $wpdb->update( $table_name , array( 'product_type_name' => $productType['product_type_name']), array('id' => $productType['id'] ));
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

    protected function getCountListProductTypes() {
        global $wpdb;
        $product_type_search = isset($_REQUEST['product_type_name']) ? $_REQUEST['product_type_name'] : '';
        $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_TYPE;
        $count_query = "select count(*) from $table_name where  product_type_name <> 'default' AND product_type_name LIKE '%${product_type_search}%'";
        $num = $wpdb->get_var($count_query);
        return $num;
    }

    protected function getListProductTypes() {
        global $wpdb;
        $product_type_search = isset($_REQUEST['product_type_name']) ? $_REQUEST['product_type_name'] : '';
        $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_TYPE;
        $paged = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 1;
        $itemPerPage = 20;
        $offset = ( $paged * $itemPerPage ) - $itemPerPage;
        $producttypes = $wpdb->get_results( "select * from $table_name where  product_type_name <> 'default' AND product_type_name LIKE '%${product_type_search}%' ORDER BY id LIMIT ${offset}, ${itemPerPage}" );
        foreach ($producttypes as $producttype) { ?>
            <tr>
                <td><?php echo $producttype->id ?></td>
                <td><?php echo $producttype->product_type_name ?></td>
                <td>
                    <a class="edit-product-type" href="#" data-edit='<?php echo json_encode($producttype) ?>'><?php echo __('Edit', TEXT_COMPARE_PRODUCT) ?></a>
                    <a class="remove-product-type" data-remove='<?php echo json_encode($producttype) ?>' href="#"><?php echo __('Delete', TEXT_COMPARE_PRODUCT) ?></a>
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
                'total'     => ceil( $this->getCountListProductTypes() / 10 ),
                'current'   => $paged,
            )
        );
        if ( $page_links ) {
            echo "<div class='tablenav-pages'>$page_links</div>";
        }
    }

    public function renderMain() {
        $product_type_search = isset($_REQUEST['product_type_name']) ? $_REQUEST['product_type_name'] : '';
    ?>

        <div class="product-compares">
            <div class="caching-file">
                <button class="button" type="button" id="create-compare-caching">Tạo cache compare sản phẩm</button>
            </div>
            <div class="modal" id="modal-product-type">
                <div class="container">
                    <div class="title">
                        <h3 class="add-new-product-type"><?php echo __("Add new product type", TEXT_COMPARE_PRODUCT);?></h3>
                        <h3 class="edit-product-type"><?php echo __("Edit product type", TEXT_COMPARE_PRODUCT);?></h3>
                        <h3 class="delete-product-type"><?php echo __("Delete product type", TEXT_COMPARE_PRODUCT);?></h3>
                        <button id="close-modal-product-type" class="button" type="button"><?php echo __('Close', TEXT_COMPARE_PRODUCT) ?></button>
                    </div>
                    <div class="content">
                        <div class="form-group" id="product-type-id">
                            <label for="type_id"><?php echo __("Product type id", TEXT_COMPARE_PRODUCT) ?></label>
                            <input type="text" readonly name="" id="type_id" placeholder="<?php echo __("Product type id", TEXT_COMPARE_PRODUCT) ?>" value="" />
                        </div>
                        <div class="form-group">
                            <label for="type_name"><?php echo __("Product type name", TEXT_COMPARE_PRODUCT) ?></label>
                            <input type="text" name="type_name" id="type_name" placeholder="<?php echo __("Product type name", TEXT_COMPARE_PRODUCT) ?>" />
                        </div>
                        <div class="form-msg">
                            <label for="type_name"><?php echo __("Do you want to delete this product type?", TEXT_COMPARE_PRODUCT) ?></label>
                        </div>
                        <div class="button-group">
                            <button class="button" type="button" id="save-product-type"><?php echo __("Save", TEXT_COMPARE_PRODUCT) ?></button>
                            <button class="button" type="button" id="delete-product-type"><?php echo __("Yes", TEXT_COMPARE_PRODUCT) ?></button>
                            <button class="button" type="button" id="cancel-product-type"><?php echo __("Cancel", TEXT_COMPARE_PRODUCT) ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lst-product-types">
                <div class="search-product-type">
                    <h3><?php echo __('Product type', TEXT_COMPARE_PRODUCT) ?></h3>
                    </i><?php echo __('These types of products will be used to configure product comparisons in product management', TEXT_COMPARE_PRODUCT) ?></i>
                    <p>
                        <a id='add-new-product-type' class="button" href="#"><?php echo __('Add new', TEXT_COMPARE_PRODUCT) ?></a>
                    </p>
                    <div class="search">
                        <form method="get">
                            <input type="hidden" name="page" class="post_page" value="compare_products" />
                            <input type="text" name="product_type_name" id="product_type_name" value="<?php echo $product_type_search; ?>" placeholder="<?php echo __("Product types", TEXT_COMPARE_PRODUCT) ?>" />
                            <button class="button" type="submit"><?php echo __("Search", TEXT_COMPARE_PRODUCT) ?></button>
                        </form>
                    </div>
                </div>
                <div class="lst-result">
                    <div class="group-links">
                        <?php 
                            echo '<a class="button" href="'. admin_url().'admin.php?page=attributes">Attributes</a>';
                            echo '<a class="button" href="'. admin_url().'admin.php?page=group_products">Group Product Compare</a>';
                        ?>
                    </div>
                    
                    <?php $this->renderPaging() ?>
                    <table cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th><?php echo __('ID', TEXT_COMPARE_PRODUCT) ?></th>
                                <th><?php echo __('Product type name', TEXT_COMPARE_PRODUCT) ?></th>
                                <th><?php echo __('Actions', TEXT_COMPARE_PRODUCT) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $this->getListProductTypes() ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php }
}