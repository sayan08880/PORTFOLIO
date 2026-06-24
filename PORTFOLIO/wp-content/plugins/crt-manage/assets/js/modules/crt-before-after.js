(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-before-after.default',function($scope) {

            var imagesWrap = $scope.find( '.crt-ba-image-container' ),
                imageOne = imagesWrap.find( '.crt-ba-image-1' ),
                imageTwo = imagesWrap.find( '.crt-ba-image-2' ),
                divider = imagesWrap.find( '.crt-ba-divider' ),
                startPos = imagesWrap.attr( 'data-position' );

            // Horizontal
            if ( imagesWrap.hasClass( 'crt-ba-horizontal' ) ) {
                // On Load
                divider.css( 'left', startPos +'%' );
                imageTwo.css( 'left', startPos +'%' );
                imageTwo.find( 'img' ).css( 'right', startPos +'%' );

                // On Move
                divider.on( 'move', function(e) {
                    var overlayWidth = e.pageX - imagesWrap.offset().left;

                    // Reset
                    divider.css({
                        'left' : 'auto',
                        'right' : 'auto'
                    });
                    imageTwo.css({
                        'left' : 'auto',
                        'right' : 'auto'
                    });

                    if ( overlayWidth > 0  && overlayWidth < imagesWrap.outerWidth() ) {
                        divider.css( 'left', overlayWidth );
                        imageTwo.css( 'left', overlayWidth );
                        imageTwo.find( 'img' ).css( 'right', overlayWidth );
                    } else {
                        if ( overlayWidth <= 0 ) {
                            divider.css( 'left', 0 );
                            imageTwo.css( 'left', 0 );
                            imageTwo.find( 'img' ).css( 'right', 0 );
                        } else if ( overlayWidth >= imagesWrap.outerWidth() ) {
                            divider.css( 'right', - divider.outerWidth() / 2 );
                            imageTwo.css( 'right', 0 );
                            imageTwo.find( 'img' ).css( 'right', '100%' );
                        }
                    }

                    hideLabelsOnTouch();
                });

                // Vertical
            } else {
                // On Load
                divider.css( 'top', startPos +'%' );
                imageTwo.css( 'top', startPos +'%' );
                imageTwo.find( 'img' ).css( 'bottom', startPos +'%' );

                // On Move
                divider.on( 'move', function(e) {
                    var overlayWidth = e.pageY - imagesWrap.offset().top;

                    // Reset
                    divider.css({
                        'top' : 'auto',
                        'bottom' : 'auto'
                    });
                    imageTwo.css({
                        'top' : 'auto',
                        'bottom' : 'auto'
                    });

                    if ( overlayWidth > 0  && overlayWidth < imagesWrap.outerHeight() ) {
                        divider.css( 'top', overlayWidth );
                        imageTwo.css( 'top', overlayWidth );
                        imageTwo.find( 'img' ).css( 'bottom', overlayWidth );
                    } else {
                        if ( overlayWidth <= 0 ) {
                            divider.css( 'top', 0 );
                            imageTwo.css( 'top', 0 );
                            imageTwo.find( 'img' ).css( 'bottom', 0 );
                        } else if ( overlayWidth >= imagesWrap.outerHeight() ) {
                            divider.css( 'bottom', - divider.outerHeight() / 2 );
                            imageTwo.css( 'bottom', 0 );
                            imageTwo.find( 'img' ).css( 'bottom', '100%' );
                        }
                    }

                    hideLabelsOnTouch();
                });
            }

            // Mouse Hover
            if ( 'mouse' === imagesWrap.attr( 'data-trigger' ) ) {

                imagesWrap.on( 'mousemove', function( event ) {

                    // Horizontal
                    if ( imagesWrap.hasClass( 'crt-ba-horizontal' ) ) {
                        var overlayWidth = event.pageX - $(this).offset().left;
                        divider.css( 'left', overlayWidth );
                        imageTwo.css( 'left', overlayWidth );
                        imageTwo.find( 'img' ).css( 'right', overlayWidth );

                        // Vertical
                    } else {
                        var overlayWidth = event.pageY - $(this).offset().top;
                        divider.css( 'top', overlayWidth );
                        imageTwo.css( 'top', overlayWidth );
                        imageTwo.find( 'img' ).css( 'bottom', overlayWidth );
                    }

                    hideLabelsOnTouch();
                });

            }

            // Hide Labels
            hideLabelsOnTouch();

            function hideLabelsOnTouch() {
                var labelOne = imagesWrap.find( '.crt-ba-label-1 div' ),
                    labelTwo = imagesWrap.find( '.crt-ba-label-2 div' );

                if ( ! labelOne.length && ! labelTwo.length ) {
                    return;
                }

                // Horizontal
                if ( imagesWrap.hasClass( 'crt-ba-horizontal' ) ) {
                    var labelOneOffset = labelOne.position().left + labelOne.outerWidth(),
                        labelTwoOffset = labelTwo.position().left + labelTwo.outerWidth();

                    if ( labelOneOffset + 15 >= parseInt( divider.css( 'left' ), 10 ) ) {
                        labelOne.stop().css( 'opacity', 0 );
                    } else {
                        labelOne.stop().css( 'opacity', 1 );
                    }

                    if ( (imagesWrap.outerWidth() - (labelTwoOffset + 15)) <= parseInt( divider.css( 'left' ), 10 ) ) {
                        labelTwo.stop().css( 'opacity', 0 );
                    } else {
                        labelTwo.stop().css( 'opacity', 1 );
                    }

                    // Vertical
                } else {
                    var labelOneOffset = labelOne.position().top + labelOne.outerHeight(),
                        labelTwoOffset = labelTwo.position().top + labelTwo.outerHeight();

                    if ( labelOneOffset + 15 >= parseInt( divider.css( 'top' ), 10 ) ) {
                        labelOne.stop().css( 'opacity', 0 );
                    } else {
                        labelOne.stop().css( 'opacity', 1 );
                    }

                    if ( (imagesWrap.outerHeight() - (labelTwoOffset + 15)) <= parseInt( divider.css( 'top' ), 10 ) ) {
                        labelTwo.stop().css( 'opacity', 0 );
                    } else {
                        labelTwo.stop().css( 'opacity', 1 );
                    }
                }
            }
        });
    });
})(jQuery);