jQuery.noConflict();
(function($) {
	$(function() {

        $(document).on('change', '.gielda_group_selector', function(e) {
            selectGroup( this );
        });

		function selectGroup( element )
		{
			$(element).closest('.inspire-panel').find('.gielda_group_container, .gielda_group_container input').hide();
            $(element).closest('.inspire-panel').find('.gielda_group_container input').prop('disabled', true);

			var groupKey = $(element).val();
			if (groupKey && groupKey.length > 0)
			{
                $(element).closest('.inspire-panel').find('.gielda_groups_header').show();
                $(element).closest('.inspire-panel').find('div.gielda_group_container.' + groupKey + '_group, div.gielda_group_container.' + groupKey + '_group input').show();
                $(element).closest('.inspire-panel').find('div.gielda_group_container.' + groupKey + '_group input').prop('disabled', false);
			} else {
                $(element).closest('.inspire-panel').find('.gielda_groups_header').hide();
			}

		}

		$(document).ready(function(){
        	$('.gielda_group_selector').each(function() {
        		$(this).change();
			});
		})

        $('#woocommerce-product-data').on('woocommerce_variations_loaded', function(event) {
            $('.gielda_group_selector').each(function() {
                $(this).change();
            });
        });

        jQuery('select.gielda_category_autocomplete_select2').select2({
            minimumInputLength: 3,
            ajax: {
                method: 		'GET',
                url: 			ajaxurl,
                dataType: 		'json',
                data: function ( params ) {
          			return {
                		term:       params.term, // search query
                        action: 	'gielda_category_autocomplete',
            		};
        		},
                processResults: function( data ) {
                    var options = [];
                    if ( data ) {
                        // data is the array of arrays, and each of them contains ID and the Label of the option
                        jQuery.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
                            options.push( { id: text.value, text: text.text  } );
                        });

                    }
                    return {
                        results: options
                    };
                },
                cache: true
            }
        });

        jQuery('input.gielda_category_autocomplete_select2').select2({
            minimumInputLength: 3,
            ajax: {
                method: 		'GET',
                url: 			ajaxurl,
                dataType: 		'json',
                data: function ( term, page ) {
                    return {
                        term:       term, // search query
                        action: 	'gielda_category_autocomplete',
                    };
                },
                processResults: function( data ) {
                    var options = [];
                    if ( data ) {
                        // data is the array of arrays, and each of them contains ID and the Label of the option
                        jQuery.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
                            options.push( { id: text.value, text: text.text  } );
                        });

                    }
                    return {
                        results: options
                    };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                callback({'text':jQuery(element).attr('data-label')});
            },
        });

	});
})(jQuery);