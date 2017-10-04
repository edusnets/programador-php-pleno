'use strict';

angular.module(
	'myApp.users', [
		'ngRoute',
		'ui.bootstrap.datetimepicker'
	],
)
.config([
	'$routeProvider', 
	function($routeProvider) {

	$routeProvider.when('/users', {
		templateUrl: 'users/index.html',
		controller: 'UsersListCtrl'
	});

	$routeProvider.when('/users/details/:id', {
		templateUrl: 'users/detalhes.html',
		controller: 'UsersDetailsCtrl'
	});

	$routeProvider.when('/users/create', {
		templateUrl: 'users/adicionar.html',
		controller: 'UsersNewCtrl'
	});
}])
.controller('UsersListCtrl', 
	[
	'$http', 
	'$scope',
	'Helpers',
	function($http, $scope, Helpers) {
		$scope.rows = [];

		$scope.fields = [{
			key: 'name',
			title: 'Nome',
			sortable: true
		}, {
			key: 'email',
			title: 'E-mail',
			sortable: true
		}, {
			key: 'birthdate',
			title: 'Aniversário',
			sortable: true,
			onRender: function(value){
				return moment(value).format('DD/MM/YYYY');
			}
		}, {
			key: 'id',
			title: 'Actions',
			sortable: false,
			onRender: function(value){
				return '<a href="/#!/users/details/'+ value +'" class="btn btn-success btn-xs">Detalhes <i class="ion ion-edit"></i></a>';
			}
		}];

		$http.get(__env.apiUrl + 'user').then(function (resp) {
			$scope.rows 			= resp.data.data;
			$scope.originalRows 	= resp.data.data;

			$scope.$watch('search', function (str) {
				if(str != undefined){
					$scope.rows = Helpers.searchGrid(str, $scope.originalRows);
				}
			});
		}, function () {
			console.error('fail to retrieve call details')
		});
	}
])
.controller('UsersDetailsCtrl', 
	[
	'$http', 
	'$scope',
	'Helpers',
	'$routeParams',
	'ngNotify',
	function($http, $scope, Helpers, $routeParams, ngNotify) {
		var id = parseInt($routeParams.id);

		$http.get(__env.apiUrl + 'user/' + id).then(function (resp) {
			$scope.user 			= resp.data.data.user;
			$scope.registrations	= resp.data.data.registrations;
			$scope.userFormTitle 	= resp.data.data.user.name;
		}, function (err) {
			ngNotify.set(err.data.data.join(' | '), 'error');
		});

		$scope.onTimeSet = function (newDate, oldDate) {
			$scope.user.birthdate = newDate.format('YYYY-MM-DD');
		}

		$scope.saveForm = function(){
			$http.put(__env.apiUrl + 'user/' + id, $scope.user).then(function (resp) {
				ngNotify.set('O usuário foi salvo com sucesso!', 'success');

				setTimeout(function(){
					location.href = "/#!/users"
				}, 500);
			}, function (err) {
				ngNotify.set(err.data.data.join(' | '), 'error');
			});
		}

		$scope.deleteUser = function(){
			$http.delete(__env.apiUrl + 'user/' + $scope.user.id).then(function (resp) {
				ngNotify.set('O usuário foi excluído com sucesso!', 'success');
				angular.element('#DeleteModal').modal('hide');

				setTimeout(function(){
					location.href = "/#!/users"
				}, 500);
			}, function (err) {
				ngNotify.set(err.data.data.join(' | '), 'error');
			});
		}
	}
])
.controller('UsersNewCtrl', 
	[
	'$http', 
	'$scope',
	'$routeParams',
	'ngNotify',
	function($http, $scope, $routeParams, ngNotify) {

		$scope.onTimeSet = function (newDate, oldDate) {
			$scope.user.birthdate = newDate.format('YYYY-MM-DD');
		}
		
		$scope.saveForm = function(){
			$http.post(__env.apiUrl + 'user', $scope.user).then(function (resp) {
				ngNotify.set('O usuário foi cadastrado com sucesso!', 'success');

				setTimeout(function(){
					location.href = "/#!/users"
				}, 500);
			}, function (err) {
				ngNotify.set(err.data.data.join(' | '), 'error');
			});
		}
	}
]);