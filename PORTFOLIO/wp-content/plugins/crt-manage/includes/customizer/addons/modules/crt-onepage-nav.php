<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
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

class CRT_OnepageNav extends Widget_Base {
		
	public function get_name() {
		return 'crt-onepage-nav';
	}

	public function get_title() {
		return esc_html__( 'Onepage Nav', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-navigator';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'one page', 'onepage', 'navigation', 'one page scroll', 'scroll navigation', 'floating menu', 'sticky menu', 'page scroll' ];
	}

    public function get_script_depends() {
        return [ 'crt-onepage-nav' ];
    }

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-one-page-navigation-help-btn';
    		return 'https://crthemes.com/contact';
    }

    public function add_section_settings() {
        $this->start_controls_section(
            'section_settings',
            [
                'label' => esc_html__( 'Settings', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'nav_consider_header',
            [
                'label' => esc_html__( 'Consider Sticky Header', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'description' => esc_html__( 'Enable this to account for Royal Sticky Header when scrolling to sections.', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'nav_item_show_tooltip',
            [
                'label' => esc_html__( 'Show Tooltip', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'nav_item_highlight',
            [
                'label' => esc_html__( 'Highlight Active', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'nav_item_scroll_speed',
            [
                'label' => esc_html__( 'Scrolling Speed', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
                'step' => 100,
                'min' => 0,
                'separator' => 'before'
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

    public function add_control_nav_item_stretch() {
        $this->add_control(
            'nav_item_stretch',
            [
                'label' => esc_html__( 'Stretch Vertically', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'selectors_dictionary' => [
                    '' => 'height: auto;',
                    'yes' => 'height: 100%; top: 50%; transform: translateY(-50%); -webkit-transform: translateY(-50%);'
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-onepage-nav' => '{{VALUE}}',
                ],
                'render_type' => 'template'
            ]
        );
    }

    public function add_condition_nav_item_stretch() {
        return [
            'nav_item_stretch!' => 'yes',
        ];
    }

    public function add_repeater_args_nav_item_tooltip() {
        return [
            'label' => esc_html__( 'Section Tooltip', 'crt-manage' ),
            'type' => Controls_Manager::TEXT,
            'default' => 'Section 1',
        ];
    }

    public function add_repeater_args_nav_item_icon_color() {
        return [
            'label' => esc_html__( 'Icon Color', 'crt-manage' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} {{CURRENT_ITEM}}.crt-onepage-nav-item i' => 'color: {{VALUE}};',
                '{{WRAPPER}} {{CURRENT_ITEM}}.crt-onepage-nav-item svg' => 'fill: {{VALUE}};',
            ],
        ];
    }

    public function add_section_nav_tooltip() {
        $this->start_controls_section(
            'section_nav_tooltip',
            [
                'label' => esc_html__( 'Tooltip', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'nav_tooltip_color',
            [
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-onepage-nav-item .crt-tooltip' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_tooltip_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#3F3F3F',
                'selectors' => [
                    '{{WRAPPER}} .crt-onepage-nav-item .crt-tooltip' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .crt-onepage-nav-item .crt-tooltip:before' => 'border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'nav_tooltip_box_shadow',
                'selector' => '{{WRAPPER}} .crt-onepage-nav-item .crt-tooltip',
            ]
        );

        $this->add_control(
            'nav_tooltip_type_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'nav_item_tooltip_typography',
                'selector' => '{{WRAPPER}} .crt-onepage-nav-item .crt-tooltip'
            ]
        );

        $this->add_responsive_control(
            'nav_tooltip_width',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'size_units' => [ 'px', ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-onepage-nav-item .crt-tooltip' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'nav_tooltip_offset',
            [
                'label' => esc_html__( 'Offset', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}}.crt-onepage-nav-hr-left .crt-onepage-nav-item .crt-tooltip' => 'transform: translate({{SIZE}}%,-50%); -webkit-transform: translate({{SIZE}}%,-50%);',
                    '{{WRAPPER}}.crt-onepage-nav-hr-left .crt-onepage-nav-item:hover .crt-tooltip' => 'transform: translate(calc({{SIZE}}% - 10%),-50%); -webkit-transform: translate(-webkit-calc({{SIZE}}% - 10%),-50%);',
                    '{{WRAPPER}}.crt-onepage-nav-hr-right .crt-onepage-nav-item .crt-tooltip' => 'transform: translate(calc(-{{SIZE}}% - 100%),-50%); -webkit-transform: translate(calc(-{{SIZE}}% - 100%),-50%);',
                    '{{WRAPPER}}.crt-onepage-nav-hr-right .crt-onepage-nav-item:hover .crt-tooltip' => 'transform: translate(calc(-{{SIZE}}% - 100% + 10%),-50%); -webkit-transform: translate(-webkit-calc(-{{SIZE}}% - 100% + 10%),-50%);',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_tooltip_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 5,
                    'right' => 10,
                    'bottom' => 5,
                    'left' => 10,
                ],
                'size_units' => [ 'px', ],
                'selectors' => [
                    '{{WRAPPER}} .crt-onepage-nav-item .crt-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'nav_tooltip_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
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
                    '{{WRAPPER}} .crt-onepage-nav-item .crt-tooltip' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

    protected function register_controls() {

		// Section: Navigation -------
		$this->start_controls_section(
			'section_nav',
			[
				'label' => 'Navigation  <a href="#" onclick="window.open(\'https://youtu.be/0hM4l2UKzXs\',\'_blank\').focus()">Video Tutorial <span class="dashicons dashicons-video-alt3"></span></a>',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'nav_item_id',
			[
				'label' => esc_html__( 'Section ID', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'section-one',
			]
		);

		$repeater->add_control( 'nav_item_tooltip', $this->add_repeater_args_nav_item_tooltip() );
		
		$repeater->add_control(
			'nav_item_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-home',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control( 'nav_item_icon_color', $this->add_repeater_args_nav_item_icon_color() );

		$this->add_control(
			'nav_items',
			[
				'label' => esc_html__( 'Navigation Items', 'crt-manage' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'nav_item_id' => 'section-one',
						'nav_item_tooltip' => 'Section 1',
						'nav_item_icon' => [
							'value' => 'fas fa-home',
							'library' => 'fa-solid',
						],
					],
					[
						'nav_item_id' => 'section-two',
						'nav_item_tooltip' => 'Section 2',
						'nav_item_icon' => [
							'value' => 'far fa-envelope',
							'library' => 'fa-regular',
						],
					],
					[
						'nav_item_id' => 'section-three',
						'nav_item_tooltip' => 'Section 3',
						'nav_item_icon' => [
							'value' => 'fas fa-info-circle',
							'library' => 'fa-solid',
						],
					],
				],
				'title_field' => '{{{ nav_item_tooltip }}}',
			]
		);


		$this->end_controls_section(); // End Controls Section

		// Section: Layout -----------
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'crt-manage' ),
			]
		);

		$this->add_control_nav_item_stretch();

		$this->add_control(
			'nav_item_position_hr',
			[
				'label' => esc_html__( 'Horizontal Position', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
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
				'prefix_class' => 'crt-onepage-nav-hr-'
			]
		);

		$this->add_control(
			'nav_item_position_vr',
			[
				'label' => esc_html__( 'Vertical Position', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
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
				'prefix_class' => 'crt-onepage-nav-vr-',
				'separator' => 'after',
				'condition' => $this->add_condition_nav_item_stretch(),
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->add_section_settings();

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'onepage-nav', [
			'Highlight Active Nav Icon',
			'Nav Icon Custom Color',
			'Nav Icon Advanced Tooltip',
			'Scrolling Animation Speed',
			'Navigation Full-height (Sidebar) option',
		] );
		
		// Styles ====================
		// Section: Nav Wrap ---------
		$this->start_controls_section(
			'section_nav_wrap',
			[
				'label' => esc_html__( 'Navigation Wrapper', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'nav_wrap_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605BE5',
					],
				],
				'selector' => '{{WRAPPER}} .crt-onepage-nav'
			]
		);

		$this->add_control(
			'nav_wrap_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#232323',
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'nav_wrap_box_shadow',
				'selector' => '{{WRAPPER}} .crt-onepage-nav',
			]
		);

		$this->add_responsive_control(
			'nav_wrap_gutter',
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
					'{{WRAPPER}} .crt-onepage-nav-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'nav_wrap_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_wrap_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'nav_item_stretch!' => 'yes',
				],
			]
		);

		$this->add_control(
			'nav_wrap_border_type',
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
					'{{WRAPPER}} .crt-onepage-nav' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'nav_wrap_border_width',
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
					'{{WRAPPER}} .crt-onepage-nav' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'nav_wrap_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'nav_wrap_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 3,
					'right' => 0,
					'bottom' => 0,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Nav Item ---------
		$this->start_controls_section(
			'section_nav_item',
			[
				'label' => esc_html__( 'Navigation Item', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'nav_item_style' );

		$this->start_controls_tab(
			'nav_item_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'nav_item_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav-item i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-onepage-nav-item svg' => 'color: {{VALUE}};', // GOGA - shesacvlelia mgoni
				],
			]
		);

		$this->add_control(
			'nav_item_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav-item i' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-onepage-nav-item svg' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav-item i' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-onepage-nav-item svg' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'nav_item_box_shadow',
				'selector' => '{{WRAPPER}} .crt-onepage-nav-item i',
				'selector' => '{{WRAPPER}} .crt-onepage-nav-item svg',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'nav_item_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'nav_item_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFEC00',
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav-item:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-onepage-active-item i' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .crt-onepage-nav-item:hover svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .crt-onepage-active-item svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav-item:hover i' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-onepage-active-item i' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .crt-onepage-nav-item:hover svg' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-onepage-active-item svg' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav-item:hover i' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-onepage-active-item i' => 'border-color: {{VALUE}} !important;',
					'{{WRAPPER}} .crt-onepage-nav-item:hover svg' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-onepage-active-item svg' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'nav_item_hover_box_shadow',
				'selector' => '{{WRAPPER}} .crt-onepage-nav-item:hover i, {{WRAPPER}} .crt-onepage-active-item i, {{WRAPPER}} .crt-onepage-nav-item:hover svg, {{WRAPPER}} .crt-onepage-active-item svg',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'nav_item_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav-item' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-onepage-nav-item i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-onepage-nav-item svg' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'nav_item_icon_size',
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
					'size' => 17,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav-item i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-onepage-nav-item svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'nav_item_icon_size_active',
			[
				'label' => esc_html__( 'Active Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1.5,
				'min' => 1,
				'max' => 2,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-active-item i' => 'transform: scale({{SIZE}}); -webkit-transform: scale({{SIZE}});',
					'{{WRAPPER}} .crt-onepage-active-item i:before' => 'transform: scale({{SIZE}}); -webkit-transform: scale({{SIZE}});',
					'{{WRAPPER}} .crt-onepage-active-item svg' => 'transform: scale({{SIZE}}); -webkit-transform: scale({{SIZE}});',
				],
				'condition' => [
					'nav_item_highlight' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'nav_item_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav-item i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-onepage-nav-item svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'nav_item_border_type',
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
					'{{WRAPPER}} .crt-onepage-nav-item i' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-onepage-nav-item svg' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'nav_item_border_width',
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
					'{{WRAPPER}} .crt-onepage-nav-item i' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-onepage-nav-item svg' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'nav_item_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'nav_item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-onepage-nav-item i' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-onepage-nav-item svg' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ======================
		// Section: Nav Tooltip --------
		$this->add_section_nav_tooltip();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();


		echo '<div class="crt-onepage-nav" data-speed="'. esc_attr($settings['nav_item_scroll_speed']) .'" data-highlight="'. esc_attr($settings['nav_item_highlight']) .'" data-consider-header="'. esc_attr($settings['nav_consider_header']) .'">';
		
		// Nav Items
		foreach ( $settings['nav_items'] as $item ) {
			echo '<div class="crt-onepage-nav-item elementor-repeater-item-'. esc_attr($item['_id']) .'">';
				echo '<a href="#'. esc_attr($item['nav_item_id']) .'">';
					echo ( 'yes' === $settings['nav_item_show_tooltip'] ) ? '<span class="crt-tooltip">'. esc_html($item['nav_item_tooltip']) .'</span>' : '';
					\Elementor\Icons_Manager::render_icon( $item['nav_item_icon'] );
				echo '</a>';
			echo '</div>';
		}
		
		echo '</div>';
	}
}