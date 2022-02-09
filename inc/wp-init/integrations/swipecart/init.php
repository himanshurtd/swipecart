<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Swipecart Integration Initialization
 *
 * @package Swipecart
 * @author Manthan Kanani
 * @version 1.0.0
**/
class WC_SC_Integrations{

	/**
	 * constructor for the integration  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function __construct(){
		add_action('plugins_loaded', array($this, 'wcIntegrations'));
	}

	/**
	 * Initialize the plugin  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function wcIntegrations() {
		if(class_exists('WC_Integration')){
			require_once('integration-swipecart.php');
			add_filter('woocommerce_integrations', array($this, 'addSwipecartIntegrationTab'));
		} else {
			return;
		}
	}

	/**
	 * Swipecart Add secondary tab  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function addSwipecartIntegrationTab($integrations){
		$integrations[] 	= 'WC_Swipecart_Integration';
		return $integrations;
	}
}

new WC_SC_Integrations();