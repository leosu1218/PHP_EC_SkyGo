<?php
/**
*	GroupBuyingMasterUserHasProductGroupCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/GroupBuyingMasterUserHasProductGroup.php' );

/**
*	GroupBuyingMasterUserHasProductGroupCollection Access User entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class GroupBuyingMasterUserHasProductGroupCollection extends PermissionDbCollection {

	public function destroyByAttributes( $attributes ){

		foreach ($attributes as $key => $value) {
			$where = "$key=:$key";
	   		$param = array(":$key" => $value);	
		}

	   	return $this->dao->delete($this->getTable(), $where, $param);

	}

	/**
	*	Append master has product group for new node.
	*
	*	@param $id int
	*	@param $parent_group_id int
	*	@return effect_row number 
	*/
	public function appendMasterHasProductGroup( $newProductGroupId, $newGroupParentId ){

		$data = $this->getRecords( array("product_group_id"=>$newGroupParentId) );
		
		$values = array();
		$attributes = array("gb_master_user_id","product_group_id");
		foreach ($data['records'] as $key => $record) {
			array_push($values, array( $record['gb_master_user_id'], $newProductGroupId ) );
		}

		return $this->multipleCreate($attributes, $values);

	}

	/* DbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "gb_master_user_has_product_group";
	}

	public function getModelName() {
		return "GroupBuyingMasterUserHasProductGroup";
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
