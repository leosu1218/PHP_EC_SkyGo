<?php
/**
*	GroupBuyingActivity code.
*
*	PHP version 5.3
*
*	@category Model
*	@package GroupBuyingMasterUser
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );
require_once( FRAMEWORK_PATH . 'extends/ValidatorHelper.php' );

require_once( FRAMEWORK_PATH . 'collections/GroupBuyingMasterUserHasProductGroupCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleMaterialsCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleProductCollection.php' );

require_once(dirname(__FILE__) . "/groupbuying/PrepareGroupBuyingState.php");
require_once(dirname(__FILE__) . "/groupbuying/StartedGroupBuyingState.php");
require_once(dirname(__FILE__) . "/groupbuying/WaitingDeliveryGroupBuyingState.php");
require_once(dirname(__FILE__) . "/groupbuying/DeliveryAllGroupBuyingState.php");
require_once(dirname(__FILE__) . "/groupbuying/DeliveryCompletedGroupBuyingState.php");
require_once(dirname(__FILE__) . "/groupbuying/WarrantyPeriodGroupBuyingState.php");
require_once(dirname(__FILE__) . "/groupbuying/WaitingReturnedGroupBuyingState.php");
require_once(dirname(__FILE__) . "/groupbuying/ReturnedAllGroupBuyingState.php");
require_once(dirname(__FILE__) . "/groupbuying/WaitingCheckStatementGroupBuyingState.php");
require_once(dirname(__FILE__) . "/groupbuying/ConfirmedStatementGroupBuyingState.php");
require_once(dirname(__FILE__) . "/groupbuying/AbnormalStatementGroupBuyingState.php");
require_once(dirname(__FILE__) . "/groupbuying/CompletedGroupBuyingState.php");


class GroupBuyingActivity extends PermissionDbModel {

	private $validator;
    const WARRANTY_DAY 	= 7;
    const DELIVERY_DAY 	= 3;
    const TYPE_NAME 	= 'groupbuying';

    public function __construct($id, &$dao=null) {
        parent::__construct($id, $dao);
        $this->validator 	= new ValidatorHelper();
    }

	/* 	Method of interface User. */
    /**
     * Get the entity table name.
     * @return string
     */
	public function getTable() {
		return "groupbuying_activity";
	}

    /**
     * Check attributes is valid.
     * @param array $attributes Attributes want to checked.
     * @return bool If valid return true.
     * @throws Exception
     */
	public function validAttributes($attributes) {
		if(array_key_exists("id", $attributes)) {
			throw new Exception("Can't write the attribute 'id'.");
		}
		return true;
	}

    /**
     * Get Primary key attribute name
     * @return string
     */
	public function getPrimaryAttribute() {
		return "id";
	}

    /**
     * @return array|mixed
     */
	public function getReference() {
   		return array(
   			"product_id"=>array(
   			    "product_id",
   				"WholesaleProduct",
   			),
   			"master_id"=>array(
   				"master_id",
   				"GroupBuyingMasterUser",
   			),
   			"id"=>array(
   				"id",
   				"GroupBuyingOrder",
   			),
   		);
   	}

}

?>