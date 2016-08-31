<?php
/**
*	GroupBuyingMasterUserCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package GroupBuyingMasterUserCollection
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'system/exception/AuthorizationException.php' );
require_once( FRAMEWORK_PATH . 'system/exception/OperationConflictException.php' );
require_once( FRAMEWORK_PATH . 'models/GroupBuyingMasterUser.php' );
require_once( FRAMEWORK_PATH . 'extends/AuthenticateHelper.php' );

/**
*	GroupBuyingMasterUserCollection access entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package GroupBuyingMasterUserCollection
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class GroupBuyingMasterUserCollection extends PermissionDbCollection {

	private $helper;

	public function __construct(&$dao=null) {
		parent::__construct($dao);
		$this->helper = new AuthenticateHelper();
	}

	/**
	*	Create a salt for hash.
	*
	*	@param $length 		string 	Salt string's length.	
	*	@return string  	Salt string.
	*/
	public function generateSalt($length = 5) {
		return $this->helper->generateSalt($length);
	}

	/**
	*	Verify password by hash with salt.
	*
	*	@param $password string
	*	@param $hash 	 string
	*	@param $salt 	 string
	*	@return bool 	 Retrun true when password is correct.
	*/
	public function passwordVerify($password, $hash, $salt) {
		return $this->helper->passwordVerify($password, $hash, $salt);
	}
	
	/**
	*	hash password
	*
	*	@param $password 	string 
	*	@param $salt  		string
	*	@return string hash code
	*/
	public function hash($password, $salt) {
		return $this->helper->hash($password, $salt);
	}

	/**
	*	Register a group buying master account.
	*
	*	@param $name 					string 	The user name of the user register.
	*	@param $email 					string 	The email name of the user register.	
	*	@param $account 				string  The account of user.
	*	@param $passowrd 				string  The password clear text of user.
	*	@param $bankAccount 			string  The account number of bank.
	*	@param $bankCode 				string  The bank code.
	*	@param $bankName 				string  The bank's name.
	*	@param $bankAccountName 		string  The	user account in the bank.
	*	@param $creatorId 				int  	The	platform user id that create the user.
	*	@return Model  		If success return user's model or fail return null.
	*/
	public function register(	     
		        				$name, 
		        				$email, 
		        				$account, 
		        				$password, 
		        				$bankAccount, 
		        				$bankCode, 
		        				$bankName, 
		        				$bankAccountName, 
		        				$creatorId
				        	) {

		$result = $this->getRecords(array(			
			'account' => $account
		));		

		if($result["totalPage"] == 0) {
			$salt = $this->generateSalt();
			$hash = $this->hash($password , $salt);
			$now  = date('Y-m-d H:i:s');

			$attributes = array(
				'name'  => $name,
				'email'  => $email,
				'account' => $account,
				'hash' => $hash,
				'salt' => $salt,
				'bank_account' => $bankAccount,
				'bank_code' => $bankCode,
				'bank_name' => $bankName,
				'bank_account_name' => $bankAccountName,
				'create_date' => $now,
				'edit_date' => $now,
				'creator_id' => $creatorId,
				'editor_id' => $creatorId
			);

			$effectRows = $this->create($attributes);				

			if($effectRows > 0) {				
				return $this->lastCreated();
			}
			else {
				throw New DbOperationException("Insert database fail.", 1);
			}
		}
		else {			
			throw new OperationConflictException("User already exsits.", 1);
		}
	}

	/**
	*	Master login.
	*
	*	@param $domain 		string 	The domain name of the user register(prepar).	
	*	@param $account 	string  The account of user.
	*	@param $passowrd 	string  The password clear text of user.
	*	@return Model  		User's model.
	*/
	public function login($domain, $account, $password) {

		$result = $this->getRecords(array(			
			"account" => $account
		));

		if($result["totalPage"] > 0) {
						
			$record = $result["records"][0];
			$hash 	= $record["hash"];
			$salt 	= $record["salt"];

			if($this->passwordVerify($password, $hash, $salt)) {				
				return $record;
			}
			else {
				throw new AuthorizationException("User account, domain or password incorrect", 1);
			}

		}
		else {
			throw new AuthorizationException("User not exsits in the domain", 1);
		}
	}

	/* DbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "gb_master_user";
	}

	public function getModelName() {
		return "GroupBuyingMasterUser";
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
