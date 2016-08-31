<?php
/**
*	GroupBuyingMasterUser code.
*
*	PHP version 5.3
*
*	@category Model
*	@package GroupBuyingMasterUser
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );
require_once( FRAMEWORK_PATH . 'collections/GroupBuyingMasterUserHasProductGroupCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/ProductGroupCollection.php' );


/**
*	GroupBuyingMasterUser code.
*
*	PHP version 5.3
*
*	@category Model
*	@package GroupBuyingMasterUser
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

class GroupBuyingMasterUser extends PermissionDbModel {
	
	/**
	*	Instance model by session variable.
	*
	*	@return PlatformUser Model id by session variable.
	*/
	public static function instanceBySession() {
		$record = self::getRecordBySession();
		return new GroupBuyingMasterUser($record["id"]);
	}	

	/**
   	*	Get user info from session variable.
   	*
   	*	@return array User record Array(
	*								"account" 		=> ["account"],
    *    							"domain_name"	=> ["domain_name"],
    *    							"group_id"		=> ["group_id"],
    *    							"id"			=> ["id"],
    *    							"name"			=> ["name"]
   	*							  ).
   	*/
   	public static function getRecordBySession() {

   		$userInfo = GeneralSession::getInstance()->groupbuyMaster;

   		if(is_null($userInfo) || empty($userInfo)) {
			throw new AuthorizationException("Unauthenticated session.", 1);
		}

   		return $userInfo;
   	}

   	/**
   	*	Clear all of session variables of user info.
   	*
   	*/
   	public static function clearSession() {
   		$session = GeneralSession::getInstance();
   		$session->clear("groupbuyMaster");
   	}

   	/**
   	*	Saving user info into session variable.
   	*
   	*	@param $userInfo array user info. Array(
   	*									"account" 		=> ["account"],
    *	    							"domain_name"	=> ["domain_name"],
    *   	 							"group_id"		=> ["group_id"],
    *    								"id"			=> ["id"],
    *    								"name"			=> ["name"]
   	*							  	).
   	*	@return bool Return true when save success.
   	*/
   	public static function saveToSession($userInfo) {
   		$session = GeneralSession::getInstance();
		$session->groupbuyMaster = $userInfo;	
   	}

	/**
	*	Append product group to the group buying master.
	*	The master will has permission to create activity
	*	by the group of products.
	*
	*	@param $productGroupIds array The group ids.
	*	@return int 	Success appended counter.(if fail will return 0).
	*/
	public function appendProductGroup($productGroupIds=array()) {
		if($this->hasPermission("append_product_group")) {
			
			$productGroup = new ProductGroupCollection();
			$ids = $productGroup->searchProductGroupSubIds($productGroupIds);

			$params = array();
			foreach($ids as $index => $id) {
				array_push($params, array($this->id, $id));
			}

			$collection = new GroupBuyingMasterUserHasProductGroupCollection();
			$attributes = array('gb_master_user_id', 'product_group_id');
			$effectRows = $collection->multipleCreate($attributes, $params);

			return $effectRows;
		}
		else {
			throw new AuthorizationException("Actor haven't permission to assign product groug in " . $this->getTable(), 1);		
		}
	}

	// TODO Unit Test
	/**
	*	Remove product group from the group buying master.
	*	The master will haven't permission to create activity
	*	by the group of products.
	*
	*	@param $productGroupIds array The group ids.
	*	@return int 	Success remove counter.(if fail will return 0).
	*/
	public function removeProductGroup($productGroupIds=array()) {
		if($this->hasPermission("append_product_group")) {
			
			$collection = new GroupBuyingMasterUserHasProductGroupCollection();			
			$effectRows = $collection->multipleDestroyById($productGroupIds);

			return $effectRows;
		}
		else {
			throw new AuthorizationException("Actor haven't permission to remove product groug in " . $this->getTable(), 1);		
		}
	}

	/**
	*	Get product group int the group buying master.	
	*	
	*	@return int 	Success appended counter.(if fail will return 0).
	*/
	public function getProductGroup($pageNo, $pageSize) {
		if($this->hasPermission("read")) {
			
			$result = $this->getDefaultRecords($pageNo, $pageSize);
			$this->dao->select(array(
				"*"
			));	
			$this->dao->leftJoin("product_group pg", "pg.id=mhpg.product_group_id");
			$this->dao->from("gb_master_user_has_product_group as mhpg");
			$this->dao->where("mhpg.gb_master_user_id=:id", array(":id" => $this->getId()));

			$result['recordCount'] = intval($this->dao->queryCount());
			$result["totalPage"] = intval(ceil($result['recordCount'] / $pageSize));
			$this->dao->paging($pageNo, $pageSize);
			$result["records"] = $this->dao->queryAll();

			return $result;
		}
		else {
			throw new AuthorizationException("Actor haven't permission to assign product groug in " . $this->getTable(), 1);		
		}
	}

	/* 	Method of interface User. */	
	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "gb_master_user";
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