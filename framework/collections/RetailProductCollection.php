<?php
/**
*	RetailProductCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Product
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/RetailProduct.php' );

/**
*	RetailProductCollection Access Product entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Product
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class RetailProductCollection extends PermissionDbCollection {
	
	/* PermissionDbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "retail_product";
	}

	public function getModelName() {
		return "RetailProduct";
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

		$this->vaildateFiled( 
                array( 
                    "name", 
                    "ready_time", 
                    "removed_time",
                    "price",
                    "detail",
                    "product_group_id",
                    "serial_number"
                ),
                $attributes 
            );
		return true;
	}

	/**
    *   vaildate you needed fileds
    *   
    *   @param array $vaildateFileds (ex. array( "name", "parent_group_id", "type" ) )
    *	@param array $attributes (ex. array( "name"=>"hello", "price"=>"150", ... ))
    */
    public function vaildateFiled( $vaildateFileds, $attributes ){

        foreach ($vaildateFileds as $key => $value) {
            
            if( !array_key_exists($value, $attributes) ) {
                throw new Exception("Missing parameter [".$value."] " . $this->getTable(), 1);
            }

        }

        $this->attributes = $attributes;

    }

    /**
    *   vaildate you needed fileds
    *   
    *	return @array (ex. array( "name"=>"hi", "price"=>"300", ... ))
    */
    public function getAttributes(){
        return array( 
                    "name" 				=> $this->attributes["name"], 
                    "ready_time" 		=> $this->getDateFormat($this->attributes["ready_time"]), 
                    "removed_time"  	=> $this->getDateFormat($this->attributes["removed_time"]), 
                    "price"  			=> $this->attributes["price"],
                    "detail"  			=> $this->attributes["detail"],
                    "product_group_id"  => $this->attributes["product_group_id"],
                    "serial_number"  	=> $this->attributes["serial_number"]
            );
    }

    /**
    *   convert date 
    *   
    *	return @String (ex. "2015-08-06 00:00:00" )
    */
    public function getDateFormat( $date ){
    	if( $date ){
    		$zone = 'Asia/Taipei';
            date_default_timezone_set( $zone );
            $date = new DateTime( $date );
            $date->setTimezone( new DateTimeZone( $zone ) );
            $result_date = $date->format("Y-m-d H:i:s");
            return $result_date;   
        }
        else{
        	throw new Exception("Missing parameter [ date ] value." . $this->getTable(), 1);
        }
    }

	/**
	*	Get Primary key attribute name
	*
	*	@return string
	*/
	public function getPrimaryAttribute() {
		return "id";
	}


	public function getList( $pageNo, $pageSize ){

		$this->permissionCheck( "read" );
		$attributes = array();
		return	$this->superGetRecords( $attributes, $pageNo, $pageSize );

	}

	/**
	*	Create product.
	*
	*	@return effect_row number 
	*/
	public function productCreate( $options ){
		
		$this->permissionCheck( "create" );
		return $this->supercreate( $options );

	}

    public function productUpdate( $product_id, $options ){

        if( !$this->hasPermission( "update" ) ){
            throw new AuthorizationException("Actor haven't permission to update model in " . $this->getTable(), 1);    
        }
        $model = $this->getById( $product_id );
        $result = $model->update($options);
        return $result;
    }

    /**
    *   remove product.
    *
    *   @return effect_row number 
    */
    public function productRemove( $options ){
        
        $permission = "delete";

        if($this->hasPermission( $permission )) {

            $model = $this->superGetById( $options["id"] );

            $result = array();
            // $model = $this->getById( $product_id );
            $params = array( "state" => '1' );
            $result['effectRow'] = $model->update($params);
            $result["id"] = $options["id"];

            return $result;

        }       
        else {
            throw new AuthorizationException("Actor haven't permission to list model in " . $this->getTable(), 1);      
        }

    }

	public function permissionCheck( $permissions ){

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
