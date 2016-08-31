/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/AdminOrderList/view.html'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("adminOrderList", function () {
        return {
            restrict: "E",
            template: view,
            controller: function ($scope, $http, $timeout) {
                $scope.deliveryStateText = 'delivering';


                /**
                 * On delivery field clicked.
                 * @param row
                 * @param field
                 * @param instance
                 */
                function deliveryClick(row, field, instance) {
                    $scope.modal.config({
                        controls:[
                            {position:"header", type:"text",label:"更新"},
                            {
                                position        :"body",
                                type            :"input",
                                label           :"物流商",
                                attribute       :row.delivery_channel,
                                attributeName   :"delivery_channel"
                            },
                            {
                                position        :"body",
                                type            :"input",
                                label           :"物流編號",
                                attribute       :row.delivery_number,
                                attributeName   :"delivery_number"
                            },
                            {
                                position:"footer",
                                type:"button",
                                label:"確定",
                                target:function( data ){
                                    data[ "order_id" ] = row.id;
                                    updateDelivery( data );
                                }
                            }
                        ]
                    });

                    $scope.modal.show();
                }

                /**
                 * Update delivery record by rest api.
                 * @param data
                 */
                function updateDelivery( data ) {

                    data.stateText = $scope.deliveryStateText;

                    var api = configs.api.order + "/" + data.order_id;
                    var request = {
                        method: 'PUT',
                        url: api,
                        headers: configs.api.headers,
                        data: data
                    };

                    $http(request).success(function(data, status, headers, config) {
                        $scope.table.reloadList();
                    }).error(function(data, status, headers, config){
                        $scope.alert.show("回壓物流有誤，請再次嘗試。");
                    });
                }

                /**
                 * Setting customer events for order list and returned list directive.
                 */
                $scope.$watch("table", function(instance) {
                    if(instance) {
                        $scope.table.onRowClick(function(row, field, instance) {
                            // if(field == 'delivery_datetime'||field == 'delivery_channel'||field == 'delivery_number') {
                            //     deliveryClick(row, field, instance);
                            // }
                            if(field == 'remark') {
                                    remarkClick(row, field, instance);
                                }
                            if(field != 'control') {
                                instance.selected();
                            }
                        });
                    }
                });

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

                /**
                 * Get id's list from a json data array.
                 * @param data
                 * @returns {{ids: Array}}
                 * @constructor
                 */
                function GetIds( data )  {
                    var fromData = {ids:[], entity_type:"gernal"};
                    for(var index in data) {
                        fromData.ids.push(data[index].id);
                    }
                    return fromData;
                }

                function GetOpenIds( data )  {
                    var fromData = {ids:[], entity_type:"open"};
                    for(var index in data) {
                        fromData.ids.push(data[index].id);
                    }
                    return fromData;
                }

                /**
                 * Download exported excel file.
                 * @type {{pickup: Function}}
                 */
                $scope.download = {
                    pickup:function(){
                        var data = $scope.table.getSelectedField();
                        if(data.length>0) {
                            $scope.downloadByUrl( 
                                configs.api.exportFile+"wholesale/pickup", 
                                GetIds(data), 
                                function(result){
                                    if(result.isSuccess) {
                                        location.href = configs.path.report + 'pickup/' + result.fileName;
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
                    },
                    excel:function(){
                        var data = $scope.table.getSelectedField();
                        if(data.length>0) {
                            $scope.downloadByUrl(
                                configs.api.exportFile+"wholesale/pickup",
                                GetOpenIds(data),
                                function(result){
                                    if(result.isSuccess) {
                                        location.href = configs.path.report + 'pickup/' + result.fileName;
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
                 * Change selected item to change state.
                 */
                $scope.changeState = function() {
                    var selected = $scope.table.getSelectedField();
                    var message = "";


                    if(checkEachState("paid", selected)) {
                        message += "是否將所選擇的訂單【等待出貨(付款成功)】，";
                        message += "變更狀態為【申請取消訂單】?";
                        $scope.alert.show(message, function() {
                            updateStateBySelected("applycancel", selected);
                        });
                    }
                    else if(checkEachState("applycancel", selected)) {
                        message += "是否變更所選擇的訂單【申請取消訂單】?";
                        $scope.alert.confirm(message,
                            function() {
                                updateStateBySelected("paid", selected);
                            }, "訊息", "恢復訂單, 返回【等待出貨(付款成功)】",
                            function() {
                                updateStateBySelected("cancel", selected);
                            }, "完成取消, 變更為【訂單已取消】");
                    }
                    else if(checkEachState("prepared", selected)) {
                        message += "是否將所選擇的訂單【尚未付款】，";
                        message += "變更狀態為【申請取消訂單】?";
                        $scope.alert.show(message, function() {
                            updateStateBySelected("applycancel", selected);
                        });
                    }
                    else {
                        message += "您所選擇的訂單必須全部為同一個狀態，且必須是";
                        message += "【等待出貨(付款成功)】,";
                        message += "【申請取消訂單】,";
                        message += "【鑑賞期(已到貨未滿7日)】,";
                        message += "其中一種";
                        $scope.alert.show(message);
                    }
                };

                /**
                 * Update to server by api.
                 *
                 * @param states json State object {text:<display text>, state:<server stateText>}
                 * @param selected array Selected item from sb-table.
                 */
                function updateStateBySelected(statesText, selected) {
                    var index, item, ids, url;

                    ids = [];
                    for(index in selected) {
                        item = selected[index];
                        ids.push(item.id);
                    }

                    url = configs.api.order + "/list/state";
                    var request = {
                        method: 'PUT',
                        url: url,
                        data: {
                            ids:ids,
                            stateText: statesText
                        },
                        headers: {'Content-Type': 'application/json'}
                    };

                    $http(request).success(function(data, status, headers, config) {
                        var index;
                        for(index in selected) {
                            selected[index].stateText = statesText;
                        }
                    }).error(function(data, status, headers, config) {
                        var message = "更改狀態失敗，請重新整理頁面後再嘗試。";
                        $scope.alert.show(message);
                    });
                }

                /**
                 * Check all selected record's state equal the statesText.
                 *
                 * @param statesText
                 * @param selected
                 * @return bool Return true when all selected as same as state object.
                 */
                    function checkEachState(statesText, selected) {
                    var record;
                    var index;

                    if(selected.length == 0) {
                        return false;
                    }

                    for(index in selected) {
                        record = selected[index];
                        if(record.stateText != statesText) {
                            return false;
                        }
                    }

                    return true;
                }

                /**
                 * On remarkClick field clicked.
                 * @param row
                 * @param field
                 * @param instance
                 */
                function remarkClick(row, field, instance) {
                    $scope.remarkModal.config({
                        controls:[
                            {position:"header", type:"text",label:"更新"},
                            {
                                position        :"body",
                                type            :"input",
                                label           :"備註",
                                attribute       :row.remark,
                                attributeName   :"remark"
                            },
                            {
                                position:"footer",
                                type:"button",
                                label:"確定",
                                target:function( data ){
                                    data[ "id" ] = row.id;
                                    updateRemark( data );
                                }
                            }
                        ]
                    });
                    $scope.remarkModal.show();
                };

                /**
                 * Update remark by rest api.
                 * @param data
                 */
                function updateRemark( data ) {
                    var api = configs.api.remarkchange + "/" + data.id;
                    var request = {
                        method: 'PUT',
                        url: api,
                        headers: configs.api.headers,
                        data: data
                    };

                    $http(request).success(function(data, status, headers, config) {
                        $scope.table.reloadList();
                    }).error(function(data, status, headers, config){
                        $scope.alert.show("回壓物流有誤，請再次嘗試。");
                    });
                }

            },
            scope: {
                instance: '=?instance',
                fixedSearch: '=?search',
                api: '=?api'
            }
        }
    });
});