<?php
require_once(dirname(__FILE__) . "/OAuthUserCollection.php");

/**
 * Class OAuthLoginService
 */
abstract class OAuthLoginService {

    protected $session;
    protected $account;
    protected $password;

    public function __construct() {
        $this->session = GeneralSession::getInstance();
    }

    /**
     * Define the login service provider name.
     * @return string
     */
    abstract public function getProviderName();

    /**
     * Request login to service provider.
     */
    abstract public function request();

    /**
     * Response login request.
     */
    abstract public function handleResponse(OAuthUserCollection $userCollection=null);

    /**
     * Save account that want to login.
     * @param string $account
     */
    public function setAccount($account='') {
        $this->account = $account;
    }

    /**
     * Save password that want to login.
     * @param string $password
     */
    public function setPassword($password='') {
        $this->password = $password;
    }

    /**
     * Check user has login by the FB service.
     * @return bool
     */
    public function hasLogin() {
        $oauth = $this->session->oauth;
        if(is_array($oauth)) {
            if(array_key_exists("type", $oauth) && array_key_exists("info", $oauth)) {
                return ($oauth["type"] == $this->getProviderName()) && (is_array($oauth));
            }
        }
        return false;
    }

    /**
     * Save user's info to session.
     * @param array $info
     * @throws OAuthHelperException
     */
    public function saveUserInfoToSession($info=array()) {
        if(count($info) == 0) {
            throw new OAuthHelperException("Can't save null user info.");
        }

        $this->session->oauth = array(
            "type" => $this->getProviderName(),
            "info" => $info
        );
    }

    /**
     * Get user info by session;
     * @return array
     */
    public function getUserInfoBySession() {
        return $this->session->oauth;
    }
}

?>