<?php
/**
*	UnifiedReturnedCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Order
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/UnifiedReturned.php' );

require_once( dirname(__FILE__) . "/UnifiedOrder/SearchOrderCreateStart.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchOrderCreateEnd.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchOrderDeliveryDateStart.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchOrderDeliveryDateEnd.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchOrderIds.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchActivityIds.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchActivityId.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchSerial.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/SearchOrderSpecKeyword.php" );

require_once( dirname(__FILE__) . "/UnifiedReturned/SearchReturnedKeyword.php" );
require_once( dirname(__FILE__) . "/UnifiedReturned/SearchReturnedIds.php" );
require_once( dirname(__FILE__) . "/UnifiedReturned/JoinUnifiedReturned.php" );

require_once( dirname(__FILE__) . "/UnifiedOrder/JoinActivity.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/JoinWholesaleProdcut.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/JoinOrderHasSpec.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/JoinWholesaleProdcutSpec.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/JoinConsumerUser.php" );
require_once( dirname(__FILE__) . "/UnifiedOrder/JoinMaster.php" );


/**
*	UnifiedReturnedCollection Access Product entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Order
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class UnifiedReturnedCollection extends PermissionDbCollection {

	private $states;
    private $searchConditions;
    private $joinStatement;

    const PREPARE_RETURNED_STATE 		= "prepared";
	const RECEIVING_RETURNED_STATE 		= "receiving";
	const CANCEL_RETURNED_STATE 		= "cancel";
	const COMPLETED_RETURNED_STATE 		= "completed";

    public function __construct(&$dao=null) {
        parent::__construct($dao);

        $this->validator = new ValidatorHelper();
        $info = array();

        $this->states = array(
        	self::PREPARE_RETURNED_STATE 		=> new PrepareReturnedState(),
			self::RECEIVING_RETURNED_STATE 		=> new ReceivingReturnedState(),
			self::CANCEL_RETURNED_STATE 		=> new CancelReturnedState(),
			self::COMPLETED_RETURNED_STATE 		=> new CompletedReturnedState(),
        );

        $this->searchConditions = array(
            new SearchOrderIds(),
            new SearchSerial(),
            new SearchActivityIds(),
            new SearchActivityId(),
            new SearchReturnedKeyword(),
            new SearchOrderCreateStart(),
            new SearchOrderCreateEnd(),
            new SearchOrderDeliveryDateStart(),
            new SearchOrderDeliveryDateEnd(),
            new SearchReturnedIds(),
        );

        $this->joinStatement = array(
            new JoinActivity(),
            new JoinUnifiedReturned(),
            new JoinConsumerUser(),
        );

        $this->searchSpecConditions = array(
            new SearchSerial(),
            new SearchActivityIds(),
            new SearchOrderIds(),
            new SearchOrderSpecKeyword(),
        );

        $this->joinSpecStatement = array(
            new JoinUnifiedReturned(),
            new JoinActivity(),
            new JoinOrderHasSpec(),
            new JoinWholesaleProdcutSpec(),
            new JoinWholesaleProdcut(),
            new JoinConsumerUser(),
        );
    }

    /**
     * Override
     * Get record by id
     *
     * @param array $id
     * @return array
     */
    public function getRecordById($id) {
        $search = array("returnedIds" => array($id));
        $result = $this->searchRecords(1, 1, $search);

        if($result["recordCount"] == 0) {
            return array();
        }
        else {
            return $result["records"][0];
        }
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
                throw new OperationConflictException("Conflict change returned records[$id] from [$state] to state[$stateText].");
            }
        }

        return $this->multipleUpdateById($ids, $nextState->getChangeAttributes());
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
        $table      = "unified_order";
        $result     = $this->getDefaultRecords($pageNo, $pageSize);
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
            "uo.fare_id fare_id",
            "uo.fare_type fare_type",
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
        );

        $this->dao->fresh();
        $this->appendStatements($this->dao, $params, $conditions, $select, $search, $joinStatement);
        $this->appendStatements($this->dao, $params, $conditions, $select, $search, $searchConditions);

        if(array_key_exists('state', $search)) {
            if($search['state'] != "all") {
                $state = $this->getState($search['state']);
                $state->setDbCondition($this->dao, $conditions, $params, "ur");
            }
        }

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
     */
	public function searchRecords($pageNo, $pageSize, $search=array()) {
        return $this->executeGetRecords( $pageNo, $pageSize, $search, $this->joinStatement, $this->searchConditions );
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

	/* PermissionDbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "unified_returned";
	}

	public function getModelName() {
		return "UnifiedReturned";
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
