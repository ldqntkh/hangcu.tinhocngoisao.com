<?php
/**
 * Home v1 Metabox
 *
 * Displays the home v1 meta box, tabbed, with several panels covering price, stock etc.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * CT_Electro_Meta_Box_Home_v1 Class.
 */
class CT_Electro_Meta_Box_Home_v1 {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public static function output( $post ) {
		global $post, $thepostid;

		wp_nonce_field( 'electro_save_data', 'electro_meta_nonce' );

		$thepostid 		= $post->ID;
		$template_file 	= get_post_meta( $thepostid, '_wp_page_template', true );

		if ( $template_file !== 'hangcu-template-homepage-v1.php' ) {
			return;
		}

		self::output_home_v1( $post );
	}

	private static function output_home_v1( $post ) {

		$home_v1 = hangcu_get_home_v1_meta();

		?>
		<div class="panel-wrap meta-box-home">
			<ul class="home_data_tabs ec-tabs">
			<?php
				$product_data_tabs = apply_filters( 'electro_home_v1_data_tabs', array(
					'general' => array(
						'label'  => esc_html__( 'General', 'electro' ),
						'target' => 'general_block',
						'class'  => array(),
					),
					'slider' => array(
						'label'  => esc_html__( 'Slider', 'electro' ),
						'target' => 'slider_block',
						'class'  => array(),
					),
					'branches' => array(
						'label'  => esc_html__( 'Brands', 'electro' ),
						'target' => 'branches',
						'class'  => array(),
					),
					'ads_block' => array(
						'label'  => esc_html__( 'Ads Block', 'electro' ),
						'target' => 'ads_block',
						'class'  => array(),
					),
					'products_viewed' => array(
						'label'  => esc_html__( 'Products Viewed', 'electro' ),
						'target' => 'products_viewed',
						'class'  => array(),
					),
					'deal_and_tabs' => array(
						'label'  => esc_html__( 'Deal and Tabs Block', 'electro' ),
						'target' => 'deals_and_tabs',
						'class'  => array(),
					),
					'cards_carousel_dow' => array(
						'label'  => esc_html__( 'Deal of Week', 'electro' ),
						'target' => 'products_cards_carousel_dow',
						'class'  => array(),
					),
					'tot_block' => array(
						'label'  => electro_is_wide_enabled() ? esc_html__( '4-1-4 Products Block', 'electro' ) :esc_html__( '2-1-2 Products Block', 'electro' ),
						'target' => 'tot_block',
						'class'  => array(),
					),
					'cards_carousel' => array(
						'label'  => esc_html__( 'Product Cards Carousel', 'electro' ),
						'target' => 'products_cards_carousel',
						'class'  => array(),
					),
					'banner' => array(
						'label'  => esc_html__( 'Banner', 'electro' ),
						'target' => 'banner_data',
						'class'  => array(),
					),
					'products_carousel' => array(
						'label'  => esc_html__( 'Products Carousel', 'electro' ),
						'target' => 'products_carousel',
						'class'  => array(),
					),
					'products_with_image_1' => array(
                        'label'  => __( 'Products with Image - 1', 'electro' ),
                        'target' => 'products_with_image_1',
                        'class'  => array(),
                        'is_wide_only' => true,
					),
					'products_with_image_2' => array(
                        'label'  => __( 'Products with Image - 2', 'electro' ),
                        'target' => 'products_with_image_2',
                        'class'  => array(),
                        'is_wide_only' => true,
					),
					'two_banners'   => array(
                        'label'     => __( 'Two Banners', 'electro' ),
                        'target'    => 'two_banners',
                        'class'     => array(),
                        'is_wide_only' => true,
                    ),
				) );
				foreach ( $product_data_tabs as $key => $tab ) {
					?><li class="<?php echo esc_attr( $key ); ?>_options <?php echo esc_attr( $key ); ?>_tab <?php echo implode( ' ' , $tab['class'] ); ?>">
						<a href="#<?php echo esc_attr( $tab['target'] ); ?>"><?php echo esc_html( $tab['label'] ); ?></a>
					</li><?php
				}
				do_action( 'electro_home_write_panel_tabs' );
			?>
			</ul>
			<div id="general_block" class="panel electro_options_panel">
				<div class="options_group">
				<?php 
					electro_wp_select( array(
						'id'			=> '_home_v1_header_style',
						'label'			=> esc_html__( 'Header Style', 'electro' ),
						'options'		=> array(
							'v1'	=> esc_html__( 'Header v1', 'electro' ),
							'v2'	=> esc_html__( 'Header v2', 'electro' ),
							'v3'	=> esc_html__( 'Header v3', 'electro' ),
							'v4'	=> esc_html__( 'Header v4', 'electro' ),
							'v5'    => esc_html__( 'Header v5', 'electro' ),
                            'v6'    => esc_html__( 'Header v6', 'electro' ),
                            'v7'    => esc_html__( 'Header v7', 'electro' ),
                            'v8'    => esc_html__( 'Header v8', 'electro' ),
						),
						'name'			=> '_home_v1[hpc][header_style]',
						'value'			=> isset( $home_v1['hpc']['header_style'] ) ? $home_v1['hpc']['header_style'] : 'v1',
					) );
				?>
				</div>
				<div class="options_group">
					<?php 
						$home_v1_blocks = array(
							'hpc'   	=> esc_html__( 'Page content', 'electro' ),
							'sdr'		=> esc_html__( 'Slider', 'electro' ),
							'brch'		=> esc_html__( 'Brands', 'electro' ),
							'ad'		=> esc_html__( 'Ads Block', 'electro' ),
							'pv'		=> esc_html__( 'Products Viewed', 'electro' ),
							'dtd'		=> esc_html__( 'Deal and Tabs Block', 'electro' ),
							'pcc_dow'	=> esc_html__( 'Deal of week', 'electro' ),
							'tot'		=> electro_is_wide_enabled() ? esc_html__( '4-1-4 Products Block', 'electro' ) :esc_html__( '2-1-2 Products Block', 'electro' ),
							'pcc'		=> esc_html__( 'Product Cards Carousel', 'electro' ),
							'bd'		=> esc_html__( 'Banner', 'electro' ),
							'pc'		=> esc_html__( 'Products Carousel', 'electro' ),
							'pcwi1'		=> esc_html__( 'Products Widt Image -1', 'electro' ),
							'pcwi2'		=> esc_html__( 'Products Widt Image -2', 'electro' ),
							'tbrs'  => esc_html__( 'Two Banners', 'electro' ),
						);
					?>
					<table class="general-blocks-table widefat striped">
						<thead>
							<tr>
								<th><?php echo esc_html__( 'Block', 'electro' ); ?></th>
								<th><?php echo esc_html__( 'Animation', 'electro' ); ?></th>
								<th><?php echo esc_html__( 'Priority', 'electro' ); ?></th>
								<th><?php echo esc_html__( 'Enabled ?', 'electro' ); ?></th>
							</tr>	
						</thead>
						<tbody>
							<?php foreach( $home_v1_blocks as $key => $home_v1_block ) : ?>
							<tr>
								<td><?php echo esc_html( $home_v1_block ); ?></td>
								<td><?php electro_wp_animation_dropdown( array(  'id' => '_home_v1_' . $key . '_animation', 'label'=> '', 'name' => '_home_v1[' . $key . '][animation]', 'value' => isset( $home_v1['' . $key . '']['animation'] ) ? $home_v1['' . $key . '']['animation'] : '', )); ?></td>
								<td><?php electro_wp_text_input( array(  'id' => '_home_v1_' . $key . '_priority', 'label'=> '', 'name' => '_home_v1[' . $key . '][priority]', 'value' => isset( $home_v1['' . $key . '']['priority'] ) ? $home_v1['' . $key . '']['priority'] : 10, ) ); ?></td>
								<td><?php electro_wp_checkbox( array( 'id' => '_home_v1_' . $key . '_is_enabled', 'label' => '', 'name' => '_home_v1[' . $key . '][is_enabled]', 'value'=> isset( $home_v1['' . $key . '']['is_enabled'] ) ? $home_v1['' . $key . '']['is_enabled'] : '', ) ); ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div><!-- /#general_block -->
			
			<div id="slider_block" class="panel electro_options_panel">
				<div class="options_group">
				<?php 
					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_sdr_shortcode', 
						'label' 		=> esc_html__( 'Shortcode', 'electro' ), 
						'placeholder' 	=> __( 'Enter the shorcode for your slider here', 'electro' ),
						'name'			=> '_home_v1[sdr][shortcode]',
						'value'			=> isset( $home_v1['sdr']['shortcode'] ) ? $home_v1['sdr']['shortcode'] : '',
					) );
				?>
				</div>
			</div><!-- /#slider_block -->
			
			<div id="branches" class="panel electro_options_panel">

				<?php 
					electro_wp_legend( esc_html__( 'Brands', 'electro' ) ); 
					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_brch_section_title', 
						'label' 		=> esc_html__( 'Block title', 'hangcu' ), 
						'name'			=> '_home_v1[brch][section_title]',
						'value'			=> isset( $home_v1['brch']['section_title'] ) ? $home_v1['brch']['section_title'] : '',
					) );
				?>

				<div class="options_group">
				<?php 
					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_brch_shortcode', 
						'label' 		=> esc_html__( 'Shortcode', 'electro' ), 
						'placeholder' 	=> __( 'Nhập shortcode cho brands', 'electro' ),
						'name'			=> '_home_v1[brch][shortcode]',
						'value'			=> isset( $home_v1['brch']['shortcode'] ) ? $home_v1['brch']['shortcode'] : '',
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_brch_section_viewmore_title', 
						'label' 		=> esc_html__( 'View more title', 'hangcu' ), 
						'name'			=> '_home_v1[brch][viewmore_title]',
						'value'			=> isset( $home_v1['brch']['viewmore_title'] ) ? $home_v1['brch']['viewmore_title'] : '',
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_brch_viewmore_url', 
						'label' 		=> esc_html__( 'View more link', 'hangcu' ), 
						'name'			=> '_home_v1[brch][viewmore_url]',
						'value'			=> isset( $home_v1['brch']['viewmore_url'] ) ? $home_v1['brch']['viewmore_url'] : '',
					) );
				?>
				<?php
					// for($index = 0; $index < 16; $index++) {
					// 	echo '<p style="border-bottom: 1px solid silver" >';
					// 	electro_wp_text_input( array( 
					// 		'id' 			=> '_home_v1_brch_'.$index.'_action_link', 
					// 		'label' 		=> esc_html__( 'Action Link', 'electro' ), 
					// 		'name'			=> '_home_v1[brch]['.$index.'][action_link]',
					// 		'value'			=> isset( $home_v1['brch'][$index]['action_link'] ) ? $home_v1['brch'][$index]['action_link'] : '#',
					// 	) );
	
					// 	electro_wp_upload_image( array(
					// 		'id'			=> '_home_v1_brch_'.$index.'_brch_image',
					// 		'label'			=> esc_html__( 'Brand Image', 'electro' ),
					// 		'name'			=> '_home_v1[brch]['.$index.'][brch_image]',
					// 		'value'			=> isset( $home_v1['brch'][$index]['brch_image'] ) ? $home_v1['brch'][$index]['brch_image'] : '',
					// 	) );
	
					// 	electro_wp_text_input( array( 
					// 		'id' 			=> '_home_v1_brch_'.$index.'_el_class', 
					// 		'label' 		=> esc_html__( 'Extra Class', 'electro' ), 
					// 		'name'			=> '_home_v1[brch]['.$index.'][el_class]',
					// 		'value'			=> isset( $home_v1['brch'][$index]['el_class'] ) ? $home_v1['brch'][$index]['el_class'] : '',
					// 	) );
					// 	echo '</p>';
					// }
				?>
				</div>

				
			</div><!-- /#branches -->

			<div id="ads_block" class="panel electro_options_panel">

				<?php electro_wp_legend( esc_html__( 'Ads Block', 'electro' ) ); ?>

				<div class="options_group">
				<?php

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_1_ad_text', 
						'label' 		=> esc_html__( 'Ad Text', 'electro' ), 
						'name'			=> '_home_v1[ad][0][ad_text]',
						'value'			=> isset( $home_v1['ad'][0]['ad_text'] ) ? $home_v1['ad'][0]['ad_text'] : wp_kses_post( __( 'Catch Big <br><strong>Deals</strong> on the <br>Cameras', 'electro' ) ),
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_1_action_text', 
						'label' 		=> esc_html__( 'Action Text', 'electro' ), 
						'name'			=> '_home_v1[ad][0][action_text]',
						'value'			=> isset( $home_v1['ad'][0]['action_text'] ) ? $home_v1['ad'][0]['action_text'] : wp_kses_post( __( 'Shop now', 'electro' ) ),
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_1_action_link', 
						'label' 		=> esc_html__( 'Action Link', 'electro' ), 
						'name'			=> '_home_v1[ad][0][action_link]',
						'value'			=> isset( $home_v1['ad'][0]['action_link'] ) ? $home_v1['ad'][0]['action_link'] : '#',
					) );

					electro_wp_upload_image( array(
						'id'			=> '_home_v1_ad_1_ad_image',
						'label'			=> esc_html__( 'Ad Image', 'electro' ),
						'name'			=> '_home_v1[ad][0][ad_image]',
						'value'			=> isset( $home_v1['ad'][0]['ad_image'] ) ? $home_v1['ad'][0]['ad_image'] : '',
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_1_el_class', 
						'label' 		=> esc_html__( 'Extra Class', 'electro' ), 
						'name'			=> '_home_v1[ad][0][el_class]',
						'value'			=> isset( $home_v1['ad'][0]['el_class'] ) ? $home_v1['ad'][0]['el_class'] : '',
					) );
				?>
				</div>

				<?php electro_wp_legend( esc_html__( 'Ads Block 2', 'electro' ) ); ?>

				<div class="options_group">
				<?php

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_2_ad_text', 
						'label' 		=> esc_html__( 'Ad Text', 'electro' ), 
						'name'			=> '_home_v1[ad][1][ad_text]',
						'value'			=> isset( $home_v1['ad'][1]['ad_text'] ) ? $home_v1['ad'][1]['ad_text'] : wp_kses_post( __( 'Shop the <br><strong>Hottest</strong><br> Products', 'electro' ) ),
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_2_action_text', 
						'label' 		=> esc_html__( 'Action Text', 'electro' ), 
						'name'			=> '_home_v1[ad][1][action_text]',
						'value'			=> isset( $home_v1['ad'][1]['action_text'] ) ? $home_v1['ad'][1]['action_text'] : wp_kses_post( __( '<span class="upto"><span class="prefix">Upto</span><span class="value">70</span><span class="suffix">%</span>', 'electro' ) ),
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_2_action_link', 
						'label' 		=> esc_html__( 'Action Link', 'electro' ), 
						'name'			=> '_home_v1[ad][1][action_link]',
						'value'			=> isset( $home_v1['ad'][1]['action_link'] ) ? $home_v1['ad'][1]['action_link'] : '#',
					) );

					electro_wp_upload_image( array(
						'id'			=> '_home_v1_ad_2_ad_image',
						'label'			=> esc_html__( 'Ad Image', 'electro' ),
						'name'			=> '_home_v1[ad][1][ad_image]',
						'value'			=> isset( $home_v1['ad'][1]['ad_image'] ) ? $home_v1['ad'][1]['ad_image'] : '',
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_2_el_class', 
						'label' 		=> esc_html__( 'Extra Class', 'electro' ), 
						'name'			=> '_home_v1[ad][1][el_class]',
						'value'			=> isset( $home_v1['ad'][1]['el_class'] ) ? $home_v1['ad'][1]['el_class'] : '',
					) );
				?>
				</div>

				<?php electro_wp_legend( esc_html__( 'Ads Block 3', 'electro' ) ); ?>

				<div class="options_group">
				<?php

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_3_ad_text', 
						'label' 		=> esc_html__( 'Ad Text', 'electro' ), 
						'name'			=> '_home_v1[ad][2][ad_text]',
						'value'			=> isset( $home_v1['ad'][2]['ad_text'] ) ? $home_v1['ad'][2]['ad_text'] : wp_kses_post( __( 'Tablets, <br>Smartphones <br><strong>and more</strong>', 'electro' ) ),
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_3_action_text', 
						'label' 		=> esc_html__( 'Action Text', 'electro' ), 
						'name'			=> '_home_v1[ad][2][action_text]',
						'value'			=> isset( $home_v1['ad'][2]['action_text'] ) ? $home_v1['ad'][2]['action_text'] : wp_kses_post( __( 'Shop now', 'electro' ) ),
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_3_action_link', 
						'label' 		=> esc_html__( 'Action Link', 'electro' ), 
						'name'			=> '_home_v1[ad][2][action_link]',
						'value'			=> isset( $home_v1['ad'][2]['action_link'] ) ? $home_v1['ad'][2]['action_link'] : '#',
					) );

					electro_wp_upload_image( array(
						'id'			=> '_home_v1_ad_3_ad_image',
						'label'			=> esc_html__( 'Ad Image', 'electro' ),
						'name'			=> '_home_v1[ad][2][ad_image]',
						'value'			=> isset( $home_v1['ad'][2]['ad_image'] ) ? $home_v1['ad'][2]['ad_image'] : '',
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_3_el_class', 
						'label' 		=> esc_html__( 'Extra Class', 'electro' ), 
						'name'			=> '_home_v1[ad][2][el_class]',
						'value'			=> isset( $home_v1['ad'][2]['el_class'] ) ? $home_v1['ad'][2]['el_class'] : '',
					) );
				?>
				</div>

				<?php if ( electro_is_wide_enabled() ) : ?>

				<?php electro_wp_legend( esc_html__( 'Ads Block 4', 'electro' ) ); ?>

				<h5 class="options-group__title"><?php echo esc_html__( 'Appears only in Wide view', 'electro' ); ?></h5>

				<div class="options_group">
				<?php

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_4_ad_text', 
						'label' 		=> esc_html__( 'Ad Text', 'electro' ), 
						'name'			=> '_home_v1[ad][3][ad_text]',
						'value'			=> isset( $home_v1['ad'][3]['ad_text'] ) ? $home_v1['ad'][3]['ad_text'] : wp_kses_post( __( 'Shop the <br><strong>Hottest</strong><br> Products', 'electro' ) ),
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_4_action_text', 
						'label' 		=> esc_html__( 'Action Text', 'electro' ), 
						'name'			=> '_home_v1[ad][3][action_text]',
						'value'			=> isset( $home_v1['ad'][3]['action_text'] ) ? $home_v1['ad'][3]['action_text'] : wp_kses_post( __( 'Shop now', 'electro' ) ),
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_4_action_link', 
						'label' 		=> esc_html__( 'Action Link', 'electro' ), 
						'name'			=> '_home_v1[ad][3][action_link]',
						'value'			=> isset( $home_v1['ad'][3]['action_link'] ) ? $home_v1['ad'][3]['action_link'] : '#',
					) );

					electro_wp_upload_image( array(
						'id'			=> '_home_v1_ad_4_ad_image',
						'label'			=> esc_html__( 'Ad Image', 'electro' ),
						'name'			=> '_home_v1[ad][3][ad_image]',
						'value'			=> isset( $home_v1['ad'][3]['ad_image'] ) ? $home_v1['ad'][3]['ad_image'] : '',
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_ad_4_el_class', 
						'label' 		=> esc_html__( 'Extra Class', 'electro' ), 
						'name'			=> '_home_v1[ad][3][el_class]',
						'value'			=> isset( $home_v1['ad'][3]['el_class'] ) ? $home_v1['ad'][3]['el_class'] : '',
					) );
				?>
				</div>	
				<?php endif; ?>
			</div><!-- /#ads_block -->

			<div id="products_viewed" class="panel electro_options_panel">
				<div class="options_group">
				<?php
					electro_wp_text_input( array(
						'id'			=> '_home_v1_pv_section_title',
						'label'			=> esc_html__( 'Section Title', 'electro' ),
						'name'			=> '_home_v1[pv][section_title]',
						'value'			=> isset( $home_v1['pv']['section_title'] ) ? $home_v1['pv']['section_title'] : esc_html__( 'Product viewed', 'electro' ),
					) );
					
					hangcu_electro_wp_owl_carousel_options( array( 
						'id' 			=> '_home_v1_pv_carousel_args',
						'label'			=> esc_html__( 'Carousel Args', 'electro' ),
						'name'			=> '_home_v1[pv][carousel_args]',
						'value'			=> isset( $home_v1['pv']['carousel_args'] ) ? $home_v1['pv']['carousel_args'] : '',
					) );

					electro_wp_text_input( array(
						'id'			=> '_home_v1_pv_product_limit',
						'label'			=> esc_html__( 'Limit', 'electro' ),
						'name'			=> '_home_v1[pv][product_limit]',
						'value'			=> isset( $home_v1['pv']['product_limit'] ) ? $home_v1['pv']['product_limit'] : 20,
					) );

					electro_wp_text_input( array(
						'id'			=> '_home_v1_pv_product_columns',
						'label'			=> esc_html__( 'Columns', 'electro' ),
						'name'			=> '_home_v1[pv][product_columns]',
						'value'			=> isset( $home_v1['pv']['product_columns'] ) ? $home_v1['pv']['product_columns'] : 6,
					) );
				?>
				</div>
				<?php if ( electro_is_wide_enabled() ) : ?>
				<h5 class="options-group__title"><?php echo esc_html__( 'Wide Layout', 'electro' ); ?></h5>
				<div class="options_group">
				<?php
					electro_wp_text_input( array(
						'id'			=> '_home_v1_pv_product_columns_wide',
						'label'			=> esc_html__( 'Columns', 'electro' ),
						'name'			=> '_home_v1[pv][product_columns_wide]',
						'value'			=> isset( $home_v1['pv']['product_columns_wide'] ) ? $home_v1['pv']['product_columns_wide'] : 7,
					) ); 
				?>
				</div>
				<?php endif; ?>
			</div><!-- Product viewed -->

			<div id="deals_and_tabs" class="panel electro_options_panel">
				
				<?php electro_wp_legend( esc_html__( 'Deals Block', 'electro' ) ); ?>
				
				<div class="options_group">
				<?php

					electro_wp_checkbox( array(
						'id'			=> '_home_v1_dtd_deal_is_enabled',
						'label'			=> esc_html__( 'Enable Deal Block', 'electro' ), 
						'name'			=> '_home_v1[dtd][deal][is_enabled]',
						'value'			=> isset( $home_v1['dtd']['deal']['is_enabled'] ) ? $home_v1['dtd']['deal']['is_enabled'] : '',
					) );

					electro_wp_text_input( array( 
						'id' 			=> '_home_v1_dtd_deal_block_title', 
						'label' 		=> esc_html__( 'Deal Block Title', 'electro' ), 
						'placeholder' 	=> __( 'Special Offer', 'electro' ),
						'name'			=> '_home_v1[dtd][deal][title]',
						'value'			=> isset( $home_v1['dtd']['deal']['title'] ) ? $home_v1['dtd']['deal']['title'] : esc_html__( 'Special Offer', 'electro' ),
					) );

					electro_wp_select( array(
						'id'			=> '_home_v1_dtd_deal_product_choice',
						'label'			=> esc_html__( 'Product Choice', 'electro' ),
						'options'		=> array(
							'random'	=> esc_html__( 'Random On Sale Product', 'electro' ),
							'recent'	=> esc_html__( 'Most Recent On Sale Product', 'electro' ),
							'specific'	=> esc_html__( 'Specify by ID', 'electro' ),
						),
						'class'			=> 'show_hide_select',
						'name'			=> '_home_v1[dtd][deal][product_choice]',
						'value'			=> isset( $home_v1['dtd']['deal']['product_choice'] ) ? $home_v1['dtd']['deal']['product_choice'] : 'random',
					) );
					
					electro_wp_text_input( array( 
						'id'			=> '_home_v1_dtd_deal_product_id', 
						'label' 		=>  esc_html__( 'Deal Product ID', 'electro' ),
						'name'			=> '_home_v1[dtd][deal][product_id]',
						'wrapper_class'	=> 'show_if_specific hide',
						'value'			=> $home_v1['dtd']['deal']['product_id']
					) );

					electro_wp_select( array( 
						'id'            => '_home_v1_dtd_deal_savings_in',
						'label'         => esc_html__( 'Savings in', 'electro' ),
						'options'       => array(
							'amount'     => esc_html__( 'Amount', 'electro' ),
							'percentage' => esc_html__( 'Percentage', 'electro' ),
						),
						'name'          => '_home_v1[dtd][deal][savings_in]',
						'value'         => isset( $home_v1['dtd']['deal']['savings_in'] ) ? $home_v1['dtd']['deal']['savings_in'] : 'amount',
					) );	
				?>
				</div>

				<?php electro_wp_legend( esc_html__( 'Tabs Block', 'electro' ) ); ?>

				<div class="options_group">
				<?php 
					electro_wp_text_input( array( 
						'id'			=> '_home_v1_dtd_tab_block_product_limit', 
						'label' 		=>  esc_html__( 'Products Limit', 'electro' ),
						'placeholder' 	=> esc_html__( 'Enter the number of products to show', 'electro' ),
						'name'			=> '_home_v1[dtd][tab][product_limit]',
						'class'			=> 'product_limit',
						'size'			=> 2,
						'value'			=> isset( $home_v1['dtd']['tab']['product_limit'] ) ? $home_v1['dtd']['tab']['product_limit'] : 6,
					) );

					electro_wp_select( array( 
						'id'			=> '_home_v1_dtd_tab_block_product_columns', 
						'label' 		=>  esc_html__( 'Columns', 'electro' ),
						'options'		=> array(
							'2'	=> '2',
							'3'	=> '3',
							'4'	=> '4',
						),
						'class'			=> 'columns_select',
						'default'		=> '3',
						'name'			=> '_home_v1[dtd][tab][product_columns]',
						'value'			=> isset( $home_v1['dtd']['tab']['product_columns'] ) ? $home_v1['dtd']['tab']['product_columns'] : 3,
					) ); 
				?>
				</div>

				<?php if ( electro_is_wide_enabled() ) : ?>
				<div class="options_group">
					<h5 class="options-group__title"><?php echo esc_html__( 'Wide Layout', 'electro' ); ?></h5>
				<?php
					electro_wp_text_input( array( 
						'id'			=> '_home_v1_dtd_tab_block_product_limit_wide', 
						'label' 		=> esc_html__( 'Products Limit', 'electro' ),
						'placeholder' 	=> esc_html__( 'Enter the number of products to show', 'electro' ),
						'name'			=> '_home_v1[dtd][tab][product_limit_wide]',
						'class'			=> 'product_limit_wide',
						'size'			=> 2,
						'value'			=> isset( $home_v1['dtd']['tab']['product_limit_wide'] ) ? $home_v1['dtd']['tab']['product_limit_wide'] : 8,
					) );

					electro_wp_select( array( 
						'id'			=> '_home_v1_dtd_tab_block_product_columns', 
						'label' 		=>  esc_html__( 'Columns', 'electro' ),
						'options'		=> array(
							'2'	=> '2',
							'3'	=> '3',
							'4'	=> '4',
							'5'	=> '5',
							'6'	=> '6',
						),
						'class'			=> 'columns_select',
						'default'		=> '4',
						'name'			=> '_home_v1[dtd][tab][product_columns_wide]',
						'value'			=> isset( $home_v1['dtd']['tab']['product_columns_wide'] ) ? $home_v1['dtd']['tab']['product_columns_wide'] : 4,
					) );
				?>
				</div>
				<?php endif; ?>

				<div class="options_group">
					<h5 class="options-group__title"><?php echo esc_html__( 'Tabs', 'electro' ); ?></h5>
					<?php	
						electro_wp_text_input( array( 
							'id'			=> '_home_v1_dtd_tab_1_title', 
							'label' 		=> esc_html__( 'Tab #1 Title', 'electro' ),
							'placeholder' 	=> esc_html__( 'Featured', 'electro' ),
							'name'			=> '_home_v1[dtd][tab][tabs][0][title]',
							'value'			=> isset( $home_v1['dtd']['tab']['tabs'][0]['title'] ) ? $home_v1['dtd']['tab']['tabs'][0]['title'] : esc_html__( 'Featured', 'electro' ),
						) );

						hangcu_electro_wp_wc_shortcode( array( 
							'id' 			=> '_home_v1_dtd_tab_1_content',
							'label'			=> esc_html__( 'Tab #1 Content', 'electro' ),
							'default'		=> 'featured_products',
							'name'			=> '_home_v1[dtd][tab][tabs][0][content]',
							'value'			=> isset( $home_v1['dtd']['tab']['tabs'][0]['content'] ) ? $home_v1['dtd']['tab']['tabs'][0]['content'] : '',
						) );
					?>
				</div>
				<div class="options_group">
				<?php
					electro_wp_text_input( array( 
						'id'			=> '_home_v1_dtd_tab_2_title', 
						'label' 		=> esc_html__( 'Tab #2 Title', 'electro' ),
						'placeholder' 	=> esc_html__( 'On Sale', 'electro' ),
						'name'			=> '_home_v1[dtd][tab][tabs][1][title]',
						'value'			=> isset( $home_v1['dtd']['tab']['tabs'][1]['title'] ) ? $home_v1['dtd']['tab']['tabs'][1]['title'] : esc_html__( 'On Sale', 'electro' ),
					) );

					hangcu_electro_wp_wc_shortcode( array( 
						'id' 			=> '_home_v1_dtd_tab_2_content',
						'label'			=> esc_html__( 'Tab #2 Content', 'electro' ),
						'default'		=> 'sale_products',
						'name'			=> '_home_v1[dtd][tab][tabs][1][content]',
						'value'			=> isset( $home_v1['dtd']['tab']['tabs'][1]['content'] ) ? $home_v1['dtd']['tab']['tabs'][1]['content'] : '',
					) );
				?>
				</div>
				<div class="options_group">
				<?php
					electro_wp_text_input( array( 
						'id'			=> '_home_v1_dtd_tab_3_title', 
						'label' 		=> esc_html__( 'Tab #3 Title', 'electro' ),
						'placeholder' 	=> esc_html__( 'Top Rated', 'electro' ),
						'name'			=> '_home_v1[dtd][tab][tabs][2][title]',
						'value'			=> isset( $home_v1['dtd']['tab']['tabs'][2]['title'] ) ? $home_v1['dtd']['tab']['tabs'][2]['title'] : esc_html__( 'Top Rated', 'electro' ),
					) );
					hangcu_electro_wp_wc_shortcode( array( 
						'id' 			=> '_home_v1_dtd_tab_3_content',
						'label'			=> esc_html__( 'Tab #3 Content', 'electro' ),
						'default'		=> 'top_rated_products',
						'name'			=> '_home_v1[dtd][tab][tabs][2][content]',
						'value'			=> isset( $home_v1['dtd']['tab']['tabs'][2]['content'] ) ? $home_v1['dtd']['tab']['tabs'][2]['content'] : '',
					) );
				?>
				</div>
			</div><!-- /#deals_and_tabs_data -->

			<div id="products_cards_carousel_dow" class="panel electro_options_panel">
				<div class="options_group">
					<?php
						electro_wp_text_input( array(
							'id'			=> '_home_v1_pcc_dow_section_title',
							'label'			=> esc_html__( 'Section Title', 'electro' ),
							'name'			=> '_home_v1[pcc_dow][section_title]',
							'default'		=> esc_html__( 'Deal of Week', 'electro' ),
							'value'			=> isset( $home_v1['pcc_dow']['section_title'] ) ? $home_v1['pcc_dow']['section_title'] : esc_html__( 'Deal of Week', 'electro' ),
							'placeholder'	=> esc_html__( 'Deal of Week', 'electro' )
						) );
						electro_wp_wc_shortcode( array( 
							'id' 			=> '_home_v1_pcc_dow_content',
							'label'			=> esc_html__( 'Products', 'electro' ),
							'default'		=> 'best_selling_products',
							'name'			=> '_home_v1[pcc_dow][content]',
							'value'			=> isset( $home_v1['pcc_dow']['content'] ) ? $home_v1['pcc_dow']['content'] : ''
						) );

						electro_wp_text_input( array(
							'id'			=> '_home_v1_pcc_dow_product_limit',
							'label'			=> esc_html__( 'Product Limit', 'electro' ),
							'name'			=> '_home_v1[pcc_dow][product_limit]',
							'value'			=> isset( $home_v1['pcc_dow']['product_limit'] ) ? $home_v1['pcc_dow']['product_limit'] : 20,
							'placeholder'	=> esc_html__( 'Enter number of products to show', 'electro' ),
						) );
						electro_wp_text_input( array(
							'id'			=> '_home_v1_pcc_dow_product_rows',
							'label'			=> esc_html__( 'Rows', 'electro' ),
							'name'			=> '_home_v1[pcc_dow][product_rows]',
							'value'			=> isset( $home_v1['pcc_dow']['product_rows'] ) ? $home_v1['pcc_dow']['product_rows'] : 2,
							'placeholder'	=> esc_html__( 'Enter number of rows to display', 'electro' ),
						) );
						electro_wp_text_input( array(
							'id'			=> '_home_v1_pcc_dow_product_columns',
							'label'			=> esc_html__( 'Columns', 'electro' ),
							'name'			=> '_home_v1[pcc_dow][product_columns]',
							'value'			=> isset( $home_v1['pcc_dow']['product_columns'] ) ? $home_v1['pcc_dow']['product_columns'] : 3,
							'placeholder'	=> esc_html__( 'Enter number of products to show', 'electro' ),
						) );
					?>
				</div>
				<?php if ( electro_is_wide_enabled() ) : ?>
				<h5 class="options-group__title"><?php echo esc_html__( 'Wide Layout', 'electro' ); ?></h5>
				<div class="options_group">
				<?php
					electro_wp_text_input( array(
						'id'			=> '_home_v1_pcc_dow_product_columns_wide',
						'label'			=> esc_html__( 'Columns', 'electro' ),
						'name'			=> '_home_v1[pcc_dow][product_columns_wide]',
						'value'			=> isset( $home_v1['pcc_dow']['product_columns_wide'] ) ? $home_v1['pcc_dow']['product_columns_wide'] : 4,
					) ); 
				?>
				</div>
				<?php endif; ?>
				<div class="options_group"><?php
					hangcu_electro_wp_owl_carousel_options( array( 
						'id' 			=> '_home_v1_pcc_dow_carousel_args',
						'label'			=> esc_html__( 'Carousel Args', 'electro' ),
						'name'			=> '_home_v1[pcc_dow][carousel_args]',
						'value'			=> isset( $home_v1['pcc_dow']['carousel_args'] ) ? $home_v1['pcc_dow']['carousel_args'] : '',
						'fields'		=> array( 'autoplay', 'loop', 'autoplayTimeout' )
					) );
					
				?>
				</div>
				
				<div class="options_group">
				<?php
					electro_wp_text_input( array(
						'id'			=> '_home_v1_pcc_dow_cat_limit',
						'label'			=> esc_html__( 'Categories Limit', 'electro' ),
						'name'			=> '_home_v1[pcc_dow][cat_limit]',
						'default'		=> 3,
						'value'			=> isset( $home_v1['pcc_dow']['cat_limit'] ) ? $home_v1['pcc_dow']['cat_limit'] : 3,
						'placeholder'	=> 3
					) );

					electro_wp_text_input( array(
						'id'			=> '_home_v1_pcc_dow_cat_slugs',
						'label'			=> esc_html__( 'Category Slug', 'electro' ),
						'name'			=> '_home_v1[pcc_dow][cat_slugs]',
						'default'		=> '',
						'value'			=> isset( $home_v1['pcc_dow']['cat_slugs'] ) ? $home_v1['pcc_dow']['cat_slugs'] : '',
						'placeholder'	=> esc_html__( 'Enter category slugs separated by comma', 'electro' )
					) );
				?>
				</div>
			</div><!-- /#products_cards_carousel_dow -->
			
			<div id="tot_block" class="panel electro_options_panel">
				<div class="options_group">
				<?php
					electro_wp_text_input( array(
						'id'			=> '_home_v1_tot_section_title',
						'label'			=> esc_html__( 'Section Title', 'electro' ),
						'name'			=> '_home_v1[tot][section_title]',
						'default'		=> esc_html__( 'Best Deals', 'electro' ),
						'value'			=> isset( $home_v1['tot']['section_title'] ) ? $home_v1['tot']['section_title'] : esc_html__( 'Best Deals', 'electro' ),
						'placeholder'	=> esc_html__( 'Best Deals', 'electro' )
					) );

					electro_wp_text_input( array(
						'id'			=> '_home_v1_tot_cat_limit',
						'label'			=> esc_html__( 'Categories Limit', 'electro' ),
						'name'			=> '_home_v1[tot][cat_limit]',
						'default'		=> 7,
						'value'			=> isset( $home_v1['tot']['cat_limit'] ) ? $home_v1['tot']['cat_limit'] : 7,
						'placeholder'	=> 7
					) );

					electro_wp_text_input( array(
						'id'			=> '_home_v1_tot_cat_slugs',
						'label'			=> esc_html__( 'Category Slug', 'electro' ),
						'name'			=> '_home_v1[tot][cat_slugs]',
						'default'		=> '',
						'value'			=> isset( $home_v1['tot']['cat_slugs'] ) ? $home_v1['tot']['cat_slugs'] : '',
						'placeholder'	=> esc_html__( 'Enter category slugs separated by comma', 'electro' )
					) );

					electro_wp_wc_shortcode( array( 
						'id' 			=> '_home_v1_tot_content',
						'label'			=> esc_html__( 'Products', 'electro' ),
						'default'		=> 'sale_products',
						'name'			=> '_home_v1[tot][content]',
						'value'			=> isset( $home_v1['tot']['content'] ) ? $home_v1['tot']['content'] : ''
					) );
				?>
				</div>
			</div>

			<div id="products_cards_carousel" class="panel electro_options_panel">
				<div class="options_group">
				<?php
					electro_wp_text_input( array(
						'id'			=> '_home_v1_pcc_section_title',
						'label'			=> esc_html__( 'Section Title', 'electro' ),
						'name'			=> '_home_v1[pcc][section_title]',
						'default'		=> esc_html__( 'Best Sellers', 'electro' ),
						'value'			=> isset( $home_v1['pcc']['section_title'] ) ? $home_v1['pcc']['section_title'] : esc_html__( 'Best Sellers', 'electro' ),
						'placeholder'	=> esc_html__( 'Best Sellers', 'electro' )
					) );
					electro_wp_wc_shortcode( array( 
						'id' 			=> '_home_v1_pcc_content',
						'label'			=> esc_html__( 'Products', 'electro' ),
						'default'		=> 'best_selling_products',
						'name'			=> '_home_v1[pcc][content]',
						'value'			=> isset( $home_v1['pcc']['content'] ) ? $home_v1['pcc']['content'] : ''
					) );

					electro_wp_text_input( array(
						'id'			=> '_home_v1_pcc_product_limit',
						'label'			=> esc_html__( 'Product Limit', 'electro' ),
						'name'			=> '_home_v1[pcc][product_limit]',
						'value'			=> isset( $home_v1['pcc']['product_limit'] ) ? $home_v1['pcc']['product_limit'] : 20,
						'placeholder'	=> esc_html__( 'Enter number of products to show', 'electro' ),
					) );
					electro_wp_text_input( array(
						'id'			=> '_home_v1_pcc_product_rows',
						'label'			=> esc_html__( 'Rows', 'electro' ),
						'name'			=> '_home_v1[pcc][product_rows]',
						'value'			=> isset( $home_v1['pcc']['product_rows'] ) ? $home_v1['pcc']['product_rows'] : 2,
						'placeholder'	=> esc_html__( 'Enter number of rows to display', 'electro' ),
					) );
					electro_wp_text_input( array(
						'id'			=> '_home_v1_pcc_product_columns',
						'label'			=> esc_html__( 'Columns', 'electro' ),
						'name'			=> '_home_v1[pcc][product_columns]',
						'value'			=> isset( $home_v1['pcc']['product_columns'] ) ? $home_v1['pcc']['product_columns'] : 3,
						'placeholder'	=> esc_html__( 'Enter number of products to show', 'electro' ),
					) );
				?>
				</div>
				<?php if ( electro_is_wide_enabled() ) : ?>
				<h5 class="options-group__title"><?php echo esc_html__( 'Wide Layout', 'electro' ); ?></h5>
				<div class="options_group">
				<?php
					electro_wp_text_input( array(
						'id'			=> '_home_v1_pcc_product_columns_wide',
						'label'			=> esc_html__( 'Columns', 'electro' ),
						'name'			=> '_home_v1[pcc][product_columns_wide]',
						'value'			=> isset( $home_v1['pcc']['product_columns_wide'] ) ? $home_v1['pcc']['product_columns_wide'] : 4,
					) ); 
				?>
				</div>
				<?php endif; ?>
				<div class="options_group"><?php

					hangcu_electro_wp_owl_carousel_options( array( 
						'id' 			=> '_home_v1_pcc_carousel_args',
						'label'			=> esc_html__( 'Carousel Args', 'electro' ),
						'name'			=> '_home_v1[pcc][carousel_args]',
						'value'			=> isset( $home_v1['pcc']['carousel_args'] ) ? $home_v1['pcc']['carousel_args'] : '',
						'fields'		=> array( 'autoplay', 'loop', 'autoplayTimeout' )
					) );
					
				?>
				</div>
				
				<div class="options_group">
				<?php
					electro_wp_text_input( array(
						'id'			=> '_home_v1_pcc_cat_limit',
						'label'			=> esc_html__( 'Categories Limit', 'electro' ),
						'name'			=> '_home_v1[pcc][cat_limit]',
						'default'		=> 3,
						'value'			=> isset( $home_v1['pcc']['cat_limit'] ) ? $home_v1['pcc']['cat_limit'] : 3,
						'placeholder'	=> 3
					) );

					electro_wp_text_input( array(
						'id'			=> '_home_v1_pcc_cat_slugs',
						'label'			=> esc_html__( 'Category Slug', 'electro' ),
						'name'			=> '_home_v1[pcc][cat_slugs]',
						'default'		=> '',
						'value'			=> isset( $home_v1['pcc']['cat_slugs'] ) ? $home_v1['pcc']['cat_slugs'] : '',
						'placeholder'	=> esc_html__( 'Enter category slugs separated by comma', 'electro' )
					) );
				?>
				</div>
			</div><!-- /#products_cards_carousel -->
			
			<div id="banner_data" class="panel electro_options_panel">
				<div class="options_group">
				<?php 
					electro_wp_upload_image( array(
						'id'			=> '_home_v1_bd_image',
						'label'			=> esc_html__( 'Banner Image', 'electro' ),
						'name'			=> '_home_v1[bd][image]',
						'value'			=> isset( $home_v1['bd']['image'] ) ? $home_v1['bd']['image'] : '',
					) );
					
					electro_wp_text_input( array(
						'id'			=> '_home_v1_bd_link',
						'label'			=> esc_html__( 'Link', 'electro' ),
						'name'			=> '_home_v1[bd][link]',
						'value'			=> isset( $home_v1['bd']['link'] ) ? $home_v1['bd']['link'] : '#',
					) );
				?>
				</div>
			</div><!-- /#banner_data -->

			<div id="products_carousel" class="panel electro_options_panel">
				<div class="options_group">
				<?php
					electro_wp_text_input( array(
						'id'			=> '_home_v1_pc_section_title',
						'label'			=> esc_html__( 'Section Title', 'electro' ),
						'name'			=> '_home_v1[pc][section_title]',
						'value'			=> isset( $home_v1['pc']['section_title'] ) ? $home_v1['pc']['section_title'] : esc_html__( 'Recently Added', 'electro' ),
					) );

					electro_wp_wc_shortcode( array( 
						'id' 			=> '_home_v1_pc_content',
						'label'			=> esc_html__( 'Products', 'electro' ),
						'default'		=> 'recent_products',
						'name'			=> '_home_v1[pc][content]',
						'value'			=> isset( $home_v1['pc']['content'] ) ? $home_v1['pc']['content'] : '',
					) );

					hangcu_electro_wp_owl_carousel_options( array( 
						'id' 			=> '_home_v1_pc_carousel_args',
						'label'			=> esc_html__( 'Carousel Args', 'electro' ),
						'name'			=> '_home_v1[pc][carousel_args]',
						'value'			=> isset( $home_v1['pc']['carousel_args'] ) ? $home_v1['pc']['carousel_args'] : '',
					) );

					electro_wp_text_input( array(
						'id'			=> '_home_v1_pc_product_limit',
						'label'			=> esc_html__( 'Limit', 'electro' ),
						'name'			=> '_home_v1[pc][product_limit]',
						'value'			=> isset( $home_v1['pc']['product_limit'] ) ? $home_v1['pc']['product_limit'] : 20,
					) );

					electro_wp_text_input( array(
						'id'			=> '_home_v1_pc_product_columns',
						'label'			=> esc_html__( 'Columns', 'electro' ),
						'name'			=> '_home_v1[pc][product_columns]',
						'value'			=> isset( $home_v1['pc']['product_columns'] ) ? $home_v1['pc']['product_columns'] : 6,
					) );
				?>
				</div>
				<?php if ( electro_is_wide_enabled() ) : ?>
				<h5 class="options-group__title"><?php echo esc_html__( 'Wide Layout', 'electro' ); ?></h5>
				<div class="options_group">
				<?php
					electro_wp_text_input( array(
						'id'			=> '_home_v1_pc_product_columns_wide',
						'label'			=> esc_html__( 'Columns', 'electro' ),
						'name'			=> '_home_v1[pc][product_columns_wide]',
						'value'			=> isset( $home_v1['pc']['product_columns_wide'] ) ? $home_v1['pc']['product_columns_wide'] : 7,
					) ); 
				?>
				</div>
				<?php endif; ?>
			</div>

			<div id="products_with_image_1" class="panel electro_options_panel">
                
                <div class="options_group">
                <?php
                    electro_wp_text_input( array(
                        'id'            => '_home_v1_pcwi1_section_title',
                        'label'         => esc_html__( 'Section Title', 'electro' ),
                        'name'          => '_home_v1[pcwi1][section_title]',
                        'value'         => isset( $home_v1['pcwi1']['section_title'] ) ? $home_v1['pcwi1']['section_title'] : esc_html__( 'Headphones', 'electro' ),
                    ) );
                ?>
                </div>

                <div class="options_group">

                <?php electro_wp_legend( esc_html__( 'Header Categories', 'electro' ) ); ?>

                <?php
                    electro_wp_checkbox( array(
                        'id'            => '_home_v1_pcwi1_enable_categories',
                        'label'         => esc_html__( 'Enable Categories', 'electro' ), 
                        'name'          => '_home_v1[pcwi1][enable_categories]',
                        'value'         => isset( $home_v1['pcwi1']['enable_categories'] ) ? $home_v1['pcwi1']['enable_categories'] : '',
                    ) );

                    electro_wp_text_input( array(
                        'id'            => '_home_v1_pcwi1_categories_title',
                        'label'         => esc_html__( 'Categories Title', 'electro' ),
                        'name'          => '_home_v1[pcwi1][categories_title]',
                        'value'         => isset( $home_v1['pcwi1']['categories_title'] ) ? $home_v1['pcwi1']['categories_title'] : esc_html__( 'Top 20', 'electro' ),
                    ) );

                    electro_wp_text_input( array(
                        'id'            => '_home_v1_pcwi1_category_limit',
                        'label'         => esc_html__( 'Categories Limit', 'electro' ),
                        'name'          => '_home_v1[pcwi1][category_args][number]',
                        'default'       => 4,
                        'value'         => isset( $home_v1['pcwi1']['category_args']['number'] ) ? $home_v1['pcwi1']['category_args']['number'] : 4,
                        'placeholder'   => 4
                    ) );

                    electro_wp_text_input( array(
                        'id'            => '_home_v1_pcwi1_category_slugs',
                        'label'         => esc_html__( 'Category Slug', 'electro' ),
                        'name'          => '_home_v1[pcwi1][category_args][slugs]',
                        'default'       => '',
                        'value'         => isset( $home_v1['pcwi1']['category_args']['slugs'] ) ? $home_v1['pcwi1']['category_args']['slugs'] : '',
                        'placeholder'   => esc_html__( 'Enter category slugs separated by comma', 'electro' )
                    ) );

                    electro_wp_checkbox( array(
                        'id'            => '_home_v1_pcwi1_hide_empty_categories',
                        'label'         => esc_html__( 'Hide Empty?', 'electro' ), 
                        'name'          => '_home_v1[pcwi1][category_args][hide_empty]',
                        'value'         => isset( $home_v1['pcwi1']['category_args']['hide_empty'] ) ? $home_v1['pcwi1']['category_args']['hide_empty'] : '',
                    ) );

                ?>
                </div>

                <div class="options_group">
                <?php
                    electro_wp_upload_image( array(
                        'id'            => '_home_v1_pcwi1_image',
                        'label'         => esc_html__( 'Image', 'electro' ),
                        'name'          => '_home_v1[pcwi1][image]',
                        'value'         => isset( $home_v1['pcwi1']['image'] ) ? $home_v1['pcwi1']['image'] : '',
                    ) );

                    electro_wp_text_input( array( 
                        'id'            => '_home_v1_pcwi1_img_action_link',
                        'label'         => esc_html__( 'Image Action Link', 'electro' ),
                        'name'          => '_home_v1[pcwi1][img_action_link]',
                        'value'         => isset( $home_v1['pcwi1']['img_action_link'] ) ? $home_v1['pcwi1']['img_action_link'] : '#',
                    ) );

                    electro_wp_wc_shortcode( array( 
                        'id'            => '_home_v1_pcwi1_content',
                        'label'         => esc_html__( 'Products', 'electro' ),
                        'default'       => 'recent_products',
                        'name'          => '_home_v1[pcwi1][content]',
                        'value'         => isset( $home_v1['pcwi1']['content'] ) ? $home_v1['pcwi1']['content'] : '',
                        'fields'        => array( 'order', 'orderby', 'per_page' )
                    ) );

                    electro_wp_select( array( 
						'id'			=> '_home_v1_pcwi1_columns', 
						'label' 		=>  esc_html__( 'Columns', 'electro' ),
						'options'		=> array(
							'2'	=> '2',
							'3'	=> '3',
							'4'	=> '4',
							'5'	=> '5',
							'6'	=> '6',
						),
						'class'			=> 'columns_select',
						'default'		=> '4',
						'name'			=> '_home_v1[pcwi1][content][shortcode_atts][columns]',
						'value'			=> isset( $home_v1['pcwi1']['content']['shortcode_atts']['columns'] ) ? $home_v1['pcwi1']['content']['shortcode_atts']['columns'] : 4,
					) );

					electro_wp_select( array( 
						'id'			=> '_home_v1_pcwi1_columns_wide', 
						'label' 		=>  esc_html__( 'Columns Wide', 'electro' ),
						'options'		=> array(
							'2'	=> '2',
							'3'	=> '3',
							'4'	=> '4',
							'5'	=> '5',
							'6'	=> '6',
						),
						'class'			=> 'columns_select',
						'default'		=> '4',
						'name'			=> '_home_v1[pcwi1][product_columns_wide]',
						'value'			=> isset( $home_v1['pcwi1']['product_columns_wide'] ) ? $home_v1['pcwi1']['product_columns_wide'] : 4,
					) );
                ?>
                </div>
            </div><!-- /#products_with_image_1 -->

			<div id="products_with_image_2" class="panel electro_options_panel">
                
                <div class="options_group">
                <?php
                    electro_wp_text_input( array(
                        'id'            => '_home_v1_pcwi2_section_title',
                        'label'         => esc_html__( 'Section Title', 'electro' ),
                        'name'          => '_home_v1[pcwi2][section_title]',
                        'value'         => isset( $home_v1['pcwi2']['section_title'] ) ? $home_v1['pcwi2']['section_title'] : esc_html__( 'Headphones', 'electro' ),
                    ) );
                ?>
                </div>

                <div class="options_group">

                <?php electro_wp_legend( esc_html__( 'Header Categories', 'electro' ) ); ?>

                <?php
                    electro_wp_checkbox( array(
                        'id'            => '_home_v1_pcwi2_enable_categories',
                        'label'         => esc_html__( 'Enable Categories', 'electro' ), 
                        'name'          => '_home_v1[pcwi2][enable_categories]',
                        'value'         => isset( $home_v1['pcwi2']['enable_categories'] ) ? $home_v1['pcwi2']['enable_categories'] : '',
                    ) );

                    electro_wp_text_input( array(
                        'id'            => '_home_v1_pcwi2_categories_title',
                        'label'         => esc_html__( 'Categories Title', 'electro' ),
                        'name'          => '_home_v1[pcwi2][categories_title]',
                        'value'         => isset( $home_v1['pcwi2']['categories_title'] ) ? $home_v1['pcwi2']['categories_title'] : esc_html__( 'Top 20', 'electro' ),
                    ) );

                    electro_wp_text_input( array(
                        'id'            => '_home_v1_pcwi2_category_limit',
                        'label'         => esc_html__( 'Categories Limit', 'electro' ),
                        'name'          => '_home_v1[pcwi2][category_args][number]',
                        'default'       => 4,
                        'value'         => isset( $home_v1['pcwi2']['category_args']['number'] ) ? $home_v1['pcwi2']['category_args']['number'] : 4,
                        'placeholder'   => 4
                    ) );

                    electro_wp_text_input( array(
                        'id'            => '_home_v1_pcwi2_category_slugs',
                        'label'         => esc_html__( 'Category Slug', 'electro' ),
                        'name'          => '_home_v1[pcwi2][category_args][slugs]',
                        'default'       => '',
                        'value'         => isset( $home_v1['pcwi2']['category_args']['slugs'] ) ? $home_v1['pcwi2']['category_args']['slugs'] : '',
                        'placeholder'   => esc_html__( 'Enter category slugs separated by comma', 'electro' )
                    ) );

                    electro_wp_checkbox( array(
                        'id'            => '_home_v1_pcwi2_hide_empty_categories',
                        'label'         => esc_html__( 'Hide Empty?', 'electro' ), 
                        'name'          => '_home_v1[pcwi2][category_args][hide_empty]',
                        'value'         => isset( $home_v1['pcwi2']['category_args']['hide_empty'] ) ? $home_v1['pcwi2']['category_args']['hide_empty'] : '',
                    ) );

                ?>
                </div>

                <div class="options_group">
                <?php
                    electro_wp_upload_image( array(
                        'id'            => '_home_v1_pcwi2_image',
                        'label'         => esc_html__( 'Image', 'electro' ),
                        'name'          => '_home_v1[pcwi2][image]',
                        'value'         => isset( $home_v1['pcwi2']['image'] ) ? $home_v1['pcwi2']['image'] : '',
                    ) );

                    electro_wp_text_input( array( 
                        'id'            => '_home_v1_pcwi2_img_action_link',
                        'label'         => esc_html__( 'Image Action Link', 'electro' ),
                        'name'          => '_home_v1[pcwi2][img_action_link]',
                        'value'         => isset( $home_v1['pcwi2']['img_action_link'] ) ? $home_v1['pcwi2']['img_action_link'] : '#',
                    ) );

                    electro_wp_wc_shortcode( array( 
                        'id'            => '_home_v1_pcwi2_content',
                        'label'         => esc_html__( 'Products', 'electro' ),
                        'default'       => 'recent_products',
                        'name'          => '_home_v1[pcwi2][content]',
                        'value'         => isset( $home_v1['pcwi2']['content'] ) ? $home_v1['pcwi2']['content'] : '',
                        'fields'        => array( 'order', 'orderby', 'per_page' )
                    ) );

                    electro_wp_select( array( 
						'id'			=> '_home_v1_pcwi2_columns', 
						'label' 		=>  esc_html__( 'Columns', 'electro' ),
						'options'		=> array(
							'2'	=> '2',
							'3'	=> '3',
							'4'	=> '4',
							'5'	=> '5',
							'6'	=> '6',
						),
						'class'			=> 'columns_select',
						'default'		=> '4',
						'name'			=> '_home_v1[pcwi2][content][shortcode_atts][columns]',
						'value'			=> isset( $home_v1['pcwi2']['content']['shortcode_atts']['columns'] ) ? $home_v1['pcwi2']['content']['shortcode_atts']['columns'] : 4,
					) );

					electro_wp_select( array( 
						'id'			=> '_home_v1_pcwi2_columns_wide', 
						'label' 		=>  esc_html__( 'Columns Wide', 'electro' ),
						'options'		=> array(
							'2'	=> '2',
							'3'	=> '3',
							'4'	=> '4',
							'5'	=> '5',
							'6'	=> '6',
						),
						'class'			=> 'columns_select',
						'default'		=> '4',
						'name'			=> '_home_v1[pcwi2][product_columns_wide]',
						'value'			=> isset( $home_v1['pcwi2']['product_columns_wide'] ) ? $home_v1['pcwi2']['product_columns_wide'] : 4,
					) );
                ?>
                </div>
            </div><!-- /#products_with_image_2 -->

			<div id="two_banners" class="panel electro_options_panel">

			<?php electro_wp_legend( esc_html__( 'Banner 1', 'electro' ) ); ?>

			<div class="options_group">
				<?php

					electro_wp_upload_image( array(
						'id'            => '_home_v1_tbrs_1_image',
						'label'         => esc_html__( 'Ad Image', 'electro' ),
						'name'          => '_home_v1[tbrs][0][image]',
						'value'         => isset( $home_v1['tbrs'][0]['image'] ) ? $home_v1['tbrs'][0]['image'] : '',
					) );

					electro_wp_text_input( array(
						'id'            => '_home_v1_tbrs_1_action_link',
						'label'         => esc_html__( 'Action Link', 'electro' ),
						'name'          => '_home_v1[tbrs][0][action_link]',
						'value'         => isset( $home_v1['tbrs'][0]['action_link'] ) ? $home_v1['tbrs'][0]['action_link'] : '#',
					) );
				?>
				</div>

				<?php electro_wp_legend( esc_html__( 'Banner 2', 'electro' ) ); ?>

				<div class="options_group">
				<?php

					electro_wp_upload_image( array(
						'id'            => '_home_v1_tbrs_2_image',
						'label'         => esc_html__( 'Ad Image', 'electro' ),
						'name'          => '_home_v1[tbrs][1][image]',
						'value'         => isset( $home_v1['tbrs'][1]['image'] ) ? $home_v1['tbrs'][1]['image'] : '',
					) );

					electro_wp_text_input( array(
						'id'            => '_home_v1_tbrs_2_action_link',
						'label'         => esc_html__( 'Action Link', 'electro' ),
						'name'          => '_home_v1[tbrs][1][action_link]',
						'value'         => isset( $home_v1['tbrs'][1]['action_link'] ) ? $home_v1['tbrs'][1]['action_link'] : '#',
					) );
				?>
				</div>
			</div><!-- /#two_banners -->

		</div>
		
		<?php
			$lstCategories = hangcu_get_list_categories();
		?>
		<script>
			const lstCategories = <?php echo json_encode($lstCategories) ?>;
		</script>
	<?php
	}

	public static function save( $post_id, $post ) {
		if ( isset( $_POST['_home_v1'] ) ) {
			$clean_home_v1_options = electro_clean_kses_post( $_POST['_home_v1'] );
			update_post_meta( $post_id, '_hangcu_home_v1_options',  serialize( $clean_home_v1_options ) );
		}	
	}
}