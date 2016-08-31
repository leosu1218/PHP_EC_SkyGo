<?php
require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'system/exception/OperationConflictException.php' );
require_once( FRAMEWORK_PATH . 'system/exception/InvalidAccessParamsException.php' );
require_once( FRAMEWORK_PATH . 'system/exception/DbOperationException.php' );
require_once( FRAMEWORK_PATH . 'extends/OAuthHelper/OAuthUserCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/AuthenticateHelper.php' );
require_once( FRAMEWORK_PATH . 'models/ConsumerUser.php' );

/**
 * Class ConsumerUserCollection
 */
class ConsumerUserCollection extends PermissionDbCollection implements OAuthUserCollection {

    const DOMAIN_NAME   = "109life.com";
    const GROUP_ID      = 1;

    private $helper;

    public function __construct(&$dao=null) {
        parent::__construct($dao);
        $this->helper = new AuthenticateHelper();
    }

    /**
     * Get const domain name.
     * @return string
     */
    public function getDomainName() {
        return self::DOMAIN_NAME;
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
     *	User login.
     *
     *	@param $domain 		string 	The domain name of the user register.
     *	@param $account 	string  The account of user.
     *	@param $password 	string  The password clear text of user.
     *	@return Model  		User's model.
     */
    public function login($domain, $account, $password) {
        $result = $this->getRecords(array(
            "domain_name" => $domain,
            "account" => $account
        ));

        if($result["totalPage"] == 0) {
            throw new AuthorizationException("User not exists in the domain", 1);
        }

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

    /**
     * Register new user.
     * @param $domain string 	The domain name of the user register.
     * @param $account string  The account of user.
     * @param $password string  The password clear text of user.
     * @param $groupId integer The id of group that user want to register.
     * @param array $userInfo array   Extends user information.
     * @return Model  		If success return user's model or fail return null.
     * @throws AuthorizationException
     * @throws DbOperationException
     * @throws Exception
     * @throws OperationConflictException
     */
    public function register($domain, $account, $password, $groupId, $userInfo = array()) {

        $result = $this->getRecords(array(
            "domain_name" => $domain,
            'account' => $account
        ));

        if($result["recordCount"] > 0) {
            throw new OperationConflictException("User already exists.", 1);
        }

        if(!array_key_exists("name", $userInfo)) {
            throw new InvalidAccessParamsException("Missing params name of userInfo.");
        }

        $salt = $this->generateSalt();
        $hash = $this->hash($password , $salt);

        $attributes = array_merge($userInfo, array(
            'domain_name'   => $domain,
            'account'       => $account,
            'hash'          => $hash,
            'salt'          => $salt,
            'group_id'      => $groupId,
            "create_datetime"       => date("Y-m-d H:i:s")
        ));

        $effectRows = $this->create($attributes);

        if($effectRows == 0) {
            throw New DbOperationException("Insert database fail.", 1);
        }

        return $this->lastCreated();
    }

    /* Interface OAuthUserCollection methods */
    /**
     * Get user's record by provider open id.
     * @param string $providerType
     * @param string $providerId
     * @return array
     */
    public function getRecordByProviderId($providerType='', $providerId='', $attributes=array()) {
        $conditions = array(
            "and",
            "oauth_type=:oauth_type",
            "oauth_id=:oauth_id");

        $params = array(
            ":oauth_type" => $providerType,
            ":oauth_id" => $providerId);

        return $this->getRecordByCondition($conditions, $params);
    }

    /**
     * Create new user record by provider id.
     * @param string $name
     * @param string $account
     * @param string $providerType
     * @param string $providerId
     * @param array $attributes
     * @return int|void
     * @throws AuthorizationException
     * @throws DbOperationException
     */
    public function createRecordByProviderId($name='', $account='', $providerType='', $providerId='', $attributes=array()) {

        if(!isset($attributes["email"])) {
            $attributes["email"] = null;
        }

        $rowCount = $this->create(array(
            "domain_name"   => self::DOMAIN_NAME,
            "group_id"      => self::GROUP_ID,
            "name"          => $name,
            "account"       => $account,
            "email"         => $attributes["email"],
            "hash"          => "------",
            "salt"          => "------",
            "oauth_type"    => $providerType,
            "oauth_id"      => $providerId,
            "create_datetime"       => date("Y-m-d H:i:s")
        ));

        if($rowCount == 1) {
            return $this->lastCreated()->getId();
        }
        else {
            throw new DbOperationException("Create new consumer user fail.");
        }
    }

    public function searchConsumerByKey($pageNo, $pageSize, $search=array()) {

        $result = $this->getDefaultRecords($pageNo, $pageSize);
        $table = $this->getTable();
        $conditions = array('and','1=1');
        $params = array();

        $this->dao->fresh();
        $this->dao->select(array(
            'cu.*'
        ));

        $this->dao->from("$table cu");

        if(array_key_exists('keyword', $search)) {
            // append product name keyword conditions.
            $keyword = $search['keyword'];
            array_push($conditions, 'cu.name like :name');
            $params[':name'] = "%$keyword%";
        }
        $this->dao->order('cu.id DESC');
        $this->dao->where($conditions,$params);
        $result['recordCount'] = intval($this->dao->queryCount());
        $result['totalRecord'] = $result['recordCount'];
        $result["totalPage"] = intval(ceil($result['totalRecord'] / $pageSize));
        $this->dao->paging($pageNo, $pageSize);
        $result["records"] = $this->dao->queryAll();


        return $result;
    }

    /* Class PermissionDbCollection methods */
    /* DbCollection abstract methods. */
    /**
     *	Get the entity table name.
     *
     *	@return string
     */
    public function getTable() {
        return "consumer_user";
    }

    public function getModelName() {
        return "ConsumerUser";
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

    /**
     *	create password to send
     *
     *	@return string
     */
    public function createPassword($length = 7) {

        $words = '0123456789';
        $wordsLength = strlen($words);
        $saltString         = '';

        for ($i = 0; $i < $length; $i++) {
            $saltString .= $words[rand(0, $wordsLength - 1)];
        }

        return $saltString;
    }

}


?>