<?php

/**
 * Class CancelOrderState
 *
 */

require_once( dirname(__FILE__) . "/UnifiedOrderState.php" );

class CancelOrderState implements UnifiedOrderState {

    /**
     * Get the state's attribute's for update activity state.
     *
     * @return array attributes
     */
    public function getChangeAttributes() {
        return array(
            "state" => $this->getStateCode(),
            "close_datetime" => date("Y-m-d H:i:s")
        );
    }

    /**
     * Check can change to next state.
     *
     * @param UnifiedOrderState $state
     * @return bool If can change to the state, return true.
     */
    public function canChangeState(UnifiedOrderState $state) {
        return false;
    }

    /**
     * Check can apply returned.
     *
     * @param mixed $applicant
     * @return bool If can apply returned, return true.
     */
    public function canApplyReturned($applicant) {
        return false;
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
        return false;
    }

    /**
     * Check the order value is match the state.
     *
     * @param UnifiedOrderState $state
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
     * @param $dao
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
        return 24;
    }
}



?>