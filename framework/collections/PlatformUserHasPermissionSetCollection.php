<?php
/**
*	PlatformUserHasPermissionSetCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package PlatformUserHasPermissionSet
*	@author Rex chen <rexchen@synctech.ebiz.tw> Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/PermissionSet.php' );

/**
*	PlatformUserHasPermissionSetCollection Access User entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package PlatformUserHasPermissionSet
*	@author Rex chen <rexchen@synctech.ebiz.tw> Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class PlatformUserHasPermissionSetCollection extends PermissionDbCollection {

	/**
	*	
 	*
	*	@param $ids 	array Attributes want to get this subject has permissions.
	*	@return effectRows
	*/
	public function append($userId, $permissionSetIds){
		$attributes = array("puid","psid");
		$values = array();
		foreach ($permissionSetIds as $key => $id) {
			array_push($values, array($userId, $id));
		}
		return $this->multipleCreate($attributes, $values);
	}

	/**
	*	Get permission set ids by platform user id.
 	*
	*	@param $id
	*	@return records
	*/
	public function getPermissionSetIdsByPlatformUserId($id){
		$ids = array();

		$attributes = array( "puid"=>$id );
		$pageNo = 1;
		$pageSize = 99999;
		$result = $this->getRecords($attributes, $pageNo, $pageSize);
		if( count($result['records'])>0 ){
			
			foreach ($result['records'] as $key => $record) {
				array_push($ids, $record['psid']);
			}
		}
		return $ids;
	}

	/**
	*	Remove permission set ids by platform user id.
 	*
	*	@param $id
	*	@return effectRows
	*/
	public function removeByPlatformUserId($id){
		$effectRows = 0;
		$attributes = array( "puid"=>$id );
		$result = $this->getRecords($attributes, $pageNo, $pageSize);
		if( count($result['records'])>0 ){
			$ids = array();
			foreach ($result['records'] as $key => $record) {
				array_push($ids, $record['id']);
			}
			$effectRows = $this->multipleDestroyById($ids);
		}
		return $effectRows;
	}

	/* DbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "platform_user_has_permission_set";
	}

	public function getModelName() {
		return "PlatformUserHasPermissionSet";
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
