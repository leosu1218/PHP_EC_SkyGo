/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/PermissionList/view.html'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("permissionList", function () {
        return {
            restrict: "E",
            template: view,
            controller:  function($scope, $http, $timeout) {

                $scope.permissionList = [
                    {
                        id:"1", name:"群组管理",isSelected:false
                    },
                    {
                        id:"1", name:"使用者管理",isSelected:false
                    },
                    {
                        id:"1", name:"會員查詢",isSelected:false
                    },
                ];

                function httpGet( url, callback ){
                    var request = {
                        method: 'GET',
                        url: url,
                        headers: configs.api.headers
                    };
                    $http(request).success(function(data, status, headers, config) {
                        callback(data, status, headers, config);
                    }).error(function(data, status, headers, config){
                        $scope.alert.show("發生錯誤");
                    });
                }

                $scope.labelList = {
                    "PlatformUserGroup" : "平台使用者群组-權限",
                    "PlatformUser" : "平台使用者-權限",
                    "PlatformMember" : "平台會員-權限",
                    "PlatformWebSite" : "網站-權限",
                    "PlatformGroupBuying" : "平台團購-權限",
                    "PlatformProduct" : "平台產品-權限",
                    "PlatformVideoBuying" : "平台影音銷售-權限",
                    "PlatformSystemSetting" : "平台系統-權限",
                };

                $scope.level = {
                    "0":null,
                    "1":"admin",
                    "2":"read"
                };

                function render( records ){
                    $scope.permissionList = [];
                    for( var i=0; i<(records.length); i=i+2 ){
                        var record = records[i];
                        var label = (record['name'].split("-"))[0];
                        record["leabel"] = $scope.labelList[ label ];
                        record["isSelected"] = null;
                        $scope.permissionList.push(record);
                    }
                }

                $scope.$watch("loadApi",function( url, pageNo, pageSize, queryString ){
                    if(url){
                        var apiUrl = url;
                        if( $scope.loadPageNo && $scope.loadPageSize ){
                            apiUrl += "/"+$scope.loadPageNo+"/"+$scope.loadPageSize;
                        }
                        httpGet( apiUrl, function(data, status, headers, config){
                            render(data.records);
                        });
                    }
                });

                $scope.level = { "admin":1,"read":2,"none":null };

                function getRecordById( id ){
                    id = id+"";
                    var list = $scope.permissionList;
                    var result = null;
                    for(var index in list){                        
                        if( list[index].id == id ){
                            result = list[index];
                        }
                    }
                    return result;
                }

                function setLevel( ids ){
                    var record = null;
                    for(var index in ids){
                        var id = ids[index];
                        if( id%2!=0 ){
                            record = getRecordById( id );
                            if(record){
                                record.isSelected = $scope.level["read"];
                            }
                        }
                        else{
                            id-=1;
                            record = getRecordById( id );
                            if(record){
                                record.isSelected = $scope.level["admin"];
                            }
                        }
                    }
                }

                function getLevelId( id, selected ){
                    if( selected == $scope.level["admin"]  ){
                        return id*1+1;
                    }

                    if( selected == $scope.level["read"] ){
                        return id*1;
                    }

                    return false;
                }

                function getSelected(){
                    var result = [];
                    var list = $scope.permissionList;
                    for(var index in list){
                        var selected = list[index].isSelected;
                        var id = getLevelId( list[index].id, selected );
                        if( id ){
                            result.push( id );
                        }
                    }
                    return result;
                }

                $scope.instance = {
                    loadByApi:function( url, pageNo, pageSize ){
                        $scope.loadApi = url;
                        $scope.loadPageNo = pageNo;
                        $scope.loadPageSize = pageSize;
                    },
                    setSelected:function( ids ){
                        setLevel(ids);
                    },
                    getSelected:function(){
                        return getSelected();
                    }
                };


            },
            scope: {
                instance: '=?instance'
            }
        };
    });
});