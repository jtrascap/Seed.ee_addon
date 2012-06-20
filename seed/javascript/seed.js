$(function(){

	$('#seed_channel').change( function() {

		var channel_id = $(this).attr('value');

		$('.seed_fields_channel').hide();
		$('#seed_fields_channel_'+channel_id).show();

	});

	$('.optional_field_populate_option').change( function() {

		var rel = $(this).attr('rel');
		var value = $(this).attr('value');

		if( value == 'empty' ) $( '#' + rel ).hide();
		else $( '#' + rel ).show();


	});

});