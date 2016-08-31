/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/ProductGroupUseSmartTree/view.html', 'configs'], 
function (angular, app, view, configs) {

	app.directive("productGroupUseSmartTree", function () {
		return {
			restrict: "E",			
			template: view,
			controller:function($scope, $http, $timeout){

				const TREE_ROOT_ID = 2;
				const CHANNEL = "wholesale";
				$scope.isApplyAppend = false;
				$scope.isApplyDelete = false;

				$scope.applyMutipleSelectedHandler = function(node){return false;}
				$scope.applySingleSelectedHandler = function(node){return false;}

				function settingRootId( tree ){
					tree.setRootId(TREE_ROOT_ID);
				}

				function loadTreeHandler( tree ){
					var api = configs.api.productGroup + "/search/"+CHANNEL+"/all/1/9999";
					var querystring = "/?parentId="+TREE_ROOT_ID;
					tree.loadByApi(api,querystring);
				}

				function appendTreeHandler( tree ){
					var api = configs.api.productGroup + "/create/"+CHANNEL;
					var applyHandler = function(tree, node, instance){
						if( node.type.value==1 ){
							return true;
						}
						return false;
					}
					tree.bindAppendHandler( api, applyHandler );
				}

				function deleteTreeHandler( tree ){
					var api = configs.api.productGroup + "/"+CHANNEL;
					var applyHandler = function(tree, node, instance){
						if( node.id==4 ){
							return false;
						}
						return true;
					}
					tree.bindDeleteHandler( api, applyHandler );
				}

				function onNodeClickHandler(tree){
					tree.onNodeClick(function( node, instance ){
						instance.openByNode();
					});
				}

				function onNodeEventHandler(tree){
					tree.onNodeEvent(function( node, attributeName, instance ){
						
						if( attributeName == "name" ){
							instance.modal( function(keyin){
								var api = configs.api.productGroup + "/wholesale/"+node.id;
								var updateForm = {data:{"name":keyin.name}};
								instance.update( api, updateForm );
							});
						}
						
					});
				}

				function typeFilter( type ){
					if( type==1 ){
						return "群组";
					}else if( type==2 ){
						return "商品群";
					}else{
						return "未定義";
					}
				}

				function onFilterNodeHandler( tree ){
					tree.onFilterNode(function( data, name, value ){
						if(name == 'type'){
							return typeFilter(value);
						}
					});
				}

				function margeInstance( tree ){
					for( var index in tree ){
						$scope.instance['tree'][index] = tree[index];
					}
				}

				$scope.$watch("tree", function(tree){
					if(tree){
						settingRootId(tree);
						loadTreeHandler(tree);
						appendTreeHandler(tree);
						deleteTreeHandler(tree);
						onNodeClickHandler(tree);
						onNodeEventHandler(tree);
						onFilterNodeHandler(tree);

						$scope.$watch("applyMutipleSelectedHandler", function(applyHandler){
							if(applyHandler){
								tree.bindMutipleSelectedHandler( applyHandler );
							}
						});
						$scope.$watch("applySingleSelectedHandler", function(applyHandler){
							if(applyHandler){
								tree.bindSingleSelectedHandler( applyHandler );
							}
						});

						$scope.$watch("singleSelectedId", function(id){
							if(id){
								tree.loadSingleSelectedId(id);
							}
						});
						
						$scope.$watch("isApplyAppend",function(){
							tree.isApplyAppend($scope.isApplyAppend);
						});
						$scope.$watch("isApplyDelete",function(){
							tree.isApplyDelete($scope.isApplyDelete);
						});

						$scope.$watch("mutipleSelectedIds",function(ids){
							tree.loadMutipleSelectedIds(ids);
						});
						
					}
				});

				$scope.instance = {
					bindMutipleSelectedApplyHandler:function( applyHandler ){
						$scope.applyMutipleSelectedHandler = applyHandler;
					},
					bindSingleSelectedApplyHandler:function( applyHandler ){
						$scope.applySingleSelectedHandler = applyHandler;
					},
					isApplyAppend:function(isApply){
						$scope.isApplyAppend = isApply;
					},
					isApplyDelete:function(isApply){
						$scope.isApplyDelete = isApply;
					},
					loadSingleSelectedId:function(id){
						$scope.singleSelectedId = id;
					},
					loadMutipleSelectedIds:function(ids){
						$scope.mutipleSelectedIds = ids;
					},
					getSingleSelectedId:function(){
						return $scope.tree.getSingleSelectedId();
					},
					getMutipleSelectedIds:function(){
						return $scope.tree.getSelectedToArray();
					},
					isSelectedId:function(id){
						var ids = $scope.tree.getSelectedToArray();
						var result = false;
						for(var index in ids){
							if( ids[index] ==  id){
								result = true;
							}
						}
						return result;
					},
				};
			},
			scope: {				
				instance: '=?instance',
			},
		};
	});
});