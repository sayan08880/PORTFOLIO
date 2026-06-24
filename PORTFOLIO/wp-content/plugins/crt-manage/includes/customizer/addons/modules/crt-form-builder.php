<?php
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Form_Builder extends Widget_Base {
	
	public function get_name() {
		return 'crt-form-builder';
	}

	public function get_title() {
		return esc_html__( 'Form Builder', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-form-horizontal';
	}

	public function get_categories() {
        return [ 'crt_manage_theme' ];
    }

	public function get_keywords() {
		return [ 'cf7', 'contact form 7', 'caldera forms', 'ninja forms', 'wpforms', 'wp forms', 'email', 'mail' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_style_depends() {
		return [ 'crt-loading-animations-css' ];
	}

    public function get_script_depends() {
        return [ 'crt-form-builder' ];
    }

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
        // return 'https://crthemes.com/contact/?ref=rea-plugin-panel-forms-help-btn';
    		return 'https://crthemes.com/contact';
    }

	// Allow overwrite the control_id with a prefix, @see Email2
	protected function get_control_id( $control_id ) {
		return $control_id;
	}

	public function get_label() {
		return esc_html__( 'Email', 'crt-manage' );
	}

	public static function get_site_domain() {
		return str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	}

	public function submit_action_args() {
        $actions_options = [
            'email' => 'Email',
            'redirect' => 'Redirect',
            'submissions' => 'Submission',
            'mailchimp' => 'Mailchimp',
            'webhook' => 'Webhook'
        ];

		return $actions_options;
	}

	public function register_settings_section_submissions( $widget ) {
		$widget->start_controls_section(
			$this->get_control_id( 'section_submissions' ),
			[
				'label' => esc_html__( 'Submissions', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'submit_actions' => 'submissions',
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'submissions_action_message' ),
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf(
					__(
						'View Submissions in CRT Builder > <a href="%s" target="_blank">Submissions</a>',
						'crt-manage'
					),
					self_admin_url( 'edit.php?post_type=crt_submissions' )
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$widget->end_controls_section();
	}

	public function register_settings_section_webhook( $widget ) {
		$widget->start_controls_section(
			$this->get_control_id( 'section_webhook' ),
			[
				'label' => esc_html__( 'Webhook', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'submit_actions' => 'webhook',
				],
			]
		);

		$widget->add_control(
			'webhook_url',
			[
				'label' => esc_html__( 'Webhook URL', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'https://your-webhook-url.com', 'crt-manage' ),
				'ai' => [
					'active' => false,
				],
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'separator' => 'before',
				'description' => esc_html__( 'Enter the webhook URL (e.g. Zapier) that will receive the submitted data.', 'crt-manage' ),
				'render_type' => 'none',
			]
		);

		$widget->end_controls_section();
	}

	public function register_settings_section_email( $widget ) {
		$widget->start_controls_section(
			$this->get_control_id( 'section_email' ),
			[
				'label' => $this->get_label(),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'submit_actions' => 'email',
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_to' ),
			[
				'label' => esc_html__( 'To', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => get_option( 'admin_email' ),
				'label_block' => true,
				'title' => esc_html__( 'Separate emails with commas', 'crt-manage' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		// maybe esc_html not necessary
		/* translators: %s: Site title. */
		$default_message = sprintf( esc_html__( 'New message from %s', 'crt-manage' ), get_option( 'blogname' ) );

		$widget->add_control(
			$this->get_control_id( 'email_subject' ),
			[
				'label' => esc_html__( 'Subject', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_message,
				'placeholder' => $default_message,
				'label_block' => true,
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_content' ),
			[
				'label' => esc_html__( 'Message', 'crt-manage' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '[all-fields]',
				'placeholder' => '[all-fields]',
				'description' => sprintf(
					esc_html__( 'By default, form sends all fields. To modify this behaviour, copy the shortcode you wish from fields and paste it instead of %s.', 'crt-manage' ),
					'<code>[all-fields]</code>'
				),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$site_domain = $this->get_site_domain();

		$widget->add_control(
			$this->get_control_id( 'email_from' ),
			[
				'label' => esc_html__( 'From Email', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'description' => esc_html__( 'Shortcode like [id="email"] can be inserted according ID of the associated mail field.', 'crt-manage' ),
				'default' => 'email@' . $site_domain,
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_from_name' ),
			[
				'label' => esc_html__( 'From Name', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => get_bloginfo( 'name' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_reply_to' ),
			[
				'label' => esc_html__( 'Reply To', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'email@' . $site_domain,
				'render_type' => 'none'
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_to_cc' ),
			[
				'label' => esc_html__( 'Cc', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__( 'Separate emails with commas', 'crt-manage' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_to_bcc' ),
			[
				'label' => esc_html__( 'Bcc', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__( 'Separate emails with commas', 'crt-manage' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'form_metadata' ),
			[
				'label' => esc_html__( 'Meta Data', 'crt-manage' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'separator' => 'before',
				'default' => [
					'date',
					'time',
					'credit'
				],
				'options' => [
					'date' => esc_html__( 'Date', 'crt-manage' ),
					'time' => esc_html__( 'Time', 'crt-manage' ),
					'page_url' => esc_html__( 'Page URL', 'crt-manage' ),
					'page_title' => esc_html__( 'Page Title', 'crt-manage' ),
					'user_agent' => esc_html__( 'User Agent', 'crt-manage' ),
					'remote_ip' => esc_html__( 'Remote IP', 'crt-manage' ),
					'credit' => esc_html__( 'Credit', 'crt-manage' ),
				],
				'render_type' => 'none',
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_content_type' ),
			[
				'label' => esc_html__( 'Send As', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'html',
				'render_type' => 'none',
				'options' => [
					'html' => esc_html__( 'HTML', 'crt-manage' ),
					'plain' => esc_html__( 'Plain', 'crt-manage' ),
				],
			]
		);

		$widget->end_controls_section();
	}

	public function register_settings_section_redirect( $widget ) {
		$widget->start_controls_section(
			'section_redirect',
			[
				'label' => esc_html__( 'Redirect', 'crt-manage' ),
				'condition' => [
					'submit_actions' => 'redirect',
				],
			]
		);

		$widget->add_control(
			'redirect_to',
			[
				'label' => esc_html__( 'Redirect To', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'crt-manage' ),
				'label_block' => true
			]
		);

		$widget->end_controls_section();
	}

	public function register_settings_section_mailchimp() {

		// Tab: Content ==============
		// Section: Settings ----------
		$this->start_controls_section(
			'section_mailchimp',
			[
				'label' => esc_html__( 'Mailchimp', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'submit_actions' => 'mailchimp'
				]
			]
		);

		$this->add_control(
			'maichimp_audience',
			[
				'label' => esc_html__( 'Select Audience', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'def',
				// 'render_type' => 'template',
				'options' => Utilities::get_mailchimp_lists(),
			]
		);

		// If we build it, needs further logic
		$this->add_control(
			'mailchimp_groups',
			[
				'label' => esc_html__( 'Groups', 'crt-manage' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => Utilities::get_mailchimp_groups(),
				// 'render_type' => 'template',
				'label_block' => true,
			]
		);

		if ( '' == get_option('crt_mailchimp_api_key') ) {
			$this->add_control(
				'mailchimp_key_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( 'Navigate to <strong><a href="%s" target="_blank">Dashboard > %s > Integrations</a></strong> to set up <strong>MailChimp API Key</strong>.', 'crt-manage' ), admin_url( 'admin.php?page=crt-addons&tab=crt_tab_settings' ), Utilities::get_plugin_name() ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control(
			'mailchimp_fields',
			[
				'label' => esc_html__( 'Fields', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'email_field',
			[
				'label' => esc_html__( 'Email', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->add_control(
			'first_name_field',
			[
				'label' => esc_html__( 'First Name', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->add_control(
			'last_name_field',
			[
				'label' => esc_html__( 'Last Name', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->add_control(
			'phone_field',
			[
				'label' => esc_html__( 'Phone', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->add_control(
			'birthday_field',
			[
				'label' => esc_html__( 'Birthday', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->add_control(
			'address_field',
			[
				'label' => esc_html__( 'Address', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->add_control(
			'country_field',
			[
				'label' => esc_html__( 'Country', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->add_control(
			'city_field',
			[
				'label' => esc_html__( 'City', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->add_control(
			'state_field',
			[
				'label' => esc_html__( 'State', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->add_control(
			'zip_field',
			[
				'label' => esc_html__( 'Zip', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->end_controls_section();

	}
    
    public $last_prev_btn_text;
	
	protected function register_controls() {

		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => esc_html__( 'Fields', 'crt-manage' ),
			]
		);

		$repeater = new Repeater();

		$field_types = [
			'text' => esc_html__( 'Text', 'crt-manage' ),
			'textarea' => esc_html__( 'Textarea', 'crt-manage' ),
			'email' => esc_html__( 'Email', 'crt-manage' ),
			'url' => esc_html__( 'URL', 'crt-manage' ),
			'number' => esc_html__( 'Number', 'crt-manage' ),
			'tel' => esc_html__( 'Tel', 'crt-manage' ),
			'radio' => esc_html__( 'Radio', 'crt-manage' ),
			'select' => esc_html__( 'Select', 'crt-manage' ),
			'checkbox' => esc_html__( 'Checkbox', 'crt-manage' ),
			'date' => esc_html__( 'Date', 'crt-manage' ),
			'time' => esc_html__( 'Time', 'crt-manage' ),
			'upload' => esc_html__( 'File Upload', 'crt-manage' ),
			'password' => esc_html__( 'Password', 'crt-manage' ),
			'html' => esc_html__( 'HTML', 'crt-manage' ),
			'recaptcha-v3' => esc_html__( 'reCAPTCHA V3', 'crt-manage'),
			'hidden' => esc_html__( 'Hidden', 'crt-manage' ),
			'step' => esc_html__( 'Step', 'crt-manage' ),
		];

		$repeater->add_control(
			'field_type',
			[
				'label' => esc_html__( 'Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			]
		);
		
		$repeater->add_control(
			'field_step_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__('Step should be a First element of fields group. Ex: Step 1 followed by Field 1, Field 2. Step 2 followed by Field 3, Field 4.', 'crt-manage'),
				'content_classes' => 'elementor-panel-alert',
				'condition' => [
					'field_type' => 'step'
				]
			]
		);

		if ( '' == get_option('crt_recaptcha_v3_site_key') ) {
			$repeater->add_control(
				'recaptcha_key_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( 'Navigate to <strong><a href="%s" target="_blank">Dashboard > %s > Integrations</a></strong> to set up <strong>reCaptcha Site Key</strong>.', 'crt-manage' ), admin_url( 'admin.php?page=crt-addons&tab=crt_tab_settings' ), Utilities::get_plugin_name() ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
					'condition' => [
						'field_type' => 'recaptcha-v3'
					]
				]
			);
		}

		$repeater->add_control(
			'field_label',
			[
				'label' => esc_html__( 'Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'alt_label',
			[
				'label' => esc_html__( 'Alternative Label', 'crt-manage' ),
				'description' => esc_html__( 'This Label will be used in Submit Actions instead of the Main Label.', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'field_sub_label',
			[
				'label' => esc_html__( 'Sub Label', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'field_type' => 'step'
				]
			]
		);

		$repeater->add_control(
			'previous_button_text',
			[
				'label' => esc_html__( 'Previous Button', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Previous',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'field_type' => 'step'
				]
			]
		);

		$repeater->add_control(
			'next_button_text',
			[
				'label' => esc_html__( 'Next Button', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Next',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'field_type' => 'step'
				]
			]
		);

		$repeater->add_control(
			'step_icon',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
				'default' => [
					'value' => 'far fa-edit',
					'library' => 'regular'
				],
				'condition' => [
					'field_type' => 'step'
				]
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'tel',
								'text',
								'email',
								'textarea',
								'number',
								'url',
								'password',
							],
						],
					],
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'field_value',
			[
				'label' => esc_html__( 'Default Value', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'text',
								'email',
								'textarea',
								'url',
								'tel',
								'radio',
								'select',
								'number',
								'date',
								'time',
								'hidden',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_id',
			[
				'label' => esc_html__( 'ID', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'description' => esc_html__( 'Element ID should be unique and not used elsewhere in this widget.', 'crt-manage' ),
				'default' => '',
				'render_type' => 'none',
				'required' => true,
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$shortcode_value = '{{ view.container.settings.get( \'field_id\' ) }}';
		
		$repeater->add_control(
			'shortcode',
			[
				'label' => esc_html__( 'Shortcode', 'crt-manage' ),
				'type' => Controls_Manager::RAW_HTML,
				'classes' => 'forms-field-shortcode',
				'raw' => '<input class="crt-form-field-shortcode" value=\'[id="' . $shortcode_value . '"]\' readonly />'
			]
		);

		$repeater->add_control(
			'required',
			[
				'label' => esc_html__( 'Required', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'recaptcha',
								'recaptcha-v3',
								'hidden',
								'html',
								'step',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'allow_multiple_upload',
			[
				'label' => esc_html__( 'Multiple', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'field_type' => 'upload'
				],
			]
		);

		$max_file_size = wp_max_upload_size() / pow( 1024, 2 ); //MB

		$repeater->add_control(
			'file_size',
			[
				'label' => esc_html__( 'File Size (MB)', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => $max_file_size,
				'step' => 1,
				'description' => esc_html__( 'Max upload size allowed is '. $max_file_size .'MB. Please contact your hosting to increase it.', 'crt-manage' ),
				'condition' => [
					'field_type' => 'upload'
				]
			]
		);

		$repeater->add_control(
			'file_types',
			[
				'label' => esc_html__( 'File Types', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter the comma separated file types to allow.', 'crt-manage' ),
				'condition' => [
					'field_type' => 'upload',
				]
			]
		);

		$repeater->add_control(
			'field_options',
			[
				'label' => esc_html__( 'Options', 'crt-manage' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'description' => esc_html__( 'Insert options in separate lines. For different label/values separate them with a pipe char ("|"). Like: First Option|f_option', 'crt-manage' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'select',
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'allow_multiple',
			[
				'label' => esc_html__( 'Multiple Selection', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'select_size',
			[
				'label' => esc_html__( 'Rows', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 2,
				'step' => 1,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
						[
							'name' => 'allow_multiple',
							'value' => 'true',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'inline_list',
			[
				'label' => esc_html__( 'Inline List', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'crt-inline-sub-group',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_html',
			[
				'label' => esc_html__( 'HTML', 'crt-manage' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'html',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Column Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'hidden',
								'recaptcha',
								'recaptcha-v3',
								'step',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'rows',
			[
				'label' => esc_html__( 'Rows', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 7,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'textarea',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_size', [
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => esc_html__( 'Normal', 'crt-manage' ),
					'compact' => esc_html__( 'Compact', 'crt-manage' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_style',
			[
				'label' => esc_html__( 'Style', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => esc_html__( 'Light', 'crt-manage' ),
					'dark' => esc_html__( 'Dark', 'crt-manage' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		// $repeater->add_control(
		// 	'recaptcha_badge', [
		// 		'label' => esc_html__( 'Badge', 'crt-manage' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'default' => 'bottomright',
		// 		'options' => [
		// 			'bottomright' => esc_html__( 'Bottom Right', 'crt-manage' ),
		// 			'bottomleft' => esc_html__( 'Bottom Left', 'crt-manage' ),
		// 			'inline' => esc_html__( 'Inline', 'crt-manage' ),
		// 		],
		// 		'description' => esc_html__( 'To view the validation badge, switch to preview mode', 'crt-manage' ),
		// 		'conditions' => [
		// 			'terms' => [
		// 				[
		// 					'name' => 'field_type',
		// 					'value' => 'recaptcha-v3',
		// 				],
		// 			],
		// 		],
		// 	]
		// );

		$repeater->add_control(
			'css_classes',
			[
				'label' => esc_html__( 'CSS Classes', 'crt-manage' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'title' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'crt-manage' ),
			]
		);

		$this->add_control(
			'form_fields',
			[
				// 'type' => Fields_Repeater::CONTROL_TYPE,
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'field_id' => 'name',
						'field_type' => 'text',
						'field_label' => esc_html__( 'Name', 'crt-manage' ),
						'placeholder' => esc_html__( 'Name', 'crt-manage' ),
						'width' => '100',
						'dynamic' => [
							'active' => true,
						],
					],
					[
						'field_id' => 'email',
						'field_type' => 'email',
						'required' => 'true',
						'field_label' => esc_html__( 'Email', 'crt-manage' ),
						'placeholder' => esc_html__( 'Email', 'crt-manage' ),
						'width' => '100',
					],
					[
						'field_id' => 'message',
						'field_type' => 'textarea',
						'field_label' => esc_html__( 'Message', 'crt-manage' ),
						'placeholder' => esc_html__( 'Message', 'crt-manage' ),
						'width' => '100',
					],
				],
				'title_field' => '{{{ field_label }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_buttons',
			[
				'label' => esc_html__( 'Buttons', 'crt-manage' ),
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => esc_html__( 'Column Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-field-group.crt-form-field-type-submit' => 'width: {{SIZE}}%;',
					'{{WRAPPER}} .crt-stp-btns-wrap' => 'width: {{SIZE}}%;'
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'buttons_width',
			[
				'label' => esc_html__( 'Step Buttons Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],				
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-step-prev' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-step-next' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-step-tab .crt-button' => 'width: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'button_distance',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'em' => [
						'min' => 0,
						'max' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-step-prev' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-step-next' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-button[type="submit"]' => 'margin-left: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors_dictionary' => [
					'left' => 'margin-left: 0; margin-right: auto;',
					'center' => 'margin-left: auto; margin-right: auto;',
					'right' => 'margin-left: auto; margin-right: 0;'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-stp-btns-wrap' => '{{VALUE}}',
					'{{WRAPPER}} .crt-step-tab:first-of-type .crt-step-next' => '{{VALUE}}',
				],
			]
		);

		// $this->add_control(
		// 	'heading_steps_buttons',
		// 	[
		// 		'label' => esc_html__( 'Step Buttons', 'crt-manage' ),
		// 		'type' => Controls_Manager::HEADING,
		// 		'separator' => 'before',
		// 	]
		// );

		// $this->add_control(
		// 	'step_next_label',
		// 	[
		// 		'label' => esc_html__( 'Next', 'crt-manage' ),
		// 		'type' => Controls_Manager::TEXT,
		// 		'dynamic' => [
		// 			'active' => true,
		// 		],
		// 		'frontend_available' => true,
		// 		'render_type' => 'none',
		// 		'default' => esc_html__( 'Next', 'crt-manage' ),
		// 		'placeholder' => esc_html__( 'Next', 'crt-manage' ),
		// 	]
		// );

		// $this->add_control(
		// 	'step_previous_label',
		// 	[
		// 		'label' => esc_html__( 'Previous', 'crt-manage' ),
		// 		'type' => Controls_Manager::TEXT,
		// 		'dynamic' => [
		// 			'active' => true,
		// 		],
		// 		'frontend_available' => true,
		// 		'render_type' => 'none',
		// 		'default' => esc_html__( 'Previous', 'crt-manage' ),
		// 		'placeholder' => esc_html__( 'Previous', 'crt-manage' ),
		// 	]
		// );

		$this->add_control(
			'heading_submit_button',
			[
				'label' => esc_html__( 'Submit Button', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Submit', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Send', 'crt-manage' ),
				'placeholder' => esc_html__( 'Send', 'crt-manage' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'selected_button_icon',
			[
				'label' => esc_html__( 'Icon', 'crt-manage' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'crt-manage' ),
					'right' => esc_html__( 'After', 'crt-manage' ),
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .crt-button .crt-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-button .crt-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label' => esc_html__( 'Button ID', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'crt-manage' ),
				'description' => esc_html__( 'Element ID should be unique and not used elsewhere in this widget', 'crt-manage' ),
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		
		$this->start_controls_section(
			'section_form_settings',
			[
				'label' => esc_html__( 'Settings', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT
			]
		);

		$this->add_control(
			'form_name',
			[
				'label' => esc_html__( 'Form Name', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'New Form', 'crt-manage' ),
				'placeholder' => esc_html__( 'Form Name', 'crt-manage' ),
			]
		);

		$this->add_control(
			'form_id',
			[
				'label' => esc_html__( 'Form ID', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => 'form_id',
				'description' => esc_html__( 'Form ID should be unique and shouldn\'t contain spaces', 'crt-manage' ),
				'separator' => 'after',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'success_message',
			[
				'label' => esc_html__( 'Success Message', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Submission successful', 'crt-manage'),
				'placeholder' => esc_html__('Submission successful', 'crt-manage'),
				'label_block' => true,
				'frontend_available' => true,
				// 'condition' => [
				// 	'custom_messages!' => '',
				// ],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'error_message',
			[
				'label' => esc_html__( 'Error Message', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Submission failed', 'crt-manage'),
				'placeholder' => esc_html__('Submission failed', 'crt-manage'),
				'label_block' => true,
				'frontend_available' => true,
				// 'condition' => [
				// 	'custom_messages!' => '',
				// ],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => esc_html__( 'Show Field Labels', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'crt-manage' ),
				'label_off' => esc_html__( 'Hide', 'crt-manage' ),
				'return_value' => 'true',
				'default' => 'true',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_placeholders',
			[
				'label' => esc_html__( 'Show Placeholders', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'crt-manage' ),
				'label_off' => esc_html__( 'Hide', 'crt-manage' ),
				'return_value' => 'true',
				'default' => 'true'
			]
		);

		$this->add_control(
			'label_position',
			[
				'label' => esc_html__( 'Label Position', 'crt-manage' ),
				'type' => Controls_Manager::HIDDEN,
				'options' => [
					'above' => esc_html__( 'Above', 'crt-manage' ),
					'inline' => esc_html__( 'Inline', 'crt-manage' ),
				],
				'default' => 'above',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'mark_required',
			[
				'label' => esc_html__( 'Show Required Mark', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'crt-manage' ),
				'label_off' => esc_html__( 'Hide', 'crt-manage' ),
				'default' => '',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_integration',
			[
				'label' => esc_html__( 'Actions', 'crt-manage' ),
			]
		);
		// $actions = Module::instance()->actions_registrar->get();

		$default_submit_actions = [ 'email' ];

		$this->add_control(
			'submit_actions',
			[
				'label' => esc_html__( 'Add Action', 'crt-manage' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->submit_action_args(),
				'render_type' => 'none',
				'label_block' => true,
				'default' => $default_submit_actions,
				'description' => esc_html__( 'Select actions to be executed following a user\'s form submission (e.g., send an email notification). Upon choosing an action, its settings will appear below.', 'crt-manage' ),
			]
		);

		$this->end_controls_section();

		$this->register_settings_section_submissions($this);

		$this->register_settings_section_email($this);

		$this->register_settings_section_webhook($this);

		$this->register_settings_section_redirect($this);

		$this->register_settings_section_mailchimp();

		$this->start_controls_section(
			'section_form_step_settings',
			[
				'label' => esc_html__( 'Steps', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT
			]
		);

		$this->add_control(
			'step_type',
			[
				'label' => esc_html__( 'Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'render_type' => 'template',
				'options' => [
					'none' => 'None',
					'text' => 'Label',
					'icon' => 'Icon',
					'number' => 'Number',
					'progress_bar' => 'Progress Bar',
					'number_text' => 'Number & Label',
					'icon_text' => 'Icon & Label',
				],
				'prefix_class' => 'crt-step-type-',
				'default' => 'number_text'
			]
		);

		$this->add_control(
			'step_content_layout',
			[
				'label' => esc_html__( 'Content Layout', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'options' => [
					'horizontal' => 'Horizontal',
					'vertical' => 'Vertical',
				],
				'default' => 'vertical',
				'prefix_class' => 'crt-step-content-layout-',
				'condition' => [
					'step_type!' => ['progress_bar', 'none']
				]
			]
		);

		$this->add_control(
			'show_separator',
			[
				'label' => esc_html__( 'Separator', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'step_type!' => 'progress_bar'
				]
			]
		);

		$this->add_responsive_control(
			'step_box_align',
			[
				'label' => esc_html__( 'Box Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}}.crt-step-content-layout-vertical .crt-step' => 'align-items: {{VALUE}}',
					'{{WRAPPER}}.crt-step-content-layout-horizontal .crt-step' => 'justify-content: {{VALUE}}'
				],
				'condition' => [
					'step_type!' => ['progress_bar', 'none']
				]
			]
		);

		$this->add_responsive_control(
			'step_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .crt-step' => 'text-align: {{VALUE}}'
				],
				'condition' => [
					'step_type!' => ['progress_bar', 'none']
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_style',
			[
				'label' => esc_html__( 'Form', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .crt-field-group > label, {{WRAPPER}} .crt-field-sub-group label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'mark_required_color',
			[
				'label' => esc_html__( 'Mark Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CB3030',
				'selectors' => [
					'{{WRAPPER}} .crt-required-mark .crt-form-field-label:after' => 'color: {{COLOR}};',
				],
				'condition' => [
					'mark_required' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .crt-field-group > label'
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'body.rtl {{WRAPPER}} .crt-labels-inline .crt-field-group > label' => 'padding-left: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body:not(.rtl) {{WRAPPER}} .crt-labels-inline .crt-field-group > label' => 'padding-right: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body {{WRAPPER}} .crt-labels-above .crt-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					// for the label position = above option
				],
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label' => esc_html__( 'Inputs', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'column_gap',
			[
				'label' => esc_html__( 'Horizontal Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-field-group' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .crt-step-wrap' => 'padding-left: calc( -{{SIZE}}{{UNIT}}/2 ); padding-right: calc( -{{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .crt-stp-btns-wrap' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .crt-form-fields-wrap' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => esc_html__( 'Vertical Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-field-group:not(.crt-stp-btns-wrap)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-field-group.recaptcha-v3-bottomleft, {{WRAPPER}} .crt-field-group.recaptcha-v3-bottomright' => 'margin-bottom: 0;',
				],
			]
		);

		$this->add_responsive_control(
			'labels_align',
			[
				'label' => esc_html__( 'Align Labels', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .crt-field-group:not(.crt-stp-btns-wrap)' => 'justify-content: {{VALUE}}'
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => esc_html__( 'Field', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_fields_style' );

		$this->start_controls_tab(
			'tab_fields_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .crt-field-group .crt-form-field' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group .crt-form-field select' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group .crt-form-field svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group input[type="radio"] + label' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group input[type="checkbox"] + label' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-field-group:not(.crt-form-field-type-upload) .crt-form-field:not(.crt-select-wrap)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group .crt-select-wrap select' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'field_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-field-group:not(.crt-form-field-type-upload) .crt-form-field:not(.crt-select-wrap)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group .crt-select-wrap select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group .crt-select-wrap::before' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'selector' => '{{WRAPPER}} .crt-field-group .crt-form-field, {{WRAPPER}} .crt-field-sub-group label'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_fields_focus',
			[
				'label' => esc_html__( 'Focus', 'crt-manage' ),
			]
		);

		$this->add_control(
			'field_text_color_focus',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .crt-field-group .crt-form-field:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group input[type="radio"]:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group input[type="checkbox"]:focus' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'field_background_color_focus',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-field-group:not(.crt-form-field-type-upload) .crt-form-field:not(.crt-select-wrap):focus' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group .crt-select-wrap select:focus' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'field_border_color_focus',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-field-group:not(.crt-form-field-type-upload) .crt-form-field:not(.crt-select-wrap):focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group .crt-select-wrap select:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group .crt-select-wrap:focus-within::before' => 'color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_fields_error',
			[
				'label' => esc_html__( 'Error', 'crt-manage' ),
			]
		);

		$this->add_control(
			'field_text_color_error',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CB3030',
				'selectors' => [
					'{{WRAPPER}} .crt-field-group .crt-form-field.crt-form-error' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group input[type="radio"].crt-form-error' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group input[type="checkbox"].crt-form-error' => 'color: {{VALUE}};',
					// '{{WRAPPER}} .crt-field-group .crt-form-field-label' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'field_background_color_error',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-field-group:not(.crt-form-field-type-upload) .crt-form-field:not(.crt-select-wrap).crt-form-error' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group .crt-select-wrap select.crt-form-error' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'field_border_color_error',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CB3030',
				'selectors' => [
					'{{WRAPPER}} .crt-field-group:not(.crt-form-field-type-upload) .crt-form-field:not(.crt-select-wrap).crt-form-error' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group .crt-select-wrap select.crt-form-error' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-field-group .crt-select-wrap.crt-form-error-wrap::before' => 'color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'field_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .crt-field-group:not(.crt-form-field-type-upload) .crt-form-field:not(.crt-select-wrap)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-field-group .crt-select-wrap select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'field_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-field-group:not(.crt-form-field-type-upload) .crt-form-field:not(.crt-select-wrap)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-field-group .crt-select-wrap select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'field_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 6,
					'right' => 5,
					'bottom' => 7,
					'left' => 10,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-field-group:not(.crt-form-field-type-upload) .crt-form-field:not(.crt-select-wrap)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-field-group .crt-select-wrap select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-field-group input[type="date"]::before' => 'right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .crt-field-group input[type="time"]::before' => 'right: {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'radio_and_checkbox_distance',
			[
				'label' => esc_html__( 'Radio & Checkbox', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'radion_&_checkbox_padding',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-form-field-option' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'radion_&_checkbox_gutter',
			[
				'label' => esc_html__( 'Inner Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-form-field-option label' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-custom-styles-yes .crt-form-field-option label:before' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Checkboxes -------
		$this->start_controls_section(
			'section_style_checkbox_radio',
			[
				'label' => esc_html__( 'Checkbox & Radio', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'checkbox_radio_custom',
			[
				'label' => esc_html__( 'Use Custom Styles', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'crt-custom-styles-'
			]
		);

		$this->add_control(
			'checkbox_radio_static_color',
			[
				'label' => esc_html__( 'Static Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-form-field-type-checkbox .crt-form-field-option label:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-form-field-type-radio .crt-form-field-option label:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'checkbox_radio_custom' => 'yes'
				]
			]
		);

		$this->add_control(
			'checkbox_radio_active_color',
			[
				'label' => esc_html__( 'Active Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e55b5b',
				'selectors' => [
					'{{WRAPPER}} .crt-form-field-type-checkbox .crt-form-field-option label:before' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-form-field-type-radio .crt-form-field-option label:before' => 'color: {{VALUE}}',
				],
				'condition' => [
					'checkbox_radio_custom' => 'yes'
				]
			]
		);

		$this->add_control(
			'checkbox_radio_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-form-field-type-checkbox .crt-form-field-option label:before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-form-field-type-radio .crt-form-field-option label:before' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'checkbox_radio_custom' => 'yes'
				]
			]
		);

		$this->add_control(
			'checkbox_radio_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-form-field-type-checkbox .crt-form-field-option label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 1.3);',
					'{{WRAPPER}} .crt-form-field-type-radio .crt-form-field-option label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 1.3);',
					'{{WRAPPER}} .crt-form-field-type-checkbox input' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-form-field-type-radio input' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
				'condition' => [
					'checkbox_radio_custom' => 'yes'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Buttons', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'heading_next_submit_button',
			[
				'label' => esc_html__( 'Submit Button, Next Button', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-step-next' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-button[type="submit"]' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-step-next' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-button[type="submit"]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-double-bounce .crt-child' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .crt-button[type="submit"] svg *' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-step-prev' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-step-next' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-button[type="submit"]' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'heading_previous_button',
			[
				'label' => esc_html__( 'Previous Button', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'previous_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-step-prev' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'previous_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-step-prev' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'previous_button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-step-prev' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .crt-button, {{WRAPPER}} .crt-step-prev, {{WRAPPER}} .crt-step-next',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'heading_next_submit_button_hover',
			[
				'label' => esc_html__( 'Next & Submit Button', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-step-next:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-button[type="submit"]:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-step-next:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-button[type="submit"]:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-button[type="submit"]:hover svg *' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-step-next:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .crt-button[type="submit"]:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'heading_previous_button_hover',
			[
				'label' => esc_html__( 'Previous Button', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'previous_button_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-step-prev:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'previous_button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-step-prev:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'previous_button_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .crt-step-prev:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		// $this->add_control(
		// 	'button_hover_animation',
		// 	[
		// 		'label' => esc_html__( 'Animation', 'crt-manage' ),
		// 		'type' => Controls_Manager::HOVER_ANIMATION,
		// 	]
		// );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .crt-button, {{WRAPPER}} .crt-step-prev, {{WRAPPER}} .crt-step-next',
				'exclude' => [
					'color',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .crt-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-step-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-step-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Text Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .crt-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-step-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-step-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'heading_button_wrap_style',
			[
				'label' => esc_html__( 'Wrapper', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_wrap_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-stp-btns-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_step_style',
			[
				'label' => esc_html__( 'Step', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_step_style' );

		$this->start_controls_tab(
			'tab_step_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'main_label_color',
			[
				'label' => esc_html__( 'Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .crt-step-main-label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'sub_label_color',
			[
				'label' => esc_html__( 'Sub Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .crt-step-sub-label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-step' => 'background-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'step_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-step' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_step_active',
			[
				'label' => esc_html__( 'Active', 'crt-manage' ),
			]
		);

		$this->add_control(
			'main_label_color_active',
			[
				'label' => esc_html__( 'Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .crt-step.crt-step-active .crt-step-main-label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'sub_label_color_active',
			[
				'label' => esc_html__( 'Sub Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .crt-step.crt-step-active .crt-step-sub-label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_bg_color_active',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-step.crt-step-active' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_border_color_active',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-step.crt-step-active' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_step_finished',
			[
				'label' => esc_html__( 'Finished', 'crt-manage' ),
			]
		);

		$this->add_control(
			'main_label_color_finish',
			[
				'label' => esc_html__( 'Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .crt-step.crt-step-finish .crt-step-main-label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'sub_label_color_finish',
			[
				'label' => esc_html__( 'Sub Label Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .crt-step.crt-step-finish .crt-step-sub-label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_bg_color_finish',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-step.crt-step-finish' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_border_color_finish',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-step.crt-step-finish' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'step_wrap_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-step-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'step_wrap_gutter_vertical',
			[
				'label' => esc_html__( 'Vertical Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-step' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'step_wrap_gutter',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 5
				],
				'selectors' => [
					'{{WRAPPER}} .crt-step-sep' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .crt-separator-off .crt-step:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'step_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .crt-step' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'step_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-step' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'step_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'step_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .crt-step' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'step_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					// '{{WRAPPER}}.crt-step-content-layout-horizontal .crt-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}' => '--crt-steps-padding: {{TOP}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'step_inner_styles',
			[
				'label' => esc_html__( 'Step Indicator', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'tabs_step_inner_style' );

		$this->start_controls_tab(
			'tab_step_inner_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'step_inner_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .crt-step-content i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-step-content svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .crt-step-content' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'step_inner_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-step-content' => 'background-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'step_inner_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-step-content' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_step_inner_active',
			[
				'label' => esc_html__( 'Active', 'crt-manage' ),
			]
		);

		$this->add_control(
			'step_inner_color_active',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .crt-step-active .crt-step-content i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-step-active .crt-step-content svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .crt-step-active .crt-step-content' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'step_inner_bg_color_active',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-step.crt-step-active .crt-step-content' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_inner_border_color_active',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-step.crt-step-active .crt-step-content' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_step_inner_finish',
			[
				'label' => esc_html__( 'Finish', 'crt-manage' ),
			]
		);

		$this->add_control(
			'step_inner_color_finish',
			[
				'label' => esc_html__( 'Color (Labels, Icon, Number)', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .crt-step-finish .crt-step-content i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crt-step-finish .crt-step-content svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .crt-step-finish .crt-step-content' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'step_inner_bg_color_finish',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-step.crt-step-finish .crt-step-content' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_inner_border_color_finish',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-step.crt-step-finish .crt-step-content' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'step_inner_border_type',
			[
				'label' => esc_html__( 'Border Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .crt-step-content' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'step_inner_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-step-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'step_inner_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'step_inner_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .crt-step-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		// $this->add_control(
		// 	'step_inner_padding',
		// 	[
		// 		'label' => esc_html__( 'Padding', 'crt-manage' ),
		// 		'type' => Controls_Manager::DIMENSIONS,
		// 		'size_units' => [ 'px', 'em', '%' ],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'top' => 10,
		// 			'right' => 10,
		// 			'bottom' => 10,
		// 			'left' => 10,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .crt-step-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		// 		],
		// 	]
		// );

		$this->add_responsive_control(
			'step_inner_padding',
			[
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--crt-steps-indicator-padding: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'step_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-step' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-step svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'step_type' => ['icon', 'icon_text']
				]
			]
		); 

		$this->add_responsive_control(
			'step_label_distance',
			[
				'label' => esc_html__( 'Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-step-content-layout-horizontal .crt-step-label' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-step-content-layout-vertical .crt-step-label' => 'margin-top: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
				'condition' => [
					'step_type' => ['number_text', 'icon_text']
				]
			]
		);

		$this->add_control(
			'step_divider',
			[
				'label' => esc_html__( 'Divider', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'step_type!' => 'progress_bar'
				]
			]
		);

		$this->add_control(
			'step_progressbar',
			[
				'label' => esc_html__( 'Progressbar', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'step_type' => 'progress_bar'
				]
			]
		);

		$this->add_control(
			'step_divider_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222333',
				'selectors' => [
					'{{WRAPPER}} .crt-step-sep' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .crt-step-progress' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_progress_text_color',
			[
				'label' => esc_html__( 'Text Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .crt-step-progress-fill' => 'color: {{VALUE}};',
				],
				'condition' => [
					'step_type' => 'progress_bar'
				]
			]
		);

		$this->add_control(
			'step_progress_fill_color',
			[
				'label' => esc_html__( 'Fill Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .crt-step-progress-fill' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'step_type' => 'progress_bar'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'step_percent_typo',
				'selector' => '{{WRAPPER}} .crt-step-progress-fill',
				'condition' => [
					'step_type' => 'progress_bar'
				]
			]
		);

		$this->add_responsive_control(
			'step_divider_height',
			[
				'label' => esc_html__( 'Divider Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--crt-steps-divider-width: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'step_type!' => 'progress_bar'
				]
			]
		);

		$this->add_responsive_control(
			'step_progress_text_distance',
			[
				'label' => esc_html__( 'Text Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-step-progress-fill' => 'padding-right: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'step_type' => 'progress_bar'
				]
			]
		);

		$this->add_responsive_control(
			'step_progress_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-step-progress' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-step-progress-fill' => 'border-radius: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'step_type' => 'progress_bar'
				]
			]
		);

		$this->add_control(
			'step_main_label',
			[
				'label' => esc_html__( 'Main Label', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'main_label_typography',
				'selector' => '{{WRAPPER}} .crt-step-main-label',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'step_sub_label',
			[
				'label' => esc_html__( 'Sub Label', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_label_typography',
				'selector' => '{{WRAPPER}} .crt-step-sub-label',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'step_number_heading',
			[
				'label' => esc_html__( 'Number', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'step_type' => ['number', 'number_text']
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'step_number',
				'selector' => '{{WRAPPER}} .crt-step-number',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				],
				'condition' => [
					'step_type' => ['number', 'number_text']
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_results_style',
			[
				'label' => esc_html__( 'Results', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'results_typography',
				'selector' => '{{WRAPPER}} .crt-submit-success, {{WRAPPER}} .crt-submit-error',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'success_result_color',
			[
				'label' => esc_html__( 'Success Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#30CBCB',
				'selectors' => [
					'{{WRAPPER}} .crt-submit-success' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'error_message_color',
			[
				'label' => esc_html__( 'Error Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CB3030',
				'selectors' => [
					'{{WRAPPER}} .crt-submit-error' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'finish_message_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'crt-manage' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'crt-manage' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'crt-manage' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .crt-submit-success' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .crt-submit-error' => 'text-align: {{VALUE}}'
				],
			]
		);

		$this->add_responsive_control(
			'notice_distance',
			[
				'label' => esc_html__( 'Top Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-submit-notice' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

    }

	private function add_required_attribute( $element ) {
		$this->add_render_attribute( $element, 'required', 'required' );
		$this->add_render_attribute( $element, 'aria-required', 'true' );
	}

	public function get_attribute_name( $item ) {
		return "form_fields[{$item['field_id']}]";
	}

	public function get_attribute_id( $item ) {
		//  $id_suffix = !empty($item['field_id']) ? $item['field_id'] : $item['field_type'];
		 $id_suffix = !empty($item['field_id']) ? $item['field_id'] : $item['_id'];
		return 'form-field-' . $id_suffix;
	}

	protected function make_textarea_field( $item, $item_index ) {
		$this->add_render_attribute( 'textarea' . $item_index, [
			'class' => [
				'crt-form-field-textual',
				'crt-form-field',
				esc_attr( $item['css_classes'] )
			],
			'name' => $this->get_attribute_name( $item ),
			'id' => $this->get_attribute_id( $item ),
			'rows' => $item['rows'],
		] );

		if ( 'true' == $this->get_settings_for_display()['show_placeholders'] && $item['placeholder'] ) {
			$this->add_render_attribute( 'textarea' . $item_index, 'placeholder', $item['placeholder'] );
		}

		if ( $item['required'] ) {
			$this->add_required_attribute( 'textarea' . $item_index );
		}

		$value = empty( $item['field_value'] ) ? '' : $item['field_value'];

		return '<textarea ' . $this->get_render_attribute_string( 'textarea' . $item_index ) . '>' . $value . '</textarea>';
	}

	protected function make_select_field( $item, $i ) {
		$this->add_render_attribute(
			[
				'select-wrapper' . $i => [
					'class' => [
						'crt-form-field',
						'crt-select-wrap', 
						'crt-fi-svg-'. (\Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) ? 'yes' : 'no'),
						'remove-before',
						esc_attr( $item['css_classes'] ),
					],
				],
				'select' . $i => [
					'name' => $this->get_attribute_name( $item ) . ( ! empty( $item['allow_multiple'] ) ? '[]' : '' ),
					'id' => $this->get_attribute_id( $item ),
					'class' => [
						'crt-form-field-textual'
					],
				],
			]
		);

		if ( $item['required'] ) {
			$this->add_required_attribute( 'select' . $i );
		}

		if ( $item['allow_multiple'] ) {
			$this->add_render_attribute( 'select' . $i, 'multiple' );
			if ( ! empty( $item['select_size'] ) ) {
				$this->add_render_attribute( 'select' . $i, 'size', $item['select_size'] );
			}
		}

		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );

		if ( ! $options ) {
			return '';
		}

		ob_start();
		?>
		<div <?php $this->print_render_attribute_string( 'select-wrapper' . $i ); ?>>

			<?php if ( \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) ) { ?>
				<!-- <svg class="e-font-icon-svg e-eicon-caret-down" viewBox="0 0 571.4 1000" xmlns="http://www.w3.org/2000/svg">
					<path d="M571 457q0-14-10-25l-250-250q-11-11-25-11t-25 11l-250 250q-11 11-11 25t11 25 25 11h500q14 0 25-11t10-25z"/>
				</svg> -->
				<!-- <svg class="e-font-icon-svg e-eicon-caret-up" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg">
					<path d="M763 279c8-8 8-12 8-25 0-8-4-16-8-25-9-8-13-8-25-8h-459c-8 0-16 4-25 8 0 9-4 17-4 25 0 9 4 17 8 25l230 229c8 5 16 9 25 9 8 0 16-4 25-9l225-229z"/>
				</svg> -->
				<!-- <svg class="e-font-icon-svg e-eicon-caret-up" viewBox="0 0 1000 500" xmlns="http://www.w3.org/2000/svg">
					<path d="M763 279c8-8 8-12 8-25 0-8-4-16-8-25-9-8-13-8-25-8h-459c-8 0-16 4-25 8 0 9-4 17-4 25 0 9 4 17 8 25l230 229c8 5 16 9 25 9 8 0 16-4 25-9l225-229z"/>
				</svg> -->
				<svg class="e-font-icon-svg e-eicon-caret-up" viewBox="0 0 1000 500" xmlns="http://www.w3.org/2000/svg">
					<path d="M763 279c8-8 8-12 8-25 0-8-4-16-8-25-9-8-13-8-25-8h-459c-8 0-16 4-25 8 0 9-4 17-4 25 0 9 4 17 8 25l230 229c8 5 16 9 25 9 8 0 16-4 25-9l225-229z"/>
				</svg>
			<?php } ?>

			<select <?php $this->print_render_attribute_string( 'select' . $i ); ?>>

				<?php
				foreach ( $options as $key => $option ) :
					$option_id = $item['field_id'] . $key;
					$option_value = esc_attr( $option );
					$option_label = esc_html( $option );

					if ( false !== strpos( $option, '|' ) ) {
						list( $label, $value ) = explode( '|', $option );
						$option_value = esc_attr( $value );
						$option_label = esc_html( $label );
					}

					$this->add_render_attribute( $option_id, 'value', $option_value );

					// Support multiple selected values
					if ( ! empty( $item['field_value'] ) && in_array( $option_value, explode( ',', $item['field_value'] ) ) ) {
						$this->add_render_attribute( $option_id, 'selected', 'selected' );
					} ?>
					<option <?php $this->print_render_attribute_string( $option_id ); ?>>
					<?php
						// PHPCS - $option_label is already escaped
						echo $option_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php

		$select = ob_get_clean();
		return $select;
	}

	protected function make_radio_checkbox_field( $item, $item_index, $type ) {
		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );
		$html = '';
		if ( $options ) {
			$html .= '<div class="crt-field-sub-group ' . esc_attr( $item['css_classes'] ) . ' ' . esc_attr( $item['inline_list'] ). '">';
			foreach ( $options as $key => $option ) {
				$element_id = ($item['field_id'] ? esc_attr( $item['field_id'] ) : $item['field_type']) . $key;
				$html_id = $this->get_attribute_id( $item ) . '-' . $key;
				$option_label = $option;
				$option_value = $option;

				if ( false !== strpos( $option, '|' ) ) {
					list( $option_label, $option_value ) = explode( '|', $option );
				}

				$this->add_render_attribute(
					$element_id,
					[
						'type' => $type,
						'value' => $option_value,
						'id' => $html_id,
						'name' => $this->get_attribute_name( $item ) . ( ( 'checkbox' === $type && count( $options ) > 1 ) ? '[]' : '' ),
					]
				);

				if ( ! empty( $item['field_value'] ) && $option_value === $item['field_value'] ) {
					$this->add_render_attribute( $element_id, 'checked', 'checked' );
				}

				if ( $item['required'] && ('radio' === $type || 'checkbox' === $type) ) {
					$this->add_required_attribute( $element_id );
				}

				$html .= '<span class="crt-form-field-option" data-key="form-field-'. esc_attr( $item['field_id'] ).'"><input ' . $this->get_render_attribute_string( $element_id ) . '> <label for="' . esc_attr( $html_id ) . '">'. $option_label .'</label></span>';
			}
			$html .= '</div>';
		}

		return $html;
	}	
	
	protected function form_fields_render_attributes( $i, $instance, $item ) {
		if ( 'upload' === $item['field_type'] ) {
			update_option( 'crt_form_upload_field_in_use_' . $this->get_attribute_id( $item ), true );
		}

		$this->add_render_attribute(
			[
				'field-group' . $i => [
					'class' => [
						'crt-form-field-type-' . $item['field_type'],
						'crt-field-group',
						'crt-column',
						'crt-field-group-' . esc_attr( $item['field_id'] ),
					],
				],
				'input' . $i => [
					'type' => ('acceptance' === $item['field_type']) ? 'checkbox' : (('upload' === $item['field_type']) ? 'file' :  $item['field_type']),
					'name' => $this->get_attribute_name( $item ),
					'id' => $this->get_attribute_id( $item ),
					'class' => [
						'crt-form-field',
						empty( $item['css_classes'] ) ? '' : esc_attr( $item['css_classes'] ),
					],
				],
				'label' . $i => [
					'for' => $this->get_attribute_id( $item ),
					'class' => 'crt-form-field-label',
					'data-alt-label' => ! empty( $item['alt_label'] ) ? esc_attr( $item['alt_label'] ) : esc_attr( $item['field_label'] ),
				],
			]
		);

		if ( empty( $item['width'] ) ) {
			$item['width'] = '100';
		}

		// $this->add_render_attribute( 'field-group' . $i, 'class', 'crt-col-' . $item['width'] );

		// if ( ! empty( $item['width_tablet'] ) ) {
		// 	$this->add_render_attribute( 'field-group' . $i, 'class', 'crt-md-' . $item['width_tablet'] );
		// }

		if ( $item['allow_multiple'] ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'crt-form-field-type-' . $item['field_type'] . '-multiple' );
		}

		// if ( ! empty( $item['width_mobile'] ) ) {
		// 	$this->add_render_attribute( 'field-group' . $i, 'class', 'crt-sm-' . $item['width_mobile'] );
		// }

		
		
		$this->add_render_attribute( 'field-group' . $i, 'class', 'elementor-repeater-item-'. esc_attr($item['_id']) );

		// Allow zero as placeholder.
		if ( 'true' == $instance['show_placeholders'] && ! Utils::is_empty( $item['placeholder'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'placeholder', $item['placeholder'] );
		}

		if ( ! empty( $item['field_value'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'value', $item['field_value'] );
		}

		if ( ! $instance['show_labels'] ) {
			$this->add_render_attribute( 'label' . $i, 'class', 'crt-hidden-element' );
		}

		if ( ! empty( $item['required'] ) ) {
			$class = 'crt-form-field-required';
			if ( ! empty( $instance['mark_required'] ) ) {
				$class .= ' crt-required-mark';
			}
			$this->add_render_attribute( 'field-group' . $i, 'class', $class );
			$this->add_required_attribute( 'input' . $i );
		}
	}

	private function render_form_icon( $settings ) { ?>
		<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
			<?php Icons_Manager::render_icon( $settings['selected_button_icon'], [ 'aria-hidden' => 'true' ] );  ?>
			<?php if ( empty( $instance['button_text'] ) ) : ?>
				<span class="crt-hidden-element"><?php echo esc_html__( 'Submit', 'crt-manage' ); ?></span>
			<?php endif; ?>
		</span>
	<?php }

	public function render_submit_button($instance) {
		?>
			<button type="submit" <?php echo $this->get_render_attribute_string( 'button' ); ?>>
				<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
					<?php if ( !empty( $instance['selected_button_icon'] ) && 'left' === $instance['button_icon_align'] ) : ?>
						<?php $this->render_form_icon($instance); ?>
					<?php endif; ?>
					<?php if ( ! empty( $instance['button_text'] ) ) : ?>
						<span><?php echo $this->print_unescaped_setting( 'button_text' ); ?></span>
					<?php endif; ?>
					<?php if ( !empty( $instance['selected_button_icon'] ) && 'right' === $instance['button_icon_align'] ) : ?>
						<?php $this->render_form_icon($instance); ?>
					<?php endif; ?>
				</span>	
				<div class="crt-double-bounce crt-loader-hidden">
					<div class="crt-child crt-double-bounce1"></div>
					<div class="crt-child crt-double-bounce2"></div>
				</div>
			</button>
		<?php 
	}

	protected function render() {
		global $post;
		$instance = $this->get_settings_for_display();
		
		$form_fields_length = sizeof($instance['form_fields']);
		$thisId = $this->get_id();

		update_option('crt_email_content_type_'. $this->get_id(), $instance['email_content_type']);
		update_option('crt_email_to_'. $this->get_id(), $instance['email_to']);
		update_option('crt_email_subject_'. $this->get_id(), $instance['email_subject']);
		update_option('crt_email_fields_'. $this->get_id(), $instance['email_content']);
		update_option('crt_cc_header_'. $this->get_id(), $instance['email_to_cc']);
		update_option('crt_bcc_header_'. $this->get_id(), $instance['email_to_bcc']);
		update_option('crt_email_from_'. $this->get_id(), $instance['email_from']);
		update_option('crt_email_from_name_'. $this->get_id(), $instance['email_from_name']);
		update_option('crt_reply_to_'. $this->get_id(), $instance['email_reply_to']);
		update_option('crt_meta_keys_'. $this->get_id(), $instance['form_metadata']);
		update_option('crt_referrer_'. $this->get_id(), home_url( $_SERVER['REQUEST_URI'] ));
		if ($post && $post->ID) {
			update_option('crt_referrer_title_'. $this->get_id(), get_the_title($post->ID));
		}
		update_option('crt_webhook_url_'. $this->get_id(), $instance['webhook_url']);

		$emailField      = isset($instance['email_field']) ? $instance['email_field'] : '';
		$firstNameField  = isset($instance['first_name_field']) ? $instance['first_name_field'] : '';
		$lastNameField   = isset($instance['last_name_field']) ? $instance['last_name_field'] : '';
		$addressField    = isset($instance['address_field']) ? $instance['address_field'] : '';
		$phoneField      = isset($instance['phone_field']) ? $instance['phone_field'] : '';
		$birthdayField   = isset($instance['birthday_field']) ? $instance['birthday_field'] : '';
		$groupId 	     = isset($instance['mailchimp_groups']) ? $instance['mailchimp_groups'] : '';

		$fieldsArray = [
			'email_field' => $emailField,
			'first_name_field' => $firstNameField,
			'last_name_field' => $lastNameField,
			'address_field' => $addressField,
			'phone_field' => $phoneField,
			'birthday_field' => $birthdayField,
			'group_id' =>  $groupId
		];

		$submit_actions = array_filter($instance['submit_actions'], function($value) {
			return $value !== 'pro-sb' && $value !== 'pro-mch' && $value !== 'pro-wh';
		});
		$submit_actions = array_values($submit_actions);

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'crt-form-fields-wrap',
						'crt-labels-' . $instance['label_position'],
					],
				],
				'submit-group' => [
					'class' => [
						'crt-field-group',
						'crt-stp-btns-wrap',
						'crt-column',
						'crt-form-field-type-submit',
					],
					'data-actions' => [
						json_encode($submit_actions)
					],
					'data-redirect-url' => [
						in_array('redirect', $submit_actions) ? esc_url( $instance['redirect_to'] ) : ''
					],
					'data-mailchimp-fields' => [
						json_encode($fieldsArray)
					],
					'data-list-id'=> [
						isset($instance['maichimp_audience']) ? esc_attr($instance['maichimp_audience']) : ''
					]
				],
				'button' => [
					'class' => 'crt-button',
				],
				'icon-align' => [
					'class' => [
						empty( $instance['button_icon_align'] ) ? '' :
							'crt-align-icon-' . $instance['button_icon_align'],
						'elementor-button-icon',
					],
				],
			]
		);

		if ( ! empty( $instance['form_id'] ) ) {
			$this->add_render_attribute( 'form', 'id', $instance['form_id'] );
		}

		if ( ! empty( $instance['form_name'] ) ) {
			$this->add_render_attribute( 'form', 'name', $instance['form_name'] );
		}
		if ($post && $post->ID) {
			$this->add_render_attribute( 'form', 'page', get_post()->post_title );
			$this->add_render_attribute( 'form', 'page_id', get_post()->ID );
		}

		if ( ! empty( $instance['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $instance['button_css_id'] );
		}

		$referer_title = trim( wp_title( '', false ) );

		if ( ! $referer_title && is_home() ) {
			$referer_title = get_option( 'blogname' );
		}

		?>
		<form class="crt-form" method="post" <?php echo $this->get_render_attribute_string( 'form' ); ?> novalidate>
			<input type="hidden" name="post_id" value="<?php // PHPCS - the method Utils::get_current_post_id is safe.
				echo get_the_ID(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"/>
			<input type="hidden" name="form_id" value="<?php echo esc_attr( $this->get_id() ); ?>"/>
			<input type="hidden" name="referer_title" value="<?php echo esc_attr( $referer_title ); ?>" />

			<?php if ( is_singular() ) {
				// `queried_id` may be different from `post_id` on Single theme builder templates.
				?>
				<input type="hidden" name="queried_id" value="<?php echo get_the_ID(); ?>"/>
			<?php } 
			
				$step_count1 = 0;
				$step_exists = '';
				$step_icon = [];
				$step_label = [];
				$step_sub_label = [];
				$whitelist = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'odt', 'avi', 'ogg', 'm4a', 'mov', 'mp3', 'mp4', 'mpg', 'wav', 'wmv', 'txt'];

				foreach ( $instance['form_fields'] as $key => $value ) {
					if ( 'step' === $value['field_type'] ) {
						$step_exists = 'exists';
						$step_count1++;
						
						ob_start();
							\Elementor\Icons_Manager::render_icon( $value['step_icon'], [ 'aria-hidden' => 'true' ] );
						$step_icon[] = ob_get_clean();

						$step_label[] = '<span class="crt-step-main-label">'. $value['field_label'] .'</span>';

						$step_sub_label[] = '<span class="crt-step-sub-label">'. $value['field_sub_label'] .'</span>';
					}
				}
				
				// Circles which indicates the steps of the form:
				$step_wrap_class  = 'yes' !== $instance['show_separator'] ? 'crt-step-wrap crt-separator-off' : 'crt-step-wrap';
				
				echo '<div class="'. $step_wrap_class .'">';
					if ( 'progress_bar' == $instance['step_type'] ) {
						echo '<div class="crt-step-progress">';
							echo '<div class="crt-step-progress-fill"></div>';
						echo '</div>';
					} else {
						$i = 0;

						while ( $i < $step_count1 ) :

							if ( 'none' == $instance['step_type'] ) {
								$step_html = '<span class="crt-step"></span>';
							} else if ( 'text' == $instance['step_type'] ) {
								$step_html = '<span class="crt-step">'. $step_label[$i] . $step_sub_label[$i] .'</span>';
							} else if ( 'icon' == $instance['step_type'] ) {
								$step_html = '<span class="crt-step"><span class="crt-step-content">'. $step_icon[$i] .'</span></span>';
							} else if ( 'number' == $instance['step_type'] ) {
								$step_html = '<span class="crt-step"><span class="crt-step-content"><span class="crt-step-number">'. ($i + 1) .'</span></span></span>';
							} else if ( 'number_text' == $instance['step_type'] ) {
								$step_html = '<span class="crt-step"><span class="crt-step-content"><span class="crt-step-number">'. ($i + 1) .'</span></span><span class="crt-step-label">'. $step_label[$i] . $step_sub_label[$i] .'</span></span>';
							} else if ( 'icon_text' == $instance['step_type'] ) {
								$step_html = '<span class="crt-step"><span class="crt-step-content">'. $step_icon[$i] .'</span><span class="crt-step-label">'. $step_label[$i] . $step_sub_label[$i] .'</span></span>';
							}

							echo $step_html;
							// echo '<span class="crt-step">'. $step_html .'</span>';

							if ( 'yes' == $instance['show_separator'] ) {
								echo '<span class="crt-step-sep"></span>';
							}

							$i++; 
						endwhile;
					}
				echo '</div>';
			?>

			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<?php

				$step_count = 0;
				$field_count = 0;

				foreach ( $instance['form_fields'] as $item_index => $item ) :
					if ( 'step' !== $item['field_type'] ) {
						$field_count++;
					}

					$this->form_fields_render_attributes( $item_index, $instance, $item );

					$print_label = ! in_array( $item['field_type'], [ 'hidden', 'html', 'step' ], true );
					$field_id = sanitize_key( $item['field_id'] );
					$field_label = sanitize_text_field( $item['field_label'] );
					
					if ( 'step' === $item['field_type'] )  {
                        if ( isset($item['previous_button_text']) ) {
                            $this->last_prev_btn_text = $item['previous_button_text'];
                        }

						if ( 0 === $step_count ) {
							echo '<div class="crt-step-tab crt-step-tab-hidden">';
						} else {
								echo '<div class="crt-stp-btns-wrap">';
									echo '<button type="button" class="crt-step-prev">'. $item['previous_button_text'] .'</button>';
									echo '<button type="button" class="crt-step-next">'. $item['next_button_text'] .'</button>';
								echo '</div>';
							echo '</div>';
							echo '<div class="crt-step-tab crt-step-tab-hidden">';
						}
						$step_count++;
					}

					?>
					<div <?php $this->print_render_attribute_string( 'field-group' . $item_index ); ?>>
						<?php
						if ( $print_label && $item['field_label'] ) {
							?>
								<label <?php echo $this->get_render_attribute_string( 'label' . $item_index ); ?>>
									<?php // PHPCS - the variable $item['field_label'] is safe.
									echo $item['field_label']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</label>
							<?php
						}

						switch ( $item['field_type'] ) :
							case 'html':
								echo do_shortcode( $item['field_html'] );
								break;
							case 'textarea':
								// PHPCS - the method make_textarea_field is safe.
								echo $this->make_textarea_field( $item, $item_index ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								break;

							case 'select':
								// PHPCS - the method make_select_field is safe.
								echo $this->make_select_field( $item, $item_index ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								break;

							case 'radio':
							case 'checkbox':
								// PHPCS - the method make_radio_checkbox_field is safe.
								echo $this->make_radio_checkbox_field( $item, $item_index, $item['field_type'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								break;
							case 'recaptcha-v3':
								echo '<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" data-site-key="'. get_option('crt_recaptcha_v3_site_key') .'" />';
							case 'text':
							case 'email':
							case 'url':
							case 'tel':
							case 'password':
							case 'hidden':
							case 'search':
							case 'number':
							case 'date':
							case 'time':
								$this->add_render_attribute( 'input' . $item_index, 'class', 'crt-form-field-textual' );
								echo '<input size="1 "'. $this->get_render_attribute_string( 'input' . $item_index ) .'>';
								break;
							case 'upload':
								if ( 'yes' === $item['allow_multiple_upload'] ) {
									$this->add_render_attribute( 'input' . $item_index, 'multiple', 'multiple' );
								}

								if ( !empty( $item['file_size'] ) ) {
									$this->add_render_attribute(
										'input' . $item_index,
										[
											'data-maxfs' => $item['file_size'],  //MB
											'data-maxfs-notice' => esc_html__( 'File size is more than allowed.', 'crt-manage' ),
										]
									);
								}

								if ( !empty( $item['file_types'] )) {

									// Convert string to array
									$file_types = explode(',', $item['file_types']);
									
									// Check for non-whitelisted file types
									$non_whitelisted = array_diff($file_types, $whitelist);
									
									if ( !empty($non_whitelisted) ) {
										$item['file_types'] = 'jpg,jpeg,png,gif,pdf,doc,docx,ppt,pptx,odt,avi,ogg,m4a,mov,mp3,mp4,mpg,wav,wmv,txt';
										if ( is_admin() ) {
											echo '<br>';
											echo '<ul class="crt-file-type-error">';
												echo esc_html__( 'Please remove unsupported file type(s):', 'crt-manage' );
												foreach ( $non_whitelisted as $type ) {
													if ( !empty($type) ) {
														echo '<li>'. $type .' <li/>';
													}
												}
											echo '</ul>';
										}
									}

									$this->add_render_attribute(
										'input' . $item_index,
										[
											'data-allft' => $item['file_types']
										]
									);
								}

								echo '<input size="1 "'. $this->get_render_attribute_string( 'input' . $item_index ) .'>';
								break;
							case 'step':
								echo '<input type="hidden" class="crt-step-input" id=form-field-'. esc_attr( $field_id ) .' value='. esc_attr( $field_label ) .'>';
								break;
							default:
								$field_type = $item['field_type'];
						endswitch;
						?>
					</div>
				<?php 
				endforeach;
				
				if ( 'exists' === $step_exists ) {
						echo '<div '. $this->get_render_attribute_string( 'submit-group' ) .'>';
							if ( 2 <= $step_count ) {
								echo '<button type="button" class="crt-step-prev">'. $this->last_prev_btn_text .'</button>';
							}

							echo $this->render_submit_button($instance);

						echo '</div>';
					echo '</div>';
				} else {
					echo '<div '. $this->get_render_attribute_string( 'submit-group' ) .'>';

						$this->render_submit_button($instance); 

					echo '</div>';
				} ?>
				
			</div>
		</form>
	  <?php
	}
}