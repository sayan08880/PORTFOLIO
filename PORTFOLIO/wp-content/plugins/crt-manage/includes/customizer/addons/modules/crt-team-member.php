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

class CRT_Team_Member extends Widget_Base {
		
	public function get_name() {
		return 'crt-team-member';
	}

	public function get_title() {
		return esc_html__( 'Team Member', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-image-box';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'team member' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_style_depends() {
		return [ 'crt-animations-css', 'crt-button-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com/contact';
    }

    public function add_section_layout() {
        $this->start_controls_section(
            'crt__section_layout',
            [
                'label' => esc_html__( 'Layout', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'member_name_location',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Name Location', 'crt-manage' ),
                'default' => 'below',
                'options' => [
                    'over' => esc_html__( 'Over Image', 'crt-manage' ),
                    'below' => esc_html__( 'Below Image', 'crt-manage' ),
                ],
            ]
        );

        $this->add_control(
            'member_job_location',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Job Location', 'crt-manage' ),
                'default' => 'below',
                'options' => [
                    'over' => esc_html__( 'Over Image', 'crt-manage' ),
                    'below' => esc_html__( 'Below Image', 'crt-manage' ),
                ],
            ]
        );

        $this->add_control(
            'member_divider_location',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Divider Location', 'crt-manage' ),
                'default' => 'below',
                'options' => [
                    'over' => esc_html__( 'Over Image', 'crt-manage' ),
                    'below' => esc_html__( 'Below Image', 'crt-manage' ),
                ],
                'condition' => [
                    'member_divider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'member_description_location',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Description Location', 'crt-manage' ),
                'default' => 'below',
                'options' => [
                    'over' => esc_html__( 'Over Image', 'crt-manage' ),
                    'below' => esc_html__( 'Below Image', 'crt-manage' ),
                ],
            ]
        );

        $this->add_control(
            'member_social_media_location',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Socials Location', 'crt-manage' ),
                'default' => 'below',
                'options' => [
                    'over' => esc_html__( 'Over Image', 'crt-manage' ),
                    'below' => esc_html__( 'Below Image', 'crt-manage' ),
                ],
                'condition' => [
                    'social_media' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'member_btn_location',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Button Location', 'crt-manage' ),
                'default' => 'below',
                'options' => [
                    'over' => esc_html__( 'Over Image', 'crt-manage' ),
                    'below' => esc_html__( 'Below Image', 'crt-manage' ),
                ],
                'condition' => [
                    'member_btn' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'content_vertical_align',
            [
                'label' => esc_html__( 'Vertical Align', 'crt-manage' ),
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
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-cv-inner' => 'vertical-align: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

    public function add_section_image_overlay() {
        $this->start_controls_section(
            'crt__section_image_overlay',
            [
                'label' => esc_html__( 'Overlay', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'overlay_animation',
            [
                'label' => esc_html__( 'Select Animation', 'crt-manage' ),
                'type' => 'crt-animations-alt',
                'default' => 'fade-in',
            ]
        );

        $this->add_control(
            'overlay_anim_size',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Animation Size', 'crt-manage' ),
                'default' => 'large',
                'options' => [
                    'small' => esc_html__( 'Small', 'crt-manage' ),
                    'medium' => esc_html__( 'Medium', 'crt-manage' ),
                    'large' => esc_html__( 'Large', 'crt-manage' ),
                ],
                'condition' => [
                    'overlay_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'overlay_anim_duration',
            [
                'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-team-member-animation .crt-cv-outer' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
                ],
                'condition' => [
                    'overlay_animation!' => 'none',
                ],
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

    public function add_section_style_overlay() {
        $this->start_controls_section(
            'section_style_overlay',
            [
                'label' => esc_html__( 'Overlay', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Image Overlay
        $this->add_control(
            'image_overlay_section',
            [
                'label' => esc_html__( 'Image Overlay', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'image_overlay_bg_color',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .crt-member-overlay'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'overlay_border',
                'label' => esc_html__( 'Border', 'crt-manage' ),
                'default' => 'solid',
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
                'selector' => '{{WRAPPER}} .crt-member-overlay',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_overlay_padding',
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
                    '{{WRAPPER}} .crt-member-overlay-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

	protected function register_controls() {

		// Section: General ----------
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
			]
		);

		$this->add_control(
			'member_image',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'member_name',
			[
				'label' => esc_html__( 'Name', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __('John Doe', 'crt-manage'),
			]
		);

		$this->add_control(
			'member_name_tag',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'HTML Tag', 'crt-manage' ),
				'default' => 'h3',
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
			]
		);

		$this->add_control(
			'member_job',
			[
				'label' => esc_html__( 'Job', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __('Sony CEO', 'crt-manage'),
			]
		);

		$this->add_control(
			'member_description',
			[
				'label' => esc_html__( 'Description', 'crt-manage' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laorsoet non vitae lorem.',
			]
		);

		$this->add_control(
            'member_description_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$this->add_control(
			'member_divider',
			[
				'label' => esc_html__( 'Divider', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

        $this->add_control(
			'member_divider_position',
			[
				'label' => esc_html__( 'Position', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'before_job' => esc_html__( 'Before Job', 'crt-manage' ),
					'after_job' => esc_html__( 'After Job', 'crt-manage' ),
				],
				'default' => 'after_job',
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Layout -----------
		$this->add_section_layout();

		// Section: Social Media -----
		$this->start_controls_section(
			'section_social_media',
			[
				'label' => esc_html__( 'Social Media', 'crt-manage' ),
			]
		);
		$this->add_control(
			'social_media',
			[
				'label' => esc_html__( 'Show Social Media', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'social_media_is_external',
			[
				'label' => esc_html__( 'Open in new window', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_media_nofollow',
			[
				'label' => esc_html__( 'Add nofollow', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_1',
			[
				'label' => esc_html__( 'Social 1', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_1',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-facebook-f',
					'library' => 'fa-brands',
				],
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_1',
			[
				'label' => esc_html__( 'Social URL', 'crt-manage' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_2',
			[
				'label' => esc_html__( 'Social 2', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_2',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-twitter',
					'library' => 'fa-brands',
				],
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_2',
			[
				'label' => esc_html__( 'Social URL', 'crt-manage' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_3',
			[
				'label' => esc_html__( 'Social 3', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_3',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-linkedin-in',
					'library' => 'fa-brands',
				],
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_3',
			[
				'label' => esc_html__( 'Social URL', 'crt-manage' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_4',
			[
				'label' => esc_html__( 'Social 4', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_4',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_4',
			[
				'label' => esc_html__( 'Social URL', 'crt-manage' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_5',
			[
				'label' => esc_html__( 'Social 5', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_5',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_5',
			[
				'label' => esc_html__( 'Social URL', 'crt-manage' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
		
		// Section: Buttom -----------
		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__( 'Button', 'crt-manage' ),
			]
		);

		$this->add_control(
			'member_btn',
			[
				'label' => esc_html__( 'Show Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'member_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'About Me',
				'condition' => [
					'member_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'member_btn_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'crt-manage' ),
				'condition' => [
					'member_btn' => 'yes',
				],
				'show_label' => false,
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Overlay ---------------
		$this->add_section_image_overlay();

		// Styles
		// Section: Image ------------
		$this->start_controls_section(
			'crt__section_style_image',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-member-media' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'full',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Border', 'crt-manage' ),
				'default' => 'solid',
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
				'selector' => '{{WRAPPER}} .crt-member-media',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-member-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .crt-member-content'
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 20,
					'right' => 15,
					'bottom' => 50,
					'left' => 15,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .crt-member-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-member-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				// 'separator' => 'before',
			]
		);

		// Name
		$this->add_control(
			'name_section',
			[
				'label' => esc_html__( 'Name', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-member-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .crt-member-name',
			]
		);

		$this->add_responsive_control(
			'name_distance',
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
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-member-name' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'name_align',
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
					'{{WRAPPER}} .crt-member-name' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Job
		$this->add_control(
			'job_section',
			[
				'label' => esc_html__( 'Job', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'job_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9e9e9e',
				'selectors' => [
					'{{WRAPPER}} .crt-member-job' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'job_typography',
				'selector' => '{{WRAPPER}} .crt-member-job',
			]
		);

		$this->add_responsive_control(
			'job_distance',
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
					'{{WRAPPER}} .crt-member-job' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'job_align',
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
					'{{WRAPPER}} .crt-member-job' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Description
		$this->add_control(
			'description_section',
			[
				'label' => esc_html__( 'Description', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#545454',
				'selectors' => [
					'{{WRAPPER}} .crt-member-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .crt-member-description',
			]
		);

		$this->add_responsive_control(
			'description_distance',
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-member-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'description_align',
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
					'{{WRAPPER}} .crt-member-description' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Divider
		$this->add_control(
			'divider_section',
			[
				'label' => esc_html__( 'Divider', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d1d1d1',				
				'selectors' => [
					'{{WRAPPER}} .crt-member-divider:after' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_type',
			[
				'label' => esc_html__( 'Style', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-member-divider:after' => 'border-bottom-style: {{VALUE}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label' => esc_html__( 'Weight', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-member-divider:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-member-divider:after' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'divider_distance',
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
					'{{WRAPPER}} .crt-member-divider:after' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],	
			]
		);

		$this->add_control(
			'divider_align',
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
                'prefix_class'	=> 'crt-team-member-divider-',
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Social Media -----
		$this->start_controls_section(
			'crt__section_style_social_media',
			[
				'label' => esc_html__( 'Social Media', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_social_style' );

		$this->start_controls_tab(
			'tab_social_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'social_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-member-social' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-member-social svg' => 'fill: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'social_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-member-social' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-member-social' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_social_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'social_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .crt-member-social:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-member-social:hover svg' => 'fill: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'social_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-member-social:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .crt-member-social:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'social_trans_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'social_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-member-social' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_responsive_control(
			'social_size',
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
					'size' => 17,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-member-social' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-member-social svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'social_box_size',
			[
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
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
					'size' => 37,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-member-social' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-member-social i' => 'line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-member-social svg' => 'line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_gutter',
			[
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
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
					'{{WRAPPER}} .crt-member-social' => 'margin-right: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_responsive_control(
			'social_distance',
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-member-social-media' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'social_align',
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
                'prefix_class'	=> 'crt-team-member-social-media-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'social_border_type',
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
					'{{WRAPPER}} .crt-member-social' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'social_border_width',
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
					'{{WRAPPER}} .crt-member-social' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'social_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'social_border_radius',
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
					'{{WRAPPER}} .crt-member-social' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
            'testimonial_style_social_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'social_box_shadow',
				'selector' => '{{WRAPPER}} .crt-member-social',
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Button -----------
		$this->start_controls_section(
			'crt__section_style_btn',
			[
				'label' => esc_html__( 'Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'member_btn' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style' );

		$this->start_controls_tab(
			'tab_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-member-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6d71e8',
				'selectors' => [
					'{{WRAPPER}} .crt-member-btn' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6d71e8',
				'selectors' => [
					'{{WRAPPER}} .crt-member-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .crt-member-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-member-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#474de8',
				'selectors' => [
					'{{WRAPPER}} .crt-member-btn.crt-button-none:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-member-btn:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-member-btn:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#474de8',
				'selectors' => [
					'{{WRAPPER}} .crt-member-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .crt-member-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_section_anim_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'btn_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => 'crt-button-animations',
				'default' => 'crt-button-none',
			]
		);

		$this->add_control(
			'btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-member-btn' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-member-btn:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-member-btn:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'btn_animation_height',
			[
				'label' => esc_html__( 'Animation Height', 'crt-manage' ),
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
				'render_type' => 'template',
				'condition' => [
					'btn_animation' => [ 
						'crt-button-underline-from-left',
						'crt-button-underline-from-center',
						'crt-button-underline-from-right',
						'crt-button-underline-reveal',
						'crt-button-overline-reveal',
						'crt-button-overline-from-left',
						'crt-button-overline-from-center',
						'crt-button-overline-from-right'
					]
				],
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
				'selector' => '{{WRAPPER}} .crt-member-btn',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_align',
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
					'{{WRAPPER}} .crt-member-btn-wrap' => 'text-align: {{VALUE}};',
				],
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
					'right' => 35,
					'bottom' => 8,
					'left' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-member-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-member-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_border_width',
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
					'{{WRAPPER}} .crt-member-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-member-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Overlay ---------------
		$this->add_section_style_overlay();

	}

	protected function team_member_social_media() {
		// Get Settings
		$settings = $this->get_settings();
		
		if ( '' !== $settings['social_icon_1']['value'] || '' !== $settings['social_icon_2']['value'] || '' !== $settings['social_icon_3']['value'] || '' !== $settings['social_icon_4']['value'] || '' !== $settings['social_icon_5']['value'] ) : 
		
		$this->add_render_attribute( 'social_attribute', 'class', 'crt-member-social' );
				
		if ( $settings['social_media_is_external'] ) {
			$this->add_render_attribute( 'social_attribute', 'target', '_blank' );
		}

		if ( $settings['social_media_nofollow'] ) {
			$this->add_render_attribute( 'social_attribute', 'nofollow', '' );
		}

		?>

		<div class="crt-member-social-media">
			
			<?php if ( $settings['social_icon_1']['value'] ) : ?>
				<a href="<?php echo esc_url( $settings['social_url_1']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' ); ?>>
					<?php
						\Elementor\Icons_Manager::render_icon( $settings['social_icon_1'], [ 'aria-hidden' => 'true' ] ); 
					?>
				</a>
			<?php endif; ?>
		
			<?php if ( $settings['social_icon_2']['value'] ) : ?>
				<a href="<?php echo esc_url( $settings['social_url_2']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' ); ?>>
					<?php
						\Elementor\Icons_Manager::render_icon( $settings['social_icon_2'], [ 'aria-hidden' => 'true' ] ); 
					?>
				</a>
			<?php endif; ?>

			<?php if ( $settings['social_icon_3']['value'] ) : ?>
				<a href="<?php echo esc_url( $settings['social_url_3']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' ); ?>>
					<?php
						\Elementor\Icons_Manager::render_icon( $settings['social_icon_3'], [ 'aria-hidden' => 'true' ] ); 
					?>
				</a>
			<?php endif; ?>

			<?php if ( $settings['social_icon_4']['value'] ) : ?>
				<a href="<?php echo esc_url( $settings['social_url_4']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' ); ?>>
					<?php
						\Elementor\Icons_Manager::render_icon( $settings['social_icon_4'], [ 'aria-hidden' => 'true' ] ); 
					?>
				</a>
			<?php endif; ?>

			<?php if ( $settings['social_icon_5']['value'] ) : ?>
				<a href="<?php echo esc_url( $settings['social_url_5']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' ); ?>>
					<?php
						\Elementor\Icons_Manager::render_icon( $settings['social_icon_5'], [ 'aria-hidden' => 'true' ] ); 
					?>
				</a>
			<?php endif; ?>

		</div>
		
		<?php endif;
	}

	protected function team_member_button() {
		// Get Settings 
		$settings = $this->get_settings();
		
		if ( '' !== $settings['member_btn_text'] ) {

			$this->add_render_attribute( 'btn_attribute', 'class', 'crt-member-btn crt-button-effect '. $this->get_settings()['btn_animation'] );
			$this->add_render_attribute( 'btn_attribute', 'href', esc_url($settings['member_btn_url']['url']) );

			if ( $settings['member_btn_url']['is_external'] ) {
				$this->add_render_attribute( 'btn_attribute', 'target', '_blank' );
			}

			if ( $settings['member_btn_url']['nofollow'] ) {
				$this->add_render_attribute( 'btn_attribute', 'nofollow', '' );
			}

			echo '<div class="crt-member-btn-wrap">';
				echo '<a '. $this->get_render_attribute_string( 'btn_attribute' ) .'>';
					echo '<span>'. esc_html($settings['member_btn_text']) .'</span>';
				echo '</a>';
			echo '</div>';

		}
	}

	protected function team_member_content() {
		// Get Settings 
		$settings = $this->get_settings();


		
		if ( ( '' !== $settings['member_name'] && 'below' === $settings['member_name_location'] ) || 
			( '' !== $settings['member_job'] && 'below' === $settings['member_job_location'] ) || 
			( '' !== $settings['member_description'] && 'below' === $settings['member_description_location'] ) || 
			( 'yes' === $settings['social_media'] && 'below' === $settings['member_social_media_location'] ) || 
			( 'yes' === $settings['member_btn'] && 'below' === $settings['member_btn_location'] ) ) : ?>

		<div class="crt-member-content">
			<?php
				if ( '' !== $settings['member_name'] && 'below' === $settings['member_name_location'] ) {
		
					$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
					$member_name_tag = Utilities::validate_html_tags_wl( $settings['member_name_tag'], 'h3', $tags_whitelist );

					echo '<'. esc_attr( $member_name_tag ) .' class="crt-member-name">';
						echo wp_kses_post( $settings['member_name'] );
					echo '</'. esc_attr( $member_name_tag ) .'>';

				}
			?>

			<?php if ( 'yes' === $settings['member_divider'] && 'below' === $settings['member_divider_location'] && 'before_job' === $settings['member_divider_position'] ) : ?>
				<div class="crt-member-divider"></div>
			<?php endif; ?>

			<?php if ( '' !== $settings['member_job'] && 'below' === $settings['member_job_location'] ) : ?>
				<div class="crt-member-job"><?php echo esc_html( $settings['member_job'] ); ?></div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['member_divider'] && 'below' === $settings['member_divider_location'] && 'after_job' === $settings['member_divider_position'] ) : ?>
				<div class="crt-member-divider"></div>
			<?php endif; ?>

			<?php if ( '' !== $settings['member_description'] && 'below' === $settings['member_description_location'] ) : ?>
				<div class="crt-member-description"><?php echo wp_kses_post( $settings['member_description'] ); ?></div>
			<?php endif; ?>
			
			<?php 
				if( 'yes' === $settings['social_media'] && 'below' === $settings['member_social_media_location'] ) {
					$this->team_member_social_media();
				}
	 			
	 			if ( 'yes' === $settings['member_btn'] && 'below' === $settings['member_btn_location'] ) {
	 				$this->team_member_button();
	 			}
 			?>
		</div>

		<?php endif;

	}

    protected function team_member_overlay() {
        // Get Settings
        $settings = $this->get_settings();

        if ( ( '' !== $settings['member_name'] && 'over' === $settings['member_name_location'] ) ||
            ( '' !== $settings['member_job'] && 'over' === $settings['member_job_location'] ) ||
            ( '' !== $settings['member_description'] && 'over' === $settings['member_description_location'] ) ||
            ( 'yes' === $settings['social_media'] && 'over' === $settings['member_social_media_location'] ) ||
            ( 'yes' === $settings['member_btn'] && 'over' === $settings['member_btn_location'] ) ) :

            $this->add_render_attribute( 'overlay_container', 'class', 'crt-member-overlay-wrap crt-cv-container' );
            $this->add_render_attribute( 'overlay_outer', 'class', 'crt-cv-outer' );

            if ( 'none' !== $settings['overlay_animation'] ) {
                $this->add_render_attribute( 'overlay_container', 'class', 'crt-team-member-animation crt-animation-wrap' );
                $this->add_render_attribute( 'overlay_outer', 'class', 'crt-anim-transparency crt-anim-size-'. $settings['overlay_anim_size'] .' crt-overlay-'. $settings['overlay_animation'] );
            }

            ?>

            <div <?php echo $this->get_render_attribute_string( 'overlay_container' ); ?>>
                <div <?php echo $this->get_render_attribute_string( 'overlay_outer' ); ?>>
                    <div class="crt-cv-inner">

                        <div class="crt-member-overlay"></div>
                        <div class="crt-member-overlay-content">
                            <?php
                            if ( '' !== $settings['member_name'] && 'over' === $settings['member_name_location'] ) {
                                echo '<'. esc_attr( $settings['member_name_tag'] ) .' class="crt-member-name">';
                                echo esc_html( $settings['member_name'] );
                                echo '</'. esc_attr( $settings['member_name_tag'] ) .'>';
                            }
                            ?>

                            <?php if ( 'yes' === $settings['member_divider'] && 'over' === $settings['member_divider_location'] && 'before_job' === $settings['member_divider_position'] ) : ?>
                                <div class="crt-member-divider"></div>
                            <?php endif; ?>

                            <?php if ( '' !== $settings['member_job'] && 'over' === $settings['member_job_location'] ) : ?>
                                <div class="crt-member-job"><?php echo esc_html( $settings['member_job'] ); ?></div>
                            <?php endif; ?>

                            <?php if ( 'yes' === $settings['member_divider'] && 'over' === $settings['member_divider_location'] && 'after_job' === $settings['member_divider_position'] ) : ?>
                                <div class="crt-member-divider"></div>
                            <?php endif; ?>

                            <?php if ( '' !== $settings['member_description'] && 'over' === $settings['member_description_location'] ) : ?>
                                <div class="crt-member-description"><?php echo esc_html( $settings['member_description'] ); ?></div>
                            <?php endif; ?>

                            <?php
                            if ( 'yes' === $settings['social_media'] && 'over' === $settings['member_social_media_location'] ) {
                                $this->team_member_social_media();
                            }

                            if ( 'yes' === $settings['member_btn'] && 'over' === $settings['member_btn_location'] ) {
                                $this->team_member_button();
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif;

    }

    protected function render() {
	// Get Settings 
	$settings = $this->get_settings();
	
	?>

	<div class="crt-team-member">
		<?php if ( '' !== $settings['member_image']['url'] ) : ?>
			<?php
				$image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['member_image']['id'], 'image_size', $settings );

				if ( ! $image_src ) {
					$image_src = $settings['member_image']['url'];
				}
			?>

			<div class="crt-member-media">
				<div class="crt-member-image">
					<img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $settings['member_name'] ); ?>">
				</div>
				<?php $this->team_member_overlay(); ?>
			</div>
		<?php endif; ?>
		
		<?php $this->team_member_content(); ?>
	</div>

	<?php
	}
}