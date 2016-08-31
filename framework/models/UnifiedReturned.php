<?php

/**
*	GroupBuyingReturn code.
*
*	PHP version 5.3
*
*	@category Model
*	@package Order
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
require_once( dirname(__FILE__) . "/returned/PrepareReturnedState.php" ); 	//0
require_once( dirname(__FILE__) . "/returned/ReceivingReturnedState.php" );	//4
require_once( dirname(__FILE__) . "/returned/CancelReturnedState.php" );  	//8
require_once( dirname(__FILE__) . "/returned/CompletedReturnedState.php" );	//16

require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );

class UnifiedReturned extends PermissionDbModel {

	/* 	Method of interface User. */	
	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "unified_returned";
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