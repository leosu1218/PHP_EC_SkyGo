/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs', 'message', 'datetime'], 
	function (angular, app, createController, configs, message, datetime) {

	return app.controller("ActivityListController", 
		createController(function ($scope, $http, $routeParams, $timeout) {

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

            /**
             * On user changed date input event.
             * format date time to (Y:m:d H:i:s)
             *
             * @param model The date object that user changed field.
             * @param fieldName
             */
            $scope.onDateInputChanged = function(model, fieldName) {
                $scope.search[fieldName] = dateTimeFormat(model);
            };

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
                var listUrl = configs.api.groupbuyingUser + "/self/activity/search";
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
             * View a activity's record detail.
             *
             * @param row json Row object of table.
             * @param attribute string Attribute name.
             */
            function viewDetail(row, attribute) {
                location.href = configs.path.gbActivity + '/' + row.id;
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

            //table
            $timeout(function(){

                //main table for admin to using.
                $scope.table.configField([
                    {attribute: "id", name: "活動ID"},
                    {attribute: "name", name: "活動名稱"},
                    {attribute: "price", name: "價位"},
                    {attribute: "start_date", name: "活動開始時間"},
                    {attribute: "end_date", name: "活動結束時間"},
                    {attribute: "stateText", name: "活動狀態",filter: displayState},
                    {attribute: "control", name: "控制",controls: [
                        {type: "button", icon: "fa-search", click: viewDetail},
                        {type: "button", icon: "fa-unlink", click: viewActivitySite}]
                    },
                ]);

                $scope.searchButtonOnClick();

            }, 100);
	}));	
});