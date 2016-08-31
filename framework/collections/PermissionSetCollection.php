<?php
/**
*	PermissionSetCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Fare
*	@author Rex chen <rexchen@synctech.ebiz.tw> Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/PermissionSet.php' );

/**
*	PermissionSetCollection Access User entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Fare
*	@author Rex chen <rexchen@synctech.ebiz.tw> Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class PermissionSetCollection extends PermissionDbCollection {

	/* DbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "permission_set";
	}

	public function getModelName() {
		return "PermissionSet";
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
