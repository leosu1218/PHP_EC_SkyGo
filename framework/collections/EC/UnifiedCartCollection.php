<?php
require_once(dirname(__FILE__) . '/UnifiedFare.php');
require_once(dirname(__FILE__) . '/UnifiedUserCollection.php');


/**
 * Class UnifiedCartCollection
 */
abstract class UnifiedCartCollection {

    protected $fare;
    protected $params;
    protected $userCollection = null;

    /**
     * Get unified order type.
     * @throws InvalidAccessParamsException
     * @return string
     */
    abstract public function getUnifiedType();

    /**
     * @return string
     */
    abstract public function getBuyerName();

    /**
     * @return string
     */
    abstract public function getBuyerPhone();

    /**
     * @return string
     */
    abstract public function getBuyerEmail();

    /**
     * Get receiver address.
     * @return string
     */
    abstract public function getReceiverAddress();

    /**
     * @return string
     */
    abstract public function getReceiverName();

    /**
     * @return string
     */
    abstract public function getReceiverPhone();

    /**
     * @return float
     */
    abstract public function getFinalTotalPrice();

    /**
     * @return float
     */
    abstract public function getProductPrice();

    /**
     * @return float
     */
    abstract public function getOtherCost();

    /**
     * @return string
     */
    abstract public function getCostType();

    /**
     * @return float
     */
    abstract public function getFare();

    /**
     * @return string
     */
    abstract public function getFareType();

    /**
     * @return float
     */
    abstract public function getDiscount();

    /**
     * @return string
     */
    abstract public function getDiscountType();

    /**
     * @return string
     */
    abstract public function getPaymentType();

    /**
     * Get spec records.
     * @return array
     */
    abstract public function getSpecs();

    /**
     * Get the cart's activity's id
     * @return string
     */
    abstract public function getActivityId();

    /**
     * Get order's serial number.
     * @return string
     */
    abstract public function getSerial();

    /**
     * Get notify payment result's datetime
     */
    public function getNotifyDatetime() {
        return NULL;
    }

    /**
     * Get create date time.
     * @return string
     */
    public function getCreateDatetime() {
        return date("Y-m-d H:i:s");
    }

    /**
     * Get delivery datetime (optional default null)
     */
    public function getDeliveryDateTime() {
        return NULL;
    }

    /**
     * Get delivery chennal (optional default null)
     */
    public function getDeliveryChannel() {
        return NULL;
    }

    /**
     * Get delivery number (optional default null)
     */
    public function getDeliveryNumber() {
        return NULL;
    }

    /**
     * Get close datetime (optional default null)
     */
    public function getCloseDateTime() {
        return NULL;
    }

    /**
     * Get product ids;
     * @return array
     */
    abstract public function getProductIds();

    /**
     * Get fare's id
     * @return int
     */
    abstract public function getFareId();

    /**
     * Check fare is valid for product ids.
     * @param UnifiedFare $fare
     * @param $productIds
     * @throws ECException
     */
    public function validateFare(UnifiedFare $fare, $productIds) {
        foreach($productIds as $index => $productId) {
            if(!$fare->isUsedForProduct($productId)) {
                throw new ECException("Invalid use the fare on product[$productId] in cart.");
            }
        }
        return true;
    }

    /**
     * Append a new fare to cart.
     * @param UnifiedFare $fare
     */
    public function setFare(UnifiedFare $fare) {
        $this->validateFare($fare, $this->getProductIds());
        $this->emitSetFare($fare);
        $this->fare = $fare;
    }

    /**
     * Emit a new fare to cart event. (Observe pattern)
     * @param UnifiedFare $newFare
     */
    protected function emitSetFare(UnifiedFare $newFare) {
        $newFare->onAppendToCart($this);
    }

    /**
     * Get how to process inventory flag.
     * @return mixed
     */
    abstract public function getInventoryProcess();

    /**
     * @return mixed
     */
    abstract public function getCompanyName();

    /**
     * @return mixed
     */
    abstract public function getTaxID();

    /**
     * @return mixed
     */
    abstract public function getConsumerRemark();

    /**
     * Convert object's attributes to array.
     * @return array
     */
    public function toArray() {
        return array(
            'activity_id' 			=> $this->getActivityId(),
            'activity_type'         => $this->getUnifiedType(),

            'buyer_name' 			=> $this->getBuyerName(),
            'buyer_phone_number' 	=> $this->getBuyerPhone(),
            'buyer_email' 			=> $this->getBuyerEmail(),
            'receiver_address' 		=> $this->getReceiverAddress(),

            'receiver_name' 		=> $this->getReceiverName(),
            'receiver_phone_number' => $this->getReceiverPhone(),

            'final_total_price' 	=> $this->getFinalTotalPrice(),
            'product_total_price' 	=> $this->getProductPrice(),
            'other_cost' 		    => $this->getOtherCost(),
            'cost_type' 		    => $this->getCostType(),
            'fare' 		            => $this->getFare(),
            'fare_id' 		        => $this->getFareId(),
            'fare_type' 		    => $this->getFareType(),
            'discount' 		        => $this->getDiscount(),
            'discount_type' 		=> $this->getDiscountType(),
            'payment_type' 		    => $this->getPaymentType(),

            'create_datetime' 		=> $this->getCreateDatetime(),
            'pay_notify_datetime'   => $this->getNotifyDatetime(),

            'delivery_datetime' 	=> $this->getDeliveryDateTime(),
            'delivery_channel' 		=> $this->getDeliveryChannel(),
            'delivery_number'       => $this->getDeliveryNumber(),
            'close_datetime' 		=> $this->getCloseDateTime(),
            'spec'                  => $this->getSpecs(),
            'inventory_process'     => $this->getInventoryProcess(),
            'companyName'     => $this->getCompanyName(),
            'taxID'     => $this->getTaxID()
        );    
    }

    /**
     * Get init params.
     * @return mixed
     */
    public function getParams() {
        return $this->params;
    }

    public function setUserCollection(UnifiedUserCollection &$user) {
        $this->userCollection = $user;
    }
}

?>