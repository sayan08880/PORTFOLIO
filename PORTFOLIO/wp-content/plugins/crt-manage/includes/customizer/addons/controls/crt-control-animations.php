<?php

use Elementor\Base_Data_Control;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}



/**
* Animation Control for Elements.
*
* A base control for creating entrance animation control. Displays a select box
* with the available entrance animation effects @see CRT_Control_Animations::get_animations() .
*/
class CRT_Control_Animations extends Base_Data_Control {

	/**
	* List of animations.
	*/
	private static $_animations;

	/**
	* Get control type.
	*/
	public function get_type() {
		return 'crt-animations';
	}

	/**
	* Get animations list.
	* Retrieve the list of all the available animations.
	*/
	public static function get_animations() {

		if ( is_null( self::$_animations ) ) {
			self::$_animations = [
				'Fade' => [
					'fade-in' => 'Fade In',
					'fade-out' => 'Fade Out',
				],
				'Slide' => [
					'slide-top' => 'Top',
					'slide-right' => 'Right',
					'slide-x-right' => 'X Right',
					'slide-bottom' => 'Bottom',
					'slide-left' => 'Left',
					'slide-x-left' => 'X Left',
				],
				'Skew' => [
					'skew-top' => 'Top',
					'skew-right' => 'Right',
					'skew-bottom' => 'Bottom',
					'skew-left' => 'Left',
				],
				'Scale' => [
					'scale-up' => 'Up',
					'scale-down' => 'Down',
				],
				'Roll' => [
					'roll-left' => 'Left',
					'roll-right' => 'Right',
				],
			];
		}

		return self::$_animations;
	}

	/**
	* Render animations control template.
	*
	* Used to generate the control HTML in the editor using Underscore JS
	* template. The variables for the class are available using `data` JS
	* object.
	*/
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<select id="<?php echo esc_attr($control_uid); ?>" data-setting="{{ data.name }}">
					<option value="none"><?php echo esc_html__( 'None', 'crt-manage' ); ?></option>
					<?php foreach ( self::get_animations() as $animations_group_name => $animations_group ) : ?>
						<optgroup label="<?php echo esc_attr($animations_group_name); ?>">
							<?php foreach ( $animations_group as $animation_name => $animation_title ) : ?>
								<option value="<?php echo esc_attr($animation_name); ?>"><?php echo esc_html($animation_title); ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}


/**
* Animation Control for Overlays.
*
* A base control for creating entrance animation control. Displays a select box
* with the available entrance animation effects @see CRT_Control_Animations::get_animations() .
*/
class CRT_Control_Animations_Alt extends CRT_Control_Animations {

	/**
	* Get control type.
	*/
	public function get_type() {
		return 'crt-animations-alt';
	}

	/**
	* Render animations control template.
	*
	* Used to generate the control HTML in the editor using Underscore JS
	* template. The variables for the class are available using `data` JS
	* object.
	*/
	public function content_template() {
		$animations = self::get_animations();
		$control_uid = $this->get_control_uid();

		// Remove Extra
		unset($animations['Slide']['slide-x-right']);
		unset($animations['Slide']['slide-x-left']);
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<select id="<?php echo esc_attr($control_uid); ?>" data-setting="{{ data.name }}">
					<option value="none"><?php echo esc_html__( 'None', 'crt-manage' ); ?></option>
					<?php foreach ( $animations as $animations_group_name => $animations_group ) : ?>
						<optgroup label="<?php echo esc_attr($animations_group_name); ?>">
							<?php foreach ( $animations_group as $animation_name => $animation_title ) : ?>
								<option value="<?php echo esc_attr($animation_name); ?>"><?php echo esc_html($animation_title); ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}


/**
* Animation Control for Buttons.
*
* A base control for creating button animation control. Displays a select box
* with the available button animation effects @see CRT_Control_Button_Animations::get_animations() .
*/
class CRT_Control_Button_Animations extends Base_Data_Control {

	/**
	* List of animations.
	*/
	private static $_animations;

	/**
	* Get control type.
	*/
	public function get_type() {
		return 'crt-button-animations';
	}

	/**
	* Get animations list.
	* Retrieve the list of all the available animations.
	*/
	public static function get_animations() {
		if ( is_null( self::$_animations ) ) {
			self::$_animations = [
				'Animations' => [
					'crt-button-none' => esc_html__( 'None', 'crt-manage' ),
					'pro-wnt' => esc_html__( 'Winona + Text', 'crt-manage' ),
					'pro-rlt' => esc_html__( 'Ray Left + Text', 'crt-manage' ),
					'pro-rrt' => esc_html__( 'Ray Right + Text', 'crt-manage' ),
					'crt-button-wayra-left' => esc_html__( 'Wayra Left', 'crt-manage' ),
					'crt-button-wayra-right' => esc_html__( 'Wayra Right', 'crt-manage' ),
					'crt-button-isi-left' => esc_html__( 'Isi Left', 'crt-manage' ),
					'crt-button-isi-right' => esc_html__( 'Isi Right', 'crt-manage' ),
					'crt-button-aylen' => esc_html__( 'Aylen', 'crt-manage' ),
					'crt-button-antiman' => esc_html__( 'Antiman', 'crt-manage' ),
				],
				'2D Animations' => [
					'elementor-animation-grow' => esc_html__( 'Grow', 'crt-manage' ),
					'elementor-animation-shrink' => esc_html__( 'Shrink', 'crt-manage' ),
					'elementor-animation-pulse' => esc_html__( 'Pulse', 'crt-manage' ),
					'elementor-animation-pulse-grow' => esc_html__( 'Pulse Grow', 'crt-manage' ),
					'elementor-animation-pulse-shrink' => esc_html__( 'Pulse Shrink', 'crt-manage' ),
					'elementor-animation-push' => esc_html__( 'Push', 'crt-manage' ),
					'elementor-animation-pop' => esc_html__( 'Pop', 'crt-manage' ),
					'elementor-animation-bounce-in' => esc_html__( 'Bounce In', 'crt-manage' ),
					'elementor-animation-bounce-out' => esc_html__( 'Bounce Out', 'crt-manage' ),
					'elementor-animation-rotate' => esc_html__( 'Rotate', 'crt-manage' ),
					'elementor-animation-grow-rotate' => esc_html__( 'Grow Rotate', 'crt-manage' ),
					'elementor-animation-float' => esc_html__( 'Float', 'crt-manage' ),
					'elementor-animation-sink' => esc_html__( 'Sink', 'crt-manage' ),
					'elementor-animation-bob' => esc_html__( 'Bob', 'crt-manage' ),
					'elementor-animation-hang' => esc_html__( 'Hang', 'crt-manage' ),
					'elementor-animation-skew' => esc_html__( 'Skew', 'crt-manage' ),
					'elementor-animation-skew-forward' => esc_html__( 'Skew Forward', 'crt-manage' ),
					'elementor-animation-skew-backward' => esc_html__( 'Skew Backward', 'crt-manage' ),
					'elementor-animation-wobble-horizontal' => esc_html__( 'Wobble Horizontal', 'crt-manage' ),
					'elementor-animation-wobble-vertical' => esc_html__( 'Wobble Vertical', 'crt-manage' ),
					'elementor-animation-wobble-to-bottom-right' => esc_html__( 'Wobble To Bottom Right', 'crt-manage' ),
					'elementor-animation-wobble-to-top-right' => esc_html__( 'Wobble To Top Right', 'crt-manage' ),
					'elementor-animation-wobble-top' => esc_html__( 'Wobble Top', 'crt-manage' ),
					'elementor-animation-wobble-bottom' => esc_html__( 'Wobble Bottom', 'crt-manage' ),
					'elementor-animation-wobble-skew' => esc_html__( 'Wobble Skew', 'crt-manage' ),
					'elementor-animation-buzz' => esc_html__( 'Buzz', 'crt-manage' ),
					'elementor-animation-buzz-out' => esc_html__( 'Buzz Out', 'crt-manage' ),
					'elementor-animation-forward' => esc_html__( 'Forward', 'crt-manage' ),
					'elementor-animation-backward' => esc_html__( 'Backward', 'crt-manage' ),
				],
				'Background Animations' => [
					'crt-button-back-pulse' => esc_html__( 'Back Pulse', 'crt-manage' ),
					'crt-button-sweep-to-right' => esc_html__( 'Sweep To Right', 'crt-manage' ),
					'crt-button-sweep-to-left' => esc_html__( 'Sweep To Left', 'crt-manage' ),
					'crt-button-sweep-to-bottom' => esc_html__( 'Sweep To Bottom', 'crt-manage' ),
					'crt-button-sweep-to-top' => esc_html__( 'Sweep To top', 'crt-manage' ),
					'crt-button-bounce-to-right' => esc_html__( 'Bounce To Right', 'crt-manage' ),
					'crt-button-bounce-to-left' => esc_html__( 'Bounce To Left', 'crt-manage' ),
					'crt-button-bounce-to-bottom' => esc_html__( 'Bounce To Bottom', 'crt-manage' ),
					'crt-button-bounce-to-top' => esc_html__( 'Bounce To Top', 'crt-manage' ),
					'crt-button-radial-out' => esc_html__( 'Radial Out', 'crt-manage' ),
					'crt-button-radial-in' => esc_html__( 'Radial In', 'crt-manage' ),
					'crt-button-rectangle-in' => esc_html__( 'Rectangle In', 'crt-manage' ),
					'crt-button-rectangle-out' => esc_html__( 'Rectangle Out', 'crt-manage' ),
					'crt-button-shutter-in-horizontal' => esc_html__( 'Shutter In Horizontal', 'crt-manage' ),
					'crt-button-shutter-out-horizontal' => esc_html__( 'Shutter Out Horizontal', 'crt-manage' ),
					'crt-button-shutter-in-vertical' => esc_html__( 'Shutter In Vertical', 'crt-manage' ),
					'crt-button-shutter-out-vertical' => esc_html__( 'Shutter Out Vertical', 'crt-manage' ),
					'crt-button-underline-from-left' => esc_html__( 'Underline From Left', 'crt-manage' ),
					'crt-button-underline-from-center' => esc_html__( 'Underline From Center', 'crt-manage' ),
					'crt-button-underline-from-right' => esc_html__( 'Underline From Right', 'crt-manage' ),
					'crt-button-underline-reveal' => esc_html__( 'Underline Reveal', 'crt-manage' ),
					'crt-button-overline-reveal' => esc_html__( 'Overline Reveal', 'crt-manage' ),
					'crt-button-overline-from-left' => esc_html__( 'Overline From Left', 'crt-manage' ),
					'crt-button-overline-from-center' => esc_html__( 'Overline From Center', 'crt-manage' ),
					'crt-button-overline-from-right' => esc_html__( 'Overline From Right', 'crt-manage' ),
				]
			];
		}

		if ( defined('CRT_ADDONS_PRO_VERSION') && crt_fs()->can_use_premium_code() ) {
			self::$_animations = \CrtAddonsPro\Includes\Controls\CRT_Control_Animations_Pro::crt_button_animations();
		}

		return self::$_animations;
	}

	/**
	* Render animations control template.
	*
	* Used to generate the control HTML in the editor using Underscore JS
	* template. The variables for the class are available using `data` JS
	* object.
	*/
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<select id="<?php echo esc_attr($control_uid); ?>" data-setting="{{ data.name }}">
					<?php foreach ( self::get_animations() as $animations_group_name => $animations_group ) : ?>
						<optgroup label="<?php echo esc_attr($animations_group_name); ?>">
							<?php foreach ( $animations_group as $animation_name => $animation_title ) : ?>
								<option value="<?php echo esc_attr($animation_name); ?>"><?php echo esc_html($animation_title); ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

	public static function get_assets( $setting ) {
		if ( ! $setting || 'none' === $setting || !str_contains($setting, 'elementor-animation') ) {
			return [];
		}

		return [
			'styles' => [ 'e-animation-' . str_replace('elementor-animation-', '', $setting) ],
		];
	}
	
}