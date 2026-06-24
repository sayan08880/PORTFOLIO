(function($) {
    "use strict";
    $( document ).ready( function () {

		var image = $('.woocommerce-product-gallery__image').find('.wp-post-image');

		/**
		 * Remove srcset & size attr
		 */

		$("#woo_gallery a, .variable-items-wrapper li").on("click", function(e){
			e.preventDefault();
			console.log('222');
			$('.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image > a img').removeAttr('srcset');
			$('.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image > a img').removeAttr('sizes');
		});

		$('.variable-items-wrapper').each(function () {
			var t = $(this).find('li');
			t.each(function () {
				$(this).find('span').on('click', function (e) {
					e.preventDefault();
					setTimeout(function () {
						var url  = $('.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image > a').attr('href');
						$('.zoomWindow').css('background-image', 'url("' + url + '")');
					}, 200);
				})
			});
		});

		/**
		 * Init Zoom
		 */

		if(image.length > 0) {
			$(image).ezPlus({
				gallery: 'woo_gallery',
				cursor: 'pointer',
				galleryActiveClass: "active",
				responsive: true,
				scrollZoom: true,
				easing: true,
			});

			$(image).bind("click", function (e) {
				var ez = $(image).data('ezPlus');
				ez.closeAll();
				$.fancybox.open(ez.getGalleryListFancyboxThree());
				return false;
			});
		}

		/**
		 * Product Wishlist, Compare, Quickview
		 */
		var list_product = $('.woocommerce ul.products li.product, .woocommerce-page ul.products li.product');
		list_product.each(function () {
			var t = $(this);
			if(t.find('.woosq-btn').length) {
				t.addClass('product-type-quick-view');
			}
			if(t.find('.woosw-btn').length) {
				t.addClass('product-type-wishlist');
			}
			if(t.find('.woosc-btn').length) {
				t.addClass('product-type-compare');
			}
		});

		$(document.body).trigger('wc_fragment_refresh');

		$('body').on( 'added_to_cart', function(){

		});

		$( '.customviewaddtocartbutton' ).click( function( event ) {

			event.preventDefault();
			var t = $(this);
			var button_text = t.text();
			const data = {
				product_id: t.attr('data-id'),
				quantity: 1,
			}

			$.ajax( {
				type: 'POST',
				url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'add_to_cart' ),
				data: data,
				dataType: 'json',
				beforeSend: function( xhr ) {
					// you can set the button loading state here
					t.text('Adding');
				},
				complete: function( res ) {
					t.text(button_text);
				},
				success: function( res ) {
					$( document.body ).trigger( 'added_to_cart', [ res.fragments, res.cart_hash ] );
				},
			});

		} );


    });
})(jQuery);