<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Search extends Widget_Base {
		
	public function get_name() {
		return 'crt-search';
	}

	public function get_title() {
		return esc_html__( 'Search (AJAX)', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-site-search';
	}

	public function get_categories() {
        return [ 'crt_manage_header_elements'];
	}

	public function get_keywords() {
		return [ 'search', 'search widget', 'ajax search' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_style_depends() {
        return [ 'crt-animations-css', 'crt-link-animations-css', 'crt-button-animations-css', 'crt-loading-animations-css', 'crt-lightgallery-css' ];
	}

    public function get_script_depends() {
        return [ 'crt-search' ];
    }

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) ) {
            return 'https://crthemes.com/contact';
        }
    }

	public function add_section_style_ajax() {
		$this->start_controls_section(
			'section_style_ajax',
			[
				'label' => esc_html__( 'Ajax', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'ajax_search' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_list',
			[
				'label' => esc_html__( 'Search List', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color_hover',
			[
				'label' => esc_html__( 'Background Color (Hover)', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F6F6F6',
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch ul li:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ajax_box_shadow',
				'selector' => '{{WRAPPER}} .crt-data-fetch'
			]
		);

		$this->add_control(
			'search_list_item_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch ul li' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_responsive_control(
            'slider_content_hr',
            [
                'label' => esc_html__( 'Horizontal Position', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'crt-manage' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'crt-manage' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'crt-manage' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors_dictionary' => [
					'left' => 'left: 0; right: auto;',
					'center' => 'left: 50%; transform: translateX(-50%)',
					'right' => 'right: 0; left: auto;'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch' => '{{VALUE}};',
				],
				'separator' => 'before'
            ]
        );

		$this->add_responsive_control(
			'search_list_width',
			[
				'label' => esc_html__( 'Container Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vw'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
					'%' => [
						'min' => 50,
						'max' => 200,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'search_list_max_height',
			[
				'label' => esc_html__( 'Max Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'vh',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch ul' => 'max-height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'search_list_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'vh' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch' => 'margin-top: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'list_item_title',
			[
				'label' => esc_html__( 'List Item', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'search_list_item_bottom_distance',
			[
				'label' => esc_html__( 'Bottom Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'vh' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch ul li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'search_list_item_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch a.crt-ajax-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .crt-data-fetch a.crt-ajax-title',
			]
		);

		$this->add_responsive_control(
			'title_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ajax-search-content a.crt-ajax-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label' => esc_html__( 'Description', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#757575',
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch p a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-search-admin-notice' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .crt-data-fetch p a, {{WRAPPER}} .crt-search-admin-notice',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'       => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				],
			]
		);

		$this->add_responsive_control(
			'description_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-ajax-search-content p.crt-ajax-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'heading_image',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_ajax_thumbnails' => 'yes'
				]
			]
		);

		// $this->add_control_ajax_search_img_size();

		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch a.crt-ajax-img-wrap' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-data-fetch .crt-ajax-search-content' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'show_ajax_thumbnails' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'image_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch a.crt-ajax-img-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_ajax_thumbnails' => 'yes'
				]
			]
		);

		$this->add_control(
			'view_result_text_heading',
			[
				'label' => esc_html__( 'View Result', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'view_result_text_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} a.crt-view-result' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_result_text_color_hr',
			[
				'label' => esc_html__( 'Color (Hover)', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} a.crt-view-result:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_result_text_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} a.crt-view-result' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_result_text_bg_color_hr',
			[
				'label' => esc_html__( 'Background Color (Hover)', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} a.crt-view-result:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'view_result_typography',
				'selector' => '{{WRAPPER}} a.crt-view-result',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'view_result_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} a.crt-view-result' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'view_result_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.crt-view-result' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'view_result_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} a.crt-view-result' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'heading_close_btn',
			[
				'label' => esc_html__( 'Close Button', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'close_btn_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch .crt-close-search' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'close_btn_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch .crt-close-search::before' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-data-fetch .crt-close-search' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'close_btn_position',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch .crt-close-search' => 'top: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'scrollbar_heading',
			[
				'label' => esc_html__( 'Scrollbar', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'scrollbar_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch ul::-webkit-scrollbar-thumb' => 'border-left-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'scrollbar_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch ul::-webkit-scrollbar-thumb' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-data-fetch ul::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + 3px);',
				]
			]
		);

		$this->add_control(
			'no_results_heading',
			[
				'label' => esc_html__( 'No Results', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'no_results_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch .crt-no-results' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'no_results_typography',
				'selector' => '{{WRAPPER}} .crt-data-fetch .crt-no-results',
			]
		);

		$this->add_responsive_control(
			'no_results_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'vh',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch .crt-no-results' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'search_results_box_border_size',
			[
				'label' => esc_html__( 'Border Size', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_results_box_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'search_results_box_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-data-fetch ul' => 'margin: 0;padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'price_heading',
			[
				'label' => esc_html__( 'Price', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'product_price_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .crt-search-product-price' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_product_price' => 'yes',
					'ajax_search' => 'yes'
				]
			]
		);

		$this->add_control(
			'product_price_spacing',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
					'{{WRAPPER}} .crt-search-product-price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_product_price' => 'yes',
					'ajax_search' => 'yes'
				]
			]
		);

		$this->end_controls_section();
	}

    public function add_section_ajax_pagination() {

        // Tab: Content ==============
        // Section: Pagination -------
        $this->start_controls_section(
            'section_ajax_search_pagination',
            [
                'label' => esc_html__( 'Ajax Pagination', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'pagination_load_more_text',
            [
                'label' => esc_html__( 'Load More Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Load More'
            ]
        );

        $this->add_control(
            'pagination_finish_text',
            [
                'label' => esc_html__( 'Finish Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'End of Content.'
            ]
        );

        $this->add_control(
            'pagination_animation',
            [
                'label' => esc_html__( 'Select Animation', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'loader-1',
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'loader-1' => esc_html__( 'Loader 1', 'crt-manage' ),
                    'loader-2' => esc_html__( 'Loader 2', 'crt-manage' ),
                    'loader-3' => esc_html__( 'Loader 3', 'crt-manage' ),
                    'loader-4' => esc_html__( 'Loader 4', 'crt-manage' ),
                    'loader-5' => esc_html__( 'Loader 5', 'crt-manage' ),
                    'loader-6' => esc_html__( 'Loader 6', 'crt-manage' ),
                ]
            ]
        );

        $this->add_control(
            'pagination_align',
            [
                'label' => esc_html__( 'Alignment', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
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
                    ]
                ],
                'default' => 'center',
                'prefix_class' => 'crt-ajax-search-pagination-',
                'render_type' => 'template',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

    }

    public function add_section_style_ajax_pagination() {

        // Styles ====================
        // Section: Pagination -------
        $this->start_controls_section(
            'section_style_pagination',
            [
                'label' => esc_html__( 'AJAX Pagination', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );

        $this->start_controls_tabs( 'tabs_grid_pagination_style' );

        $this->start_controls_tab(
            'tab_grid_pagination_normal',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-ajax-search-pagination svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .crt-ajax-search-pagination > div > span' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-ajax-search-pagination > div > span' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-no-more-results' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'pagination_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-ajax-search-pagination > div > span' => 'border-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'pagination_box_shadow',
                'selector' => '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results, {{WRAPPER}} .crt-ajax-search-pagination > div > span',
            ]
        );

        $this->add_control(
            'pagination_loader_color',
            [
                'label'  => esc_html__( 'Loader Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .crt-double-bounce .crt-child' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-wave .crt-rect' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-spinner-pulse' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-chasing-dots .crt-child' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-three-bounce .crt-child' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-fading-circle .crt-circle:before' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'pagination_wrapper_color',
            [
                'label'  => esc_html__( 'Wrapper Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-ajax-search-pagination' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_pagination_hover',
            [
                'label' => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'pagination_color_hr',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results:hover svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color_hr',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#4A45D2',
                'selectors' => [
                    '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'pagination_border_color_hr',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results:hover' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'pagination_box_shadow_hr',
                'selector' => '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results:hover',
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'pagination_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-ajax-search-pagination svg' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .crt-ajax-search-pagination > div > span' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pagination_typography',
                'selector' => '{{WRAPPER}} .crt-ajax-search-pagination, {{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results'
            ]
        );

        $this->add_control(
            'pagination_border_type',
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
                    '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .crt-ajax-search-pagination > div > span' => 'border-style: {{VALUE}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'pagination_border_width',
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
                    '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-ajax-search-pagination > div > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'pagination_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_distance_from_grid',
            [
                'label' => esc_html__( 'Distance From List', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-ajax-search-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'pagination_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 8,
                    'right' => 20,
                    'bottom' => 8,
                    'left' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-ajax-search-pagination > div > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_wrapper_padding',
            [
                'label' => esc_html__( 'Wrapper Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-ajax-search-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-ajax-search-pagination .crt-load-more-results' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-ajax-search-pagination > div > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

    }

    public function add_control_ajax_search_img_size() {

		$intermediate_image_sizes = [];

		foreach ( get_intermediate_image_sizes() as $key=>$value ) {
			$intermediate_image_sizes[$value] = $value;
		}

		$this->add_control(
			'ajax_search_img_size',
			[
				'label' => esc_html__( 'Image Crop', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => $intermediate_image_sizes,
				'default' => 'thumbnail',
			]
		);
	}

	public function add_control_search_query() {
        $search_post_type = Utilities::get_custom_types_of( 'post', false );
        $search_post_type = array_merge( [ 'all' => esc_html__( 'All', 'crt-manage' ) ], $search_post_type );

        foreach ( $search_post_type as $key => $value ) {
            if ( 'all' != $key && 'post' != $key && 'page' != $key && 'product' != $key && 'e-landing-page' != $key ) {
                $search_post_type['pro-'. $key] = $value;
                unset($search_post_type[$key]);
            } else {
                $search_post_type[$key] = $value .'';
            }
        }

        $this->add_control(
            'search_query',
            [
                'label' => esc_html__( 'Select Query', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => $search_post_type,
                'default' => 'all',
            ]
        );
	}

	public function add_control_select_category() {
        $this->add_control(
            'select_category',
            [
                'label' => esc_html__( 'Enable Taxonomy Filter', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'condition' => [
                    'search_query!' => 'all'
                ]
            ]
        );
	}

    public function add_control_all_cat_text() {
        $this->add_control(
            'all_cat_text',
            [
                'label' => esc_html__( 'All Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'All Categories', 'crt-manage' ),
                'condition' => [
                    'search_query!' => 'all',
                    'select_category' => 'yes'
                ]
            ]
        );

        $search_post_type = Utilities::get_custom_types_of( 'post', false );
        $search_post_type = array_merge( [ 'all' => esc_html__( 'All', 'crt-manage' ) ], $search_post_type );

        foreach ( $search_post_type as $key => $value ) {
            if ( 'all' != $key && 'post' != $key && 'page' != $key && 'product' != $key && 'e-landing-page' != $key ) {
                $search_post_type['pro-'. $key] = $value;
                unset($search_post_type[$key]);
            } else {
                $search_post_type[$key] = $value .'';
            }
        }

        // Taxonomies
        foreach ( $search_post_type as $slug => $title ) {
            $this->add_control(
                'query_taxonomy_'. $slug,
                [
                    'label' => esc_html__( $title. ' Taxonomies', 'crt-manage' ),
                    'type' => 'crt-ajax-select2',
                    'options' => 'ajaxselect2/get_post_type_taxonomies',
                    'query_slug' => $slug,
                    'multiple' => true,
                    'label_block' => true,
                    'condition' => [
                        'search_query!' => 'all',
                        'select_category' => 'yes',
                        'search_query' => $slug,
                    ],
                ]
            );
        }
    }


    public function add_control_ajax_search() {
		$this->add_control(
			'ajax_search',
			[
				'label' => esc_html__( 'Enable Ajax Search', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);
	}

	public function add_control_number_of_results() {
        $this->add_control(
            'number_of_results',
            [
                'label' => __( 'Number of Results', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 10,
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );
	}


	public function add_control_enable_meta_query() {
		$this->add_control(
			'enable_meta_query',
			[
				'label' => esc_html__( 'Enable Meta Query', 'crt-manage' ),
				'description' => esc_html__( 'Include Meta/Custom Fields in Search Results.', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'ajax_search' => 'yes'
				],
			]
		);
	}


	public function add_control_show_password_protected() {
		if ( current_user_can( 'administrator' ) ) {
			$this->add_control(
				'ajax_show_ps_pt',
				[
					'label' => esc_html__( 'Show Password Protected', 'crt-manage' ),
					'type' => Controls_Manager::SWITCHER,
					'description' => esc_html__( 'Only for users with capability to read private posts', 'crt-manage' ),
					'condition' => [
						'ajax_search' => 'yes'
					],
					'render_type' => 'template',
				]
			);
		}		
	}

	public function add_control_open_in_new_page() {
		$this->add_control(
			'ajax_search_link_target',
			[
				'label' => esc_html__( 'Open Link in New Tab', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'ajax_search' => 'yes'
                ]
			]
		);
	}

	public function add_control_show_ajax_thumbnails() {
		$this->add_control(
			'show_ajax_thumbnails',
			[
				'label' => esc_html__( 'Show Ajax Thumbnails', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
			]
		);
	}

	public function add_control_exclude_posts_without_thumbnail() {
		$this->add_control(
			'exclude_posts_without_thumbnail',
			[
				'label' => esc_html__( 'Exclude Results without Thumbnails', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes',
					'show_ajax_thumbnails' => 'yes'
                ]
			]
		);
	}

	public function add_control_show_description() {
		$this->add_control(
			'show_description',
			[
				'label' => esc_html__( 'Show Description', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
			]
		);
	}

	public function add_control_number_of_words_in_excerpt() {
        $this->add_control(
            'number_of_words_in_excerpt',
            [
                'label' => __( 'Description Number of Words', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 5,
                'step' => 1,
                'default' => 30,
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes',
					'show_description' => 'yes'
                ]
            ]
        );
	}

	public function add_control_show_view_result_btn() {
		$this->add_control(
			'show_view_result_btn',
			[
				'label' => esc_html__( 'Show View Results Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
			]
		);
	}

	public function add_control_view_result_text() {
		$this->add_control(
			'view_result_text',
			[
				'label' => esc_html__( 'View Results', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'View Results', 'crt-manage' ),
                'condition' => [
					'show_view_result_btn' => 'yes',
                    'ajax_search' => 'yes'
                ]
			]
		);
	}

	public function add_control_no_results_text() {
		$this->add_control(
			'no_results_text',
			[
				'label' => esc_html__( 'No Resulsts Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'No Results Found', 'crt-manage' ),
                'condition' => [
                    'ajax_search' => 'yes'
                ]
			]
		);
	}

	public function add_control_show_product_price() {
		$this->add_control(
			'show_product_price',
			[
				'label' => esc_html__( 'Show Product Price', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'render_type' => 'template',
				'condition' => [
					'ajax_search' => 'yes',
					'search_query' => ['all', 'product', 'pro-product']
				]
			]
		);
	}

	protected function register_controls() {
		
		// Section: Search -----------
		$this->start_controls_section(
			'section_search',
			[
				'label' => esc_html__( 'Search', 'crt-manage' ),
			]
		);

		Utilities::crt_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_search_query();

		$this->add_control_select_category();

		$this->add_control_all_cat_text();

		$this->add_control_ajax_search();

		$this->add_control_number_of_results();

		$this->add_control_enable_meta_query();

		$this->add_control_show_password_protected();

		if ( current_user_can( 'administrator' ) ) {
			$this->add_control(
				'show_attachments',
				[
					'label' => esc_html__( 'Show Attachments', 'crt-manage' ),
					'description' => esc_html__( 'Include Media Files in Search Results', 'crt-manage' ),
					'type' => Controls_Manager::SWITCHER,
					'condition' => [
						'ajax_search' => 'yes'
					],
					'render_type' => 'template',
				]
			);
		}

		$this->add_control_open_in_new_page();

		$this->add_control_show_ajax_thumbnails();

		$this->add_control_exclude_posts_without_thumbnail();

		$this->add_control_show_view_result_btn();

		$this->add_control_view_result_text();
		
		$this->add_control_show_description();

		$this->add_control_number_of_words_in_excerpt();

		$this->add_control_show_product_price();

		$this->add_control_no_results_text();

		$this->add_control(
			'search_placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Search...', 'crt-manage' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_aria_label',
			[
				'label' => esc_html__( 'Aria Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Search', 'crt-manage' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_btn',
			[
				'label' => esc_html__( 'Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_btn_style',
			[
				'label' => esc_html__( 'Style', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inner',
				'options' => [
					'inner' => esc_html__( 'Inner', 'crt-manage' ),
					'outer' => esc_html__( 'Outer', 'crt-manage' ),
				],
				'prefix_class' => 'crt-search-form-style-',
				'render_type' => 'template',
				'condition' => [
					'search_btn' => 'yes',
				],
			]
		);

		// $this->add_control(
		// 	'open_search_input_on_btn_click',
		// 	[
		// 		'label' => esc_html__( 'Open Search on Click', 'crt-manage' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'condition' => [
		// 			'search_btn' => 'yes',
		// 		]
		// 	]
		// );

		$this->add_control(
			'search_btn_disable_click',
			[
				'label' => esc_html__( 'Disable Button Click', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'search_btn_style' => 'inner',
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_type',
			[
				'label' => esc_html__( 'Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'text' => esc_html__( 'Text', 'crt-manage' ),
					'icon' => esc_html__( 'Icon', 'crt-manage' ),
				],
				'render_type' => 'template',
				'condition' => [
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_text',
			[
				'label' => esc_html__( 'Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Go',
				'condition' => [
					'search_btn_type' => 'text',
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
				'condition' => [
					'search_btn_type' => 'icon',
					'search_btn' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->add_section_ajax_pagination();

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles
		// Section: Input ------------
		$this->start_controls_section(
			'section_style_input',
			[
				'label' => esc_html__( 'Input', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_input_colors' );

		$this->start_controls_tab(
			'tab_input_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9e9e9e',
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-search-form-input:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-search-form-input::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-search-form-input:-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-search-form-input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-input' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-data-fetch' => 'border-color: {{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .crt-search-form-input-wrap'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus_colors',
			[
				'label' => esc_html__( 'Focus', 'crt-manage' ),
			]
		);

		$this->add_control(
			'input_focus_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}}.crt-search-form-input-focus .crt-search-form-input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}.crt-search-form-input-focus .crt-search-form-input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9e9e9e',
				'selectors' => [
					'{{WRAPPER}}.crt-search-form-input-focus .crt-search-form-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.crt-search-form-input-focus .crt-search-form-input:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.crt-search-form-input-focus .crt-search-form-input::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.crt-search-form-input-focus .crt-search-form-input:-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.crt-search-form-input-focus .crt-search-form-input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}}.crt-search-form-input-focus .crt-search-form-input' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_focus_box_shadow',
				'selector' => '{{WRAPPER}}.crt-search-form-input-focus .crt-search-form-input-wrap'
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'input_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'selector' => '{{WRAPPER}} .crt-search-form-input, {{WRAPPER}} .crt-category-select-wrap, {{WRAPPER}} .crt-category-select',
			]
		);

		$this->add_responsive_control(
			'input_align',
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
					'{{WRAPPER}} .crt-search-form-input' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_size',
			[
				'label' => esc_html__( 'Border Size', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-data-fetch' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .crt-data-fetch' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-category-select-wrap::before' => 'right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .crt-category-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Select ------------
		$this->start_controls_section(
			'section_style_select',
			[
				'label' => esc_html__( 'Taxonomy Filter', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'select_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'select_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-category-select-wrap' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-category-select' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'select_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-category-select' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'select_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-category-select' => 'border-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'select_border_size',
			[
				'label' => esc_html__( 'Border Size', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-category-select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'select_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-category-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .crt-category-select-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'select_width',
			[
				'label' => esc_html__( 'Select Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 230,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-category-select-wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'options_heading',
			[
				'label' => esc_html__( 'Options', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'option_font_size',
			[
				'label' => esc_html__( 'Font Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-category-select option' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'optgroup_heading',
			[
				'label' => esc_html__( 'Options Group', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'optgroup_font_size',
			[
				'label' => esc_html__( 'Font Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-category-select optgroup' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Button ------------
		$this->start_controls_section(
			'section_style_btn',
			[
				'label' => esc_html__( 'Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'search_btn' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_btn_colors' );

		$this->start_controls_tab(
			'tab_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'btn_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-submit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-submit' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-submit' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .crt-search-form-submit',
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);


		$this->add_control(
			'btn_hv_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-submit:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_hv_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-submit:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_hv_border_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-submit:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hv_box_shadow',
				'selector' => '{{WRAPPER}} .crt-search-form-submit:hover',
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'btn_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 125,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-submit' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
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
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-search-form-style-outer .crt-search-form-submit' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->add_control(
			'btn_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-search-form-style-outer.crt-search-form-position-right .crt-search-form-submit' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-search-form-style-outer.crt-search-form-position-left .crt-search-form-submit' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->add_control(
			'btn_position',
			[
				'label' => esc_html__( 'Position', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
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
				'prefix_class' => 'crt-search-form-position-',
				'separator' => 'before',
			]
		);

		$this->add_control(
            'btn_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'label' => esc_html__( 'Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-search-form-submit',
			]
		);

		$this->add_control(
			'btn_border_size',
			[
				'label' => esc_html__( 'Border Size', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-submit' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-search-form-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: AJAX ------------
		$this->add_section_style_ajax();

		$this->add_section_style_ajax_pagination();
		
	}

    public function render_search_pagination($settings) {
        if ( 'yes' === $settings['ajax_search'] ) :

            echo '<div class="crt-ajax-search-pagination elementor-clearfix crt-ajax-search-pagination-load-more">';
            echo '<button class="crt-load-more-results">'. esc_html__($settings['pagination_load_more_text'], 'crt-manage') .'</button>';
            echo '<div class="crt-pagination-loading">';
            switch ( $settings['pagination_animation'] ) {
                case 'loader-1':
                    echo '<div class="crt-double-bounce">';
                    echo '<div class="crt-child crt-double-bounce1"></div>';
                    echo '<div class="crt-child crt-double-bounce2"></div>';
                    echo '</div>';
                    break;
                case 'loader-2':
                    echo '<div class="crt-wave">';
                    echo '<div class="crt-rect crt-rect1"></div>';
                    echo '<div class="crt-rect crt-rect2"></div>';
                    echo '<div class="crt-rect crt-rect3"></div>';
                    echo '<div class="crt-rect crt-rect4"></div>';
                    echo '<div class="crt-rect crt-rect5"></div>';
                    echo '</div>';
                    break;
                case 'loader-3':
                    echo '<div class="crt-spinner crt-spinner-pulse"></div>';
                    break;
                case 'loader-4':
                    echo '<div class="crt-chasing-dots">';
                    echo '<div class="crt-child crt-dot1"></div>';
                    echo '<div class="crt-child crt-dot2"></div>';
                    echo '</div>';
                    break;
                case 'loader-5':
                    echo '<div class="crt-three-bounce">';
                    echo '<div class="crt-child crt-bounce1"></div>';
                    echo '<div class="crt-child crt-bounce2"></div>';
                    echo '<div class="crt-child crt-bounce3"></div>';
                    echo '</div>';
                    break;
                case 'loader-6':
                    echo '<div class="crt-fading-circle">';
                    echo '<div class="crt-circle crt-circle1"></div>';
                    echo '<div class="crt-circle crt-circle2"></div>';
                    echo '<div class="crt-circle crt-circle3"></div>';
                    echo '<div class="crt-circle crt-circle4"></div>';
                    echo '<div class="crt-circle crt-circle5"></div>';
                    echo '<div class="crt-circle crt-circle6"></div>';
                    echo '<div class="crt-circle crt-circle7"></div>';
                    echo '<div class="crt-circle crt-circle8"></div>';
                    echo '<div class="crt-circle crt-circle9"></div>';
                    echo '<div class="crt-circle crt-circle10"></div>';
                    echo '<div class="crt-circle crt-circle11"></div>';
                    echo '<div class="crt-circle crt-circle12"></div>';
                    echo '</div>';
                    break;

                default:
                    break;
            }
            echo '</div>';

            echo '<p class="crt-no-more-results">'. esc_html($settings['pagination_finish_text']) .'</p>';

            echo '</div>';

        endif;
    }

    protected function render_search_submit_btn() {
		$settings = $this->get_settings();

		$this->add_render_attribute(
		'button', [
			'class' => 'crt-search-form-submit',
			'aria-label' => $settings['search_aria_label'],
			'type' => 'submit',
		]
		);

		if ( $settings['search_btn_disable_click'] ) {
			$this->add_render_attribute( 'button', 'disabled' );
		}

		if ( 'yes' === $settings['search_btn'] ) : ?>

		<button <?php echo $this->get_render_attribute_string( 'button' ); ?>>
			<?php if ( 'icon' === $settings['search_btn_type'] && '' !== $settings['search_btn_icon']['value'] ) : ?>
				<i class="<?php echo esc_attr( $settings['search_btn_icon']['value'] ); ?>"></i>
			<?php elseif( 'text' === $settings['search_btn_type'] && '' !== $settings['search_btn_text'] ) : ?>
				<?php echo esc_html( $settings['search_btn_text'] ); ?>
			<?php endif; ?>
		</button>

		<?php
		endif;
	}
	
	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		// $hidden_input = 'yes' === $settings['open_search_input_on_btn_click'] ? 'crt-search-input-hidden' : '';
		$hidden_input = '';

		$this->add_render_attribute(
			'input', [
				'class' => 'crt-search-form-input',
				'placeholder' => $settings['search_placeholder'],
				'aria-label' => $settings['search_aria_label'],
				'type' => 'search',
				'name' => 's',
				'title' => esc_html__( 'Search', 'crt-manage' ),
				'value' => get_search_query(),
				'crt-query-type' => $settings['search_query'],
				'crt-taxonomy-type' => isset($settings['query_taxonomy_'. $settings['search_query']]) ? $settings['query_taxonomy_'. $settings['search_query']] : '',
				'number-of-results' => isset($settings['number_of_results']) ? $settings['number_of_results'] : 2,
				'ajax-search' => isset($settings['ajax_search']) ? $settings['ajax_search'] : '',
				'meta-query' => isset($settings['enable_meta_query']) ? $settings['enable_meta_query'] : '',
				'show-description' => isset($settings['show_description']) ? $settings['show_description'] : '',
				'number-of-words' => isset($settings['number_of_words_in_excerpt']) ? $settings['number_of_words_in_excerpt'] : '',
				'show-ajax-thumbnails' => isset($settings['show_ajax_thumbnails']) ? $settings['show_ajax_thumbnails'] : '',
				'show-view-result-btn' => isset($settings['show_view_result_btn']) ? $settings['show_view_result_btn'] : '',
				'show-product-price' => isset($settings['show_product_price']) ? $settings['show_product_price'] : '',
				'view-result-text' => isset($settings['view_result_text']) ? $settings['view_result_text'] : '',
				'no-results' => isset($settings['no_results_text']) ? esc_html__($settings['no_results_text']) : '',
				'exclude-without-thumb' => isset($settings['exclude_posts_without_thumbnail']) ? $settings['exclude_posts_without_thumbnail'] : '',
				'link-target' => isset($settings['ajax_search_link_target']) && ( 'yes' === $settings['ajax_search_link_target'] ) ? '_blank'  : '_self',
				'password-protected' => isset($settings['ajax_show_ps_pt']) ? $settings['ajax_show_ps_pt'] : 'no',
				'attachments' => isset($settings['show_attachments']) ? $settings['show_attachments'] : 'no',
				// 'ajax-search-img-size' => isset($settings['ajax_search_img_size']) ? $settings['ajax_search_img_size'] : ''
			]
		);

		?>
		<form role="search" method="get" class="crt-search-form" action="<?php echo esc_url(home_url()); ?>">
			<div class="crt-search-form-input-wrap elementor-clearfix">
				<input <?php echo $this->get_render_attribute_string( 'input' ); ?>>
				<?php
				if ( $settings['search_btn_style'] === 'inner' ) {
					$this->render_search_submit_btn();
				}
				?>
			</div>
			<?php
                if ( $settings['search_btn_style'] === 'outer' ) {
                    $this->render_search_submit_btn();
                }
			?>
		</form>
		<div class="crt-data-fetch">
			<span class="crt-close-search"></span>
			<ul></ul>
		</div>
		<?php
	}

}