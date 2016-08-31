/*global define*/
'use strict';

define(['angular', 'app', 'configs'], function (angular, app, configs) {

	app.directive("commonHeader", function () {
		
		return {
			restrict: "EA",
			replace: true,
			transclude: true,
			templateUrl: app.applicationPath + "/directives/Header/view.html",
			controller:  'HeaderController'
		};
	});

	app.controller("HeaderController", function ($scope, $location, $cookiesHelper, $http, $cart, $timeout) {

        var MenuControl =  function(list1 , list2 ,id ) {
            var get = this;
            var items1;
            var itenm2;
            get.items1 = list1;
            get.items2 = list2;
            get.id = id;

            this.redirectByItem = function() {
                var url =$scope.listUri + "/" + get.id + "/" + $scope.selectedMainTag;
                url = $scope.getCurrentUrl($scope.getCurrentUrl(url, get.items1), get.items2);
                $location.path(url);
            }
        }

        $scope.$watch("search", function(keyword) {
            if(keyword){
                var url = "/productlist/" + keyword;
                $location.path(url);
            }
        });

        $('.dropdown-menu-keep').click(function() {
            return false;
        });

        $scope.listUri = "/productlist";

        /**
         * Register scope's variable to cart service instance.
         */
        $cart.register($scope, "cart");

        /**
         * Check the item was active.
         * @param viewLocation
         * @returns {boolean}
         */
        $scope.isActive = function (viewLocation) {
            return viewLocation === $location.path();
        };

        //get  sub_one_category_tag
        $scope.tagImagePath = configs.path.tagImage + "/";
        var homeTeg1Api = configs.api.tag + "/1";
        var homeTeg1Request = {
            method: 'GET',
            url: homeTeg1Api,
            headers: configs.api.headers
        };

        $http(homeTeg1Request).success(function(data, status, headers, config) {
            $scope.homeTeg1 = data.main.records;
            $scope.homeTeg1[0]['url'] ="/#!/productlist/"+$scope.homeTeg1[0].chinese_name;
            $scope.oneTegSub1Lists =  data.subOne.records;
            $scope.oneTegSub2Lists =  data.subTwo.records;
            $scope.menu1  = {instance : function() {
                return  new MenuControl($scope.oneTegSub1Lists,$scope.oneTegSub2Lists,$scope.homeTeg1[0].id);
                }
            }
        }).error(function(data, status, headers, config){
            $scope.alert.show("無法取列表");
        });

        //get  sub_two_category_tag
        var homeTeg2Api = configs.api.tag + "/2";
        var homeTeg2Request = {
            method: 'GET',
            url: homeTeg2Api,
            headers: configs.api.headers
        };

        $http(homeTeg2Request).success(function(data, status, headers, config) {
            $scope.homeTeg2 = data.main.records;
            $scope.homeTeg2[0]['url'] ="/#!/productlist/"+$scope.homeTeg2[0].chinese_name;
            $scope.twoTegSub1Lists =  data.subOne.records;
            $scope.twoTegSub2Lists =  data.subTwo.records;
            $scope.menu2  = {instance : function() {
                return  new MenuControl($scope.twoTegSub1Lists,$scope.twoTegSub2Lists,$scope.homeTeg2[0].id);
                }
            }
        }).error(function(data, status, headers, config){
            $scope.alert.show("無法取列表");
        });

        //get  sub_three_category_tag
        $scope.tagImagePath = configs.path.tagImage + "/";
        var homeTeg3Api = configs.api.tag + "/3";
        var homeTeg3Request = {
            method: 'GET',
            url: homeTeg3Api,
            headers: configs.api.headers
        };

        $http(homeTeg3Request).success(function(data, status, headers, config) {
            $scope.homeTeg3 = data.main.records;
            $scope.homeTeg3[0]['url'] ="/#!/productlist/"+$scope.homeTeg3[0].chinese_name;
            $scope.threeTegSub1Lists =  data.subOne.records;
            $scope.threeTegSub2Lists =  data.subTwo.records;
            $scope.menu3  = {instance : function() {
                return  new MenuControl($scope.threeTegSub1Lists,$scope.threeTegSub2Lists,$scope.homeTeg3[0].id);
                }
            }
        }).error(function(data, status, headers, config){
            $scope.alert.show("無法取列表");
        });

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

        /**
         * Handing user click main tag event.
         * @param name
         */
        $scope.onMainTagClick = function(name,id) {
            $scope.selectedMainTag = name;
            var url = $scope.listUri + "/" + id  + "/" + name;
            cleanItem($scope.oneTegSub1Lists);
            cleanItem($scope.oneTegSub2Lists);
            cleanItem($scope.twoTegSub1Lists);
            cleanItem($scope.twoTegSub2Lists);

            $location.path(url);
        };

        /**
         * Handing user click sub tag event.
         * @param items
         * @param selectItem
         */
        $scope.onTagClick = function(items,selectItem,menujson) {
            $("#navbar").removeClass("in");  
            $("#navbar").attr("aria-expanded",false);
            cleanItem(items);
            selectItem.select = true;
            var menu = menujson.instance();
            menu.redirectByItem();
        };

        function cleanItem(items) {
            var item;
            for(var index in items){
                item = items[index];
                item.select = false;
            }
        }

        $cookiesHelper.register($scope, "oauth", "oauth", true, {
            onReady: function() {
                $scope.oauth = {result: "none"};
                getUserInfo();
            }
        });

        /**
         * Get user info by rest api.
         */
        function getUserInfo() {
            var url = configs.api.oauth + "/user/info";
            var req = {
                method: 'GET',
                url: url,
                headers: configs.api.headers
            };
            $http(req).success(function(data) {
                $scope.oauth = {
                    result: "success",
                    name: data.info.name,
                    email: data.info.email,
                    type: data.type,
                    id: data.info.id,
                    hash: data.info.hash,
                    salt: data.info.salt
                };
            }).error(function() {
                $scope.oauth = {
                    result: "none",
                    name: null,
                    email: null,
                    type: null,
                    id: null,
                    hash: null,
                    salt: null
                };
            });
        }

        /**
         * Logout user by rest api.
         */
        function logout() {
            var url = configs.api.oauth + "/logout";
            var req = {
                method: 'GET',
                url: url,
                headers: configs.api.headers
            };
            $http(req).success(function(data) {
                $scope.oauth = {
                    result: "none",
                    name: null,
                    type: null,
                    id: null
                };
                $location.path("#!/#home");
            }).error(function() {
                // Do nothings
            });
        };

        /**
         * Showing login modal view.
         */
        // $scope.showLogin = function() {
        //     var html = '<div class="embed-responsive embed-responsive-16by9"><iframe sandbox="allow-same-origin allow-scripts allow-popups allow-forms" class="embed-responsive-item" src="/userlogin.html#!/login"></iframe></div>';
        //     var callback = function() {};
        //     var title = "註冊/登入";
        //     var buttonText = "關閉視窗";
        //     var width = 900;

        //     $scope.loginView.showHtml(html, callback, title, buttonText, width);
        // };

        /**
         * Showing logout modal view.
         */
        $scope.showLogout = function() {
            var msg = "確定要登出嗎?";
            var title = "正在進行登出";
            var logoutBtnText = "確定";
            var cancelBtnText = "";
            var callback = function(){};
            $scope.logoutView.confirm(msg, logout, title, logoutBtnText, callback, cancelBtnText);
        };

        /**
         * Trace oauth variable.
         */
        $scope.$watch("oauth", function(instance) {
            if(instance.result == "success") {
                $scope.loginViewShow.hide();
            }
        })


        /**
         * Showing login modal view.
         */
        $scope.showLogin = function() {
            $scope.loginViewShow.show();
        };

        $("a#back").click(function(){
            $('#collapse').addClass("collapsed");  
            $('#collapse').attr("aria-expanded",false);  
            $("#navbar").removeClass("in");  
            $("#navbar").attr("aria-expanded",false);
        });

        $('#nav-collapsed').click(function(){
            $("#navbarTwo").removeClass("in");  
            $("#navbarTwo").attr("aria-expanded",false);
        });
        $('#search-icon').click(function(){
            $("#navbar").removeClass("in");  
            $("#navbar").attr("aria-expanded",false);
        });

    });
	
});