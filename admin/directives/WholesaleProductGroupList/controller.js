/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/WholesaleProductGroupList/view.html'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("wholesaleProductGroupList", function () {
        return {
            restrict: "E",
            template: view,
            controller:  function($scope, $http, $timeout) {

                $scope.onSelectedEvent = function(){};

                $timeout(function(){
                    $scope.fetchList();
                    $scope.params = {};
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
                        {attribute:"id", name:"ID"},
                        {attribute:"name", name:"名稱"}
                    ]);

                    var  url = configs.api.productGroup + "/search/wholesale/product";
                    $scope.table.loadByUrl(url, 1, 10,
                        function(data, status, headers, config) {
                            // console.log(data);
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
                        }
                    );
                };

                $scope.onSearchKeyword = function() {
                    $scope.params = {
                        keyword:$scope.keyword
                    };
                    $scope.fetchList();
                }

                /*
                *
                *
                */
                $scope.instance = {
                    onSelectedEvent:function( handler ){
                        $scope.onSelectedEvent = handler;
                    }
                };

            },
            scope: {
                instance: '=?instance'
            }
        };
    });
});