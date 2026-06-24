<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class CRT_Blob_Shapes extends Widget_Base {
    
    public function get_name() {
        return 'crt-blob-shapes';
    }

    public function get_title() {
        return esc_html__( 'Blob Shapes', 'crt-manage' );
    }

    public function get_icon() {
        return 'crt-icon eicon-shape';
    }

    public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

    public function get_keywords() {
        return [ 'blob', 'shape', 'gradient', 'animation' ];
    }

    public function get_script_depends() {
        return [ 'crt-blob-shapes' ];
    }

    public function get_style_depends() {
        return [ 'crt-blob-shapes' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Blob Content', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'blob_shape',
            [
                'label' => esc_html__( 'Shape Variety', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'shape-1',
                'options' => [
                    'shape-1'  => esc_html__( 'Shape 1', 'crt-manage' ),
                    'shape-2'  => esc_html__( 'Shape 2', 'crt-manage' ),
                    'shape-3'  => esc_html__( 'Shape 3', 'crt-manage' ),
                    'shape-4'  => esc_html__( 'Shape 4', 'crt-manage' ),
                    'shape-5'  => esc_html__( 'Shape 5', 'crt-manage' ),
                ],
            ]
        );

        $this->add_control(
            'animated_blob',
            [
                'label' => esc_html__( 'Animated Blob', 'crt-manage' ),
                'description' => esc_html__( 'Continuously morphs the shape of the blob via CSS.', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Yes', 'crt-manage' ),
                'label_off' => esc_html__( 'No', 'crt-manage' ),
                'return_value' => 'yes',
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'animation_speed',
            [
                'label' => esc_html__( 'Animation Speed (s)', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 30,
                        'step' => 0.5,
                    ],
                ],
                'default' => [
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-blob-animated' => 'animation-duration: {{SIZE}}s;',
                ],
                'condition' => [
                    'animated_blob' => 'yes',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'blob_content',
            [
                'label' => esc_html__( 'Content', 'crt-manage' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__( 'I am a blob. Add any content here!', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'enable_link',
            [
                'label' => esc_html__( 'Enable Link', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Yes', 'crt-manage' ),
                'label_off' => esc_html__( 'No', 'crt-manage' ),
                'return_value' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'blob_link',
            [
                'label' => esc_html__( 'URL', 'crt-manage' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'crt-manage' ),
                'default' => [
                    'url' => '',
                ],
                'condition' => [
                    'enable_link' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style_blob',
            [
                'label' => esc_html__( 'Blob Style', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'blob_width',
            [
                'label' => esc_html__( 'Width', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 1000 ],
                    '%' => [ 'min' => 10, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-blob-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'blob_height',
            [
                'label' => esc_html__( 'Height / Aspect Ratio', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vh', 'vw' ],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 1000 ],
                    'vw' => [ 'min' => 10, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-blob-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'blob_background',
                'label' => esc_html__( 'Background (Solid / Gradient / Image)', 'crt-manage' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .crt-blob',
            ]
        );

        $this->add_control(
            'blend_mode',
            [
                'label' => esc_html__( 'Blend Mode Effects', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Normal', 'crt-manage' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-blob-wrapper' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'blob_opacity',
            [
                'label' => esc_html__( 'Opacity', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.1,
                        'step' => 0.05,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-blob' => 'opacity: {{SIZE}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'blob_box_shadow',
                'selector' => '{{WRAPPER}} .crt-blob',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'blob_border',
                'selector' => '{{WRAPPER}} .crt-blob',
            ]
        );

        $this->end_controls_section();
        
        // Content Style Tab
        $this->start_controls_section(
            'section_style_inner_content',
            [
                'label' => esc_html__( 'Content Style', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .crt-blob-inner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__( 'Text Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-blob-inner-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .crt-blob-inner-content',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $shape_class = 'crt-blob-' . esc_attr($settings['blob_shape']);
        $animated_class = ($settings['animated_blob'] === 'yes') ? 'crt-blob-animated' : '';
        
        $link_tag = 'div';
        if ( $settings['enable_link'] === 'yes' && ! empty( $settings['blob_link']['url'] ) ) {
            $link_tag = 'a';
            $this->add_link_attributes( 'blob_link', $settings['blob_link'] );
        }

        ?>
        <div class="crt-blob-wrapper">
            <<?php echo $link_tag; ?> class="crt-blob-container" <?php echo $this->get_render_attribute_string( 'blob_link' ); ?>>
                <div class="crt-blob <?php echo esc_attr($shape_class . ' ' . $animated_class); ?>"></div>
                <div class="crt-blob-inner-content">
                    <?php echo wp_kses_post( $settings['blob_content'] ); ?>
                </div>
            </<?php echo $link_tag; ?>>
        </div>
        <?php
    }
}
