<?php
require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );

/**
*	PlatformUserGroupHasPermissionSet code.
*
*	PHP version 5.3
*
*	@category Model
*	@package PlatformUserGroupHasPermissionSet
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
/**
*	PlatformUserGroupHasPermissionSet code.
*
*	PHP version 5.3
*
*	@category Model
*	@package PlatformUserGroupHasPermissionSet
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

class PlatformUserGroupHasPermissionSet extends PermissionDbModel {

	/* 	Method of abstract class DbModel. */
	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "platform_user_group_has_permission_set";
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