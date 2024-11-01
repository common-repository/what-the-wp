<?php
/*
Plugin Name: What The WP?
Plugin URI: http://00plugin.net/what-the-wp/
Description: Simple audit and traceablity for WordPress DevOps
Version: 0.5
Contributors: tsewlliw
Author: Will West
Text Domain: what-the-wp
*/

call_user_func( function() {
    require __DIR__ . '/functions.php';
    foreach ( glob( __DIR__ . '/classes/class-*.php') as $class_file ) {
        require $class_file;
    }
    wtwp_boot();
} );
