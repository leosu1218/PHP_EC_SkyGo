<?php
/**
*	PlatformUserHasGroupPermissionCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/PlatformUserHasGroupPermission.php' );

/**
*	PlatformUserHasGroupPermissionCollection Access User entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class PlatformUserHasGroupPermissionCollection extends PermissionDbCollection {

	/**
	*	Remove group permission ids by platform user group id.
 	*
	*	@param $ids 	array Attributes want to get this subject has permissions.
	*	@return effectRows
	*/
	public function removeByUserId($id){
		$effectRows = 0;
		$attributes = array( "platform_user_id"=>$id );
		$pageNo = 1;
		$pageSize = 9999;
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

	/**
	*	Append permission set ids by group id.
 	*	
	*	@param $groupId int Attributes want to create permission set into groupId.
	*	@param $ids 	array Attributes want to insert permission set ids.
	*	@return effectRows
	*/
	public function append($userId, $ids){
		$attributes = array("platform_user_id","platform_group_permission_id");
		$values = array();
		foreach ($ids as $key => $id) {
			array_push($values, array($userId, $id));
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
		return "platform_user_has_group_permission";
	}

	public function getModelName() {
		return "PlatformUserHasGroupPermission";
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
