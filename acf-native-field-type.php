<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class acf_field_native extends acf_field {
	function __construct() {
		$this->name = 'native_field';

		$this->label = __( 'Native Field', 'acf-native-fields' );

		$this->defaults = array(
			'value' => false, // prevents acf_render_fields() from attempting to load value
		);

		$this->category = 'layout';

		$this->l10n = array(
			'not_implemented' => __( 'Native Field not implemented yet.', 'acf-native-fields' ),
		);

		parent::__construct();
	}

	function render_field_settings( $field ) {
		$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		$is_active      = function ( $plugin ) use ( $active_plugins ) {
			return in_array( $plugin, $active_plugins, true );
		};

		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Native Field', 'acf-native-fields' ),
				'instructions' => __( 'The native WordPress field to move into this placeholder.', 'acf-native-fields' ),
				'type'         => 'select',
				'name'         => 'native_field',
				'required'     => 1,
				// TODO: Implement backend and frontend functionality for custom native fields (hooks)
				'choices'      => array_filter(
					array(
						'Core'        => array(
							'content'         => __( 'Content Editor', 'acf-native-fields' ),
							'excerpt'         => __( 'Excerpt', 'acf-native-fields' ),
							'publish_box'     => __( 'Publish Box', 'acf-native-fields' ),
							'page_attributes' => __( 'Page Attributes', 'acf-native-fields' ),
							'featured_image'  => __( 'Featured Image', 'acf-native-fields' ),

							'permalink'       => __( 'Permalink', 'acf-native-fields' ),
							'author'          => __( 'Author', 'acf-native-fields' ),

							'categories'      => __( 'Categories', 'acf-native-fields' ),
							'tags'            => __( 'Tags', 'acf-native-fields' ),
							'format'          => __( 'Format', 'acf-native-fields' ),

							'revisions'       => __( 'Revisions', 'acf-native-fields' ),
							'comments'        => __( 'Comments', 'acf-native-fields' ),
							'discussion'      => __( 'Discussion', 'acf-native-fields' ),
							'trackbacks'      => __( 'Trackbacks', 'acf-native-fields' ),
						),

						'WooCommerce' => $is_active( 'woocommerce/woocommerce.php' ) ? array(
							'all'                       => __( 'All', 'acf-native-fields' ),
							'product_categories'        => __( 'Product Categories', 'acf-native-fields' ),
							'product_tags'              => __( 'Product Tags', 'acf-native-fields' ),
							'product_image'             => __( 'Product Image', 'acf-native-fields' ),
							'product_gallery'           => __( 'Product Gallery', 'acf-native-fields' ),
							'product_data'              => __( 'Product Data', 'acf-native-fields' ),
							'product_short_description' => __( 'Product Short Description', 'acf-native-fields' ),
							'reviews'                   => __( 'Reviews', 'acf-native-fields' ),
						) : false,

						'Plugins'     => array_filter(
							array(
								'yoast_seo'      => $is_active( 'wordpress-seo/wp-seo.php' ) ? __( 'Yoast SEO', 'acf-native-fields' ) : false,
								'seo_framework'  => $is_active( 'autodescription/autodescription.php' ) ? __( 'SEO Framework', 'acf-native-fields' ) : false,
								'classic_editor' => $is_active( 'classic-editor/classic-editor.php' ) ? __( 'Classic Editor (Editor Switcher)', 'acf-native-fields' ) : false,
								'laygridder'     => $is_active( 'laygridder/laygridder.php' ) ? __( 'LayGridder', 'acf-native-fields' ) : false,
							)
						),

						'Other'       => array(
							'custom' => __( 'Custom', 'acf-native-fields' ),
						),
					)
				),
			)
		);

		acf_render_field_setting(
			$field,
			array(
				'label'             => __( 'Custom Meta Box', 'acf-native-fields' ),
				'instructions'      => __( 'A valid CSS selector to target your desired meta box.', 'acf-native-fields' ),
				'type'              => 'text',
				'name'              => 'metabox_id',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'native_field',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
				),
			)
		);
	}

	function render_field( $field ) {?>
		<div class="acf-native-field" data-native-field="<?php echo esc_attr( $field['native_field'] ); ?>"<?php echo ( ! empty( $field['metabox_id'] ) ? ' data-metabox-id="' . esc_attr( $field['metabox_id'] ) . '"' : '' ); ?>>
			<?php esc_html_e( 'Loading…', 'acf-native-fields' ); ?>
		</div>
		<?php
	}

	function input_admin_enqueue_scripts() {
		$plugin_version = ACF_Native_Fields::instance()->plugin_data['Version'];

		wp_enqueue_script( 'acf-native-fields', plugins_url( '/js/acf-native-fields.js', __FILE__ ), array( 'jquery' ), $plugin_version, false );
		wp_enqueue_style( 'acf-native-fields', plugins_url( '/css/acf-native-fields.css', __FILE__ ), array(), $plugin_version );
	}

	function field_group_admin_enqueue_scripts() {
		$plugin_version = ACF_Native_Fields::instance()->plugin_data['Version'];

		wp_enqueue_script( 'acf-native-fields-admin', plugins_url( '/js/acf-native-fields-admin.js', __FILE__ ), array( 'jquery' ), $plugin_version, false );
		wp_enqueue_style( 'acf-native-fields-admin', plugins_url( '/css/acf-native-fields-admin.css', __FILE__ ), array(), $plugin_version );
	}
}
