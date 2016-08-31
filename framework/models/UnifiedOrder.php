<?php
require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );
require_once( dirname(__FILE__) . "/order/DeliveringOrderState.php" );
require_once(dirname(__FILE__) . "/order/AbnormalOrderState.php");
require_once(dirname(__FILE__) . "/order/ApplyCancelOrderState.php");
require_once(dirname(__FILE__) . "/order/CancelOrderState.php");
require_once(dirname(__FILE__) . "/order/CompletedOrderState.php");
require_once(dirname(__FILE__) . "/order/DeliveringOrderState.php");
require_once(dirname(__FILE__) . "/order/PreparedOrderState.php");
require_once(dirname(__FILE__) . "/order/PaidOrderState.php");
require_once(dirname(__FILE__) . "/order/WarrantyPeriodOrderState.php");
require_once(dirname(__FILE__) . "/order/ReturnedOrderState.php");


/**
 *  UnifiedOrder code.
 *
 *	PHP version 5.3
 *
 *	@category Model
 *	@package Order
 *	@author Rex chen <rexchen@synctech.ebiz.tw>
 *	@copyright 2015 synctech.com
 */
class UnifiedOrder extends PermissionDbModel {

    const WARRANTY_DAY = 7;
    const DELIVERY_DAY = 3;


    /* 	Method of interface User. */
    /**
     *	Get the entity table name.
     *
     *	@return string
     */
    public function getTable() {
        return "unified_order";
    }

    /**
     *	Check attributes is valid.
     *
     *	@param $attributes 	array Attributes want to checked.
     *	@return bool 		If valid return true.
     */
    public function validAttributes($attributes) {
        if(array_key_exists("id", $attributes)) {
            throw new Exception("Can't write the attribute 'id'.");
        }

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