<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Filter_Taxonomy extends Widget_Base {

	public function get_name() {
		return 'crt-filter-taxonomy';
	}

	public function get_title() {
		return esc_html__( 'Taxonomy Filter', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-filter';
	}

	public function get_categories() {
		return [ 'crt_manage_woocommerce' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'filter', 'category', 'tag', 'taxonomy', 'product' ];
	}

    public function get_style_depends() {
        return [ 'crt-filter-attributes' ];
    }

	protected function register_controls() {

		$this->start_controls_section(
			'section_taxonomy_filter',
			[
				'label' => esc_html__( 'Taxonomy Filter', 'crt-manage' ),
			]
		);

		$this->add_control(
			'filter_label',
			[
				'label' => esc_html__( 'Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Filter by', 'crt-manage' ),
			]
		);

		$this->add_control(
			'taxonomy',
			[
				'label' => esc_html__( 'Select Taxonomy', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'product_cat',
				'options' => [
					'product_cat' => esc_html__( 'Product Categories', 'crt-manage' ),
					'product_tag' => esc_html__( 'Product Tags', 'crt-manage' ),
				],
			]
		);
        
        $this->add_control(
			'query_type',
			[
				'label' => esc_html__( 'Query Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'AND',
				'options' => [
					'AND' => esc_html__( 'AND', 'crt-manage' ),
					'OR' => esc_html__( 'OR', 'crt-manage' ),
				],
                'description' => esc_html__('Determine how multiple selections are handled (if supported)', 'crt-manage'),
			]
		);

        $this->add_control(
            'tax_show_reset_button',
            [
                'label' => __( 'Show Reset Button', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'tax_show_reset_button_text',
            [
                'label' => esc_html__( 'Button Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Reset', 'crt-manage' ),
                'condition' => [
                    'tax_show_reset_button' => 'yes',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_taxonomy_filter',
			[
				'label' => esc_html__( 'Style', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
            'filter_tax_heading_style',
            [
                'label' => esc_html__( 'Heading', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'filter_tax_heading_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-taxonomy-widget .crt-filter-taxonomy-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'filter_tax_heading_typo',
                'selector' => '{{WRAPPER}} .crt-filter-taxonomy-widget .crt-filter-taxonomy-label',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '16',
                            'unit' => 'px',
                        ],
                    ],
                    'letter_spacing' => [
                        'default' => [
                            'size' => '1.1'
                        ]
                    ],
                ]
            ]
        );

        $this->add_control(
            'filter_tax_heading_color',
            [
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-taxonomy-widget .crt-filter-taxonomy-label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'filter_tax_heading_background_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-taxonomy-widget .crt-filter-taxonomy-label' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'filter_tax_heading_border_type',
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
                    '{{WRAPPER}} .crt-filter-taxonomy-widget .crt-filter-taxonomy-label' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filter_tax_heading_border_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e8e8e8',
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-taxonomy-widget .crt-filter-taxonomy-label' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'filter_tax_heading_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'filter_tax_heading_border_width',
            [
                'label' => esc_html__( 'Border Width', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-taxonomy-widget .crt-filter-taxonomy-label' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'filter_tax_heading_border_type!' => 'none',
                ],
            ]
        );


        $this->add_control(
            'filter_tax_list_style',
            [
                'label' => esc_html__( 'List', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'filter_tax_typo',
                'selector' => '{{WRAPPER}} .crt-filter-taxonomy-list a',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '16',
                            'unit' => 'px',
                        ],
                    ],
                    'letter_spacing' => [
                        'default' => [
                            'size' => '1.1'
                        ]
                    ],
                ]
            ]
        );

        $this->add_control(
            'filter_tax_color',
            [
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#666',
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-taxonomy-list a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'filter_tax_border',
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
                    '{{WRAPPER}} .crt-filter-taxonomy-list li a:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'filter_tax_color_active',
            [
                'label' => esc_html__( 'Active Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#666',
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-taxonomy-list li.active a:before' => 'background-color: {{VALUE}};border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_tax_icon_height',
            [
                'label' => esc_html__( 'Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 25,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-taxonomy-list li a:before' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'filter_tax_icon_width',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 25,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-taxonomy-list li a:before' => 'width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'filter_tax_button',
            [
                'label' => esc_html__( 'Button Reset', 'crt-manage' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'filter_tax_button_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 10,
                    'right' => 5,
                    'bottom' => 10,
                    'left' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-reset-wrapper button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filter_attr_button_color',
            [
                'label' => esc_html__( 'Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#666',
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-reset-wrapper button' => 'color: {{VALUE}};border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'filter_tax_button_typo',
                'selector' => '{{WRAPPER}} .crt-filter-reset-wrapper button',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '13',
                            'unit' => 'px',
                        ],
                    ],
                    'letter_spacing' => [
                        'default' => [
                            'size' => '1.1'
                        ]
                    ],
                ]
            ]
        );

        $this->add_control(
            'filter_tax_button_background_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#666',
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-reset-wrapper button' => 'background-color: {{VALUE}};border-color: {{VALUE}}',
                ],
            ]
        );



        $this->add_control(
            'filter_tax_button_border_type',
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
                    '{{WRAPPER}} .crt-filter-reset-wrapper button' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filter_tax_button_border_color',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e8e8e8',
                'selectors' => [
                    '{{WRAPPER}} .crt-filter-reset-wrapper button' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'filter_tax_button_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'filter_tax_button_border_radius',
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
                    '{{WRAPPER}} .crt-filter-reset-wrapper button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
        $taxonomy = $settings['taxonomy'];
        
        $terms = get_terms( [
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ] );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return;
        }

        // Get current selected terms from URL
        // Parameter format is [taxonomy_slug]
        $param_key = '' . $taxonomy;
        $current_values = isset($_GET[$param_key]) ? explode(',', $_GET[$param_key]) : [];
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		?>
		<div class="crt-filter-taxonomy-widget">
			<?php if ( ! empty( $settings['filter_label'] ) ) : ?>
				<h4 class="crt-filter-taxonomy-label"><?php echo esc_html( $settings['filter_label'] ); ?></h4>
			<?php endif; ?>

			<ul class="crt-filter-taxonomy-list">
                <?php foreach ( $terms as $term ) : 
                    $is_active = in_array( $term->slug, $current_values );
                    $class = $is_active ? 'active' : '';
                    
                    // Build URL
                    // Logic: Toggle term in current_values
                    $new_values = $current_values;
                    if ( $is_active ) {
                        $new_values = array_diff( $new_values, [$term->slug] );
                    } else {
                        $new_values[] = $term->slug;
                    }
                    
                    // Reconstruct URL
                    // Note: This is a simple PHP implementation. Ideal implementation might use JS to preserve other params.
                    // For now, let's generate a link that uses JS to update parameter.
                    ?>
                    <li class="crt-filter-item <?php echo esc_attr($class); ?>">
                        <a href="#" data-term-slug="<?php echo esc_attr($term->slug); ?>" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
                            <?php echo esc_html( $term->name ); ?> 
                            <span class="count">(<?php echo esc_html( $term->count ); ?>)</span>
                        </a>
                    </li>
                <?php endforeach; ?>
			</ul>

            <?php if ( 'yes' === $settings['tax_show_reset_button'] && ! empty($current_values) || $is_editor ) : ?>
                <div class="crt-filter-reset-wrapper">
                    <button type="button" class="crt-filter-reset-btn" data-taxonomy="<?php echo esc_attr($taxonomy); ?>"><?php echo $settings['tax_show_reset_button_text'] ? $settings['tax_show_reset_button_text']:'Reset'; ?></button>
                </div>
            <?php endif; ?>

            <script>
            jQuery(document).ready(function($) {
                $('.crt-filter-taxonomy-widget a').on('click', function(e) {
                    e.preventDefault();
                    var termSlug = $(this).data('term-slug');
                    var tax = $(this).data('taxonomy');
                    // param key matches the one in render:  + taxonomy
                    var paramKey = '' + tax; 
                    
                    var url = new URL(window.location.href);
                    var currentVal = url.searchParams.get(paramKey);
                    var values = currentVal ? currentVal.split(',') : [];
                    
                    // Toggle value
                    var index = values.indexOf(String(termSlug));
                    if (index > -1) {
                        values.splice(index, 1);
                    } else {
                        values.push(String(termSlug));
                    }
                    
                    if (values.length > 0) {
                        url.searchParams.set(paramKey, values.join(','));
                    } else {
                        url.searchParams.delete(paramKey);
                    }
                    
                    // Handle Relation (and/or) if needed, currently simplistic
                     url.searchParams.delete('paged'); 
                    window.location.href = url.toString();
                    url.searchParams.delete('paged'); 
                    window.location.href = url.toString();
                });

                $('.crt-filter-taxonomy-widget .crt-filter-reset-btn').on('click', function(e) {
                    e.preventDefault();
                    var tax = $(this).data('taxonomy');
                    // param key matches the one in render:  + taxonomy
                    var paramKey = '' + tax; 
                    
                    var url = new URL(window.location.href);
                    url.searchParams.delete(paramKey);
                    url.searchParams.delete('paged'); 
                    window.location.href = url.toString();
                });
            });
            </script>
		</div>
		<?php
	}
}
