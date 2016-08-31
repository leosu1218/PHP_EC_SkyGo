/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/SearchTable/view.html'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("searchTable", function () {
        return {
            restrict: "E",
            template: view,
            controller: function ($scope, $http, $timeout) {
                $scope.pageSize = 10;
                $scope.search = {};
                $scope.search.keyword = null;
                $scope.api = "api/consumeruser";
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
                console.log($scope.id);


                $scope.clear = function() {
                    $scope.search.keyword = null;
                    $scope.reloadList();
                };

                $scope.instance = {

                    /**
                     *	get smartTable instance
                     *
                     */
                    getTable: function() {
                        return $scope.table;
                    },

                    /**
                     *	get smartTable instance
                     *
                     */
                    getReloadList: function() {
                        return reloadList();
                    }

                }


            },
            scope: {
                instance: '=?instance',
                api: '=?api',
                detail: '=?detail'
            }
        }
    });
});