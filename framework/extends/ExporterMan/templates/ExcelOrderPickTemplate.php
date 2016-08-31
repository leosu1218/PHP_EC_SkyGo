<?php

require_once( 'ExportTemplate.php' );

class ExcelOrderPickTemplate implements ExportTemplate
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
            "serial"=>"訂單編號",
            "consumer_user_id"=>"會員ID",
            "buyer_name"=>"訂購人",
            "spec_serial" => "品號",
            "product_name" => "商品名稱",
            "spec_name" => "商品規格",
            "spec_amount" => "數量",
            "spec_total_price" => "金額小計",
            "fare" => "運費",
            "final_total_price" => "實付額",
            "consumer_remark" => "購物車備註",
            "payment_type" => "付款別",
            "create_datetime" => "訂購日",
            "pay_notify_datetime" => "付款日",
            "stateText" => "訂單狀態",
            "receiver_name" => "收件人",
            "receiver_phone_number" => "收件人行動電話",
            "receiver_address" => "收件人地址",
            "delivery_datetime" => "出貨日",
            "delivery_number" => "配送單號",
            "inventory_process" => "發票資訊",
            "taxID" => "統編",
            "companyName" => "發票抬頭"
			);
	}

}

?>