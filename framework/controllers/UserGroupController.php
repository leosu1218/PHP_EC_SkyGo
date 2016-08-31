<?php
/**
*  UserGroup Controller code.
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @author Jai Chien <jaichien@syncte-infinity.com>
*  @copyright 2015 synctech.com
*/
require_once( FRAMEWORK_PATH . 'collections/PlatformUserGroupCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PermissionSetHasPermissionCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PermissionSetCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserHasPermissionSetCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserGroupHasPermissionSetCollection.php' );
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'extends/GeneralSession.php' );



class UserGroupController extends RestController {

	private $rootGroupId;
  const ORTER_GROUP_ID = 0;

   	public function __construct() {
      	parent::__construct();      	
      	$this->rootGroupId = 1;
   	}

    /**   
    * 
    * GET:   /group/platformuser/<id:\d+>
    *
    * Get UserGroup by id.
    *
    * @return records
    */
    public function get( $id ){
      $result = array("records"=>array());

      $platformuser = new PlatformUserGroupCollection();
      $model = $platformuser->getById( $id );
      $result['records']['userGroup'] = $model->toRecord();

      $collection = new PlatformUserGroupHasPermissionSetCollection();
      $user = PlatformUser::instanceBySession();
      $collection->setActor($user);
      $attributes = array( "pugid"=>$id );
      $collectionResult = $collection->getRecords($attributes);
      $ids = array();
      foreach ($collectionResult['records'] as $key => $record) {
         array_push($ids, $record['psid']);
      }
      $result['records']['permissionSetIds'] = $ids;

      return $result;
    }

    /**   
    * 
    * PUT:   /group/platformuser/<id:\d+>
    *
    * Get UserGroup by id.
    *
    * @return records
    */
    public function update( $id ){

      $user = PlatformUser::instanceBySession();
      $PlatformUserGroups = new PlatformUserGroupCollection();
      $PlatformUserGroups->setActor($user);
      $PlatformUserGroup = $PlatformUserGroups->getById($id);
      $params = array( "name"=>$this->params("name") );
      $isSuccess = $PlatformUserGroup->update($params);

      $PlatformUserGroupHasPermissionSet = new PlatformUserGroupHasPermissionSetCollection();
      $PlatformUserGroupHasPermissionSet->setActor($user);
      $PlatformUserGroupHasPermissionSet->removeByPlatformUserGroupId($id);
      $permissionSetIds = $this->params("permissions");
      $effectRows = $PlatformUserGroupHasPermissionSet->append($id, $permissionSetIds);
      if( $effectRows != count($permissionSetIds) ){
        throw new Exception("Append permission set ids into group by PlatformUserHasPermissionSetCollection.", 1);
      }

      $groupPermissionSetIds = $PlatformUserGroupHasPermissionSet->getPermissionSetIdsByGroupId($id);
      $permissionIds = (new PermissionSetHasPermissionCollection())->getPermissionIdsByIds($groupPermissionSetIds);

      $PlatformUserGroupHasPermission = new PlatformUserGroupHasPermissionCollection();
      $PlatformUserGroupHasPermission->setActor($user);
      $PlatformUserGroupHasPermission->removeByPlatformUserGroupId($id);
      $effectRows = $PlatformUserGroupHasPermission->append($id, $permissionIds);
      if( $effectRows != count($permissionIds) ){
        throw new Exception("Append permission ids into group by PlatformUserHasPermissionCollection.", 1);
      }

      $users = new PlatformUserCollection();
      $userIds = $users->getUserIdsByGroupId($id);

      $groupPermissionIds = $PlatformUserGroupHasPermission->getIdsByGroupId( $id );
      $PlatformUserHasGroupPermission = new PlatformUserHasGroupPermissionCollection();
      foreach ($userIds as $key => $userId) {
        $PlatformUserHasGroupPermission->removeByUserId($userId);
        $effectRows = $PlatformUserHasGroupPermission->append($userId, $groupPermissionIds);
        if( $effectRows != count($groupPermissionIds) ){
          throw new Exception("Append group permission ids into group by PlatformUserHasGroupPermissionCollection.", 1);
        }
      }
      
      return array();

    }

   	/**
   	*	Vertify variable in a params array. 
   	*	Response Bad Reqeust to browser when find invalid params.
   	*
   	*	@param $name string The name want to vertifing.
   	*	@param $parms array The params want to vertifing.
   	*/
   	public function vertify($name, $params) {
   		if(!array_key_exists($name, $params)) {
	    	$this->responser->send(array("message" => "Missing parameter [$name]."), $this->responser->BadRequest());
	    }   		
   	}

   	/**
   	*	POST: 	/group/platformuser/<id:\d+>/permission
   	*
   	*	@param $id int The id of platform user group. 
   	*  	@param $this->receiver array Struct of array(    	
   	*										'permissions' => array( 
   	*											<permission id>, 
   	*											<permission id>, 
   	*											....
   	*									)
   	*/
   	public function appendPermission($id) {
   		$data = array();
      	try {
      		if(!array_key_exists("permissions", $this->receiver)) {
      			$this->responser->send(array("message" => "Missing parameter [permissions]."), $this->responser->BadRequest());
      		}
      		else {      			
      			$user 			= PlatformUser::instanceBySession();

      			$permissionSet = new PermissionSetHasPermissionCollection();
            $permissions = $permissionSet->getPermissionIdsByIds($this->receiver["permissions"]);
      			
            $group 			= (new PlatformUserGroupCollection())->getById($id);
      			$group->setActor($user);

      			$rowCount 		= $group->appendPermissions($permissions);

      			if(count($rowCount) > 0) {
      				$this->responser->send(array(), $this->responser->Created());
      			}
      			else {
      				throw new Exception("Not create permissions.", 1);      				
      			}
      		}      		   		       
       	}
       	catch (AuthorizationException $e) {   	        
   	        $this->responser->send( $data, $this->responser->Unauthorized() );         
   	   }
      	catch ( Exception $e) {
         	$data['message'] = SERVER_ERROR_MSG;         	
         	$this->responser->send( $data, $this->responser->InternalServerError() );
      	}
   	}

    /**   
    * Get permission list by permission set ids.
    *
    * @return permissions
    */
    private function getPermissionsByPermissionSetIds( $permissionSetIds, &$dao=null ){

      $permissionSet = new PermissionSetHasPermissionCollection( $dao );
      $user = PlatformUser::instanceBySession();
      $permissionSet->setActor($user);
      $permissions = $permissionSet->getPermissionIdsByIds($permissionSetIds);
      return $permissions;

    }

  private function getIdsByRecords($records){
    $ids = array();
    foreach ($records as $key => $record) {
      array_push($ids, $record["id"]);
    }
    return $ids;
  }


   	/**
   	*	POST: 	/user/<id:\d+>/permission
   	*  	Add a user group under root group.
   	*
   	*  	@param $this->receiver array Struct of array( 
   	*										'name' => <group name>,
   	*										'permissions' => array( 
   	*											<permission id>, 
   	*											<permission id>, 
   	*											....   	
   	*									)
   	*/
   	public function create() {
    			
			$user = PlatformUser::instanceBySession();
      
      $dao = new DB(DB_NAME);
      if($dao->transaction()) {
        try {
          $permissionSetIds = $this->receiver["permissions"];
          $permissions = $this->getPermissionsByPermissionSetIds( $permissionSetIds, $dao );

          $attributes = array(
            "name" => $this->receiver["name"],
            "parent_group_id" => $this->rootGroupId
          );
          $groups = new PlatformUserGroupCollection($dao);
          $groups->setActor($user);
          // $group = $groups->create($attributes);
          // $group = $groups->create($attributes)->with('permission', $permissions);
          $group = $groups->createWithPermission($attributes, $permissions);

          $groupId = $group->getId();
          $PlatformUserGroupHasPermissionSet = new PlatformUserGroupHasPermissionSetCollection($dao);
          $effectRows = $PlatformUserGroupHasPermissionSet->append($groupId, $permissionSetIds);
          if( $effectRows!=count($permissionSetIds) ){
            throw new Exception("Append Permission set ids into PlatformUserGroup was error effectRows", 1);
          }
          $permissionSetIds = $PlatformUserGroupHasPermissionSet->getPermissionSetIdsByGroupId($groupId);
          
          $permissionIds = (new PermissionSetHasPermissionCollection())->getPermissionIdsByIds($permissionSetIds);

          $PlatformUserGroupHasPermission = new PlatformUserGroupHasPermissionCollection($dao);
          $effectRows = $PlatformUserGroupHasPermission->append($groupId,$permissionIds);

          if( $effectRows!=count($permissionIds) ){
            throw new Exception("Append Permission ids into PlatformUserGroup was error effectRows", 1);
          }
          
          $dao->commit();
          return array();

        }
        catch(Exception $e) {
            $dao->rollback();
            throw $e;
        }
      }
      else {
          throw new DbOperationException("Begin transaction fail.");
      }
    }

      /**
      *  POST:    /group/platformuser
      *     Add a user group under root group.
      *
      *     @param $this->receiver array Struct of array( 'name' => <group name> )
      */
      private function supverisorGroupId(){
         return array('1');
      }

      /**
      *  	GET:    /group/platformuser/list/<pageNo:\d+>/<pageSize:\d+>
      *     get user group list without superisor Group
      *
      *     @return "records":{
      *               "0" : {
      *                 "id"             :"0",
      *                 "name"           :"others",
      *                 "parent_group_id":"1"
      *                },
      *               "2" : {
      *                 "id"             :"2",
      *                 "name"           :"group-2",
      *                 "parent_group_id":"1"
      *                },
      *               "3" : {
      *                 "id"             :"4",
      *                 "name"           :"group-4",
      *                 "parent_group_id":"1"
      *                }
      *             },
      *             "pageNo":1,
      *             "pageSize":1000,
      *             "totalPage":1
      *            }
      */
      public function getList($pageNo=1, $pageSize=1000 ){
         $data = array();
         try {
         	$collection = new PlatformUserGroupCollection();
         	$attributes = array("parent_group_id" => 1);
         	$actor 		= PlatformUser::instanceBySession();
         	
         	$collection->setActor($actor);
          $result = $collection->getRecords($attributes, $pageNo, $pageSize);
          
          if( $result['totalPage'] != 0 ){
             return $this->responser->send($result, $this->responser->OK());
          }
          else{               
             $this->responser->send( $data, $this->responser->NotFound() );
          }            
         }
         catch (AuthorizationException $e) {            
            $this->responser->send( $data, $this->responser->Unauthorized() );         
         }
         catch ( Exception $e) {
            $data['message'] = SERVER_ERROR_MSG;            
            $this->responser->send( $data, $this->responser->InternalServerError() );
         }
    }

  /**
  * GET:  /group/platformuser/permissionset/list/<pageNo:\d+>/<pageSize:\d+>
  *   Get permission set list.
  *
  * Response json: 
  * {
  *   "records": [
  *     {
  *       "id":"1", // group id
  *       "permission_id":"1",
  *       "entity":"platform_user_group",
  *       "entity_id":"0",
  *       "action":"read",
  *       "permission_name":"\u700f\u89bd\u7fa4\u7d44"
  *     },
  *     ... permissions depend group
  *   ]
  * }     
  */
  public function getPermissionSetList($pageNo=1, $pageSize=1000) {
      $collection = new PermissionSetCollection();
      return $collection->getRecords(array(),$pageNo,$pageSize);
  }


    /**
   	*	GET: 	/group/platformuser/<id:\d+>/permission/list/<pageNo:\d+>/<pageSize:\d+>
   	*  	Get permission list of a user group.
   	*
   	*	Response json: 
	*	{
	*		"records": [
	*			{
	*				"id":"1", // group id
	*				"permission_id":"1",
	*				"entity":"platform_user_group",
	*				"entity_id":"0",
	*				"action":"read",
	*				"permission_name":"\u700f\u89bd\u7fa4\u7d44"
	*			},
	*			... permissions depend group
	*		]
	*	}   	
   	*/
    public function getPermissionList($id, $pageNo=1, $pageSize=1000) {
    	$data = array();
        try {
        	$model = (new PlatformUserGroupCollection())->getById($id);         	
         	$actor = PlatformUser::instanceBySession();         	         	
         	
         	$model->setActor($actor);
            $records = $model->getPermissions($pageNo, $pageSize);
            
            if( $records['totalPage'] != 0 ){
               return $this->responser->send($records, $this->responser->OK());            
            }
            else{               
               $this->responser->send( $data, $this->responser->NotFound() );
            }            
        }
        catch(AuthorizationException $e) {            
            $this->responser->send( $data, $this->responser->Unauthorized() );         
        }
        catch(Exception $e) {
            $data['message'] = SERVER_ERROR_MSG;            
            $this->responser->send( $data, $this->responser->InternalServerError() );
        }
	}

	/**
   	*	GET: 	/group/platformuser/<id:\d+>/permission/list/<pageNo:\d+>/<pageSize:\d+>
   	*  	Get user list of a user group.
   	*
   	*	Response json: 
	*	{
	*		"records": [
	*			{
	*				"id":"1", // group id
	*				"permission_id":"1",
	*				"entity":"platform_user_group",
	*				"entity_id":"0",
	*				"action":"read",
	*				"permission_name":"\u700f\u89bd\u7fa4\u7d44"
	*			},
	*			... permissions depend group
	*		]
	*	}   	
	* 	@param $id int The group id.
   	*/
    public function getUserList($id, $pageNo=1, $pageSize=1000) {
    	$data = array();
        try {
        	$actor 		= PlatformUser::instanceBySession();
        	$model 		= (new PlatformUserGroupCollection())->getById($id);
        	$groupName 	= $model->getAttribute("name");

         	$model->setActor($actor);
            $records = $model->getUsers($pageNo, $pageSize);
            
            if( $records['totalPage'] != 0 ){
               	$records["name"] = $groupName;
               	return $this->responser->send($records, $this->responser->OK());            
            }
            else{               
               	$this->responser->send( $data, $this->responser->NotFound() );
            }            

        }
        catch(AuthorizationException $e) {            
            $this->responser->send( $data, $this->responser->Unauthorized() );         
        }
        catch(Exception $e) {
            $data['message'] = SERVER_ERROR_MSG;            
            $this->responser->send( $data, $this->responser->InternalServerError() );
        }
	}

	/**
	*	Update user's info that int a group.
	*	PUT: 	/group/platformuser/<groupId:\d+>/user/<userId:\d+>
	*
	*/
	public function updateUser($groupId, $userId) {
    //new groupId
  	$this->vertify("groupId", $this->receiver);
    //person permission set ids
  	$this->vertify("permissions", $this->receiver);        	

    $actor    = PlatformUser::instanceBySession(); 
  
    $personEffectRows = 0;
    $groupEffectRows = 0;

    //group
    $newGroupId = $this->receiver['groupId'];    
    $PlatformUserGroupHasPermission = new PlatformUserGroupHasPermissionCollection();
    $groupPermissionIds = $PlatformUserGroupHasPermission->getIdsByGroupId( $newGroupId );
    $PlatformUserHasGroupPermission = new PlatformUserHasGroupPermissionCollection();
    $PlatformUserHasGroupPermission->removeByUserId($userId);
    $groupEffectRows = $PlatformUserHasGroupPermission->append($userId, $groupPermissionIds);
    if( $groupEffectRows != count($groupPermissionIds) ){
      throw new Exception("Append group permission ids into group by PlatformUserHasGroupPermissionCollection.", 1);
    }
    $user     = (new PlatformUserCollection())->getById($userId);
    $isSuccess = $user->update(array('group_id'=>$newGroupId));

    //person
    $personPermissionSetIds = $this->receiver['permissions'];
    $PlatformUserHasPermissionSet = new PlatformUserHasPermissionSetCollection();
    $user->DestoryPersonPermissions();
    if( !empty($personPermissionSetIds) ){

      $effectRows = $PlatformUserHasPermissionSet->append($userId,$personPermissionSetIds);
      if( $effectRows != count($personPermissionSetIds) ){
        throw new Exception("Append new person PermissionSets into this user is error.", 1);
      }
      $permissionSet = new PermissionSetHasPermissionCollection();
      $personPermissions = $permissionSet->getPermissionIdsByIds($personPermissionSetIds);
      $personEffectRows = $user->appendPersonPermissions($personPermissions);
      if( $personEffectRows != count($personPermissions) ){
        throw new Exception("Append new person permissions into this user is error.", 1);
      }

    }

    return array( "personEffectRows"=> $personEffectRows, "groupEffectRows"=>$groupEffectRows );

	}

    public function updateGroupName($groupId) {
        $actor    = PlatformUser::instanceBySession();
        $collection = new PlatformUserGroupCollection();
        $attributes = array(
            'name' => $this->params("name")
        );
        $count = $collection->getById($groupId)->setActor($actor)->update($attributes);
        if($count != 1) {
            throw new DbOperationException("update updateGroupName record to DB fail.");
        }
        return array();
    }

  /**
  * Delete a group and move user to group other.
  *
  * @param $id int Group ID.
  * @param $dao object Group $dao.
  * @return $isSuccess boolean
  */
  private function movePlatformUserGroupId( $oldId, $newId, &$dao=null ){
    $collection = new PlatformUserCollection( $dao );
    $result = $collection->getUserCountByGroupId($oldId);
    $effectRows = $collection->updateGroupIdById($newId, $result['ids'] );
    if( $result['count'] != $effectRows ){
      throw new Exception("Update PlatformUser by group id is fail.", 1);
    }
    return true;
  }

	/**
	*	DELETE: /group/platformuser/<groupId:\d+>
	*	Delete a group and move user to group other.
	*
	*	@param $id int Group ID.
	*/
	public function remove($id) {

  	$actor 		= PlatformUser::instanceBySession();
  	$collection = new PlatformUserGroupCollection();
    $dao = $collection->dao;
    if($dao->transaction()) {
      try {

          $this->movePlatformUserGroupId( $id, UserGroupController::ORTER_GROUP_ID, $dao );

          $model = $collection->getById($id);
          $model->setActor($actor);
          $rowCount = $model->destroy();
          if(count($rowCount) == 0) {
            throw new DbOperationException("Delete user group fail.", 1);
          }

          $dao->commit();
          return array( "effectRow"=>$rowCount );
      }
      catch(Exception $e) {
          $dao->rollback();
          throw $e;
      }
    }
    else {
        throw new DbOperationException("Begin transaction fail.");
    }
	
  }

}




?>