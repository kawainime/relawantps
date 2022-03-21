/**
* Written by: Agus Prawoto Hadi
* Year		: 2021
* Website	: jagowebdev.com
*/

jQuery(document).ready(function () {
	$('.options-lainnya').click(function() {
		if ($(this).is(':checked')) {
			$(this).parent().next().show();
		} else {
			$(this).parent().next().hide();
		}
	});
	
	$('.select-options-lainnya').change(function() {
		var classes = $(this).find(':selected').attr('class');
		if (classes == 'options-lainnya') {
			$(this).next().show();
		} else {
			$(this).next().hide();
		}
	});
	
	$('.numberonly').keyup(function(){
		this.value = this.value.replace(/\D/gi, '');
	})
	
	$('.maxlength').keyup(function(){
		maxlength = parseInt($(this).attr('data-maxlength'));
		if (this.value.length > maxlength) {
			this.value = this.value.substring(0, maxlength);
		}
	});
});