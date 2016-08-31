/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/WholesaleProductGroupSelect/view.html'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("wholesaleProductGroupSelect", function () {
        return {
            restrict: "E",
            template: view,
            controller:  function($scope, $http, $timeout) {

                $scope.tableLabel   = "您選擇的群组";
                $scope.listLabel    = "您的群組列表";
                
                $timeout(
                        function(){
                            $scope.records = [];
                            $scope.pageNo = 1;
                            $scope.pageSize = 100;

                            $scope.table.configField(
                                [       
                                    {
                                        attribute:"id", 
                                        name:"ID"               
                                    },
                                    {
                                        attribute:"name", 
                                        name:"名稱"
                                    },
                                    {               
                                    attribute:"control", 
                                    name: "控制",
                                    controls: [
                                        {
                                            type: "button",
                                            icon: "fa-trash-o",
                                            click: function(row, attribute) {
                                                var index = $scope.records.indexOf(row);
                                                $scope.records.splice(index, 1);
                                                TableDirectiveLoad();
                                            }
                                        },
                                        
                                    ]
                                },
                                ]
                            );

                            function TableDirectiveLoad()
                            {
                                var records = $scope.records;
                                var pageNo  = $scope.pageNo;
                                var pageSize    = $scope.pageSize;
                                var count  = records.length;
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
                                    if( records[index].id == options.id ){
                                        isSelected = true;
                                    }
                                }
                                return isSelected;
                            }

                            function AddItemInTable( row )
                            {
                                if( !checkIsSelected( row ) )
                                {
                                    var record = {
                                        name:row.name,
                                        id:row.id,
                                        parent_group_id:row.parent_group_id,
                                        type:row.type
                                    };
                                    $scope.records.push(record);
                                    TableDirectiveLoad();
                                }
                            };

                            $scope.list.onSelectedEvent(
                                function( row, field ){
                                    AddItemInTable( row );
                                }
                            );

                    }
                ,500);
                
                /*
                *
                *
                */
                $scope.instance = {
                    getField:function()
                    {
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