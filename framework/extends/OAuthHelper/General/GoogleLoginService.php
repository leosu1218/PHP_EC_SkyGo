<?php
require_once(dirname(__FILE__) . "/../OAuthLoginService.php");
require_once(dirname(__FILE__) . "/Google/src/Google/autoload.php");
require_once(dirname(__FILE__) . "/Google/OAuthGoogleConfigs.php");
require_once(dirname(__FILE__) . "/../../GeneralSession.php");


/**
 * Class FacebookLoginService
 */
class GoogleLoginService extends OAuthLoginService {

    protected $client = null;
    protected $oauthService = null;

    public function __construct(){
        parent::__construct();
        $this->client = new Google_Client();
        $this->loadConfigs($this->client);
        $this->oauthService = new Google_Service_Oauth2($this->client);
    }

    /**
     * Load configs.
     */
    private function loadConfigs(Google_Client &$client) {
        $client->setClientId(OAuthGoogleConfigs::CLIENT_ID);
        $client->setClientSecret(OAuthGoogleConfigs::CLIENT_SECRET);
        $client->setRedirectUri(OAuthGoogleConfigs::RECEIVE_URL);
        foreach(OAuthGoogleConfigs::SCOPES() as $index => $scope) {
            $client->addScope($scope);
        }
    }

    /**
     * Saving user info to php session.
     * @param Google_Service_Oauth2_Userinfo_Resource $response
     */
    private function saveUserInfo(Google_Service_Oauth2_Userinfo_Resource &$response, OAuthUserCollection &$user=null) {
        $uerInfo = $response->get();
        $info = array(
            "oauthId" => $uerInfo->getId(),
            "name" => $uerInfo->getName(),
            "email" => $uerInfo->getEmail(),
            "phone" => "",
        );

        $record = $user->getRecordByProviderId($this->getProviderName(), $info["oauthId"]);
        if(count($record) > 0) {
            $info["id"] = $record["id"];
            $info["login"] = "second"  ;
        }
        else {
            $name           = $info["name"];
            $account        = $info["oauthId"] . "@" . $this->getProviderName();
            $providerType   = $this->getProviderName();
            $providerId     = $info["oauthId"];
            $attributes     = $info;
            $info["id"]     = $user->createRecordByProviderId($name, $account, $providerType, $providerId, $attributes);
            $info["login"] = "first"  ;
        }

        $this->saveUserInfoToSession($info);
    }

    /* OAuthLoginService abstract methods. */
    /**
     * Define the login service provider name.
     * @return string
     */
    public function getProviderName() {
        return "google";
    }

    /**
     * Request login to service provider.
     */
    public function request() {
        $url = $this->client->createAuthUrl();
        header("Location: " . $url);
        exit;
    }

    /**
     * Handling received Facebook login response data.
     * @param OAuthUserCollection $userCollection
     */
    public function handleResponse(OAuthUserCollection $userCollection=null) {
        if (!isset($_GET['code'])) {
            throw new OAuthHelperException("Missing required authenticate code.");
        }

        $this->client->authenticate($_GET['code']);
        $this->saveUserInfo($this->oauthService->userinfo, $userCollection);
        return $this->getUserInfoBySession();
    }
}

?>