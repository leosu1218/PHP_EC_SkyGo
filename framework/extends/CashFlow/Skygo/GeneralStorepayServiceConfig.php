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
class GeneralStorepayServiceConfig {

    public $SU_ID 	 	= 'service@109life.com';


    //user received
    public $RETURN_URL		    = 'http://www.109life.com/neweb_received_general.php';
    //server received
    public $NOTIFY_URL		    = 'http://www.109life.com/api/order/general/notify/neweb';
    public $LOG_PREFIX		    = 'trade';
    public $LOG_PATH			= TRADE_LOG_PATH;
    public $PROVIDER_URL 		= 'http://www.ezship.com.tw/emap/rv_request_web.jsp';

    public function SU_ID() {
        return $this->SU_ID;
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