<?php
require_once( dirname(dirname(__FILE__)) . '/config.php' );
require_once( FRAMEWORK_PATH . 'extends/MailHelper.php' );


/**
 * Class SkygoMailSender
 */
        $mailHelper = new MailHelper();
        $mailHelper->sendByRestApi('aaa', 's80000002@gmail.com',  's80000002@gmail.com', '123456789');


?>