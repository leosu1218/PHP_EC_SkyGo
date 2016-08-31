<?php
require_once( dirname(dirname(__FILE__)) . '/config.php' );
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleProductCollection.php' );


/**
 * Class SygoOffTheShelfItemList
 */
class SygoOffTheShelfItemList extends RestController {


    /**
     * @param $selectDay
     * @param $pageNo
     * @param $pageSize
     * @return array
     * @throws AuthorizationException
     */
    public function getProduct($selectDay, $pageNo, $pageSize ){
        $collection = new WholesaleProductCollection;
        $conditions = array('and','1=1');
        array_push($conditions, "DATE_FORMAT(removed_time ,'%Y-%m-%d') = DATE_ADD( CURDATE() , INTERVAL +:selectDay DAY )");
        $paramters[':selectDay'] = $selectDay;
        $records = $collection->getRecordsByCondition($conditions,$paramters,$pageNo,$pageSize);
        return $records;
    }

}


?>