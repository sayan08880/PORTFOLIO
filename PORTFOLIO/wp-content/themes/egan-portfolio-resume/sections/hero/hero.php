<?php
    $prefix = 'crt_manage_hero_';
    $enable = get_theme_mod($prefix . 'enable_section');
    $attr_id = get_theme_mod($prefix . 'attr_id', 'hero');
    $hero_layout = get_theme_mod($prefix . 'layout');
    if(!$enable) {
        return;
    }
?>
<section id="<?php echo esc_attr($attr_id); ?>" class="hero br-b <?php echo esc_attr('hero-' . $hero_layout); ?>">
    <?php get_template_part( 'sections/hero/hero-v1' ); ?>
</section>




