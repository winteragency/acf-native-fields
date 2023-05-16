<?php
if (!defined('ABSPATH')) {
  exit();
}

class acf_field_native extends acf_field {
  function __construct() {
    $this->name = 'native_field';

    $this->label = __('Native Field', 'acf-native-fields');

    $this->defaults = [
      'value' => false, // prevents acf_render_fields() from attempting to load value
    ];

    $this->category = 'layout';

    $this->l10n = [
      'not_implemented' => __(
        'Native Field not implemented yet.',
        'acf-native-fields',
      ),
    ];

    parent::__construct();
  }

  function render_field_settings($field) {
    acf_render_field_setting($field, [
      'label' => __('Native Field', 'acf-native-fields'),
      'instructions' => __(
        'The native WordPress field to move into this placeholder.',
        'acf-native-fields',
      ),
      'type' => 'select',
      'name' => 'native_field',
      'required' => 1,
      // TODO: Implement backend and frontend functionality for custom native fields (hooks)
      'choices' => [
        'content' => __('Content Editor', 'acf-native-fields'),
        'excerpt' => __('Excerpt', 'acf-native-fields'),
        'featured_image' => __('Featured Image', 'acf-native-fields'),
        'yoast_seo' => __('SEO (Yoast or SEO framework)', 'acf-native-fields'),
        'publish_box' => __('Publish Box', 'acf-native-fields'),
        'permalink' => __('Permalink', 'acf-native-fields'),
        'discussion' => __('Discussion', 'acf-native-fields'),
        'trackbacks' => __('Trackbacks', 'acf-native-fields'),
        'format' => __('Format', 'acf-native-fields'),
        'page_attributes' => __('Page Attributes', 'acf-native-fields'),
        'custom' => __('Custom', 'acf-native-fields'),
      ],
    ]);

    acf_render_field_setting($field, [
      'label' => __('Custom Meta Box ID', 'acf-native-fields'),
      'instructions' => __(
        'The ID of the custom metabox to target.',
        'acf-native-fields',
      ),
      'type' => 'text',
      'name' => 'metabox_id',
      'prefix' => '#',
      'conditional_logic' => [
        [
          [
            'field' => 'native_field',
            'operator' => '==',
            'value' => 'custom',
          ],
        ],
      ],
    ]);
  }

  function render_field($field) {
    ?>
		<div class="acf-native-field" data-native-field="<?php echo esc_attr(
    $field['native_field'],
  ); ?>"<?php echo !empty($field['metabox_id']) ? ' data-metabox-id="' . esc_attr($field['metabox_id']) . '"' : ''; ?>><?php _e('Loading...', 'acf-native-fields'); ?>
		</div><?php // Render a hidden input so that validation is triggered for this field if it's required

    if ($field['required']) {
    acf_hidden_input([
      'name' => $field['name'],
      'value' => 1,
    ]);
  }
  }

  /**
   * Validate the target field.
   * If the native field is set to be required, check that the target field has a value.
   *
   * @param bool   $valid Whether the value is valid.
   * @param mixed  $value The field value.
   * @param array  $field The field array.
   * @param string $input The request variable name for the inbound field.
   *
   * @return bool|string
   *
   * @since 1.2.0
   */
  public function validate_value($valid, $value, $field, $input) {
    if ($field['required']) {
      $message = sprintf(
        __('%s is required', 'acf-native-fields'),
        $field['label'],
      );

      switch ($field['native_field']) {
        case 'content':
          if (empty($_POST['content'])) {
            return $message;
          }

          break;
        case 'excerpt':
          if (empty($_POST['excerpt'])) {
            return $message;
          }

          break;
        case 'featured_image':
          if (
            empty($_POST['_thumbnail_id']) ||
            intval($_POST['_thumbnail_id']) === -1
          ) {
            return $message;
          }

          break;
      }
    }

    return $valid;
  }

  function input_admin_enqueue_scripts() {
    wp_enqueue_script(
      'acf-native-fields',
      plugins_url('/js/acf-native-fields.js', __FILE__),
      ['jquery'],
      ACF_Native_Fields::instance()->version,
    );
    wp_enqueue_style(
      'acf-native-fields',
      plugins_url('/css/acf-native-fields.css', __FILE__),
      [],
      ACF_Native_Fields::instance()->version,
    );
  }

  function field_group_admin_enqueue_scripts() {
    wp_enqueue_script(
      'acf-native-fields-admin',
      plugins_url('/js/acf-native-fields-admin.js', __FILE__),
      ['jquery'],
      ACF_Native_Fields::instance()->version,
    );
    wp_enqueue_style(
      'acf-native-fields-admin',
      plugins_url('/css/acf-native-fields-admin.css', __FILE__),
      [],
      ACF_Native_Fields::instance()->version,
    );
  }
}
