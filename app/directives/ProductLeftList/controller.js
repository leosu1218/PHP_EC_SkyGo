/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/ProductLeftList/view.html', 'configs'], function (angular, app, view, configs) {

	app.directive("productLeftList", function () {
		return {
			restrict: "E",			
			template: view,
			controller:  function($scope, $http, $routeParams, $location) {
                if(!$routeParams.tagid){
                    $routeParams.tagid = 1;
                }
                //get  sub_one_category_tag
                var homeTeg1Api = $scope.api;
                var homeTeg1Request = {
                    method: 'GET',
                    url: homeTeg1Api,
                    headers: configs.api.headers
                };

                $http(homeTeg1Request).success(function(data, status, headers, config) {
                    $scope.ImageName =  data.main.records[0].image_filename;
                    $scope.tegSub1Lists = data.subOne.records;
                    $scope.tegSub2Lists = data.subTwo.records;
                    selectSetItem($scope.tegSub1Lists,$routeParams);
                    selectSetItem($scope.tegSub2Lists,$routeParams);
                }).error(function(data, status, headers, config){
                    $scope.alert.show("無法取列表");
                });

                $scope.listUri = "/productlist";

                /**
                 * Handing user click sub tag event.
                 * @param items
                 * @param selectItem
                 */
                $scope.onTagClick = function(items,selectItem) {
                    cleanItem($scope.tegSub1Lists);
                    cleanItem($scope.tegSub2Lists);
                    selectItem.select = true;
                    var url;
                    if($routeParams.tag1){
                         url =$scope.listUri + "/" + $routeParams.tagid + "/" + $routeParams.tag1;
                    }else{
                         url =$scope.listUri + "/" + $routeParams.tagid;
                    }
                    url = $scope.getCurrentUrl($scope.getCurrentUrl(url, $scope.tegSub1Lists), $scope.tegSub2Lists);
                    $location.path(url);
                };

                $scope.selectCss = function(flag) {
                    if(flag){
                        return "selected";
                    } else{
                        return "";
                    }
                };

                $scope.getCurrentUrl = function(url, tagItem) {
                    url = url || "";
                    var index;
                    var item;
                    for(index in tagItem) {
                        item = tagItem[index];
                        if(item.select) {
                            url += "/" + item.name;
                        }
                    }
                    return url;
                };

                function selectSetItem(items,routeParams) {
                    var item;
                    for(var index in items){
                        item = items[index];
                        item.select = false;
                        if(item.name == routeParams.tag2 || item.name == routeParams.tag3){
                            item.select = true;
                        }
                    }
                }

                function cleanItem(items) {
                    var item;
                    for(var index in items){
                        item = items[index];
                        item.select = false;
                    }
                }

                $scope.instance = {
                    getImage:function(){
                        return $scope.ImageName;
                    }
                }

			},
			scope: {
                instance: "=?instance",
                api : "=?api"
			}
		};
	});

});