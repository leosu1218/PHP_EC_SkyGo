/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/FareListSelect/view.html'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("fareListSelect", function () {
        return {
            restrict: "E",
            template: view,
            controller:  function($scope, $http, $timeout) {

                $scope.tableLabel   = "您選擇的物流配送方案";
                $scope.listLabel    = "您擁有的物流配送方案";
                $scope.records = [];
                $scope.pageNo = 1;
                $scope.pageSize = 100;
                $scope.payTypes = {
                    list:[
                        {text:"信用卡線上刷卡", type:"neweb"},
                        {text:"超商繳款", type:"MMK"},
                        {text:"虛擬帳號轉帳", type:"ATM"},
                    ]
                };
                
                $timeout(
                        function(){

                            $scope.table.configField(
                                [       
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
                                    {               
                                        attribute:"control", 
                                        name: "控制",
                                        controls: [
                                            {
                                                type: "button",
                                                icon: "fa-trash-o",
                                                click: function(row, attribute) {
                                                    if( row.global==0 ){
                                                        var index = $scope.records.indexOf(row);
                                                        $scope.records.splice(index, 1);
                                                        TableDirectiveLoad();
                                                    }else{
                                                        $scope.alert.show("此方案為全館套用不能刪除。");
                                                    }
                                                }
                                            },
                                            
                                        ]
                                    },
                                ]
                            );

                            function TableDirectiveLoad()
                            {
                                var records     = $scope.records;
                                var pageNo      = $scope.pageNo;
                                var pageSize    = $scope.pageSize;
                                var count       = records.length;
                                $scope.table.load({
                                    records: records,
                                    recordCount: (count||0),
                                    totalPage: Math.ceil((count||0)/pageSize),
                                    pageNo: pageNo,
                                    pageSize: pageSize,
                                });
                            };

                            function checkIsSelected( options )
                            {   
                                var isSelected = false;
                                var records = $scope.records;
                                for(var index in records){
                                    // if( records[index].id == options.id ){
                                    //     isSelected = true;
                                    // }
                                    if( records[index].pay_type == options.pay_type ){
                                        isSelected = true;
                                    }
                                }
                                return isSelected;
                            }

                            function AddItemInTable( row )
                            {
                                if( !checkIsSelected( row ) )
                                {
                                    $scope.records.push(row);
                                    TableDirectiveLoad();
                                }
                                TableDirectiveLoad();
                            };

                            $scope.list.onSelectedEvent(
                                function( row, field ){
                                    AddItemInTable( row );
                                }
                            );


                            function GetList(){
                                var listData = $scope.list.getField();
                                for(var index in listData){
                                    if( listData[index]['global']==1 ){
                                        AddItemInTable(listData[index]);
                                    }
                                }
                            }

                            function StartGetList(){
                                var list = ($scope.list.getField()||[]);
                                if( list.length!=0 ){
                                    GetList();
                                }else{
                                    $timeout(function(){
                                        StartGetList();
                                    },200);
                                }
                            }

                            $scope.$watch("records", function(records) {
                                if(records) {
                                    TableDirectiveLoad();
                                    $scope.getFareTable();
                                }
                            });

                            StartGetList();
                            
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
                    }
                ,500);

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
                    getField:function()
                    {
                        // var selected = [];
                        // var listData = $scope.table.getField();
                        // for( var index in listData ){
                        //     if( listData[index]["global"]!=1 ){
                        //         selected.push( listData[index] );
                        //     }
                        // }
                        // return selected;
                        return $scope.table.getField();
                    },
                    setRecords:function( records )
                    {
                        $scope.records = records;
                    }
                };

            },
            scope: {
                instance: '=?instance'
            }
        };
    });
});