jQuery.noConflict();
jQuery(document).ready(function(){
	var thi_val;

	jQuery('.showstuff').hide();	
	jQuery('#myList').change(function () {
	
	thi_val = jQuery(this).val();
	givebackstuff(thi_val);
	
	});
	
});
function givebackstuff(thi_val){
var jaxrel = fromphp.jaxfile;
	jQuery("body").css("cursor", "progress");
	var valset = {
				action: 'give_backstuff',
				dataVal: thi_val
			};
		jQuery.post(jaxrel, valset,
		function(data) {
			jQuery("body").css("cursor", "default");
			jQuery('.showstuff').hide(300);

			 jQuery('.showstuff').html(data.sucs);

			 jQuery('.showstuff').show(300);
			}, 
			"json"
		);
}