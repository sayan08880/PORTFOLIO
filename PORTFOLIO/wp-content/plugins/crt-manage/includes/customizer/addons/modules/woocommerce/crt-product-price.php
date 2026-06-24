<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CRT_Product_Price extends Widget_Base {
	
	public function get_name() {
		return 'crt-product-price';
	}

	public function get_title() {
		return esc_html__( 'Product Price', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-product-price';
	}

	public function get_categories() {
        return ['crt_manage_woocommerce'];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-price', 'product', 'price' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_product_price',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
            'product_price_align',
            [
                'label' => esc_html__( 'Alignment', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'label_block' => false,
                'options' => [
					'left'    => [
						'title' => __( 'Left', 'crt-manage' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'crt-manage' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .crt-product-price' => 'text-align: {{VALUE}}',
				],
				'separator' => 'after'
            ]
        );

		$this->add_control(
			'product_price_tag',
			[
				'label' => esc_html__( 'Sale Price Display', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline' => esc_html__( 'Inline', 'crt-manage' ),
					'separate' => esc_html__( 'Separate', 'crt-manage' ),
				],
				'prefix_class' => 'crt-product-price-'
			]
		);

		$this->end_controls_section(); // End Controls Section


		// Styles ====================
		// Section: Price ------------
		$this->start_controls_section(
			'section_style_price',
			[
				'label' => esc_html__( 'Price', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'  => esc_html__( 'Normal Price Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-product-price' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .crt-product-price',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '25',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'price_sale_color',
			[
				'label'  => esc_html__( 'Sale Price Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#A3A3A3',
				'selectors' => [
					'{{WRAPPER}} .crt-product-price del' => 'color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_sale_typography',
				'selector' => '{{WRAPPER}} .crt-product-price del',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '18',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'price_sale_spacing',
			[
				'label' => __( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.crt-product-price-inline .crt-product-price ins' => 'margin-left: {{SIZE}}px',
					'{{WRAPPER}}.crt-product-price-separate .crt-product-price ins' => 'margin-top: {{SIZE}}px',
				],
			]
		);


	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		// Get Product
		$product = wc_get_product();

		if ( ! $product ) {
			return;
		}

		// Output
		echo '<div class="crt-product-price">';
			echo $product->get_price_html();
		echo '</div>';

	}
	
}