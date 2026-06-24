<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Before_After extends Widget_Base {
	
	public function get_name() {
		return 'crt-before-after';
	}

	public function get_title() {
		return esc_html__( 'Before After', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-image-before-after';
	}

	public function get_categories() {
		return [ 'crt-widgets'];
	}

	public function get_keywords() {
		return [  'image compare', 'image comparison', 'before after image' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_script_depends() {
		return [ 'jquery-event-move', 'crt-before-after' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com/contact';
    }

	public function add_control_direction() {
		$this->add_control(
			'direction',
			[
				'label' => esc_html__( 'Direction', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'crt-manage' ),
					'pro-vr' => esc_html__( 'Vertical (Pro)', 'crt-manage' ),
				],
				'default' => 'horizontal',
				'separator' => 'before',
			]
		);
	}

	public function add_control_trigger() {
		$this->add_control(
			'trigger',
			[
				'label' => esc_html__( 'Trigger', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'drag' => esc_html__( 'Click & Drag', 'crt-manage' ),
					'pro-ms' => esc_html__( 'Mouse Hover (Pro)', 'crt-manage' ),
				],
				'default' => 'drag',
			]
		);
	}

    public function add_control_divider_position() {
        $this->add_control(
            'divider_position',
            [
                'label' => esc_html__( 'Divider Position', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 50,
                'min' => 0,
                'max' => 100,
                'step' => 1,
            ]
        );
    }

	public function add_control_label_display() {
		$this->add_control(
			'label_display',
			[
				'label' => esc_html__( 'Display', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'default' => esc_html__( 'by Default', 'crt-manage' ),
                    'hover' => esc_html__( 'on Hover', 'crt-manage' ),
				],
				'default' => 'default',
			]
		);
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::crt_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'image_upload_1',
			[
				'label' => esc_html__( 'Upload Image 1', 'crt-manage' ),
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
			'image_upload_2',
			[
				'label' => esc_html__( 'Upload Image 2', 'crt-manage' ),
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
			]
		);

		$this->add_control_direction();

		$this->add_control_trigger();

		$this->add_control(
			'divider_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fa-angle',
				'options' => [
					'fa-caret' => esc_html__( 'Caret', 'crt-manage' ),
					'fa-angle' => esc_html__( 'Angle', 'crt-manage' ),
					'fa-arrow' => esc_html__( 'Arrow', 'crt-manage' ),
					'fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'crt-manage' ),
					'fa-chevron' => esc_html__( 'Chevron', 'crt-manage' ),
				],
			]
		);

		$this->add_control_divider_position();

		$this->end_controls_section();

		// Tab: Content ==============
		// Section: Labels -----------
		$this->start_controls_section(
			'section_labels',
			[
				'label' => esc_html__( 'Labels', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_label_display();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'before-after', 'label_display', ['pro-hv'] );

		$this->add_control(
			'label_image_1',
			[
				'label' => esc_html__( 'Image 1 Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'After',
				'placeholder'=> esc_html__( 'After', 'crt-manage' ),
				'separator' => 'before',
				'condition' => [
					'label_display!' => 'none',
				]
			]
		);

		$this->add_control(
			'label_image_2',
			[
				'label' => esc_html__( 'Image 2 Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Before',
				'placeholder'=> esc_html__( 'Before', 'crt-manage' ),
				'condition' => [
					'label_display!' => 'none',
				]
			]
		);

		$this->add_control(
			'label_position',
			[
				'label' => esc_html__( 'Position', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start'    => [
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'flex-end',
				'selectors' => [
					'{{WRAPPER}} .crt-ba-label' => 'align-items: {{VALUE}}; justify-content: {{VALUE}}',
				],
				'condition' => [
					'label_display!' => 'none',
				]
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles ====================
		// Section: General ----------
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'general_border',
				'label' => esc_html__( 'Border', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-ba-image-container'
			]
		);

		$this->add_control(
			'general_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-ba-image-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Divider ----------
		$this->start_controls_section(
			'section_style_divider',
			[
				'label' => esc_html__( 'Divider', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'divider_line_color',
			[
				'label'  => esc_html__( 'Line Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-ba-divider-icons:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-ba-divider-icons:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_icon_color',
			[
				'label'  => esc_html__( 'Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-ba-divider-icons .fa' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-ba-divider-icons .fa' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_icon_color_bg',
			[
				'label'  => esc_html__( 'Icon Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-ba-divider-icons' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_icon_color_border',
			[
				'label'  => esc_html__( 'Icon Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-ba-divider-icons' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'divider_thickness',
			[
				'label' => esc_html__( 'Line Thickness', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ba-horizontal .crt-ba-divider-icons:before' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-ba-horizontal .crt-ba-divider-icons:after' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-ba-vertical .crt-ba-divider-icons:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-ba-vertical .crt-ba-divider-icons:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'divider_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ba-divider-icons .fa' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'divider_icon_width_hr',
			[
				'label' => esc_html__( 'Icon Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ba-horizontal .crt-ba-divider-icons .fa' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-ba-horizontal .crt-ba-divider' => 'margin-left: calc(-{{SIZE}}{{UNIT}} - {{divider_icon_bdw.SIZE}}px);',
					'{{WRAPPER}} .crt-ba-horizontal .crt-ba-divider-icons:before' => 'left: calc({{SIZE}}{{UNIT}} - {{divider_thickness.SIZE}}px / 2 + {{divider_icon_bdw.SIZE}}px);',
					'{{WRAPPER}} .crt-ba-horizontal .crt-ba-divider-icons:after' => 'left: calc({{SIZE}}{{UNIT}} - {{divider_thickness.SIZE}}px / 2 + {{divider_icon_bdw.SIZE}}px);',
				],
				'condition' => [
					'direction' => ['horizontal', 'pro-vr']
				]
			]
		);

		$this->add_responsive_control(
			'divider_icon_height_hr',
			[
				'label' => esc_html__( 'Icon Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ba-horizontal .crt-ba-divider-icons .fa' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-ba-horizontal .crt-ba-divider-icons:before' => 'bottom: calc(50% + {{divider_icon_bdw.SIZE}}px + {{SIZE}}{{UNIT}} / 2 - 0.7px);',
					'{{WRAPPER}} .crt-ba-horizontal .crt-ba-divider-icons:after' => 'top: calc(50% + {{divider_icon_bdw.SIZE}}px + {{SIZE}}{{UNIT}} / 2 + 0.1px);',
				],
				'separator' => 'after',
				'condition' => [
					'direction' => ['horizontal', 'pro-vr']
				]
			]
		);

		$this->add_responsive_control(
			'divider_icon_width_vr',
			[
				'label' => esc_html__( 'Icon Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ba-vertical .crt-ba-divider-icons .fa' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-ba-vertical .crt-ba-divider-icons:before' => 'right: calc(50% + {{SIZE}}{{UNIT}} / 2 + {{divider_icon_bdw.SIZE}}px);',
					'{{WRAPPER}} .crt-ba-vertical .crt-ba-divider-icons:after' => 'left: calc(50% + {{SIZE}}{{UNIT}} / 2 + {{divider_icon_bdw.SIZE}}px);',
				],
				'condition' => [
					'direction' => 'vertical'
				]
			]
		);

		$this->add_responsive_control(
			'divider_icon_height_vr',
			[
				'label' => esc_html__( 'Icon Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ba-vertical .crt-ba-divider-icons .fa' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-ba-vertical .crt-ba-divider-icons .fa:first-child' => 'line-height: calc({{SIZE}}{{UNIT}} * 1.3);',
					'{{WRAPPER}} .crt-ba-vertical .crt-ba-divider-icons .fa:last-child' => 'line-height: calc({{SIZE}}{{UNIT}} * 0.8);',
					'{{WRAPPER}} .crt-ba-vertical .crt-ba-divider' => 'margin-top: calc(-{{SIZE}}{{UNIT}} - {{divider_icon_bdw.SIZE}}px);',
					'{{WRAPPER}} .crt-ba-vertical .crt-ba-divider-icons:before' => 'top: calc({{SIZE}}{{UNIT}} - {{divider_thickness.SIZE}}px / 2 + {{divider_icon_bdw.SIZE}}px);',
					'{{WRAPPER}} .crt-ba-vertical .crt-ba-divider-icons:after' => 'top: calc({{SIZE}}{{UNIT}} - {{divider_thickness.SIZE}}px / 2 + {{divider_icon_bdw.SIZE}}px);',
				],
				'separator' => 'after',
				'condition' => [
					'direction' => 'vertical'
				]
			]
		);

		$this->add_control(
			'divider_icon_border_type',
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
					'{{WRAPPER}} .crt-ba-divider-icons' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_icon_bdw',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ba-divider-icons' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'divider_icon_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'divider_icon_radius',
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
					'{{WRAPPER}} .crt-ba-divider-icons' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Labels -----------
		$this->start_controls_section(
			'section_style_labels',
			[
				'label' => esc_html__( 'Labels', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'label_display!' => 'none',
				]
			]
		);

		$this->add_control(
			'labels_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-ba-label > div' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'labels_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-ba-label > div' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'labels_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-ba-label > div' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'labels_box_shadow',
				'selector' => '{{WRAPPER}} .crt-ba-label > div',
			]
		);

		$this->add_control(
			'labels_box_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'labels_typography',
				'selector' => '{{WRAPPER}} .crt-ba-label > div'
			]
		);

		$this->add_control(
			'labels_border_type',
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
					'{{WRAPPER}} .crt-ba-label > div' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'labels_border_width',
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
					'{{WRAPPER}} .crt-ba-label > div' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'labels_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'labels_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 7,
					'right' => 15,
					'bottom' => 7,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ba-label > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'labels_radius',
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
					'{{WRAPPER}} .crt-ba-label > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'labels_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ba-label > div' => 'margin: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		$settings_new = $this->get_settings_for_display();

		// Class
		$class  = ' crt-ba-'. $settings['direction'];
		$class .= ' crt-ba-labels-'. $settings['label_display'];

		// Icon Direction
		$icon_dir_first  = 'horizontal' === $settings['direction'] ? 'left' : 'up';
		$icon_dir_second = 'horizontal' === $settings['direction'] ? 'right' : 'down';

		// Image Source
		$image_1_src = !empty($settings_new['image_upload_1']['id']) ? Group_Control_Image_Size::get_attachment_image_src( $settings_new['image_upload_1']['id'], 'image_size', $settings_new ) : $settings_new['image_upload_1']['url'];
		$image_2_src = !empty($settings_new['image_upload_2']['id']) ? Group_Control_Image_Size::get_attachment_image_src( $settings_new['image_upload_2']['id'], 'image_size', $settings_new ) : $settings_new['image_upload_2']['url'];

		// Divider
		echo '<div class="crt-ba-image-container'. esc_attr($class) .'" data-position="'. esc_attr($settings['divider_position']) .'" data-trigger="'. esc_attr($settings['trigger']) .'">';
			
			// Defaults
			// if ( '' !== $settings['image_upload_1']['url'] ) {
			// 	$image_1_src = $settings['image_upload_1']['url'];
			// }
			// if ( '' !== $settings['image_upload_2']['url'] ) {
			// 	$image_2_src = $settings['image_upload_2']['url'];
			// }

			// Image 1
			echo '<div class="crt-ba-image-1">';
				echo '<img src="'. esc_url( $image_1_src ) .'">';
			echo '</div>';
			
			// Image 2
			echo '<div class="crt-ba-image-2">';
				echo '<img src="'. esc_url( $image_2_src ) .'">';
			echo '</div>';

			// Divider
			echo '<div class="crt-ba-divider">';
				echo '<div class="crt-ba-divider-icons">';
					echo '<i class="fa '. esc_attr($settings['divider_icon'] .'-'. $icon_dir_first) .'"></i>';
					echo '<i class="fa '. esc_attr($settings['divider_icon'] .'-'. $icon_dir_second) .'"></i>';
				echo '</div>';
			echo '</div>';

			// Label 1
			if ( '' !== $settings['label_image_1'] ) {
				echo '<div class="crt-ba-label crt-ba-label-1">';
					echo '<div>'. esc_html($settings['label_image_1']) .'</div>';
				echo '</div>';
			}

			// Label 2
			if ( '' !== $settings['label_image_2'] ) {
				echo '<div class="crt-ba-label crt-ba-label-2">';
					echo '<div>'. esc_html($settings['label_image_2']) .'</div>';
				echo '</div>';
			}

		echo '</div>';

	}
	
}