<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Post_Navigation extends Widget_Base {
	
	public function get_name() {
		return 'crt-post-navigation';
	}

	public function get_title() {
		return esc_html__( 'Post Navigation', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-post-navigation';
	}

	public function get_categories() {
        return ['crt_manage_single'];
    }

	public function get_keywords() {
		return [ 'navigation', 'arrows', 'pagination' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function add_control_display_on_separate_lines() {
		$this->add_responsive_control(
			'display_on_separate_lines',
			[
				'label' => esc_html__( 'Display on Separate Lines', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => '',
				'condition' => [
					'post_nav_layout' => 'static'
				],
			]
		);
	}

	public function add_control_post_nav_layout() {
		$this->add_control(
			'post_nav_layout',
			[
				'label' => esc_html__( 'Layout', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'static',
				'options' => [
					'static' => esc_html__( 'Static Left/Right', 'crt-manage' ),
					'fixed' => esc_html__( 'Fixed Left/Right', 'crt-manage' ),
				],
			]
		);
	}

    public function add_control_post_nav_fixed_default_align() {
        $this->add_control(
            'post_nav_fixed_default_align',
            [
                'label' => esc_html__( 'Horizontal Align', 'crt-manage' ),
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
                'selectors_dictionary' => [
                    'left' => 'left: 0;',
                    'center' => 'left: 50%;-webkit-transform: translateX(-50%);transform: translateX(-50%);',
                    'right' => 'right: 0;'
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-fixed-default-wrap' => '{{VALUE}}',
                ],
                'condition' => [
                    'post_nav_layout' => 'fixed-default',
                ]
            ]
        );
    }

    public function add_control_post_nav_fixed_vr() {
        $this->add_responsive_control(
            'post_nav_fixed_vr',
            [
                'label' => esc_html__( 'Vertical Position', 'crt-manage' ),
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
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-fixed.crt-post-navigation' => 'top: {{SIZE}}%;',
                ],
                'condition' => [
                    'post_nav_layout' => 'fixed',
                ],
            ]
        );
    }


    public function add_control_post_nav_arrows_loc() {
        $this->add_control(
            'post_nav_arrows_loc',
            [
                'label' => esc_html__( 'Arrows Location', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'separate',
                'options' => [
                    'separate' => esc_html__( 'Separate', 'crt-manage' ),
                    'label' => esc_html__( 'Next to Label', 'crt-manage' ),
                    'title' => esc_html__( 'Next to Title', 'crt-manage' ),
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'post_nav_arrows',
                            'operator' => '!=',
                            'value' => '',
                        ],
                        [
                            'name' => 'post_nav_layout',
                            'operator' => '!=',
                            'value' => 'fixed',
                        ],
                    ],
                ],
            ]
        );
    }

    public function add_control_post_nav_title() {
        $this->add_control(
            'post_nav_title',
            [
                'label' => esc_html__( 'Show Title', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'post_nav_layout!' => 'fixed'
                ]
            ]
        );
    }

    public function add_controls_group_post_nav_image() {
        $this->add_control(
            'post_nav_image',
            [
                'label' => esc_html__( 'Show Post Thumbnail', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'post_nav_image_bg',
            [
                'label' => esc_html__( 'Set as Background Image', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'post_nav_image',
                            'operator' => '!=',
                            'value' => '',
                        ],
                        [
                            'name' => 'post_nav_layout',
                            'operator' => '!=',
                            'value' => 'fixed',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'post_nav_image_hover',
            [
                'label' => esc_html__( 'Show Image on Hover', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'post_nav_image',
                            'operator' => '!=',
                            'value' => '',
                        ],
                        [
                            'name' => 'post_nav_layout',
                            'operator' => '==',
                            'value' => 'fixed',
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'post_nav_image_width_crop',
                'default' => 'medium',
                'condition' => [
                    'post_nav_image' => 'yes',
                    // 'post_nav_layout!' => 'fixed'
                ],
            ]
        );

        $this->add_responsive_control(
            'post_nav_image_width',
            [
                'label' => __( 'Image Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 140,
                ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-navigation img' => 'width: {{SIZE}}px;',
                ],
                'condition' => [
                    'post_nav_image' => 'yes',
                    'post_nav_image_bg!' => 'yes',
                    'post_nav_layout!' => 'fixed'
                ],
            ]
        );

        $this->add_responsive_control(
            'post_nav_image_distance',
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-prev img' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-post-nav-next img' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'post_nav_image' => 'yes',
                    'post_nav_image_bg!' => 'yes',
                    'post_nav_layout!' => 'fixed'
                ],
            ]
        );
    }

    public function add_controls_group_post_nav_back() {
        $this->add_control(
            'post_nav_back',
            [
                'label' => esc_html__( 'Show Back Button', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'post_nav_layout!' => 'fixed',
                ]
            ]
        );

        $this->add_control(
            'post_nav_back_link',
            [
                'label' => esc_html__( 'Back Button Link', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'post_nav_back' => 'yes',
                    'post_nav_layout!' => 'fixed',
                ]
            ]
        );
    }

    public function add_control_post_nav_query() {
        // Get Available Taxonomies
        $post_taxonomies = \CrtAddons\Classes\Utilities::get_custom_types_of( 'tax', false );
        $post_taxonomies['all'] = esc_html__( 'All', 'crt-manage' );

        $this->add_control(
            'post_nav_query',
            [
                'label' => esc_html__( 'Navigate Through', 'crt-manage' ),
                'description' => esc_html__( 'If you select a taxonomy, Next and Previous posts will be in the same taxonomy term as the current post.', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => array_reverse($post_taxonomies),
                'default' => 'all',
                'separator' => 'before',
            ]
        );
    }

    public function add_control_post_nav_align_vr() {
        $this->add_control(
            'post_nav_align_vr',
            [
                'label' => esc_html__( 'Vertical Align', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'crt-manage' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Middle', 'crt-manage' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Bottom', 'crt-manage' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-navigation a' => 'align-items: {{VALUE}}',
                ],
                'separator' => 'before'
            ]
        );
    }

    public function add_controls_group_post_nav_overlay_style() {
        $this->start_controls_tabs(
            'tabs_post_nav_overlay_style',
            [
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'post_nav_image',
                            'operator' => '!=',
                            'value' => '',
                        ],
                        [
                            'name' => 'post_nav_image_bg',
                            'operator' => '!=',
                            'value' => '',
                        ],
                    ],
                ],
            ]
        );

        $this->start_controls_tab(
            'tab_post_nav_overlay_normal',
            [
                'label' => __( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'post_nav_overlay_color',
            [
                'label'  => esc_html__( 'Overlay Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-overlay' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'post_nav_background_filters',
                'selector' => '{{WRAPPER}} .crt-post-nav-overlay',
                'condition' => [
                    'post_nav_image' => 'yes',
                    'post_nav_image_bg' => 'yes'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_post_nav_overlay_hover',
            [
                'label' => __( 'Hover', 'crt-manage' ),
            ]
        );


        $this->add_control(
            'post_nav_overlay_color_hover',
            [
                'label'  => esc_html__( 'Overlay Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-post-navigation:hover .crt-post-nav-overlay' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'post_nav_background_filters_hover',
                'selector' => '{{WRAPPER}} .crt-post-navigation:hover .crt-post-nav-overlay',
                'condition' => [
                    'post_nav_image' => 'yes',
                    'post_nav_image_bg' => 'yes'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
    }

    public function add_section_style_post_nav_back_btn() {
        $this->start_controls_section(
            'section_style_post_nav_back_btn',
            [
                'label' => esc_html__( 'Back Button', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'post_nav_back' => 'yes',
                    'post_nav_layout!' => 'fixed'
                ]
            ]
        );

        $this->start_controls_tabs( 'tabs_grid_post_nav_back_btn_style' );

        $this->start_controls_tab(
            'tab_grid_post_nav_back_btn_normal',
            [
                'label' => __( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'post_nav_back_btn_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-back span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'post_nav_back_btn_fill_color',
            [
                'label'  => esc_html__( 'Fill Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-back span' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_post_nav_back_btn_hover',
            [
                'label' => __( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'post_nav_back_btn_color_hr',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#54595f',
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-back a:hover span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'post_nav_back_btn_fill_color_ht',
            [
                'label'  => esc_html__( 'Fill Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-back a:hover span' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'post_nav_back_btn_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-back span' => 'transition: background-color {{VALUE}}s, color {{VALUE}}s',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'post_nav_back_btn_size',
            [
                'label' => esc_html__( 'Box Size', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-back a' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-post-nav-back span' => 'width: calc({{SIZE}}px / 2 - {{post_nav_back_btn_gutter.SIZE}}px); height: calc({{SIZE}}px / 2 - {{post_nav_back_btn_gutter.SIZE}}px);',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'post_nav_back_btn_border_width',
            [
                'label' => esc_html__( 'Box Border Width', 'crt-manage' ),
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
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-back span' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'post_nav_back_btn_gutter',
            [
                'label' => esc_html__( 'Box Gutter', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-back span' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'post_nav_back_btn_distance',
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-back' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function add_section_style_post_nav_title() {
        $this->start_controls_section(
            'section_style_post_nav_title',
            [
                'label' => esc_html__( 'Title', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'post_nav_title' => 'yes',
                    'post_nav_layout!' => 'fixed'
                ]
            ]
        );

        $this->start_controls_tabs( 'tabs_grid_post_nav_title_style' );

        $this->start_controls_tab(
            'tab_grid_post_nav_title_normal',
            [
                'label' => __( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'post_nav_title_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-labels h5' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_post_nav_title_hover',
            [
                'label' => __( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'post_nav_title_color_hr',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#54595f',
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-labels h5:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'post_nav_title_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-post-nav-labels h5' => 'transition: color {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'post_nav_title_typography',
                'selector' => '{{WRAPPER}} .crt-post-nav-labels h5'
            ]
        );

        $this->end_controls_section();
    }

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_navigation',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_post_nav_layout();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'post-navigation', 'post_nav_layout', ['pro-fx', 'pro-fd'] );

		$this->add_control_post_nav_fixed_default_align();

		$this->add_control_post_nav_fixed_vr();

		$this->add_control(
			'post_nav_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control_post_nav_arrows_loc();
		
		$this->add_control(
			'post_nav_arrow_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICON,
				'default' => 'svg-angle-2-left',
				'condition' => [
					'post_nav_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'post_nav_labels',
			[
				'label' => esc_html__( 'Show Labels', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control(
			'post_nav_prev_text',
			[
				'label' => esc_html__( 'Previous Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Previous Post',
				'condition' => [
					'post_nav_labels' => 'yes',
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control(
			'post_nav_next_text',
			[
				'label' => esc_html__( 'Next Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Next Post',
				'condition' => [
					'post_nav_labels' => 'yes',
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control_post_nav_title();

		$this->add_controls_group_post_nav_image();

		$this->add_controls_group_post_nav_back();

		$this->add_control(
			'post_nav_dividers',
			[
				'label' => esc_html__( 'Show Dividers', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before',
				'condition' => [
					'post_nav_layout' => 'static'
				],
			]
		);

		$this->add_control_display_on_separate_lines();

		$this->add_control_post_nav_query();

		$this->end_controls_section();

		// Styles ====================
		// Section: General ----------
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_nav_layout!' => 'fixed'
				]
			]
		);

		$this->add_controls_group_post_nav_overlay_style();

		$this->add_control(
			'post_nav_background',
			[
				'label'  => esc_html__( 'Section Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-post-navigation-wrap' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_nav_divider_color',
			[
				'label'  => esc_html__( 'Divider Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .crt-post-navigation-wrap' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-post-nav-divider' => 'background-color: {{VALUE}}',
				],
				'separator' => 'before',
				'condition' => [
					'post_nav_layout' => 'static',
					'post_nav_dividers' => 'yes'
				]
			]
		);

		$this->add_control(
			'post_nav_divider_width',
			[
				'label' => esc_html__( 'Divider Width', 'crt-manage' ),
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
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-nav-divider' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-navigation-wrap' => 'border-width: {{SIZE}}{{UNIT}} 0 {{SIZE}}{{UNIT}} 0;',
				],
				'condition' => [
					'post_nav_layout' => 'static',
					'post_nav_dividers' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'post_nav_padding',
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
					'{{WRAPPER}} .crt-post-navigation-wrap.crt-post-nav-dividers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-nav-bg-images .crt-post-navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_post_nav_align_vr();

		$this->end_controls_section();

		// Styles ====================
		// Section: Arrows -----------
		$this->start_controls_section(
			'section_style_post_nav_arrow',
			[
				'label' => esc_html__( 'Arrows', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_nav_arrows' => 'yes'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_grid_post_nav_arrow_style' );

		$this->start_controls_tab(
			'tab_grid_post_nav_arrow_normal',
			[
				'label' => __( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'post_nav_arrow_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-post-navigation i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-post-navigation svg path' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-posts-navigation-svg-wrapper svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_nav_arrow_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-post-navigation i' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-posts-navigation-svg-wrapper' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'post_nav_arrow_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'default' => '#E8E8E8',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-post-navigation i' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-posts-navigation-svg-wrapper' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_post_nav_arrow_hover',
			[
				'label' => __( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'post_nav_arrow_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-post-navigation i:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-posts-navigation-svg-wrapper:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_nav_arrow_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-post-navigation i:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-posts-navigation-svg-wrapper:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'post_nav_arrow_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-post-navigation i:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-posts-navigation-svg-wrapper:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'post_nav_arrow_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-post-navigation i' => 'transition: color {{VALUE}}s, background-color {{VALUE}}s, border-color {{VALUE}}s',
					'{{WRAPPER}} .crt-posts-navigation-svg-wrapper svg' => 'transition: fill {{VALUE}}s',
					'{{WRAPPER}} .crt-posts-navigation-svg-wrapper' => 'transition: background-color {{VALUE}}s, border-color {{VALUE}}s',
					'{{WRAPPER}} .crt-post-nav-fixed.crt-post-nav-hover img' => 'transition: all {{VALUE}}s ease',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'post_nav_arrow_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
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
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-navigation i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-navigation svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-navigation-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-navigation-wrap svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'post_nav_arrow_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-navigation-wrap i' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-navigation i' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-posts-navigation-svg-wrapper' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-nav-fixed.crt-post-nav-prev img' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-nav-fixed.crt-post-nav-next img' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_nav_arrow_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-navigation-wrap i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-navigation i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-posts-navigation-svg-wrapper' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-nav-fixed.crt-post-navigation img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_nav_arrow_distance',
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-nav-prev i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-nav-prev .crt-posts-navigation-svg-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-nav-next i' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-post-nav-next .crt-posts-navigation-svg-wrapper' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control(
			'post_nav_arrow_border_type',
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
					'{{WRAPPER}} .crt-post-navigation i' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-posts-navigation-svg-wrapper' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'post_nav_arrow_border_width',
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
					'{{WRAPPER}} .crt-post-navigation i' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-posts-navigation-svg-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'post_nav_arrow_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'post_nav_arrow_radius',
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
					'{{WRAPPER}} .crt-post-navigation i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-posts-navigation-svg-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'post_nav_img_radius',
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
					'{{WRAPPER}} .crt-post-navigation img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Back Button ------
		$this->add_section_style_post_nav_back_btn();

		// Styles ====================
		// Section: Labels -----------
		$this->start_controls_section(
			'section_style_post_nav_label',
			[
				'label' => esc_html__( 'Labels', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_nav_labels' => 'yes',
					'post_nav_layout!' => 'fixed'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_grid_post_nav_label_style' );

		$this->start_controls_tab(
			'tab_grid_post_nav_label_normal',
			[
				'label' => __( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'post_nav_label_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-post-nav-labels span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .crt-post-nav-labels span',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'post_nav_label_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-post-nav-labels span' => 'transition: color {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_post_nav_label_hover',
			[
				'label' => __( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'post_nav_label_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .crt-post-nav-labels span:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Styles ====================
		// Section: Title ------------
		$this->add_section_style_post_nav_title();

	}

	// Arrow Icon
	public function render_arrow_by_location( $settings, $location, $dir ) {
		if ( 'fixed' === $settings['post_nav_layout'] ) {
			$settings['post_nav_arrows_loc'] = 'separate';
		}

		if ( 'yes' === $settings['post_nav_arrows'] && $location === $settings['post_nav_arrows_loc'] ) {
			if (  false !== strpos( $settings['post_nav_arrow_icon'], 'svg-' ) ) {
				echo  '<div class="crt-posts-navigation-svg-wrapper">' . Utilities::get_crt_icon( $settings['post_nav_arrow_icon'], $dir ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo  Utilities::get_crt_icon( $settings['post_nav_arrow_icon'], $dir ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

        $settings['post_nav_image'] = 'yes';
        $settings['post_nav_image_bg'] = 'yes';
        $settings['post_nav_back'] = 'yes';
        $settings['post_nav_title'] = 'yes';
        $settings['post_nav_back_link'] = '#';

		wp_reset_postdata();

		// Set Query
		$nav_query = isset($settings['post_nav_query']) ? $settings['post_nav_query'] : 'category';

		// Get Previous and Next Posts

        $prev_post = get_adjacent_post( true, '', true, $nav_query );
        $next_post = get_adjacent_post( true, '', false, $nav_query );

		// Layout Class
		$layout_class = 'crt-post-navigation crt-post-nav-'. $settings['post_nav_layout'];

		// Show Image on Hover
		if ( (isset($settings['post_nav_image_hover']) && 'yes' === $settings['post_nav_image_hover']) ) {
			$layout_class .= ' crt-post-nav-hover';
		}

		$prev_image_url = '';
		$next_image_url = '';
		$prev_post_bg = '';
		$next_post_bg = '';

		// Image URLs
		if ( ! empty($prev_post) && 'yes' === $settings['post_nav_image'] ) {
            $prev_image_url = get_the_post_thumbnail_url( $prev_post->ID );
		}
		if ( ! empty($next_post) && 'yes' === $settings['post_nav_image'] ) {
            $next_image_url = get_the_post_thumbnail_url( $next_post->ID );
		}

		// Background Images
		if ( 'yes' === $settings['post_nav_image'] && 'yes' === $settings['post_nav_image_bg'] ) {
			if ( 'fixed' !== $settings['post_nav_layout'] ) {
				if ( ! empty($prev_post) ) {
					$prev_post_bg = ' style="background-image: url('. esc_url($prev_image_url) .')"';
				}

				if ( ! empty($next_post) ) {
					$next_post_bg = ' style="background-image: url('. esc_url($next_image_url) .')"';
				}
			}
		}

		// Navigation Wrapper
		if ( 'fixed' !== $settings['post_nav_layout'] ) {
			// Layout Class
			$wrapper_class = 'crt-post-nav-'. $settings['post_nav_layout'] .'-wrap';

			// Dividers
			if ( 'static' === $settings['post_nav_layout'] && 'yes' === $settings['post_nav_dividers'] ) {
				$wrapper_class .= ' crt-post-nav-dividers';
			}

			// Background Images
			if ( 'yes' === $settings['post_nav_image'] && 'yes' === $settings['post_nav_image_bg'] ) {
				$wrapper_class .= ' crt-post-nav-bg-images';
			}

			echo '<div class="crt-post-navigation-wrap elementor-clearfix '. esc_attr($wrapper_class) .'">';
		}

		// Previous Post
		echo '<div class="crt-post-nav-prev '. esc_attr($layout_class) .'"'. $prev_post_bg .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			if ( ! empty($prev_post) ) {
				echo '<a href="'. esc_url( get_permalink($prev_post->ID) ) .'" class="elementor-clearfix">';
					// Left Arrow
					$this->render_arrow_by_location( $settings, 'separate', 'left' );

					// Post Thumbnail
					if ( 'yes' === $settings['post_nav_image'] ) {
						if ( '' === $settings['post_nav_image_bg'] || 'fixed' === $settings['post_nav_layout'] ) {
							echo '<img src="'. esc_url( $prev_image_url ) .'" alt="">';
						}
					}

					// Label & Title
					if ( 'fixed' !== $settings['post_nav_layout'] ) {
						echo '<div class="crt-post-nav-labels">';
							// Prev Label
							if ( 'yes' === $settings['post_nav_labels'] ) {
								echo '<span>';
									$this->render_arrow_by_location( $settings, 'label', 'left' );
									echo esc_html__( $settings['post_nav_prev_text'] );
								echo '</span>';
							}

							// Post Title
							if ( 'yes' === $settings['post_nav_title'] ) {
								echo '<h5>';
									$this->render_arrow_by_location( $settings, 'title', 'left' );
									echo esc_html( get_the_title($prev_post->ID) );
								echo '</h5>';
							}
						echo '</div>';
					}
				echo '</a>';

				// Image Overlay
				if ( 'yes' === $settings['post_nav_image_bg'] ) {
					echo '<div class="crt-post-nav-overlay"></div>';
				}
			}
		echo '</div>';


		// Next Post
		echo '<div class="crt-post-nav-next '. esc_attr($layout_class) .'"'. $next_post_bg .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			if ( ! empty($next_post) ) {
				echo '<a href="'. esc_url( get_permalink($next_post->ID) ) .'" class="elementor-clearfix">';
					// Label & Title
					if ( 'fixed' !== $settings['post_nav_layout'] ) {
						echo '<div class="crt-post-nav-labels">';
							// Next Label
							if ( 'yes' === $settings['post_nav_labels'] ) {
								echo '<span>';
									echo esc_html__( $settings['post_nav_next_text'] );
									$this->render_arrow_by_location( $settings, 'label', 'right' );
								echo '</span>';
							}

							// Post Title
							if ( 'yes' === $settings['post_nav_title'] ) {
								echo '<h5>';
									echo esc_html( get_the_title($next_post->ID) );
									$this->render_arrow_by_location( $settings, 'title', 'right' );
								echo '</h5>';
							}
						echo '</div>';
					}

					// Post Thumbnail
					if ( 'yes' === $settings['post_nav_image'] ) {
						if ( '' === $settings['post_nav_image_bg'] || 'fixed' === $settings['post_nav_layout'] ) {
							echo '<img src="'. esc_url( $next_image_url ) .'" alt="">';
						}
					}

					// Right Arrow
					$this->render_arrow_by_location( $settings, 'separate', 'right' );
				echo '</a>';

				// Image Overlay
				if ( 'yes' === $settings['post_nav_image_bg'] ) {
					echo '<div class="crt-post-nav-overlay"></div>';
				}
			}
		echo '</div>';

		// End Navigation Wrapper
		if ( 'fixed' !== $settings['post_nav_layout'] ) {
			echo '</div>';
		}

	}
	
}