<?php
    $prefix = 'crt_manage_service_';
    $enable = get_theme_mod($prefix . 'enable_section');
    if(!$enable) {
        return;
    }

    $headline = get_theme_mod( $prefix . 'heading', __( 'Service', 'egan-portfolio-resume' ) );
    $headline_label = get_theme_mod( $prefix . 'heading_label', __( 'Some of my skills', 'egan-portfolio-resume' ) );
    $service_list = json_to_array(get_theme_mod( $prefix . 'list' ));
    $attr_id = get_theme_mod( $prefix . 'attr_id', 'service' );

?>

<section id="<?php echo esc_attr($attr_id); ?>" class="py-5 br-b">
    <?php crt_manage_section_link( 'Service' ); ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php egan_portfolio_resume_heading($headline, $headline_label); ?>
                <?php if(!empty($service_list)): ?>
                <div class="service__list">
                    <?php
                        foreach ($service_list as $service): if(!empty($service['field_repeater'])): foreach ($service['field_repeater'] as $service_item):
                    ?>
                    <div class="service__item">
                        <div class="service__item--inner">
                            <div class="service__icon mb-3">
                                <i class="<?php echo esc_attr($service_item['service_image']) ?>"></i>
                            </div>
                            <h3 class="service__title"><a href="<?php echo esc_attr($service_item['service_button_url']) ?>"><?php echo esc_html($service_item['service_name'] ?? $service_item['service_name']); ?></a></h3>
                            <div class="service__content"><?php echo esc_html($service_item['service_intro'] ?? $service_item['service_intro']); ?></div>
                            <div class="service__button mt-3">
                                <a href="<?php echo esc_attr($service_item['service_button_url'] ?? $service_item['service_button_url']); ?>" title="<?php echo esc_html($service_item['service_name'] ?? $service_item['service_name']); ?>"><i class="i-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; endif; endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

