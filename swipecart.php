<?php
/**
 *
 * Plugin Name: Swipecart
 * Plugin URI: https://rentechdigital.com/swipecart
 * Description: Launch a world-class mobile app for your brand. <code><strong>Please, Don't Remove this plugin</strong>, This may affect your Swipecart App.</code> 
 * Version: 1.0.0 
 * Requires at least: 4.9
 * Requires PHP: 7.4
 * Author: Rentech Digital
 * Author URI: https://profiles.wordpress.org/manthankanani/
 * Text Domain: swipecart
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
**/

defined('ABSPATH') or die('No script kiddies please!');

/**
 * Intial Class for Swipecart
 *
 * @package Swipecart
 * @author Manthan Kanani
 * @version 1.0.0
**/
Class Swipecart {

	/** 
	 * Construct Development
	 *
	 * @author Manthan Kanani
	 * @version 1.0.0 
	**/
	function __construct(){
		$this->__include_files();
		$this->__init();

		add_action( 'tgmpa_register', [ $this, '_required_plugins' ] );
	}

	/** 
	 * Inclusion of files
	 *
	 * @author Manthan Kanani
	 * @version 1.0.0 
	**/
	public function __include_files(){
		global $plugin_dir_path, $plugin_dir_file;
		$plugin_dir_path = dirname(__FILE__);
		$plugin_dir_file = __FILE__;

		require_once($plugin_dir_path . '/inc/wp-init/init-authorization.php');
		require_once($plugin_dir_path . '/inc/wp-init/tgmpa.php');
		require_once($plugin_dir_path . '/inc/wp-init/integrations/integrations.php');

		require_once($plugin_dir_path . '/inc/Utility.php');
		require_once($plugin_dir_path . '/inc/class/class-checkout.php');
		require_once($plugin_dir_path . '/inc/class/class-api.php');
		require_once($plugin_dir_path . '/inc/class/class-webservices.php');
	}

	/** 
	 * First Run of Application
	 *
	 * @author Manthan Kanani
	 * @version 1.0.0 
	**/
	public function __init(){
		global $plugin_dir_file;

		new SC_AuthGeneration();

		register_uninstall_hook($plugin_dir_file, 'uninstallSwipecart');
	}

	/** 
	 * Install Required Plugin for Swipecart
	 *
	 * @author Manthan Kanani
	 * @version 1.0.0 
	**/
	function _required_plugins() {
		$plugins = array(
			array(
				'name'      => 'WooCommerce',
				'slug'      => 'woocommerce',
				'required'  => true,
			),
		);

		$config = array(
			'id'           => 'swipecart',
			'default_path' => '', 
			'menu'         => 'tgmpa-install-plugins',
			'parent_slug'  => 'plugins.php',
			'capability'   => 'manage_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
		);
		tgmpa( $plugins, $config );
	}
}
new Swipecart();

/** 
 * Uninstallation of the plugin
 *
 * @author Manthan Kanani
 * @version 1.0.0 
**/
function uninstallSwipecart(){
	$authGeneration = new SC_AuthGeneration();
	$authGeneration->removeAuthCombo();
}