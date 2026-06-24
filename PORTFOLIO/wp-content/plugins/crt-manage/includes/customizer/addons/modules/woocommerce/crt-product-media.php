<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CRT_Product_Media extends Widget_Base {
	
	public function get_name() {
		return 'crt-product-media';
	}

	public function get_title() {
		return esc_html__( 'Product Media', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-product-images';
	}

	public function get_categories() {
        return ['crt_manage_woocommerce'];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product media', 'product', 'image', 'media' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_script_depends() {
		return [ 'flexslider', 'zoom', 'wc-single-product', 'photoswipe', 'photoswipe-ui-default', 'crt-lightgallery', 'crt-product-media'];
	}

	public function get_style_depends() {
		return [ 'woocommerce_prettyPhoto_css', 'photoswipe', 'photoswipe-default-skin', 'crt-lightgallery-css'];
	}

	public function add_control_gallery_slider_thumbs() {
		$this->add_control(
			'gallery_slider_thumbs_type',
			[
				'label' => esc_html__( 'Display Thumbs As', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'stacked' => esc_html__( 'Stacked', 'crt-manage' ),
					'slider' => esc_html__( 'Slider', 'crt-manage' )
				],
				'default' => 'stacked',
				'render_type' => 'template',
				'prefix_class' => 'crt-product-media-thumbs-',
				'selectors' => [
					'{{WRAPPER}}.crt-product-media-thumbs-none .crt-product-media-wrap .flex-control-nav' => 'display: none;',
					'{{WRAPPER}}.crt-product-media-thumbs-stacked .crt-product-media-wrap .flex-control-nav' => 'display: grid;',
					'{{WRAPPER}}.crt-product-media-thumbs-slider .crt-product-media-wrap .flex-control-nav' => 'display: flex;',
                ],
                'condition' => [
                    'gallery_slider_thumbs' => 'yes'
                ]
			]
		);

		// $this->add_control(
		// 	'gallery_slider_thumbs_type',
		// 	[
		// 		'type' => \Elementor\Controls_Manager::HIDDEN,
		// 		'default' => 'stacked',
		// 		'prefix_class' => 'crt-product-media-thumbs-',
		// 		'selectors' => [
		// 			'{{WRAPPER}}.crt-product-media-thumbs-stacked .crt-product-media-wrap .flex-control-nav' => 'display: grid;',
		// 		],
        //         'condition' => [
        //             'gallery_slider_thumbs' => 'yes'
        //         ]
		// 	]
		// );
	}

    public function add_controls_group_gallery_slider_thumbs() {

        // $this->add_control(
        //     'gallery_slider_thumbs_layout',
        //     [
        //         'label' => esc_html__( 'Thumbs Layout', 'crt-manage' ),
        //         'type' => Controls_Manager::SELECT,
        //         'options' => [
        //             'horizontal' => esc_html__( 'Horizontal', 'crt-manage' ),
        //             'vertical' => esc_html__( 'Vertical', 'crt-manage' )
        //         ],
        //         'default' => 'horizontal',
        //         'render_type' => 'template',
        //         'prefix_class' => 'crt-product-media-thumbs-',
        //         'condition' => [
        //             'gallery_slider_thumbs_type' => 'slider'
        //         ]
        //     ]
        // );

        $this->add_control(
            'gallery_slider_thumbs_vertical_height',
            [
                'label' => esc_html__( 'Max Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%'],
                'default' => [
                    'size' => 70,
                    'unit' => 'vh'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-product-media-wrap .woocommerce-product-gallery' => 'max-height: {{SIZE}}{{UNIT}}'
                ],
                'condition' => [
                    'gallery_slider_thumbs_layout' => 'vertical'
                ]
            ]
        );

        $this->add_responsive_control(
            'thumbnail_slider_nav',
            [
                'label' => esc_html__( 'Show Navigation Arrows', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'none',
                    'yes' => 'flex'
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-thumbnail-slider-arrow' => 'display:{{VALUE}} !important;'
                ],
                'condition' => [
                    'gallery_slider_thumbs_type' => 'slider',
                ]
            ]
        );

        $this->add_control(
            'thumbnail_slider_nav_hover',
            [
                'label' => esc_html__( 'Show on Hover', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'fade',
                'prefix_class' => 'crt-thumbnail-slider-nav-',
                'condition' => [
                    'gallery_slider_thumbs_type' => 'slider',
                    'thumbnail_slider_nav' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'thumbnail_slider_nav_icon',
            [
                'label' => esc_html__( 'Select Icon', 'crt-manage' ),
                'type' => 'crt-arrow-icons',
                'default' => 'fas fa-angle',
                'condition' => [
                    'gallery_slider_thumbs_type' => 'slider',
                    'thumbnail_slider_nav' => 'yes'
                ],
            ]
        );
    }

    public function add_control_gallery_slider_thumbs_to_slide() {
		$this->add_control(
			'gallery_slider_thumbs_to_slide',
			[
				'label' => esc_html__( 'Thumbs To Slide', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'default' => 2,
				'render_type' => 'template',
				'condition' => [
					'gallery_slider_thumbs_type' => ['slider'],
				],

			]
		);
	}

	
	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_product_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'product_media_sales_badge',
			[
				'label' => esc_html__( 'Show Sale Badge', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'product_media_sales_badge_text',
			[
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label' => esc_html__( 'Sale Badge Text', 'crt-manage' ),
				'default' => 'Sale!',
				'separator' => 'after',
				'condition' => [
					'product_media_sales_badge' => 'yes'
				]
			]
		);

		$this->add_control(
			'product_media_lightbox',
			[
				'label' => esc_html__( 'Enable Lightbox', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'crt-gallery-lightbox-',
				'selectors' => [
					'{{WRAPPER}}.crt-gallery-lightbox-yes .crt-product-media-wrap .woocommerce-product-gallery__trigger' => 'display: block !important;'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Image Gallery ========
		// Section: General ----------
		$this->start_controls_section(
			'section_product_media_gallery',
			[
				'label' => esc_html__( 'Image Gallery', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'gallery_slider_nav_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Main Image', 'crt-manage' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gallery_slider_nav',
			[
				'label' => esc_html__( 'Show Navigation Arrows', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'flex'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-gallery-slider-arrow' => 'display:{{VALUE}} !important;',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'gallery_slider_nav_hover',
			[
				'label' => esc_html__( 'Show on Hover', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'fade',
				'prefix_class' => 'crt-gallery-slider-nav-',
				'condition' => [
					'gallery_slider_nav' => 'yes'
				]
			]
		);

		$this->add_control(
			'gallery_slider_nav_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => 'crt-arrow-icons',
				'default' => 'svg-angle-1-left',
				'condition' => [
					'gallery_slider_nav' => 'yes',
				],
			]
		);

        // Last update.
//        $this->add_control(
//            'thumbnail_slider_nav_icon',
//            [
//                'label' => esc_html__( 'Select Icon', 'crt-manage' ),
//                'type' => 'crt-arrow-icons',
//                'default' => 'svg-angle-1-left',
//            ]
//        );

		$this->add_control(
			'gallery_slider_thumb_nav_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Gallery Thumbnails', 'crt-manage' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gallery_slider_thumbs',
			[
				'label' => esc_html__( 'Show Thumbnail Images', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'render_type' => 'template',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'grid'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-product-media-wrap .flex-control-nav' => 'display: {{VALUE}};',
				]
			]
		);

		$this->add_control_gallery_slider_thumbs();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'grid', 'query_source', ['pro-rl'] );

		$this->add_controls_group_gallery_slider_thumbs();

		$this->add_control(
			'gallery_slider_thumb_cols',
			[
				'label' => esc_html__( 'Thumbnails Per Row', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 2,
				'default' => 4,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}.crt-product-media-thumbs-stacked .crt-product-media-wrap .flex-control-thumbs' => 'grid-template-columns: repeat({{VALUE}}, auto);',
					'{{WRAPPER}}.crt-product-media-thumbs-slider .crt-product-media-thumbs-horizontal.crt-product-media-wrap .flex-control-thumbs li' => 'width: calc(100%/{{VALUE}}) !important;',
					'{{WRAPPER}}.crt-product-media-thumbs-slider.crt-product-media-thumbs-vertical .crt-product-media-wrap .flex-control-thumbs li' => 'height: calc(100%/{{VALUE}}) !important;'
				],
				'condition' => [
					'gallery_slider_thumbs' => 'yes',
					'gallery_slider_thumbs_type' => ['slider', 'stacked'],
				],

			]
		);

		$this->add_control_gallery_slider_thumbs_to_slide();

		$this->end_controls_section();

		// Tab: Content ==============
		// Section: Lightbox Popup ---
		$this->start_controls_section(
			'section_lightbox_popup',
			[
				'label' => esc_html__( 'Lightbox Popup', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'product_media_lightbox' => 'yes'
				]
			]
		);

		$this->add_control(
			'lightbox_extra_icon',
			[
				'label' => esc_html__( 'Lightbox Icon', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'choose_lightbox_extra_icon',
			[
				'label' => __( 'Icon', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'solid',
				],
				'condition' => [
					'lightbox_extra_icon' => 'yes'
				]
			]
		);

		$this->add_control(
			'lightbox_popup_autoplay',
			[
				'label' => esc_html__( 'Autoplay Slides', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);


		$this->add_control(
			'lightbox_popup_progressbar',
			[
				'label' => esc_html__( 'Show Progress Bar', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
				'condition' => [
					'lightbox_popup_autoplay' => 'true'
				]
			]
		);

		$this->add_control(
			'lightbox_popup_pause',
			[
				'label' => esc_html__( 'Autoplay Speed', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'condition' => [
					'lightbox_popup_autoplay' => 'true',
				],
			]
		);

		$this->add_control(
			'lightbox_popup_counter',
			[
				'label' => esc_html__( 'Show Counter', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_captions',
			[
				'label' => esc_html__( 'Show Captions', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_thumbnails',
			[
				'label' => esc_html__( 'Show Thumbnails', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_thumbnails_default',
			[
				'label' => esc_html__( 'Show Thumbs by Default', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
				'condition' => [
					'lightbox_popup_thumbnails' => 'true'
				]
			]
		);

		$this->add_control(
			'lightbox_popup_sharing',
			[
				'label' => esc_html__( 'Show Sharing Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_zoom',
			[
				'label' => esc_html__( 'Show Zoom Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_fullscreen',
			[
				'label' => esc_html__( 'Show Full Screen Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_download',
			[
				'label' => esc_html__( 'Show Download Button', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->end_controls_section(); // End Controls Section


		// Styles ====================
		// Section: Media ------------
		$this->start_controls_section(
			'section_style_media',
			[
				'label' => esc_html__( 'Media', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'media_border',
				'label' => esc_html__( 'Border', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .woocommerce-product-gallery__image',
			]
		);

		$this->add_responsive_control(
			'media_border_radius',
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
					'{{WRAPPER}} .woocommerce-product-gallery__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-product-gallery__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Section: Navigation -------
		$this->start_controls_section(
			'section_style_gallery_arrows_nav',
			[
				'label' => esc_html__( 'Main Image Arrows', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				// 'condition' => [
				// 	'gallery_display_as' => 'slider',
				// ],
			]
		);

		$this->start_controls_tabs( 'tabs_gallery_slider_nav_style' );

		$this->start_controls_tab(
			'tab_gallery_slider_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'gallery_slider_nav_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFFCC',
				'selectors' => [
					'{{WRAPPER}} .crt-gallery-slider-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-gallery-slider-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gallery_slider_nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-gallery-slider-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gallery_slider_nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFFCC',
				'selectors' => [
					'{{WRAPPER}} .crt-gallery-slider-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_gallery_slider_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'gallery_slider_nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-gallery-slider-arrow:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-gallery-slider-arrow:hover svg' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'gallery_slider_nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-gallery-slider-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gallery_slider_nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-gallery-slider-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'gallery_slider_nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-gallery-slider-arrow' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gallery_slider_nav_font_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-gallery-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-gallery-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gallery_slider_nav_size',
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
					'size' => 31,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-gallery-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-product-media-wrap .flex-direction-nav li' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-product-media-wrap .flex-direction-nav li a.flex-prev' => 'display: block; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-product-media-wrap .flex-direction-nav li a.flex-next' => 'display: block; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-product-media-wrap .flex-direction-nav li a.flex-prev:before' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-product-media-wrap .flex-direction-nav li a.flex-next:after' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'gallery_slider_nav_position_horizontal',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Position', 'crt-manage' ),
				'size_units' => [ 'px' ],
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
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .crt-gallery-slider-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-product-media-wrap .flex-direction-nav li.flex-nav-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-gallery-slider-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-product-media-wrap .flex-direction-nav li.flex-nav-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'gallery_slider_nav_border_type',
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
					'{{WRAPPER}} .crt-gallery-slider-arrow' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gallery_slider_nav_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-gallery-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'gallery_slider_nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'gallery_slider_nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-gallery-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Navigation -------
		$this->start_controls_section(
			'section_style_gallery_thumb_nav',
			[
				'label' => esc_html__( 'Gallery Thumbnails', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				// 'condition' => [
				// 	'gallery_display_as' => 'slider',
				// ],
			]
		);

		$this->add_responsive_control(
			'gallery_thumb_nav_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 50,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-product-media-wrap .flex-control-nav' => 'max-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-product-media-wrap .crt-fcn-wrap' => 'max-width: {{SIZE}}{{UNIT}};'
				],
				// 'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'gallery_thumb_nav_gutter_hr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Gutter', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-product-media-thumbs-stacked .crt-product-media-wrap .flex-control-nav' => 'grid-column-gap: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}}.crt-product-media-thumbs-slider .crt-product-media-wrap .flex-control-nav li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};'
				],
				// 'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'gallery_thumb_nav_gutter_vr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Gutter', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-product-media-thumbs-stacked .crt-product-media-wrap .flex-control-nav' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-product-media-thumbs-slider.crt-product-media-thumbs-vertical .crt-product-media-wrap .flex-control-nav li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'gallery_slider_thumbs_type' => 'stacked'
				]
			]
		);

		$this->add_responsive_control(
			'product_media_vertical_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Top Distance', 'crt-manage' ),
				'size_units' => [ 'px' ],
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
					'{{WRAPPER}}:not(.crt-product-media-thumbs-vertical) .crt-product-media-wrap .flex-viewport' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		// $this->add_responsive_control(
		// 	'product_media_horizontal_distance',
		// 	[
		// 		'type' => Controls_Manager::SLIDER,
		// 		'label' => esc_html__( 'Horizontal Distance', 'crt-manage' ),
		// 		'size_units' => [ 'px' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 50,
		// 			],
		// 		],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 12,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}}.crt-product-media-thumbs-vertical .crt-product-media-wrap .crt-fcn-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
		// 		]
		// 	]
		// );// Add in the section_style_gallery_thumb_nav section

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'gallery_thumb_border',
				'label' => esc_html__( 'Border', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .crt-product-media-wrap .flex-control-nav li img',
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'gallery_thumb_border_radius',
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
					'{{WRAPPER}} .crt-product-media-wrap .flex-control-nav li img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->start_controls_section(
			'section_style_thumbnail_arrows_nav',
			[
				'label' => esc_html__( 'Gallery Thumbnails Arrows', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'gallery_slider_thumbs_type' => 'slider',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_thumbnail_slider_nav_style' );

		$this->start_controls_tab(
			'tab_thumbnail_slider_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-thumbnail-slider-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-thumbnail-slider-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-thumbnail-slider-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFFCC',
				'selectors' => [
					'{{WRAPPER}} .crt-thumbnail-slider-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_thumbnail_slider_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-thumbnail-slider-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-thumbnail-slider-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-thumbnail-slider-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'thumbnail_slider_nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-thumbnail-slider-arrow' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'thumbnail_slider_nav_font_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
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
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-thumbnail-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-thumbnail-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'thumbnail_slider_nav_size',
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
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-thumbnail-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};', // remove line-height if not needed
					// '{{WRAPPER}} .crt-thumbnail-slider-arrows-wrap' => 'height: {{SIZE}}{{UNIT}};', // remove line-height if not needed
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_slider_nav_position_horizontal',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Position', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .crt-thumbnail-slider-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-thumbnail-slider-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_border_type',
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
					'{{WRAPPER}} .crt-thumbnail-slider-arrow' => 'border-style: {{VALUE}};',
				],
			]
		);	$this->add_control(
			'thumbnail_slider_nav_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 0,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-thumbnail-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'thumbnail_slider_nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-thumbnail-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->start_controls_section(
			'section_product_sales_badge_styles',
			[
				'label' => esc_html__( 'Sales Badge', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sales_badge_color',
			[
				'label'     => esc_html__( 'Color', 'crt-manage' ),
				'type'      => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-product-sales-badge span' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'sales_badge_background',
			[
				'label'     => esc_html__( 'Background color', 'crt-manage' ),
				'type'      => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-product-sales-badge span' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sales_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-product-sales-badge span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sales_badge_typography',
				'selector' => '{{WRAPPER}} .crt-product-sales-badge span',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'sales_badge_box_shadow',
				'selector' => '{{WRAPPER}} .crt-product-sales-badge span',
			]
		);

		$this->add_responsive_control(
			'sales_badge_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-product-sales-badge span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'sales_badge_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-product-sales-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'sales_badge_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dash ed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .crt-product-sales-badge span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'sales_badge_border_width',
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
					'{{WRAPPER}} .crt-product-sales-badge span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'sales_badge_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'sales_badge_border_radius',
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
					'{{WRAPPER}}  .crt-product-sales-badge span'=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		// Styles ====================
		// Section: Lightbox Icon -------
		$this->start_controls_section(
			'section_style_lightbox_icon',
			[
				'label' => esc_html__( 'Lightbox Icon', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'product_media_lightbox' => 'yes'
				],
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-product-media-lightbox i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-product-media-lightbox svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-product-media-lightbox' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'lightbox_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-product-media-lightbox' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'product_lightbox_shadow',
				'selector' => '{{WRAPPER}} .crt-product-media-lightbox',
			]
		);

		$this->add_control(
			'lightbox_tr_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-product-media-lightbox i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-product-media-lightbox' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'lightbox_border_type',
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
					'{{WRAPPER}} .crt-product-media-lightbox' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'lightbox_border_width',
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
					'{{WRAPPER}} .crt-product-media-lightbox' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'lightbox_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'lightbox_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-product-media-lightbox' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-product-media-lightbox svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'lightbox_icon_box_size',
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
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-product-media-lightbox' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-product-media-wrap .woocommerce-product-gallery__trigger' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'lightbox_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-product-media-lightbox' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-product-media-wrap .woocommerce-product-gallery__trigger' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'lightbox_radius',
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
					'{{WRAPPER}} .crt-product-media-lightbox' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}
	/** 
	 * Filer WooCommerce Flexslider options - Add Navigation Arrows
	 */
//	public function crt_update_woo_flexslider_options( $options ) {
//
//		$options['directionNav'] = true;
//
//		return $options;
//	}

	public function get_lightbox_settings( $settings ) {
		$lightbox_settings = [
			'selector' => '.woocommerce-product-gallery__image',
			'iframeMaxWidth' => '60%',
			'hash' => false,
			'autoplay' => $settings['lightbox_popup_autoplay'],
			'pause' => $settings['lightbox_popup_pause'] * 1000,
			'progressBar' => $settings['lightbox_popup_progressbar'],
			'counter' => $settings['lightbox_popup_counter'],
			'controls' => $settings['lightbox_popup_arrows'],
			'getCaptionFromTitleOrAlt' => $settings['lightbox_popup_captions'],
			'thumbnail' => $settings['lightbox_popup_thumbnails'],
			'showThumbByDefault' => $settings['lightbox_popup_thumbnails_default'],
			'share' => $settings['lightbox_popup_sharing'],
			'zoom' => $settings['lightbox_popup_zoom'],
			'fullScreen' => $settings['lightbox_popup_fullscreen'],
			'download' => $settings['lightbox_popup_download'],
		];

		return json_encode( $lightbox_settings );
	}

	public function crt_remove_woo_default_lightbox() {	 	 
	   remove_theme_support( 'wc-product-gallery-lightbox' );	 	 
	}

    public function get_product_safely() {
        global $post, $product;

        if (is_a($product, 'WC_Product')) {
            return $product;
        }

        if (is_object($post) && 'product' === $post->post_type) {
            $product = wc_get_product($post->ID);
            return $product;
        }

        $product_id = get_queried_object_id();
        return wc_get_product($product_id);
    }
	
	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		$settings['thumbnail_slider_nav'] = 'yes';
		
		// Get Product
		$product = $this->get_product_safely();
		if ( ! $product ) {
			return;
		}

		// Product ID
		$post = get_post( $product->get_id() );
		$gallery_images = $product->get_gallery_image_ids();
		
		add_action( 'wp', [$this, 'crt_remove_woo_default_lightbox'], 99 );

		$this->add_render_attribute(
			'thumbnails_attributes',
			[
				'class' => ['crt-product-media-wrap', 'crt-product-media-thumbs-horizontal'],
				'data-slidestoshow' => $settings['gallery_slider_thumb_cols'],
				'data-slidestoscroll' => isset($settings['gallery_slider_thumbs_to_slide']) ? $settings['gallery_slider_thumbs_to_slide'] : '',
			]
		);

		// Lightbox
		if ( 'yes' === $settings['product_media_lightbox'] ) {
			$lightbox = ' data-lightbox="'. esc_attr( $this->get_lightbox_settings( $settings ) ) .'"';
		} else { 
			$lightbox = '';
		}

		// Output
		echo '<div '.  $this->get_render_attribute_string( 'thumbnails_attributes' ) .' '. $lightbox .'>';

		// Sales Badge
		if ( $product->is_on_sale() && 'yes' === $settings['product_media_sales_badge'] ) {
			echo '<div class="crt-product-sales-badge">';
				echo apply_filters( 'woocommerce_sale_flash', '<span>' . wp_kses_post($settings['product_media_sales_badge_text']) . '</span>', $post, $product );
			echo '</div>';
		}

		// Lightbox Icon
		if ( 'yes' === $settings['product_media_lightbox'] && 'yes' === $settings['lightbox_extra_icon'] && '' !== $settings['choose_lightbox_extra_icon'] ) {
			
			echo '<div class="crt-product-media-lightbox">';
				\Elementor\Icons_Manager::render_icon( $settings['choose_lightbox_extra_icon'], [ 'aria-hidden' => 'true' ] );
			echo '</div>';

		}
        update_option('crt_enable_woo_flexslider_navigation', 'on');
		// Slider Arrows
		if ( !empty($gallery_images) && 'on' === get_option('crt_enable_woo_flexslider_navigation', 'on') ) {
			if ( 'yes' === $settings['gallery_slider_nav'] && 'none' !== $settings['gallery_slider_nav_icon']) {
				echo '<div class="crt-gallery-slider-arrows-wrap">';
					echo '<div class="crt-gallery-slider-prev-arrow crt-gallery-slider-arrow">'. Utilities::get_crt_icon( $settings['gallery_slider_nav_icon'], 'left' ) .'</div>';
					echo '<div class="crt-gallery-slider-next-arrow crt-gallery-slider-arrow">'. Utilities::get_crt_icon( $settings['gallery_slider_nav_icon'], 'right' ) .'</div>';
				echo '</div>';
			}
		}
		
		// Thumbnail Slider Arrows
		if ( 'slider' === $settings['gallery_slider_thumbs_type'] && 'yes' === $settings['thumbnail_slider_nav'] && 'none' !== $settings['thumbnail_slider_nav_icon']) {
				echo '<div class="crt-thumbnail-slider-prev-arrow crt-tsa-hidden crt-thumbnail-slider-arrow">'. Utilities::get_crt_icon( $settings['thumbnail_slider_nav_icon'], 'left' ) .'</div>';
				echo '<div class="crt-thumbnail-slider-next-arrow crt-tsa-hidden crt-thumbnail-slider-arrow">'. Utilities::get_crt_icon( $settings['thumbnail_slider_nav_icon'], 'right' ) .'</div>';
		}

		if ( is_a( $product, 'WC_Product' ) ) {
            wc_get_template('single-product/product-image.php');
		}

		echo '</div>';

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
			<script>
				jQuery( '.woocommerce-product-gallery' ).each( function () {
					jQuery( this ).wc_product_gallery();
				} );
			</script>
			<?php
		}
	}
	
}