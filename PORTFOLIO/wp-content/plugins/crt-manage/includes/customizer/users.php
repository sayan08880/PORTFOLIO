<?php
defined('ABSPATH') or die('Sorry guys!');
/**
 * @class CRT_Manage_Users
 */
class CRT_Manage_Users {

    private $user_id_being_edited;

    public $user_socials = array(
        'crt_manage_user_social_facebook' => 'Facebook',
        'crt_manage_user_social_x-twitter' => 'Twitter',
        'crt_manage_user_social_google' => 'Google',
        'crt_manage_user_social_youtube' => 'Youtube',
        'crt_manage_user_social_email' => 'Email',
        'crt_manage_user_social_instagram' => 'Instagram',
        'crt_manage_user_social_pinterest' => 'Pinterest',
        'crt_manage_user_social_linkedin' => 'LinkedIn',
    );

    /**
     * Initialize all the things
     *
     * @since 1.0.0
     */
    public function __construct() {

        // Actions
        add_action( 'admin_init',				 array( $this, 'admin_init'               )        );
        add_action( 'show_user_profile',		 array( $this, 'edit_user_profile'        )        );
        add_action( 'edit_user_profile',		 array( $this, 'edit_user_profile'        )        );
        add_action( 'personal_options_update',	 array( $this, 'edit_user_profile_update' )        );
        add_action( 'edit_user_profile_update',	 array( $this, 'edit_user_profile_update' )        );

        // Filters
        add_filter( 'get_avatar_data',			 array( $this, 'get_avatar_data'               ), 10, 2 );
        add_filter( 'get_avatar',				 array( $this, 'get_avatar'               ), 10, 6 );
        add_filter( 'avatar_defaults',			 array( $this, 'avatar_defaults'          )        );
    }

    /**
     * Start the admin engine.
     *
     * @since 1.0.0
     */
    public function admin_init() {

        // Register/add the Discussion setting to restrict avatar upload capabilites
        register_setting( 'discussion', 'crt_manage_user_avatars_caps', array( $this, 'sanitize_options' ) );
        add_settings_field( 'crt-manage-user-avatars-caps', esc_html__( 'Local Avatar Permissions', 'crt-manage' ), array( $this, 'avatar_settings_field' ), 'discussion', 'avatars' );
    }

    /**
     * Discussion settings option
     *
     * @since 1.0.0
     * @param array $args [description]
     */
    public function avatar_settings_field( $args ) {
        $options = get_option( 'crt_manage_user_avatars_caps' );

        $crt_manage_user_avatars_caps = ! empty( $options['crt_manage_user_avatars_caps'] ) ? 1 : 0;

        ?>
        <label for="crt_manage_user_avatars_caps">
            <input type="checkbox" name="crt_manage_user_avatars_caps" id="crt_manage_user_avatars_caps" value="1" <?php checked( $crt_manage_user_avatars_caps, 1 ); ?>/>
            <?php esc_html_e( 'Only allow users with file upload capabilities to upload local avatars (Authors and above)', 'crt-manage' ); ?>
        </label>
        <?php
    }

    /**
     * Sanitize the Discussion settings option
     *
     * @since 1.0.0
     * @param array $input
     * @return array
     */
    public function sanitize_options( $input ) {
        $new_input['crt_manage_user_avatars_caps'] = empty( $input ) ? 0 : 1;
        return $new_input;
    }

    /**
     * Filter the normal avatar data and show our avatar if set.
     *
     * @since 1.0.6
     * @param array $args        Arguments passed to get_avatar_data(), after processing.
     * @param mixed $id_or_email The avatar to retrieve. Accepts a user_id, Gravatar MD5 hash,
     *                           user email, WP_User object, WP_Post object, or WP_Comment object.
     * @return array             The filtered avatar data.
     */
    public function get_avatar_data( $args, $id_or_email ) {
        if ( ! empty( $args['force_default'] ) ) {
            return $args;
        }

        global $wpdb;

        $return_args = $args;

        // Determine if we received an ID or string. Then, set the $user_id variable.
        if ( is_numeric( $id_or_email ) && 0 < $id_or_email ) {
            $user_id = (int) $id_or_email;
        } elseif ( is_object( $id_or_email ) && isset( $id_or_email->user_id ) && 0 < $id_or_email->user_id ) {
            $user_id = $id_or_email->user_id;
        } elseif ( is_object( $id_or_email ) && isset( $id_or_email->ID ) && isset( $id_or_email->user_login ) && 0 < $id_or_email->ID ) {
            $user_id = $id_or_email->ID;
        } elseif ( is_string( $id_or_email ) && false !== strpos( $id_or_email, '@' ) ) {
            $_user = get_user_by( 'email', $id_or_email );

            if ( ! empty( $_user ) ) {
                $user_id = $_user->ID;
            }
        }

        if ( empty( $user_id ) ) {
            return $args;
        }

        $user_avatar_url = null;

        // Get the user's local avatar from usermeta.
        $local_avatars = get_user_meta( $user_id, 'crt_manage_user_avatar', true );

        if ( empty( $local_avatars ) || empty( $local_avatars['full'] ) ) {
            // Try to pull avatar from WP User Avatar.
            $wp_user_avatar_id = get_user_meta( $user_id, $wpdb->get_blog_prefix() . 'user_avatar', true );
            if ( ! empty( $wp_user_avatar_id ) ) {
                $wp_user_avatar_url = wp_get_attachment_url( intval( $wp_user_avatar_id ) );
                $local_avatars = array( 'full' => $wp_user_avatar_url );
                update_user_meta( $user_id, 'crt_manage_user_avatar', $local_avatars );
            } else {
                // We don't have a local avatar, just return.
                return $args;
            }
        }

        /**
         * Filter the default avatar size during upload.
         * @param $size int The default avatar size. Default 96.
         * @param $args array The default avatar args available at the time of this filter.
         */
        $size = apply_filters( 'crt_manage_user_avatars_default_size', (int) $args['size'], $args );

        // Generate a new size
        if ( empty( $local_avatars[$size] ) ) {

            $upload_path      = wp_upload_dir();
            $avatar_full_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $local_avatars['full'] );
            $image            = wp_get_image_editor( $avatar_full_path );
            $image_sized      = null;

            if ( ! is_wp_error( $image ) ) {
                $image->resize( $size, $size, true );
                $image_sized = $image->save();
            }

            // Deal with original being >= to original image (or lack of sizing ability).
            if ( empty( $image_sized ) || is_wp_error( $image_sized ) ) {
                $local_avatars[ $size ] = $local_avatars['full'];
            } else {
                $local_avatars[ $size ] = str_replace( $upload_path['basedir'], $upload_path['baseurl'], $image_sized['path'] );
            }

            // Save updated avatar sizes
            update_user_meta( $user_id, 'crt_manage_user_avatar', $local_avatars );

        } elseif ( substr( $local_avatars[ $size ], 0, 4 ) != 'http' ) {
            $local_avatars[ $size ] = home_url( $local_avatars[ $size ] );
        }

        if ( is_ssl() ) {
            $local_avatars[ $size ] = str_replace( 'http:', 'https:', $local_avatars[ $size ] );
        }

        $user_avatar_url = $local_avatars[ $size ];

        if ( $user_avatar_url ) {
            $return_args['url'] = $user_avatar_url;
            $return_args['found_avatar'] = true;
        }

        /**
         * Allow filtering the avatar data that we are overriding.
         *
         * @since 1.0.6
         *
         * @param array $return_args The list of user avatar data arguments.
         */
        return apply_filters( 'crt_manage_user_avatar_data', $return_args );
    }

    /**
     * Add a backwards compatible hook to further filter our customized avatar HTML.
     *
     * @since 1.0.0
     *
     * @param string $avatar      HTML for the user's avatar.
     * @param mixed  $id_or_email The avatar to retrieve. Accepts a user_id, Gravatar MD5 hash,
     *                            user email, WP_User object, WP_Post object, or WP_Comment object.
     * @param int    $size        Square avatar width and height in pixels to retrieve.
     * @param string $default     URL for the default image or a default type. Accepts '404', 'retro', 'monsterid',
     *                            'wavatar', 'indenticon', 'mystery', 'mm', 'mysteryman', 'blank', or 'gravatar_default'.
     * @param string $alt         Alternative text to use in the avatar image tag.
     * @param array  $args        Arguments passed to get_avatar_data(), after processing.
     * @return string             The filtered avatar HTML.
     */
    public function get_avatar( $avatar, $id_or_email, $size = 96, $default = '', $alt = false, $args = array() ) {
        /**
         * Filter to further customize the avatar HTML.
         *
         * @since 1.0.0
         * @param string $avatar HTML for the user's avatar.
         * @param mixed  $id_or_email The avatar to retrieve. Accepts a user_id, Gravatar MD5 hash,
         *                            user email, WP_User object, WP_Post object, or WP_Comment object.
         * @return string The filtered avatar HTML.
         * @deprecated since 1.0.6
         */
        return apply_filters( 'crt_manage_user_avatar', $avatar, $id_or_email );
    }

    /**
     * Form to display on the user profile edit screen
     *
     * @since 1.0.0
     * @param object $profileuser
     * @return
     */
    public function edit_user_profile( $profileuser ) {
        ?>

        <h2><?php _e( 'Avatar', 'crt-manage' ); ?></h2>
        <table class="form-table">
            <tr>
                <th><label for="crt-manage-user-avatar"><?php esc_html_e( 'Upload Avatar', 'crt-manage' ); ?></label></th>
                <td style="width: 50px;" valign="top">
                    <?php echo get_avatar( $profileuser->ID ); ?>
                </td>
                <td>
                    <?php
                    $options = get_option( 'crt_manage_user_avatars_caps' );
                    if ( empty( $options['crt_manage_user_avatars_caps'] ) || current_user_can( 'upload_files' ) ) {
                        // Nonce security ftw
                        wp_nonce_field( 'crt_manage_user_avatar_nonce', '_crt_manage_user_avatar_nonce', false );

                        // File upload input
                        echo '<input type="file" name="crt-manage-user-avatar" id="basic-local-avatar" />';

                        if ( empty( $profileuser->crt_manage_user_avatar ) ) {
                            echo '<p class="description">' . esc_html__( 'No local avatar is set. Use the upload field to add a local avatar.', 'crt-manage' ) . '</p>';
                        } else {
                            echo '<p><input type="checkbox" name="crt-manage-user-avatar-erase" id="crt-manage-user-avatar-erase" value="1" /><label for="crt-manage-user-avatar-erase">' . esc_html__( 'Delete local avatar', 'crt-manage' ) . '</label></p>';
                            echo '<p class="description">' . esc_html__( 'Replace the local avatar by uploading a new avatar, or erase the local avatar (falling back to a gravatar) by checking the delete option.', 'crt-manage' ) . '</p>';
                        }

                    } else {
                        if ( empty( $profileuser->crt_manage_user_avatar ) ) {
                            echo '<p class="description">' . esc_html__( 'No local avatar is set. Set up your avatar at Gravatar.com.', 'crt-manage' ) . '</p>';
                        } else {
                            echo '<p class="description">' . esc_html__( 'You do not have media management permissions. To change your local avatar, contact the site administrator.', 'crt-manage' ) . '</p>';
                        }
                    }
                    ?>
                </td>
            </tr>
        </table>

        <h2><?php _e( 'Socials', 'crt-manage' ); ?></h2>
        <table class="form-table">
            <?php
                wp_nonce_field( 'crt_manage_user_social_nonce', '_crt_manage_user_social_nonce', false );
            ?>
            <?php
                foreach ($this->user_socials as $name => $label) :
                    $url = get_user_meta($profileuser->ID, $name . '_url');
            ?>
            <tr>
                <th style="padding: 5px 0;font-weight: 400;"><?php esc_html_e( $label, 'crt-manage' ); ?></th>
                <td style="padding: 5px 10px;">
                    <label for="<?php esc_attr_e($name); ?>">
                        <input name="<?php esc_attr_e($name); ?>" type="checkbox" id="<?php esc_attr_e($name); ?>" <?php echo isset($url[0]) ? 'checked':''; ?> >
                        <input name="<?php esc_attr_e($name . '_url') ?>" type="text" placeholder="<?php esc_html_e('URL ' . $label) ?>" value="<?php esc_attr_e($url[0] ? $url[0]:''); ?>" class="regular-text ltr" />
                    </label>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <script type="text/javascript">var form = document.getElementById('your-profile');form.encoding = 'multipart/form-data';form.setAttribute('enctype', 'multipart/form-data');</script>
        <?php
    }

    /**
     * Update the user's avatar setting
     *
     * @since 1.0.0
     * @param int $user_id
     */
    public function edit_user_profile_update( $user_id ) {

        // Check for nonce otherwise bail
        if ( (! isset( $_POST['_crt_manage_user_avatar_nonce'] ) || ! wp_verify_nonce( $_POST['_crt_manage_user_avatar_nonce'], 'crt_manage_user_avatar_nonce' )) && (! isset( $_POST['_crt_manage_user_social_nonce'] ) || ! wp_verify_nonce( $_POST['_crt_manage_user_social_nonce'], 'crt_manage_user_social_nonce' )) )
            return;
        if ( ! empty( $_FILES['crt-manage-user-avatar']['name'] ) ) {

            // Allowed file extensions/types
            $mimes = array(
                'jpg|jpeg|jpe' => 'image/jpeg',
                'gif'          => 'image/gif',
                'png'          => 'image/png',
            );

            // Front end support - shortcode, bbPress, etc
            if ( ! function_exists( 'wp_handle_upload' ) )
                require_once ABSPATH . 'wp-admin/includes/file.php';

            $this->avatar_delete( $this->user_id_being_edited );

            // Need to be more secure since low privelege users can upload
            if ( strstr( $_FILES['crt-manage-user-avatar']['name'], '.php' ) )
                wp_die( 'For security reasons, the extension ".php" cannot be in your file name.' );

            // Make user_id known to unique_filename_callback function
            $this->user_id_being_edited = $user_id;
            $avatar = wp_handle_upload( $_FILES['crt-manage-user-avatar'], array( 'mimes' => $mimes, 'test_form' => false, 'unique_filename_callback' => array( $this, 'unique_filename_callback' ) ) );

            // Handle failures
            if ( empty( $avatar['file'] ) ) {
                switch ( $avatar['error'] ) {
                    case 'File type does not meet security guidelines. Try another.' :
                        add_action( 'user_profile_update_errors', function( $error = 'avatar_error' ){
                            esc_html__("Please upload a valid image file for the avatar.",'crt-manage');
                        } );
                        break;
                    default :
                        add_action( 'user_profile_update_errors', function( $error = 'avatar_error' ){
                            // No error let's bail.
                            if ( empty( $avatar['error'] ) ) {
                                return;
                            }

                            "<strong>".esc_html__("There was an error uploading the avatar:",'crt-manage')."</strong> ". esc_attr( $avatar['error'] );
                        } );
                }
                return;
            }

            // Save user information (overwriting previous)
            update_user_meta( $user_id, 'crt_manage_user_avatar', array( 'full' => $avatar['url'] ) );

        } elseif ( ! empty( $_POST['crt-manage-user-avatar-erase'] ) ) {
            // Nuke the current avatar
            $this->avatar_delete( $user_id );
        }
        foreach ($this->user_socials as $name => $label) {
            if(isset($_POST[$name]) && $_POST[$name] == 'on') {
                update_user_meta( $user_id, $name . '_url',  $_POST[$name . '_url']);
                update_user_meta( $user_id, $name,  $_POST[$name]);
            } else {
                update_user_meta( $user_id, $name . '_url', '' );
                update_user_meta( $user_id, $name, '' );
            }
        }

    }

    /**
     * Remove the custom get_avatar hook for the default avatar list output on
     * the Discussion Settings page.
     *
     * @since 1.0.0
     * @param array $avatar_defaults
     * @return array
     */
    public function avatar_defaults( $avatar_defaults ) {
        remove_action( 'get_avatar', array( $this, 'get_avatar' ) );
        return $avatar_defaults;
    }

    /**
     * Delete avatars based on user_id
     *
     * @since 1.0.0
     * @param int $user_id
     */
    public function avatar_delete( $user_id ) {
        $old_avatars = get_user_meta( $user_id, 'crt_manage_user_avatar', true );
        $upload_path = wp_upload_dir();

        if ( is_array( $old_avatars ) ) {
            foreach ( $old_avatars as $old_avatar ) {
                $old_avatar_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $old_avatar );
                @unlink( $old_avatar_path );
            }
        }

        delete_user_meta( $user_id, 'crt_manage_user_avatar' );
    }

    /**
     * File names are magic
     *
     * @since 1.0.0
     * @param string $dir
     * @param string $name
     * @param string $ext
     * @return string
     */
    public function unique_filename_callback( $dir, $name, $ext ) {
        $user = get_user_by( 'id', (int) $this->user_id_being_edited );
        $name = $base_name = sanitize_file_name( strtolower( $user->display_name ) . '_avatar' );

        $number = 1;

        while ( file_exists( $dir . "/$name$ext" ) ) {
            $name = $base_name . '_' . $number;
            $number++;
        }

        return $name . $ext;
    }

}
new CRT_Manage_Users();