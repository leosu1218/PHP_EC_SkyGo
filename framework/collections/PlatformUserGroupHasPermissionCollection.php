<?php
/**
*	PlatformUserGroupHasPermissionCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/PlatformUserGroupHasPermission.php' );

/**
*	PlatformUserGroupHasPermissionCollection Access User entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class PlatformUserGroupHasPermissionCollection extends PermissionDbCollection {

	/**
	*	Get ids by group id.
 	*	
	*	@param $groupId 	int Attributes want to this group has those permission set ids.
	*	@return array 	Permission set ids
	*/
	public function getIdsByGroupId( $groupId ) {
		$result = $this->getRecords( array("platform_user_group_id"=>$groupId) );
		$ids = array();
		foreach ($result['records'] as $key => $record) {
			array_push( $ids, $record['id'] );
		}
		return $ids;
	}


	/**
	*	Remove permission set ids by platform user group id.
 	*
	*	@param $ids 	array Attributes want to get this subject has permissions.
	*	@return effectRows
	*/
	public function removeByPlatformUserGroupId($id){
		$effectRows = 0;
		$attributes = array( "platform_user_group_id"=>$id );
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
	*	Get permissions by records.
 	*	
	*	@param $records 	array Attributes want to this group has those permission ids.
	*	@return $ids array
	*/
	private function getPermissionIdsByRecords($records){
		$ids = array();
		foreach ($records as $key => $record) {
			array_push($ids, $record["permission_id"]);
		}
		return $ids;
	}

	/**
	*	Get permissions by platform user group id.
 	*	
	*	@param $id 	int Attributes want to this group has those permission ids.
	*	@return array
	*/
	public function getPermissionIdsByGroupId( $id ){
		$result = $this->getRecords( array("platform_user_group_id"=>$id), 1, 9999 );
		return $this->getPermissionIdsByRecords($result['records']);
	}

	/**
	*	Append permission set ids by group id.
 	*	
	*	@param $groupId int Attributes want to create permission set into groupId.
	*	@param $ids 	array Attributes want to insert permission set ids.
	*	@return effectRows
	*/
	public function append($groupId, $ids){
		$attributes = array("platform_user_group_id","permission_id");
		$values = array();
		foreach ($ids as $key => $id) {
			array_push($values, array($groupId, $id));
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
		return "platform_user_group_has_permission";
	}

	public function getModelName() {
		return "PlatformUserGroupHasPermission";
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
