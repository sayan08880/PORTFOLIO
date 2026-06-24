<?php
    $prefix = 'crt_manage_client_';
    $enable = get_theme_mod($prefix . 'enable_section');
    if(!$enable) {
        return;
    }

    $headline = get_theme_mod( $prefix . 'heading', __( 'Client', 'egan-portfolio-resume' ) );
    $headline_label = get_theme_mod( $prefix . 'heading_label', __( 'Some of my skills', 'egan-portfolio-resume' ) );
    $client_list = json_to_array(get_theme_mod( $prefix . 'list' ));
    $attr_id = get_theme_mod( $prefix . 'attr_id', 'client' );
?>

<section id="client" class="py-5 br-b">
    <?php crt_manage_section_link( 'Client' ); ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php egan_portfolio_resume_heading($headline, $headline_label); ?>
                <?php if(!empty($client_list)): ?>
                    <div class="client__nav slider-client-nav__js">
                        <?php
                            foreach ($client_list as $client): if(!empty($client['field_repeater'])): foreach ($client['field_repeater'] as $client_item):
                        ?>
                            <figure class="ratio11 rounded-circle mx-2" style="background-image: url(<?php echo esc_html($client_item['client_image']) ?>)"></figure>
                        <?php endforeach; endif; endforeach; ?>
                    </div>
                    <div class="client__list slider-client__js">
                        <?php
                            foreach ($client_list as $client): if(!empty($client['field_repeater'])): foreach ($client['field_repeater'] as $client_item):
                        ?>
                            <div class="client__item">
                                <div class="client__content text-center">
                                    <div class="client__intro my-4"><?php echo esc_html($client_item['client_content']) ?></div>
                                    <h3 class="client__name"><?php echo esc_html($client_item['client_name']) ?></h3>
                                    <div class="client__role"><?php echo esc_html($client_item['client_job']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; endif; endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

