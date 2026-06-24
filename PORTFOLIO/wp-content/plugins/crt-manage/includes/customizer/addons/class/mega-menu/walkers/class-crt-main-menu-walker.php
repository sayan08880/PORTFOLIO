<?php

class Crt_Main_Menu_Walker extends \Walker_Nav_Menu {

	public function __construct( $menu_item_highlight ) {
		$this->menu_item_highlight = $menu_item_highlight;
	}

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'mega-content' === $this->get_menu_item_type() ) {
			return;
		}

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu', 'crt-sub-menu' );

		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul $class_names>{$n}";
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'mega-content' === $this->get_menu_item_type() ) {
			return;
		}


		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$this->set_menu_item_type( $item->ID, $depth );
		
		if ( 'mega-content' === $this->get_menu_item_type() && 0 < $depth ) {
			return;
		}

        // Get Settings
        $settings = $this->get_mega_menu_settings($item->ID);

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$indent   = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		if ( $this->has_mega_menu( $item->ID ) ) {
			$classes[] = 'menu-item-has-children';
		}

		$id_attr = '';
		$custom_width_attr = '';

		if ( $settings ) {
			if ( 'true' === $settings['crt_mm_enable'] ) {
				$classes[] = 'crt-mega-menu-'. $settings['crt_mm_enable'];
				$classes[] = 'crt-mega-menu-pos-'. $settings['crt_mm_position'];
				$classes[] = 'crt-mega-menu-width-'. $settings['crt_mm_width'];
				$id_attr = ' data-id="'. $item->ID .'"';
				$custom_width_attr = 'custom' === $settings['crt_mm_width'] ? ' data-custom-width="'. $settings['crt_mm_custom_width'] .'"' : '';
			}
		}

		$item_li_class = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$item_li_class = $item_li_class ? ' class="' . esc_attr( $item_li_class ) . '"' : '';

        $item_a_class = 'crt-menu-item crt-pointer-item';

		if ( in_array( 'current-menu-item', $item->classes ) && ('yes' === $this->menu_item_highlight) ) {
			$item_a_class .= ' crt-active-menu-item';
		}

		if ( in_array( 'current-menu-item', $item->classes ) && ('yes' === $this->menu_item_highlight) ) {
			$item_li_class .= ' crt-active-menu-item';
		}
		
		$output .= $indent .'<li'. $id . $item_li_class . $custom_width_attr . $id_attr .'>';

			$item_output = $args->before;
				$submenu_icon = '';
				// Parent Item
				if ( in_array( 'menu-item-has-children', $item->classes ) || $this->has_mega_menu( $item->ID ) ) {
					if ( ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) && ('yes' === $this->menu_item_highlight) ) {
						$item_a_class .= ' crt-active-menu-item';
					}

					// Add Sub Menu Icon
					if ( $depth > 0 ) {
						$submenu_icon .='<i class="crt-sub-icon fas crt-sub-icon-rotate" aria-hidden="true"></i>';
					} else {
						$submenu_icon .='<i class="crt-sub-icon fas" aria-hidden="true"></i>';
					}
				}

				// Sub Menu Classes
				if ( $depth > 0 ) {
					$item_a_class = 'crt-sub-menu-item';

					if ( ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) && ('yes' === $this->menu_item_highlight) ) {
						$item_a_class .= ' crt-active-menu-item';
					}
				}

                $item_icon_html = '';
                $item_badge_html = '';

                // If has Settings
                if ( $settings ) {
                    // Icon
                    if ( '' !== $settings['crt_mm_icon_picker'] ) {
                        $color = '' !== $settings['crt_mm_icon_color'] ? ' color: '. $settings['crt_mm_icon_color'] : ';';
                        $font_size = '' !== $settings['crt_mm_icon_size'] ? 'font-size: '. $settings['crt_mm_icon_size'] .'px;' : '';
                        $item_icon_style = ( '' !== $font_size || '' !== $color || '' !== $distance ) ? ' style="'. $font_size . $color .'"' : '';
                        $item_icon_html = '<i class="crt-mega-menu-icon '. $settings['crt_mm_icon_picker'] .'"'. $item_icon_style .'></i>';
                    }

                    // Badge
                    if ( '' !== $settings['crt_mm_badge_text'] ) {
						$item_badge_class = 'true' === $settings['crt_mm_badge_animation'] ? ' crt-mega-menu-badge-animation' : '';
                        $item_badge_style = 'color: '. $settings['crt_mm_badge_color'] .';';
                        $item_badge_style .= 'background-color: '. $settings['crt_mm_badge_bg_color'] .';';
                        $item_badge_style .= 'border-color: '. $settings['crt_mm_badge_bg_color'] .';';
                        $item_badge_html = '<span class="crt-mega-menu-badge'. $item_badge_class .'" style="'. $item_badge_style .'">'. $settings['crt_mm_badge_text'] .'</span>';
                    }
                }

                // Link Attributes
                $atts = [];
                $atts['title'] = ! empty( $item->attr_title ) ? $item->attr_title : '';
                $atts['target'] = ! empty( $item->target ) ? $item->target : '';
                $atts['rel'] = ! empty( $item->xfn ) ? $item->xfn : '';
                $atts['href'] = ! empty( $item->url ) ? $item->url : '#';
                $atts['class'] = $item_a_class;
        
                $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
        
                $attributes = '';
        
                foreach ( $atts as $attr => $value ) {
                    if ( ! empty( $value ) ) {
                        $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                        $attributes .= ' ' . $attr . '="' . $value . '"';
                    }
                }
                
				// Link Output
				$item_output .= '<a'. $attributes .'>';
                    $item_output .= $item_icon_html;
					$item_output .= $args->link_before;
					$item_output .= '<span>'. apply_filters( 'the_title', $item->title, $item->ID ) .'</span>';
					$item_output .= $args->link_after;
                    $item_output .= $item_badge_html;
					$item_output .= $submenu_icon;
				$item_output .= '</a>';
			$item_output .= $args->after;
		
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( 'mega-content' === $this->get_menu_item_type() && 0 < $depth ) {
			return;
		}
		
		if ( $depth === 0 ) {
			if ( $this->has_mega_menu($item->ID) ) {
				$elementor = \Elementor\Plugin::instance();
				$mega_id = get_post_meta( $item->ID, 'crt-mega-menu-item', true);
				
				$type = get_post_meta(get_the_ID(), '_crt_template_type', true) || get_post_meta($mega_id, '_elementor_template_type', true);
				$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;
				
				$content = $elementor->frontend->get_builder_content_for_display($mega_id, $has_css);
				$output .= '<div class="crt-sub-mega-menu">'. $content .'</div>';
			}

			$output .= "</li>\n";
		}
	}

	public function set_menu_item_type( $item_id = 0, $depth = 0 ) {
		if ( 0 < $depth ) {
			return;
		}

		$item_type = 'default-content';

		if ( $this->has_mega_menu( $item_id ) ) {
			$item_type = 'mega-content';
		}

		wp_cache_set( 'menu-item-type', $item_type, 'crt-mega-menu' );
	}

	public function get_menu_item_type() {
		return wp_cache_get( 'menu-item-type', 'crt-mega-menu' );
	}

	public function has_mega_menu( $item_id ) {
		$settings = get_post_meta( $item_id, 'crt-mega-menu-settings', true);

		return isset($settings['crt_mm_enable']) && 'true' === $settings['crt_mm_enable'] ? true : false;
	}

	public function get_mega_menu_settings( $item_id ) {
		$settings = get_post_meta( $item_id, 'crt-mega-menu-settings', true);

		return ! empty($settings) ? $settings : false;
	}
}