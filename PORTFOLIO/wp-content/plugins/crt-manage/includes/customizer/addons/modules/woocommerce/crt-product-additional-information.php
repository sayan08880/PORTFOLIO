<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CRT_Product_AdditionalInformation extends Widget_Base {
	
	public function get_name() {
		return 'crt-product-additional-information';
	}

	public function get_title() {
		return esc_html__( 'Product Additional Information', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-product-info';
	}

	public function get_categories() {
        return ['crt_manage_woocommerce'];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-additional-information', 'product', 'additional information', 'information' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}
	
	public function get_script_depends() {
		return [ 'wc-single-product' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'additional_info_syles',
			[
				'label' => esc_html__('Additional Information', 'crt-manage'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'additional_info_label',
			[
				'label'     => esc_html__('Attribute Name', 'crt-manage'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'additional_info_label_color',
			[
				'label'     => esc_html__('Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#888888',
				'selectors' => [
					'{{WRAPPER}} .crt-product-additional-information table th' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'additional_info_label_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8f8f8',
				'selectors' => [
					'{{WRAPPER}} .crt-product-additional-information table th' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'additional_info_label_even_bg_color',
			[
				'label'     => esc_html__('Even Background Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-product-additional-information table tr:nth-child(even) th' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'additional_info_th_typography',
				'label'          => esc_html__('Typography', 'crt-manage'),
				'selector'       => '{{WRAPPER}} .crt-product-additional-information tr :is(th)',
				'exclude'        => ['font_family', 'text_transform', 'text_decoration'],
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'crt-manage'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '16',
							'unit' => 'px',
						],
					],
					'font_weight'    => [
						'default' => '400',
					],
					'text_transform' => [
						'default' => 'none',
					],
					'line_height'     => [
						'label'      => esc_html__('Line Height (px)', 'crt-manage'),
						'default' => [
							'size' => '19',
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
					'letter_spacing' => [
						'label'      => esc_html__('Letter Spacing (px)', 'crt-manage'),
						'size_units' => ['px'],
					],
				],
			]
		);

		$this->add_control(
			'additional_info_th_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} .crt-product-additional-information table th' => 'text-align: {{VALUE}}',
				],
				'default' => 'left',
			]
		);

		$this->add_responsive_control(
			'additional_info_label_width',
			[
				'label'      => esc_html__('Width', 'crt-manage'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 25,
				],
				'selectors'  => [
					'{{WRAPPER}} .crt-product-additional-information table th' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'additional_info_value_heading',
			[
				'label'     => esc_html__('Attribute Value', 'crt-manage'),
				'type'      => Controls_Manager::HEADING,
                'separator'  => 'before',
			]
		);

		$this->add_control(
			'additional_information_value_color',
			[
				'label'     => esc_html__('Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#101010',
				'selectors' => [
					'{{WRAPPER}} .crt-product-additional-information table td p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'additional_information_value_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fdfdfd',
				'selectors' => [
					'{{WRAPPER}} .crt-product-additional-information table td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'additional_information_value_even_bg_color',
			[
				'label'     => esc_html__('Even Background Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-product-additional-information table tr:nth-child(even) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'additional_info_td_typography',
				'label'          => esc_html__('Typography', 'crt-manage'),
				'selector'       => '{{WRAPPER}} .crt-product-additional-information tr :is(td, p)',
				'exclude'        => ['font_family', 'text_transform', 'text_decoration'],
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'crt-manage'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '16',
							'unit' => 'px',
						],
					],
					'font_weight'    => [
						'default' => '400',
					],
					'text_transform' => [
						'default' => 'none',
					],
					'line_height'     => [
						'label'      => esc_html__('Line Height (px)', 'crt-manage'),
						'default' => [
							'size' => '19',
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
					'letter_spacing' => [
						'label'      => esc_html__('Letter Spacing (px)', 'crt-manage'),
						'size_units' => ['px'],
					],
				],
			]
		);

		$this->add_responsive_control(
			'additional_info_padding',
			[
				'label'      => esc_html__('Padding', 'crt-manage'),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'      => '15',
					'right'    => '35',
					'bottom'   => '15',
					'left'     => '35',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'separator' => 'before',
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}} .crt-product-additional-information table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-product-additional-information table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'additional_info_divider_color',
			[
				'label'     => esc_html__('Divider (Border) Color', 'crt-manage'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f2f2f2',
				'selectors' => [
					'{{WRAPPER}} .crt-product-additional-information table td' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-product-additional-information table th' => 'border-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'additional_info_border_width',
			[
				'label' => esc_html__( 'Divider (Border) Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-product-additional-information table tr:not(:last-child) td' => 'border-bottom-width: {{SIZE}}px; border-bottom-style: solid;',
					'{{WRAPPER}} .crt-product-additional-information table tr:not(:last-child) th' => 'border-bottom-width: {{SIZE}}px; border-bottom-style: solid;',
					'{{WRAPPER}}.crt-add-info-borders-yes .crt-product-additional-information table td' => 'border-width: {{SIZE}}px; border-style: solid;',
					'{{WRAPPER}}.crt-add-info-borders-yes .crt-product-additional-information table th' => 'border-width: {{SIZE}}px; border-style: solid;',
				],
			]
		);

		$this->add_control(
			'additional_info_show_borders',
			[
				'label' => esc_html__( 'Show Table Borders', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'crt-add-info-borders-'
			]
		);

		$this->end_controls_section();


    }

    protected function render() {
        global $product;

        $product = wc_get_product();
        if ( empty( $product ) ) {
            return;
        }

        setup_postdata( $product->get_id() );

        $heading = apply_filters( 'woocommerce_product_additional_information_heading', esc_html__( 'Additional information', 'crt-manage' ) );

        echo esc_html($heading);

        echo '<div class="crt-product-additional-information">';
		    do_action('woocommerce_product_additional_information', $product);
        echo '</div>';   
    }
}