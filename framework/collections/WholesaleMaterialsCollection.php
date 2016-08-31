<?php
/**
*	WholesaleProductCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Product
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'controllers/FileUploadHandler.php' );
require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/WholesaleMaterials.php' );

/**
*	WholesaleProductCollection Access Product entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Product
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class WholesaleMaterialsCollection extends PermissionDbCollection {
	
	/* PermissionDbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "wholesale_materials";
	}

	public function getModelName() {
		return "WholesaleMaterials";
	}

	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	public function validAttributes($attributes) {

		if( !array_key_exists("product_id", $attributes) ){
        	throw new Exception("Error missing param [product_id]", 1);
        }

        $this->attributes = $attributes;

        return true;

	}

	/**
    *   vaildate you needed fileds
    *   
    *	return @array (ex. array( "name"=>"hi", "price"=>"300", ... ))
    */
    public function getAttributes(){
        return $this->attributes;
    }

	/**
	*	Get Primary key attribute name
	*
	*	@return string
	*/
	public function getPrimaryAttribute() {
		return "id";
	}


	public function uploadMeterials()
	{
		$result = array();
		$this->FileHandler = new FileUploadHandler();
		$result = $this->FileHandler->uploading();
        return $result;
	}

	public function productUpdate( $options )
	{
		$model = $this->getById($options["id"]);
		$data["effectRow"] = $model->update( array("cover_photo_id"=>$options["cover_photo_id"]) );
		return $data;
	}

	public function addMaterialsUrl( $options )
	{
		// if($this->hasPermission( 'create' ))
		// {
			$result = array();
			$effectRow = 0;
			foreach ($options['product_images'] as $key => $value) {
				
				$data = array();
					
				$input = array();
				$input["product_id"] = $options['product_id']; 
				$input["url"] = $value['fileName'];
					
				$effectRow += $this->create( $input );
				
				$lastCreatedData = $this->lastCreated();
				$data['id'] = $lastCreatedData->id;
					
				array_push($result, $data);
			}
			$result['effectRow'] = $effectRow;
				
			return $result;
		// }
		// else
		// {
		// 	throw new AuthorizationException("Actor haven't permission to create model in " . $this->getTable(), 1);	
		// }
	}

}



?>
