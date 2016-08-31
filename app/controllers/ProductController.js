/*global define*/
'use strict';

define(['angular', 'app', 'configs','jquery', 'slick'], function (angular, app, configs, $, slick) {

	return app.controller("ProductController", function ($scope, $routeParams, $http, $cart, $cookiesHelper) {

        $scope.apiUrl = configs.api.tag + "/" + $routeParams.tagid;
        $scope.mainTag = $routeParams.tag1;
        $scope.product = $scope.product || {
            sepc: {
                records: []
            }
        };
        $scope.product.youtubeUrl = "http://no-video";
        $scope.showImageSlide = true;
        $scope.showVideo = false;
        $scope.showBay = false;

        $scope.filterInventory = function(item,$index){
            if(item.can_sale_inventory > 0){
                $scope.showBay = true;
            }
            if(item.can_sale_inventory == 0)
            {
                $scope.product.spec.records.splice($index,1);
            }
            return item.can_sale_inventory > 0;
        };

        /**
         * Create a inner slide item by a youtube url.
         * @param url
         * @returns {*|jQuery|HTMLElement}
         */
        function createInnerYoutube(url) {
            var html = "";
            html += "<div class=\"embed-responsive embed-responsive-16by9\">";
            html += "  <iframe class=\"embed-responsive-item\" src=\"" + url + "?rel=0&amp;controls=0&amp;showinfo=0&autoplay=1&loop=1\" frameborder=\"0\" allowfullscreen><\/iframe>";
            html += "<\/div>";
            return $(html);
        }

        /**
         * Get activities product info by id.
         * @param id
         */
        $scope.get = function(id) {
            var request = {
                method: 'GET',
                url: configs.api.generalActivity + "/" + id + "/buyinfo",
                headers: configs.api.headers
            };

            $http(request).success(function(data, status, headers, config) {
                $scope.product = data;
                var numIndex = 0;
                for (var key in $scope.product.spec.records) {
                    if ($scope.product.spec.records[key].can_sale_inventory == 0) {
                        numIndex+=1;
                    }else{
                        break;
                    }
                }

                $scope.selectSpec = "0";
                $scope.specAmount = "1";
                if($scope.product.mediaType == 1) {
                    $(".youtube-video").append(createInnerYoutube($scope.product.youtubeUrl));

                    $scope.showImageSlide = false;
                    $scope.showVideo = true;
                }

                if($scope.product.mediaType == 0) {
                    $scope.product.youtubeUrl = "http://no-video";
                    $scope.showImageSlide = true;
                    $scope.showVideo = false;
                    $scope.initSlider(data.materials.records);
                    if(data.relationProducts.recordCount !=0){
                        getRelationSpec(data);
                    }
                }
            }).error(function(data, status, headers, config){
                $scope.alert.show("無法取得資料");
            });

            function getRelationSpec(data) {
                var specRequest = {
                    method: 'GET',
                    url: configs.api.productSpec + "/" + data.relationProducts.records[0].relation_product_id + "/1/10",
                    headers: configs.api.headers
                };

                $http(specRequest).success(function (data, status, headers, config) {
                    $scope.relationSpes = data.records;
                }).error(function (data, status, headers, config) {
                    $scope.alert.show("無法取得資料");
                });

            }

        };


        /**
         * initial slider ui component.
         * @param items
         */
        $scope.initSlider = function(items) {
            var item = null
            for(var index in items) {
                item = items[index];
                $('.slider-for').append('<div class="col-md-2 product-slider"><img src="upload/image/' + item.url + '"></div>');
                $('.slider-nav').append('<div class="col-md-2 product-slider-sm"><img src="upload/image/' + item.url + '"></div>');
            }

            $('.slider-for').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                speed: 500,
                arrows: false,
                fade: true,
                asNavFor: '.slider-nav'
            });

            $('.slider-nav').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                speed: 500,
                asNavFor: '.slider-for',
                dots: false,
                centerMode: true,
                focusOnSelect: true,
                arrows: false,
                slide: 'div',
            });
        };



        $scope.$watch("selectSpec", function() {
            if ($scope.selectSpec) {
                var item;
                item = $scope.product.spec.records[$scope.selectSpec];
                $scope.selectedSpec = {
                    text: item.name,
                    item: item,
                    can_sale_inventory: item.can_sale_inventory
                }    
            }
            
        });

        /**
         * Handle of user select spec event.
         * @param item
         */
        //$scope.onSelectSpec = function(item) {
        //    $scope.selectedSpec = {
        //        text: item.name,
        //        item: item,
        //        can_sale_inventory: item.can_sale_inventory
        //    }
        //};

        /**
         * Get a int array for select element option.
         * @param number
         * @returns {Array}
         */
        $scope.optionCount = function(can_sale_inventory) {
            var number;
            var array = [];

            if(can_sale_inventory>20){
                number = 20
            }else{
                number = can_sale_inventory;
            }

            for(var i = 0; i < number; i++) {
                array.push( (i + 1).toString() );
            }
            return array;
        };

        /**
         * Handle of user add product spec to cart.
         */
        $scope.addToCart = function(amount, item) {
            var newItem = angular.extend({amount: amount, activity_id: $routeParams.id}, item);
            $cart.add(newItem, function(newItem, e) {
                $scope.alert.show(e);
            });
        };

        $scope.changeToCart = function(amount, item) {
            var newItem = angular.extend({amount: amount, activity_id: $routeParams.id}, item);
            $cart.add(newItem, function(newItem, e) {
                // $scope.alert.show(e);
            });

            setTimeout(function(){
                location.href = '#!/shoppingcart'
            },200);
        };

        /**
         * Handle of user add recommended product to cart.
         * @param item
         */
        $scope.addRecommendToCart = function(item) {
            var newItem = {amount: 1, activity_id: 0 , id:$scope.productSpe , product_id:item[0].product_id};
            $cart.add(newItem, function(newItem, e) {
                $scope.alert.show(e);
            });
        };

        $cart.register($scope, "cart");
        $scope.get($routeParams.id);
        $scope.selectedSpec = {text: "選擇", item: null};
        $scope.productSpe = "";

	});
});