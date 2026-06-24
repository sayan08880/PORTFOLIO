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
use Elementor\Icons;
use Elementor\Utils;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Progress_Bar extends Widget_Base {
		
	public function get_name() {
		return 'crt-progress-bar';
	}

	public function get_title() {
		return esc_html__( 'Progress Bar', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-skill-bar';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
	}

	public function get_keywords() {
		return [ 'progress bar', 'skill bar', 'skills bar', 'percentage bar', 'bar chart' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_script_depends() {
		return [ 'jquery-numerator', 'crt-progress-bar' ];
	}

	public function get_style_depends() {
		return [ 'crt-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://crthemes.com/contact/?ref=rea-plugin-panel-progress-bar-help-btn';
    		return 'https://crthemes.com/contact';
    }

    public function add_control_layout() {
        $this->add_control(
            'layout',
            [
                'label' => esc_html__( 'Layout', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'hr-line',
                'options' => [
                    'hr-line' => esc_html__( 'Horizontal Line', 'crt-manage' ),
                    'vr-line' => esc_html__( 'Vertical Line', 'crt-manage' ),
                    'circle' => esc_html__( 'Circle', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-prbar-layout-',
                'render_type' => 'template',
            ]
        );
    }

	public function add_control_line_width() {
        $this->add_responsive_control(
            'line_width',
            [
                'label' => esc_html__( 'Line Width', 'crt-manage' ),
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
                    'size' => 15,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'condition' => [
                    'layout' => 'circle',
                ],
            ]
        );
    }

	public function add_control_prline_width() {
        $this->add_responsive_control(
            'prline_width',
            [
                'label' => esc_html__( 'Progress Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'condition' => [
                    'layout' => 'circle',
                ],
            ]
        );
    }

	public function add_control_stripe_switcher() {
		$this->add_control(
			'stripe_switcher',
			[
				'label' => sprintf( __( 'Show Stripe %s', 'crt-manage' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'no-distance'
			]
		);
	}

	public function add_control_stripe_anim() {
        $this->add_control(
            'stripe_anim',
            [
                'label' => esc_html__( 'Stripe Direction', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'left' => esc_html__( 'Left', 'crt-manage' ),
                    'right' => esc_html__( 'Right', 'crt-manage' ),
                ],
                'condition' => [
                    'layout!' => 'circle',
                    'stripe_switcher' => 'yes',
                ],
                'prefix_class' => 'crt-prbar-stripe-anim-',
                'render_type' => 'template',
            ]
        );
    }

	public function add_control_anim_loop() {
		$this->add_control(
			'anim_loop',
			[
				'label' => __( 'Animation Loop', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'no-distance',
				'separator' => 'before',
			]
		);
	}

	public function add_control_anim_loop_delay() {
        $this->add_control(
            'anim_loop_delay',
            [
                'label' => esc_html__( 'Loop Delay', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 2,
                'max' => 50,
                'step' => 1,
                'render_type' => 'template',
                'condition' => [
                    'anim_loop' => 'yes'
                ]
            ]
        );
    }

	protected function register_controls() {

		// Section: General ----------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
			]
		);

		$this->add_control_layout();

		$this->add_control(
			'max_value',
			[
				'label' => esc_html__( 'Max Value', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 100,
				'min' => 0,
				'step' => 1,
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'counter_value',
			[
				'label' => esc_html__( 'Counter Value', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 70,
				'min' => 0,
				'step' => 1,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Title',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_position',
			[
				'label' => esc_html__( 'Title Position', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inside',
				'options' => [
					'inside' => esc_html__( 'Inside', 'crt-manage' ),
					'outside' => esc_html__( 'Outside', 'crt-manage' ),
				],
				'prefix_class' => 'crt-pbar-title-pos-',
				'render_type' => 'template',
				'condition' => [
					'layout!' => 'vr-line',
				],
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'counter_switcher',
			[
				'label' => esc_html__( 'Show Counter', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'counter_position',
			[
				'label' => esc_html__( 'Counter Position', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inside',
				'options' => [
					'inside' => esc_html__( 'Inside', 'crt-manage' ),
					'outside' => esc_html__( 'Outside', 'crt-manage' ),
				],
				'prefix_class' => 'crt-pbar-counter-pos-',
				'render_type' => 'template',
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'counter_follow_line',
			[
				'label' => esc_html__( 'Follow Pr. Line', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_position' => 'inside',
					'layout' => 'hr-line',
				],
			]
		);

		$this->add_control(
			'counter_prefix',
			[
				'label' => esc_html__( 'Counter Prefix', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'counter_suffix',
			[
				'label' => esc_html__( 'Counter Suffix', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '%',
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'counter_separator',
			[
				'label' => esc_html__( 'Show Thousand Separator', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ----------
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'crt-manage' ),
			]
		);

		$this->add_responsive_control(
			'circle_size',
			[
				'label' => esc_html__( 'Circle Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'widescreen_default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'laptop_default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'tablet_extra_default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'mobile_extra_default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-circle' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'layout' => 'circle',
				],
			]
		);

		$this->add_control_line_width();

		$this->add_control_prline_width();

		$this->add_responsive_control(
			'line_size',
			[
				'label' => esc_html__( 'Line Size', 'crt-manage' ),
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
					'size' => 27,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-hr-line' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-prbar-vr-line' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout!' => 'circle',
				],
			]
		);

		$this->add_responsive_control(
			'vr_line_height',
			[
				'label' => esc_html__( 'Vertical Line Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 277,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-vr-line' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => 'vr-line',
				],
			]
		);

		$this->add_control(
			'anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 10,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-circle-prline' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-prbar-hr-line-inner' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-prbar-vr-line-inner' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],				
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'anim_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-circle-prline' => '-webkit-transition-delay: {{VALUE}}s; transition-delay: {{VALUE}}s;',
					'{{WRAPPER}} .crt-prbar-hr-line-inner' => '-webkit-transition-delay: {{VALUE}}s; transition-delay: {{VALUE}}s;',
					'{{WRAPPER}} .crt-prbar-vr-line-inner' => '-webkit-transition-delay: {{VALUE}}s; transition-delay: {{VALUE}}s;',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'anim_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::crt_animation_timings(),
				'default' => 'ease-default',
			]
		);

		$this->add_control_anim_loop();
	
		$this->add_control_anim_loop_delay();

		$this->end_controls_section(); // End Controls Section

		
		// Styles
		// Section: General ----------
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wrapper_section',
			[
				'label' => esc_html__( 'Wrapper', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'general_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f4f4f4',
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-circle-line' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .crt-prbar-hr-line' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-prbar-vr-line' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'circle_line_bg_color',
			[
				'label' => esc_html__( 'Inactive Line Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#dddddd',
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-circle-line' => 'stroke: {{VALUE}}',
				],
                'condition' => [
					'layout' => 'circle',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'general_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-prbar-hr-line, {{WRAPPER}} .crt-prbar-vr-line, {{WRAPPER}} .crt-prbar-circle svg',
			]
		);

		$this->add_control(
			'general_border_type',
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
					'{{WRAPPER}} .crt-prbar-hr-line' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-prbar-vr-line' => 'border-style: {{VALUE}};',
				],
				'condition' => [
					'layout!' => 'circle'
				]
			]
		);

		$this->add_responsive_control(
			'general_border_width',
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
					'{{WRAPPER}} .crt-prbar-hr-line' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-prbar-vr-line' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'general_border_type!' => 'none',
					'layout!' => 'circle'
				],
			]
		);

		$this->add_control(
			'general_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e5e5e5',
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-hr-line' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-prbar-vr-line' => 'border-color: {{VALUE}}',
				],
                'condition' => [
					'general_border_type!' => 'none',
					'layout!' => 'circle'
				],
			]
		);

		$this->add_control(
			'general_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-hr-line' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-prbar-prline-rounded-yes .crt-prbar-hr-line-inner' => 'border-top-right-radius: calc({{RIGHT}}{{UNIT}} - {{general_border_width.RIGHT}}{{general_border_width.UNIT}});border-bottom-right-radius: calc({{BOTTOM}}{{UNIT}} - {{general_border_width.BOTTOM}}{{general_border_width.UNIT}});',
					'{{WRAPPER}} .crt-prbar-vr-line' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-prbar-prline-rounded-yes .crt-prbar-vr-line-inner' => 'border-top-right-radius: calc({{RIGHT}}{{UNIT}} - {{general_border_width.RIGHT}}{{general_border_width.UNIT}});border-top-left-radius: calc({{TOP}}{{UNIT}} - {{general_border_width.TOP}}{{general_border_width.UNIT}});',
				],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],			
				'condition' => [
					'layout!' => 'circle',
				],
			]
		);

		$this->add_control(
			'prline_section',
			[
				'label' => esc_html__( 'Progress Line', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
            'circle_prline_bg_type',
            [
                'label' => esc_html__( 'Background Type', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'color',
                'options' => [
                    'color' => [
                        'title' => esc_html__( 'Classic', 'crt-manage' ),
                        'icon' => 'fa fa-paint-brush',
                    ],
                    'gradient' => [
                        'title' => esc_html__( 'Gradient', 'crt-manage' ),
                        'icon' => 'fa fa-barcode',
                    ],
                ],
                'condition' => [
					'layout' => 'circle',
				],
            ]
        );

		$this->add_control(
			'circle_prline_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'condition' => [
					'circle_prline_bg_type' => 'color',
					'layout' => 'circle',
				],
			]
		);

		$this->add_control(
			'circle_prline_bg_color_a',
			[
				'label' => esc_html__( 'Background Color A', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#61ce70',
				'condition' => [
					'circle_prline_bg_type' => 'gradient',
					'layout' => 'circle',
				],
			]
		);

		$this->add_control(
			'circle_prline_bg_color_b',
			[
				'label' => esc_html__( 'Background Color B', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4054b2',
				'condition' => [
					'circle_prline_bg_type' => 'gradient',
					'layout' => 'circle',
				],
			]
		);

		$this->add_control(
			'circle_prline_grad_angle',
			[
				'label' => esc_html__( 'Gradient Angle', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [
					'circle_prline_bg_type' => 'gradient',
					'layout' => 'circle',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'prline_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .crt-prbar-hr-line-inner, {{WRAPPER}} .crt-prbar-vr-line-inner',
				'condition' => [
					'layout!' => 'circle',
				],
			]
		);

		$this->add_control(
			'prline_round',
			[
				'label' => esc_html__( 'Rounded Line', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-circle-prline' => 'stroke-linecap: round;',
				],
				'prefix_class' => 'crt-prbar-prline-rounded-',
				'render_type' => 'template',
			]
		);

		$this->add_control_stripe_switcher();

		$this->add_control_stripe_anim();

		$this->add_control(
			'title_section',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#C7C6C6',
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .crt-prbar-title',
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'title_distance',
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
					'{{WRAPPER}}.crt-prbar-layout-hr-line .crt-prbar-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-prbar-layout-circle.crt-pbar-title-pos-inside .crt-prbar-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-prbar-layout-circle.crt-pbar-title-pos-outside .crt-prbar-title' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-prbar-layout-vr-line .crt-prbar-title' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_control(
			'subtitle_section',
			[
				'label' => esc_html__( 'Subtitle', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'subtitle!' => '',
				],
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#C7C6C6',
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-subtitle' => 'color: {{VALUE}}',
				],
				'condition' => [
					'subtitle!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .crt-prbar-subtitle',
				'condition' => [
					'subtitle!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'subtitle_distance',
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-prbar-layout-hr-line .crt-prbar-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-prbar-layout-circle.crt-pbar-title-pos-inside .crt-prbar-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-prbar-layout-circle.crt-pbar-title-pos-outside .crt-prbar-subtitle' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-prbar-layout-vr-line .crt-prbar-subtitle' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'subtitle!' => '',
				],
			]
		);

		$this->add_control(
			'counter_section',
			[
				'label' => esc_html__( 'Counter', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'counter_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#C7C6C6',
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-counter' => 'color: {{VALUE}}',
				],
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'counter_typography',
				'selector' => '{{WRAPPER}} .crt-prbar-counter',
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'counter_distance',
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-prbar-layout-hr-line .crt-prbar-counter' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-prbar-layout-vr-line .crt-prbar-counter' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-prbar-layout-circle.crt-pbar-counter-pos-outside .crt-prbar-counter' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_position!' => 'inside',
					'layout!' => 'hr-line'
				],
			]
		);

		$this->add_control(
			'counter_prefix_section',
			[
				'label' => esc_html__( 'Counter Prefix', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_prefix!' => ''
				],
			]
		);

		$this->add_control(
			'counter_prefix_vr_position',
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
				'default' => 'middle',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-counter-value-prefix' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_prefix!' => ''
				],
			]
		);

		$this->add_responsive_control(
			'counter_prefix_size',
			[
				'label' => esc_html__( 'Font Size', 'crt-manage' ),
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-counter-value-prefix' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_prefix!' => ''
				],
			]
		);

		$this->add_responsive_control(
			'counter_prefix_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-prbar-counter-value-prefix' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_prefix!' => ''
				],
			]
		);

		$this->add_control(
			'counter_suffix_section',
			[
				'label' => esc_html__( 'Counter Suffix', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_suffix!' => ''
				],
			]
		);

		$this->add_control(
			'counter_suffix_vr_position',
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
				'default' => 'middle',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-counter-value-suffix' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_suffix!' => ''
				],
			]
		);

		$this->add_responsive_control(
			'counter_suffix_size',
			[
				'label' => esc_html__( 'Font Size', 'crt-manage' ),
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-prbar-counter-value-suffix' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_suffix!' => ''
				],
			]
		);

		$this->add_responsive_control(
			'counter_suffix_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-prbar-counter-value-suffix' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_suffix!' => ''
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	protected function render_progress_bar_circle( $persent ) {
		// Get Settings
		$settings = $this->get_settings();

		$circle_stocke_bg = $settings['circle_prline_bg_color'];
		$circle_size = $settings['circle_size']['size'];
		$circle_half_size = ( $circle_size / 2 );
		$circle_viewbox = sprintf( '0 0 %1$s %1$s', $circle_size );
		$circle_line_width = $settings['line_width']['size'];
		$circle_prline_width = $settings['prline_width']['size'];
		$circle_radius = $circle_half_size - ( $circle_prline_width / 2 );

		if ( $circle_line_width > $circle_prline_width ) {
			$circle_radius = $circle_half_size - ( $circle_line_width / 2 );
		}

		if ( $circle_prline_width > $circle_half_size ) {
			$circle_radius = $circle_half_size / 2;
			$circle_prline_width = $circle_half_size;
		}

		if ( $circle_line_width > $circle_half_size ) {
			$circle_radius = $circle_half_size / 2;
			$circle_line_width = $circle_half_size;
		}


		$circle_perimeter = 2 * M_PI * $circle_radius;
		$circle_offset = $circle_perimeter - ( ( $circle_perimeter / 100 ) * $persent );

		$circle_options = [
			'circleOffset' => $circle_offset,
			'circleSize' => $circle_size,
			'circleViewbox' => $circle_viewbox,
			'circleRadius' => $circle_radius,
			'circleLineWidth' => $circle_line_width,
			'circlePrlineWidth' => $circle_prline_width,
			'circleOffset' => $circle_offset,
			'circleDasharray' => $circle_perimeter,
		];

		$this->add_render_attribute( 'crt-prbar-circle', [
			'class' => 'crt-prbar-circle',
			'data-circle-options' => wp_json_encode( $circle_options ),
		] );

		?>

		<div <?php echo $this->get_render_attribute_string( 'crt-prbar-circle' ); ?>>

			<svg class="crt-prbar-circle-svg" viewBox="<?php echo esc_attr( $circle_viewbox ); ?>" >
				
				<?php if ( 'gradient' === $settings['circle_prline_bg_type'] ) : ?>

					<?php $circle_stocke_bg = 'url( #crt-prbar-circle-gradient-'. esc_attr($this->get_id()) .' )'; ?>
						
					<linearGradient id="crt-prbar-circle-gradient-<?php echo esc_attr($this->get_id()); ?>" gradientTransform="rotate(<?php echo esc_html($settings['circle_prline_grad_angle']['size']); ?> 0.5 0.5)" gradientUnits="objectBoundingBox"  x1="-0.5" y1="0.5" x2="1.5" y2="0.5">
						<stop offset="0%" stop-color="<?php echo esc_attr( $settings['circle_prline_bg_color_a'] ); ?>"></stop>
						<stop offset="100%" stop-color="<?php echo esc_attr( $settings['circle_prline_bg_color_b'] ); ?>"></stop>
					</linearGradient>

				<?php endif; ?>
				
				<circle class="crt-prbar-circle-line"
					cx="<?php echo esc_attr( $circle_half_size ); ?>"
					cy="<?php echo esc_attr( $circle_half_size ); ?>"
					r="<?php echo esc_attr( $circle_radius ); ?>"
					stroke-width="<?php echo esc_attr( $circle_line_width ); ?>"
				/>

				<circle class="crt-prbar-circle-prline crt-anim-timing-<?php echo esc_attr( $settings['anim_timing'] ); ?>"
					cx="<?php echo esc_attr( $circle_half_size ); ?>"
					cy="<?php echo esc_attr( $circle_half_size ); ?>"
					r="<?php echo esc_attr( $circle_radius ); ?>"
					stroke="<?php echo esc_attr( $circle_stocke_bg ); ?>"
					fill="none"
					stroke-width="<?php echo esc_attr( $circle_prline_width ); ?>"
					style="stroke-dasharray: <?php echo esc_attr($circle_perimeter); ?>; stroke-dashoffset: <?php echo esc_attr($circle_perimeter); ?>;"
				/>

			</svg>

			<?php $this->render_progress_bar_content( 'inside' ); ?>
		</div>

		<?php

		$this->render_progress_bar_content( 'outside' );

	}

	protected function render_progress_bar_content( $position ) {
		
		$settings = $this->get_settings();
		$is_counter = ( 'yes' === $settings['counter_switcher'] && $position === $settings['counter_position'] );
		$is_title = ( '' !== $settings['title'] && $position === $settings['title_position'] );
		$is_subtitle = ( '' !== $settings['subtitle'] && $position === $settings['title_position'] );
		$do_follow = 'yes' === $this->get_settings_for_display('counter_follow_line') && 'inside' === $settings['counter_position'] ? true : false;

		if ( $is_title || $is_subtitle || $is_counter ) {
			
			echo '<div class="crt-prbar-content elementor-clearfix">';

				if ( $is_title || $is_subtitle ) {
					echo '<div class="crt-prbar-title-wrap">';
						if ( $is_title ) {
							echo '<div class="crt-prbar-title">'. esc_html( $settings['title'] )  .'</div>';
						}

						if ( $is_title ) {
							echo '<div class="crt-prbar-subtitle">'. esc_html( $settings['subtitle'] )  .'</div>';
						}
					echo '</div>';
				}
				
				if ( $is_counter && ! $do_follow ) {
					$this->render_progress_bar_counter();
				}
			
			echo '</div>';
		}
	}

	protected function render_progress_bar_counter() {
		// Get Settings
		$settings = $this->get_settings();

		?>

		<div class="crt-prbar-counter">

			<?php if ( '' !== $settings['counter_prefix'] ) : ?>
			<span class="crt-prbar-counter-value-prefix"><?php echo esc_html( $settings['counter_prefix'] ); ?></span>
			<?php endif; ?>

			<?php if ( '' !== $settings['counter_value'] ) : ?>
			<span class="crt-prbar-counter-value">0</span>
			<?php endif; ?>

			<?php if ( '' !== $settings['counter_suffix'] ) : ?>
			<span class="crt-prbar-counter-value-suffix"><?php echo esc_html( $settings['counter_suffix'] ); ?></span>
			<?php endif; ?>

		</div>

		<?php
	}

	protected function render_progress_bar_hr_line() {
		// Get Settings
		$settings = $this->get_settings();

		$this->render_progress_bar_content('outside');

		?>

		<div class="crt-prbar-hr-line">
			<div class="crt-prbar-hr-line-inner crt-anim-timing-<?php echo esc_attr( $settings['anim_timing'] ); ?>">
				<?php
					if ( 'yes' === $this->get_settings_for_display('counter_follow_line') && 'inside' === $settings['counter_position'] ) {
						$this->render_progress_bar_counter();
					}
				?>
			</div>
			<?php $this->render_progress_bar_content('inside'); ?>
		</div>

		<?php
	}

	// Vertical Layout
    public function render_progress_bar_vr_line() {
        // Get Settings
        $settings = $this->get_settings();

        if ( 'yes' === $settings['counter_switcher']  && 'outside' === $settings['counter_position']  ) {
            $this->render_progress_bar_counter();
        }

        ?>

        <div class="crt-prbar-vr-line">
            <?php
            if ( 'yes' === $settings['counter_switcher']  && 'inside' === $settings['counter_position']  ) {
                $this->render_progress_bar_counter();
            }
            ?>
            <div class="crt-prbar-vr-line-inner crt-anim-timing-<?php echo esc_attr( $settings['anim_timing'] ); ?>"></div>
        </div>

        <?php

        if ( '' !== $settings['title'] ){
            echo '<div class="crt-prbar-title">'. esc_html( $settings['title'] ) .'</div>';
        }

        if ( '' !== $settings['subtitle'] ){
            echo '<div class="crt-prbar-subtitle">'. esc_html( $settings['subtitle'] ) .'</div>';
        }

    }

    protected function render() {
		// Get Settings
		$settings = $this->get_settings_for_display();

		$prbar_counter_persent = round( ( $settings['counter_value'] / $settings['max_value'] ) * 100 );

		$progress_bar_options = [
			'counterValue' => $settings['counter_value'],
			'counterValuePersent' => $prbar_counter_persent,
			'counterSeparator' => $settings['counter_separator'],
			'animDuration' => ( $settings['anim_duration'] * 1000 ),
			'animDelay' => ( $settings['anim_delay'] * 1000 ),
			'loop' => isset($settings['anim_loop']) ? $settings['anim_loop'] : '',
			'loopDelay' => isset($settings['anim_loop_delay']) ? $settings['anim_loop_delay'] : '',
		];

		$this->add_render_attribute( 'crt-progress-bar', [
			'class' => 'crt-progress-bar',
			'data-options' => wp_json_encode( $progress_bar_options ),
		] );

		?>
			
		<div <?php echo $this->get_render_attribute_string( 'crt-progress-bar' ); ?>>
		
			<?php

			if ( 'circle' === $settings['layout'] ) {
				$this->render_progress_bar_circle( $prbar_counter_persent );
			} elseif ( 'hr-line' === $settings['layout'] ) {
				$this->render_progress_bar_hr_line();
			} elseif ( 'vr-line' === $settings['layout'] ) {
				$this->render_progress_bar_vr_line();
			}

			?>

		</div>

		<?php
	}
}