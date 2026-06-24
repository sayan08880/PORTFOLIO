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
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CRT_Instagram_Feed extends Widget_Base {
	
	public function get_name() {
		return 'crt-instagram-feed';
	}

	public function get_title() {
		return esc_html__( 'Instagram Feed', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-instagram-post';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'instagram feed', 'social', 'grid' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_script_depends() {
		return [ 'swiper', 'crt-manage-isotope', 'crt-lightgallery', 'crt-instagram-feed' ];
	}

	public function get_style_depends() {
        return ['swiper', 'crt-animations-css', 'crt-loading-animations-css', 'crt-lightgallery-css', 'e-swiper' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com/contact';
    }

	public $reauthorization_needed;
	
	public function add_controls_group_limit() {
		$this->add_control(
			'limit',
			[
				'label' => esc_html__( 'Number of Posts', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 6,
				'max' => 6
			]
		);
				
		$this->add_control(
			'limit_mobile',
			[
				'label' => esc_html__( 'Number of Posts (Mobile)', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 2,
				'max' => 6
			]
		);
	}

	public function add_responsive_control_insta_feed_slides_to_show() {
		$this->add_responsive_control(
			'insta_feed_slides_to_show',
			[
				'label' => esc_html__( 'Slides To Show', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'separator' => 'before',
				'default' => 3,
				'widescreen_default' => 3,
				'laptop_default' => 3,
				'tablet_extra_default' => 2,
				'tablet_default' => 2,
				'mobile_extra_default' => 1,
				'mobile_default' => 1,
				'frontend_available' => true,
				'max' => 3,
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
				]
			]
		);
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

        $this->start_controls_tabs( 'tabs_insta_feed_sharing_style' );

        $this->start_controls_tab(
            'tab_insta_feed_sharing_normal',
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'sharing_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block a' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block a' => 'border-color: {{VALUE}}',
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block span[class*="crt-insta-feed-extra-text"]' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_insta_feed_sharing_hover',
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'sharing_bg_color_hr',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block a:hover' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block a:hover' => 'border-color: {{VALUE}}',
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block a' => 'transition-duration: {{VALUE}}s;',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'sharing_typography',
                'selector' => '{{WRAPPER}} .crt-insta-feed-item-sharing'
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block a' => 'border-style: {{VALUE}};',
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block a' => 'border-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .crt-insta-feed-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .crt-insta-feed-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block a' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block a' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .crt-insta-feed-item-sharing .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    public function add_control_insta_feed_elements_defaults() {
		return [
			[
				'element_select' => 'icon',
				'element_location' => 'below',
				'element_display' => 'inline',
				'element_align_hr' => 'right'
			],
			[
				'element_select' => 'date',
				'element_display' => 'inline',
				'element_location' => 'below',
				'element_align_hr' => 'left'
			],
			[
				'element_select' => 'caption',
				'element_location' => 'below',
			]
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
	
	public function add_control_overlay_color() {
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_color',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#3E00E542',
					]
				],
				'selector' => '{{WRAPPER}} .crt-insta-feed-media-hover-bg'
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
					// '{{WRAPPER}} {{CURRENT_ITEM}} .crt-insta-feed-media-hover-bg' => 'mix-blend-mode: {{VALUE}}',
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg' => 'mix-blend-mode: {{VALUE}}',
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
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg' => 'border-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'overlay_border_type!' => 'none',
				],
			]
		);
	}

	public function add_option_element_select() {
		return [
			'username' => esc_html__( 'Username', 'crt-manage' ),
			'caption' => esc_html__( 'Caption', 'crt-manage' ),
			'date' => esc_html__( 'Date', 'crt-manage' ),
			'icon' => esc_html__( 'Instagram Icon', 'crt-manage' ),
			'lightbox' => esc_html__( 'Lightbox', 'crt-manage' ),
            'sharing' => esc_html__( 'Sharing', 'crt-manage' )
		];
	}

    public function add_control_lightbox_popup_thumbnails() {
        $this->add_control(
            'lightbox_popup_thumbnails',
            [
                'label' => esc_html__( 'Show Thumbnails', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true',
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
                'label' => esc_html__( 'Show Sharing Button', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'return_value' => 'true',
            ]
        );
    }

	public function add_repeater_args_element_trim_text_by() {
        return [
            'word_count' => esc_html__( 'Word Count', 'crt-manage' ),
            'letter_count' => esc_html__( 'Letter Count', 'crt-manage' )
        ];
	}

	public function add_repeater_args_element_sharing_icon_1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_2() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_3() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_4() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_5() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_6() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_action() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_direction() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_tooltip() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_control_stack_insta_feed_slider_nav_position() {
		$this->add_control(
			'insta_feed_slider_nav_position',
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
			'insta_feed_slider_nav_position_default',
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
					'insta_feed_slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'insta_feed_slider_nav_outer_distance',
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
					'{{WRAPPER}}[class*="crt-grid-slider-nav-align-top"] .crt-swiper-nav-wrap' => 'top: {{SIZE}}px;',
					'{{WRAPPER}}[class*="crt-grid-slider-nav-align-bottom"] .crt-swiper-nav-wrap' => 'bottom: {{SIZE}}px;',
					'{{WRAPPER}}.crt-grid-slider-nav-align-top-left .crt-swiper-nav-wrap' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.crt-grid-slider-nav-align-bottom-left .crt-swiper-nav-wrap' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.crt-grid-slider-nav-align-top-right .crt-swiper-nav-wrap' => 'right: {{SIZE}}px;',
					'{{WRAPPER}}.crt-grid-slider-nav-align-bottom-right .crt-swiper-nav-wrap' => 'right: {{SIZE}}px;',
				],
				'condition' => [
					'insta_feed_slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'insta_feed_slider_nav_inner_distance',
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
					'{{WRAPPER}} .crt-swiper-nav-wrap .crt-swiper-button-prev' => 'margin-right: {{SIZE}}px;',
				],
				'condition' => [
					'insta_feed_slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'insta_feed_slider_nav_position_top',
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
					'{{WRAPPER}} .crt-swiper-button' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'insta_feed_slider_nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'insta_feed_slider_nav_position_left',
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
					'{{WRAPPER}} .crt-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'insta_feed_slider_nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'insta_feed_slider_nav_position_right',
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
					'{{WRAPPER}} .crt-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'insta_feed_slider_nav_position' => 'custom',
				],
			]
		);		
	}

	public function add_control_insta_feed_slider_dots_hr() {
		$this->add_responsive_control(
			'insta_feed_slider_dots_hr',
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
					'{{WRAPPER}} .swiper-pagination-bullets' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: API ------------
		$this->start_controls_section(
			'section_insta_api',
			[
				'label' => 'Intergration',
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

//		$this->add_control(
//			'instagram_access_token_authorize',
//			[
//				'type' => Controls_Manager::RAW_HTML,
//				// 'raw' => '<a class="crt-authorize-instagram" href="https://www.instagram.com/oauth/authorize?client_id=1551600955281199&redirect_uri=https://reastats.kinsta.cloud/token/social-network.php&scope=user_profile,user_media&response_type=code" target="popup">'. esc_html__( 'Authorize Instagram','crt-manage' ) .'</a>',
//				'raw' => '<a class="crt-authorize-instagram" href="https://www.youtube.com/watch?v=PP3V97nvNRk&t=52s" target="popup">'. esc_html__( 'Get Access Token','crt-manage' ) .'</a>',
//				// 'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
//			]
//		);

		$this->add_control(
			'instagram_access_token',
			[
				'label' => esc_html__( 'Access Token', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true
			]
		);

		$this->add_control(
			'instagram_access_token_expires_in',
			[
				'label' => esc_html__( 'Expiry Date', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'description' => esc_html__('Please Note: You just need to enter this once, later it will update automatically', 'crt-manage')
			]
		);

		$this->add_control(
			'cache_timeout_select',
			[
				'label'   => esc_html__( 'Cache Timeout', 'crt-manage' ),
				'description' => esc_html__('Determine how often you want the feed to be updated', 'crt-manage'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'hour',
				'options' => [
					'none'   => esc_html__( 'None', 'crt-manage' ),
					'minute' => esc_html__( 'Minute', 'crt-manage' ),
					'hour'   => esc_html__( 'Hour', 'crt-manage' ),
					'day'    => esc_html__( 'Day', 'crt-manage' ),
					'week'   => esc_html__( 'Week', 'crt-manage' ),
				],
			]
		);

		$this->add_controls_group_limit();


        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Layout ------------
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'insta_layout_select',
			[
				'label' => esc_html__('Select Layout', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'label_block' => false,
				'default' => 'layout-grid',
				'prefix_class' => 'crt-insta-feed-'	,
				'render_type' => 'template',
				'separator' => 'before',
				'options' => [
					'layout-grid'     => esc_html__('Grid', 'crt-manage'),
					'masonry' => esc_html__('Masonry', 'crt-manage'),
					'layout-list'     => esc_html__('List Style', 'crt-manage'),
					'layout-full-width'     => esc_html__('Full Width', 'crt-manage'),
					'layout-carousel' => esc_html__('Slider/Carousel', 'crt-manage'),
				],
				'selectors' => [
					'{{WRAPPER}} .crt-layout-full-width' => 'grid-template-columns: repeat({{limit.VALUE}}, minmax(0, 1fr)); grid-template-rows: 1;',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .crt-layout-full-width' => 'grid-template-columns: repeat({{limit_mobile.VALUE}}, minmax(0, 1fr)); grid-template-rows: 1;',
				]
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1' => esc_html__('One', 'crt-manage'),
					'2'    => esc_html__('Two', 'crt-manage'),
					'3'    => esc_html__('Three', 'crt-manage'),
					'4'    => esc_html__('Four', 'crt-manage'),
					'5'    => esc_html__('Five', 'crt-manage'),
					'6'    => esc_html__('Six', 'crt-manage'),
					'7'    => esc_html__('Seven', 'crt-manage'),
					'8'    => esc_html__('Eight', 'crt-manage'),
					'9'    => esc_html__('Nine', 'crt-manage'),
					'10'    => esc_html__('Ten', 'crt-manage'),
					'11'    => esc_html__('Eleven', 'crt-manage'),
					'12'    => esc_html__('Twelve', 'crt-manage'),
				],
				'selectors' => [
					'{{WRAPPER}} .crt-layout-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr))',
					'{{WRAPPER}} .crt-layout-list' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr))',
					// '{{WRAPPER}} .crt-masonry' => 'column-count: {{VALUE}}',
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'condition' => [
					'insta_layout_select' => ['layout-grid', 'layout-list', 'masonry']
				],
			]
		);

		$this->add_responsive_control(
			'gutter',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					// '{{WRAPPER}} .crt-instagram-feed-cont .crt-masonry' => 'column-gap: {{SIZE}}px;',
					'{{WRAPPER}} .crt-instagram-feed-cont .crt-layout-grid' => 'column-gap: {{SIZE}}px;',
					'{{WRAPPER}} .crt-instagram-feed-cont .crt-layout-list' => 'column-gap: {{SIZE}}px;',
					'{{WRAPPER}} .crt-instagram-feed-cont .crt-layout-full-width' => 'column-gap: {{SIZE}}px;'
				],
				'condition' => [
					'insta_layout_select!' => 'layout-carousel'
				],
			]
		);

		$this->add_responsive_control(
			'distance_bottom',
			[
				'label' => esc_html__( 'Vertical Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					// '{{WRAPPER}} .crt-masonry .crt-insta-feed-content-wrap' => 'margin-bottom: {{SIZE}}px',
					'{{WRAPPER}}.crt-insta-feed-layout-grid .crt-instagram-feed' => 'row-gap: {{SIZE}}px',
					'{{WRAPPER}}.crt-insta-feed-layout-list .crt-instagram-feed' => 'row-gap: {{SIZE}}px',
					'{{WRAPPER}}.crt-insta-feed-layout-full-width .crt-instagram-feed' => 'row-gap: {{SIZE}}px',
				],
				'condition' => [
					'insta_layout_select' => ['layout-grid', 'layout-list', 'masonry']
				]
			]
		);

		// Media
		$this->add_control(
			'layout_list_media_section',
			[
				'label' => esc_html__( 'Media', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'insta_layout_select' => 'layout-list',
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
				'prefix_class' => 'crt-insta-feed-list-',
				'condition' => [
					'insta_layout_select' => 'layout-list'
				]
			]
		);

		$this->add_responsive_control(
			'layout_list_media_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
					'size' => 30,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-layout-list .crt-insta-feed-media-wrap' => 'width: {{SIZE}}{{UNIT}};',
					'body[data-elementor-device-mode=desktop] {{WRAPPER}} .crt-layout-list .crt-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance.SIZE}}{{layout_list_media_distance.UNIT}});',
					'body[data-elementor-device-mode=widescreen] {{WRAPPER}} .crt-layout-list .crt-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_widescreen.SIZE}}{{layout_list_media_distance_widescreen.UNIT}});',
					'body[data-elementor-device-mode=laptop] {{WRAPPER}} .crt-layout-list .crt-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_laptop.SIZE}}{{layout_list_media_distance_laptop.UNIT}});',
					'body[data-elementor-device-mode=tablet_extra] {{WRAPPER}} .crt-layout-list .crt-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_tablet_extra.SIZE}}{{layout_list_media_distance_tablet_extra.UNIT}});',
					'body[data-elementor-device-mode=tablet] {{WRAPPER}} .crt-layout-list .crt-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_tablet.SIZE}}{{layout_list_media_distance_tablet.UNIT}});',
					'body[data-elementor-device-mode=mobile_extra] {{WRAPPER}} .crt-layout-list .crt-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_mobile_extra.SIZE}}{{layout_list_media_distance_mobile_extra.UNIT}});',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .crt-layout-list .crt-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_mobile.SIZE}}{{layout_list_media_distance_mobile.UNIT}});'
				],
				'condition' => [
					'insta_layout_select' => 'layout-list',
				]
			]
		);

		$this->add_responsive_control(
			'layout_list_media_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-list-left .crt-layout-list .crt-insta-feed-media-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-insta-feed-list-right .crt-layout-list .crt-insta-feed-media-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-insta-feed-list-zigzag .crt-layout-list .crt-insta-feed-content-wrap:nth-child(odd) .crt-insta-feed-media-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-insta-feed-list-zigzag .crt-layout-list .crt-insta-feed-content-wrap:nth-child(even) .crt-insta-feed-media-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'insta_layout_select' => 'layout-list',
				]
			]
		);

		// $this->add_responsive_control(
		// 	'insta_feed_slides_to_show',
		// 	[
		// 		'label' => esc_html__( 'Slides To Show', 'crt-manage' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'default' => [
		// 			'size' => 3,
		// 		],
		// 		'widescreen_default' => [
		// 			'size' => 3,
		// 		],
		// 		'laptop_default' => [
		// 			'size' => 3,
		// 		],
		// 		'tablet_extra_default' => [
		// 			'size' => 2,
		// 		],
		// 		'tablet_default' => [
		// 			'size' => 2,
		// 		],
		// 		'mobile_extra_default' => [
		// 			'size' => 1,
		// 		],
		// 		'mobile_default' => [
		// 			'size' => 1,
		// 		],
		// 		// 'range' => [
		// 		// 	'px' => [
		// 		// 		'min' => 0,
		// 		// 		'max' => 100,
		// 		// 	],
		// 		// ],
		// 		'condition' => [
		// 			'insta_layout_select' => 'layout-carousel',
		// 		]
		// 	]
		// );

		$this->add_responsive_control_insta_feed_slides_to_show();

				
		$this->add_responsive_control(
			'insta_feed_space_between',
			[
				'label' => __( 'Gutter', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 5,
				'widescreen_default' => 5,
				'laptop_default' => 5,
				'tablet_extra_default' => 5,
				'tablet_default' => 5,
				'mobile_extra_default' => 5,
				'mobile_default' => 5,
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
				]
			]
		);
				
		$this->add_control(
			'insta_feed_speed',
			[
				'label' => __( 'Speed', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 500,
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
				]
			]
		);

		$this->add_control (
			'force_square_images',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Force Square Images', 'crt-manage' ),
				'render_type' => 'template',
				'separator' => 'before',
				'prefix_class' => 'crt-if-square-images-',
				'condition' => [
					'insta_layout_select!' => 'masonry'
				]
			]
		);

		$this->add_control (
			'enable_cs_nav',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Navigation', 'crt-manage' ),
				'render_type' => 'template',
				'separator' => 'before',
				'default' => 'yes',
				'condition' => [
					'insta_layout_select' => 'layout-carousel'
				]
			]
		);

		$this->add_control(
			'cs_nav_arrows',
			[
				'label' => esc_html__( 'Navigation Icon', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle',
				'options' => [
					'fas fa-angle' => esc_html__( 'Angle', 'crt-manage' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'crt-manage' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'crt-manage' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'crt-manage' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'crt-manage' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'crt-manage' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'crt-manage' ),
				],
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
					'enable_cs_nav' => 'yes'
				]
			]
		);

		$this->add_control (
			'enable_cs_pag',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Pagination', 'crt-manage' ),
				'render_type' => 'template',
				'separator' => 'before',
				'default' => 'yes',
				'condition' => [
					'insta_layout_select' => 'layout-carousel'
				]
			]
		);

		$this->add_control(
			'cs_pag_type',
			[
				'label' => esc_html__( 'Pagination Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bullets',
				'options' => [
					'bullets' => esc_html__( 'Bullets', 'crt-manage' ),
					'fraction' => esc_html__( 'Fraction', 'crt-manage' ),
					'progressbar' => esc_html__( 'Progressbar', 'crt-manage' ),
				],
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
					'enable_cs_pag' => 'yes',
				]
			]
		);

		$this->add_control (
			'enable_insta_feed_slider_autoplay',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Autoplay', 'crt-manage' ),
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'insta_layout_select' => 'layout-carousel'
				]
			]
		);
				
		$this->add_control(
			'insta_feed_delay',
			[
				'label' => __( 'Delay', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 1000,
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
					'enable_insta_feed_slider_autoplay' => 'yes'
				]
			]
		);

		$this->add_control (
			'enable_insta_feed_slider_loop',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Loop', 'crt-manage' ),
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'insta_layout_select' => 'layout-carousel'
				]
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => esc_html__( 'Show Pagination', 'crt-manage' ),
				'description' => esc_html__('Please note that Pagination doesn\'t work in editor', 'crt-manage'),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'insta_layout_select!' => ['layout-carousel', 'layout-full-width'],
				]
			]
		);

		$this->add_control(
			'show_instagram_follow_button',
			[
				'label' => esc_html__( 'Show Follow Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'return_value' => 'yes',
				'label_block' => false
			]
		);

		$this->add_control(
			'buttons_alignment',
			[
				'label' => esc_html__( 'Buttons Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
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
					]
				],
				'selectors' => [
					'{{WRAPPER}} .crt-isnta-feed-buttons-wrap' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .crt-instagram-follow-btn-wrap' => 'text-align: {{VALUE}};'
				],
				'separator' => 'before',
				'prefix_class' => 'crt-grid-pagination-',
				'render_type' => 'template',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'show_instagram_follow_button',
							'operator' => '==',
							'value' => 'yes'
						],
						[
							'name' => 'show_pagination',
							'operator' => '==',
							'value' => 'yes'
						]
					]
				]
			]
		);

		$this->add_control(
			'open_in_new_tab',
			[
				'label' => esc_html__( 'Open Links In New Tab', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'default' => 'yes'
			]
		);

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Elements ---------
		$this->start_controls_section(
			'section_feed_elements',
			[
				'label' => esc_html__( 'Elements', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$this_select = $this->add_option_element_select();

		$repeater->add_control(
			'element_select',
			[
				'label' => esc_html__( 'Select Element', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this_select,
				'default' => 'caption',
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_hide_year',
			[
				'label' => esc_html__( 'Hide Year', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'after',
				'condition' => [
					'element_select' => [ 'date' ],
				],
			]
		);

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
			'element_username_tag',
			[
				'label' => esc_html__( 'Username HTML Tag', 'crt-manage' ),
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
					'element_select' => 'username',
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
					'element_select' => [ 'caption' ],
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
					'element_select' => [ 'caption' ],
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
					'element_select' => [ 'caption' ],
					'element_trim_text_by' => 'letter_count'
				]
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

		// $repeater->add_control(
		// 	'element_comments_text_1',
		// 	[
		// 		'label' => esc_html__( 'No Comments', 'crt-manage' ),
		// 		'type' => Controls_Manager::TEXT,
		// 		'default' => 'No Comments',
		// 		'condition' => [
		// 			'element_select' => [ 'comments' ],
		// 		]
		// 	]
		// );

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

		// $repeater->add_control( 'element_like_icon', $this->add_repeater_args_element_like_icon() );

		// $repeater->add_control( 'element_like_show_count', $this->add_repeater_args_element_like_show_count() );

		// $repeater->add_control( 'element_like_text', $this->add_repeater_args_element_like_text() );

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
						'caption',
						'read-more',
						'separator',
						'username',
						'icon'
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
						'caption',
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
						'caption',
						'separator',
						'likes',
						'sharing',
						'icon',
						'username'
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
						'caption',
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
			'insta_feed_elements',
			[
				'label' => esc_html__( 'Feed Elements', 'crt-manage' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => $this->add_control_insta_feed_elements_defaults(),
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
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
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
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'overlay_post_link',
			[
				'label' => esc_html__( 'Link to Instagram Post', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg' => 'transition-duration: {{VALUE}}s;'
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
					'{{WRAPPER}} .crt-animation-wrap:hover .crt-insta-feed-media-hover-bg' => 'transition-delay: {{VALUE}}s;'
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
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'instagram-feed', 'overlay_animation_timing', Utilities::crt_animation_timing_pro_conditions());

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

		// $this->add_control_overlay_animation_divider();

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

		$this->add_control_image_effects();

		// Upgrade to Pro Notice

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
					'{{WRAPPER}} .crt-insta-feed-media-wrap img' => 'transition-duration: {{VALUE}}s;'
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
					'{{WRAPPER}} .crt-insta-feed-media-wrap:hover img' => 'transition-delay: {{VALUE}}s;'
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
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'instagram-feed', 'image_effects_animation_timing', Utilities::crt_animation_timing_pro_conditions());

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
		// Section: Pagination -------
		$this->start_controls_section(
			'section_insta_feed_follow_button',
			[
				'label' => esc_html__( 'Follow Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'show_instagram_follow_button' => 'yes'
				],
			]
		);

		$this->add_control(
			'follow_button_location',
			[
				'label' => esc_html__( 'Button Location', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'prefix_class' => 'crt-if-cfb-',
				'default' => 'bottom',
				'render_type' => 'template',
				'options' => [
					'top' => esc_html__( 'Top', 'crt-manage' ),
					'center' => esc_html__( 'Center', 'crt-manage' ),
					'bottom' => esc_html__( 'Bottom', 'crt-manage' )
				],
				'condition' => [
					'show_instagram_follow_button' => 'yes',
				]
			]
		);

		$this->add_control(
			'instagram_follow_text',
			[
				'label' => esc_html__( 'Button Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Follow on Instagram',
				'label_block' => true,
				'condition' => [
					'show_instagram_follow_button' => 'yes',
				]
			]
		);

		$this->add_control(
			'instagram_follow_link',
			[
				'label' => esc_html__( 'Button Link', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'crt-manage' ),
				'default' => [
					'url' => 'https://www.instagram.com/',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
				'label_block' => true,
				'condition' => [
					'show_instagram_follow_button' => 'yes',
				]
			]
		);

		$this->add_control(
			'instagram_follow_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				// 'separator' => 'before',
				'default' => [
					'value' => 'fab fa-instagram',
					'library' => 'brands'
				]
			]
		);

		$this->end_controls_section();

		// Tab: Content ==============
		// Section: Pagination -------
		$this->start_controls_section(
			'section_insta_feed_pagination',
			[
				'label' => esc_html__( 'Pagination', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'insta_layout_select!' => 'layout-carousel',
					'show_pagination' => 'yes',
				],
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
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Tab: Styles ===============
		// Section: Feed -----------
		$this->start_controls_section(
			'section_style_feed',
			[
				'label' => esc_html__( 'Feed', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'feed_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					// '{{WRAPPER}} .crt-insta-feed-content-wrap' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-insta-feed-item-above-content' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-insta-feed-item-below-content' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'feed_item_shadow',
				'selector' => '{{WRAPPER}} .crt-insta-feed-content-wrap',
				// 'fields_options' => [
                //     'box_shadow_type' =>
                //         [ 
                //             'default' =>'yes' 
                //         ],
                //     'box_shadow' => [
                //         'default' =>
                //             [
                //                 'horizontal' => 0,
                //                 'vertical' => 0,
                //                 'blur' => 3,
                //                 'spread' => 0,
                //                 'color' => '#22222266'
                //             ]
                //     ]
				// ]
			]
		);

		$this->add_control(
			'feed_item_border_type',
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
					// '{{WRAPPER}} .crt-insta-feed-content-wrap' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-insta-feed-item-above-content' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-insta-feed-item-below-content' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'feed_item_border_width',
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
					// '{{WRAPPER}} .crt-insta-feed-content-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-insta-feed-item-above-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-insta-feed-item-below-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'feed_item_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'feed_item_radius',
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
					// '{{WRAPPER}} .crt-insta-feed-content-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-insta-feed-item-above-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-insta-feed-item-below-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'feed_item_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-above-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-insta-feed-item-below-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'feed_margin',
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
					'{{WRAPPER}} .crt-instagram-feed' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'render_type' => 'template'
			]
		);

        $this->end_controls_section();

		// Styles ====================
		// Section: Grid Media -------
		$this->start_controls_section(
			'section_style_feed_media',
			[
				'label' => esc_html__( 'Feed Media', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'insta_media_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-image-wrap' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'insta_media_border_type',
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
					'{{WRAPPER}} .crt-insta-feed-image-wrap' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'insta_media_border_width',
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
					'{{WRAPPER}} .crt-insta-feed-image-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'insta_media_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'insta_media_radius',
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
					'{{WRAPPER}} .crt-insta-feed-image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_control(
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
					'{{WRAPPER}} .crt-insta-feed-media-hover-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_username',
			[
				'label' => esc_html__( 'Username', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_username_style' );

		$this->start_controls_tab(
			'tab_grid_username_normal',
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
					'{{WRAPPER}} .crt-insta-feed-item-username .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-username .inner-block a' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-insta-feed-item-username .inner-block a' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_username_hover',
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
					'{{WRAPPER}} .crt-insta-feed-item-username .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-username .inner-block a:hover' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-insta-feed-item-username .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		// $this->add_control_username_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// $this->add_control_username_pointer();

		// $this->add_control_username_pointer_height();

		// $this->add_control_username_pointer_animation();

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
					'{{WRAPPER}} .crt-insta-feed-item-username .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-insta-feed-item-username .crt-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-insta-feed-item-username .crt-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .crt-insta-feed-item-username a',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
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
					'{{WRAPPER}} .crt-insta-feed-item-username .inner-block a' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-username .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-username .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-username .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Caption ----------
		$this->start_controls_section(
			'section_style_caption',
			[
				'label' => esc_html__( 'Caption', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'caption_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6A6A6A',
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-caption .inner-block' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-insta-feed-item-caption .inner-block p' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'caption_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-caption .inner-block' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'caption_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-caption .inner-block' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_typography',
				'selector' => '{{WRAPPER}} .crt-insta-feed-item-caption, {{WRAPPER}} .crt-insta-feed-item-caption p, {{WRAPPER}} .crt-insta-feed-item-caption figcaption',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'caption_justify',
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
					'{{WRAPPER}} .crt-insta-feed-item-caption .inner-block' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'caption_width',
			[
				'label' => esc_html__( 'Caption Width', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-insta-feed-item-caption .inner-block' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption_border_type',
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
					'{{WRAPPER}} .crt-insta-feed-item-caption .inner-block' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption_border_width',
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
					'{{WRAPPER}} .crt-insta-feed-item-caption .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'caption_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'caption_padding',
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
					'{{WRAPPER}} .crt-insta-feed-item-caption .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'caption_margin',
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
					'{{WRAPPER}} .crt-insta-feed-item-caption .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-date .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-date .inner-block > span' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-insta-feed-item-date .inner-block > span' => 'border-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-insta-feed-item-date .inner-block span[class*="crt-insta-feed-extra-text"]' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-insta-feed-item-date .inner-block i[class*="crt-insta-feed-extra-icon"]' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'selector' => '{{WRAPPER}} .crt-insta-feed-item-date'
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
					'{{WRAPPER}} .crt-insta-feed-item-date .inner-block > span' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-date .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-date .crt-insta-feed-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-insta-feed-item-date .crt-insta-feed-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-date .crt-insta-feed-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-insta-feed-item-date .crt-insta-feed-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-date .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-date .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles =========================
		// Section: Sharing ---------------
		$this->add_section_style_sharing();

		// Styles ====================
		// Section: Icon ---------
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_insta_feed_icon_style' );

		$this->start_controls_tab(
			'tab_insta_feed_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-icon .inner-block > a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-icon .inner-block > a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-icon .inner-block > a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'icon_shadow',
				'selector' => '{{WRAPPER}} .crt-insta-feed-item-icon i',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_insta_feed_icon_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'icon_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-icon .inner-block > a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-icon .inner-block > a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-icon .inner-block > a:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'icon_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'icon_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-icon .inner-block > a' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'icon_typography',
				'selector' => '{{WRAPPER}} .crt-insta-feed-item-icon'
			]
		);

		$this->add_control(
			'icon_border_type',
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
					'{{WRAPPER}} .crt-insta-feed-item-icon .inner-block > a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_border_width',
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
					'{{WRAPPER}} .crt-insta-feed-item-icon .inner-block > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'icon_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'icon_text_spacing',
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
					'{{WRAPPER}} .crt-insta-feed-item-icon .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-insta-feed-item-icon .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_padding',
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
					'{{WRAPPER}} .crt-insta-feed-item-icon .inner-block > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_margin',
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
					'{{WRAPPER}} .crt-insta-feed-item-icon .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_radius',
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
					'{{WRAPPER}} .crt-insta-feed-item-icon .inner-block > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

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

		$this->start_controls_tabs( 'tabs_insta_feed_lightbox_style' );

		$this->start_controls_tab(
			'tab_insta_feed_lightbox_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#D60EC8',
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .inner-block > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .inner-block > span' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .inner-block > span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'lightbox_shadow',
				'selector' => '{{WRAPPER}} .crt-insta-feed-item-lightbox i',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_insta_feed_lightbox_hover',
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
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .inner-block > span:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .inner-block > span:hover' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .inner-block > span:hover' => 'border-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .inner-block > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'lightbox_typography',
				'selector' => '{{WRAPPER}} .crt-insta-feed-item-lightbox'
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
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .inner-block > span' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .crt-insta-feed-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .crt-insta-feed-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-insta-feed-item-lightbox .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Tab: Styles ===============
		// Section: Button -----------
		$this->start_controls_section(
			'section_style_follow_button',
			[
				'label' => esc_html__( 'Follow Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_colors' );

		$this->start_controls_tab(
			'tab_button_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-instagram-follow-btn' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-instagram-follow-btn' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-instagram-follow-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .crt-instagram-follow-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-instagram-follow-btn:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .crt-instagram-follow-btn:hover' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-instagram-follow-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .crt-instagram-follow-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .crt-instagram-follow-btn',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_transition',
			[
				'label' => esc_html__( 'Transition', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-instagram-follow-btn' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
				],
				'separator' => 'after'
			]
		);

		$this->add_responsive_control(
			'button_distance_from_feed',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 5,
				],				
				'mobile_default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-if-cfb-bottom .crt-instagram-follow-btn-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'follow_button_location' => 'bottom',
					'show_pagination' => 'yes'
				]
			]
		);

		// $this->add_responsive_control(
		// 	'button_top_distance_from_feed',
		// 	[
		// 		'label' => esc_html__( 'Top Distance', 'crt-manage' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 100,
		// 			],
		// 		],				
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 25,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}}.crt-if-cfb-bottom .crt-instagram-follow-btn-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
		// 		],
		// 		'separator' => 'before',
		// 		'condition' => [
		// 			'follow_button_location' => 'bottom',
		// 			'show_pagination!' => 'yes'
		// 		]
		// 	]
		// );

		$this->add_responsive_control(
			'button_icond_distance',
			[
				'label' => esc_html__( 'Icon Distance', 'crt-manage' ),
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
					'size' => 4,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-instagram-follow-btn i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-instagram-follow-btn svg' => 'margin-right: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'button_padding',
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
					'{{WRAPPER}} .crt-instagram-follow-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_type',
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
					'{{WRAPPER}} .crt-instagram-follow-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-instagram-follow-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
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
					'{{WRAPPER}} .crt-instagram-follow-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Slider Navigation -------
		$this->start_controls_section(
			'section_style_slider_navigation',
			[
				'label' => esc_html__( 'Slider Navigation', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
					'enable_cs_nav' => 'yes'
				],
			]
		);

		$this->start_controls_tabs('cs_nav_tabs');

		$this->start_controls_tab(
			'cs_nav_tab_normal',
			[
				'label' => __( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'cs_nav_icon_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cs_nav_icon_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'cs_nav_icon_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_navigation',
				'label' => __( 'Box Shadow', 'crt-manage' ),
				'selector' => '{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev, {{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next',
			]
		);

		$this->add_control(
			'navigation_transition',
			[
				'label' => esc_html__( 'Transition', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;'
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'cs_nav_tab_hover',
			[
				'label' => __( 'Hover', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'cs_nav_icon_color_hover',
			[
				'label'  => esc_html__( 'Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next:hover svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cs_nav_icon_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#423EC0',
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'cs_nav_icon_border_color_hover',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_navigation_hover',
				'label' => __( 'Box Shadow', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .flipster__button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'cs_nav_icon_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],			
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);
		
		$this->add_responsive_control(
			'cs_nav_icon_bg_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],			
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'cs_nav_border',
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
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev' => 'border-style: {{VALUE}};',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'cs_nav_border_width',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'cs_nav_border!' => 'none'
				]
			]
		);
		
		$this->add_control(
			'icon_border_radius',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .crt-swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);
	
		$this->add_control_stack_insta_feed_slider_nav_position();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_insta_feed_slider_pag',
			[
                'label' => esc_html__('Slider Pagination', 'crt-manage'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
					'enable_cs_pag' => 'yes'
				]
            ]
		);

		$this->add_control(
			'cs_pag_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .swiper-pagination-fraction' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .swiper-pagination-progressbar' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'cs_pag_bg_color',
			[
				'label'  => esc_html__( 'Bar Background', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#00000040',
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .swiper-pagination-progressbar' => 'background-color: {{VALUE}};'
				]
			]
		);
		
		$this->add_responsive_control(
			'cs_pag_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],			
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'cs_pag_fraction_typography',
				'label' => __( 'Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}}.crt-insta-feed-layout-carousel .swiper-pagination-fraction',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'cs_pag_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 6,
					'bottom' => 0,
					'left' => 6,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-insta-feed-layout-carousel .swiper-pagination-bullet' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		
		$this->add_control_insta_feed_slider_dots_hr();

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
					'insta_layout_select!' => 'layout-carousel',
					'show_pagination' => 'yes',
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
					'{{WRAPPER}} .crt-grid-pagination button' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-grid-pagination button' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-grid-pagination button' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow',
				'selector' => '{{WRAPPER}} .crt-grid-pagination button, {{WRAPPER}} .crt-grid-pagination > div > span',
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
					'{{WRAPPER}} .crt-grid-pagination button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination button:hover svg' => 'fill: {{VALUE}}',
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
					'{{WRAPPER}} .crt-grid-pagination button:hover' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-grid-pagination button:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow_hr',
				'selector' => '{{WRAPPER}} .crt-grid-pagination button:hover, {{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover',
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
					'{{WRAPPER}} .crt-grid-pagination button' => 'transition-duration: {{VALUE}}s',
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
				'selector' => '{{WRAPPER}} .crt-grid-pagination, {{WRAPPER}} .crt-grid-pagination button'
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
					'{{WRAPPER}} .crt-grid-pagination button' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .crt-grid-pagination button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'pagination_border_type!' => 'none',
				],
			]
		);

		// $this->add_responsive_control(
		// 	'pagination_distance_from_feed',
		// 	[
		// 		'label' => esc_html__( 'Top Distance', 'crt-manage' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 100,
		// 			],
		// 		],				
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 25,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .crt-grid-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
		// 			'{{WRAPPER}}.crt-if-cfb-bottom .crt-instagram-follow-btn-wrap' => 'margin-top: {{SIZE}}{{UNIT}};'
		// 		],
		// 		'separator' => 'before'
		// 	]
		// );

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
					'{{WRAPPER}} .crt-grid-pagination button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-grid-pagination button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
    }

	// can't use this variable of class in static function, is it necessary to use static ?
	public function call_instagram_api($access_token, $settings) {

        $key = 'crt_instagram-feed_'.$this->get_ID(). '_' .md5($settings['cache_timeout_select']);

		if ( !get_option('crt_instagram_posts_limit'. $this->get_ID()) ) {
			update_option('crt_instagram_posts_limit'. $this->get_ID(), $settings['limit']);
		}

		if( get_transient($key) && get_option('crt_instagram_posts_limit'. $this->get_ID()) ) {
			if ( get_option('crt_instagram_posts_limit'. $this->get_ID()) != $settings['limit']  ) {
				delete_transient($key);
				update_option('crt_instagram_posts_limit'. $this->get_ID(), $settings['limit']);
			}
		}

		if ( get_transient($key) === false || empty(get_transient($key)) || ($settings['instagram_access_token'] !== get_option('crt_instagram_access_token_to_compare'. $this->get_ID())) ) {

			$limit = !empty($settings['limit']) ? $settings['limit'] : 10;

			$url = 'https://graph.instagram.com/me/media?fields=id,media_type,media_url,thumbnail_url,permalink,children,username,caption,timestamp&access_token='. $access_token .'&limit='. $limit;

			$response = wp_remote_get($url);

			// TODO: GOGA refine logic later
			// if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
			// 	// If there's an error or the response code is not 200, return the cached data if available
			// 	$instagram_data = get_transient($key) ?: [];
			// }

			$body = json_decode($response['body']);

			if(!isset($body)) {
				return $response['body'];
			}

			$instagram_data = $body->data;

			$cache_timeout = $this->get_cache_duration();

			if ( 'none' !== $settings['cache_timeout_select'])  {
				set_transient($key, $instagram_data, $cache_timeout);
			}

		} else {
			$instagram_data = get_transient($key);
		}

		if ( !get_option('crt_instagram_access_token_to_compare'. $this->get_ID()) || get_option('crt_instagram_access_token_to_compare'. $this->get_ID()) != $settings['instagram_access_token'] ) {
			update_option('crt_instagram_access_token_to_compare'. $this->get_ID(), $settings['instagram_access_token'] );
		}
		
		return $instagram_data;
	}

	public function get_cache_duration() {
		$settings = $this->get_settings();
		$cache_duration = $settings['cache_timeout_select'];
		$duration = 0;

		switch ( $cache_duration ) {
			case 'minute':
				$duration = MINUTE_IN_SECONDS;
				break;
			case 'hour':
				$duration = HOUR_IN_SECONDS;
				break;
			case 'day':
				$duration = DAY_IN_SECONDS;
				break;
			case 'week':
				$duration = WEEK_IN_SECONDS;
				break;
			default:
				break;
		}

		return $duration;
	}

	public function refresh_access_token($access_token) {
		$url = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token='.$access_token.'';
		$response = wp_remote_get($url);
		if(!isset($body)) {
			$body = json_decode($response['body']);
			if ($body->error) {
				$this->reauthorization_needed = true;
			} else {
				set_transient('crt_instagram_access_token'. $this->get_ID(), $body->access_token, $body->expires_in);
				set_transient('crt_instagram_access_token_expires_in'. $this->get_ID(), $body->expires_in, $body->expires_in);
				set_transient('crt_instagram_access_token_generation_date'. $this->get_ID(), date('Y-m-d'), $body->expires_in);
			}
		}
	}

	// Get Animation Class
	public function get_animation_class( $data, $object ) {
		$class = '';

		// Animation Class
		if ( 'none' !== $data[ $object .'_animation'] ) {
			$class .= ' crt-'. $object .'-'. $data[ $object .'_animation'];
			$class .= ' crt-anim-size-'. $data[ $object .'_animation_size'];
			$class .= ' crt-anim-timing-'. $data[ $object .'_animation_timing'];

			if ( 'yes' === $data[ $object .'_animation_tr'] ) {
				$class .= ' crt-anim-transparency';
			}
		}

		return $class;
	}

	// Get Image Effect Class
	public function get_image_effect_class( $settings ) {
		$class = '';

		// Animation Class
		if ( 'none' !== $settings['image_effects'] ) {
			$class .= ' crt-'. $settings['image_effects'];
		}
		
		// Slide Effect
		if ( 'slide' !== $settings['image_effects'] ) {
			$class .= ' crt-effect-size-'. $settings['image_effects_size'];
		} else {
			$class .= ' crt-effect-dir-'. $settings['image_effects_direction'];
		}

		return $class;
	}


	// Render Media Overlay
	public function render_media_overlay( $settings, $result ) {

		$target = 'yes' == $this->get_settings()['open_in_new_tab'] ? '_blank' : '_self';


		echo '<div class="crt-insta-feed-media-hover-bg '. esc_attr($this->get_animation_class( $settings, 'overlay' )) .'" data-url="'. esc_attr( $result->permalink ) .'" data-target="'. $target .'">';

		echo '</div>';
	}

	// Render Post Title
	public function render_post_username( $settings, $class, $result ) { 

		$target = 'yes' == $this->get_settings()['open_in_new_tab'] ? '_blank' : '_self';

		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
		$element_username_tag = Utilities::validate_html_tags_wl( $settings['element_username_tag'], 'h2', $tags_whitelist );

		echo '<'. esc_attr($element_username_tag) .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a href="'. $result->permalink .'" target="'. $target .'">';
					echo esc_html($result->username);
				echo '</a>';
			echo '</div>';
		echo '</'. esc_attr($element_username_tag) .'>';
	}

	public function render_post_caption($settings, $class, $result) {

		if ( !isset($result->caption) || '' === $result->caption ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
			echo  '<figcaption class="crt-insta-feed-caption"><p>';
			if ( 'word_count' === $settings['element_trim_text_by'] ) {
				echo esc_html(wp_trim_words($result->caption, $settings['element_word_count']));
			} else {
				echo substr(html_entity_decode($result->caption), 0, $settings['element_letter_count']) .'...';
			}
			echo '</p></figcaption>';
			echo '</div>';
		echo '</div>';
	}

	public function render_post_date($settings, $class, $result) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-insta-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="crt-insta-feed-extra-icon-left '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}

				// Date
				if ( 'yes' === $settings['element_hide_year'] ) {
					echo date('F j', strtotime($result->timestamp));
				} else {
					echo date(get_option( 'date_format' ), strtotime($result->timestamp));
				}

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="crt-insta-feed-extra-icon-right '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}

				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-insta-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}

	public function render_post_icon($settings, $class, $result) {

		$target = 'yes' == $this->get_settings()['open_in_new_tab'] ? '_blank' : '_self';

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
			   echo '<a href='. $result->permalink .' target='. $target .'>';
				echo '<i class="fab fa-instagram"></i>';
			   echo '</a>';
			echo '</div>';
		echo '</div>';
	}
	
	public function render_post_lightbox( $settings, $class, $result ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				$lightbox_source = $result->media_url;

				if ( 'VIDEO' === $result->media_type ) {
					$lightbox_source = $result->thumbnail_url;
				}

				// Lightbox Button
				echo '<span data-src="'. esc_url( $lightbox_source ) .'">';
				
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="crt-insta-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

					// Lightbox Icon
					echo '<i class="'. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';

					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="crt-insta-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

				echo '</span>';

				// Media Overlay
				if ( 'yes' === $settings['element_lightbox_overlay'] ) {
					echo '<div class="crt-insta-feed-lightbox-overlay"></div>';
				}
			echo '</div>';
		echo '</div>';
	}

	public function render_post_sharing_icons( $settings, $class, $result ) {
		$args = [
			'icons' => 'yes',
			'tooltip' => $settings['element_sharing_tooltip'],
			'url' => esc_url( $result->permalink ),
			'title' => esc_html( '' ),
			'text' => esc_html( isset($result->caption) ? $result->caption : '' ),
			'image' => esc_url( $result->media_url ),
		];

		$hidden_class = '';

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-insta-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				echo '<span class="crt-post-sharing">';

					if ( 'yes' === $settings['element_sharing_trigger'] ) {
						$hidden_class = ' crt-sharing-hidden';
						$attributes  = ' data-action="'. esc_attr( $settings['element_sharing_trigger_action'] ) .'"';
						$attributes .= ' data-direction="'. esc_attr( $settings['element_sharing_trigger_direction'] ) .'"';

						echo '<a class="crt-sharing-trigger crt-sharing-icon"'. $attributes .'>';
							if ( 'yes' === $settings['element_sharing_tooltip'] ) {
								echo '<span class="crt-sharing-tooltip crt-tooltip">'. esc_html__( 'Share', 'crt-manage' ) .'</span>';
							}

							echo Utilities::get_crt_icon( $settings['element_sharing_trigger_icon'], '' );
						echo '</a>';
					}


					echo '<span class="crt-post-sharing-inner'. $hidden_class .'">';

					for ( $i = 1; $i < 7; $i++ ) {
						$args['network'] = $settings['element_sharing_icon_'. $i];

						echo Utilities::get_post_sharing_icon( $args );
					}

					echo '</span>';

				echo '</span>';

				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-insta-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}


	// Render Post Element Separator
	public function render_post_element_separator( $settings, $class ) {
		echo '<div class="crt-insta-feed-sep-style-1 '. esc_attr($class) .'">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}

	// Get Elements
	public function get_elements( $type, $settings, $class, $result ) {
		switch ( $type ) {
			case 'username':
				$this->render_post_username( $settings, $class, $result );
				break;

			case 'caption':
				$this->render_post_caption( $settings, $class, $result );
				break;

			case 'date':
				$this->render_post_date( $settings, $class, $result );
				break;

			case 'icon':
				$this->render_post_icon( $settings, $class, $result );
				break;

			// case 'comments':
			// 	$this->render_post_comments( $settings, $class );
			// 	break;

			// case 'read-more':
			// 	$this->render_post_read_more( $settings, $class );
			// 	break;

			// case 'likes':
			// 	$this->render_post_likes( $settings, $class, $post_id );
			// 	break;

			case 'sharing':
				$this->render_post_sharing_icons( $settings, $class, $result );
				break;

			case 'lightbox':
				$this->render_post_lightbox( $settings, $class, $result );
				break;

			case 'separator':
				$this->render_post_element_separator( $settings, $class );
				break;
		}

	}

	// Get Elements by Location
	public function get_elements_by_location( $location, $settings, $result ) {
		$locations = [];

		foreach ( $settings['insta_feed_elements'] as $data ) {
			$place = $data['element_location'];
			$align_vr = $data['element_align_vr'];

			if ( ! isset($locations[$place]) ) {
				$locations[$place] = [];
			}
			
			if ( 'over' === $place ) {
				if ( ! isset($locations[$place][$align_vr]) ) {
					$locations[$place][$align_vr] = [];
				}

				array_push( $locations[$place][$align_vr], $data );
			} else {
				array_push( $locations[$place], $data );
			}
		}

		if ( ! empty( $locations[$location] ) ) {

			if ( 'over' === $location ) {
				foreach ( $locations[$location] as $align => $thiss ) {

					if ( 'middle' === $align ) {
						echo '<div class="crt-cv-container"><div class="crt-cv-outer"><div class="crt-cv-inner">';
					}

					echo '<div class="crt-insta-feed-media-hover-'. esc_attr($align) .' elementor-clearfix">';
						foreach ( $thiss as $data ) {
							
							// Get Class
							$class  = 'crt-insta-feed-item-'. $data['element_select'];
							$class .= ' elementor-repeater-item-'. $data['_id'];
							$class .= ' crt-insta-feed-item-display-'. $data['element_display'];
							$class .= ' crt-insta-feed-item-align-'. $data['element_align_hr'];
							$class .= $this->get_animation_class( $data, 'element' );

							// Element
							$this->get_elements( $data['element_select'], $data, $class, $result );
						}
					echo '</div>';

					if ( 'middle' === $align ) {
						echo '</div></div></div>';
					}
				}
			} else {
				$count_elements = 0;
				$caption_not_empty = true;
				foreach ( $locations[$location] as $data ) {
					$count_elements++;
					if ( 'caption' === $data['element_select'] ) {
						$caption_not_empty = isset($result->caption);
					}
				}

				if ( $count_elements == 1 && !$caption_not_empty ) {
					return;
				}

				echo '<div class="crt-insta-feed-item-'. esc_attr($location) .'-content elementor-clearfix">';
					foreach ( $locations[$location] as $data ) {
						// Get Class
						$class  = 'crt-insta-feed-item-'. $data['element_select'];
						$class .= ' elementor-repeater-item-'. $data['_id'];
						$class .= ' crt-insta-feed-item-display-'. $data['element_display'];
						$class .= ' crt-insta-feed-item-align-'. $data['element_align_hr'];

						// Element
						$this->get_elements( $data['element_select'], $data, $class, $result );
					}
				echo '</div>';
			}

		}
	}

	public function render_insta_feed_pagination($settings) {
		echo '<div class="crt-grid-pagination crt-pagination-hidden elementor-clearfix crt-grid-pagination-load-more">';
			echo '<button class="crt-load-more-insta-posts crt-load-more-btn">';
				echo esc_html($settings['pagination_load_more_text']);
			echo '</button>';

			echo '<div class="crt-pagination-loading">';
				switch ( $settings['pagination_animation'] ) {
					case 'loader-1':
						echo '<div class="crt-double-bounce">';
							echo '<div class="crt-child crt-double-bounce1"></div>';
							echo '<div class="crt-child crt-double-bounce2"></div>';
						echo '</div>';
						break;
					case 'loader-2':
						echo '<div class="crt-wave">';
							echo '<div class="crt-rect crt-rect1"></div>';
							echo '<div class="crt-rect crt-rect2"></div>';
							echo '<div class="crt-rect crt-rect3"></div>';
							echo '<div class="crt-rect crt-rect4"></div>';
							echo '<div class="crt-rect crt-rect5"></div>';
						echo '</div>';
						break;
					case 'loader-3':
						echo '<div class="crt-spinner crt-spinner-pulse"></div>';
						break;
					case 'loader-4':
						echo '<div class="crt-chasing-dots">';
							echo '<div class="crt-child crt-dot1"></div>';
							echo '<div class="crt-child crt-dot2"></div>';
						echo '</div>';
						break;
					case 'loader-5':
						echo '<div class="crt-three-bounce">';
							echo '<div class="crt-child crt-bounce1"></div>';
							echo '<div class="crt-child crt-bounce2"></div>';
							echo '<div class="crt-child crt-bounce3"></div>';
						echo '</div>';
						break;
					case 'loader-6':
						echo '<div class="crt-fading-circle">';
							echo '<div class="crt-circle crt-circle1"></div>';
							echo '<div class="crt-circle crt-circle2"></div>';
							echo '<div class="crt-circle crt-circle3"></div>';
							echo '<div class="crt-circle crt-circle4"></div>';
							echo '<div class="crt-circle crt-circle5"></div>';
							echo '<div class="crt-circle crt-circle6"></div>';
							echo '<div class="crt-circle crt-circle7"></div>';
							echo '<div class="crt-circle crt-circle8"></div>';
							echo '<div class="crt-circle crt-circle9"></div>';
							echo '<div class="crt-circle crt-circle10"></div>';
							echo '<div class="crt-circle crt-circle11"></div>';
							echo '<div class="crt-circle crt-circle12"></div>';
						echo '</div>';
						break;
					
					default:
						break;
				}
			echo '</div>';

			echo '<p class="crt-pagination-finish">'. esc_html($settings['pagination_finish_text']) .'</p>';
		echo '</div>';
	}

    protected function render() {
		$settings = $this->get_settings_for_display();

		$columns_mobile = isset($settings['columns_mobile']) ? $settings['columns_mobile'] : $settings['columns'];
		$columns_tablet = isset($settings['columns_tablet']) ? $settings['columns_tablet'] : $settings['columns'];
		$columns_laptop = isset($settings['columns_laptop']) ? $settings['columns_laptop'] : $settings['columns'];
		$columns_widescreen = isset($settings['columns_widescreen']) ? $settings['columns_widescreen'] : $settings['columns'];

		$instagram_settings = [
			'insta_layout_select' => $settings['insta_layout_select'],
			'columns' => $settings['columns'],
			'columns_mobile' => $columns_mobile,
			'columns_mobile_extra' => isset($settings['columns_mobile_extra']) ? $settings['columns_mobile_extra'] : $columns_tablet,
			'columns_tablet' => $columns_tablet,
			'columns_tablet_extra' => isset($settings['columns_tablet_extra']) ? $settings['columns_tablet_extra'] : $columns_laptop,
			'columns_laptop' => $columns_laptop,
			'columns_widescreen' => $columns_widescreen,
			'gutter_hr' => ($settings['gutter']) ? $settings['gutter']['size'] : '',
			'gutter_vr' => isset($settings['distance_bottom']) ? $settings['distance_bottom']['size'] : '',
			// 'animation' => $settings['layout_animation'],
			// 'animation_duration' => $settings['layout_animation_duration'],
			// 'animation_delay' => $settings['layout_animation_delay'],
		];

		if ( 'layout-list' === $settings['insta_layout_select'] ) {
			$instagram_settings['media_align'] = $settings['layout_list_align'];
			$instagram_settings['media_width'] = $settings['layout_list_media_width']['size'];
			$instagram_settings['media_distance'] = $settings['layout_list_media_distance']['size'];
		}

		$instagram_settings['lightbox'] = [
			'selector' => '.crt-insta-feed-image-wrap',
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

		$instagram_settings['insta_load_more_settings'] = [
			'instagram_access_token' => $settings['instagram_access_token'],
			'limit' =>  $settings['limit'],
			'limit_mobile' =>  $settings['limit_mobile'],
			'is_mobile' => wp_is_mobile() ? 'mobile' : 'other',
			'open_in_new_tab' => $settings['open_in_new_tab'],
			'overlay_post_link' => $settings['overlay_post_link'],
			'image_effects' => isset($settings['image_effects']) ? $settings['image_effects'] : 'none',
			'image_effects_size' => isset($settings['image_effects_size']) ? $settings['image_effects_size'] : 'none',
			'image_effects_duration' => isset($settings['image_effects_duration']) ? $settings['image_effects_duration'] : 'none',
			'overlay_animation' => isset($settings['overlay_animation']) ? $settings['overlay_animation'] : 'none',
			'overlay_animation_size' => isset($settings['overlay_animation_size']) ? $settings['overlay_animation_size'] : 'none',
			'overlay_animation_timing' => isset($settings['overlay_animation_timing']) ? $settings['overlay_animation_timing'] : 'none',
			'overlay_animation_tr' => isset($settings['overlay_animation_tr']) ? $settings['overlay_animation_tr'] : 'none',
			'insta_feed_elements' => $settings['insta_feed_elements'],
		];
		
		if ( 'layout-carousel' === $settings['insta_layout_select'] ) {
			
			$navigation = $settings['enable_cs_nav'];
			$pagination = $settings['enable_cs_pag'];
			$pagination_type = isset($settings['cs_pag_type']) ? $settings['cs_pag_type'] : '';
			$autoplay = $settings['enable_insta_feed_slider_autoplay'];
			$loop = $settings['enable_insta_feed_slider_loop'];
			$slides_to_show = $settings['insta_feed_slides_to_show'];
			$slides_to_show_widescreen = isset($settings['insta_feed_slides_to_show_widescreen']) ? $settings['insta_feed_slides_to_show_widescreen'] : $slides_to_show;
			$slides_to_show_laptop = isset($settings['insta_feed_slides_to_show_laptop']) ? $settings['insta_feed_slides_to_show_laptop'] : $settings['insta_feed_slides_to_show'];
			$slides_to_show_tablet_extra = isset($settings['insta_feed_slides_to_show_tablet_extra']) ? $settings['insta_feed_slides_to_show_tablet_extra'] : $slides_to_show_laptop;
			$slides_to_show_tablet = isset($settings['insta_feed_slides_to_show_tablet']) ? $settings['insta_feed_slides_to_show_tablet'] : $slides_to_show_tablet_extra;
			$slides_to_show_mobile_extra = isset($settings['insta_feed_slides_to_show_mobile_extra']) ? $settings['insta_feed_slides_to_show_mobile_extra'] : $slides_to_show_tablet;
			$slides_to_show_mobile = isset($settings['insta_feed_slides_to_show_mobile']) ? $settings['insta_feed_slides_to_show_mobile'] : $slides_to_show_mobile_extra;
			$space_between = $settings['insta_feed_space_between'];
			$space_between_widescreen = isset($settings['insta_feed_space_between_widescreen']) ? $settings['insta_feed_space_between_widescreen'] : $space_between;
			$space_between_laptop = isset($settings['insta_feed_space_between_laptop']) ? $settings['insta_feed_space_between_laptop'] : $space_between;
			$space_between_tablet_extra = isset($settings['insta_feed_space_between_tablet_extra']) ? $settings['insta_feed_space_between_tablet_extra'] : $space_between_laptop;
			$space_between_tablet = isset($settings['insta_feed_space_between_tablet']) ? $settings['insta_feed_space_between_tablet'] : $space_between_tablet_extra;
			$space_between_mobile_extra = isset($settings['insta_feed_space_between_mobile_extra']) ? $settings['insta_feed_space_between_mobile_extra'] : $space_between_tablet;
			$space_between_mobile = isset($settings['insta_feed_space_between_mobile']) ? $settings['insta_feed_space_between_mobile'] : $space_between_mobile_extra;
			$delay = isset($settings['insta_feed_delay']) ? $settings['insta_feed_delay'] : '';
			$speed = $settings['insta_feed_speed'];

			$instagram_settings['carousel'] = [
				'crt_cs_navigation' => $navigation,
				'crt_cs_pagination' => $pagination,
				'crt_cs_pagination_type' => $pagination_type,
				'crt_cs_autoplay' => $autoplay,
				'crt_cs_loop' => $loop,
				'crt_cs_slides_to_show' => $slides_to_show,
				'crt_cs_slides_to_show_widescreen' =>  $slides_to_show_widescreen,
				'crt_cs_slides_to_show_laptop' => $slides_to_show_laptop,
				'crt_cs_slides_to_show_tablet_extra' => $slides_to_show_tablet_extra,
				'crt_cs_slides_to_show_tablet' => $slides_to_show_tablet,
				'crt_cs_slides_to_show_mobile_extra' =>  $slides_to_show_mobile_extra,
				'crt_cs_slides_to_show_mobile' =>  $slides_to_show_mobile,
				'crt_cs_space_between' => $space_between,
				'crt_cs_space_between_widescreen' => $space_between_widescreen,
				'crt_cs_space_between_laptop' => $space_between_laptop,
				'crt_cs_space_between_tablet_extra' => $space_between_tablet_extra,
				'crt_cs_space_between_tablet' => $space_between_tablet,
				'crt_cs_space_between_tablet' => $space_between_mobile_extra,
				'crt_cs_space_between_tablet' => $space_between_mobile,
				'crt_cs_delay' => $delay,
				'crt_cs_speed' => $speed,
				// 'enable_on'   => $settings['crt_enable_equal_height_on'],
			];
		}

		$this->add_render_attribute(
			'instagram',
			[
				'class'         => ['crt-instagram-feed', 'crt-'. $settings['insta_layout_select']],
				'data-settings' => wp_json_encode( $instagram_settings ),
			]
		);

		if ( ! empty( $settings['instagram_follow_link']['url'] ) ) {
			$this->add_link_attributes( 'instagram_follow_link', $settings['instagram_follow_link'] );
		}

		if ( get_transient('crt_instagram_access_token'. $this->get_ID()) && ($settings['instagram_access_token'] == get_option('crt_instagram_access_token_to_compare'. $this->get_ID())) ) {

			$access_token = get_transient('crt_instagram_access_token'. $this->get_ID());
			$token_expires_in = get_transient('crt_instagram_access_token_expires_in'. $this->get_ID());

			$compare_date = strtotime('-'.get_transient('crt_instagram_access_token_expires_in'. $this->get_ID()).' seconds');
	
			$token_generation_date = strtotime(get_transient('crt_instagram_access_token_generation_date'. $this->get_ID()));
		} else {
			$access_token = $settings['instagram_access_token'];
			
			if ( !get_transient('crt_instagram_access_token_expires_in'. $this->get_ID()) ) {
				set_transient('crt_instagram_access_token_expires_in'. $this->get_ID(), $settings['instagram_access_token_expires_in']);
			}
			
			$token_expires_in = get_transient('crt_instagram_access_token_expires_in'. $this->get_ID());
			
			if (!get_transient('crt_instagram_access_token_generation_date'. $this->get_ID())) {
				set_transient('crt_instagram_access_token_generation_date'. $this->get_ID(), date('Y-m-d'), $token_expires_in);
			}

			$compare_date = strtotime('-'.$settings['instagram_access_token_expires_in'].' seconds');

			$token_generation_date = strtotime(get_transient('crt_instagram_access_token_generation_date'. $this->get_ID()));
		}

		if ( ($token_generation_date <= $compare_date) || (get_option('crt_instagram_access_token_to_compare'. $this->get_ID()) != $settings['instagram_access_token']) ) {
				$this->refresh_access_token($access_token);
		}

		if ( '' === $access_token ) {
			if ( current_user_can('administrator') ) {
				echo '<p class="crt-token-missing">'. esc_html__('Please insert Access Token and Expiry Date in associated fields', 'crt-manage') .'</p>';
			}
			return;
		}

		if ( $this->reauthorization_needed ) {
			if ( current_user_can('administrator') ) {
				echo '<p class="crt-token-missing">'. esc_html__('Please reauthorize instagram', 'crt-manage') .'</p>';
			}
			return;
		}

		?>
				
		<?php if ( 'yes' === $settings['show_instagram_follow_button'] && 'top' === $settings['follow_button_location'] ) : ?>
			<div class="crt-instagram-follow-btn-wrap">
				<a class="crt-instagram-follow-btn" <?php echo $this->get_render_attribute_string( 'instagram_follow_link' ); ?>>
					<?php 
						if ( '' !== $settings['instagram_follow_icon']) {
							\Elementor\Icons_Manager::render_icon( $settings['instagram_follow_icon'], [ 'aria-hidden' => 'true' ] ); 
						}
					?>
					<?php echo $settings['instagram_follow_text'] ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="crt-instagram-feed-cont">
			<?php
			if ( 'yes' === $settings['enable_cs_nav'] ) {
				echo '<div class="crt-swiper-nav-wrap">';
					echo '<button class="crt-swiper-button crt-swiper-button-prev">';
						echo Utilities::get_crt_icon( $settings['cs_nav_arrows'], 'left' );
					echo '</button>';
					echo '<button class="crt-swiper-button crt-swiper-button-next">';
						echo Utilities::get_crt_icon( $settings['cs_nav_arrows'], 'right' );
					echo '</button>';
				echo '</div>';
			}
			

			if ( 'yes' === $settings['enable_cs_pag'] ) {
				echo '<div class="swiper-pagination"></div>';
			}

			$posts_count = 0;
			?>

			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'instagram' ) ); ?>>

				<?php 
					foreach($this->call_instagram_api($access_token, $settings) as $result) : 

					if ( wp_is_mobile() && $posts_count > ($settings['limit_mobile'] - 1) ) {
						break;
					}
				?>

					<div class="crt-insta-feed-content-wrap elementor-clearfix crt-insta-col-12">
						<figure>
							<?php
								// Content: Above Media
								echo $this->get_elements_by_location( 'above', $settings, $result );
							?>
							<div class="crt-insta-feed-media-wrap <?php echo esc_attr($this->get_image_effect_class( $settings )) ?>" data-overlay-link="<?php echo esc_attr( $settings['overlay_post_link'] ) ?>">
							<?php if ( 'CAROUSEL_ALBUM' == $result->media_type || 'IMAGE' == $result->media_type ) : ?>
								<div class="crt-insta-feed-image-wrap" data-src=<?php echo $result->media_url ?>>
									<img src=<?php echo $result->media_url  ?> alt="">
								</div>
							<?php elseif ($result->media_type == 'VIDEO') : ?>
								<div class="crt-insta-feed-image-wrap" data-src=<?php echo $result->thumbnail_url ?>>
									<img class="crt-insta-feed-thumb" src=<?php echo $result->thumbnail_url ?> alt="">
								</div>
							<?php endif ; ?>
								<div class="crt-insta-feed-media-hover crt-animation-wrap">
									<?php
									// Media Overlay
									$this->render_media_overlay( $settings, $result );

									// Content: Over Media
									$this->get_elements_by_location( 'over', $settings, $result );
									?>
								</div>
							</div>
							<?php
								// Content: Below Media
								echo $this->get_elements_by_location( 'below', $settings, $result );
							?>
						</figure>
					</div>

				<?php
					$posts_count++;
					endforeach; 
				?>
				
			</div>
			
		</div>

		<?php

		if ( 'yes' === $settings['show_pagination'] || 'yes' === $settings['show_instagram_follow_button'] ) :
			echo '<div class="crt-isnta-feed-buttons-wrap">';
				// Pagination
				if ( 'yes' === $settings['show_pagination'] ) {
					$this->render_insta_feed_pagination( $settings );
				}
						
				if ( 'yes' === $settings['show_instagram_follow_button'] && ('bottom' === $settings['follow_button_location'] || 'center' === $settings['follow_button_location']) ) : ?>
					<div class="crt-instagram-follow-btn-wrap">
						<a class="crt-instagram-follow-btn" <?php echo $this->get_render_attribute_string( 'instagram_follow_link' ); ?>>
							<?php 
							if ( '' !== $settings['instagram_follow_icon']) {
								\Elementor\Icons_Manager::render_icon( $settings['instagram_follow_icon'], [ 'aria-hidden' => 'true' ] ); 
							}
							?>
							<?php echo $settings['instagram_follow_text'] ?>
						</a>
					</div>
				<?php endif;
			echo '</div>';
		endif;

    }
}