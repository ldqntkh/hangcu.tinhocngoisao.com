<?php
    add_action( 'admin_init', 'create_icon_init' );
    function create_icon_init() {
        // config global
        register_setting( CREATE_ICON, CREATE_ICON );
        add_settings_section( CREATE_ICON, 'Tạo danh sách icon', 'create_icon_title', CREATE_ICON );
        // add_settings_field( 'create_icon_template', '', 'create_icon_template', CREATE_ICON, CREATE_ICON );

        add_action('wp_ajax_saveconfigicon','SaveConfigIcons');
    }

    add_action( 'admin_enqueue_scripts', 'load_icon_wp_media_files' );
    add_action( 'admin_enqueue_scripts', 'register_icon_header_script' );

    
    add_action( 'wp_head', 'hangcu_custom_icon' );

    function hangcu_custom_icon() {
      $key = 'custom_css_data_icon';
      if ( get_option( $key ) ) : 
        $data =  get_option( $key );
        $classes = json_decode( $data, true );
        
        if ($classes !== null && json_last_error() === JSON_ERROR_NONE) : 
          $classes = $classes['icons'];
        ?>
          <style>
            <?php
              foreach( $classes as $key => $class ) : 
                
                echo '.'.$key.'{ '. $class .' }';
              endforeach;
            ?>
          </style>
        <?php endif;
      endif;
    }
    
    function load_icon_wp_media_files() {
        // WordPress library
        wp_enqueue_media();
    }

    function SaveConfigIcons() {
      /**
       * [
       *  url: '',
       *  data: []
       * ]
       */
      if ( !empty( $_POST['dataicon'] ) ) {
        $key = 'custom_css_data_icon';
        $data =  json_encode($_POST['dataicon']);

        if ( !get_option( $key ) ) {
          add_option( $key, $data, false);
        } else {
          update_option( $key, $data, false);
        }

        wp_send_json_success('Cập nhật danh sách icon thành công!');
      } else {
        wp_send_json_error('data not found!');
      }
    }

    function register_icon_header_script() {
        wp_register_script( 'custom_icon_script', plugins_url('custom-preference/assets/js/admin-custom-icon.js'), '', '', true );
        wp_enqueue_script( 'custom_icon_script' );
    }

    function create_icon_title() {
        echo '<p>Tạo icon từ hình ảnh</p>';
        create_icon_template();
    }
    
    function create_icon_template() { ?>
      <div class="header-control">
        <div class="left-content">
          <input type="hidden" id="image-icon-selected" name="image-icon-selected" class="image-icon-selected" value="">
          <input type="button" name="select-icon-btn" class="button-secondary select-icon-btn" value="Chọn ảnh">
          <div class="image-size">
            <input type="number" min="1" max="1000" id="image-icon-size" name="image-icon-size" class="image-icon-size" value="200" placeholder="Tỉ lệ (%) ảnh"/>
            <input type="button" name="btn-set-size" class="btn-set-size button-secondary" value="Thiết lập kích thước ảnh">
            <p id="real-size"></p>
          </div>

          <div class="icon-size">
            <label>Chiều rộng icon</label>
            <input type="number" min="1" max="1000" id="image-icon-width" name="image-icon-width" class="image-icon-width" value="20" placeholder="chiều rộng icon"/>
            <label>Chiều cao icon</label>
            <input type="number" min="1" max="1000" id="image-icon-height" name="image-icon-height" class="image-icon-height" value="20" placeholder="chiều cao icon"/>
          </div>
        </div>
        <div class="right-content">
          <p><strong>Hướng dẫn:</strong> Chọn ảnh từ thư viện mà bạn muốn tạo icon (Hỗ trợ định dạng png | jpeg). Sau khi chọn ảnh xong thì mặc định hệ thống
            sẽ lấy theo kích thước chuẩn của ảnh. Bạn cũng có thể thay đổi kích thước ảnh bằng cách thay đổi giá trị trong phần "Thiết lập kích thước ảnh" và click button bên cạnh để
            thiết lập kích thước mới.</p>
          <p>Phần chiều rộng/cao của icon bạn có thể nhập giá trị tùy ý. Khi rê chuột lên ảnh bên dưới, bạn sẽ thấy một ô vuông màu đen với kích thước bằng với
            kích thước mà bạn đã thiết lập cho icon. Bạn chỉ cần rê chuột đúng vào vùng muốn chọn làm icon và click chuột, một popup hiện ra cho bạn nhập tên CLASS.
            <strong>Chú ý: nhập tên CLASS viết liền, không dấu, không ký tự đặc biệt và không trùng với các CLASS đã tạo trước đó.</strong>
          </p>
          <p>
            Phần bên phải sẽ là nơi hiển thị các icon đã được tạo. Bạn có thể xóa hoặc nhấn lưu để thiết lập.
          </p>
        </div>
      </div>
      <div class="container-images">
        <div class="left-content" id="display-image">
            <div id="image"><img src="" alt="" /></div>
            <div id="overlay-icon">
            </div>
        </div>
        <div class="right-content">
          <div class="btns">
            <input type="button" name="btn-save-config" class="btn-save-config button-secondary" value="Lưu">
          </div>
          <div id="lst-icons"></div>
        </div>
      </div>
      <script>
        <?php 
          $key = 'custom_css_data_icon';
          if ( get_option( $key ) ) : ?>
            const icondata = <?php echo get_option( $key )  ?> ;
          <?php endif;
        ?>
      </script>
    <?php }

   