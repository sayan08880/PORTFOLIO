<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Sharing_Buttons extends Widget_Base {
	
	public function get_name() {
		return 'crt-sharing-buttons';
	}

	public function get_title() {
		return esc_html__( 'Sharing Buttons', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-share';
	}

	public function get_categories() {
        return ['crt_manage_single'];
    }

	public function get_keywords() {
		return [ 'social sharing', 'sharing buttons', ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://crthemes.com/contact/?ref=rea-plugin-panel-social-sharing-buttons-help-btn';
    		return 'https://crthemes.com/contact';
    }

	public function add_repeater_args_sharing_custom_label() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_control_sharing_show_icon() {
        $this->add_control(
            'sharing_show_icon',
            [
                'label' => esc_html__( 'Show Icon', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'center',
                    'yes' => 'left'
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-sharing-buttons .crt-sharing-label' => 'text-align: {{VALUE}};',
                ],
                'render_type' => 'template'
            ]
        );
    }

	public function add_control_sharing_columns() {
        $this->add_responsive_control(
            'sharing_columns',
            [
                'label' => esc_html__( 'Columns', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '0' => esc_html__( 'Auto', 'crt-manage' ),
                    '1' => esc_html__( '1', 'crt-manage' ),
                    '2' => esc_html__( '2', 'crt-manage' ),
                    '3' => esc_html__( '3', 'crt-manage' ),
                    '4' => esc_html__( '4', 'crt-manage' ),
                    '5' => esc_html__( '5', 'crt-manage' ),
                    '6' => esc_html__( '6', 'crt-manage' ),
                ],
                'default' => '0',
                'prefix_class' => 'elementor-grid%s-',
            ]
        );
	}

	public function add_control_sharing_show_label() {
        $this->add_control(
            'sharing_show_label',
            [
                'label' => esc_html__( 'Show Label', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
    }

	public function add_control_sharing_icon_border_radius() {
        $this->add_control(
            'sharing_icon_border_radius',
            [
                'label' => esc_html__( 'Icon Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
    }

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_sharing_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'sharing_icon',
			[
				'label' => esc_html__( 'Network', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fab fa-facebook-f' => 'Facebook',
					'fab fa-twitter' => 'Twitter',
					'fab fa-linkedin-in' => 'Linkedin',
					'fab fa-pinterest-p' => 'Pinterest',
					'fab fa-reddit' => 'Reddit',
					'fab fa-tumblr' => 'Tumblr',
					'fab fa-digg' => 'Digg',
					'fab fa-xing' => 'Xing',
					'fab fa-stumbleupon' => 'Stumbleupon',
					'fab fa-vk' => 'vKontakte',
					'fab fa-odnoklassniki' => 'Odnoklassniki',
					'fab fa-get-pocket' => 'Pocket',
					'fab fa-skype' => 'Skype',
					'fab fa-whatsapp' => 'WhatsApp',
					'fab fa-telegram' => 'Telegram',
					'fab fa-delicious' => 'Delicious',
					'fas fa-envelope' => 'Email',
					'fas fa-print' => 'Print',
				],
				'default' => 'fab fa-facebook-f',
			]
		);

		$repeater->add_control( 'sharing_custom_label', $this->add_repeater_args_sharing_custom_label() );

		$repeater->add_control(
			'show_whatsapp_title',
			[
				'label' => esc_html__( 'Show Title', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'default' => 'yes',
				'condition' => [
					'sharing_icon' => 'fab fa-whatsapp'
				]
			]
		);

		$repeater->add_control(
			'show_whatsapp_excerpt',
			[
				'label' => esc_html__( 'Show Excerpt', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'sharing_icon' => 'fab fa-whatsapp'
				]
			]
		);

		$this->add_control(
			'sharing_buttons',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'sharing_icon' => 'fab fa-facebook-f',
					],
					[
						'sharing_icon' => 'fab fa-twitter',
					],
					[
						'sharing_icon' => 'fab fa-linkedin-in',
					],
				],
				'title_field' => '<i class="{{{ sharing_icon }}}"></i> Social Icon',
			]
		);


		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Layout ----------
		$this->start_controls_section(
			'section_sharing_layout',
			[
				'label' => esc_html__( 'Layout', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_sharing_columns();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'sharing-buttons', 'sharing_columns', ['pro-3', 'pro-4', 'pro-5', 'pro-6'] );

		$this->add_control_sharing_show_icon();

		$this->add_control_sharing_show_label();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		
		// Tab: Styles ==============
		// Section: Layout ----------
		$this->start_controls_section(
			'section_styles_sharing_layout',
			[
				'label' => esc_html__( 'Layout', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sharing_gutter_hr',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:not(.elementor-grid-0) .elementor-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-grid-0 .crt-sharing-buttons a' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .crt-sharing-buttons a' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .crt-sharing-buttons a' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}}.elementor-grid-0 .elementor-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .elementor-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .elementor-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
				],
			]
		);

		$this->add_responsive_control(
			'sharing_gutter_vr',
			[
				'label' => esc_html__( 'Vertical Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:not(.elementor-grid-0) .elementor-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-grid-0 .crt-sharing-buttons a' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .crt-sharing-buttons a' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .crt-sharing-buttons a' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sharing_icon_width',
			[
				'label' => esc_html__( 'Icon Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 45,
				],
				'range' => [
					'px' => [
						'min' => 15,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon i' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'sharing_icon_height',
			[
				'label' => esc_html__( 'Icon Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 45,
				],
				'range' => [
					'px' => [
						'min' => 15,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon svg' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-label' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sharing_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 18,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'sharing_label_spacing',
			[
				'label' => esc_html__( 'Label Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-label' => 'padding: 0 {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'sharing_show_label' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sharing_label_typography',
				'selector' => '{{WRAPPER}} .crt-sharing-buttons .crt-sharing-label',
				'condition' => [
					'sharing_show_label' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'sharing_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
				],
				'separator' => 'before'
			]
		);

		$this->add_control_sharing_icon_border_radius();

		$this->add_control(
			'sharing_button_border_radius',
			[
				'label' => esc_html__( 'Button Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'sharing_button_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
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
					'space-between' => [
						'title' => esc_html__( 'Justified', 'crt-manage' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons' => 'justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Styles ==============
		// Section: Styles ----------
		$this->start_controls_section(
			'section_styles_sharing_styles',
			[
				'label' => esc_html__( 'Styles', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sharing_custom_colors',
			[
				'label' => esc_html__( 'Use Custom Colors', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'after'
			]
		);

		$this->add_control(
			'sharing_icon_bg_tr',
			[
				'label' => esc_html__( 'Icon Background Color', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'sharing_custom_colors' => '',
				]
			]
		);

		$this->add_control(
			'sharing_label_bg',
			[
				'label' => esc_html__( 'Label Background Color', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'sharing_show_label' => 'yes',
					'sharing_custom_colors' => '',
				]
			]
		);

		$this->add_control(
			'sharing_label_bg_tr',
			[
				'label' => esc_html__( 'Label Background Transparency', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'' => '1',
					'yes' => '0.92'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-label' => 'opacity: {{VALUE}};',
				],
				'condition' => [
					'sharing_show_label' => 'yes',
					'sharing_custom_colors' => '',
					'sharing_label_bg' => 'yes',
				]
			]
		);

		$this->start_controls_tabs(
			'tabs_sharing_custom_colors', [
				'condition' => [
					'sharing_custom_colors' => 'yes',
				]
			]
		);

		$this->start_controls_tab(
			'tab_sharing_custom_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'sharing_icon_color',
			[
				'label'  => esc_html__( 'Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon svg' => 'fill: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'sharing_icon_bg_color',
			[
				'label'  => esc_html__( 'Icon Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon i' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon svg' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_label_color',
			[
				'label'  => esc_html__( 'Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-label' => 'color: {{VALUE}}',
				],
				'condition' => [
					'sharing_show_label' => 'yes',
				]
			]
		);

		$this->add_control(
			'sharing_label_bg_color',
			[
				'label'  => esc_html__( 'Label Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-label' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'sharing_show_label' => 'yes',
				]
			]
		);

		$this->add_control(
			'sharing_label_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_sharing_custom_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'sharing_icon_color_hr',
			[
				'label'  => esc_html__( 'Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_icon_bg_color_hr',
			[
				'label'  => esc_html__( 'Icon Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon:hover i' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon:hover svg' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_label_color_hr',
			[
				'label'  => esc_html__( 'Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon:hover .crt-sharing-label' => 'color: {{VALUE}}',
				],
				'condition' => [
					'sharing_show_label' => 'yes',
				]
			]
		);

		$this->add_control(
			'sharing_label_bg_color_hr',
			[
				'label'  => esc_html__( 'Label Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon:hover .crt-sharing-label' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'sharing_show_label' => 'yes',
				]
			]
		);

		$this->add_control(
			'sharing_label_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'sharing_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-sharing-buttons .crt-sharing-icon span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		$class  = '' === $settings['sharing_custom_colors'] ? ' crt-sharing-official' : '';
		$class .= '' === $settings['sharing_show_label'] ? ' crt-sharing-label-off' : '';
		$class .= '' === $settings['sharing_icon_bg_tr'] ? ' crt-sharing-icon-tr' : '';
		$class .= '' === $settings['sharing_label_bg'] ? ' crt-sharing-label-tr' : '';

		echo '<div class="crt-sharing-buttons elementor-grid'. esc_attr($class) .'">';
		
		$count = 0;
		foreach( $settings['sharing_buttons'] as $button ) {
			if ( Utilities::is_new_free_user() && $count === 4 ) {
				break;
			}

			$sharing_icon = str_replace( 'fab ', '', $button['sharing_icon'] );
			$sharing_icon = str_replace( 'fas ', '', $sharing_icon );
			$sharing_icon = str_replace( 'fa-', '', $sharing_icon );

			$args = [
				'icons' => $settings['sharing_show_icon'],
				'network' => $sharing_icon,
				'labels' => $settings['sharing_show_label'],
				'custom_label' => $button['sharing_custom_label'],
				'tooltip' => 'no',
				'url' => esc_url( get_the_permalink() ),
				'title' => esc_html( get_the_title() ),
				'text' => esc_html( get_the_excerpt() ),
				'image' => esc_url( get_the_post_thumbnail_url() )
			];

			if ( isset($button['show_whatsapp_excerpt']) && isset($button['show_whatsapp_title']) ) {
				$args['show_whatsapp_title'] = $button['show_whatsapp_title'];
				$args['show_whatsapp_excerpt'] = $button['show_whatsapp_excerpt'];
			}

			echo '<div class="elementor-grid-item">';
				echo Utilities::get_post_sharing_icon( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';

			$count++;
		}

		echo '</div>';
	}
	
}