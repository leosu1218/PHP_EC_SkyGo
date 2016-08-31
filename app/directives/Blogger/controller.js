/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'text!directives/Blogger/view.html', 'slick'], function (angular, app, $, view, slick) {

	app.directive("blogger", function () {
		return {
			restrict: "E",			
			template: view,
			scope: {				
				instance: '=?instance',
			},
			controller:function ($scope, $timeout, $window) {

				$scope.imageUrl = "/upload/website/blogger/";
				
				function bloggerChapterRender(index){
					$scope.productAdImages = $scope.bloggerChapter[index]["images"];
    				$scope.coverImage = $scope.bloggerChapter[index]["coverImage"];
    				$scope.titleOne = $scope.bloggerChapter[index]["titleOne"];
    				$scope.titleTwo = $scope.bloggerChapter[index]["titleTwo"];
    				$scope.bodyText = $scope.bloggerChapter[index]["bodyText"];
				}

				function clickEvent( number ){
					return (function(){
	                	bloggerChapterRender(number);
	                });
				}

				function imagesBuild( bloggerChapter ){
					var ini = [];
	                var images = bloggerChapter;
	                for(var index in images){
	                	ini.push({
	                		url:images[index].productImage,
	                		click:clickEvent( index )
	                	});
	                }
	                return ini;
				}

				$scope.$watch("slider",function(instance){

					if( instance ){
						instance.configs({
		                    slidesToShow: 2
		                });
		                
		                instance.imageCss({
		                	width:"65px",
		                	height:"100%"
		                });

		                instance.imageUrl( "/upload/website/blogger/" );
		                $scope.$watch("iniImages",function(iniImages){
		                	if( iniImages ){
		                		instance.init(iniImages);
		                	}
		                });
					}

				});



				$scope.$watch("bloggerChapter",function( bloggerChapter ){
					$scope.iniImages = imagesBuild( bloggerChapter );
				});

				$scope.instance = {
					render:function( bloggerChapter, label ){
						$scope.bloggerChapter = bloggerChapter;
						$scope.label = label;
						bloggerChapterRender(0);
					}
				};

			}
		};
	});
});