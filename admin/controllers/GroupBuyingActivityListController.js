/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'message', 'configs', 'datetime'], 
	function (angular, app, createController, message, configs, datetime) {

	return app.controller("GroupBuyingActivityListController", 
		createController(function ($scope , $routeParams, $http, $timeout) {

            /**
             * Defined default search model's params.
             */
			$scope.defaultSearch = function(){
                $scope.pageSize = 10;
				$scope.activityStatus = {
					list:[
						{text:"活動尚未開始", state:"prepare"},
						{text:"活動進行中", state:"started"},
						{text:"出貨處理中(活動結束)", state:"waitingdelivery"},
						{text:"出貨處理完成", state:"deliveryall"},
						{text:"送貨中(已出貨)" , state:"deliverycompleted"},
						{text:"鑑賞期(到貨, 可接受退貨)", state:"warranty"},
						{text:"退貨處理中(鑑賞結束)", state:"waitingreturned"},
						{text:"退貨處理完成(可對帳)", state:"returnedall"},
                        {text:"等待團購主對帳", state:"waitingstatement"},
                        {text:"對帳正常(可撥款)", state:"confirmedstatement"},
                        {text:"對帳異常", state:"abnormalstatement"},
                        {text:"活動結案", state:"completed"},
						{text:"未選擇", state:"all"},
					]
				};

				$scope.search = {};
				$scope.search.keyword = null;
				$scope.search.startDateOpen = null;
				$scope.search.startDateClose = null;
				$scope.search.endDateOpen = null;
				$scope.search.endDateClose = null;
				$scope.selectedItem = $scope.activityStatus.list[12];
                $scope.search.state = "all";

                $timeout(function() {
                    $scope.startDateOpen.setdate(null);
                    $scope.startDateClose.setdate(null);
                    $scope.endDateOpen.setdate(null);
                    $scope.endDateClose.setdate(null);
                }, 200);
			};

			$scope.defaultSearch();

            /**
             * Format datetime object to string (Y:m:d H:i:s)
             *
             * @param date
             * @param time
             * @returns {string}
             */
			function dateTimeFormat (date, time) {
				time = time || date;
			  	var year = "" + date.getFullYear();
			  	var month = "" + (date.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
			  	var day = "" + date.getDate(); if (day.length == 1) { day = "0" + day; }
			  	var hour = "" + time.getHours(); if (hour.length == 1) { hour = "0" + hour; }
			  	var minute = "" + time.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
			  	var second = "" + time.getSeconds(); if (second.length == 1) { second = "0" + second; }
			  	return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
			}


            $timeout(function() {
                $scope.startDateOpen.onDateInputChanged(function() {
                    $scope.search['startDateOpen'] = $scope.startDateOpen.getdate();
                })
                $scope.startDateClose.onDateInputChanged(function() {
                    $scope.search['startDateClose'] = $scope.startDateClose.getdate();
                })
                $scope.endDateOpen.onDateInputChanged(function() {
                    $scope.search['endDateOpen'] = $scope.endDateOpen.getdate();
                })
                $scope.endDateClose.onDateInputChanged(function() {
                    $scope.search['endDateClose'] = $scope.endDateClose.getdate();
                })
            }, 200);

            /**
             * On user selected state that want to search.
             * @param item
             */
			$scope.onStateSelectorChanged = function(item) {
                $scope.selectedItem = item;
                $scope.search.state = item.state;
            };

            /**
             * On user click search button.
             */
			$scope.searchButtonOnClick = function() {
				var listUrl = configs.api.groupbuyingActivity + "/search";
				$scope.table.loadByUrl( listUrl, 1, $scope.pageSize, 
					function(data, status, headers, config) {
						// Handle reload table success;
					}, 
					function(data, status, headers, config) {
						$scope.alert.show("無法搜尋到資料");
					},
					$scope.search
				);
			};

            /**
             * Display state text field.
             *
             * @param value
             * @param row
             * @returns {string}
             */
			function displayState(value, row) {
                var state = "未定義(回報系統商)";
                var item = {};
                for(var index in $scope.activityStatus.list) {
                    item = $scope.activityStatus.list[index];
                    if(item.state == value) {
                        state = item.text;
                    }
                }
                return state;
			}

            /**
             * Edit a activity record's note.
             *
             * @param row json Row object of table.
             * @param attribute string Attribute name.
             */
            function editNote(row, attribute) {

                function updateNote(item) {
                    var request = {
                        method: 'PUT',
                        url: configs.api.groupbuyingActivity + '/' + row.id + '/note',
                        data: {note: item.note},
                        headers: configs.api.headers
                    };

                    $http(request).success(function(data, status, headers, config) {
                        row.note = item.note;
                    }).error(function(data, status, headers, config) {
                        $scope.alert("更新錯誤！");
                    });
                }

                $scope.modal.config({
                    controls:[
                        {position:"header", type:"text",label:"修改欄位"},
                        {
                            position:"body",
                            attributeName :"note",
                            attribute:row.note,
                            type:"input",
                            label:"備註"
                        },
                        {
                            position:"footer",
                            type:"button",
                            label:"確定",
                            target: updateNote
                        }
                    ]
                });

                $scope.modal.show();
            }

            /**
             * View a activity's record detail.
             *
             * @param row json Row object of table.
             * @param attribute string Attribute name.
             */
            function viewDetail(row, attribute) {
                location.href = configs.path.groupbuyingActivity + '/' + row.id;
            }

            /**
             * View a activity's buyer website.
             *
             * @param row json Row object of table.
             * @param attribute string Attribute name.
             */
            function viewActivitySite(row, attribute) {
                window.open(location.origin + "/gb.html#!/"+row.id);
            }

            /**
             * Check all selected activity's state equal the state object.
             *
             * @param states
             * @param selected
             * @return bool Return true when all selected as same as state object.
             */
            function checkEachState(states, selected) {
                var activity;
                var index;

                if(selected.length == 0) {
                    return false;
                }

                for(index in selected) {
                    activity = selected[index];
                    if(activity.stateText != states.state) {
                        return false;
                    }
                }

                return true;
            }

            /**
             * Change activity's state.
             */
			function changeState() {
                var selected = $scope.table.getSelectedField();
                var message = "";

                if(checkEachState($scope.activityStatus.list[3], selected)) {
                    message += "是否將所選擇的【" + $scope.activityStatus.list[3].text + "】活動，";
                    message += "變更狀態為【" + $scope.activityStatus.list[4].text + "】";
                    $scope.alert.show(message, function() {
                        updateStateBySelected($scope.activityStatus.list[4], selected);
                    });
                }
                else if(checkEachState($scope.activityStatus.list[7], selected)) {
                    message += "是否將所選擇的【" + $scope.activityStatus.list[7].text + "】活動，";
                    message += "變更狀態為【" + $scope.activityStatus.list[8].text + "】";
                    $scope.alert.show(message, function() {
                        updateStateBySelected($scope.activityStatus.list[8], selected);
                    });
                }
                else if(checkEachState($scope.activityStatus.list[9], selected)) {
                    message += "是否將所選擇的【" + $scope.activityStatus.list[9].text + "】活動，";
                    message += "變更狀態為【" + $scope.activityStatus.list[11].text + "】";
                    $scope.alert.show(message, function() {
                        updateStateBySelected($scope.activityStatus.list[11], selected);
                    });
                }
                else if(checkEachState($scope.activityStatus.list[10], selected)) {
                    message += "是否將所選擇的【" + $scope.activityStatus.list[10].text + "】活動，";
                    message += "變更狀態為【" + $scope.activityStatus.list[11].text + "】";
                    $scope.alert.show(message, function() {
                        updateStateBySelected($scope.activityStatus.list[11], selected);
                    });
                }
                else {
                    message += "您所選擇的活動必須全部為同一個狀態，且必須是";
                    message += "【" + $scope.activityStatus.list[3].text + "】，";
                    message += "【" + $scope.activityStatus.list[7].text + "】，";
                    message += "【" + $scope.activityStatus.list[9].text + "】，";
                    message += "【" + $scope.activityStatus.list[10].text + "】其中一種活動。";
                    $scope.alert.show(message);
                }
            }

            /**
             * Get id's list from a json data array.
             * @param data
             * @returns {{ids: Array}}
             * @constructor
             */
            function GetIds( data )  {
                var fromData = {ids:[], entity_type:"groupbuying"};
                for(var index in data) {
                    fromData.ids.push(data[index].id);
                }
                return fromData;
            }

            function PickupDownload() {
                var data = $scope.table.getSelectedField();
                if(data.length>0) {
                    $scope.downloadByUrl( configs.api.exportFile+"wholesale/pickup", GetIds(data), function(result){

                        if(result.isSuccess) {
                            location.href = configs.path.report + 'pickup/' + result.fileName;
                            $scope.searchButtonOnClick();
                        }
                        else {
                            $scope.alert.show("下載錯誤！請確認活動是否在 [ " + $scope.activityStatus.list[2].text +" ] 的狀態。");
                        }
                        

                    }); 
                }
                else {
                    $scope.alert.show("請選取活動。");
                }
            }

            function ReturnedDownload()
            {
                var data = $scope.table.getSelectedField();
                if(data.length>0){
                    $scope.downloadByUrl( configs.api.exportFile+"wholesale/returned", GetIds(data), 
                        function(result){
                            if(result.isSuccess)
                            {
                                location.href = configs.path.report + 'returned/' + result.fileName;
                                $scope.searchButtonOnClick();
                            }
                            else
                            {
                                $scope.alert.show("下載錯誤！請確認活動是否在 [ " + $scope.activityStatus.list[6].text + " ] 的狀態。");
                            }
                        }
                    );
                }
                else
                {
                    $scope.alert.show("請選取活動。");
                }

            }

            function InvoiceDownload()
            {
                var data = $scope.table.getSelectedField();

                if(data.length>0){
                    $scope.downloadByUrl( configs.api.exportFile+"wholesale/invoice", GetIds(data), function(result){

                        if(result.isSuccess)
                        {
                            location.href = configs.path.report + 'invoice/' + result.fileName;
                            $scope.searchButtonOnClick();
                        }
                        else
                        {
                            $scope.alert.show("下載錯誤！請確認活動是否在 [ " + $scope.activityStatus.list[9].text +" ] 的狀態。");
                        }
                        

                    }); 
                }
                else
                {
                    $scope.alert.show("請選取活動。");
                }
            }

            /**
             * Update to server by api.
             *
             * @param states json State object {text:<display text>, state:<server stateText>}
             * @param selected array Selected item from sb-table.
             */
            function updateStateBySelected(states, selected) {
                var index, item, ids, url;

                ids = [];
                for(index in selected) {
                    item = selected[index];
                    ids.push(item.id);
                }

                url = configs.api.groupbuyingActivity + "/list/state";
                var request = {
                    method: 'PUT',
                    url: url,
                    data: {
                        ids:ids,
                        stateText: states.state
                    },
                    headers: {'Content-Type': 'application/json'}
                };

                $http(request).success(function(data, status, headers, config) {
                    var index;
                    for(index in selected) {
                        selected[index].stateText = states.state;
                    }
                }).error(function(data, status, headers, config) {
                    var message = "更改狀態失敗，請重新整理頁面後再嘗試。";
                    $scope.alert.show(message);
                });
            }

			//table
			$timeout(function(){

				//main table for admin to using.
				$scope.table.configField([
                    {attribute: "id", name: "活動ID"},
					{attribute: "name", name: "活動名稱"},
					{attribute: "gbMasterName", name: "團購主"},
					{attribute: "price", name: "價位"},
					{attribute: "start_date", name: "活動開始時間"},
					{attribute: "end_date", name: "活動結束時間"},
					{attribute: "stateText", name: "活動狀態",filter: displayState},
					{attribute: "note", name: "備註"},
					{attribute: "control", name: "控制",controls: [
						{type: "button", icon: "fa-pencil-square", click: editNote},
						{type: "button", icon: "fa-search", click: viewDetail},
						{type: "button", icon: "fa-unlink", click: viewActivitySite}]
					},
				]);

				$scope.searchButtonOnClick();
				$scope.table.rowClickCss({'background-color':'#FFDDAA'});
				$scope.table.onRowClick(function(row, field, instance) {
					if(field != 'control') {
						instance.selected();
					}
				});

			}, 100);

			$scope.downloadByUrl = function( url, data, callback ){
				var request = {
					method: 'POST',
				 	url: url,
				 	data: data,
				 	headers: {'Content-Type': 'application/json'},
				}

				$http(request).success(function(data, status, headers, config) {
					callback( { isSuccess:true, fileName:data.fileName } );
				}).error(function(data, status, headers, config) {
					callback( { isSuccess:false } );
				});
			}
		
			// exporter buttons
			$scope.exportButtons = [
				{
					text:"全選",
					click:function(){
						$scope.table.selectedAllField();
					}
				},
				{
					text:"全取消",
					click:function(){
						$scope.table.selectedCancelAllField();
					}
				},
				{
					text:"檢貨單下載",
					icon:"cloud-download",
					click:function(){ PickupDownload() }
				},
				{
					text:"請款單下載",
					icon:"cloud-download",
					click:function(){ InvoiceDownload() }
				},
				{
					text:"退貨單下載",
					icon:"cloud-download",
					click:function(){ ReturnedDownload() }
				},
				{
					text: "變更狀態",
					click: changeState
				}
			];
		})
	);
	
});