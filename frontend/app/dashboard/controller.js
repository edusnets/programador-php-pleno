'use strict';

angular.module(
	'myApp.dashboard', [
		'ngRoute'
	]
)
.config([
	'$routeProvider', 
	function($routeProvider) {

	$routeProvider.when('/dashboard', {
		templateUrl: 'dashboard/dashboard.html',
		controller: 'DashboardCtrl'
	});
}])

.controller('DashboardCtrl', 
	[
	'$http', 
	'$scope',
	function($http, $scope) {
		//return $http.post(Settings.api + '/ligacao', filter); 
		
		$http.get(__env.apiUrl + 'dashboard').then(function (resp) {
			$scope.dashboard = resp.data.data;
		}, function () {
			console.error('fail to retrieve call details')
		});

}]);