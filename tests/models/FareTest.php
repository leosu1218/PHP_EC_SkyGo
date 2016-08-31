<?php
require_once(dirname(__FILE__) . '/../../phpunit/DbHero_TestCase.php');
require_once( FRAMEWORK_PATH . 'models/Fare.php' );

/**
 * Class FareTest
 */
class FareTest extends DbHero_TestCase {

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable('fare', dirname(__FILE__)."/fare.csv");
        $dataSet->addTable('fare', dirname(__FILE__)."/fare.csv");
        $dataSet->addTable('product_group', dirname(__FILE__)."/product_group.csv");
        $dataSet->addTable('wholesale_product', dirname(__FILE__)."/wholesale_product.csv");
        $dataSet->addTable('wholesale_product_spec', dirname(__FILE__)."/wholesale_product_spec.csv");
        $dataSet->addTable('wholesale_explain_image', dirname(__FILE__)."/wholesale_explain_image.csv");
        $dataSet->addTable('wholesale_materials', dirname(__FILE__)."/wholesale_materials.csv");
        $dataSet->addTable('product_has_fare', dirname(__FILE__)."/product_has_fare.csv");
        $dataSet->addTable('groupbuying_activity', dirname(__FILE__)."/groupbuying_activity.csv");
        return $dataSet;
    }

    /**
     * Test isUsedForProduct
     * @dataProvider isUsedForProductProvider
     */
    public function testIsUsedForProduct($describe, $fareId, $productId, $expected) {
        echo "$describe \n\r ";
        $fare = new Fare($fareId);
        $result = $fare->isUsedForProduct($productId);
        $this->assertEquals($expected, $result);
    }

    public function isUsedForProductProvider () {
        return array(
            array(
                //Describe
                "Check fare[1] is used for the product[2].",
                // fare id
                1,
                // product id
                2,
                // expected
                true
            ),
        );
    }
}

?>