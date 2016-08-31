<?php
require_once( dirname(__FILE__) . "/NewebOtherServiceConfig.php");

/**
 * Class GeneralNewebOtherServiceConfig
 *
 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
class GeneralNewebOtherServiceConfig implements NewebOtherServiceConfig {

    public $MERCHANT_NUMBER 	= '460453';
    public $HASH_SALT 		    = 'nwcu8n5z';
    public $BANK_ID             = '007';        //第一銀行
    public $DUE_DAYS            = 7;
    public $RETURN_URL		    = 'http://www.109life.com';

    //server received
    public $LOG_PREFIX		    = 'trade';
    public $LOG_PATH			= TRADE_LOG_PATH;
    public $PROVIDER_URL 		= 'https://aquarius.neweb.com.tw/CashSystemFrontEnd/Payment';

    public function MERCHANT_NUMBER() {
        return $this->MERCHANT_NUMBER;
    }

    public function HASH_SALT() {
        return $this->HASH_SALT;
    }

    public function BANK_ID() {
        return $this->BANK_ID;
    }

    public function DUE_DAYS() {
        return $this->DUE_DAYS;
    }

    //user received
    public function RETURN_URL() {
        return $this->RETURN_URL;
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