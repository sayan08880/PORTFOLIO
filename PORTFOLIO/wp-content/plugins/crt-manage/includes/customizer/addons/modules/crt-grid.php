<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use CrtAddons\Classes\Utilities;
use CrtAddons\Classes\Modules\CRT_Grid_Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Grid extends Widget_Base {

	public function get_name() {
		return 'crt-grid';
	}

	public function get_title() {
		return esc_html__( 'Post Grid/Slider/Carousel', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'crt_manage_archive'];
	}

	public function get_keywords() {
		return [ 'blog', 'portfolio grid', 'posts', 'post grid', 'posts grid', 'post slider', 'posts slider', 'post carousel', 'posts carousel', 'massonry grid', 'isotope', 'post gallery', 'posts gallery', 'filterable grid', 'loop grid' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_script_depends() {
		return [ 'crt-manage-isotope', 'crt-manage-lib-slick', 'crt-lightgallery', 'crt-grid' ];
	}

	public function get_style_depends() {
        return [ 'crt-animations-css', 'crt-link-animations-css', 'crt-button-animations-css', 'crt-loading-animations-css', 'crt-lightgallery-css' ];
	}

	public function get_custom_help_url() {
		if ( empty(get_option('crt_wl_plugin_links')) )
			return 'https://crthemes.com/contact';
	}

	public $post_types;
	public $current_post_type;

	public function add_option_query_source() {
		$this->post_types = [];
		$this->post_types['post'] = esc_html__( 'Posts', 'crt-manage' );
		$this->post_types['page'] = esc_html__( 'Pages', 'crt-manage' );

		$custom_post_types = Utilities::get_custom_types_of( 'post', true );
		foreach( $custom_post_types as $slug => $title ) {
			if ( 'product' === $slug || 'e-landing-page' === $slug ) {
				continue;
			}

            $this->post_types[$slug] = esc_html( $title );
        }

		$this->post_types['current'] = esc_html__( 'Current Query', 'crt-manage' );
		$this->post_types['related'] = esc_html__( 'Related Query', 'crt-manage' );
		
		return $this->post_types;
	}

	public function get_available_taxonomies() {
		$this->post_taxonomies = [];
		$this->post_taxonomies['category'] = esc_html__( 'Categories', 'crt-manage' );
		$this->post_taxonomies['post_tag'] = esc_html__( 'Tags', 'crt-manage' );

		$custom_post_taxonomies = Utilities::get_custom_types_of( 'tax', true );
		foreach( $custom_post_taxonomies as $slug => $title ) {
			if ( 'product_tag' === $slug || 'product_cat' === $slug ) {
				continue;
			}

            $this->post_taxonomies[$slug] = esc_html( $title );
		}

		return $this->post_taxonomies;
	}

	public function add_control_secondary_img_on_hover() {
		$this->add_control(
			'secondary_img_on_hover',
			[
				'label' => sprintf( __( '2nd Image on Hover %s', 'crt-manage' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'no-distance'
			]
		);
	}

	public function add_control_open_links_in_new_tab() {
		$this->add_control(
			'open_links_in_new_tab',
			[
				'label' => __( 'Open Links in New Tab', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);
	}

	public function add_control_grid_lazy_loading() {
		$this->add_control(
			'grid_lazy_loading',
			[
				'label' => __( 'Lazy Loading', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => ''
			]
		);
	}

	public function add_control_display_scheduled_posts() {
        $this->add_control(
            'display_scheduled_posts',
            [
                'label' => esc_html__( 'Display Only Scheduled Posts', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
    }

	public function add_control_query_randomize() {
        $this->add_control(
            'query_randomize',
            [
                'label' => esc_html__( 'Randomize Query', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'rand',
            ]
        );
	}

	public function add_control_order_posts() {
		$options = [
			'date' => esc_html__( 'Date', 'crt-manage'),
			'title' => esc_html__( 'Title', 'crt-manage'),
		];

		if ( Utilities::is_pro() ) {
			$options['modified'] = esc_html__( 'Last Modified', 'crt-manage');
			$options['ID'] = esc_html__( 'Post ID', 'crt-manage' );
			$options['author'] = esc_html__( 'Post Author', 'crt-manage' );
			$options['comment_count'] = esc_html__( 'Comment Count', 'crt-manage' );
			$options['meta_value'] = esc_html__( 'Custom Field', 'crt-manage' );
		} else {
			$options['pro-mf'] = esc_html__( 'Last Modified (Pro)', 'crt-manage');
			$options['pro-tl'] = esc_html__( 'Post ID (Pro)', 'crt-manage' );
			$options['pro-ar'] = esc_html__( 'Post Author (Pro)', 'crt-manage' );
			$options['pro-cc'] = esc_html__( 'Comment Count (Pro)', 'crt-manage' );
			$options['pro-d'] = esc_html__( 'Custom Field (Pro)', 'crt-manage' );
		}

		$this->add_control(
			'order_posts',
			[
				'label' => esc_html__( 'Order By', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'label_block' => false,
				'options' => $options,
				'condition' => [
					'query_randomize!' => 'rand',
				]
			]
		);
	}

	public function add_control_order_posts_by_acf( $meta ) {
        $this->add_control(
            'order_posts_by_acf',
            [
                'label' => esc_html__( 'Select Custom Field', 'crt-manage' ),
                // 'type' => Controls_Manager::SELECT2,
                'type' => 'crt-ajax-select2',
                'label_block' => true,
                'default' => 'default',
                'description' => '<strong>Note:</strong> This option only accepts String(Text) or Numeric Custom Field Values.',
                // 'options' => $meta,
                'options' => 'ajaxselect2/get_custom_meta_keys',
                'condition' => [
                    'order_posts' => 'meta_value'
                ],
            ]
        );
	}

	public function add_control_query_slides_to_show() {
		$this->add_control(
			'query_slides_to_show',
			[
				'label' => esc_html__( 'Slides to Show', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 0,
				'max' => 4,
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);
	}

	public function add_control_layout_select() {
        $options = [
            'fitRows' => esc_html__( 'FitRows - Equal Height', 'crt-manage' ),
            'list' => esc_html__( 'List Style', 'crt-manage' ),
            'slider' => esc_html__( 'Slider / Carousel', 'crt-manage' ),
        ];

		if ( Utilities::is_pro() ) {
            $options['masonry'] = esc_html__( 'Masonry - Unlimited Height', 'crt-manage' );
		} else {
            $options['pro-ms'] = esc_html__( 'Masonry - Unlimited Height (Pro)', 'crt-manage' );
		}

		$this->add_control(
			'layout_select',
			[
				'label' => esc_html__( 'Select Layout', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'masonry',
                'options' => $options,
				'render_type' => 'template'
			]
		);
	}

	public function add_control_layout_columns() {
		$this->add_responsive_control(
			'layout_columns',
			[
				'label' => esc_html__( 'Columns', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 3,
				'widescreen_default' => 3,
				'laptop_default' => 3,
				'tablet_extra_default' => 3,
				'tablet_default' => 2,
				'mobile_extra_default' => 2,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'crt-manage' ),
					2 => esc_html__( 'Two', 'crt-manage' ),
					3 => esc_html__( 'Three', 'crt-manage' ),
					4 => esc_html__( 'Four', 'crt-manage' ),
					5 => esc_html__( 'Five', 'crt-manage' ),
					6 => esc_html__( 'Six', 'crt-manage' ),
				],
				'prefix_class' => 'crt-grid-columns-%s',
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
				]
			]
		);
	}

	public function add_control_layout_animation() {
        $options = [
			'default' => esc_html__( 'None', 'crt-manage' ),
			'zoom' => esc_html__( 'Zoom', 'crt-manage' ),
        ];

		if ( Utilities::is_pro() ) {
			$options['fade'] = esc_html__( 'Fade', 'crt-manage' );
			$options['fade-slide'] = esc_html__( 'Fade + SlideUp', 'crt-manage' );
		} else {
			$options['pro-fd'] = esc_html__( 'Fade (Pro)', 'crt-manage' );
			$options['pro-fs'] = esc_html__( 'Fade + SlideUp (Pro)', 'crt-manage' );
		}

		$this->add_control(
			'layout_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => $options,
				'selectors_dictionary' => [
					'default' => '',
					'zoom' => 'opacity: 0; transform: scale(0.01)',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-inner' => '{{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select!' => 'slider',
				]
			]
		);
	}

	public function add_control_layout_slider_amount() {
		$this->add_responsive_control(
			'layout_slider_amount',
			[
				'label' => esc_html__( 'Columns (Carousel)', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 2,
				'widescreen_default' => 2,
				'laptop_default' => 2,
				'tablet_extra_default' => 2,
				'tablet_default' => 2,
				'mobile_extra_default' => 2,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'crt-manage' ),
					2 => esc_html__( 'Two', 'crt-manage' ),
					3 => esc_html__( 'Three', 'crt-manage' ),
					4 => esc_html__( 'Four', 'crt-manage' ),
					5 => esc_html__( 'Five', 'crt-manage' ),
					6 => esc_html__( 'Six', 'crt-manage' ),
				],
				'prefix_class' => 'crt-grid-slider-columns-%s',
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);
	}
	
	public function add_control_layout_slider_nav_hover() {
		$this->add_control(
			'layout_slider_nav_hover',
			[
				'label' => sprintf( __( 'Show on Hover %s', 'crt-manage' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'no-distance',
				'condition' => [
					'layout_slider_nav' => 'yes',
					'layout_select' => 'slider',

				],
			]
		);	
	}
	
	public function add_control_layout_slider_dots_position() {
        $options = [
            'horizontal' => esc_html__( 'Horizontal', 'crt-manage' ),
        ];

		if ( Utilities::is_pro() ) {
            $options['vertical'] = esc_html__( 'Vertical', 'crt-manage' );
		} else {
            $options['pro-vr'] = esc_html__( 'Vertical (Pro)', 'crt-manage' );
		}

		$this->add_control(
			'layout_slider_dots_position',
			[
				'label' => esc_html__( 'Pagination Layout', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => $options,
				'prefix_class' => 'crt-grid-slider-dots-',
				'render_type' => 'template',
				'condition' => [
					'layout_slider_dots' => 'yes',
					'layout_select' => 'slider',
				],
			]
		);
	}
	
	public function add_control_layout_slider_autoplay() {
		$this->add_control(
			'layout_slider_autoplay',
			[
				'label' => sprintf( __( 'Autoplay %s', 'crt-manage' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => '',
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);
	}

    public function add_controls_group_layout_slider_autoplay() {
        $this->add_control(
            'layout_slider_autoplay_duration',
            [
                'label' => esc_html__( 'Autoplay Speed', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 0,
                'max' => 15,
                'step' => 0.5,
                'frontend_available' => true,
                'condition' => [
                    'layout_slider_autoplay' => 'yes',
                    'layout_select' => 'slider',
                ],
            ]
        );

        $this->add_control(
            'layout_slider_pause_on_hover',
            [
                'label' => esc_html__( 'Pause on Hover', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'layout_slider_autoplay' => 'yes',
                    'layout_select' => 'slider',
                ],
            ]
        );
    }

    public function add_option_element_select() {
        $options = [
			'title' => esc_html__( 'Title', 'crt-manage' ),
			'content' => esc_html__( 'Content', 'crt-manage' ),
			'excerpt' => esc_html__( 'Excerpt', 'crt-manage' ),
			'date' => esc_html__( 'Date', 'crt-manage' ),
			'time' => esc_html__( 'Time', 'crt-manage' ),
			'author' => esc_html__( 'Author', 'crt-manage' ),
			'comments' => esc_html__( 'Comments', 'crt-manage' ),
			'read-more' => esc_html__( 'Read More', 'crt-manage' ),
			'lightbox' => esc_html__( 'Lightbox', 'crt-manage' ),
			'separator' => esc_html__( 'Separator', 'crt-manage' ),
			'custom-field' => esc_html__( 'Custom Field', 'crt-manage' ),
        ];

		if ( Utilities::is_pro() ) {
			$options['like'] = esc_html__( 'Likes', 'crt-manage' );
			$options['sharing'] = esc_html__( 'Sharing', 'crt-manage' );
		} else {
			$options['pro-lk'] = esc_html__( 'Likes (Pro)', 'crt-manage' );
			$options['pro-shr'] = esc_html__( 'Sharing (Pro)', 'crt-manage' );
		}

		return $options;
	}

    public function add_repeater_args_element_like_icon() {
        return [
            'label' => esc_html__( 'Likes Icon', 'crt-manage' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'far fa-heart',
            'options' => [
                'fas fa-heart' => esc_html__( 'Heart', 'crt-manage' ),
                'far fa-heart' => esc_html__( 'Heart Light', 'crt-manage' ),
                'fas fa-thumbs-up' => esc_html__( 'Thumbs', 'crt-manage' ),
                'far fa-thumbs-up' => esc_html__( 'Thumbs Light', 'crt-manage' ),
                'fas fa-star' => esc_html__( 'Star', 'crt-manage' ),
                'far fa-star' => esc_html__( 'Star Light', 'crt-manage' ),
            ],
            'condition' => [
                'element_select' => [ 'likes' ],
            ]
        ];
    }

    public function add_repeater_args_element_like_text() {
        return [
            'label' => esc_html__( 'No Likes Text ', 'crt-manage' ),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'default' => '0',
            'separator' => 'after',
            'condition' => [
                'element_select' => [ 'likes' ],
                'element_like_show_count' => 'yes'
            ],
        ];
    }


    public function add_option_social_networks() {
        return [
            'none' => esc_html__( 'None', 'crt-manage' ),
            'facebook-f' => esc_html__( 'Facebook', 'crt-manage' ),
            'twitter' => esc_html__( 'Twitter', 'crt-manage' ),
            'linkedin-in' => esc_html__( 'LinkedIn', 'crt-manage' ),
            'pinterest-p' => esc_html__( 'Pinterest', 'crt-manage' ),
            'reddit' => esc_html__( 'Reddit', 'crt-manage' ),
            'tumblr' => esc_html__( 'Tumblr', 'crt-manage' ),
            'digg' => esc_html__( 'Digg', 'crt-manage' ),
            'xing' => esc_html__( 'Xing', 'crt-manage' ),
            'stumbleupon' => esc_html__( 'StumpleUpon', 'crt-manage' ),
            'vk' => esc_html__( 'vKontakte', 'crt-manage' ),
            'odnoklassniki' => esc_html__( 'OdnoKlassniki', 'crt-manage' ),
            'get-pocket' => esc_html__( 'Pocket', 'crt-manage' ),
            'skype' => esc_html__( 'Skype', 'crt-manage' ),
            'whatsapp' => esc_html__( 'WhatsApp', 'crt-manage' ),
            'telegram' => esc_html__( 'Telegram', 'crt-manage' ),
        ];
    }

    public function add_repeater_args_element_like_show_count() {
        return [
            'label' => esc_html__( 'Show Likes Count', 'crt-manage' ),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'selectors_dictionary' => [
                '' => 'display: none;',
                'yes' => ''
            ],
            'selectors' => [
                '{{WRAPPER}} {{CURRENT_ITEM}} .crt-post-like-count' => '{{VALUE}}',
            ],
            'condition' => [
                'element_select' => [ 'likes' ],
            ]
        ];
    }

    public function add_repeater_args_element_sharing_icon_1() {
        return [
            'label' => esc_html__( 'Select Network', 'crt-manage' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'facebook-f',
            'options' => $this->add_option_social_networks(),
            'condition' => [
                'element_select' => [ 'sharing' ],
            ],
        ];
    }

    public function add_repeater_args_element_sharing_icon_2() {
        return [
            'label' => esc_html__( 'Select Network', 'crt-manage' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'twitter',
            'options' => $this->add_option_social_networks(),
            'condition' => [
                'element_select' => [ 'sharing' ],
            ],
        ];
    }

    public function add_repeater_args_element_sharing_icon_3() {
        return [
            'label' => esc_html__( 'Select Network', 'crt-manage' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'linkedin-in',
            'options' => $this->add_option_social_networks(),
            'condition' => [
                'element_select' => [ 'sharing' ],
            ],
        ];
    }

    public function add_repeater_args_element_sharing_icon_4() {
        return [
            'label' => esc_html__( 'Select Network', 'crt-manage' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'reddit',
            'options' => $this->add_option_social_networks(),
            'condition' => [
                'element_select' => [ 'sharing' ],
            ],
        ];
    }

    public function add_repeater_args_element_sharing_icon_5() {
        return [
            'label' => esc_html__( 'Select Network', 'crt-manage' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'none',
            'options' => $this->add_option_social_networks(),
            'condition' => [
                'element_select' => [ 'sharing' ],
            ],
        ];
    }

    public function add_repeater_args_element_sharing_icon_6() {
        return [
            'label' => esc_html__( 'Select Network', 'crt-manage' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'none',
            'options' => $this->add_option_social_networks(),
            'condition' => [
                'element_select' => [ 'sharing' ],
            ],
        ];
    }

    public function add_repeater_args_element_sharing_trigger() {
        return [
            'label' => esc_html__( 'Trigger Button', 'crt-manage' ),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'condition' => [
                'element_select' => [ 'sharing' ],
            ]
        ];
    }

    public function add_repeater_args_element_sharing_trigger_icon() {
        return [
            'label' => esc_html__( 'Select Icon', 'crt-manage' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'fas fa-share',
            'options' => Utilities::get_svg_icons_array( 'sharing', [
                'fas fa-share' => esc_html__( 'Share', 'crt-manage' ),
                'fas fa-share-square' => esc_html__( 'Share Square', 'crt-manage' ),
                'far fa-share-square' => esc_html__( 'Share Sqaure Alt', 'crt-manage' ),
                'fas fa-share-alt' => esc_html__( 'Share Alt', 'crt-manage' ),
                'fas fa-share-alt-square' => esc_html__( 'Share Alt Square', 'crt-manage' ),
                'fas fa-retweet' => esc_html__( 'Retweet', 'crt-manage' ),
                'fas fa-paper-plane' => esc_html__( 'Paper Plane', 'crt-manage' ),
                'far fa-paper-plane' => esc_html__( 'Paper Plane Alt', 'crt-manage' ),
                'svg-icons' => esc_html__( 'SVG Icons -----', 'crt-manage' ),
            ] ),
            'condition' => [
                'element_select' => 'sharing',
                'element_sharing_trigger' => 'yes'
            ]
        ];
    }

    public function add_repeater_args_element_sharing_trigger_action() {
        return [
            'label' => esc_html__( 'Trigger Action', 'crt-manage' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'click',
            'options' => [
                'click' => esc_html__( 'Click', 'crt-manage' ),
                'hover' => esc_html__( 'Hover', 'crt-manage' ),
            ],
            'condition' => [
                'element_select' => 'sharing',
                'element_sharing_trigger' => 'yes'
            ]
        ];
    }

    public function add_repeater_args_element_sharing_trigger_direction() {
        return [
            'label' => esc_html__( 'Trigger Direction', 'crt-manage' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'right',
            'options' => [
                'top' => esc_html__( 'Top', 'crt-manage' ),
                'right' => esc_html__( 'Right', 'crt-manage' ),
                'bottom' => esc_html__( 'Bottom', 'crt-manage' ),
                'left' => esc_html__( 'Left', 'crt-manage' ),
            ],
            'condition' => [
                'element_select' => 'sharing',
                'element_sharing_trigger' => 'yes'
            ],
            'separator' => 'after'
        ];
    }

    public function add_repeater_args_element_sharing_tooltip() {
        return [
            'label' => esc_html__( 'Label Tooltip', 'crt-manage' ),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'condition' => [
                'element_select' => [ 'sharing' ],
            ],
            'separator' => 'after'
        ];
    }

    public function add_repeater_args_element_custom_field( $meta ) {
        return [
            'label' => esc_html__( 'Select Custom Field', 'crt-manage' ),
            'type' => 'crt-ajax-select2',
            'label_block' => true,
            'default' => 'default',
            'description' => '<strong>Note:</strong> This option only accepts String(Text) or Numeric Custom Field Values.',
            'options' => 'ajaxselect2/get_custom_meta_keys',
            'condition' => [
                'element_select' => 'custom-field'
            ],
        ];
    }

    public function add_repeater_args_element_custom_field_img_ID() {
        return [
            'label' => esc_html__( 'Use Value as Image ID', 'crt-manage' ),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'condition' => [
                'element_select' => 'custom-field'
            ],
        ];
    }

    public function add_repeater_args_element_custom_field_btn_link() {
        return [
            'label' => esc_html__( 'Use Value as Button Link', 'crt-manage' ),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'condition' => [
                'element_select' => 'custom-field',
                'element_custom_field_img_ID' => ''
            ],
        ];
    }

    public function add_repeater_args_element_custom_field_new_tab() {
        return [
            'label' => esc_html__( 'Open Link in a New Tab', 'crt-manage' ),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'condition' => [
                'element_select' => 'custom-field',
                'element_custom_field_btn_link' => 'yes',
                'element_custom_field_img_ID' => ''
            ],
        ];
    }

	public function add_repeater_args_custom_field_wrapper_html_divider1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

    public function add_repeater_args_element_custom_field_wrapper() {
        return [
            'label' => esc_html__( 'Wrap with HTML', 'crt-manage' ),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'condition' => [
                'element_select' => 'custom-field',
                'element_custom_field_img_ID' => '',
                'element_custom_field_btn_link' => '',
            ],
        ];
    }

    public function add_repeater_args_element_custom_field_wrapper_html() {
        return [
            'label' => esc_html__( 'Custom HTML Wrapper', 'crt-manage' ),
            'description' => 'Insert <strong>*cf_value*</strong> to dislpay your Custom Field.',
            'placeholder'=> 'For Ex: <span>*cf_value*</span>',
            'type' => Controls_Manager::TEXTAREA,
            'dynamic' => [
                'active' => true,
            ],
            'condition' => [
                'element_select' => 'custom-field',
                'element_custom_field_wrapper' => 'yes',
                'element_custom_field_img_ID' => '',
                'element_custom_field_btn_link' => '',
            ],
        ];
    }

    public function add_repeater_args_custom_field_wrapper_html_divider2() {
        return [
            'type' => Controls_Manager::DIVIDER,
            'style' => 'thick',
            'condition' => [
                'element_select' => 'custom-field',
            ],
        ];
    }

    public function add_repeater_args_element_cf_tag() {
        return [
            'label' => esc_html__( 'HTML Tag', 'crt-manage' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'div' => 'div',
                'span' => 'span',
                'P' => 'p'
            ],
            'default' => 'span',
            'condition' => [
                'element_select' => 'custom-field',
            ]
        ];
    }

    public function add_repeater_args_element_custom_field_style() {
        return [
            'label' => esc_html__( 'Select Styling', 'crt-manage' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'crt-grid-cf-style-1',
            'options' => [
                'crt-grid-cf-style-1' => esc_html__( 'Custom Field Style 1', 'crt-manage' ),
                'crt-grid-cf-style-2' => esc_html__( 'Custom Field Style 2', 'crt-manage' ),
                'crt-grid-cf-style-3' => esc_html__( 'Custom Field Style 3', 'crt-manage' ),
                'crt-grid-cf-style-4' => esc_html__( 'Custom Field Style 4', 'crt-manage' ),
            ],
            'condition' => [
                'element_select' => 'custom-field',
            ],
            'separator' => 'after'
        ];
    }

	public function add_repeater_args_element_trim_text_by() {
		return [
			'word_count' => esc_html__( 'Word Count', 'crt-manage' ),
			'letter-count' => esc_html__( 'Letter Count', 'crt-manage' )
		];
	}
	
	public function add_control_overlay_animation_divider() {
        $this->add_control(
            'overlay_animation_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
    }
	
	public function add_control_overlay_image() {
        $this->add_control(
            'overlay_image',
            [
                'label' => esc_html__( 'Upload GIF', 'crt-manage' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );
    }
	
	public function add_control_overlay_image_width() {
        $this->add_control(
            'overlay_image_width',
            [
                'label' => esc_html__( 'GIF Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 70,
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-media-hover-bg img' => 'max-width: {{SIZE}}px;',
                ],
            ]
        );
    }
	
	public function add_control_image_effects() {
		$this->add_control(
			'image_effects',
			[
				'label' => esc_html__( 'Select Effect', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'zoom-in' => esc_html__( 'Zoom In', 'crt-manage' ),
                    'zoom-out' => esc_html__( 'Zoom Out', 'crt-manage' ),
                    'grayscale-in' => esc_html__( 'Grayscale In', 'crt-manage' ),
                    'grayscale-out' => esc_html__( 'Grayscale Out', 'crt-manage' ),
                    'blur-in' => esc_html__( 'Blur In', 'crt-manage' ),
                    'blur-out' => esc_html__( 'Blur Out', 'crt-manage' ),
                    'slide' => esc_html__( 'Slide', 'crt-manage' ),
				],
				'default' => 'none',
			]
		);
	}
	
	public function add_control_lightbox_popup_thumbnails() {
		$this->add_control(
			'lightbox_popup_thumbnails',
			[
				'label' => sprintf( __( 'Show Thumbnails %s', 'crt-manage' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'no-distance'
			]
		);	
	}

    public function add_control_lightbox_popup_thumbnails_default() {
        $this->add_control(
            'lightbox_popup_thumbnails_default',
            [
                'label' => esc_html__( 'Show Thumbs by Default', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true',
                'condition' => [
                    'lightbox_popup_thumbnails' => 'true'
                ]
            ]
        );
    }
	
	public function add_control_lightbox_popup_sharing() {
		$this->add_control(
			'lightbox_popup_sharing',
			[
				'label' => __( 'Show Sharing Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'no-distance'
			]
		);	
	}
	
	public function add_control_filters_deeplinking() {
		$this->add_control(
			'filters_deeplinking',
			[
				'label' => __( 'Enable Deep Linking', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'no-distance'
			]
		);		
	}
	
	public function add_control_filters_animation() {
		$this->add_control(
			'filters_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'crt-manage' ),
					'zoom' => esc_html__( 'Zoom', 'crt-manage' ),
					'fade' => esc_html__( 'Fade', 'crt-manage' ),
					'fade-slide' => esc_html__( 'Fade + SlideUp', 'crt-manage' ),
				],
				'separator' => 'before',
			]
		);
	}
	
	public function add_control_filters_icon() {
        $this->add_control(
            'filters_icon',
            [
                'label' => esc_html__( 'Select Icon', 'crt-manage' ),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'separator' => 'before',
            ]
        );
    }
	
	public function add_control_filters_icon_align() {
        $this->add_control(
            'filters_icon_align',
            [
                'label' => esc_html__( 'Icon Position', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'crt-manage' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'crt-manage' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'condition' => [
                    'filters_icon!' => '',
                ],
            ]
        );
    }

	public function add_control_filters_count() {
		$this->add_control(
			'filters_count',
			[
				'label' => __( 'Show Count', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'no-distance'
			]
		);	
	}
	
	public function add_control_filters_count_superscript() {
        $this->add_control(
            'filters_count_superscript',
            [
                'label' => esc_html__( 'Count as Superscript', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'selectors_dictionary' => [
                    '' => 'vertical-align:middle;font-size: inherit;top:0;',
                    'yes' => 'vertical-align:super;font-size: x-smal;top:-3px;'
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-filters sup' => '{{VALUE}};',
                ],
                'condition' => [
                    'filters_count' => 'yes',
                ],
            ]
        );
    }
	
	public function add_control_filters_count_brackets() {
        $this->add_control(
            'filters_count_brackets',
            [
                'label' => esc_html__( 'Count Wrapper Brackets', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'filters_count' => 'yes',
                ],
            ]
        );
    }
	
	public function add_control_filters_default_filter() {
        $this->add_control(
            'filters_default_filter',
            [
                'label' => esc_html__( 'Default Filter', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'description' => 'Enter your custom Category (Taxonomy) slug to filter Grid items by default.',
                'condition' => [
                    'filters_linkable!' => 'yes',
                ],
            ]
        );
    }

	public function add_control_pagination_type() {
		$options = [
            'default' => esc_html__( 'Default', 'crt-manage' ),
            'load-more' => esc_html__( 'Load More Button', 'crt-manage' ),
		];

		if ( Utilities::is_pro() ) {
            $options['numbered'] = esc_html__( 'Numbered', 'crt-manage' );
            $options['infinite-scroll'] = esc_html__( 'Infinite Scrolling', 'crt-manage' );
		} else {
            $options['pro-nb'] = esc_html__( 'Numbered (Pro)', 'crt-manage' );
            $options['pro-is'] = esc_html__( 'Infinite Scrolling (Pro)', 'crt-manage' );
		}

		$this->add_control(
			'pagination_type',
			[
				'label' => esc_html__( 'Select Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'load-more',
				'options' => $options,
				'separator' => 'after'
			]
		);
	}

    public function add_section_style_likes() {
        $this->start_controls_section(
            'section_style_likes',
            [
                'label' => esc_html__( 'Likes', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs( 'tabs_grid_likes_style' );

        $this->start_controls_tab(
            'tab_grid_likes_normal',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'likes_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'likes_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'likes_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block a' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'likes_extra_text_color',
            [
                'label'  => esc_html__( 'Extra Text Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block span[class*="crt-grid-extra-text"]' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_likes_hover',
            [
                'label' => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'likes_color_hr',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'likes_bg_color_hr',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block a:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'likes_border_color_hr',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block a:hover' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'likes_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block a' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'likes_typography',
                'selector' => '{{WRAPPER}} .crt-grid-item-likes'
            ]
        );

        $this->add_control(
            'likes_border_type',
            [
                'label' => esc_html__( 'Border Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'solid' => esc_html__( 'Solid', 'crt-manage' ),
                    'double' => esc_html__( 'Double', 'crt-manage' ),
                    'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
                    'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
                    'groove' => esc_html__( 'Groove', 'crt-manage' ),
                ],
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'likes_border_width',
            [
                'label' => esc_html__( 'Border Width', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'likes_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'likes_text_spacing',
            [
                'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-item-likes .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'likes_icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes i' => 'padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'likes_width',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block a' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'likes_height',
            [
                'label' => esc_html__( 'Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block a' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'likes_margin',
            [
                'label' => esc_html__( 'Margin', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'likes_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 0,
                    'left' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-likes .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    public function add_section_style_sharing() {
        $this->start_controls_section(
            'section_style_sharing',
            [
                'label' => esc_html__( 'Sharing', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs( 'tabs_grid_sharing_style' );

        $this->start_controls_tab(
            'tab_grid_sharing_normal',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'sharing_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'sharing_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'sharing_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block a' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'sharing_tooltip_color',
            [
                'label'  => esc_html__( 'Tooltip Text Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-sharing-tooltip' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'sharing_tooltip_bg_color',
            [
                'label'  => esc_html__( 'Tooltip Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-sharing-tooltip' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-sharing-tooltip:before' => 'border-top-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'sharing_extra_text_color',
            [
                'label'  => esc_html__( 'Extra Text Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block span[class*="crt-grid-extra-text"]' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_sharing_hover',
            [
                'label' => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'sharing_color_hr',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'sharing_bg_color_hr',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block a:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'sharing_border_color_hr',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block a:hover' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'sharing_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block a' => 'transition-duration: {{VALUE}}s;',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'sharing_typography',
                'selector' => '{{WRAPPER}} .crt-grid-item-sharing'
            ]
        );

        $this->add_control(
            'sharing_border_type',
            [
                'label' => esc_html__( 'Border Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'solid' => esc_html__( 'Solid', 'crt-manage' ),
                    'double' => esc_html__( 'Double', 'crt-manage' ),
                    'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
                    'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
                    'groove' => esc_html__( 'Groove', 'crt-manage' ),
                ],
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'sharing_border_width',
            [
                'label' => esc_html__( 'Border Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block a' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'sharing_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'sharing_text_spacing',
            [
                'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-item-sharing .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'sharing_gutter',
            [
                'label' => esc_html__( 'Gutter', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            'sharing_width',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block a' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            'sharing_height',
            [
                'label' => esc_html__( 'Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block a' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'sharing_margin',
            [
                'label' => esc_html__( 'Margin', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'sharing_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sharing .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    public function add_section_style_custom_field1() {
            $this->start_controls_section(
                'section_style_custom_field1',
                [
                    'label' => esc_html__( 'Custom Field Style 1', 'crt-manage' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'show_label' => false,
                ]
            );

            $this->start_controls_tabs( 'tabs_grid_custom_field1_style' );

            $this->start_controls_tab(
                'tab_grid_custom_field1_normal',
                [
                    'label' => esc_html__( 'Normal', 'crt-manage' ),
                ]
            );

            $this->add_control(
                'custom_field1_color',
                [
                    'label'  => esc_html__( 'Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#9C9C9C',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > span' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'custom_field1_bg_color',
                [
                    'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > a' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > span' => 'background-color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_control(
                'custom_field1_border_color',
                [
                    'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#E8E8E8',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > a' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > span' => 'border-color: {{VALUE}}',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'tab_grid_custom_field1_hover',
                [
                    'label' => esc_html__( 'Hover', 'crt-manage' ),
                ]
            );

            $this->add_control(
                'custom_field1_color_hr',
                [
                    'label'  => esc_html__( 'Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#9C9C9C',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > a:hover' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > a:hover a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > span:hover' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > span:hover a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'custom_field1_bg_color_hr',
                [
                    'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > a:hover' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > span:hover' => 'background-color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_control(
                'custom_field1_border_color_hr',
                [
                    'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#E8E8E8',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > a:hover' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > span:hover' => 'border-color: {{VALUE}}',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'custom_field1_transition_duration',
                [
                    'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 0.1,
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block a' => 'transition-duration: {{VALUE}}s',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > span' => 'transition-duration: {{VALUE}}s',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'custom_field1_typography',
                    'selector' => '{{WRAPPER}} .crt-grid-cf-style-1'
                ]
            );

            $this->add_control(
                'custom_field1_border_type',
                [
                    'label' => esc_html__( 'Border Type', 'crt-manage' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'none' => esc_html__( 'None', 'crt-manage' ),
                        'solid' => esc_html__( 'Solid', 'crt-manage' ),
                        'double' => esc_html__( 'Double', 'crt-manage' ),
                        'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
                        'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
                        'groove' => esc_html__( 'Groove', 'crt-manage' ),
                    ],
                    'default' => 'none',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > a' => 'border-style: {{VALUE}};',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > span' => 'border-style: {{VALUE}};',
                    ],
                    'render_type' => 'template',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'custom_field1_border_width',
                [
                    'label' => esc_html__( 'Border Width', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px' ],
                    'default' => [
                        'top' => 1,
                        'right' => 1,
                        'bottom' => 1,
                        'left' => 1,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'render_type' => 'template',
                    'condition' => [
                        'custom_field1_border_type!' => 'none',
                    ],
                ]
            );

            $this->add_control(
                'custom_field1_text_spacing',
                [
                    'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 25,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 5,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'custom_field1_icon_spacing',
                [
                    'label' => esc_html__( 'Extra Icon Spacing', 'crt-manage' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 25,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 5,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'custom_field1_padding',
                [
                    'label' => esc_html__( 'Padding', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'render_type' => 'template',
                ]
            );

            $this->add_responsive_control(
                'custom_field1_margin',
                [
                    'label' => esc_html__( 'Margin', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'render_type' => 'template',
                ]
            );

            $this->add_control(
                'custom_field1_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default' => [
                        'top' => 2,
                        'right' => 2,
                        'bottom' => 2,
                        'left' => 2,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-1 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->end_controls_section();
    }

    public function add_section_style_custom_field2() {
        if ( true ) {
            $this->start_controls_section(
                'section_style_custom_field2',
                [
                    'label' => esc_html__( 'Custom Field Style 2', 'crt-manage' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'show_label' => false,
                ]
            );

            $this->start_controls_tabs( 'tabs_grid_custom_field2_style' );

            $this->start_controls_tab(
                'tab_grid_custom_field2_normal',
                [
                    'label' => esc_html__( 'Normal', 'crt-manage' ),
                ]
            );

            $this->add_control(
                'custom_field2_color',
                [
                    'label'  => esc_html__( 'Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#e55b5b',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > span' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'custom_field2_bg_color',
                [
                    'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > a' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > span' => 'background-color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_control(
                'custom_field2_border_color',
                [
                    'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#E8E8E8',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > a' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > span' => 'border-color: {{VALUE}}',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'tab_grid_custom_field2_hover',
                [
                    'label' => esc_html__( 'Hover', 'crt-manage' ),
                ]
            );

            $this->add_control(
                'custom_field2_color_hr',
                [
                    'label'  => esc_html__( 'Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#4A45D2',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > a:hover' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > a:hover a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > span:hover' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > span:hover a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'custom_field2_bg_color_hr',
                [
                    'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > a:hover' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > span:hover' => 'background-color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_control(
                'custom_field2_border_color_hr',
                [
                    'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#E8E8E8',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > a:hover' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > span:hover' => 'border-color: {{VALUE}}',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'custom_field2_transition_duration',
                [
                    'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 0.1,
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block a' => 'transition-duration: {{VALUE}}s',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > span' => 'transition-duration: {{VALUE}}s',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'custom_field2_typography',
                    'selector' => '{{WRAPPER}} .crt-grid-cf-style-2'
                ]
            );

            $this->add_control(
                'custom_field2_border_type',
                [
                    'label' => esc_html__( 'Border Type', 'crt-manage' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'none' => esc_html__( 'None', 'crt-manage' ),
                        'solid' => esc_html__( 'Solid', 'crt-manage' ),
                        'double' => esc_html__( 'Double', 'crt-manage' ),
                        'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
                        'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
                        'groove' => esc_html__( 'Groove', 'crt-manage' ),
                    ],
                    'default' => 'none',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > a' => 'border-style: {{VALUE}};',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > span' => 'border-style: {{VALUE}};',
                    ],
                    'render_type' => 'template',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'custom_field2_border_width',
                [
                    'label' => esc_html__( 'Border Width', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px' ],
                    'default' => [
                        'top' => 1,
                        'right' => 1,
                        'bottom' => 1,
                        'left' => 1,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'render_type' => 'template',
                    'condition' => [
                        'custom_field2_border_type!' => 'none',
                    ],
                ]
            );

            $this->add_control(
                'custom_field2_text_spacing',
                [
                    'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 25,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 5,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'custom_field2_icon_spacing',
                [
                    'label' => esc_html__( 'Extra Icon Spacing', 'crt-manage' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 25,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 5,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'custom_field2_padding',
                [
                    'label' => esc_html__( 'Padding', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'render_type' => 'template',
                ]
            );

            $this->add_responsive_control(
                'custom_field2_margin',
                [
                    'label' => esc_html__( 'Margin', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'render_type' => 'template',
                ]
            );

            $this->add_control(
                'custom_field2_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default' => [
                        'top' => 2,
                        'right' => 2,
                        'bottom' => 2,
                        'left' => 2,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-2 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->end_controls_section();
        }
    }

    public function add_section_style_custom_field3() {
        if ( true ) {
            $this->start_controls_section(
                'section_style_custom_field3',
                [
                    'label' => esc_html__( 'Custom Field Style 3', 'crt-manage' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'show_label' => false,
                ]
            );

            $this->start_controls_tabs( 'tabs_grid_custom_field3_style' );

            $this->start_controls_tab(
                'tab_grid_custom_field3_normal',
                [
                    'label' => esc_html__( 'Normal', 'crt-manage' ),
                ]
            );

            $this->add_control(
                'custom_field3_color',
                [
                    'label'  => esc_html__( 'Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#e55b5b',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > span' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'custom_field3_bg_color',
                [
                    'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > a' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > span' => 'background-color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_control(
                'custom_field3_border_color',
                [
                    'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#E8E8E8',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > a' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > span' => 'border-color: {{VALUE}}',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'tab_grid_custom_field3_hover',
                [
                    'label' => esc_html__( 'Hover', 'crt-manage' ),
                ]
            );

            $this->add_control(
                'custom_field3_color_hr',
                [
                    'label'  => esc_html__( 'Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#4A45D2',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > a:hover' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > a:hover a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > span:hover' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > span:hover a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'custom_field3_bg_color_hr',
                [
                    'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > a:hover' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > span:hover' => 'background-color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_control(
                'custom_field3_border_color_hr',
                [
                    'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#E8E8E8',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > a:hover' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > span:hover' => 'border-color: {{VALUE}}',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'custom_field3_transition_duration',
                [
                    'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 0.1,
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block a' => 'transition-duration: {{VALUE}}s',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > span' => 'transition-duration: {{VALUE}}s',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'custom_field3_typography',
                    'selector' => '{{WRAPPER}} .crt-grid-cf-style-3'
                ]
            );

            $this->add_control(
                'custom_field3_border_type',
                [
                    'label' => esc_html__( 'Border Type', 'crt-manage' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'none' => esc_html__( 'None', 'crt-manage' ),
                        'solid' => esc_html__( 'Solid', 'crt-manage' ),
                        'double' => esc_html__( 'Double', 'crt-manage' ),
                        'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
                        'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
                        'groove' => esc_html__( 'Groove', 'crt-manage' ),
                    ],
                    'default' => 'none',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > a' => 'border-style: {{VALUE}};',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > span' => 'border-style: {{VALUE}};',
                    ],
                    'render_type' => 'template',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'custom_field3_border_width',
                [
                    'label' => esc_html__( 'Border Width', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px' ],
                    'default' => [
                        'top' => 1,
                        'right' => 1,
                        'bottom' => 1,
                        'left' => 1,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'render_type' => 'template',
                    'condition' => [
                        'custom_field3_border_type!' => 'none',
                    ],
                ]
            );

            $this->add_control(
                'custom_field3_text_spacing',
                [
                    'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 25,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 5,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'custom_field3_icon_spacing',
                [
                    'label' => esc_html__( 'Extra Icon Spacing', 'crt-manage' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 25,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 5,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'custom_field3_padding',
                [
                    'label' => esc_html__( 'Padding', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'render_type' => 'template',
                ]
            );

            $this->add_responsive_control(
                'custom_field3_margin',
                [
                    'label' => esc_html__( 'Margin', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'render_type' => 'template',
                ]
            );

            $this->add_control(
                'custom_field3_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default' => [
                        'top' => 2,
                        'right' => 2,
                        'bottom' => 2,
                        'left' => 2,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-3 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->end_controls_section();
        }
    }

    public function add_section_style_custom_field4() {
        if ( true ) {
            $this->start_controls_section(
                'section_style_custom_field4',
                [
                    'label' => esc_html__( 'Custom Field Style 4', 'crt-manage' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'show_label' => false,
                ]
            );

            $this->start_controls_tabs( 'tabs_grid_custom_field4_style' );

            $this->start_controls_tab(
                'tab_grid_custom_field4_normal',
                [
                    'label' => esc_html__( 'Normal', 'crt-manage' ),
                ]
            );

            $this->add_control(
                'custom_field4_color',
                [
                    'label'  => esc_html__( 'Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#e55b5b',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > span' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'custom_field4_bg_color',
                [
                    'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > a' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > span' => 'background-color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_control(
                'custom_field4_border_color',
                [
                    'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#E8E8E8',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > a' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > span' => 'border-color: {{VALUE}}',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'tab_grid_custom_field4_hover',
                [
                    'label' => esc_html__( 'Hover', 'crt-manage' ),
                ]
            );

            $this->add_control(
                'custom_field4_color_hr',
                [
                    'label'  => esc_html__( 'Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#4A45D2',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > a:hover' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > a:hover a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > span:hover' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > span:hover a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'custom_field4_bg_color_hr',
                [
                    'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > a:hover' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > span:hover' => 'background-color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_control(
                'custom_field4_border_color_hr',
                [
                    'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#E8E8E8',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > a:hover' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > span:hover' => 'border-color: {{VALUE}}',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'custom_field4_transition_duration',
                [
                    'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 0.1,
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block a' => 'transition-duration: {{VALUE}}s',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > span' => 'transition-duration: {{VALUE}}s',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'custom_field4_typography',
                    'selector' => '{{WRAPPER}} .crt-grid-cf-style-4'
                ]
            );

            $this->add_control(
                'custom_field4_border_type',
                [
                    'label' => esc_html__( 'Border Type', 'crt-manage' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'none' => esc_html__( 'None', 'crt-manage' ),
                        'solid' => esc_html__( 'Solid', 'crt-manage' ),
                        'double' => esc_html__( 'Double', 'crt-manage' ),
                        'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
                        'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
                        'groove' => esc_html__( 'Groove', 'crt-manage' ),
                    ],
                    'default' => 'none',
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > a' => 'border-style: {{VALUE}};',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > span' => 'border-style: {{VALUE}};',
                    ],
                    'render_type' => 'template',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'custom_field4_border_width',
                [
                    'label' => esc_html__( 'Border Width', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px' ],
                    'default' => [
                        'top' => 1,
                        'right' => 1,
                        'bottom' => 1,
                        'left' => 1,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'render_type' => 'template',
                    'condition' => [
                        'custom_field4_border_type!' => 'none',
                    ],
                ]
            );

            $this->add_control(
                'custom_field4_text_spacing',
                [
                    'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 25,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 5,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'custom_field4_icon_spacing',
                [
                    'label' => esc_html__( 'Extra Icon Spacing', 'crt-manage' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 25,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 5,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'custom_field4_padding',
                [
                    'label' => esc_html__( 'Padding', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'render_type' => 'template',
                ]
            );

            $this->add_responsive_control(
                'custom_field4_margin',
                [
                    'label' => esc_html__( 'Margin', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'render_type' => 'template',
                ]
            );

            $this->add_control(
                'custom_field4_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'default' => [
                        'top' => 2,
                        'right' => 2,
                        'bottom' => 2,
                        'left' => 2,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .crt-grid-cf-style-4 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->end_controls_section();
        }
    }
	
    public function add_control_grid_item_even_bg_color() {
        $this->add_control(
            'grid_item_even_bg_color',
            [
                'label'  => esc_html__( 'Even Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item:nth-child(2n) .crt-grid-item-above-content' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-item:nth-child(2n) .crt-grid-item-below-content' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item:nth-child(2n)' => 'background-color: {{VALUE}}'
                ]
            ]
        );
    }

    public function add_control_grid_item_even_border_color() {
        $this->add_control(
            'grid_item_even_border_color',
            [
                'label'  => esc_html__( 'Even Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.crt-item-styles-inner .crt-grid-item:nth-child(2n) .crt-grid-item-above-content' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}}.crt-item-styles-inner .crt-grid-item:nth-child(2n) .crt-grid-item-below-content' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item:nth-child(2n)' => 'border-color: {{VALUE}}'
                ]
            ]
        );
    }

    public function add_control_overlay_color() {
		$this->add_control(
			'overlay_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.25)',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'background-color: {{VALUE}}',
				],
			]
		);
	}

    public function add_control_title_pointer_height() {
        $this->add_control(
            'title_pointer_height',
            [
                'label' => esc_html__( 'Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-title .crt-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-item-title .crt-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'title_pointer' => [ 'underline', 'overline' ],
                ],
            ]
        );
    }

    public function add_control_title_pointer_animation() {
        $this->add_control(
            'title_pointer_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'none' => 'None',
                    'fade' => 'Fade',
                    'slide' => 'Slide',
                    'grow' => 'Grow',
                    'drop' => 'Drop',
                ],
                'condition' => [
                    'title_pointer' => [ 'underline', 'overline' ],
                ],
            ]
        );
    }

    public function add_control_read_more_animation() {
        $this->add_control(
            'read_more_animation',
            [
                'label' => esc_html__( 'Select Animation', 'crt-manage' ),
                'type' => 'crt-button-animations',
                'default' => 'crt-button-none',
            ]
        );
    }

    public function add_control_title_pointer_color_hr() {
        $this->add_control(
            'title_pointer_color_hr',
            [
                'label'  => esc_html__( 'Hover Effect Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-title .crt-pointer-item:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-item-title .crt-pointer-item:after' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );
    }

    public function add_control_title_pointer() {
        $this->add_control(
            'title_pointer',
            [
                'label' => esc_html__( 'Hover Effect', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'underline' => esc_html__( 'Underline', 'crt-manage' ),
                    'overline' => esc_html__( 'Overline', 'crt-manage' ),
                ],
            ]
        );
    }

    public function add_control_overlay_blend_mode() {
        $this->add_control(
            'overlay_blend_mode',
            [
                'label' => esc_html__( 'Blend Mode', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => esc_html__( 'Normal', 'crt-manage' ),
                    'multiply' => esc_html__( 'Multiply', 'crt-manage' ),
                    'screen' => esc_html__( 'Screen', 'crt-manage' ),
                    'overlay' => esc_html__( 'Overlay', 'crt-manage' ),
                    'darken' => esc_html__( 'Darken', 'crt-manage' ),
                    'lighten' => esc_html__( 'Lighten', 'crt-manage' ),
                    'color-dodge' => esc_html__( 'Color-dodge', 'crt-manage' ),
                    'color-burn' => esc_html__( 'Color-burn', 'crt-manage' ),
                    'hard-light' => esc_html__( 'Hard-light', 'crt-manage' ),
                    'soft-light' => esc_html__( 'Soft-light', 'crt-manage' ),
                    'difference' => esc_html__( 'Difference', 'crt-manage' ),
                    'exclusion' => esc_html__( 'Exclusion', 'crt-manage' ),
                    'hue' => esc_html__( 'Hue', 'crt-manage' ),
                    'saturation' => esc_html__( 'Saturation', 'crt-manage' ),
                    'color' => esc_html__( 'Color', 'crt-manage' ),
                    'luminosity' => esc_html__( 'luminosity', 'crt-manage' ),
                ],
                'selectors' => [
                    // '{{WRAPPER}} {{CURRENT_ITEM}} .crt-grid-media-hover-bg' => 'mix-blend-mode: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-media-hover-bg' => 'mix-blend-mode: {{VALUE}}', // Wasn't working because of the {{CURRENT_ITEM}} selector
                ],
                'separator' => 'after',
            ]
        );
    }

    public function add_control_overlay_border_color() {
        $this->add_control(
            'overlay_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-media-hover-bg' => 'border-color: {{VALUE}}',
                ],
            ]
        );
    }

    public function add_control_overlay_border_type() {
        $this->add_control(
            'overlay_border_type',
            [
                'label' => esc_html__( 'Border Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'solid' => esc_html__( 'Solid', 'crt-manage' ),
                    'double' => esc_html__( 'Double', 'crt-manage' ),
                    'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
                    'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
                    'groove' => esc_html__( 'Groove', 'crt-manage' ),
                ],
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-media-hover-bg' => 'border-style: {{VALUE}};',
                ],
            ]
        );
    }

    public function add_control_overlay_border_width() {
        $this->add_control(
            'overlay_border_width',
            [
                'label' => esc_html__( 'Border Width', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-media-hover-bg' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'overlay_border_type!' => 'none',
                ],
            ]
        );
    }

    public function add_control_tax1_custom_colors($meta) {
        $this->add_control(
            'tax1_custom_color_switcher',
            [
                'label' => esc_html__( 'Enable Custom Colors', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'tax1_custom_color_field_text',
            [
                'label' => esc_html__( 'Select Text Color Field', 'crt-manage' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'default' => 'default',
                'description' => '<strong>Note:</strong> This option only accepts Color Custom Field Values.',
                'options' => $meta,
                'condition' => [
                    'tax1_custom_color_switcher' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'tax1_custom_color_field_bg',
            [
                'label' => esc_html__( 'Select Background Color Field', 'crt-manage' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'default' => 'default',
                'description' => '<strong>Note:</strong> This option only accepts Color Custom Field Values.',
                'options' => $meta,
                'condition' => [
                    'tax1_custom_color_switcher' => 'yes'
                ],
            ]
        );
    }

    public function add_control_tax1_pointer_color_hr() {
        $this->add_control(
            'tax1_pointer_color_hr',
            [
                'label'  => esc_html__( 'Hover Effect Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-tax-style-1 .crt-pointer-item:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-tax-style-1 .crt-pointer-item:after' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );
    }

    public function add_control_tax1_pointer() {
        $this->add_control(
            'tax1_pointer',
            [
                'label' => esc_html__( 'Hover Effect', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'underline',
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'underline' => esc_html__( 'Underline', 'crt-manage' ),
                    'overline' => esc_html__( 'Overline', 'crt-manage' ),
                ],
            ]
        );
    }

    public function add_control_tax1_pointer_height() {
        $this->add_control(
            'tax1_pointer_height',
            [
                'label' => esc_html__( 'Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-tax-style-1 .crt-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-tax-style-1 .crt-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'tax1_pointer' => [ 'underline', 'overline' ],
                ],
            ]
        );
    }

    public function add_control_tax1_pointer_animation() {
        $this->add_control(
            'tax1_pointer_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'none' => 'None',
                    'fade' => 'Fade',
                    'slide' => 'Slide',
                    'grow' => 'Grow',
                    'drop' => 'Drop',
                ],
                'condition' => [
                    'tax1_pointer' => [ 'underline', 'overline' ],
                ],
            ]
        );
    }

    public function add_control_tax2_pointer_color_hr() {
        $this->add_control(
            'tax2_pointer_color_hr',
            [
                'label'  => esc_html__( 'Hover Effect Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-tax-style-2 .crt-pointer-item:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-tax-style-2 .crt-pointer-item:after' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );
    }

    public function add_control_tax2_pointer() {
        $this->add_control(
            'tax2_pointer',
            [
                'label' => esc_html__( 'Hover Effect', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'underline',
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'underline' => esc_html__( 'Underline', 'crt-manage' ),
                    'overline' => esc_html__( 'Overline', 'crt-manage' ),
                ],
            ]
        );
    }

    public function add_control_tax2_pointer_height() {
        $this->add_control(
            'tax2_pointer_height',
            [
                'label' => esc_html__( 'Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-tax-style-2 .crt-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-tax-style-2 .crt-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'tax2_pointer' => [ 'underline', 'overline' ],
                ],
            ]
        );
    }

    public function add_control_tax2_pointer_animation() {
        $this->add_control(
            'tax2_pointer_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'none' => 'None',
                    'fade' => 'Fade',
                    'slide' => 'Slide',
                    'grow' => 'Grow',
                    'drop' => 'Drop',
                ],
                'condition' => [
                    'tax2_pointer' => [ 'underline', 'overline' ],
                ],
            ]
        );
    }

    public function add_control_filters_pointer_color_hr() {
        $this->add_control(
            'filters_pointer_color_hr',
            [
                'label'  => esc_html__( 'Hover Effect Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-filters .crt-pointer-item:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-filters .crt-pointer-item:after' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );
    }

    public function add_control_filters_pointer() {
        $this->add_control(
            'filters_pointer',
            [
                'label' => esc_html__( 'Hover Effect', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'underline' => esc_html__( 'Underline', 'crt-manage' ),
                    'overline' => esc_html__( 'Overline', 'crt-manage' ),
                ],
            ]
        );
    }

    public function add_control_filters_pointer_height() {
        $this->add_control(
            'filters_pointer_height',
            [
                'label' => esc_html__( 'Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-filters .crt-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-filters .crt-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'filters_pointer' => [ 'underline', 'overline' ],
                ],
            ]
        );
    }

    public function add_control_filters_pointer_animation() {
        $this->add_control(
            'filters_pointer_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'none' => 'None',
                    'fade' => 'Fade',
                    'slide' => 'Slide',
                    'grow' => 'Grow',
                    'drop' => 'Drop',
                ],
                'condition' => [
                    'filters_pointer' => [ 'underline', 'overline' ],
                ],
            ]
        );
    }

    public function add_control_stack_grid_slider_nav_position() {
        $this->add_control(
            'grid_slider_nav_position',
            [
                'label' => esc_html__( 'Positioning', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'custom',
                'options' => [
                    'default' => esc_html__( 'Default', 'crt-manage' ),
                    'custom' => esc_html__( 'Custom', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-grid-slider-nav-position-',
            ]
        );

        $this->add_control(
            'grid_slider_nav_position_default',
            [
                'label' => esc_html__( 'Align', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'top-left',
                'options' => [
                    'top-left' => esc_html__( 'Top Left', 'crt-manage' ),
                    'top-center' => esc_html__( 'Top Center', 'crt-manage' ),
                    'top-right' => esc_html__( 'Top Right', 'crt-manage' ),
                    'bottom-left' => esc_html__( 'Bottom Left', 'crt-manage' ),
                    'bottom-center' => esc_html__( 'Bottom Center', 'crt-manage' ),
                    'bottom-right' => esc_html__( 'Bottom Right', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-grid-slider-nav-align-',
                'condition' => [
                    'grid_slider_nav_position' => 'default',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_slider_nav_outer_distance',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Outer Distance', 'crt-manage' ),
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}}[class*="crt-grid-slider-nav-align-top"] .crt-grid-slider-arrow-container' => 'top: {{SIZE}}px;',
                    '{{WRAPPER}}[class*="crt-grid-slider-nav-align-bottom"] .crt-grid-slider-arrow-container' => 'bottom: {{SIZE}}px;',
                    '{{WRAPPER}}.crt-grid-slider-nav-align-top-left .crt-grid-slider-arrow-container' => 'left: {{SIZE}}px;',
                    '{{WRAPPER}}.crt-grid-slider-nav-align-bottom-left .crt-grid-slider-arrow-container' => 'left: {{SIZE}}px;',
                    '{{WRAPPER}}.crt-grid-slider-nav-align-top-right .crt-grid-slider-arrow-container' => 'right: {{SIZE}}px;',
                    '{{WRAPPER}}.crt-grid-slider-nav-align-bottom-right .crt-grid-slider-arrow-container' => 'right: {{SIZE}}px;',
                ],
                'condition' => [
                    'grid_slider_nav_position' => 'default',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_slider_nav_inner_distance',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Inner Distance', 'crt-manage' ),
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-slider-arrow-container .crt-grid-slider-prev-arrow' => 'margin-right: {{SIZE}}px;',
                ],
                'condition' => [
                    'grid_slider_nav_position' => 'default',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_slider_nav_position_top',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Vertical Position', 'crt-manage' ),
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'min' => -20,
                        'max' => 120,
                    ],
                    'px' => [
                        'min' => -200,
                        'max' => 2000,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-slider-arrow' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'grid_slider_nav_position' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_slider_nav_position_left',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Left Position', 'crt-manage' ),
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 120,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-slider-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'grid_slider_nav_position' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_slider_nav_position_right',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Right Position', 'crt-manage' ),
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 120,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-slider-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'grid_slider_nav_position' => 'custom',
                ],
            ]
        );
    }

    public function add_control_grid_slider_dots_hr() {
        $this->add_responsive_control(
            'grid_slider_dots_hr',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Horizontal Position', 'crt-manage' ),
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'min' => -20,
                        'max' => 120,
                    ],
                    'px' => [
                        'min' => -200,
                        'max' => 2000,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-slider-dots' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
    }

    public function add_control_read_more_animation_height() {
        $this->add_control(
            'read_more_animation_height',
            [
                'label' => esc_html__( 'Animation Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} [class*="crt-button-underline"]:before' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} [class*="crt-button-overline"]:before' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'read_more_animation' => [
                        'crt-button-underline-from-left',
                        'crt-button-underline-from-center',
                        'crt-button-underline-from-right',
                        'crt-button-underline-reveal',
                        'crt-button-overline-reveal',
                        'crt-button-overline-from-left',
                        'crt-button-overline-from-center',
                        'crt-button-overline-from-right'
                    ]
                ],
            ]
        );
    }

    public $post_taxonomies;

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Query ------------
		$this->start_controls_section(
			'section_grid_query',
			[
				'label' => esc_html__( 'Query', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		// Get Available Post Types
		$this->post_types = $this->add_option_query_source();

		// Get Available Taxonomies
		$this->post_taxonomies = $this->get_available_taxonomies();

		// Get Available Meta Keys
		$tax_meta_keys = Utilities::get_custom_meta_keys_tax();

		$this->add_control(
			'query_source',
			[
				'label' => esc_html__( 'Source', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $this->post_types,
			]
		);

		// Upgrade to Pro Notice


		$this->add_control(
			'query_selection',
			[
				'label' => esc_html__( 'Selection', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic' => esc_html__( 'Dynamic', 'crt-manage' ),
					'manual' => esc_html__( 'Manual', 'crt-manage' ),
				],
				'condition' => [
					'query_source!' => [ 'current', 'related' ],
				],
			]
		);

		$this->add_control_order_posts();

		$this->add_control_order_posts_by_acf( [] );

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'grid', 'order_posts', ['pro-tl', 'pro-mf', 'pro-d', 'pro-ar', 'pro-cc'] );

		$this->add_control(
			'order_direction',
			[
				'label' => esc_html__( 'Order', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'label_block' => false,
				'options' => [
					'ASC' => esc_html__( 'Ascending', 'crt-manage'),
					'DESC' => esc_html__( 'Descending', 'crt-manage'),
				],
				'condition' => [
					'query_randomize!' => 'rand',
				]
			]
		);

		$this->add_control(
			'query_tax_selection',
			[
				'label' => esc_html__( 'Select Taxonomy', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => $this->post_taxonomies,
				'condition' => [
					'query_source' => 'related',
				],
			]
		);


		$this->add_control(
			'query_author',
			[
				'label' => esc_html__( 'Authors', 'crt-manage' ),
				'type' => 'crt-ajax-select2',
				'options' => 'ajaxselect2/get_users',
				'multiple' => true,
				'label_block' => true,
				'separator' => 'before',
				'condition' => [
					'query_source!' => [ 'current', 'related' ],
					'query_selection' => 'dynamic',
				],
			]
		);
		
		// Taxonomies
		foreach ( $this->post_taxonomies as $slug => $title ) {
			global $wp_taxonomies;
			$post_type = '';

			if ( isset($wp_taxonomies[$slug]) && isset($wp_taxonomies[$slug]->object_type[0]) ) {
				$post_type = $wp_taxonomies[$slug]->object_type[0];
			}

			$this->add_control(
				'query_taxonomy_'. $slug,
				[
					'label' => $title,
					'type' => 'crt-ajax-select2',
					'options' => 'ajaxselect2/get_taxonomies',
					'query_slug' => $slug,
					'multiple' => true,
					'label_block' => true,
					'condition' => [
						'query_source' => $post_type,
						'query_selection' => 'dynamic',
					],
				]
			);
		}

		// Exclude
		foreach ( $this->post_types as $slug => $title ) {
			$this->add_control(
				'query_exclude_'. $slug,
				[
					'label' => esc_html__( 'Exclude ', 'crt-manage' ) . $title,
					'type' => 'crt-ajax-select2',
					'options' => 'ajaxselect2/get_posts_by_post_type',
					'query_slug' => $slug,
					'multiple' => true,
					'label_block' => true,
					'condition' => [
						'query_source' => $slug,
						'query_source!' => [ 'current', 'related' ],
						'query_selection' => 'dynamic',
					],
				]
			);
		}

		// Manual Selection
		foreach ( $this->post_types as $slug => $title ) {
			$this->add_control(
				'query_manual_'. $slug,
				[
					'label' => esc_html__( 'Select ', 'crt-manage' ) . $title,
					'type' => 'crt-ajax-select2',
					'options' => 'ajaxselect2/get_posts_by_post_type',
					'query_slug' => $slug,
					'multiple' => true,
					'label_block' => true,
					'condition' => [
						'query_source' => $slug,
						'query_selection' => 'manual',
					],
					'separator' => 'before',
				]
			);
		}

		$qqq_condition = Utilities::is_new_free_user() ? [ 'query_source!' => 'current', 'layout_select!' => 'slider', ] : [ 'query_source!' => 'current' ];

		$this->add_control(
			'query_posts_per_page',
			[
				'label' => esc_html__( 'Items Per Page', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 9,
				'min' => 0,
				'condition' => $qqq_condition,
			]
		);

        $this->add_control_query_slides_to_show();

		$this->add_control(
			'query_offset',
			[
				'label' => esc_html__( 'Offset', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'condition' => [
					'query_selection' => 'dynamic',
				]
			]
		);

		$this->add_control(
			'query_not_found_text',
			[
				'label' => esc_html__( 'Not Found Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'No Posts Found!',
				'condition' => [
					'query_selection' => 'dynamic',
					'query_source!' => 'related',
				]
			]
		);

		$this->add_control_display_scheduled_posts();

		$this->add_control_query_randomize();

		$this->add_control(
			'query_exclude_no_images',
			[
				'label' => esc_html__( 'Exclude Items without Thumbnail', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false
			]
		);

		$this->add_control(
			'advanced_filters',
			[
				'label' => esc_html__( 'Enable Advanced Filters', 'crt-manage' ),
				'description' => esc_html__( 'Turn on Only with Advanced Filters widget.', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'element_select_filter',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => CRT_Grid_Helpers::get_related_taxonomies(),
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Layout -----------
		$this->start_controls_section(
			'section_grid_layout',
			[
				'label' => esc_html__( 'Layout', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_layout_select();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'grid', 'layout_select', ['pro-ms'] );

		$this->add_control(
			'stick_last_element_to_bottom',
			[
				'label' => esc_html__( 'Last Element to Bottom', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'render_type' => 'template',
				// 'separator' => 'before',
				'condition' => [
					'layout_select' => 'fitRows',
				]
			]
		);

		$this->add_control(
			'last_element_position',
			[
				'label' => esc_html__( 'Last Element Position', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-h-align-right',
					]
				],
				'selectors_dictionary' => [
					'left' => 'left: 0; right: auto;',
					'center' => 'left: 50%; transform: translateX(-50%);',
					'right' => 'left: auto; right: 0;'
				],
				'selectors' => [
					'{{WRAPPER}}.crt-grid-last-element-yes .crt-grid-item-below-content>div:last-child' => '{{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'layout_image_crop',
				'default' => 'full',
			]
		);

		$this->add_control_layout_columns();

		// Media
		$this->add_control(
			'layout_list_media_section',
			[
				'label' => esc_html__( 'Media', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'list',
				],
			]
		);

		$this->add_control(
			'layout_list_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'crt-manage' ),
					'right' => esc_html__( 'Right', 'crt-manage' ),
					'zigzag' => esc_html__( 'ZigZag', 'crt-manage' ),
				],
				'condition' => [
					'layout_select' => 'list',
				],
			]
		);

		$this->add_control(
			'layout_list_media_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'layout_select' => 'list',
				]
			]
		);

		$this->add_control(
			'layout_list_media_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'layout_select' => 'list',
				]
			]
		);

		$this->add_responsive_control(
			'layout_gutter_hr',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'size' => 15,
				],
				'widescreen_default' => [
					'size' => 15,
				],
				'laptop_default' => [
					'size' => 15,
				],
				'tablet_extra_default' => [
					'size' => 15,
				],
				'tablet_default' => [
					'size' => 15,
				],
				'mobile_extra_default' => [
					'size' => 15,
				],
				'mobile_default' => [
					'size' => 15,
				],
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'layout_gutter_vr',
			[
				'label' => esc_html__( 'Vertical Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'size' => 15,
				],
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
				]
			]
		);

		$this->add_responsive_control(
			'layout_filters',
			[
				'label' => esc_html__( 'Show Filters', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters' => 'display:{{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select!' => 'slider',
				]
			]
		);

		$this->add_control(
			'layout_pagination',
			[
				'label' => esc_html__( 'Show Pagination', 'crt-manage' ),
				'description' => esc_html__('Please note that Pagination doesn\'t work in editor', 'crt-manage'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'render_type' => 'template',
				'condition' => [
					'layout_select!' => 'slider',
				]
			]
		);

		$this->add_control_open_links_in_new_tab();
		
		$this->add_control_grid_lazy_loading();

		$this->add_control_layout_animation();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'grid', 'layout_animation', ['pro-fd', 'pro-fs'] );

		$this->add_control(
			'layout_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'condition' => [
					'layout_animation!' => 'default',
					'layout_select!' => 'slider',
				],
			]
		);

		$this->add_control(
			'layout_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.05,
				'condition' => [
					'layout_animation!' => 'default',
					'layout_select!' => 'slider',
				],
			]
		);

		$this->add_control_layout_slider_amount();

		$this->add_control(
			'layout_slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'default' => 1,
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_responsive_control(
			'layout_slider_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],			
				'selectors' => [
					'{{WRAPPER}} .crt-grid .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'layout_slider_amount!' => '1',
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_responsive_control(
			'layout_slider_nav',
			[
				'label' => esc_html__( 'Navigation', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'flex'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-arrow' => 'display:{{VALUE}} !important;',
				],
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'slider',
				]
			]
		);

		$this->add_control_layout_slider_nav_hover();

		// $this->add_control(
		// 	'layout_slider_nav_icon',
		// 	[
		// 		'label' => esc_html__( 'Select Icon', 'crt-manage' ),
		// 		'type' => 'crt-arrow-icons',
		// 		'default' => 'fas fa-angle',
		// 		'separator' => 'after',
		// 		'condition' => [
		// 			'layout_slider_nav' => 'yes',
		// 			'layout_select' => 'slider',
		// 		]
		// 	]
		// );

		// GOGA - change to new control
		$this->add_control(
			'layout_slider_nav_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'svg-angle-1-left',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle-left' => esc_html__( 'Angle', 'crt-manage' ),
					'fas fa-angle-double-left' => esc_html__( 'Angle Double', 'crt-manage' ),
					'fas fa-arrow-left' => esc_html__( 'Arrow', 'crt-manage' ),
					'fas fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle', 'crt-manage' ),
					'far fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle Alt', 'crt-manage' ),
					'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'crt-manage' ),
					'fas fa-chevron-left' => esc_html__( 'Chevron', 'crt-manage' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'crt-manage' ),
				] ),
				'separator' => 'after',
				'condition' => [
					'layout_slider_nav' => 'yes',
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_responsive_control(
			'layout_slider_dots',
			[
				'label' => esc_html__( 'Pagination', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'inline-table'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-dots' => 'display:{{VALUE}};',
				],
				'render_type' => 'template',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_control_layout_slider_dots_position();

		// Upgrade to Pro Notice
//		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'grid', 'layout_slider_dots_position', ['pro-vr'] );

		$this->add_control_layout_slider_autoplay();

		$this->add_controls_group_layout_slider_autoplay();

		$this->add_control(
			'layout_slider_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
				'frontend_available' => true,
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);
		
		$this->add_control(
			'layout_slider_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'crt-manage' ),
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'crt-manage' ),
					'fade' => esc_html__( 'Fade', 'crt-manage' ),
				],
				'condition' => [
					'layout_slider_amount' => 1,
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_control(
			'layout_slider_effect_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'condition' => [
					'layout_slider_amount' => 1,
					'layout_select' => 'slider',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Elements ---------
		$this->start_controls_section(
			'section_grid_elements',
			[
				'label' => esc_html__( 'Elements', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$element_select = $this->add_option_element_select();

		$repeater->add_control(
			'element_select',
			[
				'label' => esc_html__( 'Select Element', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => array_merge( $element_select, $this->post_taxonomies ),
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'show_last_update_date',
			[
				'label' => esc_html__( 'Show Last Update Date', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'after',
				'condition' => [
					'element_select' => 'date',
				]
			]
		);

		$repeater->add_control(
			'element_custom_field_video_tutorial',
			[
				'raw' => esc_html__( 'Watch Custom Fields ', 'crt-manage' ) . sprintf( '<a href="%1$s" target="_blank">%2$s <span class="dashicons dashicons-video-alt3"></span></a>', '', esc_html__( 'Video Tutorial', 'crt-manage' ) ),
				'type' => Controls_Manager::RAW_HTML,
				'condition' => [
					'element_select' => ['custom-field', 'pro-cf']
				]
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'grid', 'element_select', ['pro-lk', 'pro-shr'] );
//		Utilities::upgrade_expert_notice( $repeater, Controls_Manager::RAW_HTML, 'grid', 'element_select', ['pro-cf'] );

		$repeater->add_control(
			'element_location',
			[
				'label' => esc_html__( 'Location', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'below',
				'options' => [
					'above' => esc_html__( 'Above Media', 'crt-manage' ),
					'over' => esc_html__( 'Over Media', 'crt-manage' ),
					'below' => esc_html__( 'Below Media', 'crt-manage' ),
				]
			]
		);

		$repeater->add_control(
			'element_display',
			[
				'label' => esc_html__( 'Display', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'inline' => esc_html__( 'Inline', 'crt-manage' ),
					'block' => esc_html__( 'Seperate Line', 'crt-manage' ),
					'custom' => esc_html__( 'Custom Width', 'crt-manage' ),
				],
			]
		);

		$repeater->add_control(
			'element_custom_width',
			[
				'label' => esc_html__( 'Element Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
				],
				'condition' => [
					'element_display' => 'custom',
				],
			]
		);


		$repeater->add_control(
			'element_align_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'middle',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'crt-manage' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'crt-manage' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'crt-manage' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'condition' => [
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
			'element_align_hr',
			[
				'label' => esc_html__( 'Horizontal Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-h-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h2',
				'condition' => [
					'element_select' => 'title',
				]
			]
		);

		$repeater->add_control(
			'element_dropcap',
			[
				'label' => esc_html__( 'Enable Drop Cap', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_select' => [ 'content', 'excerpt' ],
				]
			]
		);

		$repeater->add_control(
			'element_trim_text_by',
			[
				'label' => esc_html__( 'Trim Text By', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'word_count',
				'options' => $this->add_repeater_args_element_trim_text_by(),
				'separator' => 'after',
				'condition' => [
					'element_select' => [ 'title', 'excerpt' ],
				]
			]
		);

		$repeater->add_control(
			'element_word_count',
			[
				'label' => esc_html__( 'Word Count', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 20,
				'min' => 1,
				'condition' => [
					'element_select' => [ 'title', 'excerpt' ],
					'element_trim_text_by' => 'word_count'
				]
			]
		);

		$repeater->add_control(
			'element_letter_count',
			[
				'label' => esc_html__( 'Letter Count', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 40,
				'min' => 1,
				'condition' => [
					'element_select' => [ 'title', 'excerpt' ],
					'element_trim_text_by' => 'letter_count'
				]
			]
		);

		$repeater->add_control(
			'element_show_dots',
			[
				'label' => esc_html__( 'Show Dots', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'element_select' => [ 'excerpt' ],
					'element_trim_text_by' => 'word_count'
				]
			]
		);

		$repeater->add_control(
			'element_show_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_select' => [ 'author' ]
				]
			]
		);

		$repeater->add_control(
			'element_avatar_size',
			[
				'label' => esc_html__( 'Avatar Size', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 16,
				'min' => 8,
				'condition' => [
					'element_select' => [ 'author' ],
					'element_show_avatar' => 'yes'
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_read_more_text',
			[
				'label' => esc_html__( 'Read More Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Read More',
				'condition' => [
					'element_select' => [ 'read-more' ],
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_tax_sep',
			[
				'label' => esc_html__( 'Separator', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => ', ',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'excerpt',
						'date',
						'time',
						'author',
						'comments',
						'read-more',
						'likes',
						'sharing',
						'lightbox',
						'custom-field',
						'separator',
						'post_format',
					],
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_tax_style',
			[
				'label' => esc_html__( 'Select Styling', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'crt-grid-tax-style-1',
				'options' => [
					'crt-grid-tax-style-1' => esc_html__( 'Taxonomy Style 1', 'crt-manage' ),
					'crt-grid-tax-style-2' => esc_html__( 'Taxonomy Style 2', 'crt-manage' ),
				],
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'excerpt',
						'date',
						'time',
						'author',
						'comments',
						'read-more',
						'likes',
						'sharing',
						'lightbox',
						'custom-field',
						'separator',
					],
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_comments_text_1',
			[
				'label' => esc_html__( 'No Comments', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'No Comments',
				'condition' => [
					'element_select' => [ 'comments' ],
				]
			]
		);

		$repeater->add_control(
			'element_comments_text_2',
			[
				'label' => esc_html__( 'One Comment', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Comment',
				'condition' => [
					'element_select' => [ 'comments' ],
				]
			]
		);

		$repeater->add_control(
			'element_comments_text_3',
			[
				'label' => esc_html__( 'Multiple Comments', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Comments',
				'condition' => [
					'element_select' => [ 'comments' ],
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control( 'element_like_icon', $this->add_repeater_args_element_like_icon() );

		$repeater->add_control( 'element_like_show_count', $this->add_repeater_args_element_like_show_count() );

		$repeater->add_control( 'element_like_text', $this->add_repeater_args_element_like_text() );

		$repeater->add_control( 'element_sharing_icon_1', $this->add_repeater_args_element_sharing_icon_1() );

		$repeater->add_control( 'element_sharing_icon_2', $this->add_repeater_args_element_sharing_icon_2() );

		$repeater->add_control( 'element_sharing_icon_3', $this->add_repeater_args_element_sharing_icon_3() );

		$repeater->add_control( 'element_sharing_icon_4', $this->add_repeater_args_element_sharing_icon_4() );

		$repeater->add_control( 'element_sharing_icon_5', $this->add_repeater_args_element_sharing_icon_5() );

		$repeater->add_control( 'element_sharing_icon_6', $this->add_repeater_args_element_sharing_icon_6() );

		$repeater->add_control( 'element_sharing_trigger', $this->add_repeater_args_element_sharing_trigger() );

		$repeater->add_control( 'element_sharing_trigger_icon', $this->add_repeater_args_element_sharing_trigger_icon() );

		$repeater->add_control( 'element_sharing_trigger_action', $this->add_repeater_args_element_sharing_trigger_action() );

		$repeater->add_control( 'element_sharing_trigger_direction', $this->add_repeater_args_element_sharing_trigger_direction() );

		$repeater->add_control( 'element_sharing_tooltip', $this->add_repeater_args_element_sharing_tooltip() );

		$repeater->add_control(
			'element_lightbox_pfa_select',
			[
				'label' => esc_html__( 'Post Format Audio', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'crt-manage' ),
					'meta' => esc_html__( 'Meta Value', 'crt-manage' ),
				],
				'condition' => [
					'element_select' => 'lightbox',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_pfa_meta',
			[
				'label' => esc_html__( 'Audio Meta Value', 'crt-manage' ),
				'type' => 'crt-ajax-select2',
				'label_block' => true,
				'default' => 'default',
				'options' => 'ajaxselect2/get_custom_meta_keys',
				'condition' => [
					'element_select' => 'lightbox',
					'element_lightbox_pfa_select' => 'meta',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_pfv_select',
			[
				'label' => esc_html__( 'Post Format Video', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'crt-manage' ),
					'meta' => esc_html__( 'Meta Value', 'crt-manage' ),
				],
				'condition' => [
					'element_select' => 'lightbox',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_pfv_meta',
			[
				'label' => esc_html__( 'Video Meta Value', 'crt-manage' ),
				'type' => 'crt-ajax-select2',
				'label_block' => true,
				'default' => 'default',
				'options' => 'ajaxselect2/get_custom_meta_keys',
				'condition' => [
					'element_select' => 'lightbox',
					'element_lightbox_pfv_select' => 'meta',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'after',
				'condition' => [
					'element_select' => [ 'lightbox' ],
				],
			]
		);

		$repeater->add_control( 'element_custom_field', $this->add_repeater_args_element_custom_field( [] ) );

		$repeater->add_control( 'element_custom_field_img_ID', $this->add_repeater_args_element_custom_field_img_ID() );

		$repeater->add_control( 'element_custom_field_btn_link', $this->add_repeater_args_element_custom_field_btn_link() );

		$repeater->add_control( 'element_custom_field_new_tab', $this->add_repeater_args_element_custom_field_new_tab() );

		$repeater->add_control( 'custom_field_wrapper_html_divider1', $this->add_repeater_args_custom_field_wrapper_html_divider1() );

		$repeater->add_control( 'element_custom_field_wrapper', $this->add_repeater_args_element_custom_field_wrapper() );

		$repeater->add_control( 'element_custom_field_wrapper_html', $this->add_repeater_args_element_custom_field_wrapper_html() );

		$repeater->add_control( 'custom_field_wrapper_html_divider2', $this->add_repeater_args_custom_field_wrapper_html_divider2() );

		$repeater->add_control( 'element_cf_tag', $this->add_repeater_args_element_cf_tag() );

		$repeater->add_control( 'element_custom_field_style', $this->add_repeater_args_element_custom_field_style() );

		$repeater->add_control(
			'element_separator_style',
			[
				'label' => esc_html__( 'Select Styling', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'crt-grid-sep-style-1',
				'options' => [
					'crt-grid-sep-style-1' => esc_html__( 'Separator Style 1', 'crt-manage' ),
					'crt-grid-sep-style-2' => esc_html__( 'Separator Style 2', 'crt-manage' ),
				],
				'condition' => [
					'element_select' => 'separator',
				]
			]
		);

		$repeater->add_control(
			'element_extra_text_pos',
			[
				'label' => esc_html__( 'Extra Text Display', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'before' => esc_html__( 'Before Element', 'crt-manage' ),
					'after' => esc_html__( 'After Element', 'crt-manage' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'excerpt',
						'read-more',
						'separator',
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_text',
			[
				'label' => esc_html__( 'Extra Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'excerpt',
						'read-more',
						'separator',
					],
					'element_extra_text_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon_pos',
			[
				'label' => esc_html__( 'Extra Icon Position', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'before' => esc_html__( 'Before Element', 'crt-manage' ),
					'after' => esc_html__( 'After Element', 'crt-manage' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'excerpt',
						'separator',
						'likes',
						'sharing',
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'excerpt',
						'separator',
						'likes',
						'sharing',
					],
					'element_extra_icon_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => 'crt-animations',
				'default' => 'none',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		// Upgrade to Pro Notice :TODO
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'grid', 'element_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

		$repeater->add_control(
			'element_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
			'element_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-animation-wrap:hover {{CURRENT_ITEM}}' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::crt_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'grid', 'element_animation_timing', ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk',] );

		$repeater->add_control(
			'element_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'crt-manage' ),
					'medium' => esc_html__( 'Medium', 'crt-manage' ),
					'large' => esc_html__( 'Large', 'crt-manage' ),
				],
				'default' => 'large',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_disable_mobile',
			[
				'label' => esc_html__( 'Disable on Mobile/Tablet', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_responsive_control(
			'element_show_on',
			[
				'label' => esc_html__( 'Show on this Device', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'position: absolute; left: -99999999px;',
					'yes' => 'position: static; left: auto;'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'grid_elements',
			[
				'label' => esc_html__( 'Grid Elements', 'crt-manage' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'element_select' => 'title',
					],
					[
						'element_select' => 'date',
						'element_display' => 'inline',
						'element_extra_text_pos' => 'after',
						'element_extra_text' => '/',
					],
					[
						'element_select' => 'comments',
						'element_display' => 'inline',
					],
					[
						'element_select' => 'excerpt',
					],
					[
						'element_select' => 'read-more',
					],
				],
				'title_field' => '{{{ element_select.charAt(0).toUpperCase() + element_select.slice(1) }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_image_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'overlay_width',
			[
				'label' => esc_html__( 'Overlay Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_hegiht',
			[
				'label' => esc_html__( 'Overlay Hegiht', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'overlay_post_link',
			[
				'label' => esc_html__( 'Link to Single Page', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => 'crt-animations-alt',
				'default' => 'fade-in',
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'grid', 'overlay_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

		$this->add_control(
			'overlay_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-animation-wrap:hover .crt-grid-media-hover-bg' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::crt_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'grid', 'overlay_animation_timing', Utilities::crt_animation_timing_pro_conditions());

		$this->add_control(
			'overlay_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'crt-manage' ),
					'medium' => esc_html__( 'Medium', 'crt-manage' ),
					'large' => esc_html__( 'Large', 'crt-manage' ),
				],
				'default' => 'large',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control_overlay_animation_divider();

		$this->add_control_overlay_image();

		$this->add_control_overlay_image_width();

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Image Effects ----
		$this->start_controls_section(
			'section_image_effects',
			[
				'label' => esc_html__( 'Image Effects', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_secondary_img_on_hover();

		$this->add_control_image_effects();

		$this->add_control(
			'image_effects_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-wrap img' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-wrap:hover img' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::crt_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'grid', 'image_effects_animation_timing', Utilities::crt_animation_timing_pro_conditions());

		$this->add_control(
			'image_effects_size',
			[
				'label' => esc_html__( 'Animation Size', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'crt-manage' ),
					'medium' => esc_html__( 'Medium', 'crt-manage' ),
					'large' => esc_html__( 'Large', 'crt-manage' ),
				],
				'default' => 'medium',
				'condition' => [
					'image_effects!' => ['none', 'slide'],
				]
			]
		);

		$this->add_control(
			'image_effects_direction',
			[
				'label' => esc_html__( 'Animation Direction', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => esc_html__( 'Top', 'crt-manage' ),
					'right' => esc_html__( 'Right', 'crt-manage' ),
					'bottom' => esc_html__( 'Bottom', 'crt-manage' ),
					'left' => esc_html__( 'Left', 'crt-manage' ),
				],
				'default' => 'bottom',
				'condition' => [
					'image_effects!' => 'none',
					'image_effects' => 'slide'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Lightbox Popup ---
		$this->start_controls_section(
			'section_lightbox_popup',
			[
				'label' => esc_html__( 'Lightbox Popup', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'lightbox_popup_autoplay',
			[
				'label' => esc_html__( 'Autoplay Slides', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_progressbar',
			[
				'label' => esc_html__( 'Show Progress Bar', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
				'condition' => [
					'lightbox_popup_autoplay' => 'true'
				]
			]
		);

		$this->add_control(
			'lightbox_popup_pause',
			[
				'label' => esc_html__( 'Autoplay Speed', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'condition' => [
					'lightbox_popup_autoplay' => 'true',
				],
			]
		);

		$this->add_control(
			'lightbox_popup_counter',
			[
				'label' => esc_html__( 'Show Counter', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_captions',
			[
				'label' => esc_html__( 'Show Captions', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control_lightbox_popup_thumbnails();

		$this->add_control_lightbox_popup_thumbnails_default();

		$this->add_control_lightbox_popup_sharing();

		$this->add_control(
			'lightbox_popup_zoom',
			[
				'label' => esc_html__( 'Show Zoom Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_fullscreen',
			[
				'label' => esc_html__( 'Show Full Screen Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_download',
			[
				'label' => esc_html__( 'Show Download Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_description',
			[
				'raw' => sprintf(__( 'You can change Lightbox Popup styling options globaly. Navigate to <strong>Dashboard > %s > Settings</strong>.', 'crt-manage' ), Utilities::get_plugin_name()),
				'type' => Controls_Manager::RAW_HTML,
				'separator' => 'before',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Filters ----------
		$this->start_controls_section(
			'section_grid_filters',
			[
				'label' => esc_html__( 'Filters', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'layout_select!' => 'slider',
					'layout_filters' => 'yes',
				],
			]
		);

		$this->add_control(
			'filters_select',
			[
				'label' => esc_html__( 'Select Taxonomy', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->post_taxonomies,
				'default' => 'category',
			]
		);


		$this->add_control(
			'filters_linkable',
			[
				'label' => esc_html__( 'Set Linkable Filters', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'filters_hide_empty',
			[
				'label' => esc_html__( 'Hide Empty Filters', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'filters_linkable!' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'filters_hide_uncategorized',
			[
				'label' => esc_html__( 'Hide Uncategorized', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'filters_linkable!' => 'yes',
				],
			]
		);

		$this->add_control_filters_deeplinking();

		$this->add_control(
			'filters_experiment',
			[
				'label' => esc_html__( 'Enable AJAX Loading', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'render_type' => 'template',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'layout_filters',
							'operator' => '!=',
							'value' => '',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [	
										[
											'name' => 'layout_pagination',
											'operator' => '!=',
											'value' => '',
										],
										[
											'name' => 'pagination_type',
											'operator' => 'in',
											'value' => ['load-more', 'infinite'],
										],
									]
								],
								[	
									'name' => 'layout_pagination',
									'operator' => '==',
									'value' => '',
								]
							],
						],
					]
				]
			]
		);

		$this->add_control(
			'filters_all',
			[
				'label' => esc_html__( 'Show "All" Filter', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'filters_linkable!' => 'yes',
				],
			]
		);

		$this->add_control(
			'filters_all_text',
			[
				'label' => esc_html__( '"All" Filter Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'All Posts',
				'condition' => [
					'filters_all' => 'yes',
					'filters_linkable!' => 'yes',
				],
			]
		);

		$this->add_control_filters_count();

		$this->add_control_filters_count_superscript();

		$this->add_control_filters_count_brackets();

		$this->add_control_filters_default_filter();

		$this->add_control_filters_icon();

		$this->add_control_filters_icon_align();

		$this->add_control(
			'filters_separator',
			[
				'label' => esc_html__( 'Separator', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'filters_separator_align',
			[
				'label' => esc_html__( 'Separator Position', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-h-align-right',
					]
				],
				'condition' => [
					'filters_separator!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'filters_align',
			[
				'label' => esc_html__( 'Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-h-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_filters_animation();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'grid', 'filters_animation', ['pro-fd', 'pro-fs'] );

		$this->add_control(
			'filters_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'condition' => [
					'filters_animation!' => 'default',
				],
			]
		);

		$this->add_control(
			'filters_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.05,
				'condition' => [
					'filters_animation!' => 'default'
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Pagination -------
		$this->start_controls_section(
			'section_grid_pagination',
			[
				'label' => esc_html__( 'Pagination', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'layout_select!' => 'slider',
					'layout_pagination' => 'yes',
				],
			]
		);

		$this->add_control_pagination_type();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'grid', 'pagination_type', ['pro-is', 'pro-nb'] );

		$this->add_control(
			'pagination_older_text',
			[
				'label' => esc_html__( 'Older Posts Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Older Posts',
				'condition' => [
					'pagination_type' => 'default',
				],
			]
		);

		$this->add_control(
			'pagination_newer_text',
			[
				'label' => esc_html__( 'Newer Posts Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Newer Posts',
				'condition' => [
					'pagination_type' => 'default',
				]
			]
		);

		$this->add_control(
			'pagination_on_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle' => esc_html__( 'Angle', 'crt-manage' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'crt-manage' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'crt-manage' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'crt-manage' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'crt-manage' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'crt-manage' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'crt-manage' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'crt-manage' ),
				] ),
				'condition' => [
					'pagination_type' => 'default'
				],
			]
		);

		$this->add_control(
			'pagination_prev_next',
			[
				'label' => esc_html__( 'Previous & Next Buttons', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'pagination_type' => 'numbered',
				],
			]
		);

		$this->add_control(
			'pagination_prev_text',
			[
				'label' => esc_html__( 'Prev Page Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Previous Page',
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_prev_next' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_next_text',
			[
				'label' => esc_html__( 'Next Page Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Next Page',
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_prev_next' => 'yes',
				]
			]
		);

		$this->add_control(
			'pagination_pn_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle' => esc_html__( 'Angle', 'crt-manage' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'crt-manage' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'crt-manage' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'crt-manage' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'crt-manage' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'crt-manage' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'crt-manage' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'crt-manage' ),
				] ),
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_prev_next' => 'yes'
				],
			]
		);

		$this->add_control(
			'pagination_first_last',
			[
				'label' => esc_html__( 'First & Last Buttons', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'pagination_type' => 'numbered',
				],
			]
		);

		$this->add_control(
			'pagination_first_text',
			[
				'label' => esc_html__( 'First Page Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'First Page',
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_first_last' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_last_text',
			[
				'label' => esc_html__( 'Last Page Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Last Page',
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_first_last' => 'yes',
				]
			]
		);

		$this->add_control(
			'pagination_fl_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle-double',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle' => esc_html__( 'Angle', 'crt-manage' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'crt-manage' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'crt-manage' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'crt-manage' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'crt-manage' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'crt-manage' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'crt-manage' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'crt-manage' ),
				] ),
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_first_last' => 'yes'
				],
			]
		);

		$this->add_control(
			'pagination_disabled_arrows',
			[
				'label' => esc_html__( 'Show Disabled Buttons', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'pagination_type' => [ 'default', 'numbered' ],
				],
			]
		);

		$this->add_control(
			'pagination_range',
			[
				'label' => esc_html__( 'Range', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 2,
				'min' => 1,
				'condition' => [
					'pagination_type' => 'numbered',
				]
			]
		);

		$this->add_control(
			'pagination_load_more_text',
			[
				'label' => esc_html__( 'Load More Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Load More',
				'condition' => [
					'pagination_type' => 'load-more',
				]
			]
		);

		$this->add_control(
			'pagination_finish_text',
			[
				'label' => esc_html__( 'Finish Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'End of Content.',
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ],
				]
			]
		);

		$this->add_control(
			'pagination_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'loader-1',
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'loader-1' => esc_html__( 'Loader 1', 'crt-manage' ),
					'loader-2' => esc_html__( 'Loader 2', 'crt-manage' ),
					'loader-3' => esc_html__( 'Loader 3', 'crt-manage' ),
					'loader-4' => esc_html__( 'Loader 4', 'crt-manage' ),
					'loader-5' => esc_html__( 'Loader 5', 'crt-manage' ),
					'loader-6' => esc_html__( 'Loader 6', 'crt-manage' ),
				],
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ],
				]
			]
		);

		$this->add_control(
			'pagination_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'crt-manage' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'center',
				'prefix_class' => 'crt-grid-pagination-',
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'pagination_type!' => 'infinite-scroll',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
//		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'grid', [
//			'Grid Columns 1,2,3,4,5,6',
//			'Masonry Layout',
//			'List Layout Zig-zag',
//			'Posts Slider Columns (Carousel) 1,2,3,4,5,6',
//			'Secondary Featured Image',
//			'Related Posts Query, Current Page Query, Random Posts Query',
//			'Infinite Scrolling Pagination',
//			'Post Slider Autoplay options',
//			'Post Slider Advanced Navigation Positioning',
//			'Post Slider Advanced Pagination Positioning',
//			'Advanced Post Likes',
//			'Advanced Post Sharing',
//			'Advanced Grid Loading Animations (Fade in & Slide Up)',
//			'Advanced Grid Elements Positioning',
//			'Unlimited Image Overlay Animations',
//			'Image overlay GIF upload option',
//			'Image Overlay Blend Mode',
//			'Image Effects: Zoom, Grayscale, Blur',
//			'Lightbox Thumbnail Gallery, Lightbox Image Sharing Button',
//			'Grid Category Filter Deeplinking',
//			'Grid Category Filter Icons select',
//			'Grid Category Filter Count',
//			'Grid Item Even/Odd Background Color',
//			'Title, Category, Read More Advanced Link Hover Animations',
//			'Display Scheduled Posts',
//			'Open Links in New Tab',
//			'Lazy Loading',
//			'Posts Order',
//			'Trim Title & Excerpt By Letter Count',
//			'Custom Fields Support (Expert)',
//			'Custom Post Types Support (Expert)',
//		] );

		// Styles ====================
		// Section: Grid Item --------
		$this->start_controls_section(
			'section_style_grid_item',
			[
				'label' => esc_html__( 'Grid Item', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		// TEMPORARY - GOGA
		// $this->add_control(
		// 	'apply_styling_to_media_wrap',
		// 	[
		// 		'label' => esc_html__( 'Apply Styles To Media', 'crt-manage' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 	]
		// );

		// $this->add_control(
		// 	'grid_item_outer_bg_color',
		// 	[
		// 		'label'  => esc_html__( 'Background Color', 'crt-manage' ),
		// 		'type' => Controls_Manager::COLOR,
		// 		'default' => '',
		// 		'selectors' => [
		// 			'{{WRAPPER}} .crt-grid-item' => 'background-color: {{VALUE}}',
		// 		],
		// 		'condition' => [
		// 			'apply_styling_to_media_wrap' => 'yes'
		// 		]
		// 	]
		// );

		// $this->add_responsive_control(
		// 	'grid_item_outer_padding',
		// 	[
		// 		'label' => esc_html__( 'Padding', 'crt-manage' ),
		// 		'type' => Controls_Manager::DIMENSIONS,
		// 		'size_units' => [ 'px' ],
		// 		'default' => [
		// 			'top' => 10,
		// 			'right' => 0,
		// 			'bottom' => 0,
		// 			'left' => 0,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .crt-grid-media-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		// 		],
		// 		'condition' => [
		// 			'apply_styling_to_media_wrap' => 'yes'
		// 		],
		// 		'render_type' => 'template'
		// 	]
		// );

		$this->add_control(
			'grid_item_styles_selector',
			[
				'label' => esc_html__( 'Apply Styles To', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'inner' => esc_html__( 'Inner Elements', 'crt-manage' ),
					'wrapper' => esc_html__( 'Wrapper', 'crt-manage' )
				],
				'default' => 'inner',
				'prefix_class' => 'crt-item-styles-',
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'grid_item_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-above-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-item-below-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid[data-settings*="fitRows"] .crt-grid-item' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control_grid_item_even_bg_color();

		$this->add_control(
			'grid_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item-above-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item-below-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item' => 'border-color: {{VALUE}}'
				],
			]
		);

		$this->add_control_grid_item_even_border_color();

		$this->add_control(
			'grid_item_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item-above-content' => 'border-style: {{VALUE}};',
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item-below-content' => 'border-style: {{VALUE}};',
					'{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item' => 'border-style: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grid_item_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item-above-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item-below-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'grid_item_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'grid_item_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-above-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-below-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'grid_item_styles_selector' => 'inner'
				],
				'render_type' => 'template'
			]
		);

		// GOGA - maybe better to set separate padding control
		$this->add_responsive_control(
			'grid_item_wrap_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'grid_item_styles_selector' => 'wrapper'
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'grid_item_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-above-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-below-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'grid_item_shadow',
				'selector' => '{{WRAPPER}} .crt-grid-item',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Grid Media -------
		$this->start_controls_section(
			'section_style_grid_media',
			[
				'label' => esc_html__( 'Grid Media', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'grid_media_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-image-wrap' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'grid_media_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-image-wrap' => 'border-style: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'grid_media_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-image-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'grid_media_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'grid_media_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->add_control_overlay_color();

		$this->add_control_overlay_blend_mode();

		$this->add_control_overlay_border_color();

		$this->add_control_overlay_border_type();

		$this->add_control_overlay_border_width();

		$this->add_responsive_control(
			'overlay_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_title_style' );

		$this->start_controls_tab(
			'tab_grid_title_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'title_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_title_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'title_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'title_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_title_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_title_pointer();

		$this->add_control_title_pointer_height();

		$this->add_control_title_pointer_animation();

		$this->add_control(
			'title_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-item-title .crt-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-item-title .crt-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .crt-grid-item-title a'
			]
		);

		$this->add_control(
			'title_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'title_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6A6A6A',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-content .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_dropcap_color',
			[
				'label'  => esc_html__( 'DropCap Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#3a3a3a',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-content.crt-enable-dropcap p:first-child:first-letter' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-content .inner-block' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'content_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-content .inner-block' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .crt-grid-item-content'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_dropcap_typography',
				'label' => esc_html__( 'Drop Cap Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-grid-item-content.crt-enable-dropcap p:first-child:first-letter'
			]
		);

		$this->add_responsive_control(
			'content_justify',
			[
				'label' => esc_html__( 'Justify Text', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'widescreen_default' => '',
				'laptop_default' => '',
				'tablet_extra_default' => '',
				'tablet_default' => '',
				'mobile_extra_default' => '',
				'mobile_default' => '',
				'selectors_dictionary' => [
					'' => '',
					'yes' => 'text-align: justify;'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-content .inner-block' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'content_width',
			[
				'label' => esc_html__( 'Content Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-content .inner-block' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-content .inner-block' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-content .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-content .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-content .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Excerpt ----------
		$this->start_controls_section(
			'section_style_excerpt',
			[
				'label' => esc_html__( 'Excerpt', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6A6A6A',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-excerpt .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'excerpt_dropcap_color',
			[
				'label'  => esc_html__( 'DropCap Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#3a3a3a',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-excerpt.crt-enable-dropcap p:first-child:first-letter' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'excerpt_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-excerpt .inner-block' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'excerpt_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-excerpt .inner-block' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .crt-grid-item-excerpt'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_dropcap_typography',
				'label' => esc_html__( 'Drop Cap Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-grid-item-excerpt.crt-enable-dropcap p:first-child:first-letter'
			]
		);

		$this->add_responsive_control(
			'excerpt_justify',
			[
				'label' => esc_html__( 'Justify Text', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'widescreen_default' => '',
				'laptop_default' => '',
				'tablet_extra_default' => '',
				'tablet_default' => '',
				'mobile_extra_default' => '',
				'mobile_default' => '',
				'selectors_dictionary' => [
					'' => '',
					'yes' => 'text-align: justify;'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-excerpt .inner-block' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'excerpt_width',
			[
				'label' => esc_html__( 'Excerpt Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-excerpt .inner-block' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'excerpt_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-excerpt .inner-block' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'excerpt_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-excerpt .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'excerpt_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'excerpt_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-excerpt .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'excerpt_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-excerpt .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Date -------------
		$this->start_controls_section(
			'section_style_date',
			[
				'label' => esc_html__( 'Date', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-date .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-date .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'date_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-date .inner-block > span' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'date_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-date .inner-block span[class*="crt-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-date .inner-block [class*="crt-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-item-date .inner-block [class*="crt-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'selector' => '{{WRAPPER}} .crt-grid-item-date, {{WRAPPER}} .crt-grid-item-date span'
			]
		);

		$this->add_control(
			'date_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-date .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-date .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'date_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'date_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-date .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-date .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-date .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-date .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'date_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-date .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'date_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 7,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-date .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Time -------------
		$this->start_controls_section(
			'section_style_time',
			[
				'label' => esc_html__( 'Time', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'time_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-time .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'time_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-time .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'time_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-time .inner-block > span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'time_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-time .inner-block span[class*="crt-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'time_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-time .inner-block [class*="crt-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-item-time .inner-block [class*="crt-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'time_typography',
				'selector' => '{{WRAPPER}} .crt-grid-item-time'
			]
		);

		$this->add_control(
			'time_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-time .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'time_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-time .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'time_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'time_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-time .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-time .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'time_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-time .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-time .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'time_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-time .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'time_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-time .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Author -----------
		$this->start_controls_section(
			'section_style_author',
			[
				'label' => esc_html__( 'Author', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_author_style' );

		$this->start_controls_tab(
			'tab_grid_author_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'author_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'author_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'author_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'author_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .inner-block span[class*="crt-grid-extra-text"]' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_author_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'author_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'author_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'author_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'author_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .inner-block a' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'author_typography',
				'selector' => '{{WRAPPER}} .crt-grid-item-author, {{WRAPPER}} .crt-grid-item-author .inner-block a'
			]
		);

		$this->add_control(
			'author_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'author_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'author_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .inner-block a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'author_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-author .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-author .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'author_avatar_spacing',
			[
				'label' => esc_html__( 'Avatar Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author img' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'author_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'author_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-author .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Comments ---------
		$this->start_controls_section(
			'section_style_comments',
			[
				'label' => esc_html__( 'Comments', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_comments_style' );

		$this->start_controls_tab(
			'tab_grid_comments_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'comments_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'comments_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'comments_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'comments_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .inner-block span[class*="crt-grid-extra-text"]' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_comments_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'comments_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'comments_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'comments_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'comments_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .inner-block a' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'comments_typography',
				'selector' => '{{WRAPPER}} .crt-grid-item-comments'
			]
		);

		$this->add_control(
			'comments_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comments_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'comments_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'comments_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-comments .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'comments_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-comments .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'comments_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'comments_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'comments_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-comments .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Read More --------
		$this->start_controls_section(
			'section_style_read_more',
			[
				'label' => esc_html__( 'Read More', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_read_more_style' );

		$this->start_controls_tab(
			'tab_grid_read_more_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'read_more_bg_color',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .crt-grid-item-read-more .inner-block a'
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-read-more .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'read_more_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-read-more .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'read_more_box_shadow',
				'selector' => '{{WRAPPER}} .crt-grid-item-read-more .inner-block a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_read_more_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'read_more_bg_color_hr',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .crt-grid-item-read-more .inner-block a.crt-button-none:hover, {{WRAPPER}} .crt-grid-item-read-more .inner-block a:before, {{WRAPPER}} .crt-grid-item-read-more .inner-block a:after'
			]
		);

		$this->add_control(
			'read_more_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-read-more .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'read_more_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-read-more .inner-block a:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'read_more_box_shadow_hr',
				'selector' => '{{WRAPPER}} .crt-grid-item-read-more .inner-block :hover a',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'read_more_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control_read_more_animation();

		$this->add_control(
			'read_more_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-read-more .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-item-read-more .inner-block a:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-item-read-more .inner-block a:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control_read_more_animation_height();

		$this->add_control(
			'read_more_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'read_more_typography',
				'selector' => '{{WRAPPER}} .crt-grid-item-read-more a'
			]
		);

		$this->add_control(
			'read_more_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-read-more .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'read_more_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-read-more .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'read_more_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'read_more_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-read-more .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-read-more .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'read_more_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-read-more .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'read_more_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-read-more .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'read_more_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-read-more .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles =======================
		// Section: Likes ---------------
		$this->add_section_style_likes();

		// Styles =========================
		// Section: Sharing ---------------
		$this->add_section_style_sharing();

		// Styles ====================
		// Section: Lightbox ---------
		$this->start_controls_section(
			'section_style_lightbox',
			[
				'label' => esc_html__( 'Lightbox', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_lightbox_style' );

		$this->start_controls_tab(
			'tab_grid_lightbox_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-lightbox .inner-block > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-lightbox .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'lightbox_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-lightbox .inner-block > span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'lightbox_shadow',
				'selector' => '{{WRAPPER}} .crt-grid-item-lightbox i',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_lightbox_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'lightbox_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-lightbox .inner-block > span:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-lightbox .inner-block > span:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'lightbox_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-lightbox .inner-block > span:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'lightbox_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'lightbox_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-lightbox .inner-block > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'lightbox_typography',
				'selector' => '{{WRAPPER}} .crt-grid-item-lightbox'
			]
		);

		$this->add_control(
			'lightbox_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-lightbox .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'lightbox_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-lightbox .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'lightbox_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'lightbox_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-lightbox .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-lightbox .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'lightbox_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-lightbox .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'lightbox_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-lightbox .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'lightbox_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-lightbox .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Filters ----------
		$this->start_controls_section(
			'section_style_filters',
			[
				'label' => esc_html__( 'Filters', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'layout_select!' => 'slider',
					'layout_filters' => 'yes',
				],
			]
		);

		$this->add_control(
			'active_styles_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__('Apply active filter styles from the hover tab.', 'crt-manage'),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info'
			]
		);

		$this->start_controls_tabs( 'tabs_grid_filters_style' );

		$this->start_controls_tab(
			'tab_grid_filters_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'filters_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters li' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-filters li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'filters_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters li > a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-filters li > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'filters_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters li > a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-filters li > span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'filters_box_shadow',
				'selector' => '{{WRAPPER}} .crt-grid-filters li > a, {{WRAPPER}} .crt-grid-filters li > span',
			]
		);

		$this->add_control(
			'filters_wrapper_color',
			[
				'label'  => esc_html__( 'Wrapper Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_filters_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'filters_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters li > a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-filters li > span:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-filters li > .crt-active-filter' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'filters_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters li > a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-filters li > span:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-filters li > .crt-active-filter' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'filters_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters li > a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-filters li > span:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-filters li > .crt-active-filter' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control_filters_pointer_color_hr();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'filters_box_shadow_hr',
				'selector' => '{{WRAPPER}} .crt-grid-filters li > a:hover, {{WRAPPER}} .crt-grid-filters li > span:hover',
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_filters_pointer();

		$this->add_control_filters_pointer_height();

		$this->add_control_filters_pointer_animation();

		$this->add_control(
			'filters_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters li > a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-filters li > span' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-filters .crt-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-filters .crt-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'filters_typography',
				'selector' => '{{WRAPPER}} .crt-grid-filters li'
			]
		);

		$this->add_control(
			'filters_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters li > a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-grid-filters li > span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'filters_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters li > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-filters li > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'filters_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'filters_distance_from_grid',
			[
				'label' => esc_html__( 'Distance From Grid', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'filters_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-filters-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filters_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 5,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filters_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 3,
					'right' => 15,
					'bottom' => 3,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-filters li > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filters_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'filters_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-filters li > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Pagination -------
		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'Pagination', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'layout_select!' => 'slider',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_pagination_style' );

		$this->start_controls_tab(
			'tab_grid_pagination_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-pagination-finish' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow',
				'selector' => '{{WRAPPER}} .crt-grid-pagination a, {{WRAPPER}} .crt-grid-pagination > div > span',
			]
		);

		$this->add_control(
			'pagination_loader_color',
			[
				'label'  => esc_html__( 'Loader Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-double-bounce .crt-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-wave .crt-rect' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-spinner-pulse' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-chasing-dots .crt-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-three-bounce .crt-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-fading-circle .crt-circle:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-ring div' => 'border-color: {{VALUE}}  transparent transparent transparent',
				],
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ]
				]
			]
		);

		$this->add_control(
			'pagination_wrapper_color',
			[
				'label'  => esc_html__( 'Wrapper Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_pagination_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'pagination_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination a:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow_hr',
				'selector' => '{{WRAPPER}} .crt-grid-pagination a:hover, {{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover',
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'pagination_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-pagination svg' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pagination_typography',
				'selector' => '{{WRAPPER}} .crt-grid-pagination, {{WRAPPER}} .crt-grid-pagination a'
			]
		);

		$this->add_responsive_control(
			'pagination_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'pagination_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_distance_from_grid',
			[
				'label' => esc_html__( 'Distance From Grid', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'pagination_gutter',
			[
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					// '{{WRAPPER}} .crt-grid-pagination a' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination a:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};', 
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination > div > a.crt-prev-page' => 'margin-right: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination .crt-prev-post-link i' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination .crt-next-post-link i' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination .crt-first-page i' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination .crt-prev-page i' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination .crt-next-page i' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination .crt-last-page i' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination .crt-prev-post-link svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination .crt-next-post-link svg' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination .crt-first-page svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination .crt-prev-page svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination .crt-next-page svg' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination .crt-last-page svg' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 8,
					'right' => 20,
					'bottom' => 8,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Password Protected
		$this->start_controls_section(
			'section_style_pwd_protected',
			[
				'label' => esc_html__( 'Password Protected', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'pwd_protected_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-protected' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pwd_protected_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-protected' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pwd_protected_input_color',
			[
				'label'  => esc_html__( 'Input Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-protected input' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pwd_protected_typography',
				'selector' => '{{WRAPPER}} .crt-grid-item-protected p'
			]
		);

		$this->end_controls_section();

		
		// Styles ====================
		// Section: Separator Style 1 
		$this->start_controls_section(
			'section_style_separator1',
			[
				'label' => esc_html__( 'Separator Style 1', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'separator1_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-1 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator1_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-1:not(.crt-grid-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-sep-style-1.crt-grid-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator1_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-1 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator1_border_type',
			[
				'label' => esc_html__( 'Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-1 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator1_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 15,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-1 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator1_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-1 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Separator Style 2 
		$this->start_controls_section(
			'section_style_separator2',
			[
				'label' => esc_html__( 'Separator Style 2', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'separator2_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-2 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator2_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-2:not(.crt-grid-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-sep-style-2.crt-grid-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator2_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-2 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'separator2_border_type',
			[
				'label' => esc_html__( 'Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-2 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator2_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 15,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'separator2_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-2 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Taxonomy Style 1 ------
		$this->start_controls_section(
			'section_style_tax1',
			[
				'label' => esc_html__( 'Taxonomy Style 1', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_tax1_style' );

		$this->start_controls_tab(
			'tab_grid_tax1_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'tax1_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax1_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block span[class*="crt-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block [class*="crt-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block [class*="crt-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->add_control_tax1_custom_colors($tax_meta_keys[1]);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_tax1_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'tax1_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-tax-style-1 .crt-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-tax-style-1 .crt-pointer-item:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax1_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_tax1_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_tax1_pointer();

		$this->add_control_tax1_pointer_height();

		$this->add_control_tax1_pointer_animation();

		$this->add_control(
			'tax1_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-tax-style-1 .crt-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-tax-style-1 .crt-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tax1_typography',
				'selector' => '{{WRAPPER}} .crt-grid-tax-style-1'
			]
		);

		$this->add_control(
			'tax1_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax1_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'tax1_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'tax1_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-tax-style-1 .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax1_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-tax-style-1 .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tax1_gutter',
			[
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tax1_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'tax1_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'tax1_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-1 .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Taxonomy Style 2 -
		$this->start_controls_section(
			'section_style_tax2',
			[
				'label' => esc_html__( 'Taxonomy Style 2', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_tax2_style' );

		$this->start_controls_tab(
			'tab_grid_tax2_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'tax2_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax2_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax2_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax2_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block span[class*="crt-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax2_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block [class*="crt-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block [class*="crt-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_tax2_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'tax2_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-tax-style-2 .crt-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-tax-style-2 .crt-pointer-item:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax2_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax2_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_tax2_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_tax2_pointer();

		$this->add_control_tax2_pointer_height();

		$this->add_control_tax2_pointer_animation();

		$this->add_control(
			'tax2_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-tax-style-2 .crt-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-tax-style-2 .crt-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tax2_typography',
				'selector' => '{{WRAPPER}} .crt-grid-tax-style-2'
			]
		);

		$this->add_control(
			'tax2_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax2_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'tax2_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'tax2_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-tax-style-2 .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax2_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-tax-style-2 .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tax2_gutter',
			[
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tax2_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'tax2_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'tax2_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-tax-style-2 .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles =======================
		// Section: Custom Field Style 1
		$this->add_section_style_custom_field1();

		// Styles =======================
		// Section: Custom Field Style 2
		$this->add_section_style_custom_field2();

		// Styles =======================
		// Section: Custom Field Style 3
		$this->add_section_style_custom_field3();

		// Styles =======================
		// Section: Custom Field Style 4
		$this->add_section_style_custom_field4();

		// Styles ====================
		// Section: Navigation -------
		$this->start_controls_section(
			'crt__section_style_grid_slider_nav',
			[
				'label' => esc_html__( 'Slider Navigation', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_slider_nav_style' );

		$this->start_controls_tab(
			'tab_grid_slider_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'grid_slider_nav_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-grid-slider-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255,255,255,0.8)',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_slider_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'grid_slider_nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-arrow:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-grid-slider-arrow:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'grid_slider_nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-arrow' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-slider-arrow svg' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'grid_slider_nav_font_size',
			[
				'label' => esc_html__( 'Font Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'grid_slider_nav_size',
			[
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'grid_slider_nav_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-arrow' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'grid_slider_nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control_stack_grid_slider_nav_position();

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Pagination -------
		$this->start_controls_section(
			'crt__section_style_grid_slider_dots',
			[
				'label' => esc_html__( 'Slider Pagination', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_slider_dots' );

		$this->start_controls_tab(
			'tab_grid_slider_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'grid_slider_dots_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.35)',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-dot' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_dots_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_slider_dots_active',
			[
				'label' => esc_html__( 'Active', 'crt-manage' ),
			]
		);

		$this->add_control(
			'grid_slider_dots_active_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-dots .slick-active .crt-grid-slider-dot' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'grid_slider_dots_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-dots .slick-active .crt-grid-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'grid_slider_dots_width',
			[
				'label' => esc_html__( 'Box Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-dot' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'grid_slider_dots_height',
			[
				'label' => esc_html__( 'Box Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-dot' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'grid_slider_dots_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-dot' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grid_slider_dots_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-dot' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'grid_slider_dots_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'grid_slider_dots_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
					'unit' => '%',
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'grid_slider_dots_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-grid-slider-dots-horizontal .crt-grid-slider-dot' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-grid-slider-dots-vertical .crt-grid-slider-dot' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control_grid_slider_dots_hr();
		
		$this->add_responsive_control(
			'grid_slider_dots_vr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position', 'crt-manage' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 96,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-slider-dots' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
		
	}

	// Render Post Taxonomies
	public function render_post_taxonomies( $settings, $class, $post_id ) {
		$terms = wp_get_post_terms( $post_id, $settings['element_select'] );
		$count = 0;

		$tax1_pointer = $this->get_settings()['tax1_pointer'];
		$tax1_pointer_animation = $this->get_settings()['tax1_pointer_animation'];
		$tax2_pointer = $this->get_settings()['tax2_pointer'];
		$tax2_pointer_animation = $this->get_settings()['tax2_pointer_animation'];
		$pointer_item_class = (isset($this->get_settings()['tax1_pointer']) && 'none' !== $this->get_settings()['tax1_pointer']) || (isset($this->get_settings()['tax2_pointer']) && 'none' !== $this->get_settings()['tax2_pointer']) ? 'crt-pointer-item' : '';

		// Pointer Class
		if ( 'crt-grid-tax-style-1' === $settings['element_tax_style'] ) {
			$class .= ' crt-pointer-'. $tax1_pointer;
			$class .= ' crt-pointer-line-fx crt-pointer-fx-'. $tax1_pointer_animation;
		} else {
			$class .= ' crt-pointer-'. $tax2_pointer;
			$class .= ' crt-pointer-line-fx crt-pointer-fx-'. $tax2_pointer_animation;
		}

		echo '<div class="'. esc_attr($class .' '. $settings['element_tax_style']) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();
		
					echo '<span class="crt-grid-extra-icon-left">';
						echo $extra_icon;
					echo '</span>';
				}

				// Taxonomies
				foreach ( $terms as $term ) {

					// Custom Colors
					$enable_custom_colors = $this->get_settings()['tax1_custom_color_switcher'];
					
					if ( 'yes' === $enable_custom_colors ) {
						$custom_tax_styles = '';
						$cfc_text = get_term_meta($term->term_id, $this->get_settings()['tax1_custom_color_field_text'], true);
						$cfc_bg = get_term_meta($term->term_id, $this->get_settings()['tax1_custom_color_field_bg'], true);
						$color_styles = 'color:'. $cfc_text .'; background-color:'. $cfc_bg .'; border-color:'. $cfc_bg .';';
						$css_selector = '.elementor-element'. $this->get_unique_selector() .' .crt-grid-tax-style-1 .inner-block a.crt-tax-id-'. esc_attr($term->term_id);
						$custom_tax_styles .= $css_selector .'{'. $color_styles .'}';
						echo '<style>'. esc_html($custom_tax_styles) .'</style>'; // TODO: take out of loop if possible
					}

					echo '<a class="'. $pointer_item_class .' crt-tax-id-'. esc_attr($term->term_id) .'" href="'. esc_url(get_term_link( $term->term_id )) .'">'. esc_html( $term->name );
						if ( ++$count !== count( $terms ) ) {
							echo '<span class="tax-sep">'. esc_html($settings['element_tax_sep']) .'</span>';
						}
					echo '</a>';
				}

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="crt-grid-extra-icon-right">';
						echo $extra_icon;
					echo '</span>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Advanced Filters
	public function render_grid_filters( $settings ) {
		$taxonomy = $settings['filters_select'];

		// Return if Disabled
		if ( '' === $taxonomy || ! isset( $settings[ 'query_taxonomy_'. $taxonomy ] ) ) {
			return;
		}

		// Get Custom Filters
		$custom_filters = $settings[ 'query_taxonomy_'. $taxonomy ];

		// Icon
		$left_icon = 'left' === $settings['filters_icon_align'] ? '<i class="'. esc_attr($settings['filters_icon']['value']) .' crt-grid-filters-icon-left"></i>' : '';
		$right_icon = 'right' === $settings['filters_icon_align'] ? '<i class="'. esc_attr($settings['filters_icon']['value']) .' crt-grid-filters-icon-right"></i>' : '';
		
		// Separator
		$left_separator = 'left' === $settings['filters_separator_align'] ? '<em class="crt-grid-filters-sep">'. esc_html($settings['filters_separator']) .'</em>' : '';
		$right_separator = 'right' === $settings['filters_separator_align'] ? '<em class="crt-grid-filters-sep">'. esc_html($settings['filters_separator']) .'</em>' : '';

		// Count
		$post_count = 'yes' === $settings['filters_count'] ? '<sup data-brackets="'. esc_attr($settings['filters_count_brackets']) .'"></sup>' : '';

		// Pointer Class
		$pointer_class  = ' crt-pointer-'. $settings['filters_pointer'];
		$pointer_class .= ' crt-pointer-line-fx crt-pointer-fx-'. $settings['filters_pointer_animation'];
		$pointer_item_class = (isset($settings['filters_pointer']) && 'none' !== $settings['filters_pointer']) ? 'class="crt-pointer-item"' : '';
		$pointer_item_class_name = (isset($settings['filters_pointer']) && 'none' !== $settings['filters_pointer']) ? 'crt-pointer-item' : '';

		// Filters List
		echo '<ul class="crt-grid-filters elementor-clearfix crt-grid-filters-sep-'. esc_attr($settings['filters_separator_align']) .'">';

		// All Filter
		if ( 'yes' === $settings['filters_all'] && 'yes' !== $settings['filters_linkable'] ) {
			echo '<li class="'. esc_attr($pointer_class) .'">';
			echo '<span  data-filter="*" class="crt-grid-filters-item crt-active-filter '. $pointer_item_class_name .'">'. $left_icon . esc_html($settings['filters_all_text']) . $right_icon . $post_count .'</span>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</li>';
		}
		
		$q = get_queried_object();
		// category title : custom post type archive title
		$category_name = is_category() ? strtolower($q->name) : 'no-category';

		// Custom Filters
		if ( $settings['query_selection'] === 'dynamic' && ! empty( $custom_filters ) ) {
			$parent_filters = [];
			
			foreach ( $custom_filters as $key => $term_id ) {
				$filter = get_term_by( 'id', $term_id, $taxonomy );
				$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $filter->slug : $taxonomy .'-'. $filter->slug;
				$tax_data_attr = 'post_tag' === $taxonomy ? 'tag' : $taxonomy;
				$term_data_attr = $filter->slug;

				// GOGA - tested but needs advanced testing
				if (strpos($data_attr, $category_name) !== false) {
					$active_class = 'crt-active-filter';
				} else {
					$active_class = '';
				}

				// Parent Filters
				if ( 0 === $filter->parent ) {
					$children = get_term_children( $filter->term_id, $taxonomy );
					$data_role = ! empty($children) ? ' data-role="parent"' : '';

					echo '<li'. $data_role .' class="'. esc_attr($pointer_class) .'">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						if ( 'yes' !== $settings['filters_linkable'] ) {
							echo ''. $left_separator .'<span '. $pointer_item_class .'  data-ajax-filter='. json_encode([$tax_data_attr, $term_data_attr]) .'  data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</span>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							echo ''. $left_separator .'<a class="'. $active_class . ' ' . $pointer_item_class_name .'" href="'. esc_url(get_term_link( $filter->term_id, $taxonomy )) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</a>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					echo '</li>';

				// Get Sub Filters
				} else {
					array_push( $parent_filters, $filter->parent );
				}
			}

		// All Filters
		} else {
			$exclude_ids = [];

			if ( 'yes' === $settings['filters_hide_uncategorized'] ) {
				$uncategorized = get_term_by('slug', 'uncategorized', $taxonomy);

				if ($uncategorized && !is_wp_error($uncategorized)) {
					$exclude_ids[] = $uncategorized->term_id;
				}
			}

			$all_filters = get_terms([
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'exclude'    => $exclude_ids,
			]);

			$parent_filters = [];

			foreach ( $all_filters as $key => $filter ) {
				$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $filter->slug : $taxonomy .'-'. $filter->slug;
				$tax_data_attr = 'post_tag' === $taxonomy ? 'tag' : $taxonomy;
				$term_data_attr = $filter->slug;

				// GOGA - tested but needs advanced testing
				if (strpos($data_attr, $category_name) !== false) {
					$active_class = 'crt-active-filter';
				} else {
					$active_class = '';
				}

				// Parent Filters
				if ( 0 === $filter->parent ) {
					$children = get_term_children( $filter->term_id, $taxonomy );
					$data_role = ! empty($children) ? ' data-role="parent"' : '';
					$hidden_class = CRT_Grid_Helpers::get_hidden_filter_class($filter->slug, $settings);

					echo '<li'. $data_role .' class="'. esc_attr($pointer_class) . esc_attr($hidden_class) .'">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						if ( 'yes' !== $settings['filters_linkable'] ) {
							echo ''. $left_separator .'<span '. $pointer_item_class .'  data-ajax-filter='. json_encode([$tax_data_attr, $term_data_attr]) .'  data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</span>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							echo ''. $left_separator .'<a class="'. $active_class . ' ' . $pointer_item_class_name .'" href="'. esc_url(get_term_link( $filter->term_id, $taxonomy )) .'"  data-ajax-filter='. json_encode([$tax_data_attr, $term_data_attr]) .'  data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</a>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					echo '</li>';

				// Get Sub Filters
				} else {
					array_push( $parent_filters, $filter->parent );
				}
			}
		}

		// Sub Filters
		if ( 'yes' !== $settings['filters_linkable'] ) {
			foreach ( array_unique( $parent_filters ) as $key => $parent_filter ) {
				$parent = get_term_by( 'id', $parent_filter, $taxonomy );
				$children = get_term_children( $parent_filter, $taxonomy );
				$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $parent->slug : $taxonomy .'-'. $parent->slug;
				$tax_data_attr = 'post_tag' === $taxonomy ? 'tag' : $taxonomy;
				$term_data_attr = $parent->slug;

				echo '<ul data-parent=".'. esc_attr(urldecode( $data_attr )) .'" class="crt-sub-filters">';

				echo '<li data-role="back" class="'. esc_attr($pointer_class) .'">';
					echo '<span class="crt-back-filter"  data-ajax-filter='. json_encode([$tax_data_attr, $term_data_attr]) .'  data-filter=".'. esc_attr(urldecode( $data_attr )) .'">';
						echo '<i class="fas fa-long-arrow-alt-left"></i>&nbsp;&nbsp;'. esc_html__( 'Back', 'crt-manage' );
					echo '</span>';
				echo '</li>';

				foreach ( $children as $child ) {
					$sub_filter = get_term_by( 'id', $child, $taxonomy );
					$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $sub_filter->slug : $taxonomy .'-'. $sub_filter->slug;

					echo '<li data-role="sub" class="'. esc_attr($pointer_class) .'">';
						echo ''. $left_separator .'<span '. $pointer_item_class .'  data-ajax-filter='. json_encode([$tax_data_attr, $sub_filter->slug]) .'  data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($sub_filter->name) . $right_icon . $post_count .'</span>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '</li>';
				}

				echo '</ul>';
			}
		}

		echo '</ul>';
	}

	// Grid Settings
	public function add_grid_settings( $settings ) {

		if ( 'fitRows' == $settings['layout_select'] ) {
			$stick_last_element_to_bottom = $settings['stick_last_element_to_bottom'];
		} else {
			$stick_last_element_to_bottom = 'no';
		}

		global $wp_query;
		$this->current_post_type = $wp_query->query_vars['post_type'] ?? $settings['query_source'];

		$gutter_hr_widescreen = isset($settings['layout_gutter_hr_widescreen']['size']) ? $settings['layout_gutter_hr_widescreen']['size'] : $settings['layout_gutter_hr']['size'];
		$gutter_hr_desktop = $settings['layout_gutter_hr']['size'];
		$gutter_hr_laptop = isset($settings['layout_gutter_hr_laptop']['size']) ? $settings['layout_gutter_hr_laptop']['size'] : $gutter_hr_desktop;
		$gutter_hr_tablet_extra = isset($settings['layout_gutter_hr_tablet_extra']['size']) ? $settings['layout_gutter_hr_tablet_extra']['size'] : $gutter_hr_laptop;
		$gutter_hr_tablet = isset($settings['layout_gutter_hr_tablet']['size']) ? $settings['layout_gutter_hr_tablet']['size'] : $gutter_hr_tablet_extra;
		$gutter_hr_mobile_extra = isset($settings['layout_gutter_hr_mobile_extra']['size']) ? $settings['layout_gutter_hr_mobile_extra']['size'] : $gutter_hr_tablet;
		$gutter_hr_mobile = isset($settings['layout_gutter_hr_mobile']['size']) ? $settings['layout_gutter_hr_mobile']['size'] : $gutter_hr_mobile_extra;

		$gutter_vr_widescreen = isset($settings['layout_gutter_vr_widescreen']['size']) ? $settings['layout_gutter_vr_widescreen']['size'] : $settings['layout_gutter_vr']['size'];
		$gutter_vr_desktop = $settings['layout_gutter_vr']['size'];
		$gutter_vr_laptop = isset($settings['layout_gutter_vr_laptop']['size']) ? $settings['layout_gutter_vr_laptop']['size'] : $gutter_vr_desktop;
		$gutter_vr_tablet_extra = isset($settings['layout_gutter_vr_tablet_extra']['size']) ? $settings['layout_gutter_vr_tablet_extra']['size'] : $gutter_vr_laptop;
		$gutter_vr_tablet = isset($settings['layout_gutter_vr_tablet']['size']) ? $settings['layout_gutter_vr_tablet']['size'] : $gutter_vr_tablet_extra;
		$gutter_vr_mobile_extra = isset($settings['layout_gutter_vr_mobile_extra']['size']) ? $settings['layout_gutter_vr_mobile_extra']['size'] : $gutter_vr_tablet;
		$gutter_vr_mobile = isset($settings['layout_gutter_vr_mobile']['size']) ? $settings['layout_gutter_vr_mobile']['size'] : $gutter_vr_mobile_extra;

		$layout_settings = [
			'layout' => $settings['layout_select'],
			'stick_last_element_to_bottom' => $stick_last_element_to_bottom,
			'columns_desktop' => $settings['layout_columns'],
			'gutter_hr' => $gutter_hr_desktop,
			'gutter_hr_mobile' => $gutter_hr_mobile,
			'gutter_hr_mobile_extra' => $gutter_hr_mobile_extra,
			'gutter_hr_tablet' => $gutter_hr_tablet,
			'gutter_hr_tablet_extra' => $gutter_hr_tablet_extra,
			'gutter_hr_laptop' => $gutter_hr_laptop,
			'gutter_hr_widescreen' => $gutter_hr_widescreen,
			'gutter_vr' => $gutter_vr_desktop,
			'gutter_vr_mobile' => $gutter_vr_mobile,
			'gutter_vr_mobile_extra' => $gutter_vr_mobile_extra,
			'gutter_vr_tablet' => $gutter_vr_tablet,
			'gutter_vr_tablet_extra' => $gutter_vr_tablet_extra,
			'gutter_vr_laptop' => $gutter_vr_laptop,
			'gutter_vr_widescreen' => $gutter_vr_widescreen,
			'animation' => $settings['layout_animation'],
			'animation_duration' => $settings['layout_animation_duration'],
			'animation_delay' => $settings['layout_animation_delay'],
			'deeplinking' => $settings['filters_deeplinking'],
			'filters_linkable' => $settings['filters_linkable'],
			'filters_default_filter' => $settings['filters_default_filter'],
			'filters_count' => $settings['filters_count'],
			'filters_hide_empty' => $settings['filters_hide_empty'],
			'filters_animation' => $settings['filters_animation'],
			'filters_animation_duration' => $settings['filters_animation_duration'],
			'filters_animation_delay' => $settings['filters_animation_delay'],
			'pagination_type' => $settings['pagination_type'],
			'pagination_max_pages' => CRT_Grid_Helpers::get_max_num_pages( $settings ),
		];

		if ( 'list' === $settings['layout_select'] ) {
			$layout_settings['media_align'] = $settings['layout_list_align'];
			$layout_settings['media_width'] = $settings['layout_list_media_width']['size'];
			$layout_settings['media_distance'] = $settings['layout_list_media_distance']['size'];
		}

		$layout_settings['lightbox'] = [
			'selector' => '.crt-grid-image-wrap',
			'iframeMaxWidth' => '60%',
			'hash' => false,
			'autoplay' => $settings['lightbox_popup_autoplay'],
			'pause' => $settings['lightbox_popup_pause'] * 1000,
			'progressBar' => $settings['lightbox_popup_progressbar'],
			'counter' => $settings['lightbox_popup_counter'],
			'controls' => $settings['lightbox_popup_arrows'],
			'getCaptionFromTitleOrAlt' => $settings['lightbox_popup_captions'],
			'thumbnail' => $settings['lightbox_popup_thumbnails'],
			'showThumbByDefault' => $settings['lightbox_popup_thumbnails_default'],
			'share' => $settings['lightbox_popup_sharing'],
			'zoom' => $settings['lightbox_popup_zoom'],
			'fullScreen' => $settings['lightbox_popup_fullscreen'],
			'download' => $settings['lightbox_popup_download'],
		];

		if ( 'yes' === $settings['filters_experiment'] || 'yes' === $settings['advanced_filters'] ) {
			$layout_settings['grid_settings'] = [
				// General Settings
				'filters_experiment' => isset($settings['filters_experiment']) ? $settings['filters_experiment'] : null,
				'advanced_filters' => isset($settings['advanced_filters']) ? $settings['advanced_filters'] : null,
				'layout_select' => isset($settings['layout_select']) ? $settings['layout_select'] : null,
				'layout_animation' => isset($settings['layout_animation']) ? $settings['layout_animation'] : null,
				'layout_animation_duration' => isset($settings['layout_animation_duration']) ? $settings['layout_animation_duration'] : null,
				'layout_animation_delay' => isset($settings['layout_animation_delay']) ? $settings['layout_animation_delay'] : null,
				'pagination_type' => isset($settings['pagination_type']) ? $settings['pagination_type'] : null,
				'pagination_max_pages' => CRT_Grid_Helpers::get_max_num_pages($settings),

				// Filter Settings
				'filters_deeplinking' => isset($settings['filters_deeplinking']) ? $settings['filters_deeplinking'] : null,
				'filters_linkable' => isset($settings['filters_linkable']) ? $settings['filters_linkable'] : null,
				'filters_default_filter' => isset($settings['filters_default_filter']) ? $settings['filters_default_filter'] : null,
				'filters_count' => isset($settings['filters_count']) ? $settings['filters_count'] : null,
				'filters_hide_empty' => isset($settings['filters_hide_empty']) ? $settings['filters_hide_empty'] : null,
				'filters_animation' => isset($settings['filters_animation']) ? $settings['filters_animation'] : null,
				'filters_animation_duration' => isset($settings['filters_animation_duration']) ? $settings['filters_animation_duration'] : null,
				'filters_animation_delay' => isset($settings['filters_animation_delay']) ? $settings['filters_animation_delay'] : null,

				// Lightbox Settings
				'lightbox_popup_autoplay' => isset($settings['lightbox_popup_autoplay']) ? $settings['lightbox_popup_autoplay'] : null,
				'lightbox_popup_pause' => isset($settings['lightbox_popup_pause']) ? $settings['lightbox_popup_pause'] : null,
				'lightbox_popup_progressbar' => isset($settings['lightbox_popup_progressbar']) ? $settings['lightbox_popup_progressbar'] : null,
				'lightbox_popup_counter' => isset($settings['lightbox_popup_counter']) ? $settings['lightbox_popup_counter'] : null,
				'lightbox_popup_arrows' => isset($settings['lightbox_popup_arrows']) ? $settings['lightbox_popup_arrows'] : null,
				'lightbox_popup_captions' => isset($settings['lightbox_popup_captions']) ? $settings['lightbox_popup_captions'] : null,
				'lightbox_popup_thumbnails' => isset($settings['lightbox_popup_thumbnails']) ? $settings['lightbox_popup_thumbnails'] : null,
				'lightbox_popup_thumbnails_default' => isset($settings['lightbox_popup_thumbnails_default']) ? $settings['lightbox_popup_thumbnails_default'] : null,
				'lightbox_popup_sharing' => isset($settings['lightbox_popup_sharing']) ? $settings['lightbox_popup_sharing'] : null,
				'lightbox_popup_zoom' => isset($settings['lightbox_popup_zoom']) ? $settings['lightbox_popup_zoom'] : null,
				'lightbox_popup_fullscreen' => isset($settings['lightbox_popup_fullscreen']) ? $settings['lightbox_popup_fullscreen'] : null,
				'lightbox_popup_download' => isset($settings['lightbox_popup_download']) ? $settings['lightbox_popup_download'] : null,

				// Query Settings
				'query_source' => isset($settings['query_source']) ? $settings['query_source'] : null,
				'current_query_source' => $this->current_post_type,
				'query_author' => isset($settings['query_author']) ? $settings['query_author'] : null,
				'query_posts_per_page' => isset($settings['query_posts_per_page']) ? $settings['query_posts_per_page'] : null,
				'query_offset' => isset($settings['query_offset']) ? $settings['query_offset'] : null,
				'query_randomize' => isset($settings['query_randomize']) ? $settings['query_randomize'] : null,
				'order_posts' => isset($settings['order_posts']) ? $settings['order_posts'] : null,
				'order_posts_by_acf' => isset($settings['order_posts_by_acf']) ? $settings['order_posts_by_acf'] : null,
				'order_direction' => isset($settings['order_direction']) ? $settings['order_direction'] : null,
				'query_exclude_no_images' => isset($settings['query_exclude_no_images']) ? $settings['query_exclude_no_images'] : null,
				'query_selection' => isset($settings['query_selection']) ? $settings['query_selection'] : null,
				'query_tax_selection' => isset($settings['query_tax_selection']) ? $settings['query_tax_selection'] : null,
				'query_manual' => isset($settings['query_manual_' . $settings['query_source']]) ? $settings['query_manual_' . $settings['query_source']] : null,
				'display_scheduled_posts' => isset($settings['display_scheduled_posts']) ? $settings['display_scheduled_posts'] : null,
				'query_not_found_text' => isset($settings['query_not_found_text']) ? $settings['query_not_found_text'] : null,
				'layout_image_crop' => [
					'layout_image_crop_size' => isset($settings['layout_image_crop_size']) ? $settings['layout_image_crop_size'] : null,
					'layout_image_crop_custom_dimension' => isset($settings['layout_image_crop_custom_dimension']) ? $settings['layout_image_crop_custom_dimension'] : null,
				],
				'image_effects' => isset($settings['image_effects']) ? $settings['image_effects'] : null,
				'image_effects_animation_timing' => isset($settings['image_effects_animation_timing']) ? $settings['image_effects_animation_timing'] : null,
				'image_effects_size' => isset($settings['image_effects_size']) ? $settings['image_effects_size'] : null,
				'image_effects_direction' => isset($settings['image_effects_direction']) ? $settings['image_effects_direction'] : null,
				'open_links_in_new_tab' => isset($settings['open_links_in_new_tab']) ? $settings['open_links_in_new_tab'] : null,
				'overlay_post_link' => isset($settings['overlay_post_link']) ? $settings['overlay_post_link'] : null,
				'secondary_img_on_hover' => isset($settings['secondary_img_on_hover']) ? $settings['secondary_img_on_hover'] : null,
				'grid_lazy_loading' => isset($settings['grid_lazy_loading']) ? $settings['grid_lazy_loading'] : null,
				'overlay_animation' => isset($settings['overlay_animation']) ? $settings['overlay_animation'] : null,
				'overlay_animation_size' => isset($settings['overlay_animation_size']) ? $settings['overlay_animation_size'] : null,
				'overlay_animation_timing' => isset($settings['overlay_animation_timing']) ? $settings['overlay_animation_timing'] : null,
				'overlay_animation_tr' => isset($settings['overlay_animation_tr']) ? $settings['overlay_animation_tr'] : null,
				'overlay_image' => isset($settings['overlay_image']) ? $settings['overlay_image'] : null,
				'title_pointer' => isset($settings['title_pointer']) ? $settings['title_pointer'] : null,
				'title_pointer_animation' => isset($settings['title_pointer_animation']) ? $settings['title_pointer_animation'] : null,
				'read_more_animation' => isset($settings['read_more_animation']) ? $settings['read_more_animation'] : null,
				'tax1_pointer' => isset($settings['tax1_pointer']) ? $settings['tax1_pointer'] : null,
				'tax1_pointer_animation' => isset($settings['tax1_pointer_animation']) ? $settings['tax1_pointer_animation'] : null,
				'tax2_pointer' => isset($settings['tax2_pointer']) ? $settings['tax2_pointer'] : null,
				'tax2_pointer_animation' => isset($settings['tax2_pointer_animation']) ? $settings['tax2_pointer_animation'] : null,
				'tax1_custom_color_switcher' => isset($settings['tax1_custom_color_switcher']) ? $settings['tax1_custom_color_switcher'] : null,
				'tax1_custom_color_field_text' => isset($settings['tax1_custom_color_field_text']) ? $settings['tax1_custom_color_field_text'] : null,
				'tax1_custom_color_field_bg' => isset($settings['tax1_custom_color_field_bg']) ? $settings['tax1_custom_color_field_bg'] : null,
				'layout_pagination' => isset($settings['layout_pagination']) ? $settings['layout_pagination'] : null,
				'check_ajax_filter' => 'yes',
				
				// Taxonomies
				// Dynamically add taxonomy settings
				] + array_reduce(array_keys($this->get_available_taxonomies() ?? []), function($carry, $slug) use ($settings) {
					$carry['query_taxonomy_'. $slug] = isset($settings['query_taxonomy_'. $slug]) ? $settings['query_taxonomy_'. $slug] : null;
					return $carry;
				}, []) + array_reduce(array_keys($this->post_types ?? []), function($carry, $slug) use ($settings) {
					$carry['query_exclude_'. $slug] = isset($settings['query_exclude_'. $slug]) ? $settings['query_exclude_'. $slug] : null;
					$carry['query_manual_'. $slug] = isset($settings['query_manual_'. $slug]) ? $settings['query_manual_'. $slug] : null;
					return $carry;
				}, []) + [

				// Repeater Controls
				'grid_elements' => isset($settings['grid_elements']) ? $settings['grid_elements'] : null, // Assuming 'items' is a repeater control

				// Add any necessary controls from styles tab here if needed
			];
		}

		$this->add_render_attribute( 'grid-settings', [
			'data-settings' => wp_json_encode( $layout_settings ),
			'data-advanced-filters' => ( 'yes' === $settings['advanced_filters'] ) ? 'yes' : 'no'
		] );
	}

	public function add_slider_settings( $settings ) {
		$slider_is_rtl = is_rtl();
		$slider_direction = $slider_is_rtl ? 'rtl' : 'ltr';

//		if ( 'pro-3' == $settings['layout_slider_amount'] || 'pro-4' == $settings['layout_slider_amount'] || 'pro-5' == $settings['layout_slider_amount'] || 'pro-6' == $settings['layout_slider_amount'] ) {
//			$settings['layout_slider_amount'] = 1;
//		}

		$slider_options = [
			'rtl' => $slider_is_rtl,
			'infinite' => ( $settings['layout_slider_loop'] === 'yes' ),
			'speed' => absint( $settings['layout_slider_effect_duration'] * 1000 ),
			'arrows' => true,
			'dots' => true,
			'autoplay' => ( $settings['layout_slider_autoplay'] === 'yes' ),
			'autoplaySpeed' => absint( $settings['layout_slider_autoplay_duration'] * 1000 ),
			'pauseOnHover' => $settings['layout_slider_pause_on_hover'],
			'prevArrow' => '#crt-grid-slider-prev-'. $this->get_id(),
			'nextArrow' => '#crt-grid-slider-next-'. $this->get_id(),
			'sliderSlidesToScroll' => $settings['layout_slides_to_scroll'] ? absint( $settings['layout_slides_to_scroll'] ) : 1,
		];

		// Lightbox Settings
		$slider_options['lightbox'] = [
			'selector' => 'article:not(.slick-cloned) .crt-grid-image-wrap',
			'iframeMaxWidth' => '60%',
			'hash' => false,
			'autoplay' => $settings['lightbox_popup_autoplay'],
			'pause' => $settings['lightbox_popup_pause'] * 1000,
			'progressBar' => $settings['lightbox_popup_progressbar'],
			'counter' => $settings['lightbox_popup_counter'],
			'controls' => $settings['lightbox_popup_arrows'],
			'getCaptionFromTitleOrAlt' => $settings['lightbox_popup_captions'],
			'thumbnail' => $settings['lightbox_popup_thumbnails'],
			'showThumbByDefault' => $settings['lightbox_popup_thumbnails_default'],
			'share' => $settings['lightbox_popup_sharing'],
			'zoom' => $settings['lightbox_popup_zoom'],
			'fullScreen' => $settings['lightbox_popup_fullscreen'],
			'download' => $settings['lightbox_popup_download'],
		];

		if ( $settings['layout_slider_amount'] === 1 && $settings['layout_slider_effect'] === 'fade' ) {
			$slider_options['fade'] = true;
		}

		$this->add_render_attribute( 'slider-settings', [
			'dir' => esc_attr( $slider_direction ),
			'data-slick' => wp_json_encode( $slider_options ),
		] );
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
//		$settings['query_randomize'] = '';
//		$settings['display_scheduled_posts'] = '';

		// Get Posts
		$posts = new \WP_Query( CRT_Grid_Helpers::get_main_query_args($settings, []) );

		// Loop: Start
		if ( $posts->have_posts() ) :

		// Grid Settings
		if ( 'slider' !== $settings['layout_select'] ) {
			// Filters
			$this->render_grid_filters( $settings );

			$this->add_grid_settings( $settings );
			$render_attribute = $this->get_render_attribute_string( 'grid-settings' );

		// Slider Settings
		} else {
			$this->add_slider_settings( $settings );
			$render_attribute = $this->get_render_attribute_string( 'slider-settings' );
		}

		echo '<section class="crt-grid elementor-clearfix" '. $render_attribute .'>';

		while ( $posts->have_posts() ) : $posts->the_post();

			// Post Class
			$post_class = implode( ' ', get_post_class( 'crt-grid-item elementor-clearfix', get_the_ID() ) );

			// Grid Item
			echo '<article class="'. esc_attr( $post_class ) .'">';

			// Password Protected Form
			CRT_Grid_Helpers::render_password_protected_input( $settings );

			// Inner Wrapper
			echo '<div class="crt-grid-item-inner">';

			// Content: Above Media
			CRT_Grid_Helpers::get_elements_by_location( 'above', $settings, get_the_ID() );

			// Media
			if ( has_post_thumbnail() ) {
				echo '<div class="crt-grid-media-wrap'. esc_attr(CRT_Grid_Helpers::get_image_effect_class( $settings )) .' " data-overlay-link="'. esc_attr( $settings['overlay_post_link'] ) .'">';
					// Post Thumbnail
					CRT_Grid_Helpers::render_post_thumbnail( $settings, get_the_ID() );

					// Media Hover
					echo '<div class="crt-grid-media-hover crt-animation-wrap">';
						// Media Overlay
						CRT_Grid_Helpers::render_media_overlay( $settings );

						// Content: Over Media
						CRT_Grid_Helpers::get_elements_by_location( 'over', $settings, get_the_ID() );

					echo '</div>';
				echo '</div>';
			}

			// Content: Below Media
			CRT_Grid_Helpers::get_elements_by_location( 'below', $settings, get_the_ID() );

			echo '</div>'; // End .crt-grid-item-inner

			echo '</article>'; // End .crt-grid-item

		endwhile;

		// Grid Wrap
		echo '</section>';

		// reset
		wp_reset_postdata();

		if ( 'slider' === $settings['layout_select'] ) {
			// Slider Navigation
			echo '<div class="crt-grid-slider-arrow-container">';
				echo '<div class="crt-grid-slider-prev-arrow crt-grid-slider-arrow" id="crt-grid-slider-prev-'. esc_attr($this->get_id()) .'">'. Utilities::get_crt_icon( $settings['layout_slider_nav_icon'], '' ) .'</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<div class="crt-grid-slider-next-arrow crt-grid-slider-arrow" id="crt-grid-slider-next-'. esc_attr($this->get_id()) .'">'. Utilities::get_crt_icon( $settings['layout_slider_nav_icon'], '' ) .'</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';

			// Slider Dots
			echo '<div class="crt-grid-slider-dots"></div>';
		}

		// Pagination
		CRT_Grid_Helpers::render_grid_pagination( $settings );

		// No Posts Found
		else:
		
		if ( 'yes' === $settings['advanced_filters'] ) {
			// Grid Settings
			if ( 'slider' !== $settings['layout_select'] ) {
				$this->add_grid_settings( $settings );
				$render_attribute = $this->get_render_attribute_string( 'grid-settings' );

			// Slider Settings
			} else {
				$this->add_slider_settings( $settings );
				$render_attribute = $this->get_render_attribute_string( 'slider-settings' );
			}

			echo '<section class="crt-grid elementor-clearfix" '. $render_attribute .'>';

				if ( 'dynamic' === $settings['query_selection'] ) {
					echo '<h2>'. esc_html($settings['query_not_found_text']) .'</h2>';
				}

			// Grid Wrap
			echo '</section>';

			// Pagination
			CRT_Grid_Helpers::render_grid_pagination( $settings );
		} else {
			if ( 'dynamic' === $settings['query_selection'] ) {
				echo '<h2>'. esc_html($settings['query_not_found_text']) .'</h2>';
			}
		}

		// Loop: End
		endif;
	}
	
}