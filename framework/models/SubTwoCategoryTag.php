<?php
/**
*	SubTwoCategoryTag code.
*
*	PHP version 5.3
*
*	@category Model
*	@package SubTwoCategoryTag
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );

/**
*	SubTwoCategoryTag code.
*
*	PHP version 5.3
*
*	@category Model
*	@package SubTwoCategoryTag
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

class SubTwoCategoryTag extends PermissionDbModel {

	/* 	Method of interface User. */	
	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "sub_two_category_tag";
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