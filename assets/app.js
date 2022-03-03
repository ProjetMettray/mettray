/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

$(document).ready(function() {

	// Tooltips declaration
	$('[data-hover="tooltip"]').tooltip({
		delay: {
			show: 250,
			hide: 100
		}
	});

	// Show required icons
	if (!($('.form-group').hasClass('has-success'))) {
		$('.form-group i.required').removeClass('hide');
	}
});

// Call it to check if the pattern is respected
// Apply form control class to elements (red/green)
function checkPasswordPattern(input) {

	// DOM elements
	var fg = $(input).parent().parent();
	var ic = fg.find('i.input-icon');
	var r = fg.find('i.required');

	// Playing with error class
	if (input.checkValidity() == false) {
		fg.removeClass('has-success').addClass('has-feedback has-error');
		ic.removeClass('glyphicon-ok').addClass('glyphicon glyphicon-remove form-control-feedback');
		if (r.hasClass('hide')) {
			r.removeClass('hide').animo({
				animation: 'fadeInRight',
				duration: 0.4,
				timing: 'ease-out'
			});
		}

		// Playing with success class
	} else {
		fg.removeClass('has-error').addClass('has-feedback has-success');
		ic.removeClass('glyphicon-remove').addClass('glyphicon glyphicon-ok form-control-feedback');
		if (!(r.hasClass('hide'))) {
			r.animo({
				animation: 'fadeOutRight',
				duration: 0.3,
				timing: 'ease-in'
			}, function() {
				r.addClass('hide');
			});
		}
	}

	// Let's play now with circles !
	var text = input.value;
	var perfect = true;

	// Length
	if (text.length < 8) {
		$('#security-length').removeClass('green orange').addClass('red');
		perfect = false;
	} else if (text.length >= 8 && text.length < 13) {
		$('#security-length').removeClass('green red').addClass('orange');
		perfect = false;
	} else
		$('#security-length').removeClass('orange red').addClass('green');

	// Lowercase
	if (!(new RegExp('[a-z]').test(text))) {
		$('#security-lowercase').removeClass('green orange').addClass('red');
		perfect = false;
	} else
		$('#security-lowercase').removeClass('red orange').addClass('green');

	// Uppercase
	if (!(new RegExp('[A-Z]').test(text))) {
		$('#security-uppercase').removeClass('green orange').addClass('red');
		perfect = false;
	} else
		$('#security-uppercase').removeClass('red orange').addClass('green');

	// Digit
	if (!(new RegExp('[0-9]').test(text))) {
		$('#security-digit').removeClass('green orange').addClass('red');
		perfect = false;
	} else
		$('#security-digit').removeClass('red orange').addClass('green');

	// Symbol
	if (!(new RegExp(/[@+$-/:-?{-~!^_`]/).test(text))) {
		$('#security-symbol').removeClass('green orange').addClass('red');
		perfect = false;
	} else
		$('#security-symbol').removeClass('red orange').addClass('green');

	// Cookies animations
	var cookies = $('#security-cookies');
	if (perfect && cookies.hasClass('hide')) {
		cookies.removeClass('hide').animo({
			animation: 'fadeInLeft',
			duration: 0.4,
			timing: 'ease-out'
		});
	} else if (perfect == false && !(cookies.hasClass('hide'))) {
		cookies.animo({
			animation: 'fadeOutLeft',
			duration: 0.3,
			timing: 'ease-in'
		}, function() {
			cookies.addClass('hide');
		});
	}
}