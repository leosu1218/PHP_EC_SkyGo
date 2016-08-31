<?php
require_once( dirname(__FILE__) . "/../JoinStatement.php" );

/**
 * Class JoinWholesaleProductWithGeneralActivity
 */
class JoinWholesaleProductWithGeneralActivity extends JoinStatement {
    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        $dao->leftJoin('wholesale_product wp', 'wp.id=act.product_id');
        array_push($select, 'wp.name productName');
        array_push($select, 'wp.suggest_price suggestPrice');
        array_push($select, 'wp.propose_price proposePrice');
        array_push($select, 'wp.cost_price costPrice');
        array_push($select, 'wp.detail detail');
        array_push($select, 'wp.cover_photo_img coverPhoto');
        array_push($select, 'wp.explain_text explainText');
        array_push($select, 'wp.active_groupbuying groupbuying');
        array_push($select, 'wp.youtube_url youtubeUrl');
        array_push($select, 'wp.media_type mediaType');
        array_push($select, 'wp.id productId');
        array_push($select, 'wp.weight weight');

        if(array_key_exists('actor', $search)) {
            if($search['actor'] == 'admin') {
                array_push($select, 'wp.wholesale_price wholesalePrice');
            }
        }
    }
}
?>