/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/OrderSpecList/view.html'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("orderSpecList", function () {
        return {
            restrict: "E",
            template: view,
            controller:  function($scope, $http, $timeout) {

                /**
                 * Initialize directive's param.
                 */
                $scope.instance     = $scope.table;
                $scope.api          = $scope.api || configs.api.order + "/spec/search";
                $scope.fixedSearch  = $scope.fixedSearch || {};
                $scope.params       = {keyword:''};

                /**
                 * Display fare type field.
                 * @param value
                 * @param row
                 * @returns {string}
                 */
                function displayFareType(value, row) {
                    return value;
                }

                /**
                 * Display discount type field.
                 * @param value
                 * @param row
                 * @returns {string}
                 */
                function displayDiscountType(value, row) {
                    if( value == "normal" ) {
                        return "一般";
                    }
                    else if( value == "special" ) {
                        return "特殊";
                    }
                    else {
                        return "未定義";
                    }
                }

                /**
                 * Render table.
                 */
                $scope.$watch("table", function(instance) {
                    if(instance) {
                        instance.configField([
                            {attribute:"spec_id", name:"ID"},
                            {attribute:"product_name", name:"產品名稱"},
                            {attribute:"spec_name", name:"規格名稱"},
                            {attribute:"spec_serial", name:"品號"},
                            {attribute:"spec_amount", name:"數量"},
                            {attribute:"spec_unit_price", name:"單價"},
                            {attribute:"spec_total_price", name:"小計"},
                            {attribute:"spec_fare", name:"運費"},
                            {attribute:"spec_fare_type", name:"運費類型", filter:displayFareType},
                            {attribute:"spec_discount", name:"折扣"},
                            {attribute:"spec_discount_type", name:"折扣類型", filter:displayDiscountType},
                        ]);
                        $scope.fetchList();
                    }
                });

                /**
                 * Fetch record by api.
                 */
                $scope.fetchList = function(){
                    var url = $scope.api;
                    $scope.table.loadByUrl(url, 1, 10,
                        function(data, status, headers, config) {
                            // console.log(data);
                        },
                        function(data, status, headers, config) {
                            //TODO show error message
                        },
                        angular.extend($scope.fixedSearch, $scope.params)
                    );
                };

                /**
                 * On search by keyword event.
                 */
                $scope.onSearchKeyword = function() {
                    $scope.params = {
                        keyword:$scope.keyword
                    };
                    $scope.fetchList();
                }
            },
            scope: {
                instance: '=?instance',
                api: '=?api',
                fixedSearch: '=?search'
            }
        };
    });
});