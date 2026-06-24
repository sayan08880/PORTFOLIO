<?php

// Register Post Type
function register_mega_menu_cpt() {
    $args = array(
        'label'				  => esc_html__( 'Mega Menu', 'crt-manage' ),
        'public'              => true,
        'publicly_queryable'  => true,
        'rewrite'             => false,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_nav_menus'   => false,
        'exclude_from_search' => true,
        'capability_type'     => 'post',
        'supports'            => array( 'title', 'editor', 'elementor' ),
        'hierarchical'        => false,
    );

    register_post_type( 'crt_mega_menu', $args );
}

// Convert to Canvas Template
function convert_to_canvas_template( $template ) {
    if ( is_singular('crt_mega_menu') ) {
        $template = CRT_MANAGE_DIR . 'includes/customizer/addons/templates/crt-canvas.php';
    }

    return $template;
}

// Init Mega Menu
function init_mega_menu() {
    register_mega_menu_cpt();
    add_action( 'template_include', 'convert_to_canvas_template', 9999 );
}

add_action('init', 'init_mega_menu', 999);


// Confinue only for Dashboard Screen
if ( !is_admin() ) return;

// Init Actions
add_filter( 'option_elementor_cpt_support', 'add_mega_menu_cpt_support' );
add_filter( 'default_option_elementor_cpt_support', 'add_mega_menu_cpt_support' );
add_action( 'admin_footer', 'render_settings_popup', 10 );
add_action( 'wp_ajax_crt_create_mega_menu_template', 'crt_create_mega_menu_template' );
add_action( 'wp_ajax_crt_save_mega_menu_settings', 'crt_save_mega_menu_settings' );
add_action( 'admin_enqueue_scripts', 'enqueue_scripts' );

// Add Elementor Editor Support
function add_mega_menu_cpt_support( $value ) {
    if ( empty( $value ) ) {
        $value = [];
    }

    return array_merge( $value, ['crt_mega_menu'] ); 
}

// Create Menu Template
function crt_create_mega_menu_template() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'crt-mega-menu-js' )  || !current_user_can( 'manage_options' ) ) {
      return; // Get out of here, the nonce is rotten!
    }

    // $menu_id = intval( $_REQUEST['menu'] );
    // $menu_item_id = intval( $_REQUEST['item'] );
    $menu_item_id = intval( $_POST['item_id'] );
    $mega_menu_id = get_post_meta( $menu_item_id, 'crt-mega-menu-item', true );

    if ( ! $mega_menu_id ) {

        $mega_menu_id = wp_insert_post( array(
            'post_title'  => 'crt-mega-menu-item-' . $menu_item_id,
            'post_status' => 'publish',
            'post_type'   => 'crt_mega_menu',
        ) );

        update_post_meta( $menu_item_id, 'crt-mega-menu-item', $mega_menu_id );

    }

    $edit_link = add_query_arg(
        array(
            'post' => $mega_menu_id,
            'action' => 'elementor',
        ),
        admin_url( 'post.php' )
    );

    wp_send_json([
        'data' => [
            'edit_link' => $edit_link
        ]
    ]);
}

// Render Settings Popup
function render_settings_popup() {
    $screen = get_current_screen();

    if ( 'nav-menus' !== $screen->base ) {
        return;
    }

    ?>

    <div class="crt-mm-settings-popup-wrap">
        <div class="crt-mm-settings-popup">
            <div class="crt-mm-settings-popup-header">
                <span class="crt-mm-popup-logo" style="background:url('<?php echo CRT_MANAGE_URI .'assets/img/logo-256x256.png'; ?>') no-repeat center center / contain;">RE</span>
                <span><?php esc_html_e('Mega Menu', 'crt-manage'); ?></span>
                <span class="crt-mm-popup-title"><?php esc_html_e('Menu Item: ', 'crt-manage'); ?><span></span></span>
                <span class="dashicons dashicons-no-alt crt-mm-settings-close-popup-btn"></span>
            </div>

            <?php $pro_active = 'data-pro-active="true"'; ?>
            
            <div class="crt-mm-settings-wrap" <?php echo $pro_active; ?>>
                <h4><?php esc_html_e('General', 'crt-manage'); ?></h4>
                <div class="crt-mm-setting crt-mm-setting-switcher">
                    <h4><?php esc_html_e('Enable Mega Menu', 'crt-manage'); ?></h4>
                    <input type="checkbox" id="crt_mm_enable">
                    <label for="crt_mm_enable"></label>
                </div>
                <div class="crt-mm-setting">
                    <h4><?php esc_html_e('Mega Menu Content', 'crt-manage'); ?></h4>
                    <button class="button button-primary crt-edit-mega-menu-btn">
                        <i class="eicon-elementor-square" aria-hidden="true"></i>
                        <?php esc_html_e('Edit with Elementor', 'crt-manage'); ?>
                    </button>
                </div>
                <div class="crt-mm-setting">
                    <h4><?php esc_html_e('Dropdown Position', 'crt-manage'); ?></h4>
                    <select id="crt_mm_position">
                        <option value="default"><?php esc_html_e('Default', 'crt-manage'); ?></option>
                        <option value="relative"><?php esc_html_e('Relative', 'crt-manage'); ?></option>
                    </select>
                </div>
                <div class="crt-mm-setting">
                    <h4><?php esc_html_e('Dropdown Width', 'crt-manage'); ?></h4>
                    <select id="crt_mm_width">
                        <option value="default"><?php esc_html_e('Default', 'crt-manage'); ?></option>
                        <option value="stretch"><?php esc_html_e('Fit to Section', 'crt-manage'); ?></option>
                        <option value="full"><?php esc_html_e('Full Width', 'crt-manage'); ?></option>
                        <option value="custom"><?php esc_html_e('Custom', 'crt-manage'); ?></option>
                    </select>
                </div>
                <div class="crt-mm-setting">
                    <h4><?php esc_html_e('Custom Width (px)', 'crt-manage'); ?></h4>
                    <input type="number" id="crt_mm_custom_width" value="600">
                </div>
                <div class="crt-mm-setting">
                    <h4><?php esc_html_e('Mobile Sub Content', 'crt-manage'); ?></h4>
                    <div>
                        <select id="crt_mm_mobile_content">
                            <option value="mega"><?php esc_html_e('Mega Menu', 'crt-manage'); ?></option>
                            <option value="wp-sub"><?php esc_html_e('WordPress Sub Items', 'crt-manage'); ?></option>
                        </select>

                        <div class="crt-mm-pro-radio">
                            <input type="radio" name="mc" checked="checked">
                            <label>Mega Menu</label><br>
                            <input type="radio" name="mc">
                            <label>WordPress Sub Items</label>
                        </div>
                    </div>
                </div>
                <div class="crt-mm-setting">
                    <h4><?php esc_html_e('Mobile Sub Render', 'crt-manage'); ?></h4>
                    <div>
                        <select id="crt_mm_render">
                            <option value="default"><?php esc_html_e('Default', 'crt-manage'); ?></option>
                            <option value="ajax"><?php esc_html_e('Load with AJAX', 'crt-manage'); ?></option>
                        </select>

                        <div class="crt-mm-pro-radio">
                            <input type="radio" name="mr" checked="checked">
                            <label>Default</label><br>
                            <input type="radio" name="mr">
                            <label>Load with AJAX</label>
                        </div>
                    </div>
                </div>

                <br>

                <h4>
                    <?php esc_html_e('Icon', 'crt-manage'); ?>
                </h4>
                <div>
                    <div class="crt-mm-setting crt-mm-setting-icon">
                        <h4><?php esc_html_e('Icon Select', 'crt-manage'); ?></h4>
                        <div><span class="crt-mm-active-icon"><i class="fas fa-ban"></i></span><span><i class="fas fa-angle-down"></i></span></div>
                        <input type="text" id="crt_mm_icon_picker" data-alpha="true" value="">
                    </div>
                    <div class="crt-mm-setting crt-mm-setting-color">
                        <h4><?php esc_html_e('Icon Color', 'crt-manage'); ?></h4>
                        <input type="text" id="crt_mm_icon_color" data-alpha="true" value="rgba(0,0,0,0.6);">
                    </div>
                    <div class="crt-mm-setting">
                        <h4><?php esc_html_e('Icon Size (px)', 'crt-manage'); ?></h4>
                        <input type="number" id="crt_mm_icon_size" value="14">
                    </div>
                </div>

                <br>

                <h4>
                    <?php esc_html_e('Badge', 'crt-manage'); ?>
                </h4>
                <div>
                    <div class="crt-mm-setting">
                        <h4><?php esc_html_e('Badge Text', 'crt-manage'); ?></h4>
                        <input type="text" id="crt_mm_badge_text" value="">
                    </div>
                    <div class="crt-mm-setting crt-mm-setting-color">
                        <h4><?php esc_html_e('Badge Text Color', 'crt-manage'); ?></h4>
                        <input type="text" id="crt_mm_badge_color" data-alpha="true" value="rgba(0,0,0,0.6);">
                    </div>
                    <div class="crt-mm-setting crt-mm-setting-color">
                        <h4><?php esc_html_e('Badge Background Color', 'crt-manage'); ?></h4>
                        <input type="text" id="crt_mm_badge_bg_color" data-alpha="true" value="rgba(0,0,0,0.6);">
                    </div>
                    <div class="crt-mm-setting crt-mm-setting-switcher">
                        <h4><?php esc_html_e('Enable Animation', 'crt-manage'); ?></h4>
                        <input type="checkbox" id="crt_mm_badge_animation">
                        <label for="crt_mm_badge_animation"></label>
                    </div>
                </div>
            </div>

            <div class="crt-mm-settings-popup-footer">
                <button class="button crt-save-mega-menu-btn"><?php esc_html_e('Save', 'crt-manage'); ?></button>
            </div>
        </div>
    </div>

    <!-- Iframe Popup -->
    <div class="crt-mm-editor-popup-wrap">
        <div class="crt-mm-editor-close-popup-btn"><span class="dashicons dashicons-no-alt"></span></div>
        <div class="crt-mm-editor-popup-iframe"></div>
    </div>
    <?php
}

// Save Mega Menu Settings
function crt_save_mega_menu_settings() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'crt-mega-menu-js' )  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }

    if ( isset($_POST['item_settings']) ) {
        update_post_meta( $_POST['item_id'], 'crt-mega-menu-settings', $_POST['item_settings'] );
    }

    wp_send_json_success($_POST['item_settings']);
}

// Get Menu Items Data
function get_menu_items_data( $menu_id = false ) {

    if ( ! $menu_id ) {
        return false;
    }

    $menu = wp_get_nav_menu_object( $menu_id );

    $menu_items = wp_get_nav_menu_items( $menu );

    if ( ! $menu_items ) {
        return false;
    }

    return $menu_items;
}

// Get Mega Menu Item Settings
function get_menu_items_settings() {
    $menu_items = get_menu_items_data( get_selected_menu_id() );

    $settings = [];

    if ( ! $menu_items ) {
        return [];
    } else {
        foreach ( $menu_items as $key => $item_object ) {
            $item_id = $item_object->ID;

            $item_meta = get_post_meta( $item_id, 'crt-mega-menu-settings', true );

            if ( !empty($item_meta) ) {
                $settings[ $item_id ] = $item_meta;
            } else {
                $settings[ $item_id ] = [];
            }
        }
        
        return $settings;
    }
}

/**
* Get the Selected menu ID
* @author Tom Hemsley (https://wordpress.org/plugins/megamenu/)
*/
function get_selected_menu_id() {
    $nav_menus = wp_get_nav_menus( array('orderby' => 'name') );
    $menu_count = count( $nav_menus );
    $nav_menu_selected_id = isset( $_REQUEST['menu'] ) ? (int) $_REQUEST['menu'] : 0;
    $add_new_screen = ( isset( $_GET['menu'] ) && 0 == $_GET['menu'] ) ? true : false;

    $current_menu_id = $nav_menu_selected_id;

    // If we have one theme location, and zero menus, we take them right into editing their first menu
    $page_count = wp_count_posts( 'page' );
    $one_theme_location_no_menus = ( 1 == count( get_registered_nav_menus() ) && ! $add_new_screen && empty( $nav_menus ) && ! empty( $page_count->publish ) ) ? true : false;

    // Get recently edited nav menu
    $recently_edited = absint( get_user_option( 'nav_menu_recently_edited' ) );
    if ( empty( $recently_edited ) && is_nav_menu( $current_menu_id ) ) {
        $recently_edited = $current_menu_id;
    }

    // Use $recently_edited if none are selected
    if ( empty( $current_menu_id ) && ! isset( $_GET['menu'] ) && is_nav_menu( $recently_edited ) ) {
        $current_menu_id = $recently_edited;
    }

    // On deletion of menu, if another menu exists, show it
    if ( ! $add_new_screen && 0 < $menu_count && isset( $_GET['action'] ) && 'delete' == $_GET['action'] ) {
        $current_menu_id = $nav_menus[0]->term_id;
    }

    // Set $current_menu_id to 0 if no menus
    if ( $one_theme_location_no_menus ) {
        $current_menu_id = 0;
    } elseif ( empty( $current_menu_id ) && ! empty( $nav_menus ) && ! $add_new_screen ) {
        // if we have no selection yet, and we have menus, set to the first one in the list
        $current_menu_id = $nav_menus[0]->term_id;
    }

    return $current_menu_id;

}

// Enqueue Scripts and Styles
function enqueue_scripts( $hook ) {

    // Deny if NOT a Menu Page
    if ( 'nav-menus.php' == $hook ) {

        // Color Picker
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker-alpha', CRT_MANAGE_URI.'/assets/js/admin/wp-color-picker-alpha.min.js', ['jquery', 'wp-color-picker'], CRT_MANAGE_VERSION, true );

        // Icon Picker
        wp_enqueue_script( 'crt-iconpicker-js', CRT_MANAGE_URI. '/assets/js/admin/iconpicker/fontawesome-iconpicker.min.js', ['jquery'], CRT_MANAGE_VERSION, true );
        wp_enqueue_style( 'crt-iconpicker-css', CRT_MANAGE_URI. '/assets/js/admin/iconpicker/fontawesome-iconpicker.min.css', CRT_MANAGE_VERSION, true );
        wp_enqueue_style( 'crt-el-fontawesome-css', ELEMENTOR_URL .'assets/lib/font-awesome/css/all.min.css', [], CRT_MANAGE_VERSION );

        // enqueue CSS
        wp_enqueue_style( 'crt-mega-menu-css', CRT_MANAGE_URI . '/assets/css/admin/mega-menu.css', [], CRT_MANAGE_VERSION );

        // enqueue JS
        wp_enqueue_script( 'crt-mega-menu-js', CRT_MANAGE_URI.'/assets/js/admin/mega-menu.js', ['jquery'], CRT_MANAGE_VERSION );

        wp_localize_script( 
            'crt-mega-menu-js',
            'CrtMegaMenuSettingsData',
            [
                'settingsData' => get_menu_items_settings(),
				'nonce' => wp_create_nonce( 'crt-mega-menu-js' ),
            ]
        );

    }

}