'use strict';

angular.module(
	'myApp.registrations', [
		'ngRoute'
	],
)
.config([
	'$routeProvider', 
	function($routeProvider) {

	$routeProvider.when('/registrations', {
		templateUrl: 'registrations/index.html',
		controller: 'RegistrationsListCtrl'
	});

	$routeProvider.when('/registrations/details/:id', {
		templateUrl: 'registrations/detalhes.html',
		controller: 'RegistrationsDetailsCtrl'
	});

	$routeProvider.when('/registrations/create', {
		templateUrl: 'registrations/adicionar.html',
		controller: 'RegistrationsNewCtrl'
	});
}])
.controller('RegistrationsListCtrl', 
	[
	'$http', 
	'$scope',
	'Helpers',
	'ngNotify',
	function($http, $scope, Helpers, ngNotify) {
		$scope.rows = [];

		$scope.fields = [{
			key: 'user.name',
			title: 'Aluno',
			sortable: true
		}, {
			key: 'course.title',
			title: 'Curso',
			sortable: true
		}, {
			key: 'course.category.title',
			title: 'Categoria',
			sortable: true
		}, {
			key: 'date',
			title: 'Data Matrícula',
			sortable: true
		}, {
			key: 'id',
			title: 'Actions',
			sortable: false,
			onRender: function(value){
				return '<a href="/#!/registrations/details/'+ value +'" class="btn btn-success btn-xs">Detalhes <i class="ion ion-edit"></i></a>';
			}
		}];

		$http.get(__env.apiUrl + 'registration').then(function (resp) {
			$scope.rows 				= resp.data.data;
			$scope.originalRows 		= resp.data.data;

			$scope.courseCategories 	= resp.data.data.categories;

			$scope.$watch('search', function (str) {
				if(str != undefined){
					$scope.rows = Helpers.searchGrid(str, $scope.originalRows);
				}
			});
		}, function () {
			ngNotify.set('Ocorreu um erro ao trazer as matrículas.', 'error');
		});
	}
])
.controller('RegistrationsDetailsCtrl', 
	[
	'$http', 
	'$scope',
	'Helpers',
	'$routeParams',
	'ngNotify',
	function($http, $scope, Helpers, $routeParams, ngNotify) {
		var id = parseInt($routeParams.id);

		// get all users
		$http.get(__env.apiUrl + 'user').then(function (resp) {
			$scope.users = resp.data.data;
		});
		
		// get all courses
		$http.get(__env.apiUrl + 'course').then(function (resp) {
			$scope.courses = resp.data.data.courses;
		});

		$http.get(__env.apiUrl + 'registration/' + id).then(function (resp) {
			$scope.registration			= resp.data.data;
		}, function (err) {
			ngNotify.set(err.data.data.join(' | '), 'error');
		});

		$scope.saveForm = function(){
			$http.put(__env.apiUrl + 'registration/' + $scope.registration.id, $scope.registration).then(function (resp) {
				ngNotify.set('O curso foi salvo com sucesso!', 'success');

				setTimeout(function(){
					location.href = "/#!/registrations"
				}, 500);
			}, function (err) {
				ngNotify.set(err.data.data.join(' | '), 'error');
			});
		}

		$scope.deleteRegistration = function(){
			$http.delete(__env.apiUrl + 'registration/' + $scope.registration.id).then(function (resp) {
				ngNotify.set('O curso foi excluído com sucesso!', 'success');
				angular.element('#DeleteModal').modal('hide');

				setTimeout(function(){
					location.href = "/#!/registrations"
				}, 500);
			}, function (err) {
				ngNotify.set(err.data.data.join(' | '), 'error');
			});
		}
	}
])
.controller('RegistrationsNewCtrl', 
	[
	'$http', 
	'$scope',
	'$routeParams',
	'ngNotify',
	function($http, $scope, $routeParams, ngNotify) {
		$scope.registration = {};
		
		// get all users
		$http.get(__env.apiUrl + 'user').then(function (resp) {
			$scope.users = resp.data.data;
		});
		
		// get all courses
		$http.get(__env.apiUrl + 'course').then(function (resp) {
			$scope.courses = resp.data.data.courses;
		});
		
		$scope.saveForm = function(){
			$http.post(__env.apiUrl + 'registration', $scope.registration).then(function (resp) {
				ngNotify.set('A matrícula foi criada com sucesso!', 'success');

				setTimeout(function(){
					location.href = "/#!/registrations"
				}, 500);
			}, function (err) {
				ngNotify.set(err.data.data.join(' | '), 'error');
			});
		}
	}
]);