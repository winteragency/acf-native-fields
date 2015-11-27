(function($) {
	var acf_settings_native_field = acf.model.extend({
		actions: {
			'open_field':			'render',
			'change_field_type':	'render'
		},

		render: function($el){
			// bail early if not correct field type
			if( $el.attr('data-type') != 'native_field' ) {

				return;

			}

			// clear name
			$el.find('.acf-field[data-name="name"] input').val('').trigger('change');

			console.log('native_field');
		}
	});
})(jQuery);