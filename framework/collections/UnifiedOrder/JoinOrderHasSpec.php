<?php
require_once( dirname(__FILE__) . "/../JoinStatement.php" );

/**
 * Class SearchIds
 * Append activities id list.
 */
class JoinOrderHasSpec extends JoinStatement {
    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        $dao->leftJoin( 'order_has_spec ohs', 'ohs.order_id=uo.id');
        array_push($select, 'ohs.order_id order_id');
        array_push($select, 'ohs.spec_id spec_id');
        array_push($select, 'ohs.spec_amount spec_amount');
        array_push($select, 'ohs.unit_price spec_unit_price');
        array_push($select, 'ohs.total_price spec_total_price');
        array_push($select, 'ohs.fare spec_fare');
        array_push($select, 'ohs.fare_type spec_fare_type');
        array_push($select, 'ohs.other_cost spec_other_cost');
        array_push($select, 'ohs.cost_type spec_cost_type');
        array_push($select, 'ohs.discount spec_discount');
        array_push($select, 'ohs.discount_type spec_discount_type');
        array_push($select, 'ohs.activity_type spec_activity_type');
        array_push($select, 'ohs.activity_id spec_activity_id');
        array_push($select, 'ohs.product_number spec_product_number');
        array_push($select, 'ohs.status spec_status');
        array_push($select, 'ohs.delivery_datetime spec_delivery_datetime');
        array_push($select, 'ohs.id spec_id');


        array_push($conditions, 'ohs.spec_id IS NOT NULL');
    }
}
?>