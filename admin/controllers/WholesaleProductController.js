/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs', 'message'], function (angular, app, createController, configs, message) {

	function GetInfo( id, $http, CallBack )
	{
		var url = configs.api.product + '/wholesale/' + id;
		var request = {
			method: 'GET',
		 	url: url,
		 	headers: configs.api.headers,	
		 	data: {},		 	
		}
		$http(request).success(function(data, status, headers, config) {

			CallBack( data );
		}).error(function(data, status, headers, config){				

		});
	}

	/**
	*	Format datetime object to string (Y:m:d H:i:s)
	*
	*/
	function DateTimeFormat (date, time)
	{
		time = time || date;
	  	var year = "" + date.getFullYear();
	  	var month = "" + (date.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
	  	var day = "" + date.getDate(); if (day.length == 1) { day = "0" + day; }
	  	var hour = "" + time.getHours(); if (hour.length == 1) { hour = "0" + hour; }
	  	var minute = "" + time.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
	  	var second = "" + time.getSeconds(); if (second.length == 1) { second = "0" + second; }
	  	return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
	}

	/**
	*	message tool
	*/
	function Message($scope, msg)
	{
		$scope.modal.config({
			controls:[
				{
					position:"header",
					type:"text",
					label:"訊息",
				},
				{
					position:"body",
					type:"text",
					label:msg,
				},
				{
					position:"footer",
					type:"button",
					label:"確定",
					target:function( data ){}
				}
			]
		});
		$scope.modal.show();
	}

	function IsGeneralFreightFare(fare_type)
	{
		if( fare_type == 'normal' )
		{
			return true;
		}
		else if( fare_type == 'special' )
		{
			return false;
		}
	}

	function TableLoad( $table_instance, records, pageNo, pageSize )
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

	function DeleteSpecOnClickBtn( $scope, row ){
		var index = $scope.spec_records.indexOf(row);
		if(index != -1){
			$scope.deleteSpec.push(row.id);
			$scope.spec_records.splice(index, 1);
		}
		index = $scope.newspec_records.indexOf(row);
		if(index != -1){
			$scope.newspec_records.splice(index, 1);
		}
		
		TableLoad(
    		$scope.specTable,
    		$scope.spec_records,
    		1,
    		1000
    	);
	}

	function SpecSetting( $scope ,$http)
	{

		$scope.specTable.configField(
    		[
				{
					attribute:"serial",
					name:"品號(鉅盛)"
				},
				{
					attribute:"can_sale_inventory",
					name:"可販售庫存"
				},
				{
					attribute:"safe_inventory",
					name:"安全庫存"
				},
				{
					attribute:"name", 
					name:"規格名稱"
				},
				{				
					attribute:"control", 
					name: "控制",
					controls: [
						{
							type: "button",
							icon: "fa-pencil",
							click: function(row, attribute) {
                                modifySpecModal($scope ,row ,$http);
							}
						},
						
					]
				},
			]
		);

    	TableLoad(
    		$scope.specTable,
    		$scope.spec_records,
    		1,
    		1000
    	);
		
	}

	function toBoolean( string )
	{
		if( string == "1" )
		{
			return true;
		}

		return false;
	}

	function SpecSave( $scope, data )
	{
		var isFillin = (data.name!=""&&data.serial!=""&&data.can_sale_inventory!=""&&data.safe_inventory!="")
		if( isFillin )
		{
			$scope.spec_records.push({
				name:data.name, 
				serial:data.serial, 
				can_sale_inventory:data.can_sale_inventory, 
				safe_inventory:data.safe_inventory
			});
			$scope.newspec_records.push({
				name:data.name, 
				serial:data.serial, 
				can_sale_inventory:data.can_sale_inventory, 
				safe_inventory:data.safe_inventory
			});
			TableLoad(
        		$scope.specTable,
        		$scope.spec_records,
        		1,
        		1000
        	);
		}
		else
		{
			alert("請填完整的規格。");
		}
	}

	function SpecModal( $scope ){
		$scope.modal.config({
			controls:[
				{
					position:"header",
					type:"text",
					label:"新增規格",
				},
				{
					position 		:"body",
					type 			:"input",
					attribute		:"",
					attributeName	:"serial",
					label			:"品號(鉅盛)"
				},
				{
					position 		:"body",
					type 			:"number",
					attribute		:"",
					attributeName	:"can_sale_inventory",
					label 			:"可販售庫存"
				},
				{
					position 		:"body",
					type 			:"number",
					attribute		:"",
					attributeName	:"safe_inventory",
					label 			:"安全庫存"
				},
				{
					position 		:"body",
					type 			:"input",
					attribute		:"",
					attributeName	:"name",
					label 			:"規格名稱"
				},
				{
					position:"footer",
					type:"button",
					label:"確定",
					target:function( data ){
						SpecSave( $scope, data );
					}
				}
			]
		});

		$scope.modal.show();
	}

    /**
     *
     * @param $scope
     * @param row
     */
    function modifySpecModal( $scope , row , $http){
        $scope.modifyModal.config({
            controls:[
                {
                    position:"header",
                    type:"text",
                    label:"修改規格"
                },
                {
                    position 		:"body",
                    type 			:"number",
                    attribute		: row.can_sale_inventory,
                    attributeName	:"can_sale_inventory",
                    label 			:"可販售庫存"
                },
                {
                    position 		:"body",
                    type 			:"number",
                    attribute		:row.safe_inventory,
                    attributeName	:"safe_inventory",
                    label 			:"安全庫存"
                },
                {
                    position 		:"body",
                    type 			:"input",
                    attribute		:row.name,
                    attributeName	:"name",
                    label 			:"規格名稱"
                },
                {
                    position:"footer",
                    type:"button",
                    label:"確定",
                    target:function( data ){
                        var index;
                        if( data.name!=""){
                            for(index in $scope.spec_records) {
                                if($scope.spec_records[index].id == row.id){
                                    $scope.spec_records[index].name =  data.name;
                                    $scope.spec_records[index].can_sale_inventory =  data.can_sale_inventory;
                                    $scope.spec_records[index].safe_inventory =  data.safe_inventory;
                                    $scope.modifySpec_records = $scope.spec_records[index];
                                    modifySpec(configs.api.product+"/wholesale/modifySpec" ,$scope.modifySpec_records , $http);
                                }
                            }
                        }else
                        {
                            alert("請填完整的規格。");
                        }
                    }
                }
            ]
        });

        $scope.modifyModal.show();
    }

	/**
	* upload instance setting tool
	*/
	function UploadInstanceSetting( $upload_instance, api, label, isMutiple, successCallback )
	{
		$upload_instance.api( api );
		$upload_instance.label(label);
		$upload_instance.mutiple(isMutiple);
		$upload_instance.success(function(data, status, headers, config){
			successCallback(data, status, headers, config);
		});
	}

	function CoverPhotoSetting( $scope ){
		var cover_image_api = configs.api.materialUpload + "/wholesale";
		var cover_image_label = "上傳";
		var cover_image_isMutiple = false;
		UploadInstanceSetting(
			$scope.productCoverImageUpload,
			cover_image_api,
			cover_image_label,
			cover_image_isMutiple,
			function(data, status, headers, config){
				var file = data.file;
				$scope.productCoverImage = file.fileName;
			}
		);
	}

	function ProductImagesSetting( $scope ){
		var image_api = configs.api.materialUpload + "/wholesale";
		var image_label = "上傳";
		var image_isMutiple = true;
		UploadInstanceSetting(
			$scope.productImageUpload,
			image_api,
			image_label,
			image_isMutiple,
			function(data, status, headers, config){
				var file = data.file;
				file['style'] = "";
				$scope.newproductImages.push(file);
				$scope.productImages.push(file);
			}
		);
	}

	function ImageTableSetting( $scope, $timeout )
	{
		$timeout(function(){

			CoverPhotoSetting($scope);
			ProductImagesSetting($scope);

		},200);
	}

	function Is_fill_in_from( $scope )
	{

		if( $scope.name == "" )
		{
			return { isOk:false, msg:"商品名稱" };
		}

		if( $scope.wholesale_price == 0 )
		{
			return { isOk:false, msg:"批發價" };
		}

		if( $scope.suggest_price == 0 )
		{
			return { isOk:false, msg:"建議售價" };
		}

		if( $scope.lowest_end_price == 0 )
		{
			return { isOk:false, msg:"最低售價" };
		}

		if( $scope.cost_price == 0 )
		{
			return { isOk:false, msg:"成本價" };
		}

		if( $scope.propose_price == 0 )
		{
			return { isOk:false, msg:"建議售價" };
		}

		if( $scope.ready_day == "" )
		{
			return { isOk:false, msg:"上架日期" };	
		}

		if( $scope.fareList.getField().length == 0 )
		{
			return { isOk:false, msg:"運費" };
		}

		if( $scope.spec_records.length == 0 )
		{
			return { isOk:false, msg:"產品規格" };	
		}

		if( $scope.product_text == "" )
		{
			return { isOk:false, msg:"商品描述 - 文字" };
		}

        if( $scope.sbEditor.getData().length == 0 )
        {
            return { isOk:false, msg:"商品說明(次頁)" };
        }

		if( $scope.productCoverImage.length == 0 )
		{
			return { isOk:false, msg:"商品封面 照片" };
		}

		if( $scope.isUseImage && $scope.productImages.length == 0 )
		{
			return { isOk:false, msg:"商品描述 照片" };	
		}

		if( !$scope.isUseImage && $scope.youtube_url == "" )
		{
			return { isOk:false, msg:"商品描述 youtube url" };	
		}

		if( !$scope.productGroupTree.getSingleSelectedId() )
		{
			return { isOk:false, msg:"商品群组" };
		}

		if( $scope.is_open_groupbuying )
		{
			if( $scope.groupbuying_maxmun_day == 0 || $scope.groupbuying_minimun_day == 0 ){
				return { isOk:false, msg:"團購主開團天數限制" };
			}
		}

		return { isOk:true };

	}

	//function Create( url, data, $http )
	//{
	//	var req = {
	//	    method: 'POST',
	//	    url: url,
	//	    data:data,
	//	    headers: configs.api.headers
	//	};
	//	$http(req).success(function(result) {
	//		//do something
	//	}).error(function(error) {
	//	});
	//}

    function modifySpec( url, data, $http )
    {
    	var req = {
    	    method: 'PUT',
    	    url: url,
    	    data:data,
    	    headers: configs.api.headers
    	};
    	$http(req).success(function(result) {
    		//do something
    	}).error(function(error) {
    	});
    }


	function Update( url, data, $http, $scope )
	{
		var req = {
		    method: 'PUT',
		    url: url,
		    data:data,
		    headers: configs.api.headers
		};
		$http(req).success(function(result) {
			return {isSuccess:true};
		}).error(function(error) {
			if(error && error.message == "Product spec delete fail.")
			{
				Message($scope,"刪除規格時發生錯誤，由於此規格已出售過故無法刪除");
			}
			else if(error &&error.message == "Product spec update fail.")
			{
				Message($scope,"更新規格時發生錯誤，請重新確認。");
			}
			else if(error &&error.message == "Product explain update fail.")
			{
				Message($scope,"商品說明(次頁)的圖片更新發生錯誤，請重新確認。");
			}
			else if(error &&error.message == "Product media update fail.")
			{
				Message($scope,"商品描述(首頁)的媒體更新發生錯誤，請重新確認。");
			}
			else
			{
				Message($scope,"更新發生錯誤，可能是網路問題，請重新嘗試。");
			}
		});
	}

	function Delete( url, $http )
	{
		var req = {
		    method: 'DELETE',
		    url: url,
		    headers: configs.api.headers
		};
		$http(req).success(function(result) {
		}).error(function(error) {
		    Message("刪除照片錯誤");
		});
	}

	function UpdateDeleteImages( $scope, $http )
	{
		var images = $scope.deleteImages;
		for(var index in images){
			if(images[index]['file']){
				var info = images[index]['file'].split('.');
				Delete(configs.api.product + "/materials/wholesale/"+ images[index]["type"] +"/image/" + info[0] + "/" + info[1], $http);
			}
		}
	}


	return app.controller(
		"WholesaleProductController", 
		createController(function($scope, $http, $timeout, $routeParams) {

			function Get_product_group( CallBack )
			{
				var url = configs.api.productGroup + "/search/wholesale/product/1/100";
				var req = {
				    method: 'GET',
				    url: url,
				    headers: configs.api.headers
				};
				$http(req).success(function(result) {
					CallBack( result );
				}).error(function(error){
					alert("取得產品群组失敗");
					CallBack( [] );
				});
			}

			function Render()
			{
				$scope.name = $scope.productInfo.name;

				$scope.wholesale_price = $scope.productInfo.wholesale_price *1;
				$scope.suggest_price = ($scope.productInfo.suggest_price*1||0);
				$scope.lowest_end_price = $scope.productInfo.end_price 		*1;
				$scope.cost_price = $scope.productInfo.cost_price 		*1;
				$scope.propose_price = $scope.productInfo.propose_price 		*1;

				var tempFares = [];
				for( var index in $scope.fares ){
					tempFares.push($scope.fares[index]);
				}

				$scope.fareList.setRecords( tempFares );

				SpecSetting( $scope ,$http);

				$scope.productCoverImage = $scope.productInfo.cover_photo_img;

				$scope.product_text = $scope.productInfo.detail;
                $scope.sbEditor.setData( $scope.productInfo.explain_text);

				$scope.isUseImage = !toBoolean($scope.productInfo.media_type);
				$scope.youtube_url = $scope.productInfo.youtube_url;
				
				$scope.productImages = $scope.product_images;
				$scope.newproductImages = [];

				$scope.productExplainImages = $scope.explain_images;
				$scope.newproductExplainImages = [];
				
				$scope.explain_text = $scope.productInfo.explain_text;

				$scope.productGroupSelectedId = $scope.productInfo.product_group_id;

				$scope.is_open_groupbuying = toBoolean($scope.productInfo.active_groupbuying);
				$scope.groupbuying_maxmun_day 	= ($scope.productInfo.active_maximum*1||0);
				$scope.groupbuying_minimun_day 	= ($scope.productInfo.active_minimum*1||0);

				$scope.tag 				= $scope.productInfo.tag;
				$scope.weight 			= $scope.productInfo.weight*1;
				$scope.productLength 	= $scope.productInfo.product_length*1;
				$scope.productWidth 	= $scope.productInfo.product_width*1;
				$scope.productHeight 	= $scope.productInfo.product_height*1;
				$scope.productCubicFeet = 0;
				$scope.productCubicMeter = 0;

				$scope.cuftNumber = 0.0000353;
				$scope.productCubicFeet = $scope.productLength * $scope.productWidth * $scope.productHeight * $scope.cuftNumber;
				$scope.CBMNumber = 35.315;
				$scope.productCubicMeter = ($scope.productCubicFeet/$scope.CBMNumber);

				$scope.$watch("productGroupTree", function(tree){
					if(tree){
						tree.bindMutipleSelectedApplyHandler(function(node){
							return false;
						});
						tree.bindSingleSelectedApplyHandler(function(node){
							if( node.type && node.type.value == 2 ){
								return true;
							}
							return false;
						});
						tree.loadSingleSelectedId($scope.productGroupSelectedId);
						tree.isApplyAppend(false);
						tree.isApplyDelete(false);
					}
				});	
			}

			function DeleteImageInUi( type, file ){

				if( type == "productCoverImage" )
				{
					$scope.productCoverImage = "";
				}
				else if( type == "explain" )
				{
					var index = $scope.productExplainImages.indexOf(file);

					if(index != -1){
						$scope.productExplainImages.splice(index, 1);
					}
					index = $scope.newproductExplainImages.indexOf(file);

					if(index != -1){
						$scope.newproductExplainImages.splice(index, 1);
					}
				}
				else if( type == "product" )
				{
					var index = $scope.productImages.indexOf(file);
					if(index != -1){
						$scope.productImages.splice(index, 1);
					}
					index = $scope.newproductImages.indexOf(file);
					if(index != -1){
						$scope.newproductImages.splice(index, 1);
					}
				}
			}

			function DeleteImageFlow(type, file ){
				var fileName = (file["url"]||file["fileName"]);
				$scope.deleteImages.push({ type:type, file:fileName });
				DeleteImageInUi( type, file );
			}

			function FaresGetting(){
				var original = $scope.fares;
				var nowFares = $scope.fareList.getField();

				var result = { newDelivery:[], deleteDelivery:[] };

				for( var index in nowFares ){
					var isNewDelivery = true;
					for( var key in original ){
						if( original[key]['id']==nowFares[index]['id'] ){
							isNewDelivery = false;
						}
					}
					if(isNewDelivery){
						result['newDelivery'].push(nowFares[index]['id']);
					}
				}

				for( var index in original ){
					var isHasDelete = true;
					for( var key in nowFares ){
						if( original[index]['id']==nowFares[key]['id'] ){
							isHasDelete = false;
						}
					}
					if(isHasDelete){
						result['deleteDelivery'].push(original[index]);
					}
				}
				return result;
			}

			function Porduct_update( id )
			{
				var fill_in_check = Is_fill_in_from( $scope );
						
				if( fill_in_check.isOk )
				{
					var formData = {
						name 				: 	$scope.name,
						
						wholesale_price  	: 	$scope.wholesale_price,
						end_price 			: 	$scope.lowest_end_price,
						suggest_price 		: 	$scope.suggest_price,
						cost_price  		: 	$scope.cost_price,
						propose_price 		: 	$scope.propose_price,
						detail 				: 	$scope.product_text,
						product_group_id	: 	$scope.productGroupTree.getSingleSelectedId(),
						explain_text 		: 	$scope.sbEditor.getData(),
						
						active_groupbuying 	: 	($scope.is_open_groupbuying?"1":"0"),
						active_maximum		: 	$scope.groupbuying_maxmun_day,
						active_minimum		: 	$scope.groupbuying_minimun_day,

						cover_photo_img  	: 	$scope.productCoverImage,
						youtube_url 		: 	$scope.youtube_url,

						product_images 		: 	$scope.newproductImages,
						explain_images 		: 	$scope.newproductExplainImages,
						spec 				: 	$scope.newspec_records,
						//deleteSpec 			: 	$scope.deleteSpec,


						tag 				: 	$scope.tag,
						weight 				: 	$scope.weight,
						product_length 		: 	$scope.productLength,
						product_width 		: 	$scope.productWidth,
						product_height 		: 	$scope.productHeight

					};

					if( $scope.isUseImage ){
						formData[ 'media_type' ] = '0';
					}else{
						formData[ 'media_type' ] = '1';
					}

					var fareData = FaresGetting();
					formData['deleteDeliverys'] = fareData['deleteDelivery'];
					formData['deliverys'] 		= fareData['newDelivery'];

					var api = configs.api.product + "/wholesale/" + id;
					var result = Update( api, formData, $http, $scope);
					return result;
				}
				else
				{
					Message( $scope, "請確認["+fill_in_check.msg+"]欄位是否完整。" );
					return {isSuccess:false};
				}
			}

			$scope.save = function(){
				Porduct_update( $routeParams.id );
				UpdateDeleteImages( $scope, $http );
				if( !$scope.modal.isShow() ){
					location.href = "#!/product/wholesale/list/1/100";
				}
			}

			$scope.deleteImage = function( file, type ){
				DeleteImageFlow( type, file );
			};

			$scope.spec_add = function(){
				SpecModal( $scope );
			}

			$scope.cancel = function()
			{
				location.href = "#!/product/wholesale/list/1/100";
			};

			function start( data ){

				$scope.productInfo = data.record;
				$scope.spec_records = data.spec;
				$scope.product_images = data.product_images;
				$scope.explain_images = data.explain_images;
				$scope.fares = data.fares;

				$scope.deleteImages = [];
				$scope.deleteSpec = [];
				$scope.newspec_records = [];


				Render();
				ImageTableSetting($scope, $timeout);

			}

			GetInfo( $routeParams.id, $http,function( data ){ start(data) });


		})
	);	
});