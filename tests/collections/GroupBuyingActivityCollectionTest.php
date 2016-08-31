<?php
require_once(dirname(__FILE__) . '/../../phpunit/DbHero_TestCase.php');
require_once( FRAMEWORK_PATH . 'collections/GroupBuyingActivityCollection.php' );

/**
 * Class GeneralActivityCollectionTest
 */
class GroupBuyingActivityCollectionTest extends DbHero_TestCase {

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable('wholesale_product', dirname(__FILE__)."/GroupBuyingActivityCollection/wholesale_product.csv");
        $dataSet->addTable('gb_master_user', dirname(__FILE__)."/GroupBuyingActivityCollection/gb_master_user.csv");
        $dataSet->addTable('groupbuying_activity', dirname(__FILE__)."/GroupBuyingActivityCollection/groupbuying_activity.csv");
        $dataSet->addTable('unified_order', dirname(__FILE__)."/GroupBuyingActivityCollection/unified_order.csv");
        $dataSet->addTable('unified_returned', dirname(__FILE__)."/GroupBuyingActivityCollection/unified_returned.csv");

        return $dataSet;
    }

    /**
     * Test search fare's records
     * @dataProvider searchRecordsProvider
     */
    public function testSearchRecords($describe, $params, $expected) {
        echo "$describe \n\r ";
        $collection = new GroupBuyingActivityCollection();
        $result = $collection->searchRecords(1, 1000, $params);

        $this->assertEquals($expected, $result);
    }

    public function searchRecordsProvider() {
        return array(
            array(
                //Describe
                "Search groupBuying activity's record by keyword [熱賣] and actor [admin].",
                // params
                array("keyword" => "熱賣" , "actor" => "admin"),
                // expected
                json_decode('
                    {
                        "records":[
                            {
                                "id":"2",
                                "name":"IPHONE熱賣會",
                                "start_date":"2015-09-05 00:00:00",
                                "end_date":"2015-12-10 00:00:00",
                                "state":"0",
                                "buyer_counter":"1",
                                "returner_counter":"0",
                                "note":"",
                                "price":"8800",
                                "delivery_date":"0000-00-00 00:00:00",
                                "gbMasterName":"陳奕瑞",
                                "completed_delivery_counter":"",
                                "waiting_return_counter":"",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "wholesalePrice":"6000",
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
                "Search groupBuying activity's record by keyword [IPHONE] and actor [admin].",
                // params
                array("keyword" => "IPHONE" , "actor" => "admin"),
                // expected
                json_decode('
                        {
                        "records":[
                            {
                                "id":"2",
                                "name":"IPHONE熱賣會",
                                "start_date":"2015-09-05 00:00:00",
                                "end_date":"2015-12-10 00:00:00",
                                "state":"0",
                                "buyer_counter":"1",
                                "returner_counter":"0",
                                "note":"",
                                "price":"8800",
                                "delivery_date":"0000-00-00 00:00:00",
                                "gbMasterName":"陳奕瑞",
                                "completed_delivery_counter":"",
                                "waiting_return_counter":"",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "wholesalePrice":"6000",
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
                "Search groupBuying activity's record by id list [2,3] and actor [admin].",
                // params
                array("ids" => array(2,3) , "actor" => "admin"),
                // expected
                json_decode('
                        {
                        "records":[
                            {
                               "id":"2",
                                "name":"IPHONE熱賣會",
                                "start_date":"2015-09-05 00:00:00",
                                "end_date":"2015-12-10 00:00:00",
                                "state":"0",
                                "buyer_counter":"1",
                                "returner_counter":"0",
                                "note":"",
                                "price":"8800",
                                "delivery_date":"0000-00-00 00:00:00",
                                "gbMasterName":"陳奕瑞",
                                "completed_delivery_counter":"",
                                "waiting_return_counter":"",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "wholesalePrice":"6000",
                                "stateText":"started"
                            },
                            {
                                "id" : "3",
                                "name" : "尚未開始",
                                "start_date": "2015-12-10 00:00:00",
                                "end_date": "2015-12-20 00:00:00",
                                "state" : "0",
                                "buyer_counter" : "1",
                                "returner_counter" : "0",
                                "note" :"",
                                "price" : "8800",
                                "delivery_date":"0000-00-00 00:00:00",
                                "gbMasterName": "陳奕瑞",
                                "completed_delivery_counter":null,
                                "waiting_return_counter":null,
                                "productName":"iPhone 6S",
                                "suggestPrice": "9000",
                                "detail" :"活動說明",
                                "coverPhoto" :"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText": "產品說明",
                                "groupbuying": "1",
                                "youtubeUrl" : "https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType" : "1",
                                "productId" : "2",
                                "wholesalePrice" : "6000",
                                "stateText" : "prepare"
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
                "Search groupBuying activity's record by id list [2] and actor [admin].",
                // params
                array("ids" => array(2) , "actor" => "admin"),
                // expected
                json_decode('
                         {
                        "records":[
                            {
                                "id":"2",
                                "name":"IPHONE熱賣會",
                                "start_date":"2015-09-05 00:00:00",
                                "end_date":"2015-12-10 00:00:00",
                                "state":"0",
                                "buyer_counter":"1",
                                "returner_counter":"0",
                                "note":"",
                                "price":"8800",
                                "delivery_date":"0000-00-00 00:00:00",
                                "gbMasterName":"陳奕瑞",
                                "completed_delivery_counter":"",
                                "waiting_return_counter":"",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "wholesalePrice":"6000",
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
                "Search groupBuying activity's record by id state [started] and actor [admin].",
                // params
                array("state" => "started" , "actor" => "admin"),
                // expected
                json_decode('
                         {
                        "records":[
                            {
                                "id":"2",
                                "name":"IPHONE熱賣會",
                                "start_date":"2015-09-05 00:00:00",
                                "end_date":"2015-12-10 00:00:00",
                                "state":"0",
                                "buyer_counter":"1",
                                "returner_counter":"0",
                                "note":"",
                                "price":"8800",
                                "delivery_date":"0000-00-00 00:00:00",
                                "gbMasterName":"陳奕瑞",
                                "completed_delivery_counter":"",
                                "waiting_return_counter":"",
                                "productName":"iPhone 6S",
                                "suggestPrice":"9000",
                                "detail":"活動說明",
                                "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText":"產品說明",
                                "groupbuying":"1",
                                "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType":"1",
                                "productId":"2",
                                "wholesalePrice":"6000",
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
                "Search groupBuying activity's record by id state [prepare] and actor [admin].",
                // params
                array("state" => "prepare" , "actor" => "admin"),
                // expected
                json_decode('
                        {
                        "records":[
                            {
                                "id" : "3",
                                "name" : "尚未開始",
                                "start_date": "2015-12-10 00:00:00",
                                "end_date": "2015-12-20 00:00:00",
                                "state" : "0",
                                "buyer_counter" : "1",
                                "returner_counter" : "0",
                                "note" :"",
                                "price" : "8800",
                                "delivery_date":"0000-00-00 00:00:00",
                                "gbMasterName": "陳奕瑞",
                                "completed_delivery_counter":null,
                                "waiting_return_counter":null,
                                "productName":"iPhone 6S",
                                "suggestPrice": "9000",
                                "detail" :"活動說明",
                                "coverPhoto" :"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText": "產品說明",
                                "groupbuying": "1",
                                "youtubeUrl" : "https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType" : "1",
                                "productId" : "2",
                                "wholesalePrice" : "6000",
                                "stateText" : "prepare"
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
                "Search groupBuying activity's record by id state [waitingdelivery] and actor [admin].",
                // params
                array("state" => "waitingdelivery" , "actor" => "admin"),
                // expected
                json_decode('
                        {
                        "records":[
                            {
                                "id":"4",
                                "name":"已經結束",
                                "start_date":"2015-09-05 00:00:00",
                                "end_date":"2015-10-10 00:00:00",
                                "state" : "0",
                                "buyer_counter" : "1",
                                "returner_counter" : "0",
                                "note" :"",
                                "price" : "8800",
                                "delivery_date":"0000-00-00 00:00:00",
                                "gbMasterName": "簡妤倢",
                                "completed_delivery_counter":null,
                                "waiting_return_counter":null,
                                "productName":"iPhone 6S",
                                "suggestPrice": "9000",
                                "detail" :"活動說明",
                                "coverPhoto" :"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText": "產品說明",
                                "groupbuying": "1",
                                "youtubeUrl" : "https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType" : "1",
                                "productId" : "2",
                                "wholesalePrice" : "6000",
                                "stateText":"waitingdelivery"
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
                "Search groupBuying activity's record by id master id [3] and actor [admin].",
                // params
                array("masterId" => 3 , "actor" => "admin"),
                // expected
                json_decode('
                        {
                        "records":[
                            {
                                "id":"4",
                                "name":"已經結束",
                                "start_date":"2015-09-05 00:00:00",
                                "end_date":"2015-10-10 00:00:00",
                                "state" : "0",
                                "buyer_counter" : "1",
                                "returner_counter" : "0",
                                "note" :"",
                                "price" : "8800",
                                "delivery_date":"0000-00-00 00:00:00",
                                "gbMasterName": "簡妤倢",
                                "completed_delivery_counter":null,
                                "waiting_return_counter":null,
                                "productName":"iPhone 6S",
                                "suggestPrice": "9000",
                                "detail" :"活動說明",
                                "coverPhoto" :"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText": "產品說明",
                                "groupbuying": "1",
                                "youtubeUrl" : "https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType" : "1",
                                "productId" : "2",
                                "wholesalePrice" : "6000",
                                "stateText":"waitingdelivery"
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
                "Search groupBuying activity's record by  start date [2015-09-01 ~ 2015-09-05] and actor [admin].",
                // params
                array("startDateOpen" => "2015-09-01 00:00:00", "startDateClose" => "2015-09-05 00:00:00" , "actor" => "admin"),
                // expected
                json_decode('
                        {
                            "records":[
                                {
                                    "id":"2",
                                    "name":"IPHONE熱賣會",
                                    "start_date":"2015-09-05 00:00:00",
                                    "end_date":"2015-12-10 00:00:00",
                                    "state":"0",
                                    "buyer_counter":"1",
                                    "returner_counter":"0",
                                    "note":"",
                                    "price":"8800",
                                    "delivery_date":"0000-00-00 00:00:00",
                                    "gbMasterName":"陳奕瑞",
                                    "completed_delivery_counter":"",
                                    "waiting_return_counter":"",
                                    "productName":"iPhone 6S",
                                    "suggestPrice":"9000",
                                    "detail":"活動說明",
                                    "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                    "explainText":"產品說明",
                                    "groupbuying":"1",
                                    "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                    "mediaType":"1",
                                    "productId":"2",
                                    "wholesalePrice":"6000",
                                    "stateText":"started"
                                },
                                {
                                    "id":"4",
                                    "name":"已經結束",
                                    "start_date":"2015-09-05 00:00:00",
                                    "end_date":"2015-10-10 00:00:00",
                                    "state":"0",
                                    "buyer_counter":"1",
                                    "returner_counter":"0",
                                    "note":"",
                                    "price":"8800",
                                    "delivery_date":"0000-00-00 00:00:00",
                                    "gbMasterName":"簡妤倢",
                                    "completed_delivery_counter":null,
                                    "waiting_return_counter":null,
                                    "productName":"iPhone 6S",
                                    "suggestPrice":"9000",
                                    "detail":"活動說明",
                                    "coverPhoto":"tB0DUJG8evpnHSi71QCZ.jpeg",
                                    "explainText":"產品說明",
                                    "groupbuying":"1",
                                    "youtubeUrl":"https://www.youtube.com/embed/mMg153kNyDQ",
                                    "mediaType":"1",
                                    "productId":"2",
                                    "wholesalePrice":"6000",
                                    "stateText":"waitingdelivery"
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
                "Search groupBuying activity's record by  end date [2015-12-15 ~ 2015-12-20] and actor [admin].",
                // params
                array("endDateOpen" => "2015-12-15 00:00:00", "endDateClose" => "2015-12-20 00:00:00" , "actor" => "admin"),
                // expected
                json_decode('
                         {
                        "records":[
                            {
                                "id" : "3",
                                "name" : "尚未開始",
                                "start_date": "2015-12-10 00:00:00",
                                "end_date": "2015-12-20 00:00:00",
                                "state" : "0",
                                "buyer_counter" : "1",
                                "returner_counter" : "0",
                                "note" :"",
                                "price" : "8800",
                                "delivery_date":"0000-00-00 00:00:00",
                                "gbMasterName": "陳奕瑞",
                                "completed_delivery_counter":null,
                                "waiting_return_counter":null,
                                "productName":"iPhone 6S",
                                "suggestPrice": "9000",
                                "detail" :"活動說明",
                                "coverPhoto" :"tB0DUJG8evpnHSi71QCZ.jpeg",
                                "explainText": "產品說明",
                                "groupbuying": "1",
                                "youtubeUrl" : "https://www.youtube.com/embed/mMg153kNyDQ",
                                "mediaType" : "1",
                                "productId" : "2",
                                "wholesalePrice" : "6000",
                                "stateText" : "prepare"
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