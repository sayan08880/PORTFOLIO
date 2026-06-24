<?php
namespace CrtAddons\Classes\Modules;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use CrtAddons\Classes\Utilities;
use CrtAddons\Classes\Modules\CRT_Post_Likes;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CRT_Grid_Helpers setup
 *
 * @since 3.4.6
 */

 class CRT_Grid_Helpers {

    public function __construct() {
		add_action('wp_ajax_crt_grid_filters_ajax', [$this, 'crt_grid_filters_ajax']);
		add_action('wp_ajax_nopriv_crt_grid_filters_ajax', [$this, 'crt_grid_filters_ajax']);
		add_action('wp_ajax_crt_get_filtered_count_posts', [$this, 'crt_get_filtered_count_posts']);
		add_action('wp_ajax_nopriv_crt_get_filtered_count_posts', [$this, 'crt_get_filtered_count_posts']);
		add_action('wp_ajax_crt_get_dependent_terms', [$this, 'get_dependent_terms']);
		add_action('wp_ajax_nopriv_crt_get_dependent_terms', [$this, 'get_dependent_terms']);
    }

	public function get_dependent_terms() {
		// check_ajax_referer('crt_addons_elementor', 'nonce');

		if ( empty($_POST['taxonomy']) || empty($_POST['parent_term']) ) {
			wp_send_json_error('Missing data');
		}

		$taxonomy    = sanitize_text_field($_POST['taxonomy']);
		$parent_raw  = sanitize_text_field($_POST['parent_term']);

		// Determine if parent_term is ID or slug
		if ( is_numeric($parent_raw) ) {
			$related_term = get_term(intval($parent_raw));
		} else {
			// Optional: detect the related taxonomy (requires an extra POST param or default)
			$related_taxonomy = sanitize_text_field($_POST['related_taxonomy'] ?? '');
			if ( empty($related_taxonomy) ) {
				wp_send_json_error('Missing related taxonomy for slug');
			}
			$related_term = get_term_by('slug', $parent_raw, $related_taxonomy);
		}

		if ( ! $related_term || is_wp_error($related_term) ) {
			wp_send_json_error('Invalid parent term');
		}

		$related_taxonomy = $related_term->taxonomy;
		$tax_array = [];

		if ( isset($_POST['tax_array']) ) {
			$related_taxonomies = $_POST['tax_array'];
			$related_terms = $_POST['parent_terms'];

			// Add relation AND
			$tax_array['relation'] = 'AND';

			foreach ( $related_taxonomies as $index => $tax ) {
				if ( isset($related_terms[$index]) && $related_terms[$index] !== '' ) {
					$tax_array[] = [
						'taxonomy' => sanitize_text_field($tax),
						'field'    => 'term_id',
						'terms'    => intval($related_terms[$index]),
					];
				}
			}
		} else {
			$tax_array[] = [
					'taxonomy' => $related_taxonomy,
					'field'    => 'term_id',
					'terms'    => $related_term->term_id,
			];
		}

		// Get all posts with the related term
		$posts = get_posts([
			'post_type'      => 'any',
			'posts_per_page' => -1,
			'tax_query'      => $tax_array,
			'fields' => 'ids',
		]);

		if ( empty($posts) ) {
			wp_send_json_success([]);
		}

		// Get all terms from the target taxonomy used in these posts
		$terms = wp_get_object_terms($posts, $taxonomy, [
			'hide_empty' => true,
		]);

		$options = [];
		foreach ( $terms as $term ) {
			$options[] = [
				'id' => $term->term_id,
				'name' => $term->name,
				// 'posts' => $posts,
				// 'related_tax' => $related_taxonomy,
				// 'related_term' => $related_term->slug,
				// 'taxonomy' => $taxonomy,
			];
		}

		wp_send_json_success($options);
	}
    
	// Get Taxonomies Related to Post Type
	public static function get_related_taxonomies() {
		$relations = [];
		$post_types = Utilities::get_custom_types_of( 'post', false );

		foreach ( $post_types as $slug => $title ) {
			$relations[$slug] = [];

			foreach ( get_object_taxonomies( $slug ) as $tax ) {
				array_push( $relations[$slug], $tax );
			}
		}

		return json_encode( $relations );
	}

	// Get Max Pages
	public static function get_max_num_pages( $settings ) {
		if ( isset($_POST['crt_url_params']) ) {	
			$query = new \WP_Query( CRT_Grid_Helpers::get_main_query_args($settings, []) );
			$max_num_pages = intval( ceil( $query->max_num_pages ) );

			// Reset
			wp_reset_postdata();

			// $max_num_pages
			return $max_num_pages;
		} else if ( isset($_POST['grid_settings']) ) {
			$query = new \WP_Query(CRT_Grid_Helpers::get_main_query_args($settings, []) );
			$max_num_pages = intval( ceil( $query->max_num_pages ) );
			
			$adjustedTotalPosts = max(0, $query->found_posts - $query->query_vars['offset']); // Ensuring it doesn't go below 0
			$numberOfPages = ceil($adjustedTotalPosts / $query->query_vars['posts_per_page']);

			wp_send_json_success([
				'page_count' => $numberOfPages,
				'max_num_pages' => $max_num_pages,
				'query_found' => $query->found_posts,
				'post_count' => $query->post_count,
				'query_offset' => $query->query_vars['offset'],
				'query_num' => $query->query_vars['posts_per_page']
			]);

			// Reset
			wp_reset_postdata();

			// $max_num_pages
			return $max_num_pages;
		} else {
			$query = new \WP_Query( CRT_Grid_Helpers::get_main_query_args($settings, []) );
			$max_num_pages = intval( ceil( $query->max_num_pages ) );

			// Reset
			wp_reset_postdata();

			// $max_num_pages
			return $max_num_pages;
		}
	}

	// Main Query Args
	public static function get_main_query_args($settings, $params) {
		$author = ! empty( $settings[ 'query_author' ] ) ? implode( ',', $settings[ 'query_author' ] ) : '';

		// if ( is_user_logged_in() ){
		// 	$logged_in_user = wp_get_current_user();
		// 	$author = '1' . ',' . $logged_in_user->ID;
		// }

		// Get Paged
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		// Change Posts Per Page for Slider Layout
		if ( 'slider' === $settings['layout_select'] && Utilities::is_new_free_user() ) {
			$settings['query_posts_per_page'] = $settings['query_slides_to_show'] ? $settings['query_slides_to_show'] : -1;
			$settings['query_posts_per_page'] = $settings['query_posts_per_page'] > 4 ? 4 : $settings['query_posts_per_page'];
		}

		if ( 'slider' === $settings['layout_select'] ) {
			$paged = 1;
		}
		
		if ( empty($settings['query_offset']) ) {
			$settings[ 'query_offset' ] = 0;
		}

		$offset = ( $paged - 1 ) * intval($settings['query_posts_per_page']) + intval($settings[ 'query_offset' ]);

		if ( empty($settings['query_posts_per_page']) ) {
			if ( !('slider' === $settings['layout_select'] && Utilities::is_new_free_user()) ) {
				$settings['query_posts_per_page'] = 999;
			}
		}

		if ( !defined('CRT_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code() ) {
//			$settings[ 'query_randomize' ] = '';
//			$settings['order_posts'] = 'date';
		}

		$query_order_by = '' != $settings['query_randomize'] ? $settings['query_randomize'] : $settings['order_posts'];
		$post__not_in = isset($settings[ 'query_exclude_'. $settings[ 'query_source' ] ]) && !empty($settings[ 'query_exclude_'. $settings[ 'query_source' ] ]) ? $settings[ 'query_exclude_'. $settings[ 'query_source' ] ] : [];

		// Dynamic
		$args = [
			'post_type' => $settings[ 'query_source' ],
			'tax_query' => CRT_Grid_Helpers::get_tax_query_args($settings),
			'post__not_in' => $post__not_in,
			'posts_per_page' => $settings['query_posts_per_page'],
			'orderby' => $query_order_by,
			'author' => $author,
			'paged' => $paged,
			'offset' => $offset
		];

		// if ( isset($_POST['crt_item_length']) ) {
		// 	$args['posts_per_page'] == $_POST['crt_item_length'];
		// } check before uncomenting (may conflict)

		if ( $query_order_by == 'meta_value' ) {
			$args['meta_key'] = $settings['order_posts_by_acf'];
		}

		// Display Scheduled Posts
		if ( 'yes' === $settings['display_scheduled_posts'] && (defined('CRT_ADDONS_PRO_VERSION') && crt_fs()->can_use_premium_code()) ) {
			$args['post_status'] = 'future';
		} else {
			$args['post_status'] = 'publish';
		}

		// Exclude Items without F/Image
		if ( 'yes' === $settings['query_exclude_no_images'] ) {
			$args['meta_key'] = '_thumbnail_id';
		}

		// Manual
		if ( 'manual' === $settings[ 'query_selection' ] ) {
			$post_ids = [''];

			if ( ! empty($settings[ 'query_manual_'. $settings[ 'query_source' ] ]) ) {
				$post_ids = $settings[ 'query_manual_'. $settings[ 'query_source' ] ];
			}

			$args = [
				'post_type' => $settings[ 'query_source' ],
				'post__in' => $post_ids,
				'ignore_sticky_posts' => 1,
				'posts_per_page' => $settings['query_posts_per_page'],
				'orderby' => $query_order_by,
				'paged' => $paged,
			];
		}

		// Current
		if ( 'current' === $settings[ 'query_source' ] ) {
			global $wp_query;

			$tax_query = [];

			$args = $wp_query->query_vars;

			if ( is_post_type_archive() ) {
				$posts_per_page = intval(get_option('crt_cpt_ppp_'. $args['post_type']), 10);
			} else {
				$posts_per_page = intval(get_option('posts_per_page'));
			}

			if ( isset($settings['current_query_source']) ) {
				$args['post_type'] = $settings['current_query_source'];
				if ( $args['post_type'] != 'post' ) {
					$posts_per_page = intval(get_option('crt_cpt_ppp_'. $args['post_type']), 10);
					$args['posts_per_page'] = $posts_per_page;
				}
			}

			$args['orderby'] = $query_order_by;

			$args['offset'] = ( $paged - 1 ) * $posts_per_page + intval($settings[ 'query_offset' ]);
			
			if ( isset($_GET['category']) ) {
				
				if ( $_GET['category'] != '0' ) {
					// Get category from URL
					$category = sanitize_text_field($_GET['category']);
				
					array_push( $tax_query, [
						'taxonomy' => 'category',
						'field' => 'id',
						'terms' => $category
					] );
				}
			}
						
			if ( isset($_GET['crt_select_category']) ) {
				
				if ( $_GET['crt_select_category'] != '0' ) {
					// Get category from URL
					$category = sanitize_text_field($_GET['crt_select_category']);
					$taxonomy_name = 'category';
	
	                $term = get_term($category);
	
	                // Check if the term is valid
	                if (!is_wp_error($term)) {
	                    // Get the taxonomy name
	                    $taxonomy_name = $term->taxonomy;
	                }
				
					array_push( $tax_query, [
						'taxonomy' => $taxonomy_name,
						'field' => 'id',
						'terms' => $category
					] );
				}
			}
            // Get category from URL (CHECK BELOW FOR FILTERS)

			if ( !empty($tax_query) ) {
				$args['tax_query'] = $tax_query;
			}
		}

		// Related
		if ( 'related' === $settings[ 'query_source' ] ) {
			$args = [
				'post_type' => get_post_type( get_the_ID() ),
				'tax_query' => CRT_Grid_Helpers::get_tax_query_args($settings),
				'post__not_in' => [ get_the_ID() ],
				'ignore_sticky_posts' => 1,
				'posts_per_page' => $settings['query_posts_per_page'],
				'orderby' => $query_order_by,
				'offset' => $offset,
			];
		}

		if ( 'rand' !== $query_order_by ) {
			$args['order'] = $settings['order_direction'];
		}

		if ( isset($_POST['crt_offset']) ) { // Check if causes issues with grid itself
			$args['offset'] = $_POST['crt_offset'];
		}

		if ( !isset($args['tax_query']) ) {
			$args['tax_query'] = [];
		}

		if ( isset($_POST['crt_taxonomy'] ) ) {
			$settings = $_POST['grid_settings'];
			$taxonomy = $_POST['crt_taxonomy'];
			$term = $_POST['crt_filter'];
			$tax_query = [];

			if ( $term != '*' ) {
				if ( 'tag' === $taxonomy ) {
					$taxonomy = 'post_' . $_POST['crt_taxonomy'];
				}
				array_push( $tax_query, [
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $term
				] );
			}

			if ( !empty($tax_query) ) {
				$args['tax_query'] = $tax_query;
			}

			if ( isset($_POST['crt_offset']) ) {
				$args['offset'] = $_POST['crt_offset'];
			}

			return $args;
		}

		if ( isset($args['tax_query']) ) {

			$tax_query = ['relation' => 'AND'];
            $meta_query = ['relation' => 'AND'];

			$prev_cleaned_key = '';

			$crt_url_params = isset($params) && !empty($params) ? $params : (isset($_POST['crt_url_params']) ? $_POST['crt_url_params'] : []);

			if ( empty($crt_url_params) && isset($_GET) && !empty($_GET) ) {
				$crt_url_params = $_GET;
			}

			if ( isset($crt_url_params) && !empty($crt_url_params) ) {
				// Iterate through the POST array
				foreach ( $crt_url_params as $key => $value ) {

					// Check if the variable name contains "crt_af_"
					if (strpos($key, 'crt_af_') !== false) {

						// Need to setup logic to get relation from filters separately
						$cleanedKey = str_replace('crt_af_', '', $key);
						$prev_cleaned_key = $cleanedKey;

						if ( isset($crt_url_params[$key]) ) {
							if ( $cleanedKey == 'date_range' ) {
								$date = $crt_url_params[$key];
								
								$args['date_query'] = [];

								if ( str_contains($date, ',') ) {
									$date = explode(',', $date);

									if (false) {
										$args['date_query'] = ['relation' => 'or'];

										list($year1, $month1, $day1) = explode("-", $date[0]);
										list($year2, $month2, $day2) = explode("-", $date[1]);

										array_push( $args['date_query'], [
											'year' => $year1,
											'month' => $month1,
											'day' => $day1,
										] );

										array_push( $args['date_query'], [
											'year' => $year2,
											'month' => $month2,
											'day' => $day2,
										] );

									} else {
										array_push( $args['date_query'], [
											'after'     => $date[0],
											'before'    => $date[1],
											'inclusive' => true
										] );
									}
								} 
							} elseif ( $cleanedKey == 'date' ) {

								$date = $crt_url_params[$key];
								
								$args['date_query'] = [];

								if ( str_contains($date, '-') && explode("-", $date) ) {
									list($year, $month, $day) = explode("-", $date);

									array_push( $args['date_query'], [
										'year' => $year,
										'month' => $month,
										'day' => $day,
									]);
								}
							} else {
								if ( $crt_url_params[$key] != '0' ) {
									// Get category from URL
									if ( str_contains($crt_url_params[$key], ',') ) {

										// Example usage
										$key_type = CRT_Grid_Helpers::identify_key_type($cleanedKey);
										$filtervalues = explode(',', $crt_url_params[$key]);
		
										if ( ('meta_field' == $key_type || 'custom_field' == $key_type) ) {
											if ( is_numeric($filtervalues[0]) && isset($crt_url_params['crt_aft_' . $cleanedKey]) && $crt_url_params['crt_aft_' . $cleanedKey] == 'range' ) {
												$minValue = min(array_values($filtervalues));
												$maxValue = max(array_values($filtervalues));
												
												if ( isset($meta_query) ) {
													array_push($meta_query, [
														[
															'key'     => $cleanedKey,
															'value'   => [$minValue, $maxValue],
															'type'    => 'NUMERIC',
															'compare' => 'BETWEEN',
														],
													]);
												} else {
													$meta_query = [
														[
															'key'     => $cleanedKey,
															'value'   => [$minValue, $maxValue],
															'type'    => 'NUMERIC',
															'compare' => 'BETWEEN',
														],
													];
												}
											} else {
												if ( isset($meta_query) ) {
													if ( isset($_POST['crt_afr_' . $cleanedKey]) && !empty(explode(',', $_POST['crt_afr_'. $cleanedKey])[0]) ) {
														$meta_relation = explode(',', $_POST['crt_afr_'. $cleanedKey])[0];
													} else if ( isset($crt_url_params['crt_afr_'. $cleanedKey]) && !empty(explode(',', $crt_url_params['crt_afr_'. $cleanedKey])[0]) ) {
														$meta_relation = explode(',', $crt_url_params['crt_afr_'. $cleanedKey])[0];
													} else {
														$meta_relation = '';
													}
													
													$for_meta_query = [ // needs check if overrides somethings
														'relation' => $meta_relation,
													];
				
													foreach ($filtervalues as $filtervalue) {
														$filtervalue = sanitize_text_field($filtervalue);
													
														array_push($for_meta_query, [
															[
																'key'     => $cleanedKey,
																'value'   => $filtervalue
															],
														]);
													}
				
													array_push( $meta_query, $for_meta_query );
												} else {
													$meta_query = [ // needs check if overrides something
														'relation' => explode(',', $_POST['crt_afr_'. $cleanedKey])[0] ? explode(',', $_POST['crt_afr_'. $cleanedKey])[0] : explode(',', $crt_url_params['crt_afr_'. $cleanedKey])[0],
													];
		
													if (is_array($filtervalues)) {
														foreach ($filtervalues as $filtervalue) {
															$meta_query[] = [
																'key'     => $cleanedKey,
																'value'   => $filtervalue,
																'compare' => '=',
															];
														}
													}
												}
											}
										} else { // if != 'meta_field'
											// if ( isset($_POST['crt_afr_'. $cleanedKey]) ) {
												$for_tax_query = [ // needs check if overrides something
													// 'relation' => isset($_POST['crt_afr_' . $cleanedKey]) && !empty(explode(',', $_POST['crt_afr_' . $cleanedKey])[0]) ? explode(',', $_POST['crt_afr_' . $cleanedKey])[0] : '',
													'relation' => isset($crt_url_params['crt_afr_' . $cleanedKey]) && !empty(explode(',', $crt_url_params['crt_afr_' . $cleanedKey])[0]) ? explode(',', $crt_url_params['crt_afr_' . $cleanedKey])[0] : '',
												];
											// } else {
											// 	$for_tax_query = [];
											// }
		
											foreach ($filtervalues as $filtervalue) {
												$filtervalue = sanitize_text_field($filtervalue);
												
												array_push( $for_tax_query, [
													'taxonomy' => $cleanedKey,
													'field' => 'id',
													'terms' => $filtervalue
												] );
											}

											array_push($tax_query, $for_tax_query);
										}
									} else { // not str_contains($crt_url_params[$key], ',')
										$key_type = CRT_Grid_Helpers::identify_key_type($cleanedKey);
										$filtervalues = sanitize_text_field($crt_url_params[$key]);
		
										if ( $key_type == 'meta_field' || $key_type == 'custom_field' ) {
											if ( isset($meta_query) ) {
												array_push($meta_query, [
													[
														'key'     => $cleanedKey,
														'value'   => [$filtervalues],
														// 'type'    => 'NUMERIC',
														// 'compare' => 'BETWEEN',
													],
												]);
											} else {
												$meta_query = [
													[
														'key'     => $cleanedKey,
														'value'   => [$filtervalues],
														// 'type'    => 'NUMERIC',
														// 'compare' => 'BETWEEN',
													],
												];
											}
										} else {
											if (isset($crt_url_params[$key])) {
						
												array_push( $tax_query, [
													'taxonomy' => $cleanedKey,
													'field' => 'id',
													'terms' => $filtervalues
												] );
											}
										}
									}
								}
							}
						}
					}
				}	

				if ( !empty($tax_query) ) {
					if ( !empty($args['tax_query']) ) {
						$args['tax_query'] = array_merge( $args['tax_query'], $tax_query );
					} else {
						$args['tax_query'] = $tax_query;
					}
				}

				if ( !empty($meta_query) )  {
					if ( !empty($args['meta_query']) ) {
						$args['tax_query'] = array_merge( $args['tax_query'], $tax_query );
					} else {
						$args['meta_query'] = $meta_query;
					}
				}
			}
		}

		return $args;
	}
	
	public static function identify_key_type($key) {
		// Check if it's a built-in taxonomy
		$builtin_taxonomies = array('category', 'post_tag'); // Add more if needed
		if (in_array($key, $builtin_taxonomies)) {
			return 'taxonomy';
		}
	
		// Check if it's a custom taxonomy
		$custom_taxonomies = get_taxonomies(['_builtin' => false]);
		if (in_array($key, $custom_taxonomies)) {
			return 'taxonomy';
		}
	
		// Check if it's a custom field key - WHY?
		$custom_field_keys = get_post_custom_keys();
		if ( is_array($custom_field_keys) && in_array($key, $custom_field_keys) ) {
			return 'custom_field';
		}
	
		// Add more checks if needed...
	
		// If none of the checks match, assume it's a meta field
		return 'meta_field';
	}

	// Taxonomy Query Args
	public static function get_tax_query_args($settings) {
		$settings = $settings;
		$tax_query = [];

		if ( isset($_POST['crt_taxonomy']) ) {	
			$taxonomy = $_POST['crt_taxonomy'];
			$term = $_POST['crt_filter'];
		
			if ( $term != '*' ) {
				if ( 'tag' === $taxonomy ) {
					$taxonomy = 'post_' . $_POST['crt_taxonomy'];
				}
				array_push( $tax_query, [
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $term
				] );
			}
		}

		if ( 'related' === $settings[ 'query_source' ] ) {
			$tax_query = [
				[
					'taxonomy' => $settings['query_tax_selection'],
					'field' => 'term_id',
					'terms' => wp_get_object_terms( get_the_ID(), $settings['query_tax_selection'], array( 'fields' => 'ids' ) ),
				]
			];
		} else {
			foreach ( get_object_taxonomies($settings[ 'query_source' ]) as $tax ) {
				if ( ! empty($settings[ 'query_taxonomy_'. $tax ]) ) {
					array_push( $tax_query, [
						'taxonomy' => $tax,
						'field' => 'id',
						'terms' => $settings[ 'query_taxonomy_'. $tax ]
					] );
				}
			}
		}

		return $tax_query;
	}

	// Get Animation Class
	public static function get_animation_class( $data, $object ) {
		$class = '';

		// Disable Animation on Mobile
		if ( 'overlay' !== $object ) {
			if ( 'yes' === $data[$object .'_animation_disable_mobile'] && wp_is_mobile() ) {
				return $class;
			}
		}

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

	// Get Image Effect Class
	public static function get_image_effect_class( $settings ) {
		$class = '';

//		if ( !defined('CRT_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code() ) {
//			if ( 'pro-zi' ==  $settings['image_effects'] || 'pro-zo' ==  $settings['image_effects'] || 'pro-go' ==  $settings['image_effects'] || 'pro-bo' ==  $settings['image_effects'] ) {
//				$settings['image_effects'] = 'none';
//			}
//		}

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

	// Render Password Protected Input
	public static function render_password_protected_input( $settings ) {
		if ( ! post_password_required() ) {
			return;
		}

		add_filter( 'the_password_form', function () {
			$output  = '<form action="'. esc_url(home_url( 'wp-login.php?action=postpass' )) .'" method="post">';
			$output .= '<i class="fas fa-lock"></i>';
			$output .= '<p>'. esc_html(get_the_title()) .'</p>';
			$output .= '<input type="password" name="post_password" id="post-'. esc_attr(get_the_id()) .'" placeholder="'. esc_html__( 'Type and hit Enter...', 'crt-manage' ) .'">';
			$output .= '</form>';

			return $output;
		} );

		echo '<div class="crt-grid-item-protected crt-cv-container">';

			echo '<div class="crt-cv-outer">';
				echo '<div class="crt-cv-inner">';
					echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Thumbnail
	public static function render_post_thumbnail( $settings ) {
		$id = get_post_thumbnail_id();
		
		if ( isset($settings['check_ajax_filter']) && $settings['check_ajax_filter'] == 'yes' ) {
			$src = Group_Control_Image_Size::get_attachment_image_src( $id, 'layout_image_crop', $settings['layout_image_crop'] );
		} else {
			$src = Group_Control_Image_Size::get_attachment_image_src( $id, 'layout_image_crop', $settings );
		}
		
		if ( get_post_meta(get_the_ID(), 'crt_secondary_image_id') && !empty(get_post_meta(get_the_ID(), 'crt_secondary_image_id')) ) {
			if ( isset($settings['check_ajax_filter']) && $settings['check_ajax_filter'] == 'yes' ) {
				$src2 = Group_Control_Image_Size::get_attachment_image_src( get_post_meta(get_the_ID(), 'crt_secondary_image_id')[0], 'layout_image_crop', $settings['layout_image_crop'] );
			} else {
				$src2 = Group_Control_Image_Size::get_attachment_image_src( get_post_meta(get_the_ID(), 'crt_secondary_image_id')[0], 'layout_image_crop', $settings );
			}
		} else {
			$src2 = '';
		}

		if ( !empty( get_post_meta( $id, '_wp_attachment_image_alt', true ) ) ) {
			$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
		} else {
			$alt = '' === wp_get_attachment_caption( $id ) ? get_the_title() : wp_get_attachment_caption( $id );
		}

		if ( has_post_thumbnail() ) {
			echo '<div class="crt-grid-image-wrap" data-src="'. esc_url( $src ) .'" data-img-on-hover="'. esc_attr( $settings['secondary_img_on_hover'] ) .'"  data-src-secondary="'. esc_url( $src2 ) .'">';
				if ( 'yes' == $settings['grid_lazy_loading'] ) {
					echo '<img data-no-lazy="1" src="'. CRT_MANAGE_URI . 'assets/img/icon-256x256.png" alt="'. esc_attr( $alt ) .'" class="crt-hidden-image crt-anim-timing-'. esc_attr($settings[ 'image_effects_animation_timing']) .'">';
					if ( 'yes' == $settings['secondary_img_on_hover'] ) {
						echo '<img data-no-lazy="1" src="'. esc_url( $src2 ) . '" alt="'. esc_attr( $alt ) .'" class="crt-hidden-img crt-anim-timing-'. esc_attr($settings[ 'image_effects_animation_timing']) .'">';
					}
				} else {
					echo '<img data-no-lazy="1" src="'. esc_url( $src ) . '" alt="'. esc_attr( $alt ) .'" class="crt-anim-timing-'. esc_attr($settings[ 'image_effects_animation_timing']) .'">';
					if ( 'yes' == $settings['secondary_img_on_hover'] ) {
						echo '<img data-no-lazy="1" src="'. esc_url( $src2 ) . '" alt="'. esc_attr( $alt ) .'" class="crt-hidden-img crt-anim-timing-'. esc_attr($settings[ 'image_effects_animation_timing']) .'">';
					}
				}
			echo '</div>';
		}
	}

	// Render Media Overlay
	public static function render_media_overlay( $settings ) {
		echo '<div class="crt-grid-media-hover-bg '. esc_attr(CRT_Grid_Helpers::get_animation_class( $settings, 'overlay' )) .'" data-url="'. esc_attr( get_the_permalink( get_the_ID() ) ) .'">'; // changed esc_url to esc_attr (why?)

//			if ( defined('CRT_ADDONS_PRO_VERSION') && crt_fs()->can_use_premium_code() ) {
				if ( '' !== $settings['overlay_image']['url'] ) {
					echo '<img data-no-lazy="1" src="'. esc_url( $settings['overlay_image']['url'] ) .'">';
				}
//			}

		echo '</div>';
	}

	// Render Post Title
	public static function render_post_title( $settings, $class, $general_settings = '' ) {
		$title_pointer =  $general_settings['title_pointer'];
		$title_pointer_animation = $general_settings['title_pointer_animation'];
		$pointer_item_class = (isset($general_settings['title_pointer']) && 'none' !==$general_settings['title_pointer']) ? 'class="crt-pointer-item"' : '';
		$open_links_in_new_tab = 'yes' === $general_settings['open_links_in_new_tab'] ? '_blank' : '_self';

		$class .= ' crt-pointer-'. $title_pointer;
		$class .= ' crt-pointer-line-fx crt-pointer-fx-'. $title_pointer_animation;

		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
		$element_title_tag = Utilities::validate_html_tags_wl( $settings['element_title_tag'], 'h2', $tags_whitelist );

		echo '<'. esc_attr($element_title_tag) .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a target="'. $open_links_in_new_tab .'" '. $pointer_item_class .' href="'. esc_url( get_the_permalink() ) .'">';
					if ( 'word_count' === $settings['element_trim_text_by'] ) {
						echo esc_html(wp_trim_words( get_the_title(), $settings['element_word_count'] ));
					} else {
						echo substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count']) .'...';
					}
				echo '</a>';
			echo '</div>';
		echo '</'. esc_attr($element_title_tag) .'>';
	}

	// Render Post Content
	public static function render_post_content( $settings, $class ) {
		$dropcap_class = 'yes' === $settings['element_dropcap'] ? ' crt-enable-dropcap' : '';
		$class .= $dropcap_class;

		if ( '' === get_the_content() ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo wp_kses_post(get_the_content());
			echo '</div>';
		echo '</div>';
	}

	// Render Post Excerpt
	public static function render_post_excerpt( $settings, $class ) {
		$dropcap_class = 'yes' === $settings['element_dropcap'] ? ' crt-enable-dropcap' : '';
		$class .= $dropcap_class;

		if ( '' === get_the_excerpt() ) {
			return;
		}

		$excerpt = get_the_excerpt();

		// Convert HTML entities to their respective characters
		$decoded_excerpt = html_entity_decode($excerpt, ENT_QUOTES | ENT_HTML5, 'UTF-8');

		// Trim the string to the desired length
		$trimmed_excerpt = mb_substr($decoded_excerpt, 0, $settings['element_letter_count'], 'UTF-8');

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				if ( 'word_count' === $settings['element_trim_text_by'] ) {
					$show_dots = $settings['element_show_dots'] === 'yes' ? '...' : '';
					$the_excerpt = str_replace('Edit Template', '', get_the_excerpt());
					echo '<p>'. esc_html(wp_trim_words( $the_excerpt, $settings['element_word_count'], $show_dots )) .'</p>';
				} else {
					// echo '<p>'. substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count']) .'...' . '</p>';
					// echo '<p>'. esc_html(implode('', array_slice( str_split(get_the_excerpt()), 0, $settings['element_letter_count'] ))) .'...' .'</p>';	
					echo '<p>' . esc_html($trimmed_excerpt) . '...' . '</p>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Date
	public static function render_post_date( $settings, $class ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="crt-grid-extra-icon-left">';
						echo $extra_icon;
					echo '</span>';
				}

				// Date
				if ( 'yes' === $settings['show_last_update_date'] ) {
					echo esc_html(get_the_modified_time(get_option( 'date_format' )));
				} else {
					echo esc_html(apply_filters( 'the_date', get_the_date( '' ), get_option( 'date_format' ), '', '' ));
				}

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="crt-grid-extra-icon-right">';
						echo $extra_icon;
					echo '</span>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Time
	public static function render_post_time( $settings, $class ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="crt-grid-extra-icon-left">';
						echo $extra_icon;
					echo '</span>';
				}

				// Time
				echo esc_html(get_the_time(''));

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="crt-grid-extra-icon-right">';
						echo $extra_icon;
					echo '</span>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Author
	public static function render_post_author( $settings, $class ) {
		$author_id =  get_post_field( 'post_author' );

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				// Author
				echo '<a href="'. esc_url( get_author_posts_url( $author_id ) ) .'">';

				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="crt-grid-extra-icon-left">';
						echo $extra_icon;
					echo '</span>';
				}
					if ( 'yes' === $settings['element_show_avatar'] ) {
						echo get_avatar( $author_id, $settings['element_avatar_size'] );
					}

					echo '<span>'. esc_html(get_the_author_meta( 'display_name', $author_id )) .'</span>';

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="crt-grid-extra-icon-right">';
						echo $extra_icon;
					echo '</span>';
				}
				echo '</a>';

				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Comments
	public static function render_post_comments( $settings, $class ) {
		$count = get_comments_number();

		if ( comments_open() ) {
			if ( $count == 1 ) {
				$text = $count .'&nbsp;'. $settings['element_comments_text_2'];
			} elseif ( $count > 1 ) {
				$text = $count .'&nbsp;'. $settings['element_comments_text_3'];
			} else {
				$text = $settings['element_comments_text_1'];
			}

			echo '<div class="'. esc_attr($class) .'">';
				echo '<div class="inner-block">';
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="crt-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

					// Comments
					echo '<a href="'. esc_url( get_comments_link() ) .'">';

					// Icon: Before
					if ( 'before' === $settings['element_extra_icon_pos'] ) {
						ob_start();
						\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
						$extra_icon = ob_get_clean();
		
						echo '<span class="crt-grid-extra-icon-left">';
							echo $extra_icon;
						echo '</span>';
					}

					echo '<span>'. esc_html($text) .'</span>';

					// Icon: After
					if ( 'after' === $settings['element_extra_icon_pos'] ) {
						ob_start();
						\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
						$extra_icon = ob_get_clean();
			
						echo '<span class="crt-grid-extra-icon-right">';
							echo $extra_icon;
						echo '</span>';
					}

					echo '</a>';

					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="crt-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}
				echo '</div>';
			echo '</div>';
		}
	}

	// Render Post Read More
	public static function render_post_read_more( $settings, $class, $general_settings ) {
		$read_more_animation = $general_settings['read_more_animation'];
		$open_links_in_new_tab = 'yes' === $general_settings['open_links_in_new_tab'] ? '_blank' : '_self';

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a target="'. $open_links_in_new_tab .'" href="'. esc_url( get_the_permalink() ) .'" class="crt-button-effect '. esc_attr($read_more_animation) .'">';

				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="crt-grid-extra-icon-left">';
						echo $extra_icon;
					echo '</span>';
				}

				// Read More Text
				echo '<span>'. esc_html( $settings['element_read_more_text'] ) .'</span>';

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();
		
					echo '<span class="crt-grid-extra-icon-right">';
						echo $extra_icon;
					echo '</span>';
				}

				echo '</a>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Likes (Pro)
	public static function render_post_likes( $settings, $class, $post_id ) {
		
//		if ( !defined('CRT_ADDONS_PRO_VERSION') ) {
//			return;
//		}

		$post_likes = new CRT_Post_Likes();

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				echo $post_likes->get_button( $post_id, $settings );

				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Sharing Icons (Pro)
	public static function render_post_sharing_icons( $settings, $class ) {

//		if ( !defined('CRT_ADDONS_PRO_VERSION') ) {
//			return;
//		}

		$args = [
			'icons' => 'yes',
			'tooltip' => $settings['element_sharing_tooltip'],
			'url' => esc_url( get_the_permalink() ),
			'title' => esc_html( get_the_title() ),
			'text' => esc_html( get_the_excerpt() ),
			'image' => esc_url( get_the_post_thumbnail_url() ),
		];

		$hidden_class = '';

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				echo '<span class="crt-post-sharing">';

					if ( 'yes' === $settings['element_sharing_trigger'] ) {
						$hidden_class = ' crt-sharing-hidden';
						$attributes  = ' data-action="'. esc_attr( $settings['element_sharing_trigger_action'] ) .'"';
						$attributes .= ' data-direction="'. esc_attr( $settings['element_sharing_trigger_direction'] ) .'"';

						echo '<a class="crt-sharing-trigger crt-sharing-icon"'. $attributes .'>';
							if ( 'yes' === $settings['element_sharing_tooltip'] ) {
								echo '<span class="crt-sharing-tooltip crt-tooltip">'. esc_html__( 'Share', 'crt-manage' ) .'</span>';
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
					echo '<span class="crt-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Lightbox
	public static function render_post_lightbox( $settings, $class, $post_id ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				$lightbox_source = get_the_post_thumbnail_url( $post_id );

				// Audio Post Type
				if ( 'audio' === get_post_format() ) {
					// Load Meta Value
					if ( 'meta' === $settings['element_lightbox_pfa_select'] ) {
						$utilities = new Utilities();
						$meta_value = get_post_meta( $post_id, $settings['element_lightbox_pfa_meta'], true );

						// URL
						if ( false === strpos( $meta_value, '<iframe ' ) ) {
							add_filter( 'oembed_result', [ $utilities, 'filter_oembed_results' ], 50, 3 );
								$track_url = wp_oembed_get( $meta_value );
							remove_filter( 'oembed_result', [ $utilities, 'filter_oembed_results' ], 50 );

						// Iframe
						} else {
							$track_url = Utilities::filter_oembed_results( $meta_value );
						}

						$lightbox_source = $track_url;
					}

				// Video Post Type
				} elseif ( 'video' === get_post_format() ) {
					// Load Meta Value
					if ( 'meta' === $settings['element_lightbox_pfv_select'] ) {
						$meta_value = get_post_meta( $post_id, $settings['element_lightbox_pfv_meta'], true );

						// URL
						if ( false === strpos( $meta_value, '<iframe ' ) ) {
							$video = \Elementor\Embed::get_video_properties( $meta_value );

						// Iframe
						} else {
							$video = \Elementor\Embed::get_video_properties( Utilities::filter_oembed_results($meta_value) );
						}

						// Provider URL
						if ( 'youtube' === $video['provider'] ) {
							$video_url = '//www.youtube.com/embed/'. $video['video_id'] .'?feature=oembed&autoplay=1&controls=1';
						} elseif ( 'vimeo' === $video['provider'] ) {
							$video_url = 'https://player.vimeo.com/video/'. $video['video_id'] .'?autoplay=1#t=0';
						}

						// Add Lightbox Attributes
						if ( isset( $video_url ) ) {
							$lightbox_source = $video_url;
						}
					}
				}

				// Lightbox Button
				echo '<span data-src="'. esc_url( $lightbox_source ) .'">';
				
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="crt-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

					// Lightbox Icon
					echo '<i class="'. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';

					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="crt-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

				echo '</span>';

				// Media Overlay
				if ( 'yes' === $settings['element_lightbox_overlay'] ) {
					echo '<div class="crt-grid-lightbox-overlay"></div>';
				}
			echo '</div>';
		echo '</div>';
	}

	public static function render_post_custom_field( $settings, $class, $post_id ) {

//		if ( !defined('CRT_ADDONS_PRO_VERSION') ) {
//			return;
//		}

		$custom_field_value = get_post_meta( $post_id, $settings['element_custom_field'], true );
		$custom_field_html = $settings['element_custom_field_wrapper_html'];

		// Check if the custom field is a date and format it
		if ( !is_array($custom_field_value) && strtotime( $custom_field_value ) !== false ) {
			if ( function_exists('get_field_object') && get_field_object($settings['element_custom_field'], $post_id) && isset(get_field_object($settings['element_custom_field'], $post_id)['display_format']) ) {
				$date_format = get_field_object($settings['element_custom_field'], $post_id)['display_format'];
			} else {
				$date_format = get_option('date_format');
			}

			if ( \DateTime::createFromFormat($date_format, $custom_field_value) !== false ) {
				$custom_field_value = date_i18n( $date_format, strtotime( $custom_field_value ) );
			}
		}

		if ( has_filter('crt_update_custom_field_value') ) {
			ob_start();
			apply_filters('crt_update_custom_field_value', $custom_field_value, $post_id, $settings['element_custom_field']);
			$custom_field_value = ob_get_clean();
		}

		// Get First Value if Array (works only for single value checkboxes)
		if ( is_array($custom_field_value) && 1 === count($custom_field_value) ) {
			$custom_field_value = $custom_field_value[0];
		}

		// Erase if Array or Object
		if ( ! is_string( $custom_field_value ) && ! is_numeric( $custom_field_value ) ) {
			$custom_field_value = '';
		}

		// Return if Empty
		if ( '' === $custom_field_value ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .' '. $settings['element_custom_field_style'] .'">';
			echo '<div class="inner-block">';
				if ( 'yes' === $settings['element_custom_field_btn_link'] ) {
					$target = 'yes' === $settings['element_custom_field_new_tab'] ? '_blank' : '_self';
					echo '<a href="'. esc_url($custom_field_value) .'" target="'. esc_attr($target) .'">';
				} else {
					echo '<span>';
				}

				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="crt-grid-extra-icon-left">';
						echo $extra_icon;
					echo '</span>';
				}

				// Custom Field
				if ( 'yes' === $settings['element_custom_field_img_ID'] ) {
					$cf_img = wp_get_attachment_image_src( $custom_field_value, 'full' );
					if ( isset($cf_img) && is_array($cf_img) ) {
						echo '<img src="'. esc_url($cf_img[0]) .'" alt="" width="'. esc_attr($cf_img[1]) .'" height="'. esc_attr($cf_img[2]) .'">';
					}
				} else {
					if ( 'yes' !== $settings['element_custom_field_btn_link'] ) {
						$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
						$element_cf_tag = Utilities::validate_html_tags_wl( $settings['element_cf_tag'], 'span', $tags_whitelist );

						echo '<'. esc_attr($element_cf_tag) .'>';
							if ( 'yes' === $settings['element_custom_field_wrapper'] ) {
								echo str_replace( '*cf_value*', $custom_field_value, $custom_field_html );
							} else {
								echo $custom_field_value;
							}
						echo '</'. esc_attr($element_cf_tag) .'>';
					}
				}

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="crt-grid-extra-icon-right">';
						echo $extra_icon;
					echo '</span>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				if ( 'yes' === $settings['element_custom_field_btn_link'] ) {
					echo '</a>';
				} else {
					echo '</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Element Separator
	public static function render_post_element_separator( $settings, $class ) {
		echo '<div class="'. esc_attr($class .' '. $settings['element_separator_style']) .'">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}

	// Render Post Taxonomies
	public static function render_post_taxonomies( $settings, $class, $post_id, $general_settings ) {
		$terms = wp_get_post_terms( $post_id, $settings['element_select'] );
		$count = 0;
		if(is_wp_error( $terms )) {
		    return;
        }
		$tax1_pointer =  $general_settings['tax1_pointer'];
		$tax1_pointer_animation = $general_settings['tax1_pointer_animation'];
		$tax2_pointer =  $general_settings['tax2_pointer'];
		$tax2_pointer_animation =  $general_settings['tax2_pointer_animation'];
		$pointer_item_class = (isset($general_settings['tax1_pointer']) && 'none' !== $general_settings['tax1_pointer']) || (isset($general_settings['tax2_pointer']) && 'none' !== $general_settings['tax2_pointer']) ? 'crt-pointer-item' : '';

		$settings['element_tax_style'] = 'crt-grid-tax-style-1';
		// Pointer Class
		if ( 'crt-grid-tax-style-1' === $settings['element_tax_style'] ) {
			$class .= ' crt-pointer-'. $tax1_pointer;
			$class .= ' crt-pointer-line-fx crt-pointer-fx-'. $tax1_pointer_animation;
		} else {
			$class .= ' crt-pointer-'. $tax2_pointer;
			$class .= ' crt-pointer-line-fx crt-pointer-fx-'. $tax2_pointer_animation;
		}

		echo '<div class="'. esc_attr($class .' '. $settings['element_tax_style']) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();
		
					echo '<span class="crt-grid-extra-icon-left">';
						echo $extra_icon;
					echo '</span>';
				}

				// Taxonomies
            if($terms) {
				foreach ( $terms as $term ) {

					// Custom Colors
					$enable_custom_colors =  $general_settings['tax1_custom_color_switcher'];

					if ( 'yes' === $enable_custom_colors ) {
						$custom_tax_styles = '';
						$cfc_text = get_term_meta($term->term_id, $general_settings['tax1_custom_color_field_text'], true);
						$cfc_bg = get_term_meta($term->term_id, $general_settings['tax1_custom_color_field_bg'], true);
						$color_styles = 'color:'. $cfc_text .'; background-color:'. $cfc_bg .'; border-color:'. $cfc_bg .';';
						// $css_selector = '.elementor-element'. $this->get_unique_selector() .' .crt-grid-tax-style-1 .inner-block a.crt-tax-id-'. esc_attr($term->term_id);
						$css_selector = '.elementor-element .crt-grid-tax-style-1 .inner-block a.crt-tax-id-'. esc_attr($term->term_id); // TODO: get_unique_selector()
						$custom_tax_styles .= $css_selector .'{'. $color_styles .'}';
						echo '<style>'. esc_html($custom_tax_styles) .'</style>'; // TODO: take out of loop if possible
					}

					echo '<a class="'. $pointer_item_class .' crt-tax-id-'. esc_attr($term->term_id) .'" href="'. esc_url(get_term_link( $term->term_id )) .'">'. esc_html( $term->name );
						if ( ++$count !== count( $terms ) ) {
							echo '<span class="tax-sep">'. esc_html($settings['element_tax_sep']) .'</span>';
						}
					echo '</a>';
				}
            }
				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="crt-grid-extra-icon-right">';
						echo $extra_icon;
					echo '</span>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="crt-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Get Elements
	public static function get_elements( $type, $settings, $class, $post_id, $general_settings ) {
		if ( 'pro-lk' == $type || 'pro-shr' == $type || 'pro-cf' == $type ) {
			$type = 'title';
		}

		switch ( $type ) {
			case 'title':
				CRT_Grid_Helpers::render_post_title( $settings, $class, $general_settings );
				break;

			case 'content':
				CRT_Grid_Helpers::render_post_content( $settings, $class );
				break;

			case 'excerpt':
				CRT_Grid_Helpers::render_post_excerpt( $settings, $class );
				break;

			case 'date':
				CRT_Grid_Helpers::render_post_date( $settings, $class );
				break;

			case 'time':
				CRT_Grid_Helpers::render_post_time( $settings, $class );
				break;

			case 'author':
				CRT_Grid_Helpers::render_post_author( $settings, $class );
				break;

			case 'comments':
				CRT_Grid_Helpers::render_post_comments( $settings, $class );
				break;

			case 'read-more':
				CRT_Grid_Helpers::render_post_read_more( $settings, $class, $general_settings );
				break;

			case 'likes':
				CRT_Grid_Helpers::render_post_likes( $settings, $class, $post_id );
				break;

			case 'sharing':
				CRT_Grid_Helpers::render_post_sharing_icons( $settings, $class );
				break;

			case 'lightbox':
				CRT_Grid_Helpers::render_post_lightbox( $settings, $class, $post_id );
				break;

			case 'custom-field':
				CRT_Grid_Helpers::render_post_custom_field( $settings, $class, $post_id );
				break;

			case 'separator':
				CRT_Grid_Helpers::render_post_element_separator( $settings, $class );
				break;
			
			default:
				CRT_Grid_Helpers::render_post_taxonomies( $settings, $class, $post_id, $general_settings );
				break;
		}

	}

	// Get Elements by Location
	public static function get_elements_by_location( $location, $settings, $post_id ) {
		$locations = [];

		foreach ( $settings['grid_elements'] as $data ) {
			$place = $data['element_location'];
			$align_vr = $data['element_align_vr'];

//			if ( !defined('CRT_ADDONS_PRO_VERSION') ) {
//				$align_vr = 'middle';
//			}

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
				foreach ( $locations[$location] as $align => $elements ) {

					if ( 'middle' === $align ) {
						echo '<div class="crt-cv-container"><div class="crt-cv-outer"><div class="crt-cv-inner">';
					}

					echo '<div class="crt-grid-media-hover-'. esc_attr($align) .' elementor-clearfix">';
						foreach ( $elements as $data ) {
							
							// Get Class
							$class  = 'crt-grid-item-'. $data['element_select'];
							$class .= ' elementor-repeater-item-'. $data['_id'];
							$class .= ' crt-grid-item-display-'. $data['element_display'];
							$class .= ' crt-grid-item-align-'. $data['element_align_hr'];
							$class .= CRT_Grid_Helpers::get_animation_class( $data, 'element' );

							// Element
							CRT_Grid_Helpers::get_elements( $data['element_select'], $data, $class, $post_id, $settings );
						}
					echo '</div>';

					if ( 'middle' === $align ) {
						echo '</div></div></div>';
					}
				}
			} else {
				echo '<div class="crt-grid-item-'. esc_attr($location) .'-content elementor-clearfix">';
					foreach ( $locations[$location] as $data ) {

						// Get Class
						$class  = 'crt-grid-item-'. $data['element_select'];
						$class .= ' elementor-repeater-item-'. $data['_id'];
						$class .= ' crt-grid-item-display-'. $data['element_display'];
						$class .= ' crt-grid-item-align-'. $data['element_align_hr'];

						// Element
						CRT_Grid_Helpers::get_elements( $data['element_select'], $data, $class, $post_id, $settings );
					}
				echo '</div>';
			}

		}
	}

	public static function get_hidden_filter_class($slug, $settings) {
		$posts = new \WP_Query( CRT_Grid_Helpers::get_main_query_args($settings, []) );
		$visible_categories = [];

		if ( $posts->have_posts() ) {
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$categories = get_the_category();

				foreach ($categories as $key => $category) {
					array_push($visible_categories, $category->slug);
				}
			}

			$visible_categories = array_unique($visible_categories);

			wp_reset_postdata();
		}

		return ( ! in_array($slug, $visible_categories) && 'yes' == $settings['filters_hide_empty'] ) ? ' crt-hidden-element' : '';
	}

	// Render Grid Pagination
	public static function render_grid_pagination( $settings ) {
		// Return if Disabled
		if ( 'yes' !== $settings['layout_pagination'] || 'slider' === $settings['layout_select'] ) {
			return;
		}

		if ( 'yes' !== $settings['advanced_filters'] && 1 === CRT_Grid_Helpers::get_max_num_pages( $settings ) ) {
			return;
		}

		global $paged;
		$pages = CRT_Grid_Helpers::get_max_num_pages( $settings );
		
		// $paged = empty( $paged ) ? 1 : $paged;
		if ( get_query_var('paged') ) {
			$paged = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}

//		if ( !defined('CRT_ADDONS_PRO_VERSION')) {
//			$settings['pagination_type'] = 'pro-is' == $settings['pagination_type'] ? 'default' : $settings['pagination_type'];
//		}

		echo '<div class="crt-grid-pagination elementor-clearfix crt-grid-pagination-'. esc_attr($settings['pagination_type']) .'" data-pages="'. esc_attr($pages) .'">';

		// Default
		if ( 'default' === $settings['pagination_type'] ) {
			if ( $paged < $pages ) {
				echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="crt-prev-post-link">';
					echo Utilities::get_crt_icon( $settings['pagination_on_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo esc_html($settings['pagination_older_text']);
				echo '</a>';
			} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
				echo '<span class="crt-prev-post-link crt-disabled-arrow">';
					echo Utilities::get_crt_icon( $settings['pagination_on_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo esc_html($settings['pagination_older_text']);
				echo '</span>';
			}

			if ( $paged > 1 ) {
				echo '<a href="'. esc_url(get_pagenum_link( $paged - 1, true )) .'" class="crt-next-post-link">';
					echo esc_html($settings['pagination_newer_text']);
					echo Utilities::get_crt_icon( $settings['pagination_on_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</a>';
			} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
				echo '<span class="crt-next-post-link crt-disabled-arrow">';
					echo esc_html($settings['pagination_newer_text']);
					echo Utilities::get_crt_icon( $settings['pagination_on_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</span>';
			}

		// Numbered
		} elseif ( 'numbered' === $settings['pagination_type'] ) {
			$range = $settings['pagination_range'];
			$showitems = ( $range * 2 ) + 1;

			if ( 1 !== $pages ) {

				if ( 'yes' === $settings['pagination_prev_next'] || 'yes' === $settings['pagination_first_last'] ) {
					echo '<div class="crt-grid-pagi-left-arrows">';

					if ( 'yes' === $settings['pagination_first_last'] ) {
						if ( $paged >= 2 ) {
							echo '<a href="'. esc_url(get_pagenum_link( 1, true )) .'" class="crt-first-page">';
								echo Utilities::get_crt_icon( $settings['pagination_fl_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo '<span>'. esc_html($settings['pagination_first_text']) .'</span>';
							echo '</a>';
						} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
							echo '<span class="crt-first-page crt-disabled-arrow">';
								echo Utilities::get_crt_icon( $settings['pagination_fl_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo '<span>'. esc_html($settings['pagination_first_text']) .'</span>';
							echo '</span>';
						}
					}

					if ( 'yes' === $settings['pagination_prev_next'] ) {
						if ( $paged > 1 ) {
							echo '<a href="'. esc_url(get_pagenum_link( $paged - 1, true )) .'" class="crt-prev-page">';
								echo Utilities::get_crt_icon( $settings['pagination_pn_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo '<span>'. esc_html($settings['pagination_prev_text']) .'</span>';
							echo '</a>';
						} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
							echo '<span class="crt-prev-page crt-disabled-arrow">';
								echo Utilities::get_crt_icon( $settings['pagination_pn_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo '<span>'. esc_html($settings['pagination_prev_text']) .'</span>';
							echo '</span>';
						}
					}

					echo '</div>';
				}

				for ( $i = 1; $i <= $pages; $i++ ) {
					if ( 1 !== $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
						if ( $paged === $i ) {
							echo '<span class="crt-grid-current-page">'. esc_html($i) .'</span>';
						} else {
							echo '<a href="'. esc_url(get_pagenum_link( $i, true )) .'">'. esc_html($i) .'</a>';
						}
					}
				}

				if ( 'yes' === $settings['pagination_prev_next'] || 'yes' === $settings['pagination_first_last'] ) {
					echo '<div class="crt-grid-pagi-right-arrows">';

					if ( 'yes' === $settings['pagination_prev_next'] ) {
						if ( $paged < $pages ) {
							echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="crt-next-page">';
								echo '<span>'. esc_html($settings['pagination_next_text']) .'</span>';
								echo Utilities::get_crt_icon( $settings['pagination_pn_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo '</a>';
						} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
							echo '<span class="crt-next-page crt-disabled-arrow">';
								echo '<span>'. esc_html($settings['pagination_next_text']) .'</span>';
								echo Utilities::get_crt_icon( $settings['pagination_pn_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo '</span>';
						}
					}

					if ( 'yes' === $settings['pagination_first_last'] ) {
						if ( $paged <= $pages - 1 ) {
							echo '<a href="'. esc_url(get_pagenum_link( $pages, true )) .'" class="crt-last-page">';
								echo '<span>'. esc_html($settings['pagination_last_text']) .'</span>';
								echo Utilities::get_crt_icon( $settings['pagination_fl_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo '</a>';
						} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
							echo '<span class="crt-last-page crt-disabled-arrow">';
								echo '<span>'. esc_html($settings['pagination_last_text']) .'</span>';
								echo Utilities::get_crt_icon( $settings['pagination_fl_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo '</span>';
						}
					}

					echo '</div>';
				}
			}

		// Load More / Infinite Scroll
		} else {
			echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="crt-load-more-btn" data-e-disable-page-transition >';
				echo esc_html($settings['pagination_load_more_text']);
			echo '</a>';

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
		}

		echo '</div>';
	}

	public function crt_get_filtered_count_posts() {
		$nonce = $_POST['nonce'];

		if (!isset($nonce) || !wp_verify_nonce($nonce, 'crt-addons-js')) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'crt-manage'),
			));
		}

		if ( isset($_POST['crt_url_params']) ) {
			$results = [];
		
			// Loop through each set of parameters
			foreach ($_POST['crt_url_params'] as $params) {
				$query_args = CRT_Grid_Helpers::get_main_query_args($_POST['grid_settings'], $params);
				$query = new \WP_Query($query_args);
		
				// Add the count of found posts to the results array
				$results[] = [
					'found_posts' => $query->found_posts,
					'post_count' => $query->post_count,
				];
		
				wp_reset_postdata();
			}
		
			// Send the array of results
			wp_send_json_success($results);
		} else if ( isset($_POST['grid_settings']) ) {
			$settings = $_POST['grid_settings'];
			$page_count =  CRT_Grid_Helpers::get_max_num_pages( $settings );
		
			wp_send_json_success([
				'page_count' => $page_count,
			]);
		}
		
		wp_die();
	}

	public function crt_grid_filters_ajax() {
		$nonce = $_POST['nonce'];

		if (!isset($nonce)) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'crt-manage'),
			));
		}

		$start = microtime(true);
		// Get Settings
		$settings = $_POST['grid_settings'];
	
		// Create a unique cache key based on the settings
		$cache_key = 'crt_grid_filters_' . md5(serialize(CRT_Grid_Helpers::get_main_query_args($settings, [])));
		// wp_send_json_success($cache_key);
		// wp_die();
	
		// Try to get cached data
		// $cached_data = get_transient($cache_key);
		
		// if ($cached_data !== false) {
		// 	$end = microtime(true);
		// 	$duration = round(($end - $start) * 1000, 2); // in ms
		// 	wp_send_json_success([
		// 		'output' => $cached_data,
		// 		'duration' => $duration
		// 	]);
		// 	wp_die();
		// }
	
		// Start output buffering to capture the HTML output
		ob_start();
	
		// Get Posts
		$posts = new \WP_Query(CRT_Grid_Helpers::get_main_query_args($settings, []));
	
		// Loop: Start
		if ($posts->have_posts()) :
	
			while ($posts->have_posts()) : $posts->the_post();
	
				// Post Class
				$post_class = implode(' ', get_post_class('crt-grid-item elementor-clearfix', get_the_ID()));
	
				// Grid Item
				echo '<article class="' . esc_attr($post_class) . '">';
	
				// Password Protected Form
				CRT_Grid_Helpers::render_password_protected_input($settings);
	
				// Inner Wrapper
				echo '<div class="crt-grid-item-inner">';
	
				// Content: Above Media
				CRT_Grid_Helpers::get_elements_by_location('above', $settings, get_the_ID());
	
				// Media
				if (has_post_thumbnail()) {
					echo '<div class="crt-grid-media-wrap' . esc_attr(CRT_Grid_Helpers::get_image_effect_class($settings)) . '" data-overlay-link="' . esc_attr($settings['overlay_post_link']) . '">';
					// Post Thumbnail
					CRT_Grid_Helpers::render_post_thumbnail($settings, get_the_ID());
	
					// Media Hover
					echo '<div class="crt-grid-media-hover crt-animation-wrap">';
					// Media Overlay
					CRT_Grid_Helpers::render_media_overlay($settings);
	
					// Content: Over Media
					CRT_Grid_Helpers::get_elements_by_location('over', $settings, get_the_ID());
	
					echo '</div>';
					echo '</div>';
				}
	
				// Content: Below Media
				CRT_Grid_Helpers::get_elements_by_location('below', $settings, get_the_ID());
	
				echo '</div>'; // End .crt-grid-item-inner
	
				echo '</article>'; // End .crt-grid-item
	
			endwhile;
	
			// reset
			wp_reset_postdata();
	
		// Loop: End
		else :

			if ( 'dynamic' === $settings['query_selection'] || 'current' === $settings['query_selection'] ) {
				echo '<h2>'. esc_html($settings['query_not_found_text']) .'</h2>';
			}
			
		endif;

		// Get the buffered content
		$output = ob_get_clean();
	
		// Cache the output
		// set_transient($cache_key, $output, HOUR_IN_SECONDS);
	
		// Return the output
		$end = microtime(true);
		$duration = round(($end - $start) * 1000, 2); // in ms
		wp_send_json_success([
			'output' => $output,
			'duration' => $duration,
			'found_posts' => $posts->found_posts,
			'post_count' => $posts->post_count,
		]);

		wp_die();
	}

}

new CRT_Grid_Helpers();