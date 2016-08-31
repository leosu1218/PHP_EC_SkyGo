<?php
require_once(dirname(__FILE__) . '/GroupBuyingCartCollection.php');

/**
 * Class ReorderGroupbuyingCartCollection
 */
class ReorderGroupbuyingCartCollection extends GroupBuyingCartCollection {

    const UNIFIED_TYPE = "reorder_groupbuying";

    public function __construct($params=array(), &$dao=null) {
        $this->validator = new ValidatorHelper();
        $this->validator->requireAttribute("original", $params);
        parent::__construct($params, $dao);
    }

    /* Override GroupBuyingCartCollection method */
    /**
     * Check is valid activity record.
     * @param $record array The activity's record.
     * @throws InvalidAccessParamsException
     * @throws OperationConflictException
     */
    protected function activityRecordValidator($record) {
        if(count($record) == 0) {
            throw new OperationConflictException("Not exists the id.");
        }

        if($record["groupbuying"] == 0) {
            throw new InvalidAccessParamsException("The product was not active for groupbuying.");
        }

        if($record["stateText"] != GroupBuyingActivityCollection::WAITING_RETURNED_STATE) {
            throw new InvalidAccessParamsException("The activity is not waiting returned process state.");
        }
    }

    /* Override GroupBuyingCartCollection method */
    /**
     * Get unified order type.
     * @throws InvalidAccessParamsException
     */
    public function getUnifiedType() {
        return self::UNIFIED_TYPE;
    }

    /**
     * Get delivery datetime (optional default null)
     */
    public function getDeliveryDateTime() {
        return $this->params["original"]["delivery_datetime"];
    }

    /**
     * Get delivery chennal (optional default null)
     */
    public function getDeliveryChannel() {
        return $this->params["original"]["delivery_channel"];
    }

    /**
     * Get delivery number (optional default null)
     */
    public function getDeliveryNumber() {
        return $this->params["original"]["delivery_number"];
    }

    /**
     * Get how to process inventory flag.
     * @return mixed
     */
    public function getInventoryProcess() {
        return  1;
    }
}


?>