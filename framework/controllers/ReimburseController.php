<?php
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/ReimburseCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/UnifiedOrderCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/AuthenticateHelper.php' );
require_once( FRAMEWORK_PATH . 'extends/MailHelper.php' );


/**
 * Class MailController
 *  PHP version 5.3
 *
 *  @package Controller
 *  @author Ares
 */
class ReimburseController extends RestController {

    public function __construct(&$dao=null) {
        parent::__construct();

        $this->collection = new ReimburseCollection($dao);
        $this->orderCollection = new UnifiedOrderCollection($dao);
    }

    public function getCondition() {
        $condition = array();
        $this->getQueryString("keyword", $condition);
        $this->getQueryString("serial", $condition);
        $this->getQueryString("reimburse", $condition);
        return $condition;
    }

    /**
    *   POST:    /reimburse/return/information
    *   post user return information
    *
    *   @param 
    */
    public function returnInformation() {
        $rowCount = array();
        $authHelper = new AuthenticateHelper();

        $attributes = array(
            "reimburse_serial"           =>date("ym").$authHelper->generateSalt(4),
            "order_id"                   =>$this->params("orderId"),
            "order_state"                =>$this->params("orderState"),
            "buy_name"                   =>$this->params("name"),
            "payment_type"               =>$this->params("paymentType"),
            "reimburse_account"          =>$this->params("account"),
            "reimburse_money"            =>$this->params("finalTotalPrice"),
            "create_datetime"            =>date("Y-m-d H:i:s"),
            "order_datetime"             =>$this->params("orderDatetime"),
            "pay_datetime"               =>$this->params("payDatetime"),
            "state"                      =>"0",
            "reimburse_name"             =>$this->params("bankUsername"),
            "reimburse_bank"             =>$this->params("bankName"),
            "reimburse_bank_branch"      =>$this->params("branches"),
            "remark"                     =>$this->params("remark"),
            "consumer_user_id"           =>$this->params("consumerUserId"),
            "order_serial"               =>$this->params("orderSerial")
        );

        

        $rowCount = $this->collection->create( $attributes );
        if( $rowCount == 0) {
            throw new DbOperationException("insert returned record to DB fail.");
        }
        return $attributes;
    }

    /**
     * GET: 	/reimburse/list/searchByAdmin/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * @param $pageNo
     * @param $pageSize
     * @return mixed
     */
    public function getReimburse($pageNo, $pageSize ){
        $actor 		= PlatformUser::instanceBySession();
        $condition 	= $this->getCondition();
        $records = $this->collection->setActor($actor)->
        searchRecords($pageNo,$pageSize, $condition);
        return $records;
    }

    public function StatusOver( $id ) {
        $actor 		= PlatformUser::instanceBySession();
        $attributes = array(
            'state' => 1,
            'order_state' => 24,
            'reimburse_datetime' =>date("Y-m-d H:i:s")
        );
        $models = $this->collection->getById($id);
        $count = $models->update($attributes);
        if($count != 1) {
            throw new DbOperationException("update Status  to DB fail.");
        }

        $orderId = $this->params("order_id");
        $stateText  = $this->params("stateText");
        $orderCollection = new UnifiedOrderCollection();
        $rowCount   = $orderCollection->updateStateByIds(array($orderId), $stateText);

        if($rowCount == 0) {
            throw new DataAccessResultException("Accept access but no changed.");
        }

        $records = $this->collection-> searchRecords(1,10, array('id' => $id));

        $this->send($records['records'][0]);






        return $records;
    }

    /**
     *  GET:    /reimburse/search/spec/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     *  Search reimburse spec list.
     *
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     */
    public function searchReimburseSpec($pageNo, $pageSize, $querystring) {
        $actor      = PlatformUser::instanceBySession();
        $condition  = $this->getCondition();
        $records    = $this->orderCollection
                                ->setActor($actor)
                                ->searchSpecRecords($pageNo, $pageSize, $condition);
        return $records;
    }

    public function send($info=array()) {

        // $returnUrl 		= self::RETURN_URL;
        $mail           = $info["email"];
        $name           = $info["name"];
        $orderSerial    = $info["order_serial"];
        $reimburseDatetime    = $info["reimburse_datetime"];
        

        $mailFrom   = "天GO系統通知";
        $subject 	= "【天GO】完成退款通知信";

        $text = "您好:<br><br>";
        $text .= "訂單編號：" . $orderSerial . "<br><br>";
        $text .= "於" . $reimburseDatetime . "已完成退款，如有任何問題，請您再聯絡我們，謝謝！<br><br>";
        $text .= "祝您  事事順心<br><br><br>";
        $text .= "天GO客服中心 敬上<br>";
        $text .= "===================================================================<br>";
        $text .= "此為系統自動發信，請勿直接回覆，若您對訂單有任何問題，請聯絡天GO客服，我們將以最快的時間回覆。<br>";
        $text .= "===================================================================<br>";

        $mailHelper = new MailHelper();
        $mailHelper->sendText($subject, $mail, $name, $text, $mailFrom);
    }

}




?>