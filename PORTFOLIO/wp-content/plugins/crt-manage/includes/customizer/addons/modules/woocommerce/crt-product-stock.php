<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CRT_Product_Stock extends Widget_Base {
	
	public function get_name() {
		return 'crt-product-stock';
	}

	public function get_title() {
		return esc_html__( 'Product Stock', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-product-stock';
	}

	public function get_categories() {
        return ['crt_manage_woocommerce'];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-stock', 'product', 'stock' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_stock',
			[
				'label' => esc_html__( 'Settings', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'in_stock_heading',
			[
				'label' => esc_html__( 'In Stock', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'in_stock_availability_text',
			[
				'label' => esc_html__( 'Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'In Stock', 'crt-manage' ),
				'default' => esc_html__( 'In Stock', 'crt-manage' ),
			]
		);

		$this->add_control(
			'product_in_stock_icon',
			[
				'label'   => esc_html__('Select Icon', 'crt-manage'),
				'type'    => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
				'default' => [
					'value'   => 'fas fa-check-circle',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'product_in_stock_color',
			[
				'label'     => esc_html__('Icon Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-product-stock .in-stock i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-product-stock .in-stock svg' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'product_in_stock_text_color',
			[
				'label'     => esc_html__('Text Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-product-stock .in-stock' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'out_of_stock_heading',
			[
				'label' => esc_html__( 'Out Of Stock', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_out_of_stock_icon',
			[
				'label'   => esc_html__('Select Icon', 'crt-manage'),
				'type'    => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
				'default' => [
					'value'   => 'fas fa-times-circle',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'out_of_stock_availability_text',
			[
				'label' => esc_html__( 'Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Out of Stock', 'crt-manage' ),
				'default' => esc_html__( 'Out of Stock', 'crt-manage' ),
			]
		);

		$this->add_control(
			'product_out_of_stock_color',
			[
				'label'     => esc_html__('Icon Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-product-stock .out-of-stock i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-product-stock .out-of-stock svg' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'product_out_of_stock_text_color',
			[
				'label'     => esc_html__('Text Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-product-stock .out-of-stock' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'backorder_heading',
			[
				'label' => esc_html__( 'Available On Backorder', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'backorder_availability_text',
			[
				'label' => esc_html__( 'Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'On Backorder', 'crt-manage' ),
				'default' => esc_html__( 'On Backorder', 'crt-manage' ),
			]
		);

		$this->add_control(
			'product_available_on_backorder_icon',
			[
				'label'   => esc_html__('Select Icon', 'crt-manage'),
				'type'    => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
				'default' => [
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'product_available_on_backorder_color',
			[
				'label'     => esc_html__('Icon Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF4F40',
				'selectors' => [
					'{{WRAPPER}} .crt-product-stock .available-on-backorder i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-product-stock .available-on-backorder svg' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'product_available_on_backorder_text_color',
			[
				'label'     => esc_html__('Text Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-product-stock .available-on-backorder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_svg_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'SVG Size', 'crt-manage' ),
				'size_units' => [ 'px' ],
                'separator' => 'before',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-product-stock svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
				]
			]
		);
		
		$this->add_control(
			'product_stock_vertical_align',
			[
				'label' => esc_html__( 'Vertical Alignment', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => esc_html__( 'Top', 'crt-manage' ),
					'middle' => esc_html__( 'Middle', 'crt-manage' ),
					'bottom' => esc_html__( 'Bottom', 'crt-manage' ),
					'text-top' => esc_html__( 'Text Top', 'crt-manage' ),
					'text-bottom' => esc_html__( 'Text Bottom', 'crt-manage' ),
				],
				'default' => 'text-bottom',
				'selectors' => [
					'{{WRAPPER}} .crt-product-stock-icon' => 'vertical-align: {{VALUE}};',
					'{{WRAPPER}} .crt-product-stock-icon svg' => 'vertical-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_icon_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Spacing', 'crt-manage' ),
				'size_units' => [ 'px' ],
                'separator' => 'before',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-product-stock .in-stock i' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .crt-product-stock .out-of-stock i' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .crt-product-stock .available-on-backorder i' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .crt-product-stock svg' => 'margin-right: {{SIZE}}px;',
				]
			]
		);

		$this->add_responsive_control(
			'product_stock_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
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
					'{{WRAPPER}} .crt-product-stock p' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_stock_typography',
				'label' => esc_html__('Typography', 'crt-manage'),
				'selector' => '{{WRAPPER}} .crt-product-stock p',
				'fields_options' => [
					'typography'     => [
						'default' => 'custom',
					],
					'font_family'    => [
						'default' => '',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'crt-manage'),
						'default' => [
							'size' => '13',
							'unit' => 'px'
						],
						'size_units' => ['px'],
					],
				],
            ]
		);

        $this->end_controls_section();

    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
		
        global $product;

        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        setup_postdata( $product->get_id() );

        $icon = '';
        $stock_status = $product->get_stock_status();
        $availability = $product->get_availability();

        if ( 'instock' == $stock_status ) {
            $icon = isset($settings['product_in_stock_icon']) ? $settings['product_in_stock_icon'] : '';
        } elseif ( 'outofstock' == $stock_status ) {
            $icon = isset($settings['product_out_of_stock_icon']) ? $settings['product_out_of_stock_icon'] : '';
        } elseif ( 'onbackorder' == $stock_status ) {
            $icon = isset($settings['product_available_on_backorder_icon']) ? $settings['product_available_on_backorder_icon'] : '';
        }

		if ( $product->is_on_backorder() ) {
			$stock_html = $availability['availability'] ? $availability['availability'] : esc_html__($settings['backorder_availability_text'], 'crt-manage');
		} elseif ( $product->is_in_stock() ) {
			$stock_html = $availability['availability'] ? $availability['availability'] : esc_html__($settings['in_stock_availability_text'], 'crt-manage');
		} else {
			$stock_html = $availability['availability'] ? $availability['availability'] : esc_html__($settings['out_of_stock_availability_text'], 'crt-manage');
		}

        echo '<div class="crt-product-stock">';
            echo '<p class="' . esc_attr($availability['class']) . '">';

            if(!empty($icon)) {
				if ( \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
					$icon_html = ob_get_clean();

					echo '<span class="crt-product-stock-icon">';
						echo $icon_html;
					echo '</span>';
				} else {
					\Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
				}
            }

            echo apply_filters( 'woocommerce_stock_html', $stock_html, wp_kses_post($availability['availability']), $product );

			echo '</p>';
        echo '</div>';
    }
}