<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

  $page_i = isset( $_GET['page_i'] ) ? intval( $_GET['page_i'] ) : 1;
  $status = isset( $_GET['status'] ) ? $_GET['status'] : 'all';
  $search = isset( $_GET['search'] ) ? $_GET['search'] : '';
//   $from_date = isset( $_GET['from_date'] ) ? $_GET['from_date'] : '';
//   $to_date = isset( $_GET['to_date'] ) ? $_GET['to_date'] : '';

  global $wpdb, $table_prefix;
  $table_dang_ky_code_game = $table_prefix . 'dang_ky_code_game';

  $ofset = $page_i > 1 ? ($page_i - 1) * 30 : 0;

  $sql = "SELECT * FROM $table_dang_ky_code_game
            WHERE (fullname LIKE '%$search%' OR phone_number LIKE '%$search%' OR email LIKE '%$search%') ";
  $sql_count = "SELECT count(*) as `total` FROM $table_dang_ky_code_game
            WHERE (fullname LIKE '%$search%' OR phone_number LIKE '%$search%' OR email LIKE '%$search%') ";
  if( $status == '1' ) {
    $sql .= " AND `status` = 1";
    $sql_count .= " AND `status` = 1";
  } elseif( $status == '0' ) {
    $sql .= " AND `status` = 0";
    $sql_count .= " AND `status` = 0";
  } elseif( $status == '2' ) {
    $sql .= " AND `status` = 2";
    $sql_count .= " AND `status` = 2";
  } 
  
  // check count
  $total = 0;
  $result_count = $wpdb->get_results($sql_count);
  if( $result_count && count($result_count) > 0 ) {
    $total = $result_count[0]->total;
  }

  $sql .= " ORDER BY created_at DESC LIMIT $ofset, 30";
  
  $result = $wpdb->get_results($sql);
?>
<h1><?php _e('Quản lý yêu cầu bảo hành', "ycbh") ?></h1>
<div class="lst-product-container">
  <div class="container">
      <p><?php _e( 'Danh sách đăng ký code game.', "ycbh" ) ?></p>
  </div>
  <div class="container" id="list-barcode">
        <p>
            <a class="promotion-search <?php $status == 'all' ? "active" : '' ?>" href="<?php echo admin_url('admin.php?page=danh-sach-dang-ky-code-game&status=all') ?>" data-search="<?php echo 'all' ?>"><?php _e("Tất cả", "ycbh") ?></a> |
            <a class="promotion-search <?php $status == '1' ? "active" : '' ?>" href="<?php echo admin_url('admin.php?page=danh-sach-dang-ky-code-game&status=1') ?>" data-search="<?php echo '1' ?>"><?php _e("Hoàn tất", "ycbh") ?></a> |
            <a class="promotion-search <?php $status == '0' ? "active" : '' ?>" href="<?php echo admin_url('admin.php?page=danh-sach-dang-ky-code-game&status=0') ?>" data-search="<?php echo '0' ?>"><?php _e("Đang xử lý", "ycbh") ?></a> |
            <a class="promotion-search <?php $status == '2' ? "active" : '' ?>" href="<?php echo admin_url('admin.php?page=danh-sach-dang-ky-code-game&status=2') ?>" data-search="<?php echo '0' ?>"><?php _e("Hủy bỏ", "ycbh") ?></a> 
        </p>
        <p>
          <strong>Trang: </strong>
          <?php 
            if( $total > 0 ) {
              $total_page = $total / 30;
              if( $total % 30 > 0 ) $total_page ++;
              for( $p = 1; $p <= $total_page; $p++ ) { ?> 
                <a class="promotion-page <?php $page_i == $p ? "active" : '' ?>" href="<?php echo admin_url('admin.php?page=danh-sach-dang-ky-code-game&status='.$status.'&page_i='.$p) ?>" ><?php echo $p; ?></a>
              <?php }
            }
          ?>
        </p>
    <table class="wp-list-table widefat fixed striped tags ui-sortable">
      <thead>
        <tr>
            <th scope="col" class="manage-column column-thumb"><strong><?php _e('Id', "ycbh") ?></strong></th>
            <th scope="col" class="manage-column column-thumb"><strong><?php _e('Tên khách hàng', "ycbh") ?></strong></th>
            <th scope="col" class="manage-column column-thumb"><strong><?php _e('Số điện thoại', "ycbh") ?></strong></th>
            <th scope="col" class="manage-column column-thumb"><strong><?php _e('Email', "ycbh") ?></strong></th>
            <th scope="col" class="manage-column column-thumb"><strong><?php _e('Địa chỉ', "ycbh") ?></strong></th>
            <th scope="col" class="manage-column column-thumb"><strong><?php _e('Công ty', "ycbh") ?></strong></th>
            <th scope="col" class="manage-column column-thumb"><strong><?php _e('Trạng thái', "ycbh") ?></strong></th>
        </tr>
      </thead>
            <tbody id="the-list">
                <?php
                
                  if( $result && count( $result ) > 0 ) {
                    for( $i = 0; $i < count( $result ); $i++ ) { 
                      $status = 'Đang xử lý' ;
                      if( $result[$i]->status == '1' ) {
                        $status = 'Hoàn tất' ;
                      } elseif( $result[$i]->status == '2' ) {
                        $status = 'Hủy bỏ' ;
                      }
                    ?>
                      <tr>
                        <td scope="col" class="manage-column column-thumb">
                          <a href="<?php echo admin_url('admin.php?page=danh-sach-dang-ky-code-game&type=view&dkcodegame_id='.$result[$i]->id ) ?>">
                            <?php echo $result[$i]->id ?></td>
                          </a>
                        <td scope="col" class="manage-column column-thumb"><?php echo $result[$i]->fullname ?></td>
                        <td scope="col" class="manage-column column-thumb"><?php echo $result[$i]->phone_number ?></td>
                        <td scope="col" class="manage-column column-thumb"><?php echo $result[$i]->email ?></td>
                        <td scope="col" class="manage-column column-thumb"><?php echo $result[$i]->address ?></td>
                        <td scope="col" class="manage-column column-thumb"><?php echo $result[$i]->company_name ?></td>
                        <td scope="col" class="manage-column column-thumb"><?php echo $status ?></td>
                      </tr>
                    <?php }
                  }
                
                ?>
            </tbody>
    </table>
  </div>

  <script>
    const ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
  </script>
</div>