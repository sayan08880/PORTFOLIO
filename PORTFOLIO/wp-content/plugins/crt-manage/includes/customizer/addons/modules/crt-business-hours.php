<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Business_Hours extends Widget_Base {
		
	public function get_name() {
		return 'crt-business-hours';
	}

	public function get_title() {
		return esc_html__( 'Business Hours', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-clock-o';
	}

	public function get_categories() {
        return [ 'crt_manage_theme'];
    }

	public function get_keywords() {
		return [ 'business hours', 'opening Hours', 'opening times', 'currently Open' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com/contact';
    }

    public function add_repeater_args_icon() {
        return [
            'label' => esc_html__( 'Select Icon', 'crt-manage' ),
            'type' => Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
        ];
    }

    public function add_repeater_args_highlight() {
        return [
            'label' => esc_html__( 'Highlight', 'crt-manage' ),
            'type' => Controls_Manager::SWITCHER,
        ];
    }

    public function add_repeater_args_highlight_color() {
        return [
            'label' => esc_html__( 'Text Color', 'crt-manage' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} {{CURRENT_ITEM}}.crt-business-hours-item .crt-business-day' => 'color: {{VALUE}}!important;',
                '{{WRAPPER}} {{CURRENT_ITEM}}.crt-business-hours-item .crt-business-time' => 'color: {{VALUE}}!important;',
                '{{WRAPPER}} {{CURRENT_ITEM}}.crt-business-hours-item .crt-business-closed' => 'color: {{VALUE}}!important;',
            ],
            'condition' => [
                'highlight' => 'yes',
            ],
        ];
    }

    public function add_repeater_args_highlight_bg_color() {
        return [
            'label' => esc_html__( 'Background Color', 'crt-manage' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#61ce70',
            'selectors' => [
                '{{WRAPPER}} {{CURRENT_ITEM}}.crt-business-hours-item' => 'background-color: {{VALUE}}!important;',
            ],
            'condition' => [
                'highlight' => 'yes',
            ],
        ];
    }

    public function add_control_general_even_bg() {
        $this->add_control(
            'general_even_bg',
            [
                'label' => esc_html__( 'Enable Even Color', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
    }


    public function add_control_general_even_bg_color() {
        $this->add_control(
            'general_even_bg_color',
            [
                'label' => esc_html__( 'Even Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#F9F9F9',
                'selectors' => [
                    '{{WRAPPER}} .crt-business-hours-item:nth-of-type(even)' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'general_even_bg' => 'yes',
                ],
            ]
        );
    }

    public function add_control_general_icon_color() {
        $this->add_control(
            'general_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .crt-business-day i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-business-day svg' => 'fill: {{VALUE}}'
                ],
            ]
        );
    }

    public function add_control_general_hover_icon_color() {
        $this->add_control(
            'general_hover_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-business-hours .crt-business-hours-item:hover .crt-business-day i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .crt-business-hours .crt-business-hours-item:hover .crt-business-day svg' => 'fill: {{VALUE}}',
                ],
            ]
        );
    }

    public function add_control_general_icon_size() {
        $this->add_control(
            'general_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-business-day i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-business-day svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
                ],
                'separator' => 'before'
            ]
        );
    }

	protected function register_controls() {
		
		// Section: Business Hours ---
		$this->start_controls_section(
			'crt__section_business_hours_items',
			[
				'label' => esc_html__( 'Business Hours', 'crt-manage' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_business_hours_item' );

		$repeater->add_control(
			'day',
			[
				'label' => esc_html__( 'Day', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Monday',
			]
		);

		$repeater->add_control( 'icon', $this->add_repeater_args_icon() );

		$repeater->add_control(
			'time',
			[
				'label' => esc_html__( 'Time', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '08:00 AM - 05:00 PM',
				'separator' => 'before'
			]
		);

		$repeater->add_control( 'highlight', $this->add_repeater_args_highlight() );

		$repeater->add_control( 'highlight_color', $this->add_repeater_args_highlight_color() );

		$repeater->add_control( 'highlight_bg_color', $this->add_repeater_args_highlight_bg_color() );

		$repeater->add_control(
			'closed',
			[
				'label' => esc_html__( 'Closed', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'closed_text',
			[
				'label' => esc_html__( 'Closed Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Closed',
				'condition' => [
					'closed' => 'yes',
				],
			]
		);

		$this->add_control(
			'hours_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'day' => 'Monday',
					],
					[
						'day' => 'Tuesday',
					],
					[
						'day' => 'Wednesday',
					],
					[
						'day' => 'Thursday',
					],
					[
						'day' => 'Friday',
					],
					[
						'day' => 'Saturday',
						'time' => '08:00 AM - 01:00 PM',
					],
					[
						'day' => 'Sunday',
						'closed' => 'yes',
					],
				],
				'title_field' => '{{{ day }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		
		// Styles
		// Section: General ----------
		$this->start_controls_section(
			'crt__section_style_general',
			[
				'label' => esc_html__( 'General', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_general_colors' );

		$this->start_controls_tab(
			'tab_general_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'general_day_color',
			[
				'label' => esc_html__( 'Day Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-business-day' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control_general_icon_color();

		$this->add_control(
			'general_time_color',
			[
				'label' => esc_html__( 'Time Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-business-time' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'general_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-business-hours-item' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-business-hours' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_general_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'general_hover_day_color',
			[
				'label' => esc_html__( 'Day Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-business-hours .crt-business-hours-item:not(.crt-business-hours-item-closed):hover .crt-business-day' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control_general_hover_icon_color();

		$this->add_control(
			'general_hover_time_color',
			[
				'label' => esc_html__( 'Time Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-business-hours .crt-business-hours-item:not(.crt-business-hours-item-closed):hover .crt-business-time' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'general_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f7f7f7',
				'selectors' => [
					'{{WRAPPER}} .crt-business-hours .crt-business-hours-item:not(.crt-business-hours-item-closed):hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_general_even_bg();

		$this->add_control_general_even_bg_color();

		$this->add_control(
			'general_closed_section',
			[
				'label' => esc_html__( 'Closed', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_closed_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-business-hours-item-closed' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'general_closed_day_color',
			[
				'label' => esc_html__( 'Day Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-business-hours-item-closed .crt-business-day' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'general_closed_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-business-hours-item-closed .crt-business-closed' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'general_day_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Day Typography', 'crt-manage' ),
				'name' => 'general_day_typography',
				'selector' => '{{WRAPPER}} .crt-business-day',
			]
		);

		$this->add_control_general_icon_size();

		$this->add_control(
			'general_time_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Time Typography', 'crt-manage' ),
				'name' => 'general_time_typography',
				'selector' => '{{WRAPPER}} .crt-business-time,{{WRAPPER}} .crt-business-closed',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_divider',
			[
				'label' => esc_html__( 'Divider', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_divider_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-business-hours-item:after' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'general_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'general_divider_type',
			[
				'label' => esc_html__( 'Style', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-business-hours-item:after' => 'border-bottom-style: {{VALUE}};',
				],
				'condition' => [
					'general_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'general_divider_weight',
			[
				'label' => esc_html__( 'Weight', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-business-hours-item:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'general_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'general_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-business-hours-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .crt-business-hours' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'general_border',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .crt-business-hours',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-business-hours' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		$item_count = 0;

		?>

		<div class="crt-business-hours">

			<?php

			foreach ( $settings['hours_items'] as $item ) : 

				if (   '' !== $item['day'] || '' !== $item['time'] ) : 

				$this->add_render_attribute( 'hours_item_attribute'. $item_count, 'class', 'crt-business-hours-item elementor-repeater-item-'.esc_attr( $item['_id'] ) );

				if ( 'yes' === $item['closed'] ) {
					$this->add_render_attribute( 'hours_item_attribute'. $item_count, 'class', 'crt-business-hours-item-closed' );
				}

				?>
				
				<div <?php echo $this->get_render_attribute_string( 'hours_item_attribute'. $item_count ); ?>>

					<?php if ( '' !== $item['day'] ) : ?>	
					<span class="crt-business-day">
						<?php echo '' !== $item['icon']['value'] ? '<i class="'. esc_attr($item['icon']['value']) .'"></i>' : ''; ?>
						<?php echo esc_html($item['day']); ?>
					</span>
					<?php endif; ?>

					<?php if ( 'yes' === $item['closed'] ) : ?>	
					<span class="crt-business-closed"><?php echo esc_html($item['closed_text']); ?></span>
					<?php elseif ( '' !== $item['time'] ) : ?>	
					<span class="crt-business-time"><?php echo esc_html($item['time']); ?></span>
					<?php endif; ?>

				</div>

				<?php

				endif;

				$item_count++;

			endforeach;

			?>

		</div>
		<?php
	}
}