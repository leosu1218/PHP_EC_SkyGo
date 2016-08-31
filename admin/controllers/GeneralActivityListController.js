/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'message', 'configs', 'datetime'],
    function (angular, app, createController, message, configs, datetime) {

        return app.controller("GeneralActivityListController",
            createController(function ($scope , $routeParams, $http, $timeout) {

                /**
                 * Defined default search model's params.
                 */
                $scope.defaultSearch = function(){
                    $scope.pageSize = 10;
                    $scope.activityStatus = {
                        list:[
                            {text:"活動尚未開始", state:"prepare"},
                            {text:"活動進行中", state:"started"},
                            {text:"活動已結束", state:"completed"},
                            {text:"未選擇", state:"all"},
                        ]
                    };

                    $scope.search = {};
                    $scope.search.keyword = null;
                    $scope.search.startDateOpen = null;
                    $scope.search.startDateClose = null;
                    $scope.search.endDateOpen = null;
                    $scope.search.endDateClose = null;
                    $scope.selectedItem = $scope.activityStatus.list[3];
                    $scope.search.state = "all";

                    $timeout(function() {
                        $scope.startDateOpen.setdate(null);
                        $scope.startDateClose.setdate(null);
                        $scope.endDateOpen.setdate(null);
                        $scope.endDateClose.setdate(null);
                    }, 200);
                };

                $scope.defaultSearch();

                /**
                 * Format datetime object to string (Y:m:d H:i:s)
                 *
                 * @param date
                 * @param time
                 * @returns {string}
                 */
                function dateTimeFormat (date, time) {
                    time = time || date;
                    var year = "" + date.getFullYear();
                    var month = "" + (date.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
                    var day = "" + date.getDate(); if (day.length == 1) { day = "0" + day; }
                    var hour = "" + time.getHours(); if (hour.length == 1) { hour = "0" + hour; }
                    var minute = "" + time.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
                    var second = "" + time.getSeconds(); if (second.length == 1) { second = "0" + second; }
                    return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
                }

                $timeout(function() {
                    $scope.startDateOpen.onDateInputChanged(function() {
                        $scope.search['startDateOpen'] = $scope.startDateOpen.getdate();
                    })
                    $scope.startDateClose.onDateInputChanged(function() {
                        $scope.search['startDateClose'] = $scope.startDateClose.getdate();
                    })
                    $scope.endDateOpen.onDateInputChanged(function() {
                        $scope.search['endDateOpen'] = $scope.endDateOpen.getdate();
                    })
                    $scope.endDateClose.onDateInputChanged(function() {
                        $scope.search['endDateClose'] = $scope.endDateClose.getdate();
                    })
                }, 200);

                /**
                 * On user selected state that want to search.
                 * @param item
                 */
                $scope.onStateSelectorChanged = function(item) {
                    $scope.selectedItem = item;
                    $scope.search.state = item.state;
                };

                /**
                 * On user click search button.
                 */
                $scope.searchButtonOnClick = function() {
                    var listUrl = configs.api.generalActivity + "/search";
                    $scope.table.loadByUrl( listUrl, 1, $scope.pageSize,
                        function(data, status, headers, config) {
                            // Handle reload table success;
                        },
                        function(data, status, headers, config) {
                            $scope.alert.show("無法搜尋到資料");
                        },
                        $scope.search
                    );
                };

                /**
                 * Display state text field.
                 *
                 * @param value
                 * @param row
                 * @returns {string}
                 */
                function displayState(value, row) {
                    var state = "未定義(回報系統商)";
                    var item = {};
                    for(var index in $scope.activityStatus.list) {
                        item = $scope.activityStatus.list[index];
                        if(item.state == value) {
                            state = item.text;
                        }
                    }
                    return state;
                }

                /**
                 * Edit a activity record's note.
                 *
                 * @param row json Row object of table.
                 * @param attribute string Attribute name.
                 */
                function editNote(row, attribute) {

                    function updateNote(item) {
                        var request = {
                            method: 'PUT',
                            url: configs.api.generalActivity + '/' + row.id + '/note',
                            data: {note: item.note},
                            headers: configs.api.headers
                        };

                        $http(request).success(function(data, status, headers, config) {
                            row.note = item.note;
                        }).error(function(data, status, headers, config) {
                            $scope.alert("更新錯誤！");
                        });
                    }

                    $scope.modal.config({
                        controls:[
                            {position:"header", type:"text",label:"修改欄位"},
                            {
                                position:"body",
                                attributeName :"note",
                                attribute:row.note,
                                type:"input",
                                label:"備註"
                            },
                            {
                                position:"footer",
                                type:"button",
                                label:"確定",
                                target: updateNote
                            }
                        ]
                    });

                    $scope.modal.show();
                }

                /**
                 * View a activity's record detail.
                 *
                 * @param row json Row object of table.
                 * @param attribute string Attribute name.
                 */
                function viewDetail(row, attribute) {
                    //TODO modified
                    location.href = configs.path.generalActivity + '/' + row.id;
                }

                /**
                 * View a activity's buyer website.
                 *
                 * @param row json Row object of table.
                 * @param attribute string Attribute name.
                 */
                function viewActivitySite(row, attribute) {
                    window.open(location.origin + "/index.html");
                }

                //table
                $timeout(function(){

                    //main table for admin to using.
                    $scope.table.configField([
                        {attribute: "id", name: "活動ID"},
                        {attribute: "name", name: "活動名稱"},
                        {attribute: "masterName", name: "建立者"},
                        {attribute: "price", name: "定價"},
                        {attribute: "proposePrice", name: "建議售價"},
                        {attribute: "costPrice", name: "成本價"},
                        {attribute: "start_date", name: "活動開始時間"},
                        {attribute: "end_date", name: "活動結束時間"},
                        {attribute: "stateText", name: "活動狀態",filter: displayState},
                        {attribute: "note", name: "備註"},
                        {attribute: "control", name: "控制",controls: [
                            {type: "button", icon: "fa-pencil-square", click: editNote},
                            {type: "button", icon: "fa-search", click: viewDetail},
                            {type: "button", icon: "fa-unlink", click: viewActivitySite}]
                        },
                    ]);

                    $scope.searchButtonOnClick();
                    $scope.table.rowClickCss({'background-color':'#FFDDAA'});
                    $scope.table.onRowClick(function(row, field, instance) {
                        if(field != 'control') {
                            instance.selected();
                        }
                    });

                }, 100);
            })
        );
    });