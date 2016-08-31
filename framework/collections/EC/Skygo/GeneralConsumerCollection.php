<?php
require_once( FRAMEWORK_PATH . 'extends/OAuthHelper/General/GeneralOAuthHelper.php' );
require_once(dirname(__FILE__) . '/../UnifiedUserCollection.php');

/**
 * Class GeneralConsumerCollection
 */
class GeneralConsumerCollection implements UnifiedUserCollection {

    private $userInfo = null;

    public function __construct(UnifiedCartCollection $cart=null) {
        $this->oauth = new GeneralOAuthHelper();
        $userInfo = $this->oauth->getUserInfo();
        if(count($userInfo) == 0) {
            throw new AuthorizationException("Not Login");
        }

        $this->userInfo = $userInfo;
    }

    /**
     * Get user info from session.
     * @return array Array("oauthId" => <string>, "name" => <string>, "email" => <string>)
     */
    public function getUnifiedBySession() {
        return $this->userInfo["info"];
    }

    /**
     * Get user's id
     * @return int
     */
    public function getUnifiedId() {
        return $this->userInfo["info"]["id"];
    }

}

?>