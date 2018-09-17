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

			// First try to find a built-in method to run for this type of native field
			if(typeof ACF_Native_Fields['moveNativeField_' + native_field_type] === 'function') {
				native_field_placeholder.append(ACF_Native_Fields['moveNativeField_' + native_field_type](native_field_placeholder));
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

		/**
		 * ACF Native Field type: WordPress content editor
		 */
		moveNativeField_content: function() {
			return ACF_Native_Fields.getNativeFieldElement('#postdivrich');
		},

		/**
		 * ACF Native Field type: WordPress excerpt editor
		 */
		moveNativeField_excerpt: function() {
			return ACF_Native_Fields.getNativeFieldElement('#postexcerpt');
		},

		/**
		 * ACF Native Field type: WordPress featured image
		 */
		moveNativeField_featured_image: function() {
			return ACF_Native_Fields.getNativeFieldElement('#postimagediv');
		},

		/**
		 * ACF Native Field type: SEO meta box (Yoast or SEO framework)
		 */
		moveNativeField_yoast_seo: function() {
			return ACF_Native_Fields.getNativeFieldElement('#wpseo_meta, #tsf-inpost-box');
		},

		/**
		 * ACF Native Field type: WordPress publish meta box
		 */
		moveNativeField_publish_box: function() {
			return ACF_Native_Fields.getNativeFieldElement('#submitdiv');
		},

		/**
		 * ACF Native Field type: WordPress permalink meta box
		 */
		moveNativeField_permalink: function() {
			return ACF_Native_Fields.getNativeFieldElement('#slugdiv');
		},

		/**
		 * ACF Native Field type: WordPress discussion settings meta box
		 */
		moveNativeField_discussion: function() {
			return ACF_Native_Fields.getNativeFieldElement('#commentstatusdiv');
		},

		/**
		 * ACF Native Field type: WordPress trackback settings meta box
		 */
		moveNativeField_trackbacks: function() {
			return ACF_Native_Fields.getNativeFieldElement('#trackbacksdiv');
		},

		/**
		 * ACF Native Field type: WordPress post format meta box
		 */
		moveNativeField_format: function() {
			return ACF_Native_Fields.getNativeFieldElement('#formatdiv');
		},

		/**
		 * ACF Native Field type: WordPress page attributes meta box
		 */
		moveNativeField_page_attributes: function() {
			return ACF_Native_Fields.getNativeFieldElement('#pageparentdiv');
		},

		/**
		 * ACF Native Field type: custom
		 */
		moveNativeField_custom: function (native_field_placeholder) {
			return ACF_Native_Fields.getNativeFieldElement('#' + native_field_placeholder.data('metabox-id'));
		},
	};

	$(document).ready(ACF_Native_Fields.init);
})(jQuery);