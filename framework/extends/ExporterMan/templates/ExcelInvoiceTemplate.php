<?php

require_once( 'ExportTemplate.php' );

class ExcelInvoiceTemplate implements ExportTemplate
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
				
				"master_name" => "團購主",
				"master_bank_name" =>"銀行名稱",
				"master_bank_code" =>"銀行代碼",
				"master_bank_account" =>"帳戶",
				"master_bank_account_name" =>"戶名",

				"product_name" => "商品名稱",
				
				"spec_name" => "規格名稱",
				"spec_serial" => "規格品號",
				"spec_amount" => "數量",
				
				"fare_type" => "運費種類",
				"fare" => "運費價格",

				"product_total_price" => "單品售價",
				"final_total_price" => "總額",

				"spec_activity_type" => "銷售來源",
		
			);
	}

}

?>