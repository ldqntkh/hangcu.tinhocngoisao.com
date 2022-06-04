<?php
/**
 * CT Electro Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Electro_Admin class.
 */
class CT_Electro_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Include any classes we need within admin
	 */
	public function includes() {
		include_once( 'hangcu-class-electro-admin-meta-boxes.php' );
		$this->load_meta_boxes();
	}

	public function load_meta_boxes() {
		include_once( 'meta-boxes/hangcu-class-electro-meta-box-home-v1.php' );
		include_once( 'meta-boxes/hangcu-order-meta-box.php' );
	}

	/**
	 * Enqueue styles.
	 */
	public function admin_styles() {
		wp_register_style( 'hangcu_admin_style', get_stylesheet_directory_uri() . '/assets/admin/styles/custom-admin-style.css', array(), '1.0.0' );
		
		wp_enqueue_style( 'hangcu_admin_style' );
	}

	/**
	 * Enqueue scripts.
	 */
	public function admin_scripts() {
		wp_register_script( 'hangcu_admin_script_dragonfly', get_stylesheet_directory_uri() . '/assets/admin/javascripts/dragonfly.js', array( 'jquery'), '1.0.0' );
		wp_register_script( 'hangcu_admin_script', get_stylesheet_directory_uri() . '/assets/admin/javascripts/app.js', array( 'jquery'), '1.0.0' );
		wp_enqueue_script( 'hangcu_admin_script_dragonfly' );		
		wp_enqueue_script( 'hangcu_admin_script' );		
	}
}

return new CT_Electro_Admin();