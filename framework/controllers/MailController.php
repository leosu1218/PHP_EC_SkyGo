<?php
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'extends/MailHelper.php' );

/**
 * Class MailController
 *  PHP version 5.3
 *
 *  @category NeteXss
 *  @package Controller
 *  @author Rex Chen <rexchen@synctech-infinity.com>
 *  @copyright 2015 synctech.com
 */
class MailController extends RestController {

    /**
     * POST: /mail
     * Send mail's agent api
     */
    public function send() {
        $mailHelper = new MailHelper();
        $checksum   = $this->params("checksum");
        $subject    = $this->params("subject");
        $mailTo     = $this->params("mailTo");
        $mailToName = $this->params("mailToName");
        $text       = $this->params("text");
        $mailFromName = $this->params("mailFromName");

        if($mailHelper->isValidCheckSum($checksum, $subject, $mailTo, $mailToName, $text)) {
            $mailHelper->sendText($subject, $mailTo, $mailToName, $text ,$mailFromName);
            return array();
        }
        else {
            throw new AuthorizationException("Invalid mail send request");
        }
    }

    public function test() {

        $subject = "test by api";
        $mailTo = "chen.cyr@gmail.com";
        $mailToName = "api send";
        $text = "test test api";

        $mailHelper = new MailHelper();
        $mailHelper->sendByRestApi($subject, $mailTo, $mailToName, $text);
        return array();
    }

}




?>