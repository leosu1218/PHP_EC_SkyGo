<?php

require_once( 'ExportTemplate.php' );

class ExcelReimburseTemplate implements ExportTemplate
{

	public function getConfig()
	{
		return array(
				"creator"=>"skygo",
				"lastModifiedBy"=>"skygo",
				"title"=>"order picking Document",
				"subject"=>"order picking Document",
				"description"=>"",
				"keywords"=>"office PHPExcel php",
				"category"=>"file"
			);
	}

	public function getField()
	{
		return array(
            "reimburse_serial"=>"退款序號",
            "reimburse_create_datetime"=>"退款建檔日",
            "stateText" => "訂單狀態",
            "serial"=>"訂單編號",
            "consumer_user_id"=>"會員ID",
            "buyer_name"=>"訂購人",
            "spec_serial" => "品號",
            "product_name" => "商品名稱",
            "spec_name" => "商品規格",
            "spec_amount" => "數量",
            "spec_total_price" => "金額小計",
            "fare" => "運費",
            "reimburse_money" => "退款金額",
            "payment_type" => "付款別",
            "create_datetime" => "訂購日",
            "pay_notify_datetime" => "付款日",
            "reimburse_name" => "退款戶名",
            "reimburse_bank_branch" => "銀行別",
            "reimburse_account" => "銀行帳號",
            "reimburse_datetime" => "退款日",
            "reimburse_state" => "狀態"
			);
	}

}

?>