<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CRT_Product_Rating extends Widget_Base {
	
	public function get_name() {
		return 'crt-product-rating';
	}

	public function get_title() {
		return esc_html__( 'Product Rating', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-product-rating';
	}

	public function get_categories() {
        return ['crt_manage_woocommerce'];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-rating', 'product', 'rating' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_rating',
			[
				'label' => esc_html__( 'Styles', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'product_rating_layout',
			[
				'label' => esc_html__( 'Layout', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'flex' => esc_html__('Horizontal', 'crt-manage'),
					'block' => esc_html__('Vertical', 'crt-manage'),
				],
                'prefix_class' => 'crt-product-rating-',
                'selectors' => [
                    '{{WRAPPER}} .crt-product-rating' => 'display: {{VALUE}}; align-items: center;'
                ],
				'default' => 'flex',
			]
		);

		$this->add_control(
			'product_rating_show_text',
			[
				'label' => esc_html__( 'Show Text', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'crt-pr-show-text-'
			]
		);

		$this->add_responsive_control(
			'product_rating_alignment',
			[
				'label'        => esc_html__('Alignment', 'crt-manage'),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'    => [
						'title' => esc_html__('Left', 'crt-manage'),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__('Center', 'crt-manage'),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__('Right', 'crt-manage'),
						'icon'  => 'eicon-text-align-right',
					]
				],
				'prefix_class' => 'crt-product-rating-',
				'default'      => 'left',
                'selectors' => [
                    '{{WRAPPER}}.crt-product-rating-block .crt-woo-rating' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}}.crt-product-rating-block .woocommerce-review-link' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}}.crt-product-rating-flex .crt-product-rating' => 'justify-content: {{VALUE}};'
                ],
				'separator'    => 'after',
			]
		);

		$this->add_control(
			'product_rating_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd726',
				'selectors' => [
					'{{WRAPPER}} .crt-woo-rating i:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_unmarked_color',
			[
				'label' => esc_html__( 'Unmarked Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#D2CDCD',
				'selectors' => [
					'{{WRAPPER}} .crt-woo-rating i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-woo-rating svg' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'product_rating_text_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} a.woocommerce-review-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_text_color_hover',
			[
				'label' => esc_html__( 'Text Hover Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} a.woocommerce-review-link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_rating_typography',
				'selector' => '{{WRAPPER}} .crt-product-rating .woocommerce-review-link',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '13',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'product_rating_tr_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-product-rating .woocommerce-review-link' => 'transition-duration: {{VALUE}}s;'
				],
			]
		);

		$this->add_control(
			'product_rating_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
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
					'{{WRAPPER}} .crt-woo-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-woo-rating svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_rating_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Icon Gutter', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-woo-rating i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-woo-rating span' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'product_rating_spacing',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Label Distance', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-product-rating-flex .crt-product-rating a.woocommerce-review-link' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-product-rating-block .crt-product-rating a.woocommerce-review-link' => 'margin-top: {{SIZE}}{{UNIT}}; display: block;',
				],
				'separator' => 'after'
			]
		);

        $this->end_controls_section();

    }
    
    public function render_product_rating( $settings ) {
		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

        $rating_count = $product->get_rating_count();
		$rating_amount = floatval( $product->get_average_rating() );
		$round_rating = (int)$rating_amount;
        $rating_icon = '&#9734;';

		echo '<div class="crt-woo-rating">';

			for ( $i = 1; $i <= 5; $i++ ) {
				if ( $i <= $rating_amount ) {
					echo '<i class="crt-rating-icon-full">'. $rating_icon .'</i>';
				} elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) {
					echo '<i class="crt-rating-icon-'. ( $rating_amount - $round_rating ) * 10 .'">'. $rating_icon .'</i>';
				} else {
					echo '<i class="crt-rating-icon-empty">'. $rating_icon .'</i>';
				}
	     	}

		echo '</div>';

		// Another option
		// $rating  = $product->get_average_rating();
		// $count   = $product->get_rating_count();
		// echo wc_get_rating_html( $rating, $count );

		?>

        <a href="#reviews" class="woocommerce-review-link" rel="nofollow">
            (<?php printf( _n( '%s customer review', '%s customer reviews', 10, 'crt-manage' ), '<span class="count">' . esc_html( $rating_count ) . '</span>' ); ?>)
        </a>

		<?php
	}

    protected function render() {
        // Get Settings
        $settings = $this->get_settings_for_display();
        global $product;

        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        setup_postdata( $product->get_id() );

        echo '<div class="crt-product-rating">';
            $this->render_product_rating($settings);
        echo '</div>';
    }
}