<?php
// Add the custom field "favorite_color"
defined('ABSPATH') || exit;
add_action('user_profile_update_errors', 'wooc_validate_custom_field', 10, 1);
// or
add_action('woocommerce_save_account_details_errors', 'wooc_validate_custom_field', 10, 1);


// with something like:

function wooc_validate_custom_field($errors)
{

    $fields['billing_first_name'] = $_POST['account_first_name'];
    $fields['billing_last_name'] = $_POST['account_last_name'];
    $fields['billing_dob'] = $_POST['billing_dob'];
    $fields['billing_dlf'] = $_POST['billing_dlf'];
    $fields['billing_dlb'] = $_POST['billing_dlb'];

    $validate = document_varify($fields);

    if ($validate == false)
        wc_add_notice(__('Identity validation failed, please check all fields and re-try'), 'error');


}

add_action("woocommerce_after_save_address_validation", 'after_save_address_validation_custom_validation', 1, 2);

function after_save_address_validation_custom_validation($user_id, $load_address)
{
    if ($user_id <= 0) {
        return;
    }
    if ($load_address == 'billing') {
        $fields['billing_first_name'] = $_POST['billing_first_name'];
        $fields['billing_last_name'] = $_POST['billing_last_name'];
        $fields['billing_dob'] = $_POST['billing_dob'];
        $fields['billing_dlf'] = $_POST['billing_dlf'];
        $fields['billing_dlb'] = $_POST['billing_dlb'];

        $validate = document_varify($fields);
        if ($validate == false)
            wc_add_notice(__('Identity validation failed, please check all fields and re-try'), 'error');

    }
    return;
}


add_action('woocommerce_after_checkout_validation', 'validate_fname_lname', 10, 2);

function validate_fname_lname($fields, $errors)
{

    $validate = document_varify($fields);
    if ($validate == false)
        wc_add_notice(__('Identity validation failed, please check all fields and re-try'), 'error');


}


add_action( 'admin_footer', 'api_validate_test_javascript' ); // Write our JS below here

function api_validate_test_javascript() { 
wp_enqueue_style('trulioo_custom-stylesheet', plugin_dir_url(__FILE__) . '../css/trulioo_custom.css');
?>
	<script type="text/javascript" >
	jQuery('#title').keyup(function($) {
		jQuery('#checkAPIB').attr("disabled", false);
	});	
	jQuery('#checkAPIB').click(function($) {
	
		apikey=jQuery('#title').val();
		integration=jQuery('#integration').val();
		var data = {
			'action': 'my_action',
			'apikey': apikey,
			'integration':integration
		};
		jQuery('#errorAPI').html('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
		jQuery('#checkAPIB').attr("disabled", true);
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			//alert('Got this from the server: ' + response);
			jQuery('#errorAPI').html(response);
		});
	});
	</script> <?php
}


add_action( 'wp_ajax_my_action', 'api_validate_test' );

function api_validate_test() {
	
	$my_settings_page = get_option('TruliooAPI_option_name');
        
		
		$apikey = ( $_POST['apikey'] );
		$integration= ( $_POST['integration'] );
		
		if ($integration == 'Live') {
                $URL = 'https://gateway.trulioo.com/connection/v1/testauthentication';
            } else {
                $URL = 'https://gateway.trulioo.com/trial/connection/v1/testauthentication';
            }
        if (isset($apikey)) {
            $request_headers = array(
                "Accept: text/html",
                "x-trulioo-api-key: " . $apikey
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);

            $season_data = curl_exec($ch);

            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($httpcode == 200)
                $validatekey = ' <font color="#006633">Connection is successful!</font>';
            else
                $validatekey = ' <font color="#FF0000" >Connection failed, please check your API key.</font>';
            //$json= json_decode($season_data, true);
			echo $validatekey;
			}
        
	wp_die(); // this is required to terminate immediately and return a proper response
}
