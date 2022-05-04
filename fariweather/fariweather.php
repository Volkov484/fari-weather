<?php
/*
Plugin Name: Farigola Weather
Plugin URI: https://wordpress.org/plugins/health-check/
Description: Display WU Station readings
Version: 0.1.0
Author: The Health Check Team
Author URI: http://health-check-team.example.com
*/


// Exit if accessed directly
if(!defined('ABSPATH')){
    exit;
}
//Load scripts
require_once(plugin_dir_path(__FILE__).'/includes/fariweather-scripts.php');
//Load Class
require_once(plugin_dir_path(__FILE__).'/includes/fariweather-class.php');

//Register
function register_fariweather(){
    register_widget('Farigola_Weather_Widget');
}

//Hook in function
add_action('widgets_init', 'register_fariweather');