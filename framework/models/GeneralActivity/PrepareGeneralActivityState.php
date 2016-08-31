<?php
require_once( dirname(__FILE__) . "/GeneralActivityState.php" );
require_once( FRAMEWORK_PATH . "/extends/ValidatorHelper.php" );

/**
 * Class PrepareGeneralActivityState
 */
class PrepareGeneralActivityState extends GeneralActivityState {

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
     * Check can change to next state.
     *
     * @param GroupBuyingActivityState $state
     * @return bool If can change to the state, return true.
     */
    public function canChangeState(GeneralActivityState $state) {
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
     * @params $record array The record of GroupBuyingActivity.
     * @return bool If match the state define, return true.
     */
    public function isMatch($record=array()) {
        if(array_key_exists("state", $record) &&
            array_key_exists("start_date", $record)) {

            $validator = new ValidatorHelper();
            $now = array(
                "date" => date('Y-m-d H:i:s')
            );

            if($record["state"] == $this->getStateCode() &&
                $validator->requireDateGreaterThan($record, "start_date", $now, "date", false)) {
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
        array_push($conditions, "$tableAlias.start_date>:greaterStartDate");
        $params[':state'] = $this->getStateCode();
        $params[':greaterStartDate'] = date('Y-m-d H:i:s');
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