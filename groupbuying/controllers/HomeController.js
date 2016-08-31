/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

	return app.controller("HomeController", function ($scope, $timeout, $http, $routeParams,$cookiesHelper) {

        $cookiesHelper.register($scope, "serial", "serial", true);

        /**
         * Load slide menu
         * @param data
         */
		function loadInnerItem(data) {

			if(data.mediaType == 0) {
				// Slide image.
                var item;
				var records = data.materials.records;
				var inner = $('.carousel-inner');
				for(var key in records) {
					item = records[key];
					inner.append(createInnerItem("/upload/image/" + item.url, (key==0)));
				}
			}
			else {
				// Play youtube video.
                var inner = $('.carousel-inner');
                inner.append(createInnerYoutube(data.youtubeUrl));
			}		
		}

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
         * Create a inner item by a image
         * @param imageSrc
         * @param isActive
         * @returns {*|jQuery|HTMLElement}
         */
		function createInnerItem(imageSrc, isActive) {			
			var active;
			if(isActive) {
				active = 'active';
			}
			else {
				active = '';
			}

			var innerItemHtml="";
			innerItemHtml += "<div class=\"item " + active + "\">";
			innerItemHtml += "<img class=\"image\" src=\"" + imageSrc + "\" style=\"margin:0px auto; width:100%;\">";
			innerItemHtml += "<\/div>";	

			return $(innerItemHtml);
		}

        /**
         * POST data use form element
         * @param data json The form field want to submit.
         */
		function formPost(data) {
			
			var form = document.createElement("form");
			form.setAttribute('method',"post");			
			form.setAttribute('action', data.providerUrl);
			var value;
			var input;

            input = document.createElement('input');
            input.setAttribute('type', 'submit');
            input.setAttribute('value', 'send');
            input.setAttribute('style', 'width: 0px; height: 0px; display:none;');
            form.appendChild(input);

			for(var key in data) {

				input = document.createElement('input');
				input.setAttribute('type', 'hidden');
				input.setAttribute('name', key);
				input.setAttribute('value', data[key]);				
				form.appendChild(input);				
			}

            document.body.appendChild(form);
			form.submit();
		}

        /**
         * Show message mask( expired, not stated... )
         * @param data
         */
        $scope.showMask = function(data) {
            if(data.groupbuying == 0) {
                $scope.buyinfo.isExpire = true;
            }
            else if(data.stateText == 'prepare') {
                $scope.buyinfo.notStarted = true;
            }
            else if(data.stateText != 'started') {
                $scope.buyinfo.isExpire = true;
            }
            else {

            }
        };


		/**
		*	Join and buy the activity.
		*
		*/
		$scope.buy = function(info) {
			info.activityId =  $scope.buyinfo.id;
			var request = {
				method: 'POST',
				url: '/api/order/groupbuying/neweb',
				headers: {'Content-Type' : 'application/json'},
				data: info
			}

			$http(request).success(function(data, status, headers, config){
				$scope.orderInfo = data;
                $scope.serial = data.order.serial
				formPost(data.payment);
			}).error(function(data, status, headers, config){
				alert(data.message);
			});
		}

		/**
		*	Fetch activity info.
		*
		*/
		$scope.fetch = function() {
			var request = {
				method: 'GET',
			 	url: '/api/activity/groupbuying/' + $routeParams.id + '/buyinfo',
			 	headers: {'Content-Type' : 'application/json'},	
			 	data: {},		 	
			}

			$http(request).success(function(data, status, headers, config){
				$scope.buyinfo = data;
                $scope.showMask(data);
                $scope.footer.setFare(data.fares);
				$scope.header.setTitle(data.name);
                $scope.footer.setPrice(data.price);
                $scope.footer.setSpec(data.spec);
				$scope.footer.buy($scope.buy);
				loadInnerItem(data);
			}).error(function(data, status, headers, config){				
				alert(data.message);
			});	
		}

		$scope.fetch();
	});	
});

