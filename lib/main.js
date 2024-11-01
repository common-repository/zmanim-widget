jQuery(document).ready(function() {

jQuery('a.hide').click(function() {
	var id = jQuery(this).attr('rel');
	jQuery('a.'+id).toggle();
	jQuery('ul#'+id).toggle();
    //jQuery(this).parent().parent().parent('ul').toggle();
	return false;
});

jQuery('a.show_hide_all').click(function() {
	var id = jQuery(this).attr('id');
        jQuery('a.show_hide_all').toggle();
	if (id == 'zman_showall')
	        jQuery('ul.zmanim-inner-section ul').show();
	else
		jQuery('ul.zmanim-inner-section ul').hide();
        return false;
});


});

