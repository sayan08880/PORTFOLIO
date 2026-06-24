<?php
/*-----------------------------------------------------------------------------------*/
/*	Author Widget Class
/*-----------------------------------------------------------------------------------*/

class CRT_Manage_Author_Widget extends WP_Widget {

  private $users_split_at = 200; //Do not run get_users() if there are more than 200 users on the website

  	public $defaults;

	function __construct() {
		$widget_ops = array(
			'classname' => 'crt_manage_author_widget',
			'description' => __('Use this widget to display author/user profile info', 'crt-manage'),
			'customize_selective_refresh' => true,
			'show_instance_in_rest'       => true,
		);
		$control_ops = array( 'id_base' => 'crt_manage_author_widget' );
		parent::__construct( 'crt_manage_author_widget', __('CRT Manage Author', 'crt-manage'), $widget_ops, $control_ops );


		//Allow themes or plugins to modify default parameters
		$defaults = apply_filters('crt_manage_author_widget_modify_defaults',array(
				'title' => esc_html__('About Author', 'crt-manage'),
				'author' => 0,
				'auto_detect' => 0,
				'display_name' => 1,
				'display_avatar' => 1,
				'display_desc' => 1,
				'display_social' => 1,
				'display_all_posts' => 1,
				'avatar_size' => 64,
				'name_to_title' => 0,
				'link_to_name' => 0,
				'link_to_avatar' => 0,
				'link_text' => __('View all posts', 'crt-manage'),
				'link_url' => '',
				'limit_chars' => ''
			));

		$this->defaults = $defaults;

        add_action( 'wp_enqueue_scripts', array($this,'enqueue_styles'));
        add_action( 'admin_enqueue_scripts', array($this,'enqueue_styles'));
    }


	function widget( $args, $instance ) {

		extract( $args );

		$instance = wp_parse_args( (array) $instance, $this->defaults );
        include dirname( __FILE__, 2 ) . '/widget/crt-manage-author-widget-template.php';
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = wp_strip_all_tags( $new_instance['title'] );
		$instance['author'] = absint( $new_instance['author'] );
		$instance['auto_detect'] = !empty($new_instance['auto_detect']) ? 1 : 0;
		$instance['display_name'] = !empty($new_instance['display_name']) ? 1 : 0;
		$instance['display_avatar'] = !empty($new_instance['display_avatar']) ? 1 : 0;
		$instance['display_desc'] = !empty($new_instance['display_desc']) ? 1 : 0;
		$instance['display_social'] = !empty($new_instance['display_social']) ? 1 : 0;
		$instance['display_all_posts'] = !empty($new_instance['display_all_posts']) ? 1 : 0;
		$instance['name_to_title'] = !empty($new_instance['name_to_title']) ? 1 : 0;
		$instance['link_to_name'] = !empty($new_instance['link_to_name']) ? 1 : 0;
		$instance['link_to_avatar'] = !empty($new_instance['link_to_avatar']) ? 1 : 0;
		$instance['link_text'] = wp_strip_all_tags( $new_instance['link_text'] );
		$instance['link_url'] = !empty( $new_instance['link_url'] ) ? esc_url($new_instance['link_url']) : '';
		$instance['avatar_size'] = !empty($new_instance['avatar_size']) ? absint($new_instance['avatar_size']) : 64;
		$instance['limit_chars'] = !empty( $new_instance['limit_chars'] ) ? absint($new_instance['limit_chars']) : '';


		return $instance;
	}

	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title', 'crt-manage'); ?>:</label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" />
		</p>
        <div>
		<p>

			<?php if( $this->count_users() <= $this->users_split_at ) : ?>

			<?php $authors = get_users(); ?>
			<label for="<?php echo esc_attr($this->get_field_id( 'author' )); ?>"><?php esc_html_e('Choose author/user', 'crt-manage'); ?>:</label>
			<select name="<?php echo esc_attr($this->get_field_name( 'author' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'author' )); ?>" class="widefat">
			<?php foreach($authors as $author) : ?>
				<option value="<?php echo esc_attr($author->ID); ?>" <?php selected($author->ID, $instance['author']); ?>><?php echo esc_attr($author->data->user_login); ?></option>
			<?php endforeach; ?>
			</select>

			<?php else: ?>

			<label for="<?php echo esc_attr($this->get_field_id( 'author' )); ?>"><?php esc_html_e('Enter author/user ID', 'crt-manage'); ?>:</label>
			<input id="<?php echo esc_attr($this->get_field_id( 'author' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'author' )); ?>" value="<?php echo esc_attr($instance['author']); ?>" class="small-text" />

			<?php endif; ?>

		</p>

		<p>
		  	<input class="checkbox" id="<?php echo esc_attr($this->get_field_id( 'auto_detect' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'auto_detect' )); ?>" <?php checked(1, $instance['auto_detect']); ?>/>
		  	<label for="<?php echo esc_attr($this->get_field_id( 'auto_detect' )); ?>"><?php esc_html_e('Automatically detect author', 'crt-manage'); ?></label>
		  	<small class="howto"><?php esc_html_e('Use this option to automatically detect author if this sidebar is used on single post template or author template', 'crt-manage'); ?></small>
		</p>
		<h4><?php esc_html_e('Display Options', 'crt-manage'); ?></h4>
		<ul>
			<li>
				<input class="checkbox" id="<?php echo esc_attr($this->get_field_id( 'display_avatar' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'display_avatar' )); ?>" <?php checked(1, $instance['display_avatar']); ?>/>
				<label for="<?php echo esc_attr($this->get_field_id( 'display_avatar' )); ?>"><?php esc_html_e('Display author avatar', 'crt-manage'); ?></label>
			</li>
			<li class="meks-avatar-size-field">
				<label for="<?php echo esc_attr($this->get_field_id( 'avatar_size' )); ?>"><?php esc_html_e('Avatar size:', 'crt-manage'); ?></label>
				<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'avatar_size' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'avatar_size' )); ?>" value="<?php echo esc_attr($instance['avatar_size']); ?>" class="small-text"/>
				<small class="howto"><?php esc_html_e('Avatar size in pixels', 'crt-manage'); ?></small>
			</li>
		</ul>

		<ul>
			<li>
				<input class="checkbox" id="<?php echo esc_attr($this->get_field_id( 'display_name' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'display_name' )); ?>" <?php checked(1, $instance['display_name']); ?>/>
				<label for="<?php echo esc_attr($this->get_field_id( 'display_name' )); ?>"><?php esc_html_e('Display author name', 'crt-manage'); ?></label>
			</li>
			<li>
				<input class="checkbox" id="<?php echo esc_attr($this->get_field_id( 'name_to_title' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'name_to_title' )); ?>" <?php checked(1, $instance['name_to_title']); ?>/>
				<label for="<?php echo esc_attr($this->get_field_id( 'name_to_title' )); ?>"><?php esc_html_e('Overwrite widget title with author name', 'crt-manage'); ?></label>
			</li>
		</ul>

		<ul>
			<li>
				<input class="checkbox" id="<?php echo esc_attr($this->get_field_id( 'display_desc' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'display_desc' )); ?>" <?php checked(1, $instance['display_desc']); ?>/>
				<label for="<?php echo esc_attr($this->get_field_id( 'display_desc' )); ?>"><?php esc_html_e('Display author description', 'crt-manage'); ?></label>
			</li>
			<li>
				<label for="<?php echo esc_attr($this->get_field_id( 'limit_chars' )); ?>"><?php esc_html_e('Limit description:', 'crt-manage'); ?></label>
				<input id="<?php echo esc_attr($this->get_field_id( 'limit_chars' )); ?>" type="number" name="<?php echo esc_attr($this->get_field_name( 'limit_chars' )); ?>" value="<?php echo esc_attr($instance['limit_chars']); ?>" class="widefat" />
				<small class="howto"><?php esc_html_e('Specify number of characters to limit author description length', 'crt-manage'); ?></small>
			</li>
		</ul>

        <ul>
            <li>
                <input class="checkbox" id="<?php echo esc_attr($this->get_field_id( 'display_social' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'display_social' )); ?>" <?php checked(1, $instance['display_social']); ?>/>
                <label for="<?php echo esc_attr($this->get_field_id( 'display_social' )); ?>"><?php esc_html_e('Display social', 'crt-manage'); ?></label>
            </li>
        </ul>

		<ul>
			<li>
				<input class="checkbox" id="<?php echo esc_attr($this->get_field_id( 'link_to_name' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'link_to_name' )); ?>" <?php checked(1, $instance['link_to_name']); ?>/>
				<label for="<?php echo esc_attr($this->get_field_id( 'link_to_name' )); ?>"><?php esc_html_e('Link author name', 'crt-manage'); ?></label>
			</li>
			<li>
				<input class="checkbox" id="<?php echo esc_attr($this->get_field_id( 'link_to_avatar' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'link_to_avatar' )); ?>" <?php checked(1, $instance['link_to_avatar']); ?>/>
				<label for="<?php echo esc_attr($this->get_field_id( 'link_to_avatar' )); ?>"><?php esc_html_e('Link author avatar', 'crt-manage'); ?></label>
			</li>
			<li>
				<input class="checkbox" id="<?php echo esc_attr($this->get_field_id( 'display_all_posts' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'display_all_posts' )); ?>" <?php checked(1, $instance['display_all_posts']); ?>/>
				<label for="<?php echo esc_attr($this->get_field_id( 'display_all_posts' )); ?>"><?php esc_html_e('Display author "all posts" archive link', 'crt-manage'); ?></label>
			</li>
			<li>
				<label for="<?php echo esc_attr($this->get_field_id( 'link_text' )); ?>"><?php esc_html_e('Link text:', 'crt-manage'); ?></label>
				<input id="<?php echo esc_attr($this->get_field_id( 'link_text' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'link_text' )); ?>" value="<?php echo esc_attr($instance['link_text']); ?>" class="widefat"/>
				<small class="howto"><?php esc_html_e('Specify text for "all posts" link if you want to show separate link', 'crt-manage'); ?></small>
			</li>
			<li>
				<label for="<?php echo esc_attr($this->get_field_id( 'link_url' )); ?>"><?php esc_html_e('Override author link URL:', 'crt-manage'); ?></label>
				<input id="<?php echo esc_attr($this->get_field_id( 'link_url' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'link_url' )); ?>" value="<?php echo esc_attr($instance['link_url']); ?>" class="widefat"/>
				<small class="howto"><?php esc_html_e('Specify custom URL if you want to override default author archive link', 'crt-manage'); ?></small>
			</li>
		</ul>
        </div>

		<?php do_action('crt_manage_author_widget_add_opts',$this,$instance);?>

		<?php

	}

	/* Check total number of users on the website */
	function count_users(){
		$user_count = count_users();
		if(isset($user_count['total_users']) && !empty($user_count['total_users'])){
			return $user_count['total_users'];
		}
		return 0;
	}

	/**
	 * Limit character description
	 *
	 * @param string  $string Content to trim
	 * @param int     $limit  Number of characters to limit
	 * @param string  $more   Chars to append after trimmed string
	 * @return string Trimmed part of the string
	*/
	public function trim_chars( $string, $limit, $more = '...' ) {

		if ( !empty( $limit ) ) {

			$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $string ), ' ' );
			preg_match_all( '/./u', $text, $chars );
			$chars = $chars[0];
			$count = count( $chars );

			if ( $count > $limit ) {

				$chars = array_slice( $chars, 0, $limit );

				for ( $i = ( $limit -1 ); $i >= 0; $i-- ) {
					if ( in_array( $chars[$i], array( '.', ' ', '-', '?', '!' ) ) ) {
						break;
					}
				}

				$chars =  array_slice( $chars, 0, $i );
				$string = implode( '', $chars );
				$string = rtrim( $string, ".,-?!" );
				$string.= $more;
			}

		}

		return $string;
	}

    function enqueue_styles(){
        wp_register_style( 'crt-manage-author-widget', CRT_MANAGE_URI.'/assets/css/author-style.css', array(), CRT_MANAGE_VERSION  );
        wp_enqueue_style( 'crt-manage-author-widget' );
    }

    public function crt_manage_social_author($author_id = '', $align = 'justify-content-center') {
        $user_socials = array(
            'crt_manage_user_social_facebook' => 'Facebook',
            'crt_manage_user_social_x-twitter' => 'Twitter',
            'crt_manage_user_social_google' => 'Google',
            'crt_manage_user_social_youtube' => 'Youtube',
            'crt_manage_user_social_email' => 'Email',
            'crt_manage_user_social_instagram' => 'Instagram',
            'crt_manage_user_social_pinterest' => 'Pinterest',
            'crt_manage_user_social_linkedin' => 'LinkedIn',
        );
        if(empty($author_id)) {
            $author_id = get_the_author_meta('ID');
        }
        if(!empty($author_id)) {
            $authors = array();
            foreach ($user_socials as $name => $value) {
                $url = get_user_meta($author_id, $name . '_url');
                if(isset($url[0]) && $url[0]) {
                    $icon = explode('_', $name);
                    $authors[] = array('url' => $url[0], 'icon' => $icon[4]);
                }
            }
            if(!empty($authors)) {
                $class_social_item = 'me-2 ms-2';
                if($align == 'justify-content-center') {
                    $class_social_item = 'me-2 ms-2';
                } elseif($align == 'justify-content-start') {
                    $class_social_item = 'me-3';
                } elseif($align == 'justify-content-end') {
                    $class_social_item = 'ms-3';
                }
                ob_start();
                ?>
                <ul class="author-socials mt-3 d-flex m-0 p-0 <?php echo esc_attr($align); ?>">
                    <?php foreach ($authors as $author_item): if($author_item['icon'] == 'email') { $author_item['icon'] = 'envelope-o'; } ?>
                        <li class="<?php echo esc_attr($class_social_item); ?> list-unstyled"><a href="<?php echo esc_attr($author_item['url']) ?>" target="_blank"><i class="fa-brands fa-<?php echo esc_attr($author_item['icon']) ?>"></i></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php
                return ob_get_clean();
            }
        }
        return '';
    }

}

add_action('widgets_init','crt_manage_author_widget_init');

function crt_manage_author_widget_init() {
    register_widget('CRT_Manage_Author_Widget');
}

