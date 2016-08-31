
/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

	/**
	*	Sb Smart Table Directive directive.
	*
	*
	*/
	app.directive("sbModal", function () {
		return {
			restrict: "E",			
			templateUrl: app.applicationPath + "/views/SbModal.html",
			controller:  function($scope, $http, $timeout) {

				//default
				$scope.modalName = '#sbModal';

				$scope.headerText = [];
				$scope.bodyInput = [];
				$scope.footerButton = [];

				//render doing something
				$scope.renders = {
					header:function( config ){
						if( config.type == "text" ){
							$scope.headerText.push({ text:config.label });
						}
					},
					body:function( config ){
						if( config.type == "input" ){
							$scope.bodyInput.push({ attributeName:config.attributeName, label:config.label, attribute:(config.attribute||"") });
						}
					},
					footer:function( config ){
						if( config.type == "button" ){
							$scope.footerButton.push({ 
								label:config.label, 
								target:function(){
									config.target( result() );
									clear();
									$($scope.modalName).modal('hide');
								}
							});
						}
					}
				};

				var renderFlow = function(){
					clear();
					var controls = $scope.configs.controls;
					for(var index in controls){
						$scope.renders[ controls[index].position ]( controls[index] );
					}
				};

				var result = function(){
					var result = {};
					for(var index in $scope.bodyInput){
						result[ $scope.bodyInput[index].attributeName ] = $scope.bodyInput[index].attribute;
					}
					return result;
				};
				
				var clear = function(){
					$scope.headerText = [];
					$scope.bodyInput = [];
					$scope.footerButton = [];
				};

				$scope.instance = {
					/**
					*	@param json {
					*		controls:[
					*			{
					*				position:"header",
					*				label:"修改欄位",
					*				type:"text",
					*			},
					*			{
					*				position:"body"
					*				attributeName:"note",
					*				type:"input",
					*				label:"備註"
					*			},
					*			{
					*				position:"footer",
					*				type:"button",
					*				label:"確定",
					*				target:function( item ){
					*					row.note = item.note;
					*				}
					*			}
					*		]
					*	}
					*
					*/
					config:function( configs ){
						$scope.configs = configs;
						renderFlow();
					},
					show:function(){
						$($scope.modalName).modal();
					}
				};

			},
			scope: {				
				instance: '=?instance',
			},
		};
	});
});