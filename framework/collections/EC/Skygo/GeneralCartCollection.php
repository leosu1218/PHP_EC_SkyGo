<?php
require_once(dirname(__FILE__) . '/../UnifiedCartCollection.php');
require_once(dirname(__FILE__) . '/GeneralConsumerCollection.php');
require_once( FRAMEWORK_PATH . 'collections/GeneralActivityCollection.php' );
require_once( FRAMEWORK_PATH . 'models/GeneralActivity.php' );
require_once( FRAMEWORK_PATH . 'collections/FareCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/ValidatorHelper.php' );
require_once( FRAMEWORK_PATH . 'extends/AuthenticateHelper.php' );

/**
 * Class GeneralCartCollection
 */
class GeneralCartCollection extends UnifiedCartCollection {

    protected $dao;
    protected $params;

    private $activities = null;
    private $productPrice = 0;

    private $discounts = array();

    private $specs = array();

    public function __construct($params=array(), &$dao=null) {
        $this->validator = new ValidatorHelper();
        $this->validator->requireAttribute("name", $params);
        $this->validator->requireAttribute("phone", $params);
        $this->validator->requireAttribute("address", $params);
        $this->validator->requireAttribute("spec", $params);
        $this->validator->requireAttribute("fareId", $params);
        $this->validator->requireAttribute("payType", $params);
        $this->validator->requireAttribute("inventoryProcess", $params);
        $this->validator->requireAttribute("companyName", $params);
        $this->validator->requireAttribute("taxID", $params);
        $this->params = $params;
        $this->dao = $dao;

        $this->configFare();
        $this->configActivities();
        $this->configDiscount();
    }

    /**
     * Configuration UnifiedFareCollection object for self parent class UnifiedCartCollection.
     * @throws AuthorizationException
     */
    private function configFare() {
        $fareCollection = new FareCollection($this->dao);
        $fare = $fareCollection->getById($this->params["fareId"]);
        $this->setFare($fare);
    }

    /**
     * Configuration activity collection.
     */
    private function configActivities() {
        $this->activities = new GeneralActivityCollection($this->dao);
    }

    /**
     * Configuration discount object of the cart can be used.
     * There will get all discount into self::discounts
     * @throws InvalidAccessParamsException
     */
    private function configDiscount() {
        foreach($this->params["spec"] as $index => $spec) {
            $id         = $spec["activity_id"];
            $activity   = $this->activities->getById($id);
            if(is_null($activity)) {
                throw new InvalidAccessParamsException("Invalid activity id [$id].");
            }

            foreach($activity->getDiscounts() as $i => $discount) {
                for($i = 0; $i < $spec["amount"]; $i++) {
                    $this->discounts[] = $discount;
                }
            }
        }
    }

    /**
     * Check the spec has any discount that can be used.
     * @param $spec array Format: {id:<int>, product_id:<int>, activity_id:<int>, amount:<int>}
     * @return bool
     */
    private function specHasDiscount($spec) {
        $isHasDiscount = false;
        foreach($this->discounts as $index => $discount) {
            $isHasDiscount = $isHasDiscount || $discount->canUsedToSpec($spec);
        }

        return $isHasDiscount;
    }

    /**
     * Using discount for a spec. It will remove discount from $this->discounts that was used.
     * @param $spec
     * @return null
     * @throws InvalidAccessParamsException
     */
    private function usedDiscount($spec) {
        $selectIndex = null;
        $minPrice    = null;
        foreach($this->discounts as $index => $discount) {
            if($discount->canUsedToSpec($spec)) {
                $price = $this->getFloat($discount->getPriceForSpec($spec));
                if(is_null($selectIndex) || ($price < $minPrice)) {
                    $minPrice       = $price;
                    $selectIndex    = $index;
                }
            }
        }

        if(is_null($selectIndex)) {
            throw new InvalidAccessParamsException("The discount not found for the spec." . json_encode($spec));
        }

        unset($this->discounts[$selectIndex]);
        return $minPrice;
    }

    private function isNormalSpec(&$spec=array()) {
        if(array_key_exists("activity_id", $spec)) {
            return (intval($spec["activity_id"]) > 0);
        }
        return false;
    }

    private function getPriceByNormalSpec(&$spec=array()) {
        $defaultSpec    = array("amount" => 0);
        $normalSpec     = array_merge($spec, $defaultSpec);
        $discountSpec   = array_merge($spec, $defaultSpec);

        $price = $this->activities->getById($spec["activity_id"])->getAttribute("price");
        $normalSpec["unit_price"] = $this->getFloat($price);
        $normalSpec["discount"]   = 0;
        $normalSpec["discount_type"] = $this->getDiscountType();

        for($i = 0; $i < $spec["amount"]; $i++) {
            if($this->specHasDiscount($spec)) {
                $discountSpec["unit_price"]    = $this->usedDiscount($spec);
                $discountSpec["discount"]      = $price - $discountSpec["unit_price"];
                $discountSpec["activity_id"]   = $spec["activity_id"];
                $discountSpec["discount_type"] = "relation_product";
                $discountSpec["amount"]++;
            }
            else {
                $normalSpec["amount"]++;
            }
        }

        $this->saveSpec($normalSpec);
        $this->saveSpec($discountSpec);

        return $this->getTotalPriceBySpec($normalSpec) + $this->getTotalPriceBySpec($discountSpec);
    }

    private function isDiscountSpec(&$spec=array()) {
        if(array_key_exists("activity_id", $spec)) {
            return (intval($spec["activity_id"]) == 0);
        }
        return false;
    }

    private function getPriceByDiscountSpec(&$spec=array()) {
        $defaultSpec    = array("amount" => 0);
        $discountSpec   = array_merge($spec, $defaultSpec);

        for($i = 0; $i < $spec["amount"]; $i++) {
            if($this->specHasDiscount($spec)) {
                $discountSpec["unit_price"]    = $this->usedDiscount($spec);
                $discountSpec["discount"]      = 0;
                $discountSpec["activity_id"]   = 0;
                $discountSpec["discount_type"] = "relation_product";
                $discountSpec["amount"]++;
            }
            else {
                throw new OperationConflictException("Can't use any discount case.");
            }
        }

        $this->saveSpec($discountSpec);
        return $this->getTotalPriceBySpec($discountSpec);
    }

    /**
     * Get price of a product spec record.
     * @param array $spec
     * @return float|null
     * @throws InvalidAccessParamsException
     */
    private function getPriceBySpecRecord(&$spec=array()) {
        if($this->isNormalSpec($spec)) {
            return $this->getPriceByNormalSpec($spec);
        }
        else {
            return $this->getPriceByDiscountSpec($spec);
        }
    }

    /**
     * Get sum of all price.
     * @param $spec
     * @return int
     */
    private function getTotalPriceBySpec($spec) {
        if(array_key_exists("total_price", $spec)) {
            return $spec["total_price"];
        }
        else if($spec["amount"] > 0) {
            return $spec["unit_price"] * $spec["amount"];
        }
        else {
            return 0;
        }
    }

    /**
     * Ignore 0 amount spec, and save spec to $this->sepcs.
     * @param $spec
     */
    private function saveSpec($spec) {
        if($spec["amount"] > 0) {
            $spec["total_price"] = $this->getTotalPriceBySpec($spec);
            array_push($this->specs, $spec);
        }
    }

    /**
     * Get product ids;
     * @return array
     */
    public function getProductIds() {
        $productIds = array();
        foreach($this->params["spec"] as $index => $spec) {
            $productIds[] = $spec["product_id"];
        }

        return $productIds;
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

    /* Class UnifiedCartCollection abstract method. */

    /**
     * Get unified order type.
     * @throws InvalidAccessParamsException
     */
    public function getUnifiedType() {
        return GeneralActivity::TYPE_NAME;
    }

    /**
     * Get buyer name.
     * @return string
     */
    public function getBuyerName() {
        if(is_null($this->userCollection)) {
            return "";
        }
        else if($this->userCollection instanceof GeneralConsumerCollection) {
            $info = $this->userCollection->getUnifiedBySession();
            if(array_key_exists("name", $info)) {
                return $info["name"];
            }
            else {
                throw new ECException("Missing attribute [name] from class UnifiedUserCollection.");
            }
        }
        else {
            throw new ECException("Invalid type of class UnifiedUserCollection.");
        }
    }

    /**
     * Get buyer phone number.
     * @return string
     */
    public function getBuyerPhone() {
        if(is_null($this->userCollection)) {
            return "";
        }
        else if($this->userCollection instanceof GeneralConsumerCollection) {
            $info = $this->userCollection->getUnifiedBySession();
            if(array_key_exists("phone", $info)) {
                return $info["phone"];
            }
            else {
                throw new ECException("Missing attribute [phone] from class UnifiedUserCollection.");
            }
        }
        else {
            throw new ECException("Invalid type of class UnifiedUserCollection.");
        }
    }

    /**
     * Get buyer email address.
     * @return string
     */
    public function getBuyerEmail() {
        if(is_null($this->userCollection)) {
            return "";
        }
        else if($this->userCollection instanceof GeneralConsumerCollection) {
            $info = $this->userCollection->getUnifiedBySession();
            if(array_key_exists("email", $info)) {
                return $info["email"];
            }
            else {
                throw new ECException("Missing attribute [email] from class UnifiedUserCollection.");
            }
        }
        else {
            throw new ECException("Invalid type of class UnifiedUserCollection.");
        }
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
        throw new OperationConflictException("Unsupported the method.");
    }

    /**
     * Get total product price
     * @return float
     */
    public function getProductPrice() {
        if($this->productPrice == 0) {

            foreach($this->params["spec"] as $index => $spec) {
                if($this->isDiscountSpec($spec)) {
                    $this->productPrice += $this->getPriceBySpecRecord($spec);
                }
            }

            foreach($this->params["spec"] as $index => $spec) {
                if($this->isNormalSpec($spec)) {
                    $this->productPrice += $this->getPriceBySpecRecord($spec);
                }
            }
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
        if(isset($this->params["fare"])){
            return $this->params["fare"];
        }else {
            return 0;
        }
    }

    /**
     * Get fare type.
     * @return string|NULL
     */
    public function getFareType() {
        return $this->params["deliveryProgramId"];
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
        return $this->params["payType"];
    }

    /**
     * Get spec records.
     * @return array
     */
    public function getSpecs() {
        if($this->productPrice == 0) {
            $this->getProductPrice();
        }

        $specs = array();
        foreach($this->specs as $index => $spec) {
            if($spec["amount"] > 0) {
                array_push($specs, array(
                    "product_id"    => $spec["product_id"],
                    "spec_id"       => $spec["id"],
                    "unit_price"    => $spec["unit_price"],
                    "total_price"   => $spec["total_price"],
                    "spec_amount"   => strval($spec["amount"]),
                    "other_cost"    => $this->getOtherCost(),
                    "cost_type"     => $this->getCostType(),
                    "fare"          => $this->getFare(),
                    "fare_type"     => $this->getFareType(),
                    "discount"      => $spec["discount"],
                    "discount_type" => $spec["discount_type"],
                    "activity_type" => $this->getUnifiedType(),
                    "activity_id"   => $spec["activity_id"],
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
        return 0;
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
        return  $this->params['inventoryProcess'];
    }

    /**
     * @return string
     */
    public function getCompanyName() {
        return  $this->params['companyName'];
    }


    /**
     * @return string
     */
    public function getTaxID() {
        return  $this->params['taxID'];
    }

    /**
     * @return string
     */
    public function getConsumerRemark() {
        return  $this->params['consumerRemark'];
    }
}


?>