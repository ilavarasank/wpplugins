<?php
/**
* Plugin Name: Trulioo US Verification
* Plugin URI: #
* Description: This is Custom Plugins Develop for Trulioo API .
* Version: 1.0
* Author: Purnendu Chakraborty
* Author URI: #
**/

/** Step 2 (from text above). */
class MySettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Manage TruliooAPI', 
            'manage_options', 
            'manage-TruliooAPI-setting', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'TruliooAPI_option_name' );
        ?>
        <div class="wrap">
            <h1>Manage TruliooAPI</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'my-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'TruliooAPI_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'TruliooAPI Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );  

            

        add_settings_field(
            'title', 
            'API Key', 
            array( $this, 'title_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );   
		add_settings_field(
            'integration', 
            'Integration Mode', 
            array( $this, 'integration_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );  
		add_settings_field(
            'apistatus', 
            'Status', 
            array( $this, 'apistatus_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );     
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['integration'] ) )
            $new_input['integration'] = sanitize_text_field( $input['integration'] );

        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );
			
			
		if( isset( $input['apistatus'] ) )
            $new_input['apistatus'] = sanitize_text_field( $input['apistatus'] );	

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
   public function integration_callback()
    {
		
		if (isset($this->options['integration']) and esc_attr( $this->options['integration'])=='Live'){
		$live='selected';
		}else{
		$test='selected';	
		}
		
        echo '<select id="integration" name="TruliooAPI_option_name[integration]"><option value="Live" '.$live.'>Live</option><option value="Test" '.$test.'>Test</option></select>';
        
    }
	
	public function apistatus_callback()
    {
		
		if (isset($this->options['apistatus']) and esc_attr( $this->options['apistatus'])=='Enable'){
		$enable='selected';
		}else{
		$disable='selected';	
		}
        echo '<select id="apistatus" name="TruliooAPI_option_name[apistatus]"><option value="Enable" '.$enable.'>Enable</option><option value="Disable" '.$disable.'>Disable</option></select>';
        
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
	if (isset($this->options['integration']))
	if($this->options['integration']=='Live'){
	$URL='https://gateway.trulioo.com/connection/v1/testauthentication';
	}else{
	$URL='https://gateway.trulioo.com/trial/connection/v1/testauthentication';	
	}
	
	if (isset($this->options['title'])){	
	$request_headers = array(
                    "Accept: text/html",
                    "x-trulioo-api-key: ".$this->options['title']
                );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $URL);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);

    $season_data = curl_exec($ch);

    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
	
	if ($httpcode==200)
	$validatekey= ' <font color="#006633" size="6px">&#10003;</font>';
	else
	$validatekey= ' <font color="#FF0000" size="6px">&#10060;</font>';
    //$json= json_decode($season_data, true);
	
	}
		
        printf(
            '<input type="text" id="title" name="TruliooAPI_option_name[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
		echo $validatekey;
    }
}



if( is_admin() )
    $my_settings_page = new MySettingsPage();

add_action( 'woocommerce_after_checkout_validation', 'validate_fname_lname', 10, 2);
 
function validate_fname_lname( $fields, $errors ){
	
		$my_settings_page = get_option( 'TruliooAPI_option_name' );
		
	if (isset($my_settings_page['apistatus']) and esc_attr( $my_settings_page['apistatus'])=='Enable'){
 	
	if($my_settings_page['integration']=='Live'){
	$URL='https://gateway.trulioo.com/verifications/v1/verify';
	}else{
	$URL='https://gateway.trulioo.com/trial/verifications/v1/verify';	
	}
	
	$request_headers = array(
                    "Accept: text/html",
                    "x-trulioo-api-key: ".$my_settings_page['title']
                );


	$billing_dob=explode('-',$fields[ 'billing_dob' ]);
	$data['AcceptTruliooTermsAndConditions'] = true;
	$data['CountryCode'] = 'US';
	$data['DataFields']['PersonInfo']['FirstGivenName'] = $fields[ 'billing_first_name' ];
	$data['DataFields']['PersonInfo']['FirstSurName'] = $fields[ 'billing_last_name' ];
	$data['DataFields']['PersonInfo']['MiddleName'] = "";
	$data['DataFields']['PersonInfo']['DayOfBirth'] = $billing_dob[2];
	$data['DataFields']['PersonInfo']['MonthOfBirth'] = $billing_dob[1];
	$data['DataFields']['PersonInfo']['YearOfBirth'] = $billing_dob[0];
	
	$postdata = json_encode($data);
	
	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $URL);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);

    $season_data = curl_exec($ch);

    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
	
	$datajson=json_decode($season_data);
	
	//print_r($datajson->Record->DatasourceResults[1]->DatasourceFields );
	
	for ($i=0;$i<count($datajson->Record->DatasourceResults[1]->DatasourceFields);$i++ ){
	$dataReturn[$datajson->Record->DatasourceResults[1]->DatasourceFields[$i]->FieldName]=$datajson->Record->DatasourceResults[1]->DatasourceFields[$i]->Status;	
	
	}
	
	
			if ($dataReturn['FirstGivenName']!='match')
			$errors->add( 'validation', 'Your First Name is not a valid US name' );
		else
			$errors->add( 'validation', 'Your First Name is verified' );
			if ($dataReturn['FirstSurName']!='match')
			$errors->add( 'validation', 'Your Last Name is not a valid US name' );
		else
			$errors->add( 'validation', 'Your Last Name is verified' );
		
		}
}


add_filter('woocommerce_billing_fields', 'custom_woocommerce_billing_fields');

function custom_woocommerce_billing_fields($fields)
{

    $fields['billing_dob'] = array(
        'label' => __('DOB (dd/mm/yyyy)', 'woocommerce'), // Add custom field label
        'placeholder' => _x('Date Of Birth', 'placeholder', 'woocommerce'), // Add custom field placeholder
        'required' => true, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'date', // add field type
        'class' => array('my-css')    // add class name
    );

    return $fields;
}
