<?php
    $prefix = 'crt_manage_resume_';
    $enable = get_theme_mod($prefix . 'enable_section');
    if(!$enable) {
        return;
    }

    $headline = get_theme_mod( $prefix . 'heading', __( 'Resume', 'egan-portfolio-resume' ) );
    $headline_label = get_theme_mod( $prefix . 'heading_label', __( 'Some of my skills', 'egan-portfolio-resume' ) );
    $resume_list = json_to_array(get_theme_mod( $prefix . 'list' ));
    $attr_id = get_theme_mod( $prefix . 'attr_id', 'resume' );
?>

<section id="<?php echo esc_attr($attr_id); ?>" class="py-5 br-b">
    <?php crt_manage_section_link( 'Resume' ); ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php egan_portfolio_resume_heading($headline, $headline_label); ?>

                <div class="row">
                    <?php foreach ($resume_list as $skill) { ?>
                        <?php if($skill['field_type'] == 'type_1'): ?>
                            <div class="col-12 col-md-6 pe-2 pe-md-4 pe-lg-5 my-resume__item mb-4">
                                <h3 class="heading-default__small"><?php echo esc_html($skill['title']); ?></h3>
                                <div class="resume-percent">
                                    <?php if(!empty($skill['field_repeater'])): foreach ( $skill['field_repeater'] as $item_skill ) :
                                        $count = (int) $item_skill['skill_precent'] . '0';
                                        ?>
                                        <div class="resume-percent__item" data-viewport="custom">
                                            <label><?php echo esc_html($item_skill['skill_title']); ?></label>
                                            <div class="resume-percent__inner" data-precent="<?php echo esc_attr($count); ?>"><div class="<?php echo esc_attr('width-' . $count); ?>"></div><span class="count"><?php echo esc_html( $count . '%'); ?></span></div>
                                        </div>
                                    <?php endforeach; endif; ?>
                                </div>
                            </div>
                        <?php elseif($skill['field_type'] == 'type_2'): ?>
                            <div class="col-12 col-md-6 my-resume__item mb-4">
                                <h3 class="heading-default__small"><?php echo esc_html($skill['title']); ?></h3>
                                <div class="resume-text">
                                    <?php if(!empty($skill['field_repeater'])): foreach ( $skill['field_repeater'] as $item_skill ) : ?>
                                        <div class="resume-text__item">
                                            <div class="resume-text__label"><?php echo esc_html($item_skill['skill_title']); ?></div>
                                            <div class="resume-text__description">
                                                <?php echo wp_kses_post($item_skill['skill_content']); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

