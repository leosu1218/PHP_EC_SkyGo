<?php

/**
 * Class CompletedReturnedState
 *
 */

require_once( dirname(__FILE__) . "/UnifiedReturnedState.php" );

class CompletedReturnedState implements UnifiedReturnedState {

    /**
     * Get the state's attribute's for update record state.
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
     * @param UnifiedReturnedState $state
     * @return bool If can change to the state, return true.
     */
    public function canChangeState(UnifiedReturnedState $state) {
        return false;
    }

    /**
     * Check the order value is match the state.
     *
     * @param UnifiedReturnedState $state
     * @return bool If match the state define, return true.
     */
    public function isMatch($record=array()) {
        if(array_key_exists("return_state", $record)) {
            if($record["return_state"] == $this->getStateCode()) {
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
        return 16;
    }
}



?>