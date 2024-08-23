jQuery(document).ready( function() {

	jQuery('#hf-list-hf_file_tag-BuddyPress').hide();

	//add toggle effect
	jQuery('#hf-bp-showhide').bind('click', function(){
		jQuery('#hf-list-hf_file_tag-BuddyPress').slideToggle('fast', function() {
			jQuery("#hf-bp-showhide").text(jQuery(this).is(':visible') ? "Hide File list" : "Show File list");
		});
	}).css( 'cursor', 'pointer');		

	jQuery('#hf-list-hf_file_tag-bbPress').hide();

	//add toggle effect
	jQuery('#hf-bbp-showhide').bind('click', function(){
		jQuery('#hf-list-hf_file_tag-bbPress').slideToggle('fast', function() {
			jQuery("#hf-bbp-showhide").text(jQuery(this).is(':visible') ? "Hide File list" : "Show File list");
		});
	}).css( 'cursor', 'pointer');		

});

