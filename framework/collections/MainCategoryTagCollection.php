<?php
/**
*	MainCategoryTagCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package MainCategoryTag
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/MainCategoryTag.php' );

/**
*	MainCategoryTagCollection Access HomePageImage entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package MainCategoryTag
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class MainCategoryTagCollection extends PermissionDbCollection {
	
	/* PermissionDbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "main_category_tag";
	}

	public function getModelName() {
		return "MainCategoryTag";
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
