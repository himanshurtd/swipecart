<?php

defined('ABSPATH') or die('No script kiddies please!');

/**
 * Swipecart Checkout Initialization
 *
 * @package Swipecart
 * @author Manthan Kanani
 * @version 1.0.0
**/
class Swipecart_Checkout{

	/**
	 * Constructor cart and checkout after redirection  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	function __construct(){	
		add_action('wp',[ $this, '_add_to_cart' ]);
	}

	/**
	 * Add to cart and checkout Process  
	 *
	 * @author Manthan Kanani	
	 * @version 1.0.0
	**/
	function _add_to_cart(){
		if (!is_user_logged_in() && is_checkout() && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['data'])){
			WC()->cart->empty_cart();
			// $body = file_get_contents('php://input');
			$body = sanitize_text_field($_GET['data']);
			$body = base64_decode($body);
			if (json_last_error() === 0) {
				$datas = json_decode($body, TRUE);

				foreach($datas as $key => $data){
					$product_cart_id = WC()->cart->generate_cart_id( $data['id'] );
					if($data['variation'] == false){
						if(!WC()->cart->find_product_in_cart( $product_cart_id )) {  
							WC()->cart->add_to_cart( $data['id'], $data['quantity'] );
						}
					}
			    }
			}
		}
	}
}
new Swipecart_Checkout();