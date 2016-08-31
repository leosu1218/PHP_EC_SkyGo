<?php
require_once(dirname(__FILE__) . '/../UnifiedUserCollection.php');

/**
 * Class ReorderGeneralUserCollection
 */
class ReorderGeneralUserCollection implements UnifiedUserCollection {

    protected $id;

    public function __construct(UnifiedCartCollection $cart=null) {

        if(!($cart instanceof ReorderGeneralCartCollection)) {
            throw new ECException("Instance should be ReorderGeneralCartCollection.");
        }
        else {
            $this->params = $cart->getParams();
        }

        if(array_key_exists("consumer_user_id", $this->params['original'])) {
            $this->id = $this->params['original']['consumer_user_id'];
        }
        else {
            throw new ECException("Missing original consumer user id.");
        }
    }

    /**
     * Get user info from session.
     * @return array
     */
    public function getUnifiedBySession() {
        return array(
            "id" => $this->id,
        );
    }

    /**
     * Get user's id
     * @return int
     */
    public function getUnifiedId() {
        return $this->id;
    }

}

?>