/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/UnifiedReturnedList/view.html'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("unifiedReturnedList", function () {
        return {
            restrict: "E",
            template: view,
            controller: function ($scope, $http, $timeout) {

                /**
                 * Defined default search model's params.
                 */
                $scope.defaultSearch = function(){
                    $scope.pageSize = 10;
                    $scope.status = {
                        list:[
                            {text:"退貨處理中", state:"prepared"},
                            {text:"等待貨物回收", state:"receiving"},
                            {text:"已取消退貨", state:"cancel"},
                            {text:"退貨完成" , state:"completed"},
                            {text:"未選擇", state:"all"},
                        ]
                    };

                    $scope.fixedSearch = $scope.fixedSearch || {};
                    $scope.search = {};
                    $scope.search.keyword = null;
                    $scope.selectedItem = $scope.status.list[4];
                    $scope.search.state = "all";

                    $scope.search.orderDateStart = null;
                    $scope.search.orderDateEnd = null;
                    $scope.search.deliveryDateStart = null;
                    $scope.search.deliveryDateEnd = null;

                    $scope.deliveryStateText = "delivering";
                    $scope.search.order = "DESC";
                    $scope.enableSelect = $scope.enableSelect || false;
                    $scope.api = $scope.api || configs.api.returned + "/search";
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

                    var listUrl = $scope.api;
                    var search = angular.extend($scope.fixedSearch, $scope.search);

                    $scope.table.loadByUrl( listUrl, 1, $scope.pageSize,
                        function(data, status, headers, config) {
                            // Handle reload table success;
                        },
                        function(data, status, headers, config) {
                            $scope.alert.show("無法搜尋到資料");
                        },
                        search
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
                    for(var index in $scope.status.list) {
                        item = $scope.status.list[index];
                        if(item.state == value) {
                            state = item.text;
                        }
                    }
                    return state;
                }

                /**
                 * Display fare type field.
                 *
                 * @param value
                 * @param row
                 * @returns {string}
                 */
                function displayFareType(value, row) {
                    return value;
                }

                /**
                 * View record's detail.
                 * @param row
                 * @param attribute
                 */
                function viewDetail(row, attribute) {
                    var search  = {
                        serial: row.serial,
                    };

                    var apiString = "";
                    if($scope.specApi) {
                        apiString = " api=\"'" + $scope.specApi + "'\"";
                    }

                    var html    = "<order-spec-list " + apiString + " search='" + JSON.stringify(search) + "'></order-spec-list>";
                    var handler = function(){};
                    var title = "訂單內容";
                    var buttonText = "完成";
                    var width = "900";

                    $scope.specAlert.showHtml(html, handler, title, buttonText, width);
                }

                /**
                 * On table ready.
                 */
                $scope.$watch('table', function(instance) {
                    if(instance) {
                        $scope.table.configField([
                            {attribute: "ur_id",                    name: "ID"},
                            {attribute: "serial",                   name: "原訂單編號"},
                            {attribute: "buyer_name",               name: "買家姓名"},
                            {attribute: "buyer_phone_number",       name: "買家電話"},
                            {attribute: "buyer_email",              name: "買家信箱"},
                            {attribute: "final_total_price",        name: "原總金額(含運)"},
                            {attribute: "fare",                     name: "原運費"},
                            {attribute: "fare_type",                name: "原運費類型", filter:displayFareType},
                            {attribute: "ur_create_datetime",       name: "退貨申請時間"},
                            {attribute: "ur_delivery_datetime",     name: "出發取貨時間"},
                            {attribute: "ur_delivery_channel",      name: "物流商"},
                            {attribute: "ur_delivery_number",       name: "物流編號"},
                            {attribute: "stateText",                name: "退貨狀態", filter:displayState},
                            {attribute: "control", name: "", controls: [
                                {type: "button", icon: "fa-search", click: viewDetail},
                            ]
                            },
                        ]);

                        $scope.instance = $scope.table;

                        $scope.searchButtonOnClick();
                        $scope.table.rowClickCss({'background-color':'#FFDDAA'});
                        $scope.table.onRowClick(function(row, field, instance) {
                            if(field != 'control') {
                                if($scope.enableSelect) {
                                    instance.selected();
                                }
                            }
                        });

                        /**
                         * Inject reload list function
                         */
                        $scope.table.reloadList = function() {
                            $scope.searchButtonOnClick();
                        }
                    }
                });
            },
            scope: {
                instance: '=?instance',
                enableSelect: '=?enableSelect',
                fixedSearch: '=?search',
                specApi: '=?specApi',
                api: '=?api',
            }
        }
    });
});