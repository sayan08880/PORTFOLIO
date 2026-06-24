<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Base\Document;
use Elementor\Plugin;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Lottie_Animations extends Widget_Base {
		
	public function get_name() {
		return 'crt-lottie-animations';
	}

	public function get_title() {
		return esc_html__( 'Lottie Animations', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-lottie';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'lottie', 'animation', 'animations', 'svg' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}
	
	public function get_script_depends() {
		return [ 'crt-lottie', 'crt-lottie-animations' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://crthemes.com/contact/?ref=rea-plugin-panel-lottie-animations-help-btn';
    		return 'https://crthemes.com/contact';
    }
	
	protected function register_controls() {

		// Section: Settings ---------
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'crt-manage' ),
			]
		);

		Utilities::crt_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'source',
			[
				'label'   => __( 'File Source', 'crt-manage' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'url'  => __( 'External URL', 'crt-manage' ),
					'file' => __( 'Media File', 'crt-manage' ),
				],
				'default' => 'url',
			]
		);

		$this->add_control(
			'json_url',
			[
				'label'       => __( 'Animation JSON URL', 'crt-manage' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default'	  => 'https://assets3.lottiefiles.com/packages/lf20_ghs9bkkc.json',
				'description' => 'Get JSON code URL from <a href="https://lottiefiles.com/free-animations" target="_blank">here</a>',
				'label_block' => true,
				'condition'   => [
					'source' => 'url',
				],
			]
		);

		$this->add_control(
			'json_file',
			array(
				'label'              => __( 'Upload JSON File', 'crt-manage' ),
				'type'               => Controls_Manager::MEDIA,
				'media_type'         => 'application/json',
				'frontend_available' => true,
				'condition'          => [
					'source' => 'file',
				]
			)
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Loop', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'reverse',
			[
				'label' => __( 'Reverse', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'condition' => [
					'trigger!' => 'scroll'
				]
			]
		);

		$this->add_control(
			'speed',
			array(
				'label'   => __( 'Animation Speed', 'crt-manage' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 0.1,
				'max'     => 3,
				'step'    => 0.1,
			)
		);

		$this->add_control(
			'trigger',
			[
				'label' => __( 'Trigger', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options'            => array(
					'none'     => __( 'None', 'crt-manage' ),
					'viewport' => __( 'Viewport', 'crt-manage' ),
					'hover'    => __( 'Hover', 'crt-manage' ),
					'scroll'   => __( 'Scroll', 'crt-manage' ),
				),
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'animate_view',
			array(
				'label'     => __( 'Viewport', 'crt-manage' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'sizes' => array(
						'start' => 0,
						'end'   => 100,
					),
					'unit'  => '%',
				),
				'labels'    => array(
					__( 'Bottom', 'crt-manage' ),
					__( 'Top', 'crt-manage' ),
				),
				'scales'    => 1,
				'handles'   => 'range',
				'condition' => array(
					'trigger'         => array( 'scroll', 'viewport' ),
					// 'lottie_reverse!' => 'true',
				),
			)
		);
		
		$this->add_responsive_control(
			'animation_size',
			array(
				'label'       => __( 'Size', 'crt-manage' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', '%' ),
				'default'     => array(
					'unit' => '%',
					'size' => 50,
				),
				'range'       => array(
					'px' => array(
						'min' => 1,
						'max' => 800,
					),
					'em' => array(
						'min' => 1,
						'max' => 30,
					),
				),
				'render_type' => 'template',
				'separator'   => 'before',
				'selectors'   => array(
					'{{WRAPPER}} .crt-lottie-animations svg' => 'width: 100% !important; height: 100% !important;',
					'{{WRAPPER}} .crt-lottie-animations' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);
		
		$this->add_responsive_control(
			'rotate',
			array(
				'label'       => __( 'Rotate (degrees)', 'crt-manage' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => __( 'Set rotation value in degrees', 'crt-manage' ),
				'range'       => array(
					'px' => array(
						'min' => -180,
						'max' => 180,
					),
				),
				'default'     => array(
					'size' => 0,
				),
				'selectors'   => array(
					'{{WRAPPER}} .crt-lottie-animations' => 'transform: rotate({{SIZE}}deg)',
				),
			)
		);
		
		$this->add_responsive_control(
			'animation_align',
			array(
				'label'     => __( 'Alignment', 'crt-manage' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'crt-manage' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'crt-manage' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'crt-manage' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .crt-lottie-animations-wrapper' => 'display: flex; justify-content: {{VALUE}}; align-items: {{VALUE}};',
				),
			)
		);
		
		$this->add_control(
			'lottie_renderer',
			[
				'label'        => __( 'Render As', 'crt-manage' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'svg'    => __( 'SVG', 'crt-manage' ),
					'canvas' => __( 'Canvas', 'crt-manage' ),
				),
				'default'      => 'svg',
				'prefix_class' => 'crt-lottie-',
				'render_type'  => 'template',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'render_notice',
			[
				'raw'             => __( 'Set render type to canvas if you\'re having performance issues on the page.', 'premium-addons-for-elemeentor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		
		$this->add_control(
			'link_switcher',
			[
				'label' => __( 'Wrapper Link', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'link_selection',
			[
				'label'       => __( 'Link Type', 'crt-manage' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'url'  => __( 'URL', 'crt-manage' ),
					'link' => __( 'Existing Page', 'crt-manage' ),
				),
				'default'     => 'url',
				'label_block' => true,
				'condition'   => array(
					'link_switcher' => 'yes',
				),
			]
		);
		
		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'crt-manage' ),
				'type'        => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default'     => array(
					'url' => '#',
				),
				'placeholder' => 'https://crthemes.com/',
				'label_block' => true,
				'condition'   => array(
					'link_switcher'  => 'yes',
					'link_selection' => 'url',
				),
			)
		);

		$this->add_control(
			'existing_link',
			array(
				'label' => __( 'Existing Page', 'crt-manage' ),
				'type' => 'crt-ajax-select2',
				'options' => 'ajaxselect2/get_posts_by_post_type',
				'query_slug' => 'page',
				'multiple' => false,
				'label_block' => true,
				'condition' => array(
					'link_switcher'  => 'yes',
					'link_selection' => 'link',
				),
			)
		);


		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		$this->start_controls_section(
			'lottie_styles',
			[
				'label' => __( 'Animation', 'crt-manage' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_lottie' );

		$this->start_controls_tab(
			'tab_lottie_normal',
			[
				'label' => __( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label'     => __( 'Opacity', 'crt-manage' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .crt-lottie-animations' => 'opacity: {{SIZE}}',
				),
			]
		);

		$this->add_control(
			'hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-lottie-animations' => 'transition-duration: {{VALUE}}s;'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .crt-lottie-animations',
			)
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_lottie_hover',
			[
				'label' => __( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'hover_opacity',
			array(
				'label'     => __( 'Opacity', 'crt-manage' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .crt-lottie-animations:hover' => 'opacity: {{SIZE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'hover_css_filters',
				'selector' => '{{WRAPPER}} .crt-lottie-animations:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section(); // End Controls Section
	
	}

	public function lottie_attributes($settings) {
		$attributes = [
			'loop' => $settings['loop'],
			'autoplay' => $settings['autoplay'],
			/// TODO: reverse
			'speed' => $settings['speed'],
			'trigger' => $settings['trigger'],
			'reverse' => $settings['reverse'],
			'scroll_start'  => isset( $settings['animate_view']['sizes']['start'] ) ? $settings['animate_view']['sizes']['start'] : '0',
			'scroll_end'    => isset( $settings['animate_view']['sizes']['end'] ) ? $settings['animate_view']['sizes']['end'] : '100',
			'lottie_renderer' => $settings['lottie_renderer']
		];

		return json_encode($attributes);
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings_for_display();
		$lottie_json = 'url' === $settings['source'] ? esc_url($settings['json_url']) : $settings['json_file']['url'];
		$lottie_link = 'url' === $settings['link_selection'] ? $settings['link']['url'] : get_permalink($settings['existing_link']);

		if ( '' === $lottie_json ) {
			$lottie_json = CRT_MANAGE_DIR . '/includes/customizer/addons/default.json';
		}

		$lottie_animation = 'yes' === $settings['link_switcher']
				? '<a href="'. esc_url($lottie_link) .'"><div class="crt-lottie-animations" data-settings="'. esc_attr($this->lottie_attributes($settings)) .'" data-json-url="'. esc_url($lottie_json) .'"></div></a>'
				: '<div class="crt-lottie-animations" data-settings="'. esc_attr($this->lottie_attributes($settings)) .'" data-json-url="'. esc_url($lottie_json) .'"></div>';

		echo '<div class="crt-lottie-animations-wrapper">';
			echo ''. $lottie_animation; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
	}
}
