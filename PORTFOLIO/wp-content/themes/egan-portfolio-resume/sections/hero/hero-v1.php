<?php
    $prefix = 'crt_manage_hero_';
    $hero_layout = get_theme_mod($prefix . 'layout');
    $class_attr = 'justify-content-start';
    if($hero_layout == 'right-img') {
        $class_attr = 'justify-content-end justify-content-md-start';
    }
?>
<?php crt_manage_section_link( 'Hero' ); ?>

<div class="container">
    <div class="row d-flex align-items-center vh-100 <?php echo esc_attr($class_attr); ?>">
        <?php if($hero_layout == 'center-img'): ?>
            <?php get_template_part( 'sections/hero/hero-center' ); ?>
        <?php elseif($hero_layout == 'right-img'): ?>
            <?php get_template_part( 'sections/hero/hero-right' ); ?>
        <?php else: ?>
            <?php get_template_part( 'sections/hero/hero-left' ); ?>
        <?php endif; ?>
    </div>
</div>
