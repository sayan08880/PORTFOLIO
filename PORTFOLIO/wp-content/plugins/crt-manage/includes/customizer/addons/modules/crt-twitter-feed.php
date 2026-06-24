<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CRT_Twitter_Feed extends Widget_Base {
	
	public function get_name() {
		return 'crt-twitter-feed';
	}

	public function get_title() {
		return esc_html__( 'Twitter Feed', 'crt-manage' );
	}

	public function get_icon() {
		return 'crt-icon eicon-twitter-feed';
	}

	public function get_categories() {
        return [ 'crt_manage_theme'];
    }

	public function get_keywords() {
		return [ 'twitter feed', 'social', 'grid' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_script_depends() {
        return [ 'crt-manage-isotope', 'crt-lightgallery', 'crt-twitter-feed' ];
	}

	public function get_style_depends() {
        return [ 'crt-animations-css', 'crt-loading-animations-css', 'crt-lightgallery-css', 'e-swiper' ];
    }

    public function get_custom_help_url() {
    	if ( empty(get_option('crt_wl_plugin_links')) )
    		return 'https://crthemes.com/contact';
    }

    public function add_control_number_of_posts() {
        $this->add_control(
            'number_of_posts',
            [
                'label' => esc_html__( 'Items Per Page', 'crt-manage' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 0
            ]
        );
    }

	public function add_control_stack_twitter_feed_slider_nav_position() {
		$this->add_control(
			'twitter_feed_slider_nav_position',
			[
				'label' => esc_html__( 'Positioning', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'custom',
				'options' => [
					'default' => esc_html__( 'Default', 'crt-manage' ),
					'custom' => esc_html__( 'Custom', 'crt-manage' ),
				],
				'prefix_class' => 'crt-grid-slider-nav-position-',
			]
		);

		$this->add_control(
			'twitter_feed_slider_nav_position_default',
			[
				'label' => esc_html__( 'Align', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'top-left',
				'options' => [
					'top-left' => esc_html__( 'Top Left', 'crt-manage' ),
					'top-center' => esc_html__( 'Top Center', 'crt-manage' ),
					'top-right' => esc_html__( 'Top Right', 'crt-manage' ),
					'bottom-left' => esc_html__( 'Bottom Left', 'crt-manage' ),
					'bottom-center' => esc_html__( 'Bottom Center', 'crt-manage' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'crt-manage' ),
				],
				'prefix_class' => 'crt-grid-slider-nav-align-',
				'condition' => [
					'twitter_feed_slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'twitter_feed_slider_nav_outer_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Outer Distance', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}}[class*="crt-grid-slider-nav-align-top"] .crt-swiper-nav-wrap' => 'top: {{SIZE}}px;',
					'{{WRAPPER}}[class*="crt-grid-slider-nav-align-bottom"] .crt-swiper-nav-wrap' => 'bottom: {{SIZE}}px;',
					'{{WRAPPER}}.crt-grid-slider-nav-align-top-left .crt-swiper-nav-wrap' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.crt-grid-slider-nav-align-bottom-left .crt-swiper-nav-wrap' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.crt-grid-slider-nav-align-top-right .crt-swiper-nav-wrap' => 'right: {{SIZE}}px;',
					'{{WRAPPER}}.crt-grid-slider-nav-align-bottom-right .crt-swiper-nav-wrap' => 'right: {{SIZE}}px;',
				],
				'condition' => [
					'twitter_feed_slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'twitter_feed_slider_nav_inner_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Inner Distance', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-swiper-nav-wrap .crt-swiper-button-prev' => 'margin-right: {{SIZE}}px;',
				],
				'condition' => [
					'twitter_feed_slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'twitter_feed_slider_nav_position_top',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position', 'crt-manage' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-swiper-button' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'twitter_feed_slider_nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'twitter_feed_slider_nav_position_left',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Left Position', 'crt-manage' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 120,
					],
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'twitter_feed_slider_nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'twitter_feed_slider_nav_position_right',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Right Position', 'crt-manage' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 120,
					],
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'twitter_feed_slider_nav_position' => 'custom',
				],
			]
		);		
	}

	public function add_control_twitter_feed_slider_dots_hr() {
		$this->add_responsive_control(
			'twitter_feed_slider_dots_hr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Position', 'crt-manage' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullets' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function add_option_element_select() {
		return [
			'username' => esc_html__( 'Username', 'crt-manage' ),
			'profile-name' => esc_html__( 'Profile Name', 'crt-manage' ),
			'profile-picture' => esc_html__( 'Profile Picture', 'crt-manage' ),
			'twit' => esc_html__( 'Tweet', 'crt-manage' ),
			'date' => esc_html__( 'Date', 'crt-manage' ),
			'read-more' => esc_html__( 'Read More', 'crt-manage' ),
			'comment' => esc_html__( 'Comment', 'crt-manage' ),
			'likes' => esc_html__( 'Likes', 'crt-manage' ),
			'retweets' => esc_html__( 'Retweets', 'crt-manage' ),
			'media' => esc_html__( 'Media', 'crt-manage' ),
			'separator' => esc_html__( 'Separator', 'crt-manage' ),
		];
	}

	// Render User Name
	public function render_post_username( $settings, $class, $item ) {
		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
		$element_username_tag = Utilities::validate_html_tags_wl( $settings['element_username_tag'], 'h2', $tags_whitelist );

		echo '<'. esc_attr($element_username_tag) .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
                echo '<a>'. $item['user']['name'] .'</a>';
			echo '</div>';
		echo '</'. esc_attr($element_username_tag) .'>';
	}

	// Render User Account
	public function render_post_user_profile_name( $settings, $class, $item ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
            echo '<small>';
                echo '<a href="https://twitter.com/' . $item['user']['screen_name'] .'">@'. $item['user']['screen_name'] .'</a>';
            echo '</small>';
			echo '</div>';
		echo '</div>';
	}

	// Render User Account
	public function render_post_user_profile_picture( $settings, $class, $item ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
            ?>
            <figure>
                <img src="<?php echo $item['user']['profile_image_url'] ?>" alt="Image">
            </figure>
            <?php
			echo '</div>';
		echo '</div>';
	}

	public function render_post_read_more ( $settings, $class, $item ) {

		// $read_more_animation = !defined('WPR_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code() ? 'crt-button-none' : $this->get_settings()['read_more_animation'];
		$read_more_animation = 'crt-change-it-later';

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a href="'. esc_url('https://twitter.com/'. $item['user']['screen_name'] .'"/status/"'. $item['id'] ) .'" class="crt-button-effect '. esc_attr($read_more_animation) .'">';

				// // Icon: Before
				// if ( 'before' === $settings['element_extra_icon_pos'] ) {
				// 	echo '<i class="crt-grid-extra-icon-left '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				// }

				// Read More Text
				echo '<span>'. esc_html( $settings['element_read_more_text'] ) .'</span>';

				// Icon: After
				// if ( 'after' === $settings['element_extra_icon_pos'] ) {
				// 	echo '<i class="crt-grid-extra-icon-right '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				// }

				echo '</a>';
			echo '</div>';
		echo '</div>';

	}

	public function render_post_caption($settings, $class, $item) {

		if ( !isset($item['full_text']) || '' === $item['full_text'] ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
                echo '<p>';
                    $string = preg_replace("~[[:alpha:]]+://[^<p>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $item['full_text']);
                    $new_string = preg_replace('/\#([a-z0-9]+)/i', '<a href="https://twitter.com/hashtag/$1?src=hashtag_click">#$1</a>', $string);
					if ( isset( $settings['element_word_count'] ) ) {
						echo wp_trim_words(preg_replace('/\@([a-z0-9]+)/i', '<a href="https://twitter.com/$1">@$1</a>' ,$new_string), $settings['element_word_count']);
					} else {
						echo preg_replace('/\@([a-z0-9]+)/i', '<a href="https://twitter.com/$1">@$1</a>' ,$new_string);
					}
                echo '</p>';
			echo '</div>';
		echo '</div>';
	}

	public function render_post_date($settings, $class, $item) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';

				// Text: Before
				// if ( 'before' === $settings['element_extra_text_pos'] ) {
				// 	echo '<span class="crt-twitter-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				// }
				// Icon: Before
				// if ( 'before' === $settings['element_extra_icon_pos'] ) {
				// 	echo '<i class="crt-twitter-feed-extra-icon-left '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				// }

                // echo wp_date(get_option( 'date_format' ), strtotime($item['created_at'])) 
                echo human_time_diff(strtotime($item['created_at'])) .' '. esc_html__('ago', 'crt-manage');

				// Icon: After
				// if ( 'after' === $settings['element_extra_icon_pos'] ) {
				// 	echo '<i class="crt-twitter-feed-extra-icon-right '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				// }
				// Text: After
				// if ( 'after' === $settings['element_extra_text_pos'] ) {
				// 	echo '<span class="crt-twitter-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				// }
				
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}
	
	public function render_post_lightbox( $settings, $class, $item ) { 
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				$lightbox_source = $item[''];

				// Lightbox Button
				echo '<span data-src="'. esc_url( $lightbox_source ) .'">';
				
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="crt-twitter-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

					// Lightbox Icon
					echo '<i class="'. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';

					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="crt-twitter-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

				echo '</span>';

				// Media Overlay
				if ( 'yes' === $settings['element_lightbox_overlay'] ) {
					echo '<div class="crt-twitter-feed-lightbox-overlay"></div>';
				}
			echo '</div>';
		echo '</div>';
	}

	public function render_post_likes($settings, $class, $item) {

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">'; 

            ?>
            <a href="https://twitter.com/intent/like?tweet_id=<?php echo $item['id'] ?>&related=<?php echo $item['user']['screen_name'] ?>" target="_blank" title="Likes">
                <span class=""><i class="fas fa-heart"></i></span>
                <span class="crt-tweet-likes">
                    <?php echo $item['favorite_count'] ?>
                </span>
            </a>
            <?php

			echo '</div>';
		echo '</div>';
	}

	public function render_post_comment($settings, $class, $item) {

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">'; 

            ?>
            <a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $item['id'] ?>&related=<?php echo $item['user']['screen_name'] ?>" target="_blank" title="Comments">
                <span class=""><i class="fas fa-comment"></i></span>
            </a>
            <?php

			echo '</div>';
		echo '</div>';
	}

	public function render_post_retweets($settings, $class, $item) {

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">'; 

            ?>
            <a href="https://twitter.com/intent/retweet?tweet_id=<?php echo $item['id'] ?>&related=<?php echo $item['user']['screen_name'] ?>" target="_blank" title="Retweets">
                <span class=""><i class="fa fa-retweet"></i></span>
                <span class="crt-retweets">
                    <?php echo $item['retweet_count'] ?>
                </span>
            </a>
            <?php

			echo '</div>';
		echo '</div>';
	}

	// Render Post Element Separator
	public function render_post_element_separator( $settings, $class ) {
		echo '<div class="crt-twitter-feed-sep-style-1 '. esc_attr($class) .'">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}

	public function render_post_media($settings, $class, $item) {
		
		if ( isset($item['extended_entities']) && null !== $item['extended_entities'] ) {
			if ( $item['extended_entities']['media'] ) {
				$media = $item['extended_entities']['media'];
			} else if ( isset( $item['retweeted_status']['entities']['media'] ) ) {
				$media = $item['retweeted_status']['entities']['media'];
			} else if ( isset( $item['quoted_status']['entities']['media'] ) ) {
				$media = $item['quoted_status']['entities']['media'];
			} else {
				$media = [];
			}
		}

		if ( !empty($media) ) {
			echo '<div class="'. esc_attr($class) .'">';
				echo '<div class="inner-block">'; 
					// && $media[0]['type'] == 'photo'
					echo (isset( $media[0] )) ? '<img class="crt-twit-image" src="' . $media[0]['media_url_https'] . '">' : '';
				echo '</div>';
			echo '</div>';
		}
	}

	// Get Elements
	public function get_elements( $type, $settings, $class, $item ) {
		if ( 'pro-lk' == $type || 'pro-shr' == $type || 'pro-cf' == $type ) {
			$type = 'title';
		}

		switch ( $type ) {
	

			case 'username':
				$this->render_post_username( $settings, $class, $item );
				break;

			case 'profile-name':
				$this->render_post_user_profile_name( $settings, $class, $item );
				break;

			case 'twit':
				$this->render_post_caption( $settings, $class, $item );
				break;

			case 'date':
				$this->render_post_date( $settings, $class, $item );
				break;

            case 'likes':
            	$this->render_post_likes( $settings, $class, $item );
            	break;

			case 'comment':
				$this->render_post_comment( $settings, $class, $item );
				break;

            case 'retweets':
            	$this->render_post_retweets( $settings, $class, $item );
            	break;

            case 'media':
            	$this->render_post_media( $settings, $class, $item );
            	break;
                
            case 'profile-picture':
            	$this->render_post_user_profile_picture( $settings, $class, $item );
            	break;

			case 'read-more':
				$this->render_post_read_more( $settings, $class, $item );
				break;

			// case 'lightbox':
			// 	$this->render_post_lightbox( $settings, $class, $item );
			// 	break;

			case 'separator':
				$this->render_post_element_separator( $settings, $class );
				break;
		}

	}

	// Get Elements by Location
	public function get_elements_by_location( $location, $settings, $item ) {
		$locations = [];

		foreach ( $settings['twitter_feed_elements'] as $data ) {
			$place = $data['element_location'];
			$align_vr = $data['element_align_vr'];

			if ( ! isset($locations[$place]) ) {
				$locations[$place] = [];
			}
			
			if ( 'over' === $place ) {
				if ( ! isset($locations[$place][$align_vr]) ) {
					$locations[$place][$align_vr] = [];
				}

				array_push( $locations[$place][$align_vr], $data );
			} else {
				array_push( $locations[$place], $data );
			}
		}

		if ( ! empty( $locations[$location] ) ) {

			if ( 'over' === $location ) {
				foreach ( $locations[$location] as $align => $thiss ) {

					if ( 'middle' === $align ) {
						echo '<div class="crt-cv-container"><div class="crt-cv-outer"><div class="crt-cv-inner">';
					}

					echo '<div class="crt-twitter-feed-media-hover-'. esc_attr($align) .' elementor-clearfix">';
						foreach ( $thiss as $data ) {
							
							// Get Class
							$class  = 'crt-twitter-feed-item-'. $data['element_select'];
							$class .= ' elementor-repeater-item-'. $data['_id'];
							$class .= ' crt-twitter-feed-item-display-'. $data['element_display'];
							$class .= ' crt-twitter-feed-item-align-'. $data['element_align_hr'];
							$class .= $this->get_animation_class( $data, 'element' );

							// Element
							$this->get_elements( $data['element_select'], $data, $class, $item );
						}
					echo '</div>';

					if ( 'middle' === $align ) {
						echo '</div></div></div>';
					}
				}
			} else {
				echo '<div class="crt-twitter-feed-item-'. esc_attr($location) .'-content elementor-clearfix">';
					foreach ( $locations[$location] as $data ) {

						// Get Class
						$class  = 'crt-twitter-feed-item-'. $data['element_select'];
						$class .= ' elementor-repeater-item-'. $data['_id'];
						$class .= ' crt-twitter-feed-item-display-'. $data['element_display'];
						$class .= ' crt-twitter-feed-item-align-'. $data['element_align_hr'];

						// Element
						$this->get_elements( $data['element_select'], $data, $class, $item );
					}
				echo '</div>';
			}

		}
	}

	protected function register_controls() {
        $this->start_controls_section(
            'section_twitter_feeds_settings',
            [
                'label' => esc_html__('Account Settings', 'crt-manage'),
            ]
        );

		Utilities::crt_library_buttons( $this, Controls_Manager::RAW_HTML );

		$repeater = new Repeater();


        $repeater->add_control(
            'twitter_feed_account_name',
            [
                'label' => esc_html__('Profile Name', 'crt-manage'),
                'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
                'default' => '@elemntor',
                'label_block' => false,
                'description' => esc_html__('Use @ sign with your profile name.', 'crt-manage'),
            ]
        );

		$this->add_control(
			'twitter_accounts',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'twitter_feed_account_name' => '@elemntor',
                    ],
				],
				'title_field' => '{{{ twitter_feed_account_name }}}',
			]
		);


        $this->add_control(
            'twitter_feed_hashtag_name',
            [
                'label' => esc_html__('Hashtag Name', 'crt-manage'),
                'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
                'label_block' => true,
                'description' => esc_html__('Enter comma-separated list and remove # sign from your hashtag name', 'crt-manage'),
            ]
        );

		$this->add_control_number_of_posts();


        $this->add_control(
            'twitter_feed_consumer_key',
            [
                'label' => esc_html__('Consumer Key', 'crt-manage'),
                'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
                'label_block' => false,
                'default' => '',
                'description' => '<a href="https://developer.twitter.com/en/docs/authentication/oauth-1-0a/api-key-and-secret" target="_blank">Get Consumer Key.</a> Create a new app or select existing app and grab the <b>consumer key.</b>',
            ]
        );

        $this->add_control(
            'twitter_feed_consumer_secret',
            [
                'label' => esc_html__('Consumer Secret', 'crt-manage'),
                'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
                'label_block' => false,
                'default' => '',
                'description' => '<a href="https://developer.twitter.com/en/docs/authentication/oauth-1-0a/api-key-and-secret" target="_blank">Get Consumer Secret.</a> Create a new app or select existing app and grab the <b>consumer secret.</b>',
            ]
        );

        $this->add_control(
            'auto_clear_cache',
            [ 
                'label' => esc_html__( 'Auto Cache Clear', 'crt-manage' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'crt-manage' ),
                'label_off'    => __( 'No', 'crt-manage' ),
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'twitter_feed_cache_limit',
            [
                'label'       => __( 'Data Cache Time', 'crt-manage' ),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 1,
                'default'     => 60,
                'description' => __( 'Cache expiration time (Minutes)', 'crt-manage' ),
                'condition'   => [
                    'auto_clear_cache' => 'yes'
                ]
            ]
        );

        // $this->add_control(
        //     'clear_cache_control',
        //     [
        //         'label'       => __( 'Clear Cache', 'crt-manage' ),
        //         'type'        => Controls_Manager::BUTTON,
        //         'text'        => __( 'Clear', 'crt-manage' ),
        //         'event'       => 'crt-clear-cache',
        //         'description' => esc_html__( 'Note: This will refresh your feed and fetch the latest data from your Twitter account', 'crt-manage' ),
        //         'condition'   => [
        //             'auto_clear_cache' => 'yes'
        //         ]
        //     ]
        // );

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Layout ---------
		$this->start_controls_section(
			'section_feed_layout',
			[
				'label' => esc_html__( 'Layout', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout_select',
			[
				'label' => esc_html__('Select Layout', 'crt-manage'),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'label_block' => false,
				'default' => 'masonry',
				'prefix_class' => 'crt-twitter-feed-'	,
				'render_type' => 'template',
				'separator' => 'before',
				'options' => [
					'grid'     => esc_html__('Grid', 'crt-manage'),
					'masonry' => esc_html__('Masonry', 'crt-manage'),
					'carousel' => esc_html__('Slider\Carousel', 'crt-manage'),
					// 'list'     => esc_html__('List Style', 'crt-manage'),
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'              => esc_html__('Columns', 'crt-manage'),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'options'            => [
					'1' => esc_html__('One', 'crt-manage'),
					'2'    => esc_html__('Two', 'crt-manage'),
					'3'    => esc_html__('Three', 'crt-manage'),
					'4'    => esc_html__('Four', 'crt-manage'),
					'5'    => esc_html__('Five', 'crt-manage'),
					'6'    => esc_html__('Six', 'crt-manage'),
					'7'    => esc_html__('Seven', 'crt-manage'),
					'8'    => esc_html__('Eight', 'crt-manage'),
					'9'    => esc_html__('Nine', 'crt-manage'),
					'10'    => esc_html__('Ten', 'crt-manage'),
					'11'    => esc_html__('Eleven', 'crt-manage'),
					'12'    => esc_html__('Twelve', 'crt-manage'),
				],
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-grid .crt-twitter-feed' => 'column-count: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-list .crt-twitter-feed' => 'column-count: {{VALUE}}',
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'condition' => [
					'layout_select!' => 'carousel'
				]
			]
		);

		$this->add_responsive_control(
			'gutter',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid' => 'column-gap: {{SIZE}}px;',
					'{{WRAPPER}} .crt-list' => 'column-gap: {{SIZE}}px;',
				],
				'condition' => [
					'layout_select!' => 'carousel'
				],
			]
		);

		$this->add_responsive_control(
			'distance_bottom',
			[
				'label' => esc_html__( 'Vertical Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-grid .crt-twitter-feed' => 'row-gap: {{SIZE}}px',
					'{{WRAPPER}}.crt-twitter-feed-list .crt-twitter-feed' => 'row-gap: {{SIZE}}px',
				],
				'condition' => [
					'layout_select!' => 'carousel'
				]
			]
		);

		$this->add_control(
			'show_header',
			[
				'label' => esc_html__( 'Show Header', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$this->add_responsive_control(
			'header_info_style',
			[
				'label' => esc_html__( 'Header Info Style', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'inline' => esc_html__('Inline', 'crt-manage'),
					'block' => esc_html__('Block', 'crt-manage'),
				],
				'selectors_dictionary' => [
					'inline' => 'display: flex;',
					'block' => 'display: block;'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-tf-header-profile-img' => '{{VALUE}}'
				],
				'default' => 'inline',
				'condition' => [
					'show_header' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'header_info_vertical_align',
			[
				'label' => esc_html__( 'Vertical Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
                'default' => 'flex-end',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'crt-manage' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'crt-manage' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'crt-manage' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-tf-header-profile-img' => 'align-items: {{VALUE}};'
				],
				'condition' => [
					'show_header' => 'yes',
					'header_info_style' => 'inline'
				]
			]
		);

		$this->add_responsive_control(
			'header_info_margin',
			[
				'label' => esc_html__( 'Header Info Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-tf-statistics' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => esc_html__( 'Show Pagination', 'crt-manage' ),
				'description' => esc_html__('Please note that Pagination doesn\'t work in editor', 'crt-manage'),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select!' => 'carousel',
				]
			]
		);

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Elements ---------
		$this->start_controls_section(
			'section_feed_elements',
			[
				'label' => esc_html__( 'Elements', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$this_select = $this->add_option_element_select();

		$repeater->add_control(
			'element_select',
			[
				'label' => esc_html__( 'Select Element', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this_select,
				'default' => 'caption',
				'separator' => 'after'
			]
		);

		// Upgrade to Pro Notice
		$repeater->add_control(
			'element_location',
			[
				'label' => esc_html__( 'Location', 'crt-manage' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'above',
			]
		);

		$repeater->add_control(
			'element_display',
			[
				'label' => esc_html__( 'Display', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'inline' => esc_html__( 'Inline', 'crt-manage' ),
					'block' => esc_html__( 'Seperate Line', 'crt-manage' ),
					'custom' => esc_html__( 'Custom Width', 'crt-manage' ),
				],
			]
		);

		$repeater->add_control(
			'element_custom_width',
			[
				'label' => esc_html__( 'Element Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
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
				'condition' => [
					'element_display' => 'custom',
				],
			]
		);


		$repeater->add_control(
			'element_align_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
                'default' => 'middle',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'crt-manage' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'crt-manage' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'crt-manage' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'condition' => [
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
            'element_align_hr',
            [
                'label' => esc_html__( 'Horizontal Align', 'crt-manage' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'crt-manage' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'crt-manage' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'crt-manage' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'after'
            ]
        );

		$repeater->add_control(
			'element_username_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h2',
				'condition' => [
					'element_select' => 'username',
				]
			]
		);
		
		$repeater->add_control(
			'show_word_count',
			[
				'label' => esc_html__( 'Show Word Count', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => esc_html__('Hashtags and Links won\'t be clickable in case of using this feature'),
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'element_select' => [ 'twit' ]
				]
			]
		);

		$repeater->add_control(
			'element_word_count',
			[
				'label' => esc_html__( 'Word Count', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 20,
				'min' => 1,
				'condition' => [
					'show_word_count' => 'yes',
					'element_select' => [ 'twit' ]
				]
			]
		);

		$repeater->add_control(
			'element_read_more_text',
			[
				'label' => esc_html__( 'Read More Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Read More',
				'condition' => [
					'element_select' => [ 'read-more' ],
				],
				'separator' => 'after'
			]
		);

		// $repeater->add_control( 'element_like_icon', $this->add_repeater_args_element_like_icon() );

		// $repeater->add_control( 'element_like_show_count', $this->add_repeater_args_element_like_show_count() );

		// $repeater->add_control( 'element_like_text', $this->add_repeater_args_element_like_text() );

		// $repeater->add_control( 'element_sharing_icon_1', $this->add_repeater_args_element_sharing_icon_1() );

		// $repeater->add_control( 'element_sharing_icon_2', $this->add_repeater_args_element_sharing_icon_2() );

		// $repeater->add_control( 'element_sharing_icon_3', $this->add_repeater_args_element_sharing_icon_3() );

		// $repeater->add_control( 'element_sharing_icon_4', $this->add_repeater_args_element_sharing_icon_4() );

		// $repeater->add_control( 'element_sharing_icon_5', $this->add_repeater_args_element_sharing_icon_5() );

		// $repeater->add_control( 'element_sharing_icon_6', $this->add_repeater_args_element_sharing_icon_6() );

		// $repeater->add_control( 'element_sharing_trigger', $this->add_repeater_args_element_sharing_trigger() );

		// $repeater->add_control( 'element_sharing_trigger_icon', $this->add_repeater_args_element_sharing_trigger_icon() );

		// $repeater->add_control( 'element_sharing_trigger_action', $this->add_repeater_args_element_sharing_trigger_action() );

		// $repeater->add_control( 'element_sharing_trigger_direction', $this->add_repeater_args_element_sharing_trigger_direction() );

		// $repeater->add_control( 'element_sharing_tooltip', $this->add_repeater_args_element_sharing_tooltip() );

		$repeater->add_control(
			'element_lightbox_pfa_select',
			[
				'label' => esc_html__( 'Post Format Audio', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'crt-manage' ),
					'meta' => esc_html__( 'Meta Value', 'crt-manage' ),
				],
				'condition' => [
					'element_select' => 'lightbox',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_pfv_select',
			[
				'label' => esc_html__( 'Post Format Video', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'crt-manage' ),
					'meta' => esc_html__( 'Meta Value', 'crt-manage' ),
				],
				'condition' => [
					'element_select' => 'lightbox',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'after',
				'condition' => [
					'element_select' => [ 'lightbox' ],
				],
			]
		);

		// $repeater->add_control(
		// 	'element_extra_text_pos',
		// 	[
		// 		'label' => esc_html__( 'Extra Text Display', 'crt-manage' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'options' => [
		// 			'none' => esc_html__( 'None', 'crt-manage' ),
		// 			'before' => esc_html__( 'Before Element', 'crt-manage' ),
		// 			'after' => esc_html__( 'After Element', 'crt-manage' ),
		// 		],
		// 		'default' => 'none',
		// 		'condition' => [
		// 			'element_select' => [
		// 				'lightbox',
		// 			],
		// 		]
		// 	]
		// );

		// $repeater->add_control(
		// 	'element_extra_text',
		// 	[
		// 		'label' => esc_html__( 'Extra Text', 'crt-manage' ),
		// 		'type' => Controls_Manager::TEXT,
		// 		'default' => '',
		// 		'condition' => [
		// 			'element_select!' => [
		// 				'lightbox',
		// 			],
		// 			'element_extra_text_pos!' => 'none'
		// 		]
		// 	]
		// );

		// $repeater->add_control(
		// 	'element_extra_icon_pos',
		// 	[
		// 		'label' => esc_html__( 'Extra Icon Position', 'crt-manage' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'options' => [
		// 			'none' => esc_html__( 'None', 'crt-manage' ),
		// 			'before' => esc_html__( 'Before Element', 'crt-manage' ),
		// 			'after' => esc_html__( 'After Element', 'crt-manage' ),
		// 		],
		// 		'default' => 'none',
		// 		'condition' => [
		// 			'element_select!' => [
		// 				'lightbox',
		// 			],
		// 		]
		// 	]
		// );

		// $repeater->add_control(
		// 	'element_extra_icon',
		// 	[
		// 		'label' => esc_html__( 'Select Icon', 'crt-manage' ),
		// 		'type' => Controls_Manager::ICONS,
		// 		'skin' => 'inline',
		// 		'label_block' => false,
		// 		'default' => [
		// 			'value' => 'fas fa-search',
		// 			'library' => 'fa-solid',
		// 		],
		// 		'condition' => [
		// 			'element_select!' => [
		// 				'lightbox'
		// 			],
		// 			'element_extra_icon_pos!' => 'none'
		// 		]
		// 	]
		// );

		// $repeater->add_control(
		// 	'animation_divider',
		// 	[
		// 		'type' => Controls_Manager::DIVIDER,
		// 		'style' => 'thick',
		// 		'condition' => [
		// 			'element_location' => 'over' 
		// 		],
		// 	]
		// );

		// $repeater->add_control(
		// 	'element_animation',
		// 	[
		// 		'label' => esc_html__( 'Select Animation', 'crt-manage' ),
		// 		'type' => 'crt-animations',
		// 		'default' => 'none',
		// 		'condition' => [
		// 			'element_location' => 'over' 
		// 		],
		// 	]
		// );

		// Upgrade to Pro Notice :TODO
		// Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'twitter-feed', 'element_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

		// $repeater->add_control(
		// 	'element_animation_duration',
		// 	[
		// 		'label' => esc_html__( 'Animation Duration', 'crt-manage' ),
		// 		'type' => Controls_Manager::NUMBER,
		// 		'default' => 0.3,
		// 		'min' => 0,
		// 		'max' => 5,
		// 		'step' => 0.1,
		// 		'selectors' => [
		// 			'{{WRAPPER}} {{CURRENT_ITEM}}' => 'transition-duration: {{VALUE}}s;'
		// 		],
		// 		'condition' => [
		// 			'element_animation!' => 'none',
		// 			'element_location' => 'over',
		// 		],
		// 	]
		// );

		// $repeater->add_control(
		// 	'element_animation_delay',
		// 	[
		// 		'label' => esc_html__( 'Animation Delay', 'crt-manage' ),
		// 		'type' => Controls_Manager::NUMBER,
		// 		'default' => 0,
		// 		'min' => 0,
		// 		'max' => 5,
		// 		'step' => 0.1,
		// 		'selectors' => [
		// 			'{{WRAPPER}} .crt-animation-wrap:hover {{CURRENT_ITEM}}' => 'transition-delay: {{VALUE}}s;'
		// 		],
		// 		'condition' => [
		// 			'element_animation!' => 'none',
		// 			'element_location' => 'over' 
		// 		],
		// 	]
		// );

		// $repeater->add_control(
		// 	'element_animation_timing',
		// 	[
		// 		'label' => esc_html__( 'Animation Timing', 'crt-manage' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'options' => Utilities::crt_animation_timings(),
		// 		'default' => 'ease-default',
		// 		'condition' => [
		// 			'element_animation!' => 'none',
		// 			'element_location' => 'over' 
		// 		],
		// 	]
		// );

		// Upgrade to Pro Notice
		// Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'twitter-feed', 'element_animation_timing', ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk',] );

		// $repeater->add_control(
		// 	'element_animation_size',
		// 	[
		// 		'label' => esc_html__( 'Animation Size', 'crt-manage' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'options' => [
		// 			'small' => esc_html__( 'Small', 'crt-manage' ),
		// 			'medium' => esc_html__( 'Medium', 'crt-manage' ),
		// 			'large' => esc_html__( 'Large', 'crt-manage' ),
		// 		],
		// 		'default' => 'large',
		// 		'condition' => [
		// 			'element_animation!' => 'none',
		// 			'element_location' => 'over' 
		// 		],
		// 	]
		// );

		// $repeater->add_control(
		// 	'element_animation_tr',
		// 	[
		// 		'label' => esc_html__( 'Animation Transparency', 'crt-manage' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'default' => 'yes',
		// 		'return_value' => 'yes',
		// 		'condition' => [
		// 			'element_animation!' => 'none',
		// 			'element_location' => 'over' 
		// 		],
		// 	]
		// );

		$repeater->add_control(
			'element_show_on_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'element_select' => ['twit', 'username'],
				],
			]
		);

		// GOGA - twitter prefix added for styling reasons
		$repeater->add_responsive_control(
			'element_show_on',
			[
				'label' => esc_html__( 'Show on this Device', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'position: absolute; left: -99999999px;',
					'yes' => 'position: static; left: auto;'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'twitter_feed_elements',
			[
				'label' => esc_html__( 'Feed Elements', 'crt-manage' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'element_select' => 'profile-picture',
						'element_location' => 'above',
						'element_display' => 'inline',
						'element_align_hr' => 'left'
					],
					[
						'element_select' => 'username',
						'element_location' => 'above',
						'element_display' => 'inline',
						'element_align_hr' => 'left'
					],
					[
						'element_select' => 'profile-name',
						'element_location' => 'above',
						// 'element_display' => 'inline',
						// 'element_align_hr' => 'right'
					],
					[
						'element_select' => 'date',
						// 'element_display' => 'inline',
						// 'element_extra_text_pos' => 'after',
						// 'element_extra_text' => '/',
						'element_location' => 'above',
						// 'element_align_hr' => 'left'
					],
					[
						'element_select' => 'twit',
						'element_location' => 'above',
					],
					[
						'element_select' => 'read-more',
						// 'element_display' => 'inline',
						// 'element_extra_text_pos' => 'after',
						// 'element_extra_text' => '/',
						'element_location' => 'above',
						// 'element_align_hr' => 'left'
					],
					[
						'element_select' => 'media',
						'element_location' => 'above',
					],
					[
						'element_select' => 'separator',
						'element_location' => 'above',
					],
					[
						'element_select' => 'comment',
						'element_location' => 'above',
						'element_display' => 'inline',
						'element_align_hr' => 'left'
					],
					[
						'element_select' => 'likes',
						'element_location' => 'above',
						'element_display' => 'inline',
						'element_align_hr' => 'left'
					],
					[
						'element_select' => 'retweets',
						'element_location' => 'above',
						'element_display' => 'inline',
						'element_align_hr' => 'left'
					],
				],
				'title_field' => '{{{ element_select.charAt(0).toUpperCase() + element_select.slice(1) }}}',
			]
		);

        $this->end_controls_section();

		// Tab: Content ===============
		// Section: Carousel -----------
		$this->start_controls_section(
			'section_carousel_settings',
			[
				'label' => esc_html__( 'Carousel', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'layout_select' => 'carousel'
				]
			]
		);

		$this->add_responsive_control(
			'twitter_feed_slides_to_show',
			[
				'label' => esc_html__( 'Slides To Show', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'separator' => 'before',
				'default' => 3,
				'widescreen_default' => 3,
				'laptop_default' => 3,
				'tablet_extra_default' => 2,
				'tablet_default' => 2,
				'mobile_extra_default' => 1,
				'mobile_default' => 1,
				'frontend_available' => true,
				// 'min' => 1,
				'condition' => [
					'layout_select' => 'carousel',
				]
			]
		);
				
		$this->add_responsive_control(
			'twitter_feed_space_between',
			[
				'label' => __( 'Gutter', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 5,
				'widescreen_default' => 5,
				'laptop_default' => 5,
				'tablet_extra_default' => 5,
				'tablet_default' => 5,
				'mobile_extra_default' => 5,
				'mobile_default' => 5,
				'condition' => [
					'layout_select' => 'carousel',
				]
			]
		);
				
		$this->add_control(
			'twitter_feed_speed',
			[
				'label' => __( 'Speed', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 500,
				'condition' => [
					'layout_select' => 'carousel',
				]
			]
		);

		$this->add_control (
			'enable_cs_nav',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Navigation', 'crt-manage' ),
				'render_type' => 'template',
				'separator' => 'before',
				'default' => 'yes',
				'condition' => [
					'layout_select' => 'carousel'
				]
			]
		);

		$this->add_control(
			'cs_nav_arrows',
			[
				'label' => esc_html__( 'Navigation Icon', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle',
				'options' => [
					'fas fa-angle' => esc_html__( 'Angle', 'crt-manage' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'crt-manage' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'crt-manage' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'crt-manage' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'crt-manage' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'crt-manage' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'crt-manage' ),
				],
				'condition' => [
					'layout_select' => 'carousel',
					'enable_cs_nav' => 'yes'
				]
			]
		);

		$this->add_control (
			'enable_cs_pag',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Pagination', 'crt-manage' ),
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'carousel'
				]
			]
		);

		$this->add_control(
			'cs_pag_type',
			[
				'label' => esc_html__( 'Pagination Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bullets',
				'options' => [
					'bullets' => esc_html__( 'Bullets', 'crt-manage' ),
					'fraction' => esc_html__( 'Fraction', 'crt-manage' ),
					'progressbar' => esc_html__( 'Progressbar', 'crt-manage' ),
				],
				'condition' => [
					'layout_select' => 'carousel',
					'enable_cs_pag' => 'yes',
				]
			]
		);

		$this->add_control (
			'enable_twitter_feed_slider_autoplay',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Autoplay', 'crt-manage' ),
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'carousel'
				]
			]
		);
				
		$this->add_control(
			'twitter_feed_delay',
			[
				'label' => __( 'Delay', 'crt-manage' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 1000,
				'condition' => [
					'layout_select' => 'carousel',
					'enable_twitter_feed_slider_autoplay' => 'yes'
				]
			]
		);

		$this->add_control (
			'enable_twitter_feed_slider_loop',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Loop', 'crt-manage' ),
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'carousel'
				]
			]
		);

		$this->end_controls_section();

		// Tab: Content ==============
		// Section: Pagination -------
		$this->start_controls_section(
			'section_twitter_feed_pagination',
			[
				'label' => esc_html__( 'Pagination', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'layout_select!' => 'carousel',
					'show_pagination' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_load_more_text',
			[
				'label' => esc_html__( 'Load More Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Load More',
			]
		);

		$this->add_control(
			'pagination_finish_text',
			[
				'label' => esc_html__( 'Finish Text', 'crt-manage' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'End of Content.',
			]
		);

		$this->add_control(
			'pagination_animation',
			[
				'label' => esc_html__( 'Select Animation', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'loader-1',
				'options' => [
					'none' => esc_html__( 'None', 'crt-manage' ),
					'loader-1' => esc_html__( 'Loader 1', 'crt-manage' ),
					'loader-2' => esc_html__( 'Loader 2', 'crt-manage' ),
					'loader-3' => esc_html__( 'Loader 3', 'crt-manage' ),
					'loader-4' => esc_html__( 'Loader 4', 'crt-manage' ),
					'loader-5' => esc_html__( 'Loader 5', 'crt-manage' ),
					'loader-6' => esc_html__( 'Loader 6', 'crt-manage' ),
				],
			]
		);

		$this->add_control(
			'pagination_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'crt-manage' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'center',
				'prefix_class' => 'crt-grid-pagination-',
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::crt_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'twitter-feed', [
			'Unlimited Number of Posts'
		] );

		// Styles ====================
		// Section: Slider Navigation -------
		$this->start_controls_section(
			'section_style_header',
			[
				'label' => esc_html__( 'Header', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'show_header' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'header_content_horizontal_distance',
			[
				'label' => esc_html__( 'Horizontal Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-tf-header-profile-img' => 'margin-left: {{SIZE}}px;',
					'{{WRAPPER}} .crt-twitter-follow-btn-wrap' => 'margin-right: {{SIZE}}px;'
				],
			]
		);

		$this->add_responsive_control(
			'header_profile_image_size',
			[
				'label' => esc_html__( 'Profile Image Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 200,
				],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .crt-tf-header-profile-img img' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
					'{{WRAPPER}} .crt-tf-header-profile-img' => 'margin-top: calc(-{{SIZE}}px/2);'
				],
			]
		);

		$this->add_control(
			'header_username',
			[
				'label' => esc_html__( 'Header Username', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'header_username_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .crt-tf-header-user-name' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'crt-manage' ),
				'name' => 'header_username_typography',
				'selector' => '{{WRAPPER}} .crt-tf-header-user-name',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '20',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'header_account_name',
			[
				'label' => esc_html__( 'Header Profile Name', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'header_account_name_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .crt-tf-header-user-acc-name a' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'crt-manage' ),
				'name' => 'header_account_name_typography',
				'selector' => '{{WRAPPER}} .crt-tf-header-user-acc-name',
			]
		);

		$this->add_control(
			'header_stats',
			[
				'label' => esc_html__( 'Statistics', 'crt-manage' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'header_stats_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#1D9BF0',
				'selectors' => [
					'{{WRAPPER}} .crt-tf-statistics span' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'crt-manage' ),
				'name' => 'header_stats_typography',
				'selector' => '{{WRAPPER}} .crt-tf-statistics span',
			]
		);

		$this->add_control(
			'header_stats_distance',
			[
				'label' => esc_html__( 'Horizontal Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-tf-statistics>span:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Tab: Styles ===============
		// Section: Feed -----------
		$this->start_controls_section(
			'section_style_feed',
			[
				'label' => esc_html__( 'Feed', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'feed_item_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .crt-tweet' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'feed_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-tweet' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'feed_item_shadow',
				'selector' => '{{WRAPPER}} .crt-tweet',
				// 'fields_options' => [
                //     'box_shadow_type' =>
                //         [ 
                //             'default' =>'yes' 
                //         ],
                //     'box_shadow' => [
                //         'default' =>
                //             [
                //                 'horizontal' => 0,
                //                 'vertical' => 0,
                //                 'blur' => 3,
                //                 'spread' => 0,
                //                 'color' => '#22222266'
                //             ]
                //     ]
				// ]
			]
		);

		$this->add_control(
			'feed_item_border_type',
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
					'{{WRAPPER}} .crt-tweet' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'feed_item_border_width',
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
					'{{WRAPPER}} .crt-tweet' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'feed_item_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'feed_item_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-tweet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-twitter-profile-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
				]
			]
		);

		$this->add_responsive_control(
			'feed_item_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-above-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-twitter-feed-item-below-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template'
			]
		);

        $this->end_controls_section();

		// Styles ====================
		// Section: Username ------------
		$this->start_controls_section(
			'section_style_username',
			[
				'label' => esc_html__( 'Username', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_username_style' );

		$this->start_controls_tab(
			'tab_grid_username_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-username .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-username .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'title_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-username .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .crt-twitter-feed-item-username a',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '23',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_username_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'title_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-username .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-username .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'title_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-username .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		// $this->add_control_username_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// $this->add_control_username_pointer();

		// $this->add_control_username_pointer_height();

		// $this->add_control_username_pointer_animation();

		$this->add_control(
			'title_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-username .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-twitter-feed-item-username .crt-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-twitter-feed-item-username .crt-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_border_type',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-username .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_border_width',
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
					'{{WRAPPER}} .crt-twitter-feed-item-username .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'title_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-username .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-username .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Read More --------
		$this->start_controls_section(
			'section_style_account_name',
			[
				'label' => esc_html__( 'Profile Name', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_account_name_style' );

		$this->start_controls_tab(
			'tab_grid_account_name_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'account_name_bg_color',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a'
			]
		);

		$this->add_control(
			'account_name_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#1D9BF0',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'account_name_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_account_name_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'account_name_bg_color_hr',
				'label' => esc_html__( 'Background', 'crt-manage' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a.crt-button-none:hover, {{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a:before, {{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a:after'
			]
		);

		$this->add_control(
			'account_name_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'account_name_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'account_name_box_shadow_hr',
				'selector' => '{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block :hover a',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'account_name_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		// $this->add_control_account_name_animation();

		$this->add_control(
			'account_name_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		// $this->add_control_account_name_animation_height();

		$this->add_control(
			'account_name_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'account_name_typography',
				'selector' => '{{WRAPPER}} .crt-twitter-feed-item-profile-name a',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size'   => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'account_name_border_type',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'account_name_border_width',
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
					'{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'account_name_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'account_name_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'account_name_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'account_name_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-profile-name .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Caption ----------
		$this->start_controls_section(
			'section_style_twit',
			[
				'label' => esc_html__( 'Tweet', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'caption_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6A6A6A',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-twit .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'caption_link_color',
			[
				'label'  => esc_html__( 'Link Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#1D9BF0',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-twit .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'caption_link_color_hr',
			[
				'label'  => esc_html__( 'Link Color (Hover)', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6A6A6A',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-twit .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'caption_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-twit .inner-block' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'caption_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-twit .inner-block' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_typography',
				'selector' => '{{WRAPPER}} .crt-twitter-feed-item-twit',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'caption_justify',
			[
				'label' => esc_html__( 'Justify Text', 'crt-manage' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'widescreen_default' => '',
				'laptop_default' => '',
				'tablet_extra_default' => '',
				'tablet_default' => '',
				'mobile_extra_default' => '',
				'mobile_default' => '',
				'selectors_dictionary' => [
					'' => '',
					'yes' => 'text-align: justify;'
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-twit .inner-block' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'caption_width',
			[
				'label' => esc_html__( 'Caption Width', 'crt-manage' ),
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
					'{{WRAPPER}} .crt-twitter-feed-item-twit .inner-block' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption_border_type',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-twit .inner-block' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption_border_width',
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
					'{{WRAPPER}} .crt-twitter-feed-item-twit .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'caption_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'caption_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-twit .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'caption_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-twit .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Date -------------
		$this->start_controls_section(
			'section_style_date',
			[
				'label' => esc_html__( 'Date', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-date .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-date .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'date_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-date .inner-block > span' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'selector' => '{{WRAPPER}} .crt-twitter-feed-item-date',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '13',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'date_border_type',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-date .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_border_width',
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
					'{{WRAPPER}} .crt-twitter-feed-item-date .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'date_border_type!' => 'none',
				],
			]
		);
		
		$this->add_responsive_control(
			'date_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-date .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'date_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 7,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-date .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Read More ------------
		$this->start_controls_section(
			'section_style_read_more',
			[
				'label' => esc_html__( 'Read More', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_read_more_style' );

		$this->start_controls_tab(
			'tab_grid_read_more_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#1D9BF0',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-read-more .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'read_more_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-read-more .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'read_more_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-read-more .inner-block a' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'read_more_typography',
				'selector' => '{{WRAPPER}} .crt-twitter-feed-item-read-more a',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_read_more_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'read_more_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-read-more .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'read_more_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-read-more .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'read_more_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-read-more .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		// $this->add_control_username_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// $this->add_control_username_pointer();

		// $this->add_control_username_pointer_height();

		// $this->add_control_username_pointer_animation();

		$this->add_control(
			'read_more_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-read-more .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-twitter-feed-item-read-more .crt-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-twitter-feed-item-read-more .crt-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'read_more_border_type',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-read-more .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'read_more_border_width',
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
					'{{WRAPPER}} .crt-twitter-feed-item-read-more .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'read_more_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'read_more_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-read-more .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'read_more_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-read-more .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Separator Style 1
		$this->start_controls_section(
			'section_style_separator1',
			[
				'label' => esc_html__( 'Separator Style', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'separator1_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-sep-style-1 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator1_width',
			[
				'label' => esc_html__( 'Width', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-sep-style-1:not(.crt-grid-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-twitter-feed-sep-style-1.crt-grid-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator1_height',
			[
				'label' => esc_html__( 'Height', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-sep-style-1 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator1_border_type',
			[
				'label' => esc_html__( 'Type', 'crt-manage' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'crt-manage' ),
					'double' => esc_html__( 'Double', 'crt-manage' ),
					'dotted' => esc_html__( 'Dotted', 'crt-manage' ),
					'dashed' => esc_html__( 'Dashed', 'crt-manage' ),
					'groove' => esc_html__( 'Groove', 'crt-manage' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-sep-style-1 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator1_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-sep-style-1 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator1_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-sep-style-1 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Slider Navigation -------
		$this->start_controls_section(
			'section_style_slider_navigation',
			[
				'label' => esc_html__( 'Slider Navigation', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'layout_select' => 'carousel',
					'enable_cs_nav' => 'yes'
				],
			]
		);

		$this->start_controls_tabs('cs_nav_tabs');

		$this->start_controls_tab(
			'cs_nav_tab_normal',
			[
				'label' => __( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'cs_nav_icon_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cs_nav_icon_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'cs_nav_icon_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_navigation',
				'label' => __( 'Box Shadow', 'crt-manage' ),
				'selector' => '{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev, {{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next',
			]
		);

		$this->add_control(
			'navigation_transition',
			[
				'label' => esc_html__( 'Transition', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;'
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'cs_nav_tab_hover',
			[
				'label' => __( 'Hover', 'crt-manage' ),
			]
		);
		
		$this->add_control(
			'cs_nav_icon_color_hover',
			[
				'label'  => esc_html__( 'Icon Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next:hover svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cs_nav_icon_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#423EC0',
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'cs_nav_icon_border_color_hover',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_navigation_hover',
				'label' => __( 'Box Shadow', 'crt-manage' ),
				'selector' => '{{WRAPPER}} .flipster__button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'cs_nav_icon_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],			
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);
		
		$this->add_responsive_control(
			'cs_nav_icon_bg_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],			
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'cs_nav_border',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev' => 'border-style: {{VALUE}};',
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'cs_nav_border_width',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'cs_nav_border!' => 'none'
				]
			]
		);
		
		$this->add_control(
			'icon_border_radius',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}}.crt-twitter-feed-carousel .crt-swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);
	
		$this->add_control_stack_twitter_feed_slider_nav_position();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_twitter_feed_slider_pag',
			[
                'label' => esc_html__('Slider Pagination', 'crt-manage'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout_select' => 'carousel',
					'enable_cs_pag' => 'yes'
				]
            ]
		);

		$this->add_control(
			'cs_pag_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-carousel .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-carousel .swiper-pagination-fraction' => 'color: {{VALUE}}',
					'{{WRAPPER}}.crt-twitter-feed-carousel .swiper-pagination-progressbar' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.crt-twitter-feed-carousel .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'cs_pag_bg_color',
			[
				'label'  => esc_html__( 'Bar Background', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#00000040',
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .swiper-pagination-progressbar' => 'background-color: {{VALUE}};'
				]
			]
		);
		
		$this->add_responsive_control(
			'cs_pag_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Box Size', 'crt-manage' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],			
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'cs_pag_fraction_typography',
				'label' => __( 'Typography', 'crt-manage' ),
				'selector' => '{{WRAPPER}}.crt-twitter-feed-carousel .swiper-pagination-fraction',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'cs_pag_margin',
			[
				'label' => esc_html__( 'Margin', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 6,
					'bottom' => 0,
					'left' => 6,
				],
				'selectors' => [
					'{{WRAPPER}}.crt-twitter-feed-carousel .swiper-pagination-bullet' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		
		$this->add_control_twitter_feed_slider_dots_hr();

        $this->end_controls_section();

		// Tab: Styles ===============
		// Section: Meta -----------
		$this->start_controls_section(
			'section_style_meta',
			[
				'label' => esc_html__( 'Meta', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'meta_icon_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#1D9BF0',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-comment span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-twitter-feed-item-likes span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-twitter-feed-item-retweets span' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'meta_icon_color_hover',
			[
				'label' => esc_html__( 'Color (Hover)', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-comment  a:hover span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-twitter-feed-item-likes  a:hover span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-twitter-feed-item-retweets  a:hover span' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_responsive_control(
			'meta_items_size',
			[
				'label' => esc_html__( 'Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-comment span' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-twitter-feed-item-likes span' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-twitter-feed-item-retweets span' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'meta_items_margin',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 7,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-feed-item-comment' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-twitter-feed-item-likes' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-twitter-feed-item-retweets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();

		// Tab: Styles ===============
		// Section: Button -----------
		$this->start_controls_section(
			'section_style_follow_button',
			[
				'label' => esc_html__( 'Follow Button', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_header' => 'yes'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_button_colors' );

		$this->start_controls_tab(
			'tab_button_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-follow-btn' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0F1419',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-follow-btn' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-follow-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .crt-twitter-follow-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-follow-btn:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#414a4c',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-follow-btn:hover' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-follow-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .crt-twitter-follow-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .crt-twitter-follow-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_distance_from_feed',
			[
				'label' => esc_html__( 'Top Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-follow-btn-wrap' => 'margin-top: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 8,
					'right' => 20,
					'bottom' => 8,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-follow-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_type',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-follow-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-follow-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-follow-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'follow_button_align',
			[
				'label' => esc_html__( 'Alignment', 'crt-manage' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
					]
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .crt-twitter-follow-btn-wrap' => 'text-align: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Pagination -------
		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'Pagination', 'crt-manage' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'layout_select!' => 'carousel',
					'show_pagination' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_pagination_style' );

		$this->start_controls_tab(
			'tab_grid_pagination_normal',
			[
				'label' => esc_html__( 'Normal', 'crt-manage' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination button' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-pagination-finish' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination button' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow',
				'selector' => '{{WRAPPER}} .crt-grid-pagination button, {{WRAPPER}} .crt-grid-pagination > div > span',
			]
		);

		$this->add_control(
			'pagination_loader_color',
			[
				'label'  => esc_html__( 'Loader Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .crt-double-bounce .crt-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-wave .crt-rect' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-spinner-pulse' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-chasing-dots .crt-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-three-bounce .crt-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-fading-circle .crt-circle:before' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_wrapper_color',
			[
				'label'  => esc_html__( 'Wrapper Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_pagination_hover',
			[
				'label' => esc_html__( 'Hover', 'crt-manage' ),
			]
		);

		$this->add_control(
			'pagination_color_hr',
			[
				'label'  => esc_html__( 'Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination button:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination button:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'crt-manage' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination button:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow_hr',
				'selector' => '{{WRAPPER}} .crt-grid-pagination button:hover, {{WRAPPER}} .crt-grid-pagination > div > span:not(.crt-disabled-arrow):hover',
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'pagination_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'crt-manage' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination button' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-pagination svg' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pagination_typography',
				'selector' => '{{WRAPPER}} .crt-grid-pagination, {{WRAPPER}} .crt-grid-pagination button'
			]
		);

		$this->add_responsive_control(
			'pagination_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_border_type',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination button' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_border_width',
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
					'{{WRAPPER}} .crt-grid-pagination button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'pagination_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_distance_from_feed',
			[
				'label' => esc_html__( 'Top Distance', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'pagination_gutter',
			[
				'label' => esc_html__( 'Gutter', 'crt-manage' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					// '{{WRAPPER}} .crt-grid-pagination button' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination button:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};', 
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination > div > a.crt-prev-page' => 'margin-right: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => esc_html__( 'Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 8,
					'right' => 20,
					'bottom' => 8,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-disabled-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_radius',
			[
				'label' => esc_html__( 'Border Radius', 'crt-manage' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .crt-grid-pagination button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination > div > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crt-grid-pagination span.crt-grid-current-page' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
    }

    public function round_down($number, $decimals) {
        $decimals = $decimals || 0;
        return (floor($number * pow(10, $decimals)) / pow(10, $decimals));
    }

    //Format numbers as per Twitter formatting.
    public function format_numbers($num) {
        if ($num >= 1000000000) {
            $num = $this->round_down(($num / 1000000000), 1) . 'G';
            return $num;
        }
        if ($num >= 1000000) {
            $num = $this->round_down(($num / 1000000), 1) . 'M';
            return $num;
        }
        if ($num >= 10000) {
            $num = $this->round_down(($num / 1000), 1) . 'K';
            return $num;
        }
        return $num;
    }

	public function render_twitter_feed_pagination($settings) {
		echo '<div class="crt-grid-pagination elementor-clearfix crt-grid-pagination-load-more">';
			echo '<button class="crt-load-more-twitter-posts crt-load-more-btn">';
				echo esc_html($settings['pagination_load_more_text']);
			echo '</button>';

			echo '<div class="crt-pagination-loading">';
				switch ( $settings['pagination_animation'] ) {
					case 'loader-1':
						echo '<div class="crt-double-bounce">';
							echo '<div class="crt-child crt-double-bounce1"></div>';
							echo '<div class="crt-child crt-double-bounce2"></div>';
						echo '</div>';
						break;
					case 'loader-2':
						echo '<div class="crt-wave">';
							echo '<div class="crt-rect crt-rect1"></div>';
							echo '<div class="crt-rect crt-rect2"></div>';
							echo '<div class="crt-rect crt-rect3"></div>';
							echo '<div class="crt-rect crt-rect4"></div>';
							echo '<div class="crt-rect crt-rect5"></div>';
						echo '</div>';
						break;
					case 'loader-3':
						echo '<div class="crt-spinner crt-spinner-pulse"></div>';
						break;
					case 'loader-4':
						echo '<div class="crt-chasing-dots">';
							echo '<div class="crt-child crt-dot1"></div>';
							echo '<div class="crt-child crt-dot2"></div>';
						echo '</div>';
						break;
					case 'loader-5':
						echo '<div class="crt-three-bounce">';
							echo '<div class="crt-child crt-bounce1"></div>';
							echo '<div class="crt-child crt-bounce2"></div>';
							echo '<div class="crt-child crt-bounce3"></div>';
						echo '</div>';
						break;
					case 'loader-6':
						echo '<div class="crt-fading-circle">';
							echo '<div class="crt-circle crt-circle1"></div>';
							echo '<div class="crt-circle crt-circle2"></div>';
							echo '<div class="crt-circle crt-circle3"></div>';
							echo '<div class="crt-circle crt-circle4"></div>';
							echo '<div class="crt-circle crt-circle5"></div>';
							echo '<div class="crt-circle crt-circle6"></div>';
							echo '<div class="crt-circle crt-circle7"></div>';
							echo '<div class="crt-circle crt-circle8"></div>';
							echo '<div class="crt-circle crt-circle9"></div>';
							echo '<div class="crt-circle crt-circle10"></div>';
							echo '<div class="crt-circle crt-circle11"></div>';
							echo '<div class="crt-circle crt-circle12"></div>';
						echo '</div>';
						break;
					
					default:
						break;
				}
			echo '</div>';

			echo '<p class="crt-pagination-finish">'. esc_html($settings['pagination_finish_text']) .'</p>';
		echo '</div>';
	}

    protected function render() {
        $settings = $this->get_settings_for_display();

		if ( empty($settings['twitter_feed_consumer_key']) || empty($settings['twitter_feed_consumer_secret']) ) {
			echo '<p class="crt-token-missing">'. esc_html__('Please insert Consumer and Secret Keys in respective fields', 'crt-manage') .'</p>';
			return;
		}


		$twitter_feed_account_names = [];

		foreach ( $settings['twitter_accounts'] as $key=>$value ) {
			array_push($twitter_feed_account_names, $value['twitter_feed_account_name']);
		}

        $token = get_transient('crt_' . $settings['number_of_posts'] . '_' . $this->get_ID() . '_' . implode("_", $twitter_feed_account_names) . '_tf_token');
	    $expiration = !empty( $settings['auto_clear_cache'] ) && !empty( $settings['twitter_feed_cache_limit'] ) ? absint( $settings['twitter_feed_cache_limit'] ) * MINUTE_IN_SECONDS : DAY_IN_SECONDS;
	    $cache_key = 'crt_' . implode("_", $twitter_feed_account_names) . '_' . $expiration . '_' . md5( $settings['twitter_feed_hashtag_name'] . $settings['twitter_feed_consumer_key'] . $settings['twitter_feed_consumer_secret'] ) . '_tf_cache' . '_' . $settings['number_of_posts'];
        $items_array = get_transient( $cache_key );

        if ($items_array === false) {
			if (empty($token)) {
				$credentials = base64_encode($settings['twitter_feed_consumer_key'] . ':' . $settings['twitter_feed_consumer_secret']);

				add_filter('https_ssl_verify', '__return_false');

				$response = wp_remote_post('https://api.twitter.com/oauth2/token', [
					'method' => 'POST',
					'httpversion' => '1.1',
					'blocking' => true,
					'headers' => [
						'Authorization' => 'Basic ' . $credentials,
						'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
					],
					'body' => ['grant_type' => 'client_credentials'],
				]);

				$body = json_decode(wp_remote_retrieve_body($response));

				if ($body) {
					set_transient('crt_' . $settings['number_of_posts'] . '_' . $this->get_ID() . '_' . implode("_", $twitter_feed_account_names) . '_tf_token', $body->access_token, $expiration);
					$token = $body->access_token;
				}
			}

			add_filter('https_ssl_verify', '__return_false');

			$response = [];
			$items_array = [];

			// To check if verified needs oauth and new token for already accessed users
			$verified = [];
			$check_verified_array = [];

			foreach ($settings['twitter_accounts'] as $key=>$value) {

				array_push($twitter_feed_account_names, $value['twitter_feed_account_name']);
				$response[$key] = wp_remote_get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $value['twitter_feed_account_name'] . '&count='. $settings['number_of_posts'] .'&tweet_mode=extended', [
					'httpversion' => '1.1',
					'blocking' => true,
					'headers' => [
						'Authorization' => "Bearer $token",
					],
				]);

				if ( is_wp_error( $response[$key] ) ) {
					// return $html;
				}
		
				if ( ! empty( $response[$key]['response'] ) && $response[$key]['response']['code'] == 200 ) {
					$items_array[] = json_decode( wp_remote_retrieve_body( $response[$key] ), true );
					set_transient( $cache_key, $items_array, $expiration );
				}
			}
		}

        $header_banner_placeholder = WPR_ADDONS_ASSETS_URL . 'img/placeholder.png';
        $header_banner = $items_array[0][0]['user']['profile_banner_url'] ? $items_array[0][0]['user']['profile_banner_url'] : $header_banner_placeholder;

		$columns_mobile = isset($settings['columns_mobile']) ? $settings['columns_mobile'] : $settings['columns'];
		$columns_tablet = isset($settings['columns_tablet']) ? $settings['columns_tablet'] : $settings['columns'];
		$columns_laptop = isset($settings['columns_laptop']) ? $settings['columns_laptop'] : $settings['columns'];
		$columns_widescreen = isset($settings['columns_widescreen']) ? $settings['columns_widescreen'] : $settings['columns'];

		$twitter_settings = [
			'layout_select' => $settings['layout_select'],
			'columns' => $settings['columns'],
			'columns_mobile' => $columns_mobile,
			'columns_mobile_extra' => isset($settings['columns_mobile_extra']) ? $settings['columns_mobile_extra'] : $columns_tablet,
			'columns_tablet' => $columns_tablet,
			'columns_tablet_extra' => isset($settings['columns_tablet_extra']) ? $settings['columns_tablet_extra'] : $columns_laptop,
			'columns_laptop' => $columns_laptop,
			'columns_widescreen' => $columns_widescreen,
			'gutter_hr' => isset($settings['gutter']) ? $settings['gutter']['size'] : '',
			'gutter_vr' => isset($settings['distance_bottom']) ? $settings['distance_bottom']['size'] : '',
			// 'animation' => $settings['layout_animation'],
			// 'animation_duration' => $settings['layout_animation_duration'],
			// 'animation_delay' => $settings['layout_animation_delay'],
		];

		$twitter_settings['twitter_load_more_settings'] = [
			'number_of_posts' => $settings['number_of_posts'],
			'twitter_feed_consumer_key' => $settings['twitter_feed_consumer_key'],
			'twitter_feed_consumer_secret' => $settings['twitter_feed_consumer_secret'],
			'twitter_feed_hashtag_name' => $settings['twitter_feed_hashtag_name'],
			// 'image_effects' => $settings['image_effects'],
			// 'image_effects' => $settings['image_effects_size'],
			// 'image_effects' => $settings['image_effects_duration'],
			'twitter_accounts' => $settings['twitter_accounts'],
			'twitter_feed_elements' => $settings['twitter_feed_elements'],
		];

		if ( 'carousel' === $settings['layout_select'] ) {
			
			$navigation = $settings['enable_cs_nav'];
			$pagination = $settings['enable_cs_pag'];
			$pagination_type = isset($settings['cs_pag_type']) ? $settings['cs_pag_type'] : '';
			$autoplay = $settings['enable_twitter_feed_slider_autoplay'];
			$loop = $settings['enable_twitter_feed_slider_loop'];
			$slides_to_show = $settings['twitter_feed_slides_to_show'];
			$slides_to_show_widescreen = isset($settings['twitter_feed_slides_to_show_widescreen']) ? $settings['twitter_feed_slides_to_show_widescreen'] : $slides_to_show;
			$slides_to_show_laptop = isset($settings['twitter_feed_slides_to_show_laptop']) ? $settings['twitter_feed_slides_to_show_laptop'] : $settings['twitter_feed_slides_to_show'];
			$slides_to_show_tablet_extra = isset($settings['twitter_feed_slides_to_show_tablet_extra']) ? $settings['twitter_feed_slides_to_show_tablet_extra'] : $slides_to_show_laptop;
			$slides_to_show_tablet = isset($settings['twitter_feed_slides_to_show_tablet']) ? $settings['twitter_feed_slides_to_show_tablet'] : $slides_to_show_tablet_extra;
			$slides_to_show_mobile_extra = isset($settings['twitter_feed_slides_to_show_mobile_extra']) ? $settings['twitter_feed_slides_to_show_mobile_extra'] : $slides_to_show_tablet;
			$slides_to_show_mobile = isset($settings['twitter_feed_slides_to_show_mobile']) ? $settings['twitter_feed_slides_to_show_mobile'] : $slides_to_show_mobile_extra;
			$space_between = $settings['twitter_feed_space_between'];
			$space_between_widescreen = isset($settings['twitter_feed_space_between_widescreen']) ? $settings['twitter_feed_space_between_widescreen'] : $space_between;
			$space_between_laptop = isset($settings['twitter_feed_space_between_laptop']) ? $settings['twitter_feed_space_between_laptop'] : $space_between;
			$space_between_tablet_extra = isset($settings['twitter_feed_space_between_tablet_extra']) ? $settings['twitter_feed_space_between_tablet_extra'] : $space_between_laptop;
			$space_between_tablet = isset($settings['twitter_feed_space_between_tablet']) ? $settings['twitter_feed_space_between_tablet'] : $space_between_tablet_extra;
			$space_between_mobile_extra = isset($settings['twitter_feed_space_between_mobile_extra']) ? $settings['twitter_feed_space_between_mobile_extra'] : $space_between_tablet;
			$space_between_mobile = isset($settings['twitter_feed_space_between_mobile']) ? $settings['twitter_feed_space_between_mobile'] : $space_between_mobile_extra;
			$delay = isset($settings['twitter_feed_delay']) ? $settings['twitter_feed_delay'] : '';
			$speed = $settings['twitter_feed_speed'];

			$twitter_settings['carousel'] = [
				'crt_cs_navigation' => $navigation,
				'crt_cs_pagination' => $pagination,
				'crt_cs_pagination_type' => $pagination_type,
				'crt_cs_autoplay' => $autoplay,
				'crt_cs_loop' => $loop,
				'crt_cs_slides_to_show' => $slides_to_show,
				'crt_cs_slides_to_show_widescreen' => $slides_to_show_widescreen,
				'crt_cs_slides_to_show_laptop' => $slides_to_show_laptop,
				'crt_cs_slides_to_show_tablet_extra' => $slides_to_show_tablet_extra,
				'crt_cs_slides_to_show_tablet' => $slides_to_show_tablet,
				'crt_cs_slides_to_show_mobile_extra' => $slides_to_show_mobile_extra,
				'crt_cs_slides_to_show_mobile' => $slides_to_show_mobile,
				'crt_cs_space_between' => $space_between,
				'crt_cs_space_between_widescreen' => $space_between_widescreen,
				'crt_cs_space_between_laptop' => $space_between_laptop,
				'crt_cs_space_between_tablet_extra' => $space_between_tablet_extra,
				'crt_cs_space_between_tablet' => $space_between_tablet,
				'crt_cs_space_between_mobile_extra' => $space_between_mobile_extra,
				'crt_cs_space_between_mobile' => $space_between_mobile,
				'crt_cs_delay' => $delay,
				'crt_cs_speed' => $speed,
				// 'enable_on'   => $settings['crt_enable_equal_height_on'],
			];
		}

		$this->add_render_attribute(
			'twitter_feed',
			[
				'class' => ['crt-twitter-feed', 'crt-'. $settings['layout_select']],
				'data-settings' => wp_json_encode( $twitter_settings ),
			]
		);

		if ( $settings['show_header'] ) {
        
			echo '<div class="crt-twitter-feed-header">';
				echo '<img src="'. $header_banner .'" >'; ?>
	
				
				<div class="crt-tf-header-content">
				<div class="crt-tf-header-profile-img">
					<img src="<?php echo str_replace('_normal', '', $items_array[0][0]['user']['profile_image_url']) ?>" alt="Image">
					<div class="crt-tf-statistics">
						<div class="crt-tf-header-user">
							<p class="crt-tf-header-user-name"><?php echo $items_array[0][0]['user']['name'] ?></p>
							<p class="crt-tf-header-user-acc-name"><a href="<?php echo $items_array[0][0]['user']['screen_name'] ?>" target="_blank"><?php echo '@'. $items_array[0][0]['user']['screen_name'] ?></a></p>
						</div>
						<span class=""><a href='https://twitter.com/<?php echo $items_array[0][0]['user']['screen_name'] ?>' target="_blank"><span><?php echo $this->format_numbers($items_array[0][0]['user']['statuses_count']) ?></span><span><?php esc_html__(' Tweets', 'crt-manage') ?></span></a></span>
						<span class=""><a href='https://twitter.com/<?php echo $items_array[0][0]['user']['screen_name'] ?>/following' target="_blank"><span><?php echo $this->format_numbers($items_array[0][0]['user']['friends_count']) ?></span><span><?php esc_html__(' Following', 'crt-manage') ?></span></a></span>
						<span class=""><a href='https://twitter.com/<?php echo $items_array[0][0]['user']['screen_name'] ?>/followers' target="_blank"><span><?php echo $this->format_numbers($items_array[0][0]['user']['followers_count']) ?></span><span><?php esc_html__(' Followers', 'crt-manage') ?></span></a></span>
					</div>
				</div>
	
					<span class="crt-twitter-follow-btn-wrap">
						<a class="crt-twitter-follow-btn" rel="nofollow" href="https://twitter.com/intent/follow?screen_name=<?php echo $items_array[0][0]['user']['screen_name'] ?>" target="_blank">
							Follow
						</a>
					</span>
				</div>
	
			<?php echo '</div>';

		}

		echo '<div class="crt-twitter-feed-cont">';
		if ( 'yes' === $settings['enable_cs_nav'] ) {
			echo '<div class="crt-swiper-nav-wrap">';
				echo '<button class="crt-swiper-button crt-swiper-button-prev">';
					echo Utilities::get_crt_icon( $settings['cs_nav_arrows'], 'left' );
				echo '</button>';
				echo '<button class="crt-swiper-button crt-swiper-button-next">';
					echo Utilities::get_crt_icon( $settings['cs_nav_arrows'], 'right' );
				echo '</button>';
			echo '</div>';
		}
		

		if ( 'yes' === $settings['enable_cs_pag'] ) {
			echo '<div class="swiper-pagination"></div>';
		}

        echo '<div '. wp_kses_post( $this->get_render_attribute_string( 'twitter_feed' ) ) .'>';

        foreach ( $items_array as $key=>$items ) :
        $i = 0;

		if ($settings['twitter_feed_hashtag_name']) {
			$hashtag_names = explode(',', str_replace(' ', '', $settings['twitter_feed_hashtag_name']));

			foreach ($items as $key => $item) {
				$match = false;

				if ($item['entities']['hashtags']) {
					foreach ($item['entities']['hashtags'] as $tag) {
						if (in_array($tag['text'], $hashtag_names)) {
							$match = true;
						}
					}
				}

				if ($match == false) {
					unset($items[$key]);
				}
			}
		}

        foreach ( $items as $item) :
                
        $banner_placeholder = WPR_ADDONS_ASSETS_URL . 'img/placeholder.png';
        $banner = $item['user']['profile_banner_url'] ? $item['user']['profile_banner_url'] : $banner_placeholder;
        ?>
                <div class="crt-tweet elementor-clearfix">
                        <article class="media">

                            <?php 
                                // Content: Above Media
                                echo $this->get_elements_by_location( 'above', $settings, $item );
                            ?>
                            
                        </article>
                </div>
        <?php

        endforeach;
        endforeach;
        echo '</div>';
        echo '</div>';

		if ( 'yes' === $settings['show_pagination'] ) :
			echo $this->render_twitter_feed_pagination($settings);
		endif;
    }
}