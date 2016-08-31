/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/SlickSlider/view.html'], function (angular, app, view) {

    app.directive("slickSlider", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                instance: '=?instance',
                configs: '=?configs'
            },
            controller: function ($scope) {
                $scope.css = {};

                $scope.id = randomString(5, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
                $scope.configs =  $scope.configs || {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    speed: 500,
                    dots: false,
                    centerMode: true,
                    focusOnSelect: true,
                    arrows: false,
                    slide: 'div',
                };

                /**
                 * Create random id.
                 * @param length
                 * @param chars
                 * @returns {string}
                 */
                function randomString(length, chars) {
                    var result = '';
                    for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
                    return result;
                }

                function cssToString( css ){
                    // var css = $scope.css;
                    var stringCss = "";
                    for(var index in css){
                        stringCss += index+":"+css[index]+";";
                    }
                    return stringCss;
                }

                /**
                 * Get jquery element object.
                 * @returns {*|jQuery|HTMLElement}
                 */
                $scope.getJElement = function() {
                    return $(document.getElementById($scope.id));
                };

                $scope.items = [];

                $scope.$watch("items", function(items) {
                    if(items) {

                        try{
                            $scope.getJElement().slick("unslick");
                            $scope.getJElement().html("");
                        }
                        catch(e) {
                        }

                        var item = null;
                        for(var index in items) {
                            item = items[index];
                            var jItemElement = $('<div class="product-slider-sm" align=\"center\" ><img style=\"'+cssToString($scope.imageCss)+'\" src="' + $scope.imageUrl + item.url + '"><div>'+(item.label||'')+'</div></div>');
                            if(typeof(items[index].click) == 'function') {
                                jItemElement.click(items[index].click);
                            }
                            $scope.getJElement().append(jItemElement);
                        }

                        $scope.getJElement().slick($scope.configs);
                    }
                });

                /**
                 * Instance for client
                 * @type {{}}
                 */
                $scope.instance = {
                    init: function(items) {
                        $scope.items = items;
                    },

                    configs: function(configs) {
                        $scope.configs = angular.extend($scope.configs, configs);
                    },

                    imageCss: function( css ){
                        $scope.imageCss = css;
                    },

                    divCss: function( css ){
                        $scope.divCss = css;
                    },

                    imageUrl:function( url ){
                        $scope.imageUrl = url;
                    }
                };

            }
        };
    });
});