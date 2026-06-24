<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CRT_Product_Description extends Widget_Base {
	
	public function get_name() {
		return 'crt-product-description';
	}

	public function get_title() {
		return esc_html__( 'Product Description', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-product-description';
	}

	public function get_categories() {
        return ['crt_manage_woocommerce'];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-description', 'product', 'description' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_product_description',
			[
				'label' => esc_html__( 'Styles', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_html',
			[
				'label' => esc_html__( 'Render as HTML', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__('Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .crt-product-description p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-product-description li' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-product-description a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-product-description pre' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'description_typography',
				'label'          => esc_html__('Typography', 'crt-manage'),
				'selector'       => '{{WRAPPER}} .crt-product-description p, {{WRAPPER}} .crt-product-description li, {{WRAPPER}} .crt-product-description a, {{WRAPPER}} pre',
				'exclude'        => ['text_decoration'],
				'fields_options' => [
					'typography'     => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px'
						],
						'label'      => 'Font size (px)',
						'size_units' => ['px'],
					],
				],
			)
		);

		$this->add_control(
			'description_align',
			[
				'label'     => esc_html__('Alignment', 'crt-manage'),
				'type'      => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options'   => [
					'left'   => [
						'title' => esc_html__('Left', ''),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', ''),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__('Right', ''),
						'icon'  => 'eicon-text-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .crt-product-description p' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .crt-product-description pre' => 'text-align: {{VALUE}}'
				],
			]
		);

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
		
        global $product;

        if ( empty( $product ) ) {
            return;
        }
        
        $post = get_post( $product->get_id() );
        setup_postdata( $product->get_id() );
        
        if ($post) {
            // Get the product description
			if ( 'yes' === $settings['description_html'] ) {
				$description = '<pre>' . $product->get_description() . '</pre>';
			} else {
				$description = $product->get_description();
			}
        
            // Print the description
            echo '<div class="crt-product-description">';
                echo '<p>'. $description .'</p>';
            echo '</div>';
        } else {
            echo '<div class="crt-product-description">';
                echo '<p>'. esc_html__('Product not found', 'crt-manage') .'</p>';
            echo '</div>';
        }
    }
}