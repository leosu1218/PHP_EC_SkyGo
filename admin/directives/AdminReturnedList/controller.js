/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/AdminReturnedList/view.html'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("adminReturnedList", function () {
        return {
            restrict: "E",
            template: view,
            controller: function ($scope, $http, $timeout) {
                $scope.deliveryStateText = 'receiving';

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
                                attribute       :row.ur_delivery_channel,
                                attributeName   :"ur_delivery_channel"
                            },
                            {
                                position        :"body",
                                type            :"input",
                                label           :"物流編號",
                                attribute       :row.ur_delivery_number,
                                attributeName   :"ur_delivery_number"
                            },
                            {
                                position:"footer",
                                type:"button",
                                label:"確定",
                                target:function( data ){
                                    data[ "ur_id" ] = row.ur_id;
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

                    var api = configs.api.returned + "/" + data.ur_id;
                    var request = {
                        method: 'PUT',
                        url: api,
                        headers: configs.api.headers,
                        data: data,
                    };

                    $http(request).success(function(data, status, headers, config) {
                        $scope.table.reloadList();
                    }).error(function(data, status, headers, config){
                        $scope.alert.show("回壓物流有誤，請再次嘗試。");
                    });
                }

                /**
                 *
                 */
                $scope.complete = function(serial) {
                    var handler = function() {};
                    var title = "完成退貨流程";
                    var buttonText = "關閉視窗";
                    var width = "900";
                    var html = "<complete-returned-form serial=\"'" + serial + "'\"></complete-returned-form>";
                    $scope.alert.showHtml(html, handler, title, buttonText, width);
                }

                /**
                 * Setting customer events for order list and returned list directive.
                 */
                $scope.$watch("table", function(instance) {
                    if(instance) {
                        $scope.table.onRowClick(function(row, field, instance) {
                            if(field == 'control') {
                                // Do nothings.
                            }
                            else if(field == 'stateText') {
                                if(row[field] == "receiving") {
                                    $scope.complete(row["serial"]);
                                }
                            }
                            else if(field == 'ur_delivery_channel'||field == 'ur_delivery_number') {
                                deliveryClick(row, field, instance);
                            }
                            else if(field == 'ur_remark') {
                                urRemarkClick(row, field, instance);
                            }
                            else {
                                instance.selected();
                            }
                        });
                    }
                });

                $scope.downloadByUrl = function( url, data, callback ){
                    var request = {
                        method: 'POST',
                        url: url,
                        data: data,
                        headers: {'Content-Type': 'application/json'},
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

                $scope.download = {
                    returned:function(){
                        var data = $scope.table.getSelectedField();
                        if(data.length>0) {
                            $scope.downloadByUrl( 
                                configs.api.exportFile+"wholesale/returned", 
                                GetIds(data), 
                                function(result){
                                    
                                    if(result.isSuccess) {
                                        location.href = configs.path.report + 'returned/' + result.fileName;
                                    }
                                    else {
                                        $scope.alert.show("下載錯誤！請確認訂單是否在 [ 退貨處理中 ] 的狀態。");
                                    }
                                }
                            );
                        }
                        else {
                            $scope.alert.show("請選取活動。");
                        }
                    }
                };

                $scope.cancelAll = function(){
                    $scope.table.selectedCancelAllField();
                };

                $scope.selectedAll = function(){
                    $scope.table.selectedAllField();
                };

                /**
                 * Change selected item to change state.
                 */
                $scope.changeState = function() {
                    var selected = $scope.table.getSelectedField();
                    var message = "";

                    if(checkEachState("prepared", selected)) {
                        message += "是否將所選擇的退貨單【退貨處理中】，";
                        message += "變更狀態為【已取消退貨】?";
                        $scope.alert.show(message, function() {
                            updateStateBySelected("cancel", selected);
                        });
                    }
                    else if(checkEachState("receiving", selected)) {
                        message += "是否將所選擇的退貨單【等待貨物回收】，";
                        message += "變更狀態為【已取消退貨】?";
                        $scope.alert.show(message, function() {
                            updateStateBySelected("cancel", selected);
                        });
                    }
                    else {
                        message += "您所選擇的退貨單必須全部都為【退貨處理中】或【等待貨物回收】狀態";
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
                        ids.push(item.ur_id);
                    }

                    url = configs.api.returned + "/list/state";
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
                 * On ur_remark field clicked.
                 * @param row
                 * @param field
                 * @param instance
                 */
                function urRemarkClick(row, field, instance) {
                    $scope.modal.config({
                        controls:[
                            {position:"header", type:"text",label:"更新"},
                            {
                                position        :"body",
                                type            :"input",
                                label           :"備註內容",
                                attribute       :row.ur_remark,
                                attributeName   :"ur_remark"
                            },
                            {
                                position:"footer",
                                type:"button",
                                label:"確定",
                                target:function( data ){
                                    data[ "ur_id" ] = row.ur_id;
                                    updateUrRemark( data );
                                }
                            }
                        ]
                    });

                    $scope.modal.show();
                }

                /**
                 * Update ur_remark record by rest api.
                 * @param data
                 */
                function updateUrRemark( data ) {

                    // data.stateText = $scope.deliveryStateText;

                    var api = configs.api.remark + "/" + data.ur_id;
                    var request = {
                        method: 'PUT',
                        url: api,
                        headers: configs.api.headers,
                        data: data,
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
                api: '=?api',
            }
        }
    });
});