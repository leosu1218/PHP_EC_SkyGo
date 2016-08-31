<?php
require_once( dirname(dirname(__FILE__)) . '/config.php' );
require_once( FRAMEWORK_PATH . 'extends/MailHelper.php' );
require_once( CRONTAB_PATH . 'NotifySender.php' );


/**
 * Class SkygoMailSender
 */
class SkygoMailSender implements NotifySender {

    public function __construct($list) {
        $this->list = $list;
    }

    public function start($text='' ,$subject) {
        $systemLists = $this->list;

        $mailHelper = new MailHelper();

        foreach($systemLists as $systemList ){
            $mailHelper->sendByRestApi($subject, $systemList['email'],  $systemList['email'], $text);
        }
    }

}
?>