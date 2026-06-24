<?php

use CrtAddons\Classes\Utilities;
use CrtAddons\Plugin;
use Elementor\Core\Documents_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main CRT Manage Core Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class CRT_Manage_Extension {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Elementor_Test_Extension The single instance of the class.
	 */
	private static $_instance = null;


    protected $crt_manage_theme_name;

    public $categories;

    /**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_Test_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

        $this->crt_manage_theme_name = get_option( 'template' );
        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }


        // Add Plugin actions
        add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );

        // Register widget scripts
//        add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'widget_scripts' ]);

        // category register
        add_action( 'elementor/elements/categories_registered',[ $this, 'crt_manage_elementor_widget_categories' ] );

        add_action( 'elementor/documents/register', array( $this, 'register_elementor_document_type' ));
        add_action( 'wp_enqueue_scripts', array($this, 'crt_manage_front_end_modules_scripts') );

        add_action( 'elementor/controls/controls_registered', [ $this, 'register_custom_controls' ] );

        $this->includes();
        $this->hooks();

    }

    public function includes() {
        require CRT_MANAGE_DIR . '/includes/customizer/addons/controls/crt-control-animations.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/controls/crt-control-ajax-select2.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/controls/crt-control-icons.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/controls/crt-sticky-section.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/woocommerce/woocommerce-config.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/woocommerce/woocommerce-helpers.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/woocommerce/crt-check-product-in-wc.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/woocommerce/crt-grid-helpers.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/woocommerce/crt-add-remove-from-compare.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/woocommerce/crt-add-remove-from-wishlist.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/woocommerce/crt-compare-popup-action.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/woocommerce/crt-count-wishlist-compare-items.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/woocommerce/crt-update-mini-compare.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/woocommerce/crt-update-mini-wishlist.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/admin/crt-templates-modal-popups.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/class/crt-post-likes.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/class/crt-ajax-search.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/class/crt-load-more-instagram-posts.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/class/mega-menu/mega-menu.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/class/form/form-submissions.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/class/form/crt-submissions-cpt.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/class/form/crt-send-email.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/class/form/crt-recaptcha-handler.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/class/form/crt-actions-status.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/class/crt-gsap-animations.php';

        require CRT_MANAGE_DIR . '/includes/customizer/addons/admin/crt-templates-actions.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/admin/library/crt-templates-data.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/admin/library/crt-templates-library-blocks.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/admin/library/crt-templates-library-pages.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/admin/library/crt-templates-library-popups.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/admin/library/crt-templates-library-sections.php';
//        require CRT_MANAGE_DIR . '/includes/customizer/addons/notices/plugin-sale-notice.php';
//        require CRT_MANAGE_DIR . '/includes/customizer/addons/notices/plugin-update-notice.php';
//        require CRT_MANAGE_DIR . '/includes/customizer/addons/notices/pro-features-notice.php';
        require CRT_MANAGE_DIR . '/includes/customizer/addons/notices/rating-notice.php';
//        require CRT_MANAGE_DIR . '/includes/customizer/addons/email-builder/class-email-builder.php';
//        require CRT_MANAGE_DIR . '/includes/customizer/addons/email-builder/class-email-settings.php';
    }

    public function hooks() {
        add_action( 'rest_api_init', function() {
            register_rest_route(
                'crtaddons/v1/ajaxselect2',
                '/(?P<action>\w+)/',
                [
                    'methods' => 'GET',
                    'callback' =>  [$this, 'callback'],
                    'permission_callback' => '__return_true'
                ]
            );
        } );

        add_action('wp_ajax_crt_get_custom_meta_keys' , [$this, 'get_custom_meta_keys']);
        add_action('wp_ajax_nopriv_crt_get_custom_meta_keys',[$this, 'get_custom_meta_keys']);
    }

    public function callback( $request ) {
        return $this->{$request['action']}( $request );
    }

    public function get_custom_meta_keys( $request ) {
//        if ( ! current_user_can( 'edit_posts' ) ) {
//            return;
//        }

        $data = [];
        $options = [];
        $merged_meta_keys = [];
        $post_types = Utilities::get_custom_types_of( 'post', false );

        foreach ( $post_types as $post_type_slug => $post_type_name ) {
            $data[ $post_type_slug ] = [];
            $posts = get_posts( [ 'post_type' => $post_type_slug, 'posts_per_page' => -1 ] );

            foreach ( $posts as $key => $post ) {
                $meta_keys = get_post_custom_keys( $post->ID );

                if ( ! empty($meta_keys) ) {
                    for ( $i = 0; $i < count( $meta_keys ); $i++ ) {
                        if ( '_' !== substr( $meta_keys[$i], 0, 1 ) ) {
                            array_push( $data[$post_type_slug], $meta_keys[$i] );
                        }
                    }
                }
            }

            $data[ $post_type_slug ] = array_unique( $data[ $post_type_slug ] );
        }

        foreach ( $data as $array ) {
            $merged_meta_keys = array_unique( array_merge( $merged_meta_keys, $array ) );
        }

        // Rekey
        $merged_meta_keys = array_values($merged_meta_keys);

        for ( $i = 0; $i < count( $merged_meta_keys ); $i++ ) {
            // Add a search condition here
            if ( ! isset( $request['s'] ) || strpos( $merged_meta_keys[$i], $request['s'] ) !== false ) {
                $options[] = [
                    'id' => $merged_meta_keys[$i],
                    'text' => $merged_meta_keys[$i],
                ];
            }
        }

        return [ 'results' => $options ];
    }

    public function get_posts_by_post_type( $request ) {

        $post_type = isset($request['query_slug']) ? $request['query_slug'] : '';

        $args = [
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => 15,
        ];

        if ( isset( $request['ids'] ) ) {
            $ids = explode( ',', $request['ids'] );
            $args['post__in'] = $ids;
        }

        if ( isset( $request['s'] ) ) {
            $args['s'] = $request['s'];
        }

        if ( 'attachment' === $post_type ) {
            $args['post_status'] = 'any';
        }

        $options = [];
        $query = new \WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $options[] = [
                    'id' => get_the_ID(),
                    'text' => html_entity_decode(get_the_title()),
                ];
            }
        }

        wp_reset_postdata();

        return [ 'results' => $options ];
    }

    public function get_taxonomies( $request ) {


        $args = [
            'orderby' => 'name',
            'order' => 'DESC',
            'hide_empty' => true,
            'number' => 10,
        ];

        $tax = isset($request['query_slug']) ? $request['query_slug'] : '';

        if ( isset( $request['ids'] ) ) {
            $request['ids'] = ('' !== $request['ids']) ? $request['ids'] : '99999999'; // Query Hack
            $ids = explode( ',', $request['ids'] );
            $args['include'] = $ids;
        }

        if ( isset( $request['s'] ) ) {
            $args['name__like'] = $request['s'];
        }

        $options = [];
        $terms = get_terms( $tax, $args );

        if ( ! empty($terms) ) {
            foreach ( $terms as $term ) {
                if($term instanceof WP_Term) {
                    $options[] = [
                        'id'   => $term->term_id,
                        'text' => $term->name,
                    ];
                }
            }
        }

        wp_reset_postdata();

        return [ 'results' => $options ];
    }

    public function get_users( $request ) {
        $args = [
            'number' => '15',
            'blog_id' => 0
        ];

        if ( isset( $request['ids'] ) ) {
            $ids = array_map('intval', explode(',', $request['ids'] ));
            $args['include'] = $ids;
        }

        if ( isset( $request['s'] ) ) {
            $args['search'] = '*'. $request['s'] .'*';
        }

        $options = [];
        $user_query = new \WP_User_Query( $args );

        if ( ! empty( $user_query->get_results() ) ) {
            foreach ( $user_query->get_results() as $user ) {
                $options[] = [
                    'id' => $user->ID,
                    'text' => $user->display_name,
                ];
            }
        }

        wp_reset_postdata();

        return [ 'results' => $options ];
    }

    public function get_elementor_templates( $request ) {
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }

        $args = [
            'post_type' => 'crt_manage_template',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ];

        if ( isset( $request['ids'] ) ) {
            $ids = explode( ',', $request['ids'] );
            $args['post__in'] = $ids;
        }

        if ( isset( $request['s'] ) ) {
            $args['s'] = $request['s'];
        }

        $options = [];
        $query = new \WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $options[] = [
                    'id' => get_the_ID(),
                    'text' => html_entity_decode(get_the_title()),
                ];
            }
        }

        wp_reset_postdata();

        return [ 'results' => $options ];
    }

    public function get_post_type_taxonomies( $request ) {
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }

        $post_type = isset($request['query_slug']) ? $request['query_slug'] : '';

        $args = [
            'orderby' => 'name',
            'order' => 'DESC',
            'hide_empty' => true,
            'number' => -1,
        ];

        if ( isset( $request['ids'] ) ) {
            $request['ids'] = ('' !== $request['ids']) ? $request['ids'] : '99999999'; // Query Hack
            $ids = explode( ',', $request['ids'] );
            $args['include'] = $ids;
        }

        if ( isset( $request['s'] ) ) {
            $args['name__like'] = $request['s'];
        }

        $options = [];
        $taxonomies = get_object_taxonomies( $post_type, 'objects' );

        if ( ! empty($taxonomies) ) {
            foreach ( $taxonomies as $taxonomy ) {
                $options[] = [
                    'id'   => $taxonomy->name,
                    'text' => $taxonomy->label,
                ];
            }
        }

        wp_reset_postdata();

        return [ 'results' => $options ];
    }

    /**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'crt-manage' ),
			'<strong>' . esc_html__( 'CRT Manage Core', 'crt-manage' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'crt-manage' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'crt-manage' ),
			'<strong>' . esc_html__( 'CRT Manage Core', 'crt-manage' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'crt-manage' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'crt-manage' ),
			'<strong>' . esc_html__( 'CRT Manage Core', 'crt-manage' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'crt-manage' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {

        $register_widget = \Elementor\Plugin::instance()->widgets_manager;
        $modules = CRT_MANAGE_DIR . '/includes/customizer/addons/modules/*.php';
        $archives = CRT_MANAGE_DIR . '/includes/customizer/addons/modules/archives/*.php';
        $woocommerce = CRT_MANAGE_DIR . '/includes/customizer/addons/modules/woocommerce/*.php';
        $path_files = array_merge(glob($modules) , glob($archives), glob($woocommerce));
        foreach ($path_files as $filename) {
            require_once( $filename );
        }

        // Register widget
        foreach ($this->widget_class() as $class_name) {
            if(isset($class_name[0])) {
                if(class_exists($class_name[0])) {
                    $register_widget->register( new $class_name[0]() );
                }
            }
        }
	}

    public function widget_scripts() {
        wp_register_script('crt-manage-lazy-load',get_template_directory_uri() . '/assets/build/js/main.bundle.js', array('jquery'),false,true);
	}

    public function crt_manage_elementor_widget_categories( $elements_manager ) {
        $categories = array();

        $categories['crt_manage_header_elements'] = array(
            'title' => __( 'CRThemes Header', 'crt-manage' ),
            'icon' 	=> 'fa fa-plug',
        );

        $categories['crt_manage_theme'] = array(
            'title' => __( 'CRThemes Elements', 'crt-manage' ),
            'icon' 	=> 'fa fa-plug',
        );

        $categories['crt_manage_archive'] = array(
            'title' => __( 'CRThemes Archive', 'crt-manage' ),
            'icon' 	=> 'fa fa-plug',
        );

        $categories['crt_manage_single'] = array(
            'title' => __( 'CRThemes Single', 'crt-manage' ),
            'icon' 	=> 'fa fa-plug',
        );

        global $crt_manage_is_woo;
        if($crt_manage_is_woo) {
            $categories['crt_manage_woocommerce'] = array(
                'title' => __( 'CRThemes Woocommerce', 'crt-manage' ),
                'icon' 	=> 'fa fa-plug',
            );
        }

        $categories['crt_manage_popup'] = array(
            'title' => __( 'CRThemes Popup', 'crt-manage' ),
            'icon' 	=> 'fa fa-plug',
        );

        $old_categories = $elements_manager->get_categories();
        $categories     = array_merge( $categories, $old_categories );
        $set_categories = function ( $categories ) {
            $this->categories = $categories;
        };
        $set_categories->call( $elements_manager, $categories );
	}

    function crt_manage_front_end_modules_scripts() {
        wp_enqueue_style( 'font-awesome-5-all', ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css', false, ELEMENTOR_VERSION);

        foreach ($this->widget_class() as $files) {
            $filename = $files[1];
            if(isset($filename)) {
                $url_js_file = CRT_MANAGE_DIR . 'assets/js/modules/'. $filename.'.js';
                $url_css_file = CRT_MANAGE_DIR . 'assets/css/modules/'. $filename.'.css';
                $path_file = CRT_MANAGE_URI . 'assets';
                $js = file_exists($url_js_file);
                $css = file_exists($url_css_file);
                if($css) {
                    wp_register_style(  $filename, $path_file . '/css/modules/'. $filename.'.css', array(), CRT_MANAGE_VERSION, 'all' );
                }
                if($js) {
                    wp_register_script(  $filename, $path_file . '/js/modules/'. $filename.'.js', array(), CRT_MANAGE_VERSION );
                }
            }
        }
    }

    public function widget_class() {
        return array(
            array( 'CRT_Logo', 'crt-logo' ),
            array( 'CRT_Search', 'crt-search' ),

            array( 'CRT_Page_List', 'crt-page-list' ),
            array( 'CRT_Button', 'crt-button' ),
            array( 'CRT_Dual_Color_Heading', 'crt-dual-color-heading' ),
            array( 'CRT_Countdown', 'crt-countdown' ),
            array( 'CRT_Tabs', 'crt-tabs' ),
            array( 'CRT_Posts_Timeline', 'crt-posts-timeline' ),
            array( 'CRT_Lottie_Animations', 'crt-lottie-animations' ),
            array( 'CRT_Pricing_Table', 'crt-pricing-table' ),
            array( 'CRT_Price_List', 'crt-price-list' ),
            array( 'CRT_Content_Toggle', 'crt-content-toggle' ),
            array( 'CRT_Flip_Box', 'crt-flip-box' ),
            array( 'CRT_Media_Grid', 'crt-media-grid' ),
            array( 'CRT_Feature_List', 'crt-feature-list' ),
            array( 'CRT_Instagram_Feed', 'crt-instagram-feed' ),
            array( 'CRT_Twitter_Feed', 'crt-twitter-feed' ),
            array( 'CRT_Mailchimp', 'crt-mailchimp' ),
            array( 'CRT_Forms', 'crt-forms' ),
            array( 'CRT_Form_Builder', 'crt-form-builder' ),
            array( 'CRT_Charts', 'crt-charts' ),
            array( 'CRT_Testimonial_Carousel', 'crt-testimonial' ),
            array( 'CRT_Image_Scroll', 'crt-image-scroll' ),
            array( 'CRT_Date', 'crt-date' ),
            array( 'CRT_Business_Hours', 'crt-business-hours' ),
            array( 'CRT_Image_Hotspots', 'crt-image-hotspots' ),
            array( 'CRT_Data_Table', 'crt-data-table' ),
            array( 'CRT_OnepageNav', 'crt-onepage-nav' ),
            array( 'CRT_Google_Maps', 'crt-google-maps' ),
            array( 'CRT_Before_After', 'crt-before-after' ),
            array( 'CRT_Dual_Button', 'crt-dual-button' ),
            array( 'CRT_Video_Playlist', 'crt-video-playlist' ),
            array( 'CRT_Team_Member', 'crt-team-member' ),

            array( 'CRT_Offcanvas', 'crt-offcanvas' ),
            array( 'CRT_Advanced_Text', 'crt-advanced-text' ),
            array( 'CRT_Advanced_Accordion', 'crt-advanced-accordion' ),
            array( 'CRT_Advanced_Slider', 'crt-advanced-slider' ),
            array( 'CRT_Nav_Menu', 'crt-nav-menu' ),
            array( 'CRT_Mega_Menu', 'crt-mega-menu' ),
            array( 'CRT_Archive_Title', 'crt-archive-title' ),
            array( 'CRT_Breadcrumbs', 'crt-breadcrumbs' ),

            array( 'CRT_Category_Grid', 'crt-category-grid' ),
            array( 'CRT_Author_Box', 'crt-author-box' ),
            array( 'CRT_Post_Comments', 'crt-post-comments' ),
            array( 'CRT_Post_Content', 'crt-post-content' ),
            array( 'CRT_Post_Info', 'crt-post-info' ),
            array( 'CRT_Post_Media', 'crt-post-media' ),
            array( 'CRT_Post_Navigation', 'crt-post-navigation' ),
            array( 'CRT_Post_Title', 'crt-post-title' ),
            array( 'CRT_Sharing_Buttons', 'crt-sharing-buttons' ),
            array( 'CRT_Reading_Progress_Bar', 'crt-reading-progress-bar' ),

            array( 'CRT_Page_Cart', 'crt-page-cart' ),
            array( 'CRT_Page_Checkout', 'crt-page-checkout' ),
            array( 'CRT_Page_My_Account', 'crt-my-account' ),
            array( 'CRT_Product_AddToCart', 'crt-product-add-to-cart' ),
            array( 'CRT_Product_AdditionalInformation', 'crt-product-additional-information' ),
            array( 'CRT_Product_Description', 'crt-product-description' ),
            array( 'CRT_Product_Excerpt', 'crt-product-excerpt' ),
            array( 'CRT_Product_Media', 'crt-product-media' ),
            array( 'CRT_Product_Meta', 'crt-product-meta' ),
            array( 'CRT_Product_Mini_Cart', 'crt-product-mini-cart' ),
            array( 'CRT_Product_Notice', 'crt-product-notice' ),
            array( 'CRT_Product_Price', 'crt-product-price' ),
            array( 'CRT_Product_Rating', 'crt-product-rating' ),
            array( 'CRT_Product_SalesBadge', 'crt-product-sales-badge' ),
            array( 'CRT_Product_Tabs', 'crt-product-tabs' ),
            array( 'CRT_Product_Title', 'crt-product-title' ),
            array( 'CRT_Product_Breadcrumbs', 'crt-product-breadcrumbs' ),
            array( 'CRT_Woo_Grid', 'crt-woo-grid' ),
//            array( 'CRT_Filter_Price', 'crt-filter-price' ),
//            array( 'CRT_Filter_Taxonomy', 'crt-filter-taxonomy' ),
//            array( 'CRT_Filter_Attributes', 'crt-filter-attributes' ),
            array( 'CRT_Grid', 'crt-grid' ),
            array( 'CRT_Taxonomy_List', 'crt-taxonomy-list' ),
            array( 'CRT_Magazine_Grid', 'crt-magazine-grid' ),
            array( 'CRT_Content_Ticker', 'crt-content-ticker' ),
            array( 'CRT_Progress_Bar', 'crt-progress-bar' ),
            array( 'CRT_Promo_Box', 'crt-promo-box' ),
            array( 'CRT_Woo_Category_Grid_Pro', 'crt-woo-category-grid-pro' ),
            array( 'CRT_Phone_Call', 'crt-phone-call' ),
            array( 'CRT_Back_To_Top', 'crt-back-to-top' ),
//            array( 'CRT_Product_Filters', 'crt-product-filters' ),
            array( 'CRT_Advanced_Filters_Pro', 'crt-advanced-filters-pro' ),
            array( 'CRT_Compare_Button_Pro', 'crt-compare-button-pro' ),
            array( 'CRT_Compare_Pro', 'crt-compare-pro' ),
            array( 'CRT_Mini_Compare_Pro', 'crt-mini-compare-pro' ),
            array( 'CRT_Mini_Wishlist_Pro', 'crt-mini-wishlist-pro' ),
            array( 'CRT_Wishlist_Button_Pro', 'crt-wishlist-button-pro' ),
            array( 'CRT_Wishlist_Pro', 'crt-wishlist-pro' ),
            array( 'CRT_Scroll_Marquee', 'crt-scroll-marquee' ),
            array( 'CRT_Blob_Shapes', 'crt-blob-shapes' ),
            array( 'CRT_Background_Switcher', 'crt-background-switcher' ),
        );
    }

    public function register_elementor_document_type( $documents_manager )
    {
        require CRT_MANAGE_DIR . 'includes/customizer/addons/crt-theme-builder.php';
        require CRT_MANAGE_DIR . 'includes/customizer/addons/crt-popup-builder.php';

        $documents_manager->register_document_type('crt_manage_archive', 'CRT_Theme_Builder');
        $documents_manager->register_document_type('crt_manage_popup', 'CRT_Popup');
    }

    public function register_custom_controls() {
        $controls_manager = \Elementor\Plugin::$instance->controls_manager;
        $controls_manager->register( new CRT_Control_Ajax_Select2() );
        $controls_manager->register( new CRT_Control_Arrow_Icons() );
        $controls_manager->register( new CRT_Control_Animations() );
        $controls_manager->register( new CRT_Control_Animations_Alt() );
        $controls_manager->register( new CRT_Control_Button_Animations() );
    }

}

CRT_Manage_Extension::instance();