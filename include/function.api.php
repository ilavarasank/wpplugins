<?php
defined('ABSPATH') || exit;
define("LIVEURL", "https://gateway.trulioo.com/verifications/v1/verify");
define("TESTURL", "https://gateway.trulioo.com/trial/verifications/v1/verify");

function document_varify($fields)
{

    $my_settings_page = get_option('TruliooAPI_option_name');
	
	$DOB21time = strtotime('+21 years', strtotime($fields['billing_dob']));
	
 	
	if(time() < $DOB21time)  {
     wc_add_notice(__('You must be 21 years of age to proceed.'), 'error');
	 return true;  
    }
	

    if (isset($my_settings_page['apistatus']) and esc_attr($my_settings_page['apistatus']) != 'Enable') {
        return true;
    }

    if (isset($my_settings_page['apistatus']) and esc_attr($my_settings_page['apistatus']) == 'Enable') {

        if ($my_settings_page['integration'] == 'Live')
            $URL = LIVEURL;
        else
            $URL = TESTURL;


        $request_headers = array(
            "Accept: text/html",
            "x-trulioo-api-key: " . $my_settings_page['title']
        );
	
		echo "<pre>";
        var_dump($fields);
        echo "</pre>";
		
		
        $billing_dob = explode('-', $fields['billing_dob']);
        $data['AcceptTruliooTermsAndConditions'] = true;
        $data['CleansedAddress'] = false;
        $data['VerboseMode'] = true;
        //$data['ConfigurationName'] = 'Document Verification';
        $data['ConfigurationName'] = 'Identity Verification';
        $data['CountryCode'] = 'US';

        $data['DataFields']['PersonInfo']['FirstGivenName'] = $fields['billing_first_name'];
        $data['DataFields']['PersonInfo']['FirstSurName'] = $fields['billing_last_name'];
        //$data['DataFields']['PersonInfo']['MiddleName'] = "";
        $data['DataFields']['PersonInfo']['DayOfBirth'] = $billing_dob[1];
        $data['DataFields']['PersonInfo']['MonthOfBirth'] = $billing_dob[2];
        $data['DataFields']['PersonInfo']['YearOfBirth'] = $billing_dob[0];

        $data['DataFields']['Location']['StateProvinceCode'] = $fields['billing_state'];
        $data['DataFields']['Location']['PostalCode'] = $fields['billing_postcode'];
        $data['DataFields']['Location']['City'] = $fields['billing_city'];
		
		$data['DataFields']['DriverLicence']['Number'] = $fields['billing_dl'];

        if ('Document Verification' == $data['ConfigurationName']) {
            $data['DataFields']['Document']['DocumentFrontImage'] = sanitize_text_field($fields['billing_dlf']);
            $data['DataFields']['Document']['DocumentBackImage'] = sanitize_text_field($fields['billing_dlb']);
            $data['DataFields']['Document']['DocumentType'] = "DrivingLicence";
        }

        echo "<pre>";
        var_dump($data);
        echo "</pre>";
		

        $postdata = json_encode($data);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1200);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);

        $season_data = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $datajson = json_decode($season_data);

        echo "<pre>";
        print_r($datajson);
        echo "</pre>";
		
        if (isset($datajson->Record->RecordStatus) && $datajson->Record->RecordStatus == 'match')
            return true;
        else
            return false;


    }
}
