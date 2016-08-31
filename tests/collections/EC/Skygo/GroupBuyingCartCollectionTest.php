<?php
require_once(dirname(__FILE__) . '/../../../../phpunit/DbHero_TestCase.php');
require_once( FRAMEWORK_PATH . 'collections/EC/Skygo/GroupBuyingCartCollection.php' );

/**
 * Class GroupBuyingCartCollectionTest
 */
class GroupBuyingCartCollectionTest extends DbHero_TestCase {

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable('fare', dirname(__FILE__)."/GroupbuyingCartCollection/fare.csv");
        $dataSet->addTable('product_group', dirname(__FILE__)."/GroupbuyingCartCollection/product_group.csv");
        $dataSet->addTable('wholesale_product', dirname(__FILE__)."/GroupbuyingCartCollection/wholesale_product.csv");
        $dataSet->addTable('wholesale_product_spec', dirname(__FILE__)."/GroupbuyingCartCollection/wholesale_product_spec.csv");
        $dataSet->addTable('wholesale_explain_image', dirname(__FILE__)."/GroupbuyingCartCollection/wholesale_explain_image.csv");
        $dataSet->addTable('wholesale_materials', dirname(__FILE__)."/GroupbuyingCartCollection/wholesale_materials.csv");
        $dataSet->addTable('product_has_fare', dirname(__FILE__)."/GroupbuyingCartCollection/product_has_fare.csv");
        $dataSet->addTable('groupbuying_activity', dirname(__FILE__)."/GroupbuyingCartCollection/groupbuying_activity.csv");
        return $dataSet;
    }

    /**
     * Test get cart's fare.
     * @dataProvider getFareProvider
     */
    public function testGetFare($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GroupBuyingCartCollection($params);
        $result = $cart->getFare();
        $this->assertEquals($expected, $result);
    }

    public function getFareProvider () {
        return array(
            array(
                //Describe
                "Get Groupbuying Activity[2] fare for fareId[3].",
                // params
                array(
                    "activityId" => 2,
                    "name" => "rex",
                    "phone" => "0972831679",
                    "email" => "chen.cyr@gmail.com",
                    "address" => "address",
                    "fareId" => 3,
                    "spec" => array(
                        array("id" => 2, "amount" => 0, "product_id" => 2),
                        array("id" => 4, "amount" => 1, "product_id" => 2)
                    )
                ),
                // expected
                100
            ),
            array(
                //Describe
                "Get Groupbuying Activity[2] fare for fareId[3] free.",
                // params
                array(
                    "activityId" => 2,
                    "name" => "rex",
                    "phone" => "0972831679",
                    "email" => "chen.cyr@gmail.com",
                    "address" => "address",
                    "fareId" => 3,
                    "spec" => array(
                        array("id" => 1, "amount" => 3, "product_id" => 2),
                    )
                ),
                // expected
                0
            ),
        );
    }

    /**
     * Test get product list
     * @dataProvider getProductIdsProvider
     */
    public function testGetProductIds($describe, $params, $expected) {
        echo "$describe \n\r ";
        $cart = new GroupBuyingCartCollection($params);
        $result = $cart->getProductIds();
        $this->assertEquals($expected, $result);
    }

    public function getProductIdsProvider () {
        return array(
            array(
                //Describe
                "Get Groupbuying Activity[2] product list.",
                // params
                array(
                    "activityId" => 2,
                    "name" => "rex",
                    "phone" => "0972831679",
                    "email" => "chen.cyr@gmail.com",
                    "address" => "address",
                    "fareId" => 3,
                    "spec" => array(
                        array("id" => 1, "amount" => 3, "product_id" => 2),
                        array("id" => 2, "amount" => 0, "product_id" => 2),
                        array("id" => 3, "amount" => 2, "product_id" => 2),
                        array("id" => 4, "amount" => 1, "product_id" => 2)
                    )
                ),
                // expected
                array(2)
            ),
        );
    }
}

?>