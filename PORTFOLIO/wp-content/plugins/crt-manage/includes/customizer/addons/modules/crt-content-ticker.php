<?php

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Icons;
use Elementor\Utils;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Content_Ticker extends Widget_Base {
		
	public function get_name() {
		return 'crt-content-ticker';
	}

	public function get_title() {
		return esc_html__( 'Content Ticker', 'crt-addons' );
	}

	public function get_icon() {
		return 'crt-icon eicon-carousel';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
	}

	public function get_keywords() {
		return [ 'blog', 'content ticker', 'news ticker', 'post ticker', 'posts ticker' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_script_depends() {
		return [ 'crt-manage-lib-slick', 'crt-marquee', 'crt-content-ticker' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com';
    }

	public function add_control_post_type() {
		$this->add_control(
			'post_type',
			[
				'label' => esc_html__( 'Select Type', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic' => esc_html__( 'Dynamic', 'crt-addons' ),
					'custom' => esc_html__( 'Custom', 'crt-addons' ),
				],
			]
		);
	}

	public function add_control_slider_effect() {
		$this->add_control(
			'slider_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'crt-addons' ),
				'default' => 'hr-slide',
				'options' => [
                    'typing' => esc_html__( 'Typing', 'crt-addons' ),
                    'fade' => esc_html__( 'Fade', 'crt-addons' ),
                    'hr-slide' => esc_html__( 'Horizontal Slide', 'crt-addons' ),
                    'vr-slide' => esc_html__( 'Vertical Slide', 'crt-addons' ),
				],
				'prefix_class' => 'crt-ticker-effect-',
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'type_select' => 'slider',
				],
			]
		);
	}

	public function add_control_slider_effect_cursor() {
		$this->add_control(
			'slider_effect_cursor',
			[
				'label' => esc_html__( 'Typing Cursor', 'crt-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '_',
				'selectors' => [
					'{{WRAPPER}}.crt-ticker-effect-typing .crt-ticker-title:after' => 'content: "{{VALUE}}";',
				],
				'condition' => [
					'type_select' => 'slider',
					'slider_effect' => 'typing',
				],
			]
		);
	}


	public function add_control_heading_icon_type() {
		$this->add_control(
			'heading_icon_type',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Select Type', 'crt-addons' ),
				'default' => 'fontawesome',
				'options' => [
                    'none' => esc_html__( 'None', 'crt-addons' ),
                    'fontawesome' => esc_html__( 'FontAwesome', 'crt-addons' ),
                    'circle' => esc_html__( 'Circle', 'crt-addons' ),
				],
			]
		);
	}

	public function add_control_type_select() {
        $this->add_control(
            'type_select',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Select Type', 'crt-addons' ),
                'default' => 'slider',
                'options' => [
                    'slider' => esc_html__( 'Slider', 'crt-addons' ),
                    'marquee' => esc_html__( 'Marquee', 'crt-addons' ),
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );
	}

	public function add_control_marquee_direction() {
        $this->add_control(
            'marquee_direction',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Direction', 'crt-addons' ),
                'default' => 'left',
                'options' => [
                    'left' => esc_html__( 'Left', 'crt-addons' ),
                    'right' => esc_html__( 'Right', 'crt-addons' ),
                ],
                'prefix_class' => 'crt-ticker-marquee-direction-',
                'render_type' => 'template',
                'separator' => 'before',
                'condition' => [
                    'type_select' => 'marquee',
                ],
            ]
        );
    }

	public function add_control_marquee_pause_on_hover() {
        $this->add_control(
            'marquee_pause_on_hover',
            [
                'label' => esc_html__( 'Pause on Hover', 'crt-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true',
                'condition' => [
                    'type_select' => 'marquee',
                ],
            ]
        );
    }

	public function add_control_marquee_effect_duration() {
        $this->add_control(
            'marquee_effect_duration',
            [
                'label' => esc_html__( 'Duration', 'crt-addons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 50,
                'min' => 0,
                'step' => 0.5,
                'condition' => [
                    'type_select' => 'marquee',
                ],
            ]
        );
    }

    public function add_section_ticker_items() {
        $this->start_controls_section(
            'section_ticker_items',
            [
                'label' => esc_html__( 'Ticker Items', 'crt-addons' ),
                'condition' => [
                    'post_type' => 'custom',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs( 'tabs_pricing_item' );

        $repeater->add_control(
            'ticker_title',
            [
                'label' => esc_html__( 'Title', 'crt-addons' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Custom Title 1',
            ]
        );

        $repeater->add_control(
            'ticker_image',
            [
                'label' => esc_html__( 'Image', 'crt-addons' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'ticker_link',
            [
                'label' => esc_html__( 'Link', 'crt-addons' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-addons' ),
                'separator' => 'before',

            ]
        );

        $this->add_control(
            'ticker_items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ ticker_title }}}',
                'default'  => [
                    [
                        'ticker_title' => esc_html__( 'Custom Title 1', 'crt-addons' ),
                    ],
                    [
                        'ticker_title' => esc_html__( 'Custom Title 2', 'crt-addons' ),
                    ],
                    [
                        'ticker_title' => esc_html__( 'Custom Title 3', 'crt-addons' ),
                    ],
                    [
                        'ticker_title' => esc_html__( 'Custom Title 4', 'crt-addons' ),
                    ],
                    [
                        'ticker_title' => esc_html__( 'Custom Title 5', 'crt-addons' ),
                    ],

                ]
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

    public $post_types;

	public function add_control_query_source () {
		// Get Available Post Types
        $this->post_types = [];
        $this->post_types['post'] = esc_html__( 'Posts', 'crt-addons' );
        $this->post_types['page'] = esc_html__( 'Pages', 'crt-addons' );

        $custom_post_types = Utilities::get_custom_types_of( 'post', true );
        foreach( $custom_post_types as $slug => $title ) {
            if ( 'product' === $slug || 'e-landing-page' === $slug ) {
                continue;
            }
            $this->post_types[$slug] = esc_html( $title );
        }

        $this->post_types['product'] = 'Products';
        $this->post_types['featured'] = 'Featured';
        $this->post_types['sale'] = 'On Sale';

        $this->add_control(
            'query_source',
            [
                'label' => esc_html__( 'Source', 'crt-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => $this->post_types,
            ]
        );
	}

	protected function register_controls() {

		// Section: General ----------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'crt-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::crt_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_post_type();


		$this->add_control(
			'link_type',
			[
				'label' => esc_html__( 'Link Type', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-addons' ),
					'title' => esc_html__( 'Title', 'crt-addons' ),
					'image' => esc_html__( 'Image', 'crt-addons' ),
					'image-title' => esc_html__( 'Image & Title', 'crt-addons' ),
					'box' => esc_html__( 'Box', 'crt-addons' ),
				],
				'default' => 'image-title',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Query ------------
		$this->start_controls_section(
			'section_ticker_query',
			[
				'label' => esc_html__( 'Query', 'crt-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'post_type' => 'dynamic',
				],
			]
		);

		$this->add_control_query_source();


		// Get Available Taxonomies
		$post_taxonomies = Utilities::get_custom_types_of( 'tax', false );

		$this->add_control(
			'query_selection',
			[
				'label' => esc_html__( 'Selection', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic' => esc_html__( 'Dynamic', 'crt-addons' ),
					'manual' => esc_html__( 'Manual', 'crt-addons' ),
				],
				'condition' => [
					'query_source!' => [ 'current', 'related' ],
				],
			]
		);

		$this->add_control(
			'query_tax_selection',
			[
				'label' => esc_html__( 'Selection Taxonomy', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => $post_taxonomies,
				'condition' => [
					'query_source' => 'related',
				],
			]
		);

		$this->add_control(
			'query_author',
			[
				'label' => esc_html__( 'Authors', 'crt-addons' ),
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
		foreach ( $post_taxonomies as $slug => $title ) {
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
			if ( 'featured' !== $slug && 'sale' !== $slug ) {
				$this->add_control(
					'query_exclude_'. $slug,
					[
						'label' => esc_html__( 'Exclude ', 'crt-addons' ) . $title,
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
		}

		// Manual Selection
		foreach ( $this->post_types as $slug => $title ) {
			$this->add_control(
				'query_manual_'. $slug,
				[
					'label' => esc_html__( 'Select ', 'crt-addons' ) . $title,
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

		$this->add_control(
			'query_posts_per_page',
			[
				'label' => esc_html__( 'Items Per Page', 'crt-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 0,
				'condition' => [
					'query_selection' => 'dynamic',
				]
			]
		);

		$this->add_control(
			'query_offset',
			[
				'label' => esc_html__( 'Offset', 'crt-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'condition' => [
					'query_selection' => 'dynamic',
				]
			]
		);


		$this->add_control(
			'post_order',
			[
				'label' => esc_html__( 'Order', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC' => esc_html__( 'Ascending', 'crt-addons' ),
					'DESC' => esc_html__( 'Descending', 'crt-addons' ),
				],
			]
		);

    	$this->add_control(
			'post_orderby',
			[
				'label' => esc_html__( 'Order By', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date', 'crt-addons' ),
					'modified' => esc_html__( 'Last Modified', 'crt-addons' ),
					'rand' => esc_html__( 'Rand', 'crt-addons' ),
					'title' => esc_html__( 'Title', 'crt-addons' ),
					'ID' => esc_html__( 'Post ID', 'crt-addons' ),
					'author' => esc_html__( 'Post Author', 'crt-addons' ),
					'comment_count' => esc_html__( 'Comment Count', 'crt-addons' ),
				],
			]
		);

		$this->add_control(
			'element_select_filter',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => $this->get_related_taxonomies(),
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Ticker Items ---------
		$this->add_section_ticker_items();

		// Section: Heading ----------
		$this->start_controls_section(
			'section_heading',
			[
				'label' => esc_html__( 'Heading', 'crt-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'heading_text',
			[
				'label' => esc_html__( 'Text', 'crt-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Hot News',
			]
		);

		$this->add_responsive_control(
			'heading_width',
			[
				'label' => esc_html__( 'Width', 'crt-addons' ),
				'type' => Controls_Manager::SLIDER,
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
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 120,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_position',
			[
				'label' => esc_html__( 'Position', 'crt-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'crt-ticker-heading-position-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_icon_section',
			[
				'label' => esc_html__( 'Icon', 'crt-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control_heading_icon_type();

		$this->add_control(
			'heading_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-star',
					'library' => 'fa-regular',
				],
				'condition' => [
					'heading_icon_type' => 'fontawesome',
				],
			]
		);

		$this->add_control(
			'heading_icon_position',
			[
				'label' => esc_html__( 'Position', 'crt-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'crt-ticker-heading-icon-position-',
				'condition' => [
					'heading_icon_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'heading_icon_size',
			[
				'label' => esc_html__( 'Size', 'crt-addons' ),
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
					'size' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-ticker-heading-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-ticker-icon-circle' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-ticker-icon-circle:before' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}}; margin-top: calc(-{{SIZE}}{{UNIT}} / 2);margin-left: calc(-{{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .crt-ticker-icon-circle:after' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}}; margin-top: calc(-{{SIZE}}{{UNIT}} / 2);margin-left: calc(-{{SIZE}}{{UNIT}} / 2);',
				],
				'condition' => [
					'heading_icon_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'heading_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-addons' ),
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
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-ticker-heading-icon-position-left .crt-ticker-heading-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-ticker-heading-icon-position-right .crt-ticker-heading-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'heading_icon_type!' => 'none',
				],
			]
		);

		// Triangle
		$this->add_control(
			'heading_triangle',
			[
				'label' => esc_html__( 'Triangle', 'crt-addons' ),
				'type' => Controls_Manager::SWITCHER,				
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_triangle_position',
			[
				'label' => esc_html__( 'Vertical Position', 'crt-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
                'default' => 'top',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'crt-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'crt-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'crt-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'prefix_class' => 'crt-ticker-heading-triangle-',
				'render_type' => 'template',
				'condition' => [
					'heading_triangle' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'heading_triangle_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Size', 'crt-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],			
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading:before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-ticker-heading-position-left .crt-ticker-heading:before' => 'right: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-ticker-heading-position-right .crt-ticker-heading:before' => 'left: -{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'heading_triangle' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_link',
			[
				'label' => esc_html__( 'Link', 'crt-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-addons' ),
				'separator' => 'before',
				
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'crt-addons' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'image_switcher',
			[
				'label' => esc_html__( 'Show Image', 'crt-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'full',
				'condition' => [
					'image_switcher' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'crt-addons' ),
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-slider' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-ticker-item' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control_type_select();

		$this->add_responsive_control(
			'slider_amount',
			[
				'label' => esc_html__( 'Number of Slides', 'crt-addons' ),
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'default' => 4,
				'widescreen_default' => 4,
				'laptop_default' => 4,
				'tablet_extra_default' => 4,
				'tablet_default' => 3,
				'mobile_extra_default' => 3,
				'mobile_default' => 1,
				'min' => 1,
				'max' => 10,
				'prefix_class' => 'crt-ticker-slider-columns-%s',
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'slider_effect' => 'hr-slide',
					'type_select' => 'slider',
				],
			]
		);

		$this->add_control(
			'slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'crt-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'frontend_available' => true,
				'default' => 1,
				'widescreen_default' => 1,
				'laptop_default' => 1,
				'tablet_extra_default' => 1,
				'tablet_default' => 1,
				'mobile_extra_default' => 1,
				'mobile_default' => 1,
				'prefix_class' => 'crt-ticker-slides-to-scroll-',
				'render_type' => 'template',
				'condition' => [
					'slider_effect' => 'hr-slide',
					'type_select' => 'slider',
				],
			]
		);

		$this->add_responsive_control(
			'slider_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'crt-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],			
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-slider .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-ticker-slider .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-ticker-marquee .crt-ticker-item' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',		
				'separator' => 'before',
				'conditions' => [
       		    	'relation' => 'or',
					'terms' => [
						[
							'name' => 'type_select',
							'operator' => '=',
							'value' => 'marquee',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'type_select',
									'operator' => '=',
									'value' => 'slider',
								],
								[
									'name' => 'slider_amount',
									'operator' => '!=',
									'value' => '1',
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'slider_nav',
			[
				'label' => esc_html__( 'Navigation', 'crt-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',			
				'separator' => 'before',
				'condition' => [
					'type_select' => 'slider',
				],
			]
		);

		$this->add_control(
			'slider_nav_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle-left',
				'options' => [
					'fas fa-angle-left' => esc_html__( 'Angle', 'crt-addons' ),
					'fas fa-angle-double-left' => esc_html__( 'Angle Double', 'crt-addons' ),
					'fas fa-arrow-left' => esc_html__( 'Arrow', 'crt-addons' ),
					'fas fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle', 'crt-addons' ),
					'far fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle Alt', 'crt-addons' ),
					'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'crt-addons' ),
					'fas fa-chevron-left' => esc_html__( 'Chevron', 'crt-addons' ),
				],
				'condition' => [
					'type_select' => 'slider',
					'slider_nav' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_nav_style',
			[
				'label' => esc_html__( 'Style', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'crt-addons' ),
					'vertical' => esc_html__( 'Vertical', 'crt-addons' ),
				],
				'prefix_class' => 'crt-ticker-arrow-style-',
				'condition' => [
					'type_select' => 'slider',
					'slider_nav' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_nav_position',
			[
				'label' => esc_html__( 'Position', 'crt-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'crt-ticker-arrow-position-',
				'render_type' => 'template',		
				'condition' => [
					'type_select' => 'slider',
					'slider_nav' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'crt-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'type_select' => 'slider',
				],
			]
		);

		$this->add_control(
			'slider_autoplay_duration',
			[
				'label' => esc_html__( 'Autoplay Speed', 'crt-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'min' => 0,
				'max' => 15,
				'step' => 0.5,
				'frontend_available' => true,
				'condition' => [
					'type_select' => 'slider',
					'slider_autoplay' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'slider_pause_on_hover',
			[
				'label' => esc_html__( 'Pause Slide on Hover', 'crt-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'slider_autoplay' => 'yes',
					'type_select' => 'slider',
				],
			]
		);

		$this->add_control(
			'slider_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'crt-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'default' => 'yes',
				'separator' => 'before',
				'condition' => [
					'type_select' => 'slider',
				],
			]
		);
		
		$this->add_control_slider_effect();

		$this->add_control_slider_effect_cursor();

		$this->add_control(
			'slider_effect_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,	
				'condition' => [
					'type_select' => 'slider',
				],
			]
		);

		$this->add_control_marquee_direction();

		$this->add_control_marquee_pause_on_hover();

		$this->add_control_marquee_effect_duration();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles
		// Section: Heading ----------
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Heading', 'crt-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_heading_colors' );

		$this->start_controls_tab(
			'tab_heading_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-addons' ),
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'heading_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-ticker-heading::before' => 'border-right-color: {{VALUE}};background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-ticker-heading-icon svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .crt-ticker-icon-circle' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-ticker-icon-circle::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-ticker-icon-circle::after' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_heading_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-addons' ),
			]
		);

		$this->add_control(
			'heading_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-ticker-heading:hover:before' => 'border-right-color: {{VALUE}};background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_hover_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading:hover .crt-ticker-heading-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-ticker-heading:hover .crt-ticker-heading-icon svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .crt-ticker-heading:hover .crt-ticker-icon-circle' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-ticker-heading:hover .crt-ticker-icon-circle::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-ticker-heading:hover .crt-ticker-icon-circle::after' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-ticker-heading svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',

				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .crt-ticker-heading-text',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'heading_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-addons' ),
					'solid' => esc_html__( 'Solid', 'crt-addons' ),
					'double' => esc_html__( 'Double', 'crt-addons' ),
					'dotted' => esc_html__( 'Dotted', 'crt-addons' ),
					'dashed' => esc_html__( 'Dashed', 'crt-addons' ),
					'groove' => esc_html__( 'Groove', 'crt-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'heading_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'heading_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'heading_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_input',
			[
				'label' => esc_html__( 'Content', 'crt-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-content-ticker-inner' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-ticker-gradient:after' => 'background-image: linear-gradient(to right,rgba(255,255,255,0),{{VALUE}});',
					'{{WRAPPER}} .crt-ticker-gradient:before' => 'background-image: linear-gradient(to left,rgba(255,255,255,0),{{VALUE}});',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .crt-content-ticker',
			]
		);

		$this->add_control(
			'content_gradient_position',
			[
				'label' => esc_html__( 'Gradient Position', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'none' => esc_html__( 'None', 'crt-addons' ),
					'left' => esc_html__( 'Left', 'crt-addons' ),
					'right' => esc_html__( 'Right', 'crt-addons' ),
					'both' => esc_html__( 'Both', 'crt-addons' ),
				],
				'prefix_class' => 'crt-ticker-gradient-type-',
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 30,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .crt-content-ticker-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-addons' ),
					'solid' => esc_html__( 'Solid', 'crt-addons' ),
					'double' => esc_html__( 'Double', 'crt-addons' ),
					'dotted' => esc_html__( 'Dotted', 'crt-addons' ),
					'dashed' => esc_html__( 'Dashed', 'crt-addons' ),
					'groove' => esc_html__( 'Groove', 'crt-addons' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .crt-content-ticker' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .crt-content-ticker' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#dbdbdb',
				'selectors' => [
					'{{WRAPPER}} .crt-content-ticker' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-content-ticker' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		// Title
		$this->add_control(
			'content_title_section',
			[
				'label' => esc_html__( 'Title', 'crt-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_title_typography',
				'selector' => '{{WRAPPER}} .crt-ticker-title',
			]
		);
		
		$this->start_controls_tabs( 'tabs_content_title_colors' );

		$this->start_controls_tab(
			'tab_content_title_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-addons' ),
			]
		);


		$this->add_control(
			'content_title_color',
			[
				'label' => esc_html__( 'Title Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-title a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-ticker-title-inner' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-ticker-title:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_content_title_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-addons' ),
			]
		);

		$this->add_control(
			'content_hover_title_color',
			[
				'label' => esc_html__( 'Title Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-title:hover a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-ticker-title-inner:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-ticker-title:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Image
		$this->add_control(
			'content_image_section',
			[
				'label' => esc_html__( 'Image', 'crt-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_image_width',
			[
				'label' => esc_html__( 'Width', 'crt-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-image' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Navigation -------
		$this->start_controls_section(
			'section_style_nav',
			[
				'label' => esc_html__( 'Navigation', 'crt-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'type_select' => 'slider',
					'slider_nav' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_nav_style' );

		$this->start_controls_tab(
			'tab_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-addons' ),
			]
		);

		$this->add_control(
			'nav_color',
			[
				'label' => esc_html__( 'Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-arrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-addons' ),
			]
		);

		$this->add_control(
			'nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-arrow' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'nav_font_size',
			[
				'label' => esc_html__( 'Font Size', 'crt-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'nav_size',
			[
				'label' => esc_html__( 'Box Size', 'crt-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'crt-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],			
				'selectors' => [
					'{{WRAPPER}}.crt-ticker-arrow-style-vertical .crt-ticker-prev-arrow' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-ticker-arrow-style-horizontal .crt-ticker-prev-arrow' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'nav_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-addons' ),
					'solid' => esc_html__( 'Solid', 'crt-addons' ),
					'double' => esc_html__( 'Double', 'crt-addons' ),
					'dotted' => esc_html__( 'Dotted', 'crt-addons' ),
					'dashed' => esc_html__( 'Dashed', 'crt-addons' ),
					'groove' => esc_html__( 'Groove', 'crt-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-arrow' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'nav_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ticker-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	// Get Taxonomies Related to Post Type
	public function get_related_taxonomies() {
		$relations = [];
		$this->post_types = Utilities::get_custom_types_of( 'post', false );

		foreach ( $this->post_types as $slug => $title ) {
			$relations[$slug] = [];

			foreach ( get_object_taxonomies( $slug ) as $tax ) {
				array_push( $relations[$slug], $tax );
			}
		}

		return json_encode( $relations );
	}

	// Main Query Args
	public function get_main_query_args() {
		$settings = $this->get_settings();
		$author = ! empty( $settings[ 'query_author' ] ) ? implode( ',', $settings[ 'query_author' ] ) : '';

		in_array( $settings[ 'query_source' ], ['pro-pd', 'pro-ft', 'pro-sl'] ) ? $settings[ 'query_source' ] = 'post' : '';

		// Dynamic
		$args = [
			'post_type' => $settings[ 'query_source' ],
			'tax_query' => $this->get_tax_query_args(),
			'post__not_in' => isset($settings[ 'query_exclude_'. $settings[ 'query_source' ] ]) ? $settings[ 'query_exclude_'. $settings[ 'query_source' ] ] : '',
			'posts_per_page' => $settings['query_posts_per_page'],
			'orderby' => $settings[ 'post_orderby' ],
			'order' => $settings[ 'post_order' ],
			'author' => $author,
			'offset' => $settings[ 'query_offset' ],
		];

		// Manual
		if ( 'manual' === $settings[ 'query_selection' ] ) {
			$post_ids = [''];

			if ( ! empty($settings[ 'query_manual_'. $settings[ 'query_source' ] ]) ) {
				$post_ids = $settings[ 'query_manual_'. $settings[ 'query_source' ] ];
			}

			$args = [
				'post_type' => $settings[ 'query_source' ],
				'post__in' => $post_ids,
				'orderby' => $settings[ 'post_orderby' ],
				'order' => $settings[ 'post_order' ],
			];
		}

		// Get Post Type
		if ( 'current' === $settings[ 'query_source' ] ) {
			global $wp_query;

			$args = $wp_query->query_vars;
			$args['posts_per_page'] = $settings['query_posts_per_page'];
			$args['orderby'] = $settings['post_orderby'];
		}

		// Related
		if ( 'related' === $settings[ 'query_source' ] ) {
			$args = [
				'post_type' => get_post_type( get_the_ID() ),
				'tax_query' => $this->get_tax_query_args(),
				'post__not_in' => [ get_the_ID() ],
				'ignore_sticky_posts' => 1,
				'posts_per_page' => $settings['query_posts_per_page'],
				'orderby' => $settings[ 'post_orderby' ],
				'order' => $settings[ 'post_order' ],
				'offset' => $settings[ 'query_offset' ],
			];
		}

		if ( 'featured' === $settings[ 'query_source' ] ) {
			$args['post_type'] = 'product';
			$tax_query[] = [
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN', // or 'NOT IN' to exclude feature products
			];
			$args['tax_query'] = $tax_query;
		}

		if ( 'sale' === $settings[ 'query_source' ] ) {
			$args['post_type'] = 'product';
			$meta_query[] = [
				'relation' => 'OR',
				[ // Simple products type
					'key'           => '_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				],
				[ // Variable products type
					'key'           => '_min_variation_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				]
			];

			$args['meta_query'] = $meta_query;
		}

		return $args;
	}

	// Taxonomy Query Args
	public function get_tax_query_args() {
		$settings = $this->get_settings();
		$tax_query = [];

		if ( 'related' === $settings[ 'query_source' ] ) {
			$tax_query = [
				[
					'taxonomy' => $settings['query_tax_selection'],
					'field' => 'term_id',
					'terms' => wp_get_object_terms( get_the_ID(), $settings['query_tax_selection'], array( 'fields' => 'ids' ) ),
				]
			];
		} else {
			foreach ( get_object_taxonomies($settings[ 'query_source' ]) as $tax ) {
				if ( ! empty($settings[ 'query_taxonomy_'. $tax ]) ) {
					array_push( $tax_query, [
						'taxonomy' => $tax,
						'field' => 'id',
						'terms' => $settings[ 'query_taxonomy_'. $tax ]
					] );
				}
			}
		}

		return $tax_query;
	}

	// Dynamic Content Ticker
	public function crt_content_ticker_dynamic() {
		//  Get Settings
		$settings = $this->get_settings();
	
		// Get Posts
		$posts = new \WP_Query( $this->get_main_query_args() );
		
		if ( $posts->have_posts() ) :

		while ( $posts->have_posts() ) : $posts->the_post();

				$image_id = get_post_thumbnail_id();
				$image_src = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image_size', $settings );
				$image_alt = '' === wp_get_attachment_caption( $image_id ) ? get_the_title() : wp_get_attachment_caption( $image_id );
			?>

			<div class="crt-ticker-item">

				<?php if ( 'box' === $settings['link_type'] ): ?>
				<a class="crt-ticker-link" href="<?php echo esc_url( get_the_permalink() ); ?>"></a>	
				<?php endif; ?>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="crt-ticker-image">
						
						<?php
						if ( 'image' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
							echo '<a  href="'. esc_url( get_the_permalink() ).'">';
						}

						if ( 'yes' === $settings['image_switcher'] && $image_src ) {	
							echo '<img src="'. esc_url( $image_src ) .'" alt="'. esc_attr( $image_alt ) .'">';
						}
					
						if ( 'image' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
							echo '</a>';
						}
						?>

					</div>
				<?php endif; ?>

				<h3 class="crt-ticker-title">
					<div class="crt-ticker-title-inner">
					<?php
					if ( 'title' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
						echo '<a href="'. esc_url( get_the_permalink() ).'">';
					}

					the_title();
				
					if ( 'title' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
						echo '</a>';
					}
					?>
					</div>
				</h3>

			</div>

		<?php

		endwhile;

		// reset
		wp_reset_postdata();

		// Loop: End
		endif;
	}


	// Custom Content Ticker
    public function crt_content_ticker_custom() {

        $settings = $this->get_settings();
        $item_count = 0;

        ?>

        <?php foreach ( $settings['ticker_items'] as $key=>$item ) : ?>

            <?php

            $image_src = Group_Control_Image_Size::get_attachment_image_src( $item['ticker_image']['id'], 'image_size', $settings );

            if ( !$image_src ) {
                $image_src = $item['ticker_image']['url'];
            }

            $this->add_render_attribute( 'link_attribute'. $key, 'href', esc_url( $item['ticker_link']['url'] ) );

            if ( $item['ticker_link']['is_external'] ) {
                $this->add_render_attribute( 'link_attribute'. $key, 'target', '_blank' );
            }

            if ( $item['ticker_link']['nofollow'] ) {
                $this->add_render_attribute( 'link_attribute'. $key, 'nofollow', '' );
            }

            ?>

            <div class="crt-ticker-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">

                <?php if ( 'box' === $settings['link_type'] ): ?>
                    <a class="crt-ticker-link" <?php echo $this->get_render_attribute_string( 'link_attribute'. $key ); ?>></a>
                <?php endif; ?>

                <?php if ( 'yes' === $settings['image_switcher'] && $image_src ) : ?>
                    <div class="crt-ticker-image">

                        <?php
                        if ( 'image' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
                            echo '<a '.$this->get_render_attribute_string( 'link_attribute'. $key ).'>';
                        }

                        echo '<img src="'. esc_url( $image_src ) .'" >';

                        if ( 'image' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
                            echo '</a>';
                        }
                        ?>

                    </div>
                <?php endif; ?>

                <?php if ( '' !== $item['ticker_title'] ) : ?>
                    <h3 class="crt-ticker-title">
                        <div class="crt-ticker-title-inner">
                            <?php
                            if ( 'title' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
                                echo '<a '.$this->get_render_attribute_string( 'link_attribute'. $key ).'>';
                            }

                            echo esc_html( $item['ticker_title'] );

                            if ( 'title' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
                                echo '</a>';
                            }
                            ?>
                        </div>
                    </h3>
                <?php endif; ?>

            </div>

            <?php
            $item_count++;
        endforeach;

    }

    public function crt_content_ticker_heading() {

		// Get Settings
		$settings = $this->get_settings();
		$heading_element = 'div';
		$heading_link =  $settings['heading_link']['url'];

		$this->add_render_attribute( 'heading_attribute', 'class', 'crt-ticker-heading' );

		if ( '' !== $heading_link ) {

			$heading_element = 'a';

			$this->add_render_attribute( 'heading_attribute', 'href', esc_url( $settings['heading_link']['url'] ) );

			if ( $settings['heading_link']['is_external'] ) {
				$this->add_render_attribute( 'heading_attribute', 'target', '_blank' );
			}

			if ( $settings['heading_link']['nofollow'] ) {
				$this->add_render_attribute( 'heading_attribute', 'nofollow', '' );
			}
		}


		?>

		<<?php echo esc_html($heading_element); ?> <?php echo $this->get_render_attribute_string( 'heading_attribute' ); ?>>
			<span class="crt-ticker-heading-text"><?php echo esc_html( $settings['heading_text'] ); ?></span>
			<span class="crt-ticker-heading-icon">
				<?php if ( 'fontawesome' === $settings['heading_icon_type'] ): ?>	
				<?php \Elementor\Icons_Manager::render_icon( $settings['heading_icon'] ); ?>
				<?php elseif ( 'circle' === $settings['heading_icon_type'] ) : ?>
				<span class="crt-ticker-icon-circle"></span>
				<?php endif; ?>
			</span>
		</<?php echo esc_html($heading_element); ?>>

		<?php
	}

	public function crt_content_ticker_slider() {
		
		// Get Settings
		$settings = $this->get_settings();
		$slider_is_rtl = is_rtl();
		$slider_direction = $slider_is_rtl ? 'rtl' : 'ltr';

		$slider_options = [
			'rtl' => $slider_is_rtl,
			'infinite' => ( $settings['slider_loop'] === 'yes' ),
			'speed' => absint( $settings['slider_effect_duration'] * 1000 ),
			'autoplay' => ( $settings['slider_autoplay'] === 'yes' ),
			'autoplaySpeed' => absint( $settings['slider_autoplay_duration'] * 1000 ),
			'pauseOnHover' => $settings['slider_pause_on_hover'],
			'arrows' => false,
		];

		if ( $settings['slider_effect'] === 'vr-slide' ) {
			$slider_options['vertical'] = true;
		}

		if ( $settings['slider_nav'] === 'yes' ) {
			$slider_options['arrows'] = true;
			$slider_options['prevArrow'] = '<div class="crt-ticker-prev-arrow crt-ticker-arrow"><i class="'. esc_attr($settings['slider_nav_icon']) .'"></i></div>';
			$slider_options['nextArrow'] = '<div class="crt-ticker-next-arrow crt-ticker-arrow"><i class="'. esc_attr($settings['slider_nav_icon']) .'"></i></div>';
		}

		$this->add_render_attribute( 'ticker-slider-attribute', [
			'class' => 'crt-ticker-slider',
			'dir' => esc_attr( $slider_direction ),
			'data-slick' => wp_json_encode( $slider_options ),
		] );


		if ( 'none' !== $settings['content_gradient_position'] ) {
			$this->add_render_attribute( 'ticker-slider-attribute','class', 'crt-ticker-gradient' );
		}


		?>

		<div <?php echo $this->get_render_attribute_string( 'ticker-slider-attribute' ); ?> data-slide-effect="<?php echo esc_attr($settings['slider_effect']); ?>">	
			<?php
				if ( 'dynamic' === $settings['post_type'] ) {
					$this->crt_content_ticker_dynamic();
				} else {
					$this->crt_content_ticker_custom();
				}
			?>
		</div>

		<div class="crt-ticker-slider-controls"></div>

		<?php

	}

    public function crt_content_ticker_marquee() {

        // Get Settings
        $settings = $this->get_settings();

        $marquee_options = [
            'direction' => $settings['marquee_direction'],
            'duplicated' => true,
            'startVisible' => true,
            'gap' => 0,
            'duration' => absint( $settings['marquee_effect_duration'] * 1000 ),
            'pauseOnHover' => $settings['marquee_pause_on_hover'],
        ];

        $this->add_render_attribute( 'ticker-marquee-attribute', [
            'class' => 'crt-ticker-marquee crt-marquee-hidden',
            'data-options' => wp_json_encode( $marquee_options ),
        ] );

        if ( 'none' !== $settings['content_gradient_position'] ) {
            $this->add_render_attribute( 'ticker-marquee-attribute','class', 'crt-ticker-gradient' );
        }

        ?>

        <div <?php echo $this->get_render_attribute_string( 'ticker-marquee-attribute' ); ?>>
            <?php
            if ( 'dynamic' === $settings['post_type'] ) {
                $this->crt_content_ticker_dynamic();
            } else {
                $this->crt_content_ticker_custom();
            }
            ?>
        </div>

        <?php

    }

    protected function render() {

		// Get Settings
		$settings = $this->get_settings();

		?>

		<!-- Content Ticker Slider -->
		<div class="crt-content-ticker">

			<?php

			if ( '' !== $settings['heading_text'] || 'none' !== $settings['heading_icon_type'] ) {
				$this->crt_content_ticker_heading(); 
			}

			?>

			<div class="crt-content-ticker-inner">
				
				<?php

				if ( 'slider' === $settings['type_select'] ) {
					$this->crt_content_ticker_slider();
				} else {
					$this->crt_content_ticker_marquee();
				}
				
				?>
				
			</div>

		</div>

		<?php
	}
}