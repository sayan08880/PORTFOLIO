<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Post_Content extends Widget_Base {
	
	public function get_name() {
		return 'crt-post-content';
	}

	public function get_title() {
		return esc_html__( 'Post Content', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-post-content';
	}

	public function get_categories() {
		return ['crt_manage_single'];
	}

	public function get_keywords() {
		return [ 'post', 'content' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_content',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_content_display',
			[
				'label' => esc_html__( 'Display As', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'content',
				'options' => [
					'content' => esc_html__( 'Post Content', 'crt-manage' ),
					'excerpt' => esc_html__( 'Post Excerpt', 'crt-manage' ),
				],
			]
		);

		// $this->add_control(
		// 	'post_content_dropcap',
		// 	[
		// 		'label' => esc_html__( 'Enable Drop Cap', 'crt-manage' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'return_value' => 'yes',
		// 		'separator' => 'before'
		// 	]
		// );

		$this->add_responsive_control(
            'post_content_align',
            [
                'label' => esc_html__( 'Alignment', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
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
					'justify' => [
						'title' => __( 'Justified', 'crt-manage' ),
						'icon' => 'eicon-text-align-justify',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .crt-post-content' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section(); // End Controls Section


		// Styles ====================
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .crt-post-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_link_color',
			[
				'label'  => esc_html__( 'Link Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-post-content a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_link_hover_color',
			[
				'label'  => esc_html__( 'Link Hover Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-post-content a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .crt-post-content',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'title_link_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-post-content a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

        $this->add_control(
            'dropcap_heading',
            [
                'label' => esc_html__( 'Dropcap', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_dropcap_enable',
            [
                'label' => esc_html__( 'Dropcap Enable', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

		 $this->add_group_control(
		 	Group_Control_Typography::get_type(),
		 	[
		 		'name'     => 'content_dropcap_typography',
		 		'label' => esc_html__( 'Drop Cap Typography', 'crt-manage' ),
		 		'selector' => '{{WRAPPER}} .crt-post-content .has-drop-cap:not(:focus):first-letter',
                'condition' => [
                    'content_dropcap_enable' => 'yes',
                ],
		 	]
		 );

        $this->add_control(
            'quote_content_heading',
            [
                'label' => esc_html__( 'Quote', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'quote_content_type_before',
            [
                'label' => esc_html__( 'Quote Icon Before', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    '"‘"' => esc_html__( '‘', 'crt-manage' ),
                    '"’"' => esc_html__( '’', 'crt-manage' ),
                    '"‚"' => esc_html__( '‚', 'crt-manage' ),
                    '"‛"' => esc_html__( '‛', 'crt-manage' ),
                    '"“"' => esc_html__( '“', 'crt-manage' ),
                    '"”"' => esc_html__( '”', 'crt-manage' ),
                    '"„"' => esc_html__( '„', 'crt-manage' ),
                    '"‟"' => esc_html__( '‟', 'crt-manage' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-quote:before' => 'content: {{VALUE}};',
                ],
                'default' => 'none',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'quote_content_type_before_typography',
                'label' => esc_html__( 'Quote Typography', 'crt-manage' ),
                'selector' => '{{WRAPPER}} .crt-post-content .wp-block-quote:before',
            ]
        );

        $this->add_control(
            'quote_content_type_before_position',
            [
                'label' => esc_html__( 'Position', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => -20,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-quote:before' => 'position: absolute; top: {{TOP}}{{UNIT}};right: {{RIGHT}}{{UNIT}};bottom: {{BOTTOM}}{{UNIT}};left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );



//        $this->add_control(
//            'quote_content_type_after',
//            [
//                'label' => esc_html__( 'Quote Icon After', 'crt-manage' ),
//                'type' => Controls_Manager::SELECT,
//                'options' => [
//                    'none' => esc_html__( 'None', 'crt-manage' ),
//                    '"‘"' => esc_html__( '‘', 'crt-manage' ),
//                    '"’"' => esc_html__( '’', 'crt-manage' ),
//                    '"‚"' => esc_html__( '‚', 'crt-manage' ),
//                    '"‛"' => esc_html__( '‛', 'crt-manage' ),
//                    '"“"' => esc_html__( '“', 'crt-manage' ),
//                    '"”"' => esc_html__( '”', 'crt-manage' ),
//                    '"„"' => esc_html__( '„', 'crt-manage' ),
//                    '"‟"' => esc_html__( '‟', 'crt-manage' ),
//                ],
//                'selectors' => [
//                    '{{WRAPPER}} .crt-post-content .wp-block-quote:after' => 'content: "{{VALUE}}";',
//                ],
//                'default' => 'none',
//            ]
//        );

        $this->add_control(
            'quote_content_padding',
            [
                'label' => esc_html__( 'Quote Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 20,
                    'right' => 0,
                    'bottom' => 20,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-quote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'quote_content_margin',
            [
                'label' => esc_html__( 'Quote Margin', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-quote' => 'position: relative; margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_quote_typography',
                'label' => esc_html__( 'Quote Typography', 'crt-manage' ),
                'selector' => '{{WRAPPER}} .crt-post-content .wp-block-quote',
            ]
        );

        $this->add_responsive_control(
            'content_quote_typography_align',
            [
                'label' => esc_html__( 'Quote Alignment', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
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
                    'justify' => [
                        'title' => __( 'Justified', 'crt-manage' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-quote' => 'text-align: {{VALUE}}',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'content_quote_background_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-quote' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'content_quote_border_type',
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
                    '{{WRAPPER}} .crt-post-content .wp-block-quote' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_quote_border_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-quote' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_quote_border_width',
            [
                'label' => esc_html__( 'Border Width', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 1,
                    'right' => 0,
                    'bottom' => 1,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-quote' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'content_quote_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-quote' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

//        $this->add_control(
//            'content_quote_enable',
//            [
//                'label' => esc_html__( 'Dropcap Enable', 'crt-manage' ),
//                'type' => Controls_Manager::SWITCHER,
//                'default' => 'yes',
//            ]
//        );
//
//        $this->add_group_control(
//            Group_Control_Typography::get_type(),
//            [
//                'name'     => 'content_dropcap_typography',
//                'label' => esc_html__( 'Drop Cap Typography', 'crt-manage' ),
//                'selector' => '{{WRAPPER}} .crt-post-content .has-drop-cap:not(:focus):first-letter',
//                'condition' => [
//                    'content_dropcap_enable' => 'yes',
//                ],
//            ]
//        );

        $this->add_control(
            'quote_heading',
            [
                'label' => esc_html__( 'PullQuote', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'quote_padding',
            [
                'label' => esc_html__( 'PullQuote Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 20,
                    'right' => 0,
                    'bottom' => 20,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-pullquote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'quote_margin',
            [
                'label' => esc_html__( 'PullQuote Margin', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-pullquote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_pullquote_typography',
                'label' => esc_html__( 'PullQuote Typography', 'crt-manage' ),
                'selector' => '{{WRAPPER}} .crt-post-content .wp-block-pullquote',
            ]
        );

        $this->add_responsive_control(
            'content_pullquote_typography_align',
            [
                'label' => esc_html__( 'PullQuote Alignment', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
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
                    'justify' => [
                        'title' => __( 'Justified', 'crt-manage' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-pullquote' => 'text-align: {{VALUE}}',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'content_pullquote_background_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-pullquote' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'content_pullquote_border_type',
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
                    '{{WRAPPER}} .crt-post-content .wp-block-pullquote' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_pullquote_border_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-pullquote' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_pullquote_border_width',
            [
                'label' => esc_html__( 'Border Width', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 5,
                    'right' => 0,
                    'bottom' => 5,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-pullquote' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'content_pullquote_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-pullquote' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'preformatted_heading',
            [
                'label' => esc_html__( 'Preformatted', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'preformatted_padding',
            [
                'label' => esc_html__( 'Preformatted Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 30,
                    'right' => 30,
                    'bottom' => 30,
                    'left' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-preformatted' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'preformatted_margin',
            [
                'label' => esc_html__( 'Preformatted Margin', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-preformatted' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_preformatted_typography',
                'label' => esc_html__( 'Preformatted Typography', 'crt-manage' ),
                'selector' => '{{WRAPPER}} .crt-post-content .wp-block-preformatted',
            ]
        );

        $this->add_responsive_control(
            'content_preformatted_typography_align',
            [
                'label' => esc_html__( 'Preformatted Alignment', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
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
                    'justify' => [
                        'title' => __( 'Justified', 'crt-manage' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-preformatted' => 'text-align: {{VALUE}}',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'content_preformatted_background_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-preformatted' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'content_preformatted_border_type',
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
                    '{{WRAPPER}} .crt-post-content .wp-block-preformatted' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_preformatted_border_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-preformatted' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_preformatted_border_width',
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
                    '{{WRAPPER}} .crt-post-content .wp-block-preformatted' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'content_preformatted_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .crt-post-content .wp-block-preformatted' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		// $dropcap_class = 'yes' === $settings['post_content_dropcap'] ? ' crt-enable-dropcap' : '';

		echo '<div class="crt-post-content">';
			if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
				$page_templates_module = \Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' );
				
				if ( $page_templates_module ) {
					$page_templates_module->print_content();
				}
			} else {
				if ( 'content' === $settings['post_content_display'] ) {
					the_content();
				} else {
					the_excerpt();
				}
			}
		echo '</div>';

	}
	
}