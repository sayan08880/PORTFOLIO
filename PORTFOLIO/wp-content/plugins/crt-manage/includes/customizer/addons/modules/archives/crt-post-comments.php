<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Post_Comments extends Widget_Base {
	
	public function get_name() {
		return 'crt-post-comments';
	}

	public function get_title() {
		return esc_html__( 'Post Comments', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-comments';
	}

	public function get_categories() {
	    return [ 'crt_manage_single' ];
	}

	public function get_keywords() {
		return [ 'comments', 'post' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function add_control_comments_avatar_size() {
        $this->add_responsive_control(
            'comments_avatar_size',
            [
                'label' => esc_html__( 'Avatar Size', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 60,
                'min' => 10,
                'selectors' => [
                    '{{WRAPPER}} .crt-comment-avatar img' => 'width: {{SIZE}}px;',
                ],
                'render_type' => 'template',
                'condition' => [
                    'comments_avatar' => 'yes'
                ],
            ]
        );
    }

    public function add_control_comment_form_placeholders() {
        $this->add_control(
            'comment_form_placeholders',
            [
                'label' => esc_html__( 'Show Placeholders', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
    }


	public function add_control_avatar_gutter() {
		$this->add_responsive_control(
			'avatar_gutter',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
					'{{WRAPPER}} .crt-comment-meta, .crt-comment-content' => 'margin-left: calc(60px + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}}.crt-comment-reply-separate .crt-comment-reply' => 'margin-left: calc(60px + {{SIZE}}{{UNIT}});',
				],
				'separator' => 'after',
			]
		);
	}

	public function add_control_comments_form_layout() {
		$this->add_control(
			'comments_form_layout',
			[
				'label' => esc_html__( 'Select Layout', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style-5',
				'options' => [
					'style-1' => esc_html__( 'Style 1', 'crt-manage' ),
					'style-2' => esc_html__( 'Style 2', 'crt-manage' ),
					'style-3' => esc_html__( 'Style 3', 'crt-manage' ),
					'style-4' => esc_html__( 'Style 4', 'crt-manage' ),
					'style-5' => esc_html__( 'Style 5', 'crt-manage' ),
					'style-6' => esc_html__( 'Style 6', 'crt-manage' ),
				],
				'separator' => 'before'
			]
		);
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_comments_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'section_title',
			[
				'label' => esc_html__( 'Show Section Title', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'comments_text_1',
			[
				'label' => esc_html__( 'One Comment', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Comment',
				'condition' => [
					'section_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'comments_text_2',
			[
				'label' => esc_html__( 'Multiple Comments', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Comments',
				'condition' => [
					'section_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'comments_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control_comments_avatar_size();

		$this->add_control(
			'comments_reply_location',
			[
				'label' => esc_html__( 'Reply Location', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'separate',
				'options' => [
					'inline' => esc_html__( 'Inline', 'crt-manage' ),
					'separate' => esc_html__( 'Separate', 'crt-manage' ),
				],
				'prefix_class' => 'crt-comment-reply-',
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'comments_navigation_align',
			[
				'label' => __( 'Navigation Align', 'crt-manage' ),
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
				'default' => 'center',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comments_navigation_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'selectors_dictionary' => [
					'' => 'display: none;',
					'yes' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .crt-comments-navigation a.prev' => '{{VALUE}}',
					'{{WRAPPER}} .crt-comments-navigation a.next' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'comments_navigation_numbers',
			[
				'label' => esc_html__( 'Show Numbers', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'selectors_dictionary' => [
					'' => 'display: none;',
					'yes' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .crt-comments-navigation .page-numbers:not(.prev):not(.next)' => '{{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// Tab: Content ==============
		// Section: Comment Form -----
		$this->start_controls_section(
			'section_comment_form',
			[
				'label' => esc_html__( 'Comment Form', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'comment_form_title',
			[
				'label' => esc_html__( 'Section Title', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Leave a Reply',
				'condition' => [
					'section_title' => 'yes'
				]
			]
		);

		$this->add_control_comments_form_layout();

		$this->add_control(
			'comment_form_labels',
			[
				'label' => esc_html__( 'Show Labels', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);



		$this->add_control_comment_form_placeholders();

		$this->add_control(
			'comment_form_website',
			[
				'label' => esc_html__( 'Show Website Field', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_form_submit_text',
			[
				'label' => esc_html__( 'Submit Button Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Submit',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Section Title ----
		$this->start_controls_section(
			'section_style_section_title',
			[
				'label' => esc_html__( 'Section Title', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'section_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
            'section_title_align',
            [
                'label' => esc_html__( 'Align', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
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
				'selectors' => [
					'{{WRAPPER}} .crt-comments-wrap > h3' => 'text-align: {{VALUE}}',
				],
				'separator' => 'after'
            ]
        );

		$this->add_control(
			'section_title_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-comments-wrap > h3' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_title_bd_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .crt-comments-wrap > h3' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'section_title_typography',
				'selector' => '{{WRAPPER}} .crt-comments-wrap > h3',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '17',
							'unit' => 'px',
						],
					],
					'letter_spacing' => [
						'default' => [
							'size' => '0.5'
						]
					],
				]
			]
		);

		$this->add_control(
			'section_title_bd_type',
			[
				'label' => esc_html__( 'Border Style', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-comments-wrap > h3' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'section_title_bd_width',
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
					'{{WRAPPER}} .crt-comments-wrap > h3' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'section_title_bd_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'section_title_space',
			[
				'label' => esc_html__( 'Bottom Space', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-comments-wrap > h3' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Comments ---------
		$this->start_controls_section(
			'section_style_comments',
			[
				'label' => esc_html__( 'Comments', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'comment_odd_color',
			[
				'label' => esc_html__( 'Odd Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fcfcfc',
				'selectors' => [
					'{{WRAPPER}} .even .crt-post-comment' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_even_color',
			[
				'label' => esc_html__( 'Even Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fcfcfc',
				'selectors' => [
					'{{WRAPPER}} .odd .crt-post-comment' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_author_color',
			[
				'label' => esc_html__( 'By Post Author Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#EFEFEF',
				'selectors' => [
					'{{WRAPPER}} .bypostauthor .crt-post-comment' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .crt-post-comment' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'comment_shadow',
				'selector' => '{{WRAPPER}} .crt-post-comment',
			]
		);

		$this->add_responsive_control(
			'comment_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_border_type',
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
					'{{WRAPPER}} .crt-post-comment' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_border_width',
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
					'{{WRAPPER}} .crt-post-comment' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'comment_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'comment_radius',
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
					'{{WRAPPER}} .crt-post-comment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'comment_spacing',
			[
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-post-comment' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'comment_indent',
			[
				'label' => esc_html__( 'Nested Indent', 'crt-manage' ),
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
					'size' => 24,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-comments-list .children' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Avatar -----------
		$this->start_controls_section(
			'section_style_avatar',
			[
				'label' => esc_html__( 'Avatar', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'comments_avatar' => 'yes',
				],
			]
		);

		$this->add_control_avatar_gutter();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'avatar_border',
				'fields_options' => [
					'border' => [
						'default' => '',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#222222',
					],
				],
				'selector' => '{{WRAPPER}} .crt-comment-avatar',
			]
		);

		$this->add_control(
			'avatar_radius',
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
					'{{WRAPPER}} .crt-comment-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Nickname ---------
		$this->start_controls_section(
			'section_style_nickname',
			[
				'label' => esc_html__( 'Nickname', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_nickname_style' );

		$this->start_controls_tab(
			'tab_nickname_normal',
			[
				'label' => __( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'nickname_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-author span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-comment-author a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'nickname_typography',
				'selector' => '{{WRAPPER}} .crt-comment-author',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'nickname_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-comment-author a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_nickname_hover',
			[
				'label' => __( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'nickname_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-comment-author a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'nickname_space',
			[
				'label' => esc_html__( 'Bottom Space', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-comment-author' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Date and Time ----
		$this->start_controls_section(
			'section_style_metadata',
			[
				'label' => esc_html__( 'Date and Time', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'metadata_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9B9B9B',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-metadata' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-comment-metadata a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-comment-reply:before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'metadata_typography',
				'selector' => '{{WRAPPER}} .crt-comment-metadata',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Open Sans',
					],
					'font_size'      => [
						'default'    => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'metadata_space',
			[
				'label' => esc_html__( 'Bottom Space', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-comment-metadata' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content (Comment Text)', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_link_color',
			[
				'label'  => esc_html__( 'Link Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-content a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .crt-comment-content',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Open Sans',
					],
					'font_weight'    => [
						'default' => '400',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Reply Link -------
		$this->start_controls_section(
			'section_style_reply_link',
			[
				'label' => esc_html__( 'Reply Link', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_reply_link_style' );

		$this->start_controls_tab(
			'tab_reply_link_normal',
			[
				'label' => __( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'reply_link_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-reply a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'reply_link_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FCFCFC',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-reply a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'reply_link_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-comment-reply a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'reply_link_typography',
				'selector' => '{{WRAPPER}} .crt-comment-reply a',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '13',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'reply_link_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.6,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-comment-reply a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_reply_link_hover',
			[
				'label' => __( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'reply_link_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-reply a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'reply_link_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-comment-reply a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'reply_link_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-comment-reply a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'reply_link_padding',
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
					'{{WRAPPER}} .crt-comment-reply a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'reply_link_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-comment-reply a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'reply_link_border_type',
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
					'{{WRAPPER}} .crt-comment-reply a' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'reply_link_border_width',
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
					'{{WRAPPER}} .crt-comment-reply a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'reply_link_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'reply_link_radius',
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
					'{{WRAPPER}} .crt-comment-reply a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'reply_link_align',
			[
				'label' => __( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'crt-manage' ),
						'icon' => 'eicon-text-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'right',
				'prefix_class' => 'crt-comment-reply-align-',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Navigation -------
		$this->start_controls_section(
			'section_style_navigation',
			[
				'label' => esc_html__( 'Navigation', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_navigation_style' );

		$this->start_controls_tab(
			'tab_navigation_normal',
			[
				'label' => __( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'navigation_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-comments-navigation a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-comments-navigation span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'navigation_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-comments-navigation a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-comments-navigation span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'navigation_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-comments-navigation a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-comments-navigation span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'navigation_typography',
				'selector' => '{{WRAPPER}} .crt-comments-navigation a, {{WRAPPER}} .crt-comments-navigation span',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '13',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'navigation_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-comments-navigation a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_navigation_hover',
			[
				'label' => __( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'navigation_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .crt-comments-navigation a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-comments-navigation span.current' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'navigation_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-comments-navigation a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-comments-navigation span.current' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'navigation_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-comments-navigation a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-comments-navigation span.current' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'navigation_padding',
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
					'{{WRAPPER}} .crt-comments-navigation a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-comments-navigation span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'navigation_border_type',
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
					'{{WRAPPER}} .crt-comments-navigation a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-comments-navigation span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'navigation_border_width',
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
					'{{WRAPPER}} .crt-comments-navigation a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-comments-navigation span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'navigation_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'navigation_radius',
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
					'{{WRAPPER}} .crt-comments-navigation a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-comments-navigation span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();	

		// Styles ====================
		// Section: Comment Form Title
		$this->start_controls_section(
			'section_style_cf_title',
			[
				'label' => esc_html__( 'Comment Form Title', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'cf_title_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-reply-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cf_title_bd_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-reply-title' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cf_title_typography',
				'selector' => '{{WRAPPER}} .crt-comment-reply-title',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Raleway',
					],
					'font_weight'    => [
						'default' => '500',
					],
					'letter_spacing' => [
						'default' => [
							'size' => '0.5'
						]
					],
					'font_size'      => [
						'default'    => [
							'size' => '17',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'cf_title_bd_type',
			[
				'label' => esc_html__( 'Border Style', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-comment-reply-title' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'cf_title_bd_width',
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
					'{{WRAPPER}} .crt-comment-reply-title' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'cf_title_bd_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'cf_title_top_space',
			[
				'label' => esc_html__( 'Top Space', 'crt-manage' ),
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
					'size' => 85,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-comment-reply-title' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'cf_title_bottom_space',
			[
				'label' => esc_html__( 'Bottom Space', 'crt-manage' ),
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-comment-reply-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
            'cf_title_align',
            [
                'label' => esc_html__( 'Align', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
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
				'selectors' => [
					'{{WRAPPER}} .crt-comment-reply-title' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section();

		// Styles ====================
		// Section: Comment Form -----
		$this->start_controls_section(
			'section_style_comment_form',
			[
				'label' => esc_html__( 'Comment Form', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_comment_form_style' );

		$this->start_controls_tab(
			'tab_comment_form_normal',
			[
				'label' => __( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'comment_form_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-form input[type=text]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-comment-form textarea' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-comment-form label' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-comment-form .logged-in-as a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-comment-form .logged-in-as .required-field-message' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_form_placeholder_color',
			[
				'label'  => esc_html__( 'Placeholder Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#B8B8B8',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-form input[type=text]::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .crt-comment-form textarea::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .crt-comment-form input[type=text]::-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-comment-form textarea::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
				'condition' => [
					'comment_form_placeholders' => 'yes'
				]
			]
		);

		$this->add_control(
			'comment_form_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-form input[type=text]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-comment-form textarea' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'comment_form_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DBDBDB',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-form input[type=text]' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-comment-form textarea' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'comment_form_typography',
				'selector' => '{{WRAPPER}} .crt-comment-form label, {{WRAPPER}} .crt-comment-form input[type=text], {{WRAPPER}} .crt-comment-form textarea, {{WRAPPER}} .crt-comment-form .logged-in-as',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'comment_form_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.6,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-comment-form input[type=text]' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-comment-form input[type=text]::placeholder' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-comment-form input[type=text]::-ms-input-placeholder' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-comment-form textarea' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_comment_form_hover',
			[
				'label' => __( 'Focus', 'crt-manage' ),
			]
		);

		$this->add_control(
			'comment_form_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-form input[type=text]:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-comment-form textarea:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_form_placeholder_color_hr',
			[
				'label'  => esc_html__( 'Placeholder Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#B8B8B8',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-form input[type=text]:focus::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .crt-comment-form textarea:focus::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .crt-comment-form input[type=text]:focus::-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-comment-form textarea:focus::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
				'condition' => [
					'comment_form_placeholders' => 'yes'
				]
			]
		);

		$this->add_control(
			'comment_form_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-comment-form input[type=text]:focus' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-comment-form textarea:focus' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'comment_form_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-comment-form input[type=text]:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-comment-form textarea:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'comment_form_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-comment-form input[type=text]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-comment-form textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'comment_form_border_type',
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
					'{{WRAPPER}} .crt-comment-form input[type=text]' => 'border-style: {{VALUE}}',
					'{{WRAPPER}} .crt-comment-form textarea' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_form_border_width',
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
					'{{WRAPPER}} .crt-comment-form input[type=text]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-comment-form textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'comment_form_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'comment_form_radius',
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
					'{{WRAPPER}} .crt-comment-form input[type=text]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-comment-form textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'comment_form_gutter',
			[
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-comment-form-author' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-comment-form-email' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-comment-form-url' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-comment-form-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Submit Button ----
		$this->start_controls_section(
			'section_style_submit_button',
			[
				'label' => esc_html__( 'Submit Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_submit_button_style' );

		$this->start_controls_tab(
			'tab_submit_button_normal',
			[
				'label' => __( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'submit_button_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-submit-comment' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_button_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-submit-comment' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'submit_button_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DBDBDB',
				'selectors' => [
					'{{WRAPPER}} .crt-submit-comment' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'submit_button_typography',
				'selector' => '{{WRAPPER}} .crt-submit-comment',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'letter_spacing' => [
						'default' => [
							'size' => '0.5'
						]
					],
					'font_size'      => [
						'default'    => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'submit_button_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-submit-comment' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_submit_button_hover',
			[
				'label' => __( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'submit_button_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-submit-comment:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_button_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4C48BD',
				'selectors' => [
					'{{WRAPPER}} .crt-submit-comment:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'submit_button_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-submit-comment:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'submit_button_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 45,
					'bottom' => 10,
					'left' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-submit-comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'submit_button_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 25,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-submit-comment' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'submit_button_border_type',
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
					'{{WRAPPER}} .crt-submit-comment' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'submit_button_border_width',
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
					'{{WRAPPER}} .crt-submit-comment' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'submit_button_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'submit_button_radius',
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
					'{{WRAPPER}} .crt-submit-comment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
            'submit_button_align',
            [
                'label' => esc_html__( 'Align', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
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
				'selectors' => [
					'{{WRAPPER}} .form-submit' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section();

	}

	// Outputs a comment in the HTML5 format
	public static function html5_comment( $comment, $args, $depth ) {
		// Get Settings
		$this_widget = $GLOBALS['crt_post_comments_widget'];
		$settings = $this_widget->get_settings();


		// Class, URL, Name
		$comment_class = implode( ' ', get_comment_class( $comment->has_children ? 'parent' : '', $comment ) );
		$author_url = get_comment_author_url( $comment );
		$author_name = get_comment_author( $comment );

		// Comment HTML
		echo '<li id="comment-'. esc_attr(get_comment_ID()) .'" class="'. esc_attr( $comment_class ) .'">';
		echo '<article class="crt-post-comment elementor-clearfix">';

			// Comment Avatar
			if ( 'yes' === $settings['comments_avatar'] ) {
				echo '<div class="crt-comment-avatar">';
					echo get_avatar( $comment, $settings['comments_avatar_size'] );
				echo '</div>';
			}

			// Comment Meta
			echo '<div class="crt-comment-meta">';
				// Comment Author
				echo '<div class="crt-comment-author">';
					if ( '' === $author_url ) {
						echo '<span>'. esc_html( $author_name ) .'</span>';
					} else {
						echo '<a href="'. esc_url( $author_url ) .'">'. esc_html( $author_name ) .'</a>';
					}
				echo '</div>';

				// Comment Metadata
				echo '<div class="crt-comment-metadata elementor-clearfix">';
					// Date and Time
					echo '<span>'. esc_html(get_comment_date( '', $comment )) . esc_html__( ' at ', 'crt-manage' ) . esc_html(get_comment_time()) .'</span>';

					// Edit Link
					edit_comment_link( esc_html__( 'Edit', 'crt-manage' ), ' | ', '' );

					// Reply Button
					if ( 'inline' === $settings['comments_reply_location'] ) {
						comment_reply_link(
							array_merge( $args, [
								'depth' => $depth,
								'max_depth' => $args['max_depth'],
								'before' => '<div class="crt-comment-reply">',
								'after' => '</div>',
							] )
						);
					}

					// Moderation
					if ( '0' == $comment->comment_approved ) {
						echo '<p>'. esc_html__( 'Your comment is awaiting moderation.', 'crt-manage' ) .'</p>';
					}
				echo '</div>';
			echo '</div>';

			// Comment Content
			echo '<div class="crt-comment-content">';
				comment_text( $comment );
			echo '</div>';

			// Reply Button
			if ( 'separate' === $settings['comments_reply_location'] ) {
				comment_reply_link(
					array_merge( $args, [
						'depth' => $depth,
						'max_depth' => $args['max_depth'],
						'before' => '<div class="crt-comment-reply">',
						'after' => '</div>',
					] )
				);
			}

		echo '</article>';
		echo '</li>';
	}

	protected function render() {
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// Temp log out user
		if ( $is_editor ) {
			$store_current_user = wp_get_current_user()->ID;
			wp_set_current_user( 0 );
		}


		//  Get Settings
		$settings = $this->get_settings();

		$GLOBALS['crt_post_comments_widget'] = $this;

		if ( ! comments_open( get_the_ID() ) ) {
			return;
		}

		// Comments Count
		$count = get_comments_number( get_the_ID() );

		// Comments Wrapper
		echo '<div class="crt-comments-wrap" id="comments">';

			// If comments are open or we have at least one comment
			if ( $count ) {

				if ( $count == 1 ) {
					$text = $count .' '. $settings['comments_text_1'];
				} elseif ( $count > 1 ) {
					$text = $count .' '. $settings['comments_text_2'];
				}

				// Comments
				if ( 'yes' === $settings['section_title'] ) {
					echo '<h3> '. esc_html($text) .'</h3>';
				}

				// Get Post Comments
				$get_comments = get_comments( [ 'post_id' => get_the_ID() ] );

				// Comments List HTML
				echo '<ul class="crt-comments-list">';
					wp_list_comments( [ 'callback' => [$this, 'html5_comment'] ], $get_comments );
				echo '</ul>';

				unset( $GLOBALS['crt_post_comments_widget'] );

				// Comments Navigation
				if ( get_comment_pages_count($get_comments) > 1 && get_option( 'page_comments' ) ) {
					echo '<div class="crt-comments-navigation crt-comments-navigation-'. esc_html($settings['comments_navigation_align']) .'">';
						paginate_comments_links([
							'base' => add_query_arg( 'cpage', '%#%' ),
							'format' => '',
							'total' => get_comment_pages_count($get_comments),
							'echo' => true,
							'add_fragment' => '#comments',
							'prev_text' => '<i class="eicon-arrow-left"></i> '. esc_html__( 'Previous', 'crt-manage' ),
							'next_text' => esc_html__( 'Next', 'crt-manage' ) .' <i class="eicon-arrow-right"></i>',
						]);
					echo '</div>';
				}
			}

			// Comment Form: Author, Email and Website Fields
			add_filter( 'comment_form_default_fields', function( $defaults ) {
				$settings = $this->get_settings();
				$author_label = $email_label = $url_label = '';
				$author_ph = $email_ph = $url_ph = '';
				$req = get_option( 'require_name_email' );

				// Labels
				if ( 'yes' === $settings['comment_form_labels'] ) {
					$author_label = '<label>'. esc_html__( 'Name', 'crt-manage' ) . ($req ? '<span>*</span>' : '') .'</label>';
					$email_label = '<label>'. esc_html__( 'Email', 'crt-manage' ) . ($req ? '<span>*</span>' : '') .'</label>';
					$url_label = '<label>'. esc_html__( 'Website', 'crt-manage' ) .'</label>';					
				}

                $settings['comment_form_placeholders'] = '';

				// Placeholders
				if ( 'yes' === $settings['comment_form_placeholders'] ) {
					$author_ph = esc_html__( 'Name', 'crt-manage' ) . ($req ? '*' : '');
					$email_ph = esc_html__( 'Email', 'crt-manage' ) . ($req ? '*' : '');
					$url_ph = esc_html__( 'Website', 'crt-manage' );
				}

				$fields = [
					// name
					'author' => '<div class="crt-comment-form-fields"> <div class="crt-comment-form-author">'. $author_label .
					'<input type="text" name="author" placeholder="'. esc_attr($author_ph) .'"/></div>',
					// Email
					'email' => '<div class="crt-comment-form-email">'. $email_label .
					'<input type="text" name="email" placeholder="'. esc_attr($email_ph) .'"/></div>',
					// Website
					'url' => '<div class="crt-comment-form-url">'. $url_label .
					'<input type="text" name="url" placeholder="'. esc_url($url_ph) .'"/></div></div>',
				];

				// Remove Website Field
				if ( '' === $settings['comment_form_website'] ) {
					$fields['url'] = '</div>';
				}

				return $fields;
			} );

			// Comment Form Defaults
			add_filter( 'comment_form_defaults', function( $defaults ) {
				$settings = $this->get_settings();
				$text_label = $text_ph = '';
				$req = get_option( 'require_name_email' );

				// Text Input Label
				if ( 'yes' === $settings['comment_form_labels'] ) {
					$text_label = '<label>'. esc_html__( 'Message', 'crt-manage' ) . ($req ? '<span>*</span>' : '') .'</label>';
				}

                $settings['comment_form_placeholders'] = '';

				// Text Input Placeholder
				if ( 'yes' === $settings['comment_form_placeholders'] ) {
					$text_ph = esc_html__( 'Message', 'crt-manage' ) . ($req ? '*' : '');
				}

				// Form
				$defaults['id_form'] = 'crt-comment-form';
				$defaults['class_form'] = 'crt-comment-form crt-cf-'. esc_attr($settings['comments_form_layout']);

				// No Website Filed Class
				if ( '' === $settings['comment_form_website'] ) {
					$defaults['class_form'] .= ' crt-cf-no-url';
				}

				// Title
				$defaults['title_reply'] = $settings['comment_form_title'];
				$defaults['title_reply_before'] = '<h3 id="crt-reply-title" class="crt-comment-reply-title">';
				$defaults['title_reply_after'] = '</h3>';

				// Text Field
				$defaults['comment_field']  = '<div class="crt-comment-form-text">'. $text_label;
				$defaults['comment_field'] .= '<textarea name="comment" placeholder="'. esc_attr($text_ph) .'" cols="45" rows="8" maxlength="65525"></textarea>';
				$defaults['comment_field'] .= '</div>';

				// Submit Button
				$defaults['id_submit'] = 'crt-submit-comment';
				$defaults['class_submit'] = 'crt-submit-comment';
				$defaults['label_submit'] = $settings['comment_form_submit_text'];

				return $defaults;
			} );

			// Form Output
			comment_form();

		echo '</div>'; // End .crt-comments-wrap


		// Logged-in user back.
		if ( $is_editor ) {
			wp_set_current_user( $store_current_user );
		}
	}
	
}