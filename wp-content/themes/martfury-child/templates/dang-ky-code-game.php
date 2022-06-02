<?php 
/**
 * Template Name: Đăng ký codegame
 */
if( !is_user_logged_in() ) {
    wp_redirect( 'https://tinhocngoisao.com/my-account?redirect_to=https://tinhocngoisao.com/dang-ky-code-game' );
    die;
}
get_header();
?>
    <div id="dang-ky-code-game"></div>
<?php
    get_footer();
?>