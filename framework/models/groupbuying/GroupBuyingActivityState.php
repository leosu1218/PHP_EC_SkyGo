<?php

/**
 * Interface GroupBuyingActivityState
 *
 */

require_once( FRAMEWORK_PATH . 'extends/DbHero/DbHero.php' );
require_once( FRAMEWORK_PATH . 'system/exception/OperationConflictException.php' );

abstract class GroupBuyingActivityState {

    private $activityInfo ;

    /**
     *
     * @param $activity mixed The activity's object.
     */
    public function __construct($activityInfo) {
        $message = "GroupBuyingActivityState::__construct fail. Invalid activity's info, missing property ";
        if(!array_key_exists("warranty", $activityInfo)) {
            throw new Exception($message . "[warranty]");
        }
        else if(!array_key_exists("delivery", $activityInfo)) {
            throw new Exception($message . "[delivery]");
        }
        else if(!array_key_exists("type", $activityInfo)) {
            throw new Exception($message . "[type]");
        }
        else {
            $this->activityInfo = $activityInfo;
        }
    }

    /**
     * Get warranty cost days
     *
     * @return int The days of warranty cost.
     */
    public function getWarrantyDays() {
        return $this->activityInfo["warranty"];
    }

    /**
     * Get delivery cost days.
     *
     * @return int The days of delivery cost.
     */
    public function getDeliveryDays() {
        return $this->activityInfo["delivery"];
    }

    /**
     * Get activity's type name(used to DB)
     *
     * @return int The days of delivery cost.
     */
    public function getType() {
        return $this->activityInfo["type"];
    }

    /**
     * Get the state's attribute's for update activity state.
     *
     * @return array attributes
     */
    abstract public function getChangeAttributes();

    /**
     * Check can change to next state.
     *
     * @param GroupBuyingActivityState $state
     * @return bool If can change to the state, return true.
     */
    abstract public function canChangeState(GroupBuyingActivityState $state);

    /**
     * Check can export delivery list.
     *
     * @return bool If can change to the state, return true.
     */
    abstract public function canExportDeliveryList();

    /**
     * Check can export returned list.
     *
     * @return bool If can change to the state, return true.
     */
    abstract public function canExportReturnedList();

    /**
     * Check can export statement list.
     *
     * @return bool If can change to the state, return true.
     */
    abstract public function canExportStatementList();

    /**
     * Check the order value is match the state.
     *
     * @params $record array The record of GroupBuyingActivity.
     * @return bool If match the state define, return true.
     */
    abstract public function isMatch($record=array());

    /**
     * Set Db query statement condition.
     *
     * @param $dao DbHero The Db data access object of GroupBuyingActivity collection or model.
     * @param $conditions array Query condition's object reference of the state.
     * @param $params array Query params object reference of the state.
     * @param $tableAlias string Table alias name for query statement.
     */
    abstract public function setDbCondition(DbHero &$dao, &$conditions, &$params, $tableAlias);

    /**
     * Get state code of the state.
     *
     * @return int
     */
    abstract public function getStateCode();
}



?>