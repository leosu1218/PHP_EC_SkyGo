<?php

require_once( 'ExportTemplate.php' );

class ExcelReturnedSalesTemplate implements ExportTemplate
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
            "ur_id"=>"退單序號",
            "serial"=>"訂單編號",
            "consumer_user_id"=>"會員ID",
            "spec_serial" => "品號",
            "product_name" => "商品名稱",
            "spec_name" => "商品規格",
            "spec_amount" => "數量",
            "product_total_price" => "金額小計",
            "fare" => "運費",
            "ur_create_datetime" => "退貨單建檔日",
            "remark" => "退貨原因",
            "stateText" => "退貨單狀態",
            "ur_delivery_datetime" => "物流取貨通知日",
            "receiver_name" => "取件人",
            "receiver_phone_number" => "取件電話",
            "receiver_address" => "取件地址",
            "ur_remark" => "結案原因",
            "ur_close_datetime" => "結案時間"
			);
	}

}

?>