<?php
require_once(dirname(__FILE__) . '/../../phpunit/DbHero_TestCase.php');
require_once( FRAMEWORK_PATH . 'collections/GeneralActivityCollection.php' );

/**
 * Class GeneralActivityCollectionTest
 */
class GeneralActivityCollectionTest extends DbHero_TestCase {

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable('platform_user', dirname(__FILE__)."/GeneralActivityCollection/platform_user.csv");
        $dataSet->addTable('wholesale_product', dirname(__FILE__)."/GeneralActivityCollection/wholesale_product.csv");
        $dataSet->addTable('general_activity', dirname(__FILE__)."/GeneralActivityCollection/general_activity.csv");
        return $dataSet;
    }

    /**
     * Test search fare's records
     * @dataProvider searchRecordsProvider
     */
    public function testSearchRecords($describe, $params, $expected) {
        echo "$describe \n\r ";
        $collection = new GeneralActivityCollection();
        $result = $collection->searchRecords(1, 1000, $params);

        $this->assertEquals($expected, $result);
    }

    public function searchRecordsProvider() {
        return array(
            array(
                //Describe
                "Search general activity's record by keyword [原廠] and actor [admin].",
                // params
                array("keyword" => "原廠" , "actor" => "admin"),
                // expected
                json_decode('
                    {
                        "records":[
                            {
                                "id":"1",
                                "name":"原廠出售IPHONE 6S",
                                "start_date":"2015-10-01 00:00:00",
                                "end_date":"2015-12-30 00:00:00",
                                "state":"0",
                                "buyer_counter":"0",
                                "returner_counter":"0",
                                "note":"",
                                "price":"8900",
                                "delivery_date":"0000-00-00 00:00:00",
                                "masterName":"admin",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "wholesalePrice":"6000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "weight" : "0",
                                "stateText":"started"
                            }
                        ],
                        "pageNo":1,
                        "pageSize":1000,
                        "totalPage":1,
                        "recordCount":1
                        }
                ', true)
            ),
            array(
                //Describe
                "Search general activity's record by keyword [熱賣] and actor [admin]. ",
                // params
                array("keyword" => "熱賣" , "actor" => "admin"),
                // expected
                json_decode('
                   {
                        "records":[
                            {
                                "id":"2",
                                "name":"限時熱賣IPHONE 6S",
                                "start_date":"2015-10-01 00:00:00",
                                "end_date":"2015-12-30 00:00:00",
                                "state":"0",
                                "buyer_counter":"0",
                                "returner_counter":"0",
                                "note":"",
                                "price":"7999",
                                "delivery_date":"0000-00-00 00:00:00",
                                "masterName":"admin",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "wholesalePrice":"6000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "weight" : "0",
                                "stateText":"started"
                            }
                        ],
                        "pageNo":1,
                        "pageSize":1000,
                        "totalPage":1,
                        "recordCount":1
                        }
                ', true)
            ),
            array(
                //Describe
                "Search general activity's record by keyword [IPHONE] and actor [admin].",
                // params
                array("keyword" => "IPHONE" , "actor" => "admin"),
                // expected
                json_decode('
                        {
                        "records":[
                            {
                                "id":"1",
                                "name":"原廠出售IPHONE 6S",
                                "start_date":"2015-10-01 00:00:00",
                                "end_date":"2015-12-30 00:00:00",
                                "state":"0",
                                "buyer_counter":"0",
                                "returner_counter":"0",
                                "note":"",
                                "price":"8900",
                                "delivery_date":"0000-00-00 00:00:00",
                                "masterName":"admin",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "wholesalePrice":"6000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "weight" : "0",
                                "stateText":"started"
                            },
                            {
                                "id":"2",
                                "name":"限時熱賣IPHONE 6S",
                                "start_date":"2015-10-01 00:00:00",
                                "end_date":"2015-12-30 00:00:00",
                                "state":"0",
                                "buyer_counter":"0",
                                "returner_counter":"0",
                                "note":"",
                                "price":"7999",
                                "delivery_date":"0000-00-00 00:00:00",
                                "masterName":"admin",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "wholesalePrice":"6000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "weight" : "0",
                                "stateText":"started"
                            }
                        ],
                        "pageNo":1,
                        "pageSize":1000,
                        "totalPage":1,
                        "recordCount":2
                        }
                ', true)
            ),
            array(
                //Describe
                "Search general activity's record by id list [1,2] and actor [admin].",
                // params
                array("ids" => array(1,2) , "actor" => "admin"),
                // expected
                json_decode('
                        {
                        "records":[
                            {
                                "id":"1",
                                "name":"原廠出售IPHONE 6S",
                                "start_date":"2015-10-01 00:00:00",
                                "end_date":"2015-12-30 00:00:00",
                                "state":"0",
                                "buyer_counter":"0",
                                "returner_counter":"0",
                                "note":"",
                                "price":"8900",
                                "delivery_date":"0000-00-00 00:00:00",
                                "masterName":"admin",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "wholesalePrice":"6000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "weight" : "0",
                                "stateText":"started"
                            },
                            {
                                "id":"2",
                                "name":"限時熱賣IPHONE 6S",
                                "start_date":"2015-10-01 00:00:00",
                                "end_date":"2015-12-30 00:00:00",
                                "state":"0",
                                "buyer_counter":"0",
                                "returner_counter":"0",
                                "note":"",
                                "price":"7999",
                                "delivery_date":"0000-00-00 00:00:00",
                                "masterName":"admin",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "wholesalePrice":"6000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "weight" : "0",
                                "stateText":"started"
                            }
                        ],
                        "pageNo":1,
                        "pageSize":1000,
                        "totalPage":1,
                        "recordCount":2
                        }
                ', true)
            ),
            array(
                //Describe
                "Search general activity's record by id list [2] and actor [admin].",
                // params
                array("ids" => array(2) , "actor" => "admin"),
                // expected
                json_decode('
                         {
                        "records":[
                            {
                                "id":"2",
                                "name":"限時熱賣IPHONE 6S",
                                "start_date":"2015-10-01 00:00:00",
                                "end_date":"2015-12-30 00:00:00",
                                "state":"0",
                                "buyer_counter":"0",
                                "returner_counter":"0",
                                "note":"",
                                "price":"7999",
                                "delivery_date":"0000-00-00 00:00:00",
                                "masterName":"admin",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "wholesalePrice":"6000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "weight" : "0",
                                "stateText":"started"
                            }
                        ],
                        "pageNo":1,
                        "pageSize":1000,
                        "totalPage":1,
                        "recordCount":1
                        }
                ', true)
            ),
            array(
                //Describe
                "Search general activity's record by id state [started] and actor [admin].",
                // params
                array("state" => "started" , "actor" => "admin"),
                // expected
                json_decode('
                         {
                        "records":[
                            {
                                "id":"1",
                                "name":"原廠出售IPHONE 6S",
                                "start_date":"2015-10-01 00:00:00",
                                "end_date":"2015-12-30 00:00:00",
                                "state":"0",
                                "buyer_counter":"0",
                                "returner_counter":"0",
                                "note":"",
                                "price":"8900",
                                "delivery_date":"0000-00-00 00:00:00",
                                "masterName":"admin",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "wholesalePrice":"6000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "weight" : "0",
                                "stateText":"started"
                            },
                            {
                                "id":"2",
                                "name":"限時熱賣IPHONE 6S",
                                "start_date":"2015-10-01 00:00:00",
                                "end_date":"2015-12-30 00:00:00",
                                "state":"0",
                                "buyer_counter":"0",
                                "returner_counter":"0",
                                "note":"",
                                "price":"7999",
                                "delivery_date":"0000-00-00 00:00:00",
                                "masterName":"admin",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "wholesalePrice":"6000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "weight" : "0",
                                "stateText":"started"
                            }
                        ],
                        "pageNo":1,
                        "pageSize":1000,
                        "totalPage":1,
                        "recordCount":2
                        }
                ', true)
            ),
            array(
                //Describe
                "Search general activity's record by id state [prepare] and actor [admin].",
                // params
                array("state" => "prepare" , "actor" => "admin"),
                // expected
                json_decode('
                        {
                        "records":[
                            {
                                "id":"3",
                                "name":"尚未開始",
                                "start_date":"2015-12-20 00:00:00",
                                "end_date":"2015-12-30 00:00:00",
                                "state":"0",
                                "buyer_counter":"0",
                                "returner_counter":"0",
                                "note":"",
                                "price":"7999",
                                "delivery_date":"0000-00-00 00:00:00",
                                "masterName":"admin",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "wholesalePrice":"6000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "weight" : "0",
                                "stateText":"prepare"
                            }
                        ],
                        "pageNo":1,
                        "pageSize":1000,
                        "totalPage":1,
                        "recordCount":1
                        }
                ', true)
            ),
            array(
                //Describe
                "Search general activity's record by id state [completed] and actor [admin].",
                // params
                array("state" => "completed" , "actor" => "admin"),
                // expected
                json_decode('
                        {
                        "records":[
                            {
                                "id":"4",
                                "name":"已經結束",
                                "start_date":"2015-10-01 00:00:00",
                                "end_date":"2015-10-02 00:00:00",
                                "state":"0",
                                "buyer_counter":"0",
                                "returner_counter":"0",
                                "note":"",
                                "price":"7999",
                                "delivery_date":"0000-00-00 00:00:00",
                                "masterName":"user2",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "wholesalePrice":"6000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "weight" : "0",
                                "stateText":"completed"
                            }
                        ],
                        "pageNo":1,
                        "pageSize":1000,
                        "totalPage":1,
                        "recordCount":1
                        }
                ', true)
            ),
            array(
                //Describe
                "Search general activity's record by id master id [2] and actor [admin].",
                // params
                array("masterId" => 2 , "actor" => "admin"),
                // expected
                json_decode('
                        {
                        "records":[
                            {
                                "id":"4",
                                "name":"已經結束",
                                "start_date":"2015-10-01 00:00:00",
                                "end_date":"2015-10-02 00:00:00",
                                "state":"0",
                                "buyer_counter":"0",
                                "returner_counter":"0",
                                "note":"",
                                "price":"7999",
                                "delivery_date":"0000-00-00 00:00:00",
                                "masterName":"user2",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "wholesalePrice":"6000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "weight" : "0",
                                "stateText":"completed"
                            }
                        ],
                        "pageNo":1,
                        "pageSize":1000,
                        "totalPage":1,
                        "recordCount":1
                        }
                ', true)
            ),
            array(
                //Describe
                "Search general activity's record by id start date [2015-10-01 ~ 2015-10-05] and actor [admin].",
                // params
                array("startDateOpen" => "2015-10-01 00:00:00", "startDateClose" => "2015-10-05 00:00:00" , "actor" => "admin"),
                // expected
                json_decode('
                        {
                            "records":[
                                {
                                    "id":"1",
                                    "name":"原廠出售IPHONE 6S",
                                    "start_date":"2015-10-01 00:00:00",
                                    "end_date":"2015-12-30 00:00:00",
                                    "state":"0",
                                    "buyer_counter":"0",
                                    "returner_counter":"0",
                                    "note":"",
                                    "price":"8900",
                                    "delivery_date":"0000-00-00 00:00:00",
                                    "masterName":"admin",
                                    "productName":"iPhone 6S",
                                    "suggestPrice":"9000",
                                    "wholesalePrice":"6000",
                                    "detail":"活動說明",
                                    "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                    "explainText":"產品說明",
                                    "groupbuying":"1",
                                    "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                    "mediaType":"1",
                                    "productId":"2",
                                    "weight" : "0",
                                    "stateText":"started"
                                },
                                {
                                    "id":"2",
                                    "name":"限時熱賣IPHONE 6S",
                                    "start_date":"2015-10-01 00:00:00",
                                    "end_date":"2015-12-30 00:00:00",
                                    "state":"0",
                                    "buyer_counter":"0",
                                    "returner_counter":"0",
                                    "note":"",
                                    "price":"7999",
                                    "delivery_date":"0000-00-00 00:00:00",
                                    "masterName":"admin",
                                    "productName":"iPhone 6S",
                                    "suggestPrice":"9000",
                                    "wholesalePrice":"6000",
                                    "detail":"活動說明",
                                    "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                    "explainText":"產品說明",
                                    "groupbuying":"1",
                                    "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                    "mediaType":"1",
                                    "productId":"2",
                                    "weight" : "0",
                                    "stateText":"started"
                                },
                                {
                                    "id":"4",
                                    "name":"已經結束",
                                    "start_date":"2015-10-01 00:00:00",
                                    "end_date":"2015-10-02 00:00:00",
                                    "state":"0",
                                    "buyer_counter":"0",
                                    "returner_counter":"0",
                                    "note":"",
                                    "price":"7999",
                                    "delivery_date":"0000-00-00 00:00:00",
                                    "masterName":"user2",
                                    "productName":"iPhone 6S",
                                    "suggestPrice":"9000",
                                    "wholesalePrice":"6000",
                                    "detail":"活動說明",
                                    "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                    "explainText":"產品說明",
                                    "groupbuying":"1",
                                    "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                    "mediaType":"1",
                                    "productId":"2",
                                    "weight" : "0",
                                    "stateText":"completed"
                                }
                            ],
                            "pageNo":1,
                            "pageSize":1000,
                            "totalPage":1,
                            "recordCount":3
                            }
                ', true)
            ),
            array(
                //Describe
                "Search general activity's record by id end date [2015-10-01 ~ 2015-10-05] and actor [admin].",
                // params
                array("endDateOpen" => "2015-10-01 00:00:00", "endDateClose" => "2015-10-05 00:00:00" , "actor" => "admin"),
                // expected
                json_decode('
                         {
                        "records":[
                            {
                                "id":"4",
                                "name":"已經結束",
                                "start_date":"2015-10-01 00:00:00",
                                "end_date":"2015-10-02 00:00:00",
                                "state":"0",
                                "buyer_counter":"0",
                                "returner_counter":"0",
                                "note":"",
                                "price":"7999",
                                "delivery_date":"0000-00-00 00:00:00",
                                "masterName":"user2",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "wholesalePrice":"6000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "weight" : "0",
                                "stateText":"completed"
                            }
                        ],
                        "pageNo":1,
                        "pageSize":1000,
                        "totalPage":1,
                        "recordCount":1
                        }
                ', true)
            ),
        );
    }
}

?>