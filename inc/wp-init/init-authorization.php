<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Swipecart AuthGeneration and Activation Hook
 *
 * @package Swipecart
 * @author Manthan Kanani
 * @version 1.0.0
**/
class SC_AuthGeneration {

	/**
	 * Constructor for Swipecart Activation  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function __construct(){
		global $plugin_dir_file;

		$this->auth_token 			= NULL;	
		$this->auth_secret 			= NULL;
		$this->option_name 			= 'swipecart_auth';
		$this->randomStringLength 	= 64;

		register_activation_hook($plugin_dir_file, [ $this, 'createAuthCombo']);
	}

	/**
	 * Generate Auth Combo
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function createAuthCombo(){
		$utility 	= new Utility();

		$this->auth_token 	= $utility->generateRandStr($this->randomStringLength);
		$this->auth_secret 	= $utility->generateRandStr($this->randomStringLength);

		$option_value = array(
			"auth_token"  	=> $this->auth_token,
			"auth_secret"  	=> $this->auth_secret
		);

		if(!get_option($this->option_name)){
			update_option($this->option_name, $option_value);
		}
	}

	/**
	 * Update AuthCombo
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function updateAuthCombo(){
		$utility 	= new Utility();

		$this->auth_token 	= $utility->generateRandStr($this->randomStringLength);
		$this->auth_secret 	= $utility->generateRandStr($this->randomStringLength);

		$option_value = array(
			"auth_token"  	=> $this->auth_token,
			"auth_secret"  	=> $this->auth_secret
		);

		update_option($this->option_name, $option_value);
	}

	/**
	 * Remove AuthCombo will run on deletion of plugin
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function removeAuthCombo(){
		if(get_option($this->option_name)){
			delete_option($this->option_name);
		}
	}

	/**
	 * Retrive AuthCombo
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function getAuth($value=''){
		$auth_combo = get_option($this->option_name);
		if($auth_combo){
			if(in_array($value, array('token','secret'))){
				if($value=='token'){
					return $auth_combo['auth_token'];
				} else {
					return $auth_combo['auth_secret'];
				}
			} else {
				return $auth_combo;
			}
		}
		return false;
	}
}


