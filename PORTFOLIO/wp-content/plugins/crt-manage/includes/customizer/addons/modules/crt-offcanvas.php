<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Offcanvas extends Widget_Base {

	protected $nav_menu_index = 1;
	
	public function get_name() {
		return 'crt-offcanvas';
	}

	public function get_title() {
		return esc_html__( 'Off-Canvas Content', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-sidebar';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'offcanvas', 'menu', 'nav', 'content', 'off canvas', 'sidebar', 'ofcanvas', 'popup' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_style_depends() {
		return [ 'crt-link-animations-css' ];
	}

    public function get_script_depends() {
        return [ $this->get_name() ];
    }

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://crthemes.com/contact/?ref=rea-plugin-panel-nav-menu-help-btn';
    		return 'https://crthemes.com/contact';
    }

	public function add_control_offcanvas_position() {
        $this->add_control(
            'offcanvas_position',
            [
                'label'        => esc_html__('Position', 'crt-manage'),
                'type'         => Controls_Manager::SELECT,
                'label_block'  => false,
                'default'      => 'right',
                'render_type' => 'template',
                'options'      => [
                    'right' => esc_html__('Right', 'crt-manage'),
                    'left'  => esc_html__('Left', 'crt-manage'),
                    'top'   => esc_html__('Top', 'crt-manage'),
                    'bottom'  => esc_html__('Bottom', 'crt-manage'),
                    'middle'  => esc_html__('Middle', 'crt-manage'),
                    'relative'  => esc_html__('Relative', 'crt-manage')
                ]
            ]
        );
	}

	public function add_responsive_control_offcanvas_box_width() {
		$this->add_responsive_control(
			'offcanvas_box_width',
			[
				'label' => __( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'classes' => '',
				'size_units' => ['px', '%', 'vw'],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 3000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					]
				],
                'selectors' => [
                    '{{WRAPPER}} .crt-offcanvas-content' => 'width: {{SIZE}}{{UNIT}};',
                    '.crt-offcanvas-wrap-{{ID}} .crt-offcanvas-content' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-offcanvas-wrap.crt-offcanvas-wrap-relative' => 'width: {{SIZE}}{{UNIT}};'
                ],
				'condition' => [
					'offcanvas_position' => ['left', 'right', 'middle', 'relative']
				]
			]
		);
	}

	public function add_responsive_control_offcanvas_box_height() {
		$this->add_responsive_control(
			'offcanvas_box_height',
			[
				'label' => __( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'classes' => '',
				'size_units' => ['px', '%', 'vh'],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 3000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'vh',
					'size' => 30,
				],
                'selectors' => [
                    '{{WRAPPER}} .crt-offcanvas-content' => 'height: {{SIZE}}{{UNIT}};',
                    '.crt-offcanvas-wrap-{{ID}} .crt-offcanvas-content' => 'height: {{SIZE}}{{UNIT}};',
                    '.crt-offcanvas-wrap-{{ID}}.crt-offcanvas-content-wrap' => 'height: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'offcanvas_position' => ['top', 'bottom', 'middle', 'relative']
                ]
			]
		);
	}

	public function add_control_offcanvas_entrance_animation() {
        $this->add_control(
            'offcanvas_entrance_animation',
            [
                'label' => esc_html__( 'Entrance Animation', 'crt-manage' ),
                'description' => esc_html__('Only fade animation works with Position Relative', 'crt-manage'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'fade',
                'options' => [
                    'fade' => esc_html__( 'Fade', 'crt-manage' ),
                    'slide' => esc_html__( 'Slide', 'crt-manage' ),
                    'grow' => esc_html__( 'Grow', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-offcanvas-entrance-animation-'
            ]
        );
	}

	public function add_control_offcanvas_entrance_type() {
        $this->add_control(
            'offcanvas_entrance_type',
            [
                'label' => esc_html__( 'Entrance Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'options' => [
                    'cover' => esc_html__( 'Cover', 'crt-manage' ),
                    'push' => esc_html__( 'Push', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-offcanvas-entrance-type-',
                'default' => 'cover',
                'condition' => [
                    'offcanvas_position' => ['top', 'left', 'right'],
                    // 'offcanvas_entrance_animation' => ['slide', 'grow']
                ]
            ]
        );
	}

	public function add_control_offcanvas_animation_duration() {
        $this->add_control(
            'offcanvas_animation_duration',
            [
                'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'render_type' => 'template',
                'default' => 0.6,
                'min' => 0,
                'max' => 15,
                'step' => 0.1,
                'selectors' => [
                    '.crt-offcanvas-wrap-{{ID}} .crt-offcanvas-content' => 'animation-duration: {{VALUE}}s !important',
                    '{{WRAPPER}} .crt-offcanvas-content' => 'animation-duration: {{VALUE}}s !important',
                    // '.crt-offcanvas-wrap-{{ID}}' => 'transition-duration: {{VALUE}}s !important',
                    // '{{WRAPPER}} .crt-offcanvas-wrap' => 'transition-duration: {{VALUE}}s !important',
                    // '.crt-offcanvas-wrap-{{ID}}.crt-offcanvas-wrap-active' => 'transition-duration: {{VALUE}}s !important',
                    // '{{WRAPPER}} .crt-offcanvas-wrap-active' => 'transition-duration: {{VALUE}}s !important',
                ]
            ]
        );
	}

	public function add_control_offcanvas_open_by_default() {
        $this->add_control(
            'offcanvas_open_by_default',
            [
                'label' => esc_html__( 'Open by Default', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template'
                // 'separator' => 'before',
            ]
        );
	}

	public function add_control_offcanvas_reverse_header () {
		$this->add_control(
			'offcanvas_reverse_header',
			[
				'label' => sprintf( __( 'Reverse Header %s', 'crt-manage' ), '<i class="eicon-pro-icon"></i>' ),
				'description' => esc_html__('Reverse Close Icon and Title Locations', 'crt-manage'),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'classes' => 'no-distance',
			]
		);
	}

	public function add_control_offcanvas_button_icon() {
		// $this->add_control(
		// 	'offcanvas_button_icon',
		// 	[
		// 		'label' => sprintf( __( 'Select Icon %s', 'crt-manage' ), '<i class="eicon-pro-icon"></i>' ),
		// 		'type' => Controls_Manager::ICONS,
		// 		'classes' => '',
		// 		'skin' => 'inline',
		// 		'label_block' => false,
		// 		'default' => [
		// 			'value' => 'fas fa-bars',
		// 			'library' => 'fa-solid',
		// 		]
		// 	]
		// );

		$this->add_control(
			'offcanvas_button_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-bars',
					'library' => 'fa-solid',
				],
				'condition' => [
					'offcanvas_show_button_icon' => 'yes'
				]
			]
		);
	}

	public function crt_offcanvas_template( $id ) {
		if ( empty( $id ) ) {
			return '';
		}

		if ( defined('ICL_LANGUAGE_CODE') ) {
			$default_language_code = apply_filters('wpml_default_language', null);

			if ( ICL_LANGUAGE_CODE !== $default_language_code ) {
				$id = icl_object_id($id, 'elementor_library', false, ICL_LANGUAGE_CODE);
			}
		}

		$edit_link = '<span class="crt-template-edit-btn" data-permalink="'. get_permalink( $id ) .'">Edit Template</span>';
		
		$type = get_post_meta(get_the_ID(), '_crt_template_type', true) || get_post_meta($id, '_elementor_template_type', true);

		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Content ------------
		$this->start_controls_section(
			'section_offcanvas_content',
			[
				'label' => 'Content',
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'offcanvas_template',
			[
				'label'	=> esc_html__( 'Select Template', 'crt-manage' ),
				'type' => 'crt-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				// 'condition' => [
				// 	'offcanvas_content_type' => 'template',
				// ],
			]
		);

		$this->add_control(
			'offcanvas_show_header_title',
			[
				'label' => esc_html__( 'Header Title', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'default' => 'yes'
			]
		);

		$this->add_control(
			'offcanvas_title', [
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Offcanvas', 'crt-manage' ),
				'condition' => [
					'offcanvas_show_header_title' => 'yes'
				]
			]
		);

		$this->add_control_offcanvas_position();

		$this->add_responsive_control(
			'offcanvas_relative_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
					'{{WRAPPER}} .crt-offcanvas-wrap-relative' => 'top: calc(100% + {{SIZE}}px);',
				],
				'condition' => [
					'offcanvas_position' => 'relative'
				]
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'offcanvas', 'offcanvas_position', ['pro-lf', 'pro-tp', 'pro-btm', 'pro-mdl', 'pro-rl'] );

		$this->add_responsive_control_offcanvas_box_width();

		$this->add_responsive_control_offcanvas_box_height();

		$this->add_control_offcanvas_entrance_animation();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'offcanvas', 'offcanvas_entrance_animation', ['pro-sl', 'pro-gr'] );

		$this->add_control_offcanvas_entrance_type();

		$this->add_control_offcanvas_animation_duration();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'offcanvas', 'offcanvas_entrance_type', ['pro-ps'] );

		$this->add_control_offcanvas_open_by_default();

		$this->add_control(
			'offcanvas_button_heading',
			[
				'label' => esc_html__( 'Button', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'offcanvas_show_button_title',
			[
				'label' => esc_html__( 'Show Title', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'default' => 'yes'
			]
		);

		$this->add_control(
			'offcanvas_button_title', 
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Click Here', 'crt-manage' ),
				// 'condition' => [
				// 	'offcanvas_show_button_title' => 'yes'
				// ]
			]
		);

		$this->add_control(
			'offcanvas_show_button_icon',
			[
				'label' => esc_html__( 'Show Icon', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'default' => 'yes'
			]
		);

		$this->add_control_offcanvas_button_icon();

		// GOGA - hide if no text
		$this->add_responsive_control(
			'offcanvas_button_icon_distance',
			[
				'label' => esc_html__( 'Icon Distance', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-offcanvas-trigger i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-offcanvas-trigger svg' => 'margin-right: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'offcanvas_show_button_icon' => 'yes',
					'offcanvas_show_button_title' => 'yes',
					'offcanvas_button_title!' => ''
				]
			]
		);

		$this->add_responsive_control(
            'offcanvas_button_alignment',
            [
                'label'        => esc_html__('Align', 'crt-manage'),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'default'      => 'center',
				// 'separator' => 'before',
				'render_type' => 'template',
                'options'      => [
                    'left' => [
                        'title' => esc_html__('left', 'crt-manage'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'  => [
                        'title' => esc_html__('Center', 'crt-manage'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'crt-manage'),
                        'icon'  => 'eicon-h-align-right',
                    ],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-offcanvas-container' => 'text-align: {{VALUE}}'
				],
				'prefix_class' => 'crt-offcanvas-align-'
            ]
        );

        $this->end_controls_section();

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Tab: Style ==============
		// Section: Button ------------
		$this->start_controls_section(
			'section_style_offcanvas_button',
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

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-offcanvas-trigger' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-offcanvas-trigger svg' => 'fill: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-offcanvas-trigger' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-offcanvas-trigger' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .crt-offcanvas-trigger',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .crt-offcanvas-trigger',
			]
		);
		
		$this->add_responsive_control(
			'button_icon_size',
			[
				'label' => esc_html__( 'SVG Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
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
					'{{WRAPPER}} .crt-offcanvas-trigger svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-offcanvas-trigger:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-offcanvas-trigger:hover' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-offcanvas-trigger:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .crt-offcanvas-trigger:hover',
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
					'{{WRAPPER}} .crt-offcanvas-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-offcanvas-trigger' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .crt-offcanvas-trigger' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-offcanvas-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();
		
		// Tab: Style ==============
		// Section: Header ------------
		$this->start_controls_section(
			'section_style_offcanvas_header',
			[
				'label' => esc_html__( 'Header', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control_offcanvas_reverse_header();

		$this->add_responsive_control(
			'offcanvas_header_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .crt-offcanvas-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.crt-offcanvas-wrap-{{ID}} .crt-offcanvas-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'offcanvas_close_icon_heading',
			[
				'label' => esc_html__( 'Close Icon', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'offcanvas_close_icon_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-close-offcanvas' => 'color: {{VALUE}};',
					'.crt-offcanvas-wrap-{{ID}} .crt-close-offcanvas' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'offcanvas_close_icon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-close-offcanvas' => 'background-color: {{VALUE}};',
					'.crt-offcanvas-wrap-{{ID}} .crt-close-offcanvas' => 'background-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'offcanvas_close_icon_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .crt-close-offcanvas' => 'border-color: {{VALUE}};',
					'.crt-offcanvas-wrap-{{ID}} .crt-close-offcanvas' => 'border-color: {{VALUE}};'
				],
			]
		);

		$this->add_responsive_control(
			'offcanvas_close_icon_font_size',
			[
				'label' => esc_html__( 'Font Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
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
					'{{WRAPPER}} .crt-close-offcanvas i' => 'font-size: {{SIZE}}{{UNIT}};',
					'.crt-offcanvas-wrap-{{ID}} .crt-close-offcanvas i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-close-offcanvas svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'.crt-offcanvas-wrap-{{ID}} .crt-close-offcanvas svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'offcanvas_close_icon_box_size',
			[
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
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
					'{{WRAPPER}} .crt-close-offcanvas' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'.crt-offcanvas-wrap-{{ID}} .crt-close-offcanvas' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'offcanvas_close_icon_border_style',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
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
					'{{WRAPPER}} .crt-close-offcanvas' => 'border-style: {{VALUE}};',
					'.crt-offcanvas-wrap-{{ID}}  .crt-close-offcanvas' => 'border-style: {{VALUE}};'
				]
			]
		);
	
		$this->add_responsive_control(
				'offcanvas_close_icon_border_width',
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
						'{{WRAPPER}} .crt-close-offcanvas' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.crt-offcanvas-wrap-{{ID}} .crt-close-offcanvas' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					]
				]
		);
	
		$this->add_responsive_control(
				'offcanvas_close_icon_border_radius',
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
						'{{WRAPPER}} .crt-close-offcanvas' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.crt-offcanvas-wrap-{{ID}} .crt-close-offcanvas' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					]
				]
		);

		$this->add_control(
			'offcanvas_title_heading',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'offcanvas_show_header_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'offcanvas_title_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-offcanvas-title' => 'color: {{VALUE}};',
					'.crt-offcanvas-wrap-{{ID}} .crt-offcanvas-title' => 'color: {{VALUE}};'
				],
				'condition' => [
					'offcanvas_show_header_title' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'offcanvas_title',
				'selector' => '{{WRAPPER}} .crt-offcanvas-title, .crt-offcanvas-wrap-{{ID}} .crt-offcanvas-title',
				'condition' => [
					'offcanvas_show_header_title' => 'yes'
				]
			]
		);

        $this->end_controls_section();

		// Tab: Style ==============
		// Section: Box ------------
		$this->start_controls_section(
			'section_style_offcanvas_box',
			[
				'label' => esc_html__( 'Container', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'offcanvas_box_style',
			[
				'label' => esc_html__( 'Container', 'crt-manage' ),
				'type' => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'offcanvas_box_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-offcanvas-content' => 'background-color: {{VALUE}};',
					'.crt-offcanvas-wrap-{{ID}} .crt-offcanvas-content' => 'background-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'offcanvas_box_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .crt-offcanvas-content' => 'border-color: {{VALUE}}',
					'.crt-offcanvas-wrap-{{ID}} .crt-offcanvas-content' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'offcanvas_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-offcanvas-content, .crt-offcanvas-wrap-{{ID}} .crt-offcanvas-content',
				'fields_options' => [
					'box_shadow_type' =>
						[ 
							'default' =>'yes' 
						],
					'box_shadow' => [
						'default' =>
							[
								'horizontal' => 0,
								'vertical' => 0,
								'blur' => 5,
								'spread' => 0,
								'color' => 'rgba(0,0,0,0.1)'
							]
					]
				]
			]
		);

		$this->add_control(
			'offcanvas_box_border_style',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
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
					'{{WRAPPER}} .crt-offcanvas-content' => 'border-style: {{VALUE}};',
					'.crt-offcanvas-wrap-{{ID}} .crt-offcanvas-content' => 'border-style: {{VALUE}};'
				]
			]
		);
	
		$this->add_responsive_control(
			'offcanvas_box_border_width',
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
					'{{WRAPPER}} .crt-offcanvas-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.crt-offcanvas-wrap-{{ID}} .crt-offcanvas-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
				'condition' =>[
					'offcanvas_box_border_style!' => 'none',
				],
			]
		);
	
		$this->add_responsive_control(
				'offcanvas_box_border_radius',
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
						'{{WRAPPER}} .crt-offcanvas-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.crt-offcanvas-wrap-{{ID}} .crt-offcanvas-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'separator' => 'after',
				]
		);

		$this->add_responsive_control(
			'offcanvas_box_padding',
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
					'{{WRAPPER}} .crt-offcanvas-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.crt-offcanvas-wrap-{{ID}} .crt-offcanvas-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'offcanvas_overlay_style',
			[
				'label' => esc_html__( 'Overlay', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'offcanvas_overlay_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#07070733',
				'selectors' => [
					'{{WRAPPER}} .crt-offcanvas-wrap' => 'background-color: {{VALUE}};',
					'.crt-offcanvas-wrap-{{ID}}' => 'background-color: {{VALUE}};'
				],
				// 'condition' => [
				// 	'offcanvas_entrance_type!' => 'reveal'
				// ]
			]
		);

		$this->add_control(
			'offcanvas_scrollbar_heading',
			[
				'label' => esc_html__( 'Scrollbar', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'offcanvas_scrollbar_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-offcanvas-content::-webkit-scrollbar-thumb' => 'border-left-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'scrollbar_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-offcanvas-content::-webkit-scrollbar-thumb' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-offcanvas-content::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + 3px);',
				]
			]
		);

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'offcanvas-wrapper',
			[
				'class' => [ 'crt-offcanvas-container' ],
				'data-offcanvas-open' => $settings['offcanvas_open_by_default'],
			]
		);

		// Before rendering the button, add the aria attributes
		$this->add_render_attribute(
			'trigger-button',
			[
				'class' => 'crt-offcanvas-trigger',
				'aria-label' => ! empty($settings['offcanvas_button_title']) ? 
					esc_html($settings['offcanvas_button_title']) : 
					esc_html__('Toggle Offcanvas Panel', 'crt-manage'),
				'aria-expanded' => 'false',
				'aria-controls' => 'crt-offcanvas-' . $this->get_id()
			]
		);

		?>

		<div <?php echo $this->get_render_attribute_string( 'offcanvas-wrapper' ); ?>>
			<button <?php echo $this->get_render_attribute_string( 'trigger-button' ); ?>>
				<?php if ( 'yes' === $settings['offcanvas_show_button_icon'] && !empty($settings['offcanvas_button_icon']) ) : 
					\Elementor\Icons_Manager::render_icon( $settings['offcanvas_button_icon'] );
				endif; ?>
				<?php if ( 'yes' === $settings['offcanvas_show_button_title'] && !empty($settings['offcanvas_button_title']) ) : ?>
					<span><?php echo esc_html($settings['offcanvas_button_title']) ?></span>
				<?php endif; ?>
			</button>

			<div class="crt-offcanvas-wrap crt-offcanvas-wrap-<?php echo esc_attr( $settings['offcanvas_position'] ) ?>">
				<div class="crt-offcanvas-content crt-offcanvas-content-<?php echo esc_attr( $settings['offcanvas_position'] ) ?>">
					<div class="crt-offcanvas-header">
						<span class="crt-close-offcanvas">
							<i class="fa fa-times" aria-hidden="true"></i>
						</span>
						<?php if ( 'yes' === $settings['offcanvas_show_header_title'] && !empty($settings['offcanvas_title']) ) : ?>
							<span class="crt-offcanvas-title"><?php echo esc_html($settings['offcanvas_title']) ?></span>
						<?php endif; ?>
					</div>
					<?php
						if ( !empty($settings['offcanvas_template']) ) {
							echo $this->crt_offcanvas_template($settings['offcanvas_template']);
						} else {
							echo '<p>'. esc_html__('Please select a template!', 'crt-manage') .'</p>';
						}
					?>
				</div>
			</div>
		</div>
        
    <?php }
}