<?php
require_once(dirname(__FILE__) . "/../OAuthHelper.php");
require_once(dirname(__FILE__) . "/FacebookLoginService.php");
require_once(dirname(__FILE__) . "/GoogleLoginService.php");
require_once(dirname(__FILE__) . "/SelfLoginService.php");
require_once(dirname(__FILE__) . "/../../GeneralSession.php");

class GeneralOAuthHelper extends OAuthHelper {

    private $serviceCollection;

    public function __construct() {
        $this->serviceCollection = array(
            "fb"        => new FacebookLoginService(),
            "google"    => new GoogleLoginService(),
            "self"      => new SelfLoginService()
        );
    }

    /**
     * Create a login service.
     * @param string $type
     * @return OAuthLoginService
     */
    public function createLoginService($type='') {
        $login = null;
        if(array_key_exists($type, $this->serviceCollection)) {
            $login = $this->serviceCollection[$type];
        }
        else {
            throw new OAuthHelperException("Invalid oauth service provider type [$type].");
        }

        return $login;
    }

    /**
     * Check user has login by any authentication the application applied.
     * @return bool
     */
    public function getUserInfo() {
        $userInfo = array();
        foreach($this->serviceCollection as $type => $service) {
            if($service->hasLogin()) {
                $userInfo = $service->getUserInfoBySession();
            }
        }

        if(count($userInfo) == 0){
            throw new AuthorizationException("Not logon.");
        }
        return $userInfo;
    }

    /**
     * Logout the user by session.
     */
    public function logout() {
        GeneralSession::getInstance()->clear("oauth");
    }
}

?>