<?php
require_once(dirname(__FILE__) . '/../UnifiedUserCollection.php');

/**
 * Class GroupBuyingConsumerCollection
 */
class GroupBuyingConsumerCollection implements UnifiedUserCollection {

    public function __construct(UnifiedCartCollection $cart=null) {
//        $this->params = $cart->getParams();
    }

    /**
     * Get user info from session.
     * @return array
     */
    public function getUnifiedBySession() {
        return array(
            "id" => 1,
        );
    }

    /**
     * Get user's id
     * @return int
     */
    public function getUnifiedId() {
        return 1;
    }

}

?>