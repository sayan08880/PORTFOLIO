<?php
namespace CrtAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Utilities {

    const PT_DATA = array(
        'archive-all' => 'Archive All',
        'archive-post' => 'Archive Post',
        'archive-author' => 'Archive Author',
        'archive-date' => 'Archive Date',
        'archive-search' => 'Archive Search',
        'post-categories' => 'Post Categories',
        'post-tags' => 'Post Tags',
        'page_404' => '404',
        'posts' => 'Single Post',
        'pages' => 'Page',
        'page_cart' => 'Page Cart',
        'page_checkout' => 'Page Checkout',
        'front_page' => 'Front Page',
        'archive-product' => 'Archive Product',
        'archive-product-cat' => 'Archive Product Cat',
        'archive-product-tag' => 'Archive Product Tag',
        'archive-product-search' => 'Archive Product Search',
        'product' => 'Single Product',
    );

    /**
     ** Get Plugin Name
     */
    public static function get_plugin_name($full = false) {
        return 'CRT Builder';
    }

    public static function get_registered_modules() {
        $url = base64_decode('aHR0cHM6Ly9yb3lhbC1lbGVtZW50b3ItYWRkb25zLmNvbQ==');

        return [
            'Post Grid/Slider/Carousel' => ['grid', $url .'/elementor-grid-widget-examples/', '#filter:category-blog-grid', ''],
            'WooCommerce Grid/Slider/Carousel' => ['woo-grid', $url .'/elementor-grid-widget-examples/', '#filter:category-woo-grid', ''],
            'Image Grid/Slider/Carousel' => ['media-grid', $url .'/elementor-grid-widget-examples/', '#filter:category-gallery-grid', ''],
            'Magazine Grid/Slider' => ['magazine-grid', $url .'/elementor-grid-widget-examples/', '#filter:category-magazine-grid', ''],
            'Posts/Story Timeline' => ['posts-timeline', $url .'/elementor-timeline-widget/', '', ''],
            'Advanced Slider' => ['advanced-slider', $url .'/elementor-advanced-slider-widget/', '', ''],
            'Off-Canvas Content' => ['offcanvas', $url .'/elementor-offcanvas-menu-widget/', '', 'new'],
            'Testimonial' => ['testimonial', $url .'/elementor-testimonials-slider-widget/', '', ''],
            'Nav Menu' => ['nav-menu', $url .'/elementor-menu-widget/', '', ''],
            'Mega Menu' => ['mega-menu', $url .'/elementor-mega-menu-widget/', '', 'new'],
            'Form Builder' => ['form-builder', $url .'/elementor-form-builder-widget/', '', 'new'],
            'Onepage Navigation' => ['onepage-nav', $url .'/elementor-one-page-navigation-widget/', '', ''],
            'Instagram Feed' => ['instagram-feed', $url .'/elementor-instagram-feed-widget/', '', 'new'],
            'Data Table' => ['data-table', $url .'/elementor-data-table-widget/', '', ''],
            'Pricing Table' => ['pricing-table', $url .'/elementor-pricing-table-widget/', '', ''],
            'Content Toggle' => ['content-toggle', $url .'/elementor-content-toggle-widget/', '', ''],
            'Charts' => ['charts', $url .'/elementor-charts-widget/', '', ''],
            'Countdown' => ['countdown', $url .'/elementor-countdown-widget/', '', ''],
            'Progress Bar' => ['progress-bar', $url .'/elementor-progress-bar-widget/', '', ''],
            'Tabs' => ['tabs', $url .'/elementor-tabs-widget/', '', ''],
            'Dual Color Heading' => ['dual-color-heading', $url .'/elementor-dual-color-heading-widget/', '', ''],
            'Image Accordion' => ['image-accordion', $url .'/elementor-image-accordion-widget/', '', ''],
            'Advanced Accordion' => ['advanced-accordion', $url .'/elementor-advanced-accordion-widget/', '', ''],
            'Advanced Text' => ['advanced-text', $url .'/elementor-advanced-text-widget/', '', ''],
            'Flip Carousel' => ['flip-carousel', $url .'/elementor-flip-carousel-widget/', '', ''],
            'Flip Box' => ['flip-box', $url .'/elementor-flip-box-widget/', '', ''],
            'Promo Box' => ['promo-box', $url .'/elementor-promo-box-widget/', '', ''],
            'Feature List' => ['feature-list', $url .'/elementor-feature-list-widget/', '', ''],
            'Before After' => ['before-after', $url .'/elementor-before-after-widget/', '', ''],
            'Image Hotspots' => ['image-hotspots', $url .'/elementor-image-hotspot-widget/', '', ''],
            'Form Styler' => ['forms', $url .'/elementor-forms-widget/', '', ''],
            'MailChimp' => ['mailchimp', $url .'/elementor-mailchimp-subscription-widget/', '', ''],
            'Content Ticker' => ['content-ticker', $url .'/elementor-content-ticker-widget/', '', ''],
            'Button' => ['button', $url .'/elementor-button-widget/', '', ''],
            'Dual Button' => ['dual-button', $url .'/elementor-button-widget/', '#dualbuttonsection', ''],
            'Team Member' => ['team-member', $url .'/elementor-team-member-widget/', '', ''],
            'Google Maps' => ['google-maps', $url .'/elementor-google-maps-widget/', '', ''],
            'Price List' => ['price-list', $url .'/elementor-price-list-widget/', '', ''],
            'Business Hours' => ['business-hours', $url .'/elementor-business-hours-widget/', '', ''],
            'Sharing Buttons' => ['sharing-buttons', $url .'/elementor-social-sharing-buttons-widget/', '', ''],
            'Search Form (Ajax)' => ['search', $url .'/elementor-search-widget/', '', ''],
            'Back to Top' => ['back-to-top', $url .'/elementor-back-to-top-widget/', '', ''],
            'Phone Call' => ['phone-call', $url .'/elementor-phone-call-widget/', '', ''],
            'Lottie Animations' => ['lottie-animations', $url .'/elementor-lottie-animation-widget/', '', ''],
            'Site Logo' => ['logo', '', '', ''],
            'Popup Trigger' => ['popup-trigger', '', '', ''],
            'Taxonomy List' => ['taxonomy-list', '', '', ''],
            'Page List' => ['page-list', '', '', ''],
            'Template' => ['elementor-template', '', '', ''],
            'Reading Progress Bar' => ['reading-progress-bar', $url .'/elementor-reading-progress-bar-widget/', '', ''],
            'Twitter Feed' => ['twitter-feed', $url .'/elementor-twitter-feed-widget/', '', ''],
            'Image Scroll' => ['image-scroll', $url .'/elementor-image-scroll-widget/', '', ''],
            'Date' => ['date', $url .'/elementor-date-widget/', '', ''],
            // 'Video Playlist' => ['video-playlist', $url .'/elementor-video-playlist-widget/', '', ''],
        ];
    }

    /**
     ** Get Enabled Modules
     */
    public static function get_available_modules( $modules ) {
        foreach ( $modules as $title => $data ) {
            $slug = $data[0];
            if ( 'on' !== get_option('crt-element-'. $slug, 'on') ) {
                unset($modules[$title]);
            }
        }

        return $modules;
    }

    /**
     ** Get Theme Builder Modules
     */
    public static function get_theme_builder_modules() {
        return [
            'Post Title' => ['post-title', '', '', ''],
            'Post Media' => ['post-media', '', '', ''],
            'Post Content' => ['post-content', '', '', ''],
            'Post Info' => ['post-info', '', '', ''],
            'Post Navigation' => ['post-navigation', '', '', ''],
            'Post Comments' => ['post-comments', '', '', ''],
            'Author Box' => ['author-box', '', '', ''],
            'Archive Title' => ['archive-title', '', '', ''],
        ];
    }

    /**
     ** Get WooCommerce Builder Modules
     */
    public static function get_woocommerce_builder_modules() {
        return [
            'Product Title' => ['product-title', '', '', ''],
            'Product Media' => ['product-media', '', '', ''],
            'Product Price' => ['product-price', '', '', ''],
            'Product Add to Cart' => ['product-add-to-cart', '', '', ''],
            'Product Tabs' => ['product-tabs', '', '', ''],
            'Product Excerpt' => ['product-excerpt', '', '', ''],
            'Product Description' => ['product-description', '', '', ''],
            'Product Rating' => ['product-rating', '', '', ''],
            'Product Meta' => ['product-meta', '', '', ''],
            'Product Sales Badge' => ['product-sales-badge', '', '', ''],
            'Product Stock' => ['product-stock', '', '', ''],
            'Product Additional Info' => ['product-additional-information', '', '', ''],
            'Product Notice' => ['product-notice', '', '', ''],
            'Product Mini Cart' => ['product-mini-cart', '', '', ''],
            'Page: Cart' => ['page-cart', '', '', ''],
            'Page: Checkout ' => ['page-checkout', '', '', ''],
        ];
    }

    /**
     ** Get Shop Page URL
     */
    public static function get_shop_url( $settings ) {
        global $wp;

        if ( '' == get_option('permalink_structure' ) ) {
            $url = remove_query_arg(array('page', 'paged'), add_query_arg($wp->query_string, '', home_url($wp->request)));
        } else {
            $url = preg_replace('%\/page/[0-9]+%', '', home_url(trailingslashit($wp->request)));
        }

        // CRT Filters
        $url = add_query_arg( 'crtfilters', '', $url );

        // Min/Max.
        if ( isset( $_GET['min_price'] ) ) {
            $url = add_query_arg( 'min_price', wc_clean( wp_unslash( $_GET['min_price'] ) ), $url );
        }

        if ( isset( $_GET['max_price'] ) ) {
            $url = add_query_arg( 'max_price', wc_clean( wp_unslash( $_GET['max_price'] ) ), $url );
        }

        // Search
        if ( isset( $_GET['psearch'] ) ) {
            $url = add_query_arg( 'psearch', wp_unslash( $_GET['psearch'] ), $url );
        }

        // Rating
        if ( isset( $_GET['filter_rating'] ) ) {
            $url = add_query_arg( 'filter_rating', wp_unslash( $_GET['filter_rating'] ), $url );
        }

        // Categories
        if ( isset( $_GET['filter_product_cat'] ) ) {
            $url = add_query_arg( 'filter_product_cat', wp_unslash( $_GET['filter_product_cat'] ), $url );
        }

        // Tags
        if ( isset( $_GET['filter_product_tag'] ) ) {
            $url = add_query_arg( 'filter_product_tag', wp_unslash( $_GET['filter_product_tag'] ), $url );
        }

        // All current filters.
        if ( $_chosen_attributes = WC()->query->get_layered_nav_chosen_attributes() ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.FoundInControlStructure, WordPress.CodeAnalysis.AssignmentInCondition.Found
            foreach ( $_chosen_attributes as $name => $data ) {
                $filter_name = wc_attribute_taxonomy_slug( $name );
                if ( ! empty( $data['terms'] ) ) {
                    $url = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $url );
                }

                if ( !empty($settings) ) {
                    if ( 'or' === $settings['tax_query_type'] || isset($_GET['query_type_' . $filter_name]) ) {
                        $url = add_query_arg( 'query_type_' . $filter_name, 'or', $url );
                    }
                }
            }
        }

        // Sorting
        if ( isset( $_GET['orderby'] ) ) {
            $url = add_query_arg( 'orderby', wp_unslash( $_GET['orderby'] ), $url );
        }

        // Fix URL
        // $url = str_replace( '%2C', ',', $url );

        return $url;
    }


    /**
     ** Get Available Custom Post Types or Taxonomies
     */
    public static function get_custom_types_of( $query, $exclude_defaults = true ) {
        // Taxonomies
        if ( 'tax' === $query ) {
            $custom_types = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

            // Post Types
        } else {
            $custom_types = get_post_types( [ 'show_in_nav_menus' => true ], 'objects' );
        }

        $custom_type_list = [];

        foreach ( $custom_types as $key => $value ) {
            if ( $exclude_defaults ) {
                if ( $key != 'post' && $key != 'page' && $key != 'category' && $key != 'post_tag' ) {
                    $custom_type_list[ $key ] = $value->label;
                }
            } else {
                $custom_type_list[ $key ] = $value->label;
            }
        }

        return $custom_type_list;
    }


    /**
     ** Get Available WooCommerce Taxonomies
     */
    public static function get_woo_taxonomies() {
        $taxonomy_list = [];

        foreach ( get_object_taxonomies( 'product' ) as $taxonomy_data ) {
            $taxonomy = get_taxonomy( $taxonomy_data );
            if( $taxonomy->show_ui ) {
                $taxonomy_list[ $taxonomy_data ] = $taxonomy->label;
            }
        }

        return $taxonomy_list;
    }


    /**
     ** Get All Users
     */
    public static function get_users() {
        $users = [];

        if ( is_admin() ) {
            foreach ( get_users() as $key => $user ) {
                $users[$user->data->ID] = $user->data->user_nicename;
            }

            wp_reset_postdata();
        }

        return $users;
    }


    /**
     ** Get User Roles
     */
    public static function get_user_roles() {
        if ( ! function_exists( 'get_editable_roles' ) ) {
            require_once ABSPATH . 'wp-admin/includes/user.php';
        }

        $r = [];

        $editable_roles = array_reverse( get_editable_roles() );

        $r['guest'] = esc_html__( 'Guest', 'crt-manage' );

        foreach ( $editable_roles as $role => $details ) {
            $r[ $role ] = translate_user_role( $details['name'] );
        }

        return $r;
    }


    /**
     ** Get Terms of Taxonomy
     */
    public static function get_terms_by_taxonomy( $slug ) {
        if ( ( 'product_cat' === $slug || 'product_tag' === $slug ) && ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        $query = get_terms( $slug, [ 'hide_empty' => false, 'posts_per_page' => -1 ] );
        $taxonomies = [];

        foreach ( $query as $tax ) {
            $taxonomies[$tax->term_id] = $tax->name;
        }

        wp_reset_postdata();

        return $taxonomies;
    }


    /**
     ** Get Posts of Post Type
     */
    public static function get_posts_by_post_type( $slug ) {
        $posts = [];

        if ( is_admin() ) {
            $query = get_posts( [ 'post_type' => $slug, 'posts_per_page' => -1 ] );

            foreach ( $query as $post ) {
                $posts[$post->ID] = $post->post_title;
            }

            wp_reset_postdata();
        }

        return $posts;
    }


    /**
     ** Get Library Template ID
     */
    public static function get_template_id( $slug ) {

        $template = get_page_by_path( $slug, OBJECT, 'crt_templates' );

        return isset( $template->ID ) ? $template->ID : false;
    }

    /**
     ** Check Single Conditions Array
     */
    public static function check_id_in_path($path, $id) {
        // Step 1: Remove any prefix up to and including the last slash if present
        $last_slash_position = strrpos($path, '/');
        $numeric_part = $last_slash_position !== false ? substr($path, $last_slash_position + 1) : $path;

        // Step 2: Extract numbers (assuming they are separated by commas or spaces)
        preg_match_all('/\d+/', $numeric_part, $matches);

        // The numbers are now in $matches[0] as an array
        $ids = $matches[0];

        // Step 3: Check if the specific $id is in the array of IDs
        return in_array($id, $ids);
    }

    /**
     ** Get Library Template Slug
     */
    public static function get_template_slug( $data, $page, $post_id = '' ) {
        $archive = explode('/', $page);
        $template_id = Utilities::crt_get_post_by_meta_key('crt_manage_archive', 'crt-archive-all');
        if(isset($archive[1])) {
            if($archive[0] == 'single') {
                $template_id = Utilities::crt_get_post_by_meta_key('crt_manage_archive', 'crt-' . $archive[1]);
            } elseif($archive[0] == 'product_single') {
                $template_id = Utilities::crt_get_post_by_meta_key('crt_manage_archive', 'crt-' . $archive[1]);
            } else {
                $template_id = Utilities::crt_get_post_by_meta_key('crt_manage_archive', 'crt-archive-' . $archive[1]);
            }
            return $template_id;
        }
        return $template_id;
    }


    /**
     ** Get Elementor Template Type
     */
    public static function get_elementor_template_type( $id ) {
        $post_meta = get_post_meta($id);
        $template_type = isset($post_meta['_elementor_template_type'][0]) ? $post_meta['_elementor_template_type'][0] : false;

        return $template_type;
    }


    /**
     ** Get CRThemes Template Type
     */
    public static function get_crt_template_type( $id ) {
        $post_meta = get_post_meta($id);
        $template_type = isset($post_meta['_crt_template_type'][0]) ? $post_meta['_crt_template_type'][0] : false;

        return $template_type;
    }


    /**
     ** Theme Builder Show Widgets on Spacific Pages
     */
    public static function show_theme_buider_widget_on( $type ) {
        global $post;
        $display = false;

        if ( Utilities::is_theme_builder_template() ) {
            $template_type = Utilities::get_crt_template_type(get_the_ID());

            if ( $type === $template_type ) {
                $display = true;
            }

            $conditions = json_decode(get_option('crt_single_conditions'));
            $front_page = Utilities::get_template_slug($conditions, 'single/front_page', get_the_ID());
            $page_404 = Utilities::get_template_slug($conditions, 'single/page_404', get_the_ID());

            if ( $post->post_name == $front_page || $post->post_name == $page_404 ) {
                $display = false;
            }
        }

        return $display;
    }


    /**
     ** Render Elementor Template
     */
    public static function render_elementor_template( $slug ) {
        $template_id = Utilities::get_template_id( $slug );
        $type = get_post_meta(get_the_ID(), '_crt_template_type', true) || get_post_meta($template_id, '_elementor_template_type', true);
        $has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

        $get_elementor_content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id, $has_css);

        if ( '' === $get_elementor_content ) {
            return;
        }

        // Render Elementor Template Content
        echo ''. $get_elementor_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     ** Render Elementor Template
     */
    public static function render_elementor_template_id( $template_id ) {
        $has_css = true;
        $get_elementor_content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id, $has_css);

        if ( '' === $get_elementor_content ) {
            return;
        }

        // Render Elementor Template Content
        echo ''. $get_elementor_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }


    /**
     ** Theme Builder Template Check
     */
    public static function is_theme_builder_template() {
        $current_page = get_post(get_the_ID());

        if ( $current_page ) {
            return strpos($current_page->post_name, 'user-archive') !== false || strpos($current_page->post_name, 'user-single') !== false || strpos($current_page->post_name, 'user-product') !== false;
        } else {
            return false;
        }
    }


    /**
     ** Blog Archive Page Check
     */
    public static function is_blog_archive() {
        $result = false;
        $front_page = get_option( 'page_on_front' );
        $posts_page = get_option( 'page_for_posts' );

        if ( is_home() && '0' === $front_page && '0' === $posts_page || (intval($posts_page) === get_queried_object_id() && !is_404()) ) {
            $result = true;
        }

        return $result;
    }

    /**
     ** Disable Extra Image Sizes
     */
    public static function disable_extra_image_sizes( $new_sizes, $image_meta, $attachment_id ) {
        $all_attachments = get_option( 'st_attachments', array() );

        // If the cron job is already scheduled, bail.
        if ( in_array( $attachment_id, $all_attachments, true ) ) {
            return $new_sizes;
        }

        $all_attachments[] = $attachment_id;

        update_option( 'st_attachments', $all_attachments, 'no' );

        // Return blank array of sizes to not generate any sizes in this request.
        return array();
    }

    /**
     ** Regenerate Extra Image Sizes
     */
    public static function regenerate_extra_image_sizes() {
        $all_attachments = get_option( 'st_attachments', array() );

        if ( empty( $all_attachments ) ) {
            return;
        }

        foreach ( $all_attachments as $attachment_id ) {
            $file = get_attached_file( $attachment_id );
            if ( false !== $file ) {
                wp_generate_attachment_metadata( $attachment_id, $file );
            }
        }
        update_option( 'st_attachments', array(), 'no' );
    }

    // Get Post Sharing Icon
    public static function get_post_sharing_icon( $args = [] ) {

        $args['url'] = esc_url($args['url']);

        if ( 'facebook-f' === $args['network'] ) {
            $sharing_url = 'https://www.facebook.com/sharer.php?u='. $args['url'];
            $network_title = esc_html__( 'Facebook', 'crt-manage' );
        } elseif ( 'twitter' === $args['network'] ) {
            $sharing_url = 'https://twitter.com/intent/tweet?url='. $args['url'];
            $network_title = esc_html__( 'Twitter', 'crt-manage' );
        } elseif ( 'linkedin-in' === $args['network'] ) {
            $sharing_url = 'https://www.linkedin.com/shareArticle?mini=true&url='. $args['url'] .'&title='. $args['title'] .'&summary='. $args['text'] .'&source='. $args['url'];
            $network_title = esc_html__( 'LinkedIn', 'crt-manage' );
        } elseif ( 'pinterest-p' === $args['network'] ) {
            // $sharing_url = 'https://www.pinterest.com/pin/find/?url='. $args['url'];
            $sharing_url = 'https://www.pinterest.com/pin/create/button/?url='. $args['url'] .'&media='. $args['image'];
            $network_title = esc_html__( 'Pinterest', 'crt-manage' );
        } elseif ( 'reddit' === $args['network'] ) {
            $sharing_url = 'https://reddit.com/submit?url='. $args['url'] .'&title='. $args['title'];
            $network_title = esc_html__( 'Reddit', 'crt-manage' );
        } elseif ( 'tumblr' === $args['network'] ) {
            $sharing_url = 'https://tumblr.com/share/link?url='. $args['url'];
            $network_title = esc_html__( 'Tumblr', 'crt-manage' );
        } elseif ( 'digg' === $args['network'] ) {
            $sharing_url = 'https://digg.com/submit?url='. $args['url'];
            $network_title = esc_html__( 'Digg', 'crt-manage' );
        } elseif ( 'xing' === $args['network'] ) {
            $sharing_url = 'https://www.xing.com/app/user?op=share&url='. $args['url'];
            $network_title = esc_html__( 'Xing', 'crt-manage' );
        } elseif ( 'stumbleupon' === $args['network'] ) {
            $sharing_url = 'https://www.stumbleupon.com/submit?url='. $args['url'];
            $network_title = esc_html__( 'StumpleUpon', 'crt-manage' );
        } elseif ( 'vk' === $args['network'] ) {
            $sharing_url = 'https://vkontakte.ru/share.php?url='. $args['url'] .'&title='. $args['title'] .'&description='. wp_trim_words( $args['text'], 250 ) .'&image='. $args['image'] .'/';
            $network_title = esc_html__( 'vKontakte', 'crt-manage' );
        } elseif ( 'odnoklassniki' === $args['network'] ) {
            $sharing_url = 'http://odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl='. $args['url'];
            $network_title = esc_html__( 'OdnoKlassniki', 'crt-manage' );
        } elseif ( 'get-pocket' === $args['network'] ) {
            $sharing_url = 'https://getpocket.com/edit?url='. $args['url'];
            $network_title = esc_html__( 'Pocket', 'crt-manage' );
        } elseif ( 'skype' === $args['network'] ) {
            $sharing_url = 'https://web.skype.com/share?url='. $args['url'];
            $network_title = esc_html__( 'Skype', 'crt-manage' );
        } elseif ( 'whatsapp' === $args['network'] ) {
            if ( 'yes' === $args['show_whatsapp_title'] && 'yes' == $args['show_whatsapp_excerpt'] ) {
                $sharing_url = 'https://api.whatsapp.com/send?text=*'. $args['title'] .'*%0a'. wp_strip_all_tags($args['text']) .'%0a'. $args['url'];
            } else if ( 'yes' === $args['show_whatsapp_title'] ) {
                $sharing_url = 'https://api.whatsapp.com/send?text=*'. $args['title'] .'*%0a'. $args['url'];
            } else if ( 'yes' === $args['show_whatsapp_excerpt'] ) {
                $sharing_url = 'https://api.whatsapp.com/send?text=*'. wp_strip_all_tags($args['text']) .'%0a'. $args['url'];
            } else {
                $sharing_url = 'https://api.whatsapp.com/send?text='. $args['url'];
            }
            $network_title = esc_html__( 'WhatsApp', 'crt-manage' );
        } elseif ( 'telegram' === $args['network'] ) {
            $sharing_url = 'https://telegram.me/share/url?url='. $args['url'] .'&text='. $args['text'];
            $network_title = esc_html__( 'Telegram', 'crt-manage' );
        } elseif ( 'delicious' === $args['network'] ) {
            $sharing_url = 'https://del.icio.us/save?url='. $args['url'] .'&title={title}';
            $network_title = esc_html__( 'Delicious', 'crt-manage' );
        } elseif ( 'envelope' === $args['network'] ) {
            $sharing_url = 'mailto:?subject='. $args['title'] .'&body='. $args['url'];
            $network_title = esc_html__( 'Email', 'crt-manage' );
        } elseif ( 'print' === $args['network'] ) {
            $sharing_url = 'javascript:window.print()';
            $network_title = esc_html__( 'Print', 'crt-manage' );
        } else {
            $sharing_url = '';
            $network_title = '';
        }

        $sharing_url = 'print' === $args['network'] ? $sharing_url : $sharing_url;

        $output = '';

        if ( '' !== $network_title ) {
            $output .= '<a href="'. $sharing_url .'" class="crt-sharing-icon crt-sharing-'. esc_attr( $args['network'] ) .'" title="" target="_blank">';
            // Tooltip
            $output .= 'yes' === $args['tooltip'] ? '<span class="crt-sharing-tooltip crt-tooltip">'. esc_html( $network_title ) .'</span>' : '';

            // Category
            if ( 'envelope' === $args['network'] || 'print' === $args['network'] ) {
                $category = 'fas';
            } else {
                $category = 'fab';
            }

            // Icon
            if ( 'yes' === $args['icons'] ) {
                $output .= '<i class="'. esc_attr($category) .' fa-'. esc_attr( $args['network'] ) .'"></i>';
            }

            // Label
            if ( isset( $args['labels'] ) && 'yes' === $args['labels'] ) {
                $label = isset( $args['custom_label'] ) && '' !== $args['custom_label'] ? $args['custom_label'] :  $network_title;
                $output .= '<span class="crt-sharing-label">'. esc_html( $label ) .'</span>';
            }
            $output .= '</a>';
        }

        return $output;
    }


    /**
     ** Filter oEmbed Results
     */
    public static function filter_oembed_results( $html ) {
        // Filter
        preg_match( '/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $html, $matches );

        // Return URL
        return  $matches[1] .'&auto_play=true';
    }


    /**
     ** Get Post Custom Meta Keys
     */
    public static function get_custom_meta_keys() { // needs ajaxifying
        $data = [];
        $options = [];
        $merged_meta_keys = [];
        $post_types = Utilities::get_custom_types_of( 'post', false );

        foreach ( $post_types as $post_type_slug => $post_type_name ) {
            $data[ $post_type_slug ] = [];
            $posts = get_posts( [ 'post_type' => $post_type_slug, 'posts_per_page' => -1 ] );

            foreach (  $posts as $key => $post ) {
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
            $options[ $merged_meta_keys[$i] ] = $merged_meta_keys[$i];
        }

        return [ $data, $options ];
    }


    /**
     ** Get Taxonomy Custom Meta Keys
     */
    public static function get_custom_meta_keys_tax() { // needs ajaxifying
        $data = [];
        $options = [];
        $merged_meta_keys = [];
        $tax_types = Utilities::get_custom_types_of( 'tax', false );

        foreach ( $tax_types as $taxonomy_slug => $post_type_name ) {
            $data[ $taxonomy_slug ] = [];
            $taxonomies = get_terms( $taxonomy_slug );

            foreach (  $taxonomies as $key => $tax ) {
                $meta_keys = get_term_meta( $tax->term_id );
                $meta_keys = array_keys($meta_keys);

                if ( ! empty($meta_keys) ) {
                    for ( $i = 0; $i < count( $meta_keys ); $i++ ) {
                        if ( '_' !== substr( $meta_keys[$i], 0, 1 ) ) {
                            array_push( $data[$taxonomy_slug], $meta_keys[$i] );
                        }
                    }
                }
            }

            $data[ $taxonomy_slug ] = array_unique( $data[ $taxonomy_slug ] );
        }

        foreach ( $data as $array ) {
            $merged_meta_keys = array_unique( array_merge( $merged_meta_keys, $array ) );
        }

        // Rekey
        $merged_meta_keys = array_values($merged_meta_keys);

        for ( $i = 0; $i < count( $merged_meta_keys ); $i++ ) {
            $options[ $merged_meta_keys[$i] ] = $merged_meta_keys[$i];
        }

        return [ $data, $options ];
    }


    /**
     ** Get SVG Icons Array
     */
    public static function get_svg_icons_array( $stack, $fa_icons ) {
        $svg_icons = [];

        if ( 'arrows' === $stack ) {
            $svg_icons['svg-angle-1-left'] = esc_html__( 'Angle', 'crt-manage' );
            $svg_icons['svg-angle-2-left'] = esc_html__( 'Angle Bold', 'crt-manage' );
            $svg_icons['svg-angle-3-left'] = esc_html__( 'Angle Bold Round', 'crt-manage' );
            $svg_icons['svg-angle-4-left'] = esc_html__( 'Angle Plane', 'crt-manage' );
            $svg_icons['svg-arrow-1-left'] = esc_html__( 'Arrow', 'crt-manage' );
            $svg_icons['svg-arrow-2-left'] = esc_html__( 'Arrow Bold', 'crt-manage' );
            $svg_icons['svg-arrow-3-left'] = esc_html__( 'Arrow Bold Round', 'crt-manage' );
            $svg_icons['svg-arrow-4-left'] = esc_html__( 'Arrow Caret', 'crt-manage' );

        } elseif ( 'blockquote' === $stack ) {
            $svg_icons['svg-blockquote-1'] = esc_html__( 'Blockquote Round', 'crt-manage' );
            $svg_icons['svg-blockquote-2'] = esc_html__( 'Blockquote ST', 'crt-manage' );
            $svg_icons['svg-blockquote-3'] = esc_html__( 'Blockquote BS', 'crt-manage' );
            $svg_icons['svg-blockquote-4'] = esc_html__( 'Blockquote Edges', 'crt-manage' );
            $svg_icons['svg-blockquote-5'] = esc_html__( 'Blockquote Quad', 'crt-manage' );

        } elseif ( 'sharing' === $stack ) {
            // $svg_icons['svg-sharing-1'] = esc_html__( 'sharing 1', 'crt-manage' );
            // $svg_icons['svg-sharing-2'] = esc_html__( 'sharing 2', 'crt-manage' );
        }

        // Merge FontAwesome and SVG icons
        return array_merge( $fa_icons, $svg_icons );
    }


    /**
     ** Get SVG Icon
     */
    public static function get_svg_icon( $icon, $dir ) {
        $style_attr = '';

        // Rotate Right
        if ( 'right' === $dir ) {
            $style_attr = 'style="transform: rotate(180deg); -webkit-transform: rotate(180deg);" ';
        }

        $icons = [
            // Arrows
            'svg-angle-1-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 283.4 512" style="enable-background:new 0 0 283.4 512;" xml:space="preserve"><g><polygon class="st0" points="54.5,256.3 283.4,485.1 256.1,512.5 0,256.3 0,256.3 27.2,229 256.1,0 283.4,27.4 "/></g></svg>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            'svg-angle-2-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 303.3 512" style="enable-background:new 0 0 303.3 512;" xml:space="preserve"><g><polygon class="st0" points="94.7,256 303.3,464.6 256,512 47.3,303.4 0,256 47.3,208.6 256,0 303.3,47.4 "/></g></svg>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            'svg-angle-3-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 291.4 512" style="enable-background:new 0 0 291.4 512;" xml:space="preserve"><g><path class="st0" d="M281.1,451.5c13.8,13.8,13.8,36.3,0,50.1c-13.8,13.8-36.3,13.8-50.1,0L10.4,281C3.5,274.1,0,265.1,0,256c0-9.1,3.5-18.1,10.4-25L231,10.4c13.8-13.8,36.3-13.8,50.1,0c6.9,6.9,10.4,16,10.4,25s-3.5,18.1-10.4,25L85.5,256L281.1,451.5z"/></g></svg>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            'svg-angle-4-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 259.6 512" style="enable-background:new 0 0 259.6 512;" xml:space="preserve"><g><path class="st0" d="M256.6,18.1L126.2,256.1l130.6,237.6c3.6,5.6,3.9,10.8,0.2,14.9c-0.2,0.2-0.2,0.3-0.3,0.3s-0.3,0.3-0.3,0.3c-3.9,3.9-10.3,3.6-14.2-0.3L2.9,263.6c-2-2.1-3.1-4.7-2.9-7.5c0-2.8,1-5.6,3.1-7.7L242,3.1c4.1-4.1,10.6-4.1,14.6,0l0,0C260.7,7.3,260.5,10.9,256.6,18.1z"/></g></svg>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            'svg-arrow-1-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 338.4" style="enable-background:new 0 0 512 338.4;" xml:space="preserve"><g><polygon class="st0" points="511.4,183.1 53.4,183.1 188.9,318.7 169.2,338.4 0,169.2 169.2,0 188.9,19.7 53.4,155.3 511.4,155.3 "/></g></svg>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            'svg-arrow-2-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 320.6" style="enable-background:new 0 0 512 320.6;" xml:space="preserve"><g><polygon class="st0" points="512,184.4 92.7,184.4 194.7,286.4 160.5,320.6 34.3,194.4 34.3,194.4 0,160.2 160.4,0 194.5,34.2 92.7,136 512,136 "/></g></svg>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            'svg-arrow-3-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 499.6 320.6" style="enable-background:new 0 0 499.6 320.6;" xml:space="preserve"><g><path class="st0" d="M499.6,159.3c0.3,7-2.4,13.2-7,17.9c-4.3,4.3-10.4,7-16.9,7H81.6l95.6,95.6c9.3,9.3,9.3,24.4,0,33.8c-4.6,4.6-10.8,7-16.9,7c-6.1,0-12.3-2.4-16.9-7L6.9,177.2c-9.3-9.3-9.3-24.4,0-33.8l16.9-16.9l0,0L143.3,6.9c9.3-9.3,24.4-9.3,33.8,0c4.6,4.6,7,10.8,7,16.9s-2.4,12.3-7,16.9l-95.6,95.6h393.7C488.3,136.3,499.1,146.4,499.6,159.3z"/></g></svg>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            'svg-arrow-4-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 499.6 201.3" style="enable-background:new 0 0 499.6 201.3;" xml:space="preserve"><g><polygon class="st0" points="0,101.1 126,0 126,81.6 499.6,81.6 499.6,120.8 126,120.8 126,201.3 "/></g></svg>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

            // Blockquote
            'svg-blockquote-1' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 406.1" style="enable-background:new 0 0 512 406.1;" xml:space="preserve"><g><g id="Layer_2_1_" class="st0"><path class="st1" d="M510.6,301.8c0,57.6-46.7,104.3-104.3,104.3c-12.6,0-24.7-2.3-36-6.4c-28.3-9.1-64.7-29.1-82.8-76.3C218.9,145.3,477.7,0.1,477.7,0.1l6.4,12.3c0,0-152.4,85.7-132.8,200.8C421.8,170.3,510.1,220.2,510.6,301.8z"/><path class="st1" d="M234.6,301.8c0,57.6-46.7,104.3-104.3,104.3c-12.6,0-24.7-2.3-36-6.4c-28.3-9.1-64.7-29.1-82.8-76.3C-57.1,145.3,201.8,0.1,201.8,0.1l6.4,12.3c0,0-152.4,85.7-132.8,200.8C145.9,170.3,234.1,220.2,234.6,301.8z"/></g></g></svg>',
            'svg-blockquote-2' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 415.9" style="enable-background:new 0 0 512 415.9;" xml:space="preserve"><g><g class="st0"><polygon class="st1" points="512,0 303.1,208 303.1,415.9 512,415.9 "/><polygon class="st1" points="208.9,0 0,208 0,415.9 208.9,415.9 "/></g></g></svg>',
            'svg-blockquote-3' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 369.3" style="enable-background:new 0 0 512 369.3;" xml:space="preserve"><g><g class="st0"><polygon class="st1" points="240.7,0 240.7,240.5 88.1,369.3 88.1,328.3 131.4,240.5 0.3,240.5 0.3,0 "/><polygon class="st1" points="512,43.3 512,238.6 388.1,343.2 388.1,310 423.2,238.6 316.7,238.6 316.7,43.3 "/></g></g></svg>',
            'svg-blockquote-4' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 369.3" style="enable-background:new 0 0 512 369.3;" xml:space="preserve"><g><g class="st0"><g><path class="st1" d="M469.1,299.1c-62,79.7-148.7,69.8-148.7,69.8v-86.5c0,0,42.6-0.6,77.5-35.4c20.3-20.3,22.7-65.6,22.8-81.4h-101V-10.9H512v176.6C512.2,184.7,509.4,247.2,469.1,299.1z"/></g><g><path class="st1" d="M149.3,299.1c-62,79.7-148.7,69.8-148.7,69.8v-86.5c0,0,42.6-0.6,77.5-35.4c20.3-20.3,22.7-65.6,22.8-81.4H0V-10.9h192.2v176.6C192.4,184.7,189.7,247.2,149.3,299.1z"/></g></g></g></svg>',
            'svg-blockquote-5' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 422.1" style="enable-background:new 0 0 512 422.1;" xml:space="preserve"><g><g class="st0"><polygon class="st1" points="237,0 237,223.7 169.3,422.1 25.7,422.1 53.4,223.7 0,223.7 0,0 "/><polygon class="st1" points="512,0 512,223.7 444.3,422.1 300.7,422.1 328.4,223.7 275,223.7 275,0 "/></g></g></svg>',

            // Sharing
            'svg-sharing-1' => '<?xml version="1.0" ?><svg style="enable-background:new 0 0 48 48;" version="1.1" viewBox="0 0 48 48" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Icons"><g id="Icons_15_"><g><path d="M25.03766,20.73608v-3.7207c0-0.3799,0.4135-0.6034,0.7263-0.4023l9.3855,5.9218     c0.3017,0.19,0.3017,0.6146,0,0.8045l-5.1844,3.2738l-1.8659,1.1843l-2.3352,1.4749c-0.3129,0.2011-0.7263-0.0335-0.7263-0.4022     v-3.2403v-0.4916" style="fill:#5F83CF;"/><path d="M29.96506,26.61318l-1.8659,1.1843l-2.3352,1.4749c-0.3128,0.2011-0.7263-0.0335-0.7263-0.4022     v-3.2403v-0.4916c-2.5759,0.1057-5.718-0.3578-7.8439,0.6112c-1.9663,0.8963-3.5457,2.5639-4.2666,4.6015     c-0.1282,0.3623-0.2296,0.7341-0.3029,1.1114v-2.9721c0-1.128,0.2449-2.2513,0.7168-3.2759     c0.4588-0.9961,1.1271-1.8927,1.948-2.6196c0.8249-0.7306,1.8013-1.2869,2.8523-1.6189     c1.5111-0.4774,3.1532-0.4118,4.7155-0.3096c0.7252,0.0475,1.4538,0.0698,2.1808,0.0698" style="fill:#5F83CF;"/></g></g></g></svg>',
            'svg-sharing-2' => '<?xml version="1.0" ?><svg style="enable-background:new 0 0 48 48;" version="1.1" viewBox="0 0 48 48" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Icons"><g id="Icons_16_"><g><path d="M27.775,21.64385L27.775,21.64385l1-0.01h1v1.65l2.17-1.38l0.1-0.06l2.95-1.87l-5.22-3.29v0.87     v0.77h-1l-1-0.02l0,0" style="fill:#5F83CF;"/><path d="M28.775,18.32385c-0.33,0-0.67-0.01-1-0.02c-0.22-0.01-0.43-0.02-0.65-0.04     c-1.3358-0.0496-2.5105-0.0408-3.55,0.24c-0.5,0.16-0.97,0.38-1.41,0.67c-0.26,0.16-0.51,0.34-0.74,0.55     c-0.62,0.54-1.12,1.22-1.47,1.97c-0.35,0.77-0.54,1.62-0.54,2.47v2.24c0.06-0.29,0.13-0.57,0.23-0.84     c0.54-1.53,1.73-2.79,3.22-3.47c1.34-0.61,3.21-0.47,4.91-0.45c0.35,0,0.68,0,1-0.01" style="fill:#5F83CF;"/><path d="M31.945,23.63175l-1.8884,1.1873v3.8702c0,0.5422-0.5142,0.991-1.1499,0.991H16.0432     c-0.6357,0-1.1498-0.4488-1.1498-0.991v-8.7689c0-0.5515,0.5142-1.0002,1.1498-1.0002h3.5525h0.0037     c0.0561-0.0748,0.1739-0.2057,0.2393-0.2618c0.6731-0.5983,1.4864-1.0657,2.3465-1.3368     c0.0467-0.0187,0.0935-0.0281,0.1402-0.0374h-6.2821c-1.6734,0-3.0383,1.1872-3.0383,2.6362v8.7689     c0,1.449,1.3649,2.6269,3.0383,2.6269h12.8634c1.6734,0,3.0383-1.1779,3.0383-2.6269V23.63175z" style="fill:#F2F2F2;"/></g></g></g></svg>',

        ];

        return $icons[$icon];
    }


    /**
     ** Get CRT Icon
     */
    public static function get_crt_icon( $icon, $dir ) {
        if ( !empty($icon) ) {
            if ( false !== strpos( $icon, 'svg-' ) ) {
                return Utilities::get_svg_icon( $icon, $dir );

            } elseif ( false !== strpos( $icon, 'fa-' ) ) {
                $dir = '' !== $dir ? '-'. $dir : '';
                return wp_kses('<i class="'. esc_attr($icon . $dir) .'"></i>', [
                    'i' => [
                        'class' => []
                    ]
                ]);
            } else {
                return '';
            }
        }
    }


    /**
     ** Mailchimp AJAX Subscribe
     */
    public static function ajax_mailchimp_subscribe() {
        // API Key
        $api_key = !empty(get_option('crt_mailchimp_api_key')) && false != get_option('crt_mailchimp_api_key') ? get_option('crt_mailchimp_api_key') : ''; // GOGA

        $api_key_sufix = explode( '-', $api_key )[1];

        // List ID
        $list_id = isset($_POST['listId']) ? sanitize_text_field(wp_unslash($_POST['listId'])) : '';

        // Get Available Fileds (PHPCS - fields are sanitized later on input)
        $available_fields = isset($_POST['fields']) ? $_POST['fields'] : []; // phpcs:ignore
        wp_parse_str( $available_fields, $fields );

        // Merge Additional Fields
        $merge_fields = array(
            'FNAME' => !empty( $fields['crt_mailchimp_firstname'] ) ? sanitize_text_field($fields['crt_mailchimp_firstname']) : '',
            'LNAME' => !empty( $fields['crt_mailchimp_lastname'] ) ? sanitize_text_field($fields['crt_mailchimp_lastname']) : '',
            'PHONE' => !empty ( $fields['crt_mailchimp_phone_number'] ) ? sanitize_text_field($fields['crt_mailchimp_phone_number']) : '',
        );

        // API URL
        $api_url = 'https://'. $api_key_sufix .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members/'. md5(strtolower(sanitize_text_field($fields['crt_mailchimp_email'])));

        // API Args
        $api_args = [
            'method' => 'PUT',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'apikey '. $api_key,
            ],
            'body' => json_encode([
                'email_address' => sanitize_text_field($fields[ 'crt_mailchimp_email' ]),
                'status' => 'subscribed',
                'merge_fields' => $merge_fields,
            ]),
        ];

        // Send Request
        $request = wp_remote_post( $api_url, $api_args );

        if ( ! is_wp_error($request) ) {
            $request = json_decode( wp_remote_retrieve_body($request) );

            // Set Status
            if ( ! empty($request) ) {
                if ($request->status == 'subscribed') {
                    wp_send_json([ 'status' => 'subscribed' ]);
                } else {
                    wp_send_json([ 'status' => $request->title ]);
                }
            }
        }
    }

    /**
     ** Mailchimp - Get Lists
     */
    public static function get_mailchimp_lists() {
        $api_key = get_option('crt_mailchimp_api_key', '');

        $mailchimp_list = [
            'def' => esc_html__( 'Select List', 'crt-manage' )
        ];

        if ( '' === $api_key ) {
            return $mailchimp_list;
        } else {
            $url = 'https://'. substr( $api_key, strpos( $api_key, '-' ) + 1 ) .'.api.mailchimp.com/3.0/lists/';
            $args = [ 'headers' => [ 'Authorization' => 'Basic ' . base64_encode( 'user:'. $api_key ) ] ];

            $response = wp_remote_get( $url, $args );

            if ( !is_wp_error($response) ) {
                $body = json_decode($response['body']);

                if ( ! empty( $body->lists ) ) {
                    foreach ( $body->lists as $list ) {
                        $mailchimp_list[$list->id] = $list->name .' ('. $list->stats->member_count .')';
                    }
                }
            }

            return $mailchimp_list;
        }
    }

    // Needs further logic
    public static function get_mailchimp_groups() {
        $groups_array = ['def' => 'Select Group'];
        foreach (self::get_mailchimp_lists() as $key => $value ) {
            if ( 'def' === $key ) {
                continue;
            }
            $audience = $key; // How to get settin
            $api_key = get_option('crt_mailchimp_api_key');
            $url = 'https://'. substr( $api_key, strpos( $api_key, '-' ) + 1 ) .'.api.mailchimp.com/3.0/lists/'.$audience.'/interest-categories';
            $args = [ 'headers' => [ 'Authorization' => 'Basic ' . base64_encode( 'user:'. $api_key ) ] ];

            $response = wp_remote_get( $url, $args );

            foreach ( json_decode($response['body'])->categories as $key => $value ) {
                $group_name = $value->title;
                $group_id = $value->id;
                $url = 'https://'. substr( $api_key, strpos( $api_key, '-' ) + 1 ) .'.api.mailchimp.com/3.0/lists/'.$audience.'/interest-categories/'. $value->id .'/interests';
                $args = [ 'headers' => [ 'Authorization' => 'Basic ' . base64_encode( 'user:'. $api_key ) ] ];

                $response = wp_remote_get( $url, $args );

                foreach (json_decode($response['body'])->interests as $key => $value ) {
                    // var_dump($group_name, $group_id);
                    // var_dump($group_name, $group_id, $value->id, $value->name);
                    $groups_array[$value->id] = $value->name;
                }
            }
        }

        return $groups_array;
    }

    /**
     ** CRT Animation Timings
     */
    public static function crt_animation_timings() {
        $timing_functions = [
            'ease-default' => 'Default',
            'linear' => 'Linear',
            'ease-in' => 'Ease In',
            'ease-out' => 'Ease Out',
            'ease-in-out' => 'Ease In Out',
            'ease-in-quad' => 'Ease In Quad',
            'ease-in-cubic' => 'Ease In Cubic',
            'ease-in-quart' => 'Ease In Quart',
            'ease-in-quint' => 'Ease In Quint',
            'ease-in-sine' => 'Ease In Sine',
            'ease-in-expo' => 'Ease In Expo',
            'ease-in-circ' => 'Ease In Circ',
            'ease-in-back' => 'Ease In Back',
            'ease-out-quad' => 'Ease Out Quad',
            'ease-out-cubic' => 'Ease Out Cubic',
            'ease-out-quart' => 'Ease Out Quart',
            'ease-out-quint' => 'Ease Out Quint',
            'ease-out-sine' => 'Ease Out Sine',
            'ease-out-expo' => 'Ease Out Expo',
            'ease-out-circ' => 'Ease Out Circ',
            'ease-out-back' => 'Ease Out Back',
            'ease-in-out-quad' => 'Ease In Out Quad',
            'ease-in-out-cubic' => 'Ease In Out Cubic',
            'ease-in-out-quart' => 'Ease In Out Quart',
            'ease-in-out-quint' => 'Ease In Out Quint',
            'ease-in-out-sine' => 'Ease In Out Sine',
            'ease-in-out-expo' => 'Ease In Out Expo',
            'ease-in-out-circ' => 'Ease In Out Circ',
            'ease-in-out-back' => 'Ease In Out Back',
        ];

        return $timing_functions;
    }

    public static function crt_animation_timing_pro_conditions() {
        return ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk'];
    }

    /**
     ** CRT Library Button
     */
    public static function crt_library_buttons( $module, $controls_manager, $tutorial_url = '' ) {
        if ( empty(get_option('crt_wl_plugin_links')) ) {
//            if ( '' !== $tutorial_url ) {
//                $tutorial_link = '<a href="'. esc_url($tutorial_url) .'" target="_blank">'. esc_html__( 'Watch Video Tutorial ', 'crt-manage' ) .'<span class="dashicons dashicons-video-alt3"></span></a>';
//            } else {
//                $tutorial_link = '';
//            }
//
//            $module->add_control(
//                'crt_library_buttons',
//                [
//                    'raw' => '<div class='. $module->get_name() .'><a href="#" target="_blank" data-theme="'. esc_attr(get_template()) .'">'. esc_html__( 'Widget Preview', 'crt-manage' ) .'</a> <a href="#">'. esc_html__( 'Predefined Styles', 'crt-manage' ) .'</a></div>'. $tutorial_link,
//                    'type' => $controls_manager,
//                ]
//            );
        }
    }

    /**
     ** Upgrade to Pro Notice
     */
    public static function upgrade_pro_notice( $module, $controls_manager, $widget, $option, $condition = [] ) {
        if ( defined('CRT_ADDONS_PRO_VERSION') ) {
            return;
        }

        $url = 'https://crthemes.com/contact';
        $module->add_control(
            $option .'_pro_notice',
            [
                'raw' => 'This option is available<br> in the <strong><a href="'. $url .'" target="_blank">Pro version</a></strong> and above.',
                'type' => $controls_manager,
                'content_classes' => 'crt-pro-notice',
                'condition' => [
                    $option => $condition,
                ]
            ]
        );
    }

    public static function upgrade_expert_notice( $module, $controls_manager, $widget, $option, $condition = [] ) {
        if ( defined('CRT_ADDONS_PRO_VERSION') && crt_fs()->is_plan( 'expert' ) ) {
            return;
        }

        $url = 'https://crthemes.com/?ref=rea-plugin-panel-'. $widget .'-upgrade-expert#purchasepro';

        $module->add_control(
            $option .'_expert_notice',
            [
                'raw' => 'This option is available<br> in the <strong><a href="'. $url .'" target="_blank">Expert version</a></strong>',
                // 'raw' => 'This option is available<br> in the <strong><a href="'. admin_url('admin.php?page=crt-addons-pricing') .'" target="_blank">Pro version</a></strong>',
                'type' => $controls_manager,
                'content_classes' => 'crt-pro-notice',
                'condition' => [
                    $option => $condition,
                ]
            ]
        );
    }

    /**
     ** Request Feature Section
     */
    public static function crt_add_section_request_feature( $module, $raw_html, $tab ) {
        $module->start_controls_section(
            'section_request_new_feature',
            [
                'label' => __( 'Request Feature', 'crt-manage' ),
                'tab' => $tab,
            ]
        );

        $module->add_control(
            'request_new_feature',
            [
                'type' => $raw_html,
                'raw' => __( 'Missing an Option, have a New Widget or any kind of Feature Idea? Please share it with us and lets discuss. <a href="https://crthemes.com/contact" target="_blank">Request New Feature <span class="dashicons dashicons-star-empty"></span></a>', 'crt-manage' ),
            ]
        );

        $module->end_controls_section(); // End Controls Section
    }

    /**
     ** Pro Features List Section
     */
    public static function pro_features_list_section( $module, $section, $type, $widget, $features ) {
        if ( defined('CRT_ADDONS_PRO_VERSION') && crt_fs()->can_use_premium_code() ) {
            return;
        }

        if ( '' === $section ) {
            $module->start_controls_section(
                'pro_features_section',
                [
                    'label' => 'Pro Features <span class="dashicons dashicons-star-filled"></span>',
                ]
            );
        } else {
            $module->start_controls_section(
                'pro_features_section',
                [
                    'label' => 'Pro Features <span class="dashicons dashicons-star-filled"></span>',
                    'tab' => $section,
                ]
            );
        }


        $list_html = '';

        for ($i=0; $i < count($features); $i++) {
            $list_html .= '<li>'. $features[$i] .'</li>';
        }

        $module->add_control(
            'pro_features_list',
            [
                'type' => $type,
                'raw' => '<ul>'. $list_html .'</ul>
						  <a href="https://crthemes.com/?ref=rea-plugin-panel-pro-sec-'. $widget .'-upgrade-pro#purchasepro" target="_blank">Get Pro version</a>',
                'content_classes' => 'crt-pro-features-list',
            ]
        );

        $module->end_controls_section();
    }

    public static function is_pro() {
        $string = 'C47vHY3J0LWFkZG9ucy1wcm8=';
        $crt_ispro = !empty(get_option(base64_decode('Y3J0X21hbmFnZV9saWNlbnNl'))) ? json_decode(get_option(base64_decode('Y3J0X21hbmFnZV9saWNlbnNl'))) : array();
        if(in_array($string, $crt_ispro)) {
            return true;
        }
        return get_option('is_pro', true);
    }

    // Add two new functions for handling cookies
    public function get_wishlist_from_cookie() {
        if (isset($_COOKIE['crt_wishlist'])) {
            return json_decode(stripslashes($_COOKIE['crt_wishlist']), true);
        } else if ( isset($_COOKIE['crt_wishlist_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['crt_wishlist_'. get_current_blog_id() .'']), true);
        }
        return array();
    }

    // Client IP for form submission
    public static function get_client_ip() {
        $server_ip_keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ( $server_ip_keys as $key ) {
            $value = self::_unstable_get_super_global_value( $_SERVER, $key );
            if ( $value && filter_var( $value, FILTER_VALIDATE_IP ) ) {
                return $value;
            }
        }

        // Fallback local ip.
        return '127.0.0.1';
    }

    // For get_client_ip
    public static function _unstable_get_super_global_value( $super_global, $key ) {
        if ( ! isset( $super_global[ $key ] ) ) {
            return null;
        }

        if ( $_FILES === $super_global ) {
            $super_global[ $key ]['name'] = sanitize_file_name( $super_global[ $key ]['name'] );
            return $super_global[ $key ];
        }

        return wp_kses_post_deep( wp_unslash( $super_global[ $key ] ) );
    }

    /**
     ** Check for New Free Users
     */
    public static function is_new_free_user() {
        return (!defined('CRT_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code()) && (intval(get_option('royal_elementor_addons_activation_time')) > 1649247746);
    }

    /**
     ** HTML Tags Whitelist
     */
    public static function validate_html_tags_wl( $setting, $default, $tags_whitelist ) {
        $value = $setting;

        if ( ! in_array($value, $tags_whitelist) ) {
            $value = $default;
        }

        return $value;
    }

    public static function canvas_page_content_display_conditions() {
        $template_id = '';
        if(Utilities::crt_archive_templates_conditions()) {
            $template_id = Utilities::crt_archive_templates_conditions();
        }
        if(Utilities::crt_single_templates_conditions()) {
            $template_id = Utilities::crt_single_templates_conditions();
        }
        return $template_id;
    }

    /**
     ** Archive Pages Templates Conditions Free
     */
    public static function crt_archive_templates_conditions( $conditions = '' ) {
        $term_id = '';
        $term_name = '';
        $queried_object = get_queried_object();

        // Get Terms
        if ( ! is_null( $queried_object ) ) {
            if ( isset( $queried_object->term_id ) && isset( $queried_object->taxonomy ) ) {
                $term_id   = $queried_object->term_id;
                $term_name = $queried_object->taxonomy;
            }
        }

        // Reset
        $template = NULL;

        // Archive Pages (includes search)
        if ( is_archive() || is_search() ) {
            if ( ! is_search() ) {
                // Author
                if ( is_author() ) {
                    $template = Utilities::get_template_slug( $conditions, 'archive/author' );
                    // Date
                } elseif ( is_date() ) {
                    $template = Utilities::get_template_slug( $conditions, 'archive/date' );
                    // Category
                } elseif ( is_category() ) {
                    $template = Utilities::get_template_slug( $conditions, 'archive/categories', $term_id );
                    // Tag
                } elseif ( is_tag() ) {
                    $template = Utilities::get_template_slug( $conditions, 'archive/tags', $term_id );
                    // Products
                }

                // Search Page
            } else {
                $template = Utilities::get_template_slug( $conditions, 'archive/search' );
            }

            // Posts Page
        } elseif ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
            $template = Utilities::get_template_slug( $conditions, 'product_archive/product' );
        } elseif ( Utilities::is_blog_archive() ) {
            $template = Utilities::get_template_slug( $conditions, 'archive/posts' );
        }

        // Global - For All Archives
        if ( is_null($template) ) {
            $all_archives = Utilities::get_template_slug( $conditions, 'archive/all_archives' );

            if ( ! is_null($all_archives) ) {
                if ( class_exists( 'WooCommerce' ) && is_shop() ) {
                    $template = null;
                } else {
                    if ( is_archive() || is_search() || Utilities::is_blog_archive() ) {
                        $template = $all_archives;
                    }
                }
            }
        }

        return $template;
    }

    /**
     ** Single Pages Templates Conditions - Free
     */
    public static function crt_single_templates_conditions( $conditions = '' ) {
        global $post;

        // Get Posts
        $post_id   = is_null($post) ? '' : $post->ID;
        $post_type = is_null($post) ? '' : $post->post_type;

        // Reset
        $template = NULL;

        // Single Pages
        global $crt_manage_is_woo;
        if (( is_single() || is_front_page() || is_page() || is_404()) && ($crt_manage_is_woo && !is_shop()) ) {
            if ( is_single() ) {
                // Blog Posts
                if ( 'post' == $post_type ) {
                    $template = Utilities::get_template_slug( $conditions, 'single/posts', $post_id );
                } elseif ( 'product' == $post_type ) {
                    $template = Utilities::get_template_slug( $conditions, 'product_single/product', $post_id );
                }
            } else {
                // Front page
                if ( is_front_page() && ! Utilities::is_blog_archive() ) {
                    $template = Utilities::get_template_slug( $conditions, 'single/front_page' );
                } elseif ( is_page('cart') ) {
                    $template = Utilities::get_template_slug( $conditions, 'single/page_cart', $post_id );
                } elseif ( is_checkout() ) {
                    $template = Utilities::get_template_slug( $conditions, 'single/page_checkout', $post_id );
                } elseif ( is_404() ) {
                    $template = Utilities::get_template_slug( $conditions, 'single/page_404' );
                    // Single Page
                } elseif ( is_page() ) {
                    $template = Utilities::get_template_slug( $conditions, 'single/pages', $post_id );
                }
            }

        }

        return $template;
    }

    public static function crt_add_action_archive_html() {
        global $post;
        $select = '';
        $select .= '<select name="crt_archive_page_type" data-id="'.$post->ID.'" class="crt-action-page-type">';
        foreach (self::PT_DATA as $value => $name) {
            $selected = get_post_meta( $post->ID, 'crt-' . $value, true );
            $html_selected = '';
            if($selected) {
                $html_selected = 'selected="selected"';
            }
            $select .= '<option  value="'.$value.'" ' . $html_selected . ' >'.$name.'</option>';
        }
        $select .= '</select>';
        return 'Page Type ' . $select;
    }


    /**
     ** Single Pages Templates Conditions - Free
     */
    public static function crt_get_post_by_meta_key($post_type = 'post', $meta_key = '', $meta_value = true ) {
        $args = array(
            'posts_per_page' => 1,
            'post_type'  => $post_type,
            'meta_key'   => $meta_key,
            'meta_value' => $meta_value,
            'fields'     => 'ids',
        );

        $posts = new \WP_Query( $args );
        $post_ids = $posts->posts;
        if ( ! empty( $post_ids[0] ) ) {
            wp_reset_postdata();
            return $post_ids[0];
        } else {
            return null;
        }
    }

    public static function crt_manage_get_header_footer_id($post_type = 'post') {
        if ( is_singular() ) {
            $post_id = get_the_ID();
            if ( $post_type === 'crt_manage_header' ) {
                $custom_header_id = get_post_meta( $post_id, 'crt_manage_page_metabox_header_template', true );
                if ( ! empty( $custom_header_id ) && $custom_header_id !== 'header-theme' ) {
                    return (int) $custom_header_id;
                }
            }
        }

        $result = get_posts( [ 'post_type' => $post_type, 'posts_per_page' => 1, 'orderby' => 'date', 'order' => 'DESC', 'fields' => 'ids' ] );
        if(!empty($result[0])) {
            return $result[0];
        }
        return null;
    }

}