<?php
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use CrtAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Load_More_Instagram_Posts setup
 *
 * @since 3.4.6
 */

 class CRT_Load_More_Instagram_Posts {

    public function __construct() {
		add_action('wp_ajax_crt_load_more_instagram_posts', [$this, 'crt_load_more_instagram_posts_function']);
		add_action('wp_ajax_nopriv_crt_load_more_instagram_posts', [$this, 'crt_load_more_instagram_posts_function']);
    }

	public function call_instagram_api($access_token, $settings) {
		$url = 'https://graph.instagram.com/me/media?fields=id,media_type,media_url,thumbnail_url,permalink,username,caption,timestamp&access_token='. $access_token .'&limit='. ($settings['limit'] + $_POST['next_post_index']);
		$response = wp_remote_get($url);
		$body = json_decode($response['body']);
		if(!isset($body)) {
			return $response['body'];
		}
		return $body->data;	
	}

	// Get Animation Class
	public function get_animation_class( $data, $object ) {
		$class = '';

		// Animation Class
		if ( 'none' !== $data[ $object .'_animation'] ) {
			$class .= ' crt-'. $object .'-'. $data[ $object .'_animation'];
			$class .= ' crt-anim-size-'. $data[ $object .'_animation_size'];
			$class .= ' crt-anim-timing-'. $data[ $object .'_animation_timing'];

			if ( 'yes' === $data[ $object .'_animation_tr'] ) {
				$class .= ' crt-anim-transparency';
			}
		}

		return $class;
	}

	// Render Post Title
	public function render_post_username( $settings, $class, $result ) {

		$target = 'yes' == $_POST['crt_load_more_settings']['open_in_new_tab'] ? '_blank' : '_self';

		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
		$element_username_tag = Utilities::validate_html_tags_wl( $settings['element_username_tag'], 'h2', $tags_whitelist );

		echo '<'. esc_attr($element_username_tag) .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a href="'. $result->permalink .'" target="'. $target .'">';
					echo esc_html($result->username);
				echo '</a>';
			echo '</div>';
		echo '</'. esc_attr($element_username_tag) .'>';
	}

	public function render_post_caption($settings, $class, $result) {

		if ( !isset($result->caption) || '' === $result->caption ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<figcaption class="crt-insta-feed-caption"><span>'. esc_html(wp_trim_words($result->caption, $settings['element_word_count'])) .'</span></figcaption>';
			echo '</div>';
		echo '</div>';
	}

	public function render_post_date($settings, $class, $result) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-insta-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="crt-insta-feed-extra-icon-left '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}

				// Date
				if ( 'yes' === $settings['element_hide_year'] ) {
					echo date('F j', strtotime($result->timestamp));
				} else {
					echo date(get_option( 'date_format' ), strtotime($result->timestamp));
				}

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="crt-insta-feed-extra-icon-right '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-insta-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}

	public function render_post_icon($settings, $class, $result) {

		$target = 'yes' == $_POST['crt_load_more_settings']['open_in_new_tab'] ? '_blank' : '_self';

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
			   echo '<a href='. $result->permalink .' target='. $target .'>';
				echo '<i class="fab fa-instagram"></i>';
			   echo '</a>';
			echo '</div>';
		echo '</div>';
	}
	
	public function render_post_lightbox( $settings, $class, $result ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				$lightbox_source = $result->media_url;

				if ( 'VIDEO' === $result->media_type ) {
					$lightbox_source = $result->thumbnail_url;
				}

				// Lightbox Button
				echo '<span data-src="'. esc_url( $lightbox_source ) .'">';
				
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="crt-insta-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

					// Lightbox Icon
					echo '<i class="'. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';

					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="crt-insta-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

				echo '</span>';

				// Media Overlay
				if ( 'yes' === $settings['element_lightbox_overlay'] ) {
					echo '<div class="crt-insta-feed-lightbox-overlay"></div>';
				}
			echo '</div>';
		echo '</div>';
	}

	public function render_post_sharing_icons( $settings, $class, $result ) {
		$args = [
			'icons' => 'yes',
			'tooltip' => $settings['element_sharing_tooltip'],
			'url' => esc_url( $result->permalink ),
			'title' => esc_html( '' ),
			'text' => esc_html( isset($result->caption) ? $result->caption : '' ),
			'image' => esc_url( $result->media_url ),
		];

		$hidden_class = '';

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-insta-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				echo '<span class="crt-post-sharing">';

					if ( 'yes' === $settings['element_sharing_trigger'] ) {
						$hidden_class = ' crt-sharing-hidden';
						$attributes  = ' data-action="'. esc_attr( $settings['element_sharing_trigger_action'] ) .'"';
						$attributes .= ' data-direction="'. esc_attr( $settings['element_sharing_trigger_direction'] ) .'"';

						echo '<a class="crt-sharing-trigger crt-sharing-icon"'. $attributes .'>';
							if ( 'yes' === $settings['element_sharing_tooltip'] ) {
								echo '<span class="crt-sharing-tooltip crt-tooltip">'. esc_html__( 'Share', 'crt-addons' ) .'</span>';
							}

							echo Utilities::get_crt_icon( $settings['element_sharing_trigger_icon'], '' );
						echo '</a>';
					}


					echo '<span class="crt-post-sharing-inner'. $hidden_class .'">';

					for ( $i = 1; $i < 7; $i++ ) {
						$args['network'] = $settings['element_sharing_icon_'. $i];

						echo Utilities::get_post_sharing_icon( $args );
					}

					echo '</span>';

				echo '</span>';

				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-insta-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Element Separator
	public function render_post_element_separator( $settings, $class ) {
		echo '<div class="crt-insta-feed-sep-style-1 '. esc_attr($class) .'">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}

	// Get Elements
	public function get_elements( $type, $settings, $class, $result ) {
		if ( 'pro-lk' == $type || 'pro-shr' == $type || 'pro-cf' == $type ) {
			$type = 'title';
		}

		switch ( $type ) {
	

			case 'username':
				$this->render_post_username( $settings, $class, $result );
				break;

			case 'caption':
				$this->render_post_caption( $settings, $class, $result );
				break;

			case 'date':
				$this->render_post_date( $settings, $class, $result );
				break;

			case 'icon':
				$this->render_post_icon( $settings, $class, $result );
				break;

			// case 'comments':
			// 	$this->render_post_comments( $settings, $class );
			// 	break;

			// case 'read-more':
			// 	$this->render_post_read_more( $settings, $class );
			// 	break;

			// case 'likes':
			// 	$this->render_post_likes( $settings, $class, $post_id );
			// 	break;

			case 'sharing':
				$this->render_post_sharing_icons( $settings, $class, $result );
				break;

			case 'lightbox':
				$this->render_post_lightbox( $settings, $class, $result );
				break;

			case 'separator':
				$this->render_post_element_separator( $settings, $class );
				break;
		}

	}

	// Get Image Effect Class
	public function get_image_effect_class( $settings ) {
		$class = '';

		// Animation Class
		if ( 'none' !== $settings['image_effects'] ) {
			$class .= ' crt-'. $settings['image_effects'];
		}
		
		// Slide Effect
		if ( 'slide' !== $settings['image_effects'] ) {
			$class .= ' crt-effect-size-'. $settings['image_effects_size'];
		} else {
			$class .= ' crt-effect-dir-'. $settings['image_effects_direction'];
		}

		return $class;
	}

	// Get Elements by Location
	public function get_elements_by_location( $location, $settings, $result ) {
		$locations = [];

		foreach ( $settings['insta_feed_elements'] as $data ) {
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

					echo '<div class="crt-insta-feed-media-hover-'. esc_attr($align) .' elementor-clearfix">';
						foreach ( $thiss as $data ) {
							
							// Get Class
							$class  = 'crt-insta-feed-item-'. $data['element_select'];
							$class .= ' elementor-repeater-item-'. $data['_id'];
							$class .= ' crt-insta-feed-item-display-'. $data['element_display'];
							$class .= ' crt-insta-feed-item-align-'. $data['element_align_hr'];
							$class .= $this->get_animation_class( $data, 'element' );

							// Element
							$this->get_elements( $data['element_select'], $data, $class, $result );
						}
					echo '</div>';

					if ( 'middle' === $align ) {
						echo '</div></div></div>';
					}
				}
			} else {
				$count_elements = 0;
				$caption_not_empty = true;
				foreach ( $locations[$location] as $data ) {
					$count_elements++;
					if ( 'caption' === $data['element_select'] ) {
						$caption_not_empty = isset($result->caption);
					}
				}

				if ( $count_elements == 1 && !$caption_not_empty ) {
					return;
				}
				
				echo '<div class="crt-insta-feed-item-'. esc_attr($location) .'-content elementor-clearfix">';
					foreach ( $locations[$location] as $data ) {

						// Get Class
						$class  = 'crt-insta-feed-item-'. $data['element_select'];
						$class .= ' elementor-repeater-item-'. $data['_id'];
						$class .= ' crt-insta-feed-item-display-'. $data['element_display'];
						$class .= ' crt-insta-feed-item-align-'. $data['element_align_hr'];

						// Element
						$this->get_elements( $data['element_select'], $data, $class, $result );
					}
				echo '</div>';
			}

		}
	}

	// Render Media Overlay
	public function render_media_overlay( $settings, $result ) {

		$target = 'yes' == $_POST['crt_load_more_settings']['open_in_new_tab'] ? '_blank' : '_self';

		echo '<div class="crt-insta-feed-media-hover-bg '. esc_attr($this->get_animation_class( $settings, 'overlay' )) .'" data-url="'. $result->permalink .'" data-target="'. $target .'">';

		echo '</div>';
	}

    public function crt_load_more_instagram_posts_function() {
		$settings = $_POST['crt_load_more_settings'];
		
		if ( get_transient('crt_instagram_access_token'. $_POST['crt_insta_feed_widget_id']) ) {
			$instagram_token = get_transient('crt_instagram_access_token'. $_POST['crt_insta_feed_widget_id']);
		} else {
			$instagram_token = $settings['instagram_access_token'];
		}
		
        foreach($this->call_instagram_api($instagram_token, $_POST['crt_load_more_settings']) as $key=>$result) : ?>
            <?php
				if ($key < $_POST['next_post_index']) :
					continue; 
				endif;
			?>

            <div class="crt-insta-feed-content-wrap crt-insta-col-12">
                <figure>
                    <?php
                        // Content: Below Media
                        echo $this->get_elements_by_location( 'above', $_POST['crt_load_more_settings'], $result );
                    ?>
                    <div class="crt-insta-feed-media-wrap <?php echo esc_attr($this->get_image_effect_class( $_POST['crt_load_more_settings'] )) ?>" data-overlay-link="<?php echo esc_attr( $_POST['crt_load_more_settings']['overlay_post_link'] ) ?>">
                    <?php if ( 'CAROUSEL_ALBUM' == $result->media_type || 'IMAGE' == $result->media_type ) : ?>
                        <div class="crt-insta-feed-image-wrap" data-src=<?php echo $result->media_url ?>>
                            <img src=<?php echo $result->media_url  ?> alt="">
                        </div>
                    <?php else : ?>
                        <div class="crt-insta-feed-image-wrap" data-src=<?php echo $result->thumbnail_url ?>>
                            <img class="crt-insta-feed-thumb" src=<?php echo $result->thumbnail_url ?> alt="">
                        </div>
                    <?php endif ; ?>
                        <div class="crt-insta-feed-media-hover crt-animation-wrap">
                            <?php
                                // Media Overlay
                                $this->render_media_overlay( $_POST['crt_load_more_settings'], $result );

                                // Content: Over Media
                                $this->get_elements_by_location( 'over', $_POST['crt_load_more_settings'], $result );
                            ?>
                        </div>
                    </div>
                    <?php
                        // Content: Below Media
                        echo $this->get_elements_by_location( 'below', $_POST['crt_load_more_settings'], $result );
                    ?>
                </figure>
            </div>
        <?php endforeach;
        
        die();
    }
 }

 new CRT_Load_More_Instagram_Posts();