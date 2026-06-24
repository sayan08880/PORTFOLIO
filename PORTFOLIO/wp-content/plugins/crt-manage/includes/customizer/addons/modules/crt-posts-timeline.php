<?php
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use CrtAddons\Classes\Utilities;

class CRT_Posts_Timeline extends Widget_Base {
	
	public function get_name() {
		return 'crt-posts-timeline';
	}

	public function get_title() {
		return esc_html__( 'Post/Story Timeline', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-time-line';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
	}

	public function get_keywords() {
		return ['post timeline', 'blog', 'post', 'posts', 'timeline', 'posts timeline', 'story timeline', 'content timeline'];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_script_depends() {
		// TODO: separate infinite-scroll from isotope
		return [ 'swiper', 'crt-aos-js', 'crt-infinite-scroll', 'crt-posts-timeline' ];
	}

	public function get_style_depends() {
		return [ 'swiper', 'crt-animations-css', 'crt-loading-animations-css', 'crt-aos-css', 'e-swiper' ];
    }

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        return 'https://crthemes.com/contact';
    }
	
	public $my_query;	
	public $animation;
	public $animation_loadmore_left;
	public $animation_loadmore_right;		
	public $timeline_fill;
	public $show_readmore;		
	public $pagination_type;
	public $pagination_max_page;
	public $pagination_max_pages;
	public $animation_class;
	public $timeline_layout;
	public $timeline_layout_wrapper;
	public $item_url_count;				
	public $thumbnail_size;
	public $thumbnail_custom_dimension;
	public $show_year_label;
	public $timeline_year;            
	public $image;
	public $slides_to_show;
	public $horizontal_inner_class;
	public $horizontal_timeline_class;
	public $swiper_class;
	public $timeline_description;
	public $story_date_label;
	public $story_extra_label;
	public $timeline_story_title;
	public $title_key;
	public $year_key;
	public $date_label_key;
	public $extra_label_key;
	public $description_key;
	public $background_image;
	public $background_class;
	public $src;

    public function crt_aos_animation_array(){
        return [
            "none" => "None",
            "fade" => "Fade",
            "fade-up" => "Fade Up",
            "fade-down" => "Fade Down",
            "fade-left" => "Fade Left",
            "fade-right" => "Fade Right",
            "fade-up-right" => "Fade Up Right",
            "fade-up-left" => "Fade Up Left",
            "fade-down-right" => "Fade Down Right",
            "fade-down-left" => "Fade Down Left",
            "flip-up" => "Flip Up",
            "flip-down" => "Flip Down",
            "flip-right" => "Flip right",
            "flip-left" => "Flip Left",
            "slide-up" => "Slide Up",
            "slide-left" => "Slide Left",
            "slide-right" => "Slide Right",
            "slide-down" => "Slide Down",
            "zoom-in" => "Zoom In",
            "zoom-out" => "Zoom Out",
            "zoom-in-up" => "Zoom In Up",
            "zoom-in-down" => "Zoom In Down",
            "zoom-in-left" => "Zoom In Left",
            "zoom-in-right" => "Zoom In Right",
            "zoom-out-up" => "Zoom Out Up",
            "zoom-out-down" => "Zoom Out Down",
            "zoom-out-left" => "Zoom Out Left",
            "zoom-out-right" => "Zoom Out Right"
        ];
    }

	public function background_blend_modes() {
		return [
			'normal' => 'Normal',
			'multiply' => 'Multiply',
			'screen' => 'Screen',
			'overlay' => 'Overlay',
			'darken' => 'Darken',
			'lighten' => 'Lighten',
			'color-dodge' => 'Color-Dodge',
			'color-burn' => 'Color-Burn',
			'hard-light' => 'Hard-Light',
			'soft-light' => 'Soft-Light',
			'difference' => 'Difference',
			'exclusion' => 'Exclusion',
			'hue' => 'Hue',
			'saturation' => 'Saturation',
			'color' => 'Color',
			'luminosity' => 'Luminosity'
		];
	}

	public function add_control_slides_to_show() {
		$this->add_control(
			'slides_to_show',
			[
				'label' => __( 'Slides To Show', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '3',
				'max' => '4',
				'separator' => 'before',
				'render_type' => 'template',
				'condition'   => [
					'timeline_layout'   => [
					   'horizontal',
					   'horizontal-bottom'
					],
				]
			]
		);
	}

	public function add_control_swiper_loop() {
		$this->add_control(
			'swiper_loop',
			[
				'label' => __( 'Loop', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => ''
			]
		);
	}

	public function add_control_group_autoplay() {
		$this->add_control(
			'swiper_autoplay',
			[
				'label' => __( 'Autoplay', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => ''
			]
		);

        $this->add_control(
            'swiper_delay',
            [
                'label' => esc_html__( 'Autoplay Delay', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 5000,
                'frontend_available' => true,
                'default' => 500,
                'condition' => [
                    'swiper_autoplay' => 'yes',
                    'timeline_layout' => ['horizontal', 'horizontal-bottom']
                ]
            ]
        );

        $this->add_control(
            'swiper_pause_on_hover',
            [
                'label' => esc_html__( 'Pause on Hover', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'label_block' => false,
                'condition' => [
                    'timeline_layout'   => [
                        'horizontal-bottom',
                        'horizontal'
                    ],
                    'swiper_autoplay' => 'yes'
                ],
                'render_type' => 'template',
            ]
        );
    }

	public function add_control_show_pagination() {
		$this->add_control(
			'show_pagination',
			[
				'label' => __( 'Show Pagination', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => ''
			]
		);	
	}

	public function add_control_posts_per_page() {
        $this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'crt-manage'),
				'type' => Controls_Manager::NUMBER,
				'render_type' => 'template',
				'default' => 3,
				'max' => 4,
                'min' => 0,
				'label_block' => false,
			]
		);
	}

	protected function register_controls() {

		$this->start_controls_section(
			'general_section',
			[
				'label' => __( 'Layout', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'timeline_content',
			[
				'label' => __( 'Timeline Content', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'custom',
				'options'=>[
					'custom' => esc_html__('Custom', 'crt-manage'),
					'dynamic' => esc_html__('Dynamic', 'crt-manage')
				],
				'render_type' => 'template',
			]
		);
	
		$this->add_control(
			'timeline_layout',
			[
				'label' => __( 'Layout', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'centered',
				'options'=>[
					'centered'=> esc_html__('Zig-Zag', 'crt-manage'),
					'one-sided'=> esc_html__('Line Left', 'crt-manage'),
					'one-sided-left'=> esc_html__('Line Right', 'crt-manage'),
					'horizontal-bottom'=> esc_html__('Line Top - Carousel', 'crt-manage'),
					'horizontal'=> esc_html__('Line Bottom - Carousel', 'crt-manage'),
				],
				'render_type' => 'template',
			]
		);
	
		$this->add_control(
			'content_layout',
			[
				'label' => __( 'Media Position', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'image-top',
				'options'=>[
					'image-top' => esc_html__('Top', 'crt-manage'),
					'image-bottom' => esc_html__('Bottom', 'crt-manage'),
					// 'background' => esc_html__('Background', 'crt-manage'),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[ 
				'name' => 'crt_thumbnail_dynamic',
				'default' => 'full',
				'separator' => 'none',
				'condition' => [
					'timeline_content' => 'dynamic'
				]
			]
		);
	
		$this->add_control(
			'date_format',
			[
				'label' => __( 'Date Format', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'F j, Y',
				'options'=> [
					'F j, Y' => esc_html__(date('F j, Y')),
					'Y-m-d' => esc_html__(date('Y-m-d')),
					'Y, M, D' => esc_html__(date('Y, M, D')),
					'm/d/Y' => esc_html__(date('m/d/Y')),
					'd/m/Y' => esc_html__(date('d/m/Y')),
					'j. F Y' => esc_html__(date('j. F y'))
				],
				'condition' => [
					'timeline_content' => 'dynamic',
				]
			]
		);

		$this->add_control(
			'timeline_fill',
			[
				'label' => esc_html__( 'Main Line Fill', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);
		
		$this->add_control(
			'posts_icon',
			[
				'label' => __( 'Main Line Icon', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-apple',
					'library' => 'solid',
				],
				'condition' => [
					'timeline_content' => 'dynamic'
				]
			]
		);
		
		$this->add_control(
			'show_extra_label',
			[
				'label' => esc_html__( 'Show Extra Label', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'separator' => 'before',
				'condition' => [
					'timeline_content' => 'dynamic'
				]
			]
		);

		$this->add_control(
			'extra_label_source',
			[
				'label' => esc_html__( 'Extra Label Source', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'publish_date',
				'options' => [
					'publish_date' => esc_html__( 'Publish Date', 'crt-manage' ),
					'meta_field' => esc_html__( 'Meta Field', 'crt-manage' ),
				],
				'condition' => [
					'timeline_content' => 'dynamic',
					'show_extra_label' => 'yes',
				]
			]
		);
		
        $this->add_control(
			'meta_field_key',
			[
				'label' => esc_html__( 'Select Custom Field', 'crt-manage' ),
				// 'type' => Controls_Manager::SELECT2,
				'type' => 'crt-ajax-select2',
				'label_block' => true,
				'default' => 'default',
				'description' => '<strong>Note:</strong> This option only accepts String(Text) or Numeric Custom Field Values.',
				// 'options' => $meta,
				'options' => 'ajaxselect2/get_custom_meta_keys',
				'condition' => [
					'timeline_content' => 'dynamic',
					'show_extra_label' => 'yes',
					'extra_label_source' => 'meta_field',
				]
			]
		);
		
		$this->add_control_slides_to_show();

//		if ( !defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code() ) {
//			$this->add_control(
//				'slides_to_show_pro_notice',
//				[
//					'type' => Controls_Manager::RAW_HTML,
//					'raw' => 'More than 4 Slides are available<br> in the <strong><a href="https://crthemes.com/?ref=rea-plugin-panel-posts-timeline-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
//					// 'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'. admin_url('admin.php?page=crt-addons-pricing') .'" target="_blank">Pro version</a></strong>',
//					'content_classes' => 'crt-pro-notice',
//					'condition'   => [
//						'timeline_layout'   => [
//						   'horizontal',
//						   'horizontal-bottom'
//						],
//					]
//				]
//			);
//		}
				
		$this->add_control(
			'story_info_gutter',
			[
				'label' => __( 'Gutter', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 5,
				'condition'   => [
					'timeline_layout'   => [
					   'horizontal',
					   'horizontal-bottom'
					],
				]
			]
		);

		$this->add_control(
			'equal_height_slides',
			[
				'label' => esc_html__( 'Equal Height Slides', 'crt-manage' ),
				'description' => __('Make all slides the same height','crt-manage'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'auto-height',
				'default' => 'no',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'timeline_layout'   => [
					   'horizontal-bottom',
					   'horizontal'
					],
				]
			]
		);

		// $this->add_control(
		// 	'horizontal_timeline_progressfill',
		// 	[
		// 		'label' => esc_html__( 'Hide Progressbar Fill', 'crt-manage' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'label_on' => esc_html__( 'Show', 'your-plugin' ),
		// 		'label_off' => esc_html__( 'Hide', 'your-plugin' ),
		// 		'default' => 'yes',
		// 		'label_block' => false,
		// 		'render_type' => 'template',
		// 		'selectors_dictionary' => [
		// 			'' => 'display: none;'
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .swiper-pagination-progressbar-fill' => '{{VALUE}}',
		// 		],
		// 		'condition' => [
		// 			'timeline_layout'   => [
		// 			   'horizontal-bottom',
		// 			   'horizontal'
		// 			],
		// 		]
		// 	]
		// );

		$this->add_control_swiper_loop();

		$this->add_control_group_autoplay();
				
		$this->add_control(
			'swiper_speed',
			[
				'label' => esc_html__( 'Carousel Speed', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5000,
				'frontend_available' => true,
				'default' => 500,
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);
		
		$this->add_control(
			'swiper_nav_icon',
			[
				'label' => esc_html__( 'Carousel Icon', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle-left',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle-left' => esc_html__( 'Angle', 'crt-manage' ),
					'fas fa-angle-double-left' => esc_html__( 'Angle Double', 'crt-manage' ),
					'fas fa-arrow-left' => esc_html__( 'Arrow', 'crt-manage' ),
					'fas fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle', 'crt-manage' ),
					'far fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle Alt', 'crt-manage' ),
					'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'crt-manage' ),
					'fas fa-chevron-left' => esc_html__( 'Chevron', 'crt-manage' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'crt-manage' ),
				] ),
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom'],
				],
				// 'separator' => 'before',
			]
		);
		
		$this->add_control(
			'timeline_animation',
			[
				'label' => __( 'Entrance Animation', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'fade',
				'separator' => 'before',
				'options' => $this->crt_aos_animation_array(),
				'condition'   => [
					'timeline_layout!'   => [
						'horizontal',
						'horizontal-bottom'
					 ],
				]
			]
		);


		$this->add_control(
			'animation_offset',
			[
				'label' => esc_html__( 'Animation Offset', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 500,
				'frontend_available' => true,
				'default' => 150,
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'aos_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 2000,
				'frontend_available' => true,
				'default' => 600,
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control_show_pagination();
		
		$this->end_controls_section();

		$this->start_controls_section(
			'repeater_content_section',
			[
				'label' => __( 'Timeline Items', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'timeline_content' => 'custom'
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->start_controls_tabs(
			'story_tabs'
		);

		$repeater->start_controls_tab(
			'content_tab',
			[
				'label' => __( 'Content', 'crt-manage' ),
			]
		);

		$repeater->add_control(
			'main_line_label_heading',
			[
				'label' => esc_html__( 'Main Line Label', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$repeater->add_control(
			'repeater_show_year_label',
			[
				'label' => __( 'Show Label', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'crt-manage' ),
				'label_off' => __( 'Hide', 'crt-manage' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$repeater->add_control(
			'repeater_year',
			[
				'label' => __( 'Label Text', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '2022',
				'condition' => [
					'repeater_show_year_label' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'main_line_label_icon',
			[
				'label' => esc_html__( 'Main Line Icon', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'repeater_story_icon',
			[
				'label' => __( 'Select Icon', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-apple',
					'library' => 'solid',
				],
			]
		);

		$repeater->add_control(
			'extra_label_heading',
			[
				'label' => esc_html__( 'Extra Label', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$repeater->add_control(
			'repeater_show_extra_label',
			[
				'label' => esc_html__( 'Show Extra Label', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
			]
		);
		
		$repeater->add_control(
			'repeater_date_label',
			[
				'label' => __( 'Primary Label', 'crt-manage' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '01 Jan 2022',
				'condition' => [
					'repeater_show_extra_label' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_extra_label',
			[
				'label' => __( 'Secondary Label', 'crt-manage' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Secondaty Label',
				'condition' => [
					'repeater_show_extra_label' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_media',
			[
				'label' => esc_html__( 'Display Media', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => [
					'image' => esc_html__( 'Image', 'crt-manage' ),
					'icon' => esc_html__( 'Icon', 'crt-manage' ),
					'video' => esc_html__( 'Video', 'crt-manage' ),
				],
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[ 
				'name' => 'crt_thumbnail',
				'default' => 'full',
				'separator' => 'none',
				'condition' => [
					'repeater_media' => 'image'
				]
			]
		);
				
		$repeater->add_control(
			'repeater_youtube_video_url',
			[
				'label' => __( 'Youtube Video Link', 'twae1' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => '',
				'condition'   => [
					'repeater_media' => 'video',
				]
			]
		);

		$repeater->add_control(
			'repeater_image',
			[
				'label' => __( 'Choose Image', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active'=> true
                ],
				'description' => __('Image Size will not work with default image','crt-manage'),
				'condition' => [
					'repeater_media' => 'image'
				]
			]
		);

		$repeater->add_control(
			'repeater_timeline_item_icon',
			[
				'label' => __( 'Media Icon', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-address-book',
					'library' => 'solid',
				],
				'condition' => [
					'repeater_media' => 'icon'
				]
			]
		);

		$repeater->add_control(
			'repeater_story_title',
			[
				'label' => __( 'Item Title', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Timeline Story',
				'label_block' => true,
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'repeater_title_link',
			[
				'label' => esc_html__( 'Item Title URL', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'crt-manage' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
			]
		);

		$repeater->add_control(
			'repeater_description',
			[
				'label' => __( 'Description', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => 'Add Description Here',
			]
		);
		
		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'repeater_advanced_tab',
			[
				'label' => __( 'STYLE', 'crt-manage' ),
			]
		);

		$repeater->add_control(
			'show_custom_styles',
			[
				'label' => esc_html__( 'Custom Colors', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false
			]
		);

		$repeater->add_control(
			'item_main_styles',
			[
				'label' => __('Item','crt-manage'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_custom_styles' => 'yes'
				]				
			]
		);

		$repeater->add_control(
			'item_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-story-info-vertical.crt-data-wrap' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-horizontal-bottom-timeline {{CURRENT_ITEM}} .crt-story-info' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-horizontal {{CURRENT_ITEM}} .crt-story-info' => 'background-color: {{VALUE}}'
				],
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_story_border_color',
			[
				'label' => __( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-story-info' => 'border-color: {{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}}.crt-left-aligned .crt-story-info-vertical' => 'border-color: {{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}}.crt-right-aligned .crt-story-info-vertical' => 'border-color: {{VALUE}} !important;',

					// TODO: background colors for repeater arrows
					'{{WRAPPER}} {{CURRENT_ITEM}}.swiper-slide-line-top .crt-story-info:before' => 'border-bottom-color: {{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}}.swiper-slide-line-bottom .crt-story-info:before' => 'border-top-color: {{VALUE}} !important',
					'{{WRAPPER}} {{CURRENT_ITEM}}.crt-left-aligned .crt-story-info-vertical:after' => 'border-left-color: {{VALUE}} !important',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .crt-wrapper .crt-both-sided-timeline .crt-left-aligned .crt-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
					'{{WRAPPER}} .crt-centered .crt-one-sided-timeline .crt-right-aligned-aligned .crt-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
				],
				'default' => '#605BE5',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_triangle_color',
			[
				'label' => __('Triangle','crt-manage'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_custom_styles' => 'yes'
				]			
			]
		);

		$repeater->add_control(
			'repeater_triangle_bgcolor',
			[
				'label' => __( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-one-sided-timeline {{CURRENT_ITEM}}.crt-right-aligned .crt-data-wrap:after' => 'border-right-color: {{icon_bgcolor}}',
					'{{WRAPPER}} .crt-wrapper .crt-one-sided-timeline-left {{CURRENT_ITEM}}.crt-left-aligned .crt-data-wrap:after' => 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} .crt-wrapper {{CURRENT_ITEM}}.crt-right-aligned .crt-data-wrap:after' => 'border-right-color: {{VALUE}}',
					'{{WRAPPER}} .crt-horizontal {{CURRENT_ITEM}} .crt-story-info:before' => 'border-top-color: {{VALUE}} !important',
					'{{WRAPPER}} .crt-horizontal-bottom {{CURRENT_ITEM}} .crt-story-info:before' => 'border-bottom-color: {{VALUE}} !important',
					'{{WRAPPER}} .crt-wrapper {{CURRENT_ITEM}}.crt-left-aligned .crt-data-wrap:after' => 'border-left-color: {{VALUE}} !important',
					'{{WRAPPER}} .crt-centered {{CURRENT_ITEM}} .crt-one-sided-timeline .crt-right-aligned .crt-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
					'{{WRAPPER}} .crt-wrapper {{CURRENT_ITEM}} .crt-one-sided-timeline-left .crt-left-aligned .crt-data-wrap:after' => 'border-left-color: {{VALUE}} !important',
				],
				'default' => '#605BE5',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
				// 'separator' => 'after',
			]
		);

		$repeater->add_control(
			'repeater_media_styles',
			[
				'label' => __('Media','crt-manage'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_custom_styles' => 'yes'
				]			
			]
		);

		$repeater->add_control(
			'repeater_overlay_bgcolor',
			[
				'label' => __( 'Overlay Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper {{CURRENT_ITEM}} .crt-timeline-story-overlay' => 'background-color: {{VALUE}}',
				],
				'default' => '#0000005E',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_media_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-timeline-media' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_item_content_styles',
			[
				'label' => __('Content','crt-manage'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_custom_styles' => 'yes'
				]			
			]
		);
		
		/*---- Story Title ----*/
		$repeater->add_control(
			'repeater_story_title_color',
			[
				'label' => __( 'Title Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-title' => 'color: {{VALUE}} !important;',
				],
				'default' => '#444444',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_description_color',
			[
				'label' => __( 'Description Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper {{CURRENT_ITEM}} .crt-description' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-wrapper {{CURRENT_ITEM}} .crt-description p' => 'color: {{VALUE}};'
				],
				'default' => '#333333',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'item_content_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-timeline-content-wrapper' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_main_line_content_styles',
			[
				'label' => __('Main Line','crt-manage'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_custom_styles' => 'yes'
				]			
			]
		);
		
		$repeater->add_control(
			'repeater_timeline_icon_color',
			[
				'label' => __( 'Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}  .crt-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}}  .crt-icon svg' => 'fill: {{VALUE}}'
				],
				'default' => '#000',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_icon_timeline_fill_color',
			[
				'label'  => esc_html__( 'Icon Fill Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-change-border-color.crt-icon i' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-change-border-color.crt-icon svg' => 'fill: {{VALUE}} !important;',
				],
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);
		
		$repeater->add_control(
			'repeater_timeline_icon_bg_color',
			[
				'label' => __( 'Icon Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper {{CURRENT_ITEM}} .crt-icon' => 'background-color: {{VALUE}} !important;',
				],
				'default' => '#FFFFF',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_icon_timeline_background_fill_color',
			[
				'label'  => esc_html__( 'Icon Background Fill Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-change-border-color.crt-icon' => 'background-color: {{VALUE}} !important;',
				],
				'condition' => [
					'show_custom_styles' => 'yes'
				],
			]
		);

		$repeater->add_control(
			'repeater_icon_border_color',
			[
				'label'  => esc_html__( 'Icon Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper {{CURRENT_ITEM}} .crt-icon' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'item_icon_styles',
			[
				'label' => __('Media Icon','crt-manage'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'repeater_media' => 'icon',
				],				
			]
		);
		
		$repeater->add_control(
			'repeater_timeline_item_icon_color',
			[
				'label' => __( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}  .crt-timeline-media i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}}  .crt-timeline-media svg' => 'fill: {{VALUE}}'
				],
				'condition' => [
					'repeater_media' => 'icon',
				],
				'default' => '#000',
			]
		);
		
		$repeater->add_control(
			'repeater_timeline_item_icon_bgcolor',
			[
				'label' => __( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-timeline-media' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'repeater_media' => 'icon',
				],
				'default' => '#FFF',
			]
		);
		
		$repeater->add_responsive_control(
			'repeater_timeline_item_icon_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 600,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-timeline-media i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-timeline-media svg' => 'width: {{SIZE}}{{UNIT}};',
					
				],
				'condition' => [
					'repeater_media' => 'icon',
				]
			]
		);
		
		$repeater->add_responsive_control(
			'repeater_timeline_item_icon_padding',
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
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-timeline-media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					// 'show_overlay!' => 'yes',
					// 'timeline_content' => 'custom',
					'repeater_media' => 'icon'
				],
			]
		);

		$repeater->add_responsive_control(
			'repeater_timeline_item_icon_alignment',
			[
				'label' => esc_html__( 'Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'crt-manage' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
				],
                'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-timeline-media i' => 'display: block; text-align: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-timeline-media svg' => 'text-align: {{VALUE}};'
				],
				'condition' => [
					// 'timeline_content' => 'custom',
					'repeater_media' => 'icon'
				]
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'timeline_repeater_list',
			[
				
				'label' => __( 'Content', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'repeater_story_title' => __( 'Timeline Item 1', 'crt-manage' ),
						'repeater_description' => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo.','crt-manage'),
						'repeater_year'			=> __('2021','crt-manage'),
						'repeater_date_label'   => __('Jan 2021','crt-manage'),
						'repeater_extra_label'  => __('Company Established','crt-manage'),
						'repeater_story_icon' => [
							'value' => 'far fa-flag',
							'library' => 'solid'
						],
						'repeater_show_year_label' => 'yes',
						'repeater_image' =>[
							'url' => Utils::get_placeholder_image_src(),	
							'id' => '',						
						],
						'repeater_youtube_video_url' => '',
						'item_bg_color' => '#E71919',
						'repeater_triangle_bgcolor' => '#E71919',
						'repeater_overlay_bgcolor' => '#0000005E',
						'repeater_story_title_color' => '#FCFCFC',
						'repeater_description_color' => '#ECECEC',
						'repeater_timeline_icon_bg_color' => '',
						'item_content_border_color' => '#E8E8E8',
						'repeater_timeline_icon_color' => '#E8E8E8',
						'repeater_icon_timeline_fill_color' => '#E71919',
						'repeater_icon_timeline_background_fill_color' => '#FFFFFF',
						'repeater_icon_border_color' => '#E8E8E8'
					],
					[
						'repeater_story_title' => __( 'Timeline Item 2', 'crt-manage' ),
						'repeater_description' => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo.','crt-manage'),
						'repeater_year'			=> __('2021','crt-manage'),
						'repeater_date_label'   => __('March 2021','crt-manage'),
						'repeater_extra_label'  => __('New office in California','crt-manage'),
						'repeater_story_icon' => [
							'value' => 'far fa-paper-plane',
							'library' => 'solid'
						],
						'repeater_image' =>[
							'url' => Utils::get_placeholder_image_src(),
							'id' => '',							
						],
						'repeater_youtube_video_url' => '',
						'item_bg_color' => '#ECB824',
						'repeater_triangle_bgcolor' => '#ECB824',
						'repeater_overlay_bgcolor' => '#0000005E',
						'repeater_story_title_color' => '#FCFCFC',
						'repeater_description_color' => '#ECECEC',
						'repeater_timeline_icon_bg_color' => '',
						'item_content_border_color' => '#E8E8E8',
						'repeater_timeline_icon_color' => '#E8E8E8',
						'repeater_icon_timeline_fill_color' => '#ECB824',
						'repeater_icon_timeline_background_fill_color' => '#FFFFFF',
						'repeater_icon_border_color' => '#E8E8E8'	
					],
					[
						'repeater_story_title' => __( 'Timeline Item 3', 'crt-manage' ),
						'repeater_description' => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo.','crt-manage'),
						'repeater_year'			=> __('2022','crt-manage'),
						'repeater_date_label'   => __('April 2022','crt-manage'),
						'repeater_extra_label'  => __('First Product Launch','crt-manage'),
						'repeater_story_icon' => [
							'value' => 'far fa-lightbulb',
							'library' => 'solid'
						],
						'repeater_show_year_label' => 'yes',
						'repeater_image' =>[
							'url' => Utils::get_placeholder_image_src(),
							'id' => '',						
						],
						'repeater_youtube_video_url' => '',
						'item_bg_color' => '#1BE620',
						'repeater_triangle_bgcolor' => '#1BE620',
						'repeater_overlay_bgcolor' => '#0000005E',
						'repeater_story_title_color' => '#FCFCFC',
						'repeater_description_color' => '#FDFDFD',
						'item_content_border_color' => '#E8E8E8',
						'repeater_timeline_icon_bg_color' => '',
						'repeater_timeline_icon_color' => '#E8E8E8',
						'repeater_icon_timeline_fill_color' => '#1BE620',
						'repeater_icon_timeline_background_fill_color' => '#FFFFFF',
						'repeater_icon_border_color' => '#E8E8E8'
					],
					[
						'repeater_story_title' => __( 'Timeline Item 4', 'crt-manage' ),
						'repeater_description' => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo.','crt-manage'),
						'repeater_year'			=> __('2022','crt-manage'),
						'repeater_date_label'   => __('September 2022','crt-manage'),
						'repeater_extra_label'  => __('Entering Stock Market','crt-manage'),
						'repeater_story_icon' => [
							'value' => 'fas fa-bolt',
							'library' => 'solid'
						],
						'repeater_image' =>[
							'url' => Utils::get_placeholder_image_src(),
							'id' => '',						
						],
						'repeater_youtube_video_url' => '',
						'item_bg_color' => '#D82F8E',
						'repeater_triangle_bgcolor' => '#D82F8E',
						'repeater_overlay_bgcolor' => '#0000005E',
						'repeater_story_title_color' => '#FCFCFC',
						'repeater_description_color' => '#F3F3F3',
						'item_content_border_color' => '#E8E8E8',
						'repeater_timeline_icon_bg_color' => '',
						'repeater_timeline_icon_color' => '#E8E8E8',
						'repeater_icon_timeline_fill_color' => '#D82F8E',
						'repeater_icon_timeline_background_fill_color' => '#FFFFFF',
						'repeater_icon_border_color' => '#E8E8E8'
					],
				],
				'title_field' => '{{{ repeater_story_title }}}',
			]
		);

//		if ( !defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code() ) {
//			$this->add_control(
//				'timeline_repeater_pro_notice',
//				[
//					'type' => Controls_Manager::RAW_HTML,
//					'raw' => 'More than 4 Slides are available<br> in the <strong><a href="https://crthemes.com/?ref=rea-plugin-panel-posts-timeline-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
//					// 'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'. admin_url('admin.php?page=crt-addons-pricing') .'" target="_blank">Pro version</a></strong>',
//					'content_classes' => 'crt-pro-notice',
//				]
//			);
//		}

		$this->end_controls_section();

		// Get Available Post Types
		$post_types = $this->add_option_query_source();

		// Get Available Taxonomies
		$post_taxonomies = Utilities::get_custom_types_of( 'tax', false );

        $this->start_controls_section(
            'query_section',
            [
                'label' => __('Query', 'crt-manage'),
				'condition' => [
					'timeline_content' => 'dynamic',
				]
            ]
        );

        $this->add_control(
			'timeline_post_types',
			[
				'label' => esc_html__( 'Post Type', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'default' => 'post',
				'label_block' => false,
				'options' => $post_types,
			]
		);

		// Upgrade to Pro Notice
//		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'posts-timeline', 'timeline_post_types', ['pro-rl'] );

//		if ( !defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->is_plan( 'expert' ) ) {
//			$this->add_control(
//				'query_source_cpt_pro_notice',
//				[
//					'raw' => 'This option is available<br> in the <strong><a href="https://crthemes.com/?ref=rea-plugin-panel-grid-upgrade-expert#purchasepro" target="_blank">Expert version</a></strong>',
//					'type' => Controls_Manager::RAW_HTML,
//					'content_classes' => 'crt-pro-notice',
//					'condition' => [
//						'timeline_post_types!' => ['post','page','related','current','pro-rl'],
//					]
//				]
//			);
//		}
//
        $this->add_control(
			'query_selection',
			[
				'label' => esc_html__( 'Selection', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic' => esc_html__( 'Dynamic', 'crt-manage' ),
					'manual' => esc_html__( 'Manual', 'crt-manage' ),
				],
				'condition' => [
					'timeline_post_types!' => [ 'current', 'related' ],
				],
			]
		);

		$this->add_control(
			'query_tax_selection',
			[
				'label' => esc_html__( 'Selection Taxonomy', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => $post_taxonomies,
				'condition' => [
					'timeline_post_types' => 'related',
				],
			]
		);
		
		// Manual Selection
		foreach ( $post_types as $slug => $title ) {
			$this->add_control(
				'query_manual_'. $slug,
				[
					'label' => esc_html__( 'Select ', 'crt-manage' ) . $title,
					'type' => 'crt-ajax-select2',
					'options' => 'ajaxselect2/get_posts_by_post_type',
					'query_slug' => $slug,
					'multiple' => true,
					'label_block' => true,
					'condition' => [
						'timeline_post_types' => $slug,
						'query_selection' => 'manual',
					],
					'separator' => 'before',
				]
			);
		}

        $this->add_control(
			'query_author',
			[
				'label' => esc_html__( 'Authors', 'crt-manage' ),
				'type' => 'crt-ajax-select2',
				'options' => 'ajaxselect2/get_users',
				'multiple' => true,
				'label_block' => true,
				'separator' => 'before',
				'condition' => [
					'timeline_post_types!' => [ 'current', 'related' ],
					'query_selection' => 'dynamic',
				],
			]
		);

		foreach ( $post_taxonomies as $slug => $title ) {
			global $wp_taxonomies;
			$post_type = '';
			if ( isset($wp_taxonomies[$slug]) && isset($wp_taxonomies[$slug]->object_type[0]) ) {
				$post_type = $wp_taxonomies[$slug]->object_type[0];
			}

			$this->add_control(
				'query_taxonomy_'. $slug,
				[
					'label' => $title,
					'type' => 'crt-ajax-select2',
					'options' => 'ajaxselect2/get_taxonomies',
					'query_slug' => $slug,
					'multiple' => true,
					'label_block' => true,
					'condition' => [
						'timeline_post_types' => $post_type,
						'query_selection' => 'dynamic',
					],
				]
			);
		}

		foreach ( $post_types as $slug => $title ) {
			$this->add_control(
				'query_exclude_'. $slug,
				[
					'label' => esc_html__( 'Exclude ', 'crt-manage' ) . $title,
					'type' => 'crt-ajax-select2',
					'options' => 'ajaxselect2/get_posts_by_post_type',
					'query_slug' => $slug,
					'multiple' => true,
					'label_block' => true,
					'condition' => [
						'timeline_content' => 'dynamic',
						'timeline_post_types' => $slug,
						'timeline_post_types!' => [ 'current', 'related' ],
						'query_selection' => 'dynamic',
					],
				]
			);
		}

        $this->add_control_posts_per_page();

//		if ( !defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code() ) {
//			$this->add_control(
//				'posts_per_page_pro_notice',
//				[
//					'type' => Controls_Manager::RAW_HTML,
//					'raw' => 'More than 4 Posts are available<br> in the <strong><a href="https://crthemes.com/?ref=rea-plugin-panel-posts-timeline-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
//					// 'raw' => 'More than 4 Posts are available<br> in the <strong><a href="'. admin_url('admin.php?page=crt-addons-pricing') .'" target="_blank">Pro version</a></strong>',
//					'content_classes' => 'crt-pro-notice',
//				]
//			);
//		}

        $this->add_control(
			'order_posts',
			[
				'label' => esc_html__( 'Order By', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'label_block' => false,
				'options' => [
					'title' => esc_html__( 'Title', 'crt-manage'),
					'date' => esc_html__( 'Date', 'crt-manage'),
				],
				'condition' => [
					'query_selection' => 'dynamic',
				]
			]
		);

        $this->add_control(
			'order_direction',
			[
				'label' => esc_html__( 'Order', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'label_block' => false,
				'options' => [
					'ASC' => esc_html__( 'Ascending', 'crt-manage'),
					'DESC' => esc_html__( 'Descending', 'crt-manage'),
				],
				'condition' => [
					'query_selection' => 'dynamic',
				]
			]
		);

		$this->add_control(
			'query_not_found_text',
			[
				'label' => esc_html__( 'Not Found Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'No Posts Found!',
				'condition' => [
					// 'query_selection' => 'dynamic',
					// 'query_source!' => 'related',
				]
			]
		);

		$this->add_control(
			'query_exclude_no_images',
			[
				'label' => esc_html__( 'Exclude Items without Thumbnail', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'crt-manage'),
				'condition' => [
					// 'timeline_content' => 'dynamic',
				]
            ]
        );
		
		$this->add_responsive_control(
			'content_alignment_left',
			[
				'label' => esc_html__( 'Content Align', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-story-info' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-left-aligned .crt-story-info-vertical' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-left-aligned .crt-title-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-left-aligned .crt-description' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-left-aligned .crt-inner-date-label' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .swiper-wrapper .crt-title-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .swiper-wrapper .crt-description' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .swiper-wrapper .crt-inner-date-label' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-title-wrap' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided-left', 'horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Content Align (Right)', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-story-info' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-right-aligned .crt-story-info-vertical' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-right-aligned .crt-title-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-right-aligned .crt-description' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-right-aligned .crt-inner-date-label' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-title-wrap' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided']
				]
			]
		);
		
		$this->add_control(
			'show_overlay',
			[
				'label' => esc_html__( 'Show Image Overlay', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'separator' => 'before',
				'render_type' => 'template',
				'condition' => [
					'content_layout' => 'image-top'
				],
			]
		);

		$this->add_control(
			'show_on_hover',
			[
				'label' => esc_html__( 'Show Items on Hover', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'selectors_dictionary' => [
					'yes' => 'opacity: 0; transform: translateY(-50%); transition: all 0.5s ease',
					'no' => 'visibility: visible;',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-story-info' => '{{VALUE}}',
					'{{WRAPPER}} .crt-horizontal-timeline .swiper-slide:hover .crt-story-info' => 'opacity: 1; transform: translateY(0%);'
				],
				'condition' => [
					'timeline_layout' => ['horizontal']
				]
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Title', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'span',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'condition' => [
					'show_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'title_overlay',
			[
				'label' => esc_html__( 'Title Over Image', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'show_overlay' => 'yes',
					'content_layout' => 'image-top',
					'show_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_date',
			[
				'label' => esc_html__( 'Show Date', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'timeline_content' => 'dynamic'
				]
			]
		);

		$this->add_control(
			'date_source',
			[
				'label' => esc_html__( 'Date Source', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'publish_date',
				'options' => [
					'publish_date' => esc_html__( 'Publish Date', 'crt-manage' ),
					'meta_field' => esc_html__( 'Meta Field', 'crt-manage' ),
				],
				'condition' => [
					'timeline_content' => 'dynamic',
					'show_date' => 'yes',
				]
			]
		);
		
        $this->add_control(
			'date_field_key',
			[
				'label' => esc_html__( 'Select Custom Field', 'crt-manage' ),
				// 'type' => Controls_Manager::SELECT2,
				'type' => 'crt-ajax-select2',
				'label_block' => true,
				'default' => 'default',
				'description' => '<strong>Note:</strong> This option only accepts String(Text) or Numeric Custom Field Values.',
				// 'options' => $meta,
				'options' => 'ajaxselect2/get_custom_meta_keys',
				'condition' => [
					'timeline_content' => 'dynamic',
					'show_date' => 'yes',
					'date_source' => 'meta_field',
				]
			]
		);

		$this->add_control(
			'date_overlay',
			[
				'label' => esc_html__( 'Date Over Image', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				// 'render_type' => 'template',
				'condition' => [
					'show_overlay' => 'yes',
					'content_layout' => 'image-top',
					'timeline_content' => 'dynamic',
					'show_date' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_description',
			[
				'label' => esc_html__( 'Show Description', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					// 'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'description_overlay',
			[
				'label' => esc_html__( 'Description Over Image', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'condition' => [
					'show_overlay' => 'yes',
					'content_layout' => 'image-top',
					'show_description' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'excerpt_count',
			[
				'label' => esc_html__( 'Excerpt Count', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 200,
				'render_type' => 'template',
				'frontend_available' => true,
				'default' => 10,
				'condition' => [
					'timeline_content' => 'dynamic',
					'show_description' => 'yes'
				]
			]
		);

		// $this->add_control( //TODO: where does it work
		// 	'show_image',
		// 	[
		// 		'label' => esc_html__( 'Show Image', 'crt-manage' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'return_value' => 'yes',
		// 		'default' => 'yes',
		// 		'label_block' => false,
		// 		'render_type' => 'template',
		// 	]
		// );

		$this->add_control(
			'show_readmore',
			[
				'label' => esc_html__( 'Show Read More', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'timeline_content' => ['dynamic']
				]
			]
		);

		$this->add_control(
			'readmore_overlay',
			[
				'label' => esc_html__( 'Read More Over Image', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'show_overlay' => 'yes',
					'show_readmore' => 'yes',
					'content_layout' => 'image-top',
					'timeline_content' => ['dynamic']
				]
			]
		);

		$this->add_responsive_control (
			'readmore_content_alignment_left',
			[
				'label' => esc_html__( 'Align', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-left-aligned .crt-read-more-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-left-aligned .crt-read-more-button' => 'text-align: center;',
					'{{WRAPPER}} .swiper-wrapper .crt-read-more-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .swiper-wrapper .crt-read-more-button' => 'text-align: center;',
				],
				'condition' => [
					'show_readmore' => 'yes',
					'timeline_content' => ['dynamic'],
					'timeline_layout!' => 'one-sided',
				]
			]
		);

		$this->add_responsive_control (
			'readmore_content_alignment',
			[
				'label' => esc_html__( 'Align (Right)', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-right-aligned .crt-read-more-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-right-aligned .crt-read-more-button' => 'text-align: center;',
				],
				'condition' => [
					'show_readmore' => 'yes',
					'timeline_content' => ['dynamic'],
					'timeline_layout' => ['centered', 'one-sided']
				]
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label' => esc_html__( 'Read More', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Read More',
				'condition' => [
					'show_readmore' => 'yes',
					'timeline_content' => 'dynamic'
				]
			]
		);

		$this->add_control(
			'enable_img_link',
			[
				'label' => esc_html__( 'Enable Image Link', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'label_block' => false,
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'overlay_section',
			[
				'label' => __( 'Overlay', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'content_layout' => 'image-top',
					'show_overlay' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'overlay_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-timeline-story-overlay' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					// '{{WRAPPER}} .crt-timeline-story-overlay[class*="-top"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					// '{{WRAPPER}} .crt-timeline-story-overlay[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					// '{{WRAPPER}} .crt-timeline-story-overlay[class*="-right"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
					// '{{WRAPPER}} .crt-timeline-story-overlay[class*="-left"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_hegiht',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-timeline-story-overlay' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					// '{{WRAPPER}} .crt-timeline-story-overlay[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					// '{{WRAPPER}} .crt-timeline-story-overlay[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					// '{{WRAPPER}} .crt-timeline-story-overlay[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					// '{{WRAPPER}} .crt-timeline-story-overlay[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'overlay_content_alignment_vertical',
			[
				'label' => esc_html__( 'Content Vertical Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'crt-manage' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'crt-manage' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
                'selectors' => [
					'{{WRAPPER}} .crt-timeline-story-overlay' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'show_overlay' => 'yes',
					'content_layout' => 'image-top'
				]
			]
		);

		$this->add_responsive_control(
			'overlay_content_alignment_horizontal',
			[
				'label' => esc_html__( 'Content Horizontal Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'crt-manage' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'crt-manage' ),
						'icon' => 'eicon-h-align-right',
					],
				],
                'selectors' => [
					'{{WRAPPER}} .crt-timeline-story-overlay p' => 'display: flex; justify-content: {{VALUE}};',
					'{{WRAPPER}} .crt-timeline-story-overlay div' => 'display: flex; justify-content: {{VALUE}};',
				],
				'condition' => [
					'show_overlay' => 'yes',
					'content_layout' => 'image-top'
				]
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => 'crt-animations',
				'default' => 'none',
			]
		);

		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'posts-timeline', 'overlay_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );
		
		$this->add_control(
			'overlay_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-timeline-story-overlay' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-animation-wrap:hover .crt-timeline-story-overlay' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'crt-manage' ),
					'medium' => esc_html__( 'Medium', 'crt-manage' ),
					'large' => esc_html__( 'Large', 'crt-manage' ),
				],
				'default' => 'large',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::crt_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'pagination_section',
			[
				'label' => __( 'Pagination', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'timeline_content' => 'dynamic',
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left'],
					'show_pagination' => 'yes'
				]
			]
		);
	
		$this->add_control(
			'pagination_type',
			[
				'label' => __( 'Pagination Type', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'load-more',
				'options'=>[
					'load-more' => __('Load More'),
					'infinite-scroll' => __('Infinite Scroll')
				],
				'condition' => [
					'show_pagination' => 'yes',
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
				'default' => esc_html__('Load More', 'crt-manage'),
				'condition' => [
					'pagination_type' => 'load-more',
				]
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
				'default' => 'End of Content.',
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ],
				]
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
				],
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ],
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
			]
		);
		
		$this->add_responsive_control(
			'pagination_alignment',
			[
				'label' => esc_html__( 'Align', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-grid-pagination' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-pagination-loading' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'timeline_content' => ['dynamic'],
					'timeline_layout' => ['centered', 'one-sided']
				]
			]
		);
		
		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		$this->start_controls_section(
			'content_styles_section',
			[
				'label' => __( 'Timeline Items', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'story_bgcolor',
			[
				'label' => __( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-data-wrap' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-horizontal .crt-story-info' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-story-info' => 'background-color: {{VALUE}}',
				],
				'default' => '#FFF',
			]
		);

		$this->add_control(
			'story_border_color',
			[
				'label' => __( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-story-info' => 'border-color: {{VALUE}} !important;',
					'{{WRAPPER}} .crt-story-info-vertical' => 'border-color: {{VALUE}} !important;',
				],
				'condition' => [
					'timeline_layout!' => 'centered'
				],
				'default' => '#605BE5',
			]
		);

		$this->add_control(
			'story_border_color_left',
			[
				'label' => __( 'Border Color (Left Aligned)', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-left-aligned .crt-story-info-vertical' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'timeline_layout' => 'centered',
				],
				'default' => '#605BE5',
			]
		);

		$this->add_control(
			'story_border_color_right',
			[
				'label' => __( 'Border Color (Right Aligned)', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-right-aligned .crt-story-info-vertical' => 'border-color: {{VALUE}} !important;',
				],
				'condition' => [
					'timeline_layout' => 'centered',
				],
				'default' => '#605BE5',
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'timeline_item_shadow',
				'selector' => '{{WRAPPER}} .crt-story-info',
				'fields_options' => [
                    'box_shadow_type' =>
                        [ 
                            'default' =>'yes' 
                        ],
                    'box_shadow' => [
                        'default' =>
                            [
                                'horizontal' => 0,
                                'vertical' => 0,
                                'blur' => 20,
                                'spread' => 1,
                                'color' => 'rgba(0,0,0,0.1)'
                            ]
                    ]
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'timeline_item_shadow_vertical',
				'selector' => '{{WRAPPER}} .crt-story-info-vertical',
				'fields_options' => [
                    'box_shadow_type' =>
                        [ 
                            'default' =>'yes' 
                        ],
                    'box_shadow' => [
                        'default' =>
                            [
                                'horizontal' => 0,
                                'vertical' => 0,
                                'blur' => 20,
                                'spread' => 1,
                                'color' => 'rgba(0,0,0,0.1)'
                            ]
                    ]
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				]
			]
		);
	
		// $this->add_control(
		// 	'content_background_blend_mode',
		// 	[
		// 		'label' => __( 'Media Position', 'crt-manage' ),
		// 		'type' => \Elementor\Controls_Manager::SELECT,
		// 		'default' => 'normal',
		// 		'options' => $this->background_blend_modes(),
		// 		'condition' => [
		// 			'content_layout' => 'background'
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .crt-story-info-vertical' => 'background-blend-mode: {{VALUE}}',
		// 			'{{WRAPPER}} .crt-story-infO' => 'background-blend-mode: {{VALUE}}',
		// 		]
		// 	]
		// );

		$this->add_responsive_control(
			'item_distance_from_line',
			[
				'label' => esc_html__( 'Distance From Line', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],			
				'default' => [
					'size' => 40,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 40,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-timeline-centered.crt-one-sided-timeline-left .crt-data-wrap' => 'margin-right: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);', 
					'{{WRAPPER}} .crt-timeline-centered.crt-one-sided-timeline .crt-data-wrap' => 'margin-left: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',
					
					'{{WRAPPER}} .crt-centered .crt-left-aligned .crt-timeline-entry-inner .crt-data-wrap' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .crt-centered .crt-right-aligned .crt-timeline-entry-inner .crt-data-wrap' => 'margin-left: {{SIZE}}px;', //calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px)
					'{{WRAPPER}} .crt-centered .crt-one-sided-timeline .crt-right-aligned .crt-timeline-entry-inner .crt-data-wrap' => 'margin-left: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',

                    '{{WRAPPER}} .crt-centered .crt-one-sided-timeline .crt-extra-label' => 'margin-left: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',
                    '{{WRAPPER}} .crt-one-sided-wrapper .crt-one-sided-timeline .crt-extra-label' => 'margin-left: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',
                    '{{WRAPPER}} .crt-timeline-centered.crt-one-sided-timeline-left .crt-timeline-entry .crt-extra-label' => 'margin-right: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'item_distance_vertical',
			[
				'label' => esc_html__( 'Vertical Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
                    '{{WRAPPER}} .crt-timeline-centered .crt-year-wrap' => 'margin-bottom: {{SIZE}}px;',
                    '{{WRAPPER}} .crt-timeline-centered .crt-timeline-entry' => 'margin-bottom: {{SIZE}}px;',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'timeline_item_position',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Item Bottom Distance', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],			
				'selectors' => [
					'{{WRAPPER}} .crt-story-info' => 'margin-bottom: calc({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_bottom.SIZE}}{{swiper_pagination_progressbar_bottom.UNIT}}) !important;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal'],
					'equal_height_slides!' => 'auto-height',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'timeline_item_position_equal_heights',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Item Bottom Distance', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-horizontal-timeline .swiper-slide.swiper-slide-line-bottom.auto-height .crt-story-info' => 'margin-bottom: calc({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_bottom.SIZE}}{{swiper_pagination_progressbar_bottom.UNIT}}) !important; max-height: calc(100% - {{SIZE}}{{UNIT}} - {{swiper_pagination_progressbar_bottom.SIZE}}{{swiper_pagination_progressbar_bottom.UNIT}}) !important; height: calc(100% - {{SIZE}}{{UNIT}} - {{swiper_pagination_progressbar_bottom.SIZE}}{{swiper_pagination_progressbar_bottom.UNIT}}) !important;'
				],
				'condition' => [
					'timeline_layout' => 'horizontal',
					'equal_height_slides' => 'auto-height',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_responsive_control(
			'story_info_margin_top',
			[
				'label' => esc_html__( 'Item Top Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-horizontal-bottom-timeline .crt-story-info' => 'margin-top: calc({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_top.SIZE}}{{swiper_pagination_progressbar_top.UNIT}}) !important; max-height: calc(100% - {{SIZE}}{{UNIT}}) !important;',
					'{{WRAPPER}} .crt-horizontal-bottom-timeline .swiper-slide.auto-height .crt-story-info' => 'margin-top: calc({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_top.SIZE}}{{swiper_pagination_progressbar_top.UNIT}}) !important; max-height: calc(100% - {{SIZE}}{{UNIT}}) !important; height: calc(100% - ({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_top.SIZE}}{{swiper_pagination_progressbar_top.UNIT}})) !important'
				],
				'separator' => 'before',
				'condition' => [
					'timeline_layout' => ['horizontal-bottom'],
				],
			]
		);

		$this->add_responsive_control(
			'story_padding',
			[
				'label' => esc_html__( 'Item Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-story-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-data-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
				],
			]
		);

		$this->add_responsive_control(
			'story_container_padding',
			[
				'label' => esc_html__( 'Container Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'description' => esc_html__('Apply this option to fix Box Shadow issue.', 'crt-manage'),
				'size_units' => [ 'px' ],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'left' => 10,
					'bottom' => 10,
					'unit' => 'px'
				],
				'tablet_default' => [
					'top' => 10,
					'right' => 10,
					'left' => 10,
					'bottom' => 10,
					'unit' => 'px',
				],
				'mobile_default' => [
					'top' => 10,
					'right' => 10,
					'left' => 10,
					'bottom' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-vertical' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-wrapper .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'timeline_item_border_type',
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
					'{{WRAPPER}} .crt-story-info' => 'border-style: {{VALUE}} !important;',
					'{{WRAPPER}} .crt-story-info' => 'border-style: {{VALUE}} !important;',
					'{{WRAPPER}} .crt-story-info-vertical' => 'border-style: {{VALUE}} !important;',
				],
				'separator' => 'before',
			]
		);

		
		$this->add_control(
			'timeline_item_border_width',
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
					'{{WRAPPER}} .crt-story-info' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .crt-story-info-vertical' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .crt-horizontal-timeline .crt-story-info:before' => 'top: calc( 100% + {{BOTTOM}}{{UNIT}} ) !important;',
					'{{WRAPPER}} .crt-horizontal-bottom-timeline .crt-story-info:before' => 'bottom: calc( 100% + {{TOP}}{{UNIT}} ) !important;',
					'{{WRAPPER}} .crt-right-aligned .crt-story-info-vertical.crt-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
					'{{WRAPPER}} .crt-left-aligned .crt-story-info-vertical.crt-data-wrap:after' => 'left: calc( 100% + {{LEFT}}{{UNIT}} ) !important;'
				],
				'condition' => [
					'timeline_layout!' => 'centered',
					'timeline_item_border_type!' => 'none'
				],
			]
		);

		$this->add_control(
			'timeline_item_border_width_left',
			[
				'label' => esc_html__( 'Border Width (Left Aligned)', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-story-info' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .crt-story-info-vertical' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'body[data-elementor-device-mode=desktop] {{WRAPPER}} .crt-both-sided-timeline .crt-left-aligned .crt-data-wrap:after' => 'left: calc( 100% + {{RIGHT}}{{UNIT}} ) !important;',
					'body[data-elementor-device-mode=tablet] {{WRAPPER}} .crt-both-sided-timeline .crt-left-aligned .crt-data-wrap:after' => 'left: calc( 100% + {{RIGHT}}{{UNIT}} ) !important;',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .crt-both-sided-timeline .crt-left-aligned .crt-data-wrap:after' => 'right: calc( 103% + {{LEFT}}{{UNIT}} ) !important; left: auto !important',
				],
				'condition' => [
					'timeline_layout' => 'centered',
					'timeline_item_border_type!' => 'none'
				]
			]
		);

		$this->add_control(
			'timeline_item_border_width_right',
			[
				'label' => esc_html__( 'Border Width (Right Aligned)', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-right-aligned .crt-story-info-vertical' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'body[data-elementor-device-mode=desktop] {{WRAPPER}} .crt-right-aligned .crt-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
					'body[data-elementor-device-mode=tablet] {{WRAPPER}} .crt-right-aligned .crt-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .crt-right-aligned .crt-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
				],
				'condition' => [
					'timeline_layout' => 'centered',
					'timeline_item_border_type!' => 'none'
				]
			]
		);
		
		$this->add_control(
			'story_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-story-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .crt-story-info-vertical' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				// 'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'media_style_section',
			[
				'label' => __( 'Media', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Image Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-timeline-media' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'media_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-timeline-media' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'media_item_border_type',
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
					'{{WRAPPER}} .crt-timeline-media' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'media_item_border_width',
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
					'{{WRAPPER}} .crt-timeline-media' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'media_item_border_type!' => 'none'
				]
				// 'render_type' => 'template'
			]
		);

		$this->add_control(
			'media_item_radius',
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
					'{{WRAPPER}} .crt-timeline-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'media_item_padding',
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
					'{{WRAPPER}} .crt-timeline-media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
				// 'render_type' => 'template'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_style_section',
			[
				'label' => __( 'Content', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					// 'timeline_content' => 'dynamic'
				],
			]
		);

		$this->add_control(
			'item_content_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-timeline-content-wrapper' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_content_border_type',
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
					'{{WRAPPER}} .crt-timeline-content-wrapper' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'item_content_border_width',
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
					'{{WRAPPER}} .crt-timeline-content-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'item_content_border_type!' => 'none'
				],
				'separator' => 'before'
				// 'render_type' => 'template'
			]
		);

		$this->add_control(
			'item_content_border_radius',
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
					'{{WRAPPER}} .crt-timeline-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden',
				],
			]
		);

		$this->add_responsive_control(
			'content_item_padding',
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
					'{{WRAPPER}} .crt-timeline-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				// 'render_type' => 'template'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'overlay_style_section',
			[
				'label' => __( 'Overlay', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_layout' => 'image-top',
					'show_overlay' => 'yes'
				],
			]
		);

		$this->add_control(
			'overlay_bgcolor',
			[
				'label' => __( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-timeline-story-overlay' => 'background-color: {{VALUE}}',
				],
				'default' => '#0000005E',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .crt-timeline-story-overlay',
			]
		);
		
		$this->add_control(
			'overlay_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-timeline-story-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				// 'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'timeline_overlay_padding',
			[
				'label' => esc_html__( 'Overlay Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'separator' => 'before',
				'default' => [
					'top' => 25,
					'right' => 25,
					'bottom' => 25,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-timeline-story-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' =>[
					'show_overlay' => 'yes',
					'content_layout' => 'image-top',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_styles_section',
			[
				'label' => __( 'Title', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
		);
		
		/*---- Story Title ----*/
		$this->add_control(
			'story_title_color',
			[
				'label' => __( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-title' => 'color: {{VALUE}}',
				],
				'default' => '#444444',
			]
		);

		$this->add_control(
			'story_title_bg_color',
			[
				'label' => __( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-title-wrap' => 'background-color: {{VALUE}} !important',
				],
				'default' => '#FFFFFF00',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-wrapper .crt-title',
			]
		);

		$this->add_responsive_control(
			'title_padding',
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
					'{{WRAPPER}} .crt-wrapper .crt-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-title-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_date',
			[
				'label' => esc_html__( 'Date', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'timeline_content' => 'dynamic'
				]
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-inner-date-label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-inner-date-label' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'label' => __('Typography', 'crt-manage'),
				'selector' => '{{WRAPPER}} .crt-inner-date-label',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '300',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'date_border_type',
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
					'{{WRAPPER}} .crt-inner-date-label' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_border_width',
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
					'{{WRAPPER}} .crt-inner-date-label' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'date_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'date_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-inner-date-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
					
		$this->start_controls_section(
			'description_styles_section',
			[
				'label' => __( 'Description', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-description' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-wrapper .crt-description p' => 'color: {{VALUE}}'
				],
				'default' => '#808080',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography_description',
				'label' => __( 'Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-wrapper .crt-description',
			]
		);

        $this->add_control(
			'timeline_list_types',
			[
				'label' => esc_html__( 'List Style', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'label_block' => false,
				'description' => __('Apply this option for WYSIWYG lists', 'crt-manage'),
				'options' => [
					'none' => esc_html__('None', 'crt-manage'),
					'disc' => esc_html__('Disc', 'crt-manage'),
					'decimal'=> esc_html__('Number', 'crt-manage')
				],
				'prefix_class' => 'crt-list-style-',
			]
		);

		$this->add_responsive_control(
			'description_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
			
		$this->start_controls_section(
			'readmore_styles_section',
			[
				'label' => __( 'Read More', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'timeline_content' => ['dynamic']
				]
			]
		);

		$this->start_controls_tabs(
			'readmore_style_tabs'
		);

		$this->start_controls_tab(
			'readmore_style_normal_tab',
			[
				'label' => __( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'readmore_color',
			[
				'label' => __( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-read-more-button' => 'color: {{VAlUE}}',
				],
				'default' => '#fff',
			]
		);
		
		$this->add_control(
			'readmore_bg_color',
			[
				'label' => __( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-read-more-button' => 'background-color: {{VAlUE}}',
				],
				'default' => '#443DD7',
			]
		);

		$this->add_control(
			'readmore_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-read-more-button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'read_more_box_shadow',
				'selector' => '{{WRAPPER}} .crt-read-more-button',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_typography',
				'label' => __( 'Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-wrapper .crt-read-more-button',
			]
		);

		$this->add_control(
			'read_more_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-read-more-button' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'readmore_border_type',
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
					'{{WRAPPER}} .crt-read-more-button' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'readmore_item_border_width',
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
					'{{WRAPPER}} .crt-read-more-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'readmore_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'readmore_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 6,
					'right' => 13,
					'bottom' => 7,
					'left' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-read-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'readmore_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 15,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-read-more-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'readmore_border_radius',
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
					'{{WRAPPER}} .crt-wrapper .crt-read-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => __( 'Hover', 'crt-manage' ),
			]
		);
				
		$this->add_control(
			'readmore_color_hover',
			[
				'label' => __( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-read-more-button:hover' => 'color: {{VAlUE}}',
				],
				'default' => '#ffA',
			]
		);
		
		$this->add_control(
			'readmore_bg_color_hover',
			[
				'label' => __( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-read-more-button:hover' => 'background-color: {{VAlUE}}',
				],
				'default' => '#433BD5',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
		
		$this->start_controls_section(
			'middle_line_styles_section',
			[
				'label' => __( 'Main Line', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'line_color',
			[
				'label' => __( 'Line Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-line::before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-wrapper .crt-middle-line' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-wrapper .crt-timeline-centered .crt-year' => 'border-color: {{VALUE}}',

					'{{WRAPPER}} .crt-wrapper:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-wrapper:after' => 'background-color: {{VALUE}}',

					'{{WRAPPER}} .crt-horizontal .crt-swiper-pagination.swiper-pagination-progressbar' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-swiper-pagination.swiper-pagination-progressbar' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-horizontal .crt-button-prev' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-horizontal .crt-button-next' => 'color: {{VALUE}}',
				],
				'default' => '#D6D6D6',
			]
		);
		
		$this->add_control(
			'swiper_progressbar_color',
			[
				'label' => __( 'Progress(Fill) Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-horizontal .crt-swiper-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-swiper-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				],
				'default' => '#605BE5',
			]
		);

		$this->add_control(
			'timeline_fill_color',
			[
				'label'  => esc_html__( 'Line Fill Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-timeline-fill' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .crt-change-border-color' => 'border-color: {{VALUE}} !important;',
					'{{WRAPPER}} .crt-vertical:before' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .crt-vertical:after' => 'background-color: {{VALUE}} !important;',
				],
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				],
			]
		);

		$this->add_control(
			'middle_line_width',
			[
				'label' => esc_html__( 'Line Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 4,
				],
				'selectors' => [
					// '{{WRAPPER}} .crt-wrapper .crt-line::before' => 'transform: scaleX({{SIZE}}) !important;',
					'{{WRAPPER}} .crt-wrapper .crt-middle-line' => 'width: {{SIZE}}px; transform: translate(-50%) !important',
					'{{WRAPPER}} .crt-wrapper .crt-timeline-fill' => 'width: {{SIZE}}px; transform: translate(-50%)  !important;',
					
					// '{{WRAPPER}} .crt-wrapper .crt-one-sided-timeline-left .crt-line::before' => 'transform: scaleX({{SIZE}}) translateX(50%) !important;',
					'{{WRAPPER}} .crt-wrapper .crt-one-sided-timeline-left .crt-middle-line' => 'width: {{SIZE}}px; transform: translate(50%) !important;',
					'{{WRAPPER}} .crt-wrapper .crt-one-sided-timeline-left .crt-timeline-fill' => 'width: {{SIZE}}px; transform: translate(50%) !important;',

					// '{{WRAPPER}} .crt-wrapper .crt-one-sided-timeline .crt-line::before' => 'transform: scaleX({{SIZE}}) !important;',
					'{{WRAPPER}} .crt-wrapper .crt-one-sided-timeline .crt-middle-line' => 'width: {{SIZE}}px; transform: translate(-50%)  !important;',
					'{{WRAPPER}} .crt-wrapper .crt-one-sided-timeline .crt-timeline-fill' => 'width: {{SIZE}}px; transform: translate(-50%) !important;',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'swiper_pagination_progressbar_height',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'default' => 0.7,
				'step' => 0.1,		
				'selectors' => [
					'{{WRAPPER}} .crt-swiper-pagination.swiper-pagination-progressbar' => 'transform: scaleY({{SIZE}}) translateX(-50%);',
				],
				'separator' => 'before',
				'condition' => [
					'timeline_layout' => ['horizontal-bottom', 'horizontal']
				],
			]
		);

		$this->add_responsive_control(
			'swiper_pagination_progressbar_bottom',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Bottom Distance', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],			
				'selectors' => [
					'{{WRAPPER}} .crt-horizontal .crt-swiper-pagination.swiper-pagination-progressbar' => 'top: auto; bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal .crt-icon' => 'bottom: calc({{SIZE}}{{UNIT}} + 1px) !important;',
					'{{WRAPPER}} .crt-button-prev' => 'top: auto; bottom: calc({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .crt-button-next' => 'top: auto; bottom: calc({{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'timeline_layout' => ['horizontal']
				],
			]
		);

		$this->add_responsive_control(
			'swiper_pagination_progressbar_top',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Top Distance', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],			
				'selectors' => [
					'{{WRAPPER}} .crt-horizontal-bottom .crt-swiper-pagination.swiper-pagination-progressbar' => 'bottom: auto; top: {{SIZE}}{{UNIT}} !important',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-icon' => 'position: absolute; top: calc({{SIZE}}{{UNIT}} + 1px) !important; left: 50%; transform: translate(-50%, -50%);',
					'{{WRAPPER}} .crt-button-prev' => 'bottom: auto; top: calc({{SIZE}}{{UNIT}} + 2px);',
					'{{WRAPPER}} .crt-button-next' => 'bottom: auto; top: calc({{SIZE}}{{UNIT}} + 2px);',
				],
				'condition' => [
					'timeline_layout' => ['horizontal-bottom']
				],
			]
		);

		$this->add_responsive_control(
			'main_line_side_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Side Distance', 'crt-manage' ),
				'description' => esc_html__('This option for Zig-Zag layout only works on mobile devices.', 'crt-manage'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 100,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 100,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 50,
					'unit' => 'px',
				],			
				'selectors' => [
					'{{WRAPPER}} .crt-timeline-centered.crt-one-sided-timeline .crt-year-label' => 'left: calc({{SIZE}}px/2);',
					'{{WRAPPER}} .crt-timeline-centered.crt-one-sided-timeline .crt-middle-line' => 'left: calc({{SIZE}}px/2);',
					'{{WRAPPER}} .crt-timeline-centered.crt-one-sided-timeline .crt-timeline-fill' => 'left: calc({{SIZE}}px/2);',
					'{{WRAPPER}} .crt-timeline-centered.crt-one-sided-timeline .crt-icon' => 'left: calc({{SIZE}}px/2);',

					'{{WRAPPER}} .crt-timeline-centered.crt-one-sided-timeline-left .crt-year-label' => 'right: calc({{SIZE}}px/2);',
					'{{WRAPPER}} .crt-timeline-centered.crt-one-sided-timeline-left .crt-middle-line' => 'right: calc({{SIZE}}px/2);',
					'{{WRAPPER}} .crt-timeline-centered.crt-one-sided-timeline-left .crt-timeline-fill' => 'right: calc({{SIZE}}px/2);',
					'{{WRAPPER}} .crt-timeline-centered.crt-one-sided-timeline-left .crt-icon' => 'right: calc({{SIZE}}px/2);',

					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .crt-both-sided-timeline .crt-year-label' => 'position: absolute; left: calc({{SIZE}}px/2);',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .crt-both-sided-timeline .crt-middle-line' => 'left: calc({{SIZE}}px/2);',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .crt-both-sided-timeline .crt-timeline-fill' => 'left: calc({{SIZE}}px/2);',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .crt-both-sided-timeline .crt-icon' => 'left: calc({{SIZE}}px/2); transform: translate(-50%, -50%) !important;',
				],
				'render_type' => 'template',
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'year_label_section',
			[
				'label' => __( 'Main Line Label', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'timeline_content' => 'custom'
				]
			]
		);
		
		$this->add_control(
			'year_label_color',
			[
				'label' => __( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-year' => 'color: {{VALUE}}',
				],
				'default' => '#222222',
				'condition' => [
					'timeline_content' => ['custom'],
				]
			]
		);
		
		$this->add_control(
			'year_label_bgcolor',
			[
				'label' => __( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-year' => 'background-color: {{VALUE}}',
				],
				'default' => '#fff',
				'condition' => [
					'timeline_content' => ['custom'],
				]
			]
		);
		
		$this->add_control(
			'year_label_border_color',
			[
				'label' => __( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-year.crt-year-label' => 'border-color: {{VALUE}}',
				],
				'default' => '#E0E0E0',
				'condition' => [
					'timeline_content' => ['custom'],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'year_typography',
				'label' => __( 'Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-wrapper .crt-year',
				'condition' => [
					'timeline_content' => ['custom'],
				]
			]
		);
		
		$this->add_responsive_control(
			'year_label_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 70,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .crt-year-label' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template',
			]
		);
		
		$this->add_responsive_control(
			'year_label_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 41,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .crt-year-label' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-year-wrap' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'year_label_border_type',
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
					'{{WRAPPER}} .crt-year-label' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'year_label_border_size',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-year-label' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'year_label_border_type!' => 'none'
				]
			]
		);

		$this->add_control(
			'year_label_radius',
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
					'{{WRAPPER}} .crt-year-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'icon_styles_section',
			[
				'label' => __( 'Main Line Icon', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-wrapper .crt-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-wrapper .crt-icon svg' => 'fill: {{VALUE}};',
				],
				'default' => '#666666',
			]
		);

		$this->add_control(
			'icon_timeline_fill_color',
			[
				'label'  => esc_html__( 'Fill Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-change-border-color.crt-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-change-border-color.crt-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				],
			]
		);

		$this->add_control(
			'icon_bgcolor',
			[
				'label' => __( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-icon' => 'background-color: {{VALUE}}',
				],
				'default' => '#FFFFFF',
			]
		);

		$this->add_control(
			'icon_timeline_background_fill_color',
			[
				'label'  => esc_html__( 'Background Fill Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-change-border-color.crt-icon' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				],
			]
		);

		$this->add_control(
			'icon_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#EAEAEA',
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-icon' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 17,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-icon i' => 'font-size: {{SIZE}}{{UNIT}} !important',
					'{{WRAPPER}} .crt-wrapper .crt-icon svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_bg_size',
			[
				'label' => esc_html__( 'Background Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 45,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-icon i' => 'display: block;',
					'{{WRAPPER}} .crt-wrapper .crt-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; display: flex !important; justify-content: center !important; align-items: center !important;',
				],
				'render_type' => 'template',
			]
		);
		
		$this->add_control(
			'icon_border_type',
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
					'{{WRAPPER}} .crt-icon' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'icon_border_type!' => 'none'
				]
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
					'unit' => '%'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		
		$this->end_controls_section();	
			
		$this->start_controls_section(
			'label_styles_section',
			[
				'label' => __( 'Extra Label', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_extra_label' => 'yes',
				]
			]
		);

		$this->add_control(
			'extra_label_bg_color_dynamic',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-extra-label' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'label_bg_size',
			[
				'label' => esc_html__( 'Background Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 180,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-extra-label' => 'width: {{SIZE}}{{UNIT}}; height: auto;',

				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'label_right',
			[
				'label' => __( 'Label Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .crt-timeline-centered.crt-both-sided-timeline .crt-timeline-entry.crt-left-aligned .crt-extra-label' => 'left: calc(100% + {{SIZE}}{{UNIT}})',
					'{{WRAPPER}} .crt-timeline-centered.crt-both-sided-timeline .crt-timeline-entry.crt-right-aligned .crt-extra-label' => 'right: calc(100% + {{SIZE}}{{UNIT}})',
				],
				'condition' => [
					'timeline_layout' => ['centered'],
				]
			]
		);

		$this->add_responsive_control(
			'label_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-extra-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_border_radius',
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
					'{{WRAPPER}} .crt-wrapper .crt-extra-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_section',
			[
				'label' => __( 'Primary Label', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_label_color',
			[
				'label' => __( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper span.crt-label' => 'color: {{VALUE}}',
				],
				'default' => '#605BE5',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-extra-label span.crt-label',
			]
		);

		/*---- Secondary Label ----*/
		$this->add_control(
			'secondary_label_section',
			[
				'label' => __( 'Secondary Label', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'timeline_content' => 'custom'
				]
			]
		);

		$this->add_control(
			'secondary_label_color',
			[
				'label' => __( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper span.crt-sub-label' => 'color: {{VALUE}}',
				],
				'condition' => [
					'timeline_content' => 'custom'
				],
				'default' => '#7A7A7A',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'secondary_label_typography',
				'label' => __( 'Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-wrapper span.crt-sub-label',
				'condition' => [
					'timeline_content' => 'custom'
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'triangle_styles',
			[
				'label' => esc_html__( 'Triangle', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'triangle_bgcolor',
			[
				'label' => __( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-wrapper .crt-one-sided-timeline .crt-data-wrap:after' => 'border-right-color: {{icon_bgcolor}}',
					'{{WRAPPER}} .crt-wrapper .crt-one-sided-timeline-left .crt-data-wrap:after' => 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} .crt-wrapper .crt-right-aligned .crt-data-wrap:after' => 'border-right-color: {{VALUE}}',
					'{{WRAPPER}} .crt-horizontal .crt-story-info:before' => 'border-top-color: {{VALUE}} !important',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-story-info:before' => 'border-bottom-color: {{VALUE}} !important',
					'{{WRAPPER}} .crt-wrapper .crt-left-aligned .crt-data-wrap:after' => 'border-left-color: {{VALUE}}',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .crt-wrapper .crt-both-sided-timeline .crt-left-aligned .crt-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
					'{{WRAPPER}} .crt-centered .crt-one-sided-timeline .crt-right-aligned .crt-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
				],
				'default' => '#FFFFFF',
				'render_type' => 'template'
			]
		);
		
		$this->add_responsive_control(
			'story_triangle_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 11,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-horizontal .crt-story-info:before' => 'border-width: {{size}}{{UNIT}}; top: 100%; left: 50%; transform: translate(-50%);',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-story-info:before' => 'border-width: {{size}}{{UNIT}}; bottom: 100%; left: 50%; transform: translate(-50%);',
					'{{WRAPPER}} .crt-one-sided-timeline .crt-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{triangle_onesided_position_top.SIZE}}%; transform: translateY(-50%);',
					'{{WRAPPER}} .crt-one-sided-timeline-left .crt-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{triangle_onesided_position_top.SIZE}}%; transform: translateY(-50%);',
					'{{WRAPPER}} .crt-both-sided-timeline .crt-right-aligned .crt-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{arrow_bothsided_position_top.SIZE}}{{arrow_bothsided_position_top.UNIT}}; transform: translateY(-50%);',
					'{{WRAPPER}} .crt-both-sided-timeline .crt-left-aligned .crt-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{arrow_bothsided_position_top.SIZE}}{{arrow_bothsided_position_top.UNIT}}; transform: translateY(-50%);',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'triangle_onesided_position_top',
			[
				'label' => __( 'Position Top', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 30,
				],
				'selectors' => [

					'{{WRAPPER}} .crt-one-sided-timeline .crt-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;',
					'{{WRAPPER}} .crt-one-sided-timeline-left .crt-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;',
					'{{WRAPPER}} .crt-one-sided-timeline .crt-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(-50%, -50%) !important;',
					'{{WRAPPER}} .crt-one-sided-timeline-left .crt-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(50%,-50%) !important;',
				],
				'condition' => [
					'timeline_layout' => ['one-sided', 'one-sided-left']
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'arrow_bothsided_position_top',
			[
				'label' => __( 'Position Top', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'size' => 30,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-timeline-centered .crt-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;',
					'{{WRAPPER}} .crt-timeline-centered.crt-both-sided-timeline .crt-right-aligned .crt-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(50%, -50%) !important;',
					'{{WRAPPER}} .crt-timeline-centered.crt-one-sided-timeline  .crt-right-aligned .crt-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(-50%, -50%) !important;',
					'{{WRAPPER}} .crt-timeline-centered  .crt-left-aligned .crt-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(-50%, -50%) !important;',
					'{{WRAPPER}} .crt-timeline-centered .crt-extra-label' => 'top: {{size}}{{UNIT}};',
					'{{WRAPPER}} .crt-centered .crt-one-sided-timeline .crt-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;', 
				],
				'condition' => [
					'timeline_layout' => ['centered']
				],
				'render_type' => 'template'
			]
		);

		$this->end_controls_section();

			$this->start_controls_section(
				'navigation_button_styles',
			[
				'label' => esc_html__( 'Navigation', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);
		
		$this->start_controls_tabs(
			'navigation_style_tabs'
		);

		$this->start_controls_tab(
			'navigation_style_normal_tab',
			[
				'label' => __( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'navigation_button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-button-prev' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-button-next' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'navigation_button_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-button-prev i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-button-next i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-button-prev svg' => 'fill: {{VALUE}}; cursor: pointer; z-index: 11;',
					'{{WRAPPER}} .crt-button-next svg' => 'fill: {{VALUE}}; cursor: pointer; z-index: 11;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
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
					'{{WRAPPER}} .crt-button-prev' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-button-next' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-button-prev i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-button-next i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-button-prev svg' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-button-next svg' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'navigation_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
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
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-horizontal-bottom .crt-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal .crt-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal .crt-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-button-next svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-button-prev svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal .crt-button-next svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal .crt-button-prev svg' => 'width: {{SIZE}}{{UNIT}};',
					
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'navigation_icon_bg_size',
			[
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-horizontal-bottom .crt-button-next' => 'width: {{SIZE}}{{UNIT}}; text-align: center; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-button-prev' => 'width: {{SIZE}}{{UNIT}}; text-align: center; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal .crt-button-next' => 'width: {{SIZE}}{{UNIT}}; text-align: center; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal .crt-button-prev' => 'width: {{SIZE}}{{UNIT}}; text-align: center; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal .crt-button-next i' => 'width: {{SIZE}}{{UNIT}}; text-align: center; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal .crt-button-prev i' => 'width: {{SIZE}}{{UNIT}}; text-align: center; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal .crt-button-next svg' => ' text-align: center; line-height: 1.5;',
					'{{WRAPPER}} .crt-horizontal .crt-button-prev svg' => ' text-align: center; line-height: 1.5;',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-button-next i' => 'width: {{SIZE}}{{UNIT}}; text-align: center; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-button-prev i' => 'width: {{SIZE}}{{UNIT}}; text-align: center; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-button-next svg' => 'text-align: center; line-height: 1.5;',
					'{{WRAPPER}} .crt-horizontal-bottom .crt-button-prev svg' => 'text-align: center; line-height: 1.5;',
					'{{WRAPPER}} .crt-swiper-pagination.swiper-pagination-progressbar' => 'width: calc(100% - ({{SIZE}}px + 15px)*2);',
					'{{WRAPPER}} .crt-horizontal-bottom.swiper' => 'margin-left: {{SIZE}}px; margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .crt-horizontal.swiper' => 'margin-left: {{SIZE}}px; margin-right: {{SIZE}}px;',
				],
				'render_type' => 'template'
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'navigation_style_hover_tab',
			[
				'label' => __( 'Hover', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'navigation_button_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-button-prev:hover' => 'background-color: {{VALUE}}; cursor: pointer;',
					'{{WRAPPER}} .crt-button-next:hover' => 'background-color: {{VALUE}}; cursor: pointer;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'navigation_button_color_hover',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE1',
				'selectors' => [
					'{{WRAPPER}} .crt-button-prev:hover i' => 'color: {{VALUE}}; cursor: pointer; z-index: 11;',
					'{{WRAPPER}} .crt-button-next:hover i' => 'color: {{VALUE}}; cursor: pointer; z-index: 11;',
					'{{WRAPPER}} .crt-button-prev:hover svg' => 'fill: {{VALUE}}; cursor: pointer; z-index: 11;',
					'{{WRAPPER}} .crt-button-next:hover svg' => 'fill: {{VALUE}}; cursor: pointer; z-index: 11;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Styles ====================
		// Section: Pagination -------
		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'Pagination', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'timeline_content' => 'dynamic',
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
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
					'{{WRAPPER}} .crt-grid-pagination a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'color: {{VALUE}}',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				]
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-pagination-finish' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-grid-pagination a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow',
				'selector' => '{{WRAPPER}} .crt-grid-pagination a, {{WRAPPER}} .crt-grid-pagination > div > span',
			]
		);

		$this->add_control(
			'pagination_loader_color',
			[
				'label'  => esc_html__( 'Loader Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-double-bounce .crt-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-wave .crt-rect' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-spinner-pulse' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-chasing-dots .crt-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-three-bounce .crt-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-fading-circle .crt-circle:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ]
				]
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
					'{{WRAPPER}} .crt-grid-pagination a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination a:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-grid-pagination a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-grid-pagination a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow_hr',
				'selector' => '{{WRAPPER}} .crt-grid-pagination a:hover, {{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover',
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
					'{{WRAPPER}} .crt-grid-pagination a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-pagination svg' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		;$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'loadmore_typography',
				'label' => __( 'Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-load-more-btn',
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
					'{{WRAPPER}} .crt-grid-pagination a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'border-style: {{VALUE}}',
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
					'{{WRAPPER}} .crt-grid-pagination a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'pagination_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_distance_from_grid',
			[
				'label' => esc_html__( 'Distance From Timeline', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-grid-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-grid-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-grid-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}
	
	public function get_tax_query_args() {
		$settings = $this->get_settings();
		$tax_query = [];

		if ( 'related' === $settings[ 'timeline_post_types' ] ) {
			$tax_query = [
				[
					'taxonomy' => $settings['query_tax_selection'],
					'field' => 'term_id',
					'terms' => wp_get_object_terms( get_the_ID(), $settings['query_tax_selection'], array( 'fields' => 'ids' ) ),
				]
			];
		} else {
			foreach ( get_object_taxonomies($settings[ 'timeline_post_types' ]) as $tax ) {
				if ( ! empty($settings[ 'query_taxonomy_'. $tax ]) ) {
					array_push( $tax_query, [
						'taxonomy' => $tax,
						'field' => 'id',
						'terms' => $settings[ 'query_taxonomy_'. $tax ]
					] );
				}
			}
		}

		return $tax_query;
	}

	// for frontend
	public function get_main_query_args() {
		$settings = $this->get_settings();
		$author = ! empty( $settings[ 'query_author' ] ) ? implode( ',', $settings[ 'query_author' ] ) : '';

		// Get Paged
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		$posts_per_page = $settings['posts_per_page'];

		// Dynamic
		$args = [
			'post_type' => $settings[ 'timeline_post_types' ],
			'tax_query' => $this->get_tax_query_args(),
			'post__not_in' => !empty($settings[ 'query_exclude_'. $settings[ 'timeline_post_types' ] ]) ? $settings[ 'query_exclude_'. $settings[ 'timeline_post_types' ] ] : '',
			'posts_per_page' =>  $posts_per_page,
			'orderby' => $settings[ 'order_posts' ],
			'order' => $settings['order_direction'],
			'author' => $author,
			'paged' => $paged,
		];

		// Exclude Items without F/Image
		if ( 'yes' === $settings['query_exclude_no_images'] ) {
			$args['meta_key'] = '_thumbnail_id';
		}
		
		// Manual
		if ( 'manual' === $settings[ 'query_selection' ] ) {
			$post_ids = [''];

			if ( ! empty($settings[ 'query_manual_'. $settings[ 'timeline_post_types' ] ]) ) {
				$post_ids = $settings[ 'query_manual_'. $settings[ 'timeline_post_types' ] ];
			}

			$args = [
				'post_type' => $settings[ 'timeline_post_types' ],
				'post__in' => $post_ids,
				'posts_per_page' => $posts_per_page,
				'orderby' => '',  //  $settings[ 'query_randomize' ],
				'paged' => $paged,
			];
		}

		return $args;
	}

	public function get_max_num_pages( $settings ) {
		$query = new \WP_Query( $this->get_main_query_args() );
		$max_num_pages = intval( ceil( $query->max_num_pages ) );

		// Reset
		wp_reset_postdata();

		// $max_num_pages
		return $max_num_pages;
	}	
	
	public $content_alignment = '';

	public function content_and_animation_alignment($layout, $countItem, $settings) {

		if ( $layout != 'one-sided-left' ) {
			$this->content_alignment = "crt-right-aligned";
		}

		if ( $layout === 'one-sided-left' ) {
			$this->content_alignment = "crt-left-aligned"; 
		}
		
		if ( $layout == 'centered' ) {

			if ( $countItem % 2 == 0 ) { 
				$this->content_alignment = "crt-left-aligned";
			}	
			
			if ( preg_match('/right/i', $settings['timeline_animation']) ) {
				if ( 'crt-left-aligned' === $this->content_alignment ) {
					$this->animation = preg_match('/right/i', $settings['timeline_animation']) ? str_replace('right', 'left', $settings['timeline_animation']) : $settings['timeline_animation'];
				} elseif ( 'crt-right-aligned' === $this->content_alignment  ) {
					$this->animation = preg_match('/left/i', $settings['timeline_animation']) ? str_replace('left', 'right', $settings['timeline_animation']) : $settings['timeline_animation'];
				}
			}
			if ( preg_match('/left/i', $settings['timeline_animation']) ) {
				if ( 'crt-left-aligned' === $this->content_alignment ) {
					$this->animation = preg_match('/left/i', $settings['timeline_animation']) ? str_replace('left', 'right', $settings['timeline_animation']) : $settings['timeline_animation'];
				} elseif ( 'crt-right-aligned' === $this->content_alignment  ) {
					$this->animation = preg_match('/right/i', $settings['timeline_animation']) ? str_replace('right', 'left', $settings['timeline_animation']) : $settings['timeline_animation'];
				}
			}
		}

		if ( preg_match('/right/i', $settings['timeline_animation']) ) {
			$this->animation_loadmore_left = preg_match('/right/i', $settings['timeline_animation']) ? str_replace('right', 'left', $settings['timeline_animation']) : $settings['timeline_animation'];
			$this->animation_loadmore_right = preg_match('/left/i', $settings['timeline_animation']) ? str_replace('left', 'right', $settings['timeline_animation']) : $settings['timeline_animation'];
		} elseif ( preg_match('/left/i', $settings['timeline_animation']) ) {
			$this->animation_loadmore_left = preg_match('/left/i', $settings['timeline_animation']) ? str_replace('left', 'right', $settings['timeline_animation']) : $settings['timeline_animation'];
			$this->animation_loadmore_right = preg_match('/right/i', $settings['timeline_animation']) ? str_replace('right', 'left', $settings['timeline_animation']) : $settings['timeline_animation'];
		}
	}

    public function add_custom_horizontal_timeline_attributes($content, $settings, $index) {

			$this->timeline_description = $content['repeater_description'];
			$this->story_date_label = esc_html__($content['repeater_date_label']);
			$this->story_extra_label = esc_html__($content['repeater_extra_label']);
			$this->timeline_story_title = wp_kses_post($content['repeater_story_title']);
			$this->thumbnail_size = $content['crt_thumbnail_size'];
			$this->thumbnail_custom_dimension = $content['crt_thumbnail_custom_dimension'];
		              
			$this->show_year_label = esc_html__($content['repeater_show_year_label']);
			$this->timeline_year = esc_html__($content['repeater_year']);

			$this->title_key = $this->get_repeater_setting_key( 'repeater_story_title', 'timeline_repeater_list', $index );
			$this->year_key = $this->get_repeater_setting_key( 'repeater_year', 'timeline_repeater_list', $index );
			$this->date_label_key = $this->get_repeater_setting_key( 'repeater_date_label', 'timeline_repeater_list', $index );
			$this->extra_label_key = $this->get_repeater_setting_key( 'repeater_extra_label', 'timeline_repeater_list', $index );
			$this->description_key = $this->get_repeater_setting_key( 'repeater_description', 'timeline_repeater_list', $index );

			$this->background_image = $settings['content_layout'] === 'background' ? $content['repeater_image']['url'] : '';
			$this->background_class = $settings['content_layout'] === 'background' ? 'story-with-background' : '';
			
			$this->add_inline_editing_attributes( $this->title_key, 'none' );
			$this->add_inline_editing_attributes( $this->year_key, 'none' );
			$this->add_inline_editing_attributes( $this->date_label_key, 'none' );
			$this->add_inline_editing_attributes( $this->extra_label_key, 'none' );
			$this->add_inline_editing_attributes( $this->description_key, 'advanced' );

			$this->add_render_attribute( $this->title_key, ['class'=> 'crt-title']);
			$this->add_render_attribute( $this->year_key, ['class'=> 'crt-year-label crt-year']);
			$this->add_render_attribute( $this->date_label_key, ['class'=> 'crt-label']);
			$this->add_render_attribute( $this->extra_label_key, ['class'=> 'crt-sub-label']);
			$this->add_render_attribute( $this->description_key, ['class'=> 'crt-description']);
                        
    }

	public function render_image_or_icon($content, $settings, $repeater_title_link) {
        if( ( isset($content['repeater_image']['id']) && $content['repeater_image']['id'] != "" ) ) {
			// Build image HTML and optionally wrap with link
			if ( $this->thumbnail_size == 'custom' ) {
				$custom_size = [ $this->thumbnail_custom_dimension['width'], $this->thumbnail_custom_dimension['height'] ];
				$img_html = wp_get_attachment_image( $content['repeater_image']['id'], $custom_size, true );
			} else {
				$img_html = wp_get_attachment_image( $content['repeater_image']['id'], $this->thumbnail_size, true );
			}

			if ( 'yes' === $settings['enable_img_link'] && ! empty( $repeater_title_link ) ) {
				$this->image = '<a ' . $this->get_render_attribute_string( 'repeater_title_link' . $this->item_url_count ) . '>' . $img_html . '</a>';
			} else {
				$this->image = $img_html;
			}
        } elseif (isset($content['repeater_image']['url']) && $content['repeater_image']['url'] != "") {
			if ( 'yes' === $settings['enable_img_link'] && !empty( $repeater_title_link ) ) {
				$this->image = '<a '. $this->get_render_attribute_string( 'repeater_title_link'. $this->item_url_count ) .'><img src="'. esc_url($content['repeater_image']['url']) .'"></a>';
			} else {
				$this->image = '<img src="'. esc_url($content['repeater_image']['url']) .'">';
			}
        } elseif ($content['repeater_timeline_item_icon'] != '') {
            ob_start();
            \Elementor\Icons_Manager::render_icon( $content['repeater_timeline_item_icon'], [ 'aria-hidden' => 'true' ] );
            $icon_image = ob_get_clean();
            $this->image = $icon_image;
        }  else {
            $this->image ='';
        }
	}
	
	public function crt_render_swiper_navigation($settings) {
		echo '</div>
			<!-- Add Pagination -->        
			<div class="crt-swiper-pagination"></div>
			<!-- Add Arrows -->
			<div class="crt-button-prev crt-timeline-prev-arrow crt-timeline-prev-'. esc_attr($this->get_id()) .'">
				'. Utilities::get_crt_icon( $settings['swiper_nav_icon'], '' ) .'
			</div>
			<div class="crt-button-next crt-timeline-next-arrow crt-timeline-next-'. esc_attr($this->get_id()) .'">
				'. Utilities::get_crt_icon( $settings['swiper_nav_icon'], '' ) .'
			</div>
		</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

    public function render_pagination($settings, $paged) {
        if ( 'yes' === $settings['show_pagination'] ) {
            echo '<div>';
            echo '<div class="crt-grid-pagination crt-pagination-load-more">';

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

            echo '<p class="crt-pagination-finish">'. $settings['pagination_finish_text'] .'</p>';
            echo '<a href="'. get_pagenum_link( $paged + 1, true ) .'" class="crt-load-more-btn button">';
            echo $settings['pagination_load_more_text'];
            echo '</a>';
            echo '</div>';
            echo '</div>';
        }
    }

    // Get Animation Class
	public function get_animation_class( $data, $object ) {
		$class = '';

		// Animation Class
		if ( 'none' !== $data[ $object .'_animation'] ) {
			$class .= ' crt-'. $object .'-'. $data[ $object .'_animation'];
			$class .= ' crt-anim-size-'. $data[ $object .'_animation_size'];
			$class .= ' crt-anim-timing-'. $data[ $object .'_animation_timing'];

			if ( 'yes' === $data[ $object .'_animation_tr'] ) {
				$class .= ' crt-anim-transparency';
			}
		}

		return $class;
	}

	public static function youtube_url ( $story_settings ) {
		if ( $story_settings['repeater_youtube_video_url'] != '' ) {
                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $story_settings['repeater_youtube_video_url'], $matches);
              
                if ( isset($matches[1]) ) {
                    $id = $matches[1];
                    $media = '<iframe width="100%" height="auto"
                    src="https://www.youtube.com/embed/'. esc_attr($id) .'" 
                    frameborder="0" allowfullscreen></iframe>';
                }
            } elseif ( empty($story_settings['repeater_youtube_video_url']) ) {
				$media = '';
			} else {
                $media = __("Wrong URL","crt-addons");
            }
			return $media;
	}

	public function horizontal_timeline_classes($settings) {
		
		$this->slides_to_show = isset($settings['slides_to_show']) && !empty($settings['slides_to_show']) ? $settings['slides_to_show'] : 2;

		if ( $settings['timeline_layout'] == 'horizontal' ) {
			$horizontal_class = 'crt-horizontal-wrapper';
		} elseif ( $settings['timeline_layout'] == 'horizontal-bottom' ) {
			$horizontal_class = 'crt-horizontal-bottom-wrapper';
		}

		$this->horizontal_inner_class = $horizontal_class == 'crt-horizontal-wrapper' ? 'crt-horizontal' : 'crt-horizontal-bottom';

		$this->horizontal_timeline_class = $this->horizontal_inner_class == 'crt-horizontal' ? 'crt-horizontal-timeline' : 'crt-horizontal-bottom-timeline';

		$this->swiper_class = $this->horizontal_timeline_class === 'crt-horizontal-timeline' ? 'swiper-slide-line-bottom' : 'swiper-slide-line-top';

	}

	public function render_custom_vertical_timeline($layout, $settings, $data, $countItem ) {
		echo '
		<div class="crt-wrapper crt-vertical '. esc_attr($this->timeline_layout_wrapper) .'">
			<div class="crt-timeline-centered crt-line '. esc_attr($this->timeline_layout) .'">';
			echo '<div class="crt-middle-line"></div>';
			echo 'yes' === $this->timeline_fill ? '<div class="crt-timeline-fill" data-layout="'. esc_attr($layout) .'"></div>' : '';
			
			foreach ( $data as $index => $content ) {
//				if ( (!defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code()) && $index === 4 ) {
//					break;
//				}

				$repeater_title_link = isset($content['repeater_title_link']) && !empty($content['repeater_title_link']['url']) ? $content['repeater_title_link'] : '';

				if ( !empty( $content['repeater_title_link']['url'] ) ) {
					$this->add_link_attributes( 'repeater_title_link'. $this->item_url_count, $repeater_title_link );
				}

				$this->content_and_animation_alignment($layout, $countItem, $settings);
				
				$this->thumbnail_size = $content['crt_thumbnail_size'];
				$this->thumbnail_custom_dimension = $content['crt_thumbnail_custom_dimension'];

				$this->show_year_label = esc_html__($content['repeater_show_year_label']);
				$this->timeline_year = esc_html__($content['repeater_year']);

				$this->render_image_or_icon($content, $settings, $repeater_title_link);

				$background_image = $settings['content_layout'] === 'background' ? $content['repeater_image']['url'] : '';
				$background_class = $settings['content_layout'] === 'background' ? 'story-with-background' : '';

				if ( $content['repeater_show_year_label'] == 'yes' ) {
					echo '<span class="crt-year-wrap">';
						echo '<span class="crt-year-label crt-year">'. esc_html__($content['repeater_year']) .'</span>';
					echo '</span>';
				}
				
				echo '<article class="crt-timeline-entry '. esc_attr($this->content_alignment) .' elementor-repeater-item-'. esc_attr($content['_id']) .'" data-item-id="elementor-repeater-item-'. esc_attr($content['_id']) .'">';
                    
                    if ( 'yes' === $content['repeater_show_extra_label'] ) {
                        echo !empty($content['repeater_date_label']) || !empty($content['repeater_extra_label']) ? '<time class="crt-extra-label" data-aos="'. esc_attr($this->animation) .'" data-aos-left="'. esc_attr($this->animation_loadmore_left) .'" data-aos-right="'. esc_attr($this->animation_loadmore_right) .'" data-animation-offset="'. esc_attr($settings['animation_offset']) .'" data-animation-duration="'. esc_attr($settings['aos_animation_duration']) .'">' : '';
                            echo !empty($content['repeater_date_label']) ? '<span class="crt-label">'. esc_html__($content['repeater_date_label']) .'</span>' : '';
							echo !empty($content['repeater_extra_label']) ? '<span class="crt-sub-label">'. wp_kses_post($content['repeater_extra_label']) .'</span>' : '';
                        echo !empty($content['repeater_date_label']) || !empty($content['repeater_extra_label']) ? '</time>' : '';
                    }

					echo '<div class="crt-timeline-entry-inner">';

						$this->render_main_line_icon($settings, $content);

						echo '<div class="crt-story-info-vertical crt-data-wrap '. esc_attr($background_class) .'"  data-aos="'. esc_attr($this->animation) .'" data-aos-left="'. esc_attr($this->animation_loadmore_left) .'" data-aos-right="'. esc_attr($this->animation_loadmore_right) .'" data-animation-offset="'. esc_attr($settings['animation_offset']) .'" data-animation-duration="'. esc_attr($settings['aos_animation_duration']) .'">';

							echo ($settings['content_layout'] === 'image-top' && !empty($this->image)) || ($settings['content_layout'] === 'image-top' && $content['repeater_youtube_video_url']) ? '<div class="crt-animation-wrap crt-timeline-media">'. $this->image : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

							echo !empty($content['repeater_youtube_video_url']) && $settings['content_layout'] === 'image-top' ? '<div class="crt-timeline-iframe-wrapper"> '. $this->youtube_url($content) .' </div>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

								echo ($settings['show_overlay'] === 'yes' && !empty($this->image)) || ($settings['show_overlay'] === 'yes' && !empty($content['repeater_youtube_video_url'])) ? '<div class="crt-timeline-story-overlay '. esc_attr($this->animation_class) .'">' : '';

									if ( 'yes' === $settings['title_overlay'] ) {
										$this->render_title($settings, $content, $repeater_title_link);
									}

									if ( 'yes' === $settings['description_overlay'] ) {
										$this->render_description($settings, $content);
									}

								echo ($settings['show_overlay'] === 'yes' && !empty($this->image)) || ($settings['show_overlay'] === 'yes' && !empty($content['repeater_youtube_video_url']))  ? '</div>' : '';

							echo ($settings['content_layout'] === 'image-top' && !empty($content['repeater_youtube_video_url'])) || ($settings['content_layout'] === 'image-top' && !empty($this->image)) || $settings['show_overlay'] === 'yes' ? '</div>' : '';

							echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] && !empty($content['repeater_story_title'])  || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] && !empty($content['repeater_description']) ?'<div class="crt-timeline-content-wrapper">' : '';

								echo  '<div class="crt-content-wrapper">'; //remove

								if ( 'yes' !== $settings['title_overlay'] ) {
									$this->render_title($settings, $content, $repeater_title_link);
								}

								if ( 'yes' !== $settings['description_overlay'] ) {
									$this->render_description($settings, $content);
								}

								echo '</div>'; //remove

							echo !empty( $content['repeater_youtube_video_url'] ) && $settings['content_layout'] !== 'image-top' ? '<div class="crt-timeline-iframe-wrapper"> '. $this->youtube_url($content) .' </div>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

							echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] && !empty($content['repeater_story_title'])  || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] && !empty($content['repeater_description']) ? '</div>' : '';	

							echo ($settings['content_layout'] === 'image-bottom' && !empty($this->image)) ? '<div class="crt-animation-wrap crt-timeline-media">'. $this->image .'</div>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo '</div>
						</div>
				</article>';
						
				$countItem = $countItem +1;
				$this->item_url_count++;
				
			}
			echo'</div>    
			</div>';

	}

	public function render_dynamic_vertical_timeline($settings, $arrow_bgcolor, $layout, $countItem, $paged ) {
				$layout_settings = [
					'pagination_type' => $settings['pagination_type'],
				];

				$this->add_render_attribute( 'grid-settings', [
					'data-settings' => wp_json_encode( $layout_settings ),
				] );

				wp_reset_postdata();
				
				if(!$this->my_query->have_posts()) {
					echo '<div> '. esc_html__($settings['query_not_found_text']) .'</div>';
				}

				if ( $this->my_query->have_posts() ) { 
					echo '<div class="crt-wrapper crt-vertical '. esc_attr($this->timeline_layout_wrapper) .'">';
					echo '<div class="crt-timeline-centered crt-line '. esc_attr($this->timeline_layout) .'"  data-pagination="'. esc_attr($this->pagination_type) .'" data-max-pages="'. esc_attr($this->pagination_max_pages) .'" data-arrow-bgcolor="'. esc_attr($arrow_bgcolor) .'">';
					echo '<div class="crt-middle-line"></div>';
					echo 'yes' === $this->timeline_fill ? '<div class="crt-timeline-fill" data-layout="'. esc_attr($layout) .'"></div>' : '';

				while ( $this->my_query->have_posts() ) {
					global $wp_query;
					$counter = $wp_query->current_post++;
					$this->my_query->the_post();
					
					$id = get_post_thumbnail_id();
					$this->src = Group_Control_Image_Size::get_attachment_image_src( $id, 'crt_thumbnail_dynamic', $settings );
					

					$this->content_and_animation_alignment($layout, $countItem, $settings);
					$background_image = $settings['content_layout'] === 'background' ? get_the_post_thumbnail_url() : '';
					$background_class = $settings['content_layout'] === 'background' ? 'story-with-background' : '';

					echo '<article class="crt-timeline-entry '. esc_attr($this->content_alignment) .'" data-counter="'. esc_attr($countItem) .'">';
                        
                        if ( 'yes' === $settings['show_extra_label'] ) {
                            echo '<time class="crt-extra-label" data-aos="'. esc_attr($this->animation) .'" data-aos-left="'. esc_attr($this->animation_loadmore_left) .'" data-aos-right="'. esc_attr($this->animation_loadmore_right) .'" data-animation-offset="'. esc_attr($settings['animation_offset']) .'" data-animation-duration="'. esc_attr($settings['aos_animation_duration']) .'">';
                                echo '<span class="crt-label">';
								$this->date_and_extra_label($settings, 'extra_label_source', 'meta_field_key');
								echo '</span>';
                            echo'</time>';
                        }

						echo '<div class="crt-timeline-entry-inner">';

							$this->render_main_line_icon($settings);

							echo '<div class="crt-story-info-vertical crt-data-wrap animated '. esc_attr($background_class) .'" data-aos="'. esc_attr($this->animation) .'" data-aos-left="'. esc_attr($this->animation_loadmore_left) .'" data-aos-right="'. esc_attr($this->animation_loadmore_right) .'" data-animation-offset="'. esc_attr($settings['animation_offset']) .'" data-animation-duration="'. esc_attr($settings['aos_animation_duration']) .'">';

							if ( 'image-top' === $settings['content_layout'] && !empty($this->src) || 'yes' === $settings['show_overlay'] && !empty($this->src) ) {
								if ( 'yes' === $settings['enable_img_link'] ) {
									echo '<div class="crt-animation-wrap crt-timeline-media"><a href="'. get_the_permalink() .'"><img class="crt-thumbnail-image" src="'. esc_url($this->src) .'"></a>';
								} else {
									echo '<div class="crt-animation-wrap crt-timeline-media"><img class="crt-thumbnail-image" src="'. esc_url($this->src) .'">';
								}
							}
								echo ($settings['show_overlay'] === 'yes' && !empty(get_the_post_thumbnail_url())) ? '<div class="crt-timeline-story-overlay '. esc_attr($this->animation_class) .'">' : '';

									if ( 'yes' === $settings['title_overlay'] ) {
										$this->render_title($settings);
									}

									if ( 'yes' === $settings['show_date'] && 'yes' === $settings['date_overlay'] ) {

										echo '<div class="crt-inner-date-label">';
										$this->date_and_extra_label($settings, 'date_source', 'date_field_key');
										echo '</div>';
		
									}

									if ( 'yes' === $settings['description_overlay'] ) {
										$this->render_description($settings);
									}

									if ( 'yes' === $settings['readmore_overlay'] ) {
										$this->render_read_more($settings);
									}

								echo ($settings['show_overlay'] === 'yes' && !empty(get_the_post_thumbnail_url())) ? '</div>' : '';
									
							echo ($settings['content_layout'] === 'image-top' && !empty($this->src)) || ($settings['show_overlay'] === 'yes' && !empty($this->src)) ? '</div>' : '';

							echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] || 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] || 'yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay']  ? '<div class="crt-timeline-content-wrapper">' : '';

									if ( 'yes' !== $settings['title_overlay'] ) {
										$this->render_title($settings);
									}

									if ( 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] ) {
										echo '<div class="crt-inner-date-label">';
										$this->date_and_extra_label($settings, 'date_source', 'date_field_key');
										echo '</div>';
									}

									if ( 'yes' !== $settings['description_overlay'] ) {
										$this->render_description($settings);
									}

									if ( 'yes' !== $settings['readmore_overlay'] ) {
										$this->render_read_more($settings);
									}

							echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] || 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] || 'yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay']  ? '</div>' : '';

								if ( 'image-bottom' === $settings['content_layout'] && ! empty( $this->src ) ) {
									echo '<div class="crt-animation-wrap crt-timeline-media">';
									if ( 'yes' === $settings['enable_img_link'] ) {
										echo '<a href="' . esc_url( get_the_permalink() ) . '"><img class="crt-thumbnail-image" src="' . esc_url( $this->src ) . '"></a>';
									} else {
										echo '<img class="crt-thumbnail-image" src="' . esc_url( $this->src ) . '">';
									}
									echo '</div>';
								}

							echo '</div>';
					echo '</div>';
					echo '</article>';	

					$countItem++;
			}
			
			echo'</div>';  
			echo '</div>';

			// Pagination
			if(!($settings['posts_per_page'] >= wp_count_posts($settings['timeline_post_types'])->publish)) {
				$this->render_pagination($settings, $paged);
			}
		}
	} // end rendern_dynamic_vertical_timeline

	public function render_custom_horizontal_timeline( $settings, $autoplay, $loop, $dir, $data, $slidesHeight, $swiper_speed, $swiper_delay, $swiper_pause_on_hover ) {

		$this->horizontal_timeline_classes($settings);

		echo '<div class="crt-timeline-outer-container">';
		echo '<div class="crt-wrapper swiper '. esc_attr($this->horizontal_inner_class) .'" dir="'. esc_attr($dir) .'" data-slidestoshow = "'. esc_attr($this->slides_to_show) .'" data-autoplay="'. esc_attr($autoplay) .'" data-loop="'. esc_attr($loop) .'" data-swiper-speed="'. esc_attr($swiper_speed) .'" data-swiper-delay="'. esc_attr($swiper_delay) .'" data-swiper-poh="'. $swiper_pause_on_hover .'" data-swiper-space-between="'. esc_attr($settings['story_info_gutter']) .'">';

		echo '<div class="swiper-wrapper '. esc_attr($this->horizontal_timeline_class) .'">';
			if ( is_array($data) ) {
					foreach( $data as $index => $content ) {
//						if ( (!defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code()) && $index === 4 ) {
//							break;
//						}

						$repeater_title_link = isset($content['repeater_title_link']) && !empty($content['repeater_title_link']['url']) ? $content['repeater_title_link'] : '';

						if ( ! empty( $content['repeater_title_link']['url'] ) ) {
							$this->add_link_attributes( 'repeater_title_link'. $this->item_url_count, $content['repeater_title_link'] );
						}

						$this->add_custom_horizontal_timeline_attributes($content, $settings, $index);
				
						$this->thumbnail_custom_dimension = $content['crt_thumbnail_custom_dimension'];

						$this->render_image_or_icon($content, $settings, $repeater_title_link);

						echo '<div class="swiper-slide '. esc_attr($this->swiper_class) .' '. esc_attr($slidesHeight) .' elementor-repeater-item-'. esc_attr($content['_id']) .'">';

							if ( 'yes' === $content['repeater_show_extra_label'] ) {
								echo !empty($this->story_date_label) || !empty($this->story_extra_label) ? '<div class="crt-extra-label" >' : '';
								  echo !empty($this->story_date_label) ? '<span '. $this->get_render_attribute_string( $this->date_label_key ) .' >'. esc_html__($this->story_date_label) .'</span>' : ''; 
								  echo !empty($this->story_extra_label) ? '<span '. $this->get_render_attribute_string( $this->extra_label_key ) .' >'. wp_kses_post($this->story_extra_label) .'</span>' : '';
								echo !empty($this->story_date_label) || !empty($this->story_extra_label) ? '</div>' : '';
							}

							$this->render_main_line_icon($settings, $content);

							echo '<div class="crt-story-info '. esc_attr($this->background_class) .'">';

								echo !empty($this->image) && 'image-top' === $settings['content_layout'] || !empty($content['repeater_youtube_video_url']) && 'image-top' === $settings['content_layout'] ? '<div class="crt-animation-wrap crt-timeline-media">' : '';

									echo 'image-top' === $settings['content_layout'] ? $this->image : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

									echo !empty( $content['repeater_youtube_video_url'] ) && $settings['content_layout'] == 'image-top' ? '<div class="crt-timeline-iframe-wrapper">  '. $this->youtube_url($content) .' </div>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

									echo 'yes' === $settings['show_overlay'] && !empty($this->image) || $settings['show_overlay'] === 'yes' && !empty($content['repeater_youtube_video_url']) ? '<div class="crt-timeline-story-overlay '. esc_attr($this->animation_class) .'">' : '';

										if ( 'yes' === $settings['title_overlay'] ) {
											$this->render_title($settings, $content, $repeater_title_link);
										}

										if ( 'yes' === $settings['description_overlay'] ) {
											$this->render_description($settings, $content);
										}

									echo 'yes' === $settings['show_overlay'] && !empty($this->image) || $settings['show_overlay'] === 'yes' && !empty($content['repeater_youtube_video_url']) ? '</div>' : '';
								
								echo !empty($this->image) && 'image-top' === $settings['content_layout'] || !empty($content['repeater_youtube_video_url']) && 'image-top' === $settings['content_layout'] ? '</div>' : ''; 
									
								echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] && !empty($content['repeater_story_title'])  || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] && !empty($content['repeater_description']) ? '<div class="crt-timeline-content-wrapper">' : '';

									if ( 'yes' !== $settings['title_overlay'] ) {
										$this->render_title($settings, $content, $repeater_title_link);
									}

									if ( 'yes' !== $settings['description_overlay'] ) {
										$this->render_description($settings, $content);
									}

									echo !empty( $content['repeater_youtube_video_url'] ) && $settings['content_layout'] !== 'image-top' ? '<div class="crt-timeline-iframe-wrapper">  '. $this->youtube_url($content) .' </div>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

								echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] && !empty($content['repeater_story_title'])  || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] && !empty($content['repeater_description']) ? '</div>' : '';	 

								echo 'image-bottom' === $settings['content_layout'] && !empty($this->image) ? '<div class="crt-animation-wrap crt-timeline-media">'. $this->image .'</div>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped   
							echo '</div>';
						echo '</div>';
							
						$this->item_url_count++;
					}
				} 
				
				$this->crt_render_swiper_navigation($settings);
				echo '</div>';
	}
	
	public function render_dynamic_horizontal_timeline ( $settings, $dir, $autoplay, $loop, $slidesHeight, $swiper_speed, $swiper_delay, $swiper_pause_on_hover ) {
		
		wp_reset_postdata();

		$this->horizontal_timeline_classes($settings);
	
		if(!$this->my_query->have_posts()) {
			echo '<div> '. esc_html__($settings['query_not_found_text']) .'</div>';
		}
	
		if( $this->my_query->have_posts() ) { 
		
		echo '<div class="crt-timeline-outer-container">';
				echo '<div class="crt-wrapper swiper '. esc_attr($this->horizontal_inner_class) .'" dir="'. esc_attr($dir) .'" data-slidestoshow = "'. esc_attr($this->slides_to_show) .'" data-autoplay="'. esc_attr($autoplay) .'"  data-loop="'. esc_attr($loop) .'" data-swiper-speed="'. esc_attr($swiper_speed) .'" data-swiper-delay="'. esc_attr($swiper_delay) .'" data-swiper-poh="'. $swiper_pause_on_hover .'" data-swiper-space-between="'. esc_attr($settings['story_info_gutter']) .'">
					<div class="'. esc_attr($this->horizontal_timeline_class) .' swiper-wrapper">';
					while( $this->my_query->have_posts() ) {
						$this->my_query->the_post();

						
					
					$id = get_post_thumbnail_id();
					$this->src = Group_Control_Image_Size::get_attachment_image_src( $id, 'crt_thumbnail_dynamic', $settings );
						
						$background_image = $settings['content_layout'] === 'background' ? get_the_post_thumbnail_url() : '';
						$background_class = $settings['content_layout'] === 'background' ? 'story-with-background' : '';
						
					echo '<div class="swiper-slide  '. esc_attr($this->swiper_class) .'  '. esc_attr($slidesHeight) .'">';
						// TODO: apply animation class to other layouts as well
						echo '<div class="crt-story-info '. esc_attr($background_class) .'">';
						echo ($settings['content_layout'] === 'image-top' && !empty($this->src)) || ($settings['show_overlay'] === 'yes' && !empty($this->src)) ? '<div class="crt-animation-wrap crt-timeline-media">' : '';

						if ( $settings['content_layout'] === 'image-top' && ! empty( $this->src ) ) {
							if ( 'yes' === $settings['enable_img_link'] ) {
								echo '<a href="' . esc_url( get_the_permalink() ) . '"><img class="crt-thumbnail-image" src="' . esc_url( $this->src ) . '"></a>';
							} else {
								echo '<img class="crt-thumbnail-image" src="' . esc_url( $this->src ) . '">';
							}
						}
	
						echo ($settings['show_overlay'] === 'yes' && !empty(get_the_post_thumbnail_url())) ? '<div class="crt-timeline-story-overlay '. esc_attr($this->animation_class) .'">' : '';

							if ( 'yes' === $settings['title_overlay'] ) {
								$this->render_title($settings);
							}

							if ( 'yes' === $settings['show_date'] && 'yes' === $settings['date_overlay'] ) {

								echo '<div class="crt-inner-date-label">';
								$this->date_and_extra_label($settings, 'date_source', 'date_field_key');
								echo '</div>';

							}

							if ( 'yes' === $settings['description_overlay'] ) {
								$this->render_description($settings);
							}

							if ( 'yes' === $settings['readmore_overlay'] ) {
								$this->render_read_more($settings);
							}
	
						echo ($settings['show_overlay'] === 'yes' && !empty(get_the_post_thumbnail_url())) ? '</div>' : '';
						echo ($settings['content_layout'] === 'image-top' && !empty($this->src)) || ($settings['show_overlay'] === 'yes' && !empty($this->src)) ? '</div>' : '';
						
						echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] || 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] || 'yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay']  ? '<div class="crt-timeline-content-wrapper">' : '';

							if ( 'yes' !== $settings['title_overlay'] ) {
								$this->render_title($settings);
							}

							if ( 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] ) {

								echo '<div class="crt-inner-date-label">';
								
								$this->date_and_extra_label($settings, 'date_source', 'date_field_key');

								echo '</div>';

							}

							if ( 'yes' !== $settings['description_overlay'] ) {
								$this->render_description($settings);
							}

							if ( 'yes' !== $settings['readmore_overlay'] ) {
								$this->render_read_more($settings);
							}

						echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] || 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] || 'yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay'] ? '</div>' : '';
	
						if ( 'image-bottom' === $settings['content_layout'] && ! empty( $this->src ) ) {
							echo '<div class="crt-animation-wrap crt-timeline-media">';
							if ( 'yes' === $settings['enable_img_link'] ) {
								echo '<a href="' . esc_url( get_the_permalink() ) . '"><img class="crt-thumbnail-image" src="' . esc_url( $this->src ) . '"></a>';
							} else {
								echo '<img class="crt-thumbnail-image" src="' . esc_url( $this->src ) . '">';
							}
							echo '</div>';
						}

						echo '</div>';
	
						if ( 'yes' === $settings['show_extra_label'] ) {	
							echo '<div class="crt-extra-label">';
								echo '<span class="crt-label">';
								$this->date_and_extra_label($settings, 'extra_label_source', 'meta_field_key');
								echo '</span>';
							echo '</div>';
						}

						$this->render_main_line_icon($settings);

					echo '</div>';
				}
	
				$this->crt_render_swiper_navigation($settings);
				echo '</div>';
			}
	}

	public function add_option_query_source() {
		$post_types = [];
		$post_types['post'] = esc_html__( 'Posts', 'crt-manage' );
		$post_types['page'] = esc_html__( 'Pages', 'crt-manage' );

		$custom_post_types = Utilities::get_custom_types_of( 'post', true );
		foreach( $custom_post_types as $slug => $title ) {
			if ( 'product' === $slug || 'e-landing-page' === $slug ) {
				continue;
			}
            $post_types[$slug] = esc_html__( $title );
		}

		$post_types['current'] = esc_html__( 'Current Query', 'crt-manage' );
		$post_types['pro-rl'] = esc_html__( 'Related Query', 'crt-manage' );
		
		return $post_types;
	}

	public function date_and_extra_label($settings, $source, $field_key) {
		if ( isset($settings[$source]) && 'meta_field' === $settings[$source] ) {
			$meta_type = get_post_meta( get_the_ID(), $settings[$field_key] . '_type', true );

			if ( empty($meta_type) ) {
				$meta_value = get_post_meta( get_the_ID(), $settings[$field_key], true );
				if ( strtotime($meta_value) !== false ) {
					$meta_type = 'date';
				}
			}

			if ( 'meta_field' === $settings[$source] && 'date' === $meta_type ) {
				echo esc_html__( date( $settings['date_format'], strtotime( $meta_value ) ) );
			} else {
				echo esc_html__( $meta_value );
			}
		} else {
			echo esc_html__( get_the_date( $settings['date_format'] ) );
		}
	}

	public function render_title( $settings, $content = [], $repeater_title_link = '' ) {
		if ( 'yes' !== $settings['show_title'] ) {
			return;
		}

		if ( $settings['timeline_content'] === 'dynamic' ) {
			echo '<div class="crt-title-wrap"><a href="'. esc_url(get_the_permalink()) .'"><'. esc_attr($settings['title_tag']) .' class="crt-title">'. esc_html__(get_the_title()) .'</'. esc_attr($settings['title_tag']) .'></a></div>';
		} else {
			if ( 'horizontal' === $settings['timeline_layout'] || 'horizontal-bottom' === $settings['timeline_layout'] ) {
				if ( empty($this->timeline_story_title) ) {
					return;
				}

				if ( '' !== $repeater_title_link ) {
					echo '<div class="crt-title-wrap"><a ' . 
						$this->get_render_attribute_string( 'repeater_title_link'. $this->item_url_count ) . '><'. esc_attr($settings['title_tag']) .' ' . 
						$this->get_render_attribute_string( $this->title_key ) . '>' . 
						esc_html__($this->timeline_story_title) . 
						'</'. esc_attr($settings['title_tag']) .'></a></div>';
				} else {
					echo '<div class="crt-title-wrap"><'. esc_attr($settings['title_tag']) .' ' . 
						$this->get_render_attribute_string( $this->title_key ) . '>' . 
						esc_html__($this->timeline_story_title) . 
						'</'. esc_attr($settings['title_tag']) .'></div>';
				}
			} else {
				if ( empty($content['repeater_story_title']) ) {
					return;
				}

				if ( '' !== $repeater_title_link ) {
					echo '<div class="crt-title-wrap"><a '. $this->get_render_attribute_string( 'repeater_title_link'. $this->item_url_count ) .'><'. esc_attr($settings['title_tag']) .' class="crt-title">'. esc_html__($content['repeater_story_title']) .'</'. esc_attr($settings['title_tag']) .'></a></div>';
				} else {
					echo '<div class="crt-title-wrap"><'. esc_attr($settings['title_tag']) .' class="crt-title">'. esc_html__($content['repeater_story_title']) .'</'. esc_attr($settings['title_tag']) .'></div>';
				}
			}
		}
	}

	public function render_description( $settings, $content = [] ) {
		if ( 'dynamic' === $settings['timeline_content'] ) {							
			echo !empty(get_the_content()) && 'yes' === $settings['show_description'] ? '<div class="crt-description">'. esc_html__(wp_trim_words(get_the_content(), $settings['excerpt_count'])) .'</div>' : '';
		} else {
			if ( 'horizontal' === $settings['timeline_layout'] || 'horizontal-bottom' === $settings['timeline_layout'] ) {
				echo !empty($this->timeline_description) && 'yes' === $settings['show_description'] ? '<div '. $this->get_render_attribute_string( $this->description_key ) .'>'. wp_kses_post($this->timeline_description) .'</div>' : '';
			} else {
				echo !empty($content['repeater_description']) && 'yes' === $settings['show_description'] ? '<div class="crt-description">'. wp_kses_post($content['repeater_description']) .'</div>' : '';
			}
		}
	}

	public function render_read_more( $settings ) {
		if ( 'yes' === $this->show_readmore ) {
			echo '<div class="crt-read-more-wrap"><a class="crt-read-more-button" href="'. esc_url(get_the_permalink()) .'">'. esc_html__($settings['read_more_text']) .'</a></div>';
		}
	}

	public function render_main_line_icon( $settings, $content = [] ) {
		echo '<div class="crt-main-line-icon crt-icon">';

		if ( 'dynamic' === $settings['timeline_content'] ) {
			\Elementor\Icons_Manager::render_icon( $settings['posts_icon'], [ 'aria-hidden' => 'true' ] );
		} else {
			\Elementor\Icons_Manager::render_icon( $content['repeater_story_icon'], [ 'aria-hidden' => 'true' ] );
		}

		echo '</div>';
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		global $paged;
		$paged = 1;
		$this->my_query = 'dynamic' === $settings['timeline_content'] ? new \WP_Query ($this->get_main_query_args()) : '';
		
		$layout = $settings['timeline_layout'];

		$this->animation = $settings['timeline_animation'];
		$this->animation_loadmore_left = '';
		$this->animation_loadmore_right = '';

		$this->timeline_fill = $settings['timeline_fill'];
		$this->show_readmore = !empty($settings['show_readmore']) ? $settings['show_readmore'] : '';

		$data = $settings['timeline_repeater_list'];
		
		$loop =  $settings['swiper_loop'];
		$autoplay =  $settings['swiper_autoplay'];

		// $this->pause_on_hover = (!defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code()) && !isset($settings['pause_on_hover']) ? '' : $settings['pause_on_hover'];
		$swiper_delay = $settings['swiper_delay'];
		$swiper_pause_on_hover = $settings['swiper_pause_on_hover'];
		$swiper_speed = $settings['swiper_speed'];
		$slidesHeight = $settings['equal_height_slides'];

		$this->pagination_type = !empty($settings['pagination_type']) ? $settings['pagination_type'] : '';
		$this->pagination_max_pages = !empty($this->get_max_num_pages( $settings )) ? $this->get_max_num_pages( $settings ) : '';
		$arrow_bgcolor = $settings['triangle_bgcolor'];

		$animation_settings = [	
			'overlay_animation' => $settings['overlay_animation'], 
			'overlay_animation_size' => $settings['overlay_animation_size'],
			'overlay_animation_timing' => $settings['overlay_animation_timing'],
			'overlay_animation_tr' => $settings['overlay_animation_tr'],
		];

		$this->animation_class = $this->get_animation_class( $animation_settings, 'overlay' );
		
		$isRTL = is_rtl();
		$dir = '';
		if($isRTL){
			$dir = 'rtl';
		}

			if ( 'one-sided' === $layout ){
				$this->timeline_layout = "crt-one-sided-timeline";
				$this->timeline_layout_wrapper = "crt-one-sided-wrapper";
			} elseif ( 'centered' === $layout) {
				$this->timeline_layout = 'crt-both-sided-timeline';
				$this->timeline_layout_wrapper = 'crt-centered';
			} elseif ( 'one-sided-left' === $layout ) {
				$this->timeline_layout = "crt-one-sided-timeline-left";
				$this->timeline_layout_wrapper = "crt-one-sided-wrapper-left";
			} elseif ( 'horizontal' === $layout ) {
				$this->timeline_layout = "crt-horizontal-timeline";
				$this->timeline_layout_wrapper = "crt-horizontal-wrapper";
			}

			$countItem = !empty($countItem) ? $countItem : 0;
			$this->item_url_count = 0;

			if ( 'dynamic' === $settings['timeline_content'] && ('horizontal' === $layout || 'horizontal-bottom' === $layout) ) {

					$this->render_dynamic_horizontal_timeline ( $settings, $dir, $autoplay, $loop, $slidesHeight, $swiper_speed, $swiper_delay, $swiper_pause_on_hover );


			} elseif ( 'custom' === $settings['timeline_content'] && ('horizontal' === $layout || 'horizontal-bottom' === $layout) ) {

					$this->render_custom_horizontal_timeline( $settings, $autoplay, $loop, $dir, $data, $slidesHeight,  $swiper_speed, $swiper_delay, $swiper_pause_on_hover );

			} else {
				if( 'dynamic' === $settings['timeline_content'] ) {

					$this->render_dynamic_vertical_timeline($settings, $arrow_bgcolor, $layout, $countItem, $paged );

				} else {

					$this->render_custom_vertical_timeline($layout, $settings, $data, $countItem );

				}
			}
	}
}
