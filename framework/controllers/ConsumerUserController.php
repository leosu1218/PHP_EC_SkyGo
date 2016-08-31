<?php
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/ConsumerUserCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/MailHelper.php' );



/**
 * Class ConsumerUserController
 *
 * PHP version 5.3
 *
 * @category Controller
 * @package Controller
 * @author Rex Chen <rexchen@synctech-infinity.com>,Jai Chien <jaichien@syncte-infinity.com>
 * @copyright 2015 synctech.com
 */
class ConsumerUserController extends RestController {

    private $collection;

    public function __construct(&$dao=null) {
        parent::__construct();
        $this->collection = new ConsumerUserCollection($dao);
    }

    /**
     * Get condition for search product method from http request querysting.
     * There will filte querystring key, values.
     *
     * @return array
     */
    public function getCondition() {
        $condition = array();
        $this->getQueryString("keyword", $condition);
        return $condition;
    }

    /**
     * /consumeruser/<pageNo:\d+>/<pageSize:\d+>
     * @param $pageNo
     * @param $pageSize
     * @return array
     * @throws AuthorizationException
     */
    public function getConsumer($pageNo, $pageSize ){
        $actor 		= PlatformUser::instanceBySession();
        $records = $this->collection->setActor($actor)->
        getRecords(array(), $pageNo,$pageSize, array(), 'id DESC');
        return $records;
    }

    /**
     * GET: 	/consumeruser/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     * @return array
     */
    public function getConsumerByKey($pageNo, $pageSize, $querystring) {
        $actor 		= PlatformUser::instanceBySession();
        $condition 	= $this->getCondition();
        $records 	= $this->collection->setActor($actor)
            ->searchConsumerByKey($pageNo, $pageSize, $condition);

        return $records;
    }

    /**
     * GET: 	/consumeruser/<consumerId:\d+>
     * @param $consumerId
     * @return array
     * @throws AuthorizationException
     */
    public function getConsumerById($consumerId) {
        $records = $this->collection->getRecordById($consumerId);
        return $records;
    }

    /**
     * @return array
     * @throws DbOperationException
     * @throws InvalidAccessParamsException
     */
    public function updateConsignee() {
        $attributes = array(
            'consignee_name' => $this->params("name"),
            'consignee_phone' => $this->params("phone"),
            'consignee_address' => $this->params("address")
        );
        $records = $this->collection->getRecordById($this->params("consumerId"));
        if($records['consignee_name'] != $attributes['consignee_name'] || $records['consignee_phone'] != $attributes['consignee_phone'] || $records['consignee_address'] != $attributes['consignee_address']){
            $count = $this->collection->getById($this->params("consumerId"))->update($attributes);
            if($count != 1) {
                throw new DbOperationException("update updateConsignee record to DB fail.");
            }
        }

        return array();
    }


    /**
     * GET:     /consumeruser/forget
     * @return array
     * @throws AuthorizationException
     */
    public function getNewPassword() {
        $collection = new ConsumerUserCollection();
        $accountUser  = array('account' => $this->params("account"));

        $record = $collection->getRecord($accountUser);

        $userId = $record['id'];
        $mailToName = $record['name'];
        $mailTo = $record['account'];
        $newpassword = $collection->createPassword();

        $salt = $collection->generateSalt();
        $hash = $collection->hash($newpassword,$salt);

        $model = $collection->getById( $userId );
        $result = $model->update( array("hash"=>$hash,"salt"=>$salt) );


        $subject = "重新申請天GO帳號密碼的通知信";
        $text = "您好<br><br>新密碼:".$newpassword."<br><br>請您用此新密碼重新登入天GO後，可在會員中心進行更換密碼哦！<br><br>※ 提醒您定期更新密碼，以確保帳號資料的安全，謝謝！<br><br>==========================================================================<br>此為系統自動發信，請勿直接回覆，若您有任何問題，請聯絡天GO客服，我們將以最快的時間回覆。<br>==========================================================================";

        $mailHelper = new MailHelper();
        $mailHelper->sendText($subject, $mailTo, $mailToName, $text);
        return $record;
        
    }

    public function checkUserEmail(){
        $collection = new ConsumerUserCollection();
        $accountUser  = array('account' => $this->params("account"));
        $record = $collection->getRecord($accountUser);
        if($record){
            return array();    
        }
    }

    /**
     * @return array
     * @throws DbOperationException
     * @throws InvalidAccessParamsException
     */
    public function updatePersonal() {
        $userId = $this->params("id");
        $attributes = array(
            'email' => $this->params("email"),
            'name' => $this->params("name"),
            'gender' => $this->params("gender"),
            'birthday' => $this->params("birthday"),
            'address' => $this->params("address"),
            'phone' => $this->params("phone")
        );
        $count = $this->collection->getById($userId)->update($attributes);
        if($count != 1) {
            throw new DbOperationException("update personal record to DB fail.");
        }
        return array();
    }



}

?>