<?php
require_once(dirname(__FILE__) . '/GeneralCartCollection.php');

/**
 * Class ReorderGeneralCartCollection
 */
class ReorderGeneralCartCollection extends GeneralCartCollection {

    const UNIFIED_TYPE = "reorder_general";

    public function __construct($params=array(), &$dao=null) {
        $this->validator = new ValidatorHelper();
        $this->validator->requireAttribute("original", $params);
        parent::__construct($params, $dao);
    }

    /* Override GeneralCartCollection method */
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
        return  $this->params['inventoryProcess'];
    }

    /**
     * Get buyer phone number.
     * @return string
     */
    public function getBuyerPhone() {
        return $this->params["original"]["buyer_phone_number"];
    }

    /**
     * Get buyer email address.
     * @return string
     */
    public function getBuyerEmail() {
        return $this->params["original"]["buyer_email"];
    }

    /**
     * Get buyer name.
     * @return string
     */
    public function getBuyerName() {
        return $this->params["original"]["buyer_name"];
    }
}


?>