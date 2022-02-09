<?php

defined('ABSPATH') or die('No script kiddies please!');

/**
 * REST API Route List with functions...
 *
 * @package Swipecart
 * @author Manthan Kanani
 * @version 1.0.0
**/
class REST_API_v1_Controller {

	/**
	 * Setup path and define AuthCombo  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function __construct() {
		$this->namespace	= 'sc/v1';

		$auth_generation 	= new SC_AuthGeneration();
		$this->authCombo 	= $auth_generation->getAuth();
		$this->auth_token 	= $this->authCombo['auth_token'];
		$this->auth_secret 	= $this->authCombo['auth_secret'];
	}

	/**
	 * Get Product by Categories  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function getProductCategories($request){
		$api 		= new APIClass();
		$order 		= $request->get_param('order');
		$orderBy 	= $request->get_param('orderBy');
		$page 		= $request->get_param('page');
		$pp 		= $request->get_param('pp');

		$result = $api->getProductCategories($order, $orderBy, $page, $pp);
		if($result) {
			$arr = $result;
		} else {
			$arr = array("status" => false, "error" => "No Data !!");
		}
		return $arr;
	}

	/**
	 * Get Customer Orders  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function getCustomerOrders($request){
		$api 		= new APIClass();
		$customer 	= $request->get_param('customer');
		$order 		= $request->get_param('order');
		$orderBy 	= $request->get_param('orderBy');
		$page 		= $request->get_param('page');
		$pp 		= $request->get_param('pp');

		$result = $api->getCustomerOrders($order, $orderBy, $page, $pp, $customer);
		if($result) {
			$arr = $result;
		} else {
			$arr = array("status" => false, "error" => "No Data !!");
		}
		return $arr;
	}

	/**
	 * Get All Products with search,category,tag filter  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function getProducts($request){
		$api 			= new APIClass();
		$category 		= $request->get_param('category');
		$category 		= ($category)? $category : array() ;
		$order 			= $request->get_param('order');
		$orderBy 		= $request->get_param('orderBy');
		$page 			= $request->get_param('page');
		$pp 			= $request->get_param('pp');
		$search 		= $request->get_param('search');

		$result = $api->getProducts($order, $orderBy, $page, $pp, $category, $search);
		if($result) {
			$arr = $result;
		} else {
			$arr = array("status" => false, "error" => "No Data !!");
		}
		return $arr;
	}

	/**
	 * Get Single Product  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function getProduct($request){
		$api 		= new APIClass();
		$id 		= $request->get_param('id');

		$result = $api->getProductByID($id, false);
		if($result) {
			$arr = $result;
		} else {
			$arr = array("status" => false, "error" => "No Data !!");
		}
		return $arr;
	}

	/**
	 * generate Access Token by username and password  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function getToken($request){
		$api 		= new APIClass();
		$username 	= $request->get_param('username');
		$password 	= $request->get_param('password');

		$result = $api->getToken($username, $password);
		if($result) {
			$arr = $result;
		} else {
			$arr = array("status" => false, "error" => "No Data !!");
		}
		return $arr;
	}

	/**
	 * register User  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function createCustomer($request){
		$api 		= new APIClass();
		$email 		= $request->get_param('email');
		$first_name = $request->get_param('first_name');
		$last_name 	= $request->get_param('last_name');
		$username 	= $request->get_param('username');
		$password 	= $request->get_param('password');

		$result = $api->createCustomer($email, $first_name, $last_name, $username, $password);
		if($result) {
			$arr = $result;
		} else {
			$arr = array("status" => false, "error" => "No Data !!");
		}
		return $arr;
	}

	/**
	 * Retrive Customer  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function getCustomer($request){
		$api 		= new APIClass();
		$id 		= $request->get_param('id');

		$result = $api->getCustomerByID($id, false);
		if($result) {
			$arr = $result;
		} else {
			$arr = array("status" => false, "error" => "No Data !!");
		}
		return $arr;
	}

	/**
	 * Get Myself  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function getMyself($request){
		$api 				= new APIClass();
		$authorization 		= $request->get_header('Authorization');
		$auth_token			= str_replace('Bearer ','',$authorization);
		if($user = $api->tokenValidation($auth_token)){
			if($result = $api->getCustomerByID($user->ID, true)){
				$arr = $result;
			} else {
				$arr = array("status" => false, "error" => "Invalid User !!");
			}
		} else {
			$arr = array("status" => false, "error" => "Invalid Token !!");
		}
		return $arr;
	}

	/**
	 * Verify Authorization  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function verifyAuthorization($request){
		return array("status" => true);
	}


	









	/*************************************************************
	**************************************************************
	** double curly bracket {{field}} is required field  
	** single curly bracket {field} is not mendetory each time
	** Intitial url : /wp-json/
	**
	** @header ConsumerKey
	** @header ConsumerSecret
	** @author Manthan Kanani
	**/
	public function register_routes() {
		/**
		 * Get Product Categories of product
		 * sc/v1/products/categories
		 *
		 * @param (str) {order}		=> ('asc'||'desc')
		 * @param (str) {orderBy}	=> ('id'||'name'||'popularity')
		 * @param (int) {page}		=> ({}>0)
		 * @param (int) {pp}		=> ({}>=0)
		 *
		 * @return json
		 */
		register_rest_route($this->namespace, 'products/categories', [
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'getProductCategories'),
			'permission_callback' 	=> array( $this, 'verifyHeaderPermissionCallback' ),
			'args' => array(
				'order' => array(
					'required' 			=> false,
					'sanitize_callback'	=> array($this, 'orderSanitizeCallback'),
					'validate_callback'	=> array($this, 'orderValidateCallback'),
				),
				'orderBy' => array(
					'required' 			=> false,
					'sanitize_callback'	=> array($this, 'orderBySanitizeCallback'),
					'validate_callback'	=> array($this, 'orderByValidateCallback'),
				),
				'page' => array(
					'default' 			=> 1,
					'sanitize_callback'	=> 'absint',
					'validate_callback'	=> array($this, 'numericPositiveValidateCallback'),
				),
				'pp' => array(
					'default' 			=> 10,
					'sanitize_callback'	=> 'absint',
					'validate_callback'	=> 'rest_is_integer',
				)
			)
		]);


		/**
		 * Get Woocommerce Customer Orders
		 * sc/v1/orders
		 *
		 * @param (int) {customer}	=> ({}>0)
		 * @param (int) {order}		=> ('asc'||'desc')
		 * @param (int) {orderBy}	=> ('id'||'name'||'popularity')
		 * @param (int) {page}		=> ({}>0)
		 * @param (int) {pp}		=> ({}>=0)
		 *
		 * @return json
		 */
		register_rest_route($this->namespace, 'orders', [
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'getCustomerOrders'),
			'permission_callback' 	=> array( $this, 'verifyHeaderPermissionCallback' ),
			'args' => array(
				'customer' => array(
					'default' 			=> 0,
					'sanitize_callback'	=> 'absint',
					'validate_callback'	=> 'rest_is_integer',
				),
				'order' => array(
					'required' 			=> false,
					'sanitize_callback'	=> array($this, 'orderSanitizeCallback'),
					'validate_callback'	=> array($this, 'orderValidateCallback'),
				),
				'orderBy' => array(
					'required' 			=> false,
					'sanitize_callback'	=> array($this, 'orderBySanitizeCallback'),
					'validate_callback'	=> array($this, 'orderByValidateCallback'),
				),
				'page' => array(
					'default' 			=> 1,
					'sanitize_callback'	=> 'absint',
					'validate_callback'	=> array($this, 'numericPositiveValidateCallback'),
				),
				'pp' => array(
					'default' 			=> 10,
					'sanitize_callback'	=> 'absint',
					'validate_callback'	=> 'rest_is_integer',
				)
			)
		]);


		/**
		 * Get Product by Category ID or All
		 * sc/v1/products
		 *
		 * @param (int) {category}	=> ({csv}>0)
		 * @param (int) {order}		=> ('asc'||'desc')
		 * @param (int) {orderBy}	=> ('id'||'name'||'popularity')
		 * @param (int) {page}		=> ({}>0)
		 * @param (int) {pp}		=> ({}>=0)
		 * @param (int) {search}	=> (||)
		 *
		 * @return json
		 */
		register_rest_route($this->namespace, 'products', [
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'getProducts'),
			'permission_callback' 	=> array( $this, 'verifyHeaderPermissionCallback' ),
			'args' => array(
				'category' => array(
					'required' 			=> false,
					'sanitize_callback'	=> array($this, 'returnCSVArraySanitizeCallback'),
					'validate_callback'	=> array($this, 'numericCSVValidateCallback'),
				),
				'order' => array(
					'default' 			=> 'desc',
					'sanitize_callback'	=> array($this, 'orderSanitizeCallback'),
					'validate_callback'	=> array($this, 'orderValidateCallback'),
				),
				'orderBy' => array(
					'default' 			=> 'id',
					'sanitize_callback'	=> array($this, 'orderBySanitizeCallback'),
					'validate_callback'	=> array($this, 'orderByValidateCallback'),
				),
				'page' => array(
					'default' 			=> 1,
					'sanitize_callback'	=> 'absint',
					'validate_callback'	=> array($this, 'numericPositiveValidateCallback'),
				),
				'pp' => array(
					'default' 			=> 10,
					'sanitize_callback'	=> 'absint',
					'validate_callback'	=> 'rest_is_integer',
				),
				'search' => array('required' => false)
			)
		]);


		/**
		 * Get Product by ID
		 * sc/v1/product/:id
		 *
		 * @param (int) {id}	=> ({}>0)
		 *
		 * @return json
		 */
		register_rest_route($this->namespace, 'product/(?P<id>\d+)', [
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'getProduct'),
			'permission_callback' 	=> array( $this, 'verifyHeaderPermissionCallback' ),
			'args' => array(
				'id' => array(
					'required' 			=> true,
					'sanitize_callback'	=> 'absint',
					'validate_callback'	=> array($this, 'numericPositiveValidateCallback'),
				)
			)
		]);


		/**
		 * Get User token after Successfully loggedin
		 * sc/v1/token
		 *
		 * @param (int) {{username}}	=> (!='')
		 * @param (int) {{password}}	=> (!='')
		 *
		 * @return json
		 */
		register_rest_route($this->namespace, 'token', [
			'methods' 				=> 'POST',
			'callback' 				=> array( $this, 'getToken'),
			'permission_callback' 	=> '__return_true',
			'args' => array(
				'username' => array(
					'required' 			=> true,
					'sanitize_callback'	=> 'sanitize_email',
					'validate_callback'	=> 'is_email',
				),
				'password' => array(
					'required' 			=> true,
					'validate_callback'	=> array($this, 'textRequiredValidateCallback'),
				)
			)
		]);

		/**
		 * Create Customer for Woocommerce
		 * sc/v1/customer
		 *
		 * @param (str) {{email}}		=> (!='')
		 * @param (str) {{first_name}}	=> (!='')
		 * @param (str) {{last_name}}	=> (!='')
		 * @param (str) {{username}}	=> (!='')
		 * @param (str) {{password}}	=> (!='')
		 *
		 * @return json
		 */
		register_rest_route($this->namespace, 'customer', [
			'methods' 				=> 'POST',
			'callback' 				=> array( $this, 'createCustomer'),
			'permission_callback' 	=> '__return_true',
			'args' => array(
				'email' => array(
					'required' 			=> true,
					'sanitize_callback'	=> 'sanitize_email',
					'validate_callback'	=> 'is_email',
				),
				'first_name' => array(
					'required' 			=> true,
					'sanitize_callback'	=> 'sanitize_text_field',
					'validate_callback'	=> array($this, 'textRequiredValidateCallback'),
				),
				'last_name' => array(
					'required' 			=> true,
					'sanitize_callback'	=> 'sanitize_text_field',
					'validate_callback'	=> array($this, 'textRequiredValidateCallback'),
				),
				'username' => array(
					'required' 			=> true,
					'sanitize_callback'	=> 'sanitize_user',
					'validate_callback'	=> array($this, 'textRequiredValidateCallback'),
				),
				'password' => array(
					'required' 			=> true,
					'validate_callback'	=> array($this, 'textRequiredValidateCallback'),
				)
			)
		]);


		/**
		 * Get Customer by ID
		 * sc/v1/customer/:id
		 *
		 * @param (int) {id}	=> ({}>0)
		 *
		 * @return json
		 */
		register_rest_route($this->namespace, 'customer/(?P<id>\d+)', [
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'getCustomer'),
			'permission_callback' 	=> array( $this, 'verifyHeaderAndTokenPermissionCallback' ),
			'args' => array(
				'id' => array(
					'required' 			=> true,
					'sanitize_callback'	=> 'absint',
					'validate_callback'	=> array($this, 'numericPositiveValidateCallback'),
				)
			)
		]);


		/**
		 * Get Mys Own Profile Data
		 * sc/v1/user/me
		 *
		 * @param NULL
		 *
		 * @return json
		 */
		register_rest_route($this->namespace, 'user/me', [
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'getMyself'),
			'permission_callback' 	=> array( $this, 'verifyHeaderAndTokenPermissionCallback' ),
		]);

		/**
		 * Test Authorization
		 * sc/v1/verify/authorization
		 *
		 * @param NULL
		 *
		 * @return json
		 */
		register_rest_route($this->namespace, 'verify/authorization', [
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'verifyAuthorization'),
			'permission_callback' 	=> array( $this, 'verifyHeaderAndTokenPermissionCallback' ),
		]);
	}








	/**
	 * Verify request Header Callbacks
	 *
	 * @return bool
	**/
	public function verifyHeaderPermissionCallback(WP_REST_Request $request){
		$key  		= $request->get_header('ConsumerKey');
		$secret  	= $request->get_header('ConsumerSecret');
		if($key == $this->auth_token && $secret == $this->auth_secret) return true;
		return false;
	}
	public function verifyHeaderAndTokenPermissionCallback(WP_REST_Request $request){
		$api 				= new APIClass();
		$key  				= $request->get_header('ConsumerKey');
		$secret  			= $request->get_header('ConsumerSecret');
        $authorization 		= $request->get_header('Authorization');
        $auth_token			= str_replace('Bearer ','',$authorization);

        if($key == $this->auth_token && $secret == $this->auth_secret) return true;
        if($api->tokenValidation($auth_token)) return true;
        return false;
	}

	/**
	 * Sanitize Callbacks
	 *
	 * @return filtered Value
	**/
	public function returnCSVArraySanitizeCallback($param){
		$param = explode(',', $param);
		$param = array_map('intval', $param);
		return $param;
	} 
	public function orderSanitizeCallback($param){
		return (in_array($param, array('desc', 'asc')))? $param : 'desc' ;
	}
	public function orderBySanitizeCallback($param){
		return (in_array($param, array('id','name','popularity','date')))? $param : 'id';
	}

	/**
	 * Validate Callbacks
	 *
	 * @return bool
	**/
	public function numericPositiveValidateCallback($param){
		return (is_numeric($param) && ($param>0));
	}
	public function numericCSVValidateCallback($param){
		$param = explode(',', $param);
		foreach($param as $a) {
			if (!is_numeric($a)) return false;
		}
		return true;
	}
	public function textRequiredValidateCallback($param){
		return (isset($param) && !empty($param));
	}
	public function ratingMaxFiveValidateCallback($param){
		return (is_numeric($param) && ($param>=0 && $param<=5));
	}
	public function orderValidateCallback($param){
		$orders = array('id','name','popularity','date');
		$param = strtolower($param);
		return in_array($param, $orders);
	}
	public function orderByValidateCallback($param){
		return in_array($param, ['desc', 'asc']);
	}
}

/**
 * Initialize Rest API Route  
 *
 * @author Manthan Kanani	
 * @version 1.0.0
**/
add_action('rest_api_init', function(){
	$controller = new REST_API_v1_Controller();
	$controller->register_routes();
});