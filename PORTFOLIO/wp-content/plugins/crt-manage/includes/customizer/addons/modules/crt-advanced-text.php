<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Utils;
use Elementor\Icons;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Advanced_Text extends Widget_Base {
		
	public function get_name() {
		return 'crt-advanced-text';
	}

	public function get_title() {
		return esc_html__( 'Advanced Text', 'crt-addons' );
	}

	public function get_icon() {
		return 'crt-icon eicon-animated-headline';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'CRT', 'advanced text', 'text effects', 'typing text', 'fancy text', 'animated text', '3d text', 'text mask', 'text rotator', 'text animaiton' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_style_depends() {
		return [ 'crt-text-animations-css' ];
	}

    public function get_script_depends() {
        return [ 'crt-advanced-text' ];
    }

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) ) {
            return 'https://crthemes.com/contact';
        }
    }

	public function add_control_text_style() {
        $this->add_control(
            'text_style',
            [
                'label' => esc_html__( 'Style', 'crt-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'animated',
                'options' => [
                    'animated' => esc_html__( 'Animated', 'crt-addons' ),
                    'highlighted' => esc_html__( 'Highlighted', 'crt-addons' ),
                    'clipped' => esc_html__( 'Clipped', 'crt-addons' ),
                ],
                'prefix_class' => 'crt-advanced-text-style-',
                'render_type' => 'template',
            ]
        );
	}

	public function add_control_clipped_text() {
        $this->add_control(
            'clipped_text',
            [
                'label' => esc_html__( 'Clipped Text', 'crt-addons' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'Best Websites',
                'placeholder' => esc_html__( 'Enter your text', 'crt-addons' ),
                'condition' => [
                    'text_style' => 'clipped',
                ],
            ]
        );
    }

    public function add_section_style_clipped_text() {
        $this->start_controls_section(
            'section_style_clipped_text',
            [
                'label' => esc_html__( 'Clipped Text', 'crt-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'text_style' => 'clipped',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'clipped_text_bg_color',
                'label' => esc_html__( 'Background', 'crt-addons' ),
                'types' => [ 'classic', 'gradient' ],
                'fields_options' => [
                    'color' => [
                        'default' => '#e55b5b',
                    ],
                ],
                'selector' => '{{WRAPPER}} .crt-clipped-text-content'
            ]
        );

        $this->add_control(
            'clipped_text_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'clipped_text_typography',
                'selector' => '{{WRAPPER}} .crt-clipped-text',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'shadow_section',
            [
                'label' => esc_html__( 'Shadow', 'crt-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'clipped_text_shadow_type',
            [
                'label' => esc_html__( 'Type', 'crt-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'long',
                'options' => [
                    'default' => esc_html__( 'Default', 'crt-addons' ),
                    'long' => esc_html__( 'Long', 'crt-addons' ),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'clipped_text_shadow',
                'selector' => '{{WRAPPER}} .crt-clipped-text',
                'condition' => [
                    'clipped_text_shadow_type' => 'default',
                ],
            ]
        );

        $this->add_control(
            'clipped_text_long_shadow_color',
            [
                'label' => esc_html__( 'Color', 'crt-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e8e8e8',
                'selectors' => [
                    '{{WRAPPER}} .crt-clipped-text' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'clipped_text_shadow_type' => 'long',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'clipped_text_long_shadow_size',
            [
                'label' => esc_html__( 'Size', 'crt-addons' ),
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-clipped-text' => 'stroke-width: {{SIZE}}{{UNIT}}',
                ],
                'render_type' => 'template',
                'condition' => [
                    'clipped_text_shadow_type' => 'long',
                ],
            ]
        );

        $this->add_control(
            'clipped_text_long_shadow_direction',
            [
                'label' => esc_html__( 'Direction', 'crt-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'bottom-left',
                'options' => [
                    'top-left' => esc_html__( 'Top Left', 'crt-addons' ),
                    'top-right' => esc_html__( 'Top Right', 'crt-addons' ),
                    'bottom-left' => esc_html__( 'Bottom Left', 'crt-addons' ),
                    'bottom-right' => esc_html__( 'Bottom Right', 'crt-addons' ),
                    'top' => esc_html__( 'Top', 'crt-addons' ),
                    'bottom' => esc_html__( 'Bottom', 'crt-addons' ),
                    'left' => esc_html__( 'Left', 'crt-addons' ),
                    'right' => esc_html__( 'Right', 'crt-addons' ),
                ],
                'render_type' => 'template',
                'condition' => [
                    'clipped_text_shadow_type' => 'long',
                ],
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

    protected function register_controls() {

		// Section: Content ---------
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'crt-addons' ),
			]
		);

		$this->add_control_text_style();

		$this->add_control(
			'text_type',
			[
				'label' => esc_html__( 'Animation', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'typing',
				'options' => [
					'typing' => esc_html__( 'Typing', 'crt-addons' ),
					'rotate-1' => esc_html__( 'Skew', 'crt-addons' ),
					'rotate-2' => esc_html__( 'Flip VR', 'crt-addons' ),
					'rotate-3' => esc_html__( 'Flip HR', 'crt-addons' ),
					'slide' => esc_html__( 'Slide', 'crt-addons' ),
					'clip' => esc_html__( 'Clip', 'crt-addons' ),
					'zoom' => esc_html__( 'Zoom', 'crt-addons' ),
					'scale' => esc_html__( 'Scale', 'crt-addons' ),
					'push' => esc_html__( 'Push', 'crt-addons' ),
				],

				'prefix_class' => 'crt-fancy-text-',
				'render_type' => 'template',
				'condition' => [
					'text_style' => 'animated',
				],
			]
		);

		$this->add_control(
			'highlighted_shape',
			[
				'label' => esc_html__( 'Shape', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => [
					'circle' => esc_html__( 'Circle', 'crt-addons' ),
					'underline-zigzag' => esc_html__( 'Underline Zigzag', 'crt-addons' ),
					'curly' => esc_html__( 'Curly', 'crt-addons' ),
					'x' => esc_html__( 'Cross X', 'crt-addons' ),
					'strikethrough' => esc_html__( 'Linethrough', 'crt-addons' ),
					'underline' => esc_html__( 'Underline', 'crt-addons' ),
					'double' => esc_html__( 'Double', 'crt-addons' ),
					'double-underline' => esc_html__( 'Double Underline', 'crt-addons' ),
					'diagonal' => esc_html__( 'Diagonal', 'crt-addons' ),
				],
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_control(
			'highlighted_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .crt-highlighted-text svg path' => '-webkit-animation-duration: {{VALUE}}s; animation-duration: {{VALUE}}s;',
				],
				'render_type' => 'template',
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_control(
			'animated_duration_a',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 50,
				'step' => 0.05,
				'selectors' => [
				],
				'render_type' => 'template',
				'condition' => [
					'text_style' => 'animated',
					'text_type' => [ 'typing', 'rotate-2', 'rotate-3', 'scale' ]
				],
			]
		);

		$this->add_control(
			'animated_duration_b',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 50,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-anim-text.crt-anim-text-type-rotate-1 b' => '-webkit-animation-duration: {{VALUE}}s; animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-anim-text.crt-anim-text-type-slide b' => '-webkit-animation-duration: {{VALUE}}s; animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-anim-text.crt-anim-text-type-zoom b' => '-webkit-animation-duration: {{VALUE}}s; animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-anim-text.crt-anim-text-type-push b' => '-webkit-animation-duration: {{VALUE}}s; animation-duration: {{VALUE}}s;',
				],
				'render_type' => 'template',
				'condition' => [
					'text_style' => 'animated',
					'text_type' => [ 'rotate-1', 'zoom', 'clip', 'slide', 'push' ]
				],
			]
		);

		$this->add_control(
			'anim_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 2,
				'min' => 0,
				'max' => 15,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-highlighted-text svg path' => '-webkit-animation-delay: {{VALUE}}s; animation-delay: {{VALUE}}s;',
					'{{WRAPPER}} .crt-highlighted-text svg.crt-highlight-x path:first-child' => '-webkit-animation-delay: -webkit-calc({{VALUE}}s + 0.3s); animation-delay: calc({{VALUE}}s + 0.3s);',
					'{{WRAPPER}} .crt-highlighted-text svg.crt-highlight-double path:last-child' => '-webkit-animation-delay: -webkit-calc({{VALUE}}s + 0.3s); animation-delay: calc({{VALUE}}s + 0.3s);',
					'{{WRAPPER}} .crt-highlighted-text svg.crt-highlight-double-underline path:last-child' => '-webkit-animation-delay: -webkit-calc({{VALUE}}s + 0.3s); animation-delay: calc({{VALUE}}s + 0.3s);',
				],
				'render_type' => 'template',
				'condition' => [
					'text_style!' => 'clipped',
				],
			]
		); 

		$this->add_control(
			'anim_loop',
			[
				'label' => esc_html__( 'Loop', 'crt-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .crt-highlighted-text svg path' => '-webkit-animation-iteration-count: infinite; animation-iteration-count: infinite;',
				],
				'prefix_class' => 'crt-animated-text-infinite-',
				'render_type' => 'template',
				'condition' => [
					'text_style!' => 'clipped',
				],
			]
		);

		$this->add_control(
			'anim_cursor',
			[
				'label' => esc_html__( 'Cursor', 'crt-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
				'condition' => [
					'text_style' => 'animated',
					'text_type' => ['typing','clip'],
				],
			]
		);

		$this->add_control(
			'anim_cursor_content',
			[
				'label' => esc_html__( 'Text', 'crt-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => '|',
				'condition' => [
					'anim_cursor' => 'yes',
					'text_style' => 'animated',
					'text_type' => ['typing','clip'],
				],
			]
		);

		$this->add_control(
			'anim_cursor_duration',
			[
				'label' => esc_html__( 'Duration', 'crt-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 15,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-anim-text-cursor' => '-webkit-animation-duration: {{VALUE}}s; animation-duration: {{VALUE}}s;',
				],
				'condition' => [
					'anim_cursor' => 'yes',
					'text_style' => 'animated',
					'text_type' => ['typing','clip'],
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'prefix_text',
			[
				'label' => esc_html__( 'Prefix Text', 'crt-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => esc_html__( 'Hello', 'crt-addons' ),
				'placeholder' => esc_html__( 'Enter your text', 'crt-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'animated_text',
			[
				'label' => esc_html__( 'Animated Text', 'crt-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter each word in a separate line', 'crt-addons' ),
				'default' => "Best Websites\nAmazing Plugins",
				'rows' => 5,
				'condition' => [
					'text_style' => 'animated',
				],
			]
		);

		$this->add_control_clipped_text();

		$this->add_control(
			'highlighted_text',
			[
				'label' => esc_html__( 'Highlight Text', 'crt-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => esc_html__( 'Best Websites', 'crt-addons' ),
				'placeholder' => esc_html__( 'Enter your text', 'crt-addons' ),
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_control(
			'suffix_text',
			[
				'label' => esc_html__( 'Suffix Text', 'crt-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( '', 'crt-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'text_link',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label' => esc_html__( 'Link', 'crt-addons' ),
				'placeholder' => esc_html__( 'https://your-link.com', 'crt-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-text' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-advanced-text a' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'text_tag',
			[
				'label' => esc_html__( 'Text HTML Tag', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'crt-addons' ),
					'h2' => esc_html__( 'H2', 'crt-addons' ),
					'h3' => esc_html__( 'H3', 'crt-addons' ),
					'h4' => esc_html__( 'H4', 'crt-addons' ),
					'h5' => esc_html__( 'H5', 'crt-addons' ),
					'h6' => esc_html__( 'H6', 'crt-addons' ),
					'div' => esc_html__( 'div', 'crt-addons' ),
					'span' => esc_html__( 'span', 'crt-addons' ),
					'p' => esc_html__( 'p', 'crt-addons' ),
				],
				'default' => 'h3',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// // Widget Extra Buttons ---------
		// $this->start_controls_section(
		// 	'section_widget_extra_buttons',
		// 	[
		// 		'label' => '<a href="#">Widget Preview</a> <a href="#">Predefined Styles</a>',
		// 	]
		// );

		// $this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Prefix ----------
		$this->start_controls_section(
			'section_style_prefix',
			[
				'label' => esc_html__( 'Prefix Text', 'crt-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'prefix_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-text-preffix' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'prefix_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'prefix_typography',
				'selector' => '{{WRAPPER}} .crt-advanced-text-preffix',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section


		// Styles
		// Section: Text -----------
		$this->start_controls_section(
			'section_style_text',
			[
				'label' => esc_html__( 'Advanced Text', 'crt-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'text_style' => [ 'animated', 'highlighted' ],
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-anim-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-highlighted-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-anim-text' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-highlighted-text' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_selected_color',
			[
				'label' => esc_html__( 'Typing Text Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-anim-text-selected ' => 'color: {{VALUE}}',
				],
				'condition' => [
					'text_style' => 'animated',
					'text_type' => 'typing',
				],
			]
		);

		$this->add_control(
			'text_selected_bg_color',
			[
				'label' => esc_html__( 'Typing Background Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-anim-text-selected ' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'text_style' => 'animated',
					'text_type' => 'typing',
				],
			]
		);

		$this->add_control(
			'text_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .crt-anim-text b, {{WRAPPER}} .crt-anim-text b i,{{WRAPPER}} .crt-anim-text,{{WRAPPER}} .crt-highlighted-text',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-anim-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-highlighted-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-advanced-text-preffix' => 'padding-top: {{TOP}}{{UNIT}};padding-bottom: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} .crt-advanced-text-suffuix' => 'padding-top: {{TOP}}{{UNIT}};padding-bottom: {{BOTTOM}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'marker_section',
			[
				'label' => esc_html__( 'Marker', 'crt-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_control(
			'marker_color',
			[
				'label' => esc_html__( 'Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-highlighted-text path' => 'stroke: {{VALUE}};',
				],
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_responsive_control(
			'marker_width',
			[
				'label' => esc_html__( 'Width', 'crt-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 120,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-highlighted-text svg' => 'width: {{SIZE}}%;',
				],	
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_responsive_control(
			'marker_height',
			[
				'label' => esc_html__( 'Height', 'crt-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 120,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 90,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-highlighted-text svg' => 'height: {{SIZE}}%;',
				],	
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_responsive_control(
			'marker_weight',
			[
				'label' => esc_html__( 'Weight', 'crt-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-highlighted-text path' => 'stroke-width: {{SIZE}}{{UNIT}}',
				],	
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_control(
			'marker_position',
			[
				'label' => esc_html__( 'Z-index', 'crt-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'over',
				'options' => [
					'under' => esc_html__( 'Under Text', 'crt-addons' ),
					'over' => esc_html__( 'Over Text', 'crt-addons' ),
				],
				'selectors_dictionary' => [
					'under' => '0',
					'over' => '1'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-highlighted-text svg' => 'z-index: {{VALUE}}',
				],
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Clipped --------------------
		$this->add_section_style_clipped_text();

		// Styles
		// Section: Suffix -----------
		$this->start_controls_section(
			'section_style_suffix',
			[
				'label' => esc_html__( 'Suffix Text', 'crt-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'suffix_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-text-suffix' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'suffix_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'suffix_typography',
				'selector' => '{{WRAPPER}} .crt-advanced-text-suffix',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
	
	}

    public function crt_clipped_text() {
        // Get Settings
        $settings = $this->get_settings();

        $this->add_render_attribute( 'crt-clipped-text', 'class', 'crt-clipped-text' );

        if ( 'long' === $settings['clipped_text_shadow_type'] ) {
            $clipped_options = [
                'longShadowColor' => $settings['clipped_text_long_shadow_color'],
                'longShadowSize' => $settings['clipped_text_long_shadow_size']['size'],
                'longShadowSizeTablet' => $settings['clipped_text_long_shadow_size_tablet']['size'],
                'longShadowSizeMobile' => $settings['clipped_text_long_shadow_size_mobile']['size'],
                'longShadowDirection' => $settings['clipped_text_long_shadow_direction'],
            ];

            $this->add_render_attribute( 'crt-clipped-text', 'data-clipped-options', wp_json_encode( $clipped_options ) );
        }

        ?>

        <?php if ( '' !== $settings['clipped_text'] ) : ?>

            <span <?php echo $this->get_render_attribute_string( 'crt-clipped-text' ); ?>>
			<span class="crt-clipped-text-content"><?php echo esc_html( $settings['clipped_text'] ); ?></span>
			<?php if ( 'long' === $settings['clipped_text_shadow_type'] ) : ?>
                <span class="crt-clipped-text-long-shadow"><?php echo esc_html( $settings['clipped_text'] ); ?></span>
            <?php endif ?>
		</span>

        <?php endif; ?>

        <?php
    }

    public function crt_highlighted_text() {
		$settings = $this->get_settings();
		$svg_arr = [
			'circle' 			=> [ 'M284.72,15.61C276.85,14.43,2-2.85,2,80.46c0,34.09,45.22,58.86,196.31,62.81C719.59,154.18,467-74.85,109,29.15' ],
			'curly' 			=> [ 'M1.15,18C64.07,44.13,108.42,1.4,169.63,3.1,182.11,3.76,191.39,6.58,201,10c71.41,33.39,112-8.7,188.65-7,35.22,1.74,69.81,22.6,103,17' ],
			'underline' 		=> [ 'M.68,28.11c110.51-22,247.46-34.55,400.89-14.68,32.94,4.27,64.42,9.74,94.37,16.09' ],
			'double' 			=> [ 'M.58,16s93-15.56,303-12c118,2,180,12,180,12', 'M.58,127s93-13.31,303.15-10.26C421.79,118.48,483.83,127,483.83,127' ],
			'double-underline' 	=> [ 'M.58,16s93-15.56,303-12c118,2,180,12,180,12', 'M29.83,33.28S111.54,17.1,296.13,20.8c103.71,2.08,158.2,12.48,158.2,12.48' ],
			'underline-zigzag' 	=> [ 'M9.3,127.3c49.3-3,150.7-7.6,199.7-7.4c121.9,0.4,189.9,0.4,282.3,7.2C380.1,129.6,181.2,130.6,70,139 c82.6-2.9,254.2-1,335.9,1.3c-56,1.4-137.2-0.3-197.1,9' ],
			'diagonal' 			=> [ 'M.25,3.49C114.44,11.6,252,36.14,397.07,97.15c31.14,13.1,60.52,27,88.18,41.34' ],
			'strikethrough' 	=> [ 'M4,74.8h499.3' ],
			'x' 				=> [ 'M1.61,3.49C115.8,11.6,253.39,36.14,398.43,97.15c31.14,13.1,60.53,27,88.18,41.34', 'M486.61,3.49C372.42,11.6,234.84,36.14,89.79,97.15c-31.14,13.1-60.52,27-88.18,41.34' ]
		];

		?>

		<span class="crt-highlighted-text">
			<?php if ( '' !== $svg_arr[$settings['highlighted_shape']] && !empty($settings['highlighted_text']) ) : ?>		
			<span class="crt-highlighted-text-inner"><?php echo wp_kses_post( $settings['highlighted_text'] ); ?></span>

			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" class="crt-highlight-<?php echo esc_html( $settings['highlighted_shape'] ); ?>" preserveAspectRatio="none">
				<?php foreach ( $svg_arr[$settings['highlighted_shape']] as $value ) : ?>
				<path d="<?php echo esc_attr($value); ?>"></path>
				<?php endforeach; ?>
			</svg>
			<?php endif; ?>
		</span>
		<?php
	}

	public function crt_animated_text() {

		$settings = $this->get_settings();
	
		$animated_text = array_filter( explode( "\n", $settings['animated_text'] ) );
		$anim_duration_value = $settings['highlighted_duration'];

		if ( 'animated' === $settings['text_style'] ) {
			if ( in_array($settings['text_type'], ['typing', 'rotate-2', 'rotate-3', 'scale']) ) {
				$anim_duration_value = $settings['animated_duration_a'];
			} else {
				$anim_duration_value = $settings['animated_duration_b'];
			}
		}

		$anim_duration = [
			absint( $anim_duration_value * 1000 ),
			absint( $settings['anim_delay'] * 1000 ),
		];

		$anim_duration = implode( ',', $anim_duration );
		
		
		$this->add_render_attribute( 'crt-anim-text', 'class', 'crt-anim-text crt-anim-text-type-'. esc_attr($settings['text_type']) );

		$is_anim_letters = in_array( $settings['text_type'], [ 'typing', 'rotate-2', 'rotate-3', 'scale' ] );

		if ( $is_anim_letters ) {
			$this->add_render_attribute( 'crt-anim-text', 'class', 'crt-anim-text-letters' );
		}

		$this->add_render_attribute( 'crt-anim-text', 'data-anim-duration', $anim_duration );

		$this->add_render_attribute( 'crt-anim-text', 'data-anim-loop', esc_attr($settings['anim_loop']) );

		?>

		<span <?php echo $this->get_render_attribute_string( 'crt-anim-text' ); ?>>
			<span class="crt-anim-text-inner">
				<?php foreach ( $animated_text as $value ) : ?>
					<b><?php echo esc_html( $value ); ?></b>
				<?php endforeach; ?>
			</span>
			<?php $this->crt_animated_text_cursor(); ?>
		</span>

		<?php

	}

	public function crt_animated_text_cursor() {
		// Get Settings
		$settings = $this->get_settings();
		
		if ( '' !== $settings['anim_cursor_content'] && 'animated' === $settings['text_style'] && $settings['anim_cursor'] && ( 'typing' == $settings['text_type'] || 'clip' == $settings['text_type'] ) ) {
			echo '<span class="crt-anim-text-cursor">'. esc_html( $settings['anim_cursor_content'] ) .'</span>';
		}
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];

		$text_tag = $settings['text_tag'];

		if ( !in_array( $text_tag, $tags_whitelist ) ) {
			$text_tag = 'h3';
		}

		?>

		<<?php echo esc_attr( $text_tag ); ?> class="crt-advanced-text">

			<?php

			if ( '' !== $settings['text_link']['url'] ) {
				$this->add_render_attribute( 'text_link', 'href', esc_url( $settings['text_link']['url'] ) );

				if ( $settings['text_link']['is_external'] ) {
					$this->add_render_attribute( 'text_link', 'target', '_blank' );
				}

				if ( $settings['text_link']['nofollow'] ) {
					$this->add_render_attribute( 'text_link', 'nofollow', '' );
				}

				echo '<a '. $this->get_render_attribute_string( 'text_link' ) .'>' ;
			}

			?>
		
			<?php if ( '' !== $settings['prefix_text'] ) : ?>
				<span class="crt-advanced-text-preffix"><?php echo wp_kses_post($settings['prefix_text']); ?></span>
			<?php endif;

			if ( 'animated' === $settings['text_style'] ) {
				$this->crt_animated_text();
			} elseif ( 'highlighted' === $settings['text_style'] ) {
				$this->crt_highlighted_text();
			} elseif ( 'clipped' === $settings['text_style'] ) {
				$this->crt_clipped_text();
			}

			if ( '' !== $settings['suffix_text'] ) : ?>
				<span class="crt-advanced-text-suffix"><?php echo wp_kses_post($settings['suffix_text']); ?></span>
			<?php endif;

			if ( '' !== $settings['text_link']['url'] ) {
				echo '</a>';
			}

			?>
		
		</<?php echo esc_attr( $text_tag ); ?>>
		
		<?php

	}
}