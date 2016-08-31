<?php
require_once( dirname(__FILE__) . "/../JoinStatement.php" );

/**
 * Class SearchIds
 * Append activities id list.
 */
class JoinReimburse extends JoinStatement {
    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {

        if(array_key_exists("reimburse", $search)) {
            if($search["reimburse"] == 1) {
                $dao->leftJoin( 'reimburse re', 're.order_id=uo.id');
                    array_push($select, 're.id reimburse_id');
                    array_push($select, 're.reimburse_serial reimburse_serial');
                    array_push($select, 're.buy_name reimburse_buy_name');
                    array_push($select, 're.payment_type reimburse_payment_type');
                    array_push($select, 're.reimburse_name reimburse_name');
                    array_push($select, 're.reimburse_bank reimburse_bank');
                    array_push($select, 're.reimburse_bank_branch reimburse_bank_branch');
                    array_push($select, 're.reimburse_account reimburse_account');
                    array_push($select, 're.reimburse_money reimburse_money');
                    array_push($select, 're.create_datetime reimburse_create_datetime');
                    array_push($select, 're.order_datetime reimburse_order_datetime');
                    array_push($select, 're.pay_datetime reimburse_pay_datetime');
                    array_push($select, 're.state reimburse_state');
                    array_push($select, 're.remark reimburse_remark');
                    array_push($select, 're.consumer_user_id reimburse_consumer_user_id');
                    array_push($select, 're.order_serial reimburse_order_serial');
                    array_push($select, 're.reimburse_datetime reimburse_datetime');
            }
        }


        
    }
}
?>