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

class CRT_Nav_Menu extends Widget_Base {

	protected $nav_menu_index = 1;
	
	public function get_name() {
		return 'crt-nav-menu';
	}

	public function get_title() {
		return esc_html__( 'Nav Menu', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'crt_manage_header_elements'];
	}

	public function get_keywords() {
		return [ 'crt', 'nav menu', 'header', 'navigation menu', 'horizontal menu', 'horizontal navigation', 'vertical menu', 'vertical navigation', 'burger menu', 'hamburger menu', 'mobile menu', 'responsive menu' ];
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
					'slide' => esc_html__( 'Slide', 'crt-manage' ),
				],
				'prefix_class' => 'crt-sub-menu-fx-',
			]
		);
	}

	public function add_control_mob_menu_display() {
		$breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

		$this->add_control(
			'mob_menu_display',
			[
				'label' => esc_html__( 'Show On', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'mobile',
				'options' => [
					/* translators: %d: Breakpoint number. */
					'mobile' => sprintf( esc_html__( 'Mobile (≤ %dpx)', 'crt-manage' ), $breakpoints['mobile']->get_default_value() ),
					/* translators: %d: Breakpoint number. */
					'tablet' => sprintf( esc_html__( 'Tablet (≤ %dpx)', 'crt-manage' ), $breakpoints['tablet']->get_default_value() ),
					'none' => esc_html__( 'Don\'t Show', 'crt-manage' ),
					'all' => esc_html__( 'All Devices', 'crt-manage' ),
				],
				'prefix_class' => 'crt-nav-menu-bp-',
				'render_type' => 'template',
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
					'mob_menu_display!' => 'none',
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
				'label' => 'Menu',
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

//		Utilities::crt_library_buttons( $this, Controls_Manager::RAW_HTML );
		
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
					'description' => sprintf( __( 'Go to <a href="%s" target="_blank">Appearance > Menus</a> to manage your menus.', 'crt-manage' ), admin_url( 'nav-menus.php' ) ),
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

		// Upgrade to Pro Notice
//		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'menu_layout', ['pro-vr'] );

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

		// Upgrade to Pro Notice
//		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'menu_items_pointer', ['pro-bd', 'pro-bg'] );

		$this->add_control_pointer_animation_line();

		// Upgrade to Pro Notice
//		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'pointer_animation_line', ['pro-sl', 'pro-dr', 'pro-gr']);

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
					'{{WRAPPER}} .crt-menu-item.crt-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-menu-item.crt-pointer-item:after' => 'transition-duration: {{VALUE}}s',
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
				'separator' => 'before'
			]
		);

		$this->add_control(
			'menu_items_submenu_position',
			[
				'label' => esc_html__( 'Sub Menu Position', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline' => esc_html__( 'Inline', 'crt-manage' ),
					'absolute' => esc_html__( 'Absolute', 'crt-manage' ),
				],
				'prefix_class' => 'crt-sub-menu-position-',
				'condition' => [
					'menu_layout' => 'vertical',
				],
			]
		);

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

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'menu_items_submenu_entrance', ['pro-sl'] );

		$this->end_controls_section(); // End Controls Section

		// Section: Mobile Menu ------
		$this->start_controls_section(
			'section_mobile_menu',
			[
				'label' => esc_html__( 'Mobile Menu', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_mob_menu_display();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'mob_menu_display', ['pro-nn', 'pro-al'] );

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
					'{{WRAPPER}}.crt-mobile-menu-custom-width .crt-mobile-nav-menu' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
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
					'mob_menu_display!' => 'none',
					'mob_menu_stretch' => [ 'custom-width', 'auto-width' ],
				],
			]
		);

		$this->add_control(
			'mob_menu_item_align',
			[
				'label' => esc_html__( 'Item Align', 'crt-manage' ),
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
				'prefix_class' => 'crt-mobile-menu-item-align-',
				'condition' => [
					'mob_menu_display!' => 'none',
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
					'mob_menu_display!' => 'none',
				],
			]
		);

		$this->add_control_toggle_btn_style();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'toggle_btn_style', ['pro-tx'] );

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
					'mob_menu_display!' => 'none',
					'toggle_btn_style' => ['hamburger', 'text'],
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
					'mob_menu_display!' => 'none',
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
					'mob_menu_display!' => 'none',
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
					'mob_menu_display!' => 'none',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
//		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );
//
//		// Section: Pro Features
//		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'nav-menu', [
//			'Vertical Layout',
//			'Advanced Link Hover Effects: Slide, Grow, Drop',
//			'SubMenu Entrance Slide Effect',
//			'SubMenu Width option',
//			'Advanced Display Conditions',
//			'Mobile Menu Display Custom Conditions',
//			'Mobile Menu Button Custom Text option',
//		] );
		
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
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu .crt-menu-item:hover,
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
				'default' => '#e55b5b',
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
			'menu_items_sub_icon_size',
			[
				'label' => esc_html__( 'Sub Menu Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .menu-item-has-children .crt-sub-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-pointer-background:not(.crt-sub-icon-none) .crt-nav-menu-horizontal .menu-item-has-children .crt-pointer-item' => 'padding-right: calc({{SIZE}}px + {{menu_items_padding_hr.SIZE}}px);',
					'{{WRAPPER}}.crt-pointer-border:not(.crt-sub-icon-none) .crt-nav-menu-horizontal .menu-item-has-children .crt-pointer-item' => 'padding-right: calc({{SIZE}}px + {{menu_items_padding_hr.SIZE}}px);',
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_items_typography',
				'selector' => '{{WRAPPER}} .crt-nav-menu .crt-menu-item,{{WRAPPER}} .crt-mobile-nav-menu a,{{WRAPPER}} .crt-mobile-toggle-text',
			]
		);

		$this->add_control(
			'pointer_height',
			[
				'label' => esc_html__( 'Pointer Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'devices' => [ self::RESPONSIVE_DESKTOP, self::RESPONSIVE_TABLET ],
				'default' => [
					'size' => 2,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.crt-pointer-underline .crt-menu-item:after,
					 {{WRAPPER}}.crt-pointer-overline .crt-menu-item:before,
					 {{WRAPPER}}.crt-pointer-double-line .crt-menu-item:before,
					 {{WRAPPER}}.crt-pointer-double-line .crt-menu-item:after' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-pointer-border-fx .crt-menu-item:before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-pointer-underline>nav>ul>li>.crt-menu-item:after,
					 {{WRAPPER}}.crt-pointer-overline>nav>ul>li>.crt-menu-item:before,
					 {{WRAPPER}}.crt-pointer-double-line>nav>ul>li>.crt-menu-item:before,
					 {{WRAPPER}}.crt-pointer-double-line>nav>ul>li>.crt-menu-item:after' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-pointer-border-fx>nav>ul>li>.crt-menu-item:before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-pointer-underline>.elementor-widget-container>nav>ul>li>.crt-menu-item:after,
					 {{WRAPPER}}.crt-pointer-overline>.elementor-widget-container>nav>ul>li>.crt-menu-item:before,
					 {{WRAPPER}}.crt-pointer-double-line>.elementor-widget-container>nav>ul>li>.crt-menu-item:before,
					 {{WRAPPER}}.crt-pointer-double-line>.elementor-widget-container>nav>ul>li>.crt-menu-item:after' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-pointer-border-fx>.elementor-widget-container>nav>ul>li>.crt-menu-item:before' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pointer_distance',
			[
				'label' => esc_html__( 'Pointer Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'devices' => [ self::RESPONSIVE_DESKTOP, self::RESPONSIVE_TABLET ],
				'default' => [
					'size' => 1,
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
				]
			]
		);

		$this->add_responsive_control(
			'menu_items_padding_hr',
			[
				'label' => esc_html__( 'Inner Horizontal Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 7,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu .crt-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-pointer-background:not(.crt-sub-icon-none) .crt-nav-menu-vertical .menu-item-has-children .crt-sub-icon' => 'text-indent: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-pointer-border:not(.crt-sub-icon-none) .crt-nav-menu-vertical .menu-item-has-children .crt-sub-icon' => 'text-indent: -{{SIZE}}{{UNIT}};',

				]
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
					'{{WRAPPER}}.crt-main-menu-align-left .crt-nav-menu-vertical .crt-nav-menu > li > .crt-sub-icon' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-main-menu-align-right .crt-nav-menu-vertical .crt-nav-menu > li > .crt-sub-icon' => 'left: {{SIZE}}{{UNIT}};',
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
						'default' => '#222222',
					],
				],
				'selector' => '{{WRAPPER}} .crt-menu-item',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Sub Menu ---------
		$this->start_controls_section(
			'section_style_sub_menu',
			[
				'label' => esc_html__( 'Sub Menu', 'crt-manage' ),
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
				'default' => '#e55b5b',
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

		$this->add_responsive_control(
			'sub_menu_offset',
			[
				'label' => esc_html__( 'Sub Menu Offset', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-nav-menu-horizontal .crt-nav-menu > li > .crt-sub-menu' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'sub_menu_divider',
			[
				'label' => esc_html__( 'Item Divider', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'crt-sub-divider-',
				'default' => 'yes',
				'return_value' => 'yes'
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

		$this->add_control(
			'sub_menu_items_heading',
			[
				'label' => esc_html__( 'Sub Menu Items', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sub_menu_items_border',
				'fields_options' => [
					'border' => [
						'default' => '',
					],
					'width' => [
						'default' => [
							'top' => '0',
							'right' => '0',
							'bottom' => '0',
							'left' => '0',
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .crt-sub-menu .menu-item',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Mobile Menu -------
		$this->start_controls_section(
			'section_style_mobile_menu',
			[
				'label' => esc_html__( 'Mobile Menu', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'mob_menu_display!' => 'none',
				],
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
					'{{WRAPPER}} .crt-mobile-nav-menu a,
					 {{WRAPPER}} .crt-mobile-nav-menu .menu-item-has-children > a:after' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .crt-mobile-nav-menu li' => 'background-color: {{VALUE}};',
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
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-nav-menu a:hover,
					 {{WRAPPER}} .crt-mobile-nav-menu a.crt-active-menu-item' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .crt-mobile-nav-menu a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-mobile-nav-menu .menu-item-has-children > a:after' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}}.crt-mobile-divider-yes .crt-mobile-nav-menu a' => 'border-bottom-color: {{VALUE}};',
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
					'{{WRAPPER}}.crt-mobile-divider-yes .crt-mobile-nav-menu a' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'mobile_menu_divider' => 'yes',
				],
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
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_menu_sub_padding_vr',
			[
				'label' => esc_html__( 'Sub Item Vertical Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
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
					'min' => 50,
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mobile-nav-menu' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
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
					'mob_menu_display!' => 'none',
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
				'default' => '#e55b5b',
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
					'toggle_btn_style' => ['hamburger', 'text'],
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
					'toggle_btn_style' => ['hamburger', 'text'],
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

	}

	public function custom_menu_item_classes( $atts, $item, $args, $depth ) {
		$settings = $this->get_active_settings();

		// Main or Mobile
		if ( strpos( $args->menu_id, 'mobile-menu' ) === false ) {
		    $main 	= 'crt-menu-item crt-pointer-item';
		    $sub 	= 'crt-sub-menu-item';
		    $active = $settings['menu_item_highlight'] === 'yes' ? ' crt-active-menu-item' : '';
		} else {
		    $main 	= 'crt-mobile-menu-item';
		    $sub 	= 'crt-mobile-sub-menu-item';
		    $active = $settings['mobile_menu_highlight'] === 'yes' ? ' crt-active-menu-item' : '';
		}

		$classes = $depth ? $sub : $main;

		if ( in_array( 'current-menu-item', $item->classes ) ) {
			$classes .= $active;
		}

		if ( empty( $atts['class'] ) ) {
			$atts['class'] = $classes;
		} else {
			$atts['class'] .= ' '. $classes;
		}

		return $atts;
	}

	public function custom_sub_menu_class( $classes ) {
		$classes[] = 'crt-sub-menu';

		return $classes;
	}

	public function custom_menu_items( $output, $item, $depth, $args ) {
		$settings = $this->get_active_settings();

		if ( strpos( $args->menu_class, 'crt-nav-menu' ) !== false ) {
			if ( in_array( 'menu-item-has-children', $item->classes ) ) {
				$item_class = 'crt-menu-item crt-pointer-item';

				if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) {
					$item_class .= ' crt-active-menu-item';
				}

				// Sub Menu Classes
				if ( $depth > 0 ) {
					$item_class = 'crt-sub-menu-item';

					if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) {
						$item_class .= ' crt-active-menu-item';
					}
				}

				// Add Sub Menu Icon
				// $output  ='<a aria-haspopup="true" aria-expanded="false" href="'. esc_url($item->url) .'" class="'. esc_attr($item_class) .'">'. esc_html($item->title);
				// GOGA: render language switcher correctly
				$output = '<a aria-haspopup="true" aria-expanded="false" href="' . esc_url($item->url) . '" class="' . esc_attr($item_class) . '">'
							. wp_kses($item->title, array(
								'span' => array('class' => array()), // Allow <span> tags with class attribute
								'a' => array( // Allow <a> tags with specified attributes
									'href' => array(),
									'title' => array(),
									'class' => array(),
								),
								'img' => array( // Allow <img> tags with specified attributes
									'src' => array(),
									'alt' => array(),
									'title' => array(),
									'width' => array(),
									'height' => array(),
									'class' => array(),
								),
								'i' => array('class' => array()), // Allow <i> tags with class attribute for icons
							));


				if ( $depth > 0 ) {
					if ( 'inline' === $settings['menu_items_submenu_position'] ) {
						$output .='<i class="crt-sub-icon fas" aria-hidden="true"></i>';
					} else {
						$output .='<i class="crt-sub-icon fas crt-sub-icon-rotate" aria-hidden="true"></i>';
					}
				} else {
					if ( 'absolute' === $settings['menu_items_submenu_position'] ) {
						$output .='<i class="crt-sub-icon fas crt-sub-icon-rotate" aria-hidden="true"></i>';
					} else {
						$output .='<i class="crt-sub-icon fas" aria-hidden="true"></i>';
					}
				}

				$output .='</a>';		
			}
		}

		return $output;
	}

	protected function render() {
		$available_menus = $this->get_available_menus();
	
		if ( ! $available_menus ) {
			return;
		}

		// Get Settings
		$settings = $this->get_active_settings();

		$args = [
			'echo' => false,
			'menu' => $settings['menu_select'],
			'menu_class' => 'crt-nav-menu',
			'menu_id' => 'menu-'. $this->get_nav_menu_index() .'-'. $this->get_id(),
			'container' => '',
			'fallback_cb' => '__return_empty_string',
		];

		// Custom Menu Items
		add_filter( 'walker_nav_menu_start_el', [ $this, 'custom_menu_items' ], 10, 4 );

		// Add Custom Filters
		add_filter( 'nav_menu_link_attributes', [ $this, 'custom_menu_item_classes' ], 10, 4 );
		add_filter( 'nav_menu_submenu_css_class', [ $this, 'custom_sub_menu_class' ] );
		add_filter( 'nav_menu_item_id', '__return_empty_string' );

		// Generate Menu HTML
		$menu_html = wp_nav_menu( $args );

		// Generate Mobile Menu HTML
		$args['menu_id'] 	= 'mobile-menu-'. $this->get_nav_menu_index() .'-'. $this->get_id();
		$args['menu_class'] = 'crt-mobile-nav-menu';
		$moible_menu_html 	= wp_nav_menu( $args );

		// Remove Custom Filters
		remove_filter( 'nav_menu_link_attributes', [ $this, 'custom_menu_item_classes' ] );
		remove_filter( 'nav_menu_submenu_css_class', [ $this, 'custom_sub_menu_class' ] );
		remove_filter( 'walker_nav_menu_start_el', [ $this, 'custom_menu_items' ] );
		remove_filter( 'nav_menu_item_id', '__return_empty_string' );

		if ( empty( $menu_html ) ) {
			return;
		}

		// Main Menu
		echo '<nav class="crt-nav-menu-container crt-nav-menu-'. esc_attr($settings['menu_layout']) .'" data-trigger="'. esc_attr($settings['menu_items_submenu_trigger']) .'">';
			echo ''. $menu_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</nav>';

		// Mobile Menu
		echo '<nav class="crt-mobile-nav-menu-container">';

			// Toggle Button
			echo '<div class="crt-mobile-toggle-wrap">';
				echo '<div class="crt-mobile-toggle">';
					if ( $settings['toggle_btn_style'] === 'hamburger' ) {
						echo '<span class="crt-mobile-toggle-line"></span>';
						echo '<span class="crt-mobile-toggle-line"></span>';
						echo '<span class="crt-mobile-toggle-line"></span>';
					} else {
						echo '<span class="crt-mobile-toggle-text">'. esc_html($settings['toggle_btn_txt_1']) .'</span>';
						echo '<span class="crt-mobile-toggle-text">'. esc_html($settings['toggle_btn_txt_2']) .'</span>';
					}
				echo '</div>';
			echo '</div>';

			// Menu
			echo ''. $moible_menu_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo '</nav>';
	}
	
}