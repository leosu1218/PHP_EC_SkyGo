<?php
require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'system/exception/InvalidAccessParamsException.php' );

require_once( FRAMEWORK_PATH . 'models/GeneralActivity.php' );
require_once( FRAMEWORK_PATH . 'models/PlatformUser.php' );
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

require_once( dirname(__FILE__) . "/GeneralActivity/SearchGeneralActivityIds.php" );
require_once( dirname(__FILE__) . "/GeneralActivity/SearchGeneralActivityEndDate.php" );
require_once( dirname(__FILE__) . "/GeneralActivity/SearchGeneralActivityStartDate.php" );
require_once( dirname(__FILE__) . "/GeneralActivity/SearchGeneralActivityKeyword.php" );
require_once( dirname(__FILE__) . "/GeneralActivity/SearchGeneralActivityTag1.php" );
require_once( dirname(__FILE__) . "/GeneralActivity/SearchGeneralActivityTag2.php" );
require_once( dirname(__FILE__) . "/GeneralActivity/SearchGeneralActivityTag3.php" );
require_once( dirname(__FILE__) . "/GeneralActivity/SearchGeneralActivityMasterId.php" );
require_once( dirname(__FILE__) . "/GeneralActivity/SearchGeneralActivityId.php" );

require_once( dirname(__FILE__) . "/GeneralActivity/JoinPlatformUserWithGeneralActivity.php" );
require_once( dirname(__FILE__) . "/GeneralActivity/JoinWholesaleProductWithGeneralActivity.php" );

/**
 *	GeneralActivityCollection access entity collection.
 *
 *	PHP version 5.3
 *
 *	@category Collection
 *	@package GroupBuyingActivityCollection
 *	@author Rex chen <rexchen@synctech.ebiz.tw>
 *	@copyright 2015 synctech.com
 */
class GeneralActivityCollection extends PermissionDbCollection implements UnifiedActivityCollection {

    // All states object instance collection.
    private $states;
    private $searchConditions;

    const PREPARE_STATE                 = "prepare";
    const STARTED_STATE                 = "started";
    const COMPLETED_STATE               = "completed";

    public function __construct(&$dao=null) {
        parent::__construct($dao);

        $this->validator = new ValidatorHelper();
        $info = array(
            "type" => GeneralActivity::TYPE_NAME
        );

        $this->states = array(
            self::PREPARE_STATE                 => new PrepareGeneralActivityState($info),
            self::STARTED_STATE                 => new StartedGeneralActivityState($info),
            self::COMPLETED_STATE               => new CompletedGeneralActivityState($info),
        );

        $this->searchConditions = array(
            new SearchGeneralActivityIds(),
            new SearchGeneralActivityEndDate(),
            new SearchGeneralActivityStartDate(),
            new SearchGeneralActivityKeyword(),
            new SearchGeneralActivityTag1(),
            new SearchGeneralActivityTag2(),
            new SearchGeneralActivityTag3(),
            new SearchGeneralActivityMasterId(),
            new SearchGeneralActivityId(),
        );

        $this->joinStatement = array(
            new JoinPlatformUserWithGeneralActivity(),
            new JoinWholesaleProductWithGeneralActivity(),
        );
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
     *	Check attribute is valid for create.
     *	(validate max buyer, max price, datetime...etc)
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
        if(!$this->validator->isDateDifferentBetween($attributes["startDate"],$attributes["endDate"])){
            throw new Exception("Error [startDate and endDate] is equal.", 1);
        }
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
    public function appendStatements(DbHero &$dao, &$params, &$conditions, &$select, &$search, &$sqlStatements) {
        foreach ($sqlStatements as $key => $statement) {
            $statement->append($dao, $params, $conditions, $select, $search);
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
    public function searchRecords($pageNo, $pageSize, $search=array(),$blacklist = array()) {

        $result     = $this->getDefaultRecords($pageNo, $pageSize);
        $table      = $this->getTable();
        $conditions = array('and','1=1');
        $params     = array();
        $select     = array(
            'act.id id',
            'act.name name',
            'act.start_date start_date',
            'act.end_date end_date',
            'act.state state',
            'act.buyer_counter buyer_counter',
            'act.returner_counter returner_counter',
            'act.note note',
            'act.price price',
            'act.delivery_date delivery_date',
        );

        $this->dao->fresh();
        $this->appendStatements($this->dao, $params, $conditions, $select, $search, $this->joinStatement);
        $this->appendStatements($this->dao, $params, $conditions, $select, $search, $this->searchConditions);

        if(array_key_exists('state', $search)) {
            if($search['state'] != "all") {
                $state = $this->getState($search['state']);
                $state->setDbCondition($this->dao, $conditions, $params, "act");
            }
        }

        $this->dao->from("$table act");
        $this->dao->group('act.id');
        $this->dao->order('act.start_date DESC');
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
        return GeneralActivity::TYPE_NAME;
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
            $fareSearch                    = array("activityId" => $id, "activityType" => GeneralActivity::TYPE_NAME);
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
        return "general_activity";
    }

    public function getModelName() {
        return "GeneralActivity";
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
