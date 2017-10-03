(function() {

	/* global angular */
	/* global moment */

	angular.module('myApp')
	.service('Helpers', Helpers);

	function Helpers() {

		var searchGrid = function(str, grid){
			if(undefined === str){
				return false;
			}

			var result = [];
			var foundCount = 0;

			for(var i = 0; i < grid.length; i++){
				var found = false;
				var line = JSON.stringify(grid[i]);
				
				if (line.indexOf(str) > -1){
					found = true;
				}

				if(found){
					result[foundCount] = JSON.parse(line);
					foundCount++;
				}
			}
			
			return result;
		}

		return {
			searchGrid : searchGrid
		};
	}
})();
