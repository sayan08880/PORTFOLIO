<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Mailchimp extends Widget_Base {
	
	public function get_name() {
		return 'crt-mailchimp';
	}

	public function get_title() {
		return esc_html__( 'Mailchimp', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-mailchimp';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'subscribe', 'subscription form', 'email subscription', 'sing up form', 'singup form', 'newsletter', 'mailchimp' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://crthemes.com/contact/?ref=rea-plugin-panel-mailchimp-subscription-help-btn';
    		return 'https://crthemes.com/contact';
    }

    public function get_script_depends() {
        return [ 'crt-mailchimp' ];
    }

	public function add_control_clear_fields_on_submit() {
		$this->add_control(
			'clear_fields_on_submit',
			[
				'label' => __( 'Clear Fields On Submit', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => ''
			]
		);
	}

	public function add_control_extra_fields() {
		$this->add_control(
			'extra_fields',
			[
				'label' => __( 'Show Extra Fields', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => ''
			]
		);
	}

    public function add_control_name_label() {
        $this->add_control(
            'name_label',
            [
                'label' => esc_html__( 'Name Label', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Name',
                'condition' => [
                    'extra_fields' => 'yes',
                ]
            ]
        );
    }
	
	public function add_control_name_placeholder() {
        $this->add_control(
            'name_placeholder',
            [
                'label' => esc_html__( 'Name Placeholder', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Name',
                'condition' => [
                    'extra_fields' => 'yes',
                ]
            ]
        );
    }
	
	public function add_control_last_name_label() {
        $this->add_control(
            'last_name_label',
            [
                'label' => esc_html__( 'Last Name Label', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Last Name',
                'condition' => [
                    'extra_fields' => 'yes',
                ]
            ]
        );
    }
	
	public function add_control_last_name_placeholder() {
        $this->add_control(
            'last_name_placeholder',
            [
                'label' => esc_html__( 'L.Name Placeholder', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Last Name',
                'condition' => [
                    'extra_fields' => 'yes',
                ]
            ]
        );
    }

	public function add_control_phone_number_label_and_placeholder() {
        $this->add_control(
            'phone_number_label',
            [
                'label' => esc_html__( 'Phone Label', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Phone Number',
                'condition' => [
                    'extra_fields' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'phone_number_placeholder',
            [
                'label' => esc_html__( 'Phone Placeholder', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'extra_fields' => 'yes',
                ]
            ]
        );
    }

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Settings ----------
		$this->start_controls_section(
			'section_mailchimp_settings',
			[
				'label' => esc_html__( 'Settings', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'maichimp_audience',
			[
				'label' => esc_html__( 'Select Audience', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'def',
				'options' => Utilities::get_mailchimp_lists(),
			]
		);

		if ( '' == get_option('crt_mailchimp_api_key') ) {
			$this->add_control(
				'mailchimp_key_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( 'Navigate to <strong><a href="%s" target="_blank">Dashboard > %s > Settings</a></strong> to set up <strong>MailChimp API Key</strong>.', 'crt-manage' ), admin_url( 'admin.php?page=crt-manage&tab=crt_tab_settings' ), Utilities::get_plugin_name() ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_mailchimp_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_clear_fields_on_submit();

		$this->add_control(
			'show_form_header',
			[
				'label' => esc_html__( 'Show Form Header', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'form_title',
			[
				'label' => esc_html__( 'Form Title', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Join the family!',
				'condition' => [
					'show_form_header' => 'yes',
				]
			]
		);

		$this->add_control(
			'form_description',
			[
				'label' => esc_html__( 'Form Description', 'crt-manage' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Sign up for a Newsletter.',
				'condition' => [
					'show_form_header' => 'yes',
				]
			]
		);

		$this->add_control(
			'form_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-envelope',
					'library' => 'fa-regular',
				],
				'condition' => [
					'show_form_header' => 'yes',
				]
			]
		);

		$this->add_control(
			'form_icon_display',
			[
				'label' => esc_html__( 'Icon Position', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'crt-manage' ),
					'left' => esc_html__( 'Left', 'crt-manage' ),
				],
				'selectors_dictionary' => [
					'top' => 'display: block;',
					'left' => 'display: inline; margin-right: 5px;'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-header i' => '{{VALUE}}',
					'{{WRAPPER}} .crt-mailchimp-header svg' => '{{VALUE}}'
				],
				'condition' => [
					'show_form_header' => 'yes',
				]
			]
		);

		$this->add_control(
			'email_label',
			[
				'label' => esc_html__( 'Email Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Email',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'email_placeholder',
			[
				'label' => esc_html__( 'Email Placeholder', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'sample@mail.com',
			]
		);

		$this->add_control(
			'subscribe_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Subscribe',
			]
		);

		$this->add_control(
			'subscribe_button_loading_text',
			[
				'label' => esc_html__( 'Button Loading Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Subscribing...',
				'separator' => 'after'
			]
		);

		$this->add_control(
			'success_message',
			[
				'label' => esc_html__( 'Success Message', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'You have been successfully Subscribed!',
			]
		);

		$this->add_control(
			'error_message',
			[
				'label' => esc_html__( 'Error Message', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Ops! Something went wrong, please try again.',
			]
		);

		$this->add_control_extra_fields();

		$this->add_control_name_label();

		$this->add_control_name_placeholder();

		$this->add_control_last_name_label();

		$this->add_control_last_name_placeholder();

		$this->add_control_phone_number_label_and_placeholder();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles ====================
		// Section: Container --------
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'container_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hr',
				'options' => [
					'hr' => esc_html__( 'Horizontal', 'crt-manage' ),
					'vr' => esc_html__( 'Vertical', 'crt-manage' ),
				],
				'prefix_class' => 'crt-mailchimp-layout-',
				'render_type' => 'template',
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'container_background',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '',
					],
				],
				'selector' => '{{WRAPPER}} .crt-mailchimp-form'
			]
		);

		$this->add_control(
			'container_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-form' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .crt-mailchimp-form',
			]
		);

		$this->add_control(
			'container_border_type',
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
					'{{WRAPPER}} .crt-mailchimp-form' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'container_border_width',
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
					'{{WRAPPER}} .crt-mailchimp-form' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'container_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
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
					'{{WRAPPER}} .crt-mailchimp-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'container_radius',
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
					'{{WRAPPER}} .crt-mailchimp-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Title & Description
		$this->start_controls_section(
			'section_style_header',
			[
				'label' => esc_html__( 'Form Header', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'header_align',
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
					'{{WRAPPER}} .crt-mailchimp-header' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'header_align_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'header_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-header i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-mailchimp-header svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'header_icon_size',
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
					'size' => 28,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-header i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-mailchimp-header svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'header_title_color',
			[
				'label' => esc_html__( 'Title Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#424242',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-header h3' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_title_typography',
				'selector' => '{{WRAPPER}} .crt-mailchimp-header h3',
			]
		);

		$this->add_control(
			'header_description_color',
			[
				'label' => esc_html__( 'Description Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#606060',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-header p' => 'color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_description_typography',
				'selector' => '{{WRAPPER}} .crt-mailchimp-header p',
			]
		);

		$this->add_responsive_control(
			'header_title_distance',
			[
				'label' => esc_html__( 'Title Bottom Distance', 'crt-manage' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-header h3' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'header_desc_distance',
			[
				'label' => esc_html__( 'Description Bottom Distance', 'crt-manage' ),
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Labels -----------
		$this->start_controls_section(
			'section_style_labels',
			[
				'label' => esc_html__( 'Labels', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'labels_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#818181',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields label' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'labels_typography',
				'selector' => '{{WRAPPER}} .crt-mailchimp-fields label',
			]
		);

		$this->add_responsive_control(
			'labels_spacing',
			[
				'label' => esc_html__( 'Spacing', 'crt-manage' ),
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
					'size' => 4,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Fields -----------
		$this->start_controls_section(
			'section_style_inputs',
			[
				'label' => esc_html__( 'Fields', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_forms_inputs_style' );

		$this->start_controls_tab(
			'tab_inputs_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#474747',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields input' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ADADAD',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields input::placeholder' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_background_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields input' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields input' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_inputs_hover',
			[
				'label' => esc_html__( 'Focus', 'crt-manage' ),
			]
		);

		$this->add_control(
			'input_color_fc',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields input:focus' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_placeholder_color_fc',
			[
				'label' => esc_html__( 'Placeholder Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields input:focus::placeholder' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_background_color_fc',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields input:focus' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_border_color_fc',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields input:focus' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .crt-mailchimp-fields input',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'input_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields input' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'selector' => '{{WRAPPER}} .crt-mailchimp-fields input',
			]
		);

		$this->add_responsive_control(
			'input_height',
			[
				'label' => esc_html__( 'Input Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields input' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'input_spacing',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-mailchimp-layout-vr .crt-mailchimp-email, {{WRAPPER}}.crt-mailchimp-layout-vr .crt-mailchimp-first-name, {{WRAPPER}}.crt-mailchimp-layout-vr .crt-mailchimp-last-name, {{WRAPPER}}.crt-mailchimp-layout-vr .crt-mailchimp-phone-number' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-mailchimp-layout-hr .crt-mailchimp-email, {{WRAPPER}}.crt-mailchimp-layout-hr .crt-mailchimp-first-name, {{WRAPPER}}.crt-mailchimp-layout-hr .crt-mailchimp-last-name, {{WRAPPER}}.crt-mailchimp-layout-hr .crt-mailchimp-phone-number' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'input_border_type',
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
					'{{WRAPPER}} .crt-mailchimp-fields input' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_width',
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
					'{{WRAPPER}} .crt-mailchimp-fields input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'input_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 15,
					'bottom' => 0,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-fields input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Button -----------
		$this->start_controls_section(
			'section_style_subscribe_btn',
			[
				'label' => esc_html__( 'Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'subscribe_btn_align',
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
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}}.crt-mailchimp-layout-vr .crt-mailchimp-subscribe' => 'align-self: {{VALUE}};',
				],
				'condition' => [
					'container_align' => 'vr'
				]
			]
		);

		$this->add_control(
			'subscribe_btn_divider1',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'container_align' => 'vr'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_subscribe_btn_style' );

		$this->start_controls_tab(
			'tab_subscribe_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'subscribe_btn_bg_color',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#e55b5b',
					],
				],
				'selector' => '{{WRAPPER}} .crt-mailchimp-subscribe-btn'
			]
		);

		$this->add_control(
			'subscribe_btn_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-subscribe-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'subscribe_btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E6E2E2',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-subscribe-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'subscribe_btn_box_shadow',
				'selector' => '{{WRAPPER}} .crt-mailchimp-subscribe-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_subscribe_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'subscribe_btn_bg_color_hr',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#4A45D2',
					],
				],
				'selector' => '{{WRAPPER}} .crt-mailchimp-subscribe-btn:hover'
			]
		);

		$this->add_control(
			'subscribe_btn_color_hr',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-subscribe-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'subscribe_btn_border_color_hr',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-subscribe-btn:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'subscribe_btn_box_shadow_hr',
				'selector' => '{{WRAPPER}} .crt-mailchimp-subscribe-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'subscribe_btn_divider2',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'subscribe_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-subscribe-btn' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subscribe_btn_typography',
				'selector' => '{{WRAPPER}} .crt-mailchimp-subscribe-btn'
			]
		);

		$this->add_responsive_control(
			'subscribe_btn_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 300,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 130,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-subscribe' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'subscribe_btn_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
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
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-subscribe-btn' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'subscribe_btn_spacing',
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
					'{{WRAPPER}}.crt-mailchimp-layout-vr .crt-mailchimp-subscribe-btn' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'container_align' => 'vr'
				]
			]
		);

		$this->add_control(
			'subscribe_btn_border_type',
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
					'{{WRAPPER}} .crt-mailchimp-subscribe-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'subscribe_btn_border_width',
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
					'{{WRAPPER}} .crt-mailchimp-subscribe-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'subscribe_btn_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'subscribe_btn_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-subscribe-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Message ----------
		$this->start_controls_section(
			'section_style_message',
			[
				'label' => esc_html__( 'Message', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'success_message_color',
			[
				'label' => esc_html__( 'Success Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-success-message' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'error_message_color',
			[
				'label' => esc_html__( 'Error Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF348B',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-error-message' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'message_color_bg',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-mailchimp-message' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'message_typography',
				'selector' => '{{WRAPPER}} .crt-mailchimp-message',
			]
		);

		$this->add_responsive_control(
			'message_padding',
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
					'{{WRAPPER}} .crt-mailchimp-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'message_spacing',
			[
				'label' => esc_html__( 'Spacing', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-mailchimp-message' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();
	}

    public function render_pro_element_extra_fields() {
        // Get Settings
        $settings = $this->get_settings();

        if ( 'yes' === $settings['extra_fields'] ) :
            if ( '' !== $settings['name_label'] || '' !== $settings['name_placeholder'] ) : ?>
                <div class="crt-mailchimp-first-name">
                    <?php echo '' !== $settings['name_label'] ? '<label>'. esc_html($settings['name_label']) .'</label>' : ''; ?>
                    <input type="text" name="crt_mailchimp_firstname" placeholder="<?php echo esc_attr($settings['name_placeholder']); ?>">
                </div>
            <?php
            endif;

            if ( '' !== $settings['last_name_label'] || '' !== $settings['last_name_placeholder'] ) : ?>
                <div class="crt-mailchimp-last-name">
                    <?php echo '' !== $settings['last_name_label'] ? '<label>'. esc_html($settings['last_name_label']) .'</label>' : ''; ?>
                    <input type="text" name="crt_mailchimp_lastname" placeholder="<?php echo esc_attr($settings['last_name_placeholder']); ?>">
                </div>

            <?php
            endif;

            if ( '' !== $settings['phone_number_label'] || '' !== $settings['phone_number_placeholder'] ) : ?>
                <div class="crt-mailchimp-phone-number">
                    <?php echo '' !== $settings['phone_number_label'] ? '<label>'. esc_html($settings['phone_number_label']) .'</label>' : ''; ?>
                    <input type="tel" name="crt_mailchimp_phone_number" placeholder="<?php echo esc_attr($settings['phone_number_placeholder']); ?>">
                </div>

            <?php
            endif;
        endif;
    }

    protected function render() {
		// Get Settings
		$settings = $this->get_settings();
        $clear_fields_on_submit = esc_attr($settings['clear_fields_on_submit']);

		?>

		<form class="crt-mailchimp-form" id="crt-mailchimp-form-<?php echo esc_attr( $this->get_id() ); ?>" method="POST" data-list-id="<?php echo esc_attr($settings['maichimp_audience']); ?>" data-clear-fields="<?php echo $clear_fields_on_submit; ?>">
			<!-- Form Header -->
			<?php if ( 'yes' === $settings['show_form_header'] ) : ?>
			<div class="crt-mailchimp-header">
				<?php $form_icon = '' !== $settings['form_icon']['value'] ? '<i class="'. esc_attr($settings['form_icon']['value']) .'"></i>' : ''; ?>
				<h3>
					<?php
                        echo ''. $form_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo esc_html($settings['form_title']);
					?>
				</h3>
				<p><?php echo wp_kses( $settings['form_description'], [ 'br' => [], 'em' => [], 'strong' => [], ] ); ?></p>
			</div>
			<?php endif; ?>

			<div class="crt-mailchimp-fields">
				<!-- Email Input -->
				<div class="crt-mailchimp-email">
					<?php echo '' !== $settings['email_label'] ? '<label>'. esc_html($settings['email_label']) .'</label>' : ''; ?>
					<input type="email" name="crt_mailchimp_email" placeholder="<?php echo esc_attr($settings['email_placeholder']); ?>" required="required">
				</div>

				<!-- Extra Fields -->
				<?php $this->render_pro_element_extra_fields(); ?>

				<!-- Subscribe Button -->
				<div class="crt-mailchimp-subscribe">
					<button type="submit" id="crt-subscribe-<?php echo esc_attr( $this->get_id() ); ?>" class="crt-mailchimp-subscribe-btn" data-loading="<?php echo esc_attr($settings['subscribe_button_loading_text']); ?>">
				  		<?php echo esc_html($settings['subscribe_btn_text']); ?>
					</button>
				</div>
			</div>

			<!-- Success/Error Message -->
			<div class="crt-mailchimp-message">
				<span class="crt-mailchimp-success-message"><?php echo esc_html($settings['success_message']); ?></span>
				<span class="crt-mailchimp-error-message"><?php echo esc_html($settings['error_message']); ?></span>
			</div>
		</form>

		<?php
	}
	
}