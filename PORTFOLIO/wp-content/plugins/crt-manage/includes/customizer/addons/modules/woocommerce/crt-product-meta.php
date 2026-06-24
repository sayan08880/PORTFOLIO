<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CRT_Product_Meta extends Widget_Base {
	
	public function get_name() {
		return 'crt-product-meta';
	}

	public function get_title() {
		return esc_html__( 'Product Meta', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-post-info';
	}

	public function get_categories() {
        return ['crt_manage_woocommerce'];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-meta', 'product', 'meta' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_product_meta_styles',
			[
				'label' => esc_html__( 'Styles', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'product_meta_layout',
			[
				'label' => esc_html__( 'Select Layout', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
				'options' => [
					'column' => [
						'title' => esc_html__( 'Vertical', 'crt-manage' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'row' => [
						'title' => esc_html__( 'Horizontal', 'crt-manage' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
                'prefix_class' => 'crt-product-meta-',
                'selectors' => [
                    '{{WRAPPER}} .crt-product-meta .product_meta' => 'display: flex; flex-direction: {{VALUE}};'
                ],
				'label_block' => false,
			]
		);

		$this->add_responsive_control(
			'meta_align',
			[
				'label'     => esc_html__('Alignment', 'crt-manage'),
				'type'      => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options'   => [
					'left'   => [
						'title' => esc_html__('Left', 'crt-manage'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'crt-manage'),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__('Right', 'crt-manage'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'crt-product-meta-',
				'selectors' => [
					'{{WRAPPER}} .crt-product-meta .product_meta' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'product_meta_gutter',
			[
				'label' => esc_html__( 'List Gutter', 'crt-manage' ),
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
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-product-meta-column .product_meta span:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-product-meta-row .product_meta span:not(last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'meta_label_title',
			[
				'label'     => esc_html__('Title', 'crt-manage'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_title_color',
			[
				'label'     => esc_html__('Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .crt-product-meta .product_meta :is(.sku_wrapper, .posted_in, .tagged_as)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_meta_value',
			[
				'label'     => esc_html__('Value', 'crt-manage'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_value_color',
			[
				'label'     => esc_html__('Value Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .crt-product-meta .product_meta :is(.sku, .posted_in a, .tagged_as a)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_value_link_hover_color',
			[
				'label'     => esc_html__('Link Hover Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e55b5b',
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .crt-product-meta .product_meta :is(.posted_in a, .tagged_as a):hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_meta_typography',
				'label' => esc_html__('Typography', 'crt-manage'),
				'selector' => '{{WRAPPER}} .crt-product-meta .product_meta :is(a, span, .sku_wrapper, .posted_in, .tagged_as)',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'       => [
						'label'      => esc_html__('Font Size (px)', 'crt-manage'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '13',
							'unit' => 'px',
						],
					],
					'font_weight'     => [
						'default' => '500',
					],
					'text_transform'  => [
						'default' => 'none',
					],
					'line_height'     => [
						'label'      => esc_html__('Line Height (px)', 'crt-manage'),
						'default' => [
							'size' => '17',
							'unit' => 'px',
						],
						'size_units' => ['px'],
						'tablet_default' => [
							'unit' => 'px',
						],
						'mobile_default' => [
							'unit' => 'px',
						],
					],
				],
			]
		);

		$this->add_control(
			'meta_sku_hide',
			[
				'label' => esc_html__('SKU', 'crt-manage'),
				'type' => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'crt-manage'),
				'label_off'    => esc_html__('Hide', 'crt-manage'),
				'default'      => "yes",
				'return_value' => "yes",
				'prefix_class' => 'crt-product-meta-sku-',
				'selectors'    => [
					'{{WRAPPER}}.crt-product-meta-column .crt-product-meta .sku_wrapper' => 'display: inline-block;',
					'{{WRAPPER}}.crt-product-meta-row .crt-product-meta .sku_wrapper'	=> 'display: inline-block;',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_category_hide',
			[ 
				'label' => esc_html__('Category', 'crt-manage'),
				'type' => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'crt-manage'),
				'label_off'    => esc_html__('Hide', 'crt-manage'),
				'default'      => "yes",
				'return_value' => "yes",
				'prefix_class' => 'crt-product-meta-cat-',
				'selectors'    => [
					'{{WRAPPER}}.crt-product-meta-column .crt-product-meta .posted_in' => 'display: inline-block;',
					'{{WRAPPER}}.crt-product-meta-row .crt-product-meta .posted_in'	=> 'display: inline-block;',
				],
			]
		);

		$this->add_control(
			'meta_tag_hide',
			[
				'label' => esc_html__('Tag', 'crt-manage'),
				'type' => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'crt-manage'),
				'label_off'    => esc_html__('Hide', 'crt-manage'),
				'default'      => "yes",
				'return_value' => "yes",
				'prefix_class' => 'crt-product-meta-tag-',
				'selectors'    => [
					'{{WRAPPER}}.crt-product-meta-column .crt-product-meta .tagged_as'							=> 'display: inline-block;',
					'{{WRAPPER}}.crt-product-meta-row .crt-product-meta .tagged_as'	=> 'display: inline-block;',
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

        echo '<div class="crt-product-meta">';
            woocommerce_template_single_meta();
        echo '</div>';
    }
}