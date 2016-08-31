<?php
require_once(dirname(__FILE__) . "/OAurhHelperException.php");

/**
 * Class OAuthHelper
 */
abstract class OAuthHelper {
    /**
     * Create a login service.
     * @param string $type
     * @return OAuthLoginService
     */
    abstract public function createLoginService($type='');

    /**
     * Get user's info by session.
     * @return array Return null array when not login (array())
     */
    abstract public function getUserInfo();

    /**
     * Logout the user by session.
     */
    abstract public function logout();
}


?>