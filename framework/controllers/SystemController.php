<?php
/**
*  SystemController code.
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @author Jai Chien <jaichien@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );

require_once( FRAMEWORK_PATH . 'collections/FareCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/ProductEventNotifyListCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/DeliveryProgramCollection.php' );


class SystemController extends RestController {

    public function __construct() {
        parent::__construct();                
    }

    /**
     * Get condition for search product method from http request querysting.
     * There will filte querystring key, values.
     *
     * @return array
     */
    public function getCondition() {
        $condition = array();
        $this->getQueryString("keyword", $condition);
        return $condition;
    }

    /**
     * GET:    /system/config/search/fare/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
    public function searchFareList($pageNo, $pageSize, $querystring){
        $actor      = PlatformUser::instanceBySession();
        $condition  = $this->getCondition();
        $collection = new FareCollection();
        $records    = $collection
            ->setActor($actor)
            ->searchRecords($pageNo, $pageSize, $condition);
        return $records;
    }

    /**
    *
    *   GET:    /system/config/fare/list/<pageNo:\d+>/<pageSize:\d+>
    *   
    */
    public function getFareList( $pageNo, $pageSize ){

            $data = array();

            try {

                $user      = PlatformUser::instanceBySession();
                $fareCollection = new FareCollection();
                $fareCollection->setActor($user);
                $data = $fareCollection->getRecords(array(), $pageNo, $pageSize);

                $this->responser->send( $data, $this->responser->OK() );

            }
            catch ( Exception $e) {
              $data['message'] = SERVER_ERROR_MSG;
              $data['message'] = $e->getMessage();              		
              $this->responser->send( $data, $this->responser->InternalServerError() );
            }
    }

    /**
    *
    *   POST:  /system/config/fare
    *   
    */
    public function createFare(){

            $data = array();

            try {

                $user      = PlatformUser::instanceBySession();
                $fareCollection = new FareCollection();
                $fareCollection->setActor($user);
                $fareData = array(
                        "amount"=>$this->params("amount"),
                        "type"=>$this->params("type"),
                        "target_amount"=>$this->params("target_amount"),
                        "global"=>$this->params("global"),
                    );
                $data["fareRowCount"] = $fareCollection->create($fareData);

                $this->responser->send( $data, $this->responser->OK() );

            }
            catch ( Exception $e) {
              $data['message'] = SERVER_ERROR_MSG;
              $data['message'] = $e->getMessage();                      
              $this->responser->send( $data, $this->responser->InternalServerError() );
            }
    }

    /**
    *
    *   PUT:  /system/config/fare
    *   
    */
    public function updateFare( $id ){

            $data = array();

            try {

                $user      = PlatformUser::instanceBySession();
                $fareCollection = new FareCollection();
                $fareCollection->setActor($user);
                $model = $fareCollection->getById($id);
                $fareData = array(
                        "amount"=>$this->params("amount"),
                        "type"=>$this->params("type"),
                        "target_amount"=>$this->params("target_amount"),
                        "global"=>$this->params("global"),
                    );
                $data['fareRowCount'] = $model->update($fareData);

                $this->responser->send( $data, $this->responser->OK() );

            }
            catch ( Exception $e) {
              $data['message'] = SERVER_ERROR_MSG;
              $data['message'] = $e->getMessage();                      
              $this->responser->send( $data, $this->responser->InternalServerError() );
            }
    }

    /**
    *
    *   DELETE:  /system/config/fare
    *   
    */
    public function removeFare( $id ){

            $data = array();

            try {

                $user      = PlatformUser::instanceBySession();
                $fareCollection = new FareCollection();
                $fareCollection->setActor($user);
                $model = $fareCollection->getById($id);
                $data['fareRowCount'] = $model->destroy();

                $this->responser->send( $data, $this->responser->OK() );

            }
            catch ( Exception $e) {
              $data['message'] = SERVER_ERROR_MSG;
              $data['message'] = $e->getMessage();                      
              $this->responser->send( $data, $this->responser->InternalServerError() );
            }
    }

    /**
    *
    *   GET:  /system/config/productevent/list
    *   
    */
    public function getProductEventList( $pageNo, $pageSize ){

            $data = array();

            try {

                $user      = PlatformUser::instanceBySession();

                $productEventCollection = new ProductEventNotifyListCollection();
                $productEventCollection->setActor($user);
                $data = $productEventCollection->getRecords(array(), $pageNo, $pageSize);

                $this->responser->send( $data, $this->responser->OK() );

            }
            catch ( Exception $e) {
              $data['message'] = SERVER_ERROR_MSG;
              $data['message'] = $e->getMessage();                      
              $this->responser->send( $data, $this->responser->InternalServerError() );
            }
    }

    /**
    *
    *   POST:  /system/config/productevent
    *   
    */
    public function createProductEvent(){

            $data = array();

            try {

                $user      = PlatformUser::instanceBySession();

                $productEventCollection = new ProductEventNotifyListCollection();
                $productEventCollection->setActor($user);
                $productEventData = array(
                        "email"=>$this->params("email"),
                    );
                $data["productEvent"] = $productEventCollection->create($productEventData);

                $this->responser->send( $data, $this->responser->OK() );

            }
            catch ( Exception $e) {
              $data['message'] = SERVER_ERROR_MSG;
              $data['message'] = $e->getMessage();                      
              $this->responser->send( $data, $this->responser->InternalServerError() );
            }
    }

    /**
    *
    *   PUT:  /system/config/productevent
    *   
    */
    public function updateProductEvent( $id ){

            $data = array();

            try {

                $user      = PlatformUser::instanceBySession();

                $productEventCollection = new ProductEventNotifyListCollection();
                $productEventCollection->setActor($user);
                $model = $productEventCollection->getById($id);
                $producteventData = array(
                        "email"=>$this->params("email"),
                    );
                $data['productEventRowCount'] = $model->update($producteventData);

                $this->responser->send( $data, $this->responser->OK() );

            }
            catch ( Exception $e) {
              $data['message'] = SERVER_ERROR_MSG;
              $data['message'] = $e->getMessage();                      
              $this->responser->send( $data, $this->responser->InternalServerError() );
            }
    }

    /**
    *
    *   DELETE:  /system/config/productevent
    *   
    */
    public function removeProductEvent( $id ){

            $data = array();

            try {

                $user      = PlatformUser::instanceBySession();
                $productEventCollection = new ProductEventNotifyListCollection();
                $productEventCollection->setActor($user);
                $model = $productEventCollection->getById($id);
                $data['productEventRowCount'] = $model->destroy();

                $this->responser->send( $data, $this->responser->OK() );

            }
            catch ( Exception $e) {
              $data['message'] = SERVER_ERROR_MSG;
              $data['message'] = $e->getMessage();                      
              $this->responser->send( $data, $this->responser->InternalServerError() );
            }
    }

    /**
    *
    *   GET:    /system/config/delivery/list/<pageNo:\d+>/<pageSize:\d+>
    *   
    */
    public function getDeliveryList( $pageNo, $pageSize ){

            $data = array();

            try {

                $user      = PlatformUser::instanceBySession();
                $deliveryCollection = new DeliveryProgramCollection();
                $data = $deliveryCollection->getRecords(array(), $pageNo, $pageSize);

                $this->responser->send( $data, $this->responser->OK() );

            }
            catch ( Exception $e) {
              $data['message'] = SERVER_ERROR_MSG;
              $data['message'] = $e->getMessage();                      
              $this->responser->send( $data, $this->responser->InternalServerError() );
            }
    }

    /**
     * GET:    /system/config/search/delivery/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
    public function searchDeliveryList($pageNo, $pageSize, $querystring){
        $actor      = PlatformUser::instanceBySession();
        $condition  = $this->getCondition();
        $collection = new DeliveryProgramCollection();
        $records    = $collection
            ->setActor($actor)
            ->searchRecords($pageNo, $pageSize, $condition);
        return $records;
    }

    /**
    *
    *   POST:  /system/config/delivery
    *   
    */
    public function createLogistics(){

            $data = array();

            try {

                $user      = PlatformUser::instanceBySession();
                $deliveryCollection = new DeliveryProgramCollection();
                $deliveryData = array(
                        "program_name"=>$this->params("program_name"),
                        "pay_type"=>$this->params("pay_type"),
                        "delivery_type"=>$this->params("delivery_type"),
                    );
                $data["deliveryRowCount"] = $deliveryCollection->create($deliveryData);

                $this->responser->send( $data, $this->responser->OK() );

            }
            catch ( Exception $e) {
              $data['message'] = SERVER_ERROR_MSG;
              $data['message'] = $e->getMessage();                      
              $this->responser->send( $data, $this->responser->InternalServerError() );
            }
    }
    /**
    *
    *   DELETE:  /system/config/delivery
    *   
    */
    public function removeDelivery( $id ){

            $data = array();

            try {

                $user      = PlatformUser::instanceBySession();
                $deliveryCollection = new DeliveryProgramCollection();
                $model = $deliveryCollection->getById($id);
                $data['deliveryRowCount'] = $model->destroy();

                $this->responser->send( $data, $this->responser->OK() );

            }
            catch ( Exception $e) {
              $data['message'] = SERVER_ERROR_MSG;
              $data['message'] = $e->getMessage();                      
              $this->responser->send( $data, $this->responser->InternalServerError() );
            }
    }
    /**
    *
    *   PUT:  /system/config/delivery
    *   
    */
    public function updateDelivery( $id ){

            $data = array();

            try {

                $user      = PlatformUser::instanceBySession();
                $deliveryCollection = new DeliveryProgramCollection();
                $model = $deliveryCollection->getById($id);
                $deliveryData = array(
                        "pay_type"=>$this->params("pay_type"),
                        "delivery_type"=>$this->params("delivery_type"),
                    );
                $data['deliveryRowCount'] = $model->update($deliveryData);

                $this->responser->send( $data, $this->responser->OK() );

            }
            catch ( Exception $e) {
              $data['message'] = SERVER_ERROR_MSG;
              $data['message'] = $e->getMessage();                      
              $this->responser->send( $data, $this->responser->InternalServerError() );
            }
    }


}




?>