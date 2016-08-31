<?php
require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/GeneralActivityHasRelationProductDiscount.php' );

class GeneralActivityHasRelationProductDiscountCollection extends PermissionDbCollection {

    public function searchRecordsByActivityId($id, $pageNo, $pageSize) {
        $result = $this->getDefaultRecords($pageNo, $pageSize);
        $table = $this->getTable();
        $this->dao->fresh();
        $conditions = array('and','1=1');
        $params = array();

        $select = array(
            'gahr.id id',
            'gahr.activity_id activity_id',
            'gahr.relation_product_id relation_product_id',
            'gahr.price price',
            'wp.name productName',
            'wp.suggest_price suggestPrice',
            'wp.detail detail',
            'wp.explain_text explainText',
            'wp.cover_photo_img coverPhoto',
        );

        $this->dao->from("$table gahr");

        array_push($conditions, "gahr.activity_id=:activity_id");
        $params[":activity_id"] = $id;

        $this->dao->leftJoin("wholesale_product wp", "wp.id=gahr.relation_product_id");

        $this->dao->select($select);
        $this->dao->where($conditions, $params);

        $result['recordCount'] = intval($this->dao->queryCount());
        $result['totalRecord'] = $result['recordCount'];
        $result["totalPage"] = intval(ceil($result['totalRecord'] / $pageSize));
        $this->dao->paging($pageNo, $pageSize);
        $result["records"] = $this->dao->queryAll();

        return $result;
    }

    /* DbCollection abstract methods. */
    /**
     *	Get the entity table name.
     *	@return string
     */
    public function getTable() {
        return "general_activity_has_relation_product_discount";
    }

    /**
     * Get model class name.
     * @return string
     */
    public function getModelName() {
        return "GeneralActivityHasRelationProductDiscount";
    }

    /**
     *	Check attributes is valid.
     *	@param $attributes 	array Attributes want to checked.
     *	@return bool 		If valid return true.
     */
    public function validAttributes($attributes) {
        if(array_key_exists("id", $attributes)) {
            throw new Exception("Can't write the attribute 'id'.");
        }
        return true;
    }

    /**
     *	Get Primary key attribute name
     *	@return string
     */
    public function getPrimaryAttribute() {
        return "id";
    }
}

?>