<?php
/**
*	HomePageImageCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package HomePageImage
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/AdvertisementImage.php' );

/**
*	AdvertisementImageCollection Access HomePageImage entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package AdvertisementImage
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class AdvertisementImageCollection extends PermissionDbCollection {
	
	/* PermissionDbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "advertisement_image";
	}

	public function getModelName() {
		return "AdvertisementImage";
	}

	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	public function validAttributes($attributes) {


		if(array_key_exists("id", $attributes) ){
        	throw new Exception("Error cannot has param [id]", 1);
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
