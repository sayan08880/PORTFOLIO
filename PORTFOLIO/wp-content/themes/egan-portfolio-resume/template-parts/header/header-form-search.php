<?php
?>

<div class="form-search">
    <div class="form-search__inner">
        <a class="btn-search-close" href="#">
            <i class="fa-solid fa-xmark"></i>
            <label><?php esc_html_e( 'Press ESC to close','egan-portfolio-resume' ); ?></label>
        </a>
        <?php get_search_form(); ?>
        <div class="form-search__reference">
            <h3><?php esc_html_e( 'Or check our Popular Categories...','egan-portfolio-resume' ); ?></h3>
            <?php
            $terms = get_terms( array(
                'taxonomy'   => 'category',
                'hide_empty' => false,
            ) );
            ?>
            <?php if (!empty($terms)): ?>
                <ul>
                    <?php foreach ($terms as $t): ?>
                        <li><a href="<?php echo esc_attr(get_term_link($t)); ?>"><?php echo esc_html($t->name); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

