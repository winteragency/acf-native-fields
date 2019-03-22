(function($) {
	var ACF_Native_Fields = {
		editor_container: null,
		native_fields: null,

		/**
		 * Initialize the ACF Native Fields plugin
		 */
		init: function() {
			ACF_Native_Fields.editor_container = $('#post-body');
			ACF_Native_Fields.native_fields = ACF_Native_Fields.editor_container.find('.acf-native-field');

			// Move all native fields into their placeholders
			ACF_Native_Fields.moveNativeFields();
		},

		/**
		 * Function that actually kickstarts the process of moving native WP fields into ACF fields
		 */
		moveNativeFields: function() {
			if(ACF_Native_Fields.native_fields.length < 1) {
				return;
			}

			ACF_Native_Fields.native_fields.each(ACF_Native_Fields.moveNativeField);
		},

		/**
		 * Get a native field element by selector. Wrapper around jQuery.find() to do any processing that needs to be
		 * done for all native elements before they're moved to an ACF field.
		 *
		 * @param selector Selector to pass to jQuery.find();
		 *
		 * @return jQuery A jQuery object of the found element
		 */
		getNativeFieldElement: function(selector) {
			return ACF_Native_Fields.handlePostbox(ACF_Native_Fields.editor_container.find(selector));
		},

		/**
		 * If the given element is a postbox (has .postbox class), do some necessary changes so that it fits the layout
		 *
		 * @param native_field jQuery object
		 *
		 * @return jQuery The same object that was passed, native_field
		 */
		handlePostbox: function(native_field) {
			if(native_field.hasClass('postbox')) {
				native_field.removeClass('postbox').addClass('native-field-postbox');
				native_field.find('.handlediv, h3').remove();
			}

			return native_field;
		},

		/**
		 * Callback run on each ACF Native Field placeholder on the page. Finds the correct native field and moves it
		 * into the given ACF Native Field placeholder.
		 */
		moveNativeField: function() {
			var native_field_placeholder = $(this).empty();
			var native_field_type = native_field_placeholder.data('native-field');
			var native_fields = {
				// Core
				content: '#postdivrich',
				excerpt: '#postexcerpt',
				publish_box: '#submitdiv',
				page_attributes: '#pageparentdiv',
				featured_image: '#postimagediv',

				permalink: '#slugdiv',
				author: '#authordiv',

				categories: '#categorydiv',
				tags: '#tagsdiv-post_tag',
				format: '#formatdiv',

				revisions: '#revisionsdiv',
				comments: '#commentsdiv',
				discussion: '#commentstatusdiv',
				trackbacks: '#trackbacksdiv',

				// WooCommerce
				all: '#product_catdiv, #tagsdiv-product_tag, #postimagediv, #woocommerce-product-images, #woocommerce-product-data, #postexcerpt, #commentsdiv',
				product_categories: '#product_catdiv',
				product_tags: '#tagsdiv-product_tag',
				product_image: '#postimagediv', // Core Duplicate
				product_gallery: '#woocommerce-product-images',
				product_data: '#woocommerce-product-data',
				product_short_description: '#postexcerpt', // Core Duplicate
				reviews: '#commentsdiv', // Core Duplicate

				// Plugins
				yoast_seo: '#wpseo_meta',
				seo_framework: '#tsf-inpost-box',
				classic_editor: '#classic-editor-switch-editor',
				laygridder: '#gridder, #gridder-modals',

				// Custom
				custom: native_field_placeholder.data('metabox-id'),
			}

			// First try to find a built-in method to run for this type of native field
			if(native_fields[native_field_type]) {
				var native_field = ACF_Native_Fields.getNativeFieldElement(native_fields[native_field_type]);
				native_field_placeholder.append(native_field);
				// TODO: Allow custom callback code to be added in field group settings and executed here?
			}
			// If none exists, see if a custom one has been passed, and exists
			else if(false) {
				// TODO: Implement backend and frontend functionality for custom native fields (hooks)
			}
			// If no built-in or custom method exists, give up and show a message about the problem instead.
			else {
				native_field_placeholder.html(acf._e('native_field', 'not_implemented'));
			}
		},
	};

	$(document).ready(ACF_Native_Fields.init);
})(jQuery);
