/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

	/**
	*	Permission Column widget.
	*
	*	@attribute permissions json The permissions collection
	*									[
	*										{
	*											name: "組合商品權限",
	*											icon: "gift",
	*											items: [
	*												{id:24, name:"新增商品", icon:"plus", selected:false},
	*												{id:25, name:"刪除商品", icon:"minus", selected:false},
	*												{id:26, name:"修改商品", icon:"edit", selected:false},
	*												{id:27, name:"瀏覽商品列表", icon:"list-alt", selected:false},
	*											]
	*										},
	*										{ permission item2 ... }
	*									];
	*
	*
	*/
	app.directive("permissionColumn", function () {
		return {
			restrict: "E",
			// replace: true,
			// transclude: true,
			templateUrl: app.applicationPath + "/views/permissionColumn.html",
			controller:  function($scope) {					
				$scope.selectAll = function(items, isSelected) {
					var item, key;
					for(key in items) {
						item = items[key];
						item.selected = isSelected;
					}					
				}
			},
			scope: {
				permissions: "=permissions",
				permissionId: "=permissionId"
			},			
		};
	});
});