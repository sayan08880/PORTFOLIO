<?php
/**
 * Active Callbacks
 *
 * @package Crt_Manage
 */
$crt_manage_active_callback_file = dirname( __FILE__, 2 ). '/customizer/front-page-options/' .$this->crt_manage_theme.'/active-callback/active-callback.php';
if(file_exists($crt_manage_active_callback_file)) {
    require_once $crt_manage_active_callback_file;
}