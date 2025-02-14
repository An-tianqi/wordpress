( function( $ ) {

	"use strict";

	$( '.wcpscwc-product-slider' ).each(function( index ) {

		var slider_id   = $(this).attr('id');
		var slider_conf = $.parseJSON( $(this).closest('.wcpscwc-product-slider-wrap').find('.wcpscwc-slider-conf').attr('data-conf'));
		var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';

		jQuery('#'+slider_id+' .'+slider_cls).slick({
			dots			: ( slider_conf.dots == "true" )		? true : false,
			infinite		: ( slider_conf.loop == "true" )		? true : false,
			arrows			: ( slider_conf.arrows == "true" )		? true : false,
			autoplay		: ( slider_conf.autoplay == "true" )	? true : false,
			speed			: parseInt( slider_conf.speed ),
			autoplaySpeed	: parseInt( slider_conf.autoplay_speed ),
			slidesToShow	: parseInt( slider_conf.slide_to_show ),
			slidesToScroll	: parseInt( slider_conf.slide_to_scroll ),
			rtl             : ( slider_conf.rtl == "true" ) ? true : false,
			responsive: [{
				breakpoint: 1023,
				settings: {
					slidesToShow:(parseInt( slider_conf.slide_to_show ) > 3) ? 3 : parseInt( slider_conf.slide_to_show ),
					slidesToScroll: 1,
					infinite: true,
					dots: false
				}
			},{
				breakpoint: 767,
				settings: {
					slidesToShow: (parseInt( slider_conf.slide_to_show ) > 2) ? 2 : parseInt( slider_conf.slide_to_show ),
					slidesToScroll: 1
				}
			},{
				breakpoint: 479,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: false
				}
			},{
				breakpoint: 319,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: false
				}
			}]
		});
	});
})( jQuery );