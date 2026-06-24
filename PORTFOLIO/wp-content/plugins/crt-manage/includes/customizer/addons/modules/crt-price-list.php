<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Price_List extends Widget_Base {
		
	public function get_name() {
		return 'crt-price-list';
	}

	public function get_title() {
		return esc_html__( 'Price List', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-price-list';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'pricing list', 'price list', 'price menu', 'pricing menu', 'food menu', 'restaurant menu' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com/contact';
    }

	public function add_repeater_args_prlist_image() {
        return [
            'label' => esc_html__( 'Image', 'crt-addons' ),
            'type' => Controls_Manager::MEDIA,
            'dynamic' => [
                'active' => true,
            ],
            'default' => [
                'url' => Utils::get_placeholder_image_src(),
            ],
        ];
	}

	public function add_repeater_args_prlist_link() {
        return [
            'label' => esc_html__( 'Link', 'crt-addons' ),
            'type' => Controls_Manager::URL,
            'dynamic' => [
                'active' => true,
            ],
            'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-addons' ),
            'separator' => 'before'
        ];
	}

    public function add_control_prlist_position() {
        $this->add_control(
            'prlist_position_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control(
            'prlist_position',
            [
                'label' => esc_html__( 'Layout', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__( 'Image Left', 'crt-manage' ),
                    'center' => esc_html__( 'Content Center', 'crt-manage' ),
                    'right' => esc_html__( 'Image Right', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-price-list-position-',
                'render_type' => 'template',
            ]
        );
    }

    public function add_control_prlist_vr_position() {
        $this->add_responsive_control(
            'prlist_vr_position',
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
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end'
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-price-list-item' => '-webkit-align-items: {{VALUE}};align-items: {{VALUE}};',
                ],
                'condition' => [
                    'prlist_position!' => 'center',
                ],
            ]
        );
    }

    public function add_section_style_image() {
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__( 'Image', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image_size',
                'default' => 'medium',
            ]
        );

        $this->add_responsive_control(
            'image_size1',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-price-list-image img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_gutter',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Spacing', 'crt-manage' ),
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
                    '{{WRAPPER}}.crt-price-list-position-left .crt-price-list-image img' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.crt-price-list-position-right .crt-price-list-image img' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.crt-price-list-position-center .crt-price-list-image img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => esc_html__( 'Border', 'crt-manage' ),
                'fields_options' => [
                    'color' => [
                        'default' => '#E8E8E8',
                        'width' => [
                            'default' => [
                                'top' => '1',
                                'right' => '1',
                                'bottom' => '1',
                                'left' => '1',
                                'isLinked' => true,
                            ],
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .crt-price-list-image img',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .crt-price-list-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_box_shadow_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .crt-price-list-image img',
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

    protected function register_controls() {
		
		// Section: Items -----------
		$this->start_controls_section(
			'section_price_list_items',
			[
				'label' => esc_html__( 'Items', 'crt-manage' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'prlist_title',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Sweet Cakes',
			]
		);

		$repeater->add_control(
			'prlist_price',
			[
				'label' => esc_html__( 'Price', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '$30',
			]
		);

		$repeater->add_control(
			'prlist_old_price',
			[
				'label' => esc_html__( 'Old Price', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
			]
		);

		$repeater->add_control(
			'prlist_description',
			[
				'label'   	=> esc_html__( 'Description', 'crt-manage' ),
				'type'    	=> Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Best pricing list widget.',
			]
		);

		$repeater->add_control( 'prlist_image', $this->add_repeater_args_prlist_image() );

		$repeater->add_control( 'prlist_link', $this->add_repeater_args_prlist_link() );

		$this->add_control(
			'prlist_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'prlist_title' => 'Sweet Cakes',
						'prlist_price' => '$30',
						'prlist_description' => 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Best pricing list widget.',
						'prlist_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'prlist_title' => 'Fresh Vegetables',
						'prlist_price' => '$50',
						'prlist_description' => 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Best pricing list widget.',
						'prlist_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'prlist_title' => 'White Potatoes',
						'prlist_price' => '$27',
						'prlist_old_price' => '35',
						'prlist_description' => 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Best pricing list widget.',
						'prlist_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
				],
				'title_field' => '{{{ prlist_title }}}',
			]
		);

		$this->add_control_prlist_position();

		$this->add_control_prlist_vr_position();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles
		// Section: General ----------
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'general_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .crt-price-list-item'
			]
		);

		$this->add_responsive_control(
			'general_gutter',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'general_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'general_border',
				'label' => esc_html__( 'Border', 'crt-manage' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
						'width' => [
							'default' => [
								'top' => '1',
								'right' => '1',
								'bottom' => '1',
								'left' => '1',
								'isLinked' => true,
							],
						],
					],
				],
				'selector' => '{{WRAPPER}} .crt-price-list-item',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_box_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'general_box_shadow',
				'selector' => '{{WRAPPER}} .crt-price-list-item',
			]
		);

		$this->end_controls_section(); // End Controls Section


		// Styles
		// Section: Image ------------
		$this->add_section_style_image();

		// Styles
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'title_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .crt-price-list-title',
			]
		);


		$this->add_responsive_control(
			'title_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'prlist_position' => 'center',
				],
			]
		);


		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Separator ------------
		$this->start_controls_section(
			'section_style_separator',
			[
				'label' => esc_html__( 'Separator', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'separator_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'default' => '#a8a8a8',
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-separator' => 'border-color: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'separator_style',
			[
				'label' => esc_html__( 'Style', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'dotted',
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-separator' => 'border-bottom-style: {{VALUE}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'separator_weight',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Weight', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-separator' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator_spacing',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Spacing', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-price-list-separator' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section


		// Styles
		// Section: Price ------------
		$this->start_controls_section(
			'section_style_price',
			[
				'label' => esc_html__( 'Price', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'price_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'price_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-price-wrap' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'price_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Min Width', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-price-wrap' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .crt-price-list-price, {{WRAPPER}} .crt-price-list-old-price',
			]
		);

		$this->add_control(
			'old_price_section',
			[
				'label' => esc_html__( 'Old Price', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'old_price_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'default' => '#f75959',
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-old-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'old_price_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Font Size', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 11,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-old-price' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'old_price_hr_position',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'before',
				'options' => [
					'before' => [
						'title' => esc_html__( 'Before', 'crt-manage' ),
						'icon' => 'eicon-h-align-left',
					],
					'after' => [
						'title' => esc_html__( 'After', 'crt-manage' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'crt-price-list-old-position-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'old_price_vr_position',
			[
				'label' => esc_html__( 'Vertical Position', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'default' => 'top',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-old-price' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'price_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-price-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'price_border',
				'label' => esc_html__( 'Border', 'crt-manage' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
						'width' => [
							'default' => [
								'top' => '1',
								'right' => '1',
								'bottom' => '1',
								'left' => '1',
								'isLinked' => true,
							],
						],
					],
				],
				'selector' => '{{WRAPPER}} .crt-price-list-price-wrap',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-price-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_box_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'price_box_shadow',
				'selector' => '{{WRAPPER}} .crt-price-list-price-wrap',
			]
		);

		$this->end_controls_section(); // End Controls Section


		// Styles
		// Section: Description ------
		$this->start_controls_section(
			'section_style_description',
			[
				'label' => esc_html__( 'Description', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'description_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'default' => '#757575',
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .crt-price-list-description',
			]
		);

		$this->add_control(
			'description_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
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
					'justify' => [
						'title' => esc_html__( 'Justify', 'crt-manage' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-price-list-description' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'description_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-price-list-description' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section	

	}

    public function render_pro_element_image( $item, $item_count ) {
        $settings = $this->get_settings();

        $item['prlist_image']['id'] = isset($item['prlist_image']['id']) ? $item['prlist_image']['id'] : false;

        $image_src = Group_Control_Image_Size::get_attachment_image_src( $item['prlist_image']['id'], 'image_size', $settings );

        if ( isset($item['prlist_link']['url']) && '' !== $item['prlist_link']['url'] ) {

            $this->add_render_attribute( 'crt-price-list-link' . $item_count, 'class', 'crt-price-list-link' );

            $this->add_render_attribute( 'crt-price-list-link' . $item_count, 'href', esc_url( $item['prlist_link']['url'] ) );

            if ( $item['prlist_link']['is_external'] ) {
                $this->add_render_attribute( 'crt-price-list-link' . $item_count, 'target', '_blank' );
            }

            if ( $item['prlist_link']['nofollow'] ) {
                $this->add_render_attribute( 'crt-price-list-link' . $item_count, 'nofollow', '' );
            }

            echo '<a '. $this->get_render_attribute_string( 'crt-price-list-link' . $item_count ) .'></a>';
        }

        if ( $image_src ) {
            echo '<div class="crt-price-list-image">';
            echo '<img src="'. esc_url( $image_src ) .'" >';
            echo '</div>';
        }
    }

    protected function render() {
		
		$settings = $this->get_settings();
		$item_count = 0;
	
		?>

		<div class="crt-price-list">
			
			<?php foreach ( $settings['prlist_items'] as $item ) : ?>

				<div class="crt-price-list-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?> elementor-clearfix">
							
				<?php $this->render_pro_element_image($item, $item_count); ?>

					<div class="crt-price-list-content">
						
						<div class="crt-price-list-heading">
							
							<?php if ( '' !== $item['prlist_title'] ) : ?>								
							<span class="crt-price-list-title"><?php echo esc_html( $item['prlist_title'] ); ?></span>							
							<?php endif; ?>
							
							<?php if ( 'none' !== $settings['separator_style'] ) : ?>						
								<span class="crt-price-list-separator"></span>
							<?php endif ?>

							<?php if ( '' !== $item['prlist_price'] || '' !== $item['prlist_old_price'] ) : ?>
								<span class="crt-price-list-price-wrap">
									<?php if ( '' !== $item['prlist_price'] ) : ?>	
									<span class="crt-price-list-price"><?php echo esc_html( $item['prlist_price'] ); ?></span>
									<?php endif; ?>

									<?php if ( '' !== $item['prlist_old_price'] ) : ?>	
									<span class="crt-price-list-old-price"><?php echo esc_html( $item['prlist_old_price'] ); ?></span>
									<?php endif; ?>
								</span>
							<?php endif; ?>

						</div>
						
						<?php if ( '' !== $item['prlist_description'] ) : ?>
							<div class="crt-price-list-description"><?php echo wp_kses_post( $item['prlist_description'] ); ?></div>
						<?php endif; ?>
					</div>

				</div>

				<?php
				$item_count++;
			endforeach;
			?>
		</div>
		<?php

	}
}