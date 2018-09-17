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
			'not_implemented' => __('Native Field not implemented yet.', 'acf-native-fields'),
		);

    	parent::__construct();
	}
	
	function render_field_settings($field) {
		acf_render_field_setting($field, array(
			'label'			=> __('Native Field', 'acf-native-fields'),
			'instructions'	=> __('The native WordPress field to move into this placeholder.', 'acf-native-fields'),
			'type'			=> 'select',
			'name'			=> 'native_field',
			'required'		=> 1,
			// TODO: Implement backend and frontend functionality for custom native fields (hooks)
			'choices'		=> array(
				'content'		  => __('Content Editor', 'acf-native-fields'),
				'excerpt'		  => __('Excerpt', 'acf-native-fields'),
				'featured_image'  => __('Featured Image', 'acf-native-fields'),
				'yoast_seo'		  => __('SEO (Yoast or SEO framework)', 'acf-native-fields'),
				'publish_box'	  => __('Publish Box', 'acf-native-fields'),
				'permalink'	 	  => __('Permalink', 'acf-native-fields'),
				'discussion'	  => __('Discussion', 'acf-native-fields'),
				'trackbacks'	  => __('Trackbacks', 'acf-native-fields'),
				'format'		  => __('Format', 'acf-native-fields'),
				'page_attributes' => __('Page Attributes', 'acf-native-fields'),
				'custom'		  => __('Custom', 'acf-native-fields'),
			),
		));

		acf_render_field_setting($field, array(
			'label'				=> __('Custom Meta Box ID', 'acf-native-fields'),
			'instructions'		=> __('The ID of the custom metabox to target.', 'acf-native-fields'),
			'type'				=> 'text',
			'name'				=> 'metabox_id',
			'prefix'			=> '#',
			'conditional_logic' => array(
				array(
					array(
						'field' => 'native_field',
						'operator' => '==',
						'value' => 'custom',
					),
				),
			),
		));
	}
	
	function render_field($field) {?>
		<div class="acf-native-field" data-native-field="<?php echo esc_attr($field['native_field']); ?>"<?php echo (!empty($field['metabox_id']) ? ' data-metabox-id="' . esc_attr($field['metabox_id']) . '"' : ''); ?>>
			<?php _e('Loading...', 'acf-native-fields'); ?>
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