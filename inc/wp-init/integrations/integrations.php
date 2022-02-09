<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Swipecart Third-Party Integration
 *
 * @package Swipecart
 * @author Manthan Kanani
 * @version 1.0.0
**/
class Swipecart_Integrations {

	/**
	 * Constructor for Include files  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	function __construct(){
		$this->__include_files();
	}

	/**
	 * Inclusion of files  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function __include_files(){
		require_once(dirname(__FILE__).'/swipecart/init.php');
	}
}
new Swipecart_Integrations();