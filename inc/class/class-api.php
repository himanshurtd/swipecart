<?php

defined('ABSPATH') or die('No script kiddies please!');

/**
 * APIClass to Read Data for API
 * 
 * @package Swipecart
 * @author Manthan Kanani
 * @version 1.0.0
**/
class APIClass{

	/**
	 * Constructor for AuthCombo  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function __construct(){
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
	static public function getProductCategories($order='desc', $orderBy='id', $page=1, $pp=10){
		$result = array();

		$offset = ($pp * $page) - $pp;
		$args = array(
			'orderby'           => $orderBy,
			'order'             => $order,
			'hide_empty'        => false,
			'posts_per_page'    => $pp,
			'paged'             => $page
		);

		$categories = get_terms("product_cat", $args);

		$result = array_map(function($term) {
			$thumb_id   = get_term_meta($term->term_id, 'thumbnail_id', true);
			$img 		= ($thumb_id) ? wp_get_attachment_image_src($thumb_id,'full')[0] : wc_placeholder_img_src('full') ;

			return array(
				'id'        	=> $term->term_id,
				'name'      	=> $term->name,
				'slug'      	=> $term->slug,
				'description'	=> $term->description,
				'image'     	=> $img,
				'count'     	=> $term->count,
				'parent'      	=> $term->parent,
			);
		}, $categories);

		return $result;
	}

	/**
	 * Get Product by ID  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	static public function getProductByID($product_id, $is_really_product=true){
		if(!$is_really_product){
			if(!get_post($product_id) || (get_post($product_id)->post_type!='product')) {
				return false;
			}
		}
		$terms 				= array();
		$tags 				= array();
		$product_images 	= array();
		$product_attribute 	= array();
		$product 			= wc_get_product( $product_id );

		$product_terms 	= get_the_terms($product_id,'product_cat');
		$product_tags 	= get_the_terms($product_id,'product_tag');

		if($product_terms){
			foreach(get_the_terms($product_id,'product_cat') as $term){
				$terms[] = array(
					'id'	=> $term->term_id,
					'name'	=> $term->name,
					'slug'	=> $term->slug,
				);
			}
		}

		if($product_tags){
			foreach(get_the_terms($product_id,'product_tag') as $tag){
				$tags[] = array(
					'id'	=> $tag->term_id,
					'name'	=> $tag->name,
					'slug'	=> $tag->slug,
				);
			}
		}

		$gallery_images 	= ($product->get_gallery_image_ids())? : array() ;
		$feature_image 		= ($product->get_image_id())? array($product->get_image_id()) : array() ;
		$images 			= array_unique(array_merge($feature_image, $gallery_images));
		if($images){
			foreach($images as $image_id){
				$img_src = wp_get_attachment_image_src( $image_id, 'full' )[0];

				$product_images[] = array(
					'id' => $image_id,
					'src' => $img_src,
				);
			}
		}

		if($product->is_type('variation')) {
			foreach($product->get_variation_attributes() as $attribute_name=>$attribute) {
				$product_attribute[] = array(
					'name'   => wc_attribute_label(str_replace('attribute_', '', $attribute_name)),
					'slug'   => str_replace('attribute_', '', wc_attribute_taxonomy_slug($attribute_name)),
					'option' => $attribute,
				);
			}
		} else {
			foreach($product->get_attributes() as $attribute) {

				if(isset($attribute['is_taxonomy']) && $attribute['is_taxonomy']){
					$options = wc_get_product_terms($product->get_id(), $attribute['name'], array('fields' => 'names'));
				} elseif (isset($attribute['value'])) {
					$options = array_map('trim', explode('|', $attribute['value']));
				} else {
					$options = array();
				}

				$product_attribute[] = array(
					'name'      => wc_attribute_label($attribute['name']),
					'slug'      => wc_attribute_taxonomy_slug($attribute['name']),
					'position'  => (int) $attribute['position'],
					'visible'   => (bool) $attribute['is_visible'],
					'variation' => (bool) $attribute['is_variation'],
					'options'   => $options,
				);
			}
		}

		return array(
			'id' 					=> $product_id,
			'name'					=> $product->get_name(),
			'slug'					=> $product->get_slug(),
			'permalink' 			=> get_permalink($product_id),
			'date_created' 			=> $product->get_date_created()->date('Y-m-d H:i:s'), 
			'date_modified'			=> $product->get_date_modified()->date('Y-m-d H:i:s'),
			'type'					=> $product->get_type(),
			'status'				=> $product->get_status(),
			'description'			=> $product->get_description(),
			'short_description'		=> $product->get_short_description(),
			'sku'					=> $product->get_sku(),
			'price'					=> $product->get_price(),
			'regular_price'			=> $product->get_regular_price(),
			'sale_price'			=> $product->get_sale_price(),
			'on_sale'				=> $product->is_on_sale(),
			'purchasable' 			=> $product->is_purchasable(),
			'total_sales' 			=> $product->get_total_sales(),
			'virtual' 				=> $product->is_virtual(),
			'manage_stock'			=> $product->get_manage_stock(),
			'stock_quantity'		=> $product->get_stock_quantity(),
			'average_rating'		=> $product->get_average_rating(),
			'rating_count'			=> $product->get_rating_counts(),
			'categories'			=> $terms,
			'tags'					=> $tags,
			'images'				=> $product_images,
			'attributes'			=> $product_attribute,
			'stock_status'			=> $product->get_stock_status()
		);
	}

	/**
	 * Get Customer Orders  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	static public function getCustomerOrders($order='desc', $orderBy='id', $page=1, $pp=10, $customer_id=0){
		$result 		= array();
		$line_items 	= array();

		$args = array(
			'limit' 		=> 10,
			'orderby' 		=> $orderBy,
			'order' 		=> $order,
			'return' 		=> 'ids',
			'limit' 		=> $pp,
			'paged' 		=> $page
		);
		if($customer_id) $args['customer_id'] = $customer_id;

		$orders 	= wc_get_orders($args);


		$result = array_map(function($id) {
			$order 			= wc_get_order($id);
			$order_data 	= $order->get_data();

			foreach($order->get_items() as $item){
				$product      	= $item->get_product();

				$line_items[] = array(
					'id' 			=> $item->get_id(),
					'name' 			=> $item->get_name(),
					'product_id' 	=> $item->get_product_id(),
					'variation_id' 	=> $item->get_variation_id(),
					'quantity' 		=> $item->get_quantity(),
					'tax_class' 	=> $item->get_tax_class(),
					'subtotal' 		=> $item->get_subtotal(),
					'subtotal_tax' 	=> $item->get_subtotal_tax(),
					'total' 		=> $item->get_total(),
					'total_tax' 	=> $item->get_total_tax(),
					'sku' 			=> $product->get_sku(),
					'price' 		=> $product->get_price()
				);
			}

			return array(
				'id'					=> $id,
				'status'				=> $order_data['status'],
				'currency'				=> $order_data['currency'],
				'date_created'			=> $order_data['date_created']->date('Y-m-d H:i:s'),
				'date_modified'			=> $order_data['date_modified']->date('Y-m-d H:i:s'),
				'discount_total'		=> $order_data['discount_total'],
				'discount_tax'			=> $order_data['discount_tax'],
				'shipping_total'		=> $order_data['shipping_total'],
				'shipping_tax'			=> $order_data['shipping_tax'],
				'cart_tax'				=> $order_data['cart_tax'],
				'total'					=> $order_data['total'],
				'total_tax'				=> $order_data['total_tax'],
				'customer_id'			=> $order_data['customer_id'],
				'billing'				=> $order_data['billing'],
				'shipping'				=> $order_data['shipping'],
				'payment_method'		=> $order_data['payment_method'],
				'payment_method_title'	=> $order_data['payment_method_title'],
				'transaction_id'		=> $order_data['transaction_id'],
				'line_items'			=> $line_items,

			);
		}, $orders);

		return $result;
	}

	/**
	 * Get All Products with search,category,tag filter  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	static public function getProducts($order='desc', $orderBy='id', $page=1, $pp=10, $category_ids=array(), $search=''){
		$result 		= array();

		$args = array(
			'post_type' 		=> 'product',
			'posts_per_page'    => $pp,
			'paged'             => $page,
			'post_status' 		=> 'publish',
			'fields' 			=> 'ids',
			'orderby' 			=> $orderBy,
			'order' 			=> $order
		);
		if($search){
			$args['s'] = $search;
		}

		if($category_ids){
			$args['tax_query'] 	= array(
				array(
					'taxonomy'	=> 'product_cat',
					'field' 	=> 'term_id',
					'terms' 	=> $category_ids, 
					'operator' 	=> 'IN',
				)
			);
		}

		$products 	= get_posts($args);

		$result = array_map(function($product_id) {
			return APIClass::getProductByID($product_id);
		}, $products);

		return $result;
	}

	/**
	 * Get User Email Exstance  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	static public function checkUserEmailExist($email) {
		$login = get_user_by('login', $email);
		$email = get_user_by('email', $email);
		return ($login || $email) ? $email : false ;
	}

	/**
	 * Token string must be in this way :- email,password,auth_token,auth_secret,timestring,random  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function tokenValidation($value){
		$utility  		= new Utility();
		$separator 		= '~';
		$string_count 	= 6;
		$expiry_day		= 30;
		$random_length 	= 10;

		if(gettype($value)=='array'){
			// Process of Encryption
			$random 		= $utility->generateRandStr($random_length);
			$main_string 	= $value;

			
			$main_string[] 	= $this->auth_token;
			$main_string[] 	= $this->auth_secret;
			$main_string[] 	= date('Y-m-d H:i:s');
			$main_string[] 	= $random;

			if(count($main_string)!==$string_count) return false;

			$encode = array_map("urlencode", $main_string);
			$string = implode($separator, $encode);

			$key = $utility->encrypt_decrypt($string, 'encrypt');

			return $key;

		} elseif(gettype($value)=='string') {
			// Process of Decryption
			$string = $utility->encrypt_decrypt($value, 'decrypt');

			$decode = explode($separator, $string);
			$main_string = array_map("urldecode", $decode);

			if(count($main_string)!==$string_count) return false;

			$email 			= $main_string[0];
			$password 		= $main_string[1];
			$auth_token 	= $main_string[2];
			$auth_secret 	= $main_string[3];
			$timestring 	= $main_string[4];
			$random 		= $main_string[5];

			if($auth_token 	!= $this->auth_token) return false;
			if($auth_secret != $this->auth_secret) return false;
			if(strtotime($timestring) < strtotime('-'.$expiry_day.' days')) return false;

			$creds = array(
				'user_login' => $email,
				'user_password' => $password
			);

			$user = wp_signon($creds, false);
			if(!is_wp_error($user)){
				return $user;
			}
			return false;
		}
	}

	/**
	 * generate Access Token by username and password  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function getToken($username, $password){
		$result 		= array();
		$utility  		= new Utility();

		if($this->checkUserEmailExist($username)){
			$creds['user_login'] 	= $username;
			$creds['user_password'] = $password;

			$user = wp_signon($creds, false);
			if(!is_wp_error($user)){
				$username  			= $user->user_login;
				$user_email  		= $user->user_email;
				$user_nicename  	= $user->user_nicename;
				$display_name  		= $user->display_name;
				$password   		= $password;

				$token = $this->tokenValidation(array($user_email, $password));

				$result = array(
					'id'				=> $user->ID,
					'token'				=> $token,
					'user_email' 		=> $user_email,
					'user_nicename'  	=> $user_nicename,
					'user_display_name' => $display_name
				);
			} 
		}
		return $result;
	}

	/**
	 * Get Customer by ID  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function getCustomerByID($user_id, $full_data=false){
		$user 		= get_userdata($user_id);
		if(!$user) return false;

		$customer 	= new WC_Customer($user_id);
		$billing = array(
			"first_name" 	=> $customer->get_billing_first_name(),
			"last_name" 	=> $customer->get_billing_last_name(),
			"company" 		=> $customer->get_billing_company(),
			"address_1" 	=> $customer->get_billing_address_1(),
			"address_2" 	=> $customer->get_billing_address_2(),
			"city" 			=> $customer->get_billing_city(),
			"state" 		=> $customer->get_billing_state(),
			"postcode" 		=> $customer->get_billing_postcode(),
			"country" 		=> $customer->get_billing_country(),
			"email" 		=> $customer->get_billing_email(),
			"phone" 		=> $customer->get_billing_phone()
		);
		$shipping = array(
			"first_name" 	=> $customer->get_shipping_first_name(),
			"last_name" 	=> $customer->get_shipping_last_name(),
			"company" 		=> $customer->get_shipping_company(),
			"address_1" 	=> $customer->get_shipping_address_1(),
			"address_2" 	=> $customer->get_shipping_address_2(),
			"city" 			=> $customer->get_shipping_city(),
			"state" 		=> $customer->get_shipping_state(),
			"postcode" 		=> $customer->get_shipping_postcode(),
			"country" 		=> $customer->get_shipping_country(),
			"phone" 		=> $customer->get_shipping_phone()
		);

		$date_created 	= $customer->get_date_created()->date('Y-m-d H:i:s');
		$date_modified 	= ($customer->get_date_modified()) ? $customer->get_date_modified()->date('Y-m-d H:i:s') : $date_created ;

		$userdata = array(
			"id"					=> $user_id,
			"email"					=> $customer->get_email(),
			"first_name"			=> $customer->get_first_name(),
			"last_name"				=> $customer->get_last_name(),
			"avatar_url"			=> $customer->get_avatar_url()
		);

		if($full_data){
			$userdata["date_created"] = $date_created;
			$userdata["date_modified"] = $date_modified;
			$userdata["role"] = $customer->get_role();
			$userdata["username"] = $customer->get_username();
			$userdata["billing"] = $billing;
			$userdata["shipping"] = $shipping;
			$userdata["is_paying_customer"] = $customer->get_is_paying_customer();
		}

		return $userdata;
	}	

	/**
	 * register User  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	public function createCustomer($email, $fname, $lname, $username, $password){
		$result 		= array();
		$userdata 	= array(
			'user_email'    => $email,
			'user_pass'     => $password,
			'role'          => 'customer',
			'user_login'    => $username,
			'user_nicename' => $fname.$lname,
			'display_name'	=> $fname.$lname,
			'first_name'    => $fname,
			'last_name'     => $lname,
		);

		$user_id = wp_insert_user($userdata);
		if(!is_wp_error($user_id)){
			$result = $this->getCustomerByID($user_id);
		} else {
			$result = array( 'status'=> false, 'message'=>'Unable to create customer' );
		}
		return $result;
	}
}