<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Dual_Button extends Widget_Base {
		
	public function get_name() {
		return 'crt-dual-button';
	}

	public function get_title() {
		return esc_html__( 'Dual Button', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-dual-button';
	}

	public function get_categories() {
		return [ 'crt-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'dual button', 'double button' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}
	
	public function get_style_depends() {
		return [ 'crt-button-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-dual-button-help-btn';
    		return 'https://crthemes.com/contact';
    }

    public function add_control_middle_badge() {
        $this->add_control(
            'middle_badge',
            [
                'label' => esc_html__( 'Middle Badge', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes',
            ]
        );
    }

    public function add_control_middle_badge_type() {
        $this->add_control(
            'middle_badge_type',
            [
                'label' => esc_html__( 'Select Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'text' => esc_html__( 'Text', 'crt-manage' ),
                    'icon' => esc_html__( 'Icon', 'crt-manage' ),
                ],
                'condition' => [
                    'middle_badge' => 'yes'
                ],
            ]
        );
    }

    public function add_control_middle_badge_text() {
        $this->add_control(
            'middle_badge_text',
            [
                'label' => esc_html__( 'Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Or',
                'condition' => [
                    'middle_badge' => 'yes',
                    'middle_badge_type' => 'text',
                ],
            ]
        );
    }

    public function add_control_middle_badge_icon() {
        $this->add_control(
            'middle_badge_icon',
            [
                'label' => esc_html__( 'Select Icon', 'crt-manage' ),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-paper-plane',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'middle_badge' => 'yes',
                    'middle_badge_type' => 'icon',
                ],
            ]
        );
    }

    public function add_section_style_middle_badge() {
        $this->start_controls_section(
            'section_style_middle_badge',
            [
                'label' => esc_html__( 'Middle Badge', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'middle_badge' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'middle_badge_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Text Color', 'crt-manage' ),
                'default' => '#605BE5',
                'selectors' => [
                    '{{WRAPPER}} .crt-button-middle-badge' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .crt-button-middle-badge svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'middle_badge_bg_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-button-middle-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'middle_badge_box_shadow',
                'selector' => '{{WRAPPER}} .crt-button-middle-badge',
            ]
        );

        $this->add_responsive_control(
            'middle_badge_size',
            [
                'label' => esc_html__( 'Box Size', 'crt-manage' ),
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
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-button-middle-badge' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'middle_badge_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'middle_badge_typography',
                'selector' => '{{WRAPPER}} .crt-button-middle-badge',
                'condition' => [
                    'middle_badge_type' => 'text'
                ],
            ]
        );

        $this->add_responsive_control(
            'middle_badge_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-button-middle-badge i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-button-middle-badge svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'middle_badge_type' => 'icon',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'middle_badge_border',
                'label' => esc_html__( 'Border', 'crt-manage' ),
                'fields_options' => [
                    'color' => [
                        'default' => '#E8E8E8',
                    ],
                ],
                'selector' => '{{WRAPPER}} .crt-button-middle-badge',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'middle_badge_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-button-middle-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

    public function add_section_tooltip_a() {
        $this->start_controls_section(
            'section_tooltip_a',
            [
                'label' => esc_html__( 'First Button Tooltip', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'tooltip_a',
            [
                'label' => esc_html__( 'Show Tooltip', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'tooltip_a_position',
            [
                'label' => esc_html__( 'Position', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'top' => esc_html__( 'Top', 'crt-manage' ),
                    'bottom' => esc_html__( 'Bottom', 'crt-manage' ),
                    'left' => esc_html__( 'Left', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-button-tooltip-a-position-',
                'condition' => [
                    'tooltip_a' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tooltip_a_text',
            [
                'label' => '',
                'type' => Controls_Manager::WYSIWYG,
                'default' => 'Lorem Ipsum is simply dumy text of the printing typesetting industry lorem ipsum.',
                'condition' => [
                    'tooltip_a' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

    public function add_section_tooltip_b() {
        $this->start_controls_section(
            'section_tooltip_b',
            [
                'label' => esc_html__( 'Second Button Tooltip', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'tooltip_b',
            [
                'label' => esc_html__( 'Show Tooltip', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'tooltip_b_position',
            [
                'label' => esc_html__( 'Position', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'top' => esc_html__( 'Top', 'crt-manage' ),
                    'right' => esc_html__( 'Right', 'crt-manage' ),
                    'bottom' => esc_html__( 'Bottom', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-button-tooltip-b-position-',
                'condition' => [
                    'tooltip_b' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tooltip_b_text',
            [
                'label' => '',
                'type' => Controls_Manager::WYSIWYG,
                'default' => 'Lorem Ipsum is simply dumy text of the printing typesetting industry lorem ipsum.',
                'condition' => [
                    'tooltip_b' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

    public function add_section_style_tooltip() {
        $this->start_controls_section(
            'section_style_tooltip',
            [
                'label' => esc_html__( 'Tooltip', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'tooltip_a',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'tooltip_b',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'tooltip_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Text Color', 'crt-manage' ),
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-button-tooltip-a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .crt-button-tooltip-b' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_bg_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'default' => '#3f3f3f',
                'selectors' => [
                    '{{WRAPPER}} .crt-button-tooltip-a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .crt-button-tooltip-a:before' => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}} .crt-button-tooltip-b' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .crt-button-tooltip-b:before' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tooltip_box_shadow',
                'selector' => '{{WRAPPER}} .crt-button-tooltip-a,{{WRAPPER}} .crt-button-tooltip-b',
            ]
        );

        $this->add_control(
            'tooltip_duration',
            [
                'label' => esc_html__( 'Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-button-tooltip-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
                    '{{WRAPPER}} .crt-button-tooltip-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tooltip_width',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 800,
                    ],
                ],
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 210,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-button-tooltip-a' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-button-tooltip-b' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tooltip_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tooltip_typography',
                'label' => esc_html__( 'Typography', 'crt-manage' ),
                'selector' => '{{WRAPPER}} .crt-button-tooltip-a,{{WRAPPER}} .crt-button-tooltip-b',
            ]
        );

        $this->add_responsive_control(
            'tooltip_distance',
            [
                'label' => esc_html__( 'Distance', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}}.crt-button-tooltip-a-position-top .crt-button-tooltip-a' => 'top: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.crt-button-tooltip-a-position-bottom .crt-button-tooltip-a' => 'bottom: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.crt-button-tooltip-a-position-left .crt-button-tooltip-a' => 'left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.crt-button-tooltip-a-position-right .crt-button-tooltip-a' => 'right: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.crt-button-tooltip-b-position-top .crt-button-tooltip-b' => 'top: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.crt-button-tooltip-b-position-bottom .crt-button-tooltip-b' => 'bottom: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.crt-button-tooltip-b-position-left .crt-button-tooltip-b' => 'left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.crt-button-tooltip-b-position-right .crt-button-tooltip-b' => 'right: -{{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tooltip_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', ],
                'default' => [
                    'top' => 6,
                    'right' => 10,
                    'bottom' => 6,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-button-tooltip-a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-button-tooltip-b' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tooltip_border_radius',
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
                    '{{WRAPPER}} .crt-button-tooltip-a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-button-tooltip-b' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

	protected function register_controls() {

		// Section: General ---------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
			]
		);

		$this->add_responsive_control(
			'general_position',
			[
				'label' => esc_html__( 'Position', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
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
					],
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-dual-button' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
			]
		);


		$this->add_control_middle_badge();

		$this->add_control_middle_badge_type();

		$this->add_control_middle_badge_text();

		$this->add_control_middle_badge_icon();

		$this->end_controls_section(); // End Controls Section

		// Section: Button #1 ---------
		$this->start_controls_section(
			'section_button_a',
			[
				'label' => esc_html__( 'First Button', 'crt-manage' ),
			]
		);

		$this->add_control(
			'button_a_text',
			[
				'label' => esc_html__( 'Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Button 1',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'button_a_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'crt-manage' ),
				'default' => [
					'url' => '#link',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'button_a_hover_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => 'crt-button-animations',
				'default' => 'crt-button-none',
			]
		);

		$this->add_control(
			'button_a_hover_anim_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-button-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button-a::before' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button-a::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button-a .crt-button-icon-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button-a .crt-button-icon-a svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button-a .crt-button-text-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button-a .crt-button-content-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
				],
			]
		);

		$this->add_control(
			'button_a_hover_animation_height',
			[
				'label' => esc_html__( 'Effect Height', 'crt-manage' ),
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
				'condition' => [
					'button_a_hover_animation' => ['crt-button-underline-from-left','crt-button-underline-from-center','crt-button-underline-from-right','crt-button-underline-reveal','crt-button-overline-reveal','crt-button-overline-from-left','crt-button-overline-from-center','crt-button-overline-from-right']
				],
			]
		);

		$this->add_control(
			'button_a_hover_animation_text',
			[
				'label' => esc_html__( 'Effect Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Go 1',
				'condition' => [
					'button_a_hover_animation' => ['crt-button-winona','crt-button-rayen-left','crt-button-rayen-right']
				],
			]
		);

		$this->add_responsive_control(
			'button_a_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
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
					'size' => 140,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-button-a-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_a_content_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
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
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-button-content-a' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
					'{{WRAPPER}} .crt-button-text-a' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_a_id',
			[
				'label' => esc_html__( 'Button ID', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'crt-manage' ),
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this button is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'crt-manage' ),
				'label_block' => false,
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Icon #1 -----------
		$this->start_controls_section(
			'section_icon_a',
			[
				'label' => esc_html__( 'First Button Icon', 'crt-manage' ),
			]
		);

		$this->add_control(
			'select_icon_a',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_a_position',
			[
				'label' => esc_html__( 'Position', 'crt-manage' ),
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
					],
				],
				'prefix_class' => 'crt-button-icon-a-position-',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_a_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-button-icon-a' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-button-icon-a svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_a_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-button-icon-a-position-left .crt-button-icon-a' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-button-icon-a-position-right .crt-button-icon-a' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Tooltip #1 --------
		$this->add_section_tooltip_a();

		// Section: Button #2 ---------
		$this->start_controls_section(
			'section_button_b',
			[
				'label' => esc_html__( 'Second Button', 'crt-manage' ),
			]
		);

		$this->add_control(
			'button_b_text',
			[
				'label' => esc_html__( 'Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Button 2',
			]
		);

		$this->add_control(
			'button_b_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label' => esc_html__( 'Link', 'crt-manage' ),
				'placeholder' => esc_html__( 'https://your-link.com', 'crt-manage' ),
				'show_label' => false,
				'default' => [
					'url' => '#',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_b_hover_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => 'crt-button-animations',
				'default' => 'crt-button-none',
			]
		);

		$this->add_control(
			'button_b_hover_anim_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-button-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button-b::before' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button-b::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button-b .crt-button-icon-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button-b .crt-button-text-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button-b .crt-button-content-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
				],
			]
		);

		$this->add_control(
			'button_b_hover_animation_height',
			[
				'label' => esc_html__( 'Effect Height', 'crt-manage' ),
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
				'condition' => [
					'button_b_hover_animation' => ['crt-button-underline-from-left','crt-button-underline-from-center','crt-button-underline-from-right','crt-button-underline-reveal','crt-button-overline-reveal','crt-button-overline-from-left','crt-button-overline-from-center','crt-button-overline-from-right']
				],
			]
		);

		$this->add_control(
			'button_b_hover_animation_text',
			[
				'label' => esc_html__( 'Effect Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Go',
				'condition' => [
					'button_b_hover_animation' => ['crt-button-winona','crt-button-rayen-left','crt-button-rayen-right']
				],
			]
		);

		$this->add_responsive_control(
			'button_b_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
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
					'size' => 140,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-button-b-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_b_content_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
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
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-button-content-b' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
					'{{WRAPPER}} .crt-button-text-b' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_b_id',
			[
				'label' => esc_html__( 'Button ID', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'crt-manage' ),
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this button is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'crt-manage' ),
				'label_block' => false,
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Icon #2 -----------
		$this->start_controls_section(
			'section_icon_b',
			[
				'label' => esc_html__( 'Second Button Icon', 'crt-manage' ),
			]
		);

		$this->add_control(
			'select_icon_b',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_b_position',
			[
				'label' => esc_html__( 'Position', 'crt-manage' ),
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
					],
				],
				'prefix_class' => 'crt-button-icon-b-position-',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_b_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-button-icon-b' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-button-icon-b svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_b_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-button-icon-b-position-left .crt-button-icon-b' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-button-icon-b-position-right .crt-button-icon-b' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Tooltip #2 --------
		$this->add_section_tooltip_b();

		// Styles
		// Section: General ----------
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'general_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-button-a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-button-a::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-button-b' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .crt-button-b::after' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'general_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'general_typography',
				'selector' => '{{WRAPPER}} .crt-button-text-a,{{WRAPPER}} .crt-button-a::after,{{WRAPPER}} .crt-button-text-b,{{WRAPPER}} .crt-button-b::after',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Button #1----------
		$this->start_controls_section(
			'section_style_button_a',
			[
				'label' => esc_html__( 'First Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_a_colors' );

		$this->start_controls_tab(
			'tab_button_a_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_a_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605BE5',
					],
				],
				'selector' => '{{WRAPPER}} .crt-button-a'
			]
		);

		$this->add_control(
			'button_a_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-button-text-a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-button-icon-a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-button-icon-a svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_a_box_shadow',
				'selector' => '{{WRAPPER}} .crt-button-a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_a_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_a_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#4A45D2',
					],
				],
				'selector' => '	{{WRAPPER}} .crt-button-a[class*="elementor-animation"]:hover,
								{{WRAPPER}} .crt-button-a::before,
								{{WRAPPER}} .crt-button-a::after',
			]
		);

		$this->add_control(
			'button_a_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-button-a:hover .crt-button-text-a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-button-a::after' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-button-a:hover .crt-button-icon-a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-button-a:hover .crt-button-icon-a svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_a_hover_box_shadow',
				'selector' => '{{WRAPPER}} .crt-button-a:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_a_border',
				'label' => esc_html__( 'Border', 'crt-manage' ),
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => '0',
							'right' => '1',
							'bottom' => '0',
							'left' => '0',
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .crt-button-a',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_a_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 0,
					'bottom' => 0,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-button-a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Button #2----------
		$this->start_controls_section(
			'section_style_button_b',
			[
				'label' => esc_html__( 'Second Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_b_colors' );

		$this->start_controls_tab(
			'tab_button_b_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_b_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605BE5',
					],
				],
				'selector' => '{{WRAPPER}} .crt-button-b'
			]
		);

		$this->add_control(
			'button_b_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-button-text-b' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-button-icon-b' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-button-icon-b svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_b_box_shadow',
				'selector' => '{{WRAPPER}} .crt-button-b',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_b_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_b_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#4A45D2',
					],
				],
				'selector' => '	{{WRAPPER}} .crt-button-b[class*="elementor-animation"]:hover,
								{{WRAPPER}} .crt-button-b::before,
								{{WRAPPER}} .crt-button-b::after',
			]
		);

		$this->add_control(
			'button_b_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-button-b:hover .crt-button-text-b' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-button-b::after' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-button-b:hover .crt-button-icon-b' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-button-b:hover .crt-button-icon-b svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_b_hover_box_shadow',
				'selector' => '{{WRAPPER}} .crt-button-b:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_b_border',
				'label' => esc_html__( 'Border', 'crt-manage' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .crt-button-b',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_b_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 3,
					'bottom' => 3,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-button-b' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Middle Badge ---------------
		$this->add_section_style_middle_badge();

		// Styles
		// Section: Tooltip ---------
		$this->add_section_style_tooltip();
	
	}

    public function render_pro_element_tooltip_a() {
        $settings = $this->get_settings();

        if ( $settings['tooltip_a'] === 'yes' && ! empty( $settings['tooltip_a_text'] ) ) {
            echo '<div class="crt-button-tooltip-a">'. $settings['tooltip_a_text'] .'</div>';
        }
    }

    public function render_pro_element_tooltip_b() {
        $settings = $this->get_settings();

        if ( $settings['tooltip_b'] === 'yes' && ! empty( $settings['tooltip_b_text'] ) ) {
            echo '<div class="crt-button-tooltip-b">'. $settings['tooltip_b_text'] .'</div>';
        }
    }

    public function render_pro_element_middle_badge() {
        $settings = $this->get_settings();

        if ( 'yes' === $settings['middle_badge'] ) : ?>

            <span class="crt-button-middle-badge">
			<?php if ( 'text' === $settings['middle_badge_type'] ) : ?>
                <?php echo esc_html( $settings['middle_badge_text'] ); ?>
            <?php else: ?>
                <?php \Elementor\Icons_Manager::render_icon( $settings['middle_badge_icon'] ); ?>
            <?php endif; ?>
		</span>

        <?php endif;
    }

	protected function render() {

	$settings = $this->get_settings();
	$btn_a_element = 'div';
	$btn_b_element = 'div';
	$btn_a_url =  $settings['button_a_url']['url'];
	$btn_b_url =  $settings['button_b_url']['url'];
	
	?>
	
	<div class="crt-dual-button">
		<?php if ( '' !== $settings['button_a_text'] || '' !== $settings['select_icon_a']['value'] ) : ?>
		
		<?php 	
		
		$this->add_render_attribute( 'button_a_attribute', 'class', 'crt-button-a crt-button-effect '. $settings['button_a_hover_animation'] );
			
		if ( '' !== $settings['button_a_hover_animation_text'] ) {
			$this->add_render_attribute( 'button_a_attribute', 'data-text', $settings['button_a_hover_animation_text'] );
		}	

		if ( '' !== $btn_a_url ) {

			$btn_a_element = 'a';

			$this->add_render_attribute( 'button_a_attribute', 'href', esc_url( $settings['button_a_url']['url'] ));

			if ( $settings['button_a_url']['is_external'] ) {
				$this->add_render_attribute( 'button_a_attribute', 'target', '_blank' );
			}

			if ( $settings['button_a_url']['nofollow'] ) {
				$this->add_render_attribute( 'button_a_attribute', 'nofollow', '' );
			}
		}

		if ( '' !== $settings['button_a_id'] ) {
			$this->add_render_attribute( 'button_a_attribute', 'id', $settings['button_a_id']  );
		}

		?>

		<div class="crt-button-a-wrap elementor-clearfix">
		<<?php echo esc_html($btn_a_element); ?> <?php echo $this->get_render_attribute_string( 'button_a_attribute' ); ?>>
			
			<span class="crt-button-content-a">
				<?php if ( '' !== $settings['button_a_text'] ) : ?>
					<span class="crt-button-text-a"><?php echo esc_html( $settings['button_a_text'] ); ?></span>
				<?php endif; ?>
				
				<?php if ( '' !== $settings['select_icon_a']['value'] ) : ?>
					<span class="crt-button-icon-a"><?php \Elementor\Icons_Manager::render_icon( $settings['select_icon_a'] ); ?></span>
				<?php endif; ?>
			</span>
		</<?php echo esc_html($btn_a_element); ?>>

		<?php $this->render_pro_element_tooltip_a(); ?>

		<?php $this->render_pro_element_middle_badge(); ?>

		</div>

		<?php endif; ?>

		<?php if ( '' !== $settings['button_b_text'] || '' !== $settings['select_icon_b']['value'] ) : ?>
			
		<?php 	
		
		$this->add_render_attribute( 'button_b_attribute', 'class', 'crt-button-b crt-button-effect '. $settings['button_b_hover_animation'] );
			
		if ( '' !== $settings['button_b_hover_animation_text'] ) {
			$this->add_render_attribute( 'button_b_attribute', 'data-text', $settings['button_b_hover_animation_text'] );
		}	

		if ( '' !== $btn_b_url ) {

			$btn_b_element = 'a';

			$this->add_render_attribute( 'button_b_attribute', 'href', esc_url( $settings['button_b_url']['url'] ));

			if ( $settings['button_b_url']['is_external'] ) {
				$this->add_render_attribute( 'button_b_attribute', 'target', '_blank' );
			}

			if ( $settings['button_b_url']['nofollow'] ) {
				$this->add_render_attribute( 'button_b_attribute', 'nofollow', '' );
			}
		}

		if ( '' !== $settings['button_b_id'] ) {
			$this->add_render_attribute( 'button_b_attribute', 'id', $settings['button_b_id']  );
		}

		?>

		<div class="crt-button-b-wrap elementor-clearfix">
		<<?php echo esc_html($btn_b_element); ?> <?php echo $this->get_render_attribute_string( 'button_b_attribute' ); ?>>
			
			<span class="crt-button-content-b">
				<?php if ( '' !== $settings['button_b_text'] ) : ?>
					<span class="crt-button-text-b"><?php echo esc_html( $settings['button_b_text'] ); ?></span>
				<?php endif; ?>
				
				<?php if ( '' !== $settings['select_icon_b']['value'] ) : ?>
					<span class="crt-button-icon-b"><?php \Elementor\Icons_Manager::render_icon( $settings['select_icon_b'] ); ?></span>
				<?php endif; ?>
			</span>
		</<?php echo esc_html($btn_b_element); ?>>

		<?php $this->render_pro_element_tooltip_b(); ?>
		</div>
	
		<?php endif; ?>
	</div>
	<?php

	}
}