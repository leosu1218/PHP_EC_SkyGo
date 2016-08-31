<?php

/**
 * Class
 *
 */

require_once( dirname(__FILE__) . "/GroupBuyingActivityState.php" );
require_once( FRAMEWORK_PATH . "/extends/ValidatorHelper.php" );

class WaitingDeliveryGroupBuyingState extends GroupBuyingActivityState {

    /**
     * Get the state's attribute's for update activity state.
     *
     * @return array attributes
     */
    public function getChangeAttributes() {
        $message = get_class($this) . " can't get change attributes. This mean the state only changed automatically.";
        throw new OperationConflictException($message);
    }

    /**
     * Check can export delivery list.
     *
     * @return bool If can change to the state, return true.
     */
    public function canExportDeliveryList() {
        return true;
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
     * Check can change to next state.
     *
     * @param GroupBuyingActivityState $state
     * @return bool If can change to the state, return true.
     */
    public function canChangeState(GroupBuyingActivityState $state) {

        return false;
    }

    /**
     * Check the order value is match the state.
     *
     * @params $record array The record of GroupBuyingActivity.
     * @return bool If match the state define, return true.
     */
    public function isMatch($record=array()) {
        if(array_key_exists("state", $record) &&
            array_key_exists("start_date", $record) &&
            array_key_exists("end_date", $record) &&
            array_key_exists("buyer_counter", $record) &&
            array_key_exists("completed_delivery_counter", $record)) {

            $validator = new ValidatorHelper();
            $now = array(
                "date" => date('Y-m-d H:i:s')
            );

            if($record["state"] == $this->getStateCode() &&
                $validator->requireDateGreaterThan($now, "date", $record, "start_date", false) &&
                $validator->requireDateGreaterThan($now, "date", $record, "end_date", false) &&
                $record["buyer_counter"] > $record["completed_delivery_counter"]) {
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
        array_push($conditions, "$tableAlias.start_date<:lessStartDate");
        array_push($conditions, "$tableAlias.end_date<:greaterEndDate");
        array_push($conditions, "($tableAlias.buyer_counter>delivery_order.counter OR ($tableAlias.buyer_counter>0 AND delivery_order.counter IS NULL) )");

        $now = date('Y-m-d H:i:s');
        $params[':state'] = $this->getStateCode();
        $params[':lessStartDate'] = $now;
        $params[':greaterEndDate'] = $now;
    }

    /**
     * Get state code of the state.
     *
     * @return int
     */
    public function getStateCode() {
        return 0;
    }
}



?>