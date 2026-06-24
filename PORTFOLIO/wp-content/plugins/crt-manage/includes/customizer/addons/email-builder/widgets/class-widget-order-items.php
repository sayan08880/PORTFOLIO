<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Widget_Order_Items extends \Elementor\Widget_Base {

	public function get_name() {
		return 'crt_email_order_items';
	}

	public function get_title() {
		return __( 'Order Items', 'crt-manage' );
	}

	public function get_icon() {
		return 'eicon-editor-list-ul';
	}

	public function get_categories() {
		return [ 'crt_manage_woocommerce' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'content_section', [ 'label' => __( 'Information', 'crt-manage' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ] );
		
		$this->add_control(
			'show_image',
			[
				'label' => __( 'Show Product Image', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_sku',
			[
				'label' => __( 'Show SKU', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Table Style', 'crt-manage' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#e5e5e5',
				'selectors' => [
					'{{WRAPPER}} .crt-order-items-table th' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-order-items-table td' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-order-items-table' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'header_bg_color',
			[
				'label' => __( 'Header Background', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#f7f7f7',
				'selectors' => [
					'{{WRAPPER}} .crt-order-items-table th' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'header_text_color',
			[
				'label' => __( 'Header Text Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-order-items-table th' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'body_text_color',
			[
				'label' => __( 'Body Text Color', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-order-items-table td' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$order = \CrtAddons\Classes\EmailBuilder\CRT_Email_Builder::get_current_order();

		if ( ! $order ) {
			echo '<div class="elementor-alert elementor-alert-warning">' . __( 'No order context found. Order Items table will appear here in emails.', 'crt-manage' ) . '</div>';
			return;
		}

		$items = $order->get_items();

		echo '<div class="crt-order-items-wrapper">';
		echo '<table class="crt-order-items-table" style="width: 100%; border-collapse: collapse; border: 1px solid ' . esc_attr($settings['border_color'] ?? '#e5e5e5') . ';">';
		
		// Header
		echo '<thead>';
		echo '<tr>';
		echo '<th style="padding: 10px; border: 1px solid ' . esc_attr($settings['border_color'] ?? '#e5e5e5') . '; text-align: left;">' . __( 'Product', 'crt-manage' ) . '</th>';
		echo '<th style="padding: 10px; border: 1px solid ' . esc_attr($settings['border_color'] ?? '#e5e5e5') . '; text-align: left;">' . __( 'Quantity', 'crt-manage' ) . '</th>';
		echo '<th style="padding: 10px; border: 1px solid ' . esc_attr($settings['border_color'] ?? '#e5e5e5') . '; text-align: left;">' . __( 'Price', 'crt-manage' ) . '</th>';
		echo '</tr>';
		echo '</thead>';

		// Body
		echo '<tbody>';
		foreach ( $items as $item_id => $item ) {
			$product = $item->get_product();
			
			echo '<tr>';
			
			// Product Column
			echo '<td style="padding: 10px; border: 1px solid ' . esc_attr($settings['border_color'] ?? '#e5e5e5') . ';">';
			
			if ( 'yes' === $settings['show_image'] && $product && $product->get_image_id() ) {
				echo '<div style="margin-bottom: 5px;">' . wp_get_attachment_image( $product->get_image_id(), [32, 32] ) . '</div>';
			}
			
			echo esc_html( $item->get_name() );
			
			if ( 'yes' === $settings['show_sku'] && $product && $product->get_sku() ) {
				echo '<br><small>SKU: ' . esc_html( $product->get_sku() ) . '</small>';
			}
			echo '</td>';

			// Quantity Column
			echo '<td style="padding: 10px; border: 1px solid ' . esc_attr($settings['border_color'] ?? '#e5e5e5') . ';">' . esc_html( $item->get_quantity() ) . '</td>';

			// Price Column
			echo '<td style="padding: 10px; border: 1px solid ' . esc_attr($settings['border_color'] ?? '#e5e5e5') . ';">' . $order->get_formatted_line_subtotal( $item ) . '</td>';

			echo '</tr>';
		}
		echo '</tbody>';

		// Totals Footer
		echo '<tfoot>';
		$totals = $order->get_order_item_totals();
		if ( $totals ) {
			foreach ( $totals as $total ) {
				echo '<tr>';
				echo '<th colspan="2" style="padding: 10px; text-align: right; border: 1px solid ' . esc_attr($settings['border_color'] ?? '#e5e5e5') . ';">' . wp_kses_post( $total['label'] ) . '</th>';
				echo '<td style="padding: 10px; border: 1px solid ' . esc_attr($settings['border_color'] ?? '#e5e5e5') . ';">' . wp_kses_post( $total['value'] ) . '</td>';
				echo '</tr>';
			}
		}
		echo '</tfoot>';

		echo '</table>';
		echo '</div>';
	}
}
