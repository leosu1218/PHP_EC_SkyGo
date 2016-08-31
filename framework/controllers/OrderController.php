<?php
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'extends/AuthenticateHelper.php' );
require_once( FRAMEWORK_PATH . 'collections/UnifiedOrderCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/CashFlow/Skygo/SkygoCashFlow.php' );
require_once( FRAMEWORK_PATH . 'extends/OAuthHelper/General/GeneralOAuthHelper.php' );
require_once( FRAMEWORK_PATH . 'collections/EC/Skygo/SkygoEC.php' );
require_once( FRAMEWORK_PATH . 'collections/ProductHasDeliveryCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/OrderHasSpecCollection.php' );

require_once( FRAMEWORK_PATH . 'extends/LoggerHelper.php' );

/**
 * Class OrderController
 *
 * PHP version 5.3
 *
 * @category Controller
 * @package Controller
 * @author Rex Chen <rexchen@synctech-infinity.com>,Jai Chien <jaichien@syncte-infinity.com>
 * @copyright 2015 synctech.com
 */
class OrderController extends RestController {

    private $collection;
    private $cashFlow;
    private $ec;

	public function __construct(&$dao=null) {
		parent::__construct();
        $this->collection = new UnifiedOrderCollection($dao);
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
    private function createServiceProvider($type="neweb", $activity='') {
        return $this->cashFlow->createProvider($type, $activity);
    }

    /**
     * Create user notify instance
     * @param string $type
     * @return CashFlowUserNotify
     */
    private function createUserNotify($type='') {
        return $this->cashFlow->createUserNotify($type);
    }

    /**
     * Create ec user instance
     * @param string $type
     * @return UnifiedUserCollection
     */
    private function createUser($type='') {
        return $this->ec->createUser($type);
    }

    /**
     * Create ec cart instance
     * @param string $type
     * @param array $params
     * @param null $dao
     * @return UnifiedCartCollection
     */
    private function createCart($type='', $params=array(), &$dao=null) {
        return $this->ec->createCart($type, $params, $dao);
    }

    /**
     * Create a order.
     * @param UnifiedCartCollection $cart Order's info getter.
     * @param UnifiedUserCollection $user Order creator info getter.
     * @param CashFlowServiceProvider $provider Cash flow params creator.
     * @return array
     * @throws DbOperationException
     * @throws Exception
     */
    public function create(UnifiedCartCollection $cart, UnifiedUserCollection $user, CashFlowServiceProvider $provider) {
        $order          = $this->collection;
        $orderData      = $order->generate($user, $cart);
        $paymentData 	= $provider->createPaymentParams($orderData);
        $result = array(
            'order' 	=> $orderData,
            'payment' 	=> $paymentData,
        );

        return $result;
    }

    /**
     * Receive trade notify info by cash flow service provider service.
     * @param CashFlowServiceProvider $provider
     * @param CashFlowUserNotify $notify
     * @param array $tradeResult Received trade info.
     */
    public function receiveNotify(CashFlowServiceProvider $provider, CashFlowUserNotify $notify, $tradeResult=array()) {
        $order      = $this->collection;
        $provider->receiveNotify($tradeResult, $order, $notify);
    }

    /**
     * POST: 	/order/<activity:\w+>/<type:\w+>
     * Create a general order by buyer.(pay not yet)
     * @param string $type
     */
    public function createByActivity($activity='general', $type="neweb") {
        $params     = $this->receiver;
        $provider   = $this->createServiceProvider($type, $activity);
        $cart       = $this->createCart($activity, $params);
        $user       = $this->createUser($activity);
        $cart->setUserCollection($user);

        return $this->create($cart, $user, $provider);
    }

    /**
     * POST: 	/order/preview/<activity:\w+>/<type:\w+>
     * Create a general order by buyer.(pay not yet)
     * @param string $type
     */
    public function createPreview($activity='general', $type="neweb") {
        $params     = $this->receiver;
        $cart       = $this->createCart($activity, $params);
        $preview    = $cart->toArray();

        foreach($preview["spec"] as $index => $spec) {
            $products = new WholesaleProductCollection();
            $record = $products->getRecordById($spec["product_id"]);
            $preview["spec"][$index]["product_name"]    = $record["name"];
            $preview["spec"][$index]["cover_photo"]     = $record["cover_photo_img"];
            $preview["spec"][$index]["weight"]          = $record["weight"];

            $specs = new WholesaleProductSpecCollection();
            $record = $specs->getRecordById($spec["spec_id"]);
            $preview["spec"][$index]["spec_name"]       = $record["name"];
            $preview["spec"][$index]["spec_serial"]     = $record["serial"];
            $preview["spec"][$index]["can_sale_inventory"]     = $record["can_sale_inventory"];
            if($preview["spec"][$index]["spec_amount"] > $record["can_sale_inventory"]){
                $this->responser->send(array() ,510);
            }

        }

        return $preview;
    }

    /**
     * POST: 	/order/<activity:\w+>/notify/<type:\w+>
     * Received payment result.
     * @param string $activity General || GroupBuying
     * @param string $type CashFlow service provider type.
     */
    public function notifyByActivity($activity="general", $type="neweb") {
        $provider       = $this->createServiceProvider($type);
        $notify 	    = $this->createUserNotify($activity);
        $tradeResult    = $_POST;

        $this->receiveNotify($provider, $notify, $tradeResult);
        $this->responser->send(array() , $this->responser->OK());
    }

    /**
     * Get condition for search product method from http request querystring.
     * There will filter querystring key, values.
     * @return array
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
        $this->getQueryString("consumerId", $condition);
        $this->getQueryString("payDateTimeStart", $condition);
        $this->getQueryString("payDateTimeEnd", $condition);
        $this->getQueryString("productId", $condition);
        $this->getQueryString("deliveryId", $condition);
        $this->getQueryString("productArray", $condition);
        
    	return $condition;
    }

    /**
     *  GET: 	/order/spec/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     *  Search order's spec list.
     *
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
    public function searchSpec($pageNo, $pageSize, $querystring) {
        $actor 		= PlatformUser::instanceBySession();
        $condition 	= $this->getCondition();
        $records 	= $this->collection
                                ->setActor($actor)
                                ->searchSpecRecords($pageNo, $pageSize, $condition);
        return $records;
    }

    /**
     * GET: 	/order/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * Search order list.
     *
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
	public function search($pageNo, $pageSize, $querystring) {
        $actor 		= PlatformUser::instanceBySession();
        $condition 	= $this->getCondition();
        $records 	= $this->collection
                        ->setActor($actor)
                        ->searchRecords($pageNo, $pageSize, $condition);
        return $records;
	}

    /**
     * GET: 	/order/search/consumer/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * Search order list.
     *
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
    public function searchByConsumer($pageNo, $pageSize,$querystring) {
        $consumerHelper = new GeneralOAuthHelper();
        $consumerList = $consumerHelper->getUserInfo();
        $condition 	= $this->getCondition();
        $condition['consumerId'] = $consumerList['info']['id'];
        $records 	= $this->collection
            ->searchRecords($pageNo, $pageSize, $condition);
        return $records;
    }

    /**
     *  GET: 	/order/spec/search/consumer/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     *  Search order's spec list.
     *
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
    public function searchSpecByConsumer($pageNo, $pageSize, $querystring) {
        $consumerHelper = new GeneralOAuthHelper();
        $consumerList = $consumerHelper->getUserInfo();
        $condition 	= $this->getCondition();
        $records 	= $this->collection
            ->searchSpecRecords($pageNo, $pageSize, $condition);
        return $records;
    }

    /**
     * GET:     /order/search/consumer/each/spec/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * Search order each consumer spec list.
     *
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
    public function searchEachConsumerSpec($pageNo, $pageSize,$querystring) {
        $consumerHelper = new GeneralOAuthHelper();
        $consumerList = $consumerHelper->getUserInfo();
        $condition  = $this->getCondition();
        $condition['consumerId'] = $consumerList['info']['id'];
        $variable   = $this->collection
            ->searchRecords($pageNo, $pageSize, $condition);
        $requirement = [];
        foreach ($variable['records'] as $key => $value) {

            $requirement['serial'] = $value['serial'];

            $records    = $this->collection
                ->searchSpecRecords(1,10, $requirement);
            $variable['records'][$key]['spec'] = $records;
            
        }
        return $variable;

    }

    /**
     * GET: /user/groupbuyingmaster/self/order/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * Search order list.
     *
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
    public function searchByMasterSelf($pageNo, $pageSize, $querystring) {
        $master 				    = GroupBuyingMasterUser::getRecordBySession();
        $condition 				    = $this->getCondition();
        $condition["activityType"]	= GroupBuyingActivity::TYPE_NAME;
        $condition["masterId"]	    = $master["id"];
        $records 				    = $this->collection
                                        ->searchRecords($pageNo, $pageSize, $condition);
        return $records;
    }

    /**
     * GET: /user/groupbuyingmaster/self/order/spec/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * Search order list.
     *
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
    public function searchSpecByMasterSelf($pageNo, $pageSize, $querystring) {
        $master 				    = GroupBuyingMasterUser::getRecordBySession();
        $condition 				    = $this->getCondition();
        $condition["activityType"]	= GroupBuyingActivity::TYPE_NAME;
        $condition["masterId"]	    = $master["id"];
        $records 				    = $this->collection
                                        ->searchSpecRecords($pageNo, $pageSize, $condition);
        return $records;
    }

    /**
     * PUT: 	/order/list/state
     * The platform admin update order's state by id list.
     * @return array
     * @throws DataAccessResultException
     * @throws InvalidAccessParamsException
     */
    public function updateStateByIds() {
        $attributes = array(
            'state' => 8
        );
        $ids        = $this->params("ids");
        $stateText  = $this->params("stateText");
        $count      = $this->collection->getById($ids)->update($attributes);
        return $attributes;
    }

	/**
	*	PUT: 	/order/<id:\d+>
	*	update order info
	*
	*	@param array(delivery_channel=> <string>, delivery_number=> <string>)
	*/
	public function update($id) {
        $attributes = array(
            'delivery_channel' => $this->params("delivery_channel"),
            'delivery_number' => $this->params("delivery_number"),
        );

        $nextState = $this->collection->getState($this->params("stateText"));
        $record = $this->collection->getRecordById( $id );
        if(count($record) == 0) {
            throw new DataAccessResultException("Not exists order [$id].");
        }

        $recordState = $this->collection->getState( $record['stateText'] );
        if ($recordState->canChangeState($nextState)) {
            foreach ($nextState->getChangeAttributes() as $key => $value) {
                $attributes[$key] = $value;
            }
        }

        $actor 		= PlatformUser::instanceBySession();
        $rowCount 	= $this->collection
                        ->setActor($actor)
                        ->multipleUpdateById(array($id), $attributes);

        if($rowCount == 0) {
            throw new DbOperationException("No changed in the operation.", 1);
        }
        return array();
	}

    /**
    *   PUT:    /order/remark/<id:\d+>
    *   remarkChange order info
    *
    *   @param 
    */
    public function remarkChange( $id ) {
        $collection = new UnifiedOrderCollection();
        $attributes = array(
            'remark' => $this->params("remark")
        );
        $count = $collection->getById($id)->update($attributes);
        if($count != 1) {
            throw new DbOperationException("update returned record to DB fail.");
        }
        return $attributes;
    }

    /**
    *   PUT:    /order/search/consumer/each/spec/remark/<id:\d+>
    *   change order status
    *
    *   @param 
    */
    public function remarkStatus( $id ) {
        $collection = new UnifiedOrderCollection();
        $attributes = array(
            'state' => 24
        );
        $count = $collection->getById($id)->update($attributes);
        if($count != 1) {
            throw new DbOperationException("update returned record to DB fail.");
        }
        return $attributes;
    }

    /**
    *   PUT:    /order/spec/search/remark/<id:\d+>
    *   change receiver phone,address and consumer_remark
    *
    *   @param 
    */
    public function remarkSpec( $id ) {
        $collection = new UnifiedOrderCollection;
        $attributes = array(
            'receiver_phone_number' => $this->params("receiver_phone_number"),
            'receiver_address' => $this->params("receiver_address"),
            'consumer_remark' => $this->params("consumer_remark"),
            'remark' => $this->params("remark")
        );
        $count = $collection->getById($id)->update($attributes);
        if($count != 1) {
            throw new DbOperationException("update returned record to DB fail.");
        }


        return $attributes;
    }

    /** PUT:     /order/delivery/search/<pageNo:\d+>/<pageSize:\d+>
     * Search delivery list.
     *
     */
    public function deliverySpec($pageNo, $pageSize) {
        $conditions = array();
        $condition['ids']  = $this->params("productArray");
        $collection = new ProductHasDeliveryCollection;
        $records    = $collection
                        ->searchPayType($pageNo, $pageSize, $condition);
        return $records;
    }

    /** PUT:    /order/delivery/search/payType/<pageNo:\d+>/<pageSize:\d+>
     * Search delivery list.
     *
     */
    public function deliveryMaxPrice($pageNo, $pageSize){
        $conditions = array();
        $condition['ids']  = $this->params("productArray");
        $condition['payType']  = $this->params("payTypeWay");
        $condition['priceTotalPrice']  = $this->params("priceTotalPrice");

        $collection = new ProductHasDeliveryCollection;
        $records    = $collection
                        ->searchPrice($pageNo, $pageSize, $condition);

        if ($condition['priceTotalPrice'] >= $records['records'][0]['target_amount']) {
            $newFare = 0;
        }else{
            $newFare = $records['records'][0]['amount'];
        }

        $attributes = array(
            'delivery_id' => $records['records'][0]['delivery_id'],
            'pay_type' => $records['records'][0]['pay_type'],
            'target_amount' => $records['records'][0]['target_amount'],
            'amount' => $records['records'][0]['amount'],
            'fare' => $newFare
        );
        return $attributes;
    }

    /**
    *   PUT:    /order/spec/update/product/<id:\d+>
    *   updata product datetime
    *
    *   @param 
    */
    public function updateProductNumber( $id ) {
        $collection = new OrderHasSpecCollection;
        $attributes = array(
            'product_number' => $this->params("number"),
            'status' => 1,
            'delivery_datetime' => date("Y-m-d H:i:s")
        );
        $count = $collection->getById($id)->update($attributes);
        if($count != 1) {
            throw new DbOperationException("update returned record to DB fail.");
        }
        return $attributes;
    }

    /**
    *   PUT:    /order/spec/update/delivery/<id:\d+>
    *   updata product datetime
    *
    *   @param 
    */
    public function updateOrderSpec( $id ) {
        $attributes = array(
            'state' => 4,
            'delivery_datetime' => date("Y-m-d H:i:s")
        );
        $count = $this->collection->getById($id)->update($attributes);
        if($count != 1) {
            throw new DbOperationException("update returned record to DB fail.");
        }
        return $attributes;
    }

}




?>