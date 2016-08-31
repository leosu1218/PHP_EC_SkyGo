<?php


/**
 * interface NewebOtherServiceConfig
 *
 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
interface NewebOtherServiceConfig {

    public function MERCHANT_NUMBER();
    public function HASH_SALT();
    public function BANK_ID();
    public function DUE_DAYS();

    //user received
    public function RETURN_URL();
    //server received
    public function LOG_PREFIX();
    public function LOG_PATH();
    public function PROVIDER_URL();
}




?>