<?php
require_once(dirname(__FILE__) . '/../../phpunit/DbHero_TestCase.php');
require_once( FRAMEWORK_PATH . 'collections/ConsumerUserCollection.php' );

/**
 * Class FareCollectionTest
 */
class ConsumerUserCollectionTest extends DbHero_TestCase {

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable('consumer_user', dirname(__FILE__)."/ConsumerUserCollection/consumer_user.csv");
        return $dataSet;
    }

    /**
     * Test search fare's records
     * @dataProvider searchConsumerByKeyProvider
     */
    public function testSearchConsumerByKey($describe, $params, $expected) {
        echo "$describe \n\r ";
        $collection = new ConsumerUserCollection();
        $result = $collection->searchConsumerByKey(1, 1000, $params);
        $this->assertEquals($expected, $result);
    }

    public function searchConsumerByKeyProvider() {
        return array(
            array(
                //Describe
                "Search Consumer's record by keyword groupbuying.",
                // params
                array("keyword" => "groupbuying"),
                // expected
                json_decode('
                    {
                       "records":[
                          {
                             "id":"1",
                             "domain_name":"109life.com",
                             "group_id":"2",
                             "name":"groupbuying",
                             "account":"groupbuying@109life.com",
                             "email":"groupbuying@109life.com",
                             "hash":"------",
                             "salt":"------",
                             "oauth_id" : null,
                             "oauth_type" : null
                          }
                       ],
                       "pageNo":1,
                       "pageSize":1000,
                       "totalPage":1,
                       "recordCount":1,
                       "totalRecord":1
                     }
                ', true)
            ),
            array(
                //Describe
                "Search Consumer's error by keyword null.",
                // params
                array("keyword" => null),
                // expected
                json_decode('
                     {
                         "records": [{
                             "id": "1",
                             "domain_name": "109life.com",
                             "group_id": "2",
                             "name": "groupbuying",
                             "account": "groupbuying@109life.com",
                             "email": "groupbuying@109life.com",
                             "hash": "------",
                             "salt": "------",
                             "oauth_id": null,
                             "oauth_type": null
                         }, {
                             "id": "2",
                             "domain_name": "109life.com",
                             "group_id": "1",
                             "name": "leo",
                             "account": "leo@gmail",
                             "email": "leo@gmail",
                             "hash": "c49280069f194e727983ce0b7beecb20097c9180",
                             "salt": "VttZQ",
                             "oauth_id": null,
                             "oauth_type": null
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