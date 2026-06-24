<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Author_Box extends Widget_Base {
	
	public function get_name() {
		return 'crt-author-box';
	}

	public function get_title() {
		return esc_html__( 'Author Box', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-person';
	}

	public function get_categories() {
		return ['crt_manage_archive'];
	}

	public function get_keywords() {
		return [ 'author', 'box', 'post', ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function add_controls_group_author_name_links_to() {
		$this->add_control(
			'author_name_links_to',
			[
				'label' => esc_html__( 'Links To', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'Nothing', 'crt-manage' ),
					'posts' => esc_html__( 'Author Posts', 'crt-manage' ),
					'website' => esc_html__( 'Website', 'crt-manage' ),
				],
				'default' => 'none',
				'condition' => [
					'author_name' => 'yes',
				]
			]
		);
	}

	public function add_controls_group_author_title_links_to() {
		$this->add_control(
			'author_title_links_to',
			[
				'label' => esc_html__( 'Links To', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'Nothing', 'crt-manage' ),
					'posts' => esc_html__( 'Author Posts', 'crt-manage' ),
					'website' => esc_html__( 'Website', 'crt-manage' ),
				],
				'default' => 'none',
				'condition' => [
					'author_title' => 'yes',
				]
			]
		);
	}

	public function add_control_author_bio() {
        $this->add_control(
            'author_bio',
            [
                'label' => esc_html__( 'Author Bio', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator' => 'before'
            ]
        );
    }

	public function add_section_style_bio() {
//        $settings['author_bio'] = '';
//        $settings['author_name_link_tab'] = '';
//        $settings['author_title_link_tab'] = '';
//        $this->add_control(
//            'author_bio',
//            [
//                'label' => esc_html__( 'Author Bio', 'crt-manage' ),
//                'type' => Controls_Manager::SWITCHER,
//                'return_value' => 'yes',
//                'separator' => 'before'
//            ]
//        );
    }

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_author_box',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'author_arrange',
			[
				'label' => esc_html__( 'Arrange', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'vertical',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-h-align-left',
					],
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'crt-manage' ),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'crt-author-box-arrange-'
			]
		);

		$this->add_control(
			'author_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
					'{{WRAPPER}} .crt-author-box' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'author_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_name',
			[
				'label' => esc_html__( 'Show Name', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_name_tag',
			[
				'label' => esc_html__( 'Name HTML Tag', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h3',
				'condition' => [
					'author_name' => 'yes',
				]
			]
		);

		$this->add_controls_group_author_name_links_to();


		$this->add_control(
			'author_title',
			[
				'label' => esc_html__( 'Show Title', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_title_text',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Writer & Blogger',
				'condition' => [
					'author_title' => 'yes',
				]
			]
		);

		$this->add_control(
			'author_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h3',
				'condition' => [
					'author_title' => 'yes',
				]
			]
		);

		$this->add_controls_group_author_title_links_to();

		$this->add_control_author_bio();

		$this->add_control(
			'author_posts_link',
			[
				'label' => esc_html__( 'Show Author Posts Link', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'author_posts_link_text',
			[
				'label' => esc_html__( 'Posts Link Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'All Posts',
				'condition' => [
					'author_posts_link' => [ 'yes' ],
				],
			]
		);

        $this->add_control(
            'author_name_link_tab',
            [
                'label' => esc_html__( 'Author Name Link Tab', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'author_posts_link' => [ 'yes' ],
                ],

            ]
        );
        $this->add_control(
            'author_title_link_tab',
            [
                'label' => esc_html__( 'Author Link Tab', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'author_posts_link' => [ 'yes' ],
                ],
            ]
        );

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Avatar -----------
		$this->start_controls_section(
			'section_style_avatar',
			[
				'label' => esc_html__( 'Avatar', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'avatar_align',
			[
				'label' => esc_html__( 'Center Image Vertically', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'selectors_dictionary' => [
					'' => '',
					'yes' => 'align-self: center;',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-author-box-image' => '{{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'avatar_size',
			[
				'label' => esc_html__( 'Image Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 65,
				],
				'range' => [
					'px' => [
						'min' => 16,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-author-box-image img' => 'width: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'avatar_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.crt-author-box-arrange-vertical .crt-author-box-image' => 'margin-bottom: {{SIZE}}px',
					'{{WRAPPER}}.crt-author-box-arrange-left .crt-author-box-image' => 'margin-right: {{SIZE}}px',
					'{{WRAPPER}}.crt-author-box-arrange-right .crt-author-box-image' => 'margin-left: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

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
				'selector' => '{{WRAPPER}} .crt-author-box-image img',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'avatar_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-author-box-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'avatar_shadow',
				'selector' => '{{WRAPPER}} .crt-author-box-image',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Name -------------
		$this->start_controls_section(
			'section_style_name',
			[
				'label' => esc_html__( 'Name', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-author-box-name' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-author-box-name a' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .crt-author-box-name',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '500',
					],
					'font_size' => [
						'default' => [
							'size' => '18',
							'unit' => 'px',
						],
					],
					'letter_spacing' => [
						'default' => [
							'size' => '0.2'
						]
					],
				]
			]
		);

		$this->add_responsive_control(
			'name_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-author-box-name' => 'margin-top: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'name_bot_distance',
			[
				'label' => esc_html__( 'Bottom Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-author-box-name' => 'margin-bottom: {{SIZE}}px',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Title -------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-author-box-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-author-box-title a' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .crt-author-box-title',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight'    => [
						'default' => '500',
					],
					'font_size'      => [
						'default'    => [
							'size' => '15',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_responsive_control(
			'title_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-author-box-title' => 'margin-top: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_bot_distance',
			[
				'label' => esc_html__( 'Bottom Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-author-box-title' => 'margin-bottom: {{SIZE}}px',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Biography --------
		$this->add_section_style_bio();

		// Styles ====================
		// Section: Author Posts Link
		$this->start_controls_section(
			'section_style_archive_link',
			[
				'label' => esc_html__( 'Author Posts Link', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_archive_link_style' );

            $this->start_controls_tab(
                'tab_grid_archive_link_normal',
                [
                    'label' => esc_html__( 'Normal', 'crt-manage' ),
                ]
            );

                $this->add_control(
                    'archive_link_color',
                    [
                        'label'  => esc_html__( 'Color', 'crt-manage' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#ffffff',
                        'selectors' => [
                            '{{WRAPPER}} .crt-author-box-btn' => 'color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_control(
                    'archive_link_bg_color',
                    [
                        'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#e55b5b',
                        'selectors' => [
                            '{{WRAPPER}} .crt-author-box-btn' => 'background-color: {{VALUE}}',
                        ]
                    ]
                );

                $this->add_control(
                    'archive_link_border_color',
                    [
                        'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .crt-author-box-btn' => 'border-color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name'     => 'archive_link_typography',
                        'selector' => '{{WRAPPER}} .crt-author-box-btn'
                    ]
                );

                $this->add_control(
                    'archive_link_transition_duration',
                    [
                        'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                        'type' => Controls_Manager::NUMBER,
                        'default' => 0.1,
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                        'selectors' => [
                            '{{WRAPPER}} .crt-author-box-btn' => 'transition-duration: {{VALUE}}s',
                        ],
                    ]
                );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'tab_grid_archive_link_hover',
                [
                    'label' => esc_html__( 'Hover', 'crt-manage' ),
                ]
            );

                $this->add_control(
                    'archive_link_color_hr',
                    [
                        'label'  => esc_html__( 'Color', 'crt-manage' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#ffffff',
                        'selectors' => [
                            '{{WRAPPER}} .crt-author-box-btn:hover' => 'color: {{VALUE}}',
                            '{{WRAPPER}} .crt-author-box-btn:hover a' => 'color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_control(
                    'archive_link_bg_color_hr',
                    [
                        'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .crt-author-box-btn:hover' => 'background-color: {{VALUE}}',
                        ]
                    ]
                );

                $this->add_control(
                    'archive_link_border_color_hr',
                    [
                        'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .crt-author-box-btn:hover' => 'border-color: {{VALUE}}',
                        ],
                    ]
                );

            $this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'archive_link_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 15,
					'bottom' => 5,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-author-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'archive_link_border_type',
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
					'{{WRAPPER}} .crt-author-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'archive_link_border_width',
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
					'{{WRAPPER}} .crt-author-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'archive_link_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'archive_link_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-author-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		// Get Author Info
		$id = get_the_author_meta( 'ID' );
		$avatar = get_avatar( $id, 264 );
		$name = get_the_author_meta( 'display_name' );
		$title = $settings['author_title_text'];
		$biography = get_the_author_meta( 'description' );
		$website = get_the_author_meta( 'user_url' );
		$archive_url = get_author_posts_url( $id );
		$author_name_link = 'website' === $settings['author_name_links_to'] ? $website : $archive_url;
		$author_name_target = 'yes' === $settings['author_name_link_tab'] ? '_blank' : '_self';
		$author_name_has_website = 'website' === $settings['author_name_links_to'] && '' !== $website ? true : false;
		$author_title_link = 'website' === $settings['author_title_links_to'] ? $website : $archive_url;
		$author_title_target = 'yes' === $settings['author_title_link_tab'] ? '_blank' : '_self';
		$author_title_has_website = 'website' === $settings['author_title_links_to'] && '' !== $website ? true : false;
		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];

		// HTML
		echo '<div class="crt-author-box">';

			// Avatar
			if ( '' !== $settings['author_avatar'] && false !== $avatar ) {
				echo '<div class="crt-author-box-image">';
					if ( 'posts' === $settings['author_name_links_to'] || $author_name_has_website ) {
						echo '<a href="'. esc_url( $author_name_link ) .'" target="'. esc_attr($author_name_target) .'">'. wp_kses_post($avatar) .'</a>';
					} else {
						echo wp_kses_post($avatar);
					}
				echo '</div>';
			}

			// Wrap All Text Blocks
			echo '<div class="crt-author-box-text">';

			// Author Name
			if ( '' !== $settings['author_name'] && '' !== $name ) {
				$author_name_tag = Utilities::validate_html_tags_wl( $settings['author_name_tag'], 'h3', $tags_whitelist );

				echo '<'. esc_attr($author_name_tag) .' class="crt-author-box-name">';
					if ( 'posts' === $settings['author_name_links_to'] || $author_name_has_website ) {
						echo '<a href="'. esc_url( $author_name_link ) .'" target="'. esc_attr($author_name_target) .'">'. esc_html($name) .'</a>';
					} else {
						echo esc_html($name);
					}
				echo '</'. esc_attr($author_name_tag) .'>';
			}

			// Author Title
			if ( '' !== $title && 'yes' === $settings['author_title'] ) {
				$author_title_tag = Utilities::validate_html_tags_wl( $settings['author_title_tag'], 'h3', $tags_whitelist );

				echo '<'. esc_attr($author_title_tag) .' class="crt-author-box-title">';
					if ( 'posts' === $settings['author_title_links_to'] || $author_title_has_website ) {
						echo '<a href="'. esc_url( $author_title_link ) .'" target="'. esc_attr($author_title_target) .'">'. wp_kses_post($title) .'</a>';
					} else {
						echo wp_kses_post($title);
					}
				echo '</'. esc_attr($author_title_tag) .'>';
			}

			// Author Biography
			if ( '' !== $settings['author_bio'] && '' !== $biography ) {
				echo '<p class="crt-author-box-bio">'. wp_kses_post($biography) .'</p>';
			}

			// Author Posts Link
			if ( '' !== $settings['author_posts_link'] ) {
				echo '<a href="'. esc_url( $archive_url ) .'" class="crt-author-box-btn">';
					echo esc_html( $settings['author_posts_link_text'] );
				echo '</a>';
			}

			echo '</div>'; // End .crt-author-box-text

		echo '</div>';
	}
	
}