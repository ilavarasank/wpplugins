<?php
defined('ABSPATH') || exit;
add_action('woocommerce_save_account_details', 'save_favorite_color_account_details', 12, 1);

function save_favorite_color_account_details($user_id)
{


    if (isset($_POST['billing_dob']))
        update_user_meta($user_id, 'billing_dob', sanitize_text_field($_POST['billing_dob']));

	if (isset($_POST['billing_dl']))
        update_user_meta($user_id, 'billing_dl', sanitize_text_field($_POST['billing_dl']));


    if (isset($_POST['billing_dlf']))
        update_user_meta($user_id, 'billing_dlf', sanitize_text_field($_POST['billing_dlf']));

    if (isset($_POST['billing_dlb']))
        update_user_meta($user_id, 'billing_dlb', sanitize_text_field($_POST['billing_dlb']));
}