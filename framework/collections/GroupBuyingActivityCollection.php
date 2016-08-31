<?php
/**
 *	GroupBuyingActivityCollection code.
 *
 *	PHP version 5.3
 *
 *	@category Collection
 *	@package GroupBuyingMasterUserCollection
 *	@author Rex chen <rexchen@synctech.ebiz.tw>
 *	@copyright 2015 synctech.com
 */

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'system/exception/InvalidAccessParamsException.php' );

require_once( FRAMEWORK_PATH . 'models/GroupBuyingActivity.php' );
require_once( FRAMEWORK_PATH . 'models/GroupBuyingMasterUser.php' );
require_once( FRAMEWORK_PATH . 'models/UnifiedOrder.php' );
require_once( FRAMEWORK_PATH . 'models/UnifiedReturned.php' );

require_once( FRAMEWORK_PATH . 'collections/WholesaleProductSpecCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleMaterialsCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleExplainMaterialsCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleProductCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/FareCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/UnifiedActivityCollection.php' );

require_once( FRAMEWORK_PATH . 'extends/ValidatorHelper.php' );
require_once( FRAMEWORK_PATH . 'extends/LoggerHelper.php' );

require_once( dirname(__FILE__) . "/GroupBuyingActivity/SearchIds.php" );
require_once( dirname(__FILE__) . "/GroupBuyingActivity/SearchEndDate.php" );
require_once( dirname(__FILE__) . "/GroupBuyingActivity/SearchStartDate.php" );
require_once( dirname(__FILE__) . "/GroupBuyingActivity/SearchKeyword.php" );
require_once( dirname(__FILE__) . "/GroupBuyingActivity/SearchMasterId.php" );
require_once( dirname(__FILE__) . "/GroupBuyingActivity/OrderSequence.php" );

/**
 *	GroupBuyingActivityCollection access entity collection.
 *
 *	PHP version 5.3
 *
 *	@category Collection
 *	@package GroupBuyingActivityCollection
 *	@author Rex chen <rexchen@synctech.ebiz.tw>
 *	@copyright 2015 synctech.com
 */
class GroupBuyingActivityCollection extends PermissionDbCollection implements UnifiedActivityCollection {

    // All states object instance collection.
    private $states;
    private $searchConditions;
    private $orderByConditions;

    const PREPARE_STATE                 = "prepare";
    const STARTED_STATE                 = "started";
    const WAITING_DELIVERY_STATE        = "waitingdelivery";
    const DELIVERY_ALL_STATE            = "deliveryall";
    const DELIVERY_COMPLETED_STATE      = "deliverycompleted";
    const WARRANTY_PERIOD_STATE         = "warranty";
    const WAITING_RETURNED_STATE        = "waitingreturned";
    const RETURNED_ALL_STATE            = "returnedall";
    const WAITING_CHECK_STATEMENT_STATE = "waitingstatement";
    const CONFIRMED_STATEMENT_STATE     = "confirmedstatement";
    const ABNORMAL_STATEMENT_STATE      = "abnormalstatement";
    const COMPLETED_STATE               = "completed";

    public function __construct(&$dao=null) {
        parent::__construct($dao);

        $this->validator = new ValidatorHelper();
        $info = array(
            "warranty" => GroupBuyingActivity::WARRANTY_DAY,
            "delivery" => GroupBuyingActivity::DELIVERY_DAY,
            "type" => GroupBuyingActivity::TYPE_NAME
        );

        $this->states = array(
            self::PREPARE_STATE                 => new PrepareGroupBuyingState($info),
            self::STARTED_STATE                 => new StartedGroupBuyingState($info),
            self::WAITING_DELIVERY_STATE        => new WaitingDeliveryGroupBuyingState($info),
            self::DELIVERY_ALL_STATE            => new DeliveryAllGroupBuyingState($info),
            self::DELIVERY_COMPLETED_STATE      => new DeliveryCompletedGroupBuyingState($info),
            self::WARRANTY_PERIOD_STATE         => new WarrantyPeriodGroupBuyingState($info),
            self::WAITING_RETURNED_STATE        => new WaitingReturnedGroupBuyingState($info),
            self::RETURNED_ALL_STATE            => new ReturnedAllGroupBuyingState($info),
            self::WAITING_CHECK_STATEMENT_STATE => new WaitingCheckStatementGroupBuyingState($info),
            self::CONFIRMED_STATEMENT_STATE     => new ConfirmedStatementGroupBuyingState($info),
            self::ABNORMAL_STATEMENT_STATE      => new AbnormalStatementGroupBuyingState($info),
            self::COMPLETED_STATE               => new CompletedGroupBuyingState($info),
        );

        $this->searchConditions = array(
            new SearchIds(),
            new SearchEndDate(),
            new SearchStartDate(),
            new SearchKeyword(),
            new SearchMasterId(),
        );

        $this-> orderByConditions = new OrderSequence();
    }

    /**
     * Override
     * Get record by activity's id
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
     *	Check attribute is valid.
     *	(validat max buyer, max price, datetime...etc)
     *
     *	@param attributes array The attributes that want to create.
     *								Array(
     *									"name" 		=> $value[0],
     *			        				"productId" => $value[1],
     *		    	    				"masterId" 	=> $value[2],
     *		        					"buyMax" 	=> $value[3],
     *		        					"buyMin" 	=> $value[4],
     *		        					"price" 	=> $value[5],
     *		        					"startDate" => $value[6],
     *		        					"endDate" 	=> $value[7]
     *								)
     *	@return bool Return true when valid.
     */
    public function isValid($attributes) {

        // check parameters
        $this->validator->requireAttribute("name", $attributes);
        $this->validator->requireAttribute("productId", $attributes);
        $this->validator->requireAttribute("masterId", $attributes);
        $this->validator->requireAttribute("price", $attributes);
        $this->validator->requireAttribute("startDate", $attributes);
        $this->validator->requireAttribute("endDate", $attributes);

        $this->validator->requireNumeric("productId", $attributes);
        $this->validator->requireNumeric("masterId", $attributes);
        $this->validator->requireNumeric("price", $attributes);

        $result = (new WholesaleProductCollection())
            ->searchRecords(1, 1, array(
                "productId"		=> $attributes["productId"],
                "masterId"		=> $attributes["masterId"],
                "groupbuying"	=> 1,
            ));

        // Check permission
        if($result["totalRecord"] == 0) {
            throw new AuthorizationException("Not have permission use the product.", 1);
        }

        $product = $result["records"][0];

        // check date
        // $this->validator->requireDateGreaterThan($attributes, "startDate", $product, "productReady");
        // $this->validator->requireDateLessThan($attributes, "endDate", $product, "productRemoved");
        $this->validator->requireDateDuring($attributes, "startDate", $attributes, "endDate", $product["maxDays"]);
        $this->validator->requireDateOver($attributes, "startDate", $attributes, "endDate", $product["minDays"]);
        if(!$this->validator->isDateDifferentBetween($attributes["startDate"],$attributes["endDate"])){
            throw new Exception("Error [startDate and endDate] is equal.", 1);
        }

        // check price
        $this->validator->requireNumericGreaterThan($attributes, "price", $product, "minPrice");
        return true;
    }

    /**
     * Get activity state object.
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
            $searchRecords[$index]["stateText"] = $this->getStateNameFromRecord($record);
        }
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
    public function appendSearchStatements(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        foreach ($this->searchConditions as $key => $searchCondition) {
            $searchCondition->append($dao, $params, $conditions, $select, $search);
        }
    }

    /**
     * Append JOIN SQL statement of returned info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function joinReturnedStatements(DbHero &$dao, &$params, &$conditions, &$select) {
        $cancel         = new CancelReturnedState();
        $completed      = new CompletedReturnedState();
        $cancelCode     = $cancel->getStateCode();
        $completedCode  = $completed->getStateCode();

        $prepare        = new PrepareReturnedState();
        $receiving      = new ReceivingReturnedState();
        $prepareCode    = $prepare->getStateCode();
        $receivingCode  = $receiving->getStateCode();

        $type           = GroupBuyingActivity::TYPE_NAME;

        $subTable = "(SELECT
                        COUNT(rt.activity_id) counter,
                        rt.activity_id activity_id
                    FROM
                        unified_returned rt
                    WHERE
                        rt.activity_type='$type' and
                          (rt.state=$prepareCode or rt.state=$receivingCode)
                    GROUP BY
                        rt.activity_id
                    ) returned";

        $this->dao->leftJoin($subTable, 'returned.activity_id=gbActivity.id');
        array_push($select, 'returned.counter waiting_return_counter');
    }

    /**
     * Append JOIN SQL statement of order info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function joinOrderStatements(DbHero &$dao, &$params, &$conditions, &$select) {
        $delivering     = new DeliveringOrderState();
        $deliveringCode = $delivering->getStateCode();
        $type           = GroupBuyingActivity::TYPE_NAME;

        $subTable = "(SELECT
                        COUNT(uo.activity_id) counter,
                        uo.activity_id activity_id
                    FROM
                        unified_order uo
                    WHERE
                        uo.activity_type='$type' and
                        uo.state=$deliveringCode and
                        uo.delivery_datetime IS NOT NULL
                    GROUP BY
                        uo.activity_id
                    ) delivery_order";

        $dao->leftJoin($subTable, 'delivery_order.activity_id=gbActivity.id');
        array_push($select, 'delivery_order.counter completed_delivery_counter');
    }

    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function joinMasterStatements(DbHero &$dao, &$params, &$conditions, &$select) {
        $dao->leftJoin( 'gb_master_user master', 'master.id=gbActivity.master_id');
        array_push($select, 'master.name gbMasterName');
    }

    /**
     * Append JOIN SQL statement of wholesale products info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function joinProductStatements(DbHero &$dao, &$params, &$conditions, &$select , &$search) {
        $this->dao->leftJoin('wholesale_product wp', 'wp.id=gbActivity.product_id');
        array_push($select, 'wp.name productName');
        array_push($select, 'wp.suggest_price suggestPrice');
        array_push($select, 'wp.detail detail');
        array_push($select, 'wp.cover_photo_img coverPhoto');
        array_push($select, 'wp.explain_text explainText');
        array_push($select, 'wp.active_groupbuying groupbuying');
        array_push($select, 'wp.youtube_url youtubeUrl');
        array_push($select, 'wp.media_type mediaType');
        array_push($select, 'wp.id productId');

        if(array_key_exists('actor', $search)) {
            if($search['actor'] == 'admin') {
                array_push($select, 'wp.wholesale_price wholesalePrice');
            }
        }
    }

    /**
     * Search records.
     *
     * @param int $pageNo Result's page number.
     * @param int $pageSize Result's page size.
     * @param array $search Search condition's params.
     * @return array The result records.
     * @throws Exception
     * @throws InvalidAccessParamsException
     */
    public function searchRecords($pageNo, $pageSize, $search=array()) {

        $result     = $this->getDefaultRecords($pageNo, $pageSize);
        $table      = $this->getTable();
        $conditions = array('and','1=1');
        $params     = array();
        $select     = array(
            'gbActivity.id id',
            'gbActivity.name name',
            'gbActivity.start_date start_date',
            'gbActivity.end_date end_date',
            'gbActivity.state state',
            'gbActivity.buyer_counter buyer_counter',
            'gbActivity.returner_counter returner_counter',
            'gbActivity.note note',
            'gbActivity.price price',
            'gbActivity.delivery_date delivery_date',
        );

        $this->dao->fresh();
        $this->joinMasterStatements($this->dao, $params, $conditions, $select);
        $this->joinOrderStatements($this->dao, $params, $conditions, $select);
        $this->joinReturnedStatements($this->dao, $params, $conditions, $select);
        $this->joinProductStatements($this->dao, $params, $conditions, $select,$search);
        $this->appendSearchStatements($this->dao, $params, $conditions, $select, $search);
        $this->orderByConditions->append($this->dao, $params, $conditions, $select, $search);

        if(array_key_exists('state', $search)) {
            if($search['state'] != "all") {
                $state = $this->getState($search['state']);
                $state->setDbCondition($this->dao, $conditions, $params, "gbActivity");
            }
        }

        $this->dao->from("$table gbActivity");
        $this->dao->group('gbActivity.id');
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
     * Update state by id list.
     *
     * @param array $ids The list that want to update state.
     * @param string $stateText The state text.(Defined by GroupBuyingActivityCollection::STATE)
     * @return int Effect rows count.
     */
    public function updateStateByIds($ids=array(), $stateText='') {
        // TODO add permission control
        $result = $this->searchRecords(1, 9999, array("ids" => $ids));
        $records = $result["records"];
        $nextState = $this->getState($stateText);

        foreach($records as $index => $record) {
            $recordState = $this->getState($record["stateText"]);
            if(!$recordState->canChangeState($nextState)) {
                $id = $record["id"];
                throw new OperationConflictException("Conflict change activity records[$id] to state[$stateText].");
            }
        }

        return $this->multipleUpdateById($ids, $nextState->getChangeAttributes());
    }

    /* UnifiedActivityCollection interface methods. */

    /**
     * @return string
     */
    public function getUnifiedType() {
        return GroupBuyingActivity::TYPE_NAME;
    }

    /**
     * Get unified record by activity's id.
     *
     * @param int $id
     * @return mixed
     */
    public function getUnifiedById($id=0) {

        $record = $this->getRecordById($id);
        if(count($record) == 0) {
            return array();
        }
        else {
            $fareSearch                    = array("activityId" => $id, "activityType" => GroupBuyingActivity::TYPE_NAME);
            $productSpec                   = new WholesaleProductSpecCollection();
            $productMaterials              = new WholesaleMaterialsCollection();
            $productExplainMaterials       = new WholesaleExplainMaterialsCollection();
            $fares                         = new FareCollection();
            $condition                     = array("product_id" => $record["productId"]);
            $record["spec"]                = $productSpec->getRecords($condition);
            $record["materials"]           = $productMaterials->getRecords($condition);
            $record["explainMaterials"]    = $productExplainMaterials->getRecords($condition);
            $record["fares"]               = $fares->searchRecords(1, 1000, $fareSearch);

            return $record;
        }
    }

    /* DbCollection abstract methods. */

    /**
     *	Get the entity table name.
     *
     *	@return string
     */
    public function getTable() {
        return "groupbuying_activity";
    }

    public function getModelName() {
        return "GroupBuyingActivity";
    }

    /**
     *	Check attributes is valid.
     *
     *	@param $attributes 	array Attributes want to checked.
     *	@return bool 		If valid return true.
     */
    public function validAttributes($attributes) {

        if(array_key_exists("id", $attributes)) {
            throw new Exception("Can't write the attribute 'id'.");
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
