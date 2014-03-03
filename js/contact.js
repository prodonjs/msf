/**
 * jQuery document ready function
 */
$(document).ready(function() {
	// Make a generic arithmetic question for the user to answer
	var rand1 = Math.floor(Math.random()*11 + 1);
	var rand2 = Math.floor(Math.random()*11 + 1);
	var challengeAnswer = rand1 + rand2;
	$('#lblchallenge').html('What is the sum of ' + rand1 + ' and ' + rand2 + '?');
	
	// Add validation method that will check to see if the challenge field is equal to challenge answer
	$.validator.addMethod("challengecheck", function(value, element) { 
		return value == challengeAnswer;
	}, 'Your answer is not correct, need a calculator?');
	// Add validation method that will check for valid phone numbers
	$.validator.addMethod("phone", function(phone_number, element) {
	    phone_number = phone_number.replace(/\s+/g, ""); 
		return this.optional(element) || phone_number.length > 9 &&
			phone_number.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
	}, "Please specify a valid phone number");
	
	$('#form').validate({
		success : function(label) {
			label.text('OK!').addClass('valid');
		},
		invalidHandler: function(form) {
			$('#validationfail').show();
		}
	});		
});

