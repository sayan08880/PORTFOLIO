<?php
/**
 * Nason Magazine Blog functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Egan_Portfolio_Resume
 */

if ( ! defined( 'EGAN_PORTFOLIO_RESUME_VERSION' ) ) {
	define( 'EGAN_PORTFOLIO_RESUME_VERSION', wp_get_theme()->get( 'Version' ) );
}
if ( ! defined( 'EGAN_PORTFOLIO_RESUME_NAME' ) ) {
    define( 'EGAN_PORTFOLIO_RESUME_NAME', wp_get_theme()->get( 'Name' ) );
}
if ( ! defined( 'EGAN_PORTFOLIO_RESUME_URL_DEMO' ) ) {
    define( 'EGAN_PORTFOLIO_RESUME_URL_DEMO', wp_get_theme()->get( 'ThemeURI' ) );
}

if ( ! function_exists( 'egan_portfolio_resume_setup' ) ) :
    function egan_portfolio_resume_setup() {

        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        add_theme_support( 'title-tag' );

        add_theme_support( 'wp-block-styles' );

        add_theme_support( 'register_block_style' );

        add_theme_support( 'register_block_pattern' );

        add_theme_support( 'post-thumbnails' );

        add_theme_support( 'post-formats', array( 'aside', 'video', 'gallery', 'audio') );

        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support(
            'custom-logo',
            array(
                'height'      => 250,
                'width'       => 250,
                'flex-width'  => true,
                'flex-height' => true,
            )
        );

        add_theme_support( 'align-wide' );
        add_theme_support( 'responsive-embeds' );

        add_theme_support( 'html5', array(
            'comment-list',
            'comment-form',
            'search-form',
            'gallery',
            'caption',
        ) );

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(
            array(
                'primary' => esc_html__( 'Primary','egan-portfolio-resume' ),
            )
        );
        register_nav_menus(
            array(
                'not_home_nav' => esc_html__( 'Not Home','egan-portfolio-resume' ),
            )
        );
        register_nav_menus(
            array(
                'footer' => esc_html__( 'Footer','egan-portfolio-resume' ),
            )
        );
    }
endif;
add_action( 'after_setup_theme', 'egan_portfolio_resume_setup' );

add_image_size( 'egan-portfolio-resume-image-small', 300, 9999 );
add_image_size( 'egan-portfolio-resume-image-medium', 600, 9999 );
add_image_size( 'egan-portfolio-resume-image-large', 1200, 9999 );

if ( ! function_exists( 'egan_portfolio_resume_after_active' ) ) :
    function egan_portfolio_resume_after_active() {
        $theme_active = get_option('stylesheet');
        if($theme_active == 'lopez-creative-portfolio') {
            $parent_dir = get_template_directory();
            $parent_theme_slug = basename($parent_dir);
            $parent_mods = get_option( "theme_mods_$parent_theme_slug");
            unset($parent_mods['crt_manage_hero_layout']);
            unset($parent_mods['crt_manage_header_show_left_nav']);
            unset($parent_mods['crt_manage_post_latest_layout']);
            if(!empty($parent_mods)) {
                foreach($parent_mods as $key => $value) {
                    set_theme_mod($key, $value);
                }
            }
            set_theme_mod('crt_manage_hero_layout', 'left-img');
            set_theme_mod('crt_manage_header_show_left_nav', false);
            set_theme_mod('crt_manage_post_latest_layout', 'masonry-3-columns');
        }
    }
endif;
add_action('after_switch_theme', 'egan_portfolio_resume_after_active');


if ( ! function_exists( 'egan_portfolio_resume_header_style' ) ) :
    /**
     * Styles the header image and text displayed on the blog.
     *
     * @see egan_portfolio_resume_header_style().
     */
    function egan_portfolio_resume_header_style() {
        $header_text_color = get_header_textcolor();

        /*
         * If no custom options for text are set, let's bail.
         * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
         */
        if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
            return;
        }

        // If we get this far, we have custom styles. Let's do this.
        ?>
        <style type="text/css">
            <?php
            // Has the text been hidden?
            if ( ! display_header_text() ) :
                ?>
            .site-title,
            .site-description {
                position: absolute;
                clip: rect(1px, 1px, 1px, 1px);
                color: red !important;
            }
            <?php
            // If the user has set a custom color for the text use that.
        else :
            ?>
            .site-title a,
            .site-description {
                color: #<?php echo esc_attr( $header_text_color ); ?>;
            }
            <?php endif; ?>
        </style>
        <?php
    }
endif;

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function egan_portfolio_resume_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'egan_portfolio_resume_content_width', 640 );
}
add_action( 'after_setup_theme', 'egan_portfolio_resume_content_width', 0 );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

function egan_portfolio_resume_widget_registration($name, $id, $description,$beforeWidget, $afterWidget, $beforeTitle, $afterTitle){
    register_sidebar( array(
        'name' => $name,
        'id' => $id,
        'description' => $description,
        'before_widget' => $beforeWidget,
        'after_widget' => $afterWidget,
        'before_title' => $beforeTitle,
        'after_title' => $afterTitle,
    ));
}

function egan_portfolio_resume_widgets_init() {
    egan_portfolio_resume_widget_registration(esc_html__('Sidebar Front Page', 'egan-portfolio-resume'), 'sidebar-1', esc_html__('Add widgets here.', 'egan-portfolio-resume'), '<section id="%1$s" class="widget %2$s">', '</section>', '<h2 class="widget-title"><span>', '</span></h2>');

    egan_portfolio_resume_widget_registration(esc_html__('Footer Menu', 'egan-portfolio-resume'), 'footer-menu', esc_html__('Add widgets here.', 'egan-portfolio-resume'), '<div id="%1$s" class="footer %2$s">', '</div>', '<h2 class="widget-title"><span>', '</span></h2>');
    egan_portfolio_resume_widget_registration(esc_html__('Footer Above', 'egan-portfolio-resume'), 'footer-above', esc_html__('Add widgets here.', 'egan-portfolio-resume'), '<div id="%1$s" class="footer %2$s">', '</div>', '<h2 class="widget-title"><span>', '</span></h2>');
}
add_action( 'widgets_init', 'egan_portfolio_resume_widgets_init' );

/**
 * Count Widget Footer Active
 */
function egan_portfolio_resume_footer_is_widget() {
    $widget_active = array();
    for($i = 1; $i < 5;$i++) {
        if(is_active_sidebar( 'footer-'.$i )) {
            $widget_active[$i] = $i;
        }
    }
    return $widget_active;
}

/**
 * Enqueue scripts and styles.
 */
function egan_portfolio_resume_scripts() {
    wp_enqueue_style( 'egan-portfolio-resume-style', get_template_directory_uri() . '/style.css', array(), EGAN_PORTFOLIO_RESUME_VERSION );
    // Main style.
    wp_enqueue_style( 'egan-portfolio-resume-main-style', get_template_directory_uri() . '/assets/build/css/main.min.css', array(), EGAN_PORTFOLIO_RESUME_VERSION );

    // library script.
    wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/js/slick.min.js', array(  ), EGAN_PORTFOLIO_RESUME_VERSION, true );
    wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/js/modernizr-3.11.2.min.js', array(  ), EGAN_PORTFOLIO_RESUME_VERSION, true );
    wp_enqueue_script('imagesloaded', '', array(  ) );
    wp_enqueue_script('jquery-masonry', '', array( 'jquery' ) );
    wp_enqueue_script( 'jquery-lazy', get_template_directory_uri() . '/assets/js/jquery.lazy.min.js', array(  ), EGAN_PORTFOLIO_RESUME_VERSION, true );
    wp_enqueue_script( 'jquery-magnific', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.js', array(  ), EGAN_PORTFOLIO_RESUME_VERSION, true );
    wp_enqueue_script( 'egan-portfolio-resume-main-script', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), EGAN_PORTFOLIO_RESUME_VERSION, true );

    wp_enqueue_script( 'gsap-js', get_template_directory_uri() . '/assets/js/gsap.js', array(), EGAN_PORTFOLIO_RESUME_VERSION, true );
    wp_enqueue_script( 'gsap-scroll-trigger', get_template_directory_uri() . '/assets/js/ScrollTrigger.js', array('gsap-js'), EGAN_PORTFOLIO_RESUME_VERSION, true );
    wp_enqueue_script( 'gsap-split-text', get_template_directory_uri() . '/assets/js/SplitText.js', array('gsap-js'), EGAN_PORTFOLIO_RESUME_VERSION, true );
    wp_enqueue_script( 'gsap-app', get_template_directory_uri() . '/assets/js/app.js', array('gsap-js'), EGAN_PORTFOLIO_RESUME_VERSION, true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    wp_add_inline_script( 'egan-portfolio-resume-main-script', 'const EGAN_PORTFOLIO_RESUME_SCRIPT = ' . egan_portfolio_resume_script_inline(), 'before' );
}
add_action( 'wp_enqueue_scripts', 'egan_portfolio_resume_scripts' );

function egan_portfolio_resume_google_font_default() {
    $font_family = array(
        'Federo:300,regular,700',
        'DM Sans:300,regular,700'
    );
    $query_args = array(
        'family' => urlencode( implode( '|', $font_family ) ),
    );

    if ( ! empty( $font_family ) ) {
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }
    return $fonts_url;
}

function egan_portfolio_resume_dynamic_front_end_css() {
    if(!crt_manage_plugins_is_active()) {
        $body_font = get_theme_mod('crt_manage_general_body_font', 'DM Sans');
        $heading_font = get_theme_mod('crt_manage_general_heading_font', 'Federo');
        $logo_font = get_theme_mod('crt_manage_header_logo_font', 'DM Sans');
        $nav_font = get_theme_mod('crt_manage_general_nav_font', 'Federo');
        $nav_transform = get_theme_mod('crt_manage_general_nav_transform', 'uppercase');

        $custom_css_front = '';
        $custom_css_front .= ' :root {
           --body-font: '. esc_attr( $body_font ) .';
           --heading-font: '. esc_attr( $heading_font ) .';
           --logo-font: '. esc_attr( $logo_font ) .';
           --nav-font: '. esc_attr( $nav_font ) .';
           --header-nav-transform: '. esc_attr( $nav_transform ) .';
        }';
        wp_register_style( 'egan-portfolio-resume-style-inline', false );
        wp_enqueue_style( 'egan-portfolio-resume-style-inline' );
        wp_add_inline_style( 'egan-portfolio-resume-style-inline', $custom_css_front );

        wp_enqueue_style( 'egan-portfolio-resume-google-fonts', wptt_get_webfont_url( egan_portfolio_resume_google_font_default() ), array(), null );
    }
}
add_action( 'wp_enqueue_scripts', 'egan_portfolio_resume_dynamic_front_end_css' );

/**
 * Include wptt webfont loader.
 */
require_once get_theme_file_path( 'inc/wptt-webfont-loader.php' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Dynamic CSS
 */
require get_template_directory() . '/inc/dynamic-css.php';

/**
 * Breadcrumb
 */
require get_template_directory() . '/inc/class-breadcrumb-trail.php';

/**
 * Recommended Plugins
 */
require get_template_directory() . '/inc/tgmpa/recommended-plugins.php';

/**
 * Quick Setup
 */
require get_template_directory() . '/inc/class-quick-setup.php';

/**
 * Define script const
/**/
function egan_portfolio_resume_script_inline() {
    $slider_show = get_theme_mod('crt_manage_hero_v1_slider_on_row', '1');
    $slider_center_mode = get_theme_mod('crt_manage_enable_hero_v1_slider_center_mode', true);
    $slider_auto_play = get_theme_mod('crt_manage_enable_hero_v1_slider_auto_play', true);
    $slider_center_percent = '10%';
    if( $slider_show < 3) {
        $slider_center_percent = '12%';
    } elseif( $slider_show < 4) {
        $slider_center_percent = '9%';
    } elseif($slider_show < 5) {
        $slider_center_percent = '6%';
    }
    $script_inline = json_encode( array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'HERO_SLIDER_SHOW' => $slider_show,
        'HERO_SLIDER_CENTER_MODE' => $slider_center_mode,
        'HERO_SLIDER_CENTER_PADDING' => $slider_center_mode ? $slider_center_percent:'0',
        'HERO_SLIDER_AUTO_PLAY' => $slider_auto_play,
        'COLOR_MAIN' => '#FF5B15',
        'SITE' => get_template_directory_uri(),
    ));
    return $script_inline;
}

/**
* Post View Count
/**/
function egan_portfolio_resume_set_post_view_count($postID) {
    $countKey = 'post_view_count';
    $count = get_post_meta($postID, $countKey, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $countKey);
        add_post_meta($postID, $countKey, '1');
    }else{
        $count++;
        update_post_meta($postID, $countKey, $count);
    }
}

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
    require get_template_directory() . '/inc/jetpack.php';
}
/**
 * Woocommerce.
 */
if ( class_exists( 'WooCommerce' ) ) {
    require get_template_directory() . '/inc/woocommerce.php';
}

if ( ! function_exists( 'egan_portfolio_resume_is_woocommerce' ) ) {
    function egan_portfolio_resume_is_woocommerce() {
        if(class_exists( 'WooCommerce' )) {
            return true;
        }
        return false;
    }
}
/**
 * CRT Manage is active.
 */
if ( ! function_exists( 'crt_manage_plugins_is_active' ) ) {
    function crt_manage_plugins_is_active() {
        if(class_exists( 'CRT_Manage_Base' )) {
            return true;
        }
        return false;
    }
}

if(!function_exists('egan_portfolio_resume_array_to_string')) {
    function egan_portfolio_resume_array_to_string($args) {
        $texts = explode(',', $args);
        $texts = egan_portfolio_resume_snipt($texts);
        return $texts;
    }

    function egan_portfolio_resume_snipt($arr){
        $output = '';
        $c = 0;
        foreach ($arr as $word){
            $c++;
            if(count($arr) > $c ) {
                $output .= '"'.trim($word).'",';
            } else {
                $output .= '"'.trim($word).'"';
            }
        }
        return $output;
    }
}
/**
 * Custom Heading Archive.
 */
add_filter('get_the_archive_title', function ($title) {
    $title  = __( 'Archives','egan-portfolio-resume' );

    if ( is_category() ) {
        $title  = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title  = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title  = get_the_author();
    } elseif ( is_year() ) {
        /* translators: See https://www.php.net/manual/datetime.format.php */
        $title  = get_the_date( _x( 'Y', 'yearly archives date format','egan-portfolio-resume' ) );
    } elseif ( is_month() ) {
        /* translators: See https://www.php.net/manual/datetime.format.php */
        $title  = get_the_date( _x( 'F Y', 'monthly archives date format','egan-portfolio-resume' ) );
    } elseif ( is_day() ) {
        /* translators: See https://www.php.net/manual/datetime.format.php */
        $title  = get_the_date( _x( 'F j, Y', 'daily archives date format','egan-portfolio-resume' ) );
    } elseif ( is_tax( 'post_format' ) ) {
        if ( is_tax( 'post_format', 'post-format-aside' ) ) {
            $title = _x( 'Asides', 'post format archive title','egan-portfolio-resume' );
        } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
            $title = _x( 'Galleries', 'post format archive title','egan-portfolio-resume' );
        } elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
            $title = _x( 'Images', 'post format archive title','egan-portfolio-resume' );
        } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
            $title = _x( 'Videos', 'post format archive title','egan-portfolio-resume' );
        } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
            $title = _x( 'Quotes', 'post format archive title','egan-portfolio-resume' );
        } elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
            $title = _x( 'Links', 'post format archive title','egan-portfolio-resume' );
        } elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
            $title = _x( 'Statuses', 'post format archive title','egan-portfolio-resume' );
        } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
            $title = _x( 'Audio', 'post format archive title','egan-portfolio-resume' );
        } elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
            $title = _x( 'Chats', 'post format archive title','egan-portfolio-resume' );
        }
    } elseif ( is_post_type_archive() ) {
        $title  = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $queried_object = get_queried_object();
        if ( $queried_object ) {
            $tax    = get_taxonomy( $queried_object->taxonomy );
            $title  = single_term_title( '', false );
        }
    } elseif (is_cart()) {
        $title = _x( 'Cart', 'post format archive title','egan-portfolio-resume' );
    } elseif (is_checkout()) {
        $title = _x( 'Checkout', 'post format archive title','egan-portfolio-resume' );
    }  elseif (is_search()) {
        $value = get_search_query();
        $title = sprintf( esc_html__( 'Search: %s','egan-portfolio-resume' ), $value);
    }
    return $title;
});
