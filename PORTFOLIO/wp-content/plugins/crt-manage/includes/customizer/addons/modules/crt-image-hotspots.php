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
use Elementor\Utils;
use Elementor\Icons;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Image_Hotspots extends Widget_Base {
		
	public function get_name() {
		return 'crt-image-hotspots';
	}

	public function get_title() {
		return esc_html__( 'Image Hotspots', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-image-hotspot';
	}

	public function get_categories() {
        return [ 'crt_manage_theme'];
    }

	public function get_keywords() {
		return [ 'image hotspots' ];
	}

    public function get_script_depends() {
        return [ 'crt-image-hotspots' ];
    }

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com/contact';
    }

    public function add_control_tooltip_trigger() {
        $this->add_control(
            'tooltip_trigger',
            [
                'label' => esc_html__( 'Show Tooltips', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'none' => esc_html__( 'by Default', 'crt-manage' ),
                    'click' => esc_html__( 'on Click', 'crt-manage' ),
                    'hover' => esc_html__( 'on Hover', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-hotspot-trigger-',
                'render_type' => 'template',
                'separator' => 'after',
            ]
        );
    }

    public function add_control_tooltip_position() {
        $this->add_control(
            'tooltip_position',
            [
                'label' => esc_html__( 'Position', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => esc_html__( 'Top', 'crt-manage' ),
                    'bottom' => esc_html__( 'Bottom', 'crt-manage' ),
                    'left' => esc_html__( 'Left', 'crt-manage' ),
                    'right' => esc_html__( 'Right', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-hotspot-tooltip-position-',
                'render_type' => 'template',
            ]
        );
    }

	protected function register_controls() {
		
		// Section: Image ------------
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
			]
		);

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

		$this->end_controls_section(); // End Controls Section

		// Section: Hotspots ---------
		$this->start_controls_section(
			'section_hotspots',
			[
				'label' => esc_html__( 'Hotspots', 'crt-manage' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_hotspot_item' );

		$repeater->start_controls_tab(
			'tab_hotspot_item_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
			]
		);

		$repeater->add_control(
			'hotspot_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control(
			'hotspot_text',
			[
				'label' => esc_html__( 'Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'hotspot_custom_color',
			[
				'label' => esc_html__( 'Custom Color', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'hotspot_custom_text_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-hotspot-content' => 'color: {{VALUE}}',
				],
				'condition' => [
					'hotspot_custom_color' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'hotspot_custom_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-hotspot-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}}.crt-hotspot-anim-glow:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'hotspot_custom_color' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'hotspot_tooltip',
			[
				'label' => esc_html__( 'Tooltip', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'hotspot_tooltip_text',
			[
				'label' => '',
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Tooltip Content',
				'condition' => [
					'hotspot_tooltip' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'hotspot_link',
			[
				'label' => esc_html__( 'Link', 'crt-manage' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
				'separator' => 'before',
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tab_hotspot_item_position',
			[
				'label' => esc_html__( 'Position', 'crt-manage' ),
			]
		);

		$repeater->add_control(
			'hotspot_hr_position',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Position (%)', 'crt-manage' ),
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.crt-hotspot-item' => 'left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'hotspot_vr_position',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position (%)', 'crt-manage' ),
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.crt-hotspot-item' => 'top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$repeater->add_responsive_control(
			'tooltip_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-hotspot-tooltip' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'hotspot_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'hotspot_text' => '',
						'hotspot_hr_position' => [
							'unit' => '%',
							'size' => 30,
						],
						'hotspot_vr_position' => [
							'unit' => '%',
							'size' => 40,
						],
					],
					[
						'hotspot_text' => '',
						'hotspot_hr_position' => [
							'unit' => '%',
							'size' => 60,
						],
						'hotspot_vr_position' => [
							'unit' => '%',
							'size' => 20,
						],
					],
					
				],
				'title_field' => '{{{ hotspot_text }}}',
			]
		);

		$this->add_control(
			'hotspot_animation',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Animation', 'crt-manage' ),
				'default' => 'glow',
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'glow' => esc_html__( 'Glow', 'crt-manage' ),
					'pulse' => esc_html__( 'Pulse', 'crt-manage' ),
					'shake' => esc_html__( 'Shake', 'crt-manage' ),
					'swing' => esc_html__( 'Swing', 'crt-manage' ),
					'tada' => esc_html__( 'Tada', 'crt-manage' ),
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'hotspot_origin',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Origin', 'crt-manage' ),
				'description' => esc_html__('Defines where the point is located relative to hotspot item', 'crt-manage'),
				'default' => 'top-left',
				'options' => [
					'top-left' => esc_html__( 'Top Left', 'crt-manage' ),
					'top-right' => esc_html__( 'Top Right', 'crt-manage' ),
					'top-center' => esc_html__( 'Top Center', 'crt-manage' ),
					'center' => esc_html__( 'Center', 'crt-manage' ),
					'center-left' => esc_html__( 'Center Left', 'crt-manage' ),
					'center-right' => esc_html__( 'Center Right', 'crt-manage' ),
					'bottom-left' => esc_html__( 'Bottom Left', 'crt-manage' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'crt-manage' ),
					'bottom-center' => esc_html__( 'Bottom Center', 'crt-manage' )
				],
				'selectors_dictionary' => [
					'top-left' => '',
					'top-right' => 'transform: translate(-100%, 0);',
					'top-center' => 'transform: translate(-50%, 0);',
					'center' => 'transform: translate(-50%, -50%);',
					'center-left' => 'transform: translate(0, -50%);',
					'center-right' => 'transform: translate(-100%, -50%);',
					'bottom-left' => 'transform: translate(0, -100%);',
					'bottom-right' => 'transform: translate(-100%, -100%);',
					'bottom-center' => 'transform: translate(-50%, -100%);'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-hotspot-item' => '{{VALUE}}',
				],
				'separator' => 'before'
				// 'render_type' => 'template',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Tooltips ---------
		$this->start_controls_section(
			'section_tooltips',
			[
				'label' => esc_html__( 'Tooltips', 'crt-manage' ),
			]
		);

		$this->add_control_tooltip_trigger();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-hotspots', 'tooltip_trigger', ['pro-cl', 'pro-hv'] );

		$this->add_control_tooltip_position();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-hotspots', 'tooltip_position', ['pro-bt', 'pro-lt', 'pro-rt'] );

		$this->add_responsive_control(
            'tooltip_align',
            [
                'label' => esc_html__( 'Alignment', 'crt-manage' ),
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
				'selectors' => [
					'{{WRAPPER}} .crt-hotspot-tooltip' => 'text-align: {{VALUE}}',
				],
            ]
        );

		$this->add_responsive_control(
			'tooltip_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 115,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-hotspot-tooltip' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_triangle',
			[
				'label' => esc_html__( 'Triangle', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,				
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_triangle_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-hotspot-tooltip:before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-top .crt-hotspot-tooltip' => 'margin-top: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-bottom .crt-hotspot-tooltip' => 'margin-bottom: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-left .crt-hotspot-tooltip' => 'margin-left: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-right .crt-hotspot-tooltip' => 'margin-right: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-top .crt-hotspot-tooltip:before' => 'bottom: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-bottom .crt-hotspot-tooltip:before' => 'top: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-right .crt-hotspot-tooltip:before' => 'left: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-left .crt-hotspot-tooltip:before' => 'right: calc(-{{SIZE}}{{UNIT}} + 1px);',
				],
				'condition' => [
					'tooltip_triangle' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-hotspot-tooltip-position-top .crt-hotspot-tooltip' => 'top: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-bottom .crt-hotspot-tooltip' => 'bottom: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-left .crt-hotspot-tooltip' => 'left: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-right .crt-hotspot-tooltip' => 'right: -{{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_animation',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Animation', 'crt-manage' ),
				'default' => 'fade',
				'options' => [
					'shift-toward' => esc_html__( 'Shift Toward', 'crt-manage' ),
					'fade' => esc_html__( 'Fade', 'crt-manage' ),
					'scale' => esc_html__( 'Scale', 'crt-manage' ),
				],
				'prefix_class' => 'crt-tooltip-effect-',
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-hotspot-tooltip' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],		
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );



		// Section: Image ---------
		$this->start_controls_section(
			'section_style_img',
			[
				'label' => esc_html__( 'Image Hotspots', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'img_border_radius',
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
					'{{WRAPPER}} .crt-image-hotspots' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-hotspot-image>img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section
		
		// Section: Hotspots ---------
		$this->start_controls_section(
			'section_style_hotspots',
			[
				'label' => esc_html__( 'Hotspots', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hotspot_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-hotspot-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hotspot_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-hotspot-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-hotspot-anim-glow:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hotspot_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-hotspot-content' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'hotspot_box_shadow',
				'selector' => '{{WRAPPER}} .crt-hotspot-content',
			]
		);

		$this->add_control(
			'hotspot_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'hotspot_typography',
				'selector' => '{{WRAPPER}} .crt-hotspot-text',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_section',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

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
				'prefix_class' => 'crt-hotspot-icon-position-',
			]
		);

		$this->add_responsive_control(
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-hotspot-content i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-hotspot-content svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'icon_box_size',
			[
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-hotspot-content' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
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
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-hotspot-icon-position-left .crt-hotspot-text ~ i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-hotspot-icon-position-right .crt-hotspot-text ~ i' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-hotspot-icon-position-left .crt-hotspot-text ~ svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-hotspot-icon-position-right .crt-hotspot-text ~ svg' => 'margin-left: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'hotspot_border_type',
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
					'{{WRAPPER}} .crt-hotspot-content' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'hotspot_border_width',
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
					'{{WRAPPER}} .crt-hotspot-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'hotspot_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'hotspot_border_radius',
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
					'{{WRAPPER}} .crt-hotspot-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-hotspot-anim-glow:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Tooltips ---------
		$this->start_controls_section(
			'section_style_tooltips',
			[
				'label' => esc_html__( 'Tooltips', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tooltip_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-hotspot-tooltip' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tooltip_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-hotspot-tooltip' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-top .crt-hotspot-tooltip:before' => 'border-top-color: {{VALUE}}',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-bottom .crt-hotspot-tooltip:before' => 'border-top-color: {{VALUE}}',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-left .crt-hotspot-tooltip:before' => 'border-right-color: {{VALUE}}',
					'{{WRAPPER}}.crt-hotspot-tooltip-position-right .crt-hotspot-tooltip:before' => 'border-right-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tooltip_box_shadow',
				'selector' => '{{WRAPPER}} .crt-hotspot-tooltip',
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
				'selector' => '{{WRAPPER}} .crt-hotspot-tooltip',
			]
		);

		$this->add_responsive_control(
			'tooltip_padding',
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
					'{{WRAPPER}} .crt-hotspot-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-hotspot-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		$item_count = 0;
		$image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'image_size', $settings );

		if ( ! $image_src ) {
			$image_src = $settings['image']['url'];
		}

//		if ( !defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code() ) {
//			$settings['tooltip_trigger'] = 'none';
//		}

		$hotsposts_options = [	
			'tooltipTrigger' => $settings['tooltip_trigger'],
		];

		$this->add_render_attribute( 'hotspots_attribute', 'class', 'crt-image-hotspots' );
		$this->add_render_attribute( 'hotspots_attribute', 'data-options', wp_json_encode( $hotsposts_options ) );

		?>

		<div <?php echo $this->get_render_attribute_string( 'hotspots_attribute'); ?>>
			
			<?php if ( $image_src ) : ?>
				<div class="crt-hotspot-image">
					<img src="<?php echo esc_url( $image_src ); ?>" >
				</div>
			<?php endif; ?>

			<div class="crt-hotspot-item-container">
				

				<?php foreach ( $settings['hotspot_items'] as $key => $item ) : ?>
					
					<?php

//					if ( (!defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code()) && $key === 2 ) {
//						break;
//					}

					$hotspot_tag = 'div';

					$this->add_render_attribute( 'hotspot_item_attribute'. $item_count, 'class', 'crt-hotspot-item elementor-repeater-item-'. esc_attr($item['_id'] ));

					if ( 'none' !== $settings['hotspot_animation'] ) {
						$this->add_render_attribute( 'hotspot_item_attribute'. $item_count, 'class', 'crt-hotspot-anim-'. $settings['hotspot_animation'] );
					}

					$this->add_render_attribute( 'hotspot_content_attribute'. $item_count, 'class', 'crt-hotspot-content' );

					if ( '' !== $item['hotspot_link']['url'] ) {

						$hotspot_tag = 'a';

						$this->add_render_attribute( 'hotspot_content_attribute'. $item_count, 'href', esc_url( $item['hotspot_link']['url'] ) );

						if ( $item['hotspot_link']['is_external'] ) {
							$this->add_render_attribute( 'hotspot_content_attribute'. $item_count, 'target', '_blank' );
						}

						if ( $item['hotspot_link']['nofollow'] ) {
							$this->add_render_attribute( 'hotspot_content_attribute'. $item_count, 'nofollow', '' );
						}

					}

					?>

					<div <?php echo $this->get_render_attribute_string( 'hotspot_item_attribute'. $item_count ); ?>>

						<<?php echo esc_attr( $hotspot_tag ); ?> <?php echo $this->get_render_attribute_string( 'hotspot_content_attribute'. $item_count ); ?>>
							
							<?php if ( '' !== $item['hotspot_text'] ) : ?>
								<span class="crt-hotspot-text"><?php echo esc_html( $item['hotspot_text'] ); ?></span>
							<?php endif; ?>
							<?php if ( '' !== $item['hotspot_icon']['value'] && 'svg' !== $item['hotspot_icon']['library'] ) : ?>
								<i class="<?php echo esc_attr( $item['hotspot_icon']['value'] ); ?>"></i>
							<?php elseif ( '' !== $item['hotspot_icon']['value'] && 'svg' == $item['hotspot_icon']['library'] ) : ?>
								<img src="<?php echo esc_url( $item['hotspot_icon']['value']['url'] ) ?>">
							<?php endif; ?>

						</<?php echo esc_attr( $hotspot_tag ); ?>>
						
						<?php if ( 'yes' === $item['hotspot_tooltip'] && '' !== $item['hotspot_tooltip_text'] ) : ?>
							<div class="crt-hotspot-tooltip"><?php echo wp_kses_post($item['hotspot_tooltip_text']); ?></div>						
						<?php endif; ?>	

					</div>

					<?php

					$item_count++;

				endforeach;

				?>

			</div>
			
		</div>

		<?php

	}
}