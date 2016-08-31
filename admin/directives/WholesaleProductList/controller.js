/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/WholesaleProductList/view.html'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("wholesaleProductList", function () {
        return {
            restrict: "E",
            template: view,
            controller: function ($scope, $http, $timeout) {

                $scope.pageSize = 10;
                $scope.search = {};
                $scope.search.keyword = null;
                $scope.search.order = "DESC";
                $scope.enableSelect = $scope.enableSelect || false;
                $scope.api = $scope.api || configs.api.product + "/wholesale/search/spec";

                /**
                 * Reload list
                 */
                $scope.reloadList = function() {
                    $scope.table.loadByUrl( $scope.api, 1, $scope.pageSize,
                        function(data, status, headers, config) {
                            // Handle reload table success;
                        },
                        function(data, status, headers, config) {
                            $scope.alert.show("無法搜尋到資料");
                        },
                        $scope.search
                    );
                };

                $scope.clear = function() {
                    $scope.search.keyword = null;
                    $scope.reloadList();
                };

                //table
                $timeout(function(){

                    //main table for admin to using.
                    $scope.table.configField([
                        {attribute: "id",               name: "ID"},
                        {attribute: "productName",      name: "品名"},
                        {attribute: "coverPhoto",       name: "主圖",     htmlFilter:displayCoverPhoto},
                        {attribute: "groupName",        name: "分類"},
                        {attribute: "suggestPrice",     name: "定價"},
                        {attribute: "minPrice",         name: "最低價"},
                        {attribute: "wholesalePrice",   name: "批發價"},
                        {attribute: "costPrice",        name: "成本價"},
                        {attribute: "proposePrice",     name: "建議售價"},
                        {attribute: "spec",          name: "庫存" ,    htmlFilter:checkSpec},
                        {attribute: "groupbuying",      name: "開放",     filter:displayGroupBuying},
                        {attribute: "masterName",        name: "修改者"},
                        // {attribute: "productReady",     name: "上架時間"},
                        // {attribute: "productRemoved",   name: "下架時間"},
                        {attribute: "control",          name: "控制",
                            controls: [
                                {type: "button", icon: "fa-search", click: viewDetail }
                            ]
                        }
                    ]);

                    $scope.reloadList();
                    $scope.table.rowClickCss({'background-color':'#FFDDAA'});
                    $scope.table.onRowClick(function(row, field, instance) {
                        if($scope.enableSelect) {
                            if(field != 'control') {
                                instance.selected();
                            }
                        }
                        if(field == 'remark') {
                            remarkClick(row, field, instance);
                        }
                    });
                    $scope.instance = $scope.table;
                }, 100);

                
                /**
                 * On remarkClick field clicked.
                 * @param row
                 * @param field
                 * @param instance
                 */
                function remarkClick(row, field, instance) {
                    $scope.modal.config({
                        controls:[
                            {position:"header", type:"text",label:"更新"},
                            {
                                position        :"body",
                                type            :"input",
                                label           :"備註內容",
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
                    $scope.modal.show();
                }

                /**
                 * Update remark by rest api.
                 * @param data
                 */
                function updateRemark( data ) {
                    var api = configs.api.userRemark + "/" + data.id;
                    var request = {
                        method: 'PUT',
                        url: api,
                        headers: configs.api.headers,
                        data: data,
                    };

                    $http(request).success(function(data, status, headers, config) {
                        $scope.reloadList();
                    });
                }


                /**
                 * Display cover photo field.
                 *
                 * @param value
                 * @param row
                 * @returns {string}
                 */
                function displayCoverPhoto(value, row) {
                    return '<img src="' + configs.path.material + 'image/' + value + '"  height="50" />';
                }

                /**
                 * view the item's details.
                 */
                function viewDetail(row, value) {
                    if(typeof($scope.detail) == 'function') {
                        $scope.detail(row, value);
                    }
                }

                /**
                 * Display active group buying.
                 *
                 * @param value
                 * @param row
                 * @returns {string}
                 */
                function displayGroupBuying(value, row) {
                    if(value == 0) {
                        return "不開放";
                    }
                    else {
                        return "開放";
                    }
                }

                /**
                 * Display media type.
                 *
                 * @param value
                 * @param row
                 * @returns {string}
                 */
                function displayMediaType(value, row) {
                    if(value == 0) {
                        return "輪播圖";
                    }
                    else if(value == 1) {
                        return "Youtube影片";
                    }
                    else {
                        return "未定義(請聯絡系統商)";
                    }
                }

                function checkSpec(value,row) {
                    var str = "";
                    var recordCount = value.recordCount;
                    if(row.spec.recordCount > 1){
                        if( recordCount >10){
                            recordCount = 10;
                        }
                        for (var i = 0; i < recordCount; i++) {
                            str +=  "<p>" +value.records[i].name + ":"+ "<span class=\"color-blue\">" + value.records[i].can_sale_inventory 
                            + "</span></p>"                      
                        } 
                    }else {
                        str ='<p>' + value.records[0].name + ':' + "<span class=\"color-blue\">" + value.records[0].can_sale_inventory 
                        + '</span></p>' 
                    }
                    return str ;
                }
            },
            scope: {
                instance: '=?instance',
                enableSelect: '=?enableSelect',
                api: '=?api',
                detail: '=?detail'
            }
        }
    });
});