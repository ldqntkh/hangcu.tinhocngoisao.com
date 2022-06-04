<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package electro
 */

electro_get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">
		<div class="info-404">
			<div class="info-content">
				<img src="<?= get_stylesheet_directory_uri() ?>/assets/images/gbot-404.jpg" />
			</div>
		</div>

		<div class="info-contact">
			<p class="p1"><?= __("Rất tiếc, trang bạn tìm kiếm không tồn tại", "hangcu") ?></p>
			<p class="p2">
				<?= __("Nếu bạn cần hỗ trợ, vui lòng liên hệ tổng đài: ", "hangcu") ?> 
				<strong><a href="tel:19006975"><?= __("1800 6975", "hangcu") ?></a></strong>
			</p>
			<a href="<?= home_url() ?>"><?= __("Trang chủ", "hangcu") ?></a>
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer();