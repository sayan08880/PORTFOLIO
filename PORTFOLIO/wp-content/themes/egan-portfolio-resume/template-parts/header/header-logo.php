<?php
?>

<div class="head__logo">
    <?php if ( has_custom_logo() ) : ?>
        <?php
        $logo_dark = get_theme_mod( 'logo_dark' );
        $logo_light = wp_get_attachment_url( get_theme_mod( 'custom_logo' ) );
        ?>
        <div class="site-logo">
            <?php echo is_front_page() || is_home() ? '<h1 class="head__sologan">':'<span class="head__sologan">' ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                <img class="dark" src="<?php echo esc_attr($logo_dark ? $logo_dark : get_template_directory_uri() . '/assets/img/logo.png'); ?>" alt="<?php bloginfo( 'name' ); ?>">
            </a>
            <?php echo is_front_page() || is_home() ? '</h1>':'</span>' ?>
        </div>
    <?php else : ?>
        <div class="site-identity">
            <?php echo is_front_page() || is_home() ? '<h1 class="head__sologan">':'<span class="head__sologan">' ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
            <span><?php bloginfo( 'description' ); ?></span>
            <?php echo is_front_page() || is_home() ? '</h1>':'</span>' ?>
        </div>
    <?php endif; ?>
</div>
