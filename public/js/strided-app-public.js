(function( $ ) {
	'use strict';

	$(document).ready( function() {

		function getParameterByName(name, url) {
			if (!url) url = window.location.href;
			name = name.replace(/[\[\]]/g, "\\$&");
			var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
			results = regex.exec(url);
			if (!results) return null;
			if (!results[2]) return '';
			return decodeURIComponent(results[2].replace(/\+/g, " "));
		}

		var horse = getParameterByName('horse-name');
		var arena = getParameterByName('arena-name');
		var horseGender = getParameterByName('gender');
		var order = getParameterByName('order-by');
		var date = getParameterByName('date');

		setTimeout(function(){
			if (arena) {
				$(".arena-select").val(arena);
			}
			if (horse) {
				$(".horse-select").val(horse);
			}
			if (horseGender) {
				$(".gender-select").val(horseGender);
			}
			if (order) {
				$(".order-by-select").val(order);
			}
			if (date) {
				$(".datepicker").val(date);
			}
		}, 1000);

	})

})( jQuery );
