/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/ProductGroupTree/view.html', 'configs'], 
function (angular, app, view, configs) {

	app.directive("productGroupTree", function () {
		return {
			restrict: "E",			
			template: view,
			controller: function($scope, $http, $timeout){

				$scope.list = [];

				$scope.remove = function(item, key){
					var request = {
						method: 'DELETE',
					 	url: configs.api.groupbuyingUser + "/" + $scope.id + "/group/" + item.id,
					 	headers: configs.api.headers 	
					}				

					$http(request).success(function(data, status, headers, config) {
						
						if( key ){
							$scope.list.splice(key,1);
						}else{
							var index = $scope.list.indexOf(item);
							$scope.list.splice(index,1);
						}
						
					}).error(function(data, status, headers, config){				
						console.log(data, status, headers, config);
					});
				}

				$scope.save = function(id, callBack){
					var request = {
						method: 'PUT',
					 	url: configs.api.groupbuyingUser + "/" + $scope.id + "/groups",
					 	headers: configs.api.headers,	
					 	data: {
							"ids":[id]
						},		 	
					}				

					$http(request).success(function(data, status, headers, config) {
						// $scope.alert.show("");
						callBack(data, status, headers, config);
						// console.log(data, status, headers, config);
					}).error(function(data, status, headers, config){				
						// $scope.alert.show("");
						console.log(data, status, headers, config);
					});
				}

				function isNodeChildHas(nodes, newNode){
					var isContain = false;
					for(var index in nodes){
						if( !isContain && nodes[index]['id'] == newNode['id'] ){
							isContain = true;
						}

						if( !isContain && nodes[index]['nodes'] && nodes[index]['nodes'].length!=0 ){
							isContain = isNodeChildHas( nodes[index]['nodes'], newNode );
						}
					}
					return isContain;
				}

				function checkIsContain(list, newNode){
					var isContain = false;
					for( var index in list ){
						
						if( !isContain && list[index]['id'] == newNode['id']){
							isContain = true;
						}
						if( !isContain && list[index]['nodes'] && list[index]['nodes'].length!=0 ){
							isContain = isNodeChildHas( list[index]['nodes'], newNode );
							console.log(isContain,list[index]['nodes'], newNode );
						}

					}
					return isContain;
				}

				function checkIsDuplicate( list, newNodeChilds ){
					var isDuplicate = false;
					for( var index in newNodeChilds ){
						isDuplicate = isNodeChildHas( newNodeChilds[index], list );
					}
					return isDuplicate;
				}

				function removeDuplicateNode(node){
					if(node['id']){
						for( var key in $scope.list ){
							if(node['id']==$scope.list[key]["id"]){
								$scope.remove( node, key );
							}
						}	
					}else{
						console.log('error:',node);
					}
					
				}

				function fixDuplicate( nodes ){
					for( var index in nodes ){
						removeDuplicateNode(nodes[index]);
						if( nodes[index]['nodes'] && nodes[index]['nodes'].length!=0 ){
							fixDuplicate( nodes[index]['nodes'] );
						}
					}
				}

				function inPutSelectedNode( node ){

					var object = {};
					for(var index in node){
						object[index] = node[index];
					}

					if(!checkIsContain($scope.list, object)){
						if( checkIsDuplicate( $scope.list, object['nodes'] ) ){
							fixDuplicate(object['nodes']);
						}
						$scope.save(object['id'], function(){
							$scope.list.push(object);
						});
					}
					
				}
				
				function getIds(list){
					var ids = [];
					for(var index in list){
						ids.push(list[index]['id']);
					}
					return ids;
				}

				function findReallyNode( tree, userHas ){
					var temp = [];
					for( var index in tree ){
						for( var key in userHas ){
							if(tree[index]["id"] == userHas[key]["id"]){
								temp.push(tree[index]);
							}
						}
					}
					return temp;
				}

				$timeout(function(){
					var api 	= configs.api.productGroup + "/list/wholesale/product";
					var pageNo 	= 1;
					var pageSize= 99999;
					var success = function(data, status, headers, config){
						$scope.list = findReallyNode( data.records, $scope.userHas );
					};
					var error 	= function(data, status, headers, config){
						console.log(data);
					};
					$scope.tree.loadByUrl(api, pageNo, pageSize, success, error);


					$scope.tree.onSelected(function( node ){
						inPutSelectedNode(node);
					});

				},100);

				$scope.instance = {
					userHas:function( groups ){
						$scope.userHas = groups.records;
					},
					userId:function(id){
						$scope.id = id;
					}
				};
			},
			scope: {				
				instance: '=?instance',
			},
		};
	});
});