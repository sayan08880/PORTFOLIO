<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Form_Submissions {
    public function __construct() {
        // SUBMISSIONS CPT
        // aq rame solution gvinda
//        add_menu_page( esc_html__( 'CRT Builder', 'crt-manage' ), esc_html__( 'CRT Builder', 'crt-manage' ), 'manage_options', 'crt-manage', [ $this, 'crt_manage_render'], 'dashicons-admin-site', '58.6' );

        add_submenu_page( 'crt-manage',  'CRT Addons', 'CRT Addons', 'manage_options', 'crt-manage', 'crt_manage_render', 1 );
        add_action('admin_enqueue_scripts',[$this, 'enqueue_submissions_script']);
        add_action('init', [$this, 'crt_submissions']);
        add_action('admin_menu', [$this, 'reorder_crt_submissions_submenu'], 999);
        add_filter('manage_crt_submissions_posts_columns', [$this, 'crt_submissions_custom_columns']);
        add_action('manage_crt_submissions_posts_custom_column', [$this, 'crt_submissions_custom_column_content'], 10, 2);
        add_filter('manage_edit-crt_submissions_sortable_columns', [$this, 'crt_submissions_sortable_columns']);
        add_action('wp_ajax_crt_submissions_update_read_status', [$this, 'crt_submissions_update_read_status']);
        add_action('current_screen', [$this, 'crt_submissions_mark_as_read']);
        add_filter('post_row_actions', [$this, 'crt_submissions_row_actions'], 10, 2);
        add_action('current_screen', [$this, 'crt_submissions_remove_bulk_edit_filter']);
        // add_action('pre_get_posts', 'crt_submissions_orderby');
    }

    public function reorder_crt_submissions_submenu() {
        global $submenu;
    
        $parent_slug = 'crt-manage';
        $submenu_slug = 'edit.php?post_type=crt_submissions';
        $desired_position = 7; // Change this to adjust the position of the submenu item
    
        if (isset($submenu[$parent_slug])) {
            $submenu_items = $submenu[$parent_slug];
            $found_key = null;
    
            foreach ($submenu_items as $key => $item) {
                if ($item[2] === $submenu_slug) {
                    $found_key = $key;
                    break;
                }
            }
    
            if ($found_key !== null) {
                $item_to_move = $submenu[$parent_slug][$found_key];
                unset($submenu[$parent_slug][$found_key]);
                array_splice($submenu[$parent_slug], $desired_position - 1, 0, [$item_to_move]);
            }
        }
    }

    public function enqueue_submissions_script($hook) {
        $post_type = 'crt_submissions';
    
        // Check if the current page is the custom post type's edit or add new page
        if ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'edit.php' ) {
            global $post;
//            echo $post->post_type;die;
            // Check if the current post type is the desired one
            if (isset($post) && $post->post_type === $post_type) {
                // enqueue CSS
                wp_enqueue_style( 'crt-submissions-css', CRT_MANAGE_URI . '/assets/css/admin/crt-submissions.css', [], CRT_MANAGE_VERSION );
    
                // enqueue JS
                wp_enqueue_script( 'crt-submissions-js', CRT_MANAGE_URI . '/assets/js/admin/crt-submissions.js', ['jquery'], CRT_MANAGE_VERSION );
    
                wp_localize_script(
                    'crt-submissions-js',
                    'CrtSubmissions', // This is used in the js file to group all of your scripts together
                    [
                        'ajaxurl' => admin_url( 'admin-ajax.php' ),
                        'resturl' => get_rest_url() . 'crtaddons/v1',
                        'nonce' => wp_create_nonce( 'crt-submissions-js' ),
                        'form_name' => get_post_meta($post->ID, 'crt_form_name', true),
                        'form_id' => get_post_meta($post->ID, 'crt_form_id', true),
                        'form_page' => get_post_meta($post->ID, 'crt_form_page', true),
                        'form_page_id' => get_post_meta($post->ID, 'crt_form_page_id', true),
                        'form_page_url' =>  get_permalink( get_post_meta($post->ID, 'crt_form_page_id', true)),
                        'form_page_editor' => admin_url('post.php?post=' . get_post_meta($post->ID, 'crt_form_page_id', true) . '&action=elementor'),
                        'form_agent' => get_post_meta($post->ID, 'crt_user_agent', true),
                        'agent_ip' => get_post_meta($post->ID, 'crt_user_ip', true),
                        'post_created' => date('F j, Y g:i a', strtotime($post->post_date)),
                        'post_updated' => date('F j, Y g:i a', strtotime($post->post_modified)),
                    ]
                );
            }
        }
    }
    
    // Define the callback function to register the meta box
    public function crt_submissions_meta_box() {
        $args = new WP_Query([
            'post_type' => 'crt_submissions'
        ]);
        foreach ( $args as $arg ) {
    
            add_meta_box(
                'crt_submission_fields',
                'CRT Submissions',
                [$this, 'crt_meta_box_callback'],
                'crt_submissions',
                'normal',
                'default'
            );
        }
        wp_reset_postdata();
    }
    
    // Define the callback function to display the meta box contents
    public function crt_meta_box_callback( $post, $metabox ) {
        echo '<button class="crt-edit-submissions button button-primary">'. esc_html__('Edit', 'crt-manage') .'</button>';
        foreach (get_post_meta($post->ID) as $key => $value ) {

            $exclude = ['crt_form_id', 'crt_form_name', 'crt_form_page', 'crt_form_page_id', 'crt_user_agent', 'crt_user_ip', 'crt_submission_read_status', '_edit_lock'];

            if (in_array( $key ,$exclude )) {
                continue;
            }

            echo '<div class="crt-submissions-wrap">';
                if ( is_serialized($value[0]) ) {
                

                    if ($value[0]) {
                        $value = unserialize($value[0]);
                    }

                    $prefix = "form_field-";
                    $key_title = !empty($value[2]) ? $value[2] : ucfirst(str_replace($prefix, "", $key));
                    
                    if ( str_contains($key, '_action_') ) {
                        $prefix = '_action_crt_form_builder_';
                        $label = ucfirst(substr($key, strpos($key, $prefix) + strlen($prefix)));
                        echo '<label>'. $label .'</label>';
                        echo '<p class="notice notice-'. $value['status'] .'">'. ucfirst($value['message']) .'</p>';
                    } elseif ( 'file' == $value[0] ) {
                        echo '<label for="'. $key .'">'.  $key_title .' </label>';
                        if ( is_array($value[1]) ) {
                            foreach ( $value[1] as $index => $file ) {
                                echo '<a  id="'. $key . '_' . $index .'" target="_blank" href="'. $file .'">'. $file .'</a>';
                            }
                        }
                    } elseif ( 'textarea' == $value[0] ) {
                        echo '<label for="'. $key .'">'.  $key_title .' </label>';
                        echo '<textarea   id="'. $key .'">'. $value[1] .'</textarea>';
                    } else {
                        if ( $value[0] === 'radio' || $value[0] === 'checkbox' ) {
                            echo '<label for="'. $key .'" class="'. $key .'">'.  $key_title .' </label>';
                            foreach($value[1] as $index => $item ) {
                                $checked = $item[1] == 'true' ? 'checked' : '';
                                echo '<input class="crt-inline"  type="'. $value[0] .'" name="'. $item[2] .'" id="'. $item[3] .'" value="'. $item[0] .'" '. $checked .'>';
                                echo '<label class="crt-inline" for="'. $item[2] .'">'.  $item[0] .' </label>';
                            }
                        } else {
                            if ( $value[0] == 'select' ) {
                                if ( is_array($value[1]) ) {
                                    $value[1] = implode(",", $value[1]);
                                }

                                echo '<label for="'. $key .'">'.  $key_title .' </label>';
                                echo '<input  type="text" id="'. $key .'" value="'. $value[1] .'">';

                            } else {
                                echo '<label for="'. $key .'">'.  $key_title .' </label>';
                                echo '<input  type="'. $value[0] .'" id="'. $key .'" value="'. $value[1] .'">';
                            }
                        }
                    }
                } else {
                    $prefix = "form_field-";
                    $key_title = !empty($value[2]) ? $value[2] : ucfirst(str_replace($prefix, "", $key));
                    
                    echo '<label for="'. $key .'">'.  $key_title .' </label>';
                    echo '<input  type="text" id="'. $key .'" value="'. $value[0] .'">';
                }
            echo '</div>';
        }
    
        // Display the form field for the custom meta field
    }
    
    public function crt_submissions() {
        $args = [
            'labels' => [
                'name' => __( 'Submissions' ),
                'singular_name' => __( 'Submission' )
            ],
            'show_in_menu' => 'crt-manage',
            'public' => false,
            'show_ui' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => array('slug' => 'crt_submissions'),
            'supports' => array( '' ),
            'register_meta_box_cb' => [$this, 'crt_submissions_meta_box']
        ];
        
        register_post_type( 'crt_submissions', $args);
    }
    
    public function crt_submissions_custom_columns($columns) {
        // Remove the default columns
        unset($columns['title']);
        unset($columns['author']);
        unset($columns['categories']);
        unset($columns['date']);
    
        // Add new custom columns
        $columns['main'] = __('Main', 'crt-manage');
        $columns['action_status'] = __('Action Status', 'crt-manage');
        $columns['form_id'] = __('Form', 'crt-manage');
        $columns['page'] = __('Page', 'crt-manage');
        $columns['post_id'] = __('ID', 'crt-manage');
        $columns['read_status'] = __('Read Status');
        $columns['date'] = __('Date', 'crt-manage');
    
        return $columns;
    }
    
   public function crt_submissions_sortable_columns($columns) {
        $columns['read_status'] = 'read_status';
        return $columns;
    }
    
    public function crt_submissions_custom_column_content($column, $post_id) {
        $submission = get_post($post_id);
        $submission_meta = get_post_meta($post_id);
        $action_status = 'success';
    
        foreach ($submission_meta as $key => $value) {
            $check_value = isset($value[0]) ? unserialize($value[0]) : null;

            if ( str_contains($key, 'form_field-email') || (is_array($check_value) && isset($check_value[0]) && 'email' == $check_value[0]) ) {
                $main_key = $key;
            }

            if ( str_contains($key, '_action_') ) {
                if (  str_contains($value[0], 'error') ) {
                    $action_status = 'error';
                }
            }
        }
    
        switch ($column) {
            case 'main':
                // Link to view
                echo sprintf(
                    '<a href="%s" title="%s">%s</a>',
                    esc_url(admin_url('post.php?post=' . $post_id . '&action=edit')),
                    __('View', 'crt-manage'),
                    __(get_post_meta($post_id, $main_key, true)[1], 'crt-manage')
                );
                break;
    
            case 'action_status':
                // Replace 'custom_categories_key' with the post meta key
                echo $action_status;
                break;

            case 'form_id':
                echo '<a href="'. admin_url('post.php?post=' . get_post_meta($post_id, 'crt_form_page_id', true) . '&action=elementor') .'" target="_blank">';
                    echo get_post_meta($post_id, 'crt_form_name', true);
                echo '</a>';
                break;
            
            case 'page':
                echo '<a href="'. get_permalink( get_post_meta($post_id, 'crt_form_page_id', true)) .'" target="_blank">';
                    echo get_post_meta($post_id, 'crt_form_page', true);
                echo '</a>';
                break;
    
            case 'post_id':
                // Replace 'custom_date_key' with the post meta key
                echo $submission->ID;
                break;

            case 'read_status':
                $read_status = get_post_meta($post_id, 'crt_submission_read_status', true);
    
                if ($read_status == '1') {
                    echo '<span class="crt-button crt-submission-read">'. __('Read') .'</span>';
                } else {
                    echo '<span class="crt-button crt-submission-unread">'. __('Unread') .'</span>';
                }
                break;
    
            case 'custom_date':
                // Replace 'custom_date_key' with the post meta key
                echo get_post_meta($post_id, 'custom_date_key', true);
                break;
        }
    }
    
    public function crt_submissions_update_read_status() {
        if (!isset($_POST['post_id']) || !isset($_POST['read_status']) || !wp_verify_nonce($_POST['nonce'], 'crt-submissions-js')) {
            wp_send_json_error('Invalid request');
        }
    
        $post_id = intval($_POST['post_id']);
        $read_status = $_POST['read_status'] === '1' ? '1' : '0';
    
        update_post_meta($post_id, 'crt_submission_read_status', $read_status);
    
        wp_send_json_success();
    }

    public function crt_submissions_mark_as_read($screen) {
        if (is_admin()) {
            $screen = get_current_screen();
    
            // Check if the current screen is the post editor for 'crt_submissions'
            if ($screen && $screen->base == 'post' && $screen->post_type == 'crt_submissions') {
                if (isset($_GET['post']) && !empty($_GET['post'])) {
                    $post_id = intval($_GET['post']);
                    $post = get_post($post_id);
    
                    // Update the post's read status to '1' (read)
                    update_post_meta($post_id, 'crt_submission_read_status', '1');
                }
            }
        }
    }

    public function crt_submissions_row_actions($actions, $post) {
        // Check if the current post type is 'crt_submissions'
        if ($post->post_type === 'crt_submissions') {
            // Remove the 'Edit' and 'Quick Edit' actions
            unset($actions['edit']);
            unset($actions['inline hide-if-no-js']);
    
            // Add a custom action
            $actions['view'] = sprintf(
                '<a href="%s" title="%s">%s</a>',
                esc_url(admin_url('post.php?post=' . $post->ID . '&action=edit')),
                __('View', 'crt-manage'),
                __('View', 'crt-manage')
            );
        }
    
        return $actions;
    }
    
    public function crt_submissions_remove_bulk_edit($actions) {
        // Remove the 'edit' action from bulk actions
        unset($actions['edit']);
        return $actions;
    }
    
    public function crt_submissions_remove_bulk_edit_filter() {
        $screen = get_current_screen();
    
        // Check if the current screen is the 'crt_submissions' list page
        if ($screen->id === 'edit-crt_submissions') {
            // Apply the filter to remove the 'Edit' option from bulk actions
            add_filter('bulk_actions-' . $screen->id, [$this, 'crt_submissions_remove_bulk_edit']);
        }
    }
    
    // public function crt_submissions_orderby($query) {
    //     if (!is_admin() || !$query->is_main_query()) {
    //         return;
    //     }
    
    //     $orderby = $query->get('orderby');
    
    //     if ('read_status' == $orderby) {
    //         $query->set('meta_key', 'crt_submission_read_status');
    //         $query->set('orderby', 'meta_value_num');
    //     }
    // }
}

new CRT_Form_Submissions();