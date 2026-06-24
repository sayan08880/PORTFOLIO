<?php

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class CRT_Popup extends Elementor\Core\Base\Document {

    public function get_name() {
        return 'crt_manage_popup';
    }

    public static function get_type() {
        return 'crt_manage_popup';
    }

    public static function get_title() {
        return esc_html__( 'Popup', 'crt-manage' );
    }

    public function get_css_wrapper_selector() {
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            return '.crt-template-popup';
        } else {
            return '#crt-popup-id-'. $this->get_main_id();
        }
    }

    public function add_control_popup_trigger() {
        $this->add_control(
            'popup_trigger',
            [
                'label'   => esc_html__( 'Open Popup', 'crt-manage' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'load',
                'options' => [
                    'load' => esc_html__( 'On Page Load', 'crt-manage' ),
                    'scroll' => esc_html__( 'On Page Scroll', 'crt-manage' ),
                    'element-scroll' => esc_html__( 'On Scroll to Element', 'crt-manage' ),
                    'date' => esc_html__( 'After Specific Date', 'crt-manage' ),
                    'inactivity'  => esc_html__( 'After User Inactivity', 'crt-manage' ),
                    'exit' => esc_html__( 'After User Exit Intent', 'crt-manage' ),
                    'custom' => esc_html__( 'Custom Trigger (Button Click)', 'crt-manage' ),
                ],
            ]
        );
    }

    public function add_control_popup_show_again_delay() {
        $this->add_control(
            'popup_show_again_delay',
            [
                'label'   => esc_html__( 'Show Again Delay', 'crt-manage' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '0',
                'options' => [
                    '0' => esc_html__( 'No Delay', 'crt-manage' ),
                    '60000' => esc_html__( '1 Minute', 'crt-manage' ),
                    '180000' => esc_html__( '3 Minute', 'crt-manage' ),
                    '300000' => esc_html__( '5 Minute', 'crt-manage' ),
                    '600000' => esc_html__( '10 Minute', 'crt-manage' ),
                    '1800000' => esc_html__( '30 Minute', 'crt-manage' ),
                    '3600000' => esc_html__( '1 Hour', 'crt-manage' ),
                    '10800000' => esc_html__( '3 Hour', 'crt-manage' ),
                    '21600000' => esc_html__( '6 Hour', 'crt-manage' ),
                    '43200000' => esc_html__( '12 Hour', 'crt-manage' ),
                    '86400000' => esc_html__( '1 Day', 'crt-manage' ),
                    '259200000' => esc_html__( '3 Days', 'crt-manage' ),
                    '432000000' => esc_html__( '5 Days', 'crt-manage' ),
                    '604800000' => esc_html__( '7 Days', 'crt-manage' ),
                    '864000000' => esc_html__( '10 Days', 'crt-manage' ),
                    '1296000000' => esc_html__( '15 Days', 'crt-manage' ),
                    '1728000000' => esc_html__( '20 Days', 'crt-manage' ),
                    '2628000000' => esc_html__( '1 Month', 'crt-manage' ),
                ],
                'description' => esc_html__( 'This option determines when to show popup again to a visitor after it is closed.', 'crt-manage' ),
                'separator' => 'before'
            ]
        );
    }

    public function add_controls_group_popup_settings() {

        $this->add_control(
            'popup_stop_after_date',
            [
                'label' => esc_html__( 'Stop Showing After Date', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'popup_stop_after_date_select',
            [
                'label' => esc_html__( 'Select Date', 'crt-manage' ),
                'label_block' => false,
                'type' => Controls_Manager::DATE_TIME,
                'default' => date( 'Y-m-d H:i', strtotime( '+1 day' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
                'description' => sprintf( __( 'Set according to your WordPress timezone: %s.', 'crt-manage' ), Elementor\Utils::get_timezone_string() ),
                'condition' => [
                    'popup_stop_after_date!' => '',
                ],
            ]
        );

        $this->add_control(
            'popup_automatic_close_switch',
            [
                'label' => esc_html__( 'Automatic Closing Delay', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'popup_automatic_close_delay',
            [
                'label' => esc_html__( 'Set Closing Delay (sec)', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'condition' => [
                    'popup_automatic_close_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'popup_disable_esc_key',
            [
                'label' => esc_html__( 'Prevent Closing on "ESC" Key', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'popup_show_for_roles',
            [
                'label' => esc_html__( 'Show For Roles', 'crt-manage' ),
                'type' => Controls_Manager::SELECT2,
                'options' => Utilities::get_user_roles(),
                'multiple' => 'true',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'popup_show_via_referral',
            [
                'label' => esc_html__( 'Show according to URL Keyword', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'popup_referral_keyword',
            [
                'label' => esc_html__( 'Enter Keyword', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'description' => 'Popup will show up if the URL contains this Keyword.',
                'condition' => [
                    'popup_show_via_referral' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'popup_show_on_device',
            [
                'label' => esc_html__( 'Show on this Device', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'widescreen_default' => 'yes',
                'laptop_default' => 'yes',
                'tablet_extra_default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_extra_default' => 'yes',
                'mobile_default' => 'yes',
                'separator' => 'before'
            ]
        );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'popup_settings',
            [
                'label' => esc_html__( 'Settings', 'crt-manage' ),
                'tab'   => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control_popup_trigger();

        $this->add_control(
            'popup_load_delay',
            [
                'label' => esc_html__( 'Delay after Page Load (sec)', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'condition' => [
                    'popup_trigger' => 'load',
                ]
            ]
        );

        $this->add_control(
            'popup_scroll_progress',
            [
                'label' => esc_html__( 'Scroll Progress (in %)', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 1,
                'max' => 100,
                'condition' => [
                    'popup_trigger' => 'scroll',
                ]
            ]
        );

        $this->add_control(
            'popup_element_scroll',
            [
                'label' => esc_html__( 'Element Selector', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'condition' => [
                    'popup_trigger' => 'element-scroll',
                ]
            ]
        );

        $this->add_control(
            'popup_specific_date',
            [
                'label' => esc_html__( 'Select Date', 'crt-manage' ),
                'label_block' => false,
                'type' => Controls_Manager::DATE_TIME,
                'default' => date( 'Y-m-d H:i', strtotime( '+1 day' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
                'description' => sprintf( __( 'Set according to your WordPress timezone: %s.', 'crt-manage' ), Elementor\Utils::get_timezone_string() ),
                'condition' => [
                    'popup_trigger' => 'date',
                ],
            ]
        );

        $this->add_control(
            'popup_custom_trigger',
            [
                'label' => esc_html__( 'Element Selector', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'render_type' => 'template',
                'condition' => [
                    'popup_trigger' => 'custom',
                ]
            ]
        );

        $this->add_control(
            'popup_inactivity_time',
            [
                'label' => esc_html__( 'Inactivity Time (sec)', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 15,
                'min' => 1,
                'condition' => [
                    'popup_trigger' => 'inactivity',
                ]
            ]
        );

        $this->add_control_popup_show_again_delay();

        $this->add_controls_group_popup_settings();

//        if ( !defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code() ) {
//            $this->add_control(
//                'group_popup_settings_pro_notice',
//                [
//                    'type' => Controls_Manager::RAW_HTML,
//                    'raw' => '<a onclick="showOptionsImage()" class="crt-show-img" style="cursor:pointer;">Click Here</a> to see what options <br> are available in the <strong><a href="https://crthemes.com/?ref=rea-plugin-panel-popups-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>
//					<img src="'. WPR_ADDONS_ASSETS_URL .'img/pro-options/group_popup_settings.jpg" style="display:none;position: absolute;top: 80px;left: 0;z-index: 99;border: 1px solid #93003C;">
//					<script>function showOptionsImage(){jQuery(document).on("click",function(){jQuery(".elementor-control .crt-pro-notice img").hide()}),setTimeout(function(){jQuery(".elementor-control .crt-pro-notice img").show()},100)}</script>',
//                    'content_classes' => 'crt-pro-notice',
//                ]
//            );
//        }

        $this->end_controls_section();

        $this->start_controls_section(
            'popup_layout',
            [
                'label' => esc_html__( 'Layout', 'crt-manage' ),
                'tab'   => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'popup_display_as',
            [
                'label'   => esc_html__( 'Display As', 'crt-manage' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'modal',
                'options' => [
                    'modal' => esc_html__( 'Modal Popup', 'crt-manage' ),
                    'notification' => esc_html__( 'Top Bar Banner', 'crt-manage' ),
                ],
            ]
        );

        $this->add_control(
            'popup_display_as_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_responsive_control(
            'popup_width',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
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
                    'size' => 650,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'popup_display_as!' => 'notification',
                ]
            ]
        );

        $this->add_control(
            'popup_height',
            [
                'label'   => esc_html__( 'Height', 'crt-manage' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto'=> esc_html__( 'Auto', 'crt-manage' ),
                    'custom' => esc_html__( 'Custom', 'crt-manage' ),
                ],
                'selectors_dictionary' => [
                    'auto' => 'height: auto; z-index: 13;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-container-inner' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'popup_custom_height',
            [
                'label' => esc_html__( 'Custom Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','vh'],
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
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-container-inner' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'popup_height' => 'custom'
                ]
            ]
        );

        $this->add_responsive_control(
            'popup_align_hr',
            [
                'label' => esc_html__( 'Horizontal Align', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'crt-manage' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'crt-manage' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'crt-manage' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-template-popup-inner' => 'justify-content: {{VALUE}}',
                ],
                'separator' => 'before',
                'condition' => [
                    'popup_display_as!' => 'notification',
                ]
            ]
        );

        $this->add_responsive_control(
            'popup_align_vr',
            [
                'label' => esc_html__( 'Vertical Align', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'crt-manage' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Middle', 'crt-manage' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Bottom', 'crt-manage' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-template-popup-inner' => 'align-items: {{VALUE}}',
                ],
                'condition' => [
                    'popup_display_as!' => 'notification',
                ]
            ]
        );

        $this->add_responsive_control(
            'popup_content_align',
            [
                'label' => esc_html__( 'Content Align', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'crt-manage' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Middle', 'crt-manage' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Bottom', 'crt-manage' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-container-inner' => 'align-items: {{VALUE}}',
                ],
                'condition' => [
                    'popup_display_as!' => 'notification',
                ]
            ]
        );

        $this->add_control(
            'popup_animation',
            [
                'label' => esc_html__( 'Entance Animation', 'crt-manage' ),
                'type' => Controls_Manager::ANIMATION,
                'default' => 'fadeIn',
                'label_block' => true,
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'popup_animation_duration',
            [
                'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-container' => 'animation-duration: {{SIZE}}s;',
                ],
                'condition' => [
                    'popup_animation!' => ['', 'none'],
                ]
            ]
        );

        $this->add_control(
            'popup_zindex',
            [
                'label' => esc_html__( 'Z Index', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 9999,
                'min' => 1,
                'selectors' => [
                    '{{WRAPPER}}' => 'z-index: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'popup_disable_page_scroll',
            [
                'label' => esc_html__( 'Disable Page Scroll', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => true,
                'return_value' => true,
                'separator' => 'before',
                'condition' => [
                    'popup_display_as!' => 'notification',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'popup_overlay',
            [
                'label' => esc_html__( 'Overlay', 'crt-manage' ),
                'tab'   => Controls_Manager::TAB_SETTINGS,
                'condition' => [
                    'popup_display_as!' => 'notification',
                ]
            ]
        );

        $this->add_control(
            'popup_overlay_display',
            [
                'label' => esc_html__( 'Show Overlay', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'display: none !important;',
                    'yes' => 'display: block;'
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-overlay' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'popup_overlay_disable_close',
            [
                'label' => esc_html__( 'Prevent Closing on Overlay Click', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'popup_overlay_display' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'popup_close_button',
            [
                'label' => esc_html__( 'Close Button', 'crt-manage' ),
                'tab'   => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'popup_close_button_display',
            [
                'label' => esc_html__( 'Show Close Button', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'display: none;',
                    'yes' => 'display: block;'
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-close-btn' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'popup_close_button_display_delay',
            [
                'label' => esc_html__( 'Show Up Delay (sec)', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'condition' => [
                    'popup_close_button_display' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'popup_close_button_position_vr',
            [
                'label' => esc_html__( 'Vertical Position', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-close-btn' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'popup_close_button_display' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'popup_close_button_position_hr',
            [
                'label' => esc_html__( 'Horizontal Position', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-close-btn' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'popup_close_button_display' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        // Section: Pro Features
//        if ( !defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code() ) {
//            $this->start_controls_section(
//                'pro_features_section',
//                [
//                    'label' => 'Pro Features <span class="dashicons dashicons-star-filled"></span>',
//                    'tab'   => Controls_Manager::TAB_SETTINGS,
//                ]
//            );
//
//            $this->add_control(
//                'pro_features_list',
//                [
//                    'type' => Controls_Manager::RAW_HTML,
//                    'raw' => '<ul>
//						<li>Open Popup: On Page Scroll</li>
//						<li>Open Popup: On Scroll to Element</li>
//						<li>Open Popup: After Specific Date</li>
//						<li>Open Popup: After User Inactivity</li>
//						<li>Open Popup: After User Exit Intent</li>
//						<li>Open Popup: Custom Trigger (Button Click or Selector)</li>
//						<li>Show Again Delay: Set any time (hours, days, weeks) - This option determines when to show popup again to a visitor after it is closed.</li>
//						<li>Stop showing after Specific Date</li>
//						<li>Automatic Closing Delay</li>
//						<li>Show Popup for Specific Roles</li>
//						<li>Show according to URL Keyword - Popup will show up if URL(referral) contains chosen keyword</li>
//						<li>Show/Hide Popup on any Device</li>
//						<li>Prevent Popup closing on"ESC" key</li>
//					</ul>
//							  <a href="https://crthemes.com/?ref=rea-plugin-panel-pro-sec-crt-popups-upgrade-pro#purchasepro" target="_blank">Get Pro version</a>',
//                    'content_classes' => 'crt-pro-features-list',
//                ]
//            );
//
//            $this->end_controls_section();
//        }

        // Default Document Settings
        parent::register_controls();

        $this->start_controls_section(
            'popup_container_styles',
            [
                'label' => esc_html__( 'Popup', 'crt-manage' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'popup_container_bg',
                'label' => esc_html__( 'Background', 'crt-manage' ),
                'types' => [ 'classic', 'gradient' ],
                'fields_options' => [
                    'color' => [
                        'default' => '#ffffff',
                    ],
                ],
                'selector' => '{{WRAPPER}} .crt-popup-container-inner'
            ]
        );

        $this->add_control(
            'popup_scrollbar_color',
            [
                'label'  => esc_html__( 'ScrollBar Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ps-container > .ps-scrollbar-y-rail > .ps-scrollbar-y' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .ps > .ps__rail-y > .ps__thumb-y' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'popup_container_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-container-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        // $this->add_responsive_control(
        // 	'popup_container_margin',
        // 	[
        // 		'label' => esc_html__( 'Margin', 'crt-manage' ),
        // 		'type' => Controls_Manager::DIMENSIONS,
        // 		'size_units' => [ 'px', '%' ],
        // 		'default' => [
        // 			'top' => 0,
        // 			'right' => 0,
        // 			'bottom' => 0,
        // 			'left' => 0,
        // 		],
        // 		'selectors' => [
        // 			'{{WRAPPER}} .crt-template-popup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        // 		],
        // 		'separator' => 'before'
        // 	]
        // );

        $this->add_control(
            'popup_container_radius',
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
                    '{{WRAPPER}} .crt-popup-container-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'popup_container_border',
                'label' => esc_html__( 'Border', 'crt-manage' ),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .crt-popup-container-inner',
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'popup_container_shadow',
                'selector' => '{{WRAPPER}} .crt-popup-container-inner'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'popup_overlay_styles',
            [
                'label' => esc_html__( 'Overlay', 'crt-manage' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'popup_overlay_display' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'popup_overlay_bg',
                'label' => esc_html__( 'Background', 'crt-manage' ),
                'types' => [ 'classic', 'gradient' ],
                'fields_options' => [
                    'color' => [
                        'default' => '#777777',
                    ],
                ],
                'selector' => '{{WRAPPER}} .crt-popup-overlay'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'popup_close_btn_styles',
            [
                'label' => esc_html__( 'Close Button', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs( 'tabs_popup_close_btn_style' );

        $this->start_controls_tab(
            'tab_popup_close_btn_normal',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'popup_close_btn_color',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-close-btn' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'popup_close_btn_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-close-btn' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'popup_close_btn_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-close-btn' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'popup_close_btn_box_shadow',
                'selector' => '{{WRAPPER}} .crt-popup-close-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_popup_close_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'popup_close_btn_color_hr',
            [
                'label'  => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#54595f',
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-close-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'popup_close_btn_bg_color_hr',
            [
                'label'  => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-close-btn:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'popup_close_btn_border_color_hr',
            [
                'label'  => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-close-btn:hover' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'popup_close_btn_size',
            [
                'label' => esc_html__( 'Size', 'crt-manage' ),
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
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-close-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-popup-close-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'popup_close_btn_box_size',
            [
                'label' => esc_html__( 'Box Size', 'crt-manage' ),
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
                    'size' => 35,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-popup-close-btn' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-popup-close-btn i' => 'line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-popup-close-btn svg' => 'line-height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'popup_close_btn_border_type',
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
                    '{{WRAPPER}} .crt-popup-close-btn' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'popup_close_btn_border_width',
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
                    '{{WRAPPER}} .crt-popup-close-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'popup_close_btn_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'popup_close_btn_radius',
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
                    '{{WRAPPER}} .crt-popup-close-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

    }

}