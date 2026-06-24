<?php
$crt_manage_dir_ocdi = dirname( __FILE__, 2 ) . '/ocdi/'.$this->crt_manage_theme;
if(file_exists($crt_manage_dir_ocdi)) {
    require_once $crt_manage_dir_ocdi . '/ocdi.php';
}



