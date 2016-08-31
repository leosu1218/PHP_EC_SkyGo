<?php
require_once(dirname(__FILE__) . '/ECException.php');

/**
 * Interface EC
 */
interface EC {

    /**
     * Instance a cart object.
     * @param array $params
     * @param null $dao
     * @return UnifiedCartCollection
     * @return UnifiedCartCollection
     */
    public function createCart($type='', $params=array(), &$dao=null);

    /**
     * Instance a reorder cart object.
     * @param string $type
     * @param array $params
     * @param null $dao
     * @return UnifiedCartCollection
     */
    public function createReorderCart($type='', $params=array(), &$dao=null);

    /**
     * * Instance a user object.
     * @param string $type
     * @return UnifiedUserCollection
     */
    public function createUser($type='');

    /**
     * * Instance a reorder user object.
     * @param string $type
     * @return UnifiedUserCollection
     */
    public function createReorderUser($type='', UnifiedCartCollection $cart=null);

}
?>