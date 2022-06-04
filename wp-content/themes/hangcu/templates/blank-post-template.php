<?php
/*
* Template Name: HC Blank post template
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

</head>

<body >
<div id="page-blank-post" class="hfeed site">
<?php 
while ( have_posts() ) :
    
    echo '<div class="hc-custom-post">';
    the_post();
    the_content();
    echo '</div>';
	
endwhile; // End of the loop.

?>

</div><!-- #page -->

</body>
</html>
