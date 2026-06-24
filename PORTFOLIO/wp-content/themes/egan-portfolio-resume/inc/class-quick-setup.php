<?php

if(isset($_GET['home'])) {
    if($_GET['home'] == 'left') {
        set_theme_mod('crt_manage_hero_layout', 'left-img');
    }
    if($_GET['home'] == 'center') {
        set_theme_mod('crt_manage_hero_layout', 'center-img');
    }
    if($_GET['home'] == 'right') {
        set_theme_mod('crt_manage_hero_layout', 'right-img');
    }
    if($_GET['home'] == 'horizontal') {
        set_theme_mod('crt_manage_header_show_left_nav', false);
        set_theme_mod('crt_manage_post_latest_layout', 'masonry-3-columns');
    }
    if($_GET['home'] == 'sidebar') {
        set_theme_mod('crt_manage_header_show_left_nav', true);
        set_theme_mod('crt_manage_post_latest_layout', 'masonry-2-columns');
    }
}
?>

