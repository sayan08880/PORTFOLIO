(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-content-toggle.default',function($scope) {


            var $contentToggle = $( '.crt-content-toggle', $scope ).first(),
                $switcherContainer = $( '.crt-switcher-container', $contentToggle ).first(),
                $switcherWrap = $( '.crt-switcher-wrap', $contentToggle ).first(),
                $contentWrap = $( '.crt-switcher-content-wrap', $contentToggle ).first(),
                $switcherBg = $( '> .crt-switcher-bg', $switcherWrap ),
                $switcherList = $( '> .crt-switcher', $switcherWrap ),
                $contentList = $( '> .crt-switcher-content', $contentWrap );

            // Active Tab
            var activeSwitcherIndex = parseInt( $switcherContainer.data('active-switcher') ) - 1;

            $switcherList.eq( activeSwitcherIndex ).addClass( 'crt-switcher-active' );
            $contentList.eq( activeSwitcherIndex ).addClass( 'crt-switcher-content-active crt-animation-enter' );

            function crtSwitcherBg( index ) {

                if ( ! $scope.hasClass( 'crt-switcher-label-style-outer' ) ) {

                    var switcherWidth = 100 / $switcherList.length,
                        switcherBgDistance = index * switcherWidth;

                    $switcherBg.css({
                        'width' : switcherWidth + '%',
                        'left': switcherBgDistance + '%'
                    });
                }

            }

            crtSwitcherBg( activeSwitcherIndex );

            // Tab Switcher
            function crtTabsSwitcher( index ) {
                var activeSwitcher = $switcherList.eq( index ),
                    activeContent = $contentList.eq( index ),
                    activeContentHeight = 'auto';

                // Switcher
                crtSwitcherBg( index );

                if ( ! $scope.hasClass( 'crt-switcher-label-style-outer' ) ) {
                    $switcherList.removeClass( 'crt-switcher-active' );
                    activeSwitcher.addClass( 'crt-switcher-active' );

                    if ( $scope.hasClass( 'crt-switcher-style-dual' ) ) {
                        $switcherContainer.attr( 'data-active-switcher', index + 1 );
                    }
                }

                // Tabs
                $contentWrap.css( { 'height': $contentWrap.outerHeight( true ) } );

                $contentList.removeClass( 'crt-switcher-content-active crt-animation-enter' );

                activeContentHeight = activeContent.outerHeight( true );
                activeContentHeight += parseInt( $contentWrap.css( 'border-top-width' ) ) + parseInt( $contentWrap.css( 'border-bottom-width' ) );

                activeContent.addClass( 'crt-switcher-content-active crt-animation-enter' );

                $contentWrap.css({ 'height': activeContentHeight });

                setTimeout( function() {
                    $contentWrap.css( { 'height': 'auto' } );
                }, 500 );

            }

            // Tab Click Event
            function crtTabsClick() {

                // Outer Labels
                if ( $scope.hasClass( 'crt-switcher-label-style-outer' ) ) {
                    $switcherWrap.on( 'click', function() {
                        var activeSwitcher = $switcherWrap.find( '.crt-switcher-active' );

                        if ( 1 === parseInt( activeSwitcher.data( 'switcher'), 10 ) ) {
                            // Reset
                            $switcherWrap.children( '.crt-switcher' ).eq(0).removeClass( 'crt-switcher-active' );

                            // Set Active
                            $switcherWrap.children( '.crt-switcher' ).eq(1).addClass( 'crt-switcher-active' );
                            $switcherWrap.closest( '.crt-switcher-container' ).attr( 'data-active-switcher', 2 );
                            crtTabsSwitcher( 1 );

                        } else if ( 2 === parseInt( activeSwitcher.data( 'switcher'), 10 ) ) {
                            // Reset
                            $switcherWrap.children( '.crt-switcher' ).eq(1).removeClass( 'crt-switcher-active' );

                            // Set Active
                            $switcherWrap.children( '.crt-switcher' ).eq(0).addClass( 'crt-switcher-active' );
                            $switcherWrap.closest( '.crt-switcher-container' ).attr( 'data-active-switcher', 1 );
                            crtTabsSwitcher( 0 );
                        }

                        // crtTabsSwitcher( switcherIndex );

                    });

                    // Inner Labels / Multi Labels
                } else {
                    $switcherList.on( 'click', function() {

                        var switcherIndex = $( this ).data( 'switcher' ) - 1;

                        crtTabsSwitcher( switcherIndex );

                    });
                }
            }

            crtTabsClick();

        });
    });
})(jQuery);