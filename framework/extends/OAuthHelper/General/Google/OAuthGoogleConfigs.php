<?php

/**
 * Class OAuthGoogleConfigs
 */
class OAuthGoogleConfigs {
    const CLIENT_ID         = "64870785021-vk8tqhc430h5jnof0l8ib4th3svv90pi.apps.googleusercontent.com";
    const CLIENT_SECRET     = "mYg6E017JLV8L27IC3yR2HoQ";
    const RECEIVE_URL       = "http://www.109life.com/api/oauth/receive/google/";

    /**
     * Get service's scopes that want to open.
     * @return array
     */
    static function SCOPES() {
        return array(
            "https://www.googleapis.com/auth/userinfo.email",
            "https://www.googleapis.com/auth/userinfo.profile"
        );
    }
}

?>