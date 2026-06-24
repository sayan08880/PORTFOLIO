(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-tabs.default',function($scope) {

            var $tabs = $( '.crt-tabs', $scope ).first(),
                $tabList = $( '.crt-tabs-wrap', $tabs ).first(),
                $contentWrap = $( '.crt-tabs-content-wrap', $tabs ).first(),
                $tabList = $( '> .crt-tab', $tabList ),
                $contentList = $( '> .crt-tab-content', $contentWrap ),
                tabsData = $tabs.data('options');

            // Active Tab
            var activeTabIndex = tabsData.activeTab - 1;

            // ?active_tab=tab-index#your-id
            var activeTabIndexFromLocation = window.location.href.indexOf("active_tab=");

            if (activeTabIndexFromLocation > -1) {
                activeTabIndex = +window.location.href.substring(activeTabIndexFromLocation,  window.location.href.lastIndexOf("#")).replace("active_tab=", '') - 1;
            }

            $tabList.eq( activeTabIndex ).addClass( 'crt-tab-active' );
            $contentList.eq( activeTabIndex ).addClass( 'crt-tab-content-active crt-animation-enter' );

            if ( tabsData.autoplay === 'yes' ) {

                var startIndex = activeTabIndex;

                var autoplayInterval = setInterval( function() {

                    if ( startIndex < $tabList.length - 1 ) {
                        startIndex++;
                    } else {
                        startIndex = 0;
                    }

                    crtTabsSwitcher( startIndex );

                }, tabsData.autoplaySpeed );
            }

            if ( 'hover' === tabsData.trigger ) {
                crtTabsHover();
            } else {
                crtTabsClick();
            }

            // Tab Switcher
            function crtTabsSwitcher( index ) {

                var activeTab = $tabList.eq( index ),
                    activeContent = $contentList.eq( index ),
                    activeContentHeight = 'auto';

                $contentWrap.css( { 'height': $contentWrap.outerHeight( true ) } );

                $tabList.removeClass( 'crt-tab-active' );
                activeTab.addClass( 'crt-tab-active' );

                $contentList.removeClass( 'crt-tab-content-active crt-animation-enter' );

                activeContentHeight = activeContent.outerHeight( true );
                activeContentHeight += parseInt( $contentWrap.css( 'border-top-width' ) ) + parseInt( $contentWrap.css( 'border-bottom-width' ) );


                activeContent.addClass( 'crt-tab-content-active crt-animation-enter' );

                $contentWrap.css({ 'height': activeContentHeight });

                setTimeout( function() {
                    $contentWrap.css( { 'height': 'auto' } );
                }, 500 );

            }

            // Tab Click Event
            function crtTabsClick() {
                $tabList.on( 'click', function() {
                    var tabIndex = $( this ).data( 'tab' ) - 1;
                    clearInterval( autoplayInterval );
                    crtTabsSwitcher( tabIndex );
                });
            }

            // Tab Hover Event
            function crtTabsHover() {
                $tabList.hover( function () {
                    var tabIndex = $( this ).data( 'tab' ) - 1;
                    clearInterval( autoplayInterval );
                    crtTabsSwitcher( tabIndex );
                });
            }
        });
    });
})(jQuery);