<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package electro
 */

electro_get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<div class="info-contact">
			<p class="p1"><?= __("Rất tiếc, trang bạn tìm kiếm không tồn tại", "hangcu") ?></p>
			<a href="<?= home_url() ?>"><?= __("Trang chủ", "hangcu") ?></a>
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer();