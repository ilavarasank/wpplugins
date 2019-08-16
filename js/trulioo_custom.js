jQuery(document).ready(function(){
		//alert("Hi");
		if (jQuery( "#billing_dlf" ).text()!=''){
		jQuery('.dlf-class').append('<img src="data:image/png;base64,'+jQuery( "#billing_dlf" ).text()+'" width="50px;">');	
		}
        jQuery('.dlf-class').append('<input type="file" id="billing_dlfFile">');
		
		if (jQuery( "#billing_dlb" ).text()!=''){
		jQuery('.dlb-class').append('<img src="data:image/png;base64,'+jQuery( "#billing_dlb" ).text()+'" width="50px;">');	
		}
		jQuery('.dlb-class').append('<input type="file" id="billing_dlbFile">');
		jQuery("#billing_dlfFile").base64({
	 "onSuccess":function(inst,base64Str){
        jQuery( "#billing_dlf" ).text( base64Str );}});
		
		jQuery("#billing_dlbFile").base64({
	 "onSuccess":function(inst,base64Str){
        jQuery( "#billing_dlb" ).text( base64Str );}});
		//jQuery('#billing_dlf').hide();
    
	jQuery('input[type=date]').datepicker({dateFormat: 'yy-mm-dd'});

	});
	
		