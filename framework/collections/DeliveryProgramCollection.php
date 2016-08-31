<?php
/**
*	DeliveryProgramCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package deliveryProgram
*	@copyright 2016 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/DeliveryProgram.php' );

/**
*	DeliveryProgramCollection Access Fare entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package deliveryProgram
*	@copyright 2016 synctech.com
*/
class DeliveryProgramCollection extends PermissionDbCollection {
	
	/**
	*	Search records.
	*
	*
	*	@param $search array The search conditions.
	*	@return array The records.
	*/
	public function searchRecords($pageNo, $pageSize, $search=array()) {

		$result = $this->getDefaultRecords($pageNo, $pageSize);	
		$table = $this->getTable();
        $this->dao->fresh();
		$conditions = array('and','1=1');
		$params = array();

        $select = array(
            'd.id id',
            'd.program_name program_name',
            'd.pay_type pay_type',
            'd.delivery_type delivery_type',
            'd.global global',
        );

		$this->dao->from("$table d");
		$this->dao->group('d.id');

        // $this->appendStatements($this->dao, $params, $conditions, $select, $search, $this->joinStatement);
        // $this->appendStatements($this->dao, $params, $conditions, $select, $search, $this->searchConditions);

        $this->dao->select($select);
		$this->dao->where($conditions,$params);

		$result['recordCount'] = intval($this->dao->queryCount());
		$result['totalRecord'] = $result['recordCount']; 		
		$result["totalPage"] = intval(ceil($result['totalRecord'] / $pageSize));
		$this->dao->paging($pageNo, $pageSize);		
		$result["records"] = $this->dao->queryAll();

		return $result;
    }

	/* PermissionDbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "delivery_program";
	}

	public function getModelName() {
		return "DeliveryProgram";
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
