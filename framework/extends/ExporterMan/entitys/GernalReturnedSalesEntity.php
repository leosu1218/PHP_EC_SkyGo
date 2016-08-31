<?php

require_once( dirname(__FILE__) . '/ExportEntity.php' );

// require_once( FRAMEWORK_PATH . 'collections/GroupBuyingActivityCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/UnifiedReturnedCollection.php' );



class GernalReturnedSalesEntity implements ExportEntity
{
	
	function __construct()
    {
        $this->records = array();
    }

    public function setResource( $ids )
    {
        
        $records = $this->getReturnedRecordsByIds( $ids );

        $this->preparePushDataWaitting($records);
    }

    public function getReturnedRecordsByIds( $ids )
    {
        $collection = new UnifiedReturnedCollection();
        $result = $collection->searchSpecRecords(1, 9999, array("ids"=>$ids,"activityType"=>"general"));
        $records = $result["records"];
        foreach ($records as $key => $record) {
            foreach ($record as $field => $value) {
                if ($field == 'stateText') {
                    switch ($value) {
                        case 'prepared':
                            $records[$key][$field] = "退貨處理中";
                            break;
                        case 'receiving':
                            $records[$key][$field] = "等待貨物回收";
                            break;
                        case 'cancel':
                            $records[$key][$field] = "已取消退貨";
                            break;
                        case 'completed':
                            $records[$key][$field] = "退貨完成";
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
            "ur_receiver_phone_number",
        );
    }

    //save data in $this->records
    private function preparePushDataWaitting( $records )
    {
        $result = array( 'fields'=>array(
            "ur_id",
            "serial",
            "consumer_user_id",
            "buyer_name",
            "spec_serial",
            "product_name",
            "spec_name",
            "spec_amount",
            "product_total_price",
            "fare",
            "ur_create_datetime",
            "remark",
            "stateText",
            "ur_delivery_datetime",
            "receiver_name",
            "receiver_phone_number",
            "receiver_address",
            "delivery_datetime",
            "delivery_number,
            ur_remark",
            "ur_close_datetime"),
            'records'=>$records );

//        $result = array( 'fields'=>array(), 'records'=>$records );
//
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