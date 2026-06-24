<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use CrtAddons\Classes\Utilities;
use Elementor\Utils;
use Elementor\Icons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Testimonial_Carousel extends Widget_Base {
		
	public function get_name() {
		return 'crt-testimonial';
	}

	public function get_title() {
		return esc_html__( 'Testimonial Carousel', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-testimonial-carousel';
	}

	public function get_categories() {
		return [ 'crt_manage_theme'];
	}

	public function get_keywords() {
		return [ 'testimonial carousel', 'reviews', 'rating', 'stars' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}
	
	public function get_script_depends() {
		return [ 'imagesloaded', 'crt-manage-lib-slick', 'crt-testimonial' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com/contact';
    }

    public function add_control_testimonial_amount() {
        $this->add_responsive_control(
            'testimonial_amount',
            [
                'label' => esc_html__( 'Columns', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 3,
                'widescreen_default' => 3,
                'laptop_default' => 3,
                'tablet_extra_default' => 3,
                'tablet_default' => 2,
                'mobile_extra_default' => 2,
                'mobile_default' => 1,
                'options' => [
                    1 => esc_html__( 'One', 'crt-manage' ),
                    2 => esc_html__( 'Two', 'crt-manage' ),
                    3 => esc_html__( 'Three', 'crt-manage' ),
                    4 => esc_html__( 'Four', 'crt-manage' ),
                    5 => esc_html__( 'Five', 'crt-manage' ),
                    6 => esc_html__( 'Six', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-testimonial-slider-columns-%s',
                'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );
    }

    public function add_control_testimonial_icon() {
        $this->add_control(
            'testimonial_icon',
            [
                'label' => esc_html__( 'Select Quote Icon', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => Utilities::get_svg_icons_array( 'blockquote', [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'fas fa-quote-left' => esc_html__( 'Quote Left', 'crt-manage' ),
                    'fas fa-quote-right' => esc_html__( 'Quote Right', 'crt-manage' ),
                    'svg-icons' => esc_html__( 'SVG Icons -----', 'crt-manage' ),
                ] ),
                'separator' => 'before',
            ]
        );
    }

    public function add_control_testimonial_rating_score() {
        $this->add_control(
            'testimonial_rating_score',
            [
                'label' => esc_html__( 'Show Score', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => [
                    'testimonial_rating' => 'yes',
                ],
            ]
        );
    }

    public function add_repeater_args_social_media() {
        return [
            'label' => esc_html__( 'Social Media', 'crt-manage' ),
            'type' => Controls_Manager::SWITCHER,
        ];
    }

    public function add_repeater_args_social_media_is_external() {
        return [
            'label' => esc_html__( 'Open in new window', 'crt-manage' ),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_media_nofollow() {
        return [
            'label' => esc_html__( 'Add nofollow', 'crt-manage' ),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_section_1() {
        return [
            'label' => esc_html__( 'Social 1', 'crt-manage' ),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_icon_1() {
        return [
            'label' => esc_html__( 'Select Icon', 'crt-manage' ),
            'type' => Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
            'default' => [
                'value' => 'fab fa-facebook-f',
                'library' => 'fa-brands',
            ],
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_url_1() {
        return [
            'label' => esc_html__( 'Social URL', 'crt-manage' ),
            'type' => Controls_Manager::URL,
            'dynamic' => [
                'active' => true,
            ],
            'show_external' => false,
            'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_section_2() {
        return [
            'label' => esc_html__( 'Social 2', 'crt-manage' ),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_icon_2() {
        return [
            'label' => esc_html__( 'Select Icon', 'crt-manage' ),
            'type' => Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
            'default' => [
                'value' => 'fab fa-pinterest',
                'library' => 'fa-brands',
            ],
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_url_2() {
        return [
            'label' => esc_html__( 'Social URL', 'crt-manage' ),
            'type' => Controls_Manager::URL,
            'dynamic' => [
                'active' => true,
            ],
            'show_external' => false,
            'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }


    public function add_repeater_args_social_section_3() {
        return [
            'label' => esc_html__( 'Social 3', 'crt-manage' ),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_icon_3() {
        return [
            'label' => esc_html__( 'Select Icon', 'crt-manage' ),
            'type' => Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
            'default' => [
                'value' => 'fab fa-twitter',
                'library' => 'fa-brands',
            ],
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_url_3() {
        return [
            'label' => esc_html__( 'Social URL', 'crt-manage' ),
            'type' => Controls_Manager::URL,
            'dynamic' => [
                'active' => true,
            ],
            'show_external' => false,
            'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_section_4() {
        return [
            'label' => esc_html__( 'Social 4', 'crt-manage' ),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_icon_4() {
        return [
            'label' => esc_html__( 'Select Icon', 'crt-manage' ),
            'type' => Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
            'default' => [
                'value' => 'fab fa-dribbble',
                'library' => 'fa-brands',
            ],
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_url_4() {
        return [
            'label' => esc_html__( 'Social URL', 'crt-manage' ),
            'type' => Controls_Manager::URL,
            'dynamic' => [
                'active' => true,
            ],
            'show_external' => false,
            'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_section_5() {
        return [
            'label' => esc_html__( 'Social 5', 'crt-manage' ),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_icon_5() {
        return [
            'label' => esc_html__( 'Select Icon', 'crt-manage' ),
            'type' => Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
            'default' => [
                'value' => 'fab fa-linkedin',
                'library' => 'fa-brands',
            ],
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_social_url_5() {
        return [
            'label' => esc_html__( 'Social URL', 'crt-manage' ),
            'type' => Controls_Manager::URL,
            'dynamic' => [
                'active' => true,
            ],
            'show_external' => false,
            'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
            'condition' => [
                'social_media' => 'yes',
            ],
        ];
    }

    public function add_control_stack_testimonial_autoplay() {
        $this->add_control(
            'testimonial_autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'testimonial_autoplay_duration',
            [
                'label' => esc_html__( 'Autoplay Speed', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 4,
                'min' => 0,
                'max' => 15,
                'step' => 0.5,
                'frontend_available' => true,
                'condition' => [
                    'testimonial_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'testimonial_pause_on_hover',
            [
                'label' => esc_html__( 'Pause Slide on Hover', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'testimonial_autoplay' => 'yes',
                ],
            ]
        );
    }

    public function add_control_stack_nav_position() {
        $this->add_control(
            'nav_position',
            [
                'label' => esc_html__( 'Positioning', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'custom',
                'options' => [
                    'default' => esc_html__( 'Default', 'crt-manage' ),
                    'custom' => esc_html__( 'Custom', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-testimonial-nav-position-',
            ]
        );

        $this->add_control(
            'nav_position_default',
            [
                'label' => esc_html__( 'Align', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'top-left',
                'options' => [
                    'top-left' => esc_html__( 'Top Left', 'crt-manage' ),
                    'top-center' => esc_html__( 'Top Center', 'crt-manage' ),
                    'top-right' => esc_html__( 'Top Right', 'crt-manage' ),
                    'bottom-left' => esc_html__( 'Bottom Left', 'crt-manage' ),
                    'bottom-center' => esc_html__( 'Bottom Center', 'crt-manage' ),
                    'bottom-right' => esc_html__( 'Bottom Right', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-testimonial-nav-align-',
                'condition' => [
                    'nav_position' => 'default',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_outer_distance',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Outer Distance', 'crt-manage' ),
                'size_units' => [ 'px' ],
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
                    '{{WRAPPER}}[class*="crt-testimonial-nav-align-top"] .crt-testimonial-arrow-container' => 'top: {{SIZE}}px;',
                    '{{WRAPPER}}[class*="crt-testimonial-nav-align-bottom"] .crt-testimonial-arrow-container' => 'bottom: {{SIZE}}px;',
                    '{{WRAPPER}}.crt-testimonial-nav-align-top-left .crt-testimonial-arrow-container' => 'left: {{SIZE}}px;',
                    '{{WRAPPER}}.crt-testimonial-nav-align-bottom-left .crt-testimonial-arrow-container' => 'left: {{SIZE}}px;',
                    '{{WRAPPER}}.crt-testimonial-nav-align-top-right .crt-testimonial-arrow-container' => 'right: {{SIZE}}px;',
                    '{{WRAPPER}}.crt-testimonial-nav-align-bottom-right .crt-testimonial-arrow-container' => 'right: {{SIZE}}px;',
                ],
                'condition' => [
                    'nav_position' => 'default',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_inner_distance',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Inner Distance', 'crt-manage' ),
                'size_units' => [ 'px' ],
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
                    '{{WRAPPER}} .crt-testimonial-arrow-container .crt-testimonial-prev-arrow' => 'margin-right: {{SIZE}}px;',
                ],
                'condition' => [
                    'nav_position' => 'default',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_position_top',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Vertical Position', 'crt-manage' ),
                'size_units' => [ '%','px' ],
                'range' => [
                    '%' => [
                        'min' => -20,
                        'max' => 120,
                    ],
                    'px' => [
                        'min' => -200,
                        'max' => 1200,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 52,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-testimonial-arrow' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'nav_position' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_position_left',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Left Position', 'crt-manage' ),
                'size_units' => [ '%','px' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 120,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-testimonial-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'nav_position' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_position_right',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Right Position', 'crt-manage' ),
                'size_units' => [ '%','px' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 120,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-testimonial-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'nav_position' => 'custom',
                ],
            ]
        );
    }

    public function add_control_dots_hr() {
        $this->add_responsive_control(
            'dots_hr',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Horizontal Position', 'crt-manage' ),
                'size_units' => [ '%','px' ],
                'range' => [
                    '%' => [
                        'min' => -20,
                        'max' => 120,
                    ],
                    'px' => [
                        'min' => -200,
                        'max' => 1200,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-testimonial-dots' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
    }

    protected function register_controls() {

		// Section: Items -----------
		$this->start_controls_section(
			'crt__section_testimonial_items',
			[
				'label' => esc_html__( 'Items', 'crt-manage' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'testimonial_author',
			[
				'label' => esc_html__( 'Author', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'John Doe',
			]
		);

		$repeater->add_control(
			'testimonial_job',
			[
				'label' => esc_html__( 'Job', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Sony CEO',
			]
		);

		$repeater->add_control(
			'testimonial_image',
			[
				'label' => esc_html__( 'Author Image', 'crt-manage' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'testimonial_logo_image',
			[
				'label' => esc_html__( 'Company Logo', 'crt-manage' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'testimonial_logo_url',
			[
				'label' => esc_html__( 'Logo URL', 'crt-manage' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://www.your-link.com', 'crt-manage' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'testimonial_logo_image[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$repeater->add_control(
            'testimonial_title_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$repeater->add_control(
			'testimonial_title',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Awesome Theme',
			]
		);

		$repeater->add_control(
			'testimonial_rating_amount',
			[
				'label' => esc_html__( 'Rating', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 10,
				'step' => 0.1,
				'default' => 4.5,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'testimonial_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.',
			]
		);

		$repeater->add_control(
			'testimonial_date',
			[
				'label' => esc_html__( 'Date', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '7 Days Ago',
			]
		);

		$repeater->add_control(
            'social_media_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$repeater->add_control( 'social_media', $this->add_repeater_args_social_media() );

		$repeater->add_control( 'social_media_is_external', $this->add_repeater_args_social_media_is_external() );

		$repeater->add_control( 'social_media_nofollow', $this->add_repeater_args_social_media_nofollow() );

		$repeater->add_control( 'social_section_1', $this->add_repeater_args_social_section_1() );

		$repeater->add_control( 'social_icon_1', $this->add_repeater_args_social_icon_1() );

		$repeater->add_control( 'social_url_1', $this->add_repeater_args_social_url_1() );

		$repeater->add_control( 'social_section_2', $this->add_repeater_args_social_section_2() );

		$repeater->add_control( 'social_icon_2', $this->add_repeater_args_social_icon_2() );

		$repeater->add_control( 'social_url_2', $this->add_repeater_args_social_url_2() );

		$repeater->add_control( 'social_section_3', $this->add_repeater_args_social_section_3() );

		$repeater->add_control( 'social_icon_3', $this->add_repeater_args_social_icon_3() );

		$repeater->add_control( 'social_url_3', $this->add_repeater_args_social_url_3() );

		$repeater->add_control( 'social_section_4', $this->add_repeater_args_social_section_4() );

		$repeater->add_control( 'social_icon_4', $this->add_repeater_args_social_icon_4() );

		$repeater->add_control( 'social_url_4', $this->add_repeater_args_social_url_4() );

		$repeater->add_control( 'social_section_5', $this->add_repeater_args_social_section_5() );

		$repeater->add_control( 'social_icon_5', $this->add_repeater_args_social_icon_5() );

		$repeater->add_control( 'social_url_5', $this->add_repeater_args_social_url_5() );

		$this->add_control(
			'testimonial_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'testimonial_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'testimonial_rating_amount' => 4.5,
						'testimonial_title' => esc_html__( 'Awesome Theme', 'crt-manage' ),
						'testimonial_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.', 'crt-manage' ),
						'testimonial_author' => esc_html__( 'John Doe', 'crt-manage' ),
						'testimonial_job' => esc_html__( 'Sony CEO', 'crt-manage' ),
						'testimonial_date' => esc_html__( '7 Days Ago', 'crt-manage' ),
						'social_icon_1' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
						'social_icon_2' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
						'social_icon_3' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ],
					],
					[		
						'testimonial_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'testimonial_rating_amount' => 5,
						'testimonial_title' => esc_html__( 'Simply The Best', 'crt-manage' ),
						'testimonial_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.', 'crt-manage' ),
						'testimonial_author' => esc_html__( 'Tom Jones', 'crt-manage' ),
						'testimonial_job' => esc_html__( 'Tesla CMO', 'crt-manage' ),
						'testimonial_date' => esc_html__( '10.04.2018', 'crt-manage' ),
						'social_icon_1' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
						'social_icon_2' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
						'social_icon_3' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ],
					],
					[	
						'testimonial_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'testimonial_rating_amount' => 4,
						'testimonial_title' => esc_html__( 'Easy To Use', 'crt-manage' ),
						'testimonial_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.', 'crt-manage' ),
						'testimonial_author' => esc_html__( 'Mark Wilson', 'crt-manage' ),
						'testimonial_job' => esc_html__( 'Apple Manager', 'crt-manage' ),
						'testimonial_date' => esc_html__( '5 Month Ago', 'crt-manage' ),
						'social_icon_1' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
						'social_icon_2' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
						'social_icon_3' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ],
					],
					[	
						'testimonial_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],		
						'testimonial_rating_amount' => 3.5,
						'testimonial_title' => esc_html__( 'Creative', 'crt-manage' ),
						'testimonial_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.', 'crt-manage' ),
						'testimonial_author' => esc_html__( 'Bob Smith', 'crt-manage' ),
						'testimonial_job' => esc_html__( 'Doctor', 'crt-manage' ),
						'testimonial_date' => esc_html__( '6 Month Ago', 'crt-manage' ),
						'social_icon_1' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
						'social_icon_2' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
						'social_icon_3' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ],
					],
				],
				'title_field' => '{{{ testimonial_title }}}',
			]
		);



		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->start_controls_section(
			'crt__section_settings',
			[
				'label' => esc_html__( 'Settings', 'crt-manage' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'testimonial_image_size',
				'default' => 'full',
			]
		);

		$this->add_control_testimonial_amount();


		$this->add_control(
			'testimonial_slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'frontend_available' => true,
				'default' => 2,
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'testimonial_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'widescreen_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'laptop_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'tablet_extra_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'mobile_extra_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-carousel .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-testimonial-carousel .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'testimonial_amount!' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'testimonial_nav',
			[
				'label' => esc_html__( 'Navigation', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'flex'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-arrow' => 'display:{{VALUE}} !important;',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'testimonial_nav_hover',
			[
				'label' => esc_html__( 'Show on Hover', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'fade',
				'prefix_class'	=> 'crt-testimonial-nav-',
				'render_type' => 'template',
				'condition' => [
					'testimonial_nav' => 'yes',
				],
			]
		);


		$this->add_control(
			'testimonial_nav_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'svg-angle-1-left',
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
					'testimonial_nav' => 'yes',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'testimonial_dots',
			[
				'label' => esc_html__( 'Pagination', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'inline-table'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-dots' => 'display: {{VALUE}} !important;',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control_stack_testimonial_autoplay();

		$this->add_control(
			'testimonial_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'testimonial_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'crt-manage' ),
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'crt-manage' ),
					'fade' => esc_html__( 'Fade', 'crt-manage' ),
				],
			]
		);

		$this->add_control(
			'testimonial_effect_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,	
			]
		);

		// Icon
		$this->add_control_testimonial_icon();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'testimonial', 'testimonial_icon', ['pro-svg'] );

		$this->add_control(//TODO: This option doesn't work properly
			'testimonial_icon_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Icon Position', 'crt-manage' ),
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top Content', 'crt-manage' ),
					'inner' => esc_html__( 'Inner Content', 'crt-manage' ),
				],
				'condition' => [
					'testimonial_icon!' => 'none',
				],
				'render_type' => 'template',
			]
		);

		// Rating
		$this->add_control(
			'testimonial_rating',
			[
				'label' => esc_html__( 'Rating', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'testimonial_rating_scale',
			[
				'label' => esc_html__( 'Scale', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 10,
				'condition' => [
					'testimonial_rating' => 'yes',
				],
			]
		);

		$this->add_control_testimonial_rating_score();

		$this->add_control(
			'testimonial_rating_style',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style_1' => 'Style 1',
					'style_2' => 'Style 2',
				],
				'default' => 'style_2',
				'render_type' => 'template',
				'prefix_class' => 'crt-testimonial-rating-',
				'condition' => [
					'testimonial_rating' => 'yes',
				],
			]
		);

		$this->add_control(
			'testimonial_unmarked_rating_style',
			[
				'label' => esc_html__( 'Unmarked Style', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'solid' => [
						'title' => esc_html__( 'Solid', 'crt-manage' ),
						'icon' => 'fas fa-star',
					],
					'outline' => [
						'title' => esc_html__( 'Outline', 'crt-manage' ),
						'icon' => 'far fa-star',
					],
				],
				'default' => 'outline',
				'condition' => [
					'testimonial_rating' => 'yes',
					'testimonial_rating_score' => '',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'testimonial', [
			'Add Unlimited Testimonials',
			'Columns (Carousel) 1,2,3,4,5,6',
			'Advanced Social Media Icon options',
			'Advanced Rating Styling options',
			'Unlimited Slides to Scroll option',
			'Autoplay options',
			'Advanced Navigation Positioning',
			'Advanced Pagination Positioning',
		] );
		
		// Styles
		// Section: General ----------
		$this->start_controls_section(
			'crt__section_style_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'general_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .crt-testimonial-item'
			]
		);

		$this->add_responsive_control(
			'general_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 50,
					'left' => 5,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'general_border',
				'label' => esc_html__( 'Border', 'crt-manage' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
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
				],
				'selector' => '{{WRAPPER}} .crt-testimonial-item',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'crt__section_style_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .crt-testimonial-content-inner'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .crt-testimonial-content-inner',
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 25,
					'right' => 25,
					'bottom' => 27,
					'left' => 25,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-testimonial-meta-position-left .crt-testimonial-meta' => 'padding-top: {{TOP}}{{UNIT}};',
					'{{WRAPPER}}.crt-testimonial-meta-position-right .crt-testimonial-meta' => 'padding-top: {{TOP}}{{UNIT}};',
					'{{WRAPPER}}.crt-testimonial-meta-position-top:not(.crt-testimonial-meta-align-center) .crt-testimonial-meta,
					 {{WRAPPER}}.crt-testimonial-meta-position-bottom:not(.crt-testimonial-meta-align-center) .crt-testimonial-meta' => 'padding: 0 {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_type',
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
					'{{WRAPPER}} .crt-testimonial-content-inner' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_width',
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
					'{{WRAPPER}} .crt-testimonial-content-inner' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-testimonial-meta-position-left .crt-testimonial-content-inner:before' => 'left: calc(-22px - {{left}}{{UNIT}});',
					'{{WRAPPER}}.crt-testimonial-meta-position-right .crt-testimonial-content-inner:before' => 'right: calc(-22px - {{right}}{{UNIT}});',
					'{{WRAPPER}}.crt-testimonial-meta-position-top .crt-testimonial-content-inner:before' => 'top: calc(-15px - {{top}}{{UNIT}});',
					'{{WRAPPER}}.crt-testimonial-meta-position-bottom .crt-testimonial-content-inner:before' => 'bottom: calc(-15px - {{bottom}}{{UNIT}});',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_border_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-content-inner' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'content_border_radius',
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
					'{{WRAPPER}} .crt-testimonial-content-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		// Triangle
		$this->add_control(
			'content_triangle',
			[
				'label' => esc_html__( 'Triangle', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,				
				'default' => 'yes',
				'prefix_class' => 'crt-testimonial-triangle-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'triangle_color',
			[
				'label' => esc_html__( 'Triangle Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f7f7f7',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-content-inner:before' => 'border-top-color: {{VALUE}};',
				],
				'condition' => [
					'content_triangle' => 'yes',
				],
			]
		);

		// Icon
		$this->add_control(
			'icon_section',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c1c1c1',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-testimonial-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Font Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-testimonial-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'icon_align',
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
					'{{WRAPPER}} .crt-testimonial-icon' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Title
		$this->add_control(
			'title_section',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .crt-testimonial-title',
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
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'title_align',
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
					'{{WRAPPER}} .crt-testimonial-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Content
		$this->add_control(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#444444',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .crt-testimonial-content',
			]
		);

		$this->add_responsive_control(
			'content_distance',
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'content_align',
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
					'{{WRAPPER}} .crt-testimonial-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Date
		$this->add_control(
			'date_section',
			[
				'label' => esc_html__( 'Date', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c1c1c1',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-date' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'selector' => '{{WRAPPER}} .crt-testimonial-date',
			]
		);

		$this->add_control(
			'date_align',
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
					'{{WRAPPER}} .crt-testimonial-date' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Rating
		$this->add_control(
			'rating_section',
			[
				'label' => esc_html__( 'Rating', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'rating_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Position', 'crt-manage' ),
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'crt-manage' ),
					'bottom' => esc_html__( 'Bottom', 'crt-manage' ),
				],
			]
		);

		$this->add_control(
			'rating_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFD726',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-rating i:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-rating-icon .crt-rating-marked svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_unmarked_color',
			[
				'label' => esc_html__( 'Unmarked Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d8d8d8',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-rating i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-testimonial-rating svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_score_color',
			[
				'label' => esc_html__( 'Score Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd726',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-rating span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-testimonial-rating' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'rating_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
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
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-testimonial-rating svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'rating_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-rating i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-testimonial-rating svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-testimonial-rating span' => 'margin-left: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_responsive_control(
			'rating_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'rating_color_typography',
				'selector' => '{{WRAPPER}} .crt-testimonial-rating span',
			]
		);

		$this->end_controls_section();	
		
		// Styles
		// Section: Meta -------------
		$this->start_controls_section(
			'section_style_meta',
			[
				'label' => esc_html__( 'Meta', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'meta_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Position', 'crt-manage' ),
				'default' => 'bottom',
				'options' => [
					'top' => esc_html__( 'Top', 'crt-manage' ),
					'left' => esc_html__( 'Left', 'crt-manage' ),
					'right' => esc_html__( 'Right', 'crt-manage' ),
					'bottom' => esc_html__( 'Bottom', 'crt-manage' ),
					'extra' => esc_html__( 'Extra', 'crt-manage' ),
				],
				'prefix_class' => 'crt-testimonial-meta-position-',
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'meta_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'size_units' => [ 'px' ],
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
					'{{WRAPPER}}.crt-testimonial-meta-position-top .crt-testimonial-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-testimonial-meta-position-left .crt-testimonial-meta' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-testimonial-meta-position-right .crt-testimonial-meta' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-testimonial-meta-position-bottom .crt-testimonial-meta' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-testimonial-meta-position-extra .crt-testimonial-content-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_align',
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
				'prefix_class' => 'crt-testimonial-meta-align-',
				'separator' => 'before',
			]
		);

		// Image
		$this->add_control(
			'image_section',
			[
				'label' => esc_html__( 'Image', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_position',
			[
				'label' => esc_html__( 'Position', 'crt-manage' ),
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
                'prefix_class'	=> 'crt-testimonial-image-position-',
                'condition' => [
                	'meta_position!' => 'extra'
                ]
			]
		);

		$this->add_responsive_control(
			'image_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 16,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 65,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-image img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-testimonial-meta-position-top.crt-testimonial-meta-align-left .crt-testimonial-content-inner:before,
					{{WRAPPER}}.crt-testimonial-meta-position-bottom.crt-testimonial-meta-align-left .crt-testimonial-content-inner:before' => 'left: calc( {{content_padding.LEFT}}px + {{content_border_width.LEFT}}px + ({{SIZE}}px / 2) );',
					'{{WRAPPER}}.crt-testimonial-meta-position-top.crt-testimonial-meta-align-right .crt-testimonial-content-inner:before,
					{{WRAPPER}}.crt-testimonial-meta-position-bottom.crt-testimonial-meta-align-right .crt-testimonial-content-inner:before' => 'right: calc( {{content_padding.RIGHT}}px + {{content_border_width.RIGHT}}px + ({{SIZE}}px / 2) );',
					'{{WRAPPER}}.crt-testimonial-meta-position-left .crt-testimonial-content-inner:before,
					{{WRAPPER}}.crt-testimonial-meta-position-right .crt-testimonial-content-inner:before' => 'top: calc( {{content_padding.TOP}}px + {{content_border_width.TOP}}px + ({{SIZE}}px / 2) );',
				],
			]
		);

		$this->add_responsive_control(
			'image_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-testimonial-image-position-right .crt-testimonial-image' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-testimonial-image-position-left .crt-testimonial-image' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-testimonial-image-position-center .crt-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Border', 'crt-manage' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
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
				],
				'selector' => '{{WRAPPER}} .crt-testimonial-image img',
			]
		);

		$this->add_control(
			'image_border_radius',
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
					'{{WRAPPER}} .crt-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Name
		$this->add_control(
			'name_section',
			[
				'label' => esc_html__( 'Name', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .crt-testimonial-name',
			]
		);

		$this->add_responsive_control(
			'name_distance_top',
			[
				'label' => esc_html__( 'Top Distance', 'crt-manage' ),
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-name' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_position' => [ 'left', 'right' ],
				],
			]
		);

		$this->add_responsive_control(
			'name_distance_bottom',
			[
				'label' => esc_html__( 'Bottom Distance', 'crt-manage' ),
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Job
		$this->add_control(
			'job_section',
			[
				'label' => esc_html__( 'Job', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'job_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b7b7b7',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-job' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'job_typography',
				'selector' => '{{WRAPPER}} .crt-testimonial-job',
			]
		);

		$this->add_responsive_control(
			'job_distance',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-job' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		// Image
		$this->add_control(
			'logo_section',
			[
				'label' => esc_html__( 'Logo', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'logo_width',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 65,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-logo-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-logo-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);
		
		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Social Media -----
		$this->start_controls_section(
			'crt__section_style_social_media',
			[
				'label' => esc_html__( 'Social Media', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_social_style' );

		$this->start_controls_tab(
			'tab_social_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'social_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-social' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#919191',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-social' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b5b5b5',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-social' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_social_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'social_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-social:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#444444',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-social:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b5b5b5',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-social:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'social_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-social' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'social_box_size',
			[
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-social' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-testimonial-social i' => 'line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-testimonial-social svg' => 'line-height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'social_size',
			[
				'label' => esc_html__( 'Font Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 9,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-social' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_gutter',
			[
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
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
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-social' => 'margin-right: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'social_border_type',
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
					'{{WRAPPER}} .crt-testimonial-social' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'social_border_width',
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
					'{{WRAPPER}} .crt-testimonial-social' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'social_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'social_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-social' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
            'testimonial_style_social_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'social_box_shadow',
				'selector' => '{{WRAPPER}} .crt-testimonial-social',
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Navigation -------
		$this->start_controls_section(
			'crt__section_style_nav',
			[
				'label' => esc_html__( 'Navigation', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_nav_style' );

		$this->start_controls_tab(
			'tab_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'nav_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-testimonial-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-arrow:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-testimonial-arrow:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-arrow' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-testimonial-arrow svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'nav_font_size',
			[
				'label' => esc_html__( 'Font Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-testimonial-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'nav_size',
			[
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 21,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'nav_border_type',
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
					'{{WRAPPER}} .crt-testimonial-arrow' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'nav_border_width',
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
					'{{WRAPPER}} .crt-testimonial-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_stack_nav_position();

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Pagination -------
		$this->start_controls_section(
			'section_style_dots',
			[
				'label' => esc_html__( 'Pagination', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_dots' );

		$this->start_controls_tab(
			'tab_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'dots_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d1d1d1',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-dot' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dots_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_hover',
			[
				'label' => esc_html__( 'Active', 'crt-manage' ),
			]
		);

		$this->add_control(
			'dots_active_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-dots .slick-active .crt-testimonial-dot' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'dots_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-dots .slick-active .crt-testimonial-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'dots_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'dots_border_type',
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
					'{{WRAPPER}} .crt-testimonial-dot' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'dots_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-dot' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'dots_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'dots_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
					'unit'		=> '%',
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'dots_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],							
				'default' => [
					'unit' => 'px',
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-dot' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control_dots_hr();
		
		$this->add_responsive_control(
			'dots_vr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position', 'crt-manage' ),
				'size_units' => [ '%','px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 1200,
					],
				],											
				'default' => [
					'unit' => '%',
					'size' => 96,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-testimonial-dots' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
		
	}

	public function render_testimonial_image( $item ) {
		$settings = $this->get_settings();
		$image_src = Group_Control_Image_Size::get_attachment_image_src( $item['testimonial_image']['id'], 'testimonial_image_size', $settings );

		if ( ! $image_src ) {
			$image_src = $item['testimonial_image']['url'];
		}

		?>

		<?php if ( ! empty( $item['testimonial_image']['url'] ) ) : ?>
			<div class="crt-testimonial-image">
				<img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $item['testimonial_author'] ); ?>">
			</div>
		<?php endif; ?>

	<?php
	}

	public function render_pro_element_social_media( $item, $item_count ) {}	

	public function render_testimonial_meta( $item, $item_count ) {
		$logo_element = 'div'; ?>
		
		<div class="crt-testimonial-meta-content-wrap">
			<?php if ( ! empty( $item['testimonial_author'] ) ) : ?>
				<div class="crt-testimonial-name"><?php echo wp_kses_post( $item['testimonial_author'] ); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $item['testimonial_job'] ) ) : ?>
				<div class="crt-testimonial-job"><?php echo wp_kses_post( $item['testimonial_job'] ); ?></div>
			<?php endif; ?>

			<?php
			if ( ! empty( $item['testimonial_logo_image']['url'] ) ) {
				
				$this->add_render_attribute( 'logo_attribute'. $item_count, 'class', 'crt-testimonial-logo-image elementor-clearfix' );

				if ( ! empty( $item['testimonial_logo_url']['url'] ) ) {

					$logo_element = 'a';

					$this->add_render_attribute( 'logo_attribute'. $item_count, 'href', esc_url( $item['testimonial_logo_url']['url'] ) );

					if ( $item['testimonial_logo_url']['is_external'] ) {
						$this->add_render_attribute( 'logo_attribute'. $item_count, 'target', '_blank' );
					}

					if ( $item['testimonial_logo_url']['nofollow'] ) {
						$this->add_render_attribute( 'logo_attribute'. $item_count, 'nofollow', '' );
					}

				}

				echo '<'. esc_attr( $logo_element ) .' '. $this->get_render_attribute_string( 'logo_attribute'. $item_count ) .'>';
					echo '<img src="'. esc_url(  $item['testimonial_logo_image']['url'] ) .'" alt="'. esc_attr( $item['testimonial_author'] ) .'">';
				echo '</'. esc_attr( $logo_element ) .'>';

			}

			$this->render_pro_element_social_media( $item, $item_count );

			?>

		</div>
		<?php
	}


	public function crt_testimonial_content( $item ) {
		$settings = $this->get_settings(); ?>

		<div class="crt-testimonial-content-wrap">
			<div class="crt-testimonial-content-inner">
			<?php if ( $settings['testimonial_icon'] !== 'none' && $settings['testimonial_icon_position'] === 'top' ) : ?>
				<div class="crt-testimonial-icon">
					<?php echo Utilities::get_crt_icon( $settings['testimonial_icon'], '' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $item['testimonial_title'] ) ) : ?>
				<div class="crt-testimonial-title"><?php echo wp_kses_post( $item['testimonial_title'] ); ?></div>
			<?php endif; ?>

			<?php if ( $settings['rating_position'] === 'top' ) : ?>
				<?php $this->render_testimonial_rating( $item ); ?>
			<?php endif; ?>

			<?php if ( ! empty( $item['testimonial_content'] ) ) : ?>
				<div class="crt-testimonial-content">
					<?php if ( $settings['testimonial_icon'] !== 'none' && $settings['testimonial_icon_position'] === 'inner' ) : ?>
					<div class="crt-testimonial-icon">	
						<?php echo Utilities::get_crt_icon( $settings['testimonial_icon'], '' ); ?>
					</div>
					<?php endif; ?>

					<p><?php echo wp_kses_post($item['testimonial_content']); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $settings['rating_position'] === 'bottom' ) : ?>
				<?php $this->render_testimonial_rating( $item ); ?>
			<?php endif; ?>

			<?php if ( ! empty( $item['testimonial_date'] ) ) : ?>
				<div class="crt-testimonial-date"><?php echo esc_html( $item['testimonial_date'] ); ?></div>
			<?php endif; ?>
			</div>
		</div>

	    <?php
	}

    public function render_rating_icon( $class ) {
        $settings = $this->get_settings();
        ?>

        <span class="crt-rating-icon <?php echo esc_attr($class); ?>">
            <span class="crt-rating-marked">
                <?php \Elementor\Icons_Manager::render_icon( [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] ); ?>
            </span>

            <span class="crt-rating-unmarked">
                <?php 
                    if ( 'outline' === $settings['testimonial_unmarked_rating_style'] ) {
                        \Elementor\Icons_Manager::render_icon( [ 'value' => 'far fa-star', 'library' => 'fa-regular' ], [ 'aria-hidden' => 'true' ] );
                    } else {
                        \Elementor\Icons_Manager::render_icon( [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] );
                    }
                 ?>
            </span>
        </span>

        <?php
    }

	public function render_testimonial_rating( $item ) {
		$settings = $this->get_settings();
		$rating_amount = $item['testimonial_rating_amount'];
		$round_rating = (int)$rating_amount;
		$rating_icon = '&#xE934;';
        $rating_icon_entity = '&#9733;';

		if ( 'style_1' === $settings['testimonial_rating_style'] ) {
			if ( 'outline' === $settings['testimonial_unmarked_rating_style'] ) {
				$rating_icon = '&#xE933;';
			}
		} elseif ( 'style_2' === $settings['testimonial_rating_style'] ) {
			$rating_icon = '&#9733;';

			if ( 'outline' === $settings['testimonial_unmarked_rating_style'] ) {
				$rating_icon = '&#9734;';
			}
		}

        if ( 'outline' === $settings['testimonial_unmarked_rating_style'] ) {
            $rating_icon_entity = '&#9734;';
        }

		if ( 'yes' === $settings['testimonial_rating'] && ! empty( $rating_amount ) ) : ?>	

			<div class="crt-testimonial-rating">
			<?php for( $i = 1; $i <= $settings['testimonial_rating_scale']; $i++ ) : ?>


                <?php if ( \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) ) : ?>
                    <?php if ( 'style_1' === $settings['testimonial_rating_style'] ) : ?>

                        <?php if ( $i <= $rating_amount ) : ?>
                            <?php $this->render_rating_icon( 'crt-rating-icon-full' ); ?>
                        <?php elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) : ?>
                            <?php $this->render_rating_icon( 'crt-rating-icon-'. (( $rating_amount - $round_rating ) * 10) ); ?>
                        <?php else : ?>
                            <?php $this->render_rating_icon( 'crt-rating-icon-empty' ); ?>
                        <?php endif; ?>

                    <?php else: ?>

                        <?php if ( $i <= $rating_amount ) : ?>
                            <i class="crt-rating-icon-full"><?php echo esc_html($rating_icon_entity); ?></i>
                        <?php elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) : ?>
                            <i class="crt-rating-icon-<?php echo esc_attr(( $rating_amount - $round_rating ) * 10); ?>"><?php echo esc_html($rating_icon_entity); ?></i>
                        <?php else : ?>
                            <i class="crt-rating-icon-empty"><?php echo esc_html($rating_icon_entity); ?></i>
                        <?php endif; ?>

                    <?php endif; ?>
                <?php else : ?>
                    <?php if ( $i <= $rating_amount ) : ?>
                        <i class="crt-rating-icon-full"><?php echo esc_html($rating_icon); ?></i>
                    <?php elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) : ?>
                        <i class="crt-rating-icon-<?php echo esc_attr(( $rating_amount - $round_rating ) * 10); ?>"><?php echo esc_html($rating_icon); ?></i>
                    <?php else : ?>
                        <i class="crt-rating-icon-empty"><?php echo esc_html($rating_icon); ?></i>
                    <?php endif; ?>
                <?php endif; ?>


	     	<?php endfor; ?>

	     	<?php $this->render_pro_element_testimonial_score($rating_amount); ?>
			</div>

	<?php
		endif;
	}

	public function render_pro_element_testimonial_score($rating_amount) {}

	protected function render() {	
		$settings = $this->get_settings();
		$item_html = '';
		$item_count = 0;

		if ( empty( $settings['testimonial_items'] ) ) {
			return;
		}
		
		$is_rtl = is_rtl();
		$direction = $is_rtl ? 'rtl' : 'ltr';


		$options = [
			'rtl' => $is_rtl,
			'infinite' => ( $settings['testimonial_loop'] === 'yes' ),
			'speed' => absint( $settings['testimonial_effect_duration'] * 1000 ),
			'arrows' => true,
			'dots' => true,
			'autoplay' => ( $settings['testimonial_autoplay'] === 'yes' ),
			'autoplaySpeed' => absint( $settings['testimonial_autoplay_duration'] * 1000 ),
			'pauseOnHover' => $settings['testimonial_pause_on_hover'],
			'prevArrow' => '#crt-testimonial-prev-'. $this->get_id(),
			'nextArrow' => '#crt-testimonial-next-'. $this->get_id(),
			'sliderSlidesToScroll' => +$settings['testimonial_slides_to_scroll'],
		];

		$this->add_render_attribute( 'testimonial-caousel-attribute', [
			'class' => 'crt-testimonial-carousel',
			'dir' => esc_attr( $direction ),
			'data-slick' => wp_json_encode( $options ),
		] );

		?>
		<div class="crt-testimonial-carousel-wrap">
			
			<div <?php echo $this->get_render_attribute_string( 'testimonial-caousel-attribute' ); ?> data-slide-effect="<?php echo esc_attr($settings['testimonial_effect']); ?>">
					
					<?php foreach ( $settings['testimonial_items'] as $key => $item ) : ?>

						<div class="crt-testimonial-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?> elementor-clearfix">
							
							<div class="crt-testimonial-meta elementor-clearfix">
								<div class="crt-testimonial-meta-inner">
								<?php 
								$this->render_testimonial_image( $item );
								if (  $settings['meta_position'] !== 'extra' ) {
									$this->render_testimonial_meta( $item, $item_count );
								}
								?>
								</div>
							</div>

							<?php $this->crt_testimonial_content( $item ); ?>

							<?php if ( $settings['meta_position'] === 'extra' ) : ?>
								<div class="crt-testimonial-meta elementor-clearfix">
									<div class="crt-testimonial-meta-inner">
									<?php 
									if (  $settings['meta_position'] !== 'extra' ) {
										$this->render_testimonial_image( $item );
									}
									$this->render_testimonial_meta( $item, $item_count );
									?>
									</div>	
								</div>
							<?php endif; ?>

						</div>
						<?php
						$item_count++;
					endforeach;
					?>
			</div>

			<div class="crt-testimonial-controls">
				<div class="crt-testimonial-dots"></div>
			</div>

			<div class="crt-testimonial-arrow-container">
				<div class="crt-testimonial-prev-arrow crt-testimonial-arrow" id="<?php echo 'crt-testimonial-prev-'. esc_attr($this->get_id()); ?>">
					<?php echo Utilities::get_crt_icon( $settings['testimonial_nav_icon'], '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="crt-testimonial-next-arrow crt-testimonial-arrow" id="<?php echo 'crt-testimonial-next-'. esc_attr($this->get_id()); ?>">
					<?php echo Utilities::get_crt_icon( $settings['testimonial_nav_icon'], '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>

	<?php
	}
}