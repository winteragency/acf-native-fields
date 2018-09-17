<?php
/*
Plugin Name: ACF Native Fields
Plugin URI: https://github.com/winteragency/acf-native-fields
Description: An interface to move native WordPress fields and options into ACF for a cleaner editor layout
Version: 1.1.0
Author: Winter
Author URI: https://winteragency.se
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.en.html
*/

if(!defined('ABSPATH')) exit;

class ACF_Native_Fields {
	static $instance = false; 
	
	public $plugin_data = null;
	
	function __construct() {
		// Init plugin (check requirements etc)
		add_action('admin_init', array($this, 'admin_init'));
		
		// Add native field type to ACF
		add_action('acf/include_field_types', array($this, 'include_native_field_type'));
	}
	
	public function admin_init() {
		load_plugin_textdomain('acf-native-fields', false, dirname(plugin_basename(__FILE__)) . '/lang/'); 
		
		$this->plugin_data = get_plugin_data(dirname(__FILE__));
		
		// Require ACF
		if (current_user_can('activate_plugins') && !is_plugin_active('advanced-custom-fields/acf.php') && !is_plugin_active('advanced-custom-fields-pro/acf.php')) {
			add_action('admin_notices', array($this, 'require_acf'));
			deactivate_plugins(plugin_basename( __FILE__ )); 

			if(isset($_GET['activate'])) {
				unset($_GET['activate']);
			}
		}
	}
	
	public function require_acf() { ?>
		<div class="error"><p><?php _e('ACF Native Fields requires Advanced Custom Fields v5+ to be installed and activated.', 'acf-native-fields'); ?></p></div><?php
	}
	
	public function include_native_field_type() {
		require(dirname(__FILE__) . '/acf-native-field-type.php');
		
		new acf_field_native();
	}
	
	 static function &instance() { 
		if(false === self::$instance) { 
			self::$instance = new ACF_Native_Fields(); 
		}
		
		return self::$instance; 
	} 
}

new ACF_Native_Fields();