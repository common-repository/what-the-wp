<?php

class WTWP_Debug {
    public static function boot() {
        if (!constant('WP_DEBUG')) {
            return;
        }
        add_action('shutdown', array(__CLASS__, 'log_actions'));
        add_action('admin_init', array(__CLASS__, 'log_ajax'));
    }

    public static function log_actions() {

        $delta_t_ms = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

        error_log("debug:time took {$delta_t_ms}ms");

        $should_log = apply_filters('wtwp_debug_should_log_actions', true, array(
            'delta_t_ms' => $delta_t_ms,
        ));

        if (!$should_log) {
            return;
        }


        global $wp_actions;

        foreach ($wp_actions as $action => $count) {
            error_log("debug:flow did [$action] x $count");
        }
    }

    public static function log_ajax() {
        if (!(defined('DOING_AJAX') and DOING_AJAX)) {
            return;
        }

        $should_log = apply_filters('wtwp_debug_should_log_ajax', true, $_REQUEST);

        if (!$should_log) {
            return;
        }

        $req = json_encode($_REQUEST);
        error_log("debug:ajax payload=$req");
    }
}
