<?php

/**
 * Class ConfirmedStatementGroupBuyingState
 *
 */

require_once( dirname(__FILE__) . "/GroupBuyingActivityState.php" );

class ConfirmedStatementGroupBuyingState extends GroupBuyingActivityState {

    /**
     * Get the state's attribute's for update activity state.
     *
     * @return array attributes
     */
    public function getChangeAttributes() {
        return array(
            "state" => $this->getStateCode(),
            "response_statement_date" => date("Y-m-d H:i:s")
        );
    }

    /**
     * Check can export delivery list.
     *
     * @return bool If can change to the state, return true.
     */
    public function canExportDeliveryList() {
        return false;
    }

    /**
     * Check can export returned list.
     *
     * @return bool If can change to the state, return true.
     */
    public function canExportReturnedList() {
        return false;
    }

    /**
     * Check can export statement list.
     *
     * @return bool If can change to the state, return true.
     */
    public function canExportStatementList() {
        return true;
    }

    /**
     * Check can change to next state.
     *
     * @param GroupBuyingActivityState $state
     * @return bool If can change to the state, return true.
     */
    public function canChangeState(GroupBuyingActivityState $state) {
        return ($state instanceof CompletedGroupBuyingState);
    }

    /**
     * Check the order value is match the state.
     *
     * @params $record array The record of GroupBuyingActivity.
     * @return bool If match the state define, return true.
     */
    public function isMatch($record=array()) {
        if(array_key_exists("state", $record)) {
            if($record["state"] == $this->getStateCode()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Set Db query statement condition.
     *
     * @param $dao DbHero The Db data access object of GroupBuyingActivity collection or model.
     * @param $conditions array Query condition's object reference of the state.
     * @param $params array Query params object reference of the state.
     * @param $tableAlias string Table alias name for query statement.
     */
    public function setDbCondition(DbHero &$dao, &$conditions, &$params, $tableAlias) {
        array_push($conditions, "$tableAlias.state=:state");
        $params[':state'] = $this->getStateCode();
    }

    /**
     * Get state code of the state.
     *
     * @return int
     */
    public function getStateCode() {
        return 12;
    }
}



?>