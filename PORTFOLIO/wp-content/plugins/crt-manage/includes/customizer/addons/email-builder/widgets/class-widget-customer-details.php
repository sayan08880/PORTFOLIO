<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Widget_Customer_Details extends \Elementor\Widget_Base {

	public function get_name() {
		return 'crt_email_customer_details';
	}

	public function get_title() {
		return __( 'Customer Details', 'crt-manage' );
	}

	public function get_icon() {
		return 'eicon-person';
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
			'show_customer_name',
			[
				'label' => __( 'Show Name', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'crt-manage' ),
				'label_off' => __( 'Hide', 'crt-manage' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_customer_email',
			[
				'label' => __( 'Show Email', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'crt-manage' ),
				'label_off' => __( 'Hide', 'crt-manage' ),
				'return_value' => 'yes',
				'default' => 'yes',
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
				'selector' => '{{WRAPPER}} .crt-customer-details-wrapper',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-customer-details-wrapper p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$order = \CrtAddons\Classes\EmailBuilder\CRT_Email_Builder::get_current_order();

		if ( ! $order ) {
			echo '<div class="elementor-alert elementor-alert-warning">' . __( 'No order context found. Customer Details will appear here in emails.', 'crt-manage' ) . '</div>';
			return;
		}

		echo '<div class="crt-customer-details-wrapper">';
		
		if ( 'yes' === $settings['show_customer_name'] ) {
			echo '<p style="margin: 0 0 5px 0;"><strong>' . __( 'Name:', 'crt-manage' ) . '</strong> ' . esc_html( $order->get_formatted_billing_full_name() ) . '</p>';
		}
		
		if ( 'yes' === $settings['show_customer_email'] ) {
			echo '<p style="margin: 0 0 5px 0;"><strong>' . __( 'Email:', 'crt-manage' ) . '</strong> ' . esc_html( $order->get_billing_email() ) . '</p>';
		}
		
		echo '</div>';
	}
}
