<?php
require_once(dirname(__FILE__) . '/../EC.php');
require_once(dirname(__FILE__) . '/GroupBuyingCartCollection.php');
require_once(dirname(__FILE__) . '/GroupBuyingConsumerCollection.php');
require_once(dirname(__FILE__) . '/ReorderGroupbuyingCartCollection.php');
require_once(dirname(__FILE__) . '/ReorderGroupbuyingUsertCollection.php');

require_once(dirname(__FILE__) . '/GeneralCartCollection.php');
require_once(dirname(__FILE__) . '/GeneralConsumerCollection.php');
require_once(dirname(__FILE__) . '/ReorderGeneralCartCollection.php');
require_once(dirname(__FILE__) . '/ReorderGeneralUserCollection.php');

/**
 * Class SkygoEC
 */
class SkygoEC implements EC {

    const GROUP_BUYING_CART         = "groupbuying";
    const REORDER_GROUP_BUYING_CART = "groupbuying";
    const GENERAL_CART              = "general";
    const REORDER_GENERAL_CART      = "general";

    const GROUP_BUYING_USER         = "groupbuying";
    const REORDER_GROUP_BUYING_USER = "groupbuying";
    const GENERAL_USER              = "general";
    const REORDER_GENERAL_USER      = "general";

    /**
     * Instance a cart object.
     * @param array $params
     * @param null $dao
     * @return UnifiedCartCollection
     * @return UnifiedCartCollection
     */
    public function createCart($type='', $params=array(), &$dao=null) {
        if($type == self::GROUP_BUYING_CART) {
            return new GroupBuyingCartCollection($params, $dao);
        }
        else if($type == self::GENERAL_CART) {
            return new GeneralCartCollection($params, $dao);
        }
        else {
            throw new ECException("Create UnifiedCartCollection error. Invalid cart type [$type]");
        }
    }

    /**
     * Instance a reorder cart object.
     * @param string $type
     * @param array $params
     * @param null $dao
     * @return UnifiedCartCollection
     */
    public function createReorderCart($type='', $params=array(), &$dao=null) {
        if($type == self::REORDER_GROUP_BUYING_CART) {
            return new ReorderGroupbuyingCartCollection($params, $dao);
        }
        else if($type == self::REORDER_GENERAL_CART) {
            return new ReorderGeneralCartCollection($params, $dao);
        }
        else {
            throw new ECException("Create UnifiedCartCollection error. Invalid cart type [$type]");
        }
    }

    /**
     * * Instance a user object.
     * @param string $type
     * @return UnifiedUserCollection
     */
    public function createUser($type='') {
        if($type == self::GROUP_BUYING_USER) {
            return new GroupBuyingConsumerCollection();
        }
        else if($type == self::GENERAL_USER) {
            return new GeneralConsumerCollection();
        }
        else {
            throw new ECException("Create UnifiedUserCollection error. Invalid user type [$type]");
        }
    }

    public function createReorderUser($type='', UnifiedCartCollection $cart=null) {
        if($type == self::REORDER_GROUP_BUYING_USER) {
            return new ReorderGroupbuyingUsertCollection($cart);
        }
        else if($type == self::REORDER_GENERAL_USER) {
            return new ReorderGeneralUserCollection($cart);
        }
        else {
            throw new ECException("Create UnifiedUserCollection error. Invalid user type [$type]");
        }
    }
}

?>