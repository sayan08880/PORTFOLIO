<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
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

class CRT_Flip_Box extends Widget_Base {
		
	public function get_name() {
		return 'crt-flip-box';
	}

	public function get_title() {
		return esc_html__( 'Flip Box', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-flip-box';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'hover box', 'banner box', 'animated banner' ];
	}

	public function get_style_depends() {
		return [ 'crt-button-animations-css', 'crt-animations-css' ];
	}

    public function get_script_depends() {
        return [ 'crt-flip-box' ];
    }

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://crthemes.com/contact/?ref=rea-plugin-panel-flip-box-help-btn';
    		return 'https://crthemes.com/contact';
    }

	public function add_control_front_trigger () {
        $this->add_control(
            'front_trigger',
            [
                'label' => esc_html__( 'Trigger', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'btn' => esc_html__( 'Button', 'crt-manage' ),
                    'box' => esc_html__( 'Box', 'crt-manage' ),
                    'hover' => esc_html__( 'Hover', 'crt-manage' ),
                ],
                'separator' => 'before',
            ]
        );
	}

	public function add_control_back_link_type() {
        $this->add_control(
            'back_link_type',
            [
                'label' => esc_html__( 'Link Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'title' => esc_html__( 'Title', 'crt-manage' ),
                    'btn' => esc_html__( 'Button', 'crt-manage' ),
                    // 'btn-title' => esc_html__( 'Title & Button', 'crt-manage' ), TODO: add or remove?
                    'box' => esc_html__( 'Box', 'crt-manage' ),
                ],
                'default' => 'btn',
                'separator' => 'before',
            ]
        );
	}

	public function add_control_box_animation() {
        $this->add_control(
            'box_animation',
            [
                'label' => esc_html__( 'Animation', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'flip',
                'options' => [
                    'fade'     => esc_html__( 'Fade', 'crt-manage' ),
                    'flip'     => esc_html__( 'Flip', 'crt-manage' ),
                    'slide'    => esc_html__( 'Slide', 'crt-manage' ),
                    'push'     => esc_html__( 'Push', 'crt-manage' ),
                    'zoom-in'  => esc_html__( 'Zoom In', 'crt-manage' ),
                    'zoom-out' => esc_html__( 'Zoom Out', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-flip-box-animation-',
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );
	}

	protected function register_controls() {
		
		// Section: Front ------------
		$this->start_controls_section(
			'crt_section_front',
			[
				'label' => esc_html__( 'Front', 'crt-manage' ),
			]
		);

		$this->add_control(
            'front_icon_type',
            [
                'label' => esc_html__( 'Select Icon Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'icon' => esc_html__( 'Icon', 'crt-manage' ),
                    'image' => esc_html__( 'Image', 'crt-manage' ),
                ],
            ]
        );

		$this->add_control(
			'front_image',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'front_image_size',
				'default' => 'full',
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'front_icon',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-star',
					'library' => 'fa-regular',
				],
				'condition' => [
					'front_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_title',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' =>  esc_html__( 'Frontend Content', 'crt-manage' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_description',
			[
				 'label' => esc_html__( 'Description', 'crt-manage' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Hover mouse here to see backend content. Lorem ipsum dolor sit amet.',
				'separator' => 'before',
			]
		);

		$this->add_control_front_trigger();

		$this->add_control(
			'front_btn_text',
			[
				'label' => esc_html__( 'Frontend Button', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Click Me',
				'condition' => [
					'front_trigger' => 'btn',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'front_trigger' => 'btn',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Back ------------
		$this->start_controls_section(
			'crt__section_back',
			[
				'label' => esc_html__( 'Back', 'crt-manage' ),
			]
		);

		$this->add_control(
            'back_icon_type',
            [
                'label' => esc_html__( 'Select Icon Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'icon' => esc_html__( 'Icon', 'crt-manage' ),
                    'image' => esc_html__( 'Image', 'crt-manage' ),
                ],
            ]
        );

		$this->add_control(
			'back_image',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'back_image_size',
				'default' => 'full',
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'back_icon',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-star',
					'library' => 'fa-regular',
				],
				'condition' => [
					'back_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'back_title',
			[
				'label' => esc_html__( 'Backend Content', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Title',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_description',
			[
				'label' => esc_html__( 'Description', 'crt-manage' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'This is backend content. Lorem ipsum dolor sit amet.',
				'separator' => 'before',
			]
		);

		$this->add_control_back_link_type();

		$this->add_control(
			'back_link',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label' => esc_html__( 'Link', 'crt-manage' ),
				'placeholder' => esc_html__( 'https://your-link.com', 'crt-manage' ),
				'default' => [
					'url' => '#',
				],
				'separator' => 'before',
				'condition' => [
					'back_link_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'back_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Backend Button',
				'separator' => 'before',
				'condition' => [
					'back_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->add_control(
			'back_btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'back_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->start_controls_section(
			'crt_section_settings',
			[
				'label' => esc_html__( 'Settings', 'crt-manage' ),
			]
		);

		$this->add_responsive_control(
			'box_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1000,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 350,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_border_radius',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 700,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-flip-box-item' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-flip-box-overlay' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_box_animation();

		$this->add_control(
			'box_anim_3d',
			[
				'label' => esc_html__( '3D Animation', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'crt-flip-box-animation-3d-',
				'render_type' => 'template',
				'condition' => [
					'box_animation' => 'flip',
				],
			]
		);

		$this->add_control(
			'box_anim_direction',
			[
				'label' => esc_html__( 'Animation Direction', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
		     		'left'     => esc_html__( 'Left', 'crt-manage' ),
		     		'right'    => esc_html__( 'Right', 'crt-manage' ),
		     		'up'       => esc_html__( 'Top', 'crt-manage' ),
		     		'down'     => esc_html__( 'Bottom', 'crt-manage' ),
				],
				'prefix_class' => 'crt-flip-box-anim-direction-',
				'render_type' => 'template',
				'condition' => [
					'box_animation!' => [ 'fade', 'zoom-in', 'zoom-out', ],
				],
			]
		);

		$this->add_control(
			'box_anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 10,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-item' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],				
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'box_anim_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::crt_animation_timings(),
				'default' => 'ease-default',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles
		// Section: Front ------------
		$this->start_controls_section(
			'crt__section_style_front',
			[
				'label' => esc_html__( 'Front', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'front_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#e55b5b',
					],
				],
				'selector' => '{{WRAPPER}} .crt-flip-box-front',
			]
		);

		$this->add_control(
			'front_overlay_color',
			[
				'label' => esc_html__( 'Overlay Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c1c1c1',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'front_bg_color_image[id]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'front_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_vr_position',
			[
				'label' => esc_html__( 'Vertical Position', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end'
				],
                'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-content' =>  '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
				'prefix_class' => 'crt-flip-box-front-align-',
				'render_type' => 'template',
                'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'front_border',
				'label' => esc_html__( 'Border', 'crt-manage' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
				],
				'selector' => '{{WRAPPER}} .crt-flip-box-front',
				'separator' => 'before',
			]
		);

		// Image
		$this->add_control(
			'front_image_section',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'front_image_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'front_image_distance',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-image' => 'margin-bottom:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'front_image_border_radius',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		// Icon
		$this->add_control(
			'front_icon_section',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'front_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_icon_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-icon' => 'color: {{VALUE}};',
				],
				'condition' => [
					'front_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_icon_size',
			[
				'label' => esc_html__( 'Font Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'icon',
				],	
			]
		);

		// Title
		$this->add_control(
			'front_title_section',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_title_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'front_title_typography',
				'selector' => '{{WRAPPER}} .crt-flip-box-front .crt-flip-box-title',
			]
		);

		$this->add_control(
			'front_title_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		// Description
		$this->add_control(
			'front_description_section',
			[
				'label' => esc_html__( 'Description', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_description_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'front_description_typography',
				'selector' => '{{WRAPPER}} .crt-flip-box-front .crt-flip-box-description',
			]
		);

		$this->add_control(
			'front_description_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Back ------------
		$this->start_controls_section(
			'crt__section_style_back',
			[
				'label' => esc_html__( 'Back', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'back_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#FF348B',
					],
				],
				'selector' => '{{WRAPPER}} .crt-flip-box-back',
			]
		);

		$this->add_control(
			'back_overlay_color',
			[
				'label' => esc_html__( 'Overlay Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c1c1c1',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'back_bg_color_image[id]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'back_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_vr_position',
			[
				'label' => esc_html__( 'Vertical Position', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end'
				],
                'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-content' =>  '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
				'prefix_class' => 'crt-flip-box-back-align-',
				'render_type' => 'template',
                'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'back_border',
				'label' => esc_html__( 'Border', 'crt-manage' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
				],
				'selector' => '{{WRAPPER}} .crt-flip-box-back',
				'separator' => 'before',
			]
		);

		// Image
		$this->add_control(
			'back_image_section',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'back_image_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'back_image_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-image' => 'margin-bottom:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'back_image_border_radius',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		// Icon
		$this->add_control(
			'back_icon_section',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'back_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'back_icon_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-icon' => 'color: {{VALUE}};',
				],
				'condition' => [
					'back_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'back_icon_size',
			[
				'label' => esc_html__( 'Font Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'back_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'icon',
				],	
			]
		);

		// Title
		$this->add_control(
			'back_title_section',
			[
				'label' => esc_html__( 'Backend Content', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_title_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_title_typography',
				'selector' => '{{WRAPPER}} .crt-flip-box-back .crt-flip-box-title',
			]
		);

		$this->add_control(
			'back_title_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		// Description
		$this->add_control(
			'back_description_section',
			[
				'label' => esc_html__( 'Description', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_description_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_description_typography',
				'selector' => '{{WRAPPER}} .crt-flip-box-back .crt-flip-box-description',
			]
		);

		$this->add_control(
			'back_description_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->end_controls_section(); // End Controls Section
		
		// Styles
		// Section: Front Button -----
		$this->start_controls_section(
			'crt__section_style_front_btn',
			[
				'label' => esc_html__( 'Front Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'front_trigger' => 'btn',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_front_btn_colors' );

		$this->start_controls_tab(
			'tab_front_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'front_btn_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn'
			]
		);

		$this->add_control(
			'front_btn_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'front_btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'front_btn_box_shadow',
				'selector' => '{{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_front_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'front_btn_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn:hover, {{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn:before, {{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn:after',
			]
		);

		$this->add_control(
			'front_btn_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'front_btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'front_btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'front_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_btn_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'front_btn_typography',
				'selector' => '{{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'front_btn_padding',
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
					'{{WRAPPER}}  .crt-flip-box-front .crt-flip-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_btn_border_type',
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
					'{{WRAPPER}}  .crt-flip-box-front .crt-flip-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'front_btn_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'front_btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'front_btn_border_radius',
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
					'{{WRAPPER}} .crt-flip-box-front .crt-flip-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Back Button ------
		$this->start_controls_section(
			'crt__section_style_back_btn',
			[
				'label' => esc_html__( 'Back Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'back_link_type' => ['btn', 'btn-title']
				],
			]
		);

		$this->start_controls_tabs( 'tabs_back_btn_colors' );

		$this->start_controls_tab(
			'tab_back_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'back_btn_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn'
			]
		);

		$this->add_control(
			'back_btn_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'back_btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'back_btn_box_shadow',
				'selector' => '{{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_back_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'back_btn_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn:hover, {{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn:before, {{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn:after',
			]
		);

		$this->add_control(
			'back_btn_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'back_btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'back_btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->add_control(
			'back_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_btn_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_btn_typography',
				'selector' => '{{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'back_btn_padding',
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
					'{{WRAPPER}}  .crt-flip-box-back .crt-flip-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_btn_border_type',
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
					'{{WRAPPER}}  .crt-flip-box-back .crt-flip-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'back_btn_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'back_btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'back_btn_border_radius',
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
					'{{WRAPPER}} .crt-flip-box-back .crt-flip-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		$settings_old = $this->get_settings();

		$front_image_src = Group_Control_Image_Size::get_attachment_image_src( $settings_old['front_image']['id'], 'front_image_size', $settings_old );

		if ( ! $front_image_src ) {
			$front_image_src = $settings_old['front_image']['url'];
		}

		if ( isset($settings['front_image']['alt']) ) {
			$front_alt_text = $settings['front_image']['alt'];
		} else {
			$front_alt_text = '';
		}

		$back_image_src = Group_Control_Image_Size::get_attachment_image_src( $settings_old['back_image']['id'], 'back_image_size', $settings_old );

		if ( ! $back_image_src ) {
			$back_image_src = $settings_old['back_image']['url'];
		}

		if ( isset($settings['back_image']['alt']) ) {
			$back_alt_text = $settings['back_image']['alt'];
		} else {
			$back_alt_text = '';
		}

		$back_btn_element = 'div';
		$back_link = $settings_old['back_link']['url'];


		if ( '' !== $back_link ) {

			$back_btn_element = 'a';

			$this->add_render_attribute( 'link_attribute', 'href', esc_url( $back_link ) );

			if ( $settings_old['back_link']['is_external'] ) {
				$this->add_render_attribute( 'link_attribute', 'target', '_blank' );
			}

			if ( $settings_old['back_link']['nofollow'] ) {
				$this->add_render_attribute( 'link_attribute', 'nofollow', '' );
			}
		}

		?>
			
		<div class="crt-flip-box" data-trigger="<?php echo esc_attr( $settings['front_trigger'] ); ?>">
			
			<div class="crt-flip-box-item crt-flip-box-front crt-anim-timing-<?php echo esc_attr( $settings['box_anim_timing'] ); ?>">

				<div class="crt-flip-box-overlay"></div>

				<div class="crt-flip-box-content">
					
					<?php if ( 'icon' === $settings['front_icon_type'] && '' !== $settings['front_icon']['value'] ) : ?>
					<div class="crt-flip-box-icon">
						<i class="<?php echo esc_attr( $settings['front_icon']['value'] ); ?>"></i>
					</div>
					<?php elseif ( 'image' === $settings['front_icon_type'] && $front_image_src ) : ?>
					<div class="crt-flip-box-image">
						<img alt="<?php echo esc_attr( $front_alt_text ); ?>" src="<?php echo esc_url( $front_image_src ); ?>" >
					</div>
					<?php endif; ?>
					
					<?php if ( '' !== $settings['front_title'] ) : ?>
						<h3 class="crt-flip-box-title"><?php echo wp_kses_post($settings['front_title']); ?></h3>
					<?php endif; ?>

					<?php if ( '' !== $settings['front_description'] ) : ?>
						<div class="crt-flip-box-description"><?php echo wp_kses_post($settings['front_description']); ?></div>						
					<?php endif; ?>	

					<?php if ( 'btn' === $settings['front_trigger'] ) : ?>
						<div class="crt-flip-box-btn-wrap">
							<div class="crt-flip-box-btn">
								<?php if ( '' !== $settings['front_btn_text'] ) : ?>
								<span class="crt-flip-box-btn-text"><?php echo esc_html($settings['front_btn_text']); ?></span>		
								<?php endif; ?>

								<?php if ( '' !== $settings['front_btn_icon']['value'] ) : ?>
								<span class="crt-flip-box-btn-icon">
									<i class="<?php echo esc_attr( $settings['front_btn_icon']['value'] ); ?>"></i>
								</span>
								<?php endif; ?>
							</div>	
						</div>						
					<?php endif; ?>	

				</div>
			</div>

			<div class="crt-flip-box-item crt-flip-box-back crt-anim-timing-<?php echo esc_attr( $settings['box_anim_timing'] ); ?>">

				<div class="crt-flip-box-overlay"></div>
				
				<div class="crt-flip-box-content">
					
					<?php if ( 'box' === $settings['back_link_type'] ): ?>
					<a class="crt-flip-box-link" <?php echo $this->get_render_attribute_string( 'link_attribute' ); ?>></a>	
					<?php endif; ?>

					<?php if ( 'icon' === $settings['back_icon_type'] && '' !== $settings['back_icon']['value'] ) : ?>
					<div class="crt-flip-box-icon">
						<i class="<?php echo esc_attr( $settings['back_icon']['value'] ); ?>"></i>
					</div>
					<?php elseif ( 'image' === $settings['back_icon_type'] && $back_image_src ) : ?>
						<div class="crt-flip-box-image">
							<img alt="<?php echo esc_attr( $back_alt_text ); ?>" src="<?php echo esc_url( $back_image_src ); ?>" >
						</div>
					<?php endif; ?>
					
					<?php if ( '' !== $settings['back_title'] ) : ?>
						<h3 class="crt-flip-box-title">
							<?php
							if ( 'title' === $settings['back_link_type'] || 'btn-title' === $settings['back_link_type']  ) {
								echo '<a '. $this->get_render_attribute_string( 'link_attribute' ).'>';
							}

							echo wp_kses_post($settings['back_title']);
						
							if ( 'title' === $settings['back_link_type'] || 'btn-title' === $settings['back_link_type']  ) {
								echo '</a>';
							}
							?>
						</h3>
					<?php endif; ?>

					<?php if ( '' !== $settings['back_description'] ) : ?>
						<div class="crt-flip-box-description"><?php echo wp_kses_post($settings['back_description']); ?></div>						
					<?php endif; ?>	

					<?php if ( 'btn' === $settings['back_link_type'] || 'btn-title' === $settings['back_link_type'] ) : ?>

						<div class="crt-flip-box-btn-wrap">
							<?php echo '<'. esc_html($back_btn_element) .' class="crt-flip-box-btn" '. $this->get_render_attribute_string( 'link_attribute' ) .'>'; ?>

								<?php if ( '' !== $settings['back_btn_text'] ) : ?>
								<span class="crt-flip-box-btn-text"><?php echo esc_html($settings['back_btn_text']); ?></span>		
								<?php endif; ?>

								<?php if ( '' !== $settings['back_btn_icon']['value'] ) : ?>
								<span class="crt-flip-box-btn-icon">
									<i class="<?php echo esc_attr( $settings['back_btn_icon']['value'] ); ?>"></i>
								</span>
								<?php endif; ?>

							<?php echo '</'. esc_html($back_btn_element) .'>'; ?>
						</div>						
					<?php endif; ?>	

				</div>
			</div>
		</div>

		<?php

	}
}
