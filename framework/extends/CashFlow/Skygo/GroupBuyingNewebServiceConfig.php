<?php
require_once( dirname(__FILE__) . "/NewebServiceConfig.php");

/**
 * Class GroupBuyingNewebServiceConfig
 *
 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
class GroupBuyingNewebServiceConfig implements NewebServiceConfig {

    public $MERCHANT_NUMBER 	= '760839';
    public $HASH_SALT 		    = 'z9wwp2fm';
    public $APPROVE_FLAG 		= '1';
    public $DEPOSIT_FLAG		= '1';
    public $ENGLISH_MODE		= '0';
    public $IPHONE_PAGE_FLAG	= '1';

    //user received
    public $RETURN_URL		    = 'http://www.109life.com/receive.php';
    //server received
    public $NOTIFY_URL		    = 'http://www.109life.com/api/order/groupbuying/notify/neweb';
    public $LOG_PREFIX		    = 'trade';
    public $LOG_PATH			= TRADE_LOG_PATH;
    public $PROVIDER_URL 		= 'https://taurus.neweb.com.tw/NewebmPP/cdcard.jsp';

    public function MERCHANT_NUMBER() {
        return $this->MERCHANT_NUMBER;
    }

    public function HASH_SALT() {
        return $this->HASH_SALT;
    }

    public function APPROVE_FLAG() {
        return $this->APPROVE_FLAG;
    }

    public function DEPOSIT_FLAG() {
        return $this->DEPOSIT_FLAG;
    }

    public function ENGLISH_MODE() {
        return $this->ENGLISH_MODE;
    }

    public function IPHONE_PAGE_FLAG() {
        return $this->IPHONE_PAGE_FLAG;
    }

    //user received
    public function RETURN_URL() {
        return $this->RETURN_URL;
    }

    //server received
    public function NOTIFY_URL() {
        return $this->NOTIFY_URL;
    }

    public function LOG_PREFIX() {
        return $this->LOG_PREFIX;
    }

    public function LOG_PATH() {
        return $this->LOG_PATH;
    }

    public function PROVIDER_URL() {
        return $this->PROVIDER_URL;
    }
}




?>