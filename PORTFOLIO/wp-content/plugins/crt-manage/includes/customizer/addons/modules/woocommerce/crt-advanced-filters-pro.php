<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use CrtAddons\Classes\Utilities;
use CrtAddons\Classes\Modules\CRT_Post_Likes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Advanced_Filters_Pro extends Widget_Base {

	public function get_name() {
		return 'crt-advanced-filters-pro';
	}

	public function get_title() {
		return esc_html__( 'Advanced Filters', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-filter';
	}

	public function get_categories() {
        return [ 'crt_manage_woocommerce' ];
    }

	public function get_keywords() {
		return ['advanced filters', 'smart filters', 'grid filters'];
	}

    public function get_script_depends() {
        return [ 'crt-advanced-filters-pro' ];
    }

	// public function get_script_depends() {
	// 	return [ 'crt-date-picker-js' ];
	// }

	// public function get_style_depends() {
	// 	return [ 'crt-date-picker-css' ];
	// }

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-woo-grid-help-btn';
    		return 'https://crthemes.com';
    }

    // last HTML ourtput (private to avoid outer access)
	private $output;

	private $dependent_count;

	private $filter_item;

    public function register_controls() {
		$this->start_controls_section(
			'section_general',
            [
                'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
            ]
		);

//		$this->add_control(
//			'advanced_filters_video_tutorial',
//			[
//				'type' => Controls_Manager::RAW_HTML,
//				'raw' => __( 'Build powerful Advanced Filters <strong> with Elementor and CRT Builder !</strong> <ul><li><a href="" target="_blank" style="color: #93003c;"><strong>Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></strong></a></li></ul>', 'crt-manage' ),
//				'separator' => 'after',
//				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
//			]
//		);
        
		$post_types = Utilities::get_custom_types_of( 'post', false );

		foreach ( $post_types as $key => $value ) {
            $post_types[$key] = $value .'';
		}

		$this->add_control(
			'filter_type',
			[
				'label' => esc_html__( 'Filter Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
                    'select' => esc_html__( 'Select', 'crt-manage' ),
                    'checkbox' => esc_html__( 'Checkbox', 'crt-manage' ),
                    'radio' => esc_html__( 'Radio', 'crt-manage' ),
                    'range' => esc_html__( 'Range', 'crt-manage' ),
                    // 'date' => esc_html__( 'Date', 'crt-manage' ),
                    // 'date_range' => esc_html__( 'Date Range', 'crt-manage' ),
					'rating' => esc_html__( 'Rating', 'crt-manage' ),
                    'apply' => esc_html__( 'Apply Button', 'crt-manage' ),
                    'active' => esc_html__( 'Active Filters', 'crt-manage' )
                ],
				'default' => 'checkbox',
                'render_type' => 'template'
			]
		);

		$this->add_control(
			'filters_query',
			[
				'label' => esc_html__( 'Select Query', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => $post_types,
				'default' => 'post',
				'condition' => [
					'filter_type!' => ['active', 'apply'],
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'filter_data',
			[
				'label' => esc_html__( 'Filter By', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
                    'taxonomy' => 'Taxonomy',
                    'meta_field' => 'Meta Field',
                    // 'publish_date' => 'Date',
					'price' => 'Price (Products)',
                ],
				'default' => 'taxonomy',
				'condition' => [
					'filter_type!' => ['active', 'apply', 'rating'],
				],
				'render_type' => 'template'
			]
		);

		// Taxonomies
		foreach ( $post_types as $slug => $title ) {
			$this->add_control(
				'query_taxonomy_'. $slug,
				[
					'label' => esc_html__( $title. ' Taxonomies', 'crt-manage' ),
					'type' => 'crt-ajax-select2',
					'default' => 'category',
					'options' => 'ajaxselect2/get_post_type_taxonomies',
					'query_slug' => $slug,
					'label_block' => true,
					'conditions' => [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'filter_type',
										'operator' => '!==',
										'value' => 'select'
									],
									[
										'name' => 'filters_query',
										'operator' => '===', 
										'value' => $slug
									],
									[
										'name' => 'filter_data',
										'operator' => '===',
										'value' => 'taxonomy'
									],
									[
										'name' => 'filter_type',
										'operator' => '!in',
										'value' => ['active', 'range', 'rating', 'apply']
									]
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'filter_type',
										'operator' => '===',
										'value' => 'select'
									],
									[
										'name' => 'enable_dependent_select',
										'operator' => '!==',
										'value' => 'yes'
									],
									[
										'name' => 'filters_query',
										'operator' => '===',
										'value' => $slug
									],
									[
										'name' => 'filter_data',
										'operator' => '===',
										'value' => 'taxonomy'
									],
									[
										'name' => 'filter_type',
										'operator' => '!in',
										'value' => ['active', 'range', 'rating', 'apply']
									]
								]
							]
						]
					]
				]
			);
		}

        // if ( is_plugin_active('WooCommerce') ) { // TODO: REMOVE CONDITION AND APPLY INNER CONDITIONS
        //     $this->add_control(
        //         'cf_for_products',
        //         [
        //             'label' => esc_html__( 'Select Custom Field (Products)', 'crt-manage' ),
        //             // 'type' => Controls_Manager::SELECT2,
        //             'type' => 'crt-ajax-select2',
        //             'label_block' => true,
        //             'default' => 'default',
        //             // 'options' => $post_meta_keys[1],
        //             'options' => 'ajaxselect2/get_custom_meta_keys_product',
        //             'query_slug' => 'product_cat',
        //             'condition' => [
        //                 'filter_data' => 'meta_field',
        //                 'filter_type!' => [
        //                     'active', 'apply'
        //                 ] 
        //             ],
        //         ]
        //     );
        // }
		
		$this->add_control(
			'cf_for_all_post_types',
			[
				'label' => esc_html__( 'Select Custom Field', 'crt-manage' ),
				// 'type' => Controls_Manager::SELECT2,
				'type' => 'crt-ajax-select2',
				'label_block' => true,
				'default' => 'default',
				// 'options' => $post_meta_keys[1],
				'options' => 'ajaxselect2/get_custom_meta_keys',
				'condition' => [
					'filter_data' => 'meta_field',
					'filter_type!' => [
						'active', 'apply', 'rating'
					]
				],
			]
		);

		$this->add_control(
			'tax_query_type',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Relation', 'crt-manage' ),
				'description' => __( 'Choose how selected values in this filter work: <br>"AND" = all must match, "OR" = any', 'crt-manage' ),
				'options' => [
					'and' => esc_html__( 'AND', 'crt-manage' ),
					'or' => esc_html__( 'OR', 'crt-manage' ),
				],
				'default' => 'or',
				'condition' => [
					'filter_type' => [
						'checkbox'
					] 
				],
			]
		);

		$this->add_control(
			'enable_ajax',
			[
				'label' => esc_html__( 'Enable AJAX', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
				'render_type' => 'template'
			]
		);
		
		$this->add_control(
			'redirect',
			[
				'label' => esc_html__( 'Apply Button Redirect', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => [
					'filter_type' => 'apply'
				]
			]
		);
		
		$this->add_control(
			'redirect_url',
			[
				'label' => esc_html__( 'Redirect PATH', 'crt-manage' ),
				'description' => esc_html__( 'Shouldn\'t include base part of URL', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'default' => '#',
				'condition' => [
					'filter_type' => 'apply',
					'redirect' => 'yes'
				]
			]
		);

		$this->add_control(
			'enable_visual_filters',
			[
				'label' => esc_html__( 'Enable Visual Filters', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'separator' => 'before',
				'render_type' => 'template',
                'condition' => [
                    'filter_type' => [
                        'checkbox', 'radio'
                    ]
                ]
			]
		);

		$this->add_control(
			'visual_filter_type',
			[
				'label' => esc_html__( 'Visual Filter Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
                    'color' => esc_html__( 'Color', 'crt-manage' ),
                    'image' => esc_html__( 'Image', 'crt-manage' )
                ],
				'default' => 'color',
                'render_type' => 'template',
                'condition' => [
                    'enable_visual_filters' => 'yes',
                    'filter_type' => [
                        'checkbox', 'radio'
                    ]
                ]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'thumbnail',
                'condition' => [
                    'enable_visual_filters' => 'yes',
					'visual_filter_type' => 'image',
                    'filter_type' => [
                        'checkbox', 'radio'
                    ]
                ]
			]
		);

		$this->add_control(
			'enable_visual_hierarchy',
			[
				'label' => esc_html__( 'Enable Visual Hierarchy', 'crt-manage' ),
				'description' => esc_html__( 'Adds indent to show parent-child levels.', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'separator' => 'before',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filter_type',
									'operator' => '===',
									'value' => 'select'
								]
							]
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filter_type',
									'operator' => 'in',
									'value' => ['checkbox', 'radio']
								],
								[
									'name' => 'enable_visual_filters',
									'operator' => '!==',
									'value' => 'yes'
								]
							]
						]
					]
				]
			]
		);

		$this->add_control(
			'enable_dependent_select',
			[
				'label' => esc_html__( 'Enable Dependent Select', 'crt-manage' ),
				'description' => esc_html__( 'Only works with "Filter by: Taxonomies".', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'separator' => 'before',
				'condition' => [
					'filter_type' => 'select',
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		// Taxonomies
		$repeater->add_control(
			'dependent_select_taxonomy',
			[
				'label' => esc_html__( 'Taxonomy', 'crt-manage' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_taxonomies_list(),
			]
		);

		$repeater->add_control(
			'dependent_select_label',
			[
				'label' => esc_html__( 'Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Select',
			]
		);

		$repeater->add_control(
			'dependent_select_placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Any...',
			]
		);

		$this->add_control(
			'dependent_select_repeater',
			[
				'label' => esc_html__( 'Dependent Filters', 'crt-manage' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'dependent_select_taxonomy' => '',
						'dependent_select_label' => esc_html__( 'Filter Label', 'crt-manage' ),
						'dependent_select_placeholder' => esc_html__( 'Any...', 'crt-manage' )
					]
				],
				'title_field' => '{{{ dependent_select_taxonomy }}}',
				'condition' => [
					'enable_dependent_select' => 'yes',
					'filter_type' => 'select',
					'filter_data' => 'taxonomy'
				]
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filter_type',
									'operator' => '!==',
									'value' => 'select'
								]
							]
						],
						[
							'relation' => 'and', 
							'terms' => [
								[
									'name' => 'filter_type',
									'operator' => '===',
									'value' => 'select'
								],
								[
									'name' => 'enable_dependent_select',
									'operator' => '!==',
									'value' => 'yes'
								]
							]
						]
					]
				]
			]
		);
		
		$this->add_control(
			'show_label',
			[
				'label' => esc_html__( 'Show Label', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filter_type',
									'operator' => '!==',
									'value' => 'select'
								],
							]
						],
						[
							'relation' => 'and', 
							'terms' => [
								[
									'name' => 'filter_type',
									'operator' => '===',
									'value' => 'select'
								],
								[
									'name' => 'enable_dependent_select',
									'operator' => '!==',
									'value' => 'yes'
								]
							]
						]
					]
				]
			]
		);
		
		$this->add_control(
			'label_text',
			[
				'label' => esc_html__( 'Filter Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Filter Label',
				'condition' => [
					'show_label' => 'yes',
				]
			]
		);
		
		$this->add_control(
			'select_field_placeholder',
			[
				'label' => esc_html__( 'Select Placeholder', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'None',
				'separator' => 'before',
				'condition' => [
					'filter_type' => [
						'select'
					],
				]
			]
		);
		
		$this->add_control(
			'enable_more_less',
			[
				'label' => esc_html__( 'Enable More/Less', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'separator' => 'before',
				'prefix_class' => 'crt-view-more-less-',
				'render_type' => 'template',
				'condition' => [
					'filter_type' => [
						'checkbox', 'radio'
					]
				]
			]
		);
		
		$this->add_control(
			'more_less_item_count',
			[
				'label' => esc_html__( 'Show Items', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'min' => 1,
				'step' => 1,
				'condition' => [
					'enable_more_less' => 'yes',
					'filter_type!' => ['active', 'search', 'range', 'rating', 'apply', 'select', 'date_range']
				]
			]
		);
		
		$this->add_control(
			'more_text',
			[
				'label' => esc_html__( 'Show More', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Show More',
				'condition' => [
					'filter_type' => [
						'checkbox', 'radio'
					],
					'enable_more_less' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'less_text',
			[
				'label' => esc_html__( 'Show Less', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Show Less',
				'condition' => [
					'filter_type' => [
						'checkbox', 'radio'
					],
					'enable_more_less' => 'yes'
				]
			]
		);

		$this->add_control(
			'rating_style',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' => 'Icon 1',
					'style-2' => 'Icon 2',
				],
				'default' => 'style-2',
				'separator' => 'before',
				'condition' => [
					'filter_type' => 'rating',
				],
			]
		);

		$this->add_control(
			'rating_unmarked_style',
			[
				'label' => esc_html__( 'Unmarked Style', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'solid' => [
						'title' => esc_html__( 'Solid', 'crt-manage' ),
						'icon' => 'eicon-star',
					],
					'outline' => [
						'title' => esc_html__( 'Outline', 'crt-manage' ),
						'icon' => 'eicon-star-o',
					],
				],
				'default' => 'outline',
				'condition' => [
					'filter_type' => 'rating',
				],
			]
		);
		
		$this->add_control(
			'show_range_apply',
			[
				'label' => esc_html__( 'Show Apply Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
				'condition' => [
					'filter_type' => ['range']
				]
			]
		);
		
		$this->add_control(
			'range_apply_text',
			[
				'label' => esc_html__( 'Apply Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Apply',
				'condition' => [
					'show_range_apply' => 'yes',
					'filter_type' => ['range']
				]
			]
		);
		
		$this->add_control(
			'show_range_inputs',
			[
				'label' => esc_html__( 'Show Range Inputs', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
				'render_type' => 'template',
				'condition' => [
					'filter_type' => ['range']
				]
			]
		);
		
		$this->add_control(
			'range_value_prefix',
			[
				'label' => esc_html__( 'Range Value Prefix', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'filter_type' => ['range'],
					'show_range_inputs!' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'range_value_suffix',
			[
				'label' => esc_html__( 'Range Value Suffix', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'filter_type' => ['range'],
					'show_range_inputs!' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'apply_text',
			[
				'label' => esc_html__( 'Apply Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Apply All',
				'separator' => 'before',
				'condition' => [
					'filter_type' => 'apply'
				]
			]
		);
		
		$this->add_control(
			'reset_all_text',
			[
				'label' => esc_html__( 'Reset Filters Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Reset Filters',
				'separator' => 'before',
				'condition' => [
					'filter_type' => 'active'
				]
			]
		);
		
        $this->end_controls_section();

		$this->start_controls_section(
			'section_visual_filters_repeater',
			[
				'label' => esc_html__( 'Visual Filters', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'enable_visual_filters' => 'yes',
                    'visual_filter_type' => ['image', 'color'],
                    'filter_type' => [ 'checkbox', 'radio' ]
                ]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'vs_filters_title', [
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( '' , 'crt-manage' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'vs_filters_replace_value', [
				'label' => esc_html__( 'Replace Value', 'crt-manage' ),
				'description' => esc_html__( 'Enter Meta Field value or Taxonomy Term slug to replace.', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'render_type' => 'template',
				'default' => esc_html__( '' , 'crt-manage' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'vs_filters_image',
			[
				'label' => esc_html__( 'Choose Image', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'skin' => 'inline',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'vs_filters_color',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.crt-af-visual',
				'fields_options' => [
					'background' => [ 'default' => 'classic' ],
					'color' => [ 'default' => '' ],
				],
			]
		);

		$this->add_control(
			'vs_filters',
			[
				'label' => esc_html__( 'Repeater List', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ vs_filters_title }}}',
				'default' => [
					[
						'vs_filters_title' => esc_html__( 'First Item', 'crt-manage' ),
					],
                ]
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_advanced',
			[
				'label' => esc_html__( 'Advanced', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'filter_type' => ['checkbox', 'radio', 'select', 'rating'],
				]
			]
		);

		$this->add_control(
			'enable_dependency',
			[
				'label' => esc_html__( 'Enable Dependency', 'crt-manage' ),
				'description' => esc_html__( 'Current filter will respond to the global query, handling empty options and updating the count.', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'filter_type' => ['checkbox', 'radio', 'select'],
				]
			]
		);

		$this->add_control(
			'show_count',
			[
				'label' => esc_html__( 'Show Count', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'filter_type' => ['checkbox', 'radio', 'select'],
				]
			]
		);

		$this->add_control(
			'display_count_aside',
			[
				'label' => esc_html__( 'Display Count Aside', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'selectors_dictionary' => [
					'' => '5px',
					'yes' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-count' => 'margin-left: {{VALUE}}',
				],
				'condition' => [
					'show_count' => 'yes',
					'filter_type' => ['checkbox', 'radio', 'select'],
				]
			]
		);

		$this->add_control(
			'change_counter',
			[
				'label' => esc_html__( 'Change Counter', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'always' => esc_html__( 'Always', 'crt-manage' ),
					'other_filters' => esc_html__( 'Other Filters Changed', 'crt-manage' ),
				],
				'default' => 'always',
				'render_type' => 'template',
				'condition' => [
					'enable_ajax' => 'yes',
					'enable_dependency' => 'yes',
					'show_count' => 'yes',
					'filter_type' => ['checkbox', 'radio', 'select'],
				]
			]
		);

		$this->add_control(
			'hide_empty', // TODO: REMOVE LATER AFTER EXPLORING
			[
				'label' => esc_html__( 'Hide Empty', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => true,
				'condition' => [
					'enable_ajax!' => 'yes',
					'filter_type' => ['checkbox', 'radio', 'select'],
				]
			]
		);

		$this->add_control(
			'handle_empty', // TODO: CHOOSE ONE OF THEM
			[
				'label' => esc_html__( 'Handle Empty', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'condition' => [
					'enable_ajax' => 'yes',
					'filter_type' => ['checkbox', 'radio', 'select'],
				]
			]
		);

		$this->add_control(
			'empty_actions',
			[
				'label' => esc_html__( 'Empty Actions', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'disable' => esc_html__( 'Disable', 'crt-manage' ),
					'hide' => esc_html__( 'Hide', 'crt-manage' ),
				],
				'default' => 'disable',
				'condition' => [
					'handle_empty' => 'yes',
					'enable_ajax' => 'yes',
					'filter_type!' => ['active', 'search', 'range', 'rating', 'apply'],
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_label',
			[
				'label' => esc_html__( 'Label', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-af-filters-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .crt-af-filters-label',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '18',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'label_distance',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-filters-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
            'label_alignment',
            [
                'label' => esc_html__( 'Alignment', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'label_block' => false,
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
					'{{WRAPPER}} .crt-af-filters-label' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

        $this->end_controls_section();

		$this->start_controls_section(
			'section_style_fields_group',
			[
				'label' => esc_html__( 'Layout', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_type' => ['select', 'checkbox', 'radio', 'active'],
				]
			]
		);

		$this->add_control(
            'fields_group_alignment',
            [
                'label' => esc_html__( 'Alignment', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'column',
                'options' => [
                    'row' => [
                        'title' => esc_html__( 'Horizontal', 'crt-manage' ),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                    'column' => [
                        'title' => esc_html__( 'Vertical', 'crt-manage' ),
                        'icon' => 'eicon-menu-bar',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-inner, {{WRAPPER}} .crt-af-active-filters, {{WRAPPER}} .crt-af-active-filters [class^="crt-af-active-wrap-"]' => 'flex-direction: {{VALUE}}',
				],
				'prefix_class' => 'crt-af-fields-group-alignment-',
            ]
        );

		$this->add_responsive_control(
			'fields_group_wrap',
			[
				'label' => esc_html__( 'Flex Wrap', 'crt-manage' ),
				'description' => esc_html__( 'Display items/fields on multiple lines.', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'no' => 'nowrap',
					'yes' => 'wrap'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-inner' => 'flex-wrap: {{VALUE}}',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'filter_type',
							'operator' => '==',
							'value' => 'select',
						],
						[
							'name' => 'fields_group_alignment',
							'operator' => '==',
							'value' => 'row',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'fields_group_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'description' => esc_html__( 'Leave the width empty to use auto width.', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-inner > *' => 'width: {{SIZE}}%',
				],
				'condition' => [
					'filter_type!' => 'active',
					'fields_group_alignment' => 'row',
					'enable_visual_filters!' => 'yes' // this should be removed for image filter to work perfectly but needs to be disabled for color filters.
				],
			]
		);

		$this->add_responsive_control(
			'fields_group_gutter_hr',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'crt-manage' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-inner, {{WRAPPER}} .crt-af-active-filters, {{WRAPPER}} .crt-af-active-filters [class^="crt-af-active-wrap-"]' => 'column-gap: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'fields_group_alignment' => 'row',
				],
			]
		);

		$this->add_responsive_control(
			'fields_group_gutter_vr',
			[
				'label' => esc_html__( 'Vertical Gutter', 'crt-manage' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-inner, {{WRAPPER}} .crt-af-active-filters, {{WRAPPER}} .crt-af-active-filters [class^="crt-af-active-wrap-"]' => 'row-gap: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'fields_group_wrap',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'fields_group_alignment',
							'operator' => '==',
							'value' => 'row',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'fields_group_gutter_vr_for_vr',
			[
				'label' => esc_html__( 'Vertical Gutter', 'crt-manage' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-inner, {{WRAPPER}} .crt-af-active-filters, {{WRAPPER}} .crt-af-active-filters [class^="crt-af-active-wrap-"]' => 'row-gap: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'fields_group_wrap',
							'operator' => '!==',
							'value' => 'yes',
						],
						[
							'name' => 'fields_group_alignment',
							'operator' => '==',
							'value' => 'column',
						],
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_select',
			[
				'label' => esc_html__( 'Select', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_type' => ['select']
				]
			]
		);

		$this->add_control(
			'select_field_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-wrap select' => 'color: {{VALUE}};',
					// '{{WRAPPER}} .crt-field-group input[type="radio"] + label' => 'color: {{VALUE}};',
					// '{{WRAPPER}} .crt-field-group input[type="checkbox"] + label' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'select_field_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-wrap select' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'select_field_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-wrap select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-af-select-wrap::before' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'select_field_typography',
				'selector' => '{{WRAPPER}} .crt-advanced-filters-wrap select',
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
			'select_field_border_type',
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
					'{{WRAPPER}} .crt-advanced-filters-wrap select' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'select_field_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-wrap select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'select_field_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'select_field_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-wrap select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'select_field_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 6,
					'right' => 5,
					'bottom' => 7,
					'left' => 5,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-wrap select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'select_field_disable_appearance',
			[
				'label' => esc_html__( 'Disable Default Appearance', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'selectors_dictionary' => [
					'yes' => 'appearance: none; -webkit-appearance: none; -moz-appearance: none;',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-wrap select' => '{{VALUE}}',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Checkbox & Radio Item ------------
		$this->start_controls_section(
			'section_style_check_radio_item',
			[
				'label' => esc_html__( 'Checkbox & Radio Item', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_type' => ['checkbox', 'radio'],
					'enable_visual_filters!' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'check_radio_hide_input',
			[
				'label' => esc_html__( 'Hide Input', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'prefix_class' => 'crt-af-hide-input-',
				'separator' => 'after',
				'render_type' => 'template'
			]
		);
		
		$this->start_controls_tabs( 'check_radio_style' );
		
		$this->start_controls_tab(
			'check_radio_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'check_radio_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap' => 'color: {{VALUE}}'
				],
			]
		);
		
		$this->add_control(
			'check_radio_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap' => 'background-color: {{VALUE}}',
				]
			]
		);
		
		$this->add_control(
			'check_radio_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'check_radio_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'check_radio_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap:hover' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'check_radio_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap:hover' => 'background-color: {{VALUE}}',
				]
			]
		);
		
		$this->add_control(
			'check_radio_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap:hover' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'check_radio_active',
			[
				'label' => esc_html__( 'Active', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'check_radio_color_act',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap.crt-checked' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'check_radio_bg_color_act',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap.crt-checked' => 'background-color: {{VALUE}}',
				]
			]
		);
		
		$this->add_control(
			'check_radio_border_color_act',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap.crt-checked' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_control(
			'check_radio_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'check_radio_typography',
				'selector' => '{{WRAPPER}} .crt-af-check-radio-group .crt-af-term-name',
				'selector' => '{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap',
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
			'check_radio_border_type',
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
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'check_radio_border_width',
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
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'check_radio_border_type!' => 'none',
				],
			]
		);
		
		$this->add_control(
			'check_radio_radius',
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
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);
		
		$this->add_responsive_control(
			'check_radio_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-input-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
            'check_radio_alignment',
            [
                'label' => esc_html__( 'Alignment', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'label_block' => false,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .crt-af-input-wrap' => 'justify-content: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );
		
		$this->end_controls_section();

		// Styles
		// Section: Checkbox & Radio Input ------------
		$this->start_controls_section(
			'section_style_check_radio_input',
			[
				'label' => esc_html__( 'Checkbox & Radio Input', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_type' => ['checkbox', 'radio'],
					'check_radio_hide_input!' => 'yes',
					'enable_visual_filters!' => 'yes'
				]
			]
		);

		$this->add_control(
			'check_radio_custom_styles',
			[
				'label' => esc_html__( 'Use Custom Styles', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'crt-custom-styles-'
			]
		);

		$this->add_control(
			'check_radio_custom_static_color',
			[
				'label' => esc_html__( 'Static Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-term-name:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'check_radio_custom_styles' => 'yes'
				]
			]
		);

		$this->add_control(
			'check_radio_custom_active_color',
			[
				'label' => esc_html__( 'Active Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}}  .crt-af-check-radio-group .crt-af-term-name:before' => 'color: {{VALUE}}',
				],
				'condition' => [
					'check_radio_custom_styles' => 'yes'
				]
			]
		);

		$this->add_control(
			'check_radio_custom_active__bg_color',
			[
				'label' => esc_html__( 'Active Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000ff',
				'selectors' => [
					'{{WRAPPER}}  .crt-af-check-radio-group .crt-checked .crt-af-term-name:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'check_radio_custom_styles' => 'yes'
				]
			]
		);

		$this->add_control(
			'check_radio_custom_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}}  .crt-af-check-radio-group .crt-af-term-name:before' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'check_radio_custom_styles' => 'yes'
				]
			]
		);

		$this->add_control(
			'check_radio_custom_border_type',
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
					'{{WRAPPER}} .crt-af-check-radio-group input' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-term-name:before' => 'border-style: {{VALUE}};'
				],
				'condition' => [
					'check_radio_custom_styles' => 'yes'
				]
			]
		);

		$this->add_control(
			'check_radio_custom_border_width',
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
					'{{WRAPPER}} .crt-af-check-radio-group input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-term-name:before' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'check_radio_custom_border_type!' => 'none',
					'check_radio_custom_styles' => 'yes'
				],
			]
		);

		$this->add_responsive_control(
			'check_radio_custom_bg_size',
			[
				'label' => esc_html__( 'Input Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group input' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.crt-custom-styles-yes .crt-af-check-radio-group .crt-af-term-name::before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};  line-height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 1.3);'
				],
				'condition' => [
					'check_radio_custom_styles' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'check_radio_custom_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 15,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group input' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-custom-styles-yes .crt-af-check-radio-group .crt-af-term-name::before' => 'border-radius: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'filter_type' => 'checkbox',
					'check_radio_custom_styles' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'radio_custom_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 15,
					],
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group input' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-custom-styles-yes .crt-af-check-radio-group .crt-af-term-name::before' => 'border-radius: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'filter_type' => 'radio',
					'check_radio_custom_styles' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'check_radio_custom_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-check-radio-group input' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-af-check-radio-group .crt-af-term-name:before' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();
        
		// Styles
		// Section: Visual Filters Item ------------
		$this->start_controls_section(
			'section_style_visual_item',
			[
				'label' => esc_html__( 'Visual Filters Item', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_type' => ['checkbox', 'radio'],
					'enable_visual_filters' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'visual_item_hide_label',
			[
				'label' => esc_html__( 'Hide Label', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'selectors_dictionary' => [
					'yes' => 'none',
					'' => 'block',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-group .crt-af-term-name, {{WRAPPER}} .crt-af-visual-group .crt-af-count' => 'display: {{VALUE}};',
				],
				'render_type' => 'template',
				'prefix_class' => 'crt-af-hide-label-',
				'separator' => 'after',
			]
		);
		
		$this->start_controls_tabs( 'visual_item_style' );
		
		$this->start_controls_tab(
			'visual_item_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'visual_item_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-group .crt-af-visual-wrap' => 'color: {{VALUE}}'
				],
				'condition' => [
					'visual_item_hide_label!' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'visual_item_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-group .crt-af-visual-wrap' => 'background-color: {{VALUE}}',
				]
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'visual_item_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'visual_item_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-group .crt-af-visual-wrap:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					'visual_item_hide_label!' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'visual_item_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-group .crt-af-visual-wrap:hover' => 'background-color: {{VALUE}}',
				]
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'visual_item_active',
			[
				'label' => esc_html__( 'Active', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'visual_item_color_act',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-group .crt-af-visual-wrap.crt-af-visual-active' => 'color: {{VALUE}}',
				],
				'condition' => [
					'visual_item_hide_label!' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'visual_item_bg_color_act',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-group .crt-af-visual-wrap.crt-af-visual-active' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_control(
			'visual_item_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-group .crt-af-visual-wrap' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'visual_item_typography',
				'selector' => '{{WRAPPER}} .crt-af-visual-group .crt-af-term-name',
				'selector' => '{{WRAPPER}} .crt-af-visual-group .crt-af-visual-wrap',
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
				],
				'condition' => [
					'visual_item_hide_label!' => 'yes'
				]
			]
		);
		
		$this->end_controls_section();
        
		// Styles
		// Section: Visual Filters Color & Image ------------
		$this->start_controls_section(
			'section_style_visual_color_image',
			[
				'label' => esc_html__( 'Visual Filters Color & Image', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_visual_filters' => 'yes',
					'filter_type' => ['checkbox', 'radio']
				]
			]
		);
		
		$this->start_controls_tabs( 'visual_color_image_style' );
		
		$this->start_controls_tab(
			'visual_color_image_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'visual_color_image_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF00',
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-color-wrap .crt-af-visual' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-img-wrap' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'visual_color_image_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'visual_color_image_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-color-wrap:hover .crt-af-visual' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-img-wrap:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'visual_color_image_active',
			[
				'label' => esc_html__( 'Active', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'visual_color_image_border_color_act',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-color-wrap.crt-af-visual-active .crt-af-visual' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-img-wrap.crt-af-visual-active' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'visual_color_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-color-wrap .crt-af-visual' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'visual_filter_type' => 'color'
				]
			]
		);

		$this->add_responsive_control(
			'visual_image_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-wrap img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'visual_filter_type' => 'image'
				]
			]
		);
		
		$this->add_control(
			'visual_color_image_border_type',
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
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-color-wrap .crt-af-visual' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-img-wrap' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'visual_color_image_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-color-wrap .crt-af-visual' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-img-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'visual_color_image_border_type!' => 'none',
				],
			]
		);
		
		$this->add_control(
			'visual_color_image_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-color-wrap .crt-af-visual' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-img-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);
		
		$this->add_responsive_control(
			'visual_image_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-visual-wrap.crt-af-visual-img-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
				'condition' => [
					'visual_filter_type' => 'image'
				]
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Range Slider ------------
		$this->start_controls_section(
			'section_style_range_slider',
			[
				'label' => esc_html__( 'Range Slider', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_type' => 'range'
				]
			]
		);

		$this->add_control(
			'range_slider_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'frontend_available' => true,
				'selectors' => [
					// '{{WRAPPER}} .crt-af-range-container input[type="range"]' => 'background: {{VALUE}};',
					'{{WRAPPER}} .crt-af-slider-track-bg' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'range_slider_range_bg_color',
			[
				'label' => esc_html__( 'Range Background Color', 'crt-manage' ), // used in javascript hence no color
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'frontend_available' => true,
				'selectors' => [
					// '{{WRAPPER}} .crt-af-range-container input[type="range"]' => 'background: {{VALUE}};',
					'{{WRAPPER}} .crt-af-slider-fill' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'range_slider_handlers_bg_color',
			[
				'label' => esc_html__( 'Handlers Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} input[type="range"]::-webkit-slider-thumb' => 'background: {{VALUE}} !important;',
					'{{WRAPPER}} input[type="range"]::-moz-range-thumb' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} input[type="range"]' => 'accent-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'range_slider_handlers_border_color',
			[
				'label' => esc_html__( 'Handlers Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} input[type="range"]::-webkit-slider-thumb' => 'border-color: {{VALUE}} !important;',
					'{{WRAPPER}} input[type="range"]::-moz-range-thumb' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'range_slider_handlers_box_shadow',
				'selector' => '{{WRAPPER}} .crt-af-range-container input[type=range]::-webkit-slider-thumb'
			]
		);

		// TODO: not applied to mozila, figure out why (ex croco) - Duke
		$this->add_control(
			'range_slider_handlers_box_shadow_pseudo_classes',
			[
				'type' => Controls_Manager::HIDDEN,
				'default'   => 'style',
				'selectors' => [
					'{{WRAPPER}} .crt-af-range-container input[type=range]::-moz-range-thumb' => 'box-shadow: {{range_slider_handlers_box_shadow.HORIZONTAL}}px {{range_slider_handlers_box_shadow.VERTICAL}}px {{range_slider_handlers_box_shadow.BLUR}}px {{range_slider_handlers_box_shadow.SPREAD}}px {{range_slider_handlers_box_shadow.COLOR}} {{range_slider_handlers_box_shadow_position.VALUE}};',
				],
				'condition' => [
					'range_slider_handlers_box_shadow_box_shadow_type' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'range_slider_bar_height',
			[
				'label' => esc_html__( 'Bar Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-rs-control' => 'min-height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .crt-af-range-container input[type="range"]' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .crt-af-range-container .crt-af-slider-track-bg' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .crt-af-range-container .crt-af-slider-fill' => 'height: {{SIZE}}{{UNIT}} !important;'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'range_slider_handlers_size',
			[
				'label' => esc_html__( 'Handlers Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-range-container input[type=range]::-webkit-slider-thumb' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-af-range-container input[type=range]::-moz-range-thumb' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'range_slider_handlers_border_type',
			[
				'label' => esc_html__( 'Handlers Border Type', 'crt-manage' ),
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
					'{{WRAPPER}} input[type="range"]::-webkit-slider-thumb' => 'border-style: {{VALUE}} !important;',
					'{{WRAPPER}} input[type="range"]::-moz-range-thumb' => 'border-style: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'range_slider_handlers_border_width',
			[
				'label' => esc_html__( 'Handlers Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} input[type="range"]::-webkit-slider-thumb' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} input[type="range"]::-moz-range-thumb' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'range_slider_handlers_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'range_handlers_border_radius',
			[
				'label' => esc_html__( 'Handlers Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 15,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-range-container input[type=range]::-webkit-slider-thumb' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-af-range-container input[type=range]::-moz-range-thumb' => 'border-radius: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
            'range_slider_position',
            [
                'label' => esc_html__( 'Slider Position', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'column-reverse',
                'options' => [
                    'column' => [
                        'title' => esc_html__( 'Top', 'crt-manage' ),
                        'icon' => 'eicon-arrow-up',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__( 'Bottom', 'crt-manage' ),
                        'icon' => 'eicon-arrow-down',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} .crt-af-range-container' => 'flex-direction: {{VALUE}}',
				],
				'separator' => 'before',
            ]
        );

		$this->end_controls_section();

		// Styles
		// Section: Range Values ------------
		$this->start_controls_section(
			'section_style_range_values',
			[
				'label' => esc_html__( 'Range Values', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_type' => 'range',
					'show_range_inputs!' => 'yes'
				]
			]
		);

		$this->add_control(
			'range_values_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .crt-af-rs-values' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'range_values_typography',
				'selector' => '{{WRAPPER}} .crt-af-rs-values',
				'separator' => 'before',
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

		// alignment
		$this->add_responsive_control(
			'range_values_alignment',
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .crt-af-rs-values' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'range_values_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-rs-values' => 'margin: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} 0;',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Range Inputs ------------
		$this->start_controls_section(
			'section_style_range_inputs',
			[
				'label' => esc_html__( 'Range Inputs', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_type' => 'range',
					'show_range_inputs' => 'yes'
				]
			]
		);

		$this->add_control(
			'range_inputs_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .crt-af-rf-control input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'range_inputs_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-af-rf-control input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'range_inputs_bd_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-af-rf-control input' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'range_inputs_typography',
				'label' => esc_html__( 'Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-af-rf-control input',
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

        $this->add_responsive_control(
            'range_inputs_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .crt-af-rf-control input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'range_inputs_margin',
            [
                'label' => esc_html__( 'Margin', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 15,
					'left' => 0,
				],
                'selectors' => [
                    '{{WRAPPER}} .crt-af-rf-control' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'range_inputs_border_type',
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
					'{{WRAPPER}} .crt-af-rf-control input' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'range_inputs_border_width',
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
					'{{WRAPPER}} .crt-af-rf-control input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'range_inputs_radius',
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
					'{{WRAPPER}} .crt-af-rf-control input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
            'range_inputs_alignment',
            [
                'label' => esc_html__( 'Alignment', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .crt-af-rf-control' => 'justify-content: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->add_responsive_control(
			'range_inputs_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%', 'px'],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 250,
					],
					'%' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-rf-control input' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'range_inputs_gap',
			[
				'label' => esc_html__( 'Gap', 'crt-manage' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-rf-control' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'section_style_more_less',
            [
                'label' => esc_html__( 'More / Less', 'crt-manage' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
					'filter_type' => ['checkbox', 'radio'],
                    'enable_more_less' => 'yes',
                ],
            ]
        );
        
		$this->start_controls_tabs( 'more_less_tabs' );

		$this->start_controls_tab( 'more_less_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
        $this->add_control(
            'more_less_text_color',
            [
                'label' => esc_html__( 'Text Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#B7B7B7',
                'selectors' => [
                    '{{WRAPPER}} .crt-view-more-less' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'more_less_background_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-view-more-less' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'more_less_border_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-view-more-less' => 'border-color: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab( 'more_less_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

        $this->add_control(
            'more_less_text_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-view-more-less:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'more_less_background_hover_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-view-more-less:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'more_less_border_hover_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#605BE5',
                'selectors' => [
                    '{{WRAPPER}} .crt-view-more-less:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'more_less_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-view-more-less' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
            'more_less_align',
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
					'{{WRAPPER}} .crt-view-ml-wrap' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'more_less_typography',
				'selector' => '{{WRAPPER}} .crt-view-more-less',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
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

		$this->add_control(
			'more_less_border_type',
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
					'{{WRAPPER}} .crt-view-more-less' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'more_less_border_width',
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
					'{{WRAPPER}} .crt-view-more-less' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'more_less_radius',
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
					'{{WRAPPER}} .crt-view-more-less' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

        $this->add_responsive_control(
            'more_less_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .crt-view-more-less' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'separator' => 'before',
            ]
        );

        $this->end_controls_section();
		
		// Styles
		// Section: Rating ------------
		$this->start_controls_section(
			'section_style_rating',
			[
				'label' => esc_html__( 'Rating', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_type' => 'rating'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_rating_styles' );

		$this->start_controls_tab(
			'tab_rating_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'rating_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd726',
				'selectors' => [
					'{{WRAPPER}} .crt-woo-rating i:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-woo-rating svg.marked' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_unmarked_color',
			[
				'label' => esc_html__( 'Unmarked Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-woo-rating i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-woo-rating svg.unmarked' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'rating_score_color',
			[
				'label' => esc_html__( 'Score Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .crt-woo-rating span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_rating_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'rating_color_hover',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-woo-rating:hover i:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-woo-rating:hover svg.marked' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_unmarked_color_hover',
			[
				'label' => esc_html__( 'Unmarked Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-woo-rating:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-woo-rating:hover svg.unmarked' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'rating_score_color_hover',
			[
				'label' => esc_html__( 'Score Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .crt-woo-rating span:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_rating_active',
			[
				'label' => esc_html__( 'Active', 'crt-manage' ),
			]
		);

		$this->add_control(
			'rating_color_active',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-active-product-filter.crt-woo-rating i:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_unmarked_color_active',
			[
				'label' => esc_html__( 'Unmarked Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-active-product-filter.crt-woo-rating i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-active-product-filter.crt-woo-rating svg' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'rating_score_color_active',
			[
				'label' => esc_html__( 'Score Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .crt-active-product-filter.crt-woo-rating span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rating_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 15,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 21,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-woo-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-woo-rating svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'rating_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-woo-rating svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-woo-rating span:not(:first-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'rating_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Ditance', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-woo-rating:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'rating_typography',
				'selector' => '{{WRAPPER}} .crt-woo-rating span',
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

		$this->end_controls_section();

		// Section: Apply Button ------------
		$this->start_controls_section(
            'section_style_apply_btn',
            [
                'label' => esc_html__( 'Apply Button', 'crt-manage' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'filter_type' => ['apply', 'range']
                ],
            ]
        );
        
		$this->start_controls_tabs( 'apply_btn_tabs' );

		$this->start_controls_tab( 'apply_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

        $this->add_control(
            'apply_text_color',
            [
                'label' => esc_html__( 'Text Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-af-apply-btn, {{WRAPPER}} .crt-af-range-apply-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'apply_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#605BE5',
                'selectors' => [
                    '{{WRAPPER}} .crt-af-apply-btn, {{WRAPPER}} .crt-af-range-apply-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'apply_border_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#605BE5',
                'selectors' => [
                    '{{WRAPPER}} .crt-af-apply-btn, {{WRAPPER}} .crt-af-range-apply-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab( 'apply_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

        $this->add_control(
            'apply_text_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-af-apply-btn:hover, {{WRAPPER}} .crt-af-range-apply-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'apply_bg_hover_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-af-apply-btn:hover, {{WRAPPER}} .crt-af-range-apply-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'apply_border_hover_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#605BE5',
                'selectors' => [
                    '{{WRAPPER}} .crt-af-apply-btn:hover, {{WRAPPER}} .crt-af-range-apply-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'apply_btn_typography',
				'selector' => '{{WRAPPER}} .crt-af-apply-btn, {{WRAPPER}} .crt-af-range-apply-btn',
				'separator' => 'before',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
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

		$this->add_control(
			'apply_btn_border_type',
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
					'{{WRAPPER}} .crt-af-apply-btn, {{WRAPPER}} .crt-af-range-apply-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'apply_btn_border_width',
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
					'{{WRAPPER}} .crt-af-apply-btn, {{WRAPPER}} .crt-af-range-apply-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'apply_btn_border_type!' => 'none'
				]
			]
		);

		$this->add_control(
			'apply_btn_radius',
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
					'{{WRAPPER}} .crt-af-apply-btn, {{WRAPPER}} .crt-af-range-apply-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
            'apply_alignment',
            [
                'label' => esc_html__( 'Alignment', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'stretch',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'crt-manage' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'crt-manage' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'crt-manage' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'stretch' => [
						'title' => esc_html__( 'Stretch', 'crt-manage' ),
						'icon' => 'eicon-text-align-justify',
					],
                ],
				'prefix_class' => 'crt-af-apply-btn-',
				'selectors' => [
					'{{WRAPPER}} .crt-af-apply-btn-wrap' => 'justify-content: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'apply_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 6,
					'right' => 5,
					'bottom' => 6,
					'left' => 5,
				],
                'selectors' => [
                    '{{WRAPPER}} .crt-af-apply-btn, {{WRAPPER}} .crt-af-range-apply-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'apply_margin',
            [
                'label' => esc_html__( 'Margin', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 12,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
                'selectors' => [
                    '{{WRAPPER}} .crt-af-apply-btn, {{WRAPPER}} .crt-af-range-apply-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_style_active_filters_item',
            [
                'label' => esc_html__( 'Active Filters Item', 'crt-manage' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'filter_type' => 'active',
                ],
            ]
        );
        
		$this->start_controls_tabs( 'active_item_tabs' );

		$this->start_controls_tab( 'normal_item',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

        $this->add_control(
            'active_item_text_color',
            [
                'label' => esc_html__( 'Text Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#787878',
                'selectors' => [
                    '{{WRAPPER}} .crt-af-active-filters .crt-remove-filter' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-af-active-filters .crt-remove-filter svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_item_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-af-active-filters .crt-remove-filter' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_item_border_color_normal',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .crt-af-active-filters .crt-remove-filter' => 'border-color: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover_item',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

        $this->add_control(
            'active_item_text_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#605BE5',
                'selectors' => [
                    '{{WRAPPER}} .crt-af-active-filters .crt-remove-filter:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-af-active-filters .crt-remove-filter:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_item_remove_icon_color_hover',
            [
                'label' => esc_html__( 'Remove Icon Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-af-active-filters .crt-remove-filter:hover span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-af-active-filters .crt-remove-filter:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_item_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-af-active-filters .crt-remove-filter:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_item_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#605BE5',
                'selectors' => [
                    '{{WRAPPER}} .crt-af-active-filters .crt-remove-filter:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'active_item_typography',
				'selector' => '{{WRAPPER}} .crt-af-active-filters .crt-remove-filter, {{WRAPPER}} .crt-af-reset-btn',
				'separator' => 'before',
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

		$this->add_responsive_control(
			'active_item_remove_icon_size',
			[
				'label' => esc_html__( 'Remove Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => '13',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-active-filters .crt-remove-filter svg' => 'width: {{SIZE}}px; height: {{SIZE}}px',
				],
			]
		);

		$this->add_control(
			'active_item_border_type',
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
					'{{WRAPPER}} .crt-af-active-filters .crt-remove-filter, {{WRAPPER}} .crt-af-reset-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'active_item_border_width',
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
					'{{WRAPPER}} .crt-af-active-filters .crt-remove-filter, {{WRAPPER}} .crt-af-reset-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'active_item_radius',
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
					'{{WRAPPER}} .crt-af-active-filters .crt-remove-filter, {{WRAPPER}} .crt-af-reset-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

        $this->add_responsive_control(
            'active_item_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 4,
					'right' => 6,
					'bottom' => 4,
					'left' => 6,
				],
                'selectors' => [
                    '{{WRAPPER}} .crt-af-active-filters .crt-remove-filter, {{WRAPPER}} .crt-af-reset-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'separator' => 'before',
            ]
        );

		$this->add_control(
            'active_item_alignment',
            [
                'label' => esc_html__( 'Alignment', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'label_block' => false,
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
					'justify' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
                ],
				'prefix_class' => 'crt-active-alignment-',
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-filters-wrap' => 'text-align: {{VALUE}}',
				],
				'condiitions' => [
					'fields_group_alignment' => 'column',
				],
				'separator' => 'before'
            ]
        );

        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_style_active_filters_reset',
            [
                'label' => esc_html__( 'Active Filters Reset', 'crt-manage' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'filter_type' => 'active',
                ],
            ]
        );

		$this->start_controls_tabs( 'active_btn_tabs' );

		$this->start_controls_tab( 'normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

        $this->add_control(
            'active_btn_text_color',
            [
                'label' => esc_html__( 'Text Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#787878',
                'selectors' => [
                    '{{WRAPPER}} .crt-af-reset-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_btn_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-af-reset-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_btn_border_color_normal',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#FFFFFF00',
                'selectors' => [
                    '{{WRAPPER}} .crt-af-reset-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

        $this->add_control(
            'active_btn_text_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#605BE5',
                'selectors' => [
                    '{{WRAPPER}} .crt-af-reset-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_btn_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-af-reset-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_btn_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .crt-af-reset-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
            'active_btn_position',
            [
                'label' => esc_html__( 'Position', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'last',
                'options' => [
                    'first' => [
                        'title' => esc_html__( 'First', 'crt-manage' ),
                        'icon' => 'eicon-arrow-up',
                    ],
                    'last' => [
                        'title' => esc_html__( 'Last', 'crt-manage' ),
                        'icon' => 'eicon-arrow-down',
                    ]
                ],
				'selectors_dictionary' => [
					'first' => 'order: 0',
					'last' => 'order: 999'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-af-reset-btn' => '{{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'before',
            ]
        );

        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_style_date',
            [
                'label' => esc_html__( 'Date Filters', 'crt-manage' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'filter_type' => ['date', 'date_range'],
                ],
            ]
        );

		$this->add_control(
			'calendar_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.air-datepicker-{{ID}}' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

        $this->end_controls_section();
		
    }
    
	public function is_serialized_array($str) {
		if (is_serialized($str)) {
			$unserialized = unserialize($str);
			return is_array($unserialized) && $unserialized !== false;
		} else {
			return false;
		}
	}

	// Recursive function to print categories and their children
	protected function print_filters($filter_type, $term, $taxonomy, $settings, $count = 0, $level = 0, $repeater_label = '') {
        if ( 'meta_field'  == $settings['filter_data'] ) {
            global $wpdb;

            // GOGA: needs validation
            $custom_field_key = $settings['cf_for_all_post_types'];
            
            // Query to get all values for the specified custom field
            $query = $wpdb->prepare("
                SELECT meta_value
                FROM $wpdb->postmeta
                WHERE meta_key = %s
            ", $custom_field_key);
            
            // Execute the query
            $values = $wpdb->get_col($query);

            // Remove duplicates
            $uniqueValues = array_unique($values);
        } else {
			if ( isset($term->term_id) ) {
				$args = [
					'taxonomy' => $taxonomy,
					'hide_empty' => $settings['hide_empty'],
					'parent' => $term->term_id, // get children of this category
				];
		
				$children = get_terms($args);
			}
        }

		$inner_output = '';

		if ( ( is_array($term) && isset($term['invalid_taxonomy']) ) || empty($term) || (isset($term->slug) && 'uncategorized' == $term->slug) ) {
			return;
		} else {
			if ( isset($settings['vs_filters'][$count]) ) {
				$visual_label = $settings['vs_filters'][$count]['vs_filters_title'];
			}

			if ( isset($term->slug) && 'uncategorized' != $term->slug ) {
				if ( is_array($taxonomy) ) {
					$get_var = $taxonomy[0];
				} else {
					$get_var = $taxonomy;
				}
	
				if ( 'select' === $settings['filter_type'] ) {
					if ( (1 == $this->dependent_count || !$this->dependent_count) || isset($_GET['crt_af_'. $get_var]) ) {
						$option_key = 'crt_af_option_' . $term->term_id;
						
						$this->add_render_attribute(
							$option_key,
							[
								'value' => $term->term_id,
								'data-post-type' => $term->taxonomy
							]
						);

						if ( isset($_GET['crt_af_'. $get_var]) && $_GET['crt_af_'. $get_var] == $term->term_id ) {
							$this->add_render_attribute($option_key, 'selected');
						}

						$inner_output .= '<option ' . $this->get_render_attribute_string($option_key) . '>' . str_repeat('&nbsp;', $level * 3) . $term->name;

						if ( 'yes' === $settings['show_count'] ) {
							$inner_output .= '<span class="crt-af-count">' .'('. $term->count .')' .'</span>';
						}
					   
					   $inner_output .= '</option>';
					}
				} else if ( 'checkbox' === $settings['filter_type'] || 'radio' === $settings['filter_type'] ) {
					$levels = $this->get_term_parent_levels($term->term_id, $get_var);
				
					if ($levels == 1) {
						$child_class = 'crt-af-child';
					} else if ($levels == 2) {
						$child_class = 'crt-af-g-child';
					} else if ( $levels > 2) {
						$child_class = 'crt-af-g-grand-child';
					} else {
						$child_class = '';
					}
	
					if ( empty ( $term->name ) ) {
						return;
					}
						
					if ( isset($visual_label) && !empty($visual_label) ) {
						$term_name = $visual_label;
					} else {
						$term_name = $term->name;
					}

					// Add render attributes for the input
					$input_key = 'crt_af_input_' . $term->term_id;
					$this->add_render_attribute(
						$input_key,
						[
							'type' => $filter_type,
							'id' => 'crt_af_' . $get_var . '_' . $term->term_id,
							'name' => 'crt_af_' . $get_var,
							'value' => $term->term_id,
						]
					);
					
					if ( 0 === $term->count && isset($settings['empty_actions']) && $settings['empty_actions'] == 'disable' ) {
						$this->add_render_attribute($input_key, 'disabled');
					}

					if ( isset($_GET['crt_af_'. $get_var]) && in_array($term->term_id, explode(',', $_GET['crt_af_'. $get_var])) ) {
						$this->add_render_attribute($input_key, 'checked');
						$crt_checked = ' crt-checked';
						$visual_active = ' crt-af-visual-active';
					} else {
						$crt_checked = '';
						$visual_active = '';
					}

					if ( $settings['enable_visual_filters'] == 'yes' ) {
						if ( $settings['visual_filter_type'] == 'color' ) {
							$inner_output .= '<div class="crt-af-visual-wrap crt-af-visual-color-wrap'. $visual_active .'">';
						} else if ( $settings['visual_filter_type'] == 'image' ) {
							$inner_output .= '<div class="crt-af-visual-wrap crt-af-visual-img-wrap'. $visual_active .'">';
						}
					}

					$inner_output .= '<label class="crt-af-input-wrap crt-flex '. $child_class . $crt_checked .'">';
						$inner_output .= '<input ' . $this->get_render_attribute_string($input_key) . '>';
						$inner_output .= '<span class="crt-af-term-name">'. $term_name .'</span>';
						if ( $settings['show_count'] == 'yes' ) {
							$inner_output .= '<span class="crt-af-count">' .'('. $term->count .')' .'</span>';
						}
					$inner_output .= '</label>';
				}
			} else if ( !isset($term->slug) ) {
				if ( is_array($taxonomy) ) {
					$get_var = $taxonomy[0];
				} else {
					$get_var = $taxonomy;
				}

				// Get count of posts with this custom meta field value
				$post_count = $wpdb->get_var($wpdb->prepare("
					SELECT COUNT(DISTINCT pm.post_id)
					FROM {$wpdb->postmeta} pm
					INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
					WHERE pm.meta_key = %s
					AND pm.meta_value = %s
					AND p.post_type = %s
					AND p.post_status = 'publish'
				", $taxonomy, $term, $settings['filters_query']));
	
				if ( 'select' === $settings['filter_type'] ) {
					if ( isset($_GET['crt_af_'. $get_var]) && $_GET['crt_af_'. $get_var] == $term ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
					
					if ( $this->is_serialized_array($term) !== false ) {
						foreach( $term as $key=>$value) {
							$inner_output = '<option value="' . $key . '" data-post-type="'. $settings['filters_query'] .'" '. $selected .'>' . str_repeat('&nbsp;', $level * 3) . $value . '</option>';
						}
					} else {
						if ( !isset($term['invalid_taxonomy']) ) {
							$inner_output = '<option value="' . $term . '" data-post-type="'. $settings['filters_query'] .'" '. $selected .'>' . str_repeat('&nbsp;', $level * 3) . $term;
							if ( 'yes' === $settings['show_count'] ) {
								$inner_output .= '<span class="crt-af-count">' .'('. $post_count .')' .'</span>';
							}
							$inner_output .= '</option>';
						}
					}
				} else if ( 'checkbox' === $settings['filter_type'] || 'radio' === $settings['filter_type'] ) {
					if ( empty($term) ) {
						return;
					}

					// Add render attributes for the input
					$input_key = 'crt_af_input_' . $term;
					$this->add_render_attribute(
						$input_key,
						[
							'type' => $filter_type,
							'id' => 'crt_af_' . $get_var . '_' . $term,
							'name' => 'crt_af_' . $get_var,
							'value' => $term,
						]
					);

					if ( isset($_GET['crt_af_'. $get_var]) && in_array($term, explode(',', $_GET['crt_af_'. $get_var])) ) {
						$this->add_render_attribute($input_key, 'checked');
						$crt_checked = ' crt-checked';
						$visual_active = ' crt-af-visual-active';
					} else {
						$crt_checked = '';
						$visual_active = '';
					}

					if ( $settings['enable_visual_filters'] == 'yes' ) {
						if ( $settings['visual_filter_type'] == 'color' ) {
							$inner_output .= '<div class="crt-af-visual-wrap crt-af-visual-color-wrap'. $visual_active .'">';
						} else if ( $settings['visual_filter_type'] == 'image' ) {
							$inner_output .= '<div class="crt-af-visual-wrap crt-af-visual-img-wrap'. $visual_active .'">';
						}
					}
	
					if ( isset($visual_label) && !empty($visual_label) ) {
						$term_name = $visual_label;
					} else {
						$term_name = $term;
					}

				   $inner_output .= '<label class="crt-af-input-wrap crt-flex'. $crt_checked .'">';
					$inner_output .= '<input ' . $this->get_render_attribute_string($input_key) . '>';
					$inner_output .= '<span class="crt-af-term-name">' . $term_name . '</span>';	
					if ( 'yes' === $settings['show_count'] ) {
						$inner_output .= '<span class="crt-af-count">' .'('. $post_count .')' .'</span>';
					}
				   $inner_output .= '</label>';
				}
				
			}

			$visual_term = $term;

			if ( isset($term->slug) ) {
				$visual_term = $term->slug;
			} else if ( isset($term->term_id) ) {
				$visual_term = $term->term_id;
			}

			// GOGA: get variables validation/sanitization needed
			if ( $settings['enable_visual_filters'] == 'yes' ) {
				if ( isset($settings['vs_filters'][$count]) ) {
					$vs_filters_item = $settings['vs_filters'][$count];
				}

				if ( isset($get_var) && isset($_GET['crt_af_' . $get_var]) && isset($visual_term) && in_array($visual_term, explode(',', $_GET['crt_af_' . $get_var])) ) {
					$class = 'crt-af-visual crt-af-visual-active elementor-repeater-item-' . $vs_filters_item['_id'];
				} else {
					$class = 'crt-af-visual elementor-repeater-item-' . $vs_filters_item['_id'];
				}

				if ( isset($vs_filters_item) && !empty($vs_filters_item['vs_filters_replace_value']) ) {
					if ( $settings['visual_filter_type'] == 'color' ) {
						$inner_output .= '<div class="'. $class .'" data-replace-value="'. $vs_filters_item['vs_filters_replace_value'] .'"></div>';
					} else if ( $settings['visual_filter_type'] == 'image' ) {
						$img_url = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $vs_filters_item['vs_filters_image']['id'], 'thumbnail', $settings );
	
						if ( $img_url ) {
							$inner_output .= '<img src="' . esc_url($img_url) . '" data-replace-value="'. $vs_filters_item['vs_filters_replace_value'] .'" alt="Visual Filter Image" class="' . $class . '">';
						}
					}
				} else {
					$inner_output .= '<span class="crt-af-visual-term">'. $visual_term .'</span>' . ' ';
				}

				$inner_output .= '</div>';
			}
	
			if ( $settings['enable_visual_hierarchy'] == 'yes' && isset($children) ) {
				foreach ($children as $child) {
				   $inner_output .= $this->print_filters($filter_type, $child, $taxonomy, $settings, $count, $level + 1, $repeater_label);
				}
			}
	
			return $inner_output;
		}
	}

    public function get_term_parent_levels($term_id, $taxonomy) {
        $levels = 0;
    
        // Start with the initial term
        $current_term = get_term($term_id, $taxonomy);
    
        // Loop through parents until reaching the topmost parent (parent is 0)
        while (!is_wp_error($current_term) && $current_term->parent !== 0) {
            $levels++;
            $current_term = get_term($current_term->parent, $taxonomy);
        }
    
        return $levels;
    }

	public function array_contains_array($haystack, $needle) {
		foreach ($haystack as $haystackItem) {
			$match = true;

			foreach ($needle as $key => $value) {
				if (!isset($haystackItem[$key]) || $haystackItem[$key] != $value) {
					$exploded = explode( ',', $value[0] );
					if ( !is_array($exploded) ) {
						$match = false;
					} else if( is_array($exploded) && !in_array( $haystackItem[$key][0], $exploded ) ) {
						$match = false;
					}
					break; // Break the inner loop if a mismatch is found
				}
			}
	
			if ($match) {
				return true; // Return true if a match is found
			}
		}
	
		return false; // Return false if no match is found
	}
	
	public function is_sub_array_present($subArray, $arrayOfArrays) {
		foreach ($arrayOfArrays as $array) {
			if (is_array($array)) {
				if ($this->is_sub_array_present($subArray, $array)) {
					return true;
				}
			} else {
				if ($subArray == $array) {
					return true;
				}
			}
		}
		return false;
	}

	public function get_all_meta_keys_by_post_type($post_type) {
		global $wpdb;
	
		$meta_keys = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT meta_key
				FROM $wpdb->postmeta
				INNER JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->postmeta.post_id
				WHERE $wpdb->posts.post_type = %s
				AND $wpdb->postmeta.meta_key != ''
				",
				$post_type
			)
		);
	
		return $meta_keys;
	}

	public function get_meta_field_label($post_type, $meta_key) {
		$meta_fields = $this->get_all_meta_keys_by_post_type($post_type);
	
		foreach ($meta_fields as $key => $meta_field) {
			if ($key === $meta_key) {
				return $meta_field['title']; // Adjust this based on the actual array key used in your registration
			}
		}
	
		return false; // Meta field not found
	}

	function crt_get_related_terms($current_tax, $current_term_slug, $target_tax, $filters_query) {
		// Step 1: Get all posts that belong to current archive term
		$post_ids = get_posts([
			'post_type'      => $filters_query,
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'tax_query'      => [
				[
					'taxonomy' => $current_tax,
					'field'    => 'slug',
					'terms'    => $current_term_slug,
				]
			]
		]);

		if (empty($post_ids)) {
			return [];
		}

		// Step 2: Collect all terms from another taxonomy assigned to those posts
		$term_ids = [];

		foreach ($post_ids as $pid) {
			$terms = wp_get_post_terms($pid, $target_tax, ['fields' => 'ids']);
			if (!empty($terms)) {
				$term_ids = array_merge($term_ids, $terms);
			}
		}

		$term_ids = array_unique($term_ids);

		if (empty($term_ids)) {
			return [];
		}

		// Step 3: Return only related terms
		return [
			'taxonomy'   => $target_tax,
			'include'    => $term_ids,
			'hide_empty' => true,
		];
	}

	public function render_main_filters($settings, $taxonomy, $filter_type, $vf_class, $repeater_label = '') {
		if ( !isset( $settings['tax_query_type'] ) ) {
			$settings['tax_query_type'] = 'AND';
		}
		
		$current_query = get_queried_object();

		if ( is_array($taxonomy) ) {
			$terms = [];

			foreach($taxonomy as $taxonomy_type) {
				
				if ( isset($settings['enable_dependency']) && 'yes' === $settings['enable_dependency'] ) {
					// Collect all 'crt_af_' prefixed GET variables
					$crt_af_variables = [];
					
					foreach ($_GET as $key => $value) {
						// Check if the key has the 'crt_af_' prefix
						if (strpos($key, 'crt_af_') === 0) {
							if ($key !== 'crt_af_' . $taxonomy_type) { // NEEDS CONDITION FOR INNER-DEPENDANCY
								// Extract the taxonomy type
								$taxonomy_key = substr($key, strlen('crt_af_'));
								
								// if ( taxonomy_exists($taxonomy_key) ) {
									// Filter and store the values
									$crt_af_variables[$taxonomy_key] = array_filter(explode(',', $value));
								// }
							}
						}
					}
	
					// Modify the args array for get_terms
					$include_terms = array_filter(array_merge(...array_values($crt_af_variables)));
				}
	
				if ( 'select' == $filter_type ) {
					if ( 1 > $this->dependent_count ) {
						$this->output .= '<option value="0">'. esc_html__($settings['select_field_placeholder']) .'</option>';
					} else {
						$this->output .= '<option value="0">'. esc_html__($this->filter_item['dependent_select_placeholder']) .'</option>';
					}
				}
	
				$args = [
					'taxonomy'   => $taxonomy_type,
					'hide_empty' => $settings['hide_empty'],
					'parent' => 0
				];
	
				// Get the terms
				$terms = get_terms($args);
				$count = 0;
	
				foreach ($terms as $term) {
					// Get posts that have the specified terms
					if ( isset($settings['enable_dependency']) && 'yes' === $settings['enable_dependency'] ) {
						$terms_array = [[
							'taxonomy' => $term->taxonomy, // Adjust this according to your needs
							// 'hide_empty' => $settings['hide_empty'],
							'terms' => $term->term_taxonomy_id,
							'field' => 'term_id',
	
						]];
	
						$meta_array = [];
	
						$date_query = [];
	
						foreach ($crt_af_variables as $key => $include_term) {
							if ( isset( $_GET['crt_afr_' .$key] ) && 'range' == explode(',', $_GET['crt_afr_' .$key])[1] ) {
								$array_to_push = [
									'key' => $key, // Adjust this according to your needs
									// 'hide_empty' => $settings['hide_empty'],
									'type' => 'numeric',
									'value' => [$include_term[0], $include_term[1]],
									'compare' => 'between'
								];
	
								if ( !$this->is_sub_array_present($array_to_push, $meta_array) ) {
									array_push($meta_array, $array_to_push);	
								}	
							} elseif ( is_array($include_term) && 'and' == $settings['tax_query_type'] ) {
								foreach ($include_term as $inc_term) {
									if ( !get_taxonomy($key) ) {
										array_push($meta_array, array(
											'key' => $key,
											// 'hide_empty' => $settings['hide_empty'],
											'value' => $inc_term,
										));
									} else {
										array_push($terms_array, array(
											'taxonomy' => $key,
											// 'hide_empty' => $settings['hide_empty'],
											'terms' => $inc_term,
											'field' => 'term_id',
										));
									}	
								}
							} elseif ( $key == 'date' ) {
								$date = $_GET['crt_af_' .$key];
	
								if ( str_contains("-", $date) ) {
									list($year, $month, $day) = explode("-", $date);
	
									array_push( $date_query, [
										'year' => $year,
										'month' => $month,
										'day' => $day,
									]);
								}
							} elseif ( $key == 'date_range' ) {
								$date = $_GET['crt_af_' .$key];
	
								if ( str_contains($date, ',') ) {
									$date = explode(',', $date);
	
									if (false) {
										$date_query = ['relation' => 'or'];
	
										list($year1, $month1, $day1) = explode("-", $date[0]);
										list($year2, $month2, $day2) = explode("-", $date[1]);
	
										array_push( $date_query, [
											'year' => $year1,
											'month' => $month1,
											'day' => $day1,
										] );
	
										array_push( $date_query, [
											'year' => $year2,
											'month' => $month2,
											'day' => $day2,
										] );
	
									} else {
										array_push( $date_query, [
											'after'     => $date[0],
											'before'    => $date[1],
											'inclusive' => true
										] );
									}
								} 
							} else {
								if ( !get_taxonomy($key) ) {
									array_push($meta_array, array(
										'key' => $key,
										// 'hide_empty' => $settings['hide_empty'],
										'value' => $include_term,
									));
								} else {
									array_push($terms_array, [
										'taxonomy' => $key,
										// 'hide_empty' => $settings['hide_empty'],
										'terms' => $include_term,
										'field' => 'term_id',
									]);	
								}
							}
						}
	
						$post_args = [
							'post_type'      => $settings['filters_query'],
							'posts_per_page' => -1,
							'tax_query'      => [
								'relation' => isset($settings['tax_query_type']) ? $settings['tax_query_type'] : 'AND',
								$terms_array
							],
						];
	
						if ( !empty($meta_array) ) {
							$post_args['meta_query'] = $meta_array;
						}
	
						if ( !empty($date_query) ) {
							$post_args['date_query'] = $date_query;
						}
	
						// Get posts that have the specified terms
						$posts = get_posts($post_args);
	
						if (count($posts) == 0) {
							continue;
						}
					}
					
					if ( isset($term->count) && !($term->count > 0) && $settings['hide_empty'] == 'yes' ) {
						continue;
					}
					
					$this->output .= $this->print_filters($filter_type, $term, $taxonomy, $settings, $count, 0, $repeater_label);
					$count++;
				}
			}
		} else {
			if ( isset($settings['enable_dependency']) && 'yes' === $settings['enable_dependency']) {
				// Collect all 'crt_af_' prefixed GET variables
				$crt_af_variables = [];
				
				foreach ($_GET as $key => $value) {
					// Check if the key has the 'crt_af_' prefix
					if (strpos($key, 'crt_af_') === 0) {
						if ($key !== 'crt_af_' . $taxonomy) {
							// Extract the taxonomy type
							$taxonomy_type = substr($key, strlen('crt_af_'));
					
							// Filter and store the values
							$crt_af_variables[$taxonomy_type] = array_filter(explode(',', $value));
						}
					}
				}
	
				// Modify the args array for get_terms
				$include_terms = array_filter(array_merge(...array_values($crt_af_variables)));
			}
	
			if ( 'select' == $filter_type ) {
				if ( 1 > $this->dependent_count ) {
					$this->output .= '<option value="0">'. esc_html__($settings['select_field_placeholder']) .'</option>';
				} else {
					$this->output .= '<option value="0">'. esc_html__($this->filter_item['dependent_select_placeholder']) .'</option>';
				}
			}
			
			if ( 'taxonomy' == $settings['filter_data'] ) {
				$args = [
					'taxonomy'   => $taxonomy,
					'hide_empty' => $settings['hide_empty'],
					'parent'     => 0,
				];

				if ( isset( $current_query->taxonomy ) && $current_query->taxonomy == $settings['query_taxonomy_' . $settings['filters_query']] ) {
					$args['include'] = [$current_query->term_taxonomy_id];
					unset($args['parent']);
				} else if ( isset( $current_query->taxonomy ) && $current_query->taxonomy != $settings['query_taxonomy_' . $settings['filters_query']] ) {
					// $args = $this->crt_get_related_terms( $current_query->taxonomy, $current_query->slug, $taxonomy, $settings['filters_query'] );

					global $wpdb;
					$terms_array = [];
					$current_term = $current_query;

					// Add archive context filtering
					$additional_where = $wpdb->prepare(
						"AND posts.ID IN (
							SELECT object_id 
							FROM {$wpdb->term_relationships} tr 
							JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id 
							WHERE tt.term_id = %d
						)",
						$current_term->term_id
					);

					$query = $wpdb->prepare(
						"SELECT
							terms.term_id,
							terms.name,
							terms.slug,
							terms.term_group,
							term_tax.term_taxonomy_id,
							term_tax.taxonomy,
							term_tax.description,
							term_tax.parent,
							COUNT(DISTINCT posts.ID) AS count
						FROM {$wpdb->term_relationships} AS rel
						INNER JOIN {$wpdb->posts} AS posts
							ON posts.ID = rel.object_id
						INNER JOIN {$wpdb->term_taxonomy} AS term_tax
							ON term_tax.term_taxonomy_id = rel.term_taxonomy_id
						INNER JOIN {$wpdb->terms} AS terms
							ON terms.term_id = term_tax.term_id
						WHERE posts.post_type = %s
							AND posts.post_status = 'publish'
							AND term_tax.taxonomy = %s
							{$additional_where}
						GROUP BY term_tax.term_taxonomy_id",
						$settings['filters_query'],
						$taxonomy
					);
					
					$terms = $wpdb->get_results($query);
				}
	
				// Get the terms
				if ( !isset( $terms ) || empty( $terms ) ) {
					$terms = get_terms($args);
				}
				
				$count = 0;
	
				foreach ($terms as $term) {
					if (!empty($include_terms)) {
				
						if ( isset($settings['enable_dependency']) && 'yes' === $settings['enable_dependency']) {
							// Get posts that have the specified terms
							$terms_array = [[
								'taxonomy' => $term->taxonomy,
								// 'hide_empty' => $settings['hide_empty'],
								'terms' => $term->term_taxonomy_id,
								'field' => 'term_id',
	
							]];
	
							foreach ($crt_af_variables as $key => $include_term) {
								if ( get_taxonomy($include_term[0]) ) {
									array_push($terms_array, array(
										'taxonomy' => $key,
										// 'hide_empty' => $settings['hide_empty'],
										'terms' => $include_term[0],
										'field' => 'term_id',
									));
								}
							}
	
							$post_args = [
								'post_type'      => $settings['filters_query'],
								'posts_per_page' => -1,
								'tax_query'      => [
									'relation' => isset($settings['tax_query_type']) ? $settings['tax_query_type'] : 'AND',
									$terms_array
								],
							];
						
							// Get posts that have the specified terms
							$posts = get_posts($post_args);
	
							if (count($posts) == 0) {
								continue;
							}
						}
						
						if ( !($term->count > 0) && $settings['hide_empty'] == 'yes' ) {
							continue;
						}
					}
	
					if ( isset($term->count) && !($term->count > 0) && $settings['hide_empty'] == 'yes' ) {
						continue;
					}

					if ( isset($term->slug) && $term->slug == 'uncategorized' ) {
						continue;
					}
	
					$repeater_label = $repeater_label;
					$this->output .= $this->print_filters($filter_type, $term, $taxonomy, $settings, $count, 0, $repeater_label);
					$count++;
				}
				// HIERARCHY EXPERIMENT END
					
			} else {
				global $wpdb;
	
				// Replace 'your_custom_field_key' with the actual key of your custom field
				$custom_field_key = $taxonomy;
				
				// Query to get all values for the specified custom field
				$query = $wpdb->prepare("
					SELECT meta_value
					FROM $wpdb->postmeta
					WHERE meta_key = %s
				", $custom_field_key);
				
				// Execute the query
				$values = $wpdb->get_col($query);

				// Remove duplicates
				$terms = array_unique($values);
				$count = 0;
			
				foreach ($terms as $term) {
					if ( isset($term->count) && !($term->count > 0) && $settings['hide_empty'] == 'yes' ) {
						continue;
					}
	
					if (!empty($include_terms)) {
				
						if ( isset($settings['enable_dependency']) && 'yes' === $settings['enable_dependency']) {
							// Get posts that have the specified terms
							$meta_array = [
								'relation' => $settings['tax_query_type'],
								[
									'key' => $custom_field_key,
									// 'hide_empty' => $settings['hide_empty'],
									'value' => $term,
									'compare' => '='
								]
							];
	
							$tax_array = [
								'relation' => $settings['tax_query_type']
							];
	
							foreach ($crt_af_variables as $key => $include_term) {
								if (  isset( $_GET['crt_afr_' .$key] ) && 'range' == explode(',', $_GET['crt_afr_' .$key])[1] ) {
									$array_to_push = [
										'key' => $key,
										// 'hide_empty' => $settings['hide_empty'],
										'type' => 'numeric',
										'value' => [$include_term[0], $include_term[1]],
										'compare' => 'between'
									];
	
									if ( !$this->is_sub_array_present($array_to_push, $meta_array) ) {
										array_push($meta_array, $array_to_push);	
									}	
								} elseif ( !taxonomy_exists($key) ) {
									if ( is_array($include_term) ) {
										foreach ( $include_term as $term_key => $term_value ) {
											$array_to_push = [
												'key' => $key,
												// 'hide_empty' => $settings['hide_empty'],
												'value' => $term_value,
												'compare' => '='
											];
	
											if ( !$this->is_sub_array_present($array_to_push, $meta_array) ) {
												array_push($meta_array, $array_to_push);
											}
										}
									}	
								} else {
									foreach ( $include_term as $term_key => $term_value ) {
										$array_to_push = [
											'taxonomy' => $key,
											// 'hide_empty' => $settings['hide_empty'],
											'terms' => $term_value,
											'field' => 'term_id',
										];
	
										if ( !$this->is_sub_array_present($array_to_push, $tax_array) ) {
											array_push($tax_array, $array_to_push);
										}
									}
								}
							}
	
							$post_args = array(
								'post_type'      => $settings['filters_query'], // Adjust this according to your needs
								'posts_per_page' => -1,
								'meta_query'      => array(
									'relation' => isset($settings['tax_query_type']) && !empty($settings['tax_query_type']) ? $settings['tax_query_type'] : 'AND',
									$meta_array
								),
							);
							
							if ( !empty($tax_array) ) {
								$post_args['tax_query'] = $tax_array;
							}
	
							// Get posts that have the specified terms
							$posts = get_posts($post_args);
	
							if ( count($posts) == 0 && $settings['hide_empty'] == 'yes' ) {
								continue;
							}
						}
					}
	
					if ( isset($term->count) && !($term->count > 0) && $settings['hide_empty'] == 'yes' ) {
						continue;
					}
	
					$this->output .= $this->print_filters($filter_type, $term, $taxonomy, $settings, $count, 0, $repeater_label);
					$count++;
				}
			}
		}
	}

	public function render_range_filter($settings) {
		$this->add_render_attribute(
			'range-cont', 
			[
				'class' => 'crt-af-range-container',
				'show-inputs' => 'yes' !== $settings['show_range_inputs'] ? 'no' : 'yes',
				// TODO: add border and shadow? - Duke
			]
		);
		
		global $wpdb;

		if ( $settings['filter_data'] === 'price' ) {
			$custom_field_key = '_price';

			// Query only prices of published WooCommerce products
			$query = "
				SELECT CAST(meta_value AS UNSIGNED) as price
				FROM $wpdb->postmeta
				INNER JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->postmeta.post_id
				WHERE meta_key = '_price'
				AND $wpdb->posts.post_type = 'product'
				AND $wpdb->posts.post_status = 'publish'
				AND meta_value != ''
			";

			$values = $wpdb->get_col($query);

			$min_value = !empty($values) ? min($values) : 0;
			$max_value = !empty($values) ? max($values) : 0;
		} else {
			$custom_field_key = $settings['cf_for_all_post_types'];
			if ( $custom_field_key == 'price' && $settings['filters_query'] == 'product' ) {
				$custom_field_key = '_price';
			}

			$query = $wpdb->prepare("
				SELECT meta_value
				FROM $wpdb->postmeta
				WHERE meta_key = %s
				AND meta_value REGEXP '^[0-9]+(\.[0-9]+)?$'
			", $custom_field_key);

			$values = $wpdb->get_col($query);
			$uniqueValues = array_unique($values);
			
			// Convert to floats for proper numeric comparison
			$numericValues = array_map('floatval', array_filter($uniqueValues, 'is_numeric'));

			$min_value = !empty($numericValues) ? min($numericValues) : 0;
			$max_value = !empty($numericValues) ? max($numericValues) : 0;
		}

		if ( isset($_GET['crt_af_'. $custom_field_key]) ) {
			$this->add_render_attribute('range-cont', 'data-active', 'yes');
			$filtervalues = explode(',', $_GET['crt_af_'. $custom_field_key]);

			$min_selected_value = $filtervalues[0];
			if ( isset($filtervalues[1]) ) {
				$max_selected_value = $filtervalues[1];
			} else {
				$max_selected_value = $max_value;
			}
		} else {
			$this->add_render_attribute('range-cont', 'data-active', 'no');

			$min_selected_value = $min_value;
			$max_selected_value = $max_value;
		}

	   $this->output .= '<div '. $this->get_render_attribute_string('range-cont') .'>';
		
			// Sliders
			$this->output .= '<div class="crt-af-rs-control">';
				$this->output .= '<div class="crt-af-slider-track-bg"></div>';
				$this->output .= '<div class="crt-af-slider-fill"></div>';
				
				$this->output .= '<input class="crt-af-from-slider" id="crt-af-from-slider-'. $this->get_id() .'" type="range" value="'. esc_attr($min_selected_value) .'" min="'. esc_attr($min_value) .'" max="'. esc_attr($max_value) .'"/>';
				$this->output .= '<input class="crt-af-to-slider" id="crt-af-to-slider-'. $this->get_id() .'" type="range" value="'. esc_attr($max_selected_value) .'" min="'. esc_attr($min_value) .'" max="'. esc_attr($max_value) .'"/>';
			$this->output .= '</div>';

			// Inputs
			$this->output .= '<div class="crt-af-rf-control">';
				$this->output .= '<input class="crt-af-rf-control-min-input" name="crt_af_'. $custom_field_key .'" type="number" id="crt-from-input-'. $this->get_id() .'" value="'. $min_selected_value .'" min="'. $min_value .'" max="'. $max_value .'"/>';
				$this->output .= '<input class="crt-af-rf-control-max-input" name="crt_af_'. $custom_field_key .'" type="number" id="crt-to-input-'. $this->get_id() .'" value="'. $max_selected_value .'" min="'. $min_value .'" max="'. $max_value .'"/>';
			$this->output .= '</div>';

			if ( 'yes' !== $settings['show_range_inputs'] ) {
				$prefix = $settings['range_value_prefix'];
				$suffix = $settings['range_value_suffix'];
				
				// Values
				$this->output .= '<div class="crt-af-rs-values">';
					$this->output .= '<span>';
						$this->output .= '<span class="crt-af-rs-value-prefix">'. $prefix .'</span>';
						$this->output .= '<span class="crt-af-rs-value-min">'. $min_selected_value .'</span>';
						$this->output .= '<span class="crt-af-rs-value-suffix">'. $suffix .'</span>';
					$this->output .= '</span>';

					$this->output .= '<span> - </span>';

					$this->output .= '<span>';
						$this->output .= '<span class="crt-af-rs-value-prefix">'. $prefix .'</span>';
						$this->output .= '<span class="crt-af-rs-value-max">'. $max_selected_value .'</span>';
						$this->output .= '<span class="crt-af-rs-value-suffix">'. $suffix .'</span>';
					$this->output .= '</span>';
				$this->output .= '</div>';
			}
		
	   $this->output .= '</div>';
	
	   if ( $settings['show_range_apply'] == 'yes' ) {
		$this->output .= '<div class="crt-af-apply-btn-wrap">';
		   $this->output .= '<button class="crt-af-range-apply-btn">'. esc_html__($settings['range_apply_text']) .'</button>';
		$this->output .= '</div>';
	   }
	}

	public function render_apply_all_filter($settings) {
		$redirect_url = isset($settings['redirect_url']) && !empty($settings['redirect_url']) ? esc_attr($settings['redirect_url']) : '#';

		$this->output .= '<div class="crt-af-apply-btn-wrap">';
			$this->output .= '<button class="crt-af-apply-btn" data-redirect-url="'. $redirect_url .'">'. esc_html__($settings['apply_text']) .'</button>';
		$this->output .= '</div>';
	}

	public function render_active_filters($settings) {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$reset_btn_class = 'crt-af-reset-btn';
		} else {
			$reset_btn_class = 'crt-af-reset-btn crt-hidden-element';
		}

		$this->output .= '<div class="crt-af-active-filters">';
			$this->output .= '<button class="'. $reset_btn_class .'">'. esc_html__($settings['reset_all_text']) .'</button>';
		$this->output .= '</div>';
	}

	public function render_date_filter($settings) {

		if ( $settings['filter_type'] == 'date' ) {
			if ( isset($_GET['crt_af_date']) ) {
				$date = sanitize_text_field($_GET['crt_af_date']);
			} else {
				$date = '';
			}

			// $this->output .= '<input type="date" name="crt_af_date" class="crt-date-filter" value="'. $date .'">';
			$this->output .= '<input type="text" name="crt_af_date" class="crt-date-filter" autocomplete="off" value="'. esc_attr($date) .'">';
		} else {
			if ( isset ( $_GET['crt_af_date_range'] ) ) {
				$date = $_GET['crt_af_date_range'];
	
				if (  str_contains($date, ',') ) {
					$date = explode(',', $_GET['crt_af_date_range']);
					$min_date = $date[0];
					$max_date = $date[1];
				} else {
					$min_date = $date;
					$max_date = '';
				}
			} else {
				$min_date = '';
				$max_date = '';
			}

            // $this->output .= '<input type="date" name="crt_af_date_range" class="crt-date-filter-start" value="'. $min_date .'">';
            // $this->output .= '<input type="date" name="crt_af_date_range" class="crt-date-filter-end" value="'. $max_date .'">';
            $this->output .= '<input type="text" name="crt_af_date_range" class="crt-date-filter-start" id="crt-datepicker-1" value="'. $min_date .'">';
            $this->output .= '<input type="text" name="crt_af_date_range" class="crt-date-filter-end" id="crt-datepicker-2" value="'. $max_date .'">';
		}
	}

	public function render_search_filter($settings) {
		$this->output .= '<input type="text" name="crt_af_search" class="crt-search-filter" placeholder="'. esc_attr($settings['search_placeholder']) .'">';
	}

    public function render_rating_filter($settings) {
        $this->output .= '<div class="crt-af-rating-filter">';
        
		$wrapper_class = 'crt-product-filter-rating';
		$rating_icon = '&#xE934;';
		$get_var = 'rating';

		if ( 'style-1' === $settings['rating_style'] ) {
			$wrapper_class .= ' crt-woo-rating-style-1';
			if ( 'outline' === $settings['rating_unmarked_style'] ) {
				$rating_icon = '&#xE933;';
			}
		} elseif ( 'style-2' === $settings['rating_style'] ) {
			$rating_icon = '&#9733;';
			$wrapper_class .= ' crt-woo-rating-style-2';

			if ( 'outline' === $settings['rating_unmarked_style'] ) {
				$rating_icon = '&#9734;';
			}
		}

		// Get counts based on current filters/query
		$counts = $this->get_rating_buckets( );

        $this->output .= '<ul class="'. esc_attr($wrapper_class) .'">';

        for ( $rating = 5; $rating >= 1; $rating-- ) {

            $class = 'crt-woo-rating';
			$class .= ' crt-woo-rating-' . $rating;

			if ( isset($_GET['crt_af_'. $get_var]) && $_GET['crt_af_'. $get_var] == $rating ) {
				$class .= ' crt-active-product-filter';
			}

			$this->add_render_attribute(
				'crt_af_rating_' . $rating,
				[
					'class' => esc_attr($class),
					'name' => 'crt_af_rating',
					'data-rating' => esc_attr($rating)
				]
			);

			$this->add_render_attribute(
				'crt_af_rating_input_' . $rating,
				[
					'type' => 'number',
					'name' => 'crt_af_rating',
					'id' => 'crt_af_rating_'. $rating,
					'class' => 'crt-rating-filter',
					'min' => '0',
					'max' => '5',
					'step' => '0.1',
				]
			);

            $this->output .= '<li '. $this->get_render_attribute_string('crt_af_rating_' . $rating) .'>';
        		$this->output .= '<input '. $this->get_render_attribute_string('crt_af_rating_input_' . $rating) .'>';
                $this->output .= '<span href="">';
                    $this->output .= '<span>';
                        for ( $i = 1; $i <= 5; $i++ ) {
                            if ( $i <= $rating ) {
								if ( \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) && 'style-1' == $settings['rating_style'] ) {
									ob_start();
									\Elementor\Icons_Manager::render_icon( [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ], [ 'class' => 'marked', 'aria-hidden' => 'true' ] );
									$this->output .= ob_get_clean();
								} else {
									$this->output .= '<i class="crt-rating-icon-full">'. esc_html($rating_icon) .'</i>';
								}
                            } else {
								if ( \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) && 'style-1' == $settings['rating_style'] ) {									
									ob_start();
									if ( 'outline' === $settings['rating_unmarked_style'] ) {
										\Elementor\Icons_Manager::render_icon( [ 'value' => 'far fa-star', 'library' => 'fa-regular' ], [ 'class' => 'unmarked', 'aria-hidden' => 'true' ] );
									} else {
										\Elementor\Icons_Manager::render_icon( [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ], [ 'class' => 'unmarked', 'aria-hidden' => 'true' ] );
									}
									$this->output .= ob_get_clean();
								} else {
                                	$this->output .= '<i class="crt-rating-icon-empty">'. esc_html($rating_icon) .'</i>';
								}
                            }
                        }
                    $this->output .= '</span>';

					if ( $settings['show_count'] == 'yes' ) {
						$this->output .= '<span class="crt-af-count">' .'('. $counts[$rating] .')' .'</span>';
					}
                $this->output .= '</span>';
            $this->output .= '</li>';
        }

        $this->output .= '</ul>';

        $this->output .= '</div>';
    }

	public function render_general_filters($settings, $taxonomy, $filter_type, $vf_class, $repeater_label = '')  {

		if ( 'select' == $filter_type ) {
			if ( 1 == $this->dependent_count ) {
				$dependency_class = 'crt-af-main-select';
			} else if ( $this->dependent_count > 1 ) {
				$dependency_class = 'crt-af-dependent-select';
			} else {
				$dependency_class = '';
			}

			if ( is_array($taxonomy) ) {
				$data_taxonomy = $taxonomy[0];
			} else {
				$data_taxonomy = $taxonomy;
			}

            $this->output .= '<div class="crt-af-select-wrap">';

			
				if ( isset($settings['show_label']) && 'yes' == $settings['show_label'] && isset($settings['label_text']) && !empty($settings['label_text']) ) {
					$this->output .= '<h4 class="crt-af-filters-label">' . esc_html__($settings['label_text']) . '</h4>';
				}
					
				if ( isset($repeater_label) && !empty($repeater_label) ) {
					$this->output .= '<h4 class="crt-af-filters-label">' . esc_html__($repeater_label) . '</h4>';
				}

				// Build select attributes using add_render_attribute
				$this->add_render_attribute(
					'crt_af_select_' . $data_taxonomy,
					[
						'data-taxonomy' => $data_taxonomy,
						'name' => 'crt_af_' . $data_taxonomy,
						'class' => 'crt-af-select ' . esc_attr($dependency_class),
					]
				);
				$this->output .= '<select ' . $this->get_render_attribute_string('crt_af_select_' . $data_taxonomy) . '>';
		
		} else if ('radio' == $filter_type || 'checkbox' == $filter_type ) {
			if ( isset($settings['show_label']) && 'yes' == $settings['show_label'] && isset($settings['label_text']) && !empty($settings['label_text']) ) {
				$this->output .= '<h4 class="crt-af-filters-label">' . esc_html__($settings['label_text']) . '</h4>';
			}

			$this->output .= '<div class="crt-af-check-radio-group crt-advanced-filters-inner '. esc_attr($vf_class) .'">';
		}

		$this->render_main_filters($settings, $taxonomy, $filter_type, $vf_class, $repeater_label);

		if ( 'select' == $filter_type ) {
			// Close the select
			$this->output .= '</select>';
            $this->output .= '</div>';
		} else if ( 'radio' == $filter_type || 'checkbox' == $filter_type ) {
			if ( 'yes' == $settings['enable_more_less'] ) {
				$this->output .= '<div class="crt-view-ml-wrap" data-item-count="'. esc_attr($settings['more_less_item_count']) .'">';
				
				// Use add_render_attribute for the <a> tag
				$this->add_render_attribute(
					'crt_view_more_less',
					[
						'href' => '#',
						'class' => 'crt-view-more-less',
						'data-less-text' => esc_attr($settings['less_text']),
						'data-more-text' => esc_attr($settings['more_text']),
					]
				);

				$this->output .= '<a ' . $this->get_render_attribute_string('crt_view_more_less') . '>' . esc_html($settings['more_text']) . '</a>';
				$this->output .= '</div>';
			}

			// Group options by post type
			$this->output .= '</div>';
		}

		if ( 'select' == $filter_type && 'yes' == $settings['enable_dependent_select'] ) {
			$this->dependent_count++;
		}
	}

    public function render_grid_filters($settings = []) {
        // Start the output
        $vf_class = 'yes' === $settings['enable_visual_filters'] ? 'crt-af-visual-group' : '';
        $taxonomy = isset($settings['query_taxonomy_'. $settings['filters_query']]) ? $settings['query_taxonomy_'. $settings['filters_query']] : $settings['cf_for_all_post_types'];
        $filter_type = $settings['filter_type'];
		$valid_types = ['select', 'checkbox', 'radio']; // Valit types for render_general_filters

		// Derive a human‑readable label from taxonomy or meta field key.
		$primary_tax = is_array($taxonomy) ? (isset($taxonomy[0]) ? $taxonomy[0] : '') : (string) $taxonomy;
		$term_label = '';

		// 1) If this is a taxonomy, use its registered label.
		if ($settings['filter_data'] === 'taxonomy' && $primary_tax) {
			$tax_obj = get_taxonomy($primary_tax);
			if ($tax_obj && !is_wp_error($tax_obj)) {
				$term_label = !empty($tax_obj->labels->singular_name)
					? $tax_obj->labels->singular_name
					: (!empty($tax_obj->label) ? $tax_obj->label : $tax_obj->name);
			}
		}

		// 2) If still empty (meta field or unknown taxonomy), try known keys or ACF, else prettify the key.
		if ('' === $term_label) {
			$key = $primary_tax ?: (isset($settings['cf_for_all_post_types']) ? $settings['cf_for_all_post_types'] : '');
			if ($key === '_price' || $key === 'price') {
				$term_label = esc_html__('Price', 'crt-manage');
			} elseif (function_exists('acf_get_field')) {
				// Try to resolve ACF field label by meta key (field name).
				$acf_field = acf_get_field($key);
				if (is_array($acf_field) && !empty($acf_field['label'])) {
					$term_label = $acf_field['label'];
				}
			}

			// Fallback: prettify the slug/key.
			if ('' === $term_label && $key) {
				$term_label = ucwords(str_replace(['_', '-'], ' ', $key));
			}
		}

		if ( 'rating' === $filter_type ) {
			if ( !class_exists( 'WooCommerce' ) ) {
				return '<p>'. esc_html__( 'WooCommerce is not active. Please activate WooCommerce to use the Rating filter.', 'crt-manage' ) .'</p>';
			}

			$settings['tax_query_type'] = 'or';
			$term_label = ucfirst($filter_type);
		}

        $this->add_render_attribute(
            'gf_wrapper',
            [
                'class' => [ 'crt-advanced-filters-wrap' ],
                'data-crt-relation' => esc_attr($settings['tax_query_type']),
                'data-crt-filter-type' => esc_attr($filter_type),
                'data-enable-ajax' => esc_attr($settings['enable_ajax']),
				'data-show-count' => isset($settings['show_count']) ? esc_attr($settings['show_count']) : '',
				'data-change-counter' => isset($settings['change_counter']) ? esc_attr($settings['change_counter']) : '',
				'data-empty-action' => isset($settings['empty_actions']) ? esc_attr($settings['empty_actions']) : '',
				'data-none-label' => isset($settings['select_field_placeholder']) ? esc_attr($settings['select_field_placeholder']) : '',
				'data-term-label' => is_array($term_label) ? esc_attr($term_label[0]) : esc_attr($term_label),
            ]
        );

		$this->output = '<div '. $this->get_render_attribute_string('gf_wrapper') .'>';

		if ( in_array(strtolower($filter_type), $valid_types) ) {
			
			if ( $filter_type === 'select' ) {

				$this->output .= '<div class="crt-af-select-group crt-advanced-filters-inner">';
				
				if ( $settings['enable_dependent_select'] === 'yes' ) {
					$this->dependent_count = 1;

					foreach ( $settings['dependent_select_repeater'] as $index => $filter ) {
						$this->filter_item = $filter;
	
						echo $this->render_general_filters($settings, $filter['dependent_select_taxonomy'], $filter_type, $vf_class, $filter['dependent_select_label']);
					}
				} else {
					echo $this->render_general_filters($settings, $taxonomy, $filter_type, $vf_class);
				}

				$this->output .= '</div>';
			} else {
				echo $this->render_general_filters($settings, $taxonomy, $filter_type, $vf_class);
			}

        } else {
			if ( 'yes' == $settings['show_label'] && isset($settings['label_text']) && !empty($settings['label_text']) ) {
				$this->output .= '<h4 class="crt-af-filters-label">' . esc_html__($settings['label_text']) . '</h4>';
			}

			if ( $filter_type == 'range' ) {
				echo $this->render_range_filter($settings);
			} else if ( $filter_type == 'apply' ) {
				echo $this->render_apply_all_filter($settings);
			} else if ( $filter_type == 'active' ) {
				echo $this->render_active_filters($settings);
			} else if ( $filter_type == 'date' || $filter_type == 'date_range' ) {
				echo $this->render_date_filter($settings);
			} else if ( $filter_type == 'search') {
				echo $this->render_search_filter($settings);
			} else if ( $filter_type == 'rating' ) {
				echo $this->render_rating_filter($settings);
			}
		}
    
		// Close the output
		$this->output .= '</div>';
    
		// Return the generated output
		return $this->output;
    }

	private function get_taxonomies_list() {
		$taxonomies = get_taxonomies([], 'objects');
		$options = [];

		foreach ($taxonomies as $taxonomy) {
			// Skip taxonomies we don't want to include
			if (in_array($taxonomy->name, ['elementor_library_type', 'elementor_library_category', 'crt_template_type'])) {
				continue;
			}

			if ( !isset($options[$taxonomy->name]) ) {
				$options[$taxonomy->name] = $taxonomy->label;
			}
		}

		return $options;
	}
	
	private function get_rating_buckets(array $query_args = []): array {
		global $wpdb;

		// Restrict to IDs per current filters.
		$args = wp_parse_args($query_args, [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		]);

		$q = new \WP_Query($args);
		$ids = $q->posts;
		if (empty($ids)) {
			return [1=>0,2=>0,3=>0,4=>0,5=>0];
		}
		$ids_csv = implode(',', array_map('absint', $ids));

		// Count rounded average ratings from meta.
		$sql = "
			SELECT ROUND(CAST(pm.meta_value AS DECIMAL)) AS rating, COUNT(*) AS cnt
			FROM {$wpdb->postmeta} pm
			WHERE pm.meta_key = '_wc_average_rating'
			AND pm.post_id IN ($ids_csv)
			AND pm.meta_value IS NOT NULL AND pm.meta_value <> '' AND pm.meta_value <> '0'
			GROUP BY ROUND(CAST(pm.meta_value AS DECIMAL))
		";
		$rows = $wpdb->get_results($sql);

		$buckets = [1=>0,2=>0,3=>0,4=>0,5=>0];
		foreach ($rows as $r) {
			$rr = (int) $r->rating;
			if ($rr >= 1 && $rr <= 5) {
				$buckets[$rr] = (int) $r->cnt;
			}
		}

		return $buckets;
	}

    protected function render() {
        $settings = $this->get_settings_for_display();

		echo $this->render_grid_filters($settings);
    }
}