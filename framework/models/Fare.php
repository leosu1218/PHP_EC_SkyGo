<?php
require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );
require_once( FRAMEWORK_PATH . 'collections/EC/UnifiedFare.php' );
/**
*	Fare code.
*
*	PHP version 5.3
*
*	@category Model
*	@package Fare
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
/**
*	Fare code.
*
*	PHP version 5.3
*
*	@category Model
*	@package Fare
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

class Fare extends PermissionDbModel implements UnifiedFare {

    protected $cart = null;


    /* 	Method of interface UnifiedFare. */
    /**
     * Compute fare with the cart.
     * @return float
     */
    public function getUnifiedFare() {

        $record     = $this->toRecord();
        $cartPrice  = $this->cart->getProductPrice() + $this->cart->getOtherCost() + $this->cart->getDiscount();
        $canFree    = ($cartPrice >= $record["target_amount"]);

        if($canFree) {
            return 0;
        }
        else {
            return $record["amount"];
        }
    }

    /**
     * Get fare type
     * @return mixed
     * @throws Exception
     */
    public function getUnifiedType() {
        return $this->getAttribute("type");
    }

    /**
     * Get fare id
     * @return mixed
     */
    public function getUnifiedId() {
        return $this->getId();
    }

    /**
     * Notify when self appended to cart.
     * @param UnifiedCartCollection $cart
     * @return mixed
     */
    public function onAppendToCart(UnifiedCartCollection $cart) {
        $this->cart = $cart;
    }

    /**
     * Check the fare can be used for the product.
     * @param int $id
     * @return bool
     */
    public function isUsedForProduct($id=0) {
        //TODO implement
        return true;
    }

	/* 	Method of abstract class DbModel. */
	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "fare";
	}

   	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	public function validAttributes($attributes) {
		return true;
	}

	/**
	*	Get Primary key attribute name
	*
	*	@return string
	*/
	public function getPrimaryAttribute() {
		return "id";
	}
	
}

?>