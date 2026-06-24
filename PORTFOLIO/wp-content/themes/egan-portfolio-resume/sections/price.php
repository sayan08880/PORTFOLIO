<?php
    $prefix = 'crt_manage_price_';
    $enable = get_theme_mod($prefix . 'enable_section');
    if(!$enable) {
        return;
    }
    $headline = get_theme_mod( $prefix . 'heading', __( 'Price', 'egan-portfolio-resume' ) );
    $headline_label = get_theme_mod( $prefix . 'heading_label', __( 'Some of my skills', 'egan-portfolio-resume' ) );
    $price_list = json_to_array(get_theme_mod( $prefix . 'list' ));
    $attr_id = get_theme_mod( $prefix . 'attr_id', 'price' );
?>

<section id="<?php echo esc_attr($attr_id); ?>" class="py-5 br-b">
    <?php crt_manage_section_link( 'Price' ); ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php egan_portfolio_resume_heading($headline, $headline_label); ?>

                <?php if(!empty($price_list)): ?>
                    <div class="price__list">
                        <?php
                            foreach ($price_list as $price): if(!empty($price['field_repeater'])): foreach ($price['field_repeater'] as $price_item):
                        ?>
                        <div class="price__item">
                            <div class="price__item--inner text-center">
                                <h3 class="price__name"><?php echo esc_html($price_item['price_title']); ?></h3>
                                <div class="price__cost"><?php echo wp_kses_post($price_item['price_value']); ?></div>
                                <div class="price__content"><?php echo wp_kses_post($price_item['price_description']); ?></div>
                                <div class="price__button mt-3">
                                    <a href="<?php echo esc_attr($price_item['price_button_url']); ?>"><?php echo esc_html($price_item['price_button_text']); ?></a>
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

