<?php
/**
 * Processor for options from raw data
 */
$pre = self::$prefix_pre;

if(!empty($options)) {
    $licenses = !empty(get_option('crt_manage_license')) ? json_decode(get_option('crt_manage_license')) : array();
    foreach ($options as $section => $control) {
        $section_args = explode('-', $section);
        $section_key = $section_args[0];
        $section_is_pre = isset($section_args[1]) ? $section_args[1]:'';
        $controls = $control['control'];
        $title = $control['title'];
        $panel = $control['panel'];
        $active_callback = isset($control['active_callback']) ? $control['active_callback']:'';

        $default_args  = array(
            'panel'    => $panel,
            'title'    => $title,
            'priority' => crt_manage_priority_section($section_key),
        );

        // Premium
        if($section_is_pre == $pre) {
            if(in_array($this->crt_manage_theme, $licenses)):
                $wp_customize->add_section(
                    $section_key,
                    $default_args
                );
            else:
                $wp_customize->add_section(
                    new Crt_Manage_Custom_Section(
                        $wp_customize,
                        $section_key,
                        array_merge(
                            $default_args,
                            array(
                                'button_text'      => __( 'Buy Pre', 'crt-manage' ),
                                'url'              => CRT_MANAGE_URL_DEMO,
                            )
                        )
                    )
                );
            endif;
        } else {
            $wp_customize->add_section(
                $section_key,
                $default_args
            );
        }
        if(!empty($controls)) {
            foreach ($controls as $control_key => $control_option) {
                $type = $control_option['type'];

                if($type == 'text') {
                    $wp_customize->add_setting(
                        $control_key,
                        array(
                            'default'           => isset($control_option['def']) ? $control_option['def']:'',
                            'sanitize_callback' => 'wp_kses_post',
                        )
                    );
                    $wp_customize->add_control(
                        $control_key,
                        array(
                            'label'           => isset($control_option['label']) ? $control_option['label']:esc_html__( 'Title', 'crt-manage' ),
                            'description'     => isset($control_option['description']) ? $control_option['description']:'',
                            'section'         => $section_key,
                            'settings'        => $control_key,
                            'type'            => $type,
                            'active_callback' => isset($control_option['active_callback']) ? $control_option['active_callback']:$active_callback,
                        )
                    );
                } elseif ($type == 'textarea') {
                    $wp_customize->add_setting(
                        $control_key,
                        array(
                            'default'           => isset($control_option['def']) ? $control_option['def']:'',
                            'sanitize_callback' => 'wp_kses_post',
                        )
                    );
                    $wp_customize->add_control(
                        $control_key,
                        array(
                            'label'           => isset($control_option['label']) ? $control_option['label']:esc_html__( 'Title', 'crt-manage' ),
                            'section'         => $section_key,
                            'settings'        => $control_key,
                            'type'            => $type,
                            'active_callback' => isset($control_option['active_callback']) ? $control_option['active_callback']:$active_callback,
                        )
                    );
                } elseif($type == 'toggle_switch') {
                    $wp_customize->add_setting(
                        $control_key,
                        array(
                            'default'           => isset($control_option['def']) ? $control_option['def']:false,
                            'sanitize_callback' => 'crt_manage_sanitize_switch',
                        )
                    );
                    $wp_customize->add_control(
                        new $control_option['class'](
                            $wp_customize,
                            $control_key,
                            array(
                                'label'    => isset($control_option['label']) ? $control_option['label']:esc_html__( 'Title', 'crt-manage' ),
                                'section'  => $section_key,
                                'settings' => $control_key,
                                'active_callback' => isset($control_option['active_callback']) ? $control_option['active_callback']:'',
                            )
                        )
                    );
                    if(isset($control_option['selective_refresh'])) {
                        if ( isset( $wp_customize->selective_refresh ) ) {
                            $wp_customize->selective_refresh->add_partial(
                                $control_key,
                                array(
                                    'selector' => isset($control_option['selector']) ? $control_option['selector']:'',
                                    'settings' => $control_key,
                                )
                            );
                        }
                    }
                } elseif ($type == 'select_multiple') {
                    $wp_customize->add_setting(
                        $control_key,
                        array(
                            'sanitize_callback' => 'crt_manage_sanitize_array',
                        )
                    );
                    $wp_customize->add_control(
                        new $control_option['class'](
                            $wp_customize,
                            $control_key,
                            array(
                                'label'           => isset($control_option['label']) ? $control_option['label']:esc_html__( 'Title', 'crt-manage' ),
                                'description'     => isset($control_option['description']) ? $control_option['description']:'',
                                'section'         => $section_key,
                                'settings' => $control_key,
                                'choices' => isset($control_option['choices']) ? $control_option['choices']:array(),
                                'height' => general_height_from_count_post(crt_manage_get_post_choices()),
                                'active_callback' => isset($control_option['active_callback']) ? $control_option['active_callback']:$active_callback,
                            )
                        )
                    );
                } elseif ($type == 'select') {
                    $wp_customize->add_setting(
                        $control_key,
                        array(
                            'default'         => isset($control_option['def']) ? $control_option['def']:'',
                            'sanitize_callback' => isset($control_option['sanitize_callback']) ? $control_option['sanitize_callback']:'crt_manage_sanitize_array',
                        )
                    );
                    $wp_customize->add_control(
                        $control_key,
                        array(
                            'type' => 'select',
                            'label'           => isset($control_option['label']) ? $control_option['label']:'',
                            'description'     => isset($control_option['description']) ? $control_option['description']:'',
                            'section'         => $section_key,
                            'settings' => $control_key,
                            'choices' => isset($control_option['choices']) ? $control_option['choices']:array(),
                            'active_callback' => isset($control_option['active_callback']) ? $control_option['active_callback']:$active_callback,
                        )
                    );
                } elseif ($type == 'repeater') {
                    $wp_customize->add_setting(
                        $control_key,
                        array(
                            'default'           => '',
                            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
                        )
                    );
                    $wp_customize->add_control(
                        new Crt_Manage_Customize_Field_Repeater(
                            $wp_customize,
                            $control_key,
                            array_merge(
                                $control_option['repeater_fields'],
                                array(
                                    'active_callback' => isset($control_option['active_callback']) ? $control_option['active_callback']:$active_callback,
                                )
                            ),
                        )
                    );
                } elseif ($type == 'image') {
                    $wp_customize->add_setting(
                        $control_key,
                        array(
                            'sanitize_callback' => 'sanitize_text_field',
                        )
                    );
                    $wp_customize->add_control(
                        new WP_Customize_Image_Control(
                            $wp_customize,
                            $control_key,
                            array(
                                'label'      => isset($control_option['label']) ? $control_option['label']:'',
                                'section'    => $section_key,
                                'active_callback' => $active_callback,
                            )
                        )
                    );
                } elseif($type == 'radio_image') {
                    $wp_customize->add_setting(
                        $control_key, array(
                            'default' => isset($control_option['def']) ? $control_option['def']:'',
                        )
                    );

                    $wp_customize->add_control(
                        new Crt_Manage_Customize_Control_Radio_Image(
                            $wp_customize, $control_key, array(
                                'label' => isset($control_option['label']) ? $control_option['label']:'',
                                'section' => $section_key,
                                'choices' => isset($control_option['choices']) ? $control_option['choices']:array(),
                                'active_callback' => $active_callback,
                            )
                        )
                    );
                } elseif($type == 'radio') {
                    $wp_customize->add_setting( $control_key, array(
                        'default' => isset($control_option['def']) ? $control_option['def']:'',
                        'sanitize_callback' => 'sanitize_text_field',
                    ) );

                    $wp_customize->add_control( $control_key, array(
                        'type' => 'radio',
                        'section' => $section_key,
                        'label' => isset($control_option['label']) ? $control_option['label']:'',
                        'description' => isset($control_option['description']) ? $control_option['description']:'',
                        'choices' => isset($control_option['choices']) ? $control_option['choices']:array(),
                        'active_callback' => isset($control_option['active_callback']) ? $control_option['active_callback']:$active_callback,
                    ));
                } elseif($type == 'checkbox') {
                    $wp_customize->add_setting(
                        $control_key,
                        array(
                            'default'           => isset($control_option['def']) ? $control_option['def']:false,
                            'sanitize_callback' => 'crt_manage_sanitize_checkbox',
                        )
                    );
                    $wp_customize->add_control(
                        $control_key,
                        array(
                            'label' => isset($control_option['label']) ? $control_option['label']:'',
                            'description' => isset($control_option['description']) ? $control_option['description']:'',
                            'section'         => $section_key,
                            'type'            => 'checkbox',
                            'active_callback' => isset($control_option['active_callback']) ? $control_option['active_callback']:$active_callback,
                        )
                    );
                } elseif ($type == 'color') {
                    $wp_customize->add_setting( $control_key, array(
                        'capability'        => 'edit_theme_options',
                        'default'           => isset($control_option['def']) ? $control_option['def']:'',
                    ) );
                    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $control_key,
                        array(
                            'label'    => isset($control_option['label']) ? $control_option['label']:'',
                            'section'  => $section_key,
                            'settings' => $control_key,
                            'active_callback' => isset($control_option['active_callback']) ? $control_option['active_callback']:$active_callback,
                        )
                    ));
                } elseif ($type == 'line') {
                    $wp_customize->add_setting(
                        $control_key
                    );
                    $wp_customize->add_control(
                        new Crt_Manage_Customize_Horizontal_Line(
                            $wp_customize,
                            $control_key,
                            array(
                                'section'  => $section_key,
                                'settings' => $control_key,
                            )
                        )
                    );
                }  elseif ($type == 'heading') {
                    $wp_customize->add_setting(
                        $control_key
                    );
                    $wp_customize->add_control(
                        new Crt_Manage_Customize_Heading(
                            $wp_customize,
                            $control_key,
                            array(
                                'label'    => isset($control_option['label']) ? $control_option['label']:'',
                                'section'  => $section_key,
                                'settings' => $control_key,
                            )
                        )
                    );
                } elseif ($type == 'tab') {
                    $wp_customize->add_setting(
                        $control_key, array(
                            'sanitize_callback' => 'sanitize_text_field',
                        )
                    );
                    $wp_customize->add_control(
                        new Crt_Manage_Customizer_Tabs_Control(
                            $wp_customize, $control_key, array(
                                'section' => $section_key,
                                'tabs'    => isset($control_option['tabs']) ? $control_option['tabs']:array()
                            )
                        )
                    );
                }
            }
        }
    }
}
