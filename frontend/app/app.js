'use strict';

// Declare app level module which depends on views, and components
angular.module('myApp', [
	'ngRoute',
	'myApp.dashboard',
	'myApp.users',
	'myApp.courses',
	'myApp.registrations',
	'myApp.version',
	'ngMinimalGrid',
	'ui.dateTimeInput',
	'ngNotify',
	'ngSanitize',
	'ui.select'
]).
config([
	'$locationProvider',
	'$routeProvider', 
	'minimalGridConfigProvider',
	function(
		$locationProvider,
		$routeProvider,
		minimalGridConfigProvider
	) {
		moment.locale('pt-br');

		$locationProvider.hashPrefix('!');

		$routeProvider.otherwise({redirectTo: '/dashboard'});

		// Default environment variables
		var __env = {};

		// Import variables if present
		if(window){
			Object.assign(__env, window.__env);
		}

		var ngModule = angular.module('app', []);
		ngModule.constant('__env', __env);

		minimalGridConfigProvider.setStatsMessage('Mostrando %1 à %2 de %3 resultados')
		minimalGridConfigProvider.setFirstLabel('Primeiro')
		minimalGridConfigProvider.setLastLabel('Último')
}]);
