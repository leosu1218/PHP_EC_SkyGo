/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/SbSmartTree/view.html', 'configs'], 
function (angular, app, view, configs) {

	app.directive("sbSmartTree", function () {
		return {
			restrict: "E",			
			template: view,
		 	controller: function($scope, $http){

		 		$scope.rootId = 0;

		 		$scope.openIcon 	= "fa fa-folder-open";
				$scope.closeIcon 	= "fa fa-folder";
				$scope.nameIcon 	= "fa fa-arrow-right";
				$scope.createIcon 	= "fa fa-plus-circle";
				$scope.deleteIcon 	= "fa fa-trash-o";
				
				$scope.selectedIcon = "fa fa-star";
				$scope.unselectedIcon = "fa fa-star-o";

				$scope.appendApi 			= null;
				$scope.deleteApi 			= null;

				$scope.nodeEventHandler 	= function( data, field, instance ){};
				$scope.onNodeClickHandler 	= function(node){};
				$scope.filterHandler 		= function(data, name, value){};
				
				$scope.applyAppendHandler 	= function(tree, node, instance){return true;};
				$scope.appendHandler 		= function(node, instance){};
				
				$scope.applyDeleteHandler 	= function(tree, node, instance){return true;};
				$scope.deleteHandler 		= function(node, instance){};

				$scope.mutipleSelectedHandler = function( node ){};
				$scope.singleSelectedHandler = function( node ){};

				$scope.applyMutipleSelectedHandler = function(node){return false;};
				$scope.applySingleSelectedHandler = function(node){return false;};

				$scope.singleSelectedId = null;
				$scope.seletedArray = [];
		 		$scope.tree = [
					{id:0, isOpen:false, name:{label:"name",value:"nodeA",icon:"fa fa-arrow-right"},
						sub:[ 
							{id:3, isOpen:false, name:{label:"name",value:"nodeA",icon:"fa fa-arrow-right"},
								sub:[ 
									{id:6, isOpen:false, name:{label:"name",value:"nodeA",icon:"fa fa-arrow-right"},
										sub:[ 
											{id:9, isOpen:false, name:{label:"name",value:"nodeA",icon:"fa fa-arrow-right"},
												sub:[]} 
										]} 
								]} 
						]},
				];

				/**
				*	search tree by node id
				*	@params tree object
				*	ex. [{ id:0, name:"nodeA", isOpen:false, 
				*			sub:[{id:3, name:"sub-3", isOpen:false, sub:[]} ]},
				*	]
				*	@params id int
				*/
				function search( tree, id ){
					var result = null;
					for(var index in tree){
						if( tree[index].id==id ){
							result = tree[index];
						}
						if( tree[index].sub.length ){
							var subResult = search( tree[index].sub, id );
							if(subResult){
								result = subResult;
							}
						}
					}
					return result;
				}

				/**
				*	Get node by id to search tree.
				*	@params id int
				*	@return node or null
				*/
				function getNodeById( id ){
					var tree = $scope.tree;
					return search(tree,id);
				}

				/**
				*	Append node by parent id into this directive tree.
				*	@params newNode json object
				*	@params id int
				*/
				function appendByParentId( newNode, id ){
					var node = getNodeById(id);
					node.sub.push(newNode);
				}

				/**
				*	Get node by id to search tree.
				*	@params id int
				*	@return node or null
				*/
				function removeNodeByNode( removeNode ){
					var tree = $scope.tree;
					var node = search(tree,removeNode.parentId);
					var isSuccess = false;
					for( index in tree ){
						if( tree[index].id == removeNode.id ){
							tree.splice(index,1);
							isSuccess = true;
						}
					}
					if(!isSuccess){
						for(var index in node.sub){
							if( node.sub[index].id == removeNode.id ){
								node.sub.splice(index,1);
								isSuccess = true;
							}
						}
					}
					return isSuccess;
				}

				/**
				*	Append node into tree by root.
				*/				
				$scope.appendNodeInRoot = function(){
					getDefaultAppendUi();
				}

				/**
				*	Node selected then into array
				*	@param node This node selected.
				*/
				function seletedIntoArray(node){
					var array = $scope.seletedArray;
					var isExist = false;
					for( var index in array ){
						if( node.id == array[index] ){
							isExist = true;
						}
					}
					if(!isExist){
						$scope.seletedArray.push(node.id);
					}
					return !isExist;
				}

				/**
				*	Node unselected from array
				*	@param node This node unselected.
				*/
				function unseletedFromArray(node){
					var array = $scope.seletedArray;
					var isSuccess = false;
					for(var index in array){
						if(node.id == array[index]){
							array.splice(index, 1);
							isSuccess = true;
						}
					}
					return isSuccess;
				}

				/**
				*	Node selected then doing something.
				*	@param node This node unselected.
				*/
				function selectedEvent(node){
					if(seletedIntoArray(node)){
						node.isSelected = !node.isSelected;
					}
				}

				/**
				*	Node unselected then doing something.
				*	@param node This node unselected.
				*/
				function unselectedEvent(node){
					if(unseletedFromArray(node)){
						node.isSelected = !node.isSelected;
					}
				}

				/**
				*	Node mutiple selected default handler.
				*	@param node This node unselected.
				*/
				function mutipleSelectedDefaultHandler( node ){
					if(!node.isSelected){
						selectedEvent(node);
					}else{
						unselectedEvent(node);
					}
				}

				/**
				*	Node single selected default handler.
				*	@param node This node unselected.
				*/
				function singleSelectedDefaultHandler( node ){
					$scope.singleSelectedId = node.id;
				}

				/**
				*	Node selected event.
				*/
				$scope.selectedNode = function( node ){
					if( $scope.applyMutipleSelectedHandler( node ) ){
						$scope.mutipleSelectedHandler( node );
					}

					if( $scope.applySingleSelectedHandler( node ) ){
						$scope.singleSelectedHandler(node);
					}
				}

				/**
				*	The ui check does user selected single item
				*	and return true or false for ui that's show or hide icon.
				*	@param node 
				*/
				$scope.isSingleSelected = function(node){
					if( node.id == $scope.singleSelectedId ){
						return true;
					}
					return false;
				}

				/**
				*	On node click run handler.
				*	@param node object 
				*/
				$scope.onNodeClick = function( node ){
					var instance = {
						openByNode:function(){
							var api = $scope.loadApi + "/?parentId=" + node.id;
							$scope.get( api, function(data){
								node.sub = [];
								if( data.records.length>0 ){
									getDefaultLoadHandler( data, node );
								}
							});
						}
					};
					$scope.onNodeClickHandler(node, instance);
					node.isOpen = !node.isOpen;
				}

				/**
				*	use $scope.modal function
				*	@param title Modal title name.
				*	@param body Push object into Modal directive is body front.
				*	@param footerHandler That's result data from modal of user key-in input data. 
				*/
				function modalShow( title, body, footerHandler ){
					var modalObject = {
						controls:[{
								position:"header",
								type:"text",
								label:title,
							},
							{
								position:"footer",
								type:"button",
								label:"確定",
								target:footerHandler
							},{
								position:"footer",
								type:"button",
								label:"取消",
								target:function(){}
							}]
					};
					for( var index in body ){
						body[index]["position"] = "body";
						modalObject.controls.push(body[index]);
					}
					$scope.modal.config(modalObject);
					$scope.modal.show();
				}

				/**
				*	get tree to server by api.
				*	@param api
				*	@param inputData
				*	@param callback function(data, status, headers, config){}
				*/
				$scope.get = function( api, callback ){
					var request = {
						method: 'GET',
					 	url: api,
					 	headers: configs.api.headers
					};
					$http(request).success(function(data, status, headers, config){
						callback(data, status, headers, config);
					}).error(function(data, status, headers, config){
						$scope.alert.show("取得資料發生錯誤請重新嘗試。");
					});
				}

				/**
				*	create node to server by api.
				*	@param api
				*	@param inputData
				*	@param callback function(data, status, headers, config){}
				*/
				$scope.create = function( api, inputData, callback ){
					var request = {
						method: 'POST',
					 	url: api,
					 	headers: configs.api.headers,	
					 	data: inputData
					};
					$http(request).success(function(data, status, headers, config){
						callback(data, status, headers, config);
					}).error(function(data, status, headers, config){
						$scope.alert.show("新增發生錯誤請重新嘗試。");
					});
				}

				/**
				*	update node to server by api.
				*	@param api
				*	@param inputData
				*	@param callback function(data, status, headers, config){}
				*/
				$scope.update = function( api, inputData, callback ){
					var request = {
						method: 'PUT',
					 	url: api,
					 	headers: configs.api.headers,	
					 	data: inputData
					};
					$http(request).success(function(data, status, headers, config){
						callback(data, status, headers, config);
					}).error(function(data, status, headers, config){
						$scope.alert.show("更新發生錯誤請重新嘗試。");
					});
				}

				/**
				*	remove node to server by api.
				*	@param api
				*	@param callback function(data, status, headers, config){}
				*/
				$scope.remove = function( api, callback ){
					var request = {
						method: 'DELETE',
					 	url: api,
					 	headers: configs.api.headers
					};
					$http(request).success(function(data, status, headers, config){
						callback(data, status, headers, config);
					}).error(function(data, status, headers, config){
						$scope.alert.show("刪除發生錯誤請重新嘗試。");
					});
				}

				/**
				*	In this directive tree for node attribute click event. 
				*	@param api
				*	@param callback function
				*/
				$scope.onAttributeClick = function( nodeAttribute, attributeName ){
					var instance = {
						update:function( api, fromData ){
							$scope.update( api, fromData, function(data, status, headers, config){
								nodeAttribute[attributeName].value = fromData.data[attributeName];
							});
						},
						modal:function( callback ){
							getDefaultUpdateUi( nodeAttribute, attributeName, callback );
						}
					};
					$scope.nodeEventHandler( nodeAttribute, attributeName, instance );
				}

				/**
				*	In this directive tree for append node.
				*	@param node All new node data.
				*/
				$scope.appendNode = function(node){
					if($scope.applyAppendHandler($scope.tree, node)){
						var instance = {
							createByApi:function( api, inputData, callback ){
								$scope.create( api, inputData, callback );
							},
							modal:function( title, body, callback ){
								modalShow( title, body, callback );
							},
							getNodeFormat:function( record ){
								return getNodeFormat( record );
							}
						};
						$scope.appendHandler($scope.tree, node, instance);
					}else{
						$scope.alert.show("[新增] 發生錯誤請確認是否選擇正確的群组");
					}
				}

				/**
				*	In this directive tree for append node.
				*	@param node That's you wanted to delete node.
				*/
				$scope.deleteNode = function(node){
					if($scope.applyDeleteHandler($scope.tree, node)){
						var instance = {
							deleteByApi:function( callback ){
								$scope.remove( $scope.deleteApi+"/"+node.id, callback );
							},
							remove:function(){
								if(!removeNodeByNode( node )){
									$scope.alert.show("[刪除] 發生錯誤");			
								}
							}
						};
						$scope.deleteHandler($scope.tree, node, instance);
					}else{
						$scope.alert.show("[刪除] 發生錯誤請確認是否選擇正確");
					}
				}

				/**
				*	The filter is for ui to filter really value output screen.
				*	@param data The input node all of attribute item.
				*	@param name The input node of attribute name.
				*	@param value The input node of attribute value.
				*/
				$scope.filter = function(data, name, value){
					return ($scope.filterHandler(data, name, value)||value);
				}

				/**
				*	In this directive tree for the node format get.
				*	@param record object That's server output record.
				*/
				function getNodeFormat( record ){
					return {
						parentId:record['parent_group_id'],
						id:record['id'],
						name:{label:"名稱:", value:record["name"], icon:""},
						type:{label:"種類:", value:record["type"], icon:""},
						isSelected:false,
						isOpen:false,
						sub:[]
					};
				}

				/**
				*	In this directive tree for default load handler proccess.
				*	@param data object 
				*	@param node object 
				*/
				function getDefaultLoadHandler(data, node){
					var records = data.records;
					for(var index in records){
						var record = getNodeFormat( records[index] );
						if (node){
							node.sub.push( record );
						}else{
							$scope.tree.push( record );
						}
					}
				}
				/**
				*	Get modal default body format in this tree for you use.
				*	@param node If you has default node that's you can input
				*				then build format return for you.
				*/
				function getModalBodyConfig(node){
					var typeLabel, selectedNode;
					if(node){
						typeLabel = $scope.filter(node, "type", node.type.value);
						selectedNode = {text:typeLabel,value:node.type.value};
					}
					return [{
						type:"select",
						label:"群组種類",
						attributeName:"type",
						attribute:{
							selected:(node?selectedNode:{text:"請選擇",value:null}),
							list:[
								{text:"群组",value:1},
								{text:"產品群",value:2},
							]
						}
					},
					{
						type:"input",
						label:"群组名稱",
						attributeName:"name",
						attribute:(node?node.name.value:"")
					}];
				}

				/**
				*	In this directive tree for default append handler proccess.
				*	@param tree
				* 	@param node That's append in this node sub.
				* 	@param instance Doing something is tree.
				*/
				function getDefaultAppendHandler(tree, node, instance){
					var body = getModalBodyConfig();
					modalShow( "新增", body, function(keyin){
						if( keyin.name!="" && keyin.type.value ){
							var inputData = { 
								"name":keyin.name, 
								"type":(keyin.type.value==1?"sub":"product"), 
								"parent_group_id":node.id 
							};
							instance.createByApi( $scope.appendApi, inputData, function(data, status, headers, config){
								var newNode = instance.getNodeFormat({
									 "id":data.id, 
									 "parent_group_id":node.id, 
									 "name":keyin.name, 
									 "type":keyin.type.value 
								});
								node.sub.push( newNode );
							});
						}
					});
				}

				/**
				*	In this directive tree for default append ui flow.
				*/
				function getDefaultAppendUi(){
					var body = getModalBodyConfig();
					modalShow( "新增", body, function(keyin){
						if( keyin.name!="" && keyin.type.value ){
							var inputData = { 
								"name":keyin.name, 
								"type":(keyin.type.value==1?"sub":"product"), 
								"parent_group_id": $scope.rootId
							};
							$scope.create( $scope.appendApi, inputData, function(data, status, headers, config){
								var newNode = getNodeFormat({
									 "id":data.id, 
									 "parent_group_id":$scope.rootId, 
									 "name":keyin.name, 
									 "type":keyin.type.value 
								});
								$scope.tree.push( newNode );
							});
						}
					});
				}

				/**
				*	In this directive tree for default update ui flow.
				*/
				function getDefaultUpdateUi( data, attributeName, callback ){
					var body = [{
						type:"input",
						label:data[attributeName].label,
						attributeName:attributeName,
						attribute:data[attributeName].value
					}];
					modalShow( "更新", body, function(keyin){
						callback(keyin);
					});
				}

				/**
				*	In this directive tree for default delete ui flow handler.
				*/
				function getDefaultDeleteHandler(tree, node, instance){
					var body = [{
						type:"text",
						label:"確認是否刪除?",
					}];
					modalShow( "刪除", body, function(keyin){
						instance.deleteByApi(function(data, status, headers, config){
							instance.remove(node);
						});
					});	
				}

				$scope.instance = {
					/**
					*	Set icon for tree node ui.
					*/
					setOpenIcon:function( icon ){
						$scope.openIcon = icon;
					},
					setCloseIcon:function( icon ){
						$scope.closeIcon = icon;
					},
					setAppendIcon:function( icon ){
						$scope.createIcon = icon;
					},
					setDeleteIcon:function( icon){
						$scope.deleteIcon = icon;
					},
					setTitleIcon:function( icon ){
						$scope.nameIcon = icon;
					},

					/**
					*	On open/close doing something you wanted.
					*
					*/
					onOpenHandler:function( handler ){
						$scope.openHandler = handler;
					},
					onCloseHandler:function( handler ){
						$scope.closeHandler = handler;
					},

					/**
					*	Get tree all of data.
					*/
					getTree:function(){
						return $scope.tree;
					},

					/**
					*	Get selected in tree of array.
					*/
					getSelectedToArray:function(){
						return $scope.seletedArray;
					},

					/**
					*	Get selected in tree of array.
					*/
					getSingleSelectedId:function(){
						return $scope.singleSelectedId;
					},

					/**
					*	Set Root_ID
					*/
					setRootId:function(id){
						$scope.rootId = id;
					},

					/**
					*	Load api setting function.
					*	@param api string
					*	@param handler function( data, instance ){}
					*/
					loadByApi:function( api, queryString, handler ){
						$scope.loadApi = api;
						$scope.get( api+(queryString?queryString:""), function(data){
							$scope.tree = [];
							if( !handler ){
								getDefaultLoadHandler( data );
							}else{
								var instance = {
									appendIntoRootNode:function(newNode){
										$scope.tree.push(newNode);
									},
									appendByParentId:function( newNode, id ){
										appendByParentId( newNode, id );
									},
									getTree:function(){
										return $scope.tree;
									},
								};
								handler( data, instance );
							}
						});
					},

					loadMutipleSelectedIds:function(ids){
						for(var index in ids){
							var node = getNodeById( ids[index] );
							if(node){
								mutipleSelectedDefaultHandler( node );
							}
						}
					},

					loadSingleSelectedId:function(id){
						$scope.singleSelectedId = id;
					},

					/**
					*	Append node into tree by root. 
					*/
					appendNodeInRoot:function(){
						getDefaultAppendUi({label:"群组名稱",attributeName:"name"}, function(keyin){
							// console.log(keyin.name);
						})
					},

					/**
					*	Binding append icon
					*	@param isApply boolean
					*/
					isApplyAppend:function( isApply ){
						$scope.isApplyAppend = isApply;
					},

					/**
					*	Binding delete icon
					*	@param isApply boolean
					*/
					isApplyDelete:function( isApply ){
						$scope.isApplyDelete = isApply;
					},

					/**
					*	Binding append api setting function.
					* 	@param api string
					* 	@param applyHandler fucntion(tree, node, instance){return true;}
					*	@param handler function(node, instance){}
					*/
					bindAppendHandler:function( api, applyHandler, handler ){
						$scope.appendApi = api;
						$scope.applyAppendHandler = applyHandler;
						$scope.appendHandler = (handler||function(tree, node, instance){
							getDefaultAppendHandler(tree, node, instance);
						});
					},

					/**
					*	Binding delete api setting function.
					* 	@param api string
					* 	@param applyHandler fucntion(tree, node, instance){return true;}
					*	@param handler function(node, instance){}
					*/
					bindDeleteHandler:function( api, applyHandler, handler ){
						$scope.deleteApi = api;
						$scope.applyDeleteHandler = applyHandler;
						$scope.deleteHandler = (handler||function(tree, node, instance){
							getDefaultDeleteHandler(tree, node, instance);
						});
					},

					/**
					*	Binding single selected handler setting.
					* 	@param applyHandler fucntion(tree, node, instance){return true;}
					*	@param handler function(node, instance){}
					*/
					bindSingleSelectedHandler:function( applyHandler, handler ){
						$scope.applySingleSelectedHandler = applyHandler;
						$scope.singleSelectedHandler = (handler||function(node){
							singleSelectedDefaultHandler(node);
						});
					},

					/**
					*	Binding single selected handler setting.
					* 	@param applyHandler fucntion(tree, node, instance){return true;}
					*	@param handler function(node, instance){}
					*/
					bindMutipleSelectedHandler:function( applyHandler, handler ){
						$scope.applyMutipleSelectedHandler = applyHandler;
						$scope.mutipleSelectedHandler = (handler||function(node){
							mutipleSelectedDefaultHandler(node);
						});
					},

					/**
					*	On node click event handler binding.
					*/
					onNodeClick:function( handler ){
						$scope.onNodeClickHandler = handler;
					},

					/**
					*	On node event click event handler binding.
					* 	@param handler function( data, field, $scope.instance ){}
					*/
					onNodeEvent:function( handler ){
						$scope.nodeEventHandler = handler;
					},

					onFilterNode:function( handler ){
						$scope.filterHandler = handler;
					},

					/**
					*	In SbTree modal handler.
					*/
					modal:function( title, body, footerHandler ){
						modalShow( title, body, footerHandler );
					},

					/**
					*	In SbAlert modal handler.
					*	@param msg string
					*/
					alert:function( msg ){
						$scope.alert.show(msg);
					},

				};

		 	},
			scope: {				
				instance: '=?instance',
			},
		};
	});

});