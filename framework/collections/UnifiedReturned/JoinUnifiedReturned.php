<?php
require_once( dirname(__FILE__) . "/../JoinStatement.php" );

/**
 * Class SearchIds
 * Append activities id list.
 */
class JoinUnifiedReturned extends JoinStatement {
    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        $dao->rightJoin( 'unified_returned ur', 'ur.order_id=uo.id');
        array_push($select, 'ur.id ur_id');
        array_push($select, 'ur.state return_state');
        array_push($select, 'ur.receiver_name ur_receiver_name');
        array_push($select, 'ur.receiver_phone_number ur_receiver_phone_number');
        array_push($select, 'ur.receiver_address ur_receiver_address');
        array_push($select, 'ur.create_datetime ur_create_datetime');
        array_push($select, 'ur.close_datetime ur_close_datetime');
        array_push($select, 'ur.delivery_datetime ur_delivery_datetime');
        array_push($select, 'ur.delivery_channel ur_delivery_channel');
        array_push($select, 'ur.delivery_number ur_delivery_number');
        array_push($select, 'ur.remark  ur_remark');
    }
}
?>