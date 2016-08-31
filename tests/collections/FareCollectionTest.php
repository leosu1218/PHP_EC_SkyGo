<?php
require_once(dirname(__FILE__) . '/../../phpunit/DbHero_TestCase.php');
require_once( FRAMEWORK_PATH . 'collections/FareCollection.php' );

/**
 * Class FareCollectionTest
 */
class FareCollectionTest extends DbHero_TestCase {

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable('fare', dirname(__FILE__)."/FareCollection/fare.csv");
//        $dataSet->addTable('product_group', dirname(__FILE__)."/FareCollection/product_group.csv");
//        $dataSet->addTable('wholesale_product', dirname(__FILE__)."/FareCollection/wholesale_product.csv");
//        $dataSet->addTable('wholesale_product_spec', dirname(__FILE__)."/FareCollection/wholesale_product_spec.csv");
//        $dataSet->addTable('wholesale_explain_image', dirname(__FILE__)."/FareCollection/wholesale_explain_image.csv");
//        $dataSet->addTable('wholesale_materials', dirname(__FILE__)."/FareCollection/wholesale_materials.csv");
        $dataSet->addTable('product_has_fare', dirname(__FILE__)."/FareCollection/product_has_fare.csv");
        $dataSet->addTable('groupbuying_activity', dirname(__FILE__)."/FareCollection/groupbuying_activity.csv");
        return $dataSet;
    }

    /**
     * Test search fare's records
     * @dataProvider searchRecordsProvider
     */
    public function testSearchRecords($describe, $params, $expected) {
        echo "$describe \n\r ";
        $collection = new FareCollection();
        $result = $collection->searchRecords(1, 1000, $params);
        //echo json_encode($result) ."\n\r";
        $this->assertEquals($expected, $result);
    }

    public function searchRecordsProvider() {
        return array(
            array(
                //Describe
                "Search fare's record by keyword [宅配].",
                // params
                array("keyword" => "宅配"),
                // expected
                json_decode('
                    {
                        "records": [{
                            "id": "2",
                            "amount": "70",
                            "type": "宅配",
                            "target_amount": "700",
                            "global": "1"
                        }, {
                            "id": "3",
                            "amount": "100",
                            "type": "宅配",
                            "target_amount": "20000",
                            "global": "0"
                        }],
                        "pageNo": 1,
                        "pageSize": 1000,
                        "totalPage": 1,
                        "recordCount": 2,
                        "totalRecord": 2
                    }
                ', true)
            ),
            array(
                //Describe
                "Search fare's record by groupbuying activity id [2].",
                // params
                array("activityId" => 2, "activityType" => "groupbuying"),
                // expected
                json_decode('
                    {
                        "records": [{
                            "id": "2",
                            "amount": "70",
                            "type": "宅配",
                            "target_amount": "700",
                            "global": "1"
                        }, {
                            "id": "3",
                            "amount": "100",
                            "type": "宅配",
                            "target_amount": "20000",
                            "global": "0"
                        }],
                        "pageNo": 1,
                        "pageSize": 1000,
                        "totalPage": 1,
                        "recordCount": 2,
                        "totalRecord": 2
                    }
                ', true)
            ),
        );
    }
}

?>