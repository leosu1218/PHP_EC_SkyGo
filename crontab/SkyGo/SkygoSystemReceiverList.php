<?php
require_once( dirname(dirname(__FILE__)) . '/config.php' );
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/ProductEventNotifyListCollection.php' );



/**
 * Class SkygoSystemReceiverList
 *
 * PHP version 5.3
 *
 * @category Controller
 * @package Controller
 * @author Rex Chen <rexchen@synctech-infinity.com>,Jai Chien <jaichien@syncte-infinity.com>
 * @copyright 2015 synctech.com
 */
class SkygoSystemReceiverList extends RestController {


    /**
     * @param $pageNo
     * @param $pageSize
     * @return array
     * @throws AuthorizationException
     */
    public function getNotify($pageNo, $pageSize ){
        $collection = new ProductEventNotifyListCollection;
        $records = $collection->getRecords(array(),$pageNo,$pageSize);
        return $records;
    }

}




?>