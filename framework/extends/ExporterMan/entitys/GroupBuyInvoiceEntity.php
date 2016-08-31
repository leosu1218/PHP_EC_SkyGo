<?php

require_once( dirname(__FILE__) . '/ExportEntity.php' );

require_once( FRAMEWORK_PATH . 'collections/GroupBuyingActivityCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/UnifiedOrderCollection.php' );



class GroupBuyInvoiceEntity implements ExportEntity
{
	
	function __construct()
    {
        $this->records = array();
    }

    public function setResource( $ids )
    {
        $activity_ids = $this->checkActivitysStateByActiveIds( $ids );
        $result = $this->getOrderRecordsByActivityIds( $activity_ids );

        $this->preparePushDataWaitting( $result );
    }

    public function getOrderRecordsByActivityIds($ids)
    {
        $collection = new UnifiedOrderCollection();   
        $result = $collection->searchSpecRecords(1, 9999, array("activityIds"=>$ids, "activityType"=>GroupBuyingActivity::TYPE_NAME, "state"=>UnifiedOrderCollection::COMPLETED_ORDER_STATE));
        $records = $result["records"];
        return $records;
    }

	public function checkActivitysStateByActiveIds( $ids )
	{
		$collection = new GroupBuyingActivityCollection();

		$result = $collection->searchRecords(1, 9999, array("ids" => $ids));
        $records = $result["records"];

        $canExportRecord = array();
        foreach($records as $index => $record)
        {
            $recordState = $collection->getState($record["stateText"]);
            
            if($recordState->canExportStatementList())
            {
                array_push($canExportRecord, $record["id"]);
            }
            else
            {
            	$id = $record["id"];
                throw new OperationConflictException("Conflict change activity records[$id] to state[$stateText].");
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
            "master_bank_account",
            "master_bank_code",
        );
    }

    //save data in $this->records
    private function preparePushDataWaitting( $records )
    {
        $result = array( 'fields'=>array(), 'records'=>$records );
        
        foreach ($records as $key => $record) {
            
            foreach ($record as $field => $value) {
                
                if( !in_array($field, $result["fields"]) )
                {
                    array_push($result["fields"], $field);
                }

                if( in_array($field, $this->hasToStringFields()) )
                {
                    $result['records'][ $key ][ $field ] = $value . " ";
                }

            }
            
        }
        array_push($this->records, $result);
    }

}


?>