<?php

/**
 * Interface UnifiedOrderState
 * Define Unified order state methods.
 *
 */

require_once( FRAMEWORK_PATH . 'extends/DbHero/DbHero.php' );

interface UnifiedOrderState {

    /**
     * Get the state's attribute's for update activity state.
     *
     * @return array attributes
     */
    public function getChangeAttributes();

    /**
     * Check can change to next state.
     *
     * @param UnifiedOrderState $state
     * @return bool If can change to the state, return true.
     */
    public function canChangeState(UnifiedOrderState $state);

    /**
     * Check can apply returned.
     *
     * @param mixed $applicant
     * @return bool If can apply returned, return true.
     */
    public function canApplyReturned($applicant);

    /**
     * Check can export delivery list.
     *
     * @return bool If can change to the state, return true.
     */
    public function canExportDeliveryList();

    /**
     * Check can export returned list.
     *
     * @return bool If can change to the state, return true.
     */
    public function canExportReturnedList();

    /**
     * Check can export statement list.
     *
     * @return bool If can change to the state, return true.
     */
    public function canExportStatementList();

    /**
     * Check the order value is match the state.
     *
     * @param UnifiedOrderState $state
     * @return bool If match the state define, return true.
     */
    public function isMatch($record=array());

    /**
     * Set Db query statement condition.
     *
     * @param $dao DbHero The Db data access object of GroupBuyingActivity collection or model.
     * @param $conditions array Query condition's object reference of the state.
     * @param $params array Query params object reference of the state.
     * @param $tableAlias string Table alias name for query statement.
     */
    public function setDbCondition(DbHero &$dao, &$conditions, &$params, $tableAlias);

    /**
     * Get state code of the state.
     *
     * @return int
     */
    public function getStateCode();
}



?>