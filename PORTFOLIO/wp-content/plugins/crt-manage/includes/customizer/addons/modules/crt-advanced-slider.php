<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Icons;
use Elementor\Icons_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Advanced_Slider extends Widget_Base {
		
	public function get_name() {
		return 'crt-advanced-slider';
	}

	public function get_title() {
		return esc_html__( 'Advanced Slider/Carousel', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-media-carousel';
	}

	public function get_categories() {
		return [ 'crt_manage_theme' ];
	}

	public function get_keywords() {
		return [ 'image slider', 'slideshow', 'image carousel', 'template slider', 'posts slider' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}
	
	public function get_script_depends() {
		return [ 'imagesloaded', 'crt-manage-lib-slick', $this->get_name() ];
	}

//	public function get_style_depends() {
//		return [ 'crt-animations-css' ];
//	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com/contact';
    }
		
	public function add_control_slider_effect() {
		$this->add_control(
			'slider_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'crt-manage' ),
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'crt-manage' ),
					'slide-vertical' => esc_html__( 'Sl Vertical', 'crt-manage' ),
					'fade' => esc_html__( 'Fade', 'crt-manage' ),
				],
				'separator' => 'before'
			]
		);
	}

	public function add_control_slider_nav_hover() {
		$this->add_control(
			'slider_nav_hover',
			[
				'label' => __( 'Show on Hover', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'no-distance'
			]
		);
	}

	public function add_control_slider_dots_layout() {
		$options = [
			'horizontal' => esc_html__( 'Horizontal', 'crt-manage' ),
		];

		if ( Utilities::is_pro() ) {
			$options['Vertical'] = esc_html__( 'Vertical', 'crt-manage' );
		} else {
			$options['pro-vr'] = esc_html__( 'Vertical (Pro)', 'crt-manage' );
		}

		$this->add_control(
			'slider_dots_layout',
			[
				'label' => esc_html__( 'Pagination Layout', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => $options,
				'prefix_class' => 'crt-slider-dots-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_slider_autoplay() {
		$this->add_control(
			'slider_autoplay',
			[
				'label' => __( 'Autoplay', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => ''
			]
		);
	}

	public function add_control_slider_autoplay_duration() {
        $this->add_control(
            'slider_autoplay_duration',
            [
                'label' => __( 'Autoplay', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'classes' => 'no-distance'
            ]
        );
    }

	public function add_control_slider_pause_on_hover() {
		$this->add_control(
			'slider_pause_on_hover',
			[
				'label' => __( 'Pause on Hover', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'no-distance'
			]
		);
	}

	public function add_control_slider_scroll_btn() {
		$this->add_control(
			'slider_scroll_btn',
			[
				'label' => __( 'Scroll to Section Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => ''
			]
		);
	}

	public function add_repeater_args_slider_item_bg_kenburns() {
		return [
			'label' => __( 'Ken Burn Effect', 'crt-manage' ),
			'type' => Controls_Manager::SWITCHER,
			'separator' => 'before',
			'conditions' => [
				'terms' => [
					[
						'name' => 'slider_item_bg_image[url]',
						'operator' => '!=',
						'value' => '',
					],
				],
			],
			'classes' => ''
		];
	}

	public function add_repeater_args_slider_item_bg_zoom() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_slider_content_type() {
		$options = [
			'custom' => esc_html__( 'Custom', 'crt-manage' ),
		];

		if ( Utilities::is_pro() ) {
			$options['template'] = esc_html__( 'Elementor Template', 'crt-manage' );
		} else {
			$options['pro-tm'] = esc_html__( 'Elementor Template (Pro)', 'crt-manage' );
		}

		return $options;
	}

	public function add_repeater_args_slider_select_template() {
        return [
            'label'	=> esc_html__( 'Select Template', 'crt-manage' ),
            'type' => 'crt-ajax-select2',
            'options' => 'ajaxselect2/get_elementor_templates',
            'label_block' => true,
            'condition' => [
                'slider_content_type' => 'template'
            ]
        ];
	}

	public function add_repeater_args_slider_item_link_type() {
		$options = [
			'none' => esc_html__( 'None', 'crt-manage' ),
		];

		if ( Utilities::is_pro() ) {
			$options['custom'] = esc_html__( 'Custom URL', 'crt-manage' );
			$options['video-youtube'] = esc_html__( 'Youtube', 'crt-manage' );
			$options['video-vimeo'] = esc_html__( 'Vimeo', 'crt-manage' );
			$options['video-media'] = esc_html__( 'Custom Video', 'crt-manage' );
		} else {
            $options['custom'] = esc_html__( 'Custom URL', 'crt-manage' );
            $options['video-youtube'] = esc_html__( 'Youtube', 'crt-manage' );
		    $options['pro-vm'] = esc_html__( 'Vimeo (Pro)', 'crt-manage' );
			$options['pro-md'] = esc_html__( 'Custom Video (Pro)', 'crt-manage' );
		}

		return [
			'label' => esc_html__( 'Link Type', 'crt-manage' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => $options,
			'condition' => [
				'slider_content_type' => 'custom'
			],
			'separator' => 'before'
		];
	}

    public function add_section_style_scroll_btn() {
        $this->start_controls_section(
            'crt_section_style_scroll_btn',
            [
                'label' => esc_html__( 'Scroll Button', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_scroll_btn_style' );

        $this->start_controls_tab(
            'tab_scroll_btn_normal',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'scroll_btn_color',
            [
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-slider-scroll-btn' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-slider-scroll-btn svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'scroll_btn_border_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-slider-scroll-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_scroll_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'scroll_btn_hover_color',
            [
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-slider-scroll-btn:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .crt-slider-scroll-btn:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'scroll_btn_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-slider-scroll-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'scroll_btn_font_size',
            [
                'label' => esc_html__( 'Size', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .crt-slider-scroll-btn' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-slider-scroll-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'scroll_btn_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', ],
                'default' => [
                    'top' => 6,
                    'right' => 7,
                    'bottom' => 8,
                    'left' => 7,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-slider-scroll-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'scroll_btn_vr',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Vertical Position', 'crt-manage' ),
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 150,
                    ],
                    '%' => [
                        'min' => -20,
                        'max' => 120,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 45,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-slider-scroll-btn' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'scroll_btn_border_type',
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
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .crt-slider-scroll-btn' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'scroll_btn_border_width',
            [
                'label' => esc_html__( 'Border Width', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', ],
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-slider-scroll-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'scroll_btn_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'scroll_btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 9,
                    'right' => 9,
                    'bottom' => 9,
                    'left' => 9,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-slider-scroll-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    public function add_control_slider_amount() {
		$this->add_responsive_control(
			'slider_amount',
			[
				'label' => esc_html__( 'Columns (Carousel)', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 1,
				'widescreen_default' => 1,
				'laptop_default' => 1,
				'tablet_extra_default' => 1,
				'tablet_default' => 1,
				'mobile_extra_default' => 1,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'crt-manage' ),
					2 => esc_html__( 'Two', 'crt-manage' ),
					3 => esc_html__( 'Three', 'crt-manage' ),
					4 => esc_html__( 'Four', 'crt-manage' ),
					5 => esc_html__( 'Five', 'crt-manage' ),
					6 => esc_html__( 'Six', 'crt-manage' ),
				],
				'prefix_class' => 'crt-adv-slider-columns-%s',
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'slider_effect!' => 'slide_vertical'
				]
			]
		);
	}

	public function add_control_slides_to_scroll() {
		$this->add_control(
			'slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 2,
				'prefix_class' => 'crt-adv-slides-to-scroll-',
				'render_type' => 'template',
				'frontend_available' => true,
				'default' => 1,
				'condition' => [
					'slider_effect!' => 'slide_vertical'
				]
			]
		);
	}

	public function add_control_stack_slider_nav_position() {
		$this->add_control(
			'slider_nav_position',
			[
				'label' => esc_html__( 'Positioning', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'custom',
				'options' => [
					'default' => esc_html__( 'Default', 'crt-manage' ),
					'custom' => esc_html__( 'Custom', 'crt-manage' ),
				],
				'prefix_class' => 'crt-slider-nav-position-',
			]
		);

		$this->add_control(
			'slider_nav_position_default',
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
				'prefix_class' => 'crt-slider-nav-align-',
				'condition' => [
					'slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_outer_distance',
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
					'{{WRAPPER}}[class*="crt-slider-nav-align-top"] .crt-slider-arrow-container' => 'top: {{SIZE}}px;',
					'{{WRAPPER}}[class*="crt-slider-nav-align-bottom"] .crt-slider-arrow-container' => 'bottom: {{SIZE}}px;',
					'{{WRAPPER}}.crt-slider-nav-align-top-left .crt-slider-arrow-container' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.crt-slider-nav-align-bottom-left .crt-slider-arrow-container' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.crt-slider-nav-align-top-right .crt-slider-arrow-container' => 'right: {{SIZE}}px;',
					'{{WRAPPER}}.crt-slider-nav-align-bottom-right .crt-slider-arrow-container' => 'right: {{SIZE}}px;',
				],
				'condition' => [
					'slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_inner_distance',
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
					'{{WRAPPER}} .crt-slider-arrow-container .crt-slider-prev-arrow' => 'margin-right: {{SIZE}}px;',
				],
				'condition' => [
					'slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_position_top',
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
					'{{WRAPPER}} .crt-slider-arrow' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_position_left',
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
					'{{WRAPPER}} .crt-slider-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_position_right',
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
					'{{WRAPPER}} .crt-slider-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_nav_position' => 'custom',
				],
			]
		);
	}


	public function add_control_slider_dots_hr() {
		$this->add_responsive_control(
			'slider_dots_hr',
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
					'{{WRAPPER}} .crt-slider-dots' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}


	protected function register_controls() {

		// Section: Slides -----------
		$this->start_controls_section(
			'crt__section_slides',
			[
				'label' => esc_html__( 'Slides', 'crt-manage' ),
			]
		);

		Utilities::crt_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'posts_slider_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Looking for a <strong>Post Slider or Carousel?</strong>, <ul><li>1. Search for the <strong>"Post Slider"</strong> in widgets</li><li>2. Add <strong>"Posts Grid/Slider/Carousel"</strong></li><li>3. Navigate to <strong>"Layout"</strong> section</li><li>4. Select Layout: <strong>"Slider / Carousel"</strong></li></ul>', 'crt-manage' ),
				'separator' => 'after',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
            'slider_content_type',
            [
                'label' => esc_html__( 'Content Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => $this->add_repeater_args_slider_content_type(),
				'render_type' => 'template'
            ]
        );

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'advanced-slider', 'slider_content_type', ['pro-tm'] );

		$repeater->add_control( 'slider_select_template', $this->add_repeater_args_slider_select_template() );

		$repeater->add_control(
			'slider_content_type_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$repeater->start_controls_tabs( 'tabs_slider_item' );

		$repeater->start_controls_tab(
			'tab_slider_item_background',
			[
				'label' => esc_html__( 'Background', 'crt-manage' ),
			]
		);

		$repeater->add_control(
			'slider_item_bg_image',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'slider_item_bg_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover' => esc_html__( 'Cover', 'crt-manage' ),
					'contain' => esc_html__( 'Contain', 'crt-manage' ),
					'auto' => esc_html__( 'Auto', 'crt-manage' ),
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-slider-item-bg' => 'background-size: {{VALUE}}',
				],
				// 'conditions' => [
				// 	'slider_content_type' => 'custom'
				// ]
			]
		);

		$repeater->add_control( 'slider_item_link_type', $this->add_repeater_args_slider_item_link_type() );

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'advanced-slider', 'slider_item_link_type', ['pro-vm', 'pro-md'] );

		$repeater->add_control(
			'vimeo_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => 'Please Upload Background Image',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition' => [
					'slider_item_link_type' => 'video-vimeo'
				]
			]
		);

		$repeater->add_control(
			'slider_item_bg_image_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'crt-manage' ),
				'show_label' => false,
				'condition' => [
					'slider_item_link_type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'hosted_url',
			[
				'label' => esc_html__( 'Choose File', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::MEDIA_CATEGORY,
					],
				],
				'media_type' => 'video',
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => 'video-media',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_src',
			[
				'label' => esc_html__( 'Video URL', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo', 'video-media'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_loop',
			[
				'label' => esc_html__( 'Loop', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo','video-media'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_mute',
			[
				'label' => esc_html__( 'Mute', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo', 'video-media'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_controls',
			[
				'label' => esc_html__( 'Controls', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo', 'video-media'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_start',
			[
				'label' => esc_html__( 'Start Time', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'description' => esc_html__( 'Specify a start time (in seconds)', 'crt-manage' ),
				'frontend_available' => true,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo'],
					'slider_item_video_loop!' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_end',
			[
				'label' => esc_html__( 'End Time', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'description' => esc_html__( 'Specify an end time (in seconds)', 'crt-manage' ),
				'frontend_available' => true,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => 'video-youtube',
					'slider_item_video_loop!' => 'yes',
				],
			]
		);

		$repeater->add_control( 'slider_item_bg_kenburns', $this->add_repeater_args_slider_item_bg_kenburns() );

		$repeater->add_control( 'slider_item_bg_zoom', $this->add_repeater_args_slider_item_bg_zoom() );

		$repeater->add_control(
			'overlay_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$repeater->add_control(
			'slider_item_overlay',
			[
				'label' => esc_html__( 'Background Overlay', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'render_type' => 'template',
				'condition' => [
					'slider_content_type' => 'custom'
				]
			]
		);

		$repeater->add_control(
			'slider_item_overlay_bg',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(236,64,122,0.8)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-slider-item-overlay' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'slider_item_overlay' => 'yes',
					'slider_content_type' => 'custom'
				],
			]
		);

		$repeater->add_control(
			'slider_item_blend_mode',
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
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-slider-item-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
				'condition' => [
					'slider_item_overlay' => 'yes',
					'slider_content_type' => 'custom'
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tab_slider_item_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
			]
		);

		$repeater->add_control(
			'slider_show_content',
			[
				'label' => esc_html__( 'Show Sldier Content', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'slider_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'crt-manage' ),
					'h2' => esc_html__( 'H2', 'crt-manage' ),
					'h3' => esc_html__( 'H3', 'crt-manage' ),
					'h4' => esc_html__( 'H4', 'crt-manage' ),
					'h5' => esc_html__( 'H5', 'crt-manage' ),
					'h6' => esc_html__( 'H6', 'crt-manage' ),
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h2',
				'condition' => [
					'slider_show_content' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'slider_item_title',
			[
				'label'  	=> esc_html__( 'Title', 'crt-manage' ),
				'type'   	=> Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Slide Title',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_sub_title_tag',
			[
				'label' => esc_html__( 'Sub Title HTML Tag', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'crt-manage' ),
					'h2' => esc_html__( 'H2', 'crt-manage' ),
					'h3' => esc_html__( 'H3', 'crt-manage' ),
					'h4' => esc_html__( 'H4', 'crt-manage' ),
					'h5' => esc_html__( 'H5', 'crt-manage' ),
					'h6' => esc_html__( 'H6', 'crt-manage' ),
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h3',
				'condition' => [
					'slider_show_content' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'slider_item_sub_title',
			[
				'label'  	=> esc_html__( 'Sub Title', 'crt-manage' ),
				'type'   	=> Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Slide Sub Title',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_description',
			[
				'label'   	=> esc_html__( 'Description', 'crt-manage' ),
				'type'    	=> Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Slider Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_1_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_responsive_control(
			'slider_item_btn_1',
			[
				'label' => esc_html__( 'Button Primary', 'crt-manage' ),
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
					'yes' => 'inline-block'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-slider-primary-btn' => 'display:{{VALUE}};',
				],
				'condition' => [
					'slider_show_content' => 'yes',
				],
				'render_type' => 'template'
			]
		);

		$repeater->add_control(
			'slider_item_btn_text_1',
			[
				'label' => esc_html__( 'Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Button 1',
				'condition' => [
					'slider_item_btn_1' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_icon_1',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'slider_item_btn_1' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_url_1',
			[
				'label' => esc_html__( 'Link', 'crt-manage' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
				'condition' => [
					'slider_item_btn_1' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_2_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_responsive_control(
			'slider_item_btn_2',
			[
				'label' => esc_html__( 'Button Secondary', 'crt-manage' ),
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
					'yes' => 'inline-block'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-slider-secondary-btn' => 'display:{{VALUE}};',
				],
				'condition' => [
					'slider_show_content' => 'yes',
				],
				'render_type' => 'template'
			]
		);

		$repeater->add_control(
			'slider_item_btn_text_2',
			[
				'label' => esc_html__( 'Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Button 2',
				'condition' => [
					'slider_item_btn_2' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_icon_2',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'slider_item_btn_2' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_url_2',
			[
				'label' => esc_html__( 'Link', 'crt-manage' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
				'condition' => [
					'slider_item_btn_2' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'slider_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						
						'slider_item_title' => esc_html__( 'Slide 1 Title', 'crt-manage' ),
						'slider_item_sub_title' => esc_html__( 'Slide 1 Sub Title', 'crt-manage' ),
						'slider_item_description' => esc_html__( 'Slider 1 Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ', 'crt-manage' ),
						'slider_item_btn_text_1' => esc_html__( 'Button 1', 'crt-manage' ),
						'slider_item_btn_text_2' => esc_html__( 'Button 2', 'crt-manage' ),
						'slider_item_overlay_bg' => '#e55b5b9C',
					],
					[
						
						'slider_item_title' => esc_html__( 'Slide 2 Title', 'crt-manage' ),
						'slider_item_sub_title' => esc_html__( 'Slide 2 Sub Title', 'crt-manage' ),
						'slider_item_description' => esc_html__( 'Slider 2 Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ', 'crt-manage' ),
						'slider_item_btn_text_1' => esc_html__( 'Button 1', 'crt-manage' ),
						'slider_item_btn_text_2' => esc_html__( 'Button 2', 'crt-manage' ),
						'slider_item_overlay_bg' => '#AB47BCAB',
					],
					[
						
						'slider_item_title' => esc_html__( 'Slide 3 Title', 'crt-manage' ),
						'slider_item_sub_title' => esc_html__( 'Slide 3 Sub Title', 'crt-manage' ),
						'slider_item_description' => esc_html__( 'Slider 3 Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ', 'crt-manage' ),
						'slider_item_btn_text_1' => esc_html__( 'Button 1', 'crt-manage' ),
						'slider_item_btn_text_2' => esc_html__( 'Button 2', 'crt-manage' ),
						'slider_item_overlay_bg' => '#EF535094',
					],
				],
				'title_field' => '{{{ slider_item_title }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Slider Options ---
		$this->start_controls_section(
			'crt__section_slider_options',
			[
				'label' => esc_html__( 'Settings', 'crt-manage' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'slider_image_size',
				'default' => 'full',
			]
		);

		$this->add_control(
			'slider_image_type',
			[
				'label' => esc_html__( 'Media Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'background',
				'options' =>  [
					'background' => esc_html__( 'Background', 'crt-manage' ),
					'image' => esc_html__( 'Image', 'crt-manage' )
				]
			]
		);

		$this->add_responsive_control(
			'slider_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1500,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-slider' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-slider-item' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-list' => 'height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
				'condition' => [
					'slider_image_type' => 'background'
				]
			]
		);

		$this->add_control_slider_amount();

		$this->add_control_slides_to_scroll();

		$this->add_control(
			'slides_amount_hidden',
			[
				'type' => Controls_Manager::HIDDEN,
				'prefix_class' => 'crt-adv-slider-columns-',
				'default' => 1,
				'condition' => [
					'slider_effect' => 'slide_vertical'
				]
			]
		);

		$this->add_control(
			'slides_to_scroll_hidden',
			[
				'type' => Controls_Manager::HIDDEN,
				'prefix_class' => 'crt-adv-slides-to-scroll-',
				'default' => 1,
				'condition' => [
					'slider_effect' => 'slide_vertical'
				]
			]
		);

		$this->add_responsive_control(
			'slider_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-slider .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-advanced-slider .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'slider_amount!' => '1',
				],	
			]
		);

		$this->add_control(
			'slider_btn_heading',
			[
				'label' => esc_html__( 'Button', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_btn_icon_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Button Icon Distance', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-btns i' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-slider-btns svg' => 'margin-left: {{SIZE}}{{UNIT}};',
				],	
			]
		);
		
		$this->add_control(
			'slider_btn_icon_align',
			[
				'label' => esc_html__( 'SVG Vertical Align', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => esc_html__( 'Top', 'crt-manage' ),
					'middle' => esc_html__( 'Middle', 'crt-manage' ),
					'bottom' => esc_html__( 'Bottom', 'crt-manage' ),
					'text-top' => esc_html__( 'Text Top', 'crt-manage' ),
					'text-bottom' => esc_html__( 'Text Bottom', 'crt-manage' ),
				],
				'default' => 'text-bottom',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-btns svg' => 'vertical-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_title',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
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
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-title' => 'display:{{VALUE}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'slider_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'crt-manage' ),
					'h2' => esc_html__( 'H2', 'crt-manage' ),
					'h3' => esc_html__( 'H3', 'crt-manage' ),
					'h4' => esc_html__( 'H4', 'crt-manage' ),
					'h5' => esc_html__( 'H5', 'crt-manage' ),
					'h6' => esc_html__( 'H6', 'crt-manage' )
				],
				'default' => 'h2',
				'condition' => [
					'slider_title' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'slider_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'crt-manage' ),
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
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-sub-title' => 'display:{{VALUE}};',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'slider_sub_title_tag',
			[
				'label' => esc_html__( 'Sub Title HTML Tag', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'crt-manage' ),
					'h2' => esc_html__( 'H2', 'crt-manage' ),
					'h3' => esc_html__( 'H3', 'crt-manage' ),
					'h4' => esc_html__( 'H4', 'crt-manage' ),
					'h5' => esc_html__( 'H5', 'crt-manage' ),
					'h6' => esc_html__( 'H6', 'crt-manage' )
				],
				'default' => 'h3',
				'condition' => [
					'slider_sub_title' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'slider_description',
			[
				'label' => esc_html__( 'Description', 'crt-manage' ),
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
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-description' => 'display:{{VALUE}};',
				],
				'separator' => 'after',
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'slider_nav',
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
					'{{WRAPPER}} .crt-slider-arrow' => 'display:{{VALUE}} !important;',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control_slider_nav_hover();

		$this->add_control(
			'slider_nav_icon',
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
				'condition' => [
					'slider_nav' => 'yes',
				],
				'separator' => 'after',
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'slider_dots',
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
					'{{WRAPPER}} .crt-slider-dots' => 'display:{{VALUE}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control_slider_dots_layout();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'advanced-slider', 'slider_dots_layout', ['pro-vr'] );

		$this->add_control_slider_scroll_btn();

		$this->add_control(
			'slider_scroll_btn_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-angle-double-down',
					'library' => 'fa-solid',
				],
				'condition' => [
					'slider_scroll_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_scroll_btn_url',
			[
				'label' => esc_html__( 'Button URL', 'crt-manage' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
				'condition' => [
					'slider_scroll_btn' => 'yes',
				],
			]
		);

		$this->add_control_slider_autoplay();

		$this->add_control_slider_autoplay_duration();

		$this->add_control_slider_pause_on_hover();

		$this->add_control(
			'slider_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'slider_hide_video_content',
			[
				'label' => esc_html__( 'Hide Content', 'crt-manage' ),
				'description' => esc_html__( 'Hide the content of the slider when the video is playing', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before',
			]
		);

		$this->add_control_slider_effect();

		$this->add_control(
			'slider_effect_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,	
			]
		);

//		$this->add_control(
//			'slider_content_animation',
//			[
//				'label' => esc_html__( 'Content Animation', 'crt-manage' ),
//				'type' => 'crt-animations-alt',
//				'default' => 'none',
//				'condition' => [
//					'slider_effect' => 'fade',
//				],
//			]
//		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'advanced-slider', 'slider_content_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );
		
		$this->add_control(
			'slider_content_anim_size',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Animation Size', 'crt-manage' ),
				'default' => 'large',
				'options' => [
					'small' => esc_html__( 'Small', 'crt-manage' ),
					'medium' => esc_html__( 'Medium', 'crt-manage' ),
					'large' => esc_html__( 'Large', 'crt-manage' ),
				],
				'condition' => [
					'slider_content_animation!' => 'none',
					'slider_effect' => 'fade',
				],
			]
		);

		$this->add_control(
			'slider_content_anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-animation .crt-cv-outer' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'slider_content_animation!' => 'none',
					'slider_effect' => 'fade',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
//		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		
		// Styles
		// Section: Slider Content ---
		$this->start_controls_section(
			'crt__section_style_slider_content',
			[
				'label' => esc_html__( 'Slider Content', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'slider_content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_content_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-item' => 'border-color: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
            'slider_content_hr',
            [
                'label' => esc_html__( 'Horizontal Position', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
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
				'default' => 'center',
				'widescreen_default' => 'center',
				'laptop_default' => 'center',
				'tablet_extra_default' => 'center',
				'tablet_default' => 'center',
				'mobile_extra_default' => 'center',
				'mobile_default' => 'center',
				'selectors_dictionary' => [
					'left' => 'float: left',
					'center' => 'margin: 0 auto',
					'right' => 'float: right'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-content' => '{{VALUE}};',
				],
            ]
        );

		$this->add_responsive_control(
			'slider_content_vr',
			[
				'label' => esc_html__( 'Vertical Position', 'crt-manage' ),
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
                'selectors' => [
					'{{WRAPPER}} .crt-cv-inner' => 'vertical-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_content_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
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
				],
                'selectors' => [
					'{{WRAPPER}} .crt-slider-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_content_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 20,
						'max' => 100,
					],
					'px' => [
						'min' => 200,
						'max' => 1500,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 750,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_content_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'slider_content_border_type',
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
					'{{WRAPPER}} .crt-slider-item' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_content_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-item' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'slider_content_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'slider_content_border_radius',
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
					'{{WRAPPER}}  .crt-slider-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section
		
		// Styles
		// Section: Title ------------
		$this->start_controls_section(
			'crt__section_style_slider_title',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'slider_title_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-title *' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slider_title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-title *' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'slider_title_typography',
				'selector' => '{{WRAPPER}} .crt-slider-title *',
			]
		);

		$this->add_responsive_control(
			'slider_title_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-title *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_title_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-title *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Sub Title ------------
		$this->start_controls_section(
			'crt__section_style_slider_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'slider_sub_title_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-sub-title *' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slider_sub_title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-sub-title *' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'slider_sub_title_typography',
				'selector' => '{{WRAPPER}} .crt-slider-sub-title *',
			]
		);

		$this->add_responsive_control(
			'slider_sub_title_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-sub-title *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_sub_title_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-sub-title *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		
		// Styles
		// Section: Description ------------
		$this->start_controls_section(
			'crt__section_style_slider_description',
			[
				'label' => esc_html__( 'Description', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'slider_description_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,		
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-description p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slider_description_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-description p' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'slider_description_typography',
				'selector' => '{{WRAPPER}} .crt-slider-description p',
			]
		);

		$this->add_responsive_control(
			'slider_description_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-description p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_description_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 30,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-description p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		// Styles
		// Section: Button Primary ---
		$this->start_controls_section(
			'crt__section_style_btn_1',
			[
				'label' => esc_html__( 'Button Primary', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style_1' );

		$this->start_controls_tab(
			'tab_btn_normal_1',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg_color_1',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .crt-slider-primary-btn'
			]
		);

		$this->add_control(
			'btn_color_1',
			[
				'label'     => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-primary-btn' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-slider-primary-btn svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color_1',
			[
				'label'     => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-primary-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_box_shadow_1',
				'selector' => '{{WRAPPER}} .crt-slider-primary-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_1',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_hover_bg_color_1',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .crt-slider-primary-btn:hover'
			]
		);

		$this->add_control(
			'btn_hover_color_1',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-primary-btn:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-slider-primary-btn:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color_1',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-primary-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow_1',
				'selector' => '{{WRAPPER}} .crt-slider-primary-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_transition_duration_1',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-primary-btn' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-slider-primary-btn svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_typography_1_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography_1',
				'selector' => '{{WRAPPER}} .crt-slider-primary-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_icon_size_1',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-primary-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-slider-primary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'btn_padding_1',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 12,
					'right' => 25,
					'bottom' => 12,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_margin_1',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-primary-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'btn_border_type_1',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-primary-btn' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_width_1',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-primary-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type_1!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_radius_1',
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
					'{{WRAPPER}} .crt-slider-primary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		
		// Styles
		// Section: Button Secondary --------
		$this->start_controls_section(
			'crt__section_style_btn_2',
			[
				'label' => esc_html__( 'Button Secondary', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style_2' );

		$this->start_controls_tab(
			'tab_btn_normal_2',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg_color_2',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .crt-slider-secondary-btn'
			]
		);

		$this->add_control(
			'btn_color_2',
			[
				'label'     => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-secondary-btn' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-slider-secondary-btn svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color_2',
			[
				'label'     => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-secondary-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_box_shadow_2',
				'selector' => '{{WRAPPER}} .crt-slider-secondary-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_2',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_hover_bg_color_2',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .crt-slider-secondary-btn:hover'
			]
		);

		$this->add_control(
			'btn_hover_color_2',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-secondary-btn:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-slider-secondary-btn:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color_2',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-secondary-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow_2',
				'selector' => '{{WRAPPER}} .crt-slider-secondary-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_transition_duration_2',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-secondary-btn' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-slider-secondary-btn svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_typography_2_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography_2',
				'selector' => '{{WRAPPER}} .crt-slider-secondary-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_icon_size_2',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-secondary-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-slider-secondary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
			]
		);
			

		$this->add_responsive_control(
			'btn_padding_2',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 12,
					'right' => 25,
					'bottom' => 12,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-secondary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_margin_2',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-secondary-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'btn_border_type_2',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-secondary-btn' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_width_2',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-secondary-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type_2!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_radius_2',
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
					'{{WRAPPER}} .crt-slider-secondary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
		

		// Styles
		// Section: Scroll Button -----------
		$this->add_section_style_scroll_btn();

		// Styles
		// Section: Video Icon -------
		$this->start_controls_section(
			'crt__section_style_slider_video_btn',
			[
				'label' => esc_html__( 'Video Icon', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'slider_video_btn_size',
			[
				'label' => esc_html__( 'Video Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'medium',
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'small' => esc_html__( 'Small', 'crt-manage' ),
					'medium' => esc_html__( 'Medium', 'crt-manage' ),
					'large' => esc_html__( 'Large', 'crt-manage' ),
				],
				'frontend_available' => true,
				// 'prefix_class' => 'crt-slider-video-icon-size-%s',
			]
		);
	
		$this->add_control(
			'slider_video_btn_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-video-btn' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		
		// Styles
		// Section: Navigation ---
		$this->start_controls_section(
			'crt__section_style_slider_nav',
			[
				'label' => esc_html__( 'Navigation', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_slider_nav_style' );

		$this->start_controls_tab(
			'tab_slider_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'slider_nav_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255,255,255,0.8)',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-slider-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255,255,255,0.8)',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_slider_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'slider_nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-arrow:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-slider-arrow:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'slider_nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-arrow' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-slider-arrow svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_nav_font_size',
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
					'{{WRAPPER}} .crt-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_nav_size',
			[
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
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
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'slider_nav_border_type',
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
					'{{WRAPPER}} .crt-slider-arrow' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'slider_nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'slider_nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control_stack_slider_nav_position();

		$this->end_controls_section(); // End Controls Section


		// Styles
		// Section: Pagination ---
		$this->start_controls_section(
			'crt__section_style_slider_dots',
			[
				'label' => esc_html__( 'Pagination', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_slider_dots' );

		$this->start_controls_tab(
			'tab_slider_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'slider_dots_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.35)',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-dot' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_dots_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_slider_dots_active',
			[
				'label' => esc_html__( 'Active', 'crt-manage' ),
			]
		);

		$this->add_control(
			'slider_dots_active_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-slider-dots .slick-active .crt-slider-dot' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slider_dots_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-slider-dots .slick-active .crt-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'slider_dots_width',
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
					'{{WRAPPER}} .crt-slider-dot' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'slider_dots_height',
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
					'{{WRAPPER}} .crt-slider-dot' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'slider_dots_border_type',
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
					'{{WRAPPER}} .crt-slider-dot' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'slider_dots_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-slider-dot' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'slider_dots_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'slider_dots_border_radius',
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
					'{{WRAPPER}} .crt-slider-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_dots_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],							
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-slider-dots-horizontal .crt-slider-dot' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-slider-dots-vertical .crt-slider-dot' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_control_slider_dots_hr();
		
		$this->add_responsive_control(
			'slider_dots_vr',
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
					'{{WRAPPER}} .crt-slider-dots' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
		
	}

	public function load_slider_template( $id ) {
		if ( empty( $id ) ) {
			return '';
		}

		if ( defined('ICL_LANGUAGE_CODE') ) {
			$default_language_code = apply_filters('wpml_default_language', null);

			if ( ICL_LANGUAGE_CODE !== $default_language_code ) {
				$id = icl_object_id($id, 'elementor_library', false, ICL_LANGUAGE_CODE);
			}
		}

		$edit_link = '<span class="crt-template-edit-btn" data-permalink="'. esc_url(get_permalink( $id )) .'">Edit Template</span>';
		
		$type = get_post_meta(get_the_ID(), '_crt_template_type', true) || get_post_meta($id, '_elementor_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

	public function render_pro_element_slider_scroll_btn() {
		$settings = $this->get_settings_for_display();
		
        if ( isset($settings['slider_scroll_btn_url']) ) {
            $this->add_render_attribute( 'slider_scroll_btn', 'href',  esc_url( $settings['slider_scroll_btn_url']['url'] ));
        }

		if ( isset($settings['slider_scroll_btn_url']) && $settings['slider_scroll_btn_url']['is_external'] ) {
			$this->add_render_attribute( 'slider_scroll_btn', 'target', '_blank' );
		}

		if (  isset($settings['slider_scroll_btn_url']) && $settings['slider_scroll_btn_url']['nofollow'] ) {
			$this->add_render_attribute( 'slider_scroll_btn', 'nofollow', '' );
		}

		$slider_scroll_btn_attr = $this->get_render_attribute_string( 'slider_scroll_btn' );
        if($settings['slider_scroll_btn'] == 'yes') {
            echo '<a class="crt-slider-scroll-btn" '. $slider_scroll_btn_attr .'>';
                Icons_Manager::render_icon( $settings['slider_scroll_btn_icon'], [ 'class' => 'crt-scroll-animation', 'aria-hidden' => 'true' ] );
            echo '</a>';
        }
    }


	protected function render() {
		$settings = $this->get_settings_for_display();
		$slider_html = '';
		$item_count = 0;

		if ( empty( $settings['slider_items'] ) ) {
			return;
		}

		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];

		$settings_slider_title_tag = Utilities::validate_html_tags_wl( $settings['slider_title_tag'], 'h2', $tags_whitelist );
		$settings_slider_sub_title_tag = Utilities::validate_html_tags_wl( $settings['slider_sub_title_tag'], 'h3', $tags_whitelist );
		
		foreach ( $settings['slider_items'] as $key => $item ) {

			$item_slider_title_tag = Utilities::validate_html_tags_wl( $item['slider_title_tag'], 'h2', $tags_whitelist );
			$item_slider_sub_title_tag = Utilities::validate_html_tags_wl( $item['slider_sub_title_tag'], 'h3', $tags_whitelist );


			// Load Template
			if ( 'template' === $item['slider_content_type'] ) {

				$slider_html .= '<div class="crt-slider-item elementor-repeater-item-'. esc_attr($item['_id']) .'">';
			
					$slider_html .= $this->load_slider_template( $item['slider_select_template'] );

				$slider_html .= '</div>';

			// Or Build Custom
			} elseif( 'custom' === $item['slider_content_type'] ) {

				$item_type = $item['slider_item_link_type'];
				$item_url = isset($item['slider_item_bg_image_url']) ? $item['slider_item_bg_image_url']['url'] : '';
				$btn_url_1 = isset($item['slider_item_btn_url_1']) ? $item['slider_item_btn_url_1']['url'] : '';
				$btn_element_1 = 'div';
				$btn_attribute_1 = '';
				$icon_html_1 = esc_attr($item['slider_item_btn_text_1']);
				$btn_url_2 = isset($item['slider_item_btn_url_2']) ? $item['slider_item_btn_url_2']['url'] : '';
				$btn_element_2 = 'div';
				$btn_attribute_2 = '';
				$icon_html_2 = $item['slider_item_btn_text_2'];
				$ken_burn_class = '';

				if( isset($item['slider_item_bg_image']['source']) && $item['slider_item_bg_image']['source'] == 'url' ) {
					$item_bg_image_url = $item['slider_item_bg_image']['url'];
				} else {
					$item_bg_image_url = Group_Control_Image_Size::get_attachment_image_src( $item['slider_item_bg_image']['id'], 'slider_image_size', $settings );
				}

				$item_video_src = $item['slider_item_video_src'];
				$item_video_start = esc_attr($item['slider_item_video_start']);
				$item_video_end = esc_attr($item['slider_item_video_end']);

				if ( $item_type === 'video-media' ) {
					$item_video_src = $item['hosted_url']['url'];
				}

				if ( isset($item['slider_item_btn_icon_1']) && '' !== $item['slider_item_btn_icon_1']['value'] ) {
					ob_start();
					Icons_Manager::render_icon( $item['slider_item_btn_icon_1'], [ 'aria-hidden' => 'true' ] );
					$icon_html_1 .= ob_get_clean();
				}

				if ( isset($item['slider_item_btn_icon_2']) && '' !== $item['slider_item_btn_icon_2']['value'] ) { // me vpikrob es jobia ak - isset($item['slider_item_btn_icon_2']['value']
					ob_start();
					Icons_Manager::render_icon( $item['slider_item_btn_icon_2'], [ 'aria-hidden' => 'true' ] );
					$icon_html_2 .= ob_get_clean();	
				}

				// Slider Ken Burns Effect
				if ( $item['slider_item_bg_kenburns'] === 'yes' ) {
					$ken_burn_class = ' crt-ken-burns-'. $item['slider_item_bg_zoom'];
				}

				$this->add_render_attribute( 'slider_item'. $item_count, 'class', 'crt-slider-item elementor-repeater-item-'. esc_attr($item['_id']) );

				if ( strpos( $item_type, 'video' ) !== false && ! empty( $item_video_src ) ) {

					$this->add_render_attribute( 'slider_item'. $item_count, 'class', 'crt-slider-video-item' );
					$this->add_render_attribute( 'slider_item' . $item_count, 'data-video-autoplay', esc_attr($item['slider_item_video_autoplay']) );

					if ( $item_type === 'video-youtube' ) {

						
						preg_match('![?&]{1}v=([^&]+)!', $item_video_src, $item_video_id );

						if ( empty($item_bg_image_url) ) {
							$item_bg_image_url = 'https://i.ytimg.com/vi_webp/'. $item_video_id[1] .'/maxresdefault.webp';
						}
						
						if ( 'yes' === $item['slider_item_video_autoplay'] ) {
							// GOGA - if there is no way to autoplay with api we need mute=1 for this purpose
							// $item_video_src = 'https://www.youtube.com/embed/'. $item_video_id[1] .'?autoplay=1&enablejsapi=1';
							// $item_video_src = 'https://www.youtube.com/embed/'. $item_video_id[1] .'?autoplay=1&mute=1';
							$item_video_src = 'https://www.youtube.com/embed/'. $item_video_id[1] .'?autoplay=1';
							// $item_video_src = 'https://www.youtube.com/embed/'. $item_video_id[1] .'?controls=0&autoplay=1';
						} else {
							$item_video_src = 'https://www.youtube.com/embed/'. $item_video_id[1] . '?enablejsapi=1';
						}

						if ( $item['slider_item_video_mute'] === 'yes' ) {
							$item_video_src .= '&mute=1';
						}

						if ( $item['slider_item_video_controls'] !== 'yes') {
							$item_video_src .= '&controls=0';
						}

						if ( $item['slider_item_video_loop'] === 'yes' ) {
							$item_video_src .= '&loop=1&playlist='. $item_video_id[1];
						} else {
							if ( ! empty( $item_video_start ) ) {
								$item_video_src .= '&start='. $item_video_start;
							}

							if ( ! empty( $item_video_end ) ) {
								$item_video_src .= '&end='. $item_video_end;
							}
						}

					} elseif ( $item_type === 'video-vimeo' ) {
		          
		                $item_video_src = str_replace( 'vimeo.com', 'player.vimeo.com/video', $item_video_src );

						$item_video_src .= '?autoplay=1&title=0&portrait=0&byline=0';

						if ( $item['slider_item_video_mute'] === 'yes' ) {
							$item_video_src .= '&muted=1';
						}

						if ( $item['slider_item_video_controls'] !== 'yes') {
							$item_video_src .= '&controls=0';
						}

						if ( $item['slider_item_video_loop'] === 'yes' ) {
							$item_video_src .= '&loop=1';
						} elseif ( ! empty( $item_video_start ) ) {
							$item_video_src .= '&#t='. gmdate( 'H', $item_video_start ) .'h'. gmdate( 'i', $item_video_start ) .'m'. gmdate( 's', $item_video_start ) .'s';
						}
						
					} elseif ( $item_type === 'video-media' ) {
							$item_video_src = $item['hosted_url']['url'];
							$item_video_mute = $item['slider_item_video_mute'] === 'yes' ? 'muted' : '';
							$item_video_loop = $item['slider_item_video_loop'] === 'yes' ? 'loop' : '';
							$item_video_controls = $item['slider_item_video_controls'] === 'yes' ? 'controls' : '';

							$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-mute', $item_video_mute );
							$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-loop', $item_video_loop );
							$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-controls', $item_video_controls );
					}

					$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-src', esc_url($item_video_src) );
				}

				$slider_item_attribute = $this->get_render_attribute_string( 'slider_item'. $item_count );

				$slider_html .= '<div '. $slider_item_attribute .'>';

				if ( 'image' == $settings['slider_image_type'] ) {
					$slider_html .= '<img class="crt-slider-img" src="'. esc_url($item_bg_image_url) .'" />';
				} else {
					// Slider Background Image
					$slider_html .= '<div class="crt-slider-item-bg '. esc_attr($ken_burn_class) .'" style="background-image: url('. esc_url($item_bg_image_url) .')"></div>';
				}

				if ( 'slide_vertical' === $settings['slider_effect'] ) {
					$slider_amount = 1;
				} else {
					$slider_amount = +$settings['slider_amount'];
				}

				// Slider Overlay
				$slider_overlay_html = '';
				if ( $item['slider_item_overlay'] === 'yes' ) {
					if ( $slider_amount === 1 || $item['slider_item_blend_mode'] !== 'normal' ) {	
						$slider_html .= '<div class="crt-slider-item-overlay"></div>';
					} else {
						$slider_overlay_html = '<div class="crt-slider-item-overlay"></div>';
					}
				} 

				// Slider Content Attributes
				$this->add_render_attribute( 'slider_container'. $item_count, 'class', 'crt-cv-container' );	
				$this->add_render_attribute( 'slider_outer'. $item_count, 'class', 'crt-cv-outer' );

				if ( $settings['slider_effect'] != 'fade' ) {
					$settings['slider_content_animation'] = 'none';
				}

				if ( $settings['slider_content_animation'] !== 'none' ) {
					$slider_content_anim_size = esc_attr($settings['slider_content_anim_size']);
					$slider_content_animation = esc_attr($settings['slider_content_animation']);

					if ( $slider_amount === 1 ) {
						$this->add_render_attribute( 'slider_container'. $item_count, 'class', 'crt-slider-animation' );
						$this->add_render_attribute( 'slider_outer'. $item_count, 'class', 'crt-anim-transparency crt-anim-size-'. $slider_content_anim_size .' crt-overlay-'. $slider_content_animation );
					} elseif ( !empty( $item_bg_image_url ) && $item['slider_item_video_autoplay'] !== 'yes' ) {
						$this->add_render_attribute( 'slider_container'. $item_count, 'class', 'crt-slider-animation crt-animation-wrap' );
						$this->add_render_attribute( 'slider_outer'. $item_count, 'class', 'crt-anim-transparency crt-anim-size-'. $slider_content_anim_size .' crt-overlay-'. $slider_content_animation );
					}
				}

				// Slider Content
				$slider_html .= '<div '. $this->get_render_attribute_string( 'slider_container'. $item_count ) .'>';

					// Slider Link Type
					if ( ! empty( $item_url ) && $item_type === 'custom' ) {

						$this->add_render_attribute( 'slider_item_url'. $item_count, 'href', esc_url($item_url) );

						if ( $item['slider_item_bg_image_url']['is_external'] ) {
							$this->add_render_attribute( 'slider_item_url'. $item_count, 'target', '_blank' );
						}

						if ( $item['slider_item_bg_image_url']['nofollow'] ) {
							$this->add_render_attribute( 'slider_item_url'. $item_count, 'nofollow', '' );
						}

						$slider_html .= '<a class="crt-slider-item-url" '. $this->get_render_attribute_string( 'slider_item_url'. $item_count ) .'></a>';

					}

					$slider_html .= '<div '. $this->get_render_attribute_string( 'slider_outer'. $item_count ) .'>';
						$slider_html .= '<div class="crt-cv-inner">';
							
							// Slider Overlay
							$slider_html .= $slider_overlay_html;
							if ( 'yes' === $item['slider_show_content'] ) {

							$slider_html .= '<div class="crt-slider-content">';

								//  Video Icon
								if ( strpos( $item_type, 'video' ) !== false && $item['slider_item_video_autoplay'] !== 'yes' ) {
									$slider_html .= '<div class="crt-slider-video-btn">';
										$slider_html .= '<i class="fas fa-play"></i>';
									$slider_html .= '</div>';
								}

								//  Slider Title
								if ( $settings['slider_title'] === 'yes' && ! empty( $item['slider_item_title'] ) ) {
								$slider_html .= '<div class="crt-slider-title">';
									if ( '' !== $item_slider_title_tag ) {
										$slider_html .= '<' . $item_slider_title_tag . '>'. wp_kses_post($item['slider_item_title']) .'</'. $item_slider_title_tag .'>';
									} else {
										$slider_html .= '<' . $settings_slider_title_tag . '>'. wp_kses_post($item['slider_item_title']) .'</'. $settings_slider_title_tag .'>';
									}
								$slider_html .= '</div>';
								}	
								
								// Slider Sub Title
								if ( $settings['slider_sub_title'] === 'yes' && ! empty( $item['slider_item_sub_title'] ) ) {
								$slider_html .= '<div class="crt-slider-sub-title">';
									if ( '' !== $item_slider_sub_title_tag ) {
										$slider_html .= '<' . $item_slider_sub_title_tag . '>'. wp_kses_post($item['slider_item_sub_title']) .'</' . $item_slider_sub_title_tag . '>';
									} else {
										$slider_html .= '<' . $settings_slider_sub_title_tag . '>'. wp_kses_post($item['slider_item_sub_title']) .'</' . $settings_slider_sub_title_tag . '>';
									}
								$slider_html .= '</div>';
								}							

								// Slider Description
								if ( $settings['slider_description'] === 'yes' && ! empty( $item['slider_item_description'] ) ) {
									$slider_html .= '<div class="crt-slider-description">';	
										$slider_html .= '<p>'. wp_kses_post($item['slider_item_description']) .'</p>';
									$slider_html .= '</div>';
								}
								
								// Slider Button Secondary
								if ( ! empty( $btn_url_1 ) ) {
									
									$btn_element_1 = 'a';

									$this->add_render_attribute( 'primary_btn_url'. $item_count, 'href', esc_url($btn_url_1) );

									if ( $item['slider_item_btn_url_1']['is_external'] ) {
										$this->add_render_attribute( 'primary_btn_url'. $item_count, 'target', '_blank' );
									}

									if ( $item['slider_item_btn_url_1']['nofollow'] ) {
										$this->add_render_attribute( 'primary_btn_url'. $item_count, 'nofollow', '' );
									}

									$btn_attribute_1 = $this->get_render_attribute_string( 'primary_btn_url'. $item_count );
								}
				
								// Slider Button Secondary
								if ( ! empty( $btn_url_2 ) ) {
									
									$btn_element_2 = 'a';

									$this->add_render_attribute( 'secondary_btn_url'. $item_count, 'href', esc_url($btn_url_2) );

									if ( $item['slider_item_btn_url_2']['is_external'] ) {
										$this->add_render_attribute( 'secondary_btn_url'. $item_count, 'target', '_blank' );
									}

									if ( $item['slider_item_btn_url_2']['nofollow'] ) {
										$this->add_render_attribute( 'secondary_btn_url'. $item_count, 'nofollow', '' );
									}

									$btn_attribute_2 = $this->get_render_attribute_string( 'secondary_btn_url'. $item_count );
								}

								$slider_html .= '<div class="crt-slider-btns">';
								
								if ( $item['slider_item_btn_1'] === 'yes' && ! empty( $icon_html_1 ) ) {
									$slider_html .= '<'. $btn_element_1 .' class="crt-slider-primary-btn" '. $btn_attribute_1 .'>'. $icon_html_1 .'</'. $btn_element_1 .'>';
								}

								if ( $item['slider_item_btn_2'] === 'yes' && ! empty( $icon_html_2 ) ) {
									$slider_html .= '<'. $btn_element_2 .' class="crt-slider-secondary-btn" '. $btn_attribute_2 .'>'. $icon_html_2 .'</'. $btn_element_2 .'>';
								}
					
								$slider_html .= '</div>';
								
							$slider_html .= '</div>';
							} else {
								//  Video Icon
								if ( strpos( $item_type, 'video' ) !== false && $item['slider_item_video_autoplay'] !== 'yes' ) {
									$slider_html .= '<div class="crt-slider-video-btn">';
										$slider_html .= '<i class="fas fa-play"></i>';
									$slider_html .= '</div>';
								}
							}

							$slider_html .= '</div>';
						$slider_html .= '</div>';
					$slider_html .= '</div>';
				$slider_html .= '</div>';

				$item_count++;

			}
		}


		if ( 'sl_vl' === $settings['slider_effect'] ) {
			$settings['slider_effect'] = 'slide';
		}

		$slider_is_rtl = is_rtl();
		$slider_direction = $slider_is_rtl ? 'rtl' : 'ltr';

		$slider_video_btn_widescreen = isset($settings['slider_video_btn_size_widescreen']) && !empty($settings['slider_video_btn_size_widescreen']) ? $settings['slider_video_btn_size_widescreen'] : $settings['slider_video_btn_size'];
		$slider_video_btn_desktop = isset($settings['slider_video_btn_size']) && !empty($settings['slider_video_btn_size']) ? $settings['slider_video_btn_size'] : $slider_video_btn_widescreen;
		$slider_video_btn_laptop =  isset($settings['slider_video_btn_size_laptop']) && !empty($settings['slider_video_btn_size_laptop']) ? $settings['slider_video_btn_size_laptop'] : $slider_video_btn_desktop;
		$slider_video_btn_tablet_extra =  isset($settings['slider_video_btn_size_tablet_extra']) && !empty($settings['slider_video_btn_size_tablet_extra']) ? $settings['slider_video_btn_size_tablet_extra'] : $slider_video_btn_laptop;
		$slider_video_btn_tablet =  isset($settings['slider_video_btn_size_tablet']) && !empty($settings['slider_video_btn_size_tablet']) ? $settings['slider_video_btn_size_tablet'] : $slider_video_btn_tablet_extra;
		$slider_video_btn_mobile_extra =  isset($settings['slider_video_btn_size_mobile_extra']) && !empty($settings['slider_video_btn_size_mobile_extra']) ? $settings['slider_video_btn_size_mobile_extra'] : $slider_video_btn_tablet;
		$slider_video_btn_mobile =  isset($settings['slider_video_btn_size_mobile']) && !empty($settings['slider_video_btn_size_mobile']) ? $settings['slider_video_btn_size_mobile'] : $slider_video_btn_mobile_extra;

		$slider_options = [
			'rtl' => $slider_is_rtl,
			'infinite' => ( $settings['slider_loop'] === 'yes' ),
			'speed' => absint( ( floatval( $settings['slider_effect_duration'] ?: 1 ) ) * 1000 ),
			'arrows'=> true,
			'dots' 	=> true,
			'autoplay' => ( $settings['slider_autoplay'] === 'yes' ),
			'autoplaySpeed' => absint( ( floatval( $settings['slider_autoplay_duration'] ?: 1 ) ) * 1000 ),
			'pauseOnHover' => esc_attr($settings['slider_pause_on_hover']),
			'prevArrow' => '#crt-slider-prev-'. $this->get_id(),
			'nextArrow' => '#crt-slider-next-'. $this->get_id(),
			'vertical' => 'slide_vertical' === $settings['slider_effect'] ? true : false,
			'adaptiveHeight' => true
		];

		$this->add_render_attribute( 'advanced-slider-attribute', [
			'class' => 'crt-advanced-slider',
			'dir' => esc_attr( $slider_direction ),
			'data-slick' => wp_json_encode( $slider_options ),
			'data-hide-video-content' => esc_attr($settings['slider_hide_video_content']),
			'data-video-btn-size' => wp_json_encode(
				[
					'widescreen' => esc_attr($slider_video_btn_widescreen),
					'desktop' => esc_attr($slider_video_btn_desktop),
					'laptop' => esc_attr($slider_video_btn_laptop),
					'tablet_extra' => esc_attr($slider_video_btn_tablet_extra),
					'tablet' => esc_attr($slider_video_btn_tablet),
					'mobile_extra' => esc_attr($slider_video_btn_mobile_extra),
					'mobile' => esc_attr($slider_video_btn_mobile),
				]
			)
		] );

		?>

		<!-- Advanced Slider -->
		<div class="crt-advanced-slider-wrap">
			
			<div <?php echo $this->get_render_attribute_string( 'advanced-slider-attribute' ); ?> data-slide-effect="<?php echo esc_attr($settings['slider_effect']); ?>">
				<?php echo ''. $slider_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="crt-slider-controls">
				<div class="crt-slider-dots"></div>
			</div>

			<div class="crt-slider-arrow-container">
				<div class="crt-slider-prev-arrow crt-slider-arrow" id="<?php echo 'crt-slider-prev-'. esc_attr($this->get_id()); ?>">
					<?php echo Utilities::get_crt_icon( $settings['slider_nav_icon'], '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="crt-slider-next-arrow crt-slider-arrow" id="<?php echo 'crt-slider-next-'. esc_attr($this->get_id()); ?>">
					<?php echo Utilities::get_crt_icon( $settings['slider_nav_icon'], '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
			
			<?php $this->render_pro_element_slider_scroll_btn(); ?>

		</div>
		<?php
	}
}