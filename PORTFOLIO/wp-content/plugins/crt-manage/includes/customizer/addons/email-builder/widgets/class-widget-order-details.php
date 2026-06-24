<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Widget_Order_Details extends \Elementor\Widget_Base {

	public function get_name() {
		return 'crt_email_order_details';
	}

	public function get_title() {
		return __( 'Order Details', 'crt-manage' );
	}

	public function get_icon() {
		return 'eicon-table';
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
			'show_order_number',
			[
				'label' => __( 'Show Order Number', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'crt-manage' ),
				'label_off' => __( 'Hide', 'crt-manage' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_order_date',
			[
				'label' => __( 'Show Order Date', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'crt-manage' ),
				'label_off' => __( 'Hide', 'crt-manage' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'show_order_total',
			[
				'label' => __( 'Show Order Total', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'crt-manage' ),
				'label_off' => __( 'Hide', 'crt-manage' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_order_status',
			[
				'label' => __( 'Show Order Status', 'crt-manage' ),
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
				'selector' => '{{WRAPPER}} .crt-order-details-wrapper ul li',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-order-details-wrapper ul li' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$order = \CrtAddons\Classes\EmailBuilder\CRT_Email_Builder::get_current_order();

		if ( ! $order ) {
			echo '<div class="elementor-alert elementor-alert-warning">' . __( 'No order context found. Order Details will appear here in emails.', 'crt-manage' ) . '</div>';
			return;
		}

		echo '<div class="crt-order-details-wrapper">';
		echo '<ul style="list-style:none; padding: 0; margin: 0;">';
		
		if ( 'yes' === $settings['show_order_number'] ) {
			echo '<li style="margin-bottom: 5px;"><strong>' . __( 'Order Number:', 'crt-manage' ) . '</strong> ' . $order->get_order_number() . '</li>';
		}
		
		if ( 'yes' === $settings['show_order_date'] ) {
			echo '<li style="margin-bottom: 5px;"><strong>' . __( 'Order Date:', 'crt-manage' ) . '</strong> ' . wc_format_datetime( $order->get_date_created() ) . '</li>';
		}

		if ( 'yes' === $settings['show_order_total'] ) {
			echo '<li style="margin-bottom: 5px;"><strong>' . __( 'Order Total:', 'crt-manage' ) . '</strong> ' . $order->get_formatted_order_total() . '</li>';
		}

		if ( 'yes' === $settings['show_order_status'] ) {
			echo '<li style="margin-bottom: 5px;"><strong>' . __( 'Order Status:', 'crt-manage' ) . '</strong> ' . wc_get_order_status_name( $order->get_status() ) . '</li>';
		}

		echo '</ul>';
		echo '</div>';
	}
}
