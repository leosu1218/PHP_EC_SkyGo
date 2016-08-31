<?php
require_once(dirname(__FILE__) . '/UnifiedCartCollection.php');

/**
 * Interface UnifiedUserCollection
 */
interface UnifiedUserCollection {

    public function __construct(UnifiedCartCollection $cart=null);

    /**
     * Get user info from session.
     * @return array Array("oauthId" => <string>, "name" => <string>, "email" => <string>)
     */
    public function getUnifiedBySession();

    /**
     * Get user's id
     * @return int
     */
    public function getUnifiedId();
}

?>