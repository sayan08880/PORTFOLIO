<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Google_Maps extends Widget_Base {
	
	public function get_name() {
		return 'crt-google-maps';
	}

	public function get_title() {
		return esc_html__( 'Google Maps', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-google-maps';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'google maps', 'location', 'gmap', 'cluster' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_script_depends() {
		return [ 'crt-google-maps', 'crt-google-maps-clusters' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-google-maps-help-btn';
    		return 'https://crthemes.com/contact';
    }

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_google_map_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		if ( '' == get_option('crt_google_map_api_key') ) {
			$this->add_control(
				'gm_api_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( 'Please enter <strong>Google Map API Key</strong> from <br><a href="%s" target="_blank">Dashboard > %s > Settings</a> tab to get this widget working.', 'crt-manage' ), admin_url( 'admin.php?page=crt-manage' ), Utilities::get_plugin_name() ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control(
			'gm_integration',
			[
				'label' => esc_html__( 'Map Integration', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'api_key' => esc_html__( 'API Key', 'crt-manage' ),
					'without_api_key' => esc_html__( 'Without API Key', 'crt-manage' )
				],
				'default' => 'api_key',
				'render_type' => 'template',
			]
		);
		
		$this->add_control(
			'gm_latitude',
			[
				'label' => esc_html__( 'Latitude', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => '40.782864',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'gm_integration' => 'without_api_key',
				]
			]
		);

		$this->add_control(
			'gm_longtitude',
			[
				'label' => esc_html__( 'Longtitude', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => '-73.965355',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'gm_integration' => 'without_api_key',
				]
			]
		);

		$this->add_control(
			'gm_location_title',
			[
				'label' => esc_html__( 'Location Title', 'crt-manage' ),
				'description' => esc_html__( 'Enter a location name or address to display the map. For example: "Central Park, New York, USA". (Works if long/lat Fields Empty)', 'crt-manage' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => 'Central Park, New York, USA',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'gm_integration' => 'without_api_key',
				]
			]
		);

		$this->add_control(
			'gm_type',
			[
				'label' => esc_html__( 'Select Map Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'roadmap' => esc_html__( 'Road Map', 'crt-manage' ),
					'satellite' => esc_html__( 'Satellite', 'crt-manage' ),
					'hybrid' => esc_html__( 'Hybrid', 'crt-manage' ),
					'terrain' => esc_html__( 'Terrain', 'crt-manage' ),
				],
				'default' => 'roadmap',
				'condition' => [
					'gm_integration' => 'api_key',
				],
			]
		);

		$this->add_control(
			'gm_color_scheme',
			[
				'label' => esc_html__( 'Color Scheme', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'crt-manage' ),
					'simple' => esc_html__( 'Simple', 'crt-manage' ),
					'white-black' => esc_html__( 'White Black', 'crt-manage' ),
					'light-silver' => esc_html__( 'Light Silver', 'crt-manage' ),
					'light-grayscale' => esc_html__( 'Light Grayscale', 'crt-manage' ),
					'subtle-grayscale' => esc_html__( 'Subtle Grayscale', 'crt-manage' ),
					'mostly-white' => esc_html__( 'Mostly White', 'crt-manage' ),
					'mostly-green' => esc_html__( 'Mostly Green', 'crt-manage' ),
					'neutral-blue' => esc_html__( 'Neutral Blue', 'crt-manage' ),
					'blue-water' => esc_html__( 'Blue Water', 'crt-manage' ),
					'blue-essense' => esc_html__( 'Blue Essense', 'crt-manage' ),
					'golden-brown' => esc_html__( 'Golden Brown', 'crt-manage' ),
					'midnight-commander' => esc_html__( 'Midnight Commander', 'crt-manage' ),
					'shades-of-grey' => esc_html__( 'Shades of Grey', 'crt-manage' ),
					'yellow-black' => esc_html__( 'Yellow Black', 'crt-manage' ),
					'custom' => esc_html__( 'Custom', 'crt-manage' ),
				],
				'default' => 'default',
				'condition' => [
					'gm_integration' => 'api_key',
					'gm_type!' => 'satellite',
				]
			]
		);

		$this->add_control(
			'gm_custom_color_scheme',
			[
				'label' => esc_html__( 'Custom Style', 'crt-manage' ),
				'description' => __( 'Get custom map style code from <a href="https://snazzymaps.com/explore" target="_blank">Snazzy Maps</a> or <a href="https://mapstyle.withgoogle.com/" target="_blank">GM Styling Wizard</a> and copy/paste in this field.', 'crt-manage' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'gm_integration' => 'api_key',
					'gm_color_scheme' => 'custom',
				]
			]
		);

		$this->add_responsive_control(
			'gm_height',
			[
				'label' => esc_html__( 'Map Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 500,
				],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-google-map' => 'height: {{SIZE}}px;',
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'gm_zoom_depth',
			[
				'label' => esc_html__( 'Zoom Depth', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'gm_zoom_on_scroll',
			[
				'label' => esc_html__( 'Disable Zoom on Scroll', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'cooperative',
				'separator' => 'before',
				'condition' => [
					'gm_integration' => 'api_key',
				]
			]
		);

		$this->add_control(
			'gm_cluster_markers',
			[
				'label' => esc_html__( 'Cluster Markers', 'crt-manage' ),
				'description' => esc_html__( 'Combine markers of close proximity into clusters, and simplify the display of markers on the map.', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'gm_integration' => 'api_key',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Locations --------
		$this->start_controls_section(
			'section_google_map_locations',
			[
				'label' => esc_html__( 'Locations', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'gm_integration' => 'api_key',
				]
			]
		);

		$this->add_control(
			'gm_location_helper',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<a href="https://www.latlong.net/" target="_blank">'. esc_html__( 'Click Here', 'crt-manage' ) .'</a> '. esc_html__( 'to find Coordinates of your location.', 'crt-manage' ),
				'separator' => 'after'
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'gm_latitude',
			[
				'label' => esc_html__( 'Latitude', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'gm_longtitude',
			[
				'label' => esc_html__( 'Longtitude', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'gm_show_info_window',
			[
				'label' => esc_html__( 'Show Info Window', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'load' => esc_html__( 'on Load', 'crt-manage' ),
					'click' => esc_html__( 'on Click', 'crt-manage' ),
				],
				'default' => 'load',
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'gm_location_title',
			[
				'label' => esc_html__( 'Location Title', 'crt-manage' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'gm_show_info_window!' => 'none',
				]
			]
		);

		$repeater->add_control(
			'gm_location_description',
			[
				'label' => esc_html__( 'Location Description', 'crt-manage' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'gm_show_info_window!' => 'none',
				]
			]
		);

		$repeater->add_control(
			'gm_info_window_width',
			[
				'label' => esc_html__( 'Info Window Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 300,
				],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'condition' => [
					'gm_show_info_window!' => 'none',
				]
			]
		);

		$repeater->add_control(
			'gm_marker_animation',
			[
				'label' => esc_html__( 'Marker Animation', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'DROP' => esc_html__( 'Drop', 'crt-manage' ),
					'BOUNCE' => esc_html__( 'Bounce', 'crt-manage' ),
				],
				'default' => 'none',
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'gm_custom_marker',
			[
				'label' => esc_html__( 'Use Custom Marker', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'gm_marker_icon',
			[
				'label' => esc_html__( 'Upload Marker Icon', 'crt-manage' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'gm_custom_marker' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'gm_marker_icon_size_width',
			[
				'label' => esc_html__( 'Marker Icon Size Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 150,
					],
				],
				'condition' => [
					'gm_custom_marker' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'gm_marker_icon_size_height',
			[
				'label' => esc_html__( 'Marker Icon Size Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 150,
					],
				],
				'condition' => [
					'gm_custom_marker' => 'yes',
				]
			]
		);

		$this->add_control(
			'google_map_locations',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'gm_location_title' => 'Central Park, New York, USA',
						'gm_latitude' => '40.782864',
						'gm_longtitude' => '-73.965355',
					],
				],
				'title_field' => '{{{ gm_location_title }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Controls ---------
		$this->start_controls_section(
			'section_google_map_controls',
			[
				'label' => esc_html__( 'Controls', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'gm_integration' => 'api_key',
				]
			]
		);

		$this->add_control(
			'gm_controls_map_type',
			[
				'label' => esc_html__( 'Show Map Type Control', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'gm_controls_fullscreen',
			[
				'label' => esc_html__( 'Show FullScreen Control', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'gm_controls_zoom',
			[
				'label' => esc_html__( 'Show Zoom Control', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'gm_controls_street_view',
			[
				'label' => esc_html__( 'Show Street View Control', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Info Window ------
		$this->start_controls_section(
			'section_style_info_window',
			[
				'label' => esc_html__( 'Info Window', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'gm_integration' => 'api_key',
				]
			]
		);

		$this->add_control(
			'infow_window_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'crt-manage' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .crt-google-map .gm-style-iw-c' => 'text-align: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'infow_window_title_color',
			[
				'label'  => esc_html__( 'Title Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-google-map .gm-style-iw-c .crt-gm-iwindow h3' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'infow_window_description_color',
			[
				'label'  => esc_html__( 'Description Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-google-map .gm-style-iw-c .crt-gm-iwindow p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'infow_window_background_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-google-map .gm-style-iw-d' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-google-map .gm-style .gm-style-iw-c' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-google-map .gm-style-iw-t:after' => 'background: {{VALUE}}',
					'{{WRAPPER}} .crt-google-map .gm-style-iw-tc:after' => 'background: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'infow_window_title_typography',
				'label' => esc_html__( 'Title Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-google-map .gm-style-iw-c .crt-gm-iwindow h3'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'infow_window_desc_typography',
				'label' => esc_html__( 'Description Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-google-map .gm-style-iw-c .crt-gm-iwindow p'
			]
		);

		$this->add_responsive_control(
			'infow_window_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-google-map .gm-style-iw-c .crt-gm-iwindow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'infow_window_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-google-map .gm-style-iw-c' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'map_radius',
			[
				'label' => esc_html__( 'Map Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-google-map' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'infow_window_distance',
			[
				'label' => esc_html__( 'Distance from Marker', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-google-map .gm-style-iw-a' => 'transform: translateY(-{{SIZE}}px);',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	public function get_map_settings( $settings ) {
		$map_settings = [
			'type' => $settings['gm_type'],
			'style' => $settings['gm_color_scheme'],
			'zoom_depth' => $settings['gm_zoom_depth']['size'],
			'zoom_on_scroll' => $settings['gm_zoom_on_scroll'],
			'cluster_markers' => $settings['gm_cluster_markers'],
			'clusters_url' => WPR_ADDONS_URL . 'assets/js/lib/gmap/clusters/m',
		];


        if ( !is_array($settings['gm_custom_color_scheme']) ) {
			$map_settings['custom_style'] = preg_replace( '/\s/', '', strip_tags($settings['gm_custom_color_scheme']) );
        }

        return $map_settings;
	}

	public function get_map_controls( $settings ) {
		return [
			'type' => $settings['gm_controls_map_type'],
			'fullscreen' => $settings['gm_controls_fullscreen'],
			'zoom' => $settings['gm_controls_zoom'],
			'streetview' => $settings['gm_controls_street_view'],
		];
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		
		if ( '' == get_option('crt_google_map_api_key') && 'without_api_key' === $settings['gm_integration'] ) {
			$latitude  = $settings['gm_latitude'] ?? '';
			$longitude = $settings['gm_longtitude'] ?? '';
			$gm_location_title = $settings['gm_location_title'] ?? '';
			$zoom = !empty($settings['gm_zoom_depth']['size']) ? intval($settings['gm_zoom_depth']['size']) : 14;
	
			if ($latitude && $longitude) {
				$map_src = "https://www.google.com/maps?q={$latitude},{$longitude}&z={$zoom}&output=embed";
			} else {
				$map_src = "https://www.google.com/maps?q=" . rawurlencode($gm_location_title) . "&z={$zoom}&output=embed";
			}

			echo '<div class="crt-google-map" data-integration-type ="'. esc_attr($settings['gm_integration']) .'">';
			echo '<iframe 
					src="' . esc_url($map_src) . '" 
					width="100%"
					height="'. $settings['gm_height']['size'] .'"
					style="border:0;" 
					allowfullscreen="" 
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade">
				  </iframe>';
			echo '</div>';
		} else {
			// Access and sanitize gm_location_title
			$google_map_locations = $settings['google_map_locations'];
		
			// Sanitize: Remove <img>, <script> tags and any attributes like onerror
			$gm_location_title = $google_map_locations[0]['gm_location_title'];
			$gm_location_title = preg_replace('/<\s*(img|script)[^>]*>/i', '', $gm_location_title); // Remove <img> and <script>
			$gm_location_title = preg_replace('/\s*on\w+="[^"]*"/i', '', $gm_location_title);       // Remove inline event handlers
		
			// Final encode to escape any remaining HTML entities or unsafe characters
			$gm_location_title = htmlspecialchars($gm_location_title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
		
			// Assign sanitized title back to location data
			$google_map_locations[0]['gm_location_title'] = $gm_location_title;
		
			// Encode data for output, ensuring JSON strings are sanitized and properly escaped
			$map_settings = json_encode($this->get_map_settings($settings), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
			$map_locations = json_encode($google_map_locations, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
			$map_controls = json_encode($this->get_map_controls($settings), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
		
			// Set sanitized attributes
			$attributes  = ' data-settings="'. esc_attr($map_settings) .'"';
			$attributes .= ' data-locations="'. esc_attr($map_locations) .'"';
			$attributes .= ' data-controls="'. esc_attr($map_controls) .'"';

			// Output the sanitized HTML container
			echo '<div class="crt-google-map" '. $attributes .'></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	
		// Additional admin notice for missing API key
		if (current_user_can('manage_options') && 'api_key' === $settings['gm_integration'] && '' == get_option('crt_google_map_api_key')) { 
			echo '<p class="crt-api-key-missing">Please go to plugin <a href='. esc_url(admin_url( 'admin.php?page=crt-addons&tab=crt_tab_settings' )) .' target="_blank">Settings</a> and Insert Google Map API Key in order to make Google Maps work</p>'; 
		}
	}	
	
}