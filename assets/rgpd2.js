document.querySelector(document).ready(function() {

	// Tooltips declaration
	document.querySelector('[data-hover="tooltip"]').tooltip({
		delay: {
			show: 250,
			hide: 100
		}
	});

	// Show required icons
	if (!(document.querySelector('.form-group').classList.contains('has-success'))) {
		document.querySelector('.form-group i.required').removeClass('hide');
	}
});

// Call it to check if the pattern is respected
// Apply form control class to elements (red/green)
function checkPasswordPattern(input) {

	// DOM elements
	var fg = document.querySelector(input).parent().parent();
	var ic = fg.querySelector('i.input-icon');
	var r = fg.querySelector('i.required');

	// Playing with error class
	if (input.checkValidity() == false) {
		fg.removeClass('has-success').classList.add('has-feedback has-error');
		ic.removeClass('glyphicon-ok').classList.add('glyphicon glyphicon-remove form-control-feedback');
		if (r.classList.contains('hide')) {
			r.removeClass('hide').animo({
				animation: 'fadeInRight',
				duration: 0.4,
				timing: 'ease-out'
			});
		}

		// Playing with success class
	} else {
		fg.removeClass('has-error').classList.add('has-feedback has-success');
		ic.removeClass('glyphicon-remove').classList.add('glyphicon glyphicon-ok form-control-feedback');
		if (!(r.classList.contains('hide'))) {
			r.animo({
				animation: 'fadeOutRight',
				duration: 0.3,
				timing: 'ease-in'
			}, function() {
				r.classList.add('hide');
			});
		}
	}

	// Let's play now with circles !
	var text = input.value;
	var perfect = true;

	// Length
	if (text.length < 8) {
		document.querySelector('#security-length').removeClass('green orange').classList.add('red');
		perfect = false;
	} else if (text.length >= 8 && text.length < 13) {
		document.querySelector('#security-length').removeClass('green red').classList.add('orange');
		perfect = false;
	} else
		document.querySelector('#security-length').removeClass('orange red').classList.add('green');

	// Lowercase
	if (!(new RegExp('[a-z]').test(text))) {
		document.querySelector('#security-lowercase').removeClass('green orange').classList.add('red');
		perfect = false;
	} else
		document.querySelector('#security-lowercase').removeClass('red orange').classList.add('green');

	// Uppercase
	if (!(new RegExp('[A-Z]').test(text))) {
		document.querySelector('#security-uppercase').removeClass('green orange').classList.add('red');
		perfect = false;
	} else
		document.querySelector('#security-uppercase').removeClass('red orange').classList.add('green');

	// Digit
	if (!(new RegExp('[0-9]').test(text))) {
		document.querySelector('#security-digit').removeClass('green orange').classList.add('red');
		perfect = false;
	} else
		document.querySelector('#security-digit').removeClass('red orange').classList.add('green');

	// Symbol
	if (!(new RegExp(/[@+$-/:-?{-~!^_`]/).test(text))) {
		document.querySelector('#security-symbol').removeClass('green orange').classList.add('red');
		perfect = false;
	} else
		document.querySelector('#security-symbol').removeClass('red orange').classList.add('green');

	// Cookies animations
	var cookies = document.querySelector('#security-cookies');
	if (perfect && cookies.classList.contains('hide')) {
		cookies.removeClass('hide').animo({
			animation: 'fadeInLeft',
			duration: 0.4,
			timing: 'ease-out'
		});
	} else if (perfect == false && !(cookies.classList.contains('hide'))) {
		cookies.animo({
			animation: 'fadeOutLeft',
			duration: 0.3,
			timing: 'ease-in'
		}, function() {
			cookies.classList.add('hide');
		});
	}
}