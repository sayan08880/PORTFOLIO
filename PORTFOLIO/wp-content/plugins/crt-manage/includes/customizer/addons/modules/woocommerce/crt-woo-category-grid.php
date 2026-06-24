<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Woo_Category_Grid_Pro extends Widget_Base {
	
	public function get_name() {
		return 'crt-woo-category-grid-pro';
	}

	public function get_title() {
		return esc_html__( 'Woo Category Grid', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-gallery-grid';
	}

	public function get_categories() {
        return ['crt_manage_woocommerce'];
	}

	public function get_keywords() {
		return [ 'product category grid', 'woocommerce category', 'product categories', 'product', 'woocommerce'];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_script_depends() {
		return [ 'crt-manage-isotope', 'crt-manage-lib-slick', 'crt-lightgallery', 'crt-woo-category-grid-pro' ];
    }

	public function get_style_depends() {
		return [ 'crt-animations-css', 'crt-link-animations-css', 'crt-button-animations-css', 'crt-loading-animations-css', 'crt-lightgallery-css' ];
    }

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://crthemes.com/contact/?ref=rea-plugin-panel-woo-grid-help-btn';
    		return 'https://crthemes.com/contact';
    }
	
	public function add_control_layout_select() {
		$this->add_control(
			'layout_select',
			[
				'label' => esc_html__( 'Select Layout', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fitRows',
				'options' => [
					'fitRows' => esc_html__( 'FitRows - Equal Height', 'crt-manage' ),
					'list' => esc_html__( 'List Style', 'crt-manage' ),
					'masonry' => esc_html__( 'Masonry - Unlimited Height', 'crt-manage' )
				],
				'label_block' => true
			]
		);
	}
    
    public function add_control_layout_columns() {
		$this->add_responsive_control(
			'layout_columns',
			[
				'label' => esc_html__( 'Columns', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 3,
				'widescreen_default' => 3,
				'laptop_default' => 3,
				'tablet_extra_default' => 3,
				'tablet_default' => 2,
				'mobile_extra_default' => 2,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'crt-manage' ),
					2 => esc_html__( 'Two', 'crt-manage' ),
					3 => esc_html__( 'Three', 'crt-manage' ),
					4 => esc_html__( 'Four', 'crt-manage' ),
					5 => esc_html__( 'Five', 'crt-manage' ),
					6 => esc_html__( 'Six', 'crt-manage' ),
				],
				'prefix_class' => 'crt-grid-columns-%s',
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
				]
			]
		);
	}
	
	public function add_control_layout_animation() {
		$this->add_control(
			'layout_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'crt-manage' ),
					'fade' => esc_html__( 'Fade', 'crt-manage' ),
					'fade-slide' => esc_html__( 'Fade + SlideUp', 'crt-manage' ),
					'zoom' => esc_html__( 'Zoom', 'crt-manage' ),
				],
				'selectors_dictionary' => [
					'default' => '',
					'fade' => 'opacity: 0',
					'fade-slide' => 'opacity: 0; top: 20px',
					'zoom' => 'opacity: 0; transform: scale(0.01)',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-inner' => '{{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'before'
			]
		);
	}

	public function add_option_element_select() {
		return [
			'title' => esc_html__( 'Title', 'crt-manage' ),
			'description' => esc_html__( 'Description', 'crt-manage' ),
			'count' => esc_html__( 'Count', 'crt-manage' ),
			'separator' => esc_html__( 'Separator', 'crt-manage' )
		];
	}

	public function add_repeater_args_element_like_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_like_text() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_like_show_count() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_2() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_3() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_4() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_5() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_6() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_action() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_direction() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_tooltip() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}
	
	public function add_control_overlay_animation_divider() {
		$this->add_control(
			'overlay_animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
	}
	
	public function add_control_overlay_image() {
		$this->add_control(
			'overlay_image',
			[
				'label' => esc_html__( 'Upload GIF', 'crt-manage' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				]
			]
		);
	}
	
	public function add_control_overlay_image_width() {
		
		$this->add_control(
			'overlay_image_width',
			[
				'label' => esc_html__( 'GIF Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 70,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-hover-bg img' => 'max-width: {{SIZE}}px;',
				],
			]
		);
	}

	public function add_control_image_effects() {
		$this->add_control(
			'image_effects',
			[
				'label' => esc_html__( 'Select Effect', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'zoom-in' => esc_html__( 'Zoom In', 'crt-manage' ),
					'zoom-out' => esc_html__( 'Zoom Out', 'crt-manage' ),
					'grayscale-in' => esc_html__( 'Grayscale In', 'crt-manage' ),
					'grayscale-out' => esc_html__( 'Grayscale Out', 'crt-manage' ),
					'blur-in' => esc_html__( 'Blur In', 'crt-manage' ),
					'blur-out' => esc_html__( 'Blur Out', 'crt-manage' ),
					'slide' => esc_html__( 'Slide', 'crt-manage' ),
				],
				'default' => 'zoom-in',
			]
		);
	}
	
	public function add_control_lightbox_popup_thumbnails() {}
	
	public function add_control_lightbox_popup_thumbnails_default() {}
	
	public function add_control_lightbox_popup_sharing() {}
	
	public function add_control_filters_icon() {}
	
	public function add_control_filters_icon_align() {}
	
	public function add_control_filters_default_filter() {}

	public function add_control_grid_item_even_bg_color() {
		$this->add_control(
			'grid_item_even_bg_color',
			[
				'label'  => esc_html__( 'Even Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item:nth-child(2n) .crt-grid-item-above-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-item:nth-child(2n) .crt-grid-item-below-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item:nth-child(2n)' => 'background-color: {{VALUE}}'
				],
			]
		);
	}
	
	public function add_control_grid_item_even_border_color() {
		$this->add_control(
			'grid_item_even_border_color',
			[
				'label'  => esc_html__( 'Even Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item:nth-child(2n) .crt-grid-item-above-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item:nth-child(2n) .crt-grid-item-below-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item:nth-child(2n)' => 'border-color: {{VALUE}}'
				],
			]
		);
	}
	
	public function add_control_overlay_color() {
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_color',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => 'rgba(0, 0, 0, 0.25)',
					],
				],
				'selector' => '{{WRAPPER}} .crt-grid-media-hover-bg'
			]
		);
	}
	
	public function add_control_overlay_blend_mode() {
		$this->add_control(
			'overlay_blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => esc_html__( 'Normal', 'crt-manage' ),
					'multiply' => esc_html__( 'Multiply', 'crt-manage' ),
					'screen' => esc_html__( 'Screen', 'crt-manage' ),
					'overlay' => esc_html__( 'Overlay', 'crt-manage' ),
					'darken' => esc_html__( 'Darken', 'crt-manage' ),
					'lighten' => esc_html__( 'Lighten', 'crt-manage' ),
					'color-dodge' => esc_html__( 'Color-dodge', 'crt-manage' ),
					'color-burn' => esc_html__( 'Color-burn', 'crt-manage' ),
					'hard-light' => esc_html__( 'Hard-light', 'crt-manage' ),
					'soft-light' => esc_html__( 'Soft-light', 'crt-manage' ),
					'difference' => esc_html__( 'Difference', 'crt-manage' ),
					'exclusion' => esc_html__( 'Exclusion', 'crt-manage' ),
					'hue' => esc_html__( 'Hue', 'crt-manage' ),
					'saturation' => esc_html__( 'Saturation', 'crt-manage' ),
					'color' => esc_html__( 'Color', 'crt-manage' ),
					'luminosity' => esc_html__( 'luminosity', 'crt-manage' ),
				],
				'selectors' => [
					// '{{WRAPPER}} {{CURRENT_ITEM}} .crt-grid-media-hover-bg' => 'mix-blend-mode: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}
	
	public function add_control_overlay_border_color() {
		$this->add_control(
			'overlay_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'border-color: {{VALUE}}',
				],
			]
		);
	}
	
	public function add_control_overlay_border_type() {
		$this->add_control(
			'overlay_border_type',
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
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'border-style: {{VALUE}};',
				],
			]
		);
	}
	
	public function add_control_overlay_border_width() {
		$this->add_control(
			'overlay_border_width',
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
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'overlay_border_type!' => 'none',
				],
			]
		);
	}

	public function add_control_title_pointer_color_hr() {
		$this->add_control(
			'title_pointer_color_hr',
			[
				'label'  => esc_html__( 'Hover Effect Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .crt-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-item-title .crt-pointer-item:after' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}

	public function add_control_title_pointer() {
		$this->add_control(
			'title_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'underline' => esc_html__( 'Underline', 'crt-manage' ),
					'overline' => esc_html__( 'Overline', 'crt-manage' ),
				],
			]
		);
	}

	public function add_control_title_pointer_height() {
		$this->add_control(
			'title_pointer_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .crt-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-title .crt-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'title_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_title_pointer_animation() {
		$this->add_control(
			'title_pointer_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'slide' => 'Slide',
					'grow' => 'Grow',
					'drop' => 'Drop',
				],
				'condition' => [
					'title_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_categories_pointer_color_hr() {
		$this->add_control(
			'categories_pointer_color_hr',
			[
				'label'  => esc_html__( 'Hover Effect Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-product-categories .crt-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-product-categories .crt-pointer-item:after' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}
	
	public function add_control_categories_pointer() {
		$this->add_control(
			'categories_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'underline' => esc_html__( 'Underline', 'crt-manage' ),
					'overline' => esc_html__( 'Overline', 'crt-manage' ),
				],
			]
		);
	}
	
	public function add_control_categories_pointer_height() {
		$this->add_control(
			'categories_pointer_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-product-categories .crt-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-product-categories .crt-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'categories_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_categories_pointer_animation() {
		$this->add_control(
			'categories_pointer_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'slide' => 'Slide',
					'grow' => 'Grow',
					'drop' => 'Drop',
				],
				'condition' => [
					'categories_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_tags_pointer_color_hr() {
		$this->add_control(
			'tags_pointer_color_hr',
			[
				'label'  => esc_html__( 'Hover Effect Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-product-tags .crt-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-product-tags .crt-pointer-item:after' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}
	
	public function add_control_tags_pointer() {
		$this->add_control(
			'tags_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'underline' => esc_html__( 'Underline', 'crt-manage' ),
					'overline' => esc_html__( 'Overline', 'crt-manage' ),
				],
			]
		);
	}
	
	public function add_control_tags_pointer_height() {
		$this->add_control(
			'tags_pointer_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-product-tags .crt-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-product-tags .crt-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'tags_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_tags_pointer_animation() {
		$this->add_control(
			'tags_pointer_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'slide' => 'Slide',
					'grow' => 'Grow',
					'drop' => 'Drop',
				],
				'condition' => [
					'tags_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_add_to_cart_animation() {
		$this->add_control(
			'add_to_cart_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => 'crt-button-animations',
				'default' => 'crt-button-none',
			]
		);
	}
	
	public function add_control_add_to_cart_animation_height() {
		$this->add_control(
			'add_to_cart_animation_height',
			[
				'label' => esc_html__( 'Animation Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [					
					'{{WRAPPER}} [class*="crt-button-underline"]:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} [class*="crt-button-overline"]:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'add_to_cart_animation' => [ 
						'crt-button-underline-from-left',
						'crt-button-underline-from-center',
						'crt-button-underline-from-right',
						'crt-button-underline-reveal',
						'crt-button-overline-reveal',
						'crt-button-overline-from-left',
						'crt-button-overline-from-center',
						'crt-button-overline-from-right'
					]
				],
			]
		);
	}
	
	public function add_control_filters_pointer_color_hr() {
		$this->add_control(
			'filters_pointer_color_hr',
			[
				'label'  => esc_html__( 'Hover Effect Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters .crt-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-filters .crt-pointer-item:after' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}
	
	public function add_control_filters_pointer() {
		$this->add_control(
			'filters_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'underline' => esc_html__( 'Underline', 'crt-manage' ),
					'overline' => esc_html__( 'Overline', 'crt-manage' ),
				],
			]
		);
	}
	
	public function add_control_filters_pointer_height() {
		$this->add_control(
			'filters_pointer_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-filters .crt-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-filters .crt-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'filters_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_filters_pointer_animation() {
		$this->add_control(
			'filters_pointer_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'slide' => 'Slide',
					'grow' => 'Grow',
					'drop' => 'Drop',
				],
				'condition' => [
					'filters_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}

    public function register_controls() {

		// Tab: Content ==============
		// Section: Query ------------
		$this->start_controls_section(
			'section_woo_category_query',
			[
				'label' => esc_html__( 'Query', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'query_hide_empty',
			[
				'label' => esc_html__( 'Hide Empty', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'hide_child_categories',
			[
				'label' => esc_html__( 'Hide Child Categories', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => ''
			]
		);

		// Exclude
		$this->add_control(
			'query_exclude_cats',
			[
				'label' => esc_html__( 'Exclude Categories', 'crt-manage' ),
				'type' => 'crt-ajax-select2',
				'options' => 'ajaxselect2/get_taxonomies',
				'query_slug' => 'product_cat',
				'multiple' => true,
				'label_block' => true
			]
		);

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Layout -----------
		$this->start_controls_section(
			'section_grid_layout',
			[
				'label' => esc_html__( 'Layout', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_layout_select();

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'layout_image_crop',
				'default' => 'full',
			]
		);

		$this->add_control_layout_columns();


		// Media
		$this->add_control(
			'layout_list_media_section',
			[
				'label' => esc_html__( 'Media', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'list',
				],
			]
		);

		$this->add_control(
			'layout_list_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'crt-manage' ),
					'right' => esc_html__( 'Right', 'crt-manage' ),
					'zigzag' => esc_html__( 'ZigZag', 'crt-manage' ),
				],
				'condition' => [
					'layout_select' => 'list',
				],
			]
		);

		$this->add_control(
			'layout_list_media_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'layout_select' => 'list',
				]
			]
		);

		$this->add_control(
			'layout_list_media_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'layout_select' => 'list',
				]
			]
		);

		$this->add_responsive_control(
			'layout_gutter_hr',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'widescreen_default' => [
					'size' => 20,
				],
				'laptop_default' => [
					'size' => 20,
				],
				'tablet_extra_default' => [
					'size' => 20,
				],
				'tablet_default' => [
					'size' => 20,
				],
				'mobile_extra_default' => [
					'size' => 20,
				],
				'mobile_default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
				],
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'layout_gutter_vr',
			[
				'label' => esc_html__( 'Vertical Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'widescreen_default' => [
					'size' => 20,
				],
				'laptop_default' => [
					'size' => 20,
				],
				'tablet_extra_default' => [
					'size' => 20,
				],
				'tablet_default' => [
					'size' => 20,
				],
				'mobile_extra_default' => [
					'size' => 20,
				],
				'mobile_default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
				],
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->add_control_layout_animation();

		$this->add_control(
			'layout_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'condition' => [
					'layout_animation!' => 'default',
				],
			]
		);

		$this->add_control(
			'layout_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.05,
				'condition' => [
					'layout_animation!' => 'default',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Elements ---------
		$this->start_controls_section(
			'section_grid_elements',
			[
				'label' => esc_html__( 'Elements', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$element_select = $this->add_option_element_select();

		$repeater->add_control(
			'element_select',
			[
				'label' => esc_html__( 'Select Element', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => $element_select,
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_location',
			[
				'label' => esc_html__( 'Location', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'below',
				'options' => [
					'above' => esc_html__( 'Above Media', 'crt-manage' ),
					'over' => esc_html__( 'Over Media', 'crt-manage' ),
					'below' => esc_html__( 'Below Media', 'crt-manage' ),
				]
			]
		);

		$repeater->add_control(
			'element_display',
			[
				'label' => esc_html__( 'Display', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'inline' => esc_html__( 'Inline', 'crt-manage' ),
					'block' => esc_html__( 'Seperate Line', 'crt-manage' ),
					'custom' => esc_html__( 'Custom Width', 'crt-manage' ),
				],
			]
		);

		$repeater->add_control(
			'element_custom_width',
			[
				'label' => esc_html__( 'Element Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
				],
				'condition' => [
					'element_display' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'element_show_brackets',
			[
				'label' => esc_html__( 'Show Brackets', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_select' => 'count'
				],
			]
		);

		$repeater->add_control(
			'element_align_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
                'default' => 'middle',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'crt-manage' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'crt-manage' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'crt-manage' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'condition' => [
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
            'element_align_hr',
            [
                'label' => esc_html__( 'Horizontal Align', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'crt-manage' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'crt-manage' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'crt-manage' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'after'
            ]
        );

		$repeater->add_control(
			'element_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h3',
				'condition' => [
					'element_select' => 'title',
				]
			]
		);

		$repeater->add_control(
			'element_word_count',
			[
				'label' => esc_html__( 'Word Count', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 20,
				'min' => 1,
				'condition' => [
					'element_select' => [ 'title', 'description' ],
				]
			]
		);

		$repeater->add_control(
			'element_tax_sep',
			[
				'label' => esc_html__( 'Separator', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => ', ',
				'condition' => [
					'element_select!' => [
						'title',
						'separator',
						'count'
					],
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control( 'element_like_icon', $this->add_repeater_args_element_like_icon() );

		$repeater->add_control( 'element_like_show_count', $this->add_repeater_args_element_like_show_count() );

		$repeater->add_control( 'element_like_text', $this->add_repeater_args_element_like_text() );

		$repeater->add_control( 'element_sharing_icon_1', $this->add_repeater_args_element_sharing_icon_1() );

		$repeater->add_control( 'element_sharing_icon_2', $this->add_repeater_args_element_sharing_icon_2() );

		$repeater->add_control( 'element_sharing_icon_3', $this->add_repeater_args_element_sharing_icon_3() );

		$repeater->add_control( 'element_sharing_icon_4', $this->add_repeater_args_element_sharing_icon_4() );

		$repeater->add_control( 'element_sharing_icon_5', $this->add_repeater_args_element_sharing_icon_5() );

		$repeater->add_control( 'element_sharing_icon_6', $this->add_repeater_args_element_sharing_icon_6() );

		$repeater->add_control( 'element_sharing_trigger', $this->add_repeater_args_element_sharing_trigger() );

		$repeater->add_control( 'element_sharing_trigger_icon', $this->add_repeater_args_element_sharing_trigger_icon() );

		$repeater->add_control( 'element_sharing_trigger_action', $this->add_repeater_args_element_sharing_trigger_action() );

		$repeater->add_control( 'element_sharing_trigger_direction', $this->add_repeater_args_element_sharing_trigger_direction() );

		$repeater->add_control( 'element_sharing_tooltip', $this->add_repeater_args_element_sharing_tooltip() );

		$repeater->add_control(
			'element_separator_style',
			[
				'label' => esc_html__( 'Select Styling', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'crt-grid-sep-style-1',
				'options' => [
					'crt-grid-sep-style-1' => esc_html__( 'Separator Style 1', 'crt-manage' ),
					'crt-grid-sep-style-2' => esc_html__( 'Separator Style 2', 'crt-manage' ),
				],
				'condition' => [
					'element_select' => 'separator',
				]
			]
		);
		
		$repeater->add_control(
			'element_extra_text_pos',
			[
				'label' => esc_html__( 'Extra Text Display', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'before' => esc_html__( 'Before Element', 'crt-manage' ),
					'after' => esc_html__( 'After Element', 'crt-manage' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select' => [
						'title',
						'count'
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_text',
			[
				'label' => esc_html__( 'Extra Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'condition' => [
					'element_select' => [
						'title',
						'count'
					],
					'element_extra_text_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon_pos',
			[
				'label' => esc_html__( 'Extra Icon Position', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'before' => esc_html__( 'Before Element', 'crt-manage' ),
					'after' => esc_html__( 'After Element', 'crt-manage' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select' => [
						'title'
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon',
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
					'element_select' => [
						'title'
					],
					'element_extra_icon_pos!' => 'none'
				]
			]
		);


		$repeater->add_control(
			'animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => 'crt-animations',
				'default' => 'none',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
			'element_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-animation-wrap:hover {{CURRENT_ITEM}}' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::crt_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'crt-manage' ),
					'medium' => esc_html__( 'Medium', 'crt-manage' ),
					'large' => esc_html__( 'Large', 'crt-manage' ),
				],
				'default' => 'large',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_responsive_control(
			'element_show_on',
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
				'selectors_dictionary' => [
					'' => 'position: absolute; left: -99999999px;',
					'yes' => 'position: static; left: auto;'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'grid_elements',
			[
				'label' => esc_html__( 'Grid Elements', 'crt-manage' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'element_select' => 'title',
						'element_display' => 'inline',
						'element_location' => 'below',
						'element_align_hr' => 'center',
						'element_align_vr' => 'middle'
					],
					[
						'element_select' => 'count',
						'element_display' => 'block',
						'element_location' => 'below',
						'element_align_hr' => 'center',
						'element_align_vr' => 'middle',
						'element_animation' => 'slide-bottom' 
					],
					[
						'element_select' => 'description',
					]
				],
				'title_field' => '{{{ element_select.charAt(0).toUpperCase() + element_select.slice(1) }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_image_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'overlay_width',
			[
				'label' => esc_html__( 'Overlay Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_hegiht',
			[
				'label' => esc_html__( 'Overlay Hegiht', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .crt-grid-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'overlay_post_link',
			[
				'label' => esc_html__( 'Link to Single Page', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => 'crt-animations-alt',
				'default' => 'fade-in',
			]
		);

		$this->add_control(
			'overlay_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-animation-wrap:hover .crt-grid-media-hover-bg' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::crt_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'crt-manage' ),
					'medium' => esc_html__( 'Medium', 'crt-manage' ),
					'large' => esc_html__( 'Large', 'crt-manage' ),
				],
				'default' => 'large',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control_overlay_animation_divider();

		$this->add_control_overlay_image();

		$this->add_control_overlay_image_width();

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Image Effects ----
		$this->start_controls_section(
			'section_image_effects',
			[
				'label' => esc_html__( 'Image Effects', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_image_effects();

		$this->add_control(
			'image_effects_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-wrap img' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-media-wrap:hover img' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::crt_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_size',
			[
				'label' => esc_html__( 'Animation Size', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'crt-manage' ),
					'medium' => esc_html__( 'Medium', 'crt-manage' ),
					'large' => esc_html__( 'Large', 'crt-manage' ),
				],
				'default' => 'medium',
				'condition' => [
					'image_effects!' => ['none', 'slide'],
				]
			]
		);

		$this->add_control(
			'image_effects_direction',
			[
				'label' => esc_html__( 'Animation Direction', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => esc_html__( 'Top', 'crt-manage' ),
					'right' => esc_html__( 'Right', 'crt-manage' ),
					'bottom' => esc_html__( 'Bottom', 'crt-manage' ),
					'left' => esc_html__( 'Left', 'crt-manage' ),
				],
				'default' => 'bottom',
				'condition' => [
					'image_effects!' => 'none',
					'image_effects' => 'slide'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section
		
		// Styles ====================
		// Section: Grid Item --------
		$this->start_controls_section(
			'section_style_grid_item',
			[
				'label' => esc_html__( 'Grid Item', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'grid_item_styles_selector',
			[
				'label' => esc_html__( 'Apply Styles To', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'inner' => esc_html__( 'Inner Elements', 'crt-manage' ),
					'wrapper' => esc_html__( 'Wrapper', 'crt-manage' )
				],
				'default' => 'inner',
				'prefix_class' => 'crt-item-styles-'
			]
		);

		$this->add_control(
			'grid_item_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-above-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-item-below-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'grid_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item-above-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item-below-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item' => 'border-color: {{VALUE}}'
				],
			]
		);

		$this->add_control_grid_item_even_bg_color();

		$this->add_control_grid_item_even_border_color();

		$this->add_control(
			'grid_item_border_type',
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
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item-above-content' => 'border-style: {{VALUE}};',
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item-below-content' => 'border-style: {{VALUE}};',
					'{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item' => 'border-style: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grid_item_border_width',
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
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item-above-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-item-styles-inner .crt-grid-item-below-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'grid_item_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'grid_item_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-above-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-below-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.crt-item-styles-wrapper .crt-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'grid_item_radius',
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
					'{{WRAPPER}} .crt-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-above-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-below-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'grid_item_shadow',
				'selector' => '{{WRAPPER}} .crt-grid-item',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Grid Media -------
		$this->start_controls_section(
			'section_style_grid_media',
			[
				'label' => esc_html__( 'Grid Media', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'grid_media_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-image-wrap' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'grid_media_border_type',
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
					'{{WRAPPER}} .crt-grid-image-wrap' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_media_border_width',
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
					'{{WRAPPER}} .crt-grid-image-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'grid_media_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'grid_media_radius',
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
					'{{WRAPPER}} .crt-grid-image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grid_media_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-image-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->add_control_overlay_color();

		$this->add_control_overlay_blend_mode();

		$this->add_control_overlay_border_color();

		$this->add_control_overlay_border_type();

		$this->add_control_overlay_border_width();

		$this->add_control(
			'overlay_radius',
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
					'{{WRAPPER}} .crt-grid-media-hover-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_title_style' );

		$this->start_controls_tab(
			'tab_grid_title_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'title_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'categories_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block span[class*="crt-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'categories_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item .inner-block i[class*="crt-grid-extra-icon"]' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_title_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'title_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'title_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_title_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_title_pointer();

		$this->add_control_title_pointer_height();

		$this->add_control_title_pointer_animation();

		$this->add_control(
			'title_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-item-title .crt-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-item-title .crt-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .crt-grid-item-title',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '700',
					],
					'font_size'   => [
						'default' => [
							'size' => '',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'title_border_type',
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
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_border_width',
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
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'title_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-title .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'title_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
					'{{WRAPPER}} .crt-grid-item-title .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-title .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
					'{{WRAPPER}} .crt-grid-item-title .crt-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-title .crt-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Description ----------
		$this->start_controls_section(
			'section_style_description',
			[
				'label' => esc_html__( 'Description', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-description .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'description_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-description .inner-block' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'description_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-description .inner-block' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .crt-grid-item-description',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'description_justify',
			[
				'label' => esc_html__( 'Justify Text', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'widescreen_default' => '',
				'laptop_default' => '',
				'tablet_extra_default' => '',
				'tablet_default' => '',
				'mobile_extra_default' => '',
				'mobile_default' => '',
				'selectors_dictionary' => [
					'' => '',
					'yes' => 'text-align: justify;'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-description .inner-block' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'description_border_type',
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
					'{{WRAPPER}} .crt-grid-item-description .inner-block' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_border_width',
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
					'{{WRAPPER}} .crt-grid-item-description .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'description_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'description_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-description .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'description_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-description .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Count
		$this->start_controls_section(
			'section_style_count',
			[
				'label' => esc_html__( 'Count', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_count_style' );

		$this->start_controls_tab(
			'tab_grid_count_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'count_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-count .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'count_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF00',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-count .inner-block a' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_count_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'count_color_hover',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-count .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'count_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF00',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-count .inner-block a:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'count_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item .crt-grid-item-count .inner-block span[class*="crt-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'count_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-count .inner-block a' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grid_count_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'count_typography',
				'selector' => '{{WRAPPER}} .crt-grid-item-count a',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size'   => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'count_border_type',
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
					'{{WRAPPER}} .crt-grid-item-count .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'count_border_width',
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
					'{{WRAPPER}} .crt-grid-item-count .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'count_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'count_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-count .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'count_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-item-count .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'count_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
					'{{WRAPPER}} .crt-grid-item-count .crt-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-item-count .crt-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Separator Style 1 
		$this->start_controls_section(
			'section_style_separator1',
			[
				'label' => esc_html__( 'Separator Style 1', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'separator1_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-1 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator1_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-1:not(.crt-grid-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-sep-style-1.crt-grid-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator1_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-1 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator1_border_type',
			[
				'label' => esc_html__( 'Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-1 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator1_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-1 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator1_radius',
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
					'{{WRAPPER}} .crt-grid-sep-style-1 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Separator Style 2 
		$this->start_controls_section(
			'section_style_separator2',
			[
				'label' => esc_html__( 'Separator Style 2', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'separator2_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-2 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator2_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-2:not(.crt-grid-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-sep-style-2.crt-grid-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator2_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
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
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-2 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'separator2_border_type',
			[
				'label' => esc_html__( 'Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-2 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator2_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-sep-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'separator2_radius',
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
					'{{WRAPPER}} .crt-grid-sep-style-2 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
    }

	// Get Image Effect Class
	public function get_image_effect_class( $settings ) {
		$class = '';

		// Animation Class
		if ( 'none' !== $settings['image_effects'] ) {
			$class .= ' crt-'. $settings['image_effects'];
		}
		
		// Slide Effect
		if ( 'slide' !== $settings['image_effects'] ) {
			$class .= ' crt-effect-size-'. $settings['image_effects_size'];
		} else {
			$class .= ' crt-effect-dir-'. $settings['image_effects_direction'];
		}

		return $class;
	}
	
	// Render Post Categories
	public function render_category_title( $settings, $class, $term ) {

		// Pointer Class
		$categories_pointer = $this->get_settings()['title_pointer'];
		$categories_pointer_animation = $this->get_settings()['title_pointer_animation'];

		$class .= ' crt-pointer-'. $categories_pointer;
		$class .= ' crt-pointer-line-fx crt-pointer-fx-'. $categories_pointer_animation;

		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
		$element_title_tag = Utilities::validate_html_tags_wl( $settings['element_title_tag'], 'h3', $tags_whitelist );

		echo '<'. esc_attr($element_title_tag) .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="crt-grid-extra-icon-left '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}

				// Taxonomies
				echo '<a href="'. esc_url(get_term_link( $term->term_id )) .'" class="crt-pointer-item">';
					echo esc_attr(wp_trim_words( $term->name, $settings['element_word_count'] ));
				echo '</a>';

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="crt-grid-extra-icon-right '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</'. esc_attr($element_title_tag) .'>';
	}

	// // Render Category/Tag Title
	// public function render_category_title( $settings, $class, $term ) {
	// 	$title_pointer = ! crt_fs()->can_use_premium_code() || ! defined('WPR_ADDONS_PRO_VERSION') ? 'none' : $this->get_settings()['title_pointer'];
	// 	$title_pointer_animation = ! crt_fs()->can_use_premium_code() || ! defined('WPR_ADDONS_PRO_VERSION') ? 'fade' : $this->get_settings()['title_pointer_animation'];

	// 	$class .= ' crt-pointer-'. $title_pointer;
	// 	$class .= ' crt-pointer-line-fx crt-pointer-fx-'. $title_pointer_animation;

	// 	echo '<'. esc_attr($settings['element_title_tag']) .' class="'. esc_attr($class) .'">';
	// 		echo '<div class="inner-block">';
	// 			echo '<a href="'. esc_url(get_term_link( $term->term_id )) .'" class="crt-pointer-item">';
	// 				echo esc_attr(wp_trim_words( $term->name, $settings['element_word_count'] ));
	// 			echo '</a>';
	// 		echo '</div>';
	// 	echo '</'. esc_attr($settings['element_title_tag']) .'>';
	// }

	// Render Post Element Separator
	public function render_category_element_separator( $settings, $class ) {
		echo '<div class="'. esc_attr($class .' '. $settings['element_separator_style']) .'">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}

	// Render Post Title
	public function render_category_count( $settings, $class, $term ) {

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				echo '<a href="'. esc_url(get_term_link( $term->term_id )) .'" class="crt-pointer-item">';
					if ( 'yes' === $settings['element_show_brackets'] ) {
						echo '('. $term->count .')';
					} else {
						echo $term->count;
					}
				echo '</a>';

				
				// Text: Before
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';

	}

	// Render Post Excerpt
	public function render_category_description( $settings, $class, $term ) {
		if ( '' === $term->description  ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<p>'. esc_html(wp_trim_words( $term->description, $settings['element_word_count'] )) .'</p>';
			echo '</div>';
		echo '</div>';
	}

	// Get Elements
	public function get_elements( $type, $settings, $class, $term ) {
		if ( 'pro-lk' == $type || 'pro-shr' == $type ) {
			$type = 'title';
		}

		switch ( $type ) {
			case 'title':
				$this->render_category_title( $settings, $class, $term );
				break;

			case 'separator':
				$this->render_category_element_separator( $settings, $class );
				break;

			case 'count':
				$this->render_category_count( $settings, $class, $term );
				break;

			case 'description':
				$this->render_category_description( $settings, $class, $term );
				break;
		}
	}

	// Get Elements by Location
	public function get_elements_by_location( $location, $settings, $term ) {
		$locations = [];

		foreach ( $settings['grid_elements'] as $data ) {
			$place = $data['element_location'];
			$align_vr = $data['element_align_vr'];

			if ( ! isset($locations[$place]) ) {
				$locations[$place] = [];
			}
			
			if ( 'over' === $place ) {
				if ( ! isset($locations[$place][$align_vr]) ) {
					$locations[$place][$align_vr] = [];
				}

				array_push( $locations[$place][$align_vr], $data );
			} else {
				array_push( $locations[$place], $data );
			}
		}

		if ( ! empty( $locations[$location] ) ) {

			if ( 'over' === $location ) {
				foreach ( $locations[$location] as $align => $elements ) {
					if ( 'middle' === $align ) {
						echo '<div class="crt-cv-container"><div class="crt-cv-outer"><div class="crt-cv-inner">';
					}

					echo '<div class="crt-grid-media-hover-'. esc_attr($align) .' elementor-clearfix">';
						foreach ( $elements as $data ) {
							
							// Get Class
							$class  = 'crt-grid-item-'. $data['element_select'];
							$class .= ' elementor-repeater-item-'. $data['_id'];
							$class .= ' crt-grid-item-display-'. $data['element_display'];
							$class .= ' crt-grid-item-align-'. $data['element_align_hr'];
							$class .= $this->get_animation_class( $data, 'element' );

							// Element
							$this->get_elements( $data['element_select'], $data, $class, $term );
						}
					echo '</div>';

					if ( 'middle' === $align ) {
						echo '</div></div></div>';
					}
				}
			} else {
				echo '<div class="crt-grid-item-'. esc_attr($location) .'-content elementor-clearfix">';
					foreach ( $locations[$location] as $data ) {

						// Get Class
						$class  = 'crt-grid-item-'. $data['element_select'];
						$class .= ' elementor-repeater-item-'. $data['_id'];
						$class .= ' crt-grid-item-display-'. $data['element_display'];
						$class .= ' crt-grid-item-align-'. $data['element_align_hr'];

						// Element
						$this->get_elements( $data['element_select'], $data, $class, $term );
					}
				echo '</div>';
			}

		}
	}

	// Render Post Thumbnail
	public function render_category_thumbnail( $settings, $id ) {
		$src = get_term_meta($id, 'thumbnail_id', true);
		$src = Group_Control_Image_Size::get_attachment_image_src( $src, 'layout_image_crop', $settings );
		$alt = '' === wp_get_attachment_caption( $id ) ? get_the_title() : wp_get_attachment_caption( $id );

		if ( $src ) {
			echo '<div class="crt-grid-image-wrap" data-src="'. esc_url( $src ) .'">';
				echo '<img src="'. esc_url( $src ) .'" alt="'. esc_attr( $alt ) .'" class="crt-anim-timing-'. esc_attr($settings[ 'image_effects_animation_timing']) .'">';
			echo '</div>';
		}
	}

	// Render Media Overlay
	public function render_media_overlay( $settings, $term ) {
		echo '<div class="crt-grid-media-hover-bg '. esc_attr($this->get_animation_class( $settings, 'overlay' )) .'" data-url="'. esc_url( get_term_link( $term->term_id ) ) .'">';
            if ( '' !== $settings['overlay_image']['url'] ) {
                echo '<img src="'. esc_url( $settings['overlay_image']['url'] ) .'">';
            }
		echo '</div>';
	}

	// Get Animation Class
	public function get_animation_class( $data, $object ) {
		$class = '';

		// Animation Class
		if ( 'none' !== $data[ $object .'_animation'] ) {
			$class .= ' crt-'. $object .'-'. $data[ $object .'_animation'];
			$class .= ' crt-anim-size-'. $data[ $object .'_animation_size'];
			$class .= ' crt-anim-timing-'. $data[ $object .'_animation_timing'];

			if ( 'yes' === $data[ $object .'_animation_tr'] ) {
				$class .= ' crt-anim-transparency';
			}
		}

		return $class;
	}
	
	public function add_grid_settings( $settings ) {

		$gutter_hr_widescreen = isset($settings['layout_gutter_hr_widescreen']['size']) ? $settings['layout_gutter_hr_widescreen']['size'] : $settings['layout_gutter_hr']['size'];
		$gutter_hr_desktop = $settings['layout_gutter_hr']['size'];
		$gutter_hr_laptop = isset($settings['layout_gutter_hr_laptop']['size']) ? $settings['layout_gutter_hr_laptop']['size'] : $gutter_hr_desktop;
		$gutter_hr_tablet_extra = isset($settings['layout_gutter_hr_tablet_extra']['size']) ? $settings['layout_gutter_hr_tablet_extra']['size'] : $gutter_hr_laptop;
		$gutter_hr_tablet = isset($settings['layout_gutter_hr_tablet']['size']) ? $settings['layout_gutter_hr_tablet']['size'] : $gutter_hr_tablet_extra;
		$gutter_hr_mobile_extra = isset($settings['layout_gutter_hr_mobile_extra']['size']) ? $settings['layout_gutter_hr_mobile_extra']['size'] : $gutter_hr_tablet;
		$gutter_hr_mobile = isset($settings['layout_gutter_hr_mobile']['size']) ? $settings['layout_gutter_hr_mobile']['size'] : $gutter_hr_mobile_extra;

		$gutter_vr_widescreen = isset($settings['layout_gutter_vr_widescreen']['size']) ? $settings['layout_gutter_vr_widescreen']['size'] : $settings['layout_gutter_vr']['size'];
		$gutter_vr_desktop = $settings['layout_gutter_vr']['size'];
		$gutter_vr_laptop = isset($settings['layout_gutter_vr_laptop']['size']) ? $settings['layout_gutter_vr_laptop']['size'] : $gutter_vr_desktop;
		$gutter_vr_tablet_extra = isset($settings['layout_gutter_vr_tablet_extra']['size']) ? $settings['layout_gutter_vr_tablet_extra']['size'] : $gutter_vr_laptop;
		$gutter_vr_tablet = isset($settings['layout_gutter_vr_tablet']['size']) ? $settings['layout_gutter_vr_tablet']['size'] : $gutter_vr_tablet_extra;
		$gutter_vr_mobile_extra = isset($settings['layout_gutter_vr_mobile_extra']['size']) ? $settings['layout_gutter_vr_mobile_extra']['size'] : $gutter_vr_tablet;
		$gutter_vr_mobile = isset($settings['layout_gutter_vr_mobile']['size']) ? $settings['layout_gutter_vr_mobile']['size'] : $gutter_vr_mobile_extra;

		$layout_settings = [
			'layout' => $settings['layout_select'],
			'columns_desktop' => $settings['layout_columns'],
			'gutter_hr' => $gutter_hr_desktop,
			'gutter_hr_mobile' => $gutter_hr_mobile,
			'gutter_hr_mobile_extra' => $gutter_hr_mobile_extra,
			'gutter_hr_tablet' => $gutter_hr_tablet,
			'gutter_hr_tablet_extra' => $gutter_hr_tablet_extra,
			'gutter_hr_laptop' => $gutter_hr_laptop,
			'gutter_hr_widescreen' => $gutter_hr_widescreen,
			'gutter_vr' => $gutter_vr_desktop,
			'gutter_vr_mobile' => $gutter_vr_mobile,
			'gutter_vr_mobile_extra' => $gutter_vr_mobile_extra,
			'gutter_vr_tablet' => $gutter_vr_tablet,
			'gutter_vr_tablet_extra' => $gutter_vr_tablet_extra,
			'gutter_vr_laptop' => $gutter_vr_laptop,
			'gutter_vr_widescreen' => $gutter_vr_widescreen,
			'animation' => $settings['layout_animation'],
			'animation_duration' => $settings['layout_animation_duration'],
			'animation_delay' => $settings['layout_animation_delay']
		];

		if ( 'list' === $settings['layout_select'] ) {
			$layout_settings['media_align'] = $settings['layout_list_align'];
			$layout_settings['media_width'] = $settings['layout_list_media_width']['size'];
			$layout_settings['media_distance'] = $settings['layout_list_media_distance']['size'];
		}

		if (! defined('WPR_ADDONS_PRO_VERSION') ) {
			$settings['lightbox_popup_thumbnails'] = '';
			$settings['lightbox_popup_thumbnails_default'] = '';
			$settings['lightbox_popup_sharing'] = '';
		}

		$this->add_render_attribute( 'grid-settings', [
			'data-settings' => wp_json_encode( $layout_settings ),
		] );
	}

	public function render_grid_html($term, $settings) {

		$term_posts = get_posts([
			'post_type' => 'product',
			'posts_per_page' => -1,
			'tax_query' => [
				[
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $term->term_id,
					'include_children' => false,
				],
			],
		]);

		if ( 'yes' === $settings['query_hide_empty'] && empty($term_posts) ) {
			return;
		}
		
		$post_class = implode( ' ', get_post_class( 'crt-grid-item elementor-clearfix', $term->term_id ) );
		
		// Grid Item
		echo '<article class="'. esc_attr( $post_class ) .'">';
		
		// Inner Wrapper
		echo '<div class="crt-grid-item-inner">';

		// Content: Above Media
		$this->get_elements_by_location( 'above', $settings, $term );

		// Media
		echo '<div class="crt-grid-media-wrap'. esc_attr($this->get_image_effect_class( $settings )) .' " data-overlay-link="'. esc_attr( $settings['overlay_post_link'] ) .'">';
			// Post Thumbnail
			$this->render_category_thumbnail( $settings, $term->term_id );

			// Media Hover
			echo '<div class="crt-grid-media-hover crt-animation-wrap">';
				// Media Overlay
				$this->render_media_overlay( $settings, $term );

				// Content: Over Media
				$this->get_elements_by_location( 'over', $settings, $term );

			echo '</div>';
		echo '</div>';

		// Content: Below Media
		$this->get_elements_by_location( 'below', $settings, $term );

		echo '</div>';  // End .crt-grid-item-inner

		echo '</article>';  // End .crt-grid-item

	}

    protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<h2>'. esc_html__( 'WooCommerce is NOT active!', 'crt-manage' ) .'</h2>';
			return;
		}
		
		$exclude_categories = $settings['query_exclude_cats'];

        // Get Taxonomies
		$terms = get_terms([
			'taxonomy' => 'product_cat',
			'hide_empty' => 'yes' === $settings['query_hide_empty'],
			'exclude' => $exclude_categories
		]);

		// Grid Settings

		$this->add_grid_settings( $settings );
		$render_attribute = $this->get_render_attribute_string( 'grid-settings' );
		
		// Grid Wrap
		echo '<section class="crt-grid elementor-clearfix" '. $render_attribute .'>';

		if ( !empty($terms) ) {

			foreach ($terms as $key => $term) {
				if ( 'yes' === $settings['hide_child_categories'] ) {
					if ( 0 === $term->parent) {
						$this->render_grid_html($term, $settings);
					}
				} else {
					$this->render_grid_html($term, $settings);
				}
			}

		} else {
			echo "<h4>You don't have any products or product categories yet!</h4>";
		}

		echo '</section>';
    }
}