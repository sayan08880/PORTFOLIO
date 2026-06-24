<?php
//namespace CrtAddons\Modules\AdvancedAccordion\Widgets;
//
//use Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CRT_Advanced_Accordion extends Widget_Base {
	
	public function get_name() {
		return 'crt-advanced-accordion';
	}

	public function get_title() {
		return esc_html__( 'Advanced Accordion', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-toggle';
	}

    public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'blog', 'advanced accordion' ];
	}

    public function get_script_depends() {
        return [ $this->get_name() ];
    }

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_style_depends() {
		return [ $this->get_name() ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com/contact';
    }

	public function add_repeater_args_accordion_content_type() {
		return  [
            'editor' => esc_html__( 'Text Editor', 'crt-manage' ),
            'template' => esc_html__( 'Elementor Template', 'crt-manage' )
		];
	}

	public function add_control_show_acc_search() {
		$this->add_control(
			'show_acc_search',
			[
				'label' => sprintf( __( 'Show Search %s', 'crt-manage' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => ''
			]
		);
	}

	public function add_section_style_search_input() {

		// Styles
		// Section: Input ------------
		$this->start_controls_section(
			'section_style_search_input',
			[
				'label' => esc_html__( 'Search', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_acc_search' => 'yes'
				]
			]
		);

		$this->add_control(
			'acc_input_heading',
			[
				'label' => esc_html__( 'Input', 'crt-manage' ),
				'type' => Controls_Manager::HEADING
			]
		);

		$this->start_controls_tabs( 'tabs_input_colors' );

		$this->start_controls_tab(
			'tab_input_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-acc-search-input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-acc-search-input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9e9e9e',
				'selectors' => [
					'{{WRAPPER}} .crt-acc-search-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-acc-search-input:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-acc-search-input::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-acc-search-input:-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-acc-search-input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-acc-search-input' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .crt-acc-search-input-wrap'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'selector' => '{{WRAPPER}} .crt-acc-search-input',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus_colors',
			[
				'label' => esc_html__( 'Focus', 'crt-manage' ),
			]
		);

		$this->add_control(
			'input_focus_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}}.crt-acc-search-input-focus .crt-acc-search-input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}.crt-acc-search-input-focus .crt-acc-search-input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9e9e9e',
				'selectors' => [
					'{{WRAPPER}}.crt-acc-search-input-focus .crt-acc-search-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.crt-acc-search-input-focus .crt-acc-search-input:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.crt-acc-search-input-focus .crt-acc-search-input::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.crt-acc-search-input-focus .crt-acc-search-input:-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.crt-acc-search-input-focus .crt-acc-search-input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}}.crt-acc-search-input-focus .crt-acc-search-input' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_focus_box_shadow',
				'selector' => '{{WRAPPER}}.crt-acc-search-input-focus .crt-acc-search-input-wrap'
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'input_align',
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
					'{{WRAPPER}} .crt-acc-search-input' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_size',
			[
				'label' => esc_html__( 'Border Size', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-acc-search-input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-acc-search-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 45,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-acc-search-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_distance',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-acc-search-input-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'acc_input_search_icon',
			[
				'label' => esc_html__( 'Search Icon', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'search_icon_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-acc-search-input-wrap i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-acc-search-input-wrap svg' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_responsive_control(
			'search_icon_size',
			[
				'label' => esc_html__( 'Search Icon Size', 'crt-manage' ),
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
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-acc-search-input-wrap i:first-child' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'acc_input_clear_search_icon',
			[
				'label' => esc_html__( 'Clear Search Icon', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'clear_search_icon_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-acc-search-input-wrap i.fa-times' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'clear_search_icon_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
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
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-acc-search-input-wrap i.fa-times' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'search_icon_indent',
			[
				'label' => esc_html__( 'Icon Indent', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'separator' => 'before',
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
					'{{WRAPPER}} .crt-acc-search-input-wrap i:first-child' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-acc-search-input-wrap i.fa-times' => 'right: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

	}


	public function render_search_input( $settings ) {
		if ( 'yes' === $settings['show_acc_search'] ) : ?>
			<div class="crt-acc-search-input-wrap elementor-clearfix">
				<?php if ( '' !== $settings['acc_search_icon']['value'] ) : ?>
					<i class="<?php echo esc_attr( $settings['acc_search_icon']['value'] ); ?>"></i>
				<?php endif; ?>
				<input <?php echo $this->get_render_attribute_string( 'input' ); ?>>
				<i class="fas fa-times"></i>
			</div>
		<?php endif;
	}


    protected function register_controls() {

		// Tab: Content ==============
		// Section: Content ------------
		$this->start_controls_section(
			'section_accordion_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

//		Utilities::crt_library_buttons( $this, Controls_Manager::RAW_HTML );
        
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'accordion_title', [
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Acc Item Title' , 'crt-manage' ),
			]
		);
 
		$repeater->add_control(
			'accordion_content_type',
			[
				'label' => esc_html__( 'Content Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'editor',
				'options' => $this->add_repeater_args_accordion_content_type(),
				'render_type' => 'template',
			]
		);

        $repeater->add_control(
            'accordion_content_template' ,
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

		$repeater->add_control(
			'accordion_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'type' => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'crt-manage' ),
				'default' => 'Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis.',
				'condition' => [
                    'accordion_content_type!' => 'template'
				]
			]
		);

		$repeater->add_control(
			'accordion_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
				'default' => [
					'value' => 'far fa-edit',
					'library' => 'regular'
				]
			]
		);

		$this->add_control(
			'advanced_accordion',
			[
				'label' => esc_html__( 'Accordion Items', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'accordion_title' => esc_html__( 'Title #1', 'crt-manage' ),
						'accordion_icon' => [
							'value' => 'fas fa-desktop',
							'library' => 'solid'
						],
						'accordion_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'crt-manage' ),
					],
					[
						'accordion_title' => esc_html__( 'Title #2', 'crt-manage' ),
						'accordion_icon' => [
							'value' => 'fab fa-telegram-plane',
							'library' => 'brands'
						],
						'accordion_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'crt-manage' ),
					],
					[
						'accordion_title' => esc_html__( 'Title #3', 'crt-manage' ),
						'accordion_icon' => [
							'value' => 'fas fa-layer-group',
							'library' => 'solid'
						],
						'accordion_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'crt-manage' ),
					]
				],
				'title_field' => '{{{ accordion_title }}}',
			]
		);

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Content ------------
		$this->start_controls_section(
			'section_accordion_settings',
			[
				'label' => esc_html__( 'Settings', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'accordion_type',
            [
                'label'       => esc_html__('Accordion Type', 'crt-manage'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'accordion',
                'label_block' => false,
                'options'     => [
                    'accordion' => esc_html__('Accordion', 'crt-manage'),
                    'toggle'    => esc_html__('Toggle', 'crt-manage'),
                ]
            ]
        );

        $this->add_control(
            'accordion_trigger',
            [
                'label'       => esc_html__('Trigger', 'crt-manage'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'click',
                'label_block' => false,
                'options'     => [
                    'click' => esc_html__('Click', 'crt-manage'),
                    'hover'    => esc_html__('Hover', 'crt-manage'),
                ],
				'condition' => [
					'accordion_type' => 'accordion'
				]
            ]
        );

		$this->add_control(
			'accordion_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'span' => esc_html__( 'Span', 'crt-manage' ),
					'h1' => esc_html__( 'H1', 'crt-manage' ),
					'h2' => esc_html__( 'H2', 'crt-manage' ),
					'h3' => esc_html__( 'H3', 'crt-manage' ),
					'h4' => esc_html__( 'H4', 'crt-manage' ),
					'h5' => esc_html__( 'H5', 'crt-manage' ),
					'h6' => esc_html__( 'H6', 'crt-manage' )
				],
				'default' => 'span',
			]
		);

		$this->add_control(
			'interaction_speed',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'step' => 0.1,
				'min' => 0,
				'max' => 2,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'active_item',
			[
				'label' => esc_html__( 'Active Item Index', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'default' => 1,
				'min' => 0,
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before'
			]
		);

		$this->add_control_show_acc_search();

		$this->add_control(
			'acc_search_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_acc_search' => 'yes',
				],
			]
		);

		$this->add_control(
			'acc_search_placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Search...', 'crt-manage' ),
				'condition' => [
					'show_acc_search' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		// Tab: Content ==========
		// Section: Icons ---------
		$this->start_controls_section(
			'section_icon_settings',
			[
				'label' => esc_html__( 'Icons', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'change_icons_position',
            [
                'label'       => esc_html__('Position', 'crt-manage'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'default',
                'label_block' => false,
                'options'     => [
                    'default' => esc_html__('Default', 'crt-manage'),
                    'reverse'    => esc_html__('Reverse', 'crt-manage'),
                ]
            ]
        );
 
		$this->add_control(
			'accordion_title_icon_box_style',
			[
				'label' => esc_html__( 'Box Style', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'side-box',
				'options' => [
					'no-box' => esc_html__( 'None', 'crt-manage' ),
					'side-box' => esc_html__( 'Side Box', 'crt-manage' ),
					'side-curve' => esc_html__( 'Side Curve', 'crt-manage' )
				],
				'prefix_class' => 'crt-advanced-accordion-icon-',
				'render_type' => 'template'
			]
		);
		
		$this->add_control(
			'accordion_title_icon_box_width',
			[
				'label' => esc_html__( 'Box Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 70,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-acc-icon-box' => 'width: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'accordion_title_icon_box_style!' => 'none'
				]
			]
		);
		
		$this->add_responsive_control(
			'accordion_title_icon_after_box_width',
			[
				'label' => esc_html__( 'Triangle Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 30,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button .crt-acc-icon-box-after' => 'border-left: {{SIZE}}{{UNIT}} solid {{icon_box_color.VALUE}};',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button:hover .crt-acc-icon-box-after' => 'border-left: {{SIZE}}{{UNIT}} solid {{icon_box_hover_color.VALUE}};',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button.crt-acc-active .crt-acc-icon-box-after' => 'border-left: {{SIZE}}{{UNIT}} solid {{icon_box_active_color.VALUE}};',
				],
				'condition' => [
					'accordion_title_icon_box_style' => 'side-curve'
				]
			]
		);

		$this->add_control(
			'toggle_icon',
			[
				'label' => esc_html__( 'Select Toggle Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'fa-solid',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_icon_active',
			[
				'label' => esc_html__( 'Select Toggle Icon Active', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-minus',
					'library' => 'fa-solid',
				]
			]
		);

		$this->add_control(
			'toggle_icon_rotation',
			[
				'label' => esc_html__( 'Active Icon Rotation', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 0,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-active .crt-toggle-icon i' => 'transform: rotate({{SIZE}}deg); transform-origin: center;',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-active .crt-toggle-icon svg' => 'transform: rotate({{SIZE}}deg); transform-origin: center;'
				]
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
//		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );
//
//		// Section: Pro Features
//		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'advanced-accordion', [
//			'Load Elementor Template in Accordion Panels.',
//			'Enable Accordion content Live Search.',
//		] );

		$this->add_section_style_search_input();

		// Tab: Styles ===============
		// Section: Title ---------
		$this->start_controls_section(
			'section_style_switcher',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tab_style' );

		$this->start_controls_tab(
			'tab_normal_style',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' )
			]
		);

		$this->add_control(
			'tab_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE4',
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button .crt-acc-title-text' => 'color: {{VALUE}}',
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'tab_bg_color',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#FFFFFF',
					],
				],
				'selector' => '{{WRAPPER}} .crt-advanced-accordion .crt-acc-button'
			]
		);

		$this->add_control(
			'tab_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#EAEAEA',
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_box_shadow',
				'selector' => '{{WRAPPER}} .crt-advanced-accordion .crt-acc-button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'selector' => '{{WRAPPER}} .crt-advanced-accordion .crt-acc-button, {{WRAPPER}} .crt-advanced-accordion .crt-acc-button .crt-acc-title-text',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight'     => [
						'default' => '400',
					]
				]
			]
		);

		$this->add_control(
			'accordion_transition',
			[
				'label' => esc_html__( 'Transition', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion button.crt-acc-button' => 'transition: all {{VALUE}}s ease-in-out;',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hover_style',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' )
			]
		);

		$this->add_control(
			'tab_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button:hover .crt-acc-title-text' => 'color: {{VALUE}}',
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'tab_hover_bg_color',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .crt-advanced-accordion .crt-acc-button:hover'
			]
		);

		$this->add_control(
			'tab_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_hover_box_shadow',
				'selector' => '{{WRAPPER}} .crt-advanced-accordion .crt-acc-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_active_style',
			[
				'label' => esc_html__( 'Active', 'crt-manage' )
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button.crt-acc-active' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button.crt-acc-active .crt-acc-title-text' => 'color: {{VALUE}}',
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'tab_active_bg_color',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .crt-advanced-accordion .crt-acc-button.crt-acc-active'
			]
		);

		$this->add_control(
			'tab_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button.crt-acc-active' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_active_box_shadow',
				'selector' => '{{WRAPPER}} .crt-advanced-accordion .crt-acc-button.crt-acc-active',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'tab_gutter',
			[
				'label' => esc_html__( 'Vertical Gutter', 'crt-manage' ),
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
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_title_distance',
			[
				'label' => esc_html__( 'Title Left Distance', 'crt-manage' ),
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
					'{{WRAPPER}}.crt-advanced-accordion-icon-no-box .crt-acc-item-title .crt-acc-title-text' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-advanced-accordion-icon-side-box .crt-acc-item-title .crt-acc-title-text' => 'margin-left: calc({{accordion_title_icon_box_width.SIZE}}{{accordion_title_icon_box_width.UNIT}} + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}}.crt-advanced-accordion-icon-side-curve .crt-acc-item-title .crt-acc-title-text' => 'margin-left: calc({{accordion_title_icon_box_width.SIZE}}{{accordion_title_icon_box_width.UNIT}} + {{accordion_title_icon_after_box_width.SIZE}}{{accordion_title_icon_after_box_width.UNIT}} + {{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'tab_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 18,
					'right' => 18,
					'bottom' => 18,
					'left' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button' => 'border-style: {{VALUE}};',
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
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Icon ----------
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icons', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs( 'tab_style_icon' );

		$this->start_controls_tab(
			'tab_icon_normal_style',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' )
			]
		);

		$this->add_control(
			'tab_main_icon_color',
			[
				'label' => esc_html__( 'Main Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#EDEDED',
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button .crt-title-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button .crt-title-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tab_toggle_icon_color',
			[
				'label' => esc_html__( 'Toggle Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button .crt-toggle-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button .crt-toggle-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_box_color',
			[
				'label' => esc_html__( 'Icon Box Bg Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE4',
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button .crt-acc-icon-box' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'accordion_title_icon_box_style!' => 'none'
				]
			]
		);

		$this->add_control(
			'accordion_icon_transition',
			[
				'label' => esc_html__( 'Transition', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-toggle-icon i' => 'transition: all {{VALUE}}s ease-in-out;',
					'{{WRAPPER}} .crt-advanced-accordion .crt-title-icon i' => 'transition: all {{VALUE}}s ease-in-out;',
					'{{WRAPPER}} .crt-advanced-accordion .crt-toggle-icon svg' => 'transition: all {{VALUE}}s ease-in-out;',
					'{{WRAPPER}} .crt-advanced-accordion .crt-title-icon svg' => 'transition: all {{VALUE}}s ease-in-out;',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover_style',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' )
			]
		);

		$this->add_control(
			'tab_main_hover_icon_color',
			[
				'label' => esc_html__( 'Main Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button:hover .crt-title-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button:hover .crt-title-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tab_toggle_hover_icon_color',
			[
				'label' => esc_html__( 'Toggle Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button:hover .crt-toggle-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button:hover .crt-toggle-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_box_hover_color',
			[
				'label' => esc_html__( 'Icon Box Bg Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button:hover .crt-acc-icon-box' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'accordion_title_icon_box_style!' => 'none'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_active_style',
			[
				'label' => esc_html__( 'Active', 'crt-manage' )
			]
		);

		$this->add_control(
			'tab_main_active_icon_color',
			[
				'label' => esc_html__( 'Main Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button.crt-acc-active .crt-title-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button.crt-acc-active .crt-title-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tab_toggle_active_icon_color',
			[
				'label' => esc_html__( 'Toggle Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button.crt-acc-active .crt-toggle-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button.crt-acc-active .crt-toggle-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_box_active_color',
			[
				'label' => esc_html__( 'Icon Box Bg Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button.crt-acc-active .crt-acc-icon-box' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'accordion_title_icon_box_style!' => 'none'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'tab_main_icon_size',
			[
				'label' => esc_html__( 'Main Icon Size', 'crt-manage' ),
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button .crt-title-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button .crt-title-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_toggle_icon_size',
			[
				'label' => esc_html__( 'Toggle Icon Size', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button .crt-toggle-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-button .crt-toggle-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'icon_box_border_radius',
			[
				'label' => esc_html__( 'Icon Box Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-advanced-accordion-icon-side-box .crt-advanced-accordion .crt-acc-icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-advanced-accordion-icon-side-curve .crt-advanced-accordion .crt-acc-icon-box' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'accordion_title_icon_box_style!' => 'no-box'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section 

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-panel .crt-acc-panel-content' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-panel' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-panel' => 'border-color: {{VALUE}}',
				],
				// 'condition' => [
				// 	'content_border_type!' => 'none',
				// ],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .crt-advanced-accordion .crt-acc-panel',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .crt-advanced-accordion .crt-acc-panel .crt-acc-panel-content',
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
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-panel' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-panel' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .crt-advanced-accordion .crt-acc-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section
    }

	public function crt_accordion_template( $id ) {
		if ( empty( $id ) ) {
			return '';
		}

		if ( defined('ICL_LANGUAGE_CODE') ) {
			$default_language_code = apply_filters('wpml_default_language', null);

			if ( ICL_LANGUAGE_CODE !== $default_language_code ) {
				$id = icl_object_id($id, 'elementor_library', false, ICL_LANGUAGE_CODE);
			}
		}

		$edit_link = '<span class="crt-template-edit-btn" data-permalink="'. get_permalink( $id ) .'">Edit Template</span>';
		
		$type = get_post_meta(get_the_ID(), '_crt_template_type', true) || get_post_meta($id, '_elementor_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

	public function render_first_icon($settings, $acc) {
		if ( $settings['change_icons_position'] == 'reverse' ) :
			if (!empty($settings['toggle_icon'])) : ?>
				<span class="crt-toggle-icon crt-ti-close"><?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
				<span class="crt-toggle-icon crt-ti-open"><?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon_active'], [ 'aria-hidden' => 'true' ] ); ?></span>
			<?php endif ;
		else :
			if (!empty($acc['accordion_icon'])) : ?>
				<span class="crt-title-icon">
					<?php \Elementor\Icons_Manager::render_icon( $acc['accordion_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			<?php	endif ;
		endif;
	}

	public function render_second_icon($settings, $acc) {
		if ( $settings['change_icons_position'] == 'reverse' ) :
			if (!empty($acc['accordion_icon'])) : ?>
				<span class="crt-title-icon">
					<?php \Elementor\Icons_Manager::render_icon( $acc['accordion_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			<?php	endif ;
		else :
			if (!empty($settings['toggle_icon'])) : ?>
				<span class="crt-toggle-icon crt-ti-close"><?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
				<span class="crt-toggle-icon crt-ti-open"><?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon_active'], [ 'aria-hidden' => 'true' ] ); ?></span>
			<?php endif ;
		endif;
	}

    protected function render() {
        $settings = $this->get_settings_for_display();

		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
		$accordion_title_tag = $settings['accordion_title_tag'];

		if ( !in_array( $accordion_title_tag, $tags_whitelist ) ) {
			$accordion_title_tag = 'span';
		}

		$this->add_render_attribute(
			'accordion_attributes',
			[
				'class' => [ 'crt-advanced-accordion' ],
				'data-accordion-type' => esc_attr($settings['accordion_type']),
				'data-active-index' => intval($settings['active_item']),
				'data-accordion-trigger' => isset($settings['accordion_trigger']) ? esc_attr($settings['accordion_trigger']) : 'click',
				'data-interaction-speed' => isset($settings['interaction_speed']) ? floatval($settings['interaction_speed']) : 0.4
			]
		);

		if ( 'yes' === $settings['show_acc_search'] ) {

			$this->add_render_attribute(
				'input', [
					'placeholder' => esc_attr($settings['acc_search_placeholder']),
					'class' => 'crt-acc-search-input',
					'type' => 'search',
					'title' => esc_html__( 'Search', 'crt-manage' ),
				]
			);

		} ?>

            <div <?php echo $this->get_render_attribute_string( 'accordion_attributes' ); ?>>

			<?php $this->render_search_input( $settings ) ?>

                <?php 
					foreach ($settings['advanced_accordion'] as $key => $acc) :
						$acc_content_type = $acc['accordion_content_type'];
				?>

					<div class="crt-accordion-item-wrap">
						<button class="crt-acc-button">
							<span class="crt-acc-item-title">
								<?php if ('side-box' === $settings['accordion_title_icon_box_style']) : ?>
									<div class="crt-acc-icon-box">
										<?php $this->render_first_icon($settings, $acc); ?>
									</div>
								<?php elseif ('side-curve' === $settings['accordion_title_icon_box_style']) : ?>
									<div class="crt-acc-icon-box">
										<?php $this->render_first_icon($settings, $acc); ?>
										<div class="crt-acc-icon-box-after"></div>
									</div>
								<?php else :
									$this->render_first_icon($settings, $acc); 
								endif ; ?>

								<<?php echo $accordion_title_tag ?> class="crt-acc-title-text"><?php echo $acc['accordion_title'] ?></<?php echo $accordion_title_tag ?>>
							</span>
							<?php $this->render_second_icon($settings, $acc); ?>
						</button>

						<div class="crt-acc-panel">
							<?php if ('editor' === $acc_content_type) : ?>
								<div class="crt-acc-panel-content"><?php echo $acc['accordion_content'] ?></div>
							<?php else:
                                echo $this->crt_accordion_template( $acc['accordion_content_template'] );
							endif; ?>
						</div>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php
    }
}