/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

	app.directive("commonFooter", function () {
		
		return {
			restrict: "EA",
			replace: true,
			transclude: true,
			templateUrl: app.applicationPath + "/views/Footer.html",
			controller:  'FooterController',
			scope: {								
				outerInstance: "=?instance",				
			}
		};
	});

	app.controller("FooterController", function ($scope, $location) {		

		$scope.amount = 0;
		$scope.price = 0;
        $scope.productPrice = $scope.amount * $scope.price;

        /**
         * On user select amount on spec.
         */
        $scope.onChangeAmount = function() {
            var item, index;
            $scope.amount = 0;

            for(index = 0; index < $scope.spec.records.length; index++) {
                item = $scope.spec.records[index];
                $scope.amount += parseInt(item.amount, 10);
            }

            $scope.productPrice = $scope.amount * $scope.price;
            $scope.resetFare();
        };

        /**
         * Reset fare when amount or fare type changed.
         */
        $scope.resetFare = function() {
            if($scope.productPrice >= parseInt($scope.seltedFare.target_amount, 10)) {
                $scope.fare = 0;
            }
            else {
                $scope.fare = parseInt($scope.seltedFare.amount, 10);
            }
        }

        /**
         * On user select new fare type.
         */
        $scope.onChangeFareType = function() {
            $scope.seltedFare = $scope.fares.records[$scope.fareTypeIndex];
            $scope.resetFare();
        };

        /**
         * On user click buy button.
         */
		$scope.onBuyClick = function() {
            if(verifyForm()) {
                console.log($scope);
                $scope.buyHandler({
                    name: $scope.name,
                    phone: $scope.phone,
                    address: $scope.address,
                    email: $scope.email,
                    spec: $scope.spec.records,
                    fareId: $scope.seltedFare.id
                })
            }
		};

        /**
         * Validator for create form.
         *
         * @param data
         */
        function verifyForm() {
            $scope.stock = false;
            for(var key in  $scope.spec.records){

                if($scope.spec.records[key].amount > 0 ){
                    console.log($scope.spec.records[key]);
                    $scope.stock = true;
                }
            }
            if(!$scope.stock) {
                alert("請最少選擇一樣產品");
                return false
            }
            if(!$scope.fareTypeIndex) {
                alert("請選擇運送方式");
                return false
            }
            return true;
        }

        /**
         * Open method for client object.
         * @type {{setFare: Function, setPrice: Function, buy: Function, setSpec: Function}}
         */
		$scope.outerInstance = {
            setFare: function(fares) {
                $scope.fares = fares;
                $scope.fareTypeIndex = 0;
                $scope.onChangeFareType();
            },

        	setPrice: function(price) {
        		$scope.price = price;
        	},

        	buy: function(handler) {
        		$scope.buyHandler = handler;
        	},

            setSpec: function(spec) {
                $scope.spec = spec;
            }
        };
	});
	
});