<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Widget_Shipping_Address extends \Elementor\Widget_Base {

	public function get_name() {
		return 'crt_email_shipping_address';
	}

	public function get_title() {
		return __( 'Shipping Address', 'crt-manage' );
	}

	public function get_icon() {
		return 'eicon-envelope';
	}

	public function get_categories() {
		return [ 'crt_manage_woocommerce' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Information', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title_text',
			[
				'label' => __( 'Title', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Shipping Address', 'crt-manage' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .crt-shipping-address-wrapper',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-shipping-address-wrapper' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$order = \CrtAddons\Classes\EmailBuilder\CRT_Email_Builder::get_current_order();

		if ( ! $order ) {
			echo '<div class="elementor-alert elementor-alert-warning">' . __( 'No order context found. Shipping Address will appear here in emails.', 'crt-manage' ) . '</div>';
			return;
		}

		$shipping_address = $order->get_formatted_shipping_address();

		if ( ! $shipping_address ) {
			// Fallback or hide
			return;
		}

		echo '<div class="crt-shipping-address-wrapper">';
		
		if ( ! empty( $settings['title_text'] ) ) {
			echo '<h2 style="font-size: 16px; margin: 0 0 10px 0;">' . esc_html( $settings['title_text'] ) . '</h2>';
		}

		echo wp_kses_post( $shipping_address );
		
		if ( $order->get_shipping_phone() ) {
			echo '<br />' . esc_html( $order->get_shipping_phone() );
		}
		
		echo '</div>';
	}
}
