<?php
require_once( dirname(__FILE__) . "/NewebServiceConfig.php");

/**
 * Class GeneralNewebServiceConfig
 *
 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
class GeneralNewebServiceConfig implements NewebServiceConfig {

    public $MERCHANT_NUMBER 	= '760839';
    public $HASH_SALT 		    = 'z9wwp2fm';
    public $APPROVE_FLAG 		= '1';
    public $DEPOSIT_FLAG		= '1';
    public $ENGLISH_MODE		= '0';
    public $IPHONE_PAGE_FLAG	= '0';

    //user received
    public $RETURN_URL		    = 'http://www.109life.com/neweb_received_general.php';
    //server received
    public $NOTIFY_URL		    = 'http://www.109life.com/api/order/general/notify/neweb';
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
        if($this->check_mobile()){
            $this->IPHONE_PAGE_FLAG = 1;
        }
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

    public function check_mobile(){
        $regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
        $regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
        $regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
        $regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
        $regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
        $regex_match.=")/i";
        return preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
    }
}




?>