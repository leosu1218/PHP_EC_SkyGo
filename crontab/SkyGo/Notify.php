<?php
require_once( CRONTAB_PATH . 'SkyGO/SygoOffTheShelfContentGenerator.php' );
require_once( CRONTAB_PATH . 'SkyGO/SkygoMailSender.php' );


class Notify {
    /**
     * @param NotifyContentGenerator $generator
     */
    public function __construct(NotifyContentGenerator $generator) {
        $this->generator = $generator;
    }

    /**
     * @param NotifySender $sender
     */
    public function send(NotifySender $sender , $subject) {
        $content = $this->generator->create();
        $sender->start($content ,$subject);
    }
}

?>