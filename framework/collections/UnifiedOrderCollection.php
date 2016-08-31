<?php
/**
 * UnifiedOrderCollection code.
 *
 * @category Collection
 * @package Order
 * @author Rex chen <rexchen@synctech.ebiz.tw>, Jai Chien <jaichien@synctech.ebiz.tw>
 * @copyright 2015 synctech.com
 */
require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/MailHelper.php' );
require_once( FRAMEWORK_PATH . 'extends/AuthenticateHelper.php' );
require_once( FRAMEWORK_PATH . 'extends/CashFlow/Skygo/SkygoCashFlow.php' );
require_once( FRAMEWORK_PATH . 'collections/EC/Skygo/SkygoEC.php' );

require_once( FRAMEWORK_PATH . 'collections/UnifiedActivityCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/OrderHasSpecCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/UnifiedReturnedCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/ConsumerUserHasOrderCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/ReimburseCollection.php' );
require_once( FRAMEWORK_PATH . 'models/UnifiedOrder.php' );
require_once( FRAMEWORK_PATH . 'models/WholesaleProductSpec.php' );

require_once( dirname(__FILE__) . "/UnifiedOrder/SearchOrderCreateStart.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchOrderCreateEnd.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchOrderDeliveryDateStart.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchOrderDeliveryDateEnd.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchOrderKeyword.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchOrderIds.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchActivityIds.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchActivityId.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchSerial.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchOrderSpecKeyword.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchConsumerId.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchPayDateTimeStart.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchPayDateTimeEnd.php" );

require_once( dirname(__FILE__) . "/UnifiedOrder/JoinActivity.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/JoinWholesaleProdcut.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/JoinOrderHasSpec.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/JoinWholesaleProdcutSpec.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/JoinConsumerUser.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/JoinMaster.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/JoinReimburse.php" );




class UnifiedOrderCollection extends PermissionDbCollection implements CashFlowOrderCollection {

    private $states;
    private $searchConditions;
    private $searchSpecConditions;
    private $joinStatement;
    private $joinSpecStatement;

    const PREPARED_ORDER_STATE          = "prepared";      //0
    const ABNORMAL_ORDER_STATE          = "abnormal";      //1
    const PAID_ORDER_STATE              = "paid";          //4 - no delivery number
    const DELIVERING_ORDER_STATE        = "delivering";    //4 - has delivery number
    const WARRANTY_PERIOD_ORDER_STATE   = "warrantyperiod";//4 - Before three days of delivery datetime
    const APPLY_CANCEL_ORDER_STATE      = "applycancel";   //8
    const COMPLETED_ORDER_STATE         = "completed";     //4
    const RETURNED_ORDER_STATE          = "returned";      //12
    const CANCEL_ORDER_STATE            = "cancel";        //24


    public function __construct(&$dao=null) {
        parent::__construct($dao);
        $this->validator = new ValidatorHelper();

        $this->states = array(
            self::PREPARED_ORDER_STATE          => new PreparedOrderState(),
            self::ABNORMAL_ORDER_STATE          => new AbnormalOrderState(),
            self::PAID_ORDER_STATE              => new PaidOrderState(),
            self::DELIVERING_ORDER_STATE        => new DeliveringOrderState(),
            self::WARRANTY_PERIOD_ORDER_STATE   => new WarrantyPeriodOrderState(),
            self::APPLY_CANCEL_ORDER_STATE      => new ApplyCancelOrderState(),
            self::COMPLETED_ORDER_STATE         => new CompletedOrderState(),
            self::RETURNED_ORDER_STATE          => new ReturnedOrderState(),
            self::CANCEL_ORDER_STATE            => new CancelOrderState(),
        );

        $this->searchConditions = array(
            new SearchOrderIds(),
            new SearchSerial(),
            new SearchActivityIds(),
            new SearchActivityId(),
            new SearchOrderKeyword(),
            new SearchOrderCreateStart(),
            new SearchOrderCreateEnd(),
            new SearchOrderDeliveryDateStart(),
            new SearchOrderDeliveryDateEnd(),
            new SearchConsumerId(),
            new SearchPayDateTimeStart(),
            new SearchPayDateTimeEnd(),
        );

        $this->joinStatement = array(
            new JoinActivity(),
            new JoinConsumerUser(),
        );

        $this->searchSpecConditions = array(
            new SearchSerial(),
            new SearchActivityIds(),
            new SearchOrderIds(),
            new SearchOrderSpecKeyword(),
        );

        $this->joinSpecStatement = array(
            new JoinActivity(),
            new JoinOrderHasSpec(),
            new JoinWholesaleProdcutSpec(),
            new JoinWholesaleProdcut(),
            new JoinConsumerUser(),
            new JoinReimburse(),
        );
    }

    /**
     * Update state by id list.
     *
     * @param array $ids The list that want to update state.
     * @param string $stateText The state text.(Defined by GroupBuyingActivityCollection::STATE)
     * @return int Effect rows count.
     */
    public function updateStateByIds($ids=array(), $stateText='') {
        // TODO refactoring (Too long method)
        $result = $this->searchRecords(1, 9999, array("ids" => $ids));
        $records = $result["records"];
        $nextState = $this->getState($stateText);

        foreach($records as $index => $record) {
            $recordState = $this->getState($record["stateText"]);
            if(!$recordState->canChangeState($nextState)) {
                $id = $record["id"];
                $state = $record["stateText"];
                throw new OperationConflictException("Conflict change order records[$id] from [$state] to state[$stateText].");
            }
        }

        return $this->multipleUpdateById($ids, $nextState->getChangeAttributes());
    }

    /**
     * Get state name from a record.
     *
     * @params $record array The record of GroupBuyingActivity.
     * @return string State name.
     */
    public function getStateNameFromRecord($record) {
        $stateName = "Undefined";
        foreach($this->states as $name => $state) {
            if($state->isMatch($record)) {
                $stateName = $name;
                break;
            }
        }
        return $stateName;
    }

    /**
     * Append field stateText to search records.
     *
     * @param &$searchRecords array The records of search result.
     */
    public function insertStateText(&$searchRecords) {
        foreach($searchRecords as $index => $record) {
            $searchRecords[$index]["stateText"] = $this->getStateNameFromRecord($searchRecords[$index]);
        }
    }

    /**
     * Get state object.
     *
     * @param $state string The state name
     * @throws InvalidAccessParamsException
     */
    public function getState($state) {
        if(array_key_exists($state, $this->states)) {
            return $this->states[$state];
        }
        else {
            throw  new InvalidAccessParamsException("Invalid state [$state]");
        }
    }

    /**
     * Override
     * Get record by id
     *
     * @param array $id
     * @return array
     */
    public function getRecordById($id) {
        $search = array("ids" => array($id));
        $result = $this->searchRecords(1, 1, $search);

        if($result["recordCount"] == 0) {
            return array();
        }
        else {
            return $result["records"][0];
        }
    }

    /**
     * @param $pageNo
     * @param $pageSize
     * @param array $search
     * @return array
     */
    public function searchRecords( $pageNo, $pageSize, $search=array() ) {
        return $this->executeGetRecords( $pageNo, $pageSize, $search, $this->joinStatement, $this->searchConditions );
    }

    /**
     * @param $pageNo
     * @param $pageSize
     * @param array $search
     * @return array
     */
    public function searchSpecRecords( $pageNo, $pageSize, $search=array() ) {
        return $this->executeGetRecords( $pageNo, $pageSize, $search, $this->joinSpecStatement, $this->searchSpecConditions );
    }

    /**
     * @param $pageNo
     * @param $pageSize
     * @param array $search
     * @param $joinStatement
     * @param $searchConditions
     * @return array
     * @throws Exception
     * @throws InvalidAccessParamsException
     */
    private function executeGetRecords( $pageNo, $pageSize, $search=array(), &$joinStatement, &$searchConditions ) {
        $result     = $this->getDefaultRecords($pageNo, $pageSize);
        $table      = $this->getTable();
        $conditions = array('and','1=1');
        $params     = array();
        $select     = array(
            "uo.id id",
            "uo.activity_id activity_id",
            "uo.activity_type activity_type",
            "uo.consumer_user_id consumer_user_id",
            "uo.buyer_name buyer_name",
            "uo.buyer_phone_number buyer_phone_number",
            "uo.buyer_email buyer_email",
            "uo.product_total_price product_total_price",
            "uo.final_total_price final_total_price",
            "uo.other_cost other_cost",
            "uo.cost_type cost_type",
            "uo.fare fare",
            "uo.fare_type fare_type",
            "uo.fare_id fare_id",
            "uo.discount discount",
            "uo.discount_type discount_type",
            "uo.payment_type payment_type",
            "uo.receiver_address receiver_address",
            "uo.receiver_name receiver_name",
            "uo.receiver_phone_number receiver_phone_number",
            "uo.state state",
            "uo.create_datetime create_datetime",
            "uo.pay_notify_datetime pay_notify_datetime",
            "uo.serial serial",
            "uo.delivery_datetime delivery_datetime",
            "uo.delivery_channel delivery_channel",
            "uo.delivery_number delivery_number",
            "uo.close_datetime close_datetime",
            "uo.inventory_process inventory_process",
            "uo.companyName companyName",
            "uo.taxID taxID",
            "uo.remark remark",
            "uo.consumer_remark consumer_remark"
        );

        $this->dao->fresh();
        $this->appendStatements($this->dao, $params, $conditions, $select, $search, $joinStatement);
        $this->appendStatements($this->dao, $params, $conditions, $select, $search, $searchConditions);

        if(array_key_exists('state', $search)) {
            if($search['state'] != "all") {
                $state = $this->getState($search['state']);
                $state->setDbCondition($this->dao, $conditions, $params, "uo");
            }
        }

        $this->dao->order("uo.id DESC");
        $this->dao->from("$table uo");
        $this->dao->where($conditions,$params);
        $this->dao->select($select);

        $result['recordCount'] = intval($this->dao->queryCount());
        $result["totalPage"] = intval(ceil($result['recordCount'] / $pageSize));

        $this->dao->paging($pageNo, $pageSize);
        $result["records"] = $this->dao->queryAll();

        $this->insertStateText($result["records"]);
        return $result;
    }

    /**
     * Append search condition's statement for search records sql.
     *
     * @param DbHero $dao  The data access object want to set statements.
     * @param $params array SQL's params (reference PDO)
     * @param $conditions array  SQL's condition statements.
     * @param $select array SQL's select fields.
     * @param $search array Search value and params.
     */
    public function appendStatements(DbHero &$dao, &$params, &$conditions, &$select, &$search, &$sqlStatements) {
        foreach ($sqlStatements as $key => $statement) {
            $statement->append($dao, $params, $conditions, $select, $search);
        }
    }

    /**
     * Insert order record.
     * @param UnifiedUserCollection $user
     * @param UnifiedCartCollection $cart
     * @throws AuthorizationException
     * @throws DbOperationException
     */
    private function insertOrder(UnifiedUserCollection &$user, UnifiedCartCollection &$cart) {
        $authHelper     = new AuthenticateHelper();
        $orderData = array(
            'activity_id' 			=> $cart->getActivityId(),
            'activity_type'         => $cart->getUnifiedType(),
            'consumer_user_id'      => $user->getUnifiedId(),

            'buyer_name' 			=> $cart->getBuyerName(),
            'buyer_phone_number' 	=> $cart->getBuyerPhone(),
            'buyer_email' 			=> $cart->getBuyerEmail(),
            'receiver_address' 		=> $cart->getReceiverAddress(),

            'receiver_name' 		=> $cart->getReceiverName(),
            'receiver_phone_number' => $cart->getReceiverPhone(),

            'final_total_price' 	=> $cart->getFinalTotalPrice(),
            'product_total_price' 	=> $cart->getProductPrice(),
            'other_cost' 		    => $cart->getOtherCost(),
            'cost_type' 		    => $cart->getCostType(),
            'fare' 		            => $cart->getFare(),
            'fare_type' 		    => $cart->getFareType(),
            'fare_id' 		        => $cart->getFareId(),
            'discount' 		        => $cart->getDiscount(),
            'discount_type' 		=> $cart->getDiscountType(),
            'payment_type' 		    => $cart->getPaymentType(),

            'create_datetime' 		=> $cart->getCreateDatetime(),
            'pay_notify_datetime'   => $cart->getNotifyDatetime(),
            'serial' 		        => $cart->getSerial(),

            'delivery_datetime' 	=> $cart->getDeliveryDateTime(),
            'delivery_channel' 		=> $cart->getDeliveryChannel(),
            'delivery_number'       => $cart->getDeliveryNumber(),
            'close_datetime' 		=> $cart->getCloseDateTime(),
            'inventory_process'     => $cart->getInventoryProcess(),
            'companyName'           => $cart->getCompanyName(),
            'taxID'                 => $cart->getTaxID(),
            'consumer_remark'       => $cart->getConsumerRemark(),
        );

        $rowCount = $this->create($orderData);
//        var_dump($this->dao->getSql());
        if($rowCount != 1) {
            throw new DbOperationException("Insert order record fail.");
        }

        $orderData["id"] = $this->dao->lastInsertId();

        return $orderData;
    }

    /**
     * Insert order's spec records.
     * @param UnifiedUserCollection $user
     * @param UnifiedCartCollection $cart
     * @throws AuthorizationException
     * @throws DbOperationException
     * @throws Exception
     */
    private function insertSpec(UnifiedUserCollection &$user, UnifiedCartCollection &$cart) {

        $orderId = $this->dao->lastInsertId();
        $attributes = array(
            "order_id",
            "product_id",
            "spec_id",
            "unit_price",
            "total_price",
            "spec_amount",
            "other_cost",
            "cost_type",
            "fare",
            "fare_type",
            "discount",
            "discount_type",
            "activity_type",
            "activity_id",
        );

        $values = array();
        foreach($cart->getSpecs() as $key => $spec) {
            $spec["order_id"] = $orderId;

            $item = array();
            foreach($attributes as $index => $attribute) {
                $item[$index] = $spec[$attribute];
            }
            array_push($values, $item);
        }

        $collection = new OrderHasSpecCollection($this->dao);
        $rowCount = $collection->multipleCreate($attributes, $values);

        if($rowCount != count($values)) {
            throw new DbOperationException("Insert order's spec records fail.");
        }
    }

    /**
     * Insert user's order records.
     * @param UnifiedUserCollection $user
     * @param UnifiedCartCollection $cart
     */
    private function insertUser(UnifiedUserCollection &$user, UnifiedCartCollection &$cart) {
        $record = array(
            'user_id'   => $user->getUnifiedId(),
            'order_id'  => $this->dao->lastInsertId()
        );

        $collection = new ConsumerUserHasOrderCollection($this->dao);
        $rowCount = $collection->create($record);
        if($rowCount != 1) {
            throw new DbOperationException("Insert user's order record fail.");
        }
    }

    /**
     * Generate a new order(Fully function).
     * @param UnifiedUserCollection $user
     * @param UnifiedCartCollection $cart
     * @throws DbOperationException
     * @throws Exception
     * @return array Order info.
     */
    public function generate(UnifiedUserCollection &$user, UnifiedCartCollection &$cart) {

        if($this->dao->transaction()) {
            try {
                $record = $this->insertOrder($user, $cart);
                $this->insertSpec($user, $cart);
                $this->insertUser($user, $cart);
                $this->dao->commit();
                return $record;
            }
            catch(Exception $e) {
                $this->dao->rollback();
                throw $e;
            }
        }
        else {
            throw new DbOperationException("Begin transaction fail.");
        }
    }

    /**
     * Get record for recordResult method.
     * @param array $attribute
     * @return mixed
     * @throws DataAccessResultException
     */
    private function innerGetRecord($attribute=array()) {
        $result = $this->searchRecords(1, 1, $attribute);
        if($result["recordCount"] == 1) {
            $record = $result["records"][0];
        }
        else {
            throw new DataAccessResultException("Not exists the order.");
        }

        //        TODO refactoring (only groupbuying now)
        if($record["activity_type"] == GroupBuyingActivity::TYPE_NAME) {
            $activity = new GroupBuyingActivity($record["activity_id"], $this->dao);
            $record["activity_name"] = $activity->getAttribute("name");
        }
        else if($record["activity_type"] == GeneralActivity::TYPE_NAME){
//            $activity = new GeneralActivity($record["activity_id"], $this->dao);
        }
        else if($record["activity_type"] == "reorder_" . GroupBuyingActivity::TYPE_NAME) {
            $activity = new GroupBuyingActivity($record["activity_id"], $this->dao);
            $record["activity_name"] = $activity->getAttribute("name");
        }
        else if($record["activity_type"] == "reorder_" . GeneralActivity::TYPE_NAME){
//            $activity = new GeneralActivity($record["activity_id"], $this->dao);
        }
        else {
            $type = $record["activity_type"];
            throw new InvalidAccessParamsException("Invalid activity type[$type]");
        }



        //        TODO refactoring (only groupbuying now)
        $record["specs"] = $this->searchSpecRecords(1, 10000, array( "serial" => $record["serial"] ));

        return $record;
    }

    /**
     * Inner update a order record's state.
     * @param array $record Order's record by searchRecord method.
     * @param string $state
     * @throws AuthorizationException
     * @throws DataAccessResultException
     * @throws InvalidAccessParamsException
     */
    private function innerUpdateOrderState($record=array(), UnifiedOrderState $nextState) {
        $rowCount       = 0;
        $recordState    = $this->getState($record["stateText"]);

        if($recordState->canChangeState($nextState)) {
            $rowCount = $this->multipleUpdateById(array($record["id"]), $nextState->getChangeAttributes());
        }
        else {
            throw new DataAccessResultException("Invalid order state.");
        }

        if($rowCount != 1) {
            throw new DataAccessResultException("Update order state fail.");
        }
    }

    /**
     * Increasing activity's buyer counter.
     * @param $record Order's record by searchRecord method.
     * @throws DataAccessResultException
     * @throws InvalidAccessParamsException
     */
    private function innerIncreaseActivityCounter($record) {
//        TODO refactoring groupbuying now
        if($record["activity_type"] == GroupBuyingActivity::TYPE_NAME) {
            $activity = new GroupBuyingActivity($record["activity_id"], $this->dao);
            $rowCount = $activity->increaseAttributes(array("buyer_counter" => 1));
            if($rowCount != 1) {
                throw new DataAccessResultException("Update order's activity buyer counter fail.");
            }
        }
        else if($record["activity_type"] == GeneralActivity::TYPE_NAME) {
            // Do nothing
        }
        else if($record["activity_type"] == "reorder_" . GroupBuyingActivity::TYPE_NAME) {
            // Do nothing
        }
        else if($record["activity_type"] == "reorder_" . GeneralActivity::TYPE_NAME){
            // Do nothing
        }
        else {
            throw new InvalidAccessParamsException("Invalid activity type in the order record.");
        }
    }

    /**
     * Decrease spec's inventory.
     * @param array $record Order's record by searchRecord method.
     * @throws AuthorizationException
     * @throws DataAccessResultException
     * @throws Exception
     * @throws OperationConflictException
     */
    private function innerDecreaseSpecInventory($record=array()) {
        if($record["activity_type"] != "reorder") {
            $hasSpec        = new OrderHasSpecCollection($this->dao);
            $hasSpecRecords = $hasSpec->getRecords(array("order_id" => $record["id"]));

            foreach($hasSpecRecords["records"] as $index => $hasSpecRecord) {
                $specId = $hasSpecRecord["spec_id"];
                $amount = $hasSpecRecord["spec_amount"];

                $spec       = new WholesaleProductSpec($specId, $this->dao);
                $rowCount   = $spec->increaseAttributes(array("can_sale_inventory" => (-1 * $amount) ));
                if($rowCount != 1) {
                    throw new DataAccessResultException("Update order's spec inventory counter fail.");
                }

                $inventory  = $spec->getAttribute("can_sale_inventory");
//                if($inventory < 0) {
//                    throw new OperationConflictException("Spec's inventory not enough.");
//                }
            }
        }
    }

    /* CashFlowOrderCollection interface methods. */

    /**
     *  Recording order trade result.
     *  Update order state.
     *  Increasing product counter, buyer counter with activity.
     *  Decreasing specs inventory counter with spec.
     *
     *	@param $state mixed State code or object.
     *	@param $outTradeNo string The trade number.
     */
    public function recordResult($state, $outTradeNumber='') {
        if($this->dao->transaction()) {
            try {
                $search = array("serial" => $outTradeNumber,);
                $record = $this->innerGetRecord($search);
                $this->innerUpdateOrderState($record, $state);
                $this->innerIncreaseActivityCounter($record);
                $this->innerDecreaseSpecInventory($record);
                $this->dao->commit();
                return $record;
            }

            catch(Exception $e) {
                $this->dao->rollback();
                throw $e;
            }
        }
        else {
            throw new DbOperationException("Begin transaction fail.");
        }
    }

    /**
     * Get trade success state code or object.
     * @return mixed
     */
    public function getTradeSuccessState() {
        return $this->getState(self::PAID_ORDER_STATE);
    }

    /**
     * Get trade error state code or object.
     * @return mixed
     */
    public function getTradeErrorState() {
        return $this->getState(self::ABNORMAL_ORDER_STATE);
    }

    /**
     * Get waiting trade state code or object.
     * @return mixed
     */
    public function getWaitingTradeState() {
        return $this->getState(self::PREPARED_ORDER_STATE);
    }

    /* PermissionDbCollection abstract methods. */

    /**
     *	Get the entity table name.
     *
     *	@return string
     */
    public function getTable() {
        return "unified_order";
    }

    public function getModelName() {
        return "UnifiedOrder";
    }

    /**
     *	Check attributes is valid.
     *
     *	@param $attributes 	array Attributes want to checked.
     *	@return bool 		If valid return true.
     */
    public function validAttributes($attributes) {

        if(array_key_exists("id", $attributes)) {
            throw new InvalidAccessParamsException("Can't write the attribute 'id'.");
        }

        return true;
    }

    /**
     *	Get Primary key attribute name
     *
     *	@return string
     */
    public function getPrimaryAttribute() {
        return "id";
    }
}

?>