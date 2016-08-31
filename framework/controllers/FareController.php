<?php
/**
 *  FareController code.
 *
 *  PHP version 5.3
 *
 *  @package Controller
 *  @author Rex Chen <rexchen@synctech-infinity.com>
 *  @copyright 2015 synctech.com
 */
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/FareCollection.php' );


class FareController extends RestController {

    public function __construct() {
        parent::__construct();
    }

    /**
     * POST:    /fare/search/<activityType:\w+>/<pageNo:\d+>/<pageSize:\d+>
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
    public function search($activityType, $pageNo, $pageSize){
        $condition = array(
            "activityType" => $activityType,
            "activityIds" => $this->params("ids")
        );

        $collection = new FareCollection();
        return $collection->searchRecords($pageNo, $pageSize, $condition);
    }

}




?>