<?php
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
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

class CRT_Tabs extends Widget_Base {
		
	public function get_name() {
		return 'crt-tabs';
	}

	public function get_title() {
		return esc_html__( 'Tabs', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-tabs';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'vertical tabs', 'horizontal tabs', 'accordion' ];
	}

	public function get_style_depends() {
		return [ 'crt-animations-css' ];
	}

    public function get_script_depends() {
        return [ 'crt-tabs' ];
    }

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com/contact';
    }

	public function add_repeater_args_tab_custom_color() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_tab_content_type() {
		return [
            'label' => esc_html__( 'Content Type', 'crt-manage' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'editor',
            'options' => [
                'editor' => esc_html__( 'Editor', 'crt-manage' ),
                'acf' => esc_html__( 'Custom Field', 'crt-manage' ),
                'template' => esc_html__( 'Elementor Template', 'crt-manage' ),
            ],
			'separator' => 'before',
        ];
	}

    public function add_control_tabs_hr_position() {
        $this->add_control(
            'tabs_hr_position',
            [
                'label' => esc_html__( 'Horizontal Align', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'justify',
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
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Stretch', 'crt-manage' ),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                ],
                'prefix_class' => 'crt-tabs-hr-position-',
                'render_type' => 'template',
                'condition' => [
                    'tabs_position' => 'above',
                ],
            ]
        );
    }

    public function add_section_settings() {
        // CSS Selectors
        $css_selector = [
            'general' => '> .elementor-widget-container > .crt-tabs',
            'control_list' => '> .elementor-widget-container > .crt-tabs > .crt-tabs-wrap > .crt-tab',
            'content_wrap' => '> .elementor-widget-container > .crt-tabs > .crt-tabs-content-wrap',
            'content_list' => '> .elementor-widget-container > .crt-tabs > .crt-tabs-content-wrap > .crt-tab-content',
            'control_icon' => '.crt-tab-icon',
            'control_image' => '.crt-tab-image',
        ];

        if ( ! $this->has_widget_inner_wrapper() ) {
            $css_selector['general'] = '> .crt-tabs';
            $css_selector['control_list'] = '> .crt-tabs > .crt-tabs-wrap > .crt-tab';
            $css_selector['content_wrap'] = '> .crt-tabs > .crt-tabs-content-wrap';
            $css_selector['content_list'] = '> .crt-tabs > .crt-tabs-content-wrap > .crt-tab-content';
        }

        $this->start_controls_section(
            'section_settings',
            [
                'label' => esc_html__( 'Settings', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'tabs_trigger',
            [
                'label' => esc_html__( 'Trigger', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'click',
                'options' => [
                    'click' => esc_html__( 'Click', 'crt-manage' ),
                    'hover' => esc_html__( 'Hover', 'crt-manage' ),
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'active_tab',
            [
                'label' => esc_html__( 'Active Tab Index', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'label_block' => false,
                'min' => 1,
                'default' => 1,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'content_animation',
            [
                'label' => esc_html__( 'Content Animation', 'crt-manage' ),
                'type' => 'crt-animations-alt',
                'default' => 'fade-in',
            ]
        );

        $this->add_control(
            'content_anim_size',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Animation Size', 'crt-manage' ),
                'default' => 'large',
                'options' => [
                    'small' => esc_html__( 'Small', 'crt-manage' ),
                    'medium' => esc_html__( 'Medium', 'crt-manage' ),
                    'large' => esc_html__( 'Large', 'crt-manage' ),
                ],
                'condition' => [
                    'content_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'content_anim_duration',
            [
                'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} '. $css_selector['content_list']. ' > .crt-tab-content-inner' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
                    '{{WRAPPER}}.crt-tabs-triangle-type-inner '. $css_selector['control_list'] .':before' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
                ],
                'condition' => [
                    'content_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'autoplay_duration',
            [
                'label' => esc_html__( 'Autoplay Speed', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 0,
                'max' => 15,
                'step' => 0.1,
                'frontend_available' => true,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

    protected function register_controls() {

		// CSS Selectors
		$css_selector = [
			'general' => '> .elementor-widget-container > .crt-tabs',
			'control_list' => '> .elementor-widget-container > .crt-tabs > .crt-tabs-wrap > .crt-tab',
			'content_wrap' => '> .elementor-widget-container > .crt-tabs > .crt-tabs-content-wrap',
			'content_list' => '> .elementor-widget-container > .crt-tabs > .crt-tabs-content-wrap > .crt-tab-content',
			'control_icon' => '.crt-tab-icon',
			'control_image' => '.crt-tab-image',
		];

		if ( ! $this->has_widget_inner_wrapper() ) {
			$css_selector['general'] = '> .crt-tabs';
			$css_selector['control_list'] = '> .crt-tabs > .crt-tabs-wrap > .crt-tab';
			$css_selector['content_wrap'] = '> .crt-tabs > .crt-tabs-content-wrap';
			$css_selector['content_list'] = '> .crt-tabs > .crt-tabs-content-wrap > .crt-tab-content';
		}
	
		// Section: Tabs Items -------
		$this->start_controls_section(
			'section_tabs',
			[
				'label' => esc_html__( 'Tabs', 'crt-manage' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__( 'Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Tab 1',
			]
		);

		$repeater->add_control(
            'tab_icon_type',
            [
                'label' => esc_html__( 'Icon Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'icon' => esc_html__( 'Icon', 'crt-manage' ),
                    'image' => esc_html__( 'Image', 'crt-manage' ),
                ],
				'separator' => 'before',
            ]
        );

        $repeater->add_control(
			'tab_image',
			[
				'label' => esc_html__( 'Upload Image', 'crt-manage' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'tab_icon_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'tab_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-star',
					'library' => 'fa-regular',
				],
				'condition' => [
					'tab_icon_type' => 'icon',
				],
			]
		);

		$repeater->add_control(  'tab_content_type', $this->add_repeater_args_tab_content_type() );

		// Upgrade to Pro Notice
        $repeater->add_control(
            'tab_custom_field',
            [
                'label' => esc_html__( 'Select Custom Field', 'crt-manage' ),
                'type' => 'crt-ajax-select2',
                'label_block' => true,
                'default' => 'default',
                'description' => '<strong>Note:</strong> This option only accepts String(Text) or Numeric Custom Field Values.',
                'options' => 'ajaxselect2/get_custom_meta_keys',
                'condition' => [
                    'tab_content_type' => 'acf'
                ],
            ]
        );

		$repeater->add_control(
			'tab_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'type' => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'crt-manage' ),
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis. Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur.',
				'condition' => [
					'tab_content_type' => 'editor',
				],
			]
		);

		$repeater->add_control(
			'select_template' ,
			[
				'label'	=> esc_html__( 'Select Template', 'crt-manage' ),
				'type' => 'crt-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				'condition' => [
					'tab_content_type' => 'template',
				],
			]
		);

		$repeater->add_control( 'tab_custom_color', $this->add_repeater_args_tab_custom_color() );

		$repeater->add_control(
			'tab_custom_text_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'{{CURRENT_ITEM}} .crt-tab-title' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} '. $css_selector['control_list'] .'{{CURRENT_ITEM}} .crt-tab-icon' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} '. $css_selector['content_list'] .'{{CURRENT_ITEM}}' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} '. $css_selector['control_list'] .'{{CURRENT_ITEM}}:before' => 'display: none !important;',
				],
				'condition' => [
					'tab_custom_color' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'tab_custom_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#61ce70',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'{{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} '. $css_selector['content_list'] .'{{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important;',
				],
				'condition' => [
					'tab_custom_color' => 'yes',
				],
			]
		);

		$this->add_control(
			'tabs',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => 'Tab 1',
						'tab_custom_bg_color' => '#61ce70',
					],
					[
						'tab_title' => 'Tab 2',
						'tab_custom_bg_color' => '#f41f46',
					],
					[
						'tab_title' => 'Tab 3',
						'tab_custom_bg_color' => '#1e36ea',
					]
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->add_control(
			'tabs_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Label Position', 'crt-manage' ),
				'default' => 'above',
				'options' => [
					'above' => esc_html__( 'Default', 'crt-manage' ),
					'left' => esc_html__( 'Left', 'crt-manage' ),
					'right' => esc_html__( 'Right', 'crt-manage' ),
				],
				'prefix_class' => 'crt-tabs-position-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tabs_invert_responsive',
			[
				'label' => esc_html__( 'Invert on Mobile', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'crt-tabs-responsive-',
			]
		);

		$this->add_control_tabs_hr_position();

		$this->add_control(
			'tabs_vr_position',
			[
				'label' => esc_html__( 'Vertical Align', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'top',
                'options' => [
                    'top' => [
						'title' => esc_html__( 'Top', 'crt-manage' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'crt-manage' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'crt-manage' ),
						'icon' => 'eicon-v-align-bottom',
					],
                ],
                'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['general'] => '-webkit-align-items: {{VALUE}};align-items: {{VALUE}};',
				],
				'condition' => [
					'tabs_position!' => 'above',
				],
			]
		);

		$this->add_control(
			'text_align',
			[
				'label' => esc_html__( 'Label Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
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
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}}.crt-tabs-icon-position-left '. $css_selector['control_list'] => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
					'{{WRAPPER}}.crt-tabs-icon-position-center '. $css_selector['control_list'] => '-webkit-align-items: {{VALUE}};align-items: {{VALUE}};',
					'{{WRAPPER}}.crt-tabs-icon-position-right '. $css_selector['control_list'] => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tabs_width',
			[
				'label' => esc_html__( 'Label Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 600,
					],
					'%' => [
						'min' => 10,
						'max' => 100
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'tabs_icon_section',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tabs_icon_position',
			[
				'label' => esc_html__( 'Position', 'crt-manage' ),
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
					],
				],
				'prefix_class' => 'crt-tabs-icon-position-',
			]
		);

		$this->add_responsive_control(
			'tabs_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-tabs-icon-position-left '. $css_selector['control_list']. ' '. $css_selector['control_icon'] => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-tabs-icon-position-right '. $css_selector['control_list']. ' '. $css_selector['control_icon'] => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-tabs-icon-position-center '. $css_selector['control_list']. ' '. $css_selector['control_icon'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-tabs-icon-position-left '. $css_selector['control_list']. ' '. $css_selector['control_image'] => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-tabs-icon-position-right '. $css_selector['control_list']. ' '. $css_selector['control_image'] => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-tabs-icon-position-center '. $css_selector['control_list']. ' '. $css_selector['control_image'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'tabs_image_size',
				'default' => 'full',
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->add_section_settings();

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles
		// Section: Tabs ------------
		$this->start_controls_section(
			'section_style_tabs',
			[
				'label' => esc_html__( 'Labels', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tab_style' );

		$this->start_controls_tab(
			'tab_normal_style',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'tab_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .' .crt-tab-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .' '. $css_selector['control_icon'] => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_box_shadow',
				'selector' => '{{WRAPPER}} '. $css_selector['control_list'],
			]
		);

		$this->add_control(
			'tab_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'selector' => '{{WRAPPER}} '. $css_selector['control_list'] .' .crt-tab-title',
			]
		);

		$this->add_responsive_control(
			'tab_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .' .crt-tab-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_list'] .' .crt-tab-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_list'] .' .crt-tab-image' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_border_type',
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
					'{{WRAPPER}} '. $css_selector['control_list'] => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 0,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tab_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'tab_border_radius',
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
					'{{WRAPPER}} '. $css_selector['control_list'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hover_style',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'tab_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover .crt-tab-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_hover_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover .crt-tab-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_hover_box_shadow',
				'selector' => '{{WRAPPER}} '. $css_selector['control_list'] .':hover',
			]
		);

		$this->add_control(
			'tab_hover_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_hover_typography',
				'selector' => '{{WRAPPER}} '. $css_selector['control_list'] .':hover .crt-tab-title',
			]
		);

		$this->add_responsive_control(
			'tab_hover_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover .crt-tab-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover .crt-tab-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover .crt-tab-image' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_hover_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_hover_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_hover_border_type',
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
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_hover_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 0,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tab_hover_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'tab_hover_border_radius',
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
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_active_style',
			[
				'label' => esc_html__( 'Active', 'crt-manage' ),
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active .crt-tab-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_active_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active .crt-tab-icon' => 'color: {{VALUE}}',
				],
			]
		);
	
		$this->add_control(
			'tab_active_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-tabs-position-above.crt-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => 'border-top-color: {{VALUE}}',
					// '{{WRAPPER}}.crt-tabs-position-right.crt-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => 'border-right-color: {{VALUE}}',
					// '{{WRAPPER}}.crt-tabs-position-left.crt-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => 'border-right-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e5e5e5',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_active_box_shadow',
				'selector' => '{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active',
			]
		);

		$this->add_control(
			'tab_active_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_active_typography',
				'selector' => '{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active .crt-tab-title',
			]
		);

		$this->add_responsive_control(
			'tab_active_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active .crt-tab-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active .crt-tab-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active .crt-tab-image' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_active_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_active_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => -1,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_active_border_type',
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
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_active_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 0,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tab_active_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'tab_active_border_radius',
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
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-tab-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'tab_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}}.crt-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['content_list'] => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['content_wrap'] => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-tabs-position-above.crt-tabs-triangle-type-inner '. $css_selector['control_list'] .':before' => 'border-top-color: {{VALUE}}',
					'{{WRAPPER}}.crt-tabs-position-right.crt-tabs-triangle-type-inner '. $css_selector['control_list'] .':before' => 'border-right-color: {{VALUE}}',
					'{{WRAPPER}}.crt-tabs-position-left.crt-tabs-triangle-type-inner '. $css_selector['control_list'] .':before' => 'border-right-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} '. $css_selector['content_wrap'],
			]
		);

		$this->add_control(
	        'content_box_shadow_divider',
	        [
	            'type' => Controls_Manager::DIVIDER,
	            'style' => 'thick',
	        ]
	    );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} '. $css_selector['content_list'] .', {{WRAPPER}} '. $css_selector['content_list'] .' ul',
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
					'bottom' => 25,
					'left' => 25,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['content_list'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['content_wrap'] => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_border_width',
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
					'{{WRAPPER}} '. $css_selector['content_wrap'] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['content_wrap'] => 'border-color: {{VALUE}}',
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
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['content_wrap'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->start_controls_section(
			'section_style_triangle',
			[
				'label' => esc_html__( 'Triangle', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tab_triangle',
			[
				'label' => esc_html__( 'Triangle', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,				
				'default' => 'yes',
				'prefix_class' => 'crt-tabs-triangle-',
				'separator' => 'before',
			]
		);

		$this->add_control(
            'tab_triangle_type',
            [
                'label' => esc_html__( 'Triangle Points to', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'outer',
                'options' => [
                    'inner' => esc_html__( 'Tab', 'crt-manage' ),
                    'outer' => esc_html__( 'Content', 'crt-manage' ),
                ],
				'prefix_class' => 'crt-tabs-triangle-type-',
				'render_type' => 'template',
				'condition' => [
					'tab_triangle' => 'yes',
				],
            ]
        );

		$this->add_responsive_control(
			'tab_triangle_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-tabs-position-above.crt-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => 'bottom: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-tabs-position-right.crt-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => 'left: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-tabs-position-left.crt-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => 'right: -{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'tab_triangle' => 'yes',
				],
			]
		);

		$this->end_controls_section();

	}

	public function crt_tabs_template( $id ) {
		if ( empty( $id ) ) {
			return '';
		}

		if ( defined('ICL_LANGUAGE_CODE') ) {
			$default_language_code = apply_filters('wpml_default_language', null);

			if ( ICL_LANGUAGE_CODE !== $default_language_code ) {
				$id = icl_object_id($id, 'elementor_library', false, ICL_LANGUAGE_CODE);
			}
		}

		$edit_link = '<span class="crt-template-edit-btn" data-permalink="'. esc_url(get_permalink( $id )) .'">Edit Template</span>';
		
		// Add CSS in Editor
		$type = get_post_meta(get_the_ID(), '_crt_template_type', true) || get_post_meta($id, '_elementor_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;
        return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

	protected function render() {
		$settings = $this->get_settings();

//		if ( !defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code() ) {
//			$settings['active_tab'] = 1;
//			$settings['tabs_trigger'] = 'click';
//			$settings['autoplay'] = '';
//			$settings['autoplay_duration'] = 0;
//			$settings['content_animation'] = 'fade-in';
//			$settings['content_anim_size'] = 'large';
//		}

		$tabs = $this->get_settings_for_display( 'tabs' );
		$id_int = substr( $this->get_id_int(), 0, 3 );

		$tabs_options = [
			'activeTab' 		=> $settings['active_tab'],
			'trigger' 			=>  $settings['tabs_trigger'],
			'autoplay' 			=> isset($settings['autoplay']) ? $settings['autoplay'] : '',
			'autoplaySpeed'		=> absint( $settings['autoplay_duration'] * 1000 ),
		];

		$this->add_render_attribute( 'tabs-attribute', [
			'class' => 'crt-tabs',
			'data-options' => wp_json_encode( $tabs_options ),
		] );

		?>
		
		<div <?php echo $this->get_render_attribute_string( 'tabs-attribute' ); ?>>
			
			<div class="crt-tabs-wrap">
				<?php foreach ( $tabs as $index => $item ) :
				
//				if ( !defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code() ) {
//					$item['tab_content_type'] = ('pro-tmp' == $item['tab_content_type']) ? 'editor' : $item['tab_content_type'];
//
//					if ( $index === 3 ) {
//						break;
//					}
//				}

				$tab_count = $index + 1;
				$tab_setting_key = $this->get_repeater_setting_key( 'tab_control', 'tabs', $index );
				$tab_image_src = false;
		
				if ( isset($item['tab_image']['id']) ) {
					$tab_image_src = Group_Control_Image_Size::get_attachment_image_src( $item['tab_image']['id'], 'tabs_image_size', $settings );

					if ( ! $tab_image_src ) {
						$tab_image_src = $item['tab_image']['url'];
					}
				}

				$this->add_render_attribute( $tab_setting_key, [
					'id' => 'crt-tab-'. $id_int . $tab_count,
					'class' => [ 'crt-tab', 'elementor-repeater-item-'. $item['_id'] ],
					'data-tab' => $tab_count,
				] );

				?>

				<div <?php echo $this->get_render_attribute_string( $tab_setting_key ); ?>>
					
					<?php if ( '' !== $item['tab_title'] ) : ?>
					<div class="crt-tab-title"><?php echo esc_html($item['tab_title']); ?></div>
					<?php endif; ?>

					<?php if ( 'icon' === $item['tab_icon_type'] && '' !== $item['tab_icon']['value'] ) : ?>
					<div class="crt-tab-icon">
						<i class="<?php echo esc_attr( $item['tab_icon']['value'] ); ?>"></i>
					</div>
					<?php elseif ( 'image' === $item['tab_icon_type'] && $tab_image_src ) : ?>
					<div class="crt-tab-image">
						<img src="<?php echo esc_url( $tab_image_src ); ?>" >
					</div>
					<?php endif; ?>
				
				</div>

				<?php endforeach; ?>
			</div>

			<div class="crt-tabs-content-wrap">
				<?php foreach ( $tabs as $index => $item ) :

				$tab_count = $index + 1;

				$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );
				$this->add_render_attribute( $tab_content_setting_key, [
					'id' => 'crt-tab-content-'. $id_int . $tab_count,
					'class' => [ 'crt-tab-content', 'elementor-repeater-item-'. $item['_id'] ],
					'data-tab' => $tab_count,
				] );

				?>

				<div <?php echo $this->get_render_attribute_string( $tab_content_setting_key ); ?>>
					<?php 
					echo '<div class="crt-tab-content-inner elementor-clearfix crt-anim-size-'. esc_attr($settings['content_anim_size']) .' crt-overlay-'. esc_attr($settings['content_animation']) .'">';

						if ( 'template' === $item['tab_content_type'] ) {

							// Render Elementor Template
							echo ''. $this->crt_tabs_template( $item['select_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

						} elseif( 'editor' === $item['tab_content_type'] ) {

							echo wp_kses_post($item['tab_content']);

						} elseif( 'acf' === $item['tab_content_type'] ) {

							echo wp_kses_post(get_post_meta( get_the_ID(), $item['tab_custom_field'], true ));
						}

					echo '</div>';

					?>
				</div>

				<?php endforeach; ?>
			</div>

		</div>

		<?php
	}
}