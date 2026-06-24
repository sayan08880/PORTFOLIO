<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Promo_Box extends Widget_Base {
		
	public function get_name() {
		return 'crt-promo-box';
	}

	public function get_title() {
		return esc_html__( 'Promo Box', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-image';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'image hover', 'image effects', 'image box', 'promo box', 'banner box', 'animated banner', 'interactive banner' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_style_depends() {
		return [ 'crt-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://crthemes.com/contact/?ref=rea-plugin-panel-promo-box-help-btn';
    		return 'https://crthemes.com/contact';
    }

	public function add_control_image_style() {
		$this->add_control(
			'image_style',
			[
				'label' => esc_html__( 'Style', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
                    'classic' => esc_html__( 'Classic', 'crt-manage' ),
                    'cover' => esc_html__( 'Cover', 'crt-manage' ),
				],
				'prefix_class' => 'crt-promo-box-style-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_border_animation() {
		$this->add_control(
			'border_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'layla' => esc_html__( 'Layla', 'crt-manage' ),
                    'oscar' => esc_html__( 'Oscar', 'crt-manage' ),
                    'bubba' => esc_html__( 'Bubba', 'crt-manage' ),
                    'romeo' => esc_html__( 'Romeo', 'crt-manage' ),
                    'chicho' => esc_html__( 'Chicho', 'crt-manage' ),
                    'apollo' => esc_html__( 'Apollo', 'crt-manage' ),
                    'jazz' => esc_html__( 'Jazz', 'crt-manage' ),
				],
				'default' => 'oscar',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);
	}

	public function add_control_image_position() {
        $this->add_control(
            'image_position',
            [
                'label' => esc_html__( 'Position', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__( 'Left', 'crt-manage' ),
                    'center' => esc_html__( 'Center', 'crt-manage' ),
                    'right' => esc_html__( 'Right', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-promo-box-image-position-',
                'render_type' => 'template',
                'separator' => 'before',
                'condition' => [
                    'image_style' => 'classic',
                ],
            ]
        );
    }

	public function add_control_image_min_width() {
        $this->add_responsive_control(
            'image_min_width',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Min Width', 'crt-manage' ),
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 270,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-image' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_style' => 'classic',
                    'image_position' => [ 'left','right' ],
                ],
            ]
        );
    }

	public function add_control_image_min_height() {
        $this->add_responsive_control(
            'image_min_height',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Height', 'crt-manage' ),
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-image' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_style' => 'classic',
                ],
            ]
        );
    }

	public function add_control_content_bg_color() {
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_bg_color',
                'types' => [ 'classic', 'gradient' ],
                'fields_options' => [
                    'color' => [
                        'default' => '#212121',
                    ],
                ],
                'selector' => '{{WRAPPER}} .crt-promo-box-content',
                'condition' => [
                    'image_style' => 'classic',
                ],
            ]
        );
    }

	public function add_control_content_hover_bg_color() {
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_hover_bg_color',
                'types' => [ 'classic', 'gradient' ],
                'fields_options' => [
                    'color' => [
                        'default' => '#ddb34f',
                    ],
                ],
                'selector' => '{{WRAPPER}} .crt-promo-box:hover .crt-promo-box-content',
                'condition' => [
                    'image_style' => 'classic',
                ],
            ]
        );
    }

	public function add_section_badge() {
        $this->start_controls_section(
            'section_badge',
            [
                'label' => esc_html__( 'Badge', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'badge_style',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Style', 'crt-manage' ),
                'default' => 'corner',
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'corner' => esc_html__( 'Corner Badge', 'crt-manage' ),
                    'cyrcle' => esc_html__( 'Cyrcle Badge', 'crt-manage' ),
                    'flag' => esc_html__( 'Flag Badge', 'crt-manage' ),
                ],
            ]
        );

        $this->add_control(
            'badge_title',
            [
                'label' => esc_html__( ' Title', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Hot',
                'condition' => [
                    'badge_style!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'badge_title_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_responsive_control(
            'badge_cyrcle_size',
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
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-badge-cyrcle .crt-promo-box-badge-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'badge_style' => 'cyrcle',
                    'badge_style!' => 'none',
                ],
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
                        'max' => 80,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-badge-corner .crt-promo-box-badge-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg);',
                    '{{WRAPPER}} .crt-promo-box-badge-flag' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'badge_style!' => [ 'none', 'cyrcle' ],
                ],

            ]
        );

        $this->add_control(
            'badge_hr_position',
            [
                'label' => esc_html__( 'Horizontal Position', 'crt-manage' ),
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
                    ]
                ],
                'separator' => 'before',
                'condition' => [
                    'badge_style!' => 'none',
                ],
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

	public function add_section_style_badge() {
        $this->start_controls_section(
            'section_style_badge',
            [
                'label' => esc_html__( 'Badge', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'badge_style!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-badge-inner' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'default' => '#e83d17',
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-badge-inner' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .crt-promo-box-badge-flag:before' => ' border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'badge_box_shadow',
                'selector' => '{{WRAPPER}} .crt-promo-box-badge-inner'
            ]
        );

        $this->add_control(
            'badge_box_shadow_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typography',
                'label' => esc_html__( 'Typography', 'crt-manage' ),
                'selector' => '{{WRAPPER}} .crt-promo-box-badge-inner'
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 0,
                    'right' => 10,
                    'bottom' => 0,
                    'left' => 10,
                ],
                'size_units' => [ 'px', ],
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-badge .crt-promo-box-badge-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

	public function add_control_group_icon_animation_section() {
        $this->add_control(
            'icon_animation_section',
            [
                'label' => esc_html__( 'Icon', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'content_icon_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'icon_animation',
            [
                'label' => esc_html__( 'Select Animation', 'crt-manage' ),
                'type' => 'crt-animations',
                'default' => 'none',
                'condition' => [
                    'content_icon_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'icon_animation_duration',
            [
                'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.4,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-icon' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
                ],
                'condition' => [
                    'icon_animation!' => 'none',
                    'content_icon_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'icon_animation_delay',
            [
                'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-icon' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
                ],
                'condition' => [
                    'icon_animation!' => 'none',
                    'content_icon_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'icon_animation_timing',
            [
                'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->add_args_animation_timings(),
                'default' => 'ease-default',
                'condition' => [
                    'icon_animation!' => 'none',
                    'content_icon_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'icon_animation_size',
            [
                'label' => esc_html__( 'Animation Size', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'small' => esc_html__( 'Small', 'crt-manage' ),
                    'medium' => esc_html__( 'Medium', 'crt-manage' ),
                    'large' => esc_html__( 'Large', 'crt-manage' ),
                ],
                'default' => 'medium',
                'condition' => [
                    'icon_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'icon_animation_tr',
            [
                'label' => esc_html__( 'Animation Transparency', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'icon_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'icon_animation_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
                'condition' => [
                    'content_icon_type!' => 'none',
                ],
            ]
        );
    }

	public function add_control_group_title_animation_section() {
        $this->add_control(
            'title_animation_section',
            [
                'label' => esc_html__( 'Title', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'title_animation',
            [
                'label' => esc_html__( 'Select Animation', 'crt-manage' ),
                'type' => 'crt-animations',
                'default' => 'none',
            ]
        );

        $this->add_control(
            'title_animation_duration',
            [
                'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.4,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-title' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;'
                ],
                'condition' => [
                    'title_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'title_animation_delay',
            [
                'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-title' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;'
                ],
                'condition' => [
                    'title_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'title_animation_timing',
            [
                'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->add_args_animation_timings(),
                'default' => 'ease-default',
                'condition' => [
                    'title_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'title_animation_size',
            [
                'label' => esc_html__( 'Animation Size', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'small' => esc_html__( 'Small', 'crt-manage' ),
                    'medium' => esc_html__( 'Medium', 'crt-manage' ),
                    'large' => esc_html__( 'Large', 'crt-manage' ),
                ],
                'default' => 'medium',
                'condition' => [
                    'title_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'title_animation_tr',
            [
                'label' => esc_html__( 'Animation Transparency', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'title_animation!' => 'none',
                ],
            ]
        );
    }

	public function add_control_group_description_animation_section() {
        $this->add_control(
            'description_animation_section',
            [
                'label' => esc_html__( 'Description', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_animation',
            [
                'label' => esc_html__( 'Select Animation', 'crt-manage' ),
                'type' => 'crt-animations',
                'default' => 'none',
            ]
        );

        $this->add_control(
            'description_animation_duration',
            [
                'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.4,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-description' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;'
                ],
                'condition' => [
                    'description_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'description_animation_delay',
            [
                'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.2,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-description' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;'
                ],
                'condition' => [
                    'description_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'description_animation_timing',
            [
                'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->add_args_animation_timings(),
                'default' => 'ease-default',
                'condition' => [
                    'description_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'description_animation_size',
            [
                'label' => esc_html__( 'Animation Size', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'small' => esc_html__( 'Small', 'crt-manage' ),
                    'medium' => esc_html__( 'Medium', 'crt-manage' ),
                    'large' => esc_html__( 'Large', 'crt-manage' ),
                ],
                'default' => 'medium',
                'condition' => [
                    'description_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'description_animation_tr',
            [
                'label' => esc_html__( 'Animation Transparency', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'description_animation!' => 'none',
                ],
            ]
        );
    }

	public function add_control_group_btn_animation_section() {
        $this->add_control(
            'btn_animation_section',
            [
                'label' => esc_html__( 'Button', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'content_link_type' => ['btn','btn-title'],
                ],
            ]
        );

        $this->add_control(
            'btn_animation',
            [
                'label' => esc_html__( 'Select Animation', 'crt-manage' ),
                'type' => 'crt-animations',
                'default' => 'none',
                'condition' => [
                    'content_link_type' => ['btn','btn-title'],
                ],
            ]
        );

        $this->add_control(
            'btn_animation_duration',
            [
                'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.4,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-btn-wrap' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;'
                ],
                'condition' => [
                    'btn_animation!' => 'none',
                    'content_link_type' => ['btn','btn-title'],
                ],
            ]
        );

        $this->add_control(
            'btn_animation_delay',
            [
                'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-promo-box-btn-wrap' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;'
                ],
                'condition' => [
                    'btn_animation!' => 'none',
                    'content_link_type' => ['btn','btn-title'],
                ],
            ]
        );

        $this->add_control(
            'btn_animation_timing',
            [
                'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->add_args_animation_timings(),
                'default' => 'ease-default',
                'condition' => [
                    'btn_animation!' => 'none',
                    'content_link_type' => ['btn','btn-title'],
                ],
            ]
        );

        $this->add_control(
            'btn_animation_size',
            [
                'label' => esc_html__( 'Animation Size', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'small' => esc_html__( 'Small', 'crt-manage' ),
                    'medium' => esc_html__( 'Medium', 'crt-manage' ),
                    'large' => esc_html__( 'Large', 'crt-manage' ),
                ],
                'default' => 'medium',
                'condition' => [
                    'btn_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'btn_animation_tr',
            [
                'label' => esc_html__( 'Animation Transparency', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'btn_animation!' => 'none',
                ],
            ]
        );
    }

	public function add_args_animation_timings() {
		return Utilities::crt_animation_timings();
	}

	protected function register_controls() {
		
		// Section: Image ------------
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

//		Utilities::crt_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_image_style();


		$this->add_control_image_position();

		$this->add_control_image_min_width();

		$this->add_control_image_min_height();

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'full',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Content ----------
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'content_icon_type',
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
			'content_image',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'content_image_size',
				'default' => 'full',
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'content_icon',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'content_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'content_title',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Banner Title',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'crt-manage' ),
					'h2' => esc_html__( 'H2', 'crt-manage' ),
					'h3' => esc_html__( 'H3', 'crt-manage' ),
					'h4' => esc_html__( 'H4', 'crt-manage' ),
					'h5' => esc_html__( 'H5', 'crt-manage' ),
					'h6' => esc_html__( 'H6', 'crt-manage' ),
					'div' => esc_html__( 'div', 'crt-manage' ),
					'span' => esc_html__( 'span', 'crt-manage' ),
					'p' => esc_html__( 'p', 'crt-manage' ),
				],
				'default' => 'h3',
			]
		);

		$this->add_control(
			'content_description',
			[
				 'label' => esc_html__( 'Description', 'crt-manage' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Lorem Ipsum is simply dumy text of the printing typesetting industry lorem ipsum.',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_link_type',
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

		$this->add_control(
			'content_link',
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
					'content_link_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Click here',
				'separator' => 'before',
				'condition' => [
					'content_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->add_control(
			'content_btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'content_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Badge ---------
		$this->add_section_badge();

		// Section: Effects ----------
		$this->start_controls_section(
			'section_effectz',
			[
				'label' => esc_html__( 'Effects', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'hover_animation_section',
			[
				'label' => esc_html__( 'Hover Animation', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control_border_animation();


		$this->add_control(
			'border_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-bg-overlay::after' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-promo-box-bg-overlay::before' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'border_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-bg-overlay::after' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
					'{{WRAPPER}} .crt-promo-box-bg-overlay::before' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => 'none',
				],
			]
		);



		$this->add_control(
			'border_animation_section',
			[
				'label' => esc_html__( 'Hover Border Style', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'border_animation_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'default' => 'rgba(255,255,255,0.93)',
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-bg-overlay::before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-promo-box-bg-overlay::after' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-border-anim-apollo::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-border-anim-romeo::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-border-anim-romeo::after' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'border_animation_type',
			[
				'label' => esc_html__( 'Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .crt-border-anim-layla::before' => 'border-top-style: {{VALUE}};border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .crt-border-anim-layla::after' => 'border-left-style: {{VALUE}};border-right-style: {{VALUE}};',
					'{{WRAPPER}} .crt-border-anim-oscar::before' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-border-anim-bubba::before' => 'border-top-style: {{VALUE}};border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .crt-border-anim-bubba::after' => 'border-left-style: {{VALUE}};border-right-style: {{VALUE}};',
					'{{WRAPPER}} .crt-border-anim-chicho::before' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-border-anim-jazz::after' => 'border-top-style: {{VALUE}};border-bottom-style: {{VALUE}};',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => [ 'none', 'apollo', 'romeo' ],
				],
			]
		);

		$this->add_control(
			'border_animation_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
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
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-bg-overlay::before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-promo-box-bg-overlay::after' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-border-anim-romeo::before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-border-anim-romeo::after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => [ 'none', 'apollo' ],
				],
			]
		);

		$this->add_control(
			'border_animation_distance',
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-border-anim-layla::before' => 'top: calc({{SIZE}}{{UNIT}} + 20px);right: {{SIZE}}{{UNIT}};bottom: calc({{SIZE}}{{UNIT}} + 20px);left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-border-anim-layla::after' => 'top: {{SIZE}}{{UNIT}};right: calc({{SIZE}}{{UNIT}} + 20px);bottom: {{SIZE}}{{UNIT}};left: calc({{SIZE}}{{UNIT}} + 20px);',
					'{{WRAPPER}} .crt-border-anim-oscar::before' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-border-anim-bubba::before' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-border-anim-bubba::after' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-border-anim-chicho::before' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => [ 'none', 'apollo', 'romeo', 'jazz' ],
				],	
			]
		);

		$this->add_control(
			'hover_animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'image_animation_section',
			[
				'label' => esc_html__( 'Image Animation', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'image_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'zoom-in' => esc_html__( 'Zoom In', 'crt-manage' ),
					'zoom-out' => esc_html__( 'Zoom Out', 'crt-manage' ),
					'move-left' => esc_html__( 'Move Left', 'crt-manage' ),
					'move-right' => esc_html__( 'Move Right', 'crt-manage' ),
					'move-up' => esc_html__( 'Move Top', 'crt-manage' ),
					'move-down' => esc_html__( 'Move Bottom', 'crt-manage' ),
				],
				'default' => 'zoom-in',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'image_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-bg-image' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-promo-box-bg-overlay' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'image_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-bg-image' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
					'{{WRAPPER}} .crt-promo-box-bg-overlay' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
				],
				'condition' => [
					'image[url]!' => '',
					'image_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->add_args_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'image[url]!' => '',
					'image_animation!' => 'none',
				],
			]
		);

		$this->add_control_group_icon_animation_section();

		$this->add_control_group_title_animation_section();

		$this->add_control_group_description_animation_section();

		$this->add_control_group_btn_animation_section();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_content_colors' );

		$this->start_controls_tab(
			'tab_content_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_control_content_bg_color();

		$this->add_control(
			'content_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_title_color',
			[
				'label' => esc_html__( 'Title Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-promo-box-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_description_color',
			[
				'label' => esc_html__( 'Description Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_content_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control_content_hover_bg_color();

		$this->add_control(
			'content_hover_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box:hover .crt-promo-box-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_hover_title_color',
			[
				'label' => esc_html__( 'Title Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box:hover .crt-promo-box-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-promo-box:hover .crt-promo-box-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_hover_description_color',
			[
				'label' => esc_html__( 'Description Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box:hover .crt-promo-box-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'content_trans_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-content' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-promo-box-icon i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-promo-box-icon svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-promo-box-title span' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-promo-box-title a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .crt-promo-box-description p' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],
			]
		);

		$this->add_responsive_control(
			'content_min_height',
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
					'size' => 280,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-content' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 30,
					'right' => 30,
					'bottom' => 30,
					'left' => 30,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden',
				],
			]
		);


		$this->add_control(
			'content_vr_position',
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
					'{{WRAPPER}} .crt-promo-box-content' =>  '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_align',
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
                'selectors' => [
					'{{WRAPPER}} .crt-promo-box-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Image
		$this->add_control(
			'content_image_section',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'content_image_width',
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
					'{{WRAPPER}} .crt-promo-box-icon img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);


		// Icon
		$this->add_control(
			'content_icon_section',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'content_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'content_icon_size',
			[
				'label' => esc_html__( 'Font Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 27,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-content .crt-promo-box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'content_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-content .crt-promo-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_icon_type!' => 'none',
				],	
			]
		);

		$this->add_control(
			'content_icon_border_radius',
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
					'{{WRAPPER}} .crt-promo-box-content .crt-promo-box-icon img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);

		// Title
		$this->add_control(
			'content_title_section',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_title_typography',
				'selector' => '{{WRAPPER}} .crt-promo-box-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_title_shadow',
				'selector' => '{{WRAPPER}} .crt-promo-box-title',
			]
		);

		$this->add_responsive_control(
			'content_title_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		// Description
		$this->add_control(
			'content_description_section',
			[
				'label' => esc_html__( 'Description', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_description_typography',
				'selector' => '{{WRAPPER}} .crt-promo-box-description',
			]
		);

		$this->add_responsive_control(
			'content_description_distance',
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Button ------
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_link_type' => [ 'btn', 'btn-title' ],
				],
			]
		);

		$this->start_controls_tabs( 'tabs_btn_colors' );

		$this->start_controls_tab(
			'tab_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#222222',
					],
				],
				'selector' => '{{WRAPPER}} .crt-promo-box-btn'
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .crt-promo-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .crt-promo-box:hover .crt-promo-box-btn',
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box:hover .crt-promo-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box:hover .crt-promo-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .crt-promo-box:hover .crt-promo-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-btn' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'selector' => '{{WRAPPER}} .crt-promo-box-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 8,
					'right' => 17,
					'bottom' => 8,
					'left' => 17,
				],
				'selectors' => [
					'{{WRAPPER}}  .crt-promo-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_border_type',
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
					'{{WRAPPER}}  .crt-promo-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_border_width',
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
					'{{WRAPPER}} .crt-promo-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_radius',
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
					'{{WRAPPER}} .crt-promo-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Badge -----------
		$this->add_section_style_badge();

		// Styles
		// Section: Overlay ----------
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => esc_html__( 'Overlay', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_overlay_colors' );

		$this->start_controls_tab(
			'tab_overlay_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Overlay Color', 'crt-manage' ),
				'default' => 'rgba(0, 0, 0, 0.38823529411764707)',
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-bg-overlay' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'bg_css_filters',
				'selector' => '{{WRAPPER}} .crt-promo-box-bg-image',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_overlay_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'overlay_hover_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Overlay Color', 'crt-manage' ),
				'default' => 'rgba(0, 0, 0, 0.87)',
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box:hover .crt-promo-box-bg-overlay' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'bg_css_filters_hover',
				'selector' => '{{WRAPPER}} .crt-promo-box:hover .crt-promo-box-bg-image',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'overlay_blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => esc_html__( 'Normal', 'crt-manage' ),
					'multiply' => esc_html__( 'Multiply', 'crt-manage' ),
					'screen' => esc_html__( 'Screen', 'crt-manage' ),
					'overlay' => esc_html__( 'Overlay', 'crt-manage' ),
					'darken' => esc_html__( 'Darken', 'crt-manage' ),
					'lighten' => esc_html__( 'Lighten', 'crt-manage' ),
					'color-dodge' => esc_html__( 'Color-dodge', 'crt-manage' ),
					'color-burn' => esc_html__( 'Color-burn', 'crt-manage' ),
					'hard-light' => esc_html__( 'Hard-light', 'crt-manage' ),
					'soft-light' => esc_html__( 'Soft-light', 'crt-manage' ),
					'difference' => esc_html__( 'Difference', 'crt-manage' ),
					'exclusion' => esc_html__( 'Exclusion', 'crt-manage' ),
					'hue' => esc_html__( 'Hue', 'crt-manage' ),
					'saturation' => esc_html__( 'Saturation', 'crt-manage' ),
					'color' => esc_html__( 'Color', 'crt-manage' ),
					'luminosity' => esc_html__( 'luminosity', 'crt-manage' ),
				],
				'selectors' => [
					'{{WRAPPER}} .crt-promo-box-bg-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
		
	}

    public function render_pro_element_badge() {
        $settings = $this->get_settings();

        if ( $settings['badge_style'] !== 'none' && ! empty( $settings['badge_title'] ) ) :

            $this->add_render_attribute( 'crt-promo-box-badge-attr', 'class', 'crt-promo-box-badge crt-promo-box-badge-'. $settings[ 'badge_style'] );
            if ( ! empty( $settings['badge_hr_position'] ) ) :
                $this->add_render_attribute( 'crt-promo-box-badge-attr', 'class', 'crt-promo-box-badge-'. $settings['badge_hr_position'] );
            endif; ?>

            <div <?php echo $this->get_render_attribute_string( 'crt-promo-box-badge-attr' ); ?>>
                <div class="crt-promo-box-badge-inner"><?php echo $settings['badge_title']; ?></div>
            </div>
        <?php endif;
    }

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		$settings_new = $this->get_settings_for_display();

		$image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'image_size', $settings );
		$content_image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['content_image']['id'], 'content_image_size', $settings );

		if ( ! $image_src ) {
			$image_src = $settings['image']['url'];
		}

		if ( ! $content_image_src ) {
			$content_image_src = $settings['content_image']['url'];
		}

		$content_btn_element = 'div';
		
		if ( $settings_new['content_link'] ) {
			$content_link = $settings_new['content_link']['url'];
		} else {
			$content_link = $settings['content_link']['url'];
		}

		if ( '' !== $content_link ) {

			$content_btn_element = 'a';

			$this->add_render_attribute( 'link_attribute', 'href', esc_url( $content_link ) );

			if ( $settings['content_link']['is_external'] ) {
				$this->add_render_attribute( 'link_attribute', 'target', '_blank' );
			}

			if ( $settings['content_link']['nofollow'] ) {
				$this->add_render_attribute( 'link_attribute', 'nofollow', '' );
			}
		}

		$this->add_render_attribute( 'title_attribute', 'class', 'crt-promo-box-title' );
		if ( 'none' !== $settings['title_animation'] ) {
			$anim_transparency = 'yes' === $settings['title_animation_tr'] ? ' crt-anim-transparency' : '';
			$this->add_render_attribute( 'title_attribute', 'class', 'crt-anim-transparency crt-anim-size-medium crt-element-'. $settings['title_animation'] .' crt-anim-timing-'. $settings['title_animation_timing'] .' crt-anim-size-'. $settings['title_animation_size']. $anim_transparency );	
		}

		$this->add_render_attribute( 'description_attribute', 'class', 'crt-promo-box-description' );
		if ( 'none' !== $settings['description_animation'] ) {
			$anim_transparency = 'yes' === $settings['title_animation_tr'] ? ' crt-anim-transparency' : '';
			$this->add_render_attribute( 'description_attribute', 'class', 'crt-anim-transparency crt-anim-size-medium crt-element-'. $settings['description_animation'] .' crt-anim-timing-'. $settings['description_animation_timing'] .' crt-anim-size-'. $settings['description_animation_size']. $anim_transparency );	
		}

		$this->add_render_attribute( 'btn_attribute', 'class', 'crt-promo-box-btn-wrap' );
		if ( 'none' !== $settings['btn_animation'] ) {
			$anim_transparency = 'yes' === $settings['title_animation_tr'] ? ' crt-anim-transparency' : '';
			$this->add_render_attribute( 'btn_attribute', 'class', 'crt-anim-transparency crt-anim-size-medium crt-element-'. $settings['btn_animation'] .' crt-anim-timing-'. $settings['btn_animation_timing'] .' crt-anim-size-'. $settings['btn_animation_size']. $anim_transparency );	
		}

		$this->add_render_attribute( 'icon_attribute', 'class', 'crt-promo-box-icon' );
		if ( 'none' !== $settings['icon_animation'] ) {
			$anim_transparency = 'yes' === $settings['title_animation_tr'] ? ' crt-anim-transparency' : '';
			$this->add_render_attribute( 'icon_attribute', 'class', 'crt-anim-transparency crt-anim-size-medium crt-element-'. $settings['icon_animation'] .' crt-anim-timing-'. $settings['icon_animation_timing'] .' crt-anim-size-'. $settings['icon_animation_size']. $anim_transparency );	
		}

		?>

		<div class="crt-promo-box crt-animation-wrap">

			<?php if ( 'box' === $settings['content_link_type'] ): ?>
			<a class="crt-promo-box-link" <?php echo $this->get_render_attribute_string( 'link_attribute' ); ?>></a>	
			<?php endif; ?>
				
			<?php if ( $image_src ) : ?>
				<div class="crt-promo-box-image">
					<div class="crt-promo-box-bg-image crt-bg-anim-<?php echo esc_attr($settings['image_animation']); ?> crt-anim-timing-<?php echo esc_attr( $settings['image_animation_timing'] ); ?>" style="background-image:url(<?php echo esc_url( $image_src ); ?>);"></div>
					<div class="crt-promo-box-bg-overlay crt-border-anim-<?php echo esc_attr($settings['border_animation']); ?>"></div>
				</div>
			<?php endif; ?>
			
			<div class="crt-promo-box-content">

				<?php if ( 'none' !== $settings['content_icon_type'] ) : ?>
				<div <?php echo $this->get_render_attribute_string('icon_attribute'); ?>>
					<?php if ( 'icon' === $settings['content_icon_type'] && '' !== $settings['content_icon']['value'] ) : ?>
						<i class="<?php echo esc_attr( $settings['content_icon']['value'] ); ?>"></i>
					<?php elseif ( 'image' === $settings['content_icon_type'] && $content_image_src ) : ?>
						<img src="<?php echo esc_url( $content_image_src ); ?>" >
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<?php

				if ( '' !== $settings['content_title'] ) {
		
					$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
					$content_title_tag = Utilities::validate_html_tags_wl( $settings['content_title_tag'], 'h3', $tags_whitelist );

					echo '<'. esc_attr($content_title_tag) .' '. $this->get_render_attribute_string( 'title_attribute' ) .'>';
					if ( 'title' === $settings['content_link_type'] || 'btn-title' === $settings['content_link_type']  ) {
						echo '<a '. $this->get_render_attribute_string( 'link_attribute' ).'>';
					}

					echo '<span>'. wp_kses_post($settings['content_title']) .'</span>';
				
					if ( 'title' === $settings['content_link_type'] || 'btn-title' === $settings['content_link_type']  ) {
						echo '</a>';
					}

					echo '</'. esc_attr($content_title_tag) .'>';
				}

				?>

				<?php if ( '' !== $settings['content_description'] ) : ?>
					<div <?php echo $this->get_render_attribute_string( 'description_attribute' ); ?>>
						<?php echo '<p>'. wp_kses_post($settings['content_description']) .'</p>'; ?>	
					</div>						
				<?php endif; ?>

				<?php if ( 'btn' === $settings['content_link_type'] || 'btn-title' === $settings['content_link_type'] ) : ?>
					<div <?php echo $this->get_render_attribute_string( 'btn_attribute' ); ?>>
						<<?php echo esc_html($content_btn_element); ?> class="crt-promo-box-btn" <?php echo $this->get_render_attribute_string( 'link_attribute' ); ?>>

							<?php if ( '' !== $settings['content_btn_text'] ) : ?>
							<span class="crt-promo-box-btn-text"><?php echo esc_html($settings['content_btn_text']); ?></span>		
							<?php endif; ?>

							<?php if ( '' !== $settings['content_btn_icon']['value'] ) : ?>
							<span class="crt-promo-box-btn-icon">
								<i class="<?php echo esc_attr( $settings['content_btn_icon']['value'] ); ?>"></i>
							</span>
							<?php endif; ?>
						</<?php echo esc_html($content_btn_element); ?>>
					</div>	
				<?php endif; ?>
			</div>

			<?php $this->render_pro_element_badge(); ?>
		</div>

		<?php
	}
}