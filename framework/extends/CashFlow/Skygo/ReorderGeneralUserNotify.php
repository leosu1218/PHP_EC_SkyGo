<?php
require_once( FRAMEWORK_PATH . 'extends/MailHelper.php' );
require_once(dirname(__FILE__) . '/../CashFlowUserNotify.php');

/**
 * Class ReorderGeneralUserNotify
 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
class ReorderGeneralUserNotify implements CashFlowUserNotify {

    const RETURN_URL = 'www.109life.com/gb.html#!/helper/';

    /**
     *	Notify user that order trade result.
     *
     *	@param $info array The info for user notify
     */
    public function send($info=array()) {

        $returnUrl 		= self::RETURN_URL;
        $mail 			= $info["buyer_email"];
        $name 			= $info["buyer_name"];
        $date 			= $info["create_datetime"];
        $serial 		= $info["serial"];
        $specs	        = $info["specs"];

        $mailFrom   = "天GO系統通知";
        $subject 	= "天GO-購買成功通知";

        $text = "親愛的" . $name . "先生/小姐 您好:<br>";
        $text .= "<br>";
        $text .= "您的購買的訂單變更通知。<br>";
        $text .= "訂單編號：" . $serial . "<br>";
        $text .= "訂購日期：" . $date . "<br>";

        foreach($specs["records"] as $index => $spec) {
            $text .= "訂購商品：" . $spec["spec_name"] . "(" . $spec["spec_serial"] .")<br>";
            $text .= "訂購數量：" . $spec["spec_amount"] . "<br>";
            $text .= "<br>";
        }

//        $text .= "若是您對此次訂單或是貨品有任何問題，可以從此得到協助：";
//        $text .= "<a href='" . $returnUrl . $serial . "'>活動協助</a>";

        $mailHelper = new MailHelper();
        $mailHelper->sendText($subject, $mail, $name, $text, $mailFrom);
    }
}

?>