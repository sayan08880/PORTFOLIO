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


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class CRT_GS_Animation {

    public function __construct() {
        add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ], 10 );
        add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ], 10 );
        add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_controls' ], 10 );
        add_action( 'elementor/element/container/section_layout/after_section_end', [$this, 'register_controls'], 10);
        add_action( 'elementor/frontend/widget/before_render', [ $this, 'render_attributes' ] );
        add_action( 'elementor/frontend/section/before_render', [ $this, 'render_attributes' ] );
        add_action( 'elementor/frontend/column/before_render', [ $this, 'render_attributes' ] );
        add_action( 'elementor/frontend/container/before_render', [ $this, 'render_attributes' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    public function enqueue_scripts() {
        wp_enqueue_script( 'crt-manage-lib-gsap');
        wp_enqueue_script( 'crt-manage-lib-scroll-trigger');
        wp_enqueue_script( 'crt-manage-gsap-app', CRT_MANAGE_URI . 'assets/js/gsap-frontend.js', ['jquery', 'crt-manage-lib-scroll-trigger'], '1.0.0', true );
    }

    public function render_attributes( $element ) {
        $settings = $element->get_settings_for_display();

        if ( ! empty( $settings['gfea_animation_enable'] ) && 'yes' === $settings['gfea_animation_enable'] ) {
            $animation_settings = [
                'type' => $settings['gfea_animation_type'] ?? 'fadeInUp',
                'duration' => $settings['gfea_animation_duration']['size'] ?? 1,
                'delay' => $settings['gfea_animation_delay']['size'] ?? 0,
                'ease' => $settings['gfea_animation_ease'] ?? 'power2.out',
                'scrollTrigger' => [
                    'enable' => $settings['gfea_animation_scrolltrigger'] ?? '',
                    'start' => $settings['gfea_animation_start'] ?? 'top 80%',
                    'end' => $settings['gfea_animation_end'] ?? 'bottom 20%',
                    'scrub' => $settings['gfea_animation_scrub'] ?? '',
                    'markers' => $settings['gfea_animation_markers'] ?? '',
                ],
            ];

            $element->add_render_attribute( '_wrapper', 'data-gfea-settings', json_encode( $animation_settings ) );
            $element->add_render_attribute( '_wrapper', 'class', 'gfea-animate' );
        }
    }

    public function register_controls( $element ) {

        $element->start_controls_section (
                'gfea_section_animations',
                [
                    'tab'   => Controls_Manager::TAB_ADVANCED,
                    'label' =>  esc_html__('GSAP Animations', 'crt-manage'),
                ]
            );

            $element->add_control(
                'gfea_animation_enable',
                [
                    'label' => esc_html__( 'Enable Animation', 'crt-manage' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'frontend_available' => true,
                    'selectors' => [
                        '{{WRAPPER}}' => 'transition: none;',
                    ],
                ]
            );

            $element->add_control(
                'gfea_animation_type',
                [
                    'label' => esc_html__( 'Animation Type', 'crt-manage' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'fadeIn' => 'Fade In',
                        'fadeInUp' => 'Fade In Up',
                        'fadeInDown' => 'Fade In Down',
                        'fadeInLeft' => 'Fade In Left',
                        'fadeInRight' => 'Fade In Right',
                        'zoomIn' => 'Zoom In',
                        'zoomOut' => 'Zoom Out',
                        'rotateIn' => 'Rotate In',
                        'fromLeft' => 'From Left',
                        'fromRight' => 'From Right',
                        'text-chars-fadeInUp' => 'Text: Chars Fade In Up',
                        'text-chars-typewriter' => 'Text: Chars Typewriter',
                        'text-words-fadeIn' => 'Text: Words Fade In',
                    ],
                    'default' => 'fadeInUp',
                    'condition' => [
                        'gfea_animation_enable' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'gfea_animation_duration',
                [
                    'label' => esc_html__( 'Duration', 'crt-manage' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0.1,
                            'max' => 5,
                            'step' => 0.1,
                        ],
                    ],
                    'default' => [
                        'size' => 1,
                    ],
                    'condition' => [
                        'gfea_animation_enable' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'gfea_animation_delay',
                [
                    'label' => esc_html__( 'Delay', 'crt-manage' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 5,
                            'step' => 0.1,
                        ],
                    ],
                    'default' => [
                        'size' => 0,
                    ],
                    'condition' => [
                        'gfea_animation_enable' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'gfea_animation_ease',
                [
                    'label' => esc_html__( 'Ease', 'crt-manage' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'power1.out' => 'Power1',
                        'power2.out' => 'Power2',
                        'power3.out' => 'Power3',
                        'power4.out' => 'Power4',
                        'back.out' => 'Back',
                        'elastic.out' => 'Elastic',
                        'bounce.out' => 'Bounce',
                        'none' => 'Linear',
                    ],
                    'default' => 'power2.out',
                    'condition' => [
                        'gfea_animation_enable' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'gfea_animation_scrolltrigger',
                [
                    'label' => esc_html__( 'Use ScrollTrigger', 'crt-manage' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'condition' => [
                        'gfea_animation_enable' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'gfea_animation_header_st',
                [
                    'label' => esc_html__( 'ScrollTrigger Settings', 'crt-manage' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'gfea_animation_enable' => 'yes',
                        'gfea_animation_scrolltrigger' => 'yes',
                    ],
                ]
            );

            $element->add_control(
                'gfea_animation_start',
                [
                    'label' => esc_html__( 'Start', 'crt-manage' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => 'top 80%',
                    'description' => 'e.g., "top center", "top 80%"',
                    'condition' => [
                        'gfea_animation_enable' => 'yes',
                        'gfea_animation_scrolltrigger' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

             $element->add_control(
                'gfea_animation_end',
                [
                    'label' => esc_html__( 'End', 'crt-manage' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => 'bottom 20%',
                    'description' => 'e.g., "bottom center"',
                    'condition' => [
                        'gfea_animation_enable' => 'yes',
                        'gfea_animation_scrolltrigger' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'gfea_animation_scrub',
                [
                    'label' => esc_html__( 'Scrub', 'crt-manage' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'condition' => [
                        'gfea_animation_enable' => 'yes',
                        'gfea_animation_scrolltrigger' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'gfea_animation_markers',
                [
                    'label' => esc_html__( 'Markers (Debug)', 'crt-manage' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'condition' => [
                        'gfea_animation_enable' => 'yes',
                        'gfea_animation_scrolltrigger' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $element->end_controls_section();

    }

}

new CRT_GS_Animation();