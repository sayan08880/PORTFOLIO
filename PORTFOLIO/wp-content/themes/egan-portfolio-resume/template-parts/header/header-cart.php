<?php

$prefix_header_left_option = get_theme_mod('crt_manage_header_left_show_cart');
$prefix_header_right_option = get_theme_mod('crt_manage_header_right_show_cart');

?>

<?php if(egan_portfolio_resume_is_woocommerce()) : ?>
    <div class="header__cart <?php echo esc_attr($args['class']); ?>">
        <?php do_action('egan_portfolio_resume_mini_cart'); ?>
    </div>
<?php endif; ?>
