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

class CRT_Compare_Pro extends Widget_Base {
	
	public function get_name() {
		return 'crt-compare-pro';
	}

	public function get_title() {
		return esc_html__( 'Compare Table', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-exchange';
	}

	public function get_categories() {
        return [ 'crt_manage_woocommerce' ];
	}

	public function get_keywords() {
		return [ 'compare', 'table', 'grid' ];
	}

    public function get_script_depends() {
        return [ 'crt-compare-pro' ];
    }

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Settings ------------
		$this->start_controls_section(
			'section_compare_settings',
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
//				'separator' => 'after',
//				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
//			]
//		);

		$this->add_control(
			'remove_from_compare_text',
			[
				'label' => esc_html__( 'Remove Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Remove from Compare'
			]
		);

		$this->add_control(
			'compare_empty_text',
			[
				'label' => esc_html__( 'Empty Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'No Products in the Compare', 'crt-manage' ),
				'default' => esc_html__( 'No Products in the Compare', 'crt-manage' )
			]
		);

		// Add to Cart
		$this->add_control(
			'layout_list_media_section',
			[
				'label' => esc_html__( 'Add to Cart', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'element_addcart_simple_txt',
			[
				'label' => esc_html__( 'Simple Item Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Add to Cart'
			]
		);

		$this->add_control(
			'element_addcart_grouped_txt',
			[
				'label' => esc_html__( 'Grouped Item Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Select Options'
			]
		);

		$this->add_control(
			'element_addcart_variable_txt',
			[
				'label' => esc_html__( 'Variable Item Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'View Products',
				'separator' => 'after'
			]
		);

        $this->end_controls_section();
        
		// Tab: Style ==============
		// Section: General ------------
		$this->start_controls_section(
			'section_compare_styles_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'compare_table_border_style',
			[
				'label' => esc_html__('Border', 'crt-manage' ),
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
					// '{{WRAPPER}} .crt-compare-table' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-compare-products' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-compare-table th' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-compare-table td' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_table_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E4E4E4',
				'selectors' => [
					// '{{WRAPPER}} .crt-compare-table' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-compare-products' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-compare-table th' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-compare-table td' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'compare_table_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
					'unit' => 'px'
				],
				'selectors' => [
					// '{{WRAPPER}} .crt-compare-table' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-compare-products' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-compare-table th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-compare-table td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'compare_table_border_color!' => 'none',
				]
			]
		);

		$this->add_control(
			'compare_table_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 4,
					'right' => 4,
					'bottom' => 4,
					'left' => 4,
				],
				'selectors' => [
					'{{WRAPPER}} table tr:first-of-type th:first-of-type' => 'border-top-left-radius: {{TOP}}{{UNIT}} !important;',
					'{{WRAPPER}} table tr:first-of-type th:last-of-type' => 'border-top-right-radius: {{RIGHT}}{{UNIT}} !important;',
					'{{WRAPPER}} table tr:last-of-type th:first-of-type' => 'border-bottom-left-radius: {{BOTTOM}}{{UNIT}} !important;',
					'{{WRAPPER}} table tr:last-of-type td:last-of-type' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}} !important;',
					// '{{WRAPPER}} .crt-compare-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'
					'{{WRAPPER}} .crt-compare-products' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'
				]
			]
		);

        $this->end_controls_section();
		
		// Styles ====================
		// Section: Headings ------
		$this->start_controls_section(
			'section_style_headings',
			[
				'label' => esc_html__( 'Headings', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'table_headings_color',
			[
				'label'  => esc_html__( 'Headings Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-table tr th' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_headings_bg_color',
			[
				'label'  => esc_html__( 'Headings Bg Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-compare-table tr th:first-child' => 'background-color: {{VALUE}} !important'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'headings_typography',
				'selector' => '{{WRAPPER}}  .crt-compare-table tr th'
			]
		);

		$this->add_responsive_control(
			'headings_padding',
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
					'{{WRAPPER}} .crt-compare-table th:first-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'compare_headings_alignment_hr',
			[
				'label' => esc_html__( 'Horizontal Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'crt-manage' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-table th:first-child' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'compare_headings_alignment_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'top',
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
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-table th:first-child' => 'vertical-align: {{VALUE}};',
				]
			]
		);

        $this->end_controls_section();
		
		// Styles ====================
		// Section: Content ------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'table_content_odd_color',
			[
				'label'  => esc_html__( 'Color (Odd)', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-table tr:nth-child(odd) td' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_row_odd_bg_color',
			[
				'label'  => esc_html__( 'Bg Color (Odd)', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-table tr:nth-child(odd) th' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-compare-table tr:nth-child(odd) td' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_content_even_color',
			[
				'label'  => esc_html__( 'Content Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-table tr:nth-child(even) td' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_row_even_bg_color',
			[
				'label'  => esc_html__( 'Bg Color (Even)', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-table tr:nth-child(even) th' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-compare-table tr:nth-child(even) td' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}}  .crt-compare-table tr td'
			]
		);

		$this->add_responsive_control(
			'content_padding',
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
					'{{WRAPPER}} .crt-compare-table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-compare-table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'compare_content_alignment_hr',
			[
				'label' => esc_html__( 'Horizontal Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'crt-manage' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-table th' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-compare-table td' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->add_responsive_control(
			'compare_content_alignment_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'top',
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
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-table th' => 'vertical-align: {{VALUE}};',
					'{{WRAPPER}} .crt-compare-table td' => 'vertical-align: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'compare_product_name_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Product Title', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'compare_product_name_color',
			[
				'label'     => esc_html__( 'Color', 'crt-manage' ),
				'default' => '#787878',
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-compare-product-name' => 'color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'compare_product_image_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Product Image', 'crt-manage' ),
			]
		);

		$this->add_responsive_control(
			'product_image_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-img-wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		// Styles ====================
		// Section: Add to Cart ------
		$this->start_controls_section(
			'section_style_add_to_cart',
			[
				'label' => esc_html__( 'Add to Cart', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_add_to_cart_style' );

		$this->start_controls_tab(
			'tab_add_to_cart_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'add_to_cart_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-product-atc a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-compare-product-atc a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-product-atc a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow',
				'selector' => '{{WRAPPER}} .crt-compare-product-atc a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_add_to_cart_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'add_to_cart_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-product-atc a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-compare-product-atc a.crt-button-none:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-compare-product-atc a.added_to_cart:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-compare-product-atc a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-compare-product-atc a:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-compare-product-atc a:after' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-product-atc a:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow_hr',
				'selector' => '{{WRAPPER}} .crt-compare-product-atc :hover a',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'add_to_cart_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		// $this->add_control_add_to_cart_animation();

		$this->add_control(
			'add_to_cart_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-compare-product-atc a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-compare-product-atc a:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-compare-product-atc a:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		// $this->add_control_add_to_cart_animation_height();

		$this->add_control(
			'add_to_cart_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'add_to_cart_typography',
				'selector' => '{{WRAPPER}} .crt-compare-product-atc a'
			]
		);

		$this->add_control(
			'add_to_cart_border_type',
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
					'{{WRAPPER}} .crt-compare-product-atc a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'add_to_cart_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-product-atc a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'add_to_cart_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'add_to_cart_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 15,
					'bottom' => 5,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-product-atc a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'add_to_cart_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-product-atc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'add_to_cart_radius',
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
					'{{WRAPPER}} .crt-compare-product-atc a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
        
		// Tab: Style ==============
		// Section: Buttons ------------
		$this->start_controls_section(
			'section_compare_button_styles',
			[
				'label' => esc_html__( 'Remove Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'btn_styles' );

		$this->start_controls_tab(
			'tab_btn_normal_style',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' )
			]
		);

		$this->add_control(
			'table_button_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-remove' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_button_bg_color',
			[
				'label'  => esc_html__( 'Bg Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-remove' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_button_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-remove' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'selector' => '{{WRAPPER}} .crt-compare-remove'
			]
		);

        $this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_style',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' )
			]
		);

		$this->add_control(
			'table_button_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-remove:hover' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_button_bg_color_hr',
			[
				'label'  => esc_html__( 'Bg Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-remove:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_button_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-compare-remove:hover' => 'border-color: {{VALUE}}'
				]
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
					'top' => 5,
					'right' => 15,
					'bottom' => 5,
					'left' => 15,
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-compare-remove' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-compare-remove' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .crt-compare-remove' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-compare-remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();
    }

    public function render_product_rating($product) {

        // $rating_count = $product->get_rating_count();
		// $rating_amount = floatval( $product->get_average_rating() );
		// $round_rating = (int)$rating_amount;
        // $rating_icon = '&#9734;';

		// echo '<div class="crt-woo-rating">';

		// 	for ( $i = 1; $i <= 5; $i++ ) {
		// 		if ( $i <= $rating_amount ) {
		// 			echo '<i class="crt-rating-icon-full">'. $rating_icon .'</i>';
		// 		} elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) {
		// 			echo '<i class="crt-rating-icon-'. ( $rating_amount - $round_rating ) * 10 .'">'. $rating_icon .'</i>';
		// 		} else {
		// 			echo '<i class="crt-rating-icon-empty">'. $rating_icon .'</i>';
		// 		}
	    //  	}

		// echo '</div>';

		// Another option
		$rating  = $product->get_average_rating();
		$count   = $product->get_rating_count();
		echo wc_get_rating_html( $rating, $count );
	}
	
	// Render Add To Cart
	public function render_product_add_to_cart( $settings, $product ) {

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		ob_start();

		// Get Button Class
		$button_class = implode( ' ', array_filter( [
			'product_type_'. $product->get_type(),
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
		] ) );

		$attributes = [
			'rel="nofollow"',
			'class="'. esc_attr($button_class) .' crt-button-effect '. (!$product->is_in_stock() && 'simple' === $product->get_type() ? 'crt-atc-not-clickable' : '').'"',
			'aria-label="'. esc_attr($product->add_to_cart_description()) .'"',
			'data-product_id="'. esc_attr($product->get_id()) .'"',
			'data-product_sku="'. esc_attr($product->get_sku()) .'"',
		];

		$button_HTML = '';
		$page_id = get_queried_object_id();

		// Button Text
		if ( 'simple' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_simple_txt'];

			if ( 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {
				array_push( $attributes, 'href="'. esc_url( get_permalink( $page_id ) .'/?add-to-cart='. get_the_ID() ) .'"' );
			} else {
				array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
			}
		} elseif ( 'grouped' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_grouped_txt'];
			array_push( $attributes, 'href="'. esc_url( $product->get_permalink() ) .'"' );
		} elseif ( 'variable' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_variable_txt'];
			array_push( $attributes, 'href="'. esc_url( $product->get_permalink() ) .'"' );
		} else {
			array_push( $attributes, 'href="'. esc_url( $product->get_product_url() ) .'"' );
			$button_HTML .= get_post_meta( get_the_ID(), '_button_text', true ) ? get_post_meta( get_the_ID(), '_button_text', true ) : 'Buy Product';
		}

			// Button HTML
		echo '<a '. implode( ' ', $attributes ) .'><span>'. $button_HTML .'</span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		return \ob_get_clean();
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

		$this->add_render_attribute(
			'wrapper',
			[
				'class' => [ 'crt-compare-table-wrap' ],
				'remove_from_compare_text' => $settings['remove_from_compare_text'],
				'compare_empty_text' => $settings['compare_empty_text'],
				'element_addcart_simple_txt' => $settings['element_addcart_simple_txt'],
				'element_addcart_grouped_txt' => $settings['element_addcart_grouped_txt'],
				'element_addcart_variable_txt' => $settings['element_addcart_variable_txt']
			]
		);

		echo '<div '. $this->get_render_attribute_string( 'wrapper' ) .'>';
		 echo '<span class="crt-compare-placeholder">'. esc_html__('Loading...') .'</span>';
		echo '</div>';
    }
}