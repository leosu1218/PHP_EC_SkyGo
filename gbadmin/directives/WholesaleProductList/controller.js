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
                $scope.api = $scope.api || configs.api.product + "/wholesale/search";

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
                        {attribute: "id", name: "ID"},
                        {attribute: "productName", name: "名稱"},
                        {attribute: "coverPhoto", name: "圖片", htmlFilter:displayCoverPhoto},
                        {attribute: "mediaType", name: "顯示媒體", filter:displayMediaType},
                        {attribute: "groupName", name: "分類"},
                        {attribute: "groupbuying", name: "開放", filter:displayGroupBuying},
                        {attribute: "proposePrice",name: "建議售價"},
                        {attribute: "minPrice",name: "最低價"},
                        {attribute: "wholesalePrice", name: "批發價"},
                        {attribute: "control", name: "控制",controls: [
                            {type: "button", icon: "fa-search", click: viewDetail }]
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
                    });
                    $scope.instance = $scope.table;

                }, 100);

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
                function viewDetail() {
                    if(typeof($scope.detail) == 'function') {
                        $scope.detail();
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