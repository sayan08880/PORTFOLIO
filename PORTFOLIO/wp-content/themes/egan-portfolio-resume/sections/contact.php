<?php
    $prefix = 'crt_manage_contact_';
    $enable = get_theme_mod($prefix . 'enable_section');
    if(!$enable) {
        return;
    }
    $heading = get_theme_mod($prefix . 'heading');
    $heading_label = get_theme_mod($prefix . 'heading_label');
    $img = get_theme_mod($prefix . 'image');
    $shortcode = get_theme_mod($prefix . 'shortcode');
    $attr_id = get_theme_mod($prefix . 'attr_id', 'contact');
?>
<section id="<?php echo esc_attr($attr_id); ?>" class="contact py-5 br-b position-relative">
    <?php crt_manage_section_link( 'Contact' ); ?>
    <div class="container ">
        <div class="row">
            <div class="col-12">
                <?php egan_portfolio_resume_heading($heading, $heading_label); ?>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-12 col-md-7">
                <figure class="lazy ratio43" data-src="<?php echo esc_attr($img); ?>"></figure>
            </div>
            <div class="col-12 col-md-5">
                <div class="ps-0 ps-md-4">
                    <?php echo do_shortcode($shortcode);?>
                </div>
            </div>
        </div>
    </div>
</section>
