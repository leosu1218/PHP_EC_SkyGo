/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

	/**
	*	Carousel widget.
	*
	*	<bs-carousel 	slides="slidesMenu" 
	*					padding-top="137" 
	*					action-interval="5" >
	*	</bs-carousel>
	*
	*	@attribute slides json The slide menu items info.
	*									[
	*										{
	*											image: "http://mostafiz.me/demo/doctor/img/slide-one.jpg", 
	*											title: "providing",
	*											text: "highquality service for men & women",
	*											active: true							
	*										},
	*										{ item2 ... }
	*									];
	*
	*
	*/
	app.directive("bsCarousel", function () {		
		return {
			restrict: "E",			
			templateUrl: app.applicationPath + "/views/BsCarousel.html",
			scope: {
				slides: "=slides",
				paddingTop: "=paddingTop",
				actionInterval: "=actionInterval",
			},
			controller:  function($scope) {
				$scope.interval = $scope.actionInterval * 1000;				
			}
		};
	});	
});