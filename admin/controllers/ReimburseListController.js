/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'message', 'configs', 'datetime'],
    function (angular, app, createController, message, configs, datetime) {

        return app.controller("ReimburseListController",
            createController(function ($scope , $routeParams, $http, $timeout) {

                /**
                 * Defined default search model's params.
                 */
                $scope.defaultSearch = function(){
                    $scope.pageSize = 10;
                    $scope.orderStatus = {
                        list:[
                            {text:"尚未付款", state:"0"},
                            {text:"付款失敗", state:"1"},
                            {text:"等待出貨(付款成功)", state:"2"},
                            {text:"已出貨(未到貨)", state:"4"},
                            {text:"鑑賞期(已到貨未滿7日)" , state:"4"},
                            {text:"申請取消訂單", state:"8"},
                            {text:"已完成(超過鑑賞期)", state:"16"},
                            {text:"訂單已取消", state:"24"},
                            {text:"訂單已退貨", state:"12"}
                        ]
                    }

                    $scope.search = {};
                    $scope.search.keyword = null;
                    $scope.search.startDateOpen = null;
                    $scope.search.startDateClose = null;
                    $scope.search.endDateOpen = null;
                    $scope.search.endDateClose = null;
                    //$scope.selectedItem = $scope.activityStatus.list[3];
                    $scope.search.state = "all";

                    //$timeout(function() {
                    //    $scope.startDateOpen.setdate(null);
                    //    $scope.startDateClose.setdate(null);
                    //    $scope.endDateOpen.setdate(null);
                    //    $scope.endDateClose.setdate(null);
                    //}, 200);
                };

                $scope.defaultSearch();



                //$timeout(function() {
                //    $scope.startDateOpen.onDateInputChanged(function() {
                //        $scope.search['startDateOpen'] = $scope.startDateOpen.getdate();
                //    })
                //    $scope.startDateClose.onDateInputChanged(function() {
                //        $scope.search['startDateClose'] = $scope.startDateClose.getdate();
                //    })
                //    $scope.endDateOpen.onDateInputChanged(function() {
                //        $scope.search['endDateOpen'] = $scope.endDateOpen.getdate();
                //    })
                //    $scope.endDateClose.onDateInputChanged(function() {
                //        $scope.search['endDateClose'] = $scope.endDateClose.getdate();
                //    })
                //}, 200);


                /**
                 * Cancel all selected item of table.
                 */
                $scope.cancelAll = function(){
                    $scope.table.selectedCancelAllField();
                };

                /**
                 * Select all item of table.
                 */
                $scope.selectedAll = function(){
                    $scope.table.selectedAllField();
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
                 * Download exported excel file.
                 * @type {{pickup: Function}}
                 */
                $scope.download = {
                    excel:function(){
                        var data = $scope.table.getSelectedField();
                        if(data.length>0) {
                            $scope.downloadByUrl(
                                configs.api.exportFile+"wholesale/reimburse",
                                GetOpenIds(data),
                                function(result){
                                    if(result.isSuccess) {
                                        location.href = configs.path.report + 'reimburse/' + result.fileName;
                                    }
                                    else {
                                        $scope.alert.show("下載錯誤！請確認訂單是否在 [ 等待出貨(付款成功) ] 的狀態。");
                                    }
                                }
                            );
                        }
                        else {
                            $scope.alert.show("請選取活動。");
                        }
                    }
                };

                /**
                 * Download by REST API
                 * @param url
                 * @param data
                 * @param callback
                 */
                $scope.downloadByUrl = function( url, data, callback ){
                    var request = {
                        method: 'POST',
                        url: url,
                        data: data,
                        headers: {'Content-Type': 'application/json'}
                    }

                    $http(request).success(function(data, status, headers, config) {
                        callback( { isSuccess:true, status:status, fileName:data.fileName } );
                    }).error(function(data, status, headers, config) {
                        callback( { isSuccess:false, status:status } );
                    });
                };

                function GetOpenIds( data )  {
                    var fromData = {ids:[], entity_type:"reimburse"};
                    for(var index in data) {
                        fromData.ids.push(data[index].order_id);
                    }
                    return fromData;
                }

                /**
                 * On user click search button.
                 */
                $scope.searchButtonOnClick = function() {
                    var listUrl = "/api/reimburse/list/searchByAdmin";
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

                function changeState(row, type) {
                    row['stateText'] = 'cancel';
                    var request = {
                        method: 'PUT',
                        url: configs.api.reimburse + '/statusOver/' + row.id,
                        data: row,
                        headers: configs.api.headers
                    };

                    $http(request).success(function(data, status, headers, config) {
                        $scope.alert.show("更新成功！");
                        $scope.searchButtonOnClick();
                    }).error(function(data, status, headers, config) {
                        $scope.alert.show("更新錯誤！");
                    });

                };


                function displayPayType(value, row) {
                    if(value == 'neweb'){
                        return '刷卡';
                    }else if(value == 'MMK'){
                        return '超商付款';
                    }else if(value == 'ATM'){
                        return 'ATM';
                    }
                }

                function displayState(value, row) {
                    if(value == '0'){
                        return '退款中';
                    }else if(value == '1'){
                        return '完成退款';
                    }
                }

                function displayOrderState(value, row) {
                    var state = "未定義(回報系統商)";
                    var item = {};
                    for(var index in $scope.orderStatus.list) {
                        item = $scope.orderStatus.list[index];
                        if(item.state == value) {
                            state = item.text;
                        }
                    }
                    return state;
                }

                function deleteTime(value) {
                    if (value) {
                        var newValue = value.substr(0,10);
                        return newValue;
                    }else{
                        return value;
                    }
                }

                function viewDetail(row, attribute) {
                    var search  = {
                        serial: row.order_serial,
                    };

                    var apiString = "";
                    if($scope.specApi) {
                        apiString = " api=\"'" + $scope.specApi + "'\"";
                    }

                    document.location.href="#!/reimburse/detail/" + row.order_serial;
                }

                //table
                $timeout(function(){

                    //main table for admin to using.
                    $scope.table.configField([
                        {attribute: "reimburse_serial", name: "退款序號"},
                        {attribute: "create_datetime", name: "退款建檔日" , filter: deleteTime},
                        {attribute: "order_state", name: "訂單狀態"  , filter: displayOrderState},
                        {attribute: "order_serial", name: "訂單編號"},
                        {attribute: "buy_name", name: "訂購人"},
                        {attribute: "payment_type", name: "付款別" ,filter:displayPayType},
                        {attribute: "order_datetime", name: "訂購日" , filter:deleteTime},
                        {attribute: "pay_datetime", name: "付款日" , filter:deleteTime},
                        {attribute: "remark", name: "原因"},
                        //{attribute: "reimburse_name", name: "退款戶名"},
                        {attribute: "reimburse_money", name: "退款金額"},
                        {attribute: "state", name: "狀態" , filter:displayState},
                        {attribute: "reimburse_datetime", name: "退款日" , filter:deleteTime},
                        {attribute: "control", name: "控制",controls: [
                            {type: "button", icon: "fa-pencil-square", click: changeState}]
                        },
                        {attribute: "control", name: "詳細",controls: [
                            {type: "button", icon: "fa-search", click: viewDetail }]
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
            })
        );
    });