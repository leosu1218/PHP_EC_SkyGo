<?php
/**
*	WholesaleProduct code.
*
*	PHP version 5.3
*
*	@category Model
*	@package WolsesaleProduct
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );
require_once( FRAMEWORK_PATH . 'models/WholesaleMaterials.php' );

/**
*	WholesaleProduct code.
*
*	PHP version 5.3
*
*	@category Model
*	@package WolsesaleProduct
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

class WholesaleProduct extends PermissionDbModel {

	/* 	Method of interface User. */	
	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "wholesale_product";
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

	/**
	*	check product is activity or not yet.
	*
	*	@param number $id product's id for query activity time(ready_time)
	*	@return boolean
	*/
	public function isActivityStart(){

		// $model = $this->superGetById( $productId );
		$activityTime = $this->getAttribute( "ready_time" );
		$now = new DateTime("now");
		if( $now > $activityTime ){
			return false;
		}else{
			return true;
		}

	}

	/**
	*	remove product.
	*
	*	@return effect_row number 
	*/
	public function productRemove(){
		
		$permission = "delete";

		if($this->hasPermission( $permission ) && $this->isActivityStart()) {

			$result = array();

			// $model = $this->superGetById( $options["id"] );
        	$params = array( "state" => '1' );
        	
        	$result = $this->update($params);
			// $result["id"] = $this->id;

			return $result;

		}		
		else {
			throw new AuthorizationException("Actor haven't permission to list model in " . $this->getTable(), 1);		
		}

	}

	public function updateWithValid( $attributes ) {
		if( array_key_exists("removed_time", $attributes) )
		{
			$index = "removed_time";
			$datetime = $this->getAttribute( $index );	
			$originalDatetime = strtotime(date($datetime));
			$updateDatetime = strtotime(date($attributes[$index]));
			if( $originalDatetime > $updateDatetime )
			{
				throw new Exception("Error $index is not valid.", 1);
				
			}
		}

		return $this->update( $attributes );
		
	}

	public function getRecordWithImage() {

		$table = $this->getTable();
		$conditions = array("and",'1=1');
		$params = array();

		$this->dao->fresh();
		$this->dao->select(array(
			'wm.url url',
		));
		$this->dao->rightJoin(
			'wholesale_materials wm', 
			'wm.product_id=wp.id');

		$this->dao->from("$table wp");

		array_push($conditions, 'wm.product_id=:id');
		$params[':id'] = $this->id;

		$this->dao->where($conditions,$params);
	
		$result = array();
		$result = $this->dao->queryAll();


		return $result;

	}

	public function getExplainImages()
	{
		$table = $this->getTable();
		$conditions = array("and",'1=1');
		$params = array();

		$this->dao->fresh();
		$this->dao->select(array(
			'wei.url url',
		));
		$this->dao->rightJoin(
			'wholesale_explain_image wei', 
			'wei.product_id=wp.id');

		$this->dao->from("$table wp");

		array_push($conditions, 'wei.product_id=:id');
		$params[':id'] = $this->id;

		$this->dao->where($conditions,$params);
	
		$result = array();
		$result = $this->dao->queryAll();


		return $result;
	}

	public function getSpec(){
		$table = $this->getTable();
		$conditions = array("and",'1=1');
		$params = array();

		$this->dao->fresh();
		$this->dao->select(array(
			'*',
		));
		$this->dao->rightJoin(
			'wholesale_product_spec wps', 
			'wps.product_id=wp.id');

		$this->dao->from("$table wp");

		array_push($conditions, 'wps.product_id=:id');
		$params[':id'] = $this->id;

		$this->dao->where($conditions,$params);
	
		$result = array();
		$result = $this->dao->queryAll();


		return $result;
	}

	public function getFares(){
		$table = $this->getTable();
		$conditions = array("and",'1=1');
		$params = array();

		$this->dao->fresh();
		$this->dao->select(array(
			// 'f.id id',
			// 'f.type type',
			// 'f.amount amount',
			// 'f.target_amount target_amount',
			'd.global global',
			'd.program_name program_name',
			'd.pay_type pay_type',
			'd.delivery_type delivery_type',
			'd.id id',

			'phd.id productHasDeliveryId'
		));

		$this->dao->rightJoin(
			'product_has_delivery phd', 
			'phd.product_id=wp.id');
		// $this->dao->rightJoin(
		// 	'fare f',
		// 	'f.id=phf.fare_id');
		$this->dao->rightJoin(
			'delivery_program d',
			'd.id=phd.delivery_id');

		$this->dao->from("$table wp");

		array_push($conditions, 'phd.product_id=:id');
		$params[':id'] = $this->id;

		$this->dao->where($conditions,$params);
	
		$result = array();
		$result['sql'] = $this->dao->getSql();
		$result = $this->dao->queryAll();


		return $result;
	}

	public function getReference(){
		return array(
				"findCoverPhoto" => array("cover_photo_id","WholesaleMaterials")
			);
	}
	
}

?>