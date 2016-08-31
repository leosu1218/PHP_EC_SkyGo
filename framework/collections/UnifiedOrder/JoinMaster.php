<?php
require_once( dirname(__FILE__) . "/../JoinStatement.php" );

/**
 * Class SearchIds
 * Append activities id list.
 */
class JoinMaster extends JoinStatement {
    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        if(array_key_exists("activityType", $search)) {

            if($search["activityType"] == GroupBuyingActivity::TYPE_NAME) {
                $dao->leftJoin( 'gb_master_user gbmu', 'gbmu.id=ga.master_id');
                array_push($select, 'gbmu.name master_name');
                array_push($select, 'gbmu.bank_name master_bank_name');
                array_push($select, 'gbmu.bank_code master_bank_code');
                array_push($select, 'gbmu.bank_account master_bank_account');
                array_push($select, 'gbmu.bank_account_name master_bank_account_name');

                if( array_key_exists("masterId", $search) ) {
                    array_push($conditions, 'gbmu.id=:masterId');
                    $params[':masterId'] = $search['masterId'];
                }
            }

            if($search["activityType"] == GeneralActivity::TYPE_NAME) {
                $dao->leftJoin( 'platform_user gbmu', 'gbmu.id=ga.master_id');
                array_push($select, 'gbmu.name master_name');

                if( array_key_exists("masterId", $search) ) {
                    array_push($conditions, 'gbmu.id=:masterId');
                    $params[':masterId'] = $search['masterId'];
                }
            }
        }
    }
}
?>