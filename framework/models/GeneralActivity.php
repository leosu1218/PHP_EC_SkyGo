<?php
require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );
require_once( FRAMEWORK_PATH . 'extends/ValidatorHelper.php' );

require_once( FRAMEWORK_PATH . 'collections/WholesaleMaterialsCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleProductCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/GeneralActivityHasRelationProductDiscountCollection.php' );

require_once(dirname(__FILE__) . "/GeneralActivity/PrepareGeneralActivityState.php");
require_once(dirname(__FILE__) . "/GeneralActivity/StartedGeneralActivityState.php");
require_once(dirname(__FILE__) . "/GeneralActivity/CompletedGeneralActivityState.php");

/**
 *	GeneralActivity code.
 *
 *	PHP version 5.3
 *
 *	@category Model
 *	@package GroupBuyingMasterUser
 *	@author Rex chen <rexchen@synctech.ebiz.tw>
 *	@copyright 2015 synctech.com
 */
class GeneralActivity extends PermissionDbModel {

    private $validator;
    const WARRANTY_DAY 	= 7;
    const DELIVERY_DAY 	= 3;
    const TYPE_NAME 	= 'general';

    public function __construct($id, &$dao=null) {
        parent::__construct($id, $dao);
        $this->validator 	= new ValidatorHelper();
    }

    /**
     * Get each relation product discount of the activity.
     * @return array(ECDiscount)
     */
    public function getDiscounts() {

        $discounts  = array();
        $collection = new GeneralActivityHasRelationProductDiscountCollection();
        $attribute  = array("activity_id" => $this->getId());
        $result     = $collection->getRecords($attribute, 1, 10000);

        foreach($result["records"] as $key => $record) {
            array_push($discounts, new GeneralActivityHasRelationProductDiscount($record["id"]));
        }

        return $discounts;
    }

    /* 	Method of interface User. */
    /**
     *	Get the entity table name.
     *
     *	@return string
     */
    public function getTable() {
        return "general_activity";
    }

    /**
     *	Check attributes is valid.
     *
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
     *
     *	@return string
     */
    public function getPrimaryAttribute() {
        return "id";
    }
}

?>