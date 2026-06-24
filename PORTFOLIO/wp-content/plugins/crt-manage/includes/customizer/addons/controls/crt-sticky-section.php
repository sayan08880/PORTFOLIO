<?php
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Core\Base\Module;
use Elementor\Core\Kits\Documents\Tabs\Settings_Layout;
use Elementor\Core\Responsive\Files\Frontend;
use Elementor\Plugin;
use Elementor\Core\Breakpoints\Manager;
use Elementor\Core\Breakpoints;
use Elementor\Group_Control_Box_Shadow;
use CrtAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Crt_Sticky_Section {

    public function __construct() {
		add_action( 'elementor/element/section/section_background/after_section_end', [ $this, 'register_controls' ], 10 );
		add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );
		add_action( 'elementor/frontend/section/before_render', [ $this, '_before_render' ], 10, 1 );

        // FLEXBOX
        add_action('elementor/element/container/section_layout/after_section_end', [$this, 'register_controls'], 10);
        add_action( 'elementor/container/print_template', [ $this, '_print_template' ], 10, 2 );
        add_action('elementor/frontend/container/before_render', [$this, '_before_render'], 10, 1);
    }

    public static function add_control_group_sticky_advanced_options($element) {

        $element->add_control(
            'sticky_advanced_options_heading',
            [
                'label' => esc_html__( 'Advanced', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'position_location'	=> 'top'
                ],
            ]
        );

        // All pro
        $element->add_control (
            'sticky_advanced_options',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Enable Advanced Options', 'crt-manage' ),
                'description' => 'Please note that <strong>Advanced Options</strong> are designed to work only with <strong>Header Sections</strong>.',
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'position_location'	=> 'top'
                ]
            ]
        );

        $element->add_responsive_control(
            'crt_sticky_effects_offset',
            [
                'label' => __( 'Scroll Top Distance', 'crt-manage' ), // SHOULD WORK AFTER STICKING
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Set the distance to start the effect when the Top of the page touches the Sticky section.', 'crt-manage'),
                'min' => 0,
                'required' => true,
                'frontend_available' => true,
                'render_type' => 'template',
                'default' => 0,
                'widescreen_default' => 0,
                'laptop_default' => 0,
                'tablet_extra_default' => 0,
                'tablet_default' => 0,
                'mobile_extra_default' => 0,
                'mobile_default' => 0,
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top'
                ],
                'separator' => 'before'
            ]
        );

        $element->add_control ( // NEXT HIDDEN SECTION
            'sticky_replace_header',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Replace with New Section', 'crt-manage' ),
                'description' => esc_html__('After enabling this option, the next section will replace this section when it becomes sticky. The next section will be automatically hidden on page load.', 'crt-manage'),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top'
                ],
                'prefix_class' => 'crt-sticky-replace-header-',
                'separator' => 'before',
                'render_type' => 'template',
            ]
        );

        $element->add_control ( // NEXT HIDDEN SECTION
            'sticky_shrink_section',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Custom Height', 'crt-manage' ),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ],
                'separator' => 'before',
                'prefix_class' => 'crt-sticky-custom-height-'
            ]
        );

        $element->add_responsive_control(
            'sticky_shrink_size',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Section Height', 'crt-manage' ),
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ]
                ],
                'default' => [
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}}.crt-sticky-header .elementor-container' => 'min-height: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_advanced_options' => 'yes',
                    'sticky_shrink_section' => 'yes',
                    'sticky_replace_header!' => 'yes'
                ]
            ]
        );

        $element->add_control (
            'sticky_background',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Custom Colors (Beta)', 'crt-manage' ),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ],
                'separator' => 'before',
                'prefix_class' => 'crt-sticky-custom-colors-'
            ]
        );

        $element->add_control(
            'sticky_text_color',
            [
                'label' => esc_html__( 'Text Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.crt-sticky-header *:not(.sub-menu *)' => 'color: {{VALUE}} !important;', // CHECK SELECTORS - LOGO MENU & maybe BUTTON
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_background' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ]
            ]
        );

        $element->add_control(
            'sticky_link_color',
            [
                'label' => esc_html__( 'Link Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.crt-sticky-header a:not(.sub-menu a)' => 'color: {{VALUE}} !important;', // CHECK SELECTORS - LOGO MENU & maybe BUTTON
                    '{{WRAPPER}}.crt-sticky-header a:not(.sub-menu a) *' => 'color: {{VALUE}} !important;'
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_background' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ]
            ]
        );

        // $element->add_control(
        // 	'sticky_logo_color',
        // 	[
        // 		'label' => esc_html__( 'Logo Color', 'crt-manage' ),
        // 		'type' => Controls_Manager::COLOR,
        // 		'selectors' => [
        // 			'{{WRAPPER}}.crt-sticky-header .crt-logo' => 'color: {{VALUE}} !important;', // CHECK SELECTORS - LOGO MENU & maybe BUTTON
        // 			'{{WRAPPER}}.crt-sticky-header .crt-logo *' => 'color: {{VALUE}} !important;'
        // 		],
        // 		'condition' => [
        // 			'sticky_background' => 'yes',
        // 			'sticky_advanced_options' => 'yes'
        // 		]
        // 	]
        // );

        $element->add_control(
            'sticky_background_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.crt-sticky-header' => 'background-color: {{VALUE}} !important; z-index: 9999 !important;',
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_background' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ]
            ]
        );

        $element->add_control (
            'sticky_logo_scale',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Logo Scale', 'crt-manage' ), // Show Number Input 0.7 default
                'description' => esc_html__( 'Works with CRT Builder Logo widget.', 'crt-manage' ),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ],
                'separator' => 'before',
                'prefix_class' => 'crt-sticky-scale-logo-'
            ]
        );

        $element->add_control(
            'sticky_logo_scale_size',
            [
                'label' => esc_html__( 'Logo Size %', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 70,
                'min' => 10,
                'max' => 100,
                'step' => 5,
                'selectors' => [
                    '{{WRAPPER}}.crt-sticky-header .crt-logo-image' => 'width: {{VALUE}}%', // ADD CONTROL FOR ANIMATION TIMINGS
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_logo_scale' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ]
            ]
        );

        $element->add_control(
            'sticky_trans_duration',
            [
                'label' => esc_html__( 'Transition Time', 'crt-manage' ),
                'description' => esc_html__('Set a trinsition time for Custom Height animation, Custom Colors and Logo Scale.', 'crt-manage'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}}.crt-sticky-custom-height-yes' => 'transition: all {{VALUE}}s linear !important;', // ADD CONTROL FOR ANIMATION TIMINGS
                    '{{WRAPPER}}.crt-sticky-scale-logo-yes .crt-logo' => 'transition: all {{VALUE}}s linear !important;', // ADD CONTROL FOR ANIMATION TIMINGS
                    '{{WRAPPER}}.crt-sticky-custom-colors-yes span' => 'transition: all {{VALUE}}s linear !important;',
                    '{{WRAPPER}}.crt-sticky-custom-colors-yes a' => 'transition: all {{VALUE}}s linear !important;',
                    '{{WRAPPER}}.crt-sticky-custom-colors-yes button' => 'transition: all {{VALUE}}s linear !important;',
                    '{{WRAPPER}}.crt-sticky-custom-colors-yes *::before' => 'transition: all {{VALUE}}s linear !important;'
                    // '{{WRAPPER}}' => 'transition: background {{VALUE}}s, border {{VALUE}}s, border-radius {{VALUE}}s, box-shadow {{VALUE}}s;',
                    // '{{WRAPPER}} *' => 'transition: background {{VALUE}}s, border {{VALUE}}s, border-radius {{VALUE}}s, box-shadow {{VALUE}}s;'

                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ],
                'separator' => 'before'
            ]
        );

        $element->add_control ( // NEXT HIDDEN SECTION
            'crt_sticky_section_border',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Custom Border', 'crt-manage' ),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ],
                'separator' => 'before'
            ]
        );

        $element->add_control(
            'crt_sticky_section_border_type',
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
                    '{{WRAPPER}}.crt-sticky-header' => 'border-style: {{VALUE}};'
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes',
                    'crt_sticky_section_border' => 'yes'
                ]
            ]
        );

        $element->add_control(
            'crt_sticky_section_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}}.crt-sticky-header' => 'border-color: {{VALUE}}'
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes',
                    'crt_sticky_section_border' => 'yes',
                    'crt_sticky_section_border_type!' => 'none'
                ]
            ]
        );

        $element->add_control(
            'crt_sticky_section_border_width',
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
                    '{{WRAPPER}}.crt-sticky-header' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes',
                    'crt_sticky_section_border' => 'yes',
                    'crt_sticky_section_border_type!' => 'none'
                ]
            ]
        );

        $element->add_control(
            'crt_sticky_section_border_radius',
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
                    '{{WRAPPER}}.crt-sticky-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes',
                    'crt_sticky_section_border' => 'yes',
                    'crt_sticky_section_border_type!' => 'none'
                ]
            ]
        );

        $element->add_control ( // NEXT HIDDEN SECTION
            'crt_sticky_section_bs',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Custom Shadow', 'crt-manage' ),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ],
                'separator' => 'before'
            ]
        );

        $element->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'crt_sticky_section_box_shadow',
                'selector' => '{{WRAPPER}}.crt-sticky-header',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes',
                    'crt_sticky_section_bs' => 'yes'
                ]
            ]
        );

        $element->add_control (
            'sticky_hide',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Show on Scrolling Up', 'crt-manage' ),
                'description' => esc_html__('If the section is sticky and page is scrolled Down, this section will be hidden and will only show up when the page is scrolled Up.', 'crt-manage'),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top'
                ],
                'separator' => 'before'
            ]
        );

        $element->add_control( // TRANSITION
            'sticky_animation',
            [
                'label' => esc_html__( 'Select Animation', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => 'None',
                    'fade' => 'Fade',
                    'slide' => 'Slide'
                ],
                'frontend_available' => true,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'enable_sticky_section',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'sticky_advanced_options',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'position_location',
                            'operator' => '=',
                            'value' => 'top',
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'sticky_replace_header',
                                    'operator' => '=',
                                    'value' => 'yes',
                                ],
                                [
                                    'name' => 'sticky_hide',
                                    'operator' => '=',
                                    'value' => 'yes',
                                ],
                            ],
                        ],
                    ],
                ],
                'separator' => 'before',
                'render_type' => 'template'
            ]
        );

        $element->add_control(
            'sticky_animation_duration',
            [
                'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}}' => '--crt-animation-duration: {{VALUE}}s', // ADD CONTROL FOR ANIMATION TIMINGS

                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'enable_sticky_section',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'sticky_advanced_options',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'position_location',
                            'operator' => '=',
                            'value' => 'top',
                        ],
                        [
                            'name' => 'sticky_animation',
                            'operator' => '!=',
                            'value' => 'none',
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'sticky_replace_header',
                                    'operator' => '=',
                                    'value' => 'yes',
                                ],
                                [
                                    'name' => 'sticky_hide',
                                    'operator' => '=',
                                    'value' => 'yes',
                                ],
                            ],
                        ],
                    ],
                ],
                'render_type' => 'template',
            ]
        );
    }


    public function register_controls( $element ) {

        if ( ( 'section' === $element->get_name() || 'container' === $element->get_name() ) ) {

            $element->start_controls_section (
                'crt_section_sticky_section',
                [
                    'tab'   => Controls_Manager::TAB_ADVANCED,
                    'label' =>  sprintf(esc_html__('Sticky Section - %s', 'crt-manage'), Utilities::get_plugin_name()),
                ]
            );

            $element->add_control(
                'crt_sticky_apply_changes',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<div class="elementor-update-preview-button editor-crt-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button></div>',
                    'separator' => 'after'
                ]
            );

            $element->add_control (
                'enable_sticky_section',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label' => esc_html__( 'Make This Section Sticky', 'crt-manage' ),
                    'default' => 'no',
                    'return_value' => 'yes',
                    'prefix_class' => 'crt-sticky-section-',
                    'render_type' => 'template',
                ]
            );

            $element->add_control(
                'enable_on_devices',
                [
                    'label' => esc_html__( 'Enable on Devices', 'crt-manage' ),
                    'label_block' => true,
                    'type' => Controls_Manager::SELECT2,
                    'default' => ['desktop_sticky'],
                    'options' => $this->breakpoints_manager(),
                    'multiple' => true,
                    'separator' => 'before',
                    'condition' => [
                        'enable_sticky_section' => 'yes'
                    ],

                ]
            );

            $element->add_control (
                'position_type',
                [
                    'label' => __( 'Position Type', 'crt-manage' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'sticky',
                    'options' => [
                        'sticky'  => __( 'Stick on Scroll', 'crt-manage' ),
                        'fixed' => __( 'Fixed by Default', 'crt-manage' ),
                    ],
                    // 'selectors' => [
                    // 	'{{WRAPPER}}' => 'position: {{VALUE}};',
                    // ],
                    'render_type' => 'template',
                    'condition' => [
                        'enable_sticky_section' => 'yes'
                    ],
                ]
            );

            $element->add_control (
                'sticky_type',
                [
                    'label' => __( 'Sticky Relation', 'crt-manage' ),
                    'type' => Controls_Manager::SELECT,
                    'description' => __('Please switch to *Window* if you are going to use <span style="color: red;">*Advanced Options*</span>.', 'crt-manage'),
                    'default' => 'sticky',
                    'options' => [
                        'sticky'  => __( 'Parent', 'crt-manage' ),
                        'fixed' => __( 'Window', 'crt-manage' ),
                    ],
                    'render_type' => 'template',
                    'condition' => [
                        'enable_sticky_section' => 'yes',
                        'position_type' => 'sticky'
                    ],
                ]
            );

            $element->add_control (
                'position_location',
                [
                    'label' => __( 'Location', 'crt-manage' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'top',
                    'render_type' => 'template',
                    'options' => [
                        'top' => __( 'Top', 'crt-manage' ),
                        'bottom'  => __( 'Bottom', 'crt-manage' ),
                    ],
                    // 'selectors_dictionary' => [
                    // 	'top' => 'top: {{position_offset.VALUE}}px; bottom: auto;',
                    // 	'bottom' => 'bottom: {{position_offset.VALUE}}px; top: auto;'
                    // ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'top: auto; bottom: auto; {{VALUE}}: {{position_offset.VALUE}}px;',
                    ],
                    'condition' => [
                        'enable_sticky_section' => 'yes'
                    ]
                ]
            );

            $element->add_responsive_control(
                'position_offset',
                [
                    'label' => __( 'Offset', 'crt-manage' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 500,
                    'required' => true,
                    'frontend_available' => true,
                    'render_type' => 'template',
                    'default' => 0,
                    'widescreen_default' => 0,
                    'laptop_default' => 0,
                    'tablet_extra_default' => 0,
                    'tablet_default' => 0,
                    'mobile_extra_default' => 0,
                    'mobile_default' => 0,
                    'selectors' => [
                        '{{WRAPPER}}' => 'top: auto; bottom: auto; {{position_location.VALUE}}: {{VALUE}}px;',
                        '{{WRAPPER}} + .crt-hidden-header' => 'top: {{VALUE}}px;',
                        '{{WRAPPER}} + .crt-hidden-header-flex' => 'top: {{VALUE}}px;'
                    ],
                    'condition' => [
                        'enable_sticky_section' => 'yes'
                    ],
                ]
            );

            $element->add_control(
                'crt_z_index',
                [
                    'label' => esc_html__( 'Z-Index', 'crt-manage' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => -99,
                    'max' => 99999,
                    'step' => 1,
                    'default' => 10,
                    'selectors' => [
                        '{{WRAPPER}}' => 'z-index: {{VALUE}};',
                        '.crt-hidden-header' => 'z-index: {{VALUE}};',
                        '.crt-hidden-header-flex' => 'z-index: {{VALUE}};'
                    ],
                    'condition' => [
                        'enable_sticky_section' => 'yes'
                    ],
                    'render_type' => 'template'
                ]
            );

            $element->add_control(
                'custom_breakpoints',
                [
                    'label' => __( 'Breakpoints', 'crt-manage' ),
                    'type' => \Elementor\Controls_Manager::HIDDEN,
                    'default' => get_option('elementor_experiment-additional_custom_breakpoints'),
                    'condition' => [
                        'enable_sticky_section' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'active_breakpoints',
                [
                    'label' => __( 'Active Breakpoints', 'crt-manage' ),
                    'type' => \Elementor\Controls_Manager::HIDDEN,
                    'default' => $this->breakpoints_manager_active(),
                    'condition' => [
                        'enable_sticky_section' => 'yes'
                    ]
                ]
            );

            $this->add_control_group_sticky_advanced_options($element);

            $element->end_controls_section();
        }
    }

    public function breakpoints_manager() {
		$active_breakpoints = [];
		foreach ( \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints() as $key => $value ) {
			$active_breakpoints[$key . '_sticky'] = esc_html__(ucwords(preg_replace('/_/i', ' ', $key)), 'crt-manage');
		}

		$active_breakpoints['desktop_sticky'] = esc_html__('Desktop', 'crt-manage');
		return $active_breakpoints;
	}

	public function breakpoints_manager_active() {
		$active_breakpoints = [];

		foreach ( $this->breakpoints_manager() as $key => $value ) {
			array_push($active_breakpoints, $key);
		}

		return $active_breakpoints;
	}
    
    public function _before_render( $element ) {
        if ( $element->get_name() !== 'section' && $element->get_name() !== 'container' ) {
            return;
        }
		
        $settings = $element->get_settings_for_display();

		if ($settings['enable_sticky_section'] !== 'yes') return;

		$crt_sticky_effects_offset_widescreen = isset($settings['crt_sticky_effects_offset_widescreen']) && !empty($settings['crt_sticky_effects_offset_widescreen']) ? $settings['crt_sticky_effects_offset_widescreen'] : 0;
		$crt_sticky_effects_offset_desktop = isset($settings['crt_sticky_effects_offset']) && !empty($settings['crt_sticky_effects_offset']) ? $settings['crt_sticky_effects_offset'] : $crt_sticky_effects_offset_widescreen;
		$crt_sticky_effects_offset_laptop =  isset($settings['crt_sticky_effects_offset_laptop']) && !empty($settings['crt_sticky_effects_offset_laptop']) ? $settings['crt_sticky_effects_offset_laptop'] : $crt_sticky_effects_offset_desktop;
		$crt_sticky_effects_offset_tablet_extra =  isset($settings['crt_sticky_effects_offset_tablet_extra']) && !empty($settings['crt_sticky_effects_offset_tablet_extra']) ? $settings['crt_sticky_effects_offset_tablet_extra'] : $crt_sticky_effects_offset_laptop;
		$crt_sticky_effects_offset_tablet =  isset($settings['crt_sticky_effects_offset_tablet']) && !empty($settings['crt_sticky_effects_offset_tablet']) ? $settings['crt_sticky_effects_offset_tablet'] : $crt_sticky_effects_offset_tablet_extra;
		$crt_sticky_effects_offset_mobile_extra =  isset($settings['crt_sticky_effects_offset_mobile_extra']) && !empty($settings['crt_sticky_effects_offset_mobile_extra']) ? $settings['crt_sticky_effects_offset_mobile_extra'] : $crt_sticky_effects_offset_tablet;
		$crt_sticky_effects_offset_mobile =  isset($settings['crt_sticky_effects_offset_mobile']) && !empty($settings['crt_sticky_effects_offset_mobile']) ? $settings['crt_sticky_effects_offset_mobile'] : $crt_sticky_effects_offset_mobile_extra;
		
		$allowed_positions = ['top', 'bottom']; // Define allowed positions
		// $position_location = isset( $_POST['position_location'] ) ? $_POST['position_location'] : ''; // TODO: Check if this is needed
		$position_location = $settings['position_location'];

		if ( ! in_array( $position_location, $allowed_positions ) ) {
			$position_location = 'top';
		} else {
			$position_location = sanitize_text_field( $position_location );
		}
		
        if ( $settings['enable_sticky_section'] === 'yes' ) {
            $element->add_render_attribute( '_wrapper', [
                'data-crt-sticky-section' => $settings['enable_sticky_section'],
                'data-crt-position-type' => $settings['position_type'],
                'data-crt-position-offset' => $settings['position_offset'],
                'data-crt-position-location' => $position_location,
				'data-crt-sticky-devices' => $settings['enable_on_devices'],
				'data-crt-custom-breakpoints' => $settings['custom_breakpoints'],
				'data-crt-active-breakpoints' => $this->breakpoints_manager_active(),
				'data-crt-z-index' => $settings['crt_z_index'],
				'data-crt-sticky-hide' => isset($settings['sticky_hide']) ? $settings['sticky_hide'] : '',
				'data-crt-replace-header' => isset($settings['sticky_replace_header']) ? $settings['sticky_replace_header'] : '',
				'data-crt-animation-duration' => isset($settings['sticky_animation_duration']) ? $settings['sticky_animation_duration'] : '',
				'data-crt-sticky-type' => isset($settings['sticky_type']) ? $settings['sticky_type'] : '',
				// 'data-crt-offset-settings' => wp_json_encode([
				// 	'widescreen' => $crt_sticky_effects_offset_widescreen,
				// 	'desktop' => $crt_sticky_effects_offset_desktop,
				// 	'laptop' => $crt_sticky_effects_offset_laptop,
				// 	'tablet_extra' => $crt_sticky_effects_offset_tablet_extra,
				// 	'tablet' => $crt_sticky_effects_offset_tablet,
				// 	'mobile_extra' => $crt_sticky_effects_offset_mobile_extra,
				// 	'mobile' => $crt_sticky_effects_offset_mobile
				// ])
            ] );
        }
    }

    public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' && $widget->get_name() !== 'container' ) {
			return $template;
		}

		ob_start();

		?>

		<# if ( 'yes' === settings.enable_sticky_section) { #>
			<# if ( 'top' !== settings.position_location && 'bottom' !== settings.position_location ) {
				settings.position_location = 'top';
			} #>

			<div class="crt-sticky-section-yes-editor" data-crt-z-index={{{settings.crt_z_index}}} data-crt-sticky-section={{{settings.enable_sticky_section}}} data-crt-position-type={{{settings.position_type}}} data-crt-position-offset={{{settings.position_offset}}} data-crt-position-location={{{settings.position_location}}} data-crt-sticky-devices={{{settings.enable_on_devices}}} data-crt-custom-breakpoints={{{settings.custom_breakpoints}}} data-crt-active-breakpoints={{{settings.active_breakpoints}}} data-crt-sticky-animation={{{settings.sticky_animation}}}  data-crt-offset-settings={{{settings.crt_sticky_effects_offset}}} data-crt-sticky-type={{{settings.sticky_type}}}></div>
		<# } #>

		<?php
		
		// how to render attributes without creating new div using view.addRenderAttributes
		$particles_content = ob_get_contents();

		ob_end_clean();

		return $template . $particles_content;
	}
}

new Crt_Sticky_Section();