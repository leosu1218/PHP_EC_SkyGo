/*global define*/
'use strict';

define(['angular', 'app', 'configs','jquery', 'slick', 'bloggerContext'], function (angular, app, configs, $, slick, bloggerContext) {

	return app.controller("BloggerController", function ($scope, $timeout, $window) {

		$scope.sliderNav = bloggerContext;

		function clickEvent( number ){
			return (function(){
				var data = $scope.sliderNav[ number ];
				$scope.blogger.render( data.article, data.modelName );
            });
		}

		function imagesBuild( chapters ){
			var ini = [];
            for(var index in chapters){
            	ini.push({
            		url:chapters[index].modelImage,
            		label:chapters[index].modelName,
            		click:clickEvent( index )
            	});
            }
            return ini;
		}

        $scope.$watch("slider", function(instance) {
            if(instance) {

            	if($window.innerWidth<767){
            		instance.configs({
	                    slidesToShow: 2
	                });
            	}else{
            		instance.configs({
	                    slidesToShow: 6
	                });
            	}

                instance.imageCss({
                	"width":"80px",
                	"height":"100%",
                	"border-radius": "50%"
                });
                
                instance.imageUrl( "/upload/website/blogger/" );

                var images = imagesBuild( $scope.sliderNav );
                instance.init(images);
            }
        });

		$scope.$watch("blogger",function(blogger){
			if(blogger){
				var data = $scope.sliderNav[0];
				blogger.render( data.article, data.modelName );
			}
		});
        
	});
});