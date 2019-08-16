<?php
/**
 * Plugin Name: Trulioo Integration
 * Plugin URI: #
 * Description: Integration with Trulioo API (US Identity verification).
 * Version: 1.0
 * Author: TechNWeb, Inc. dba PowerSync
 * Author URI: #
 **/

include("include/class.settings.php");
include("include/function.api.php");


if (is_admin()) {
    $my_settings_page = new MySettingsPage();
}

include("include/create.fields.php");

include("include/validate.fields.php");

include("include/save.fields.php");
