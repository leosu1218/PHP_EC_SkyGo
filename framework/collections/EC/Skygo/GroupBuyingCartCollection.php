<?php
require_once(dirname(__FILE__) . '/../UnifiedCartCollection.php');
require_once( FRAMEWORK_PATH . 'collections/GroupBuyingActivityCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/FareCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/ValidatorHelper.php' );
require_once( FRAMEWORK_PATH . 'extends/AuthenticateHelper.php' );

/**
 * Class GroupBuyingCartCollection
 */
class GroupBuyingCartCollection extends UnifiedCartCollection {

    protected $dao;
    protected $params;
    protected $activityRecord;
    protected $activityCollection;

    protected $amount = 0;
    protected $unitPrice = 0;
    protected $productPrice = 0;

    public function __construct($params=array(), &$dao=null) {
        $this->validator = new ValidatorHelper();
        $this->validator->requireAttribute("activityId", $params);
        $this->validator->requireAttribute("name", $params);
        $this->validator->requireAttribute("phone", $params);
        $this->validator->requireAttribute("email", $params);
        $this->validator->requireAttribute("address", $params);
        $this->validator->requireAttribute("spec", $params);
        $this->validator->requireAttribute("fareId", $params);
        $this->params = $params;
        $this->dao = $dao;

        $this->activityRecord = array();
        $this->activityCollection = new GroupBuyingActivityCollection($this->dao);
        $this->queryActivity();

        $fareCollection = new FareCollection($this->dao);
        $fare = $fareCollection->getById($this->params["fareId"]);
        $this->setFare($fare);
    }

    /**
     * Get product ids;
     * @return array
     */
    public function getProductIds() {
        return array($this->activityRecord["productId"]);
    }

    /**
     * Get float variable safely.
     * @param $var
     * @return float
     */
    private function getFloat($var) {
        if(is_float($var)) {
            return $var;
        }
        else {
            return floatval($var);
        }
    }

    /**
     * Get amount sum from specs records
     * @param array $specs
     * @return float
     */
    private function getAmountBySpecs($specs=array()) {
        $amount = 0;
        foreach($specs as $index => $spec) {
            $amount += $this->getFloat($spec["amount"]);
        }
        return $amount;
    }

    /**
     * Get total spec amount.
     * @return float|int
     */
    private function getAmount() {
        if($this->amount == 0) {
            $this->amount = $this->getAmountBySpecs($paramsSpecs = $this->getParamsSpec());
        }
        return $this->amount;
    }

    /**
     * Check cart's spec with activity.
     *
     * @param $activitySpecs array Spec's record.
     * @param $paramsSpecs array Spec's record.
     * @throws DataAccessResultException
     * @throws InvalidAccessParamsException
     */
    public function specValidator($activitySpecs, $paramsSpecs) {
        if(count($activitySpecs) > 0) {
            if(is_array($paramsSpecs)) {
                foreach($paramsSpecs as $paramsIndex => $paramsSpec) {

                    $isValid = false;
                    foreach($activitySpecs as $activityIndex => $activitySpec) {
                        if( ($activitySpec["id"] == $paramsSpec["id"]) &&
                            ($activitySpec["product_id"] == $paramsSpec["product_id"]) ) {

                            if( ($activitySpec["can_sale_inventory"] - $paramsSpec["amount"]) >= 0 ) {
                                $isValid = true;
                            }
                            else {
                                throw new InvalidAccessParamsException("Spec amount should be less than inventory.");
                            }
                        }
                    }
                    if(!$isValid) {
                        throw new InvalidAccessParamsException("Can't find the spec int the activity.");
                    }
                }
            }
            else {
                throw new InvalidAccessParamsException("The params spec should be array.");
            }
        }
        else {
            throw new DataAccessResultException("The activity not exists any spec.");
        }
    }

    /**
     * Get activity's spec records.
     * @return array
     */
    protected function getActivitySpecs() {
        return $this->activityRecord["spec"]["records"];
    }

    /**
     * Get params's spec records.
     * @return array
     */
    protected function getParamsSpec() {
        return $this->params["spec"];
    }

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

        if($record["stateText"] != GroupBuyingActivityCollection::STARTED_STATE) {
            throw new InvalidAccessParamsException("The activity is not started.");
        }
    }

    /**
     * Get activity's record from db.
     *
     * @throws InvalidAccessParamsException
     * @throws OperationConflictException
     */
    private function queryActivity() {
        $id                     = $this->params["activityId"];
        $this->activityRecord   = $this->activityCollection->getUnifiedById($id);
        $this->activityRecordValidator($this->activityRecord);

        $activitySpecs          = $this->getActivitySpecs();
        $paramsSpecs            = $this->getParamsSpec();
        $this->specValidator($activitySpecs, $paramsSpecs);
    }

    /* Class UnifiedCartCollection abstract method. */

    /**
     * Get unified order type.
     * @throws InvalidAccessParamsException
     */
    public function getUnifiedType() {
        if($this->activityCollection instanceof UnifiedActivityCollection) {
            return $this->activityCollection->getUnifiedType();
        }
        else {
            throw new InvalidAccessParamsException("Invalid variable activityCollection.");
        }
    }

    /**
     * Get buyer name.
     * @return string
     */
    public function getBuyerName() {
        return $this->params["name"];
    }

    /**
     * Get buyer phone number.
     * @return string
     */
    public function getBuyerPhone() {
        return $this->params["phone"];
    }

    /**
     * Get buyer email address.
     * @return string
     */
    public function getBuyerEmail() {
        return $this->params["email"];
    }

    /**
     * Get receiver address.
     * @return string
     */
    public function getReceiverAddress() {
        return $this->params["address"];
    }

    /**
     * Get receiver name.
     * @return string
     */
    public function getReceiverName() {
        return $this->params["name"];
    }

    /**
     * Get receiver phone
     * @return string
     */
    public function getReceiverPhone() {
        return $this->params["phone"];
    }

    /**
     * Get final total price(user payment).
     * @return float
     */
    public function getFinalTotalPrice() {
        return $this->getProductPrice() + $this->getFare() + $this->getOtherCost() + $this->getDiscount();
    }

    /**
     * Get unit price of the product for the activity.
     * @return float
     */
    public function getUnitPrice() {
        if($this->unitPrice == 0) {
            $this->unitPrice = $this->getFloat($this->activityRecord["price"]);
        }

        return $this->unitPrice;
    }

    /**
     * Get total product price
     * @return float
     */
    public function getProductPrice() {
        if($this->productPrice == 0) {
            $this->productPrice = $this->getUnitPrice() * $this->getAmount();
        }
        return $this->productPrice;
    }

    /**
     * Get other cost (native or passive) fare discount or something
     * @return float
     */
    public function getOtherCost() {
        return 0;
    }

    /**
     * Get other cost type.
     * @return string|NULL
     */
    public function getCostType() {
        return "normal";
    }

    /**
     * Get total fare price
     * @return float
     */
    public function getFare() {
        return $this->fare->getUnifiedFare();
    }

    /**
     * Get fare type.
     * @return string|NULL
     */
    public function getFareType() {
        return $this->fare->getUnifiedType();
    }

    /**
     * Get fare id.
     * @return mixed
     */
    public function getFareId() {
        return $this->fare->getUnifiedId();
    }

    /**
     * Get total discount.
     * @return float
     */
    public function getDiscount() {
        return 0;
    }

    /**
     * Get discount type.
     * @return string|NULL
     */
    public function getDiscountType() {
        return "normal";
    }

    /**
     * Get payment type
     * @return string
     */
    public function getPaymentType() {
        return "neweb";
    }

    /**
     * Get spec records.
     * @return array
     */
    public function getSpecs() {
        $specs = array();
        foreach($this->getParamsSpec() as $index => $spec) {
            if($spec["amount"] > 0) {
                array_push($specs, array(
                    "product_id"    => $this->activityRecord["productId"],
                    "spec_id"       => $spec["id"],
                    "unit_price"    => $this->getUnitPrice(),
                    "total_price"   => $spec["amount"] * $this->getUnitPrice(),
                    "spec_amount"   => $spec["amount"],
                    "other_cost"    => $this->getOtherCost(),
                    "cost_type"     => $this->getCostType(),
                    "fare"          => $this->getFare(),
                    "fare_type"     => $this->getFareType(),
                    "discount"      => $this->getDiscount(),
                    "discount_type" => $this->getDiscountType(),
                    "activity_type" => $this->getUnifiedType(),
                    "activity_id"   => $this->activityRecord["id"],
                ));
            }
        }
        return $specs;
    }

    /**
     * Get the cart's activity's id
     * @return string
     */
    public function getActivityId() {
        return $this->params["activityId"];
    }

    /**
     * Get order's serial number.
     * @return string
     */
    public function getSerial() {
        $authHelper     = new AuthenticateHelper();
        return date("ymd") . $authHelper->generateSalt(7);
    }

    /**
     * Get how to process inventory flag.
     * @return mixed
     */
    public function getInventoryProcess() {
        return  1;
    }

    /**
     * @return string
     */
    public function getCompanyName() {
        return  '';
    }


    /**
     * @return string
     */
    public function getTaxID() {
        return  '';
    }

    /**
     * @return string
     */
    public function getConsumerRemark() {
        return  '';
    }
}


?>