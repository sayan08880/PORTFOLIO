<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class CRT_Scroll_Marquee extends Widget_Base {
    
    public function get_name() {
        return 'crt-scroll-marquee';
    }

    public function get_title() {
        return esc_html__( 'Scroll Marquee', 'crt-manage' );
    }

    public function get_icon() {
        return 'crt-icon eicon-scroll';
    }

    public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

    public function get_keywords() {
        return [ 'scroll', 'marquee', 'gsap', 'slider', 'ticker' ];
    }

    public function get_script_depends() {
        return [ 'crt-manage-lib-gsap', 'crt-scroll-marquee' ];
    }

    public function get_style_depends() {
        return [ 'crt-scroll-marquee' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'crt-manage' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_text',
            [
                'label' => esc_html__( 'Text', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Marquee Text', 'crt-manage' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'item_image',
            [
                'label' => esc_html__( 'Image', 'crt-manage' ),
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
                'label' => esc_html__( 'Marquee Items', 'crt-manage' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item_text' => esc_html__( 'Marquee Item 1', 'crt-manage' ),
                    ],
                    [
                        'item_text' => esc_html__( 'Marquee Item 2', 'crt-manage' ),
                    ],
                    [
                        'item_text' => esc_html__( 'Marquee Item 3', 'crt-manage' ),
                    ],
                ],
                'title_field' => '{{{ item_text }}}',
            ]
        );
        
        $this->add_control(
            'pause_on_hover',
            [
                'label' => esc_html__( 'Pause on Hover', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => esc_html__( 'Yes', 'crt-manage' ),
                'label_off' => esc_html__( 'No', 'crt-manage' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'scroll_effect',
            [
                'label' => esc_html__( 'Scroll Effect (GSAP)', 'crt-manage' ),
                'description' => esc_html__( 'Changes speed and direction when scrolling up or down.', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => esc_html__( 'Yes', 'crt-manage' ),
                'label_off' => esc_html__( 'No', 'crt-manage' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'direction',
            [
                'label' => esc_html__( 'Direction', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left'  => esc_html__( 'Left', 'crt-manage' ),
                    'right' => esc_html__( 'Right', 'crt-manage' ),
                ],
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__( 'Speed', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 50,
                ],
            ]
        );
        
        $this->add_control(
            'separator_icon',
            [
                'label' => esc_html__( 'Separator Icon', 'crt-manage' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__( 'Marquee Style', 'crt-manage' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => esc_html__( 'Background', 'crt-manage' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .crt-marquee-container',
            ]
        );
        
        $this->add_responsive_control(
            'gap',
            [
                'label' => esc_html__( 'Gap Between Items', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%', 'rem', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-marquee-track' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__( 'Height', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'em', 'rem' ],
                'default' => [
                    'size' => 200,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-marquee-item-image img' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'marquee_padding',
            [
                'label' => esc_html__( 'Padding', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .crt-marquee-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => esc_html__( 'Text Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-marquee-item-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .crt-marquee-item-text',
            ]
        );
        
        $this->add_control(
            'separator_color',
            [
                'label' => esc_html__( 'Separator Color', 'crt-manage' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crt-marquee-separator' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .crt-marquee-separator svg' => 'fill: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'separator_size',
            [
                'label' => esc_html__( 'Separator Size', 'crt-manage' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .crt-marquee-separator' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .crt-marquee-separator svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image_size',
                'default' => 'thumbnail',
                'separator' => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Image Border Radius', 'crt-manage' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .crt-marquee-item-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if ( empty( $settings['items'] ) ) {
            return;
        }

        $speed = $settings['speed']['size'] ? $settings['speed']['size'] : 50;
        $direction = $settings['direction'] ? $settings['direction'] : 'left';
        $pause_on_hover = $settings['pause_on_hover'] === 'yes' ? 'true' : 'false';
        $scroll_effect = $settings['scroll_effect'] === 'yes' ? 'true' : 'false';

        ?>
        <div class="crt-marquee-container" 
             data-speed="<?php echo esc_attr( $speed ); ?>" 
             data-direction="<?php echo esc_attr( $direction ); ?>"
             data-scroll-effect="<?php echo esc_attr( $scroll_effect ); ?>"
             data-pause-on-hover="<?php echo esc_attr( $pause_on_hover ); ?>">
            <div class="crt-marquee-track">
                <?php foreach ( $settings['items'] as $index => $item ) : 
                    
                    $link_key = 'link_' . $index;
                    $item_tag = 'div';
                    if ( ! empty( $item['item_link']['url'] ) ) {
                        $item_tag = 'a';
                        $this->add_link_attributes( $link_key, $item['item_link'] );
                    }
                    ?>
                    
                    <<?php echo $item_tag; ?> class="crt-marquee-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>" <?php echo $this->get_render_attribute_string( $link_key ); ?>>
                        
                        <?php if ( ! empty( $item['item_image']['url'] ) ) : ?>
                            <div class="crt-marquee-item-image">
                                <?php Group_Control_Image_Size::print_attachment_image_html( $item, 'image_size', 'item_image' ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $item['item_text'] ) ) : ?>
                            <div class="crt-marquee-item-text">
                                <?php echo wp_kses_post( $item['item_text'] ); ?>
                            </div>
                        <?php endif; ?>

                    </<?php echo $item_tag; ?>>
                    
                    <?php if ( ! empty( $settings['separator_icon']['value'] ) ) : ?>
                        <div class="crt-marquee-separator">
                            <?php \Elementor\Icons_Manager::render_icon( $settings['separator_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
