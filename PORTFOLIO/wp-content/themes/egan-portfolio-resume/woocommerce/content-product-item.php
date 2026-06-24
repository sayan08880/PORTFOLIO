<?php
/**
 * The template for displaying item product content in home page template
*/
defined( 'ABSPATH' ) || exit;
$product = wc_get_product(get_the_ID());

?>
<div class="product__item" data-id="<?php echo esc_attr(get_the_ID()); ?>">
    <div class="product__item--inner">
        <?php woocommerce_template_loop_product_link_open(); ?>
            <figure class="ratio43 lazy" data-src="<?php echo esc_attr(get_the_post_thumbnail_url( get_the_ID() )); ?>"></figure>
        <?php woocommerce_template_loop_product_link_close(); ?>
        <div class="product__content">
            <?php
                woocommerce_template_loop_product_link_open();
                woocommerce_template_loop_product_title();
                woocommerce_template_loop_product_link_close();
                woocommerce_template_loop_price();
            ?>
            <div class="product__view">
                <?php
                    if( $product->is_type('variable') || $product->is_type('grouped') ) {
                        woocommerce_template_loop_product_link_open();
                            echo esc_html( 'View Detail','egan-portfolio-resume' );
                        woocommerce_template_loop_product_link_close();
                    } else {
                        echo '<a class="customviewaddtocartbutton" href="' . esc_attr( $product->add_to_cart_url() ) . '">' . __('Add To Cart', 'egan-portfolio-resume') . '</a>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>
