<?php
if( electro_detect_is_mobile() ) {
    require THEME_PATH . '/header-mobile.php';
} else {
    require THEME_PATH . '/header-desktop.php';
}
?>
<?php 
    $user = wp_get_current_user();
    if( $user ) :
?>
<script type="text/javascript">
    sessionStorage.setItem('user', JSON.stringify(<?= json_encode($user) ?>))
</script>
<?php endif; ?>