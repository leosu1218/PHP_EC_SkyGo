/*global define*/
'use strict';

define(['angular', 'app', 'configs'], function (angular, app,configs ) {

	return app.controller("ProductListController", function ($scope, $http, $routeParams) {
        $scope.pageNo =1;
        $scope.pageSize = 24;
        $scope.params = $routeParams;

        $scope.imagePath = configs.path.productImage;
        $scope.productPath =configs.path.productPath;
        $scope.mainTag = $routeParams.tag1;

        $scope.$watch("prductTag.getImage()", function(prductTag) {
            if (prductTag) {
                $scope.tegImage = $scope.prductTag.getImage()
            }
        });


        if($routeParams.tagid !=  null) {
            if($routeParams.tagid == 1){
                $scope.imglink = "http://www.109life.com/#!/productlist/1/%E6%97%A5%E9%9F%93%E7%BE%8E%E5%A6%9D"
            }else if ($routeParams.tagid == 2){
                $scope.imglink = "http://www.109life.com/#!/productlist/2/%E6%AD%90%E7%BE%8E%E7%BE%8E%E5%A6%9D"
            }else {
                $scope.imglink = "http://www.109life.com/#!/productlist/3/%E7%AB%A5%E8%A3%9D"
            }

            $scope.apiUrl = configs.api.tag + "/" + $routeParams.tagid;
            var item ;
            for(var index in $routeParams) {
                item = $routeParams[index];
                $scope.productPath += "/" + item;
            }
        }else{
            $scope.apiUrl = configs.api.tag + "/1";
            $scope.productPath += "/1"
        }

        loadFromServer();

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


        function loadFromServer() {
            var url =  configs.api.generalActivity + '/search/client/' + $scope.pageNo + '/' + $scope.pageSize +'/';
            $scope.params.state = "started";
            var request = {
                method: 'GET',
                url: url,
                headers: configs.api.headers,
                params: $scope.params
            };


            $http(request).success(function(data, status, headers, config) {
                $scope.productArray =getProductArray(data.records,3);
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

        function getProductArray(productlist,columns) {
            var product =[];
            var x = 0;
            var y = 0;
            product[x] = [];
            for(var i=0;i<productlist.length ; i++){
                product[x][y] = productlist[i];
                y++;
                if(y == columns && i< productlist.length-1){
                    x++;
                    y=0;
                    product[x]=[];
                }


            }
            return product;
        }

	});
	
});