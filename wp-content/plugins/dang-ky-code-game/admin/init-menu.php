<?php
add_action( 'admin_menu', 'dkcodegame_service_menu', 100 );
function dkcodegame_service_menu() {
    add_menu_page( 'Danh sách ĐK code game' ,'Danh sách ĐK code game', 'quan_ly_code_game', 'danh-sach-dang-ky-code-game', 'danh_sach_dang_ky_code_game');
    add_submenu_page( 'danh-sach-dang-ky-code-game' ,'Cấu hình thông báo', 'Cấu hình thông báo', 'quan_ly_code_game', 'cau-hinh-thong-bao', 'cau_hinh_thong_bao_codegame');
}

function danh_sach_dang_ky_code_game() {
    if( isset( $_GET['type'] ) && $_GET['type'] == 'view' ) {
        include_once DKCODEGAME_PLUGIN_DIR . '/admin/view/chi-tiet.php';
    } else {
        include_once DKCODEGAME_PLUGIN_DIR . '/admin/view/danh-sach.php';
    }
}

function cau_hinh_thong_bao_codegame() {
    include_once DKCODEGAME_PLUGIN_DIR . '/admin/view/cau-hinh-thong-bao.php';
}