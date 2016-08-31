<?php
/**
*	PermissionSetHasPermissionCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Fare
*	@author Rex chen <rexchen@synctech.ebiz.tw> Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/PermissionSetHasPermission.php' );

/**
*	PermissionSetHasPermissionCollection Access User entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Fare
*	@author Rex chen <rexchen@synctech.ebiz.tw> Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class PermissionSetHasPermissionCollection extends PermissionDbCollection {

	/**
	*	Get permission id list by permission set id list.
 	*
	*	@param $ids 	array Attributes want to get this subject has permissions.
	*	@return array 		permission id array
	*/
	public function getPermissionIdsByIds($ids){

		$conditions = array("or");
        $params = array();
        foreach ($ids as $key => $id) {
        	array_push($conditions, "psid=:psid".$key);
        	$params[":psid".$key] = $id;
        }

		$result = $this->superGetRecordsByCondition($conditions, $params, 1, 99999);
		
		$pIds = array();
		foreach ($result['records'] as $key => $record) {
			array_push($pIds, $record['pid']);
		}
		return $pIds;

	}

	/* DbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "permission_set_has_permission";
	}

	public function getModelName() {
		return "PermissionSetHasPermission";
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
