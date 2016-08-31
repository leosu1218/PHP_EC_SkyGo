<?php
interface NotifySender
{
    /**
     * @param array $list
     * @return mixed
     */
    public function start($text='' , $subject);
}

?>