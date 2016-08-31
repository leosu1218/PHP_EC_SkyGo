<?php
require_once(dirname(__FILE__) . "/../OAuthLoginService.php");
require_once(dirname(__FILE__) . "/../../GeneralSession.php");
require_once( FRAMEWORK_PATH . 'collections/ConsumerUserCollection.php' );

/**
 * Class SelfLoginService
 */
class SelfLoginService extends OAuthLoginService {


    /* OAuthLoginService abstract methods. */
    /**
     * Define the login service provider name.
     * @return string
     */
    public function getProviderName() {
        return "self";
    }

    /**
     * Request login to service provider.
     */
    public function request() {
        throw new OAuthHelperException("Not support the method.");
    }

    /**
     * Handling received Facebook login response data.
     * @param OAuthUserCollection $userCollection
     */
    public function handleResponse(OAuthUserCollection $userCollection=null) {
        if(!($userCollection instanceof ConsumerUserCollection)) {
            throw new OAuthHelperException("Only support instance of ConsumerUserCollection.");
        }

        $domain     = $userCollection->getDomainName();
        $account    = $this->account;
        $password   = $this->password;
        $user       = $userCollection->login($domain, $account, $password);
        $info = array(
            "id"        => $user["id"],
            "name"      => $user["name"],
            "email"     => $user["email"],
            "phone"      => $user["phone"],
            "hash"      => $user["hash"],
            "salt"      => $user["salt"],
            "oauthId"   => 0,
        );

        $this->saveUserInfoToSession($info);
        return $this->getUserInfoBySession();
    }
}

?>