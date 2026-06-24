<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Content_Toggle extends Widget_Base {
		
	public function get_name() {
		return 'crt-content-toggle';
	}

	public function get_title() {
		return esc_html__( 'Content Toggle', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-toggle';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'content toggle', 'content switcher', 'pricing toggle', 'toggle price plan', 'pricing table' ];
	}

	public function get_style_depends() {
		return [ 'crt-animations-css' ];
	}

    public function get_script_depends() {
        return [ 'crt-content-toggle' ];
    }

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://crthemes.com/contact/?ref=rea-plugin-panel-content-toggle-help-btn';
    		return 'https://crthemes.com/contact';
    }

	public function add_control_switcher_style() {
        $this->add_control(
            'switcher_style',
            [
                'label' => esc_html__( 'Switcher Style', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'dual',
                'options' => [
                    'dual' => esc_html__( 'Dual', 'crt-manage' ),
                    'multi' => esc_html__( 'Multi', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-switcher-style-',
                'render_type' => 'template',
            ]
        );
	}

    public function add_repeater_switcher_items() {
        $repeater = new Repeater();

        $repeater->add_control(
            'switcher_label',
            [
                'label' => esc_html__( 'Label', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Label 1',
            ]
        );

        $repeater->add_control(
            'switcher_show_icon',
            [
                'label' => esc_html__( 'Show Icon', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'switcher_icon',
            [
                'label' => esc_html__( 'Select Icon', 'crt-manage' ),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-angle-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'switcher_show_icon' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'switcher_content_type',
            [
                'label' => esc_html__( 'Content Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'editor',
                'options' => [
                    'template' => esc_html__( 'Elementor Template', 'crt-manage' ),
                    'editor' => esc_html__( 'Editor', 'crt-manage' ),
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'switcher_content',
            [
                'label' => esc_html__( 'Content', 'crt-manage' ),
                'type' => Controls_Manager::WYSIWYG,
                'placeholder' => esc_html__( 'Tab Content', 'crt-manage' ),
                'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis. Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur.',
                'condition' => [
                    'switcher_content_type' => 'editor',
                ],
            ]
        );

        $repeater->add_control(
            'switcher_select_template',
            [
                'label'	=> esc_html__( 'Select Template', 'crt-manage' ),
                'type' => 'crt-ajax-select2',
                'options' => 'ajaxselect2/get_elementor_templates',
                'label_block' => true,
                'condition' => [
                    'switcher_content_type' => 'template',
                ],
            ]
        );

        $this->add_control(
            'switcher_items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ switcher_label }}}',
                'condition' => [
                    'switcher_style' => 'multi',
                ],
            ]
        );
    }

    public function add_control_switcher_label_style() {
        $this->add_control(
            'switcher_label_style',
            [
                'label' => esc_html__( 'Label Position', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'outer',
                'options' => [
                    'inner' => esc_html__( 'Inside', 'crt-manage' ),
                    'outer' => esc_html__( 'Outside', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-switcher-label-style-',
                'render_type' => 'template',
                'condition' => [
                    'switcher_style' => 'dual',
                ],
            ]
        );
    }

    public function add_section_settings() {
        $this->start_controls_section(
            'section_settings',
            [
                'label' => esc_html__( 'Settings', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'active_switcher',
            [
                'label' => esc_html__( 'Active Switcher Index', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'label_block' => false,
                'default' => 1,
                'min' => 1,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'content_animation',
            [
                'label' => esc_html__( 'Content Animation', 'crt-manage' ),
                'type' => 'crt-animations-alt',
                'default' => 'none',
                'separator' => 'before',
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
                'default' => 1,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-switcher-content-inner' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
                    '{{WRAPPER}} .crt-tabs-content-wrap' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
                ],
                'condition' => [
                    'content_animation!' => 'none',
                ],
            ]
        );

        $this->end_controls_section(); // End Controls Section
    }

    protected function register_controls() {

		// CSS Selectors
		$css_selector = [
			'general' => '> .elementor-widget-container > .crt-content-toggle',
			'control_container' => '> .elementor-widget-container > .crt-content-toggle > .crt-switcher-container',
			'control_outer' => '> .elementor-widget-container > .crt-content-toggle > .crt-switcher-container > .crt-switcher-outer',
			'control_wrap' => '> .elementor-widget-container > .crt-content-toggle > .crt-switcher-container > .crt-switcher-outer > .crt-switcher-wrap',
			'control_list' => '> .elementor-widget-container > .crt-content-toggle > .crt-switcher-container > .crt-switcher-outer > .crt-switcher-wrap > .crt-switcher',
			'control_bg' => '> .elementor-widget-container > .crt-content-toggle > .crt-switcher-container > .crt-switcher-outer > .crt-switcher-wrap > .crt-switcher-bg',
			'content_wrap' => '> .elementor-widget-container > .crt-content-toggle > .crt-switcher-content-wrap',
			'content_list' => '> .elementor-widget-container > .crt-content-toggle > .crt-switcher-content-wrap > .crt-switcher-content',
			'control_icon' => '.crt-switcher-icon',
		];

		if ( ! $this->has_widget_inner_wrapper() ) {
			$css_selector['general'] = '> .crt-content-toggle';
			$css_selector['control_container'] = '> .crt-content-toggle > .crt-switcher-container';
			$css_selector['control_outer'] = '>  .crt-content-toggle > .crt-switcher-container > .crt-switcher-outer';
			$css_selector['control_wrap'] = '> .crt-content-toggle > .crt-switcher-container > .crt-switcher-outer > .crt-switcher-wrap';
			$css_selector['control_list'] = '> .crt-content-toggle > .crt-switcher-container > .crt-switcher-outer > .crt-switcher-wrap > .crt-switcher';
			$css_selector['control_bg'] = '> .crt-content-toggle > .crt-switcher-container > .crt-switcher-outer > .crt-switcher-wrap > .crt-switcher-bg';
			$css_selector['content_wrap'] = '> .crt-content-toggle > .crt-switcher-content-wrap';
			$css_selector['content_list'] = '> .crt-content-toggle > .crt-switcher-content-wrap > .crt-switcher-content';
		}


		// Section: General ------------
		$this->start_controls_section(
			'section_switcher_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
			]
		);

		$this->add_control_switcher_style();


		$this->add_control_switcher_label_style();

		$this->add_control(
			'switcher_style_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_repeater_switcher_items();

		$this->start_controls_tabs( 'tab_switcher_settings' );

		$this->start_controls_tab(
			'tab_switcher_first_settings',
			[
				'label' => esc_html__( 'First', 'crt-manage' ),
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'switcher_first_label',
			[
				'label' => esc_html__( 'Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Annual',
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'switcher_first_show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'switcher_first_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'switcher_first_show_icon' => 'yes',
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
            'switcher_first_content_type',
            [
                'label' => esc_html__( 'Select Content Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'editor',
                'options' => [
                    'template' => esc_html__( 'Elementor Template', 'crt-manage' ),
                    'editor' => esc_html__( 'Editor', 'crt-manage' ),
                ],
				'separator' => 'before',
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
            ]
        );

		$this->add_control(
			'switcher_first_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'type' => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'crt-manage' ),
				'default' => 'Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis.',
				'condition' => [
					'switcher_first_content_type' => 'editor',
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'switcher_first_select_template',
			[
				'label'	=> esc_html__( 'Select Template', 'crt-manage' ),
				'type' => 'crt-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				'condition' => [
					'switcher_first_content_type' => 'template',
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_switcher_second_settings',
			[
				'label' => esc_html__( 'Second', 'crt-manage' ),
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'switcher_second_label',
			[
				'label' => esc_html__( 'Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Lifetime',
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'switcher_second_show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'switcher_second_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'switcher_second_show_icon' => 'yes',
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
            'switcher_second_content_type',
            [
                'label' => esc_html__( 'Select Content Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'editor',
                'options' => [
                    'template' => esc_html__( 'Elementor Template', 'crt-manage' ),
                    'editor' => esc_html__( 'Editor', 'crt-manage' ),
                ],
				'separator' => 'before',
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
            ]
        );

		$this->add_control(
			'switcher_second_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'type' => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'crt-manage' ),
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis. Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur.',
				'condition' => [
					'switcher_second_content_type' => 'editor',
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'switcher_second_select_template',
			[
				'label'	=> esc_html__( 'Select Template', 'crt-manage' ),
				'type' => 'crt-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				'condition' => [
					'switcher_second_content_type' => 'template',
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->add_section_settings();

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );


		// Styles
		// Section: Switcher ---------
		$this->start_controls_section(
			'section_style_switcher',
			[
				'label' => esc_html__( 'Switcher', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tab_style' );

		$this->start_controls_tab(
			'tab_normal_style',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->add_control(
			'switcher_color',
			[
				'label' => esc_html__( 'Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => 'color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);
		
		$this->add_control(
			'switcher_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_outer'] => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_active_style',
			[
				'label' => esc_html__( 'Active', 'crt-manage' ),
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->add_control(
			'switcher_active_color',
			[
				'label' => esc_html__( 'Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-switcher-active' => 'color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->add_control(
			'switcher_active_bg_color',
			[
				'label' => esc_html__( 'Handler Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_bg'] => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->start_controls_tabs( 'switcher_dual_style' );

		$this->start_controls_tab(
			'switcher_first_style',
			[
				'label' => esc_html__( 'First', 'crt-manage' ),
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'handler_first_color',
			[
				'label' => esc_html__( 'Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}}'. $css_selector['control_container'] .'[data-active-switcher*="1"] .crt-switcher-first' => 'color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'handler_first_bg_color',
			[
				'label' => esc_html__( 'Handler Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .'[data-active-switcher*="1"] .crt-switcher-bg' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'switcher_first_color',
			[
				'label' => esc_html__( 'Inactive Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#939393',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .'[data-active-switcher*="1"] .crt-switcher-second' => 'color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);
		
		$this->add_control(
			'switcher_first_bg_color',
			[
				'label' => esc_html__( 'Inactive Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .'[data-active-switcher*="1"] > .crt-switcher-outer' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_switcher_second_style',
			[
				'label' => esc_html__( 'Second', 'crt-manage' ),
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'handler_second_color',
			[
				'label' => esc_html__( 'Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}}'. $css_selector['control_container'] .'[data-active-switcher*="2"] .crt-switcher-second' => 'color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'handler_second_bg_color',
			[
				'label' => esc_html__( 'Handler Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .'[data-active-switcher*="2"] .crt-switcher-bg' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'switcher_second_color',
			[
				'label' => esc_html__( 'Inactive Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#939393',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .'[data-active-switcher*="2"] .crt-switcher-first' => 'color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->add_control(
			'switcher_second_bg_color',
			[
				'label' => esc_html__( 'Inactive Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .'[data-active-switcher*="2"] > .crt-switcher-outer' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'switcher_box_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'switcher_box_shadow',
				'selector' => '{{WRAPPER}} '. $css_selector['control_outer'],
			]
		);

		$this->add_control(
	        'switcher_typography_divider',
	        [
	            'type' => Controls_Manager::DIVIDER,
	            'style' => 'thick',
	        ]
	    );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'switcher_typography',
				'selector' => '{{WRAPPER}} .crt-switcher-label',
			]
		);

		$this->add_responsive_control(
			'switcher_outer_label_distance',
			[
				'label' => esc_html__( 'Label Distance', 'crt-manage' ),
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .' > .crt-switcher-first'  => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_container'] .' > .crt-switcher-second'  => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
					'switcher_label_style' => ['outer', 'pro-in'],
				],
			]
		);

		$this->add_responsive_control(
			'switcher_width',
			[
				'label' => esc_html__( 'Wrapper Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_wrap'] => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'switcher_height',
			[
				'label' => esc_html__( 'Wrapper Height', 'crt-manage' ),
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_wrap'] => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'handler_offset',
			[
				'label' => esc_html__( 'Wrapper Padding', 'crt-manage' ),
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
					'{{WRAPPER}} '. $css_selector['control_wrap'] => 'margin: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'handler_width',
			[
				'label' => esc_html__( 'Handler Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_bg'] => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_list'] .'.crt-switcher-active' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'switcher_style' => ['dual', 'multi'],
					'switcher_label_style' => ['outer', 'pro-in'],
				],
			]
		);

		$this->add_control(
			'switcher_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'{{WRAPPER}} '. $css_selector['control_outer'] => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_bg'] => 'border-radius: calc({{SIZE}}{{UNIT}} - {{switcher_border_width.SIZE}}{{switcher_border_width.UNIT}});',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'switcher_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'switcher_border_type',
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
					'{{WRAPPER}} '. $css_selector['control_outer'] => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'switcher_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
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
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_outer'] => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'switcher_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'switcher_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e5e5e5',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_outer'] => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'switcher_icon_section',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'switcher_icon_position',
			[
				'label' => esc_html__( 'Position', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'crt-switcher-icon-position-',
			]
		);

		$this->add_responsive_control(
			'switcher_icon_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-switcher-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'switcher_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-switcher-icon-position-left'. $css_selector['control_container'] .' > .crt-switcher-inner > .crt-switcher-label ~ '. $css_selector['control_icon']  => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-switcher-icon-position-left'. $css_selector['control_list'] .' > .crt-switcher-inner > .crt-switcher-label ~ '. $css_selector['control_icon']  => 'margin-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.crt-switcher-icon-position-right'. $css_selector['control_container'] .' > .crt-switcher-inner > .crt-switcher-label ~ '. $css_selector['control_icon']  => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-switcher-icon-position-right'. $css_selector['control_list'] .' > .crt-switcher-inner > .crt-switcher-label ~ '. $css_selector['control_icon']  => 'margin-left: {{SIZE}}{{UNIT}};',
				],
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
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['content_list'] => 'color: {{VALUE}};',
				],
				'separator' => 'before',
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
				],
			]
		);

		$this->add_control(
	        'content_typography_divider',
	        [
	            'type' => Controls_Manager::DIVIDER,
	            'style' => 'thick',
	        ]
	    );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} '. $css_selector['content_list'],
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
				'size_units' => [ 'px' ],
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

		$this->add_control(
	        'content_box_shadow_divider',
	        [
	            'type' => Controls_Manager::DIVIDER,
	            'style' => 'thick',
	        ]
	    );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} '. $css_selector['content_wrap'],
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	public function crt_switcher_template( $id ) {
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
		
		$type = get_post_meta(get_the_ID(), '_crt_template_type', true) || get_post_meta($id, '_elementor_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

    public function crt_multi_switcher() {

        $settings = $this->get_settings();

        $switcher = $this->get_settings_for_display( 'switcher_items' );

        $active_switcher = $settings['active_switcher'];

//        if ( ! defined('WPR_ADDONS_PRO_VERSION') ) {
//            $active_switcher = 1;
//        }

        if ( $active_switcher > sizeof( $switcher ) ) {
            $active_switcher = sizeof( $switcher );
        }

        $id_int = substr( $this->get_id_int(), 0, 3 );
        ?>

        <div class="crt-switcher-container" data-active-switcher="<?php echo esc_attr( $active_switcher ); ?>">

            <div class="crt-switcher-outer">
                <div class="crt-switcher-wrap">
                    <?php foreach ( $switcher as $index => $item ) :

                        $switcher_count = $index + 1;
                        $switcher_setting_key = $this->get_repeater_setting_key( 'crt_switcher', 'switcher_items', $index );

                        $this->add_render_attribute( $switcher_setting_key, [
                            'id' => 'crt-switcher-' . $id_int . $switcher_count,
                            'class' => [ 'crt-switcher', 'elementor-repeater-item-'. $item['_id'] ],
                            'data-switcher' => $switcher_count,
                        ] );

                        ?>

                        <div <?php echo $this->get_render_attribute_string( $switcher_setting_key ); ?>>
                            <div class="crt-switcher-inner">
                                <?php if ( '' !== $item['switcher_label'] ) : ?>
                                    <div class="crt-switcher-label"><?php echo $item['switcher_label']; ?></div>
                                <?php endif; ?>

                                <?php if ( 'yes' === $item['switcher_show_icon'] && '' !== $item['switcher_icon']['value'] ) : ?>
                                    <div class="crt-switcher-icon">
                                        <i class="<?php echo esc_attr( $item['switcher_icon']['value'] ); ?>"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>

                    <?php endforeach; ?>

                    <div class="crt-switcher-bg"></div>
                </div>
            </div>

        </div>

        <div class="crt-switcher-content-wrap">
            <?php foreach ( $switcher as $index => $item ) :

                $switcher_count = $index + 1;

                $switcher_content_setting_key = $this->get_repeater_setting_key( 'crt_switcher_content', 'switcher_items', $index );
                $this->add_render_attribute( $switcher_content_setting_key, [
                    'id' => 'crt-switcher-content-' . $id_int . $switcher_count,
                    'class' => [ 'crt-switcher-content', 'elementor-repeater-item-'. $item['_id'] ],
                    'data-switcher' => $switcher_count,
                ] );

                ?>

                <div <?php echo $this->get_render_attribute_string( $switcher_content_setting_key ); ?>>
                    <?php
                    echo '<div class="crt-switcher-content-inner crt-anim-size-'. $settings['content_anim_size'] .' crt-overlay-'. $settings['content_animation'] .'">';

                    if ( 'template' === $item['switcher_content_type'] ) {

                        echo $this->crt_switcher_template( $item['switcher_select_template'] );

                    } else if( 'editor' === $item['switcher_content_type'] ) {

                        echo $item['switcher_content'];
                    }

                    echo '</div>';

                    ?>
                </div>

            <?php endforeach; ?>
        </div>

        <?php
    }

    public function crt_dual_switcher_outer_text() {
		$settings = $this->get_settings();
		?>

		<div class="crt-switcher-inner crt-switcher-first">
			<?php if ( '' !== $settings['switcher_first_label'] ) : ?>
			<div class="crt-switcher-label"><?php echo esc_html($settings['switcher_first_label']); ?></div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['switcher_first_show_icon'] && '' !== $settings['switcher_first_icon']['value'] ) : ?>
			<div class="crt-switcher-icon">
				<i class="<?php echo esc_attr( $settings['switcher_first_icon']['value'] ); ?>"></i>
			</div>
			<?php endif; ?>
		</div>

		<div class="crt-switcher-outer">
			<div class="crt-switcher-wrap">
				<div class="crt-switcher" data-switcher="1"></div>
				<div class="crt-switcher" data-switcher="2"></div>
				<div class="crt-switcher-bg"></div>
			</div>
		</div>

		<div class="crt-switcher-inner crt-switcher-second">
			<?php if ( '' !== $settings['switcher_second_label'] ) : ?>
			<div class="crt-switcher-label"><?php echo esc_html($settings['switcher_second_label']); ?></div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['switcher_second_show_icon'] && '' !== $settings['switcher_second_icon']['value'] ) : ?>
			<div class="crt-switcher-icon">
				<i class="<?php echo esc_attr( $settings['switcher_second_icon']['value'] ); ?>"></i>
			</div>
			<?php endif; ?>
		</div>

		<?php
	}


	public function crt_dual_switcher_inner_text() {

		$settings = $this->get_settings();

		?>

		<div class="crt-switcher-outer">
			<div class="crt-switcher-wrap">

				<div class="crt-switcher" data-switcher="1">
					
					<div class="crt-switcher-inner crt-switcher-first">
						<?php if ( '' !== $settings['switcher_first_label'] ) : ?>
						<div class="crt-switcher-label"><?php echo esc_html($settings['switcher_first_label']); ?></div>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['switcher_first_show_icon'] && '' !== $settings['switcher_first_icon']['value'] ) : ?>
						<div class="crt-switcher-icon">
							<i class="<?php echo esc_attr( $settings['switcher_first_icon']['value'] ); ?>"></i>
						</div>
						<?php endif; ?>
					</div>

				</div>

				<div class="crt-switcher" data-switcher="2">
					
					<div class="crt-switcher-inner crt-switcher-second">
						<?php if ( '' !== $settings['switcher_second_label'] ) : ?>
						<div class="crt-switcher-label"><?php echo esc_html($settings['switcher_second_label']); ?></div>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['switcher_second_show_icon'] && '' !== $settings['switcher_second_icon']['value'] ) : ?>
						<div class="crt-switcher-icon">
							<i class="<?php echo esc_attr( $settings['switcher_second_icon']['value'] ); ?>"></i>
						</div>
						<?php endif; ?>
					</div>

				</div>

				<div class="crt-switcher-bg"></div>

			</div>
		</div>

		<?php
	}

	public function crt_dual_switcher() {

		$settings = $this->get_settings();

		
		$active_switcher = $settings['active_switcher'];

		if ( $active_switcher > 2 ) {
			$active_switcher = 2;
		}

		?>

		<div class="crt-switcher-container" data-active-switcher="<?php echo esc_attr( $active_switcher ); ?>">

			<?php

			if ( 'inner' === $settings['switcher_label_style'] ) {
				$this->crt_dual_switcher_inner_text();
			} else {
				$this->crt_dual_switcher_outer_text();
			}

			?>

		</div>

		<div class="crt-switcher-content-wrap">
			
			<div class="crt-switcher-content" data-switcher="1">
				<?php 
				echo '<div class="crt-switcher-content-inner crt-anim-size-'. esc_attr($settings['content_anim_size']) .' crt-overlay-'. esc_attr($settings['content_animation']) .'">';

					if ( 'template' === $settings['switcher_first_content_type'] ) {

						// Render Elementor Template
						echo ''. $this->crt_switcher_template( $settings['switcher_first_select_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

					} elseif( 'editor' === $settings['switcher_first_content_type'] ) {

						echo wp_kses_post($settings['switcher_first_content']);
					}

				echo '</div>';

				?>
			</div>

			<div class="crt-switcher-content" data-switcher="2">
				<?php 
				echo '<div class="crt-switcher-content-inner crt-anim-size-'. esc_attr($settings['content_anim_size']) .' crt-overlay-'. esc_attr($settings['content_animation']) .'">';

					if ( 'template' === $settings['switcher_second_content_type'] ) {

						// Render Elementor Template
						echo ''. $this->crt_switcher_template( $settings['switcher_second_select_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

					} elseif( 'editor' === $settings['switcher_second_content_type'] ) {

						echo wp_kses_post($settings['switcher_second_content']);
					}

				echo '</div>';

				?>
			</div>

		</div>

		<?php
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();


		echo '<div class="crt-content-toggle">';

		if ('dual' === $settings['switcher_style'] ) {
			$this->crt_dual_switcher();
		} else {
			$this->crt_multi_switcher();
		}

		echo '</div>';

	}
}