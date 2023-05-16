<?php
/**
 * ACF Native Fields by Winter ❄
 *
 * @link              https://github.com/winteragency/wntr-acf-flexible-content-preview
 * @since             1.0.0
 * @package           ACF_Native_Fields
 *
 * x-release-please-start-version
 *
 * @wordpress-plugin
 * Plugin Name:       ACF Native Fields
 * Plugin URI:        https://github.com/winteragency/acf-native-fields
 * Description:       An interface to move native WordPress fields and options into ACF for a cleaner editor layout
 * Version:           1.2.1
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Winter Agency
 * Author URI:        http://winteragency.se
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       acf-native-fields
 * Domain Path:       /languages
 *
 * x-release-please-end
 */

if (!defined('ABSPATH')) {
  exit();
}

class ACF_Native_Fields {
  static $instance = false;
  public $version = '1.2.1'; // x-release-please-version

  function __construct() {
    // Init plugin (check requirements etc)
    add_action('admin_init', [$this, 'admin_init']);

    // Add native field type to ACF
    add_action('acf/include_field_types', [$this, 'include_native_field_type']);
  }

  public function admin_init() {
    load_plugin_textdomain(
      'acf-native-fields',
      false,
      dirname(plugin_basename(__FILE__)) . '/lang/',
    );

    // Require ACF
    if (
      current_user_can('activate_plugins') &&
      !is_plugin_active('advanced-custom-fields/acf.php') &&
      !is_plugin_active('advanced-custom-fields-pro/acf.php')
    ) {
      add_action('admin_notices', [$this, 'require_acf']);
      deactivate_plugins(plugin_basename(__FILE__));

      if (isset($_GET['activate'])) {
        unset($_GET['activate']);
      }
    }
  }

  public function require_acf() {
    ?>
		<div class="error"><p><?php _e(
    'ACF Native Fields requires Advanced Custom Fields v5+ to be installed and activated.',
    'acf-native-fields',
  ); ?></p></div><?php
  }

  public function include_native_field_type() {
    require dirname(__FILE__) . '/acf-native-field-type.php';

    new acf_field_native();
  }

  static function &instance() {
    if (false === self::$instance) {
      self::$instance = new ACF_Native_Fields();
    }

    return self::$instance;
  }
}

new ACF_Native_Fields();
