<?php

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
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
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
            array($this, 'create_admin_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option('TruliooAPI_option_name');
        ?>
        <div class="wrap">
            <h1>Manage TruliooAPI</h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('my_option_group');
                do_settings_sections('my-setting-admin');
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
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'TruliooAPI Settings', // Title
            array($this, 'print_section_info'), // Callback
            'my-setting-admin' // Page
        );


        add_settings_field(
            'apistatus',
            'Status',
            array($this, 'apistatus_callback'),
            'my-setting-admin',
            'setting_section_id'
        );
        add_settings_field(
            'integration',
            'Integration Mode',
            array($this, 'integration_callback'),
            'my-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'title',
            'API Key',
            array($this, 'title_callback'),
            'my-setting-admin',
            'setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $new_input = array();
        if (isset($input['integration']))
            $new_input['integration'] = sanitize_text_field($input['integration']);

        if (isset($input['title']))
            $new_input['title'] = sanitize_text_field($input['title']);


        if (isset($input['apistatus']))
            $new_input['apistatus'] = sanitize_text_field($input['apistatus']);

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

        if (isset($this->options['integration']) and esc_attr($this->options['integration']) == 'Live') {
            $live = 'selected';
        } else {
            $test = 'selected';
        }

        echo '<select id="integration" name="TruliooAPI_option_name[integration]"><option value="Live" ' . $live . '>Live</option><option value="Test" ' . $test . '>Test</option></select>';

    }

    public function apistatus_callback()
    {

        if (isset($this->options['apistatus']) and esc_attr($this->options['apistatus']) == 'Enable') {
            $enable = 'selected';
        } else {
            $disable = 'selected';
        }
        echo '<select id="apistatus" name="TruliooAPI_option_name[apistatus]"><option value="Enable" ' . $enable . '>Enable</option><option value="Disable" ' . $disable . '>Disable</option></select>';

    }

    /**
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        

        printf(
            '<input type="text" id="title" name="TruliooAPI_option_name[title]" value="%s" />',
            isset($this->options['title']) ? esc_attr($this->options['title']) : ''
        );
		echo '&nbsp;<input type="button" id="checkAPIB" value="Test Connectivity" disabled="disabled" class="button button-primary">';
		echo '<div id="errorAPI"></div>';
    }
}