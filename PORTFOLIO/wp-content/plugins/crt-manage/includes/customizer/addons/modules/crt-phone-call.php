<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Phone_Call extends Widget_Base {
			
	public function get_name() {
		return 'crt-phone-call';
	}

	public function get_title() {
		return esc_html__( 'Phone Call', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-tel-field';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'Phone', 'call','phone','Phone call' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://crthemes.com/contact/?ref=rea-plugin-panel-phone-call-help-btn';
    		return 'https://crthemes.com/contact';
    }

	protected function register_controls() {

		// Section: General ----------
	$this->start_controls_section(
		'section_general',
		[
			'label' => esc_html__( 'General', 'crt-manage' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);

	$this->add_control(
		'telnumber',
		[
			'label' => esc_html__( 'Phone Number', 'crt-manage' ),
			'type' => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
			'default' => '123456789',
		]
	);
	$this->add_control(
		'button_position',
		[
			'label' => esc_html__( 'Button Position', 'crt-manage' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'fixed',
			'label_block' => false,
			'render_type' => 'template',
			'options' => [
				'fixed' => esc_html__( 'Fixed', 'crt-manage' ),
				'inline' => esc_html__( 'Inline', 'crt-manage' ),
				],
			'prefix_class' => 'crt-pc-btn-align-',
			'separator' => 'before',
		]
	);

	$this->add_responsive_control(
		'button_position_inline',
		[
			'label' => esc_html__( 'Inline Position', 'crt-manage' ),
			'type' => Controls_Manager::CHOOSE,
			'default' => 'center',
			'label_block' => false,
			'options' => [
				'flex-start' => [
					'title' => esc_html__( 'Left', 'crt-manage' ),
					'icon' => 'eicon-h-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'crt-manage' ),
					'icon' => 'eicon-h-align-center',
				],
				'flex-end' => [
					'title' => esc_html__( 'Right', 'crt-manage' ),
					'icon' => 'eicon-h-align-right',
				],
			],
			'selectors' => [
				'{{WRAPPER}} .crt-pc-wrapper' => 'justify-content: {{VALUE}}',
			],

			'separator' => 'before',
			'condition' => [
				'button_position' => 'inline',
			],
		]
	);
	
	$this->add_responsive_control(
		'button_position_fixed',
		[
			'label' => esc_html__( 'Fixed Position', 'crt-manage' ),
			'type' => Controls_Manager::CHOOSE,
			'default' => 'right',
			'label_block' => false,
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
			'separator' => 'before',
			'condition' => [
				'button_position' => 'fixed',
			],
			'prefix_class' => 'crt-pc-btn-align-fixed-',
		]
	);
	$this->add_responsive_control(
		'distance_x_right',
		[
			'label' => esc_html__( 'Distance Right', 'crt-manage' ),
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
				'size' => 30,
			],
			'selectors' => [
				'{{WRAPPER}}.crt-pc-btn-align-fixed-right .crt-pc-btn' => 'right: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'button_position' => 'fixed',
				'button_position_fixed' => 'right',
			],
		]
	);
	$this->add_responsive_control(
		'distance_y_right',
		[
			'label' => esc_html__( 'Distance Bottom', 'crt-manage' ),
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
				'size' => 30,
			],
			'selectors' => [
				'{{WRAPPER}}.crt-pc-btn-align-fixed-right .crt-pc-btn' => 'bottom: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'button_position' => 'fixed',
				'button_position_fixed' => 'right',
			],
		]
	);

	$this->add_responsive_control(
		'distance_x_left',
		[
			'label' => esc_html__( 'Distance Left', 'crt-manage' ),
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
				'size' => 30,
			],
			'selectors' => [
				'{{WRAPPER}}.crt-pc-btn-align-fixed-left .crt-pc-btn' => 'left: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'button_position' => 'fixed',
				'button_position_fixed' => 'left',
			],
		]
	);

	$this->add_responsive_control(
		'distance_y_left',
		[
			'label' => esc_html__( 'Distance Bottom', 'crt-manage' ),
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
				'size' => 30,
			],
			'selectors' => [
				'{{WRAPPER}}.crt-pc-btn-align-fixed-left .crt-pc-btn' => 'bottom: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'button_position' => 'fixed',
				'button_position_fixed' => 'left',
			],
		]
	);

	$this->add_control(
		'pc_icon',
		[
			'label' => esc_html__( 'Select Icon', 'crt-manage' ),
			'type' => Controls_Manager::ICONS,
			'skin' => 'inline',
			'label_block' => false,
			'default' => [
				'value' => 'fas fa-phone',
				'library' => 'fa-solid',
			],
			'separator' => 'before',
			'recommended' => [
				'fa-solid' => [
					'phone',
					'phone-alt',
					'search',
				],
			],
		]
	);

	$this->add_control(
			'button_txt_show',
			[
				'label' => __( 'Button Text', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'crt-manage' ),
				'label_off' => __( 'Hide', 'crt-manage' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
	);

	$this->add_control(
		'pc_icon_layout_select',
		[
			'label' => esc_html__( 'Icon Align', 'crt-manage' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'right',
			'label_block' => false,
			'options' => [
				'top' => esc_html__( 'Top', 'crt-manage' ),
				'bottom' => esc_html__( 'Bottom', 'crt-manage' ),
				'right' => esc_html__( 'Right', 'crt-manage' ),
				'left' => esc_html__( 'Left', 'crt-manage' ),
			],
			'condition' => [
				'pc_icon[value]!' => '',
				'button_txt_show' => 'yes',
			],
			'prefix_class' => 'crt-pc-btn-icon-',
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
			'default' => 'Call Now',
			'condition' => [
				'button_txt_show' => 'yes',
			],
		]
	);

	$this->end_controls_section();

	// Section: Request New Feature
	Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

	// Section Button ------------
	$this->start_controls_section(
		'section_style_button',
		[
			'label' => esc_html__( 'Button', 'crt-manage' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);


	$this->start_controls_tabs( 'tabs_pc_button_colors' );

	// Normal Tab ------------
	$this->start_controls_tab(
		'tab_pc_button_normal_colors',
		[
			'label' => esc_html__( 'Normal', 'crt-manage' ),
		]
	);

	$this->add_control(
		'button_text_color',
		[
			'label' => esc_html__( ' Color', 'crt-manage' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .crt-pc-content' => 'color: {{VALUE}}',
				'{{WRAPPER}} .crt-pc-btn-icon' => 'color: {{VALUE}}',
				'{{WRAPPER}} .crt-pc-btn-icon svg' => 'fill: {{VALUE}}',
			],
		]
	);
	
	$this->add_control(
		'button_bg_color',
		[
			'label' => esc_html__( 'Background', 'crt-manage' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#39B54A',
			'selectors' => [
				'{{WRAPPER}} .crt-pc-btn' => 'background-color: {{VALUE}}',
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
				'{{WRAPPER}} .crt-pc-btn' => 'border-color: {{VALUE}}',
			],
			'condition' =>[
				'border_switcher' => 'yes',
			],
		]
	);

	$this->add_group_control(
		\Elementor\Group_Control_Box_Shadow::get_type(),
		[
			'name' => 'button_box_shadow',
			'label' => __( 'Box Shadow', 'crt-manage' ),
			'selector' => '{{WRAPPER}} .crt-pc-btn',
		]
	);

	$this->end_controls_tab();// normal tab end

	// Hover Tab -------------
	$this->start_controls_tab(
		'tab_button_hover_colors',
		[
			'label' => esc_html__( 'Hover', 'crt-manage' ),
		]
	);

	$this->add_control(
		'button_texte_color_hover',
		[
			'label' => esc_html__( 'Color', 'crt-manage' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#fff',
			'selectors' => [
				'{{WRAPPER}} .crt-pc-btn:hover > .crt-pc-btn-icon' => 'Color: {{VALUE}}',
				'{{WRAPPER}} .crt-pc-btn:hover svg' => 'fill: {{VALUE}}',
			],
		]
	);

	$this->add_control(
		'button_bg_color_hover',
		[
			'label' => esc_html__( 'Background', 'crt-manage' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#1FD538',
			'selectors' => [
				'{{WRAPPER}} .crt-pc-btn:hover' => 'background-color: {{VALUE}}',
			],
		]
	);

	$this->add_control(
		'button_border_color_hover',
		[
			'label' => esc_html__( 'Border Color', 'crt-manage' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#E8E8E8',
			'selectors' => [
				'{{WRAPPER}} .crt-pc-btn:hover' => 'border-color: {{VALUE}}',
			],
			'condition' =>[
				'border_switcher' => 'yes',
			],
		]
	);

	$this->add_group_control(
		\Elementor\Group_Control_Box_Shadow::get_type(),
		[
			'name' => 'button_box_shadow_hover',
			'label' => __( 'Box Shadow', 'crt-manage' ),
			'selector' => '{{WRAPPER}} .crt-pc-btn:hover',
		]
	);

	$this->end_controls_tab();// hover tab end

	$this->end_controls_tabs();// End tabs

	$this->add_control(
			'hover_animation_hover_duration',
			[
				'label' => __( 'Hover Animation Duration', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'default' => 0.3,
				'selectors' => [
					'{{WRAPPER}} .crt-pc-btn' => 'transition:  all  {{VALUE}}s ease-in-out 0s;',
				],
				'separator' => 'before',
			]
		);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'button_typography',
			'selector' => '{{WRAPPER}} .crt-pc-content,{{WRAPPER}} .crt-pc-content::after',
			'separator' => 'before',
			'condition' => [
				'button_txt_show' => 'yes',
			],
		]
	);

	$this->add_control(
		'icon_size',
		[
			'label' => esc_html__( 'Icon Size', 'crt-manage' ),
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
				'size' => 13,
			],
			'selectors' => [
				'{{WRAPPER}} .crt-pc-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .crt-pc-btn-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
			],
			'separator' => 'before',
			'condition' => [
				'pc_icon[value]!' => '',
			],
		]
	);

	$this->add_control(
		'pc_icon_distance',
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
				'size' => 7,
			],
			'selectors' => [
				'{{WRAPPER}}.crt-pc-btn-icon-top .crt-pc-btn-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}}.crt-pc-btn-icon-left .crt-pc-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}}.crt-pc-btn-icon-right .crt-pc-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}}.crt-pc-btn-icon-bottom .crt-pc-btn-icon' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
			'separator' => 'before',
			'condition' => [
				'pc_icon[value]!' => '',
				'button_txt_show' => 'yes',
			],
		]
	);

	$this->add_responsive_control(
		'button_padding',
		[
			'label' => esc_html__( 'Padding', 'crt-manage' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px' ],
			'default' => [
				'top' => 13,
				'right' => 14,
				'bottom' => 13,
				'left' => 14,
			],
			'selectors' => [
				'{{WRAPPER}} .crt-pc-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'separator' => 'before',
		]
	);

	$this->add_control(
		'border_switcher',
		[
			'label' => esc_html__( 'Border', 'crt-manage' ),
			'type' => Controls_Manager::SWITCHER,
			'separator' => 'before',
			'return_value' => 'yes',
			'label_block' => false,
			'default' => 'yes',

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
				'{{WRAPPER}} .crt-pc-btn' => 'border-style: {{VALUE}};',
			],
			'condition' =>[
				'border_switcher' => 'yes',
			],
		]
	);

	$this->add_responsive_control(
		'border_width',
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
				'{{WRAPPER}} .crt-pc-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'separator' => 'before',
			'condition' =>[
					'border_switcher' => 'yes',
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
					'{{WRAPPER}} .crt-pc-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
	);

	$this->end_controls_section();

	}

	protected function render() {
	// Get Settings
	$settings = $this->get_settings();

		echo '<div class="crt-pc-wrapper">';
			echo '<a href="tel:'. esc_attr($settings['telnumber']) .'" class="crt-pc-btn">';
				echo '<div class="crt-pc-content">';
				if ( '' !== $settings['button_text'] && $settings['button_txt_show'] == 'yes' ) {
					echo '<span class="crt-pc-text">'. esc_html($settings ['button_text']) .'</span>';
				}
				if ( '' !== $settings['pc_icon']['value'] ) {
					echo '<span class="crt-pc-btn-icon">';
			 			\Elementor\Icons_Manager::render_icon( $settings['pc_icon'] );
					echo '</span>';
				}
				echo '</div>';
			echo '</a>';
		echo '</div>';


	}

}