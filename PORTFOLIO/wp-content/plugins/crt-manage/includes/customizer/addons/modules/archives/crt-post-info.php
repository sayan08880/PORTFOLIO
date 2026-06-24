<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Post_Info extends Widget_Base {
	
	public function get_name() {
		return 'crt-post-info';
	}

	public function get_title() {
		return esc_html__( 'Post Meta', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-post-info';
	}

	public function get_categories() {
		return ['crt_manage_single'];
	}

	public function get_keywords() {
		return [ 'post meta', 'post info', 'date', 'time', 'author', 'categories', 'tags', 'comments' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function add_options_post_info_select() {
		return [
			'date' => esc_html__( 'Date', 'crt-manage' ),
			'time' => esc_html__( 'Time', 'crt-manage' ),
			'comments' => esc_html__( 'Comments', 'crt-manage' ),
			'author' => esc_html__( 'Author', 'crt-manage' ),
			'taxonomy' => esc_html__( 'Taxonomy', 'crt-manage' ),
			'custom-field' => esc_html__( 'Custom Field (Expert)', 'crt-manage' ),
		];
	}

    public function add_section_style_custom_field() {
        $this->start_controls_section(
            'section_style_custom_field',
            [
                'label' => esc_html__( 'Custom Field', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'post_info_select' => 'custom-field'
                ]
            ]
        );

        $this->start_controls_tabs( 'tabs_grid_custom_field_style' );

        $this->start_controls_tab(
            'tab_grid_custom_field_normal',
            [
                'label' => __( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'custom_field_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .crt-post-info-custom-field a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-post-info-custom-field > span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'custom_field_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-post-info-custom-field a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-post-info-custom-field > span' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'custom_field_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-post-info-custom-field a' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-post-info-custom-field > span' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'custom_field_typography',
                'selector' => '{{WRAPPER}} .crt-post-info-custom-field'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_custom_field_hover',
            [
                'label' => __( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'custom_field_color_hr',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-post-info-custom-field a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-post-info-custom-field > span:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'custom_field_bg_color_hr',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-post-info-custom-field a:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-post-info-custom-field > span:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'custom_field_border_color_hr',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-post-info-custom-field a:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-post-info-custom-field > span:hover' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'custom_field_padding',
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
                    '{{WRAPPER}} .crt-post-info-custom-field a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-post-info-custom-field > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'custom_field_border_type',
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
                    '{{WRAPPER}} .crt-post-info-custom-field a' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .crt-post-info-custom-field > span' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'custom_field_border_width',
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
                    '{{WRAPPER}} .crt-post-info-custom-field a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-post-info-custom-field > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_field_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'custom_field_radius',
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
                    '{{WRAPPER}} .crt-post-info-custom-field a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-post-info-custom-field > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function get_post_taxonomies() {
		return [
			'category' => esc_html__( 'Categories', 'crt-manage' ),
			'post_tag' => esc_html__( 'Tags', 'crt-manage' ),
		];		
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_info',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_info_layout',
			[
				'label' => esc_html__( 'List Layout', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
				'options' => [
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'crt-manage' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'horizontal' => [
						'title' => esc_html__( 'Horizontal', 'crt-manage' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'label_block' => false,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'post_info_select',
			[
				'label' => esc_html__( 'Select Element', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'time',
				'options' => $this->add_options_post_info_select(),
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'post_info_custom_field_video_tutorial',
			[
				'raw' => esc_html__( 'Watch Custom Fields ', 'crt-manage' ) . sprintf( '<a href="%1$s" target="_blank">%2$s <span class="dashicons dashicons-video-alt3"></span></a>', '', esc_html__( 'Video Tutorial', 'crt-manage' ) ),
				'type' => Controls_Manager::RAW_HTML,
				'condition' => [
					'post_info_select' => 'custom-field'
				]
			]
		);

		$repeater->add_control(
			'post_info_modified_time',
			[
				'label' => esc_html__( 'Show Modified Time', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'condition' => [
					'post_info_select' => [ 'time', 'date' ],
				]
			]
		);

		$repeater->add_control(
			'post_info_comments_text_1',
			[
				'label' => esc_html__( 'No Comments', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => ' No Comments',
				'condition' => [
					'post_info_select' => 'comments',
				]
			]
		);

		$repeater->add_control(
			'post_info_comments_text_2',
			[
				'label' => esc_html__( 'One Comment', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => ' Comment',
				'condition' => [
					'post_info_select' => 'comments',
				]
			]
		);

		$repeater->add_control(
			'post_info_comments_text_3',
			[
				'label' => esc_html__( 'Multiple Comments', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => ' Comments',
				'separator' => 'after',
				'condition' => [
					'post_info_select' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'post_info_tax_select',
			[
				'label' => esc_html__( 'Select Taxonomy', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => $this->get_post_taxonomies(),
				'condition' => [
					'post_info_select' => 'taxonomy',
				]
			]
		);

		$repeater->add_control(
			'post_info_tax_display',
			[
				'label' => esc_html__( 'Display', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline-block',
				'options' => [
					'inline-block' => esc_html__( 'Inline', 'crt-manage' ),
					'block' => esc_html__( 'Separate', 'crt-manage' ),
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-info-taxonomy a' => 'display: {{VALUE}}',
					'{{WRAPPER}} .crt-post-info-taxonomy > span:not(.crt-post-info-text)' => 'display: {{VALUE}}',
				],
				'condition' => [
					'post_info_select' => 'taxonomy',
				]
			]
		);

		$repeater->add_control(
			'post_info_tax_sep',
			[
				'label' => esc_html__( 'Separator', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => ', ',
				'separator' => 'after',
				'condition' => [
					'post_info_select' => 'taxonomy',
				]
			]
		);

		$repeater->add_control(
			'post_info_show_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'condition' => [
					'post_info_select' => 'author'
				]
			]
		);

		$repeater->add_responsive_control(
			'post_info_avatar_size',
			[
				'label' => esc_html__( 'Avatar Size', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 32,
				'min' => 8,
				'selectors' => [
					'{{WRAPPER}} .crt-post-info-author img' => 'width: {{SIZE}}px;',
				],
				'render_type' => 'template',
				'condition' => [
					'post_info_select' => 'author',
					'post_info_show_avatar' => 'yes'
				],
			]
		);

        $repeater->add_control(
            'post_info_cf',
            [
                'label' => esc_html__( 'Select Custom Field', 'crt-manage' ),
                'type' => 'crt-ajax-select2',
                'label_block' => true,
                'default' => 'default',
                'options' => 'ajaxselect2/get_custom_meta_keys',
                'condition' => [
                    'post_info_select' => 'custom-field'
                ],
            ]
        );

        $repeater->add_control(
            'post_info_cf_btn_link',
            [
                'label' => esc_html__( 'Use Value as Button Link', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'post_info_select' => 'custom-field'
                ],
            ]
        );

        $repeater->add_control(
            'post_info_cf_new_tab',
            [
                'label' => esc_html__( 'Open Link in a New Tab', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'post_info_select' => 'custom-field',
                    'post_info_cf_btn_link' => 'yes'
                ],
            ]
        );

        $repeater->add_control(
            'post_info_cf_btn_text',
            [
                'label' => esc_html__( 'Button Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Click Me',
                'condition' => [
                    'post_info_select' => 'custom-field',
                    'post_info_cf_btn_link' => 'yes'
                ],
            ]
        );

        $repeater->add_control(
            'custom_field_wrapper_html_divider1',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
                'condition' => [
                    'post_info_select' => 'custom-field',
                ],
            ]
        );

        $repeater->add_control(
            'post_info_cf_wrapper',
            [
                'label' => esc_html__( 'Wrap with HTML', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'post_info_select' => 'custom-field'
                ],
            ]
        );

        $repeater->add_control(
				'post_info_cf_wrapper_html',
				[
					'label' => esc_html__( 'Custom HTML Wrapper', 'crt-manage' ),
					'description' => 'Insert <strong>*cf_value*</strong> to dislpay your Custom Field.',
					'placeholder'=> 'For Ex: <span>*cf_value*</span>',
					'type' => Controls_Manager::TEXTAREA,
					'dynamic' => [
						'active' => true,
					],
					'condition' => [
						'post_info_select' => 'custom-field',
						'post_info_cf_wrapper' => 'yes',
					],
				]
			);

		$repeater->add_control(
			'post_info_link_wrap',
			[
				'label' => esc_html__( 'Wrap with Link', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'condition' => [
					'post_info_select!' => [ 'time', 'custom-field' ],
				]
			]
		);

		$repeater->add_control(
			'post_info_extra_icon',
			[
				'label' => esc_html__( 'Extra Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'post_info_apply_individually',
			[
				'label' => esc_html__( 'Apply Individually', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'condition' => [
					'post_info_select' => [ 'taxonomy' ],
          'post_info_extra_icon!' => ''
				]
			]
		);

		$repeater->add_control(
			'post_info_extra_text',
			[
				'label' => esc_html__( 'Extra Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
			]
		);

		$this->add_control(
			'post_info_elements',
			[
				'label' => esc_html__( 'Post Info Elements', 'crt-manage' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'post_info_select' => 'taxonomy',
					],
					[
						'post_info_select' => 'date',
					],
				],
				'title_field' => '{{{ post_info_select.charAt(0).toUpperCase() + post_info_select.slice(1) }}}',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: List -------------
		$this->start_controls_section(
			'section_style_post_info_list',
			[
				'label' => esc_html__( 'List Style', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'post_info_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'Some of the options will only apply if you have multiple Post Meta Elements.', 'crt-manage' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_responsive_control(
			'post_info_gutter',
			[
				'label' => esc_html__( 'List Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-info-vertical li' => 'padding-bottom: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-info-horizontal li' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-info-horizontal li:after' => 'right: calc({{SIZE}}{{UNIT}} / 2);',
				],
			]
		);

		$this->add_responsive_control(
            'post_info_align',
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
					'{{WRAPPER}} .crt-post-info' => 'text-align: {{VALUE}}',
				],
				'prefix_class' => 'crt-post-info-align-',
				'separator' => 'after'
            ]
        );

		$this->add_control(
			'post_info_divider',
			[
				'label' => esc_html__( 'Show Dividers', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'post_info_divider_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'selectors' => [
					'{{WRAPPER}} .crt-post-info li:after' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'post_info_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'post_info_divider_style',
			[
				'label' => esc_html__( 'Style', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .crt-post-info-vertical li:after' => 'border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .crt-post-info-horizontal li:after' => 'border-right-style: {{VALUE}};',
				],
				'condition' => [
					'post_info_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'post_info_divider_weight',
			[
				'label' => esc_html__( 'Weight', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-info-vertical li:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-info-horizontal li:after' => 'border-right-width: {{SIZE}}{{UNIT}}; margin-right: calc(-{{SIZE}}px / 2);',
				],
				'condition' => [
					'post_info_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'post_info_divider_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-info-vertical li:after' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'post_info_divider' => 'yes',
					'post_info_layout!' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'post_info_divider_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-info-horizontal li:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'post_info_divider' => 'yes',
					'post_info_layout!' => 'vertical',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Elements ---------
		$this->start_controls_section(
			'section_style_post_info_elements',
			[
				'label' => esc_html__( 'Elements (Date, Comments, Author)', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_post_info_elements_style' );

		$this->start_controls_tab(
			'tab_post_info_elements_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'post_info_elements_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#959595',
				'selectors' => [
					'{{WRAPPER}} .crt-post-info li' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-post-info li:not(.crt-post-info-taxonomy):not(.crt-post-info-custom-field) a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_info_elements_typography',
				'label' => esc_html__('Typography', 'crt-manage'),
				'selector' => '{{WRAPPER}} .crt-post-info li:not(.crt-post-info-taxonomy):not(.crt-post-info-custom-field)',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'post_info_elements_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-post-info li a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'post_info_avatar_border_radius',
			[
				'label' => esc_html__( 'Avatar Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_post_info_elements_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'post_info_elements_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-post-info li:not(.crt-post-info-taxonomy):not(.crt-post-info-custom-field) a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Styles ====================
		// Section: Taxonomy ---------
		$this->start_controls_section(
			'section_style_post_info_tax',
			[
				'label' => esc_html__( 'Taxonomy (Categories, Tags, etc..)', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_post_info_tax_style' );

		$this->start_controls_tab(
			'tab_grid_post_info_tax_normal',
			[
				'label' => __( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'post_info_tax_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-post-info-taxonomy a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-post-info-taxonomy > span:not(.crt-post-info-text)' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_info_tax_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-post-info-taxonomy a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-post-info-taxonomy > span:not(.crt-post-info-text)' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'post_info_tax_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-post-info-taxonomy a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-post-info-taxonomy > span:not(.crt-post-info-text)' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_info_tax_typography',
				'selector' => '{{WRAPPER}} .crt-post-info-taxonomy a, {{WRAPPER}} .crt-post-info-taxonomy > span:not(.crt-post-info-text)',
				'separator' => 'before',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_post_info_tax_hover',
			[
				'label' => __( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'post_info_tax_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595F',
				'selectors' => [
					'{{WRAPPER}} .crt-post-info-taxonomy a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_info_tax_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-post-info-taxonomy a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'post_info_tax_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-post-info-taxonomy a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'post_info_tax_padding',
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
					'{{WRAPPER}} .crt-post-info-taxonomy a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-info-taxonomy > span:not(.crt-post-info-text)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'post_info_tax_margin',
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
					'{{WRAPPER}} .crt-post-info-taxonomy a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-info-taxonomy > span:not(.crt-post-info-text)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'post_info_tax_border_type',
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
					'{{WRAPPER}} .crt-post-info-taxonomy a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-post-info-taxonomy > span:not(.crt-post-info-text)' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'post_info_tax_border_width',
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
					'{{WRAPPER}} .crt-post-info-taxonomy a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-info-taxonomy > span:not(.crt-post-info-text)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'post_info_tax_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'post_info_tax_radius',
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
					'{{WRAPPER}} .crt-post-info-taxonomy a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-info-taxonomy > span:not(.crt-post-info-text)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Custom Field -----
		$this->add_section_style_custom_field();

		// Styles ====================
		// Section: Extra Icon -------
		$this->start_controls_section(
			'section_style_post_info_icon',
			[
				'label' => esc_html__( 'Extra Icon', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'post_info_icon_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-post-info li:not(.crt-post-info-custom-field) i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-post-info li:not(.crt-post-info-custom-field) svg' => 'fill: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_responsive_control(
			'post_info_icon_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 16
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-info li i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-info li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_info_icon_space',
			[
				'label' => esc_html__( 'Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 5
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-info li i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-info li svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Extra Text -------
		$this->start_controls_section(
			'section_style_post_info_text',
			[
				'label' => esc_html__( 'Extra Text', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'post_info_text_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					// '{{WRAPPER}} .crt-post-info li:not(.crt-post-info-custom-field) .crt-post-info-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-post-info li .crt-post-info-text' => 'color: {{VALUE}}'
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_info_extra_text_typography',
				'label' => esc_html__('Typography', 'crt-manage'),
				'selector' => '{{WRAPPER}} .crt-post-info li .crt-post-info-text',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'post_info_text_width',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-info li .crt-post-info-text span' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	// Post Date
	public function render_post_info_date( $settings ) {
		// Extra Icon & Text 
		$this->render_extra_icon_text( $settings );

		// Wrap with Link
		if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
			echo '<a href="'. esc_url( get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) ) ) .'">';
		}

		// Modified Time
		if ( 'yes' === $settings['post_info_modified_time']) {
			echo esc_html(get_the_modified_time(get_option( 'date_format')));
		} else {
			// Date
			echo '<span>'. esc_html(apply_filters( 'the_date', get_the_date( '' ), get_option( 'date_format' ), '', '' )) .'</span>';
		}

		// Wrap with Link
		if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
			echo '</a>';
		}
	}

	// Post Time
	public function render_post_info_time( $settings ) {
		// Extra Icon & Text 
		$this->render_extra_icon_text( $settings );

		if ( 'yes' === $settings['post_info_modified_time']) {
			echo esc_html(get_the_modified_time());
		} else {
			echo '<span>'. esc_html(get_the_time('')) .'</span>';
		}
	}

	// Post Comments
	public function render_post_info_comments( $settings ) {
		// Extra Icon & Text 
		$this->render_extra_icon_text( $settings );

		$count = get_comments_number();

		if ( comments_open() ) {
			if ( $count == 1 ) {
				$text = $count . $settings['post_info_comments_text_2'];
			} elseif ( $count > 1 ) {
				$text = $count . $settings['post_info_comments_text_3'];
			} else {
				$text = $settings['post_info_comments_text_1'];
			}

			// Wrap with Link
			if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
				echo '<a href="'. esc_url( get_comments_link() ) .'">';
			}

			// Comments
			echo '<span> '. esc_html($text) .'</span>';

			if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
				echo '</a>';
			}
		}
	}

	// Post Author
	public function render_post_info_author( $settings ) {
		$author_id = get_post_field( 'post_author' );

		// Extra Icon & Text 
		$this->render_extra_icon_text( $settings );
		
		// Wrap with Link
		if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
			echo '<a href="'. esc_url( get_author_posts_url( $author_id ) ) .'">';
		}

			if ( 'yes' === $settings['post_info_show_avatar'] ) {
				echo get_avatar( $author_id, $settings['post_info_avatar_size'] );
			}

			echo '<span>'. esc_html(get_the_author_meta( 'display_name', $author_id )) .'</span>';

		if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
			echo '</a>';
		}
	}

	// Post Taxonomy
	public function render_post_info_taxonomy( $settings ) {
		$terms = wp_get_post_terms( get_the_ID(), $settings['post_info_tax_select'] );
		$count = 0;

		// Extra Icon & Text 
    if ( 'yes' !== $settings['post_info_apply_individually'] ) {
      $this->render_extra_icon_text( $settings );
    }
		
		// Taxonomies
		foreach ( $terms as $term ) {
			if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
				echo '<a href="'. esc_url(get_term_link( $term->term_id )) .'">';
          if ( 'yes' == $settings['post_info_apply_individually'] ) {
            $this->render_extra_icon_text( $settings );
          }

					// Term Name
					echo esc_html( $term->name );

					// Separator
					if ( ++$count !== count( $terms ) ) {
						echo '<span class="tax-sep">'. esc_html($settings['post_info_tax_sep']) .'</span>';
					}
				echo '</a>';
			} else {
				echo '<span>';
          if ( 'yes' == $settings['post_info_apply_individually'] ) {
            $this->render_extra_icon_text( $settings );
          }

					// Term Name
					echo esc_html( $term->name );

					// Separator
					if ( ++$count !== count( $terms ) ) {
						echo '<span class="tax-sep">'. esc_html($settings['post_info_tax_sep']) .'</span>';
					}
				echo '</span>';
			}
		}
	}

	// Post Custom Field
	public function render_post_info_custom_field( $settings ) {
        $meta_key = $settings['post_info_cf'];
        $value = get_post_meta(get_the_ID(), $meta_key);
        ?>
            <span><?php echo isset($value[0]) ? esc_html($value[0]) : ''; ?></span>
        <?php
    }

	// Extra Icon & Text 
	public function render_extra_icon_text( $settings ) {
		if ( ( isset( $settings['post_info_extra_icon'] ) && '' !== $settings['post_info_extra_icon']['value'] ) || '' !== $settings['post_info_extra_text'] ) {
			echo '<span class="crt-post-info-text">';
				// Extra Icon
				if ( '' !== $settings['post_info_extra_icon'] ) {
					\Elementor\Icons_Manager::render_icon( $settings['post_info_extra_icon'], [ 'aria-hidden' => 'true' ] );
				}

				// Extra Text
				if ( '' !== $settings['post_info_extra_text'] ) {
					echo '<span>'. esc_html( $settings['post_info_extra_text'] ) .'</span>';
				}
			echo '</span>';
		}
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		echo '<ul class="crt-post-info crt-post-info-'. esc_attr($settings['post_info_layout']) .'">';

		foreach( $settings['post_info_elements'] as $element_settings ) {
			echo '<li class="crt-post-info-'. esc_attr($element_settings['post_info_select']) .'">';

			switch ( $element_settings['post_info_select'] ) {
				case 'date':
					$this->render_post_info_date( $element_settings );
					break;

				case 'time':
					$this->render_post_info_time( $element_settings );
					break;

				case 'comments':
					$this->render_post_info_comments( $element_settings );
					break;

				case 'author':
					$this->render_post_info_author( $element_settings );
					break;

				case 'taxonomy':
					$this->render_post_info_taxonomy( $element_settings );
					break;

				case 'custom-field':
					$this->render_post_info_custom_field( $element_settings );
					break;
			}

			echo '</li>';
		}

		echo '</ul>';

	}
	
}