/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs'], function (angular, app, createController,configs) {

    return app.controller("ConsumerUserController", createController(function ($scope,$routeParams) {
        $scope.api = "api/consumeruser";
        $scope.pageSize = 10;
        $scope.$watch("table", function(table) {
            if(table) {
                var searchTable = $scope.table.getTable();
                searchTable.configField([
                    {attribute: "id",       name: "id"  },
                    {attribute: "name",       name: "姓名"  },
                    {attribute: "account",       name: "帳號"  },
                    {attribute: "phone",         name: "電話"  },
                    {attribute: "email",                name: "Email"  },
                    {attribute: "create_datetime",       name: "註冊日期"  },
                    {attribute: "control",          name: "控制",
                        controls: [
                            {type: "button", icon: "fa-search" ,click: viewDetail}
                        ]
                    }
                ]);

                searchTable.loadByUrl( $scope.api, 1, $scope.pageSize,
                    function(data, status, headers, config) {
                        // Handle reload table success;
                    },
                    function(data, status, headers, config) {
                        $scope.alert.show("無法搜尋到資料");
                    }
                );

            }
        });

        /**
         * view the Consumer　order.
         */
        function viewDetail(row, value) {
            location.href = "#!/comsumer/order/list/"+row.id;
        }

    }));

});