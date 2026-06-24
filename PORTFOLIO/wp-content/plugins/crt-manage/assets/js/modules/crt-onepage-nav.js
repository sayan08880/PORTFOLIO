(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-onepage-nav.default',function($scope) {

            // GOGA - remove extra code before update
            $(document).ready(function(){
                // Get all the links with the class "nav-link"
                var $navLinks = $scope.find( '.crt-onepage-nav-item' ),
                    scrollSpeed = parseInt( $scope.find('.crt-onepage-nav').attr( 'data-speed' ), 10 ),
                    considerHeader = $scope.find('.crt-onepage-nav').attr( 'data-consider-header' ),
                    // sections = $( '.elementor-section' );
                    getSections = [];
                $navLinks.each(function() {
                    getSections.push($($(this).find('a').attr('href')));
                });

                var sections = $(getSections);

                var currentUrl = window.location.href;
                var sectionId = currentUrl.split("#")[1];

                // Check if the URL contains a section id
                if(sectionId) {
                    // Get the section element
                    var $section = $("#" + sectionId);

                    // Get the offset position of the section
                    var sectionPos = $section.offset().top;

                    // Smoothly scroll to the section
                    $('html, body').animate({
                        scrollTop: 'yes' === considerHeader && $('[data-crt-sticky-section="yes"]').first().length ? sectionPos - $('[data-crt-sticky-section="yes"]').first().height() : sectionPos
                    }, scrollSpeed);
                }

                $navLinks.each(function() {
                    if(currentUrl.indexOf($(this).find('a').attr('href')) !== -1){
                        $(this).addClass('crt-onepage-active-item');
                    }
                });

                // Iterate over each link
                $navLinks.each(function() {
                    // Add a click event to each link
                    $(this).click(function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        // Remove the active class from all links
                        $navLinks.removeClass('crt-onepage-active-item');
                        // Add the active class to the clicked link
                        $(this).addClass('crt-onepage-active-item');
                        // Get the id of the section the link points to
                        var sectionId = $(this).find('a').attr('href');
                        // Get the section element
                        var $section = $(sectionId);
                        // Get the offset position of the section
                        var sectionPos = $section.offset().top;
                        // Smoothly scroll to the section
                        $('html, body').animate({
                            scrollTop: 'yes' === considerHeader  && $('[data-crt-sticky-section="yes"]').first().length ? sectionPos - $('[data-crt-sticky-section="yes"]').first().height() : sectionPos
                        }, scrollSpeed);
                    });
                });

                $(window).on("scroll", function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    // Get the current scroll position
                    var scrollPos = $(this).scrollTop();

                    if ( !$.isEmptyObject(sections) ) {
                        // Iterate over each section
                        sections.each(function() {
                            if ( $(this).length > 0 ) {
                                // Get the offset position of the section
                                var sectionPos = $(this).offset().top;
                                // Get the height of the section
                                var sectionHeight = sectionPos + $(this).outerHeight();

                                // Check if the section is in view
                                if (scrollPos >= sectionPos - 50 && scrollPos < sectionPos + sectionHeight - 50) {
                                    // if ( scrollPos >= sectionPos && scrollPos < sectionPos + sectionHeight ) {
                                    // Get the id of the section
                                    var sectionId = $(this).attr("id");
                                    // Remove the active class from all links
                                    $navLinks.removeClass("crt-onepage-active-item");
                                    // Add the active class to the corresponding link
                                    $navLinks.filter(function(){
                                        return $(this).find('a[href*=' + sectionId + ']').length;
                                    }).addClass("crt-onepage-active-item");
                                }
                            }
                        });
                    }
                });
            });
        });
    });
})(jQuery);