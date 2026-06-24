<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Widget_Shipping_Method extends \Elementor\Widget_Base {

	public function get_name() {
		return 'crt_email_shipping_method';
	}

	public function get_title() {
		return __( 'Shipping Method', 'crt-manage' );
	}

	public function get_icon() {
		return 'eicon-truck';
	}

	public function get_categories() {
		return [ 'crt_manage_woocommerce' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'content_section', [ 'label' => __( 'Information', 'crt-manage' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ] );
		$this->add_control( 'title_text', [ 'label' => __( 'Title', 'crt-manage' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => __( 'Shipping Method:', 'crt-manage' ) ] );
		$this->end_controls_section();

		$this->start_controls_section( 'style_section', [ 'label' => __( 'Style', 'crt-manage' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'typography', 'selector' => '{{WRAPPER}} .crt-shipping-method-wrapper' ] );
		$this->add_control( 'text_color', [ 'label' => __( 'Text Color', 'crt-manage' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .crt-shipping-method-wrapper' => 'color: {{VALUE}}' ] ] );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$order = \CrtAddons\Classes\EmailBuilder\CRT_Email_Builder::get_current_order();

		if ( ! $order ) {
			echo '<div class="elementor-alert elementor-alert-warning">' . __( 'No order context found. Shipping Method will appear here.', 'crt-manage' ) . '</div>';
			return;
		}

		echo '<div class="crt-shipping-method-wrapper">';
		if ( ! empty( $settings['title_text'] ) ) {
			echo '<strong>' . esc_html( $settings['title_text'] ) . ' </strong>';
		}
		echo esc_html( $order->get_shipping_method() );
		echo '</div>';
	}
}
