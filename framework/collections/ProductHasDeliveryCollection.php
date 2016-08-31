<?php
/**
*	ProductHasDeliveryCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package ProductHasDeliveryCollection
*	@copyright 2016 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/ProductHasDelivery.php' );

// require_once( dirname(__FILE__) . '/NewebServiceConfig.php');
// require_once( FRAMEWORK_PATH . 'extends/LoggerHelper.php' );

/**
*	ProductHasDeliveryCollection Access User entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package ProductHasDeliveryCollection
*	@copyright 2016 synctech.com
*/
class ProductHasDeliveryCollection extends PermissionDbCollection {

	public function searchPayType($pageNo, $pageSize, $search=array()) {
		$conditions = array('and','1=1');
		$params = array();
		$result = array();		

		$this->dao->select(array(
			'phd.id id',
			'phd.product_id product_id',
			'phd.delivery_id delivery_id',

			'dp.id dp_id',
			'dp.program_name program_name',
			'dp.pay_type pay_type',
			'dp.delivery_type delivery_type',
			'dp.global dp_global',

			'f.id f_id',
			'f.amount amount',
			'f.type type',
			'f.target_amount target_amount',
			'f.global f_global',
		));
		$this->dao->from("product_has_delivery phd");
		$this->dao->leftJoin(
			'delivery_program dp',
			'phd.delivery_id = dp.id');
		$this->dao->leftJoin(
			'fare f',
			'dp.delivery_type = f.id');

		$statement = "";
        foreach($search["ids"] as $index => $id) {
            $statement .= "phd.product_id=:id$index OR ";
            $params[":id$index"] = $id;
        }
        $statement = substr($statement, 0, -3);
        array_push($conditions, "($statement)");

		$this->dao->where($conditions,$params);
		$this->dao->group('dp.pay_type');
		$this->dao->having('COUNT(*) = ' . count($search["ids"]) );

		$result['recordCount'] = intval($this->dao->queryCount());
		$result["totalPage"] = intval(ceil($result['recordCount'] / $pageSize));

		$this->dao->paging($pageNo, $pageSize);
		$result["records"] = $this->dao->queryAll();

//		var_dump($this->dao->getSql());
		return $result;
	}

	public function searchPrice($pageNo, $pageSize, $search=array()) {
		$conditions = array('and','1=1');
		$params = array();
		$result = array();	
		$this->dao->select(array(
			'phd.id id',
			'phd.product_id product_id',
			'phd.delivery_id delivery_id',

			'dp.id dp_id',
			'dp.program_name program_name',
			'dp.pay_type pay_type',
			'dp.delivery_type delivery_type',
			'dp.global dp_global',

			'f.id f_id',
			'f.amount amount',
			'f.type type',
			'f.target_amount target_amount',
			'f.global f_global',
		));
		$this->dao->from("product_has_delivery phd");
		$this->dao->leftJoin(
			'delivery_program dp',
			'phd.delivery_id = dp.id');
		$this->dao->leftJoin(
			'fare f',
			'dp.delivery_type = f.id');


		
		$params[":type"] = $search["payType"];
		array_push($conditions, " pay_type = :type");

		$statement = "";
        foreach($search["ids"] as $index => $id) {
            $statement .= "phd.product_id=:id$index OR ";
            $params[":id$index"] = $id;
        }
        $statement = substr($statement, 0, -3);

        array_push($conditions, "($statement)");

        

		$this->dao->where($conditions,$params);

		$this->dao->order('target_amount DESC');

		// var_dump($this->dao->getSql());


		$result['recordCount'] = intval($this->dao->queryCount());
		$result["totalPage"] = intval(ceil($result['recordCount'] / $pageSize));

		$this->dao->paging($pageNo, $pageSize);
		$result["records"] = $this->dao->queryAll();


		return $result;
	}

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
		return "product_has_delivery";
	}

	public function getModelName() {
		return "ProductHasDelivery";
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
