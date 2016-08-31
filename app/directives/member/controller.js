/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/Member/view.html'], function (angular, app, view) {

    app.directive("member", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                idName: '=?idname'
            },
            controller: function ($scope, $timeout) {
                var arrow = "<span class=\"glyphicon glyphicon-chevron-right arrow-list\" aria-hidden=\"true\"></span>";

                $scope.idArray = [
                    {name: 'personalinformation'},
                    {name: 'changepassword'},
                    {name: 'order'}
                ]

                $timeout(function (){
                    $scope.$watch("idName", function(idName) {
                        if(idName){
                            for(var key in $scope.idArray){
                                if ($scope.idName == $scope.idArray[key].name) {
                                    $('#' + $scope.idArray[key].name).before(arrow);
                                }
                            };
                        }
                    });
                }, 100);
                
                
                
            }
        };
    });
});