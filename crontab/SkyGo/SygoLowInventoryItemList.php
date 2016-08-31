<?php
require_once( dirname(dirname(__FILE__)) . '/config.php' );
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleProductSpecCollection.php' );



/**
 * Class SygoLowInventoryItemList
 *
 * PHP version 5.3
 *
 * @category Controller
 * @package Controller
 * @author Rex Chen <rexchen@synctech-infinity.com>,Jai Chien <jaichien@syncte-infinity.com>
 * @copyright 2015 synctech.com
 */
class SygoLowInventoryItemList extends RestController {


    /**
     *  GET:   /homepage/<position:\w+>/image
     * Get home page images with position.
     * @param $position
     * @return array
     * @throws AuthorizationException
     * @throws Exception
     */
    public function getProductSpec($pageNo, $pageSize ){
        $collection = new WholesaleProductSpecCollection;
        $conditions = array('and','1=:one');
        array_push($conditions, "can_sale_inventory <= safe_inventory");
        $paramters[':one'] = 1;
        $records = $collection->getRecordsByCondition($conditions,$paramters,$pageNo,$pageSize);

        return $records;
    }

}




?>