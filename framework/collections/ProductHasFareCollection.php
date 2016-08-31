<?php
/**
*	ProductHasFareCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package ProductHasFareCollection
*	@author Rex chen <rexchen@synctech.ebiz.tw> Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/ProductHasFare.php' );

// require_once( dirname(__FILE__) . '/NewebServiceConfig.php');
// require_once( FRAMEWORK_PATH . 'extends/LoggerHelper.php' );

/**
*	ProductHasFareCollection Access User entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package ProductHasFareCollection
*	@author Rex chen <rexchen@synctech.ebiz.tw> Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class ProductHasFareCollection extends PermissionDbCollection {

	public function multipleAdd($options){

		$result = array();
		$effectRow = 0;
		foreach ($options['ids'] as $key => $id) {
			
			$data = array();

			$input = array();
			$input["product_id"] 	= $options['product_id']; 
			$input["fare_id"] 		= $id;
			$effectRow += $this->create( $input );
			
			$lastCreatedData = $this->lastCreated();
			$data['id'] = $lastCreatedData->id;

			array_push($result, $data);
		}
		$result['effectRow'] = $effectRow;

		return $result;

	}

	/* DbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "product_has_fare";
	}

	public function getModelName() {
		return "ProductHasFare";
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
