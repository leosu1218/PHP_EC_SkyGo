<?php

require_once( dirname(__FILE__) . '/ExportEntity.php' );

require_once( FRAMEWORK_PATH . 'collections/GroupBuyingActivityCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/UnifiedOrderCollection.php' );



class GernalPickupEntity implements ExportEntity
{
	
	function __construct()
    {
        $this->records = array();
    }

    public function setResource( $ids )
    {
        $result = $this->checkOrderIds( $ids, UnifiedOrderCollection::PAID_ORDER_STATE );
        $this->preparePushDataWaitting( $result );
    }

    public function checkOrderIds($ids, $stateText)
    {
        $collection = new UnifiedOrderCollection();   
        $result = $collection->searchSpecRecords(1, 9999, array("ids"=>$ids,"activityType"=>"general"));
        $records = $result["records"];

        $canExportRecord = array();
        foreach($records as $index => $record)
        {
            $recordState = $collection->getState($record["stateText"]);
            
            if($recordState->canExportDeliveryList())
            {
                array_push($canExportRecord, $record);
            }
            else
            {
                $id = $record["id"];
                throw new OperationConflictException("Conflict change order records[$id] to state[$stateText].");
            }
        }

        foreach ($canExportRecord as $key => $record) {
            foreach ($record as $field => $value) {
                if ($field == 'stateText') {
                    switch ($value) {
                        case 'prepared':
                            $canExportRecord[$key][$field] = "尚未付款";
                            break;
                        case 'abnormal':
                            $canExportRecord[$key][$field] = "付款失敗";
                            break;
                        case 'paid':
                            $canExportRecord[$key][$field] = "等待出貨(付款成功)";
                            break;
                        case 'delivering':
                            $canExportRecord[$key][$field] = "已出貨(未到貨)";
                            break;
                        case 'warrantyperiod':
                            $canExportRecord[$key][$field] = "鑑賞期(已到貨未滿7日)";
                            break;
                        case 'applycancel':
                            $canExportRecord[$key][$field] = "已完成(超過鑑賞期)";
                            break;
                        case 'cancel':
                            $canExportRecord[$key][$field] = "訂單已取消";
                            break;
                        case 'returned':
                            $canExportRecord[$key][$field] = "訂單已退貨";
                            break;
                    }
                }
                if ($field == 'inventory_process') {
                    switch ($value) {
                        case 1:
                            $canExportRecord[$key][$field] = "捐贈發票";
                            break;
                        case 2:
                            $canExportRecord[$key][$field] = "二聯式發票";
                            break;
                        case 3:
                            $canExportRecord[$key][$field] = "三聯式發票";
                            break;
                    }
                }
            }
        }

        return $canExportRecord;
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
            "serial",
            "consumer_user_id",
            "buyer_name",
            "spec_serial",
            "product_name",
            "spec_name",
            "spec_amount",
            "product_total_price",
            "fare",
            "final_total_price",
            "consumer_remark",
            "payment_type",
            "create_datetime",
            "pay_notify_datetime",
            "stateText",
            "receiver_name",
            "receiver_phone_number",
            "receiver_address",
            "delivery_datetime",
            "delivery_number,
            inventory_process"),
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