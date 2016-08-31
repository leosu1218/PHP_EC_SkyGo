/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs'], 
	function (angular, app, createController, configs) {

	return app.controller("CreateRetailProductController", createController(function ($scope, $timeout, $http) {	

		function constructer(){
			$scope.productGroups = [];
			$scope.form = {
				name:"",
				ready_time:"",
				removed_time:"",
				price:0,
				detail:"",
				product_group_id:null,
				serial_number:""
			};	
		};

		function isFormFillIn(){

			var form = $scope.form;
			var condition = (form.name == "" );
			condition = (condition || form.ready_time == "" || form.removed_time == "");
			// condition = (condition || Date.parse($scope.form.removed_time) > Date.parse($scope.form.ready_time) );
			condition = (condition || form.wholesale_price == 0 || form.end_price == 0);
			condition = (condition || form.active_minimum > form.active_maximum);
			condition = (condition || form.maximum == 0 || form.minimum == 0);

			if( condition ){
				return false;
			}else{
				return true;
			}
		}
		function isProductGroupIdSelected(){			
			if($scope.form.product_group_id){
				return true;
			}
			return false;
		}

		function eventBind(){

			$scope.clickProductGroup = function( item ){
				$scope.isShowError = false;
				for(var index in $scope.productGroups){
					$scope.productGroups[ index ].selected = false;
				}
	     		item.selected = true;
	     		$scope.form.product_group_id = item.id;     		
			};

			$scope.fillIn = function(){
				$scope.form = {
					name:"312312",
					ready_time:"",
					removed_time:"",
					price:100,
					detail:"123",
					product_group_id:null,
					serial_number:"VM00392021"
				};	
			};

			$scope.create = function(){

				if( isProductGroupIdSelected() && isFormFillIn() ){

					$scope.message = {
	            		isShowError : false,
	            		showErrorMessage : ""
	            	};

	            	var url = configs.api.product + "/retail";
					var req = {
					    method: 'POST',
					    url: url,
					    headers: configs.api.headers,
					    data: $scope.form
					};
					$http(req).success(function(result) {
						$scope.updateUrl( result.id );
					}).error(function(error) {
					    alert("Create product error");
					});


				}else{
					$scope.message = {
	            		isShowError : true,
	            		showErrorMessage : "請先填寫完成表單!"
	            	};
				}
			}

			$scope.cancel = function(){
				$scope.form = {
					name:"",
					ready_time:"",
					removed_time:"",
					price:0,
					detail:"",
					product_group_id:null,
					serial_number:""
				};
			}

		};
		
		function listFlow(){
			var url = configs.api.productGroup + "/list/retail/product/1/100";
			var req = {
			    method: 'GET',
			    url: url,
			    headers: {
			        'Content-Type': 'application/json'
			    }
			};
			$http(req).success(function(result) {
				for(var index in result.records){
					result.records[index]['selected'] = false;
				}
				$scope.productGroups = result.records;			
			}).error(function(error) {
			    // Do nothings.
			});
		};

		constructer();
		eventBind();
     	listFlow();
		$scope.fillIn();

	}));
	
});