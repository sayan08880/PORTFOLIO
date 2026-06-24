<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use CrtAddons\Classes\Utilities;
use CrtAddons\Classes\Modules\CRT_Woo_Grid_Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Woo_Grid extends Widget_Base {
	
	public function get_name() {
		return 'crt-woo-grid';
	}

	public function get_title() {
		return esc_html__( 'Woo Grid/Slider/Carousel', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-gallery-grid';
	}

	public function get_categories() {
        return ['crt_manage_woocommerce'];
	}

	public function get_keywords() {
		return [ 'shop grid', 'product grid', 'woocommerce', 'product slider', 'product carousel', 'isotope', 'massonry grid', 'filterable grid', 'loop grid' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_script_depends() {
		return [ 'crt-manage-isotope', 'crt-manage-lib-slick', 'crt-lightgallery', 'crt-woo-grid' ];
	}

	public function get_style_depends() {
        return [ 'crt-animations-css', 'crt-link-animations-css', 'crt-button-animations-css', 'crt-loading-animations-css', 'crt-lightgallery-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com';
    }

	public function add_control_secondary_img_on_hover() {
		$this->add_control(
			'secondary_img_on_hover',
			[
				'label' => __( '2nd Image on Hover', 'crt-manage' ),
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
				'classes' => 'no-distance'
			]
		);
	}

	public function add_control_query_selection() {
        $options = [
			'dynamic' => esc_html__( 'Dynamic', 'crt-manage' ),
			'manual' => esc_html__( 'Manual', 'crt-manage' ),
			'current' => esc_html__( 'Current Query', 'crt-manage' ),
        ];

		if ( Utilities::is_pro() ) {
			$options['featured'] = esc_html__( 'Featured', 'crt-manage' );
			$options['onsale'] = esc_html__( 'On Sale', 'crt-manage' );
			$options['upsell'] = esc_html__( 'Upsell', 'crt-manage' );
			$options['cross-sell'] = esc_html__( 'Cross-sell', 'crt-manage' );
		} else {
			$options['pro-fr'] = esc_html__( 'Featured (Pro)', 'crt-manage' );
			$options['pro-os'] = esc_html__( 'On Sale (Pro)', 'crt-manage' );
			$options['pro-us'] = esc_html__( 'Upsell (Pro)', 'crt-manage' );
			$options['pro-cs'] = esc_html__( 'Cross-sell (Pro)', 'crt-manage' );
		}

		$this->add_control(
			'query_selection',
			[
				'label' => esc_html__( 'Query Products', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => $options,
			]
		);
	}

	public function add_control_query_orderby() {
        $options = [
			'default' => esc_html__( 'Default', 'crt-manage' ),
			'date' => esc_html__( 'Date', 'crt-manage' ),
			'sales' => esc_html__( 'Sales', 'crt-manage' ),
			'price-low' => esc_html__( 'Price - Low to High', 'crt-manage' ),
			'price-high' => esc_html__( 'Price - High to Low', 'crt-manage' ),
			'random' => esc_html__( 'Random', 'crt-manage' ),
        ];

		if ( Utilities::is_pro() ) {
			$options['rating'] = esc_html__( 'Rating', 'crt-manage' );
		} else {
			$options['pro-rn'] = esc_html__( 'Rating (Pro)', 'crt-manage' );
		}

		$this->add_control(
			'query_orderby',
			[
				'label' => esc_html__( 'Order By', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => $options,
				'condition' => [
					'query_selection' => [ 'dynamic', 'onsale', 'featured', 'upsell', 'cross-sell' ],
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
				'default' => 'fitRows',
				'options' => $options,
				'label_block' => true,
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
			'default' => esc_html__( 'Default', 'crt-manage' ),
			'zoom' => esc_html__( 'Zoom', 'crt-manage' ),
        ];

		if ( Utilities::is_pro() ) {
			$options['fade'] = esc_html__( 'Fade', 'crt-manage' );
			$options['fade_slideup'] = esc_html__( 'Fade + SlideUp', 'crt-manage' );
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

	public function add_control_sort_and_results_count() {
		$this->add_control(
			'layout_sort_and_results_count',
			[
				'label' => __( 'Show Sorting', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => '',
				'condition' => [
					'layout_select!' => 'slider',
				]
			]
		);
	}

	public function add_section_grid_sorting() {
		// Tab: Content ==============
		// Section: Sorting ----------
		$this->start_controls_section(
			'section_grid_sorting',
			[
				'label' => esc_html__( 'Sorting', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'query_selection!' => ['upsell', 'cross-sell'],
					'layout_select!' => 'slider',
					'layout_sort_and_results_count' => 'yes'
				],
			]
		);

		$this->add_control(
			'sort_heading',
			[
				'label' => esc_html__( 'Heading', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Shop'
			]
		);

		$this->add_control(
			'sort_heading_tag',
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
				'sort_heading!' => ''
			]
		);

		$this->add_control(
			'sort_select_position',
			[
				'label' => esc_html__( 'Select Position', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'below',
				'options' => [
					'above' => [
						'title' => esc_html__( 'Above', 'crt-manage' ),
						'icon' => 'eicon-v-align-top',
					],
					'below' => [
						'title' => esc_html__( 'Below', 'crt-manage' ),
						'icon' => 'eicon-v-align-bottom',
					]
				],
				'render_type' => 'template',
				'prefix_class' => 'crt-sort-select-position-',
				'sort_heading!' => ''
			]
		);

		$this->end_controls_section(); // End Controls Section
	}


    public function add_section_style_sort_and_results() {
        // Styles ====================
        // Section: sorting ----------
        $this->start_controls_section(
            'section_style_sort_and_results',
            [
                'label' => esc_html__( 'Sorting', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'query_selection!' => ['upsell', 'cross-sell'],
                    'layout_select!' => 'slider',
                    'layout_sort_and_results_count' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'sort_and_results_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-sorting-wrap' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_responsive_control(
            'sort_and_results_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-sorting-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'sort_and_results_distance_from_grid',
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
                    '{{WRAPPER}} .crt-grid-sorting-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ]
                // 'separator' => 'before'
            ]
        );

        // Results
        $this->add_control(
            'sort_title_style_heading',
            [
                'label' => esc_html__( 'Title', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'sort_title_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-sort-heading :is(h1, h2, h3, h4, h5, h6)' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'sort_title',
                'selector' => '{{WRAPPER}} .crt-grid-sort-heading :is(h1, h2, h3, h4, h5, h6)',
                'fields_options' => [
                    'typography'      => [
                        'default' => 'custom',
                    ],
                    // 'font_size'      => [
                    // 	'default'    => [
                    // 		'size' => '14',
                    // 		'unit' => 'px',
                    // 	],
                    // ]
                ]
            ]
        );

        $this->add_responsive_control(
            'sort_and_results_title_distance_from_grid',
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-sort-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        // Results
        $this->add_control(
            'results_style_heading',
            [
                'label' => esc_html__( 'Results', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'results_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#787878',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-sorting-inner-wrap .woocommerce-result-count' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'results',
                'selector' => '{{WRAPPER}} .crt-grid-sorting-inner-wrap .woocommerce-result-count',
                'fields_options' => [
                    'typography'      => [
                        'default' => 'custom',
                    ],
                    // 'font_size'      => [
                    // 	'default'    => [
                    // 		'size' => '14',
                    // 		'unit' => 'px',
                    // 	],
                    // ]
                ]
            ]
        );

        // Results
        $this->add_control(
            'sorting_style_heading',
            [
                'label' => esc_html__( 'Sorting', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'sorting_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#787878',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-sorting-wrap form .orderby' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-sorting-wrap form .crt-orderby-icon' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'sorting_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-sorting-wrap form .orderby' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'sorting_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-sorting-wrap form .orderby' => 'background-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'sorting',
                'selector' => '{{WRAPPER}} .crt-grid-sorting-wrap form .orderby, {{WRAPPER}} .crt-grid-sorting-wrap form .orderby option'
            ]
        );

        $this->add_responsive_control(
            'sorting_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-sorting-wrap form .crt-orderby-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                // 'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'sorting_select_width',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 150,
                        'max' => 400,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-sorting-wrap form .orderby' => 'width: {{SIZE}}{{UNIT}};',
                ],
                // 'separator' => 'before'
            ]
        );

        $this->add_control(
            'sorting_select_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 5,
                    'right' => 15,
                    'bottom' => 5,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-sorting-wrap form .orderby' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-sorting-wrap .crt-orderby-icon' => 'right: {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_control(
            'sorting_border_type',
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
                    '{{WRAPPER}} .crt-grid-sorting-wrap form .orderby' => 'border-style: {{VALUE}};',
                ],
                // 'separator' => 'before',
            ]
        );

        $this->add_control(
            'sorting_border_width',
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
                    '{{WRAPPER}} .crt-grid-sorting-wrap form .orderby' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'sorting_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'sorting_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-sorting-wrap form .orderby' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
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
					3 => esc_html__( 'Three ', 'crt-manage' ),
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
				'label' => esc_html__( 'Show on Hover', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'fade',
				'prefix_class' => 'crt-grid-slider-nav-',
				'render_type' => 'template',
				'condition' => [
					'layout_slider_nav' => 'yes',
					'layout_select' => 'slider',

				],
			]
		);
	}


	public function add_control_layout_slider_dots_position() {
		$this->add_control(
			'layout_slider_dots_position',
			[
				'label' => esc_html__( 'Pagination Layout', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'crt-manage' ),
					'vertical' => esc_html__( 'Vertical', 'crt-manage' ),
				],
				'prefix_class' => 'crt-grid-slider-dots-',
				'render_type' => 'template',
				'condition' => [
					'layout_slider_dots' => 'yes',
					'layout_select' => 'slider',
				],
			]
		);
	}

	public function add_control_stack_layout_slider_autoplay() {
        $this->add_control(
            'layout_slider_autoplay',
            [
                'label' => esc_html__( 'Slider Autoplay', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'layout_select' => 'slider',
                ]
            ]
        );
        $this->add_control(
            'layout_slider_autoplay_duration',
            [
                'label' => esc_html__( 'Autoplay Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'max' => 5,
                'condition' => [
                    'layout_select' => 'slider',
                ]
            ]
        );
        $this->add_control(
            'layout_slider_pause_on_hover',
            [
                'label' => esc_html__( 'Slider Pause On Hover', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'layout_select' => 'slider',
                ]
            ]
        );
    }

	public function add_option_element_select() {
        $options = [
			'title' => esc_html__( 'Title', 'crt-manage' ),
			'excerpt' => esc_html__( 'Excerpt', 'crt-manage' ),
			'product_cat' => esc_html__( 'Categories', 'crt-manage' ),
			'product_tag' => esc_html__( 'Tags', 'crt-manage' ),
			'custom-field' => esc_html__( 'Custom Fields/Attributes', 'crt-manage' ),
			'status' => esc_html__( 'Status', 'crt-manage' ),
			'price' => esc_html__( 'Price', 'crt-manage' ),
			'sale_dates' => esc_html__( 'Sale Dates', 'crt-manage' ),
			'rating' => esc_html__( 'Rating', 'crt-manage' ),
			'add-to-cart' => esc_html__( 'Add to Cart', 'crt-manage' ),
			'lightbox' => esc_html__( 'Lightbox', 'crt-manage' ),
			'separator' => esc_html__( 'Separator', 'crt-manage' ),
			'like' => esc_html__( 'Likes', 'crt-manage' ),
			'sharing' => esc_html__( 'Sharing', 'crt-manage' ),
			'read-more' => esc_html__( 'View More', 'crt-manage' )
        ];

		if ( Utilities::is_pro() ) {
            $options['wishlist-button'] = esc_html__( 'Wishlist Button', 'crt-manage' );
            $options['compare-button'] = esc_html__( 'Compare Button', 'crt-manage' );
		} else {
            $options['pro-ws'] = esc_html__( 'Wishlist Button (Pro)', 'crt-manage' );
			$options['pro-cm'] = esc_html__( 'Compare Button (Pro)', 'crt-manage' );
		}

		return $options;
	}

	public function add_repeater_args_element_custom_field() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_custom_field_btn_link() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_custom_field_new_tab() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_custom_field_style() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_like_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_like_text() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_like_show_count() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
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

	public function add_repeater_args_element_show_added_tc_popup() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_show_added_to_wishlist_popup() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_show_added_to_compare_popup() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_trim_text_by() {
		return [
			'none' => esc_html__( 'None', 'crt-manage' ),
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


	public function add_control_filters_deeplinking() {
		$this->add_control(
			'filters_deeplinking',
			[
				'label' => esc_html__( 'Enable Deep Linking', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'filters_linkable!' => 'yes',
				],
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
				'label' => esc_html__( 'Show Count', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
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
				'render_type' => 'template',
				'separator' => 'after'
			]
		);
	}

    public function add_section_added_to_cart_popup() {
        // Styles ====================
        // Section: Added to Cart Popup ------
        $this->start_controls_section(
            'section_style_added_to_cart_popup',
            [
                'label' => esc_html__( 'Popup Notifications', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        // Added To Cart Text
        $this->add_control(
            'added_to_cart_popup_wrapper',
            [
                'label' => esc_html__( 'Wrapper', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'added_to_cart_popup_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FCFCFC',
                'selectors' => [
                    '{{WRAPPER}} .crt-added-to-cart-popup' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-added-to-compare-popup' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-added-to-wishlist-popup' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'added_to_cart_popup_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-added-to-cart-popup' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-added-to-compare-popup' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-added-to-wishlist-popup' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'added_to_cart_popup',
                'selector' => '{{WRAPPER}} .crt-added-to-cart-popup, {{WRAPPER}} .crt-added-to-compare-popup, {{WRAPPER}} .crt-added-to-wishlist-popup',
            ]
        );

        $this->add_responsive_control(
            'added_to_cart_popup_position',
            [
                'label' => esc_html__( 'Position', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'bottom',
                'options' => [
                    'top' => [
                        'title' => esc_html__( 'Top', 'crt-manage' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => esc_html__( 'Bottom', 'crt-manage' ),
                        'icon' => 'eicon-v-align-bottom',
                    ]
                ],
                'prefix_class' => 'crt-atc-popup-',
            ]
        );

        $this->add_control(
            'popup_notification_animation',
            [
                'label' => esc_html__( 'Entrance Animation', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => 'Default',
                    'scale-up' => 'Scale',
                    'fade' => 'Fade',
                    'slide-left' => 'Slide Left',
                    'skew' => 'Skew',
                ],
                'default' => 'default'
            ]
        );

        $this->add_control(
            'popup_notification_fade_out_in',
            [
                'label' => esc_html__( 'Fade Out', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 0,
                'max' => 15
            ]
        );

        $this->add_control(
            'popup_notification_animation_duration',
            [
                'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'min' => 0,
                'max' => 15,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-added-to-cart-popup' => 'animation-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-added-to-cart-popup-hide' => 'animation-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-added-to-wishlist-popup' => 'animation-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-added-to-wishlist-popup-hide' => 'animation-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-added-to-compare-popup' => 'animation-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-added-to-compare-popup-hide' => 'animation-duration: {{VALUE}}s'
                ]
            ]
        );

        // Added To Cart Text
        $this->add_control(
            'added_to_cart_popup_title_heading',
            [
                'label' => esc_html__( 'Text', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'added_to_cart_popup_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .crt-added-tc-title p:first-child' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-added-tw-title p:first-child' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-added-tw-title p:first-child' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'added_to_cart_popup_texts',
                'selector' => '{{WRAPPER}} .crt-added-tc-title p:first-child, {{WRAPPER}} .crt-added-tw-title p:first-child, {{WRAPPER}} .crt-added-tw-title p:first-child',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '',
                            'unit' => 'px'
                        ]
                    ]
                ]
            ]
        );

        $this->add_control(
            'added_to_cart_text_alignment',
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
                    ]
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .crt-added-tc-title p:first-child' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .crt-added-tw-title p:first-child' => 'text-align: {{VALUE}};',
                ]
            ]
        );

        // Results
        $this->add_control(
            'added_to_cart_popup_link_heading',
            [
                'label' => esc_html__( 'Link', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'added_to_cart_popup_link_hover_color',
            [
                'label'  => esc_html__( 'Link Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-added-tc-title a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-added-tw-title a' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'added_to_cart_popup_link_color',
            [
                'label'  => esc_html__( 'Link Hover Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-added-tc-title a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-added-tw-title a:hover' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'added_to_cart_popup_link',
                'selector' => '{{WRAPPER}} .crt-added-tc-title a, {{WRAPPER}} .crt-added-tw-title a',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '14',
                            'unit' => 'px'
                        ]
                    ]
                ]
            ]
        );

        $this->add_control(
            'added_to_cart_link_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-added-tc-title a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-added-tw-title a' => 'transition-duration: {{VALUE}}s'
                ]
            ]
        );

        $this->add_control(
            'added_to_cart_link_alignment',
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
                    ]
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .crt-added-tc-title p:last-child' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .crt-added-tw-title p:last-child' => 'text-align: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'added_to_cart_popup_border_type',
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
                    '{{WRAPPER}} .crt-added-to-cart-popup' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .crt-added-to-compare-popup' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .crt-added-to-wishlist-popup' => 'border-style: {{VALUE}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'added_to_cart_popup_border_width',
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
                    '{{WRAPPER}} .crt-added-to-cart-popup' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-added-to-compare-popup' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-added-to-wishlist-popup' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'added_to_cart_popup_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'added_to_cart_popup_border_radius',
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
                    '{{WRAPPER}} .crt-added-to-cart-popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-added-to-compare-popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-added-to-wishlist-popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    // '{{WRAPPER}} .crt-added-tc-popup-img' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                    // '{{WRAPPER}} .crt-added-tc-popup-img img' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}}',
                    // '{{WRAPPER}} .crt-added-tc-title' => 'border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-right-radius: {{BOTTOM}}{{UNIT}}'
                ]
            ]
        );

        $this->add_responsive_control(
            'added_to_cart_popup_text_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-added-tc-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-added-tw-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'added_to_cart_popup_margin',
            [
                'label' => esc_html__( 'Margin', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-added-to-cart-popup' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-added-to-compare-popup' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-added-to-wishlist-popup' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'added_to_cart_popup_width',
            [
                'label' => esc_html__( 'Popup Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 350,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-added-to-cart-popup' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-added-to-compare-popup' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-added-to-wishlist-popup' => 'width: {{SIZE}}{{UNIT}};'
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'added_to_cart_popup_img_size',
            [
                'label' => esc_html__( 'Img Size', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-added-to-cart-popup .crt-added-tc-popup-img' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-added-to-compare-popup .crt-added-tcomp-popup-img' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-added-to-wishlist-popup .crt-added-tw-popup-img' => 'width: {{SIZE}}{{UNIT}};'
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();
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
					'bottom' => 2,
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
					'{{WRAPPER}} .crt-grid-item-sharing .inner-block a' => 'transition-duration: {{VALUE}}s',
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
				],
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
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'mix-blend-mode: {{VALUE}}',
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


	public function add_control_categories_pointer_color_hr() {
		$this->add_control(
			'categories_pointer_color_hr',
			[
				'label'  => esc_html__( 'Hover Effect Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-product-categories .crt-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-product-categories .crt-pointer-item:after' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}


	public function add_control_categories_pointer() {
		$this->add_control(
			'categories_pointer',
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


	public function add_control_categories_pointer_height() {
		$this->add_control(
			'categories_pointer_height',
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
					'{{WRAPPER}} .crt-grid-product-categories .crt-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-product-categories .crt-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'categories_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}


	public function add_control_categories_pointer_animation() {
		$this->add_control(
			'categories_pointer_animation',
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
					'categories_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}


	public function add_control_tags_pointer_color_hr() {
		$this->add_control(
			'tags_pointer_color_hr',
			[
				'label'  => esc_html__( 'Hover Effect Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-product-tags .crt-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-product-tags .crt-pointer-item:after' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}


	public function add_control_tags_pointer() {
		$this->add_control(
			'tags_pointer',
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


	public function add_control_tags_pointer_height() {
		$this->add_control(
			'tags_pointer_height',
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
					'{{WRAPPER}} .crt-grid-product-tags .crt-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-product-tags .crt-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'tags_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}


	public function add_control_tags_pointer_animation() {
		$this->add_control(
			'tags_pointer_animation',
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
					'tags_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}


	public function add_control_add_to_cart_animation() {
		$this->add_control(
			'add_to_cart_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => 'crt-button-animations',
				'default' => 'crt-button-none',
			]
		);
	}


	public function add_control_add_to_cart_animation_height() {
		$this->add_control(
			'add_to_cart_animation_height',
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
					'add_to_cart_animation' => [ 
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


	public function add_control_atc_popup_repeater() {
		$this->add_control(
			'atc_popup_repeater',
			[
				'label' => esc_html__( 'Add to Cart Popup', 'crt-manage' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'atc_popup_product_id',
						'label' => esc_html__( 'Product', 'crt-manage' ),
						'type' => 'crt-ajax-select2',
						'options' => 'ajaxselect2/get_posts_by_post_type',
						'query_slug' => 'product',
						'multiple' => false,
						'label_block' => true,
					],
					[
						'name' => 'atc_popup_template_id',
						'label' => esc_html__( 'Select Template', 'crt-manage' ),
						'type' => 'crt-ajax-select2',
						'options' => 'ajaxselect2/get_posts_by_post_type',
						'query_slug' => 'elementor_library',
						'multiple' => false,
						'label_block' => true,
					],
				],
				'title_field' => '{{{ elementor.helpers.get  ( atc_popup_product_id, { id: atc_popup_product_id } ) }}}',
			]
		);
	}

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

        $this->add_control_query_selection();

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

        // Upgrade to Pro Notice
        Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'query_selection', ['pro-fr','pro-os','pro-us','pro-cs'] );

        $this->add_control_query_orderby();

        // Upgrade to Pro Notice
        Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'query_orderby', ['pro-rn'] );

        // Categories
        $this->add_control(
            'query_taxonomy_product_cat',
            [
                'label' => esc_html__( 'Categories', 'crt-manage' ),
                'type' => 'crt-ajax-select2',
                'options' => 'ajaxselect2/get_taxonomies',
                'query_slug' => 'product_cat',
                'multiple' => true,
                'label_block' => true,
                'condition' => [
                    'query_selection' => [ 'dynamic', 'onsale', 'featured' ],
                ],
            ]
        );

        // Tags
        $this->add_control(
            'query_taxonomy_product_tag',
            [
                'label' => esc_html__( 'Tags', 'crt-manage' ),
                'type' => 'crt-ajax-select2',
                'options' => 'ajaxselect2/get_taxonomies',
                'query_slug' => 'product_tag',
                'multiple' => true,
                'label_block' => true,
                'condition' => [
                    'query_selection' => [ 'dynamic', 'onsale', 'featured' ],
                ],
            ]
        );

        // Exclude
        $this->add_control(
            'query_exclude_products',
            [
                'label' => esc_html__( 'Exclude Products', 'crt-manage' ),
                'type' => 'crt-ajax-select2',
                'options' => 'ajaxselect2/get_posts_by_post_type',
                'query_slug' => 'product',
                'multiple' => true,
                'label_block' => true,
                'condition' => [
                    'query_selection!' => [ 'manual', 'onsale', 'current', 'upsell', 'cross-sell' ],
                ],
            ]
        );

        // Manual Selection
        $this->add_control(
            'query_manual_products',
            [
                'label' => esc_html__( 'Select Products', 'crt-manage' ),
                'type' => 'crt-ajax-select2',
                'options' => 'ajaxselect2/get_posts_by_post_type',
                'query_slug' => 'product',
                'multiple' => true,
                'label_block' => true,
                'condition' => [
                    'query_selection' => 'manual',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'query_posts_per_page',
            [
                'label' => esc_html__( 'Products Per Page', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 9,
                'min' => 0,
                'condition' => [
                    'query_selection!' => 'current',
                ],
            ]
        );

        $this->add_control(
            'query_offset',
            [
                'label' => esc_html__( 'Offset', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'condition' => [
                    'query_selection' => [ 'dynamic', 'current' ],
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
                'default' => 'No Products Found!',
                'condition' => [
                    'query_selection' => [ 'dynamic', 'current' ],
                ]
            ]
        );

        $this->add_control(
            'query_randomize',
            [
                'label' => esc_html__( 'Randomize Query', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'rand',
                'condition' => [
                    'query_selection' => [ 'manual', 'current' ],
                ]
            ]
        );

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
            'query_exclude_out_of_stock',
            [
                'label' => esc_html__( 'Exclude Out Of Stock', 'crt-manage' ),
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
            'current_query_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => sprintf( __( 'To set <strong>Posts per Page</strong> for all <strong>Shop Pages</strong>, navigate to <strong><a href="%s" target="_blank">CRT Builder > Settings<a></strong>.', 'crt-manage' ), admin_url( '?page=crt-manage' ) ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'query_selection' => 'current',
                ],
            ]
        );

        // if ( Utilities::is_new_free_user() && (!defined('CRT_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code()) ) {
        // 	$this->add_control(
        // 		'limit_grid_items_pro_notice',
        // 		[
        // 			'type' => Controls_Manager::RAW_HTML,
        // 			'raw' => 'More than <strong>12 Items</strong> in total<br> are available in the <strong><a href="https://crthemes.com/?ref=rea-plugin-panel-woo-grid-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
        // 			// 'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'. admin_url('admin.php?page=crt-addons-pricing') .'" target="_blank">Pro version</a></strong>',
        // 			'content_classes' => 'crt-pro-notice',
        // 		]
        // 	);
        // }

        // $this->add_control(
        // 	'post_meta_keys_filter',
        // 	[
        // 		'type' => Controls_Manager::HIDDEN,
        // 		'default' => json_encode( $post_meta_keys[0] ),
        // 	]
        // );

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
        Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'layout_select', ['pro-ms'] );

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
                'default' => [
                    'size' => 20,
                ],
                'widescreen_default' => [
                    'size' => 20,
                ],
                'laptop_default' => [
                    'size' => 20,
                ],
                'tablet_extra_default' => [
                    'size' => 20,
                ],
                'tablet_default' => [
                    'size' => 20,
                ],
                'mobile_extra_default' => [
                    'size' => 20,
                ],
                'mobile_default' => [
                    'size' => 20,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
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
                'default' => [
                    'size' => 30,
                ],
                'widescreen_default' => [
                    'size' => 30,
                ],
                'laptop_default' => [
                    'size' => 30,
                ],
                'tablet_extra_default' => [
                    'size' => 30,
                ],
                'tablet_default' => [
                    'size' => 30,
                ],
                'mobile_extra_default' => [
                    'size' => 30,
                ],
                'mobile_default' => [
                    'size' => 30,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'condition' => [
                    'layout_select' => [ 'fitRows', 'masonry', 'list' ],
                ],
            ]
        );

        $this->add_control_sort_and_results_count();


        $this->add_responsive_control(
            'layout_filters',
            [
                'label' => esc_html__( 'Show Filters', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'selectors_dictionary' => [
                    '' => 'none',
                    'yes' => 'block'
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-filters' => 'display:{{VALUE}};',
                ],
                'render_type' => 'template',
                // 'separator' => 'before',
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

        $this->add_control_layout_animation();

        // Upgrade to Pro Notice
        Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'layout_animation', ['pro-fd', 'pro-fs'] );

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
                'frontend_available' => true,
                'default' => 2,
                'render_type' => 'template',
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
//        Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'layout_slider_dots_position', ['pro-vr'] );

        $this->add_control_stack_layout_slider_autoplay();

        $this->add_control(
            'layout_slider_loop',
            [
                'label' => esc_html__( 'Infinite Loop', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
                'separator' => 'after',
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
        // Section: Upsell / Cross-sell Title
        $this->start_controls_section(
            'section_grid_linked_products',
            [
                'label' => esc_html__( 'Upsell / Cross-sell Title', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'query_selection' => ['upsell', 'cross-sell'],
                    // 'layout_select!' => 'slider'
                ]
            ]
        );

        $this->add_control(
            'grid_linked_products_heading',
            [
                'label' => esc_html__( 'Heading', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'You may be interested in...',
                'condition' => [
                    'query_selection' => ['upsell', 'cross-sell'],
                ]
            ]
        );

        $this->add_control(
            'grid_linked_products_heading_tag',
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
                    'query_selection' => ['upsell'],
                    'grid_linked_products_heading!' => ''
                ]
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
                'options' => $element_select + Utilities::get_woo_taxonomies(),
                'separator' => 'after'
            ]
        );

        $repeater->add_control(
            'wishlist_compare_video_tutorial',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<ul><li><a href="https://www.youtube.com/watch?v=wis1rQTn1tg" target="_blank" style="color: #93003c;"><strong>Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></strong></a></li></ul>', 'crt-manage' ),
                'separator' => 'after',
                // 'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'element_select' => ['wishlist-button', 'compare-button']
                ]
            ]
        );

        // Upgrade to Pro Notice
        Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'woo-grid', 'element_select', ['pro-ws', 'pro-cm'] );

//        Utilities::upgrade_expert_notice( $repeater, Controls_Manager::RAW_HTML, 'grid', 'element_select', ['pro-ws', 'pro-cm'] );

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
                        'likes',
                        'sharing',
                        'lightbox',
                        'separator',
                        'post_format',
                        'status',
                        'price',
                        'rating',
                        'add-to-cart',
                        'wishlist-button',
                        'compare-button'
                    ],
                ],
                'separator' => 'after'
            ]
        );

        $repeater->add_control(
            'element_sale_dates_layout',
            [
                'label' => esc_html__( 'Layout', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline' => esc_html__( 'Inline', 'crt-manage' ),
                    'block' => esc_html__( 'Block', 'crt-manage' ),
                ],
                'condition' => [
                    'element_select' => [
                        'sale_dates',
                    ]
                ]
            ]
        );

        $repeater->add_control(
            'element_sale_dates_sep',
            [
                'label' => esc_html__( 'Separator', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'default' => ' - ',
                'condition' => [
                    'element_select' => [
                        'sale_dates',
                    ],
                    'element_sale_dates_layout' => 'inline'
                ],
                'separator' => 'after'
            ]
        );

        $repeater->add_control( 'element_custom_field', $this->add_repeater_args_element_custom_field() );

        $repeater->add_control( 'element_custom_field_btn_link', $this->add_repeater_args_element_custom_field_btn_link() );

        $repeater->add_control( 'element_custom_field_style', $this->add_repeater_args_element_custom_field_style() );

        $repeater->add_control( 'element_custom_field_new_tab', $this->add_repeater_args_element_custom_field_new_tab() );

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
                'query_slug' => 'product_cat',
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
                'query_slug' => 'product_cat',
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
            'element_rating_style',
            [
                'label' => esc_html__( 'Select Icon', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'style-1' => 'Icon 1',
                    'style-2' => 'Icon 2',
                ],
                'default' => 'style-2',
                'condition' => [
                    'element_select' => 'rating',
                ]
            ]
        );

        $repeater->add_control(
            'element_rating_score',
            [
                'label' => esc_html__( 'Show Score', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => 'rating',
                ],
            ]
        );

        $repeater->add_control(
            'element_rating_unmarked_style',
            [
                'label' => esc_html__( 'Unmarked Style', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'solid' => [
                        'title' => esc_html__( 'Solid', 'crt-manage' ),
                        'icon' => 'eicon-star',
                    ],
                    'outline' => [
                        'title' => esc_html__( 'Outline', 'crt-manage' ),
                        'icon' => 'eicon-star-o',
                    ],
                ],
                'default' => 'outline',
                'condition' => [
                    'element_select' => 'rating',
                    'element_rating_score!' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'element_status_offstock',
            [
                'label' => esc_html__( 'Show Out of Stock Badge', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => 'status',
                ],
            ]
        );

        $repeater->add_control(
            'element_status_featured',
            [
                'label' => esc_html__( 'Show Featured Badge', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => 'status',
                ],
            ]
        );

        $repeater->add_control(
            'element_addcart_simple_txt',
            [
                'label' => esc_html__( 'Simple Item Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Add to Cart',
                'condition' => [
                    'element_select' => 'add-to-cart',
                ]
            ]
        );

        $repeater->add_control(
            'element_addcart_grouped_txt',
            [
                'label' => esc_html__( 'Grouped Item Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Select Options',
                'condition' => [
                    'element_select' => 'add-to-cart',
                ]
            ]
        );

        $repeater->add_control(
            'element_addcart_variable_txt',
            [
                'label' => esc_html__( 'Variable Item Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'View Products',
                'separator' => 'after',
                'condition' => [
                    'element_select' => 'add-to-cart',
                ]
            ]
        );

        $repeater->add_control( 'element_show_added_tc_popup', $this->add_repeater_args_element_show_added_tc_popup() );

        $repeater->add_control( 'element_show_added_to_wishlist_popup', $this->add_repeater_args_element_show_added_to_wishlist_popup() );

        $repeater->add_control( 'element_show_added_to_compare_popup', $this->add_repeater_args_element_show_added_to_compare_popup() );

        $repeater->add_control(
            'element_open_links_in_new_tab',
            [
                'label' => esc_html__( 'Open Links in New Tab', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'element_select' => ['wishlist-button', 'compare-button']
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
                        'separator',
                        'status',
                        'price',
                        'sale_dates',
                        'rating',
                        'add-to-cart',
                        'wishlist-button',
                        'compare-button',
                        'excerpt'
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
                        'separator',
                        'status',
                        'price',
                        'sale_dates',
                        'rating',
                        'add-to-cart',
                        'wishlist-button',
                        'compare-button',
                        'excerpt'
                    ],
                    'element_extra_text_pos!' => 'none'
                ]
            ]
        );

        $repeater->add_control(
            'show_sale_starts_date',
            [
                'label' => esc_html__( 'Sale Starts Date', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => [
                        'sale_dates'
                    ]
                ],
            ]
        );

        $repeater->add_control(
            'element_sale_starts_text',
            [
                'label' => esc_html__( 'Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'element_select' => [
                        'sale_dates'
                    ],
                    'show_sale_starts_date' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'show_sale_ends_date',
            [
                'label' => esc_html__( 'Sale Ends Date', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => [
                        'sale_dates'
                    ]
                ],
            ]
        );

        $repeater->add_control(
            'element_sale_ends_text',
            [
                'label' => esc_html__( 'Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'element_select' => [
                        'sale_dates'
                    ],
                    'show_sale_ends_date' => 'yes'
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
                        'separator',
                        'likes',
                        'sharing',
                        'status',
                        'price',
                        'sale_dates',
                        'rating',
                        'excerpt',
                        'wishlist-button',
                        'compare-button'
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
                        'separator',
                        'likes',
                        'sharing',
                        'status',
                        'price',
                        'rating',
                        'wishlist-button',
                        'compare-button'
                    ],
                    'element_extra_icon_pos!' => 'none'
                ]
            ]
        );

        $repeater->add_control(
            'show_icon',
            [
                'label' => esc_html__( 'Show Icon', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'element_select' => [
                        'wishlist-button',
                        'compare-button'
                    ]
                ]
            ]
        );

        $repeater->add_control(
            'show_text',
            [
                'label' => esc_html__( 'Show Text', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'element_select' => [
                        'wishlist-button',
                        'compare-button'
                    ]
                ]
            ]
        );

        $repeater->add_control(
            'add_to_wishlist_text',
            [
                'label' => esc_html__( 'Add Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Add to Wishlist',
                'condition' => [
                    'element_select' => [
                        'wishlist-button'
                    ]
                ]
            ]
        );

        $repeater->add_control(
            'add_to_compare_text',
            [
                'label' => esc_html__( 'Add Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Add to Compare',
                'condition' => [
                    'element_select' => [
                        'compare-button'
                    ]
                ]
            ]
        );

        $repeater->add_control(
            'remove_from_wishlist_text',
            [
                'label' => esc_html__( 'Remove Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Remove from Wishlist',
                'condition' => [
                    'element_select' => [
                        'wishlist-button'
                    ]
                ]
            ]
        );

        $repeater->add_control(
            'remove_from_compare_text',
            [
                'label' => esc_html__( 'Remove Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Remove from Compare',
                'condition' => [
                    'element_select' => [
                        'compare-button'
                    ]
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

        // Upgrade to Pro Notice
        Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'woo-grid', 'element_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt',] );

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
        Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'woo-grid', 'element_animation_timing', Utilities::crt_animation_timing_pro_conditions() );

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
                        'element_select' => 'status',
                        'element_location' => 'over',
                        'element_align_vr' => 'middle',
                        'element_align_hr' => 'middle',
                        'element_animation' => 'fade-in',
                    ],
                    [
                        'element_select' => 'product_cat',
                    ],
                    [
                        'element_select' => 'title',
                    ],
                    [
                        'element_select' => 'rating',
                    ],
                    [
                        'element_select' => 'price',
                    ],
                    [
                        'element_select' => 'add-to-cart',
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
        Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'overlay_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt',] );

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
        Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'overlay_animation_timing', Utilities::crt_animation_timing_pro_conditions() );

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
        Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'image_effects_animation_timing', Utilities::crt_animation_timing_pro_conditions() );

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
        // Section: Lightbox Popup ----
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

        $this->end_controls_section(); // End Controls Section

        $this->add_section_grid_sorting();

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
                'options' => [
                    'product_cat' => esc_html__( 'Categories', 'crt-manage' ),
                    'product_tag' => esc_html__( 'Tags', 'crt-manage' ),
                ],
                'default' => 'product_cat',
                'separator' => 'after',
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
                'type' => Controls_Manager::HIDDEN,
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
                'default' => 'All',
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
        Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'filters_animation', ['pro-fd', 'pro-fs'] );

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
        Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'pagination_type', ['pro-is', 'pro-nb'] );

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
                'default' => 'no',
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
                'default' => 'no',
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
                    'pagination_first_last' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'pagination_disabled_arrows',
            [
                'label' => esc_html__( 'Show Disabled Buttons', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
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
                'prefix_class' => 'crt-item-styles-'
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
                    '{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item' => 'background-color: {{VALUE}}'
                ]
            ]
        );

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
                ]
            ]
        );

        $this->add_control_grid_item_even_bg_color();

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

        $this->add_responsive_control(
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
                'size_units' => [ 'px', '%' ],
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
                ],
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
                ],
            ]
        );

        $this->add_responsive_control(
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
                'default' => 0.3,
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
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-excerpt .inner-block' => 'color: {{VALUE}}',
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
        // Section: Categories -------
        $this->start_controls_section(
            'section_style_categories',
            [
                'label' => esc_html__( 'Categories', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs( 'tabs_grid_categories_style' );

        $this->start_controls_tab(
            'tab_grid_categories_normal',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'categories_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'categories_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'categories_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block a' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'categories_extra_text_color',
            [
                'label'  => esc_html__( 'Extra Text Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block span[class*="crt-grid-extra-text"]' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'categories_extra_icon_color',
            [
                'label'  => esc_html__( 'Extra Icon Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block [class*="crt-grid-extra-icon"] i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block [class*="crt-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_categories_hover',
            [
                'label' => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'categories_color_hr',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-product-categories .crt-pointer-item:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-product-categories .crt-pointer-item:after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'categories_bg_color_hr',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block a:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'categories_border_color_hr',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block a:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control_categories_pointer_color_hr();

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control_categories_pointer();

        $this->add_control_categories_pointer_height();

        $this->add_control_categories_pointer_animation();

        $this->add_control(
            'categories_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-grid-product-categories .crt-pointer-item:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-grid-product-categories .crt-pointer-item:after' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'categories_typography',
                'selector' => '{{WRAPPER}} .crt-grid-product-categories'
            ]
        );

        $this->add_control(
            'categories_border_type',
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
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'categories_border_width',
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
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'categories_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'categories_text_spacing',
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-categories .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-product-categories .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'categories_icon_spacing',
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-categories .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-product-categories .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'categories_gutter',
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
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'categories_padding',
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
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'categories_margin',
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
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'categories_radius',
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
                    '{{WRAPPER}} .crt-grid-product-categories .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // Styles ====================
        // Section: Tags -------------
        $this->start_controls_section(
            'section_style_tags',
            [
                'label' => esc_html__( 'Tags', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs( 'tabs_grid_tags_style' );

        $this->start_controls_tab(
            'tab_grid_tags_normal',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'tags_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tags_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tags_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block a' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tags_extra_text_color',
            [
                'label'  => esc_html__( 'Extra Text Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block span[class*="crt-grid-extra-text"]' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tags_extra_icon_color',
            [
                'label'  => esc_html__( 'Extra Icon Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block [class*="crt-grid-extra-icon"] i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block [class*="crt-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_tags_hover',
            [
                'label' => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'tags_color_hr',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-product-tags .crt-pointer-item:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-product-tags .crt-pointer-item:after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tags_bg_color_hr',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block a:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tags_border_color_hr',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block a:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control_tags_pointer_color_hr();

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control_tags_pointer();

        $this->add_control_tags_pointer_height();

        $this->add_control_tags_pointer_animation();

        $this->add_control(
            'tags_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-grid-product-tags .crt-pointer-item:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-grid-product-tags .crt-pointer-item:after' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tags_typography',
                'selector' => '{{WRAPPER}} .crt-grid-product-tags'
            ]
        );

        $this->add_control(
            'tags_border_type',
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
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tags_border_width',
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
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'tags_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'tags_text_spacing',
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-tags .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-product-tags .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tags_icon_spacing',
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-product-tags .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-product-tags .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tags_gutter',
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
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tags_padding',
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
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'tags_margin',
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
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'tags_radius',
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
                    '{{WRAPPER}} .crt-grid-product-tags .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // Styles ====================
        // Section: Rating -----------
        $this->start_controls_section(
            'section_style_product_rating',
            [
                'label' => esc_html__( 'Rating', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'product_rating_color',
            [
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffd726',
                'selectors' => [
                    '{{WRAPPER}} .crt-woo-rating i:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'product_rating_unmarked_color',
            [
                'label' => esc_html__( 'Unmarked Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#D2CDCD',
                'selectors' => [
                    '{{WRAPPER}} .crt-woo-rating i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .crt-woo-rating .crt-rating-unmarked svg' => 'fill: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'product_rating_score_color',
            [
                'label' => esc_html__( 'Score Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffd726',
                'selectors' => [
                    '{{WRAPPER}} .crt-woo-rating span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .crt-woo-rating .crt-rating-marked svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'product_rating_size',
            [
                'label' => esc_html__( 'Size', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 22,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-woo-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-woo-rating svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'product_rating_gutter',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Gutter', 'crt-manage' ),
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
                    '{{WRAPPER}} .crt-woo-rating i' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-woo-rating .crt-rating-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-woo-rating span.crt-rating-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-woo-rating span:not(.crt-rating-icon, .crt-rating-icon span)' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'product_rating_typography',
                'selector' => '{{WRAPPER}} .crt-woo-rating span'
            ]
        );

        $this->add_responsive_control(
            'product_rating_margin',
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
                    '{{WRAPPER}} .crt-grid-item-rating .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

        // Styles ====================
        // Section: Status -----------
        $this->start_controls_section(
            'section_style_product_status',
            [
                'label' => esc_html__( 'Status', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'product_status_os_color',
            [
                'label'  => esc_html__( 'On Sale Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > .crt-woo-onsale' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_status_os_bg_color',
            [
                'label'  => esc_html__( 'On Sale BG Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > .crt-woo-onsale' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_status_os_border_color',
            [
                'label'  => esc_html__( 'On Sale Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > .crt-woo-onsale' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'product_status_ft_color',
            [
                'label'  => esc_html__( 'Featured Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > .crt-woo-featured' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_status_ft_bg_color',
            [
                'label'  => esc_html__( 'Featured BG Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > .crt-woo-featured' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_status_ft_border_color',
            [
                'label'  => esc_html__( 'Featured Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > .crt-woo-featured' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'product_status_oos_color',
            [
                'label'  => esc_html__( 'Out of Stock Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > .crt-woo-outofstock' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_status_oos_bg_color',
            [
                'label'  => esc_html__( 'Out of Stock BG Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > .crt-woo-outofstock' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_status_oos_border_color',
            [
                'label'  => esc_html__( 'Out of Stock Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > .crt-woo-outofstock' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'product_status_typography',
                'selector' => '{{WRAPPER}} .crt-grid-item-status .inner-block > span'
            ]
        );

        $this->add_control(
            'product_status_border_type',
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
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > span' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'product_status_border_width',
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
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'product_status_border_type!' => 'none',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'product_status_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 3,
                    'right' => 10,
                    'bottom' => 3,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'product_status_margin',
            [
                'label' => esc_html__( 'Margin', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 0,
                    'right' => 5,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'product_status_radius',
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
                    '{{WRAPPER}} .crt-grid-item-status .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'product_status_shadow',
                'selector' => '{{WRAPPER}} .crt-grid-item-status .inner-block > span',
            ]
        );

        $this->end_controls_section();

        // Styles ====================
        // Section: Price ------------
        $this->start_controls_section(
            'section_style_product_price',
            [
                'label' => esc_html__( 'Price', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'product_price_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-price .inner-block > span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_price_old_color',
            [
                'label'  => esc_html__( 'Old Price Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-price .inner-block > span del' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_price_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-price .inner-block > span' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_price_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-price .inner-block > span' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'product_price_typography',
                'selector' => '{{WRAPPER}} .crt-grid-item-price .inner-block > span'
            ]
        );

        $this->add_control(
            'product_price_old_font_size',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Old Price Font Size', 'crt-manage' ),
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-price .inner-block > span del' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'product_price_border_type',
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
                    '{{WRAPPER}} .crt-grid-item-price .inner-block > span' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'product_price_border_width',
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
                    '{{WRAPPER}} .crt-grid-item-price .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'product_price_border_type!' => 'none',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'product_price_padding',
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
                    '{{WRAPPER}} .crt-grid-item-price .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'product_price_margin',
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
                    '{{WRAPPER}} .crt-grid-item-price .inner-block > span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'product_price_radius',
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
                    '{{WRAPPER}} .crt-grid-item-price .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'product_price_shadow',
                'selector' => '{{WRAPPER}} .crt-grid-item-price .inner-block > span',
            ]
        );

        $this->end_controls_section();

        // Styles ====================
        // Section: Sale Dates ------------
        $this->start_controls_section(
            'section_style_product_sale_dates',
            [
                'label' => esc_html__( 'Sale Dates', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'product_sale_dates_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sale_dates .inner-block > span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_sale_dates_old_color',
            [
                'label'  => esc_html__( 'Text Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sale_dates .inner-block span.crt-grid-extra-text-left' => 'color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'product_sale_dates_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sale_dates .inner-block > span' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_sale_dates_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-sale_dates .inner-block > span.crt-sale-dates' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'product_sale_dates_typography',
                'selector' => '{{WRAPPER}} .crt-grid-item-sale_dates .inner-block > .crt-sale-dates'
            ]
        );

        $this->add_control(
            'product_sale_dates_border_type',
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
                    '{{WRAPPER}} .crt-grid-item-sale_dates .inner-block > .crt-sale-dates' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'product_sale_dates_border_width',
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
                    '{{WRAPPER}} .crt-grid-item-sale_dates .inner-block > .crt-sale-dates' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'product_sale_dates_border_type!' => 'none',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'product_sale_dates_padding',
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
                    '{{WRAPPER}} .crt-grid-item-sale_dates .inner-block > .crt-sale-dates' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'product_sale_dates_margin',
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
                    '{{WRAPPER}} .crt-grid-item-sale_dates .inner-block > .crt-sale-dates' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'product_sale_dates_radius',
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
                    '{{WRAPPER}} .crt-grid-item-sale_dates .inner-block > .crt-sale-dates' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
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

        // Styles ====================
        // Section: Add to Cart ------
        $this->start_controls_section(
            'section_style_add_to_cart',
            [
                'label' => esc_html__( 'Add to Cart', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs( 'tabs_grid_add_to_cart_style' );

        $this->start_controls_tab(
            'tab_grid_add_to_cart_normal',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'add_to_cart_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'add_to_cart_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'add_to_cart_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'add_to_cart_box_shadow',
                'selector' => '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_add_to_cart_hover',
            [
                'label' => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'add_to_cart_color_hr',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'add_to_cart_bg_color_hr',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a.crt-button-none:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a.added_to_cart:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a:after' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'add_to_cart_border_color_hr',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a:hover' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'add_to_cart_box_shadow_hr',
                'selector' => '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block :hover a',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'add_to_cart_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control_add_to_cart_animation();

        $this->add_control(
            'add_to_cart_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a:after' => 'transition-duration: {{VALUE}}s',
                ],
            ]
        );

        $this->add_control_add_to_cart_animation_height();

        $this->add_control(
            'add_to_cart_typo_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'add_to_cart_typography',
                'selector' => '{{WRAPPER}} .crt-grid-item-add-to-cart a'
            ]
        );

        $this->add_control(
            'add_to_cart_icon_spacing',
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
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'add_to_cart_border_type',
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
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a' => 'border-style: {{VALUE}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'add_to_cart_border_width',
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
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'add_to_cart_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'add_to_cart_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 5,
                    'right' => 15,
                    'bottom' => 5,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'add_to_cart_margin',
            [
                'label' => esc_html__( 'Margin', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 15,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'add_to_cart_radius',
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
                    '{{WRAPPER}} .crt-grid-item-add-to-cart .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // Tab: Style ==============
        // Section: Button Styles ------------
        $this->start_controls_section(
            'section_wishlist_button_styles',
            [
                'label' => esc_html__( 'Add to Wishlist', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_btn_styles' );

        $this->start_controls_tab(
            'tab_btn_normal',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .crt-wishlist-add span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-wishlist-add i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-wishlist-add svg' => 'fill: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'btn_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-wishlist-add' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-wishlist-add' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'selector' => '{{WRAPPER}} .crt-wishlist-add, {{WRAPPER}} .crt-wishlist-remove',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_typography',
                'selector' => '{{WRAPPER}} .crt-wishlist-add span, {{WRAPPER}} .crt-wishlist-add i, .crt-wishlist-remove span, {{WRAPPER}} .crt-wishlist-remove i',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '16',
                            'unit' => 'px',
                        ],
                    ],
                ]
            ]
        );

        $this->add_control(
            'btn_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-wishlist-add' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-wishlist-add span' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-wishlist-add i' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-wishlist-remove' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-wishlist-remove span' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-wishlist-remove i' => 'transition-duration: {{VALUE}}s'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .crt-wishlist-add:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-wishlist-add:hover svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .crt-wishlist-add:hover span' => 'color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'btn_hover_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .crt-wishlist-add:hover' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'btn_hover_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-wishlist-add:hover' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow_hr',
                'selector' => '{{WRAPPER}} .crt-wishlist-add:hover, WRAPPER}} .crt-wishlist-remove:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_remove_btn',
            [
                'label' => esc_html__( 'Remove', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'remove_btn_text_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .crt-wishlist-remove span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-wishlist-remove i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-wishlist-remove svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .crt-wishlist-remove:hover span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-wishlist-remove:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-wishlist-remove:hover svg' => 'fill: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'remove_btn_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4F40',
                'selectors' => [
                    '{{WRAPPER}} .crt-wishlist-remove' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-wishlist-remove:hover' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'remove_btn_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-wishlist-remove' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-wishlist-remove:hover' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 5,
                    'right' => 15,
                    'bottom' => 5,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-wishlist-add' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-wishlist-remove' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__( 'Margin', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 5,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    // '{{WRAPPER}} .crt-wishlist-add' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    // '{{WRAPPER}} .crt-wishlist-remove' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-item-wishlist-button .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
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
                    '{{WRAPPER}} .crt-wishlist-add' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .crt-wishlist-remove' => 'border-style: {{VALUE}};'
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
                    '{{WRAPPER}} .crt-wishlist-add' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-wishlist-remove' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-wishlist-add' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-wishlist-remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        // Tab: Style ==============
        // Section: Button Styles ------------
        $this->start_controls_section(
            'section_compare_button_styles',
            [
                'label' => esc_html__( 'Add to Compare', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'comp_tabs_btn_styles' );

        $this->start_controls_tab(
            'comp_tab_btn_normal',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'comp_btn_text_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .crt-compare-add span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-compare-add i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-compare-add svg' => 'fill: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'comp_btn_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-compare-add' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'comp_btn_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-compare-add' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'comp_btn_box_shadow',
                'selector' => '{{WRAPPER}} .crt-compare-add, {{WRAPPER}} .crt-compare-remove',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'comp_btn_typography',
                'selector' => '{{WRAPPER}} .crt-compare-add span, {{WRAPPER}} .crt-compare-add i, .crt-compare-remove span, {{WRAPPER}} .crt-compare-remove i',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '16',
                            'unit' => 'px',
                        ],
                    ],
                ]
            ]
        );

        $this->add_control(
            'comp_btn_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-compare-add' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-compare-add span' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-compare-add i' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-compare-remove' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-compare-remove span' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-compare-remove i' => 'transition-duration: {{VALUE}}s'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'comp_tab_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'comp_btn_hover_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .crt-compare-add:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-compare-add:hover svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .crt-compare-add:hover span' => 'color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'comp_btn_hover_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .crt-compare-add:hover' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'comp_btn_hover_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-compare-add:hover' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'comp_btn_box_shadow_hr',
                'selector' => '{{WRAPPER}} .crt-compare-add:hover, WRAPPER}} .crt-compare-remove:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'comp_tab_remove_btn',
            [
                'label' => esc_html__( 'Remove', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'comp_remove_btn_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .crt-compare-remove span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-compare-remove i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-compare-remove svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .crt-compare-remove:hover span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-compare-remove:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-compare-remove:hover svg' => 'fill: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'comp_remove_btn_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4F40',
                'selectors' => [
                    '{{WRAPPER}} .crt-compare-remove' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-compare-remove:hover' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'comp_remove_btn_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-compare-remove' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-compare-remove:hover' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'comp_button_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 5,
                    'right' => 15,
                    'bottom' => 5,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-compare-add' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-compare-remove' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'comp_button_margin',
            [
                'label' => esc_html__( 'Margin', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 5,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    // '{{WRAPPER}} .crt-compare-add' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    // '{{WRAPPER}} .crt-compare-remove' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-item-compare-button .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'comp_button_border_type',
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
                    '{{WRAPPER}} .crt-compare-add' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .crt-compare-remove' => 'border-style: {{VALUE}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'comp_button_border_width',
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
                    '{{WRAPPER}} .crt-compare-add' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-compare-remove' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'button_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'comp_button_border_radius',
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
                    '{{WRAPPER}} .crt-compare-add' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-compare-remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->add_section_added_to_cart_popup();

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
                'default' => '#FFFFFF',
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
                    'size' => 10,
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
                'default' => '#9C9C9C',
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
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
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
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
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
                'default' => '#E8E8E8',
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
                'default' => '#E8E8E8',
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
                'default' => '#E8E8E8',
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
                'default' => '#E8E8E8',
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

        // Styles ====================
        // Section: Upsell / Cross-sell Title
        $this->start_controls_section(
            'section_style_linked_products',
            [
                'label' => esc_html__( 'Upsell / Cross-sell Title', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'query_selection' => ['upsell', 'cross-sell'],
                    // 'layout_select!' => 'slider'
                ]
            ]
        );

        $this->add_control(
            'linked_products_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-linked-products-heading' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'linked_products',
                'selector' => '{{WRAPPER}} .crt-grid-linked-products-heading *'
            ]
        );

        $this->add_responsive_control(
            'linked_products_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 3,
                    'right' => 15,
                    'bottom' => 3,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-linked-products-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'linked_products_distance_from_grid',
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
                    '{{WRAPPER}} .crt-grid-linked-products-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ]
                // 'separator' => 'before'
            ]
        );

        $this->add_control(
            'linked_products_alignment',
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
                    ]
                ],
                'default' => 'left',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .crt-grid-linked-products-heading *' => 'text-align: {{VALUE}};',
                ]
            ]
        );

        $this->end_controls_section(); // End Controls Section

        $this->add_section_style_sort_and_results();

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
                'default' => '#FFFFFF',
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
                    '{{WRAPPER}} .crt-pointer-item:before' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-pointer-item:after' => 'transition-duration: {{VALUE}}s',
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
                'size_units' => [ 'px' ],
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
                    'layout_pagination' => 'yes',
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
                'default' => '#FFFFFF',
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
                'default' => '#FFFFFF',
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
                'selector' => '{{WRAPPER}} .crt-grid-pagination'
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
                    'size' => 30,
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
                    '{{WRAPPER}} .crt-grid-pagination a' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-pagination > div > span' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'margin-right: {{SIZE}}{{UNIT}};',
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

        // Styles =======================
        // Section: Custom Field Style 1
        $this->add_section_style_custom_field1();

        // Styles =======================
        // Section: Custom Field Style 2
        $this->add_section_style_custom_field2();
    }


    // Render Sort & Results
	public function render_grid_sorting( $settings, $posts ) {
		if (isset($settings['layout_sort_and_results_count']) && 'yes' === $settings['layout_sort_and_results_count']) {
			$catalog_orderby_options = [
				'menu_order' => esc_html__('Default Sorting', 'crt-manage'),
				'date' => esc_html__('Latest', 'crt-manage'),
				'popularity' => esc_html__('Popularity', 'crt-manage'),
				'rating' => esc_html__('Average Rating', 'crt-manage'),
				'price' => esc_html__('Price: Low to High', 'crt-manage'),
				'price-desc' => esc_html__('Price: High to Low', 'crt-manage'),
				'title' => esc_html__('Title: A to Z', 'crt-manage'),
				'title-desc' => esc_html__('Title: Z to A', 'crt-manage'),
			];

			$orderby = '';

			if ( get_option('woocommerce_default_catalog_orderby') ) {
				$orderby = get_option('woocommerce_default_catalog_orderby');
			}

			if ( isset( $_GET['orderby'] ) ) {
				$orderby = $_GET['orderby'];
			}
			
			echo '<div class="crt-grid-sorting-wrap">';
			
			if ( '' !== $settings['sort_heading'] ) {
				$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
				$sort_heading_tag = Utilities::validate_html_tags_wl( $settings['sort_heading_tag'], 'h2', $tags_whitelist );

				echo '<div class="crt-grid-sort-heading">';
					echo '<'. $sort_heading_tag .'>'. esc_html__( $settings['sort_heading'] ) .'</'. $sort_heading_tag .'>';
				
					if ( 'above' === $settings['sort_select_position'] ) {
						?>
						<div class="crt-grid-orderby">
							<form action="<?php echo Utilities::get_shop_url([]); ?>" method="get">
							<!-- DROPDOWN STYLE -->
								<span>
									<i class="crt-orderby-icon fas fa-angle-down"></i>
									<select name="orderby" class="orderby" aria-label="<?php echo esc_attr__('Shop order', 'crt-manage'); ?>">
										<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
											<option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>><?php echo esc_html($name); ?></option>
										<?php endforeach; ?>
									</select>
								</span>

								<?php
								// Preserve search parameters
								if ( isset( $_GET['s'] ) ) {
									echo '<input type="hidden" name="s" value="'. esc_attr($_GET['s']) .'"/>';
								}
								
								if ( isset( $_GET['post_type'] ) ) {
									echo '<input type="hidden" name="post_type" value="'. esc_attr($_GET['post_type']) .'"/>';
								}

								if ( isset( $_GET['psearch'] ) ) {
									echo '<input type="hidden" name="psearch" value="'. esc_attr($_GET['psearch']) .'"/>';
								}
								
								if ( isset( $_GET['filter_rating'] ) ) {
									echo '<input type="hidden" name="filter_rating" value="'. esc_attr($_GET['filter_rating']) .'"/>';
								}
								
								if ( isset( $_GET['filter_product_cat'] ) ) {
									echo '<input type="hidden" name="filter_product_cat" value="'. esc_attr($_GET['filter_product_cat']) .'"/>';
								}
								
								if ( isset( $_GET['filter_product_tag'] ) ) {
									echo '<input type="hidden" name="filter_product_tag" value="'. esc_attr($_GET['filter_product_tag']) .'"/>';
								}
								
								if ( isset( $_GET['min_price'] ) ) {
									echo '<input type="hidden" name="min_price" value="'. esc_attr($_GET['min_price']) .'"/>';
								}
								
								if ( isset( $_GET['max_price'] ) ) {
									echo '<input type="hidden" name="max_price" value="'. esc_attr($_GET['max_price']) .'"/>';
								}

								// Handle attribute filters
								if ( $_chosen_attributes = WC()->query->get_layered_nav_chosen_attributes() ) {
									foreach ( $_chosen_attributes as $name => $data ) {
										$filter_name = wc_attribute_taxonomy_slug( $name );
										reset($_chosen_attributes);
										if ( $name === key($_chosen_attributes) ) {
											echo '<input type="hidden" name="crtfilters" value="sort"/>';
										}
										
										if ( isset($_GET['query_type_' . $filter_name]) ) {
											echo '<input type="hidden" name="query_type_'. esc_attr($filter_name) .'" value="or"/>';
										}

										if ( isset($_GET['filter_' . $filter_name]) ) {
											echo '<input type="hidden" name="filter_'. esc_attr($filter_name) .'" value="'. esc_attr($_GET['filter_' . $filter_name]) .'"/>';
										}
									}
								}
								?>
							</form>
						</div>
						<?php
					}
				echo '</div>';
			}
			
			echo '<div class="crt-grid-sorting-inner-wrap">';
					?>
					<div class="crt-products-result-count">
						<p class="woocommerce-result-count">
							<?php echo sprintf(esc_html__("Showing 1–1 of %u results", 'crt-manage'), $posts->found_posts); ?>
						</p>
					</div>
					<?php
				
				if ( 'below' === $settings['sort_select_position'] ) {
					?>
					<div class="crt-grid-orderby">
						<form action="<?php echo Utilities::get_shop_url([]); ?>" method="get">
							<span>
								<i class="crt-orderby-icon fas fa-angle-down"></i>
								<select name="orderby" class="orderby" aria-label="<?php echo esc_attr__('Shop order', 'crt-manage'); ?>">
									<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
										<option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>><?php echo esc_html($name); ?></option>
									<?php endforeach; ?>
								</select>
							</span>

							<?php
							// Preserve search parameters
							if ( isset($_GET['s']) ) {
								echo '<input type="hidden" name="s" value="'. esc_attr($_GET['s']) .'"/>';
							}
							
							if ( isset( $_GET['post_type'] ) ) {
								echo '<input type="hidden" name="post_type" value="'. esc_attr($_GET['post_type']) .'"/>';
							}

							if ( isset( $_GET['psearch'] ) ) {
								echo '<input type="hidden" name="psearch" value="'. esc_attr($_GET['psearch']) .'"/>';
							}
							
							if ( isset( $_GET['filter_rating'] ) ) {
								echo '<input type="hidden" name="filter_rating" value="'. esc_attr($_GET['filter_rating']) .'"/>';
							}
							
							if ( isset( $_GET['filter_product_cat'] ) ) {
								echo '<input type="hidden" name="filter_product_cat" value="'. esc_attr($_GET['filter_product_cat']) .'"/>';
							}
							
							if ( isset( $_GET['filter_product_tag'] ) ) {
								echo '<input type="hidden" name="filter_product_tag" value="'. esc_attr($_GET['filter_product_tag']) .'"/>';
							}
							
							if ( isset( $_GET['min_price'] ) ) {
								echo '<input type="hidden" name="min_price" value="'. esc_attr($_GET['min_price']) .'"/>';
							}
							
							if ( isset( $_GET['max_price'] ) ) {
								echo '<input type="hidden" name="max_price" value="'. esc_attr($_GET['max_price']) .'"/>';
							}
							
							if ( isset( $_GET['crtfilters'] ) ) {
								echo '<input type="hidden" name="crtfilters" value="sort"/>';
							}

							// Handle attribute filters
							if ( $_chosen_attributes = WC()->query->get_layered_nav_chosen_attributes() ) {
								foreach ( $_chosen_attributes as $name => $data ) {
									$filter_name = wc_attribute_taxonomy_slug( $name );
									reset($_chosen_attributes);
									if ( $name === key($_chosen_attributes) ) {
										echo '<input type="hidden" name="crtfilters" value="sort"/>';
									}
									
									if ( isset($_GET['query_type_' . $filter_name]) ) {
										echo '<input type="hidden" name="query_type_'. esc_attr($filter_name) .'" value="or"/>';
									}

									if ( isset($_GET['filter_' . $filter_name]) ) {
										echo '<input type="hidden" name="filter_'. esc_attr($filter_name) .'" value="'. esc_attr($_GET['filter_' . $filter_name]) .'"/>';
									}
								}
							}
							?>
						</form>
					</div>
					<?php
				}
			
			echo '</div>';
			echo '</div>';
		}
	}


	// Render Grid Filters
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
			echo '<span data-filter="*" class="crt-active-filter '. $pointer_item_class_name .'">'. $left_icon . esc_html($settings['filters_all_text']) . $right_icon . $post_count .'</span>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</li>';
		}

		// Custom Filters
		if ( $settings['query_selection'] === 'dynamic' && ! empty( $custom_filters ) ) {
			$parent_filters = [];

			foreach ( $custom_filters as $key => $term_id ) {
				$filter = get_term_by( 'id', $term_id, $taxonomy );
				$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $filter->slug : $taxonomy .'-'. $filter->slug;
				$tax_data_attr = 'post_tag' === $taxonomy ? 'tag' : $taxonomy;
				$term_data_attr = $filter->slug;

				// Parent Filters
				if ( 0 === $filter->parent ) {
					$children = get_term_children( $filter->term_id, $taxonomy );
					$data_role = ! empty($children) ? ' data-role="parent"' : '';

					echo '<li'. $data_role .' class="'. esc_attr($pointer_class) .'">';
						if ( 'yes' !== $settings['filters_linkable'] ) {
							echo ''. $left_separator .'<span '. $pointer_item_class .' data-ajax-filter='. json_encode([$tax_data_attr, $term_data_attr]) .' data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</span>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							echo ''. $left_separator .'<a '. $pointer_item_class .' href="'. esc_url(get_term_link( $filter->term_id, $taxonomy )) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</a>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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

				// Parent Filters
				if ( 0 === $filter->parent ) {
					$children = get_term_children( $filter->term_id, $taxonomy );
					$data_role = ! empty($children) ? ' data-role="parent"' : '';
					$hidden_class = CRT_Woo_Grid_Helpers::get_hidden_filter_class($filter->slug, $settings);

					echo '<li'. $data_role .' class="'. esc_attr($pointer_class) . esc_attr($hidden_class) .'">';
						if ( 'yes' !== $settings['filters_linkable'] ) {
							echo ''. $left_separator .'<span '. $pointer_item_class .' data-ajax-filter='. json_encode([$tax_data_attr, $term_data_attr]) .' data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</span>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							echo ''. $left_separator .'<a '. $pointer_item_class .' href="'. esc_url(get_term_link( $filter->term_id, $taxonomy )) .'" data-ajax-filter='. json_encode([$tax_data_attr, $term_data_attr]) .' data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</a>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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

				echo '<ul data-parent=".'. esc_attr(urldecode($data_attr)) .'" class="crt-sub-filters">';

				echo '<li data-role="back" class="'. esc_attr($pointer_class) .'">';
					echo '<span class="crt-back-filter" data-ajax-filter='. json_encode([$tax_data_attr, $term_data_attr]) .' data-filter=".'. esc_attr(urldecode( $data_attr )) .'">';
						echo '<i class="fas fa-long-arrow-alt-left"></i>&nbsp;&nbsp;'. esc_html__( 'Back', 'crt-manage' );
					echo '</span>';
				echo '</li>';

				foreach ( $children as $child ) {
					$sub_filter = get_term_by( 'id', $child, $taxonomy );
					$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $sub_filter->slug : $taxonomy .'-'. $sub_filter->slug;
					$term_data_attr = $sub_filter->slug;

					echo '<li data-role="sub" class="'. esc_attr($pointer_class) .'">';
						echo ''. $left_separator .'<span '. $pointer_item_class .' data-ajax-filter='. json_encode([$tax_data_attr, $term_data_attr]) .' data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($sub_filter->name) . $right_icon . $post_count .'</span>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '</li>';
				}

				echo '</ul>';
			}
		}

		echo '</ul>';
	}

	// Grid Settings
	public function add_grid_settings( $settings, $settings_new ) {

		if ( 'fitRows' == $settings['layout_select'] ) {
			$stick_last_element_to_bottom = $settings['stick_last_element_to_bottom'];
		} else {
			$stick_last_element_to_bottom = 'no';
		}

		$gutter_hr_widescreen = isset($settings_new['layout_gutter_hr_widescreen']['size']) ? $settings_new['layout_gutter_hr_widescreen']['size'] : $settings_new['layout_gutter_hr']['size'];
		$gutter_hr_desktop = $settings_new['layout_gutter_hr']['size'];
		$gutter_hr_laptop = isset($settings_new['layout_gutter_hr_laptop']['size']) ? $settings_new['layout_gutter_hr_laptop']['size'] : $gutter_hr_desktop;
		$gutter_hr_tablet_extra = isset($settings_new['layout_gutter_hr_tablet_extra']['size']) ? $settings_new['layout_gutter_hr_tablet_extra']['size'] : $gutter_hr_laptop;
		$gutter_hr_tablet = isset($settings_new['layout_gutter_hr_tablet']['size']) ? $settings_new['layout_gutter_hr_tablet']['size'] : $gutter_hr_tablet_extra;
		$gutter_hr_mobile_extra = isset($settings_new['layout_gutter_hr_mobile_extra']['size']) ? $settings_new['layout_gutter_hr_mobile_extra']['size'] : $gutter_hr_tablet;
		$gutter_hr_mobile = isset($settings_new['layout_gutter_hr_mobile']['size']) ? $settings_new['layout_gutter_hr_mobile']['size'] : $gutter_hr_mobile_extra;

		$gutter_vr_widescreen = isset($settings_new['layout_gutter_vr_widescreen']['size']) ? $settings_new['layout_gutter_vr_widescreen']['size'] : $settings_new['layout_gutter_vr']['size'];
		$gutter_vr_desktop = $settings_new['layout_gutter_vr']['size'];
		$gutter_vr_laptop = isset($settings_new['layout_gutter_vr_laptop']['size']) ? $settings_new['layout_gutter_vr_laptop']['size'] : $gutter_vr_desktop;
		$gutter_vr_tablet_extra = isset($settings_new['layout_gutter_vr_tablet_extra']['size']) ? $settings_new['layout_gutter_vr_tablet_extra']['size'] : $gutter_vr_laptop;
		$gutter_vr_tablet = isset($settings_new['layout_gutter_vr_tablet']['size']) ? $settings_new['layout_gutter_vr_tablet']['size'] : $gutter_vr_tablet_extra;
		$gutter_vr_mobile_extra = isset($settings_new['layout_gutter_vr_mobile_extra']['size']) ? $settings_new['layout_gutter_vr_mobile_extra']['size'] : $gutter_vr_tablet;
		$gutter_vr_mobile = isset($settings_new['layout_gutter_vr_mobile']['size']) ? $settings_new['layout_gutter_vr_mobile']['size'] : $gutter_vr_mobile_extra;

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
			'pagination_max_pages' => CRT_Woo_Grid_Helpers::get_max_num_pages( $settings )
		];

		if ( 'current' !== $settings[ 'query_selection' ] ) {
			$layout_settings['query_posts_per_page'] = $settings['query_posts_per_page'];
		} else {
			$layout_settings['query_selection'] = $settings['query_selection'];
		}

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
				'pagination_max_pages' => CRT_Woo_Grid_Helpers::get_max_num_pages( $settings ),

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
				'query_source' => 'product',
				'current_query_tax' => isset(get_queried_object()->taxonomy) ? get_queried_object()->taxonomy : '',
				'current_query_source' => isset(get_queried_object()->taxonomy) ? get_queried_object()->slug : '',
				'current_query_order' => isset($settings['order_direction']) ? $settings['order_direction'] : (get_query_var('order') ? get_query_var('order') : 'ASC'),
				'current_query_orderby' => get_query_var('orderby') ? get_query_var('orderby') : 'menu_order title',
				'query_author' => isset($settings['query_author']) ? $settings['query_author'] : null,
				'query_posts_per_page' => isset($settings['query_posts_per_page']) ? $settings['query_posts_per_page'] : null,
				'query_offset' => isset($settings['query_offset']) ? $settings['query_offset'] : null,
				'query_randomize' => isset($settings['query_randomize']) ? $settings['query_randomize'] : null,
				'query_orderby' => isset($settings['query_orderby']) ? $settings['query_orderby'] : null,
				'order_direction' => isset($settings['order_direction']) ? $settings['order_direction'] : null,
				'query_exclude_no_images' => isset($settings['query_exclude_no_images']) ? $settings['query_exclude_no_images'] : null,
				'query_selection' => isset($settings['query_selection']) ? $settings['query_selection'] : null,
				'query_tax_selection' => isset($settings['query_tax_selection']) ? $settings['query_tax_selection'] : null,
				'query_manual' => isset($settings['query_manual_' . 'product']) ? $settings['query_manual_' . 'product'] : null,
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
				'popup_notification_animation' => isset($settings['popup_notification_animation']) ? $settings['popup_notification_animation'] : null,
				'popup_notification_fade_out_in' => isset($settings['popup_notification_fade_out_in']) ? $settings['popup_notification_fade_out_in'] : null,
				'popup_notification_animation_duration' => isset($settings['popup_notification_animation_duration']) ? $settings['popup_notification_animation_duration'] : null,

				// Taxonomies
				// Dynamically add taxonomy settings
				] + array_reduce(array_keys($this->post_taxonomies ?? ['product_cat' => 'product_cat', 'product_tag' => 'product_tag']), function($carry, $slug) use ($settings) {
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
			'sliderSlidesToScroll' => +$settings['layout_slides_to_scroll'],
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
		$settings_new = $this->get_settings_for_display();
        $settings['element_animation'] = '';
        $settings['overlay_animation'] = '';

		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<h2>'. esc_html__( 'WooCommerce is NOT active!', 'crt-manage' ) .'</h2>';
			return;
		}

		// Get Posts
//        print_r(CRT_Woo_Grid_Helpers::get_main_query_args($settings, []));die;

//        $args = array(
//            'post_type'      => 'product',   // Fetch regular posts
//            'posts_per_page' => -1,        // Limit to 5 posts per page
//        );
//        print_r(CRT_Woo_Grid_Helpers::get_main_query_args($settings, []));die;
		$posts = new \WP_Query( CRT_Woo_Grid_Helpers::get_main_query_args($settings, []) );

		// Loop: Start
		if ( $posts->have_posts() ) :

		$post_index = 0;

		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
		$grid_linked_products_heading_tag = Utilities::validate_html_tags_wl( $settings['grid_linked_products_heading_tag'], 'h2', $tags_whitelist );

		if ( ('upsell' === $settings['query_selection'] && '' !== $settings['grid_linked_products_heading']) || ('cross-sell' === $settings['query_selection'] && '' !== $settings['grid_linked_products_heading']) ) {
			echo '<div class="crt-grid-linked-products-heading">';
				echo '<'. $grid_linked_products_heading_tag .'>'. esc_html( $settings['grid_linked_products_heading'] ) .'</'. $grid_linked_products_heading_tag .'>';
			echo '</div>';
		}

		// Grid Settings
		if ( 'slider' !== $settings['layout_select'] ) {

			if ( 'upsell' !== $settings['query_selection'] && 'cross-sell' !== $settings['query_selection'] ) {
				// Sort & Results
				$this->render_grid_sorting( $settings, $posts );

				if ( !((is_product_category() || is_product_tag()) ) ) {
					// Filters
					$this->render_grid_filters( $settings );
				}
			}

			$this->add_grid_settings( $settings, $settings_new );
			$render_attribute = $this->get_render_attribute_string( 'grid-settings' );

		// Slider Settings
		} else {
			$this->add_slider_settings( $settings );
			$render_attribute = $this->get_render_attribute_string( 'slider-settings' );
		}

		// Grid Wrap
        echo '<section class="crt-grid elementor-clearfix" '. $render_attribute .' data-found-posts = '. $posts->found_posts .'>';


		while ( $posts->have_posts() ) : $posts->the_post();
			 $post_index++;
//			 if ( Utilities::is_new_free_user() && $post_index > 12 ) {
//			 	return;
//			 }

			// Post Class
			$post_class = implode( ' ', get_post_class( 'crt-grid-item elementor-clearfix', get_the_ID() ) );

			// Grid Item
			echo '<article class="'. esc_attr( $post_class ) .'">';

			// Password Protected Form
			CRT_Woo_Grid_Helpers::render_password_protected_input( $settings );

			// Inner Wrapper
			echo '<div class="crt-grid-item-inner">';
//            echo get_the_title();
			// Content: Above Media
			CRT_Woo_Grid_Helpers::get_elements_by_location( 'above', $settings, get_the_ID(), $settings_new );

			// Media
			echo '<div class="crt-grid-media-wrap'. esc_attr(CRT_Woo_Grid_Helpers::get_image_effect_class( $settings )) .' " data-overlay-link="'. esc_attr( $settings['overlay_post_link'] ) .'">';
				// Post Thumbnail
				CRT_Woo_Grid_Helpers::render_product_thumbnail( $settings, get_the_ID() );

				// Media Hover
				echo '<div class="crt-grid-media-hover crt-animation-wrap">';

					// Filter to compensate woo incompatibility
					echo apply_filters('crt_grid_media_hover_content', '', get_the_ID());

					// Media Overlay
					CRT_Woo_Grid_Helpers::render_media_overlay( $settings );

					// Content: Over Media
					CRT_Woo_Grid_Helpers::get_elements_by_location( 'over', $settings, get_the_ID(), $settings_new );

				echo '</div>';
			echo '</div>';

			// Content: Below Media
			CRT_Woo_Grid_Helpers::get_elements_by_location( 'below', $settings, get_the_ID(), $settings_new );

			echo '</div>'; // End .crt-grid-item-inner

			echo '</article>'; // End .crt-grid-item

		endwhile;

		// reset
		wp_reset_postdata();

		// Grid Wrap
		echo '</section>';

		if ( 'slider' === $settings['layout_select'] ) {
			if ( $posts->found_posts > (int) $settings['layout_slider_amount'] &&  ( (int) $settings['layout_slider_amount'] < $settings['query_posts_per_page'] || empty($settings['query_posts_per_page']) ) ) {
				// Slider Navigation
				echo '<div class="crt-grid-slider-arrow-container">';
					echo '<div class="crt-grid-slider-prev-arrow crt-grid-slider-arrow" id="crt-grid-slider-prev-'. esc_attr($this->get_id()) .'">'. Utilities::get_crt_icon( $settings['layout_slider_nav_icon'], '' ) .'</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<div class="crt-grid-slider-next-arrow crt-grid-slider-arrow" id="crt-grid-slider-next-'. esc_attr($this->get_id()) .'">'. Utilities::get_crt_icon( $settings['layout_slider_nav_icon'], '' ) .'</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';

				// Slider Dots
				echo '<div class="crt-grid-slider-dots"></div>';
			}
		}


		// Pagination
		CRT_Woo_Grid_Helpers::render_grid_pagination( $settings );

		// No Posts Found
		else:
			if ( 'yes' === $settings['advanced_filters'] ) {
				// Grid Settings
				if ( 'slider' !== $settings['layout_select'] ) {
					$this->add_grid_settings( $settings, $settings_new );
					$render_attribute = $this->get_render_attribute_string( 'grid-settings' );

				// Slider Settings
				} else {
					$this->add_slider_settings( $settings, $settings_new );
					$render_attribute = $this->get_render_attribute_string( 'slider-settings' );
				}

				echo '<section class="crt-grid elementor-clearfix" '. $render_attribute .'>';

					if ('upsell' !== $settings['query_selection'] && 'cross-sell' !== $settings['query_selection']) {
						echo '<h2>'. esc_html($settings['query_not_found_text']) .'</h2>';
					}

				// Grid Wrap
				echo '</section>';

				// Pagination
				CRT_Woo_Grid_Helpers::render_grid_pagination( $settings );
			} else {
				if ('upsell' !== $settings['query_selection'] && 'cross-sell' !== $settings['query_selection']) {
					echo '<h2>'. esc_html($settings['query_not_found_text']) .'</h2>';
				}
			}

		// Loop: End
		endif;
	}
	
}