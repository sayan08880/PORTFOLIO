(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-advanced-accordion.default',function($scope) {
            var acc = $scope.find('.crt-acc-button');
            var accPanels = $scope.find('.crt-acc-panel');
            var accItemWrap = $scope.find('.crt-accordion-item-wrap');
            var accordionType = $scope.find('.crt-advanced-accordion').data('accordion-type');
            var activeIndex = +$scope.find('.crt-advanced-accordion').data('active-index') - 1;
            var accordionTrigger = $scope.find('.crt-advanced-accordion').data('accordion-trigger');
            var interactionSpeed = +$scope.find('.crt-advanced-accordion').data('interaction-speed') * 1000;
            var scopeID = $scope.attr('data-id');

            // ?active_panel=panel-index#your-id
            var activeTabIndexFromLocation = window.location.href.indexOf("active_panel=");

            if (activeTabIndexFromLocation > -1) {
                activeIndex = +window.location.href.substring(activeTabIndexFromLocation,  window.location.href.lastIndexOf("#")).replace("active_panel=", '') - 1;
            }

            if ('click' === accordionTrigger) {

                if ( accordionType == 'accordion' ) {
                    acc.off('click').on("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var thisIndex = acc.index(this);
                        acc.each(function(index){
                            (index != thisIndex && $(this).closest('.elementor-widget-crt-advanced-accordion').attr('data-id') == scopeID) ? $(this).removeClass('crt-acc-active') : '';
                        });
                        $scope.find('.crt-acc-panel').each(function(index) {
                            (index != thisIndex && $(this).closest('.elementor-widget-crt-advanced-accordion').attr('data-id') == scopeID) ? $(this).removeClass('crt-acc-panel-active') && $(this).slideUp(interactionSpeed) : '';
                        });
                        $(this).toggleClass("crt-acc-active");
                        var panel = $(this).next();
                        if ( !panel.hasClass('crt-acc-panel-active') ) {
                            panel.slideDown(interactionSpeed);
                            panel.addClass('crt-acc-panel-active');
                        } else {
                            panel.slideUp(interactionSpeed);
                            panel.removeClass('crt-acc-panel-active');
                        }
                    });
                } else {
                    acc.each(function() {
                        $(this).on("click", function() {
                            $(this).toggleClass("crt-acc-active");
                            var panel = $(this).next();
                            if ( !panel.hasClass('crt-acc-panel-active') ) {
                                panel.slideDown(interactionSpeed);
                                panel.addClass('crt-acc-panel-active');
                            } else {
                                panel.slideUp(interactionSpeed);
                                panel.removeClass('crt-acc-panel-active');
                            }
                        });
                    });
                }

                // acc && (activeIndex > -1 && acc.eq(activeIndex).trigger('click'));
            } else if ( accordionTrigger == 'hover' ) {
                accItemWrap.on("mouseenter", function() {
                    var thisIndex = accItemWrap.index(this);

                    $(this).find('.crt-acc-button').addClass("crt-acc-active");

                    var panel = $(this).find('.crt-acc-panel');
                    panel.slideDown(interactionSpeed);
                    panel.addClass('crt-acc-panel-active');

                    accItemWrap.each(function(index) {
                        if (index != thisIndex) {
                            $(this).find('.crt-acc-button').removeClass("crt-acc-active");
                            var panel = $(this).find('.crt-acc-panel');
                            panel.slideUp(interactionSpeed);
                            panel.removeClass('crt-acc-panel-active');
                        }
                    });
                });

                accItemWrap &&  (activeIndex > -1 && accItemWrap.eq(activeIndex).trigger('mouseenter'));
            }

            $scope.find('.crt-acc-search-input').on( {
                focus: function() {
                    $scope.addClass( 'crt-acc-search-input-focus' );
                },
                blur: function() {
                    $scope.removeClass( 'crt-search-form-input-focus' );
                }
            } );

            let allInAcc = $scope.find('.crt-advanced-accordion > *');

            $scope.find('i.fa-times').on('click', function() {
                $scope.find('.crt-acc-search-input').val('');
                $scope.find('.crt-acc-search-input').trigger('keyup');
            });

            var iconBox = $scope.find('.crt-acc-icon-box');

            iconBox.each(function() {
                $(this).find('.crt-acc-icon-box-after').css({
                    'border-top': $(this).height()/2 + 'px solid transparent',
                    'border-bottom': $(this).height()/2 + 'px solid transparent'
                });
            });

            $(window).resize(function() {
                iconBox.each(function() {
                    $(this).find('.crt-acc-icon-box-after').css({
                        'border-top': $(this).height()/2 + 'px solid transparent',
                        'border-bottom': $(this).height()/2 + 'px solid transparent'
                    });
                });
            });

            $scope.find('.crt-acc-search-input').on('keyup', function() {
                setTimeout( () => {
                    let thisValue = $(this).val();
                    if ( thisValue.length > 0 ) {
                        $scope.find('.crt-acc-search-input-wrap').find('i.fa-times').css('display', 'inline-block');
                        allInAcc.each(function() {
                            if ( $(this).hasClass('crt-accordion-item-wrap') ) {
                                var itemWrap = $(this);
                                if ( itemWrap.text().toUpperCase().indexOf(thisValue.toUpperCase()) == -1 ) {
                                    itemWrap.hide();
                                    if ( itemWrap.find('.crt-acc-button').hasClass('crt-acc-active') && itemWrap.find('.crt-acc-panel').hasClass('crt-acc-panel-active') ) {
                                        itemWrap.find('.crt-acc-button').removeClass('crt-acc-active');
                                        itemWrap.find('.crt-acc-panel').removeClass('crt-acc-panel-active');
                                    }
                                } else {
                                    itemWrap.show();
                                    if ( !itemWrap.find('.crt-acc-button').hasClass('crt-acc-active') && !itemWrap.find('.crt-acc-panel').hasClass('crt-acc-panel-active') ) {
                                        itemWrap.find('.crt-acc-button').addClass('crt-acc-active');
                                        itemWrap.find('.crt-acc-panel').addClass('crt-acc-panel-active');
                                        itemWrap.find('.crt-acc-panel').slideDown(interactionSpeed);
                                    }
                                }
                            }
                        });
                    } else {
                        $scope.find('.crt-acc-search-input-wrap').find('i.fa-times').css('display', 'none');
                        allInAcc.each(function() {
                            if ( $(this).hasClass('crt-accordion-item-wrap') ) {
                                $(this).show();
                                if ( $(this).find('.crt-acc-panel').hasClass('crt-acc-panel-active') ) {
                                    $(this).find('.crt-acc-panel').removeClass('crt-acc-panel-active');
                                }
                                if ( $(this).find('.crt-acc-button').hasClass('crt-acc-active') ) {
                                    $(this).find('.crt-acc-button').removeClass('crt-acc-active')
                                }
                                $(this).find('.crt-acc-panel').slideUp(interactionSpeed);
                            }
                        });
                        // if ('click' === accordionTrigger) {
                        // 	acc && (activeIndex > -1 && acc.eq(activeIndex).trigger('click'));
                        // } else if ( 'hover' === accordionTrigger ) {
                        // 	accItemWrap &&  (activeIndex > -1 && accItemWrap.eq(activeIndex).trigger('mouseenter'));
                        // }
                    }
                }, 1000);
            });

        });
    });
})(jQuery);