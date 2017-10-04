'use strict';

angular.module(
	'myApp.courses', [
		'ngRoute'
	],
)
.config([
	'$routeProvider', 
	function($routeProvider) {

	$routeProvider.when('/courses', {
		templateUrl: 'courses/index.html',
		controller: 'CoursesListCtrl'
	});

	$routeProvider.when('/courses/details/:id', {
		templateUrl: 'courses/detalhes.html',
		controller: 'CoursesDetailsCtrl'
	});

	$routeProvider.when('/courses/create', {
		templateUrl: 'courses/adicionar.html',
		controller: 'CoursesNewCtrl'
	});
}])
.controller('CoursesListCtrl', 
	[
	'$http', 
	'$scope',
	'Helpers',
	'ngNotify',
	function($http, $scope, Helpers, ngNotify) {
		$scope.rows = [];

		$scope.fields = [{
			key: 'title',
			title: 'Título',
			sortable: true
		}, {
			key: 'description',
			title: 'Descrição',
			sortable: true
		}, {
			key: 'category_name',
			title: 'Categoria',
			sortable: true
		}, {
			key: 'id',
			title: 'Actions',
			sortable: false,
			onRender: function(value){
				return '<a href="/#!/courses/details/'+ value +'" class="btn btn-success btn-xs">Detalhes <i class="ion ion-edit"></i></a>';
			}
		}];

		$http.get(__env.apiUrl + 'course').then(function (resp) {
			$scope.rows 				= resp.data.data.courses;
			$scope.originalRows 		= resp.data.data.courses;

			$scope.courseCategories 	= resp.data.data.categories;

			$scope.$watch('search', function (str) {
				if(str != undefined){
					$scope.rows = Helpers.searchGrid(str, $scope.originalRows);
				}
			});
		}, function () {
			ngNotify.set('Ocorreu um erro ao trazer os cursos.', 'error');
		});
	}
])
.controller('CoursesDetailsCtrl', 
	[
	'$http', 
	'$scope',
	'Helpers',
	'$routeParams',
	'ngNotify',
	function($http, $scope, Helpers, $routeParams, ngNotify) {
		var id = parseInt($routeParams.id);

		$http.get(__env.apiUrl + 'course/' + id).then(function (resp) {
			$scope.course 				= resp.data.data.course;
			$scope.courseFormTitle 		= resp.data.data.course.title;
			$scope.courseCategories 	= resp.data.data.categories;

			$scope.registrations 		= resp.data.data.registrations;

		}, function (err) {
			ngNotify.set(err.data.data.join(' | '), 'error');
		});

		$scope.saveForm = function(){
			$http.put(__env.apiUrl + 'course/' + id, $scope.course).then(function (resp) {
				ngNotify.set('O curso foi salvo com sucesso!', 'success');

				setTimeout(function(){
					location.href = "/#!/courses"
				}, 500);
			}, function (err) {
				ngNotify.set(err.data.data.join(' | '), 'error');
			});
		}

		$scope.deleteCourse = function(){
			$http.delete(__env.apiUrl + 'course/' + $scope.course.id).then(function (resp) {
				ngNotify.set('O curso foi excluído com sucesso!', 'success');
				angular.element('#DeleteModal').modal('hide');

				setTimeout(function(){
					location.href = "/#!/courses"
				}, 500);
			}, function (err) {
				ngNotify.set(err.data.data.join(' | '), 'error');
			});
		}
	}
])
.controller('CoursesNewCtrl', 
	[
	'$http', 
	'$scope',
	'$routeParams',
	'ngNotify',
	function($http, $scope, $routeParams, ngNotify) {

		$http.get(__env.apiUrl + 'categories').then(function (resp) {
			$scope.courseCategories = resp.data.data;
		});
		
		$scope.saveForm = function(){
			$http.post(__env.apiUrl + 'course', $scope.course).then(function (resp) {
				ngNotify.set('O curso foi cadastrado com sucesso!', 'success');

				setTimeout(function(){
					location.href = "/#!/courses"
				}, 500);
			}, function (err) {
				ngNotify.set(err.data.data.join(' | '), 'error');
			});
		}
	}
]);