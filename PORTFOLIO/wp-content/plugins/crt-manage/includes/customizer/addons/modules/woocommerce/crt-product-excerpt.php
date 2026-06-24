<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CRT_Product_Excerpt extends Widget_Base {
	
	public function get_name() {
		return 'crt-product-excerpt';
	}

	public function get_title() {
		return esc_html__( 'Product Excerpt', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-post-excerpt';
	}

	public function get_categories() {
        return ['crt_manage_woocommerce'];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-excerpt', 'product', 'excerpt' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_product_excerpt',
			[
				'label' => esc_html__( 'Styles', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => esc_html__('Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .crt-product-excerpt p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-product-excerpt li' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-product-excerpt a' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'excerpt_typography',
				'label'          => esc_html__('Typography', 'crt-manage'),
				'selector'       => '{{WRAPPER}} .crt-product-excerpt p, {{WRAPPER}} .crt-product-excerpt li, {{WRAPPER}} .crt-product-excerpt a',
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
			'excerpt_align',
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
					'{{WRAPPER}} .crt-product-excerpt p' => 'text-align: {{VALUE}}',
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

        $post = get_post( $product->get_id() );
        setup_postdata( $product->get_id() );

		$product_excerpt = apply_filters('woocommerce_short_description', $post->post_excerpt);

        echo '<div class="crt-product-excerpt">';
            echo $product_excerpt;
        echo '</div>';
    }
}