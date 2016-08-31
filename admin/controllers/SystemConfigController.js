/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs', 'message'], function (angular, app, createController, configs, message) {

	//tool
	function TableLoadByRecord( $table_instance, records, pageNo, pageSize )
	{
		var count  = records.length;
		$table_instance.load({
			records: records,
			recordCount: (count||0),
			totalPage: Math.ceil((count||0)/pageSize),
			pageNo: pageNo,
			pageSize: pageSize,
		});
	}
	//tool

	function TableLoadByUrl( $table_instance, api, pageNo, pageSize ,test)
	{
		$table_instance.loadByUrl( api, pageNo, pageSize,
			test, 

			function(data, status, headers, config) {}
		);
	}

	//fare
	function FareTableSetting( $scope )
	{
		$scope.fareTable.configField(
    		[
    			{
					attribute:"id",
					name:"ID"
				},
				{
					attribute:"amount",
					name:"未滿額之運費"
				},
				{
					attribute:"target_amount",
					name:"滿額價"
				},
				{
					attribute:"type",
					name:"配送方式"
				},
				{				
					attribute:"control", 
					name: "控制",
					controls: [
						{
							type: "button",
							icon: "fa-trash-o",
							click: function(row, attribute) {
								var api = configs.api.systemConfig + "/fare/" + row.id;
								$scope.destroy(api,function(data, status, headers, config){
									if( status!=200 ){
										$scope.message("刪除物流配送方案失敗，可能是因為網路關係。");
									}
									$scope.fareTableReload();
								});
							}
						},
					]
				},
			]
		);
		$scope.fareTable.onRowClick(function(row, field, tableRow) {			
			if( field != 'control' ){
				FareUpdate( $scope, row );
			}
		});
	}

	function FareAddEvent( $scope )
	{
		$scope.modal.config({
			controls:[
				{
					position:"header",
					type:"text",
					label:"新增 ",
				},
				{
					position 		:"body",
					type 			:"number",
					attribute		:"",
					attributeName	:"target_amount",
					label			:"滿額價"
				},
				{
					position 		:"body",
					type 			:"number",
					attribute		:"",
					attributeName	:"amount",
					label			:"未滿額之運費"
				},
				{
					position 		:"body",
					type 			:"input",
					attribute		:"",
					attributeName	:"type",
					label			:"配送方式"
				},
				{
					position:"footer",
					type:"button",
					label:"確定",
					target:function( data ){
						var formData = {
							amount:data.amount,
							target_amount:data.target_amount,
							global:0,
							type:data.type,
						};
						$scope.create( configs.api.systemConfig+"/fare", formData, function(data, status, headers, config){
							if( status!=200 ){
								$scope.message("新增物流配送方案失敗，可能是因為網路關係。");
							}
							$scope.fareTableReload();
						});
					}
				}
			]
		});

		$scope.modal.show();
	}

	function FareTableLoad( $scope, configs )
	{
		var api = configs.api.systemConfig + "/fare/list";
		var pageNo = 1;
		var pageSize = 100;

		TableLoadByUrl( $scope.fareTable, api, pageNo, pageSize, function(data, status, headers, config) {
			$scope.fareItem = data;
		});
	}

	function FareUpdate( $scope, data ){

		$scope.modal.config({
			controls:[
				{
					position:"header",
					type:"text",
					label:"更新",
				},
				{
					position 		:"body",
					type 			:"number",
					attribute		:data.target_amount,
					attributeName	:"target_amount",
					label			:"滿額價"
				},
				{
					position 		:"body",
					type 			:"number",
					attribute		:data.amount,
					attributeName	:"amount",
					label			:"未滿額之運費"
				},
				{
					position 		:"body",
					type 			:"input",
					attribute		:data.type,
					attributeName	:"type",
					label			:"配送方式"
				},
				{
					position:"footer",
					type:"button",
					label:"確定",
					target:function( result ){
						var formData = {
							amount:result.amount,
							target_amount:result.target_amount,
							global:0,
							type:result.type,
						};
						var api = configs.api.systemConfig + "/fare/" + data.id;
						$scope.update(api, formData, function(data, status, headers, config){
							if( status!=200 ){
								$scope.message("更新物流配送方案失敗，可能是因為網路關係。");
							}
							$scope.fareTableReload();
						});
					}
				}
			]
		});

		$scope.modal.show();
		
	}

	//logistics
	function LogisticsTableSetting( $scope )
	{
		$scope.logisticsTable.configField(
    		[
    			{
					attribute:"program_name",
					name:"方案名稱"
				},
				{
					attribute:"pay_type",
					name:"付款方式",
				},
				{
					attribute:"delivery_type",
					name:"配送方式"
				},
				{				
					attribute:"control", 
					name: "控制",
					controls: [
						{
							type: "button",
							icon: "fa-trash-o",
							click: function(row, attribute) {
								var api = configs.api.systemConfig + "/delivery/" + row.id;
								$scope.destroy(api,function(data, status, headers, config){
									if( status!=200 ){
										$scope.message("刪除資料失敗，可能是因為網路關係。");
									}
									$scope.deliveryTableReload();
								});
							}
						},
					]
				},
			]
		);
		$scope.logisticsTable.onRowClick(function(row, field, tableRow) {			
			if( field != 'control' ){
				LogisticsUpdate( $scope, row );
			}
		});
	}

	function LogisticsAddEvent( $scope )
	{
		$scope.restructuring($scope.fareItem);
		var logisticsArray = {
			controls:[
				{
					position:"header",
					type:"text",
					label:"新增 ",
				},
				{
					position 		:"body",
					type 			:"input",
					attribute		:"",
					attributeName	:"program_name",
					label			:"方案名稱"
				},
				{
					position 		:"body",
					type 			:"select",
					attribute		:{
						list:[{
							text:"信用卡線上刷卡",
							attribute:"neweb",
						},{
							text:"超商繳款",
							attribute:"MMK",
						},{
							text:"虛擬帳號轉帳",
							attribute:"ATM",
						}],
						selected:{
							text:"信用卡線上刷卡",
							attribute:"neweb",
						}
					},
					attributeName	:"pay_type",
					label			:"付款方式"
				},
				{
					position:"footer",
					type:"button",
					label:"確定",
					target:function( data ){
						var formData = {
							program_name:data.program_name,
							pay_type:data.pay_type.attribute,
							delivery_type:data.delivery_type.attribute
						};

						$scope.create( configs.api.systemConfig+"/delivery", formData, function(data, status, headers, config){
							if( status!=200 ){
								$scope.message("新增資料失敗，可能是因為網路關係。");
							}
							$scope.deliveryTableReload();
						});
					}
				}
			]
		};
		logisticsArray.controls.push($scope.select_type);
		$scope.modal.config(logisticsArray);
		$scope.modal.show();
	}

	function LogisticsTableLoad( $scope, configs )
	{
		var api = configs.api.systemConfig + "/delivery/list";
		var pageNo = 1;
		var pageSize = 100;
	
		TableLoadByUrl( $scope.logisticsTable, api, pageNo, pageSize, function(data, status, headers, config) {
			$scope.logisticsItem = data.records;
			for(var key in $scope.logisticsItem){
				if($scope.logisticsItem[key].pay_type == "MMK"){
					$scope.logisticsItem[key].pay_type = "超商繳款";
				}else if($scope.logisticsItem[key].pay_type == "neweb"){
					$scope.logisticsItem[key].pay_type = "信用卡線上刷卡";
				}else if($scope.logisticsItem[key].pay_type == "ATM"){
					$scope.logisticsItem[key].pay_type = "虛擬帳號轉帳";
				}
			}
			for(var key in $scope.logisticsItem){
				if($scope.logisticsItem[key].delivery_type){
					$scope.logisticsItem[key].delivery_type = $scope.changeName("delivery_type", $scope.logisticsItem[key].delivery_type);}
			}
			
		} );
	}

	function LogisticsUpdate( $scope, data ){
		$scope.restructuring($scope.fareItem, data);
		var logisticsArrayUpdate = {
			controls:[
				{
					position:"header",
					type:"text",
					label:"更新",
				},
				{
					position 		:"body",
					type 			:"text",
					attribute		:data.program_name,
					attributeName	:"program_name",
					label			:"方案名稱"
				},
				{
					position 		:"body",
					type 			:"select",
					attribute		:{
						list:[{
							text:"信用卡線上刷卡",
							attribute:"neweb",
						},{
							text:"超商繳款",
							attribute:"MMK",
						},{
							text:"虛擬帳號轉帳",
							attribute:"ATM",
						}],
						selected:{
							text:$scope.changeName("pay_type",data.pay_type),
							attribute:data.pay_type,
						}
					},
					attributeName	:"pay_type",
					label			:"付款方式"
				},
				{
					position:"footer",
					type:"button",
					label:"更新",
					target:function( result ){
						var formData = {
							program_name:result.program_name,
							pay_type:$scope.rename("pay_type",result.pay_type.attribute),
							delivery_type:$scope.rename("delivery_type",result.delivery_type.attribute),
						};
						var api = configs.api.systemConfig + "/delivery/" + data.id;
						$scope.update(api, formData, function(data, status, headers, config){
							if( status!=200 ){
								$scope.message("更新物流配送方案失敗，可能是因為網路關係。");
							}
							$scope.deliveryTableReload();
						});
					}
				}
			]
		};
		logisticsArrayUpdate.controls.push($scope.select_type);
		$scope.modal.config(logisticsArrayUpdate);

		$scope.modal.show();
		
	}

	//product event
	function ProductEventTableSetting( $scope )
	{
		$scope.productEventTable.configField(
    		[
				{
					attribute:"id",
					name:"ID"
				},
				{
					attribute:"email",
					name:"信箱"
				},
				{				
					attribute:"control", 
					name: "控制",
					controls: [
						{
							type: "button",
							icon: "fa-trash-o",
							click: function(row, attribute) {
								var api = configs.api.systemConfig + "/productevent/" + row.id;
								$scope.destroy(api,function(data, status, headers, config){
									if( status!=200 ){
										$scope.message("刪除信箱通知失敗，可能是因為網路關係。");
									}
									$scope.productEventTableReload();
								});
							}
						},
						
					]
				},
			]
		);

		$scope.productEventTable.onRowClick(function(row, field, tableRow) {			
			if( field != 'control' ){
				ProductEventUpdate( $scope, row );
			}
		});
	}

	function ProductEventAdd( $scope )
	{
		$scope.modal.config({
			controls:[
				{
					position:"header",
					type:"text",
					label:"新增 商品事件-信箱通知",
				},
				{
					position 		:"body",
					type 			:"input",
					attribute		:"",
					attributeName	:"email",
					label			:"信箱"
				},
				{
					position:"footer",
					type:"button",
					label:"確定",
					target:function( data ){
						$scope.create( configs.api.systemConfig+"/productevent", data, function(data, status, headers, config){
							if( status!=200 ){
								$scope.message("新增信箱通知失敗，可能是因為網路關係。");
							}
							$scope.productEventTableReload();
						});
					}
				}
			]
		});

		$scope.modal.show();
	}

	function ProductEventTableLoad( $scope )
	{
		var api = configs.api.systemConfig + "/productevent/list";
		var pageNo = 1;
		var pageSize = 100;

		TableLoadByUrl( $scope.productEventTable, api, pageNo, pageSize, function(data, status, headers, config) {} );
	}

	function ProductEventUpdate( $scope, data ){

		$scope.modal.config({
			controls:[
				{
					position:"header",
					type:"text",
					label:"更新",
				},
				{
					position 		:"body",
					type 			:"input",
					attribute		:data.email,
					attributeName	:"email",
					label			:"信箱"
				},
				{
					position:"footer",
					type:"button",
					label:"確定",
					target:function( result ){
						var api = configs.api.systemConfig + "/productevent/" + data.id;
						$scope.update(api, result, function(data, status, headers, config){
							if( status!=200 ){
								$scope.message("更新信箱通知失敗，可能是因為網路關係。");
							}
							$scope.productEventTableReload();
						});
					}
				}
			]
		});

		$scope.modal.show();
		
	}

	return app.controller("SystemConfigController", createController(function ($scope, $http, $timeout, $routeParams) {
		$scope.payTypes = {
            list:[
                {text:"信用卡線上刷卡", type:"neweb"},
                {text:"超商繳款", type:"MMK"},
                {text:"虛擬帳號轉帳", type:"ATM"},
            ]
        };

		$scope.restructuring = function( data, row ){
			$scope.select_type = {};
			$scope.select_type.position = "body";
			$scope.select_type.type = "select";
			$scope.select_type.attribute = {};
			$scope.select_type.attribute.list = [];
			$scope.select_type.attribute.selected = {};
			if (row) {
				$scope.select_type.attribute.selected.text = $scope.changeName("delivery_type",row.delivery_type);
				$scope.select_type.attribute.selected.attribute = row.delivery_type;
			}else{
				$scope.select_type.attribute.selected.text = $scope.fareItem.records[0].type;
				$scope.select_type.attribute.selected.attribute = $scope.fareItem.records[0].id;	
			}
			$scope.select_type.attributeName	= "delivery_type",
			$scope.select_type.label = "配送方式";

			for(var key in $scope.fareItem.records){
				$scope.select_type.attribute.list[key] = {};
				$scope.select_type.attribute.list[key].text = $scope.fareItem.records[key].type;
				$scope.select_type.attribute.list[key].attribute = $scope.fareItem.records[key].id;
			}
		}

		$scope.changeName = function( type, value ){
			var item = {};
			if (type == "pay_type") {
	            for(var key in $scope.payTypes.list) {
	                item = $scope.payTypes.list[key];
	                if(item.type == value) {
	                    value = item.text;
	                }
	            }
			}else if (type == "delivery_type") {
				for(var key in $scope.fareItem.records) {
	                item = $scope.fareItem.records[key];
	                if(item.id == value) {
	                    value = item.type;
	                }
	            }
			}
			return value;
		}

		$scope.rename = function( type, value ){
			var item = {};
			if (type == "pay_type") {
	            for(var key in $scope.payTypes.list) {
	                item = $scope.payTypes.list[key];
	                if(item.text == value) {
	                    value = item.type;
	                }
	            }
			}else if (type == "delivery_type") {
				for(var key in $scope.fareItem.records) {
	                item = $scope.fareItem.records[key];
	                if(item.type == value) {
	                    value = item.id;
	                }
	            }
			}
			return value;
		}

		$scope.destroy = function(api, Callback){
			var request = {
				method: 'DELETE',
			 	url: api,
			 	headers: configs.api.headers,	 	
			};
			$http(request)
				.success(function(data, status, headers, config) {
					Callback(data, status, headers, config);
				})
				.error(function(data, status, headers, config){
					Callback(data, status, headers, config);
				});
		}

		$scope.create = function( api, data, Callback ){
			var request = {
				method: 'POST',
			 	url: api,
			 	headers: configs.api.headers,	
			 	data: data,		 	
			};
			$http(request)
				.success(function(data, status, headers, config) {
					Callback(data, status, headers, config);
				})
				.error(function(data, status, headers, config){
					Callback(data, status, headers, config);
				});
		}

		$scope.update = function( api, data, Callback ){
			var request = {
				method: 'PUT',
			 	url: api,
			 	headers: configs.api.headers,	
			 	data: data,		 	
			};
			$http(request)
				.success(function(data, status, headers, config) {
					Callback(data, status, headers, config);
				})
				.error(function(data, status, headers, config){
					Callback(data, status, headers, config);
				});
		}

		$scope.message = function(msg){
			$scope.modal.config({
				controls:[
					{
						position:"header",
						type:"text",
						label:"訊息",
					},
					{
						position 		:"body",
						type 			:"text",
						attribute		:msg,
						attributeName	:"msg",
						label			:"訊息:"
					},
					{
						position:"footer",
						type:"button",
						target:function(){},
						label:"確定",
					}
				]
			});

			$scope.modal.show();
		}

		$scope.fareTableReload = function(){
			FareTableLoad( $scope, configs );
		};

		$scope.productEventTableReload = function(){
			ProductEventTableLoad( $scope, configs );
		};

		$scope.deliveryTableReload = function(){
			LogisticsTableLoad( $scope, configs );
		};

		$scope.fareAdd = function(){
			FareAddEvent( $scope );
		};
		$scope.logisticsAdd = function(){
			LogisticsAddEvent( $scope );
		}
		$scope.productEventAdd = function(){
			ProductEventAdd( $scope );
		};

		$timeout(function(){
			FareTableSetting( $scope );
			FareTableLoad( $scope, configs );
			LogisticsTableSetting( $scope );
			LogisticsTableLoad( $scope, configs );
			ProductEventTableSetting( $scope );
			ProductEventTableLoad( $scope, configs );
		},200);

	}));
	
});