<?php

// Elementor classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Icons;
use Elementor\Utils;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Reading_Progress_Bar extends Widget_Base {
    public function get_name() {
        return 'crt-reading-progress-bar';
    }

    public function get_title() {
        return esc_html__( 'Reading Progress Bar', 'crt-manage' );
    }

    public function get_icon() {
        return 'crt-icon eicon-skill-bar';
    }

    public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

    public function get_script_depends() {
        return [ 'crt-reading-progress-bar' ];
    }

    public function get_keywords() {
        return [ 'reading progress bar', 'skills bar', 'percentage bar', 'scroll' ];
    }

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
        if ( empty(get_option('crt_wl_plugin_links')) )
            return 'https://crthemes.com/contact';
    }

	public function is_reload_preview_required() {
		return true;
	}
    
    public function register_controls() {
        
		$this->start_controls_section(
			'reading_progress_bar',
			[
                'tab' => Controls_Manager::TAB_CONTENT,
				'label' => __( 'Reading Progress Bar', 'crt-manage' ),
			]
        );

		$this->add_control(
			'rpb_info',
			[
				'raw' => esc_html__( 'Please scroll down a page to see how Progress Bar in works action.', 'crt-manage' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'rpb_height',
			[
				'label' => __( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
                'render_type' => 'template',
				'range' => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'.crt-reading-progress-bar-container' => 'height: {{SIZE}}{{UNIT}} !important',
					'.crt-reading-progress-bar-container .crt-reading-progress-bar' => 'height: {{SIZE}}{{UNIT}} !important',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'progress_bar_position',
			[
				'label' => __( 'Position', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'top',
				'render_type' => 'template',
				'separator' => 'before',
				'options' => [
					'top' => __( 'Top', 'crt-manage' ),
					'bottom' => __( 'Bottom', 'crt-manage' ),
				],
				'selectors_dictionary' => [
					'top' => 'top: 0px; bottom: auto;',
					'bottom' => 'bottom: 0px; top: auto;',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-reading-progress-bar-container' => '{{VALUE}}',
				]
			]
		);

		$this->add_control(
			'rpb_background_type',
			[
				'label' => __( 'Background Type', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'transparent',
				'render_type' => 'template',
				'options' => [
					'transparent' => __( 'Transparent', 'crt-manage' ),
					'colored' => __( 'Colored', 'crt-manage' ),
				]
			]
		);

		$this->add_control(
			'rpb_background_color',
			[
				'label' => __( 'Background Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#C5C5C6',
				'selectors' => [
					'.crt-reading-progress-bar-container' => 'background-color: {{VALUE}};'
				],
				'condition' => [
					'background_type' => 'colored'
				]
			]
		);

		$this->add_control(
			'rpb_fill_color',
			[
				'label' => __( 'Fill Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#6A63DA',
				'selectors' => [
					'.crt-reading-progress-bar-container .crt-reading-progress-bar' => 'background-color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'rpb_transition_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .crt-reading-progress-bar' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				]
			]
		);

        $this->end_controls_section();

	}

	protected function render() {
    	$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'crt-rpb-attrs', [
			'class' => 'crt-reading-progress-bar-container',
			'data-background-type' => $settings['rpb_background_type'],
		] );

        echo '<div '. $this->get_render_attribute_string('crt-rpb-attrs') .'><div class="crt-reading-progress-bar"></div></div>';
	}
}