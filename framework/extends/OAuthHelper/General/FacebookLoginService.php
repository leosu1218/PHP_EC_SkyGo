<?php
require_once(dirname(__FILE__) . "/../OAuthLoginService.php");
require_once(dirname(__FILE__) . "/Facebook/autoload.php");
require_once(dirname(__FILE__) . "/Facebook/OAuthFacebookConfigs.php");
require_once(dirname(__FILE__) . "/../../GeneralSession.php");

// added in v4.0.0
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

/**
 * Class FacebookLoginService
 */
class FacebookLoginService extends OAuthLoginService {

    private $helper;

    public function __construct() {
        parent::__construct();
        FacebookSession::setDefaultApplication(OAuthFacebookConfigs::APP_ID, OAuthFacebookConfigs::APP_SECRET);
        $this->helper = new FacebookRedirectLoginHelper(OAuthFacebookConfigs::RECEIVE_URL);
    }

    /**
     * Saving user info to php session.
     * @param FacebookResponse $response
     */
    private function saveUserInfo(FacebookResponse &$response, OAuthUserCollection &$user=null) {
        $graphObject = $response->getGraphObject();
        $info = array(
            "oauthId" => $graphObject->getProperty('id'),
            "name" => $graphObject->getProperty('name'),
            "email" => $graphObject->getProperty('email'),
            "phone" => "",
        );

        $record = $user->getRecordByProviderId($this->getProviderName(), $info["oauthId"]);
        if(count($record) > 0) {
            $info["id"] = $record["id"];
        }
        else {
            $name           = $info["name"];
            $account        = $info["oauthId"] . "@" . $this->getProviderName();
            $providerType   = $this->getProviderName();
            $providerId     = $info["oauthId"];
            $attributes     = $info;
            $info["id"]     = $user->createRecordByProviderId($name, $account, $providerType, $providerId, $attributes);
        }

        $this->saveUserInfoToSession($info);
    }

    /* OAuthLoginService abstract methods. */
    /**
     * Define the login service provider name.
     * @return string
     */
    public function getProviderName() {
        return "fb";
    }

    /**
     * Request login to service provider.
     */
    public function request() {
        $loginUrl = $this->helper->getLoginUrl(array('email'));
        header("Location: " . $loginUrl);
        exit;
    }

    /**
     * Handling received Facebook login response data.
     * @param OAuthUserCollection $userCollection
     */
    public function handleResponse(OAuthUserCollection $userCollection=null) {
        $session    = $this->helper->getSessionFromRedirect();
        $request    = new FacebookRequest( $session, 'GET', '/me/email' );
        $response   = $request->execute();

        $this->saveUserInfo($response, $userCollection);
        return $this->getUserInfoBySession();
    }
}

?>