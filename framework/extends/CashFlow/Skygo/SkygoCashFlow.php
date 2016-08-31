<?php
require_once(dirname(__FILE__) . '/../CashFlow.php');
require_once(dirname(__FILE__) . '/GroupBuyingUserNotify.php');
require_once(dirname(__FILE__) . '/ReorderGroupBuyingUserNotify.php');

require_once(dirname(__FILE__) . '/GeneralUserNotify.php');
require_once(dirname(__FILE__) . '/ReorderGeneralUserNotify.php');

require_once(dirname(__FILE__) . '/NewebServiceProvider.php');
require_once(dirname(__FILE__) . '/NewebOtherServiceProvider.php');
require_once(dirname(__FILE__) . '/GeneralNewebServiceConfig.php');
require_once(dirname(__FILE__) . '/GroupBuyingNewebServiceConfig.php');
require_once(dirname(__FILE__) . '/StorepayServiceProvider.php');

require_once(dirname(__FILE__) . '/ReorderServiceProvider.php');


/**
 * Class SkygoCashFlow
 *
 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
class SkygoCashFlow implements CashFlow {

    const GROUPBUYING_NOTIFY            = "groupbuying";
    const GENERAL_NOTIFY                = "general";
    const REORDER_GROUPBUYING_NOTIFY    = "groupbuying";
    const REORDER_GENERAL_NOTIFY        = "general";
    const NEWEB_PROVIDER                = "neweb";
    const NEWEB_MMK_PROVIDER            = "MMK";
    const NEWEB_ATM_PROVIDER            = "ATM";
    const NEWEB_CS_PROVIDER             = "CS";
    const NEWEB_OTHER_PROVIDER          = "neweb-other";
    const REORDER_PROVIDER              = "reorder";
    const STORE_PAY__PROVIDER              = "store-pay";

    /**
     * Create service provider instance.
     * @param string $type
     * @return CashFlowServiceProvider
     */
    public function createProvider($type='', $activity='') {
        if($type == self::NEWEB_PROVIDER) {
            $config = null;
            if($activity == self::GROUPBUYING_NOTIFY) {
                $config = new GroupBuyingNewebServiceConfig();
            }
            else if($activity == self::GENERAL_NOTIFY) {
                $config = new GeneralNewebServiceConfig();
            }

            return new NewebServiceProvider($config);
        }
        else if($type == self::REORDER_PROVIDER) {
            return new ReorderServiceProvider();
        }
        else if( ($type == self::NEWEB_MMK_PROVIDER) ||
            ($type == self::NEWEB_ATM_PROVIDER) ||
            ($type == self::NEWEB_CS_PROVIDER) ||
            ($type == self::NEWEB_OTHER_PROVIDER) ) {
            return new NewebOtherServiceProvider();
        }
        else if($type == self::STORE_PAY__PROVIDER){
            return new StorepayServiceProvider();
        }
        else {
            throw new CashFlowException("Create CashFlowServiceProvider error. Invalid service provider type [$type]");
        }
    }

    /**
     * Create user notify instance.
     * @param string $type
     * @return CashFlowUserNotify
     */
    public function createUserNotify($type='') {
        if($type == self::GROUPBUYING_NOTIFY) {
            return new GroupBuyingUserNotify();
        }
        else if($type == self::GENERAL_NOTIFY) {
            return new GeneralUserNotify();
        }
        else {
            throw new CashFlowException("Create CashFlowUserNotify error. Invalid notify type [$type]");
        }
    }

    /**
     * Create reorder user notify instance.
     * @param string $type
     * @return CashFlowUserNotify
     */
    public function createReorderUserNotify($type='') {
        if($type == self::REORDER_GROUPBUYING_NOTIFY) {
            return new ReorderGroupBuyingUserNotify();
        }
        else if($type == self::REORDER_GENERAL_NOTIFY) {
            return new ReorderGeneralUserNotify();
        }
        else {
            throw new CashFlowException("Create CashFlowUserNotify error. Invalid notify type [$type]");
        }
    }
}

?>