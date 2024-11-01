<?php

class WTWP_Audit {
    public static function boot() {
        self::_add_action('transition_post_status', 10, 3);
        self::_add_action('activated_plugin', 10, 2);
        self::_add_action('deactivated_plugin', 10, 2);
        self::_add_action('switch_theme', 10, 2);
        self::_add_action('wp_login', 10, 2);
        self::_add_action('add_user_role', 10, 2);
        self::_add_action('remove_user_role', 10, 2);
        self::_add_action('set_user_role', 10, 3);
        self::_add_action('granted_super_admin', 10, 1);
        self::_add_action('revoked_super_admin', 10, 1);
        self::_add_action('user_register', 10, 1);
        self::_add_action('profile_update', 10, 1);
        self::_add_action('deleted_user', 10, 2);
        self::_add_action('retrieve_password_key', 10, 1);
        self::_add_action('upgrader_process_complete', 10, 2);
        self::_add_action('upgrader_process_complete_update_core', 10, 2);
        self::_add_action('upgrader_process_complete_update_plugin', 10, 2);
        self::_add_action('upgrader_process_complete_update_theme', 10, 2);

        add_action('wtwp_audit_event', array(__CLASS__, 'error_log_event'), 10, 2);
        if (function_exists('newrelic_record_custom_event')) {
            add_action('wtwp_audit_event', array(__CLASS__, 'newrelic_record_event'), 10, 2);
        }
    }

    private static function _add_action($action, $priority, $nargs) {
        add_action($action, array(__CLASS__, $action), $priority, $nargs);
    }

    public static function add_user_role($user_id, $role) {
        self::audit_event(__FUNCTION__, array(
            'user_id' => $user_id,
            'role' => $role,
        ));
    }

    public static function remove_user_role($user_id, $role) {
        self::audit_event(__FUNCTION__, array(
            'user_id' => $user_id,
            'role' => $role,
        ));
    }

    public static function set_user_role($user_id, $role, $old_roles) {
        self::audit_event(__FUNCTION__, array(
            'user_id' => $user_id,
            'role' => $role,
            'old_roles' => $old_roles,
        ));
    }

    public static function profile_update($user_id) {
        self::audit_event(__FUNCTION__, array(
            'user_id' => $user_id,
        ));
    }

    public static function user_register($user_id) {
        self::audit_event(__FUNCTION__, array(
            'user_id' => $user_id,
        ));
    }

    public static function deleted_user($user_id, $reassign) {
        self::audit_event(__FUNCTION__, array(
            'user_id' => $user_id,
            'reassign' => $reassign,
        ));
    }

    public static function retrieve_password_key($user_login) {
        self::audit_event(__FUNCTION__, array(
            'user_login' => $user_login,
            'remote_addr' => $_SERVER['REMOTE_ADDR'],
        ));
    }

    public static function revoked_super_admin($user_id) {
        self::audit_event(__FUNCTION__, array(
            'user_id' => $user_id,
        ));
    }

    public static function granted_super_admin($user_id) {
        self::audit_event(__FUNCTION__, array(
            'user_id' => $user_id,
        ));
    }

    public static function wp_login($user_login, $user) {
        self::audit_event(__FUNCTION__, array(
            'user_login' => $user_login,
            'user_id' => $user->ID,
            'remote_addr' => $_SERVER['REMOTE_ADDR'],
        ));
    }

    public static function transition_post_status($new_status, $old_status, $post) {
        self::audit_event(__FUNCTION__, array(
            'post_id' => $post->ID,
            'old_status' => $old_status,
            'new_status' => $new_status,
        ));
    }

    public static function activated_plugin($plugin, $network_activation) {
        self::audit_event(__FUNCTION__, array(
            'plugin' => $plugin,
            'network_activation' => $network_activation,
        ));
    }

    public static function deactivated_plugin($plugin, $network_activation) {
        self::audit_event(__FUNCTION__, array(
            'plugin' => $plugin,
            'network_activation' => $network_activation,
        ));
    }

    public static function switch_theme($new_name, $new_theme) {
        self::audit_event(__FUNCTION__, array(
            'new_name' => $name_name,
        ));
    }

    public static function upgrader_process_complete($upgrader, $info) {
        $action = $info['action'];
        $type = $info['type'];
        do_action("upgrader_process_complete_{$action}_{$type}", $upgrader, $info);
    }

    public static function upgrader_process_complete_update_core($upgrader, $info) {
        global $wp_version, $wp_db_version;
        $info['wp_version'] = $wp_version;
        $info['wp_db_version'] = $wp_db_version;
        self::audit_event(__FUNCTION__, $info);
    }

    public static function upgrader_process_complete_update_plugin($upgrader, $info) {
        $plugins = $info['plugins'];
        unset ($info['plugins']);
        foreach ($plugins as $plugin) {
            $info['plugin'] = $plugin;
            self::audit_event(__FUNCTION__, $info);
        }
    }

    public static function upgrader_process_complete_update_theme($upgrader, $info) {
        $themes = $info['themes'];
        unset ($info['themes']);
        foreach ($themes as $theme) {
            $info['theme'] = $theme;
            self::audit_event(__FUNCTION__, $info);
        }
    }

    public static function audit_event($event, $attrs) {
        $attrs['blog_id'] = get_current_blog_id();
        $u = wp_get_current_user();
        if ($u) {
            $attrs['current_user_id'] = $u->ID;
        }
        do_action('wtwp_audit_event', $event, $attrs);
    }

    public static function error_log_event($event, $attrs) {
        error_log("audit:$event " . json_encode($attrs));
    }

    public static function newrelic_record_event($event, $attrs) {
        $attrs['event'] = $event;
        newrelic_record_custom_event(__CLASS__, $attrs);
    }

}
