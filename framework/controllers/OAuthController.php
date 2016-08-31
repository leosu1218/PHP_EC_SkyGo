<?php
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'extends/OAuthHelper/General/GeneralOAuthHelper.php' );
require_once( FRAMEWORK_PATH . 'system/exception/AuthorizationException.php' );
require_once( FRAMEWORK_PATH . 'collections/ConsumerUserCollection.php' );

/**
 * Class OAuthController
 *  PHP version 5.3
 *
 *  @package Controller
 *  @author Rex Chen <rexchen@synctech-infinity.com>
 *  @copyright 2015 synctech.com
 */
class OAuthController extends RestController {

    const SELF_PROVIDER = "self";

    /**
     * Construct the class.
     */
    public function __construct() {
        parent::__construct();
        $this->oauth = new GeneralOAuthHelper();
    }

    /**
     * Handling user login response result.
     * @param string $type
     * @return array user's info
     */
    private function handleResponse($type='') {
        $login = $this->oauth->createLoginService($type);
        return $login->handleResponse($this->createUserCollection());
    }

    /**
     *	POST: 	/oauth/user/login
     *  	User login API,
     *	Response json:
     *	{
     *		"account":"admin",
     *		"domainName":"skygo.com.tw",
     *		"groupId":"2",
     *		"id":"1",
     *		"name":"admin"
     *	}
     *
     *  	@param $this->receiver array array(
     *                             'account' => <user account>,
     *                             'password' => <user password> )
     */
    public function login() {
        $login = $this->oauth->createLoginService(self::SELF_PROVIDER);
        $login->setAccount($this->params("account"));
        $login->setPassword($this->params("password"));

        return $login->handleResponse($this->createUserCollection());
    }

    /**
     * POST: 	/oauth/user/register
     * Create new user without OAuth service provider.
     */
    public function register() {
        $collection = $this->createUserCollection();
        $account  = $this->params("account");
        $password = $this->params("password");

        $userInfo = array(
            "name"          => $this->params("name"),
            "email"         => $this->params("email"),
            "phone"         => $this->params("phone", false),
        );

        $user = $collection->register(
            ConsumerUserCollection::DOMAIN_NAME,
            $account,
            $password,
            ConsumerUserCollection::GROUP_ID,
            $userInfo);

        $login = $this->oauth->createLoginService(self::SELF_PROVIDER);
        $login->setAccount($this->params("account"));
        $login->setPassword($this->params("password"));

        return $login->handleResponse($collection);
    }

    /**
     * Build http uri by regexp.
     * @param array $params
     * @param string $pattern
     * @return string
     */
    private function httpBuildUri($params=array(), $url="") {
        foreach($params as $key => $value) {
            $url = str_replace($key, $value, $url);
        }
        return $url;
    }

    /**
     * Get a OAuthUserCollection.
     * @return OAuthUserCollection
     */
    private function createUserCollection() {
        return new ConsumerUserCollection();
    }
    /**
     * GET: 	/oauth/user/info
     * Get user's info from session.
     * return array Return array() when not login.
     */
    public function get() {
        $userInfo = $this->oauth->getUserInfo();
        if(count($userInfo) == 0) {
            throw new AuthorizationException("Not Login");
        }

        return $userInfo;
    }

    /**
     * GET: 	/oauth/logout
     * Logout the session.
     */
    public function logout() {
        $this->oauth->logout();
        return array();
    }

    /**
     * GET: 	/oauth/login/<serviceProvider:\w+>
     * Login by OAuth service provider's API.
     * @param $serviceProvider OAuth service provider.
     */
    public function requestLogin($serviceProvider) {
        $login = $this->oauth->createLoginService($serviceProvider);
        $login->request();
        return array();
    }

    /**
     * GET: 	/oauth/receive/<serviceProvider:\w+>
     * Handling oauth service provider response for login.
     * @param $serviceProvider
     */
    public function handleLoginResponse($serviceProvider) {
        $user       = $this->handleResponse($serviceProvider);
        $uri        = $this->httpBuildUri($user["info"], "oauth/login/success/name");
        header("Location: /index.html#!/$uri");
        exit;
        return array();
    }

    public function getMail() {
        $userInfo = $this->oauth->getUserInfo();
        if(count($userInfo) == 0) {
            throw new AuthorizationException("Not Login");
        }

        return $userInfo;
    }

    /**
     * POST: 	/oauth/user/check
     */
    public function check() {
        $login = $this->oauth->createLoginService(self::SELF_PROVIDER);
        $login->setAccount($this->params("account"));
        $login->setPassword($this->params("password"));
        $checkPassword = $login->handleResponse($this->createUserCollection());
        if ($checkPassword) {
            $collection = new ConsumerUserCollection();
            $account  = $this->params("account");
            $password = $this->params("password");
            $newpassword = $this->params("newpassword");
            $userId = $this->params("id");
            
            $salt = $collection->generateSalt();
            $hash = $collection->hash($newpassword,$salt);

            $model = $collection->getById( $userId );
            $result = $model->update( array("hash"=>$hash,"salt"=>$salt) );
            
            $login->setAccount($this->params("account"));
            $login->setPassword($this->params("newpassword"));
            return $login->handleResponse($this->createUserCollection());
        }else {
            return false;
        }


        
    }










}


?>