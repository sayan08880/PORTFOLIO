<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use CrtAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Dual_Color_Heading extends Widget_Base {

	public function get_name() {
		return 'crt-dual-color-heading';
	}

	public function get_title() {
		return esc_html__('Dual Color Heading', 'crt-manage');
	}
	public function get_icon() {
		return 'crt-icon eicon-heading';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return ['heading', 'Dual Color Heading'];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com/contact';
    }

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Settings', 'crt-manage'),
			]
		);

		$this->add_control(
			'dual_heading_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'crt-manage' ),
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
					'p' => 'p'
				],
				'default' => 'h2'
			]
		);

		$this->add_control(
			'content_style',
			[
				'label' => esc_html__('Select Layout', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon-top',
				'options' => [
					'default'  => esc_html__('Default', 'crt-manage'),
					'icon-top'  => esc_html__('Icon Top', 'crt-manage'),
					'desc-top'  => esc_html__('Desccription Top', 'crt-manage'),
					'icon-and-desc-top'  => esc_html__('Heading Bottom', 'crt-manage'),
				],
				'prefix_class' => 'crt-dual-heading-',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => __('Alignment', 'crt-manage'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'crt-manage'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'crt-manage'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'crt-manage'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .crt-dual-heading-wrap' => 'text-align: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'primary_heading',
			[
				'label'   => __('Primary Heading', 'crt-manage'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('Dual Color', 'crt-manage'),
				'separator' => 'before',
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'secondary_heading',
			[
				'label'   => __('Secondary Heading', 'crt-manage'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('Heading', 'crt-manage'),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'show_description',
			[
				'label' => __('Show Description', 'crt-manage'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'crt-manage'),
				'label_off' => __('Hide', 'crt-manage'),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'description',
			[
				'label'   => __('', 'crt-manage'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __('Description text or Sub Heading', 'crt-manage'),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_description' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_icon',
			[
				'label' => __('Show Icon', 'crt-manage'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'crt-manage'),
				'label_off' => __('Hide', 'crt-manage'),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'feature_list_icon',
			[
				'label' => esc_html__('Select Icon', 'crt-manage'),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-rocket',
					'library' => 'solid',
				],
				'condition' => [
					'show_icon' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'primary_heading_styles',
			[
				'label' => esc_html__('Primary Heading', 'crt-manage'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'primary_heading_bg_color',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .crt-dual-title .first'
			]
		);

		$this->add_control(
			'primary_heading_color',
			[
				'label' => __('Text Color', 'crt-manage'),
				'type' => Controls_Manager::COLOR,
				'default' => '#7B7B7B',
				'selectors' => [
					'{{WRAPPER}} .crt-dual-title .first' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'primary_heading_border_color',
			[
				'label' => __('Border Color', 'crt-manage'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .crt-dual-title .first' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'primary_heading_typography',
				'label' => __('Typography', 'crt-manage'),
				'selector' => '{{WRAPPER}} .crt-dual-title .first',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '300',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '32',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'primary_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-dual-title .first' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'primary_heading_border_type',
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
					'{{WRAPPER}} .crt-dual-title .first' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'primary_heading_border_width',
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
					'{{WRAPPER}} .crt-dual-title .first' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'primary_heading_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'primary_heading_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-dual-title .first' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'feature_list_title_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-dual-title-wrap'  => 'margin-bottom: {{SIZE}}px;',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'feature_list_title_gutter',
			[
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-dual-title .first'  => 'margin-right: {{SIZE}}px;',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'secondary_heading_styles',
			[
				'label' => esc_html__('Secondary Heading', 'crt-manage'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'secondary_heading_bg_color',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .crt-dual-title .second'
			]
		);

		$this->add_control(
			'secondary_heading_color',
			[
				'label' => __('Text Color', 'crt-manage'),
				'type' => Controls_Manager::COLOR,
				'default' => '#9E5BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-dual-title .second' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'secondary_heading_border_color',
			[
				'label' => __('Border Color', 'crt-manage'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .crt-dual-title .second' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'secondary_heading_typography',
				'label' => __('Typography', 'crt-manage'),
				'selector' => '{{WRAPPER}} .crt-dual-title .second',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '600',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '32',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'secondary_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-dual-title .second' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'secondary_heading_border_type',
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
					'{{WRAPPER}} .crt-dual-title .second' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'secondary_heading_border_width',
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
					'{{WRAPPER}} .crt-dual-title .second' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'secondary_heading_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'secondary_heading_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-dual-title .second' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'general_styles_description',
			[
				'label' => esc_html__('Description', 'crt-manage'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_description' => 'yes'
				]
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __('Color', 'crt-manage'),
				'type' => Controls_Manager::COLOR,
				'default' => '#989898',
				'selectors' => [
					'{{WRAPPER}} .crt-dual-heading-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __('Typography', 'crt-manage'),
				'selector' => '{{WRAPPER}} .crt-dual-heading-description',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_family' => [
						'default' => 'Roboto',
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
			'feature_list_description_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-dual-heading-description'  => 'margin-bottom: {{SIZE}}px;',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'general_styles_icon',
			[
				'label' => esc_html__('Icon', 'crt-manage'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_icon' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __('Color', 'crt-manage'),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-dual-heading-icon-wrap' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-dual-heading-icon-wrap svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__('Size', 'crt-manage'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 35,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-dual-heading-icon-wrap' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-dual-heading-icon-wrap svg' => 'width: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'feature_list_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-dual-heading-icon-wrap'  => 'margin-bottom: {{SIZE}}px;',
				]
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes('title', 'none');
		$this->add_inline_editing_attributes('description', 'basic');
		$this->add_inline_editing_attributes('content', 'advanced');
		
		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];

		$dual_heading_tag = $settings['dual_heading_tag'];

		if ( !in_array( $dual_heading_tag, $tags_whitelist ) ) {
			$dual_heading_tag = 'h2';
		}

        ?>
			<div class="crt-dual-heading-wrap">
				<div class="crt-dual-title-wrap">
					<<?php echo esc_attr($dual_heading_tag); ?> class="crt-dual-title">
					<?php if (!empty($settings['primary_heading'])) : ?>
						<span class="first"><?php echo esc_html($settings['primary_heading']); ?></span>
					<?php endif; ?>
					
					<?php if (!empty($settings['secondary_heading'])) : ?>
						<span class="second"><?php echo esc_html($settings['secondary_heading']); ?></span>
					<?php endif; ?>
					</<?php echo esc_attr($dual_heading_tag); ?>>
				</div>
				
				<?php if ('yes' == $settings['show_description']) { ?>
					<div class="crt-dual-heading-description" <?php echo $this->get_render_attribute_string('description'); ?>><?php echo esc_html($settings['description']); ?></div>
				<?php } ?>

				<?php if ('yes' == $settings['show_icon']) { ?>
					<div class="crt-dual-heading-icon-wrap">
						<?php \Elementor\Icons_Manager::render_icon($settings['feature_list_icon'], ['aria-hidden' => 'true']); ?>
					</div>
				<?php } ?>

			</div>
		<?php
	}
}
