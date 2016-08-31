<?php
require_once(dirname(__FILE__) . '/../../../../phpunit/DbHero_TestCase.php');
require_once( FRAMEWORK_PATH . 'collections/EC/Skygo/GeneralCartCollection.php' );

/**
 * Class GroupBuyingCartCollectionTest
 */
class GeneralCartCollectionTest extends DbHero_TestCase {

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable('fare', dirname(__FILE__)."/GeneralCartCollection/fare.csv");
        $dataSet->addTable('product_group', dirname(__FILE__)."/GeneralCartCollection/product_group.csv");
        $dataSet->addTable('wholesale_product', dirname(__FILE__)."/GeneralCartCollection/wholesale_product.csv");
        $dataSet->addTable('wholesale_product_spec', dirname(__FILE__)."/GeneralCartCollection/wholesale_product_spec.csv");
        $dataSet->addTable('wholesale_explain_image', dirname(__FILE__)."/GeneralCartCollection/wholesale_explain_image.csv");
        $dataSet->addTable('wholesale_materials', dirname(__FILE__)."/GeneralCartCollection/wholesale_materials.csv");
        $dataSet->addTable('product_has_fare', dirname(__FILE__)."/GeneralCartCollection/product_has_fare.csv");
        $dataSet->addTable('general_activity', dirname(__FILE__)."/GeneralCartCollection/general_activity.csv");
        $dataSet->addTable('general_activity_has_relation_product_discount', dirname(__FILE__)."/GeneralCartCollection/general_activity_has_relation_product_discount.csv");
        return $dataSet;
    }

    /**
     * Get a typical test case.
     * @return array
     */
    private function getTypicalCase() {
        return json_decode('{
                    "fareId": "3",
                    "activityId": "1",
                    "name": "Rex",
                    "phone": "0972831678",
                    "email": "chen.cyr@gmail.com",
                    "address": "台北市信義區安和路一段89號",
                    "payType": "ATM",
                    "companyName":"抬頭",
                    "taxID":"11111111111",
                   "inventoryProcess":"1",
                    "spec": [{
                        "amount": 1,
                        "id": "1",
                        "product_id": "2",
                        "activity_id": "1"
                    }, {
                        "amount": 1,
                        "id": "2",
                        "product_id": "2",
                        "activity_id": "1"
                    }, {
                        "amount": 2,
                        "id": "18",
                        "product_id": "3",
                        "activity_id": "2"
                    }]
                }', true);
    }

    /**
     * Test get cart's type.
     * @dataProvider getUnifiedTypeProvider
     */
    public function testGetUnifiedType($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GeneralCartCollection($params);
        $result = $cart->getUnifiedType();
        $this->assertEquals($expected, $result);
    }

    public function getUnifiedTypeProvider () {
        return array(
            array(
                //Describe
                "Get cart type name.",
                // params
                $this->getTypicalCase(),
                // expected
                "general"
            ),
        );
    }

    /**
     * Test get cart's buyer name.
     * @dataProvider getBuyerNameProvider
     */
    public function testGetBuyerName($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GeneralCartCollection($params);
        $result = $cart->getBuyerName();
        $this->assertEquals($expected, $result);
    }

    public function getBuyerNameProvider () {
        return array(
            array(
                //Describe
                "Get buyer name.",
                // params
                $this->getTypicalCase(),
                // expected
                "Rex"
            ),
        );
    }

    /**
     * Test get cart's buyer name.
     * @dataProvider getBuyerPhoneProvider
     */
    public function testGetBuyerPhone($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GeneralCartCollection($params);
        $result = $cart->getBuyerPhone();
        $this->assertEquals($expected, $result);
    }

    public function getBuyerPhoneProvider() {
        return array(
            array(
                //Describe
                "Get buyer phone.",
                // params
                $this->getTypicalCase(),
                // expected
                ""
            ),
        );
    }

    /**
     * Test get cart's buyer email.
     * @dataProvider getBuyerEmailProvider
     */
    public function testGetBuyerEmail($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GeneralCartCollection($params);
        $result = $cart->getBuyerEmail();
        $this->assertEquals($expected, $result);
    }

    public function getBuyerEmailProvider() {
        return array(
            array(
                //Describe
                "Get buyer email.",
                // params
                $this->getTypicalCase(),
                // expected
                ""
            ),
        );
    }

    /**
     * Test get cart's receiver address.
     * @dataProvider getReceiverAddressProvider
     */
    public function testGetReceiverAddress($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GeneralCartCollection($params);
        $result = $cart->getReceiverAddress();
        $this->assertEquals($expected, $result);
    }

    public function getReceiverAddressProvider() {
        return array(
            array(
                //Describe
                "Get receiver address.",
                // params
                $this->getTypicalCase(),
                // expected
                "台北市信義區安和路一段89號"
            ),
        );
    }

    /**
     * Test get cart's receiver name.
     * @dataProvider getReceiverNameProvider
     */
    public function testGetReceiverName($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GeneralCartCollection($params);
        $result = $cart->getReceiverName();
        $this->assertEquals($expected, $result);
    }

    public function getReceiverNameProvider() {
        return array(
            array(
                //Describe
                "Get receiver name.",
                // params
                $this->getTypicalCase(),
                // expected
                "Rex"
            ),
        );
    }

    /**
     * Test get cart's receiver phone.
     * @dataProvider getReceiverPhoneProvider
     */
    public function testGetReceiverPhone($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GeneralCartCollection($params);
        $result = $cart->getReceiverPhone();
        $this->assertEquals($expected, $result);
    }

    public function getReceiverPhoneProvider() {
        return array(
            array(
                //Describe
                "Get receiver phone.",
                // params
                $this->getTypicalCase(),
                // expected
                "0972831678"
            ),
        );
    }

    /**
     * Test get cart's Inventory Process.
     * @dataProvider getInventoryProcess
     */
    public function testGetInventoryProcess($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GeneralCartCollection($params);
        $result = $cart->getInventoryProcess();
        $this->assertEquals($expected, $result);
    }

    public function getInventoryProcess() {
        return array(
            array(
                //Describe
                "Get Inventory Process.",
                // params
                $this->getTypicalCase(),
                // expected
                "1"
            ),
        );
    }

    /**
     * Test get cart's Company Name.
     * @dataProvider getCompanyName
     */
    public function testGetCompanyName($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GeneralCartCollection($params);
        $result = $cart->getCompanyName();
        $this->assertEquals($expected, $result);
    }

    public function getCompanyName() {
        return array(
            array(
                //Describe
                "Get Company Name.",
                // params
                $this->getTypicalCase(),
                // expected
                "抬頭"
            ),
        );
    }

    /**
     * Test get cart's Tax ID.
     * @dataProvider getTaxID
     */
    public function testGetTaxID($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GeneralCartCollection($params);
        $result = $cart->getTaxID();
        $this->assertEquals($expected, $result);
    }

    public function getTaxID() {
        return array(
            array(
                //Describe
                "Get Tax ID.",
                // params
                $this->getTypicalCase(),
                // expected
                "11111111111"
            ),
        );
    }

    /**
     * Test get cart's product price (sum of all products).
     * @dataProvider getProductPriceProvider
     */
    public function testGetProductPrice($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GeneralCartCollection($params);
        $result = $cart->getProductPrice();
        $this->assertEquals($expected, $result);
    }

    public function getProductPriceProvider() {
        return array(
            array(
                //Describe
                "Get product price case 1.",
                // params
                json_decode('{
                    "fareId": "3",
                    "activityId": "1",
                    "name": "Rex",
                    "phone": "0972831678",
                    "email": "chen.cyr@gmail.com",
                    "address": "台北市信義區安和路一段89號",
                    "payType": "ATM",
                   "inventoryProcess":"1",
                   "companyName":"抬頭",
                    "taxID":"11111111111",
                    "spec": [{
                        "amount": 2,
                        "id": "1",
                        "product_id": "2",
                        "activity_id": "1"
                    }]
                }', true),
                // expected
                (8900 * 2)
            ),
            array(
                //Describe
                "Get product price case 2.",
                // params
                json_decode('{
                    "fareId": "3",
                    "activityId": "1",
                    "name": "Rex",
                    "phone": "0972831678",
                    "email": "chen.cyr@gmail.com",
                    "address": "台北市信義區安和路一段89號",
                    "payType": "ATM",
                   "inventoryProcess":"1",
                   "companyName":"抬頭",
                    "taxID":"11111111111",
                    "spec": [{
                        "amount": 2,
                        "id": "1",
                        "product_id": "2",
                        "activity_id": "1"
                    },
                    {
                        "amount": 1,
                        "id": "2",
                        "product_id": "2",
                        "activity_id": "1"
                    }]
                }', true),
                // expected
                (8900 * 3)
            ),
            array(
                //Describe
                "Get product price case 3.",
                // params
                json_decode('{
                    "fareId": "3",
                    "activityId": "1",
                    "name": "Rex",
                    "phone": "0972831678",
                    "email": "chen.cyr@gmail.com",
                    "address": "台北市信義區安和路一段89號",
                    "payType": "ATM",
                   "inventoryProcess":"1",
                   "companyName":"抬頭",
                    "taxID":"11111111111",
                    "spec": [{
                        "amount": 1,
                        "id": "1",
                        "product_id": "2",
                        "activity_id": "1"
                    },
                    {
                        "amount": 2,
                        "id": "2",
                        "product_id": "2",
                        "activity_id": "1"
                    },
                     {
                        "amount": 2,
                        "id": "18",
                        "product_id": "3",
                        "activity_id": "2"
                    }]
                }', true),
                // expected
                (8500 * 2) + (10999 * 2) + 8900
            ),
        );
    }

    /**
     * Test get cart's specs list.
     * @dataProvider getSpecsProvider
     */
    public function testGetSpecs($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GeneralCartCollection($params);
        $result = $cart->getSpecs();
        $this->assertEquals($expected, $result);
    }

    public function getSpecsProvider() {
        return array(
            array(
                //Describe
                "Get spec list case 1.",
                // params
                json_decode('{
                    "fareId": "3",
                    "activityId": "1",
                    "name": "Rex",
                    "phone": "0972831678",
                    "email": "chen.cyr@gmail.com",
                    "address": "台北市信義區安和路一段89號",
                    "payType": "ATM",
                   "inventoryProcess":"1",
                   "companyName":"抬頭",
                    "taxID":"11111111111",
                    "spec": [{
                        "amount": 2,
                        "id": "1",
                        "product_id": "2",
                        "activity_id": "1"
                    }]
                }', true),
                // expected
                json_decode('[{
                    "product_id": "2",
                    "spec_id": "1",
                    "unit_price": 8900,
                    "total_price": 17800,
                    "spec_amount": 2,
                    "other_cost": 0,
                    "cost_type": "normal",
                    "fare": "100",
                    "fare_type": "宅配",
                    "discount": 0,
                    "discount_type": "normal",
                    "activity_type": "general",
                    "activity_id": "1"
                }]', true)
            ),
            array(
                //Describe
                "Get spec list case 2.",
                // params
                json_decode('{
                    "fareId": "3",
                    "activityId": "1",
                    "name": "Rex",
                    "phone": "0972831678",
                    "email": "chen.cyr@gmail.com",
                    "address": "台北市信義區安和路一段89號",
                    "payType": "ATM",
                   "inventoryProcess":"1",
                   "companyName":"",
                    "taxID":"",
                    "spec": [{
                        "amount": 2,
                        "id": "1",
                        "product_id": "2",
                        "activity_id": "1"
                    },
                    {
                        "amount": 1,
                        "id": "2",
                        "product_id": "2",
                        "activity_id": "1"
                    }]
                }', true),
                // expected
                json_decode('[{
                    "product_id": "2",
                    "spec_id": "1",
                    "unit_price": 8900,
                    "total_price": 17800,
                    "spec_amount": 2,
                    "other_cost": 0,
                    "cost_type": "normal",
                    "fare": 0,
                    "fare_type": "宅配",
                    "discount": 0,
                    "discount_type": "normal",
                    "activity_type": "general",
                    "activity_id": "1"
                }, {
                    "product_id": "2",
                    "spec_id": "2",
                    "unit_price": 8900,
                    "total_price": 8900,
                    "spec_amount": 1,
                    "other_cost": 0,
                    "cost_type": "normal",
                    "fare": 0,
                    "fare_type": "宅配",
                    "discount": 0,
                    "discount_type": "normal",
                    "activity_type": "general",
                    "activity_id": "1"
                }]', true)
            ),
            array(
                //Describe
                "Get spec list case 3.",
                // params
                json_decode('{
                    "fareId": "3",
                    "activityId": "1",
                    "name": "Rex",
                    "phone": "0972831678",
                    "email": "chen.cyr@gmail.com",
                    "address": "台北市信義區安和路一段89號",
                    "payType": "ATM",
                   "inventoryProcess":"1",
                   "companyName":"",
                    "taxID":"",
                    "spec": [{
                        "amount": 1,
                        "id": "1",
                        "product_id": "2",
                        "activity_id": "1"
                    },
                    {
                        "amount": 2,
                        "id": "2",
                        "product_id": "2",
                        "activity_id": "1"
                    },
                     {
                        "amount": 2,
                        "id": "18",
                        "product_id": "3",
                        "activity_id": "2"
                    }]
                }', true),
                // expected
                json_decode('[{
                    "product_id": "2",
                    "spec_id": "1",
                    "unit_price": "8500",
                    "total_price": 8500,
                    "spec_amount": 1,
                    "other_cost": 0,
                    "cost_type": "normal",
                    "fare": 0,
                    "fare_type": "宅配",
                    "discount": 400,
                    "discount_type": "relation_product",
                    "activity_type": "general",
                    "activity_id": 1
                }, {
                    "product_id": "2",
                    "spec_id": "2",
                    "unit_price": 8900,
                    "total_price": 8900,
                    "spec_amount": 1,
                    "other_cost": 0,
                    "cost_type": "normal",
                    "fare": 0,
                    "fare_type": "宅配",
                    "discount": 0,
                    "discount_type": "normal",
                    "activity_type": "general",
                    "activity_id": "1"
                }, {
                    "product_id": "2",
                    "spec_id": "2",
                    "unit_price": "8500",
                    "total_price": 8500,
                    "spec_amount": 1,
                    "other_cost": 0,
                    "cost_type": "normal",
                    "fare": 0,
                    "fare_type": "宅配",
                    "discount": 400,
                    "discount_type": "relation_product",
                    "activity_type": "general",
                    "activity_id": 1
                }, {
                    "product_id": "3",
                    "spec_id": "18",
                    "unit_price": 10999,
                    "total_price": 21998,
                    "spec_amount": 2,
                    "other_cost": 0,
                    "cost_type": "normal",
                    "fare": 0,
                    "fare_type": "宅配",
                    "discount": 0,
                    "discount_type": "normal",
                    "activity_type": "general",
                    "activity_id": "2"
                }]', true)
            ),
        );
    }

    /**
     * Test get cart's fare.
     * @dataProvider getFareProvider
     */
    public function testGetFare($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GeneralCartCollection($params);
        $result = $cart->getFare();
        $this->assertEquals($expected, $result);
    }

    public function getFareProvider () {
        return array(
            array(
                //Describe
                "Get fare for fareId[3].",
                // params
                json_decode('{
                    "fareId": "3",
                    "activityId": "1",
                    "name": "Rex",
                    "phone": "0972831678",
                    "email": "chen.cyr@gmail.com",
                    "address": "台北市信義區安和路一段89號",
                    "payType": "ATM",
                   "inventoryProcess":"1",
                   "companyName":"",
                    "taxID":"",
                    "spec": [{
                        "amount": 1,
                        "id": "1",
                        "product_id": "2",
                        "activity_id": "1"
                    }]
                }', true),
                // expected
                100
            ),
            array(
                //Describe
                "Get fare for fareId[3] (over target price).",
                // params
                json_decode('{
                    "fareId": "3",
                    "activityId": "1",
                    "name": "Rex",
                    "phone": "0972831678",
                    "email": "chen.cyr@gmail.com",
                    "address": "台北市信義區安和路一段89號",
                    "payType": "ATM",
                   "inventoryProcess":"1",
                   "companyName":"",
                    "taxID":"",
                    "spec": [{
                        "amount": 1,
                        "id": "1",
                        "product_id": "2",
                        "activity_id": "1"
                    },
                    {
                        "amount": 2,
                        "id": "2",
                        "product_id": "2",
                        "activity_id": "1"
                    },
                     {
                        "amount": 2,
                        "id": "18",
                        "product_id": "3",
                        "activity_id": "2"
                    }]
                }', true),
                // expected
                0
            ),
        );
    }
}

?>