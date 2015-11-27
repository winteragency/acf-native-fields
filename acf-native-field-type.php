<?php
if(!defined('ABSPATH')) exit;

class acf_field_native extends acf_field {
	function __construct() {
		$this->name = 'native_field';

		$this->label = __('Native Field', 'acf-native-fields');

		$this->defaults = array(
			'value'			=> false, // prevents acf_render_fields() from attempting to load value
		);
		
		$this->category = 'layout';
		
		$this->l10n = array(
			'not_implemented' => __('Native Field not implemented yet.', 'acf-native-field'),
		);

    	parent::__construct();
	}
	
	function render_field_settings($field) {
		acf_render_field_setting($field, array(
			'label'			=> __('Native Field', 'acf-native-field'),
			'instructions'	=> __('The native WordPress field to move into this placeholder.', 'acf-native-field'),
			'type'			=> 'select',
			'name'			=> 'native_field',
			// TODO: Implement backend and frontend functionality for custom native fields (hooks)
			'choices'		=> array(
				'content'		 => __('Content Editor', 'acf-native-field'),
				'excerpt'		 => __('Excerpt', 'acf-native-field'),
				'featured_image' => __('Featured Image', 'acf-native-field'),
				'yoast_seo'		 => __('Yoast SEO', 'acf-native-field'),
				'publish_box'	 => __('Publish Box', 'acf-native-field'),
				'permalink'	 	 => __('Permalink', 'acf-native-field'),
				'discussion'	 => __('Discussion', 'acf-native-field'),
				'trackbacks'	 => __('Trackbacks', 'acf-native-field'),
				'format'		 => __('Format', 'acf-native-field'),
				'page_attributes'=> __('Page Attributes', 'acf-native-field'),
			),
		));
	}
	
	function render_field($field) {?>
		<div class="acf-native-field" data-native-field="<?php echo $field['native_field']; ?>">
			<?php _e('Loading...', 'acf-native-field'); ?>
		</div><?php
	}
	
	function input_admin_enqueue_scripts() {
		wp_enqueue_script('acf-native-fields', plugins_url('/js/acf-native-fields.js', __FILE__), array('jquery'), ACF_Native_Fields::instance()->plugin_data['Version']);
		wp_enqueue_style('acf-native-fields', plugins_url('/css/acf-native-fields.css', __FILE__), array(), ACF_Native_Fields::instance()->plugin_data['Version']);
	}

	function field_group_admin_enqueue_scripts() {
		wp_enqueue_script('acf-native-fields-admin', plugins_url('/js/acf-native-fields-admin.js', __FILE__), array('jquery'), ACF_Native_Fields::instance()->plugin_data['Version']);
		wp_enqueue_style('acf-native-fields-admin', plugins_url('/css/acf-native-fields-admin.css', __FILE__), array(), ACF_Native_Fields::instance()->plugin_data['Version']);
	}
}