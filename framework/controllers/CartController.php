<?php
/**
*  Cart Controller code.
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'extends/GeneralSession.php' );

class CartController extends RestController {

   	public function __construct() {
      	parent::__construct();      
   	}


   	// This is a api fake
   	// TODO Watting implement.
   	/**
   	*	POST: /cart
   	*  	Add product to cart. 
   	*
   	*  	@param $this->receiver array Struct of array( 'products' => array(  
   	*								array('id' => <product id 1>, 'amount' => <amount>),
   	*								array('id' => <product id 2>, 'amount' => <amount>),
   	*								array('id' => <product id 3>, 'amount' => <amount>)
   	*							)
   	*/
   	public function appendProduct() {
      
      	try {
      		if(array_key_exists("products", $this->receiver)) {
      			$products = $this->receiver['products'];
      			if(is_array($products)) {
      				$session = GeneralSession::getInstance();
      				$cart = $session->cart;

      				if(!is_array($cart)) {
      					$cart = array();
      				}

      				foreach($products as $key => $product) {
      					if(array_key_exists("productId", $product) &&
	      				 array_key_exists("amount", $product)) {
			  				array_push($cart, $product);			  				
	      				}	      				
      				}	

      				$session->cart = $cart;
      				$this->responser->send(array("cart" => $session->cart), $this->responser->Created());  				
	      		}
	      		else {
	      			$this->responser->send(array("message" => "products should be json array"), $this->responser->BadRequest());
	      		}	
      		}
      		else {
      			$this->responser->send(array("message" => "Missing parameter products:[]"), $this->responser->BadRequest());
      		}      		        
    	}
      	catch ( Exception $e) {
         	// $data['message'] = SERVER_ERROR_MSG;
         	$data['message'] = $e->getMessage();
         	$this->responser->send( $data, $this->responser->InternalServerError() );
      	}
   	}

   	// This is a api fake
   	// TODO Watting implement
   	/**
   	*	GET: /cart/payment/<type:\w+>
   	*  	Add product to cart. 
   	*
   	*  	@param $type string The type of payment. (wechat pay, 支付寶)
   	*/
   	public function getPayment($type) {
      
      	try {
      		      			
    	}
      	catch ( Exception $e) {
         	$data['message'] = SERVER_ERROR_MSG;
         	$data['message'] = $e->getMessage();
         	$this->responser->send( $data, $this->responser->InternalServerError() );
      	}
   	}

 
}




?>