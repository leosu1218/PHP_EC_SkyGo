<?php

/**
 * Class WarrantyPeriodGroupBuyingState
 *
 */

require_once( dirname(__FILE__) . "/GroupBuyingActivityState.php" );
require_once( FRAMEWORK_PATH . 'extends/DatetimeHelper.php' );

class WarrantyPeriodGroupBuyingState extends GroupBuyingActivityState {

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
        return false;
    }

    /**
     * Check can export returned list.
     *
     * @return bool If can change to the state, return true.
     */
    public function canExportReturnedList() {
        return true;
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
            array_key_exists("delivery_date", $record)) {

            $validator = new ValidatorHelper();
            $warranty = array(
                "date" => DatetimeHelper::beforeNow($this->getWarrantyDays() + $this->getDeliveryDays())
            );
            $delivery = array(
                "date" => DatetimeHelper::beforeNow($this->getDeliveryDays())
            );

            if($record["state"] == $this->getStateCode() &&
                $validator->requireDateGreaterThan($record, "delivery_date", $warranty, "date", false) &&
                $validator->requireDateGreaterThan($delivery, "date", $record, "delivery_date", false)) {
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
        array_push($conditions, "$tableAlias.delivery_date<=:delivery_date");
        array_push($conditions, "$tableAlias.delivery_date>=:warranty_date");

        $params[':state'] = $this->getStateCode();
        $params[':delivery_date'] = DatetimeHelper::beforeNow($this->getDeliveryDays());
        $params[':warranty_date'] = DatetimeHelper::beforeNow($this->getWarrantyDays() + $this->getDeliveryDays());
    }

    /**
     * Get state code of the state.
     *
     * @return int
     */
    public function getStateCode() {
        return 4;
    }
}



?>