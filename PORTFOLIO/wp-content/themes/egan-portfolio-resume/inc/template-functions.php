<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Egan_Portfolio_Resume
 */
function egan_portfolio_resume_body_classes( $classes ) {
    $classes[] = get_theme_mod('crt_manage_general_homepage_options', 'boxed');
    return $classes;
}
add_filter( 'body_class', 'egan_portfolio_resume_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function egan_portfolio_resume_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'egan_portfolio_resume_pingback_header' );

if ( ! function_exists( 'egan_portfolio_resume_excerpt_length' ) ) :
    /**
     * Excerpt length.
     */
    function egan_portfolio_resume_excerpt_length( $length ) {
        if ( is_admin() ) {
            return $length;
        }

        return get_theme_mod( 'egan_portfolio_resume_excerpt_length', 20 );
    }
endif;
add_filter( 'excerpt_length', 'egan_portfolio_resume_excerpt_length', 999 );

if ( ! function_exists( 'egan_portfolio_resume_excerpt_custom' ) ) :
    /**
     * Excerpt Custom length.
     */
    function egan_portfolio_resume_excerpt_custom($limit, $post_id = null) {
        $excerpt = explode(' ', wp_trim_words(get_the_excerpt($post_id), $limit));
        if (count($excerpt) >= $limit) {
            array_pop($excerpt);
            $excerpt = implode(" ", $excerpt) . '...';
        } else {
            $excerpt = implode(" ", $excerpt);
        }
        $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
        return $excerpt;
    }
endif;

if ( ! function_exists( 'egan_portfolio_resume_excerpt_more' ) ) :
    /**
     * Excerpt more.
     */
    function egan_portfolio_resume_excerpt_more( $more ) {
        if ( is_admin() ) {
            return $more;
        }

        return '&hellip;';
    }
endif;
add_filter( 'excerpt_more', 'egan_portfolio_resume_excerpt_more' );

/**
 * Breadcrumb.
 */
function egan_portfolio_resume_breadcrumb( $args = array() ) {
    if ( ! get_theme_mod( 'egan_portfolio_resume_enable_breadcrumb', true ) ) {
        return;
    }

    $args = array(
        'show_on_front' => false,
        'show_title'    => true,
        'show_browse'   => false,
    );
    breadcrumb_trail( $args );
}
add_action( 'egan_portfolio_resume_breadcrumb', 'egan_portfolio_resume_breadcrumb', 10 );

function egan_portfolio_resume_archive_header() {
    $archive_heading_align = get_theme_mod('crt_manage_archive_heading_align', 'center');
    ?>
    <section class="archive-header archive-header__<?php echo esc_attr($archive_heading_align); ?>">
        <div class="container">
            <div class="">
                <div class="row">
                    <div class="col-12 py-3 py-sm-5">
                    <?php if(is_author()): ?>
                        <div class="archive-heading-default">
                            <div class="archive-heading-default__inner">
                                <?php $avatar = get_avatar(get_the_author_meta('ID'), 80, $default_value = '', $alt = '', array('class' => 'mb-3 rounded-circle')); ?>
                                <?php echo $avatar; ?>
                                <h1 class="archive-heading-default__title"><?php echo esc_html(get_the_author()); ?></h1>
                                <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
                                <?php echo egan_portfolio_resume_social_author(); ?>
                            </div>
                        </div>
                    <?php elseif(is_home()): ?>
                        <div class="archive-heading-default">
                            <div class="archive-heading-default__inner">
                                <?php
                                    $queried_object = get_queried_object();
                                ?>
                                <h1 class="archive-heading-default__title"><?php echo esc_html($queried_object->post_title); ?></h1>
                                <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
                            </div>
                            <div class="breadcrumb-option">
                                <?php do_action('egan_portfolio_resume_breadcrumb'); ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="archive-heading-default">
                            <div class="archive-heading-default__inner">
                                <?php the_archive_title( '<h1 class="archive-heading-default__title">', '</h1>' ); ?>
                                <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
                            </div>
                            <div class="breadcrumb-option">
                                <?php do_action('egan_portfolio_resume_breadcrumb'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
}
add_action('egan_portfolio_resume_archive_header', 'egan_portfolio_resume_archive_header', 10);
/**
 * Layout.
 */
function egan_portfolio_resume_archive_layout($position = '') {
    $col_one = '';
    $col_two = '';
    $category_layout = 'standard';
    $sidebar = 'right-sidebar';
    $sidebar_position = 'sidebar-1';

    if(egan_portfolio_resume_is_woocommerce() && (is_product_category() || is_shop())) {
        $sidebar            = get_theme_mod( 'crt_manage_product_category_sidebar', 'right-sidebar' );
        $sidebar_position   = get_theme_mod( 'crt_manage_product_category_sidebar_position', 'sidebar-e-commerce' );
    } elseif (egan_portfolio_resume_is_woocommerce() && is_product()) {
        $sidebar            = get_theme_mod( 'crt_manage_product_single_sidebar', 'right-sidebar' );
        $sidebar_position   = get_theme_mod( 'crt_manage_product_single_sidebar_position', 'sidebar-e-commerce' );
    } elseif(is_category() || is_home()) {
        $category_layout    = get_theme_mod( 'crt_manage_category_layout', 'standard' );
        $sidebar            = get_theme_mod( 'crt_manage_category_sidebar', 'right-sidebar' );
        $sidebar_position   = get_theme_mod( 'crt_manage_category_sidebar_position', 'sidebar-1' );
        $queried_object = get_queried_object();
        if(isset($queried_object->term_id)) {
            $term_layout = get_term_meta($queried_object->term_id, 'crt_manage_tax_layout');
            $term_sidebar = get_term_meta($queried_object->term_id, 'crt_manage_tax_sidebar');
            $term_sidebar_position = get_term_meta($queried_object->term_id, 'crt_manage_tax_sidebar_position');

            if(!empty($term_layout)) {
                $category_layout = $term_layout[0];
            }
            if(!empty($term_sidebar)) {
                $sidebar = $term_sidebar[0];
            }
            if(!empty($term_sidebar_position)) {
                $sidebar_position = $term_sidebar_position[0];
            }
        }
    } elseif (is_tag()) {
        $category_layout    = get_theme_mod( 'crt_manage_tag_layout', 'standard' );
        $sidebar            = get_theme_mod( 'crt_manage_tag_sidebar', 'right-sidebar' );
        $sidebar_position   = get_theme_mod( 'crt_manage_tag_sidebar_position', 'sidebar-1' );
    } elseif (is_author()) {
        $category_layout    = get_theme_mod( 'crt_manage_author_layout', 'standard' );
        $sidebar            = get_theme_mod( 'crt_manage_author_sidebar', 'right-sidebar' );
        $sidebar_position   = get_theme_mod( 'crt_manage_author_sidebar_position', 'sidebar-1' );
    } elseif (is_search()) {
        $category_layout    = get_theme_mod( 'crt_manage_search_layout', 'standard' );
        $sidebar            = get_theme_mod( 'crt_manage_search_sidebar', 'right-sidebar' );
        $sidebar_position   = get_theme_mod( 'crt_manage_search_sidebar_position', 'sidebar-1' );
    } elseif (is_month()) {
        $category_layout    = get_theme_mod( 'crt_manage_date_month_year_layout', 'standard' );
        $sidebar            = get_theme_mod( 'crt_manage_date_month_year_sidebar', 'right-sidebar' );
        $sidebar_position   = get_theme_mod( 'crt_manage_date_month_year_sidebar_position', 'sidebar-1' );
    } elseif (is_single()) {
        $category_layout             = get_theme_mod( 'crt_manage_single_related_layout','' );
        $sidebar            = get_theme_mod( 'crt_manage_single_sidebar', 'right-sidebar' );
        $sidebar_position   = get_theme_mod( 'crt_manage_single_sidebar_position', 'sidebar-1' );
        if(get_post_format() == 'aside') {
            $sidebar = 'right-sidebar';
        }
    } elseif (is_front_page() && ! is_home()) {
        $category_layout    = get_theme_mod( 'crt_manage_post_latest_layout', 'standard' );
        $sidebar            = get_theme_mod( 'crt_manage_post_latest_sidebar', 'right-sidebar' );
        $sidebar_position   = get_theme_mod( 'crt_manage_post_latest_sidebar_position', 'sidebar-1' );
    } elseif(is_page()) {
        $sidebar            = get_theme_mod( 'crt_manage_page_sidebar', 'right-sidebar' );
        $sidebar_position   = get_theme_mod( 'crt_manage_page_sidebar_position', 'sidebar-1' );
    }
    if($sidebar == 'right-sidebar') {
        $col_one = 'col-12 col-md-8 mb-3 mb-md-0';
        $col_two = 'col-12 col-md-4 sidebar-fixed';
    } elseif($sidebar == 'left-sidebar') {
        $col_one = 'col-12 col-md-8 mb-3 mb-md-0';
        $col_two = 'col-12 col-md-4 order-first sidebar-fixed';
    }  elseif($sidebar == 'no-sidebar') {
        $col_one = 'col-12';
        $col_two = 'd-none';
        if($category_layout == 'standard' || $category_layout == '') {
            $col_one = 'col-12 col-md-9 offset-0 offset-md-1';
        }
        if(egan_portfolio_resume_is_woocommerce() && (is_product_category() || is_shop() || is_product() )) {
            $col_one = 'col-12';
        }
    }
    if($category_layout == 'standard') {
        $category_layout = '';
    } elseif($category_layout == 'grid-2-columns') {
        $category_layout = 'grid2';
    } elseif($category_layout == 'grid-3-columns') {
        $category_layout = 'grid3';
    } elseif($category_layout == 'grid-4-columns') {
        $category_layout = 'grid4';
    } elseif($category_layout == 'masonry-2-columns') {
        $category_layout = 'masonry2';
    } elseif($category_layout == 'masonry-3-columns') {
        $category_layout = 'masonry3';
    } elseif($category_layout == 'masonry-4-columns') {
        $category_layout = 'masonry4';
    }  elseif($category_layout == 'list-large-image') {
        $category_layout = 'large-image';
    }
    return array(
        'col_one' => $col_one,
        'col_two' => $col_two,
        'layout' => $category_layout,
        'sidebar' => $sidebar_position,
    );
}

/**
 * Border Columns.
 */
function egan_portfolio_resume_bor_col($layout) {
    ?>
    <?php if($layout == 'grid2' || $layout == 'masonry2'): ?>
        <div class="br-col br-col50 br-sm-col-none"></div>
    <?php elseif($layout == 'grid3' || $layout == 'masonry3'): ?>
        <div class="br-col br-col33 br-sm-col-none"></div>
        <div class="br-col br-col66 br-sm-col-none"></div>
    <?php elseif($layout == 'grid4' || $layout == 'masonry4'): ?>
        <div class="br-col br-col25 br-md-col50 br-sm-col-none"></div>
        <div class="br-col br-col50 br-md-col-none"></div>
        <div class="br-col br-col75 br-md-col-none"></div>
    <?php endif;
}

function egan_portfolio_resume_social_author($author_id = '', $align = 'justify-content-center') {
    $user_socials = array(
        'crt_manage_user_social_facebook' => 'Facebook',
        'crt_manage_user_social_x-twitter' => 'Twitter',
        'crt_manage_user_social_google' => 'Google',
        'crt_manage_user_social_youtube' => 'Youtube',
        'crt_manage_user_social_email' => 'Email',
        'crt_manage_user_social_instagram' => 'Instagram',
        'crt_manage_user_social_pinterest' => 'Pinterest',
        'crt_manage_user_social_linkedin' => 'LinkedIn',
    );
    if(empty($author_id)) {
        $author_id = get_the_author_meta('ID');
    }
    if(!empty($author_id)) {
        $authors = array();
        foreach ($user_socials as $name => $value) {
            $url = get_user_meta($author_id, $name . '_url');
            if(isset($url[0]) && $url[0]) {
                $icon = explode('_', $name);
                $authors[] = array('url' => $url[0], 'icon' => $icon[4]);
            }
        }
        if(!empty($authors)) {
            $class_social_item = 'me-2 ms-2';
            if($align == 'justify-content-center') {
                $class_social_item = 'me-2 ms-2';
            } elseif($align == 'justify-content-start') {
                $class_social_item = 'me-3';
            } elseif($align == 'justify-content-end') {
                $class_social_item = 'ms-3';
            }
            ob_start();
            ?>
                <ul class="author-socials mt-3 d-flex m-0 p-0 <?php echo esc_attr($align); ?>">
                    <?php foreach ($authors as $author_item): if($author_item['icon'] == 'email') { $author_item['icon'] = 'envelope-o'; } ?>
                        <li class="<?php echo esc_attr($class_social_item); ?> list-unstyled"><a href="<?php echo esc_attr($author_item['url']) ?>" target="_blank"><i class="fa-brands fa-<?php echo esc_attr($author_item['icon']) ?>"></i></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php
            return ob_get_clean();
        }
    }
    return '';
}


/**
 * Add separator for breadcrumb trail.
 */
function egan_portfolio_resume_breadcrumb_trail_print_styles() {
    $breadcrumb_separator = get_theme_mod( 'egan_portfolio_resume_breadcrumb_separator', '' );

    if($breadcrumb_separator != '') {
        $style = '
        .breadcrumb-option ul li::after {
            content: "' . $breadcrumb_separator . '";
            }'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        $style = apply_filters( 'egan_portfolio_resume_breadcrumb_trail_inline_style', trim( str_replace( array( "\r", "\n", "\t", '  ' ), '', $style ) ) );

        if ( $style ) {
            echo "\n" . '<style type="text/css" id="breadcrumb-trail-css">' . $style . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }
}
add_action( 'wp_head', 'egan_portfolio_resume_breadcrumb_trail_print_styles' );

/**
 * Pagination for archive.
 */
function egan_portfolio_resume_render_posts_pagination() {
    $is_pagination_enabled = get_theme_mod( 'egan_portfolio_resume_enable_pagination', true );
    if ( $is_pagination_enabled ) {
        $pagination_type = get_theme_mod( 'egan_portfolio_resume_pagination_type', 'defaultc' );
        if ( 'default' === $pagination_type ) :
            the_posts_navigation();
        else :
            global $wp_query;
            $pagination = paginate_links( array(
                'current' => max( 1, get_query_var( 'paged' ) ),
                'total'      => $wp_query->max_num_pages,
                'prev_text'  => __( 'Previous' ,'egan-portfolio-resume'  ),
                'next_text'  => __( 'Next' ,'egan-portfolio-resume'  ),
                'type'       => 'list'
            ) );
            echo '<div class="pagination"><div class="pagination__inner">'. $pagination .'</div></div>';
        endif;
    }
}
add_action( 'egan_portfolio_resume_posts_pagination', 'egan_portfolio_resume_render_posts_pagination', 10 );

/**
 * Pagination for single post.
 */
function egan_portfolio_resume_render_post_navigation() {
    $post_navigation_type = get_theme_mod( 'crt_manage_single_post_navigation_type', 'single-post-navigation-thumb' );
    if($post_navigation_type != 'single-post-navigation-thumb') {
        the_post_navigation(
            array(
                'prev_text' => '<span class="nav-title">%title</span><span class="nav-label">'.esc_html__( 'Prev Post','egan-portfolio-resume' ).'</span>',
                'next_text' => '<span class="nav-title">%title</span><span class="nav-label">'.esc_html__( 'Next Post','egan-portfolio-resume' ).'</span>',
            )
        );
    } else {
        $prev_post = get_previous_post();
        $next_post = get_next_post();
        $prev_thumbnail = '';
        $next_thumbnail = '';
        $prev_html = '';
        $next_html = '';
        if(isset($prev_post->ID)) {
            $prev_thumbnail = get_the_post_thumbnail_url( $prev_post->ID );
        }
        if(isset($next_post->ID)) {
            $next_thumbnail = get_the_post_thumbnail_url( $next_post->ID );
        }
        if(isset($prev_post->post_title)){
            $prev_html = '<div class="prev me-2 d-flex align-content-center"><figure class="me-3 ratio11 lazy position-relative" data-src="'.$prev_thumbnail.'"></figure><div class="d-flex flex-column justify-content-center"><span class="">'.esc_html__( 'Prev Post','egan-portfolio-resume' ).'</span><h3>'.$prev_post->post_title.'</h3></div></div>';
        }
        if(isset($next_post->post_title)){
            $next_html = '<div class="next ms-2 d-flex align-content-center"><div class="d-flex flex-column justify-content-center align-items-end text-end"><span class="">'.esc_html__( 'Next Post','egan-portfolio-resume' ).'</span><h3>'.$next_post->post_title.'</h3></div><figure class="ms-3 ratio11 lazy position-relative" data-src="'.$next_thumbnail.'"></figure></div>';
        }
    ?>
        <div class="d-flex post_navigation_thumb my-4">
            <div class="post_navigation_thumb__item w-50"><?php previous_post_link( '%link', $prev_html ); ?></div>
            <div class="post_navigation_thumb__item w-50"><?php next_post_link( '%link', $next_html ); ?></div>
        </div>
    <?php
    }
}
add_action( 'egan_portfolio_resume_post_navigation', 'egan_portfolio_resume_render_post_navigation' );


/**
 * Author for single post.
 */
function egan_portfolio_resume_render_author_single($post) {
    $avatar = get_avatar($post->post_author, 300);
    $name = get_the_author_meta('display_name', $post->post_author );
    $description = get_the_author_meta( 'description', $post->post_author );
    $url = get_author_posts_url( $post->post_author );
    $author_social = egan_portfolio_resume_social_author($post->post_author, 'justify-content-start');
    ?>
    <div class="single-author p-3 p-md-4 mt-5">
        <div class="row">
            <div class="col-5 col-md-3">
                <?php echo $avatar; ?>
            </div>
            <div class="col-7 col-md-8">
                <h4 class="single-author__name"><?php echo esc_html($name); ?></h4>
                <div class="single-author__description"><?php echo esc_html($description); ?></div>
                <div class="single-author__social"><?php echo $author_social; ?></div>
            </div>
        </div>
    </div>
    <?php
}
add_action( 'egan_portfolio_resume_author', 'egan_portfolio_resume_render_author_single' );


if ( ! function_exists( 'egan_portfolio_resume_validate_excerpt_length' ) ) :
    function egan_portfolio_resume_validate_excerpt_length( $validity, $value ) {
        $value = intval( $value );
        if ( empty( $value ) || ! is_numeric( $value ) ) {
            $validity->add( 'required', esc_html__( 'You must supply a valid number.','egan-portfolio-resume' ) );
        } elseif ( $value < 1 ) {
            $validity->add( 'min_no_of_words', esc_html__( 'Minimum no of words is 1','egan-portfolio-resume' ) );
        } elseif ( $value > 100 ) {
            $validity->add( 'max_no_of_words', esc_html__( 'Maximum no of words is 100','egan-portfolio-resume' ) );
        }
        return $validity;
    }
endif;

/**
 * Adds footer copyright text.
 */
function egan_portfolio_resume_output_footer_copyright_content() {
    $theme_data = wp_get_theme();
    $search     = array( '[the-year]', '[site-link]' );
    $replace    = array( date( 'Y' ), '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '</a>' );
    /* translators: 1: Year, 2: Site Title with home URL. */
    $copyright_default = sprintf( esc_html_x( 'Copyright &copy; %1$s %2$s', '1: Year, 2: Site Title with home URL','egan-portfolio-resume' ), '[the-year]', '[site-link]' );
    $copyright_text    = get_theme_mod( 'egan_portfolio_resume_footer_copyright_text', $copyright_default );
    $copyright_text    = str_replace( $search, $replace, $copyright_text );
    $copyright_text   .= ' ' . esc_html( ' | ' . $theme_data->get( 'Name' ) ) . '&nbsp;' . esc_html__( 'by','egan-portfolio-resume' ) . '&nbsp;<a target="_blank" href="' . esc_url( $theme_data->get( 'AuthorURI' ) ) . '">' . esc_html( ucwords( $theme_data->get( 'Author' ) ) ) . '</a>';
    /* translators: %s: WordPress.org URL */
    $copyright_text .= sprintf( esc_html__( ' | Powered by %s','egan-portfolio-resume' ), '<a href="' . esc_url( __( 'https://wordpress.org/','egan-portfolio-resume' ) ) . '" target="_blank">WordPress</a>. ' );
    ?>
    <div class="copyright">
        <span><?php echo wp_kses_post( $copyright_text ); ?></span>
    </div>
    <?php
}
add_action( 'egan_portfolio_resume_footer_copyright', 'egan_portfolio_resume_output_footer_copyright_content' );

/**
 * Check is json.
 */
function is_json($string) {
    json_decode(html_entity_decode($string), true);
    return json_last_error() === JSON_ERROR_NONE;
}

/**
 * Convert json to array.
 */
function json_to_array($data) {
    $array = array();
    $data = json_decode($data);
    if(empty($data)) {
        return $array;
    }
    foreach ($data as $key => $value) {
        $array_item = array();
        if(is_object($value)) {
            foreach ($value as $key_item =>  $value_item) {
                if(is_json($value_item)){
                    $array_item[$key_item] = json_decode(html_entity_decode($value_item), true);
                } else {
                    $array_item[$key_item] = $value_item;
                }
            }
            $array[$key] = is_object($value) ? (array) $array_item : $array_item;
        }
    }
    return $array;
}

add_action('egan_portfolio_resume_header', 'egan_portfolio_resume_header');
/**
 * Header Global.
 */
function egan_portfolio_resume_header() {
    $header_is_left = get_theme_mod('crt_manage_header_show_left_nav', true);
    if($header_is_left) {
        get_template_part( 'template-parts/header-v1');
    } else {
        get_template_part( 'template-parts/header-v2' );
    }
}

/**
 * Custom Pagination.
 */
if ( ! function_exists( 'egan_portfolio_resume_pagination_custom' ) ) :
    function egan_portfolio_resume_pagination_custom( $paged = '', $max_page = '' ) {
        $big = 999999999;
        if( ! $paged ) {
            $paged = get_query_var('page');
        }

        if( ! $max_page ) {
            global $wp_query;
            $max_page = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
        }

        $pagination = paginate_links( array(
            'base'       => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'     => '?paged=%#%',
            'current'    => max( 1, $paged ),
            'total'      => $max_page,
            'mid_size'   => 1,
            'prev_text'  => __( 'Previous' ,'egan-portfolio-resume'  ),
            'next_text'  => __( 'Next' ,'egan-portfolio-resume'  ),
            'type'       => 'list'
        ) );

        echo '<div class="pagination"><div class="pagination__inner">'. $pagination .'</div></div>';
    }
endif;
