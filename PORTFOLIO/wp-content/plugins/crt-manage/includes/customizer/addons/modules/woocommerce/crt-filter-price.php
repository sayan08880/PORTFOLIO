<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Filter_Price extends Widget_Base {

	public function get_name() {
		return 'crt-filter-price';
	}

	public function get_title() {
		return esc_html__( 'Price Filter', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-price-list';
	}

	public function get_categories() {
		return [ 'crt_manage_woocommerce' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'filter', 'price', 'range', 'product' ];
	}

	public function get_script_depends() {
		return [ 'jquery-ui-slider', 'crt-filter-price' ];
	}

	public function get_style_depends() {
		return [ 'crt-filter-price' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_price_filter',
			[
				'label' => esc_html__( 'Price Filter', 'crt-manage' ),
			]
		);

		$this->add_control(
			'filter_label',
			[
				'label' => esc_html__( 'Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Price', 'crt-manage' ),
			]
		);

        $this->add_control(
			'show_reset_button',
			[
				'label' => esc_html__( 'Show Reset Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_price_filter',
			[
				'label' => esc_html__( 'Style', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_label_style',
			[
				'label' => esc_html__( 'Label', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_responsive_control(
            'filter_price_heading_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-price-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'filter_price_heading_typo',
                'selector' => '{{WRAPPER}} .crt-filter-price-label',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '16',
                            'unit' => 'px',
                        ],
                    ],
                    'letter_spacing' => [
                        'default' => [
                            'size' => '1.1'
                        ]
                    ],
                ]
            ]
        );

        $this->add_control(
            'filter_price_heading_color',
            [
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-price-label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'filter_price_heading_background_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-price-label' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'filter_price_heading_border_type',
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
                    '{{WRAPPER}} .crt-filter-price-label' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filter_price_heading_border_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e8e8e8',
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-price-label' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_price_heading_border_width',
            [
                'label' => esc_html__( 'Border Width', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-price-label' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'filter_price_heading_border_type!' => 'none',
                ],
            ]
        );


        $this->add_control(
            'heading_ranger_style',
            [
                'label' => esc_html__( 'Ranger', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filter_price_ranger_background_inner_color',
            [
                'label' => esc_html__( 'Ranger BG Color Inner', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ui-slider .ui-slider-range' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'filter_price_ranger_background_outer_color',
            [
                'label' => esc_html__( 'Ranger BG Color Outer', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-price-slider' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'filter_price_ranger_height_outer',
            [
                'label' => esc_html__( 'Ranger Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 15,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-price-slider' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filter_price_ranger_border_radius',
            [
                'label' => esc_html__( 'Ranger Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-price-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_ranger_icon_style',
            [
                'label' => esc_html__( 'Ranger Icon', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filter_price_ranger_icon_width',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px' ],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ui-slider .ui-slider-handle' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ui-slider-horizontal .ui-slider-handle:nth-child(3)' => 'margin-left: -{{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filter_price_ranger_icon_height',
            [
                'label' => esc_html__( 'Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px' ],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ui-slider .ui-slider-handle' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filter_price_ranger_background_icon',
            [
                'label' => esc_html__( 'Ranger BG Color Icon', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ui-slider .ui-slider-handle' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'filter_price_ranger_icon',
            [
                'label' => esc_html__( 'Ranger Icon Border', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'solid' => esc_html__( 'Solid', 'crt-manage' ),
                ],
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .ui-slider .ui-slider-handle' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filter_price_ranger_icon_border_color',
            [
                'label' => esc_html__( 'Ranger Icon Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e8e8e8',
                'selectors' => [
                    '{{WRAPPER}} .ui-slider .ui-slider-handle' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'filter_price_ranger_icon!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'filter_price_ranger_icon_border_width',
            [
                'label' => esc_html__( 'Border Width', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ui-slider .ui-slider-handle' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'filter_price_ranger_icon!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'filter_price_ranger_icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 30,
                    'right' => 30,
                    'bottom' => 30,
                    'left' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ui-slider .ui-slider-handle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );




		// Add more style controls as needed for the slider/inputs

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_buttons',
			[
				'label' => esc_html__( 'Buttons', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_buttons_style' );

		$this->start_controls_tab(
			'tab_buttons_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} form button[type="submit"]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-filter-reset-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} form button[type="submit"]' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-filter-reset-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} form button[type="submit"], {{WRAPPER}} .crt-filter-reset-btn',
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_buttons_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} form button[type="submit"]:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-filter-reset-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} form button[type="submit"]:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-filter-reset-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_box_shadow',
                'selector' => '{{WRAPPER}} form button[type="submit"]:hover, {{WRAPPER}} .crt-filter-reset-btn:hover',
            ]
        );

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} form button[type="submit"]:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-filter-reset-btn:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} form button[type="submit"], {{WRAPPER}} .crt-filter-reset-btn',
                'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} form button[type="submit"], {{WRAPPER}} .crt-filter-reset-btn',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} form button[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-filter-reset-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} form button[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-filter-reset-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} form button[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-filter-reset-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Check if we are on a WooCommerce archive or shop page ideally, 
        // but for now just output HTML structure.
		// Logic to get min/max price from DB or query
        global $wpdb;
//        print_r($wpdb->wc_product_meta_lookup);die;
        $sql = "SELECT min(min_price) as min_price, max(max_price) as max_price FROM {$wpdb->wc_product_meta_lookup} WHERE product_id IN (SELECT ID FROM {$wpdb->posts} WHERE post_type = 'product' AND post_status = 'publish')";
        $price_result = $wpdb->get_row($sql);
        $min_price = floor($price_result->min_price);
        $max_price = ceil($price_result->max_price);
        
        // Current values from URL
        $current_min = isset($_GET['filter_price']) ? explode('-', $_GET['filter_price'])[0] : $min_price;
        $current_max = isset($_GET['filter_price']) ? explode('-', $_GET['filter_price'])[1] : $max_price;
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		?>
		<div class="crt-filter-price-widget">
			<?php if ( ! empty( $settings['filter_label'] ) ) : ?>
				<h4 class="crt-filter-price-label"><?php echo esc_html( $settings['filter_label'] ); ?></h4>
			<?php endif; ?>

                <div class="crt-price-filter-wrapper" data-min="<?php echo esc_attr($min_price); ?>" data-max="<?php echo esc_attr($max_price); ?>">
                    <div class="crt-price-slider"></div>
                    <form method="get">
                        <input type="text" class="crt-price-min" name="min_price_display" value="<?php echo esc_attr($current_min); ?>" placeholder="<?php echo esc_attr($min_price); ?>" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>">
                        <input type="text" class="crt-price-max" name="max_price_display" value="<?php echo esc_attr($current_max); ?>" placeholder="<?php echo esc_attr($max_price); ?>" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>">
                        <input type="hidden" name="filter_price" class="crt-af-price-value" value="<?php echo esc_attr($current_min . '-' . $current_max); ?>">
                        <button type="submit"><?php echo esc_html__( 'Filter', 'crt-manage' ); ?></button>
                        <?php if ( 'yes' === $settings['show_reset_button'] && isset($_GET['filter_price']) || $is_editor ) : ?>
                            <button type="button" class="crt-filter-reset-btn"><?php echo esc_html__( 'Reset', 'crt-manage' ); ?></button>
                        <?php endif; ?>
                    </form>
                </div>
		</div>
		<?php
	}
}
