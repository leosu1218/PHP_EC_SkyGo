<?php


/**
 * interface NewebServiceConfig
 *
 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
interface NewebServiceConfig {

	public function MERCHANT_NUMBER();
	public function HASH_SALT();
	public function APPROVE_FLAG();
	public function DEPOSIT_FLAG();
	public function ENGLISH_MODE();
	public function IPHONE_PAGE_FLAG();

	//user received
	public function RETURN_URL();
	//server received 
	public function NOTIFY_URL();
	public function LOG_PREFIX();
    public function LOG_PATH();
	public function PROVIDER_URL();
}




?>