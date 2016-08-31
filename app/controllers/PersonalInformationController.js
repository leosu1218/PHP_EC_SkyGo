/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'slick', 'configs'], function (angular, app, $, slick, configs) {

	return app.controller("PersonalInformationController", function ($scope, $location, $http, $timeout, $cookiesHelper) {
        
        $scope.modifyShow = true;
        $scope.$watch("oauth", function(oauth) {
            if(oauth) {        	
                if($scope.oauth.result == "success") {
                	getConsumerUserData();
                }
            }
        });

		function getConsumerUserData(num) {
            var url =  configs.api.consumeruser + '/' + $scope.oauth.id;
            var request = {
                method: 'GET',
                url: url,
                headers: configs.api.headers,
                params: $scope.params
            };

            $http(request).success(function(data, status, headers, config) {
                if(num == 1){
                    $scope.items = data;    
                }else{
                    $scope.items = data;
                    getGender($scope.items.gender);
                    $scope.birthdayDate.setdate($scope.items.birthday);
                    markInformation(data);    
                }
            }).error(function(data, status, headers, config) {
                $scope.alert("尚未登入");
            });
        };	

        $scope.updateInformation = function(){
        	checkGender();
        	var personalData = {
        		"id": $scope.oauth.id,
	        	"email": $scope.items.email, 
	        	"name": $scope.items.name,
	        	"gender": $scope.genderNumber,
	        	"birthday": $scope.birthdayDate.getdate(),
	        	"address": $scope.items.address,
	        	"phone": $scope.items.phone
	        }
            var url =  configs.api.consumeruser + '/personal';
            var request = {
                    method: 'PUT',
                    headers: configs.api.headers,
                    data: personalData,
                    url: url
                };

            $http(request).success(function(data, status, headers, config) {
                $scope.alert.show("修改成功");
            }).error(function(data, status, headers, config) {
                $scope.alert.show("未更改資料或系統內部發生異常");
            });
        };		

        function getGender(gender){
        	if(gender == 0) {
        		$('#genderRadiosFemale').prop("checked","true");
        	}else{
        		$('#genderRadiosMale').prop("checked","true");
        	}
        };

        function checkGender(){
            if($('#genderRadiosMale').prop('checked')){
                $scope.genderNumber = 1;
            }else if($('#genderRadiosFemale').prop('checked')){
            	$scope.genderNumber = 0;
            }
        }

        $scope.modifyStart = function(){
            $scope.modifyShow = false;
            $('input').removeProp('disabled');
            getConsumerUserData(1);
        }

        function markInformation(value){
            var starNumName = value.name.length-1;
            var starNumEmail = value.email.length;
            var numIndex = value.email.indexOf("@")+1;         
            var starNumEmail = starNumEmail - numIndex;
            $scope.items.name = value.name.replace(value.name.substr(1,starNumName), $scope.addStar(starNumName));
            $scope.items.phone = value.phone.replace(value.phone.substr(4,3), "***");
            $scope.items.email = value.email.replace(value.email.substr(numIndex,starNumEmail), $scope.addStar(starNumEmail));
            $scope.items.address = value.address.replace(value.address.substr(3,6), "******");            
        }
  
        $scope.addStar = function(value){
            var str = "";
            for (var i = 0; i < value; i++) {
                var str = str.concat("*");
            }
            return str;
        }
	});	
});