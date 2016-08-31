<?php

/**
 * Class WarrantyPeriodOrderState
 *
 */

require_once( dirname(__FILE__) . "/UnifiedOrderState.php" );

class WarrantyPeriodOrderState implements UnifiedOrderState {

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
        return true;
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
            if($record["state"] == $this->getStateCode()
                && $record["delivery_datetime"] != NULL
            ){

                $validator = new ValidatorHelper();
                $delivery = array(
                    "date" => DatetimeHelper::beforeNow(UnifiedOrder::DELIVERY_DAY)
                );
                $warranty = array(
                    "date" => DatetimeHelper::beforeNow(UnifiedOrder::WARRANTY_DAY + UnifiedOrder::DELIVERY_DAY)
                );

                $canException = false;
                if($validator->requireDateGreaterThan($record, "delivery_datetime", $warranty, "date", $canException)
                    && $validator->requireDateGreaterThan($delivery, "date", $record, "delivery_datetime", $canException)) {
                    return true;
                }
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
        array_push($conditions, "$tableAlias.delivery_datetime IS NOT NULL");
        array_push($conditions, "$tableAlias.delivery_datetime<=:delivery_date");
        array_push($conditions, "$tableAlias.delivery_datetime>=:warranty_date");

        $params[':state'] = $this->getStateCode();
        $params[':delivery_date'] = DatetimeHelper::beforeNow(UnifiedOrder::DELIVERY_DAY);
        $params[':warranty_date'] = DatetimeHelper::beforeNow(UnifiedOrder::WARRANTY_DAY + UnifiedOrder::DELIVERY_DAY);
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