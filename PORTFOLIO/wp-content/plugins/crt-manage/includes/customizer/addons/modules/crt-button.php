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

class CRT_Button extends Widget_Base {
		
	public function get_name() {
		return 'crt-button';
	}

	public function get_title() {
		return esc_html__( 'Button', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-button';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'button' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}
	
	public function get_style_depends() {
		return [ 'crt-button-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com/contact';
    }

	public function add_control_icon_style() {
        $this->add_control(
            'icon_style',
            [
                'label' => esc_html__( 'Select Style', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline' => esc_html__( 'Inline', 'crt-manage' ),
                    'block' => esc_html__( 'Block', 'crt-manage' ),
                    'inline-block' => esc_html__( 'Inline Block', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-button-icon-style-',
                'separator' => 'before',
            ]
        );
	}

	public function add_control_icon_width() {
        $this->add_control(
            'icon_width',
            [
                'label' => esc_html__( 'Icon Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 38,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-button-icon' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'icon_style!' => 'inline',
                ],
            ]
        );
    }

    public function add_section_style_icon() {
        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Icon', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'icon_style!' => 'inline',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_icon_colors' );

        $this->start_controls_tab(
            'tab_icon_normal_colors',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-button-icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-button-icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#4A45D2',
                'selectors' => [
                    '{{WRAPPER}} .crt-button-icon' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_border_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-button-icon' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow',
                'selector' => '{{WRAPPER}} .crt-button-icon',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover_colors',
            [
                'label' => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-button:hover .crt-button-icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-button:hover .crt-button-icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e55b5b',
                'selectors' => [
                    '{{WRAPPER}} .crt-button:hover .crt-button-icon' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-button:hover .crt-button-icon' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_hover_box_shadow',
                'selector' => '{{WRAPPER}} .crt-button:hover .crt-button-icon',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

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
                    '{{WRAPPER}} .crt-button-icon' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'icon_border_width',
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
                    '{{WRAPPER}} .crt-button-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'icon_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .crt-button-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );


        $this->end_controls_section(); // End Controls Section
    }

    public function add_section_tooltip() {
        $this->start_controls_section(
            'section_tooltip',
            [
                'label' => esc_html__( 'Tooltip', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'tooltip',
            [
                'label' => esc_html__( 'Show Tooltip', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'tooltip_position',
            [
                'label' => esc_html__( 'Position', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'bottom',
                'options' => [
                    'top' => esc_html__( 'Top', 'crt-manage' ),
                    'right' => esc_html__( 'Right', 'crt-manage' ),
                    'bottom' => esc_html__( 'Bottom', 'crt-manage' ),
                    'left' => esc_html__( 'Left', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-button-tooltip-position-',
                'condition' => [
                    'tooltip' => 'yes',
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
                        'max' => 500,
                    ],
                ],
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 210,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-button-tooltip' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tooltip' => 'yes',
                ],
                'separator' => 'before',
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
                    '{{WRAPPER}} .crt-button-tooltip' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
                ],
                'condition' => [
                    'tooltip' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tooltip_text',
            [
                'label' => '',
                'type' => Controls_Manager::WYSIWYG,
                'default' => 'Lorem Ipsum is simply dumy text of the printing typesetting industry lorem ipsum.',
                'condition' => [
                    'tooltip' => 'yes',
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
                'condition' => [
                    'tooltip' => 'yes',
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
                    '{{WRAPPER}} .crt-button-tooltip' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .crt-button-tooltip' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .crt-button-tooltip:before' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tooltip_box_shadow',
                'selector' => '{{WRAPPER}} .crt-button-tooltip',
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
                'selector' => '{{WRAPPER}} .crt-button-tooltip',
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
                    '{{WRAPPER}}.crt-button-tooltip-position-top .crt-button-tooltip' => 'top: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.crt-button-tooltip-position-bottom .crt-button-tooltip' => 'bottom: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.crt-button-tooltip-position-left .crt-button-tooltip' => 'left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.crt-button-tooltip-position-right .crt-button-tooltip' => 'right: -{{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .crt-button-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .crt-button-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

    public function render_pro_element_tooltip( $settings ) {
        if ( $settings['tooltip'] === 'yes' && ! empty( $settings['tooltip_text'] ) ) {
            echo '<div class="crt-button-tooltip">'. $settings['tooltip_text'] .'</div>';
        }
    }
	
	protected function register_controls() {

		// Section: Button ----------
		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__( 'Button', 'crt-manage' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Click here',
			]
		);

		$this->add_control(
			'button_url',
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
			'button_hover_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => 'crt-button-animations',
				'default' => 'crt-button-none',
			]
		);


		$this->add_control(
			'button_hover_anim_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-button' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button::before' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button .crt-button-icon' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button .crt-button-icon svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button .crt-button-text' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-button .crt-button-content' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
				],
			]
		);

		$this->add_control(
			'button_hover_animation_height',
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
					'button_hover_animation' => ['crt-button-underline-from-left','crt-button-underline-from-center','crt-button-underline-from-right','crt-button-underline-reveal','crt-button-overline-reveal','crt-button-overline-from-left','crt-button-overline-from-center','crt-button-overline-from-right']
				],
			]
		);

		$this->add_control(
			'button_hover_animation_text',
			[
				'label' => esc_html__( 'Effect Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Go',
				'condition' => [
					'button_hover_animation' => ['crt-button-winona','crt-button-rayen-left','crt-button-rayen-right']
				],
			]
		);

		$this->add_responsive_control(
			'button_width',
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
					'size' => 160,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-button-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);


		$this->add_responsive_control(
			'button_position',
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
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_content_align',
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
					'{{WRAPPER}} .crt-button-content' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
					'{{WRAPPER}} .crt-button-text' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_id',
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

		// Section: Icon -------------
		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
			]
		);

		$this->add_control(
			'select_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_icon_style();

		$this->add_control(
			'icon_position',
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
				'prefix_class' => 'crt-button-icon-position-',
				'separator' => 'before',
			]
		);

		$this->add_control_icon_width();

		$this->add_control(
			'icon_size',
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
					'{{WRAPPER}} .crt-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-button-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-button-icon-position-left .crt-button-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-button-icon-position-right .crt-button-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_style' => ['inline', 'block', 'inline-block'],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Tooltip ---------
		$this->add_section_tooltip();


		// Styles
		// Section: Button -----------
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'crt-manage' ),
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
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#e55b5b',
					],
				],
				'selector' => '{{WRAPPER}} .crt-button'
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-button-text' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-button-icon-style-inline .crt-button-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-button-icon-style-inline .crt-button-icon svg' => 'fill: {{VALUE}}',
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
					'{{WRAPPER}} .crt-button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .crt-button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .crt-button-text,{{WRAPPER}} .crt-button::after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#4A45D2',
					],
				],
				'selector' => '	{{WRAPPER}} [class*="elementor-animation"]:hover,
								{{WRAPPER}} .crt-button::before,
								{{WRAPPER}} .crt-button::after',
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-button:hover .crt-button-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-button::after' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-button-icon-style-inline .crt-button:hover .crt-button-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-button-icon-style-inline .crt-button:hover .crt-button-icon svg' => 'fill: {{VALUE}}',
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
					'{{WRAPPER}} .crt-button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .crt-button:hover',
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
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-button-icon-style-inline .crt-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-button-icon-style-block .crt-button-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-button-icon-style-inline-block .crt-button-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-button::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-button' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .crt-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Icon ---------------
		$this->add_section_style_icon();

		// Styles
		// Section: Tooltip ---------------
		$this->add_section_style_tooltip();
	
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		$settings_new = $this->get_settings_for_display();
		
		$settings = array_merge( $settings, $settings_new );

		$btn_element = 'div';
		$btn_url =  $settings['button_url']['url'];

	?>
	
	<?php if ( '' !== $settings['button_text'] || '' !== $settings['select_icon']['value'] ) : ?>
		
		<?php 	
		
		$this->add_render_attribute( 'button_attribute', 'class', 'crt-button crt-button-effect '. $settings['button_hover_animation'] );
			
		if ( '' !== $settings['button_hover_animation_text'] ) {
			$this->add_render_attribute( 'button_attribute', 'data-text', $settings['button_hover_animation_text'] );
		}	

		if ( '' !== $btn_url ) {

			$btn_element = 'a';

			$this->add_render_attribute( 'button_attribute', 'href', esc_url( $settings['button_url']['url'] ) );

			if ( $settings['button_url']['is_external'] ) {
				$this->add_render_attribute( 'button_attribute', 'target', '_blank' );
			}

			if ( $settings['button_url']['nofollow'] ) {
				$this->add_render_attribute( 'button_attribute', 'nofollow', '' );
			}
		}

		if ( '' !== $settings['button_id'] ) {
			$this->add_render_attribute( 'button_attribute', 'id', $settings['button_id']  );
		}

		?>

		<div class="crt-button-wrap elementor-clearfix">
		<<?php echo esc_html($btn_element); ?> <?php echo $this->get_render_attribute_string( 'button_attribute' ); ?>>
			
			<span class="crt-button-content">
				<?php if ( '' !== $settings['button_text'] ) : ?>
					<span class="crt-button-text"><?php echo esc_html__( $settings['button_text'] ); ?></span>
				<?php endif; ?>
				
				<?php if ( '' !== $settings['select_icon']['value'] ) : ?>
					<span class="crt-button-icon"><?php \Elementor\Icons_Manager::render_icon( $settings['select_icon'] ); ?></span>
				<?php endif; ?>
			</span>
		</<?php echo esc_html($btn_element); ?>>

		<?php $this->render_pro_element_tooltip( $settings ); ?>
		</div>
	
	<?php endif; ?>

	<?php

	}
}