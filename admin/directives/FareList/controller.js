/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/FareList/view.html'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("fareList", function () {
        return {
            restrict: "E",
            template: view,
            controller:  function($scope, $http, $timeout) {
                $scope.pageNo = 1;
                $scope.pageSize = 10;
                $scope.payTypes = {
                    list:[
                        {text:"信用卡線上刷卡", type:"neweb"},
                        {text:"超商繳款", type:"MMK"},
                        {text:"虛擬帳號轉帳", type:"ATM"},
                    ]
                };
                $scope.onSelectedEvent = function(){};

                $timeout(function(){
                    $scope.getFareTable();
                    $scope.params = {keyword:""};
                    $scope.fetchList();
                    $scope.table.rowClickCss({'background-color':'antiquewhite'});
                    $scope.table.onRowClick(
                        function(row, field, instance)
                        {
                            $scope.table.selectedCancelAllField();
                            instance.selected();
                            $scope.onSelectedEvent( row, field );
                        }
                    );
                }, 10);

                $scope.fetchList = function(){
                    $scope.table.configField([
                        // {attribute:"id",             name:"ID"},
                        {attribute:"program_name",   name:"方案名稱"},
                        {attribute:"pay_type",       name:"付款方式", filter:changePayType},
                        {attribute:"delivery_type",  name:"配送方式", filter:changeDeliveryType},
                        // {
                        //     attribute:"global",        
                        //     name:"是否開啟自動套用",
                        //     filter:function(value){
                        //         if( value == "1" ){
                        //             return "使用";
                        //         }else if( value == "0" ){
                        //             return "不使用";
                        //         }else{
                        //             return "未定義";
                        //         }
                        //     }
                        // },
                    ]);
                    
                    var  url = configs.api.systemConfig + "/search/delivery";
                    $scope.table.loadByUrl(url, 1, 10,
                        function(data, status, headers, config) {
                            // $scope.deliveryTable = data.records;
                        },
                        function(data, status, headers, config) {
                            //TODO show error message
                        },
                        $scope.params
                    );

                    $scope.table.rowClickCss({'background-color':'antiquewhite'});
                    $scope.table.onRowClick(
                        function(row, field, instance)
                        {
                            $scope.table.selectedCancelAllField();
                            instance.selected();
                            $scope.onSelectedEvent( row, field );
                            console.log(field);
                        }
                    );

                    function changePayType(value){
                        var item = {};
                        for(var key in $scope.payTypes.list) {
                            item = $scope.payTypes.list[key];
                            if(item.type == value) {
                                value = item.text;
                            }
                        }
                        return value;
                    } 
                    function changeDeliveryType(value){
                        var item = {};
                        for(var key in $scope.fareItem) {
                            item = $scope.fareItem[key];
                            if(item.id == value) {
                                value = item.type;
                            }
                        }
                        return value;
                    }
                };

                $scope.onSearchKeyword = function() {
                    $scope.params = {
                        keyword:$scope.keyword
                    };
                    $scope.fetchList();
                }

                $scope.getFareTable = function() {
                    var api = configs.api.systemConfig +"/fare/list/" + $scope.pageNo +"/"+ $scope.pageSize;
                    var request = {
                        method: 'GET',
                        url: api,
                        headers: configs.api.headers
                    };
                    $http(request).success(function(data, status, headers, config) {
                        $scope.fareItem = data.records;
                    }).error(function(data, status, headers, config){
                    });
                };

                /*
                *
                *
                */
                $scope.instance = {
                    onSelectedEvent:function( handler ){
                        $scope.onSelectedEvent = handler;
                    },
                    getField:function(){
                        return $scope.table.getField();

                    }
                };

            },
            scope: {
                instance: '=?instance'
            }
        };
    });
});