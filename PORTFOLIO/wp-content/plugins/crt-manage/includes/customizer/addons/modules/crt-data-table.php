<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use CrtAddons\Classes\Utilities;


// Security Note: Blocks direct access to the plugin PHP files.
defined('ABSPATH') || die();

class CRT_Data_Table extends Widget_Base {

    public function get_name() {
		return 'crt-data-table';
	}

	public function get_title() {
		return esc_html__('Data Table', 'crt-manage');
	}
	public function get_icon() {
		return 'crt-icon eicon-table';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'data table', 'advanced', 'table', 'data', 'comparison table', 'table comparison'];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_script_depends() {
		return [ 'crt-data-table', 'crt-table-to-excel-js', 'crt-perfect-scroll-js'];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-pricing-table-help-btn';
    		return 'https://crthemes.com/contact';
    }

    public function add_control_choose_table_type() {
        $this->add_control(
            'choose_table_type',
            [
                'label' => esc_html__( 'Data Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'render_type' => 'template',
                'options' => [
                    'custom' => esc_html__( 'Custom', 'crt-manage' ),
                    'csv' => esc_html__( 'CSV', 'crt-manage' ),
                ],
                'prefix_class' => 'crt-data-table-type-'
            ]
        );

        $this->add_control(
            'choose_csv_type',
            [
                'label' => esc_html__( 'File Type', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'file',
                'options' => [
                    'file' => esc_html__( 'Upload CSV', 'crt-manage' ),
                    'url' => esc_html__( 'Remote CSV URL', 'crt-manage' ),
                ],
                'condition' => [
                    'choose_table_type' => 'csv'
                ]
            ]
        );

        $this->add_control(
            'display_header',
            [
                'label' => esc_html__('Show Table Header', 'crt-manage'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'choose_table_type' => 'csv',
                ]
            ]
        );

        $this->add_control(
            'table_upload_csv',
            [
                'label'     => esc_html__('Upload CSV File', 'crt-manage'),
                'type'      => Controls_Manager::MEDIA,
                'media_type'=> array(),
                'dynamic' => [
                    'active' => true,
                    'categories' => [
                        'media',
                    ],
                ],
                'condition' => [
                    'choose_table_type'   => 'csv',
                    'choose_csv_type' => 'file',
                ]
            ]
        );

        $this->add_control(
            'table_insert_url',
            [
                'label'         => esc_html__( 'Enter a CSV File URL', 'crt-manage' ),
                'type'          => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'show_external' => false,
                'label_block'   => true,
                // 'default'       => [
                //     'url' => Handler::get_url()  . 'assets/table.csv',
                // ],
                'condition' => [
                    'choose_table_type' => 'csv',
                    'choose_csv_type'   => 'url',
                ]
            ]
        );
    }

    public function add_control_enable_table_export() {
        $this->add_control(
            'enable_table_export',
            [
                'label' => esc_html__('Show Export Buttons', 'crt-manage'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before'
            ]
        );
    }

    public function add_control_export_excel_text() {
        $this->add_control(
            'export_excel_text',
            [
                'label'                 => esc_html__( 'Export Excel Text', 'crt-manage' ),
                'type'                  => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default'               => 'Export XLS',
                'condition'             => [
                    'enable_table_export'   => 'yes',
                ],
            ]
        );
    }

    public function add_control_export_buttons_distance() {
        $this->add_responsive_control(
            'export_buttons_distance',
            [
                'label' => esc_html__( 'Distance', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default'   => [
                    'size'  => 7,
                    'unit'  => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .crt-xls' => 'margin-right: {{SIZE}}{{UNIT}}; position: relative;',
                    '{{WRAPPER}} .crt-table-export-button-cont' => 'margin-bottom: {{SIZE}}{{UNIT}}; position: relative;',
                    '{{WRAPPER}} .crt-table-live-search-cont' => 'margin-bottom: {{SIZE}}{{UNIT}}; position: relative;',
                ]
            ]
        );
    }

    public function add_control_table_search_input_padding() {
        $this->add_responsive_control(
            'table_search_input_padding',
            [
                'label'      => esc_html__( 'Search & Export Padding', 'crt-manage' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'    => 5,
                    'bottom' => 5,
                    'left'   => 5,
                    'right'  => 5,
                    'unit'   => 'px'
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-live-search-cont input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-table-export-button-cont button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .crt-search-input-icon' => 'right: {{RIGHT}}{{UNIT}} !important',
                ]
            ]
        );
    }

    public function add_control_export_csv_text() {
        $this->add_control(
            'export_csv_text',
            [
                'label'                 => esc_html__( 'Export CSV Text', 'crt-manage' ),
                'type'                  => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default'               => 'Export CSV',
                'condition'             => [
                    'enable_table_export'   => 'yes',
                ],
            ]
        );
    }

    public function add_section_export_buttons_styles() {
        $this->start_controls_section(
            'export_buttons_styles_section',
            [
                'label' => esc_html__( 'Export Buttons', 'crt-manage' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_table_export' => 'yes'
                ]
            ]
        );

        $this->start_controls_tabs(
            'export_button_style_tabs'
        );

        $this->start_controls_tab(
            'export_buttons_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'export_buttons_color',
            [
                'label'  => esc_html__( 'Text Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#7A7A7A',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-export-button-cont .crt-button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'export_buttons_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-export-button-cont .crt-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'export_buttons_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-export-button-cont .crt-button' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'export_typograpphy_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'export_typography',
                'selector' => '{{WRAPPER}} .crt-table-export-button-cont .crt-button',
                'fields_options' => [
                    'typography'      => [
                        'default' => 'custom',
                    ],
                    'font_size'       => [
                        'label'      => esc_html__('Font Size (px)', 'crt-manage'),
                        'size_units' => ['px'],
                        'default'    => [
                            'size' => '13',
                            'unit' => 'px',
                        ],
                    ],
                    'font_weight'     => [
                        'default' => '400',
                    ]
                ],
            ]
        );

        $this->add_control(
            'export_hover_transition',
            [
                'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .crt-button' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size;'
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'export_container_width',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default'   => [
                    'size'  => 325,
                    'unit'  => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-table-export-button-cont' => 'width: {{SIZE}}{{UNIT}}; position: relative;',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'export_buttons_border_type',
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
                'separator' => 'before',
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-export-button-cont .crt-button' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'export_buttons_border_width',
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
                    '{{WRAPPER}} .crt-table-export-button-cont .crt-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'export_buttons_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'export_buttons_border_radius',
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
                    '{{WRAPPER}} .crt-table-export-button-cont .crt-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'export_buttons_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'export_buttons_color_hover',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#7A7A7A',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-export-button-cont .crt-button:hover' => 'color: {{VALUE}}; cursor: pointer;',
                ],
            ]
        );

        $this->add_control(
            'export_buttons_bg_color_hover',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-export-button-cont .crt-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'export_buttons_border_color_hover',
            [
                'label'     => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-export-button-cont .crt-button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->add_section_search_styles();
    }

    public function add_control_enable_table_search() {
        $this->add_control(
            'enable_table_search',
            [
                'label' => esc_html__('Show Search', 'crt-manage'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'search_placeholder',
            [
                'label'                 => esc_html__( 'Search Placeholder', 'crt-manage' ),
                'type'                  => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default'               => 'Type Here To Search...',
                'condition'             => [
                    'enable_table_search'   => 'yes',
                ],
            ]
        );
    }

    public function add_section_search_styles() {
        $this->start_controls_section(
            'search_style_section',
            [
                'label' => esc_html__('Search', 'crt-manage'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_table_search' => 'yes'
                ]
            ]
        );

        $this->start_controls_tabs(
            'table_search_input_tabs'
        );

        $this->start_controls_tab(
            'table_search_input_normal_tab',
            [
                'label'     => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'table_search_input_color',
            [
                'label'     => esc_html__( 'Color', 'crt-manage' ),
                'type'      => Controls_Manager::COLOR,
                'default' => '#7A7A7A',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-live-search-cont input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_search_input_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'crt-manage' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-table-live-search-cont input' => 'background-color: {{VALUE}};',
                    // '{{WRAPPER}} .crt-table-live-search-cont' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_search_input_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'crt-manage' ),
                'type'      => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-live-search-cont input' => 'border-color: {{VALUE}};',
                    // '{{WRAPPER}} .crt-table-live-search-cont' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'table_search_input_hover_tab',
            [
                'label'     => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'table_search_input_hover_color',
            [
                'label'     => esc_html__( 'Color', 'crt-manage' ),
                'type'      => Controls_Manager::COLOR,
                'default' => '#7A7A7A',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-live-search-cont input:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_search_input_hover_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'crt-manage' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-table-live-search-cont input:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'table_search_input_focus_tab',
            [
                'label'     => esc_html__( 'Focus', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'table_search_input_focus_color',
            [
                'label'     => esc_html__( 'Color', 'crt-manage' ),
                'default' => '#7A7A7A',
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-table-live-search-cont input:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_search_input_focus_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'crt-manage' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-table-live-search-cont input:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'table_search_input_border_shadow',
                'selector' => '{{WRAPPER}} .crt-table-live-search-cont input',
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'table_search_input_text_typography',
                'label' =>esc_html__( 'Typography', 'crt-manage' ),
                'selector' => '{{WRAPPER}} .crt-table-live-search-cont input',
                'fields_options' => [
                    'typography'      => [
                        'default' => 'custom',
                    ],
                    'font_size'       => [
                        'label'      => esc_html__('Font Size (px)', 'crt-manage'),
                        'size_units' => ['px'],
                        'default'    => [
                            'size' => '13',
                            'unit' => 'px',
                        ],
                    ]
                ],
            ]
        );

        $this->add_control(
            'table_search_input_placeholder_heading',
            [
                'label'     => esc_html__( 'Input Placeholder', 'crt-manage' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'table_search_input_placeholder_color',
            [
                'label'     => esc_html__( 'Color', 'crt-manage' ),
                'type'      => Controls_Manager::COLOR,
                'default' => '#7A7A7A',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-live-search-cont input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'table_search_input_placeholder_typo',
                'label' =>esc_html__( 'Typography', 'crt-manage' ),
                'selector' => '{{WRAPPER}} .crt-table-live-search-cont input::placeholder',
            ]
        );

        $this->add_control(
            'table_search_icon_heading',
            [
                'label'     => esc_html__( 'Icon', 'crt-manage' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'table_search_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'crt-manage' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7A7A7A',
                'selectors' => [
                    '{{WRAPPER}} i.crt-search-input-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'table_search_icon_font_size',
            [
                'label'      => esc_html__( 'Icon Size', 'crt-manage' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [
                    'px', 'em', 'rem',
                ],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} i.crt-search-input-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'table_search_input_heading',
            [
                'label'     => esc_html__( 'Input', 'crt-manage' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'table_search_input_width',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default'   => [
                    'size'  => 325,
                    'unit'  => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-table-live-search-cont' => 'width: {{SIZE}}{{UNIT}}; position: relative;',
                ],
            ]
        );

        $this->add_control(
            'table_search_input_border',
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
                'separator' => 'before',
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-live-search-cont input' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'table_search_input_border_width',
            [
                'label'      => esc_html__( 'Border Width', 'crt-manage' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .crt-table-live-search-cont input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
                'condition' => [
                    'table_search_input_border!' => 'none'
                ]
            ]
        );

        $this->add_responsive_control(
            'table_search_input_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'crt-manage' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .crt-table-live-search-cont input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function add_control_enable_table_sorting() {
        $this->add_control(
            'enable_table_sorting',
            [
                'label' => esc_html__('Show Sorting', 'crt-manage'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before'
            ]
        );
    }

    public function add_control_active_td_bg_color() {
        $this->add_control(
            'active_td_bg_color',
            [
                'label'  => esc_html__( 'Active Column Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .crt-active-td-bg-color' => 'background: {{VALUE}} !important;',
                ]
            ]
        );
    }

    public function add_control_enable_custom_pagination() {
        $this->add_control(
            'enable_custom_pagination',
            [
                'label' => esc_html__('Show Pagination', 'crt-manage'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'table_items_per_page',
            [
                'label' => esc_html__( 'Items Per Page', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'render_type' => 'template',
                'frontend_available' => true,
                'default' => 10,
                'condition' => [
                    'enable_custom_pagination' => 'yes'
                ]
            ]
        );


        $this->add_control(
            'pagination_nav_icons',
            [
                'label' => esc_html__( 'Select Icon', 'crt-manage' ),
                'type' => 'crt-arrow-icons',
                'default' => 'fas fa-angle',
                'condition' => [
                    'enable_custom_pagination' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'enable_entry_info',
            [
                'label' => esc_html__('Entry Info', 'crt-manage'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'return_value' => 'yes',
                'default' => 'yes',
                'prefix_class' => 'crt-entry-info-',
                'condition' => [
                    'enable_custom_pagination' => 'yes'
                ]
            ]
        );
    }

    public function add_section_pagination_styles() {
        $this->start_controls_section(
            'pagination_style_section',
            [
                'label' => esc_html__('Pagination', 'crt-manage'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_custom_pagination' => 'yes'
                ]
            ]
        );

        $this->start_controls_tabs(
            'pagination_normal_style_tabs'
        );

        $this->start_controls_tab(
            'pagination_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-custom-pagination-list' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-table-custom-pagination-list svg' => 'fill: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-custom-pagination-list' => 'background-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color_active',
            [
                'label' => esc_html__( 'Background Color (Active)', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#605BE5',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-custom-pagination-list.crt-active-pagination-item' => 'background-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'pagination_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'separator' => 'after',
                'selectors' => [
                    // '{{WRAPPER}} .crt-table-custom-pagination-inner-cont' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .crt-table-custom-pagination-prev' => 'border-left-color: {{VALUE}}; border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .crt-table-custom-pagination-next' => 'border-right-color: {{VALUE}}; border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .crt-table-custom-pagination-list-item' => 'border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'table_pagination_typography',
                'selector' => '{{WRAPPER}} .crt-table-custom-pagination-list',
                'render_type' => 'template',
                'fields_options' => [
                    'typography'      => [
                        'default' => 'custom',
                    ],
                    'font_weight'     => [
                        'default' => '400',
                    ]
                ],
            ]
        );

        $this->add_control(
            'pagination_hover_transition',
            [
                'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-custom-pagination-inner-cont li' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size;'
                ]
            ]
        );

        $this->add_responsive_control(
            'pagination_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-custom-pagination-list svg' => 'width: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_responsive_control(
            'pagination_horizontal_gutter',
            [
                'label' => esc_html__( 'Horizontal Gutter', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-table-custom-pagination-list:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'pagination_vertical_gutter',
            [
                'label' => esc_html__( 'Vertical Gutter', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200
                    ]
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-table-pagination-cont' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'separator' => 'before',
                'default' => [
                    'top' => 7,
                    'right' => 13,
                    'bottom' => 7,
                    'left' => 13,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-table-custom-pagination-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                'separator' => 'before',
                'default' => 'solid',
                'selectors' => [
                    // '{{WRAPPER}} .crt-table-custom-pagination-inner-cont' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .crt-table-custom-pagination-prev' => 'border-left-style: {{VALUE}}; border-top-style: {{VALUE}}; border-bottom-style: {{VALUE}};',
                    '{{WRAPPER}} .crt-table-custom-pagination-next' => 'border-right-style: {{VALUE}}; border-top-style: {{VALUE}}; border-bottom-style: {{VALUE}};',
                    '{{WRAPPER}} .crt-table-custom-pagination-list-item' => 'border-top-style: {{VALUE}}; border-bottom-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_border_width',
            [
                'label' => esc_html__( 'Border Width', 'crt-manage' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [
                    'px', '%'
                ],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-table-custom-pagination-prev' => 'border-left-width: {{SIZE}}{{UNIT}}; border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-table-custom-pagination-next' => 'border-right-width: {{SIZE}}{{UNIT}};  border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-table-custom-pagination-list-item' => 'border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'pagination_border_type!' => 'none'
                ]
            ]
        );

        $this->add_responsive_control(
            'pagination_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'crt-manage' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [
                    'px', '%'
                ],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-table-custom-pagination-prev' => 'border-top-left-radius: {{SIZE}}{{UNIT}}; border-bottom-left-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-table-custom-pagination-next' => 'border-top-right-radius: {{SIZE}}{{UNIT}}; border-bottom-right-radius: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'pagination_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'pagination_color_hover',
            [
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#A4A4A4',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-custom-pagination-list:hover' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-custom-pagination-list:hover' => 'background-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'pagination_border_color_hover',
            [
                'label'     => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-table-custom-pagination-inner-cont:hover' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'pagination_border_type!' => 'none',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_control(
            'pagination_alignment',
            [
                'label'        => esc_html__('Alignment', 'crt-manage'),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'default'      => 'center',
                'separator' => 'before',
                'options'      => [
                    'flex-start'   => [
                        'title' => esc_html__('Left', 'crt-manage'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'crt-manage'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'flex-end'  => [
                        'title' => esc_html__('Right', 'crt-manage'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-table-custom-pagination' => 'display: flex; justify-content: {{VALUE}}; align-items: center;',
                ],
                'condition' => [
                    'enable_entry_info!' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'entry_info_styles',
            [
                'label' => esc_html__('Entry Info', 'crt-manage'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_custom_pagination' => 'yes',
                    'enable_entry_info' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'entry_info_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#B3B3B3',
                'selectors' => [
                    '{{WRAPPER}} .crt-entry-info' => 'color: {{VALUE}}; cursor: pointer;',
                ],
            ]
        );

        $this->add_control(
            'entry_info_color_hover',
            [
                'label'  => esc_html__( 'Hover Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#B3B3B3',
                'selectors' => [
                    '{{WRAPPER}} .crt-entry-info:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'entry_info_typography',
                'selector' => '{{WRAPPER}} .crt-entry-info',
                'fields_options' => [
                    'typography'      => [
                        'default' => 'custom',
                    ],
                    'font_size'       => [
                        'default'    => [
                            'size' => '13',
                            'unit' => 'px',
                        ],
                    ],
                    'font_weight'     => [
                        'default' => '400',
                    ]
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function add_control_stack_content_tooltip_section() {

        $this->start_controls_section(
            'tooltip_styles',
            [
                'label' => esc_html__('Tooltip', 'crt-manage'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'content_tooltip_icon_heading',
            [
                'label' => esc_html__( 'Tooltip Icon', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_tooltip_icon_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'default' => '#7A7A7A',
                'selectors' => [
                    '{{WRAPPER}} .crt-data-table .fa-question-circle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_tooltip_section',
            [
                'label' => esc_html__( 'Tooltip', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'content_tooltip_width',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                    ],
                ],
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-data-table-content-tooltip' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_tooltip_bg_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'default' => '#3f3f3f',
                'selectors' => [
                    '{{WRAPPER}} .crt-data-table-content-tooltip' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .crt-data-table-content-tooltip:before' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_tooltip_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .crt-data-table-content-tooltip' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_tooltip_typography',
                'label' => esc_html__( 'Typography', 'crt-manage' ),
                'selector' => '{{WRAPPER}} .crt-data-table-content-tooltip',
            ]
        );

        $this->end_controls_section();
    }

    public function add_repeater_args_content_tooltip() {
        return [
            'label' => esc_html__( 'Show Tooltip', 'crt-manage' ),
            'type' => Controls_Manager::SWITCHER,
            'separator' => 'before',
            'condition' => [
                'table_content_row_type' => 'col',
            ],
        ];
    }

    public function add_repeater_args_content_tooltip_text() {
        return [
            'label' => esc_html__( 'Description', 'crt-manage' ),
            'type' => Controls_Manager::TEXTAREA,
            'dynamic' => [
                'active' => true,
            ],
            'default' => 'Tooltip Text',
            'description' => esc_html__( 'This field accepts HTML.', 'crt-manage' ),
            'condition' => [
                'table_content_row_type' => 'col',
                'content_tooltip' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_content_tooltip_show_icon() {
        return [
            'label' => esc_html__( 'Show Tooltip Icon', 'crt-manage' ),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'table_content_row_type' => 'col',
                'content_tooltip' => 'yes',
            ],
        ];
    }

    public function register_controls() {

		$this->start_controls_section(
			'section_preview',
			[
				'label' => esc_html__('General', 'crt-manage'),
			]
		);
		
		Utilities::crt_library_buttons( $this, Controls_Manager::RAW_HTML );

		// Utilities::crt_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_choose_table_type();


		// $this->add_control(
		// 	'enable_custom_links',
		// 	[
		// 		'label' => esc_html__('Custom Links', 'crt-manage'),
		// 		'type' => \Elementor\Controls_Manager::SWITCHER,
		// 		'return_value' => 'yes',
		// 		'default' => 'no',
		// 		'separator' => 'before',
		// 		'condition' => [
		// 			'choose_table_type' => 'csv',
		// 		]
		// 	]
		// );

		$this->add_control_enable_table_export();

		$this->add_control_export_excel_text();

		$this->add_control_export_csv_text();

		$this->add_control_enable_table_search();

		$this->add_control_enable_table_sorting();

		$this->add_control_enable_custom_pagination();

		$this->add_control(
			'equal_column_width',
			[
				'label' => esc_html__('Equal Column Width', 'crt-manage'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'prefix_class' => 'crt-equal-column-width-'
			]
		);

		$this->add_control(
			'enable_row_pagination', 
			[
				'label' => esc_html__('Table Row Index', 'crt-manage'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'white_space_text',
			[
				'label' => esc_html__('Prevent Word Wrap', 'crt-manage'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'crt-table-text-nowrap-',
				'separator' => 'before'
			]
		);

		// $this->add_control(
		// 	'enable_columns_control',
		// 	[
		// 		'label' => esc_html__('Columns', 'crt-manage'),
		// 		'type' => \Elementor\Controls_Manager::SWITCHER,
		// 		'return_value' => 'yes',
		// 		'default' => 'no',
		// 		'separator' => 'before'
		// 	]
		// );

		// $this->add_control(
		// 	'columns_number',
		// 	[
		// 		'label' => esc_html__( 'Quantity', 'crt-manage' ),
		// 		'type' => Controls_Manager::NUMBER,
		// 		'min' => 1,
		// 		'max' => 100,
		// 		'render_type' => 'template',
		// 		'frontend_available' => true,
		// 		'default' => 10,
		// 		'condition' => [
		// 			'enable_columns_control' => 'yes'
		// 		]
		// 	]
		// );

        $this->add_control(
            'table_export_csv_button',
            [
                'label' => esc_html__('Export table as CSV file', 'crt-manage'),
                'type'  => Controls_Manager::BUTTON,
                'text'  => esc_html__('Export', 'crt-manage'),
                'event' => 'my-table-export',
				'separator' => 'before'
            ]
        );

		$this->end_controls_section();

		// $this->start_controls_section(
		// 	'custom_links_section',
		// 	[
		// 		'label' => esc_html__( 'Custom Links', 'crt-manage' ),
		// 		'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		// 		'condition' => [
		// 			'choose_table_type' => 'csv',
		// 			'enable_custom_links' => 'yes',
		// 		]
		// 	]
		// );

		// $repeater = new \Elementor\Repeater();

		// $repeater->add_control(
		// 	'custom_link_title', [
		// 		'label' => esc_html__( 'Title', 'plugin-name' ),
		// 		'type' => \Elementor\Controls_Manager::TEXT,
		// 		'default' => esc_html__( 'Custom Link Title' , 'plugin-name' ),
		// 		'label_block' => true,
		// 	]
		// );

        // $repeater->add_control(
        //     'table_custom_link',
        //     [
        //         'label'         => esc_html__( 'Custom Link', 'crt-manage' ),
        //         'type'          => Controls_Manager::URL,
        //         'show_external' => false,
        //         'label_block'   => true,
        //     ]
        // );

		// $repeater->add_control(
		// 	'custom_link_tr_index',
		// 	[
		// 		'label'			=> esc_html__( 'Table Row Index', 'crt-manage'),
		// 		'type'			=> Controls_Manager::NUMBER,
		// 		'default' 		=> 0,
		// 		'min'     		=> 0,
		// 	]
		// );

		// $this->add_control(
		// 	'custom_links',
		// 	[
		// 		'label' => esc_html__( 'Custom Links', 'crt-manage' ),
		// 		'type' => \Elementor\Controls_Manager::REPEATER,
		// 		'fields' => $repeater->get_controls(),
		// 		'default' => [
		// 			[
		// 				'table_custom_link' => esc_html__( 'X', 'plugin-name' ),
		// 				'custom_link_tr_index' => esc_html__( 'Change This', 'plugin-name' ),
		// 			],
		// 		],
		// 		'title_field' => '{{{ custom_link_title }}}',
		// 	]
		// );

		// $this->end_controls_section();

		$this->start_controls_section(
			'section_header',
			[
				'label' => esc_html__('Header', 'crt-manage'),
				'condition' => [
					'choose_table_type' => 'custom'
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'table_th', [
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Table Title' , 'crt-manage' ),
				'label_block' => true
			]
		);
		
		$repeater->add_responsive_control(
			'header_icon',
			[
				'label' => esc_html__('Media', 'crt-manage'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'header_icon_type',
			[
				'label' => esc_html__('Media Type', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__('Icon', 'crt-manage'),
					'image' => esc_html__('Image', 'crt-manage'),
				],
				'condition' => [
					'header_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'header_icon_position',
			[
				'label' => esc_html__('Media Position', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => esc_html__('Left', 'crt-manage'),
					'right' => esc_html__('Right', 'crt-manage'),
					'top' => esc_html__('Top', 'crt-manage'),
					'bottom' => esc_html__('Bottom', 'crt-manage'),
				],
				'condition' => [
					'header_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'choose_header_col_icon',
			[
				'label' => esc_html__('Select Icon', 'crt-manage'),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					'header_icon' => 'yes',
					'header_icon_type' => 'icon',
				]

			]
		);

		$repeater->add_control(
			'header_col_img',
			[
				'label' => esc_html__( 'Image', 'crt-manage'),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'header_icon_type'	=> 'image'
				]
			]
		);

		$repeater->add_responsive_control(
			'header_col_img_size',
			[
				'label' => esc_html__( 'Image Size', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500
					]
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 100,
					'unit' => 'px'
				],
				'desktop_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-data-table-th-img' => 'width: {{SIZE}}{{UNIT}} !important; height: auto !important;',
				],
				'condition' => [
					'header_icon_type'	=> 'image'
				]
			]
		);
		
		$repeater->add_control(
			'header_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}'
				],
				'condition' => [
					'header_icon' => 'yes',
					'header_icon_type'	=> 'icon'
				]
			]
		);

		$repeater->add_control(
			'header_th_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}'
				],
			]
		);

		$repeater->add_control(
			'header_th_background_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important'
				],
			]
		);

		$repeater->add_control(
			'header_colspan',
			[
				'label'			=> esc_html__( 'Col Span', 'crt-manage'),
				'type'			=> Controls_Manager::NUMBER,
				'default' 		=> 1,
				'min'     		=> 1,
				'separator' => 'before'
			]
		);

		$repeater->add_responsive_control(
			'th_individual_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'separator' => 'before',
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
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'table_header',
			[
				'label' => esc_html__( 'Repeater Table Header', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'table_th' => esc_html__( 'TABLE HEADER 1', 'crt-manage' ),
					],
					[
						'table_th' => esc_html__( 'TABLE HEADER 2', 'crt-manage' ),
					],
					[
						'table_th' => esc_html__( 'TABLE HEADER 3', 'crt-manage' ),
					],
					[
						'table_th' => esc_html__( 'TABLE HEADER 4', 'crt-manage' ),
					],
				],
				'title_field' => '{{{ table_th }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__('Content', 'crt-manage'),
				'condition' => [
					'choose_table_type' => 'custom'
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'table_content_row_type',
			[
				'label' => esc_html__( 'Row Type', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'default' => 'row',
				'label_block' => false,
				'options' => [
					'row' => esc_html__( 'Row', 'crt-manage'),
					'col' => esc_html__( 'Column', 'crt-manage'),
				]
			]
		);

		$repeater->add_control(
			'table_td', 
			[
				'label' => esc_html__( 'Content', 'crt-manage' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Content' , 'crt-manage' ),
				'show_label' => true,
				'separator' => 'before',
				'condition' => [
					'table_content_row_type' => 'col',
				]
			]
		);

		$repeater->add_control(
			'cell_link',
			[
				'label' => esc_html__( 'Content URL', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'crt-manage' ),
				'show_external' => true,
				'default' => [
					'url' => 'https://royal-elementor-addons.com/',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [
					'table_content_row_type' => 'col',
				]
			]
		);

		$repeater->add_responsive_control(
			'td_icon',
			[
				'label' => esc_html__('Media', 'crt-manage'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);
		$repeater->add_control(
			'td_icon_type',
			[
				'label' => esc_html__('Media Type', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__('Icon', 'crt-manage'),
					'image' => esc_html__('Image', 'crt-manage')
				],
				'condition' => [
					'td_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'td_icon_position',
			[
				'label' => esc_html__('Media Position', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => esc_html__('Left', 'crt-manage'),
					'right' => esc_html__('Right', 'crt-manage'),
					'top' => esc_html__('Top', 'crt-manage'),
					'bottom' => esc_html__('Bottom', 'crt-manage'),
				],
				'condition' => [
					'td_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'choose_td_icon',
			[
				'label' => esc_html__('Select Icon', 'crt-manage'),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					'td_icon' => 'yes',
					'td_icon_type' => 'icon'
				]

			]
		);

		$repeater->add_control(
			'td_col_img',
			[
				'label' => esc_html__( 'Image', 'crt-manage'),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'td_icon' => 'yes',
					'td_icon_type!'	=> ['none', 'icon']
				]
			]
		);

		$repeater->add_responsive_control(
			'td_col_img_size',
			[
				'label' => esc_html__( 'Image Size', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500
					]
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 100,
					'unit' => 'px'
				],
				'desktop_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} img' => 'width: {{SIZE}}{{UNIT}} !important; height: auto !important;',
				],
				'condition' => [
					'td_icon' => 'yes',
					'td_icon_type!'	=> ['none', 'icon']
				]
			]
		);

        $repeater->add_responsive_control(
            'td_col_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'crt-manage' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [
                    'px', 'em', 'rem',
				],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
					],
				],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .crt-td-content-wrapper i:not(.fa-question-circle)' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'td_icon' => 'yes',
					'td_icon_type!'	=> ['none', 'image']
				]
			]
        );

		$repeater->add_control(
			'td_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}'
				],
				'condition' 	=> [
					'table_content_row_type' => 'col',
					'td_icon' => 'yes',
					'td_icon_type' => 'icon'
				]
			]
		);

		$repeater->add_control( 'content_tooltip', $this->add_repeater_args_content_tooltip() );

		$repeater->add_control( 'content_tooltip_text', $this->add_repeater_args_content_tooltip_text() );

		$repeater->add_control( 'content_tooltip_show_icon', $this->add_repeater_args_content_tooltip_show_icon() );

		$repeater->add_control(
			'td_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .crt-table-text' => 'color: {{VALUE}} !important'
				],
			]
		);

		$repeater->add_control(
			'td_background_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important'
				],
			]
		);

		$repeater->add_control(
			'td_background_color_hover',
			[
				'label' => esc_html__( 'Background Color (Hover)', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'background-color: {{VALUE}} !important'
				],
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_control(
			'table_content_row_colspan',
			[
				'label'			=> esc_html__( 'Col Span', 'crt-manage'),
				'type'			=> Controls_Manager::NUMBER,
				'default' 		=> 1,
				'min'     		=> 1,
				'label_block'	=> false,
				'separator' => 'before',
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_control(
			'table_content_row_rowspan',
			[
				'label'			=> esc_html__( 'Row Span', 'crt-manage'),
				'type'			=> Controls_Manager::NUMBER,
				'default' 		=> 1,
				'min'     		=> 1,
				'label_block'	=> false,
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_responsive_control(
			'td_individual_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'separator' => 'before',
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
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}} !important;',
				],
			]
		);
	
		$this->add_control(
			'table_content_rows',
			[
				'label' => esc_html__( 'Repeater Table Rows', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'table_content_row_type' => 'row' ],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 1'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 2'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 3'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 4'
					],
					[ 'table_content_row_type' => 'row' ],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 1'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 2'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 3'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 4'
					],
				],
				'title_field' => '{{table_content_row_type}}::{{table_td}}',
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'data-table', [
			'Import Table data from CSV file upload or URL',
			'Show/Hide Export Table data buttons',
			'Enable Live Search for Tables',
			'Enable Table Sorting option',
			'Enable Table Pagination. Divide Table items by pages',
			'Enable Tooltips on each cell'
		] );

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__('General', 'crt-manage'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'table_responsive_width',
			[
				'label' => esc_html__( 'Table Min Width', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500
					]
				],
				// 'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 600,
					'unit' => 'px'
				],
				// 'desktop_default' => [
				// 	'size' => 100,
				// 	'unit' => '%',
				// ],
				// 'tablet_default' => [
				// 	'size' => 100,
				// 	'unit' => '%',
				// ],
				// 'mobile_default' => [
				// 	'size' => 100,
				// 	'unit' => '%',
				// ],
				'selectors' => [
					'{{WRAPPER}} .crt-table-container .crt-data-table' => 'min-width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .crt-export-search-inner-cont' => 'min-width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .crt-table-custom-pagination' => 'width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .crt-table-pagination-cont' => 'min-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-table-inner-container' => 'width: 100%;',
					'{{WRAPPER}} .crt-data-table' => 'width: 100%;',
				],
				// 'separator' => 'before'
			]
		);

		$this->add_control(
			'all_border_type',
			[
				'label' => esc_html__('Border', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-table-inner-container' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} th.crt-table-th' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} th.crt-table-th-pag' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} td.crt-table-td' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} td.crt-table-td-pag' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'all_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E4E4E4',
				'selectors' => [
					'{{WRAPPER}} .crt-table-inner-container' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} th.crt-table-th' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} th.crt-table-th-pag' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} td.crt-table-td' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} td.crt-table-td-pag' => 'border-color: {{VALUE}}'
				],
				'condition' => [
					'all_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'all_border_width',
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
					'{{WRAPPER}} .crt-table-inner-container' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} th.crt-table-th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} th.crt-table-th-pag' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} td.crt-table-td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} td.crt-table-td-pag' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'all_border_type!' => 'none',
				]
			]
		);

		$this->add_responsive_control(
			'header_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-table-inner-container' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} table' => 'border-radius: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} th:first-child' => 'border-top-left-radius: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} th:last-child' => 'border-top-right-radius: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} tr:last-child td:first-child' => 'border-bottom-left-radius: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} tr:last-child td:last-child' => 'border-bottom-right-radius: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control_export_buttons_distance();

		$this->add_control_table_search_input_padding();

		$this->add_control(
			'hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .crt-table-th' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .crt-table-th-pag' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .crt-table-th i' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .crt-table-th svg' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .crt-table-td' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .crt-table-td-pag' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .crt-table-td i' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .crt-table-td svg' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .crt-table-text' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size'
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'header_style',
			[
				'label' => esc_html__('Header', 'crt-manage'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->start_controls_tabs(
			'style_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'th_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} th' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'th_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} tr th' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'th_color_hover',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} th:hover' => 'color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->add_control(
			'th_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} tr th:hover' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'th_typography',
				'selector' => '{{WRAPPER}} th',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight'     => [
						'default' => '400',
					]
				],
			]
		);

		$this->add_responsive_control(
            'header_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'crt-manage'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 15,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .crt-data-table thead i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-data-table thead svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
				'separator' => 'before'
            ]
        );

		$this->add_responsive_control(
            'header_sorting_icon_size',
            [
                'label'      => esc_html__('Sorting Icon Size', 'crt-manage'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 12,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .crt-data-table thead .crt-sorting-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-data-table thead .crt-sorting-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
				'condition' => [
					'enable_table_sorting' => 'yes'
				]
            ]
        );

		$this->add_responsive_control(
			'header_padding',
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
					'{{WRAPPER}} th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

        $this->add_responsive_control(
            'header_image_space',
            [
                'label'      => esc_html__('Image Margin', 'crt-manage'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    // 'px' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
                    // '%' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .crt-data-table th img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

        $this->add_responsive_control(
            'header_icon_space',
            [
                'label'      => esc_html__('Icon Margin', 'crt-manage'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    // 'px' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
                    // '%' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .crt-data-table th i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-data-table th svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

		$this->add_responsive_control(
			'th_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'separator' => 'before',
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
				'prefix_class' => 'crt-table-align-items-',
				'selectors' => [
					'{{WRAPPER}} th:not(".crt-table-th-pag")' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-th-inner-cont' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-flex-column span' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-flex-column-reverse span' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-table-th' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'content_styles',
			[
				'label' => esc_html__('Content', 'crt-manage'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs(
			'cells_style_tabs'
		);

		$this->start_controls_tab(
			'cells_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'odd_cell_styles',
			[
				'label' => esc_html__('Odd Rows', 'crt-manage'),
				'type' => \Elementor\Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'odd_row_td_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					// '{{WRAPPER}} tr:nth-child(odd) td a' => 'color: {{VALUE}} !important',
					// '{{WRAPPER}} tr.crt-odd td.crt-table-text' => 'color: {{VALUE}}',
					// '{{WRAPPER}} tr.crt-odd td a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td.crt-table-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(odd) td a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td span' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'odd_row_td_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					// '{{WRAPPER}} tr.crt-odd td' => 'background-color: {{VALUE}}', // TODO: decide tr or td
					'{{WRAPPER}} tbody tr:nth-child(odd) td' => 'background-color: {{VALUE}}', // TODO: decide tr or td
				],
			]
		);

		$this->add_control(
			'even_cell_styles',
			[
				'label' => esc_html__('Even Rows', 'crt-manage'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'even_row_td_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					// '{{WRAPPER}} tr.crt-even td a .crt-table-text' => 'color: {{VALUE}} !important',
					// '{{WRAPPER}} tr.crt-even td.crt-table-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(even) td a .crt-table-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(even) td.crt-table-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(even) td.crt-table-td-pag' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'even_row_td_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F3F3F3',
				'selectors' => [
					// '{{WRAPPER}} tr.crt-even td' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} tbody tr:nth-child(even) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cells_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'odd_cell_hover_styles',
			[
				'label' => esc_html__('Odd Rows', 'crt-manage'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'odd_row_td_color_hover',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					// '{{WRAPPER}} tr.crt-odd td:hover a' => 'color: {{VALUE}} !important',
					// '{{WRAPPER}} tr.crt-odd td:hover.crt-table-text' => 'color: {{VALUE}} !important',
					// '{{WRAPPER}} tr.crt-odd td:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover span' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover.crt-table-text' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'odd_row_td_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					// '{{WRAPPER}} tr.crt-odd:hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
					'{{WRAPPER}} tbody tr:nth-child(odd):hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->add_control(
			'even_cell_hover_styles',
			[
				'label' => esc_html__('Even Rows', 'crt-manage'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'even_row_td_color_hover',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				// 'selectors' => [
				// 	'{{WRAPPER}} tr.crt-even td:hover.crt-table-text' => 'color: {{VALUE}}',
				// 	'{{WRAPPER}} tr.crt-even td:hover a .crt-table-text' => 'color: {{VALUE}} !important',
				// 	'{{WRAPPER}} tr.crt-even td:hover i' => 'color: {{VALUE}}',
				// ],
				'selectors' => [
					'{{WRAPPER}} tbody tr:nth-child(even) td:hover.crt-table-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(even) td:hover a .crt-table-text' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(even) td:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(even) td:hover svg' => 'fill: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'even_row_td_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					// '{{WRAPPER}} tr.crt-even:hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
					'{{WRAPPER}} tbody tr:nth-child(even):hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_active_td_bg_color();

		$this->add_control(
			'typograpphy_divider',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'td_typography',
				'selector' => '{{WRAPPER}} td, {{WRAPPER}} i.fa-question-circle',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight'     => [
						'default' => '400',
					]
				],
			]
		);

		$this->add_responsive_control(
            'tbody_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'crt-manage'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
				'separator' => 'before',
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 15,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .crt-data-table tbody i:not(.fa-question-circle)' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-data-table tbody svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-data-table tbody span:has(>svg)' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

		$this->add_responsive_control(
            'tbody_image_size',
            [
                'label'      => esc_html__('Image Size', 'crt-manage'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
                'default'    => [
                    'size' => 50,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .crt-data-table-th-img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

		$this->add_responsive_control(
			'td_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'separator' => 'before',
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'tbody_image_border_radius',
			[
				'label' => esc_html__( 'Image Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-data-table-th-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'td_img_space',
            [
                'label'      => esc_html__('Image Margin', 'crt-manage'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    // 'px' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
                    // '%' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .crt-data-table td img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

        $this->add_responsive_control(
            'td_icon_space',
            [
                'label'      => esc_html__('Icon Margin', 'crt-manage'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    // 'px' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
                    // '%' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .crt-data-table td i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-data-table td svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

		$this->add_responsive_control(
			'td_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'separator' => 'before',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => ' eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => ' eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => ' eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} td:not(".crt-table-td-pag")' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-td-content-wrapper span' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .crt-table-td' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->add_section_export_buttons_styles();

		$this->add_section_pagination_styles();

		$this->add_control_stack_content_tooltip_section();
	
    }

    public function render_content_tooltip($item) {
        if ( $item['content_tooltip'] === 'yes' && ! empty( $item['content_tooltip_text'] ) ) : ?>
            <div class="crt-data-table-content-tooltip"><?php echo wp_kses_post($item['content_tooltip_text']); ?></div>
        <?php endif;
    }

    public function render_tooltip_icon($item) {
        if ( 'yes' === $item['content_tooltip'] && 'yes' === $item['content_tooltip_show_icon'] ) {
            echo '&nbsp;&nbsp;<i class="far fa-question-circle"></i>';
        }
    }

    public function render_custom_pagination($settings, $countRows) {
        ?>

        <div class="crt-table-pagination-outer-cont">
            <div class="crt-table-pagination-cont">
                <?php if ( 'yes' === $settings['enable_entry_info'] ) : ?>
                    <div class="crt-entry-info"></div>
                <?php endif; ?>
                <ul class="crt-table-custom-pagination">
                    <div class="crt-table-custom-pagination-inner-cont">
                        <?php if ( 'none' !== $settings['pagination_nav_icons'] ) : ?>
                            <li class='crt-table-custom-pagination-prev crt-table-prev-next crt-table-custom-pagination-list'>
                                <?php
                                echo Utilities::get_crt_icon( $settings['pagination_nav_icons'], 'left');
                                ?>
                            </li>
                        <?php endif; ?>

                        <?php $total_rows = 0;
                        $item_index = 0;

                        if ( 'custom' === $settings['choose_table_type'] ) {
                            foreach ( $settings['table_content_rows'] as $item ) {
                                if ( 'row' === $item['table_content_row_type'] ) {
                                    $total_rows++;
                                }
                            }
                        }

                        // $exact_number_of_pages = $total_rows/$settings['table_items_per_page'];
                        $total_pages = 'custom' === $settings['choose_table_type'] ? ceil($total_rows/$settings['table_items_per_page']) : ceil($countRows/$settings['table_items_per_page']);

                        for (  $i = 1; $i <= $total_pages; $i++ ) {	?>

                            <li class="crt-table-custom-pagination-list crt-table-custom-pagination-list-item <?php echo $i === 1 ? 'crt-active-pagination-item' : ''; ?>">
                                <span><?php echo $i; ?></span>
                            </li>

                        <?php } ?>

                        <?php if ( 'none' !== $settings['pagination_nav_icons'] ) : ?>
                            <li class='crt-table-custom-pagination-next crt-table-prev-next crt-table-custom-pagination-list crt-table-prev-arrow crt-table-arrow'>
                                <?php echo Utilities::get_crt_icon( $settings['pagination_nav_icons'], 'right'); ?>
                            </li>
                        <?php endif; ?>
                    </div>
                </ul>
            </div>
        </div>

        <?php
    }

	protected function render_csv_data($url, $custom_pagination, $sorting_icon, $settings) {
		
		$url_ext = pathinfo($url, PATHINFO_EXTENSION);
		$url_ext2 = pathinfo($url);

		ob_start();
		if( $url_ext === 'csv' || str_contains($url_ext2['dirname'], 'docs.google.com/spreadsheets') ) {
			if (str_contains($url_ext2['dirname'], 'docs.google.com/spreadsheets')) {
				$url = $settings['table_insert_url']['url'];
			}
			echo $this->crt_parse_csv_to_table($url, $settings, $custom_pagination, $sorting_icon );
		} else {
			echo '<p class="crt-no-csv-file-found">'. esc_html__('Please provide a CSV file.', 'crt-manage') .'</p>';
		}
		return \ob_get_clean();

	}

	protected function crt_parse_csv_to_table($filename, $settings, $custom_pagination, $sorting_icon ) {
		$allowed_html = array(
			'a' => array(
				'href' => array(),
				'title' => array(),
				'target' => array(),
			),
			'b' => array(),
			'strong' => array(),
			'i' => array(),
			'em' => array(),
			'p' => array(),
			'br' => array(),
			'ul' => array(),
			'ol' => array(),
			'li' => array(),
			'span' => array(),
			'div' => array(
				'class' => array(),
			),
			'img' => array(
				'src' => array(),
				'alt' => array(),
				'width' => array(),
				'height' => array(),
			),
			// Add more allowed tags and attributes as needed
		);		

		$handle = fopen($filename, "r");
		
		// Determine the delimiter
		$delimiter = $this->detect_csv_delimiter($filename);
		//display header row if true
		echo '<table class="crt-append-to-scope crt-data-table">';
		if ( 'yes' === $settings['display_header'] ) {
			$csvcontents = fgetcsv($handle, 0, $delimiter);
			echo '<thead><tr class="crt-table-head-row crt-table-row">';
			foreach ($csvcontents as $headercolumn) {
				echo "<th class='crt-table-th crt-table-text'>". wp_kses($headercolumn, $allowed_html) . $sorting_icon ."</th>";
			}
			echo '</tr></thead>';
		}
		echo '<tbody>';

		// displaying contents
		$countRows = 0;
		$oddEven = '';
		while ($csvcontents = fgetcsv($handle, 0, $delimiter)) {
				$countRows++;
				$oddEven = $countRows % 2 == 0 ? 'crt-even' : 'crt-odd';
				echo '<tr class="crt-table-row  '. esc_attr($oddEven) .'">';
				foreach ($csvcontents as $column) {
					echo '<td class="crt-table-td crt-table-text">'. wp_kses($column, $allowed_html) .'</td>';
				}
				echo '</tr>';
		}
		echo '</tbody></table>';
		echo '</div>';
		echo '</div>';

		if ( 'yes' == $settings['enable_custom_pagination'] ) {
			$this->render_custom_pagination($settings, $countRows);
		} 

		fclose($handle);
	}

	protected function detect_csv_delimiter($filename) {
		$delimiters = [',', ';'];
		$counts = [];
		$maxCount = 0;
		$bestDelimiter = ',';
	
		$handle = fopen($filename, "r");
		$firstLine = fgets($handle);
		fclose($handle);
	
		foreach ($delimiters as $delimiter) {
			$counts[$delimiter] = count(str_getcsv($firstLine, $delimiter));
		}
	
		foreach ($counts as $delimiter => $count) {
			if ($count > $maxCount) {
				$maxCount = $count;
				$bestDelimiter = $delimiter;
			}
		}
	
		return $bestDelimiter;
	}

	public function render_th_icon($item) {
		ob_start();
		\Elementor\Icons_Manager::render_icon($item['choose_header_col_icon'], ['aria-hidden' => 'true']);
		return ob_get_clean();
	}

	public function render_th_icon_or_image($item, $i) {
		if ( $item['header_icon'] === 'yes' && $item['header_icon_type'] === 'icon' ) {
			$header_icon = '<span style="display: inline-block; vertical-align: middle;">'. $this->render_th_icon($item) . '</span>';
		}

		if( $item['header_icon'] == 'yes' && $item['header_icon_type'] == 'image' ) {
			$this->add_render_attribute('crt_table_th_img'. $i, [
				'src'	=> esc_url( $item['header_col_img']['url'] ),
				'class'	=> 'crt-data-table-th-img',
				'alt'	=> esc_attr(get_post_meta($item['header_col_img']['id'], '_wp_attachment_image_alt', true))
			]);

			$header_icon = '<img'.' '. $this->get_render_attribute_string('crt_table_th_img'. $i) . '>';
		}

		echo $header_icon;
	}

	public function render_td_icon($table_td, $j) {
		ob_start();
		\Elementor\Icons_Manager::render_icon($table_td[$j]['icon_item'], ['aria-hidden' => 'true']);
		return ob_get_clean();
	}

	public function render_td_icon_or_image($table_td, $j) {
		if ( $table_td[$j]['icon'] === 'yes' && $table_td[$j]['icon_type'] == 'icon' ) {
			$tbody_icon = '<span style="display: inline-block; vertical-align: middle;">'. $this->render_td_icon($table_td, $j) . '</span>';
		}

		if ( $table_td[$j]['icon'] == 'yes' && $table_td[$j]['icon_type'] == 'image' ) { 
            $this->add_render_attribute('crt_table_td_img'. esc_attr($j), [
                'src'	=> esc_url( $table_td[$j]['col_img']['url'] ),
                'class'	=> 'crt-data-table-th-img',
                'alt'	=> esc_attr(get_post_meta($table_td[$j]['col_img']['id'], '_wp_attachment_image_alt', true))
            ]);

			$tbody_icon = '<img' . ' ' . $this->get_render_attribute_string('crt_table_td_img'. esc_attr($j)) . '>';
		}

		echo $tbody_icon;
	}

    public function render_search_export() {
        $settings = $this->get_settings_for_display();

        if ( 'yes' === $settings['enable_table_search'] || 'yes' === $settings['enable_table_export'] ) {

            echo '<div class="crt-export-search-cont">';
            echo '<div class="crt-export-search-inner-cont">';

            if ( 'yes' === $settings['enable_table_export'] ) {
                echo '<div class="crt-table-export-button-cont">';
                if ( '' !== $settings['export_excel_text'] ) {
                    echo '<button class="crt-button crt-xls">'. $settings['export_excel_text'] .'</button>';
                }
                if ( '' !== $settings['export_csv_text'] ) {
                    echo '<button class="crt-button crt-csv">'. $settings['export_csv_text'] .'</button>';
                }
                echo '</div>';
            }

            if ( 'yes' === $settings['enable_table_search'] ) {
                echo '<div class="crt-table-live-search-cont">';
                echo '<input type="search" class="crt-table-live-search" placeholder="'. esc_attr($settings['search_placeholder']) .'">';
                echo '<i class="fas fa-search crt-search-input-icon"></i>';
                echo '</div>';
            }

            echo '</div>';
            echo '</div>';

        }
    }

    // Add this function to your class
	private function custom_wp_kses($content) {
		$allowed_html = array(
			'a' => array(
				'href' => array(),
				'title' => array(),
				'target' => array(),
			),
			'b' => array(),
			'strong' => array(),
			'i' => array(),
			'em' => array(),
			'br' => array(),
			'ul' => array(),
			'ol' => array(),
			'li' => array(),
			'span' => array(),
			'img' => array(
				'src' => array(),
				'alt' => array(),
				'width' => array(),
				'height' => array(),
			),
			'div' => array(
				'class' => array(),
			),
			'p' => array(),
			// Add the custom add-to-calendar-button element
			'add-to-calendar-button' => array(
				'name' => array(),
				'debug' => array(),
				'stylelight' => array(),
				'startdate' => array(),
				'starttime' => array(),
				'endtime' => array(),
				'enddate' => array(),
				'timezone' => array(),
				'label' => array(),
				'options' => array(),
				'lightmode' => array(),
				'size' => array(),
				'description' => array(),
				'dates' => array(),
			),
		);

		return wp_kses($content, $allowed_html);
	}

    protected function render() {
		$settings = $this->get_settings_for_display(); 

		$table_tr = [];
		$table_td = [];
		?>

		<?php

		// Render Search and/or Export Buttons
		$this->render_search_export();
		
		$x = 0;
		
		$sorting_icon = ('yes' === $settings['enable_table_sorting'] ) ? '<span class="crt-sorting-icon"><i class="fas fa-sort"></i></span>' : '';
		
		$this->add_render_attribute(
			'crt_table_inner_container_attributes',
			[
				'class' => ['crt-table-inner-container', 'yes' === $settings['enable_custom_pagination'] ? 'crt-hide-table-before-arrange' : ''],
				// 'data-table-columns' => !empty($settings['columns_number']) ? $settings['columns_number'] : '',
				'data-table-sorting' => $settings['enable_table_sorting'],
				'data-custom-pagination' => $settings['enable_custom_pagination'],
				'data-row-pagination' => $settings['enable_row_pagination'],
				'data-entry-info' => $settings['enable_entry_info'],
				'data-rows-per-page' => isset($settings['table_items_per_page']) ? $settings['table_items_per_page'] : ''
			]
		);

		?>
		
		<div class="crt-table-container">
		<div <?php echo $this->get_render_attribute_string( 'crt_table_inner_container_attributes' ); ?>>

		<?php if ( isset($settings['choose_csv_type']) && 'file' === $settings['choose_csv_type'] ) {

			echo $this->render_csv_data($settings['table_upload_csv']['url'], $settings['enable_custom_pagination'], $sorting_icon, $settings);

		} elseif ( isset($settings['choose_csv_type']) && 'url' === $settings['choose_csv_type']) {

			echo $this->render_csv_data(esc_url($settings['table_insert_url']['url']), esc_attr($settings['enable_custom_pagination']), $sorting_icon, $settings);

		} else {

			// Storing Data table content values
			$countRows = 0;
			foreach( $settings['table_content_rows'] as $content_row ) {
				$countRows++;
				$oddEven = $countRows % 2 == 0 ? 'crt-even' : 'crt-odd';
				$row_id = uniqid();

				if( $content_row['table_content_row_type'] == 'row' ) {
					$table_tr[] = [
						'id' => $row_id,
						'type' => $content_row['table_content_row_type'],
						'class' => ['crt-table-body-row', 'crt-table-row', 'elementor-repeater-item-'. esc_attr($content_row['_id']), esc_attr($oddEven)]
					];
				}

				if( $content_row['table_content_row_type'] == 'col' ) {

					$table_tr_keys = array_keys( $table_tr );
					$last_key = end( $table_tr_keys );

					$table_td[] = [
						'row_id' => isset($table_tr[$last_key]['id']) ? $table_tr[$last_key]['id'] : '',
						'type' => $content_row['table_content_row_type'],
						'content' => $content_row['table_td'],
						'colspan' => $content_row['table_content_row_colspan'],
						'rowspan' => $content_row['table_content_row_rowspan'],
						'link' => $content_row['cell_link'],
						'external' => $content_row['cell_link']['is_external'] == true ? '_blank' : '_self',
						'icon_type' => $content_row['td_icon_type'],
						'icon' => $content_row['td_icon'],
						'icon_position' => $content_row['td_icon_position'],
						'icon_item' => $content_row['choose_td_icon'],
						'col_img' => $content_row['td_col_img'],
						'class' => ['elementor-repeater-item-'. esc_attr($content_row['_id']), 'crt-table-td'],
						'content_tooltip' => $content_row['content_tooltip'],
						'content_tooltip_text' => $content_row['content_tooltip_text'],
						'content_tooltip_show_icon' => $content_row['content_tooltip_show_icon']
					];
				}
			} ?>

			<table class="crt-data-table" id="crt-data-table">
			<?php if ( $settings['table_header'] ) { ?>
					
				<thead>
					<tr class="crt-table-head-row crt-table-row">
					<?php $i = 0; foreach ($settings['table_header'] as $item) { 

						$this->add_render_attribute('th_class'. esc_attr($i), [
							'class' => ['crt-table-th', 'elementor-repeater-item-'. esc_attr($item['_id'])],
							'colspan' => $item['header_colspan'],
						]); 
						
						$this->add_render_attribute('th_inner_class'. esc_attr($i), [
							'class' => [($item['header_icon_position'] === 'top') ? 'crt-flex-column-reverse' : (($item['header_icon_position'] === 'bottom') ? 'crt-flex-column' : '')],
						]); ?>

						<th <?php echo $this->get_render_attribute_string('th_class'. esc_attr($i)); ?>>
							<div <?php echo $this->get_render_attribute_string('th_inner_class'. esc_attr($i)); ?>>
								<?php $item['header_icon'] === 'yes'  && $item['header_icon_position'] == 'left' ? $this->render_th_icon_or_image($item, $i) : '' ?>
								
								<?php if ( '' !== $item['table_th'] ) :  ?>
									<span class="crt-table-text"><?php echo esc_html($item['table_th']); ?></span>
								<?php endif; ?>
								<?php $item['header_icon'] === 'yes' && $item['header_icon_position'] == 'right' ? $this->render_th_icon_or_image($item, $i) : '' ?>
								<?php echo $sorting_icon; ?>
								<?php $item['header_icon'] === 'yes' && ($item['header_icon_position'] == 'top' || $item['header_icon_position'] == 'bottom')? $this->render_th_icon_or_image($item, $i) : '' ?>
								<?php echo $sorting_icon; ?>
							</div>
						</th>
						<?php $i++; } ?>
					</tr>
				</thead>

				<tbody>
				<?php for( $i = 0 + $x; $i < count( $table_tr ) + $x; $i++ ) :

						$this->add_render_attribute('table_row_attributes'. esc_attr($i), [
							'class' => $table_tr[$i]['class'],
						]);

						?>
					<tr <?php echo $this->get_render_attribute_string('table_row_attributes'. esc_attr($i)) ?>>
					<?php for( $j = 0; $j < count( $table_td ); $j++ ) {
							if( $table_tr[$i]['id'] == $table_td[$j]['row_id'] ) {
								$this->add_render_attribute('tbody_td_attributes'. esc_attr($i . $j), [
								'colspan' => $table_td[$j]['colspan'] > 1 ? $table_td[$j]['colspan'] : '',
								'rowspan' => $table_td[$j]['rowspan'] > 1 ? $table_td[$j]['rowspan'] : '',
								'class' => $table_td[$j]['class']
								]); ?>
								
							<td <?php echo $this->get_render_attribute_string('tbody_td_attributes'. esc_attr($i . $j)); ?>>

								<div class="crt-td-content-wrapper <?php echo esc_attr(('top' === $table_td[$j]['icon_position']) ? 'crt-flex-column' : (('bottom' === $table_td[$j]['icon_position']) ? 'crt-flex-column-reverse' : '')) ?>">

									<?php $table_td[$j]['icon'] === 'yes' && ($table_td[$j]['icon_position'] === 'left' || $table_td[$j]['icon_position'] === 'top' || $table_td[$j]['icon_position'] === 'bottom') ? $this->render_td_icon_or_image($table_td, $j) : '' ?>
									<?php if ( '' !== $table_td[$j]['content'] ) : 
										  if ( '' !== $table_td[$j]['link']['url'] ) : ?>
											<a href="<?php echo esc_url($table_td[$j]['link']['url']) ?>" target="<?php echo esc_attr($table_td[$j]['external']) ?>">
									<?php else : ?>
											<span>
									<?php endif; ?> 
											<span class="crt-table-text">
												<?php 
													echo $this->custom_wp_kses($table_td[$j]['content']);

													$this->render_tooltip_icon( $table_td[$j] );
													
													$this->render_content_tooltip( $table_td[$j] ); 
												?>
											</span>
										<?php if ( '' !== $table_td[$j]['link']['url'] ) : ?>
										</a>
										<?php else : ?>
										</span>
										<?php endif; ?>
									<?php endif;  ?>
									<?php $table_td[$j]['icon'] === 'yes' && $table_td[$j]['icon_position'] === 'right' ? $this->render_td_icon_or_image($table_td, $j) : '' ?>

								</div>

							</td>
							<?php }
							} ?>
					</tr>
			        <?php endfor; ?>
				</tbody>
			</table>
		</div>
		</div>
    	<?php }
			if ( 'yes' == $settings['enable_custom_pagination'] ) {
				$this->render_custom_pagination($settings, null);
			}
		}
  	}
}