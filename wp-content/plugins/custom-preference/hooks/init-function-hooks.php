<?php
if( !function_exists('hangcu_render_banner_header') ) {
    function hangcu_render_banner_header() {
        if( electro_detect_is_mobile() ) return null;
        $enable_header_promotion = isset(get_option( CUSTOM_PREFERECE_HEADER_PROMOTION )['enable_header_promotion']) ? get_option( CUSTOM_PREFERECE_HEADER_PROMOTION )['enable_header_promotion'] : '';
        if ($enable_header_promotion != 'on') {
            return null;
        }
        
        // add class body
        add_filter( 'body_class',function( $classes ) {
            $classes[] = 'has-slider-promotion';
         
            return $classes;
        } );
    
        $arrayData = [];
        // hỗ trợ tối đa 10 items
        for( $i = 1; $i <= 10; $i++ ) {
            if( empty( get_option( CUSTOM_PREFERECE_HEADER_PROMOTION )['section_'.$i]['image'] ) ) continue;
            $item = [
                "background_color" => get_option( CUSTOM_PREFERECE_HEADER_PROMOTION )['section_'.$i]['background_color'],
                "image" => get_option( CUSTOM_PREFERECE_HEADER_PROMOTION )['section_'.$i]['image'],
                "url" => get_option( CUSTOM_PREFERECE_HEADER_PROMOTION )['section_'.$i]['url'],
            ];
            $arrayData[] = $item;
        
        } 
        $renderBanner = [];
        /*
        // testing chỉ lấy 4 cái tượng trưng
        // check ip để lấy banner hiển thị
        // nếu HCM thì load 2 banner đầu tiên
        // ngược lại thì load mấy cái còn lại
        $ip = get_client_ip(); 
        // $query = @unserialize(file_get_contents('http://ip-api.com/json/'.$ip));
        $query = file_get_contents('http://ip-api.com/json/'.$ip);
        $query = json_decode($query);
        
        if ( $query->status == 'success' ) {
        if( strtolower( $query->countryCode ) == 'vn' ) {
            if( strtolower( $query->region ) == 'sg' ) {
            $renderBanner[] = $arrayData[0]; 
            $renderBanner[] = $arrayData[1];
            }
        } else {
            $renderBanner[] = $arrayData[2]; 
            $renderBanner[] = $arrayData[3];
        }
        */
    
        $renderBanner = $arrayData;
        if( count($renderBanner) > 0 ) : ?>
        <div id="hc-banner-header" class="owl-carousel">
            <?php
            foreach( $renderBanner as $banner ) : ?>
                <div class="item" style="background-color: <?php echo $banner['background_color'] ?>">
                <a href="<?php echo $banner['url'] ?>" style=" background: url(<?php echo $banner['image'] ?>); background-position: center; background-repeat: no-repeat; ">
                </a>
                </div>
            <?php endforeach;
            ?>
        </div>
        <?php endif; ?>
    <?php }
}


if( !function_exists('get_client_ip') ) {
    function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}
