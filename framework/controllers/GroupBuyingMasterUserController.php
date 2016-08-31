<?php
/**
*  GroupBuyingMasterUserController code.
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @author Jai Chien <jaichien@syncte-infinity.com>
*  @copyright 2014 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/GroupBuyingMasterUserCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/GroupBuyingMasterUserHasProductGroupCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/GeneralSession.php' );
require_once( FRAMEWORK_PATH . 'models/PlatformUser.php' );

class GroupBuyingMasterUserController extends RestController {

   	public function __construct() {
    	parent::__construct();      
   	}

   	/**
   	*	GET: 	/user/groupbuyingmaster/<id:\d+>
   	*	Get user info API
   	*
   	*/
   	public function getById($id) {
   		$data = array();
   		try {
   			$actor 			= PlatformUser::instanceBySession();	             
	        $collection 	= new GroupBuyingMasterUserCollection();
	        $model 			= $collection
	        						->setActor($actor)
	        						->getById($id);

	        $record			= $model->setActor($actor)
	        						->toRecord();
	        	        
	        if(count($record) > 0) {	        
	        	unset($record["hash"]);
	        	unset($record["salt"]);	
	        	$record["groups"] = $model->getProductGroup(1, 9999);
	        	$this->responser->send( $record, $this->responser->OK() );
	        }
	        else {
	        	$this->responser->send( $data, $this->responser->NotFound());
	        }
   		}
   		catch (AuthorizationException $e) {
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->Unauthorized() );
	    }
   		catch(Exception $e) {
   			$data['message'] = SERVER_ERROR_MSG;
	        $this->responser->send( $data, $this->responser->InternalServerError() );
   		}
   	}

   	/**
   	*	Update record by id.
   	*
   	*	@param $attribute array New value for the record.
   	*	@param $id int The record's id.
   	*/
   	private function updateById($attribute, $id) {
   		$data = array();
   		try {   			
   			$actor 			= PlatformUser::instanceBySession();	             
	        $collection 	= new GroupBuyingMasterUserCollection();
	        $model 			= $collection
	        						->setActor($actor)
	        						->getById($id);

	        $effectRows		= $model->setActor($actor)
	        						->update($attribute);
	        	        
	        if($effectRows > 0) {	        	        		        
	        	$this->responser->send( $data, $this->responser->OK() );
	        }
	        else {
	        	throw new DataAccessResultException("Update but no change.", 1);	        	
	        }
   		}
   		catch(DataAccessResultException $e) {
			$data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->Conflict());
		}
		catch(DbOperationException $e) {
	    	$data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->InternalServerError());
	    }	    
	    catch(InvalidAccessParamsException $e) {
	    	$data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->BadRequest());
	    }
	    catch(AuthorizationException $e) {
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->Forbidden() );         
	    }
	    catch(Exception $e) {
	        $data['message'] = SERVER_ERROR_MSG;	        
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->InternalServerError() );
	    }
   	}

   	/**
   	*	PUT: 	/user/groupbuyingmaster/<id:\d+>/base
   	*	Update user base info.
   	*
   	*
   	*/
   	public function updateBaseById($id) {
   		$data = array();
   		try {   			
   			$this->updateById(array(
   				"name" 	=> $this->params("name"),
   				"email" => $this->params("email"),
   			), $id);
   		}
   		catch(InvalidAccessParamsException $e) {
			$data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->BadRequest());
		}		
	    catch(Exception $e) {
	        $data['message'] = SERVER_ERROR_MSG;	        	        
	        $this->responser->send( $data, $this->responser->InternalServerError() );
	    }
   	}

   	/**
   	*	PUT: 	/user/groupbuyingmaster/<id:\d+>/bank
   	*	Update user bank info.
   	*
   	*	
   	*/
   	public function updateBankById($id) {
   		$data = array();
   		try {   			
   			$this->updateById(array(
   				"bank_account" 	=> $this->params("bankAccount"),
   				"bank_code" 	=> $this->params("bankCode"),
   				"bank_name" 	=> $this->params("bankName"),
   				"bank_account_name" => $this->params("bankAccountName"),
   			), $id);
   		}
   		catch(InvalidAccessParamsException $e) {
			$data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->BadRequest());
		}		
	    catch(Exception $e) {
	        $data['message'] = SERVER_ERROR_MSG;
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->InternalServerError() );
	    }
   	}

   	/**
   	*	PUT: 	/user/groupbuyingmaster/<id:\d+>/account
   	*	Update user system account info.
   	*
   	*	
   	*/
   	public function updateAccountById($id) {
   		$data = array();
   		try {   			

   			$collection = new GroupBuyingMasterUserCollection();
   			$salt 		= $collection->generateSalt();
   			$hash		= $collection->hash($this->params("password"), $salt);

   			$this->updateById(array(
   				"hash" 	=> $hash,
   				"salt"  => $salt
   			), $id);
   		}
   		catch(InvalidAccessParamsException $e) {
			$data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->BadRequest());
		}		
	    catch(Exception $e) {
	        $data['message'] = SERVER_ERROR_MSG;
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->InternalServerError() );
	    }
   	}

   	/**
   	*	PUT: 	/user/groupbuyingmaster/<id:\d+>/group
   	*	Update user system account info.
   	*
   	*	@param $id int The group buying master id.	
   	*/
   	public function appendGroupById($id) {
   		$data = array();
		   $actor 			= PlatformUser::instanceBySession();	             
         $collection 	= new GroupBuyingMasterUserCollection();
         $model 			= $collection
        						->setActor($actor)
        						->getById($id);

         $effectRows		= $model->setActor($actor)
        						->appendProductGroup($this->params("ids"));

         if($effectRows > 0) {	        	        		        
        	   return $data;
         }
         else {
        	   throw new DataAccessResultException("Update but no change.", 1);	        	
         }
   	}

   	public function removeGroupById($id, $groupId){
   		$data = array();
		   $actor 			= PlatformUser::instanceBySession();	             
         $collection 	= new GroupBuyingMasterUserHasProductGroupCollection();
         $attributes    = array('gb_master_user_id'=>$id,'product_group_id'=>$groupId);
         $model = $collection->get( $attributes );
         $result = $model->destroy();
         if($result) {
         	return $data;
         }
         else {
           throw new DataAccessResultException("Remove but no change.[$attributes]", 1);
         }
   	}
   	
   	/**
   	*	GET: 	/user/groupbuyingmaster/self
   	*	Get self's user info API. There work under login state.
   	*	Response json: 
   	*	{
   	*		"account":"admin",		
   	*		"id":"1",
   	*		"name":"admin"
   	*	}   
   	*
   	*/
   	public function getSelf() {

   		$data = array();
   		try {   			
   			$response = $this->getFromSession();
   			$response = $this->getUserInfo($response);
   			$this->responser->send($response, $this->responser->OK());
   		}
   		catch (AuthorizationException $e) {
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->Unauthorized() );         
	    }
   		catch(Exception $e) {
   			$data['message'] = SERVER_ERROR_MSG;
	        // $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->InternalServerError() );
   		}
   	}

   	// Maybe will is useful to project, but not now.
   	/**
   	*	GET: 	/user/self/permission
   	*	User get permission list itself.
   	*
   	*	Response json: 
   	*	{
   	*		"group": [
   	*			{
   	*				"id":"1",
   	*				"permission_id":"1",
   	*				"entity":"platform_user_group",
   	*				"entity_id":"0",
   	*				"action":"read",
   	*				"permission_name":"\u700f\u89bd\u7fa4\u7d44"
   	*			},
   	*			... permissions depend group
   	*		],
   	*		"user": [
   	*			{
   	*				"id":"1",
   	*				"permission_id":"1",
   	*				"entity":"platform_user_group",
   	*				"entity_id":"0",
   	*				"action":"read",
   	*				"permission_name":"\u700f\u89bd\u7fa4\u7d44"
   	*			},
   	*			... permissions depend user
   	*		]
   	*	}   	
   	*
   	*/
   	public function getSelfPermission() {

   		$this->responser->send(array(), $this->responser->NotImplemented());
   	}
   	
   	/**
   	*	POST: 	/user/groupbuyingmaster/login
   	*  	User login API, 
   	*	Response json: 
   	*	{
   	*		"account":"admin",	
   	*		"id":"1",
   	*		"name":"admin"
   	*	}   	
   	*
   	*  	@param $this->receiver array array( 'domain' => <domain name>, 
   	*                             'account' => <user account>,
   	*                             'password' => <user password> )
   	*/
   	public function login() {   		

    	$data = array();
	    try {
	        // TODO refactoring receiver class.	        
	      	$this->vertify("domain", $this->receiver);
	      	$this->vertify("account", $this->receiver);
	      	$this->vertify("password", $this->receiver);

	        $domain     = $this->receiver['domain'];
	        $account    = $this->receiver['account'];
	        $password   = $this->receiver['password'];

	        $user 		= (new GroupBuyingMasterUserCollection())->login($domain, $account, $password);	        
	        $response 	= $this->getUserInfo($user);
	        
	        $this->saveToSession($user);	        	       
	        $this->responser->send($response, $this->responser->OK());
	    }      
	    catch (AuthorizationException $e) {
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->Forbidden() );         
	    }
	    catch (Exception $e) {
	        $data['message'] = SERVER_ERROR_MSG;	        
	        $this->responser->send( $data, $this->responser->InternalServerError() );
	 	}
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

        if( count(($params[$name])) == 0 ){
            $this->responser->send(array("message" => "the [$name] filed is null."), $this->responser->BadRequest());
        }
   	}
   	
   	/**
   	*	POST: 	/user/logout
   	*  	User login API, 
   	*	Response json: 
	*	{
	*	}   	
   	*   	
   	*/
   	public function logout() {
   		
    	$data = array();
	    try {
	    	$this->clearSession();
	        $this->responser->send($data, $this->responser->OK());
	    }      	    
	    catch (Exception $e) {
	        $data['message'] = SERVER_ERROR_MSG;
	        $this->responser->send( $data, $this->responser->InternalServerError() );
	 	}
   	}
   	
   	/**
   	*	Get user info from session variable.
   	*
   	*	@return array User info.
   	*/
   	private function getFromSession() {   		
   		return GroupBuyingMasterUser::getRecordBySession();  
   	}

   	/**
   	*	Clear all of session variables.
   	*
   	*/
   	private function clearSession() {   		
   		GroupBuyingMasterUser::clearSession();
   	}
   	
   	/**
   	*	Saving user info into session variable.
   	*
   	*	@param $userInfo array user info.
   	*/
   	private function saveToSession($userInfo) {     		
		GroupBuyingMasterUser::saveToSession($userInfo);
   	}
   	
   	/**
   	*	Get user info from user row data for api response.
   	*
   	*	@param $userInfo array User data of row.
   	*	@return array 	Response data.
   	*/
   	private function getUserInfo($userInfo) {   		
        $response = array(
        	"account" 		=> $userInfo["account"],        	
        	"id"			=> $userInfo["id"],
        	"name"			=> $userInfo["name"]
		);

		return $response;		
   	}

   	// Maybe will is useful to project, but not now.
    /**
   *  PUT:  /user/self
   *  platform user modify itself password
   *
   *  @param $this->receiver array array( 'password' => <user password> , 'newpassword' => <user new password> ) 
   *                             
   */
   public function updateSelf(){
   	 	$this->responser->send(array(), $this->responser->NotImplemented());
    }
  
   	/**
   	*	POST: 	/user/groupbuyingmaster/register
   	*  	User register API, 
   	*	Response json: 
	*	{
	*	}   	
   	*
   	*  	@param $this->receiver array array( 
   	*                             'name' => <string>,
   	*                             'email' => <string>,
   	*                             'account' => <string>,
   	*                             'password' => <string>,
   	*                             'bankAccount' => <string>,
   	*                             'bankCode' => <string>,
   	*                             'bankName' => <string>,
   	*                             'bankAccountName' => <string>,
   	*                             'productGroupIds' => <array(int,int.int)> )
   	*/
   	public function register() {

    	$data = array();
	    try {
	        // TODO refactoring receiver class.	      
	    	$this->vertify("name", $this->receiver);
	    	$this->vertify("email", $this->receiver);
	    	$this->vertify("account", $this->receiver);
	    	$this->vertify("password", $this->receiver);
	    	$this->vertify("bankAccount", $this->receiver);
	    	$this->vertify("bankCode", $this->receiver);
	    	$this->vertify("bankName", $this->receiver);
	    	$this->vertify("bankAccountName", $this->receiver);	    	
	    	$this->vertify("productGroupIds", $this->receiver);
	    		        
	        $actor 		= PlatformUser::instanceBySession();
	        
	        $creatorId			= $actor->getId();
	        $name     			= $this->receiver['name'];
	        $email    			= $this->receiver['email'];
	        $account   			= $this->receiver['account'];
	        $password   		= $this->receiver['password'];
	        $bankAccount   		= $this->receiver['bankAccount'];
	        $bankCode   		= $this->receiver['bankCode'];
	        $bankName   		= $this->receiver['bankName'];
	        $bankAccountName   	= $this->receiver['bankAccountName'];
	        $productGroupIds   	= $this->receiver['productGroupIds'];	        
	        $collection 		= new GroupBuyingMasterUserCollection();
	        $user 				= $collection
	        						->setActor($actor)
	        						->register(	     
				        				$name, 
				        				$email, 
				        				$account, 
				        				$password, 
				        				$bankAccount, 
				        				$bankCode, 
				        				$bankName, 
				        				$bankAccountName, 
				        				$creatorId
				        			);

	        $effectRows 		= $user
	        						->setActor($actor)
	        						->appendProductGroup($productGroupIds);
	        
	        if($effectRows > 0 ) {
	        	$response 	= $this->getUserInfo($user->toRecord());	       
	        	$this->responser->send($response, $this->responser->OK());	
	        }
	        else {
	        	throw new OperationConflictException("Append product group to group buying master fail.", 1);
	        }
	    }      
	    catch(OperationConflictException $e) {
	    	$data['message'] = $e->getMessage();
	    	$this->responser->send( $data, $this->responser->Conflict() );
	    }
	    catch (AuthorizationException $e) {
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->Forbidden() );         
	    }
	    catch (Exception $e) {
	        $data['message'] = SERVER_ERROR_MSG;
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->InternalServerError() );
	 	}
   	}
   	
   	/**
   	*	GET: 	/user/groupbuyingmaster/list/<pageNo:\d+>/<pageSize:\d+>
   	*	Get of the user list.
   	*
   	*	Response json: 
	*	{
	*		"records": [
	*			{
	*				"id":"1",
	*				"name":"user name",
	*				"email":"user@email.com",
	*				"account":"userAccount",	
	*			},
	*			...	
	*		],
	*		"pageSize": 100,
	*		"pageNo": 1,
	*		"toatalPage": 1
	*	}   	
   	*   	
   	*	@param $pageNo int 		The record page's number.
   	*	@param $pageSize int 	The record page's size.    	
   	*/
   	public function getList($pageNo, $pageSize) {
   		$data = array();
      	try {      		      				
  			$actor 		= PlatformUser::instanceBySession();
  			$filter		= array('id', 'account', 'name', 'email');
  			$records = (new GroupBuyingMasterUserCollection())
  							->setActor($actor)
  							->getRecords($data, $pageNo, $pageSize, $filter ,'id DESC');

  			if( $records['totalPage'] != 0 ){
               return $this->responser->send($records, $this->responser->OK());            
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
}




?>