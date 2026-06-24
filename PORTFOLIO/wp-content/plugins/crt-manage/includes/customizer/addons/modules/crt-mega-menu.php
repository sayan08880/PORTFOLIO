<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Mega_Menu extends Widget_Base {

	protected $nav_menu_index = 1;
	
	public function get_name() {
		return 'crt-mega-menu';
	}

	public function get_title() {
		return esc_html__( 'Mega Menu', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-nav-menu';
	}

	public function get_categories() {
        return [ 'crt_manage_header_elements'];
    }

	public function get_keywords() {
		return [ 'royal', 'nav menu', 'header', 'navigation menu', 'horizontal menu', 'horizontal navigation', 'vertical menu', 'vertical navigation', 'burger menu', 'hamburger menu', 'mobile menu', 'responsive menu' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_style_depends() {
		return [ 'crt-link-animations-css' ];
	}

    public function get_script_depends() {
        return [ $this->get_name() ];
    }

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-nav-menu-help-btn';
    		return 'https://crthemes.com/contact';
    }

	public function on_export( $element ) {
		unset( $element['settings']['menu'] );
		return $element;
	}

	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

    public function add_control_menu_layout() {
        $this->add_control(
            'menu_layout',
            [
                'label' => esc_html__( 'Menu Layout', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__( 'Horizontal', 'crt-manage' ),
                    'vertical' => esc_html__( 'Vertical', 'crt-manage' ),
                ],
                'frontend_available' => true,
            ]
        );
    }

    public function add_control_menu_items_pointer() {
        $this->add_control(
            'menu_items_pointer',
            [
                'label' => esc_html__( 'Hover Effect', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'underline',
                'options' => [
                    'none' => esc_html__( 'None', 'crt-manage' ),
                    'underline' => esc_html__( 'Underline', 'crt-manage' ),
                    'overline' => esc_html__( 'Overline', 'crt-manage' ),
                    'double-line' => esc_html__( 'Double Line', 'crt-manage' ),
                    'border' => esc_html__( 'Border', 'crt-manage' ),
                    'background' => esc_html__( 'Background', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-pointer-',
            ]
        );
    }

    public function add_control_pointer_animation_line() {
        $this->add_control(
            'pointer_animation_line',
            [
                'label' => esc_html__( 'Hover Animation', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'none' => 'None',
                    'fade' => 'Fade',
                    'slide' => 'Slide',
                    'grow' => 'Grow',
                    'drop' => 'Drop',
                ],
                'prefix_class' => 'crt-pointer-line-fx crt-pointer-fx-',
                'condition' => [
                    'menu_items_pointer' => [ 'underline', 'overline', 'double-line' ],
                ],
            ]
        );
    }

    public function add_control_pointer_animation_border() {
        $this->add_control(
            'pointer_animation_border',
            [
                'label' => esc_html__( 'Hover Animation', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'none' => 'None',
                    'fade' => 'Fade',
                    'grow' => 'Grow',
                    'shrink' => 'Shrink',
                ],
                'prefix_class' => 'crt-pointer-border-fx crt-pointer-fx-',
                'condition' => [
                    'menu_items_pointer' => 'border',
                ],
            ]
        );
    }

    public function add_control_pointer_animation_background() {
        $this->add_control(
            'pointer_animation_background',
            [
                'label' => esc_html__( 'Hover Animation', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'none' => 'None',
                    'fade' => 'Fade',
                    'grow' => 'Grow',
                    'shrink' => 'Shrink',
                    'sweep' => 'Sweep',
                    'skew' => 'Skew',
                ],
                'prefix_class' => 'crt-pointer-background-fx crt-pointer-fx-',
                'condition' => [
                    'menu_items_pointer' => 'background',
                ],
            ]
        );
    }

    public function add_control_menu_items_submenu_entrance() {
        $this->add_control(
            'menu_items_submenu_entrance',
            [
                'label' => esc_html__( 'Sub Menu Entrance', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'fade' => esc_html__( 'Fade', 'crt-manage' ),
                    'move-up' => esc_html__( 'Move Up', 'crt-manage' ),
                    'move-down' => esc_html__( 'Move Down', 'crt-manage' ),
                    'move-left' => esc_html__( 'Move Left (VR Menu)', 'crt-manage' ),
                    'move-right' => esc_html__( 'Move Right (VR Menu)', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-sub-menu-fx-',
                'render_type' => 'template',
            ]
        );
    }

    public function add_control_mob_menu_show_on() {
        $breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

        $this->add_control(
            'mob_menu_show_on',
            [
                'label' => esc_html__( 'Show On', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'tablet',
                'options' => [
                    // 'none' => esc_html__( 'Don\'t Show', 'crt-manage' ),
                    'always' => esc_html__( 'All Devices', 'crt-manage' ),
                    /* translators: %d: Breakpoint number. */
                    'mobile' => sprintf( esc_html__( 'Mobile (≤ %dpx)', 'crt-manage' ), $breakpoints['mobile']->get_default_value() ),
                    /* translators: %d: Breakpoint number. */
                    'tablet' => sprintf( esc_html__( 'Tablet (≤ %dpx)', 'crt-manage' ), $breakpoints['tablet']->get_default_value() ),
                ],
                'prefix_class' => 'crt-nav-menu-bp-',
            ]
        );
    }

    public function add_controls_group_offcanvas() {
        $this->add_control(
            'mob_menu_display_as',
            [
                'label' => esc_html__( 'Display As', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'dropdown',
                'options' => [
                    'dropdown' => esc_html__( 'Dropdown', 'crt-manage' ),
                    'offcanvas' => esc_html__( 'Off-Canvas', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-mobile-menu-display-',
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'mob_menu_offcanvas_align',
            [
                'label' => esc_html__( 'Off-Canvas Slide', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__( 'Left', 'crt-manage' ),
                    'right' => esc_html__( 'Right', 'crt-manage' )
                ],
                'prefix_class' => 'crt-mobile-menu-offcanvas-slide-',
                'condition' => [
                    'mob_menu_display_as' => 'offcanvas',
                ],
            ]
        );

        $this->add_responsive_control(
            'mob_menu_offcanvas_width',
            [
                'label' => esc_html__( 'Off-Canvas Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'tablet_default' => [
                    'size' => 300,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 300,
                    'unit' => 'px',
                ],
                'default' => [
                    'size' => 300,
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.crt-mobile-menu-display-offcanvas .crt-mobile-mega-menu-wrap' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'mob_menu_display_as' => 'offcanvas',
                ],
            ]
        );

        $this->add_control(
            'mob_menu_offcanvas_animation_timing',
            [
                'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => Utilities::crt_animation_timings(),
                'default' => 'ease-default',
                'condition' => [
                    'mob_menu_display_as' => 'offcanvas',
                ],
            ]
        );

        $this->add_control(
            'mob_menu_offcanvas_animation_duration',
            [
                'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}}.crt-mobile-menu-display-offcanvas .crt-mobile-mega-menu-wrap' => 'transition-duration: {{VALUE}}s;',
                    '{{WRAPPER}}.crt-mobile-menu-display-offcanvas .crt-mobile-mega-menu > li > a,
					 {{WRAPPER}}.crt-mobile-menu-display-offcanvas .crt-mobile-mega-menu .crt-mobile-sub-menu > li > a,
					 {{WRAPPER}}.crt-mobile-menu-display-offcanvas .crt-mobile-sub-mega-menu,
					 {{WRAPPER}}.crt-mobile-menu-display-offcanvas .crt-mobile-mega-menu > li > .crt-mobile-sub-menu' => 'transition-duration: {{VALUE}}s;'
                ],
                'condition' => [
                    'mob_menu_display_as' => 'offcanvas',
                ],
            ]
        );

        $this->add_control(
            'mob_menu_offcanvas_logo',
            [
                'label' => esc_html__( 'Off-Canvas Logo', 'crt-manage' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'mob_menu_display_as' => 'offcanvas',
                ],
            ]
        );

        $this->add_control(
            'mob_menu_toggle_offcanvas_backface',
            [
                'label' => esc_html__( 'Toggle Backface Menu', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'mob_menu_display_as' => 'offcanvas',
                ],
                'render_type' => 'template'
            ]
        );
    }

    public function add_control_toggle_btn_style() {
        $this->add_control(
            'toggle_btn_style',
            [
                'label' => esc_html__( 'Style', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'hamburger',
                'options' => [
                    'hamburger' => esc_html__( 'Hamburger', 'crt-manage' ),
                    'text' => esc_html__( 'Text', 'crt-manage' ),
                ],
                'condition' => [
                    'mob_menu_show_on!' => 'none',
                ],
            ]
        );
    }

    public function add_control_sub_menu_width() {
        $this->add_responsive_control(
            'sub_menu_width',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'size' => 180,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-sub-menu' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
    }

    protected function register_controls() {

		// Tab: Content ==============
		// Section: Menu -------------
		$this->start_controls_section(
			'section_menu',
			[
				'label' => esc_html__( 'Menu', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu_select',
				[
					'label' => esc_html__( 'Select Menu', 'crt-manage' ),
					'type' => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
					'save_default' => true,
					'separator' => 'after',
					'description' => sprintf( __( '<strong>Note:</strong> Navigate to <a href="%s" target="_blank">Appearance > Menus</a><br> to manage your <strong>Mega Menus</strong>.', 'crt-manage' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu_select',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( '<strong>No menus found!</strong><br><a href="%s" target="_blank">Click Here</a> to create a new Menu.', 'crt-manage' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control_menu_layout();

		$this->add_responsive_control(
			'vertical_menu_width',
			[
				'label' => esc_html__( 'Vertical Menu Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 300,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu-vertical' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'menu_layout' => 'vertical',
				],
			]
		);

		$this->add_responsive_control(
			'menu_align',
			[
				'label' => esc_html__( 'Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'widescreen_default' => 'left',
				'laptop_default' => 'left',
				'tablet_extra_default' => 'left',
				'tablet_default' => 'left',
				'mobile_extra_default' => 'left',
				'mobile_default' => 'left',
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
				'prefix_class' => 'crt-main-menu-align-%s',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Menu Items -------
		$this->start_controls_section(
			'section_menu_items',
			[
				'label' => esc_html__( 'Menu Items', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_menu_items_pointer();

		$this->add_control_pointer_animation_line();

		$this->add_control_pointer_animation_border();

		$this->add_control_pointer_animation_background();

		$this->add_control(
			'pointer_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-menu-item.crt-pointer-item' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-menu-item.crt-pointer-item .crt-mega-menu-icon' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-menu-item.crt-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-menu-item.crt-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'condition' => [
					'menu_items_pointer!' => 'none',
				],
			]
		);

		$this->add_control(
			'menu_items_submenu_icon',
			[
				'label' => esc_html__( 'Sub Menu Icon', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'caret-down',
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'caret-down' => esc_html__( 'Triangle', 'crt-manage' ),
					'angle-down' => esc_html__( 'Angle', 'crt-manage' ),
					'chevron-down' => esc_html__( 'Chevron', 'crt-manage' ),
					'plus' => esc_html__( 'Plus', 'crt-manage' ),
				],
				'prefix_class' => 'crt-sub-icon-',
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		// $this->add_control(
		// 	'menu_items_submenu_position',
		// 	[
		// 		'label' => esc_html__( 'Sub Menu Position', 'crt-manage' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'default' => 'inline',
		// 		'options' => [
		// 			'inline' => esc_html__( 'Inline', 'crt-manage' ),
		// 			'absolute' => esc_html__( 'Absolute', 'crt-manage' ),
		// 		],
		// 		'prefix_class' => 'crt-sub-menu-position-',
		// 		'condition' => [
		// 			'menu_layout' => 'vertical',
		// 		],
		// 	]
		// );

		$this->add_control(
			'menu_items_submenu_trigger',
			[
				'label' => esc_html__( 'Sub Menu Display', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => [
					'hover' => esc_html__( 'on Mouse Over', 'crt-manage' ),
					'click' => esc_html__( 'on Mouse Click', 'crt-manage' ),
				],
			]
		);

		$this->add_control_menu_items_submenu_entrance();

		$this->end_controls_section(); // End Controls Section

		// Section: Mobile Menu ------
		$this->start_controls_section(
			'section_mobile_menu',
			[
				'label' => esc_html__( 'Mobile Menu', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_mob_menu_show_on();

		$this->add_controls_group_offcanvas();

		$this->add_control(
			'mob_menu_stretch',
			[
				'label' => esc_html__( 'Stretch', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'full-width',
				'options' => [
					'auto-width' => esc_html__( 'None', 'crt-manage' ),
					'full-width' => esc_html__( 'Full Width', 'crt-manage' ),
					'custom-width' => esc_html__( 'Custom Width', 'crt-manage' ),
				],
				'prefix_class' => 'crt-mobile-menu-',
				'render_type' => 'template',
				'condition' => [
					'mob_menu_display_as' => 'dropdown',
				],
			]
		);

		$this->add_responsive_control(
			'mob_menu_stretch_width',
			[
				'label' => esc_html__( 'Dropdown Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'tablet_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.crt-mobile-menu-custom-width .crt-mobile-mega-menu-wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'mob_menu_display_as' => 'dropdown',
					'mob_menu_stretch' => 'custom-width',
				],
			]
		);

		$this->add_control(
			'mob_menu_drdown_align',
			[
				'label' => esc_html__( 'Dropdown Align', 'crt-manage' ),
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
				'prefix_class' => 'crt-mobile-menu-drdown-align-',
				'condition' => [
					'mob_menu_display_as' => 'dropdown',
					'mob_menu_show_on!' => 'none',
					'mob_menu_stretch' => [ 'custom-width', 'auto-width' ],
				],
			]
		);

		$this->add_control(
			'heading_toggle_button',
			[
				'label' => esc_html__( 'Toggle Button', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'mob_menu_show_on!' => 'none',
				],
			]
		);

		$this->add_control_toggle_btn_style();

		$this->add_control(
			'toggle_btn_burger',
			[
				'label' => esc_html__( 'Toggle Icon', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'v1',
				'options' => [
					'v1' => esc_html__( 'Icon 1', 'crt-manage' ),
					'v2' => esc_html__( 'Icon 2', 'crt-manage' ),
					'v3' => esc_html__( 'Icon 3', 'crt-manage' ),
					'v4' => esc_html__( 'Icon 4', 'crt-manage' ),
					'v5' => esc_html__( 'Icon 5', 'crt-manage' ),
				],
				'prefix_class' => 'crt-mobile-toggle-',
				'condition' => [
					'mob_menu_show_on!' => 'none',
					'toggle_btn_style' => ['hamburger', 'pro-tx'],
				],
			]
		);

		$this->add_control(
			'toggle_btn_txt_1',
			[
				'label' => esc_html__( 'Toggle Open Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Menu', 'crt-manage' ),
				'condition' => [
					'mob_menu_show_on!' => 'none',
					'toggle_btn_style' => 'text',
				],
			]
		);

		$this->add_control(
			'toggle_btn_txt_2',
			[
				'label' => esc_html__( 'Toggle Close Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Close', 'crt-manage' ),
				'condition' => [
					'mob_menu_show_on!' => 'none',
					'toggle_btn_style' => 'text',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_btn_align',
			[
				'label' => esc_html__( 'Toggle Align', 'crt-manage' ),
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
				'selectors_dictionary' => [
					'left' => 'text-align: left',
					'center' => 'text-align: center',
					'right' => 'text-align: right',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-toggle-wrap' => '{{VALUE}}',
				],
				'condition' => [
					'mob_menu_show_on!' => 'none',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section


		// Tab: Styles ===============
		// Section: Menu Items -------
		$this->start_controls_section(
			'section_style_menu_items',
			[
				'label' => esc_html__( 'Menu Items', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'menu_item_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu .crt-menu-item,
					 {{WRAPPER}} .crt-nav-menu > .menu-item-has-children > .crt-sub-icon' => 'color: {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'menu_item_icon_color',
			[
				'label' => esc_html__( 'Custom Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu .crt-mega-menu-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_item_color_bg',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E800',
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu .crt-menu-item' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'menu_items_pointer' => 'background',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_items_typography',
				'selector' => '{{WRAPPER}} .crt-menu-item,{{WRAPPER}} .crt-mobile-menu-item,{{WRAPPER}} .crt-mobile-sub-menu-item,{{WRAPPER}} .crt-mobile-toggle-text, .crt-menu-offcanvas-back h3',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'menu_item_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_4,
				// ],
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu .crt-menu-item:hover,
					{{WRAPPER}} .crt-nav-menu .crt-menu-item:hover .crt-mega-menu-icon,
					{{WRAPPER}} .crt-nav-menu .crt-menu-item.crt-active-menu-item .crt-mega-menu-icon,
					 {{WRAPPER}} .crt-nav-menu > .menu-item-has-children:hover > .crt-sub-icon,
					 {{WRAPPER}} .crt-nav-menu .crt-menu-item.crt-active-menu-item,
					 {{WRAPPER}} .crt-nav-menu > .menu-item-has-children.current_page_item > .crt-sub-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pointer_color_hover',
			[
				'label' => esc_html__( 'Pointer Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_4,
				// ],
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}}.crt-pointer-line-fx .crt-menu-item:before,
					 {{WRAPPER}}.crt-pointer-line-fx .crt-menu-item:after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.crt-pointer-border-fx .crt-menu-item:before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}}.crt-pointer-background-fx .crt-menu-item:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'menu_item_highlight',
			[
				'label' => esc_html__( 'Highlight Active Item', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'menu_items_extra_icon_size',
			[
				'label' => esc_html__( 'Custom Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 16,
				],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu .crt-mega-menu-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'menu_items_extra_icon_distance',
			[
				'label' => esc_html__( 'Custom Icon Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu .crt-mega-menu-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'menu_items_sub_icon_size',
			[
				'label' => esc_html__( 'Sub Menu Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .menu-item-has-children .crt-sub-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}}.crt-pointer-background:not(.crt-sub-icon-none) .crt-nav-menu-horizontal .menu-item-has-children .crt-pointer-item' => 'padding-right: calc({{SIZE}}px + {{menu_items_padding_hr.SIZE}}px);',
					// '{{WRAPPER}}.crt-pointer-border:not(.crt-sub-icon-none) .crt-nav-menu-horizontal .menu-item-has-children .crt-pointer-item' => 'padding-right: calc({{SIZE}}px + {{menu_items_padding_hr.SIZE}}px);',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'pointer_height',
			[
				'label' => esc_html__( 'Pointer Weight', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'devices' => [ self::RESPONSIVE_DESKTOP, self::RESPONSIVE_TABLET ],
				'default' => [
					'size' => 2,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.crt-pointer-underline>.crt-nav-menu-container >ul>li>.crt-menu-item:after,
					 {{WRAPPER}}.crt-pointer-overline>.crt-nav-menu-container >ul>li>.crt-menu-item:before,
					 {{WRAPPER}}.crt-pointer-double-line>.crt-nav-menu-container >ul>li>.crt-menu-item:before,
					 {{WRAPPER}}.crt-pointer-double-line>.crt-nav-menu-container >ul>li>.crt-menu-item:after' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-pointer-border-fx>.crt-nav-menu-container >ul>li>.crt-menu-item:before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-pointer-underline>.elementor-widget-container>.crt-nav-menu-container >ul>li>.crt-menu-item:after,
					 {{WRAPPER}}.crt-pointer-overline>.elementor-widget-container>.crt-nav-menu-container >ul>li>.crt-menu-item:before,
					 {{WRAPPER}}.crt-pointer-double-line>.elementor-widget-container>.crt-nav-menu-container >ul>li>.crt-menu-item:before,
					 {{WRAPPER}}.crt-pointer-double-line>.elementor-widget-container>.crt-nav-menu-container >ul>li>.crt-menu-item:after' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-pointer-border-fx>.elementor-widget-container>.crt-nav-menu-container >ul>li>.crt-menu-item:before' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'menu_items_pointer!' => 'background',
				],
			]
		);

		$this->add_control(
			'pointer_distance',
			[
				'label' => esc_html__( 'Pointer Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'devices' => [ self::RESPONSIVE_DESKTOP, self::RESPONSIVE_TABLET ],
				'default' => [
					'size' => 13,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:not(.crt-pointer-border-fx) .crt-menu-item.crt-pointer-item:before' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
					'{{WRAPPER}}:not(.crt-pointer-border-fx) .crt-menu-item.crt-pointer-item:after' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'menu_items_pointer!' => 'background',
				],
			]
		);

		$this->add_responsive_control(
			'menu_items_padding_hr',
			[
				'label' => esc_html__( 'Inner Horizontal Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu .crt-menu-item' => 'padding-left: {{SIZE}}px; padding-right: {{SIZE}}px;',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'menu_items_padding_bg_hr',
			[
				'label' => esc_html__( 'Outer Horizontal Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu > .menu-item' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-nav-menu-vertical .crt-nav-menu > li > .crt-sub-menu' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-nav-menu-vertical .crt-nav-menu > li > .crt-sub-mega-menu' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-main-menu-align-left .crt-nav-menu-vertical .crt-nav-menu > li > .crt-sub-icon' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-main-menu-align-right .crt-nav-menu-vertical .crt-nav-menu > li > .crt-sub-icon' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'menu_layout!' => 'vertical',
				],
			]
		);

		$this->add_responsive_control(
			'menu_items_padding_vr',
			[
				'label' => esc_html__( 'Vertical Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu .crt-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control( // Only Vertical Menu
			'menu_items_sub_offset',
			[
				'label' => esc_html__( 'Sub Menu Offset', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu-horizontal .crt-nav-menu .crt-sub-mega-menu' => 'transform: translateY({{SIZE}}{{UNIT}});',
					'{{WRAPPER}}.crt-main-menu-align-center .crt-nav-menu-horizontal .crt-mega-menu-pos-default.crt-mega-menu-width-custom .crt-sub-mega-menu' => 'transform: translate(-50%, {{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .crt-nav-menu-horizontal .crt-nav-menu > li > .crt-sub-menu' => 'transform: translateY({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .crt-nav-menu-vertical .crt-nav-menu > li > .crt-sub-menu' => 'transform: translateX({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .crt-nav-menu-vertical .crt-nav-menu > li > .crt-sub-mega-menu' => 'transform: translateX({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'menu_items_border',
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
						'default' => '#e8e8e8',
					],
				],
				'selector' => '{{WRAPPER}} .crt-menu-item',
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Menu Item Badge ---------
		$this->start_controls_section(
			'section_style_menu_item_badge',
			[
				'label' => esc_html__( 'Menu Item Badge', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_items_badge_typography',
				'selector' => '{{WRAPPER}} .crt-nav-menu .crt-mega-menu-badge'
			]
		);

		$this->add_control(
			'menu_items_badge_top_distance',
			[
				'label' => esc_html__( 'Vertical Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu .crt-mega-menu-badge' => 'top: -{{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'menu_layout' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'menu_items_badge_right_distance',
			[
				'label' => esc_html__( 'Horizontal Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu-horizontal .crt-mega-menu-badge' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-nav-menu-vertical .crt-mega-menu-badge' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'menu_items_badge_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => '3',
					'right' =>  '5',
					'bottom' => '2',
					'left' => '5',
					'unit' => 'px',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu .crt-mega-menu-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'menu_items_badge_radius',
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
					'{{WRAPPER}} .crt-nav-menu .crt-mega-menu-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Sub Mega Menu ---------
		$this->start_controls_section(
			'section_style_sub_mega_menu',
			[
				'label' => esc_html__( 'Sub Mega Menu', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sub_mega_menu_color_bg',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-sub-mega-menu' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'sub_mega_menu_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .crt-sub-mega-menu',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sub_mega_menu_border',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
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
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .crt-sub-mega-menu',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'sub_mega_menu_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-sub-mega-menu' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: WP Sub Menu ---------
		$this->start_controls_section(
			'section_style_sub_menu',
			[
				'label' => esc_html__( 'WordPress Sub Menu', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_sub_menu_style' );

		$this->start_controls_tab(
			'tab_sub_menu_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'sub_menu_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-sub-menu .crt-sub-menu-item,
					 {{WRAPPER}} .crt-sub-menu > .menu-item-has-children .crt-sub-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sub_menu_color_bg',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-sub-menu .crt-sub-menu-item' => 'background-color: {{VALUE}};',
				],
				'separator' => 'after'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_sub_menu_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'sub_menu_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_4,
				// ],
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-sub-menu .crt-sub-menu-item:hover,
					 {{WRAPPER}} .crt-sub-menu > .menu-item-has-children .crt-sub-menu-item:hover .crt-sub-icon,
					 {{WRAPPER}} .crt-sub-menu .crt-sub-menu-item.crt-active-menu-item,
					 {{WRAPPER}} .crt-sub-menu .crt-sub-menu-item.crt-active-menu-item .crt-sub-icon,
					 {{WRAPPER}} .crt-sub-menu > .menu-item-has-children.current_page_item .crt-sub-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sub_menu_color_bg_hover',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_4,
				// ],
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-sub-menu .crt-sub-menu-item:hover,
					 {{WRAPPER}} .crt-sub-menu .crt-sub-menu-item.crt-active-menu-item' => 'background-color: {{VALUE}};',
				],
				'separator' => 'after'
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_menu_typography',
				'selector' => '{{WRAPPER}} .crt-sub-menu .crt-sub-menu-item'
			]
		);

		$this->add_control_sub_menu_width();

		$this->add_responsive_control(
			'sub_menu_padding_hr',
			[
				'label' => esc_html__( 'Horizontal Padding', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-sub-menu .crt-sub-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-sub-menu .crt-sub-icon' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-main-menu-align-right .crt-nav-menu-vertical .crt-sub-menu .crt-sub-icon' => 'left: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'sub_menu_padding_vr',
			[
				'label' => esc_html__( 'Vertical Padding', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 13,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-sub-menu .crt-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sub_menu_divider',
			[
				'label' => esc_html__( 'Item Divider', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'crt-sub-divider-',
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sub_menu_divider_color',
			[
				'label' => esc_html__( 'Divider Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_4,
				// ],
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}}.crt-sub-divider-yes .crt-sub-menu li:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'sub_menu_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'sub_menu_divider_height',
			[
				'label' => esc_html__( 'Divider Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-sub-divider-yes .crt-sub-menu li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'sub_menu_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'sub_menu_divider_ctrl',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sub_menu_border',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
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
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .crt-sub-menu',
			]
		);

		$this->add_control(
			'sub_menu_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-sub-menu li:first-child a' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .crt-sub-menu li:last-child a' => 'border-bottom-left-radius: {{BOTTOM}}{{UNIT}}; border-bottom-right-radius: {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'sub_menu_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .crt-sub-menu',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Toggle Button ----
		$this->start_controls_section(
			'section_style_toggle_button',
			[
				'label' => esc_html__( 'Toggle Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'mob_menu_show_on!' => 'none',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_toggle_style' );

		$this->start_controls_tab(
			'tab_toggle_btn_style_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'toggle_btn_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-toggle' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-mobile-toggle-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-mobile-toggle-line' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-toggle' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_toggle_btn_style_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'toggle_btn_color_hover',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-toggle:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-mobile-toggle:hover .crt-mobile-toggle-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-mobile-toggle:hover .crt-mobile-toggle-line' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-toggle:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toggle_btn_lines_height',
			[
				'label' => esc_html__( 'Lines Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 4,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-toggle-line' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'toggle_btn_style' => ['hamburger', 'pro-tx'],
				],
			]
		);

		$this->add_control(
			'toggle_btn_line_space',
			[
				'label' => esc_html__( 'Space Between Lines', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-toggle-line' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'toggle_btn_style' => ['hamburger', 'pro-tx'],
				],
			]
		);

		$this->add_control(
			'toggle_btn_width',
			[
				'label' => esc_html__( 'Button Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 150,
					],
				],
				'default' => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-toggle' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_btn_padding',
			[
				'label' => esc_html__( 'Button Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_border_width',
			[
				'label' => esc_html__( 'Button Border Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-toggle' => 'border-width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'toggle_btn_border_radius',
			[
				'label' => esc_html__( 'Button Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-toggle' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Mobile Menu Off-Canvas -------
		$this->start_controls_section(
			'section_style_mobile_menu_offcanvas',
			[
				'label' => esc_html__( 'Mobile Menu Off-Canvas', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'mob_menu_display_as' => 'offcanvas',
				],
			]
		);

		$this->add_control(
			'mobile_menu_general_heading',
			[
				'label' => esc_html__('General', 'crt-manage'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'mobile_menu_general_color_bg',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-mega-menu-wrap' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'mobile_menu_general_overlay_color_bg',
			[
				'label'  => esc_html__( 'Overlay Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0000007A',
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-mega-menu-overlay' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'mobile_menu_general_box_shadow',
				'selector' => '{{WRAPPER}} .crt-mobile-mega-menu-wrap',
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
                                'blur' => 5,
                                'spread' => 0,
                                'color' => 'rgba(0,0,0,0.3)'
                            ]
                    ]
				]
			]
		);

		$this->add_responsive_control(
			'mobile_menu_general_padding',
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
					'{{WRAPPER}} .crt-mobile-mega-menu-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-mobile-menu-display-offcanvas .crt-mobile-sub-mega-menu' => 'margin-left: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-mobile-menu-display-offcanvas .crt-mobile-mega-menu > li > .crt-mobile-sub-menu' => 'margin-left: {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_logo_heading',
			[
				'label' => esc_html__('Logo', 'crt-manage'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'mobile_menu_logo_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 150,
					],
				],
				'default' => [
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .mobile-mega-menu-logo' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_close_heading',
			[
				'label' => esc_html__('Close Button', 'crt-manage'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_menu_close_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .mobile-mega-menu-close' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'mobile_menu_close_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .mobile-mega-menu-close' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_header_heading',
			[
				'label' => esc_html__('Logo & Close Button Wrapper', 'crt-manage'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'mobile_menu_header_padding',
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
					'{{WRAPPER}} .mobile-mega-menu-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mobile_menu_header_distance',
			[
				'label' => esc_html__( 'Bottom Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .mobile-mega-menu-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_back_heading',
			[
				'label' => esc_html__('Back to Menu Arrow & Title', 'crt-manage'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'menu_items_sub_back_icon_color',
			[
				'label'  => esc_html__( 'Arrow Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .menu-item-has-children .crt-menu-offcanvas-back svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'menu_items_sub_back_icon_size',
			[
				'label' => esc_html__( 'Arrow Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 18,
				],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .menu-item-has-children .crt-menu-offcanvas-back svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'menu_items_sub_back_heading_color',
			[
				'label'  => esc_html__( 'Heading Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .menu-item-has-children .crt-menu-offcanvas-back h3' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'menu_items_sub_back_heading_size',
			[
				'label' => esc_html__( 'Heading Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 18,
				],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .menu-item-has-children .crt-menu-offcanvas-back h3' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Mobile Menu -------
		$this->start_controls_section(
			'section_style_mobile_menu',
			[
				'label' => esc_html__( 'Mobile Menu Items', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_mobile_menu_style' );

		$this->start_controls_tab(
			'tab_mobile_menu_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'mobile_menu_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-menu-item,
					{{WRAPPER}} .crt-mobile-sub-menu-item,
					{{WRAPPER}} .menu-item-has-children > .crt-mobile-menu-item:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-nav-menu > li,
					 {{WRAPPER}} .crt-mobile-sub-menu li' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_mobile_menu_focus',
			[
				'label' => esc_html__( 'Focus', 'crt-manage' ),
			]
		);

		$this->add_control(
			'mobile_menu_color_focus',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_4,
				// ],
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-nav-menu li a:hover,
					 {{WRAPPER}} .crt-mobile-nav-menu .menu-item-has-children > a:hover:after,
					 {{WRAPPER}} .crt-mobile-nav-menu li a.crt-active-menu-item,
					 {{WRAPPER}} .crt-mobile-nav-menu .menu-item-has-children.current_page_item > a:hover:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_bg_color_focus',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-menu-item:hover,
					{{WRAPPER}} .crt-mobile-sub-menu-item:hover,
					{{WRAPPER}} .crt-mobile-sub-menu-item.crt-active-menu-item,
					{{WRAPPER}} .crt-mobile-menu-item.crt-active-menu-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'mobile_menu_highlight',
			[
				'label' => esc_html__( 'Highlight Active Item', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes'
			]
		);

		$this->add_control(
			'mobile_menu_padding_hr',
			[
				'label' => esc_html__( 'Item Horizontal Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-mobile-mega-menu > li > a > .crt-mobile-sub-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_menu_padding_vr',
			[
				'label' => esc_html__( 'Item Vertical Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-nav-menu .crt-mobile-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'mobile_menu_divider',
			[
				'label' => esc_html__( 'Item Divider', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'crt-mobile-divider-',
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_menu_divider_color',
			[
				'label' => esc_html__( 'Divider Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}}.crt-mobile-divider-yes .crt-mobile-menu-item' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'mobile_menu_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'mobile_menu_divider_height',
			[
				'label' => esc_html__( 'Divider Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-mobile-divider-yes .crt-mobile-menu-item' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'mobile_menu_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'mobile_menu_sub_icon_size',
			[
				'label' => esc_html__( 'Sub Menu Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-mega-menu .crt-mobile-sub-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_menu_sub_font_size',
			[
				'label' => esc_html__( 'Sub Item Font Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 30,
					],
				],
				'default' => [
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-nav-menu .crt-mobile-sub-menu-item' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_sub_padding_hr',
			[
				'label' => esc_html__( 'Sub Item Horizontal Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-nav-menu .crt-mobile-sub-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-menu-offcanvas-back' => 'padding-left:{{SIZE}}{{UNIT}}; padding-right:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_sub_padding_vr',
			[
				'label' => esc_html__( 'Sub Item Vertical Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-nav-menu .crt-mobile-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'mobile_menu_offset',
			[
				'label' => esc_html__( 'Dropdown Offset', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'px' => [
					'min' => 1,
					'max' => 50,
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-mobile-menu-display-dropdown .crt-mobile-nav-menu' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'mob_menu_display_as' => 'dropdown',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	public function load_walkers() {
        require_once CRT_MANAGE_DIR . 'includes/customizer/addons/class/mega-menu/walkers/class-crt-main-menu-walker.php';
		require_once CRT_MANAGE_DIR . 'includes/customizer/addons/class/mega-menu/walkers/class-crt-mobile-menu-walker.php';
	}

	protected function render() {
		$available_menus = $this->get_available_menus();
	
		if ( ! $available_menus ) {
			return;
		}

		// Custom Menu
		$this->load_walkers();

		// Get Settings
		$settings = $this->get_active_settings();

		$main_args = [
			'echo' => false,
			'menu' => $settings['menu_select'],
			'menu_class' => 'crt-nav-menu crt-mega-menu',
			'menu_id' => 'menu-'. $this->get_nav_menu_index() .'-'. $this->get_id(),
			'container' => '',
			'fallback_cb' => '__return_empty_string',
			'walker' => new \Crt_Main_Menu_Walker($settings['menu_item_highlight']),
		];

		// Add Custom Filters
		add_filter( 'nav_menu_item_id', '__return_empty_string' );

		// Generate Menu HTML
		$menu_html = wp_nav_menu( $main_args );

		$mobile_args = [
			'echo' => false,
			'menu' => $settings['menu_select'],
			'menu_class' => 'crt-mobile-nav-menu crt-mobile-mega-menu',
			'menu_id' => 'mobile-menu-'. $this->get_nav_menu_index() .'-'. $this->get_id(),
			'container' => '',
			'fallback_cb' => '__return_empty_string',
			'walker' => new \Crt_Mobile_Menu_Walker($settings['mobile_menu_highlight']),
		];

		// Retrieve Image Alt Text
		$image_alt = '';
		if ( ! empty( $settings["mob_menu_offcanvas_logo"]["url"] ) ) {
			// Get the attachment ID from the image source URL
			$attachment_id = attachment_url_to_postid( $settings["mob_menu_offcanvas_logo"]["url"] );
			
			if ( $attachment_id ) {
				$image_alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
			}
		}

		// Generate Mobile Menu HTML
		$moible_menu_html = wp_nav_menu( $mobile_args );

		// Remove Custom Filters
		remove_filter( 'nav_menu_item_id', '__return_empty_string' );

		if ( empty( $menu_html ) ) {
			return;
		}

		// Main Menu
		echo '<nav class="crt-nav-menu-container crt-mega-menu-container crt-nav-menu-'. esc_attr($settings['menu_layout']) .'" data-trigger="'. esc_attr($settings['menu_items_submenu_trigger']) .'">';
			echo ''. $menu_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</nav>';

		// Mobile Menu
		echo '<nav class="crt-mobile-nav-menu-container">';

			// Toggle Button
			echo '<div class="crt-mobile-toggle-wrap">';
				echo '<div class="crt-mobile-toggle">';
					if ( 'hamburger' === $settings['toggle_btn_style'] ) {
						echo '<span class="crt-mobile-toggle-line"></span>';
						echo '<span class="crt-mobile-toggle-line"></span>';
						echo '<span class="crt-mobile-toggle-line"></span>';
					} elseif ( 'text' === $settings['toggle_btn_style'] ) {
						echo '<span class="crt-mobile-toggle-text">'. esc_html($settings['toggle_btn_txt_1']) .'</span>';
						echo '<span class="crt-mobile-toggle-text">'. esc_html($settings['toggle_btn_txt_2']) .'</span>';
					}
				echo '</div>';
			echo '</div>';

			$animation_class =  'crt-anim-timing-'. $settings['mob_menu_offcanvas_animation_timing'];
			$toggle_offcanvas_backface = isset($settings['mob_menu_toggle_offcanvas_backface']) ? $settings['mob_menu_toggle_offcanvas_backface'] : '';

			// Menu
			echo '<div class="crt-mobile-mega-menu-wrap '. $animation_class .'" toggle-backface="'. $toggle_offcanvas_backface .'">';
				if ( 'offcanvas' === $settings['mob_menu_display_as'] ) {
					echo '<div class="mobile-mega-menu-header">';
						if ( ! empty( $settings['mob_menu_offcanvas_logo']['url'] ) ) {
							echo '<div class="mobile-mega-menu-logo">';
								echo '<a href="'. esc_url(home_url()) .'">';
									echo '<img src="'. esc_url($settings["mob_menu_offcanvas_logo"]["url"]) .'" alt="'. esc_attr( $image_alt ) .'">';
								echo '</a>';
							echo '</div>';
						}
						
						echo '<i class="mobile-mega-menu-close fas fa-times"></i>';
					echo '</div>';
				}

				echo ''. $moible_menu_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';

            echo '<div class="crt-mobile-mega-menu-overlay"></div>';

		echo '</nav>';
	}
	
}