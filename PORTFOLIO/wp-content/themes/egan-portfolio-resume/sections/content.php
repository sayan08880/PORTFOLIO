<?php
    $enable_content = get_theme_mod('crt_manage_enable_content_section');
    if(!$enable_content) {
        return;
    }
?>
<section id="content" class="py-5 position-relative br-b">
    <?php crt_manage_section_link( 'Content' ); ?>
    <div class="container ">
        <div class="row">
            <div class="col-12">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</section>
