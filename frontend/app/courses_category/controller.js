'use strict';

angular.module(
	'myApp.courses_category', [
		'ngRoute'
	],
)
.config([
	'$routeProvider', 
	function($routeProvider) {

	$routeProvider.when('/courses-category', {
		templateUrl: 'courses_category/index.html',
		controller: 'CoursesCategoryListCtrl'
	});

	$routeProvider.when('/courses-category/details/:id', {
		templateUrl: 'courses_category/detalhes.html',
		controller: 'CoursesCategoryDetailsCtrl'
	});

	$routeProvider.when('/courses-category/create', {
		templateUrl: 'courses_category/adicionar.html',
		controller: 'CoursesCategoryNewCtrl'
	});
}])
.controller('CoursesCategoryListCtrl', 
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
			key: 'icon',
			title: 'Ícone',
			sortable: true,
			onRender: function(value){
				if(value  === null){
					return '';
				}
				return value + ' <i class="ion ' + value + '"></i>';
			}
		}, {
			key: 'courses.length',
			title: 'Cursos',
			sortable: true
		}, {
			key: 'id',
			title: 'Actions',
			sortable: false,
			onRender: function(value){
				return '<a href="/#!/courses-category/details/'+ value +'" class="btn btn-success btn-xs">Detalhes <i class="ion ion-edit"></i></a>';
			}
		}];

		$http.get(__env.apiUrl + 'course_category').then(function (resp) {
			$scope.rows 				= resp.data.data;
			$scope.originalRows 		= resp.data.data;

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
.controller('CoursesCategoryDetailsCtrl', 
	[
	'$http', 
	'$scope',
	'Helpers',
	'$routeParams',
	'ngNotify',
	function($http, $scope, Helpers, $routeParams, ngNotify) {
		var id = parseInt($routeParams.id);

		$http.get(__env.apiUrl + 'course_category/' + id).then(function (resp) {
			$scope.course_category				= resp.data.data.categories;
			$scope.courseCategoryFormTitle 		= resp.data.data.categories.title;
			$scope.courseCategories 			= resp.data.data.categories;

		}, function (err) {
			ngNotify.set(err.data.data.join(' | '), 'error');
		});

		$scope.saveForm = function(){
			$http.put(__env.apiUrl + 'course_category/' + id, $scope.courseCategories).then(function (resp) {
				ngNotify.set('A categoria do curso foi salva com sucesso!', 'success');

				setTimeout(function(){
					location.href = "/#!/courses-category"
				}, 500);
			}, function (err) {
				ngNotify.set(err.data.data.join(' | '), 'error');
			});
		}

		$scope.deleteCourse = function(){
			$http.delete(__env.apiUrl + 'course_category/' + $scope.courseCategories.id).then(function (resp) {
				ngNotify.set('A categoria foi excluída com sucesso!', 'success');
				angular.element('#DeleteModal').modal('hide');

				setTimeout(function(){
					location.href = "/#!/courses-category"
				}, 500);
			}, function (err) {
				ngNotify.set(err.data.data.join(' | '), 'error');
			});
		}
	}
])
.controller('CoursesCategoryNewCtrl', 
	[
	'$http', 
	'$scope',
	'$routeParams',
	'ngNotify',
	function($http, $scope, $routeParams, ngNotify) {
		$scope.saveForm = function(){
			$http.post(__env.apiUrl + 'course_category', $scope.course_category).then(function (resp) {
				ngNotify.set('A categoria foi cadastrada com sucesso!', 'success');

				setTimeout(function(){
					location.href = "/#!/courses-category"
				}, 500);
			}, function (err) {
				ngNotify.set(err.data.data.join(' | '), 'error');
			});
		}
	}
]);