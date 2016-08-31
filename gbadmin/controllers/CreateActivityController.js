/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs', 'message', 'datetime'], function (angular, app, createController, configs, message, datetime) {

	return app.controller("CreateActivityController", createController(function ($scope, $http, $timeout) {

        $scope.api = configs.api.groupbuyingUser + "/self/product/search";
        $scope.selecter = null;
        $scope.selectedProduct = null;
        $scope.betweenDays = 0;

        $timeout(function() {

            /**
             * Override selected method.
             */
            $scope.list.onRowClick(function(row, field, instance) {
                if($scope.selecter) {
                    $scope.selecter.selected();
                }
                $scope.selecter = instance;
                $scope.selectedProduct = row;
                instance.selected();
            });
        }, 150);

        $scope.updateBetweenDays = function() {
            try {
                var start = $scope.startDate.getTime() +  $scope.startTime.getTime();
                var end = $scope.endDate.getTime() + $scope.endTime.getTime();
                $scope.betweenDays = (end - start) / (1000 * 60 * 60 *24);
            }
            catch(e) {
            //
            }
        };

        /**
         * Support view's operation.
         *
         * @param string
         * @param bit
         * @returns {Number}
         */
        $scope.parseInt = function(string, bit) {
            return parseInt(string, bit);
        };

        /**
         * Handle create success.
         *
         * @param data
         */
		function createSuccess(data) {
            $scope.alert.show(message.CREATE_ACTIVITY_SUCCESS, function() {
                window.location = configs.path.gbActivityList;
            });
		}

        /**
         * Handle create error.
         *
         * @param status int Http status code from rest api.
         * @param data
         */
		function createError(status, data) {

			if(!(status)) {
                $scope.alert.show(message.UNDEFINE_ERROR);
			}
			else if(status == 500) {
                $scope.alert.show(message.SERVER_ERROR);
			}
			else if(status == -1) {
                $scope.alert.show(data.message);
			}
			else if(status == 400) {
				if(data.message) {
                    $scope.alert.show(data.message);
				}
				else {
                    $scope.alert.show(message.SERVER_ERROR);
				}
			}
			else if(status == 401) {
                $scope.alert.show(message.UNAUTHORIZED_ERROR);
			}
			else if(status == 403) {
                $scope.alert.show(message.PERMISSION_DENIED_ERROR);
			}
			else {
                $scope.alert.show(message.CREATE_PLATFORM_GRUOP_ERROR);
			}
		}

        /**
         * Validator for create form.
         *
         * @param data
         */
		function verifyForm(data) {
			if(!data.selectedProduct) {
				throw {message: "沒有選擇販售的產品"};
			}
            if(!$scope.startDate) {
                throw {message: "開始日期沒有填寫"};
            }
            if(!$scope.startTime) {
                throw {message: "開始時間沒有填寫"};
            }
            if(!$scope.endDate) {
                throw {message: "結束日期沒有填寫"};
            }
            if(!$scope.endTime) {
                throw {message: "結束時間沒有填寫"};
            }
            var startDatetime = dateTimeFormat ($scope.startDate, $scope.startTime);
            var endDatetime = dateTimeFormat ($scope.endDate, $scope.endTime);
            if( startDatetime == endDatetime ){
                throw {message: "開始時間與結束時間相等"};
            }
		}

        /**
         * Datetime object to string Y-m-d H:i:s.
         *
         * @param date
         * @param time
         * @returns {string}
         */
		function dateTimeFormat (date, time) {
			var year = "" + date.getFullYear();
		  	var month = "" + (date.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
		  	var day = "" + date.getDate(); if (day.length == 1) { day = "0" + day; }
		  	var hour = "" + time.getHours(); if (hour.length == 1) { hour = "0" + hour; }
		  	var minute = "" + time.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
		  	var second = "" + time.getSeconds(); if (second.length == 1) { second = "0" + second; }
		  	return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
		}

        /**
         * POST new activity to db by REST API
         * @returns {number}
         */
		$scope.create = function() {
			try {
				verifyForm($scope);
				var request = {
					method: 'POST',
					url: configs.api.groupbuyingUser + "/self/activity",
					headers: configs.api.headers,
					data: {
						"name": $scope.name,
						"productId": $scope.selectedProduct.id,
						"price": $scope.price,
						"startDate": dateTimeFormat($scope.startDate, $scope.startTime),
						"endDate": dateTimeFormat($scope.endDate, $scope.endTime),
					}
				};

				$http(request).success(function(data, status, headers, config){
					createSuccess(data);
				}).error(function(data, status, headers, config){
					createError(status, data);				});

				return 0;
			}
			catch(e) {
				var data = {message: "新增失敗, 原因 :" + e.message}
				createError(-1, data);
			}
		}

		$scope.cancel = function() {
			window.history.back();
		}

	}));	
});