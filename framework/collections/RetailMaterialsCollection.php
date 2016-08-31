<?php
/**
*	RetailMaterialsCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package RetailMaterials
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/WholesaleProduct.php' );

/**
*	RetailMaterialsCollection Access Product entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Product
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class RetailMaterialsCollection extends PermissionDbCollection {
	
	/* PermissionDbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "retail_materials";
	}

	public function getModelName() {
		return "RetailMaterials";
	}

	public function getPermissionName(){
		return "retail";
	}

	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	public function validAttributes($attributes) {
				
		// if(array_key_exists("id", $attributes)) {
		// 	throw new Exception("Can't write the attribute 'id'.");
		// 	return false;
		// }

		if(!array_key_exists("product_id", $attributes)) {
			throw new Exception("Attributes should be has 'product_id'.", 1);
		}

		if(!array_key_exists("product_images", $attributes)) {
			throw new Exception("Attributes should be has 'product_images'.", 1);
		}

		// if(!array_key_exists("account", $attributes)) {
		// 	throw new Exception("Attributes should be has 'account'.", 1);
		// }

		// if(!array_key_exists("hash", $attributes)) {
		// 	throw new Exception("Attributes should be has 'hash'.", 1);
		// }

		// if(!array_key_exists("salt", $attributes)) {
		// 	throw new Exception("Attributes should be has 'salt'.", 1);
		// }

		return true;
	}

	/**
    *   vaildate you needed fileds
    *   
    *	return @array (ex. array( "name"=>"hi", "price"=>"300", ... ))
    */
    public function getAttributes(){
        return array( 
                    "product_id" 	=> $this->attributes["product_id"],
                    "product_images"=> $this->attributes['product_images']
            );
    }

	/**
	*	Get Primary key attribute name
	*
	*	@return string
	*/
	public function getPrimaryAttribute() {
		return "id";
	}

	public function uploadMeterials(){
		$result = array();
		$this->FileHandler = new FileUploadHandler();
		$this->permissionsCheck( $this->FileHandler->getTypes() );
		$result = $this->FileHandler->uploading();
        return $result;
	}

	public function productUpdate( $options ){
		$this->permissionsCheck("update");
		$model = $this->getById($options["id"]);
		$data["effectRow"] = $model->update( array("cover_photo_id"=>$options["cover_photo_id"]) );
		return $data;
	}

	public function addMaterialsUrl( $options ){

		$this->permissionsCheck("create");
		$result = array();
		foreach ($options['product_images'] as $key => $value) {
			$data = array();
				
			$input = array();
			$input["product_id"] = $options['product_id']; 
			$input["fileName"] = $value;
				
			$data["effectRow"] = $this->create( $input );
			$lastCreatedData = $this->lastCreated();
			$data['id'] = $lastCreatedData->id;
				
			array_push($result, $data);
		}
			
		return $result;
	}

	public function permissionsCheck( $permissions ){
		
		if( is_array($permissions) ){
			foreach ($permissions as $key => $value) {
				if( !$this->hasPermission( $value ) ){
					throw new AuthorizationException("Actor haven't permission to " . $value . " model in " . $this->getTable(), 1);	
				}	
			}
		}
		else{
			if( !$this->hasPermission( $permissions ) ){
				throw new AuthorizationException("Actor haven't permission to " . $permissions . " model in " . $this->getTable(), 1);	
			}
		}


	}

}



?>
