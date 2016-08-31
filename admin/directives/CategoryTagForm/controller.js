/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/CategoryTagForm/view.html'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("categoryTagForm", function () {
        return {
            restrict: "E",
            template: view,
            controller: function ($scope, $http, $timeout) {
                $scope.id = null;

                function loadTagRecords(){
                    var url = configs.api.tag + "/" + $scope.id;
                    var req = {
                        method: 'GET',
                        url: url,
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    };
                    $http(req).success(function(result) {
                        $scope.chinese_name = result['main']['records'][0]['chinese_name'];
                        $scope.english_name = result['main']['records'][0]['english_name'];
                        $scope.tagImage = configs.path.tagImage + "/" + result['main']['records'][0]['image_filename'];
                        $scope.records = result;
                    }).error(function(error) {
                        // Do nothings.
                    });
                }

                function save( data, type, id ){
                    var url = configs.api.tag + "/" + type + "/" + id;
                    var req = {
                        method: 'PUT',
                        url: url,
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        data:{
                            update:data
                        }
                    };
                    $http(req).success(function(result) {
                        // Do nothings.
                    }).error(function(error) {
                        // Do nothings.
                    });
                };

                $scope.save = function( item ){
                    if( item == "chinese_name" ){
                        save( {chinese_name:$scope.chinese_name}, "main", $scope.id );
                    }
                    else if( item == "english_name" ){
                        save( {english_name:$scope.english_name}, "main", $scope.id );
                    }
                }

                $scope.$watch("upload", function(upload) {
                    if(upload) {
                        upload.api(  configs.api.tag+"/upload/"+$scope.id );
                        upload.label("選擇要更新的圖片");
                        upload.mutiple(false);
                        upload.success(function(data, status, headers, config){
                            $scope.tagImage = configs.path.tagImage + "/" + data['fileName'];
                        });
                        upload.fail(function(data, status, headers, config) {
                            $scope.alert.show("您上傳的檔案中包含曾經上傳過的檔案，或是不允許的檔案格式，請重新確認。");
                        });
                    }
                });

                function updateForm(row, attribute){
                    $scope.modal.config({
                        controls:[
                            {
                                position:"header",
                                type:"text",
                                label:"更新",
                            },
                            {
                                position        :"body",
                                type            :"input",
                                attribute       : row.name,
                                attributeName   :"name",
                                label           :"標籤名稱"
                            },
                            {
                                position:"footer",
                                type:"button",
                                label:"確定",
                                target:function( result ){
                                    if( row.name!=result.name ){
                                        save( {name:result.name}, $scope.updateTable, row.id );
                                        loadTagRecords();
                                    }
                                }
                            }
                        ]
                    });

                    $scope.modal.show();
                }

                $scope.insertForm =  function(tableType){
                    $scope.modal.config({
                        controls:[
                            {
                                position:"header",
                                type:"text",
                                label:"新增標籤",
                            },
                            {
                                position        :"body",
                                type            :"input",
                                attribute       : "",
                                attributeName   :"name",
                                label           :"標籤名稱"
                            },
                            {
                                position:"footer",
                                type:"button",
                                label:"確定",
                                target:function( result ){
                                    insert( {'name':result.name , 'mct_id' : $scope.id}, tableType);
                                    loadTagRecords();
                                }
                            }
                        ]
                    });

                    $scope.modal.show();
                }

                function insert(data, tableType){
                    var url = configs.api.tag + "/insert/" + tableType;

                    var req = {
                        method: 'POST',
                        url: url,
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        data:{
                            insert:data
                        }
                    };
                    $http(req).success(function(result) {
                        // Do nothings.
                    }).error(function(error) {
                        // Do nothings.
                    });
                };

                function prepareTable(tagTable, records, tableType){
                    tagTable.configField(
                        [
                            {
                                attribute:"id",
                                name:"#"
                            },
                            {
                                attribute:"name",
                                name:"標籤名稱"
                            },
                            {               
                                attribute:"control", 
                                name: "控制",
                                controls: [
                                    {
                                        type: "button",
                                        icon: "fa-pencil",
                                        click: function(row,attribute){
                                            $scope.updateTable = tableType;
                                            updateForm(row,attribute);
                                        }
                                    },
                                    {
                                        type: "button",
                                        icon: "fa-times ",
                                        click: function(row,attribute){
                                           deleteTag(row,tableType);
                                        }
                                    }
                                ]
                            },
                        ]
                    );

                    tagTable.load( records );
                }

                function tableConfig(){
                    $scope.$watch("subTagOne", function(tagTable) {
                        if(tagTable) {
                            prepareTable( tagTable, $scope.records['subOne'], 'subOne' );
                        }
                    });
                    $scope.$watch("subTagTwo", function(tagTable) {
                        if(tagTable) {
                            prepareTable( tagTable, $scope.records['subTwo'], 'subTwo' );
                        }
                    });
                }

                function deleteTag(row,tableType){
                    var url = configs.api.tag + "/delete/" + tableType + "/" +  row.id;
                    var req = {
                        method: 'DELETE',
                        url: url,
                        headers: {
                            'Content-Type': 'application/json'
                        },
                    };
                    $http(req).success(function(result) {
                        loadTagRecords();
                    }).error(function(error) {
                        // Do nothings.
                    });

                }

                $scope.$watch("records",function( records ){
                    if(records){
                        tableConfig();
                    }
                });

                $scope.instance = {

                    id:function( id ){
                        $scope.id = id;
                        loadTagRecords();
                    }

                };

            },
            scope: {
                instance: '=?instance',
            }
        }
    });
});