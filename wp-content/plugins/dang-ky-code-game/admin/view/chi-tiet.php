<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

  $dkcodegame_id = isset( $_GET['dkcodegame_id'] ) ? intval( $_GET['dkcodegame_id'] ) : -1;
  
  if( $dkcodegame_id == -1 ) {
      wp_redirect('/wp-admin/admin.php?page=danh-sach-dang-ky-code-game');
  }

  global $wpdb, $table_prefix;
  $table_dang_ky_code_game = $table_prefix . 'dang_ky_code_game';
  
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = '0';
    if( isset( $_POST['dkcodegame_status'] ) ) {
      if( $_POST['dkcodegame_status'] == '1' ) {
        $status = '1';
      } else {
        $status = '2';
      }
    }
    // update vào db
    $wpdb->update($table_dang_ky_code_game, array( 'status' => $status ), array( "id" => $dkcodegame_id ));
  }

  $sql = "SELECT * FROM $table_dang_ky_code_game
            WHERE id = '$dkcodegame_id'";
  
  $result = $wpdb->get_results($sql);

  if( !$result || count( $result ) == 0 ) {
    wp_redirect('/wp-admin/admin.php?page=danh-sach-dang-ky-code-game');
  }
  $result = $result[0];
  
?>
<h2><a href="<?php echo admin_url('admin.php?page=danh-sach-dang-ky-code-game') ?>"><?php _e( 'Danh sách đăng ký code game', "ycbh" ) ?></a></h2>
<h3><?php _e('Chi tiết đăng ký code game', "ycbh") ?></h3>
<div class="lst-product-container">
    <div class="container" id="list-barcode">
        <p><?php _e("Họ tên khách hàng:", "ycbh") ?> <strong><?php echo $result->fullname ?></strong></p>
        <p><?php _e("Số điện thoại:", "ycbh") ?> <strong><?php echo $result->phone_number ?></strong></p>
        <p><?php _e("Email:", "ycbh") ?> <strong><?php echo $result->email ?></strong></p>
        <p><?php _e("Địa chỉ:", "ycbh") ?> <strong><?php echo $result->address ?></strong></p>
        <p><?php _e("Công ty:", "ycbh") ?> <strong><?php echo $result->company_name ?></strong></p>
        <p><?php _e("Thông tin mô tả:", "ycbh") ?> 
          <strong>
            <?php 
              $file = json_decode($result->file_data, true);
              $file = $file[0];
            ?>
            <a href="<?= $file['url'] ?>" target="_blank">CLICK HERE</a>
          </strong>
        </p>
        <p style="margin-bottom: 0"><?php _e("Nhu cầu:", "ycbh") ?></p>
        <p style="padding: 0 10px; margin-top: 5px"><?php echo $result->description ?></p>
        <form method="post">
            <?php if( $result->status != "2" ) : ?>
              <p> 
                <?php _e("Hoàn tất?", "ycbh") ?>
                <input type="checkbox" value="finish" name="dkcodegame_status"  <?php if( $result->status == "1" ) echo 'checked' ?> />
              </p>
            <?php endif; ?>
           
            <?php if( $result->status != "1" ) : ?>
              <p> 
                <?php _e("Hủy bỏ?", "ycbh") ?>
                <input type="checkbox" value="cancel" name="dkcodegame_status"  <?php if( $result->status == "2" ) echo 'checked' ?> />
              </p>
            <?php endif; ?>
            <p>
                <button class="button"><?php _e("Cập nhật", "ycbh") ?></button>
            </p>
        </form>
    </div>

    <script>
        const ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
    </script>
</div>