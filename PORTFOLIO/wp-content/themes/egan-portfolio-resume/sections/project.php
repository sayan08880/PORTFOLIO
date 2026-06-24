<?php
    $prefix = 'crt_manage_project_';
    $enable = get_theme_mod($prefix . 'enable_section');
    if(!$enable) {
        return;
    }

    $list_project = json_to_array(get_theme_mod( $prefix . 'project' ));
    $heading = get_theme_mod($prefix . 'heading', __( 'Project', 'egan-portfolio-resume' ));
    $heading_label = get_theme_mod($prefix . 'heading_label', __( 'Some projects I have done', 'egan-portfolio-resume' ));
    $attr_id = get_theme_mod( $prefix . 'attr_id', 'project' );
?>

<section id="<?php echo esc_attr($attr_id); ?>" class="py-5 br-b">
    <?php crt_manage_section_link( 'Project' ); ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php egan_portfolio_resume_heading($heading, $heading_label); ?>
                <!-- Start Tabs --->
                <ul class="my-project__nav nav d-flex justify-content-center mb-3 tab-js" data-viewport="opacity">
                    <li class="nav-item" role="presentation">
                        <a class="active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">All</a>
                    </li>
                    <?php foreach ($list_project as $nav) { ?>
                        <li class="nav-item" role="presentation">
                            <a class="" id="<?php echo esc_attr(sanitize_title($nav['title'])); ?>-tab" data-bs-toggle="tab" data-bs-target="#<?php echo esc_attr(sanitize_title($nav['title'])); ?>" type="button" role="tab" aria-controls="<?php echo esc_attr(sanitize_title($nav['title'])); ?>" aria-selected="false"><?php echo esc_html($nav['title']); ?></a>
                        </li>
                    <?php } ?>
                </ul>
                <!-- End Tabs --->

                <!-- Start Tab Content --->
                <div class="tab-content" data-viewport="opacity">
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="project__list">
                            <?php foreach ($list_project as $items) :
                                if(!empty($items['field_repeater'])): foreach ($items['field_repeater'] as $item) : ?>
                                    <?php get_template_part( 'sections/project-item', '',  $item); ?>
                                <?php endforeach; endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php foreach ($list_project as $items) : ?>
                        <div class="tab-pane fade" id="<?php echo esc_attr(sanitize_title($items['title'])); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr(sanitize_title($items['title'])); ?>-tab">
                            <div class="project__list">
                                <?php if(!empty($items['field_repeater'])): foreach ($items['field_repeater'] as $item) : ?>
                                    <?php get_template_part( 'sections/project-item', '',  $item); ?>
                                <?php endforeach; endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- End Tab Content --->
            </div>
        </div>
    </div>
</section>

