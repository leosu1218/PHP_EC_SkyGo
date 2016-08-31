<?php

require_once( dirname(__FILE__) . '/ExportEntity.php' );

require_once( FRAMEWORK_PATH . 'collections/GroupBuyingActivityCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/UnifiedOrderCollection.php' );



class GernalReimburseEntity implements ExportEntity
{

    function __construct()
    {
        $this->records = array();
    }

    public function setResource( $ids )
    {
        $result = $this->checkOrderIds($ids);
        $this->preparePushDataWaitting($result);
    }

    public function checkOrderIds($ids)
    {
        $collection = new UnifiedOrderCollection();
        $result = $collection->searchSpecRecords(1, 9999, array("ids"=>$ids,"activityType"=>"general" , "reimburse" =>"1"));
        $records = $result["records"];
        foreach ($records as $key => $record) {
            foreach ($record as $field => $value) {

                if($field == 'reimburse_state'){
                    if($value == 0){
                        $records[$key][$field] = "退款中";
                    }else{
                        $records[$key][$field] = "完成退款";
                    }
                }
                if ($field == 'stateText') {
                    switch ($value) {
                        case 'prepared':
                            $records[$key][$field] = "尚未付款";
                            break;
                        case 'abnormal':
                            $records[$key][$field] = "付款失敗";
                            break;
                        case 'paid':
                            $records[$key][$field] = "等待出貨(付款成功)";
                            break;
                        case 'delivering':
                            $records[$key][$field] = "已出貨(未到貨)";
                            break;
                        case 'warrantyperiod':
                            $records[$key][$field] = "鑑賞期(已到貨未滿7日)";
                            break;
                        case 'completed':
                            $records[$key][$field] = "已完成(超過鑑賞期)";
                            break;
                        case 'applycancel':
                            $records[$key][$field] = "已完成(超過鑑賞期)";
                            break;
                        case 'cancel':
                            $records[$key][$field] = "訂單已取消";
                            break;
                        case 'returned':
                            $records[$key][$field] = "訂單已退貨";
                            break;
                    }
                }
                if ($field == 'payment_type') {
                    switch ($value) {
                        case 'neweb':
                            $records[$key][$field] = "信用卡付款";
                            break;
                        case 'MMK':
                            $records[$key][$field] = "超商付款";
                            break;
                        case 'ATM':
                            $records[$key][$field] = "ATM轉帳付款";
                            break;
                    }
                }
            }
        }
        return $records;
    }

    //get records method
    public function getRecords()
    {
        return $this->records;
    }

    //define phone number to string
    public function hasToStringFields()
    {
        return array(
            "buyer_phone_number",
            "receiver_phone_number",
        );
    }

    //save data in $this->records
    private function preparePushDataWaitting( $records )
    {
        $result = array( 'fields'=>array(
            "reimburse_serial",
            "reimburse_create_datetime",
            "stateText",
            "serial",
            "consumer_user_id",
            "buyer_name",
            "spec_serial",
            "product_name",
            "spec_name",
            "spec_amount",
            "spec_total_price",
            "fare",
            "reimburse_money",
            "payment_type",
            "create_datetime",
            "pay_notify_datetime",
            "reimburse_name",
            "reimburse_bank_branch",
            "reimburse_account",
            "reimburse_datetime",
            "reimburse_state"),
            'records'=>$records );

//        foreach ($records as $key => $record) {
//
//            foreach ($record as $field => $value) {
//
//                if( !in_array($field, $result["fields"]) )
//                {
//                    array_push($result["fields"], $field);
//                }
//
//                if( in_array($field, $this->hasToStringFields()) )
//                {
//                    $result['records'][ $key ][ $field ] = $value . " ";
//                }
//
//            }
//
//        }
        array_push($this->records, $result);
    }

}


?>