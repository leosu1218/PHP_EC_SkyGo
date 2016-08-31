/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'jquery',
    'text!directives/UnifiedOrderList/view.html'
], function (
    angular,
    app,
    configs,
    $,
    view)
{
    app.directive("unifiedOrderList", function () {
        return {
            restrict: "E",
            template: view,
            controller: function ($scope, $http, $timeout) {

                $scope.value_check = false;
                /**
                 * Defined default search model's params.
                 */
                $scope.defaultSearch = function(){
                    $scope.pageSize = 10;
                    $scope.status = {
                        list:[
                            {text:"尚未付款", state:"prepared"},
                            {text:"付款失敗", state:"abnormal"},
                            {text:"等待出貨(付款成功)", state:"paid"},
                            {text:"已出貨(未到貨)", state:"delivering"},
                            {text:"鑑賞期(已到貨未滿7日)" , state:"warrantyperiod"},
                            {text:"申請取消訂單", state:"applycancel"},
                            {text:"已完成(超過鑑賞期)", state:"completed"},
                            {text:"訂單已取消", state:"cancel"},
                            {text:"訂單已退貨", state:"returned"},
                            {text:"未選擇", state:"all"},
                        ]
                    }

                    $scope.payTypes = {
                        list:[
                            {text:"信用卡付款", type:"neweb"},
                            {text:"超商繳款", type:"MMK"},
                            {text:"ATM付款", type:"ATM"},
                            {text:"超商代收", type:"CS"}
                        ]
                    };

                    $scope.fixedSearch = $scope.fixedSearch || {};
                    $scope.search = {};
                    $scope.search.keyword = null;
                    $scope.selectedItem = $scope.status.list[9];
                    $scope.search.state = "all";

                    $scope.search.orderDateStart = null;
                    $scope.search.orderDateEnd = null;
                    $scope.search.deliveryDateStart = null;
                    $scope.search.deliveryDateEnd = null;
                    $scope.search.payDateTimeStart = null;
                    $scope.search.payDateTimeEnd = null;

                    $timeout(function() {
                        $scope.startDateOpen.setdate(null);
                        $scope.startDateClose.setdate(null);
                        $scope.endDateOpen.setdate(null);
                        $scope.endDateClose.setdate(null);
                        $scope.payDateOpen.setdate(null);
                        $scope.payDateClose.setdate(null);
                    }, 200);


                    $scope.deliveryStateText = "delivering";
                    $scope.search.order = "DESC";
                    $scope.enableSelect = $scope.enableSelect || false;
                    $scope.api = $scope.api || configs.api.order + "/search";
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
                        $scope.search['orderDateStart'] = $scope.startDateOpen.getdate();
                    })
                    $scope.startDateClose.onDateInputChanged(function() {
                        $scope.search['orderDateEnd'] = $scope.startDateClose.getdate();
                    })
                    $scope.endDateOpen.onDateInputChanged(function() {
                        $scope.search['deliveryDateStart'] = $scope.endDateOpen.getdate();
                    })
                    $scope.endDateClose.onDateInputChanged(function() {
                        $scope.search['deliveryDateEnd'] = $scope.endDateClose.getdate();
                    })
                    $scope.payDateOpen.onDateInputChanged(function() {
                        $scope.search['payDateTimeStart'] = $scope.payDateOpen.getdate();
                    })
                    $scope.payDateClose.onDateInputChanged(function() {
                        $scope.search['payDateTimeEnd'] = $scope.payDateClose.getdate();
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
                * Get order spec
                */
                function getProductSpec(serial){
                    var api = configs.api.order + "/spec/search/1/50/?keyword=&serial=" + serial;
                    var request = {
                        method: 'GET',
                        url: api,
                        headers: configs.api.headers,
                    };

                    $http(request).success(function(data, status, headers, config) {
                        $scope.productSpec = data.records;
                    }).error(function(data, status, headers, config){
                        $scope.alert.show("回壓物流有誤，請再次嘗試。");
                    });
                }

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
                 * Display state text field.
                 *
                 * @param value
                 * @param row
                 * @returns {string}
                 */
                function displayPayState(value,row) {
                    if (row.pay_notify_datetime != null || row.state == 2) {
                        value = "已付款";
                    }else {
                        value = "未付款";
                    }
                    return value;
                }

                /**
                 * Display state text field.
                 *
                 * @param value
                 * @returns {string}
                 */
                function displayPayType(value) {
                    var type = "";
                    var item = {};
                    for(var index in $scope.payTypes.list) {
                        item = $scope.payTypes.list[index];
                        if(item.type == value) {
                            type = item.text;
                        }
                    }
                    return type;
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

                    document.location.href="#!/management/" + row.serial;
                    
                }

                /**
                 * View product spec detail.
                 * @param row
                 * @param attribute
                 */
                function viewSpecDetail(row, attribute) {
                    $('#myModal').modal('show');
                    $scope.productSpecNumber = row.serial;
                    getProductSpec($scope.productSpecNumber);      
                }

                $scope.updateNumber = function(items){
                    var request = {
                        method: 'PUT',
                        url: configs.api.order + '/spec/update/product/' + items.spec_id,
                        data: {number: items.spec_product_number},
                        headers: configs.api.headers
                    };

                    $http(request).success(function(data, status, headers, config) {
                        var api = configs.api.order + "/spec/search/1/50/?keyword=&serial=" + $scope.productSpecNumber ;
                        var request = {
                            method: 'GET',
                            url: api,
                            headers: configs.api.headers,
                        };

                        $http(request).success(function(data, status, headers, config) {
                            $scope.productSpec = data.records;
                            checkSpecStatus($scope.productSpec);
                        })
                    }).error(function(data, status, headers, config) {
                        $scope.alert("更新錯誤！");
                    });
                }

                function checkSpecStatus(items){
                    var status = new Array;
                    for(var key in items){
                        status.push(Number(items[key].spec_status));
                        var flag = status.reduce(function (previousValue, currentValue, index, array) {
                            return previousValue + currentValue;
                        })
                        if (flag == items.length) {
                            updateStatus(items);
                        }
                    }
                }

                function updateStatus(items){
                    var request = {
                        method: 'PUT',
                        url: configs.api.order + '/spec/update/delivery/' + items[0].order_id,
                        data: items,
                        headers: configs.api.headers
                    };

                    $http(request).success(function(data, status, headers, config) {
                        $scope.searchButtonOnClick();
                    }).error(function(data, status, headers, config) {
                        $scope.alert("更新錯誤！");
                    });
                }
                
                $scope.changeStatus = function(item){
                    if (item == 1) {
                        return "已出貨";
                    }else if (item == 0) {
                        return "未出貨";
                    }
                }

                function deleteTime(value) {
                    if (value) {
                        var newValue = value.substr(0,10);
                        return newValue; 
                    }else{
                        return value;    
                    }             
                }

                /**
                 * On table ready.
                 * Watching $scope.table variable.
                 * instance = $scope.table.
                 */
                $scope.$watch('table', function(instance) {
                    if(instance) {
                        $scope.table.configField([
                            {attribute: "id",                       name: "ID"},
                            {attribute: "serial",                   name: "訂單編號"},
                            {attribute: "buyer_name",               name: "訂購人"},
                            {attribute: "receiver_name",            name: "收件人"},
                            {attribute: "final_total_price",        name: "實付額"},
                            {attribute: "create_datetime",          name: "訂購日", filter:deleteTime},
                            {attribute: "pay_notify_datetime",      name: "付款日", filter:deleteTime},
                            {attribute: "payment_type",             name: "付款別", filter:displayPayType},
                            {attribute: "fare_type",                name: "配送方式", filter:displayFareType},
                            {attribute: "delivery_datetime",        name: "出貨日"},
                            {attribute: "pay_notify_datetime",      name: "付款狀態", filter:displayPayState},
                            {attribute: "stateText",                name: "訂單管理", filter:displayState},
                            {attribute: "control", name: "管理", controls: [
                                    {type: "button", icon: "fa-search", click: viewDetail},
                                    {type: "button", icon: "fa fa-file-text-o", click: viewSpecDetail}
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