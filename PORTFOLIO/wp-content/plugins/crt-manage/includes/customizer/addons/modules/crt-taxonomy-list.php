<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Image_Size;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Taxonomy_List extends Widget_Base {
	
	public function get_name() {
		return 'crt-taxonomy-list';
	}

	public function get_title() {
		return esc_html__( 'Taxonomy List', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-editor-list-ul';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
	}

	public function get_keywords() {
		return [ 'taxonomy-list', 'taxonomy', 'category', 'categories', 'tag', 'list'];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function add_section_style_toggle_icon() {

		// Tab: Style ==============
		// Section: Toggle Icon --------
		$this->start_controls_section(
			'section_style_toggle_icon',
			[
				'label' => esc_html__( 'Toggle Icon', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_sub_categories_on_click' => 'yes'
				]
			]
		);

		$this->add_control(
			'toggle_icon_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li i.crt-tax-dropdown' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_icon_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'description' => esc_html__('Changing Size may distort distances, click on icon to see actual result', 'crt-manage'),
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
					'{{WRAPPER}} .crt-taxonomy-list li i.crt-tax-dropdown' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'toggle_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li .crt-tax-dropdown' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

	}


	public function get_post_taxonomies() {
		$post_taxonomies = [];
		$post_taxonomies['category'] = esc_html__( 'Categories', 'crt-manage' );
		$post_taxonomies['post_tag'] = esc_html__( 'Tags', 'crt-manage' );
		$post_taxonomies['product_cat'] = esc_html__( 'Product Categories', 'crt-manage' );
		$post_taxonomies['product_tag'] = esc_html__( 'Product Tags', 'crt-manage' );

		$custom_post_taxonomies = Utilities::get_custom_types_of( 'tax', true );
		foreach( $custom_post_taxonomies as $slug => $title ) {
			if ( 'product_tag' === $slug || 'product_cat' === $slug ) {
				continue;
			}
			$post_taxonomies[$slug] = esc_html( $title );
		}

		return $post_taxonomies;
	}

	public function add_controls_group_sub_category_filters() {
		$this->add_control(
			'show_sub_categories',
			[
				'label' => __( 'Show Sub Categories', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => '',
			]
		);

		$this->add_control(
			'show_sub_children',
			[
				'label' => __( 'Show Sub Children', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => '',
			]
		);

		$this->add_control(
			'show_sub_categories_on_click',
			[
				'label' => __( 'Show Children on Click', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => '',
			]
		);
	}

    protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_taxonomy_list_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'query_heading',
			[
				'label' => esc_html__( 'Query', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'query_tax_selection',
			[
				'label' => esc_html__( 'Select Taxonomy', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => $this->get_post_taxonomies(),
			]
		);


		$this->add_control(
			'query_hide_empty',
			[
				'label' => esc_html__( 'Hide Empty', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes'
			]
		);

		$this->add_controls_group_sub_category_filters();

		$this->add_control(
			'layout_heading',
			[
				'label' => esc_html__( 'Layout', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'taxonomy_list_layout',
			[
				'label' => esc_html__( 'Select Layout', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
				'render_type' => 'template',
				'options' => [
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'crt-manage' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'horizontal' => [
						'title' => esc_html__( 'Horizontal', 'crt-manage' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
                'prefix_class' => 'crt-taxonomy-list-',
				'label_block' => false,
			]
		);

		$this->add_control(
			'show_tax_list_icon',
			[
				'label' => esc_html__( 'Show Icon', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'tax_list_icon',
			[
				'label' => esc_html__( 'Select Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'exclude_inline_options' => 'svg',
				'condition' => [
					'show_tax_list_icon' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_tax_count',
			[
				'label' => esc_html__( 'Show Count', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'show_count_brackets',
			[
				'label' => esc_html__( 'Count Brackets', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => 'yes',
				'condition' => [
					'show_tax_count' => 'yes'
				]
			]
		);

		$this->add_control(
			'disable_links',
			[
				'label' => esc_html__( 'Disable Links', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => '',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'open_in_new_page',
			[
				'label' => esc_html__( 'Open in New Page', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => 'yes',
				// 'separator' => 'before',
				'condition' => [
					'disable_links!' => 'yes'
				]
			]
		);

		$this->add_control(
			'highlight_active',
			[
				'label' => esc_html__( 'Highlight Active', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => ''
			]
		);

        $this->end_controls_section();

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles ====================
		// Section: Taxonomy Style ---
		$this->start_controls_section(
			'section_style_tax',
			[
				'label' => esc_html__( 'Taxonomy Style', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tax_style' );

		$this->start_controls_tab(
			'tax_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'tax_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-taxonomy-list li>span' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'tax_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
                'default' => '#00000000',
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-taxonomy-list li>span' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'tax_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-taxonomy-list li>span' => 'border-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'tax_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-taxonomy-list li>span' => 'transition-duration: {{VALUE}}s'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tax_typography',
				'selector' => '{{WRAPPER}} .crt-taxonomy-list li a, {{WRAPPER}} .crt-taxonomy-list li>span',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tax_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'tax_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li a:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .crt-taxonomy-list li>span:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .crt-taxonomy-list li.crt-taxonomy-active a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-taxonomy-list li.crt-taxonomy-active>span' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'tax1_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li a:hover' => 'background-color: {{VALUE}} !important',
					'{{WRAPPER}} .crt-taxonomy-list li>span:hover' => 'background-color: {{VALUE}} !important',
					'{{WRAPPER}} .crt-taxonomy-list li.crt-taxonomy-active a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-taxonomy-list li.crt-taxonomy-active>span' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'tax1_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li a:hover' => 'border-color: {{VALUE}} !important',
					'{{WRAPPER}} .crt-taxonomy-list li>span:hover' => 'border-color: {{VALUE}} !important',
					'{{WRAPPER}} .crt-taxonomy-list li.crt-taxonomy-active a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-taxonomy-list li.crt-taxonomy-active>span' => 'border-color: {{VALUE}}'
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'tax_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-taxonomy-list li>span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tax_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 8,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tax_border_type',
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
					'{{WRAPPER}} .crt-taxonomy-list li a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-taxonomy-list li>span' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 1,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-taxonomy-list li>span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'tax_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'tax_radius',
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
					'{{WRAPPER}} .crt-taxonomy-list li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-taxonomy-list li>span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Icon --------
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_tax_list_icon' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-taxonomy-list li svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-taxonomy-list li i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-taxonomy-list li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-taxonomy-list li i:not(.crt-tax-dropdown)' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-taxonomy-list li svg:not(.crt-tax-dropdown)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		$this->add_section_style_toggle_icon();
    }

	public function get_tax_wrapper_open_tag( $settings, $term_id, $open_in_new_page ) {
		if ( 'yes' == $settings['disable_links'] ) {
			echo '<span>';
		} else {
			echo '<a target="'. $open_in_new_page .'" href="'. esc_url(get_term_link($term_id)) .'">';
		}
	}

	public function get_tax_wrapper_close_tag( $settings ) {
		if ( 'yes' == $settings['disable_links'] ) {
			echo '</span>';
		} else {
			echo '</a>';
		}
	}

    protected function render() {
		// Get Settings
        $settings = $this->get_settings_for_display();

		$open_in_new_page = $settings['open_in_new_page'] ? '_blank' : '_self';

		ob_start();
		\Elementor\Icons_Manager::render_icon( $settings['tax_list_icon'], [ 'aria-hidden' => 'true' ] );
		$icon = ob_get_clean();
		$icon_wrapper = !empty($settings['tax_list_icon']) ? '<span>'. $icon .'</span>' : '';
		$brackets = isset($settings['show_count_brackets']) ? $settings['show_count_brackets'] : '';

		// 	'hide_empty' => 'yes' === $settings['query_hide_empty']
		$settings['query_tax_selection'] = str_contains($settings['query_tax_selection'], 'pro-') ? 'category' : $settings['query_tax_selection'];
		
         echo '<ul class="crt-taxonomy-list" data-show-on-click="'. esc_attr( $settings['show_sub_categories_on_click'] ) .'">';
		$terms = get_terms( $settings['query_tax_selection'], [ 'hide_empty' => 'yes' === $settings['query_hide_empty'], 'parent' => 0, 'child_of' => 0 ] );

        foreach ($terms as $key => $term) {
			if ( !empty(get_queried_object()) && isset(get_queried_object()->term_taxonomy_id) && $term->term_id == get_queried_object()->term_taxonomy_id && 'yes' == $settings['highlight_active'] ) {
				$cat_class = ' class="crt-taxonomy crt-taxonomy-active"';
			} else {
				$cat_class = ' class="crt-taxonomy"';
			}
			$data_parent_term_id = $term->term_id;

			if ( 'yes' === $settings['show_sub_categories'] ) {
				$children = get_terms( $settings['query_tax_selection'], [ 'hide_empty' => 'yes' === $settings['query_hide_empty'], 'parent' => $term->term_id ] );
			} else {
				$children = [];
			}
        	
            echo '<li'. $cat_class . 'data-term-id="'.$data_parent_term_id .'">';
				$toggle_icon = !empty($children) && ('vertical' === $settings['taxonomy_list_layout']) && ('yes' === $settings['show_sub_categories_on_click']) ? '<i class="fas fa-caret-right crt-tax-dropdown" aria-hidden="true"></i>' : '';
				$this->get_tax_wrapper_open_tag( $settings, $term->term_id, $open_in_new_page );
					echo '<span class="crt-tax-wrap">'. $toggle_icon . ' ' . $icon_wrapper .'<span>'. esc_html($term->name) .'</span></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					if ( 'yes' === $brackets ) {
						echo ($settings['show_tax_count']) ? '<span><span class="crt-term-count">&nbsp;('. esc_html($term->count) .')</span></span>' : '';
					} else {
						echo ($settings['show_tax_count']) ? '<span><span class="crt-term-count">&nbsp;'. esc_html($term->count) .'</span></span>' : '';
					}
				$this->get_tax_wrapper_close_tag( $settings );
            echo '</li>';

			foreach ($children as $term) :
				$hidden_class = $settings['show_sub_categories_on_click'] == 'yes' ? ' crt-sub-hidden' : '';
				if ( isset(get_queried_object()->term_taxonomy_id) && $term->term_id == get_queried_object()->term_taxonomy_id && 'yes' == $settings['highlight_active'] ) {
					$sub_class = $term->parent > 0 ? ' class="crt-sub-taxonomy crt-taxonomy-active' . $hidden_class . '"' : '';
				} else {
					$sub_class = $term->parent > 0 ? ' class="crt-sub-taxonomy' . $hidden_class . '"' : '';
				}
				$data_child_term_id = $data_parent_term_id;
				$data_item_id = $term->term_id;

				if ( 'yes' === $settings['show_sub_categories'] && 'yes' === $settings['show_sub_children'] ) {
					$grand_children = get_terms( $settings['query_tax_selection'], [ 'hide_empty' => 'yes' === $settings['query_hide_empty'], 'parent' => $term->term_id ] );
				} else {
					$grand_children = [];
				}
				
				echo '<li'. $sub_class . 'data-term-id="child-'. $data_child_term_id .'" data-id="'. $data_item_id .'">';
				$toggle_icon = !empty($grand_children) && ('vertical' === $settings['taxonomy_list_layout']) && ('yes' === $settings['show_sub_categories_on_click']) ? '<i class="fas fa-caret-right crt-tax-dropdown" aria-hidden="true"></i>' : '';
					$this->get_tax_wrapper_open_tag( $settings, $term->term_id, $open_in_new_page );
						echo '<span class="crt-tax-wrap">'. $toggle_icon . ' ' . $icon_wrapper .'<span>'. esc_html($term->name) .'</span></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						if ( 'yes' === $brackets ) {
							echo ($settings['show_tax_count']) ? '<span><span class="crt-term-count">&nbsp;('. esc_html($term->count) .')</span></span>' : '';
						} else {
							echo ($settings['show_tax_count']) ? '<span><span class="crt-term-count">&nbsp;'. esc_html($term->count) .'</span></span>' : '';
						}
					$this->get_tax_wrapper_close_tag( $settings );
				echo '</li>';
	
				foreach ($grand_children as $term) :
					$hidden_class = $settings['show_sub_categories_on_click'] == 'yes' ? ' crt-sub-hidden' : '';
					if ( !empty(get_queried_object()) && ($term->term_id == get_queried_object()->term_taxonomy_id) && 'yes' == $settings['highlight_active'] ) {
						$sub_class = $term->parent > 0 ? ' class="crt-inner-sub-taxonomy crt-taxonomy-active' . $hidden_class . '"' : '';
					} else {
						$sub_class = $term->parent > 0 ? ' class="crt-inner-sub-taxonomy' . $hidden_class . '"' : '';
					}
					$data_grandchild_term_id = ' data-parent-id="'. $data_item_id .'" data-term-id="grandchild-'. $data_child_term_id .'"';
					$grandchild_id = $term->term_id;

					if ( 'yes' === $settings['show_sub_categories'] && 'yes' === $settings['show_sub_children'] ) {
						$great_grand_children = get_terms( $settings['query_tax_selection'], [ 'hide_empty' => 'yes' === $settings['query_hide_empty'], 'parent' => $term->term_id ] );
					} else {
						$great_grand_children = [];
					}
					
					echo '<li'. $sub_class . $data_grandchild_term_id .' data-id="'. $grandchild_id .'">';
						$this->get_tax_wrapper_open_tag( $settings, $term->term_id, $open_in_new_page );
							echo '<span class="crt-tax-wrap">'. $toggle_icon . ' ' . $icon_wrapper .'<span>'. esc_html($term->name) .'</span></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							if ( 'yes' === $brackets ) {
								echo ($settings['show_tax_count']) ? '<span><span class="crt-term-count">&nbsp;('. esc_html($term->count) .')</span></span>' : '';
							} else {
								echo ($settings['show_tax_count']) ? '<span><span class="crt-term-count">&nbsp;'. esc_html($term->count) .'</span></span>' : '';
							}
						$this->get_tax_wrapper_close_tag( $settings );
					echo '</li>';

					foreach($great_grand_children as $term) :
						if ( $term->term_id == get_queried_object()->term_taxonomy_id && 'yes' == $settings['highlight_active'] ) {
							$sub_class = $term->parent > 0 ? ' class="crt-inner-sub-taxonomy-2 crt-taxonomy-active' . $hidden_class . '"' : '';
						} else {
							$sub_class = $term->parent > 0 ? ' class="crt-inner-sub-taxonomy-2' . $hidden_class . '"' : '';
						}
						$data_great_grandchild_term_id = ' data-parent-id="'. $grandchild_id .'" data-term-id="great-grandchild-'. $data_child_term_id .'"';
					
						echo '<li'. $sub_class . $data_great_grandchild_term_id .'>';
							$this->get_tax_wrapper_open_tag( $settings, $term->term_id, $open_in_new_page );
								echo '<span class="crt-tax-wrap">'. $icon_wrapper .'<span>'. esc_html($term->name) .'</span></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								if ( 'yes' === $brackets ) {
									echo ($settings['show_tax_count']) ? '<span><span class="crt-term-count">&nbsp;('. esc_html($term->count) .')</span></span>' : '';
									$this->get_tax_wrapper_close_tag( $settings );
								} else {
									echo ($settings['show_tax_count']) ? '<span><span class="crt-term-count">&nbsp;'. esc_html($term->count) .'</span></span>' : '';
								}
							$this->get_tax_wrapper_close_tag( $settings );
						echo '</li>';
					
					endforeach;
					
	
				endforeach;
				

			endforeach;
        }

         echo '</ul>';
    }
}