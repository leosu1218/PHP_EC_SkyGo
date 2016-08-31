<?php
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/UnifiedReturnedCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/UnifiedOrderCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );
require_once( FRAMEWORK_PATH . 'controllers/OrderController.php' );
require_once( FRAMEWORK_PATH . 'collections/EC/Skygo/SkygoEC.php' );
require_once( FRAMEWORK_PATH . 'models/GroupBuyingMasterUser.php' );
require_once( FRAMEWORK_PATH . 'extends/ValidatorHelper.php' );
require_once( FRAMEWORK_PATH . 'extends/CashFlow/Skygo/SkygoCashFlow.php' );


/**
 * Class ReturnController
 *
 * PHP version 5.3
 *
 * @category Controller
 * @author Rex Chen <rexchen@synctech-infinity.com>, Jai Chien <jaichien@syncte-infinity.com>
 * @copyright 2015 synctech.com
 */
class ReturnController extends RestController {

    private $cashFlow;
    private $ec;
    private $dao;

	public function __construct() {
        parent::__construct();
        
        $this->orderCollection = new UnifiedOrderCollection();
        $this->dao = $this->orderCollection->dao;
        $this->returnedCollection = new UnifiedReturnedCollection($this->dao);
        $this->setCashFlow(new SkygoCashFlow());
        $this->setEC(new SkygoEC());
	}

    /**
     * Setting cash flow module for order.
     * @param CashFlow $cashFlow
     */
    private function setCashFlow(CashFlow $cashFlow) {
        $this->cashFlow = $cashFlow;
    }

    /**
     * Setting EC module for order.
     * @param EC $ec
     */
    private function setEC(EC $ec) {
        $this->ec = $ec;
    }

    /**
     * Create service provider instance.
     * @param string $type
     * @return CashFlowServiceProvider
     */
    private function createServiceProvider($type="neweb") {
        return $this->cashFlow->createProvider($type);
    }

    /**
     * Create user notify instance
     * @param string $type
     * @return CashFlowUserNotify
     */
    private function createUserNotify($type='') {
        return $this->cashFlow->createReorderUserNotify($type);
    }

    /**
     * Create ec user instance
     * @param string $type
     * @return UnifiedUserCollection
     */
    private function createReorderUser($type='', UnifiedCartCollection $cart=null) {
        return $this->ec->createReorderUser($type, $cart);
    }

    /**
     * Create ec cart instance
     * @param string $type
     * @param array $params
     * @param null $dao
     * @return UnifiedCartCollection
     */
    private function createReorderCart($type='', $params=array(), &$dao=null) {
        return $this->ec->createReorderCart($type, $params, $dao);
    }

    /**
     * Get order record by order serial number.
     * @param $serial
     * @throws InvalidAccessParamsException
     */
    public function getOrderRecordBySerial($serial) {
        $search = array( "serial" => $serial );
        $result = $this->orderCollection->searchRecords(1, 1, $search);

        if(count($result["records"]) == 1) {
            return $result["records"][0];
        }
        else {
            throw new InvalidAccessParamsException("Order serial not found.");
        }
    }

    /**
     *	PUT: 	/return/<id:\d+>
     *	update order info
     *
     *	@param array(ur_delivery_channel=> <string>, ur_delivery_number=> <string>, stateText=> <string>)
     */
    public function update($id) {
//        TODO refactoring (Too long method)
        $collection = new UnifiedReturnedCollection();
        $attributes = array(
            'delivery_channel' => $this->params("ur_delivery_channel"),
            'delivery_number' => $this->params("ur_delivery_number"),
        );

        $nextState = $collection->getState($this->params("stateText"));
        $record = $collection->getRecordById( $id );
        if(count($record) == 0) {
            throw new DataAccessResultException("Not exists returned [$id].");
        }

        $recordState = $collection->getState( $record['stateText'] );
        if ($recordState->canChangeState($nextState)) {
            foreach ($nextState->getChangeAttributes() as $key => $value) {
                $attributes[$key] = $value;
            }
        }

        $actor 		= PlatformUser::instanceBySession();
        $rowCount 	= $collection
//                ->setActor($actor)
            ->multipleUpdateById(array($id), $attributes);

        if($rowCount == 0) {
            throw new DataAccessResultException("No changed in the operation.", 1);
        }
        return array();
    }

    /**
     * PUT: 	/return/list/state
     * The platform admin update returned's state by id list.
     *
     */
    public function updateStateByIds() {
//        TODO add permission control
        $ids        = $this->params("ids");
        $stateText  = $this->params("stateText");
        $actor      = PlatformUser::instanceBySession();
        $collection = new UnifiedReturnedCollection();
//            $rowCount   = $collection->setActor($actor)->updateStateByIds($ids, $stateText);
        $rowCount   = $collection->updateStateByIds($ids, $stateText);

        if($rowCount == 0) {
            throw new DataAccessResultException("Accept access but no changed.");
        }
         return array();
    }

    /**
     * Create new returned record by order's record. (apply return)
     *
     * @param array $orderRecord
     * @param string $creatorType
     * @throws AuthorizationException
     * @throws DbOperationException
     */
    public function create($orderRecord=array(), $creatorType="string") {
        $returnedRecord = array(
            "activity_id"           => $orderRecord["activity_id"],
            "activity_type"         => $orderRecord["activity_type"],
            "receiver_address"      => $orderRecord["receiver_address"],
            "receiver_name"         => $orderRecord["receiver_name"],
            "receiver_phone_number" => $orderRecord["receiver_phone_number"],
            "create_datetime"       => date("Y-m-d H:i:s"),
            "order_id"              => $orderRecord["id"]
        );

        $collection = new UnifiedReturnedCollection();
        $rowCount = $collection->create($returnedRecord);

        if($rowCount != 1) {
            throw new DbOperationException("Insert returned record to DB fail.");
        }
    }

    /**
     * POST: /return/groupbuying/user
     * Create return by buyer(will check buyer mail && phone)
     * 	@param 	{
     *				orderSerial: <string>,
     *				phone: <string>,
     *				email: <string>,
     *			}
     */
    public function createByBuyer() {
        $attributes             = array();
        $attributes["serial"]   = $this->params("orderSerial");

        $order = $this->getOrderRecordBySerial($attributes["serial"]);

        if( ($order["buyer_phone_number"] != $this->params("phone")) ||
            ($order["buyer_email"] != $this->params("email")) ) {
            throw new InvalidAccessParamsException("Invalid email or phone number.");
        }

        $state = $this->orderCollection->getState($order["stateText"]);

        if($state->canApplyReturned("buyer")) {
            $this->create($order, "buyer");
        }
        else {
            throw new DataAccessResultException("Invalid order state.");
        }

        return $order;
    }

    /**
     * POST: /return/groupbuying/consumer
     * Create return by buyer(will check buyer mail && phone)
     * 	@param 	{
     *				orderSerial: <string>,
     *			}
     */
    public function createByConsumer() {
        $attributes             = array();
        $attributes["serial"]   = $this->params("orderSerial");

        $order = $this->getOrderRecordBySerial($attributes["serial"]);

        $state = $this->orderCollection->getState($order["stateText"]);

        if($state->canApplyReturned("buyer")) {
            $this->create($order, "buyer");
        }
        else {
            throw new DataAccessResultException("Invalid order state.");
        }

        return $order;
    }

    /**
     *	Get condition for search product method from http request querysting.
     *	There will filter querystring key, values.
     *
     *	@return
     */
    public function getCondition() {

        $condition = array();
        $this->getQueryString("keyword", $condition);
        $this->getQueryString("activityId", $condition);
        $this->getQueryString("activityIds", $condition);
        $this->getQueryString("activityType", $condition);
        $this->getQueryString("state", $condition);
        $this->getQueryString("orderDateStart", $condition);
        $this->getQueryString("orderDateEnd", $condition);
        $this->getQueryString("deliveryDateStart", $condition);
        $this->getQueryString("deliveryDateEnd", $condition);
        $this->getQueryString("serial", $condition);

        return $condition;
    }

    /**
     * GET: /return/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * Search returned list.
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
	public function search($pageNo, $pageSize, $querystring) {
        $actor 		= PlatformUser::instanceBySession();
        $condition 	= $this->getCondition();
        $records 	= (new UnifiedReturnedCollection())
                    // ->setActor($actor)
                    ->searchRecords($pageNo, $pageSize, $condition);
        return $records;
	}

    /**
     *  GET: 	/return/spec/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     *  Search order's spec list.
     *
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
    public function searchSpec($pageNo, $pageSize, $querystring) {
        $actor 		= PlatformUser::instanceBySession();
        $condition 	= $this->getCondition();
        $records 	= (new UnifiedReturnedCollection())
//                                        ->setActor($actor)
                                ->searchSpecRecords($pageNo, $pageSize, $condition);
        return $records;
    }

    /**
     * Re-Order by reorder cart.
     * @param $activityType
     * @param $reorderCart
     */
    private function reorder($activityType, $reorderCart) {
        if(count($reorderCart->getSpecs()) > 0) {
            $reorderUser    = $this->createReorderUser($activityType, $reorderCart);
            $provider       = $this->createServiceProvider(SkygoCashFlow::REORDER_PROVIDER);
            $notify         = $this->createUserNotify($activityType);

            $orderControl   = new OrderController($this->dao);
            $result         = $orderControl->create($reorderCart, $reorderUser, $provider);
            $tradeResult    = $result["payment"];
            $orderControl->receiveNotify($provider, $notify, $tradeResult);
        }
    }

    /**
     * Change a order record's order to returned state.
     * @param $orderRecords
     * @throws AuthorizationException
     * @throws DbOperationException
     * @throws InvalidAccessParamsException
     */
    private function changeReturnedOrderStateByRecord($orderRecords) {
        $nextState = $this->orderCollection->getState(UnifiedOrderCollection::RETURNED_ORDER_STATE);
        $ids = array($orderRecords["id"]);
        $rowCount = $this->orderCollection->multipleUpdateById($ids, $nextState->getChangeAttributes());

        if($rowCount == 0) {
            throw new DbOperationException("Change order state to returned fail.");
        }
    }

    /**
     * Change a returned to completed state by returned id.
     * @param $id
     * @throws AuthorizationException
     * @throws DbOperationException
     * @throws InvalidAccessParamsException
     */
    private function changeCompletedReturnedStateById($id) {
        $nextState = $this->returnedCollection->getState(UnifiedReturnedCollection::COMPLETED_RETURNED_STATE);
        $ids = array($id);
        $rowCount = $this->returnedCollection->multipleUpdateById($ids, $nextState->getChangeAttributes());
        if($rowCount == 0) {
            throw new DbOperationException("Change order state to returned fail.");
        }
    }

    /**
     * POST: 	/return/complete
     * Create returned complete.
     */
    public function complete() {
        $actor          = PlatformUser::instanceBySession();
        $returnedSpec   = $this->params("specs");
        $serial 	    = $this->params("serial");
        $components     = $this->getPrepareCompleteComponents($serial, $returnedSpec);
        $orderRecords   = $components["originalOrder"];
        $cart           = $components["reorderCart"];

        if($orderRecords["stateText"] == UnifiedOrderCollection::RETURNED_ORDER_STATE) {
            throw new InvalidAccessParamsException("The order was returned.");
        }

        if($this->dao->transaction()) {
            try {
                $this->reorder($orderRecords["activity_type"], $cart);
                $this->changeReturnedOrderStateByRecord($orderRecords);
                $this->changeCompletedReturnedStateById($components["returnedId"]);
                $this->dao->commit();
                return array();
            }
            catch(Exception $e) {
                $log = new LoggerHelper();
                $log->debug($e->getMessage());
                $log->debug($e->getTraceAsString());

                $this->dao->rollback();
                throw $e;
            }
        }
        else {
            throw new DbOperationException("Begin transaction fail.");
        }
    }

    /**
     * Get components object that complete returned required.
     * @param string $serial Original order serial.
     * @param array $returnedSpec Want to returned spec list.
     * @return array Components.
     * @throws DataAccessResultException
     * @throws InvalidAccessParamsException
     */
    private function getPrepareCompleteComponents($serial, $returnedSpec) {
        $components     = array();
        $order          = $this->getOrderRecordBySerial($serial);
        $spec           = $this->getOrderSpecRecordBySerial($serial);
        $newSpec        = $this->getNewSpec($returnedSpec, $spec);

        $list = array(
            "fareId"            => $order["fare_id"],
            "activityId"        => $order["activity_id"],
            "name"              => $order["receiver_name"],
            "phone"             => $order["receiver_phone_number"],
            "email"             => $order["buyer_email"],
            "address"           => $order["receiver_address"],
            "inventoryProcess"  => $order["inventory_process"],
            "companyName"       => $order["companyName"],
            "taxID"             => $order["taxID"],
            "consumerRemark"    => $order["consumer_remark"],
            "payType"           => "reorder",
            "spec"              => $newSpec,
            "original"          => $order,
        );

        $components["reorderCart"] = $this->createReorderCart($order["activity_type"], $list);
        $components["originalOrder"] = $order;
        $components["returnedId"] = $returnedSpec[0]["ur_id"];

        return $components;
    }

    /**
     * Get spec records from order serial.
     * @param $serial
     * @return mixed
     * @throws DataAccessResultException
     */
    private function getOrderSpecRecordBySerial($serial) {
        $pageNo = 1;
        $pageSize = 10000;
        $conditions = array("serial" => $serial);
        $result = $this->orderCollection->searchSpecRecords($pageNo, $pageSize, $conditions);

        if(count($result["records"]) == 0) {
            throw new DataAccessResultException("Order spec records null error.");
        }

        return $result["records"];
    }

    /**
     * Get new spec order list.
     * @param array $returnedSpec   Returned spec item.
     * @param array $orderSpec      Original order spec.
     * @return array
     * @throws InvalidAccessParamsException
     */
    private function getNewSpec($returnedSpec=array(), $orderSpec=array()) {
        // TODO refactoring (Too long method)
        $newSpec = array();
        foreach($returnedSpec as $returnedIndex => $returnedItem) {

            $orderItemNotExists = true;
            foreach($orderSpec as $orderIndex => $orderItem) {

                if($orderItem["spec_id"] == $returnedItem["spec_id"]) {

                    $orderItemNotExists = false;
                    if($returnedItem["spec_returned_amount"] < 0) {
                        throw new InvalidAccessParamsException("Invalid spec returned amount error, can't be nagetive.");
                    }

                    if($returnedItem["spec_returned_amount"] > $orderItem["spec_amount"]) {
                        throw new InvalidAccessParamsException("Returned spec amount greater than original order spec amount error.");
                    }

                    $newItem = array(
                        "activity_id"   => $returnedItem["spec_activity_id"],
                        "amount"        => ($orderItem["spec_amount"] - $returnedItem["spec_returned_amount"]),
                        "id"            => $returnedItem["spec_id"],
                        "product_id"    => $returnedItem["product_id"],
                    );

                    array_push($newSpec, $newItem);
                }
            }

            if($orderItemNotExists) {
                throw new InvalidArgumentException("Returned spec id not exists ib original order spec list.");
            }
        }

        return $newSpec;
    }

    /**
     * POST: 	/return/complete/preview
     * Create returned complete preview info.
     */
    public function previewComplete() {

        $actor          = PlatformUser::instanceBySession();
        $returnedSpec   = $this->params("specs");
        $serial 	    = $this->params("serial");
        $components     = $this->getPrepareCompleteComponents($serial, $returnedSpec);
        $order          = $components["originalOrder"];
        $reorderCart    = $components["reorderCart"];

        $data = array(
            "returnedId"        => $components["returnedId"],

            "originTotalPrice"  => $order["product_total_price"],
            "originFare"        => $order["fare"],
            "originDiscount"    => $order["discount"],
            "originFinalPrice"  => $order["final_total_price"],

            "newTotalPrice"     => $reorderCart->getProductPrice(),
            "newFare"           => $reorderCart->getFare(),
            "newDiscount"       => $reorderCart->getDiscount(),
            "newFinalPrice"     => $reorderCart->getFinalTotalPrice(),
        );

        $data["totalPriceMargin"]   = $data["originTotalPrice"] - $data["newTotalPrice"];
        $data["fareMargin"]         = $data["originFare"] - $data["newFare"];
        $data["discountMargin"]     = $data["originDiscount"] - $data["newDiscount"];
        $data["finalPriceMargin"]   = $data["originFinalPrice"] - $data["newFinalPrice"];

        return $data;
    }

    /**
     * GET: /user/groupbuyingmaster/self/return/spec/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * Search returned list.
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
    public function searchSpecByMasterSelf($pageNo, $pageSize, $querystring) {
        $master 				    = GroupBuyingMasterUser::getRecordBySession();
        $condition 				    = $this->getCondition();
        $condition["activityType"]	= GroupBuyingActivity::TYPE_NAME;
        $condition["masterId"]	    = $master["id"];
        $records 				    = (new UnifiedReturnedCollection())
                                        ->searchSpecRecords($pageNo, $pageSize, $condition);
        return $records;
    }

    /**
     * GET: /user/groupbuyingmaster/self/return/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * Search returned list.
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
	public function searchByMasterSelf($pageNo, $pageSize, $querystring) {
        $master 				    = GroupBuyingMasterUser::getRecordBySession();
        $condition 				    = $this->getCondition();
        $condition["activityType"]	= GroupBuyingActivity::TYPE_NAME;
        $condition["masterId"]	    = $master["id"];
        $records 				    = (new UnifiedReturnedCollection())
                                        ->searchRecords($pageNo, $pageSize, $condition);
        return $records;
	}

    public function ckangeRemark( $id ) {
        $collection = new UnifiedReturnedCollection();
        $attributes = array(
            'remark' => $this->params("ur_remark")
        );
        $count = $collection->getById($id)->update($attributes);
        if($count != 1) {
            throw new DataAccessResultException("update returned record to DB fail.");
        }
        return $attributes;
    }
}




?>