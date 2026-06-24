<?php
use Elementor\Controls_Manager;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class CRT_Theme_Builder extends Elementor\Core\Base\Document {

    public function get_name() {
        return 'crt_manage_archive';
    }

    public static function get_type() {
        return 'crt_manage_archive';
    }

    public static function get_properties() {
        $properties = parent::get_properties();

        $properties['cpt'] = [ 'crt_manage_archive' ];
        $properties['show_in_finder'] = true;
        $properties['show_on_admin_bar'] = true;
        $properties['is_editable'] = true;

        return $properties;
    }

    public static function get_title() {
        return esc_html__( 'Post', 'crt-manage' );
    }

    protected function register_controls() {
        parent::register_controls();

        $post_type = get_post_type( $this->get_main_id() );
        if($post_type == 'crt_manage_archive') {
            $this->crt_control_archive();
        } elseif ($post_type == 'crt_manage_header') {
            $this->crt_control_header();
        }
    }

    public function crt_control_archive() {

        $this->start_controls_section(
            'preview_settings',
            [
                'label' => esc_html__( 'Preview Settings', 'crt-manage' ),
                'tab'   => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $post_types = Utilities::get_custom_types_of( 'post', false );

        $post_taxonomies = Utilities::get_custom_types_of( 'tax', false );

        $default_archives = [
            'archive/posts' => esc_html__( 'Posts Archive', 'crt-manage' ),
            'archive/author' => esc_html__( 'Author Archive', 'crt-manage' ),
            'archive/date' => esc_html__( 'Date Archive', 'crt-manage' ),
            'archive/search' => esc_html__( 'Search Results', 'crt-manage' ),
            'product_archive/products' => esc_html__( 'Products Archive', 'crt-manage' ),
            'product_archive/product_search' => esc_html__('Products Search', 'crt-manage'),
        ];

        $taxonomy_archives = $post_taxonomies;

        // Add CPT to Default Archives
        foreach ($post_types as $post_type => $value) {
            if ( 'post' === $post_type || 'page' === $post_type || 'e-landing-page' === $post_type ) {
                continue;
            }

            $default_archives['archive/'. $post_type] = $value .' '. esc_html__( 'Archive', 'crt-manage' );
        }

        $id = get_the_ID();
        $query = '';
        $prefix = 'crt-';

        if ( false !== $id ) {
            $template_type = get_post_meta( $id, '_crt_template_type', true  );
            $template_slug = get_post($id)->post_name;

            if ( false !== strpos( $template_type, 'single' ) ) {


//                if () {
//                    $query = 'post';
//                } elseif (  ) {
//                    $query = 'product';
//                } elseif () {
//                    $query = 'page';
//                } else {
//                    foreach ($post_types as $post_type => $value) {
//                        if ( 'post' === $post_type || 'page' === $post_type ) {
//                            continue;
//                        }
//
//                        if ( $template_slug == Utilities::get_template_slug($conds, 'single/'. $post_type, $id) ) {
//                            $query = $post_type;
//                        }
//                    }
//                }
            } else {
                if ( get_post_meta( $id, $prefix . 'archive-all', true  ) || get_post_meta( $id, $prefix . 'archive-author', true  ) ) {
                    $query = 'archive/posts';
                } elseif ( get_post_meta( $id, $prefix . 'archive-search', true  ) ) {
                    $query = 'archive/search';
                } elseif ( get_post_meta( $id, $prefix . 'archive-author', true  ) ) {
                    $query = 'archive/author';
                } elseif ( get_post_meta( $id, $prefix . 'archive-date', true  ) ) {
                    $query = 'archive/date';
                } elseif ( get_post_meta( $id, $prefix . 'archive-product', true  ) ) {
                    $query = 'product_archive/products';
                } elseif ( get_post_meta( $id, $prefix . 'archive-product-cat', true  ) ) {
                    $query = 'product_cat';
                } elseif ( get_post_meta( $id, $prefix . 'archive-product-tag', true  ) ) {
                    $query = 'product_tag';
                } elseif ( get_post_meta( $id, $prefix . 'archive-product-search', true  ) ) {
                    $query = 'product_archive/product_search';
                } elseif ( get_post_meta( $id, $prefix . 'product', true  ) ) {
                    $query = 'product';
                } elseif ( get_post_meta( $id, $prefix . 'post-categories', true  ) ) {
                    $query = 'category';
                } elseif ( get_post_meta( $id, $prefix . 'post-tags', true  ) ) {
                    $query = 'post_tag';
                } else {
                    foreach ($post_types as $post_type => $value) {
                        if ( 'post' === $post_type || 'page' === $post_type ) {
                            continue;
                        }

                        if ( $query ) {
                            $query = 'archive/'. $post_type;
                        }
                    }

                    foreach ($post_taxonomies as $tax => $value) {
                        if ( 'category' === $tax || 'tag' === $tax ) {
                            continue;
                        }

                        if ( $query ) {
                            $query = $tax;
                        }
                    }
                }
            }
        }

        $this->add_control(
            'preview_source',
            [
                'label' => esc_html__( 'Preview Source', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => $query,
                'groups' => [
                    'archive' => [
                        'label' => __( 'Archives', 'crt-manage' ),
                        'options' => $default_archives + $taxonomy_archives,
                    ],
                    'single' => [
                        'label' => __( 'Singular', 'crt-manage' ),
                        'options' => $post_types
                    ],
                ],
            ]
        );

        $wp_users = Utilities::get_users();
        reset($wp_users);
        $first_user_id = key($wp_users);

        $this->add_control(
            'preview_archive_author',
            [
                'label' => esc_html__( 'Select Author', 'crt-manage' ),
                'type' => Controls_Manager::SELECT2,
                'options' => Utilities::get_users(),
                'default' => $first_user_id,
                'separator' => 'before',
                'condition' => [
                    'preview_source' => 'archive/author'
                ]
            ]
        );

        $this->add_control(
            'preview_archive_search',
            [
                'label' => esc_html__( 'Search Keyword', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
                'default' => 'a',
                'condition' => [
                    'preview_source' => 'archive/search',
                ]
            ]
        );

        // Posts
        foreach ( $post_types as $slug => $title ) {
            $latest_post = get_posts('post_type='. $slug .'&numberposts=1');

            $this->add_control(
                'preview_single_'. $slug,
                [
                    'label' => 'Select '. $title,
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'default' => !empty($latest_post) ? $latest_post[0]->ID : '',
                    'options' => Utilities::get_posts_by_post_type( $slug ),
                    'separator' => 'before',
                    'condition' => [
                        'preview_source' => $slug,
                    ],
                ]
            );
        }

        // Taxonomies
//        foreach ( $post_taxonomies as $slug => $title ) {
//            if ( 'category' === $slug || 'post_tag' === $slug ) {
//                $title = 'Post '. $title;
//            }
//
//            $terms = get_terms( $slug, 'orderby=date&hide_empty=0&number=1' );
//
//            $this->add_control(
//                'preview_archive_'. $slug,
//                [
//                    'label' => 'Select '. $title,
//                    'type' => Controls_Manager::SELECT2,
//                    'label_block' => true,
//                    'default' => !empty($terms) ? $terms[0]->term_id : '',
//                    'options' => Utilities::get_terms_by_taxonomy( $slug ),
//                    'separator' => 'before',
//                    'condition' => [
//                        'preview_source' => $slug,
//                    ],
//                ]
//            );
//        }

        $this->add_control(
            'review_changes',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-update-preview-button editor-crt-preview-update"><span>Click the Publish Button, then Click the Apply Button</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button></div>',
                'separator' => 'after'
            ]
        );

        $this->end_controls_section();
    }

    public function crt_control_header() {

        $this->start_controls_section(
            'preview_settings',
            [
                'label' => esc_html__( 'Preview Settings', 'crt-manage' ),
                'tab'   => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'review_changes',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-update-preview-button editor-crt-preview-update">Header</div>',
                'separator' => 'after'
            ]
        );

        $this->end_controls_section();
    }

    public function get_tax_query_args( $tax, $terms ) {
        $terms = empty($terms) ? [ 'all' ] : $terms;

        $args = [
            'tax_query' => [
                [
                    'taxonomy' => $tax,
                    'terms' => $terms,
                    'field' => 'id',
                ],
            ],
        ];

        return $args;
    }

    public function get_document_query_args() {
        $settings = $this->get_settings();
        $source = $settings['preview_source'];
        $args = false;

        // Default Archives
        switch ( $source ) {
            case 'archive/posts':
                $args = [ 'post_type' => 'post' ];
                break;

            case 'product_archive/products':
                $args = ['post_type' => 'product'];
                break;

            case 'archive/author':
                $args = [ 'author' => $settings['preview_archive_author'] ];
                break;

            case 'archive/search':
                $args = [ 's' => $settings['preview_archive_search'] ];
                break;

            default:
                // Custom Post Type Archives
                if ( strpos( $source, 'archive/' ) !== false ) {
                    $post_type = str_replace( 'archive/', '', $source );
                    $args = [ 'post_type' => $post_type ];
                }

                break;
        }

        // Taxonomy Archives
        foreach ( Utilities::get_custom_types_of( 'tax', false ) as $slug => $title ) {
            if ( $slug === $source ) {
                $args = $this->get_tax_query_args( $slug, $settings[ 'preview_archive_'. $slug ] );
            }
        }

        // Singular Posts
        foreach ( Utilities::get_custom_types_of( 'post', false ) as $slug => $title ) {
            if ( $slug === $source ) {
                // Get Post
                $post = get_posts( [
                    'post_type' => $source,
                    'numberposts' => 1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'suppress_filters' => false,
                ] );

                $args = [ 'post_type' => $source ];

                $post_id = $settings[ 'preview_single_'. $slug ];

                if ( ! empty( $post ) && '' === $post_id ) {
                    $args['p'] = $post[0]->ID;
                } else {
                    $args['p'] = $post_id;
                }
            }
        }

        // Default
        if ( false === $args ) {
            // Get Post
            $post = get_posts( [
                'post_type' => 'post',
                'numberposts' => 1,
                'orderby' => 'date',
                'order' => 'DESC',
                'suppress_filters' => false,
            ] );

            $args = [ 'post_type' => 'post' ];

            // Last Post for Single Pages
            if ( ! empty( $post ) ) {
                $args['p'] = $post[0]->ID;
            }
        }

        return $args;
    }

    public function switch_to_preview_query() {
        if ( 'crt_manage_archive' === get_post_type( get_the_ID() ) ) {
            $document = Elementor\Plugin::instance()->documents->get_doc_or_auto_save( get_the_ID() );
            Elementor\Plugin::instance()->db->switch_to_query( $document->get_document_query_args() );
        }
    }

    public function get_content( $with_css = false ) {
        $this->switch_to_preview_query();

        $content = parent::get_content( $with_css );

        Elementor\Plugin::instance()->db->restore_current_query();

        return $content;
    }

    public function print_content() {
        $plugin = Plugin::elementor();

        if ( $plugin->preview->is_preview_mode( $this->get_main_id() ) ) {
            echo ''. $plugin->preview->builder_wrapper( '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            echo ''. $this->get_content(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    public static function get_preview_as_default() {
        return '';
    }

    public static function get_preview_as_options() {
        return [];
    }

    public function get_elements_raw_data( $data = null, $with_html_content = false ) {

        $this->switch_to_preview_query();

        $editor_data = parent::get_elements_raw_data( $data, $with_html_content );

        Elementor\Plugin::instance()->db->restore_current_query();

        return $editor_data;
    }

    public function render_element( $data ) {

        $this->switch_to_preview_query();

        $render_html = parent::render_element( $data );

        Elementor\Plugin::instance()->db->restore_current_query();

        return $render_html;
    }

    // Add Post Class to Single Pages
    public function get_container_attributes() {
        $attributes = parent::get_container_attributes();

        if ( is_singular() ) { // Not 404
            $template_type = get_post_meta(get_the_ID(), '_crt_template_type', true);
            $post_classes = get_post_class( '', get_the_ID() );

            if ( 'product_single' === $template_type ) {
                array_push($post_classes, 'product');
            }

            $attributes['class'] .= ' ' . implode( ' ', $post_classes );
        }

        return $attributes;
    }

}