<?php
defined('ABSPATH') || exit;
add_action('woocommerce_edit_account_form', 'edit_account_form');

function edit_account_form()
{
    $user = wp_get_current_user();
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="favorite_color"><?php _e('DOB (dd/mm/yyyy)', 'woocommerce'); ?>
            <input type="date" class="woocommerce-Input woocommerce-Input--text input-text" name="account_dob"
                   id="account_dob" value="<?php echo esc_attr($user->account_dob); ?>"/>
    </p>
    
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="favorite_color"><?php _e('Drivers Licence', 'woocommerce'); ?>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_dl"
                   id="billing_dl" value="<?php echo esc_attr($user->billing_dl); ?>"/>
    </p>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide dlf-class">
        <label for="favorite_color"><?php _e('Driving Licience Front', 'woocommerce'); ?>
            <textarea name="billing_dlf" class="input-text " id="billing_dlf" placeholder="Driving Licience Font"
                      rows="2" cols="5"><?php echo esc_attr($user->billing_dlf); ?></textarea>
    </p>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide dlb-class">
        <label for="favorite_color"><?php _e('Driving Licience Back', 'woocommerce'); ?>
            <textarea name="billing_dlb" class="input-text " id="billing_dlb" placeholder="Driving Licience Back"
                      rows="2" cols="5"><?php echo esc_attr($user->billing_dlb); ?></textarea>
    </p>
    <?php
}

add_filter('woocommerce_billing_fields', 'custom_woocommerce_billing_fields');

function custom_woocommerce_billing_fields($fields)
{
    //print_r($fields);
if (!is_account_page()){
    $fields['billing_dob'] = array(
        'label' => __('DOB (dd/mm/yyyy)', 'woocommerce'), // Add custom field label
        'placeholder' => _x('Date Of Birth', 'placeholder', 'woocommerce'), // Add custom field placeholder
        'required' => true, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'date', // add field type
        'class' => array('my-css'),    // add class name
        'priority' => 22
    );
	 $fields['billing_dl'] = array(
        'label' => __('Drivers Licence', 'woocommerce'), // Add custom field label
        'placeholder' => _x('Drivers Licence', 'placeholder', 'woocommerce'), // Add custom field placeholder
        'required' => true, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'text', // add field type
        'class' => array('dl-class'),    // add class name
        'priority' => 23
    );
	
    $fields['billing_dlf'] = array(
        'label' => __('Driving Licience Front', 'woocommerce'), // Add custom field label
        'placeholder' => _x('Driving Licience Front', 'placeholder', 'woocommerce'), // Add custom field placeholder
        'required' => true, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'textarea', // add field type
        'class' => array('dlf-class'),    // add class name
        'priority' => 24
    );
    $fields['billing_dlb'] = array(
        'label' => __('Driving Licience Back', 'woocommerce'), // Add custom field label
        'placeholder' => _x('Driving Licience Back', 'placeholder', 'woocommerce'), // Add custom field placeholder
        'required' => true, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'textarea', // add field type
        'class' => array('dlb-class'),    // add class name
        'priority' => 25
    );

}
    return $fields;
}

function add_custom_script()
{
    wp_enqueue_script('Base64Conv', plugin_dir_url(__FILE__) . '../js/Base64Conv.js', array(), '1.0.0', 'true');
    wp_enqueue_script('custom_script', plugin_dir_url(__FILE__) . '../js/trulioo_custom.js', array(), '1.0.0', 'true');
    wp_enqueue_style('trulioo_custom-stylesheet', plugin_dir_url(__FILE__) . '../css/trulioo_custom.css');


}

add_action('wp_enqueue_scripts', 'add_custom_script');