<?php
require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );
require_once( FRAMEWORK_PATH . 'collections/EC/UnifiedDiscount.php' );

class GeneralActivityHasRelationProductDiscount extends PermissionDbModel implements UnifiedDiscount {


    /*  Methods of ECDiscount interface. */
    /**
     * Check the spec can use the discount
     * $spec array Format: {id:<int>, product_id:<int>, activity_id:<int>, amount:<int>}
     * return bool
     */
    public function canUsedToSpec($spec=array()) {
        if(array_key_exists("product_id", $spec)) {
            $record = $this->toRecord();
            return ($record["relation_product_id"] == $spec["product_id"]);
        }
        else {
            return false;
        }
    }

    /**
     * Get price for the spec that use the discount.
     * $spec array Format: {id:<int>, product_id:<int>, activity_id:<int>, amount:<int>}
     * return float
     */
    public function getPriceForSpec($spec=array()) {
        if($this->canUsedToSpec($spec)) {
            $record = $this->toRecord();
            return $record["price"];
        }
        else {
            throw new InvalidAccessParamsException("The spec can't use the discount.");
        }
    }

    /* 	Method of abstract class DbModel. */
    /**
     *	Get the entity table name.
     *
     *	@return string
     */
    public function getTable() {
        return "general_activity_has_relation_product_discount";
    }

    /**
     *	Check attributes is valid.
     *
     *	@param $attributes 	array Attributes want to checked.
     *	@return bool 		If valid return true.
     */
    public function validAttributes($attributes) {
        return true;
    }

    /**
     *	Get Primary key attribute name
     *
     *	@return string
     */
    public function getPrimaryAttribute() {
        return "id";
    }
}

?>