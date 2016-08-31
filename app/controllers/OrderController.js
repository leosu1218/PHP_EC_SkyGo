/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'slick' , 'configs' ,'datetime'], function (angular, app, $, slick, configs, datetime) {

	return app.controller("OrderController", function ($scope, $log, $q, $timeout, $http, $interval , $cookiesHelper , $location) {
        $scope.params = {order:"client"};
        $scope.pageNo =1;
        $scope.pageSize = 10;
        $cookiesHelper.register($scope, "oauth", "oauth", true);
        $scope.messageDetail = "";
        $scope.status = {
            list:[
                {text:"尚未付款", state:"prepared"},
                {text:"付款失敗", state:"abnormal"},
                {text:"等待出貨(付款成功)", state:"paid"},
                {text:"已出貨(未到貨)", state:"delivering"},
                {text:"鑑賞期(已到貨未滿7日)" , state:"warrantyperiod"},
                {text:"申請取消訂單", state:"applycancel"},
                {text:"已完成(超過鑑賞期)", state:"completed"},
                {text:"訂單已取消", state:"cancel"},
                {text:"訂單已退貨", state:"returned"},
                {text:"未選擇", state:"all"},
            ]
        };
        $scope.payTypes = {
            list:[
                {text:"信用卡線上刷卡", type:"neweb"},
                {text:"超商繳款", type:"MMK"},
                {text:"虛擬帳號轉帳", type:"ATM"},
                {text:"超商代收", type:"CS"}
            ]
        };

        $scope.$watch("oauth", function(oauth) {
            if(oauth) {
                if($scope.oauth.result == "success") {
                    loadFromServer();
                }
                else {
                    $scope.loginViewShow.show(closeWindow);
                } 
            }
        });

        $scope.$watch("pagination", function(pagination) {
            if(pagination) {
                $scope.pagination.onPageClick(function(page) {
                    $scope.pageNo = page.number;
                    loadFromServer();
                })

                $scope.pagination.onPreviousClick(function(pageNo) {
                    $scope.pageNo--;
                    loadFromServer();
                })

                $scope.pagination.onNextClick(function(pageNo) {
                    $scope.pageNo++;
                    loadFromServer();
                })
            }
        });

        function getEachDetail(id, serial) {
            var Api = configs.api.order + "/spec/search/consumer/1/30/?ids="+ id + "&serial=" + serial;
            var Request = {
                method: 'GET',
                url: Api,
                headers: configs.api.headers
            };

            $http(Request).success(function(data, status, headers, config) {

            }).error(function(data, status, headers, config){
            });
        };

        function loadFromServer() {
            var url =  configs.api.order + '/search/consumer/each/spec/' + $scope.pageNo + '/' + $scope.pageSize +'/';
            var request = {
                method: 'GET',
                url: url,
                headers: configs.api.headers,
                params: $scope.params
            };

            $http(request).success(function(data, status, headers, config) {
                $scope.orders = data.records;
                loadFromData(data);

                // for (var i = 0; i <= $scope.orders.length-1; i++) {
                //     getEachDetail(data.records[i].id, data.records[i].serial);
                // }
            }).error(function(data, status, headers, config) {
                $scope.alert("尚未登入");
            });
        };

        function loadFromData(data) {
            $scope.pagination.load({
                recordCount: parseInt(data.recordCount, 10),
                totalPage: parseInt(data.totalPage, 10),
                pageSize: parseInt(data.pageSize, 10),
                pageNo: parseInt(data.pageNo, 10)
            });
        }

        $scope.displayPayType = function(value) {
            var type = "";
            var item = {};
            for(var index in $scope.payTypes.list) {
                item = $scope.payTypes.list[index];
                if(item.type == value) {
                    type = item.text;
                }
            }
            return type;
        }

        /**
         * Display state text field.
         *
         * @param value
         * @returns {string}
         */
        $scope.displayState = function(value) {
            var state = "未定義(回報系統商)";
            var item = {};
            for(var index in $scope.status.list) {
                item = $scope.status.list[index];
                if(item.state == value) {
                    state = item.text;
                }
            }
            return state;
        }

        $scope.displayDate = function(list) {
            if(list.stateText == "delivering"){
                var deliveryDate = new Date(datetime.parseDate(list.delivery_datetime));
                deliveryDate.setDate(deliveryDate.getDate()+10);
                return deliveryDate.getFullYear() +"-" + deliveryDate.getMonth() + "-" + deliveryDate.getDate();
            }

            return "";
        }

        $scope.returnConfirm = function(list) {
            if(list.stateText == 'warrantyperiod'){
                return true;
            }
            return false;
        }

        $scope.returnApply = function(list) {
            if((list.stateText == 'paid') || (list.stateText == 'prepared')){
                return true;
            }
            return false;
        }

        /**
         * Update to server by api.
         *
         * @param states json State object {text:<display text>, state:<server stateText>}
         * @param selected array Selected item from sb-table.
         */
        function updateStateBySelected(statesText, id) {  
            var ids = id; 
            var url = configs.api.order + "/list/state";
            var request = {
                method: 'PUT',
                url: url,
                data: {
                    ids:ids,
                    stateText: statesText
                },
                headers: configs.api.headers,
            };
            $http(request).success(function(data, status, headers, config) {
                loadFromData(data);
            }).error(function(data, status, headers, config) {
                // var message = "更改狀態失敗，請重新整理頁面後再嘗試。";
                // $scope.alert.show(message);
            });
        }

        function returnCreate(serial){
            var request = {
                method: 'POST',
                url: configs.api.returnedConsumer,
                headers: configs.api.headers,
                data: {
                    orderSerial: serial
                }
            }

            $http(request).success(function(data, status, headers, config){
                $scope.alert("成功退貨");
            }).error(function(data, status, headers, config){
                $scope.alert("退貨失敗,請重新申請");
            });

        }

        function closeWindow(){
            if($scope.oauth.result != "success"){
                $location.path('#!/')
            }
        }

        $scope.changeStatus = function(data){
            var url =  configs.api.order + '/search/consumer/each/spec/remark/' + data.id;
            var request = {
                method: 'PUT',
                url: url,
                headers: configs.api.headers,
                data: data
            };

            $http(request).success(function(data, status, headers, config){
                // $scope.alert("更改成功");
            }).error(function(data, status, headers, config){
                // $scope.alert("更改失敗");
            });
        }

        $scope.checkstatus = function(serial,data){
            $scope.eachOrder = data;
            if(serial == 0){
                $scope.unpaidApplySuccess = true;
                $scope.orderInformation = false;
                $scope.bankAccount = false;
                $scope.paidApplySuccess = false;
                $scope.serialZero = true;
            }else if (serial == 4){
                $scope.orderInformation = true;
                $scope.bankAccount = false;
                $scope.unpaidApplySuccess = false;
                $scope.paidApplySuccess = false;
                $scope.selectReasonNull = true;
            }else {
                $scope.alert("error");
            }
        }

        $scope.addSeriaZero = function(data){
            $scope.changeStatus(data);
            loadFromServer(); 
        }
 		$scope.reloadMessage = function(){
            $scope.messageDetail = "";
        }

        $scope.reloadInput = function(){
            $scope.bankName = "";
            $scope.bankBranches = "";
            $scope.bankAccountNumber = "";
            $scope.bankUsername = "";
        }
		
        function getReturnInformationByNeweb(){
            var url = configs.api.reimburse + "/return/information";
            var remark = $scope.selectReason + ':' + $scope.messageDetail;
            var req = {
                method: 'POST',
                headers: configs.api.headers,
                data: {
                    "remark": remark,
                    "bankName": "", 
                    "branches": "",
                    "account": "", 
                    "bankUsername": "",
                    "orderId": $scope.eachOrder.id,
                    "orderState": 8,
                    "name": $scope.eachOrder.buyer_name,
                    "paymentType": $scope.eachOrder.payment_type,
                    "finalTotalPrice": $scope.eachOrder.final_total_price, 
                    "orderDatetime": $scope.eachOrder.create_datetime,
                    "payDatetime": $scope.eachOrder.pay_notify_datetime,
                    "consumerUserId": $scope.eachOrder.consumer_user_id,
                    "orderSerial": $scope.eachOrder.serial
                },
                url: url
            };
            $http(req).success(function(data) {
                updateStateBySelected("applycancel", $scope.eachOrder.id);
            }).error(function() {
                // $scope.alert.show("錯誤, 請重新嘗試");
            });
        }

        $scope.nextApply = function(){
            if($scope.eachOrder.payment_type == "neweb"){
                if ($scope.selectReason) {
                    $scope.unpaidApplySuccess = false;
                    $scope.orderInformation = false;
                    $scope.bankAccount = false;
                    $scope.paidApplySuccess = true;
                    getReturnInformationByNeweb();
                }else{
                    $scope.selectReasonNull = false;
                }
            }else{
                if($scope.selectReason){
                    $scope.unpaidApplySuccess = false;
                    $scope.orderInformation = false;
                    $scope.bankAccount = true;
                    $scope.paidApplySuccess = false;
                    $scope.informationNot = true;
                }else{
                    $scope.selectReasonNull = false;
                }
            }        
        }   

        function getReturnInformation(){
            var url = configs.api.reimburse + "/return/information";
            var remark = $scope.selectReason + ':' + $scope.messageDetail;
            var req = {
                method: 'POST',
                headers: configs.api.headers,
                data: {
                    "remark": remark,
                    "bankName": $scope.bankName, 
                    "branches": $scope.bankBranches,
                    "account": $scope.bankAccountNumber, 
                    "bankUsername": $scope.bankUsername,
                    "orderId": $scope.eachOrder.id,
                    "orderState": 8,
                    "name": $scope.eachOrder.buyer_name,
                    "paymentType": $scope.eachOrder.payment_type,
                    "finalTotalPrice": $scope.eachOrder.final_total_price, 
                    "orderDatetime": $scope.eachOrder.create_datetime,
                    "payDatetime": $scope.eachOrder.pay_notify_datetime,
                    "consumerUserId": $scope.eachOrder.consumer_user_id,
                    "orderSerial": $scope.eachOrder.serial
                },
                url: url
            };
            $http(req).success(function(data) {
                updateStateBySelected("applycancel", $scope.eachOrder.id);
                // console.log("success");
            }).error(function() {
                // $scope.alert.show("錯誤, 請重新嘗試");
            });
        }
        
        $scope.addSeriaClose = function(){
            loadFromServer(); 
        }

        $scope.deliverApply = function(){
            if ($scope.bankName != null && $scope.bankBranches != null  && $scope.bankAccountNumber != null && $scope.bankUsername != null) {
                $scope.bankAccount = false;
                $scope.paidApplySuccess = true;  
                getReturnInformation();
            }else{
                $scope.informationNot = false;
            }          
        } 
	});	
});