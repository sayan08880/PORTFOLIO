jQuery(document).ready(function(){
    'use strict';

    var sections_container = control_settings.sections_container;
    var saved_data_input = control_settings.saved_data_input;

    jQuery( sections_container ).sortable({
        axis: 'y',
        items: '> li:not(.panel-meta)',
    });

    var section_item = jQuery( sections_container ).find('li:not(.panel-meta)');
    section_item.each(function () {
        var s = jQuery(this);
        s.find('h3').prepend('<span class="section-top" data-dir="up"> < </span><span class="section-bottom" data-dir="down"> > </span>');
        s.click(function (e) {
            e.preventDefault();
            var jTarget = jQuery(e.target),
                dir = jTarget.data('dir'),
                jItem = jQuery(e.currentTarget),
                jItems = jQuery( sections_container ).find('li:not(.panel-meta)'),
                index = jItems.index(jItem);

            switch (dir) {
                case 'up':
                    if (index != 0) {
                        jQuery(this).detach().insertBefore(jItems[index - 1]);
                    }
                    break;
                case 'down':
                    if (index != jItems.length - 1) {
                        jQuery(this).detach().insertAfter(jItems[index + 1]);
                    }
                    break;
            }
            update_order();
        });
    });



    function update_order(){
        var values = {};
        var sections = jQuery( sections_container ).sortable('toArray');
        for(var i = 0; i < sections.length; i++){
            var section_id =  sections[i].replace('accordion-section-','');
            values[section_id] = (i+2)*5;
        }
        var data_to_send = JSON.stringify(values);
        jQuery(saved_data_input).val(data_to_send);

        setTimeout(function() {
            jQuery(saved_data_input).trigger('change');
        }, 500);
    }
});