<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class CRT_Background_Switcher extends Widget_Base {
    
    public function get_name() {
        return 'crt-background-switcher';
    }

    public function get_title() {
        return esc_html__( 'Background Switcher', 'crt-manage' );
    }

    public function get_icon() {
        return 'crt-icon eicon-photo-library';
    }

    public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

    public function get_keywords() {
        return [ 'background', 'switcher', 'hover', 'slider', 'slip' ];
    }

    public function get_script_depends() {
        return [ 'crt-manage-lib-gsap', 'crt-background-switcher' ];
    }

    public function get_style_depends() {
        return [ 'crt-background-switcher' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Switcher Items', 'crt-manage' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_title',
            [
                'label' => esc_html__( 'Title', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Hover Me', 'crt-manage' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'item_subtitle',
            [
                'label' => esc_html__( 'Subtitle', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Discover more', 'crt-manage' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'item_description',
            [
                'label' => esc_html__( 'Description', 'crt-manage' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Enter a brief description here.', 'crt-manage' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'item_image',
            [
                'label' => esc_html__( 'Background Image', 'crt-manage' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        
        $repeater->add_control(
            'item_link',
            [
                'label' => esc_html__( 'Link', 'crt-manage' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'crt-manage' ),
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__( 'Items', 'crt-manage' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item_title' => esc_html__( 'First Item', 'crt-manage' ),
                    ],
                    [
                        'item_title' => esc_html__( 'Second Item', 'crt-manage' ),
                    ],
                    [
                        'item_title' => esc_html__( 'Third Item', 'crt-manage' ),
                    ],
                ],
                'title_field' => '{{{ item_title }}}',
            ]
        );

        $this->add_control(
            'description_animation',
            [
                'label' => esc_html__( 'Description Hover Reveal', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'  => esc_html__( 'Always Visible', 'crt-manage' ),
                    'fade'  => esc_html__( 'Fade Reveal', 'crt-manage' ),
                    'slide' => esc_html__( 'Slide Reveal', 'crt-manage' ),
                ],
            ]
        );

        $this->add_control(
            'effect',
            [
                'label' => esc_html__( 'Transition Effect', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'slip',
                'options' => [
                    'fade'  => esc_html__( 'Fade', 'crt-manage' ),
                    'slip'  => esc_html__( 'Slip (Slide Up)', 'crt-manage' ),
                    'zoom'  => esc_html__( 'Zoom In', 'crt-manage' ),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'transition_speed',
            [
                'label' => esc_html__( 'Transition Speed (ms)', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 2000,
                        'step' => 50,
                    ],
                ],
                'default' => [
                    'size' => 600,
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style_layout',
            [
                'label' => esc_html__( 'Layout & Wrapper', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'layout_direction',
            [
                'label' => esc_html__( 'Layout Direction', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html__( 'Horizontal', 'crt-manage' ),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                    'column' => [
                        'title' => esc_html__( 'Vertical', 'crt-manage' ),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                ],
                'default' => 'row',
                'prefix_class' => 'crt-bgs-layout-',
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-nav' => 'flex-direction: {{VALUE}};',
                    '{{WRAPPER}}.crt-bgs-layout-column .crt-bgs-nav .crt-bgs-nav-item' => 'width: 100%',
                    '{{WRAPPER}}.crt-bgs-layout-row .crt-bgs-nav .crt-bgs-nav-item:last-child' => 'border-right: none;',
                    '{{WRAPPER}}.crt-bgs-layout-column .crt-bgs-nav .crt-bgs-nav-item:last-child' => 'border-bottom: none;',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'nav_alignment',
            [
                'label' => esc_html__( 'Main Axis Alignment', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Start', 'crt-manage' ),
                        'icon' => 'eicon-align-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'crt-manage' ),
                        'icon' => 'eicon-align-center-h',
                    ],
                    'right' => [
                        'title' => esc_html__( 'End', 'crt-manage' ),
                        'icon' => 'eicon-align-end-h',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-nav' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_cross_alignment',
            [
                'label' => esc_html__( 'Cross Axis Alignment (Equal Height)', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'crt-manage' ),
                        'icon' => 'eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'crt-manage' ),
                        'icon' => 'eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'crt-manage' ),
                        'icon' => 'eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch / Equal', 'crt-manage' ),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                ],
                'default' => 'stretch',
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-nav' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}}.crt-bgs-layout-row .crt-bgs-nav .crt-bgs-nav-item' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}}.crt-bgs-layout-column .crt-bgs-nav .crt-bgs-nav-item' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_height',
            [
                'label' => esc_html__( 'Min Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [ 'min' => 200, 'max' => 1200 ],
                    'vh' => [ 'min' => 10, 'max' => 100 ],
                ],
                'default' => [
                    'size' => 600,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-container' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'overlay_color',
            [
                'label' => esc_html__( 'Background Overlay Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.5)',
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-overlay' => 'background-color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // Nav Item Style
        $this->start_controls_section(
            'section_style_nav',
            [
                'label' => esc_html__( 'Navigation Items', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-nav-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_nav_style' );

        $this->start_controls_tab(
            'tab_nav_normal',
            [
                'label' => esc_html__( 'Normal', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'nav_item_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-nav-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'subtitle_color',
            [
                'label' => esc_html__( 'Subtitle Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-description' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'nav_item_border',
                'selector' => '{{WRAPPER}} .crt-bgs-nav-item',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_nav_hover',
            [
                'label' => esc_html__( 'Hover / Active', 'crt-manage' ),
            ]
        );

        $this->add_control(
            'nav_item_bg_color_active',
            [
                'label' => esc_html__( 'Background Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-nav-item.active' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .crt-bgs-nav-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color_active',
            [
                'label' => esc_html__( 'Title Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-nav-item.active .crt-bgs-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .crt-bgs-nav-item:hover .crt-bgs-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'subtitle_color_active',
            [
                'label' => esc_html__( 'Subtitle Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-nav-item.active .crt-bgs-subtitle' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .crt-bgs-nav-item:hover .crt-bgs-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_color_active',
            [
                'label' => esc_html__( 'Description Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-nav-item.active .crt-bgs-description' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .crt-bgs-nav-item:hover .crt-bgs-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_item_border_active',
            [
                'label' => esc_html__( 'Border Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-bgs-nav-item.active' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .crt-bgs-nav-item:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Title Typography', 'crt-manage' ),
                'selector' => '{{WRAPPER}} .crt-bgs-title',
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'label' => esc_html__( 'Subtitle Typography', 'crt-manage' ),
                'selector' => '{{WRAPPER}} .crt-bgs-subtitle',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__( 'Description Typography', 'crt-manage' ),
                'selector' => '{{WRAPPER}} .crt-bgs-description',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if ( empty( $settings['items'] ) ) {
            return;
        }

        $effect = $settings['effect'] ? $settings['effect'] : 'slip';
        $desc_anim = $settings['description_animation'] ? $settings['description_animation'] : 'none';
        $speed = $settings['transition_speed']['size'] ? $settings['transition_speed']['size'] : 600;

        ?>
        <div class="crt-bgs-container" data-effect="<?php echo esc_attr( $effect ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>">
            
            <div class="crt-bgs-backgrounds">
                <?php foreach ( $settings['items'] as $index => $item ) : 
                    $active_class = ($index === 0) ? 'active' : '';
                    $bg_image_url = !empty($item['item_image']['url']) ? $item['item_image']['url'] : '';
                    ?>
                    <div class="crt-bgs-bg-item <?php echo esc_attr($active_class); ?>" style="background-image: url('<?php echo esc_url($bg_image_url); ?>');"></div>
                <?php endforeach; ?>
                <div class="crt-bgs-overlay"></div>
            </div>

            <div class="crt-bgs-nav">
                <?php foreach ( $settings['items'] as $index => $item ) : 
                    $active_class = ($index === 0) ? 'active' : '';
                    
                    $link_key = 'link_' . $index;
                    $item_tag = 'div';
                    if ( ! empty( $item['item_link']['url'] ) ) {
                        $item_tag = 'a';
                        $this->add_link_attributes( $link_key, $item['item_link'] );
                    }
                    ?>
                    <<?php echo $item_tag; ?> class="crt-bgs-nav-item desc-anim-<?php echo esc_attr($desc_anim); ?> <?php echo esc_attr($active_class); ?> elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>" data-index="<?php echo esc_attr($index); ?>" <?php echo $this->get_render_attribute_string( $link_key ); ?>>
                        
                        <?php if ( ! empty( $item['item_title'] ) ) : ?>
                            <h3 class="crt-bgs-title"><?php echo wp_kses_post( $item['item_title'] ); ?></h3>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $item['item_subtitle'] ) ) : ?>
                            <p class="crt-bgs-subtitle"><?php echo wp_kses_post( $item['item_subtitle'] ); ?></p>
                        <?php endif; ?>

                        <?php if ( ! empty( $item['item_description'] ) ) : ?>
                            <div class="crt-bgs-description-wrapper">
                                <div class="crt-bgs-description"><?php echo wp_kses_post( $item['item_description'] ); ?></div>
                            </div>
                        <?php endif; ?>

                    </<?php echo $item_tag; ?>>
                <?php endforeach; ?>
            </div>

        </div>
        <?php
    }
}
