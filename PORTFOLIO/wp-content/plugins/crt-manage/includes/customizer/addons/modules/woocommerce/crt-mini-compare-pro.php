<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CRT_Mini_Compare_Pro extends Widget_Base {
	
	public function get_name() {
		return 'crt-mini-compare-pro';
	}

	public function get_title() {
		return esc_html__( 'Mini Compare', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-exchange';
	}

	public function get_categories() {
        return [ 'crt_manage_woocommerce' ];
	}

    public function get_script_depends() {
        return [ 'crt-mini-compare-pro' ];
    }

	public function get_keywords() {
		return [  'compare count' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Settings ------------
		$this->start_controls_section(
			'section_compare_count_settings',
			[
				'label' => esc_html__( 'Settings', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

//		$this->add_control(
//			'compare_notice_video_tutorial',
//			[
//				'type' => Controls_Manager::RAW_HTML,
//				'raw' => __( 'Build Wishlist & Compare features <strong>completely with Elementor and Royal Elementor Addons !</strong> <ul><li><a href="https://www.youtube.com/watch?v=wis1rQTn1tg" target="_blank" style="color: #93003c;"><strong>Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></strong></a></li></ul>', 'crt-manage' ),
//				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info'
//			]
//		);

        $this->add_control(
            'compare_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' =>  sprintf( __( '<strong>Note:</strong> Navigate to <a href="%s" target="_blank">CRT Builder > Settings</a><br> to choose your <strong>Compare Page</strong>.', 'crt-manage' ), admin_url( 'admin.php?page=crt-manage' ) ),
                'separator' => 'after',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info'
            ]
        );

		$this->add_control(
			'toggle_text',
			[
				'label' => esc_html__( 'Text', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'view_compare_text',
			[
				'label' => esc_html__( 'Compare Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'View Compare', 'crt-manage' ),
				'default' => esc_html__( 'View Compare', 'crt-manage' ),
				// 'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'compare_button_alignment',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'crt-manage' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', 'crt-manage' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-wrap' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'compare_style',
			[
				'label' => esc_html__( 'Compare Content', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'render_type' => 'template',
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'popup' => esc_html__( 'Pop-up', 'crt-manage' ),
				],
				'prefix_class' => 'crt-compare-style-',
				'default' => 'none'
			]
		);

		$this->add_control(
			'compare_entrance',
			[
				'label' => esc_html__( 'Entrance Animation', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'crt-manage' ),
					'slide' => esc_html__( 'Slide', 'crt-manage' ),
				],
				'prefix_class' => 'crt-compare-',
				'condition' => [
						'compare_style' => 'dropdown'
				]
			]
		);

        $this->add_control(
            'compare_entrance_speed',
            [
                'label' => __( 'Entrance Speed', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 600,
                'render_type' => 'template',
				'condition' => [
					'compare_style!' => 'none'
				]
            ]
        );

		$this->add_control(
			'open_in_new_tab',
			[
				'label' => esc_html__( 'Open in New Tab', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_close_btn',
			[
				'label'     => esc_html__('Close Button', 'crt-manage'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'compare_style' => 'popup'
				]
			]
		);

		$this->add_control(
			'close_compare_heading',
			[
				'label' => esc_html__( 'Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Compare', 'crt-manage' ),
				'default' => esc_html__( 'Compare', 'crt-manage' ),
				// 'render_type' => 'template',
				'condition' => [
					'compare_style' => 'popup'
				]
			]
		);

		$this->add_responsive_control(
			'compare_heading_align',
			[
				'label' => esc_html__( 'Title Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'crt-manage' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'End', 'crt-manage' ),
						'icon' => 'eicon-h-align-right',
					]
				],
				'selectors_dictionary' => [
					'left' => '',
					'right' => 'flex-direction: row-reverse;'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-close-compare' => '{{VALUE}}',
				],
				'condition' => [
					'compare_style' => 'popup'
				]
			]
		);

		$this->end_controls_section();
		
		// Tab: Styles ==============
		// Section: Toggle Button ----------
		$this->start_controls_section(
			'section_compare_button',
			[
				'label' => esc_html__( 'Compare Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'toggle_btn_compare_icon',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Icon', 'crt-manage' ),
			]
		);

		$this->add_control(
			'toggle_btn_icon_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-toggle-btn i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-compare-toggle-btn svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'toggle_btn_icon_color_hover',
			[
				'label'  => esc_html__( 'Color (Hover)', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-compare-toggle-btn:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-compare-toggle-btn:hover svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_icon_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-toggle-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-compare-toggle-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'toggle_btn_compare_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Text', 'crt-manage' ),
				'separator' => 'before',
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);

		$this->add_control(
			'compare_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-text' => 'color: {{VALUE}}',
				],
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'crt-manage' ),
                'selector' => '{{WRAPPER}} .crt-compare-toggle-btn, {{WRAPPER}} .crt-compare-count',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '13',
							'unit' => 'px',
						],
					]
				],
				'condition' => [
					'toggle_text!' => 'none'
				]
            ]
        );

		$this->add_responsive_control(
			'toggle_text_distance',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-toggle-btn .crt-compare-text' => 'margin-right: {{SIZE}}{{UNIT}};'
                ],
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);

		$this->add_control(
			'compare_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-toggle-btn' => 'background-color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-toggle-btn' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'compare_btn_box_shadow',
				'selector' => '{{WRAPPER}} .crt-compare-toggle-btn',
			]
		);

		$this->add_responsive_control(
			'compare_btn_padding',
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
					'{{WRAPPER}} .crt-compare-toggle-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_btn_border_type',
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
					'{{WRAPPER}} .crt-compare-toggle-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_btn_border_width',
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
					'{{WRAPPER}} .crt-compare-toggle-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'compare_btn_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'compare_btn_border_radius',
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
					'{{WRAPPER}} .crt-compare-toggle-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_item_count',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Item Count', 'crt-manage' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'toggle_btn_item_count_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-count' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'toggle_btn_item_count_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-count' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_font_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}}  .crt-compare-count' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_box_size',
			[
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-count' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_position',
			[
				'label' => esc_html__( 'Position', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 20,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 65,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-count' => 'bottom: {{SIZE}}{{UNIT}}; left: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_style_compare',
			[
				'label' => esc_html__( 'Compare Content', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'compare_style' => 'popup'
				]
			]
		);

		$this->add_control(
			'compare_loader_color',
			[
				'label'  => esc_html__( 'Loader Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-double-bounce .crt-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-wave .crt-rect' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-spinner-pulse' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-chasing-dots .crt-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-three-bounce .crt-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-fading-circle .crt-circle:before' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'compare_close_btn_styles',
			[
				'label'     => esc_html__('Close Button', 'crt-manage'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'compare_style' => 'popup'
				]
			]
		);

		$this->add_control(
			'compare_close_btn_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .crt-close-compare' => 'color: {{VALUE}}',
				],
				'condition' => [
					'compare_style' => 'popup',
				]
			]
		);

		$this->add_responsive_control(
			'compare_close_btn_size',
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-close-compare' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'compare_style' => 'popup',
				]
			]
		);

		$this->add_responsive_control(
			'compare_close_btn_distance',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-close-compare' => 'top: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'compare_style' => 'popup',
				]
			]
		);

		$this->add_control(
			'compare_popup_heading',
			[
				'label'     => esc_html__('Heading', 'crt-manage'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'compare_style' => 'popup',
					'close_compare_heading!' => ''
				]
			]
		);

		$this->add_control(
			'compare_sidebar_heading_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-close-compare h2' => 'color: {{VALUE}}',
				],
				'condition' => [
					'compare_style' => 'popup',
					'close_compare_heading!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'compare_sidebar_heading_typography',
				'selector' => '{{WRAPPER}} .crt-close-compare h2',
				'fields_options' => [
						'typography' => [
							'default' => 'custom',
						],
						'font_size' => [
							'default' => [
								'size' => '18',
								'unit' => 'px',
							],
						]
					],
					'condition' => [
						'compare_style' => 'popup',
						'close_compare_heading!' => ''
					]
			]
		);

		$this->add_control(
			'compare_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-popup' => 'background-color: {{VALUE}}'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-popup' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'compare_overlay_color',
			[
				'label'  => esc_html__( 'Overlay Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#070707C4',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-bg' => 'background-color: {{VALUE}}'
				],
				'condition' => [
					'compare_style' => 'popup'
				]
			]
		);

		$this->add_control(
			'scrollbar_color',
			[
				'label'  => esc_html__( 'ScrollBar Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-popup::-webkit-scrollbar-thumb' => 'border-right-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_responsive_control(
			'compare_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vw'],
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 1500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'vw' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'vw',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-popup' => 'width: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'compare_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vh'],
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 1500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'vh',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-popup' => 'height: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'compare_scrollbar_width',
			[
				'label' => esc_html__( 'ScrollBar Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-popup::-webkit-scrollbar-thumb' => 'border-right: {{SIZE}}{{UNIT}} solid;',
					'{{WRAPPER}} .crt-compare-popup::-webkit-scrollbar' => 'min-width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'compare_scrollbar_distance',
			[
				'label' => esc_html__( 'ScrollBar Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width.SIZE}}{{compare_scrollbar_width.UNIT}});',
					'[data-elementor-device-mode="widescreen"] {{WRAPPER}} .crt-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width_widescreen.SIZE}}{{compare_scrollbar_width_widescreen.UNIT}});',
					'[data-elementor-device-mode="laptop"] {{WRAPPER}} .crt-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width_laptop.SIZE}}{{compare_scrollbar_width_laptop.UNIT}});',
					'[data-elementor-device-mode="tablet"] {{WRAPPER}} .crt-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width_tablet.SIZE}}{{compare_scrollbar_width_tablet.UNIT}});',
					'[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .crt-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width_tablet_extra.SIZE}}{{compare_scrollbar_width_tablet_extra.UNIT}});',
					'[data-elementor-device-mode="mobile"] {{WRAPPER}} .crt-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width_mobile.SIZE}}{{compare_scrollbar_width_mobile.UNIT}});',
					'[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .crt-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width_mobile_extra.SIZE}}{{compare_scrollbar_width_mobile_extra.UNIT}});',
				]
			]
		);
		
		$this->add_responsive_control(
			'compare_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-popup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_border_type',
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
					'{{WRAPPER}} .crt-compare-popup' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_border_width',
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
					'{{WRAPPER}} .crt-compare-popup' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'compare_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'compare_border_radius',
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
					'{{WRAPPER}} .crt-compare-popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

    public function get_id_by_slug($page_slug) {
        // $page_slug = "parent-page"; in case of parent page
        // $page_slug = "parent-page/sub-page"; in case of inner page
        $page = get_page_by_path($page_slug);
        if ($page) {
            return $page->ID;
        } else {
            return '#';
        }
    }
	
	// Add two new functions for handling cookies
	public function get_compare_from_cookie() {
        if (isset($_COOKIE['crt_compare'])) {
            return json_decode(stripslashes($_COOKIE['crt_compare']), true);
        } else if ( isset($_COOKIE['crt_compare_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['crt_compare_'. get_current_blog_id() .'']), true);
        }
        return array();
	}

    protected function render() {

		$settings = $this->get_settings_for_display();

        $user_id = get_current_user_id();

		if ($user_id > 0) {
			$compare = get_user_meta( get_current_user_id(), 'crt_compare', true );
		
			if ( ! $compare ) {
				$compare = array();
			}
		} else {
			$compare = $this->get_compare_from_cookie();
		}
		
        $compare_count = sizeof($compare);
		$link_target = 'yes' == $settings['open_in_new_tab'] ? '_blank' : '_self';
		// $compare_link = '#' !== $this->get_id_by_slug('crt_compare') ? get_page_link($this->get_id_by_slug('crt_compare')) : '#';

		$this->add_render_attribute(
			'compare_attributes',
			[
				'data-animation' => $settings['compare_entrance_speed']
			]
		);

		echo '<div class="crt-compare-wrap "' . $this->get_render_attribute_string( 'compare_attributes' ) . '>';
		
			// Get the selected compare page ID
			$compare_page_id = get_option( 'crt_compare_page' );

			// Get the permalink to the selected page
			$compare_page_link = get_permalink( $compare_page_id );

			echo '<div class="crt-compare-toggle-btn">';
				echo '<a class="crt-inline-flex-center" href="'. $compare_page_link .'" target="'. $link_target .'">';
					if ( 'yes' == $settings['toggle_text'] ) {
						echo '<span class="crt-compare-text">'. esc_html__($settings['view_compare_text']) .'</span>';
					}
					echo '<i class="fas fa-exchange-alt" title="'. esc_html__($settings['view_compare_text']) .'">';
						echo '<span class="crt-compare-count">'. $compare_count .'</span>';
					echo '</i>';
				echo '</a>';
			echo '</div>';

			echo '<div class="crt-compare-bg  crt-compare-popup-hidden crt-compare-fade-out">';
				echo '<div class="crt-compare-popup  crt-compare-fade-in">';
					echo '<span class="crt-close-compare"></span>';
					echo '<div class="crt-compare-popup-inner-wrap">';
					echo '</div>';
				echo '</div>';
			echo '</div>';

		echo '</div>';

        // function create_compare_button() {
        // }

        // add_action( 'woocommerce_after_add_to_compare_button', 'create_compare_button' );
    }
}