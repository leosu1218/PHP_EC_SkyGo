<?php
require_once(dirname(__FILE__) . '/../../phpunit/DbHero_TestCase.php');
require_once( FRAMEWORK_PATH . 'collections/ProductGroupCollection.php' );

/**
 * Class FareCollectionTest
 */
class ProductGroupCollectionTest extends DbHero_TestCase {

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable('product_group', dirname(__FILE__)."/ProductGroupCollection/product_group.csv");
        $dataSet->addTable('gb_master_user_has_product_group', dirname(__FILE__)."/ProductGroupCollection/gb_master_user_has_product_group.csv");
        return $dataSet;
    }

    /**
     * Test search product group's sub node
     * @dataProvider searchProductGroupSubIdsProvider
     */
    public function testSearchProductGroupSubIds($describe, $params, $expected) {
        echo "$describe \n\r ";
        $collection = new ProductGroupCollection();
        $result = $collection->searchProductGroupSubIds($params);
        $this->assertEquals($expected, $result);
    }

    public function searchProductGroupSubIdsProvider() {
        return array(
            array(
                //Describe
                "Search productGroup's sub node by parentIds.",
                // params
                array(2),
                // expected
                json_decode('
                    ["4","5","6","2"]
                ', true)
            ),
        );
    }

    /**
     * Test create product group and append new node for master.
     * @dataProvider productGroupCreateProvider
     */
    public function testProductGroupCreate($describe, $params, $expected) {
        echo "$describe \n\r ";
        $collection = new ProductGroupCollection();
        $result = $collection->productGroupCreate($params);
        $this->assertEquals($expected, $result);
    }

    public function productGroupCreateProvider() {
        return array(
            array(
                //Describe
                "Append productGroup's sub node.",
                // params
                array("name"=>"Test Group","parent_group_id"=>"6","type"=>"1","channel"=>"wholesale"),
                // expected
                array("productGroupCreate"=>1,"masterUserHasProductGroup"=>1)
            ),
        );
    }
}

?>