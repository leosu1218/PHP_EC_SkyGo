<?php
require_once( FRAMEWORK_PATH . 'extends/MailHelper.php' );
require_once(dirname(__FILE__) . '/../CashFlowUserNotify.php');

/**
 * Class GeneralUserNotify

 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */

class GeneralUserNotify implements CashFlowUserNotify {

    const RETURN_URL = 'www.109life.com/gb.html#!/helper/';

    /**
     *	Notify user that order trade result.
     *
     *	@param $info array The info for user notify
     *      {
                "id":"27",
                "activity_id":"0",
                "activity_type":"general",
                "consumer_user_id":"9",
                "buyer_name":"rex",
                "buyer_phone_number":"0972831644",
                "buyer_email":"chen.cyr@gmail.com",
                "product_total_price":"8900",
                "final_total_price":"8900",
                "other_cost":"0",
                "cost_type":"normal",
                "fare":"0",
                "fare_type":"宅配",
                "fare_id":"2",
                "discount":"0",
                "discount_type":"normal",
                "payment_type":"0",
                "receiver_address":"test",
                "receiver_name":"rex",
                "receiver_phone_number":"0972831644",
                "state":"0",
                "create_datetime":"2015-11-25 00:42:48",
                "pay_notify_datetime":null,
                "serial":"20151125w616F",
                "delivery_datetime":null,
                "delivery_channel":null,
                "delivery_number":null,
                "close_datetime":null,
                "user_name":"rex",
                "user_account":"chen.cyr@gmail.com2",
                "user_email":"chen.cyr@gmail.com2",
                "stateText":"prepared",
                "specs":{
                "records":[
                    {
                        "id":"27",
                        "activity_id":"0",
                        "activity_type":"general",
                        "consumer_user_id":"9",
                        "buyer_name":"rex",
                        "buyer_phone_number":"0972831644",
                        "buyer_email":"chen.cyr@gmail.com",
                        "product_total_price":"8900",
                        "final_total_price":"8900",
                        "other_cost":"0",
                        "cost_type":"normal",
                        "fare":"0",
                        "fare_type":"宅配",
                        "fare_id":"2",
                        "discount":"0",
                        "discount_type":"normal",
                        "payment_type":"0",
                        "receiver_address":"test",
                        "receiver_name":"rex",
                        "receiver_phone_number":"0972831644",
                        "state":"0",
                        "create_datetime":"2015-11-25 00:42:48",
                        "pay_notify_datetime":null,
                        "serial":"20151125w616F",
                        "delivery_datetime":null,
                        "delivery_channel":null,
                        "delivery_number":null,
                        "close_datetime":null,
                        "order_id":"27",
                        "spec_id":"1",
                        "spec_amount":"1",
                        "spec_unit_price":"8900",
                        "spec_total_price":"8900",
                        "spec_fare":"0",
                        "spec_fare_type":"宅配",
                        "spec_other_cost":"0",
                        "spec_cost_type":"normal",
                        "spec_discount":"0",
                        "spec_discount_type":"normal",
                        "spec_activity_type":"general",
                        "spec_activity_id":"1",
                        "spec_name":"32G 玫瑰金",
                        "spec_product_id":"2",
                        "spec_serial":"RX2245",
                        "product_id":"2",
                        "product_name":"iPhone 6S",
                        "weight":"0",
                        "user_name":"rex",
                        "user_account":"chen.cyr@gmail.com2",
                        "user_email":"chen.cyr@gmail.com2",
                        "stateText":"prepared"
                    }
                ],
                    "pageNo":1,
                    "pageSize":10000,
                    "totalPage":1,
                    "recordCount":1
                }
            }
     */
    public function send($info=array()) {

        $returnUrl 		= self::RETURN_URL;
        $mail 			= $info["buyer_email"];
        $name 			= $info["buyer_name"];
        $date 			= $info["create_datetime"];
        $serial 		= $info["serial"];
        $specs	        = $info["specs"];
        $product        = $specs["records"][0]['product_name'];
        $price          = $info["final_total_price"];

        $mailFrom   = "天GO系統通知";
        $subject 	= "天GO-購買成功通知";

        $text = "親愛的" . $name . "先生/小姐 您好:<br>";
        $text .= "<br>";
        $text .= "感謝您對天GO的熱愛與支持！<br><br>";
        $text .= "訂單編號：" . $serial . "<br>";
        $text .= "訂購商品：" . $product . "<br>";

        foreach($specs["records"] as $index => $spec) {
            $text .= "商品品項：" . $spec["spec_name"] . "<br>";
            $text .= "訂購數量：" . $spec["spec_amount"] . "<br>";
        }
        $text .= "實付金額：" . $price . "<br><br>";

        $text .= "我們已收到您的訂單，會盡快為您安排商品配送，謝謝！<br><br>※ 為了保護您的個人訂購安全，通知信不顯示訂單明細，詳情可前往訂單查詢。<br>※ 提醒您！收到商品7天鑑賞期內，鑑賞期非試用期，請保留商品本身完整性，以保障您的退換貨權益。<br>※ 為保障您的訂購權益，若您欲辦理商品退、換貨或取消訂單等服務，請您直接使用天GO線上客服功能，謝謝。<br><br>【防詐騙提醒】<br>提醒您！天GO 絕對不會誤設成分期付款或通知您變更付款條件，更不會在電話中要求您提供信用卡帳號資料、銀行帳號，或主動提供您銀行/信用卡客服電話。請提高警覺勿上當！<br>";
        $text .= "===============================================================<br>";
        $text .= "此為系統自動發信，請勿直接回覆，若您對訂單有任何問題，請聯絡天GO客服，我們將以最快的時間回覆。<br>";
        $text .= "===============================================================<br>";
//
//        $text .= "若是您對此次購物或是貨品有任何問題，可以從此得到協助：";
//        $text .= "<a href='" . $returnUrl . $serial . "'>活動協助</a>";

        $mailHelper = new MailHelper();
        $mailHelper->sendText($subject, $mail, $name, $text, $mailFrom);
    }
}



?>