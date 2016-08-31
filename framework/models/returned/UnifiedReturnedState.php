<?php

/**
 * Interface UnifiedReturnedState
 * Define Unified returned state methods.
 *
 */

require_once( FRAMEWORK_PATH . 'extends/DbHero/DbHero.php' );

interface UnifiedReturnedState {

    /**
     * Get the state's attribute's for update record state.
     *
     * @return array attributes
     */
    public function getChangeAttributes();

    /**
     * Check can change to next state.
     *
     * @param UnifiedReturnedState $state
     * @return bool If can change to the state, return true.
     */
    public function canChangeState(UnifiedReturnedState $state);

    /**
     * Check the order value is match the state.
     *
     * @param UnifiedReturnedState $state
     * @return bool If match the state define, return true.
     */
    public function isMatch($record=array());

    /**
     * Set Db query statement condition.
     *
     * @param $dao
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