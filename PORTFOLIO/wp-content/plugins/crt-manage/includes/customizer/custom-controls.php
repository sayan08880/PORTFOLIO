<?php
/**
 * Customizer Custom Controls
 */

if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Toggle Switch Custom Control
	 */
	class Crt_Manage_Toggle_Switch_Custom_Control extends WP_Customize_Control {
		/**
		 * The type of control being rendered
		 */
		public $type = 'toggle_switch';

		/**
		 * Render the control in the customizer
		 */
		public function render_content() {
			?>
			<div class="toggle-switch-control">
				<div class="toggle-switch">
					<input type="checkbox" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" class="toggle-switch-checkbox" value="<?php echo esc_attr( $this->value() ); ?>" 
														<?php
															$this->link();
															checked( $this->value() );
														?>
					>
					<label class="toggle-switch-label" for="<?php echo esc_attr( $this->id ); ?>">
						<span class="toggle-switch-inner"></span>
						<span class="toggle-switch-switch"></span>
					</label>
				</div>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php if ( ! empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
			</div>
			<?php
		}
	}

	/**
	 * Sortable Repeater Custom Control
	 */
	class Crt_Manage_Sortable_Repeater_Custom_Control extends WP_Customize_Control {
		/**
		 * The type of control being rendered
		 */
		public $type = 'sortable_repeater';

		/**
		 * Button labels
		 */
		public $button_labels = array();

		/**
		 * Constructor
		 */
		public function __construct( $manager, $id, $args = array(), $options = array() ) {
			parent::__construct( $manager, $id, $args );
			// Merge the passed button labels with our default labels
			$this->button_labels = wp_parse_args(
				$this->button_labels,
				array(
					'add' => __( 'Add', 'crt-manage' ),
				)
			);
		}

		/**
		 * Render the control in the customizer
		 */
		public function render_content() {
			?>
			<div class="sortable_repeater_control">
				<?php if ( ! empty( $this->label ) ) { ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php } ?>
				<?php if ( ! empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-sortable-repeater" <?php $this->link(); ?> />
				<div class="sortable_repeater sortable">
					<div class="repeater">
						<input type="text" value="" class="repeater-input" placeholder="https://" /><span class="dashicons dashicons-sort"></span><a class="customize-control-sortable-repeater-delete" href="#"><span class="dashicons dashicons-no-alt"></span></a>
					</div>
				</div>
				<button class="button customize-control-sortable-repeater-add" type="button"><?php echo esc_html( $this->button_labels['add'] ); ?></button>
			</div>
			<?php
		}
	}

	/**
	 * Multi Input field
	 */
	class Crt_Manage_Multi_Input_Custom_control extends WP_Customize_Control {
		public $type = 'multi_input';

		public function render_content() {
			?>
			<label class="customize_multi_input">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<p><?php echo wp_kses_post( $this->description ); ?></p>
				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize_multi_value_field" data-customize-setting-link="<?php echo esc_attr( $this->id ); ?>"/>
				<div class="customize_multi_fields ascendoor-multi-fields">
					<div class="set">
						<input type="text" value="" class="customize_multi_single_field"/>
						<a href="#" class="customize_multi_remove_field dashicons dashicons-no-alt">X</a>
					</div>
				</div>
				<a href="#" class="button button-primary customize_multi_add_field"><?php echo esc_html__( 'Add More', 'crt-manage' ); ?></a>
			</label>
			<?php
		}
	}

	/**
	 * Horizontal Line Control
	 */
	class Crt_Manage_Customize_Horizontal_Line extends WP_Customize_Control {
		/**
		 * Control Type
		 */
		public $type = 'hr';

		/**
		 * Render Settings
		 */
		public function render_content() {
			?>
			<div>
				<hr style="border-bottom: 1px solid #DDD;" />
			</div>
			<?php
		}
	}

    class Crt_Manage_Customize_Heading extends WP_Customize_Control {
        /**
         * Render Settings
         */
        public function render_content() {
            ?>
            <div>
                <h2 class="customize-control-title" style="color: var(--wp-admin-theme-color);"><?php echo esc_html( $this->label ); ?></h2>
            </div>
            <?php
        }
    }

    /**
     * Repeater Field
     */
    class Crt_Manage_Customize_Field_Repeater extends WP_Customize_Control {
        public $id;
        private $boxtitle = array();
        private $add_field_label = array();
        private $custom_icon_container = '';
        private $allowed_html = array();
        public $custom_repeater_image_control = false;
        public $custom_repeater_icon_control = false;
        public $custom_repeater_color_control = false;
        public $custom_repeater_color2_control = false;
        public $custom_repeater_title_control = array();
        public $custom_repeater_subtitle_control = array();
        public $custom_repeater_text_control = array();
        public $custom_repeater_link_control = array();
        public $custom_repeater_text2_control = array();
        public $custom_repeater_link2_control = false;
        public $custom_repeater_shortcode_control = false;
        public $custom_repeater_repeater_control = false;
        public $custom_repeater_repeater_fields = '';
        public $custom_repeater_radio_control = array();



        /*Class constructor*/
        public function __construct( $manager, $id, $args = array() ) {
            parent::__construct( $manager, $id, $args );
            /*Get options from customizer.php*/
            $this->add_field_label = esc_html__( 'Add new field', 'crt-manage' );
            if ( ! empty( $args['add_field_label'] ) ) {
                $this->add_field_label = $args['add_field_label'];
            }

            $this->boxtitle = esc_html__( 'Customizer Repeater', 'crt-manage' );
            if ( ! empty ( $args['item_name'] ) ) {
                $this->boxtitle = $args['item_name'];
            } elseif ( ! empty( $this->label ) ) {
                $this->boxtitle = $this->label;
            }

            if ( ! empty( $args['custom_repeater_image_control'] ) ) {
                $this->custom_repeater_image_control = $args['custom_repeater_image_control'];
            }

            if ( ! empty( $args['custom_repeater_icon_control'] ) ) {
                $this->custom_repeater_icon_control = $args['custom_repeater_icon_control'];
            }

            if ( ! empty( $args['custom_repeater_color_control'] ) ) {
                $this->custom_repeater_color_control = $args['custom_repeater_color_control'];
            }

            if ( ! empty( $args['custom_repeater_color2_control'] ) ) {
                $this->custom_repeater_color2_control = $args['custom_repeater_color2_control'];
            }

            if ( ! empty( $args['custom_repeater_title_control'] ) ) {
                $this->custom_repeater_title_control = $args['custom_repeater_title_control'];
            }

            if ( ! empty( $args['custom_repeater_subtitle_control'] ) ) {
                $this->custom_repeater_subtitle_control = $args['custom_repeater_subtitle_control'];
            }

            if ( ! empty( $args['custom_repeater_text_control'] ) ) {
                $this->custom_repeater_text_control = $args['custom_repeater_text_control'];
            }

            if ( ! empty( $args['custom_repeater_link_control'] ) ) {
                $this->custom_repeater_link_control = $args['custom_repeater_link_control'];
            }

            if ( ! empty( $args['custom_repeater_text2_control'] ) ) {
                $this->custom_repeater_text2_control = $args['custom_repeater_text2_control'];
            }

            if ( ! empty( $args['custom_repeater_link2_control'] ) ) {
                $this->custom_repeater_link2_control = $args['custom_repeater_link2_control'];
            }

            if ( ! empty( $args['custom_repeater_shortcode_control'] ) ) {
                $this->custom_repeater_shortcode_control = $args['custom_repeater_shortcode_control'];
            }

            if ( ! empty( $args['custom_repeater_repeater_control'] ) ) {
                $this->custom_repeater_repeater_control = $args['custom_repeater_repeater_control'];
            }

            if ( ! empty( $id ) ) {
                $this->id = $id;
            }

            if ( file_exists( CRT_MANAGE_DIR . 'includes/customizer/icons.php' ) ) {
                $this->custom_icon_container =  CRT_MANAGE_DIR . 'includes/customizer/icons.php';
            }

            $allowed_array1 = wp_kses_allowed_html( 'post' );
            $allowed_array2 = array(
                'input' => array(
                    'type'        => array(),
                    'class'       => array(),
                    'placeholder' => array()
                )
            );

            $this->allowed_html = array_merge( $allowed_array1, $allowed_array2 );
        }

        /*Enqueue resources for the control*/
        public function enqueue() {
//            wp_enqueue_style( 'font-awesome', CRT_MANAGE_URI . '/assets/css/font-awesome.css', array(), CRT_MANAGE_VERSION );
            wp_enqueue_style( 'font-awesome', CRT_MANAGE_URI . '/assets/css/all.min.css', array(), CRT_MANAGE_VERSION );
            wp_enqueue_style( 'crt-manage-customizer-repeater-admin-stylesheet', CRT_MANAGE_URI . '/assets/css/admin-style.css', array(), CRT_MANAGE_VERSION );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'crt-manage-customizer-customizer-repeater-script', CRT_MANAGE_URI . '/assets/js/customizer_repeater.js', array('jquery', 'jquery-ui-draggable', 'wp-color-picker' ), CRT_MANAGE_VERSION, true  );
            wp_enqueue_script( 'crt-manage-customizer-repeater-fontawesome-iconpicker', CRT_MANAGE_URI . '/assets/js/fontawesome-iconpicker.js', array( 'jquery' ), CRT_MANAGE_VERSION, true );
            wp_enqueue_style( 'crt-manage-customizer-repeater-fontawesome-iconpicker-script', CRT_MANAGE_URI . '/assets/css/fontawesome-iconpicker.min.css', array(), CRT_MANAGE_VERSION );
        }

        public function render_content() {

            /*Get default options*/
            $this_default = json_decode( $this->setting->default );

            /*Get values (json format)*/
            $values = $this->value();

            /*Decode values*/
            $json = json_decode( $values );

            if ( ! is_array( $json ) ) {
                $json = array( $values );
            } ?>

            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <div class="customizer-repeater-general-control-repeater customizer-repeater-general-control-droppable">
                <?php
                if ( ( count( $json ) == 1 && '' === $json[0] ) || empty( $json ) ) {
                    if ( ! empty( $this_default ) ) {
                        $this->iterate_array( $this_default ); ?>
                        <input type="hidden"
                               id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
                               class="customizer-repeater-colector"
                               value="<?php echo esc_textarea( json_encode( $this_default ) ); ?>"/>
                        <?php
                    } else {
                        $this->iterate_array(); ?>
                        <input type="hidden"
                               id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
                               class="customizer-repeater-colector"/>
                        <?php
                    }
                } else {
                    $this->iterate_array( $json ); ?>
                    <input type="hidden" id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
                           class="customizer-repeater-colector" value="<?php echo esc_textarea( $this->value() ); ?>"/>
                    <?php
                } ?>
            </div>
            <button type="button" class="button add_field customizer-repeater-new-field">
                <?php echo esc_html( $this->add_field_label ); ?>
            </button>
            <?php
        }

        private function iterate_array($array = array()){
            /*Counter that helps checking if the box is first and should have the delete button disabled*/
            $it = 0;
            if(!empty($array)){
                foreach($array as $icon){ ?>
                    <div class="customizer-repeater-general-control-repeater-container customizer-repeater-draggable">
                        <div class="customizer-repeater-customize-control-title">
                            <?php echo esc_html( $this->boxtitle ) ?>
                        </div>
                        <div class="customizer-repeater-box-content-hidden">
                            <?php
                            $choice = $image_url = $image_id = $icon_value = $title = $subtitle = $text = $text2  = $link2 = $link = $shortcode = $repeater = $color = $color2 = '';

                            $radio_value = $icon->field_type;
                            if(!empty($this->custom_repeater_radio_control)) {
                                $this->icon_type_radio($radio_value, $this->custom_repeater_radio_control);
                            }

                            if(!empty($icon->id)){
                                $id = $icon->id;
                            }
                            if(!empty($icon->choice)){
                                $choice = $icon->choice;
                            }
                            if(!empty($icon->image_url)){
                                $image_url = $icon->image_url;
                            }
                            if(!empty($icon->image_id)){
                                $image_id = $icon->image_id;
                            }
                            if(!empty($icon->icon_value)){
                                $icon_value = $icon->icon_value;
                            }
                            if(!empty($icon->color)){
                                $color = $icon->color;
                            }
                            if(!empty($icon->color2)){
                                $color2 = $icon->color2;
                            }
                            if(!empty($icon->title)){
                                $title = $icon->title;
                            }
                            if(!empty($icon->subtitle)){
                                $subtitle =  $icon->subtitle;
                            }
                            if(!empty($icon->text)){
                                $text = $icon->text;
                            }
                            if(!empty($icon->link)){
                                $link = $icon->link;
                            }
                            if(!empty($icon->text2)){
                                $text2 = $icon->text2;
                            }
                            if(!empty($icon->link2)){
                                $link2 = $icon->link2;
                            }
                            if(!empty($icon->shortcode)){
                                $shortcode = $icon->shortcode;
                            }

                            if(!empty($icon->social_repeater)){
                                $repeater = $icon->social_repeater;
                            }

                            if($this->custom_repeater_image_control == true && $this->custom_repeater_icon_control == true) {
                                $this->icon_type_choice( $choice );
                            }
                            if($this->custom_repeater_image_control == true){
                                $choice = 'customizer_repeater_image';
                                $attrs = array('label' => 'Image', 'class' => 'a', 'name' => 'image', 'data_id' => $image_id);
                                $this->image_control($image_url, $choice, $attrs);
                            }
                            if($this->custom_repeater_icon_control == true){
                                $this->icon_picker_control($icon_value, $choice);
                            }
                            if($this->custom_repeater_color_control == true){
                                $this->input_control(array(
                                    'label' => apply_filters('repeater_input_labels_filter', esc_html__( 'Hover color','crt-manage' ), $this->id, 'custom_repeater_color_control' ),
                                    'class' => 'customizer-repeater-color-control',
                                    'type'  => apply_filters('customizer_repeater_input_types_filter', 'color', $this->id, 'custom_repeater_color_control' ),
                                    'sanitize_callback' => 'sanitize_hex_color',
                                    'choice' => $choice,
                                ), $color);
                            }
                            if($this->custom_repeater_color2_control == true){
                                $this->input_control(array(
                                    'label' => apply_filters('repeater_input_labels_filter', esc_html__( 'Color','crt-manage' ), $this->id, 'custom_repeater_color2_control' ),
                                    'class' => 'customizer-repeater-color2-control',
                                    'type'  => apply_filters('customizer_repeater_input_types_filter', 'color', $this->id, 'custom_repeater_color2_control' ),
                                    'sanitize_callback' => 'sanitize_hex_color'
                                ), $color2);
                            }
                            if(!empty($this->custom_repeater_title_control)){
                                $this->input_control(array(
                                    'label' => apply_filters('repeater_input_labels_filter', esc_html__( !empty($this->custom_repeater_title_control['title']) ? $this->custom_repeater_title_control['title']:'Title','crt-manage' ), $this->id, 'custom_repeater_title_control' ),
                                    'class' => 'customizer-repeater-title-control',
                                    'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'custom_repeater_title_control' ),
                                ), $title);
                            }
                            if(!empty($this->custom_repeater_subtitle_control)){
                                $this->input_control(array(
                                    'label' => apply_filters('repeater_input_labels_filter', esc_html__( !empty($this->custom_repeater_subtitle_control['title']) ? $this->custom_repeater_subtitle_control['title']:'Subtitle','crt-manage' ), $this->id, 'custom_repeater_subtitle_control' ),
                                    'class' => 'customizer-repeater-subtitle-control',
                                    'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'custom_repeater_subtitle_control' ),
                                ), $subtitle);
                            }
                            if(!empty($this->custom_repeater_text_control)){
                                $this->input_control(array(
                                    'label' => apply_filters('repeater_input_labels_filter', esc_html__( !empty($this->custom_repeater_text_control['title']) ? $this->custom_repeater_text_control['title']:'Text','crt-manage' ), $this->id, 'custom_repeater_text_control' ),
                                    'class' => 'customizer-repeater-text-control',
                                    'type'  => apply_filters('customizer_repeater_input_types_filter', 'textarea', $this->id, 'custom_repeater_text_control' ),
                                ), $text);
                            }
                            if(!empty($this->custom_repeater_link_control)){
                                $this->input_control(array(
                                    'label' => apply_filters('repeater_input_labels_filter', esc_html__( !empty($this->custom_repeater_link_control['title']) ? $this->custom_repeater_link_control['title']:'Link','crt-manage' ), $this->id, 'custom_repeater_link_control' ),
                                    'class' => 'customizer-repeater-link-control',
                                    'sanitize_callback' => 'esc_url_raw',
                                    'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'custom_repeater_link_control' ),
                                ), $link);
                            }
                            if(!empty($this->custom_repeater_text2_control)){
                                $this->input_control(array(
                                    'label' => apply_filters('repeater_input_labels_filter', esc_html__( !empty($this->custom_repeater_text2_control['title']) ? $this->custom_repeater_text2_control['title']:'Text','crt-manage' ), $this->id, 'custom_repeater_text2_control' ),
                                    'class' => 'customizer-repeater-text2-control',
                                    'type'  => apply_filters('customizer_repeater_input_types_filter', 'textarea', $this->id, 'custom_repeater_text2_control' ),
                                ), $text2);
                            }
                            if($this->custom_repeater_link2_control){
                                $this->input_control(array(
                                    'label' => apply_filters('repeater_input_labels_filter', esc_html__( 'Link','crt-manage' ), $this->id, 'custom_repeater_link2_control' ),
                                    'class' => 'customizer-repeater-link2-control',
                                    'sanitize_callback' => 'esc_url_raw',
                                    'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'custom_repeater_link2_control' ),
                                ), $link2);
                            }
                            if($this->custom_repeater_shortcode_control==true){
                                $this->input_control(array(
                                    'label' => apply_filters('repeater_input_labels_filter', esc_html__( 'Shortcode','crt-manage' ), $this->id, 'custom_repeater_shortcode_control' ),
                                    'class' => 'customizer-repeater-shortcode-control',
                                    'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'custom_repeater_shortcode_control' ),
                                ), $shortcode);
                            }
                            if($this->custom_repeater_repeater_control==true){
                                $this->repeater_control($repeater);
                            }

                            $value = $icon->field_repeater;
                            if(!empty($this->custom_repeater_repeater_fields)) {
                                $this->repeater_fields($value, $this->custom_repeater_repeater_fields);
                            }
                            ?>

                            <input type="hidden" class="social-repeater-box-id" value="<?php if ( ! empty( $id ) ) {
                                echo esc_attr( $id );
                            } ?>">
                            <button type="button" class="social-repeater-general-control-remove-field" <?php if ( $it == 0 ) {
                                echo 'style="display:none;"';
                            } ?>>
                                <?php echo esc_html__( 'Delete field', 'crt-manage' ); ?>
                            </button>

                        </div>
                    </div>

                    <?php
                    $it++;
                }
            } else { ?>
                <div class="customizer-repeater-general-control-repeater-container">
                    <div class="customizer-repeater-customize-control-title">
                        <?php echo esc_html( $this->boxtitle ) ?>
                    </div>
                    <div class="customizer-repeater-box-content-hidden">
                        <?php
                        if(!empty($this->custom_repeater_radio_control)) {
                            $this->icon_type_radio($data = '', $this->custom_repeater_radio_control);
                        }
                        if ( $this->custom_repeater_image_control == true && $this->custom_repeater_icon_control == true ) {
                            $this->icon_type_choice();
                        }
                        if ( $this->custom_repeater_image_control == true ) {
                            $choice = 'customizer_repeater_image';
                            $attrs = array('label' => 'Image', 'class' => 'a', 'name' => 'image');
                            $image_url = '';
                            $this->image_control($image_url, $choice, $attrs);
                        }
                        if ( $this->custom_repeater_icon_control == true ) {
                            $this->icon_picker_control();
                        }
                        if($this->custom_repeater_color_control==true){
                            $this->input_control(array(
                                'label' => apply_filters('repeater_input_labels_filter', esc_html__( 'Hover color','crt-manage' ), $this->id, 'custom_repeater_color_control' ),
                                'class' => 'customizer-repeater-color-control',
                                'type'  => apply_filters('customizer_repeater_input_types_filter', 'color', $this->id, 'custom_repeater_color_control' ),
                                'sanitize_callback' => 'sanitize_hex_color'
                            ) );
                        }
                        if($this->custom_repeater_color2_control==true){
                            $this->input_control(array(
                                'label' => apply_filters('repeater_input_labels_filter', esc_html__( 'Color','crt-manage' ), $this->id, 'custom_repeater_color2_control' ),
                                'class' => 'customizer-repeater-color2-control',
                                'type'  => apply_filters('customizer_repeater_input_types_filter', 'color', $this->id, 'custom_repeater_color2_control' ),
                                'sanitize_callback' => 'sanitize_hex_color'
                            ) );
                        }
                        if (!empty( $this->custom_repeater_title_control)) {
                            $this->input_control( array(
                                'label' => apply_filters('repeater_input_labels_filter', esc_html__( !empty($this->custom_repeater_title_control['title']) ? $this->custom_repeater_title_control['title']:'Title','crt-manage' ), $this->id, 'custom_repeater_title_control' ),
                                'class' => 'customizer-repeater-title-control',
                                'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'custom_repeater_title_control' ),
                            ) );
                        }
                        if ( !empty($this->custom_repeater_subtitle_control) ) {
                            $this->input_control( array(
                                'label' => apply_filters('repeater_input_labels_filter', esc_html__( !empty($this->custom_repeater_subtitle_control['title']) ? $this->custom_repeater_subtitle_control['title']:'Subtitle','crt-manage' ), $this->id, 'custom_repeater_subtitle_control' ),
                                'class' => 'customizer-repeater-subtitle-control',
                                'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'custom_repeater_subtitle_control' ),
                            ) );
                        }
                        if ( $this->custom_repeater_text_control == true ) {
                            $this->input_control( array(
                                'label' => apply_filters('repeater_input_labels_filter', esc_html__( !empty($this->custom_repeater_text_control['title']) ? $this->custom_repeater_text_control['title']:'Text','crt-manage' ), $this->id, 'custom_repeater_text_control' ),
                                'class' => 'customizer-repeater-text-control',
                                'type'  => apply_filters('customizer_repeater_input_types_filter', 'textarea', $this->id, 'custom_repeater_text_control' ),
                            ) );
                        }
                        if (!empty($this->custom_repeater_link_control)) {
                            $this->input_control( array(
                                'label' => apply_filters('repeater_input_labels_filter', esc_html__( !empty($this->custom_repeater_link_control['title']) ? $this->custom_repeater_link_control['title']:'Link','crt-manage' ), $this->id, 'custom_repeater_link_control' ),
                                'class' => 'customizer-repeater-link-control',
                                'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'custom_repeater_link_control' ),
                            ) );
                        }
                        if ( !empty($this->custom_repeater_text2_control['title']) ) {
                            $this->input_control( array(
                                'label' => apply_filters('repeater_input_labels_filter', esc_html__( !empty($this->custom_repeater_text2_control['title']) ? $this->custom_repeater_text2_control['title']:'Text','crt-manage' ), $this->id, 'custom_repeater_text2_control' ),
                                'class' => 'customizer-repeater-text2-control',
                                'type'  => apply_filters('customizer_repeater_input_types_filter', 'textarea', $this->id, 'custom_repeater_text2_control' ),
                            ) );
                        }
                        if ( $this->custom_repeater_link2_control == true ) {
                            $this->input_control( array(
                                'label' => apply_filters('repeater_input_labels_filter', esc_html__( 'Link','crt-manage' ), $this->id, 'custom_repeater_link2_control' ),
                                'class' => 'customizer-repeater-link2-control',
                                'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'custom_repeater_link2_control' ),
                            ) );
                        }
                        if ( $this->custom_repeater_shortcode_control == true ) {
                            $this->input_control( array(
                                'label' => apply_filters('repeater_input_labels_filter', esc_html__( 'Shortcode','crt-manage' ), $this->id, 'custom_repeater_shortcode_control' ),
                                'class' => 'customizer-repeater-shortcode-control',
                                'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'custom_repeater_shortcode_control' ),
                            ) );
                        }
                        if($this->custom_repeater_repeater_control==true){
                            $this->repeater_control();
                        }

                        if(!empty($this->custom_repeater_repeater_fields)) {
                            $this->repeater_fields($value = array(), $this->custom_repeater_repeater_fields);
                        }
                        ?>
                        <input type="hidden" class="social-repeater-box-id">
                        <button type="button" class="social-repeater-general-control-remove-field button" style="display:none;">
                            <?php echo esc_html__( 'Delete field', 'crt-manage' ); ?>
                        </button>
                    </div>
                </div>
                <?php
            }
        }

        private function input_control( $options, $value='' ){ ?>

            <?php
            if( !empty($options['type']) ){
                switch ($options['type']) {
                    case 'textarea':?>
                        <span class="customize-control-title"><?php echo esc_html( $options['label'] ); ?></span>
                        <textarea name="<?php echo esc_attr( isset($options['name']) ? $options['name']:'' ); ?>" class="<?php echo esc_attr( isset($options['class']) ? $options['class']:'' ); ?>" placeholder="<?php echo esc_attr( isset($options['label']) ? $options['label']:'' ); ?>"><?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?></textarea>
                        <?php
                        break;
                    case 'color':
                        $style_to_add = '';
                        ?>
                        <span class="customize-control-title" <?php if( !empty( $style_to_add ) ) { echo 'style="'.esc_attr( $style_to_add ).'"';} ?>><?php echo esc_html( $options['label'] ); ?></span>
                        <div class="color-mienle <?php echo esc_attr($options['class']); ?>" <?php if( !empty( $style_to_add ) ) { echo 'style="'.esc_attr( $style_to_add ).'"';} ?>>
                            <input data-mienl="true" type="text" value="<?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?>" class="<?php echo esc_attr($options['class']); ?>" />
                        </div>
                        <?php
                        break;
                }
            } else { ?>
                <span class="customize-control-title"><?php echo esc_html( $options['label'] ); ?></span>
                <input name="<?php echo esc_attr( isset($options['name']) ? $options['name']:'' ); ?>" type="text" value="<?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?>" class="<?php echo esc_attr($options['class']); ?>" placeholder="<?php echo esc_attr( !empty($options['placeholder']) ? $options['placeholder']:$options['label'] ); ?>"/>
                <?php
            }
        }

        private function icon_picker_control($value = '', $show = ''){
            ?>
            <div class="social-repeater-general-control-icon" <?php if( $show === 'customizer_repeater_image' || $show === 'customizer_repeater_none' ) { echo 'style="display:none;"'; } ?>>
            <span class="customize-control-title">
                <?php echo esc_html__('Icon','crt-manage'); ?>
            </span>
                <span class="description customize-control-description">
                <?php printf( __( 'Note: Some icons may not be displayed here. You can see the full list of icons at %s', 'crt-manage' ), 'http://fontawesome.io/icons/' ); ?>
            </span>
                <div class="input-group icp-container">
                    <input data-placement="bottomRight" class="icp icp-auto" value="<?php if(!empty($value)) { echo esc_attr( $value );} ?>" type="text">
                    <span class="input-group-addon">
                    <i class="fa <?php echo esc_attr($value); ?>"></i>
                </span>
                </div>
                <?php include $this->custom_icon_container; ?>
            </div>
            <?php
        }

        private function image_control($value = '', $show = '', $attrs = array()){  ?>
            <div class="customizer-repeater-image-control" <?php if( $show === 'customizer_repeater_icon' || $show === 'customizer_repeater_none' || empty( $show ) ) { echo 'style="display:none;"'; } ?>>
                <span class="customize-control-title">
                    <?php printf(__('%s', 'crt-manage'), $attrs['label']); ?>
                </span>
                <input type="text" name="<?php echo esc_attr($attrs['name']) ?>" data-id="<?php echo (isset($attrs['data_id']) ? $attrs['data_id']:''); ?>" class="widefat custom-media-url <?php printf(__('%s', 'crt-manage'), $attrs['class']); ?>" value="<?php printf(__('%s', 'crt-manage'), esc_attr( $value )); ?>">
                <input type="button" class="button button-secondary customizer-repeater-custom-media-button" value="<?php printf(__('Upload Image', 'crt-manage'), isset($attrs['class']) ?? $attrs['class']); ?>" />
            </div>
            <?php
        }

        private function icon_type_choice($value='customizer_repeater_icon'){ ?>
            <span class="customize-control-title"><?php esc_html__('Image type','crt-manage');?></span>
            <select class="customizer-repeater-image-choice">
                <option value="customizer_repeater_icon" <?php selected($value,'customizer_repeater_icon');?>><?php echo esc_html__('Icon','crt-manage'); ?></option>
                <option value="customizer_repeater_image" <?php selected($value,'customizer_repeater_image');?>><?php echo esc_html__('Image','crt-manage'); ?></option>
                <option value="customizer_repeater_none" <?php selected($value,'customizer_repeater_none');?>><?php echo esc_html__('None','crt-manage'); ?></option>
            </select>
            <?php
        }

        private function icon_type_radio($value, $field){ ?>
            <span class="customize-control-title">
                <?php printf( __( '%s', 'crt-manage' ), $field['label']);?>
            </span>
            <div class="radio_type">
                <?php if( !empty($field['choices']) ) { ?>
                    <select class="customizer-repeater-option">
                        <?php  foreach ($field['choices'] as $name => $item) { ?>
                            <option value="<?php echo esc_attr($name); ?>" <?php selected($value,$name);?>><?php echo esc_html( $item ); ?></option>
                        <?php }; ?>
                    </select>
                <?php }; ?>
            </div>
            <?php
        }

        private function repeater_control($value = ''){
            $social_repeater = array();
            $show_del        = 0; ?>
            <span class="customize-control-title"><?php echo esc_html__( 'Social icons', 'crt-manage' ); ?></span>
            <?php
            '<span class="description customize-control-description">';
                printf(__('Note: Some icons may not be displayed here. You can see the full list of icons at %s', 'crt-manage'), 'http://fontawesome.io/icons/');
            echo '</span>';
            if(!empty($value)) {
                $social_repeater = json_decode( html_entity_decode( $value ), true );
            }
            if ( ( count( $social_repeater ) == 1 && '' === $social_repeater[0] ) || empty( $social_repeater ) ) { ?>
                <div class="customizer-repeater-social-repeater">
                    <div class="customizer-repeater-social-repeater-container">
                        <div class="customizer-repeater-rc input-group icp-container">
                            <input data-placement="bottomRight" class="icp icp-auto" value="<?php if(!empty($value)) { echo esc_attr( $value ); } ?>" type="text">
                            <span class="input-group-addon"></span>
                        </div>
                        <?php include $this->custom_icon_container; ?>
                        <input type="text" class="customizer-repeater-social-repeater-link" placeholder="<?php esc_attr_e( 'Link', 'crt-manage' ); ?>">
                        <input type="hidden" class="customizer-repeater-social-repeater-id" value="">
                        <button class="social-repeater-remove-social-item" style="display:none">
                            <?php esc_html__( 'Remove Icon', 'crt-manage' ); ?>
                        </button>
                    </div>
                    <input type="hidden" id="social-repeater-socials-repeater-colector" class="social-repeater-socials-repeater-colector" value=""/>
                </div>
                <button class="social-repeater-add-social-item button-secondary"><?php esc_html__( 'Add Icon', 'crt-manage' ); ?></button>
                <?php
            } else { ?>
                <div class="customizer-repeater-social-repeater">
                    <?php
                    foreach ( $social_repeater as $social_icon ) {
                        $show_del ++; ?>
                        <div class="customizer-repeater-social-repeater-container">
                            <div class="customizer-repeater-rc input-group icp-container">
                                <input data-placement="bottomRight" class="icp icp-auto" value="<?php if( !empty($social_icon['icon']) ) { echo esc_attr( $social_icon['icon'] ); } ?>" type="text">
                                <span class="input-group-addon"><i class="fa <?php echo esc_attr( $social_icon['icon'] ); ?>"></i></span>
                            </div>
                            <?php include $this->custom_icon_container; ?>
                            <input type="text" class="customizer-repeater-social-repeater-link"
                                   placeholder="<?php esc_attr_e( 'Link', 'crt-manage' ); ?>"
                                   value="<?php if ( ! empty( $social_icon['link'] ) ) {
                                       echo esc_url( $social_icon['link'] );
                                   } ?>">
                            <input type="hidden" class="customizer-repeater-social-repeater-id"
                                   value="<?php if ( ! empty( $social_icon['id'] ) ) {
                                       echo esc_attr( $social_icon['id'] );
                                   } ?>">
                            <button class="button-primary social-repeater-remove-social-item" style="<?php if ( $show_del == 1 ) {echo "display:none";} ?>"><?php esc_html__( 'Remove Icon', 'crt-manage' ); ?></button>
                        </div>
                        <?php
                    } ?>
                    <input type="hidden" id="social-repeater-socials-repeater-colector"
                           class="social-repeater-socials-repeater-colector"
                           value="<?php echo esc_textarea( html_entity_decode( $value ) ); ?>" />
                </div>
                <button class="social-repeater-add-social-item button-secondary"><?php esc_html__( 'Add Icon', 'crt-manage' ); ?></button>
                <?php
            }
        }

        private function repeater_fields($value = '', $data = array()) {
            $field_repeater = array('');
            if(!empty($value)) {
                $field_repeater = json_decode( html_entity_decode( $value ), true );
            }
            if($field_repeater == NULL) {
                $field_repeater = array('');
            }
            ?>
            <span class="customize-control-title"><?php printf(__('%s', 'crt-manage'), $data['label'][0]); ?></span>
            <div class="customizer-repeater-field" data-key="<?php printf(__('%s', 'crt-manage'), $data['key']); ?>">
                <?php $show_btn_delete = 0; foreach ($field_repeater as $k => $v): $show_btn_delete++; ?>
                <div class="customizer-repeater-field__group">
                    <?php
                        $c = 0;
                        foreach ($data['fields'] as $name => $item) {
                            ?>
                            <div class="customizer-repeater-field__item">
                                <?php
                                    if($item['type'] == 'textarea') {
                                        $this->input_control( array(
                                            'label' => apply_filters('repeater_input_labels_filter', sprintf(__('%s', 'crt-manage'), $item['label']), $this->id, 'custom_repeater_link_control' ),
                                            'class' => $item['class'],
                                            'name'  => $name,
                                            'type' => $item['type'],
                                            'placeholder' => !empty($item['placeholder']) ? $item['placeholder']:$item['label'],
                                        ), isset($v[$name]) ? $v[$name]:'');
                                    } elseif ($item['type'] == 'icon') {
                                        ?>
                                        <span class="customize-control-title"><?php echo esc_html( $item['label'] ); ?></span>
                                        <div class="customizer-repeater-rc input-group icp-container">
                                            <input data-placement="bottomRight" name="<?php echo esc_attr($name); ?>" class="<?php echo esc_attr($item['class']); ?> icp icp-auto" value="<?php echo isset($v[$name]) ?? $v[$name] ?>" type="text">
                                            <span class="input-group-addon"><i class="fa <?php echo esc_attr( isset($v[$name]) ?? $v[$name] ); ?>"></i></span>
                                        </div>
                                        <?php
                                        include $this->custom_icon_container;
                                    } elseif($item['type'] == 'image') {
                                        $choice = 'customizer_repeater_image';
                                        $image_url = isset($v[$name]) ? $v[$name]:'';
                                        $args = array('label' => $item['label'],'name' => $name,'class' => $item['class']);
                                        $this->image_control($image_url, $choice, $args);
                                    } elseif($item['type'] == 'choices') {
                                        ?>
                                        <span class="customize-control-title">
                                            <?php printf( __( '%s', 'crt-manage' ), $item['label']);?>
                                        </span>
                                        <div class="radio_type" style="margin: 0 0 10px;">
                                            <?php if( !empty($item['data']) ) { ?>
                                                <select class="trigger_field" name="<?php echo esc_attr($name); ?>">
                                                    <?php  foreach ($item['data'] as $key => $option) { ?>
                                                        <option value="<?php echo esc_attr($key); ?>" <?php selected(isset($v[$name]) ? $v[$name]:'',$key);?>><?php echo esc_html( $option ); ?></option>
                                                    <?php }; ?>
                                                </select>
                                            <?php }; ?>
                                        </div>
                                        <?php
                                    } else {
                                        $this->input_control( array(
                                            'label' => apply_filters('repeater_input_labels_filter', sprintf(__('%s', 'crt-manage'), $item['label']), $this->id, 'custom_repeater_link_control' ),
                                            'class' => $item['class'],
                                            'name'  => $name,
                                            'placeholder' => !empty($item['placeholder']) ? $item['placeholder']:$item['label'],
                                        ), isset($v[$name]) ? $v[$name]:'');
                                    }
                                ?>
                            </div>
                            <?php
                        }
                    ?>
                    <?php
                        $item_delete = esc_attr( 'Remove Item', 'crt-manage' );
                        if(!empty($data['label'][2])) {
                            $item_delete = $data['label'][2];
                        }
                    ?>
                    <button class="button-primary" data-button-delete="<?php echo $data['key'] ?>" style="<?php if ( $show_btn_delete == 1 ) {echo "display:none";} ?>"><?php echo $item_delete; ?></button>
                </div>
                <?php endforeach; ?>
                <input type="hidden" name="<?php echo $data['key'] ?>" class="customizer-repeater-field" value="<?php echo !empty($value) ? esc_textarea( html_entity_decode( $value ) ) : ''; ?>" />
                <?php
                $item_add = esc_attr( 'Add Item', 'crt-manage' );
                if(!empty($data['label'][1])) {
                    $item_add = $data['label'][1];
                }
                ?>
                <button class="button-add-row button-secondary" data-button="<?php echo $data['key'] ?>"><?php echo $item_add; ?></button>
            </div>
            <?php
        }
    }

    /**
     * Select Multiple
     */
    class Crt_Manage_Customize_Select_Multiple extends WP_Customize_Control {
        /**
         * The type of customize control being rendered.
         *
         * @var    string
         */
        public $type = 'select-multiple';

        /**
         * Custom classes to apply on select.
         *
         * @var string
         */
        public $custom_class = '';

        /**
         * Custom height to apply on select.
         *
         * @var string
         */
        public $custom_height = '50';

        /**
         * Hestia_Select_Multiple constructor.
         *
         * @param WP_Customize_Manager $manager Customize manager object.
         * @param string               $id Control id.
         * @param array                $args Control arguments.
         */
        public function __construct( WP_Customize_Manager $manager, $id, array $args = array() ) {
            parent::__construct( $manager, $id, $args );
            if ( array_key_exists( 'custom_class', $args ) ) {
                $this->custom_class = esc_attr( $args['custom_class'] );
            }
            if ( array_key_exists( 'height', $args ) ) {
                $this->custom_height = esc_attr( $args['height'] );
            }
        }

        /**
         * Add custom parameters to pass to the JS via JSON.
         *
         * @since  1.1.40
         * @access public
         * @return array
         */
        public function json() {
            $json                 = parent::json();
            $json['choices']      = $this->choices;
            $json['link']         = $this->get_link();
            $json['value']        = (array) $this->value();
            $json['id']           = $this->id;
            $json['custom_class'] = $this->custom_class;
            $json['custom_height'] = $this->custom_height;

            return $json;
        }


        /**
         * Underscore JS template to handle the control's output.
         *
         * @since  1.1.40
         * @access public
         * @return void
         */
        public function content_template() {
            ?>
            <#
            if ( ! data.choices ) {
            return;
            } #>

            <label>
                <# if ( data.label ) { #>
                <span class="customize-control-title">{{ data.label }}</span>
                <# } #>

                <# if ( data.description ) { #>
                <span class="description customize-control-description">{{{ data.description }}}</span>
                <# } #>

                <#
                var custom_class = ''
                if ( data.custom_class ) {
                custom_class = 'class='+data.custom_class
                } #>

                <#
                var custom_height = ''
                if ( data.custom_height ) {
                custom_height = 'style=height:'+data.custom_height+'px'
                } #>

                <select multiple="multiple" {{{ data.link }}} {{ custom_class }} {{ custom_height }}>
                    <# _.each( data.choices, function( label, choice ) {
                    var selected = data.value.includes( choice.toString() ) ? 'selected="selected"' : ''
                    #>
                    <option value="{{ choice }}" {{ selected }} >{{ label }}</option>
                    <# } ) #>
                </select>
            </label>
            <?php
        }
    }

    /**
     * Select Multiple
     */
    class Crt_Manage_Customize_Control_Radio_Image extends WP_Customize_Control {
        /**
         * The type of customize control being rendered.
         *
         * @since 1.1.24
         * @var   string
         */
        public $type = 'radio-image';
        /**
         * Displays the control content.
         *
         * @since  1.1.24
         * @access public
         * @return void
         */
        public function render_content() {
            /* If no choices are provided, bail. */
            if ( empty( $this->choices ) ) {
                return;
            } ?>
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif; ?>
            <?php if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>
            <div id="<?php echo esc_attr( "input_{$this->id}" ); ?>">
                <?php foreach ( $this->choices as $value => $args ) : ?>
                    <input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( "_customize-radio-{$this->id}" ); ?>" id="<?php echo esc_attr( "{$this->id}-{$value}" ); ?>" <?php $this->link(); ?> <?php checked( $this->value(), $value ); ?> />
                    <label for="<?php echo esc_attr( "{$this->id}-{$value}" ); ?>">
                        <?php if ( ! empty( $args['label'] ) ) { ?>
                            <span class="screen-reader-text"><?php echo esc_html( $args['label'] ); ?></span>
                            <?php
                        }
                        ?>
                        <img src="<?php echo esc_url( sprintf( $args['url'], get_template_directory_uri(), get_stylesheet_directory_uri() ) ); ?>"
                            <?php
                            if ( ! empty( $args['label'] ) ) {
                                echo 'alt="' . esc_attr( $args['label'] ) . '"'; }
                            ?>
                        />
                    </label>
                <?php endforeach; ?>
            </div><!-- .image -->
            <script type="text/javascript">
                jQuery( document ).ready( function() {
                    jQuery( '#<?php echo esc_attr( "input_{$this->id}" ); ?>' ).buttonset();
                } );
            </script>
            <?php
        }
        /**
         * Loads the jQuery UI Button script and hooks our custom styles in.
         *
         * @since  1.1.24
         * @access public
         * @return void
         */
        public function enqueue() {
            wp_enqueue_script( 'jquery-ui-button' );
            add_action( 'customize_controls_print_styles', array( $this, 'print_styles' ) );
        }
        /**
         * Outputs custom styles to give the selected image a visible border.
         *
         * @since  1.1.24
         * @access public
         * @return void
         */
        public function print_styles() {
            ?>
            <style type="text/css" id="hybrid-customize-radio-image-css">
                .customize-control-radio-image .ui-buttonset {
                    text-align: center;
                }
                .customize-control-radio-image label {
                    display: inline-block;
                    max-width: 33.3%;
                    padding: 3px;
                    font-size: inherit;
                    line-height: inherit;
                    height: auto;
                    cursor: pointer;
                    border-width: 0;
                    -webkit-appearance: none;
                    -webkit-border-radius: 0;
                    border-radius: 0;
                    white-space: nowrap;
                    -webkit-box-sizing: border-box;
                    -moz-box-sizing: border-box;
                    box-sizing: border-box;
                    color: inherit;
                    background: none;
                    -webkit-box-shadow: none;
                    box-shadow: none;
                    vertical-align: inherit;
                }
                .customize-control-radio-image label:first-of-type {
                    float: left;
                }
                .customize-control-radio-image label:nth-of-type(n + 3){
                    float: right;
                }
                .customize-control-radio-image label:hover {
                    background: none;
                    border-color: inherit;
                    color: inherit;
                }
                .customize-control-radio-image label:active {
                    background: none;
                    border-color: inherit;
                    -webkit-box-shadow: none;
                    box-shadow: none;
                    -webkit-transform: none;
                    -ms-transform: none;
                    transform: none;
                }
                .customize-control-radio-image img { border: 1px solid transparent; }
                .customize-control-radio-image .ui-state-active img {
                    border-color: #5b9dd9;
                }
            </style>
            <?php
        }
    }

    class Crt_Manage_Customizer_Tabs_Control extends WP_Customize_Control {

        /**
         * Hestia_Customize_Control_Tabs constructor.
         *
         * @param WP_Customize_Manager $manager wp_customize manager.
         * @param string               $id      control id.
         * @param array                $args    public parameters for control.
         */
        public function __construct( WP_Customize_Manager $manager, $id, array $args = array() ) {
            parent::__construct( $manager, $id, $args );

            add_action( 'customize_preview_init', array( $this, 'partials_helper_script_enqueue' ) );

            if ( ! empty( $this->tabs ) ) {
                foreach ( $this->tabs as $value => $args ) {
                    $this->controls[ $value ] = $args['controls'];
                }
            }
        }

        /**
         * Controls array from tabs.
         *
         * @var array
         */
        public $controls = array();

        /**
         * The type of customize control being rendered.
         *
         * @var   string
         */
        public $type = 'interface-tabs';

        /**
         * The type refresh being used.
         *
         * @var   string
         */
        public $transport = 'postMessage';

        /**
         * The priority of the control.
         *
         * @var   string
         */
        public $priority = -10;

        /**
         * The tabs with keys of the controls that are under each tab.
         *
         * @var array
         */
        public $tabs;

        /**
         * Displays the control content.
         *
         * @access public
         * @return void
         */
        public function render_content() {
            /* If no tabs are provided, bail. */
            if ( empty( $this->tabs ) || ! $this->more_than_one_valid_tab() ) {
                return;
            }

            $output = '';
            $i      = 0;

            $output .= '<div class="tabs-control" id="input_' . esc_attr( $this->id ) . '">';
            foreach ( $this->tabs as $value => $args ) {
                if ( ! empty( $args['controls'] ) && ( $this->tab_has_controls( $args['controls'] ) ) ) {
                    $controls_attribute = json_encode( $args['controls'] );

                    $output .= '<div class="customizer-tab">';

                    $output .= '<input type="radio"';
                    $output .= 'value="' . esc_attr( $value ) . '" ';
                    $output .= 'name="' . esc_attr( "_customize-radio-{$this->id}" ) . '" ';
                    $output .= 'id="' . esc_attr( "{$this->id}-{$value}" ) . '" ';
                    $output .= 'data-controls="' . esc_attr( $controls_attribute ) . '" ';
                    if ( $i === 0 ) {
                        $output .= 'checked="true" ';
                    }
                    $i ++;
                    $output .= '/><!-- /input -->';

                    $label_classes = '';
                    foreach ( $args['controls'] as $control_id ) {
                        $label_classes .= esc_attr( $control_id . ' ' );
                    }

                    $output .= '<label class = "' . $label_classes . '" ';
                    $output .= 'for="' . esc_attr( "{$this->id}-{$value}" ) . '">';
                    if ( ! empty( $args['nicename'] ) ) {
                        $output .= '<span class="screen-reader-text">' . esc_html( $args['nicename'] ) . '</span>';
                    }
                    if ( ! empty( $args['icon'] ) ) {
                        $output .= '<i class="fa fa-' . esc_attr( $args['icon'] ) . '"></i>';
                    }
                    if ( ! empty( $args['nicename'] ) ) {
                        $output .= $args['nicename'];
                    }
                    $output .= '</label>';
                    $output .= '</div>';
                }
            }
            $output .= '</div>';

            echo $output;
        }
        /**
         * Loads the scripts and hooks our custom styles in.
         *
         * @since  1.1.45
         * @access public
         * @return void
         */
        public function enqueue() {

            if ( empty( $this->tabs ) || ! $this->more_than_one_valid_tab() ) {
                return;
            }
            wp_enqueue_script( 'tabs-control-script', CRT_MANAGE_URI . '/assets/js/script.js', array( 'jquery' ), CRT_MANAGE_VERSION, true );
            wp_enqueue_style( 'tabs-control-style', CRT_MANAGE_URI . '/assets/css/tab-style.css', null, CRT_MANAGE_VERSION );

        }

        /**
         * Enqueue the partials handler script that works synchronously with the hestia-tabs-control-script
         */
        public function partials_helper_script_enqueue() {
            wp_enqueue_script( 'tabs-addon-script', CRT_MANAGE_URI . '/assets/js/customizer-addon-script.js', array( 'jquery' ), CRT_MANAGE_VERSION, true );
        }

        /**
         * Verify if the tab has valid controls.
         *
         * Meant to foolproof the control if a tab has no valid controls.
         * Returns false if there are no valid controls inside the tab.
         *
         * @param controls array $controls_array the array of controls.
         *
         * @return bool
         */
        protected final function tab_has_controls( $controls_array ) {
            $i = 0;
            foreach ( $controls_array as $control ) {
                $setting = $this->manager->get_setting( $control );
                if ( ! empty( $setting ) ) {
                    $i++;
                }
            }
            if ( $i === 0 ) {
                return false;
            }
            return true;
        }

        /**
         * Verify if there's more than one valid tab.
         *
         * @return bool
         */
        protected final function more_than_one_valid_tab() {
            $i = 0;
            foreach ( $this->tabs as $tab ) {
                if ( $this->tab_has_controls( $tab['controls'] ) ) {
                    $i++;
                }
            }
            if ( $i > 1 ) {
                return true;
            }
            return false;
        }
    }

}

if ( class_exists( 'WP_Customize_Section' ) ) {
	/**
	 * Upsell section
	 */
	class Crt_Manage_Custom_Section extends WP_Customize_Section {
		/**
		 * The type of control being rendered
		 */
		public $type = 'crt-manage-upsell';

		/**
		 * The Upsell button text
		 */
		public $button_text = '';

		/**
		 * The Upsell URL
		 */
		public $url = '';

		/**
		 * The background color for the control
		 */
		public $background_color = '';

		/**
		 * The text color for the control
		 */
		public $text_color = '';

		/**
		 * Render the section, and the controls that have been added to it.
		 */
		protected function render() {
			?>
			<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="crt_manage_upsell_section accordion-section control-section control-section-<?php echo esc_attr( $this->id ); ?> cannot-expand">
				<h3 class="accordion-section-title crt-manage-accordion-section-title" tabindex="0" style="">
                    <?php echo esc_html( $this->title ); ?>
					<a href="<?php echo esc_url( $this->url ); ?>" class="button button-secondary crt-manage__button-buy" target="_blank" style="margin-top: -3px;"><?php echo esc_html( $this->button_text ); ?></a>
				</h3>
			</li>
			<?php
		}
	}
}

if ( class_exists( 'WP_Customize_Control' ) ) {
    class Crt_Manage_WP_Customize_TinyMCE_Control extends WP_Customize_Control {
        /**
         * The type of control being rendered
         */
        public $type = 'tinymce_editor';

        /**
         * Pass our TinyMCE toolbar config to JavaScript
         */
        public function to_json() {
            parent::to_json();

            $this->json['tinymce_toolbar1']      = isset( $this->input_attrs['toolbar1'] ) ? esc_attr( $this->input_attrs['toolbar1'] ) : 'bold italic bullist numlist alignleft aligncenter alignright link';
            $this->json['tinymce_toolbar2']      = isset( $this->input_attrs['toolbar2'] ) ? esc_attr( $this->input_attrs['toolbar2'] ) : '';
            $this->json['tinymce_media_buttons'] = isset( $this->input_attrs['mediaButtons'] ) && ( $this->input_attrs['mediaButtons'] === true ) ? true : false;
            $this->json['tinymce_height']        = isset( $this->input_attrs['height'] ) ? esc_attr( $this->input_attrs['height'] ) : 200;
        }

        /**
         * Render the control in the customizer
         */
        public function render_content() {
            ?>
            <div class="tinymce-control">
				<span class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
				</span>

                <?php if ( ! empty( $this->description ) ) : ?>
                    <span class="customize-control-description">
						<?php echo esc_html( $this->description ); ?>
					</span>
                <?php endif; ?>

                <textarea id="<?php echo esc_attr( $this->id ); ?>" class="customize-control-tinymce-editor" <?php $this->link(); ?>><?php echo esc_attr( $this->value() ); ?></textarea>
                <script>
                    jQuery( document ).ready( function ( $ ) {
                        function tinyMCE_setup() {
                            var tinyMCEToolbar1 = _wpCustomizeSettings.controls[$( this ).attr( 'id' )].tinymce_toolbar1;
                            var tinyMCEToolbar2 = _wpCustomizeSettings.controls[$( this ).attr( 'id' )].tinymce_toolbar2;
                            var tinyMCEMediaButtons = _wpCustomizeSettings.controls[$( this ).attr( 'id' )].tinymce_media_buttons;
                            var tinyMCEheight = _wpCustomizeSettings.controls[$( this ).attr( 'id' )].tinymce_height;

                            wp.editor.initialize( $( this ).attr( 'id' ), {
                                tinymce: {
                                    wpautop: true,
                                    toolbar1: tinyMCEToolbar1,
                                    toolbar2: tinyMCEToolbar2,
                                    height: tinyMCEheight
                                },
                                quicktags: true,
                                mediaButtons: tinyMCEMediaButtons
                            } );
                        }

                        function initialize_tinyMCE( event, editor ) {
                            editor.on( 'change', function () {
                                tinyMCE.triggerSave();
                                $( "#".concat( editor.id ) ).trigger( 'change' );
                            } );
                        }

                        $( document ).on( 'tinymce-editor-init', initialize_tinyMCE );
                        $( '.customize-control-tinymce-editor' ).each( tinyMCE_setup );
                    } );
                </script>
            </div>
            <?php
        }
    }
}