<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Image_Size;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Page_List extends Widget_Base {
	
	public function get_name() {
		return 'crt-page-list';
	}

	public function get_title() {
		return esc_html__( 'Page List', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-editor-list-ul';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
	}

	public function get_keywords() {
		return [  'page-list', 'list'];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function add_control_title_pointer_color_hr() {
        $this->add_control(
            'title_pointer_color_hr',
            [
                'label'  => esc_html__( 'Hover Effect Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-pointer-item:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-pointer-item:after' => 'background-color: {{VALUE}}',
                ]
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
                'separator' => 'before'
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
                    '{{WRAPPER}} .crt-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
                ],
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
    
    protected function register_controls() {

		// Tab: Content ==============
		// Section: Content ----------
		$this->start_controls_section(
			'section_page_list_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'enable_wrapper_link',
			[
				'label' => esc_html__( 'Enable Wrapper Link', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'taxonomy_list_layout',
			[
				'label' => esc_html__( 'Select Layout', 'crt-manage' ),
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
                'prefix_class' => 'crt-page-list-',
				'label_block' => false,
			]
		);

		$repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'page_list_item_type',
            [
                'label'       => esc_html__( 'Page Type', 'crt-manage' ),
                'type'        => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'custom',
                'options'     => [
                    'custom' => esc_html__( 'Custom', 'crt-manage' ),
                    'dynamic'  => esc_html__( 'Dynamic', 'crt-manage' )
                ]
            ]
        );

		$repeater->add_control(
			'query_page_selection',
			[
				'label' => esc_html__( 'Select Page', 'crt-manage' ),
				'type' => 'crt-ajax-select2',
				'options' => 'ajaxselect2/get_posts_by_post_type',
				'query_slug' => 'page',
				'label_block' => true,
                'condition' => [
                    'page_list_item_type' => 'dynamic'
                ]
			]
		);

		$repeater->add_control(
			'page_list_item_title', 
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'New Page' , 'crt-manage' ),
				'label_block' => true,
				'separator' => 'before',
                'condition' => [
                    'page_list_item_type' => 'custom'
                ]
			]
		);

		$repeater->add_control(
			'page_list_item_sub_title', 
			[
				'label' => esc_html__( 'Sub Title', 'crt-manage' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'New Page Sub Title' , 'crt-manage' ),
			]
		);

		$repeater->add_control(
			'page_list_item_title_url',
			[
				'label' => esc_html__( 'Title Link', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'crt-manage' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
                'condition' => [
                    'page_list_item_type' => 'custom'
                ],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'open_in_new_page',
			[
				'label' => esc_html__( 'Open In New Page', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'default' => 'yes',
				'separator' => 'before',
                'condition' => [
                    'page_list_item_type' => 'dynamic'
                ]
			]
		);

		$repeater->add_control(
			'page_list_item_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
				'exclude_inline_options' => 'svg'
			]
		);

		$repeater->add_control(
			'show_page_list_item_badge',
			[
				'label' => esc_html__( 'Show Badge', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => '',
                'separator' => 'before'
			]
		);

		$repeater->add_control(
			'show_page_list_item_badge_animation',
			[
				'label' => esc_html__( 'Enable Animation', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'label_block' => false,
                'condition' => [
                    'show_page_list_item_badge' => 'yes'
                ]
			]
		);

		$repeater->add_control(
			'page_list_item_badge_text', [
				'label' => esc_html__( 'Badge Text', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Badge' , 'crt-manage' ),
                'condition' => [
                    'show_page_list_item_badge' => 'yes'
                ]
			]
		);

		$repeater->add_control(
			'badge_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-page-list-item-badge' => 'background-color: {{VALUE}}',
				],
                'condition' => [
                    'show_page_list_item_badge' => 'yes'
                ]
			]
		);

		$this->add_control(
			'page_list',
			[
				'label' => esc_html__( 'Repeater List', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'page_list_item_title' => esc_html__( 'New Page 1', 'crt-manage' ),
						'page_list_item_sub_title' => esc_html__( 'First Sub Title', 'crt-manage' ),
						// 'page_list_icon' => [
						// 	'value' => 'fas fa-rocket',
						// 	'library' => 'solid'
						// ],
						// 'page_list_label' =>[
						// ],
					],
					[
						'page_list_item_title' => esc_html__( 'New Page 2', 'crt-manage' ),
						'page_list_item_sub_title' => esc_html__( 'Second Sub Title', 'crt-manage' ),
						// 'page_list_icon' => [
						// 	'value' => 'far fa-flag',
						// 	'library' => 'solid'
						// ],
						// 'page_list_label' =>[
						// ],
					],
					[
						'page_list_item_title' => esc_html__( 'New Page 3', 'crt-manage' ),
						'page_list_item_sub_title' => esc_html__( 'Third Sub Title', 'crt-manage' ),
						// 'page_list_icon' => [
						// 	'value' => 'fas fa-grip-lines-vertical',
						// 	'library' => 'solid'
						// ],
						// 'page_list_label' =>[
						// ],
					],
				],
				'title_field' => '{{{ page_list_item_title }}}',
			]
		);

        $this->end_controls_section();

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles ====================
		// Section: General ---
		$this->start_controls_section(
			'section_style_page_list_general',
			[
				'label' => esc_html__( 'List Items', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_responsive_control(
			'page_list_item_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => '5',
					'right' => 0,
					'bottom' => '5',
					'left' => 0,
					'isLinked' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .crt-page-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'page_list_item_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => '5',
					'right' =>  '8',
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .crt-page-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'page_list_item_border_type',
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
					'{{WRAPPER}} .crt-page-list-item' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'page_list_item_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 1,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-page-list-item' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'page_list_item_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'page_list_item_radius',
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
					'{{WRAPPER}} .crt-page-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Title ---
		$this->start_controls_section(
			'section_style_page_list_title',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'list_item_style' );

		$this->start_controls_tab(
			'page_list_item_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'page_list_item_title_color',
			[
				'label'  => esc_html__( 'Title Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-page-list-item a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-page-list-item .crt-pl-title' => 'color: {{VALUE}}'
				],
			]
		);

		// $this->add_control(
		// 	'page_list_item_background_color',
		// 	[
		// 		'label'  => esc_html__( 'Background Color', 'crt-manage' ),
		// 		'type' => Controls_Manager::COLOR,
        //         'default' => '#00000000',
		// 		'selectors' => [
		// 			'{{WRAPPER}} .crt-page-list-item' => 'background-color: {{VALUE}}',
		// 		]
		// 	]
		// );

		$this->add_control(
			'page_list_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-page-list-item' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'page_list_item_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-page-list-item' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-page-list-item a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-page-list-item .crt-pl-title' => 'transition-duration: {{VALUE}}s'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'page_list_item_title_typo',
				'selector' => '{{WRAPPER}} .crt-page-list-item a, {{WRAPPER}} .crt-page-list-item .crt-pl-title',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					],
					'line_height'     => [
						'default' => [
							'size' => '0.8',
							'unit' => 'em',
						]
					],
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'page_list_item_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'page_list_item_title_color_hr',
			[
				'label'  => esc_html__( 'Title Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} li.crt-page-list-item a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} li.crt-page-list-item .crt-pl-title:hover' => 'color: {{VALUE}}',
				],
			]
		);

		// $this->add_control(
		// 	'page_list_item_background_color_hr',
		// 	[
		// 		'label'  => esc_html__( 'Background Color', 'crt-manage' ),
		// 		'type' => Controls_Manager::COLOR,
        //         'default' => '#00000000',
		// 		'selectors' => [
		// 			'{{WRAPPER}} .crt-page-list-item:hover' => 'background-color: {{VALUE}}',
		// 		]
		// 	]
		// );

        $this->add_control_title_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

        $this->add_control_title_pointer();

        $this->add_control_title_pointer_animation();

        $this->add_control_title_pointer_height();

		$this->add_responsive_control(
			'title_distance',
			[
				'label' => esc_html__( 'Bottom Distance', 'crt-manage' ),
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
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-page-list-item div a' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-page-list-item div .crt-pl-title' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();
		

		$this->start_controls_section(
			'section_style_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'page_list_item_sub_title_color',
			[
				'label'  => esc_html__( 'Sub Title Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#B6B6B6',
				'selectors' => [
					'{{WRAPPER}} .crt-page-list-item p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'page_list_item_sub_title_typo',
				'selector' => '{{WRAPPER}} .crt-page-list-item p',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '12',
							'unit' => 'px',
						],
					],
					'line_height'     => [
						'default' => [
							'size' => '0.8',
							'unit' => 'em',
						]
					]
				]
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Content --------
		$this->start_controls_section(
			'section_style_badge',
			[
				'label' => esc_html__( 'Badge', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-page-list-item-badge' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-page-list-item-badge' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'badge_vertical_align',
			[
				'label' => esc_html__( 'Vertical Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'crt-manage' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'crt-manage' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'crt-manage' ),
						'icon' => 'eicon-v-align-bottom',
					]
				],
				'prefix_class' => 'crt-pl-badge-',
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'badge_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-page-list-item-badge' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'badge_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-page-list-item-badge' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'badge_border_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 5,
					'bottom' => 2,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-page-list-item-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'badge_border_radius',
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
					'{{WRAPPER}} .crt-page-list-item-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();

		// Tab: Style ==============
		// Section: Content --------
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-page-list i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-page-list svg' => 'fill: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'icon_vertical_align',
			[
				'label' => esc_html__( 'Vertical Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'crt-manage' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'crt-manage' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'crt-manage' ),
						'icon' => 'eicon-v-align-bottom',
					]
				],
				'prefix_class' => 'crt-pl-icon-',
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-page-list i' => 'font-size: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-page-list svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-page-list i:before' => 'max-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-page-list-item-icon' => 'max-height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-page-list .crt-page-list-item-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
    }
	
    protected function render() {
        $settings = $this->get_settings_for_display();

        $class = '';

		// Pointer Class
		$page_title_pointer =  $settings['title_pointer'];
		$page_title_pointer_animation =  $settings['title_pointer_animation'];
		$pointer_item_class = (isset($settings['title_pointer']) && 'none' !== $settings['title_pointer']) ? 'crt-pointer-item crt-pl-title' : 'crt-no-pointer crt-pl-title';

		$class .= ' crt-pointer-'. $page_title_pointer;
		$class .= ' crt-pointer-line-fx crt-pointer-fx-'. $page_title_pointer_animation;

        echo '<div class="crt-page-list-wrap">';
            echo '<ul class="crt-page-list">';
            
                foreach ( $settings['page_list'] as $key=>$item ) {

					if ( 'yes' === $item['open_in_new_page'] ) {
						$target = '_blank';
					} else {
						$target = '_self';
					}

					if ( 'yes' === $item['show_page_list_item_badge_animation'] ) {
						$badge_anim_class = ' crt-pl-badge-anim-yes';
					} else {
						$badge_anim_class = '';
					}

					$this->add_render_attribute( 'page_list_item'. $key, 'class', 'crt-page-list-item elementor-repeater-item-'. $item['_id']. $class . $badge_anim_class );

                    if ( isset($item['page_list_item_icon']) && '' !== $item['page_list_item_icon']['value'] ) {
                        ob_start();
                        \Elementor\Icons_Manager::render_icon( $item['page_list_item_icon'], [ 'aria-hidden' => 'true' ] );
                        $icon = ob_get_clean();
                    }

                    if ( 'dynamic' === $item['page_list_item_type'] && isset($item['query_page_selection']) ) {
                        $wrapper_link = 'yes' === $settings['enable_wrapper_link'] ? get_the_permalink($item['query_page_selection']) : '';
                        
                        if ($wrapper_link) {
                            $this->add_link_attributes( 'wrapper_link'. $key, [
                                'url' => $wrapper_link,
                                'is_external' => 'yes' === $item['open_in_new_page'],
                            ]);
                        }

						echo '<li '. $this->get_render_attribute_string('page_list_item'. $key) .'>';

						if ($wrapper_link) {
							echo '<a class="crt-page-list-wrapper-link" '. $this->get_render_attribute_string( 'wrapper_link'. $key ) .'>';
						}
						
						echo (isset($item['page_list_item_icon']) && '' !== $item['page_list_item_icon']['value']) ? '<span class="crt-page-list-item-icon">'. $icon .'</span>' : '';
						
						echo '<div>';
						if ($wrapper_link) {
							echo '<span  class="'. $pointer_item_class .'">';
								echo get_the_title($item['query_page_selection']);
							echo '</span>';
						} else {
							echo '<a class="'. $pointer_item_class .'" href='. get_the_permalink($item['query_page_selection']) .' target='. $target .'>';
								echo get_the_title($item['query_page_selection']);
							echo '</a>';
						}

							if ( isset($item['page_list_item_sub_title']) && '' !== $item['page_list_item_sub_title']  ) {
								echo '<p>'. $item['page_list_item_sub_title'] .'</p>';
							}
						echo '</div>';

						echo 'yes' === $item['show_page_list_item_badge'] ? '<span class="crt-page-list-item-badge">'. $item['page_list_item_badge_text'] .'</span>' : '';
						
						if ($wrapper_link) {
							echo '</a>';
						}

						echo '</li>';

                    } else if ( 'custom' === $item['page_list_item_type'] && isset($item['page_list_item_title']) ) {
                        $wrapper_link = 'yes' === $settings['enable_wrapper_link'] && !empty($item['page_list_item_title_url']['url']) ? $item['page_list_item_title_url']['url'] : '';
                        
                        if ($wrapper_link) {
                            $this->add_link_attributes( 'wrapper_link'. $key, [
                                'url' => $wrapper_link,
                                'is_external' => 'yes' === $item['open_in_new_page'],
                            ]);
                        }
                        
                        if ( ! empty( $item['page_list_item_title_url']['url'] ) ) {
                            $this->add_link_attributes( 'title_link_'. $key, $item['page_list_item_title_url'] );
						}

                        echo '<li '. $this->get_render_attribute_string('page_list_item'. $key) .'>';

                        if ($wrapper_link) {
                            echo '<a class="crt-page-list-wrapper-link" '. $this->get_render_attribute_string( 'wrapper_link' . $key ) .'>';
                        }
                        echo (isset($item['page_list_item_icon']) && '' !== $item['page_list_item_icon']['value']) ? '<span class="crt-page-list-item-icon">'. $icon .'</span>' : '';

						echo '<div>';
							if ($wrapper_link) {
								echo '<span  class="'. $pointer_item_class .'">';
									echo $item['page_list_item_title'];
								echo '</span>';
							} else {
								echo '<a  '. $this->get_render_attribute_string( 'title_link_'. $key ) .' class="'. $pointer_item_class .'">';
									echo $item['page_list_item_title'];
								echo '</a>';
							}
							if ( isset($item['page_list_item_sub_title']) && '' !== $item['page_list_item_sub_title'] ) {
								echo '<p>'. $item['page_list_item_sub_title'] .'</p>';
							}
                        echo '</div>';

                        echo 'yes' === $item['show_page_list_item_badge'] ? '<span class="crt-page-list-item-badge">'. $item['page_list_item_badge_text'] .'</span>' : '';

                        if ($wrapper_link) {
                            echo '</a>';
                        }

                        echo '</li>';
                    }
                }

            echo '</ul>';
        echo '</div>';
    }
}