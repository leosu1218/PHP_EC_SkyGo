/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'slick' , 'configs','datetime'], function (angular, app, $, slick , configs , datetime) {

	return app.controller("GroupBuyingController", function ($scope, $log, $q, $routeParams, $http) {
        $scope.pageNo =1;
        $scope.pageSize = 24;
        $scope.params = $routeParams;
        $scope.params.state = "started";
        $scope.imagePath = configs.path.productImage;
        loadFromServer();

        var homePageApi = configs.api.website + "banner/image/group/1/999";
        var homePageRequest = {
            method: 'GET',
            url: homePageApi,
            headers: configs.api.headers
        };

        $http(homePageRequest).success(function(data, status, headers, config) {
            $scope.groupImg = data.records[0];

        }).error(function(data, status, headers, config){
            $scope.alert.show("無法取得圖片，請再次嘗試。");
        });

        $scope.$watch("pagination", function(pagination) {
            if(pagination) {
                $scope.pagination.onPageClick(function(page) {
                    $scope.pageNo = page.number;
                    loadFromServer();
                })

                $scope.pagination.onPreviousClick(function(pageNo) {
                    $scope.pageNo--;
                    loadFromServer();
                })

                $scope.pagination.onNextClick(function(pageNo) {
                    $scope.pageNo++;
                    loadFromServer();
                })
            }
        })

        $scope.newSequence = function(string){
            $scope.params.order = string;
            loadFromServer();
        }


        function loadFromServer() {
            var url =  configs.api.groupbuyingActivity + '/search/client/' + $scope.pageNo + '/' + $scope.pageSize +'/';
            var request = {
                method: 'GET',
                url: url,
                headers: configs.api.headers,
                params: $scope.params
            };


            $http(request).success(function(data, status, headers, config) {
                $scope.groupBuyBetweenNow = getBetweenNow(data.records);
                $scope.groupBuyArray =getGroupBuyArray( $scope.groupBuyBetweenNow,3);
                $scope.groupBuyArrayXs =getGroupBuyArray( $scope.groupBuyBetweenNow,2);
                loadFromData(data);
            }).error(function(data, status, headers, config) {
                //$scope.alert.show("無法取得列表");
            });
        };

        function loadFromData(data) {
            $scope.pagination.load({
                recordCount: parseInt(data.recordCount, 10),
                totalPage: parseInt(data.totalPage, 10),
                pageSize: parseInt(data.pageSize, 10),
                pageNo: parseInt(data.pageNo, 10)
            });
        }

        function getGroupBuyArray(groupBuyList,columns) {
            var product =[];
            var x = 0;
            var y = 0;
            product[x] = [];
            for(var i=0;i<groupBuyList.length ; i++){
                product[x][y] = groupBuyList[i];
                y++;
                if(y == columns && i< groupBuyList.length-1){
                    x++;
                    y=0;
                    product[x]=[];
                }


            }
            return product;
        }
        function getBetweenNow(datalist) {
            var item;
            for(var index in datalist){
                item = datalist[index];
                item['days'] = datetime.betweenNow(item.end_date).days();
                item['hours'] = datetime.betweenNow(item.end_date).hours();
                item['minutes'] = datetime.betweenNow(item.end_date).minutes();
            }
            return datalist;
        }
            
	});	
});